<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

use App\Models\OTP;
use App\Interfaces\OTPRepositoryInterface;

class OTPRepository implements OTPRepositoryInterface
{
    public $exp_time = 10;

    public function generateCode()
    {
        return rand(100000, 999999);
    }

    public function all()
    {
        return OTP::all();
    }

    public function create($data)
    {
        return OTP::create($data);
    }

    public function update($data, $id)
    {
        $get = OTP::findOrFail($id);
        $get->update($data);
        return $get;
    }

    public function delete($id)
    {
        $get = OTP::findOrFail($id);
        $get->delete();
    }

    public function find($id)
    {
        return OTP::findOrFail($id);
    }

    public function OTPEntry($usersId, $type)
    {
        return OTP::where('user_id', $usersId)
            ->where('type', $type)
            ->whereNull('verified_at')
            ->latest()
            ->first();
    }

    public function latestOTPEntry($usersId, $type)
    {
        return OTP::where('user_id', $usersId)
            ->where('type', $type)
            ->latest()
            ->first();
    }
}
