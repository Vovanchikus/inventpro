<?php namespace Samvol\Inventory\Updates;

use Schema;
use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableCreateSamvolInventoryOrganizations extends Migration
{
    public function up()
    {
        if (Schema::hasTable('samvol_inventory_organizations')) {
            return;
        }

        Schema::create('samvol_inventory_organizations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->string('code', 120)->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active'], 'samvol_inv_org_active_idx');
        });
    }

    public function down()
    {
        Schema::dropIfExists('samvol_inventory_organizations');
    }
}
