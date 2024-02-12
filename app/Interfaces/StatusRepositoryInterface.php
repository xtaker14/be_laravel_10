<?php

namespace App\Interfaces;

interface StatusRepositoryInterface
{
    public function getAllStatus();
    public function dataTableStatus();
    public function getStatusById(int $statusId);
    public function getStatusByCode(string $code);
    public function getStatusByGroup(string $group);
    public function deleteStatus(int $statusId);
    public function createStatus(array $statusDetails);
    public function updateStatus(int $statusId, array $newDetails);
}