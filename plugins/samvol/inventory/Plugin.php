<?php namespace Samvol\Inventory;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
         return [
            \Samvol\Inventory\Components\Warehouse::class => 'warehouse',
            \Samvol\Inventory\Components\ImportExcel::class => 'importExcel',
            \Samvol\Inventory\Components\Types::class => 'types',
            \Samvol\Inventory\Components\AddOperation::class => 'addOperation',
            \Samvol\Inventory\Components\EditOperation::class => 'editOperation',
            \Samvol\Inventory\Components\History::class => 'history',
        ];
    }

    public function registerSettings()
    {
    }
}
