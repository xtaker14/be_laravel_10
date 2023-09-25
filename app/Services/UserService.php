<?php

namespace App\Services;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\LogLogin;
use App\Helpers\Main;

class UserService
{
    private $auth;

    public function __construct($auth='api')
    { 
        $this->auth = auth($auth);
    }

    public function profile(Request $request)
    {   
        $user = $this->auth->user();  
        $organization_id = $user->organization_ids;
        $client_id = $user->client_ids;
        $hub_id = $user->hub_ids;
        $couriers = $user->getCouriers();

        $last_login = LogLogin::where('created_by', $user->email)
            ->latest()
            ->first(); 

        $profile['user'] = [
            'user_id' => $user->users_id,
            'organization_id' => $organization_id,
            'client_id' => $client_id,
            'hub_id' => $hub_id,
            'username' => $user->email,
            'full_name' => $user->full_name,
            'email' => $user->email,
            'role' => $user->role->name,
            'phone_number' => $couriers->phone ?? null,
            'vehicle' => [
                'plate_number' => $couriers->vehicle_number ?? null,
                'type' => $couriers->vehicle_type ?? null,
                'capacity' => 0
            ],
            'last_login' => $last_login->created_date ?? null,
            'profile_picture_url'  => $user->picture
        ];

        return [
            'res' => 'success',
            'status_code' => 200,
            'msg' => __('messages.success'),
            'trace_code' => null,
            'data' => $profile,
        ];
    }
}
