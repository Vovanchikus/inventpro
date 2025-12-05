<?php namespace Samvol\Inventory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableUpdateSamvolInventoryDocuments2 extends Migration
{
    public function up()
{
    Schema::table('samvol_inventory_documents', function($table)
    {
        $table->string('doc_purpose');
    });
}

public function down()
{
    Schema::table('samvol_inventory_documents', function($table)
    {
        $table->dropColumn('doc_purpose');
    });
}
}
