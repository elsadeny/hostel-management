<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'manage users',
            'manage hostels',
            'manage rooms',
            'manage students',
            'manage allocations',
            'view allocations',
            'request room change',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        $studentRole = Role::create(['name' => 'student']);
        $studentRole->givePermissionTo(['view allocations', 'request room change']);

        // Assign admin role to existing admin user
        $adminUser = User::where('email', 'admin@unilak.ac.rw')->first();
        if ($adminUser) {
            $adminUser->assignRole('admin');
        }

        // Assign student role to all student users
        $studentUsers = User::whereIn('email', function ($query) {
            $query->select('email')->from('students');
        })->get();

        foreach ($studentUsers as $studentUser) {
            $studentUser->assignRole('student');
        }
    }
}
