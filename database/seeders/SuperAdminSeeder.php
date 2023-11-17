<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {
        $role = Role::create(['name' => 'super admin']);

        // Create the user
        $user = User::create([
            'first_name' => "Super",
            'last_name' => "Admin",
            'birthdate' => "2000-1-1",
            'email' => 'super-admin@admin.com',
            'password' => bcrypt('password'), 
        ]);

        // Assign the role to the user
        $user->assignRole($role);
    }
}