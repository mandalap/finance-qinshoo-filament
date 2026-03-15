<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * List of permissions to create.
     */
    protected array $permissions = [
        // Transaksi Keuangan
        'view-transaksi',
        'create-transaksi',
        'edit-transaksi',
        'delete-transaksi',

        // Budget
        'view-budget',
        'create-budget',
        'edit-budget',
        'delete-budget',

        // Pengajuan Barang
        'view-pengajuan',
        'approve-pengajuan',
        'reject-pengajuan',

        // Kategori Transaksi
        'view-kategori',
        'create-kategori',
        'edit-kategori',
        'delete-kategori',

        // User Management
        'view-user',
        'create-user',
        'edit-user',
        'delete-user',

        // Laporan
        'view-laporan-keuangan',
        'view-laporan-budget',
        'export-laporan',
    ];

    public function run(): void
    {
        // Create all permissions
        foreach ($this->permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        $this->command->info('✅ Permissions created successfully!');

        // Assign permissions to roles

        // Super Admin - semua permissions
        $superAdmin = Role::where('name', 'super-admin')->first();
        if ($superAdmin) {
            $superAdmin->syncPermissions($this->permissions);
            $this->command->info('✅ Super Admin permissions assigned!');
        }

        // Approver - bisa view dan approve pengajuan, view transaksi
        $approver = Role::where('name', 'approver')->first();
        if ($approver) {
            $approverPermissions = [
                'view-transaksi',
                'view-pengajuan',
                'approve-pengajuan',
                'reject-pengajuan',
                'view-laporan-keuangan',
                'view-laporan-budget',
            ];
            $approver->syncPermissions($approverPermissions);
            $this->command->info('✅ Approver permissions assigned!');
        }

        // Viewer - hanya view
        $viewer = Role::where('name', 'viewer')->first();
        if ($viewer) {
            $viewerPermissions = [
                'view-transaksi',
                'view-budget',
                'view-pengajuan',
                'view-kategori',
                'view-laporan-keuangan',
                'view-laporan-budget',
            ];
            $viewer->syncPermissions($viewerPermissions);
            $this->command->info('✅ Viewer permissions assigned!');
        }

        $this->command->info('✅ All permissions have been assigned to roles!');
    }
}
