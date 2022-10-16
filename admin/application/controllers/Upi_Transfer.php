<?php
header('Access-Control-Allow-Origin: *');

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';
require APPPATH . '/libraries/PaytmChecksum.php';
class Upi_Transfer extends BaseController {
    /**
     * This is default constructor of the class
     */
    public function __construct() {
      parent::__construct();        
      $this->load->model('loginApi_model');
      $this->load->model('transactions_model');
      $this->load->model('moneyTransferApi_model');
      $this->load->model('rechargeApi_model');
      $this->load->model('operatorApi_model');
      $this->load->model('apiLog_model');
      $this->load->helper('file');
    }

    public function index() {
    }

    public function authenticateUser($data){
      //autheticate user by token details begin
      if (!empty($data['token']) && !empty($data['user_id']) && !empty($data['role_id'])) {
        $user_id= $data['user_id'];
        $role_id= $data['role_id'];

          //authenticate user with their details
        if(!$this->loginApi_model->authenticateUser($data['user_id'],$data['role_id'],$data['token'])){
          $response = array(
            'status' => "false",
            'msg' => "Authetication failure with invalid token",
            'result' => null
          );
          echo json_encode($response);
          exit;
        }
      }else{//if proper input not getting from the application           
        $response = array(
          'status' => "false",
          'msg' => "Authetication Failure",
          'result' => null
        );
        echo json_encode($response);
        exit;
      }
      //autheticate user by token details end
  }

  

  public function verifySenderRegistration() {
    $input =  file_get_contents('php://input'); 
    $data =  json_decode($input, true);        
    //$data =  json_decode(file_get_contents('php://input'), true);

    $this->authenticateUser($data);

    if(!empty($data['operatorID']) && !empty($data['sender_mobile_number'])){
      //get service details by operator id begin
      $operator_id = $data['operatorID'];
      $serviceDtl = $this->operatorApi_model->getServiceDetailsByOperatorID($operator_id);
      if($serviceDtl){
        $service_id = $serviceDtl[0]->service_id;
      }else{
        $response = array(
          'status' => "false",
          'msg' => "Invalid Service",
          'result' => null
        );
        echo json_encode($response);
        exit;
      }
      //get service details by operator id end 

      //check active api for operator begin
      $api_details = $this->rechargeApi_model->getActiveApiDetails($operator_id,$service_id,"");
      if(!$api_details){
        $response = array(
          'status' => "false",
          'msg' => "API details not configured for operator.Please contact administrator.",
          'result' => null
        );
        echo json_encode($response);
        exit;
      }
      //check active api for operator end

      if($api_details->api_id == "8"){ //go payment        
        $url = $api_details->api_url.'verifySender';
        $sender=$this->moneyTransferApi_model->check_sender_mobile($data['sender_mobile_number']);
        $additional_registration_data=(!empty($sender)?$sender->additional_registration_data:'');
        $post_data = array(
          'partner_code' => $api_details->api_token,
          'mobile_number' => $api_details->username,
          'password' => base64_decode($api_details->password),
          'sender_mobile_number'=>$data['sender_mobile_number'],
          'otp' => $data['otp'],
          'transaction_type' => 'IMPS',
          'additional_registration_data'=>$additional_registration_data."",
        );        
        $parameters = json_encode($post_data);
        $result = $this->doCurlCall($url,$parameters);      
        $result =  json_decode($result, true);
        curl_close($ch);
        if($result['response_code'] != "0"){
          $result['response_description'] = "Invalid OTP. Enter valid OTP or Resend OTP.";
        }
        if($result['response_code'] == "0"){
          $response = array(
            'status' => "true",
            'msg' => "Success",
            'result' => $result
          );
        }else{
          $response = array(
            'status' => "false",
            'msg' => $result['response_description'],
            'result' => $result
          );
        }
      }//developed by susmitha start
      else if($api_details->api_id == "9"){
        $mobile_number=$data['sender_mobile_number'];
        $otp=$data['otp'];
        echo $url = $api_details->api_url."customers/verification/otp:".$otp."";
        $user_id = $data['user_id'];
        $user=$this->moneyTransferApi_model->getUser_code($user_id);
        $user_code=$user->user_code;
        $post_data = array(
          'initiator_id' =>$api_details->username,
          'id_type' => 'mobile_number',
          'id' => $mobile_number,
          'user_code'=>$user_code,
          'pipe'=>'9',
          'otp_ref_id'=>$data['otp_ref_id']
        );  
        // echo "<pre>";
        //print_r($post_data);
        $parameters = http_build_query($post_data);
        $result = $this->doCurlCallEko($url,$parameters,"PUT");      
        $result =  json_decode($result, true);
        //print_r($result);
        $ekores=array("sender_mobile_number"=>$result['data']['customer_id'],
                       "response_code"=>$result['response_status_id'],
                        "response_description"=>$result['message'],
                        "state"=>$result['data']['state'],
                        "state_desc"=>$result['data']['state_desc'],
                        "uuid"=>'',
                        "user_code"=>$result['data']['user_code'],
                        "pipe"=>$result['data']['pipe']);
        
        if($result['response_status_id'] == 0){
          $response = array(
            'api'=>"EKO",
            'status' => "true",
            'msg' => "Success",
            'result' =>$ekores
          );
        }else{
          $response = array(
            'status' => "false",
            'msg' => $result['message'],
            'result' => $result
          );
        }
      }
      else if($api_details->api_id == "12"){
        
        $user=$this->moneyTransferApi_model->get_singlebyid('tbl_sender_dts','sender_mobile_number',$data['sender_mobile_number']);
        if(empty($user)){
            $response = array(
          'status' => "false",
          'msg' => "User mobile Not Registered.",
          'result' => null
        );
        echo json_encode($response);
        exit;
        }
        $original_otp =$user->otp;
        $userpassotp  =$data['otp'];
        $senderdata=array('sender_mobile_number'=>$data['sender_mobile_number'],
                              'response_code'      =>0,
                              'response_description'=>"Verified Successfully");
        if($original_otp==$userpassotp){
          $response = array(
            'api'=>"paytm",
            'status' => "true",
            'result' =>$senderdata,
            
          );
        }else{
          $response = array(
            'api'=>"paytm",
            'status' => "false",
            'msg' =>  "Check otp",
            'result' => "{}",
            
          );
        }
        
      }
      //developed by susmitha end

      else{
      	$user=$this->moneyTransferApi_model->get_singlebyid('tbl_sender_dts','sender_mobile_number',$data['sender_mobile_number']);
        if(empty($user)){
            $response = array(
          'status' => "false",
          'msg' => "User mobile Not Registered.",
          'result' => null
        );
        echo json_encode($response);
        exit;
        }
        $original_otp =$user->otp;
        $userpassotp  =$data['otp'];
        $senderdata=array('sender_mobile_number'=>$data['sender_mobile_number'],
                              'response_code'      =>0,
                              'response_description'=>"Verified Successfully");
        if($original_otp==$userpassotp){
          $response = array(
            'api'=>"paytm",
            'status' => "true",
            'result' =>$senderdata,
            
          );
        }else{
          $response = array(
            'api'=>"paytm",
            'status' => "false",
            'result' => "Check otp",
            
          );
        }
        // $response = array(
        //   'status' => "false",
        //   'msg' => "API implementation under process. Please contact administrator.",
        //   'result' => null
        // );
        // echo json_encode($response);
        // exit;
      }
    }else{
      $response = array(
        'status' => "false",
        'msg' => "Invalid Request",
        'result' => null
      );
    }
    echo json_encode($response);
    exit;
  }

  public function resendOTP() {        
    $data =  json_decode(file_get_contents('php://input'), true);

    $this->authenticateUser($data);

    if(!empty($data['operatorID']) && !empty($data['sender_mobile_number'])){
      //get service details by operator id begin
      $operator_id = $data['operatorID'];
      $serviceDtl = $this->operatorApi_model->getServiceDetailsByOperatorID($operator_id);
      if($serviceDtl){
        $service_id = $serviceDtl[0]->service_id;
      }else{
        $response = array(
          'status' => "false",
          'msg' => "Invalid Service",
          'result' => null
        );
        echo json_encode($response);
        exit;
      }
      //get service details by operator id end 

      //check active api for operator begin
      $api_details = $this->rechargeApi_model->getActiveApiDetails($operator_id,$service_id,"");
      if(!$api_details){
        $response = array(
          'status' => "false",
          'msg' => "API details not configured for operator.Please contact administrator.",
          'result' => null
        );
        echo json_encode($response);
        exit;
      }
      //check active api for operator end

      if($api_details->api_id == "8"){ //go payment        
        $url = $api_details->api_url.'resendSenderOTP';
        $post_data = array(
          'partner_code' => $api_details->api_token,
          'mobile_number' => $api_details->username,
          'password' => base64_decode($api_details->password),
          'sender_mobile_number'=>$data['sender_mobile_number'],
          'transaction_type' => 'IMPS',
        );        
        $parameters = json_encode($post_data);
        $result = $this->doCurlCall($url,$parameters);      
        $result =  json_decode($result, true);
        curl_close($ch);
        if($result['response_code'] == "0"){
          $response = array(
            'status' => "true",
            'msg' => "Success",
            'result' => $result
          );
        }else{
          $response = array(
            'status' => "false",
            'msg' => $result['response_description'],
            'result' => $result
          );
        }
      }else if($api_details->api_id == "9"){
        $mobile_number=$data['sender_mobile_number'];
        $url = $api_details->api_url."customers/mobile_number:".$mobile_number."/otp";
        $user_id = $data['user_id'];
        $user=$this->moneyTransferApi_model->getUser_code($user_id);
        $user_code=$user->user_code;
        $post_data = array(
          'initiator_id' =>$api_details->username,
          'user_code'=>$user_code,
          'pipe'=>'9',
        );  

        $parameters = http_build_query($post_data);
        $result = $this->doCurlCallEko($url,$parameters,"POST");      
        $result =  json_decode($result, true);
        $ekores=array("sender_mobile_number"         =>$mobile_number,
                       "response_code"               =>$result['response_status_id'],
                        "response_description"       =>$result['message'],
                        "additional_registration_data"=>'',
                        "otp"                         =>$result['data']['otp'],
                        "uuid"                        =>'');
        
        if($result['response_status_id'] == 0){
          $response = array(
            'api'=>"EKO",
            'status' => "true",
            'msg' => "Success",
            'result' =>$ekores
          );
        }else{
          $response = array(
            'api'=>"EKO",
            'status' => "false",
            'msg' => $result['message'],
            'result' => $result
          );
        }
      }
      else if($api_details->api_id == "12"){
          $digits = 5;
          $rand=rand(pow(10, $digits-1), pow(10, $digits)-1);
          //echo "$rand and " . ($startDate + $rand);
          $username="anandkaushal.in";
          $password="Budd789";
          $msisdn=$data['sender_mobile_number'];
          $sid="SRLSHD";
          $msg="".$rand."%20is%20your%20verification%20code.%20Regards%20Saral%20Shaadi.";
          $url="http://cloud.smsindiahub.in/vendorsms/pushsms.aspx?user=".$username."&password=".$password."&msisdn=".$msisdn."&sid=".$sid."&msg=".$msg."&fl=0&gwid=2";
         $ch = curl_init();  
         curl_setopt($ch,CURLOPT_URL,$url);
         curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
         $output=curl_exec($ch);
         curl_close($ch);
         
          $senderdata=array('otp'       =>$rand,
                              'updated_on' =>date('Y-m-d H:i:s'));
          $senderdata1=array('sender_mobile_number'=>$data['sender_mobile_number'],
                              'response_code'      =>0,
                              'response_description'=>"Sender already registered");
          $checkmobile=$this->moneyTransferApi_model->check_sender_mobile($data['sender_mobile_number']);
          if(empty($checkmobile)){
             $response = array(
                      'status' => "false",
                      'msg' => "Mobile Number not Register.",
                      'result' => null);
                      echo json_encode($response);
                      exit;
          }
          else{
             $this->db->where('sender_mobile_number',$data['sender_mobile_number']);
             $this->db->update('tbl_sender_dts',$senderdata);
          }
          if($this->db->affected_rows() > 0 ) {
             $response = array(
            'api'=>"Paytm",
            'status' => "true",
            'msg' => "Success",
            'result' =>$senderdata1
          );

          } 

         
      }
      else{
      	$digits = 5;
          $rand=rand(pow(10, $digits-1), pow(10, $digits)-1);
          //echo "$rand and " . ($startDate + $rand);
          $username="anandkaushal.in";
          $password="Budd789";
          $msisdn=$data['sender_mobile_number'];
          $sid="SRLSHD";
          $msg="".$rand."%20is%20your%20verification%20code.%20Regards%20Saral%20Shaadi.";
          $url="http://cloud.smsindiahub.in/vendorsms/pushsms.aspx?user=".$username."&password=".$password."&msisdn=".$msisdn."&sid=".$sid."&msg=".$msg."&fl=0&gwid=2";
         $ch = curl_init();  
         curl_setopt($ch,CURLOPT_URL,$url);
         curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
         $output=curl_exec($ch);
         curl_close($ch);
         
          $senderdata=array('otp'       =>$rand,
                              'updated_on' =>date('Y-m-d H:i:s'));
          $senderdata1=array('sender_mobile_number'=>$data['sender_mobile_number'],
                              'response_code'      =>0,
                              'response_description'=>"Sender already registered");
          $checkmobile=$this->moneyTransferApi_model->check_sender_mobile($data['sender_mobile_number']);
          if(empty($checkmobile)){
             $response = array(
                      'status' => "false",
                      'msg' => "Mobile Number not Register.",
                      'result' => null);
                      echo json_encode($response);
                      exit;
          }
          else{
             $this->db->where('sender_mobile_number',$data['sender_mobile_number']);
             $this->db->update('tbl_sender_dts',$senderdata);
          }
          if($this->db->affected_rows() > 0 ) {
             $response = array(
            'api'=>"Paytm",
            'status' => "true",
            'msg' => "Success",
            'result' =>$senderdata1
          );

          } 

         
        // $response = array(
        //   'status' => "false",
        //   'msg' => "API implementation under process. Please contact administrator.",
        //   'result' => null
        // );
        // echo json_encode($response);
        // exit;
      }
    }else{
      $response = array(
        'status' => "false",
        'msg' => "Invalid Request",
        'result' => null
      );
    }
    echo json_encode($response);
    exit;  
  }

  public function getRecipientList() {        
    $data =  json_decode(file_get_contents('php://input'), true);

    $this->authenticateUser($data);

    if(!empty($data['operatorID']) && !empty($data['sender_mobile_number'])){
      //get service details by operator id begin
      $operator_id = $data['operatorID'];
      $serviceDtl = $this->operatorApi_model->getServiceDetailsByOperatorID($operator_id);
      if($serviceDtl){
        $service_id = $serviceDtl[0]->service_id;
      }else{
        $response = array(
          'status' => "false",
          'msg' => "Invalid Service",
          'result' => null
        );
        echo json_encode($response);
        exit;
      }
      //get service details by operator id end 

      //check active api for operator begin
      $api_details = $this->rechargeApi_model->getActiveApiDetails($operator_id,$service_id,"");
      if(!$api_details){
        $response = array(
          'status' => "false",
          'msg' => "API details not configured for operator.Please contact administrator.",
          'result' => null
        );
        echo json_encode($response);
        exit;
      }
      //check active api for operator end

      	$where=array('sender_mobile_number'=>$data['sender_mobile_number'],
                      'api_name'=>'upi',
                      'is_deleted'=>0);
          $receiptlist=$this->moneyTransferApi_model->getdata_where('tbl_dmt_benificiary_dtls',$where, 'DESC');
        $ekores  =array("sender_mobile_number"=>$data['sender_mobile_number'],
                       "response_code"=>0,
                        "recipient_list"=>$receiptlist,
                        "response_description"=>"Success",
                        );
        $response = array(
            'api'=>"upi",
            'status' => "true",
            'msg' => "Success",
            'result' =>$ekores
          );
         echo json_encode($response);
         exit();
        // $response = array(
        //   'status' => "false",
        //   'msg' => "API implementation under process. Please contact administrator.",
        //   'result' => null
        // );
        // echo json_encode($response);
        // exit;
      
    }else{
      $response = array(
        'status' => "false",
        'msg' => "Invalid Request",
        'result' => null
      );
    }
    echo json_encode($response);
    exit;
  }

  public function getRecipientDetails() {        
    $data =  json_decode(file_get_contents('php://input'), true);

    $this->authenticateUser($data);

    if(!empty($data['operatorID']) && !empty($data['sender_mobile_number'])){
      //get service details by operator id begin
      $operator_id = $data['operatorID'];
      $serviceDtl = $this->operatorApi_model->getServiceDetailsByOperatorID($operator_id);
      if($serviceDtl){
        $service_id = $serviceDtl[0]->service_id;
      }else{
        $response = array(
          'status' => "false",
          'msg' => "Invalid Service",
          'result' => null
        );
        echo json_encode($response);
        exit;
      }
      //get service details by operator id end 

      //check active api for operator begin
      $api_details = $this->rechargeApi_model->getActiveApiDetails($operator_id,$service_id,"");
      if(!$api_details){
        $response = array(
          'status' => "false",
          'msg' => "API details not configured for operator.Please contact administrator.",
          'result' => null
        );
        echo json_encode($response);
        exit;
      }
      //check active api for operator end

      	$where=array('sender_mobile_number'=>$data['sender_mobile_number'],
                       'recipient_id'=>$data['recipient_id'],
                       'is_deleted'=>0);
      	//print_r($where);
      	$orderby='DESC';
         $receipt=$this->moneyTransferApi_model->getdata_where('tbl_dmt_benificiary_dtls',$where,$orderby);
         //print_r($receipt);
          if(!empty($receipt)){
           
           $recipient_details=array("recipient_name"  =>$receipt[0]->recipient_name,
                                 "recipient_mobile"=>$receipt[0]->recipient_mobile_number,
                                 "recipient_id"    =>$receipt[0]->recipient_id,
                                 "bank_name"       =>'',
                                 "bank_code"       =>$receipt[0]->bank_code,
                                 "bank_account_number"=>$receipt[0]->bank_account_number,
                                 "ifsc"             =>$receipt[0]->ifsc,  
                                 "recipient_status" =>'',
                                 "is_verified"      =>'',
                                 "verified_name"    =>'',
                                 );
        $ekores  =array("sender_mobile_number"=>$receipt[0]->sender_mobile_number,
                       "response_code"=>0,
                        "recipient_details"=>$recipient_details,
                        "response_description"=>"Success",
                        );
        $response = array(
            'api'=>"upi",
            'status' => "true",
            'msg' => "Success",
            'result' =>$ekores
          );
         echo json_encode($response);
         exit();
         }else{
         $response = array(
          'api'=>'upi',
          'status' => "false",
          'msg' => "Recipent Details Not avalible",
          'result' =>"",
        );
        echo json_encode($response);
        exit;
        }
        
       
      
    }else{
      $response = array(
        'status' => "false",
        'msg' => "Invalid Request",
        'result' => null
      );
    }
    echo json_encode($response);
    exit;
  }

  public function getBankList() {        
    $data =  json_decode(file_get_contents('php://input'), true);

    $this->authenticateUser($data);

    if(!empty($data['operatorID']) && !empty($data['sender_mobile_number'])){
      //get service details by operator id begin
      $operator_id = $data['operatorID'];
      $serviceDtl = $this->operatorApi_model->getServiceDetailsByOperatorID($operator_id);
      if($serviceDtl){
        $service_id = $serviceDtl[0]->service_id;
      }else{
        $response = array(
          'status' => "false",
          'msg' => "Invalid Service",
          'result' => null
        );
        echo json_encode($response);
        exit;
      }
      //get service details by operator id end 

      //check active api for operator begin
      $api_details = $this->rechargeApi_model->getActiveApiDetails($operator_id,$service_id,"");
      if(!$api_details){
        $response = array(
          'status' => "false",
          'msg' => "API details not configured for operator.Please contact administrator.",
          'result' => null
        );
        echo json_encode($response);
        exit;
      }
      //check active api for operator end

      if($api_details->api_id == "8"){ //go payment        
        $url = $api_details->api_url.'getBankList';
        $post_data = array(
          'partner_code' => $api_details->api_token,
          'mobile_number' => $api_details->username,
          'password' => base64_decode($api_details->password),     
          'type' => 'ALL'         
        );        
        $parameters = json_encode($post_data);
        $result = $this->doCurlCall($url,$parameters);      
        $result =  json_decode($result, true);
        curl_close($ch);
        if($result['response_code'] == "0"){
          $response = array(
            'status' => "true",
            'msg' => "Success",
            'result' => $result
          );
        }else{
          $response = array(
            'status' => "false",
            'msg' => $result['response_description'],
            'result' => $result
          );
        }
      }
      //developed by susmitha start
      else if($api_details->api_id == "9"){//eko
        $bank=$this->moneyTransferApi_model->get_bank_list();
        foreach($bank as $val){
          $banklist[]        =array("bank_id"=>trim($val['BankID']),
                                "bank_name"=>trim($val['BANK_NAME']),
                                "bank_code"=>trim($val['ShortCode']),
                                "bank_icon"=>$val['bank_icon'],
                                "neft_allowed"=>(trim($val['NEFT_Status'])=="Enabled")?'Y':'N',
                                "imps_allowed"=>(trim($val['IMPS_Status'])=="Enabled")?'Y':'N',
                                "account_verification_allowed"=>(trim($val['IsVerficationAvailable'])=="On")?'Y':'N',
                                "ifsc_prefix"=>$val['ifsc_prefix'],
                                 );
            
         }
         
         $mainrec   =array(
          "mobile_number"=>"",
          "bank_list"      =>$banklist,
          "type"=> "ALL",
           "response_code"=>'0',
           "response_description"=> "Success",
          "uuid"=>"" 
          );
        
          $response = array(
            'api'=>"EKO",
            'status' => "true",
            'msg' => "Success",
            'result' =>$mainrec
          );
        
     }//developed by susmitha end
     else if($api_details->api_id == "12"){//eko
        $bank=$this->moneyTransferApi_model->get_bank_list();
        foreach($bank as $val){
          $banklist[]        =array("bank_id"=>trim($val['BankID']),
                                "bank_name"=>trim($val['BANK_NAME']),
                                "bank_code"=>trim($val['ShortCode']),
                                "bank_icon"=>$val['bank_icon'],
                                "neft_allowed"=>(trim($val['NEFT_Status'])=="Enabled")?'Y':'N',
                                "imps_allowed"=>(trim($val['IMPS_Status'])=="Enabled")?'Y':'N',
                                "account_verification_allowed"=>(trim($val['IsVerficationAvailable'])=="On")?'Y':'N',
                                "ifsc_prefix"=>$val['ifsc_prefix'],
                                 );
            
         }
         
         $mainrec   =array(
          "mobile_number"=>"",
          "bank_list"      =>$banklist,
          "type"=> "ALL",
           "response_code"=>'0',
           "response_description"=> "Success",
          "uuid"=>"" 
          );
        
          $response = array(
            'api'=>"paytm",
            'status' => "true",
            'msg' => "Success",
            'result' =>$mainrec
          );
        
     }//developed by susmitha end
      else{
      	$bank=$this->moneyTransferApi_model->get_bank_list();
        foreach($bank as $val){
          $banklist[]        =array("bank_id"=>trim($val['BankID']),
                                "bank_name"=>trim($val['BANK_NAME']),
                                "bank_code"=>trim($val['ShortCode']),
                                "bank_icon"=>$val['bank_icon'],
                                "neft_allowed"=>(trim($val['NEFT_Status'])=="Enabled")?'Y':'N',
                                "imps_allowed"=>(trim($val['IMPS_Status'])=="Enabled")?'Y':'N',
                                "account_verification_allowed"=>(trim($val['IsVerficationAvailable'])=="On")?'Y':'N',
                                "ifsc_prefix"=>$val['ifsc_prefix'],
                                 );
            
         }
         
         $mainrec   =array(
          "mobile_number"=>"",
          "bank_list"      =>$banklist,
          "type"=> "ALL",
           "response_code"=>'0',
           "response_description"=> "Success",
          "uuid"=>"" 
          );
        
          $response = array(
            'api'=>"paytm",
            'status' => "true",
            'msg' => "Success",
            'result' =>$mainrec
          );
        // $response = array(
        //   'status' => "false",
        //   'msg' => "API implementation under process. Please contact administrator.",
        //   'result' => null
        // );
        // echo json_encode($response);
        // exit;
      }
    }else{
      $response = array(
        'status' => "false",
        'msg' => "Invalid Request",
        'result' => null
      );
    }
    echo json_encode($response);
    exit;
  }

  public function addRecipient() {  
      
    $data =  json_decode(file_get_contents('php://input'), true);
       
    $this->authenticateUser($data);

    if(!empty($data['operatorID']) && !empty($data['sender_mobile_number'])){
      //get service details by operator id begin
      $operator_id = $data['operatorID'];
      $serviceDtl = $this->operatorApi_model->getServiceDetailsByOperatorID($operator_id);
      if($serviceDtl){
        $service_id = $serviceDtl[0]->service_id;
      }else{
        $response = array(
          'status' => "false",
          'msg' => "Invalid Service",
          'result' => null
        );
        echo json_encode($response);
        exit;
      }
      //get service details by operator id end 
                //Beneficiary Added to Cashfree
        //Add Beneficary ID
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
          $url="https://payout-api.cashfree.com/payout/v1/addBeneficiary";
          $ch = curl_init($url);
         $beneid=str_replace("@","",$data['bank_account_number']);
           $Params["beneId"]= $beneid;
          $Params["name"]= "xyz";
          $Params["email"] = "mehtashyam13@gmail.com";
          $Params["phone"] = "9033975413";
          $Params["vpa"]= $data['bank_account_number'];
          $Params["ifsc"] = $data['ifsc'];
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
          
        //cashfree end
      //check active api for operator begin
      $api_details = $this->rechargeApi_model->getActiveApiDetails($operator_id,$service_id,"");
      if(!$api_details){
        $response = array(
          'status' => "false",
          'msg' => "API details not configured for operator.Please contact administrator.",
          'result' => null
        );
        echo json_encode($response);
        exit;
      }
      //check active api for operator end
      $where=array('sender_mobile_number'=>$data['sender_mobile_number'],
                    'bank_account_number'=>$data['bank_account_number'],
                    'api_name'=>'upi',
                    'is_deleted'=>0);
      // echo "<pre>";
      // print_r($where);
          $receiptcheck=$this->moneyTransferApi_model->getdata_where('tbl_dmt_benificiary_dtls',$where);
          //print_r($receiptcheck);
          if(empty($receiptcheck)){
              $senderDtls = $this->db->select('*')->from('tbl_sender_dts')->where('sender_mobile_number', $data['recipient_mobile_number'])->get()->row();
            $resp_fundaccount = $this->fundAccount_RezorpayUpi($data['bank_account_number'],  $senderDtls->razorpay_contact_id);
            if (isset($resp_fundaccount['id'])) {
                $rzp = $resp_fundaccount['id'];
            } else {
                $rzp = "";
                
            }
             //add recepient info begin
          $recipientInfo = array(
            'recipient_id' =>'',
            'recipient_name' =>$data['recipient_name'],
            'bank_name' =>'',
            'bank_code' =>$data['bank_code'],
            'bank_account_number' => $data['bank_account_number'],
            'ifsc' => $data['ifsc'],  
            'recipient_status' =>'',
            'is_verified' =>'',       
            'verified_name' =>'',
            'api_name'     =>'upi',
            'recipient_mobile_number' =>$data['recipient_mobile_number'],
            'sender_mobile_number'=>$data['sender_mobile_number'],
            'api_id'              =>$api_details->api_id,
            'razorpay_fund_acc_id' => $rzp,
            'cfree_beneficiaryid' =>$data['bank_account_number']
          );
        $recipientid=$this->moneyTransferApi_model->addNewRecepient($recipientInfo);
        $recipient_details=array("recipient_name"  =>$data['recipient_name'],
                             "recipient_mobile"=>$data['recipient_mobile_number'],
                             "recipient_id"    =>$recipientid,
                              "bank_name"       =>'',
                              "bank_code"       =>$data['bank_code'],
                              "bank_account_number"=>$data['bank_account_number'],
                              "ifsc"             =>$data['ifsc'],  
                              "recipient_status" =>'',
                              "is_verified"      =>'',
                              "verified_name"    =>'',
                              );
        $ekores  =array("sender_mobile_number"=>$data['sender_mobile_number'],
                       "response_code"=>0,
                        "recipient_details"=>$recipient_details,
                        "response_description"=>"Recipient added with recipient ID: ".$recipientid."",
                        );
        $response = array(
            'api'=>"upi",
            'status' => "true",
            'msg' => "Success",
            'result' =>$ekores
          );
         echo json_encode($response);
         exit();
      }else{
        $response = array(
          'api'=>'paytm',
          'status' => "false",
          'msg' => "Already this Recipent details avalible for same sender",
          'result' =>"",
        );
        echo json_encode($response);
        exit;
      }
        // $response = array(
        //   'status' => "false",
        //   'msg' => "API implementation under process. Please contact administrator.",
        //   'result' => null
        // );
        // echo json_encode($response);
        // exit;
      
    }else{
      $response = array(
        'status' => "false",
        'msg' => "Invalid Request",
        'result' => null
      );
    }
    echo json_encode($response);
    exit;
  }

