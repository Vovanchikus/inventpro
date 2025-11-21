<?php namespace Samvol\Inventory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableUpdateSamvolInventoryProducts3 extends Migration
{
    public function up()
{
    Schema::table('samvol_inventory_products', function($table)
    {
        $table->decimal('price', 10, 2)->change();
        $table->decimal('sum', 15, 2)->change();
    });
}

public function down()
{
    Schema::table('samvol_inventory_products', function($table)
    {
        $table->decimal('price', 10, 0)->change();
        $table->decimal('sum', 10, 0)->change();
    });
}
}
