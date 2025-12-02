<?php namespace Samvol\Inventory\Components;

use Cms\Classes\ComponentBase;
use Samvol\Inventory\Models\Operation;
use Samvol\Inventory\Models\Product;
use DB;
use Exception;

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

    public function onUpdateOperation()
    {
        $data = post();

        try {
            DB::beginTransaction();

            // Создаём новую операцию или берем существующую
            if (!empty($data['operation_id'])) {
                $operation = Operation::find($data['operation_id']);
            } else {
                $operation = new Operation();
            }

            $operation->type_id = $data['type_id'] ?? null;
            $operation->save();

            $operationCounteragent = $data['counteragent'] ?? null;

            // Удаляем старые документы и создаём новые
            $operation->documents()->delete();
            foreach ($data['doc_name'] as $i => $docName) {
                if ($docName) {
                    $operation->documents()->create([
                        'doc_name' => $docName,
                        'doc_num'  => $data['doc_num'][$i] ?? '',
                        'doc_date' => $data['doc_date'][$i] ?? null
                    ]);
                }
            }

            // Обновляем товары
            foreach ($data['name'] as $i => $name) {
                if (!$name) continue;

                $inv_number = $data['inv_number'][$i];
                $unit       = $data['unit'][$i];
                $price      = floatval($data['price'][$i] ?? 0);
                $quantity   = floatval($data['quantity'][$i] ?? 0);

                $product = Product::firstOrCreate(
                    ['inv_number' => $inv_number],
                    ['name' => $name, 'unit' => $unit, 'price' => $price]
                );

                $pivotData = [
                    'quantity'     => $quantity,
                    'sum'          => round($quantity * $price, 2),
                    'counteragent' => $operationCounteragent
                ];

                // Удаляем товар из других операций, чтобы переместить
                DB::table('samvol_inventory_operation_products')
                    ->where('product_id', $product->id)
                    ->where('operation_id', '!=', $operation->id)
                    ->delete();

                // Привязываем товар к текущей операции
                $operation->products()->syncWithoutDetaching([
                    $product->id => $pivotData
                ]);
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
