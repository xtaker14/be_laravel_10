<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
// use Spatie\Permission\Models\Role;
// use Spatie\Permission\Models\Permission;
use Carbon\Carbon;

use App\Helpers\Main;
use App\Models\User; 
use App\Models\Role;
use App\Models\Permission;
use App\Models\Feature;
use App\Models\Privilege; 

class InitSeeder extends Seeder
{ 
    private function masterPermissionSA()
    {
        $params = ['name' => 'DEVELOPMENT'];
        // Role::where($params)->delete();
        Main::setCreatedModifiedVal(false, $params);
        $sa_role = Role::create($params);

        $params = ['name' => 'all'];
        // Permission::where($params)->delete();
        Main::setCreatedModifiedVal(false, $params);
        $sa_all_permission = Permission::create($params);

        $params = ['name' => 'create'];
        Main::setCreatedModifiedVal(false, $params);
        $sa_create_permission = Permission::create($params);

        $params = ['name' => 'update'];
        Main::setCreatedModifiedVal(false, $params);
        $sa_update_permission = Permission::create($params);

        $params = ['name' => 'delete'];
        Main::setCreatedModifiedVal(false, $params);
        $sa_delete_permission = Permission::create($params);

        $params = ['name' => 'read'];
        Main::setCreatedModifiedVal(false, $params);
        $sa_read_permission = Permission::create($params);

        $params = ['name' => 'manage-users'];
        // Feature::where($params)->delete();
        Main::setCreatedModifiedVal(false, $params);
        $sa_manage_users_feature = Feature::create($params);

        $params = [
            'role_id' => $sa_role->role_id, 
            'feature_id' => $sa_manage_users_feature->feature_id, 
            'permission_id' => $sa_all_permission->permission_id
        ];
        // Privilege::where($params)->delete();
        Main::setCreatedModifiedVal(false, $params);
        $sa_privilege = Privilege::create($params);

        return [
            'sa_role' => $sa_role,
        ];
    } 

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (App::environment(['local', 'staging', 'development'])) { 

            // --- super admin

            $master_permission_sa = $this->masterPermissionSA();
            $sa_role = $master_permission_sa['sa_role'];

            $params = [
                'role_id' => $sa_role->role_id,
                'gender' => 'P',
                'full_name' => 'development',
                'email' => 'development@admin.com',
                'username' => 'development',
                'password' => bcrypt('development123'),
            ];
            Main::setCreatedModifiedVal(false, $params);
            $sa_user = User::create($params); 
        }
    }
}