<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Privilege;
use App\Models\Role;
use App\Models\Feature;
use App\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $features = ['user-access'];

        $roles = ['DEVELOPMENT','ADMINISTRATOR','OPEN_API'];

        $privilege['DEVELOPMENT'] = ['user-access'];

        $privilege['ADMINISTRATOR'] = ['user-access'];

        $permissions = ['create','update','delete','read'];

        foreach ($roles as $key => $roleName) {
            // Find or create the role
            $role = Role::firstOrCreate(['name' => $roleName]);

            if (isset($privilege[$roleName])) {
                $listPrivilage = $privilege[$roleName];
                foreach ($listPrivilage as $key2 => $privilageName) {
                    $feature = Feature::firstOrCreate(['name' => $privilageName]);
                    foreach ($permissions as $key3 => $permissionName) {
                        $permission = Permission::firstOrCreate(['name' => $permissionName]);
                        $insertPrivilage = Privilege::firstOrCreate([
                            'role_id' => $role->role_id,
                            'feature_id' => $feature->feature_id,
                            'permission_id' => $permission->permission_id
                        ]);
                    }
                }
            }
        }
    }
}
