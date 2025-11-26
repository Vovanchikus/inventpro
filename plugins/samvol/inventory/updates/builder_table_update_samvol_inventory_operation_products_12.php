<?php namespace Samvol\Inventory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableUpdateSamvolInventoryOperationProducts12 extends Migration
{
    public function up()
{
    Schema::table('samvol_inventory_operation_products', function($table)
    {
        $table->string('counteragent');
    });
}

public function down()
{
    Schema::table('samvol_inventory_operation_products', function($table)
    {
        $table->dropColumn('counteragent');
    });
}
}
