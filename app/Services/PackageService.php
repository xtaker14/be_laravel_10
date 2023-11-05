<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

use App\Models\User;
use App\Models\Status;
use App\Models\Organization;
use App\Models\Client;
use App\Models\Package;
use App\Models\PackageApi; 
use App\Helpers\Main;

class PackageService
{
    private $auth;

    public function __construct($auth='api')
    { 
        if($auth){
            $this->auth = auth($auth);
        }
    } 

    public function get(Request $request, $routing, $add_filter=false)
    {
        $status_group = Status::STATUS_GROUP['package'];

        $query = $routing->routingdetails();
        
        if (is_callable($add_filter)) {
            $query = $add_filter($query);
            $package = $query;
        }else{
            $package = $query->first();
        }
        
        if(!$package){
            return [
                'res' => 'error',
                'status_code' => 404,
                'msg' => __('messages.not_found'),
                'trace_code' => 'EXCEPTION015',
            ];
        }

        return [
            'res' => 'success',
            'status_code' => 200,
            'msg' => __('messages.success'),
            'trace_code' => null,
            'data' => $package,
        ];
    }

    public function getOndelivery(Request $request, $routing, $add_filter=false)
    {
        $status_group = Status::STATUS_GROUP['package'];

        $query = $routing->routingdetails()
            // ->whereHas('package.packagehistories.status', function ($query) use ($status_group) {
            //     return $query->where('code', '=', Status::STATUS[$status_group]['ondelivery']);
            // })
            // ->whereDoesntHave('package.packagehistories.status', function ($query) use ($status_group) {
            //     return $query->whereIn('code', [
            //         Status::STATUS[$status_group]['delivered'], 
            //         Status::STATUS[$status_group]['undelivered'],
            //     ]);
            // })
            ->whereHas('package.status', function ($query) use ($status_group) {
                return $query->where('code', '=', Status::STATUS[$status_group]['ondelivery']);
            });
        
        if (is_callable($add_filter)) {
            $query = $add_filter($query);
            $package = $query;
        }else{
            $package = $query->first();
        }
        
        if(!$package){
            return [
                'res' => 'error',
                'status_code' => 404,
                'msg' => __('messages.not_found'),
                'trace_code' => 'EXCEPTION015',
            ];
        }

        return [
            'res' => 'success',
            'status_code' => 200,
            'msg' => __('messages.success'),
            'trace_code' => null,
            'data' => $package,
        ];
    }

    public function queryOrderByPositionNumber()
    {
        $q_package = Package::select([
            'p.position_number',
        ]);
        $sql_package = $q_package->toSql();

        $sql_package .= "
            AS p 
            WHERE p.package_id = routingdetail.package_id 
            ORDER BY p.position_number ASC
        ";

        return $sql_package;
    }
    
    public function getOrderByCompanyWMS($company_code, $company_name, $add_filter=false)
    {
        $status_group = Status::STATUS_GROUP['package']; 
        
        $companies = Organization::from(app(Organization::class)->getTable() . ' AS o')
            ->select([
                'c.client_id',
                'c.code AS client_code',
                'c.name AS client_name',
                'o.organization_id',
                'o.code AS organization_code',
                'o.name AS organization_name',
            ])
            ->join(app(Client::class)->getTable() . ' AS c', 'c.organization_id', '=', 'o.organization_id');
            
        if(!empty($company_code)) {
            $companies->where([
                'o.code' => $company_code,
            ]);
        }elseif(!empty($company_name)) {
            $companies->where([
                'o.name' => $company_name,
            ]);
        }else{
            return [
                'res' => 'error',
                'status_code' => 404,
                'msg' => 'Company ' . __('messages.not_found'),
                'trace_code' => 'EXCEPTION015',
            ];
        }

        $companies = $companies->get();

        $client_ids = [];
        foreach ($companies as $index => $val) {
            $client_ids[] = $val->client_id;
        }

        // $query = Package::leftJoin(app(PackageApi::class)->getTable() . ' AS pa', function ($join) {
        //         $join->on('pa.package_id', '=', 'package.package_id')
        //             ->where('pa.action', '=', PackageApi::ACTION_WMS['post_tracking']);
        //     })
        //     ->whereIn([
        //         'package.client_id' => $client_ids,
        //     ])
        //     ->where(function ($q) {
        //         $q->where('pa.status', PackageApi::PROCESSED)
        //             ->orWhere('pa.status', PackageApi::FAILED)
        //             ->orWhereNull('pa.status');
        //     });

        $query = Package::whereIn('client_id', $client_ids)
            ->whereHas('packageapies', function ($q) {
                $q->where('action', PackageApi::ACTION_WMS['post_tracking'])
                    ->whereIn('status', [
                        PackageApi::PROCESSED, 
                        PackageApi::FAILED,
                    ]);
            })
            ->orWhereDoesntHave('packageapies', function ($q) {
                $q->where('action', PackageApi::ACTION_WMS['post_tracking']);
            });

        if (is_callable($add_filter)) {
            $query = $add_filter($query);
            $package = $query;
        } else {
            $package = $query->get();
        }

        if (!$package) {
            return [
                'res' => 'error',
                'status_code' => 404,
                'msg' => 'Package ' . __('messages.not_found'),
                'trace_code' => 'EXCEPTION015',
            ];
        }

        return [
            'res' => 'success',
            'status_code' => 200,
            'msg' => __('messages.success'),
            'trace_code' => null,
            'data' => $package,
        ];
    }

