<?php namespace Samvol\Inventory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableUpdateSamvolInventoryDocuments extends Migration
{
    public function up()
{
    Schema::table('samvol_inventory_documents', function($table)
    {
        $table->integer('operation_id')->unsigned();
    });
}

public function down()
{
    Schema::table('samvol_inventory_documents', function($table)
    {
        $table->dropColumn('operation_id');
    });
}
}
