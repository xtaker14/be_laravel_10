<?php

namespace App\Repositories;

use App\Interfaces\InboundTypeRepositoryInterface;
use App\Models\InboundType;
use Illuminate\Support\Facades\DB;

class InboundTypeRepository implements InboundTypeRepositoryInterface
{
    public function getAllInboundType()
    {
        return InboundType::all();
    }

    public function dataTableInboundType()
    {

    }

    public function getInboundTypeById($inboundTypeId)
    {
        return InboundType::findOrFail($inboundTypeId);
    }
    public function deleteInboundType($inboundTypeId)
    {
        InboundType::destroy($inboundTypeId);
    }
    public function createInboundType(array $inboundTypeDetails)
    {
        return InboundType::create($inboundTypeDetails);
    }
    public function updateInboundType($inboundTypeId, array $newDetails)
    {
        return InboundType::whereId($inboundTypeId)->update($newDetails);
    }
}