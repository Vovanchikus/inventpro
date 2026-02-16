<?php namespace Samvol\Inventory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableUpdateSamvolInventoryProducts8 extends Migration
{
    public function up()
{
    Schema::table('samvol_inventory_products', function($table)
    {
        $table->text('name')->nullable(false)->unsigned(false)->default(null)->change();
    });
}

public function down()
{
    Schema::table('samvol_inventory_products', function($table)
    {
        $table->string('name', 255)->nullable(false)->unsigned(false)->default(null)->change();
    });
}
}
