<?php namespace Samvol\Inventory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableCreateSamvolInventoryNotes extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('samvol_inventory_notes')) {
            Schema::create('samvol_inventory_notes', function($table)
            {
                $table->engine = 'InnoDB';
                $table->increments('id')->unsigned();
                $table->string('title')->nullable();
                $table->text('description')->nullable();
                $table->date('due_date')->nullable();
                $table->string('status')->default('new');
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('samvol_inventory_notes');
    }
}
