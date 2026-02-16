<?php namespace Samvol\Inventory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableUpdateSamvolInventoryCategories2 extends Migration
{
    public function up()
{
    Schema::table('samvol_inventory_categories', function($table)
    {
        $table->integer('parent_id')->nullable()->change();
    });
}

public function down()
{
    Schema::table('samvol_inventory_categories', function($table)
    {
        $table->integer('parent_id')->nullable(false)->change();
    });
}
}
