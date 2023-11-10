<?php

namespace App\Http\Controllers\api;

use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Carbon\Carbon;

use App\Models\User;
use App\Models\LogLogin;
use App\Models\OTP;
use App\Helpers\Main;
use App\Helpers\ResponseFormatter;

class AuthController extends Controller
{ 
    private $auth;

    public function __construct()
    {
        // $this->auth = new JWTAuth;
        // $this->auth = new Auth;
        $this->auth = auth('api');
    }

    // unused function
    public function register(Request $request)
    {
        $res = new ResponseFormatter;
        return $res::error(401, 'unused function'); 
        
        // $validator = Main::validator($request, [
        //     'rules'=>[
        //         'name' => 'required|string|max:255',
        //         'email' => 'required|email|unique:users,email',
        //         'password' => 'required|min:6|confirmed',
        //     ],
        // ]);
        
        // if (!empty($validator)){
        //     return $validator;
        // }

        // try {
        //     $user = new User();
        //     $user->name = $request->input('name');
        //     $user->email = $request->input('email');
        //     $user->password = Hash::make($request->input('password'));
        //     $user->save();
            
        //     $token = JWTAuth::fromUser($user);

        //     return $res::success(__('messages.success'), [
        //         'name' => $user->name,
        //         'email' => $user->email,
        //         'token' => $token
        //     ]);
        // } catch (\Exception $e) {
        //     return $res::catchError($e);
        // } 
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

        // return Hash::make($request->input('password'));

        $AuthService = new \App\Services\AuthService('api');
        // $expiration = now()->addHours(2);
        $expiration = now()->addMinutes(env('JWT_TTL_OPEN_API', 180));
        $login_service = $AuthService->login($request, $expiration);
        
        $res = new ResponseFormatter;   

        if ($login_service['res'] == 'error'){
            return $res::error($login_service['status_code'], $login_service['msg'], $res::traceCode($login_service['trace_code']));
        } else {
            return $res::success($login_service['msg'], $login_service['data'], $login_service['status_code']);
        } 
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

        // return Hash::make($request->password);

        $AuthService = new \App\Services\AuthService('api');
        // $expiration = now()->addHours(2);
        $expiration = now()->addMinutes(env('JWT_TTL', 180));
        $login_service = $AuthService->login($request, $expiration);
        
        $res = new ResponseFormatter;   

        if ($login_service['res'] == 'error'){
            return $res::error($login_service['status_code'], $login_service['msg'], $res::traceCode($login_service['trace_code']));
        } else {
            return $res::success($login_service['msg'], $login_service['data'], $login_service['status_code']);
        } 
    } 

    public function logout(Request $request)
    {
        $AuthService = new \App\Services\AuthService('api');
        $logout_service = $AuthService->logout($request);
        
        $res = new ResponseFormatter;   

        return $res::success($logout_service['msg'], $logout_service['data'], $logout_service['status_code']);
    } 

    public function checkToken(Request $request)
    { 
        $JWTService = app(\App\Services\JWTService::class);
        $checkToken = $JWTService->checkToken(); 

        $res = new ResponseFormatter;
        switch ($checkToken) {
            case 'invalid_token':
                return $res::error(401, __('messages.invalid_token'), $res::traceCode('AUTH004')); 
                break;
            case 'token_expired':
                return $res::error(401, __('messages.token_expired'), $res::traceCode('AUTH005')); 
                break;
            case 'token_not_found':
                return $res::error(404, __('messages.token_not_found'), $res::traceCode('AUTH006'));
                break;
            case 'valid_token':
                return $res::success(__('messages.valid_token')); 
                break;
            
            default:
                return $res::error(401, __('messages.invalid_token'), $res::traceCode('AUTH004')); 
                break;
        } 
    }

