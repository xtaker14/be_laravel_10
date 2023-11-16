<?php

namespace App\Repositories;

use App\Interfaces\ReconcileRepositoryInterface;
use App\Models\Courier;
use App\Models\Reconcile;
use App\Models\Status;
use Illuminate\Support\Facades\DB;
use Auth;
use Carbon\Carbon;

class ReconcileRepository implements ReconcileRepositoryInterface
{
    public function getAllReconcile()
    {
        return Reconcile::all();
    }

    public function dataTableReconcile()
    {
        return DB::table('reconcile');
    }

    public function getReconcileById($reconcileId)
    {
        return Reconcile::findOrFail($reconcileId);
    }

    public function getReconcileByRouting($routingId)
    {
        return Reconcile::where('routing_id', $routingId)->first();
    }

    public function deleteReconcile($reconcileId)
    {
        Reconcile::destroy($reconcileId);
    }

    public function createOrUpdateReconcile(array $reconcileDetails)
    {
        $check_data = Reconcile::where('routing_id', $reconcileDetails['routing_id'])->first();
        if ($check_data) {
            $reconcile = Reconcile::find($check_data->reconcile_id);
            $reconcile->actual_deposit = $reconcile->actual_deposit + $reconcileDetails['actual_deposit'];
            $reconcile->modified_date = Carbon::now();
            $reconcile->modified_by = Auth::user()->full_name;
        } else {
            $reconcile = new Reconcile;
            $reconcile->code = $reconcileDetails['code'];
            $reconcile->routing_id = $reconcileDetails['routing_id'];
            $reconcile->unique_number = $reconcileDetails['unique_number'];
            $reconcile->total_deposit = $reconcileDetails['total_deposit'];
            $reconcile->actual_deposit = $reconcileDetails['actual_deposit'];
            $reconcile->created_date = Carbon::now();
            $reconcile->modified_date = Carbon::now();
            $reconcile->created_by = Auth::user()->full_name;
            $reconcile->modified_by = Auth::user()->full_name;
        }
        $reconcile->remaining_deposit = $reconcileDetails['remaining_deposit'];
        $reconcile->save();

        return $reconcile;
    }

    public function updateReconcile($reconcileId, array $newDetails)
    {
        return Reconcile::whereId($reconcileId)->update($newDetails);
    }

    public function getRoutingById($reconcileId, array $filter)
    {
        $routingStatus = Status::where('code', 'ROUTING')->first()->status_id;

        return Reconcile::find($reconcileId)
        ->routings()
        ->where('status_id',$routingStatus)
        ->orderBy('created_date','asc')
        ->first();
    }

    public function generateCode()
    {
        // Get the current date
        $date = now();

        // Format the date as desired (e.g., 'Ymd' for YYYYMMDD)
        $prefixCode = 'RCN-DTX'.rand(10,99).$date->format('ymd');

        // Find the latest item created on this date
        $latestItem = Reconcile::whereDate('created_date', $date->toDateString())->count();

        $numericPart = $latestItem + 1;

        return $prefixCode. str_pad($numericPart, 3, '0', STR_PAD_LEFT);
    }

    public function getRemainingDeposit($routingId, $totalCodActual)
    {
        $remaining_deposit = $totalCodActual;

        $reconcile = Reconcile::where('routing_id', $routingId)->first();
        if ($reconcile) {
            $remaining_deposit = $reconcile->remaining_deposit;
        }

        return $remaining_deposit;
    }
  
    public function getAllReconcileByDate($date, $hub)
    {
        return DB::table('reconcile')
        ->join('routing', 'reconcile.routing_id', '=', 'routing.routing_id')
        ->join('courier', 'routing.courier_id', '=', 'courier.courier_id')
        ->join('userspartner', 'courier.users_partner_id', '=', 'userspartner.users_partner_id')
        ->join('users', 'userspartner.users_id', '=', 'users.users_id')
        ->join('status', 'routing.status_id', '=', 'status.status_id')
        ->select('reconcile.reconcile_id', 'routing.code as dr_code', 'routing.routing_id', 'users.full_name', 'reconcile.total_deposit', 'reconcile.actual_deposit', 'reconcile.modified_by', 'reconcile.modified_date', 'status.label as status_label', 'status.name as status')
        ->where('status.name', 'Collected')
        ->where(function($q) use($date, $hub){
            if ($date != "") {
                $q->whereDate('reconcile.created_date', $date);
            }

            if ($hub != "") {
                $q->where('courier.hub_id', $hub);
            }
        })
        ->get();
    }

    public function reportingCod(array $filter)
    {
        return DB::table('reconcile')
        ->join('routing', 'reconcile.routing_id', '=', 'routing.routing_id')
        ->join('courier', 'routing.courier_id', '=', 'courier.courier_id')
        ->join('hub', 'courier.hub_id', '=', 'hub.hub_id')
        ->join('userspartner', 'courier.users_partner_id', '=', 'userspartner.users_partner_id')
        ->join('users', 'userspartner.users_id', '=', 'users.users_id')
        ->join('status', 'routing.status_id', '=', 'status.status_id')
        ->join('routingdelivery', 'routingdelivery.routing_id', '=', 'routing.routing_id')
        ->join('routingdetail', 'routingdetail.routing_id', '=', 'routing.routing_id')
        ->join('package', 'routingdetail.package_id', '=', 'package.package_id')
        ->select('hub.code as hub_code', 'hub.name as hub_name', 'reconcile.code as collection_code', 'users.full_name as courier_name', 'courier.code as courier_code', 'routing.code as dr_code', 'reconcile.total_deposit', 'reconcile.actual_deposit', 'reconcile.created_date', 'reconcile.created_by')
        ->where('status.name', 'Collected')
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
        ->selectRaw('COUNT(package.package_id) as total_waybill')
        ->selectRaw('COUNT(package.package_id) as total_waybill_cod')
        ->selectRaw('COUNT(package.package_id) as total_waybill_non')
        ->orderBy('routing.routing_id', 'asc')
        ->groupBy('routing.routing_id');
    }
}