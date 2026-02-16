<?php namespace Samvol\Inventory\Classes\Transformers;

use Samvol\Inventory\Classes\Transformers\DocumentTransformer;

class OperationTransformer
{
    public static function one($op)
    {
        // Собираем уникальные контрагенты из pivot-таблицы
        $counteragents = $op->products->pluck('pivot.counteragent')->filter()->unique()->values();

        return [
            'id'           => $op->id,
            'type'         => $op->type?->name,
            'created_at'   => $op->created_at?->toDateTimeString(),

            // Пивот-товары
            'products' => $op->products->map(function ($p) {
                return [
                    'id'       => $p->id,
                    'name'     => $p->name,
                    'quantity' => $p->pivot?->quantity,
                    'counteragent' => $p->pivot?->counteragent, // добавили для каждого товара
                ];
            }),

            // Контрагенты операции
            'counteragents' => $counteragents,

            // Документы
            'documents' => $op->documents->map(function ($doc) {
                return DocumentTransformer::one($doc);
            })
        ];
    }

    public static function collection($items)
    {
        return $items->map(fn($op) => self::one($op));
    }
}
