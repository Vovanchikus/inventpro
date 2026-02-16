<?php namespace Samvol\Inventory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableUpdateSamvolInventoryOperationsAddStatusDates extends Migration
{
    public function up()
    {
        Schema::table('samvol_inventory_operations', function($table)
        {
            if (!Schema::hasColumn('samvol_inventory_operations', 'prepared_at')) {
                $table->timestamp('prepared_at')->nullable();
            }
            if (!Schema::hasColumn('samvol_inventory_operations', 'accounting_at')) {
                $table->timestamp('accounting_at')->nullable();
            }
            if (!Schema::hasColumn('samvol_inventory_operations', 'completed_at')) {
                $table->timestamp('completed_at')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('samvol_inventory_operations', function($table)
        {
            if (Schema::hasColumn('samvol_inventory_operations', 'prepared_at')) {
                $table->dropColumn('prepared_at');
            }
            if (Schema::hasColumn('samvol_inventory_operations', 'accounting_at')) {
                $table->dropColumn('accounting_at');
            }
            if (Schema::hasColumn('samvol_inventory_operations', 'completed_at')) {
                $table->dropColumn('completed_at');
            }
        });
    }
}
