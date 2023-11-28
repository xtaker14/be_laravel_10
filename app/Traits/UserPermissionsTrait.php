<?php

namespace App\Traits;

trait UserPermissionsTrait
{
    public function hasFeaturePermission($featureName, $permissionName)
    {
        return $this->role->privileges()
            ->whereHas('feature', function ($query) use ($featureName) {
                $query->where('name', $featureName);
            })
            ->whereHas('permission', function ($query) use ($permissionName) {
                $query->where('name', $permissionName);
            })
            ->exists();
    }

    public function hasPrivilege($privilege)
    {
        return $this->role->privileges()
            ->whereHas('feature', function ($query) use ($privilege) {
                $query->where('name', $privilege->feature->name);
            })
            ->whereHas('permission', function ($query) use ($privilege) {
                $query->where('name', $privilege->permission->name);
            })
            ->exists();
    }

    public function hasFeature($featureName)
    {
        return $this->role->privileges()
            ->whereHas('feature', function ($query) use ($featureName) {
                $query->where('name', $featureName);
            })
            ->exists();
    }
}