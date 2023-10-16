<?php

namespace App\Repositories;

use App\Interfaces\HubRepositoryInterface;
use App\Models\Hub;
use Illuminate\Support\Facades\DB;

class HubRepository implements HubRepositoryInterface
{
    public function getAllHub()
    {
        return Hub::all();
    }

    public function dataTableHub()
    {
        return DB::table('hub')
        ->leftJoin('hubarea', 'hub.hub_id', '=', 'hubarea.hub_id')
        ->join('subdistrict', 'hub.subdistrict_id', '=', 'subdistrict.subdistrict_id')
        ->join('district', 'subdistrict.district_id', '=', 'district.district_id')
        ->join('city', 'district.city_id', '=', 'city.city_id')
        ->join('province', 'city.province_id', '=', 'province.province_id')
        ->join(DB::raw("(SELECT hub_id, ROW_NUMBER() OVER (ORDER BY hub_id) AS row_index FROM hub) as sub"), 'hub.hub_id', '=', 'sub.hub_id')
        ->join(DB::raw("(SELECT hub_id, CASE WHEN is_active = 1 THEN 'active' ELSE 'inactive' END AS status FROM hub) as sub2"), 'hub.hub_id', '=', 'sub2.hub_id')
        ->select('sub.row_index', 'hub.hub_id', 'hub.code', 'hub.name', 'hub.street_name', 'city.name as city', 'province.name as province','district.name as district','subdistrict.name as subdistrict', 'hub.is_active', 'hub.total_district', 'sub2.status')
        ->groupBy('hub.hub_id');

    }

    public function getHubById($hubId)
    {
        return Hub::findOrFail($hubId);
    }
    public function deleteHub($hubId)
    {
        Hub::destroy($hubId);
    }
    public function createHub(array $hubDetails)
    {
        return Hub::create($hubDetails);
    }
    public function updateHub($hubId, array $newDetails)
    {
        return Hub::whereId($hubId)->update($newDetails);
    }
    
}