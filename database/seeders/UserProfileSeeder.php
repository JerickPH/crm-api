<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UserProfileSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();

        foreach ($users as $user) {
            DB::table('user_profiles')->insert([
                'user_id' => $user->id,
                'first_name' => explode(' ', $user->name)[0],
                'last_name' => explode(' ', $user->name)[1] ?? '',
                'phone' => '123-456-7890',
                'role' => 'Employee',
                'company_id' => 1, // Assuming all users belong to the first company
                'employee_id' => strtoupper(substr($user->name, 0, 3)) . rand(100, 999),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
