<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\LogLogin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Jenssegers\Agent\Facades\Agent;
use Session;

class LoginController extends Controller
{
    public function index(Request $request)
    {
        return view('content.login');
    }

    public function login_validation(Request $request)
    {
        if(Session::has('username'))
        {
            return redirect()->route('dashboard');
        }

        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $data = [
            'username' => $request->username,
            'password' => $request->password
        ];

        if(Auth::attempt($data))
        {
            $request->session()->regenerate();

            $user = DB::table('users')->where('username', $request->username)->first();
            if($user->is_active != 1)
            {
                return redirect()->route('login')->with('failed', 'Your account has been inactivated. please contact your admin');
            }
            
            $role = DB::table('role')->where('role_id', $user->role_id)->first();
            if($role->name == 'COURIER')
            {
                return redirect()->route('login')->with('failed', 'Forbidden Access');
            }

            $hub = DB::table('usershub')->where('users_id', $user->users_id)->first();
            if(!$hub)
            {
                return redirect()->route('login')->with('failed', 'Hub users not found');
            }

            $request->session()->put('userid', $user->users_id);
            $request->session()->put('username', $user->username);
            $request->session()->put('fullname', $user->full_name);
            $request->session()->put('photo', 'template/assets/img/website/profile/'.$user->picture.'');
            $request->session()->put('role', $role->name);
            $request->session()->put('hubid', $hub->hub_id);

            $client = DB::table('usersclient as a')
                ->select('b.organization_id', 'b.client_id', 'b.name')
                ->join('client as b', 'a.client_id','=','b.client_id')
                ->where('a.users_id', $user->users_id)->first();

            $request->session()->put('clientid', $client->client_id);
            $request->session()->put('orgid', $client->organization_id);

            $loglogin['ip']            = $request->ip();
            $loglogin['browser']       = Agent::browser();
            $loglogin['created_by']    = $user->username;
            $loglogin['modified_by']   = $user->username;
            $loglogin['created_date']  = Carbon::now();
            $loglogin['modified_date'] = Carbon::now();

            LogLogin::create($loglogin);

            return redirect()->route('dashboard');
        }
        else
        {
            return redirect()->route('login')->with('failed', 'The email and password you entered did not matched our record. Please double check and try again.');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}