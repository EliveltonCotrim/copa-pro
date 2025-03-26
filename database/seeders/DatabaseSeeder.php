<?php

namespace Database\Seeders;

use App\Enum\PlayerExperienceLevelEnum;
use App\Enum\PlayerPlatformGameEnum;
use App\Enum\PlayerSexEnum;
use App\Enum\PlayerStatusEnum;
use App\Models\Organizer;
use App\Models\Player;
use App\Models\User;
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
            UFSeeder::class,
        ]);

        User::create([
            'name' => 'Admin',
            'email' => 'adm@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('123456'),
        ])->assignRole(RoleEnum::ADMIN->value);

        $userOrganizer = User::create([
            'name' => 'Organizer test',
            'email' => 'organizerTest@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('123456'),
        ])->assignRole(RoleEnum::ORGANIZATION->value);

        $organizer = Organizer::create([
            'name' => 'Organizer Test',
            'phone' => '999999999',
            'descrition' => 'Organizer Test Description',
        ]);

        $organizer->user()->save($userOrganizer);

        $player = Player::create([
            'phone' => '999999999',
            'level_experience' => PlayerExperienceLevelEnum::BEGINNER->value,
            'bio' => 'Organizer Test bio',
            'heart_team_name' => 'Vasco',
            'birth_dt' => now()->format('Y-m-d'),
            'sex' => PlayerSexEnum::MALE->value,
            'status' => PlayerStatusEnum::ACTIVE->value,
            'game_platform' => PlayerPlatformGameEnum::PC->value,
        ]);

        $player->user()->create([
            'name' => 'Player Test',
            'email' => 'player@mail.com',
            'password' => Hash::make('123456'),
        ])->assignRole(RoleEnum::PLAYER->value);
    }
}
