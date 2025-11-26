<?php namespace Samvol\Inventory\Components;

use Cms\Classes\ComponentBase;
use Samvol\Inventory\Models\Operation;
use Samvol\Inventory\Models\OperationType;
use Samvol\Inventory\Models\Product;
use ValidationException;
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

    public function onAddOperation()
    {
        $data = post(); // все данные формы

        // --- Создаем операцию ---
        $operation = new \Samvol\Inventory\Models\Operation();
        $operation->type_id = $data['type_id'] ?? null;
        $operation->counteragent = $data['counteragent'] ?? null;

        $operation->save();

        // --- Добавляем документы ---
        if (!empty($data['doc_name'])) {
            foreach ($data['doc_name'] as $index => $name) {
                $num = $data['doc_num'][$index] ?? null;
                $date = $data['doc_date'][$index] ?? null;

                if ($name || $num || $date) {
                    $operation->documents()->create([
                        'doc_name' => $name,
                        'doc_num'  => $num,
                        'doc_date' => $date,
                        'counteragent' => $operation->counteragent, // привязываем контрагента
                    ]);
                }
            }
        }

        // --- Добавляем товары ---
        if (!empty($data['name'])) {
            foreach ($data['name'] as $index => $name) {
                $inv_number = $data['inv_number'][$index] ?? null;
                $unit = $data['unit'][$index] ?? null;
                $price = $data['price'][$index] ?? 0;
                $quantity = $data['quantity'][$index] ?? 0;
                $sum = $data['sum'][$index] ?? 0;

                if ($name || $inv_number) {
                    // ищем существующий продукт или создаем новый
                    $product = \Samvol\Inventory\Models\Product::firstOrCreate([
                        'inv_number' => $inv_number
                    ], [
                        'name' => $name,
                        'unit' => $unit,
                        'price' => $price,
                        'sum' => $sum,
                    ]);

                    // привязываем к операции через pivot с количеством
                    $operation->products()->attach($product->id, [
                        'quantity' => $quantity
                    ]);
                }
            }
        }

        return [
            'success' => true,
            'toast' => [
                'message' => 'Операция успешно добавлена',
                'type' => 'success'
            ]
        ];
    }
}
