<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        Permission::create(['name' => 'create ticket', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'delete ticket', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'view ticket', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'edit ticket', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'comment ticket', 'guard_name' => 'sanctum']);

        $agent = Role::create(['name' => 'agent', 'guard_name' => 'sanctum']);
        $agent->givePermissionTo(Permission::all());

        $client = Role::create(['name' => 'client', 'guard_name' => 'sanctum']);
        $client->givePermissionTo(['view ticket', 'create ticket', 'comment ticket']);
    }
}
