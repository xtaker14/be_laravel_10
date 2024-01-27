<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserRepository implements UserRepositoryInterface
{
    public function dataTableUser()
    {
        return DB::table('users')
        ->join('role', 'users.role_id', '=', 'role.role_id')
        ->join(DB::raw("(
            SELECT 
                users_id, ROW_NUMBER() OVER (ORDER BY users_id) AS row_index 
            FROM users
            JOIN role ON users.role_id = role.role_id AND role.name <> 'COURIER' 
        ) as sub"), 'users.users_id', '=', 'sub.users_id')
        ->join(DB::raw("(
            SELECT 
                users_id, CASE WHEN is_active = 1 THEN 'active' ELSE 'inactive' END AS status 
            FROM users
        ) as sub2"), 'users.users_id', '=', 'sub2.users_id')
        ->select('sub.row_index', 'users.full_name', 'users.username', 'role.name as role', 'users.email as email', 'users.is_active', 'sub2.status')
        ->where('role.name','<>','COURIER')
        ->groupBy('users.users_id');
    }
}