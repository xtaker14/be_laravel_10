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

class OrderController extends Controller
{
    private $auth;

    public function __construct()
    {
        $this->auth = auth('api');
    }

    public function scanDeliveryRecord(Request $request)
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
        $status_group = [
            'routing' => Status::STATUS_GROUP['routing'],
            'package' => Status::STATUS_GROUP['package'],
        ];

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

        if($status_code == Status::STATUS[$status_group['routing']]['inprogress']){
            return $res::error(404, $subject_msg . ' ' . __('messages.has_status') . ' Inprogress / On-Delivery', $res::traceCode('EXCEPTION015'));
        }else{
            if($status_code != Status::STATUS[$status_group['routing']]['assigned']){
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
        $count_order = $count_order['data'];

        $status_inprogress = Status::where([
            'code' => Status::STATUS[$status_group['routing']]['inprogress'],
            'status_group' => $status_group['routing'],
            'is_active' => 1,
        ])->first();

        $status_ondelivery = Status::where([
            'code' => Status::STATUS[$status_group['package']]['ondelivery'],
            'status_group' => $status_group['package'],
            'is_active' => 1,
        ])->first(); 

        DB::beginTransaction();
        try {
            $routing->status_id = $status_inprogress->status_id;
            Main::setCreatedModifiedVal(true, $routing, 'modified'); 
            $routing->save();
            
            $PackageService->get($request, $routing, function ($query) use ($status_group, $status_ondelivery) {
                // Dapatkan semua package IDs 
                $packageIds = $query
                    ->join('package', 'routingdetail.package_id', '=', 'package.package_id')
                    ->join('status', 'package.status_id', '=', 'status.status_id')
                    ->where('status.code', Status::STATUS[$status_group['package']]['routing'])
                    ->pluck('routingdetail.package_id');

                // Update status package
                return Package::whereIn('package_id', $packageIds)
                    ->update(['status_id' => $status_ondelivery->status_id]);
            });

            $routing_delivery = $RoutingService->counterRoutingDelivery(
                $routing->routing_id,
                [
                    'delivery' => $count_order,
                    'total_delivery' => $count_order,
                ]
            );

            DB::commit();

            return $res::success(__('messages.success'), [
                'code' => $request->code,
                'from_status' => $status_name,
                // 'to_status' => $status_inprogress->name,
                'to_status' => 'Inprogress / On-Delivery',
            ]);

        } catch (Exception $e) {
            DB::rollback();

            $trace_code = $res::traceCode('EXCEPTION014');
            if(env('APP_DEBUG', false)){
                $trace_code = $res::traceCode('EXCEPTION014', [
                    'message' => $e->getMessage(),
                ]);
            } 
            return $res::error(500, __('messages.something_went_wrong'), $trace_code);
        }
    }

    public function summaryDeliveryRecord(Request $request)
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
        
        $summary = $RoutingService->summaryDeliveryRecord($request, $routing, function ($q) {
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

    public function deliveryRecordList(Request $request)
    {
        $validator_msg = [
            'string' => __('messages.validator_string'),
            'integer' => __('messages.validator_integer'),
            'min' => __('messages.validator_min'),
            'in' => __('messages.validator_in'),
        ];

        $status_group = [
            'routing' => Status::STATUS_GROUP['routing'],
            'package' => Status::STATUS_GROUP['package'],
        ];

        $status_in = [
            strtolower(Status::STATUS[$status_group['routing']]['inprogress']),
            strtolower(Status::STATUS[$status_group['routing']]['collected']),
        ];

        $validator = Main::validator($request, [
            'rules' => [
                'status' => 'sometimes|string|in:' . (implode(',', $status_in)),
                'page' => 'sometimes|integer|min:1',
                'per_page' => 'sometimes|integer|min:1',
            ],
            'messages' => $validator_msg,
        ]);

        if (!empty($validator)) {
            return $validator;
        }

        $res = new ResponseFormatter;

        $CourierService = new \App\Services\CourierService('api');
        $RoutingService = new \App\Services\RoutingService('api');
        $courier = $CourierService->get($request);

        if ($courier['res'] == 'error') {
            $subject_msg = 'Kurir';
            if ($request->lang && $request->lang == 'en') {
                $subject_msg = 'Courier';
            }
            return $res::error($courier['status_code'], $subject_msg . ' ' . $courier['msg'], $res::traceCode($courier['trace_code']));
        }
        $courier = $courier['data'];

        $page = $request->input('page');
        $per_page = $request->input('per_page', 10);
        $dr_list = $RoutingService->get($request, $courier, function ($q) use ($request, $status_group, $page, $per_page) {
            $res_dr_list = $q
                ->where(function ($q2) use ($request, $status_group) {
                    // filter
                    if ($request->status) {
                        $q2->whereHas('status', function ($q3) use ($request, $status_group) {
                            return $q3->where('code', '=', Status::STATUS[$status_group['routing']][$request->status]);
                        });
                    } else {
                        // Tampilkan semua status inprogress, collected
                        $q2->whereHas('status', function ($q3) use ($request, $status_group) {
                            return $q3->whereIn('code', [
                                Status::STATUS[$status_group['routing']]['inprogress'],
                                Status::STATUS[$status_group['routing']]['collected'],
                            ]);
                        });
                    }
                });

            if (!empty($page)) {
                return $res_dr_list
                    ->paginate($per_page, ['*'], 'page_dr_list', $page)
                    ->withQueryString();
            } else {
                return $res_dr_list
                    ->paginate($per_page)
                    ->withQueryString();
            } 
        });
        if ($dr_list['res'] == 'error') {
            $subject_msg = 'Delivery Record';
            if ($request->lang && $request->lang == 'en') {
                $subject_msg = 'Delivery Record';
            }
            return $res::error($dr_list['status_code'], $subject_msg . ' ' . $dr_list['msg'], $res::traceCode($dr_list['trace_code']));
        }
        $dr_list = $dr_list['data'];

        $res_data = [
            'data' => [],
            'pagination' => [
                'total' => $dr_list->total(),
                'current_page' => $dr_list->currentPage(),
                'last_page' => $dr_list->lastPage(),
                'per_page' => $dr_list->perPage(),
                'next_page_url' => $dr_list->nextPageUrl(),
                'prev_page_url' => $dr_list->previousPageUrl(),
            ]
        ];

        foreach ($dr_list as $idx => $val) {
            // $val->load([
            //     'routingdelivery'=> function ($q) {
            //         $q->first();
            //     },
            // ]);
            $delivery_record = $val->code;
            $created_date = $val->created_date;
            $status_code = $val->status->code;
            $status_name = $val->status->name;
            $total_delivery = $val->routingdelivery->total_delivery ?? 0;
            $delivered = $val->routingdelivery->delivered ?? 0;
            $undelivered = $val->routingdelivery->undelivered ?? 0;
            $total_cod_price = $val->routingdelivery->total_cod_price ?? 0;

            $res_data['data'][] = [
                'total_delivery' => $total_delivery,
                'delivered' => $delivered,
                'undelivered' => $undelivered,
                'total_cod_price' => $total_cod_price,
                'delivery_record' => $delivery_record,
                'status_code' => $status_code,
                'status_name' => $status_name,
                'date' => $created_date,
            ];
        }

        return $res::success(__('messages.success'), $res_data);
    }
    
    public function deliveryRecordDetail(Request $request)
    {
        $validator_msg = [
            'string' => __('messages.validator_string'),
            'required' => __('messages.validator_required'),
            'min' => __('messages.validator_min'),
            'max' => __('messages.validator_max'),
        ];

        $validator = Main::validator($request, [
            'rules' => [
                'code' => 'required|string|min:10|max:30',
            ],
            'messages' => $validator_msg,
        ]);

        if (!empty($validator)) {
            return $validator;
        }

        $res = new ResponseFormatter;
        $status_group = [
            'package' => Status::STATUS_GROUP['package'],
            'routing' => Status::STATUS_GROUP['routing'],
        ];

        $CourierService = new \App\Services\CourierService('api');
        $RoutingService = new \App\Services\RoutingService('api');
        $courier = $CourierService->get($request);

        if ($courier['res'] == 'error') {
            $subject_msg = 'Kurir';
            if ($request->lang && $request->lang == 'en') {
                $subject_msg = 'Courier';
            }
            return $res::error($courier['status_code'], $subject_msg . ' ' . $courier['msg'], $res::traceCode($courier['trace_code']));
        }
        $courier = $courier['data'];

        $routing = $RoutingService->get($request, $courier, function ($q) use ($request, $status_group) {
            return $q
                ->whereHas('status', function ($q2) use ($status_group) {
                    return $q2->whereIn('code', [
                        Status::STATUS[$status_group['routing']]['inprogress'],
                        Status::STATUS[$status_group['routing']]['collected'],
                    ]);
                })
                ->where('code', $request->code)
                ->first();
        });
        if ($routing['res'] == 'error') {
            $subject_msg = 'Delivery Record';
            if ($request->lang && $request->lang == 'en') {
                $subject_msg = 'Delivery Record';
            }
            return $res::error($routing['status_code'], $subject_msg . ' ' . $routing['msg'], $res::traceCode($routing['trace_code']));
        }
        $routing = $routing['data'];

        $delivery_record = $routing->code;
        $created_date = $routing->created_date;
        $status_code = $routing->status->code;
        $status_name = $routing->status->name;
        $total_delivery = $routing->routingdelivery->total_delivery ?? 0;
        $delivered = $routing->routingdelivery->delivered ?? 0;
        $undelivered = $routing->routingdelivery->undelivered ?? 0;
        $total_cod_price = $routing->routingdelivery->total_cod_price ?? 0;

        $res_data = [
            'total_delivery' => $total_delivery,
            'delivered' => $delivered,
            'undelivered' => $undelivered,
            'total_cod_price' => $total_cod_price,
            'delivery_record' => $delivery_record,
            'status_code' => $status_code,
            'status_name' => $status_name,
            'date' => $created_date,
        ];

        return $res::success(__('messages.success'), $res_data);
    }

    public function downloadDeliveryRecord(Request $request)
    {
        $validator_msg = [
            'string' => __('messages.validator_string'),
            'required' => __('messages.validator_required'),
            'min' => __('messages.validator_min'),
            'max' => __('messages.validator_max'),
        ];

        $validator = Main::validator($request, [
            'rules' => [
                'code' => 'required|string|min:10|max:30',
            ],
            'messages' => $validator_msg,
        ]);

        if (!empty($validator)) {
            return $validator;
        }

        $res = new ResponseFormatter;
        $status_group = [
            'package' => Status::STATUS_GROUP['package'],
            'routing' => Status::STATUS_GROUP['routing'],
        ];

        $CourierService = new \App\Services\CourierService('api');
        $RoutingService = new \App\Services\RoutingService('api');
        $courier = $CourierService->get($request);

        if ($courier['res'] == 'error') {
            $subject_msg = 'Kurir';
            if ($request->lang && $request->lang == 'en') {
                $subject_msg = 'Courier';
            }
            return $res::error($courier['status_code'], $subject_msg . ' ' . $courier['msg'], $res::traceCode($courier['trace_code']));
        }
        $courier = $courier['data'];

        $routing = $RoutingService->get($request, $courier, function ($q) use ($request, $status_group) {
            return $q
                ->whereHas('status', function ($q2) use ($status_group) {
                    return $q2->whereIn('code', [
                        Status::STATUS[$status_group['routing']]['inprogress'],
                        Status::STATUS[$status_group['routing']]['collected'],
                    ]);
                })
                ->where('code', $request->code)
                ->first();
        });
        if ($routing['res'] == 'error') {
            $subject_msg = 'Delivery Record';
            if ($request->lang && $request->lang == 'en') {
                $subject_msg = 'Delivery Record';
            }
            return $res::error($routing['status_code'], $subject_msg . ' ' . $routing['msg'], $res::traceCode($routing['trace_code']));
        }
        $routing = $routing['data'];

        $delivery_record = $routing->code;
        $status_code = $routing->status->code;
        $status_name = $routing->status->name;

        $filename = 'delivery-record-mobile-' . $delivery_record . '.pdf';
        $link_pdf = url('storage/pdf/' . $filename);

        $res_data = [
            'delivery_record' => $delivery_record,
            'status_code' => $status_code,
            'status_name' => $status_name,
            'link_pdf' => $link_pdf,
        ];

        return $res::success(__('messages.success'), $res_data);
    }

    public function latestDelivery(Request $request)
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

    public function deliveryList(Request $request)
    { 
        $validator_msg = [
            'string' => __('messages.validator_string'), 
            'integer' => __('messages.validator_integer'), 
            'required' => __('messages.validator_required'),
            'min' => __('messages.validator_min'),
            'max' => __('messages.validator_max'),
            'in' => __('messages.validator_in'),
        ];

        $status_group = [
            'routing' => Status::STATUS_GROUP['routing'],
            'package' => Status::STATUS_GROUP['package'],
        ];

        $status_in = [
            strtolower(Status::STATUS[$status_group['package']]['ondelivery']),
            strtolower(Status::STATUS[$status_group['package']]['delivered']),
            strtolower(Status::STATUS[$status_group['package']]['undelivered']),
        ];

        $validator = Main::validator($request, [
            'rules'=>[
                'code' => 'required|string|min:10|max:30', 
                'status' => 'sometimes|string|in:' . (implode(',', $status_in)), 
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
        
        $routing = $RoutingService->get($request, $courier, function ($q) use ($request, $status_group) {
            return $q
                ->whereHas('status', function ($q2) use ($status_group) {
                    return $q2->where('code', Status::STATUS[$status_group['routing']]['inprogress']);
                })
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
        $per_page = $request->input('per_page', 10);
        $order_list = $PackageService->get($request, $routing, function ($q) use ($request, $PackageService, $status_group, $page, $per_page) {
            $sql_package = $PackageService->queryOrderByPositionNumber();
            
            $res_order_list = $q
                ->where(function ($q2) use ($request, $status_group) {
                    // filter
                    if ($request->status) {
                        $q2->whereHas('package.status', function ($q3) use ($request, $status_group) {
                            return $q3->where('code', '=', Status::STATUS[$status_group['package']][$request->status]);
                        });
                        
                        // Jika status yang di request adalah UNDELIVERED maka cek apakah ada history dengan status RETURN
                        if ($request->status === 'undelivered') {
                            $q2->orWhereHas('package.packagehistories.status', function ($q3) use ($request, $status_group) {
                                return $q3->where('code', '=', Status::STATUS[$status_group['package']]['return']);
                            });
                        }
                    } else {
                        // Tampilkan semua status ondelivery, delivered, undelivered, return
                        $q2->whereHas('package.status', function ($q3) use ($request, $status_group) {
                            return $q3->whereIn('code', [
                                Status::STATUS[$status_group['package']]['ondelivery'],
                                Status::STATUS[$status_group['package']]['delivered'], 
                                Status::STATUS[$status_group['package']]['undelivered'],
                                Status::STATUS[$status_group['package']]['return'],
                            ]);
                        });
                    }
                })
                // ->joinTable('package', 'p', 'rd')
                // ->joinRelation('package') 
                // ->orderBy('p.position_number', 'ASC')
                ->orderByRaw("({$sql_package})");

            if(!empty($page)){
                return $res_order_list
                    ->paginate($per_page, ['*'], 'page_order_list', $page)
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

        $status_undelivered = Status::where([
            'code'=>Status::STATUS[$status_group['package']]['undelivered'],
            'status_group'=>$status_group['package'],
            'is_active'=>1,
        ])->first();

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
            $package_status = $package->status;
            $package_status_code = $package_status->code;
            $package_status_name = $package_status->name; 
            if($package_status_code == Status::STATUS[$status_group['package']]['return']){
                $package_status_code = Status::STATUS[$status_group['package']]['undelivered'];
                $package_status_name = $status_undelivered->name;
            }

            $is_cod = false;
            if($package->cod_price > 0){
                $is_cod = true;
            } 

            $res_data['data'][] = [
                'position_number' => $package->position_number,
                'delivery_record' => $delivery_record,
                'tracking_number' => $package->tracking_number,
                'name' => $package->recipient_name,

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

    public function sortingDeliveryNumbers(Request $request)
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
        $status_group = [
            'package' => Status::STATUS_GROUP['package'],
            'routing' => Status::STATUS_GROUP['routing'],
        ];
        
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
        
        $routing = $RoutingService->get($request, $courier, function ($q) use ($request, $status_group) {
            return $q
                ->whereHas('status', function ($q2) use ($status_group) {
                    return $q2->where('code', Status::STATUS[$status_group['routing']]['inprogress']);
                })
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
        
        $order_detail = $PackageService->get($request, $routing, function ($q) use ($request, $status_group) {
            return $q
                ->with([
                    'package', 
                ])
                ->whereHas('package.status', function ($q2) use ($request, $status_group) {
                    return $q2->whereIn('code', [
                        Status::STATUS[$status_group['package']]['ondelivery'],
                        Status::STATUS[$status_group['package']]['delivered'], 
                        Status::STATUS[$status_group['package']]['undelivered'],
                        Status::STATUS[$status_group['package']]['return'],
                    ]);
                })
                ->whereHas('package', function ($q2) use ($request) {
                    return $q2->where('tracking_number', $request->tracking_number);
                })
                ->first();
        }); 
        if ($order_detail['res'] == 'error'){
            $subject_msg = 'Pengiriman';
            if($request->lang && $request->lang == 'en'){
                $subject_msg = 'Package';
            }
            return $res::error($order_detail['status_code'], $subject_msg . ' ' . $order_detail['msg'], $res::traceCode($order_detail['trace_code']));
        } 
        $order_detail = $order_detail['data']; 

        // Mengambil posisi saat ini
        $currentPosition = $order_detail->package->position_number;
        
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

    public function deliveryDetail(Request $request)
    {
        $validator_msg = [
            'string' => __('messages.validator_string'), 
            'required' => __('messages.validator_required'),
            'min' => __('messages.validator_min'),
            'max' => __('messages.validator_max'),
        ];

        // $request->merge(['code' => $code]);
        $validator = Main::validator($request, [
            'rules'=>[
                'code' => 'required|string|min:10|max:30', 
                'tracking_number' => 'required|string|min:10|max:30',
            ],
            'messages'=>$validator_msg,
        ]);
        
        if (!empty($validator)){
            return $validator;
        } 

        // $user = $this->auth->user();
        $res = new ResponseFormatter; 
        $status_group = [
            'package' => Status::STATUS_GROUP['package'],
            'routing' => Status::STATUS_GROUP['routing'],
        ];

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
        
        $routing = $RoutingService->get($request, $courier, function ($q) use ($request, $status_group) {
            return $q
                ->whereHas('status', function ($q2) use ($status_group) {
                    return $q2->where('code', Status::STATUS[$status_group['routing']]['inprogress']);
                })
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
        
        $order_detail = $PackageService->get($request, $routing, function ($q) use ($request, $status_group) {
            $res_order_detail = $q
                ->with([
                    'package.packagedelivery',
                    'package.packagedelivery.packagehistory',
                ])
                ->whereHas('package.status', function ($q2) use ($request, $status_group) {
                    return $q2->whereIn('code', [
                        Status::STATUS[$status_group['package']]['ondelivery'],
                        Status::STATUS[$status_group['package']]['delivered'], 
                        Status::STATUS[$status_group['package']]['undelivered'],
                        Status::STATUS[$status_group['package']]['return'],
                    ]);
                })
                ->whereHas('package', function ($q2) use ($request) {
                    return $q2->where('tracking_number', $request->tracking_number);
                })
                ->first();
                
            return $res_order_detail;
        });

        if ($order_detail['res'] == 'error'){
            $subject_msg = 'Pengiriman';
            if($request->lang && $request->lang == 'en'){
                $subject_msg = 'Package';
            }
            return $res::error($order_detail['status_code'], $subject_msg . ' ' . $order_detail['msg'], $res::traceCode($order_detail['trace_code']));
        } 
        $order_detail = $order_detail['data']; 

        $status_undelivered = Status::where([
            'code'=>Status::STATUS[$status_group['package']]['undelivered'],
            'status_group'=>$status_group['package'],
            'is_active'=>1,
        ])->first();

        $package = $order_detail->package;
        $packagedelivery = $package->packagedelivery;
        // $packagehistory = $packagedelivery->packagehistory;

        $package_status = $package->status;
        $package_status_code = $package_status->code;
        $package_status_name = $package_status->name; 
        if($package_status_code == Status::STATUS[$status_group['package']]['return']){
            $package_status_code = Status::STATUS[$status_group['package']]['undelivered'];
            $package_status_name = $status_undelivered->name;
        }

        $is_cod = false;
        if($package->cod_price > 0){
            $is_cod = true;
        } 

        $res_data = [
            'position_number' => $package->position_number,
            'delivery_record' => $delivery_record,
            'tracking_number' => $package->tracking_number,
            'merchant_name' => $package->merchant_name,
            'reference_number' => $package->reference_number,
            'weight' => $package->total_weight,
            'koli' => $package->total_koli,
            'is_cod' => $is_cod,
            'request_pickup_date' => $package->request_pickup_date,
            'date' => $package->created_date,
            'status_code' => $package_status_code,
            'status_name' => $package_status_name,

            'information' => $packagedelivery->information ?? null,
            'notes' => $packagedelivery->notes ?? null,
            'accept_cod' => $packagedelivery->accept_cod ?? null,
            'e_signature' => $packagedelivery->e_signature ?? null,
            'photo' => $packagedelivery->photo ?? null,

            'pickup_country' => $package->pickup_country,
            'pickup_province' => $package->pickup_province,
            'pickup_city' => $package->pickup_city,
            'pickup_address' => $package->pickup_address,
            'pickup_district' => $package->pickup_district,
            'pickup_subdistrict' => $package->pickup_subdistrict,
            'pickup_postal_code' => $package->pickup_postal_code,
            'pickup_name' => $package->pickup_name,
            'pickup_email' => $package->pickup_email,
            'pickup_phone' => $package->pickup_phone,
            'pickup_coordinate' => $package->pickup_coordinate,
            'pickup_notes' => $package->pickup_notes,

            'recipient_country' => $package->recipient_country,
            'recipient_province' => $package->recipient_province,
            'recipient_city' => $package->recipient_city,
            'recipient_address' => $package->recipient_address,
            'recipient_district' => $package->recipient_district,
            'recipient_postal_code' => $package->recipient_postal_code,
            'recipient_name' => $package->recipient_name,
            'recipient_email' => $package->recipient_email,
            'recipient_phone' => $package->recipient_phone,
            'recipient_coordinate' => $package->recipient_coordinate,
            'recipient_notes' => $package->recipient_notes,

        ];

        return $res::success(__('messages.success'), $res_data);
    }

    public function updateDeliveryDetail(Request $request)
    {
        $validator_msg = [
            'string' => __('messages.validator_string'), 
            'required' => __('messages.validator_required'),
            'min' => __('messages.validator_min'),
            'max' => __('messages.validator_max'),
        ];

        $status_group = [
            'routing' => Status::STATUS_GROUP['routing'],
            'package' => Status::STATUS_GROUP['package'],
        ];

        $status_in = [ 
            strtolower(Status::STATUS[$status_group['package']]['delivered']),
            strtolower(Status::STATUS[$status_group['package']]['undelivered']),
        ];

        $validator = Main::validator($request, [
            'rules'=>[
                'code' => 'required|string|min:10|max:30', 
                'tracking_number' => 'required|string|min:10|max:30',
                'status' => 'required|string|in:' . (implode(',', $status_in)), 
                'information' => 'required|string|min:10|max:30',
                'notes' => 'required|string|min:3|max:30',
                'accept_cod' => 'required|string|in:no,yes', 
                'e_signature' => [
                    'required',
                    File::types(['jpg', 'jpeg', 'png'])
                        // ->dimensions(Rule::dimensions()->maxWidth(1000)->maxHeight(500))
                        // ->min(1024)
                        ->max(2 * 1024),
                ],
                'photo' => [
                    'required',
                    File::types(['jpg', 'jpeg', 'png'])
                        // ->min(1024)
                        ->max(2 * 1024),
                ],
            ],
            'messages'=>$validator_msg,
        ]);
        
        if (!empty($validator)){
            return $validator;
        } 

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
        
        $routing = $RoutingService->get($request, $courier, function ($q) use ($request, $status_group) {
            return $q
                ->whereHas('status', function ($q2) use ($status_group) {
                    return $q2->where('code', Status::STATUS[$status_group['routing']]['inprogress']);
                })
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
        
        $ondelivery_order = $PackageService->getOndelivery($request, $routing, function ($q) use ($request) { 
            return $q
                ->whereHas('package', function ($q2) use ($request) {
                    return $q2->where('tracking_number', $request->tracking_number);
                })
                ->first();
        }); 
        if ($ondelivery_order['res'] == 'error'){
            $subject_msg = 'Pengiriman';
            if($request->lang && $request->lang == 'en'){
                $subject_msg = 'Package';
            }
            return $res::error($ondelivery_order['status_code'], $subject_msg . ' ' . $ondelivery_order['msg'], $res::traceCode($ondelivery_order['trace_code']));
        } 
        $ondelivery_order = $ondelivery_order['data'];
        $ondelivery_package = $ondelivery_order->package;
        
        $url_e_signature = ''; 
        $url_photo = '';

        $file_e_signature = 'e-signature-'.time().'.'.$request->e_signature->extension();  
        $contents_e_signature = file_get_contents($request->e_signature);

        $file_photo = 'e-signature-'.time().'.'.$request->photo->extension();  
        $contents_photo = file_get_contents($request->photo);
        try {
            Storage::disk('s3')->put($file_e_signature, $contents_e_signature); 
            $url_e_signature = Storage::disk('s3')->url($file_e_signature);
            
            Storage::disk('s3')->put($file_photo, $contents_photo); 
            $url_photo = Storage::disk('s3')->url($file_photo);
        } catch (\Exception $e) { 
            $trace_code = $res::traceCode('EXCEPTION016');
            if(env('APP_DEBUG', false)){
                $trace_code = $res::traceCode('EXCEPTION016', [
                    'message' => $e->getMessage(),
                ]);
            }
            return $res::error(500, __('messages.failed_to_upload_aws'), $trace_code);
        } 

        $status_delivered = Status::where([
            'code'=>Status::STATUS[$status_group['package']]['delivered'],
            'status_group'=>$status_group['package'],
            'is_active'=>1,
        ])->first();

        $status_undelivered = Status::where([
            'code'=>Status::STATUS[$status_group['package']]['undelivered'],
            'status_group'=>$status_group['package'],
            'is_active'=>1,
        ])->first();

        $to_status = '';
        if($request->status == 'delivered'){
            $to_status = $status_delivered->status_id;
        }
        if($request->status == 'undelivered'){
            $to_status = $status_undelivered->status_id;
        }

        DB::beginTransaction();
        try {
            $ondelivery_package->status_id = $to_status;
            Main::setCreatedModifiedVal(true, $ondelivery_package, 'modified'); 
            $ondelivery_package->save(); 

            $params = [
                'package_id' => $ondelivery_package->package_id,
                'status_id' => $to_status,
            ];
            Main::setCreatedModifiedVal(false, $params);
            $ins_packagehistory = PackageHistory::create($params);

            $params = [
                'package_id' => $ondelivery_package->package_id,
                'package_history_id' => $ins_packagehistory->package_history_id,
                'information' => $request->information,
                'notes' => $request->notes,
                'accept_cod' => $request->accept_cod,
                'e_signature' => $url_e_signature,
                'photo' => $url_photo,
            ];
            Main::setCreatedModifiedVal(false, $params);
            $ins_packagedelivery = PackageDelivery::create($params);

            $params_routing_delivery = [
                'total_cod_price' => $ondelivery_package->cod_price,
                'total_shipping_price' => $ondelivery_package->shipping_price,
                'total_package_price' => $ondelivery_package->package_price,
            ];
            if ($request->status == 'delivered') {
                $params_routing_delivery['delivered'] = 1;
            }
            if ($request->status == 'undelivered') {
                $params_routing_delivery['undelivered'] = 1;
            }

            $routing_delivery = $RoutingService->counterRoutingDelivery($routing->routing_id, $params_routing_delivery);
            
            DB::commit();

            return $res::success(__('messages.success'), $params);

        } catch (Exception $e) {
            DB::rollback();
            
            $trace_code = $res::traceCode('EXCEPTION014');
            if(env('APP_DEBUG', false)){
                $trace_code = $res::traceCode('EXCEPTION014', [
                    'message' => $e->getMessage(),
                ]);
            }
            return $res::error(500, __('messages.something_went_wrong'), $trace_code);
        }
    }
}
