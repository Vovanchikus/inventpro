<?php namespace Samvol\Inventory\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Log;
use ApplicationException;
use Samvol\Inventory\Models\Operation;
use Samvol\Inventory\Models\Product;

class Operations extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Backend\Behaviors\RelationController',
    ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $relationConfig = 'config_relation.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Samvol.Inventory', 'inventory', 'operations');
    }

    public function formExtendFields($form)
    {
        $operation = $this->formGetModel();
        if (!$operation || !$operation->exists) {
            $this->vars['infoMessage'] = 'Сначала сохраните операцию, чтобы добавлять продукты';
        }
    }
}
