<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $users = User::query()->get();
        foreach ($users as $user) {
            Employee::query()->create([
                'user_id' => $user->id,
                'employment_type' => random_int(1,2)
            ]);
        }
    }
}
