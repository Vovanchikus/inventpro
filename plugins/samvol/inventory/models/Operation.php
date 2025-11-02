<?php namespace Samvol\Inventory\Models;

use Model;
use DB;
use Carbon\Carbon;
use ValidationException;

class Operation extends Model
{
    use \Winter\Storm\Database\Traits\Validation;

    public $table = 'samvol_inventory_operations';

    protected $fillable = [
        'type_id',
    ];

    public $rules = [];

    public $belongsTo = [
        'type' => [
            'Samvol\Inventory\Models\OperationType',
            'key' => 'type_id',
        ]
    ];

    public $belongsToMany = [
        'products' => [
            'Samvol\Inventory\Models\Product',
            'table' => 'samvol_inventory_operation_products',
            'pivot' => ['quantity'], // всегда положительное число
            'pivotModel' => \Samvol\Inventory\Models\OperationProduct::class,
            'timestamps' => true,
            'detach' => true
        ]
    ];

    public $hasMany = [
        'documents' => [
            'Samvol\Inventory\Models\Document',
            'key' => 'operation_id'
        ]
    ];

    /**
     * Первый документ операции
     */
    public function getFirstDocumentAttribute()
    {
        $first = $this->documents()->orderBy('id', 'asc')->first();
        if (!$first) return 'Документ отсутствует';

        $date = $first->doc_date ? Carbon::parse($first->doc_date)->format('d.m.Y') : 'Дата не указана';
        return $first->doc_name . ' №' . $first->doc_num . ', ' . $date;
    }

    /**
     * Перед сохранением проверяем расход: нельзя списывать больше, чем есть
     */
    public function beforeSave()
    {
        if (!$this->type || !$this->products) return;

        $isOutgoing = mb_strtolower(trim($this->type->name)) === 'расход';
        if ($isOutgoing) {
            foreach ($this->products as $product) {
                $pivotQty = abs($product->pivot->quantity); // количество из формы

                // остаток на складе без учета текущей операции
                $currentQty = DB::table('samvol_inventory_operation_products as op')
                    ->join('samvol_inventory_operations as o', 'op.operation_id', '=', 'o.id')
                    ->join('samvol_inventory_operation_types as t', 'o.type_id', '=', 't.id')
                    ->where('op.product_id', $product->id)
                    ->where('o.id', '<>', $this->id) // исключаем текущую операцию
                    ->sum(DB::raw("CASE WHEN LOWER(t.name) = 'приход' THEN op.quantity ELSE -op.quantity END"));

                if ($pivotQty > $currentQty) {
                    throw new ValidationException([
                        'products' => "Ошибка! Нельзя списать {$pivotQty} ед. товара «{$product->name}». На складе всего {$currentQty}"
                    ]);
                }
            }
        }
    }

    /**
     * После сохранения корректируем количество в пивоте
     * Всегда положительное число, знак определяет тип операции
     */
    public function afterSave()
    {
        if (!$this->type || !$this->products) {
            return;
        }

        $isIncoming = mb_strtolower(trim($this->type->name)) === 'приход';

        foreach ($this->products as $product) {
            $pivot = $product->pivot;
            if (!$pivot) continue;

            $qty = abs($pivot->quantity);
            $pivot->quantity = $qty; // всегда положительное в пивоте
            $pivot->save();
        }
    }

    /**
     * Получаем количество прибавляемое/убавляемое для конкретного продукта
     * Используется в списке операций
     */
    public function getQuantityForProduct($product)
    {
        if (!$this->type) return $product->pivot->quantity;

        $isIncoming = mb_strtolower(trim($this->type->name)) === 'приход';
        return $isIncoming ? abs($product->pivot->quantity) : -abs($product->pivot->quantity);
    }
}
