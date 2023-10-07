<?php

namespace App\Http\Controllers\api;
 
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;

use App\Models\User;
use App\Models\Package;
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

    public function scanDelivery(Request $request)
    { 
        $validator_msg = [
            'string' => __('messages.validator_string'), 
            'required' => __('messages.validator_required'),
            'min' => __('messages.validator_min'),
            'max' => __('messages.validator_max'),
        ];

        $validator = Main::validator($request, [
            'rules'=>[
                'code' => 'required|string|min:10|max:30', 
            ],
            'messages'=>$validator_msg,
        ]);
        
        if (!empty($validator)){
            return $validator;
        } 

        $res = new ResponseFormatter; 
        $status_group = Status::STATUS_GROUP['routing'];

        $CourierService = new \App\Services\CourierService('api');
        $RoutingService = new \App\Services\RoutingService('api');
        $PackageService = new \App\Services\PackageService('api');
        $courier = $CourierService->get($request);

        if ($courier['res'] == 'error'){
            $subject_msg = 'Kurir';
            if($request->lang && $request->lang == 'en'){
                $subject_msg = 'Courier';
            }
            return $res::error($courier['status_code'], $subject_msg . ' ' . $courier['msg'], $res::traceCode($courier['trace_code']));
        } 
        $courier = $courier['data'];
    
        // $routing = $RoutingService->getAssigned($request, $courier, function ($q) use ($request) {
        //     return $q->with([
        //             'routinghistories' => function ($q2) {
        //                 $q2->latest()->limit(1);
        //             },
        //         ])
        //         ->where('code', $request->code)
        //         ->first();
        // });
        $routing = $RoutingService->get($request, $courier, function ($q) use ($request) {
            return $q
                ->with([
                    'status',
                ])
                ->where('code', $request->code)
                ->first();
        });

        $subject_msg = 'Delivery Record';
        if($request->lang && $request->lang == 'en'){
            $subject_msg = 'Delivery Record';
        }
        if ($routing['res'] == 'error'){
            return $res::error($routing['status_code'], $subject_msg . ' ' . $routing['msg'], $res::traceCode($routing['trace_code']));
        } 
        $routing = $routing['data']; 

        $delivery_record = $routing->code;
        $status_code = $routing->status->code;
        $status_name = $routing->status->name;

        if($status_code == Status::STATUS[$status_group]['inprogress']){
            return $res::error(404, $subject_msg . ' ' . __('messages.has_status') . ' Inprogress / On-Delivery', $res::traceCode('EXCEPTION015'));
        }else{
            if($status_code != Status::STATUS[$status_group]['assigned']){
                return $res::error(404, $subject_msg . ' ' . __('messages.doesnt_have_status') . ' Assigned', $res::traceCode('EXCEPTION015'));
            }
        } 
        
        $count_order = $PackageService->get($request, $routing, function ($q) {
            return $q->with([
                    'package', 
                    // 'package.packagehistories', 
                ])
                ->count();
        }); 
        if ($count_order['res'] == 'error'){
            $subject_msg = 'Pengiriman';
            if($request->lang && $request->lang == 'en'){
                $subject_msg = 'Package';
            }
            return $res::error($count_order['status_code'], $subject_msg . ' ' . $count_order['msg'], $res::traceCode($count_order['trace_code']));
        } 

        $status_inprogress = Status::where([
            'code'=>Status::STATUS[$status_group]['inprogress'],
            'status_group'=>$status_group,
            'is_active'=>1,
        ])->first();

        DB::beginTransaction();
        try {
            $routing->status_id = $status_inprogress->status_id;
            Main::setCreatedModifiedVal(true, $routing, 'modified'); 
            $routing->save(); 

            DB::commit();

            return $res::success(__('messages.success'), [
                'code' => $request->code,
                'from_status' => $status_name,
                // 'to_status' => $status_inprogress->name,
                'to_status' => 'Inprogress / On-Delivery',
            ]);

        } catch (Exception $e) {
            DB::rollback();

            // return $res::error(500, $e->getMessage(), 'EXCEPTION014');
            return $res::error(500, __('messages.something_went_wrong'), 'EXCEPTION014');
        }
    }

    public function summaryDelivery(Request $request)
    { 
        $validator_msg = [
            'string' => __('messages.validator_string'), 
            'min' => __('messages.validator_min'),
            'max' => __('messages.validator_max'),
        ];

        $validator = Main::validator($request, [
            'rules'=>[
                'code' => 'sometimes|string|min:10|max:30', 
            ],
            'messages'=>$validator_msg,
        ]);
        
        if (!empty($validator)){
            return $validator;
        } 

        // $user = $this->auth->user();
        $res = new ResponseFormatter; 

        $CourierService = new \App\Services\CourierService('api');
        $RoutingService = new \App\Services\RoutingService('api');
        $PackageService = new \App\Services\PackageService('api');
        $courier = $CourierService->get($request);

        if ($courier['res'] == 'error'){
            $subject_msg = 'Kurir';
            if($request->lang && $request->lang == 'en'){
                $subject_msg = 'Courier';
            }
            return $res::error($courier['status_code'], $subject_msg . ' ' . $courier['msg'], $res::traceCode($courier['trace_code']));
        } 
        $courier = $courier['data'];

        $routing = $RoutingService->getInprogress($request, $courier, function ($q) use ($request) {
            if(!empty($request->code)){
                return $q
                    ->where('code', $request->code)
                    ->first();
            }else{
                $today = Carbon::today();
                
                return $q
                    ->whereDate('created_date', $today)
                    ->first();
            }
        });
        if ($routing['res'] == 'error'){
            $subject_msg = 'Delivery Record';
            if($request->lang && $request->lang == 'en'){
                $subject_msg = 'Delivery Record';
            }
            return $res::error($routing['status_code'], $subject_msg . ' ' . $routing['msg'], $res::traceCode($routing['trace_code']));
        } 
        $routing = $routing['data']; 

        $delivery_record = $routing->code;
        
        $summary = $PackageService->summary($request, $routing, function ($q) {
            // add more some filter
            // return $q->where();
            return $q;
        }); 
        if ($summary['res'] == 'error'){
            $subject_msg = 'Pengiriman';
            if($request->lang && $request->lang == 'en'){
                $subject_msg = 'Package';
            }
            return $res::error($summary['status_code'], $subject_msg . ' ' . $summary['msg'], $res::traceCode($summary['trace_code']));
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
            $subject_msg = 'Kurir';
            if($request->lang && $request->lang == 'en'){
                $subject_msg = 'Courier';
            }
            return $res::error($courier['status_code'], $subject_msg . ' ' . $courier['msg'], $res::traceCode($courier['trace_code']));
        } 
        $courier = $courier['data'];

        $routing = $RoutingService->getInprogress($request, $courier, function ($q) {
            $today = Carbon::today();

            return $q
                ->whereDate('created_date', $today)
                ->first();
        });
        if ($routing['res'] == 'error'){
            $subject_msg = 'Delivery Record';
            if($request->lang && $request->lang == 'en'){
                $subject_msg = 'Delivery Record';
            }
            return $res::error($routing['status_code'], $subject_msg . ' ' . $routing['msg'], $res::traceCode($routing['trace_code']));
        } 
        $routing = $routing['data']; 

        $delivery_record = $routing->code;
        
        $latest_order = $PackageService->getOndelivery($request, $routing, function ($q) { 
            return $q
                ->with([
                    'package', 
                    'package.packagehistories' => function ($q2){
                        $q2->orderBy('package_history_id', 'DESC')
                            ->limit(1);
                    }, 
                    // 'package.packagehistories.status', 
                ])
                ->first();
        }); 
        if ($latest_order['res'] == 'error'){
            $subject_msg = 'Pengiriman';
            if($request->lang && $request->lang == 'en'){
                $subject_msg = 'Package';
            }
            return $res::error($latest_order['status_code'], $subject_msg . ' ' . $latest_order['msg'], $res::traceCode($latest_order['trace_code']));
        } 
        $latest_order = $latest_order['data'];

        // return [
        //     'latest_order' => $latest_order,
        //     'routing' => $routing,
        // ];

        $package = $latest_order->package;
        $tracking_number = $package->tracking_number;

        // $package_history_latest = $package->packagehistories->first();
        // $package_status = $package_history_latest->status;
        $package_status = $package->status;
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

    public function list(Request $request)
    { 
        $validator_msg = [
            'string' => __('messages.validator_string'), 
            'integer' => __('messages.validator_integer'), 
            'required' => __('messages.validator_required'),
            'min' => __('messages.validator_min'),
            'max' => __('messages.validator_max'),
            'in' => __('messages.validator_in'),
        ];

        $validator = Main::validator($request, [
            'rules'=>[
                'code' => 'required|string|min:10|max:30', 
                'status' => 'sometimes|string|in:ondelivery,delivered,undelivered', 
                'page' => 'sometimes|integer|min:1', 
                'per_page' => 'sometimes|integer|min:1', 
            ],
            'messages'=>$validator_msg,
        ]);
        
        if (!empty($validator)){
            return $validator;
        } 

        // $user = $this->auth->user();
        $res = new ResponseFormatter; 
        $status_group = Status::STATUS_GROUP['package'];

        $CourierService = new \App\Services\CourierService('api');
        $RoutingService = new \App\Services\RoutingService('api');
        $PackageService = new \App\Services\PackageService('api');
        $courier = $CourierService->get($request); 

        if ($courier['res'] == 'error'){
            $subject_msg = 'Kurir';
            if($request->lang && $request->lang == 'en'){
                $subject_msg = 'Courier';
            }
            return $res::error($courier['status_code'], $subject_msg . ' ' . $courier['msg'], $res::traceCode($courier['trace_code']));
        } 
        $courier = $courier['data'];
        
        $routing = $RoutingService->get($request, $courier, function ($q) use ($request) {
            return $q
                ->where('code', $request->code)
                ->first();
        });
        if ($routing['res'] == 'error'){
            $subject_msg = 'Delivery Record';
            if($request->lang && $request->lang == 'en'){
                $subject_msg = 'Delivery Record';
            }
            return $res::error($routing['status_code'], $subject_msg . ' ' . $routing['msg'], $res::traceCode($routing['trace_code']));
        } 
        $routing = $routing['data']; 

        $delivery_record = $routing->code; 
        
        $page = $request->input('page');
        $per_page = $request->input('per_page', 15);
        $order_list = $PackageService->get($request, $routing, function ($q) use ($request, $PackageService, $status_group, $page, $per_page) {
            $sql_package = $PackageService->queryOrderByPositionNumber();
            
            $res_order_list = $q
                // ->with([
                //     'package', 
                //     'package.packagehistories', 
                // ]) 
                ->whereHas('package.status', function ($q2) use ($request, $status_group) {
                    if(!$request->status){ 
                        return $q2->whereIn('code', [
                            Status::STATUS[$status_group]['ondelivery'],
                            Status::STATUS[$status_group]['delivered'], 
                            Status::STATUS[$status_group]['undelivered'],
                        ]);
                    }
                    
                    // if($request->status == 'ondelivery'){
                    //     return $q2->where('code', '=', Status::STATUS[$status_group]['ondelivery']);
                    // }
                    return $q2->where('code', '=', Status::STATUS[$status_group][$request->status]);
                })
                // ->joinTable('package', 'p', 'rd')
                // ->joinRelation('package') 
                // ->orderBy('p.position_number', 'ASC')
                ->orderByRaw("({$sql_package})");

            if(!empty($page)){
                return $res_order_list
                    ->paginate($per_page, ['*'], 'order_list_page', $page)
                    ->withQueryString();
            }else{
                return $res_order_list
                    ->paginate($per_page)
                    ->withQueryString();
            } 
        });

        if ($order_list['res'] == 'error'){
            $subject_msg = 'Pengiriman';
            if($request->lang && $request->lang == 'en'){
                $subject_msg = 'Package';
            }
            return $res::error($order_list['status_code'], $subject_msg . ' ' . $order_list['msg'], $res::traceCode($order_list['trace_code']));
        } 
        $order_list = $order_list['data']; 

        $res_data = [
            'data' => [],
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
            $package = $val->package;
            // $package->load([
            //     'packagehistories'=> function ($q) {
            //         $q->orderBy('package_history_id', 'DESC')
            //             ->limit(1);
            //     },
            // ]);
            $package_status = $package->status;
            $package_status_code = $package_status->code;
            $package_status_name = $package_status->name; 

            $is_cod = false;
            if($package->cod_price > 0){
                $is_cod = true;
            } 

            $res_data['data'][] = [
                'position_number' => $package->position_number,
                'delivery_record' => $delivery_record,
                'tracking_number' => $package->tracking_number,
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
                'date' => $package->created_date,
            ];
        }

        return $res::success(__('messages.success'), $res_data);
    }

    public function sortingNumbers(Request $request)
    {
        $validator_msg = [
            'string' => __('messages.validator_string'), 
            'integer' => __('messages.validator_integer'), 
            'required' => __('messages.validator_required'),
            'min' => __('messages.validator_min'),
            'max' => __('messages.validator_max'),
        ];

        $validator = Main::validator($request, [
            'rules'=>[
                'code' => 'required|string|min:10|max:30', 
                'tracking_number' => 'required|string|min:10|max:30',
                'position' => 'required|integer|min:1'
            ],
            'messages'=>$validator_msg,
        ]);
        
        if (!empty($validator)){
            return $validator;
        } 

        $res = new ResponseFormatter; 
        $status_group = Status::STATUS_GROUP['package'];
        
        $CourierService = new \App\Services\CourierService('api');
        $RoutingService = new \App\Services\RoutingService('api');
        $PackageService = new \App\Services\PackageService('api');
        $courier = $CourierService->get($request); 

        if ($courier['res'] == 'error'){
            $subject_msg = 'Kurir';
            if($request->lang && $request->lang == 'en'){
                $subject_msg = 'Courier';
            }
            return $res::error($courier['status_code'], $subject_msg . ' ' . $courier['msg'], $res::traceCode($courier['trace_code']));
        } 
        $courier = $courier['data'];
        
        $routing = $RoutingService->get($request, $courier, function ($q) use ($request) {
            return $q
                ->where('code', $request->code)
                ->first();
        });
        if ($routing['res'] == 'error'){
            $subject_msg = 'Delivery Record';
            if($request->lang && $request->lang == 'en'){
                $subject_msg = 'Delivery Record';
            }
            return $res::error($routing['status_code'], $subject_msg . ' ' . $routing['msg'], $res::traceCode($routing['trace_code']));
        } 
        $routing = $routing['data']; 

        $delivery_record = $routing->code; 
        
        $get_order = $PackageService->get($request, $routing, function ($q) use ($request) {
            return $q
                ->with([
                    'package', 
                ])
                ->whereHas('package', function ($q2) use ($request) {
                    return $q2->where('tracking_number', $request->tracking_number);
                })
                ->first();
        }); 
        if ($get_order['res'] == 'error'){
            $subject_msg = 'Pengiriman';
            if($request->lang && $request->lang == 'en'){
                $subject_msg = 'Package';
            }
            return $res::error($get_order['status_code'], $subject_msg . ' ' . $get_order['msg'], $res::traceCode($get_order['trace_code']));
        } 
        $get_order = $get_order['data']; 

        // Mengambil posisi saat ini
        $currentPosition = $get_order->package->position_number;
        
        // Menentukan batasan posisi yang akan diupdate
        $minPosition = min($currentPosition, $request->position);
        $maxPosition = max($currentPosition, $request->position); 

        // Update posisi untuk rekaman dengan posisi lebih rendah dari rekaman saat ini
        // DB::table('test_order')
        //     ->whereBetween('position', [$minPosition, $currentPosition - 1])
        //     ->increment('position');
        Package::whereIn('package_id', $routing->routingdetails()->select('package_id'))
            ->whereBetween('position_number', [$minPosition, $currentPosition - 1])
            ->increment('position_number');

        // Update posisi untuk rekaman dengan posisi lebih tinggi dari rekaman saat ini
        // DB::table('test_order')
        //     ->whereBetween('position', [$currentPosition + 1, $maxPosition])
        //     ->decrement('position');
        Package::whereIn('package_id', $routing->routingdetails()->select('package_id'))
            ->whereBetween('position_number', [$currentPosition + 1, $maxPosition])
            ->decrement('position_number');

        // Set posisi baru untuk rekaman saat ini
        // DB::table('test_order')
        //     ->where('tracking_number', $request->tracking_number)
        //     ->update(['position' => $request->position]);  
        Package::where('tracking_number', $request->tracking_number)
            ->update(['position_number' => $request->position]);

        return $res::success(__('messages.success'));
    }
}
