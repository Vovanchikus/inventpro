<?php namespace Samvol\Inventory\Models;

use Model;

class DocTemplatePerson extends Model
{
    public $table = 'samvol_inventory_doc_template_people';

    protected $fillable = [
        'scope_key',
        'role_key',
        'name',
        'position',
        'sort_order',
    ];
}
