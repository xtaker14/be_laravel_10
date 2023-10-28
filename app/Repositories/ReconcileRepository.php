<?php

namespace App\Repositories;

use App\Interfaces\ReconcileRepositoryInterface;
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
        $prefixCode = 'RCN-DTX'.$date->format('Ymd');

        // Find the latest item created on this date
        $latestItem = Reconcile::whereDate('created_date', $date->toDateString())->first();

        // Extract the numeric part from the latest code, if it exists
        $numericPart = $latestItem ? intval(substr($latestItem->code, strlen($prefixCode))) + 1 : 1;

        return $prefixCode . str_pad($numericPart, 4, '0', STR_PAD_LEFT);
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
    
}