<?php

namespace App\Services;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DataTables;

use App\Models\User; 
use App\Helpers\Main;

use App\Repositories\UserRepository;
use App\Repositories\LogLoginRepository;
use App\Repositories\OTPRepository;

class UserService
{
    private UserRepository $userRepository;
    private LogLoginRepository $logLoginRepository;
    private OTPRepository $OTPRepository;

    public function __construct(UserRepository $userRepository, LogLoginRepository $logLoginRepository, OTPRepository $OTPRepository)
    {
        $this->userRepository = $userRepository;
        $this->logLoginRepository = $logLoginRepository;
        $this->OTPRepository = $OTPRepository;
    } 

    public function list()
    {
        $data = $this->userRepository->dataTableUser();

        return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('status', function ($row) {
                $label = $row->is_active == 1 ? 'success' : 'danger';
                return '<span class="badge bg-label-' . $label . '">' . ucwords($row->status) . '</span>';
            })
            ->addColumn('action', function ($row) {
                $btn = '<button type="button" class="btn btn-warning waves-effect waves-light">
                <i class="ti ti-eye cursor-pointer"></i>
                View
                </button>';
                return $btn;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function checkToken()
    {
        $res = [
            'res' => 'success',
            'status_code' => 200,
            'msg' => __('messages.valid_token'),
            'trace_code' => null,
        ];

        try {
            JWTAuth::parseToken()->authenticate();
        } catch (TokenInvalidException $e) {
            if ($e) {
                $res = [
                    'res' => 'error',
                    'status_code' => 401,
                    'msg' => __('messages.invalid_token'),
                    'trace_code' => 'AUTH004',
                ];
            } else if ($e) {
                $res = [
                    'res' => 'error',
                    'status_code' => 401,
                    'msg' => __('messages.token_expired'),
                    'trace_code' => 'AUTH005',
                ];
            } else {
                $res = [
                    'res' => 'error',
                    'status_code' => 404,
                    'msg' => __('messages.token_not_found'),
                    'trace_code' => 'AUTH006',
                ];
            }
        } 

        return $res;
    }

    public function refreshToken($auth)
    {
        $old_token = JWTAuth::getToken();
        if (!$old_token) {
            return [
                'res' => 'error',
                'status_code' => 401,
                'msg' => __('messages.invalid_token'),
                'trace_code' => 'AUTH004',
            ];
        }
        $new_token = '';

        try {
            $user = $auth->user();
            $new_token = JWTAuth::refresh($old_token);
        } catch (TokenInvalidException $e) {
            return [
                'res' => 'error',
                'status_code' => 401,
                'msg' => __('messages.invalid_refresh_token'),
                'trace_code' => 'AUTH007',
            ];
        }

        return [
            'res' => 'success',
            'status_code' => 200,
            'msg' => __('messages.success'),
            'trace_code' => null,
            'data' => [
                'username' => $user->username,
                'new_token' => $new_token
            ],
        ];
    }

    public function login(Request $request, $auth, $expiration)
    { 
        $credentials = [
            'username' => $request->user_name,
            'password' => $request->password,
        ];
        $remember = $request->has('remember_me'); 

        if($remember && ($request->remember_me == false || $request->remember_me == 'false')){
            $remember = false;
        }  

        if (!$token = $auth->attempt($credentials)){
            return [
                'res' => 'error',
                'status_code' => 400,
                'msg' => __('messages.invalid_credentials'),
                'trace_code' => 'AUTH001',
            ];
        }
        $user = $auth->user();
         
        //get token id
        $getAccessToken = $this->logLoginRepository->findAccessTokenByUsername($user->username);

        if ($getAccessToken) {
            JWTAuth::setToken($getAccessToken->access_token)->invalidate();
        }

        if($user->is_active != User::ACTIVE){
            $auth->logout();

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
        $this->logLoginRepository->create($params);

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

    public function logout(Request $request, $auth)
    {
        $auth->logout();
        
        return [
            'res' => 'success',
            'status_code' => 204,
            'msg' => __('messages.success'),
            'trace_code' => null,
            'data' => [],
        ];
    } 

    public function profile(Request $request, $auth)
    {   
        $user = $auth->user();  

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
            $last_login = $this->logLoginRepository->findByUsername($user->username);

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

    public function setPassword(Request $request, $auth)
    {
        $user = $auth->user();

        if (Hash::check($request->password, $user->password)) {
            return [
                'res' => 'error',
                'status_code' => 400,
                'msg' => __('messages.password_already_used'),
                'trace_code' => 'AUTH008',
            ];
        }

        $user->password = Hash::make($request->password);
        Main::setCreatedModifiedVal(true, $user, 'modified');
        $user->save();
        
        return [
            'res' => 'success',
            'status_code' => 200,
            'msg' => __('messages.password_updated'),
            'trace_code' => null,
            'data' => [
                'username' => $user->username,
            ],
        ];

    }

    public function generateOtp(Request $request, $auth)
    {
        $user = $auth->user();

        $latest_otp_entry = $this->OTPRepository->latestOTPEntry($user->users_id, $request->type);

        // Jika entri OTP terbaru ditemukan dan belum lewat 1 menit sejak dibuat
        if ($latest_otp_entry && Carbon::parse($latest_otp_entry->otp_created_at)->addMinutes(1)->isFuture()) {
            return [
                'res' => 'error',
                'status_code' => 429,
                'msg' => __('messages.wait_req_new_otp'),
                'trace_code' => 'AUTH009',
                'data' => [
                    'wait_time' => Carbon::parse($latest_otp_entry->otp_created_at)->addMinutes(1)->diffInSeconds(Carbon::now()) // Waktu tunggu dalam detik
                ],
            ];
        }

        $otp = $this->OTPRepository->generateCode(); // OTP 6 digit
        $otp_exp_time = $this->OTPRepository->exp_time;
        $expiration_time = Carbon::now()->addMinutes($otp_exp_time); // Tanggal kadaluwarsa 10 menit dari sekarang

        $params = [
            'phone_number' => $request->phone_number,
            'otp' => $otp,
            'otp_expires_at' => $expiration_time, // Simpan tanggal kadaluwarsa
            'type' => $request->type,

        ];
        Main::setCreatedModifiedVal(false, $params);
        $otpEntry = $this->OTPRepository->create($params);

        Main::sendOtp($otp, $request->phone_number);

        return [
            'res' => 'success',
            'status_code' => 200,
            'msg' => __('messages.otp_sent'),
            'trace_code' => null,
            'data' => [
                'username' => $user->username,
                'phone_number' => $request->phone_number,
                'otp' => $otp,
                'exp_otp' => $expiration_time, // kadaluwarsa dalam 10 menit
            ],
        ];

    }

    public function verifyOtp(Request $request, $auth)
    {
        $user = $auth->user();

        $otp_entry = $this->OTPRepository->OTPEntry($user->users_id, $request->type);

        if (!$otp_entry) {
            return [
                'res' => 'error',
                'status_code' => 404,
                'msg' => __('messages.no_otp_found'),
                'trace_code' => 'AUTH010',
            ];
        }

        // Periksa apakah sudah mencapai batasan percobaan (max 5x)
        if ($otp_entry->attempts >= 5) {
            return [
                'res' => 'error',
                'status_code' => 429,
                'msg' => __('messages.exceeded_otp_att_limit'),
                'trace_code' => 'AUTH011',
            ];
        }

        // Periksa apakah OTP cocok dan masih valid (dalam 10 menit setelah pembuatan)
        $otp_exp_time = $this->OTPRepository->exp_time;
        if ($otp_entry->otp ===$request->otp && Carbon::parse($otp_entry->otp_created_at)->addMinutes($otp_exp_time)->isFuture()) {
            // Reset percobaan OTP jika berhasil masuk
            $otp_entry->attempts = 0;
            $otp_entry->verified_at = now();
            Main::setCreatedModifiedVal(true, $otp_entry, 'modified');
            $otp_entry->save();

            // OTP valid
            return [
                'res' => 'success',
                'status_code' => 200,
                'msg' => __('messages.otp_verified'),
                'trace_code' => null,
                'data' => [
                    'username' => $user->username,
                    'otp' => $request->otp,
                ],
            ];
        } else {
            // Update percobaan OTP jika OTP salah
            $otp_entry->attempts += 1;
            Main::setCreatedModifiedVal(true, $otp_entry, 'modified');
            $otp_entry->save();

            return [
                'res' => 'error',
                'status_code' => 400,
                'msg' => __('messages.invalid_otp'),
                'trace_code' => 'AUTH012',
            ];
        }
    }
}