    public function getStatusOrderByCompanyWMS($company_code, $company_name, $add_filter = false)
    {
        $status_group = Status::STATUS_GROUP['package'];

        $companies = Organization::from(app(Organization::class)->getTable() . ' AS o')
        ->select([
            'c.client_id',
            'c.code AS client_code',
            'c.name AS client_name',
            'o.organization_id',
            'o.code AS organization_code',
            'o.name AS organization_name',
        ])
            ->join(app(Client::class)->getTable() . ' AS c', 'c.organization_id', '=', 'o.organization_id');

        if (!empty($company_code)) {
            $companies->where([
                'o.code' => $company_code,
            ]);
        } elseif (!empty($company_name)) {
            $companies->where([
                'o.name' => $company_name,
            ]);
        } else {
            return [
                'res' => 'error',
                'status_code' => 404,
                'msg' => 'Company ' . __('messages.not_found'),
                'trace_code' => 'EXCEPTION015',
            ];
        }

        $companies = $companies->get();

        $client_ids = [];
        foreach ($companies as $index => $val) {
            $client_ids[] = $val->client_id;
        } 

        $query = Package::whereIn('client_id', $client_ids)
            ->whereHas('packageapies', function ($q) {
                $q->where('action', PackageApi::ACTION_WMS['post_tracking'])
                    ->whereIn('status', [
                        PackageApi::PROCESSED, 
                        PackageApi::FAILED,
                        PackageApi::COMPLETED,
                    ]);
            });

        if (is_callable($add_filter)) {
            $query = $add_filter($query);
            $package = $query;
        } else {
            $package = $query->get();
        }

        if (!$package) {
            return [
                'res' => 'error',
                'status_code' => 404,
                'msg' => 'Package ' . __('messages.not_found'),
                'trace_code' => 'EXCEPTION015',
            ];
        }

        return [
            'res' => 'success',
            'status_code' => 200,
            'msg' => __('messages.success'),
            'trace_code' => null,
            'data' => $package,
        ];
    }

    public function postOrderToWMS($package)
    { 
        $api_url = env('URL_WMS') . '/index.php?r=Externalv2/Order/PostTrackingNumber';
        $api_params = [
            'order_code' => $package['reference_number'],
            'Tracking_number' => $package['tracking_number'],
        ];
        $api_post_tracking = Main::API('post', $api_url, $api_params, [
            'log_type' => 'order-to-wms',
        ]);

        if($api_post_tracking['status_code'] !== 200){
            return [
                'res' => 'error',
                'status_code' => $api_post_tracking['status_code'],
                'msg' => $api_post_tracking['message'],
                'trace_code' => 'EXCEPTION014',
            ];
        }
        $api_post_tracking = $api_post_tracking['data'];

        return [
            'res' => 'success',
            'status_code' => 200,
            'msg' => __('messages.success'),
            'trace_code' => null,
            'data' => $api_post_tracking,
        ];
    }

}
