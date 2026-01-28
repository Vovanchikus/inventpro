<?php namespace Samvol\Inventory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableUpdateSamvolInventoryOperationsAddFields extends Migration
{
    public function up()
    {
        Schema::table('samvol_inventory_operations', function($table)
        {
            if (!Schema::hasColumn('samvol_inventory_operations', 'is_draft')) {
                $table->boolean('is_draft')->default(false)->after('type_id');
            }
            if (!Schema::hasColumn('samvol_inventory_operations', 'is_posted')) {
                $table->boolean('is_posted')->default(false)->after('is_draft');
            }
            if (!Schema::hasColumn('samvol_inventory_operations', 'note_id')) {
                $table->integer('note_id')->unsigned()->nullable()->after('is_posted');
                $table->index('note_id');
            }
        });
    }

    public function down()
    {
        Schema::table('samvol_inventory_operations', function($table)
        {
            if (Schema::hasColumn('samvol_inventory_operations', 'note_id')) {
                $table->dropIndex(['note_id']);
                $table->dropColumn('note_id');
            }
            if (Schema::hasColumn('samvol_inventory_operations', 'is_posted')) {
                $table->dropColumn('is_posted');
            }
            if (Schema::hasColumn('samvol_inventory_operations', 'is_draft')) {
                $table->dropColumn('is_draft');
            }
        });
    }
}
