<?php

namespace App\Interfaces;

interface RoutingRepositoryInterface
{
    public function getAllRouting();
    public function getRoutingById($routingId);
    public function getRoutingByCode($code);
    public function deleteRouting($routingId);
    public function createRouting(array $routingDetails);
    public function updateRouting($routingId, array $newDetails);
    public function updateStatusRouting($routingId, $statusCode);
}