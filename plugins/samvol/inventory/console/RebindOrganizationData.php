<?php namespace Samvol\Inventory\Console;

use DB;
use Illuminate\Console\Command;
use Samvol\Inventory\Models\Organization;
use Winter\User\Models\User;

class RebindOrganizationData extends Command
{
    protected $signature = 'samvol:rebind-org-data
        {user : User id, email or login}
        {--org-id= : Target organization id}
        {--org-code= : Target organization code}
        {--org-name= : Target organization exact name}
        {--all : Rebind all rows, not only NULL organization_id}
        {--activate-org : Force target organization is_active = 1}
        {--pending : Keep user status pending instead of approved}
        {--dry-run : Print counters without changing DB}';

    protected $description = 'Assign user to organization and rebind inventory data to that organization';

    public function handle()
    {
        $user = $this->resolveUser((string) $this->argument('user'));
        if (!$user) {
            $this->error('User not found. Pass user id, email, or login.');
            return 1;
        }

        $organization = $this->resolveOrganization();
        if (!$organization) {
            $this->error('Organization not found. Pass --org-id, --org-code, or --org-name.');
            return 1;
        }

        $dryRun = (bool) $this->option('dry-run');
        $rebindAll = (bool) $this->option('all');
        $activateOrg = (bool) $this->option('activate-org');
        $pending = (bool) $this->option('pending');
        $organizationId = (int) $organization->id;

        $tables = [
            'samvol_inventory_products',
            'samvol_inventory_categories',
            'samvol_inventory_notes',
            'samvol_inventory_operations',
            'samvol_inventory_documents',
            'samvol_inventory_operation_products',
            'samvol_inventory_note_products',
        ];

        $this->line('Target organization: #' . $organizationId . ' ' . $organization->name . ' [' . $organization->code . ']');
        $this->line('Target user: #' . $user->id . ' ' . ($user->email ?: $user->login));
        $this->line('Mode: ' . ($rebindAll ? 'all rows' : 'only NULL organization_id rows'));
        $this->line($dryRun ? 'Dry-run: yes' : 'Dry-run: no');

        $stats = [];
        foreach ($tables as $table) {
            if (!DB::getSchemaBuilder()->hasTable($table) || !DB::getSchemaBuilder()->hasColumn($table, 'organization_id')) {
                $stats[$table] = ['affected' => 0, 'skipped' => true];
                continue;
            }

            $query = DB::table($table);
            if (!$rebindAll) {
                $query->whereNull('organization_id');
            }

            $affected = $query->count();
            $stats[$table] = ['affected' => $affected, 'skipped' => false];
        }

        foreach ($stats as $table => $row) {
            if ($row['skipped']) {
                $this->warn($table . ': skipped (table or organization_id column missing)');
                continue;
            }
            $this->info($table . ': will update ' . $row['affected'] . ' rows');
        }

        if ($dryRun) {
            $this->comment('Dry-run complete. No changes were applied.');
            return 0;
        }

        DB::beginTransaction();
        try {
            if ($activateOrg) {
                DB::table('samvol_inventory_organizations')
                    ->where('id', $organizationId)
                    ->update(['is_active' => 1, 'updated_at' => now()]);
            }

            foreach ($tables as $table) {
                if (!DB::getSchemaBuilder()->hasTable($table) || !DB::getSchemaBuilder()->hasColumn($table, 'organization_id')) {
                    continue;
                }

                $update = DB::table($table);
                if (!$rebindAll) {
                    $update->whereNull('organization_id');
                }

                $update->update(['organization_id' => $organizationId]);
            }

            $userUpdate = [
                'organization_id' => $organizationId,
                'organization_role' => 'admin',
                'organization_status' => $pending ? 'pending' : 'approved',
                'organization_approved_at' => $pending ? null : now(),
            ];

            DB::table('users')->where('id', (int) $user->id)->update($userUpdate);

            DB::commit();
            $this->info('Done. User and data are bound to organization #' . $organizationId . '.');
            return 0;
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error('Failed: ' . $e->getMessage());
            return 1;
        }
    }

    private function resolveUser(string $userArg): ?User
    {
        if (ctype_digit($userArg)) {
            $user = User::query()->find((int) $userArg);
            if ($user) {
                return $user;
            }
        }

        $hasLoginColumn = DB::getSchemaBuilder()->hasColumn('users', 'login');

        return User::query()
            ->where(function ($query) use ($userArg, $hasLoginColumn) {
                $query->where('email', $userArg);

                if ($hasLoginColumn) {
                    $query->orWhere('login', $userArg);
                }
            })
            ->first();
    }

    private function resolveOrganization(): ?Organization
    {
        $id = (string) ($this->option('org-id') ?? '');
        $code = trim((string) ($this->option('org-code') ?? ''));
        $name = trim((string) ($this->option('org-name') ?? ''));

        if ($id !== '' && ctype_digit($id)) {
            $organization = Organization::query()->find((int) $id);
            if ($organization) {
                return $organization;
            }
        }

        if ($code !== '') {
            $organization = Organization::query()
                ->whereRaw('LOWER(code) = ?', [mb_strtolower($code)])
                ->first();
            if ($organization) {
                return $organization;
            }
        }

        if ($name !== '') {
            return Organization::query()
                ->whereRaw('LOWER(TRIM(name)) = ?', [trim((string) mb_strtolower($name))])
                ->first();
        }

        return null;
    }
}
