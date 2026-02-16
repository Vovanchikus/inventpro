<?php namespace Samvol\Inventory\Classes\Transformers;

use Samvol\Inventory\Models\Category;

class CategoryTransformer
{
    public static function one(Category $category)
    {
        return [
            'id'         => $category->id,
            'name'       => $category->name,
            'slug'       => $category->slug,
            'parent_id'  => $category->parent_id,
            'children'   => self::children($category),
        ];
    }

    protected static function children(Category $category)
    {
        if (!$category->children()->exists()) {
            return [];
        }

        return $category->children->map(function ($child) {
            return self::one($child);
        })->toArray();
    }

    public static function collection($categories)
    {
        return $categories->map(fn($cat) => self::one($cat))->toArray();
    }
}
