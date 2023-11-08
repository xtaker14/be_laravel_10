<?php

namespace App\Interfaces;

interface InboundRepositoryInterface
{
    public function getAllInbound();
    public function dataTableInbound();
    public function reportInboundDetail(array $filter);
    public function getInboundById($inboundId);
    public function deleteInbound($inboundId);
    public function createInbound(array $inboundDetails);
    public function updateInbound($inboundId, array $newDetails);
}