<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Config;
use App\User;
use Illuminate\Support\Facades\Http;

class VerifyOtp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   
        if (Auth::check() && Auth::user()->roleId == Config::get('constants.ADMIN') && Auth::user()->is_verified == 1) {
            return $next($request);
        }
        $this->updateUserOTP();
        return redirect('/verify-otp');
    }

    public function updateUserOTP()
    {
        $otp = Config::get('constants.AD_LOGIN_PIN');
        
        // $otp = rand(100000,999999);
        $user = User::find((int) Auth::user()->userId);

        $user->is_verified = 0;
        $user->logged_otp = $otp;
        $user->save();

        if ($user) {
            // Disbled SMS functionality with for Login OTP
            // $this->sendSms("Hello User Your Verification OTP is " . $otp, $user->mobile);
        }
    }

    /**
     * Send Sms with credential details after successful user creation
     */
    public function sendSms($message, $phone_nos)
    {
        $response = Http::get('http://smszone.smartwebeasy.com/rest/services/sendSMS/sendGroupSms?routeId=1&smsContentType=english', [
            'AUTH_KEY' => Config::get('constants.AUTH_KEY'),
            'senderId' => Config::get('constants.SENDER_ID'),
            'message' => $message,
            'mobileNos' => $phone_nos,
        ]);
    }
}
