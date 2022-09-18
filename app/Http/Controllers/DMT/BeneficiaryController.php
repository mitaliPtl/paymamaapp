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
use App\DMTVerificationList;
use App\ApiSetting;
use App\BankAccount;


class BeneficiaryController extends Controller
{
   //Add Beneficiary
   public function adddmtbeneficiary(Request $request)
   {
              
        //Fetch Bank List
        $data['bankList'] = $this->getAllBankList();
        $data['bank_ifsc']=[];
        foreach ($data['bankList']['result']['bank_list'] as $key => $value) {
            $data['bank_ifsc'][$value['bank_code']] = $value['ifsc_prefix'];
        }
        
        return view('modules.DMT.addbeneficiary', compact('data','request'));
   }
   //Ends Here
   
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
    
   
     
    
    //Insert Beneficary
    public function insertbeneficiary(Request $request)
    {       
            $sender_receipient_list=DMTSenderBeneficaryList::where('bank_account_number',$request->beneficiary_acc_no)->first();
            
            if($sender_receipient_list)
            {
                $data['error']="Beneficiary Already Exist ! ";
                 //Fetch Bank List
                $data['bankList'] = $this->getAllBankList();
                $data['bank_ifsc']=[];
                foreach ($data['bankList']['result']['bank_list'] as $key => $value) {
                    $data['bank_ifsc'][$value['bank_code']] = $value['ifsc_prefix'];
                }
                
                return view('modules.DMT.addbeneficiary', compact('data','request'));
            }
            
            
            
            
            $sender_receipient_list=DB::table('tbl_dmt_benificiary_dtls_new')->orderBy('recipient_id', 'desc')->first();
            if($sender_receipient_list)
            {
                $newcfreeid=$sender_receipient_list->recipient_last_cashfree+1;
                $Cfreeid="CFB".$newcfreeid;
            }
            else
            {
                $Cfreeid="CFB10001";
                $newcfreeid=10001;
            }
            
            
            $bankDtls = DB::table('tbl_bank_list')->where('ShortCode', $request->bank_code)->first();
            
            
            $bank_name = $bankDtls->BANK_NAME;
           
            $isVerified = 'N';
            // $verifiedname = '';
            // if ($request->is_verified == '1') {
            //   $isVerified = 'Y';
            //   $verifiedname = $data['recipient_name'];
            // }
          $recipientInfo = array(
            'recipient_name' =>$request->beneficiary_name,
            'bank_name' =>$bank_name,
            'bank_code' =>$request->bank_code,
            'bank_account_number' => $request->beneficiary_acc_no,
            'ifsc' => $request->beneficiary_ifsc,  
            'recipient_status' =>'',
            'is_verified' =>$isVerified,       
            'verified_name' =>'',
            'recipient_mobile_number' =>$request->beneficiary_mobile,
            'sender_mobile_number'=>$request->sender_mobile_no,
            'cfree_beneficiaryid' =>$Cfreeid,
            'recipient_last_cashfree' =>$newcfreeid
          );
          $sender_insert=DMTSenderBeneficaryList::insert($recipientInfo);
          $sendermobileno=$request->sender_mobile_no;
          $sender_dtls=DMTSender::where('sender_mobile_number',$sendermobileno)->first();
          
          //Get Sender Beneficiary Details
          $sender_receipient_list=DMTSenderBeneficaryList::where('sender_mobile_number',$sendermobileno)->get();
          //Ends Here
        
         //Create Beneficiary Fund Account ID
         $resp_fundaccount = $this->fundAccount_Rezorpay($request,  $sender_dtls->razorpay_contact_id);
         //Ends Here
        
        if($sender_insert)
        {
        
          //Generate Receipient in Cashfree
              //Generate Token
              $clientid="CF154737C6L0GFLJDDO8UP2KFU3G";
              $clientsecret="11bd7c7cc53eb959188b10fcc7c282a067ad4997";
              $url="https://payout-api.cashfree.com/payout/v1/authorize";
              $ch = curl_init($url);
              curl_setopt($ch, CURLOPT_POST, true);
              curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "X-Client-Id:" . $clientid, "X-Client-Secret:" . $clientsecret)); 
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
              $result_json = curl_exec($ch);
              
              curl_close($ch);
              $result =  json_decode($result_json, true); 
              
              $token=$result['data']['token'];
              
              //End
              //Verify token
             
