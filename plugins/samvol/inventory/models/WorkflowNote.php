<?php namespace Samvol\Inventory\Models;

use Model;

class WorkflowNote extends Model
{
    use \Winter\Storm\Database\Traits\Validation;

    public $table = 'samvol_inventory_workflow_notes';

    public $hasMany = [
        'items' => [
            \Samvol\Inventory\Models\WorkflowNoteItem::class,
            'key' => 'workflow_note_id'
        ],
        'operation' => [
            \Samvol\Inventory\Models\WorkflowNoteOperation::class,
            'key' => 'workflow_note_id'
        ],
    ];

    public $belongsToMany = [
        'operations' => [
            \Samvol\Inventory\Models\Operation::class,
            'table' => 'samvol_inventory_workflow_note_operation',
            'key' => 'workflow_note_id',
            'otherKey' => 'operation_id'
        ],
    ];

    public function getIsCompletedAttribute()
    {
        if (!$this->items->isEmpty()) {
            return false;
        }
        return $this->items->every(fn($item) =>
            ($item->is_closed)
        );
    }

    public $rules = [
    ];

    public $jsonable = [];
}
