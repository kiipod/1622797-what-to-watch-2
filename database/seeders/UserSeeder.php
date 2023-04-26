<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        User::factory()
            ->count(2)
            ->create([
                'role' => UserRole::whereRole('moderator')->value('id'),
            ]);

        User::factory()
            ->count(10)
            ->create([
                'role' => UserRole::whereRole('user')->value('id'),
            ]);
    }
}
