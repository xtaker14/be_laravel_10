<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

use App\Models\User;
use App\Models\Status;
use App\Helpers\Main;

class RoutingService
{
    private $auth;

    public function __construct($auth='api')
    { 
        $this->auth = auth($auth);
    }

    public function get(Request $request, $courier, $add_filter=false)
    {   
        // $user = $this->auth->user(); 
        $status_group = [
            'package' => Status::STATUS_GROUP['package'],
            'routing' => Status::STATUS_GROUP['routing'],
        ];

        $query = $courier->routings();

        if (is_callable($add_filter)) {
            $query = $add_filter($query);
            $routing = $query;
        }else{
            $routing = $query->first();
        }

        if(!$routing){
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
            'data' => $routing,
        ];
    }

    public function getAssigned(Request $request, $courier, $add_filter=false)
    {   
        // $user = $this->auth->user(); 
        $status_group = [
            'package' => Status::STATUS_GROUP['package'],
            'routing' => Status::STATUS_GROUP['routing'],
        ];

        $query = $courier->routings()
            ->whereHas('status', function ($query) use ($status_group) {
                return $query->where('code', '=', Status::STATUS[$status_group['routing']]['assigned']);
            });

        if (is_callable($add_filter)) {
            $query = $add_filter($query);
            $routing = $query;
        }else{
            $routing = $query->first();
        }

        if(!$routing){
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
            'data' => $routing,
        ];
    }

    public function getInprogress(Request $request, $courier, $add_filter=false)
    {   
        // $user = $this->auth->user(); 
        $status_group = [
            'package' => Status::STATUS_GROUP['package'],
            'routing' => Status::STATUS_GROUP['routing'],
        ];

        $query = $courier->routings()
            // ->whereHas('routinghistories.status', function ($query) use ($status_group) {
            //     return $query->where('code', '=', Status::STATUS[$status_group['routing']]['inprogress']);
            // })
            // ->whereDoesntHave('routinghistories.status', function ($query) use ($status_group) {
            //     return $query->where('code', '=', Status::STATUS[$status_group['routing']]['collected']);
            // })
            ->whereHas('status', function ($query) use ($status_group) {
                return $query->where('code', '=', Status::STATUS[$status_group['routing']]['inprogress']);
            });

        if (is_callable($add_filter)) {
            $query = $add_filter($query);
            $routing = $query;
        }else{
            $routing = $query->first();
        }

        if(!$routing){
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
            'data' => $routing,
        ];
    }

}
