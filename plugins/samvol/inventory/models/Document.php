<?php namespace Samvol\Inventory\Models;

use Model;

/**
 * Model
 */
class Document extends Model
{
    use \Winter\Storm\Database\Traits\Validation;

    protected $fillable = [
        'operation_id',
        'doc_name',
        'doc_num',
        'doc_date',
        'doc_file',
        'doc_purpose',
        'mime_type',
        'file_size',
        'organization_id',
    ];

    public $table = 'samvol_inventory_documents';

    public $belongsTo = [
        'operation' => \Samvol\Inventory\Models\Operation::class,
        'organization' => [\Samvol\Inventory\Models\Organization::class, 'key' => 'organization_id'],
    ];

    public $attachOne = [
        'doc_file' => \System\Models\File::class
    ];

    public function getDocFileFlagAttribute()
    {
        return $this->doc_file !== null;
    }

    public function scopeApiList($query)
    {
        return $query->select([
            'id',
            'operation_id',
            'doc_name',
            'doc_num',
            'doc_date',
            'doc_purpose',
            'organization_id',
            'mime_type',
            'file_size',
            'updated_at',
        ]);
    }

    public $rules = [
    ];

    public $jsonable = [];
}
