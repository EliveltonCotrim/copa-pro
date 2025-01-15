<?php

namespace Database\Seeders;

use App\Models\Organizer;
use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\RoleEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionsSeed::class,
            RolesSeed::class,
        ]);

        User::create([
            'name' => 'Admin',
            'email' => 'adm@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('123456'),
        ])->assignRole(RoleEnum::ADMIN->value);

        // $organizer = Organizer::create([
        //     'name' => 'Organizer Test',
        //     'phone' => '999999999',
        //     'descrition' => 'Organizer Test Description',
        // ]);

        User::create([
            'name' => 'Organizer test',
            'email' => 'organizerTest@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('123456'),
        ])->assignRole(RoleEnum::ORGANIZATION->value);
    }
}
