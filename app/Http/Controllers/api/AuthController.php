<?php

namespace App\Http\Controllers\Api;

use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Carbon\Carbon;

use App\Helpers\Main;
use App\Helpers\ResponseFormatter;

use App\Services\UserService;

class AuthController extends Controller
{ 
    private UserService $userService;
    private $auth;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        $this->auth = auth('api');
    }

    // unused function
    public function register(Request $request)
    {
        $res = new ResponseFormatter;
        return $res::error(401, 'unused function');   
    }

    public function loginOpenApi(Request $request)
    {   
        $validator_msg = [
            'string' => __('messages.validator_string'), 
            'required' => __('messages.validator_required'),
            'min' => __('messages.validator_min'),
            'max' => __('messages.validator_max'),
        ];

        $validator = Main::validator($request, [
            'rules'=>[
                'user_name' => 'required|string|min:8|max:30',
                'password' => 'required|string|min:8|max:30',
                'remember_me' => 'sometimes|string', 
            ],
            'messages'=>$validator_msg,
        ]);
        
        if (!empty($validator)){
            return $validator;
        }

        $expiration = now()->addMinutes(env('JWT_TTL_OPEN_API', 180));
        $login_service = $this->userService->login($request, $this->auth, $expiration);
        
        $res = new ResponseFormatter;

        return $this->resService($res, $login_service);
    } 

    public function login(Request $request)
    {   
        $validator_msg = [
            'string' => __('messages.validator_string'), 
            'required' => __('messages.validator_required'),
            'min' => __('messages.validator_min'),
            'max' => __('messages.validator_max'),
        ];

        $validator = Main::validator($request, [
            'rules'=>[
                'user_name' => 'required|string|min:8|max:30',
                'password' => 'required|string|min:8|max:30',
                'remember_me' => 'sometimes|string', 
            ],
            'messages'=>$validator_msg,
        ]);
        
        if (!empty($validator)){
            return $validator;
        } 
        
        $expiration = now()->addMinutes(env('JWT_TTL', 180));
        $login_service = $this->userService->login($request, $this->auth, $expiration);
        
        $res = new ResponseFormatter;

        return $this->resService($res, $login_service);
    } 

    public function logout(Request $request)
    {
        $logout_service = $this->userService->logout($request, $this->auth);
        
        $res = new ResponseFormatter;   

        return $res::success($logout_service['msg'], $logout_service['data'], $logout_service['status_code']);
    } 

    public function checkToken(Request $request)
    { 
        $checkToken = $this->userService->checkToken(); 

        $res = new ResponseFormatter;
        if ($checkToken['res'] == 'success') {
            return $res::success($checkToken['msg']); 
        } else {
            return $res::error($checkToken['status_code'], $checkToken['msg'], $res::traceCode($checkToken['trace_code'])); 
        } 
    }

    public function refreshToken(Request $request)
    {
        $res = new ResponseFormatter;

        $refreshToken = $this->userService->refreshToken($this->auth);

        return $this->resService($res, $refreshToken);
    }

    public function setPassword(Request $request)
    {
        $validator = Main::validator($request, [
            'rules'=>[
                'password' => 'required|string|min:8|confirmed',
            ],
        ]);
        
        if (!empty($validator)){
            return $validator;
        } 

        $res = new ResponseFormatter;
        $setPassword = $this->userService->setPassword($request, $this->auth);

        return $this->resService($res, $setPassword);
    }

    public function generateOtp(Request $request)
    {
        $validator = Main::validator($request, [
            'rules'=>[
                'phone_number' => 'required|string',
                'type' => 'required|string|in:registration,password_reset,password_change',
            ],
        ]);
        
        if (!empty($validator)){
            return $validator;
        } 

        $res = new ResponseFormatter;

        $generateOtp = $this->userService->generateOtp($request, $this->auth);

        return $this->resService($res, $generateOtp);
    }

    public function verifyOtp(Request $request)
    {
        $validator = Main::validator($request, [
            'rules'=>[
                'otp' => 'required|string',
                'type' => 'required|string|in:registration,password_reset,password_change',
            ],
        ]);
        
        if (!empty($validator)){
            return $validator;
        }

        $res = new ResponseFormatter;

        $verifyOtp = $this->userService->verifyOtp($request, $this->auth);

        return $this->resService($res, $verifyOtp);
    }
}
