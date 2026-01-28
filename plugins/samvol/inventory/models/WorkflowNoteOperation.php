<?php namespace Samvol\Inventory\Models;

use Model;

class WorkflowNoteOperation extends Model
{
    protected $table = 'samvol_inventory_workflow_note_operation';

    public $timestamps = true;

    protected $fillable = [
        'workflow_note_id',
        'operation_id',
        'workflow_note_item_id',
        'quantity_closed',
    ];

    // Связь с товаром в заметке
    public $belongsTo = [
        'noteItem' => [WorkflowNoteItem::class, 'key' => 'workflow_note_item_id'],
        'note'     => [WorkflowNote::class, 'key' => 'workflow_note_id'],
        'operation'=> [Operation::class, 'key' => 'operation_id'],
    ];
}
