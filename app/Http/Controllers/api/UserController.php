<?php

namespace App\Http\Controllers\api;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Helpers\Main;
use App\Helpers\ResponseFormatter;

class UserController extends Controller
{ 
    public function profile(Request $request)
    {
        $validator = Main::validator($request, [
            'rules'=>[
                'show' => 'sometimes|string', 
            ],
        ]);
        
        if (!empty($validator)){
            return $validator;
        }

        $user = Auth::user();
        if (!$user) {
            return ResponseFormatter::error(401, __('messages.unauthenticated')); 
        }

        $profile = [
            'name' => $user->name,
            'email' => $user->email,
        ];

        switch ($request->show) {
            case 'all':
                $profile['roles'] = $user->getAllRolesName();
                $profile['permissions'] = $user->getAllPermissionsName();
                break;
            
            default:
                # code...
                break;
        }

        return ResponseFormatter::success(__('messages.success'), $profile);
    }
}
