<?php namespace Samvol\Inventory\Updates;

use DB;
use Schema;
use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableUpdateSamvolInventoryOrgScopePivots extends Migration
{
    public function up()
    {
        $this->addOrganizationColumn('samvol_inventory_operation_products');
        $this->addOrganizationColumn('samvol_inventory_note_products');

        $this->backfillOperationProducts();
        $this->backfillNoteProducts();
    }

    public function down()
    {
        $this->dropOrganizationColumn('samvol_inventory_operation_products');
        $this->dropOrganizationColumn('samvol_inventory_note_products');
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

    private function backfillOperationProducts(): void
    {
        if (!Schema::hasTable('samvol_inventory_operation_products')
            || !Schema::hasTable('samvol_inventory_operations')
            || !Schema::hasColumn('samvol_inventory_operation_products', 'organization_id')
            || !Schema::hasColumn('samvol_inventory_operations', 'organization_id')) {
            return;
        }

        DB::statement('UPDATE samvol_inventory_operation_products
            SET organization_id = (
                SELECT o.organization_id
                FROM samvol_inventory_operations o
                WHERE o.id = samvol_inventory_operation_products.operation_id
            )
            WHERE organization_id IS NULL
              AND EXISTS (
                SELECT 1
                FROM samvol_inventory_operations o
                WHERE o.id = samvol_inventory_operation_products.operation_id
                  AND o.organization_id IS NOT NULL
            )');
    }

    private function backfillNoteProducts(): void
    {
        if (!Schema::hasTable('samvol_inventory_note_products')
            || !Schema::hasTable('samvol_inventory_notes')
            || !Schema::hasColumn('samvol_inventory_note_products', 'organization_id')
            || !Schema::hasColumn('samvol_inventory_notes', 'organization_id')) {
            return;
        }

        DB::statement('UPDATE samvol_inventory_note_products
            SET organization_id = (
                SELECT n.organization_id
                FROM samvol_inventory_notes n
                WHERE n.id = samvol_inventory_note_products.note_id
            )
            WHERE organization_id IS NULL
              AND EXISTS (
                SELECT 1
                FROM samvol_inventory_notes n
                WHERE n.id = samvol_inventory_note_products.note_id
                  AND n.organization_id IS NOT NULL
            )');
    }
}
