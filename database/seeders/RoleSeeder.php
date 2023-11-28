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
        $features = ['dashboard','request-waybill','waybill-list','adjustment','inbound','transfer','delivery-record','cod-collection','report-inbound','report-delivery-order','report-transfer','report-delivery-record','report-cod-collection','master-organization','master-hub','master-vendor','master-courier','master-region','user-access'];

        $roles = ['DEVELOPMENT','COURIER','INBOUND_TEAM','ROUTING_TEAM','DELIVERY_TEAM','STORE_OPS','COLLECTION_TEAM','CONTROL_TOWER','ADMINISTRATOR','MANAGEMENT','OPEN_API'];

        $privilege['DEVELOPMENT'] = ['dashboard','request-waybill','waybill-list','adjustment','inbound','transfer','delivery-record','cod-collection','report-inbound','report-delivery-order','report-transfer','report-delivery-record','report-cod-collection','master-organization','master-hub','master-vendor','master-courier','master-region','user-access'];

        $privilege['INBOUND_TEAM'] = ['dashboard','waybill-list','inbound','report-inbound','master-region'];

        $privilege['ROUTING_TEAM'] = ['dashboard','waybill-list','transfer','delivery-record','report-delivery-order','report-transfer','report-delivery-record','master-region'];

        $privilege['DELIVERY_TEAM'] = ['dashboard','waybill-list','transfer','delivery-record','report-delivery-order','report-transfer','report-delivery-record','master-region'];

        $privilege['STORE_OPS'] = ['dashboard','request-waybill','waybill-list','report-delivery-record','master-region'];

        $privilege['CONTROL_TOWER'] = ['dashboard','request-waybill','waybill-list','adjustment','inbound','transfer','delivery-record','cod-collection','report-inbound','report-delivery-order','report-transfer','report-delivery-record','report-cod-collection','master-organization','master-hub','master-vendor','master-courier','master-region','user-access'];

        $privilege['ADMINISTRATOR'] = ['dashboard','waybill-list','report-inbound','report-delivery-order','report-transfer','report-delivery-record','report-cod-collection','master-organization','master-hub','master-vendor','master-courier','master-region','user-access'];

        $privilege['MANAGEMENT'] = ['dashboard','waybill-list','adjustment','report-inbound','report-delivery-order','report-transfer','report-delivery-record','report-cod-collection','master-organization','master-hub','master-vendor','master-courier','master-region','user-access'];

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
