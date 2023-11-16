<?php

namespace App\Interfaces;

interface ReconcileRepositoryInterface
{
    public function getAllReconcile();
    public function dataTableReconcile();
    public function getReconcileById($reconcileId);
    public function getReconcileByRouting($routingId);
    public function deleteReconcile($reconcileId);
    public function createOrUpdateReconcile(array $reconcileDetails);
    public function updateReconcile($reconcileId, array $newDetails);
    public function getRoutingById($reconcileId, array $filter);
    public function generateCode();
    public function getRemainingDeposit($routingId, $totalCodActual);
    public function getAllReconcileByDate($date, $hub);
    public function reportingCod(array $filter);
}