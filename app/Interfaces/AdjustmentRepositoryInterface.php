<?php

namespace App\Interfaces;

interface AdjustmentRepositoryInterface
{
    public function getAllAdjustment();
    public function dataTableAdjustment();
    public function getAdjustmentById($adjustmentId);
    public function getAdjustmentByCode($code);
    public function getAdjustmentByType($type, array $filter);
    public function getAdjustmentByGroup($group);
    public function deleteAdjustment($adjustmentId);
    public function createAdjustment(array $adjustmentDetails);
    public function updateAdjustment($adjustmentId, array $newDetails);
}