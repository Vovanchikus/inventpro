<?php namespace Samvol\Inventory\Controllers;

use Backend\Classes\Controller;
use BackendAuth;
use BackendMenu;
use Winter\User\Models\User;

class OrganizationUsers extends Controller
{
    public $implement = [
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.FormController',
    ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Samvol.Inventory', 'inventory', 'organization_users');
    }

    public function listExtendQuery($query): void
    {
        $query->whereNotNull('organization_id');
    }

    public function formBeforeSave($model): void
    {
        if (!$model || empty($model->id)) {
            return;
        }

        $existing = User::find($model->id);
        if (!$existing) {
            return;
        }

        $newStatus = strtolower(trim((string) ($model->organization_status ?? '')));
        $oldStatus = strtolower(trim((string) ($existing->organization_status ?? '')));

        if ($newStatus === 'approved' && $oldStatus !== 'approved') {
            $backendUser = BackendAuth::getUser();
            $model->organization_approved_at = now();
            $model->organization_approved_by = (int) ($backendUser->id ?? 0) ?: null;
            return;
        }

        if ($newStatus !== 'approved') {
            $model->organization_approved_at = null;
            $model->organization_approved_by = null;
        }
    }
}
