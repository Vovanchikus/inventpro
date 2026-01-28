<?php namespace Samvol\Inventory\Models;

use Model;

class WorkflowNoteItem extends Model
{
    use \Winter\Storm\Database\Traits\Validation;


    public $table = 'samvol_inventory_workflow_note_items';

    protected $fillable = [
        'note_id',
        'workflow_note_id',
        'product_id',
        'required_quantity',
        'completed_quantity',
    ];

    public $belongsTo = [
        'note' => [
            \Samvol\Inventory\Models\WorkflowNote::class,
            'key' => 'workflow_note_id'
        ],
        'product' => [
            \Samvol\Inventory\Models\Product::class,
            'key' => 'product_id'
        ],
    ];

    public $hasMany = [
        'operations' => [
            \Samvol\Inventory\Models\WorkflowNoteOperation::class,
            'key' => 'workflow_note_item_id'
        ],
    ];

    public function getClosedQuantityAttribute()
    {
        // Суммируем все закрытые количества из операций через пивот
        return $this->operations()->sum('quantity_closed');
    }

    public function getIsClosedAttribute()
    {
        // Товар закрыт, если сумма закрытых >= required_quantity (или просто > 0)
        return $this->closed_quantity >= ($this->required_quantity ?? 1);
    }


    public $rules = [
    ];


    public $jsonable = [];
}