  public function deleteRecipient() {        
    $data =  json_decode(file_get_contents('php://input'), true);

    $this->authenticateUser($data);

    if(!empty($data['operatorID']) && !empty($data['sender_mobile_number'])){
      //get service details by operator id begin
      $operator_id = $data['operatorID'];
      $serviceDtl = $this->operatorApi_model->getServiceDetailsByOperatorID($operator_id);
      if($serviceDtl){
        $service_id = $serviceDtl[0]->service_id;
      }else{
        $response = array(
          'status' => "false",
          'msg' => "Invalid Service",
          'result' => null
        );
        echo json_encode($response);
        exit;
      }
      //get service details by operator id end 

      //check active api for operator begin
      $api_details = $this->rechargeApi_model->getActiveApiDetails($operator_id,$service_id,"");
      if(!$api_details){
        $response = array(
          'status' => "false",
          'msg' => "API details not configured for operator.Please contact administrator.",
          'result' => null
        );
        echo json_encode($response);
        exit;
      }
      //check active api for operator end

      	$where=array('sender_mobile_number'=>$data['sender_mobile_number'],
                       'recipient_id'=>$data['recipient_id'],
                       'api_name'=>'upi',
                       'is_deleted'=>0);
        // echo "<pre>";
        // print_r($where);
          $receiptcheck=$this->moneyTransferApi_model->getdata_where('tbl_dmt_benificiary_dtls',$where);
          // print_r($receiptcheck);
          // echo "ku";
          if(!empty($receiptcheck)){
             $this->db->where('recipient_id',$data['recipient_id']);
              $this->db->update('tbl_dmt_benificiary_dtls', array('is_deleted' =>1));
              $response = array(
            'api'=>"upi",
            'status' => "true",
            'msg' => "Recipient ID: ".$data['recipient_id']." Deleted Successfully",
            
          );
         // echo json_encode($response);
         // exit();
          }else{
           $response = array(
          'api'=>'upi',
          'status' => "false",
          'msg' => "Recipent details not avalible check recipent id",
          'result' =>"",
        );
        echo json_encode($response);
        exit;
          }
        
      
    }else{
      $response = array(
        'status' => "false",
        'msg' => "Invalid Request",
        'result' => null
      );
    }
    echo json_encode($response);
    exit;
  }

  public function verifyBankAccount() {        
    $data =  json_decode(file_get_contents('php://input'), true);

    $this->authenticateUser($data);

    if(!empty($data['operatorID']) && !empty($data['sender_mobile_number'])){
      //get service details by operator id begin
      $operator_id = $data['operatorID'];
      $user_id=$data['user_id'];
      $mobile=$data['sender_mobile_number'];
      $serviceDtl = $this->operatorApi_model->getServiceDetailsByOperatorID($operator_id);
      if($serviceDtl){
        $service_id = $serviceDtl[0]->service_id;
      }else{
        $response = array(
          'status' => "false",
          'msg' => "Invalid Service",
          'result' => null
        );
        echo json_encode($response);
        exit;
      }
      //get service details by operator id end 

      //check active api for operator begin
      $api_details = $this->rechargeApi_model->getActiveApiDetails($operator_id,$service_id,"");
      if(!$api_details){
        $response = array(
          'status' => "false",
          'msg' => "API details not configured for operator.Please contact administrator.",
          'result' => null
        );
        echo json_encode($response);
        exit;
      }
      //check active api for operator end
        $order_uni=$this->get_order_id();
        $ores=json_decode($order_uni,true);
        $sno_order_id=$ores['sno_order_id'];
        $order_id=$ores['order_id'];
     //  $last_order_id = file_get_contents("admin/txn_order_id.txt");
     //  $sno_order_id  =intval($last_order_id)+1;            
     //  $order_id ="SP".$sno_order_id;              
     // //validated order_id start 
     //          if($order_id!=''){
     //            $clientres=$this->transactions_model->check_order_id($order_id);
     //            if(!empty($clientres)){
                   
     //                $response = array(
     //              'status' => "false",
     //              'msg' => "Order id already there try once again",
     //              'result' => null
     //            );
     //            echo json_encode($response);
     //            exit;
     //            }
     //          }
              
     //  //validated order_id end
      
      //check balance of user begin
      //$smartpay=15;
      $smartpay='upichargeclient';
      //$apptblsmt= $this->transactions_model->getapptab($smartpay);
      $apptblsmt= $this->transactions_model->getapp_byalis($smartpay);
      //$smartpay=16;
      $smartpay='upichargeapi';
      //$apptblpay= $this->transactions_model->getapptab($smartpay);
      $apptblpay= $this->transactions_model->getapp_byalis($smartpay);
      $smartpaymaster='upichargemaster';//master
      //$smartpaymaster=17;
      //$appmastersmt= $this->transactions_model->getapptab($smartpaymaster);
      $appmastersmt= $this->transactions_model->getapp_byalis($smartpaymaster);
      $amount = $apptblsmt->value;
      $operator_id = $data['operatorID'];
      $user_id= $data['user_id'];
      $role_id= $data['role_id'];
      $userbalance = $this->rechargeApi_model->getUserBalance($data['user_id']);
      if ($userbalance) {
        $wallet_balance = $userbalance->wallet_balance;
        $min_balance = $userbalance->min_balance;
        $user_package_id = $userbalance->package_id;
        if(is_numeric($wallet_balance) && is_numeric($min_balance) && is_numeric($amount) && $wallet_balance-$amount < $min_balance){
          $response = array(
              'status' => "false",
              'msg' => "Insufficient balance",
              'result' => null
          );
          echo json_encode($response);
          exit;
        }else if(!is_numeric($wallet_balance) || !is_numeric($min_balance) || !is_numeric($amount)){
          $response = array(
              'status' => "false",
              'msg' => "Invalid amount details.",
              'result' => null
          );
          echo json_encode($response);
          exit;
        }else{//get all commission details by package id
          $commissionDtl = $this->rechargeApi_model->getCommissionDetails($user_package_id,$service_id,$operator_id,$amount);              
          if ($commissionDtl) {

            if($commissionDtl->commission_type == "Rupees"){
              
              $admin_commission = $commissionDtl->admin_commission;
              $md_commission = $commissionDtl->md_commission;
              $api_commission = $commissionDtl->api_commission;
              $distributor_commission = $commissionDtl->distributor_commission;
              $retailer_commission = $commissionDtl->retailer_commission;
              //susmitha commision cal
              $ccf=$commissionDtl->ccf_commission;
              $charge = $commissionDtl->retailer_commission;
              $cashback=$ccf-$charge;
              //cashback update to trans table based on order_id
              $app    =$this->rechargeApi_model->getTDS();
              $TDS     =$cashback*$app->value;
              $PayableCharge = $charge+$TDS;
              $totalAmount=$amount+$PayableCharge;
              
            }else if($commissionDtl->commission_type == "Percent"){
              $admin_commission = ($amount*$commissionDtl->admin_commission)/100;
              $md_commission = ($amount*$commissionDtl->md_commission)/100;
              $api_commission = ($amount*$commissionDtl->api_commission)/100;
              $distributor_commission = ($amount*$commissionDtl->distributor_commission)/100;
              $retailer_commission = ($amount*$commissionDtl->retailer_commission)/100;
            }else if($commissionDtl->commission_type == "Range"){
              if($commissionDtl->admin_commission_type == "Rupees")
                $admin_commission = $commissionDtl->admin_commission;
              else
                $admin_commission = ($amount*$commissionDtl->admin_commission)/100;
              if($commissionDtl->md_commission_type == "Rupees")
                $md_commission = $commissionDtl->md_commission;
              else
                $md_commission = ($amount*$commissionDtl->md_commission)/100;
              if($commissionDtl->distributor_commission_type == "Rupees")
                $distributor_commission = $commissionDtl->distributor_commission;
              else
                $distributor_commission = ($amount*$commissionDtl->distributor_commission)/100;
              if($commissionDtl->retailer_commission_type == "Rupees")
                $retailer_commission = $commissionDtl->retailer_commission;
              else
                $retailer_commission = ($amount*$commissionDtl->retailer_commission)/100;
                $api_commission = $commissionDtl->api_commission;
            }
          }
        }
      }else{
        $response = array(
            'status' => "false",
            'msg' => "Error while retriving balance",
            'result' => null
        );
        echo json_encode($response);
        exit;
      }
      //check balance of user end
      
      //if all above conditions valid then update order id in file
      //$this->writeTxnOrderID($sno_order_id);  
       
        
      	$api=$this->moneyTransferApi_model->get_singlebyid('tbl_api_settings','api_id',13);
        $url      =$api->api_url."/api/verify/upi_id";
        $api_token=$api->api_token;
        $verifybankcheck=$this->moneyTransferApi_model->get_singlebyid('tbl_verified_banks','bank_acc_no',$data['bank_account_number']);
         
       if(!empty($verifybankcheck)){
        $transaction_id     ="";
        $bank_transaction_id="";
        $account_holder_name=$verifybankcheck->bank_holder_name;
        $charges_tax       ="";
        $debit_amount      ="";
        $balance           ="";
        $status            ="";
        $bank_acc_no       =$verifybankcheck->bank_acc_no; 
        $bank_ifsc        =$verifybankcheck->bank_ifsc;
        $responsecheck='true'; 

       }
       else{          $this->db->select('default_api_id');
                $this->db->from('tbl_operator_settings');
                $this->db->where('operator_name','BANK VERIFICATION');
                $query = $this->db->get();
                $apiis=$query->row()->default_api_id;
                
      if($apiis == 22){
          
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
        $verifybankcheck=$this->moneyTransferApi_model->get_singlebyid('tbl_verified_banks','bank_acc_no',$data['bank_account_number']);
       
       if(!empty($verifybankcheck)){
        $transaction_id     ="";
        $bank_transaction_id="";
        $account_holder_name=$verifybankcheck->bank_holder_name;
        $charges_tax       ="";
        $debit_amount      ="";
        $balance           ="";
        $status            ="";
        $bank_acc_no       =$verifybankcheck->bank_acc_no; 
        $bank_ifsc        =$verifybankcheck->bank_ifsc;
        $responsecheck='SUCCESS'; 

       }
       else{
           
          $post=array("ifsc"   =>$data['ifsc'],
                    "number"=>$data['bank_account_number'],
                    "reference_number"=>$order_id);
        $bank_account_number=$data['bank_account_number'];
        $ifsc=$data['ifsc'];
        $name="SHYAM";
        $phone=9033975412;
        
        $bank_account_number=$data['bank_account_number'];
        
        
        //Cashfree bank
         $url="https://payout-api.cashfree.com/payout/v1/validation/upiDetails?name=".$name."&vpa=".$bank_account_number;
          $ch = curl_init($url);
          curl_setopt($ch, CURLOPT_GET, true);
          curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json", "Authorization:Bearer " . $token)); 
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
          $result_json = curl_exec($ch);
          
          curl_close($ch);
          $result =  json_decode($result_json, true); 
        //Cashfree bank end
        
       
        

         //save api log details begin
        $api_info = array(
          'service_id' => $service_id."", 
          'api_id' => $api_details->api_id."", 
          'api_name' => $api_details->api_name."",  
          'api_method' => "doFundTransfer",
          'api_url' => $url."", 
          'order_id' => $order_id."", 
          'user_id' => $user_id."",  
          'request_input' => $payload."",
          'request' => $payload."",         
          'response' => $result."",
          'access_type' => "APP",
          'updated_on'=>date('Y-m-d H:i:s'),
        );
        $api_log_id = $this->apiLog_model->addApiLogDetails($api_info); //save api log details end
        $transaction_id     =$order_id;
        $bank_transaction_id="";
        $account_holder_name=$result['data']['nameAtBank']."";
        $charges_tax       ='';
        $debit_amount      =1;
        $balance           = "";
        $status            =$result['status']."";
        $bank_acc_no       =$data['bank_account_number'];
        $bank_ifsc        =$data['ifsc'];
        $responsecheck=$result['status']; 
        //$bank_holder_name =$result['data']['verify_account_holder']."",
       }

        
        //update balance after deduction begin
        $updatedBalance = $wallet_balance-$apptblsmt->value;
          //insert DEBIT txn into tbl_wallet_trans_dtls table
          $wallet_trans_info = array(
            'service_id' =>$service_id,
            'order_id' => $this->isValid($order_id), 
            'user_id' => $user_id, 
            'operator_id' => $operator_id,
            'api_id' => $api_details->api_id,
            //'transaction_status' => $this->isValid($result['transaction_status']),
            'transaction_type' => "DEBIT",
            'payment_type' => "SERVICE",
            'payment_mode' => "ACCOUNT VALIDATION CHARGE FOR ACCOUNT NUMBER ".$data['bank_account_number'],
            'transaction_id' => $this->isValid($transaction_id),               
            'trans_date' => date("Y-m-d H:i:s"),  
            'total_amount' =>$apptblsmt->value,
            'charge_amount' => "0.00",
            'balance' => $updatedBalance,
            'updated_on'=>date('Y-m-d H:i:s'),
          );
          $wallet_txn_id = $this->transactions_model->addNewWalletTransaction($wallet_trans_info);
          //update balance into users table                           
          $updateBalQry = $this->rechargeApi_model->updateUserBalance($user_id,$updatedBalance);
          //update balance after deduction end
          if($responsecheck == "SUCCESS"){

          $trans_info = array(
            'transaction_id' => $this->isValid($transaction_id),
            'operator_id'=>$operator_id,
            'service_id' =>$service_id, 
            'api_id' =>$api_details->api_id,
            'trans_date' => date("Y-m-d H:i:s"),
            'order_id' => $this->isValid($order_id),  
            'mobileno' => $data['sender_mobile_number'], 
            'user_id' => $this->isValid($user_id),          
            'total_amount' =>$apptblsmt->value,
            'bank_transaction_id' => $this->isValid($bank_transaction_id), //add
            'imps_name' => $account_holder_name, //add
            // 'recipient_id' => $this->isValid($result['recipient_id']), //add
            'charges_tax' => $this->isValid($charges_tax), //add

            // 'commission' => $this->isValid($result['commission']), //add
            // 'commission_tax' => $this->isValid($result['commission_tax']), //add
            // 'commission_tds' => $this->isValid($result['commission_tds']), //add
            'debit_amount' => $this->isValid($debit_amount),
            'balance' => $this->isValid($balance),
            'order_status' => $this->isValid("SUCCESS"),
            'updated_on'=>date('Y-m-d H:i:s'),
          );
          $txn_id = $this->transactions_model->addNewTransaction($trans_info);
          //update balance based on api id in api setting table developed by susmitha start
          //$currentapibal=$this->isValid($result['data']['closing_balance']);
          $data = array('balance'=>$balance);
           $this->apiLog_model->update_api_amount($data,$api_details->api_id);
         //update balance based on api id in apisetting table developed by susmitha end 

          //commission wallet txn begin
          if(is_numeric($role_id) && intval($role_id) <= 4){                
            $walletUserID = $user_id;
            $walletRoleID = $role_id;
            $isUserBalanceUpdated = false;
            for($i=$walletRoleID;$i>=1;$i--){                
              if($i==2 ||$i==3 || $i==4  || $i==7){
                $isUserBalanceUpdated = true;
                $userParentID = $this->rechargeApi_model->getUserParentID($walletUserID);
                if ($isUserBalanceUpdated && $userParentID && ($userParentID->roleId==2|| $userParentID->roleId==3||$userParentID->roleId==4||$userParentID->roleId==7)) {
                  $walletUserID = $userParentID->userId;
                  $walletRoleID = $userParentID->roleId;
                  $updatedBalance = $userParentID->wallet_balance;
                }
                continue;
              }
              $walletAmt = 0;
              $walletBal = 0;                 
              $userParentID = $this->rechargeApi_model->getUserParentID($walletUserID);
              if ($isUserBalanceUpdated && $userParentID) {
                $walletUserID = $userParentID->userId;
                $walletRoleID = $userParentID->roleId;
                $updatedBalance = $userParentID->wallet_balance;
              }
              

               if($walletRoleID == 1){ //Admin
                $walletAmt = $apptblsmt->value-$apptblpay->value;
                $walletBal = $updatedBalance+($apptblsmt->value-$apptblpay->value);
              }
              if(is_numeric($walletAmt) && is_numeric($walletBal)){
                $transType = "CREDIT";
                if($walletAmt < 0){
                  $transType = "DEBIT";
                }
                $wallet_trans_info = array(
                  'service_id' => $service_id,
                  'order_id' => $this->isValid($order_id), 
                  'user_id' => $walletUserID, 
                  'operator_id' => $operator_id,
                  'api_id' => $api_details->api_id,
                  'transaction_status' => $status,
                  'transaction_type' => $transType,
                  'payment_type' => "COMMISSION",
                  'payment_mode' => "Commission by bank verification",
                  'transaction_id' => $this->isValid($transaction_id),               
                  'trans_date' => date("Y-m-d H:i:s"),  
                  'total_amount' => abs($walletAmt),
                  'charge_amount' => "0.00",
                  'balance' => $walletBal,
                  'updated_on'=>date('Y-m-d H:i:s'),
                );
                $wallet_txn_id = $this->transactions_model->addNewWalletTransaction($wallet_trans_info);
                //update balance into users table                           
                $updateBalQry = $this->rechargeApi_model->updateUserBalance($walletUserID,$walletBal);
                //update balance after deduction end
              }
              $isUserBalanceUpdated = true;
            }
          }
          //after verify bank insert bank details start
           $verifybank=array(
            "bank_acc_no"=>$bank_acc_no, 
            "bank_ifsc"  =>$bank_ifsc,
            "bank_holder_name"  =>$account_holder_name,
            "created_at"  =>date('Y-m-d H:i:s'));
           //print_r($verifybank);
           if(empty($verifybankcheck)){
            $this->db->insert('tbl_verified_banks',$verifybank);
           }
             //$avno=$result['data']['verify_account_number'];
             
             if($account_holder_name!='')
             {
                  $where=array("sender_mobile_number"=>$mobile,"bank_account_number"=>$bank_acc_no);
            $updatebeni=array("recipient_name"=>$account_holder_name,"is_verified"=>"Y","verified_name"=>$account_holder_name);
             $this->db->where($where);
             $this->db->update('tbl_dmt_benificiary_dtls',$updatebeni);
             }
             else
             {
                
             $where=array("sender_mobile_number"=>$mobile,"bank_account_number"=>$bank_acc_no);
            
             $this->db->where('bank_acc_no',$bank_acc_no);
            $this->db->delete('tbl_verified_banks');
             }
           
             $verify_result=array("verify_account_number"=>$bank_acc_no,
                                  "verify_account_ifsc"=>$bank_ifsc,
                                  "verify_account_holder"=>$account_holder_name);
                                  
           
           //end verify bank
            $response = array(
            'status' => "true",
            'msg' => "Success",
            'result' =>$verify_result
            );
        }
         
         else{
          $trans_info = array(
            'transaction_id' => $this->isValid($result['data']['id']),
            'operator_id'=>$operator_id,
            'service_id' =>$service_id, 
            'api_id' =>$api_details->api_id,
            'trans_date' => date("Y-m-d H:i:s"),
            'order_id' => $this->isValid($order_id),  
            'mobileno' => $this->isValid($data['sender_mobile_number']), 
            'user_id' => $this->isValid($user_id),          
            'total_amount' =>$apptblsmt->value,
            'bank_transaction_id' =>$this->isValid($bank_transaction_id), //add
            'imps_name' =>$account_holder_name, //add
            // 'recipient_id' => $this->isValid($result['recipient_id']), //add
            'charges_tax' => $this->isValid($charges_tax), //add

            // 'commission' => $this->isValid($result['commission']), //add
            // 'commission_tax' => $this->isValid($result['commission_tax']), //add
            // 'commission_tds' => $this->isValid($result['commission_tds']), //add
            'debit_amount' => $this->isValid($debit_amount),
            'balance' => $this->isValid($balance),
            'order_status' => $this->isValid("FAILED"),
            'transaction_msg'=>$result['message'],
            'updated_on'=>date('Y-m-d H:i:s'),
          );
          $txn_id = $this->transactions_model->addNewTransaction($trans_info);
          $userbalance = $this->rechargeApi_model->getUserBalance($user_id);
          $wallet_balance=$userbalance->wallet_balance;
          $updatedBalance = $wallet_balance+$apptblsmt->value;
          //insert DEBIT txn into tbl_wallet_trans_dtls table
          $wallet_trans_info = array(
            'service_id' =>$service_id,
            'order_id' => $this->isValid($order_id), 
            'user_id' => $user_id, 
            'operator_id' => $operator_id,
            'api_id' => $api_details->api_id,
            //'transaction_status' => $this->isValid($result['transaction_status']),
            'transaction_type' => "CREDIT",
            'payment_type' => "SERVICE",
            'payment_mode' => "REFUND FOR ACCOUNT VALIDATION FOR ACCOUNT NUMBER ".$data['bank_account_number'],
            'transaction_id' => $this->isValid($transaction_id),               
            'trans_date' => date("Y-m-d H:i:s"),  
            'total_amount' =>$apptblsmt->value,
            'charge_amount' => "0.00",
            'balance' => $updatedBalance,
            'updated_on'=>date('Y-m-d H:i:s'),
          );
          $wallet_txn_id = $this->transactions_model->addNewWalletTransaction($wallet_trans_info);
          //update balance into users table                           
          $updateBalQry = $this->rechargeApi_model->updateUserBalance($user_id,$updatedBalance);
          //update balance after deduction end
          $response = array(
            'status' => "false",
            'msg' => $result['message'],
            'result' => $result
          );
        }
       
        
        // $response = array(
        //   'status' => "false",
        //   'msg' => "API implementation under process. Please contact administrator.",
        //   'result' => null
        // );
        // echo json_encode($response);
        // exit;
      
      }else {
      
        
        
        $post=array("upi_id"=>$data['bank_account_number'],
                    "reference_number"=>$order_id);
        
       
         $ch = curl_init($url);
        # Setup request to send json via POST.
        $payload = json_encode($post);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:'.$api_token.''));
        # Return response instead of printing.
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        # Send request.
        $result = curl_exec($ch);
        // echo "<pre>";
        // print_r($result);
        curl_close($ch);
        $result =  json_decode($result, true);
        
        
        
        # Print response.
         //save api log details begin
        $api_info = array(
          'service_id' => $service_id."", 
          'api_id' => $api_details->api_id."", 
          'api_name' => $api_details->api_name."",  
          'api_method' => "doFundTransfer",
          'api_url' => $url."", 
          'order_id' => $order_id."", 
          'user_id' => $user_id."",  
          'request_input' => $payload."",
          'request' => $payload."",         
          'response' => json_encode($result)."",
          'access_type' => "APP",
          'updated_on'=>date('Y-m-d H:i:s'),
        );
        $api_log_id = $this->apiLog_model->addApiLogDetails($api_info); //save api log details end
        $transaction_id     =$result['data']['id']."";
        $bank_transaction_id=$result['data']['bank_ref_num']."";
        $account_holder_name=$result['data']['verify_upi_id_holder']."";
        $charges_tax       =$result['data']['charges_gst']."";
        $debit_amount      =$result['data']['settled_amount']."";
        $balance           = $result['data']['closing_balance']."";
        $status            =$result['data']['status']."";
        $bank_acc_no       =$result['data']['verify_upi_id']."";
        $bank_ifsc        ="";
        $responsecheck=$result['success']; 
        //$bank_holder_name =$result['data']['verify_account_holder']."",
       }}

        
        //update balance after deduction begin
        $updatedBalance = $wallet_balance-$apptblsmt->value;
          //insert DEBIT txn into tbl_wallet_trans_dtls table
          $wallet_trans_info = array(
            'service_id' =>$service_id,
            'order_id' => $this->isValid($order_id), 
            'user_id' => $user_id, 
            'operator_id' => $operator_id,
            'api_id' => $api_details->api_id,
            //'transaction_status' => $this->isValid($result['transaction_status']),
            'transaction_type' => "DEBIT",
            'payment_type' => "SERVICE",
            'payment_mode' => "UPI VALIDATION CHARGE FOR UPI ID ".$data['bank_account_number'],
            'transaction_id' => $this->isValid($transaction_id),               
            'trans_date' => date("Y-m-d H:i:s"),  
            'total_amount' =>$apptblsmt->value,
            'charge_amount' => "0.00",
            'balance' => $updatedBalance,
            'updated_on'=>date('Y-m-d H:i:s'),
          );
          $wallet_txn_id = $this->transactions_model->addNewWalletTransaction($wallet_trans_info);
          //update balance into users table                           
          $updateBalQry = $this->rechargeApi_model->updateUserBalance($user_id,$updatedBalance);
          //update balance after deduction end
          if($responsecheck == "SUCCESS")
          {
              $responsecheck="true";
          }
          if($responsecheck == "true"){

          $trans_info = array(
            'transaction_id' => $this->isValid($transaction_id),
            'operator_id'=>$operator_id,
            'service_id' =>$service_id, 
            'api_id' =>$api_details->api_id,
            'transaction_type'=>'UPI_VERIFICATION',
            'trans_date' => date("Y-m-d H:i:s"),
            'order_id' => $this->isValid($order_id),  
            'mobileno' => $data['sender_mobile_number'], 
            'user_id' => $this->isValid($user_id),          
            'total_amount' =>$apptblsmt->value,
            'bank_transaction_id' => $this->isValid($bank_transaction_id), //add
            'imps_name' => $account_holder_name, //add
            'charges_tax' => $this->isValid($charges_tax), //add

            'debit_amount' => $this->isValid($debit_amount),
            'balance' => $this->isValid($balance),
            'order_status' => $this->isValid("SUCCESS"),
            'updated_on'=>date('Y-m-d H:i:s'),
          );
          $txn_id = $this->transactions_model->addNewTransaction($trans_info);
          //update balance based on api id in api setting table developed by susmitha start
          //$currentapibal=$this->isValid($result['data']['closing_balance']);
          $datadb = array('balance'=>$balance);
           $this->apiLog_model->update_api_amount($datadb,$api_details->api_id);
         //update balance based on api id in apisetting table developed by susmitha end 

          //commission wallet txn begin
          if(is_numeric($role_id) && intval($role_id) <= 4){                
            $walletUserID = $user_id;
            $walletRoleID = $role_id;
            $isUserBalanceUpdated = false;
            for($i=$walletRoleID;$i>=1;$i--){                
              if($i==2 ||$i==3 || $i==4  || $i==7){
                $isUserBalanceUpdated = true;
                $userParentID = $this->rechargeApi_model->getUserParentID($walletUserID);
                if ($isUserBalanceUpdated && $userParentID && ($userParentID->roleId==2|| $userParentID->roleId==3||$userParentID->roleId==4||$userParentID->roleId==7)) {
                  $walletUserID = $userParentID->userId;
                  $walletRoleID = $userParentID->roleId;
                  $updatedBalance = $userParentID->wallet_balance;
                }
                continue;
              }
              $walletAmt = 0;
              $walletBal = 0;                 
              $userParentID = $this->rechargeApi_model->getUserParentID($walletUserID);
              if ($isUserBalanceUpdated && $userParentID) {
                $walletUserID = $userParentID->userId;
                $walletRoleID = $userParentID->roleId;
                $updatedBalance = $userParentID->wallet_balance;
              }
              

              if($walletRoleID == 1){//Admin
                $verifybankcheck=$this->moneyTransferApi_model->get_singlebyid('tbl_verified_banks','bank_acc_no',$bank_acc_no);
                if(!empty($verifybankcheck)){
                  $walletAmt = $apptblsmt->value-$appmastersmt->value;
                  $walletBal = $updatedBalance+($apptblsmt->value-$appmastersmt->value);
                }else{
                $walletAmt = $apptblsmt->value-$apptblpay->value;
                $walletBal = $updatedBalance+($apptblsmt->value-$apptblpay->value);
                }
                
              }
              if(is_numeric($walletAmt) && is_numeric($walletBal)){
                $transType = "CREDIT";
                if($walletAmt < 0){
                  $transType = "DEBIT";
                }
                $wallet_trans_info = array(
                  'service_id' => $service_id,
                  'order_id' => $this->isValid($order_id), 
                  'user_id' => $walletUserID, 
                  'operator_id' => $operator_id,
                  'api_id' => $api_details->api_id,
                  'transaction_status' => $status,
                  'transaction_type' => $transType,
                  'payment_type' => "COMMISSION",
                  'payment_mode' => "Commission by bank verification",
                  'transaction_id' => $this->isValid($transaction_id),               
                  'trans_date' => date("Y-m-d H:i:s"),  
                  'total_amount' => abs($walletAmt),
                  'charge_amount' => "0.00",
                  'balance' => $walletBal,
                  'updated_on'=>date('Y-m-d H:i:s'),
                );
                $wallet_txn_id = $this->transactions_model->addNewWalletTransaction($wallet_trans_info);
                //update balance into users table                           
                $updateBalQry = $this->rechargeApi_model->updateUserBalance($walletUserID,$walletBal);
                //update balance after deduction end
              }
              $isUserBalanceUpdated = true;
            }
          }
          //after verify bank insert bank details start
           $verifybank=array(
            "bank_acc_no"=>$bank_acc_no, 
            "bank_ifsc"  =>$bank_ifsc."",
            "bank_holder_name"  =>$account_holder_name,
            "created_at"  =>date('Y-m-d H:i:s'));
           //print_r($verifybank);
           if(empty($verifybankcheck)){
            $this->db->insert('tbl_verified_banks',$verifybank);
           }
             //$avno=$result['data']['verify_account_number'];
             
            $where=array("sender_mobile_number"=>$mobile,"bank_account_number"=>$bank_acc_no);
            $updatebeni=array("recipient_name"=>$account_holder_name,"is_verified"=>"Y","verified_name"=>$account_holder_name);
             $this->db->where($where);
             $this->db->update('tbl_dmt_benificiary_dtls',$updatebeni);
             $verify_result=array("verify_account_number"=>$bank_acc_no,
                                  "verify_account_ifsc"=>$bank_ifsc,
                                  "verify_account_holder"=>$account_holder_name);
           //end verify bank
            $response = array(
            'status' => "true",
            'msg' => "Success",
            'result' =>$verify_result
            );
        }
         
         else{
          $trans_info = array(
            'transaction_id' => $this->isValid($result['data']['id']),
            'operator_id'=>$operator_id,
            'service_id' =>$service_id, 
            'api_id' =>$api_details->api_id,
            'transaction_type'=>'UPI_VERIFICATION',
            'trans_date' => date("Y-m-d H:i:s"),
            'order_id' => $this->isValid($order_id),  
            'mobileno' => $this->isValid($data['sender_mobile_number']), 
            'user_id' => $this->isValid($user_id),          
            'total_amount' =>$apptblsmt->value,
            'bank_transaction_id' =>$this->isValid($bank_transaction_id), //add
            'imps_name' =>$account_holder_name, //add
            // 'recipient_id' => $this->isValid($result['recipient_id']), //add
            'charges_tax' => $this->isValid($charges_tax), //add

            // 'commission' => $this->isValid($result['commission']), //add
            // 'commission_tax' => $this->isValid($result['commission_tax']), //add
            // 'commission_tds' => $this->isValid($result['commission_tds']), //add
            'debit_amount' => $this->isValid($debit_amount),
            'balance' => $this->isValid($balance),
            'order_status' => $this->isValid("FAILED"),
            'transaction_msg'=>$result['message'],
            'updated_on'=>date('Y-m-d H:i:s'),
          );
          $txn_id = $this->transactions_model->addNewTransaction($trans_info);
          $userbalance = $this->rechargeApi_model->getUserBalance($user_id);
          $wallet_balance=$userbalance->wallet_balance;
          $updatedBalance = $wallet_balance+$apptblsmt->value;
          //insert DEBIT txn into tbl_wallet_trans_dtls table
          $wallet_trans_info = array(
            'service_id' =>$service_id,
            'order_id' => $this->isValid($order_id), 
            'user_id' => $user_id, 
            'operator_id' => $operator_id,
            'api_id' => $api_details->api_id,
            //'transaction_status' => $this->isValid($result['transaction_status']),
            'transaction_type' => "CREDIT",
            'payment_type' => "SERVICE",
            'payment_mode' => "UPI VALIDATION CHARGE FOR UPI ID ".$data['bank_account_number'],
            'transaction_id' => $this->isValid($transaction_id),               
            'trans_date' => date("Y-m-d H:i:s"),  
            'total_amount' =>$apptblsmt->value,
            'charge_amount' => "0.00",
            'balance' => $updatedBalance,
            'updated_on'=>date('Y-m-d H:i:s'),
          );
          $wallet_txn_id = $this->transactions_model->addNewWalletTransaction($wallet_trans_info);
          //update balance into users table                           
          $updateBalQry = $this->rechargeApi_model->updateUserBalance($user_id,$updatedBalance);
          //update balance after deduction end
          $response = array(
            'status' => "false",
            'msg' => $result['message'],
            'result' => $result
          );
        }
       
        
        
      
    }else{
      $response = array(
        'status' => "false",
        'msg' => "Invalid Request",
        'result' => null
      );
    }
    echo json_encode($response);
    exit;   
  }
  
