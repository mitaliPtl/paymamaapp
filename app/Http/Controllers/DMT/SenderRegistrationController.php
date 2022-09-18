<?php
namespace App\Http\Controllers\DMT;

use App\Http\Controllers\Controller;
use App\UserLoginSessionDetail;
use App\OperatorSetting;
use App\TransactionDetail;
use DB;
use Auth;
use Config;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\User;
// Import Guzzle Client
use GuzzleHttp\Client;
use App\DMTSender;
use App\DMTSenderBeneficaryList;
use App\SmsTemplate;
use App\ApiSetting;


class SenderRegistrationController extends Controller
{
    /**
     * Services View
     */
    public function index(Request $request)
    {
        return view("modules.DMT.sendercheck");
    }
    
    //Check Sender Exists
    public function checksenderexists(Request $request)
    {
        //Get Sender Details
        $sendermobileno=$request->sender_mobile_number;
        $sender_dtls=DMTSender::where('sender_mobile_number',$sendermobileno)->first();
        
        if(!$sender_dtls)
        {
            $data['error']="Sender Not Found of this Mobile Number. Please Register it ! .";
            return view("modules.DMT.sender_registeration", compact('sendermobileno','data'));
        }
        
        if($sender_dtls->sender_status!=0)
        {       $generatedotp=mt_rand(111111,999999);
                $message = $this->sendOTP($generatedotp);
                
                if ($message) {
                            $template_id = SmsTemplate::where('alias', Config::get('constants.SMS_TEMPLATE_ALIAS.SENDER_REGISTRATION.name'))->first();
                            $msgResponse = $this->sendSms($message, $sendermobileno, $template_id->template_id);
                            
                            $sender_dtls=DMTSender::where('sender_mobile_number',$sendermobileno)->first();
                            if(!$sender_dtls)
                            {
                                return view("modules.DMT.sender_registeration");
                            }
                            if ($msgResponse) {
    
                                return view("modules.DMT.otpverification", compact('sender_dtls'));

                            }
                    }
        }
        //Ends Here
        
        //Get Sender Beneficiary Details
        $sender_receipient_list=DMTSenderBeneficaryList::where('sender_mobile_number',$sendermobileno)->get();
        //Ends Here
        
        //Fetch Bank List
        $data['bankList'] = $this->getAllBankList();
        $data['bank_ifsc']=[];
        foreach ($data['bankList']['result']['bank_list'] as $key => $value) {
            $data['bank_ifsc'][$value['bank_code']] = $value['ifsc_prefix'];
        }
        
        //Ends Here
        $sender_by_acc="";
        
        return view("modules.DMT.beneficiarylist", compact('data', 'sender_dtls', 'sender_receipient_list', 'sender_by_acc'));
    }
    //Check Sender Exists
    
    //Get Bank List
    public function getAllBankList()
    {

        $bank_list = DB::table('tbl_bank_list')->orderBy(trim('BANK_NAME'),'ASC')->get();
        $bank_array = [];
        foreach ($bank_list as $key => $val) {
           $bank_array[$key] =  [   "bank_id"=>trim($val->BankID),
                                    "bank_name"=>trim($val->BANK_NAME),
                                    "bank_code"=>trim($val->ShortCode),
                                    "bank_icon"=>$val->bank_icon,
                                    "neft_allowed"=>(trim($val->NEFT_Status)=="Enabled")?'Y':'N',
                                    "imps_allowed"=>(trim($val->IMPS_Status)=="Enabled")?'Y':'N',
                                    "account_verification_allowed"=>(trim($val->IsVerficationAvailable)=="On")?'Y':'N',
                                    "ifsc_prefix"=>$val->ifsc_prefix,
                                ];
        }

        $response['result']['bank_list'] = $bank_array;

        return $response;
    }
    //End Get Bank List
    
