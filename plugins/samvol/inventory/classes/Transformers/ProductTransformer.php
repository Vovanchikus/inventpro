<?php namespace Samvol\Inventory\Classes\Transformers;

class ProductTransformer
{
    public static function one($item)
    {
        return [
            'id'        => $item->id,
            'name'      => $item->name,
            'unit'      => $item->unit,
            'quantity'  => $item->calculated_quantity,
            'price'     => $item->price,
            'sum'       => $item->calculated_sum,
            'updated_at'=> $item->updated_at?->toDateTimeString(),
            'created_at'=> $item->created_at?->toDateTimeString(),
        ];
    }

    public static function collection($items)
    {
        return $items->map(fn($item) => self::one($item));
    }
}
