<?php namespace Samvol\Inventory\Components;

use Cms\Classes\ComponentBase;
use Samvol\Inventory\Models\Operation;

class OperationInfo extends ComponentBase
{
    public $operations;

    public function componentDetails()
    {
        return [
            'name'        => 'OperationInfo',
            'description' => 'Полная информация об операции'
        ];
    }

    public function onRun() {
        $this->operations = Operation::with('products')
        ->whereIn('type_id', ['1','2','3'])
        ->get();
        $this->page['operations'] = $this->operations;

        $this->page['plural'] = [$this, 'plural'];
    }

    public function defineProperties()
    {
        return [];
    }
}
