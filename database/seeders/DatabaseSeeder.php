<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(RoleAndPermissionSeeder::class);
        $this->call(LeaveTypeSeeder::class);
        $this->call(EmployeeSeeder::class);
        $this->call(CompanyCalendarSeeder::class);

        // You now have 4 users in your database with the password password.

        // superadmin@example.com (Has access to everything)

        // admin@example.com (Cannot manage users)

        // usera@example.com (Can View + Print)

        // userb@example.com (Can View Only)
    }
}
