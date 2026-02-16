<?php namespace Samvol\Inventory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableUpdateSamvolInventoryProducts2 extends Migration
{
    public function up()
{
    Schema::table('samvol_inventory_products', function($table)
    {
        $table->string('inv_number');
        $table->decimal('price', 10, 0);
        $table->decimal('sum', 10, 0);
    });
}

public function down()
{
    Schema::table('samvol_inventory_products', function($table)
    {
        $table->dropColumn('inv_number');
        $table->dropColumn('price');
        $table->dropColumn('sum');
    });
}
}
