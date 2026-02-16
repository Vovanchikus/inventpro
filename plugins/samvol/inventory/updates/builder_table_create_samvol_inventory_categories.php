<?php namespace Samvol\Inventory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableCreateSamvolInventoryCategories extends Migration
{
    public function up()
{
    Schema::create('samvol_inventory_categories', function($table)
    {
        $table->engine = 'InnoDB';
        $table->increments('id')->unsigned();
        $table->string('name');
        $table->string('slug');
        $table->text('desc')->nullable();
        $table->integer('parent_id');
        $table->integer('nest_left');
        $table->integer('nest_right');
        $table->integer('nest_depth');
        $table->timestamp('created_at')->nullable();
        $table->timestamp('updated_at')->nullable();
        $table->timestamp('deleted_at')->nullable();
    });
}

public function down()
{
    Schema::dropIfExists('samvol_inventory_categories');
}
}
