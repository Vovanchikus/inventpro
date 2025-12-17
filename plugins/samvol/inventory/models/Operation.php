<?php namespace Samvol\Inventory\Models;

use Model;
use DB;
use Log;

class Operation extends Model
{
    use \Winter\Storm\Database\Traits\Validation;
    use \Winter\Storm\Database\Traits\Sluggable;

    public $table = 'samvol_inventory_operations';

    protected $fillable = ['type_id'];
    protected $slugs = ['slug' => 'id'];

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
            'pivot' => ['quantity','sum','counteragent'],
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
     * Проверка перед сохранением — нельзя списывать больше, чем есть
     */
    public function beforeSave()
    {
        if (!$this->type || !$this->products) return;

        $isOutgoing = in_array(mb_strtolower(trim($this->type->name)), ['расход','передача']);

        foreach ($this->products as $product) {
            $pivotQty = abs($product->pivot->quantity);

            $currentQty = DB::table('samvol_inventory_operation_products as op')
                ->join('samvol_inventory_operations as o', 'op.operation_id', '=', 'o.id')
                ->join('samvol_inventory_operation_types as t', 'o.type_id', '=', 't.id')
                ->where('op.product_id', $product->id)
                ->where('o.id', '<>', $this->id)
                ->sum(DB::raw("CASE WHEN LOWER(t.name) = 'приход' THEN op.quantity ELSE -op.quantity END"));

            Log::info("BeforeSave: Product {$product->name} | PivotQty={$pivotQty} | CurrentQty={$currentQty}");

            if ($isOutgoing && $pivotQty > $currentQty) {
                Log::error("Невозможно списать больше, чем есть: {$pivotQty} > {$currentQty}");
                throw new \Exception("Ошибка! Нельзя передать {$pivotQty} ед. товара {$product->name}. На складе всего {$currentQty}");
            }
        }
    }

    /**
     * После сохранения корректируем pivot quantity и sum
     */
    public function afterSave()
    {
        if (!$this->type || !$this->products) return;

        $isIncoming = mb_strtolower(trim($this->type->name)) === 'приход';
        $isOutgoing = in_array(mb_strtolower(trim($this->type->name)), ['расход','передача']);

        foreach ($this->products as $product) {
            $pivot = $product->pivot;
            if (!$pivot) continue;

            $qty = abs($pivot->quantity);
            $pivot->quantity = $qty;

            $currentQty = $product->calculated_quantity;
            $currentSum = $product->calculated_sum;

            if ($isIncoming) {
                $pivot->sum = $pivot->sum ?: round($qty * $product->price, 2);
            } elseif ($isOutgoing) {
                $pivot->sum = $currentQty > 0 ? round($currentSum * ($qty / $currentQty), 2) : 0;
            }

            Log::info("AfterSave: Product {$product->name} | PivotQty={$pivot->quantity} | PivotSum={$pivot->sum} | CurrentQty={$currentQty} | CurrentSum={$currentSum}");

            $pivot->save();
        }
    }

    /**
     * Количество для отображения
     */
    public function getQuantityForProduct($product)
    {
        if (!$this->type) return $product->pivot->quantity;

        $isIncoming = mb_strtolower(trim($this->type->name)) === 'приход';
        return $isIncoming ? abs($product->pivot->quantity) : -abs($product->pivot->quantity);
    }

    public function getFirstCounteragentAttribute()
    {
        $firstProduct = $this->products->first(); // первый товар в операции
        return $firstProduct ? $firstProduct->pivot->counteragent : null;
    }

    public function getFirstDocumentAttribute()
    {
        $first = $this->documents()->orderBy('id', 'asc')->first();
        if (!$first) {
            return null; // или 'Документ отсутствует'
        }

        $date = $first->doc_date ? \Carbon\Carbon::parse($first->doc_date)->format('d.m.Y') : '-';
        return $first->doc_name . ' №' . $first->doc_num . ', ' . $date;
    }

    public function getFirstDocumentDateAttribute()
    {
        if (!$this->relationLoaded('documents')) {
            return null;
        }

        $first = $this->documents->sortBy('id')->first();

        if (!$first || !$first->doc_date) {
            return null;
        }

        return \Carbon\Carbon::parse($first->doc_date)->format('d.m.Y');
    }

    public function getFirstDocumentPurposeAttribute()
    {
        $first = $this->documents()->orderBy('id', 'asc')->first();

        if (!$first || !$first->doc_purpose) {
            return null; // или '-'
        }
        return $first->doc_purpose;
    }



    public $rules = [];
    public $jsonable = [];
}
