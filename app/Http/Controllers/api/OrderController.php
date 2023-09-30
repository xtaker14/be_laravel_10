<?php

namespace App\Http\Controllers\api;
 
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

use App\Models\User;
use App\Models\Status;
use App\Helpers\Main;
use App\Helpers\ResponseFormatter;

class OrderController extends Controller
{
    private $auth;

    public function __construct()
    {
        $this->auth = auth('api');
    }

    public function summaryDelivery(Request $request)
    { 
        // $user = $this->auth->user();
        $res = new ResponseFormatter;

        $CourierService = new \App\Services\CourierService('api');
        $RoutingService = new \App\Services\RoutingService('api');
        $PackageService = new \App\Services\PackageService('api');
        $courier = $CourierService->get($request);

        if ($courier['res'] == 'error'){
            return $res::error($courier['status_code'], $courier['msg'], $res::traceCode($courier['trace_code']));
        } 
        $courier = $courier['data'];

        $routing = $RoutingService->getInprogress($request, $courier, function ($q) {
            $today = Carbon::today();
            
            return $q->whereDate('created_date', $today)
                ->first();
        });
        if ($routing['res'] == 'error'){
            return $res::error($routing['status_code'], $routing['msg'], $res::traceCode($routing['trace_code']));
        } 
        $routing = $routing['data']; 

        $delivery_record = $routing->code;
        
        $summary = $PackageService->summary($request, $routing, function ($q) {
            // add more some filter
            // return $q->where();
            return $q;
        }); 
        if ($summary['res'] == 'error'){
            return $res::error($summary['status_code'], $summary['msg'], $res::traceCode($summary['trace_code']));
        } 
        $summary = $summary['data'];
        
        return $res::success(__('messages.success'), $summary);
    }

    public function latest(Request $request)
    { 
        // $user = $this->auth->user();
        $res = new ResponseFormatter;

        $CourierService = new \App\Services\CourierService('api');
        $RoutingService = new \App\Services\RoutingService('api');
        $PackageService = new \App\Services\PackageService('api');

        $courier = $CourierService->get($request); 
        if ($courier['res'] == 'error'){
            return $res::error($courier['status_code'], $courier['msg'], $res::traceCode($courier['trace_code']));
        } 
        $courier = $courier['data'];

        $routing = $RoutingService->getInprogress($request, $courier, function ($q) {
            $today = Carbon::today();

            return $q->whereDate('created_date', $today)
                ->first();
        });
        if ($routing['res'] == 'error'){
            return $res::error($routing['status_code'], $routing['msg'], $res::traceCode($routing['trace_code']));
        } 
        $routing = $routing['data']; 

        $delivery_record = $routing->code;

        $routing_history_latest = $routing->routinghistories->first(); 

        $routing_status = $routing_history_latest->status;
        $routing_status_code = $routing_status->code;
        $routing_status_name = $routing_status->name;
        
        $latest_order = $PackageService->getOndelivery($request, $routing, function ($q) {
            // add more some filter
            return $q->first();
        }); 
        if ($latest_order['res'] == 'error'){
            return $res::error($latest_order['status_code'], $latest_order['msg'], $res::traceCode($latest_order['trace_code']));
        } 
        $latest_order = $latest_order['data'];

        // return [
        //     'latest_order' => $latest_order,
        //     'routing' => $routing,
        // ];

        $package = $latest_order->package;
        $tracking_number = $package->tracking_number;

        $package_history_latest = $package->packagehistories->first();
        $package_status = $package_history_latest->status;
        $package_status_code = $package_status->code;
        $package_status_name = $package_status->name; 

        $is_cod = false;
        if($package->cod_price > 0){
            $is_cod = true;
        } 

        $res_data = [
            'delivery_record' => $delivery_record,
            'tracking_number' => $tracking_number,
            'name' => $package->recipient_name,

            'pickup_country' => $package->pickup_country,
            'pickup_province' => $package->pickup_province,
            'pickup_city' => $package->pickup_city,
            'pickup_address' => $package->pickup_address,
            'pickup_district' => $package->pickup_district,
            'pickup_subdistrict' => $package->pickup_subdistrict,

            'recipient_country' => $package->recipient_country,
            'recipient_province' => $package->recipient_province,
            'recipient_city' => $package->recipient_city,
            'recipient_address' => $package->recipient_address,
            'recipient_district' => $package->recipient_district,
            'recipient_postal_code' => $package->recipient_postal_code,

            'weight' => $package->total_weight,
            'koli' => $package->total_koli,
            'is_cod' => $is_cod,
            'status_code' => $package_status_code,
            'status_name' => $package_status_name,
            'date' => Carbon::now()->format('Y-m-d H:i:s'),
        ];
        return $res::success(__('messages.success'), $res_data);
    }
}
