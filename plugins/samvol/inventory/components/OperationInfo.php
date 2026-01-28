<?php namespace Samvol\Inventory\Components;

use Cms\Classes\ComponentBase;
use Samvol\Inventory\Models\Operation;

class OperationInfo extends ComponentBase
{
    public $operations;
    public $operation_item;

    public function componentDetails()
    {
        return [
            'name'        => 'OperationInfo',
            'description' => 'Полная информация об операции'
        ];
    }

    public function onRun() {
        $this->operations = Operation::with(['products', 'documents', 'documents.doc_file'])
            ->whereIn('type_id', [1,2,3])
            ->whereHas('documents', function($q){
                $q->whereHas('doc_file');
            })
            ->get();
        $this->page['operations'] = $this->operations;

        $slug = $this->param('slug');
        $this->operation_item = Operation::where('slug', $slug)->first();
        $this->page['operation_item'] = $this->operation_item;
    }

    public function defineProperties()
    {
        return [];
    }
}
