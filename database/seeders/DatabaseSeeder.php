<?php

namespace Database\Seeders;

use App\Models\Organizer;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $organizer = Organizer::create([
            'name' => 'Organizer Test',
            'phone' => '999999999',
            'descrition' => 'Organizer Test Description',
        ]);

        $organizer->user()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('123456'),
        ]);
    }
}
