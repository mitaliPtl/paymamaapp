<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';
require APPPATH . '/libraries/PaytmChecksum.php';

class Transactions extends BaseController {
    /**
     * This is default constructor of the class
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('loginApi_model');
        $this->load->model('transactions_model');
        $this->load->model('rechargeApi_model');
        $this->load->helper('file');
        $this->load->model('moneyTransferApi_model');
    }

    public function index() {        
        
    }

    public function getAllTransctionByUser() {       
        $data =  json_decode(file_get_contents('php://input'), true);
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
        if (!empty($data['serviceID'])){ //for recharge it is //Recharge
            $serviceID = $data['serviceID'];
            $reportType = "transactions";
            if(isset($data['reportType'])){
               $reportType = $data['reportType']; 
            }
            if(isset($data['from'])){
               $from = $data['from']; 
            }else { $from=date('Y-m-d');}
            if(isset($data['to'])){
               $to = $data['to']; 
            }else { $to="";}
            if(isset($data['operator_id'])){
               $operator_id = $data['operator_id']; 
            }else { $operator_id="";}
            if(isset($data['order_status'])){
               $order_status = $data['order_status']; 
            }else { $order_status="";}
            if(isset($data['mobileno'])){
               $mobileno = $data['mobileno']; 
            }else { $mobileno="";}
            if(!empty($data['limit'])){
                $limit=$data['limit'];
            }else{ $limit=''; }
            if(!empty($data['start'])){
                $start=$data['start'];
            }else{ $start='0'; }
            
            
            
            
            $transactions = $this->transactions_model->getAllTransactions($serviceID,$user_id,$reportType,'',$from,$to,$operator_id,$order_status,$mobileno,$role_id,$limit,$start);
           
          
            if($serviceID=="5" || $serviceID=="7"){
               // $comm=$transactions;
                foreach($transactions as $val){
               $comall=$this->commisionget_order_id($val->order_no);
               $wallet=json_decode($comall,true);
                //     $wallet=array('retailer_comm'=>$retailer_comm,
                // 'distributor_comm'=>$distributor_comm,
                // 'admin_comm'      =>$admin_comm,
                // );

               $comm[]=array('id'=>$val->id,
                             'order_no'=>$val->order_no,
                             'api_id'=>$val->api_id,
                             'operator_id'=>$val->operator_id,
                             'trans_date'=>$val->trans_date,
                             'name'=>$val->name,
                             'sender_no'=>$val->sender_no,
                             'mode'=>$val->mode,
                             'amount'=>$val->amount,
                             'charge_amount'=>$val->charge_amount,
                             'retailer_comm'=>$wallet['retailer_comm'],
                             'distributor_comm'=>$wallet['distributor_comm'],
                              'admin_comm'      =>$wallet['admin_comm'],
                              'service_name'=>$val->service_name,
                              'bank_transaction_id'=>$val->bank_transaction_id,
                              'order_status'=>$val->order_status,
                              'remarks'=>$val->remarks,
                              'CCFcharges'=>$val->CCFcharges,
                              'Cashback'=>$val->Cashback,
                              'CCFcharges'=>$val->CCFcharges,
                              'TDSamount'=>$val->TDSamount,
                              'PayableCharge'=>$val->PayableCharge,
                              'FinalAmount'=>$val->FinalAmount,
                              'bank_account_number'=>$val->bank_account_number,
                              'ifsc'=>$val->ifsc,
                              'store_name'=>$val->store_name,
                              'mobile'=>$val->mobile,
                              );
            }

            }else if($serviceID=="4"){
               
               if($reportType == "transactions"){
                  $comm=$transactions;
               }
               else if($reportType == "passbook"){
               	$comm=$transactions;
               }
               else{
                foreach($transactions as $val){
               $comall        =$this->commisionget_order_id($val->order_id);
               $wallet        =json_decode($comall,true);
               $bbps_response =json_decode($val->response_msg,true);

               $comm[]=array('name'=>$val->name,
                             'operator_name'=>$val->operator_name,
                             'mobileno'=>$val->mobileno,
                             'order_id'=>$val->order_id,
                             'api_id'=>$val->api_id,
                             'trans_date'=>$val->trans_date,
                             'rechargeAmt'=>$val->rechargeAmt,
                             'retailer_comm'=>$wallet['retailer_comm'],
                             'distributor_comm'=>$wallet['distributor_comm'],
                              'admin_comm'      =>$wallet['admin_comm'],
                              'operator_icon'=>$val->operator_icon,
                              'operator_id'=>$val->operator_id,
                              'store_name'=>$val->store_name,
                              'retailer_mobile'=>$val->retailer_mobile,
                              'billerName'=>$val->billerName,
                              'billerIcon'=>$val->billerIcon,
                              'inputparameter'=>$bbps_response['inputParams'],
                              'response_msg'=>$bbps_response,

                              );
            }
               }

             
            } 
            else if($serviceID=="6" or $serviceID==6){
                 foreach($transactions as $val){
            //   $comall        =$this->commisionget_order_id($val->order_id);
            //   $wallet        =json_decode($comall,true);
            //   $bbps_response =json_decode($val->response_msg,true);
                if($role_id=="4" or $role_id==4)
                {
               $comm[]=array('datetime'=>$val->trans_date,
                             'orderid'=>$val->order_id,
                             'transaction_id'=>$val->transaction_id,
                             'client_ref_id'=>$val->client_reference_id,
                             'rrn'=>$val->rrnno,
                             'mobileno'=>$val->mobileno,
                             'aadharno'=>$val->aadharnumber,
                             'bankname'=>$val->aeps_bank_id,
                             'amount'=>$val->total_amount,
                              'user_account_balance'      =>$val->aeps_balance,
                              'commission'=>$val->retailer_commision,
                              'status'=>$val->order_status,
                              'response'=>$val->response_msg,

                              );
                }
                else
                {
                     $comm[]=array('datetime'=>$val->trans_date,
                             'orderid'=>$val->order_id,
                             'transaction_id'=>$val->transaction_id,
                             'client_ref_id'=>$val->client_reference_id,
                             'rrn'=>$val->rrnno,
                             'mobileno'=>$val->mobileno,
                             'aadharno'=>$val->aadharnumber,
                             'bankname'=>$val->aeps_bank_id,
                             'amount'=>$val->total_amount,
                              'user_account_balance'      =>$val->aeps_balance,
                              'retailer_commision'=>$val->retailer_commision,
                              'distributor_commision'=>$val->distributor_commision,
                              'status'=>$val->order_status,
                              'response'=>$val->response_msg,

                              );
                }
            }
                
             
            }
            else if($serviceID==9){
                
                 foreach($transactions as $val){
            //   $comall        =$this->commisionget_order_id($val->order_id);
            //   $wallet        =json_decode($comall,true);
            //   $bbps_response =json_decode($val->response_msg,true);
                if($role_id==4 or $role_id=="4"){
               $comm[]=array('datetime'=>$val->trans_date,
                             'orderid'=>$val->order_id,
                             'transaction_id'=>$val->transaction_id,
                             'client_ref_id'=>$val->client_reference_id,
                             'rrn'=>$val->rrnno,
                             'mobileno'=>$val->mobileno,
                             'aadharno'=>$val->aadharnumber,
                             'bankname'=>$val->aeps_bank_id,
                             'amount'=>$val->total_amount,
                              'user_account_balance'=>$val->aeps_balance,
                              'retailer_commision'=>$val->retailer_commision,
                              'status'=>$val->order_status,
                              'response'=>$val->response_msg,

                              );
                }
                else
                {
                     $comm[]=array('datetime'=>$val->trans_date,
                             'orderid'=>$val->order_id,
                             'transaction_id'=>$val->transaction_id,
                             'client_ref_id'=>$val->client_reference_id,
                             'rrn'=>$val->rrnno,
                             'mobileno'=>$val->mobileno,
                             'aadharno'=>$val->aadharnumber,
                             'bankname'=>$val->aeps_bank_id,
                             'amount'=>$val->total_amount,
                              'user_account_balance'=>$val->aeps_balance,
                              'retailer_commision'=>$val->retailer_commision,
                              'distributor_commision'=>$val->distributor_commision,
                              'status'=>$val->order_status,
                              'response'=>$val->response_msg,

                             
                           

                              );
                }
              
            
                 }  
             
            }
            elseif($serviceID=="100") {
                // if(isset($data['from'])){
                //   $from = $data['from']; 
                // } else {
                //     $from = '';
                // }
                // $txn = $this->transactions_model->getAllTransactions($serviceID,$user_id,$reportType,'',$from,$to,$operator_id,$order_status,$mobileno,$role_id,$limit,$start);
                // print_r($txn);die();
                //PG Report
                
                foreach($transactions as $val){
    
                  $comm[]=array('id'=>$val->id,
                             'order_no'=>$val->order_id,
                            //  'api_id'=>"APi",
                            //  'operator_id'=>"Operator",
                             'trans_date'=>$val->trans_date,
                             'name'=>"CREDIT (".$val->payment_mode.")",
                             'sender_no'=>$val->transaction_id,
                             'mode'=>$val->payment_mode,
                             'amount'=>$val->total_amount,
                             'charge_amount'=>$val->charges,
                            //  'retailer_comm'=>"14",
                            //  'distributor_comm'=>"2",
                            //   'admin_comm'      =>"3",
                            //   'service_name'=>"",
                              'bank_transaction_id'=>$val->bank_trans_id,
                              'order_status'=>$val->transaction_status,
                            //   'remarks'=>$val->remarks,
                            //   'CCFcharges'=>"4",
                            //   'Cashback'=>"5",
                            //   'CCFcharges'=>"6",
                            //   'TDSamount'=>"7",
                            //   'PayableCharge'=>"8",
                              'FinalAmount'=>($val->total_amount - $val->charges),
                            //   'bank_account_number'=>"10",
                            //   'ifsc'=>"11",
                            //   'store_name'=>"12",
                            //   'mobile'=>"13",
                              );
                }
                
            } elseif($serviceID=="101") {
                // if(isset($data['from'])){
                //   $from = $data['from']; 
                // } else {
                //     $from = '';
                // }
                // $txn = $this->transactions_model->getAllTransactions($serviceID,$user_id,$reportType,'',$from,$to,$operator_id,$order_status,$mobileno,$role_id,$limit,$start);
                // print_r($txn);die();
                //VA Report
                
                foreach($transactions as $val){
    
                  $comm[]=array('id'=>$val->id,
                             'order_no'=>$val->order_id,
                             'trans_date'=>$val->trans_date,
                             'name'=>$val->transaction_type,
                            //  'name'=>$val->transaction_type . " (".$val->payment_type.")",
                             'sender_no'=>$val->transaction_id,
                             'mode'=>$val->payment_type,
                             'amount'=>$val->total_amount,
                             'charge_amount'=>$val->charge_amount,
                              'bank_transaction_id'=>$val->bank_trans_id,
                              'order_status'=>$val->transaction_status,
                          );
                }
                
            } elseif($serviceID=="102") {
                // if(isset($data['from'])){
                //   $from = $data['from']; 
                // } else {
                //     $from = '';
                // }
                // $txn = $this->transactions_model->getAllTransactions($serviceID,$user_id,$reportType,'',$from,$to,$operator_id,$order_status,$mobileno,$role_id,$limit,$start);
                // print_r($txn);die();
                //VA Report
                
                foreach($transactions as $val){
    
                  $comm[]=array('id'=>$val->id,
                             'order_no'=>$val->order_id,
                             'trans_date'=>$val->trans_date,
                            //  'name'=>$val->transaction_type . " (".$val->payment_type.")",
                             'name'=>$val->transaction_type,
                             'sender_no'=>$val->transaction_id,
                             'mode'=>$val->payment_type,
                             'amount'=>$val->total_amount,
                             'charge_amount'=>$val->charge_amount,
                              'bank_transaction_id'=>$val->bank_trans_id,
                              'order_status'=>$val->transaction_status,
                              );
                }
                
            }
            else{
            	 if($reportType == "transactions"){
                  $comm=$transactions;
               }
               else if($reportType == "passbook"){
               	$comm=$transactions;
               }
               else{
                foreach($transactions as $val){
               $comall=$this->commisionget_order_id($val->order_id);
               $wallet=json_decode($comall,true);
       

               $comm[]=array('name'=>$val->name,
                             'operator_name'=>$val->operator_name,
                             'mobileno'=>$val->mobileno,
                             'order_id'=>$val->order_id,
                             'api_id'=>$val->api_id,
                             'trans_date'=>$val->trans_date,
                             'rechargeAmt'=>$val->rechargeAmt,
                             'retailer_comm'=>$wallet['retailer_comm'],
                             'distributor_comm'=>$wallet['distributor_comm'],
                              'admin_comm'      =>$wallet['admin_comm'],
                              'operator_icon'=>$val->operator_icon,
                              'operator_id'=>$val->operator_id,
                              'store_name'=>$val->store_name,
                              'retailer_mobile'=>$val->retailer_mobile,
                              );
               
                
            }
            }
            }
     
            if ($transactions) {
                $response = array(
                    'status' => "true",
                    'msg' => "All Transactions",
                    'result' => $comm
                );
            }else{
                $response = array(
                    'status' => "false",
                    'msg' => "no data found",
                    'result' => null
                );
            }
        }
        else if(empty($data['serviceID'])){

            if(isset($data['reportType'])){
               $reportType = $data['reportType']; 
            }
            if(isset($data['from'])){
               $from = $data['from']; 
            }else { $from=date('Y-m-d');}
            if(isset($data['to'])){
               $to = $data['to']; 
            }else { $to="";}
            if(isset($data['operator_id'])){
               $operator_id = $data['operator_id']; 
            }else { $operator_id="";}
            if(!empty($data['limit'])){
                $limit=$data['limit'];
            }else{ $limit=''; }
            if(!empty($data['start'])){
                $start=$data['start'];
            }else{ $start='0'; }
            if(isset($data['role_id'])){
               $role_id = $data['role_id']; 
            }else { $role_id="";}
            $transactions = $this->transactions_model->getAllTransactions_withoutserviceeid($user_id,$reportType,$from,$to,$operator_id,$limit,$start,$role_id);
            // foreach($transactions as $val){
            //    $comall=$this->commisionget_order_id($val->order_id);
            //    $wallet=json_decode($comall,true);
         //    	$wallet=array('retailer_comm'=>$retailer_comm,
        	// 'distributor_comm'=>$distributor_comm,
        	// 'admin_comm'      =>$admin_comm,
        	// );

            //    $comm[]=array('name'=>$val->name,
            //    	             'operator_name'=>$val->operator_name,
            //    	             'mobileno'=>$val->mobileno,
            //    	             'order_id'=>$val->order_id,
            //    	             'trans_date'=>$val->trans_date,
            //    	             'rechargeAmt'=>$val->rechargeAmt,
            //    	             'retailer_comm'=>$wallet['retailer_comm'],
        	//                  'distributor_comm'=>$wallet['distributor_comm'],
        	//                   'admin_comm'      =>$wallet['admin_comm'],
        	//                   'operator_icon'=>$val->operator_icon,
        	//                   'operator_id'=>$val->operator_id,
            //                   'store_name'=>$val->store_name,
            //                   'retailer_mobile'=>$val->retailer_mobile,
        	//                   );
            // }
            if ($transactions) {
                $response = array(
                    'status' => "true",
                    'msg' => "All Transactions",
                    // 'result' => $comm
                    'result' => $transactions
                );
            }else{
                $response = array(
                    'status' => "false",
                    'msg' => "no data found",
                    'result' => null
                );
            }
        }
        else{
            $response = array(
                'status' => "false",
                'msg' => "Please provide required inputs",
                'result' => null
            );
        }
        echo json_encode($response);
        exit;
    }
    public function commisionget_order_id($orderid=''){
    	//$orderid='SP5529';
        $walcomm=$this->transactions_model->commisionget_byorderid($orderid);
        // echo "<pre>";
        // print_r($walcomm);
        $retailer_comm='0';
        $distributor_comm='0';
        $admin_comm='0';
        if(!empty($walcomm)){
        	foreach($walcomm as $val){
				   $role = $val->roleid;
                   if($role==1){
                   	$admin_comm=$val->total_amount;
                   }
                   else if($role==2){
                   	$distributor_comm=$val->total_amount;
                   }else if($role==4){
                   	$retailer_comm=$val->total_amount;
                   }
				
            }

        }
        $wallet=array('retailer_comm'=>$retailer_comm,
        	'distributor_comm'=>$distributor_comm,
        	'admin_comm'      =>$admin_comm,
        	);
        return json_encode($wallet);
        
    }
    public function getOneTransctionByUser() {
     $data =  json_decode(file_get_contents('php://input'), true);
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
        if (!empty($data['serviceID']) && !empty($data['id'])){ //for recharge it is //Recharge
            $serviceID = $data['serviceID'];
            $reportType = "transactions";
            if(isset($data['reportType'])){
               $reportType = $data['reportType']; 
            }
            $id = $data['id'];
            $transactions = $this->transactions_model->getAllTransactions($serviceID,$user_id,$reportType,$id);
            if ($transactions) {
                $response = array(
                    'status' => "true",
                    'msg' => "One Transactions",
                    'result' => $transactions
                );
            }else{
                $response = array(
                    'status' => "false",
                    'msg' => "no data found",
                    'result' => null
                );
            }
        }
        
        else{
            $response = array(
                'status' => "false",
                'msg' => "Please provide required inputs",
                'result' => null
            );
        }
        echo json_encode($response);
        exit;
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
  
  /*public function getPaytmTransactionDetailsApi() {
    $data =  json_decode(file_get_contents('php://input'), true);
    $this->authenticateUser($data);         
    if(!empty($data['amount'])){
        $this->db->select('*');
        $this->db->from('tbl_payment_gateway_integation');
        $this->db->where('id', "1");
        $query = $this->db->get();
        $payment_dtls = $query->row();
        if($payment_dtls->environment == "testing"){
            //staging url
            $url = "https://securegw-stage.paytm.in/theia/api/v1/showPaymentPage";
        }else{
            //production url
            $url = "https://securegw.paytm.in/theia/api/v1/showPaymentPage";
        }
        $last_txn_id = file_get_contents("admin/txn_order_id.txt");              
        $orderId = intval($last_txn_id)+1;
        $this->writeTxnOrderID($orderId);
        $result = array(
          'merchant_id' => $payment_dtls->merchant_id,
          'paytm_txn_start_url' => $url."",
          'orderId' => $orderId."",
          'callbackurl' => base_url()."transactions/paytm_transaction_status/".$orderId
        );
        $response = array(
            'status' => "true",
            'msg' => "Success",
            'result' => $result
        );
    }else{
      $response = array(
        'status' => "false",
        'msg' => "Invalid Request",
        'result' => null
      );
    }
    echo json_encode($response);
    exit;
  }*/

