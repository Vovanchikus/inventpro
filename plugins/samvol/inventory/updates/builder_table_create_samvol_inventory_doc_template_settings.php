<?php namespace Samvol\Inventory\Updates;

use Schema;
use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableCreateSamvolInventoryDocTemplateSettings extends Migration
{
    public function up()
    {
        if (Schema::hasTable('samvol_inventory_doc_template_settings')) {
            return;
        }

        Schema::create('samvol_inventory_doc_template_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('scope_key', 191);
            $table->string('key', 120);
            $table->text('value')->nullable();
            $table->timestamps();

            $table->unique(['scope_key', 'key'], 'samvol_inv_doc_set_scope_key_unique');
            $table->index(['scope_key'], 'samvol_inv_doc_set_scope_idx');
        });
    }

    public function down()
    {
        Schema::dropIfExists('samvol_inventory_doc_template_settings');
    }
}
