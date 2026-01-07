<?php namespace Samvol\Inventory\Classes\Transformers;

use Samvol\Inventory\Models\Category;
use Samvol\Inventory\Models\Product;

class ProductTransformer
{
    /**
     * Преобразование одного продукта
     */
    public static function one(Product $item): array
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
            'category'    => $item->relationLoaded('category') && $item->category
                ? self::category($item->category)
                : null,

            // 🔥 ВАЖНО: images должны быть загружены через with('images')
            'images' => $item->relationLoaded('images')
                ? $item->images
                    ->map(fn($file) => url($file->getPath()))
                    ->values()
                    ->toArray()
                : [],

            'created_at'  => $item->created_at
                ? $item->created_at->toDateTimeString()
                : null,

            'updated_at'  => $item->updated_at
                ? $item->updated_at->toDateTimeString()
                : null,
        ];
    }

    /**
     * Коллекция продуктов
     */
    public static function collection($items)
    {
        return $items->map(fn($item) => self::one($item))->values();
    }

    /**
     * Категория (рекурсивно)
     */
    public static function category(Category $category): array
    {
        return [
            'id'        => $category->id,
            'name'      => $category->name,
            'slug'      => $category->slug,
            'parent_id' => $category->parent_id,

            'children' => $category->relationLoaded('children')
                ? $category->children
                    ->map(fn($child) => self::category($child))
                    ->values()
                    ->toArray()
                : [],
        ];
    }
}
