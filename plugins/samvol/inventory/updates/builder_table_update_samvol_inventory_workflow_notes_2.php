<?php namespace Samvol\Inventory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableUpdateSamvolInventoryWorkflowNotes2 extends Migration
{
    public function up()
{
    Schema::table('samvol_inventory_workflow_notes', function($table)
    {
        $table->boolean('is_completed')->default(0);
    });
}

public function down()
{
    Schema::table('samvol_inventory_workflow_notes', function($table)
    {
        $table->dropColumn('is_completed');
    });
}
}
