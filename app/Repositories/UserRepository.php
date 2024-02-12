<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function dataTableUser()
    {
        return User::join('role', 'users.role_id', '=', 'role.role_id')
            ->join(DB::raw("(
                SELECT 
                    users_id, ROW_NUMBER() OVER (ORDER BY users_id) AS row_index 
                FROM users
                JOIN role ON users.role_id = role.role_id  
            ) as sub"), 'users.users_id', '=', 'sub.users_id')
            ->join(DB::raw("(
                SELECT 
                    users_id, CASE WHEN is_active = 1 THEN 'active' ELSE 'inactive' END AS status 
                FROM users
            ) as sub2"), 'users.users_id', '=', 'sub2.users_id')
            ->select('sub.row_index', 'users.full_name', 'users.username', 'role.name as role', 'users.email as email', 'users.is_active', 'sub2.status')
            ->groupBy('users.users_id');
    }

    public function all()
    {
        return User::all();
    }

    public function create($data)
    {
        return User::create($data);
    }

    public function update($data, $id)
    {
        $get = User::findOrFail($id);
        $get->update($data);
        return $get;
    }

    public function delete($id)
    {
        $get = User::findOrFail($id);
        $get->delete();
    }

    public function find($id)
    {
        return User::findOrFail($id);
    }
}