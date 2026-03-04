<?php namespace Samvol\Inventory\Components;

use Cms\Classes\ComponentBase;
use Samvol\Inventory\Classes\AdminPageDataService;

class SettingsPageData extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Settings Page Data',
            'description' => 'Подготавливает данные для страницы настроек',
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun()
    {
        $service = new AdminPageDataService();
        $data = $service->buildSettingsPageData();

        foreach ($data as $key => $value) {
            $this->page[$key] = $value;
        }
    }
}
