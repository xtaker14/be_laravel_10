<?php

namespace App\Repositories;

use App\Interfaces\CountryRepositoryInterface;
use App\Models\Country;
use Illuminate\Support\Facades\DB;

class CountryRepository implements CountryRepositoryInterface
{
    public function getAllCountry()
    {
        return Country::all();
    }

    public function getPluckCountry($firstColumn, $secondColumn)
    {
        return Country::orderBy('name','asc')->pluck($secondColumn, $firstColumn);
    }

    public function dataTableCountry()
    {
        
    }

    public function getCountryById($countryId)
    {
        return Country::findOrFail($countryId);
    }

    public function deleteCountry($countryId)
    {
        Country::destroy($countryId);
    }

    public function createCountry(array $countryDetails)
    {
        return Country::create($countryDetails);
    }

    public function updateCountry($countryId, array $newDetails)
    {
        return Country::whereId($countryId)->update($newDetails);
    }
    
}