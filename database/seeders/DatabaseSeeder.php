<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $teacherRole = Role::create(['name' => 'teacher']);
        $studentRole = Role::create(['name' => 'student']);
        
        // Create permissions
        $permissions = [
            'manage-users',
            'manage-attendance',
            'view-reports',
            'manage-classrooms',
            'manage-schedules',
        ];
        
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
        
        // Assign permissions to roles
        $adminRole->givePermissionTo(Permission::all());
        $teacherRole->givePermissionTo(['manage-attendance', 'view-reports']);
        $studentRole->givePermissionTo(['view-reports']);
    }
}