              $url="https://payout-api.cashfree.com/payout/v1/verifyToken";
              $ch = curl_init($url);
              curl_setopt($ch, CURLOPT_POST, true);
              curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization:Bearer " . $token)); 
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
              $result_json = curl_exec($ch);
              
              curl_close($ch);
              $result =  json_decode($result_json, true); 
              //End
             
              $last_beneficiary_id=DMTSenderBeneficaryList::where('sender_mobile_number',$sendermobileno)->first();
              $cashfreebeneif="CFB".$last_beneficiary_id->recipient_last_cashfree;
              $url="https://payout-api.cashfree.com/payout/v1/addBeneficiary";
              $ch = curl_init($url);
              $user = User::where('userId', Auth::user()->userId)->first();
              $Params["beneId"]= $cashfreebeneif;
              $Params["name"]= $request->beneficiary_name;
              $Params["email"] = $user->email;
              $Params["phone"] = $request->beneficiary_mobile;
              $Params["bankAccount"]= $request->beneficiary_acc_no;
              $Params["ifsc"] = $request->beneficiary_ifsc;
              $Params["address1"] ="ABC Street";
              $Params["address2"] = "ABC Streetssss";
              $Params["city"] = "";
              $Params["state"] = "";
              $Params["pincode"] = "";
            
              $post_data = json_encode($Params, JSON_UNESCAPED_SLASHES);
            
              curl_setopt($ch, CURLOPT_POST, true);
              curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
              curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization:Bearer " . $token)); 
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
              $result_json = curl_exec($ch);
              
              curl_close($ch);
              $result =  json_decode($result_json, true);
             
