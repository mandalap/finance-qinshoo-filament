<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AssignSuperAdminRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:make-super-admin {user? : User ID or email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign super_admin role to a user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get user from argument or ask
        $userIdentifier = $this->argument('user');
        
        if (!$userIdentifier) {
            $userIdentifier = $this->ask('Enter user email or ID');
        }
        
        // Find user by email or ID
        $user = is_numeric($userIdentifier)
            ? User::find($userIdentifier)
            : User::where('email', $userIdentifier)->first();
        
        if (!$user) {
            $this->error('User not found!');
            return 1;
        }
        
        // Create super_admin role if not exists
        $role = Role::firstOrCreate(['name' => 'super_admin']);
        
        // Assign role
        if ($user->hasRole('super_admin')) {
            $this->info("User {$user->email} already has super_admin role!");
        } else {
            $user->assignRole('super_admin');
            $this->info("✅ Super admin role assigned to {$user->email}!");
        }
        
        return 0;
    }
}
