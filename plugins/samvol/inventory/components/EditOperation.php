<?php namespace Samvol\Inventory\Components;

use Cms\Classes\ComponentBase;
use Samvol\Inventory\Models\Operation;
use Samvol\Inventory\Models\Product;
use DB;
use Exception;
use Input;

class EditOperation extends ComponentBase
{
    public $operation;
    public $operationId;
    public $products = [];

    public function componentDetails()
    {
        return [
            'name' => 'Редактирование операции',
            'description' => 'Редактирование существующей операции с товарами и документами'
        ];
    }

    public function defineProperties()
    {
        return [
            'operation_id' => [
                'title' => 'ID операции',
                'type' => 'string',
                'default' => '{{ :id }}'
            ]
        ];
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

    public function onRun()
    {
        $this->operationId = $this->property('operation_id');

        if ($this->operationId) {
            // Если передан ID через URL
            $this->operation = Operation::with(['products', 'documents'])->find($this->operationId);
            $this->products = $this->operation ? $this->operation->products : [];
        } else {
            // Если ID нет — пробуем взять из localStorage через JS
            $this->operation = null;
            $this->products = []; // пустой массив для JS, будет заполнен через localStorage
        }

        $this->page['operation'] = $this->operation;
        $this->page['products'] = $this->products;
    }

    public function onEditOperation()
    {
        $data = post();
        $isCreating = empty($data['operation_id']);
        $noteId = $data['note_id'] ?? null;

        // --- Валидация типа и контрагента ---
        if (empty($data['type_id'])) return $this->firstError('type_id', 'Не указан тип операции');
        if (empty($data['counteragent'])) return $this->firstError('counteragent', 'Не указан контрагент');

        // --- Валидация документов ---
        // Считаем операцию финальной только если загружен хотя бы один файл PDF.
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

        // Ранее: при создании новой операции без заметки документы были обязательны.
        // Теперь требуем документы только если есть загруженные файлы.
        if ($isCreating && empty($noteId) && !$hasFiles) {
            // Создание без загруженных файлов — позволяем сохранить как черновик
        }

        // Проверяем поля документов ТОЛЬКО для тех записей, у которых есть файл
        if (!empty($data['doc_name']) && is_array($data['doc_name'])) {
            foreach ($data['doc_name'] as $i => $docName) {
                if (!$docName) continue;
                $file = $files[$i] ?? null;
                if ($file) {
                    if (empty($data['doc_num'][$i])) return $this->firstError("doc_num[$i]", 'Номер документа обязателен');
                    if (empty($data['doc_date'][$i])) return $this->firstError("doc_date[$i]", 'Укажите дату документа');
                }
            }
        }

        // --- Валидация товаров ---
        if (empty($data['name']) || !is_array($data['name'])) {
            return $this->firstError('name', 'Не добавлены товары');
        }

        $errors = [];

        // Тип операции для проверки остатков
        try {
            $opType = \Samvol\Inventory\Models\OperationType::find($data['type_id']);
            $operationTypeName = mb_strtolower(trim($opType->name ?? ''));
        } catch (\Exception $e) {
            $operationTypeName = '';
        }

        // Проверка дубликатов
        $usedInvNumbers = [];
        foreach ($data['inv_number'] as $i => $inv_number) {
            if (!$inv_number) continue;
            if (in_array($inv_number, $usedInvNumbers)) {
                return [
                    'validationErrors' => [
                        ['field' => "inv_number[$i]", 'message' => "Этот товар уже добавлен в операцию"]
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

        // Проверка каждого товара
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

            // Проверка остатков для расхода/передачи
            if (in_array($operationTypeName, ['расход', 'передача'])) {
                $product = Product::where('inv_number', $inv_number)->first();
                $currentQty = $product->calculated_quantity ?? 0;

                if (is_numeric($quantity_raw) && floatval($quantity_raw) > $currentQty) {
                    $errors[] = ["field" => "quantity[$i]", "message" => "Слишком много"];
                    return [
                        'validationErrors' => $errors,
                        'toast' => [
                            'message' => "<b>Ошибка!</b><br>Нельзя передать <b>{$quantity_raw}</b> ед. товара \"<b>{$name}</b>\", на складе всего <b>{$currentQty}</b>",
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
        // Сохранение операции
        // ================================
        try {
            DB::beginTransaction();

            $operation = !empty($data['operation_id'])
                ? Operation::find($data['operation_id'])
                : new Operation();


            // Решим draft/финальная по наличию документов и по noteId
            $hasDocs = !empty($data['doc_name']) && is_array($data['doc_name']) && count(array_filter($data['doc_name'])) > 0;
            // Если нет документов и операция привязана к заметке — считаем черновой
            $operation->is_draft = (!$hasDocs && !empty($noteId));
            $operation->is_posted = $hasDocs ? true : false;

            // Связываем с заметкой (если передана)
            $operation->note_id = $noteId ?? null;

            $operation->type_id = $data['type_id'];
            $operation->save();

            $operationCounteragent = $data['counteragent'] ?? null;

            // Обработка файлов документов
            $files = Input::file('doc_file');
            if ($files && !is_array($files)) $files = [$files];

            // Удаляем старые документы
            $operation->documents()->delete();

            foreach ($data['doc_name'] as $i => $docName) {
                $uploadedFile = $files[$i] ?? null;
                $document = $operation->documents()->create([
                    'doc_name' => $docName,
                    'doc_num'  => $data['doc_num'][$i] ?? '',
                    'doc_purpose' => $data['doc_purpose'][$i] ?? null,
                    'doc_date' => $data['doc_date'][$i] ?? null,
                ]);

                if ($uploadedFile) {
                    $document->doc_file = $uploadedFile;
                    $document->save();
                }
            }

            // Привязка товаров — только для финальной операции. Для черновика не трогаем pivot,
            // чтобы не ломать историю операций.
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

                    // ========================
                    // ЧАСТИЧНЫЙ ПЕРЕНОС ТОВАРА
                    // ========================

                    // Ищем товар в старой операции
                    $oldPivot = DB::table('samvol_inventory_operation_products')
                        ->where('product_id', $product->id)
                        ->where('operation_id', '!=', $operation->id)
                        ->first();

                    if ($oldPivot) {
                        $oldQty = floatval($oldPivot->quantity);
                        $newQty = floatval($quantity);

                        // Остаток в старой операции
                        $diff = $oldQty - $newQty;

                        if ($diff > 0) {
                            // 🔹 Переносим часть → уменьшаем количество в старой операции
                            DB::table('samvol_inventory_operation_products')
                                ->where('product_id', $product->id)
                                ->where('operation_id', $oldPivot->operation_id)
                                ->update([
                                    'quantity' => $diff,
                                    'sum'      => round($diff * $price, 2),
                                ]);

                        } else {
                            // 🔹 Переносим всё → удаляем старую запись
                            DB::table('samvol_inventory_operation_products')
                                ->where('product_id', $product->id)
                                ->where('operation_id', $oldPivot->operation_id)
                                ->delete();
                        }
                    }

                    // Добавляем товар в текущую операцию (новая запись или обновление)
                    $operation->products()->syncWithoutDetaching([
                        $product->id => [
                            'quantity' => $quantity,
                            'sum' => $pivotSum,
                            'counteragent' => $operationCounteragent
                        ]
                    ]);
                }
            } else {
                // Для черновой операции — сохраним текущий выбор товаров в заметке (если есть),
                // вместо записи в pivot
                if (!empty($noteId) && !empty($data['name']) && is_array($data['name'])) {
                    $note = \Samvol\Inventory\Models\Note::find($noteId);
                    if ($note) {
                        $tmp = [];
                        foreach ($data['name'] as $i => $name) {
                            $inv = $data['inv_number'][$i] ?? null;
                            if (!$inv) continue;
                            $tmp[] = [
                                'inv_number' => $inv,
                                'name' => $name,
                                'unit' => $data['unit'][$i] ?? null,
                                'price' => isset($data['price'][$i]) ? floatval($data['price'][$i]) : null,
                                'quantity' => isset($data['quantity'][$i]) ? floatval($data['quantity'][$i]) : 0,
                            ];
                        }
                        $note->products = $tmp;
                        $note->save();
                    }
                }
            }
            // После успешного сохранения операций и документов — пересчитываем статус заметки
            if ($operation->note) {
                $operation->note->recalcStatus();
            }

            DB::commit();

            return [
                'toast' => [
                    'message' => 'Операция успешно обновлена',
                    'type' => 'success',
                    'timeout' => 4000,
                    'position' => 'top-center'
                ]
            ];

        } catch (Exception $e) {
            DB::rollBack();
            return [
                'toast' => [
                    'message' => 'Ошибка при обновлении операции: ' . $e->getMessage(),
                    'type' => 'error',
                    'timeout' => 7000,
                    'position' => 'top-center'
                ]
            ];
        }
    }

}
