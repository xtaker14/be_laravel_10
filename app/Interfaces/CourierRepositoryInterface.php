<?php

namespace App\Interfaces;

interface CourierRepositoryInterface
{
    public function getAllCourier();
    public function dataTableCourier();
    public function getCourierById($courierId);
    public function deleteCourier($courierId);
    public function createCourier(array $courierDetails);
    public function updateCourier($courierId, array $newDetails);
}