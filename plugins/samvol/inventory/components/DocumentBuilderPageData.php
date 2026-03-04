<?php namespace Samvol\Inventory\Components;

use Cms\Classes\ComponentBase;
use Samvol\Inventory\Classes\AdminPageDataService;

class DocumentBuilderPageData extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Document Builder Page Data',
            'description' => 'Подготавливает данные для страницы формирования документов',
        ];
    }

    public function defineProperties()
    {
        return [
            'operation_id' => [
                'title' => 'ID операции',
                'type' => 'string',
                'default' => '',
            ],
        ];
    }

    public function onRun()
    {
        $rawOperationId = input('operation_id', $this->property('operation_id'));
        $operationId = (int) $rawOperationId;

        $service = new AdminPageDataService();
        $data = $service->buildDocumentBuilderData($operationId);

        foreach ($data as $key => $value) {
            $this->page[$key] = $value;
        }
    }
}
