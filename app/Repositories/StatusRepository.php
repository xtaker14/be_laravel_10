<?php

namespace App\Repositories;

use App\Interfaces\StatusRepositoryInterface;
use App\Models\Status;
use Illuminate\Support\Facades\DB;

class StatusRepository implements StatusRepositoryInterface
{
    public function getAllStatus()
    {
        return Status::all();
    }

    public function dataTableStatus()
    {

    }

    public function getStatusById($statusId)
    {
        return Status::findOrFail($statusId);
    }
    public function getStatusByCode($code)
    {
        return Status::where('code', $code)->first();
    }
    public function getStatusByGroup($group)
    {
        return Status::where('status_group',$group)->orderBy('status_order','asc')->get();
    }
    public function deleteStatus($statusId)
    {
        Status::destroy($statusId);
    }
    public function createStatus(array $statusDetails)
    {
        return Status::create($statusDetails);
    }
    public function updateStatus($statusId, array $newDetails)
    {
        return Status::whereId($statusId)->update($newDetails);
    }
}