<?php

namespace App\Repositories;

use App\Interfaces\RoutingRepositoryInterface;
use App\Models\Routing;
use App\Models\Status;
use App\Models\Package;
use Illuminate\Support\Facades\DB;

class RoutingRepository implements RoutingRepositoryInterface
{
    public function getAllRouting()
    {
        return Routing::all();
    }

    public function getRoutingById($routingId)
    {
        return Routing::findOrFail($routingId);
    }

    public function getRoutingByCode($code)
    {
        $result = [];

        $data = Routing::where('code',$code)->first();

        if ($data) {
            $delivered_status = Status::whereIn('code', ['DELIVERED','COLLECTED'])->pluck('status_id','status_id');
            $undelivered_status = Status::whereNotIn('code', ['DELIVERED','COLLECTED'])->pluck('status_id','status_id');
            $collected_status = Status::where('code', 'COLLECTED')->first()->status_id;

            $package_pluck = $data->routingdetails()->pluck('package_id','package_id');

            $result['data'] = $data;
            
            $result['waybill'] = $data->routingdetails()->count();

            $result['waybill_cod'] = Package::whereIn('package_id',$package_pluck)
            ->where('cod_price','>',0)
            ->count();

            $result['cod_delivered'] = Package::whereIn('package_id',$package_pluck)
            ->where('cod_price','>',0)
            ->whereIn('status_id',$delivered_status)
            ->count();

            $result['cod_undelivered'] = Package::whereIn('package_id',$package_pluck)
            ->where('cod_price','>',0)
            ->whereIn('status_id',$undelivered_status)
            ->count();

            $result['value_cod_delivered'] = Package::whereIn('package_id',$package_pluck)
            ->where('cod_price','>',0)
            ->whereIn('status_id',$delivered_status)
            ->sum('cod_price');

            $result['value_cod_uncollected'] = Package::whereIn('package_id',$package_pluck)
            ->where('cod_price','>',0)
            ->whereIn('status_id',$delivered_status)
            ->whereDoesntHave('packagehistories', function ($query) use($collected_status) {
                $query->where('status_id', $collected_status);
            })
            ->sum('cod_price');

            $result['value_cod_total'] = Package::whereIn('package_id',$package_pluck)
            ->where('cod_price','>',0)
            ->sum('cod_price');
            
            $result['list_waybill'] = Package::whereIn('package_id',$package_pluck)
            ->where('cod_price','>',0)
            ->whereIn('status_id',$delivered_status)
            ->get();

            $result['list_waybill_collected'] = Package::whereIn('package_id',$package_pluck)
            ->where('cod_price','>',0)
            ->where('status_id',$collected_status)
            ->get();
        }
        return $result;
    }

    public function deleteRouting($routingId)
    {
        Routing::destroy($routingId);
    }

    public function createRouting(array $routingDetails)
    {
        return Routing::create($routingDetails);
    }

    public function updateRouting($routingId, array $newDetails)
    {
        return Routing::where('routing_id', $routingId)->update($newDetails);
    }
    
}