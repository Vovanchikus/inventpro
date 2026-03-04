<?php namespace Samvol\Inventory;

use System\Classes\PluginBase;
use Backend;
use Route;
use Event;
use Broadcast;
use ApplicationException;
use Samvol\Inventory\Classes\OrganizationAccess;
use Samvol\Inventory\Models\Organization;
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
            \Samvol\Inventory\Components\CreateNote::class => 'createNote',
            \Samvol\Inventory\Components\NotesList::class => 'notesList',
            \Samvol\Inventory\Components\History::class => 'history',
            \Samvol\Inventory\Components\OperationInfo::class => 'operationInfo',
            \Samvol\Inventory\Components\QrProductCode::class => 'qrProductCode',
            \Samvol\Inventory\Components\SettingsPageData::class => 'settingsPageData',
            \Samvol\Inventory\Components\DocumentBuilderPageData::class => 'documentBuilderPageData',
        ];
    }

    public function register()
    {
        $this->registerConsoleCommand('samvol.dumpnotes', \Samvol\Inventory\Console\DumpNoteProducts::class);

        $router = app('router');
        $router->aliasMiddleware('api.token', \Samvol\Inventory\Classes\Api\Middleware\ApiTokenMiddleware::class);
        $router->aliasMiddleware('api.scope', \Samvol\Inventory\Classes\Api\Middleware\ApiScopeMiddleware::class);
        $router->aliasMiddleware('api.org_role', \Samvol\Inventory\Classes\Api\Middleware\ApiOrganizationRoleMiddleware::class);
    }



    public function boot()
    {
        Broadcast::channel('org.{organizationId}.inventory', function ($user, $organizationId) {
            return (int)($user->organization_id ?? 0) === (int)$organizationId;
        });

        Route::get('document/{id}', [DocumentsDownloadController::class, 'download']);

        \Winter\User\Models\User::extend(function ($model) {
            if (method_exists($model, 'addFillable')) {
                $model->addFillable([
                    'organization_id',
                    'organization_role',
                    'organization_status',
                    'organization_approved_at',
                    'organization_approved_by',
                ]);
            }

            if (!isset($model->belongsTo['organization'])) {
                $model->belongsTo['organization'] = [Organization::class, 'key' => 'organization_id'];
            }
        });

        Event::listen('winter.user.beforeRegister', function (&$data) {
            $organizationId = (int)($data['organization_id'] ?? 0);
            if ($organizationId <= 0) {
                $organizationCode = strtolower(trim((string)($data['organization_code'] ?? '')));
                if ($organizationCode !== '') {
                    $organization = Organization::query()
                        ->whereRaw('LOWER(code) = ?', [$organizationCode])
                        ->where('is_active', true)
                        ->first();

                    $organizationId = (int)($organization->id ?? 0);
                }
            }

            if ($organizationId <= 0) {
                throw new ApplicationException('Вкажіть код або виберіть організацію для реєстрації.');
            }

            $organization = Organization::query()
                ->where('id', $organizationId)
                ->where('is_active', true)
                ->first();

            if (!$organization) {
                throw new ApplicationException('Обрана організація недоступна.');
            }

            $data['organization_id'] = $organizationId;
            $data['organization_role'] = OrganizationAccess::defaultRole();
            $data['organization_status'] = OrganizationAccess::defaultStatus();
            $data['organization_approved_at'] = null;
            $data['organization_approved_by'] = null;
        });
    }


    public function registerSettings()
    {
    }
}
