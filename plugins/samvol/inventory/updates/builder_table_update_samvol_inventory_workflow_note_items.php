<?php namespace Samvol\Inventory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableUpdateSamvolInventoryWorkflowNoteItems extends Migration
{
    public function up()
{
    Schema::table('samvol_inventory_workflow_note_items', function($table)
    {
        $table->decimal('completed_quantity', 10, 2)->default(0);
        $table->decimal('required_quantity', 10, 2)->default(1)->change();
    });
}

public function down()
{
    Schema::table('samvol_inventory_workflow_note_items', function($table)
    {
        $table->dropColumn('completed_quantity');
        $table->decimal('required_quantity', 10, 2)->default(null)->change();
    });
}
}
