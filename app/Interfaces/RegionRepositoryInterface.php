<?php

namespace App\Interfaces;

interface RegionRepositoryInterface
{
    public function getAllRegion();
    public function selectAllSubdistrict();
    public function getRegionById($regionId);
    public function deleteRegion($regionId);
    public function createRegion(array $regionDetails);
    public function updateRegion($regionId, array $newDetails);
}