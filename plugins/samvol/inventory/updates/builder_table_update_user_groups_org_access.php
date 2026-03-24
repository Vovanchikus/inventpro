<?php namespace Samvol\Inventory\Updates;

use DB;
use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableUpdateUserGroupsOrgAccess extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('user_groups')) {
            return;
        }

        $this->upsertGroup('project_admin', 'Адмін проєкту', 'Повний проектний доступ до інвентаризації');
        $orgUserGroupId = $this->upsertGroup('org_user', 'Користувач організації', 'Базова група для користувачів з організаційним доступом');

        if ($orgUserGroupId > 0 && Schema::hasTable('users') && Schema::hasTable('users_groups')) {
            $userIds = DB::table('users')->pluck('id');
            foreach ($userIds as $userId) {
                $exists = DB::table('users_groups')
                    ->where('user_id', (int) $userId)
                    ->where('user_group_id', $orgUserGroupId)
                    ->exists();

                if (!$exists) {
                    DB::table('users_groups')->insert([
                        'user_id' => (int) $userId,
                        'user_group_id' => $orgUserGroupId,
                    ]);
                }
            }
        }
    }

    public function down()
    {
    }

    protected function upsertGroup(string $code, string $name, string $description): int
    {
        $existingId = (int) DB::table('user_groups')->where('code', $code)->value('id');
        if ($existingId > 0) {
            DB::table('user_groups')->where('id', $existingId)->update([
                'name' => $name,
                'description' => $description,
                'updated_at' => now(),
            ]);
            return $existingId;
        }

        return (int) DB::table('user_groups')->insertGetId([
            'name' => $name,
            'code' => $code,
            'description' => $description,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
