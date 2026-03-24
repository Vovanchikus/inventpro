<?php namespace Samvol\Inventory\Controllers;

use Illuminate\Routing\Controller;
use Samvol\Inventory\Classes\OrganizationAccess;
use Samvol\Inventory\Models\Document;
use Samvol\Inventory\Models\Operation;

class DocumentsDownloadController extends Controller
{
    public function download($id)
    {
        $user = $this->resolveUser();
        if (!$user) {
            return response('Необхідна авторизація', 401);
        }

        $docQuery = Document::query()->where('id', $id);
        $docQuery = Document::withoutGlobalScope('organization_scope')->where('id', $id);
        if (!$this->hasProjectAdminAccess($user)) {
            $organizationId = (int) ($user->organization_id ?? 0);
            if ($organizationId <= 0) {
                return response('Документ недоступний', 404);
            }

            $docQuery->where(function ($query) use ($organizationId) {
                $query->where('organization_id', $organizationId)
                    ->orWhere(function ($legacyQuery) use ($organizationId) {
                        $legacyQuery->whereNull('organization_id')
                            ->whereExists(function ($existsQuery) use ($organizationId) {
                                $existsQuery->selectRaw('1')
                                    ->from('samvol_inventory_operations as o')
                                    ->whereColumn('o.id', 'samvol_inventory_documents.operation_id')
                                    ->where('o.organization_id', $organizationId);
                            });
                    });
            });
        }

        $doc = $docQuery->first();

        if (!$doc || !$doc->doc_file) {
            return response('Документ або файл не знайдено', 404);
        }

        if (empty($doc->organization_id)) {
            $operationOrganizationId = (int) Operation::withoutGlobalScope('organization_scope')
                ->where('id', (int) $doc->operation_id)
                ->value('organization_id');

            if ($operationOrganizationId > 0) {
                try {
                    $doc->organization_id = $operationOrganizationId;
                    $doc->save();
                } catch (\Throwable $e) {
                }
            }
        }

        return response()->file(
            $doc->doc_file->getLocalPath(),
            [
                'Content-Disposition' => 'inline; filename="'.$doc->doc_file->file_name.'"'
            ]
        );
    }

    protected function resolveUser()
    {
        try {
            $frontendUser = \Auth::getUser();
            if ($frontendUser) {
                return $frontendUser;
            }
        } catch (\Throwable $e) {
        }

        try {
            if (class_exists(\Backend\Facades\BackendAuth::class)) {
                $backendUser = \Backend\Facades\BackendAuth::getUser();
                if ($backendUser) {
                    return $backendUser;
                }
            }
        } catch (\Throwable $e) {
        }

        return null;
    }

    protected function hasProjectAdminAccess($user): bool
    {
        if (!$user) {
            return false;
        }

        return OrganizationAccess::isOrganizationAdmin($user)
            || OrganizationAccess::isProjectAdmin($user);
    }
}
