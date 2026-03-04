<?php namespace Samvol\Inventory\Updates;

use Schema;
use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableUpdateUsersAddOrganizationFields extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('users')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'organization_id')) {
                $table->unsignedInteger('organization_id')->nullable()->index();
            }

            if (!Schema::hasColumn('users', 'organization_role')) {
                $table->string('organization_role', 60)->nullable()->index();
            }

            if (!Schema::hasColumn('users', 'organization_status')) {
                $table->string('organization_status', 40)->nullable()->index();
            }

            if (!Schema::hasColumn('users', 'organization_approved_at')) {
                $table->timestamp('organization_approved_at')->nullable();
            }

            if (!Schema::hasColumn('users', 'organization_approved_by')) {
                $table->unsignedInteger('organization_approved_by')->nullable();
            }
        });
    }

    public function down()
    {
        if (!Schema::hasTable('users')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'organization_approved_by')) {
                $table->dropColumn('organization_approved_by');
            }

            if (Schema::hasColumn('users', 'organization_approved_at')) {
                $table->dropColumn('organization_approved_at');
            }

            if (Schema::hasColumn('users', 'organization_status')) {
                $table->dropColumn('organization_status');
            }

            if (Schema::hasColumn('users', 'organization_role')) {
                $table->dropColumn('organization_role');
            }

            if (Schema::hasColumn('users', 'organization_id')) {
                $table->dropColumn('organization_id');
            }
        });
    }
}
