<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Config;
use Illuminate\Support\Facades\Http;
use App\SmsGatewaySetting;
use App\User;
use DB;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendSuccess($result, $message = "")
    {
        $response = [
            'status' => true,
            'result' => $result,
            'message' => $message,
        ];
        return response()->json($response, 200);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'status' => false,
            'message' => $error,
        ];
        if (!empty($errorMessages)) {
            $response['result'] = $errorMessages;
        }
        return response()->json($response, $code);
    }

    /**
     * Send Sms providing Message and Phone No.
     */
    public function sendSms_old($message, $phone_nos)
    {
        $response = Http::get('http://smszone.smartwebeasy.com/rest/services/sendSMS/sendGroupSms?routeId=1&smsContentType=english', [
            'AUTH_KEY' => Config::get('constants.AUTH_KEY'),
            'senderId' => Config::get('constants.SENDER_ID'),
            'message' => $message,
            'mobileNos' => $phone_nos,
        ]);
        return $response;
    }
    public function sendSms($message, $phone_nos, $temp_id=null)
    {
        
        $gatway_sms =  SmsGatewaySetting::where('activated_status', 'YES')->where('is_deleted', '0')->get()->first();
        
        if ($gatway_sms) {
            $method_sms = $gatway_sms->alias;
            $call_sms = $this->$method_sms($message, $phone_nos, $gatway_sms, $temp_id);
            // print_r($call_sms);
            // exit();
            return $call_sms;
        }
       
        return false;
    }

    public function bulk_sms($message, $phone_nos, $gatway_sms, $temp_id){
       
        // 'https://bulksms.co/sendmessage.php'
        $response = Http::get($gatway_sms->api_url, [
            'user' =>$gatway_sms->username,
            'password' => $gatway_sms->password,
            'mobile' => $phone_nos,
            'message' => $message,
            'sender' => 'PYMAMA',
            'type' => '3',
            'template_id' => (isset($temp_id))? $temp_id :'123'
        ]);
        // print_r($response);
        // exit();
        return $response;
    }

    public function dolphin_techno_old($message, $phone_nos, $gatway_sms, $temp_id){
        $digits = 5;
        $rand=rand(pow(10, $digits-1), pow(10, $digits)-1);
        $sid="SRLSHD";
        $msg="".$rand."%20is%20your%20verification%20code.%20Regards%20Saral%20Shaadi.";
        // $url="http://cloud.smsindiahub.in/vendorsms/pushsms.aspx?user=".$username."&password=".$password."&msisdn=".$msisdn."&sid=".$sid."&msg=".$msg."&fl=0&gwid=2";
      
        $response = Http::get($gatway_sms->api_url, [
            'user' =>$gatway_sms->username,
            'password' => $gatway_sms->password,
            'msisdn' => $phone_nos,
            'sid'=>$sid,
            'msg' => $msg,
            'fl'=>'0',
            'gwid'=>'2'
        ]);
        // print_r( $response);
        return $response;
    }

    public function dolphin_techno($message, $phone_nos, $gatway_sms, $temp_id){
        $digits = 5;
        $rand=rand(pow(10, $digits-1), pow(10, $digits)-1);
        //echo "$rand and " . ($startDate + $rand);
        $username="anandkaushal.in";
        $password="Budd789";
        $msisdn=$phone_nos;
        $sid="SRLSHD";
        $msg="".$rand."%20is%20your%20verification%20code.%20Regards%20Saral%20Shaadi.";
        $url="http://cloud.smsindiahub.in/vendorsms/pushsms.aspx?user=".$username."&password=".$password."&msisdn=".$msisdn."&sid=".$sid."&msg=".$msg."&fl=0&gwid=2";
       $ch = curl_init();  
       curl_setopt($ch,CURLOPT_URL,$url);
       curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
       $response=curl_exec($ch);
       curl_close($ch);

        return $response;
    }


    public function smart_web_easy($message, $phone_nos, $gatway_sms , $temp_id){
        // 'http://smszone.smartwebeasy.com/rest/services/sendSMS/sendGroupSms?routeId=1&smsContentType=english'
        $response = Http::get($gatway_sms->api_url, [
            'AUTH_KEY' => $gatway_sms->username,
            'senderId' => $gatway_sms->password,
            'message' => $message,
            'mobileNos' => $phone_nos,
            
        ]);
        return $response;
    }

    public function sendNotification($firebase_tkn, $title, $body, $user_id)
    {
        $success = false;
        
      

        // 'Authorization: key=AIzaSyCnaR4RamDcMt3t3eFm2MatoxGXnx1VZJU',
            
        // AAAABaKhVcU:APA91bHT-s8HV1EKIyYXeJBWrtQvZ2vFk0rQRRTAaDkwCLZdaDLAoMwNsyAyVQpPI-2fkQoRu2RfRZDTNfvQhV-NAieEMPXwr1DWJFzg5lE4HpqOBxH6J5w6g2TO3aTKr9ZZAOkh29D0
                $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
               
               
                $notification = [
                    'title' => $title,
                    'body' => $body,
                    'sound' => 'mySound'
                ];
                $extraNotificationData = ["message" => $notification];

                $fcmNotification = [
                    'to' => $firebase_tkn, //single token
                    'notification' => $notification
                ];
                $headers = [ 
                    'Authorization: key=AAAABaKhVcU:APA91bHT-s8HV1EKIyYXeJBWrtQvZ2vFk0rQRRTAaDkwCLZdaDLAoMwNsyAyVQpPI-2fkQoRu2RfRZDTNfvQhV-NAieEMPXwr1DWJFzg5lE4HpqOBxH6J5w6g2TO3aTKr9ZZAOkh29D0',
                    'Content-Type: application/json'
                ];


                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $fcmUrl);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
                $result = curl_exec($ch);
                curl_close($ch);

                $success = true;
                
                //$message = $message + $result['message_id'];
           
                
                
                $insert_noti = DB::table('tbl_notifiaction_log')->insert([
                                                            'user_id' => $user_id, 
                                                            'title' => $title,
                                                            'body' => $body
                                                            ]);

        return true;

    }

}
