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

    protected $fillable = [
        'name',
        'quantity',
        'unit',
        'inv_number',
        'price',
        'sum',
    ];

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
                    WHEN LOWER(t.name) = 'передача' THEN -op.quantity
                    WHEN LOWER(t.name) = 'списание' THEN -op.quantity
                    WHEN LOWER(t.name) = 'импорт' THEN op.quantity
                    WHEN LOWER(t.name) = 'импорт приход' THEN op.quantity
                    WHEN LOWER(t.name) = 'импорт расход' THEN -op.quantity
                    ELSE 0
                END
            "));

        return max($total, 0);
    }


    public $rules = [
    ];

    public $jsonable = [];
}
