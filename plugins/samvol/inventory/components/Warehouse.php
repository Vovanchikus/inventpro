<?php namespace Samvol\Inventory\Components;

use Cms\Classes\ComponentBase;
use Samvol\Inventory\Models\Product;
class Warehouse extends ComponentBase
{
    public $products;


    public function componentDetails()
    {
        return [
            'name'        => 'Склад',
            'description' => 'Все данные со склада'
        ];
    }

    public function onRun(){
        $this->products = Product::all();
        $this->page['products'] = $this->products;
    }

    /**
     * Returns the properties provided by the component
     */
    public function defineProperties()
    {
        return [];
    }
}
