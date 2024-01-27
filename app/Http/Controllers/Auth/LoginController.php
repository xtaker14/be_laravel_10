<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Jenssegers\Agent\Facades\Agent;
use Illuminate\Http\Request;
use App\Models\LogLogin;
use Carbon\Carbon;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        //check user status
        if($user->is_active != 1)
        {
            $this->guard()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('failed', 'Your account has been inactivated. please contact your admin.');
        }

        $loglogin['ip']            = $request->ip();
        $loglogin['browser']       = Agent::browser();
        $loglogin['created_by']    = $user->username;
        $loglogin['modified_by']   = $user->username;
        $loglogin['created_date']  = Carbon::now();
        $loglogin['modified_date'] = Carbon::now();

        LogLogin::create($loglogin);
    }

    public function username()
    {
        return 'username';
    }
}
