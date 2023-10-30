<?php

namespace App\Repositories;

use App\Interfaces\RoutingRepositoryInterface;
use App\Models\Routing;
use App\Models\Reconcile;
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
            $delivered_status = Status::whereIn('code', ['DELIVERED'])->pluck('status_id','status_id');
            $undelivered_status = Status::whereNotIn('code', ['DELIVERED'])->pluck('status_id','status_id');
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

            $cod_delivered_total = Package::whereIn('package_id',$package_pluck)
            ->where('cod_price','>',0)
            ->whereIn('status_id',$delivered_status)
            ->sum('cod_price');

            $result['value_cod_delivered'] = $cod_delivered_total;

            $reconcile = Reconcile::where('routing_id',$data->routing_id)->first();
            if ($reconcile) {
                $result['value_cod_uncollected'] = $cod_delivered_total - $reconcile->actual_deposit;

                $result['list_waybill_collected'] = Package::whereIn('package_id',$package_pluck)
                ->where('cod_price','>',0)
                ->get();

            } else {
                $result['value_cod_uncollected'] = $cod_delivered_total;

                $result['list_waybill_collected'] = [];
            }

            $result['value_cod_total'] = Package::whereIn('package_id',$package_pluck)
            ->where('cod_price','>',0)
            ->sum('cod_price');
            
            $result['list_waybill'] = Package::whereIn('package_id',$package_pluck)
            ->where('cod_price','>',0)
            ->whereIn('status_id',$delivered_status)
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

    public function updateStatusRouting($routingId, $statusCode)
    {
        $status = Status::where('code', $statusCode)->first()->status_id;

        return Routing::where('routing_id', $routingId)->update(['status_id' => $status]);
    }
    
}