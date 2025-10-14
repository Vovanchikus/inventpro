<?php namespace Samvol\Inventory\Models;

use Winter\Storm\Database\Pivot;

class OperationProduct extends Pivot
{
    use \Winter\Storm\Database\Traits\Validation;

    public $table = 'samvol_inventory_operation_products';
    public $timestamps = false;

    protected $fillable = ['quantity'];

    public $belongsTo = [
        'product' => ['Samvol\Inventory\Models\Product'],
        'operation' => ['Samvol\Inventory\Models\Operation'],
    ];

    public $rules = [];

}
