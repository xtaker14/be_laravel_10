<?php

namespace App\Repositories;

use App\Interfaces\StatusRepositoryInterface;
use App\Models\Status;
use Illuminate\Support\Facades\DB;

class StatusRepository implements StatusRepositoryInterface
{
    public function getAllStatus()
    {
        return Status::all();
    }

    public function dataTableStatus()
    {

    }

    public function getStatusById($statusId)
    {
        return Status::findOrFail($statusId);
    }
    public function getStatusByGroup($group)
    {
        return Status::where('status_group',$group)->orderBy('status_order','asc')->get();
    }
    public function deleteStatus($statusId)
    {
        Status::destroy($statusId);
    }
    public function createStatus(array $statusDetails)
    {
        return Status::create($statusDetails);
    }
    public function updateStatus($statusId, array $newDetails)
    {
        return Status::whereId($statusId)->update($newDetails);
    }
    
    public function getStatusRegion($statusName)
    {
        return DB::table('status as a')
        ->select('a.status_id', 'a.postcode', 'a.coordinate', 'b.name as subdistrict', 'c.name as district', 'd.name as city', 'e.name as province', 'f.name as country')
        ->join('subdistrict as b', 'a.subdistrict_id','=','b.subdistrict_id')
        ->join('district as c', 'b.district_id','=','c.district_id')
        ->join('city as d', 'c.city_id','=','d.city_id')
        ->join('province as e', 'd.province_id','=','e.province_id')
        ->join('country as f', 'e.country_id','=','f.country_id')
        ->where('a.name', $statusName)->first();
    }
}