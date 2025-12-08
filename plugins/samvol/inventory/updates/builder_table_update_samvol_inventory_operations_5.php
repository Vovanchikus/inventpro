<?php namespace Samvol\Inventory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableUpdateSamvolInventoryOperations5 extends Migration
{
    public function up()
{
    Schema::table('samvol_inventory_operations', function($table)
    {
        $table->string('slug');
    });
}

public function down()
{
    Schema::table('samvol_inventory_operations', function($table)
    {
        $table->dropColumn('slug');
    });
}
}
