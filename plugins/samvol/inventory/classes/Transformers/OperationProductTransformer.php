<?php namespace Samvol\Inventory\Classes\Transformers;

use Samvol\Inventory\Models\OperationProduct;
use Carbon\Carbon;

class OperationProductTransformer
{
    public static function one(OperationProduct $item)
    {
        $lastDoc = $item->operation->documents->sortByDesc('doc_date')->first();

        return [
            'id'           => $item->id,
            'product'      => $item->product ? [
                'id'        => $item->product->id,
                'name'      => $item->product->name,
                'unit'      => $item->product->unit,
                'inv_number'=> $item->product->inv_number,
                'price'     => $item->product->price,
            ] : null,
            'operation'    => $item->operation ? [
                'id'   => $item->operation->id,
                'type' => $item->operation->type ? [
                    'id'   => $item->operation->type->id,
                    'name' => $item->operation->type->name,
                ] : null,
            ] : null,
            'counteragent' => $item->counteragent,
            'quantity'     => $item->quantity,
            'doc_date'     => $lastDoc ? Carbon::parse($lastDoc->doc_date)->format('d.m.Y') : null,
            'doc_name'     => $lastDoc ? $lastDoc->doc_name : null,
            'doc_num'      => $lastDoc ? $lastDoc->doc_num : null,
        ];
    }

    public static function collection($items)
    {
        return $items->map(fn($item) => self::one($item));
    }
}
