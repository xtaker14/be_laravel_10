<?php

namespace App\Http\Controllers\api;
 
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;
use Exception;

use App\Models\User;
use App\Models\Package;
use App\Models\PackageHistory;
use App\Models\PackageDelivery;
use App\Models\Status;
use App\Helpers\Main;
use App\Helpers\ResponseFormatter;
use App\Models\Routing;
use App\Jobs\PushOrderToWMS;
use App\Models\PackageApi;

class OrderWmsController extends Controller
{ 
    public function postTrackingNumber(Request $request)
    {
        $validator_msg = [
            'string' => __('messages.validator_string'),
            'integer' => __('messages.validator_integer'),
            'required' => __('messages.validator_required'),
            'min' => __('messages.validator_min'),
            'max' => __('messages.validator_max'),
        ];

        $status_group = [
            'routing' => Status::STATUS_GROUP['routing'],
            'package' => Status::STATUS_GROUP['package'],
        ];

        $validator = Main::validator($request, [
            'rules' => [
                'company_code' => 'sometimes|string|min:3|max:30',
                'company_name' => 'sometimes|string|min:3|max:30', 
                'page' => 'sometimes|integer|min:1',
                'per_page' => 'sometimes|integer|min:1',
            ],
            'messages' => $validator_msg,
        ]);
        
        if (!empty($validator)){
            return $validator;
        } 

        $res = new ResponseFormatter;

        $PackageService = new \App\Services\PackageService(false); 
        
        $page = $request->input('page');
        $per_page = $request->input('per_page', 10);

        $company_code = $request->company_code;
        $company_name = $request->company_name;

        $order_list = $PackageService->getOrderByCompanyWMS($company_code, $company_name, function ($q) use ($request, $status_group, $page, $per_page) { 
            $res_order_list = $q
                ->where(function ($q2) use ($request, $status_group) { 
                    $q2->whereHas('status', function ($q3) use ($request, $status_group) {
                        return $q3->whereIn('code', [
                            Status::STATUS[$status_group['package']]['entry'], 
                        ]);
                    });
                });

            if(!empty($page)){
                return $res_order_list
                    ->paginate($per_page, ['*'], 'page_order_wms_list', $page)
                    ->withQueryString();
            }else{
                return $res_order_list
                    ->paginate($per_page)
                    ->withQueryString();
            } 
        });

        if ($order_list['res'] == 'error'){
            return $res::error($order_list['status_code'], $order_list['msg'], $res::traceCode($order_list['trace_code']));
        } 
        $order_list = $order_list['data'];  

        $res_data = [
            'processed' => [],
            'pagination' => [
                'total' => $order_list->total(),
                'current_page' => $order_list->currentPage(),
                'last_page' => $order_list->lastPage(),
                'per_page' => $order_list->perPage(),
                'next_page_url' => $order_list->nextPageUrl(),
                'prev_page_url' => $order_list->previousPageUrl(),
            ]
        ];

        foreach($order_list as $idx => $val){
            $package = $val;
            $package_status = $package->status;
            $package_status_code = $package_status->code;
            $package_status_name = $package_status->name; 

            $is_cod = false;
            if($package->cod_price > 0){
                $is_cod = true;
            } 

            PushOrderToWMS::dispatch($package);
            $res_data['processed'][] = [
                'tracking_number' => $package->tracking_number,
                'reference_number' => $package->reference_number,
                'name' => $package->recipient_name,
                'is_cod' => $is_cod,
                'status_code' => $package_status_code,
                'status_name' => $package_status_name,
                'date' => $package->created_date,
            ];
        }

        return $res::success(__('messages.success'), $res_data);
    }

    public function statusTrackingNumber(Request $request)
    {
        $validator_msg = [
            'string' => __('messages.validator_string'),
            'integer' => __('messages.validator_integer'),
            'required' => __('messages.validator_required'),
            'min' => __('messages.validator_min'),
            'max' => __('messages.validator_max'),
        ];

        $status_group = [
            'routing' => Status::STATUS_GROUP['routing'],
            'package' => Status::STATUS_GROUP['package'],
        ];

        $validator = Main::validator($request, [
            'rules' => [
                'company_code' => 'sometimes|string|min:3|max:30',
                'company_name' => 'sometimes|string|min:3|max:30',
                'page' => 'sometimes|integer|min:1',
                'per_page' => 'sometimes|integer|min:1',
            ],
            'messages' => $validator_msg,
        ]);

        if (!empty($validator)) {
            return $validator;
        }

        $res = new ResponseFormatter;

        $PackageService = new \App\Services\PackageService(false);

        $page = $request->input('page');
        $per_page = $request->input('per_page', 10);

        $company_code = $request->company_code;
        $company_name = $request->company_name;

        $order_list = $PackageService->getStatusOrderByCompanyWMS($company_code, $company_name, function ($q) use ($request, $status_group, $page, $per_page) {
            $res_order_list = $q;

            if (!empty($page)) {
                return $res_order_list
                    ->paginate($per_page, ['*'], 'page_order_wms_list', $page)
                    ->withQueryString();
            } else {
                return $res_order_list
                    ->paginate($per_page)
                    ->withQueryString();
            }
        });

        if ($order_list['res'] == 'error') {
            return $res::error($order_list['status_code'], $order_list['msg'], $res::traceCode($order_list['trace_code']));
        }
        $order_list = $order_list['data'];

        $res_data = [
            'list' => [],
            'pagination' => [
                'total' => $order_list->total(),
                'current_page' => $order_list->currentPage(),
                'last_page' => $order_list->lastPage(),
                'per_page' => $order_list->perPage(),
                'next_page_url' => $order_list->nextPageUrl(),
                'prev_page_url' => $order_list->previousPageUrl(),
            ]
        ];

        foreach ($order_list as $idx => $val) {
            $package = $val;
            $package_api = $val->packageapies->first();
            $package_status = $package->status;
            $package_status_code = $package_status->code;
            $package_status_name = $package_status->name;
            $package_api_action = $package_api->action;
            $package_api_message = $package_api->message;
            $package_api_status = '';

            if($package_api->status == PackageApi::PROCESSED){
                $package_api_status = 'PROCESSED';
            }
            if($package_api->status == PackageApi::FAILED){
                $package_api_status = 'FAILED';
            }
            if($package_api->status == PackageApi::COMPLETED){
                $package_api_status = 'COMPLETED';
            }

            $is_cod = false;
            if ($package->cod_price > 0) {
                $is_cod = true;
            }

            PushOrderToWMS::dispatch($package);
            $res_data['list'][] = [
                'status_api' => [
                    'action' => $package_api_action,
                    'status' => $package_api_status,
                    'message' => $package_api_message,
                ],
                'tracking_number' => $package->tracking_number,
                'reference_number' => $package->reference_number,
                'name' => $package->recipient_name,
                'is_cod' => $is_cod,
                'status_code' => $package_status_code,
                'status_name' => $package_status_name,
                'date' => $package->created_date,
            ];
        }

        return $res::success(__('messages.success'), $res_data);
    } 
}
