<?php namespace Samvol\Inventory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableUpdateSamvolInventoryProducts6 extends Migration
{
    public function up()
{
    Schema::table('samvol_inventory_products', function($table)
    {
        $table->integer('category_id')->nullable()->change();
    });
}

public function down()
{
    Schema::table('samvol_inventory_products', function($table)
    {
        $table->integer('category_id')->nullable(false)->change();
    });
}
}
