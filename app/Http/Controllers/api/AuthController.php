<?php

namespace App\Http\Controllers\api;

use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
// use Laravel\Passport\Token;
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
    // unused function
    public function register(Request $request)
    {
        return ResponseFormatter::error(401, 'unused function'); 
        
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

            // token passport
            // $token_name = 'TMS';
            // if(!empty(env('TOKEN_NAME'))){
            //     $token_name = env('TOKEN_NAME');
            // }
            // $token = $user->createToken($token_name)->accessToken;

            // token JWT
            $token = JWTAuth::fromUser($user);

            return ResponseFormatter::success(__('messages.success'), [
                'name' => $user->name,
                'email' => $user->email,
                'token' => $token
            ]);
        } catch (\Exception $e) {
            return ResponseFormatter::catchError($e);
        } 
    }

    public function login(Request $request)
    {
        $validator = Main::validator($request, [
            'rules'=>[
                'email' => 'required|email',
                'password' => 'required',
            ],
        ]);
        
        if (!empty($validator)){
            return $validator;
        } 

        $credentials = $request->only(['email', 'password']);
        $remember = $request->has('remember_me'); 

        if($remember && ($request->remember_me == false || $request->remember_me == 'false')){
            $remember = false;
        }

        // token passport
        // $user = User::where('email', $request->input('email'))->first();

        // if (!$user || !Hash::check($request->input('password'), $user->password)) {
        //     return ResponseFormatter::error(401, __('messages.invalid_credentials')); 
        // } 

        // if (!Auth::attempt($credentials, $remember)) {
        //     return ResponseFormatter::error(401, __('messages.unauthenticated')); 
        // } 
        
        // $user = Auth::user();
        // $token_name = 'TMS';
        // if(!empty(env('TOKEN_NAME'))){
        //     $token_name = env('TOKEN_NAME');
        // }
        // $token = $user->createToken($token_name)->accessToken;

        // token JWT
        try {
            if (!$token = JWTAuth::attempt($credentials))  
            {
                return ResponseFormatter::error(401, __('messages.invalid_credentials'));
            }
        } catch (JWTException $e) {
            return ResponseFormatter::error(500, __('messages.unauthenticated'));
        }

        // $user = JWTAuth::user();
        $user = Auth::user(); 

        $toke_exp_time = JWTAuth::setToken($token)->getPayload()->get('exp'); 
        $toke_exp_date = \Carbon\Carbon::createFromTimestamp($toke_exp_time);
        $toke_exp_date_formatted = $toke_exp_date->format('Y-m-d H:i:s'); 
        
        // Check if the token has expired
        // if ($toke_exp_date->isPast()){}

        LoginHistory::create([
            'user_id' => $user->id,
            'ip_address' => $request->ip(),
            'location' => null, 
            'login_time' => now(),
        ]); 
        
        return ResponseFormatter::success(__('messages.success'), [
            'name' => $user->name,
            'email' => $user->email,
            'is_remember' => $remember,
            'roles' => $user->getAllRolesName(),
            'permissions' => $user->getAllPermissionsName(),
            'token' => $token,
            'toke_exp_date' => $toke_exp_date_formatted,
        ]);
    } 

    public function checkToken(Request $request)
    { 
        $JWTService = app(\App\Services\JWTService::class);
        $checkToken = $JWTService->checkToken();

        switch ($checkToken) {
            case 'token_invalid':
                return ResponseFormatter::error(401, __('messages.token_invalid')); 
                break;
            case 'token_expired':
                return ResponseFormatter::error(401, __('messages.token_expired')); 
                break;
            case 'token_not_found':
                return ResponseFormatter::error(404, __('messages.token_not_found'));
                break;
            case 'token_valid':
                return ResponseFormatter::success(__('messages.token_valid')); 
                break;
            
            default:
                return ResponseFormatter::error(401, __('messages.token_invalid')); 
                break;
        } 
    }

    public function refreshToken(Request $request)
    {
        $old_token = JWTAuth::getToken();
        if(!$old_token){
            return ResponseFormatter::error(401, __('messages.token_invalid')); 
        }
        $new_token = '';

        try {
            // $token = auth()->refresh();
            $user = JWTAuth::user(); 
            $new_token = JWTAuth::refresh($old_token);
        } catch (TokenInvalidException $e) {
            return ResponseFormatter::error(401, __('messages.refresh_token_invalid'), $e); 
        }
        
        return ResponseFormatter::success(__('messages.success'), [
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

        $user = Auth::user(); 
        if (!$user) {
            return ResponseFormatter::error(401, __('messages.unauthenticated')); 
        }

        if (Hash::check($request->password, $user->password)) {
            return ResponseFormatter::error(400, __('messages.password_already_used'));
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return ResponseFormatter::success(__('messages.password_updated'), [
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

        $user = Auth::user(); 
        if (!$user) {
            return ResponseFormatter::error(401, __('messages.unauthenticated')); 
        }

        $latestOtpEntry = OTP::where('user_id', $user->id)
            ->where('type', $request->type)
            ->latest()
            ->first();
        
        // Jika entri OTP terbaru ditemukan dan belum lewat 1 menit sejak dibuat
        if ($latestOtpEntry && Carbon::parse($latestOtpEntry->otp_created_at)->addMinutes(1)->isFuture()) { 
            return ResponseFormatter::error(429, __('messages.wait_req_new_otp'), [
                'wait_time' => Carbon::parse($latestOtpEntry->otp_created_at)->addMinutes(1)->diffInSeconds(Carbon::now()) // Waktu tunggu dalam detik
            ]); 
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
            
        return ResponseFormatter::success(__('messages.otp_sent'), [
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

        $user = Auth::user(); 
        if (!$user) {
            return ResponseFormatter::error(401, __('messages.unauthenticated')); 
        }

        $otpEntry = OTP::where('user_id', $user->id)
            ->where('type', $request->type)
            ->whereNull('verified_at')
            ->latest()
            ->first();

        if (!$otpEntry) {
            return ResponseFormatter::error(404, __('messages.no_otp_found')); 
        }

        // Periksa apakah sudah mencapai batasan percobaan (max 5x)
        if ($otpEntry->attempts >= 5) {
            return ResponseFormatter::error(429, __('messages.exceeded_otp_att_limit')); 
        }

        // Periksa apakah OTP cocok dan masih valid (dalam 10 menit setelah pembuatan)
        if ($otpEntry->otp === $request->otp && Carbon::parse($otpEntry->otp_created_at)->addMinutes(OTP::$exp_time)->isFuture()) {
            // Reset percobaan OTP jika berhasil masuk
            $otpEntry->attempts = 0;
            $otpEntry->verified_at = now();
            $otpEntry->save();

            // OTP valid

            return ResponseFormatter::success(__('messages.otp_verified'), [
                'name' => $user->name,
                'email' => $user->email,
                'otp' => $request->otp,
            ]); 
        } else {
            // Update percobaan OTP jika OTP salah
            $otpEntry->attempts += 1;
            $otpEntry->save();

            return ResponseFormatter::error(400, __('messages.invalid_otp')); 
        }
    }
}
