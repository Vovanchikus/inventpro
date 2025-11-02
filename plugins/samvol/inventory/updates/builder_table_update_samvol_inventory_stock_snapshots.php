<?php namespace Samvol\Inventory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableUpdateSamvolInventoryStockSnapshots extends Migration
{
    public function up()
{
    Schema::table('samvol_inventory_stock_snapshots', function($table)
    {
        $table->date('snapshot_date');
        $table->text('date');
        $table->timestamp('created_at')->nullable();
        $table->timestamp('updated_at')->nullable();
        $table->text('name')->nullable(false)->unsigned(false)->default(null)->change();
    });
}

public function down()
{
    Schema::table('samvol_inventory_stock_snapshots', function($table)
    {
        $table->dropColumn('snapshot_date');
        $table->dropColumn('date');
        $table->dropColumn('created_at');
        $table->dropColumn('updated_at');
        $table->string('name', 255)->nullable(false)->unsigned(false)->default(null)->change();
    });
}
}
