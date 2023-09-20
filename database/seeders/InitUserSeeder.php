<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class InitUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdminRole = Role::create(['name' => 'super-admin', 'guard_name' => 'api']);

        $allPermission = Permission::create(['name' => 'all', 'guard_name' => 'api']);

        $superAdminRole->givePermissionTo($allPermission);

        $superAdmin = User::create([
            'name' => 'super admin',
            'email' => 'superadmin@admin.com',
            'password' => bcrypt('superadmin123'),
        ]);

        $superAdmin->assignRole($superAdminRole);
    }
}
