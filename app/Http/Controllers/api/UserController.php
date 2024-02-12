<?php

namespace App\Http\Controllers\Api;
 
use App\Http\Controllers\Api\Controller as ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Helpers\Main;
use App\Helpers\ResponseFormatter;

use App\Services\UserService;

class UserController extends ApiController
{ 
    private UserService $userService;
    private $auth;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
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
        $profile_service = $this->userService->profile($request, $this->auth);
        
        $res = new ResponseFormatter;
        
        return $this->resService($res, $profile_service);
    }
}
