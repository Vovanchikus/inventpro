<?php namespace Samvol\Inventory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableUpdateSamvolInventoryOperations extends Migration
{
    public function up()
    {
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            return;
        }

        if (!Schema::hasTable('samvol_inventory_operations')) {
            return;
        }

        Schema::table('samvol_inventory_operations', function($table)
        {
            if (Schema::hasColumn('samvol_inventory_operations', 'doc_name')) {
                $table->dropColumn('doc_name');
            }

            if (Schema::hasColumn('samvol_inventory_operations', 'doc_num')) {
                $table->dropColumn('doc_num');
            }

            if (Schema::hasColumn('samvol_inventory_operations', 'doc_date')) {
                $table->dropColumn('doc_date');
            }
        });
    }

    public function down()
    {
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            return;
        }

        if (!Schema::hasTable('samvol_inventory_operations')) {
            return;
        }

        Schema::table('samvol_inventory_operations', function($table)
        {
            if (!Schema::hasColumn('samvol_inventory_operations', 'doc_name')) {
                $table->string('doc_name', 255)->nullable();
            }

            if (!Schema::hasColumn('samvol_inventory_operations', 'doc_num')) {
                $table->string('doc_num', 255)->nullable();
            }

            if (!Schema::hasColumn('samvol_inventory_operations', 'doc_date')) {
                $table->date('doc_date')->nullable();
            }
        });
    }
}
