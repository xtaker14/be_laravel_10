<?php

namespace App\Repositories;

use App\Interfaces\ProvinceRepositoryInterface;
use App\Models\Province;
use Illuminate\Support\Facades\DB;

class ProvinceRepository implements ProvinceRepositoryInterface
{
    public function getAllProvince()
    {
        return Province::all();
    }

    public function getPluckProvince($firstColumn, $secondColumn)
    {
        return Province::orderBy('name','asc')->pluck($secondColumn, $firstColumn);
    }

    public function dataTableProvince()
    {
        
    }

    public function getProvinceById($provinceId)
    {
        return Province::findOrFail($provinceId);
    }

    public function deleteProvince($provinceId)
    {
        Province::destroy($provinceId);
    }

    public function createProvince(array $provinceDetails)
    {
        return Province::create($provinceDetails);
    }

    public function updateProvince($provinceId, array $newDetails)
    {
        return Province::whereId($provinceId)->update($newDetails);
    }
    
}