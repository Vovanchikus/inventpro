<?php namespace Samvol\Inventory\Models;

use Model;

class Note extends Model
{
    use \Winter\Storm\Database\Traits\Validation;

    public $table = 'samvol_inventory_notes';

    protected $fillable = ['title', 'description', 'due_date', 'status'];
    public $jsonable = [];

    public $belongsToMany = [
        'products' => [
            'Samvol\Inventory\Models\Product',
            'table' => 'samvol_inventory_note_products',
            'pivot' => ['quantity','sum','counteragent'],
            'timestamps' => true
        ]
    ];

    public static $statusLabels = [
        'new' => 'Нова',
        'in_development' => 'В розробці',
        'document_prepared' => 'Документи готові',
        'in_accounting' => 'В бухгалтерії',
        'completed' => 'Виконано',
    ];

    public function getHumanStatusAttribute()
    {
        $s = $this->status ?: 'new';
        return static::$statusLabels[$s] ?? $s;
    }

    public $hasMany = [
        'operations' => [
            \Samvol\Inventory\Models\Operation::class,
            'key' => 'note_id'
        ]
    ];

    public function beforeDelete()
    {
        try {
            $draftOperations = $this->operations()->where('is_draft', 1)->get();
            foreach ($draftOperations as $op) {
                try {
                    $op->documents()->delete();
                } catch (\Exception $e) {
                    \Log::warning('[samvol] Note beforeDelete: failed to delete draft op documents: ' . $e->getMessage());
                }

                try {
                    $op->products()->detach();
                } catch (\Exception $e) {
                    \Log::warning('[samvol] Note beforeDelete: failed to detach draft op products: ' . $e->getMessage());
                }

                try {
                    $op->workflowNotes()->delete();
                } catch (\Exception $e) {
                    \Log::warning('[samvol] Note beforeDelete: failed to delete draft op workflow links: ' . $e->getMessage());
                }

                $op->delete();
            }
        } catch (\Exception $e) {
            \Log::warning('[samvol] Note beforeDelete: failed to cleanup draft operations: ' . $e->getMessage());
        }

        try {
            $this->products()->detach();
        } catch (\Exception $e) {
            \Log::warning('[samvol] Note beforeDelete: failed to detach note products: ' . $e->getMessage());
        }
    }

    public $rules = [];

    public function recalcStatus()
    {
        $ops = $this->operations()->get();

        if ($ops->isEmpty()) {
            $this->status = 'in_development';
            $this->save();
            return $this->status;
        }

        $priority = [
            'in_development' => 1,
            'document_prepared' => 2,
            'in_accounting' => 3,
            'completed' => 4,
        ];

        $minKey = null;
        $minPriority = PHP_INT_MAX;

        foreach ($ops as $op) {
            if (!empty($op->is_posted)) {
                $key = 'completed';
            } elseif (!empty($op->accounting_at)) {
                $key = 'in_accounting';
            } elseif (!empty($op->is_draft)) {
                $key = 'document_prepared';
            } else {
                $key = 'in_development';
            }

            $p = $priority[$key] ?? PHP_INT_MAX;
            if ($p < $minPriority) {
                $minPriority = $p;
                $minKey = $key;
            }
        }

        $this->status = $minKey ?: 'in_development';

        $this->save();
        return $this->status;
    }
}
