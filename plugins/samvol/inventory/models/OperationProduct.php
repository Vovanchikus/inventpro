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
        'operation' => ['Samvol\Inventory\Models\Operation']
    ];

    public function getOperationTypeAttribute()
    {
        return $this->operation && $this->operation->type ? $this->operation->type->name : '-';
    }

    public function getDocumentNameAttribute()
    {
        $doc_name = $this->operation ? $this->operation->doc_name : '-';
        $doc_num = $this->operation ? $this->operation->doc_num : '-';

        return $doc_name . ' №' . $doc_num;
    }

    public function getProductNameAttribute()
    {
        return $this->product ? $this->product->name : '-';
    }

    public function getProductUnitAttribute()
    {
        return $this->product ? $this->product->unit : '-';
    }

    public $rules = [];

}
