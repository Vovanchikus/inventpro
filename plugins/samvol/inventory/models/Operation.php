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

    protected $fillable = ['type_id', 'is_draft', 'is_posted', 'note_id', 'draft_products'];
    protected $dates = ['prepared_at', 'accounting_at', 'completed_at'];
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
        $counteragent = $product?->pivot?->counteragent;

        if (empty($counteragent) && $this->relationLoaded('note') && $this->note) {
            $noteProduct = $this->note->products?->first();
            $counteragent = $noteProduct?->pivot?->counteragent;
        }

        if (empty($counteragent) && !empty($this->draft_products)) {
            $items = $this->draft_products;
            if (is_string($items)) {
                $items = json_decode($items, true);
            }
            if (is_array($items) && !empty($items)) {
                $first = $items[0] ?? null;
                if (is_array($first) && !empty($first['counteragent'])) {
                    $counteragent = $first['counteragent'];
                }
            }
        }

        return $counteragent;
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

    /**
     * Название документа для карточки (с fallback на заметку)
     */
    public function getCardDocumentTitleAttribute()
    {
        if ($this->first_document && !empty($this->first_document->doc_name)) {
            return $this->first_document->doc_name;
        }

        if ($this->relationLoaded('note') && $this->note && !empty($this->note->title)) {
            return $this->note->title;
        }

        return null;
    }

    /**
     * Номер документа для карточки
     */
    public function getCardDocumentNumberAttribute()
    {
        return $this->first_document_number;
    }

    /**
     * Дата документа для карточки
     */
    public function getCardDocumentDateAttribute()
    {
        return $this->first_document_date;
    }

    /**
     * Підстава для карточки
     */
    public function getCardDocumentPurposeAttribute()
    {
        return $this->first_document_purpose;
    }

    /**
     * Количество товаров для карточки
     */
    public function getItemsCountAttribute()
    {
        $count = 0;

        try {
            if ($this->relationLoaded('products')) {
                $count = $this->products->count();
            } else {
                $count = $this->products()->count();
            }
        } catch (\Exception $e) {
            $count = 0;
        }

        if (!$count && $this->relationLoaded('note') && $this->note) {
            try {
                $count = $this->note->products?->count() ?? 0;
            } catch (\Exception $e) {
                $count = 0;
            }
        }

        if (!$count && !empty($this->draft_products)) {
            $items = $this->draft_products;
            if (is_string($items)) {
                $items = json_decode($items, true);
            }
            if (is_array($items)) {
                $count = count($items);
            }
        }

        return $count;
    }

    /* -----------------------------------------------------------------
     | Validation
     |-----------------------------------------------------------------*/

    public $rules = [];
    public $jsonable = ['draft_products'];
}
