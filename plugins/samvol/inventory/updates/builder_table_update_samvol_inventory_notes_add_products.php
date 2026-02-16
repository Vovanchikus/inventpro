<?php namespace Samvol\Inventory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableUpdateSamvolInventoryNotesAddProducts extends Migration
{
    public function up()
    {
        Schema::table('samvol_inventory_notes', function($table)
        {
            $table->text('products')->nullable();
        });
    }

    public function down()
    {
        Schema::table('samvol_inventory_notes', function($table)
        {
            $table->dropColumn('products');
        });
    }
}
