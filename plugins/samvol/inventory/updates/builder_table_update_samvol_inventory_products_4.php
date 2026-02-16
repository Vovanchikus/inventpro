<?php namespace Samvol\Inventory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableUpdateSamvolInventoryProducts4 extends Migration
{
    public function up()
{
    Schema::table('samvol_inventory_products', function($table)
    {
        $table->dropColumn('sum');
    });
}

public function down()
{
    Schema::table('samvol_inventory_products', function($table)
    {
        $table->decimal('sum', 15, 2);
    });
}
}
