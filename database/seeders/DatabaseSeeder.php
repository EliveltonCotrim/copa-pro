<?php

namespace Database\Seeders;

use App\Enum\{PlayerExperienceLevelEnum, PlayerPlatformGameEnum, PlayerSexEnum, PlayerStatusEnum};
use App\Models\{Organizer, Player, User};
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

        User::updateOrCreate(
            [
                'email' => 'adm@example.com',
            ],
            [
                'name' => 'Admin',
                'email_verified_at' => now(),
                'password' => Hash::make('123456'),
            ]
        )->assignRole(RoleEnum::ADMIN->value);

        $userOrganizer = User::updateOrCreate([
            'email' => 'organizerTest@example.com',
        ], [
            'name' => 'Organizer test',
            'email' => 'organizerTest@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('123456'),
        ])->assignRole(RoleEnum::ORGANIZATION->value);

        $organizer = Organizer::updateOrCreate([
            'name' => 'Organizer Test',
        ], [
            'name' => 'Organizer Test',
            'phone' => '999999999',
            'descrition' => 'Organizer Test Description',
        ]);

        $organizer->user()->save($userOrganizer);

        $player = Player::updateOrCreate([
            'nickname' => 'PlayerTest'
        ], [
            'nickname' => 'PlayerTest',
            'phone' => '999999999',
            'level_experience' => PlayerExperienceLevelEnum::BEGINNER->value,
            'bio' => 'Organizer Test bio',
            'heart_team_name' => 'Vasco',
            'birth_dt' => now()->format('Y-m-d'),
            'sex' => PlayerSexEnum::MALE->value,
            'status' => PlayerStatusEnum::ACTIVE->value,
            'game_platform' => PlayerPlatformGameEnum::PC->value,
        ]);

        $user = User::firstOrNew([
            'email' => 'player@mail.com',
        ]);

        $user->fill([
            'name' => 'Player Test',
            'password' => Hash::make('123456'),
        ]);

        $user->userable()->associate($player);
        $user->save();

        $user->assignRole(RoleEnum::PLAYER->value);
    }
}
