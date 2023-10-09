<?php

namespace App\Interfaces;

interface VendorRepositoryInterface
{
    public function getAllVendor();
    public function dataTableVendor();
    public function getVendorById($vendorId);
    public function deleteVendor($vendorId);
    public function createVendor(array $vendorDetails);
    public function updateVendor($vendorId, array $newDetails);
}