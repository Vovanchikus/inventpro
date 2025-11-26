<?php namespace Samvol\Inventory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableUpdateSamvolInventoryOperations4 extends Migration
{
    public function up()
{
    Schema::table('samvol_inventory_operations', function($table)
    {
        $table->dropColumn('counteragent');
    });
}

public function down()
{
    Schema::table('samvol_inventory_operations', function($table)
    {
        $table->string('counteragent', 255)->nullable();
    });
}
}
