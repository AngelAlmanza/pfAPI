<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        Permission::create(['name' => 'delete any post']);
        Permission::create(['name' => 'delete own post']);
        Role::create(['name' => 'admin'])->givePermissionTo('delete any post');
        Role::create(['name' => 'user'])->givePermissionTo('delete own post');
    }
}
