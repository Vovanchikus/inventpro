<?php namespace Samvol\Inventory\Models;

use Model;
use DB;
use Log;
use Carbon\Carbon;

class Operation extends Model
{
    use \Winter\Storm\Database\Traits\Validation;
    use \Winter\Storm\Database\Traits\Sluggable;

    public $table = 'samvol_inventory_operations';

    protected $fillable = ['type_id', 'is_draft', 'is_posted', 'note_id'];
    protected $slugs = ['slug' => 'id'];

    /* -----------------------------------------------------------------
     | Relations
     |-----------------------------------------------------------------*/

    public $belongsTo = [
        'type' => [
            'Samvol\Inventory\Models\OperationType',
            'key' => 'type_id',
        ],
        'note' => [
            'Samvol\\Inventory\\Models\\Note',
            'key' => 'note_id'
        ]
    ];

    public $belongsToMany = [
        'products' => [
            'Samvol\Inventory\Models\Product',
            'table' => 'samvol_inventory_operation_products',
            'pivot' => ['quantity', 'sum', 'counteragent'],
            'pivotModel' => \Samvol\Inventory\Models\OperationProduct::class,
            'timestamps' => true,
            'detach' => true,
        ]
    ];

    public $hasMany = [
        'documents' => [
            'Samvol\Inventory\Models\Document',
            'key' => 'operation_id',
        ],
        'workflowNotes' => [
            'Samvol\Inventory\Models\WorkflowNoteOperation',
            'key' => 'operation_id',
        ],
    ];

    /* -----------------------------------------------------------------
     | Hooks
     |-----------------------------------------------------------------*/

    /**
     * Проверка перед сохранением — нельзя списывать больше, чем есть
     */
    public function beforeSave()
    {
        // Если операция черновая — не проводить проверок остатков
        if (!empty($this->is_draft)) {
            return;
        }

        if (!$this->type || !$this->products) {
            return;
        }

        $isOutgoing = in_array(
            mb_strtolower(trim($this->type->name)),
            ['расход', 'передача']
        );

        foreach ($this->products as $product) {
            $pivotQty = abs($product->pivot->quantity);

            $currentQty = DB::table('samvol_inventory_operation_products as op')
                ->join('samvol_inventory_operations as o', 'op.operation_id', '=', 'o.id')
                ->join('samvol_inventory_operation_types as t', 'o.type_id', '=', 't.id')
                ->where('op.product_id', $product->id)
                ->where('o.id', '<>', $this->id)
                ->sum(DB::raw("
                    CASE
                        WHEN LOWER(t.name) = 'приход' THEN op.quantity
                        ELSE -op.quantity
                    END
                "));

            Log::info("BeforeSave: {$product->name} | Qty={$pivotQty} | Current={$currentQty}");

            if ($isOutgoing && $pivotQty > $currentQty) {
                throw new \Exception(
                    "Ошибка! Нельзя передать {$pivotQty} ед. товара {$product->name}. На складе всего {$currentQty}"
                );
            }
        }
    }

    /**
     * После сохранения корректируем pivot quantity и sum
     */
    public function afterSave()
    {
        // Если операция черновая — не трогаем pivot и склад
        if (!empty($this->is_draft)) {
            return;
        }

        if (!$this->type || !$this->products) {
            return;
        }

        $type = mb_strtolower(trim($this->type->name));
        $isIncoming = $type === 'приход';
        $isOutgoing = in_array($type, ['расход', 'передача']);

        foreach ($this->products as $product) {
            $pivot = $product->pivot;
            if (!$pivot) {
                continue;
            }

            $qty = abs($pivot->quantity);
            $pivot->quantity = $qty;

            $currentQty = $product->calculated_quantity;
            $currentSum = $product->calculated_sum;

            if ($isIncoming) {
                $pivot->sum = $pivot->sum ?: round($qty * $product->price, 2);
            }

            if ($isOutgoing) {
                $pivot->sum = $currentQty > 0
                    ? round($currentSum * ($qty / $currentQty), 2)
                    : 0;
            }

            Log::info("AfterSave: {$product->name} | Qty={$qty} | Sum={$pivot->sum}");

            $pivot->save();
        }
    }

    /* -----------------------------------------------------------------
     | Helpers
     |-----------------------------------------------------------------*/

    /**
     * Количество для отображения (со знаком)
     */
    public function getQuantityForProduct($product)
    {
        if (!$this->type) {
            return $product->pivot->quantity;
        }

        $isIncoming = mb_strtolower(trim($this->type->name)) === 'приход';

        return $isIncoming
            ? abs($product->pivot->quantity)
            : -abs($product->pivot->quantity);
    }

    /* -----------------------------------------------------------------
     | Accessors (FIRST VALUES)
     |-----------------------------------------------------------------*/

    /**
     * Первый контрагент
     */
    public function getFirstCounteragentAttribute()
    {
        $product = $this->products->first();
        return $product?->pivot?->counteragent;
    }

    /**
     * Первый документ (МОДЕЛЬ)
     */
    public function getFirstDocumentAttribute()
    {
        if ($this->relationLoaded('documents')) {
            return $this->documents->sortBy('id')->first();
        }

        return $this->documents()->orderBy('id')->first();
    }

    /**
     * Дата первого документа
     */
    public function getFirstDocumentDateAttribute()
    {
        $doc = $this->first_document;

        return $doc && $doc->doc_date
            ? Carbon::parse($doc->doc_date)->format('d.m.Y')
            : null;
    }

    /**
     * Назначение первого документа
     */
    public function getFirstDocumentPurposeAttribute()
    {
        return $this->first_document->doc_purpose ?? null;
    }

    /**
     * Номер первого документа
     */
    public function getFirstDocumentNumberAttribute()
    {
        return $this->first_document->doc_num ?? null;
    }

    /* -----------------------------------------------------------------
     | Validation
     |-----------------------------------------------------------------*/

    public $rules = [];
    public $jsonable = [];
}
