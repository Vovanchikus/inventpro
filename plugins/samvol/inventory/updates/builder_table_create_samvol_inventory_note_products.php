<?php namespace Samvol\Inventory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableCreateSamvolInventoryNoteProducts extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('samvol_inventory_note_products')) {
            Schema::create('samvol_inventory_note_products', function($table)
            {
                $table->engine = 'InnoDB';
                $table->increments('id')->unsigned();
                $table->integer('note_id')->unsigned();
                $table->integer('product_id')->unsigned();
                $table->decimal('quantity', 10, 0)->default(0);
                $table->decimal('sum', 15, 2)->nullable();
                $table->string('counteragent')->nullable();
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('samvol_inventory_note_products');
    }
}
