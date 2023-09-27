<?php

namespace App\Http\Controllers\api;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\LogLogin;
use App\Helpers\Main;
use App\Helpers\ResponseFormatter;

class UserController extends Controller
{ 
    private $auth;

    public function __construct()
    {
        // $this->auth = new JWTAuth;
        // $this->auth = new Auth;
        $this->auth = auth('api');
    }

    public function profile(Request $request)
    {
        $validator = Main::validator($request, [
            'rules'=>[
                'display' => 'sometimes|string', 
            ],
        ]);
        
        if (!empty($validator)){
            return $validator;
        }
        $UserService = new \App\Services\UserService('api');
        $profile_service = $UserService->profile($request);
        
        $res = new ResponseFormatter;   

        if ($profile_service['res'] == 'error'){
            return $res::error($profile_service['status_code'], $profile_service['msg'], $res::traceCode($profile_service['trace_code']));
        } else {
            return $res::success($profile_service['msg'], $profile_service['data'], $profile_service['status_code']);
        } 
    }
}
