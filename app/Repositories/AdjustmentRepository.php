<?php

namespace App\Repositories;

use App\Interfaces\AdjustmentRepositoryInterface;
use App\Models\Adjustment;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdjustmentRepository implements AdjustmentRepositoryInterface
{
    public function getAllAdjustment()
    {
        return Adjustment::all();
    }

    public function dataTableAdjustment()
    {

    }

    public function getAdjustmentById($adjustmentId)
    {
        return Adjustment::findOrFail($adjustmentId);
    }
    
    public function getAdjustmentByCode($code)
    {
        return Adjustment::where('code', $code)->first();
    }

    public function getAdjustmentByType($type, array $filter)
    {
        return Adjustment::where('type', $type)
        ->where(function($q) use($filter, $type){
            if (isset($filter['date']) && $filter['date'] != "") {
                $q->whereDate('created_date', Carbon::parse($filter['date']));
            }
            if (isset($filter['hub']) && $filter['hub'] != "") {
                if ($type != 'REJECT_MASTER_WAYBILL') {
                    $q->whereRelation('package', 'hub_id', $filter['hub']);
                } else {
                    $q->whereRelation('masterWaybill.package', 'hub_id', $filter['hub']);
                }
            }
        })
        ->orderBy('adjustment_id','desc')
        ->get();
    }
    public function getAdjustmentByGroup($group)
    {
        return Adjustment::where('adjustment_group',$group)->orderBy('adjustment_order','asc')->get();
    }
    public function deleteAdjustment($adjustmentId)
    {
        Adjustment::destroy($adjustmentId);
    }
    public function createAdjustment(array $adjustmentDetails)
    {
        return Adjustment::create($adjustmentDetails);
    }
    public function updateAdjustment($adjustmentId, array $newDetails)
    {
        return Adjustment::whereId($adjustmentId)->update($newDetails);
    }
}