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
        ->leftJoin('subdistrict', 'hub.subdistrict_id', '=', 'subdistrict.subdistrict_id')
        ->leftJoin('district', 'subdistrict.district_id', '=', 'district.district_id')
        ->leftJoin('city', 'district.city_id', '=', 'city.city_id')
        ->leftJoin('province', 'city.province_id', '=', 'province.province_id')
        ->select('hub.hub_id', 'hub.name', 'hub.street_name', 'city.name as city', 'province.name as province','district.name as district','subdistrict.name as subdistrict', 'hub.is_active', DB::raw('COUNT(subdistrict.subdistrict_id) as total_district'))
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