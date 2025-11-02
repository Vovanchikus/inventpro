<?php namespace Samvol\Inventory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableCreateSamvolInventoryStockSnapshots extends Migration
{
    public function up()
{
    Schema::create('samvol_inventory_stock_snapshots', function($table)
    {
        $table->engine = 'InnoDB';
        $table->increments('id')->unsigned();
        $table->string('name');
    });
}

public function down()
{
    Schema::dropIfExists('samvol_inventory_stock_snapshots');
}
}
