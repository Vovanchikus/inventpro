<?php namespace Samvol\Inventory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableUpdateSamvolInventoryOperationProducts10 extends Migration
{
    public function up()
{
    Schema::table('samvol_inventory_operation_products', function($table)
    {
        $table->decimal('sum', 10, 0);
    });
}

public function down()
{
    Schema::table('samvol_inventory_operation_products', function($table)
    {
        $table->dropColumn('sum');
    });
}
}
