<?php namespace Samvol\Inventory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableUpdateSamvolInventoryWorkflowNotes extends Migration
{
    public function up()
{
    Schema::table('samvol_inventory_workflow_notes', function($table)
    {
        $table->boolean('is_archived')->default(false)->change();
    });
}

public function down()
{
    Schema::table('samvol_inventory_workflow_notes', function($table)
    {
        $table->boolean('is_archived')->default(null)->change();
    });
}
}
