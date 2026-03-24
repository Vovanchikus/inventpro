<?php namespace Samvol\Inventory;

use System\Classes\PluginBase;
use Backend;
use Route;
use Event;
use Broadcast;
use ApplicationException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
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
        $this->registerConsoleCommand('samvol.rebindorg', \Samvol\Inventory\Console\RebindOrganizationData::class);

        $router = app('router');
        $router->aliasMiddleware('api.request_context', \Samvol\Inventory\Classes\Api\Middleware\ApiRequestContextMiddleware::class);
        $router->aliasMiddleware('api.exception_json', \Samvol\Inventory\Classes\Api\Middleware\ApiExceptionMiddleware::class);
        $router->aliasMiddleware('api.token', \Samvol\Inventory\Classes\Api\Middleware\ApiTokenMiddleware::class);
        $router->aliasMiddleware('api.scope', \Samvol\Inventory\Classes\Api\Middleware\ApiScopeMiddleware::class);
        $router->aliasMiddleware('api.org_role', \Samvol\Inventory\Classes\Api\Middleware\ApiOrganizationRoleMiddleware::class);
    }



    public function boot()
    {
        Broadcast::channel('org.{organizationId}.inventory', function ($user, $organizationId) {
            return (int)($user->organization_id ?? 0) === (int)$organizationId;
        });

        Route::get('document/{id}', [DocumentsDownloadController::class, 'download'])->middleware('web');

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
            $createOrganization = filter_var($data['create_organization'] ?? false, FILTER_VALIDATE_BOOL);
            $organizationName = trim((string)($data['organization_name'] ?? ''));

            if ($organizationName !== '') {
                $createOrganization = true;
            }

            if ($createOrganization && $organizationId > 0) {
                throw new ApplicationException('Оберіть існуючу організацію або створіть нову. Одночасно обрати обидва варіанти не можна.');
            }

            $organizationRole = OrganizationAccess::defaultRole();
            if ($createOrganization) {
                if ($organizationName === '') {
                    throw new ApplicationException('Вкажіть назву нової організації.');
                }

                $normalizedName = preg_replace('/\s+/u', ' ', mb_strtolower($organizationName));
                $existsByName = Organization::query()
                    ->whereRaw('LOWER(TRIM(name)) = ?', [trim((string)$normalizedName)])
                    ->exists();

                if ($existsByName) {
                    throw new ApplicationException('Організація з такою назвою вже існує. Оберіть її зі списку.');
                }

                $codeBase = Str::slug($organizationName);
                if ($codeBase === '') {
                    $codeBase = 'org';
                }

                $code = $codeBase;
                $attempt = 2;
                while (Organization::query()->whereRaw('LOWER(code) = ?', [strtolower($code)])->exists()) {
                    $code = $codeBase . '-' . $attempt;
                    $attempt++;
                }

                $organization = Organization::query()->create([
                    'name' => $organizationName,
                    'code' => $code,
                    'is_active' => false,
                ]);

                $organizationId = (int)($organization->id ?? 0);
                $organizationRole = OrganizationAccess::ROLE_ADMIN;
            }

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
                throw new ApplicationException('Оберіть існуючу організацію або створіть нову.');
            }

            $organization = Organization::query()
                ->where('id', $organizationId)
                ->first();

            if (!$organization) {
                throw new ApplicationException('Обрана організація недоступна.');
            }

            if (!$createOrganization && !$organization->is_active) {
                throw new ApplicationException('Організація ще не підтверджена адміністратором проєкту.');
            }

            $data['organization_id'] = $organizationId;
            $data['organization_role'] = $organizationRole;
            $data['organization_status'] = OrganizationAccess::defaultStatus();
            $data['organization_approved_at'] = null;
            $data['organization_approved_by'] = null;
        });

        Event::listen('winter.user.register', function ($user) {
            if (!$user) {
                return;
            }

            $this->attachUserToGroupByCode((int) $user->id, 'org_user');
        });

        Event::listen('winter.user.beforeAuthenticate', function ($component, $credentials) {
            $login = trim((string)($credentials['login'] ?? ''));
            if ($login === '') {
                return;
            }

            $hasLoginColumn = Schema::hasColumn('users', 'login');
            $hasUsernameColumn = Schema::hasColumn('users', 'username');

            $user = \Winter\User\Models\User::query()
                ->where(function ($query) use ($login, $hasLoginColumn, $hasUsernameColumn) {
                    $query->where('email', $login);

                    if ($hasLoginColumn) {
                        $query->orWhere('login', $login);
                    }

                    if ($hasUsernameColumn) {
                        $query->orWhere('username', $login);
                    }
                })
                ->first();

            if (!$user) {
                return;
            }

            if (OrganizationAccess::isProjectAdmin($user)) {
                return;
            }

            $status = strtolower(trim((string)($user->organization_status ?? '')));
            if ($status !== OrganizationAccess::STATUS_APPROVED) {
                throw new ApplicationException('Ваш обліковий запис очікує підтвердження адміністратором проєкту.');
            }

            $organizationId = (int)($user->organization_id ?? 0);
            if ($organizationId <= 0) {
                throw new ApplicationException('Ваш обліковий запис не прив\'язаний до організації. Зверніться до адміністратора.');
            }
        });
    }


    public function registerSettings()
    {
    }

    protected function attachUserToGroupByCode(int $userId, string $groupCode): void
    {
        if ($userId <= 0 || $groupCode === '') {
            return;
        }

        try {
            $groupId = (int) DB::table('user_groups')
                ->where('code', $groupCode)
                ->value('id');

            if ($groupId <= 0) {
                return;
            }

            $exists = DB::table('users_groups')
                ->where('user_id', $userId)
                ->where('user_group_id', $groupId)
                ->exists();

            if (!$exists) {
                DB::table('users_groups')->insert([
                    'user_id' => $userId,
                    'user_group_id' => $groupId,
                ]);
            }
        } catch (\Throwable $e) {
        }
    }
}
