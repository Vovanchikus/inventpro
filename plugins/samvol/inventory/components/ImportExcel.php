<?php namespace Samvol\Inventory\Components;

use Cms\Classes\ComponentBase;
use Samvol\Inventory\Models\Product;
use Samvol\Inventory\Models\Operation;
use Samvol\Inventory\Models\OperationType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Input;
use Log;

class ImportExcel extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Импорт Excel',
            'description' => 'Импорт остатков через Excel с проверкой и выводом результатов'
        ];
    }

    public function onImportExcel()
    {
        Log::info("Начало импорта Excel", ['time' => now()]);

        // Конвертация числовых значений из Excel
        function parseExcelNumber($value) {
            if (!$value) return 0;
            $clean = preg_replace('/[\s\x{00A0}\x{202F}]+/u', '', $value);
            $clean = str_replace(',', '.', $clean);
            return floatval($clean);
        }

        $file = Input::file('excel_file');
        if (!$file) {
            return [
                'modalContent' => '<p style="color:red;">Файл не загружен.</p>',
                'modalType'    => 'error',
                'modalTitle'   => 'Нет файла'
            ];
        }

        try {
            $spreadsheet = IOFactory::load($file->getRealPath());
            $rows = $spreadsheet->getActiveSheet()->toArray();

            if (empty($rows)) {
                return [
                    'modalContent' => '<p style="color:red;">Файл пустой.</p>',
                    'modalType'    => 'error',
                    'modalTitle'   => 'Пустой файл'
                ];
            }

            unset($rows[0]); // пропускаем заголовок

            $differences = [];
            $newProducts = [];
            $newCount = 0;

            // -----------------------------
            // Функция для безопасного сравнения float
            // -----------------------------
            function floatsAreEqual($a, $b, $epsilon = 0.0001) {
                return abs($a - $b) < $epsilon;
            }

            foreach ($rows as $row) {
                if (empty($row[0])) continue;

                $name       = trim($row[0]);
                $unit       = trim($row[1] ?? 'шт');
                $inv_number = trim($row[2] ?? '');
                $price      = parseExcelNumber($row[3] ?? 0);
                $quantity   = parseExcelNumber($row[4] ?? 0);
                $sum        = parseExcelNumber($row[5] ?? 0);

                // -----------------------------
                // Поиск продукта без дубликатов
                // -----------------------------
                $product = null;
                if (!empty($inv_number)) {
                    $product = Product::where('inv_number', $inv_number)->first();
                }
                if (!$product) {
                    $product = Product::where('name', $name)->where('unit', $unit)->first();
                }

                // -----------------------------
                // Создание нового продукта
                // -----------------------------
                if (!$product) {
                    $product = Product::create([
                        'name'       => $name,
                        'unit'       => $unit,
                        'inv_number' => $inv_number,
                        'price'      => $price,
                        'sum'        => $sum
                    ]);

                    $newProducts[] = [
                        'product'  => $product,
                        'quantity' => $quantity
                    ];
                    $newCount++;
                    continue;
                }

                // -----------------------------
                // Логируем текущее состояние продукта
                // -----------------------------
                $currentQuantity = $product->calculated_quantity ?? 0;
                $currentSum = round($currentQuantity * $product->price, 2);
                $excelSum   = round($quantity * $price, 2);

                Log::info("Проверка продукта", [
                    'name' => $name,
                    'inv_number' => $inv_number,
                    'excel_quantity' => $quantity,
                    'current_quantity' => $currentQuantity,
                    'excel_sum' => $excelSum,
                    'current_sum' => $currentSum,
                    'price_excel' => $price,
                    'price_db' => $product->price
                ]);

                // -----------------------------
                // Проверка различий (без дублирования количества)
                // -----------------------------
                if (!floatsAreEqual($currentQuantity, $quantity) ||
                    !floatsAreEqual($currentSum, $excelSum) ||
                    !floatsAreEqual($product->price, $price))
                {
                    $differences[] = [
                        'name'             => $product->name,
                        'inv_number'       => $product->inv_number,
                        'current_quantity' => $currentQuantity,
                        'excel_quantity'   => $quantity,
                        'current_sum'      => $currentSum,
                        'excel_sum'        => $excelSum,
                        'price'            => $price,
                        'unit'             => $unit
                    ];

                    Log::info("Добавлено в различия", [
                        'name' => $name,
                        'inv_number' => $inv_number,
                        'current_quantity' => $currentQuantity,
                        'excel_quantity' => $quantity,
                        'current_sum' => $currentSum,
                        'excel_sum' => $excelSum,
                        'price_excel' => $price,
                        'price_db' => $product->price
                    ]);
                }

                // -----------------------------
                // Обновление полей кроме quantity
                // -----------------------------
                $product->name       = $name;
                $product->unit       = $unit;
                $product->inv_number = $inv_number;
                $product->price      = $price;
                $product->sum        = $sum;
                $product->save();
            }

            // -----------------------------
            // Создаём операцию только если есть новые продукты
            // -----------------------------
            if (!empty($newProducts)) {
                $importType = OperationType::firstOrCreate(['name' => 'Импорт']);
                $operation = new Operation();
                $operation->type_id = $importType->id;
                $operation->save();

                Log::info("Создана операция", [
                    'operation_id' => $operation->id,
                    'type_id' => $importType->id,
                    'time' => now()
                ]);

                foreach ($newProducts as $item) {
                    $operation->products()->attach($item['product']->id, ['quantity' => $item['quantity']]);
                }
            }

            // -----------------------------
            // Формирование HTML результата
            // -----------------------------
            $html = $this->renderPartial('modals/modal_import_result', [
                'differences' => $differences,
                'newCount'    => $newCount
            ]);

            $modalType  = !empty($differences) ? 'warning' : 'success';
            $modalTitle = !empty($differences) ? 'Есть различия с текущими данными:' : 'Импорт завершён';

            return [
                'modalContent' => $html,
                'modalType'    => $modalType,
                'modalTitle'   => $modalTitle
            ];

        } catch (\Exception $e) {
            return [
                'modalContent' => '<p style="color:red;">Ошибка: ' . $e->getMessage() . '</p>',
                'modalType'    => 'error',
                'modalTitle'   => 'Ошибка импорта'
            ];
        }
    }
}
