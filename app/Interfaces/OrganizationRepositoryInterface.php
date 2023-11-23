<?php

namespace App\Interfaces;

interface OrganizationRepositoryInterface
{
    public function getAllOrganization();
    public function dataTableOrganization();
    public function getOrganizationById($organizationId);
    public function getOrganizationByUser($userId);
    public function getOrganizationSummary($organizationId);
    public function deleteOrganization($organizationId);
    public function createOrganization(array $organizationDetails);
    public function updateOrganization($organizationId, array $newDetails);
    public function updateOrganizationDetail($organizationId, $newDetails);
}