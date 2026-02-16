<?php namespace Samvol\Inventory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableUpdateSamvolInventoryOperations3 extends Migration
{
    public function up()
{
    Schema::table('samvol_inventory_operations', function($table)
    {
        $table->string('counteragent', 255)->nullable()->change();
    });
}

public function down()
{
    Schema::table('samvol_inventory_operations', function($table)
    {
        $table->string('counteragent', 255)->nullable(false)->change();
    });
}
}