  public function paytm_transaction_status($orderID="") {
    $this->db->select('*');
    $this->db->from('tbl_payment_gateway_integation');
    $this->db->where('id', "1");
    $query = $this->db->get();
    $payment_dtls = $query->row();
    $mid = $payment_dtls->merchant_id;//"XtrSQa90965375513903";//"ASJCcA23692100733906";
    $merchantKey = $payment_dtls->working_key;//"Cw7UhxxDlP3x#IFf";//"9UtzE2DUArQnP5fy";
    $env = $payment_dtls->environment;
    $currency = $payment_dtls->currency;

    $paytmParams = array();
    $paytmParams["MID"]     = $mid;
    $paytmParams["ORDERID"] = $orderID;
    /*
    * Generate checksum by parameters we have
    * Find your Merchant Key in your Paytm Dashboard at https://dashboard.paytm.com/next/apikeys 
    */
    $checksum = PaytmChecksum::generateSignature($paytmParams, $merchantKey);
    $paytmParams["CHECKSUMHASH"] = $checksum;

    $post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);
    if($env == "testing"){
        /* for Staging */
        $url = "https://securegw-stage.paytm.in/order/status";
    }else{
        /* for Production */
        $url = "https://securegw.paytm.in/order/status";
    }

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));  
    $response = curl_exec($ch);
    print_r($response);
  }

  public function getPaytmTransactionApiGatewayDetails() {
    $data =  json_decode(file_get_contents('php://input'), true);
    $this->authenticateUser($data);         
    if(!empty($data['amount'])){
        $user_id = $data['user_id'];
        $amount = $data['amount'];

        $last_txn_id = file_get_contents("admin/txn_order_id.txt");              
        $order_id = intval($last_txn_id)+1;
        $this->writeTxnOrderID($order_id);

        $this->db->select('*');
        $this->db->from('tbl_payment_gateway_integation');
        $this->db->where('id', "1");
        $query = $this->db->get();
        $payment_dtls = $query->row();
        $mid = $payment_dtls->merchant_id;//"XtrSQa90965375513903";//"ASJCcA23692100733906";
        $merchantKey = $payment_dtls->working_key;//"Cw7UhxxDlP3x#IFf";//"9UtzE2DUArQnP5fy";
        $env = $payment_dtls->environment;
        $currency = $payment_dtls->currency;

        $paytmParams = array();
        if($env == "testing"){
            //staging
            $callback_url = "https://securegw-stage.paytm.in/theia/paytmCallback?ORDER_ID=".$order_id."";
        }else{
            //production
            $callback_url = "https://securegw.paytm.in/theia/paytmCallback?ORDER_ID=".$order_id."";
        }
        $paytmParams["body"] = array(
            "requestType"   => "Payment",
            "mid"           => $mid,
            "websiteName"   => "WEBSTAGING",
            "orderId"       => $order_id,
            "callbackUrl"   => $callback_url,
            "txnAmount"     => array(
                "value"     => $amount,
                "currency"  => $currency,
            ),
            "userInfo"      => array(
                "custId"    => $user_id,
            ),
        );
        /*
        * Generate checksum by parameters we have in body
        * Find your Merchant Key in your Paytm Dashboard at https://dashboard.paytm.com/next/apikeys 
        */
        $checksum = PaytmChecksum::generateSignature(json_encode($paytmParams["body"], JSON_UNESCAPED_SLASHES), 
            $merchantKey);
        $paytmParams["head"] = array(
            "signature" => $checksum
        );

        $post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);
        //echo $post_data;
        //die;
        //if($env == "testing"){
            /* for Staging */
            $url = "https://securegw-stage.paytm.in/theia/api/v1/initiateTransaction?mid=".$mid."&orderId=".$order_id;
       // }else{
            /* for Production */
            //$url = "https://securegw.paytm.in/theia/api/v1/initiateTransaction?mid=".$mid."&orderId=".$order_id;
        //}

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json")); 
        $response1 = curl_exec($ch);
        //$response2 = json_decode($response1,true);
        //print_r($response);
        if($payment_dtls->environment == "testing"){
            //staging url
            $paymenturl = "https://securegw-stage.paytm.in/theia/api/v1/showPaymentPage";
        }else{
            //production url
            $paymenturl = "https://securegw.paytm.in/theia/api/v1/showPaymentPage";
        }
        $result = array(
            'orderId' => $order_id,
            'merchant_id' => $mid,
            'callbackurl' => base_url()."transactions/paytm_transaction_status/".$order_id,
            'paytm_txn_start_url' => $paymenturl."",
            'response' => $response1.""
        );
        $response = array(
            'status' => "true",
            'msg' => "Success",
            'result' => $result
        );
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
  public function getPaytmTransactionApiGatewayDetails1() {
    $data =  json_decode(file_get_contents('php://input'), true);
    $this->authenticateUser($data);         
    if(!empty($data['amount'])){
        $user_id = $data['user_id'];
        $amount = $data['amount'];

        $last_txn_id = file_get_contents("admin/txn_order_id.txt");              
        $order_id = intval($last_txn_id)+1;
        $this->writeTxnOrderID($order_id);

        $this->db->select('*');
        $this->db->from('tbl_payment_gateway_integation');
        $this->db->where('id', "1");
        $query = $this->db->get();
        $payment_dtls = $query->row();
        $mid = $payment_dtls->merchant_id;//"XtrSQa90965375513903";//"ASJCcA23692100733906";
        $merchantKey = $payment_dtls->working_key;//"Cw7UhxxDlP3x#IFf";//"9UtzE2DUArQnP5fy";
        $env = $payment_dtls->environment;
        $currency = $payment_dtls->currency;

        $paytmParams = array();
        if($env == "testing"){
            //staging
            $callback_url = "https://securegw-stage.paytm.in/theia/paytmCallback?ORDER_ID=".$order_id."";
        }else{
            //production
            $callback_url = "https://securegw.paytm.in/theia/paytmCallback?ORDER_ID=".$order_id."";
        }
        $txntoken='aeeea63d3902413cbd3cfe00658c5a6a1602941245202';
      
      $payment_type='UPI_INTENT';
      $paytmParams = array();
      $paytmParams["body"] = array(
      "requestType" => "NATIVE",
      "mid"         => $mid,
      "orderId"     => $order_id,
      "paymentMode" =>$payment_type,
    //"authMode"    => "otp"
    
     );
        // $paytmParams["body"] = array(
        //     "requestType"   => "Payment",
        //     "mid"           => $mid,
        //     "websiteName"   => "WEBSTAGING",
        //     "orderId"       => $order_id,
        //     "callbackUrl"   => $callback_url,
        //     "txnAmount"     => array(
        //         "value"     => $amount,
        //         "currency"  => $currency,
        //     ),
        //     "userInfo"      => array(
        //         "custId"    => $user_id,
        //     ),
        // );
        /*
        * Generate checksum by parameters we have in body
        * Find your Merchant Key in your Paytm Dashboard at https://dashboard.paytm.com/next/apikeys 
        */
        $checksum = PaytmChecksum::generateSignature(json_encode($paytmParams["body"], JSON_UNESCAPED_SLASHES), 
            $merchantKey);
        $paytmParams["head"] = array(
            "signature" => $checksum
        );

        $post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);
        //echo $post_data;
        //die;
        //if($env == "testing"){
            /* for Staging */
            $url = "https://securegw-stage.paytm.in/theia/api/v1/initiateTransaction?mid=".$mid."&orderId=".$order_id;
       // }else{
            /* for Production */
            //$url = "https://securegw.paytm.in/theia/api/v1/initiateTransaction?mid=".$mid."&orderId=".$order_id;
        //}

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json")); 
        $response1 = curl_exec($ch);
        $response2 = json_decode($response1,true);
        print_r($response1);

        if($payment_dtls->environment == "testing"){
            //staging url
            $paymenturl = "https://securegw-stage.paytm.in/theia/api/v1/showPaymentPage";
        }else{
            //production url
            $paymenturl = "https://securegw.paytm.in/theia/api/v1/showPaymentPage";
        }
        $result = array(
            'orderId' => $order_id,
            'merchant_id' => $mid,
            'callbackurl' => base_url()."transactions/paytm_transaction_status/".$order_id,
            'paytm_txn_start_url' => $paymenturl."",
            'response' => $response1.""
        );
        $response = array(
            'status' => "true",
            'msg' => "Success",
            'result' => $result
        );
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
 public function getupiPaytmTransactionApiGatewayDetails() {
    $data =  json_decode(file_get_contents('php://input'), true);
    //print_r($data);
    $this->authenticateUser($data);         
    if(!empty($data['amount'])){
        $user_id            = $data['user_id'];
        $amount             = $data['amount'];
        //$payment_type       =$data['payment_type'];
        $last_txn_id = file_get_contents("admin/txn_order_id.txt");              
        $order_id = intval($last_txn_id)+1;
        $this->writeTxnOrderID($order_id);

        $this->db->select('*');
        $this->db->from('tbl_payment_gateway_integation');
        $this->db->where('id', "1");
        $query = $this->db->get();
        $payment_dtls = $query->row();
        $mid = $payment_dtls->merchant_id;
        $merchantKey = $payment_dtls->working_key;
        $env = $payment_dtls->environment;
        $currency = $payment_dtls->currency;
        //$order_id1="OREDRID_".$order_id-1;
       // $url = "https://securegw-stage.paytm.in/theia/api/v1/processTransaction?mid=YOUR_MID_HERE&orderId=ORDERID_98765";
         $url = "https://securegw-stage.paytm.in/theia/api/v1/processTransaction?mid=".$mid."&orderId=".$order_id;
        //$url = "https://securegw.paytm.in/theia/api/v1/processTransaction?mid=".$mid."&orderId=".$order_id1;
       
      $txntoken='912ee285913a4c75af02f2aed856d5a91602940327866';
      
      $payment_type='UPI_INTENT';
      $paytmParams = array();
      $paytmParams["body"] = array(
      "requestType" => "NATIVE",
      "mid"         => $mid,
      "orderId"     => $order_id,
      "paymentMode" =>$payment_type,
    //"authMode"    => "otp"
    
     );
//print_r($paytmParams["body"]);
     $paytmParams["head"] = array(
    "version"=>1,
    "requestTimestamp"=>'1588402269',
    "channelId"=>"WAP",
    "txnToken"    => $txntoken
    );

    $post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);



$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json")); 
$response = curl_exec($ch);
print_r($response);
exit();
//return $response;
        // $ch = curl_init($url);
        // curl_setopt($ch, CURLOPT_POST, 1);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        // curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json")); 
        // $response1 = curl_exec($ch);
        // $response2 = json_decode($response1,true);
        // //print_r($response1);
        // $txntoken=$response2['body']['txnToken'];
        // $payresponse=$this->transprocess($txntoken,$mid,$order_id,$payment_type);
        // print_r($payresponse);
        // if($payment_dtls->environment == "testing"){
        //     //staging url
        //     $paymenturl = "https://securegw-stage.paytm.in/theia/api/v1/showPaymentPage";
        // }else{
        //     //production url
        //     $paymenturl = "https://securegw.paytm.in/theia/api/v1/showPaymentPage";
        // }
        $result = array(
            'orderId' => $order_id,
            'merchant_id' => $mid,
            'callbackurl' => base_url()."transactions/paytm_transaction_status/".$order_id,
            'paytm_txn_start_url' => $paymenturl."",
            'response' => $response1.""
        );
        $response = array(
            'status' => "true",
            'msg' => "Success",
            'result' => $result
        );
    }else{
      $response = array(
        'status' => "false",
        'msg' => "Invalid Request",
        'result' => null
      );
    }
    echo json_encode($response);
    //exit;    
  }
  public function test(){
    $userlist=$this->db->query('SELECT userId  FROM `tbl_users` WHERE `parent_user_id` ="15"')->result();
    foreach($userlist as $up){
        $test[]=$up->userId;
    }
    echo "<pre>";
    //print_r($test);
    echo implode(',',$test);
  //print_r($userlist);
  exit();
  	$json1 = '{"g":7, "e":5, "e":5, "k":11, "s":19}'; 
  $json='{
"0": "Mobile Number"
}';

// Use json_decode() function to 
// decode a string 
    $ss=json_decode($json);
    $ss1=json_decode($json1);
   //echo $ss[0]; 
    echo "<pre>";
     print_r($ss);
     print_r($ss1);
     echo $ss1->g;
     echo $ss->{0};
    $client_id='SP3888';
    $test=$this->transactions_model->check_order_id($client_id);
    print_r($test);
if(empty($test)){
    echo "hi";
}
else{
    echo "there";
}
    exit();
    $paytmParams = array();

$paytmParams["body"] = array(
    "requestType" => "NATIVE",
    "mid"         => "YOUR_MID_HERE",
    "orderId"     => "ORDERID_98765",
    "paymentMode" => "CREDIT_CARD",
    "cardInfo"    => "|4111111111111111|111|122032",
    "authMode"    => "otp",
);

$paytmParams["head"] = array(
    "txnToken"    => "f0bed899539742309eebd8XXXX7edcf61588842333227"
);

$post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);

