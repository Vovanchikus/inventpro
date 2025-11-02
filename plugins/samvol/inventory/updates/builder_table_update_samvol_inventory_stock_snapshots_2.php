<?php namespace Samvol\Inventory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableUpdateSamvolInventoryStockSnapshots2 extends Migration
{
    public function up()
{
    Schema::table('samvol_inventory_stock_snapshots', function($table)
    {
        $table->renameColumn('date', 'data');
    });
}

public function down()
{
    Schema::table('samvol_inventory_stock_snapshots', function($table)
    {
        $table->renameColumn('data', 'date');
    });
}
}
