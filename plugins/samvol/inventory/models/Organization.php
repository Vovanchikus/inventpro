<?php namespace Samvol\Inventory\Models;

use Model;

class Organization extends Model
{
    public $table = 'samvol_inventory_organizations';

    protected $fillable = [
        'name',
        'code',
        'is_active',
    ];

    public $hasMany = [
        'users' => [\Winter\User\Models\User::class, 'key' => 'organization_id'],
    ];
}
