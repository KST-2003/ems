<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Create Permissions
        // We define granular permissions for the Employee module
        $permissions = [
            'view employees',
            'create employees',
            'edit employees',
            'delete employees',
            'print employees', // <--- This distinguishes User A from User B
            'manage users',    // <--- Only for Super Admin
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // 3. Create Roles and Assign Permissions

        // --- Role: Super Admin ---
        // Has EVERYTHING.
        $superAdminRole = Role::create(['name' => 'Super Admin']);
        $superAdminRole->givePermissionTo(Permission::all());

        // --- Role: Admin ---
        // Can do everything with employees, but cannot manage other users/admins.
        $adminRole = Role::create(['name' => 'Admin']);
        $adminRole->givePermissionTo([
            'view employees',
            'create employees',
            'edit employees',
            'delete employees',
            'print employees'
        ]);

        // --- Role: User A ---
        // Can View and Print only. (No Create/Edit/Delete)
        $userARole = Role::create(['name' => 'User A']);
        $userARole->givePermissionTo([
            'view employees',
            'print employees'
        ]);

        // --- Role: User B ---
        // Can View only. (No Print)
        $userBRole = Role::create(['name' => 'User B']);
        $userBRole->givePermissionTo([
            'view employees'
        ]);

        // 4. Create Default Users for Testing

        // Super Admin User
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('password'), // password is "password"
            'is_active' => true,
        ]);
        $superAdmin->assignRole($superAdminRole);

        // Admin User
        $admin = User::create([
            'name' => 'Admin Manager',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);
        $admin->assignRole($adminRole);

        // User A (Viewer + Printer)
        $userA = User::create([
            'name' => 'User A (Printer)',
            'email' => 'usera@example.com',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);
        $userA->assignRole($userARole);

        // User B (Viewer Only)
        $userB = User::create([
            'name' => 'User B (Viewer)',
            'email' => 'userb@example.com',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);
        $userB->assignRole($userBRole);
    }
}