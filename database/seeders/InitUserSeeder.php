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
use App\Models\PackageDelivery;
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
        
        // ------- Kemayoran

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
        
        // ------- Tanah Abang
        
        $params = [
            'city_id' => $ins_city_jakpus->city_id,
            'name' => 'Tanah Abang',
            'is_active' => '1',
        ];
        Main::setCreatedModifiedVal(false, $params);
        $ins_district_tanahabang = District::create($params);
        
        $params = [
            'district_id' => $ins_district_tanahabang->district_id,
            'name' => 'Bendungan Hilir',
            'is_active' => '1',
        ];
        Main::setCreatedModifiedVal(false, $params);
        $ins_subdistrict_bendungan = Subdistrict::create($params);

        // -------
        
        $params = [
            'province_id' => $ins_province_dki_jakarta->province_id,
            'name' => 'Jakarta Timur',
            'is_active' => '1',
        ];
        Main::setCreatedModifiedVal(false, $params);
        $ins_city_jaktim = City::create($params);

        // ------- Matraman
        
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
        
        // ------- Cakung
        
        $params = [
            'city_id' => $ins_city_jaktim->city_id,
            'name' => 'Cakung',
            'is_active' => '1',
        ];
        Main::setCreatedModifiedVal(false, $params);
        $ins_district_cakung = District::create($params);
        
        $params = [
            'district_id' => $ins_district_cakung->district_id,
            'name' => 'Cakung Barat',
            'is_active' => '1',
        ];
        Main::setCreatedModifiedVal(false, $params);
        $ins_subdistrict_cakungbarat = Subdistrict::create($params);

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

    private function masterPermissionDriver()
    {
        $params = ['name' => 'COURIER'];
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
            'organization_id' => $ins_organization_sicepat->organization_id,
            'code' => 'PARTNER002',
            'name' => 'test partner 2 name',
            'package_cost' => 2500,
        ];
        Main::setCreatedModifiedVal(false, $params);
        $ins_partner2 = Partner::create($params);  

        return [
            'ins_partner' => $ins_partner, 
        ];
    }

    private function masterServiceType($ins_organization_sicepat, $ins_city_jakpus, $ins_city_jaktim, $ins_client_fulfillment)
    {
        $params = [
            'organization_id' => $ins_organization_sicepat->organization_id,
            'code' => 'SERVTYPE001',
            'name' => 'REGULAR',
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

    private function masterStatus()
    {
        /*** 
        - ENTRY : 
            Paket/resi yang baru dibuat melalui import request waybill maupun API dari WMS
        - REJECTED : 
            Paket/resi yang baru dibuat belum diproses (oleh inbound/belum RECEIVED) namun dibatalkan dengan alasan customer cancel order, stok tidak dapat di fulilled dan lain-lain
        - RECEIVED : 
            Paket/resi sudah diproses dan di handover dari tim fulfillment dan diterima oleh tim inbound transport
        - TRANSFER : 
            Paket/resi yang sedang dalam proses transfer ke hub lain/hub destination (karena diluar coverage area hub origin)
        - IN TRANSIT : 
            Paket/resi yang ditransfer dan telah sampai ke hub lain/hub destination (sudah diproses inbound oleh tim inbound hub tersebut)
        - ROUTING : 
            Paket/resi yang telah digruping dan dibuat Delivery Record, menunggu kurir pickup
        -------------------------------------------------------------------------------------
        - ON DELIVERY : 
            Paket/resi telah dipickup oleh kurir dan sedang dalam proses pengiriman
        - UNDELIVERED : 
            Paket/resi yang sudah diproses pengiriman namun gagal terkirim
        - DELIVERED : 
            Paket/resi yang sudah diproses pengiriman dan telah diterima oleh customer
        - RETURN : 
            Paket/resi yang UNDELIVERED di bawa oleh kurir dan dikembalikan ke gudang karena pesanan ditolak (tidak bisa delivered) 
        ***/

        $status_group = [
            'package' => Status::STATUS_GROUP['package'],
            'routing' => Status::STATUS_GROUP['routing'],
        ];
        
        $params = [
            'code' => Status::STATUS[$status_group['package']]['entry'],
            'status_order' => 1,
            'status_group' => $status_group['package'],
            'name' => 'Entry',
            'color' => 'green',
            'is_active' => 1,
        ];
        Main::setCreatedModifiedVal(false, $params);
        $ins_status_entry = Status::create($params); 
        
        $params = [
            'code' => Status::STATUS[$status_group['package']]['routing'],
            'status_order' => 1,
            'status_group' => $status_group['package'],
            'name' => 'Routing',
            'color' => 'green',
            'is_active' => 1,
        ];
        Main::setCreatedModifiedVal(false, $params);
        $ins_status_routing = Status::create($params); 

        $params = [
            'code' => Status::STATUS[$status_group['package']]['ondelivery'],
            'status_order' => 1,
            'status_group' => $status_group['package'],
            'name' => 'On-Delivery',
            'color' => 'green',
            'is_active' => 1,
        ];
        Main::setCreatedModifiedVal(false, $params);
        $ins_status_ondelivery = Status::create($params); 
        
        $params = [
            'code' => Status::STATUS[$status_group['package']]['delivered'],
            'status_order' => 2,
            'status_group' => $status_group['package'],
            'name' => 'Delivered',
            'color' => 'green',
            'is_active' => 1,
        ];
        Main::setCreatedModifiedVal(false, $params);
        $ins_status_delivered = Status::create($params); 
        
        $params = [
            'code' => Status::STATUS[$status_group['package']]['undelivered'],
            'status_order' => 3,
            'status_group' => $status_group['package'],
            'name' => 'Undelivered',
            'color' => 'green',
            'is_active' => 1,
        ];
        Main::setCreatedModifiedVal(false, $params);
        $ins_status_undelivered = Status::create($params);
        
        $params = [
            'code' => Status::STATUS[$status_group['routing']]['assigned'],
            'status_order' => 1,
            'status_group' => $status_group['routing'],
            'name' => 'Assigned',
            'color' => 'green',
            'is_active' => 1,
        ];
        Main::setCreatedModifiedVal(false, $params);
        $ins_status_assigned = Status::create($params); 
        
        $params = [
            'code' => Status::STATUS[$status_group['routing']]['inprogress'],
            'status_order' => 1,
            'status_group' => $status_group['routing'],
            'name' => 'Inprogress',
            'color' => 'green',
            'is_active' => 1,
        ];
        Main::setCreatedModifiedVal(false, $params);
        $ins_status_inprogress = Status::create($params); 
        
        $params = [
            'code' => Status::STATUS[$status_group['routing']]['collected'],
            'status_order' => 1,
            'status_group' => $status_group['routing'],
            'name' => 'Collected',
            'color' => 'green',
            'is_active' => 1,
        ];
        Main::setCreatedModifiedVal(false, $params);
        $ins_status_collected = Status::create($params); 

        return [
            'ins_status_entry' => $ins_status_entry,
            'ins_status_routing' => $ins_status_routing,
            'ins_status_ondelivery' => $ins_status_ondelivery,
            'ins_status_delivered' => $ins_status_delivered,
            'ins_status_undelivered' => $ins_status_undelivered, 
            'ins_status_assigned' => $ins_status_assigned,
            'ins_status_inprogress' => $ins_status_inprogress,
            'ins_status_collected' => $ins_status_collected,
        ];
    } 

    private function transactionPackage($ins_hub, $ins_client_fulfillment, $ins_servicetype, $ins_status_delivered, $ins_status_ondelivery, $ins_status_undelivered)
    {
        $params_package = [
            'hub_id' => $ins_hub->hub_id,
            'client_id' => $ins_client_fulfillment->client_id,
            'service_type_id' => $ins_servicetype->service_type_id,
            'status_id' => $ins_status_delivered->status_id,
            'position_number' => 1,
            'tracking_number' => 'TRACKING_NUMBER_001',
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

            'recipient_name' => 'test recipient_name 1', 
            'recipient_phone' => '+62081211111111', 
            'recipient_email' => 'test1@gmail.com', 
            'recipient_address' => 'Jl. Test1', 
            'recipient_country' => 'Indonesia', 
            'recipient_province' => 'DKI Jakarta', 
            'recipient_city' => 'Jakarta Pusat', 
            'recipient_district' => 'Tanah Abang', 
            'recipient_postal_code' => '10461', 
            'recipient_notes' => 'test recipient_notes 1', 
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
        Main::setCreatedModifiedVal(false, $params_package);
        $ins_package_tanahabang = Package::create($params_package); 

        $params = [
            'package_id' => $ins_package_tanahabang->package_id,
            'status_id' => $ins_status_ondelivery->status_id,
        ];
        Main::setCreatedModifiedVal(false, $params);
        $ins_packagehistory_tanahabang = PackageHistory::create($params); // status ondelivery

        $params = [
            'package_id' => $ins_package_tanahabang->package_id,
            'status_id' => $ins_status_delivered->status_id,
        ];
        Main::setCreatedModifiedVal(false, $params);
        $ins_packagehistory_tanahabang = PackageHistory::create($params); // status delivered

        $params = [
            'package_id' => $ins_package_tanahabang->package_id,
            'package_history_id' => $ins_packagehistory_tanahabang->package_history_id,
            'information' => 'Keluarga/Orang Lain',
            'notes' => 'Eko',
            'accept_cod' => 'yes',
            'e_signature' => 'https://w7.pngwing.com/pngs/826/960/png-transparent-signature-digital-signature-signature-miscellaneous-angle-white.png',
            'photo' => 'https://media.hitekno.com/thumbs/2020/06/25/37729-ilustrasi-paket-pixabaydevanath/730x480-img-37729-ilustrasi-paket-pixabaydevanath.jpg',
        ];
        Main::setCreatedModifiedVal(false, $params);
        $ins_packagedelivery_tanahabang = PackageDelivery::create($params);
        
        $params_package['status_id'] = $ins_status_undelivered->status_id;
        $params_package['position_number'] = 2;
        $params_package['package_price'] = 0;
        $params_package['shipping_price'] = 4000;
        $params_package['cod_price'] = 9000;
        $params_package['total_weight'] = 20;
        $params_package['total_koli'] = 2;
        $params_package['tracking_number'] = 'TRACKING_NUMBER_002';
        $params_package['recipient_name'] = 'test recipient_name 2';
        $params_package['recipient_phone'] = '+62081211111112';
        $params_package['recipient_email'] = 'test2@gmail.com';
        $params_package['recipient_address'] = 'Jl. Test2';
        $params_package['recipient_country'] = 'Indonesia';
        $params_package['recipient_province'] = 'DKI Jakarta';
        $params_package['recipient_city'] = 'Jakarta Timur';
        $params_package['recipient_district'] = 'Matraman';
        $params_package['recipient_postal_code'] = '10462';
        $params_package['recipient_notes'] = 'test recipient_notes 2';
        $params_package['recipient_coordinate'] = '-6.1627705,106.859259';
        Main::setCreatedModifiedVal(false, $params_package);
        $ins_package_matraman = Package::create($params_package); 

        $params = [
            'package_id' => $ins_package_matraman->package_id,
            'status_id' => $ins_status_ondelivery->status_id,
        ];
        Main::setCreatedModifiedVal(false, $params);
        $ins_packagehistory_matraman = PackageHistory::create($params); // status ondelivery

        $params = [
            'package_id' => $ins_package_matraman->package_id,
            'status_id' => $ins_status_undelivered->status_id,
        ];
        Main::setCreatedModifiedVal(false, $params);
        $ins_packagehistory_matraman = PackageHistory::create($params); // status undelivered

        $params = [
            'package_id' => $ins_package_matraman->package_id,
            'package_history_id' => $ins_packagehistory_matraman->package_history_id,
            'information' => 'Rumah kosong/tidak ada orang',
            'notes' => 'Eko',
            'accept_cod' => 'no',
            'e_signature' => 'https://w7.pngwing.com/pngs/826/960/png-transparent-signature-digital-signature-signature-miscellaneous-angle-white.png',
            'photo' => 'https://media.hitekno.com/thumbs/2020/06/25/37729-ilustrasi-paket-pixabaydevanath/730x480-img-37729-ilustrasi-paket-pixabaydevanath.jpg',
        ];
        Main::setCreatedModifiedVal(false, $params);
        $ins_packagedelivery_tanahabang = PackageDelivery::create($params);
        
        $params_package['status_id'] = $ins_status_ondelivery->status_id;
        $params_package['position_number'] = 3;
        $params_package['package_price'] = 11500;
        $params_package['shipping_price'] = 4000;
        $params_package['cod_price'] = 0;
        $params_package['total_weight'] = 6;
        $params_package['total_koli'] = 1;
        $params_package['tracking_number'] = 'TRACKING_NUMBER_003';
        $params_package['recipient_name'] = 'test recipient_name 3';
        $params_package['recipient_phone'] = '+62081211111113';
        $params_package['recipient_email'] = 'test3@gmail.com';
        $params_package['recipient_address'] = 'Jl. Test3';
        $params_package['recipient_country'] = 'Indonesia';
        $params_package['recipient_province'] = 'DKI Jakarta';
        $params_package['recipient_city'] = 'Jakarta Timur';
        $params_package['recipient_district'] = 'Cakung';
        $params_package['recipient_postal_code'] = '10463';
        $params_package['recipient_notes'] = 'test recipient_notes 3';
        $params_package['recipient_coordinate'] = '-6.1627705,106.859259';
        Main::setCreatedModifiedVal(false, $params_package);
        $ins_package_cakung = Package::create($params_package); 

        $params = [
            'package_id' => $ins_package_cakung->package_id,
            'status_id' => $ins_status_ondelivery->status_id,
        ];
        Main::setCreatedModifiedVal(false, $params);
        $ins_packagehistory_cakung = PackageHistory::create($params); // status ondelivery

        $params = [
            'hub_id' => $ins_hub->hub_id,
            'name' => 'test grid name',
        ];
        Main::setCreatedModifiedVal(false, $params);
        $ins_grid = Grid::create($params); 

        $params = [
            'package_id' => $ins_package_tanahabang->package_id,
            'grid_id' => $ins_grid->grid_id,
        ];
        Main::setCreatedModifiedVal(false, $params);
        $ins_moving_tanahabang = Moving::create($params); 

        $params = [
            'package_id' => $ins_package_matraman->package_id,
            'grid_id' => $ins_grid->grid_id,
        ];
        Main::setCreatedModifiedVal(false, $params);
        $ins_moving_matraman = Moving::create($params); 

        $params = [
            'package_id' => $ins_package_cakung->package_id,
            'grid_id' => $ins_grid->grid_id,
        ];
        Main::setCreatedModifiedVal(false, $params);
        $ins_moving_cakung = Moving::create($params); 

        return [
            'ins_package_tanahabang' => $ins_package_tanahabang,
            'ins_package_matraman' => $ins_package_matraman,
            'ins_package_cakung' => $ins_package_cakung,
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
            
            // --- status
            
            $master_status = $this->masterStatus(); 
            $ins_status_routing = $master_status['ins_status_routing'];
            $ins_status_ondelivery = $master_status['ins_status_ondelivery'];
            $ins_status_delivered = $master_status['ins_status_delivered'];
            $ins_status_undelivered = $master_status['ins_status_undelivered']; 
            $ins_status_assigned = $master_status['ins_status_assigned'];
            $ins_status_inprogress = $master_status['ins_status_inprogress'];
            $ins_status_collected = $master_status['ins_status_collected'];

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

            // --- super admin

            // --- inactive user
            $params = [
                'role_id' => $sa_role->role_id,
                'gender' => 'P',
                'full_name' => 'inactiveuser',
                'email' => 'inactive@admin.com',
                'is_active' => 0,
                'username' => 'inactiveuser',
                'password' => bcrypt('inactive123'),
            ];
            Main::setCreatedModifiedVal(false, $params);
            $sa_user2 = User::create($params); 

            // --- driver / courier
            
            $master_permission_driver = $this->masterPermissionDriver();
            $driver_role = $master_permission_driver['driver_role']; 

            $params = [
                'role_id' => $driver_role->role_id,
                'gender' => 'L',
                'full_name' => 'user driver',
                'email' => 'testdriver@test.com',
                'username' => 'testdriver',
                'password' => bcrypt('testdriver123'),
            ];
            Main::setCreatedModifiedVal(false, $params);
            $driver_user = User::create($params);  

            $params = [
                'role_id' => $driver_role->role_id,
                'gender' => 'P',
                'full_name' => 'user driver 2',
                'email' => 'testdriver2@test.com',
                'username' => 'testdriver2',
                'password' => bcrypt('testdriver123'),
            ];
            Main::setCreatedModifiedVal(false, $params);
            $driver_user_2 = User::create($params);  

            // --- assign data for driver / courier

            $master_client = $this->masterClient(); 
            $ins_organization_sicepat = $master_client['ins_organization_sicepat'];
            $ins_client_fulfillment = $master_client['ins_client_fulfillment'];

            $params = [
                'users_id' => $sa_user->users_id,
                'client_id' => $ins_client_fulfillment->client_id,
            ];
            Main::setCreatedModifiedVal(false, $params);
            $ins_userclient = UserClient::create($params); 

            $params = [
                'users_id' => $sa_user2->users_id,
                'client_id' => $ins_client_fulfillment->client_id,
            ];
            Main::setCreatedModifiedVal(false, $params);
            $ins_userclient = UserClient::create($params);

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


            $params = [
                'users_id' => $sa_user->users_id,
                'hub_id' => $ins_hub->hub_id,
            ];
            Main::setCreatedModifiedVal(false, $params);
            $ins_usershub = UserHub::create($params); 

            // ----

            $master_partner = $this->masterPartner($ins_organization_sicepat); 
            $ins_partner = $master_partner['ins_partner'];

            $params = [
                'users_id' => $driver_user->users_id,
                'partner_id' => $ins_partner->partner_id,
            ];
            Main::setCreatedModifiedVal(false, $params);
            $ins_userpartner = UserPartner::create($params); 

            $params = [
                'partner_id' => $ins_partner->partner_id,
                'hub_id' => $ins_hub->hub_id,
                'users_partner_id' => $ins_userpartner->users_partner_id,
                'code' => 'COURIER001',
                'phone' => '+62081211111110',
                'name' => 'Courier ' . rand(10, 99),
                'vehicle_type' => 'test vehicle_type name',
                'vehicle_number' => 'test partner name',
            ];
            Main::setCreatedModifiedVal(false, $params);
            $ins_courier = Courier::create($params);
            
            // -------- routing

            $master_servicetype = $this->masterServiceType($ins_organization_sicepat, $ins_city_jakpus, $ins_city_jaktim, $ins_client_fulfillment); 
            $ins_servicetype = $master_servicetype['ins_servicetype']; 

            $transaction_package = $this->transactionPackage($ins_hub, $ins_client_fulfillment, $ins_servicetype, $ins_status_delivered, $ins_status_ondelivery, $ins_status_undelivered);
            $ins_package_tanahabang = $transaction_package['ins_package_tanahabang']; 
            $ins_package_matraman = $transaction_package['ins_package_matraman']; 
            $ins_package_cakung = $transaction_package['ins_package_cakung']; 

            $params = [
                'spot_id' => $ins_spot->spot_id,
                'courier_id' => $ins_courier->courier_id,
                // 'status_id' => $ins_status_inprogress->status_id,
                'status_id' => $ins_status_assigned->status_id,
                'code' => 'DR-JKT0010000234',
            ];
            Main::setCreatedModifiedVal(false, $params);
            // $ins_routing_inprogress = Routing::create($params); 
            $ins_routing_assigned = Routing::create($params); 

            $params = [
                // 'routing_id' => $ins_routing_inprogress->routing_id,
                'routing_id' => $ins_routing_assigned->routing_id,
                'package_id' => $ins_package_tanahabang->package_id,
            ];
            Main::setCreatedModifiedVal(false, $params);
            $ins_routingdetail_tanahabang = RoutingDetail::create($params); 

            $params = [
                // 'routing_id' => $ins_routing_inprogress->routing_id,
                'routing_id' => $ins_routing_assigned->routing_id,
                'package_id' => $ins_package_matraman->package_id,
            ];
            Main::setCreatedModifiedVal(false, $params);
            $ins_routingdetail_matraman = RoutingDetail::create($params); 

            $params = [
                // 'routing_id' => $ins_routing_inprogress->routing_id,
                'routing_id' => $ins_routing_assigned->routing_id,
                'package_id' => $ins_package_cakung->package_id,
            ];
            Main::setCreatedModifiedVal(false, $params);
            $ins_routingdetail_cakung = RoutingDetail::create($params); 

            $params = [
                // 'routing_id' => $ins_routing_inprogress->routing_id,
                'routing_id' => $ins_routing_assigned->routing_id,
                'status_id' => $ins_status_inprogress->status_id,
            ];
            Main::setCreatedModifiedVal(false, $params);
            $ins_routinghistory = RoutingHistory::create($params); 
            
        }
    }
}