<?php namespace Samvol\Inventory\Components;

use Cms\Classes\ComponentBase;
use Samvol\Inventory\Models\Product;
use Samvol\Inventory\Models\Operation;
use Samvol\Inventory\Models\OperationType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Input;
use Log;

class ImportExcel extends ComponentBase
{
    protected const EPSILON = 0.0001;

    protected function normalizeInvNumber($value)
    {
        if ($value === null) {
            return '';
        }

        $value = (string)$value;
        $value = preg_replace('/[\s\x{00A0}\x{202F}\x{2000}-\x{200B}]+/u', '', $value);
        return trim($value);
    }
    public function componentDetails()
    {
        return [
            'name' => 'Импорт Excel',
            'description' => 'Импорт остатков через Excel с проверкой и выводом результатов'
        ];
    }

    // -----------------------------
    // Применение выбранных различий
    // -----------------------------
    public function onApplyDifferences()
    {
        $updates = post('updates', []);
        Log::info('Применение различий — данные с фронта:', ['updates' => $updates]);

        if (empty($updates)) {
            return ['error' => 'Нет выбранных продуктов для обновления'];
        }

        $counteragent = post('counteragent', 'Не указан');

        foreach ($updates as $item) {
            $invNumber = $this->normalizeInvNumber($item['inv_number'] ?? null);
            $excelQuantity = floatval($item['quantity'] ?? 0);
            $price = floatval($item['price'] ?? 0);
            $excelSum = floatval($item['sum'] ?? 0);

            Log::info("Обрабатываем продукт {$invNumber}", [
                'excelQuantity' => $excelQuantity,
                'price' => $price,
                'excelSum' => $excelSum,
            ]);

            if (!$invNumber) continue;

            $product = Product::where('inv_number', $invNumber)->first();
            if (!$product) continue;

            $currentQuantity = $product->calculated_quantity ?? 0;
            $currentSum = $product->calculated_sum ?? 0;

            $deltaQuantity = $excelQuantity - $currentQuantity;
            $deltaSum = $excelSum - $currentSum;

            if (abs($deltaQuantity) < self::EPSILON) {
                $deltaQuantity = 0.0;
            }

            if (abs($deltaSum) < self::EPSILON) {
                $deltaSum = 0.0;
            }

            // Создаём операцию если есть любое изменение
            if ($deltaQuantity != 0.0 || $deltaSum != 0.0) {
                $isIncoming = $deltaSum > 0 || ($deltaSum == 0.0 && $deltaQuantity > 0);
                $operationTypeName = $isIncoming ? 'Импорт приход' : 'Импорт расход';
                $operationType = OperationType::firstOrCreate(['name' => $operationTypeName]);

                $operation = new Operation();
                $operation->type_id = $operationType->id;
                $operation->save();

                // Количество и сумма записываются в pivot
                $pivotQuantity = abs($deltaQuantity); // если не изменилось количество, будет 0
                if ($pivotQuantity < self::EPSILON) {
                    $pivotQuantity = 0.0;
                }

                $pivotSum = $deltaSum != 0
                    ? abs($deltaSum)
                    : ($deltaQuantity > 0
                        ? $excelSum
                        : ($currentSum * abs($deltaQuantity) / max($currentQuantity,1)));

                if ($pivotSum < self::EPSILON) {
                    $pivotSum = 0.0;
                }

                $operation->products()->attach($product->id, [
                    'quantity' => $pivotQuantity,
                    'sum' => $pivotSum,
                    'counteragent' => $counteragent
                ]);

                Log::info("Создана операция для продукта {$invNumber}", [
                    'operationType' => $operationTypeName,
                    'pivotQuantity' => $pivotQuantity,
                    'pivotSum' => $pivotSum,
                ]);
            }

            // Обновляем цену продукта
            $product->price = $price;
            $product->save();
        }

        return ['success' => true, 'toast' => [
            'message' => 'Изменения применены',
            'type' => 'success',
            'timeout' => 4000,
            'position' => 'top-center'
        ]];
    }

