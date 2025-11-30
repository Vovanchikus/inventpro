<?php namespace Samvol\Inventory\Components;

use Cms\Classes\ComponentBase;
use Samvol\Inventory\Models\OperationProduct;

class History extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'История операций',
            'description' => 'Вывод информации об истории операций'
        ];
    }

    public $histories;

    public function onRun(){
        $this->histories = OperationProduct::whereDoesntHave('operation', function($q) {
            $q->whereIn('type_id', ['4', '6', '7']);
        })->get();
        // return $histories;
        $this->page['histories'] = $this->histories;
    }


    public function defineProperties()
    {
        return [];
    }
}
