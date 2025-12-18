<?php namespace Samvol\Inventory\Classes\Transformers;

use URL;

class DocumentTransformer
{
    public static function one($doc)
    {
        return [
            'id'         => $doc->id,
            'name'       => $doc->doc_name,
            'number'     => $doc->doc_num,
            'date'       => $doc->doc_date,
            'file_url'   => $doc->doc_file ? URL::to('/api/documents/file/' . $doc->id) : null,
            'file_name'  => $doc->doc_file?->file_name,
            'created_at' => $doc->created_at?->toDateTimeString(),
        ];
    }

    public static function collection($items)
    {
        return $items->map(fn($i) => self::one($i));
    }
}