/* for Staging */
$url = "https://securegw-stage.paytm.in/theia/api/v1/processTransaction?mid=YOUR_MID_HERE&orderId=ORDERID_98765";

/* for Production */
// $url = "https://securegw.paytm.in/theia/api/v1/processTransaction?mid=YOUR_MID_HERE&orderId=ORDERID_98765";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json")); 
$response = curl_exec($ch);
print_r($response);

//     $curl = curl_init();

// curl_setopt_array($curl, array(
//   CURLOPT_PORT => "25004",
//   CURLOPT_URL => "https://staging.eko.in:25004/ekoapi/v2/transactions",
//   CURLOPT_RETURNTRANSFER => true,
//   CURLOPT_ENCODING => "",
//   CURLOPT_MAXREDIRS => 10,
//   CURLOPT_TIMEOUT => 30,
//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//   CURLOPT_CUSTOMREQUEST => "POST",
//   CURLOPT_POSTFIELDS => "recipient_id=10012510&amount=5000&timestamp=1990-01-01T01:01:01Z&currency=INR&customer_id=9999912345&initiator_id=9962981729&client_ref_id=12345678901234567890&state=1&channel=2&latlong=26.45427547,79.0747037,818&user_code=20110035",
//   CURLOPT_HTTPHEADER => array(
//     "developer_key: becbbce45f79c6f5109f848acd540567",
//     "secret-key: MC6dKW278tBef+AuqL/5rW2K3WgOegF0ZHLW/FriZQw=",
//     "secret-key-timestamp: 1516705204593"
//   ),
// ));

