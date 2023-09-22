<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Roles
        $roles = [
            'rootadmin', 'admin', 'staff',
        ];
        foreach ($roles as $role) {
            \App\Models\Role::create([
                'name' => $role,
            ]);
        }

        // Absence Reasons
        $absenceReasons = [
            'Illness', 'Home Office', 'Vacation', 'Business Trip', 'Other',
        ];

        foreach ($absenceReasons as $reason) {
            \App\Models\AbsenceReason::create([
                'reason' => $reason,
            ]);
        }

        // Absence Statuses
        $absenceStatuses = [
            'pending', 'approved', 'rejected',
        ];

        foreach ($absenceStatuses as $status) { // first needs to be pending
            \App\Models\AbsenceStatus::create([
                'status' => $status,
            ]);
        }

        $adminUser = User::factory()->create([
            'username' => 'admin',
            'name' => 'Marc Wenzelburger',
            'email' => 'admin@example.de',
            'password' => bcrypt('password'),
            'role_id' => 1,
        ]);

        //User::factory(10)->create();

        //Absence::factory(10)->create();

//        Absence::factory()->create([
//            'user_id' => $adminUser->id,
//        ]);

        //Chat::factory(50)->create();

    }
}
