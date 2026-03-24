<?php namespace Samvol\Inventory\Updates;

use DB;
use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableUpdateUserGroupsCleanupLegacy extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('user_groups') || !Schema::hasTable('users_groups')) {
            return;
        }

        $orgUserGroupId = (int) DB::table('user_groups')->where('code', 'org_user')->value('id');
        if ($orgUserGroupId <= 0) {
            return;
        }

        $legacyGroupIds = DB::table('user_groups')
            ->whereIn('code', ['guest', 'viewer', 'editor', 'admin'])
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->filter(fn($id) => $id > 0)
            ->values();

        if ($legacyGroupIds->isEmpty()) {
            return;
        }

        $affectedUserIds = DB::table('users_groups')
            ->whereIn('user_group_id', $legacyGroupIds->all())
            ->pluck('user_id')
            ->map(fn($id) => (int) $id)
            ->unique()
            ->values();

        foreach ($affectedUserIds as $userId) {
            $exists = DB::table('users_groups')
                ->where('user_id', $userId)
                ->where('user_group_id', $orgUserGroupId)
                ->exists();

            if (!$exists) {
                DB::table('users_groups')->insert([
                    'user_id' => $userId,
                    'user_group_id' => $orgUserGroupId,
                ]);
            }
        }

        DB::table('users_groups')->whereIn('user_group_id', $legacyGroupIds->all())->delete();
        DB::table('user_groups')->whereIn('id', $legacyGroupIds->all())->delete();
    }

    public function down()
    {
    }
}