    //Register Sender
     public function registerSender(Request $request){
         
        
        $sendermobileno=$request->mobile_no;
        $generatedotp=mt_rand(111111,999999);
        
        $requestBody = [
                            "sender_mobile_number" => $request->mobile_no,
                            "first_name"=> $request->reg_first_name,
                            "last_name"=> $request->reg_last_name,
                            "pincode"=> $request->pincode,
                            "additional_registration_data"=> $request->pincode,
                            "available_limit"=> 200000,
                            "used_limit"=> 0,
                            "Upi_available_limit"=> 200000,
                            "Upi_used_limit"=> 0,
                            "available_limit_crazy"=> 200000,
                            "used_limit_crazy"=> 0,
                            "api_name"=>"paytm",
                            "otp"=>$generatedotp,
                        ];
        $sender_insert=DMTSender::insert($requestBody);
        if($sender_insert)
        {
              $message = $this->sendOTP($generatedotp);
              
              if ($message) {
                            $template_id = SmsTemplate::where('alias', Config::get('constants.SMS_TEMPLATE_ALIAS.SENDER_REGISTRATION.name'))->first();
                            $msgResponse = $this->sendSms($message, $sendermobileno, $template_id->template_id);
                            
                            $sender_dtls=DMTSender::where('sender_mobile_number',$sendermobileno)->first();
                            if(!$sender_dtls)
                            {
                                return view("modules.DMT.sender_registeration");
                            }
                            if ($msgResponse) {
    
                                return view("modules.DMT.otpverification", compact('sender_dtls'));

                            }
                    }
              
            //   $msg="Dear Customer Your One Time Password (OTP) For Sender Registration is :".$rand.".www.paymamaapp.in";
            //   $url = "https://www.bulksms.co/sendmessage.php?user=NAIDUSOFTWARE&password=4049102&mobile=".$msisdn."&message=".$msg."&sender=".$sender."&type=3&template_id=".$template_id;
              
            //   $ch = curl_init();  
            //   curl_setopt($ch,CURLOPT_URL,$url);
            //   curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
            //   $output=curl_exec($ch);
            //   echo $output;
            //   curl_close($ch);
            //   exit;
              $sender_dtls=DMTSender::where('sender_mobile_number',$sendermobileno)->first();
              if(!$sender_dtls)
              {
                  
                    return view("modules.DMT.sender_registeration");
              }
            
             return view("modules.DMT.otpverification", compact('sender_dtls'));
        }
        else
        {
            $data['error']="Something Went Wrong! Kindly Try Again";
            return view("modules.DMT.sender_registeration", compact('data'));
        }
        
    }
    
    //End Register Sender
    
    //Reset Sender Limit
    public function resetlimit()
    {
            $requestBody =  [
                          "available_limit"=>200000,
                        ];
            $sender_update=DMTSender::where('id','>=',0)->update($requestBody);
    }
    //Ends Here
    
    
    
    //Verify OTP
    public function verifyOTP(REQUEST $request)
    {
        $sendermobileno=$request->mob_no;
        $reg_otp=$request->reg_otp;
        $sender_dtls_check=DMTSender::where('sender_mobile_number',$sendermobileno)->where('otp',$reg_otp)->first();
        if(!$sender_dtls_check)
        {
            $sender_dtls=DMTSender::where('sender_mobile_number',$sendermobileno)->first();
            $data['error']="OTP Incorrect! Kindly Try Again or Click On Resend OTP";
            return view("modules.DMT.otpverification", compact('sender_dtls','data'));
        }
        else
        {
             $requestBody =  [
                          "sender_status"=>0,
                        ];
            $sender_update=DMTSender::where('sender_mobile_number',$sendermobileno)->update($requestBody);
            $sender_dtls=DMTSender::where('sender_mobile_number',$sendermobileno)->first();
            
            
            if(!$sender_dtls)
            {
                return view("modules.DMT.sender_registeration");
            }
            
            $create_razorpay=$this->createContact_Razorpay($sender_dtls);
            $contactid=$create_razorpay['id'];
             $requestBody =  [
                          "razorpay_contact_id"=>$contactid,
                        ];
             $sender_update=DMTSender::where('sender_mobile_number',$sendermobileno)->update($requestBody);
            
            //Get Sender Beneficiary Details
            $sender_receipient_list=DMTSenderBeneficaryList::where('sender_mobile_number',$sendermobileno)->get();
            //Ends Here
            
            //Fetch Bank List
            $data['bankList'] = $this->getAllBankList();
            $data['bank_ifsc']=[];
            foreach ($data['bankList']['result']['bank_list'] as $key => $value) {
                $data['bank_ifsc'][$value['bank_code']] = $value['ifsc_prefix'];
            }
            
            //Ends Here
            $sender_by_acc="";
            
            return view("modules.DMT.beneficiarylist", compact('data', 'sender_dtls', 'sender_receipient_list', 'sender_by_acc'));
        }
    }
    //Ends
    
