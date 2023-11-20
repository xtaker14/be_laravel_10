<?php

namespace App\Repositories;

use App\Interfaces\CourierRepositoryInterface;
use App\Models\Courier;
use App\Models\Status;
use Illuminate\Support\Facades\DB;

class CourierRepository implements CourierRepositoryInterface
{
    public function getAllCourier()
    {
        return Courier::all();
    }

    public function dataTableCourier()
    {
        return DB::table('users')
        ->join('role', 'users.role_id', '=', 'role.role_id')
        ->join('userspartner', 'users.users_id', '=', 'userspartner.users_id')
        ->join('courier', 'userspartner.users_partner_id', '=', 'courier.users_partner_id')
        ->join('hub', 'courier.hub_id', '=', 'hub.hub_id')
        ->join('partner', 'courier.partner_id', '=', 'partner.partner_id')
        ->join(DB::raw("(SELECT users_id, ROW_NUMBER() OVER (ORDER BY users_id) AS row_index FROM users) as sub"), 'users.users_id', '=', 'sub.users_id')
        ->join(DB::raw("(SELECT users_id, CASE WHEN is_active = 1 THEN 'active' ELSE 'inactive' END AS status FROM users) as sub2"), 'users.users_id', '=', 'sub2.users_id')
        ->select('sub.row_index', 'courier.courier_id', 'users.full_name', 'users.is_active', 'courier.code as code','courier.vehicle_type as vehicle_type','courier.vehicle_number as vehicle_number','courier.phone as phone', 'partner.name as vendor_name', 'hub.name as hub_name', 'sub2.status')
        ->where('role.name','COURIER');
    }

    public function getCourierById($courierId)
    {
        return Courier::findOrFail($courierId);
    }

    public function deleteCourier($courierId)
    {
        Courier::destroy($courierId);
    }

    public function createCourier(array $courierDetails)
    {
        return Courier::create($courierDetails);
    }

    public function updateCourier($courierId, array $newDetails)
    {
        return Courier::whereId($courierId)->update($newDetails);
    }

    public function getRoutingById($courierId, array $filter)
    {
        $routingStatus = Status::whereIn('code', ['INPROGRESS'])->pluck('status_id','status_id');
        $collectedStatus = Status::where('code', 'COLLECTED')->first()->status_id;

        $routing = Courier::find($courierId)
        ->routings()
        ->whereIn('status_id',$routingStatus)
        ->has('routingdetails')
        ->orderBy('created_date','desc')
        ->first();

        if (!$routing) {
            $routing = Courier::find($courierId)
            ->routings()
            ->where('status_id',$collectedStatus)
            ->orderBy('created_date','desc')
            ->first();
        }

        return $routing;
    }
    
    public function courierPerformance(array $filter)
    {
        $status = Status::pluck('status_id','code');

        return DB::table('routing')
        ->join('routingdetail', 'routing.routing_id', '=', 'routingdetail.routing_id')
        ->join('package', 'routingdetail.package_id', '=', 'package.package_id')
        ->join('status', 'routing.status_id', '=', 'status.status_id')
        ->join('courier', 'routing.courier_id', '=', 'courier.courier_id')
        ->join('hub', 'courier.hub_id', '=', 'hub.hub_id')
        ->join('userspartner', 'courier.users_partner_id', '=', 'userspartner.users_partner_id')
        ->join('users', 'userspartner.users_id', '=', 'users.users_id')
        ->leftJoin('routingdelivery', 'routing.routing_id', '=', 'routingdelivery.routing_id')
        ->leftJoin('reconcile', 'routing.routing_id', '=', 'reconcile.routing_id')
        ->leftJoin('routinghistory', function($join) use($status) {
            $join->on('routinghistory.routing_id', '=', 'routing.routing_id');
            $join->where('routinghistory.status_id', isset($status['INPROGRESS']) ? $status['INPROGRESS'] : 0);
        })
        ->select(
            'hub.code as hub_code',
            'hub.name as hub_name',
            'users.full_name as courier_name',
            'courier.code as courier_code',
            'routing.code as routing_code',
            'status.name as status',
            'routinghistory.created_date as pickup_date',
            'reconcile.created_date as collected_date'
        )
        ->selectRaw('COUNT(package.package_id) as total_waybill')
        ->selectRaw('SUM(package.total_weight) as total_weight')
        ->selectRaw('SUM(package.total_koli) as total_koli')
        ->where(function($q) use($filter){
            if (isset($filter['date']) && $filter['date'] != "") {
                $q->whereDate('routing.created_date',$filter['date']);
            }
            if (isset($filter['hub']) && $filter['hub'] != "") {
                $q->where('hub.hub_id',$filter['hub']);
            }
        })
        ->orderBy('routing.routing_id', 'asc')
        ->groupBy('routing.routing_id');
    }

    public function getCourierHub($hubId)
    {
        return DB::table('courier')
        ->select('courier.courier_id', 'users.full_name as name')
        ->join('hub', 'courier.hub_id', '=', 'hub.hub_id')
        ->join('userspartner', 'courier.users_partner_id', '=', 'userspartner.users_partner_id')
        ->join('users', 'userspartner.users_id', '=', 'users.users_id')
        ->where('hub.hub_id', $hubId)
        ->get();
    }

    public function getCouriers()
    {
        return DB::table('courier')
        ->select('courier.courier_id', 'users.full_name as name')
        ->join('userspartner', 'courier.users_partner_id', '=', 'userspartner.users_partner_id')
        ->join('users', 'userspartner.users_id', '=', 'users.users_id')
        ->get();
    }
}