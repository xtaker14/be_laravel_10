<?php

namespace App\Interfaces;

interface StatusRepositoryInterface
{
    public function getAllStatus();
    public function dataTableStatus();
    public function getStatusById($statusId);
    public function getStatusByGroup($group);
    public function deleteStatus($statusId);
    public function createStatus(array $statusDetails);
    public function updateStatus($statusId, array $newDetails);
}