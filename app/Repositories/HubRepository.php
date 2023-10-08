<?php

namespace App\Repositories;

use App\Interfaces\HubRepositoryInterface;
use App\Models\Hub;

class HubRepository implements HubRepositoryInterface
{
    public function getAllHub()
    {
        return Hub::all();
    }

    public function selectAllHub()
    {
        return Hub::select("*");
    }

    public function getHubById($hubId)
    {
        return Hub::findOrFail($hubId);
    }
    public function deleteHub($hubId)
    {
        Hub::destroy($hubId);
    }
    public function createHub(array $hubDetails)
    {
        return Hub::create($hubDetails);
    }
    public function updateHub($hubId, array $newDetails)
    {
        return Hub::whereId($hubId)->update($newDetails);
    }
    
}