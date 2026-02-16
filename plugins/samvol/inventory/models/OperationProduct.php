<?php namespace Samvol\Inventory\Models;

use Winter\Storm\Database\Pivot;

class OperationProduct extends Pivot
{
    use \Winter\Storm\Database\Traits\Validation;

    public $table = 'samvol_inventory_operation_products';
    public $timestamps = false;

    protected $fillable = ['quantity', 'sum', 'counteragent'];

    public $belongsTo = [
        'product' => ['Samvol\Inventory\Models\Product'],
        'operation' => ['Samvol\Inventory\Models\Operation'],
    ];

    /*
     |--------------------------------------------------------------------------
     | Быстрые аксессоры (БЕЗ ЗАПРОСОВ)
     |--------------------------------------------------------------------------
     */

    public function getOperationTypeAttribute()
    {
        return $this->operation && $this->operation->type
            ? $this->operation->type->name
            : '-';
    }

    public function getProductNameAttribute()
    {
        return $this->product->name ?? '-';
    }

    public function getProductUnitAttribute()
    {
        return $this->product->unit ?? '-';
    }

    public function getProductInvNumberAttribute()
    {
        return $this->product->inv_number ?? '-';
    }

    public function getProductPriceAttribute()
    {
        return $this->product->price ?? '-';
    }

    public $rules = [];
}