    public function refreshToken(Request $request)
    {
        $res = new ResponseFormatter;  
        $old_token = JWTAuth::getToken();
        if(!$old_token){
            return $res::error(401, __('messages.invalid_token'), $res::traceCode('AUTH004')); 
        }
        $new_token = '';

        try {
            // $token = $this->auth->refresh();
            $user = $this->auth->user();
            $new_token = JWTAuth::refresh($old_token);
        } catch (TokenInvalidException $e) {
            return $res::error(401, __('messages.invalid_refresh_token'), $res::traceCode('AUTH007')); 
        }
        
        return $res::success(__('messages.success'), [
            'username' => $user->username, 
            'new_token' => $new_token
        ]);
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
        $user = $this->auth->user();  

        if (Hash::check($request->password, $user->password)) {
            return $res::error(400, __('messages.password_already_used'), $res::traceCode('AUTH008'));
        }

        $user->password = Hash::make($request->password);
        Main::setCreatedModifiedVal(true, $user, 'modified'); 
        $user->save();

        return $res::success(__('messages.password_updated'), [
            'username' => $user->username,
        ]); 
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
        $user = $this->auth->user();  

        $latest_otp_entry = OTP::where('user_id', $user->users_id)
            ->where('type', $request->type)
            ->latest()
            ->first();
        
        // Jika entri OTP terbaru ditemukan dan belum lewat 1 menit sejak dibuat
        if ($latest_otp_entry && Carbon::parse($latest_otp_entry->otp_created_at)->addMinutes(1)->isFuture()) { 
            return $res::error(429, __('messages.wait_req_new_otp'), $res::traceCode('AUTH009', [
                'wait_time' => Carbon::parse($latest_otp_entry->otp_created_at)->addMinutes(1)->diffInSeconds(Carbon::now()) // Waktu tunggu dalam detik
            ])); 
        }

        $otp = OTP::generateCode(); // OTP 6 digit
        $expiration_time = Carbon::now()->addMinutes(OTP::$exp_time); // Tanggal kadaluwarsa 10 menit dari sekarang
        
        $params = [
            'phone_number' => $request->phone_number,
            'otp' => $otp,
            'otp_expires_at' => $expiration_time, // Simpan tanggal kadaluwarsa
            'type' => $request->type,
        
        ];
        Main::setCreatedModifiedVal(false, $params);
        $otpEntry = OTP::create($params);

        Main::sendOtp($otp, $request->phone_number);
            
        return $res::success(__('messages.otp_sent'), [
            'username' => $user->username,
            'phone_number' => $request->phone_number,
            'otp' => $otp,
            'exp_otp' => $expiration_time, // kadaluwarsa dalam 10 menit
        ]); 
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
        $user = $this->auth->user(); 

        $otp_entry = OTP::where('user_id', $user->users_id)
            ->where('type', $request->type)
            ->whereNull('verified_at')
            ->latest()
            ->first();

        if (!$otp_entry) {
            return $res::error(404, __('messages.no_otp_found'), $res::traceCode('AUTH010')); 
        }

        // Periksa apakah sudah mencapai batasan percobaan (max 5x)
        if ($otp_entry->attempts >= 5) {
            return $res::error(429, __('messages.exceeded_otp_att_limit'), $res::traceCode('AUTH011')); 
        }

        // Periksa apakah OTP cocok dan masih valid (dalam 10 menit setelah pembuatan)
        if ($otp_entry->otp === $request->otp && Carbon::parse($otp_entry->otp_created_at)->addMinutes(OTP::$exp_time)->isFuture()) {
            // Reset percobaan OTP jika berhasil masuk
            $otp_entry->attempts = 0;
            $otp_entry->verified_at = now();
            Main::setCreatedModifiedVal(true, $otp_entry, 'modified'); 
            $otp_entry->save();

            // OTP valid

            return $res::success(__('messages.otp_verified'), [
                'username' => $user->username,
                'otp' => $request->otp,
            ]); 
        } else {
            // Update percobaan OTP jika OTP salah
            $otp_entry->attempts += 1;
            Main::setCreatedModifiedVal(true, $otp_entry, 'modified'); 
            $otp_entry->save();

            return $res::error(400, __('messages.invalid_otp'), $res::traceCode('AUTH012')); 
        }
    }
}
