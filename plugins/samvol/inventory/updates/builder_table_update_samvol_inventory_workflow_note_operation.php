<?php namespace Samvol\Inventory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableUpdateSamvolInventoryWorkflowNoteOperation extends Migration
{
    public function up()
{
    Schema::table('samvol_inventory_workflow_note_operation', function($table)
    {
        $table->integer('workflow_note_item_id')->unsigned();
        $table->decimal('quantity_closed', 10, 3)->default(0);
    });
}

public function down()
{
    Schema::table('samvol_inventory_workflow_note_operation', function($table)
    {
        $table->dropColumn('workflow_note_item_id');
        $table->dropColumn('quantity_closed');
    });
}
}
