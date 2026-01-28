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
        'new' => 'Новая',
        'in_development' => 'Документы в разработке',
        'document_prepared' => 'Документ разработан',
        'in_accounting' => 'В бухгалтерии',
        'completed' => 'Готово',
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

    public $rules = [];

    public function recalcStatus()
    {
        $hasFinal = $this->operations()->whereHas('documents')->exists();

        if ($hasFinal) {
            $this->status = 'completed';
        } else {
            $this->status = 'in_development';
        }

        $this->save();
        return $this->status;
    }
}
