<?php
header("Access-Control-Allow-Origin", "https://paymamaapp.in");
header("Access-Control-Allow-Origin", "https://devserver3.paymamaapp.in");
header("Access-Control-Allow-Origin", "https://www.devserver3.paymamaapp.in");
header("Access-Control-Allow-Origin", "https://www.paymamaapp.in");
header("Access-Control-Allow-Methods", "DELETE, POST, GET, OPTIONS");
header("Access-Control-Allow-Headers", "origin, x-api-key, x-requested-with, content-type, accept, access-control-request-method, access-control-allow-headers, authorization, observe, enctype, content-length, x-csrf-token");
header("Access-Control-Allow-Credentials", true);

require APPPATH . '/libraries/BaseController.php';
require APPPATH . '/libraries/PaytmChecksum.php';


class MoneyTransferApi extends BaseController {
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
        echo "hello";
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

  public function getSenderDetails() {       
     
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

      if($api_details->api_id == "12"){
          $sender=$this->moneyTransferApi_model->get_sender_details($data['sender_mobile_number']);
          $paytm=$this->moneyTransferApi_model->get_singlebyid('tbl_application_details','id',13);
          $upi=$this->moneyTransferApi_model->get_table_alllike('tbl_application_details','alias','upilimit');
          $sender_details=array("id"                  =>$sender->id,
                              "sender_mobile_number"=>$sender->sender_mobile_number,
                              "sender_name"         =>$sender->first_name." ".$sender->last_name,
                              "pincode"             =>$sender->pincode,
            "additional_registration_data"=>$sender->additional_registration_data,
            "user_code"                             =>$sender->user_code,
            "otp_ref_id"                            =>$sender->otp_ref_id,
            "api_name"                              =>$sender->api_name,
            "available_limit"                       =>$sender->available_limit*100,
            "used_limit"                            =>$sender->used_limit*100,
            "total_limit"                           =>$paytm->value*100,
            'Upi_available_limit'                   =>$sender->Upi_available_limit,
            'Upi_used_limit'                        =>$sender->Upi_used_limit,
            'available_limit_crazy'                 =>$sender->available_limit_crazy,
            'used_limit_crazy'                      =>$sender->used_limit_crazy,
            'UPI_total_Limit'                       =>$upi[0]->value,
            "otp"                                   =>$sender->otp  
            );
          if(!empty($sender)){

              //create Rezor pay account
              if (isset($sender->razorpay_contact_id)) {
                $resp_createContact = $this->createContact_Razorpay($sender);
                if ( isset($resp_createContact['id'])) {
                  $this->db->where(['id' => $sender->id]);
                  $this->db->update('tbl_sender_dts', ['razorpay_contact_id' => $resp_createContact['id']]);
                }
              }

             $response = array(
            'api'=>"Paytm",
            'status' => "true",
            'msg' => "Success",
            'result' =>$sender_details);
           }else{
             $response = array(
            'api'=>"Paytm",
            'status' => "false",
            'msg' => "No Data Found",
            );

           }
         
      }
      else{
        
      	$sender=$this->moneyTransferApi_model->get_sender_details($data['sender_mobile_number']);
          $paytm=$this->moneyTransferApi_model->get_singlebyid('tbl_application_details','id',13);
          $upi=$this->moneyTransferApi_model->get_table_alllike('tbl_application_details','alias','upilimit');
          $sender_details=array("id"                  =>$sender->id,
                              "sender_mobile_number"=>$sender->sender_mobile_number,
                              // "first_name"          =>$sender->first_name,
                              // "last_name"           =>$sender->last_name,
                              "sender_name"         =>$sender->first_name." ".$sender->last_name,
                              "pincode"             =>$sender->pincode,
            "additional_registration_data"=>$sender->additional_registration_data,
            "user_code"                             =>$sender->user_code,
            "otp_ref_id"                            =>$sender->otp_ref_id,
            "api_name"                              =>$sender->api_name,
            "available_limit"                       =>$sender->available_limit,
            "used_limit"                            =>$sender->used_limit,
            "total_limit"                           =>$paytm->value,
            'Upi_available_limit'                   =>$sender->Upi_available_limit,
            'Upi_used_limit'                        =>$sender->used_limit,
            'UPI_total_Limit'                       =>$upi[0]->value,
            'available_limit_crazy'                 =>$sender->available_limit_crazy,
            'used_limit_crazy'                      =>$sender->used_limit_crazy,
            "otp"                                   =>$sender->otp  
            );
            
          if(!empty($sender)){
             $response = array(
            'api'=>"Paytm",
            'status' => "true",
            'msg' => "Success",
            'result' =>$sender_details);
           }else{
             $response = array(
            'api'=>"Paytm",
            'status' => "false",
            'msg' => "No Data Found",
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

  public function createSender() {        
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
      //check active api for operator end

      if($api_details->api_id == "12"){
          $digits = 5;
          $rand=rand(pow(10, $digits-1), pow(10, $digits)-1);
          //echo "$rand and " . ($startDate + $rand);
          // $username="anandkaushal.in";
          $username="NAIDUSOFTWARE";
          // $password="Budd789";
          $password="4049102";
          $msisdn=$data['sender_mobile_number'];
          $sid="SRLSHD";

          $sender="PYMAMA"; //ex:INVITE
          $template_id='123';
          $tem_sms = $this->db->select('*')->from('tbl_sms_gateway_settings')->where('activated_status', 'YES')->where('is_deleted', '0')->get()->row();
          $tem_sms_row= $this->db->select('*')->from('tbl_sms_templates')->where('alias', 'sender_registration')->get()->row();
          $template_id = $tem_sms_row->template_id;
          // $msg="".$rand."%20is%20your%20verification%20code.%20Regards%20Saral%20Shaadi.";
          //$msg  =  "".$rand."%20is%20your%20verification%20code.%20SMARTPAY";
          $msg="Dear Customer Your One Time Password (OTP) For Sender Registration is :".$rand.".www.paymamaapp.in";
          $url = "https://www.bulksms.co/sendmessage.php?user=NAIDUSOFTWARE&password=4049102&mobile=".$msisdn."&message=".$msg."&sender=".$sender."&type=3&template_id=".$template_id;
          
          $ch = curl_init();  
         curl_setopt($ch,CURLOPT_URL,$url);
         curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
         $output=curl_exec($ch);
         curl_close($ch);

        //  $predfine_sms = $this->db->select('*')->from('verify_user_otp')->where('alias', 'verify_user_otp')->get()->row();
        //   if ( $predfine_sms) {
        //     $msg = __($predfine_sms->template, [
        //       "otp" => $rand 
        //     ]);
        //   }

          // $sms_status =  $this->bulk_sms($msg , $msisdn);


         $paytm=$this->moneyTransferApi_model->get_singlebyid('tbl_application_details','id',13);
         $upi=$this->moneyTransferApi_model->get_table_alllike('tbl_application_details','alias','upilimit');
         $crazypay=$this->moneyTransferApi_model->get_table_alllike('tbl_application_details','alias','crazymoneylimit');
         //print_r($upi);
         $resp_createContact = $this->createContact_Razorpay((object) $data);
         if ( isset($resp_createContact['id'])) {
             $rzp = $resp_createContact['id'];
         } else {
             $rzp = "";
         }
         $senderdata=array('sender_mobile_number'=>$data['sender_mobile_number'],
                              'first_name'                  =>$data['first_name'],
                              'last_name'                   =>$data['last_name'],
                              'pincode'                     =>$data['pincode'],
                              'additional_registration_data'=>$result['additional_registration_data']."",
                              'api_name'                    =>'paytm',
                              'available_limit'             =>$paytm->value,
                              'used_limit'                  =>0,
                              'Upi_available_limit'         =>$upi[0]->value,
                              'Upi_used_limit'              =>0,
                              'available_limit_crazy'       =>$crazypay[0]->value,
                              'used_limit_crazy'            =>0,
                              'otp'                         =>$rand,
                              'razorpay_contact_id' => $rzp,
                              'updated_on'                  =>date('Y-m-d H:i:s'));
          $checkmobile=$this->moneyTransferApi_model->check_sender_mobile($data['sender_mobile_number']);
          if(empty($checkmobile)){
             $this->db->insert('tbl_sender_dts',$senderdata);
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
            'result' =>$senderdata
          );

          } 

         
      }
      else{
         $digits = 5;
          $rand=rand(pow(10, $digits-1), pow(10, $digits)-1);
          //echo "$rand and " . ($startDate + $rand);
          // $username="anandkaushal.in";
          $username="NAIDUSOFTWARE";
          // $password="Budd789";
          $password="4049102";
          $msisdn=$data['sender_mobile_number'];
          $sid="SRLSHD";
            $msg="Dear Customer Your One Time Password (OTP) For Sender Registration is :".$rand.".www.paymamaapp.in";
          $sender="PYMAMA"; //ex:INVITE
          $template_id='123';
          $tem_sms = $this->db->select('*')->from('tbl_sms_gateway_settings')->where('activated_status', 'YES')->where('is_deleted', '0')->get()->row();
          $tem_sms_row= $this->db->select('*')->from('tbl_sms_templates')->where('alias', 'sender_registration')->get()->row();
          $template_id = $tem_sms_row->template_id;

          $url = "https://www.bulksms.co/sendmessage.php?user=NAIDUSOFTWARE&password=4049102&mobile=".$msisdn."&message=".urlencode($msg)."&sender=PYMAMA&type=3&template_id=".$template_id;
         
          $ch = curl_init();  
         curl_setopt($ch,CURLOPT_URL,$url);
         curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
         $output=curl_exec($ch);
         curl_close($ch);
         $paytm=$this->moneyTransferApi_model->get_singlebyid('tbl_application_details','id',13);
         $upi=$this->moneyTransferApi_model->get_table_alllike('tbl_application_details','alias','upilimit');
         $crazypay=$this->moneyTransferApi_model->get_table_alllike('tbl_application_details','alias','crazymoneylimit');
         $resp_createContact = $this->createContact_Razorpay((object) $data);
         if ( isset($resp_createContact['id'])) {
             $rzp = $resp_createContact['id'];
         } else {
             $rzp = "";
         }
         $senderdata=array('sender_mobile_number'=>$data['sender_mobile_number'],
                              'first_name'                  =>$data['first_name'],
                              'last_name'                   =>$data['last_name'],
                              'pincode'                     =>$data['pincode'],
                              'additional_registration_data'=>$result['additional_registration_data']."",
                              'api_name'                    =>'paytm',
                              'available_limit'             =>$paytm->value,
                              'used_limit'                  =>0,
                              'Upi_available_limit'         =>$upi[0]->value,
                              'Upi_used_limit'              =>0,
                              'used_limit_crazy'              =>0,
                              'available_limit_crazy'       =>$crazypay[0]->value,
                              'otp'                         =>$rand,
                              'razorpay_contact_id' => $rzp,
                              'updated_on'                  =>date('Y-m-d H:i:s'));
          $checkmobile=$this->moneyTransferApi_model->check_sender_mobile($data['sender_mobile_number']);
          if(empty($checkmobile)){
             $this->db->insert('tbl_sender_dts',$senderdata);
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
            'result' =>$senderdata
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

      if($api_details->api_id == "12"){
        
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
          $resp_createContact = $this->createContact_Razorpay($user);
          if ( isset($resp_createContact['id'])) {
            $this->db->where(['id' => $user->id]);
            $this->db->update('tbl_sender_dts', ['razorpay_contact_id' => $resp_createContact['id']]);
          }
         
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

      if($api_details->api_id == "12"){
          $digits = 5;
          $rand=rand(pow(10, $digits-1), pow(10, $digits)-1);
          //echo "$rand and " . ($startDate + $rand);
          $username="anandkaushal.in";
          $password="Budd789";
          $msisdn=$data['sender_mobile_number'];
          $sid="SRLSHD";
          // $msg="".$rand."%20is%20your%20verification%20code.%20Regards%20Saral%20Shaadi.";
          $msg  =  "".$rand."%20is%20your%20verification%20code.%20SMARTPAY";
          $tem_sms_row= $this->db->select('*')->from('tbl_sms_templates')->where('alias', 'verify_user_otp')->get()->row();
          $template_id = $tem_sms_row->template_id;
          $url = "https://www.bulksms.co/sendmessage.php?user=SMARTPAYINDIA&password=Smartpay@2021&mobile=".$msisdn."&message=".$msg."&sender=SMAPAY&type=3&template_id=".$template_id;
          // $url="http://cloud.smsindiahub.in/vendorsms/pushsms.aspx?user=".$username."&password=".$password."&msisdn=".$msisdn."&sid=".$sid."&msg=".$msg."&fl=0&gwid=2";
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
          // $msg="".$rand."%20is%20your%20verification%20code.%20Regards%20Saral%20Shaadi.";
          // $url="http://cloud.smsindiahub.in/vendorsms/pushsms.aspx?user=".$username."&password=".$password."&msisdn=".$msisdn."&sid=".$sid."&msg=".$msg."&fl=0&gwid=2";
          $msg  =  "".$rand."%20is%20your%20verification%20code.%20SMARTPAY";
          $tem_sms_row= $this->db->select('*')->from('tbl_sms_templates')->where('alias', 'verify_user_otp')->get()->row();
          $template_id = $tem_sms_row->template_id;
          $url = "https://www.bulksms.co/sendmessage.php?user=SMARTPAYINDIA&password=Smartpay@2021&mobile=".$msisdn."&message=".$msg."&sender=SMAPAY&type=3&template_id=".$template_id;
         
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

      if($api_details->api_id == "12"){
        

        $where=array('sender_mobile_number'=>$data['sender_mobile_number'],
                      'api_name'=>'paytm',
                      'is_deleted'=>0);
          //$receiptlist=$this->moneyTransferApi_model->getdata_where('tbl_dmt_benificiary_dtls',$where);

           $receiptlist=$this->moneyTransferApi_model->get_receipt_list($data['sender_mobile_number'], '','DESC');
        $ekores  =array("sender_mobile_number"=>$data['sender_mobile_number'],
                       "response_code"=>0,
                        "recipient_list"=>$receiptlist,
                        "response_description"=>"Success",
                        );
        $response = array(
            'api'=>"paytm",
            'status' => "true",
            'msg' => "Success",
            'result' =>$ekores
          );
         echo json_encode($response);
         exit();

      }
      //developed by susmitha end
      else{
      	$where=array('sender_mobile_number'=>$data['sender_mobile_number'],
                      'api_name'=>'paytm',
                      'is_deleted'=>0);
        $orderby='DESC';
          $receiptlist=$this->moneyTransferApi_model->getdata_where('tbl_dmt_benificiary_dtls',$where,$orderby);
        $ekores  =array("sender_mobile_number"=>$data['sender_mobile_number'],
                       "response_code"=>0,
                        "recipient_list"=>$receiptlist,
                        "response_description"=>"Success",
                        );
        $response = array(
            'api'=>"paytm",
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

      if($api_details->api_id == "12"){
       
        $where=array('sender_mobile_number'=>$data['sender_mobile_number'],
                       'recipient_id'=>$data['recipient_id'],
                       'is_deleted'=>0);
         //$receipt=$this->moneyTransferApi_model->getdata_where('tbl_dmt_benificiary_dtls',$where);
         $receipt=$this->moneyTransferApi_model->get_receipt_list($data['sender_mobile_number'],$data['recipient_id']);
         //print_r($receiptlist);
          if(!empty($receipt)){
           
           $recipient_details=array("recipient_name"  =>$receipt[0]->recipient_name,
                                 "recipient_mobile"=>$receipt[0]->recipient_mobile_number,
                                 "recipient_id"    =>$receipt[0]->recipient_id,
                                 "bank_name"       =>$receipt[0]->bank_name,
                                 "bank_code"       =>$receipt[0]->bank_code,
                                 "IMPS_mode"       =>trim($receipt[0]->IMPS_mode),
                                 "NEFT_mode"       =>trim($receipt[0]->NEFT_mode),
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
            'api'=>"paytm",
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
          'msg' => "Recipent Details Not avalible",
          'result' =>"",
        );
        echo json_encode($response);
        exit;
        }
         
          
      }
      //developed by susmitha end
      else{
      	$where=array('sender_mobile_number'=>$data['sender_mobile_number'],
                       'recipient_id'=>$data['recipient_id'],
                       'is_deleted'=>0);
         $receipt=$this->moneyTransferApi_model->getdata_where('tbl_dmt_benificiary_dtls',$where);
         
          if(!empty($receipt)){
           
           $recipient_details=array("recipient_name"  =>$receipt[0]->recipient_name,
                                 "recipient_mobile"=>$receipt[0]->recipient_mobile_number,
                                 "recipient_id"    =>$receipt[0]->recipient_id,
                                 "bank_name"       =>'',
                                 "bank_code"       =>$receipt[0]->bank_code,
                                 "IMPS_mode"       =>trim($receipt[0]->IMPS_mode),
                                 "NEFT_mode"       =>trim($receipt[0]->NEFT_mode),
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
            'api'=>"paytm",
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
          'msg' => "Recipent Details Not avalible",
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
        
      if($api_details->api_id == "12"){//paytm
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
 //To Get Beneficiary Detail
   public function getbeneficiaryfromcashfree(){
       
    //Get Beneficiary get_sender_details
        $this->db->select('*');
        $this->db->from('tbl_dmt_benificiary_dtls');
       // $this->db->where('recipient_id>=',2900);
        $this->db->where('recipient_id=',10);
        $query = $this->db->get();        
        foreach ($query->result() as $row)
        {
            $bankaccountno=$row->bank_account_number;
            $ifsc=$row->ifsc;
            $rec_id=$row->recipient_id;
            
    //End
    if($ifsc != '')
    {

    //Generate Token
          $clientid="CF154737C6L0GFLJDDO8UP2KFU3GEDCWDCQEDCWCQAES";
          $clientsecret="11bd7c7cc53eb959188b10fcc7c282a067ad4997WDWEDCAWDCWAEC";
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
        
          $url="https://payout-api.cashfree.com/payout/v1/getBeneId?bankAccount=9705031487@ybl&ifsc=";
          // $url="https://payout-api.cashfree.com/payout/v1/getBeneId?bankAccount=mehtashyam13@okhdfcbank&ifsc=";
          $ch = curl_init($url);
          curl_setopt($ch, CURLOPT_GET, true);
          curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json", "Authorization:Bearer " . $token)); 
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
          $result_json = curl_exec($ch);
          curl_close($ch);
          $result =  json_decode($result_json, true); 
          print_r($result);
          
          if($token=$result['status']=="SUCCESS")
          {
                    $beneid=$result['data']['beneId'];
                    $update_rows = array('cfree_beneficiaryid' => $beneid);
            		$this->db->where('recipient_id', $rec_id);
            		$this->db->update('tbl_dmt_benificiary_dtls', $update_rows);	
          }
          
    }
        else
        {
            
        }
        }
        
    }
 
 
 //End 
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

      if($api_details->api_id != "12" or $api_details->api_id == "12"){
       
          $where=array('sender_mobile_number'=>$data['sender_mobile_number'],
                       'bank_account_number'=>$data['bank_account_number'],
                       'api_name'=>'paytm',
                       'is_deleted'=>'0');
          $receiptcheck=$this->moneyTransferApi_model->getdata_where('tbl_dmt_benificiary_dtls',$where);
          
          if(empty($receiptcheck)){
            $bank_arr['bank_name'] = '';
            $bankDtls = $this->db->select('*')->from('tbl_bank_list')->where('ShortCode', $data['bank_code'])->get()->row();
            if ($bankDtls) {
              $bank_arr['bank_name'] = $bankDtls->BANK_NAME;
            }
            $isVerified = 'N';
            $verifiedname = '';
            if ($data['is_verified'] == '1') {
              $isVerified = 'Y';
              $verifiedname = $data['recipient_name'];
            }
             //add recepient info begin
          $recipientInfo = array(
            'recipient_id' =>'',
            'recipient_name' =>$data['recipient_name'],
            'bank_name' =>$bank_arr['bank_name'],
            'bank_code' =>$data['bank_code'],
            'bank_account_number' => $data['bank_account_number'],
            'ifsc' => $data['ifsc'],  
            'recipient_status' =>'',
            'is_verified' =>$isVerified,       
            'verified_name' =>$verifiedname,
            'api_name'     =>'paytm',
            'recipient_mobile_number' =>$data['recipient_mobile_number'],
            'sender_mobile_number'=>$data['sender_mobile_number'],
            'api_id'              =>$api_details->api_id,
            'cfree_beneficiaryid' =>$data['bank_account_number']
          );
        $recipientid=$this->moneyTransferApi_model->addNewRecepient($recipientInfo);
        $recipient_details=array("recipient_name"  =>$data['recipient_name'],
                             "recipient_mobile"=>$data['recipient_mobile_number'],
                             "recipient_id"    =>$recipientid,
                              "bank_name"       =>$bank_arr['bank_name'],
                              "bank_code"       =>$data['bank_code'],
                              "bank_account_number"=>$data['bank_account_number'],
                              "ifsc"             =>$data['ifsc'],  
                              "recipient_status" =>'',
                              "is_verified"      =>$isVerified,
                              "verified_name"    =>$verifiedname,
                              );    

        $senderDtls = $this->db->select('*')->from('tbl_sender_dts')->where('sender_mobile_number', $data['sender_mobile_number'])->get()->row();
        
        //Beneficiary added to Razorpay
        $resp_fundaccount = $this->fundAccount_Rezorpay($data,  $senderDtls->razorpay_contact_id);
     
        if ( isset($resp_fundaccount['id'])) {
          $this->db->where(['recipient_id' => $recipientid]);
          $this->db->update('tbl_dmt_benificiary_dtls', ['razorpay_fund_acc_id' => $resp_fundaccount['id']]);
        }
        //Ends Here
        
        //Beneficiary Added to Cashfree
        //Add Beneficary ID
         //Generate Token
          $clientid="CF154737C6L0GFLJDDO8UP2KFU3GWDEWCEASWCDCDES";
          $clientsecret="11bd7c7cc53eb959188b10fcc7c282a067ad4997SADQECWSDCWS";
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
         
           $Params["beneId"]= $data['bank_account_number'];
          $Params["name"]= $data['recipient_name'];
          $Params["email"] = "mehtashyam13@gmail.com";
          $Params["phone"] = "9033975413";
          $Params["bankAccount"]= $data['bank_account_number'];
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
          
        
        //Ends Here
        $ekores  =array("sender_mobile_number"=>$data['sender_mobile_number'],
                       "response_code"=>0,
                        "recipient_details"=>$recipient_details,
                        "response_description"=>"Recipient added with recipient ID: ".$recipientid."",
                        );
        
        $response = array(
            'api'=>"paytm",
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
          
        
        //print_r($result);
      }
      //developed by susmitha end

      else{
      	   $where=array('sender_mobile_number'=>$data['sender_mobile_number'],
                       'bank_account_number'=>$data['bank_account_number'],
                       'api_name'=>'paytm',);
          $receiptcheck=$this->moneyTransferApi_model->getdata_where('tbl_dmt_benificiary_dtls',$where);
          
          if(empty($receiptcheck)){
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
            'api_name'     =>'paytm',
            'recipient_mobile_number' =>$data['recipient_mobile_number'],
            'sender_mobile_number'=>$data['sender_mobile_number'],
            'api_id'              =>$api_details->api_id
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
            'api'=>"paytm",
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
          'msg' => "Already this Recipent details avalible for same senders",
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
      
      
      /*Delete Beneficiary from cashfree*/
       //Generate Token
          $clientid="CF154737C6L0GFLJDDO8UP2KFU3GEWDCEAWDAWEDWE";
          $clientsecret="11bd7c7cc53eb959188b10fcc7c282a067ad4997WDCASDCSDCEW";
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
          //$where=array('recipient_id'=>$data['recipient_id']);
            //$receiptcheck=$this->moneyTransferApi_model->getdata_where('tbl_dmt_benificiary_dtls',$where);
            
            $ceipt=$data['recipient_id'];
            
            $this->db->select('*');
            $this->db->from('tbl_dmt_benificiary_dtls');
            $this->db->where('recipient_id',$ceipt);
            $query = $this->db->get();        
            $receiptcheck = $query->row();     
            
          $url="https://payout-api.cashfree.com/payout/v1/removeBeneficiary";
          $ch = curl_init($url);
          
          $Params["beneId"]= $receiptcheck->bank_account_number;
        
          $post_data = json_encode($Params, JSON_UNESCAPED_SLASHES);
        
          curl_setopt($ch, CURLOPT_POST, true);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
          curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization:Bearer " . $token)); 
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
          $result_json = curl_exec($ch);
          
          curl_close($ch);
          
        /*Ends Here*/
      
      
      
      
      
      
      
      
      if($api_details->api_id == "12"){
      // echo $data['recipient_id'];
       $where=array('sender_mobile_number'=>$data['sender_mobile_number'],
                       'recipient_id'=>$data['recipient_id'],
                       'api_name'=>'paytm',
                       'is_deleted'=>0);
          $receiptcheck=$this->moneyTransferApi_model->getdata_where('tbl_dmt_benificiary_dtls',$where);
          if(!empty($receiptcheck)){
             $this->db->where('recipient_id',$data['recipient_id']);
              $this->db->update('tbl_dmt_benificiary_dtls', array('is_deleted' =>1));
              $response = array(
            'api'=>"paytm",
            'status' => "true",
            'msg' => "Recipient ID: ".$data['recipient_id']." Deleted Successfully",
            
          );
         // echo json_encode($response);
         // exit();
          }else{
           $response = array(
          'api'=>'paytm',
          'status' => "false",
          'msg' => "Recipent details not avalible check recipent id",
          'result' =>"",
        );
        echo json_encode($response);
        exit;
          }
      }
      else{
      	$where=array('sender_mobile_number'=>$data['sender_mobile_number'],
                       'recipient_id'=>$data['recipient_id'],
                       'api_name'=>'paytm',
                       'is_deleted'=>0);
          $receiptcheck=$this->moneyTransferApi_model->getdata_where('tbl_dmt_benificiary_dtls',$where);
          if(!empty($receiptcheck)){
             $this->db->where('recipient_id',$data['recipient_id']);
              $this->db->update('tbl_dmt_benificiary_dtls', array('is_deleted' =>1));
              $response = array(
            'api'=>"paytm",
            'status' => "true",
            'msg' => "Recipient ID: ".$data['recipient_id']." Deleted Successfully",
            
          );
         // echo json_encode($response);
         // exit();
          }else{
           $response = array(
          'api'=>'paytm',
          'status' => "false",
          'msg' => "Recipent details not avalible check recipent id",
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
      $smartpay=11;
      $apptblsmt= $this->transactions_model->getapptab($smartpay);
      $smartpay=10;
      $apptblpay= $this->transactions_model->getapptab($smartpay);
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
       
                $this->db->select('default_api_id');
                $this->db->from('tbl_operator_settings');
                $this->db->where('operator_name','BANK VERIFICATION');
                $query = $this->db->get();
                $apiis=$query->row()->default_api_id;
                
      if($apiis == 22){
          
       //Generate Token
          $clientid="CF154737C6L0GFLJDDO8UP2KFU3GSDCWEDCEDASDAEW";
          $clientsecret="11bd7c7cc53eb959188b10fcc7c282a067ad499WAWEADCEWDCASCDEW7";
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
         $url="https://payout-api.cashfree.com/payout/v1.2/validation/bankDetails?name=".$name."&phone=".$phone."&bankAccount=".$bank_account_number."&ifsc=".$ifsc;
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
        $transaction_id     =$result['data']['id']."";
        $bank_transaction_id=$result['data']['utr']."";
        $account_holder_name=$result['data']['nameAtBank']."";
        $charges_tax       ='';
        $debit_amount      =1;
        $balance           = $result['data']['closing_balance']."";
        $status            =$result['data']['status']."";
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
              if($i==2 ||$i==3 || $i==4 || $i==7){
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
      
      }
      else{
          
      	$api=$this->moneyTransferApi_model->get_singlebyid('tbl_api_settings','api_id',13);
        $url      =$api->api_url."/api/verify/bank_account";
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
       else{
          $post=array("ifsc"   =>$data['ifsc'],
                    "number"=>$data['bank_account_number'],
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
          'response' => $result."",
          'access_type' => "APP",
          'updated_on'=>date('Y-m-d H:i:s'),
        );
        $api_log_id = $this->apiLog_model->addApiLogDetails($api_info); //save api log details end
        $transaction_id     =$result['data']['id']."";
        $bank_transaction_id=$result['data']['bank_ref_num']."";
        $account_holder_name=$result['data']['verify_account_holder']."";
        $charges_tax       =$result['data']['charges_gst']."";
        $debit_amount      =$result['data']['settled_amount']."";
        $balance           = $result['data']['closing_balance']."";
        $status            =$result['data']['status']."";
        $bank_acc_no       =$result['data']['verify_account_number']."";
        $bank_ifsc        =$result['data']['verify_account_ifsc']."";
        $responsecheck=$result['success']; 
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
          if($responsecheck == "true"){

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
              $where=array("sender_mobile_number"=>$mobile,"bank_account_number"=>$bank_acc_no);
            $updatebeni=array("recipient_name"=>$account_holder_name,"is_verified"=>"Y","verified_name"=>$account_holder_name);
             $this->db->where($where);
             $this->db->update('tbl_dmt_benificiary_dtls',$updatebeni);
             /*if($account_holder_name!='')
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
           */
             $verify_result=array("verify_account_number"=>$bank_acc_no,
                                  "verify_account_ifsc"=>$bank_ifsc,
                                  "verify_account_holder"=>$account_holder_name);
                                 
           //end verify bank
              $response = array(
            'status' => "true",
            'msg' => 'success',
            'result' => $verify_result
          );
            
         
        // exit;
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
public function doFundTransferss() {    
   
    $input =  file_get_contents('php://input'); 
    $data =  json_decode($input, true);
    // print_r($data);die();
    
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
      //check active api for operator end
        //check fund transfer duplicate occur with in minutes start
        $wherecheck=array("service_id"=>$service_id,
                          "operator_id"=>$operator_id,
                          "recipient_id"=>$data['recipient_id'],
                          "request_amount"=>$data['transaction_amount']);
        $transdate=date('Y-m-d H');
        $min="5";
               // $transdate=date('Y-m-d H:i');

        $transduplicatecheck=$this->moneyTransferApi_model->checkduplicate_transaction_new($transdate,$wherecheck,$min);
        if(!empty($transduplicatecheck)){
           $response = array(
          
          'status' => "false",
          'msg' => "Same receipt and amount just now hit one Trasaction so Try again after 5 minute",
          'result' =>""
        );
        echo json_encode($response);
        exit;
        }//check fund transfer occur with in minutes end
       
        $order_uni=$this->get_order_id();
        $ores=json_decode($order_uni,true);
        $sno_order_id=$ores['sno_order_id'];
        $order_id=$ores['order_id'];

      // $last_order_id = file_get_contents("admin/txn_order_id.txt");
      // $sno_order_id  =intval($last_order_id)+1;            
      // $order_id ="SP".$sno_order_id;
      // //validated order_id start 
      //  if($order_id!=''){
      //    $clientres=$this->transactions_model->check_order_id($order_id);
      //    if(!empty($clientres)){
      //      $response = array(
      //             'status' => "false",
      //             'msg' => "Order id already there try once again",
      //             'result' => null
      //      );
      //      echo json_encode($response);
      //      exit;
      //      }
      //      }
      // //validated order_id end
    //   if($api_details->api_id == "12" && floatval($data['transaction_amount']) > 5000){ //calling multi fund transfer API
        
    //     $this->doMultipleFundTransfer(file_get_contents('php://input'));
    //     die;
        
    //   }
    //   if($api_details->api_id == "13" && floatval($data['transaction_amount']) > 5000){ //calling multi fund transfer API
        
    //     $this->doMultipleFundTransfer(file_get_contents('php://input'));
    //     die;
        
    //   }
      if($data['operatorID']=="40" && floatval($data['transaction_amount']) > 5000){ //calling multi fund transfer API
        
        $this->doMultipleFundTransfer(file_get_contents('php://input'));
        die();
        exit;
        
      }

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

      if($api_details->api_id == "12"){//paytm
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

          //end insert transaction table

          /*
          * Generate checksum by parameters we have in body
          * Find your Merchant Key in your Paytm Dashboard at https://dashboard.paytm.com/next/apikeys 
          */
          $checksum = PaytmChecksum::generateSignature($post_data,$api_details->api_secretkey);

          $x_mid      = $api_details->username;
          $x_checksum = $checksum;

          /* for Staging */
          $url =$api_details->api_url;
          // echo "===";
          // print_r("Url = ".$url);
          // echo"=== checksome";
          // print_r($x_checksum);

          /* for Production */
          // $url = "https://dashboard.paytm.com/bpay/api/v1/disburse/order/bank";

          $ch = curl_init($url);
          curl_setopt($ch, CURLOPT_POST, true);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
          curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "x-mid: " . $x_mid, "x-checksum: " . $x_checksum)); 
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
          $result_json = curl_exec($ch);
          $result =  json_decode($result_json, true); 

          //UPDATE TRANSACTION RESULT
          $bnk = ($result['status']=="SUCCESS") ? $result['result']['rrn'] : $result['result']['paytmOrderId'] ;
          $trans_update_arr = array(
            'transaction_status' =>$result['status'],
            'bank_transaction_id' =>$bnk,
            'order_status' => $result['status'],
          );
          $update_tras_record = $this->db->where('order_id', $order_id)->update('tbl_transaction_dtls', $trans_update_arr);
          // END UPDATE TRANSACTION RESULT

           // echo "<pre>";
           // print_r($result);
          // $status_json=$this->check_paytm_status($order_id,$api_details->api_secretkey,$api_details->username,$api_details->api_trn_status_url); 
          // $result =  json_decode($status_json, true); 
          // echo"=== Response == ";
          // print_r($result_json);
          // echo"=== status == ";
          if($result['statusCode']=="DE_002"){
            // print_r('Condition');
             $status_json=$this->check_paytm_status($order_id,$api_details->api_secretkey,$api_details->username,$api_details->api_trn_status_url); 
             $result =  json_decode($status_json, true);
           }
          // if($result['statusCode']=="DE_101"){
          //   // print_r('Condition2');
          //   $status_json=$this->check_paytm_status($order_id,$api_details->api_secretkey,$api_details->username,$api_details->api_trn_status_url); 
          //   $result =  json_decode($status_json, true); 
          // }

          // if ($data['transaction_type'] != 'NEFT') {
          //   $break_count=0;
          //   while($result['statusCode'] == 'DE_101') {
          //     if($break_count>39)
          //       break;

          //     $status_json=$this->check_paytm_status($order_id,$api_details->api_secretkey,$api_details->username,$api_details->api_trn_status_url); 
          //     $result =  json_decode($status_json, true);

          //     $break_count++;
          //   }
          // }

           // echo "<pre>";
           
          //update api log details
          $update_api_dtls_arr =  array('response' => json_encode($result), 'updated_on'=>date('Y-m-d H:i:s') );
          $update_api_log = $this->db->where('order_id', $order_id)->update('tbl_apilog_dts', $update_api_dtls_arr );
          //print_r($result);
          
          if($result['status']=="SUCCESS" || $result['status']=="PENDING"){
            //TRANSACTION TABLE UPDATE AUTOMATICALLY WHEN WEBHOOK CALLED
            

            // $trans_record = $this->db->select('*')->from('tbl_transaction_dtls')->where('order_id', $order_id)->get()->row();
           

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
                // $failed_resp = $this->failedPaytmTransfer($trans_info, $trans_record->order_status );
                $response = array(
                  'status' => "false",
                  'msg' => 'failed',
                  'result' => $trans_record
                );   
                echo json_encode($response) ;

                exit;
            }

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
            // $msg="Transaction Done TID: ".$txn_id." -  order id ".$money_re->order_no.", Amt : Rs ".$amount." Fees: 1 % Name :".$money_re->name."  A/c : ".$money_re->bank_account_number." IFSC: ".$money_re->ifsc." SMARTPAY - ";
            // $msg="TID: ".$money_re->order_no." Amt : Rs ".$amount." Fees: 1 %, Name : ".$money_re->name." A/c : ".$money_re->bank_account_number." Mobile ".$benificiary_details->recipient_mobile_number." IFSC: ".$money_re->ifsc." www.paymamaapp.in";
            $msg="TID: ".$money_re->order_no." Amt : ".$amount." Fees: 1 %, Name : ".$money_re->name." A/c : ".$money_re->bank_account_number." Mobile ".$benificiary_details->recipient_mobile_number." IFSC: ".$money_re->ifsc." www.paymamaapp.in SMARTPAY";
            //$msg="".$rand."%20is%20your%20verification%20code.%20Regards%20Saral%20Shaadi.";
            $tem_sms_row= $this->db->select('*')->from('tbl_sms_templates')->where('alias', 'Trasaction message')->get()->row();
            $template_id = $tem_sms_row->template_id;
              //comment by vishal
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
      else if($api_details->api_id == "13"){ //hypto
      	//echo $api_details->api_id;
        $url =$api_details->api_url."/api/transfers/initiate";
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

          $post=array(
            "amount"=>$amount,
            "payment_type"=>$data['transaction_type'],
            "ifsc"   =>$receipt_details->ifsc,
            "number"=>$receipt_details->bank_account_number,
            "note"  =>"Fund Transfer",
            "reference_number"=>$order_id);
          
          $result_json1=$this->hyptocurl($url,$post,$api_token);
          $result1 =  json_decode($result_json1, true);
          // $staorder_id='DEMOREFNUM0123';
          // $status_url=$api_details->api_url."/api/transfers/status/".$staorder_id."";
          $status_url=$api_details->api_url."/api/transfers/status/".$order_id."";
          $headers=array('Content-Type:application/json','Authorization:'.$api_token.'');
           $result_json=$this->getcurl_with_header($status_url,$headers);
           $result =  json_decode($result_json, true);

            //for status check bt vishal
           
          // $order_id=json_decode($trarr[$m], true)['orderId'];
          if ($result['success']) {

            if ($result['data']['status']=='PENDING') {

              if ($data['transaction_type'] != 'NEFT') {
                $break_count = 0;
                while($result['data']['status'] != 'COMPLETED') {
                  if($break_count>39)
                    break;

                  $result_json=$this->getcurl_with_header($status_url,$headers);
                    $result =  json_decode($result_json, true);

                    $break_count++;
                   
                }
              }

            }
          }


           // echo "<pre>";
           // print_r($result);
           // print_r($result1);
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
            'transaction_type' =>$result['data']['txn_type'], //add
            'bank_transaction_id' =>$result['data']['bank_ref_num'], //add
            'imps_name' =>$receipt_details->recipient_name."", //add
            'recipient_id' =>$receipt_details->recipient_id, //add
            'charges_tax' =>"0", //add
            'commission' =>"0", //add
            'commission_tax' =>"0", //add
            'commission_tds' =>"0", //add
            'debit_amount' =>"0",
            'balance' =>$result['data']['closing_balance'],
            'order_status' => $this->isValid("SUCCESS"),
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
          $available_limit=($sender_details[0]->available_limit)-($amount);
          $used_limit=($sender_details[0]->used_limit)+($amount);
          $sender_detupdate=array("available_limit"=>$available_limit,
                                 "used_limit"=>$used_limit);
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
          $msg="Transaction Done TID: ".$txn_id." -  order id ".$money_re->order_no.", Amt : Rs ".$amount." Fees: 1 % Name :".$money_re->name."  A/c : ".$money_re->bank_account_number." IFSC: ".$money_re->ifsc." SMARTPAY - ";
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
            'msg' => $result['message'],
            'result' =>$result
          );        
        }
         
      }else if($api_details->api_id == "14"){//rezorpay
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
        $senamount=$sender_avlcheck[0]->available_limit_crazy;
        
        
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

          

          //insert transaction table
          $trans_info = array(
                                'transaction_id' =>'',
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
                                'bank_transaction_id' =>'', //add
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
                                'access_type' => (isset($data['access_type'])) ? $data['access_type'] : 'DEFAULT' ,
                                // 'ip_address' => (isset($data['ip_address'])) ? $data['ip_address'] : $this->getRealIpAddr() ,
          );
            //print_r($trans_info);
          $txn_id = $this->transactions_model->addNewTransaction($trans_info);
          //end transaction table
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
            //save api log details begin
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
              // 'response' => json_encode($result)."",
              'response' => "",
              'access_type' => (isset($data['access_type'])) ? $data['access_type'] : 'DEFAULT' ,
              'updated_on'=>date('Y-m-d H:i:s'),
            );
            $api_log_id = $this->apiLog_model->addApiLogDetails($api_info);
            //save api log details end

          $result = $this->payout_Rezorpay($post_req, $api_details);
          //echo "<pre>";
          $update_api_dtls_arr =  array('response' => json_encode($result), 'updated_on'=>date('Y-m-d H:i:s') );
          $update_api_log = $this->db->where('order_id', $order_id)->update('tbl_apilog_dts', $update_api_dtls_arr );
          /* for Staging */
         
          
         
           
          //print_r($result);
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

          if($result['id'] && ( ( in_array($result['status'], $pending_arr)) || (in_array($result['status'], $success_arr))  ) ){
            
            //update balance based on api id in api setting table developed by susmitha start
            // $trans_record = [];
           
            // while ($oStatus=='PENDING') {
            //     $trans_record = $this->db->select('*')->from('tbl_transaction_dtls')->where('order_id', $order_id)->get()->row();
            //     // $trans_record =$this->moneyTransferApi_model->getdata_where('tbl_transaction_dtls',array('order_id'=> $order_id ));
            //     if ($trans_record->order_status != 'PENDING' ) {
            //         // $oStatus = $trans_record[0]->order_status;
            //         $oStatus = $trans_record->order_status;
            //         break;
            //     }
            // }
            $trans_record = $this->db->select('*')->from('tbl_transaction_dtls')->where('order_id', $order_id)->get()->row();
            $trans_update_arr = array(
                                      'transaction_status' =>$result['status'],
                                      'bank_transaction_id' =>$result['utr'],
                                      'order_status' => $oStatus,
                                    );
            $update_tras_record = $this->db->where('order_id', $order_id)->update('tbl_transaction_dtls', $trans_update_arr);

            if ( $oStatus == 'FAILED') {
                $failed_resp = $this->failedRezorpayTransfer($trans_info, $trans_record->transaction_status );
                $response = array(
                  'status' => "false",
                  'msg' => 'failed',
                  'result' => $trans_record
                );   
                exit;
            }

            

            $currentapibal=0;
            $data = array('balance'=>$currentapibal);
            $this->apiLog_model->update_api_amount($data,$api_details->api_id);
            //update balance based on api id in apisetting table developed by susmitha end  
            //update sender avaland used limit start
            $sender_det  =array("sender_mobile_number"=>$mobile);
            $sender_details =$this->moneyTransferApi_model->getdata_where('tbl_sender_dts',$sender_det);
            $available_limit=($sender_details[0]->available_limit_crazy)-($amount);
            $used_limit=($sender_details[0]->used_limit_crazy)+($amount);
            $sender_detupdate=array("available_limit_crazy"=>$available_limit,
                                  "used_limit_crazy"=>$used_limit);
            //print_r($sender_detupdate);
            $this->db->where('sender_mobile_number',$mobile);
            $this->db->update('tbl_sender_dts',$sender_detupdate);
            //update sender avaland used limit end
            $recipent_ver  =array("recipient_id"=>$data['recipient_id'],"is_verified"=>"Y");
            $benificiary_details =$this->moneyTransferApi_model->getdata_where('tbl_dmt_benificiary_dtls',$recipent_ver);
            //print_r($benificiary_details);
            // if(empty($benificiary_details)){
            //   $updatebeni=array("is_verified"=>"Y","verified_name"=>$receipt_details->recipient_name);
            // $where=array('recipient_id',$data['recipient_id']); 
            // $this->db->where($where);
            // $this->db->update('tbl_dmt_benificiary_dtls',$updatebeni);
            // }
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
            // $msg="Transaction Done TID: ".$txn_id." -  order id ".$money_re->order_no.", Amt : Rs ".$amount." Fees: 1 % Name :".$money_re->name."  A/c : ".$money_re->bank_account_number." IFSC: ".$money_re->ifsc." SMARTPAY - ";
              // $msg="TID: ".$money_re->order_no." Amt : Rs ".$amount." Fees: 1 %, Name : ".$money_re->name." A/c : ".$money_re->bank_account_number." Mobile ".$benificiary_details->recipient_mobile_number." IFSC: ".$money_re->ifsc." www.paymamaapp.in";
              $msg="TID: ".$money_re->order_no." Amt : ".$amount." Fees: 1 %, Name : ".$money_re->name." A/c : ".$money_re->bank_account_number." Mobile ".$benificiary_details->recipient_mobile_number." IFSC: ".$money_re->ifsc." www.paymamaapp.in SMARTPAY";
              //$msg="".$rand."%20is%20your%20verification%20code.%20Regards%20Saral%20Shaadi.";
            $tem_sms_row= $this->db->select('*')->from('tbl_sms_templates')->where('alias', 'Trasaction message')->get()->row();
            $template_id = $tem_sms_row->template_id;
            //comment by vishal
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
          }else{
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
          //   'transaction_msg'=>'',
          //   'updated_on'=>date('Y-m-d H:i:s'),
          //   'request_amount'=>$amount,
            
          // );
          // $txn_id = $this->transactions_model->addNewTransaction($trans_info);
          $trans_update_arr = array(
                                    'transaction_status' =>$result['status'],
                                    'order_status' => $oStatus,
          );
          $update_tras_record = $this->db->where('order_id', $order_id)->update('tbl_transaction_dtls', $trans_update_arr);
         
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
            'msg' => "failed",
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
    write_file('admin/dofundtrans.txt', json_encode($response));
    exit;
  }
  public function doFundTransfer() {    
   
    $input =  file_get_contents('php://input'); 
    $data =  json_decode($input, true);
    $recid=$data['recipient_id'];
    $sender_mobile_number=$data['sender_mobile_number'];
    $transtype=$data['transaction_type'];
   
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
      
      //check active api for operator end
        //check fund transfer duplicate occur with in minutes start
        $wherecheck=array("service_id"=>$service_id,
                          "operator_id"=>$operator_id,
                          "recipient_id"=>$data['recipient_id'],
                          "request_amount"=>$data['transaction_amount']);
        $transdate=date('Y-m-d H');
        $min="5";
               // $transdate=date('Y-m-d H:i');

        $transduplicatecheck=$this->moneyTransferApi_model->checkduplicate_transaction_new($transdate,$wherecheck,$min);
        if(!empty($transduplicatecheck)){
           $response = array(
          
          'status' => "false",
          'msg' => "Same receipt and amount just now hit one Trasaction so Try again after 5 minute",
          'result' =>""
        );
        echo json_encode($response);
        exit;
        }//check fund transfer occur with in minutes end
       
        $order_uni=$this->get_order_id();
        $ores=json_decode($order_uni,true);
        $sno_order_id=$ores['sno_order_id'];
        $order_id=$ores['order_id'];

      // $last_order_id = file_get_contents("admin/txn_order_id.txt");
      // $sno_order_id  =intval($last_order_id)+1;            
      // $order_id ="SP".$sno_order_id;
      // //validated order_id start 
      //  if($order_id!=''){
      //    $clientres=$this->transactions_model->check_order_id($order_id);
      //    if(!empty($clientres)){
      //      $response = array(
      //             'status' => "false",
      //             'msg' => "Order id already there try once again",
      //             'result' => null
      //      );
      //      echo json_encode($response);
      //      exit;
      //      }
      //      }
      // //validated order_id end
    //   if($api_details->api_id == "12" && floatval($data['transaction_amount']) > 5000){ //calling multi fund transfer API
        
    //     $this->doMultipleFundTransfer(file_get_contents('php://input'));
    //     die;
        
    //   }
    //   if($api_details->api_id == "13" && floatval($data['transaction_amount']) > 5000){ //calling multi fund transfer API
        
    //     $this->doMultipleFundTransfer(file_get_contents('php://input'));
    //     die;
        
    //   }
       
        
        if(($data['operatorID'] == 40 or $data['operatorID'] == "40") && floatval($data['transaction_amount']) > 5000){ //calling multi fund transfer API
        
        $this->doMultipleFundTransfer(file_get_contents('php://input'));
        die;
        
      }
 
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
      if($data['operatorID'] == 40 or $data['operatorID'] == "40"){//paytm
        
        //Get Beneficiary Details
        $this->db->select('*');
        $this->db->from('tbl_dmt_benificiary_dtls');
        $this->db->where('recipient_id',$data['recipient_id']);
        $this->db->where('is_deleted',0);
        $this->db->order_by('recipient_id', 'desc');
        $query = $this->db->get();
        $receipt_details=$query->row(); 
        //Ends Here
        
        
                
        //$receipt_details=$this->moneyTransferApi_model->get_singlebyid('tbl_dmt_benificiary_dtls','recipient_id',$data['recipient_id']);
          if(empty($receipt_details)){
           $response = array(
          'status' => "false",
          'msg' => "Recipient Id not there check receipt id!! try again.",
          'result' => null
              );
          echo json_encode($response);
        exit;
          }
       if($data['transaction_type']=="IMPS")
            {
                $this->db->select('default_api_id');
                $this->db->from('tbl_operator_settings');
                $this->db->where('operator_name','SMART MONEY IMPS');
                $query = $this->db->get();
                $apiis=$query->row()->default_api_id;
            }
            else
            {
                $this->db->select('default_api_id');
                $this->db->from('tbl_operator_settings');
                $this->db->where('operator_name','SMART MONEY NEFT');
                $query = $this->db->get();
                $apiis=$query->row()->default_api_id;
            }
         $api_details = $this->rechargeApi_model->getActiveApiDetailsclone($apiis);
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
            if($apiis==22)
            {
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

        
            //Add Transaction in Table
            $trans_info = array(
                                'transaction_id' =>'',
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
                                'bank_transaction_id' =>'', //add
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
                                'access_type' => (isset($data['access_type'])) ? $data['access_type'] : 'DEFAULT' ,
                                // 'ip_address' => (isset($data['ip_address'])) ? $data['ip_address'] : $this->getRealIpAddr() ,
          );
            //print_r($trans_info);
          $txn_id = $this->transactions_model->addNewTransaction($trans_info);
            
            //Ends Here
            
            
          //Cashfree payment gateway begins
             //From Here Cash Free Integration Starts
        //   //Generate Token
        //   $clientid="CF154737C6L0GFLJDDO8UP2KFU3G5511414151";
        //   $clientsecret="11bd7c7cc53eb959188b10fcc7c282a067ad499728118515";
        //   $url="https://payout-api.cashfree.com/payout/v1/authorize";
        //   $ch = curl_init($url);
        //   curl_setopt($ch, CURLOPT_POST, true);
        //   curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "X-Client-Id:" . $clientid, "X-Client-Secret:" . $clientsecret)); 
        //   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        //   $result_json = curl_exec($ch);
          
        //   curl_close($ch);
        //   $result =  json_decode($result_json, true); 
          
        //   $token=$result['data']['token'];
          
        //  //End
        //  //Verify token
         
        //   $url="https://payout-api.cashfree.com/payout/v1/verifyToken";
        //   $ch = curl_init($url);
        //   curl_setopt($ch, CURLOPT_POST, true);
        //   curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization:Bearer " . $token)); 
        //   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        //   $result_json = curl_exec($ch);
          
        //   curl_close($ch);
        //   $result =  json_decode($result_json, true); 
        //  //End
        
       
          
        //   //Transfer Amount
          
        //   $Params["beneId"]= $receipt_details->cfree_beneficiaryid;
        //   $Params["transferId"]= $order_id;
        //   $Params["amount"] = $data['transaction_amount'];
        //   $Params["transferMode"] = strtolower($data['transaction_type']);
        
        //   $post_data = json_encode($Params, JSON_UNESCAPED_SLASHES);
        //   $api_info = array(
        //       'service_id' => $service_id."", 
        //       'api_id' => $api_details->api_id."", 
        //       'api_name' => $api_details->api_name."",  
        //       'api_method' => "doFundTransfer",
        //       'api_url' => $api_details->api_url."payouts", 
        //       'order_id' => $order_id."", 
        //       'user_id' => $user_id."",  
        //       'request_input' => json_encode($post_data)."",
        //       'request' => json_encode($post_data)."",         
        //       // 'response' => json_encode($result)."",
        //       'response' => "",
        //       'access_type' => (isset($data['access_type'])) ? $data['access_type'] : 'DEFAULT' ,
        //       'updated_on'=>date('Y-m-d H:i:s'),
        //     );
        //     $api_log_id = $this->apiLog_model->addApiLogDetails($api_info);
        //   $url="https://payout-api.cashfree.com/payout/v1/requestTransfer";
        //   $ch = curl_init($url);
        //   curl_setopt($ch, CURLOPT_POST, true);
        //   curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        //   curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization:Bearer " . $token)); 
        //   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        //   $result_json = curl_exec($ch);
          
        //   curl_close($ch);
        //   $result =  json_decode($result_json, true); 
         // $result = {"status":"SUCCESS","subCode":"200","message":"Transfer completed successfully","data":{"referenceId":"712880116","utr":"225318927710","acknowledged":1}};
          
          //Temporary storing
          $result['status'] = 'SUCCESS';
          $result['subCode'] = 200;
          $result['message'] = 'Transfer completed successfully';
          $result['data']['utr'] = 225318927710;
          
          
          
        //echo "<pre>";
          $update_api_dtls_arr =  array('response' => json_encode($result), 'updated_on'=>date('Y-m-d H:i:s') );
          $update_api_log = $this->db->where('order_id', $order_id)->update('tbl_apilog_dts', $update_api_dtls_arr );
          /* for Staging */
         
          
         
           
          //print_r($result);
          $pending_arr = array('queued','PENDING', 'processing', 'pending');
          $success_arr = array('SUCCESS');
          $failed_arr = array('rejected', 'cancelled','ERROR');
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
       
          //if( ( in_array($result['status'], $pending_arr)) || (in_array($result['status'], $success_arr))  ) {
            if($success_arr)
            //update balance based on api id in api setting table developed by susmitha start
            // $trans_record = [];
           
            // while ($oStatus=='PENDING') {
            //     $trans_record = $this->db->select('*')->from('tbl_transaction_dtls')->where('order_id', $order_id)->get()->row();
            //     // $trans_record =$this->moneyTransferApi_model->getdata_where('tbl_transaction_dtls',array('order_id'=> $order_id ));
            //     if ($trans_record->order_status != 'PENDING' ) {
            //         // $oStatus = $trans_record[0]->order_status;
            //         $oStatus = $trans_record->order_status;
            //         break;
            //     }
            // }
            $trans_record = $this->db->select('*')->from('tbl_transaction_dtls')->where('order_id', $order_id)->get()->row();
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
            
            if ( $oStatus == 'FAILED') {
                $failed_resp = $this->failedRezorpayTransfer($trans_info, $trans_record->transaction_status );
                $response = array(
                  'status' => "false",
                  'msg' => 'failed',
                  'result' => $trans_record
                );   
                exit;
            }

            

            $currentapibal=0;
            $data = array('balance'=>$currentapibal);
            $this->apiLog_model->update_api_amount($data,$api_details->api_id);
            //update balance based on api id in apisetting table developed by susmitha end  
            //update sender avaland used limit start
            $sender_det  =array("sender_mobile_number"=>$mobile);
            $sender_details =$this->moneyTransferApi_model->getdata_where('tbl_sender_dts',$sender_det);
            $available_limit=($sender_details[0]->available_limit_crazy)-($amount);
            $used_limit=($sender_details[0]->used_limit_crazy)+($amount);
            $sender_detupdate=array("available_limit_crazy"=>$available_limit,
                                  "used_limit_crazy"=>$used_limit);
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
            $template_id=1207163818494893617;
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
          }else{
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
          //   'transaction_msg'=>'',
          //   'updated_on'=>date('Y-m-d H:i:s'),
          //   'request_amount'=>$amount,
            
          // );
          // $txn_id = $this->transactions_model->addNewTransaction($trans_info);
          $trans_update_arr = array(
                                    'transaction_status' =>$result['status'],
                                    'bank_transaction_id' =>$result['utr'],
                                    'order_status' => $oStatus,
          );
          $update_tras_record = $this->db->where('order_id', $order_id)->update('tbl_transaction_dtls', $trans_update_arr);
         
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
            'msg' => "failed",
            'result' => $result
          );        
        }
         
            }
            else if($apiis==12)
            {
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
            'api_id' => $apiis,
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
            'api_id' => $apiis."", 
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
            'api_id' => $apiis,
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

          //end insert transaction table

          /*
          * Generate checksum by parameters we have in body
          * Find your Merchant Key in your Paytm Dashboard at https://dashboard.paytm.com/next/apikeys 
          */
          $checksum = PaytmChecksum::generateSignature($post_data,$api_details->api_secretkey);

          $x_mid      = $api_details->username;
          $x_checksum = $checksum;

          /* for Staging */
          $url =$api_details->api_url;
          // echo "===";
          // print_r("Url = ".$url);
          // echo"=== checksome";
          // print_r($x_checksum);

          /* for Production */
          // $url = "https://dashboard.paytm.com/bpay/api/v1/disburse/order/bank";

          $ch = curl_init($url);
          curl_setopt($ch, CURLOPT_POST, true);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
          curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "x-mid: " . $x_mid, "x-checksum: " . $x_checksum)); 
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
          $result_json = curl_exec($ch);
          $result =  json_decode($result_json, true); 

          //UPDATE TRANSACTION RESULT
          $bnk = ($result['status']=="SUCCESS") ? $result['result']['rrn'] : $result['result']['paytmOrderId'] ;
          $trans_update_arr = array(
            'transaction_status' =>$result['status'],
            'bank_transaction_id' =>$bnk,
            'order_status' => $result['status'],
          );
          $update_tras_record = $this->db->where('order_id', $order_id)->update('tbl_transaction_dtls', $trans_update_arr);
          // END UPDATE TRANSACTION RESULT
          
          //UPDATE TRANSACTION DETAIL IN WALLET TRANSACTION TABLE
        //   $trans_update_arr = array(
        //                               'transaction_status' =>$result['status'],
        //                               'bank_transaction_id' =>$result['data']['utr'],
        //                               'order_status' => $oStatus,
        //                             );
                                     
        //     $update_tras_record = $this->db->where('order_id', $order_id)->update('tbl_transaction_dtls', $trans_update_arr);
            $trans_wallet_update_arr = array(
                                      'transaction_id' =>$bnk,
                                      'bank_trans_id' =>$bnk
                                    );
            $update_tras_records = $this->db->where('order_id', $order_id)->update('tbl_wallet_trans_dtls', $trans_wallet_update_arr);
          //ENDS HERE

           // echo "<pre>";
           // print_r($result);
          // $status_json=$this->check_paytm_status($order_id,$api_details->api_secretkey,$api_details->username,$api_details->api_trn_status_url); 
          // $result =  json_decode($status_json, true); 
          // echo"=== Response == ";
          // print_r($result_json);
          // echo"=== status == ";
          if($result['statusCode']=="DE_002"){
            // print_r('Condition');
             $status_json=$this->check_paytm_status($order_id,$api_details->api_secretkey,$api_details->username,$api_details->api_trn_status_url); 
             $result =  json_decode($status_json, true);
           }
          // if($result['statusCode']=="DE_101"){
          //   // print_r('Condition2');
          //   $status_json=$this->check_paytm_status($order_id,$api_details->api_secretkey,$api_details->username,$api_details->api_trn_status_url); 
          //   $result =  json_decode($status_json, true); 
          // }

          // if ($data['transaction_type'] != 'NEFT') {
          //   $break_count=0;
          //   while($result['statusCode'] == 'DE_101') {
          //     if($break_count>39)
          //       break;

          //     $status_json=$this->check_paytm_status($order_id,$api_details->api_secretkey,$api_details->username,$api_details->api_trn_status_url); 
          //     $result =  json_decode($status_json, true);

          //     $break_count++;
          //   }
          // }

           // echo "<pre>";
           
          //update api log details
          $update_api_dtls_arr =  array('response' => json_encode($result), 'updated_on'=>date('Y-m-d H:i:s') );
          $update_api_log = $this->db->where('order_id', $order_id)->update('tbl_apilog_dts', $update_api_dtls_arr );
          //print_r($result);
          
          if($result['status']=="SUCCESS" || $result['status']=="PENDING"){
            //TRANSACTION TABLE UPDATE AUTOMATICALLY WHEN WEBHOOK CALLED
            

            // $trans_record = $this->db->select('*')->from('tbl_transaction_dtls')->where('order_id', $order_id)->get()->row();
           

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
                // $failed_resp = $this->failedPaytmTransfer($trans_info, $trans_record->order_status );
                $response = array(
                  'status' => "false",
                  'msg' => 'failed',
                  'result' => $trans_record
                );   
                echo json_encode($response) ;

                exit;
            }

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
            $recipent_ver  =array("recipient_id"=>$data['recipient_id'],"is_verified"=>"Y");
            
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
            $username="NAIDUSOFTWARE";
            $password="4049102";
            
            $msisdn=$sender_mobile_number;
            $sid="PYMAMA";
            
            // $msg="Transaction Done TID: ".$txn_id." -  order id ".$money_re->order_no.", Amt : Rs ".$amount." Fees: 1 % Name :".$money_re->name."  A/c : ".$money_re->bank_account_number." IFSC: ".$money_re->ifsc." SMARTPAY - ";
              // $msg="TID: ".$money_re->order_no." Amt : Rs ".$amount." Fees: 1 %, Name : ".$money_re->name." A/c : ".$money_re->bank_account_number." Mobile ".$benificiary_details->recipient_mobile_number." IFSC: ".$money_re->ifsc." www.paymamaapp.in";
              $msg="TID : ".$order_id." Amt : ".$amount." Fees: 1 %, Name: ".$benificiary_details[0]->verified_name." A/c: ".$benificiary_details[0]->bank_account_number." Mobile ".$benificiary_details[0]->recipient_mobile_number." Bank ".$benificiary_details[0]->bank_name." IFSC: ".$benificiary_details[0]->ifsc." www.paymamaapp.in";
             
            
            //$msg="".$rand."%20is%20your%20verification%20code.%20Regards%20Saral%20Shaadi.";
            $tem_sms_row= $this->db->select('*')->from('tbl_sms_templates')->where('alias', 'money_transfer')->get()->row();
            $template_id = $tem_sms_row->template_id;
              //comment by vishal
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
        else if($apiis==14)
            {
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
        $senamount=$sender_avlcheck[0]->available_limit_crazy;
        
        
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

          

          //insert transaction table
          $trans_info = array(
                                'transaction_id' =>'',
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
                                'bank_transaction_id' =>'', //add
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
                                'access_type' => (isset($data['access_type'])) ? $data['access_type'] : 'DEFAULT' ,
                                // 'ip_address' => (isset($data['ip_address'])) ? $data['ip_address'] : $this->getRealIpAddr() ,
          );
            //print_r($trans_info);
          $txn_id = $this->transactions_model->addNewTransaction($trans_info);
          //end transaction table
          $paisa = $amount * 100;
          $post_req = '{
                        "account_number": "4564567550186219",
                        "fund_account_id": "'.$receipt_details->razorpay_fund_acc_id.'",
                        "amount": '.$paisa.',
                        "currency": "INR",
                        "mode": "'. $data['transaction_type'].'",
                        "purpose": "payout",
                        "queue_if_low_balance": true,
                        "reference_id": "'.$order_id.'"
                      }';
            //save api log details begin
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
              // 'response' => json_encode($result)."",
              'response' => "",
              'access_type' => (isset($data['access_type'])) ? $data['access_type'] : 'DEFAULT' ,
              'updated_on'=>date('Y-m-d H:i:s'),
            );
            $api_log_id = $this->apiLog_model->addApiLogDetails($api_info);
                      $result = $this->payout_Rezorpay($post_req, $api_details);
        
          //echo "<pre>";
          $update_api_dtls_arr =  array('response' => json_encode($result), 'updated_on'=>date('Y-m-d H:i:s') );
          $update_api_log = $this->db->where('order_id', $order_id)->update('tbl_apilog_dts', $update_api_dtls_arr );
          /* for Staging */
         
          
        
           
          //print_r($result);
          $pending_arr = array('queued', 'pending', 'processing');
          $success_arr = array('SUCCESS');
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
         
          if( ( in_array($result['status'], $pending_arr)) || (in_array($result['status'], $success_arr))  ) {
            
            //update balance based on api id in api setting table developed by susmitha start
            // $trans_record = [];
           
            // while ($oStatus=='PENDING') {
            //     $trans_record = $this->db->select('*')->from('tbl_transaction_dtls')->where('order_id', $order_id)->get()->row();
            //     // $trans_record =$this->moneyTransferApi_model->getdata_where('tbl_transaction_dtls',array('order_id'=> $order_id ));
            //     if ($trans_record->order_status != 'PENDING' ) {
            //         // $oStatus = $trans_record[0]->order_status;
            //         $oStatus = $trans_record->order_status;
            //         break;
            //     }
            // }
            $trans_record = $this->db->select('*')->from('tbl_transaction_dtls')->where('order_id', $order_id)->get()->row();
            $trans_update_arr = array(
                                      'transaction_status' =>$result['status'],
                                      'bank_transaction_id' =>$result['utr'],
                                      'order_status' => $oStatus,
                                    );
            $update_tras_record = $this->db->where('order_id', $order_id)->update('tbl_transaction_dtls', $trans_update_arr);
            
            if ( $oStatus == 'FAILED') {
                $failed_resp = $this->failedRezorpayTransfer($trans_info, $trans_record->transaction_status );
                $response = array(
                  'status' => "false",
                  'msg' => 'failed',
                  'result' => $trans_record
                );   
                exit;
            }

            

            $currentapibal=0;
            $data = array('balance'=>$currentapibal);
            $this->apiLog_model->update_api_amount($data,$api_details->api_id);
            //update balance based on api id in apisetting table developed by susmitha end  
            //update sender avaland used limit start
            $sender_det  =array("sender_mobile_number"=>$mobile);
            $sender_details =$this->moneyTransferApi_model->getdata_where('tbl_sender_dts',$sender_det);
            $available_limit=($sender_details[0]->available_limit_crazy)-($amount);
            $used_limit=($sender_details[0]->used_limit_crazy)+($amount);
            $sender_detupdate=array("available_limit_crazy"=>$available_limit,
                                  "used_limit_crazy"=>$used_limit);
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
            $username="NAIDUSOFTWARE";
            $password="4049102";
            
            $msisdn=$sender_mobile_number;
            $sid="PYMAMA";
            
            // $msg="Transaction Done TID: ".$txn_id." -  order id ".$money_re->order_no.", Amt : Rs ".$amount." Fees: 1 % Name :".$money_re->name."  A/c : ".$money_re->bank_account_number." IFSC: ".$money_re->ifsc." SMARTPAY - ";
              // $msg="TID: ".$money_re->order_no." Amt : Rs ".$amount." Fees: 1 %, Name : ".$money_re->name." A/c : ".$money_re->bank_account_number." Mobile ".$benificiary_details->recipient_mobile_number." IFSC: ".$money_re->ifsc." www.paymamaapp.in";
              $msg="TID : ".$order_id." Amt : ".$amount." Fees: 1 %, Name: ".$benificiary_details[0]->verified_name." A/c: ".$benificiary_details[0]->bank_account_number." Mobile ".$benificiary_details[0]->recipient_mobile_number." Bank ".$benificiary_details[0]->bank_name." IFSC: ".$benificiary_details[0]->ifsc." www.paymamaapp.in";
             
            
            //$msg="".$rand."%20is%20your%20verification%20code.%20Regards%20Saral%20Shaadi.";
            $tem_sms_row= $this->db->select('*')->from('tbl_sms_templates')->where('alias', 'money_transfer')->get()->row();
            $template_id = $tem_sms_row->template_id;
            //comment by vishal
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
          }else{
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
          //   'transaction_msg'=>'',
          //   'updated_on'=>date('Y-m-d H:i:s'),
          //   'request_amount'=>$amount,
            
          // );
          // $txn_id = $this->transactions_model->addNewTransaction($trans_info);
          $trans_update_arr = array(
                                    'transaction_status' =>$result['status'],
                                    'order_status' => $oStatus,
          );
          $update_tras_record = $this->db->where('order_id', $order_id)->update('tbl_transaction_dtls', $trans_update_arr);
         
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
            'msg' => "failed",
            'result' => $result
          );        
        }
         

            }
            else
            {}
      }
      else if($api_details->api_id == "13"){ //hypto
      	//echo $api_details->api_id;
        $url =$api_details->api_url."/api/transfers/initiate";
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

          $post=array(
            "amount"=>$amount,
            "payment_type"=>$data['transaction_type'],
            "ifsc"   =>$receipt_details->ifsc,
            "number"=>$receipt_details->bank_account_number,
            "note"  =>"Fund Transfer",
            "reference_number"=>$order_id);
          
          $result_json1=$this->hyptocurl($url,$post,$api_token);
          $result1 =  json_decode($result_json1, true);
          // $staorder_id='DEMOREFNUM0123';
          // $status_url=$api_details->api_url."/api/transfers/status/".$staorder_id."";
          $status_url=$api_details->api_url."/api/transfers/status/".$order_id."";
          $headers=array('Content-Type:application/json','Authorization:'.$api_token.'');
           $result_json=$this->getcurl_with_header($status_url,$headers);
           $result =  json_decode($result_json, true);

            //for status check bt vishal
           
          // $order_id=json_decode($trarr[$m], true)['orderId'];
          if ($result['success']) {

            if ($result['data']['status']=='PENDING') {

              if ($data['transaction_type'] != 'NEFT') {
                $break_count = 0;
                while($result['data']['status'] != 'COMPLETED') {
                  if($break_count>39)
                    break;

                  $result_json=$this->getcurl_with_header($status_url,$headers);
                    $result =  json_decode($result_json, true);

                    $break_count++;
                   
                }
              }

            }
          }


           // echo "<pre>";
           // print_r($result);
           // print_r($result1);
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
            'transaction_type' =>$result['data']['txn_type'], //add
            'bank_transaction_id' =>$result['data']['bank_ref_num'], //add
            'imps_name' =>$receipt_details->recipient_name."", //add
            'recipient_id' =>$receipt_details->recipient_id, //add
            'charges_tax' =>"0", //add
            'commission' =>"0", //add
            'commission_tax' =>"0", //add
            'commission_tds' =>"0", //add
            'debit_amount' =>"0",
            'balance' =>$result['data']['closing_balance'],
            'order_status' => $this->isValid("SUCCESS"),
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
          $available_limit=($sender_details[0]->available_limit)-($amount);
          $used_limit=($sender_details[0]->used_limit)+($amount);
          $sender_detupdate=array("available_limit"=>$available_limit,
                                 "used_limit"=>$used_limit);
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
          $msg="Transaction Done TID: ".$txn_id." -  order id ".$money_re->order_no.", Amt : Rs ".$amount." Fees: 1 % Name :".$money_re->name."  A/c : ".$money_re->bank_account_number." IFSC: ".$money_re->ifsc." SMARTPAY - ";
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
            'msg' => $result['message'],
            'result' =>$result
          );        
        }
         
      }else if($data['operatorID'] == "21"){//rezorpay
      
      //Get Beneficiary Details
        $this->db->select('*');
        $this->db->from('tbl_dmt_benificiary_dtls');
        $this->db->where('recipient_id',$data['recipient_id']);
        $this->db->where('is_deleted',0);
        $this->db->order_by('recipient_id', 'desc');
        $query = $this->db->get();
        $receipt_details=$query->row(); 
        //Ends Here
        
        
      if($data['transaction_type']=="IMPS")
            {
                $this->db->select('default_api_id');
                $this->db->from('tbl_operator_settings');
                $this->db->where('operator_name','CRAZY MONEY IMPS');
                $query = $this->db->get();
                $apiis=$query->row()->default_api_id;
            }
            else
            {
                $this->db->select('default_api_id');
                $this->db->from('tbl_operator_settings');
                $this->db->where('operator_name','CRAZY MONEY NEFT');
                $query = $this->db->get();
                $apiis=$query->row()->default_api_id;
            }
           $api_details = $this->rechargeApi_model->getActiveApiDetailsclone($apiis);
           
        //echo "hj";
         $mobile=$data['sender_mobile_number'];
        //get receipt account number ifsc from receipt table based on receipt id
          //$receipt_details=$this->moneyTransferApi_model->get_singlebyid('tbl_dmt_benificiary_dtls','recipient_id',$data['recipient_id']);
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
        $senamount=$sender_avlcheck[0]->available_limit_crazy;
        
        
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

          

          //insert transaction table
          $trans_info = array(
                                'transaction_id' =>$order_id,
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
                                'bank_transaction_id' =>'', //add
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
                                'access_type' => (isset($data['access_type'])) ? $data['access_type'] : 'DEFAULT' ,
                                // 'ip_address' => (isset($data['ip_address'])) ? $data['ip_address'] : $this->getRealIpAddr() ,
          );
        
          $txn_id = $this->transactions_model->addNewTransaction($trans_info);
          //end transaction table
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
            //save api log details begin
           
            //save api log details end
            
            if($apiis==22)
            {
          //Cashfree payment gateway begins
             //From Here Cash Free Integration Starts
          //Generate Token
          $clientid="CF154737C6L0GFLJDDO8UP2KFU3Gadadasedaewdcsad";
          $clientsecret="11bd7c7cc53eb959188b10fcc7c282a067ad4997asdxwaqdQAXASDXEAS";
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
          
          $Params["beneId"]= $receipt_details->cfree_beneficiaryid;
          $Params["transferId"]= $order_id;
          $Params["amount"] = $data['transaction_amount'];
          $Params["transferMode"] = strtolower($data['transaction_type']);
        
          $post_data = json_encode($Params, JSON_UNESCAPED_SLASHES);
           $api_info = array(
              'service_id' => $service_id."", 
              'api_id' => $api_details->api_id."", 
              'api_name' => $api_details->api_name."",  
              'api_method' => "doFundTransfer",
              'api_url' => $api_details->api_url."payouts", 
              'order_id' => $order_id."", 
              'user_id' => $user_id."",  
              'request_input' => json_encode($post_data)."",
              'request' => json_encode($post_data)."",         
              // 'response' => json_encode($result)."",
              'response' => "",
              'access_type' => (isset($data['access_type'])) ? $data['access_type'] : 'DEFAULT' ,
              'updated_on'=>date('Y-m-d H:i:s'),
            );
            $api_log_id = $this->apiLog_model->addApiLogDetails($api_info);
          $url="https://payout-api.cashfree.com/payout/v1/requestTransfer";
          $ch = curl_init($url);
          curl_setopt($ch, CURLOPT_POST, true);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
          curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization:Bearer " . $token)); 
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
          $result_json = curl_exec($ch);
          
          curl_close($ch);
          $result =  json_decode($result_json, true); 
            }
        else
        {
          //Ends Here
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
              // 'response' => json_encode($result)."",
              'response' => "",
              'access_type' => (isset($data['access_type'])) ? $data['access_type'] : 'DEFAULT' ,
              'updated_on'=>date('Y-m-d H:i:s'),
            );
            $api_log_id = $this->apiLog_model->addApiLogDetails($api_info);
            $result = $this->payout_Rezorpay($post_req, $api_details);
        }
     
          //echo "<pre>";
          $update_api_dtls_arr =  array('response' => json_encode($result), 'updated_on'=>date('Y-m-d H:i:s') );
          $update_api_log = $this->db->where('order_id', $order_id)->update('tbl_apilog_dts', $update_api_dtls_arr );
          /* for Staging */
         
          
         
           
          //print_r($result);
          $pending_arr = array('queued', 'PENDING', 'processing', 'pending');
          $success_arr = array('SUCCESS');
          $failed_arr = array('rejected', 'cancelled','ERROR');
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
         
          if( ( in_array($result['status'], $pending_arr)) || (in_array($result['status'], $success_arr))  ) {
            
            //update balance based on api id in api setting table developed by susmitha start
            // $trans_record = [];
           
            // while ($oStatus=='PENDING') {
            //     $trans_record = $this->db->select('*')->from('tbl_transaction_dtls')->where('order_id', $order_id)->get()->row();
            //     // $trans_record =$this->moneyTransferApi_model->getdata_where('tbl_transaction_dtls',array('order_id'=> $order_id ));
            //     if ($trans_record->order_status != 'PENDING' ) {
            //         // $oStatus = $trans_record[0]->order_status;
            //         $oStatus = $trans_record->order_status;
            //         break;
            //     }
            // }
            $trans_record = $this->db->select('*')->from('tbl_transaction_dtls')->where('order_id', $order_id)->get()->row();
            if($apiis==22)
            {
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
            }
            else
            {
            $trans_update_arr = array(
                                      'transaction_status' =>$result['status'],
                                      'bank_transaction_id' =>$result['utr'],
                                      'order_status' => $oStatus,
                                    );
                                     
            $update_tras_record = $this->db->where('order_id', $order_id)->update('tbl_transaction_dtls', $trans_update_arr);
           
            }
            
            if ($oStatus == 'FAILED') {
                $failed_resp = $this->failedRezorpayTransfer($trans_info, $trans_record->transaction_status );
                $response = array(
                  'status' => "false",
                  'msg' => 'failed',
                  'result' => $trans_record
                );   
                exit;
            }

            

            $currentapibal=0;
            $data = array('balance'=>$currentapibal);
            $this->apiLog_model->update_api_amount($data,$api_details->api_id);
            //update balance based on api id in apisetting table developed by susmitha end  
            //update sender avaland used limit start
            $sender_det  =array("sender_mobile_number"=>$mobile);
            $sender_details =$this->moneyTransferApi_model->getdata_where('tbl_sender_dts',$sender_det);
            $available_limit=($sender_details[0]->available_limit_crazy)-($amount);
            $used_limit=($sender_details[0]->used_limit_crazy)+($amount);
            $sender_detupdate=array("available_limit_crazy"=>$available_limit,
                                  "used_limit_crazy"=>$used_limit);
            //print_r($sender_detupdate);
            $this->db->where('sender_mobile_number',$mobile);
            $this->db->update('tbl_sender_dts',$sender_detupdate);
            //update sender avaland used limit end
            $recipent_ver  =array("recipient_id"=>$data['recipient_id'],"is_verified"=>"Y");
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
            $template_id=1207163818494893617;
             //comment by vishal
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
                }*/ 
                
                if($walletRoleID == 2){ //Distributor
                  //$walletAmt = $distributor_commission;
                  $ds = $distributor_commission;
                    $apptds    =$this->rechargeApi_model->getTDS();
                    $distds=$ds*($apptds->value/100);
                    $walletAmt=$ds-$distds; 
                  $walletBal = $updatedBalance+$distributor_commission;
                }
                else if($walletRoleID == 1){ //Admin
                
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
          }else{
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
          //   'transaction_msg'=>'',
          //   'updated_on'=>date('Y-m-d H:i:s'),
          //   'request_amount'=>$amount,
            
          // );
          // $txn_id = $this->transactions_model->addNewTransaction($trans_info);
          $trans_update_arr = array(
                                    'transaction_status' =>$result['status'],
                                    'order_status' => $oStatus,
          );
          $update_tras_record = $this->db->where('order_id', $order_id)->update('tbl_transaction_dtls', $trans_update_arr);
         
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
            'msg' => "failed",
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
    write_file('admin/dofundtrans.txt', json_encode($response));
    exit;
  }
public function doMultipleFundTransfer($input) {   

    $data =  json_decode($input, true);
    
    $this->authenticateUser($data);
    $transtypes1=$data['transaction_type'];
    if(!empty($data['operatorID']) && !empty($data['sender_mobile_number'])){
      //get service details by operator id begin
      $operator_id = $data['operatorID'];
      $recip_id = $data['recipient_id'];
      $tran_type = $data['transaction_type'];
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
      //check fund transfer duplicate occur with in minutes start
        $wherecheck=array("service_id"=>$service_id,
                          "operator_id"=>$operator_id,
                          "recipient_id"=>$data['recipient_id'],
                          "request_amount"=>$data['transaction_amount']);
        $transdate=date('Y-m-d H:i');
        //  $min=date('Y-m-d H:i');
        $min="5";

        $transduplicatecheck=$this->moneyTransferApi_model->checkduplicate_transaction_new($transdate,$wherecheck,$min);
        if(!empty($transduplicatecheck)){
           $response = array(
          
          'status' => "false",
          'msg' => "Same receipt and amount just now hit one Trasaction so Try again after 5 minutes",
          'result' =>"",
            'records'=>json_encode($transduplicatecheck)
        );
        echo json_encode($response);
        exit;
        }
        
       //check fund transfer occur with in minutes end
      // $last_order_id = file_get_contents("admin/txn_order_id.txt");              
      // $sno_order_id  =intval($last_order_id)+1;            
      // $order_id ="SP".$sno_order_id;
      //get group_id
      $last_group_id = file_get_contents("admin/txn_group_id.txt");              
      $sno_group_id  =intval($last_group_id)+1;            
      $group_id ="GR".$sno_group_id;
      //check balance of user begin
      $amount = $data['transaction_amount'];
      $operator_id = $data['operatorID'];
      $user_id= $data['user_id'];
      $role_id= $data['role_id'];
       //min bal calculate
      $charge=$amount*(0.6/100);
      $app    =$this->rechargeApi_model->getTDS();
      $TDS     =$amount*(0.4/100)*($app->value/100);
      $totalcharge=$charge+$TDS;
     $Checkamountinwallet =$amount +$totalcharge;
      $userbalance = $this->rechargeApi_model->getUserBalance($data['user_id']);
      
      if ($userbalance) {
        
        $wallet_balance = $userbalance->wallet_balance;
        $min_balance = $userbalance->min_balance;
        $user_package_id = $userbalance->package_id;
        
        if(is_numeric($wallet_balance) && is_numeric($min_balance) && is_numeric($Checkamountinwallet) && $wallet_balance-$Checkamountinwallet < $min_balance){
          $response = array(
              'status' => "false",
              'msg' => "Insufficient balance",
              'result' => null
          );
          echo json_encode($response);
          exit;
        }else if(!is_numeric($wallet_balance) || !is_numeric($min_balance) || !is_numeric($Checkamountinwallet)){
          $response = array(
              'status' => "false",
              'msg' => "Invalid amount details.",
              'result' => null
          );
          echo json_encode($response);
          exit;
        }else{//get all commission details by package id
          /*$commissionDtl = $this->rechargeApi_model->getCommissionDetails($user_package_id,$service_id,$operator_id,$amount);              
          if ($commissionDtl) {
            if($commissionDtl->commission_type == "Rupees"){
              $admin_commission = $commissionDtl->admin_commission;
              $md_commission = $commissionDtl->md_commission;
              $api_commission = $commissionDtl->api_commission;
              $distributor_commission = $commissionDtl->distributor_commission;
              $retailer_commission = $commissionDtl->retailer_commission;
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
          }*/
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

      // //if all above conditions valid then update order id in file
      // $this->writeTxnOrderID($sno_order_id);
      ////if all above conditions valid then update group id in file
      $this->writeTxnGroupID($sno_group_id);
      
         if($data['transaction_type']=="IMPS")
            {
                $this->db->select('default_api_id');
                $this->db->from('tbl_operator_settings');
                $this->db->where('operator_name','SMART MONEY IMPS');
                $query = $this->db->get();
                $apiis=$query->row()->default_api_id;
            }
            else
            {
                $this->db->select('default_api_id');
                $this->db->from('tbl_operator_settings');
                $this->db->where('operator_name','SMART MONEY NEFT');
                $query = $this->db->get();
                $apiis=$query->row()->default_api_id;
            }
           $api_details = $this->rechargeApi_model->getActiveApiDetailsclone($apiis);
      if($api_details->api_id == "12" or $api_details->api_id == 12){ //pattm payment
         $mobile=$data['sender_mobile_number'];
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
        }
        //check avalibe limit in sender table end
         $splitre=$this->split_amount($amount);
         $splitarr=json_decode($splitre);
         $scount=count($splitarr);
         $reduced_amt = [];
         $tds_arr = [];
         $ores_arr=[];
         for($k= 0 ; $k < $scount; $k++){
                          $order_uni         =$this->get_order_id();
                          $ores_new  =json_decode($order_uni,true);
                          $ores_arr[$k]=$order_uni;
                          $calamout=$splitarr[$k];
                          $order_id=$ores_new['order_id'];

                          $commissionDtl = $this->rechargeApi_model->getcommission($service_id,$user_package_id,$operator_id,$calamout);
                          $ccf=$commissionDtl->ccf_commission;
                          //  $charge = $commissionDtl->retailer_commission;
                          if($commissionDtl->retailer_commission_type == 'Percent'){
                            $charge = $calamout * ($commissionDtl->retailer_commission/100);
                          }elseif($commissionDtl->retailer_commission_type == 'Rupees'){
                            $charge = $commissionDtl->retailer_commission;
                          }
                          
                          $cashback=$ccf-$charge;
                          
                          //cashback update to trans table based on order_id
                          $app    =$this->rechargeApi_model->getTDS();
                          $TDS     =$cashback*($app->value/100);
                          $tds_arr[$k]= $TDS;
                          $PayableCharge = $charge+$TDS;
                          $totalAmount=$calamout+$PayableCharge;
                          $reduced_amt[$k] = $totalAmount;
                        
                      //save api log details end
                      //update balance after deduction begin
                      //$updatedBalance = $wallet_balance-$amount;
                      $wallet_balance_info = $this->rechargeApi_model->getUserBalance($user_id);
                      
                      $wallet_balance = $wallet_balance_info->wallet_balance;
                      $updatedBalance = $wallet_balance-$totalAmount;
                        //insert DEBIT txn into tbl_wallet_trans_dtls table
                      $wallet_trans_info = array(
                                                  'service_id' =>$service_id,
                                                  'order_id' => $order_id, 
                                                  'user_id' => $user_id,
                                                  'group_id'=>$group_id."", 
                                                  'operator_id' => $operator_id,
                                                  'api_id' => $api_details->api_id,
                                                  'transaction_status' =>"",
                                                  'transaction_type' => "DEBIT",
                                                  'payment_type' => "SERVICE",
                                                  'payment_mode' => "PAID FOR DMT, ACCOUNT NUMBER ".$receipt_details->bank_account_number.", AMOUNT ".$totalAmount.", CHARGE ".$PayableCharge,
                                                  'transaction_id' => "",               
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
                    
                    $trans_info = array(
                      'transaction_id' =>"",
                      'transaction_status' =>"PENDING", 
                      'service_id' => $service_id, 
                      'operator_id'=>$operator_id,
                      'api_id' => $api_details->api_id,
                      'trans_date' => date("Y-m-d H:i:s"),
                      'order_id' => $this->isValid($order_id),
                      'group_id'   =>$this->isValid($group_id),  
                      'mobileno' =>$mobile, 
                      'user_id' => $this->isValid($user_id),          
                      'total_amount' =>$splitarr[$k],
                      'charge_amount' =>0,
                      // 'transaction_type' =>$data['transaction_type'], //add
                      'transaction_type' =>$tran_type, //add
                      // 'bank_transaction_id' =>$tarr[$j]['result']['paytmOrderId']."", //add
                      'bank_transaction_id' =>"", //add
                      'imps_name' =>$receipt_details->recipient_name."" , //add
                       // 'recipient_id' =>$data['recipient_id'], //add recip_id
                      'recipient_id' =>$recip_id, //add 
                      'charges_tax' =>0, //add
                      'commission' =>0, //add
                      'commission_tax' =>0, //add
                      'commission_tds' =>0, //add
                      'debit_amount' =>0,
                      'balance' =>0,
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
                     // echo "<pre>";
                     // print_r($trans_info);
                    $txn_id = $this->transactions_model->addNewTransaction($trans_info);

         }
         $tarr=array();
         $trarr=array();
         for($i= 0 ; $i < $scount; $i++){//split array start
          //  $order_uni         =$this->get_order_id();
          $order_uni         =$ores_arr[$i];
           $ores               =json_decode($order_uni,true);
           $sno_order_id       =$ores['sno_order_id'];
           $order_id           =$ores['order_id'];

           

            $mobile_number                     =$data['sender_mobile_number'];
            $recipient_id                      =$data['recipient_id'];
            $paytmParams                       = array();
            $paytmParams["subwalletGuid"]      = $api_details->api_token;
            $paytmParams["orderId"]            = $order_id;
            $paytmParams["beneficiaryAccount"] = $receipt_details->bank_account_number; 
            $paytmParams["beneficiaryIFSC"]    = $receipt_details->ifsc;
            $paytmParams["amount"]             = $splitarr[$i];
            $paytmParams["purpose"]            = "OTHERS";
            $paytmParams["date"]  = date('Y-m-d');
            $paytmParams["transferMode"]       =$data['transaction_type'];
            $paytmParams["callbackUrl"]       = "http://paymamaapp.in/admin/index.php/Transactions/paytm_moneytrans_status/".$order_id;
            $post_data[$i]        = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);
           //print_r($post_data[$i]);
          /*
          * Generate checksum by parameters we have in body
          * Find your Merchant Key in your Paytm Dashboard at https://dashboard.paytm.com/next/apikeys 
          */
          $checksum = PaytmChecksum::generateSignature($post_data[$i],$api_details->api_secretkey);

          $x_mid      = $api_details->username;
          $x_checksum = $checksum;

          /* for Staging */
          $url =$api_details->api_url;

          /* for Production */
          // $url = "https://dashboard.paytm.com/bpay/api/v1/disburse/order/bank";

          $ch = curl_init($url);
          curl_setopt($ch, CURLOPT_POST, true);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data[$i]);
          curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "x-mid: " . $x_mid, "x-checksum: " . $x_checksum)); 
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
          $result_json[$i] = curl_exec($ch);
          $result1[$i] =  json_decode($result_json[$i], true); 

          $bnk = ( $result1[$i]['statusCode'] == "DE_001" ) ?  $result1[$i]['result']['rrn'] :  $result1[$i]['result']['paytmOrderId'] ;
          $this->db->where('order_id', $order_id)->update('tbl_transaction_dtls', array('bank_transaction_id' =>$bnk."", 'updated_on'=>date('Y-m-d H:i:s')));
          
          $status_json[$i]=$this->check_paytm_status($order_id,$api_details->api_secretkey,$api_details->username,$api_details->api_trn_status_url); 
          $result[$i] =  json_decode($status_json[$i], true); 


          // if($result[$i]['statusCode']=="DE_002"){
          //    $status_json[$i]=$this->check_paytm_status($order_id,$api_details->api_secretkey,$api_details->username,$api_details->api_trn_status_url); 
          //    $result[$i] =  json_decode($status_json[$i], true); 
          // }
          // if ($data['transaction_type'] != 'NEFT') {
          //   while($result[$i]['statusCode'] == 'DE_101') {
          //     $status_json[$i]=$this->check_paytm_status($order_id,$api_details->api_secretkey,$api_details->username,$api_details->api_trn_status_url); 
          //     $result[$i] =  json_decode($status_json[$i], true); 
          //   }
          // }
          array_push($tarr,$result[$i]);
          array_push($trarr,$post_data[$i]);
            curl_close($ch);
           
        
        //save api log details begin
        
        }
        //for status check bt vishal
        // for ($m=0; $m < count($tarr); $m++) { 
        //   $order_id=json_decode($trarr[$m], true)['orderId'];
        //   if($tarr[$m]['statusCode']=="DE_002"){
        //     $status_json[$m]=$this->check_paytm_status($order_id,$api_details->api_secretkey,$api_details->username,$api_details->api_trn_status_url); 
        //     $tarr[$i] =  json_decode($status_json[$m], true); 
        //   }

        //  if ($data['transaction_type'] != 'NEFT') {
        //     $break_count=0;
        //     while($tarr[$m]['statusCode'] == 'DE_101') {
        //         if($break_count>39)
        //         break;

        //         $status_json[$m]=$this->check_paytm_status($order_id,$api_details->api_secretkey,$api_details->username,$api_details->api_trn_status_url); 
        //         $tarr[$m] =  json_decode($status_json[$m], true); 

        //         $break_count++;
        //     }
        //   }

        // }
        
        //split array start
          //echo "<pre>";
         //    print_r($tarr);
          $aso=0;
         for($j= 0 ; $j < count($tarr); $j++){//split array start
           //validated order_id end
           $calamout=$splitarr[$j];


           // $order_uni         =$this->get_order_id();
           // $ores               =json_decode($order_uni,true);
           // $sno_order_id       =$ores['sno_order_id'];
           // $order_id           =$ores['order_id'];
          
          //  $order_id=$tarr[$j]['result']['orderId'];
          $order_id=json_decode($trarr[$j], true)['orderId'];
           $paytmParams                       = array();
           $paytmParams["subwalletGuid"]      = $api_details->api_token;
           $paytmParams["orderId"]            = $order_id;
           $paytmParams["beneficiaryAccount"] = $receipt_details->bank_account_number;
            $paytmParams["beneficiaryIFSC"]    = $receipt_details->ifsc;
            $paytmParams["amount"]             = $splitarr[$j];
            $paytmParams["purpose"]            = "OTHERS";
            $paytmParams["date"]  = date('Y-m-d');
            $post_data[$j]       = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);
            $api_info = array(
               'service_id' => $service_id."", 
               'api_id' => $api_details->api_id."", 
               'api_name' => $api_details->api_name."",  
               'api_method' => "doMultipleFundTransfer",
               'api_url' => $url."", 
               'group_id'=>$group_id."",
                'order_id' => $order_id."", 
               'user_id' => $user_id."",  
               'request_input' => $post_data[$j],
               'request' => $post_data[$j],         
               'response' =>  json_encode($tarr[$j]),
               'access_type' => "APP",
               'updated_on'=>date('Y-m-d H:i:s'),
               );
              $api_log_id = $this->apiLog_model->addApiLogDetails($api_info);
              $commissionDtl = $this->rechargeApi_model->getcommission($service_id,$user_package_id,$operator_id,$calamout);
             $ccf=$commissionDtl->ccf_commission;
            //  $charge = $commissionDtl->retailer_commission;
            if($commissionDtl->retailer_commission_type == 'Percent'){
              $charge = $calamout * ($commissionDtl->retailer_commission/100);
            }elseif($commissionDtl->retailer_commission_type == 'Rupees'){
              $charge = $commissionDtl->retailer_commission;
            }

             $cashback=$ccf-$charge;

             //cashback update to trans table based on order_id
             $app    =$this->rechargeApi_model->getTDS();
             $TDS     =$cashback*($app->value/100);
             $PayableCharge = $charge+$TDS;
             $totalAmount=$calamout+$PayableCharge;

          //save api log details end
          //update balance after deduction begin
          //$updatedBalance = $wallet_balance-$amount;
          $wallet_balance_info = $this->rechargeApi_model->getUserBalance($user_id);
          $wallet_balance = $wallet_balance_info->wallet_balance;
         $updatedBalance = $wallet_balance-$totalAmount;
           //insert DEBIT txn into tbl_wallet_trans_dtls table
         $wallet_trans_info = array(
        'service_id' =>$service_id,
        'order_id' => $this->isValid($order_id), 
        'user_id' => $user_id,
        'group_id'=>$group_id."", 
        'operator_id' => $operator_id,
        'api_id' => $api_details->api_id,
        'transaction_status' =>"",
        'transaction_type' => "DEBIT",
        'payment_type' => "SERVICE",
        'payment_mode' => "PAID FOR DMT, ACCOUNT NUMBER ".$receipt_details->bank_account_number.", AMOUNT ".$totalAmount.", CHARGE ".$PayableCharge,
        'transaction_id' => "",               
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
        //  $wallet_txn_id = $this->transactions_model->addNewWalletTransaction($wallet_trans_info);
        //update balance into users table                           
        // $updateBalQry = $this->rechargeApi_model->updateUserBalance($user_id,$updatedBalance);
        //update balance after deduction end 
       if($tarr[$j]['statusCode'] == "DE_101" || $tarr[$j]['statusCode'] == "DE_001"){//result success start
        //MYTEST
          
           
          //update balance based on api id in api setting table developed by susmitha start

          $trans_record = [];
              $oStatus = $tarr[$j]['status'];
              // while ($oStatus=='PENDING') {
              //     $trans_record = $this->db->select('*')->from('tbl_transaction_dtls')->where('order_id', $order_id)->get()->row();
              //     // $trans_record =$this->moneyTransferApi_model->getdata_where('tbl_transaction_dtls',array('order_id'=> $order_id ));
              //     if ($trans_record->order_status != 'PENDING' ) {
              //         // $oStatus = $trans_record[0]->order_status;
              //         $oStatus = $trans_record->order_status;
              //         break;
              //     }
              // }

              if ( $oStatus != 'FAILED') {
                  // $failed_resp = $this->failedPaytmTransfer($trans_info, $trans_record->order_status );
                  // $response = array(
                  //   'status' => "false",
                  //   'msg' => 'failed',
                  //   'result' => $trans_record
                  // );  
                  // echo json_encode($response) ;
                  // exit;
             
                
              

                  $currentapibal=0;
                  $data = array('balance'=>$currentapibal);
                  $this->apiLog_model->update_api_amount($data,$api_details->api_id);
                  //update balance based on api id in apisetting table developed by susmitha end
                  //update sender avaland used limit start
                  $sender_det  =array("sender_mobile_number"=>$mobile);
                  $sender_details =$this->moneyTransferApi_model->getdata_where('tbl_sender_dts',$sender_det);
                  $available_limit=($sender_details[0]->available_limit)-($splitarr[$j]);
                  $used_limit=($sender_details[0]->used_limit)+($splitarr[$j]);
                  $sender_detupdate=array("available_limit"=>$available_limit,
                                        "used_limit"=>$used_limit);
                  //print_r($sender_detupdate);
                  $this->db->where('sender_mobile_number',$mobile);
                  $this->db->update('tbl_sender_dts',$sender_detupdate);
                //update sender avaland used limit end
                  $recipent_ver  =array("recipient_id"=>$data['recipient_id'],"is_verified"=>"Y");
                  $benificiary_details =$this->moneyTransferApi_model->getdata_where('tbl_dmt_benificiary_dtls',$recipent_ver);
                  //print_r($benificiary_details);
                  if(empty($benificiary_details)){
                    $updatebeni=array("is_verified"=>"Y","verified_name"=>$tarr[$j]['result']['beneficiaryName']);
                    $where=array('recipient_id',$data['recipient_id']); 
                    $this->db->where($where);
                    $this->db->update('tbl_dmt_benificiary_dtls',$updatebeni);
                  }
                  //Commission details added by ashik begin
                    if ($commissionDtl) {
                      if($commissionDtl->commission_type == "Rupees"){
                        $admin_commission = $commissionDtl->admin_commission;
                        $md_commission = $commissionDtl->md_commission;
                        $api_commission = $commissionDtl->api_commission;
                        $distributor_commission = $commissionDtl->distributor_commission;
                        $retailer_commission = $commissionDtl->retailer_commission;
                      }else if($commissionDtl->commission_type == "Percent"){
                        $admin_commission = ($calamout*$commissionDtl->admin_commission)/100;
                        $md_commission = ($calamout*$commissionDtl->md_commission)/100;
                        $api_commission = ($calamout*$commissionDtl->api_commission)/100;
                        $distributor_commission = ($calamout*$commissionDtl->distributor_commission)/100;
                        $retailer_commission = ($calamout*$commissionDtl->retailer_commission)/100;
                      }else if($commissionDtl->commission_type == "Range"){
                        if($commissionDtl->admin_commission_type == "Rupees")
                          $admin_commission = $commissionDtl->admin_commission;
                        else
                          $admin_commission = ($calamout*$commissionDtl->admin_commission)/100;
                        if($commissionDtl->md_commission_type == "Rupees")
                          $md_commission = $commissionDtl->md_commission;
                        else
                          $md_commission = ($calamout*$commissionDtl->md_commission)/100;
                        if($commissionDtl->distributor_commission_type == "Rupees")
                          $distributor_commission = $commissionDtl->distributor_commission;
                        else
                          $distributor_commission = ($calamout*$commissionDtl->distributor_commission)/100;
                        if($commissionDtl->retailer_commission_type == "Rupees")
                          $retailer_commission = $commissionDtl->retailer_commission;
                        else
                          $retailer_commission = ($calamout*$commissionDtl->retailer_commission)/100;
                          $api_commission = $commissionDtl->api_commission;
                      }
                    }
                    //Commission details added by ashik end 
                    //commission wallet txn begin
                    if(is_numeric($role_id) && intval($role_id) <= 4){                
                      $walletUserID = $user_id;
                      $walletRoleID = $role_id;
                      $isUserBalanceUpdated = false;
                    
                    for($k=$walletRoleID;$k>=1;$k--){                
                      if($k==3 || $k==4  || $i==7){
                        $isUserBalanceUpdated = true;
                        $userParentID = $this->rechargeApi_model->getUserParentID($walletUserID);
                        if ($isUserBalanceUpdated && $userParentID && ($userParentID->roleId==3||$userParentID->roleId==4||$userParentID->roleId==7)) {
                          $walletUserID = $userParentID->userId;
                          $walletRoleID = $userParentID->roleId;
                          $updatedBalance = $userParentID->wallet_balance;
                        }
                        continue;
                      }
                      $walletAmt = 0;
                      $walletBal = 0; 
                      $distds="";                
                      $userParentID = $this->rechargeApi_model->getUserParentID($walletUserID);
                      if ($isUserBalanceUpdated && $userParentID ) {
                        $walletUserID = $userParentID->userId;
                        $walletRoleID = $userParentID->roleId;
                        $updatedBalance = $userParentID->wallet_balance;
                      }                
                      /*if($walletRoleID == 4){ //Retailer
                        $walletAmt = $retailer_commission;
                        $walletBal = $updatedBalance+$retailer_commission;
                      }/*else if($walletRoleID == 3){ //FOS
                        $walletAmt = $distributor_commission;
                        $walletBal = $updatedBalance+$distributor_commission;
                      }else*/ if($walletRoleID == 2){ //Distributor
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
                        if($walletBal < 0){
                          $transType = "DEBIT";
                        }
                        $wallet_trans_info = array(
                          'service_id' => $service_id,
                          'order_id' => $this->isValid($order_id), 
                          'user_id' =>$walletUserID,
                          'group_id'   =>$this->isValid($group_id), 
                          'operator_id' => $operator_id,
                          'api_id' => $api_details->api_id,
                          'transaction_status' =>'Success',
                          'transaction_type' => $transType,
                          'payment_type' => "COMMISSION",
                          'payment_mode' => "Commission by Money Transfer",
                          'transaction_id' =>"",               
                          'trans_date' => date("Y-m-d H:i:s"),  
                          'total_amount' => $walletAmt,
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
                }
            //commission wallet txn end
            $this->db->select('TransTbl.order_id as order_no, 
                                  TransTbl.group_id, 
                                  TransTbl.trans_date as trans_date, 
                                  TransTbl.imps_name as name, 
                                  TransTbl.mobileno as sender_no, 
                                  TransTbl.transaction_type as mode, 
                                  Services.service_name, 
                                  TransTbl.bank_transaction_id, 
                                  TransTbl.order_status, 
                                  TransTbl.total_amount,
                                  TransTbl.remarks, 
                                  Benificiary.bank_account_number, 
                                  Benificiary.ifsc');
                $this->db->from('tbl_transaction_dtls as TransTbl');
                $this->db->join('tbl_services_type as Services','Services.service_id = TransTbl.service_id','left');
                //get accountno and ifsc
                $this->db->join('tbl_dmt_benificiary_dtls as Benificiary','Benificiary.recipient_id = TransTbl.recipient_id','left');
                //get accountno and ifsc
                
                $this->db->where('TransTbl.group_id',$group_id);
                $this->db->where('TransTbl.id_deleted', 0);
                $this->db->order_by("TransTbl.id", "desc");
                $query = $this->db->get();        
                $transactions = $query->result_array();
                
                # For sum of charges of all transaction
                $this->db->select('sum(TransTbl.total_amount) as amount, 
                                  sum(TransTbl.charge_amount) as charge_amount, 
                                  sum(TransTbl.CCFcharges) as CCFcharges, 
                                  sum(TransTbl.Cashback) as Cashback, 
                                  sum(TransTbl.TDSamount) as TDSamount, 
                                  sum(TransTbl.PayableCharge) as PayableCharge, 
                                  sum(TransTbl.FinalAmount) as FinalAmount');
                $this->db->from('tbl_transaction_dtls as TransTbl');
                $this->db->join('tbl_services_type as Services','Services.service_id = TransTbl.service_id','left');
                //get accountno and ifsc
                $this->db->join('tbl_dmt_benificiary_dtls as Benificiary','Benificiary.recipient_id = TransTbl.recipient_id','left');
                //get accountno and ifsc
                
                $this->db->where('TransTbl.group_id',$group_id);
                $this->db->where('TransTbl.id_deleted', 0);
                $this->db->order_by("TransTbl.id", "desc");
                $query = $this->db->get();        
                $transactions_count = $query->row_array();
           
                // $money_re=$this->transactions_model->getmoneyreport($txn_id);
            
            // print_r($money_re);
            $response = array(
              'status' => "true",
              'msg' => "Success",
              'groups' => "yes",
              'result' => $tarr,
              'request' =>$trarr,
              'money'=>$transactions,
              'transaction_sums'=>$transactions_count,
            );
           $numItems=count($tarr);
           if(++$aso === $numItems) {
             
             $orderwhere=array("group_id"=>$group_id);
             $trans_de=$this->moneyTransferApi_model->getdata_where("tbl_transaction_dtls",$orderwhere);
          
          foreach($trans_de as $tval){
            $transid[]=$tval->order_id;
          }
          //print_r($transid);
          $trans_ids=implode(',',$transid);
          $amt_arr=implode(',',$splitarr);

          //send sms 
          $digits = 5;
          $rand=rand(pow(10, $digits-1), pow(10, $digits)-1);
          //echo "$rand and " . ($startDate + $rand);
          $username="anandkaushal.in";
          $password="Budd789";
          $msisdn=$mobile;
          $sid="SRLSHD";
          //by vishal
          // $msg="Transaction Done TID: ".$trans_ids." -,Amt : Rs ".$amount." Fees: 1 % Name :".$money->name."  A/c : ".$money->bank_account_number." IFSC: ".$money->ifsc." SMARTPAY - ";
          //$msg="".$rand."%20is%20your%20verification%20code.%20Regards%20Saral%20Shaadi.";

        //   $url="http://cloud.smsindiahub.in/vendorsms/pushsms.aspx?user=".$username."&password=".$password."&msisdn=".$msisdn."&sid=".$sid."&msg=".$msg."&fl=0&gwid=2";
        //  $ch = curl_init();  
        //  curl_setopt($ch,CURLOPT_URL,$url);
        //  curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        //  $output=curl_exec($ch);
        //  curl_close($ch);
        //  $msg="TID: ".$trans_ids." Amt : Rs ".$amt_arr." Fees: 1 %, Name : ".$money->name." A/c : ".$money->bank_account_numbe." Mobile ".$benificiary_details->recipient_mobile_number." IFSC: ".$money->ifsc." www.paymamaapp.in";
         $msg ="TID:%20".$trans_ids."%20Amt : ".$amt_arr." Fees: 1 %, Name : ".$money->name." A/c : ".$money->bank_account_numbe." Mobile ".$benificiary_details->recipient_mobile_number." IFSC: ".$money->ifsc." www.paymamaapp.in SMARTPAY";
         
         $tem_sms_row= $this->db->select('*')->from('tbl_sms_templates')->where('alias', 'Trasaction message')->get()->row();
         $template_id = $tem_sms_row->template_id;
         $sms_output = $this->sendBulkSMS($msisdn, $msg, $template_id);
          //by vishal end
         //send sms
            }
            
           
        }//result success end
        else if($tarr[$j]['statusCode'] != "DE_101" || $tarr[$j]['statusCode'] != "DE_001"){
          
              $trans_info = array('transaction_id' =>"",
                                  'transaction_status' => $this->isValid($tarr[$j]['status']), 
                                  'service_id' =>$service_id, 
                                  'operator_id'=>$operator_id,
                                  'api_id' =>$api_details->api_id,
                                  'trans_date' => date("Y-m-d H:i:s"),
                                  'group_id'   =>$this->isValid($group_id),
                                  'order_id' => $order_id,  
                                  'mobileno' =>$mobile, 
                                  'user_id' => $this->isValid($user_id),          
                                  'total_amount' =>$splitarr[$j],
                                  'charge_amount' =>0,
                                  'transaction_type' =>$data['transaction_type'], //add
                                  'bank_transaction_id' =>$tarr[$j]['result']['paytmOrderId']."", //add
                                  'imps_name' =>$receipt_details->recipient_name."", //add
                                  'recipient_id' =>$receipt_details->recipient_id, //add
                                  'charges_tax' =>0 , //add
                                  'commission' =>0, //add
                                  'commission_tax' =>'0', //add
                                  'commission_tds' =>0, //add
                                  'debit_amount' => 0,
                                  'balance' =>0,
                                  'order_status' => $this->isValid("FAILED"),
                                  'transaction_msg'=>$tarr[$j]['statusMessage'],
                                  'CCFcharges'=>$ccf,
                                  'Cashback'=>$cashback,
                                  'TDSamount'=>$TDS,
                                  'PayableCharge'=>$PayableCharge,
                                  'FinalAmount'=>$totalAmount,
                                  'request_amount'=>$amount,
                                  'updated_on'=>date('Y-m-d H:i:s'),
                                );
             $txn_id = $this->transactions_model->addNewTransaction($trans_info);
             $userbalance = $this->rechargeApi_model->getUserBalance($user_id);
               $wallet_balance=$userbalance->wallet_balance;
                $updatedBalance = $wallet_balance+$totalAmount; 
                // $updatedBalance = $wallet_balance+(int)$splitarr[$j]; 
                 //insert DEBIT txn into tbl_wallet_trans_dtls table
                 $wallet_trans_info = array(
                  'service_id' =>$service_id,
                  'order_id' => $this->isValid($order_id), 
                  'group_id'   =>$this->isValid($group_id),
                  'user_id' => $user_id, 
                  'operator_id' => $operator_id,
                  'api_id' => $api_details->api_id,
                  'transaction_status' => $this->isValid($result['status']),
                  'transaction_type' => "CREDIT",
                  'payment_type' => "SERVICE",
                  'payment_mode' => "REFUND FOR DMT, ACCOUNT NUMBER ".$receipt_details->bank_account_number.", AMOUNT ".$totalAmount.", CHARGE ".$PayableCharge,
                  'transaction_id' =>"",               
                  'trans_date' => date("Y-m-d H:i:s"),  
                  'total_amount' => $totalAmount,
                  'charge_amount' => "0.00",
                  'balance' => $updatedBalance,
                  'TDSamount' => $tds_arr[$j] * -1,
                  'updated_on'=>date('Y-m-d H:i:s'),
                );
                $wallet_txn_id = $this->transactions_model->addNewWalletTransaction($wallet_trans_info);
                //update balance into users table                           
                $updateBalQry = $this->rechargeApi_model->updateUserBalance($user_id,$updatedBalance);
                //update balance after deduction end
                $response = array(
                  'status' => "false",
                  'msg' => $tarr[$j]['statusMessage'],
                  'result' => $tarr[$j]
                );  
        }
      }//split array end

      }//paytm end
      else if($api_details->api_id == "13" or $api_details->api_id == 13){//hypto
      	$url      =$api_details->api_url."/api/transfers/initiate";
        $api_token=$api_details->api_token;
      	$mobile=$data['sender_mobile_number'];
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
        }
        //check avalibe limit in sender table end
         $splitre=$this->split_amount($amount);
         $splitarr=json_decode($splitre);
         $scount=count($splitarr);

         
         $ores_arr=[];
        for($k= 0 ; $k < $scount; $k++){
          $order_uni =$this->get_order_id();
          $ores_new  =json_decode($order_uni,true);
          $ores_arr[$k]=$order_uni;
                  //validated order_id end
                    $calamout=$splitarr[$k];
                    //  $order_id=$tarr[$j]['data']['reference_numbe'];
                    $order_id=$ores_new['order_id'];
                    
                    // $post_data[$k]=array(
                    //   "amount"=>$splitarr[$k],
                    //   "payment_type"=>$data['transaction_type'],
                    //   "ifsc"   =>$receipt_details->ifsc,
                    //   "number"=>$receipt_details->bank_account_number,
                    //   "note"  =>"Fund Transfer",
                    //   "reference_number"=>$order_id);
                    // $api_info = array(
                    //     'service_id' => $service_id."", 
                    //     'api_id' => $api_details->api_id."", 
                    //     'api_name' => $api_details->api_name."",  
                    //     'api_method' => "doMultipleFundTransfer",
                    //     'api_url' => $url."", 
                    //     'group_id'=>$group_id."",
                    //       'order_id' => $order_id."", 
                    //     'user_id' => $user_id."",  
                    //     'request_input' => $post_data[$k],
                    //     'request' => $post_data[$k],         
                    //     'response' =>  '',
                    //     'access_type' => "APP",
                    //     'updated_on'=>date('Y-m-d H:i:s'),
                    //     );
                        // $api_log_id = $this->apiLog_model->addApiLogDetails($api_info);
                        $commissionDtl = $this->rechargeApi_model->getcommission($service_id,$user_package_id,$operator_id,$calamout);
                      $ccf=$commissionDtl->ccf_commission;
                      //  $charge = $commissionDtl->retailer_commission;
                      if($commissionDtl->retailer_commission_type == 'Percent'){
                        $charge = $calamout * ($commissionDtl->retailer_commission/100);
                      }elseif($commissionDtl->retailer_commission_type == 'Rupees'){
                        $charge = $commissionDtl->retailer_commission;
                      }
                      $cashback=$ccf-$charge;
                      //cashback update to trans table based on order_id
                      $app    =$this->rechargeApi_model->getTDS();
                      $TDS     =$cashback*($app->value/100);
                      $PayableCharge = $charge+$TDS;
                      $totalAmount=$calamout+$PayableCharge;
                    //save api log details end
                    //update balance after deduction begin
                    //$updatedBalance = $wallet_balance-$amount;
                    $wallet_balance_info = $this->rechargeApi_model->getUserBalance($user_id);
                    $wallet_balance = $wallet_balance_info->wallet_balance;

                  $updatedBalance = $wallet_balance-$totalAmount;
                    //insert DEBIT txn into tbl_wallet_trans_dtls table
                  $wallet_trans_info = array(
                  'service_id' =>$service_id,
                  'order_id' => $order_id, 
                  'user_id' => $user_id,
                  'group_id'=>$group_id."", 
                  'operator_id' => $operator_id,
                  'api_id' => $api_details->api_id,
                  'transaction_status' =>"",
                  'transaction_type' => "DEBIT",
                  'payment_type' => "SERVICE",
                  'payment_mode' => "PAID FOR DMT, ACCOUNT NUMBER ".$receipt_details->bank_account_number.", AMOUNT ".$totalAmount.", CHARGE ".$PayableCharge,
                  'transaction_id' => "",               
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
        
        }





         $tarr=array();
         $trarr=array();

         for($i= 0 ; $i < $scount; $i++){//split array start
           $headers=array('Content-Type:application/json','Authorization:'.$api_token.'');
          //  $order_uni         =$this->get_order_id();
           $order_uni         =$ores_arr[$i];
           $ores               =json_decode($order_uni,true);

           $sno_order_id       =$ores['sno_order_id'];
           $order_id           =$ores['order_id'];
           $mobile_number      =$data['sender_mobile_number'];
           $recipient_id       =$data['recipient_id'];
           $post[$i]=array(
            "amount"=>$splitarr[$i],
            "payment_type"=>$data['transaction_type'],
            "ifsc"   =>$receipt_details->ifsc,
            "number"=>$receipt_details->bank_account_number,
            "note"  =>"Fund Transfer",
            "reference_number"=>$order_id);
            //print_r($post[$i]);
         	$result_json1[$i]=$this->hyptocurl($url,$post[$i],$api_token);
            $result1[$i] =  json_decode($result_json1[$i], true);
           // $staorder_id='DEMOREFNUM0123';
           // $status_url=$api_details->api_url."/api/transfers/status/".$staorder_id."";
           $status_url[$i]=$api_details->api_url."/api/transfers/status/".$order_id."";
         
           $result_json[$i]=$this->getcurl_with_header($status_url[$i],$headers);
           $result[$i] =  json_decode($result_json[$i], true);




           array_push($tarr,$result[$i]);
           array_push($trarr,$post[$i]);
           
          }//split array end
          
           //for status check bt vishal
        for ($m=0; $m < count($tarr); $m++) { 
          // $order_id=json_decode($trarr[$m], true)['orderId'];
          $headers=array('Content-Type:application/json','Authorization:'.$api_token.'');
          $order_id=$trarr[$m]['reference_number'];
          $status_url[$m]=$api_details->api_url."/api/transfers/status/".$order_id."";
          if ($tarr[$m]['success']) {

            if ($tarr[$m]['data']['status']=='PENDING') {

              if ($data['transaction_type'] != 'NEFT') {
                $break_count = 0;
                while($tarr[$m]['data']['status'] != 'COMPLETED') {
                  if($break_count>39)
                    break;

                  $result_json[$im]=$this->getcurl_with_header($status_url[$m],$headers);
                    $tarr[$m] =  json_decode($result_json[$m], true);

                    $break_count++;
                   
                }
              }

            }
          }

        }
          // echo "<pre>";
          //   print_r($tarr);
          //   exit();
          
          $aso=0;
         for($j= 0 ; $j < count($tarr); $j++){//split array start
           //validated order_id end
           $calamout=$splitarr[$j];
          //  $order_id=$tarr[$j]['data']['reference_numbe'];
          $order_id=$trarr[$j]['reference_number'];
          
           $post_data[$j]=array(
            "amount"=>$splitarr[$j],
            "payment_type"=>$data['transaction_type'],
            "ifsc"   =>$receipt_details->ifsc,
            "number"=>$receipt_details->bank_account_number,
            "note"  =>"Fund Transfer",
            "reference_number"=>$order_id);
           $api_info = array(
               'service_id' => $service_id."", 
               'api_id' => $api_details->api_id."", 
               'api_name' => $api_details->api_name."",  
               'api_method' => "doMultipleFundTransfer",
               'api_url' => $url."", 
               'group_id'=>$group_id."",
                'order_id' => $order_id."", 
               'user_id' => $user_id."",  
               'request_input' =>  json_encode($post_data[$j], JSON_UNESCAPED_SLASHES),
               'request' => json_encode($post_data[$j], JSON_UNESCAPED_SLASHES),        
               'response' =>  json_encode($tarr[$j]),
               'access_type' => "APP",
               'updated_on'=>date('Y-m-d H:i:s'),
               );

              $api_log_id = $this->apiLog_model->addApiLogDetails($api_info);
              $commissionDtl = $this->rechargeApi_model->getcommission($service_id,$user_package_id,$operator_id,$calamout);
             $ccf=$commissionDtl->ccf_commission;
            //  $charge = $commissionDtl->retailer_commission;
            if($commissionDtl->retailer_commission_type == 'Percent'){
              $charge = $calamout * ($commissionDtl->retailer_commission/100);
            }elseif($commissionDtl->retailer_commission_type == 'Rupees'){
              $charge = $commissionDtl->retailer_commission;
            }
             $cashback=$ccf-$charge;
             //cashback update to trans table based on order_id
             $app    =$this->rechargeApi_model->getTDS();
             $TDS     =$cashback*($app->value/100);
             $PayableCharge = $charge+$TDS;
             $totalAmount=$calamout+$PayableCharge;
          //save api log details end
          //update balance after deduction begin
          //$updatedBalance = $wallet_balance-$amount;
          $wallet_balance_info = $this->rechargeApi_model->getUserBalance($user_id);
          $wallet_balance = $wallet_balance_info->wallet_balance;

         $updatedBalance = $wallet_balance-$totalAmount;
           //insert DEBIT txn into tbl_wallet_trans_dtls table
         $wallet_trans_info = array(
        'service_id' =>$service_id,
        'order_id' => $order_id, 
        'user_id' => $user_id,
        'group_id'=>$group_id."", 
        'operator_id' => $operator_id,
        'api_id' => $api_details->api_id,
        'transaction_status' =>"",
        'transaction_type' => "DEBIT",
        'payment_type' => "SERVICE",
        'payment_mode' => "PAID FOR DMT, ACCOUNT NUMBER ".$receipt_details->bank_account_number.", AMOUNT ".$totalAmount.", CHARGE ".$PayableCharge,
        'transaction_id' => "",               
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

        //  $wallet_txn_id = $this->transactions_model->addNewWalletTransaction($wallet_trans_info);
        //update balance into users table                           
        // $updateBalQry = $this->rechargeApi_model->updateUserBalance($user_id,$updatedBalance);
        //update balance after deduction end 

        if($tarr[$j]['success'] == "true" ){//result success start
           $trans_info = array(
            'transaction_id' =>$tarr[$j]['data']['id'],
            'transaction_status' =>$tarr[$j]['data']['status'], 
            'service_id' => $service_id, 
            'operator_id'=>$operator_id,
            'api_id' => $api_details->api_id,
            'trans_date' => date("Y-m-d H:i:s"),
            'order_id' => $order_id,
            'group_id'   =>$this->isValid($group_id),  
            'mobileno' =>$mobile, 
            'user_id' => $this->isValid($user_id),          
            'total_amount' =>$splitarr[$j],
            'charge_amount' =>0,
            'transaction_type' =>0, //add
            'bank_transaction_id' =>$tarr[$j]['data']['bank_ref_num']."", //add
            'imps_name' =>$receipt_details->recipient_name."" , //add
            'recipient_id' =>$data['recipient_id'], //add
            'charges_tax' =>0, //add
            'commission' =>0, //add
            'commission_tax' =>0, //add
            'commission_tds' =>0, //add
            'debit_amount' =>0,
            'balance' =>0,
            'order_status' => "SUCCESS",
            'transaction_msg'=>$tarr[$j]['data']['message'],
            'CCFcharges'=>$ccf,
            'Cashback'=>$cashback,
            'TDSamount'=>$TDS,
            'PayableCharge'=>$PayableCharge,
            'FinalAmount'=>$totalAmount,
            'request_amount'=>$amount,
            'updated_on'=>date('Y-m-d H:i:s'),
            );
           // echo "<pre>";
           // print_r($trans_info);
          $txn_id = $this->transactions_model->addNewTransaction($trans_info);
          //update balance based on api id in api setting table developed by susmitha start
          $currentapibal=0;
          $data = array('balance'=>$currentapibal);
           $this->apiLog_model->update_api_amount($data,$api_details->api_id);
          //update balance based on api id in apisetting table developed by susmitha end
          //update sender avaland used limit start
          $sender_det  =array("sender_mobile_number"=>$mobile);
          $sender_details =$this->moneyTransferApi_model->getdata_where('tbl_sender_dts',$sender_det);
          $available_limit=($sender_details[0]->available_limit)-($splitarr[$j]);
          $used_limit=($sender_details[0]->used_limit)+($splitarr[$j]);
          $sender_detupdate=array("available_limit"=>$available_limit,
                                 "used_limit"=>$used_limit);
          //print_r($sender_detupdate);
          $this->db->where('sender_mobile_number',$mobile);
          $this->db->update('tbl_sender_dts',$sender_detupdate);
         //update sender avaland used limit end
          $recipent_ver  =array("recipient_id"=>$data['recipient_id'],"is_verified"=>"Y");
         $benificiary_details =$this->moneyTransferApi_model->getdata_where('tbl_dmt_benificiary_dtls',$recipent_ver);
           //print_r($benificiary_details);
          if(empty($benificiary_details)){
            $updatebeni=array("is_verified"=>"Y","verified_name"=>$tarr[$j]['data']['transfer_account_holder']);
          $where=array('recipient_id',$data['recipient_id']); 
          $this->db->where($where);
          $this->db->update('tbl_dmt_benificiary_dtls',$updatebeni);
          }
         //Commission details added by ashik begin
            if ($commissionDtl) {
              if($commissionDtl->commission_type == "Rupees"){
                $admin_commission = $commissionDtl->admin_commission;
                $md_commission = $commissionDtl->md_commission;
                $api_commission = $commissionDtl->api_commission;
                $distributor_commission = $commissionDtl->distributor_commission;
                $retailer_commission = $commissionDtl->retailer_commission;
              }else if($commissionDtl->commission_type == "Percent"){
                $admin_commission = ($calamout*$commissionDtl->admin_commission)/100;
                $md_commission = ($calamout*$commissionDtl->md_commission)/100;
                $api_commission = ($calamout*$commissionDtl->api_commission)/100;
                $distributor_commission = ($calamout*$commissionDtl->distributor_commission)/100;
                $retailer_commission = ($calamout*$commissionDtl->retailer_commission)/100;
              }else if($commissionDtl->commission_type == "Range"){
                if($commissionDtl->admin_commission_type == "Rupees")
                  $admin_commission = $commissionDtl->admin_commission;
                else
                  $admin_commission = ($calamout*$commissionDtl->admin_commission)/100;
                if($commissionDtl->md_commission_type == "Rupees")
                  $md_commission = $commissionDtl->md_commission;
                else
                  $md_commission = ($calamout*$commissionDtl->md_commission)/100;
                if($commissionDtl->distributor_commission_type == "Rupees")
                  $distributor_commission = $commissionDtl->distributor_commission;
                else
                  $distributor_commission = ($calamout*$commissionDtl->distributor_commission)/100;
                if($commissionDtl->retailer_commission_type == "Rupees")
                  $retailer_commission = $commissionDtl->retailer_commission;
                else
                  $retailer_commission = ($calamout*$commissionDtl->retailer_commission)/100;
                  $api_commission = $commissionDtl->api_commission;
              }
            }
            //Commission details added by ashik end 
            //commission wallet txn begin
            if(is_numeric($role_id) && intval($role_id) <= 4){                
              $walletUserID = $user_id;
              $walletRoleID = $role_id;
              $isUserBalanceUpdated = false;
              
              for($k=$walletRoleID;$k>=1;$k--){                
                if($k==3 || $k==4  || $i==7){
                  $isUserBalanceUpdated = true;
                  $userParentID = $this->rechargeApi_model->getUserParentID($walletUserID);
                  if ($isUserBalanceUpdated && $userParentID && ($userParentID->roleId==3||$userParentID->roleId==4||$userParentID->roleId==7)) {
                    $walletUserID = $userParentID->userId;
                    $walletRoleID = $userParentID->roleId;
                    $updatedBalance = $userParentID->wallet_balance;
                  }
                  continue;
                }
                $walletAmt = 0;
                $walletBal = 0; 
                $distds="";                
                $userParentID = $this->rechargeApi_model->getUserParentID($walletUserID);
                 if ($isUserBalanceUpdated && $userParentID ) {
                  $walletUserID = $userParentID->userId;
                  $walletRoleID = $userParentID->roleId;
                  $updatedBalance = $userParentID->wallet_balance;
                }                
                /*if($walletRoleID == 4){ //Retailer
                  $walletAmt = $retailer_commission;
                  $walletBal = $updatedBalance+$retailer_commission;
                }/*else if($walletRoleID == 3){ //FOS
                  $walletAmt = $distributor_commission;
                  $walletBal = $updatedBalance+$distributor_commission;
                }else*/ if($walletRoleID == 2){ //Distributor
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
                  if($walletBal < 0){
                    $transType = "DEBIT";
                  }
                  $wallet_trans_info = array(
                    'service_id' => $service_id,
                    'order_id' => $this->isValid($order_id), 
                    'user_id' =>$walletUserID,
                    'group_id'   =>$this->isValid($group_id), 
                    'operator_id' => $operator_id,
                    'api_id' => $api_details->api_id,
                    'transaction_status' =>'Success',
                    'transaction_type' => $transType,
                    'payment_type' => "COMMISSION",
                    'payment_mode' => "Commission by Money Transfer",
                    'transaction_id' =>"",               
                    'trans_date' => date("Y-m-d H:i:s"),  
                    'total_amount' => $walletAmt,
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
             $this->db->select('TransTbl.order_id as order_no, 
                                  TransTbl.group_id, 
                                  TransTbl.trans_date as trans_date, 
                                  TransTbl.imps_name as name, 
                                  TransTbl.mobileno as sender_no, 
                                  TransTbl.transaction_type as mode, 
                                  Services.service_name, 
                                  TransTbl.bank_transaction_id, 
                                  TransTbl.order_status, 
                                  TransTbl.total_amount,
                                  TransTbl.remarks, 
                                  Benificiary.bank_account_number, 
                                  Benificiary.ifsc');
                $this->db->from('tbl_transaction_dtls as TransTbl');
                $this->db->join('tbl_services_type as Services','Services.service_id = TransTbl.service_id','left');
                //get accountno and ifsc
                $this->db->join('tbl_dmt_benificiary_dtls as Benificiary','Benificiary.recipient_id = TransTbl.recipient_id','left');
                //get accountno and ifsc
                
                $this->db->where('TransTbl.group_id',$group_id);
                $this->db->where('TransTbl.id_deleted', 0);
                $this->db->order_by("TransTbl.id", "desc");
                $query = $this->db->get();        
                $transactions = $query->result_array();
                
                # For sum of charges of all transaction
                $this->db->select('sum(TransTbl.total_amount) as amount, 
                                  sum(TransTbl.charge_amount) as charge_amount, 
                                  sum(TransTbl.CCFcharges) as CCFcharges, 
                                  sum(TransTbl.Cashback) as Cashback, 
                                  sum(TransTbl.TDSamount) as TDSamount, 
                                  sum(TransTbl.PayableCharge) as PayableCharge, 
                                  sum(TransTbl.FinalAmount) as FinalAmount');
                $this->db->from('tbl_transaction_dtls as TransTbl');
                $this->db->join('tbl_services_type as Services','Services.service_id = TransTbl.service_id','left');
                //get accountno and ifsc
                $this->db->join('tbl_dmt_benificiary_dtls as Benificiary','Benificiary.recipient_id = TransTbl.recipient_id','left');
                //get accountno and ifsc
                
                $this->db->where('TransTbl.group_id',$group_id);
                $this->db->where('TransTbl.id_deleted', 0);
                $this->db->order_by("TransTbl.id", "desc");
                $query = $this->db->get();        
                $transactions_count = $query->row_array();
           
                // $money_re=$this->transactions_model->getmoneyreport($txn_id);
            
            // print_r($money_re);
            $response = array(
              'status' => "true",
              'msg' => "Success",
              'groups' => "yes",
              'result' => $tarr,
              'request' =>$trarr,
              'money'=>$transactions,
              'transaction_sums'=>$transactions_count,
            );
           $numItems=count($tarr);
           if(++$aso === $numItems) {
             
             $orderwhere=array("group_id"=>$group_id);
             $trans_de=$this->moneyTransferApi_model->getdata_where("tbl_transaction_dtls",$orderwhere);
          
          foreach($trans_de as $tval){
            $transid[]=$tval->order_id;
          }
          //print_r($transid);
          $trans_ids=implode(',',$transid);
          //send sms 
          $digits = 5;
          $rand=rand(pow(10, $digits-1), pow(10, $digits)-1);
          //echo "$rand and " . ($startDate + $rand);
          $username="anandkaushal.in";
          $password="Budd789";
          $msisdn=$mobile;
          $sid="SRLSHD";
          $msg="Transaction Done TID: ".$trans_ids." -,Amt : Rs ".$amount." Fees: 1 % Name :".$money->name."  A/c : ".$money->bank_account_number." IFSC: ".$money->ifsc." SMARTPAY - ";
          //$msg="".$rand."%20is%20your%20verification%20code.%20Regards%20Saral%20Shaadi.";
          $url="http://cloud.smsindiahub.in/vendorsms/pushsms.aspx?user=".$username."&password=".$password."&msisdn=".$msisdn."&sid=".$sid."&msg=".$msg."&fl=0&gwid=2";
         $ch = curl_init();  
         curl_setopt($ch,CURLOPT_URL,$url);
         curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
         $output=curl_exec($ch);
         curl_close($ch);
         //send sms
            }
            
           
        }//result success end
        else if($tarr[$j]['success'] != "true" ){

          $trans_info = 
          // array('transaction_id' =>$tarr[$j]['data']['id'],
          array('transaction_id' =>'',
                'transaction_status' => $this->isValid($tarr[$j]['status']),                
                'service_id' =>$service_id, 
                'operator_id'=>$operator_id,
                'api_id' =>$api_details->api_id,
                'trans_date' => date("Y-m-d H:i:s"),
                'group_id'   =>$this->isValid($group_id),
                'order_id' => $order_id,  
                'mobileno' =>$mobile, 
                'user_id' => $user_id,          
                'total_amount' =>$splitarr[$j],
                'charge_amount' =>0,
                'transaction_type' =>0, //add
                'bank_transaction_id' =>$tarr[$j]['data']['bank_ref_num']."", //add
                'imps_name' =>$receipt_details->recipient_name."", //add
                'recipient_id' =>$data['recipient_id'], //add
                'charges_tax' =>0 , //add
                'commission' =>0, //add
                'commission_tax' =>'0', //add
                'commission_tds' =>0, //add
                'debit_amount' => 0,
                'balance' =>0,
                'order_status' => "FAILED",
                // 'transaction_msg'=>$tarr[$j]['reason'],
                'transaction_msg'=>$tarr[$j]['message'],
                'CCFcharges'=>$ccf,
                'Cashback'=>$cashback,
                'TDSamount'=>$TDS,
                'PayableCharge'=>$PayableCharge,
                'FinalAmount'=>$totalAmount,
                'request_amount'=>$amount,
                'updated_on'=>date('Y-m-d H:i:s'),
              );
             $txn_id = $this->transactions_model->addNewTransaction($trans_info);
             $userbalance = $this->rechargeApi_model->getUserBalance($data['user_id']);
              $wallet_balance=$userbalance->wallet_balance;
                $updatedBalance = $wallet_balance+$totalAmount; 
                 //insert DEBIT txn into tbl_wallet_trans_dtls table
                 $wallet_trans_info = array(
                  'service_id' =>$service_id,
                  'order_id' => $order_id, 
                  'group_id'   =>$this->isValid($group_id),
                  'user_id' => $user_id, 
                  'operator_id' => $operator_id,
                  'api_id' => $api_details->api_id,
                  'transaction_status' => $this->isValid($result['message']),
                  'transaction_type' => "CREDIT",
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
                  'msg' => $tarr[$j]['message'],
                  'result' => $tarr
                );  
        }
        }//split array end
      }
      //Cashfree Integration Starts Here
      else if($api_details->api_id == "22" or $api_details->api_id == 22){//cashfree
      	$url      =$api_details->api_url."/api/transfers/initiate";
      	//Generate Token
          $clientid="CF154737C6L0GFLJDDO8UP2KFU3GASXDSACSACAS";
          $clientsecret="11bd7c7cc53eb959188b10fcc7c282a067ad4997SCDSZCSEDCAWEDASDWCWEC";
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
        $api_token=$token;
      	$mobile=$data['sender_mobile_number'];
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
        }
        //check avalibe limit in sender table end
         $splitre=$this->split_amount($amount);
         $splitarr=json_decode($splitre);
         $scount=count($splitarr);
        
         $ores_arr=[];
        for($k= 0 ; $k < $scount; $k++){
          $order_uni =$this->get_order_id();
          $ores_new  =json_decode($order_uni,true);
          $ores_arr[$k]=$order_uni;
                  //validated order_id end
                    $calamout=$splitarr[$k];
                    //  $order_id=$tarr[$j]['data']['reference_numbe'];
                    $order_id=$ores_new['order_id'];
                    
                    // $post_data[$k]=array(
                    //   "amount"=>$splitarr[$k],
                    //   "payment_type"=>$data['transaction_type'],
                    //   "ifsc"   =>$receipt_details->ifsc,
                    //   "number"=>$receipt_details->bank_account_number,
                    //   "note"  =>"Fund Transfer",
                    //   "reference_number"=>$order_id);
                    // $api_info = array(
                    //     'service_id' => $service_id."", 
                    //     'api_id' => $api_details->api_id."", 
                    //     'api_name' => $api_details->api_name."",  
                    //     'api_method' => "doMultipleFundTransfer",
                    //     'api_url' => $url."", 
                    //     'group_id'=>$group_id."",
                    //       'order_id' => $order_id."", 
                    //     'user_id' => $user_id."",  
                    //     'request_input' => $post_data[$k],
                    //     'request' => $post_data[$k],         
                    //     'response' =>  '',
                    //     'access_type' => "APP",
                    //     'updated_on'=>date('Y-m-d H:i:s'),
                    //     );
                        // $api_log_id = $this->apiLog_model->addApiLogDetails($api_info);
                        $commissionDtl = $this->rechargeApi_model->getcommission($service_id,$user_package_id,$operator_id,$calamout);
                      $ccf=$commissionDtl->ccf_commission;
                      //  $charge = $commissionDtl->retailer_commission;
                      if($commissionDtl->retailer_commission_type == 'Percent'){
                        $charge = $calamout * ($commissionDtl->retailer_commission/100);
                      }elseif($commissionDtl->retailer_commission_type == 'Rupees'){
                        $charge = $commissionDtl->retailer_commission;
                      }
                      $cashback=$ccf-$charge;
                      //cashback update to trans table based on order_id
                      $app    =$this->rechargeApi_model->getTDS();
                      $TDS     =$cashback*($app->value/100);
                      $PayableCharge = $charge+$TDS;
                      $totalAmount=$calamout+$PayableCharge;
                    //save api log details end
                    //update balance after deduction begin
                    //$updatedBalance = $wallet_balance-$amount;
                    $wallet_balance_info = $this->rechargeApi_model->getUserBalance($user_id);
                    $wallet_balance = $wallet_balance_info->wallet_balance;

                  $updatedBalance = $wallet_balance-$totalAmount;
                    //insert DEBIT txn into tbl_wallet_trans_dtls table
                  $wallet_trans_info = array(
                  'service_id' =>$service_id,
                  'order_id' => $order_id, 
                  'user_id' => $user_id,
                  'group_id'=>$group_id."", 
                  'operator_id' => $operator_id,
                  'api_id' => $api_details->api_id,
                  'transaction_status' =>"",
                  'transaction_type' => "DEBIT",
                  'payment_type' => "SERVICE",
                  'payment_mode' => "PAID FOR DMT, ACCOUNT NUMBER ".$receipt_details->bank_account_number.", AMOUNT ".$totalAmount.", CHARGE ".$PayableCharge,
                  'transaction_id' => "",               
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
                
              //  $wallet_txn_id = $this->transactions_model->addNewWalletTransaction($wallet_trans_info);
                //update balance into users table                           
           //     $updateBalQry = $this->rechargeApi_model->updateUserBalance($user_id,$updatedBalance);
                //update balance after deduction end 
        
        }





            $tarr=array();
            $trarr=array();
            $orderidarray=array();
            for($i= 0 ; $i < $scount; $i++){//split array start
            $headers=array('Content-Type:application/json','Authorization:'.$api_token.'');
            //  $order_uni         =$this->get_order_id();
            $order_uni         =$ores_arr[$i];
            $ores               =json_decode($order_uni,true);
            $sno_order_id       =$ores['sno_order_id'];
            $order_id           =$ores['order_id'];
            $mobile_number      =$data['sender_mobile_number'];
            $recipient_id       =$data['recipient_id'];
           //Cashfree Api sending starts here
           //Cashfree payment gateway begins
             //From Here Cash Free Integration Starts
          
          //$random=rand(1111,9999);
         // $order_id="test_".$random;
          //Transfer Amount
          
          $Params["beneId"]= $receipt_details->cfree_beneficiaryid;
          $Params["transferId"]= $order_id;
          $Params["amount"] = $splitarr[$i];
          $Params["transferMode"] = strtolower($data['transaction_type']);
        
          $post[$i] = json_encode($Params, JSON_UNESCAPED_SLASHES);
          
          $url="https://payout-api.cashfree.com/payout/v1/requestTransfer";
          $ch = curl_init($url);
          curl_setopt($ch, CURLOPT_POST, true);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $post[$i]);
          curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization:Bearer " . $api_token)); 
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
          $result_json1[$i] = curl_exec($ch);
          $result1[$i] =  json_decode($result_json1[$i], true);
          curl_close($ch);
          
           //Cashfree Ends
           // $staorder_id='DEMOREFNUM0123';
           // $status_url=$api_details->api_url."/api/transfers/status/".$staorder_id."";
          // $status_url[$i]=$api_details->api_url."/api/transfers/status/".$order_id."";
         
           //$result_json[$i]=$this->getcurl_with_header($status_url[$i],$headers);
           //$result[$i] =  json_decode($result_json[$i], true);




           array_push($tarr,$result1[$i]);
           array_push($trarr,$post[$i]);
           array_push($orderidarray,$order_id);
             
          }//split array end
    //  print_r($trarr);
        
           //for status check bt vishal
        for ($m=0; $m < count($tarr); $m++) { 
          
          // $order_id=json_decode($trarr[$m], true)['orderId'];
          $headers=array('Content-Type:application/json','Authorization:Bearer '.$api_token.'');
          $order_id=$trarr[$m]['transferId'];
          $status_url[$m]="https://payout-api.cashfree.com/payout/v1/getTransferStatus?transferId=".$order_id."";
          if ($tarr[$m]['status']) {

            if ($tarr[$m]['status']=='PENDING') {

              if ($data['transaction_type'] != 'NEFT') {
                $break_count = 0;
                while($tarr[$m]['status'] != 'SUCCESS') {
                  if($break_count>39)
                    break;

                  $result_json[$im]=$this->getcurl_with_header($status_url[$m],$headers);
                    $tarr[$m] =  json_decode($result_json[$m], true);

                    $break_count++;
                   
                }
              }

            }
          }

        }
          // echo "<pre>";
          //   print_r($tarr);
          //   exit();
          
          $aso=0;
        //echo "Total Count is ".count($tarr);
             for($j= 0 ; $j < count($tarr); $j++){//split array start
           
           //validated order_id end
           $calamout=$splitarr[$j];
          //  $order_id=$tarr[$j]['data']['reference_numbe'];
          $order_id=$trarr[$j]['transferId'];
          
           $post_data[$j]=array(
            "amount"=>$splitarr[$j],
            "payment_type"=>$data['transaction_type'],
            "ifsc"   =>$receipt_details->ifsc,
            "number"=>$receipt_details->bank_account_number,
            "note"  =>"Fund Transfer",
            "reference_number"=>$order_id);
           $api_info = array(
               'service_id' => $service_id."", 
               'api_id' => $api_details->api_id."", 
               'api_name' => $api_details->api_name."",  
               'api_method' => "doMultipleFundTransfer",
               'api_url' => $url."", 
               'group_id'=>$group_id."",
                'order_id' => $order_id."", 
               'user_id' => $user_id."",  
               'request_input' =>  json_encode($post_data[$j], JSON_UNESCAPED_SLASHES),
               'request' => json_encode($post_data[$j], JSON_UNESCAPED_SLASHES),        
               'response' =>  json_encode($tarr[$j]),
               'access_type' => "APP",
               'updated_on'=>date('Y-m-d H:i:s'),
               );

              $api_log_id = $this->apiLog_model->addApiLogDetails($api_info);
              $commissionDtl = $this->rechargeApi_model->getcommission($service_id,$user_package_id,$operator_id,$calamout);
             $ccf=$commissionDtl->ccf_commission;
            //  $charge = $commissionDtl->retailer_commission;
            if($commissionDtl->retailer_commission_type == 'Percent'){
              $charge = $calamout * ($commissionDtl->retailer_commission/100);
            }elseif($commissionDtl->retailer_commission_type == 'Rupees'){
              $charge = $commissionDtl->retailer_commission;
            }
             $cashback=$ccf-$charge;
             //cashback update to trans table based on order_id
             $app    =$this->rechargeApi_model->getTDS();
             $TDS     =$cashback*($app->value/100);
             $PayableCharge = $charge+$TDS;
             $totalAmount=$calamout+$PayableCharge;
          //save api log details end
          //update balance after deduction begin
          //$updatedBalance = $wallet_balance-$amount;
          $wallet_balance_info = $this->rechargeApi_model->getUserBalance($user_id);
          $wallet_balance = $wallet_balance_info->wallet_balance;

         $updatedBalance = $wallet_balance-$totalAmount;
           //insert DEBIT txn into tbl_wallet_trans_dtls table
         $wallet_trans_info = array(
        'service_id' =>$service_id,
        'order_id' => $orderidarray[$j], 
        'user_id' => $user_id,
        'group_id'=>$group_id."", 
        'operator_id' => $operator_id,
        'api_id' => $api_details->api_id,
        'transaction_status' =>"",
        'transaction_type' => "DEBIT",
        'payment_type' => "SERVICE",
        'payment_mode' => "PAID FOR DMT, ACCOUNT NUMBER ".$receipt_details->bank_account_number.", AMOUNT ".$totalAmount.", CHARGE ".$PayableCharge,
        'transaction_id' => $tarr[$j]['data']['utr']."",   
        'bank_trans_id' => $tarr[$j]['data']['utr']."",   
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
        $pending_arr = array('pending', 'PENDING','Pending');
        $oStatus='';
        if (in_array($tarr[$j]['status'], $pending_arr)){
            $oStatus = 'PENDING';
        }
        else
        {
            $oStatus = 'SUCCESS';
        }
        
        if($tarr[$j]['status'] == "SUCCESS" or $tarr[$j]['status'] == "Pending" or $tarr[$j]['status'] == "pending" or $tarr[$j]['status'] == "PENDING"){//result success start
           
           //send sms 
            $digits = 5;
            $rand=rand(pow(10, $digits-1), pow(10, $digits)-1);
            //echo "$rand and " . ($startDate + $rand);
            $username="NAIDUSOFTWARE";
            $password="4049102";
            $sender_mobile_number=$data['sender_mobile_number'];
            $msisdn=$sender_mobile_number;
            $sid="PYMAMA";
            $receipt_details=$this->moneyTransferApi_model->get_singlebyid('tbl_dmt_benificiary_dtls','recipient_id',$data['recipient_id']);
            
            // $msg="Transaction Done TID: ".$txn_id." -  order id ".$money_re->order_no.", Amt : Rs ".$amount." Fees: 1 % Name :".$money_re->name."  A/c : ".$money_re->bank_account_number." IFSC: ".$money_re->ifsc." SMARTPAY - ";
              // $msg="TID: ".$money_re->order_no." Amt : Rs ".$amount." Fees: 1 %, Name : ".$money_re->name." A/c : ".$money_re->bank_account_number." Mobile ".$benificiary_details->recipient_mobile_number." IFSC: ".$money_re->ifsc." www.paymamaapp.in";
            $msg="TID : ".$orderidarray[$j]." Amt : ".$splitarr[$j]." Fees: 1 %, Name: ".$receipt_details->verified_name." A/c: ".$receipt_details->bank_account_number." Mobile ".$receipt_details->recipient_mobile_number." Bank ".$receipt_details->bank_name." IFSC: ".$receipt_details->ifsc." www.paymamaapp.in";
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
            $template_id=1207163818494893617;
            $sms_output = $this->sendBulkSMS($msisdn, $msg, $template_id);
         //send sms
           
           
           $trans_info = array(
            'transaction_id' =>$tarr[$j]['data']['referenceId'],
            'transaction_status' =>$tarr[$j]['status'], 
            'service_id' => $service_id, 
            'operator_id'=>$operator_id,
            'api_id' => $api_details->api_id,
            'trans_date' => date("Y-m-d H:i:s"),
            'order_id' => $orderidarray[$j],
            'group_id'   =>$this->isValid($group_id),  
            'mobileno' =>$mobile, 
            'user_id' => $this->isValid($user_id),          
            'total_amount' =>$splitarr[$j],
            'charge_amount' =>0,
            'transaction_type' =>$transtypes1, //add
            'bank_transaction_id' =>$tarr[$j]['data']['utr']."", //add
            'imps_name' =>$receipt_details->recipient_name."" , //add
            'recipient_id' =>$recip_id, //add
            'charges_tax' =>0, //add
            'commission' =>0, //add
            'commission_tax' =>0, //add
            'commission_tds' =>0, //add
            'debit_amount' =>0,
            'balance' =>0,
            'order_status' => $oStatus,
            'transaction_msg'=>$tarr[$j]['message'],
            'CCFcharges'=>$ccf,
            'Cashback'=>$cashback,
            'TDSamount'=>$TDS,
            'PayableCharge'=>$PayableCharge,
            'FinalAmount'=>$totalAmount,
            'request_amount'=>$amount,
            'updated_on'=>date('Y-m-d H:i:s'),
            );
           // echo "<pre>";
          
          $txn_id = $this->transactions_model->addNewTransaction($trans_info);
          //update balance based on api id in api setting table developed by susmitha start
          $currentapibal=0;
          $data = array('balance'=>$currentapibal);
           $this->apiLog_model->update_api_amount($data,$api_details->api_id);
          //update balance based on api id in apisetting table developed by susmitha end
          //update sender avaland used limit start
          $sender_det  =array("sender_mobile_number"=>$mobile);
          $sender_details =$this->moneyTransferApi_model->getdata_where('tbl_sender_dts',$sender_det);
          $available_limit=($sender_details[0]->available_limit)-($splitarr[$j]);
          $used_limit=($sender_details[0]->used_limit)+($splitarr[$j]);
          $sender_detupdate=array("available_limit"=>$available_limit,
                                 "used_limit"=>$used_limit);
          //print_r($sender_detupdate);
          $this->db->where('sender_mobile_number',$mobile);
          $this->db->update('tbl_sender_dts',$sender_detupdate);
         //update sender avaland used limit end
          $recipent_ver  =array("recipient_id"=>$data['recipient_id'],"is_verified"=>"Y");
         $benificiary_details =$this->moneyTransferApi_model->getdata_where('tbl_dmt_benificiary_dtls',$recipent_ver);
           //print_r($benificiary_details);
          if(empty($benificiary_details)){
            $updatebeni=array("is_verified"=>"Y","verified_name"=>$tarr[$j]['data']['transfer_account_holder']);
          $where=array('recipient_id',$data['recipient_id']); 
          $this->db->where($where);
          $this->db->update('tbl_dmt_benificiary_dtls',$updatebeni);
          }
         //Commission details added by ashik begin
            if ($commissionDtl) {
              if($commissionDtl->commission_type == "Rupees"){
                $admin_commission = $commissionDtl->admin_commission;
                $md_commission = $commissionDtl->md_commission;
                $api_commission = $commissionDtl->api_commission;
                $distributor_commission = $commissionDtl->distributor_commission;
                $retailer_commission = $commissionDtl->retailer_commission;
              }else if($commissionDtl->commission_type == "Percent"){
                $admin_commission = ($calamout*$commissionDtl->admin_commission)/100;
                $md_commission = ($calamout*$commissionDtl->md_commission)/100;
                $api_commission = ($calamout*$commissionDtl->api_commission)/100;
                $distributor_commission = ($calamout*$commissionDtl->distributor_commission)/100;
                $retailer_commission = ($calamout*$commissionDtl->retailer_commission)/100;
              }else if($commissionDtl->commission_type == "Range"){
                if($commissionDtl->admin_commission_type == "Rupees")
                  $admin_commission = $commissionDtl->admin_commission;
                else
                  $admin_commission = ($calamout*$commissionDtl->admin_commission)/100;
                if($commissionDtl->md_commission_type == "Rupees")
                  $md_commission = $commissionDtl->md_commission;
                else
                  $md_commission = ($calamout*$commissionDtl->md_commission)/100;
                if($commissionDtl->distributor_commission_type == "Rupees")
                  $distributor_commission = $commissionDtl->distributor_commission;
                else
                  $distributor_commission = ($calamout*$commissionDtl->distributor_commission)/100;
                if($commissionDtl->retailer_commission_type == "Rupees")
                  $retailer_commission = $commissionDtl->retailer_commission;
                else
                  $retailer_commission = ($calamout*$commissionDtl->retailer_commission)/100;
                  $api_commission = $commissionDtl->api_commission;
              }
            }
            //Commission details added by ashik end 
            //commission wallet txn begin
            if(is_numeric($role_id) && intval($role_id) <= 4){                
              $walletUserID = $user_id;
              $walletRoleID = $role_id;
              $isUserBalanceUpdated = false;
              
              for($k=$walletRoleID;$k>=1;$k--){                
                if($k==3 || $k==4  || $i==7){
                  $isUserBalanceUpdated = true;
                  $userParentID = $this->rechargeApi_model->getUserParentID($walletUserID);
                  if ($isUserBalanceUpdated && $userParentID && ($userParentID->roleId==3||$userParentID->roleId==4||$userParentID->roleId==7)) {
                    $walletUserID = $userParentID->userId;
                    $walletRoleID = $userParentID->roleId;
                    $updatedBalance = $userParentID->wallet_balance;
                  }
                  continue;
                }
                $walletAmt = 0;
                $walletBal = 0; 
                $distds="";                
                $userParentID = $this->rechargeApi_model->getUserParentID($walletUserID);
                 if ($isUserBalanceUpdated && $userParentID ) {
                  $walletUserID = $userParentID->userId;
                  $walletRoleID = $userParentID->roleId;
                  $updatedBalance = $userParentID->wallet_balance;
                }                
                /*if($walletRoleID == 4){ //Retailer
                  $walletAmt = $retailer_commission;
                  $walletBal = $updatedBalance+$retailer_commission;
                }/*else if($walletRoleID == 3){ //FOS
                  $walletAmt = $distributor_commission;
                  $walletBal = $updatedBalance+$distributor_commission;
                }else*/ if($walletRoleID == 2){ //Distributor
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
                  if($walletBal < 0){
                    $transType = "DEBIT";
                  }
                  $wallet_trans_info = array(
                    'service_id' => $service_id,
                    'order_id' => $orderidarray[$j], 
                    'user_id' =>$walletUserID,
                    'group_id'   =>$this->isValid($group_id), 
                    'operator_id' => $operator_id,
                    'api_id' => $api_details->api_id,
                    'transaction_status' =>'Success',
                    'transaction_type' => $transType,
                    'payment_type' => "COMMISSION",
                    'payment_mode' => "Commission by Money Transfer",
                    'bank_trans_id' =>$tarr[$j]['data']['utr']."",
                    'transaction_id' =>$tarr[$j]['data']['utr'],               
                    'trans_date' => date("Y-m-d H:i:s"),  
                    'total_amount' => $walletAmt,
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
            
           
             //$money=$this->transactions_model->getmoney_domultiple_group_report($group_id);
             
             //to be removed after success
             
            # query with sum function
             
            /*  $this->db->select('TransTbl.order_id as order_no,TransTbl.group_id,TransTbl.trans_date as trans_date,TransTbl.imps_name as name,TransTbl.mobileno as sender_no, TransTbl.transaction_type as mode,sum(TransTbl.total_amount) as amount,sum(TransTbl.charge_amount) as charge_amount,Services.service_name,
                TransTbl.bank_transaction_id,TransTbl.order_status,TransTbl.remarks,sum(TransTbl.CCFcharges) as CCFcharges,sum(TransTbl.Cashback) as Cashback,sum(TransTbl.TDSamount) as TDSamount,sum(TransTbl.PayableCharge) as PayableCharge,sum(TransTbl.FinalAmount) as FinalAmount,Benificiary.bank_account_number,Benificiary.ifsc');
                $this->db->from('tbl_transaction_dtls as TransTbl');
                $this->db->join('tbl_services_type as Services','Services.service_id = TransTbl.service_id','left');
                //get accountno and ifsc
                $this->db->join('tbl_dmt_benificiary_dtls as Benificiary','Benificiary.recipient_id = TransTbl.recipient_id','left');
                //get accountno and ifsc
                
                $this->db->where('TransTbl.group_id',$group_id);
                $this->db->where('TransTbl.id_deleted', 0);
                $this->db->order_by("TransTbl.id", "desc");
                $query = $this->db->get();        
                $transactions = $query->result(); 
                */
                
                $this->db->select('TransTbl.order_id as order_no, 
                                  TransTbl.group_id, 
                                  TransTbl.trans_date as trans_date, 
                                  TransTbl.imps_name as name, 
                                  TransTbl.mobileno as sender_no, 
                                  TransTbl.transaction_type as mode, 
                                  Services.service_name, 
                                  TransTbl.bank_transaction_id, 
                                  TransTbl.order_status, 
                                  TransTbl.total_amount,
                                  TransTbl.remarks, 
                                  Benificiary.bank_account_number, 
                                  Benificiary.ifsc');
                $this->db->from('tbl_transaction_dtls as TransTbl');
                $this->db->join('tbl_services_type as Services','Services.service_id = TransTbl.service_id','left');
                //get accountno and ifsc
                $this->db->join('tbl_dmt_benificiary_dtls as Benificiary','Benificiary.recipient_id = TransTbl.recipient_id','left');
                //get accountno and ifsc
                
                $this->db->where('TransTbl.group_id',$group_id);
                $this->db->where('TransTbl.id_deleted', 0);
                $this->db->order_by("TransTbl.id", "desc");
                $query = $this->db->get();        
                $transactions = $query->result_array();
                
                # For sum of charges of all transaction
                $this->db->select('sum(TransTbl.total_amount) as amount, 
                                  sum(TransTbl.charge_amount) as charge_amount, 
                                  sum(TransTbl.CCFcharges) as CCFcharges, 
                                  sum(TransTbl.Cashback) as Cashback, 
                                  sum(TransTbl.TDSamount) as TDSamount, 
                                  sum(TransTbl.PayableCharge) as PayableCharge, 
                                  sum(TransTbl.FinalAmount) as FinalAmount');
                $this->db->from('tbl_transaction_dtls as TransTbl');
                $this->db->join('tbl_services_type as Services','Services.service_id = TransTbl.service_id','left');
                //get accountno and ifsc
                $this->db->join('tbl_dmt_benificiary_dtls as Benificiary','Benificiary.recipient_id = TransTbl.recipient_id','left');
                //get accountno and ifsc
                
                $this->db->where('TransTbl.group_id',$group_id);
                $this->db->where('TransTbl.id_deleted', 0);
                $this->db->order_by("TransTbl.id", "desc");
                $query = $this->db->get();        
                $transactions_count = $query->row_array();
           
                // $money_re=$this->transactions_model->getmoneyreport($txn_id);
            
            // print_r($money_re);
            $response = array(
              'status' => "true",
              'msg' => "Success",
              'groups' => "yes",
              'result' => $tarr,
              'request' =>$trarr,
              'money'=>$transactions,
              'transaction_sums'=>$transactions_count,
            );
           $numItems=count($tarr);
           if(++$aso === $numItems) {
             
             $orderwhere=array("group_id"=>$group_id);
             $trans_de=$this->moneyTransferApi_model->getdata_where("tbl_transaction_dtls",$orderwhere);
          
          foreach($trans_de as $tval){
            $transid[]=$tval->order_id;
          }
          //print_r($transid);
          $trans_ids=implode(',',$transid);
          //send sms 
          //send sms 
            // $digits = 5;
            // $rand=rand(pow(10, $digits-1), pow(10, $digits)-1);
            // //echo "$rand and " . ($startDate + $rand);
            // $username="NAIDUSOFTWARE";
            // $password="4049102";
            // $sender_mobile_number=$data['sender_mobile_number'];
            // $msisdn=$sender_mobile_number;
            // $sid="PYMAMA";
            // $receipt_details=$this->moneyTransferApi_model->get_singlebyid('tbl_dmt_benificiary_dtls','recipient_id',$data['recipient_id']);
            
            // // $msg="Transaction Done TID: ".$txn_id." -  order id ".$money_re->order_no.", Amt : Rs ".$amount." Fees: 1 % Name :".$money_re->name."  A/c : ".$money_re->bank_account_number." IFSC: ".$money_re->ifsc." SMARTPAY - ";
            //   // $msg="TID: ".$money_re->order_no." Amt : Rs ".$amount." Fees: 1 %, Name : ".$money_re->name." A/c : ".$money_re->bank_account_number." Mobile ".$benificiary_details->recipient_mobile_number." IFSC: ".$money_re->ifsc." www.paymamaapp.in";
            // $msg="TID : ".$order_id." Amt : ".$amount." Fees: 1 %, Name: ".$receipt_details->verified_name." A/c: ".$receipt_details->bank_account_number." Mobile ".$receipt_details->recipient_mobile_number." Bank ".$receipt_details->bank_name." IFSC: ".$receipt_details->ifsc." www.paymamaapp.in";
            //   //TID : {#var#} Amt : {#var#} Fees: 1 %, Name : {#var#} A/c : {#var#} Mobile {#var#} Bank {#var#} IFSC: {#var#} www.paymamaapp.in
            //   //$msg="".$rand."%20is%20your%20verification%20code.%20Regards%20Saral%20Shaadi.";
            // $tem_sms_row= $this->db->select('*')->from('tbl_sms_templates')->where('alias', 'money_transfer')->get()->row();
            
            // //comment by vishal
            // //   $url="http://cloud.smsindiahub.in/vendorsms/pushsms.aspx?user=".$username."&password=".$password."&msisdn=".$msisdn."&sid=".$sid."&msg=".$msg."&fl=0&gwid=2";
            // //  $ch = curl_init();  
            // //  curl_setopt($ch,CURLOPT_URL,$url);
            // //  curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
            // //  $output=curl_exec($ch);
            // //  curl_close($ch);
            // $template_id=1207163818494893617;
            //   $sms_output = $this->sendBulkSMS($msisdn, $msg, $template_id);
         //send sms
            }
            
           
        }//result success end
        else if($tarr[$j]['status'] != "SUCCESS" ){

          $trans_info =   // array('transaction_id' =>$tarr[$j]['data']['id'],
          array('transaction_id' =>'ssssss',
                'transaction_status' => $this->isValid($tarr[$j]['status']),                
                'service_id' =>$service_id, 
                'operator_id'=>$operator_id,
                'api_id' =>$api_details->api_id,
                'trans_date' => date("Y-m-d H:i:s"),
                'group_id'   =>$this->isValid($group_id),
                'order_id' => $order_id,  
                'mobileno' =>$mobile, 
                'user_id' => $user_id,          
                'total_amount' =>$splitarr[$j],
                'charge_amount' =>0,
                'transaction_type' =>$transtypes1, //add
                'bank_transaction_id' =>$tarr[$j]['data']['bank_ref_num']."", //add
                'imps_name' =>$receipt_details->recipient_name."", //add
                'recipient_id' =>$data['recipient_id'], //add
                'charges_tax' =>0 , //add
                'commission' =>0, //add
                'commission_tax' =>'0', //add
                'commission_tds' =>0, //add
                'debit_amount' => 0,
                'balance' =>0,
                'order_status' => "FAILED",
                // 'transaction_msg'=>$tarr[$j]['reason'],
                'transaction_msg'=>$tarr[$j]['message'],
                'CCFcharges'=>$ccf,
                'Cashback'=>$cashback,
                'TDSamount'=>$TDS,
                'PayableCharge'=>$PayableCharge,
                'FinalAmount'=>$totalAmount,
                'request_amount'=>$amount,
                'updated_on'=>date('Y-m-d H:i:s'),
              );
              print_r($trans_info);
             $txn_id = $this->transactions_model->addNewTransaction($trans_info);
             $userbalance = $this->rechargeApi_model->getUserBalance($data['user_id']);
              $wallet_balance=$userbalance->wallet_balance;
                $updatedBalance = $wallet_balance+$totalAmount; 
                 //insert DEBIT txn into tbl_wallet_trans_dtls table
                 $wallet_trans_info = array(
                  'service_id' =>$service_id,
                  'order_id' => $order_id, 
                  'group_id'   =>$this->isValid($group_id),
                  'user_id' => $user_id, 
                  'operator_id' => $operator_id,
                  'api_id' => $api_details->api_id,
                  'transaction_status' => $this->isValid($result['message']),
                  'transaction_type' => "CREDIT",
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
                  'msg' => $tarr[$j]['message'],
                  'result' => $tarr
                );  
        }
        }//split array end
      }
      else if($api_details->api_id == "14" or $api_details->api_id == 14){//Razorpay
        
      	$url=$api_details->api_url."/api/transfers/initiate";
      	
        $api_token=$token;
      	$mobile=$data['sender_mobile_number'];
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
        }
        //check avalibe limit in sender table end
         $splitre=$this->split_amount($amount);
         $splitarr=json_decode($splitre);
         $scount=count($splitarr);
        
         $ores_arr=[];
         
         
         
         
         
         
        for($k= 0 ; $k < $scount; $k++){
          $order_uni =$this->get_order_id();
          $ores_new  =json_decode($order_uni,true);
          $ores_arr[$k]=$order_uni;
                  //validated order_id end
                    $calamout=$splitarr[$k];
                    //  $order_id=$tarr[$j]['data']['reference_numbe'];
                    $order_id=$ores_new['order_id'];
                    
                    // $post_data[$k]=array(
                    //   "amount"=>$splitarr[$k],
                    //   "payment_type"=>$data['transaction_type'],
                    //   "ifsc"   =>$receipt_details->ifsc,
                    //   "number"=>$receipt_details->bank_account_number,
                    //   "note"  =>"Fund Transfer",
                    //   "reference_number"=>$order_id);
                    // $api_info = array(
                    //     'service_id' => $service_id."", 
                    //     'api_id' => $api_details->api_id."", 
                    //     'api_name' => $api_details->api_name."",  
                    //     'api_method' => "doMultipleFundTransfer",
                    //     'api_url' => $url."", 
                    //     'group_id'=>$group_id."",
                    //       'order_id' => $order_id."", 
                    //     'user_id' => $user_id."",  
                    //     'request_input' => $post_data[$k],
                    //     'request' => $post_data[$k],         
                    //     'response' =>  '',
                    //     'access_type' => "APP",
                    //     'updated_on'=>date('Y-m-d H:i:s'),
                    //     );
                        // $api_log_id = $this->apiLog_model->addApiLogDetails($api_info);
                        $commissionDtl = $this->rechargeApi_model->getcommission($service_id,$user_package_id,$operator_id,$calamout);
                      $ccf=$commissionDtl->ccf_commission;
                      //  $charge = $commissionDtl->retailer_commission;
                      if($commissionDtl->retailer_commission_type == 'Percent'){
                        $charge = $calamout * ($commissionDtl->retailer_commission/100);
                      }elseif($commissionDtl->retailer_commission_type == 'Rupees'){
                        $charge = $commissionDtl->retailer_commission;
                      }
                      $cashback=$ccf-$charge;
                      //cashback update to trans table based on order_id
                      $app    =$this->rechargeApi_model->getTDS();
                      $TDS     =$cashback*($app->value/100);
                      $PayableCharge = $charge+$TDS;
                      $totalAmount=$calamout+$PayableCharge;
                    //save api log details end
                    //update balance after deduction begin
                    //$updatedBalance = $wallet_balance-$amount;
                    $wallet_balance_info = $this->rechargeApi_model->getUserBalance($user_id);
                    $wallet_balance = $wallet_balance_info->wallet_balance;

                  $updatedBalance = $wallet_balance-$totalAmount;
                    //insert DEBIT txn into tbl_wallet_trans_dtls table
                  $wallet_trans_info = array(
                  'service_id' =>$service_id,
                  'order_id' => $order_id, 
                  'user_id' => $user_id,
                  'group_id'=>$group_id."", 
                  'operator_id' => $operator_id,
                  'api_id' => $api_details->api_id,
                  'transaction_status' =>"",
                  'transaction_type' => "DEBIT",
                  'payment_type' => "SERVICE",
                  'payment_mode' => "PAID FOR DMT, ACCOUNT NUMBER ".$receipt_details->bank_account_number.", AMOUNT ".$totalAmount.", CHARGE ".$PayableCharge,
                  'transaction_id' => "",               
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
                
               // $wallet_txn_id = $this->transactions_model->addNewWalletTransaction($wallet_trans_info);
                //update balance into users table                           
             //   $updateBalQry = $this->rechargeApi_model->updateUserBalance($user_id,$updatedBalance);
                //update balance after deduction end 
        
        }





         $tarr=array();
         $trarr=array();
            $orderidarray=array();
         for($i= 0 ; $i < $scount; $i++){//split array start
           $headers=array('Content-Type:application/json','Authorization:'.$api_token.'');
          //  $order_uni         =$this->get_order_id();
           $order_uni         =$ores_arr[$i];
           $ores               =json_decode($order_uni,true);

           $sno_order_id       =$ores['sno_order_id'];
           $order_id           =$ores['order_id'];
           $mobile_number      =$data['sender_mobile_number'];
           $recipient_id       =$data['recipient_id'];
           //Cashfree Api sending starts here
           //Cashfree payment gateway begins
             //From Here Cash Free Integration Starts
          
          //$random=rand(1111,9999);
         // $order_id="test_".$random;
          //Transfer Amount
          
        
       // Razorpay payment started
          
             $paisa = $splitarr[$i]*100;
            $post[$i] = '{
                        "account_number": "4564567550186219",
                        "fund_account_id": "'.$receipt_details->razorpay_fund_acc_id.'",
                        "amount": '.$paisa.',
                        "currency": "INR",
                        "mode": "'. $data['transaction_type'].'",
                        "purpose": "payout",
                        "queue_if_low_balance": true,
                        "reference_id": "'.$order_id.'"
                      }';
            //save api log 
         
            
            $result1[$i] = $this->payout_Rezorpay($post[$i], $api_details);
            $response1[$i]=json_encode($result1[$i]);
          //echo "<pre>";
          //$update_api_dtls_arr =  array('response' => json_encode($result), 'updated_on'=>date('Y-m-d H:i:s') );
         // $update_api_log = $this->db->where('order_id', $order_id)->update('tbl_apilog_dts', $update_api_dtls_arr );
       // Razorpay Ends   
       
           //Cashfree Ends
           // $staorder_id='DEMOREFNUM0123';
           // $status_url=$api_details->api_url."/api/transfers/status/".$staorder_id."";
          // $status_url[$i]=$api_details->api_url."/api/transfers/status/".$order_id."";
         
           //$result_json[$i]=$this->getcurl_with_header($status_url[$i],$headers);
           //$result[$i] =  json_decode($result_json[$i], true);




           array_push($tarr,$result1[$i]);
           array_push($trarr,$post[$i]);
           array_push($orderidarray,$order_id);
             
          }//split array end
        
    //  print_r($trarr);
    //  print_r($tarr);
        
           //for status check bt vishal
        for ($m=0; $m < count($tarr); $m++) { 
          
          // $order_id=json_decode($trarr[$m], true)['orderId'];
          $headers=array('Content-Type:application/json','Authorization:Bearer '.$api_token.'');
          $order_id=$trarr[$m]['transferId'];
          $status_url[$m]="https://payout-api.cashfree.com/payout/v1/getTransferStatus?transferId=".$order_id."";
          if ($tarr[$m]['status']) {

            if ($tarr[$m]['status']=='PENDING') {

              if ($data['transaction_type'] != 'NEFT') {
                $break_count = 0;
                while($tarr[$m]['status'] != 'SUCCESS') {
                  if($break_count>39)
                    break;

                  $result_json[$im]=$this->getcurl_with_header($status_url[$m],$headers);
                    $tarr[$m] =  json_decode($result_json[$m], true);

                    $break_count++;
                   
                }
              }

            }
          }

        }
          // echo "<pre>";
          //   print_r($tarr);
          //   exit();
          
          $aso=0;
        //echo "Total Count is ".count($tarr);
             for($j= 0 ; $j < count($tarr); $j++){//split array start
           
           //validated order_id end
           $calamout=$splitarr[$j];
          //  $order_id=$tarr[$j]['data']['reference_numbe'];
          $order_id=$trarr[$j]['transferId'];
          
           $post_data[$j]=array(
            "amount"=>$splitarr[$j],
            "payment_type"=>$data['transaction_type'],
            "ifsc"   =>$receipt_details->ifsc,
            "number"=>$receipt_details->bank_account_number,
            "note"  =>"Fund Transfer",
            "reference_number"=>$order_id);
           $api_info = array(
               'service_id' => $service_id."", 
               'api_id' => $api_details->api_id."", 
               'api_name' => $api_details->api_name."",  
               'api_method' => "doMultipleFundTransfer",
               'api_url' => $url."", 
               'group_id'=>$group_id."",
                'order_id' => $orderidarray[$j]."", 
               'user_id' => $user_id."",  
               'request_input' =>  json_encode($post_data[$j], JSON_UNESCAPED_SLASHES),
               'request' => json_encode($post_data[$j], JSON_UNESCAPED_SLASHES),        
               'response' =>  json_encode($tarr[$j]),
               'access_type' => "APP",
               'updated_on'=>date('Y-m-d H:i:s'),
               );

              $api_log_id = $this->apiLog_model->addApiLogDetails($api_info);
              $commissionDtl = $this->rechargeApi_model->getcommission($service_id,$user_package_id,$operator_id,$calamout);
             $ccf=$commissionDtl->ccf_commission;
            //  $charge = $commissionDtl->retailer_commission;
            if($commissionDtl->retailer_commission_type == 'Percent'){
              $charge = $calamout * ($commissionDtl->retailer_commission/100);
            }elseif($commissionDtl->retailer_commission_type == 'Rupees'){
              $charge = $commissionDtl->retailer_commission;
            }
             $cashback=$ccf-$charge;
             //cashback update to trans table based on order_id
             $app    =$this->rechargeApi_model->getTDS();
             $TDS     =$cashback*($app->value/100);
             $PayableCharge = $charge+$TDS;
             $totalAmount=$calamout+$PayableCharge;
          //save api log details end
          //update balance after deduction begin
          //$updatedBalance = $wallet_balance-$amount;
          $wallet_balance_info = $this->rechargeApi_model->getUserBalance($user_id);
          $wallet_balance = $wallet_balance_info->wallet_balance;

         $updatedBalance = $wallet_balance-$totalAmount;
           //insert DEBIT txn into tbl_wallet_trans_dtls table
         $wallet_trans_info = array(
        'service_id' =>$service_id,
        'order_id' => $orderidarray[$j], 
        'user_id' => $user_id,
        'group_id'=>$group_id."", 
        'operator_id' => $operator_id,
        'api_id' => $api_details->api_id,
        'transaction_status' =>"",
        'transaction_type' => "DEBIT",
        'payment_type' => "SERVICE",
        'payment_mode' => "PAID FOR DMT, ACCOUNT NUMBER ".$receipt_details->bank_account_number.", AMOUNT ".$totalAmount.", CHARGE ".$PayableCharge,
        'transaction_id' => $tarr[$j]['data']['utr']."",
        'bank_trans_id' =>$tarr[$j]['data']['utr']."",
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
        $pending_arr = array('queued', 'pending', 'processing');
        
        $oStatus='';
        if (in_array($tarr[$j]['status'], $pending_arr)){
            $oStatus = 'PENDING';
        }
        else
        {
            $oStatus = 'SUCCESS';
        }
        
        if($tarr[$j]['status'] == "SUCCESS" or $tarr[$j]['status'] == "true" or $tarr[$j]['status'] == "queued" or $tarr[$j]['status'] == "pending"  or $tarr[$j]['status'] == "processing"  or $tarr[$j]['status'] == "processed"){//result success start
            
            //send sms 
            $digits = 5;
            $rand=rand(pow(10, $digits-1), pow(10, $digits)-1);
            //echo "$rand and " . ($startDate + $rand);
            $username="NAIDUSOFTWARE";
            $password="4049102";
            $sender_mobile_number=$data['sender_mobile_number'];
            $msisdn=$sender_mobile_number;
            $sid="PYMAMA";
            $receipt_details=$this->moneyTransferApi_model->get_singlebyid('tbl_dmt_benificiary_dtls','recipient_id',$data['recipient_id']);
            
            // $msg="Transaction Done TID: ".$txn_id." -  order id ".$money_re->order_no.", Amt : Rs ".$amount." Fees: 1 % Name :".$money_re->name."  A/c : ".$money_re->bank_account_number." IFSC: ".$money_re->ifsc." SMARTPAY - ";
              // $msg="TID: ".$money_re->order_no." Amt : Rs ".$amount." Fees: 1 %, Name : ".$money_re->name." A/c : ".$money_re->bank_account_number." Mobile ".$benificiary_details->recipient_mobile_number." IFSC: ".$money_re->ifsc." www.paymamaapp.in";
            $msg="TID : ".$orderidarray[$j]." Amt : ".$splitarr[$j]." Fees: 1 %, Name: ".$receipt_details->verified_name." A/c: ".$receipt_details->bank_account_number." Mobile ".$receipt_details->recipient_mobile_number." Bank ".$receipt_details->bank_name." IFSC: ".$receipt_details->ifsc." www.paymamaapp.in";
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
            $template_id=1207163818494893617;
            $sms_output = $this->sendBulkSMS($msisdn, $msg, $template_id);
         //send sms
         
           $trans_info = array(
            'transaction_id' =>$tarr[$j]['data']['referenceId'],
            'transaction_status' =>$tarr[$j]['status'], 
            'service_id' => $service_id, 
            'operator_id'=>$operator_id,
            'api_id' => $api_details->api_id,
            'trans_date' => date("Y-m-d H:i:s"),
            'order_id' => $orderidarray[$j],
            'group_id'   =>$this->isValid($group_id),  
            'mobileno' =>$mobile, 
            'user_id' => $this->isValid($user_id),          
            'total_amount' =>$splitarr[$j],
            'charge_amount' =>0,
            'transaction_type' =>0, //add
            'bank_transaction_id' =>$tarr[$j]['data']['utr']."", //add
            'imps_name' =>$receipt_details->recipient_name."" , //add
            'recipient_id' =>$recip_id, //add
            'charges_tax' =>0, //add
            'commission' =>0, //add
            'commission_tax' =>0, //add
            'commission_tds' =>0, //add
            'debit_amount' =>0,
            'balance' =>0,
            'order_status' => $oStatus,
            'transaction_msg'=>$tarr[$j]['message'],
            'CCFcharges'=>$ccf,
            'Cashback'=>$cashback,
            'TDSamount'=>$TDS,
            'PayableCharge'=>$PayableCharge,
            'FinalAmount'=>$totalAmount,
            'request_amount'=>$amount,
            'updated_on'=>date('Y-m-d H:i:s'),
            );
           // echo "<pre>";
          
          $txn_id = $this->transactions_model->addNewTransaction($trans_info);
          //update balance based on api id in api setting table developed by susmitha start
          $currentapibal=0;
          $data = array('balance'=>$currentapibal);
           $this->apiLog_model->update_api_amount($data,$api_details->api_id);
          //update balance based on api id in apisetting table developed by susmitha end
          //update sender avaland used limit start
          $sender_det  =array("sender_mobile_number"=>$mobile);
          $sender_details =$this->moneyTransferApi_model->getdata_where('tbl_sender_dts',$sender_det);
          $available_limit=($sender_details[0]->available_limit)-($splitarr[$j]);
          $used_limit=($sender_details[0]->used_limit)+($splitarr[$j]);
          $sender_detupdate=array("available_limit"=>$available_limit,
                                 "used_limit"=>$used_limit);
          //print_r($sender_detupdate);
          $this->db->where('sender_mobile_number',$mobile);
          $this->db->update('tbl_sender_dts',$sender_detupdate);
         //update sender avaland used limit end
          $recipent_ver  =array("recipient_id"=>$data['recipient_id'],"is_verified"=>"Y");
         $benificiary_details =$this->moneyTransferApi_model->getdata_where('tbl_dmt_benificiary_dtls',$recipent_ver);
           //print_r($benificiary_details);
          if(empty($benificiary_details)){
            $updatebeni=array("is_verified"=>"Y","verified_name"=>$tarr[$j]['data']['transfer_account_holder']);
          $where=array('recipient_id',$data['recipient_id']); 
          $this->db->where($where);
          $this->db->update('tbl_dmt_benificiary_dtls',$updatebeni);
          }
         //Commission details added by ashik begin
            if ($commissionDtl) {
              if($commissionDtl->commission_type == "Rupees"){
                $admin_commission = $commissionDtl->admin_commission;
                $md_commission = $commissionDtl->md_commission;
                $api_commission = $commissionDtl->api_commission;
                $distributor_commission = $commissionDtl->distributor_commission;
                $retailer_commission = $commissionDtl->retailer_commission;
              }else if($commissionDtl->commission_type == "Percent"){
                $admin_commission = ($calamout*$commissionDtl->admin_commission)/100;
                $md_commission = ($calamout*$commissionDtl->md_commission)/100;
                $api_commission = ($calamout*$commissionDtl->api_commission)/100;
                $distributor_commission = ($calamout*$commissionDtl->distributor_commission)/100;
                $retailer_commission = ($calamout*$commissionDtl->retailer_commission)/100;
              }else if($commissionDtl->commission_type == "Range"){
                if($commissionDtl->admin_commission_type == "Rupees")
                  $admin_commission = $commissionDtl->admin_commission;
                else
                  $admin_commission = ($calamout*$commissionDtl->admin_commission)/100;
                if($commissionDtl->md_commission_type == "Rupees")
                  $md_commission = $commissionDtl->md_commission;
                else
                  $md_commission = ($calamout*$commissionDtl->md_commission)/100;
                if($commissionDtl->distributor_commission_type == "Rupees")
                  $distributor_commission = $commissionDtl->distributor_commission;
                else
                  $distributor_commission = ($calamout*$commissionDtl->distributor_commission)/100;
                if($commissionDtl->retailer_commission_type == "Rupees")
                  $retailer_commission = $commissionDtl->retailer_commission;
                else
                  $retailer_commission = ($calamout*$commissionDtl->retailer_commission)/100;
                  $api_commission = $commissionDtl->api_commission;
              }
            }
            //Commission details added by ashik end 
            //commission wallet txn begin
            if(is_numeric($role_id) && intval($role_id) <= 4){                
              $walletUserID = $user_id;
              $walletRoleID = $role_id;
              $isUserBalanceUpdated = false;
              
              for($k=$walletRoleID;$k>=1;$k--){                
                if($k==3 || $k==4  || $i==7){
                  $isUserBalanceUpdated = true;
                  $userParentID = $this->rechargeApi_model->getUserParentID($walletUserID);
                  if ($isUserBalanceUpdated && $userParentID && ($userParentID->roleId==3||$userParentID->roleId==4||$userParentID->roleId==7)) {
                    $walletUserID = $userParentID->userId;
                    $walletRoleID = $userParentID->roleId;
                    $updatedBalance = $userParentID->wallet_balance;
                  }
                  continue;
                }
                $walletAmt = 0;
                $walletBal = 0; 
                $distds="";                
                $userParentID = $this->rechargeApi_model->getUserParentID($walletUserID);
                 if ($isUserBalanceUpdated && $userParentID ) {
                  $walletUserID = $userParentID->userId;
                  $walletRoleID = $userParentID->roleId;
                  $updatedBalance = $userParentID->wallet_balance;
                }                
                /*if($walletRoleID == 4){ //Retailer
                  $walletAmt = $retailer_commission;
                  $walletBal = $updatedBalance+$retailer_commission;
                }/*else if($walletRoleID == 3){ //FOS
                  $walletAmt = $distributor_commission;
                  $walletBal = $updatedBalance+$distributor_commission;
                }else*/ if($walletRoleID == 2){ //Distributor
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
                  if($walletBal < 0){
                    $transType = "DEBIT";
                  }
                  $wallet_trans_info = array(
                    'service_id' => $service_id,
                    'order_id' => $orderidarray[$j], 
                    'user_id' =>$walletUserID,
                    'group_id'   =>$this->isValid($group_id), 
                    'operator_id' => $operator_id,
                    'api_id' => $api_details->api_id,
                    'transaction_status' =>'Success',
                    'transaction_type' => $transType,
                    'payment_type' => "COMMISSION",
                    'payment_mode' => "Commission by Money Transfer",
                    'transaction_id' =>"",               
                    'trans_date' => date("Y-m-d H:i:s"),  
                    'total_amount' => $walletAmt,
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
            
           
            $this->db->select('TransTbl.order_id as order_no, 
                                  TransTbl.group_id, 
                                  TransTbl.trans_date as trans_date, 
                                  TransTbl.imps_name as name, 
                                  TransTbl.mobileno as sender_no, 
                                  TransTbl.transaction_type as mode, 
                                  Services.service_name, 
                                  TransTbl.bank_transaction_id, 
                                  TransTbl.order_status, 
                                  TransTbl.total_amount,
                                  TransTbl.remarks, 
                                  Benificiary.bank_account_number, 
                                  Benificiary.ifsc');
                $this->db->from('tbl_transaction_dtls as TransTbl');
                $this->db->join('tbl_services_type as Services','Services.service_id = TransTbl.service_id','left');
                //get accountno and ifsc
                $this->db->join('tbl_dmt_benificiary_dtls as Benificiary','Benificiary.recipient_id = TransTbl.recipient_id','left');
                //get accountno and ifsc
                
                $this->db->where('TransTbl.group_id',$group_id);
                $this->db->where('TransTbl.id_deleted', 0);
                $this->db->order_by("TransTbl.id", "desc");
                $query = $this->db->get();        
                $transactions = $query->result_array();
                
                # For sum of charges of all transaction
                $this->db->select('sum(TransTbl.total_amount) as amount, 
                                  sum(TransTbl.charge_amount) as charge_amount, 
                                  sum(TransTbl.CCFcharges) as CCFcharges, 
                                  sum(TransTbl.Cashback) as Cashback, 
                                  sum(TransTbl.TDSamount) as TDSamount, 
                                  sum(TransTbl.PayableCharge) as PayableCharge, 
                                  sum(TransTbl.FinalAmount) as FinalAmount');
                $this->db->from('tbl_transaction_dtls as TransTbl');
                $this->db->join('tbl_services_type as Services','Services.service_id = TransTbl.service_id','left');
                //get accountno and ifsc
                $this->db->join('tbl_dmt_benificiary_dtls as Benificiary','Benificiary.recipient_id = TransTbl.recipient_id','left');
                //get accountno and ifsc
                
                $this->db->where('TransTbl.group_id',$group_id);
                $this->db->where('TransTbl.id_deleted', 0);
                $this->db->order_by("TransTbl.id", "desc");
                $query = $this->db->get();        
                $transactions_count = $query->row_array();
           
                // $money_re=$this->transactions_model->getmoneyreport($txn_id);
            
            // print_r($money_re);
            $response = array(
              'status' => "true",
              'msg' => "Success",
              'groups' => "yes",
              'result' => $tarr,
              'request' =>$trarr,
              'money'=>$transactions,
              'transaction_sums'=>$transactions_count,
            );
           $numItems=count($tarr);
           if(++$aso === $numItems) {
             
             $orderwhere=array("group_id"=>$group_id);
             $trans_de=$this->moneyTransferApi_model->getdata_where("tbl_transaction_dtls",$orderwhere);
          
          foreach($trans_de as $tval){
            $transid[]=$tval->order_id;
          }
          //print_r($transid);
          $trans_ids=implode(',',$transid);
          //send sms 
          $digits = 5;
          $rand=rand(pow(10, $digits-1), pow(10, $digits)-1);
          //echo "$rand and " . ($startDate + $rand);
          $username="anandkaushal.in";
          $password="Budd789";
          $msisdn=$mobile;
          $sid="SRLSHD";
          $msg="Transaction Done TID: ".$trans_ids." -,Amt : Rs ".$amount." Fees: 1 % Name :".$money->name."  A/c : ".$money->bank_account_number." IFSC: ".$money->ifsc." SMARTPAY - ";
          //$msg="".$rand."%20is%20your%20verification%20code.%20Regards%20Saral%20Shaadi.";
          $url="http://cloud.smsindiahub.in/vendorsms/pushsms.aspx?user=".$username."&password=".$password."&msisdn=".$msisdn."&sid=".$sid."&msg=".$msg."&fl=0&gwid=2";
         $ch = curl_init();  
         curl_setopt($ch,CURLOPT_URL,$url);
         curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
         $output=curl_exec($ch);
         curl_close($ch);
         //send sms
            }
            
           
        }//result success end
        else if($tarr[$j]['status'] != "SUCCESS" ){

          $trans_info =   // array('transaction_id' =>$tarr[$j]['data']['id'],
          array('transaction_id' =>'ssssss',
                'transaction_status' => $this->isValid($tarr[$j]['status']),                
                'service_id' =>$service_id, 
                'operator_id'=>$operator_id,
                'api_id' =>$api_details->api_id,
                'trans_date' => date("Y-m-d H:i:s"),
                'group_id'   =>$this->isValid($group_id),
                'order_id' => $order_id,  
                'mobileno' =>$mobile, 
                'user_id' => $user_id,          
                'total_amount' =>$splitarr[$j],
                'charge_amount' =>0,
                'transaction_type' =>0, //add
                'bank_transaction_id' =>$tarr[$j]['data']['bank_ref_num']."", //add
                'imps_name' =>$receipt_details->recipient_name."", //add
                'recipient_id' =>$data['recipient_id'], //add
                'charges_tax' =>0 , //add
                'commission' =>0, //add
                'commission_tax' =>'0', //add
                'commission_tds' =>0, //add
                'debit_amount' => 0,
                'balance' =>0,
                'order_status' => "FAILED",
                // 'transaction_msg'=>$tarr[$j]['reason'],
                'transaction_msg'=>$tarr[$j]['message'],
                'CCFcharges'=>$ccf,
                'Cashback'=>$cashback,
                'TDSamount'=>$TDS,
                'PayableCharge'=>$PayableCharge,
                'FinalAmount'=>$totalAmount,
                'request_amount'=>$amount,
                'updated_on'=>date('Y-m-d H:i:s'),
              );
            //   print_r($trans_info);
             $txn_id = $this->transactions_model->addNewTransaction($trans_info);
             $userbalance = $this->rechargeApi_model->getUserBalance($data['user_id']);
              $wallet_balance=$userbalance->wallet_balance;
                $updatedBalance = $wallet_balance+$totalAmount; 
                 //insert DEBIT txn into tbl_wallet_trans_dtls table
                 $wallet_trans_info = array(
                  'service_id' =>$service_id,
                  'order_id' => $order_id, 
                  'group_id'   =>$this->isValid($group_id),
                  'user_id' => $user_id, 
                  'operator_id' => $operator_id,
                  'api_id' => $api_details->api_id,
                  'transaction_status' => $this->isValid($result['message']),
                  'transaction_type' => "CREDIT",
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
                  'msg' => $tarr[$j]['message'],
                  'result' => $tarr
                );  
                
        }
        }//split array end
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
    }else{
      $response = array(
        'status' => "false",
        'msg' => "Invalid Request",
        'result' => null
      );
    }
    echo json_encode($response);
    write_file('admin/domultifundtrans.txt', json_encode($response));
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
      $upi=$this->moneyTransferApi_model->get_table_alllike('tbl_application_details','alias','upilimit');
      $crazypay=$this->moneyTransferApi_model->get_table_alllike('tbl_application_details','alias','crazymoneylimit');
      // print_r($paytm);
      // print_r($upi);
      // print_r($crazypay);
      // exit();
      $update=array("available_limit"   =>$paytm->value,
                    "used_limit"         =>0,
                    "Upi_available_limit"=>$upi->value,
                    "Upi_used_limit"    =>0,
                    "available_limit_crazy"=>$crazypay->value,
                    "used_limit_crazy"    =>0);
      // $this->db->where('api_name',"paytm");
      // $this->db->update('tbl_sender_dts',$update);
      // $sql = "UPDATE `tbl_sender_dts` SET `available_limit`= ".$paytm->value.",`used_limit`=0,`Upi_available_limit`=".$upi->value.",`Upi_used_limit`=0,`available_limit_crazy`=".$crazypay->value.",`used_limit_crazy`=0";
      $sql = "UPDATE `tbl_sender_dts` SET `available_limit`= ".$paytm->value.",`used_limit`=0,`Upi_available_limit`=".$upi[0]->value.",`Upi_used_limit`=0,`available_limit_crazy`=".$crazypay[0]->value.",`used_limit_crazy`=0";
      $this->db->query($sql);
      if($this->db->affected_rows() > 0 ) {
        echo "Reset Successfully";
      }else{
        echo "Already Reseted";
      }
   }
   public function check_paytm_status($order_id,$merchant_key,$mid,$api_trn_status_url){
     
        // echo" = status 1 = ";

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
      $response =curl_exec($ch);
       $err = curl_error($ch);
      // $resultsta =  json_decode($response, true);
      // $char='SUCCESS';
      // if($resultsta['status']==$char){
      //  return $response;
      // }else{
      //   //echo 'sus';
      //   //return $response;
      //  $this->check_paytm_status($order_id,$merchant_key,$mid,$api_trn_status_url);
      // }
      // echo" == url = ";
      // print_r($url);
      // echo" == x_checksum ==";
      // print_r($x_checksum);
      // echo" == Post ==";
      // print_r($post_data);
      // echo" == Response == ";
      // print_r($response);
      // echo" == Response End == ";
      return $response;
      curl_close($ch);
      
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
	 $result =curl_exec($ch);
	 curl_close($ch);
	 return $result;
   //   $result_json =curl_exec($ch);
   // $resultsta =  json_decode($result_json, true);
   // $char = "COMPLETED";
   // //return $result_json;
   //  if($resultsta['data']['status']==$char){
   //    return $result_json;
   //  }else{
   //    $this->getcurl_with_header($url,$headers);
   //  }
   // curl_close($ch);
     
       

   }
   public function get_sender_byaccountnum(){
     $data =  json_decode(file_get_contents('php://input'), true);
     $this->authenticateUser($data);

     if($data['account_number']!=''){
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
   
   public function sendBulkSMS($mobile_no, $msg, $template_id){
    $smsDtls = $this->db->select('*')->from('tbl_sms_gateway_settings')->where('alias', 'bulk_sms')->get()->row();
    $username="NAIDUSOFTWARE";
    $password="4049102";
    $url = "https://www.bulksms.co/sendmessage.php?user=".$username."&password=".$password."&mobile=".$mobile_no."&message=".urlencode($msg)."&sender=PYMAMA&type=3&template_id=".$template_id;
         
    $ch = curl_init();  
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    $output=curl_exec($ch);
    curl_close($ch);
    write_file('admin/smsresp.txt', $output."--".$url);
    return $output;
   }

   public function createContact_Razorpay($user){
   

        $czy_api = $this->db->select('*')->from('tbl_api_settings')->where('api_alias', 'rezorpay')->get()->row();
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

   public function fundAccount_Rezorpay($data,  $razorpay_contact_id){

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
                                    "account_type": "bank_account",
                                    "bank_account": {
                                      "name": "'.$data['recipient_name'].'", 
                                      "ifsc": "'.$data['ifsc'].'",
                                      "account_number": "'.$data['bank_account_number'].'"
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

   public function payout_Rezorpay($post_req, $api_details){
      // $api_details->key_id='rzp_test_ObzJKVprAXc301';
       //$api_details->api_secretkey='GIfXJfBPI4Vw98bhqK3eo4VL';
       
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
       // CURLOPT_USERPWD => "rzp_test_ObzJKVprAXc301 : GIfXJfBPI4Vw98bhqK3eo4VL",
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
//Cashfree Webhook
public function CashfreeWebhook(){
    // $link ="https://paymamaapp.in/admin/index.php/MoneyTransferApi/RezorpayXWebhook";

      $input =  file_get_contents('php://input'); 
    $data =  $_POST;
      write_file('admin/cashfree_webhook.txt', json_encode($data));

      $webhook_insert = array( 'order_id'=> $data['transferId'], 'response'=> json_encode($data), 'api_name'=> 'Cashfree');
      $this->db->insert('tbl_webhook_log', $webhook_insert);

      $checkFundAcc_id = $this->db->select('*')->from('tbl_dmt_benificiary_dtls')
                        ->where('cfree_beneficiaryid', $data['payload']['payout']['entity']['fund_account_id'])
                        ->get()->row();
      if ($checkFundAcc_id) {
        // echo 'checkFundAcc_id';
        // $check_transaction = $this->db->select('*')->from('tbl_transaction_dtls')
        //                     ->where('transaction_id', $data['payload']['payout']['entity']['id'])
        //                     ->get()->row();
        $check_transaction = $this->db->select('*')->from('tbl_transaction_dtls')
                            ->where('order_id', $data['transferId'])
                            ->get()->row();
            $order_id=$data['transferId'];
          if ($check_transaction->order_status != 'SUCCESS' || $check_transaction->order_status != 'FAILED') {
              
            //   $this->rechargeApi_model->send_telegram_api($data['payload']['payout']['entity']['reference_id']);
           
            if ($check_transaction) {
              $req_status = $data['event'];
              // echo 'check_transaction';
              $pending_arr = array('queued', 'pending', 'processing');
              $success_arr = array('TRANSFER_SUCCESS');
              $failed_arr = array('rejected', 'cancelled','TRANSFER_FAILED','TRANSFER_REVERSED');
              $oStatus = '';
              if (in_array($req_status, $pending_arr)){
                $oStatus = 'PENDING';
              }elseif (in_array($req_status, $success_arr)) {
                $oStatus = 'SUCCESS';
              }elseif ($req_status == 'reversed' or $req_status == 'Reversed' or $req_status == 'TRANSFER_REVERSED') {
                $oStatus = 'REFUNDED';
              }elseif (in_array($req_status, $failed_arr)) {
                $oStatus = 'FAILED';
              }
              if ($check_transaction->order_id != $oStatus ) {
              
              
                // $this->db->where([ 'transaction_id'=> $data['payload']['payout']['entity']['id'] ])
                $this->db->where([ 'order_id'=> $data['transferId'] ])
                          ->update('tbl_transaction_dtls', [ 
                                                              'transaction_status'=> $oStatus,
                                                              'transaction_id'=> $data['referenceId'],
                                                              'bank_transaction_id' =>$data['utr'],
                                                              'order_status'=> $oStatus,
                                                              'updated_on'=> date('Y-m-d H:i:s')
                                                            ] 
                                  );
                          // if($this->db->affected_rows() > 0 ) {
                          //   // echo 'updated';
                          // }
            //   if( ($oStatus == 'FAILED') ||($oStatus == 'REFUNDED') ) {
            //       $trans_record =$this->moneyTransferApi_model->getdata_where('tbl_transaction_dtls',array('transaction_id'=> $data['payload']['payout']['entity']['id'] ));
            //       $trans_info['service_id']=$trans_record[0]->service_id;
            //       $trans_info['order_id']=$trans_record[0]->order_id;
            //       $trans_info['user_id']=$trans_record[0]->user_id;
            //       $trans_info['operator_id']=$trans_record[0]->operator_id;
            //       $trans_info['api_id']=$trans_record[0]->api_id;
            //       $trans_info['FinalAmount']=$trans_record[0]->FinalAmount;
            //       $failed_resp = $this->failedRezorpayTransfer($trans_info, $req_status);

            //   }
              }
              
              if($oStatus == 'FAILED' || $oStatus == 'REFUNDED') {
               
                $this->load->helper('url'); 
                redirect('https://paymamaapp.in/changewebhookstatusfromcodeigniter/'.$order_id);
                }

            }
          }
      }

   }


//Ends here


   public function RezorpayXWebhook(){
    // $link ="https://paymamaapp.in/admin/index.php/MoneyTransferApi/RezorpayXWebhook";

      $data =  json_decode(file_get_contents('php://input'), true);
      write_file('admin/razorpay_webhook.txt', json_encode($data));

      $webhook_insert = array( 'order_id'=> $data['payload']['payout']['entity']['reference_id'], 'response'=> json_encode($data), 'api_name'=> 'RazorPay' );
      $this->db->insert('tbl_webhook_log', $webhook_insert);

      $checkFundAcc_id = $this->db->select('*')->from('tbl_dmt_benificiary_dtls')
                        ->where('razorpay_fund_acc_id', $data['payload']['payout']['entity']['fund_account_id'])
                        ->get()->row();
      if ($checkFundAcc_id) {
        // echo 'checkFundAcc_id';
        // $check_transaction = $this->db->select('*')->from('tbl_transaction_dtls')
        //                     ->where('transaction_id', $data['payload']['payout']['entity']['id'])
        //                     ->get()->row();
        $check_transaction = $this->db->select('*')->from('tbl_transaction_dtls')
                            ->where('order_id', $data['payload']['payout']['entity']['reference_id'])
                            ->get()->row();
                            
          if ($check_transaction->order_status != 'SUCCESS' || $check_transaction->order_status != 'FAILED' || $check_transaction->order_status != 'REFUNDED' || $check_transaction->order_status != 'reversed' || $check_transaction->order_status != 'Refunded'  || $check_transaction->order_status != 'Reversed') {
              
            //   $this->rechargeApi_model->send_telegram_api($data['payload']['payout']['entity']['reference_id']);
            $order_id=$data['payload']['payout']['entity']['reference_id'];
            if ($check_transaction) {
              $req_status = $data['payload']['payout']['entity']['status'];
              // echo 'check_transaction';
              
              
              $pending_arr = array('queued', 'pending', 'processing');
              $success_arr = array('processed');
              $failed_arr = array('rejected', 'cancelled', 'failed');
              $oStatus = '';
              if (in_array($req_status, $pending_arr)){
                $oStatus = 'PENDING';
              }elseif (in_array($req_status, $success_arr)) {
                $oStatus = 'SUCCESS';
              }elseif ($req_status == 'reversed') {
                $oStatus = 'REFUNDED';
              }elseif (in_array($req_status, $failed_arr)) {
                $oStatus = 'FAILED';
              }
                  
              if ($check_transaction->order_id != $oStatus ) {
              
              
                // $this->db->where([ 'transaction_id'=> $data['payload']['payout']['entity']['id'] ])
                $this->db->where([ 'order_id'=> $data['payload']['payout']['entity']['reference_id'] ])
                          ->update('tbl_transaction_dtls', [ 
                                                              'transaction_status'=> $data['payload']['payout']['entity']['status'],
                                                              'transaction_id'=> $data['payload']['payout']['entity']['id'],
                                                              'bank_transaction_id' =>$data['payload']['payout']['entity']['utr'],
                                                              'order_status'=> $oStatus,
                                                              'updated_on'=> date('Y-m-d H:i:s')
                                                            ] 
                                  );
                                  
            //Update UTR in Wallet Transaction Table
            $trans_wallet_update_arr = array(
                                      'transaction_id' =>$data['payload']['payout']['entity']['utr'],
                                      'bank_trans_id' =>$data['payload']['payout']['entity']['utr']
                                    );
            $update_tras_records = $this->db->where('order_id', $data['payload']['payout']['entity']['reference_id'])->update('tbl_wallet_trans_dtls', $trans_wallet_update_arr);
            //Update Ends here
            
                          // if($this->db->affected_rows() > 0 ) {
                          //   // echo 'updated';
                          // }
                $trans_record = [];
               
               // write_file('admin/razorpay_webhook.txt', $store);
                //   write_file('admin/razorpay_webhook.txt', json_encode($trans_record[0]->order_id));
                //   $trans_record =$this->moneyTransferApi_model->getdata_where('tbl_transaction_dtls',array('order_id'=> $order_id ));
                //   $trans_info['service_id']=$trans_record[0]->service_id;
                //   $trans_info['order_id']=$trans_record[0]->order_id;
                //   $trans_info['user_id']=$trans_record[0]->user_id;
                //   $trans_info['operator_id']=$trans_record[0]->operator_id;
                //   $trans_info['api_id']=$trans_record[0]->api_id;
                //   $trans_info['FinalAmount']=$trans_record[0]->FinalAmount;
                //   $failed_resp = $this->failedRezorpayTransfer($trans_info, $req_status);
                
                //$userWalletResponse = WalletTransactionDetail::where('order_id', $tranDtl->order_id)->get();

              
              
                if($oStatus == 'FAILED' || $oStatus == 'REFUNDED') {
               
                $this->load->helper('url'); 
                redirect('https://paymamaapp.in/changewebhookstatusfromcodeigniter/'.$order_id);
                }
              }

            }
          }
      }

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
                                  'payment_mode' => "REFUND FOR DMT, AMOUNT ".$trans_info['FinalAmount'].", CHARGE ".$trans_info['PayableCharge'],
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

  public function failedPaytmTransfer($trans_info, $paytm_status){
    $userbalance = $this->rechargeApi_model->getUserBalance($trans_info['user_id']);
    $wallet_balance=$userbalance->wallet_balance;
    $updatedBalance = $wallet_balance+$userbalance['totalAmount'];

    
    $wallet_trans_info = array(
                                'service_id' =>$trans_info['service_id'],
                                'order_id' => $trans_info['order_id'], 
                                'user_id' => $trans_info['user_id'], 
                                'operator_id' => $trans_info['operator_id'],
                                'api_id' => $trans_info['api_id'],
                                'transaction_status'=> $paytm_status,
                                'transaction_type'=>"CREDIT",
                                'payment_type' => "SERVICE",
                                'payment_mode' => "REFUND FOR DMT, AMOUNT ".$trans_info['FinalAmount'].", CHARGE ".$trans_info['PayableCharge'],
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
          
          //update transaction record
          $trans_arr = array( 'transaction_status' =>$paytm_status,'order_status' => $paytm_status, 'updated_on'=>date('Y-m-d H:i:s') );
          $this->db->where('order_id', $trans_info['order_id'])->update('tbl_transaction_dtls', $trans_arr);
          return true;
  }
  
  public function getRealIpAddr() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
          $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
          $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
     	} else {
          $ip = $_SERVER['REMOTE_ADDR'];
        }
        $ips = explode(',', $ip);
        return $ips[0];
    }

}