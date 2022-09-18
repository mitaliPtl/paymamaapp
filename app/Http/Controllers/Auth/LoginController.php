<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Config;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Session;

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

    protected function authenticated(Request $request, $user)
    {
        if($user->tfa == 1) {
            Session::put('userId',$user->userId);
            Session::put('username',$request->username);
            Session::put('password',$request->password);
            Auth::logout();
            return redirect('/verify-2fa');
        }
        $user->last_login_ip = $this->getRealIpAddr();
        if ($user->roleId == Config::get('constants.ADMIN')) {
            $user->is_verified = 0;
            $response = $user->save();
             Session::put('userId',$user->userId);
            Session::put('username',$request->username);
            Session::put('password',$request->password);
            if ($response) {
                return redirect()->route('admin-home');
            }
        } else {
            $user->save();
            return redirect('/home');
        }
        
        // return redirect('/home');
    }

    /**
     * Allowing Login with Mobile
     */
    public function username()
    {
        $login = request()->input('login');
        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : ($this->validate_phone_number($login) ? 'mobile' : 'username');
        request()->merge([$fieldType => $login]);
        return $fieldType;
    }

    protected function credentials(Request $request)
    {
        if ($this->username() == "email") {
            $credentials['email'] = $request->{$this->username()};
        } else if ($this->username() == "mobile") {
            $credentials['mobile'] = $request->{$this->username()};
        }else if ($this->username() == "username") {
            $credentials['username'] = $request->{$this->username()};
        }
        $credentials['password'] = $request->password;
        $credentials['activated_status'] = Config::get('constants.ACTIVE');
        $credentials['isDeleted'] = Config::get('constants.NOT-DELETED');

        return $credentials;
    }

    protected function validate_phone_number($phone)
    {
        $filtered_phone_number = filter_var($phone, FILTER_SANITIZE_NUMBER_INT);
        $phone_to_check = str_replace("-", "", $filtered_phone_number);
        if (strlen($phone_to_check) < 10 || strlen($phone_to_check) > 14) {
            return false;
        } else {
        return true;
        }
    }
    
    public function getRealIpAddr() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
          $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
          $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
     	} else {
          $ip = $_SERVER['REMOTE_ADDR'];
        }
        // return $ip;
        $ips = explode(',', $ip);
        return $ips[0];
    }
}
