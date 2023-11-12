<?php

namespace App\Interfaces;

interface TransferRepositoryInterface
{
    public function getAllTransfer();
    public function dataTableTransfer($date);
    public function reportTransfer(array $filter);
}