              if($result['status']!="SUCCESS")
              {
                $data['error']="Oops, Error While Adding Benficiary";
                 //Fetch Bank List
                $data['bankList'] = $this->getAllBankList();
                $data['bank_ifsc']=[];
                foreach ($data['bankList']['result']['bank_list'] as $key => $value) {
                    $data['bank_ifsc'][$value['bank_code']] = $value['ifsc_prefix'];
                }
                
                return view('modules.DMT.addbeneficiary', compact('data','request'));
              }
        }
        else
        {
           
                $data['error']="Oops, Error While Adding Benficiary";
                 //Fetch Bank List
                $data['bankList'] = $this->getAllBankList();
                $data['bank_ifsc']=[];
                foreach ($data['bankList']['result']['bank_list'] as $key => $value) {
                    $data['bank_ifsc'][$value['bank_code']] = $value['ifsc_prefix'];
                }
                
                return view('modules.DMT.addbeneficiary', compact('data','request'));
        }
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
    //Ends
    
    
    public function fundAccount_Rezorpay($data,  $razorpay_contact_id){
        $czy_api = ApiSetting::where('api_alias', 'rezorpay')->first();
        $username = $czy_api->key_id;
        $password = $czy_api->api_secretkey;
        $api_url = $czy_api->api_url;

    $curl = curl_init();
    $datatobesent=array(
                                    CURLOPT_URL => $api_url.'fund_accounts',
                                    CURLOPT_RETURNTRANSFER => true,
                                    CURLOPT_ENCODING => '',
                                    CURLOPT_MAXREDIRS => 10,
                                    CURLOPT_TIMEOUT => 0,
                                    CURLOPT_FOLLOWLOCATION => true,
                                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                    CURLOPT_CUSTOMREQUEST => 'POST',
                                    CURLOPT_USERPWD => "$username:$password",
                                    CURLOPT_POSTFIELDS =>'{
                                    "contact_id": "'.$razorpay_contact_id.'",
                                    "account_type": "bank_account",
                                    "bank_account": {
                                      "name": "'.$data->beneficiary_name.'", 
                                      "ifsc": "'.$data->beneficiary_ifsc.'",
                                      "account_number": "'.$data->beneficiary_acc_no.'"
                                    }
                                  }',
                                    CURLOPT_HTTPHEADER => array(
                                      'Content-Type: application/json',
                                      'Authorization: Basic cnpwX2xpdmVfaFpvd2g4NnlVSEVJNmM6TTFWZGNkbFRkZ3c4OVUwTmRCVEZVOEEw'
                                    ),
                                );
    
    curl_setopt_array($curl, $datatobesent);
    
    $response = curl_exec($curl);
    
    curl_close($curl);
    // echo $response;

      return json_decode($response, true);
   }
    
    
    public function getifsc(Request $request)
    {
        $bankcode=$request->bankcode;
        $GetBankName=DB::table('tbl_bank_list')->where('ShortCode',$bankcode)->first();
        return $GetBankName->ifsc_prefix;
    }
    
    
    
    public function verifybankaccount(Request $request)
    {
        $bank_account_number=$request->bankaccountno;
        $ifsc=$request->bankifsc;
        $bankname=$request->bankname;
        $name=$request->name;
        $phone=$request->phone;
        
        
      //  Check if Bankaccount no already exist in database
                $bank_acc_no = DMTVerificationList::where('bank_acc_no', $bank_account_number)->first();
                if($bank_acc_no)
                {
                    return "Bank Account details verified successfully";
                }
        //Ends Here
        
        
      //  Cashfree Bank Validation Started
        //           Generate Token
                  $clientid="CF154737C6L0GFLJDDO8UP2KFU3G";
                  $clientsecret="11bd7c7cc53eb959188b10fcc7c282a067ad4997";
                  $url="https://payout-api.cashfree.com/payout/v1/authorize";
                  $ch = curl_init($url);
                  curl_setopt($ch, CURLOPT_POST, true);
                  curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "X-Client-Id:" . $clientid, "X-Client-Secret:" . $clientsecret)); 
                  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
                  $result_json = curl_exec($ch);
                  
                  curl_close($ch);
                  $result =  json_decode($result_json, true); 
                  
                  $token=$result['data']['token'];
                 
                 //End
                 //Verify token
                  $url="https://payout-api.cashfree.com/payout/v1/verifyToken";
                  $ch = curl_init($url);
                  curl_setopt($ch, CURLOPT_POST, true);
                  curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization:Bearer " . $token)); 
                  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
                  $result_json = curl_exec($ch);
                  
                  curl_close($ch);
                  $result =  json_decode($result_json, true);
                  //Ends
                  
                  
                  $url="https://payout-api.cashfree.com/payout/v1.2/validation/bankDetails?name=".$name."&phone=".$phone."&bankAccount=".$bank_account_number."&ifsc=".$ifsc;
                  $ch = curl_init($url);
                  curl_setopt($ch, CURLOPT_HTTPGET, true);
                  curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json", "Authorization:Bearer " . $token)); 
                  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
                  $result_json = curl_exec($ch);
                  
                  curl_close($ch);
                  $result =  json_decode($result_json, true);
                  
                  
                  if($result['status'] == "SUCCESS")
                  {
                        $recipientInfo = array(
                        'bank_acc_no' =>$bank_account_number,
                        'bank_ifsc' =>$ifsc,
                        'bank_holder_name' =>$result['data']['nameAtBank']
                      );
                      $sender_insert=DMTVerificationList::insert($recipientInfo);  
                      if($sender_insert)
                      {
                          return $result['message'];
                      }
                      else
                      {
                          
                      }
                  }
                  else
                  {
                      
                  }
                 
        //Ends Here
        
        
        // //Hypto Verification Begins Now
        // $api = ApiSetting::where('api_id',13)->first();
        // $url      =$api->api_url."/api/verify/bank_account";
        // $api_token="bf15086e-807c-41e9-af85-dda3270fbfbf";
        
        // $order_id=mt_rand(1111,9999);
        
        // $post=array("ifsc"   =>$ifsc,
        //             "number"=>$bank_account_number,
        //             "reference_number"=>$order_id);
        
        
        //  $ch = curl_init($url);
        // # Setup request to send json via POST.
        // $payload = json_encode($post);
        // curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        // curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:'.$api_token.''));
        // # Return response instead of printing.
        // curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        // # Send request.
        // $result_json = curl_exec($ch);
       
        // curl_close($ch);
        // $result =  json_decode($result_json, true); 
       
        // if($result['success']=="true")
        // {
        //     $recipientInfo = array(
        //                 'bank_acc_no' =>$bank_account_number,
        //                 'bank_ifsc' =>$ifsc,
        //                 'bank_holder_name' =>$name
        //               );
        //               $sender_insert=DMTVerificationList::insert($recipientInfo);  
        //               if($sender_insert)
        //               {
        //                   return "Bank Account details verified successfully";
        //               }
        //               else
        //               {
                          
        //               }
        // }
        // else
        // {
        //   return "Bank Account details Not verified successfully"; 
        // }
      
        //Ends Here
        
        
    }
    
    //Delete Beneficiary
    public function deletedmtbeneficiary(Request $request)
    {
        $mpin=$request->mpin;
        $beneficiaryid=$request->beneficiaryid;
        $senderno=$request->senderno;
        
        # First Match Mpin of retailer
        $user = User::where('userId', Auth::user()->userId)->first();
        
        if($user->mpin == $mpin)
        {
            $delete=DMTSenderBeneficaryList::where('recipient_id', $beneficiaryid)->delete();
            
            //Get Sender Beneficiary Details
            $sender_receipient_list=DMTSenderBeneficaryList::where('sender_mobile_number',$senderno)->get();
            //Ends Here
            
            //Fetch Bank List
            $data['bankList'] = $this->getAllBankList();
            $data['bank_ifsc']=[];
            foreach ($data['bankList']['result']['bank_list'] as $key => $value) {
                $data['bank_ifsc'][$value['bank_code']] = $value['ifsc_prefix'];
            }
            //Ends Here
            $data['success']="Beneficiary Deleted Successfully";
            $sender_by_acc="";
            $sender_dtls=DMTSender::where('sender_mobile_number',$senderno)->first();
            return view("modules.DMT.beneficiarylist", compact('data', 'sender_dtls', 'sender_receipient_list', 'sender_by_acc'));
        }
        else
        {
            //Get Sender Beneficiary Details
            $sender_receipient_list=DMTSenderBeneficaryList::where('sender_mobile_number',$senderno)->get();
            //Ends Here
            $data['error']="Mpin Incorrect";
            //Fetch Bank List
            $data['bankList'] = $this->getAllBankList();
            $data['bank_ifsc']=[];
            foreach ($data['bankList']['result']['bank_list'] as $key => $value) {
                $data['bank_ifsc'][$value['bank_code']] = $value['ifsc_prefix'];
            }
            //Ends Here
            
            $sender_by_acc="";
            $sender_dtls=DMTSender::where('sender_mobile_number',$senderno)->first();
            return view("modules.DMT.beneficiarylist", compact('data', 'sender_dtls', 'sender_receipient_list', 'sender_by_acc'));
        }
        #Ends Here
        
        
    }
    //Ends Here
    
    
    public function BeneficiaryList($senderno)
    {
        
        //Get Sender Beneficiary Details
        $sender_receipient_list=DMTSenderBeneficaryList::where('sender_mobile_number',$senderno)->get();
        //Ends Here
        
        //Fetch Bank List
        $data['bankList'] = $this->getAllBankList();
        $data['bank_ifsc']=[];
        foreach ($data['bankList']['result']['bank_list'] as $key => $value) {
            $data['bank_ifsc'][$value['bank_code']] = $value['ifsc_prefix'];
        }
        
        //Ends Here
        $sender_by_acc="";
        $sender_dtls=DMTSender::where('sender_mobile_number',$senderno)->first();
       
    }
    
    public function deleteallbeneficiary()
    {
         //Generate Receipient in Cashfree
              //Generate Token
              $clientid="CF154737C6L0GFLJDDO8UP2KFU3G";
              $clientsecret="11bd7c7cc53eb959188b10fcc7c282a067ad4997";
              $url="https://payout-api.cashfree.com/payout/v1/authorize";
              $ch = curl_init($url);
              curl_setopt($ch, CURLOPT_POST, true);
              curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "X-Client-Id:" . $clientid, "X-Client-Secret:" . $clientsecret)); 
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
              $result_json = curl_exec($ch);
              
              curl_close($ch);
              $result =  json_decode($result_json, true); 
              
              $token=$result['data']['token'];
              
              //End
              //Verify token
             
              $url="https://payout-api.cashfree.com/payout/v1/verifyToken";
              $ch = curl_init($url);
              curl_setopt($ch, CURLOPT_POST, true);
              curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization:Bearer " . $token)); 
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
              $result_json = curl_exec($ch);
              
              curl_close($ch);
              $result =  json_decode($result_json, true); 
              //End
              
               //Verify token
             
             $url="https://payout-api.cashfree.com/payout/v1/removeBeneficiary";
              $ch = curl_init($url);
              $user = User::where('userId', Auth::user()->userId)->first();
              $Params["beneId"]= '918374913154';
            
              $post_data = json_encode($Params, JSON_UNESCAPED_SLASHES);
            
              curl_setopt($ch, CURLOPT_POST, true);
              curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
              curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization:Bearer " . $token)); 
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
              $result_json = curl_exec($ch);
              
              curl_close($ch);
              $result =  json_decode($result_json, true);
              return $result;
              //End
             
        //From Cashfree
    }
}