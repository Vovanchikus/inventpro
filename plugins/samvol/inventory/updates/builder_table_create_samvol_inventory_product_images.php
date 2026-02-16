<?php namespace Samvol\Inventory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableCreateSamvolInventoryProductImages extends Migration
{
    public function up()
{
    Schema::create('samvol_inventory_product_images', function($table)
    {
        $table->engine = 'InnoDB';
        $table->increments('id')->unsigned();
        $table->integer('product_id')->unsigned();
        $table->string('url');
    });
}

public function down()
{
    Schema::dropIfExists('samvol_inventory_product_images');
}
}
