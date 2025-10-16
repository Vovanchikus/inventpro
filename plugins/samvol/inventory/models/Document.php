<?php namespace Samvol\Inventory\Models;

use Model;

/**
 * Model
 */
class Document extends Model
{
    use \Winter\Storm\Database\Traits\Validation;

    public $table = 'samvol_inventory_documents';

    public $belongsTo = [
        'operation' => \Samvol\Inventory\Models\Operation::class,
    ];

    public $rules = [
    ];

    public $jsonable = [];
}
