<?php namespace Samvol\Inventory\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableUpdateSamvolInventoryMobileFields extends Migration
{
    public function up()
    {
        Schema::table('samvol_inventory_products', function ($table) {
            if (!Schema::hasColumn('samvol_inventory_products', 'mobile_summary')) {
                $table->string('mobile_summary', 500)->nullable();
            }

            if (!Schema::hasColumn('samvol_inventory_products', 'external_id')) {
                $table->string('external_id', 120)->nullable()->index();
            }
        });

        Schema::table('samvol_inventory_operations', function ($table) {
            if (!Schema::hasColumn('samvol_inventory_operations', 'mobile_note')) {
                $table->string('mobile_note', 500)->nullable();
            }

            if (!Schema::hasColumn('samvol_inventory_operations', 'external_id')) {
                $table->string('external_id', 120)->nullable()->index();
            }
        });

        Schema::table('samvol_inventory_documents', function ($table) {
            if (!Schema::hasColumn('samvol_inventory_documents', 'mime_type')) {
                $table->string('mime_type', 120)->nullable();
            }

            if (!Schema::hasColumn('samvol_inventory_documents', 'file_size')) {
                $table->unsignedBigInteger('file_size')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('samvol_inventory_products', function ($table) {
            if (Schema::hasColumn('samvol_inventory_products', 'mobile_summary')) {
                $table->dropColumn('mobile_summary');
            }
            if (Schema::hasColumn('samvol_inventory_products', 'external_id')) {
                $table->dropColumn('external_id');
            }
        });

        Schema::table('samvol_inventory_operations', function ($table) {
            if (Schema::hasColumn('samvol_inventory_operations', 'mobile_note')) {
                $table->dropColumn('mobile_note');
            }
            if (Schema::hasColumn('samvol_inventory_operations', 'external_id')) {
                $table->dropColumn('external_id');
            }
        });

        Schema::table('samvol_inventory_documents', function ($table) {
            if (Schema::hasColumn('samvol_inventory_documents', 'mime_type')) {
                $table->dropColumn('mime_type');
            }
            if (Schema::hasColumn('samvol_inventory_documents', 'file_size')) {
                $table->dropColumn('file_size');
            }
        });
    }
}
