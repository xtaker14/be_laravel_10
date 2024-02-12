<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Session;

use App\Models\Role;
use App\Interfaces\RoleRepositoryInterface;

class RoleRepository implements RoleRepositoryInterface
{
    public function dataTableRole()
    {
        return Role::join(DB::raw("(SELECT role_id, ROW_NUMBER() OVER (ORDER BY role_id) AS row_index FROM role) as sub"), 'role.role_id', '=', 'sub.role_id')
            ->select('sub.row_index', 'role.name', 'role.created_date')
            ->groupBy('role.role_id');
    }

    public function all()
    {
        return Role::all();
    }

    public function create($data)
    {
        return Role::create($data);
    }

    public function update($data, $id)
    {
        $get = Role::findOrFail($id);
        $get->update($data);
        return $get;
    }

    public function delete($id)
    {
        $get = Role::findOrFail($id);
        $get->delete();
    }

    public function find($id)
    {
        return Role::findOrFail($id);
    }
}