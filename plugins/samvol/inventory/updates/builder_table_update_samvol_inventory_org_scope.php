<?php namespace Samvol\Inventory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableUpdateSamvolInventoryOrgScope extends Migration
{
    public function up()
    {
        Schema::table('samvol_inventory_operations', function ($table) {
            if (!Schema::hasColumn('samvol_inventory_operations', 'organization_id')) {
                $table->unsignedInteger('organization_id')->nullable()->index();
            }
        });

        Schema::table('samvol_inventory_documents', function ($table) {
            if (!Schema::hasColumn('samvol_inventory_documents', 'organization_id')) {
                $table->unsignedInteger('organization_id')->nullable()->index();
            }
        });
    }

    public function down()
    {
        Schema::table('samvol_inventory_operations', function ($table) {
            if (Schema::hasColumn('samvol_inventory_operations', 'organization_id')) {
                $table->dropColumn('organization_id');
            }
        });

        Schema::table('samvol_inventory_documents', function ($table) {
            if (Schema::hasColumn('samvol_inventory_documents', 'organization_id')) {
                $table->dropColumn('organization_id');
            }
        });
    }
}
