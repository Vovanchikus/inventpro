<?php namespace Samvol\Inventory\Components;

use Cms\Classes\ComponentBase;
use Samvol\Inventory\Models\Operation;
use Samvol\Inventory\Models\Product;

class AddOperation extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Добавление операции',
            'description' => 'Создание новой операции с товарами и документами'
        ];
    }

    // --- Вспомогательная функция для первой ошибки ---
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

    public function onAddOperation()
    {
        $data = post();

        // ===== Очередная валидация =====
        if (empty($data['type_id'])) {
            return $this->firstError('type_id', 'Не указан тип операции');
        }

        if (empty($data['counteragent'])) {
            return $this->firstError('counteragent', 'Не указан контрагент');
        }

        if (empty($data['doc_name']) || !is_array($data['doc_name'])) {
            return $this->firstError('doc_name', 'Не добавлены документы');
        }

        foreach ($data['doc_name'] as $i => $docName) {
            $docNum  = $data['doc_num'][$i] ?? null;
            $docDate = $data['doc_date'][$i] ?? null;

            if (!$docName) return $this->firstError("doc_name[$i]", 'Обязательное поле');
            if (!$docNum)  return $this->firstError("doc_num[$i]", 'Номера нет');
            if (!$docDate) return $this->firstError("doc_date[$i]", 'Укажите дату');
        }

        if (empty($data['name']) || !is_array($data['name'])) {
            return $this->firstError('name', 'Не добавлены товары');
        }

        foreach ($data['name'] as $i => $name) {
            $inv_number = $data['inv_number'][$i] ?? null;
            $unit       = $data['unit'][$i] ?? null;
            $price_raw  = $data['price'][$i] ?? null;
            $quantity_raw = $data['quantity'][$i] ?? null;
            $sum_raw    = $data['sum'][$i] ?? null;

            if (!$name)       return $this->firstError("name[$i]", 'Обязательное поле');
            if (!$inv_number) return $this->firstError("inv_number[$i]", 'Обязательное поле');
            if (!$unit)       return $this->firstError("unit[$i]", 'Не указано');

            if (!is_numeric($price_raw) || floatval($price_raw) <= 0)
                return $this->firstError("price[$i]", 'Должно быть больше 0');
            if (!is_numeric($quantity_raw) || floatval($quantity_raw) <= 0)
                return $this->firstError("quantity[$i]", 'Должно быть больше 0');
            if (!is_numeric($sum_raw) || floatval($sum_raw) <= 0)
                return $this->firstError("sum[$i]", 'Должно быть больше 0');
        }

        // ===== Создание операции =====
        $operation = new Operation();
        $operation->type_id = $data['type_id'];
        $operation->save();
        $operationCounteragent = $data['counteragent'] ?? null;

        // --- Документы ---
        foreach ($data['doc_name'] as $i => $docName) {
            $operation->documents()->create([
                'doc_name' => $docName,
                'doc_num'  => $data['doc_num'][$i],
                'doc_date' => $data['doc_date'][$i]
            ]);
        }

        // --- Товары ---
        foreach ($data['name'] as $i => $name) {
            $inv_number = $data['inv_number'][$i];
            $unit       = $data['unit'][$i];
            $price      = floatval($data['price'][$i]);
            $quantity   = floatval($data['quantity'][$i]);
            $sum        = floatval($data['sum'][$i]);

            $product = Product::firstOrCreate(
                ['inv_number' => $inv_number],
                ['name' => $name, 'unit' => $unit, 'price' => $price]
            );

            $operationType = mb_strtolower(trim($operation->type->name ?? ''));

            if (in_array($operationType, ['расход', 'передача'])) {
                $currentQty = $product->calculated_quantity;
                if ($quantity > $currentQty) {
                    return [
                        'toast' => [
                            'message' => "Ошибка! Нельзя передать <b>{$quantity}</b> ед. товара <b>{$product->name}</b>. На складе всего <b>{$currentQty}</b>",
                            'type' => 'error',
                            'timeout' => 8000,
                            'position' => 'top-center'
                        ]
                    ];
                }
            }

            $pivotSum = round($quantity * $price, 2);
            $operation->products()->attach($product->id, [
                'quantity'     => $quantity,
                'sum'          => $pivotSum,
                'counteragent' => $operationCounteragent
            ]);
        }

        return [
            'toast' => [
                'message' => 'Операция успешно добавлена',
                'type' => 'success',
                'timeout' => 4000,
                'position' => 'top-center'
            ]
        ];
    }
}
