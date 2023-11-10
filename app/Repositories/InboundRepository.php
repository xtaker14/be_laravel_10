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
        ->select('hub.code as hub_id', 'hub.name as hub_name', 'inbound.code as inbound_id', 'inboundtype.name as inboundtype', 'package.tracking_number', 'package.reference_number', 'package.total_koli', 'package.total_weight', 'inbound.created_date', 'package.created_date as finish_date', 'inbound.modified_by')
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