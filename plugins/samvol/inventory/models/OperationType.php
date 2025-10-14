<?php namespace Samvol\Inventory\Models;

use Model;

/**
 * Model
 */
class OperationType extends Model
{
    use \Winter\Storm\Database\Traits\Validation;

    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;

    public $table = 'samvol_inventory_operation_types';

    public $hasMany = [
        'operations' => 'Samvol\Inventory\Models\Operation'
    ];

    public $rules = [
    ];

    public $jsonable = [];
}
