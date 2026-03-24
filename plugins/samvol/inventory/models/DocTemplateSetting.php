<?php namespace Samvol\Inventory\Models;

use Model;

class DocTemplateSetting extends Model
{
    public $table = 'samvol_inventory_doc_template_settings';

    protected $fillable = [
        'organization_id',
        'scope_key',
        'key',
        'value',
    ];
}
