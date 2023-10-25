<?php

namespace App\Repositories;

use App\Interfaces\ReconcileRepositoryInterface;
use App\Models\Courier;
use App\Models\Status;
use Illuminate\Support\Facades\DB;

class ReconcileRepository implements ReconcileRepositoryInterface
{
    public function getAllReconcile($date)
    {
        return DB::table('reconcile')
        ->join('routing', 'reconcile.routing_id', '=', 'routing.routing_id')
        ->join('courier', 'routing.courier_id', '=', 'courier.courier_id')
        ->join('userspartner', 'courier.users_partner_id', '=', 'userspartner.users_partner_id')
        ->join('users', 'userspartner.users_id', '=', 'users.users_id')
        ->join('status', 'routing.status_id', '=', 'status.status_id')
        ->select('routing.code as dr_code', 'users.full_name', 'reconcile.total_deposit', 'reconcile.actual_deposit', 'reconcile.modified_by', 'reconcile.modified_date', 'status.label as status_label', 'status.name as status')
        ->where('status.name', 'Collected')
        ->whereDate('reconcile.created_date', $date == "" ? date('Y-m-d'):$date)
        ->get();
    }    
}