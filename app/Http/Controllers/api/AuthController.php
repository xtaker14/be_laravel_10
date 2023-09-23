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
use App\Models\LoginHistory;
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
        
        $validator = Main::validator($request, [
            'rules'=>[
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6|confirmed',
            ],
        ]);
        
        if (!empty($validator)){
            return $validator;
        }

        try {
            $user = new User();
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = Hash::make($request->input('password'));
            $user->save();
            
            $token = JWTAuth::fromUser($user);

            return $res::success(__('messages.success'), [
                'name' => $user->name,
                'email' => $user->email,
                'token' => $token
            ]);
        } catch (\Exception $e) {
            return $res::catchError($e);
        } 
    }

    public function login(Request $request)
    {   
        $validator_msg = [
            'string' => __('messages.validator_string'),
            'email' => __('messages.validator_email'),
            'required' => __('messages.validator_required'),
            'min' => __('messages.validator_min'),
            'max' => __('messages.validator_max'),
        ];

        $validator = Main::validator($request, [
            'rules'=>[
                'user_name' => 'required|email|min:12|max:30',
                'password' => 'required|string|min:8|max:30',
                'remember_me' => 'sometimes|string', 
            ],
            'messages'=>$validator_msg,
        ]);
        
        if (!empty($validator)){
            return $validator;
        } 

        // $credentials = $request->only(['user_name', 'password']);
        $credentials = [
            'email' => $request->user_name,
            'password' => $request->password,
        ];
        $remember = $request->has('remember_me'); 

        if($remember && ($request->remember_me == false || $request->remember_me == 'false')){
            $remember = false;
        }
        
        $res = new ResponseFormatter;  
        
        try { 
            if (!$token = $this->auth->attempt($credentials, $remember))  
            {
                return $res::error(400, __('messages.invalid_credentials'), $res::traceCode('AUTH001'));
            }
        } catch (JWTException $e) {
            return $res::error(500, __('messages.error'), $res::traceCode('AUTH002'));
        }

        // $user = $this->auth->user(); 
        $user = $this->auth->user();  

        // $toke_exp_time = JWTAuth::setToken($token)->getPayload()->get('exp'); 
        // $toke_exp_date = \Carbon\Carbon::createFromTimestamp($toke_exp_time);
        // $toke_exp_date_formatted = $toke_exp_date->format('Y-m-d H:i:s'); 
        
        // Check if the token has expired
        // if ($toke_exp_date->isPast()){}

        LoginHistory::create([
            'user_id' => $user->id,
            'ip_address' => $request->ip(),
            'location' => null, 
            'login_time' => now(),
        ]); 
        
        $token_type = 'Bearer'; 
        
        return $res::success(__('messages.success'), [
            // 'is_remember' => $remember,
            'access_token' => $token,
            'token_type' => $token_type,
            'expires_in' => (JWTAuth::factory()->getTTL() * 60),
            // 'toke_exp_date' => $toke_exp_date_formatted,
            // 'name' => $user->name,
            // 'email' => $user->email,
            // 'roles' => $user->getAllRolesName(),
            // 'permissions' => $user->getAllPermissionsName(),
        ]);
    } 

    public function logout(Request $request)
    {
        $this->auth->logout();
        $res = new ResponseFormatter;
        
        return $res::success(__('messages.success'),[],204);
    } 

    public function checkToken(Request $request)
    { 
        $JWTService = app(\App\Services\JWTService::class);
        $checkToken = $JWTService->checkToken(); 

        $res = new ResponseFormatter;
        switch ($checkToken) {
            case 'token_invalid':
                return $res::error(401, __('messages.token_invalid'), $res::traceCode('AUTH004')); 
                break;
            case 'token_expired':
                return $res::error(401, __('messages.token_expired'), $res::traceCode('AUTH005')); 
                break;
            case 'token_not_found':
                return $res::error(404, __('messages.token_not_found'), $res::traceCode('AUTH006'));
                break;
            case 'token_valid':
                return $res::success(__('messages.token_valid')); 
                break;
            
            default:
                return $res::error(401, __('messages.token_invalid'), $res::traceCode('AUTH004')); 
                break;
        } 
    }

    public function refreshToken(Request $request)
    {
        $res = new ResponseFormatter;  
        $old_token = JWTAuth::getToken();
        if(!$old_token){
            return $res::error(401, __('messages.token_invalid'), $res::traceCode('AUTH004')); 
        }
        $new_token = '';

        try {
            // $token = $this->auth->refresh();
            $user = $this->auth->user();
            $new_token = JWTAuth::refresh($old_token);
        } catch (TokenInvalidException $e) {
            return $res::error(401, __('messages.refresh_token_invalid'), $res::traceCode('AUTH007')); 
        }
        
        return $res::success(__('messages.success'), [
            'name' => $user->name,
            'email' => $user->email, 
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
        $user->save();

        return $res::success(__('messages.password_updated'), [
            'name' => $user->name,
            'email' => $user->email,
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

        $latestOtpEntry = OTP::where('user_id', $user->id)
            ->where('type', $request->type)
            ->latest()
            ->first();
        
        // Jika entri OTP terbaru ditemukan dan belum lewat 1 menit sejak dibuat
        if ($latestOtpEntry && Carbon::parse($latestOtpEntry->otp_created_at)->addMinutes(1)->isFuture()) { 
            return $res::error(429, __('messages.wait_req_new_otp'), $res::traceCode('AUTH009', [
                'wait_time' => Carbon::parse($latestOtpEntry->otp_created_at)->addMinutes(1)->diffInSeconds(Carbon::now()) // Waktu tunggu dalam detik
            ])); 
        }

        $otp = OTP::generateCode(); // OTP 6 digit
        $expirationTime = Carbon::now()->addMinutes(OTP::$exp_time); // Tanggal kadaluwarsa 10 menit dari sekarang

        $otpEntry = OTP::create([
            'user_id' => $user->id,
            'phone_number' => $request->phone_number,
            'otp' => $otp,
            'otp_created_at' => Carbon::now(),
            'otp_expires_at' => $expirationTime, // Simpan tanggal kadaluwarsa
            'type' => $request->type,
        ]);

        Main::sendOtp($otp, $request->phone_number);
            
        return $res::success(__('messages.otp_sent'), [
            'name' => $user->name,
            'email' => $user->email,
            'phone_number' => $request->phone_number,
            'otp' => $otp,
            'exp_otp' => $expirationTime, // kadaluwarsa dalam 10 menit
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

        $otpEntry = OTP::where('user_id', $user->id)
            ->where('type', $request->type)
            ->whereNull('verified_at')
            ->latest()
            ->first();

        if (!$otpEntry) {
            return $res::error(404, __('messages.no_otp_found'), $res::traceCode('AUTH010')); 
        }

        // Periksa apakah sudah mencapai batasan percobaan (max 5x)
        if ($otpEntry->attempts >= 5) {
            return $res::error(429, __('messages.exceeded_otp_att_limit'), $res::traceCode('AUTH011')); 
        }

        // Periksa apakah OTP cocok dan masih valid (dalam 10 menit setelah pembuatan)
        if ($otpEntry->otp === $request->otp && Carbon::parse($otpEntry->otp_created_at)->addMinutes(OTP::$exp_time)->isFuture()) {
            // Reset percobaan OTP jika berhasil masuk
            $otpEntry->attempts = 0;
            $otpEntry->verified_at = now();
            $otpEntry->save();

            // OTP valid

            return $res::success(__('messages.otp_verified'), [
                'name' => $user->name,
                'email' => $user->email,
                'otp' => $request->otp,
            ]); 
        } else {
            // Update percobaan OTP jika OTP salah
            $otpEntry->attempts += 1;
            $otpEntry->save();

            return $res::error(400, __('messages.invalid_otp'), $res::traceCode('AUTH012')); 
        }
    }
}