// $response = curl_exec($curl);
// $err = curl_error($curl);

// curl_close($curl);

// if ($err) {
//   echo "cURL Error #:" . $err;
// } else {
//   echo $response;
// }

// //print_r($response);
// exit();
    



  }
  public function transprocess(){
    $txntoken='e5eb16fcec4541238516d6431f5a2cad1602935721580';
    $mid='ASJCcA23692100733906';
    $order_id='OREDRID_987';
    $payment_type='UPI_INTENT';
$paytmParams = array();

$paytmParams["body"] = array(
    "requestType" => "NATIVE",
    "mid"         => $mid,
    "orderId"     => $order_id,
    "paymentMode" =>$payment_type,
    //"authMode"    => "otp"
    
);

$paytmParams["head"] = array(
    "txnToken"    => $txntoken
);

$post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);

/* for Staging */
// echo $url = "https://securegw-stage.paytm.in/theia/api/v1/processTransaction?mid=".$mid."&orderId=".$order_id."";

/* for Production */
 echo $url = "https://securegw.paytm.in/theia/api/v1/processTransaction?mid=".$mid."&orderId=".$order_id."";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json")); 
$response = curl_exec($ch);
print_r($response);
//return $response;
  }
  public function writeTxnOrderID($order_id){
    write_file('admin/txn_order_id.txt', $order_id."");
  }

  public function paytm_moneytrans_status(){
    $data =  json_decode(file_get_contents('php://input'), true);
    $data_store = json_encode($data);
    write_file('admin/patm_status_response.txt', $data_store);

    $order_id= $data['result']['orderId'];
    $order_status = $data['status'];

    $webhook_insert = array( 'order_id'=> $order_id, 'response'=> json_encode($data), 'api_name'=> 'Paytm' );
    $this->db->insert('tbl_webhook_log', $webhook_insert);

    
    $check_order = $this->db->select('*')->from('tbl_transaction_dtls')->where('order_id', $order_id)->get()->row();

    if ($check_order) {
       
        if( ($order_status == 'SUCCESS') || ($order_status == 'PENDING') ){

            $update_data = array(   'transaction_status'=>$order_status,
                                    'bank_transaction_id'=>$data['result']['rrn'],
                                    'transaction_msg' => $data['statusMessage'],
                                    'order_status'=>$order_status,
                                    'updated_on'=> date('Y-m-d H:i:s'),
                                );

            $this->db->where('order_id',  $order_id)
                        ->update('tbl_transaction_dtls', $update_data);
        }else {
                $trans_record =$this->moneyTransferApi_model->getdata_where('tbl_transaction_dtls',array('order_id'=> $order_id ));
                $trans_info['service_id']=$trans_record[0]->service_id;
                $trans_info['order_id']=$trans_record[0]->order_id;
                $trans_info['user_id']=$trans_record[0]->user_id;
                $trans_info['operator_id']=$trans_record[0]->operator_id;
                $trans_info['api_id']=$trans_record[0]->api_id;
                $trans_info['FinalAmount']=$trans_record[0]->FinalAmount;
                $trans_info['mobileno']=$trans_record[0]->mobileno;
                $trans_info['PayableCharge']=$trans_record[0]->PayableCharge;
                $failed_resp = $this->failedPaytmTransfer($trans_info, ($order_status == 'FAILURE') ? 'FAILED' : $order_status);
        }


    }
    
    if($check_order->transaction_status != $order_status) {
        $this->rechargeApi_model->send_telegram_api($order_id);
    }

  }
  
  public function hypto_trans_status(){
    $data = $_POST;
    $data_store = json_encode($_POST);
    write_file('admin/hypto_status_response.txt', json_encode($_POST));

    $order_id = $data['reference_number'];
    $order_status = $data['status'];

    $webhook_insert = array( 'order_id'=> $order_id, 'response'=> json_encode($data), 'api_name'=> 'Hypto' );
    $this->db->insert('tbl_webhook_log', $webhook_insert);

    
    $check_order = $this->db->select('*')->from('tbl_transaction_dtls')->where('order_id', $order_id)->get()->row();

    if ($check_order) {
       
        if( ($order_status == 'COMPLETED')){

            $update_data = array(   'transaction_status'=>$order_status,
                                    'bank_transaction_id'=>$data['bank_ref_num'],
                                    'transaction_msg' => $data['status'],
                                    'order_status'=>'SUCCESS',
                                    'updated_on'=> date('Y-m-d H:i:s'),
                                );

            $this->db->where('order_id',  $order_id)
                        ->update('tbl_transaction_dtls', $update_data);
        }else {
                $trans_record =$this->moneyTransferApi_model->getdata_where('tbl_transaction_dtls',array('order_id'=> $order_id ));
                $trans_info['service_id']=$trans_record[0]->service_id;
                $trans_info['order_id']=$trans_record[0]->order_id;
                $trans_info['user_id']=$trans_record[0]->user_id;
                $trans_info['operator_id']=$trans_record[0]->operator_id;
                $trans_info['api_id']=$trans_record[0]->api_id;
                $trans_info['FinalAmount']=$trans_record[0]->FinalAmount;
                $trans_info['mobileno']=$trans_record[0]->mobileno;
                $trans_info['PayableCharge']=$trans_record[0]->PayableCharge;
                $failed_resp = $this->failedPaytmTransfer($trans_info, $order_status);
        }
        if($check_order->transaction_status != $order_status) {
            $this->rechargeApi_model->send_telegram_api($order_id);
        }

    }

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
        'payment_mode' => "PAID FOR DMT,ACCOUNT NUMBER ".$trans_info['mobileno'].",AMOUNT ".$trans_info['FinalAmount'].",CHARGE ".$trans_info['PayableCharge'],
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
  
  public function recharge_webhook() {
    $result = $_GET;
    write_file('admin/ashish.txt', json_encode($_GET));
    $trid = $result['rpid'];
    $clientId = $result['agentid'];
    $mobile = $result['mobile'];
    $amount = $result['amount'];
    $status = $result['status'];
    $message = $result['msg'];
    
    $webhook_insert = array( 'order_id'=> $clientId, 'response'=> json_encode($result), 'api_name'=> 'Ambika/Techno' );
    $this->db->insert('tbl_webhook_log', $webhook_insert);
    
    if($result['status'] == 1) {
        $status = "PENDING";
    } elseif($result['status'] == 2) {
        $status = "SUCCESS";
    } elseif($result['status'] == 3) {
        $status = "FAILED";
    }
    
    $check_order = $this->db->select('*')->from('tbl_transaction_dtls')->where('order_id', $clientId)->get()->row();

    if(!$check_order) {
        exit;
    }
    if($check_order->transaction_status != $order_status) {
        $this->rechargeApi_model->send_telegram_api($order_id);
    }

    $url = "https://paymamaapp.in/api/change_txn_status";
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
    $headers = array(
        "Content-Type: application/json",
    );
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    $data = array(
        "order_id"=>$clientId,
        "transaction_status"=>$status
    );
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $resp = curl_exec($curl);
    curl_close($curl);
  }
  
  public function samriddhipay() {
      $result = $_GET;
      write_file('admin/ashish1.txt', json_encode($_GET));
      $clientId = $result['userRcId'];
      $status = $result['status'];
      if($status == "Success") {
          $status = "SUCCESS";
      } elseif($status == "Pending") {
          $status = "PENDING";
      } elseif($status == "Failure"){
          $status = "FAILED";
      }
      $check_order = $this->db->select('*')->from('tbl_transaction_dtls')->where('order_id', $clientId)->get()->row();
      
      if(!$check_order) {
          exit;
      }
      if($check_order->transaction_status != $order_status) {
          $this->rechargeApi_model->send_telegram_api($order_id);
      }
      $url = "https://paymamaapp.in/api/change_txn_status";
      $curl = curl_init($url);
      curl_setopt($curl, CURLOPT_URL, $url);
      curl_setopt($curl, CURLOPT_POST, true);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
    $headers = array(
        "Content-Type: application/json",
    );
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    $data = array(
      "order_id"=>$clientId,
      "transaction_status"=>$status
    );
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    
    $resp = curl_exec($curl);
    curl_close($curl);
  }
}

?>