    // -----------------------------
    // Скачивание различий в Excel
    // -----------------------------
    public function onDownloadDifferencesExcel()
    {
        $rows = post('rows', []);

        if (!is_array($rows) || empty($rows)) {
            return [
                'toast' => [
                    'message' => 'Немає відмінностей для експорту',
                    'type' => 'info',
                    'timeout' => 3500,
                    'position' => 'top-center'
                ]
            ];
        }

        try {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Differences');

            $headerStyle = [
                'font' => [
                    'bold' => true,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'FFF5F7FA',
                    ],
                ],
            ];

            $diffStyle = [
                'font' => [
                    'bold' => true,
                    'color' => [
                        'argb' => 'FF5B4A00',
                    ],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'FFFFF1C2',
                    ],
                ],
            ];

            $toFloat = function ($value) {
                if ($value === null || $value === '') {
                    return 0.0;
                }

                if (is_numeric($value)) {
                    return (float) $value;
                }

                $normalized = preg_replace('/[\s\x{00A0}\x{202F}]+/u', '', (string) $value);
                $normalized = str_replace(',', '.', $normalized);

                return (float) $normalized;
            };

            $headers = [
                'ID',
                'Інвентарний номер',
                'Найменування',
                'К-сть на складі',
                'К-сть в Excel',
                'Ціна на складі',
                'Ціна в Excel',
                'Сума на складі',
                'Сума в Excel',
            ];

            $sheet->fromArray($headers, null, 'A1');
            $sheet->getStyle('A1:I1')->applyFromArray($headerStyle);

            $rowIndex = 2;
            foreach ($rows as $row) {
                $sheet->fromArray([
                    $row['id'] ?? '',
                    $row['inv_number'] ?? '',
                    $row['name'] ?? '',
                    $row['current_quantity'] ?? '',
                    $row['excel_quantity'] ?? '',
                    $row['current_price'] ?? '',
                    $row['excel_price'] ?? '',
                    $row['current_sum'] ?? '',
                    $row['excel_sum'] ?? '',
                ], null, 'A' . $rowIndex);

                $currentQuantity = $toFloat($row['current_quantity'] ?? 0);
                $excelQuantity = $toFloat($row['excel_quantity'] ?? 0);
                $currentPrice = $toFloat($row['current_price'] ?? 0);
                $excelPrice = $toFloat($row['excel_price'] ?? 0);
                $currentSum = $toFloat($row['current_sum'] ?? 0);
                $excelSum = $toFloat($row['excel_sum'] ?? 0);

                if ($currentQuantity != $excelQuantity) {
                    $sheet->getStyle('D' . $rowIndex . ':E' . $rowIndex)->applyFromArray($diffStyle);
                }

                if ($currentPrice != $excelPrice) {
                    $sheet->getStyle('F' . $rowIndex . ':G' . $rowIndex)->applyFromArray($diffStyle);
                }

                if ($currentSum != $excelSum) {
                    $sheet->getStyle('H' . $rowIndex . ':I' . $rowIndex)->applyFromArray($diffStyle);
                }

                $rowIndex++;
            }

            foreach (range('A', 'I') as $columnLetter) {
                $sheet->getColumnDimension($columnLetter)->setAutoSize(true);
            }

            $tempBasePath = tempnam(storage_path('temp'), 'diff_');
            if ($tempBasePath === false) {
                throw new \RuntimeException('Не удалось создать временный файл');
            }

            $tempXlsxPath = $tempBasePath . '.xlsx';
            $writer = new Xlsx($spreadsheet);
            $writer->save($tempXlsxPath);

            $binary = file_get_contents($tempXlsxPath);
            if ($binary === false) {
                throw new \RuntimeException('Не удалось прочитать временный XLSX');
            }

            @unlink($tempBasePath);
            @unlink($tempXlsxPath);

