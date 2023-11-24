<?php

namespace App\Repositories;

use App\Interfaces\RoutingRepositoryInterface;
use App\Models\Routing;
use App\Models\RoutingHistory;
use App\Models\Reconcile;
use App\Models\Status;
use App\Models\Package;
use Illuminate\Support\Facades\DB;
use Auth;
use Carbon\Carbon;

class RoutingRepository implements RoutingRepositoryInterface
{
    public function getAllRouting()
    {
        return Routing::all();
    }

    public function countRouting()
    {
        return Routing::count();
    }

    public function getRoutingById($routingId)
    {
        return Routing::findOrFail($routingId);
    }

    public function getRoutingInformation($code)
    {
        $data = [];

        $routing = Routing::where('code',$code)->first();
        if ($routing) {
            $delivered_status = Status::where('code', 'DELIVERED')->first();
            $undelivered_status = Status::whereIn('code', ['UNDELIVERED','ONDELIVERY','ROUTING','ENTRY'])->pluck('status_id','status_id');
            $return_status = Status::where('code', 'RETURN')->first();

            $data['courier_name'] = $routing->courier->userpartner->user->full_name;
            $data['timestamp_created'] = Carbon::parse($routing->created_date)->format('d/m/Y H:i');
            $data['total_waybill'] = $routing->routingdetails()->count();
            $data['total_delivered'] = $routing->routingdetails()
            ->join('package', 'routingdetail.package_id', '=', 'package.package_id')
            ->where('package.status_id', $delivered_status->status_id)
            ->count();
            $data['total_undelivered'] = $routing->routingdetails()
            ->join('package', 'routingdetail.package_id', '=', 'package.package_id')
            ->whereIn('package.status_id', $undelivered_status)
            ->count();
            $data['total_return'] = $routing->routingdetails()
            ->join('package', 'routingdetail.package_id', '=', 'package.package_id')
            ->where('package.status_id', $return_status->status_id)
            ->count();
            $data['destination_hub'] = $routing->spot->hub->name;

            $list_waybill = [];

            $details = $routing->routingdetails()->orderBy('routing_detail_id','asc')->get();
            foreach ($details as $key => $detail) {
                $list_waybill[$key]['waybill'] = $detail->package->tracking_number;
                $list_waybill[$key]['order_code'] = $detail->package->reference_number;   
                $list_waybill[$key]['last_status'] = $detail->package->status->name;      
            }

            $data['list_waybill'] = $list_waybill;
        }

        return $data;
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
                ->whereIn('status_id',$delivered_status)
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
        DB::beginTransaction();

        try {
            $status = Status::where('code', $statusCode)->first()->status_id;

            $routing = Routing::find($routingId);
            $routing->status_id = $status;
            if ($routing->save()) {
                $history = new RoutingHistory;
                $history->routing_id = $routing->routing_id;
                $history->status_id = $routing->status_id;
                $history->created_date = Carbon::now();
                $history->modified_date = Carbon::now();
                $history->created_by = Auth::user()->full_name;
                $history->modified_by = Auth::user()->full_name;
                $history->save();

                DB::commit();

                return $routing;
            } else {
                DB::rollBack();

                return false;
            }
        } catch (\Exception $e) {
            DB::rollBack();
            
            return false;
        }
    }
    
    public function reportingdetailRecord(array $filter)
    {
        return DB::table('routing')
        ->join('courier', 'routing.courier_id', '=', 'courier.courier_id')
        ->join('userspartner', 'courier.users_partner_id', '=', 'userspartner.users_partner_id')
        ->join('users', 'userspartner.users_id', '=', 'users.users_id')
        ->join('routingdetail', 'routingdetail.routing_id', '=', 'routing.routing_id')
        ->join('package', 'routingdetail.package_id', '=', 'package.package_id')
        ->join('hub', 'package.hub_id', '=', 'hub.hub_id')
        ->join('status as rt_status', 'routing.status_id', '=', 'rt_status.status_id')
        ->join('status as pg_status', 'package.status_id', '=', 'pg_status.status_id')
        ->select('hub.code as hub_code', 'hub.name as hub_name', 'routing.code as dr_code', 'rt_status.name as routing_status', 'package.tracking_number', 'package.reference_number', 'package.total_koli', 'package.total_weight', 'pg_status.name as package_status', 'routing.created_date', 'routing.created_by', 'users.full_name as assigned_to')
        ->where(function($q) use($filter){
            if (isset($filter['date']) && $filter['date'] != "") {
                $q->whereDate('routing.created_date',$filter['date']);
            }
            if (isset($filter['hub']) && $filter['hub'] != "") {
                $q->where('hub.hub_id',$filter['hub']);
            }
            if (isset($filter['courier']) && $filter['courier'] != "") {
                $q->where('courier.courier_id',$filter['courier']);
            }
        })
        ->orderBy('routing.routing_id','desc');
    }
}