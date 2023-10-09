<?php

namespace App\Services;

use Illuminate\Http\Request;

use App\Models\User;
use App\Helpers\Main;

class CourierService
{
    private $auth;

    public function __construct($auth='api')
    { 
        $this->auth = auth($auth);
    }

    public function get(Request $request)
    {   
        $user = $this->auth->user(); 
        $couriers = $user->userpartner->partner->couriers()->latest()->first();

        if(!$couriers){
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
            'data' => $couriers,
        ];
    }

}
