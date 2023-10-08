<?php

namespace App\Repositories;

use App\Interfaces\RegionRepositoryInterface;
use App\Models\Subdistrict;

class RegionRepository implements RegionRepositoryInterface
{
    public function getAllRegion()
    {
        return Subdistrict::all();
    }

    public function selectAllSubdistrict()
    {
        return Subdistrict::select("*");
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