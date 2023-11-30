<?php

namespace App\Interfaces;

interface HubRepositoryInterface
{
    public function getAllHub();
    public function getAllHubByRole();
    public function dataTableHub();
    public function getHubById($hubId);
    public function getHubByCode($code);
    public function deleteHub($hubId);
    public function createHub(array $hubDetails);
    public function updateHub($hubId, array $newDetails);
    public function getUsersHub();
}