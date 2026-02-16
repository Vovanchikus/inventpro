<?php namespace Samvol\Inventory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableUpdateSamvolInventoryProducts7 extends Migration
{
    public function up()
{
    Schema::table('samvol_inventory_products', function($table)
    {
            if (!Schema::hasColumn('samvol_inventory_products', 'slug')) {
                $table->string('slug');
            }
    });
}

public function down()
{
    Schema::table('samvol_inventory_products', function($table)
    {
        if (Schema::hasColumn('samvol_inventory_products', 'slug')) {
            $table->dropColumn('slug');
        }
    });
}
}
