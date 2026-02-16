<?php namespace Samvol\Inventory\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

use Samvol\Inventory\Models\WorkflowNote;
use Samvol\Inventory\Models\Product;
use Samvol\Inventory\Models\Operation;

class WorkflowNotes extends Controller
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
        BackendMenu::setContext('Samvol.Inventory', 'inventory', 'workflownotes');
    }
}
