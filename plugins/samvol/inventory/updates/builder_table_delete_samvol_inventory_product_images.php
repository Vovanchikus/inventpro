<?php namespace Samvol\Inventory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableDeleteSamvolInventoryProductImages extends Migration
{
    public function up()
{
    Schema::dropIfExists('samvol_inventory_product_images');
}

public function down()
{
    Schema::create('samvol_inventory_product_images', function($table)
    {
        $table->engine = 'InnoDB';
        $table->increments('id')->unsigned();
        $table->integer('product_id')->unsigned();
        $table->string('url', 255);
    });
}
}
