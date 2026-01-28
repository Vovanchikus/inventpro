<?php namespace Samvol\Inventory\Models;

use Winter\Storm\Database\Pivot;

class NoteProduct extends Pivot
{
    use \Winter\Storm\Database\Traits\Validation;

    public $table = 'samvol_inventory_note_products';
    public $timestamps = false;

    protected $fillable = ['quantity', 'sum', 'counteragent'];

    public $belongsTo = [
        'product' => ['Samvol\Inventory\Models\Product'],
        'note' => ['Samvol\Inventory\Models\Note'],
    ];

    public $rules = [];
}
