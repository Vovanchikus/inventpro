<?php namespace Samvol\Inventory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableCreateSamvolInventoryWorkflowNotes extends Migration
{
    public function up()
{
    Schema::create('samvol_inventory_workflow_notes', function($table)
    {
        $table->engine = 'InnoDB';
        $table->increments('id')->unsigned();
        $table->string('title');
        $table->text('description');
        $table->date('deadline_at');
        $table->boolean('is_archived');
        $table->timestamp('created_at')->nullable();
        $table->timestamp('updated_at')->nullable();
    });
}

public function down()
{
    Schema::dropIfExists('samvol_inventory_workflow_notes');
}
}
