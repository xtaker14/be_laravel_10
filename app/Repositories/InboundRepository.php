<?php

namespace App\Repositories;

use App\Interfaces\InboundRepositoryInterface;
use App\Models\Inbound;
use Illuminate\Support\Facades\DB;

class InboundRepository implements InboundRepositoryInterface
{
    public function getAllInbound()
    {
        return Inbound::all();
    }

    public function dataTableInbound()
    {

    }

    public function reportInboundDetail(array $filter)
    {
        return DB::table('inbound')
        ->join('inbounddetail', 'inbound.inbound_id', '=', 'inbounddetail.inbound_id')
        ->join('package', 'inbounddetail.package_id', '=', 'package.package_id')
        ->join('inboundtype', 'inbound.inbound_type_id', '=', 'inboundtype.inbound_type_id')
        ->join('hub', 'inbound.hub_id', '=', 'hub.hub_id')
        ->leftJoin('transfer', function($join) {
            $join->on('transfer.transfer_id', '=', 'inbound.transfer_id');
            $join->where('inboundtype.name', 'TRANSFER');
        })
        ->leftJoin('routingdetail', 'routingdetail.package_id', '=', 'package.package_id')
        ->leftJoin('routing', 'routingdetail.routing_id', '=', 'routing.routing_id')
        ->leftJoin('packagehistory as received', function($join) {
            $join->leftJoin('status as status_received', 'received.status_id', '=', 'status_received.status_id');
            $join->on('received.package_id', '=', 'package.package_id');
            $join->where('status_received.code', 'RECEIVED');
        })
        ->leftJoin('packagehistory as finish', function($join) {
            $join->leftJoin('status as status_finish', 'finish.status_id', '=', 'status_finish.status_id');
            $join->on('finish.package_id', '=', 'package.package_id');
            $join->where('status_finish.code', 'DELIVERED');
        })
        ->select(
            'hub.code as hub_id',
            'hub.name as hub_name', 
            'inbound.code as inbound_id',
            'inboundtype.name as inboundtype', 
            'transfer.code as mbag_code', 
            'routing.code as delivery_record',
            'package.tracking_number', 
            'package.reference_number', 
            'package.total_koli', 
            'package.total_weight', 
            'received.created_date as received_date', 
            'finish.created_date as finish_date', 
            'received.modified_by as received_by'
        )
        ->where(function($q) use($filter){
            if (isset($filter['date']) && $filter['date'] != "") {
                $q->whereDate('inbound.created_date',$filter['date']);
            }
            if (isset($filter['hub']) && $filter['hub'] != "") {
                $q->where('hub.hub_id',$filter['hub']);
            }
            if (isset($filter['type']) && $filter['type'] != "") {
                $q->where('inbound.inbound_type_id',$filter['type']);
            }
        })
        ->orderBy('inbound.inbound_id','asc');
    }

    public function getInboundById($inboundId)
    {
        return Inbound::findOrFail($inboundId);
    }
    public function deleteInbound($inboundId)
    {
        Inbound::destroy($inboundId);
    }
    public function createInbound(array $inboundDetails)
    {
        return Inbound::create($inboundDetails);
    }
    public function updateInbound($inboundId, array $newDetails)
    {
        return Inbound::whereId($inboundId)->update($newDetails);
    }
}