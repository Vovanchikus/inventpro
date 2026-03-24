<?php namespace Samvol\Inventory\Updates;

use DB;
use Schema;
use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableUpdateSamvolInventoryDocSettingsOrgScope extends Migration
{
    public function up()
    {
        $this->addOrganizationColumn('samvol_inventory_doc_template_settings');
        $this->addOrganizationColumn('samvol_inventory_doc_template_people');
        $this->addOrganizationColumn('samvol_inventory_document_generations');

        $this->backfillByScopeKey('samvol_inventory_doc_template_settings');
        $this->backfillByScopeKey('samvol_inventory_doc_template_people');
        $this->backfillDocumentGenerations();
    }

    public function down()
    {
        $this->dropOrganizationColumn('samvol_inventory_doc_template_settings');
        $this->dropOrganizationColumn('samvol_inventory_doc_template_people');
        $this->dropOrganizationColumn('samvol_inventory_document_generations');
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

    private function backfillByScopeKey(string $tableName): void
    {
        if (!Schema::hasTable($tableName) || !Schema::hasColumn($tableName, 'organization_id') || !Schema::hasColumn($tableName, 'scope_key')) {
            return;
        }

        DB::table($tableName)
            ->whereNull('organization_id')
            ->where('scope_key', 'like', 'org:%')
            ->chunkById(500, function ($rows) use ($tableName) {
                foreach ($rows as $row) {
                    $scopeKey = (string) ($row->scope_key ?? '');
                    if (preg_match('/^org:(\d+)$/', $scopeKey, $match) !== 1) {
                        continue;
                    }

                    $organizationId = (int) ($match[1] ?? 0);
                    if ($organizationId <= 0) {
                        continue;
                    }

                    DB::table($tableName)
                        ->where('id', (int) $row->id)
                        ->update(['organization_id' => $organizationId]);
                }
            });
    }

    private function backfillDocumentGenerations(): void
    {
        if (!Schema::hasTable('samvol_inventory_document_generations') || !Schema::hasColumn('samvol_inventory_document_generations', 'organization_id')) {
            return;
        }

        if (Schema::hasTable('samvol_inventory_operations') && Schema::hasColumn('samvol_inventory_operations', 'organization_id')) {
            DB::statement('UPDATE samvol_inventory_document_generations
                SET organization_id = (
                    SELECT o.organization_id
                    FROM samvol_inventory_operations o
                    WHERE o.id = samvol_inventory_document_generations.operation_id
                )
                WHERE organization_id IS NULL
                  AND EXISTS (
                    SELECT 1
                    FROM samvol_inventory_operations o
                    WHERE o.id = samvol_inventory_document_generations.operation_id
                      AND o.organization_id IS NOT NULL
                )');
        }

        if (Schema::hasTable('users') && Schema::hasColumn('users', 'organization_id')) {
            DB::statement('UPDATE samvol_inventory_document_generations
                SET organization_id = (
                    SELECT u.organization_id
                    FROM users u
                    WHERE u.id = samvol_inventory_document_generations.user_id
                )
                WHERE organization_id IS NULL
                  AND EXISTS (
                    SELECT 1
                    FROM users u
                    WHERE u.id = samvol_inventory_document_generations.user_id
                      AND u.organization_id IS NOT NULL
                )');
        }
    }
}
