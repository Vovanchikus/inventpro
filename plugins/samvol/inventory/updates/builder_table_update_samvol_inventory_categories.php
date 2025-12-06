<?php namespace Samvol\Inventory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableUpdateSamvolInventoryCategories extends Migration
{
    public function up()
{
    Schema::table('samvol_inventory_categories', function($table)
    {
        $table->integer('nest_depth')->nullable()->change();
    });
}

public function down()
{
    Schema::table('samvol_inventory_categories', function($table)
    {
        $table->integer('nest_depth')->nullable(false)->change();
    });
}
}
