<?php namespace Samvol\Inventory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableCreateSamvolInventoryWorkflowNoteOperation extends Migration
{
    public function up()
{
    Schema::create('samvol_inventory_workflow_note_operation', function($table)
    {
        $table->engine = 'InnoDB';
        $table->increments('id')->unsigned();
        $table->integer('workflow_note_id')->unsigned();
        $table->integer('operation_id')->unsigned();
    });
}

public function down()
{
    Schema::dropIfExists('samvol_inventory_workflow_note_operation');
}
}
