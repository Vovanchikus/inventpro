<?php namespace Samvol\Inventory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableUpdateSamvolInventoryOperationsAddDraftProducts extends Migration
{
    public function up()
    {
        Schema::table('samvol_inventory_operations', function($table)
        {
            if (!Schema::hasColumn('samvol_inventory_operations', 'draft_products')) {
                $table->text('draft_products')->nullable()->after('note_id');
            }
        });
    }

    public function down()
    {
        Schema::table('samvol_inventory_operations', function($table)
        {
            if (Schema::hasColumn('samvol_inventory_operations', 'draft_products')) {
                $table->dropColumn('draft_products');
            }
        });
    }
}
