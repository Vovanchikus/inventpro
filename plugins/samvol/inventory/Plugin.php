<?php namespace Samvol\Inventory;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
         return [
            \Samvol\Inventory\Components\Warehouse::class => 'warehouse',
            \Samvol\Inventory\Components\ImportExcel::class => 'importExcel',
        ];
    }

    public function registerSettings()
    {
    }
}