            return [
                'download' => [
                    'filename' => 'import-differences-' . date('Y-m-d') . '.xlsx',
                    'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'content' => base64_encode($binary),
                ]
            ];
        } catch (\Throwable $e) {
            Log::error('Ошибка экспорта различий в Excel', [
                'message' => $e->getMessage(),
            ]);

            return [
                'toast' => [
                    'message' => 'Не вдалося сформувати Excel файл відмінностей',
                    'type' => 'error',
                    'timeout' => 4500,
                    'position' => 'top-center'
                ]
            ];
        }
    }

    // -----------------------------
    // Импорт Excel
    // -----------------------------
    public function onImportExcel()
    {
        function parseExcelNumber($value) {
            if (!$value) return 0;
            $clean = preg_replace('/[\s\x{00A0}\x{202F}]+/u', '', $value);
            $clean = str_replace(',', '.', $clean);
            return floatval($clean);
        }

        $file = Input::file('excel_file');
        Log::info('$_FILES:', $_FILES);

        if (!$file) {
            Log::info('Файл не получен через Input::file');
            return ['toast'=>['message'=>'Файл не загружен','type'=>'error']];
        }
        Log::info('Файл получен: '.$file->getClientOriginalName());

        $counteragent = Input::get('counteragent', 'Не указан');

        try {
            $spreadsheet = IOFactory::load($file->getRealPath());
            $rows = $spreadsheet->getActiveSheet()->toArray();
            if (empty($rows)) return ['toast' => ['message'=>'Файл пустой','type'=>'error']];

            unset($rows[0]); // пропускаем заголовок

            // ===============================
            // Предзагрузка товаров по inv_number (убираем N+1)
            // ===============================
            $invNumbers = collect($rows)
                ->pluck(2)          // колонка inv_number
                ->map(fn($v) => $this->normalizeInvNumber($v))
                ->filter()
                ->unique()
                ->values();

            $productsByInv = Product::whereIn('inv_number', $invNumbers)
                ->get()
                ->groupBy('inv_number');

            foreach ($productsByInv as $inv => $list) {
                if ($list->count() > 1) {
                    Log::warning('[samvol] import: duplicate inv_number in DB', [
                        'inv_number' => $inv,
                        'product_ids' => $list->pluck('id')->values()->all()
                    ]);
                }
            }

            $products = $productsByInv->map(function($list) {
                return $list->first();
            });

            $differences = [];
            $newProducts = [];
            $newCount = 0;

            foreach ($rows as $row) {
                if (empty($row[0])) continue;

                $name       = trim($row[0]);
                $unit       = trim($row[1] ?? 'шт');
                $inv_number = $this->normalizeInvNumber($row[2] ?? '');
                $price      = parseExcelNumber($row[3] ?? 0);
                $quantity   = parseExcelNumber($row[4] ?? 0);
                $sum        = parseExcelNumber($row[5] ?? 0);

                $product = $inv_number ? ($products[$inv_number] ?? null) : null;

                if (!$product) {
                    // fallback by name+unit when inv_number is empty or not found
                    $product = Product::where('name', $name)
                        ->where('unit', $unit)
                        ->first();

                    if ($product) {
                        Log::info('[samvol] import: fallback match by name+unit', [
                            'inv_number' => $inv_number,
                            'name' => $name,
                            'unit' => $unit,
                            'product_id' => $product->id
                        ]);
                    }
                }

                // $product = Product::where('inv_number', $inv_number)->first();
                // if (!$product) $product = Product::where('name',$name)->where('unit',$unit)->first();

                if (!$product) {
                    if (!$inv_number) {
                        Log::warning('[samvol] import: empty inv_number, row skipped to avoid duplicates', [
                            'name' => $name,
                            'unit' => $unit,
                            'price' => $price,
                            'quantity' => $quantity,
                            'sum' => $sum
                        ]);
                        continue;
                    }
                    $product = Product::create([
                        'name'=>$name,
                        'unit'=>$unit,
                        'inv_number'=>$inv_number,
                        'price'=>$price,
                    ]);
                    Log::info('[samvol] import: created product', [
                        'product_id' => $product->id,
                        'inv_number' => $inv_number,
                        'name' => $name,
                        'unit' => $unit,
                        'price' => $price
                    ]);
                    $newProducts[] = ['product'=>$product, 'quantity'=>$quantity, 'sum'=>$sum];
                    $newCount++;
                    continue;
                }

                $currentQuantity = $product->calculated_quantity ?? 0;
                $currentSum = $product->calculated_sum ?? 0;

                // Если есть различия по количеству, цене или сумме
                $hasQuantityDiff = abs($currentQuantity - $quantity) > self::EPSILON;
                $hasPriceDiff = abs(((float) $product->price) - $price) > self::EPSILON;
                $hasSumDiff = abs($currentSum - $sum) > self::EPSILON;

                if ($hasQuantityDiff || $hasPriceDiff || $hasSumDiff) {
                    $differences[] = [
                        'id'=>$product->id,
                        'name'=>$product->name,
                        'inv_number'=>$product->inv_number,
                        'current_quantity'=>$currentQuantity,
                        'excel_quantity'=>$quantity,
                        'price'=>$product->price,
                        'excel_price'=>$price,
                        'sum'=>$currentSum,
                        'current_sum'=>$currentSum,
                        'excel_sum'=>$sum,
                        'unit'=>$unit
                    ];
                }

                // Обновляем цену продукта, поле sum не трогаем
                $product->price = $price;
                $product->save();
            }

            // Создаём операцию для новых продуктов
            if (!empty($newProducts)) {
                $importType = OperationType::firstOrCreate(['name'=>'Импорт']);
                $operation = new Operation();
                $operation->type_id = $importType->id;
                $operation->save();

                foreach ($newProducts as $item) {
                    $operation->products()->attach($item['product']->id, [
                        'quantity'=>$item['quantity'],
                        'sum'=>$item['sum'],
                        'counteragent'=>$counteragent
                    ]);
                }
            }

            // Если есть различия, показываем модалку для подтверждения изменений
            if (!empty($differences)) {
                $html = $this->renderPartial('modals/modal_import_result', [
                    'differences'=>$differences,
                    'newCount'=>$newCount
                ]);

                return [
                    'modalContent'=>$html,
                    'modalType'=>'warning',
                    'modalTitle'=>'Знайдено відмінності',
                    'modalSubtitle'=>'При імпорті файлу знайдено відмінності між вашим складом та вашим файлом ',
                    'modalIconSvg'=>'<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 8.25C12.4142 8.25 12.75 8.58579 12.75 9V13C12.75 13.4142 12.4142 13.75 12 13.75C11.5858 13.75 11.25 13.4142 11.25 13V9C11.25 8.58579 11.5858 8.25 12 8.25Z" fill="currentColor"/><path d="M12 16.75C12.5523 16.75 13 16.3023 13 15.75C13 15.1977 12.5523 14.75 12 14.75C11.4477 14.75 11 15.1977 11 15.75C11 16.3023 11.4477 16.75 12 16.75Z" fill="currentColor"/><path fill-rule="evenodd" clip-rule="evenodd" d="M10.3196 3.41968C11.0599 2.14559 12.9401 2.14558 13.6804 3.41968L21.4111 16.725C22.157 18.0088 21.2293 19.625 19.7307 19.625H4.26925C2.77067 19.625 1.84304 18.0088 2.58893 16.725L10.3196 3.41968ZM12.3831 4.17304C12.2597 3.96064 11.9403 3.96064 11.8169 4.17304L4.08619 17.4784C3.96187 17.6924 4.11647 17.9688 4.26925 17.9688H19.7307C19.8835 17.9688 20.0381 17.6924 19.9138 17.4784L12.3831 4.17304Z" fill="currentColor"/></svg>',
                ];
            }

            return ['toast'=>['message'=>'Импорт завершен','type'=>'success', 'timeout' => 5000]];

        } catch (\Exception $e) {
            return ['toast'=>['message'=>'Ошибка: '.$e->getMessage(),'type'=>'error']];
        }
    }
}
