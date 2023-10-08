<?php

namespace App\Interfaces;

interface HubRepositoryInterface
{
    public function getAllHub();
    public function selectAllHub();
    public function getHubById($hubId);
    public function deleteHub($hubId);
    public function createHub(array $hubDetails);
    public function updateHub($hubId, array $newDetails);
}