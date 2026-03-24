<?php namespace Samvol\Inventory\Updates;

use DB;
use Schema;
use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableUpdateSamvolInventorySingleOrgScope extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('samvol_inventory_organizations')) {
            return;
        }

        $organizationId = $this->resolveOrganizationId();
        if ($organizationId <= 0) {
            return;
        }

        $this->addOrganizationColumn('samvol_inventory_products');
        $this->addOrganizationColumn('samvol_inventory_categories');
        $this->addOrganizationColumn('samvol_inventory_notes');

        $this->backfillOrganizationColumn('samvol_inventory_products', $organizationId);
        $this->backfillOrganizationColumn('samvol_inventory_categories', $organizationId);
        $this->backfillOrganizationColumn('samvol_inventory_notes', $organizationId);
        $this->backfillOrganizationColumn('samvol_inventory_operations', $organizationId);
        $this->backfillOrganizationColumn('samvol_inventory_documents', $organizationId);

        if (Schema::hasTable('users') && Schema::hasColumn('users', 'organization_id')) {
            DB::table('users')->whereNull('organization_id')->update(['organization_id' => $organizationId]);
        }
    }

    public function down()
    {
        $this->dropOrganizationColumn('samvol_inventory_products');
        $this->dropOrganizationColumn('samvol_inventory_categories');
        $this->dropOrganizationColumn('samvol_inventory_notes');
    }

    private function addOrganizationColumn(string $tableName): void
    {
        if (!Schema::hasTable($tableName) || Schema::hasColumn($tableName, 'organization_id')) {
            return;
        }

        Schema::table($tableName, function (Blueprint $table) {
            $table->unsignedInteger('organization_id')->nullable()->index();
        });
    }

    private function dropOrganizationColumn(string $tableName): void
    {
        if (!Schema::hasTable($tableName) || !Schema::hasColumn($tableName, 'organization_id')) {
            return;
        }

        Schema::table($tableName, function (Blueprint $table) {
            $table->dropColumn('organization_id');
        });
    }

    private function backfillOrganizationColumn(string $tableName, int $organizationId): void
    {
        if (!Schema::hasTable($tableName) || !Schema::hasColumn($tableName, 'organization_id')) {
            return;
        }

        DB::table($tableName)
            ->whereNull('organization_id')
            ->update(['organization_id' => $organizationId]);
    }

    private function resolveOrganizationId(): int
    {
        $existingOrganizationId = (int) DB::table('samvol_inventory_organizations')
            ->orderByRaw('CASE WHEN is_active = 1 THEN 0 ELSE 1 END')
            ->orderBy('id')
            ->value('id');

        if ($existingOrganizationId > 0) {
            return $existingOrganizationId;
        }

        $defaultCode = 'main';
        $code = $defaultCode;

        for ($i = 0; $i < 50; $i++) {
            $exists = DB::table('samvol_inventory_organizations')
                ->whereRaw('LOWER(code) = ?', [strtolower($code)])
                ->exists();

            if (!$exists) {
                break;
            }

            $code = $defaultCode . '-' . ($i + 2);
        }

        return (int) DB::table('samvol_inventory_organizations')->insertGetId([
            'name' => 'Main organization',
            'code' => $code,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
