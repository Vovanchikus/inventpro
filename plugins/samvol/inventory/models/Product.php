<?php namespace Samvol\Inventory\Models;

use Model;
use DB;

/**
 * Model
 */
class Product extends Model
{
    use \Winter\Storm\Database\Traits\Validation;

    public $table = 'samvol_inventory_products';

    public $belongsToMany = [
        'operations' => [
            'Samvol\Inventory\Models\Operation',
            'table' => 'samvol_inventory_operation_products',
            'pivot' => ['quantity'],
            'timestamp' => true
        ]
    ];

    public function getCalculatedQuantityAttribute()
    {
        $total = DB::table('samvol_inventory_operation_products as op')
            ->join('samvol_inventory_operations as o', 'op.operation_id', '=', 'o.id')
            ->join('samvol_inventory_operation_types as t', 'o.type_id', '=', 't.id')
            ->where('op.product_id', $this->id)
            ->sum(DB::raw("
                CASE
                    WHEN LOWER(t.name) = 'приход' THEN op.quantity
                    ELSE -op.quantity
                END
            "));

        // Чтобы не было отрицательного остатка
        return max($total, 0);
    }


    public $rules = [
    ];

    public $jsonable = [];
}
