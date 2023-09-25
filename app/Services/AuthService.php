<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\LogLogin;
use App\Helpers\Main;

class AuthService
{
    private $auth;

    public function __construct($auth='api')
    { 
        $this->auth = auth($auth);
    }

    public function login(Request $request, $user_role)
    { 
        // $credentials = $request->only(['user_name', 'password']);
        $credentials = [
            'email' => $request->user_name,
            'password' => $request->password,
        ];
        $remember = $request->has('remember_me'); 

        if($remember && ($request->remember_me == false || $request->remember_me == 'false')){
            $remember = false;
        } 

        $user = User::where('email', $request->user_name)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return [
                'res' => 'error',
                'status_code' => 400,
                'msg' => __('messages.invalid_credentials'),
                'trace_code' => 'AUTH001',
            ];
        }

        if (!empty($user->role) && $user->role->name != $user_role) {
            return [
                'res' => 'error',
                'status_code' => 400,
                'msg' => __('messages.invalid_credentials'),
                'trace_code' => 'AUTH001',
            ];
        }

        if (!$token = $this->auth->attempt($credentials)){
            return [
                'res' => 'error',
                'status_code' => 400,
                'msg' => __('messages.invalid_credentials'),
                'trace_code' => 'AUTH001',
            ];
        }
        $user = $this->auth->user(); 

        $params = [
            'ip' => $request->ip(),
            'browser' => $request->header('User-Agent'), 
            'location' => null, 
        ];
        Main::setCreatedModifiedVal(false, $params);
        LogLogin::create($params); 
        
        $token_type = 'Bearer'; 

        return [
            'res' => 'success',
            'status_code' => 200,
            'msg' => __('messages.success'),
            'trace_code' => null,
            'data' => [
                // 'is_remember' => $remember,
                'access_token' => $token,
                'token_type' => $token_type,
                'expires_in' => ($this->auth->factory()->getTTL() * 60),
            ],
        ];
    }

    public function logout(Request $request)
    {
        $this->auth->logout();
        
        return [
            'res' => 'success',
            'status_code' => 204,
            'msg' => __('messages.success'),
            'trace_code' => null,
            'data' => [],
        ];
    } 
}
