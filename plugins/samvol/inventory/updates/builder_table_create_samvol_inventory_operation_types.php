<?php namespace Samvol\Inventory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableCreateSamvolInventoryOperationTypes extends Migration
{
    public function up()
{
    Schema::create('samvol_inventory_operation_types', function($table)
    {
        $table->engine = 'InnoDB';
        $table->increments('id')->unsigned();
        $table->string('name');
    });
}

public function down()
{
    Schema::dropIfExists('samvol_inventory_operation_types');
}
}
