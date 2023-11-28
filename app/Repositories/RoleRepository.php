<?php

namespace App\Repositories;

use App\Interfaces\RoleRepositoryInterface;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Session;

class RoleRepository implements RoleRepositoryInterface
{
    public function dataTableRole()
    {
        return DB::table('role')
        ->join(DB::raw("(SELECT role_id, ROW_NUMBER() OVER (ORDER BY role_id) AS row_index FROM role) as sub"), 'role.role_id', '=', 'sub.role_id')
        ->select('sub.row_index', 'role.name', 'role.created_date')
        ->groupBy('role.role_id');
    }
}