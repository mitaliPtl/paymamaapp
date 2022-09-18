<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\User;
use App\SmsTemplate;
use Auth;
use Config;
use Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;


class UserVerificationController extends Controller
{

        public function verifyMobile(Request $request){
            // print_r($request->all());
           
            $check_user  =  User::where('mobile', $request->verify_mobile)->first();
            if($check_user){
                $user_id = $check_user['userId'];
                // print_r($check_user['userId']);

                $g_otp = rand(100000, 999999);

                $updated_otp = User::where('userId', $check_user['userId'])
                                    ->update([
                                            'logged_otp'=> $g_otp,
                                            'updatedDtm'=>now()
                                        ]);

                if($updated_otp){
                       
                      $message = $this->sendOTP($g_otp);
                    
                      if ($message) {
                        $template_id = SmsTemplate::where('alias', Config::get('constants.SMS_TEMPLATE_ALIAS.VERIFY_USER_OTP.name'))->first();
                            $msgResponse = $this->sendSms($message, $check_user->mobile, $template_id->template_id);
                            
                            $data = array(
                                'name'=> $check_user->first_name." ".$check_user->last_name,
                                'otp' => $g_otp
                            );
                            Mail::send('mail.otp',$data, function($msg) use($check_user) {
                                $msg->to($check_user->email, $check_user->first_name." ".$check_user->last_name);
                                $msg->subject('OTP to Reset your Password - PayMama');
                                $msg->from('hello@paymamaapp.in','PayMama - Business Made Easy');
                            });
                           
                        if ($msgResponse) {

                            return view('modules.user.verify_otp', compact('user_id'));
                            // return back()->with('success', 'Password has been reset successfully!!');
                        }
                    }
                       
                }

            }else{
                return back()->with('error', 'This Number is not Registered with us !! ');
            }
        }

        public function sendOTP($_otp){
            $msg='';
            $resetPwdOTP = SmsTemplate::where('alias', Config::get('constants.SMS_TEMPLATE_ALIAS.VERIFY_USER_OTP.name'))->first();
            if (isset($resetPwdOTP)) {
                // print_r($resetPwdOTP);
                $msg = __($resetPwdOTP->template, [
                    "otp" => $_otp
                ]);
            }
            return $msg;
        }

        public function verifyUser(Request $request){
                // print_r($request->all());

                $result = "";
                if (isset($request->otp) && $request->otp) {
                    $user = User::where('userId', $request->verify_user_id)->where('logged_otp', $request->otp)->first();
                    if ($user) {
                        $new_password = rand(100000, 999999);
                        
                        $new_password_hash = Hash::make($new_password);
                        
                        $user->is_verified = 1;
                        $user->password = $new_password_hash;
                        $user->save();
                       
                        $msg = $this->sendUserDetailsSMS($user, $new_password);
                        
                        return redirect('login')->with('message', $msg);
                    }
                }

                $message = "INVALID OTP";
                $user_id = $request->verify_user_id;
                return view('modules.user.verify_otp')->with(compact('message','user_id'));
        

        }

        public function sendUserDetailsSMS($user, $password){
            // $password = rand(100000, 999999);

            $message = $this->prepareResetPwdMsg($user->mpin, $password, $user->username);
            if ($message) {
                $template_id = SmsTemplate::where('alias', Config::get('constants.SMS_TEMPLATE_ALIAS.RESET_USER_PWD.name'))->first();
                $msgResponse = $this->sendSms($message, $user->mobile, $template_id->template_id);
                $data = array(
                    'name'=>$user->first_name." ".$user->last_name,
                    'username'=>$user->username,
                    'password'=>$password,
                    'mpin'=>$user->mpin
                );
                Mail::send('mail.reset',$data, function($msg) use($user) {
                    $msg->to($user->email, $user->first_name." ".$user->last_name)
                    ->subject('Login Credentials - PayMama')
                    ->from('hello@paymamaapp.in','PayMama - Business Made Easy');
                });
                if ($msgResponse) {
                    return 'Password has been reset successfully!!';
                }
            }
        }

        public function prepareResetPwdMsg($mpin, $password, $username)
        {
            $msg = "";
    
            $resetPwdSmsTemplate = SmsTemplate::where('alias', Config::get('constants.SMS_TEMPLATE_ALIAS.RESET_USER_PWD.name'))->first();
            if (isset($resetPwdSmsTemplate)) {
                $msg = __($resetPwdSmsTemplate->template, [
                    "username" => $username,
                    "mpin" => $mpin,
                    "password" => $password,
                ]);
            }
            return $msg;
        }
}
