<?php namespace Samvol\Inventory\Updates;

use DB;
use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableUpdateSamvolInventoryDocumentsOrgBackfill extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('samvol_inventory_documents') || !Schema::hasColumn('samvol_inventory_documents', 'organization_id')) {
            return;
        }

        if (!Schema::hasTable('samvol_inventory_operations') || !Schema::hasColumn('samvol_inventory_operations', 'organization_id')) {
            return;
        }

        try {
            DB::statement('UPDATE samvol_inventory_documents
                SET organization_id = (
                    SELECT o.organization_id
                    FROM samvol_inventory_operations o
                    WHERE o.id = samvol_inventory_documents.operation_id
                )
                WHERE organization_id IS NULL
                  AND EXISTS (
                    SELECT 1
                    FROM samvol_inventory_operations o
                    WHERE o.id = samvol_inventory_documents.operation_id
                      AND o.organization_id IS NOT NULL
                )');
        } catch (\Throwable $e) {
        }
    }

    public function down()
    {
    }
}
