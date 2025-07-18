<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->createMany([
            [
                'name' => 'ユーザー1',
                'email' => 'user1@example.com',
            ],
            [
                'name' => 'ユーザー2',
                'email' => 'user2@example,com',
            ],
            [
                'name' => 'ユーザー3',
                'email' => 'user3@example,com',
            ],
        ]);
    }
}
