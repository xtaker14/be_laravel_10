<?php

namespace App\Services;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\LogLogin;
use App\Helpers\Main;

class UserService
{
    private $auth;

    public function __construct($auth='api')
    { 
        $this->auth = auth($auth);
    }

    public function profile(Request $request)
    {   
        $user = $this->auth->user();  

        $profile = [];

        $display = 'all';
        if($request->display){
            $display = $request->display;
        }

        if($display == 'homepage'){
            $profile['user'] = [
                'user_id' => $user->users_id,
                'full_name' => $user->full_name,
                'profile_picture_url' => $user->picture
            ];
        }else if($display == 'all'){ 
            $last_login = LogLogin::where('created_by', $user->username)
                ->latest()
                ->first();

            $profile['user'] = [
                'user_id' => $user->users_id,
                'username' => $user->username,
                'gender' => $user->gender,
                'full_name' => $user->full_name,
                'email' => $user->email,
                'role' => $user->role->name ?? null,
                'last_login' => $last_login->created_date ?? null,
                'join_date' => $user->created_date,
                'profile_picture_url' => $user->picture
            ];
        }else{
            return [
                'res' => 'error',
                'status_code' => 400,
                'msg' => __('messages.request_cant_be_displayed'),
                'trace_code' => 'REQUEST002',
            ];
        }

        return [
            'res' => 'success',
            'status_code' => 200,
            'msg' => __('messages.success'),
            'trace_code' => null,
            'data' => $profile,
        ];
    }
}
