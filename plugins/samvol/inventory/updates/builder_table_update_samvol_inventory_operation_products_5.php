<?php namespace Samvol\Inventory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableUpdateSamvolInventoryOperationProducts5 extends Migration
{
    public function up()
{
    Schema::table('samvol_inventory_operation_products', function($table)
    {
        $table->integer('quantity')->nullable(false)->unsigned(false)->default(null)->change();
    });
}

public function down()
{
    Schema::table('samvol_inventory_operation_products', function($table)
    {
        $table->string('quantity', 255)->nullable(false)->unsigned(false)->default(null)->change();
    });
}
}
