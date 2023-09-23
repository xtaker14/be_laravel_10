<?php

namespace App\Http\Controllers\api;
 
use Illuminate\Http\Request;

use App\Models\User;
use App\Helpers\Main;
use App\Helpers\ResponseFormatter;

class OrderController extends Controller
{
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
        $res = new ResponseFormatter;
        $res_data = [
            'tracking_number' => '230300600581-00',
            'name' => 'Hamdani',
            'address' => 'Jl. Jend Sudirman No. 5, DKI Jakarta, Jakarta Pusat, Tanah Abang, 10270',
            'weight' => '2 Koli /5Kg',
            'is_cod' => false,
            'is_complete' => true,
            'status' => 'delivered',
        ];
        return $res::success(__('messages.success'), $res_data);
    }
}