    //Create Razorpay Sender contact id
    public function createContact_Razorpay($user){
   

        $czy_api = ApiSetting::where('api_alias', 'rezorpay')->first();
        $username = $czy_api->key_id;
        $password = $czy_api->api_secretkey;
        $api_url = $czy_api->api_url;
        $curl = curl_init();
        
        $post_data = array("name"=>$user->first_name.' '.$user->last_name,"contact"=>$user->sender_mobile_number);
        $user_pass = "$username:$password";
        curl_setopt_array($curl, array(
                                        CURLOPT_URL => $api_url.'contacts',
                                        CURLOPT_RETURNTRANSFER => true,
                                        CURLOPT_ENCODING => '',
                                        CURLOPT_MAXREDIRS => 10,
                                        CURLOPT_TIMEOUT => 0,
                                        CURLOPT_FOLLOWLOCATION => true,
                                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                        CURLOPT_CUSTOMREQUEST => 'POST',
                                        CURLOPT_USERPWD => "$username:$password",
                                        CURLOPT_POSTFIELDS =>json_encode($post_data),
                                        CURLOPT_HTTPHEADER => array(
                                          'Content-Type: application/json',
                                          'Authorization: Basic cnpwX2xpdmVfaFpvd2g4NnlVSEVJNmM6TTFWZGNkbFRkZ3c4OVUwTmRCVEZVOEEw'
                                        ),
                                      )
                          );
                   
        $response = curl_exec($curl);
    
        curl_close($curl);
   
        return json_decode( $response, true);
   }
    //Ends Here
    
