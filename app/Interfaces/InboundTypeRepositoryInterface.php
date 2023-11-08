<?php

namespace App\Interfaces;

interface InboundTypeRepositoryInterface
{
    public function getAllInboundType();
    public function dataTableInboundType();
    public function getInboundTypeById($inboundTypeId);
    public function deleteInboundType($inboundTypeId);
    public function createInboundType(array $inboundTypeDetails);
    public function updateInboundType($inboundTypeId, array $newDetails);
}