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
use App\Models\Status;
use App\Models\Spot;
use App\Models\SpotArea;
use App\Models\Routing;
use App\Models\RoutingDetail;
use App\Models\RoutingHistory;
use App\Models\ServiceType;
use App\Models\Package;
use App\Models\PackageHistory;
use App\Models\Rates;
use App\Models\ClientRates;
use App\Models\Moving;
use App\Models\Grid;

class InitUserSeeder extends Seeder
{
    private function masterLocation()
    {
        $params = [
            'code' => 'ID',
            'name' => 'Indonesia',
            'is_active' => '1',
        ];
        Main::setCreatedModifiedVal(false, $params);
        $ins_country_indo = Country::create($params); 
        
        $params = [
            'country_id' => $ins_country_indo->country_id,
            'name' => 'DKI Jakarta',
            'is_active' => '1',
        ];
        Main::setCreatedModifiedVal(false, $params);
        $ins_province_dki_jakarta = Province::create($params); 
        
        $params = [
            'province_id' => $ins_province_dki_jakarta->province_id,
            'name' => 'Jakarta Pusat',
            'is_active' => '1',
        ];
        Main::setCreatedModifiedVal(false, $params);
        $ins_city_jakpus = City::create($params);
        
        $params = [
            'city_id' => $ins_city_jakpus->city_id,
            'name' => 'Kemayoran',
            'is_active' => '1',
        ];
        Main::setCreatedModifiedVal(false, $params);
        $ins_district_kmy = District::create($params);
        
        $params = [
            'district_id' => $ins_district_kmy->district_id,
            'name' => 'Serdang',
            'is_active' => '1',
        ];
        Main::setCreatedModifiedVal(false, $params);
        $ins_subdistrict_serdang = Subdistrict::create($params);
        
        $params = [
            'province_id' => $ins_province_dki_jakarta->province_id,
            'name' => 'Jakarta Timur',
            'is_active' => '1',
        ];
        Main::setCreatedModifiedVal(false, $params);
        $ins_city_jaktim = City::create($params);
        
        $params = [
            'city_id' => $ins_city_jaktim->city_id,
            'name' => 'Matraman',
            'is_active' => '1',
        ];
        Main::setCreatedModifiedVal(false, $params);
        $ins_district_matraman = District::create($params);
        
        $params = [
            'district_id' => $ins_district_matraman->district_id,
            'name' => 'Tegalan',
            'is_active' => '1',
        ];
        Main::setCreatedModifiedVal(false, $params);
        $ins_subdistrict_tegalan = Subdistrict::create($params);

        return [
            'ins_subdistrict_serdang' => $ins_subdistrict_serdang,
            'ins_city_jakpus' => $ins_city_jakpus,
            'ins_district_kmy' => $ins_district_kmy,
            'ins_city_jaktim' => $ins_city_jaktim,
        ];
    }

    private function masterHub($ins_organization_sicepat, $ins_subdistrict_serdang, $ins_city_jakpus, $ins_district_kmy)
    {
        $params = [
            'name' => 'test hubtype name',
        ];
        Main::setCreatedModifiedVal(false, $params);
        $ins_hubtype = HubType::create($params);
        
        $params = [
            'organization_id' => $ins_organization_sicepat->organization_id,
            'hub_type_id' => $ins_hubtype->hub_type_id,
            'subdistrict_id' => $ins_subdistrict_serdang->subdistrict_id,
            'code' => 'HUB001',
            'name' => 'Gading',
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
            'city_id' => $ins_city_jakpus->city_id,
        ];
        Main::setCreatedModifiedVal(false, $params);
        // lvl kota
        $ins_hubarea = HubArea::create($params); 

        $params = [
            'hub_id' => $ins_hub->hub_id,
            'code' => 'SPOT001',
            'name' => 'test spot name',
        ];
        Main::setCreatedModifiedVal(false, $params);
        $ins_spot = Spot::create($params); 

        $params = [
            'spot_id' => $ins_spot->spot_id,
            'district_id' => $ins_district_kmy->district_id,
        ];
        Main::setCreatedModifiedVal(false, $params);
        // lvl kecamatan
        $ins_spotarea = SpotArea::create($params); 

        return [
            'ins_hub' => $ins_hub,
            'ins_spot' => $ins_spot,
        ];
    }

    private function masterClient()
    {
        $params = [
            'code' => 'ORG001',
            'name' => 'Sicepat',
        ];
        Main::setCreatedModifiedVal(false, $params);
        $ins_organization_sicepat = Organization::create($params);

        $params = [
            'organization_id' => $ins_organization_sicepat->organization_id,
            'code' => 'CLIENT001',
            'name' => 'Fulfillment',
            'is_active' => '1',
        ];
        Main::setCreatedModifiedVal(false, $params);
        $ins_client_fulfillment = Client::create($params);

        return [
            'ins_organization_sicepat' => $ins_organization_sicepat,
            'ins_client_fulfillment' => $ins_client_fulfillment,
        ];
    }

    private function masterPermissionSA()
    {
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

        return [
            'sa_role' => $sa_role,
        ];
    }

