<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin']);
        $approverRole = Role::firstOrCreate(['name' => 'approver']);
        
        // Assign super-admin role to existing admin user
        $adminUser = User::where('email', 'admin@yayasan.com')->first();
        if ($adminUser) {
            $adminUser->assignRole('super-admin');
        }
        
        // Create default approver user if not exists
        $approverUser = User::firstOrCreate(
            ['email' => 'approver@yayasan.com'],
            [
                'name' => 'Approver Yayasan',
                'password' => bcrypt('password'),
            ]
        );
        $approverUser->assignRole('approver');
    }
}
