<?php

namespace App\Interfaces;

interface ProvinceRepositoryInterface
{
    public function getAllProvince();
    public function getPluckProvince($firstColumn, $secondColumn);
    public function dataTableProvince();
    public function getProvinceById($provinceId);
    public function deleteProvince($provinceId);
    public function createProvince(array $provinceDetails);
    public function updateProvince($provinceId, array $newDetails);
}