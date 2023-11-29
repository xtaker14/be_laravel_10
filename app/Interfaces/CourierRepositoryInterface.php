<?php

namespace App\Interfaces;

interface CourierRepositoryInterface
{
    public function getAllCourier();
    public function dataTableCourier();
    public function getCourierById($courierId);
    public function deleteCourier($courierId);
    public function createCourier(array $courierDetails);
    public function createCourierImport(array $courierDetails);
    public function updateCourier($courierId, array $newDetails);
    public function getRoutingById($courierId, array $filter);
    public function courierPerformance(array $filter);
    public function getCourierHub($hubId);
    public function getCouriers();
}