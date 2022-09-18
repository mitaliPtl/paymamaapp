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
use App\Http\Controllers\User\UserVerificationController;

class APIUserVerificationController extends Controller
{
    public function verifyMobileAPI(Request $request){
        $check_user  =  User::where('mobile', $request->mobile)->first();
        if($check_user){
            $user_id = $check_user['userId'];

            $g_otp = rand(100000, 999999);

                $updated_otp = User::where('userId', $check_user['userId'])
                                    ->update([
                                            'logged_otp'=> $g_otp,
                                            'updatedDtm'=>now()
                                        ]);
                if($updated_otp){

                    $uv_controller = new UserVerificationController();

                    $msg = $uv_controller->sendOTP($g_otp);

                    if ($msg) {
                        $msgResponse = $uv_controller->sendSms($msg, $check_user->mobile);
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

                            $statusMsg = "Success!!";

                            $user_id = array(['user_id'=> $user_id]);

                            return $this->sendSuccess($user_id, $statusMsg);
                            // return view('modules.user.verify_otp', compact('user_id'));
                            // return back()->with('success', 'Password has been reset successfully!!');
                        }
                    }

                }
        }else{
            return $this->sendError("Sorry!! This Number is not Registered with us");
            // return back()->with('error', 'This Number is not Registered with us !! ');
        }
    }

    public function verifyUserAPI(Request $request){

        $result = "";
        if (isset($request->otp) && $request->otp) {
            $user = User::where('userId', $request->user_id)->where('logged_otp', $request->otp)->first();
            if ($user) {
                $new_password = rand(100000, 999999);
                
                $new_password_hash = Hash::make($new_password);
                
                $user->is_verified = 1;
                $user->password = $new_password_hash;
                $user->save();
                $uv_controller = new UserVerificationController();

                $msg = $uv_controller->sendUserDetailsSMS($user, $new_password);
                $statusMsg = "Username, Password and MPIN has been send to your mobile no";
                return $this->sendSuccess($msg, $statusMsg);
                // return redirect('login')->with('message', $msg);
            }
        }

        $message = "INVALID OTP";
        // $user_id = $request->verify_user_id;
        
        return $this->sendError($message);
        // return view('modules.user.verify_otp')->with(compact('message','user_id'));
    }
}