<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::findOrCreate('users:list');
        Permission::findOrCreate('users:view');
        Permission::findOrCreate('users:create');
        Permission::findOrCreate('users:edit');
        Permission::findOrCreate('users:delete');
        Permission::findOrCreate('users:restore');
        Permission::findOrCreate('users:forceDelete');

        Permission::findOrCreate('roles:list');
        Permission::findOrCreate('roles:view');
        Permission::findOrCreate('roles:create');
        Permission::findOrCreate('roles:edit');
        Permission::findOrCreate('roles:delete');
        Permission::findOrCreate('roles:restore');
        Permission::findOrCreate('roles:forceDelete');

        Permission::findOrCreate('permissions:list');
        Permission::findOrCreate('permissions:view');
        Permission::findOrCreate('permissions:create');
        Permission::findOrCreate('permissions:edit');
        Permission::findOrCreate('permissions:delete');
        Permission::findOrCreate('permissions:restore');
        Permission::findOrCreate('permissions:forceDelete');

        Permission::findOrCreate('championships:list');
        Permission::findOrCreate('championships:view');
        Permission::findOrCreate('championships:create');
        Permission::findOrCreate('championships:edit');
        Permission::findOrCreate('championships:delete');
        Permission::findOrCreate('championships:restore');
        Permission::findOrCreate('championships:forceDelete');
    }
}
