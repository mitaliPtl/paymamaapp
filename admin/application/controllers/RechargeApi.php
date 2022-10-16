    <?php
   header("Access-Control-Allow-Origin", "https://paymamaapp.in");
header("Access-Control-Allow-Methods", "DELETE, POST, GET, OPTIONS");
header("Access-Control-Allow-Headers", "Content-Type, Authorization, X-Requested-With");
    if (!defined('BASEPATH'))
      exit('No direct script access allowed');

    require APPPATH . '/libraries/BaseController.php';
    class RechargeApi extends BaseController {
        /**
         * This is default constructor of the class
         */
        public function __construct() {
          parent::__construct();        
          $this->load->model('loginApi_model');
          $this->load->model('operatorApi_model');
          $this->load->model('transactions_model');
          $this->load->model('rechargeApi_model');
          $this->load->model('moneyTransferApi_model');
          $this->load->model('apiLog_model');
          $this->load->helper('file');
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

        /**
         * This function used as a recharge api
         */
        public function index() {
          $data =  json_decode(file_get_contents('php://input'), true);
          //print_r($data);
          //die;
          //$data =array('operatorCode'=>"RA",'mobileNumber'=>'9601466980','amount'=>'70'); 
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
          if (!empty($data['operatorID']) && !empty($data['mobileNumber']) && !empty($data['amount'])){
              $operator_id = $data['operatorID'];
              $mobile_number = $data['mobileNumber'];
              $amount = $data['amount'];
                    //check fund transfer duplicate occur with in minutes start
              $wherecheck=array("operator_id"=>$operator_id,
                                "mobileno"=>$mobile_number,
                                "request_amount"=>$data['amount']);
              $transdate=date('Y-m-d H:i');
              $min="1";
              $transduplicatecheck=$this->moneyTransferApi_model->checkduplicate_transaction_new($transdate,$wherecheck,$min);
              if(!empty($transduplicatecheck)){
                 $response = array(
                
                'status' => "false",
                'msg' => "Same mobile number and amount just now hit one Trasaction so Try again after a minute",
                'result' =>"",
              );
              echo json_encode($response);
              exit;
              }
              /*$client_id = date("dmyhis");
              $last_txn_id = $this->transactions_model->getLastTxnID();
              $client_id = intval($last_txn_id[0]->order_id)+1;*/
              // $last_txn_id = file_get_contents(base_url()."admin/txn_order_id.txt");
              // $sno_client_id = intval($last_txn_id)+1;
              // $client_id ="SP".$sno_client_id;
              $order_uni=$this->get_order_id();
              $ores=json_decode($order_uni,true);
              $sno_client_id=$ores['sno_order_id'];
              $client_id=$ores['order_id'];
              $admin_commission = 0;
              $md_commission = 0;
              $api_commission = 0;
              $distributor_commission = 0;
              $retailer_commission = 0;
              
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

              $serviceDtl = $this->operatorApi_model->getServiceDetailsByOperatorID($operator_id);//operator_id,service_id,api_id
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
              
              //check balance of user begin
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
                    $commissionDtl = $this->rechargeApi_model->getCommissionDetails($user_package_id,$service_id,$data['operatorID'],$amount);              
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

                //check active api for operator begin
                $api_details = $this->rechargeApi_model->getActiveApiDetails($operator_id,$service_id,$amount);
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

                $operatorCodeDtl = $this->operatorApi_model->getOperatorDetailsByID($service_id,$api_details->api_id,$operator_id);//operator_id,service_id,api_id
                if($operatorCodeDtl){
                  $operator_code = $operatorCodeDtl[0]->operator_code;
                }else{
                  $response = array(
                    'status' => "false",
                    'msg' => "Invalid Operator",
                    'result' => null
                  );
                  echo json_encode($response);
                  exit;
                } 

                //if all above conditions valid then update order id in file
                //$this->writeTxnOrderID($sno_client_id);

              if($api_details){//chamcharges_api
                $loginId = $api_details->username; //it will come from db
                $trans_pass = base64_decode($api_details->password);//'820328'; //it will come from db
                 //update balance after deduction begin
                  $updatedBalance = $wallet_balance-$amount; 
                  //insert DEBIT txn into tbl_wallet_trans_dtls table
                  $wallet_trans_info = array(
                    'service_id' => $service_id,
                    'order_id' => $client_id, 
                    'user_id' => $user_id, 
                    'operator_id' => $operator_id,
                    'api_id' => $api_details->api_id,
                    'transaction_status' =>'success',
                    'transaction_type' => "DEBIT",
                    'payment_type' => "SERVICE",
                    'payment_mode' => "PAID FOR ".$serviceDtl[0]->operator_name.",MOBILE NUMBER ".$mobile_number." , AMOUNT ".$data['amount'],
                    'transaction_id' =>'',               
                    'trans_date' => date("Y-m-d H:i:s"),  
                    'total_amount' => $data['amount'],
                    'charge_amount' => "0.00",
                    'balance' => $updatedBalance,
                    'updated_on'=>date('Y-m-d H:i:s'),
                  );
                  $wallet_txn_id = $this->transactions_model->addNewWalletTransaction($wallet_trans_info);
                  //update balance into users table                           
                  $updateBalQry = $this->rechargeApi_model->updateUserBalance($user_id,$updatedBalance);
                  //update balance after deduction end
                if($api_details->api_id == "2"){//campaign
                  $type = 'XML';
                  $msg = $operator_code.$mobile_number.'A'.$amount.'REF'.$client_id;               
                  $url = $api_details->api_url."recharge?login_id=".$loginId."&transaction_password=".$trans_pass."&message=".$msg."&response_type=".$type;
                  //http://www.champrecharges.com/api_users/recharge?login_id=test&transaction_password=121&message=RA9601466980A70REF1194270&response_type=CSV
                 
                  
                }else if($api_details->api_id == "3"){//For ambika
                  $url = $api_details->api_url."?UserID=".$loginId."&Token=".$api_details->api_token."&SPKey=".$operator_code."&Account=".$mobile_number."&Amount=".$amount."&APIRequestID=".$client_id."&Format=JSON";
                }else if($api_details->api_id == "5"){//For robotics
                  $url = $api_details->api_url."?api_token=".$api_details->api_token."&mobile_no=".$mobile_number."&amount=".$amount."&company_id=".$operator_code."&order_id=".$client_id."&is_stv=false";
                } else if($api_details->api_id == "4"){//For SAMRIDDHIPAY
                  $url = $api_details->api_url."?username=".$loginId."&apiToken=".$api_details->api_token."&mobileNumber=".$mobile_number."&amount=".$amount."&operator=".$operator_code."&userRcId=".$client_id;
                }     
                else if($api_details->api_id == "11"){//For api master
                	//echo "esrer";
                	$operator_code = 'RA';
                  $msg = $operator_code.$mobile_number.'A'.$amount.'REF'.$client_id;               
                  $url = $api_details->api_url;
                  $apimaster=array('username' =>$loginId,
                  	    'token'    =>$api_details->api_token,
                  	    'order_id'    =>$client_id,
                  	    'operator_code'=>$operator_code,
                  	    'mobile_no'=>$mobile_number,
                  	    'amount'=>$amount);
				
				
				 

                } else if($api_details->api_id == "15"){//For technopayment
                
                //UserID=APIUserID&Token=APIToken&Account=ConsumerNo&Amount=Amount&SPKey=OperatorCode&APIRequestID=UniqueRefNo&Optional1=&Optional2=&Optional3=&Optional4=&GEOCode=Longitude,Latitude&CustomerNumber=Reg.MobileNumber&Pincode=Area Pincode&Format=2
                  $url = $api_details->api_url."?UserID=".$loginId."&Token=".$api_details->api_token."&SPKey=".$operator_code."&Account=".$mobile_number."&Amount=".$amount."&APIRequestID=".$client_id."&Format=1&GEOCode=31.1153,75.8140&CustomerNumber=".$api_details->account_no."&Pincode=501505";
                } else if($api_details->api_id == "16"){//For ambika new
                
                  $url = $api_details->api_url."?UserID=".$loginId."&Token=".$api_details->api_token."&SPKey=".$operator_code."&Account=".$mobile_number."&Amount=".$amount."&APIRequestID=".$client_id."&Format=1&GEOCode=31.1153,75.8140&CustomerNumber=".$api_details->account_no."&Pincode=501505";
                }
               
                //  print_r($url);
                 //save api log details begin
                $api_info = array(
                  'service_id' => $service_id."", 
                  'api_id' => $api_details->api_id."", 
                  'api_name' => $api_details->api_name."",  
                  'api_method' => "RechargeTransaction",
                  'api_url' => $url."", 
                  'order_id' => $client_id, 
                  'user_id' => $data['user_id'],  
                  'request_input' => $api_details->api_token."",
                  'request' => $url."",         
                  'response' => "",
                  'access_type' => (isset($data['access_type'])) ? $data['access_type'] : 'APP' ,
                  'updated_on'=>date('Y-m-d H:i:s'),
                );
                $api_log_id = $this->apiLog_model->addApiLogDetails($api_info);
                //save api log details end 

                //INSERT TRNSACTION TABLE
                $trans_info = array(
                                    // 'transaction_id' =>$trid."",
                                    'transaction_id' =>"",
                                    // 'transaction_status' => $status."", 
                                    'transaction_status' => "PENDING", 
                                    'service_id' => $service_id."",
                                    'api_id' => $api_details->api_id."",
                                    'trans_date' => date("Y-m-d H:i:s"),  
                                    'order_id' => $client_id."",  
                                    'mobileno' => $mobile_number."",  
                                    'user_id' => $user_id."",               
                                    'operator_id' => $operator_id."",
                                    // 'total_amount' => $amount."",
                                    'total_amount' => "",
                                    'charge_amount' => "0.00",
                                    // 'basic_amount' => $amount."",
                                    'basic_amount' => "",
                                    // 'debit_amount' => $debitAmount."",
                                    'debit_amount' => "",
                                    // 'credit_amount' => $creditAmount."",
                                    'credit_amount' => "",
                                    // 'balance' => $balance."",
                                    'balance' => "",
                                    'order_status' => "PENDING",
                                    // 'transaction_msg'=>$message."",
                                    'transaction_msg'=>"",
                                    // 'response_msg'=>json_encode($result),
                                    'response_msg'=>"",
                                    'request_amount'=>$data['amount'],
                                    'updated_on'=>date('Y-m-d H:i:s'),
                );
                // echo "<pre>";
                // print_r($trans_info);
                $txn_id = $this->transactions_model->addNewTransaction($trans_info);
                //END INSERT TRANSACTION TABLE

                //request call
                if($api_details->api_id!="11"){
                  $ch = curl_init($url);
                  curl_setopt($ch, CURLOPT_TIMEOUT, 36000);
                  curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
                  if($api_details->api_id == "4"){
                    curl_setopt($ch, CURLOPT_POST, 1);
                  }
                
                  curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                  $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                  $result = curl_exec($ch);
                  //print_r($result);

                  curl_close($ch); 
                }
                else{
                  $ch = curl_init($url);
                  # Setup request to send json via POST.
                  $payload = json_encode($apimaster);
                  curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
                  curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
                  # Return response instead of printing.
                  curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
                  # Send request.
                  $result = curl_exec($ch);
                  curl_close($ch);
                  # Print response.
                  //print_r($result);
                }
                //Samrriddhi Pay
                /*$result = '{"Response":"Success","Message":"Request submitted.","rechargeId":36582,"rechargeStatus":"Success","rechargeOperatorId":"BR00051QI5XM","mobileNumber":"8369728351"}';
                */
                
                $this->db->where('order_id', $client_id)->update('tbl_apilog_dts', array('response'=>$result."", 'updated_on'=> date('Y-m-d H:i:s') ));
                
                $clientId = $client_id; //for order id in case of failure and success

                if($api_details->api_id == "2"){//campaign
                  //print_r($result);
                  $xml = simplexml_load_string($result);    
                  // echo "<pre>";
                  // print_r($xml);
                   

                  if(empty($xml)){
                    $trans_info = array(
                      'transaction_id' =>'',
                      'transaction_status' =>'Failed', 
                      'service_id' =>$service_id,
                      'api_id' =>$api_details->api_id,
                      'trans_date' => date("Y-m-d H:i:s"),  
                      'order_id' => $client_id,  
                      'mobileno' => $mobile_number,  
                      'user_id' => $user_id,               
                      'operator_id' => $operator_id,
                      'total_amount' => $data['amount'],
                      'charge_amount' => "0.00",
                      'basic_amount' => $data['amount'],
                      'debit_amount' =>'',
                      'credit_amount' =>'',
                      'balance' =>'',
                      'order_status' => 'FAILED',
                      'transaction_msg'=>'API not responding',
                      'request_amount'=>$data['amount'],
                      'updated_on'=>date('Y-m-d H:i:s'),
                    );
                      //print_r($trans_info);
                      //echo "f";
                      $txn_id = $this->transactions_model->addNewTransaction($trans_info);
                      //update balance after deduction begin
                      $userbalance = $this->rechargeApi_model->getUserBalance($user_id);
                      $wallet_balance=$userbalance->wallet_balance;
                      $updatedBalance = $wallet_balance+$data['amount']; 
                      //insert DEBIT txn into tbl_wallet_trans_dtls table
                      $wallet_trans_info = array(
                        'service_id' => $service_id,
                        'order_id' => $client_id, 
                        'user_id' => $user_id, 
                        'operator_id' => $operator_id,
                        'api_id' => $api_details->api_id,
                        'transaction_status' =>'Success'."",
                        'transaction_type' => "CREDIT",
                        'payment_type' => "SERVICE",
                        'payment_mode' => "REFUND FOR ".$serviceDtl[0]->operator_name.", MOBILE NUMBER ".$mobile_number.", AMOUNT ".$data['amount'],
                        'transaction_id' =>"",               
                        'trans_date' => date("Y-m-d H:i:s"),  
                        'total_amount' => $data['amount'],
                        'charge_amount' => "0.00",
                        'balance' => $updatedBalance,
                        'updated_on'=>date('Y-m-d H:i:s'),
                      );
                      $wallet_txn_id = $this->transactions_model->addNewWalletTransaction($wallet_trans_info);
                      //print_r($wallet_trans_info);
                      //update balance into users table                           
                      $updateBalQry = $this->rechargeApi_model->updateUserBalance($user_id,$updatedBalance);
                      //update balance after deduction end
                        $response = array(
                          'status' => "false",
                          'msg' => "Error: API not responding.",
                          'result' => null
                        );
                        echo json_encode($response);
                        exit;
                  }
                  $pre_xml = $xml;
                  $xmlJSON = json_encode($xml);
                  $jsonArray = json_decode($xmlJSON, true);
               
                  //print_r($jsonArray);
                   
                  $status = '';
                  $trid ='';
                  $clientId = $client_id;
                  
                  if (isset($jsonArray['STATUS'])) {
                    $status = $jsonArray['STATUS'];
                  }
                  if (isset($jsonArray['TRID'])) {
                    $trid = $jsonArray['TRID'];
                  }
                  if (isset($jsonArray['CLIENTID'])) {
                    $clientId = $jsonArray['CLIENTID'];
                  }
                  if (isset($jsonArray['MOBILE'])) {
                    $mobile = $jsonArray['MOBILE'];
                  }
                  if (isset($jsonArray['AMOUNT'])) {
                    $amount = $jsonArray['AMOUNT'];
                  }
                  if (isset($jsonArray['MESSAGE'])) {
                    $message ="".$jsonArray['MESSAGE']."";
                    
                  }
                  if (isset($jsonArray['DEBIT_AMOUNT'])) {
                    $debitAmount = "".$jsonArray['DEBIT_AMOUNT']."";
                  }
                  if (isset($jsonArray['CREDIT_AMOUNT'])) {
                    $creditAmount = "".$jsonArray['CREDIT_AMOUNT']."";
                  }
                  if (isset($jsonArray['BALANCE'])) {
                    $balance = $jsonArray['BALANCE'];
                  } 
                }else if($api_details->api_id == "3"){
                  $result = json_decode($result, true);
                  $status = $result['STATUS'];
                  $trid = $result['RPID'];
                  $clientId = $result['AGENTID'];
                  $mobile = $result['ACCOUNT'];
                  $amount = $result['AMOUNT'];
                  $message = $result['MSG'];
                  $debitAmount = "";
                  $creditAmount = "";
                  $balance = $result['BAL'];                  
                } else if($api_details->api_id == "5"){

                  $result = json_decode($result, true);
                  $status = $result['status'];
                  $trid = $result['tnx_id'];
                  $clientId = $result['order_id'];
                  $mobile = $result['mobile_no'];
                  $amount = $amount;
                  $message = $result['response'];
                  if(!isset($message)){                    
                    $message = $result['errorMessage'];
                  }
                  $debitAmount = "";
                  $creditAmount = "";
                  $balance = $result['balance'];                  
                }else if($api_details->api_id == "4"){//SAMRIDDHIPAY 
                  $result = json_decode($result, true);
                  // echo "<pre>";
                  // print_r($result);
                  $status = $result['rechargeStatus'];
                  $trid = $result['rechargeId'];
                  $clientId = $client_id;
                  $mobile = $mobile_number;
                  $amount = $amount;
                  $message = $result['Message'];
                  $debitAmount = "";
                  $creditAmount = "";
                  $balance = "";                  
                }           
                else if($api_details->api_id == "11"){//SAMRIDDHIPAY 
                  $result = json_decode($result, true);
                  // echo "<pre>";
                  // print_r($result);
                  $status = $result['rechargeStatus'];
                  $trid = $result['rechargeId'];
                  $clientId = $client_id;
                  $mobile = $mobile_number;
                  $amount = $amount;
                  $message = $result['message'];
                  $debitAmount = "";
                  $creditAmount = "";
                  $balance = "";                  
                } else if($api_details->api_id == "15"){
                  $result = json_decode($result, true);
                  $status = "FAILED";
                  if($result['status'] == 1) {
                      $status = "PENDING";
                  } elseif($result['status'] == 2) {
                      $status = "SUCCESS";
                  } elseif($result['status'] == 3) {
                      $status = "FAILED";
                  }
                  $trid = $result['rpid'];
                  $clientId = $result['agentid'];
                  $mobile = $result['account'];
                  $amount = $result['amount'];
                  $message = $result['msg'];
                  $debitAmount = "";
                  $creditAmount = "";
                  $balance = $result['bal'];
                } else if($api_details->api_id == "16"){
                  $result = json_decode($result, true);
                  $status = "FAILED";
                  if($result['status'] == 1) {
                      $status = "PENDING";
                  } elseif($result['status'] == 2) {
                      $status = "SUCCESS";
                  } elseif($result['status'] == 3) {
                      $status = "FAILED";
                  }
                  $trid = $result['rpid'];
                  $clientId = $result['agentid'];
                  $mobile = $result['account'];
                  $amount = $result['amount'];
                  $message = $result['msg'];
                  $debitAmount = "";
                  $creditAmount = "";
                  $balance = $result['bal'];
                }
                          
                $result = array(
                  'status' => $status,
                  'transaction_id' => $trid."",
                  'mobile' => $mobile_number."",
                  'uniqueid' => $client_id."",
                  'operator_code' => $operator_code."",
                  'message' => $message."",
                );
                 //echo $status;
                if(!strcasecmp($status,"Pending") || !strcasecmp($status,"Success")){ //true

                  $order_status = "PENDING";
                  if(!strcasecmp($status,"Pending")){
                    $order_status = "PENDING";
                  }else if(!strcasecmp($status,"Success")){
                    $order_status = "SUCCESS";
                  }

                  //UPDATE TRANSACTION 
                  $trans_update_arr = array(
                    'transaction_id' =>$trid."",
                    'transaction_status' => $status."", 
                    'total_amount' => $amount."",
                    'basic_amount' => $amount."",
                    'debit_amount' => $debitAmount."",
                    'credit_amount' => $creditAmount."",
                    'balance' => $balance."",
                    'order_status' => $order_status,
                    'transaction_msg'=>$message."",
                    'response_msg'=>json_encode($result),
                    'updated_on'=>date('Y-m-d H:i:s'),

                  );
                  
                  $this->db->where('order_id', $client_id)->update('tbl_transaction_dtls', $trans_update_arr);
                  
                  $tg_msg  = "Recharge for ". $client_id ." is " . $order_status . " :-\n\n";
                  $tg_msg .= "Smart ID - ".$client_id."\n";
                  $tg_msg .= "Mobile Number - ".$mobile_number."\n";
                  $tg_msg .= "Operator - ".$serviceDtl[0]->operator_name."\n";
                  $tg_msg .= "Amount - ".$amount."\n";
                  $tg_msg .= "Transaction ID - ".$this->isValid($trid)."\n";
                  $tg_msg .= "Status - ". $order_status ."\n";
                  $tg_msg .= "Transaction Date - ".date('Y-m-d H:i:s')."\n";
                  $this->rechargeApi_model->send_telegram($data['user_id'],$tg_msg);
                  //END UPDATE TRANSACTION

                  //update balance based on api id in api setting table developed by susmitha start
                  if($api_details->api_id!= "5"){ 
                  $data = array('balance'=>$balance);
                    $this->apiLog_model->update_api_amount($data,$api_details->api_id);
                   }
                 //update balance based on api id in apisetting table developed by susmitha end 
                  $response = array(
                    'status' => "true",
                    'msg' => "Recharge successfully done.",
                    'result' => $result
                  ); 

                  // //update balance after deduction begin
                  // $updatedBalance = $wallet_balance-$amount; 
                  // //insert DEBIT txn into tbl_wallet_trans_dtls table
                  // $wallet_trans_info = array(
                  //   'service_id' => $service_id,
                  //   'order_id' => $clientId, 
                  //   'user_id' => $user_id, 
                  //   'operator_id' => $operator_id,
                  //   'api_id' => $api_details->api_id,
                  //   'transaction_status' => $status,
                  //   'transaction_type' => "DEBIT",
                  //   'payment_type' => "SERVICE",
                  //   'payment_mode' => "Recharge By Wallet Balance",
                  //   'transaction_id' => $trid,               
                  //   'trans_date' => date("yy-m-d H:i:s"),  
                  //   'total_amount' => $amount,
                  //   'charge_amount' => "0.00",
                  //   'balance' => $updatedBalance,
                  //   'updated_on'=>date('Y-m-d H:i:s'),
                  // );
                  // $wallet_txn_id = $this->transactions_model->addNewWalletTransaction($wallet_trans_info);
                  // //update balance into users table                           
                  // $updateBalQry = $this->rechargeApi_model->updateUserBalance($user_id,$updatedBalance);
                  // //update balance after deduction end

                  //commission wallet txn begin
                 if(is_numeric($role_id) && intval($role_id) <= 4){                
                  $walletUserID = $user_id;
                  $walletRoleID = $role_id;
                  $isUserBalanceUpdated = false;
                  for($i=$walletRoleID;$i>=1;$i--){
                    if($i == 3){
                      $isUserBalanceUpdated = true;
                      $userParentID = $this->rechargeApi_model->getUserParentID($walletUserID);
                      if ($isUserBalanceUpdated && $userParentID && $userParentID->roleId==3) {
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
                      if($walletRoleID == 4){ //Retailer
                        $walletAmt = $retailer_commission;
                        $walletBal = $updatedBalance+$retailer_commission;
                      }/*else if($walletRoleID == 3){ //FOS
                        $walletAmt = $distributor_commission;
                        $walletBal = $updatedBalance+$distributor_commission;
                      }*/else if($walletRoleID == 2){ //Distributor
                        $walletAmt = $distributor_commission;
                        $walletBal = $updatedBalance+$distributor_commission;
                      }else if($walletRoleID == 1){ //Admin
                        $walletAmt = $admin_commission;
                        $walletBal = $updatedBalance+$admin_commission;
                      }else if($walletRoleID == 7){ //Admin
                        $walletAmt = $md_commission;
                        $walletBal = $updatedBalance+$md_commission;
                      }
                      if(is_numeric($walletAmt) && is_numeric($walletBal)){
                        $transType = "CREDIT";
                        if($walletAmt < 0){
                          $transType = "DEBIT";
                        }
                        $wallet_trans_info = array(
                          'service_id' => $service_id,
                          'order_id' => $client_id, 
                          'user_id' => $walletUserID, 
                          'operator_id' => $operator_id,
                          'api_id' => $api_details->api_id,
                          'transaction_status' => $status,
                          'transaction_type' => $transType,
                          'payment_type' => "COMMISSION",
                          'payment_mode' => "COMMISSION FOR ".$serviceDtl[0]->operator_name.",MOBILE NUMBER ".$mobile_number." , AMOUNT ".abs($walletAmt),
                          'transaction_id' => $trid,               
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
                  //commission wallet txn end                
                }else {

                   //UPDATE TRANSACTION 
                   $trans_update_arr = array(
                    'transaction_id' =>$this->isValid($trid)."",
                    'transaction_status' => $this->isValid($status)."", 
                    'total_amount' => $data['amount']."",
                    'basic_amount' => $data['amount']."",
                    'debit_amount' => $this->isValid($debitAmount)."",
                    'credit_amount' => $this->isValid($creditAmount)."",
                    'balance' => $this->isValid($balance)."",
                    'order_status' => $this->isValid("FAILED"),
                    'transaction_msg'=>$message."",
                    'response_msg'=>json_encode($result),
                    'updated_on'=>date('Y-m-d H:i:s'),
                  );
                  
                  
                  $this->db->where('order_id', $client_id)->update('tbl_transaction_dtls', $trans_update_arr);
                  //END UPDATE TRANSACTION
                  
                  $tg_msg  = "Recharge for ". $client_id ." is" . $this->isValid("FAILED") . " :-\n\n";
                  $tg_msg .= "Smart ID - ".$client_id."\n";
                  $tg_msg .= "Mobile Number - ".$mobile_number."\n";
                  $tg_msg .= "Operator - ".$serviceDtl[0]->operator_name."\n";
                  $tg_msg .= "Amount - ".$data['amount']."\n";
                //   $tg_msg .= "Commission - 0.00\n";
                  $tg_msg .= "Transaction ID - ".$this->isValid($trid)."\n";
                  $tg_msg .= "Status - ". $this->isValid("FAILED") ."\n";
                  $tg_msg .= "Transaction Date - ".date('Y-m-d H:i:s')."\n";
                  $this->rechargeApi_model->send_telegram($data['user_id'],$tg_msg);


                  // $trans_info = array(
                  //   'transaction_id' => $this->isValid($trid)."",
                  //   'transaction_status' => $this->isValid($status)."", 
                  //   'service_id' => $service_id."",
                  //   'api_id' => $api_details->api_id."",
                  //   'trans_date' => date("Y-m-d H:i:s"),  
                  //   'order_id' => $client_id."",  
                  //   'mobileno' => $this->isValid($mobile_number)."",  
                  //   'user_id' => $this->isValid($user_id)."",               
                  //   'operator_id' => $this->isValid($operator_id)."",
                  //   'total_amount' => $data['amount']."",
                  //   'charge_amount' => "0.00",
                  //   'basic_amount' => $data['amount']."",
                  //   'debit_amount' => $this->isValid($debitAmount)."",
                  //   'credit_amount' => $this->isValid($creditAmount)."",
                  //   'balance' => $this->isValid($balance)."",
                  //   'order_status' => $this->isValid("FAILED"),
                  //   'transaction_msg'=>$message."",
                  //   'request_amount'=>$data['amount'],
                  //   'updated_on'=>date('Y-m-d H:i:s'),
                  // );
                  //print_r($trans_info);
                  //echo "f";
                  // $txn_id = $this->transactions_model->addNewTransaction($trans_info);
                   //update balance after deduction begin
                  $userbalance = $this->rechargeApi_model->getUserBalance($user_id);
                  $wallet_balance=$userbalance->wallet_balance;
                  $updatedBalance = $wallet_balance+$data['amount']; 
                  //insert DEBIT txn into tbl_wallet_trans_dtls table
                  $wallet_trans_info = array(
                    'service_id' => $service_id,
                    'order_id' => $client_id, 
                    'user_id' => $user_id, 
                    'operator_id' => $operator_id,
                    'api_id' => $api_details->api_id,
                    'transaction_status' => $status."",
                    'transaction_type' => "CREDIT",
                    'payment_type' => "SERVICE",
                    'payment_mode' => "REFUND FOR ".$serviceDtl[0]->operator_name.", MOBILE NUMBER ".$mobile_number." ,AMOUNT ".$data['amount'],
                    'transaction_id' => $trid."",               
                    'trans_date' => date("Y-m-d H:i:s"),  
                    'total_amount' => $data['amount'],
                    'charge_amount' => "0.00",
                    'balance' => $updatedBalance,
                    'updated_on'=>date('Y-m-d H:i:s'),
                  );
                  $wallet_txn_id = $this->transactions_model->addNewWalletTransaction($wallet_trans_info);
                  //print_r($wallet_trans_info);
                  //update balance into users table                           
                  $updateBalQry = $this->rechargeApi_model->updateUserBalance($user_id,$updatedBalance);
                  //update balance after deduction end
                  $response = array(
                    'status' => "false",
                    'msg' => $message,
                    'result' => $result
                  );          
                }
              }else{
                $response = array(
                  'status' => "false",
                  'msg' => "API implementation under process. Please contact administrator.",
                  'result' => null
                );
                echo json_encode($response);
                exit;
                echo "URL:";
                echo $url = 'https://stgapi.billavenue.com/billpay/extMdmCntrl/mdmRequest/xml';
                echo "<br/><br/>";
                $workingKey = "DB9E94A3C46347EF2A3C254C793B3113";
                $accessCode = "AVZD78IJ22WJ27SDKF";
                $institutionID = "SP02";
                $institutionName = "SMARTPAY";
                echo "XML Request:";
                echo $request = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><billerInfoRequest><billerId>".$data['billerID']."</billerId></billerInfoRequest>";
                //die;
                $encrypt_req = $this->encrypt($request,$workingKey);
                //echo $decrypt_req = $this->decrypt($encrypt_req,$workingKey);
                $post_data = array(
                  'accessCode' => $accessCode,
                  'requestId' => $client_id,
                  'encRequest' => $encrypt_req,
                  'ver' => '1.0',
                  'instituteId' => $institutionID
                );  
                $parameters = http_build_query($post_data);
                echo "<br/><br/>"; 
                echo "POST Params:";
                print_r($post_data); 
                echo "<br/><br/>";  
                //echo base_url()."assets/cert/billavenue_UAT_Certificate.crt";              
                //die;
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_TIMEOUT, 36000);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                /*curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
                curl_setopt($ch, CURLOPT_CAINFO, base_url()."assets/cert/billavenue_UAT_Certificate.crt"); //  ok*/
                $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);         // GET STATUS CODE
                //echo "<br/>Status Code:".$status_code."<br/><br/><br/>";
                echo "Result:";
                echo $result = curl_exec($ch);
                if(curl_errno($ch)){
                  $error_msg = curl_error($ch);
                }
                curl_close($ch);
                echo "Error:";
                if(isset($error_msg)){
                  echo $error_msg."";
                }

                $response = $this->decrypt($result, $workingKey);
                echo $response;
                die;
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

          public function writeTxnOrderID($order_id){
            write_file('admin/txn_order_id.txt', $order_id."");
          }

          public function getDthInfo(){
            $data =  json_decode(file_get_contents('php://input'), true);
            $this->authenticateUser($data);
            $operatorDtl = $this->operatorApi_model->getServiceDetailsByOperatorID($data['operatorID']);//operator_id,service_id,api_id
            if($operatorDtl){
              $operator_id = $operatorDtl[0]->offers_121_op_code;
            }else{
              $response = array(
                'status' => "false",
                'msg' => "Invalid operator",
                'result' => null
              );
              echo json_encode($response);
              exit;
            } 
            $url = "http://planapi.in/api/Mobile/DTHINFOCheck?apimember_id=3617&api_password=Smartpay@8443&Opcode=".$operator_id."&mobile_no=".$data['mobileNumber'];
            //request call
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 36000);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
            // if($api_details->api_id == "4"){
            //   curl_setopt($ch, CURLOPT_POST, 1);
            // }
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $result = curl_exec($ch);
            curl_close($ch); 
            $response1 = json_decode($result, true);
            $error = $response1['error'];
            if ($error == "0") {
                $resultFinal = array(
                  'vc'=>$response1['DATA']['VC'],
                  'Name'=>$response1['DATA']['Name'],
                  'Rmn'=>$response1['DATA']['Rmn'],
                  'Balance'=>$response1['DATA']['Balance'],
                  'NextRechargeDate'=>$response1['DATA']['Next Recharge Date'],
                  'Plan'=>$response1['DATA']['Plan'],
                  'City'=>$response1['DATA']['City'],
                  'District'=>$response1['DATA']['District'],
                  'State'=>$response1['DATA']['State'],
                  'PINCode'=>$response1['DATA']['PIN Code']
                );
                $response = array(
                    'status' => "true",
                    'msg' => "success",
                    'result' => $resultFinal
                );              
            } else {
                $response = array(
                    'status' => "false",
                    'msg' => "No DTH info available",
                    'result' => $response1 
                );
            }
            echo json_encode($response);
            exit;
          }
          public function  getBillerList(){
            $data =  json_decode(file_get_contents('php://input'), true);

            $this->authenticateUser($data);
          //$data =array('operatorCode'=>"RA",'mobileNumber'=>'9601466980','amount'=>'70'); 

           // if (!empty($data['billerID'])) {  
              
              //$order_id = date("His"); 
              $order_id=$this->getName('35');  
              //$order_id = "ord_".$data['user_id']."_".$order_id;

             // echo $url = 'https://stgapi.billavenue.com/billpay/extMdmCntrl/mdmRequest/xml';
               //$url = 'https://api.billavenue.com/billpay/extMdmCntrl/mdmRequest/xml';
             
              //$workingKey = "DB9E94A3C46347EF2A3C254C793B3113";
              $workingKey ="267CCE2A18759131586D028FF049B35D";
              //$accessCode = "AVZD78IJ22WJ27SDKF";
              $accessCode = "AVAI39GI73ZC50TZNR";
              //$institutionID = "SP02";
              $institutionID = "BA03";
              
              $institutionName = "SMARTPAY";
               echo $url='https://api.billavenue.com/billpay/extMdmCntrl/mdmRequestNew/xml?accessCode='.$accessCode.'&requestId='.$order_id.'&ver=1.0&instituteId='.$institutionID.'';
              $request = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><billerInfoRequest></billerInfoRequest>";
              //die;
               $encrypt_req = $this->encrypt($request,$workingKey);
              //echo $decrypt_req = $this->decrypt($encrypt_req,$workingKey);
              $post_data = array(
                'accessCode' => $accessCode,
                'requestId' => $order_id,
                'encRequest' => $encrypt_req,
                'ver' => '1.0',
                'instituteId' => $institutionID
              );  
              $parameters = http_build_query($post_data);
              $ch = curl_init();  
              curl_setopt($ch,CURLOPT_URL,$url);
              curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
             //  curl_setopt($ch,CURLOPT_HEADER, false); 
               $output=curl_exec($ch);
               curl_close($ch);
               print_r($output);
              exit();
              $ch = curl_init($url);
              curl_setopt($ch, CURLOPT_TIMEOUT, 36000);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
              curl_setopt($ch, CURLOPT_POST, 1);
              curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
              curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
              curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
              curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
              curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
              /*curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
              curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
              curl_setopt($ch, CURLOPT_CAINFO, base_url()."assets/cert/billavenue_UAT_Certificate.crt"); //  ok*/
              $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);    
              $result = curl_exec($ch);
             // print_r($result);
              if(curl_errno($ch)){
                $error_msg = curl_error($ch);
              }
              curl_close($ch);
              if(isset($error_msg)){
                $response = array(
                  'status' => "false",
                  'msg' => "Invalid Request",
                  'result' => null
                );
              }else{
                
                $api_response = $this->decrypt($result, $workingKey);
                $xml = new SimpleXMLElement($api_response);
                if($xml->responseCode == "00" || $xml->responseCode == "000"){
                  // $x1=count($xml->biller);
                 
                 //  for ($x = 0; $x <$x1; $x++) {
                 //  $billerId=$xml->biller[$x]->billerId;
                 //  $biller=array('billerId'                  =>$xml->biller[$x]->billerId,
                 //                'billerName'                =>$xml->biller[$x]->billerId,
                 //                'billerCategory'            =>$xml->biller[$x]->billerCategory,
                 //                'billerAdhoc'               =>$xml->biller[$x]->billerAdhoc,
                 //                'billerCoverage'            =>$xml->biller[$x]->billerCoverage,
                 //                'billerFetchRequiremet'     =>$xml->biller[$x]->billerFetchRequiremet,
                 //                'billerPaymentExactness'    =>$xml->biller[$x]->billerPaymentExactness,
                 //                'billerSupportBillValidation'=>$xml->biller[$x]->billerSupportBillValidation,
                 //                'supportPendingStatus'        =>$xml->biller[$x]->supportPendingStatus,
                 //                'supportDeemed'               =>$xml->biller[$x]->supportDeemed,
                 //                'billerTimeout'               =>json_encode($xml->biller[$x]->billerTimeout),
                 //                'billerInputParams'           =>json_encode($xml->biller[$x]->billerInputParams),
                 //                'billerAmountOptions'         =>$xml->biller[$x]->billerAmountOptions,
                 //                'billerPaymentModes'          =>$xml->biller[$x]->billerPaymentModes,
                 //                'billerDescription'               =>json_enoce($xml->biller[$x]->billerDescription),
                 //                'rechargeAmountInValidationRequest'=>json_encode($xml->biller[$x]->rechargeAmountInValidationRequest),
                 //                'billerPaymentChannels'       =>json_encode($xml->biller[$x]->billerPaymentChannels),
                 //                );
                 //   $sql=$this->db->query('SELECT id FROM `tbl_bbps_list` where billerId='.$billerId.'')->row();
                 //    if(empty($sql)){
                 //    $this->db->insert('tbl_bbps_list',$biller);
                 //    } else{
                 //    $this->db->where('billerId',$billerId);
                 //    $this->db->update('mytable', $biller);
                 //    }
                  
                 //  }
                  $response = array(
                    'status' => "true",
                    'msg' => "SUCCESS",
                    'result' => $xml
                  );
                  
                }else{
                  $response = array(
                    'status' => "false",
                    'msg' => "FAILED",
                    'result' => $xml
                  );
                }
              }
            // }else{
            //   $response = array(
            //     'status' => "false",
            //     'msg' => "Invalid Request",
            //     'result' => null
            //   );
            // }
            echo json_encode($response);
            exit;
          }
        public function getName($n) { 
          $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
          $randomString = ''; 
  
        for ($i = 0; $i < $n; $i++) { 
        $index = rand(0, strlen($characters) - 1); 
        $randomString .= $characters[$index]; 
           } 
  
           return $randomString; 
           }
          public function getBillerDetails(){       
            $data =  json_decode(file_get_contents('php://input'), true);

            $this->authenticateUser($data);
          //$data =array('operatorCode'=>"RA",'mobileNumber'=>'9601466980','amount'=>'70'); 

            if (!empty($data['billerID'])) {  
              
              $order_id = date("His");   
              //$order_id = "ord_".$data['user_id']."_".$order_id;

             // echo $url = 'https://stgapi.billavenue.com/billpay/extMdmCntrl/mdmRequest/xml';
               $url = 'https://api.billavenue.com/billpay/extMdmCntrl/mdmRequest/xml';
              
              //$workingKey = "DB9E94A3C46347EF2A3C254C793B3113";
              $workingKey ="267CCE2A18759131586D028FF049B35D";
              //$accessCode = "AVZD78IJ22WJ27SDKF";
              $accessCode = "AVAI39GI73ZC50TZNR";
              //$institutionID = "SP02";
              $institutionID = "BA03";
              
              $institutionName = "SMARTPAY";
              
              $request = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><billerInfoRequest><billerId>".$data['billerID']."</billerId></billerInfoRequest>";
              //die;
               $encrypt_req = $this->encrypt($request,$workingKey);
              //echo $decrypt_req = $this->decrypt($encrypt_req,$workingKey);
              $post_data = array(
                'accessCode' => $accessCode,
                'requestId' => $order_id,
                'encRequest' => $encrypt_req,
                'ver' => '1.0',
                'instituteId' => $institutionID
              );  
              $parameters = http_build_query($post_data);
              
              $ch = curl_init($url);
              curl_setopt($ch, CURLOPT_TIMEOUT, 36000);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
              curl_setopt($ch, CURLOPT_POST, 1);
              curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
              curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
              curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
              curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
              curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
              /*curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
              curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
              curl_setopt($ch, CURLOPT_CAINFO, base_url()."assets/cert/billavenue_UAT_Certificate.crt"); //  ok*/
              $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);    
              $result = curl_exec($ch);
              //print_r($result);
              if(curl_errno($ch)){
                $error_msg = curl_error($ch);
              }
              curl_close($ch);
              if(isset($error_msg)){
                $response = array(
                  'status' => "false",
                  'msg' => "Invalid Request",
                  'result' => null
                );
              }else{
                
                $api_response = $this->decrypt($result, $workingKey);
                $xml = new SimpleXMLElement($api_response);
                //print_r($xml);
                if($xml->responseCode == "00" || $xml->responseCode == "000"){
                  $response = array(
                    'status' => "true",
                    'msg' => "SUCCESS",
                    'result' => $xml
                  );
                }else{
                  $response = array(
                    'status' => "false",
                    'msg' => "FAILED",
                    'result' => $xml
                  );
                }
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
          public function saveMultipleBillerDetails(){       
            $data =  json_decode(file_get_contents('php://input'), true);

            $this->authenticateUser($data);
          
            if (!empty($data['biller'])) { 
              // echo "<pre>";
              //  print_r($data['biller']);

                $xml=$this->MultipleBillerDetails($data['biller']);
                if($xml->responseCode='000'||$xml->responseCode == "00"){
              
               if(!empty($xml->biller)){
                
                 foreach($xml->biller as $key=> $value){
                 
                  //$biller1=[$key]$value->billerId;
                  //echo $biller1=$value->billerId;
                    $biller1=$value->billerId[0];
                   ////echo "<pre>";
                  $biller=array('billerId'        =>$value->billerId[0],
                                'billerName'      =>$value->billerName[0],
                                'billerCategory'  =>$value->billerCategory[0],
                                'billerAdhoc'     =>$value->billerAdhoc[0],
                                
                                'billerCoverage'            =>$value->billerCoverage[0],
                               'billerFetchRequiremet'     =>$value->billerFetchRequiremet[0],
                                 'billerPaymentExactness'    =>$value->billerPaymentExactness[0],
                                 'billerSupportBillValidation'=>$value->billerSupportBillValidation[0],//ok
                                 'supportPendingStatus'        =>$value->supportPendingStatus[0],
                                 'supportDeemed'               =>$value->supportDeemed[0],
                                 'billerTimeout'               =>json_encode($value->billerTimeout[0]),
                                 'billerInputParams'           =>json_encode($value->billerInputParams[0]),
                                'billerAmountOptions'         =>json_encode($value->billerAmountOptions[0]),
                                 'billerPaymentModes'          =>$value->billerPaymentModes[0],
                                   'billerDescription'               =>$value->billerDescription[0],
                                   'rechargeAmountInValidationRequest'=>json_encode($value->rechargeAmountInValidationRequest[0]),
                                   'billerPaymentChannels'       =>json_encode($value->billerPaymentChannels[0]),
                                );
                  //print_r($biller);

                  $billerId=$value->billerId[0];
                  //echo 'SELECT id FROM `tbl_bbps_list` where billerId="'.$billerId.'"';
                  $sql=$this->db->query('SELECT id FROM `tbl_bbps_list` where billerId="'.$billerId.'"')->row();
                    if(empty($sql)){
                    $this->db->insert('tbl_bbps_list',$biller);
                    } else{
                    $this->db->where('billerId',$billerId);
                    $this->db->update('tbl_bbps_list', $biller);
                    }
                 }
                 
                  $response = array(
                    'status' => "true",
                    'msg' => "SUCCESS",
                    'result' => 'BBPS Inserted Successfully'
                  ); 
              
               }else{
                $response = array(
                'status' => "false",
                'msg' => "Empty biller",
                'result' => $xml
                );
               
               }
              
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
            exit;
          }
          
          public function fetchBillDetailsNew() {
              $data =  json_decode(file_get_contents('php://input'), true);
              $this->authenticateUser($data);
              
              if (!empty($data['billerID']) && !empty($data['a0'])) {
                  $order_id = $this->generateRandomString(); 
                  $api = $this->rechargeApi_model->getOne('tbl_api_settings','6','api_id')->row(); //payrecharge now api
                  $url = $api->api_url."api/bill-fetch.php?username=".$api->username."&apikey=".$api->api_token."&format=json&no=".$data['a0']."&operator=".$data['billerID']."&txnid=".$order_id;
                  
                  $ch = curl_init($url);
                  curl_setopt($ch, CURLOPT_URL, $url);
                  curl_setopt($ch, CURLOPT_TIMEOUT, 36000);
                  curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
                  
                  $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                  $result = curl_exec($ch);
                  if(curl_errno($ch)){
                      $error_msg = curl_error($ch);
                  }
                  curl_close($ch);
                  write_file('admin/ashish.txt', $result);
                  if(isset($error_msg)) {
                      $response = array(
                          'status' => "false",
                          'msg' => "Invalid Request",
                          'result' => null
                          );
                  } else {
                      $body = json_decode($result,true);
                      if(isset($body['status']) && $body['status'] == "SUCCESS") {
                          $fetch = array(
                              'due_amount' => $body['dueAmount'],
                              'due_date' => $body['dueDate'],
                              'bill_date' => $body['billDate'],
                              'customer_name' => $body['customerName'],
                              'consumer_no' => $data['billerID'],
                              'orderId' => time(),
                          );
                          $response = array(
                              'status' => "true",
                              'msg' => "SUCCESS",
                              'result' => $fetch
                          );
                      }
                  }
                  
                  
              } else {
                  $response = array(
                      'status' => "false",
                      'msg' => "Invalid Request",
                      'result' => null
                  );
              }
              echo json_encode($response);
              exit;
          }
          
          public function fetchBillDetails() {
            $data =  json_decode(file_get_contents('php://input'), true);
            $this->authenticateUser($data);
          //$data =array('operatorCode'=>"RA",'mobileNumber'=>'9601466980','amount'=>'70'); 

            if (!empty($data['billerID'])) {
              // $mobile_number = $data['mobileNumber'];
              $order_id = $this->generateRandomString();//date("his");   
              //$order_id = "ord_".$data['user_id']."_".$order_id;
              $api=$this->rechargeApi_model->getOne('tbl_api_settings','7','api_id')->row();
              $url = ''.$api->api_url.'extBillCntrl/billFetchRequest/xml';
              //$url = 'https://api.billavenue.com/billpay/extBillCntrl/billFetchRequest/xml';
              
              //$workingKey ="267CCE2A18759131586D028FF049B35D";
              $workingKey =$api->api_secretkey;
              //$accessCode = "AVAI39GI73ZC50TZNR";
              $accessCode = $api->accessCode;
              //$institutionID = "BA03";
              $institutionID = $api->institutionID;
              //$institutionName = "SMARTPAY";
              $institutionName = $api->institutionName;
              //$agent_id="CC01BA03MOBU00000001";
              $agent_id=$api->agent_id;
              $bill=$this->rechargeApi_model->getOne('tbl_bbps_list',$data['billerID'],'billerId')->row();
              $user=$this->rechargeApi_model->getOne('tbl_users',$data['user_id'],'userId')->row();
              //print_r($bill->billerInputParams);
              //exit();
              if(!empty($bill)) { 
                
              $myJson     =''.$bill->billerInputParams.'';
              $customize  =explode(',',$bill->billercustomizeInputParams);
              // print_r($customize); 
              // echo count($customize); 
              //customize start
              if($bill->billercustomize=="Yes"){
                $input="<inputParams>";
               foreach($customize as $key => $val){
              
               $input.='<input><paramName>'.$val.'</paramName><paramValue>'.$data['a'.$key.''].'</paramValue></input>';
               }
                $input.='</inputParams>';
              }
               //customize end
               
                  
             // $myJson='{"paramInfo":[{"paramName": "BU","dataType":"NUMERIC","isOptional":"false","minLength": "4","maxLength": "4", "regEx": "^[0-9]{4}$"},{"paramName":"Consumer No","dataType":"NUMERIC","isOptional":"false","minLength":"12","maxLength":"12","regEx":"^[0-9]{12}$"}]}';
             //$myJson= '{"paramInfo":{"paramName":"Enrollment Id","dataType":"ALPHANUMERIC","isOptional":"false","minLength":"7","maxLength":"14","regEx":"^[a-zA-Z0-9]{7,14}$"}}';
                 //test
              //$input='<inputParams><input><paramName>Mobile Number</paramName><paramValue>9985518686</paramValue></input></inputParams>';
              //all input parameter
              else{
              $myJson = json_decode($myJson,true);
              // print_r($myJson);
              // if(is_array($myJson['paramInfo'][0])){
              if (array_key_exists("paramName",$myJson['paramInfo'])){

                $input="<inputParams><input><paramName>".$myJson['paramInfo']['paramName']."</paramName><paramValue>".$data['a0']."</paramValue></input></inputParams>";

                
              }else{
                $input='<inputParams>';
                foreach($myJson['paramInfo'] as $key => $val){
                $input.='<input><paramName>'.$val['paramName'].'</paramName><paramValue>'.$data['a'.$key.''].'</paramValue></input>';
                // print_r($val['paramName']);
                // echo "==";
                // print_r($data['a'.$key.'']);
                }
                $input.='</inputParams>';
              }
            }
            //all input end
              
              //  $mac = $data['mac_address'];
                $request = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><billFetchRequest><agentId>".$agent_id."</agentId><agentDeviceInfo><ip>".$_SERVER['SERVER_ADDR']."</ip><initChannel>".$api->initChannel."</initChannel><imei>".$api->imei."</imei><app>".$api->app."</app><os>".$api->os."</os>
              </agentDeviceInfo><customerInfo><customerMobile>".$user->mobile."</customerMobile><customerEmail></customerEmail><customerAdhaar></customerAdhaar><customerPan></customerPan></customerInfo><billerId>".$data['billerID']."</billerId>".$input."</billFetchRequest>";
              
              // echo $request;
              //die;
              $encrypt_req = $this->encrypt($request,$workingKey);
              //echo $decrypt_req = $this->decrypt($encrypt_req,$workingKey);
              $post_data = array(
                'accessCode' => $accessCode,
                'requestId' => $order_id,
                'encRequest' => $encrypt_req,
                'ver' => '1.0',
                'instituteId' => $institutionID
              );  
              $parameters = http_build_query($post_data);
              //die;
              $ch = curl_init($url);
              curl_setopt($ch, CURLOPT_TIMEOUT, 36000);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
              curl_setopt($ch, CURLOPT_POST, 1);
              curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
              curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
              curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
              curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
              curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
              $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);    
              $result = curl_exec($ch);
              if(curl_errno($ch)){
                $error_msg = curl_error($ch);
              }
              curl_close($ch);
              if(isset($error_msg)){
                $response = array(
                  'status' => "false",
                  'msg' => "Invalid Request",
                  'result' => null
                );
              }else{
                $response = $this->decrypt($result, $workingKey);
                $xml = new SimpleXMLElement($response);
                $xml->orderId = $order_id;
                
                 $input_para[]=$xml->inputParams->input;
                 //echo count($xml->inputParams->input);
                 
                 foreach($xml->inputParams->input as $key=> $valinput){
                   // echo $valinput->paramName['0'];
                   //   echo $valinput->paramName['0'];
                 	$jin=''.$valinput->paramName.'';
                 	//print_r($jin);
                 	 $js=json_decode($jin);
                 	 //print_r($js);
                    //echo $paramName=$js->{0};
                    $jval=''.$valinput->paramValue.'';
                    //print_r($jval);
                 	 $jv=json_decode($jval);
                 	 //print_r($jv);
                    //echo $paramValue=$jv->{0};
                  $ss['input'][]=array('paramName'=>$jin,
                                   'paramValue'=>$jval);
                  
                 }

                // print_r($ss);
                  $oidj=''.$xml->orderId.'';
                 $oid=json_decode($oidj);
                 // print_r($oid);
                 // $orderId1=$oid->{0};
                 // print_r($orderId1);
                 $addinfo_new = $xml->additionalInfo;
                 if (property_exists($addinfo_new->info, 'infoName')){
                  //  if (array_key_exists("infoName",$addinfo_new->info)){

                      
                      $json_ = json_encode($addinfo_new->info);
                      $array_ = json_decode($json_,TRUE);
                      
                      $array_new['info'] = [ $array_ ];
                      $array_new1 = (object)$array_new;
                      // print_r($array_new1);
                  }
                
                 $fetch1=array(
                  'responseCode'=>'000',
                  'orderId'=>$oidj,
                  'billerID'          =>$data['billerID'],
                  'inputParams'       =>$ss,
                  'billerResponse'    =>$xml->billerResponse,
                  'billerPaymentExactness'  =>$bill->billerPaymentExactness,
                  // 'additionalInfo'    =>$xml->additionalInfo);
                  'additionalInfo'    =>$array_new1);
                 
                if($xml->responseCode == "00" || $xml->responseCode == "000"){
                  $response = array(
                    'status' => "true",
                    'msg' => "SUCCESS",
                    'result' => $fetch1
                  );
                  
                  $fetch=array('orderId'=>$xml->orderId,
                    'billerID'          =>$data['billerID'],
                    'inputParams'       =>json_encode($xml->inputParams),
                    'billerResponse'    =>json_encode($xml->billerResponse),
                    'additionalInfo'    =>json_encode($xml->additionalInfo));
                    // 'additionalInfo'    =>json_encode($array_new1));
                  $this->db->insert('tbl_bill_fetch',$fetch);

                }else{
                  $response = array(
                    'status' => "false",
                    'msg' => "FAILED",
                    'result' => $xml
                  );
                }
              }
               }//nt empty biller id
               else{
               $response = array(
                'status' => "false",
                'msg' => "Biller not available",
                'result' => null
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
          
          public function billPayNew() {        
            $data =  json_decode(file_get_contents('php://input'), true);
            $this->authenticateUser($data);
            
            if (!empty($data['operatorID']) && !empty($data['billerID']) && !empty($data['orderId']) && !empty($data['amount']) && !empty($data['billPayType'])) {
              $user_id= $data['user_id'];
              $role_id= $data['role_id'];
              $operator_id = $data['operatorID'];
              $amount = $data['amount'];
              //check fund transfer duplicate occur with in minutes start
              $wherecheck=array("operator_id"=>$operator_id,
                                "user_id"=>$user_id,
                                "request_amount"=>$data['amount']);
              $transdate=date('Y-m-d H:i');
              $min="1";
              $transduplicatecheck=$this->moneyTransferApi_model->checkduplicate_transaction_new($transdate,$wherecheck,$min);
              if(!empty($transduplicatecheck)){
                 $response = array(
                
                'status' => "false",
                'msg' => "Duplicate  Trasaction please Try again after a minute",
                'result' =>"",
              );
              echo json_encode($response);
              exit;
              }
              //check fund transfer duplicate occur with in minutes end
              $order_id = $data['orderId'];
              //$order_id = $this->generateRandomString(); 
              //$this->generateRandomString();
              $order_uni=$this->get_order_id();
              $ores=json_decode($order_uni,true);
              $sno_client_id=$ores['sno_order_id'];
              $client_id=$ores['order_id'];
              $admin_commission = 0;
              $md_commission = 0;
              $api_commission = 0;
              $distributor_commission = 0;
              $retailer_commission = 0; 
              $api=$this->rechargeApi_model->getOne('tbl_api_settings','6','api_id')->row();
              
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
        
              $serviceDtl = $this->operatorApi_model->getServiceDetailsByOperatorID($operator_id);//operator_id,service_id,api_id
              
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
        
              //check balance of user begin
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
                    $commissionDtl = $this->rechargeApi_model->getCommissionDetails($user_package_id,$service_id,$data['operatorID'],$amount);              
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
                
                  $updatedBalance = $wallet_balance-$amount; 
                  $bill=$this->rechargeApi_model->getOne('tbl_payrcnow',$data['billerID'],'billercode')->row();
                  $wallet_trans_info = array(
                    'service_id' => $service_id,
                    'order_id' => $client_id, 
                    'user_id' => $user_id, 
                    'operator_id' => $operator_id,
                    'api_id' => $api->api_id,
                    'transaction_status' =>'SUCCESS',
                    'transaction_type' => "DEBIT",
                    'payment_type' => "SERVICE",
                    'payment_mode' => "PAID FOR ".$bill->biller_name .",ACCOUNT NUMBER ". $data['consumer_no'] ?? "" .",AMOUNT ".$data['amount'],
                    'transaction_id' => $client_id,               
                    'trans_date' => date("Y-m-d H:i:s"),  
                    'total_amount' => $data['amount'],
                    'charge_amount' => "0.00",
                    'balance' => $updatedBalance,
                    'updated_on'=>date('Y-m-d H:i:s'),
                  );
                  
                  $wallet_txn_id = $this->transactions_model->addNewWalletTransaction($wallet_trans_info);
                  //update balance into users table                           
                  $updateBalQry = $this->rechargeApi_model->updateUserBalance($user_id,$updatedBalance);
                  
                  $user = $this->rechargeApi_model->getOne('tbl_users',$user_id,'userId')->row();
                  //update balance after deduction end
                //save api log details end 
        
                 $fetch1=array(
                  'responseCode'=>'000',
                  'responseReason'=>"",
                  'txnRefId'         =>"",
                  'approvalRefNumber'=>"",
                  'txnRespType'      =>"PENDING",
                  'inputParams'      =>"",
                  'CustConvFee'      =>"",
                  'RespAmount'       =>"".$data['amount']."",
                  'RespBillDate'     =>"",
                  'RespBillNumber'   =>"",
                  'RespBillPeriod'   =>"",
                  'RespCustomerName' =>"",
                  'RespDueDate'    =>"");
                     
                     
                     
                     $inputParams = array(
                         "inputParams"=>array(
                             "input"=>array(
                                 "paramName"=>"Consumer No",
                                 "paramValue"=>$data['consumer_no'] ?? ""
                             )
                         )
                     );
                  $trans_info = array(
                    'transaction_id' => $data['orderId'],
                    'recipient_id'=> "",
                    'transaction_status' => 'PENDING', 
                    'service_id' => $service_id,
                    'api_id' => '',
                    'trans_date' => date("Y-m-d H:i:s"),  
                    'order_id' => $client_id,  
                    'mobileno' => $user->mobile,  
                    'user_id' => $user_id,               
                    'operator_id' => $operator_id,
                    'total_amount' => $data['amount'],
                    'charge_amount' => "0.00",
                    'basic_amount' => $data['amount'],
                    'balance' => "0.00",
                    'order_status' => 'PENDING',
                    'transaction_msg'=>'PENDING',
                    'response_msg'=>json_encode($inputParams),
                    'updated_on'=>date('Y-m-d H:i:s'),
                    'CustConvFee'=> "0.00",
                    'RespAmount' =>$data['amount'],
                    'RespBillDate' =>"",
                    'RespBillNumber' =>$data['consumer_name'] ?? "",
                    'RespBillPeriod' =>"",
                    'RespCustomerName' =>$data['customer_name'] ?? ".",
                    'RespDueDate' =>"",
                    'request_amount'=>$data['amount'],
                    'billerID'=>$data['billerID'],
                    'billPayType'=>$data['billPayType'],
                    'bill_orderId'=>$order_id,
                  );
                  $txn_id = $this->transactions_model->addNewTransaction($trans_info);
                  
                  $response = array(
                    'status' => "true",
                    'msg' => "SUCCESS",
                    'order_id'=>$data['orderId'],
                    'result' =>$fetch1
                  );
                  $this->rechargeApi_model->send_telegram_api($client_id);
                  //commission wallet txn begin
                 if(is_numeric($role_id) && intval($role_id) <= 4){                
                  $walletUserID = $user_id;
                  $walletRoleID = $role_id;
                  $isUserBalanceUpdated = false;
                  for($i=$walletRoleID;$i>=1;$i--){                
                    if($i == 3){
                      $isUserBalanceUpdated = true;
                      $userParentID = $this->rechargeApi_model->getUserParentID($walletUserID);
                      if ($isUserBalanceUpdated && $userParentID && $userParentID->roleId==3) {
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
                      if($walletRoleID == 4){ //Retailer
                        $walletAmt = $retailer_commission;
                        $walletBal = $updatedBalance+$retailer_commission;
                      }/*else if($walletRoleID == 3){ //FOS
                        $walletAmt = $distributor_commission;
                        $walletBal = $updatedBalance+$distributor_commission;
                      }*/else if($walletRoleID == 2){ //Distributor
                        $walletAmt = $distributor_commission;
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
                          'order_id' => $client_id, 
                          'user_id' => $walletUserID, 
                          'operator_id' => $operator_id,
                          'api_id' => $api->api_id,
                          'transaction_status' => 'PENDING',
                          'transaction_type' => $transType,
                          'payment_type' => "COMMISSION",
                          'payment_mode' => "COMMISSION FOR ".$bill->billerName.", ACCOUNT NUMBER ". $data['consumer_no'] ?? "" .", AMOUNT ".$data['amount'],
                          'transaction_id' => $client_id,               
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
                  //commission wallet txn end 
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


          public function billPay() {        
            $data =  json_decode(file_get_contents('php://input'), true);
            $this->authenticateUser($data);
            
            if (!empty($data['operatorID']) && !empty($data['billerID']) && !empty($data['orderId']) && !empty($data['amount']) && !empty($data['billPayType'])) {
              $user_id= $data['user_id'];
              $role_id= $data['role_id'];
              $operator_id = $data['operatorID'];
              $amount = $data['amount'];
              //check fund transfer duplicate occur with in minutes start
              $wherecheck=array("operator_id"=>$operator_id,
                                "user_id"=>$user_id,
                                "request_amount"=>$data['amount']);
              $transdate=date('Y-m-d H:i');
              $min="1";
              $transduplicatecheck=$this->moneyTransferApi_model->checkduplicate_transaction_new($transdate,$wherecheck,$min);
              if(!empty($transduplicatecheck)){
                 $response = array(
                
                'status' => "false",
                'msg' => "Duplicate  Trasaction please Try again after a minute",
                'result' =>"",
              );
              echo json_encode($response);
              exit;
              }
             
              //check fund transfer duplicate occur with in minutes end
              $order_id = $data['orderId'];
              //$order_id = $this->generateRandomString(); 
              //$this->generateRandomString();
              $order_uni=$this->get_order_id();
              $ores=json_decode($order_uni,true);
              $sno_client_id=$ores['sno_order_id'];
              $client_id=$ores['order_id'];
              $admin_commission = 0;
              $md_commission = 0;
              $api_commission = 0;
              $distributor_commission = 0;
              $retailer_commission = 0; 
              $api=$this->rechargeApi_model->getOne('tbl_api_settings','7','api_id')->row();
              
              $mobile_number = $data['mobileNumber'] ?? "";
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

              $serviceDtl = $this->operatorApi_model->getServiceDetailsByOperatorID($operator_id);//operator_id,service_id,api_id
              
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

              //check balance of user begin
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
                
                    $commissionDtl = $this->rechargeApi_model->getCommissionDetails($user_package_id,$service_id,$data['operatorID'],$amount);              
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

                //check active api for operator begin
             $api_details = $this->rechargeApi_model->getActiveApiDetails($operator_id,$service_id,$amount);
               
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
                //print_r($api_details);

                // $operatorCodeDtl = $this->operatorApi_model->getOperatorDetailsByID($service_id,$api_details->api_id,$operator_id);//operator_id,service_id,api_id
                // if($operatorCodeDtl){
                //   $operator_code = $operatorCodeDtl[0]->operator_code;
                // }else{
                //   $response = array(
                //     'status' => "false",
                //     'msg' => "Invalid Operator",
                //     'result' => null
                //   );
                //   echo json_encode($response);
                //   exit;
                // }
                 //update balance after deduction begin
                  $updatedBalance = $wallet_balance-$amount;
                  
                  $bill = $this->rechargeApi_model->getOne('tbl_bbps_list',$data['billerID'],'billerId')->row();
                  
                  
                  $billfetch = $this->rechargeApi_model->getOne('tbl_bill_fetch',$data['orderId'],'orderId')->row();
                  //print_r($billfetch);
                 // exit();
                  //insert DEBIT txn into tbl_wallet_trans_dtls table
                  $inputParams = array(
                         "inputParams"=>array(
                             "input"=>array(
                                 "paramName"=>"Consumer No",
                                 "paramValue"=>$data['consumer_no'] ?? ""
                             )
                         )
                     );
                     
                    //  $myJson1 = json_decode($myJson1,true);
                    //  $rv = array_filter($myJson1['input'], 'is_array');
                    //  echo count($rv);
                     
                    //  $consu = "";
                     $myJson=''.$billfetch->inputParams.'';
                 
              $myJson = json_decode($myJson,true);
             
               $rv = array_filter($myJson['input'], 'is_array');
             
                     if(count($rv)>=0) {
                         $input='<inputParams>';
                      foreach($myJson['input'] as $key => $val){
                          if($val['paramName'] == 'Consumer No') {
                              $consu = $val['paramValue'];
                          }
                        
                      }
                  $wallet_trans_info = array(
                    'service_id' => $service_id,
                    'order_id' => $client_id, 
                    'user_id' => $user_id, 
                    'operator_id' => $operator_id,
                    'api_id' => $api_details->api_id,
                    'transaction_status' =>'success',
                    'transaction_type' => "DEBIT",
                    'payment_type' => "SERVICE",
                    'payment_mode' => "PAID FOR ".$bill->billerName.", ACCOUNT NUMBER ". $consu .", AMOUNT ".$data['amount'],
                    'transaction_id' =>'',               
                    'trans_date' => date("Y-m-d H:i:s"),  
                    'total_amount' => $data['amount'],
                    'charge_amount' => "0.00",
                    'balance' => $updatedBalance,
                    'updated_on'=>date('Y-m-d H:i:s'),
                  );
                  
                  $wallet_txn_id = $this->transactions_model->addNewWalletTransaction($wallet_trans_info);
                  //update balance into users table                           
                  $updateBalQry = $this->rechargeApi_model->updateUserBalance($user_id,$updatedBalance);

                  //update balance after deduction end        
              //$url = 'https://api.billavenue.com/billpay/extBillPayCntrl/billPayRequest/xml';
              // $workingKey ="267CCE2A18759131586D028FF049B35D";
              // $accessCode = "AVAI39GI73ZC50TZNR";
              // $institutionID = "BA03";
              // $institutionName = "SMARTPAY";
              // $agent_id="CC01BA03MOBU00000001";
              
               $url = ''.$api_details->api_url.'extBillPayCntrl/billPayRequest/xml';
             
              $workingKey =$api_details->api_secretkey;
              $accessCode = $api_details->accessCode;
              $institutionID = $api_details->institutionID;
              $institutionName = $api_details->institutionName;
              $agent_id=$api_details->agent_id;
              $user=$this->rechargeApi_model->getOne('tbl_users',$data['user_id'],'userId')->row();
              
              $myJson=''.$billfetch->inputParams.'';
              
              $myJson = json_decode($myJson,true);
             
              //echo count($myJson);
              
              // echo $myJson['input']['paramName'];
              // echo count($myJson['input']);
               $rv = array_filter($myJson['input'], 'is_array');
               
               if(count($rv)>0){
                //echo "true";
                $input='<inputParams>';
              foreach($myJson['input'] as $key => $val){
                $input.='<input><paramName>'.$val['paramName'].'</paramName><paramValue>'.$val['paramValue'].'</paramValue></input>';
                
              }
                $input.='</inputParams>';
               }else{
               
                $input='<inputParams><input><paramName>'.$myJson['input']['paramName'].'</paramName><paramValue>'.$myJson['input']['paramValue'].'</paramValue></input></inputParams>';
               }
              //  print_r($myJson);
              //  print_r($input);
              
              //  print_r($rv);
              // exit();
              
              
              

              $billres=$billfetch->billerResponse;
              $billres = json_decode($billres,true);
              //print_r($billres['amountOptions']['option']);
              $billresponse='<billerResponse><amountOptions>';
              foreach($billres['amountOptions']['option'] as $key => $val1){
               
               $billresponse.='<option><amountName>'.$val1['amountName'].'</amountName><amountValue>'.$val1['amountValue'].'</amountValue></option>';
              }
              $billresponse.='</amountOptions><billAmount>'.$billres['billAmount'].'</billAmount><billDate>'.$billres['billDate'].'</billDate><billNumber>'.$billres['billNumber'].'</billNumber><billPeriod>'.$billres['billPeriod'].'</billPeriod><customerName>'.$billres['customerName'].'</customerName><dueDate>'.$billres['dueDate'].'</dueDate></billerResponse>';
              $addinfo=$billfetch->additionalInfo;
              $addinfo = json_decode($addinfo,true);
              // print_r($addinfo);
              // exit();
              $additionalInfo='<additionalInfo>';
              //vishal 10-02-2021
                //old
                // foreach($addinfo['info'] as $key => $val2){
                
                // $additionalInfo.='<info><infoName>'.$val2['infoName'].'</infoName><infoValue>'.$val2['infoValue'].'</infoValue></info>';
                // }
              if (array_key_exists("infoName",$addinfo['info'])){
                $addinfo['info']['infoValue'] = str_replace("&","&amp;", $addinfo['info']['infoValue']);
                $additionalInfo.='<info><infoName>'. $addinfo['info']['infoName'].'</infoName><infoValue>'. $addinfo['info']['infoValue'].'</infoValue></info>';
              }else {
              
                foreach($addinfo['info'] as $key => $val2){
                  $val2['infoValue'] = str_replace("&","&amp;", $val2['infoValue']);
                  $additionalInfo.='<info><infoName>'.$val2['infoName'].'</infoName><infoValue>'.$val2['infoValue'].'</infoValue></info>';
                }
              }
             
              $additionalInfo.='</additionalInfo>';
              // print_r($additionalInfo);
              
                
              if($data['billPayType'] == "normal"){
               $bbpsamt=$data['amount']*100;
              $request='<?xml version="1.0" encoding="UTF-8"?><billPaymentRequest><agentId>'.$agent_id.'</agentId><agentDeviceInfo><ip>'.$_SERVER['SERVER_ADDR'].'</ip><initChannel>'.$api_details->initChannel.'</initChannel><app>'.$api_details->app.'</app><os>'.$api_details->os.'</os><imei>'.$api_details->imei.'</imei></agentDeviceInfo><billerAdhoc>true</billerAdhoc><customerInfo><customerMobile>'.$user->mobile.'</customerMobile><customerEmail /><customerAdhaar /><customerPan /></customerInfo><billerId>'.$data['billerID'].'</billerId>'.$input.''.$billresponse.''.$additionalInfo.'<amountInfo><amount>'.$bbpsamt.'</amount><currency>356</currency><custConvFee>0</custConvFee><amountTags></amountTags></amountInfo><paymentMethod><paymentMode>Wallet</paymentMode><quickPay>N</quickPay><splitPay>N</splitPay></paymentMethod><paymentInfo><info><infoName>WalletName</infoName><infoValue>'.$api_details->app.'</infoValue></info><info><infoName>MobileNo</infoName><infoValue>'.$user->mobile.'</infoValue></info></paymentInfo></billPaymentRequest>';

              }else{
                $bbpsamt=$data['amount']*100;

                 echo $request = '<?xml version="1.0" encoding="UTF-8"?><billPaymentRequest><agentId>'.$agent_id.'</agentId><agentDeviceInfo><ip>'.$_SERVER['SERVER_ADDR'].'</ip><initChannel>'.$api_details->initChannel.'</initChannel><app>'.$api_details->app.'</app><os>'.$api_details->os.'</os><imei>'.$api_details->imei.'</imei></agentDeviceInfo><billerAdhoc>true</billerAdhoc><customerInfo><customerMobile>'.$user->mobile.'</customerMobile><customerEmail /><customerAdhaar /><customerPan /></customerInfo><billerId>'.$data['billerID'].'</billerId>'.$input.''.$billresponse.''.$additionalInfo.'<amountInfo><amount>'.$bbpsamt.'</amount><currency>356</currency><custConvFee>0</custConvFee><amountTags></amountTags></amountInfo><paymentMethod><paymentMode>Wallet</paymentMode><quickPay>N</quickPay><splitPay>N</splitPay></paymentMethod><paymentInfo><info><infoName>WalletName</infoName><infoValue>'.$api_details->app.'</infoValue></info><info><infoName>MobileNo</infoName><infoValue>'.$user->mobile.'</infoValue></info></paymentInfo></billPaymentRequest>';
              }
           
              
               $encrypt_req = $this->encrypt($request,$workingKey);
              //echo $decrypt_req = $this->decrypt($encrypt_req,$workingKey);
              $post_data = array(
                'accessCode' => $accessCode,
                'requestId' => $order_id,
                'encRequest' => $encrypt_req,
                'ver' => '1.0',
                'instituteId' => $institutionID
              );  
           
              $parameters = http_build_query($post_data);
              // print_r($url);
              // print_r("Data");
               //print_r($request);
              $ch = curl_init($url);
              curl_setopt($ch, CURLOPT_TIMEOUT, 36000);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
              curl_setopt($ch, CURLOPT_POST, 1);
              curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
              curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
              curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
              curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
              curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
              /*curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
              curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
              curl_setopt($ch, CURLOPT_CAINFO, base_url()."assets/cert/billavenue_UAT_Certificate.crt"); //  ok*/
              $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);    
              $result = curl_exec($ch);

              
              if(curl_errno($ch)){
                $error_msg = curl_error($ch);
              }
              curl_close($ch);
              if(isset($error_msg)){
                //save api log details begin
                $api_info = array(
                  'service_id' => $service_id."", 
                  'api_id' => $api_details->api_id."", 
                  'api_name' => $api_details->api_name."",  
                  'api_method' => "BBPSTransaction",
                  'api_url' => $api_details->api_url."", 
                  'order_id' => $client_id, 
                  'user_id' => $data['user_id'],  
                  'request_input' => $request."",
                  'request' => $parameters."",         
                  'response' => $result."",
                  'access_type' => "APP",
                  'updated_on'=>date('Y-m-d H:i:s'),
                );
                
                $api_log_id = $this->apiLog_model->addApiLogDetails($api_info);
                //save api log details end 
                $response = array(
                  'status' => "false",
                  'msg' => "Invalid Request",
                  'result' => null
                );
              }else{
                
                $api_response = $this->decrypt($result, $workingKey);
                $xml = new SimpleXMLElement($api_response);
                //print_r($xml);
                //save api log details begin
                $api_info = array(
                  'service_id' => $service_id."", 
                  'api_id' => $api_details->api_id."", 
                  'api_name' => $api_details->api_name."",  
                  'api_method' => "BBPSTransaction",
                  'api_url' => $api_details->api_url."", 
                  'order_id' => $client_id, 
                  'user_id' => $data['user_id'],  
                  'request_input' => $request."",
                  'request' => $parameters."",         
                  'response' => json_encode($xml)."",
                  'access_type' => "APP",
                  'updated_on'=>date('Y-m-d H:i:s'),
                );
                
                $api_log_id = $this->apiLog_model->addApiLogDetails($api_info);
                //save api log details end 
                if($xml->responseCode == "00" || $xml->responseCode == "000"){
                  $input_para[]=$xml->inputParams->input;
                 //echo count($xml->inputParams->input);
                 
                 foreach($xml->inputParams->input as $key=> $valinput){
                   // echo $valinput->paramName['0'];
                   //   echo $valinput->paramName['0'];
                  $jin=''.$valinput->paramName.'';
                  //print_r($jin);
                   $js=json_decode($jin);
                   //print_r($js);
                    //echo $paramName=$js->{0};
                    $jval=''.$valinput->paramValue.'';
                    //print_r($jval);
                   $jv=json_decode($jval);
                   //print_r($jv);
                    //echo $paramValue=$jv->{0};
                  $ss['input'][]=array('paramName'=>$jin,
                                   'paramValue'=>$jval);
                  
                 }
                 

                // print_r($ss);
                  
                 // print_r($oid);
                 // $orderId1=$oid->{0};
                 // print_r($orderId1);
                 $fetch1=array(
                  'responseCode'=>'000',
                  'responseReason'=>"".$xml->responseReason."",
                  'txnRefId'         =>"".$xml->txnRefId."",
                  'approvalRefNumber'=>"".$xml->approvalRefNumber."",
                  'txnRespType'      =>"".$xml->txnRespType."",
                  'inputParams'      =>$ss,
                  'CustConvFee'      =>"".$xml->CustConvFee."",
                  'RespAmount'       =>"".$xml->RespAmount."",
                  'RespBillDate'     =>"".$xml->RespBillDate."",
                  'RespBillNumber'   =>"".$xml->RespBillNumber."",
                  'RespBillPeriod'   =>"".$xml->RespBillPeriod."",
                  'RespCustomerName' =>"".$xml->RespCustomerName."",
                  'RespDueDate'    =>"".$xml->RespDueDate."");
                  $response = array(
                    'status' => "true",
                    'msg' => "SUCCESS",
                    'order_id'=>$client_id,
                    'result' =>$fetch1
                  );
                     
                  $trans_info = array(
                    'transaction_id' => $xml->txnRefId,
                    'recipient_id'=> $xml->approvalRefNumber,
                    'transaction_status' => $xml->responseReason, 
                    'service_id' => $service_id,
                    'api_id' => $api_details->api_id,
                    'trans_date' => date("Y-m-d H:i:s"),  
                    'order_id' => $client_id,  
                    'mobileno' => $user->mobile,  
                    'user_id' => $user_id,               
                    'operator_id' => $operator_id,
                    'total_amount' => $xml->RespAmount/100,
                    'charge_amount' => "0.00",
                    'basic_amount' => $xml->RespAmount/100,
                    'balance' => $balance,
                    'order_status' => 'Success',
                    'transaction_msg'=>$xml->responseReason,
                    'response_msg'=>json_encode($xml),
                    'updated_on'=>date('Y-m-d H:i:s'),
                    'CustConvFee'=>$xml->CustConvFee/100,
                    'RespAmount' =>$xml->RespAmount,
                    'RespBillDate' =>$xml->RespBillDate,
                    'RespBillNumber' =>$xml->RespBillNumber,
                    'RespBillPeriod' =>$xml->RespBillPeriod,
                    'RespCustomerName' =>$xml->RespCustomerName,
                    'RespDueDate' =>$xml->RespDueDate,
                    'request_amount'=>$amount,
                    'billerID'=>$data['billerID'],
                    'billPayType'=>$data['billPayType'],
                    'bill_orderId'=>$order_id,
                  );
                  $txn_id = $this->transactions_model->addNewTransaction($trans_info);
                  //update balance based on api id in api setting table developed by susmitha start
                  //commission wallet txn begin
                 if(is_numeric($role_id) && intval($role_id) <= 4){                
                  $walletUserID = $user_id;
                  $walletRoleID = $role_id;
                  $isUserBalanceUpdated = false;
                  for($i=$walletRoleID;$i>=1;$i--){                
                    if($i == 3){
                      $isUserBalanceUpdated = true;
                      $userParentID = $this->rechargeApi_model->getUserParentID($walletUserID);
                      if ($isUserBalanceUpdated && $userParentID && $userParentID->roleId==3) {
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
                      if($walletRoleID == 4){ //Retailer
                        $walletAmt = $retailer_commission;
                        $walletBal = $updatedBalance+$retailer_commission;
                      }/*else if($walletRoleID == 3){ //FOS
                        $walletAmt = $distributor_commission;
                        $walletBal = $updatedBalance+$distributor_commission;
                      }*/else if($walletRoleID == 2){ //Distributor
                        $walletAmt = $distributor_commission;
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
                          'order_id' => $client_id, 
                          'user_id' => $walletUserID, 
                          'operator_id' => $operator_id,
                          'api_id' => $api_details->api_id,
                          'transaction_status' => $xml->responseReason,
                          'transaction_type' => $transType,
                          'payment_type' => "COMMISSION",
                          'payment_mode' => "Commission by BBPS",
                          'transaction_id' => $xml->txnRefId,               
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
                  //commission wallet txn end 
                }else{
                  $response = array(
                    'status' => "false",
                    'msg' => "FAILED",
                    'result' => $xml
                  );
                  
                 // print_r($xml);
                 $trans_info = array(
                    'transaction_id' => $xml->txnRefId,
                    'recipient_id'=> $xml->approvalRefNumber,
                    'transaction_status' =>'FAILED', 
                    'service_id' => $service_id,
                    'api_id' => $api_details->api_id,
                    'trans_date' => date("Y-m-d H:i:s"),  
                    'order_id' => $client_id,  
                    'mobileno' => $user->mobile,  
                    'user_id' => $this->isValid($user_id),               
                    'operator_id' => $this->isValid($operator_id),
                    'total_amount' => $data['amount'],
                    'charge_amount' => "0.00",
                    'basic_amount' => $data['amount'],
                    
                    'balance' => $this->isValid($balance),
                    'order_status' => $this->isValid("FAILED"),
                    'transaction_msg'=>$xml->errorInfo->error->errorMessage,
                    'request_amount'=>$amount,
                    'updated_on'=>date('Y-m-d H:i:s'),

                  );
                  
                  //print_r($trans_info);
                  //echo "f";
                  $txn_id = $this->transactions_model->addNewTransaction($trans_info);
                   //update balance after deduction begin
                  $userbalance = $this->rechargeApi_model->getUserBalance($user_id);
                  $wallet_balance=$userbalance->wallet_balance;
                  $updatedBalance = $wallet_balance+$data['amount']; 
                  //insert DEBIT txn into tbl_wallet_trans_dtls table
                  $wallet_trans_info = array(
                    'service_id' => $service_id,
                    'order_id' => $client_id, 
                    'user_id' => $user_id, 
                    'operator_id' => $operator_id,
                    'api_id' => $api_details->api_id,
                    'transaction_status' => $xml->responseReason."",
                    'transaction_type' => "CREDIT",
                    'payment_type' => "SERVICE",
                    'payment_mode' => "REFUND FOR ".$bill->billerName.", ACCOUNT NUMBER ". $consu .", AMOUNT ".$data['amount'],
                    'transaction_id' => $xml->txnRefId."",               
                    'trans_date' => date("Y-m-d H:i:s"),  
                    'total_amount' => $data['amount'],
                    'charge_amount' => "0.00",
                    'balance' => $updatedBalance,
                    'updated_on'=>date('Y-m-d H:i:s'),
                  );
                  $wallet_txn_id = $this->transactions_model->addNewWalletTransaction($wallet_trans_info);
                  //print_r($wallet_trans_info);
                  //update balance into users table                           
                  $updateBalQry = $this->rechargeApi_model->updateUserBalance($user_id,$updatedBalance);
                  //update balance after deduction end
                }
              }
            }else{
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
          }

          public function registerComplaint(){       
            $data =  json_decode(file_get_contents('php://input'), true);

            $this->authenticateUser($data);
          //$data =array('operatorCode'=>"RA",'mobileNumber'=>'9601466980','amount'=>'70'); 

            if (!empty($data['billerID'])) {     
              $order_id = $this->generateRandomString();   
              //$order_id = "ord_".$data['user_id']."_".$order_id;

              // $url = 'https://stgapi.billavenue.com/billpay/extComplaints/register/xml';
              // $workingKey = "DB9E94A3C46347EF2A3C254C793B3113";
              // $accessCode = "AVZD78IJ22WJ27SDKF";
              // $institutionID = "SP02";
              // $institutionName = "SMARTPAY";
              $api=$this->rechargeApi_model->getOne('tbl_api_settings','7','api_id')->row();
              $url = ''.$api->api_url.'extComplaints/register/xml';
              //$url = 'https://api.billavenue.com/billpay/extBillCntrl/billFetchRequest/xml';
              //https://api.billavenue.com/billpay/extComplaints/track/xml
              
              //$workingKey ="267CCE2A18759131586D028FF049B35D";
              $workingKey =$api->api_secretkey;
              //$accessCode = "AVAI39GI73ZC50TZNR";
              $accessCode = $api->accessCode;
              //$institutionID = "BA03";
              $institutionID = $api->institutionID;
              //$institutionName = "SMARTPAY";
              $institutionName = $api->institutionName;
              //$agent_id="CC01BA03MOBU00000001";
              $agent_id=$api->agent_id;
              $request = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><complaintRegistrationReq><complaintType>Transaction</complaintType><participationType /><agentId /><txnRefId>".$data['transID']."</txnRefId><billerId /><complaintDesc>Complaint initiated through API</complaintDesc><servReason /><complaintDisposition>".$data['complaintDisposition']."</complaintDisposition></complaintRegistrationReq>";
              //die;
              $encrypt_req = $this->encrypt($request,$workingKey);
              //echo $decrypt_req = $this->decrypt($encrypt_req,$workingKey);
              $post_data = array(
                'accessCode' => $accessCode,
                'requestId' => $order_id,
                'encRequest' => $encrypt_req,
                'ver' => '1.0',
                'instituteId' => $institutionID
              );  
              $parameters = http_build_query($post_data);
              
              $ch = curl_init($url);
              curl_setopt($ch, CURLOPT_TIMEOUT, 36000);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
              curl_setopt($ch, CURLOPT_POST, 1);
              curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
              curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
              curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
              curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
              curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
              /*curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
              curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
              curl_setopt($ch, CURLOPT_CAINFO, base_url()."assets/cert/billavenue_UAT_Certificate.crt"); //  ok*/
              $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);    
              $result = curl_exec($ch);
              if(curl_errno($ch)){
                $error_msg = curl_error($ch);
              }
              curl_close($ch);
              if(isset($error_msg)){
                $response = array(
                  'status' => "false",
                  'msg' => "Invalid Request",
                  'result' => null
                );
              }else{
                $api_response = $this->decrypt($result, $workingKey);
                $api_response = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><complaintRegistrationResp><complaintAssigned>CC AVENUE</complaintAssigned><complaintId>AP1511264230664</complaintId><responseCode>000</responseCode><responseReason>SUCCESS</responseReason></complaintRegistrationResp>";
                $xml = new SimpleXMLElement($api_response);
                if($xml->responseCode == "00" || $xml->responseCode == "000"){
                  $response = array(
                    'status' => "true",
                    'msg' => "SUCCESS",
                    'result' => $xml
                  );
                }else{
                  $response = array(
                    'status' => "false",
                    'msg' => "FAILED",
                    'result' => $xml
                  );
                }
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

          public function searchTransaction(){       
            $data =  json_decode(file_get_contents('php://input'), true);

            $this->authenticateUser($data);
          //$data =array('operatorCode'=>"RA",'mobileNumber'=>'9601466980','amount'=>'70'); 
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
            if (!empty($data['transID'])) {     
              $order_id = $this->generateRandomString(); 
              $api=$this->rechargeApi_model->getOne('tbl_api_settings','7','api_id')->row();  
              //$order_id = "ord_".$data['user_id']."_".$order_id;
              $url = ''.$api->api_url.'/transactionStatus/fetchInfo/xml';
              $workingKey =$api->api_secretkey;
              $accessCode = $api->accessCode;
            
              $institutionID = $api->institutionID;
             
              $institutionName = $api->institutionName;
             
              $agent_id=$api->agent_id;
              
              $request = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><transactionStatusReq><trackType>TRANS_REF_ID</trackType><trackValue>".$data['transID']."</trackValue></transactionStatusReq>";
              //die;
              $encrypt_req = $this->encrypt($request,$workingKey);
              //echo $decrypt_req = $this->decrypt($encrypt_req,$workingKey);
              $post_data = array(
                'accessCode' => $accessCode,
                'requestId' => $order_id,
                'encRequest' => $encrypt_req,
                'ver' => '1.0',
                'instituteId' => $institutionID
              );  
              $parameters = http_build_query($post_data);
              
              $ch = curl_init($url);
              curl_setopt($ch, CURLOPT_TIMEOUT, 36000);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
              curl_setopt($ch, CURLOPT_POST, 1);
              curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
              curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
              curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
              curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
              curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
              /*curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
              curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
              curl_setopt($ch, CURLOPT_CAINFO, base_url()."assets/cert/billavenue_UAT_Certificate.crt"); //  ok*/
              $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);    
              $result = curl_exec($ch);
              if(curl_errno($ch)){
                $error_msg = curl_error($ch);
              }
              curl_close($ch);
              if(isset($error_msg)){
                $response = array(
                  'status' => "false",
                  'msg' => "Invalid Request",
                  'result' => null
                );
              }else{
                $api_response = $this->decrypt($result, $workingKey);
                $xml = new SimpleXMLElement($api_response);
                if($xml->responseCode == "00" || $xml->responseCode == "000"){
                  $response = array(
                    'status' => "true",
                    'msg' => "SUCCESS",
                    'result' => $xml
                  );
                }else{
                  $response = array(
                    'status' => "false",
                    'msg' => "FAILED",
                    'result' => $xml
                  );
                }
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
          
          //susmitha start
          public function fetchBillDetailss() {
            $data =  json_decode(file_get_contents('php://input'), true);
            // $this->authenticateUser($data);

            if (!empty($data['billerID'])) {
              $order_id = $this->generateRandomString();  
              $api=$this->rechargeApi_model->getOne('tbl_api_settings','7','api_id')->row();
              $url = ''.$api->api_url.'extBillCntrl/billFetchRequest/xml';
              
              $workingKey =$api->api_secretkey;
              $accessCode = $api->accessCode;
              $institutionID = $api->institutionID;
              $institutionName = $api->institutionName;
              $agent_id=$api->agent_id;
              $bill=$this->rechargeApi_model->getOne('tbl_bbps_list',$data['billerID'],'billerId')->row();
            //   $user=$this->rechargeApi_model->getOne('tbl_users',$data['user_id'],'userId')->row();
              if(!empty($bill)) { 
              $myJson =''.$bill->billerInputParams.'';
              $customize  =explode(',',$bill->billercustomizeInputParams);
              if($bill->billercustomize=="Yes"){
                $input="<inputParams>";
               foreach($customize as $key => $val){
              
               $input.='<input><paramName>'.$val.'</paramName><paramValue>'.$data['a'.$key.''].'</paramValue></input>';
               }
                $input.='</inputParams>';
              } else{
              $myJson = json_decode($myJson,true);
              if (array_key_exists("paramName",$myJson['paramInfo'])){
                $input="<inputParams><input><paramName>".$myJson['paramInfo']['paramName']."</paramName><paramValue>".$data['a0']."</paramValue></input></inputParams>";
              }else{
                $input='<inputParams>';
                foreach($myJson['paramInfo'] as $key => $val){
                $input.='<input><paramName>'.$val['paramName'].'</paramName><paramValue>'.$data['a'.$key.''].'</paramValue></input>';
                }
                $input.='</inputParams>';
              }
            }
                $request = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><billFetchRequest><agentId>".$agent_id."</agentId><agentDeviceInfo><ip>".$_SERVER['SERVER_ADDR']."</ip><initChannel>".$api->initChannel."</initChannel><imei>".$api->imei."</imei><app>".$api->app."</app><os>".$api->os."</os>
              </agentDeviceInfo><customerInfo><customerMobile>9999999999</customerMobile><customerEmail></customerEmail><customerAdhaar></customerAdhaar><customerPan></customerPan></customerInfo><billerId>".$data['billerID']."</billerId>".$input."</billFetchRequest>";
              $encrypt_req = $this->encrypt($request,$workingKey);
              $post_data = array(
                'accessCode' => $accessCode,
                'requestId' => $order_id,
                'encRequest' => $encrypt_req,
                'ver' => '1.0',
                'instituteId' => $institutionID
              );  
              $parameters = http_build_query($post_data);
              $ch = curl_init($url);
              curl_setopt($ch, CURLOPT_TIMEOUT, 36000);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
              curl_setopt($ch, CURLOPT_POST, 1);
              curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
              curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
              curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
              curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
              curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
              $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);    
              $result = curl_exec($ch);
              if(curl_errno($ch)){
                $error_msg = curl_error($ch);
              }
              curl_close($ch);
              if(isset($error_msg)){
                $response = array(
                  'status' => "false",
                  'msg' => "Invalid Request",
                  'result' => null
                );
              }else{
                $response = $this->decrypt($result, $workingKey);
                $xml = new SimpleXMLElement($response);
                $xml->orderId = $order_id;
                
                 $input_para[]=$xml->inputParams->input;
                 //echo count($xml->inputParams->input);
                 
                 foreach($xml->inputParams->input as $key=> $valinput){
                 	$jin=''.$valinput->paramName.'';
                 	 $js=json_decode($jin);
                    $jval=''.$valinput->paramValue.'';
                 	 $jv=json_decode($jval);
                  $ss['input'][]=array('paramName'=>$jin,'paramValue'=>$jval);
                 }

                  $oidj=''.$xml->orderId.'';
                 $oid=json_decode($oidj);
                 $addinfo_new = $xml->additionalInfo;
                 if (property_exists($addinfo_new->info, 'infoName')){
                      $json_ = json_encode($addinfo_new->info);
                      $array_ = json_decode($json_,TRUE);
                      $array_new['info'] = [ $array_ ];
                      $array_new1 = (object)$array_new;
                  }
                
                 $fetch1=array(
                //   'responseCode'=>'000',
                //   'orderId'=>$oidj,
                  'billerID'          =>$data['billerID'],
                //   'inputParams'       =>$ss,
                  'billerResponse'    =>$xml->billerResponse,
                //   'billerPaymentExactness'  =>$bill->billerPaymentExactness,
                //   'additionalInfo'    =>$array_new1
                  );
                 
                if($xml->responseCode == "00" || $xml->responseCode == "000"){
                  $response = array(
                    'status' => "true",
                    'msg' => "SUCCESS",
                    'result' => $fetch1
                  );

                }else{
                  $response = array(
                    'status' => "false",
                    'msg' => "FAILED",
                    'result' => $xml
                  );
                }
              }
               }
               else{
               $response = array(
                'status' => "false",
                'msg' => "Biller not available",
                'result' => null
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

          public function complaintTracking(){       
            $data =  json_decode(file_get_contents('php://input'), true);

            $this->authenticateUser($data);
          //$data =array('operatorCode'=>"RA",'mobileNumber'=>'9601466980','amount'=>'70'); 

            if (!empty($data['complaintId'])) {     
              $order_id = $this->generateRandomString();   
              //$order_id = "ord_".$data['user_id']."_".$order_id;
              $api=$this->rechargeApi_model->getOne('tbl_api_settings','7','api_id')->row();      
             // $url = 'https://stgapi.billavenue.com/billpay/transactionStatus/fetchInfo/xml';
              $url = ''.$api->api_url.'extComplaints/track/xml';

              // $workingKey = "DB9E94A3C46347EF2A3C254C793B3113";
              // $accessCode = "AVZD78IJ22WJ27SDKF";
              // $institutionID = "SP02";
              // $institutionName = "SMARTPAY";
              $workingKey =$api->api_secretkey;
              $accessCode = $api->accessCode;
              $institutionID = $api->institutionID;
              $institutionName = $api->institutionName;
              $agent_id=$api->agent_id;


              $request = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><complaintTrackingReq><complaintType>Transaction</complaintType><complaintId>".$data['complaintId']."</complaintId></complaintTrackingReq>";
              //die;
              $encrypt_req = $this->encrypt($request,$workingKey);
              //echo $decrypt_req = $this->decrypt($encrypt_req,$workingKey);
              $post_data = array(
                'accessCode' => $accessCode,
                'requestId' => $order_id,
                'encRequest' => $encrypt_req,
                'ver' => '1.0',
                'instituteId' => $institutionID
              );  
              $parameters = http_build_query($post_data);
              
              $ch = curl_init($url);
              curl_setopt($ch, CURLOPT_TIMEOUT, 36000);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
              curl_setopt($ch, CURLOPT_POST, 1);
              curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
              curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
              curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
              curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
              curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
              /*curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
              curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
              curl_setopt($ch, CURLOPT_CAINFO, base_url()."assets/cert/billavenue_UAT_Certificate.crt"); //  ok*/
              $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);    
              $result = curl_exec($ch);
              //print_r($result);
              if(curl_errno($ch)){
                $error_msg = curl_error($ch);
              }
              curl_close($ch);
              if(isset($error_msg)){
                $response = array(
                  'status' => "false",
                  'msg' => "Invalid Request",
                  'result' => null
                );
              }else{
                $api_response = $this->decrypt($result, $workingKey);
                
                $xml = new SimpleXMLElement($api_response);
                if($xml->responseCode == "00" || $xml->responseCode == "000"){
                  $response = array(
                    'status' => "true",
                    'msg' => "SUCCESS",
                    'result' => $xml
                  );
                }else{
                  $response = array(
                    'status' => "false",
                    'msg' => "FAILED",
                    'result' => $xml
                  );
                }
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

        //*********** Encryption Function *********************
          function encrypt($plainText, $key) {
            $secretKey = $this->hextobin(md5($key));
            $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
            $openMode = openssl_encrypt($plainText, 'AES-128-CBC', $secretKey, OPENSSL_RAW_DATA, $initVector);
            $encryptedText = bin2hex($openMode);
            return $encryptedText;
          }

        //*********** Decryption Function *********************
          function decrypt($encryptedText, $key) {
            $key = $this->hextobin(md5($key));
            $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
            $encryptedText = $this->hextobin($encryptedText);
            $decryptedText = openssl_decrypt($encryptedText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
            return $decryptedText;
          }

        //*********** Padding Function *********************
          function pkcs5_pad($plainText, $blockSize) {
            $pad = $blockSize - (strlen($plainText) % $blockSize);
            return $plainText . str_repeat(chr($pad), $pad);
          }

        //********** Hexadecimal to Binary function for php 4.0 version ********
          function hextobin($hexString) {
            $length = strlen($hexString);
            $binString = "";
            $count = 0;
            while ($count < $length) {
              $subString = substr($hexString, $count, 2);
              $packedString = pack("H*", $subString);
              if ($count == 0) {
                $binString = $packedString;
              } else {
                $binString .= $packedString;
              }

              $count += 2;
            }
            return $binString;
          }

        //********** To generate ramdom String ********
          function generateRandomString($length = 35) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
              $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
          }

          public function isValid($str){
            if(isset($str) && $str != null)
              return $str;
            else
              return '';
          }
          public function get_order_id(){
            $last_txn_id = file_get_contents("admin/txn_order_id.txt");
            $sno_client_id = intval($last_txn_id)+1;
            $client_id ="SP".$sno_client_id;
            $clientres=$this->transactions_model->check_order_id($client_id);
           if(!empty($clientres)){
            $this->writeTxnOrderID($sno_client_id);
            $this->get_order_id();
            }
           else{
           $order=array('order_id'=>$client_id,'sno_order_id'=>$sno_client_id);
           $this->writeTxnOrderID($sno_client_id);
            return json_encode($order);
           }

          }
          public function get_inputparameter_bybiller(){
            $data =  json_decode(file_get_contents('php://input'), true);
            $billerId=$data['billerID'];
           
           $biller=$this->db->query('select billerInputParams from tbl_bbps_list where billerId="'.$billerId.'"')->row();
          if(!empty($biller)){
             $biller->billerInputParams;
           $response = array(
                'status' => "true",
                'msg' => "successfully get input parameter",
                'result' =>$biller->billerInputParams 
              );
         }else{
          $response = array(
                'status' => "false",
                'msg' => "Biller data not available",
                'result' =>''
              );
         }
          
           echo json_encode($response);
          }
      public function getbillerlist_bycategory(){
            $data =  json_decode(file_get_contents('php://input'), true);
            $this->authenticateUser($data);
            if (!empty($data['operatorID'])) { 
              $op=$this->rechargeApi_model->getOne('tbl_operator_settings',$data['operatorID'],'operator_id')->row();
              $xml=$this->rechargeApi_model->getbiller_bycategory($op->operator_name);
           
            $response = array(
                    'status' => "true",
                    'msg' => "SUCCESS",
                    'result' => $xml
                  ); 
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
       public function save_billerlist_bycategory(){
            $data =  json_decode(file_get_contents('php://input'), true);
            $this->authenticateUser($data);
            if (!empty($data['category'])) { 
              
            $catdata=$this->rechargeApi_model->getbillermaster_bycategory($data['category']);
              
            $xml=$this->MultipleBillerDetails($catdata);
            //print_r($xml);
            if($xml->responseCode='000'||$xml->responseCode == "00"){
              
               if(!empty($xml->biller)){
                
                 foreach($xml->biller as $key=> $value){
                 
                  //$biller1=[$key]$value->billerId;
                  //echo $biller1=$value->billerId;
                    $biller1=$value->billerId[0];
                   ////echo "<pre>";
                  $biller=array('billerId'        =>$value->billerId[0],
                                'billerName'      =>$value->billerName[0],
                                'billerCategory'  =>$value->billerCategory[0],
                                'billerAdhoc'     =>$value->billerAdhoc[0],
                                
                                'billerCoverage'            =>$value->billerCoverage[0],
                               'billerFetchRequiremet'     =>$value->billerFetchRequiremet[0],
                                 'billerPaymentExactness'    =>$value->billerPaymentExactness[0],
                                 'billerSupportBillValidation'=>$value->billerSupportBillValidation[0],//ok
                                 'supportPendingStatus'        =>$value->supportPendingStatus[0],
                                 'supportDeemed'               =>$value->supportDeemed[0],
                                 'billerTimeout'               =>json_encode($value->billerTimeout[0]),
                                 'billerInputParams'           =>json_encode($value->billerInputParams[0]),
                                'billerAmountOptions'         =>json_encode($value->billerAmountOptions[0]),
                                 'billerPaymentModes'          =>$value->billerPaymentModes[0],
                                   'billerDescription'               =>$value->billerDescription[0],
                                   'rechargeAmountInValidationRequest'=>json_encode($value->rechargeAmountInValidationRequest[0]),
                                   'billerPaymentChannels'       =>json_encode($value->billerPaymentChannels[0]),
                                );
                  //print_r($biller);

                  $billerId=$value->billerId[0];
                  //echo 'SELECT id FROM `tbl_bbps_list` where billerId="'.$billerId.'"';
                  $sql=$this->db->query('SELECT id FROM `tbl_bbps_list` where billerId="'.$billerId.'"')->row();
                    if(empty($sql)){
                    $this->db->insert('tbl_bbps_list',$biller);
                    } else{
                    $this->db->where('billerId',$billerId);
                    $this->db->update('tbl_bbps_list', $biller);
                    }
                 }
                 
                  $response = array(
                    'status' => "true",
                    'msg' => "SUCCESS",
                    'result' => 'BBPS Inserted Successfully'
                  ); 
              
               }else{
                $response = array(
                'status' => "false",
                'msg' => "Empty biller",
                'result' => $xml
                );
               
               }
              
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
            exit;
      }    
      public function MultipleBillerDetails($data){       
             //print_r($data);
           foreach ($data as $key => $value) {
          
           
          
             $billerlist[]="<billerId>".$value['billerID']."</billerId>";
            } 
            //print_r($billerlist);
            $allbiller=implode('',$billerlist);
            // echo $allbiller;
            // exit();
          $order_id = date("His");   
              
          $url = 'https://api.billavenue.com/billpay/extMdmCntrl/mdmRequest/xml';
          $workingKey ="267CCE2A18759131586D028FF049B35D";
          $accessCode = "AVAI39GI73ZC50TZNR";
          $institutionID = "BA03";
          $institutionName = "SMARTPAY";
          $request = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><billerInfoRequest>".$allbiller."</billerInfoRequest>";
            
          $encrypt_req = $this->encrypt($request,$workingKey);
              
          $post_data = array(
                'accessCode' => $accessCode,
                'requestId' => $order_id,
                'encRequest' => $encrypt_req,
                'ver' => '1.0',
                'instituteId' => $institutionID
              );  
              $parameters = http_build_query($post_data);
              
              $ch = curl_init($url);
              curl_setopt($ch, CURLOPT_TIMEOUT, 36000);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
              curl_setopt($ch, CURLOPT_POST, 1);
              curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
              curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
              curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
              curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
              curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
             
              $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);  
              $result = curl_exec($ch);  
              //print_r($result);
              if(curl_errno($ch)){
                $error_msg = curl_error($ch);
              }
              curl_close($ch);
              if(isset($error_msg)){
                $response = array(
                  'status' => "false",
                  'msg' => "Invalid Request",
                  'result' => null
                );
               
              }else{
                
                $api_response = $this->decrypt($result, $workingKey);
                $xml = new SimpleXMLElement($api_response);
                //print_r($xml);

                if($xml->responseCode == "00" || $xml->responseCode == "000"){
                  $sample=json_encode($xml);
                  return $xml;
                }else{
                  // $response = array(
                  //   'status' => "false",
                  //   'msg' => "FAILED",
                  //   'result' => $xml
                  // );
                  return $xml;
                }
              }
              
            
            
          
        }
          public function test(){
            //echo "hi";
            $ss=array(['us'=>1],['us2'=>2]);
            //print_r($ss);
           // echo $ss =json_encode($ss);
            $data=' {
                "paymentChannelInfo": [
                    {
                        "paymentChannelName": "INT",
                        "minAmount": "1000",
                        "maxAmount": "500000000"
                    },
                    {
                        "paymentChannelName": "INTB",
                        "minAmount": "1000",
                        "maxAmount": "500000000"
                    },
                    {
                        "paymentChannelName": "MOB",
                        "minAmount": "1000",
                        "maxAmount": "500000000"
                    },
                    {
                        "paymentChannelName": "MOBB",
                        "minAmount": "1000",
                        "maxAmount": "500000000"
                    },
                    {
                        "paymentChannelName": "POS",
                        "minAmount": "1000",
                        "maxAmount": "500000000"
                    },
                    {
                        "paymentChannelName": "MPOS",
                        "minAmount": "1000",
                        "maxAmount": "500000000"
                    },
                    {
                        "paymentChannelName": "ATM",
                        "minAmount": "1000",
                        "maxAmount": "500000000"
                    },
                    {
                        "paymentChannelName": "BNKBRNCH",
                        "minAmount": "1000",
                        "maxAmount": "500000000"
                    },
                    {
                        "paymentChannelName": "KIOSK",
                        "minAmount": "1000",
                        "maxAmount": "500000000"
                    },
                    {
                        "paymentChannelName": "AGT",
                        "minAmount": "1000",
                        "maxAmount": "500000000"
                    },
                    {
                        "paymentChannelName": "BSC",
                        "minAmount": "1000",
                        "maxAmount": "500000000"
                    }
                ]
            }';
            print_r(json_decode($data));
            echo json_encode(json_decode($data));
          }



        }
        ?>