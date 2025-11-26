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

    public function getOperationTypeAttribute()
    {
        return $this->operation && $this->operation->type ? $this->operation->type->name : '-';
    }


    public function getFirstDocumentAttribute()
    {
        $first = $this->operation->documents()->orderBy('id', 'asc')->first();
        $firstDate = $this->operation->documents()->orderBy('id', 'asc')->first();
        if (!$first){
            return 'Документ отсутствует';
        } elseif (!$firstDate) {
            return 'Дата не указана';
        }
        $date = \Carbon\Carbon::parse($first->doc_date)->format('d.m.Y');
        return $first ? $first->doc_name . ' №' . $first->doc_num . ', ' . $date : '-';
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
