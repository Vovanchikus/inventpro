<?php namespace Samvol\Inventory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableUpdateSamvolInventoryOperations extends Migration
{
    public function up()
{
    Schema::table('samvol_inventory_operations', function($table)
    {
        $table->dropColumn('doc_name');
        $table->dropColumn('doc_num');
        $table->dropColumn('doc_date');
    });
}

public function down()
{
    Schema::table('samvol_inventory_operations', function($table)
    {
        $table->string('doc_name', 255);
        $table->string('doc_num', 255);
        $table->date('doc_date');
    });
}
}