   public function fundAccount_RezorpayUpi($upi_id,  $razorpay_contact_id){

        $czy_api = $this->db->select('*')->from('tbl_api_settings')->where('api_alias', 'rezorpay')->get()->row();
        $username = $czy_api->key_id;
        $password = $czy_api->api_secretkey;
        $api_url = $czy_api->api_url;

    $curl = curl_init();

    curl_setopt_array($curl, array(
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
                                    "account_type": "vpa",
                                    "vpa": {
                                      "address": "'.$upi_id.'"
                                    }
                                  }',
                                    CURLOPT_HTTPHEADER => array(
                                      'Content-Type: application/json',
                                      'Authorization: Basic cnpwX2xpdmVfaFpvd2g4NnlVSEVJNmM6TTFWZGNkbFRkZ3c4OVUwTmRCVEZVOEEw'
                                    ),
                                ));
    
    $response = curl_exec($curl);
    
    curl_close($curl);
    // echo $response;

      return json_decode($response, true);
   }

  public function doFundTransfer() {
    $input =  file_get_contents('php://input'); 
    $data =  json_decode($input, true);
    $this->authenticateUser($data);
    $recid=$data['recipient_id'];
   $sender_mobile_number=$data['sender_mobile_number'];
    

    if(!empty($data['operatorID']) && !empty($data['sender_mobile_number'])){
      //get service details by operator id begin
      $operator_id = $data['operatorID'];
      $serviceDtl = $this->operatorApi_model->getServiceDetailsByOperatorID($operator_id);
      if($serviceDtl){
       $service_id = $serviceDtl[0]->service_id;
      }else{
        $response = array(
          'status' => "false",
          'msg' => "Invalid Service",
          'result' => null
        );
        echo json_encode($response);
        exit;
      }
      //get service details by operator id end 

      //check active api for operator begin
      $api_details = $this->rechargeApi_model->getActiveApiDetails($operator_id,$service_id,"");
      // echo "<pre>";
      // print_r($api_details);
      if(!$api_details){
        $response = array(
          'status' => "false",
          'msg' => "API details not configured for operator.Please contact administrator.",
          'result' => null
        );
        echo json_encode($response);
        exit;
      }
      $upi = $this->moneyTransferApi_model->get_table_alllike('tbl_application_details','alias','upilimit');
      $limit = $upi[0]->value;
      //check ampunt more than 100000 start
        if($data['transaction_amount'] > $limit){
        $response = array(
          'status' => "false",
          'msg' => "Exceed amount",
          'result' => null
        );
        echo json_encode($response);
        exit;
      }
      //check ampunt more than 100000 end
      //check active api for operator end
        //check fund transfer duplicate occur with in minutes start
        $wherecheck=array("service_id"=>$service_id,
                          "operator_id"=>$operator_id,
                          "recipient_id"=>$data['recipient_id'],
                          "request_amount"=>$data['transaction_amount']);
        $transdate=date('Y-m-d H:i');
        $min="5";
        $transduplicatecheck=$this->moneyTransferApi_model->checkduplicate_transaction_new($transdate,$wherecheck,$min);
        if(!empty($transduplicatecheck)){
           $response = array(
          
          'status' => "false",
          'msg' => "Same receipt and amount just now hit one Trasaction so Try again after a minute",
          'result' =>"",
        );
        echo json_encode($response);
        exit;
        }//check fund transfer occur with in minutes end
       
        $order_uni=$this->get_order_id();
        $ores=json_decode($order_uni,true);
        $sno_order_id=$ores['sno_order_id'];
        $order_id=$ores['order_id'];
      
   
      // if($api_details->api_id == "8" && floatval($data['transaction_amount']) > 5000){ //calling multi fund transfer API
      //   $this->doMultipleFundTransfer(file_get_contents('php://input'));
      //   die;
      // }
      // if($api_details->api_id == "9" && floatval($data['transaction_amount']) > 5000){ //calling multi fund transfer API
      //   $this->doMultipleFundTransfer(file_get_contents('php://input'));
      //   die;
        
      // }
      //  if($api_details->api_id == "12" && floatval($data['transaction_amount']) > 5000){ //calling multi fund transfer API
        
      //   $this->doMultipleFundTransfer(file_get_contents('php://input'));
      //   die;
        
      // }
      // if($api_details->api_id == "13" && floatval($data['transaction_amount']) > 5000){ //calling multi fund transfer API
        
      //   $this->doMultipleFundTransfer(file_get_contents('php://input'));
      //   die;
        
      // }
          
      //check balance of user begin
      $amount = $data['transaction_amount'];
      $operator_id = $data['operatorID'];
      $user_id= $data['user_id'];
      $role_id= $data['role_id'];
     
      $userbalance = $this->rechargeApi_model->getUserBalance($data['user_id']);
      if ($userbalance) {
        $wallet_balance = $userbalance->wallet_balance;
        $min_balance = $userbalance->min_balance;
        $user_package_id = $userbalance->package_id;
        //susmitha commision cal
        $commissiondet = $this->rechargeApi_model->getcommission($service_id,$user_package_id,$operator_id,$amount);
        $ccf=$commissiondet->ccf_commission;
        $charge = $commissiondet->retailer_commission;
         $cashback=$ccf-$charge;
        //cashback update to trans table based on order_id
        $app    =$this->rechargeApi_model->getTDS();
        $TDS     =$cashback*($app->value/100);
        $PayableCharge = $charge+$TDS;
        $totalAmount=$amount+$PayableCharge;
        if(is_numeric($wallet_balance) && is_numeric($min_balance) && is_numeric($totalAmount) && $wallet_balance-$totalAmount < $min_balance){
          $response = array(
              'status' => "false",
              'msg' => "Insufficient balance",
              'result' => null
          );
          echo json_encode($response);
          exit;
        }else if(!is_numeric($wallet_balance) || !is_numeric($min_balance) || !is_numeric($totalAmount)){
          $response = array(
              'status' => "false",
              'msg' => "Invalid amount details.",
              'result' => null
          );
          echo json_encode($response);
          exit;
        }else{//get all commission details by package id
          $commissionDtl = $this->rechargeApi_model->getCommissionDetails($user_package_id,$service_id,$operator_id,$amount);              
          if ($commissionDtl) {

            if($commissionDtl->commission_type == "Rupees"){
              
              $admin_commission = $commissionDtl->admin_commission;
              $md_commission = $commissionDtl->md_commission;
              $api_commission = $commissionDtl->api_commission;
              $distributor_commission = $commissionDtl->distributor_commission;
              $retailer_commission = $commissionDtl->retailer_commission;
              // //susmitha commision cal
              // $ccf=$commissionDtl->ccf_commission;
              // $charge = $commissionDtl->retailer_commission;
              // $cashback=$ccf-$charge;
              // //cashback update to trans table based on order_id
              // $app    =$this->rechargeApi_model->getTDS();
              // $TDS     =$cashback*$app->value;
              // $PayableCharge = $charge+$TDS;
              // $totalAmount=$amount+$PayableCharge;
              
            }else if($commissionDtl->commission_type == "Percent"){
              $admin_commission = ($amount*$commissionDtl->admin_commission)/100;
              $md_commission = ($amount*$commissionDtl->md_commission)/100;
              $api_commission = ($amount*$commissionDtl->api_commission)/100;
              $distributor_commission = ($amount*$commissionDtl->distributor_commission)/100;
              $retailer_commission = ($amount*$commissionDtl->retailer_commission)/100;
            }else if($commissionDtl->commission_type == "Range"){
              if($commissionDtl->admin_commission_type == "Rupees")
                $admin_commission = $commissionDtl->admin_commission;
              else
                $admin_commission = ($amount*$commissionDtl->admin_commission)/100;
              if($commissionDtl->md_commission_type == "Rupees")
                $md_commission = $commissionDtl->md_commission;
              else
                $md_commission = ($amount*$commissionDtl->md_commission)/100;
              if($commissionDtl->distributor_commission_type == "Rupees")
                $distributor_commission = $commissionDtl->distributor_commission;
              else
                $distributor_commission = ($amount*$commissionDtl->distributor_commission)/100;
              if($commissionDtl->retailer_commission_type == "Rupees")
                $retailer_commission = $commissionDtl->retailer_commission;
              else
                $retailer_commission = ($amount*$commissionDtl->retailer_commission)/100;
                $api_commission = $commissionDtl->api_commission;
            }
          }
        }
      }else{
        $response = array(
            'status' => "false",
            'msg' => "Error while retriving balance",
            'result' => null
        );
        echo json_encode($response);
        exit;
      }
      //check balance of user end

      //validate mpin begin
      if(isset($data['mpin']) && !empty($data['mpin'])){
        $userDtls = $this->rechargeApi_model->getValidateMPIN($data['user_id'],$data['mpin']);
        if(!$userDtls){
          $response = array(
            'status' => "false",
            'msg' => "Invalid MPIN",
            'result' => null
          );
          echo json_encode($response);
          exit;
        }
      }else{
        $response = array(
          'status' => "false",
          'msg' => "Invalid MPIN",
          'result' => null
        );
        echo json_encode($response);
        exit;
      }
      //validate mpin end

      //if all above conditions valid then update order id in file
     // $this->writeTxnOrderID($sno_order_id);      
        
      if($api_details->api_id == "12") {//paytm
         $mobile=$data['sender_mobile_number'];
        //get receipt account number ifsc from receipt table based on receipt id
          $receipt_details=$this->moneyTransferApi_model->get_singlebyid('tbl_dmt_benificiary_dtls','recipient_id',$data['recipient_id']);
          if(empty($receipt_details)){
           $response = array(
          'status' => "false",
          'msg' => "Recipient Id not there check receipt id!! try again.",
          'result' => null
              );
          echo json_encode($response);
        exit;
          }
       
        $senderwhere  =array("sender_mobile_number"=>$mobile,
                             "api_name"=>"paytm");
        //print_r($senderwhere);

        $sender_avlcheck =$this->moneyTransferApi_model->getdata_where('tbl_sender_dts',$senderwhere);
        $senamount=$sender_avlcheck[0]->available_limit;
        
        
        if($amount>$senamount){
           $response = array(
          
          'status' => "false",
          'msg' => "Limit Exceed",
          'result' =>"",
        );
        echo json_encode($response);
        exit;
        }//check avalibe limit in sender table end
         //susmitha commision cal
        $commissionDtl = $this->rechargeApi_model->getcommission($service_id,$user_package_id,$operator_id,$amount);
        $ccf=$commissionDtl->ccf_commission;
        // $charge = $commissionDtl->retailer_commission;
        if($commissionDtl->retailer_commission_type == 'Percent'){
          $charge = $amount * ($commissionDtl->retailer_commission/100);
        }elseif($commissionDtl->retailer_commission_type == 'Rupees'){
          $charge = $commissionDtl->retailer_commission;
        }
        $cashback=$ccf-$charge;
        $app    =$this->rechargeApi_model->getTDS();
        $TDS     =$cashback*($app->value/100);
        $PayableCharge = $charge+$TDS;
        $totalAmount=$amount+$PayableCharge;
        $updatedBalance = $wallet_balance-$totalAmount; //update balance after deduction begin
        //insert DEBIT txn into tbl_wallet_trans_dtls table
          $wallet_trans_info = array(
            'service_id' =>$service_id,
            'order_id' => $this->isValid($order_id), 
            'user_id' => $user_id, 
            'operator_id' => $operator_id,
            'api_id' => $api_details->api_id,
            'transaction_status' =>'Success',
            'transaction_type' => "DEBIT",
            'payment_type' => "SERVICE",
            'payment_mode' => "PAID FOR UPI, UPI ID ".$receipt_details->bank_account_number.", AMOUNT ".$totalAmount.", CHARGE ".$PayableCharge,
            'transaction_id' =>'',               
            'trans_date' => date("Y-m-d H:i:s"),  
            'total_amount' => $totalAmount,
            'charge_amount' => "0.00",
            'balance' => $updatedBalance,
            'CCFcharges'=>$ccf,
            'Cashback'=>$cashback,
            'TDSamount'=>$TDS,
            'PayableCharge'=>$PayableCharge,
            'FinalAmount'=>$totalAmount,
            'updated_on'=>date('Y-m-d H:i:s'),
          );
          $wallet_txn_id = $this->transactions_model->addNewWalletTransaction($wallet_trans_info);
          //update balance into users table                           
          $updateBalQry = $this->rechargeApi_model->updateUserBalance($user_id,$updatedBalance);
          //update balance after deduction end
        
          $trans_info = array(
            'transaction_id' =>"0",
            'transaction_status' =>"PENDING", 
            'service_id' => $service_id, 
            'operator_id'=>$operator_id,
            'api_id' => $api_details->api_id,
            'trans_date' => date("Y-m-d H:i:s"),
            'order_id' => $this->isValid($order_id),  
            'mobileno' =>$data['sender_mobile_number']."", 
            'user_id' => $this->isValid($user_id),          
            'total_amount' => $this->isValid($amount),
            'charge_amount' =>"0",
            'transaction_type' => $data['transaction_type'], //add
            // 'bank_transaction_id' =>$result['result']['paytmOrderId'], //add
            'bank_transaction_id' =>"", //add
            'imps_name' =>$receipt_details->recipient_name."", //add
            'recipient_id' =>$data['recipient_id'], //add
            'charges_tax' =>"0", //add
            'commission' =>"0", //add
            'commission_tax' =>"0", //add
            'commission_tds' =>"0", //add
            'debit_amount' =>"0",
            'balance' =>"0",
            'order_status' => "PENDING",
            'transaction_msg'=>"",
            'CCFcharges'=>$ccf,
            'Cashback'=>$cashback,
            'TDSamount'=>$TDS,
            'PayableCharge'=>$PayableCharge,
            'FinalAmount'=>$totalAmount,
            'request_amount'=>$amount,
            'updated_on'=>date('Y-m-d H:i:s'),
      );
      //print_r($trans_info);
        $txn_id = $this->transactions_model->addNewTransaction($trans_info);

          //echo "<pre>";
          $paytmParams = array();

          $paytmParams["subwalletGuid"]      = $api_details->api_token;
          $paytmParams["orderId"]            = $order_id;
          //$paytmParams["beneficiaryAccount"] = $receipt_details->bank_account_number;
          $paytmParams["beneficiaryVPA"]     = $receipt_details->bank_account_number;
          
          //$paytmParams["beneficiaryIFSC"]    = $receipt_details->ifsc;
          $paytmParams["amount"]             = $amount;
          $paytmParams["purpose"]            = "OTHERS";
          $paytmParams["date"]               = date('Y-m-d');
          $paytmParams["transferMode"]       =$data['transaction_type'];
          $paytmParams["callbackUrl"]       = "https://paymamaapp.in/admin/index.php/Transactions/paytm_moneytrans_status/".$order_id;
        
          $post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);
           //echo "<pre>";
          //  print_r($post_data);

          //save api log details begin
          $api_info = array(
            'service_id' => $service_id."", 
            'api_id' => $api_details->api_id."", 
            'api_name' => $api_details->api_name."",  
            'api_method' => "doFundTransfer",
            'api_url' => $api_details->api_url."", 
            'order_id' => $order_id."", 
            'user_id' => $user_id."",  
            'request_input' => json_encode($post_data)."",
            'request' => json_encode($post_data)."",         
            'response' =>"",
            'access_type' => (isset($data['access_type'])) ? $data['access_type'] : 'DEFAULT' ,
            'updated_on'=>date('Y-m-d H:i:s'),
          );
          $api_log_id = $this->apiLog_model->addApiLogDetails($api_info);
          //save api log details end

          /*
          * Generate checksum by parameters we have in body
          * Find your Merchant Key in your Paytm Dashboard at https://dashboard.paytm.com/next/apikeys 
          */
          $checksum = PaytmChecksum::generateSignature($post_data,$api_details->api_secretkey);

          $x_mid      = $api_details->username;
          $x_checksum = $checksum;

          /* for Staging */
          $url =$api_details->api_url;

          /* for Production */
          // $url = "https://dashboard.paytm.com/bpay/api/v1/disburse/order/bank";

          $ch = curl_init($url);
          curl_setopt($ch, CURLOPT_POST, true);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
          curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "x-mid: " . $x_mid, "x-checksum: " . $x_checksum)); 
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
          $result_json = curl_exec($ch);
          curl_close($ch);
          $result =  json_decode($result_json, true);

          $this->db->where('order_id', $order_id)->update('tbl_apilog_dts', array("response"=>$result_json, 'updated_on'=>date('Y-m-d H:i:s')));
          /*if($result['statusCode']=="DE_002"){
            // print_r('Condition');
            $status_json = $this->check_paytm_status($order_id,$api_details->api_secretkey,$api_details->username,$api_details->api_trn_status_url); 
            $result =  json_decode($status_json, true);   
          }*/
          
          //temporary refund status
         // $result['status']="CANCEL";
          if($result['status'] == "SUCCESS" || $result['status'] == "ACCEPTED" || $result['status'] == "PENDING") {
            
              //update balance based on api id in api setting table developed by susmitha start

              $trans_record = [];
              $oStatus = $result['status'];
              while ($oStatus=='PENDING') {
                  $trans_record = $this->db->select('*')->from('tbl_transaction_dtls')->where('order_id', $order_id)->get()->row();
                  // $trans_record =$this->moneyTransferApi_model->getdata_where('tbl_transaction_dtls',array('order_id'=> $order_id ));
                  if ($trans_record->order_status != 'PENDING' ) {
                      // $oStatus = $trans_record[0]->order_status;
                      $oStatus = $trans_record->order_status;
                      break;
                  }
              }

              if ( $oStatus == 'FAILED') {
                  $failed_resp = $this->failedPaytmTransfer($trans_info, $trans_record->order_status );
                  $response = array(
                    'status' => "false",
                    'msg' => 'failed',
                    'result' => $trans_record
                  );   
                  echo json_encode($response) ;

                  exit;
              }

              $currentapibal=0;
              $data = array('balance'=>$currentapibal);
              $this->apiLog_model->update_api_amount($data,$api_details->api_id);
              //update balance based on api id in apisetting table developed by susmitha end  
              //update sender avaland used limit start
              $sender_det  =array("sender_mobile_number"=>$mobile);
              $sender_details =$this->moneyTransferApi_model->getdata_where('tbl_sender_dts',$sender_det);
              $available_limit=($sender_details[0]->Upi_available_limit)-($amount);
              $used_limit=($sender_details[0]->Upi_used_limit)+($amount);
              $sender_detupdate=array("Upi_available_limit"=>$available_limit,
                                    "Upi_used_limit"=>$used_limit);
              //print_r($sender_detupdate);
              $this->db->where('sender_mobile_number',$mobile);
              $this->db->update('tbl_sender_dts',$sender_detupdate);
            //update sender avaland used limit end
              $recipent_ver  =array("recipient_id"=>$recid,"is_verified"=>"Y");
            $benificiary_details =$this->moneyTransferApi_model->getdata_where('tbl_dmt_benificiary_dtls',$recipent_ver);
            
            // if(empty($benificiary_details)){
            //   $updatebeni=array("is_verified"=>"Y","verified_name"=>$receipt_details->recipient_name);
            // $where=array('recipient_id',$data['recipient_id']); 
            // $this->db->where($where);
            // $this->db->update('tbl_dmt_benificiary_dtls',$updatebeni);
            // }
            $money_re=$this->transactions_model->getmoneyreport($txn_id);
            
            // print_r($money_re);
           
         
            //send sms 
            $digits = 5;
            $rand=rand(pow(10, $digits-1), pow(10, $digits)-1);
            //echo "$rand and " . ($startDate + $rand);
            $username="NAIDUSOFTWARE";
            $password="4049102";
            
            $msisdn=$sender_mobile_number;
            $sid="PYMAMA";
            
            // $msg="Transaction Done TID: ".$txn_id." -  order id ".$money_re->order_no.", Amt : Rs ".$amount." Fees: 1 % Name :".$money_re->name."  A/c : ".$money_re->bank_account_number." IFSC: ".$money_re->ifsc." SMARTPAY - ";
              // $msg="TID: ".$money_re->order_no." Amt : Rs ".$amount." Fees: 1 %, Name : ".$money_re->name." A/c : ".$money_re->bank_account_number." Mobile ".$benificiary_details->recipient_mobile_number." IFSC: ".$money_re->ifsc." www.paymamaapp.in";
              $msg="TID : ".$order_id." Amt : ".$amount." Fees: 1 %, Name: ".$benificiary_details[0]->verified_name." A/c: ".$benificiary_details[0]->bank_account_number." Mobile ".$benificiary_details[0]->recipient_mobile_number." Bank ".$benificiary_details[0]->bank_name." IFSC: ".$benificiary_details[0]->ifsc." www.paymamaapp.in";
              //TID : {#var#} Amt : {#var#} Fees: 1 %, Name : {#var#} A/c : {#var#} Mobile {#var#} Bank {#var#} IFSC: {#var#} www.paymamaapp.in
              //$msg="".$rand."%20is%20your%20verification%20code.%20Regards%20Saral%20Shaadi.";
            $tem_sms_row= $this->db->select('*')->from('tbl_sms_templates')->where('alias', 'money_transfer')->get()->row();
            
            //comment by vishal
            //   $url="http://cloud.smsindiahub.in/vendorsms/pushsms.aspx?user=".$username."&password=".$password."&msisdn=".$msisdn."&sid=".$sid."&msg=".$msg."&fl=0&gwid=2";
            //  $ch = curl_init();  
            //  curl_setopt($ch,CURLOPT_URL,$url);
            //  curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
            //  $output=curl_exec($ch);
            //  curl_close($ch);
            $temp_id=1207163818494893617;
                $sms_output = $this->sendBulkSMS($msisdn, $msg, $temp_id); 
            //by vishal End
            //send sms 
            //commission wallet txn begin
              if(is_numeric($role_id) && intval($role_id) <= 4){
                $walletUserID = $user_id;
                $walletRoleID = $role_id;
                $isUserBalanceUpdated = false;
                
                for($i=$walletRoleID;$i>=1;$i--){                
                  if($i==3 || $i==4  || $i==7){
                    $isUserBalanceUpdated = true;
                    $userParentID = $this->rechargeApi_model->getUserParentID($walletUserID);
                    if ($isUserBalanceUpdated && $userParentID && ( $userParentID->roleId==3||$userParentID->roleId==4||$userParentID->roleId==7) ) {
                      $walletUserID = $userParentID->userId;
                      $walletRoleID = $userParentID->roleId;
                      $updatedBalance = $userParentID->wallet_balance;
                    }
                    continue;
                  }
                  $walletAmt = 0;
                  $walletBal = 0;    
                  $distds='';             
                  $userParentID = $this->rechargeApi_model->getUserParentID($walletUserID);
                  if ($isUserBalanceUpdated && $userParentID) {
                    $walletUserID = $userParentID->userId;
                    $walletRoleID = $userParentID->roleId;
                    $updatedBalance = $userParentID->wallet_balance;
                  }
                  /*echo "Wallet User ID:".$walletUserID." Role ID:".$walletRoleID."<br/>";
                  echo "Retailer Commision:".$retailer_commission."Dist.:".$distributor_commission."Admin.:".$admin_commission."<br/>";*/
                  // if($walletRoleID == 4){ //Retailer
                  //   $walletAmt = $retailer_commission;
                  //   $walletBal = $updatedBalance+$retailer_commission;
                  // }
                  /*else if($walletRoleID == 3){ //FOS
                    $walletAmt = $distributor_commission;
                    $walletBal = $updatedBalance+$distributor_commission;
                  }*/ if($walletRoleID == 2){ //Distributor
                    //$walletAmt = $distributor_commission;
                    $ds = $distributor_commission;
                      $apptds    =$this->rechargeApi_model->getTDS();
                      $distds=$ds*($apptds->value/100);
                      $walletAmt=$ds-$distds; 
                    $walletBal = $updatedBalance+$distributor_commission;
                  }else if($walletRoleID == 1){ //Admin
                    $walletAmt = $admin_commission;
                    $walletBal = $updatedBalance+$admin_commission;
                  }
                  if(is_numeric($walletAmt) && is_numeric($walletBal)){
                    $transType = "CREDIT";
                    if($walletAmt < 0){
                      $transType = "DEBIT";
                    }
                    $wallet_trans_info = array(
                      'service_id' => $service_id,
                      'order_id' => $this->isValid($order_id), 
                      'user_id' => $walletUserID, 
                      'operator_id' => $operator_id,
                      'api_id' => $api_details->api_id,
                      'transaction_status' => $result['status'],
                      'transaction_type' => $transType,
                      'payment_type' => "COMMISSION",
                      'payment_mode' => "COMMISSION FOR UPI, UPI ID ".$benificiary_details->bank_account_number.", AMOUNT ".$walletAmt,
                      'transaction_id' => "",               
                      'trans_date' => date("Y-m-d H:i:s"),  
                      'total_amount' => abs($walletAmt),
                      'charge_amount' => "0.00",
                      'balance' => $walletBal,
                      'TDSamount'=>$distds,
                      'updated_on'=>date('Y-m-d H:i:s'),
                    );
                    $wallet_txn_id = $this->transactions_model->addNewWalletTransaction($wallet_trans_info);
                    //update balance into users table                           
                    $updateBalQry = $this->rechargeApi_model->updateUserBalance($walletUserID,$walletBal);
                    //update balance after deduction end
                  }
                  $isUserBalanceUpdated = true;
                }
              }
              //commission wallet txn end
               $response = array(
              'status' => "true",
              'msg' => "Success",
              'result' => $result,
              'money'=>$money_re
            );
          }else{
          $trans_info = array(
            'transaction_id' =>"",
            'transaction_status' =>$result['status'], 
            'service_id' => $service_id, 
            'operator_id'=>$operator_id,
            'api_id' => $api_details->api_id,
            'trans_date' => date("Y-m-d H:i:s"),
            'order_id' => $this->isValid($order_id),  
            'mobileno' => $this->isValid($data['sender_mobile_number']), 
            'user_id' => $this->isValid($user_id),          
            'total_amount' => $amount,
            'charge_amount' =>"",
            'transaction_type' => $data['transaction_type'], //add
            'bank_transaction_id' =>"", //add
            'imps_name' => $receipt_details->recipient_name."", //add
            'recipient_id' =>$receipt_details->recipient_id, //add
            'charges_tax' =>"", //add
            'commission' =>"", //add
            'commission_tax' =>"", //add
            'commission_tds' =>"", //add
            'debit_amount' =>"",
            'balance' => "",
            'order_status' => $this->isValid("FAILED"),
            'transaction_msg'=>$result['statusMessage'],
            'updated_on'=>date('Y-m-d H:i:s'),
            'request_amount'=>$amount,
            
          );
          $txn_id = $this->transactions_model->addNewTransaction($trans_info);
          //update balance after deduction begin
        //$updatedBalance = $wallet_balance-$amount;
          $userbalance = $this->rechargeApi_model->getUserBalance($data['user_id']);
          $wallet_balance=$userbalance->wallet_balance;
          $updatedBalance = $wallet_balance+$totalAmount; 
          //insert DEBIT txn into tbl_wallet_trans_dtls table
          $wallet_trans_info = array(
            'service_id' =>$service_id,
            'order_id' => $this->isValid($order_id), 
            'user_id' => $user_id, 
            'operator_id' => $operator_id,
            'api_id' => $api_details->api_id,
            'transaction_status'=>$result['status'],
            'transaction_type'=>"CREDIT",
            'payment_type' => "SERVICE",
            'payment_mode' => "PAID FOR UPI, UPI ID ".$receipt_details->bank_account_number.", AMOUNT ".$totalAmount.", CHARGE ".$PayableCharge,
            'transaction_id' =>"",               
            'trans_date' => date("Y-m-d H:i:s"),  
            'total_amount' => $totalAmount,
            'charge_amount' => "0.00",
            'balance' => $updatedBalance,
            'updated_on'=>date('Y-m-d H:i:s'),
          );
          $wallet_txn_id = $this->transactions_model->addNewWalletTransaction($wallet_trans_info);
          //update balance into users table                           
          $updateBalQry = $this->rechargeApi_model->updateUserBalance($user_id,$updatedBalance);
          //update balance after deduction end
          $response = array(
            'status' => "false",
            'msg' => $result['statusMessage'],
            'result' => $result
          );        
        }
         

      }
      else if($api_details->api_id == "13"){ //hypto
      	//echo $api_details->api_id;
        $url = $api_details->api_url."/api/transfers/initiate";
        $api_token=$api_details->api_token;
        $mobile=$data['sender_mobile_number'];
        //get receipt account number ifsc from receipt table based on receipt id
          $receipt_details=$this->moneyTransferApi_model->get_singlebyid('tbl_dmt_benificiary_dtls','recipient_id',$data['recipient_id']);
          if(empty($receipt_details)){
              $response = array(
                  'status' => "false",
                  'msg' => "Recipient Id not there check receipt id!! try again.",
                  'result' => null
              );
              echo json_encode($response);
              exit;
          }
         //check avalibe limit in sender table start
        $senderwhere  =array("sender_mobile_number"=>$mobile,
                             "api_name"=>"paytm");
        $sender_avlcheck =$this->moneyTransferApi_model->getdata_where('tbl_sender_dts',$senderwhere);
        $senamount=$sender_avlcheck[0]->available_limit;
        if($amount>$senamount){
           $response = array(
          
          'status' => "false",
          'msg' => "Limit Exceed",
          'result' =>"",
        );
        echo json_encode($response);
        exit;
        }//check avalibe limit in sender table end
         //susmitha commision cal
        // echo $service_id;
        // echo $user_package_id;
        // echo $operator_id;
        // echo $amount;
        $commissionDtl = $this->rechargeApi_model->getcommission($service_id,$user_package_id,$operator_id,$amount);
        $ccf=$commissionDtl->ccf_commission;
        // $charge = $commissionDtl->retailer_commission;
        if($commissionDtl->retailer_commission_type == 'Percent'){
          $charge = $amount * ($commissionDtl->retailer_commission/100);
        }elseif($commissionDtl->retailer_commission_type == 'Rupees'){
          $charge = $commissionDtl->retailer_commission;
        }
        $cashback=$ccf-$charge;
        $app    =$this->rechargeApi_model->getTDS();
        $TDS     =$cashback*($app->value/100);
        $PayableCharge = $charge+$TDS;
        $totalAmount=$amount+$PayableCharge;
        $updatedBalance = $wallet_balance-$totalAmount; //update balance after deduction begin
        //insert DEBIT txn into tbl_wallet_trans_dtls table
          $wallet_trans_info = array(
            'service_id' =>$service_id,
            'order_id' => $this->isValid($order_id), 
            'user_id' => $user_id, 
            'operator_id' => $operator_id,
            'api_id' => $api_details->api_id,
            'transaction_status' =>'Success',
            'transaction_type' => "DEBIT",
            'payment_type' => "SERVICE",
            'payment_mode' => "PAID FOR UPI, UPI ID ".$receipt_details->bank_account_number.", AMOUNT ".$totalAmount.", CHARGE ".$PayableCharge,
            'transaction_id' =>'',               
            'trans_date' => date("Y-m-d H:i:s"),  
            'total_amount' => $totalAmount,
            'charge_amount' => "0.00",
            'balance' => $updatedBalance,
            'CCFcharges'=>$ccf,
            'Cashback'=>$cashback,
            'TDSamount'=>$TDS,
            'PayableCharge'=>$PayableCharge,
            'FinalAmount'=>$totalAmount,
            'updated_on'=>date('Y-m-d H:i:s'),
          );
          $wallet_txn_id = $this->transactions_model->addNewWalletTransaction($wallet_trans_info);
          //update balance into users table                           
          $updateBalQry = $this->rechargeApi_model->updateUserBalance($user_id,$updatedBalance);
          //update balance after deduction end

          $post=array(
            "amount"=>$amount,
            "payment_type"=>$data['transaction_type'],
            //"ifsc"   =>$receipt_details->ifsc,
            //"number"=>$receipt_details->bank_account_number,
            "upi_id"  =>$receipt_details->bank_account_number,
            "note"  =>"Fund Transfer",
            "reference_number"=>$order_id);
          

          $result_json=$this->hyptocurl($url,$post,$api_token);
          $result =  json_decode($result_json, true);
          $status_url = $api_details->api_url."/api/transfers/status/".$order_id."";
          $headers = array('Content-Type:application/json','Authorization:'.$api_token.'');
        //   $result_json=$this->getcurl_with_header($status_url,$headers);
        //   $result =  json_decode($result_json, true);
           
           if ($result['success']) {

            if ($result['data']['status'] == 'PENDING') {

                $break_count = 0;
                while($result['data']['status'] != 'COMPLETED') {
                    sleep(10);
                  if($break_count>1)
                    break;

                  $result_json=$this->getcurl_with_header($status_url,$headers);
                    $result =  json_decode($result_json, true);

                    $break_count++;
                   
                }
            }
          }
          
          //save api log details begin
          $api_info = array(
          'service_id' => $service_id."", 
          'api_id' => $api_details->api_id."", 
          'api_name' => $api_details->api_name."",  
          'api_method' => "doFundTransfer",
          'api_url' => $url."", 
          'order_id' => $order_id."", 
          'user_id' => $user_id."",  
          'request_input' => json_encode($post)."",
          'request' => json_encode($post)."",         
          'response' => $result_json."",
          'access_type' => "APP",
          'updated_on'=>date('Y-m-d H:i:s'),
        );
        $api_log_id = $this->apiLog_model->addApiLogDetails($api_info);
          if($result['success']=="true"){
            $trans_info = array(
            'transaction_id' =>$result['data']['id'],
            'transaction_status' =>$result['data']['status'], 
            'service_id' => $service_id, 
            'operator_id'=>$operator_id,
            'api_id' => $api_details->api_id,
            'trans_date' => date("Y-m-d H:i:s"),
            'order_id' => $this->isValid($order_id),  
            'mobileno' =>$data['sender_mobile_number']."", 
            'user_id' => $this->isValid($user_id),          
            'total_amount' => $this->isValid($amount),
            'charge_amount' =>"0",
            'transaction_type' =>$result['data']['payment_type'], //add
            'bank_transaction_id' =>$result['data']['bank_ref_num'], //add
            'imps_name' =>$receipt_details->recipient_name."", //add
            'recipient_id' =>$receipt_details->recipient_id, //add
            'charges_tax' =>"0", //add
            'commission' =>"0", //add
            'commission_tax' =>"0", //add
            'commission_tds' =>"0", //add
            'debit_amount' =>"0",
            'balance' =>$result['data']['closing_balance'],
            'order_status' => ($result['data']['status'] == 'COMPLETED') ? 'SUCCESS' : $result['data']['status'],
            'transaction_msg'=>$result['message'],
            'CCFcharges'=>$ccf,
            'Cashback'=>$cashback,
            'TDSamount'=>$TDS,
            'PayableCharge'=>$PayableCharge,
            'FinalAmount'=>$totalAmount,
            'request_amount'=>$amount,
            'updated_on'=>date('Y-m-d H:i:s'),
          );
            //print_r($trans_info);
          $txn_id = $this->transactions_model->addNewTransaction($trans_info);
          //update balance based on api id in api setting table developed by susmitha start
          $currentapibal=0;
          $data = array('balance'=>$currentapibal);
          $this->apiLog_model->update_api_amount($data,$api_details->api_id);
          //update balance based on api id in apisetting table developed by susmitha end  
           //update sender avaland used limit start
          $sender_det  =array("sender_mobile_number"=>$mobile);
          $sender_details =$this->moneyTransferApi_model->getdata_where('tbl_sender_dts',$sender_det);
          $available_limit=($sender_details[0]->Upi_available_limit)-($amount);
          $used_limit=($sender_details[0]->Upi_available_limit)+($amount);
          $sender_detupdate=array("Upi_available_limit"=>$available_limit,
                                 "Upi_used_limit"=>$used_limit);
          //print_r($sender_detupdate);
          $this->db->where('sender_mobile_number',$mobile);
          $this->db->update('tbl_sender_dts',$sender_detupdate);
         //update sender avaland used limit end
           $recipent_ver  =array("recipient_id"=>$data['recipient_id'],"is_verified"=>"Y");
         $benificiary_details =$this->moneyTransferApi_model->getdata_where('tbl_dmt_benificiary_dtls',$recipent_ver);
           //print_r($benificiary_details);
          if(empty($benificiary_details)){
            $updatebeni=array("is_verified"=>"Y","verified_name"=>$result['data']['transfer_account_holder']);
          $where=array('recipient_id',$data['recipient_id']); 
          $this->db->where($where);
          $this->db->update('tbl_dmt_benificiary_dtls',$updatebeni);
          }
          $money_re=$this->transactions_model->getmoneyreport($txn_id);
          // print_r($money_re);
          
          //UPDATE DETAILS IN TRANSACTION TABLE AND WALLET TRANSACTION TABLE
          $trans_update_arr = array(
                                      'transaction_status' =>$result['status'],
                                      'bank_transaction_id' =>$result['data']['bank_ref_num'],
                                      'order_status' => $oStatus,
                                    );
                                     
            $update_tras_record = $this->db->where('order_id', $order_id)->update('tbl_transaction_dtls', $trans_update_arr);
            $trans_wallet_update_arr = array(
                                      'transaction_id' =>$result['data']['bank_ref_num'],
                                      'bank_trans_id' =>$result['data']['bank_ref_num']
                                    );
            $update_tras_records = $this->db->where('order_id', $order_id)->update('tbl_wallet_trans_dtls', $trans_wallet_update_arr);
          
          //ENDS HERE
          //send sms 
          $digits = 5;
          $rand=rand(pow(10, $digits-1), pow(10, $digits)-1);
          //echo "$rand and " . ($startDate + $rand);
          $username="anandkaushal.in";
          $password="Budd789";
          $msisdn=$mobile;
          $sid="SRLSHD";
          $msg="Transaction Done TID: ".$txn_id." -  order id ".$money_re->order_no.", Amt : Rs ".$amount." Fees: 1 % Name :".$money_re->name."  A/c : ".$money_re->bank_account_number." IFSC: ".$money_re->ifsc." PAYMAMA - ";
          //$msg="".$rand."%20is%20your%20verification%20code.%20Regards%20Saral%20Shaadi.";
          $url="http://cloud.smsindiahub.in/vendorsms/pushsms.aspx?user=".$username."&password=".$password."&msisdn=".$msisdn."&sid=".$sid."&msg=".$msg."&fl=0&gwid=2";
         $ch = curl_init();  
         curl_setopt($ch,CURLOPT_URL,$url);
         curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
         $output=curl_exec($ch);
         curl_close($ch);
         //send sms 
        //commission wallet txn begin
          if(is_numeric($role_id) && intval($role_id) <= 4){                
            $walletUserID = $user_id;
            $walletRoleID = $role_id;
            $isUserBalanceUpdated = false;
            
            for($i=$walletRoleID;$i>=1;$i--){                
              if($i==3 || $i==4  || $i==7){
                $isUserBalanceUpdated = true;
                $userParentID = $this->rechargeApi_model->getUserParentID($walletUserID);
                if ($isUserBalanceUpdated && $userParentID && ( $userParentID->roleId==3||$userParentID->roleId==4||$userParentID->roleId==7) ) {
                  $walletUserID = $userParentID->userId;
                  $walletRoleID = $userParentID->roleId;
                  $updatedBalance = $userParentID->wallet_balance;
                }
                continue;
              }
              $walletAmt = 0;
              $walletBal = 0;    
              $distds='';             
              $userParentID = $this->rechargeApi_model->getUserParentID($walletUserID);
              if ($isUserBalanceUpdated && $userParentID) {
                $walletUserID = $userParentID->userId;
                $walletRoleID = $userParentID->roleId;
                $updatedBalance = $userParentID->wallet_balance;
              }
              /*echo "Wallet User ID:".$walletUserID." Role ID:".$walletRoleID."<br/>";
              echo "Retailer Commision:".$retailer_commission."Dist.:".$distributor_commission."Admin.:".$admin_commission."<br/>";*/
              // if($walletRoleID == 4){ //Retailer
              //   $walletAmt = $retailer_commission;
              //   $walletBal = $updatedBalance+$retailer_commission;
              // }
              /*else if($walletRoleID == 3){ //FOS
                $walletAmt = $distributor_commission;
                $walletBal = $updatedBalance+$distributor_commission;
              }*/ if($walletRoleID == 2){ //Distributor
                //$walletAmt = $distributor_commission;
                $ds = $distributor_commission;
                  $apptds    =$this->rechargeApi_model->getTDS();
                  $distds=$ds*($apptds->value/100);
                  $walletAmt=$ds-$distds; 
                $walletBal = $updatedBalance+$distributor_commission;
              }else if($walletRoleID == 1){ //Admin
                $walletAmt = $admin_commission;
                $walletBal = $updatedBalance+$admin_commission;
              }
              if(is_numeric($walletAmt) && is_numeric($walletBal)){
                $transType = "CREDIT";
                if($walletAmt < 0){
                  $transType = "DEBIT";
                }
                $wallet_trans_info = array(
                  'service_id' => $service_id,
                  'order_id' => $this->isValid($order_id), 
                  'user_id' => $walletUserID, 
                  'operator_id' => $operator_id,
                  'api_id' => $api_details->api_id,
                  'transaction_status' => $result['status'],
                  'transaction_type' => $transType,
                  'payment_type' => "COMMISSION",
                  'payment_mode' => "COMMISSION FOR UPI, UPI ID ".$benificiary_details->bank_account_number.", AMOUNT ".$walletAmt,
                  'transaction_id' => "",               
                  'trans_date' => date("Y-m-d H:i:s"),  
                  'total_amount' => abs($walletAmt),
                  'charge_amount' => "0.00",
                  'balance' => $walletBal,
                  'TDSamount'=>$distds,
                  'updated_on'=>date('Y-m-d H:i:s'),
                );
                $wallet_txn_id = $this->transactions_model->addNewWalletTransaction($wallet_trans_info);
                //update balance into users table                           
                $updateBalQry = $this->rechargeApi_model->updateUserBalance($walletUserID,$walletBal);
                //update balance after deduction end
              }
              $isUserBalanceUpdated = true;
            }
          }
          //commission wallet txn end
          $response = array(
            'status' => "true",
            'msg' => "Success",
            'result' => $result,
            'money'=>$money_re
          );
        }else{
          $trans_info = array(
            'transaction_id' =>"",
            'transaction_status' =>$result['message'], 
            'service_id' => $service_id, 
            'operator_id'=>$operator_id,
            'api_id' => $api_details->api_id,
            'trans_date' => date("Y-m-d H:i:s"),
            'order_id' => $this->isValid($order_id),  
            'mobileno' => $this->isValid($data['sender_mobile_number']), 
            'user_id' => $this->isValid($user_id),          
            'total_amount' => $amount,
            'charge_amount' =>"",
            'transaction_type' => $data['transaction_type'], //add
            'bank_transaction_id' =>"", //add
            'imps_name' => $receipt_details->recipient_name."", //add
            'recipient_id' =>$receipt_details->recipient_id, //add
            'charges_tax' =>"", //add
            'commission' =>"", //add
            'commission_tax' =>"", //add
            'commission_tds' =>"", //add
            'debit_amount' =>"",
            'balance' => "",
            'order_status' => $this->isValid("FAILED"),
            'transaction_msg'=>$result['reason'],
            'updated_on'=>date('Y-m-d H:i:s'),
            'request_amount'=>$amount,
            
          );
          $txn_id = $this->transactions_model->addNewTransaction($trans_info);
          //update balance after deduction begin
        //$updatedBalance = $wallet_balance-$amount;
          $userbalance = $this->rechargeApi_model->getUserBalance($data['user_id']);
          $wallet_balance=$userbalance->wallet_balance;
          $updatedBalance = $wallet_balance+$totalAmount; 
          //insert DEBIT txn into tbl_wallet_trans_dtls table
          $wallet_trans_info = array(
            'service_id' =>$service_id,
            'order_id' => $this->isValid($order_id), 
            'user_id' => $user_id, 
            'operator_id' => $operator_id,
            'api_id' => $api_details->api_id,
            'transaction_status'=>"Success",
            'transaction_type'=>"CREDIT",
            'payment_type' => "SERVICE",
            'payment_mode' => "PAID FOR UPI, UPI ID ".$receipt_details->bank_account_number.", AMOUNT ".$totalAmount.", CHARGE ".$PayableCharge,
            'transaction_id' =>"",               
            'trans_date' => date("Y-m-d H:i:s"),  
            'total_amount' => $totalAmount,
            'charge_amount' => "0.00",
            'balance' => $updatedBalance,
            'updated_on'=>date('Y-m-d H:i:s'),
          );
          $wallet_txn_id = $this->transactions_model->addNewWalletTransaction($wallet_trans_info);
          //update balance into users table                           
          $updateBalQry = $this->rechargeApi_model->updateUserBalance($user_id,$updatedBalance);
          //update balance after deduction end
          $response = array(
            'status' => "false",
            'msg' => $result['message'],
            'result' =>$result
          );        
        }
         
      }
      elseif($api_details->api_id == "14") { //razorpay
      	//echo $api_details->api_id;
        $url = $api_details->api_url;
        $mobile=$data['sender_mobile_number'];
        //get receipt account number ifsc from receipt table based on receipt id
          $receipt_details=$this->moneyTransferApi_model->get_singlebyid('tbl_dmt_benificiary_dtls','recipient_id',$data['recipient_id']);
          if(empty($receipt_details)){
              $response = array(
                  'status' => "false",
                  'msg' => "Recipient Id not there check receipt id!! try again.",
                  'result' => null
              );
              echo json_encode($response);
              exit;
          }
         //check avalibe limit in sender table start
        $senderwhere  =array("sender_mobile_number"=>$mobile,
                             "api_name"=>"paytm");
        $sender_avlcheck =$this->moneyTransferApi_model->getdata_where('tbl_sender_dts',$senderwhere);
        $senamount=$sender_avlcheck[0]->available_limit;
        if($amount>$senamount){
           $response = array(
          
          'status' => "false",
          'msg' => "Limit Exceed",
          'result' =>"",
        );
        echo json_encode($response);
        exit;
        }//check avalibe limit in sender table end
        $commissionDtl = $this->rechargeApi_model->getcommission($service_id,$user_package_id,$operator_id,$amount);
        $ccf=$commissionDtl->ccf_commission;
        // $charge = $commissionDtl->retailer_commission;
        if($commissionDtl->retailer_commission_type == 'Percent'){
          $charge = $amount * ($commissionDtl->retailer_commission/100);
        }elseif($commissionDtl->retailer_commission_type == 'Rupees'){
          $charge = $commissionDtl->retailer_commission;
        }
        $cashback=$ccf-$charge;
        $app = $this->rechargeApi_model->getTDS();
        $TDS = $cashback*($app->value/100);
        $PayableCharge = $charge+$TDS;
        $totalAmount=$amount + $PayableCharge;
        $updatedBalance = $wallet_balance-$totalAmount; //update balance after deduction begin
        //insert DEBIT txn into tbl_wallet_trans_dtls table
          $wallet_trans_info = array(
            'service_id' =>$service_id,
            'order_id' => $this->isValid($order_id), 
            'user_id' => $user_id, 
            'operator_id' => $operator_id,
            'api_id' => $api_details->api_id,
            'transaction_status' =>'Success',
            'transaction_type' => "DEBIT",
            'payment_type' => "SERVICE",
            'payment_mode' => "PAID FOR UPI, UPI ID ".$api_details->account_no.", AMOUNT ".$totalAmount.", CHARGE ".$PayableCharge,
            'transaction_id' =>'',               
            'trans_date' => date("Y-m-d H:i:s"),  
            'total_amount' => $totalAmount,
            'charge_amount' => "0.00",
            'balance' => $updatedBalance,
            'CCFcharges'=>$ccf,
            'Cashback'=>$cashback,
            'TDSamount'=>$TDS,
            'PayableCharge'=>$PayableCharge,
            'FinalAmount'=>$totalAmount,
            'updated_on'=>date('Y-m-d H:i:s'),
          );
          $wallet_txn_id = $this->transactions_model->addNewWalletTransaction($wallet_trans_info);
          //update balance into users table                           
          $updateBalQry = $this->rechargeApi_model->updateUserBalance($user_id,$updatedBalance);
          //update balance after deduction end
          
          $trans_info = array(
            'transaction_id' =>"0",
            'transaction_status' =>"PENDING", 
            'service_id' => $service_id, 
            'operator_id'=>$operator_id,
            'api_id' => $api_details->api_id,
            'trans_date' => date("Y-m-d H:i:s"),
            'order_id' => $this->isValid($order_id),  
            'mobileno' =>$data['sender_mobile_number']."", 
            'user_id' => $this->isValid($user_id),          
            'total_amount' => $this->isValid($amount),
            'charge_amount' =>"0",
            'transaction_type' => $data['transaction_type'], //add
            // 'bank_transaction_id' =>$result['result']['paytmOrderId'], //add
            'bank_transaction_id' =>"", //add
            'imps_name' =>$receipt_details->recipient_name."", //add
            'recipient_id' =>$data['recipient_id'], //add
            'charges_tax' =>"0", //add
            'commission' =>"0", //add
            'commission_tax' =>"0", //add
            'commission_tds' =>"0", //add
            'debit_amount' =>"0",
            'balance' =>"0",
            'order_status' => "PENDING",
            'transaction_msg'=>"",
            'CCFcharges'=>$ccf,
            'Cashback'=>$cashback,
            'TDSamount'=>$TDS,
            'PayableCharge'=>$PayableCharge,
            'FinalAmount'=>$totalAmount,
            'request_amount'=>$amount,
            'updated_on'=>date('Y-m-d H:i:s'),
            );
            $txn_id = $this->transactions_model->addNewTransaction($trans_info);

          $paisa = $amount * 100;
          $post_req = '{
                        "account_number": "'.$api_details->account_no.'",
                        "fund_account_id": "'.$receipt_details->razorpay_fund_acc_id.'",
                        "amount": '.$paisa.',
                        "currency": "INR",
                        "mode": "'. $data['transaction_type'].'",
                        "purpose": "payout",
                        "queue_if_low_balance": true,
                        "reference_id": "'.$order_id.'"
                      }';
          $api_info = array(
              'service_id' => $service_id."", 
              'api_id' => $api_details->api_id."", 
              'api_name' => $api_details->api_name."",  
              'api_method' => "doFundTransfer",
              'api_url' => $api_details->api_url."payouts", 
              'order_id' => $order_id."", 
              'user_id' => $user_id."",  
              'request_input' => json_encode($post_req)."",
              'request' => json_encode($post_req)."",         
            //   'response' => json_encode($result)."",
              'access_type' => (isset($data['access_type'])) ? $data['access_type'] : 'DEFAULT' ,
              'updated_on'=>date('Y-m-d H:i:s'),
         );
          $api_log_id = $this->apiLog_model->addApiLogDetails($api_info);
          $result = $this->payout_Rezorpay($post_req,$api_details);
          $update_api_dtls_arr =  array('response' => json_encode($result), 'updated_on'=>date('Y-m-d H:i:s') );
          $update_api_log = $this->db->where('order_id', $order_id)->update('tbl_apilog_dts', $update_api_dtls_arr );
          
          $pending_arr = array('queued', 'pending', 'processing');
          $success_arr = array('processed');
          $failed_arr = array('rejected', 'cancelled');
          $oStatus = '';
          if (in_array($result['status'], $pending_arr)){
            $oStatus = 'PENDING';
          }elseif (in_array($result['status'], $success_arr)) {
            $oStatus = 'SUCCESS';
          }elseif ($result['status'] == 'reversed') {
            $oStatus = 'REFUNDED';
          }elseif (in_array($result['status'], $failed_arr)) {
            $oStatus = 'FAILED';
          }
           //Temporary refund Status
           //$oStatus = 'REFUNDED';
           if($result['id'] && ((in_array($result['status'], $pending_arr)) || (in_array($result['status'], $success_arr)))) {
                //update balance based on api id in api setting table developed by susmitha start

              $trans_record = [];
              $oStatus = $result['status'];
              while ($oStatus=='PENDING') {
                  $trans_record = $this->db->select('*')->from('tbl_transaction_dtls')->where('order_id', $order_id)->get()->row();
                  // $trans_record =$this->moneyTransferApi_model->getdata_where('tbl_transaction_dtls',array('order_id'=> $order_id ));
                  if ($trans_record->order_status != 'PENDING' ) {
                      // $oStatus = $trans_record[0]->order_status;
                      $oStatus = $trans_record->order_status;
                      break;
                  }
              }

              if ( $oStatus == 'FAILED') {
                  $failed_resp = $this->failedPaytmTransfer($trans_info, $trans_record->order_status );
                  $response = array(
                    'status' => "false",
                    'msg' => 'failed',
                    'result' => $trans_record
                  );   
                  echo json_encode($response) ;

                  exit;
              }

              $currentapibal=0;
              $data = array('balance'=>$currentapibal);
              $this->apiLog_model->update_api_amount($data,$api_details->api_id);
              //update balance based on api id in apisetting table developed by susmitha end  
              //update sender avaland used limit start
              $sender_det  =array("sender_mobile_number"=>$mobile);
              $sender_details =$this->moneyTransferApi_model->getdata_where('tbl_sender_dts',$sender_det);
              $available_limit=($sender_details[0]->Upi_available_limit)-($amount);
              $used_limit=($sender_details[0]->Upi_used_limit)+($amount);
              $sender_detupdate=array("Upi_available_limit"=>$available_limit,
                                    "Upi_used_limit"=>$used_limit);
              //print_r($sender_detupdate);
              $this->db->where('sender_mobile_number',$mobile);
              $this->db->update('tbl_sender_dts',$sender_detupdate);
            //update sender avaland used limit end
              $recipent_ver  =array("recipient_id"=>$data['recipient_id'],"is_verified"=>"Y");
              $benificiary_details =$this->moneyTransferApi_model->getdata_where('tbl_dmt_benificiary_dtls',$recipent_ver);
              //print_r($benificiary_details);
              if(empty($benificiary_details)){
                $updatebeni=array("is_verified"=>"Y","verified_name"=>$result['result']['beneficiaryName']);
              $where=array('recipient_id',$data['recipient_id']); 
              $this->db->where($where);
              $this->db->update('tbl_dmt_benificiary_dtls',$updatebeni);
              }
              
              $money_re=$this->transactions_model->getmoneyreport($txn_id);
              // print_r($money_re);
              

              //send sms 
              $digits = 5;
              $rand=rand(pow(10, $digits-1), pow(10, $digits)-1);
              //echo "$rand and " . ($startDate + $rand);
              $username="anandkaushal.in";
              $password="Budd789";
              $msisdn=$mobile;
              $sid="SRLSHD";
            //by vishal
              // $msg="Transaction Done TID: ".$txn_id." -  order id ".$money_re->order_no.", Amt : Rs ".$amount." Fees: 1 % Name :".$money_re->name."  A/c : ".$money_re->bank_account_number." IFSC: ".$money_re->ifsc." PAYMAMA - ";
              //$msg="".$rand."%20is%20your%20verification%20code.%20Regards%20Saral%20Shaadi.";
              $url="http://cloud.smsindiahub.in/vendorsms/pushsms.aspx?user=".$username."&password=".$password."&msisdn=".$msisdn."&sid=".$sid."&msg=".$msg."&fl=0&gwid=2";
            //  $ch = curl_init();  
            //  curl_setopt($ch,CURLOPT_URL,$url);
            //  curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
            //  $output=curl_exec($ch);
            //  curl_close($ch);
            $msg="TID: ".$order_id." Amt : ".$amount." Fees: 1 %, Name : ".$money_re->name." UPI : ".$benificiary_details->bank_account_number." Mobile ".$benificiary_details->recipient_mobile_number." www.paymamaapp.in PAYMAMA";
              $temp_id = "1007821138683202361"; 
            $sms_output = $this->sendBulkSMS($msisdn, $msg, $temp_id); 
            //by vishal End
            //send sms 
            //commission wallet txn begin
              if(is_numeric($role_id) && intval($role_id) <= 4){                
                $walletUserID = $user_id;
                $walletRoleID = $role_id;
                $isUserBalanceUpdated = false;
                
                for($i=$walletRoleID;$i>=1;$i--){
                  if($i==3 || $i==4  || $i==7){
                    $isUserBalanceUpdated = true;
                    $userParentID = $this->rechargeApi_model->getUserParentID($walletUserID);
                    if ($isUserBalanceUpdated && $userParentID && ( $userParentID->roleId==3||$userParentID->roleId==4||$userParentID->roleId==7) ) {
                      $walletUserID = $userParentID->userId;
                      $walletRoleID = $userParentID->roleId;
                      $updatedBalance = $userParentID->wallet_balance;
                    }
                    continue;
                  }
                  $walletAmt = 0;
                  $walletBal = 0;    
                  $distds='';             
                  $userParentID = $this->rechargeApi_model->getUserParentID($walletUserID);
                  if ($isUserBalanceUpdated && $userParentID) {
                    $walletUserID = $userParentID->userId;
                    $walletRoleID = $userParentID->roleId;
                    $updatedBalance = $userParentID->wallet_balance;
                  }
                  /*echo "Wallet User ID:".$walletUserID." Role ID:".$walletRoleID."<br/>";
                  echo "Retailer Commision:".$retailer_commission."Dist.:".$distributor_commission."Admin.:".$admin_commission."<br/>";*/
                  // if($walletRoleID == 4){ //Retailer
                  //   $walletAmt = $retailer_commission;
                  //   $walletBal = $updatedBalance+$retailer_commission;
                  // }
                  /*else if($walletRoleID == 3){ //FOS
                    $walletAmt = $distributor_commission;
                    $walletBal = $updatedBalance+$distributor_commission;
                  }*/ if($walletRoleID == 2){ //Distributor
                    //$walletAmt = $distributor_commission;
                    $ds = $distributor_commission;
                      $apptds    =$this->rechargeApi_model->getTDS();
                      $distds=$ds*($apptds->value/100);
                      $walletAmt=$ds-$distds; 
                    $walletBal = $updatedBalance+$distributor_commission;
                  }else if($walletRoleID == 1){ //Admin
                    $walletAmt = $admin_commission;
                    $walletBal = $updatedBalance+$admin_commission;
                  }
                  if(is_numeric($walletAmt) && is_numeric($walletBal)){
                    $transType = "CREDIT";
                    if($walletAmt < 0){
                      $transType = "DEBIT";
                    }
                    $wallet_trans_info = array(
                      'service_id' => $service_id,
                      'order_id' => $this->isValid($order_id), 
                      'user_id' => $walletUserID, 
                      'operator_id' => $operator_id,
                      'api_id' => $api_details->api_id,
                      'transaction_status' => $result['status'],
                      'transaction_type' => $transType,
                      'payment_type' => "COMMISSION",
                      'payment_mode' => "Commission by Money Transfer",
                      'transaction_id' => "",               
                      'trans_date' => date("Y-m-d H:i:s"),  
                      'total_amount' => abs($walletAmt),
                      'charge_amount' => "0.00",
                      'balance' => $walletBal,
                      'TDSamount'=>$distds,
                      'updated_on'=>date('Y-m-d H:i:s'),
                    );
                    $wallet_txn_id = $this->transactions_model->addNewWalletTransaction($wallet_trans_info);
                    //update balance into users table                           
                    $updateBalQry = $this->rechargeApi_model->updateUserBalance($walletUserID,$walletBal);
                    //update balance after deduction end
                  }
                  $isUserBalanceUpdated = true;
                }
              }
              //commission wallet txn end
              $response = array(
                'status' => "true",
                'msg' => "Success",
                'result' => $result,
                'money'=>$money_re
              );
          } else{
          $trans_info = array(
            'transaction_id' =>"",
            'transaction_status' =>$result['status'], 
            'service_id' => $service_id, 
            'operator_id'=>$operator_id,
            'api_id' => $api_details->api_id,
            'trans_date' => date("Y-m-d H:i:s"),
            'order_id' => $this->isValid($order_id),  
            'mobileno' => $this->isValid($data['sender_mobile_number']), 
            'user_id' => $this->isValid($user_id),          
            'total_amount' => $amount,
            'charge_amount' =>"",
            'transaction_type' => $data['transaction_type'], //add
            'bank_transaction_id' =>"", //add
            'imps_name' => $receipt_details->recipient_name."", //add
            'recipient_id' =>$receipt_details->recipient_id, //add
            'charges_tax' =>"", //add
            'commission' =>"", //add
            'commission_tax' =>"", //add
            'commission_tds' =>"", //add
            'debit_amount' =>"",
            'balance' => "",
            'order_status' => $this->isValid("FAILED"),
            'transaction_msg'=>$result['statusMessage'],
            'updated_on'=>date('Y-m-d H:i:s'),
            'request_amount'=>$amount,
          );
          $txn_id = $this->transactions_model->addNewTransaction($trans_info);
          $userbalance = $this->rechargeApi_model->getUserBalance($data['user_id']);
          $wallet_balance=$userbalance->wallet_balance;
          $updatedBalance = $wallet_balance+$totalAmount; 
          //insert DEBIT txn into tbl_wallet_trans_dtls table
          $wallet_trans_info = array(
            'service_id' =>$service_id,
            'order_id' => $this->isValid($order_id), 
            'user_id' => $user_id, 
            'operator_id' => $operator_id,
            'api_id' => $api_details->api_id,
            'transaction_status'=>$result['status'],
            'transaction_type'=>"CREDIT",
            'payment_type' => "SERVICE",
            'payment_mode' => "PAID FOR UPI, UPI ID ".$benificiary_details->bank_account_number.", AMOUNT ".$totalAmount.", CHARGE ".$PayableCharge,
            'transaction_id' =>"",               
            'trans_date' => date("Y-m-d H:i:s"),  
            'total_amount' => $totalAmount,
            'charge_amount' => "0.00",
            'balance' => $updatedBalance,
            'updated_on'=>date('Y-m-d H:i:s'),
          );
          $wallet_txn_id = $this->transactions_model->addNewWalletTransaction($wallet_trans_info);
          //update balance into users table                           
          $updateBalQry = $this->rechargeApi_model->updateUserBalance($user_id,$updatedBalance);
          //update balance after deduction end
          $response = array(
            'status' => "false",
            'msg' => $result['statusMessage'],
            'result' => $result
          );        
        }
         
      }
      
      elseif($api_details->api_id == "22") {
          
                    //echo "hj";
         $mobile=$data['sender_mobile_number'];
        //get receipt account number ifsc from receipt table based on receipt id
          $receipt_details=$this->moneyTransferApi_model->get_singlebyid('tbl_dmt_benificiary_dtls','recipient_id',$data['recipient_id']);
          if(empty($receipt_details)){
           $response = array(
          'status' => "false",
          'msg' => "Recipient Id not there check receipt id!! try again.",
          'result' => null
              );
          echo json_encode($response);
        exit;
          }
        //    //check avalibe limit in sender table start
        // $senderwhere  =array("sender_mobile_number"=>$data['sender_mobile_number'],
        //                      "available_limit >="=>$amount);
        // //print_r($senderwhere);
        // $sender_avlcheck =$this->moneyTransferApi_model->getdata_where('tbl_sender_dts',$senderwhere);
        // if(empty($sender_avlcheck)){
        //    $response = array(
          
        //   'status' => "false",
        //   'msg' => "Limit Exceed",
        //   'result' =>"",
        // );
        // echo json_encode($response);
        // exit;
        // }
        // //check avalibe limit in sender table end
          //check avalibe limit in sender table start
        $senderwhere  =array("sender_mobile_number"=>$mobile,
                             "api_name"=>"paytm");
        //print_r($senderwhere);

        $sender_avlcheck =$this->moneyTransferApi_model->getdata_where('tbl_sender_dts',$senderwhere);
        $senamount=$sender_avlcheck[0]->available_limit;
        
        
        if($amount>$senamount){
           $response = array(
          
          'status' => "false",
          'msg' => "Limit Exceed",
          'result' =>"",
        );
        echo json_encode($response);
        exit;
        }//check avalibe limit in sender table end
         //susmitha commision cal
        $commissionDtl = $this->rechargeApi_model->getcommission($service_id,$user_package_id,$operator_id,$amount);
        $ccf=$commissionDtl->ccf_commission;
        
        if($commissionDtl->retailer_commission_type == 'Percent'){
          $charge = $amount * ($commissionDtl->retailer_commission/100);
        }elseif($commissionDtl->retailer_commission_type == 'Rupees'){
          $charge = $commissionDtl->retailer_commission;
        }
        // $charge = $commissionDtl->retailer_commission;
        $cashback=$ccf-$charge;
        $app    =$this->rechargeApi_model->getTDS();
        $TDS     =$cashback*($app->value/100);
        $PayableCharge = $charge+$TDS;
        $totalAmount=$amount+$PayableCharge;
        $updatedBalance = $wallet_balance-$totalAmount; //update balance after deduction begin
        //insert DEBIT txn into tbl_wallet_trans_dtls table
          $wallet_trans_info = array(
            'service_id' =>$service_id,
            'order_id' => $order_id, 
            'user_id' => $user_id, 
            'operator_id' => $operator_id,
            'api_id' => $api_details->api_id,
            'transaction_status' =>'Success',
            'transaction_type' => "DEBIT",
            'payment_type' => "SERVICE",
            'payment_mode' => "PAID FOR DMT, ACCOUNT NUMBER ".$receipt_details->bank_account_number.", AMOUNT ".$totalAmount.", CHARGE ".$PayableCharge,
            'transaction_id' =>'',               
            'trans_date' => date("Y-m-d H:i:s"),  
            'total_amount' => $totalAmount,
            'charge_amount' => "0.00",
            'balance' => $updatedBalance,
            'CCFcharges'=>$ccf,
            'Cashback'=>$cashback,
            'TDSamount'=>$TDS,
            'PayableCharge'=>$PayableCharge,
            'FinalAmount'=>$totalAmount,
            'updated_on'=>date('Y-m-d H:i:s'),
          );
          $wallet_txn_id = $this->transactions_model->addNewWalletTransaction($wallet_trans_info);
          //update balance into users table                           
          $updateBalQry = $this->rechargeApi_model->updateUserBalance($user_id,$updatedBalance);
          //update balance after deduction end
        
           


          //echo "<pre>";
          $paytmParams = array();

          $paytmParams["subwalletGuid"]      = $api_details->api_token;
          $paytmParams["orderId"]            = $order_id;
          $paytmParams["beneficiaryAccount"] = $receipt_details->bank_account_number;
          $paytmParams["beneficiaryIFSC"]    = $receipt_details->ifsc;
          $paytmParams["amount"]             = $amount;
          $paytmParams["purpose"]            = "OTHERS";
          $paytmParams["date"]               = date('Y-m-d');
          $paytmParams["transferMode"]       = $data['transaction_type'];
          $paytmParams["callbackUrl"]        = "http://paymamaapp.in/admin/index.php/Transactions/paytm_moneytrans_status/".$order_id;
          $post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);
          // echo "<pre>";
          //  print_r($post_data);

          //save api log details begin
          $api_info = array(
            'service_id' => $service_id."", 
            'api_id' => $api_details->api_id."", 
            'api_name' => $api_details->api_name."",  
            'api_method' => "doFundTransfer",
            'api_url' => $url."", 
            'order_id' => $order_id."", 
            'user_id' => $user_id."",  
            'request_input' => json_encode($post_data)."",
            'request' => json_encode($post_data)."",         
            'response' =>"",
            'access_type' => "APP",
            'updated_on'=>date('Y-m-d H:i:s'),
          );
          $api_log_id = $this->apiLog_model->addApiLogDetails($api_info);
          //save api log details end

          //insert trasaction table
          $trans_info = array(
            'transaction_id' =>"0",
            'transaction_status' =>'PENDING', 
            'service_id' => $service_id, 
            'operator_id'=>$operator_id,
            'api_id' => $api_details->api_id,
            'trans_date' => date("Y-m-d H:i:s"),
            'order_id' => $this->isValid($order_id),  
            'mobileno' =>$data['sender_mobile_number']."", 
            'user_id' => $this->isValid($user_id),          
            'total_amount' => $this->isValid($amount),
            'charge_amount' =>"0",
            'transaction_type' =>$data['transaction_type'], //add
            // 'bank_transaction_id' =>$result['result']['paytmOrderId'], //add  MYTEST
            'bank_transaction_id' =>$bnk, //add  MYTEST
            'imps_name' =>$receipt_details->recipient_name."", //add
            'recipient_id' =>$data['recipient_id'], //add
            'charges_tax' =>"0", //add
            'commission' =>"0", //add
            'commission_tax' =>"0", //add
            'commission_tds' =>"0", //add
            'debit_amount' =>"0",
            'balance' =>"0",
            'order_status' => 'PENDING',
            'transaction_msg'=>'',
            'CCFcharges'=>$ccf,
            'Cashback'=>$cashback,
            'TDSamount'=>$TDS,
            'PayableCharge'=>$PayableCharge,
            'FinalAmount'=>$totalAmount,
            'request_amount'=>$amount,
            'updated_on'=>date('Y-m-d H:i:s'),
          );
          //print_r($trans_info);
          $txn_id = $this->transactions_model->addNewTransaction($trans_info);
          
          //Cashfree payment gateway begins
             //From Here Cash Free Integration Starts
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
        
       
          
          //Transfer Amount
          $receipt_details=$this->moneyTransferApi_model->get_singlebyid('tbl_dmt_benificiary_dtls','recipient_id',$data['recipient_id']);
          $beneid=str_replace('@','',$receipt_details->cfree_beneficiaryid);
          $Params["beneId"]= $beneid;
          $Params["transferId"]= $order_id;
          $Params["amount"] = $data['transaction_amount'];
          $Params["transferMode"] = "upi";
          
        
          $post_data = json_encode($Params, JSON_UNESCAPED_SLASHES);
          
          $url="https://payout-api.cashfree.com/payout/v1.2/requestTransfer";
          $ch = curl_init($url);
          curl_setopt($ch, CURLOPT_POST, true);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
          curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization:Bearer " . $token)); 
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
          $result_json = curl_exec($ch);
          
          curl_close($ch);
          $result =  json_decode($result_json, true); 
          
            $update_api_dtls_arr =  array('response' => json_encode($result), 'updated_on'=>date('Y-m-d H:i:s') );
          $update_api_log = $this->db->where('order_id', $order_id)->update('tbl_apilog_dts', $update_api_dtls_arr );
          //print_r($result);
           //Temporary Refund Status
           //$result['status']='CANCEL';
          if($result['status']=="SUCCESS" || $result['status']=="PENDING"){
            //TRANSACTION TABLE UPDATE AUTOMATICALLY WHEN WEBHOOK CALLED
            

            // $trans_record = $this->db->select('*')->from('tbl_transaction_dtls')->where('order_id', $order_id)->get()->row();
           

            $trans_record = [];
            $oStatus = $result['status'];
            
            // while ($oStatus=='PENDING') {
            //     $trans_record = $this->db->select('*')->from('tbl_transaction_dtls')->where('order_id', $order_id)->get()->row();
            //     // $trans_record =$this->moneyTransferApi_model->getdata_where('tbl_transaction_dtls',array('order_id'=> $order_id ));
            //     if ($trans_record->order_status != 'PENDING' ) {
            //         // $oStatus = $trans_record[0]->order_status;
            //         $oStatus = $trans_record->order_status;
            //         break;
            //     }
            // }
           
            if ( $oStatus == 'FAILED') {
                // $failed_resp = $this->failedPaytmTransfer($trans_info, $trans_record->order_status );
                $response = array(
                  'status' => "false",
                  'msg' => 'failed',
                  'result' => $trans_record
                );   
                echo json_encode($response) ;

                exit;
            }
            
            $trans_update_arr = array(
                                      'transaction_status' =>$result['status'],
                                      'bank_transaction_id' =>$result['data']['utr'],
                                      'order_status' => $oStatus,
                                    );
                                     
            $update_tras_record = $this->db->where('order_id', $order_id)->update('tbl_transaction_dtls', $trans_update_arr);
            $trans_wallet_update_arr = array(
                                      'transaction_id' =>$result['data']['utr'],
                                      'bank_trans_id' =>$result['data']['utr']
                                    );
            $update_tras_records = $this->db->where('order_id', $order_id)->update('tbl_wallet_trans_dtls', $trans_wallet_update_arr);
            
            //update balance based on api id in api setting table developed by susmitha start
            $currentapibal=0;
            $data = array('balance'=>$currentapibal);
            $this->apiLog_model->update_api_amount($data,$api_details->api_id);
            //update balance based on api id in apisetting table developed by susmitha end  
            //update sender avaland used limit start
            $sender_det  =array("sender_mobile_number"=>$mobile);
            $sender_details =$this->moneyTransferApi_model->getdata_where('tbl_sender_dts',$sender_det);
            $available_limit=($sender_details[0]->available_limit)-($amount);
            $used_limit=($sender_details[0]->used_limit)+($amount);
            $sender_detupdate=array("available_limit"=>$available_limit,
                                  "used_limit"=>$used_limit);
            //print_r($sender_detupdate);
            $this->db->where('sender_mobile_number',$mobile);
            $this->db->update('tbl_sender_dts',$sender_detupdate);
            //update sender avaland used limit end
          $recipent_ver  =array("recipient_id"=>$recid,"is_verified"=>"Y");
            $benificiary_details =$this->moneyTransferApi_model->getdata_where('tbl_dmt_benificiary_dtls',$recipent_ver);
            
            // if(empty($benificiary_details)){
            //   $updatebeni=array("is_verified"=>"Y","verified_name"=>$receipt_details->recipient_name);
            // $where=array('recipient_id',$data['recipient_id']); 
            // $this->db->where($where);
            // $this->db->update('tbl_dmt_benificiary_dtls',$updatebeni);
            // }
            $money_re=$this->transactions_model->getmoneyreport($txn_id);
            
            // print_r($money_re);
           
            //send sms 
            $digits = 5;
            $rand=rand(pow(10, $digits-1), pow(10, $digits)-1);
            //echo "$rand and " . ($startDate + $rand);
            $username="NAIDUSOFTWARE";
            $password="4049102";
            
            $msisdn=$sender_mobile_number;
            $sid="PYMAMA";
            
            // $msg="Transaction Done TID: ".$txn_id." -  order id ".$money_re->order_no.", Amt : Rs ".$amount." Fees: 1 % Name :".$money_re->name."  A/c : ".$money_re->bank_account_number." IFSC: ".$money_re->ifsc." SMARTPAY - ";
              // $msg="TID: ".$money_re->order_no." Amt : Rs ".$amount." Fees: 1 %, Name : ".$money_re->name." A/c : ".$money_re->bank_account_number." Mobile ".$benificiary_details->recipient_mobile_number." IFSC: ".$money_re->ifsc." www.paymamaapp.in";
              $msg="TID : ".$order_id." Amt : ".$amount." Fees: 1 %, Name: ".$benificiary_details[0]->verified_name." A/c: ".$benificiary_details[0]->bank_account_number." Mobile ".$benificiary_details[0]->recipient_mobile_number." Bank ".$benificiary_details[0]->bank_name." IFSC: ".$benificiary_details[0]->ifsc." www.paymamaapp.in";
              //TID : {#var#} Amt : {#var#} Fees: 1 %, Name : {#var#} A/c : {#var#} Mobile {#var#} Bank {#var#} IFSC: {#var#} www.paymamaapp.in
              //$msg="".$rand."%20is%20your%20verification%20code.%20Regards%20Saral%20Shaadi.";
            $tem_sms_row= $this->db->select('*')->from('tbl_sms_templates')->where('alias', 'money_transfer')->get()->row();
            
            //comment by vishal
            //   $url="http://cloud.smsindiahub.in/vendorsms/pushsms.aspx?user=".$username."&password=".$password."&msisdn=".$msisdn."&sid=".$sid."&msg=".$msg."&fl=0&gwid=2";
            //  $ch = curl_init();  
            //  curl_setopt($ch,CURLOPT_URL,$url);
            //  curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
            //  $output=curl_exec($ch);
            //  curl_close($ch);
            $temp_id=1207163818494893617;    //comment by vishal
            //   $url="http://cloud.smsindiahub.in/vendorsms/pushsms.aspx?user=".$username."&password=".$password."&msisdn=".$msisdn."&sid=".$sid."&msg=".$msg."&fl=0&gwid=2";
            //  $ch = curl_init();  
            //  curl_setopt($ch,CURLOPT_URL,$url);
            //  curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
            //  $output=curl_exec($ch);
            //  curl_close($ch);
              $sms_output = $this->sendBulkSMS($msisdn, $msg, $template_id);
            //Vishal End

            //send sms 
            //commission wallet txn begin
            if(is_numeric($role_id) && intval($role_id) <= 4){                
              $walletUserID = $user_id;
              $walletRoleID = $role_id;
              $isUserBalanceUpdated = false;
              
              for($i=$walletRoleID;$i>=1;$i--){                
                if($i==3 || $i==4  || $i==7){
                  $isUserBalanceUpdated = true;
                  $userParentID = $this->rechargeApi_model->getUserParentID($walletUserID);
                  if ($isUserBalanceUpdated && $userParentID && ( $userParentID->roleId==3||$userParentID->roleId==4||$userParentID->roleId==7) ) {
                    $walletUserID = $userParentID->userId;
                    $walletRoleID = $userParentID->roleId;
                    $updatedBalance = $userParentID->wallet_balance;
                  }
                  continue;
                }
                $walletAmt = 0;
                $walletBal = 0;    
                $distds='';             
                $userParentID = $this->rechargeApi_model->getUserParentID($walletUserID);
                if ($isUserBalanceUpdated && $userParentID) {
                  $walletUserID = $userParentID->userId;
                  $walletRoleID = $userParentID->roleId;
                  $updatedBalance = $userParentID->wallet_balance;
                }
                /*echo "Wallet User ID:".$walletUserID." Role ID:".$walletRoleID."<br/>";
                echo "Retailer Commision:".$retailer_commission."Dist.:".$distributor_commission."Admin.:".$admin_commission."<br/>";*/
                // if($walletRoleID == 4){ //Retailer
                //   $walletAmt = $retailer_commission;
                //   $walletBal = $updatedBalance+$retailer_commission;
                // }
                /*else if($walletRoleID == 3){ //FOS
                  $walletAmt = $distributor_commission;
                  $walletBal = $updatedBalance+$distributor_commission;
                }*/ if($walletRoleID == 2){ //Distributor
                  //$walletAmt = $distributor_commission;
                  $ds = $distributor_commission;
                    $apptds    =$this->rechargeApi_model->getTDS();
                    $distds=$ds*($apptds->value/100);
                    $walletAmt=$ds-$distds; 
                  $walletBal = $updatedBalance+$distributor_commission;
                }else if($walletRoleID == 1){ //Admin
                  $walletAmt = $admin_commission;
                  $walletBal = $updatedBalance+$admin_commission;
                }
                if(is_numeric($walletAmt) && is_numeric($walletBal)){
                  $transType = "CREDIT";
                  if($walletAmt < 0){
                    $transType = "DEBIT";
                  }
                  $wallet_trans_info = array(
                    'service_id' => $service_id,
                    'order_id' => $this->isValid($order_id), 
                    'user_id' => $walletUserID, 
                    'operator_id' => $operator_id,
                    'api_id' => $api_details->api_id,
                    'transaction_status' => $result['status'],
                    'transaction_type' => $transType,
                    'payment_type' => "COMMISSION",
                    'payment_mode' => "Commission by Money Transfer",//
                    'transaction_id' => "",               
                    'trans_date' => date("Y-m-d H:i:s"),  
                    'total_amount' => abs($walletAmt),
                    'charge_amount' => "0.00",
                    'balance' => $walletBal,
                    'TDSamount'=>$distds,
                    'updated_on'=>date('Y-m-d H:i:s'),
                  );
                  $wallet_txn_id = $this->transactions_model->addNewWalletTransaction($wallet_trans_info);
                  //update balance into users table                           
                  $updateBalQry = $this->rechargeApi_model->updateUserBalance($walletUserID,$walletBal);
                  //update balance after deduction end
                }
                $isUserBalanceUpdated = true;
              }
            }
          //commission wallet txn end
           $response = array(
              'status' => "true",
              'msg' => "Success",
              'result' => $result,
              'money'=>$money_re
            );
         
          }else{
            //TRANSACTION UPDATED AFTER API CALL

            // $trans_info = array(
            //   'transaction_id' =>"",
            //   'transaction_status' =>$result['status'], 
            //   'service_id' => $service_id, 
            //   'operator_id'=>$operator_id,
            //   'api_id' => $api_details->api_id,
            //   'trans_date' => date("Y-m-d H:i:s"),
            //   'order_id' => $order_id,  
            //   'mobileno' => $this->isValid($data['sender_mobile_number']), 
            //   'user_id' => $this->isValid($user_id),          
            //   'total_amount' => $amount,
            //   'charge_amount' =>"",
            //   'transaction_type' => $data['transaction_type'], //add
            //   'bank_transaction_id' =>"", //add
            //   'imps_name' => $receipt_details->recipient_name."", //add
            //   'recipient_id' =>$receipt_details->recipient_id, //add
            //   'charges_tax' =>"", //add
            //   'commission' =>"", //add
            //   'commission_tax' =>"", //add
            //   'commission_tds' =>"", //add
            //   'debit_amount' =>"",
            //   'balance' => "",
            //   'order_status' => $this->isValid("FAILED"),
            //   'transaction_msg'=>$result['statusMessage'],
            //   'updated_on'=>date('Y-m-d H:i:s'),
            //   'request_amount'=>$amount,
              
            // );
            // $txn_id = $this->transactions_model->addNewTransaction($trans_info);

            //update balance after deduction begin
            //$updatedBalance = $wallet_balance-$amount;
            //paytmFailed
          

          $userbalance = $this->rechargeApi_model->getUserBalance($data['user_id']);
          $wallet_balance=$userbalance->wallet_balance;
          $updatedBalance = $wallet_balance+$totalAmount; 
          //insert DEBIT txn into tbl_wallet_trans_dtls table
          $wallet_trans_info = array(
            'service_id' =>$service_id,
            'order_id' => $this->isValid($order_id), 
            'user_id' => $user_id, 
            'operator_id' => $operator_id,
            'api_id' => $api_details->api_id,
            'transaction_status'=>$result['status'],
            'transaction_type'=>"CREDIT",
            'payment_type' => "SERVICE",
            'payment_mode' => "REFUND FOR DMT, ACCOUNT NUMBER ".$receipt_details->bank_account_number.", AMOUNT ".$totalAmount.", CHARGE ".$PayableCharge,
            'transaction_id' =>"",               
            'trans_date' => date("Y-m-d H:i:s"),  
            'total_amount' => $totalAmount,
            'charge_amount' => "0.00",
            'balance' => $updatedBalance,
            'updated_on'=>date('Y-m-d H:i:s'),
          );
          $wallet_txn_id = $this->transactions_model->addNewWalletTransaction($wallet_trans_info);
          //update balance into users table                           
          $updateBalQry = $this->rechargeApi_model->updateUserBalance($user_id,$updatedBalance);
          //update balance after deduction end
          $response = array(
            'status' => "false",
            'msg' => $result['statusMessage'],
            'result' => $result
          );        
        }    
                
            
      }
      else{
      
        $response = array(
          'status' => "false",
          'msg' => "API implementation under process. Please contact administrator.",
          'result' => null
        );
        echo json_encode($response);
        exit;
      }
    }
    else{

      $response = array(
        'status' => "false",
        'msg' => "Invalid Request",
        'result' => null
      );
    }
    echo json_encode($response);
    $this->rechargeApi_model->send_telegram_api($order_id);
    exit;
  }
  
  public function doFundTransfer_test() {
    $input =  file_get_contents('php://input'); 
    $data =  json_decode($input, true);
          
          
    
    
    
    
    $this->authenticateUser($data);

    if(!empty($data['operatorID']) && !empty($data['sender_mobile_number'])){
      //get service details by operator id begin
      $operator_id = $data['operatorID'];
      $serviceDtl = $this->operatorApi_model->getServiceDetailsByOperatorID($operator_id);
      if($serviceDtl){
       $service_id = $serviceDtl[0]->service_id;
      }else{
        $response = array(
          'status' => "false",
          'msg' => "Invalid Service",
          'result' => null
        );
        echo json_encode($response);
        exit;
      }
      //get service details by operator id end 

      //check active api for operator begin
      $api_details = $this->rechargeApi_model->getActiveApiDetails($operator_id,$service_id,"");
      // echo "<pre>";
      // print_r($api_details);
      if(!$api_details){
        $response = array(
          'status' => "false",
          'msg' => "API details not configured for operator.Please contact administrator.",
          'result' => null
        );
        echo json_encode($response);
        exit;
      }
      $upi = $this->moneyTransferApi_model->get_table_alllike('tbl_application_details','alias','upilimit');
      $limit = $upi[0]->value;
      //check ampunt more than 100000 start
        if($data['transaction_amount'] > $limit){
        $response = array(
          'status' => "false",
          'msg' => "Exceed amount",
          'result' => null
        );
        echo json_encode($response);
        exit;
      }
      //check ampunt more than 100000 end
      //check active api for operator end
        //check fund transfer duplicate occur with in minutes start
        $wherecheck=array("service_id"=>$service_id,
                          "operator_id"=>$operator_id,
                          "recipient_id"=>$data['recipient_id'],
                          "request_amount"=>$data['transaction_amount']);
        $transdate=date('Y-m-d H:i');
        $min="5";
        $transduplicatecheck=$this->moneyTransferApi_model->checkduplicate_transaction_new($transdate,$wherecheck,$min);
        if(!empty($transduplicatecheck)){
           $response = array(
          
          'status' => "false",
          'msg' => "Same receipt and amount just now hit one Trasaction so Try again after a minute",
          'result' =>"",
        );
        echo json_encode($response);
        exit;
        }//check fund transfer occur with in minutes end
       
        $order_uni=$this->get_order_id();
        $ores=json_decode($order_uni,true);
        $sno_order_id=$ores['sno_order_id'];
        $order_id=$ores['order_id'];
        
   
      // if($api_details->api_id == "8" && floatval($data['transaction_amount']) > 5000){ //calling multi fund transfer API
      //   $this->doMultipleFundTransfer(file_get_contents('php://input'));
      //   die;
      // }
      // if($api_details->api_id == "9" && floatval($data['transaction_amount']) > 5000){ //calling multi fund transfer API
      //   $this->doMultipleFundTransfer(file_get_contents('php://input'));
      //   die;
        
      // }
      //  if($api_details->api_id == "12" && floatval($data['transaction_amount']) > 5000){ //calling multi fund transfer API
        
      //   $this->doMultipleFundTransfer(file_get_contents('php://input'));
      //   die;
        
      // }
      // if($api_details->api_id == "13" && floatval($data['transaction_amount']) > 5000){ //calling multi fund transfer API
        
      //   $this->doMultipleFundTransfer(file_get_contents('php://input'));
      //   die;
        
      // }
          
      //check balance of user begin
      $amount = $data['transaction_amount'];
      $operator_id = $data['operatorID'];
      $user_id= $data['user_id'];
      $role_id= $data['role_id'];
     
      $userbalance = $this->rechargeApi_model->getUserBalance($data['user_id']);
      if ($userbalance) {
        $wallet_balance = $userbalance->wallet_balance;
        $min_balance = $userbalance->min_balance;
        $user_package_id = $userbalance->package_id;
        //susmitha commision cal
        $commissiondet = $this->rechargeApi_model->getcommission($service_id,$user_package_id,$operator_id,$amount);
        $ccf=$commissiondet->ccf_commission;
        $charge = $commissiondet->retailer_commission;
         $cashback=$ccf-$charge;
        //cashback update to trans table based on order_id
        $app    =$this->rechargeApi_model->getTDS();
        $TDS     =$cashback*($app->value/100);
        $PayableCharge = $charge+$TDS;
        $totalAmount=$amount+$PayableCharge;
        if(is_numeric($wallet_balance) && is_numeric($min_balance) && is_numeric($totalAmount) && $wallet_balance-$totalAmount < $min_balance){
          $response = array(
              'status' => "false",
              'msg' => "Insufficient balance",
              'result' => null
          );
          echo json_encode($response);
          exit;
        }else if(!is_numeric($wallet_balance) || !is_numeric($min_balance) || !is_numeric($totalAmount)){
          $response = array(
              'status' => "false",
              'msg' => "Invalid amount details.",
              'result' => null
          );
          echo json_encode($response);
          exit;
        }else{//get all commission details by package id
          $commissionDtl = $this->rechargeApi_model->getCommissionDetails($user_package_id,$service_id,$operator_id,$amount);              
          if ($commissionDtl) {

            if($commissionDtl->commission_type == "Rupees"){
              
              $admin_commission = $commissionDtl->admin_commission;
              $md_commission = $commissionDtl->md_commission;
              $api_commission = $commissionDtl->api_commission;
              $distributor_commission = $commissionDtl->distributor_commission;
              $retailer_commission = $commissionDtl->retailer_commission;
              // //susmitha commision cal
              // $ccf=$commissionDtl->ccf_commission;
              // $charge = $commissionDtl->retailer_commission;
              // $cashback=$ccf-$charge;
              // //cashback update to trans table based on order_id
              // $app    =$this->rechargeApi_model->getTDS();
              // $TDS     =$cashback*$app->value;
              // $PayableCharge = $charge+$TDS;
              // $totalAmount=$amount+$PayableCharge;
              
            }else if($commissionDtl->commission_type == "Percent"){
              $admin_commission = ($amount*$commissionDtl->admin_commission)/100;
              $md_commission = ($amount*$commissionDtl->md_commission)/100;
              $api_commission = ($amount*$commissionDtl->api_commission)/100;
              $distributor_commission = ($amount*$commissionDtl->distributor_commission)/100;
              $retailer_commission = ($amount*$commissionDtl->retailer_commission)/100;
            }else if($commissionDtl->commission_type == "Range"){
              if($commissionDtl->admin_commission_type == "Rupees")
                $admin_commission = $commissionDtl->admin_commission;
              else
                $admin_commission = ($amount*$commissionDtl->admin_commission)/100;
              if($commissionDtl->md_commission_type == "Rupees")
                $md_commission = $commissionDtl->md_commission;
              else
                $md_commission = ($amount*$commissionDtl->md_commission)/100;
              if($commissionDtl->distributor_commission_type == "Rupees")
                $distributor_commission = $commissionDtl->distributor_commission;
              else
                $distributor_commission = ($amount*$commissionDtl->distributor_commission)/100;
              if($commissionDtl->retailer_commission_type == "Rupees")
                $retailer_commission = $commissionDtl->retailer_commission;
              else
                $retailer_commission = ($amount*$commissionDtl->retailer_commission)/100;
                $api_commission = $commissionDtl->api_commission;
            }
          }
        }
      }else{
        $response = array(
            'status' => "false",
            'msg' => "Error while retriving balance",
            'result' => null
        );
        echo json_encode($response);
        exit;
      }
      //check balance of user end

      //validate mpin begin
      if(isset($data['mpin']) && !empty($data['mpin'])){
        $userDtls = $this->rechargeApi_model->getValidateMPIN($data['user_id'],$data['mpin']);
        if(!$userDtls){
          $response = array(
            'status' => "false",
            'msg' => "Invalid MPIN",
            'result' => null
          );
          echo json_encode($response);
          exit;
        }
      }else{
        $response = array(
          'status' => "false",
          'msg' => "Invalid MPIN",
          'result' => null
        );
        echo json_encode($response);
        exit;
      }
      //validate mpin end

      //if all above conditions valid then update order id in file
     // $this->writeTxnOrderID($sno_order_id);      
   
      
      if($api_details->api_id == "12") {//paytm
         $mobile=$data['sender_mobile_number'];
        //get receipt account number ifsc from receipt table based on receipt id
          $receipt_details=$this->moneyTransferApi_model->get_singlebyid('tbl_dmt_benificiary_dtls','recipient_id',$data['recipient_id']);
          if(empty($receipt_details)){
           $response = array(
          'status' => "false",
          'msg' => "Recipient Id not there check receipt id!! try again.",
          'result' => null
              );
          echo json_encode($response);
        exit;
          }
       
        $senderwhere  =array("sender_mobile_number"=>$mobile,
                             "api_name"=>"paytm");
        //print_r($senderwhere);

        $sender_avlcheck =$this->moneyTransferApi_model->getdata_where('tbl_sender_dts',$senderwhere);
        $senamount=$sender_avlcheck[0]->available_limit;
        
        
        if($amount>$senamount){
           $response = array(
          
          'status' => "false",
          'msg' => "Limit Exceed",
          'result' =>"",
        );
        echo json_encode($response);
        exit;
        }//check avalibe limit in sender table end
         //susmitha commision cal
        $commissionDtl = $this->rechargeApi_model->getcommission($service_id,$user_package_id,$operator_id,$amount);
        $ccf=$commissionDtl->ccf_commission;
        // $charge = $commissionDtl->retailer_commission;
        if($commissionDtl->retailer_commission_type == 'Percent'){
          $charge = $amount * ($commissionDtl->retailer_commission/100);
        }elseif($commissionDtl->retailer_commission_type == 'Rupees'){
          $charge = $commissionDtl->retailer_commission;
        }
        $cashback=$ccf-$charge;
        $app    =$this->rechargeApi_model->getTDS();
        $TDS     =$cashback*($app->value/100);
        $PayableCharge = $charge+$TDS;
        $totalAmount=$amount+$PayableCharge;
        $updatedBalance = $wallet_balance-$totalAmount; //update balance after deduction begin
        //insert DEBIT txn into tbl_wallet_trans_dtls table
          $wallet_trans_info = array(
            'service_id' =>$service_id,
            'order_id' => $this->isValid($order_id), 
            'user_id' => $user_id, 
            'operator_id' => $operator_id,
            'api_id' => $api_details->api_id,
            'transaction_status' =>'Success',
            'transaction_type' => "DEBIT",
            'payment_type' => "SERVICE",
            'payment_mode' => "PAID FOR UPI, UPI ID ".$receipt_details->bank_account_number.", AMOUNT ".$totalAmount.", CHARGE ".$PayableCharge,
            'transaction_id' =>'',               
            'trans_date' => date("Y-m-d H:i:s"),  
            'total_amount' => $totalAmount,
            'charge_amount' => "0.00",
            'balance' => $updatedBalance,
            'CCFcharges'=>$ccf,
            'Cashback'=>$cashback,
            'TDSamount'=>$TDS,
            'PayableCharge'=>$PayableCharge,
            'FinalAmount'=>$totalAmount,
            'updated_on'=>date('Y-m-d H:i:s'),
          );
          $wallet_txn_id = $this->transactions_model->addNewWalletTransaction($wallet_trans_info);
          //update balance into users table                           
          $updateBalQry = $this->rechargeApi_model->updateUserBalance($user_id,$updatedBalance);
          //update balance after deduction end
        
          $trans_info = array(
            'transaction_id' =>"0",
            'transaction_status' =>"PENDING", 
            'service_id' => $service_id, 
            'operator_id'=>$operator_id,
            'api_id' => $api_details->api_id,
            'trans_date' => date("Y-m-d H:i:s"),
            'order_id' => $this->isValid($order_id),  
            'mobileno' =>$data['sender_mobile_number']."", 
            'user_id' => $this->isValid($user_id),          
            'total_amount' => $this->isValid($amount),
            'charge_amount' =>"0",
            'transaction_type' => $data['transaction_type'], //add
            // 'bank_transaction_id' =>$result['result']['paytmOrderId'], //add
            'bank_transaction_id' =>"", //add
            'imps_name' =>$receipt_details->recipient_name."", //add
            'recipient_id' =>$data['recipient_id'], //add
            'charges_tax' =>"0", //add
            'commission' =>"0", //add
            'commission_tax' =>"0", //add
            'commission_tds' =>"0", //add
            'debit_amount' =>"0",
            'balance' =>"0",
            'order_status' => "PENDING",
            'transaction_msg'=>"",
            'CCFcharges'=>$ccf,
            'Cashback'=>$cashback,
            'TDSamount'=>$TDS,
            'PayableCharge'=>$PayableCharge,
            'FinalAmount'=>$totalAmount,
            'request_amount'=>$amount,
            'updated_on'=>date('Y-m-d H:i:s'),
      );
      //print_r($trans_info);
        $txn_id = $this->transactions_model->addNewTransaction($trans_info);
        /* Here begins Cashfree Layout */
        
        
        
        /* Here Ends Cashfree Layout */
          //echo "<pre>";
          $paytmParams = array();

          $paytmParams["subwalletGuid"]      = $api_details->api_token;
          $paytmParams["orderId"]            = $order_id;
          //$paytmParams["beneficiaryAccount"] = $receipt_details->bank_account_number;
          $paytmParams["beneficiaryVPA"] = $receipt_details->bank_account_number;
          
          //$paytmParams["beneficiaryIFSC"]    = $receipt_details->ifsc;
          $paytmParams["amount"]             = $amount;
          $paytmParams["purpose"]            = "OTHERS";
          $paytmParams["date"]               = date('Y-m-d');
          $paytmParams["transferMode"]       =$data['transaction_type'];
          $paytmParams["callbackUrl"]       = "https://paymamaapp.in/admin/index.php/Transactions/paytm_moneytrans_status/".$order_id;
        
          $post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);
           //echo "<pre>";
          //  print_r($post_data);

          //save api log details begin
          $api_info = array(
            'service_id' => $service_id."", 
            'api_id' => $api_details->api_id."", 
            'api_name' => $api_details->api_name."",  
            'api_method' => "doFundTransfer",
            'api_url' => $api_details->api_url."", 
            'order_id' => $order_id."", 
            'user_id' => $user_id."",  
            'request_input' => json_encode($post_data)."",
            'request' => json_encode($post_data)."",         
            'response' =>"",
            'access_type' => (isset($data['access_type'])) ? $data['access_type'] : 'DEFAULT' ,
            'updated_on'=>date('Y-m-d H:i:s'),
          );
          $api_log_id = $this->apiLog_model->addApiLogDetails($api_info);
          //save api log details end

          /*
          * Generate checksum by parameters we have in body
          * Find your Merchant Key in your Paytm Dashboard at https://dashboard.paytm.com/next/apikeys 
          */
          $checksum = PaytmChecksum::generateSignature($post_data,$api_details->api_secretkey);

          $x_mid      = $api_details->username;
          $x_checksum = $checksum;

          /* for Staging */
          $url =$api_details->api_url;

          /* for Production */
          // $url = "https://dashboard.paytm.com/bpay/api/v1/disburse/order/bank";

          $ch = curl_init($url);
          curl_setopt($ch, CURLOPT_POST, true);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
          curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "x-mid: " . $x_mid, "x-checksum: " . $x_checksum)); 
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
          $result_json = curl_exec($ch);
          curl_close($ch);
          $result =  json_decode($result_json, true);

          $this->db->where('order_id', $order_id)->update('tbl_apilog_dts', array("response"=>$result_json, 'updated_on'=>date('Y-m-d H:i:s')));
          /*if($result['statusCode']=="DE_002"){
            // print_r('Condition');
            $status_json = $this->check_paytm_status($order_id,$api_details->api_secretkey,$api_details->username,$api_details->api_trn_status_url); 
            $result =  json_decode($status_json, true);   
          }*/

          if($result['status'] == "SUCCESS" || $result['status'] == "ACCEPTED" || $result['status'] == "PENDING") {
            
              //update balance based on api id in api setting table developed by susmitha start

              $trans_record = [];
              $oStatus = $result['status'];
              while ($oStatus=='PENDING') {
                  $trans_record = $this->db->select('*')->from('tbl_transaction_dtls')->where('order_id', $order_id)->get()->row();
                  // $trans_record =$this->moneyTransferApi_model->getdata_where('tbl_transaction_dtls',array('order_id'=> $order_id ));
                  if ($trans_record->order_status != 'PENDING' ) {
                      // $oStatus = $trans_record[0]->order_status;
                      $oStatus = $trans_record->order_status;
                      break;
                  }
              }

              if ( $oStatus == 'FAILED') {
                  $failed_resp = $this->failedPaytmTransfer($trans_info, $trans_record->order_status );
                  $response = array(
                    'status' => "false",
                    'msg' => 'failed',
                    'result' => $trans_record
                  );   
                  echo json_encode($response) ;

                  exit;
              }

              $currentapibal=0;
              $data = array('balance'=>$currentapibal);
              $this->apiLog_model->update_api_amount($data,$api_details->api_id);
              //update balance based on api id in apisetting table developed by susmitha end  
              //update sender avaland used limit start
              $sender_det  =array("sender_mobile_number"=>$mobile);
              $sender_details =$this->moneyTransferApi_model->getdata_where('tbl_sender_dts',$sender_det);
              $available_limit=($sender_details[0]->Upi_available_limit)-($amount);
              $used_limit=($sender_details[0]->Upi_used_limit)+($amount);
              $sender_detupdate=array("Upi_available_limit"=>$available_limit,
                                    "Upi_used_limit"=>$used_limit);
              //print_r($sender_detupdate);
              $this->db->where('sender_mobile_number',$mobile);
              $this->db->update('tbl_sender_dts',$sender_detupdate);
            //update sender avaland used limit end
              $recipent_ver  =array("recipient_id"=>$data['recipient_id'],"is_verified"=>"Y");
              $benificiary_details =$this->moneyTransferApi_model->getdata_where('tbl_dmt_benificiary_dtls',$recipent_ver);
              //print_r($benificiary_details);
              if(empty($benificiary_details)){
                $updatebeni=array("is_verified"=>"Y","verified_name"=>$result['result']['beneficiaryName']);
              $where=array('recipient_id',$data['recipient_id']); 
              $this->db->where($where);
              $this->db->update('tbl_dmt_benificiary_dtls',$updatebeni);
              }
              
              $money_re=$this->transactions_model->getmoneyreport($txn_id);
              // print_r($money_re);
              $response = array(
                'status' => "true",
                'msg' => "Success",
                'result' => $result,
                'money'=>$money_re
              );

              //send sms 
              $digits = 5;
              $rand=rand(pow(10, $digits-1), pow(10, $digits)-1);
              //echo "$rand and " . ($startDate + $rand);
              $username="anandkaushal.in";
              $password="Budd789";
              $msisdn=$mobile;
              $sid="SRLSHD";
            //by vishal
              // $msg="Transaction Done TID: ".$txn_id." -  order id ".$money_re->order_no.", Amt : Rs ".$amount." Fees: 1 % Name :".$money_re->name."  A/c : ".$money_re->bank_account_number." IFSC: ".$money_re->ifsc." PAYMAMA - ";
              //$msg="".$rand."%20is%20your%20verification%20code.%20Regards%20Saral%20Shaadi.";
              $url="http://cloud.smsindiahub.in/vendorsms/pushsms.aspx?user=".$username."&password=".$password."&msisdn=".$msisdn."&sid=".$sid."&msg=".$msg."&fl=0&gwid=2";
            //  $ch = curl_init();  
            //  curl_setopt($ch,CURLOPT_URL,$url);
            //  curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
            //  $output=curl_exec($ch);
            //  curl_close($ch);
            $msg="TID: ".$order_id." Amt : ".$amount." Fees: 1 %, Name : ".$money_re->name." UPI : ".$benificiary_details->bank_account_number." Mobile ".$benificiary_details->recipient_mobile_number." www.paymamaapp.in PAYMAMA";
              $temp_id = "1007821138683202361"; 
            $sms_output = $this->sendBulkSMS($msisdn, $msg, $temp_id); 
            //by vishal End
            //send sms 
            //commission wallet txn begin
              if(is_numeric($role_id) && intval($role_id) <= 4){
                $walletUserID = $user_id;
                $walletRoleID = $role_id;
                $isUserBalanceUpdated = false;
                
                for($i=$walletRoleID;$i>=1;$i--){                
                  if($i==3 || $i==4  || $i==7){
                    $isUserBalanceUpdated = true;
                    $userParentID = $this->rechargeApi_model->getUserParentID($walletUserID);
                    if ($isUserBalanceUpdated && $userParentID && ( $userParentID->roleId==3||$userParentID->roleId==4||$userParentID->roleId==7) ) {
                      $walletUserID = $userParentID->userId;
                      $walletRoleID = $userParentID->roleId;
                      $updatedBalance = $userParentID->wallet_balance;
                    }
                    continue;
                  }
                  $walletAmt = 0;
                  $walletBal = 0;    
                  $distds='';             
                  $userParentID = $this->rechargeApi_model->getUserParentID($walletUserID);
                  if ($isUserBalanceUpdated && $userParentID) {
                    $walletUserID = $userParentID->userId;
                    $walletRoleID = $userParentID->roleId;
                    $updatedBalance = $userParentID->wallet_balance;
                  }
                  /*echo "Wallet User ID:".$walletUserID." Role ID:".$walletRoleID."<br/>";
                  echo "Retailer Commision:".$retailer_commission."Dist.:".$distributor_commission."Admin.:".$admin_commission."<br/>";*/
                  // if($walletRoleID == 4){ //Retailer
                  //   $walletAmt = $retailer_commission;
                  //   $walletBal = $updatedBalance+$retailer_commission;
                  // }
                  /*else if($walletRoleID == 3){ //FOS
                    $walletAmt = $distributor_commission;
                    $walletBal = $updatedBalance+$distributor_commission;
                  }*/ if($walletRoleID == 2){ //Distributor
                    //$walletAmt = $distributor_commission;
                    $ds = $distributor_commission;
                      $apptds    =$this->rechargeApi_model->getTDS();
                      $distds=$ds*($apptds->value/100);
                      $walletAmt=$ds-$distds; 
                    $walletBal = $updatedBalance+$distributor_commission;
                  }else if($walletRoleID == 1){ //Admin
                    $walletAmt = $admin_commission;
                    $walletBal = $updatedBalance+$admin_commission;
                  }
                  if(is_numeric($walletAmt) && is_numeric($walletBal)){
                    $transType = "CREDIT";
                    if($walletAmt < 0){
                      $transType = "DEBIT";
                    }
                    $wallet_trans_info = array(
                      'service_id' => $service_id,
                      'order_id' => $this->isValid($order_id), 
                      'user_id' => $walletUserID, 
                      'operator_id' => $operator_id,
                      'api_id' => $api_details->api_id,
                      'transaction_status' => $result['status'],
                      'transaction_type' => $transType,
                      'payment_type' => "COMMISSION",
                      'payment_mode' => "COMMISSION FOR UPI, UPI ID ".$benificiary_details->bank_account_number.", AMOUNT ".$walletAmt,
                      'transaction_id' => "",               
                      'trans_date' => date("Y-m-d H:i:s"),  
                      'total_amount' => abs($walletAmt),
                      'charge_amount' => "0.00",
                      'balance' => $walletBal,
                      'TDSamount'=>$distds,
                      'updated_on'=>date('Y-m-d H:i:s'),
                    );
                    $wallet_txn_id = $this->transactions_model->addNewWalletTransaction($wallet_trans_info);
                    //update balance into users table                           
                    $updateBalQry = $this->rechargeApi_model->updateUserBalance($walletUserID,$walletBal);
                    //update balance after deduction end
                  }
                  $isUserBalanceUpdated = true;
                }
              }
              //commission wallet txn end
          }else{
          $trans_info = array(
            'transaction_id' =>"",
            'transaction_status' =>$result['status'], 
            'service_id' => $service_id, 
            'operator_id'=>$operator_id,
            'api_id' => $api_details->api_id,
            'trans_date' => date("Y-m-d H:i:s"),
            'order_id' => $this->isValid($order_id),  
            'mobileno' => $this->isValid($data['sender_mobile_number']), 
            'user_id' => $this->isValid($user_id),          
            'total_amount' => $amount,
            'charge_amount' =>"",
            'transaction_type' => $data['transaction_type'], //add
            'bank_transaction_id' =>"", //add
            'imps_name' => $receipt_details->recipient_name."", //add
            'recipient_id' =>$receipt_details->recipient_id, //add
            'charges_tax' =>"", //add
            'commission' =>"", //add
            'commission_tax' =>"", //add
            'commission_tds' =>"", //add
            'debit_amount' =>"",
            'balance' => "",
            'order_status' => $this->isValid("FAILED"),
            'transaction_msg'=>$result['statusMessage'],
            'updated_on'=>date('Y-m-d H:i:s'),
            'request_amount'=>$amount,
            
          );
          $txn_id = $this->transactions_model->addNewTransaction($trans_info);
          //update balance after deduction begin
        //$updatedBalance = $wallet_balance-$amount;
          $userbalance = $this->rechargeApi_model->getUserBalance($data['user_id']);
          $wallet_balance=$userbalance->wallet_balance;
          $updatedBalance = $wallet_balance+$totalAmount; 
          //insert DEBIT txn into tbl_wallet_trans_dtls table
          $wallet_trans_info = array(
            'service_id' =>$service_id,
            'order_id' => $this->isValid($order_id), 
            'user_id' => $user_id, 
            'operator_id' => $operator_id,
            'api_id' => $api_details->api_id,
            'transaction_status'=>$result['status'],
            'transaction_type'=>"CREDIT",
            'payment_type' => "SERVICE",
            'payment_mode' => "PAID FOR UPI, UPI ID ".$receipt_details->bank_account_number.", AMOUNT ".$totalAmount.", CHARGE ".$PayableCharge,
            'transaction_id' =>"",               
            'trans_date' => date("Y-m-d H:i:s"),  
            'total_amount' => $totalAmount,
            'charge_amount' => "0.00",
            'balance' => $updatedBalance,
            'updated_on'=>date('Y-m-d H:i:s'),
          );
          $wallet_txn_id = $this->transactions_model->addNewWalletTransaction($wallet_trans_info);
          //update balance into users table                           
          $updateBalQry = $this->rechargeApi_model->updateUserBalance($user_id,$updatedBalance);
          //update balance after deduction end
          $response = array(
            'status' => "false",
            'msg' => $result['statusMessage'],
            'result' => $result
          );        
        }
         

      }
      else if($api_details->api_id == "13"){ //hypto
      	//echo $api_details->api_id;
        $url = $api_details->api_url."/api/transfers/initiate";
        $api_token=$api_details->api_token;
        $mobile=$data['sender_mobile_number'];
        //get receipt account number ifsc from receipt table based on receipt id
          $receipt_details=$this->moneyTransferApi_model->get_singlebyid('tbl_dmt_benificiary_dtls','recipient_id',$data['recipient_id']);
          if(empty($receipt_details)){
              $response = array(
                  'status' => "false",
                  'msg' => "Recipient Id not there check receipt id!! try again.",
                  'result' => null
              );
              echo json_encode($response);
              exit;
          }
         //check avalibe limit in sender table start
        $senderwhere  =array("sender_mobile_number"=>$mobile,
                             "api_name"=>"paytm");
        $sender_avlcheck =$this->moneyTransferApi_model->getdata_where('tbl_sender_dts',$senderwhere);
        $senamount=$sender_avlcheck[0]->available_limit;
        if($amount>$senamount){
           $response = array(
          
          'status' => "false",
          'msg' => "Limit Exceed",
          'result' =>"",
        );
        echo json_encode($response);
        exit;
        }//check avalibe limit in sender table end
         //susmitha commision cal
        // echo $service_id;
        // echo $user_package_id;
        // echo $operator_id;
        // echo $amount;
        $commissionDtl = $this->rechargeApi_model->getcommission($service_id,$user_package_id,$operator_id,$amount);
        $ccf=$commissionDtl->ccf_commission;
        // $charge = $commissionDtl->retailer_commission;
        if($commissionDtl->retailer_commission_type == 'Percent'){
          $charge = $amount * ($commissionDtl->retailer_commission/100);
        }elseif($commissionDtl->retailer_commission_type == 'Rupees'){
          $charge = $commissionDtl->retailer_commission;
        }
        $cashback=$ccf-$charge;
        $app    =$this->rechargeApi_model->getTDS();
        $TDS     =$cashback*($app->value/100);
        $PayableCharge = $charge+$TDS;
        $totalAmount=$amount+$PayableCharge;
        $updatedBalance = $wallet_balance-$totalAmount; //update balance after deduction begin
        //insert DEBIT txn into tbl_wallet_trans_dtls table
          $wallet_trans_info = array(
            'service_id' =>$service_id,
            'order_id' => $this->isValid($order_id), 
            'user_id' => $user_id, 
            'operator_id' => $operator_id,
            'api_id' => $api_details->api_id,
            'transaction_status' =>'Success',
            'transaction_type' => "DEBIT",
            'payment_type' => "SERVICE",
            'payment_mode' => "PAID FOR UPI, UPI ID ".$receipt_details->bank_account_number.", AMOUNT ".$totalAmount.", CHARGE ".$PayableCharge,
            'transaction_id' =>'',               
            'trans_date' => date("Y-m-d H:i:s"),  
            'total_amount' => $totalAmount,
            'charge_amount' => "0.00",
            'balance' => $updatedBalance,
            'CCFcharges'=>$ccf,
            'Cashback'=>$cashback,
            'TDSamount'=>$TDS,
            'PayableCharge'=>$PayableCharge,
            'FinalAmount'=>$totalAmount,
            'updated_on'=>date('Y-m-d H:i:s'),
          );
          $wallet_txn_id = $this->transactions_model->addNewWalletTransaction($wallet_trans_info);
          //update balance into users table                           
          $updateBalQry = $this->rechargeApi_model->updateUserBalance($user_id,$updatedBalance);
          //update balance after deduction end

          $post=array(
            "amount"=>$amount,
            "payment_type"=>$data['transaction_type'],
            //"ifsc"   =>$receipt_details->ifsc,
            //"number"=>$receipt_details->bank_account_number,
            "upi_id"  =>$receipt_details->bank_account_number,
            "note"  =>"Fund Transfer",
            "reference_number"=>$order_id);
          

          $result_json=$this->hyptocurl($url,$post,$api_token);
          $result =  json_decode($result_json, true);
          $status_url = $api_details->api_url."/api/transfers/status/".$order_id."";
          $headers = array('Content-Type:application/json','Authorization:'.$api_token.'');
        //   $result_json=$this->getcurl_with_header($status_url,$headers);
        //   $result =  json_decode($result_json, true);
           
           if ($result['success']) {

            if ($result['data']['status'] == 'PENDING') {

                $break_count = 0;
                while($result['data']['status'] != 'COMPLETED') {
                    sleep(10);
                  if($break_count>1)
                    break;

                  $result_json=$this->getcurl_with_header($status_url,$headers);
                    $result =  json_decode($result_json, true);

                    $break_count++;
                   
                }
            }
          }
          
          //save api log details begin
          $api_info = array(
          'service_id' => $service_id."", 
          'api_id' => $api_details->api_id."", 
          'api_name' => $api_details->api_name."",  
          'api_method' => "doFundTransfer",
          'api_url' => $url."", 
          'order_id' => $order_id."", 
          'user_id' => $user_id."",  
          'request_input' => json_encode($post)."",
          'request' => json_encode($post)."",         
          'response' => $result_json."",
          'access_type' => "APP",
          'updated_on'=>date('Y-m-d H:i:s'),
        );
        $api_log_id = $this->apiLog_model->addApiLogDetails($api_info);
          if($result['success']=="true"){
            $trans_info = array(
            'transaction_id' =>$result['data']['id'],
            'transaction_status' =>$result['data']['status'], 
            'service_id' => $service_id, 
            'operator_id'=>$operator_id,
            'api_id' => $api_details->api_id,
            'trans_date' => date("Y-m-d H:i:s"),
            'order_id' => $this->isValid($order_id),  
            'mobileno' =>$data['sender_mobile_number']."", 
            'user_id' => $this->isValid($user_id),          
            'total_amount' => $this->isValid($amount),
            'charge_amount' =>"0",
            'transaction_type' =>$result['data']['payment_type'], //add
            'bank_transaction_id' =>$result['data']['bank_ref_num'], //add
            'imps_name' =>$receipt_details->recipient_name."", //add
            'recipient_id' =>$receipt_details->recipient_id, //add
            'charges_tax' =>"0", //add
            'commission' =>"0", //add
            'commission_tax' =>"0", //add
            'commission_tds' =>"0", //add
            'debit_amount' =>"0",
            'balance' =>$result['data']['closing_balance'],
            'order_status' => ($result['data']['status'] == 'COMPLETED') ? 'SUCCESS' : $result['data']['status'],
            'transaction_msg'=>$result['message'],
            'CCFcharges'=>$ccf,
            'Cashback'=>$cashback,
            'TDSamount'=>$TDS,
            'PayableCharge'=>$PayableCharge,
            'FinalAmount'=>$totalAmount,
            'request_amount'=>$amount,
            'updated_on'=>date('Y-m-d H:i:s'),
          );
            //print_r($trans_info);
          $txn_id = $this->transactions_model->addNewTransaction($trans_info);
          //update balance based on api id in api setting table developed by susmitha start
          $currentapibal=0;
          $data = array('balance'=>$currentapibal);
          $this->apiLog_model->update_api_amount($data,$api_details->api_id);
          //update balance based on api id in apisetting table developed by susmitha end  
           //update sender avaland used limit start
          $sender_det  =array("sender_mobile_number"=>$mobile);
          $sender_details =$this->moneyTransferApi_model->getdata_where('tbl_sender_dts',$sender_det);
          $available_limit=($sender_details[0]->Upi_available_limit)-($amount);
          $used_limit=($sender_details[0]->Upi_available_limit)+($amount);
          $sender_detupdate=array("Upi_available_limit"=>$available_limit,
                                 "Upi_used_limit"=>$used_limit);
          //print_r($sender_detupdate);
          $this->db->where('sender_mobile_number',$mobile);
          $this->db->update('tbl_sender_dts',$sender_detupdate);
         //update sender avaland used limit end
           $recipent_ver  =array("recipient_id"=>$data['recipient_id'],"is_verified"=>"Y");
         $benificiary_details =$this->moneyTransferApi_model->getdata_where('tbl_dmt_benificiary_dtls',$recipent_ver);
           //print_r($benificiary_details);
          if(empty($benificiary_details)){
            $updatebeni=array("is_verified"=>"Y","verified_name"=>$result['data']['transfer_account_holder']);
          $where=array('recipient_id',$data['recipient_id']); 
          $this->db->where($where);
          $this->db->update('tbl_dmt_benificiary_dtls',$updatebeni);
          }
          $money_re=$this->transactions_model->getmoneyreport($txn_id);
          // print_r($money_re);
          $response = array(
            'status' => "true",
            'msg' => "Success",
            'result' => $result,
            'money'=>$money_re
          );

          //send sms 
          $digits = 5;
          $rand=rand(pow(10, $digits-1), pow(10, $digits)-1);
          //echo "$rand and " . ($startDate + $rand);
          $username="anandkaushal.in";
          $password="Budd789";
          $msisdn=$mobile;
          $sid="SRLSHD";
          $msg="Transaction Done TID: ".$txn_id." -  order id ".$money_re->order_no.", Amt : Rs ".$amount." Fees: 1 % Name :".$money_re->name."  A/c : ".$money_re->bank_account_number." IFSC: ".$money_re->ifsc." PAYMAMA - ";
          //$msg="".$rand."%20is%20your%20verification%20code.%20Regards%20Saral%20Shaadi.";
          $url="http://cloud.smsindiahub.in/vendorsms/pushsms.aspx?user=".$username."&password=".$password."&msisdn=".$msisdn."&sid=".$sid."&msg=".$msg."&fl=0&gwid=2";
         $ch = curl_init();  
         curl_setopt($ch,CURLOPT_URL,$url);
         curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
         $output=curl_exec($ch);
         curl_close($ch);
         //send sms 
        //commission wallet txn begin
          if(is_numeric($role_id) && intval($role_id) <= 4){                
            $walletUserID = $user_id;
            $walletRoleID = $role_id;
            $isUserBalanceUpdated = false;
            
            for($i=$walletRoleID;$i>=1;$i--){                
              if($i==3 || $i==4  || $i==7){
                $isUserBalanceUpdated = true;
                $userParentID = $this->rechargeApi_model->getUserParentID($walletUserID);
                if ($isUserBalanceUpdated && $userParentID && ( $userParentID->roleId==3||$userParentID->roleId==4||$userParentID->roleId==7) ) {
                  $walletUserID = $userParentID->userId;
                  $walletRoleID = $userParentID->roleId;
                  $updatedBalance = $userParentID->wallet_balance;
                }
                continue;
              }
              $walletAmt = 0;
              $walletBal = 0;    
              $distds='';             
              $userParentID = $this->rechargeApi_model->getUserParentID($walletUserID);
              if ($isUserBalanceUpdated && $userParentID) {
                $walletUserID = $userParentID->userId;
                $walletRoleID = $userParentID->roleId;
                $updatedBalance = $userParentID->wallet_balance;
              }
              /*echo "Wallet User ID:".$walletUserID." Role ID:".$walletRoleID."<br/>";
              echo "Retailer Commision:".$retailer_commission."Dist.:".$distributor_commission."Admin.:".$admin_commission."<br/>";*/
              // if($walletRoleID == 4){ //Retailer
              //   $walletAmt = $retailer_commission;
              //   $walletBal = $updatedBalance+$retailer_commission;
              // }
              /*else if($walletRoleID == 3){ //FOS
                $walletAmt = $distributor_commission;
                $walletBal = $updatedBalance+$distributor_commission;
              }*/ if($walletRoleID == 2){ //Distributor
                //$walletAmt = $distributor_commission;
                $ds = $distributor_commission;
                  $apptds    =$this->rechargeApi_model->getTDS();
                  $distds=$ds*($apptds->value/100);
                  $walletAmt=$ds-$distds; 
                $walletBal = $updatedBalance+$distributor_commission;
              }else if($walletRoleID == 1){ //Admin
                $walletAmt = $admin_commission;
                $walletBal = $updatedBalance+$admin_commission;
              }
              if(is_numeric($walletAmt) && is_numeric($walletBal)){
                $transType = "CREDIT";
                if($walletAmt < 0){
                  $transType = "DEBIT";
                }
                $wallet_trans_info = array(
                  'service_id' => $service_id,
                  'order_id' => $this->isValid($order_id), 
                  'user_id' => $walletUserID, 
                  'operator_id' => $operator_id,
                  'api_id' => $api_details->api_id,
                  'transaction_status' => $result['status'],
                  'transaction_type' => $transType,
                  'payment_type' => "COMMISSION",
                  'payment_mode' => "COMMISSION FOR UPI, UPI ID ".$benificiary_details->bank_account_number.", AMOUNT ".$walletAmt,
                  'transaction_id' => "",               
                  'trans_date' => date("Y-m-d H:i:s"),  
                  'total_amount' => abs($walletAmt),
                  'charge_amount' => "0.00",
                  'balance' => $walletBal,
                  'TDSamount'=>$distds,
                  'updated_on'=>date('Y-m-d H:i:s'),
                );
                $wallet_txn_id = $this->transactions_model->addNewWalletTransaction($wallet_trans_info);
                //update balance into users table                           
                $updateBalQry = $this->rechargeApi_model->updateUserBalance($walletUserID,$walletBal);
                //update balance after deduction end
              }
              $isUserBalanceUpdated = true;
            }
          }
          //commission wallet txn end
        }else{
          $trans_info = array(
            'transaction_id' =>"",
            'transaction_status' =>$result['message'], 
            'service_id' => $service_id, 
            'operator_id'=>$operator_id,
            'api_id' => $api_details->api_id,
            'trans_date' => date("Y-m-d H:i:s"),
            'order_id' => $this->isValid($order_id),  
            'mobileno' => $this->isValid($data['sender_mobile_number']), 
            'user_id' => $this->isValid($user_id),          
            'total_amount' => $amount,
            'charge_amount' =>"",
            'transaction_type' => $data['transaction_type'], //add
            'bank_transaction_id' =>"", //add
            'imps_name' => $receipt_details->recipient_name."", //add
            'recipient_id' =>$receipt_details->recipient_id, //add
            'charges_tax' =>"", //add
            'commission' =>"", //add
            'commission_tax' =>"", //add
            'commission_tds' =>"", //add
            'debit_amount' =>"",
            'balance' => "",
            'order_status' => $this->isValid("FAILED"),
            'transaction_msg'=>$result['reason'],
            'updated_on'=>date('Y-m-d H:i:s'),
            'request_amount'=>$amount,
            
          );
          $txn_id = $this->transactions_model->addNewTransaction($trans_info);
          //update balance after deduction begin
        //$updatedBalance = $wallet_balance-$amount;
          $userbalance = $this->rechargeApi_model->getUserBalance($data['user_id']);
          $wallet_balance=$userbalance->wallet_balance;
          $updatedBalance = $wallet_balance+$totalAmount; 
          //insert DEBIT txn into tbl_wallet_trans_dtls table
          $wallet_trans_info = array(
            'service_id' =>$service_id,
            'order_id' => $this->isValid($order_id), 
            'user_id' => $user_id, 
            'operator_id' => $operator_id,
            'api_id' => $api_details->api_id,
            'transaction_status'=>"Success",
            'transaction_type'=>"CREDIT",
            'payment_type' => "SERVICE",
            'payment_mode' => "PAID FOR UPI, UPI ID ".$receipt_details->bank_account_number.", AMOUNT ".$totalAmount.", CHARGE ".$PayableCharge,
            'transaction_id' =>"",               
            'trans_date' => date("Y-m-d H:i:s"),  
            'total_amount' => $totalAmount,
            'charge_amount' => "0.00",
            'balance' => $updatedBalance,
            'updated_on'=>date('Y-m-d H:i:s'),
          );
          $wallet_txn_id = $this->transactions_model->addNewWalletTransaction($wallet_trans_info);
          //update balance into users table                           
          $updateBalQry = $this->rechargeApi_model->updateUserBalance($user_id,$updatedBalance);
          //update balance after deduction end
          $response = array(
            'status' => "false",
            'msg' => $result['message'],
            'result' =>$result
          );        
        }
         
      }
      elseif($api_details->api_id == "14") { //razorpay
      	//echo $api_details->api_id;
        $url = $api_details->api_url;
        $mobile=$data['sender_mobile_number'];
        //get receipt account number ifsc from receipt table based on receipt id
          $receipt_details=$this->moneyTransferApi_model->get_singlebyid('tbl_dmt_benificiary_dtls','recipient_id',$data['recipient_id']);
          if(empty($receipt_details)){
              $response = array(
                  'status' => "false",
                  'msg' => "Recipient Id not there check receipt id!! try again.",
                  'result' => null
              );
              echo json_encode($response);
              exit;
          }
         //check avalibe limit in sender table start
        $senderwhere  =array("sender_mobile_number"=>$mobile,
                             "api_name"=>"paytm");
        $sender_avlcheck =$this->moneyTransferApi_model->getdata_where('tbl_sender_dts',$senderwhere);
        $senamount=$sender_avlcheck[0]->available_limit;
        if($amount>$senamount){
           $response = array(
          
          'status' => "false",
          'msg' => "Limit Exceed",
          'result' =>"",
        );
        echo json_encode($response);
        exit;
        }//check avalibe limit in sender table end
        $commissionDtl = $this->rechargeApi_model->getcommission($service_id,$user_package_id,$operator_id,$amount);
        $ccf=$commissionDtl->ccf_commission;
        // $charge = $commissionDtl->retailer_commission;
        if($commissionDtl->retailer_commission_type == 'Percent'){
          $charge = $amount * ($commissionDtl->retailer_commission/100);
        }elseif($commissionDtl->retailer_commission_type == 'Rupees'){
          $charge = $commissionDtl->retailer_commission;
        }
        $cashback=$ccf-$charge;
        $app = $this->rechargeApi_model->getTDS();
        $TDS = $cashback*($app->value/100);
        $PayableCharge = $charge+$TDS;
        $totalAmount=$amount + $PayableCharge;
        $updatedBalance = $wallet_balance-$totalAmount; //update balance after deduction begin
        //insert DEBIT txn into tbl_wallet_trans_dtls table
          $wallet_trans_info = array(
            'service_id' =>$service_id,
            'order_id' => $this->isValid($order_id), 
            'user_id' => $user_id, 
            'operator_id' => $operator_id,
            'api_id' => $api_details->api_id,
            'transaction_status' =>'Success',
            'transaction_type' => "DEBIT",
            'payment_type' => "SERVICE",
            'payment_mode' => "PAID FOR UPI, UPI ID ".$api_details->account_no.", AMOUNT ".$totalAmount.", CHARGE ".$PayableCharge,
            'transaction_id' =>'',               
            'trans_date' => date("Y-m-d H:i:s"),  
            'total_amount' => $totalAmount,
            'charge_amount' => "0.00",
            'balance' => $updatedBalance,
            'CCFcharges'=>$ccf,
            'Cashback'=>$cashback,
            'TDSamount'=>$TDS,
            'PayableCharge'=>$PayableCharge,
            'FinalAmount'=>$totalAmount,
            'updated_on'=>date('Y-m-d H:i:s'),
          );
          $wallet_txn_id = $this->transactions_model->addNewWalletTransaction($wallet_trans_info);
          //update balance into users table                           
          $updateBalQry = $this->rechargeApi_model->updateUserBalance($user_id,$updatedBalance);
          //update balance after deduction end
          
          $trans_info = array(
            'transaction_id' =>"0",
            'transaction_status' =>"PENDING", 
            'service_id' => $service_id, 
            'operator_id'=>$operator_id,
            'api_id' => $api_details->api_id,
            'trans_date' => date("Y-m-d H:i:s"),
            'order_id' => $this->isValid($order_id),  
            'mobileno' =>$data['sender_mobile_number']."", 
            'user_id' => $this->isValid($user_id),          
            'total_amount' => $this->isValid($amount),
            'charge_amount' =>"0",
            'transaction_type' => $data['transaction_type'], //add
            // 'bank_transaction_id' =>$result['result']['paytmOrderId'], //add
            'bank_transaction_id' =>"", //add
            'imps_name' =>$receipt_details->recipient_name."", //add
            'recipient_id' =>$data['recipient_id'], //add
            'charges_tax' =>"0", //add
            'commission' =>"0", //add
            'commission_tax' =>"0", //add
            'commission_tds' =>"0", //add
            'debit_amount' =>"0",
            'balance' =>"0",
            'order_status' => "PENDING",
            'transaction_msg'=>"",
            'CCFcharges'=>$ccf,
            'Cashback'=>$cashback,
            'TDSamount'=>$TDS,
            'PayableCharge'=>$PayableCharge,
            'FinalAmount'=>$totalAmount,
            'request_amount'=>$amount,
            'updated_on'=>date('Y-m-d H:i:s'),
            );
            $txn_id = $this->transactions_model->addNewTransaction($trans_info);

          $paisa = $amount * 100;
          $post_req = '{
                        "account_number": "'.$api_details->account_no.'",
                        "fund_account_id": "'.$receipt_details->razorpay_fund_acc_id.'",
                        "amount": '.$paisa.',
                        "currency": "INR",
                        "mode": "'. $data['transaction_type'].'",
                        "purpose": "payout",
                        "queue_if_low_balance": true,
                        "reference_id": "'.$order_id.'"
                      }';
          $api_info = array(
              'service_id' => $service_id."", 
              'api_id' => $api_details->api_id."", 
              'api_name' => $api_details->api_name."",  
              'api_method' => "doFundTransfer",
              'api_url' => $api_details->api_url."payouts", 
              'order_id' => $order_id."", 
              'user_id' => $user_id."",  
              'request_input' => json_encode($post_req)."",
              'request' => json_encode($post_req)."",         
            //   'response' => json_encode($result)."",
              'access_type' => (isset($data['access_type'])) ? $data['access_type'] : 'DEFAULT' ,
              'updated_on'=>date('Y-m-d H:i:s'),
         );
          $api_log_id = $this->apiLog_model->addApiLogDetails($api_info);
          //From Here Cash Free Integration Starts
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
        
        
          
         
        //  //Get Beneficiary ID
        //   $url="https://payout-api.cashfree.com/payout/v1/getBeneId?bankAccount=20188152381&ifsc=SBIN0060471";
        //   $ch = curl_init($url);
        //   curl_setopt($ch, CURLOPT_GET, true);
        //   curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization:Bearer " . $token)); 
        //   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        //   $result_json = curl_exec($ch);
          
        //   curl_close($ch);
          //$result =  json_decode($result_json, true); 
          //print_r($result_json);
          
          //Transfer Amount
          
          $Params["beneId"]= $data['cfree_beneficiaryid'];
          $Params["transferId"]= "TN".rand(1111,9999);
          $Params["amount"] = 1;
        
          $post_data = json_encode($Params, JSON_UNESCAPED_SLASHES);
          
          $url="https://payout-api.cashfree.com/payout/v1/requestTransfer";
          $ch = curl_init($url);
          curl_setopt($ch, CURLOPT_POST, true);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
          curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization:Bearer " . $token)); 
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
          $result_json = curl_exec($ch);
          
          curl_close($ch);
          $result =  json_decode($result_json, true); 
          
        //end
         
          //From Here Cash Free Integration Ends
          $update_api_dtls_arr =  array('response' => json_encode($result), 'updated_on'=>date('Y-m-d H:i:s') );
          $update_api_log = $this->db->where('order_id', $order_id)->update('tbl_apilog_dts', $update_api_dtls_arr );
          
          $pending_arr = array('queued', 'pending', 'processing');
          $success_arr = array('processed');
          $failed_arr = array('rejected', 'cancelled');
          $oStatus = '';
          if (in_array($result['status'], $pending_arr)){
            $oStatus = 'PENDING';
          }elseif (in_array($result['status'], $success_arr)) {
            $oStatus = 'SUCCESS';
          }elseif ($result['status'] == 'reversed') {
            $oStatus = 'REFUNDED';
          }elseif (in_array($result['status'], $failed_arr)) {
            $oStatus = 'FAILED';
          }
           
           if($result['id'] && ((in_array($result['status'], $pending_arr)) || (in_array($result['status'], $success_arr)))) {
                //update balance based on api id in api setting table developed by susmitha start

              $trans_record = [];
              $oStatus = $result['status'];
              while ($oStatus=='PENDING') {
                  $trans_record = $this->db->select('*')->from('tbl_transaction_dtls')->where('order_id', $order_id)->get()->row();
                  // $trans_record =$this->moneyTransferApi_model->getdata_where('tbl_transaction_dtls',array('order_id'=> $order_id ));
                  if ($trans_record->order_status != 'PENDING' ) {
                      // $oStatus = $trans_record[0]->order_status;
                      $oStatus = $trans_record->order_status;
                      break;
                  }
              }

              if ( $oStatus == 'FAILED') {
                  $failed_resp = $this->failedPaytmTransfer($trans_info, $trans_record->order_status );
                  $response = array(
                    'status' => "false",
                    'msg' => 'failed',
                    'result' => $trans_record
                  );   
                  echo json_encode($response) ;

                  exit;
              }

              $currentapibal=0;
              $data = array('balance'=>$currentapibal);
              $this->apiLog_model->update_api_amount($data,$api_details->api_id);
              //update balance based on api id in apisetting table developed by susmitha end  
              //update sender avaland used limit start
              $sender_det  =array("sender_mobile_number"=>$mobile);
              $sender_details =$this->moneyTransferApi_model->getdata_where('tbl_sender_dts',$sender_det);
              $available_limit=($sender_details[0]->Upi_available_limit)-($amount);
              $used_limit=($sender_details[0]->Upi_used_limit)+($amount);
              $sender_detupdate=array("Upi_available_limit"=>$available_limit,
                                    "Upi_used_limit"=>$used_limit);
              //print_r($sender_detupdate);
              $this->db->where('sender_mobile_number',$mobile);
              $this->db->update('tbl_sender_dts',$sender_detupdate);
            //update sender avaland used limit end
              $recipent_ver  =array("recipient_id"=>$data['recipient_id'],"is_verified"=>"Y");
              $benificiary_details =$this->moneyTransferApi_model->getdata_where('tbl_dmt_benificiary_dtls',$recipent_ver);
              //print_r($benificiary_details);
              if(empty($benificiary_details)){
                $updatebeni=array("is_verified"=>"Y","verified_name"=>$result['result']['beneficiaryName']);
              $where=array('recipient_id',$data['recipient_id']); 
              $this->db->where($where);
              $this->db->update('tbl_dmt_benificiary_dtls',$updatebeni);
              }
              
              $money_re=$this->transactions_model->getmoneyreport($txn_id);
              // print_r($money_re);
              $response = array(
                'status' => "true",
                'msg' => "Success",
                'result' => $result,
                'money'=>$money_re
              );

              //send sms 
              $digits = 5;
              $rand=rand(pow(10, $digits-1), pow(10, $digits)-1);
              //echo "$rand and " . ($startDate + $rand);
              $username="anandkaushal.in";
              $password="Budd789";
              $msisdn=$mobile;
              $sid="SRLSHD";
            //by vishal
              // $msg="Transaction Done TID: ".$txn_id." -  order id ".$money_re->order_no.", Amt : Rs ".$amount." Fees: 1 % Name :".$money_re->name."  A/c : ".$money_re->bank_account_number." IFSC: ".$money_re->ifsc." PAYMAMA - ";
              //$msg="".$rand."%20is%20your%20verification%20code.%20Regards%20Saral%20Shaadi.";
              $url="http://cloud.smsindiahub.in/vendorsms/pushsms.aspx?user=".$username."&password=".$password."&msisdn=".$msisdn."&sid=".$sid."&msg=".$msg."&fl=0&gwid=2";
            //  $ch = curl_init();  
            //  curl_setopt($ch,CURLOPT_URL,$url);
            //  curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
            //  $output=curl_exec($ch);
            //  curl_close($ch);
            $msg="TID: ".$order_id." Amt : ".$amount." Fees: 1 %, Name : ".$money_re->name." UPI : ".$benificiary_details->bank_account_number." Mobile ".$benificiary_details->recipient_mobile_number." www.paymamaapp.in PAYMAMA";
              $temp_id = "1007821138683202361"; 
            $sms_output = $this->sendBulkSMS($msisdn, $msg, $temp_id); 
            //by vishal End
            //send sms 
            //commission wallet txn begin
              if(is_numeric($role_id) && intval($role_id) <= 4){                
                $walletUserID = $user_id;
                $walletRoleID = $role_id;
                $isUserBalanceUpdated = false;
                
                for($i=$walletRoleID;$i>=1;$i--){
                  if($i==3 || $i==4  || $i==7){
                    $isUserBalanceUpdated = true;
                    $userParentID = $this->rechargeApi_model->getUserParentID($walletUserID);
                    if ($isUserBalanceUpdated && $userParentID && ( $userParentID->roleId==3||$userParentID->roleId==4||$userParentID->roleId==7) ) {
                      $walletUserID = $userParentID->userId;
                      $walletRoleID = $userParentID->roleId;
                      $updatedBalance = $userParentID->wallet_balance;
                    }
                    continue;
                  }
                  $walletAmt = 0;
                  $walletBal = 0;    
                  $distds='';             
                  $userParentID = $this->rechargeApi_model->getUserParentID($walletUserID);
                  if ($isUserBalanceUpdated && $userParentID) {
                    $walletUserID = $userParentID->userId;
                    $walletRoleID = $userParentID->roleId;
                    $updatedBalance = $userParentID->wallet_balance;
                  }
                  /*echo "Wallet User ID:".$walletUserID." Role ID:".$walletRoleID."<br/>";
                  echo "Retailer Commision:".$retailer_commission."Dist.:".$distributor_commission."Admin.:".$admin_commission."<br/>";*/
                  // if($walletRoleID == 4){ //Retailer
                  //   $walletAmt = $retailer_commission;
                  //   $walletBal = $updatedBalance+$retailer_commission;
                  // }
                  /*else if($walletRoleID == 3){ //FOS
                    $walletAmt = $distributor_commission;
                    $walletBal = $updatedBalance+$distributor_commission;
                  }*/ if($walletRoleID == 2){ //Distributor
                    //$walletAmt = $distributor_commission;
                    $ds = $distributor_commission;
                      $apptds    =$this->rechargeApi_model->getTDS();
                      $distds=$ds*($apptds->value/100);
                      $walletAmt=$ds-$distds; 
                    $walletBal = $updatedBalance+$distributor_commission;
                  }else if($walletRoleID == 1){ //Admin
                    $walletAmt = $admin_commission;
                    $walletBal = $updatedBalance+$admin_commission;
                  }
                  if(is_numeric($walletAmt) && is_numeric($walletBal)){
                    $transType = "CREDIT";
                    if($walletAmt < 0){
                      $transType = "DEBIT";
                    }
                    $wallet_trans_info = array(
                      'service_id' => $service_id,
                      'order_id' => $this->isValid($order_id), 
                      'user_id' => $walletUserID, 
                      'operator_id' => $operator_id,
                      'api_id' => $api_details->api_id,
                      'transaction_status' => $result['status'],
                      'transaction_type' => $transType,
                      'payment_type' => "COMMISSION",
                      'payment_mode' => "Commission by Money Transfer",
                      'transaction_id' => "",               
                      'trans_date' => date("Y-m-d H:i:s"),  
                      'total_amount' => abs($walletAmt),
                      'charge_amount' => "0.00",
                      'balance' => $walletBal,
                      'TDSamount'=>$distds,
                      'updated_on'=>date('Y-m-d H:i:s'),
                    );
                    $wallet_txn_id = $this->transactions_model->addNewWalletTransaction($wallet_trans_info);
                    //update balance into users table                           
                    $updateBalQry = $this->rechargeApi_model->updateUserBalance($walletUserID,$walletBal);
                    //update balance after deduction end
                  }
                  $isUserBalanceUpdated = true;
                }
              }
              //commission wallet txn end
          } else{
          $trans_info = array(
            'transaction_id' =>"",
            'transaction_status' =>$result['status'], 
            'service_id' => $service_id, 
            'operator_id'=>$operator_id,
            'api_id' => $api_details->api_id,
            'trans_date' => date("Y-m-d H:i:s"),
            'order_id' => $this->isValid($order_id),  
            'mobileno' => $this->isValid($data['sender_mobile_number']), 
            'user_id' => $this->isValid($user_id),          
            'total_amount' => $amount,
            'charge_amount' =>"",
            'transaction_type' => $data['transaction_type'], //add
            'bank_transaction_id' =>"", //add
            'imps_name' => $receipt_details->recipient_name."", //add
            'recipient_id' =>$receipt_details->recipient_id, //add
            'charges_tax' =>"", //add
            'commission' =>"", //add
            'commission_tax' =>"", //add
            'commission_tds' =>"", //add
            'debit_amount' =>"",
            'balance' => "",
            'order_status' => $this->isValid("FAILED"),
            'transaction_msg'=>$result['statusMessage'],
            'updated_on'=>date('Y-m-d H:i:s'),
            'request_amount'=>$amount,
          );
          $txn_id = $this->transactions_model->addNewTransaction($trans_info);
          $userbalance = $this->rechargeApi_model->getUserBalance($data['user_id']);
          $wallet_balance=$userbalance->wallet_balance;
          $updatedBalance = $wallet_balance+$totalAmount; 
          //insert DEBIT txn into tbl_wallet_trans_dtls table
          $wallet_trans_info = array(
            'service_id' =>$service_id,
            'order_id' => $this->isValid($order_id), 
            'user_id' => $user_id, 
            'operator_id' => $operator_id,
            'api_id' => $api_details->api_id,
            'transaction_status'=>$result['status'],
            'transaction_type'=>"CREDIT",
            'payment_type' => "SERVICE",
            'payment_mode' => "PAID FOR UPI, UPI ID ".$benificiary_details->bank_account_number.", AMOUNT ".$totalAmount.", CHARGE ".$PayableCharge,
            'transaction_id' =>"",               
            'trans_date' => date("Y-m-d H:i:s"),  
            'total_amount' => $totalAmount,
            'charge_amount' => "0.00",
            'balance' => $updatedBalance,
            'updated_on'=>date('Y-m-d H:i:s'),
          );
          $wallet_txn_id = $this->transactions_model->addNewWalletTransaction($wallet_trans_info);
          //update balance into users table                           
          $updateBalQry = $this->rechargeApi_model->updateUserBalance($user_id,$updatedBalance);
          //update balance after deduction end
          $response = array(
            'status' => "false",
            'msg' => $result['statusMessage'],
            'result' => $result
          );        
        }
         
      }
      else{
      
        $response = array(
          'status' => "false",
          'msg' => "API implementation under process. Please contact administrator.",
          'result' => null
        );
        echo json_encode($response);
        exit;
      }
    }
    else{

      $response = array(
        'status' => "false",
        'msg' => "Invalid Request",
        'result' => null
      );
    }
    echo json_encode($response);
    $this->rechargeApi_model->send_telegram_api($order_id);
    exit;
  }

  
  public function resent_otp_fundtransfer(){
    
    $data =  json_decode(file_get_contents('php://input'), true);

    $this->authenticateUser($data); 

    if(!empty($data['operatorID']) && !empty($data['order_id'])){
      //get service details by operator id begin
      $operator_id = $data['operatorID'];
      $serviceDtl = $this->operatorApi_model->getServiceDetailsByOperatorID($operator_id);
      if($serviceDtl){
        $service_id = $serviceDtl[0]->service_id;
      }else{
        $response = array(
          'status' => "false",
          'msg' => "Invalid Service",
          'result' => null
        );
        echo json_encode($response);
        exit;
      }
      //get service details by operator id end 

      //check active api for operator begin
      $api_details = $this->rechargeApi_model->getActiveApiDetails($operator_id,$service_id,"");
      if(!$api_details){
        $response = array(
          'status' => "false",
          'msg' => "API details not configured for operator.Please contact administrator.",
          'result' => null
        );
        echo json_encode($response);
        exit;
      }
      //check active api for operator end

      if($api_details->api_id == "8"){ //go payment start
         $trans = $this->transactions_model->getone('tbl_transaction_dtls','order_id',$data['order_id']);

        $url = $api_details->api_url.'resendRefundOTP';
        $post_data = array(
          'partner_code' => $api_details->api_token,
          'mobile_number' => $api_details->username,
          'password' => base64_decode($api_details->password),
          'transaction_id'=>$trans->transaction_id,
          
        );        
        $parameters = json_encode($post_data);
        $result = $this->doCurlCall($url,$parameters);  
        $result =  json_decode($result, true);
        curl_close($ch);
        
        if($result['response_code'] == "0"){
          $response = array(
            'status' => "true",
            'msg' => "Success",
            'result' => $result
          );
        }else{
          $response = array(
            'status' => "false",
            'msg' => $result['response_description'],
            'result' => $result
          );
        }
      }//go payment  end
  }
  else{
      $response = array(
        'status' => "false",
        'msg' => "Invalid Request",
        'result' => null
      );
    }

    echo json_encode($response);

    exit;  
 }
 public function  refundFundTransfer()
 {
  $data =  json_decode(file_get_contents('php://input'), true);

    $this->authenticateUser($data); 

    if(!empty($data['operatorID']) && !empty($data['order_id']) && !empty($data['otp'])){
      //get service details by operator id begin
      $operator_id = $data['operatorID'];
      $serviceDtl = $this->operatorApi_model->getServiceDetailsByOperatorID($operator_id);
      if($serviceDtl){
        $service_id = $serviceDtl[0]->service_id;
      }else{
        $response = array(
          'status' => "false",
          'msg' => "Invalid Service",
          'result' => null
        );
        echo json_encode($response);
        exit;
      }
      //get service details by operator id end 

      //check active api for operator begin
      $api_details = $this->rechargeApi_model->getActiveApiDetails($operator_id,$service_id,"");
      if(!$api_details){
        $response = array(
          'status' => "false",
          'msg' => "API details not configured for operator.Please contact administrator.",
          'result' => null
        );
        echo json_encode($response);
        exit;
      }
      //check active api for operator end

      if($api_details->api_id == "8"){ //go payment start
        $trans = $this->transactions_model->getone('tbl_transaction_dtls','order_id',$data['order_id']);
        $url = $api_details->api_url.'refundFundTransfer';
        $post_data = array(
          'partner_code' => $api_details->api_token,
          'mobile_number' => $api_details->username,
          'password' => base64_decode($api_details->password),
          'transaction_id'=>$trans->transaction_id,
          'otp'=>$data['otp'],
          
        );        
        $parameters = json_encode($post_data);
        $result = $this->doCurlCall($url,$parameters);  
        $result =  json_decode($result, true);
        curl_close($ch);
 

        if($result['response_code'] == "0" && $result['transaction_status']=="R"){
                 
          $wallet = $this->transactions_model->getall('tbl_wallet_trans_dtls','order_id',$data['order_id']);
         $trans=array('order_status'=>"REFUNDED",'transaction_status'=>'R','transaction_msg'=>$result['response_description']);
        $this->transactions_model->update('tbl_transaction_dtls','order_id',$data['order_id'],$trans);
        //print_r($wallet);
        $scount=count($wallet);
        for($i= 0 ; $i < $scount; $i++){
          
          $userbalance = $this->rechargeApi_model->getUserBalance($wallet[$i]->user_id);
          $wallet_balance=$userbalance->wallet_balance;
          $updatedBalance = $wallet_balance+$wallet[$i]->total_amount; 
          //insert DEBIT txn into tbl_wallet_trans_dtls table
          
          $wallet_trans_info = array(
            'service_id' =>$wallet[$i]->service_id,
            'order_id' =>$wallet[$i]->order_id,
            'group_id'   =>$wallet[$i]->group_id,
            'user_id' => $wallet[$i]->user_id, 
            'operator_id' => $wallet[$i]->operator_id,
            'api_id' => $wallet[$i]->api_id,
            'transaction_status' =>($wallet[$i]->transaction_status!='')?$wallet[$i]->transaction_status:'NULL',
            'transaction_type' =>($wallet[$i]->transaction_type=="DEBIT")?"CREDIT":'DEBIT',
            'payment_type' => "SERVICE",
            'payment_mode' => $wallet[$i]->payment_mode,
            'transaction_id' =>$trans->transaction_id,               
            'trans_date' => date("Y-m-d H:i:s"),  
            'total_amount' =>($wallet[$i]->total_amount!='')?$wallet[$i]->total_amount:'0',
            'charge_amount' => "0.00",
            'balance' =>($updatedBalance!='')?$updatedBalance:'0',
             'CCFcharges'=>($wallet[$i]->CCFcharges!='')?$wallet[$i]->CCFcharges:'0',
             'Cashback'=>($wallet[$i]->Cashback!='')?$wallet[$i]->Cashback:'0',
             'TDSamount'=>($wallet[$i]->TDSamount!='')?"-".$wallet[$i]->TDSamount:'0',
             'PayableCharge'=>($wallet[$i]->PayableCharge!='')?$wallet[$i]->PayableCharge:'0',
             'FinalAmount'=>($wallet[$i]->FinalAmount!='')?$wallet[$i]->FinalAmount:'0',
             'updated_on'=>date('Y-m-d H:i:s')
             );
          
         
          $wallet_txn_id = $this->transactions_model->addNewWalletTransaction($wallet_trans_info);
          
          //update balance into users table                           
          $updateBalQry = $this->rechargeApi_model->updateUserBalance($wallet[$i]->user_id,$updatedBalance);

        }
         
          $response = array(
            'status' => "true",
            'msg' => "Success",
            'result' => $result
          );
          
         
        }else{
          $response = array(
            'status' => "false",
            'msg' => $result['response_description'],
            'result' => $result
          );
        }
      }//go payment  end
  }
  else{
      $response = array(
        'status' => "false",
        'msg' => "Invalid Request",
        'result' => null
      );
    }

    echo json_encode($response);

    exit; 
 }
 public function getFundTransferStatus(){
    $data =  json_decode(file_get_contents('php://input'), true);
    $this->authenticateUser($data); 
    if(!empty($data['operatorID']) && !empty($data['order_id'])){
      //get service details by operator id begin
      $operator_id = $data['operatorID'];
      $serviceDtl = $this->operatorApi_model->getServiceDetailsByOperatorID($operator_id);
      if($serviceDtl){
        $service_id = $serviceDtl[0]->service_id;
      }else{
        $response = array(
          'status' => "false",
          'msg' => "Invalid Service",
          'result' => null
        );
        echo json_encode($response);
        exit;
      }
      //get service details by operator id end 

      //check active api for operator begin
      $api_details = $this->rechargeApi_model->getActiveApiDetails($operator_id,$service_id,"");
      if(!$api_details){
        $response = array(
          'status' => "false",
          'msg' => "API details not configured for operator.Please contact administrator.",
          'result' => null
        );
        echo json_encode($response);
        exit;
      }
      //check active api for operator end

      if($api_details->api_id == "8"){ //go payment start
        $trans = $this->transactions_model->getone('tbl_transaction_dtls','order_id',$data['order_id']);
        if($trans->transaction_status=="Q"){ 
        $url = $api_details->api_url.'getFundTransferStatus';
        $post_data = array(
          'partner_code' => $api_details->api_token,
          'mobile_number' => $api_details->username,
          'password' => base64_decode($api_details->password),
          'transaction_id'=>$trans->transaction_id,
          'reference_number'=>$data['order_id'],
          );        
        $parameters = json_encode($post_data);
        $result = $this->doCurlCall($url,$parameters);  
        $result =  json_decode($result, true);
        //print_r($result);
        if($result['transaction_status']=="Q"){
          $status="Pending";
        }else if($result['transaction_status']=="C"){
         $status="Success";
        }
        else if($result['transaction_status']=="T"){
         $status="Refund Pending";
        }
        $trans=array('order_status'=>$status,'transaction_status'=>$result['transaction_status'],'transaction_msg'=>$result['response_description']);
        $this->transactions_model->update('tbl_transaction_dtls','order_id',$data['order_id'],$trans);
        $response = array(
          'status' => "true",
          'msg' => $result['response_description'],
          'result' => $result
        );
        curl_close($ch);
      }else{
        $response = array(
          'status' => "false",
          'msg' => "Invalid Request.Please contact administrator.",
          'result' => null
        );
        echo json_encode($response);
        exit;
      }
      }
    }
    echo json_encode($response);
        exit;
 }
  public function isValid($str){
    if(isset($str) && $str != null)
      return $str;
    else
      return '';
  }
  public function test(){
      $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_PORT => "25004",
  CURLOPT_URL => "https://staging.eko.in:25004/ekoapi/v1/user/onboard",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "PUT",
  CURLOPT_POSTFIELDS => 'initiator_id=9962981729&pan_number=BFUPM3499K&mobile=9123354235&first_name=Tina&last_name=Chawla&email=a@gmail.com&residence_address={"line": "Eko India","city":"Gurgaon","state":"Haryana","pincode":"122002"}&dob=1992-05-10&shop_name=Akanksha Stores',
  CURLOPT_HTTPHEADER => array(
    "Cache-Control: no-cache",
    "Content-Type: application/x-www-form-urlencoded",
    "developer_key: becbbce45f79c6f5109f848acd540567",
    "secret-key: MC6dKW278tBef+AuqL/5rW2K3WgOegF0ZHLW/FriZQw=",
    "secret-key-timestamp: 1516705204593"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}
  }

 

  public function doCurlCall($url,$parameters){
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 36000);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
    $result = curl_exec($ch);
    if(curl_errno($ch)){
      $error_msg = curl_error($ch);
      $response = array(
        'status' => "false",
        'msg' => "Invalid Service Call - "+$error_msg ,
        'result' => null
      );
      echo json_encode($response);
      exit;
    }
    return $result;
  }
 
  public function doCurlCallEko($url,$parameters,$requestType){  
    $api=$this->apiLog_model->get_eko_apikey();
     $developer_sekey=$api->api_secretkey;
     $api_token=$api->api_token;
     $key_result=$this->getsecretkey_timestramp($developer_sekey);
     $key=json_decode($key_result,true);
     $secret_key=$key['secret_key'];
     $secret_key_timestamp=$key['secret_key_timestamp'];
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_PORT => "25004",
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_POSTFIELDS => $parameters,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => $requestType,
    CURLOPT_HTTPHEADER => array(
        "Content-Type:application/x-www-form-urlencoded",
        "cache-control: no-cache",
        "developer_key: ".$api_token."",
        "secret-key: ".$secret_key."",
        "secret-key-timestamp: ".$secret_key_timestamp.""
      ),
    ));

    $result = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if($err){
      $error_msg = $err;
      $response = array(
        'status' => "false",
        'msg' => "Invalid Service Call - "+$error_msg ,
        'result' => null
      );
      echo json_encode($response);
      exit;
    }
    return $result;
  }

  public function writeTxnOrderID($order_id){
    write_file('admin/txn_order_id.txt', $order_id."");
  }
  public function writeTxnGroupID($group_id){
    write_file('admin/txn_group_id.txt', $group_id."");
  }
  //eko getsecret key and keytimestramp
  public function getsecretkey_timestramp($skey){
    // Initializing key in some variable. You will receive this key from Eko via email
    $key = $skey;
    // Encode it using base64
    $encodedKey = base64_encode($key);
   // Get current timestamp in milliseconds since UNIX epoch as STRING
   // Check out https://currentmillis.com to understand the timestamp format
   $secret_key_timestamp = "".round(microtime(true) * 1000);
   // Computes the signature by hashing the salt with the encoded key 
   $signature = hash_hmac('SHA256', $secret_key_timestamp, $encodedKey, true);
  // Encode it using base64
   $secret_key = base64_encode($signature);
   $key=array('secret_key'=>$secret_key,'secret_key_timestamp'=>$secret_key_timestamp);
    return json_encode($key);
  }
   //eko curl developed by susmitha start
  public function ekocurl($url,$header,$type){
       $curl = curl_init();
       curl_setopt_array($curl, array(
        CURLOPT_PORT => "25004",
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => $type,
        CURLOPT_HTTPHEADER => $header,
      ));

      $response = curl_exec($curl);
      $err = curl_error($curl);

      curl_close($curl);
      
      return $response;
  }
  //eko curl developed by susmitha end
  //split amount each for transation
  public function split_amount($amount){
    $x=$amount;
    $splitarr=array();
    while($x >0) {
        if ($x>5000) {
            array_push($splitarr,"5000");
            $newx=$x-5000;
            $x=$newx;
        }
        else{
            $newx=$x;
            array_push($splitarr,$newx);
            $x=$newx;
            break;
        }
    }
    return json_encode($splitarr);
  }

  public function get_order_id(){
          $last_order_id = file_get_contents("admin/txn_order_id.txt");              
          $sno_order_id  =intval($last_order_id)+1;
          $order_id ="SP".$sno_order_id;
          $clientres=$this->transactions_model->check_order_id($order_id);
         if(!empty($clientres)){
          $this->writeTxnOrderID($sno_order_id);
            $this->get_order_id();
           
           }
           else{
            $order=array('order_id'=>$order_id,'sno_order_id'=>$sno_order_id);
            $this->writeTxnOrderID($sno_order_id);
            return json_encode($order);
           }

  }
  public function reset_limit_paytm() 
   {
      $paytm=$this->moneyTransferApi_model->get_singlebyid('tbl_application_details','id',13);
      $update=array("available_limit"=>$paytm->value,
                    "used_limit"     =>0);
      $this->db->where('api_name',"paytm");
      $this->db->update('tbl_sender_dts',$update);
      if($this->db->affected_rows() > 0 ) {
        echo "Reset Successfully";
      }else{
        echo "Already Reseted";
      }
   }
   public function check_paytm_status($order_id,$merchant_key,$mid,$api_trn_status_url){
      $paytmParams = array();

      $paytmParams["orderId"] = $order_id;

      $post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);

      /*
      * Generate checksum by parameters we have in body
      * Find your Merchant Key in your Paytm Dashboard at https://dashboard.paytm.com/next/apikeys 
      */
      $checksum = PaytmChecksum::generateSignature($post_data,$merchant_key);

      $x_mid      = $mid;
      $x_checksum = $checksum;

      /* for Staging */
      $url = $api_trn_status_url;

      /* for Production */
      // $url = "https://dashboard.paytm.com/bpay/api/v1/disburse/order/query";

      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "x-mid: " . $x_mid, "x-checksum: " . $x_checksum)); 
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
      curl_close($ch);
       $response = curl_exec($ch);
       $err = curl_error($ch);
       return $response;
   }
   public function hyptocurl($url,$post,$api_token){

         $ch = curl_init($url);
        # Setup request to send json via POST.
        $payload = json_encode($post);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:'.$api_token.''));
        # Return response instead of printing.
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        # Send request.
         $result = curl_exec($ch);
        
        curl_close($ch);
        write_file('admin/hypto_status_response.txt', $result);
        return $result;
        //$result =  json_decode($result, true);
   }
   public function getcurl_with_header($url,$headers){
	 // $api_token='e51f797c-b5ed-48fb-84b2-1ef78a0b8f92';
	 // $headers=array('Content-Type:application/json','Authorization:'.$api_token.'');
	 // $url='https://partners.hypto.in/api/transfers/status/DEMOREFNUM0123';
	 $ch = curl_init();
     curl_setopt($ch, CURLOPT_URL,$url);
	 curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	 curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	  # Return response instead of printing.
     curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	 $result_json =curl_exec($ch);
	 // $resultsta =  json_decode($result_json, true);
	 // $char = "COMPLETED";
	 return $result_json;
	  // if($resultsta['data']['status']==$char){
	  // 	return $result_json;
	  // }else{
	  // 	$this->getcurl_with_header($url,$headers);
	  // }
	 curl_close($ch);
     
       

   }
   public function get_sender_by_upiID(){
     $data =  json_decode(file_get_contents('php://input'), true);
     $this->authenticateUser($data);

     if($data['upi_id']!=''){
     	 $sender=$this->moneyTransferApi_model->get_senderdts_byaccno($data['account_number']);
      // echo "<pre>";
      // print_r($sender);

      $response = array(
        'status' => "true",
        'msg' => "Success",
        'result' =>$sender
      );
     }else{
     	$response = array(
        'status' => "false",
        'msg' => "Please Provide Account Number",
        
      );
     }
     
      echo json_encode($response);
      
   }

   public function sendBulkSMS($mobile_no, $msg, $temp_id){
    $smsDtls = $this->db->select('*')->from('tbl_sms_gateway_settings')->where('alias', 'bulk_sms')->get()->row();
    $url = "https://www.bulksms.co/sendmessage.php?user=".$smsDtls->username."&password=".$smsDtls->password."&mobile=".$mobile_no."&message=".urlencode($msg)."&sender=SMAPAY&type=3&template_id=".$temp_id."";
         
    $ch = curl_init();  
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    $output=curl_exec($ch);
    curl_close($ch);
    return $output;
   }
   public function failedPaytmTransfer($trans_info, $paytm_status){
    $userbalance = $this->rechargeApi_model->getUserBalance($trans_info['user_id']);
    $wallet_balance=$userbalance->wallet_balance;
    $updatedBalance = $wallet_balance+$trans_info['FinalAmount'];

    
    $wallet_trans_info = array(
                                'service_id' =>$trans_info['service_id'],
                                'order_id' => $trans_info['order_id'], 
                                'user_id' => $trans_info['user_id'], 
                                'operator_id' => $trans_info['operator_id'],
                                'api_id' => $trans_info['api_id'],
                                'transaction_status'=> $paytm_status,
                                'transaction_type'=>"CREDIT",
                                'payment_type' => "SERVICE",
                                'payment_mode' => "REFUND FOR UPI, AMOUNT ".$trans_info['FinalAmount'].", CHARGE ".$trans_info['PayableCharge'],
                                'transaction_id' =>"",               
                                'trans_date' => date("Y-m-d H:i:s"),  
                                'total_amount' => $trans_info['FinalAmount'],
                                'charge_amount' => "0.00",
                                'balance' => $updatedBalance,
                                'updated_on'=>date('Y-m-d H:i:s'),
                            );
                            
          $wallet_txn_id = $this->transactions_model->addNewWalletTransaction($wallet_trans_info);
          //update balance into users table                           
          $updateBalQry = $this->rechargeApi_model->updateUserBalance($trans_info['user_id'],$updatedBalance);
          //update balance after deduction end

          $trans_arr = array( 'transaction_status' =>$paytm_status,'order_status' => $paytm_status, 'updated_on'=>date('Y-m-d H:i:s') );
          $this->db->where('order_id', $trans_info['order_id'])->update('tbl_transaction_dtls', $trans_arr);

          return true;
  }
  
  public function payout_Rezorpay($post_req,$api_details){
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $api_details->api_url.'payouts',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>$post_req,
        CURLOPT_USERPWD => $api_details->key_id . ":" . $api_details->api_secretkey,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
        ),
    ));
    
    $response = curl_exec($curl);
    
    curl_close($curl);
    // echo $response;
    return json_decode($response, true);
   }
   
   public function failedRezorpayTransfer($trans_info, $rasor_status){

    $userbalance = $this->rechargeApi_model->getUserBalance($trans_info['user_id']);
    $wallet_balance=$userbalance->wallet_balance;
    $updatedBalance = $wallet_balance+$userbalance['totalAmount']; 
    //insert DEBIT txn into tbl_wallet_trans_dtls table
    $wallet_trans_info = array(
                                  'service_id' =>$trans_info['service_id'],
                                  'order_id' => $trans_info['order_id'], 
                                  'user_id' => $trans_info['user_id'], 
                                  'operator_id' => $trans_info['operator_id'],
                                  'api_id' => $trans_info['api_id'],
                                  'transaction_status'=> $rasor_status,
                                  'transaction_type'=>"CREDIT",
                                  'payment_type' => "SERVICE",
                                  'payment_mode' => "REFUND FOR UPI, AMOUNT ".$trans_info['FinalAmount'].", CHARGE ".$trans_info['PayableCharge'],
                                  'transaction_id' =>"",               
                                  'trans_date' => date("Y-m-d H:i:s"),  
                                  'total_amount' => $trans_info['FinalAmount'],
                                  'charge_amount' => "0.00",
                                  'balance' => $updatedBalance,
                                  'updated_on'=>date('Y-m-d H:i:s'),
                              );
    $wallet_txn_id = $this->transactions_model->addNewWalletTransaction($wallet_trans_info);
    //update balance into users table                           
    $updateBalQry = $this->rechargeApi_model->updateUserBalance($trans_info['user_id'],$updatedBalance);
    return true;
   }
   
  public function createFundAccount(){  
      $data =  json_decode(file_get_contents('php://input'), true);
      $this->authenticateUser($data); 
      
      $user=$this->moneyTransferApi_model->get_singlebyid('tbl_sender_dts','sender_mobile_number',$data['sender_mobile_number']);
      if(empty($user)){
          $response = array(
                            'status' => "false",
                            'msg' => "User mobile Not Registered.",
                            'result' => null
                          );
          echo json_encode($response);
          exit;
      }else {
        
      
          $get_recipient = $this->db->select('*')->from('tbl_dmt_benificiary_dtls')->where('recipient_id', $data['recipient_id'])->get()->row();

          if (!empty($get_recipient)) {


            $recipient_data['recipient_name'] =  $get_recipient->recipient_name;
            $recipient_data['ifsc'] =  $get_recipient->ifsc;
            $recipient_data['bank_account_number'] =  $get_recipient->bank_account_number;
            $resp_FundAcc = $this->fundAccount_Rezorpay($recipient_data,  $user->razorpay_contact_id);
            if ( isset($resp_FundAcc['id'])) {
              $this->db->where([ 'recipient_id' => $data['recipient_id'] ]);
              $this->db->update('tbl_dmt_benificiary_dtls', ['razorpay_fund_acc_id' => $resp_FundAcc['id']]);
              if($this->db->affected_rows() > 0 ) {
                $response = array(
                                    'status' => "true",
                                    'msg' => "Recipent Fund Accountis Created",
                                    'result' =>$resp_FundAcc,
                                  );
                  echo json_encode($response);
                  exit;
              }
            }
            $response = array(
                              'status' => "false",
                              'msg' => "Recipent Fund Account Not Created",
                              'result' =>"",
                            );
            echo json_encode($response);
            exit;
          }else{
                $response = array(
                                    'status' => "false",
                                    'msg' => "Recipent Details Not avalible",
                                    'result' =>"",
                                  );
                echo json_encode($response);
                exit;
          }

      }
  }
}