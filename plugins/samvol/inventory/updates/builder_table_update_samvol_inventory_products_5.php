<?php namespace Samvol\Inventory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableUpdateSamvolInventoryProducts5 extends Migration
{
    public function up()
{
    Schema::table('samvol_inventory_products', function($table)
    {
        $table->integer('category_id')->unsigned();
    });
}

public function down()
{
    Schema::table('samvol_inventory_products', function($table)
    {
        $table->dropColumn('category_id');
    });
}
}