    //Fetch All Contact Details
    public function fetch_all_contact_details()
    {
         $curl = curl_init();
        
        $czy_api = ApiSetting::where('api_alias', 'rezorpay')->first();
        $username = $czy_api->key_id;
        $password = $czy_api->api_secretkey;
        $user_pass = "$username:$password";
        curl_setopt_array($curl, array(
                                        CURLOPT_URL => 'https://api.razorpay.com/v1/contacts/cont_JdoofER8zSeLsj',
                                        CURLOPT_RETURNTRANSFER => true,
                                        CURLOPT_ENCODING => '',
                                        CURLOPT_MAXREDIRS => 10,
                                        CURLOPT_TIMEOUT => 0,
                                        CURLOPT_FOLLOWLOCATION => true,
                                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                        CURLOPT_CUSTOMREQUEST => 'GET',
                                        CURLOPT_USERPWD => "$username:$password",
                                        CURLOPT_HTTPHEADER => array(
                                          'Content-Type: application/json',
                                          'Authorization: Basic cnpwX2xpdmVfaFpvd2g4NnlVSEVJNmM6TTFWZGNkbFRkZ3c4OVUwTmRCVEZVOEEw'
                                        ),
                                      )
                          );
                   
        $response = curl_exec($curl);
    
        curl_close($curl);
   
        return json_decode( $response, true);
    }
    //Ends Here
    
    
    //Resend OTP
    public function resendOTP(REQUEST $request)
    {
        $sendermobileno=$request->mobile_no;
        $generatedotp=mt_rand(111111,999999);
        
        $requestBody =  [
                          "otp"=>$generatedotp,
                        ];
        $sender_update=DMTSender::where('sender_mobile_number',$sendermobileno)->update($requestBody);
        if($sender_update)
        {
            $message = $this->sendOTP($generatedotp);
                  
            if ($message) 
            {
                $template_id = SmsTemplate::where('alias', Config::get('constants.SMS_TEMPLATE_ALIAS.SENDER_REGISTRATION.name'))->first();
                $msgResponse = $this->sendSms($message, $sendermobileno, $template_id->template_id);
                                
                $sender_dtls=DMTSender::where('sender_mobile_number',$sendermobileno)->first();
                if(!$sender_dtls)
                {
                    return view("modules.DMT.sender_registeration");
                }
                if ($msgResponse) {
        
                    return view("modules.DMT.otpverification", compact('sender_dtls'));
    
                }                          
            }
        }
        else
        {
            $sender_dtls=DMTSender::where('sender_mobile_number',$sendermobileno)->first();
            $data['error']="OTP Incorrect! Kindly Try Again or Click On Resend OTP";
            return view("modules.DMT.otpverification", compact('sender_dtls','data'));
        }
    }
    //Ends
    
    //Get Sender Registeration OTP Template
     public function sendOTP($_otp){
            $msg='';
            $resetPwdOTP = SmsTemplate::where('alias', Config::get('constants.SMS_TEMPLATE_ALIAS.SENDER_REGISTRATION.name'))->first();
            if (isset($resetPwdOTP)) {
                // print_r($resetPwdOTP);
                $msg = __($resetPwdOTP->template, [
                    "otp" => $_otp
                ]);
            }
            return $msg;
        }
    //End

    
    //After Succesfull OTP Sender
     public function registerSender1(Request $request){
         
        
        $sendermobileno=$request->mobile_no;
        
        
        $requestBody = [
                            "sender_mobile_number" => $request->mobile_no,
                            "first_name"=> $request->reg_first_name,
                            "last_name"=> $request->reg_last_name,
                            "pincode"=> $request->pincode,
                            "additional_registration_data"=> $request->pincode,
                            "available_limit"=> 100000,
                            "used_limit"=> 0,
                            "Upi_available_limit"=> 100000,
                            "Upi_used_limit"=> 0,
                            "available_limit_crazy"=> 100000,
                            "used_limit_crazy"=> 0,
                            "api_name"=>"paytm",
                            "sender_status"=>1,
                        ];
        $sender_insert=DMTSender::insert($requestBody);
        if($sender_insert)
        {
            $sender_dtls=DMTSender::where('sender_mobile_number',$sendermobileno)->first();
            if(!$sender_dtls)
            {
                return view("modules.DMT.sender_registeration");
            }
            //Ends Here
            
            //Get Sender Beneficiary Details
            $sender_receipient_list=DMTSenderBeneficiaryList::where('sender_mobile_number',$sendermobileno)->get();
            //Ends Here
            
            //Fetch Bank List
            $data['bankList'] = $this->getAllBankList();
            $data['bank_ifsc']=[];
            foreach ($data['bankList']['result']['bank_list'] as $key => $value) {
                $data['bank_ifsc'][$value['bank_code']] = $value['ifsc_prefix'];
            }
            
            //Ends Here
            $sender_by_acc="";
            
            return view("modules.DMT.beneficiarylist", compact('data', 'sender_dtls', 'sender_receipient_list', 'sender_by_acc'));
        }
        else
        {
            $data['error']="Something Went Wrong! Kindly Try Again";
            return view("modules.DMT.sender_registeration", compact('data'));
        }
        
    }
    //End
    
}