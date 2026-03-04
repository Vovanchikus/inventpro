<?php namespace Samvol\Inventory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableCreateSamvolInventoryApiRefreshTokens extends Migration
{
    public function up()
    {
        Schema::create('samvol_inventory_api_refresh_tokens', function ($table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->index();
            $table->string('token_id', 64)->index();
            $table->string('token_hash', 128)->unique();
            $table->timestamp('expires_at')->index();
            $table->timestamp('revoked_at')->nullable()->index();
            $table->string('replaced_by_token_id', 64)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('samvol_inventory_api_refresh_tokens');
    }
}
