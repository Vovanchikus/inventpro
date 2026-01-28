<?php namespace Samvol\Inventory\Components;

use Cms\Classes\ComponentBase;
use Samvol\Inventory\Models\Operation;
use Samvol\Inventory\Models\Product;
use Samvol\Inventory\Models\OperationType;
use DB;
use Exception;
use Input;
use Log;

class AddOperation extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Добавление операции',
            'description' => 'Создание новой операции с товарами и документами'
        ];
    }

    public function onRun()
    {
        // Если пришёл note_id в URL, передадим товары из заметки в страницу (не создавая операцию)
        $noteId = input('note_id') ?: session('note_id');
        if ($noteId) {
            $note = \Samvol\Inventory\Models\Note::find($noteId);
            if ($note) {
                $prefill = [];

                // Попробуем собрать массив продуктов с полями pivot (quantity, sum)
                try {
                    foreach ($note->products as $p) {
                        $prefill[] = [
                            'id' => $p->id,
                            'name' => $p->name,
                            'inv_number' => $p->inv_number,
                            'unit' => $p->unit,
                            'price' => $p->price,
                            'quantity' => isset($p->pivot->quantity) ? (float)$p->pivot->quantity : null,
                            'sum' => isset($p->pivot->sum) ? (float)$p->pivot->sum : null,
                        ];
                    }
                } catch (\Exception $e) {
                    $prefill = [];
                }

                // Если связанных продуктов нет (в pivot product_id может быть NULL),
                // попробуем восстановить из JSON-поля notes.products
                if (empty($prefill)) {
                    try {
                        // Сначала попробуем собрать из pivot + products через JOIN
                        $rows = \DB::table('samvol_inventory_note_products as np')
                            ->leftJoin('samvol_inventory_products as p', 'np.product_id', '=', 'p.id')
                            ->where('np.note_id', $note->id)
                            ->select('np.*', 'p.id as p_id', 'p.name as p_name', 'p.inv_number as p_inv', 'p.unit as p_unit', 'p.price as p_price')
                            ->get();

                        foreach ($rows as $r) {
                            $prefill[] = [
                                'id' => $r->p_id ?? null,
                                'name' => $r->p_name ?? null,
                                'inv_number' => $r->p_inv ?? null,
                                'unit' => $r->p_unit ?? null,
                                'price' => isset($r->p_price) ? (float)$r->p_price : null,
                                'quantity' => isset($r->quantity) ? (float)$r->quantity : null,
                                'sum' => isset($r->sum) ? (float)$r->sum : null,
                            ];
                        }

                        // Если pivot пуст — попробуем декодировать сырое JSON поле как запасной вариант
                        if (empty($prefill)) {
                            $raw = \DB::table('samvol_inventory_notes')->where('id', $note->id)->value('products');
                            if ($raw) {
                                $items = json_decode($raw, true);
                                if (is_array($items)) {
                                    foreach ($items as $it) {
                                        $prefill[] = [
                                            'inv_number' => $it['inv_number'] ?? $it['inv'] ?? $it['id'] ?? null,
                                            'name' => $it['name'] ?? null,
                                            'unit' => $it['unit'] ?? null,
                                            'price' => isset($it['price']) ? (float)$it['price'] : null,
                                            'quantity' => isset($it['quantity']) ? (float)$it['quantity'] : (isset($it['qty']) ? (float)$it['qty'] : null),
                                            'sum' => isset($it['sum']) ? (float)$it['sum'] : null,
                                        ];
                                    }
                                }
                            }
                        }
                    } catch (\Exception $e) {
                        // noop
                    }
                }

                // Логируем для отладки — сколько продуктов найдено и пример
                try {
                    // debug logging removed
                    // Если prefill пуст — выведем сырые pivot-строки для заметки
                    if (empty($prefill)) {
                        try {
                            $rows = \DB::table('samvol_inventory_note_products')->where('note_id', $note->id)->get();
                            // debug logging removed
                        } catch (\Exception $e) {
                            \Log::warning('[samvol] cannot read pivot rows: ' . $e->getMessage());
                        }
                    }
                } catch (\Exception $e) {
                    // noop
                }

                $this->page['prefill_products'] = $prefill;
                $this->page['note_id'] = $note->id;
            }
        }
    }

    protected function firstError($field, $message)
    {
        return [
            'validationErrors' => [
                ['field' => $field, 'message' => $message]
            ],
            'toast' => [
                'message' => 'Проверьте выделенное поле!',
                'type' => 'error',
                'timeout' => 5000,
                'position' => 'top-center'
            ]
        ];
    }

    public function onSearchProductsByInv()
    {
        $q = trim(post('query', ''));

        if (!$q) return ['products' => []];

        // Ищем товары, у которых inv_number начинается с $q
        $product = Product::where('inv_number', 'LIKE', "$q%")
            ->orderBy('inv_number')
            ->first(['name', 'inv_number', 'unit', 'price']); // <- только первый результат

        return ['products' => $product ? [$product] : []];
    }

    public function onAddOperation()
    {

        $user = \Auth::getUser();

        // 🔐 Проверка прав: только admin
        if (!$user || !$user->isInGroup('admin')) {
            throw new \ApplicationException('У вас нет прав на создание операций!');
        }

        $data = post();
        // Логируем пришедшие данные кратко для диагностики
        try {
            if (!empty($data['products'])) {
                // debug logging removed
            } else {
                // логируем количество строк формовых полей как индикатор
                $countNames = is_array($data['name']) ? count($data['name']) : 0;
                // debug logging removed
            }
        } catch (Exception $e) {
            Log::warning('[samvol] onAddOperation: failed to log incoming data: ' . $e->getMessage());
        }
        // Ранняя рекурсивная проверка: ищем вложенные массивы в любом уровне вложенности
        try {
            $found = null;
            $checkRecursive = function($value, $path = '') use (&$checkRecursive, &$found) {
                if ($found) return true; // уже нашли
                if (is_array($value)) {
                    foreach ($value as $k => $v) {
                        $currentPath = $path === '' ? (string)$k : $path . '[' . $k . ']';
                        if (is_array($v)) {
                            $found = $currentPath;
                            return true;
                        }
                        // continue deeper
                        if (is_array($v) || is_object($v)) {
                            // only recurse into arrays (objects are typically UploadedFile instances)
                            if (is_array($v)) {
                                if ($checkRecursive($v, $currentPath)) return true;
                            }
                        }
                    }
                }
                return false;
            };

            foreach ($data as $key => $val) {
                if ($checkRecursive($val, $key)) break;
            }

            if ($found) {
                Log::warning('[samvol] onAddOperation: nested array detected in field path: ' . $found);
                return [
                    'validationErrors' => [
                        ['field' => (string)$found, 'message' => 'Неподдерживаемая вложенная структура данных']
                    ],
                    'toast' => [
                        'message' => 'Ошибка данных: найден вложенный массив в поле "' . $found . '"',
                        'type' => 'error',
                        'timeout' => 7000,
                        'position' => 'top-center'
                    ]
                ];
            }
        } catch (Exception $e) {
            Log::warning('[samvol] onAddOperation nested-check failed: ' . $e->getMessage());
        }
        // Protective normalization: if any product input array elements are themselves arrays,
        // replace them with the first scalar value to avoid nested arrays reaching DB queries
        // (log occurrences for later inspection).
        try {
            $fieldsToCheck = ['name','inv_number','unit','price','quantity','sum'];
            foreach ($fieldsToCheck as $f) {
                if (!empty($data[$f]) && is_array($data[$f])) {
                    foreach ($data[$f] as $i => $val) {
                        if (is_array($val)) {
                            // find first scalar inside
                            $replacement = null;
                            foreach ($val as $sub) {
                                if (is_scalar($sub) || $sub === null) { $replacement = $sub; break; }
                            }
                            if ($replacement === null) {
                                // fallback to JSON-encoded string
                                $replacement = json_encode($val);
                            }
                            Log::warning("[samvol] onAddOperation: field {$f}[{$i}] contained nested array; replaced with scalar/encoded.");
                            $data[$f][$i] = $replacement;
                        }
                    }
                }
            }
        } catch (Exception $e) {
            Log::warning('[samvol] onAddOperation normalization failed: ' . $e->getMessage());
        }

        // --- Тип операции ---
        if (empty($data['type_id'])) {
            return $this->firstError('type_id', 'Не указан тип операции');
        }

        // ⬇⬇⬇ ВАЖНО: определяем тип ДО использования
        $opType = OperationType::find($data['type_id']);
        $operationTypeName = mb_strtolower(trim($opType->name ?? ''));

        // Контрагент обязателен НЕ для всех операций
        if (!in_array($operationTypeName, ['списание'])) {
            if (empty($data['counteragent'])) {
                return $this->firstError('counteragent', 'Не указан контрагент');
            }
        }

        // --- Документы ---
        // Считаем операцию финальной только если загружен хотя бы один PDF-файл.
        $files = Input::file('doc_file');
        if ($files && !is_array($files)) {
            $files = [$files];
        }

        $hasFiles = false;
        if (is_array($files) && count(array_filter($files))) {
            $hasFiles = true;
        } elseif ($files) {
            $hasFiles = true;
        }

        $hasDocs = $hasFiles;

        // Если файл был загружен для строки — требуем заполненные поля документа.
        if (is_array($files) && count($files)) {
            foreach ($files as $i => $f) {
                if (!$f) continue;
                // проверяем соответствующие поля в $data
                $docName = $data['doc_name'][$i] ?? null;
                $docNum  = $data['doc_num'][$i] ?? null;
                $docDate = $data['doc_date'][$i] ?? null;

                if (empty($docName)) return $this->firstError("doc_name[$i]", 'Укажите имя документа или удалите файл');
                if (empty($docNum))  return $this->firstError("doc_num[$i]", 'Укажите номер документа');
                if (empty($docDate)) return $this->firstError("doc_date[$i]", 'Укажите дату документа');
            }
        }

        // --- Товары обязательно ---
        if (empty($data['name']) || !is_array($data['name'])) {
            return $this->firstError('name', 'Не добавлены товары');
        }

        $errors = [];

        // Тип операции
        try {
            $opType = OperationType::find($data['type_id']);
            $operationTypeName = mb_strtolower(trim($opType->name ?? ''));
        } catch (Exception $e) {
            $operationTypeName = '';
        }

        // ======================================
        // 🔥 Проверка на дубликаты товаров
        // ======================================
        $usedInvNumbers = [];

        foreach ($data['name'] as $i => $name) {

            $inv_number   = $data['inv_number'][$i] ?? null;

            if ($inv_number) {
                if (in_array($inv_number, $usedInvNumbers)) {

                    return [
                        'validationErrors' => [
                            [
                                'field' => "inv_number[$i]",
                                'message' => "Этот товар уже добавлен в операцию"
                            ]
                        ],
                        'toast' => [
                            'message' => "Нельзя добавить один и тот же товар дважды!",
                            'type' => 'error',
                            'timeout' => 5000,
                            'position' => 'top-center'
                        ]
                    ];
                }

                $usedInvNumbers[] = $inv_number;
            }
        }

        // ======================================
        // 🔥 Основная валидация каждого товара
        // ======================================
        foreach ($data['name'] as $i => $name) {

            $inv_number   = $data['inv_number'][$i] ?? null;
            $unit         = $data['unit'][$i] ?? null;
            $price_raw    = $data['price'][$i] ?? null;
            $quantity_raw = $data['quantity'][$i] ?? null;
            $sum_raw      = $data['sum'][$i] ?? null;

            if (!$name)       $errors[] = ["field" => "name[$i]", "message" => "Обязательное поле"];
            if (!$inv_number) $errors[] = ["field" => "inv_number[$i]", "message" => "Обязательное поле"];
            if (!$unit)       $errors[] = ["field" => "unit[$i]", "message" => "Не указано"];

            if (!is_numeric($price_raw) || floatval($price_raw) <= 0)
                $errors[] = ["field" => "price[$i]", "message" => "Должно быть больше 0"];
            if (!is_numeric($quantity_raw) || floatval($quantity_raw) <= 0)
                $errors[] = ["field" => "quantity[$i]", "message" => "Должно быть больше 0"];
            if (!is_numeric($sum_raw) || floatval($sum_raw) <= 0)
                $errors[] = ["field" => "sum[$i]", "message" => "Должно быть больше 0"];

            // --- Проверка остатка ---
            if (in_array($operationTypeName, ['расход', 'передача'])) {

                $product = Product::where('inv_number', $inv_number)->first();
                $currentQty = $product->calculated_quantity ?? 0;

                if (is_numeric($quantity_raw) && floatval($quantity_raw) > $currentQty) {

                    $errors[] = ["field" => "quantity[$i]", "message" => "Слишком много"];

                    return [
                        'validationErrors' => $errors,
                        'toast' => [
                            'message' =>
                                "<b>Ошибка!</b><br>Нельзя передать <b>{$quantity_raw}</b> ед. товара \"<b>{$name}</b>\",
                                 на складе всего <b>{$currentQty}</b>",
                            'type' => 'error',
                            'timeout' => 6000,
                            'position' => 'top-center'
                        ]
                    ];
                }
            }
        }

        if (!empty($errors)) {
            return [
                'validationErrors' => $errors,
                'toast' => [
                    'message' => 'Исправьте ошибки перед сохранением!',
                    'type' => 'error',
                    'timeout' => 6000,
                    'position' => 'top-center'
                ]
            ];
        }

            // ================================
            // СОХРАНЕНИЕ ОПЕРАЦИИ
            // ================================
        try {
            DB::beginTransaction();

            $operation = new Operation();
            $operation->type_id = $data['type_id'];

            // Если есть документы — финализируем операцию сразу
            if ($hasDocs) {
                $operation->is_draft = false;
                $operation->is_posted = true;
            } else {
                $operation->is_draft = true;
                $operation->is_posted = false;
            }

            // Привяжем к заметке, если есть
            if (!empty($data['note_id'])) {
                $operation->note_id = $data['note_id'];
            }

            $operation->save();

            $operationCounteragent = $data['counteragent'] ?? null;

            $files = Input::file('doc_file'); // может быть UploadedFile или массив UploadedFile

            // Приводим к массиву, чтобы всегда можно было использовать foreach
            if ($files && !is_array($files)) {
                $files = [$files];
                foreach ($files as $f) {
                    \Log::info('Получен файл: ' . $f->getClientOriginalName());
                }
            } else {
                \Log::warning('Файлы не пришли');
            }

            // Сохраняем документы только если прислан реальный файл
            if ($hasDocs && !empty($data['doc_name']) && is_array($data['doc_name'])) {
                foreach ($data['doc_name'] as $i => $docName) {
                    if (!$docName) continue;
                    $uploadedFile = $files[$i] ?? null;
                    // создаём запись документа только если есть файл
                    if (!$uploadedFile) continue;

                    $document = $operation->documents()->create([
                        'doc_name' => $docName,
                        'doc_num'  => $data['doc_num'][$i] ?? '',
                        'doc_purpose' => $data['doc_purpose'][$i] ?? null,
                        'doc_date' => $data['doc_date'][$i] ?? null,
                    ]);

                    $document->doc_file = $uploadedFile;
                    $document->save();
                }
            }


            // Товары — добавляем в pivot ТОЛЬКО для финальной операции
            if (!$operation->is_draft) {
                foreach ($data['name'] as $i => $name) {

                    $inv_number = $data['inv_number'][$i];
                    $unit       = $data['unit'][$i];
                    $price      = floatval($data['price'][$i]);
                    $quantity   = floatval($data['quantity'][$i]);

                    $product = Product::firstOrCreate(
                        ['inv_number' => $inv_number],
                        ['name' => $name, 'unit' => $unit, 'price' => $price]
                    );

                    $pivotSum = round($quantity * $price, 2);

                    // Привязываем товары
                    $operation->products()->attach($product->id, [
                        'quantity'     => $quantity,
                        'sum'          => $pivotSum,
                        'counteragent' => $operationCounteragent
                    ]);
                }
            } else {
                // Для черновой операции не трогаем pivot — вместо этого обновим товары в заметке (если она есть)
                if (!empty($operation->note_id) && !empty($data['name']) && is_array($data['name'])) {
                    $note = \Samvol\Inventory\Models\Note::find($operation->note_id);
                    if ($note) {
                        // Build sync payload: ensure products exist and map by product ID
                        $sync = [];
                        foreach ($data['name'] as $i => $name) {
                            $inv = $data['inv_number'][$i] ?? null;
                            if (!$inv) continue;
                            try {
                                $product = Product::firstOrCreate(
                                    ['inv_number' => $inv],
                                    ['name' => $name, 'unit' => $data['unit'][$i] ?? null, 'price' => isset($data['price'][$i]) ? floatval($data['price'][$i]) : null]
                                );
                                $pid = $product->id;
                                $qty = isset($data['quantity'][$i]) ? floatval($data['quantity'][$i]) : 0;
                                $price = isset($data['price'][$i]) ? floatval($data['price'][$i]) : null;
                                $sync[$pid] = [
                                    'quantity' => $qty,
                                    'sum' => $price !== null ? round($qty * $price, 2) : null,
                                    'counteragent' => null,
                                ];
                            } catch (Exception $e) {
                                Log::warning('[samvol] onAddOperation: failed to ensure product for note sync: ' . ($inv ?? 'n/a') . ' - ' . $e->getMessage());
                            }
                        }

                        try {
                            if (!empty($sync)) {
                                $note->products()->sync($sync);
                            } else {
                                $note->products()->sync([]);
                            }
                        } catch (Exception $e) {
                            Log::error('[samvol] onAddOperation: note products sync failed: ' . $e->getMessage());
                            throw $e;
                        }
                    }
                }
            }

            // После сохранения — если операция draft, не трогаем склад, но обновим статус заметки
            if ($operation->is_draft) {
                if (!empty($operation->note_id)) {
                    $note = \Samvol\Inventory\Models\Note::find($operation->note_id);
                    if ($note) {
                        $note->status = 'document_prepared';
                        $note->save();
                    }
                }

                DB::commit();

                return [
                    'toast' => [
                        'message' => 'Документ разработан, склад не изменён',
                        'type' => 'success',
                        'timeout' => 4000,
                        'position' => 'top-center'
                    ]
                ];
            }

            // Если операция финальная — вызовем recalcStatus у заметки
            if (!empty($operation->note_id)) {
                $note = \Samvol\Inventory\Models\Note::find($operation->note_id);
                if ($note) $note->recalcStatus();
            }

            DB::commit();

            return [
                'toast' => [
                    'message' => 'Операция успешно добавлена',
                    'type' => 'success',
                    'timeout' => 4000,
                    'position' => 'top-center'
                ]
            ];

        } catch (Exception $e) {

            DB::rollBack();
            return [
                'toast' => [
                    'message' => 'Ошибка при сохранении операции: ' . $e->getMessage(),
                    'type' => 'error',
                    'timeout' => 7000,
                    'position' => 'top-center'
                ]
            ];
        }
    }


    // ---- Поиск товаров ----
    public function onSearchProducts()
    {
        $query = trim(post('query', ''));
        if (!$query) return ['results' => []];

        $q = mb_strtolower($query);

        $products = Product::whereRaw('LOWER(name) LIKE ?', ["%{$q}%"])
            ->orWhereRaw('LOWER(inv_number) LIKE ?', ["%{$q}%"])
            ->limit(20)
            ->get(['id','name','inv_number','unit','price']);

        return [
            'results' => $products->map(function($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'inv_number' => $p->inv_number,
                    'unit' => $p->unit,
                    'price' => $p->price,
                    'calculated_quantity' => $p->calculated_quantity ?? 0,
                    'calculated_sum' => $p->calculated_sum ?? 0,
                ];
            })
        ];
    }

    public function onShowProductSearchModal()
    {
        $html = $this->renderPartial('modals/modal_product_list');

        return [
            'modalContent' => $html,
            'modalType' => 'info',
            'modalTitle' => 'Выберите товар'
        ];
    }
}
