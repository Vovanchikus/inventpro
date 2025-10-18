<?php namespace Samvol\Inventory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableUpdateSamvolInventoryOperationProducts4 extends Migration
{
    public function up()
{
    Schema::table('samvol_inventory_operation_products', function($table)
    {
        $table->string('quantity')->nullable(false)->unsigned(false)->default(null)->change();
    });
}

public function down()
{
    Schema::table('samvol_inventory_operation_products', function($table)
    {
        $table->decimal('quantity', 10, 0)->nullable(false)->unsigned(false)->default(null)->change();
    });
}
}
