<?php namespace Samvol\Inventory\Updates;

use Schema;
use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableCreateSamvolInventoryDocTemplatePeople extends Migration
{
    public function up()
    {
        if (Schema::hasTable('samvol_inventory_doc_template_people')) {
            return;
        }

        Schema::create('samvol_inventory_doc_template_people', function (Blueprint $table) {
            $table->increments('id');
            $table->string('scope_key', 191);
            $table->string('role_key', 80);
            $table->string('name', 255);
            $table->text('position')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['scope_key', 'role_key'], 'samvol_inv_doc_people_scope_role_idx');
            $table->index(['scope_key'], 'samvol_inv_doc_people_scope_idx');
        });
    }

    public function down()
    {
        Schema::dropIfExists('samvol_inventory_doc_template_people');
    }
}
