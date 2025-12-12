<?php namespace Samvol\Inventory\Classes\Transformers;

use Samvol\Inventory\Models\Category;

class ProductTransformer
{
    public static function one($item)
    {
        return [
            'id'          => $item->id,
            'name'        => $item->name,
            'inv_number'  => $item->inv_number,
            'unit'        => $item->unit,
            'quantity'    => $item->calculated_quantity,
            'price'       => $item->price,
            'sum'         => $item->calculated_sum,
            'category_id' => $item->category_id,
            'category'    => $item->category ? self::category($item->category) : null,
            'updated_at'  => $item->updated_at?->toDateTimeString(),
            'created_at'  => $item->created_at?->toDateTimeString(),
        ];
    }

    public static function collection($items)
    {
        return $items->map(fn($item) => self::one($item));
    }

    protected static function category(Category $category)
    {
        return [
            'id'       => $category->id,
            'name'     => $category->name,
            'slug'     => $category->slug,
            'parent_id'=> $category->parent_id,
            'children' => $category->children ? $category->children->map(fn($c) => self::category($c))->toArray() : [],
        ];
    }
}
