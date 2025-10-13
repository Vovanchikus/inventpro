<?php namespace Samvol\Inventory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableCreateSamvolInventoryOperationProducts extends Migration
{
    public function up()
{
    Schema::create('samvol_inventory_operation_products', function($table)
    {
        $table->engine = 'InnoDB';
        $table->increments('id')->unsigned();
        $table->integer('operation_id')->unsigned();
        $table->integer('product_id')->unsigned();
        $table->decimal('quantity', 10, 0);
    });
}

public function down()
{
    Schema::dropIfExists('samvol_inventory_operation_products');
}
}
