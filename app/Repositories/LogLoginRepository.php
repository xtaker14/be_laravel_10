<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

use App\Models\LogLogin;
use App\Interfaces\LogLoginRepositoryInterface;

class LogLoginRepository implements LogLoginRepositoryInterface
{  
    public function all()
    {
        return LogLogin::all();
    }

    public function create($data)
    {
        return LogLogin::create($data);
    }

    public function update($data, $id)
    {
        $get = LogLogin::findOrFail($id);
        $get->update($data);
        return $get;
    }

    public function delete($id)
    {
        $get = LogLogin::findOrFail($id);
        $get->delete();
    }

    public function find($id)
    {
        return LogLogin::findOrFail($id);
    }

    public function findByUsername($username)
    {
        return LogLogin::where('created_by', $username)
            ->latest()
            ->first();
    }

    public function findAccessTokenByUsername($username)
    {
        return LogLogin::where('created_by', $username)
            ->whereNotNull('access_token')
            ->orderBy('log_login_id', 'desc')
            ->first();
    }
}
