<?php namespace Samvol\Inventory\Models;

use Model;

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

    public function getQuantityAttribute()
    {
        return $this->operations()->sum('samvol_inventory_operation_products.quantity');
    }


    public $rules = [
    ];

    public $jsonable = [];
}
