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
        $res = new ResponseFormatter;  

        $profile['user'] = [
            'user_id' => $user->id,
            'organization_id' => 1,
            'client_id' => 1,
            'hub_id' => 1,
            'username' => $user->username,
            'full_name' => 'John Doe',
            'email' => $user->email,
            'role' => 'Driver',
            'phone_number' => '+1234567890',
            'vehicle' => [
                'plate_number' => 'AB 123 CD',
                'type' => 'Motorcycle',
                'capacity' => '100 kilogram'
            ],
            'last_login' => null,
            'profile_picture_url'  => null
        ];

        // switch ($request->show) {
        //     case 'all':
        //         $profile['roles'] = $user->getAllRolesName();
        //         $profile['permissions'] = $user->getAllPermissionsName();
        //         break; 
        // }

        return $res::success(__('messages.success'), $profile);
    }
}
