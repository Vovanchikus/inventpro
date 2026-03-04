<?php namespace Samvol\Inventory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableCreateSamvolInventoryDocumentGenerations extends Migration
{
    public function up()
    {
        if (Schema::hasTable('samvol_inventory_document_generations')) {
            return;
        }

        Schema::create('samvol_inventory_document_generations', function ($table) {
            $table->increments('id');
            $table->unsignedInteger('operation_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->string('template_id')->nullable();
            $table->string('action', 32)->nullable();
            $table->string('mode', 32)->nullable();
            $table->string('status', 32)->nullable();
            $table->text('message')->nullable();
            $table->integer('items_count')->nullable();
            $table->bigInteger('file_size')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('samvol_inventory_document_generations');
    }
}
