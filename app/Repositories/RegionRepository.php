<?php

namespace App\Repositories;

use App\Interfaces\RegionRepositoryInterface;
use App\Models\Subdistrict;
use Illuminate\Support\Facades\DB;

class RegionRepository implements RegionRepositoryInterface
{
    public function getAllRegion()
    {
        return Subdistrict::all();
    }

    public function dataTableSubdistrict()
    {
        return DB::table('subdistrict')
        ->join('district', 'subdistrict.district_id', '=', 'district.district_id')
        ->join('city', 'district.city_id', '=', 'city.city_id')
        ->join('province', 'city.province_id', '=', 'province.province_id')
        ->select('subdistrict.subdistrict_id', 'city.name as city', 'province.name as province','district.name as district','subdistrict.name as subdistrict');
    }

    public function getRegionById($regionId)
    {
        return Subdistrict::findOrFail($regionId);
    }

    public function deleteRegion($regionId)
    {
        Subdistrict::destroy($regionId);
    }

    public function createRegion(array $regionDetails)
    {
        return Subdistrict::create($regionDetails);
    }

    public function updateRegion($regionId, array $newDetails)
    {
        return Subdistrict::whereId($regionId)->update($newDetails);
    }
    
}