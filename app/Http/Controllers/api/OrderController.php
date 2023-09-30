<?php

namespace App\Http\Controllers\api;
 
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

use App\Models\User;
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
        $res = new ResponseFormatter;
        $res_data = [
            'delivery_record'=>'TEST001',
            'delivery'=>[
                'total' => '70/150',
                'total_money' => '2 Koli /5Kg',
                'total_weight' => '10.341.000.000',
            ],
            'cod'=>'80',
            'non_cod'=>'70',
            'delivered'=>[
                'total' => '70/150',
                'total_money' => '2 Koli /5Kg',
                'total_weight' => '655.000.000',
            ],
            'undelivered'=>[
                'total' => '70/150',
                'total_money' => '2 Koli /5Kg',
                'total_weight' => '200.000.000',
            ],
            'date'=>date('Y-m-d H:i:s'),
        ];
        return $res::success(__('messages.success'), $res_data);
    }

    public function latest(Request $request)
    { 
        $user = $this->auth->user();  

        $today = Carbon::today();
        $couriers = $user->userpartner->partner->couriers()->latest()->first();
        $routing = $couriers->routings()->whereDate('created_date', $today)->latest()->first();
        $delivery_record = $routing->code;
        $routing_detail = $routing->routingdetails()->latest()->first();
        $routing_history = $routing->routinghistories()->latest()->first();
        $status = $routing_history->status->name;
        $package = $routing_detail->package;
        $servicetype = $package->servicetype;
        $rates = $package->rates;
        // $clientrates = [];
        // foreach($rates as $idx => $val) {
        //     $clientrates = $val->clientrates;
        // }

        $is_cod = false;
        if($package->cod_price > 0){
            $is_cod = true;
        }

        $is_complete = false;
        if($status == 'delivered'){
            $is_complete = true;
        }

        $res = new ResponseFormatter;
        $res_data = [
            'tracking_number' => $package->tracking_number,
            'name' => $package->recipient_name,
            'address' => $package->recipient_address,
            'weight' => $package->total_weight,
            'koli' => $package->total_koli,
            'is_cod' => $is_cod,
            'is_complete' => $is_complete,
            'status' => $status,
        ];
        return $res::success(__('messages.success'), $res_data);
    }
}
