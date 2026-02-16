<?php namespace Samvol\Inventory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableCreateSamvolInventoryWorkflowNoteItems extends Migration
{
    public function up()
{
    Schema::create('samvol_inventory_workflow_note_items', function($table)
    {
        $table->engine = 'InnoDB';
        $table->increments('id')->unsigned();
        $table->integer('workflow_note_id')->unsigned();
        $table->integer('product_id')->unsigned();
        $table->decimal('required_quantity', 10, 2);
        $table->timestamp('created_at')->nullable();
        $table->timestamp('updated_at')->nullable();
    });
}

public function down()
{
    Schema::dropIfExists('samvol_inventory_workflow_note_items');
}
}
