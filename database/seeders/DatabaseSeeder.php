<?php

namespace Database\Seeders;

use App\Models\Genre;
use App\Models\HashTag;
use App\Models\UrlLibrary;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);
        HashTag::factory(10)->create();
        Genre::factory(10)->create();
        UrlLibrary::factory(10)->create();
    }
}
