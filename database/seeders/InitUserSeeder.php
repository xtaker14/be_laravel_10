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
use App\Models\UserHub;
use App\Models\UserClient;
use App\Models\UserPartner;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Feature;
use App\Models\Privilege;
use App\Models\HubType;
use App\Models\Hub;
use App\Models\HubArea;
use App\Models\Organization;
use App\Models\Client;
use App\Models\Country;
use App\Models\Province;
use App\Models\City;
use App\Models\District;
use App\Models\Subdistrict;
use App\Models\Partner;
use App\Models\Courier;

class InitUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $superAdminRole = Role::create(['name' => 'super-admin', 'guard_name' => 'api']);

        // $allPermission = Permission::create(['name' => 'all', 'guard_name' => 'api']);

        // $superAdminRole->givePermissionTo($allPermission); 

        if (App::environment(['local', 'staging', 'development'])) {
            $params = ['name' => 'super-admin'];
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

            $params = [
                'role_id' => $sa_role->role_id,
                'type' => 'test sa',
                'full_name' => 'super admin',
                'email' => 'superadmin@admin.com',
                'password' => bcrypt('superadmin123'),
            ];
            Main::setCreatedModifiedVal(false, $params);
            $sa_user = User::create($params); 

            // --- driver / courier
            
            $params = ['name' => 'driver'];
            // Role::where($params)->delete();
            Main::setCreatedModifiedVal(false, $params);
            $driver_role = Role::create($params); 

            // --- 

            $params = ['name' => 'view-packages'];
            Main::setCreatedModifiedVal(false, $params);
            $sa_view_packages_permission = Permission::create($params);

            $params = ['name' => 'accept-package'];
            Main::setCreatedModifiedVal(false, $params);
            $sa_accept_package_permission = Permission::create($params);

            $params = ['name' => 'package-delivery'];
            Main::setCreatedModifiedVal(false, $params);
            $sa_package_delivery_feature = Feature::create($params);

            $params = [
                'role_id' => $driver_role->role_id, 
                'feature_id' => $sa_package_delivery_feature->feature_id, 
                'permission_id' => $sa_view_packages_permission->permission_id
            ];
            Privilege::where($params)->delete();
            Main::setCreatedModifiedVal(false, $params);
            $sa_privilege = Privilege::create($params);

            $params = [
                'role_id' => $driver_role->role_id, 
                'feature_id' => $sa_package_delivery_feature->feature_id, 
                'permission_id' => $sa_accept_package_permission->permission_id
            ];
            Privilege::where($params)->delete();
            Main::setCreatedModifiedVal(false, $params);
            $sa_privilege = Privilege::create($params);

            // --- 
            
            $params = ['name' => 'update-status'];
            Main::setCreatedModifiedVal(false, $params);
            $sa_update_status_permission = Permission::create($params);

            $params = ['name' => 'add-notes'];
            Main::setCreatedModifiedVal(false, $params);
            $sa_add_notes_permission = Permission::create($params);

            $params = ['name' => 'delivery-updates'];
            Main::setCreatedModifiedVal(false, $params);
            $sa_delivery_updates_feature = Feature::create($params);

            $params = [
                'role_id' => $driver_role->role_id, 
                'feature_id' => $sa_delivery_updates_feature->feature_id, 
                'permission_id' => $sa_update_status_permission->permission_id
            ];
            Privilege::where($params)->delete();
            Main::setCreatedModifiedVal(false, $params);
            $sa_privilege = Privilege::create($params);

            $params = [
                'role_id' => $driver_role->role_id, 
                'feature_id' => $sa_delivery_updates_feature->feature_id, 
                'permission_id' => $sa_add_notes_permission->permission_id
            ];
            Privilege::where($params)->delete();
            Main::setCreatedModifiedVal(false, $params);
            $sa_privilege = Privilege::create($params);

            // --- 

            $params = [
                'role_id' => $driver_role->role_id,
                'type' => 'test driver',
                'full_name' => 'driver admin',
                'email' => 'driveradmin@admin.com',
                'password' => bcrypt('driveradmin123'),
            ];
            Main::setCreatedModifiedVal(false, $params);
            $driver_user = User::create($params); 

            // ----

            $params = [
                'code' => 'ORG001',
                'name' => 'test organization name',
            ];
            Main::setCreatedModifiedVal(false, $params);
            $ins_organization = Organization::create($params);

            $params = [
                'organization_id' => $ins_organization->organization_id,
                'code' => 'CLIENT001',
                'name' => 'test client name',
                'is_active' => '1',
            ];
            Main::setCreatedModifiedVal(false, $params);
            $ins_client = Client::create($params);

            $params = [
                'users_id' => $driver_user->users_id,
                'client_id' => $ins_client->client_id,
            ];
            Main::setCreatedModifiedVal(false, $params);
            $ins_userclient = UserClient::create($params);

            // ----

            $params = [
                'code' => 'CN',
                'name' => 'test country name',
                'is_active' => '1',
            ];
            Main::setCreatedModifiedVal(false, $params);
            $ins_country = Country::create($params); 
            
            $params = [
                'country_id' => $ins_country->country_id,
                'name' => 'test province name',
                'is_active' => '1',
            ];
            Main::setCreatedModifiedVal(false, $params);
            $ins_province = Province::create($params); 
            
            $params = [
                'province_id' => $ins_province->province_id,
                'name' => 'test city name',
                'is_active' => '1',
            ];
            Main::setCreatedModifiedVal(false, $params);
            $ins_city = City::create($params);
            
            $params = [
                'city_id' => $ins_city->city_id,
                'name' => 'test district name',
                'is_active' => '1',
            ];
            Main::setCreatedModifiedVal(false, $params);
            $ins_district = District::create($params);
            
            $params = [
                'district_id' => $ins_district->district_id,
                'name' => 'test subdistrict name',
                'is_active' => '1',
            ];
            Main::setCreatedModifiedVal(false, $params);
            $ins_subdistrict = Subdistrict::create($params);
            
            $params = [
                'name' => 'test subdistrict name',
            ];
            Main::setCreatedModifiedVal(false, $params);
            $ins_hubtype = HubType::create($params);
            
            $params = [
                'organization_id' => $ins_organization->organization_id,
                'hub_type_id' => $ins_hubtype->hub_type_id,
                'subdistrict_id' => $ins_subdistrict->subdistrict_id,
                'code' => 'HUB001',
                'name' => 'test hub name',
                'street_name' => 'test street name',
                'street_number' => '1001',
                'neighbourhood' => 'test neighbourhood name',
                'postcode' => '2001',
                'maps_url' => 'https://maps.app.goo.gl/EgrmgSsqdXU2v4cZA',
                'coordinate' => '-6.1627705,106.859259',
                'is_active' => '1',
            ];
            Main::setCreatedModifiedVal(false, $params);
            $ins_hub = Hub::create($params);
            
            $params = [
                'hub_id' => $ins_hub->hub_id,
                'city_id' => $ins_city->city_id,
            ];
            Main::setCreatedModifiedVal(false, $params);
            $ins_hubarea = HubArea::create($params); 
            
            $params = [
                'users_id' => $driver_user->users_id,
                'hub_id' => $ins_hub->hub_id,
            ];
            Main::setCreatedModifiedVal(false, $params);
            $ins_usershub = UserHub::create($params); 

            // ----
            
            $params = [
                'organization_id' => $ins_organization->organization_id,
                'code' => 'PARTNER001',
                'name' => 'test partner name',
                'package_cost' => 2500,
            ];
            Main::setCreatedModifiedVal(false, $params);
            $ins_partner = Partner::create($params); 
            
            $params = [
                'partner_id' => $ins_partner->partner_id,
                'code' => 'COURIER001',
                'phone' => '+62081211111110',
                'vehicle_type' => 'test vehicle_type name',
                'vehicle_number' => 'test partner name',
            ];
            Main::setCreatedModifiedVal(false, $params);
            $ins_courier = Courier::create($params); 
            
            $params = [
                'users_id' => $driver_user->users_id,
                'partner_id' => $ins_partner->partner_id,
            ];
            Main::setCreatedModifiedVal(false, $params);
            $ins_userpartner = UserPartner::create($params); 
            
        }
    }
}
