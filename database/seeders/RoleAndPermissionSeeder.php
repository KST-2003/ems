<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleAndPermissionSeeder extends Seeder
{
    public function run()
    {
        // 1. Reset cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Define Granular Permissions
        $permissions = [
            'view employees',   // Level 1, 2, 3
            'print employees',  // Level 1, 2, 3
            'create employees', // Level 2, 3
            'edit employees',   // Level 3
            'delete employees', // Super Admin Only
            'manage users',    // Super Admin Only
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // 3. Create Roles and Assign Permissions

        // --- Super Admin ---
        $superAdminRole = Role::create(['name' => 'Super Admin']);
        $superAdminRole->givePermissionTo(Permission::all());

        // --- Level 3 (Editor) ---
        $level3 = Role::create(['name' => 'Level 3']);
        $level3->givePermissionTo([
            'view employees',
            'print employees',
            'create employees',
            'edit employees'
        ]);

        // --- Level 2 (Data Entry) ---
        $level2 = Role::create(['name' => 'Level 2']);
        $level2->givePermissionTo([
            'view employees',
            'print employees',
            'create employees'
        ]);

        // --- Level 1 (Employee/Viewer) ---
        $level1 = Role::create(['name' => 'Level 1']);
        $level1->givePermissionTo([
            'view employees',
            'print employees'
        ]);

        // 4. Create Default Users for Testing
        $this->createUser('Super Admin', 'superadmin@gmail.com', $superAdminRole);
        $this->createUser('Editor User', 'level3@gmail.com', $level3);
        $this->createUser('Data Entry User', 'level2@gmail.com', $level2);
        $this->createUser('Viewer User', 'level1@gmail.com', $level1);
    }

    private function createUser($name, $email, $role)
    {
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);
        $user->assignRole($role);
    }
}