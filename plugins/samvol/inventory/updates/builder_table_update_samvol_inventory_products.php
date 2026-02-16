<?php namespace Samvol\Inventory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableUpdateSamvolInventoryProducts extends Migration
{
    public function up()
{
    Schema::table('samvol_inventory_products', function($table)
    {
        $table->dropColumn('quantity');
    });
}

public function down()
{
    Schema::table('samvol_inventory_products', function($table)
    {
        $table->decimal('quantity', 10, 0);
    });
}
}
