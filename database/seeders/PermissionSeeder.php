<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $adminPermissions = [
            'view dashboard',
            'view categories',
            'create categories',
            'edit categories',
            'delete categories',
            'view books',
            'create books',
            'edit books',
            'delete books',
            'view members',
            'create members',
            'edit members',
            'delete members',
            'view borrowings',
            'create borrowings',
            'edit borrowings',
            'view fines',
            'edit fines',
            'view reports',
            'view activity logs',
            'view attendances',
        ];

        $memberPermissions = [
            'view dashboard',
            'create borrowings',
            'view fines',
            'edit profile',
        ];

        foreach (array_unique(array_merge($adminPermissions, $memberPermissions)) as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        Role::findOrCreate('admin', 'web')->syncPermissions($adminPermissions);
        Role::findOrCreate('member', 'web')->syncPermissions($memberPermissions);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
