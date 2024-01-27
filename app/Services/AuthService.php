<?php

namespace App\Services;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Contracts\JWTSubject;
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

    public function login(Request $request, $expiration)
    { 
        // $credentials = $request->only(['user_name', 'password']);
        $credentials = [
            'username' => $request->user_name,
            'password' => $request->password,
        ];
        $remember = $request->has('remember_me'); 

        if($remember && ($request->remember_me == false || $request->remember_me == 'false')){
            $remember = false;
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
        //get token id
        $tokenString = LogLogin::where('created_by', $user->username)
        ->whereNotNull('access_token')
        ->orderBy('log_login_id', 'desc')
        ->first();
        if ($tokenString) {
            JWTAuth::setToken($tokenString->access_token)->invalidate();
        }

        if($user->is_active != User::ACTIVE){
            $this->auth->logout();
            return [
                'res' => 'error',
                'status_code' => 400,
                'msg' => __('messages.invalid_credentials'),
                'trace_code' => 'AUTH001',
            ];
        }

        $token = JWTAuth::claims(['exp' => $expiration->timestamp])->fromUser($user);
        $expires_in = $expiration->diffInSeconds(now());
        
        $token_type = 'Bearer'; 

        $params = [
            'ip' => $request->ip(),
            'browser' => $request->header('User-Agent'), 
            'location' => null, 
            'access_token' => $token, 
        ];
        Main::setCreatedModifiedVal(false, $params);
        LogLogin::create($params);

        return [
            'res' => 'success',
            'status_code' => 200,
            'msg' => __('messages.success'),
            'trace_code' => null,
            'data' => [
                // 'is_remember' => $remember,
                'access_token' => $token,
                'token_type' => $token_type,
                'expires_in' => $expires_in,
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
