<?php

namespace App\Interfaces;

interface PackageRepositoryInterface
{
    public function getAllPackage();
    public function dataTablePackage();
    public function getPackageById($packageId);
    public function deletePackage($packageId);
    public function createPackage(array $packageDetails);
    public function updatePackage($packageId, array $newDetails);
    public function updateStatusPackage($packageId, $statusCode);
}