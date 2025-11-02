<?php namespace Samvol\Inventory\Models;

use Model;

class StockSnapshot extends Model
{
    use \Winter\Storm\Database\Traits\Validation;


    protected $table = 'samvol_inventory_stock_snapshots';

    protected $fillable = [
        'name',
        'snapshot_date',
        'data',
    ];


    public $rules = [
    ];


    public $jsonable = ['data'];
}
