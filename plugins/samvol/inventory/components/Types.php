<?php namespace Samvol\Inventory\Components;

use Cms\Classes\ComponentBase;
use Samvol\Inventory\Models\OperationType;

class Types extends ComponentBase
{

    public $types;

    /**
     * Gets the details for the component
     */
    public function componentDetails()
    {
        return [
            'name'        => 'Types Component',
            'description' => 'No description provided yet...'
        ];
    }


    public function onRun(){
        $this->types = OperationType::all();
        $this->page['types'] = $this->types;
    }
    /**
     * Returns the properties provided by the component
     */
    public function defineProperties()
    {
        return [];
    }
}
