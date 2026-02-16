<?php namespace Samvol\Inventory\Models;

use Model;

/**
 * Model
 */
class Category extends Model
{
    use \Winter\Storm\Database\Traits\Validation;
    use \Winter\Storm\Database\Traits\SoftDelete;
    use \Winter\Storm\Database\Traits\NestedTree;
    use \Winter\Storm\Database\Traits\Sluggable;

    public $fillable = ['name', 'parent_id'];

    protected $dates = ['deleted_at'];
    protected $slugs = ['slug' => 'name'];

    public $table = 'samvol_inventory_categories';

    public function getParentCategoryNameAttribute()
    {
        return $this->parent ? $this->parent->name : '-';
    }

    public $rules = [];
    public $jsonable = [];
}
