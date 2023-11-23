<?php

namespace App\Interfaces;

interface CountryRepositoryInterface
{
    public function getAllCountry();
    public function getPluckCountry($firstColumn, $secondColumn);
    public function dataTableCountry();
    public function getCountryById($countryId);
    public function deleteCountry($countryId);
    public function createCountry(array $countryDetails);
    public function updateCountry($countryId, array $newDetails);
}