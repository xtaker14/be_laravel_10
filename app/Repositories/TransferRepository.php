<?php

namespace App\Repositories;

use App\Interfaces\TransferRepositoryInterface;
use App\Models\Transfer;
use Illuminate\Support\Facades\DB;

class TransferRepository implements TransferRepositoryInterface
{
    public function getAllTransfer()
    {
        return Transfer::all();
    }

    public function dataTableTransfer($date)
    {
        return DB::table('transfer')
        ->join('transferdetail', 'transfer.transfer_id', '=', 'transferdetail.transfer_id')
        ->join('package', 'transferdetail.package_id', '=', 'package.package_id')
        ->join('status', 'transfer.status_id', '=', 'status.status_id')
        ->join('hub as from_hub', 'transfer.from_hub_id', '=', 'from_hub.hub_id')
        ->join('hub as to_hub', 'transfer.to_hub_id', '=', 'to_hub.hub_id')
        ->select('transfer.code', 'status.name as status', 'status.label as status_label', 'from_hub.name as hub_origin', 'to_hub.name as hub_dest')
        ->selectRaw('COUNT(package.package_id) as total_waybill')
        ->selectRaw('SUM(package.total_weight) as total_weight')
        ->selectRaw('SUM(package.total_koli) as total_koli')
        ->selectRaw('SUM(package.cod_price) as total_cod')
        ->whereDate('transfer.created_date', $date == "" ? date('Y-m-d'):$date)
        ->groupBy('transfer.transfer_id')
        ->get();
    }
    
}