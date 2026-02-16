<?php namespace Samvol\Inventory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableUpdateSamvolInventoryOperations5 extends Migration
{
    public function up()
{
    Schema::table('samvol_inventory_operations', function($table)
    {
            if (!Schema::hasColumn('samvol_inventory_operations', 'slug')) {
                $table->string('slug');
            }
    });
}

public function down()
{
    Schema::table('samvol_inventory_operations', function($table)
    {
        if (Schema::hasColumn('samvol_inventory_operations', 'slug')) {
            $table->dropColumn('slug');
        }
    });
}
}
