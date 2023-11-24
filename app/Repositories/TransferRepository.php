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
        ->select('transfer.transfer_id', 'transfer.code', 'status.name as status', 'status.label as status_label', 'from_hub.name as hub_origin', 'to_hub.name as hub_dest')
        ->selectRaw('COUNT(package.package_id) as total_waybill')
        ->selectRaw('SUM(package.total_weight) as total_weight')
        ->selectRaw('SUM(package.total_koli) as total_koli')
        ->selectRaw('SUM(package.cod_price) as total_cod')
        ->whereDate('transfer.created_date', $date == "" ? date('Y-m-d'):$date)
        ->groupBy('transfer.transfer_id')
        ->get();
    }
    
    public function reportTransfer(array $filter)
    {
        return DB::table('transfer')
        ->join('transferdetail', 'transfer.transfer_id', '=', 'transferdetail.transfer_id')
        ->join('package', 'transferdetail.package_id', '=', 'package.package_id')
        ->join('hub as fromhub', 'transfer.from_hub_id', '=', 'fromhub.hub_id')
        ->join('hub as tohub', 'transfer.to_hub_id', '=', 'tohub.hub_id')
        ->join('status', 'transfer.status_id', '=', 'status.status_id')
        ->leftJoin('inbound', 'inbound.transfer_id', '=', 'transfer.transfer_id')
        ->select('transfer.code as mbag_id', 'status.name as mbag_status', 'package.tracking_number', 'package.reference_number', 'package.recipient_city', 'package.recipient_district', 'package.total_koli', 'package.total_weight', 'fromhub.name as fromhub', 'tohub.name as tohub', 'transfer.created_date as transfer_date', 'transfer.created_by as transfer_by', 'inbound.created_date as intransit_by', 'inbound.created_by as intransit_date')
        ->where(function($q) use($filter){
            if (isset($filter['date']) && $filter['date'] != "") {
                $q->whereDate('transfer.created_date',$filter['date']);
            }
            if (isset($filter['tohub']) && $filter['tohub'] != "") {
                $q->where('fromhub.hub_id',$filter['tohub']);
            }
            if (isset($filter['fromhub']) && $filter['fromhub'] != "") {
                $q->where('tohub.hub_id',$filter['fromhub']);
            }
        })
        ->orderBy('transfer.transfer_id','asc');
    }
}