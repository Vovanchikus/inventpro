<?php namespace Samvol\Inventory;

use System\Classes\PluginBase;
use Backend;
use Route;
use Samvol\Inventory\Controllers\DocumentsDownloadController;


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
            \Samvol\Inventory\Components\OperationInfo::class => 'operationInfo',
            \Samvol\Inventory\Components\QrProductCode::class => 'qrProductCode',
            \Samvol\Inventory\Components\WorkflowNotesFrontend::class => 'workflowNotesFrontend',
        ];
    }



    public function boot()
    {
        Route::get('document/{id}', [DocumentsDownloadController::class, 'download']);
    }


    public function registerSettings()
    {
    }
}
