<?php namespace Samvol\Inventory\Models;

use Model;

class Operation extends Model
{
    use \Winter\Storm\Database\Traits\Validation;

    public $table = 'samvol_inventory_operations';

    public $belongsTo = [
        'type' => 'Samvol\Inventory\Models\OperationType'
    ];

    public $belongsToMany = [
        'products' => [
            'Samvol\Inventory\Models\Product',
            'table' => 'samvol_inventory_operation_products',
            'pivot' => ['quantity'],
            'pivotModel' => \Samvol\Inventory\Models\OperationProduct::class,
        ]
    ];

    public function beforeAttach($relationName, $attachedIdList, $insertData)
    {
        // Этот хук срабатывает при вызове attach() вручную
        if ($relationName === 'products') {
            foreach ($attachedIdList as $id) {
                $product = \Samvol\Inventory\Models\Product::find($id);
                if ($product) {
                    $insertData[$id] = [
                        'quantity' => $product->quantity ?? 1
                    ];
                }
            }
        }

        return $insertData;
    }

    public function afterSave()
    {
        if ($this->product_ids) {
            foreach ($this->product_ids as $id => $productId) {
                $product = \Samvol\Inventory\Models\Product::find($productId);
                $this->products()->attach($productId, ['quantity' => $product->quantity]);
            }
        }
    }

    public $rules = [];

}
