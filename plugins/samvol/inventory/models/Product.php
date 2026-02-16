<?php namespace Samvol\Inventory\Models;

use Model;
use DB;
use System\Models\File;

class Product extends Model
{
    use \Winter\Storm\Database\Traits\Validation;
    use \Winter\Storm\Database\Traits\Sluggable;

    public $table = 'samvol_inventory_products';

    protected $slugs = ['slug' => 'inv_number'];

    protected $fillable = [
        'category_id',
        'name',
        'quantity',
        'unit',
        'inv_number',
        'price',
    ];

    public $belongsTo = [
        'category' => ['Samvol\Inventory\Models\Category']
    ];

    public $belongsToMany = [
        'operations' => [
            'Samvol\Inventory\Models\Operation',
            'table' => 'samvol_inventory_operation_products',
            'pivot' => ['quantity','sum'],
            'timestamps' => true
        ]
    ];

    public $attachMany = [
        'images' => File::class
    ];

    /**
     * Рассчитываем текущее количество
     */
    public function getCalculatedQuantityAttribute()
    {
        $total = DB::table('samvol_inventory_operation_products as op')
            ->join('samvol_inventory_operations as o', 'op.operation_id', '=', 'o.id')
            ->join('samvol_inventory_operation_types as t', 'o.type_id', '=', 't.id')
            ->where('op.product_id', $this->id)
            ->sum(DB::raw("
                CASE
                    WHEN LOWER(t.name) = 'приход' THEN op.quantity
                    WHEN LOWER(t.name) = 'передача' THEN -op.quantity
                    WHEN LOWER(t.name) = 'списание' THEN -op.quantity
                    WHEN LOWER(t.name) = 'импорт' THEN op.quantity
                    WHEN LOWER(t.name) = 'импорт приход' THEN op.quantity
                    WHEN LOWER(t.name) = 'импорт расход' THEN -op.quantity
                    ELSE 0
                END
            "));

        return max($total, 0);
    }

    /**
     * Рассчитываем текущую сумму
     */
    public function getCalculatedSumAttribute()
    {
        $total = DB::table('samvol_inventory_operation_products as op')
            ->join('samvol_inventory_operations as o', 'op.operation_id', '=', 'o.id')
            ->join('samvol_inventory_operation_types as t', 'o.type_id', '=', 't.id')
            ->where('op.product_id', $this->id)
            ->sum(DB::raw("
                CASE
                    WHEN LOWER(t.name) = 'приход' THEN op.sum
                    WHEN LOWER(t.name) = 'передача' THEN -op.sum
                    WHEN LOWER(t.name) = 'списание' THEN -op.sum
                    WHEN LOWER(t.name) = 'импорт' THEN op.sum
                    WHEN LOWER(t.name) = 'импорт приход' THEN op.sum
                    WHEN LOWER(t.name) = 'импорт расход' THEN -op.sum
                    ELSE 0
                END
            "));

        return max($total, 0);
    }

    /**
     * Возвращает массив с категориями по уровням: [level1, level2, level3, ...]
     */
    public function getCategoryTreeAttribute()
    {
        $tree = [];
        $category = $this->category;

        while ($category) {
            array_unshift($tree, $category); // вставляем в начало, чтобы был порядок родитель → потомок
            $category = $category->parent ?? null;
        }

        return $tree; // массив объектов Category
    }

    public $rules = [
    ];

    public $jsonable = [];
}
