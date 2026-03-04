<?php namespace Samvol\Inventory\Models;

use Model;

class DocTemplateSetting extends Model
{
    public $table = 'samvol_inventory_doc_template_settings';

    protected $fillable = [
        'scope_key',
        'key',
        'value',
    ];
}
