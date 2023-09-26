<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function index()
    {
        $pageConfigs = ['myLayout' => 'blank'];
        return view('content.login-page', ['pageConfigs' => $pageConfigs]);
    }

    public function login_validation(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if(Auth::attempt($data))
        {
            $request->session()->regenerate();

            $user = DB::table('users')->where('email', $request->email)->first();
            if($user->is_active != 1)
            {
                return redirect()->route('login')->with('failed', 'Your account has been inactivated. please contact your admin');
            }
            
            $role = DB::table('role')->where('role_id', $user->role_id)->first();
            
            $request->session()->put('userid', $user->users_id);
            $request->session()->put('fullname', $user->full_name);
            $request->session()->put('photo', $user->picture);
            $request->session()->put('role', $role->name);

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