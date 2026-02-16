<?php namespace Samvol\Inventory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableCreateSamvolInventoryOperations extends Migration
{
    public function up()
{
    Schema::create('samvol_inventory_operations', function($table)
    {
        $table->engine = 'InnoDB';
        $table->increments('id')->unsigned();
        $table->integer('type_id')->unsigned();
        $table->string('doc_name');
        $table->string('doc_num');
        $table->date('doc_date');
        $table->timestamp('created_at')->nullable();
        $table->timestamp('updated_at')->nullable();
    });
}

public function down()
{
    Schema::dropIfExists('samvol_inventory_operations');
}
}
