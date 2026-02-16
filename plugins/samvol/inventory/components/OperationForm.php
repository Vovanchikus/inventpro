<?php namespace Samvol\Inventory\Components;

use Cms\Classes\ComponentBase;
use Samvol\Inventory\Models\Operation;
use Samvol\Inventory\Models\Product;
use Samvol\Inventory\Models\OperationType;
use DB;
use Exception;

class OperationForm extends ComponentBase
{
    public $operation;
    public $operationId;
    public $products = [];

    /**
     * Название и описание компонента
     */
    public function componentDetails()
    {
        return [
            'name' => 'Форма операции',
            'description' => 'Добавление или редактирование операции с товарами и документами'
        ];
    }

    /**
     * Определение свойств компонента
     */
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

    /**
     * Метод запуска компонента
     * Определяет, создаём новую операцию или редактируем существующую
     */
    public function onRun()
    {
        $this->operationId = $this->property('operation_id');

        if ($this->operationId) {
            // Редактирование существующей операции
            $this->operation = Operation::with(['products', 'documents'])->find($this->operationId);
            $this->products = $this->operation ? $this->operation->products : [];
        } else {
            // Создание новой операции
            $this->operation = null;
            $this->products = [];
        }

        $this->page['operation'] = $this->operation;
        $this->page['products']  = $this->products;
    }

    /**
     * Универсальный метод сохранения операции
     * Добавление или редактирование определяется по наличию operation_id
     */
    public function onSaveOperation()
    {
        $data = post();

        // --- Общая валидация ---
        $validation = $this->validateOperationData($data);
        if ($validation !== true) return $validation;

        $operationCounteragent = $data['counteragent'] ?? null;

        try {
            DB::beginTransaction();

            // --- Создаём новую или получаем существующую операцию ---
            if (!empty($data['operation_id'])) {
                $operation = Operation::find($data['operation_id']);
            } else {
                $operation = new Operation();
            }

            $operation->type_id = $data['type_id'] ?? null;
            $operation->save();

            // --- Сохраняем документы ---
            $operation->documents()->delete(); // удаляем старые
            foreach ($data['doc_name'] as $i => $docName) {
                if ($docName) {
                    $operation->documents()->create([
                        'doc_name' => $docName,
                        'doc_num'  => $data['doc_num'][$i] ?? '',
                        'doc_date' => $data['doc_date'][$i] ?? null
                    ]);
                }
            }

            // --- Сохраняем товары ---
            $this->saveOperationProducts($operation, $data, $operationCounteragent);

            DB::commit();

            return [
                'toast' => [
                    'message' => !empty($data['operation_id'])
                        ? 'Операция успешно обновлена'
                        : 'Операция успешно добавлена',
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

    /**
     * Валидация данных операции (общая для добавления и редактирования)
     */
    protected function validateOperationData($data)
    {
        if (empty($data['type_id'])) return $this->firstError('type_id', 'Не указан тип операции');
        if (empty($data['counteragent'])) return $this->firstError('counteragent', 'Не указан контрагент');

        if (empty($data['doc_name']) || !is_array($data['doc_name']))
            return $this->firstError('doc_name', 'Не добавлены документы');

        foreach ($data['doc_name'] as $i => $docName) {
            if (!$docName) return $this->firstError("doc_name[$i]", 'Обязательное поле');
            if (empty($data['doc_num'][$i])) return $this->firstError("doc_num[$i]", 'Номера нет');
            if (empty($data['doc_date'][$i])) return $this->firstError("doc_date[$i]", 'Укажите дату');
        }

        if (empty($data['name']) || !is_array($data['name']))
            return $this->firstError('name', 'Не добавлены товары');

        // Проверка на дубликаты
        $usedInvNumbers = [];
        foreach ($data['inv_number'] ?? [] as $i => $inv_number) {
            if ($inv_number) {
                if (in_array($inv_number, $usedInvNumbers)) {
                    return [
                        'validationErrors' => [
                            ['field' => "inv_number[$i]", 'message' => "Этот товар уже добавлен"]
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

        $errors = [];
        $operationTypeName = '';
        try {
            $opType = OperationType::find($data['type_id']);
            $operationTypeName = mb_strtolower(trim($opType->name ?? ''));
        } catch (Exception $e) {}

        foreach ($data['name'] as $i => $name) {
            $inv_number = $data['inv_number'][$i] ?? null;
            $unit       = $data['unit'][$i] ?? null;
            $price_raw  = $data['price'][$i] ?? null;
            $qty_raw    = $data['quantity'][$i] ?? null;
            $sum_raw    = $data['sum'][$i] ?? null;

            if (!$name)       $errors[] = ["field" => "name[$i]", "message" => "Обязательное поле"];
            if (!$inv_number) $errors[] = ["field" => "inv_number[$i]", "message" => "Обязательное поле"];
            if (!$unit)       $errors[] = ["field" => "unit[$i]", "message" => "Не указано"];
            if (!is_numeric($price_raw) || floatval($price_raw) <= 0)
                $errors[] = ["field" => "price[$i]", "message" => "Должно быть больше 0"];
            if (!is_numeric($qty_raw) || floatval($qty_raw) <= 0)
                $errors[] = ["field" => "quantity[$i]", "message" => "Должно быть больше 0"];
            if (!is_numeric($sum_raw) || floatval($sum_raw) <= 0)
                $errors[] = ["field" => "sum[$i]", "message" => "Должно быть больше 0"];

            // Проверка остатка
            if (in_array($operationTypeName, ['расход', 'передача']) && $inv_number) {
                $product = Product::where('inv_number', $inv_number)->first();
                $currentQty = $product->calculated_quantity ?? 0;
                if (is_numeric($qty_raw) && floatval($qty_raw) > $currentQty) {
                    $errors[] = ["field" => "quantity[$i]", "message" => "Слишком много"];
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

        return true;
    }

    /**
     * Сохраняет товары операции (проверка существующих, удаление из других операций)
     */
    protected function saveOperationProducts($operation, $data, $operationCounteragent)
    {
        foreach ($data['name'] as $i => $name) {
            if (!$name) continue;

            $inv_number = $data['inv_number'][$i];
            $unit       = $data['unit'][$i];
            $price      = floatval($data['price'][$i]);
            $quantity   = floatval($data['quantity'][$i]);

            $product = Product::firstOrCreate(
                ['inv_number' => $inv_number],
                ['name' => $name, 'unit' => $unit, 'price' => $price]
            );

            $pivotData = [
                'quantity' => $quantity,
                'sum' => round($quantity * $price, 2),
                'counteragent' => $operationCounteragent
            ];

            // Удаляем товар из других операций
            DB::table('samvol_inventory_operation_products')
                ->where('product_id', $product->id)
                ->where('operation_id', '!=', $operation->id)
                ->delete();

            // Привязываем к текущей операции
            $operation->products()->syncWithoutDetaching([$product->id => $pivotData]);
        }
    }

    /**
     * Первый объект ошибки
     */
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

    /**
     * Поиск товаров
     */
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

    /**
     * Модальное окно выбора товара
     */
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