    private function masterPermissionDriver()
    {
        $params = ['name' => 'driver'];
        // Role::where($params)->delete();
        Main::setCreatedModifiedVal(false, $params);
        $driver_role = Role::create($params);  

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

        return [
            'driver_role' => $driver_role,
        ];
    }

    private function masterPartner($ins_organization_sicepat)
    { 
        $params = [
            'organization_id' => $ins_organization_sicepat->organization_id,
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

        return [
            'ins_partner' => $ins_partner,
            'ins_courier' => $ins_courier,
        ];
    }

    private function masterServiceType($ins_organization_sicepat, $ins_city_jakpus, $ins_city_jaktim, $ins_client_fulfillment)
    {
        $params = [
            'organization_id' => $ins_organization_sicepat->organization_id,
            'code' => 'SERVTYPE001',
            'name' => 'test service type name',
            'minimum_weight' => '10',
            'maximum_weight' => '20',
            'description' => 'test description',
        ];
        Main::setCreatedModifiedVal(false, $params);
        $ins_servicetype = ServiceType::create($params); 

        $params = [
            'service_type_id' => $ins_servicetype->service_type_id,
            'origin_city_id' => $ins_city_jakpus->city_id,
            'destination_city_id' => $ins_city_jaktim->city_id,
            'is_cod' => 0,
            'publish_price' => '257000',
            'maximum_delivered' => '25',
        ];
        Main::setCreatedModifiedVal(false, $params);
        $ins_rates = Rates::create($params); 

        $params = [
            'client_id' => $ins_client_fulfillment->client_id,
            'rates_id' => $ins_rates->rates_id,
            'selling_price' => '1500',
        ];
        Main::setCreatedModifiedVal(false, $params);
        $ins_clientrates = ClientRates::create($params);

        return [
            'ins_servicetype' => $ins_servicetype,
        ];
    }

    private function masterStatusDelivery()
    {
        $params = [
            'code' => 'ONDELIVERY',
            'name' => 'On-Delivery',
            'color' => 'green',
            'is_active' => 1,
        ];
        Main::setCreatedModifiedVal(false, $params);
        $ins_status_ondelivery = Status::create($params); 
        
        $params = [
            'code' => 'DELIVERED',
            'name' => 'Delivered',
            'color' => 'green',
            'is_active' => 1,
        ];
        Main::setCreatedModifiedVal(false, $params);
        $ins_status_delivered = Status::create($params); 
        
        $params = [
            'code' => 'UNDELIVERED',
            'name' => 'Undelivered',
            'color' => 'green',
            'is_active' => 1,
        ];
        Main::setCreatedModifiedVal(false, $params);
        $ins_status_undelivered = Status::create($params); 

        return [
            'ins_status_ondelivery' => $ins_status_ondelivery,
            'ins_status_delivered' => $ins_status_delivered,
            'ins_status_undelivered' => $ins_status_undelivered,
        ];
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $superAdminRole = Role::create(['name' => 'super-admin', 'guard_name' => 'api']);

        // $allPermission = Permission::create(['name' => 'all', 'guard_name' => 'api']);

        // $superAdminRole->givePermissionTo($allPermission); 

        if (App::environment(['local', 'staging', 'development'])) {
            
            // --- super admin

            $master_permission_sa = $this->masterPermissionSA();
            $sa_role = $master_permission_sa['sa_role'];

            $params = [
                'role_id' => $sa_role->role_id,
                'gender' => 'P',
                'full_name' => 'super admin',
                'email' => 'superadmin@admin.com',
                'username' => 'superadmin',
                'password' => bcrypt('superadmin123'),
            ];
            Main::setCreatedModifiedVal(false, $params);
            $sa_user = User::create($params); 

            // --- driver / courier
            
            $master_permission_driver = $this->masterPermissionDriver();
            $driver_role = $master_permission_driver['driver_role']; 

            $params = [
                'role_id' => $driver_role->role_id,
                'gender' => 'L',
                'full_name' => 'driver admin',
                'email' => 'driveradmin@admin.com',
                'username' => 'driveradmin',
                'password' => bcrypt('driveradmin123'),
            ];
            Main::setCreatedModifiedVal(false, $params);
            $driver_user = User::create($params);  

            // --- assign data for driver / courier
            
            $master_status_delivery = $this->masterStatusDelivery(); 
            $ins_status_ondelivery = $master_status_delivery['ins_status_ondelivery'];
            $ins_status_delivered = $master_status_delivery['ins_status_delivered'];
            $ins_status_undelivered = $master_status_delivery['ins_status_undelivered'];

            $master_client = $this->masterClient(); 
            $ins_organization_sicepat = $master_client['ins_organization_sicepat'];
            $ins_client_fulfillment = $master_client['ins_client_fulfillment'];

            $params = [
                'users_id' => $driver_user->users_id,
                'client_id' => $ins_client_fulfillment->client_id,
            ];
            Main::setCreatedModifiedVal(false, $params);
            $ins_userclient = UserClient::create($params); 

            // ----

            $master_location = $this->masterLocation(); 
            $ins_subdistrict_serdang = $master_location['ins_subdistrict_serdang'];
            $ins_city_jakpus = $master_location['ins_city_jakpus'];
            $ins_district_kmy = $master_location['ins_district_kmy'];
            $ins_city_jaktim = $master_location['ins_city_jaktim'];
            
            $master_hub = $this->masterHub($ins_organization_sicepat, $ins_subdistrict_serdang, $ins_city_jakpus, $ins_district_kmy);
            $ins_hub = $master_hub['ins_hub'];
            $ins_spot = $master_hub['ins_spot'];
        
            $params = [
                'users_id' => $driver_user->users_id,
                'hub_id' => $ins_hub->hub_id,
            ];
            Main::setCreatedModifiedVal(false, $params);
            $ins_usershub = UserHub::create($params); 

            // ----

            $master_partner = $this->masterPartner($ins_organization_sicepat); 
            $ins_partner = $master_partner['ins_partner'];
            $ins_courier = $master_partner['ins_courier'];
            
            $params = [
                'users_id' => $driver_user->users_id,
                'partner_id' => $ins_partner->partner_id,
            ];
            Main::setCreatedModifiedVal(false, $params);
            $ins_userpartner = UserPartner::create($params); 
            
            // -------- routing

            $master_servicetype = $this->masterServiceType($ins_organization_sicepat, $ins_city_jakpus, $ins_city_jaktim, $ins_client_fulfillment); 
            $ins_servicetype = $master_servicetype['ins_servicetype']; 

            $params = [
                'hub_id' => $ins_hub->hub_id,
                'client_id' => $ins_client_fulfillment->client_id,
                'service_type_id' => $ins_servicetype->service_type_id,
                'tracking_number' => 123,
                'reference_number' => 456,
                'request_pickup_date' => '2023-09-26 05:28:44',
                'merchant_name' => 'test merchant_name',
                'pickup_name' => 'test pickup_name',
                'pickup_phone' => '+62081211111111',
                'pickup_email' => 'test@gmail.com',
                'pickup_address' => 'Jl. Test',
                'pickup_country' => 'Indonesia',
                'pickup_province' => 'DKI Jakarta', 
                'pickup_city' => 'Jakarta Pusat', 
                'pickup_district' => 'Kemayoran', 
                'pickup_subdistrict' => 'Serdang', 
                'pickup_postal_code' => '10560', 
                'pickup_notes' => 'test pickup_notes', 
                'pickup_coordinate' => '-6.1627705,106.859259', 
                'recipient_name' => 'test recipient_name', 
                'recipient_phone' => '+62081211111112', 
                'recipient_email' => 'test2@gmail.com', 
                'recipient_address' => 'Jl. Test2', 
                'recipient_country' => 'Indonesia', 
                'recipient_province' => 'DKI Jakarta', 
                'recipient_city' => 'Jakarta Timur', 
                'recipient_district' => 'Matraman', 
                'recipient_postal_code' => '10460', 
                'recipient_notes' => 'test recipient_notes', 
                'recipient_coordinate' => '-6.1627705,106.859259', 
                'package_price' => 7500, 
                'is_insurance' => 1, 
                'shipping_price' => 2500, 
                'cod_price' => 0, 
                'total_weight' => 15, 
                'total_koli' => 3, 
                'volumetric' => 'test volumetric', 
                'notes' => 'test notes', 
                'created_via' => 'mobile',
            ];
            Main::setCreatedModifiedVal(false, $params);
            $ins_package = Package::create($params); 

            $params = [
                'code' => 'STATUS002',
                'name' => 'test status package name',
                'color' => 'green',
                'is_active' => 1,
            ];
            Main::setCreatedModifiedVal(false, $params);
            $ins_status_package = Status::create($params); 

            $params = [
                'package_id' => $ins_package->package_id,
                'status_id' => $ins_status_package->status_id,
            ];
            Main::setCreatedModifiedVal(false, $params);
            $ins_packagehistory = PackageHistory::create($params);

            $params = [
                'hub_id' => $ins_hub->hub_id,
                'name' => 'test grid name',
            ];
            Main::setCreatedModifiedVal(false, $params);
            $ins_grid = Grid::create($params); 

            $params = [
                'package_id' => $ins_package->package_id,
                'grid_id' => $ins_grid->grid_id,
            ];
            Main::setCreatedModifiedVal(false, $params);
            $ins_moving = Moving::create($params); 

            $params = [
                'spot_id' => $ins_spot->spot_id,
                'courier_id' => $ins_courier->courier_id,
                // 'code' => 'ROUTING001',
                'code' => 'DR-JKT0010000234',
            ];
            Main::setCreatedModifiedVal(false, $params);
            $ins_routing = Routing::create($params); 

            $params = [
                'routing_id' => $ins_routing->routing_id,
                'package_id' => $ins_package->package_id,
            ];
            Main::setCreatedModifiedVal(false, $params);
            $ins_routingdetail = RoutingDetail::create($params); 

            $params = [
                'routing_id' => $ins_routing->routing_id,
                'status_id' => $ins_status_ondelivery->status_id,
            ];
            Main::setCreatedModifiedVal(false, $params);
            $ins_routinghistory = RoutingHistory::create($params); 
            
        }
    }
}
