<?php

namespace Database\Seeders;

use App\Models\{Permission, Role};
use App\RoleEnum;
use Illuminate\Database\Seeder;

class RolesSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::findOrCreate(RoleEnum::ADMIN->value)->givePermissionTo(Permission::all()->pluck('name')->toArray());
        Role::findOrCreate(RoleEnum::ORGANIZATION->value)->givePermissionTo(Permission::where('name', 'like', 'championships:%')->pluck('name')->toArray());
        Role::findOrCreate(RoleEnum::PLAYER->value)->givePermissionTo(Permission::where('name', 'like', 'championships:%')->pluck('name')->toArray()); // criar permissions para jogador
    }
}
