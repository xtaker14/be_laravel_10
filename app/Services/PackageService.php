<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

use App\Models\User;
use App\Models\Status;
use App\Models\Package;
use App\Helpers\Main;

class PackageService
{
    private $auth;

    public function __construct($auth='api')
    { 
        $this->auth = auth($auth);
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

}
