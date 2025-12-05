<?php namespace Samvol\Inventory\Models;

use Model;

/**
 * Model
 */
class Document extends Model
{
    use \Winter\Storm\Database\Traits\Validation;

    protected $fillable = [
        'doc_name',
        'doc_num',
        'doc_date',
        'doc_file',
        'doc_purpose',
    ];

    public $table = 'samvol_inventory_documents';

    public $belongsTo = [
        'operation' => \Samvol\Inventory\Models\Operation::class,
    ];

    public $attachOne = [
        'doc_file' => \System\Models\File::class
    ];

    public function getDocFileFlagAttribute() {
        $doc_file = $this->doc_file;

        if($doc_file) {
            return 'PDF есть';
        } else {
            return '-';
        }
    }

    public $rules = [
    ];

    public $jsonable = [];
}
