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
        $hub_id = $user->userhub->hub_id ?? null;
        $hub = $user->userhub->hub->name ?? null;

        $profile = [];

        $display = 'all';
        if($request->display){
            $display = $request->display;
        }

        if($display == 'homepage'){
            $profile['user'] = [
                'user_id' => $user->users_id,
                'hub' => $hub,
                'full_name' => $user->full_name,
                'profile_picture_url' => $user->picture
            ];
        }else if($display == 'all'){ 
            $last_login = LogLogin::where('created_by', $user->username)
                ->latest()
                ->first();

            $client = $user->getClient() ?? null;
            $partner = $user->getPartner() ?? null;
            $organization_id = $client->organization_id ?? null;
            $client_id = $client->client_id ?? null;
            // $courier = $user->courier ?? null;
            $CourierService = new CourierService('api');
            $courier = $CourierService->get($request);

            if ($courier['res'] == 'error') {
                $subject_msg = 'Kurir';
                if ($request->lang && $request->lang == 'en') {
                    $subject_msg = 'Courier';
                }

                return [
                    'res' => 'error',
                    'status_code' => $courier['status_code'],
                    'msg' => $subject_msg . ' ' . $courier['msg'],
                    'trace_code' => $courier['trace_code'],
                ];
            }
            $courier = $courier['data'];

            $profile['user'] = [
                'user_id' => $user->users_id,
                'organization_id' => $organization_id,
                'client_id' => $client_id,
                'hub_id' => $hub_id,
                'hub' => $hub,
                'partner_id' => $partner->partner_id ?? 0,
                'partner' => $partner->name ?? '',
                'username' => $user->username,
                'gender' => $user->gender,
                'full_name' => $user->full_name,
                'email' => $user->email,
                'role' => $user->role->name ?? null,
                'phone_number' => $courier->phone,
                'vehicle' => [
                    'plate_number' => $courier->vehicle_number,
                    'type' => $courier->vehicle_type, 
                ],
                'last_login' => $last_login->created_date ?? null,
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
}
