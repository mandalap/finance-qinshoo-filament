<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin']);
        $approverRole = Role::firstOrCreate(['name' => 'approver']);
        $viewerRole = Role::firstOrCreate(['name' => 'viewer']);

        // Create or update super-admin user
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@yayasan.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'), // ganti dengan password yang aman
            ]
        );
        
        // Assign super-admin role
        if (!$adminUser->hasRole('super-admin')) {
            $adminUser->assignRole('super-admin');
        }

        // Create or update approver user
        $approverUser = User::firstOrCreate(
            ['email' => 'approver@yayasan.com'],
            [
                'name' => 'Approver Yayasan',
                'password' => Hash::make('password'),
            ]
        );

        // Assign approver role
        if (!$approverUser->hasRole('approver')) {
            $approverUser->assignRole('approver');
        }

        // Create or update viewer user (hanya bisa melihat)
        $viewerUser = User::firstOrCreate(
            ['email' => 'viewer@yayasan.com'],
            [
                'name' => 'Viewer Yayasan',
                'password' => Hash::make('password'),
            ]
        );

        // Assign viewer role
        if (!$viewerUser->hasRole('viewer')) {
            $viewerUser->assignRole('viewer');
        }

        // Output untuk konfirmasi
        $this->command->info('✅ Roles created successfully!');
        $this->command->info("📧 Super Admin: admin@yayasan.com");
        $this->command->info("📧 Approver: approver@yayasan.com");
        $this->command->info("📧 Viewer: viewer@yayasan.com");
        $this->command->info("🔑 Default password: password");
    }
}