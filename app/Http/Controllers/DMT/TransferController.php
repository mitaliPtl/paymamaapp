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
use App\PackageCommissionDetail;
use App\TDS;
use PDF;
use Storage;
use File;
use App\ApiLogDetail;
use App\WalletTransactionDetail;


class TransferController extends Controller
{
   //Add Beneficiary
   public function transferdmtmoney(Request $request,$id,$senderid)
   {
              
        //Fetch Bank List
        $data['bankList'] = $this->getAllBankList();
        $data['bank_ifsc']=[];
        foreach ($data['bankList']['result']['bank_list'] as $key => $value) {
            $data['bank_ifsc'][$value['bank_code']] = $value['ifsc_prefix'];
        }
        
        $receipientDtls = DB::table('tbl_dmt_benificiary_dtls_new')->where('recipient_id',$id)->get()->first();
        $senderid = DMTSender::where('id',$senderid)->first();
        $sender_mobile_number=$senderid->sender_mobile_number;
        return view('modules.DMT.transfermoney', compact('data','request','receipientDtls','sender_mobile_number'));
   }
   
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
    
    public function DoDmtTransaction(Request $request)
    {
      
        $recipient_id=$request->benificiary;
        $transaction_type="IMPS";
        $amount=$request->transfer_amount;
        $sender_mobile_no=$request->sender_mobile_number;
        $account_no=$request->account_no;
        $ifsc_code=$request->ifsc_code;
        $account_holder_name=$request->account_holder_name;
        
        #Fetch Receipient Information
        $receipt_details=DMTSenderBeneficaryList::where('recipient_id',$recipient_id)->where('is_deleted',0)->first();
        
        #Check Which Api is Active According to transaction type
        $operator_id = OperatorSetting::where('operator_name','DMT')->pluck('default_api_id')->first();
        $apiis=$operator_id;
      
        $api_details = ApiSetting::where('api_id',$apiis)->first();
      
      
        if(!$api_details){
        $response = array(
          'status' => "false",
          'msg' => "API details not configured for operator.Please contact administrator.",
          'result' => null
        );
        
        // Check Duplicate Transaction Starts
           $min="5";
           $cDate=date('Y-m-d H:i:s');
           $newtimestamp = strtotime($cDate.' - '.$min.' minute');
           $cDate1= date('Y-m-d H:i:s', $newtimestamp);
            //$che="SELECT * from tbl_transaction_dtls WHERE  trans_date >= '".$cDate1."' AND trans_date < '".$cDate."' AND bank_account_no='".$accountNumber."'";
            $check=TransactionDetail::where([
            ['trans_date', '>=', $cDate1],
            ['trans_date', '<', $cDate],
            ['recipient_id', '=', $recipient_id],['request_amount', '=', $amount]])->count();
 
 
           if($check > 0){
           $response = array(
                'status' => "false",
                'msg' => "Same receipt and amount just now hit one Trasaction so Try again after 5 minute",
                'result' =>""
            );
            if(isset($request->user_id))
            {
                $statusMsg = "Same receipt and amount just now hit one Trasaction so Try again after 5 minute";
                $success = "false";
                return $this->sendSuccess($success, $statusMsg);
            }
        
           
        
        $msg="Duplicate Transaction";
		return redirect()->back()->with('merror', $response['message'] ?? "Duplicate Transaction")->with('messages', $msg);
        exit;
        }
        // Check Duplicate Transaction Ends
        }
        
        //Checking Retailer Balance Eligible for Payment
        $userbalance = User::where('userId', Auth::user()->userId)->first();
        $user_id=Auth::user()->userId;
        $userID=$user_id;
        if($userbalance)
        {
            
            $wallet_balance = $userbalance->wallet_balance;
            $min_balance = $userbalance->min_balance;
            $user_package_id = $userbalance->package_id;
            
            #Checking Commission from package detail table
            $commissiondet=PackageCommissionDetail::where('from_range','<',$amount)
            ->where('to_range','>',$amount)
            ->where('service_id','=',5)
            ->where('pkg_id','=',$user_package_id)
            ->where('operator_id','=',54)
            ->first();
            
            $ccf=$commissiondet->ccf_commission;
            $charge = $commissiondet->retailer_commission;
            $cashback=$ccf-$charge;
            
            #Get TDS Detail from Table Application Details
            // $app    =TDS::where('id',7)->first();
            // $TDS     =$cashback*($app->value/100);
            // $PayableCharge = $charge+$TDS;
            // $totalAmount=$amount+$PayableCharge;
            if(is_numeric($wallet_balance) && is_numeric($min_balance) && is_numeric($amount) && $wallet_balance-$amount < $min_balance){
             
            $response = array(
                  'status' => "false",
                  'msg' => "Insufficient balance",
                  'result' => null
            );
            return $response;
            exit;
            }
        }
        else if(!is_numeric($wallet_balance) || !is_numeric($min_balance) || !is_numeric($totalAmount)){
            $response = array(
              'status' => "false",
              'msg' => "Invalid amount details.",
              'result' => null
            );
         return $response;
        }
        else{//get all commission details by package id
       
          $commissionDtl = $this->getCommissionDetails($user_package_id,$service_id,$operator_id,$amount);      
          
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
            }
            else if($commissionDtl->commission_type == "Range"){
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
            else
            {
                $response = array(
                'status' => "false",
                'msg' => "Error while retriving balance",
                'result' => null
                );
                return $response;
            }
            
            #Validating Mpin
            //validate mpin begin
              if(isset($request->mpin) && !empty($request->mpin)){
                if($userbalance->mpin != $request->mpin)
                {
                if(!$userDtls){
                  $response = array(
                    'status' => "false",
                    'msg' => "Invalid MPIN",
                    'result' => null
                  );
                return $response;
                }
              }else{
                $response = array(
                  'status' => "false",
                  'msg' => "Invalid MPIN",
                  'result' => null
                );
               return $response;
              }
              //validate mpin end
          }
        
        //Ends Here
        
    
    }
    
   
} 
        
        //Get commission Details
         $commissionDtl=PackageCommissionDetail::where('from_range','<',$amount)
            ->where('to_range','>',$amount)
            ->where('service_id','=',5)
            ->where('pkg_id','=',$user_package_id)
            ->where('operator_id','=',54)
            ->first();
            
            $ccf=$commissionDtl->ccf_commission;
            
        
        //Get Order ID from JSon File
        $order_uni=$this->get_order_id();
       
        $sno_order_id=$order_uni;
        $order_id=$order_uni;
        
        //$sno_order_id="JB10001";
        //$order_id="JB10002";
        
        
        //Ends Here
        if($commissionDtl->retailer_commission_type == 'Percent'){
          $charge = $amount * ($commissionDtl->retailer_commission/100);
        }elseif($commissionDtl->retailer_commission_type == 'Rupees'){
          $charge = $commissionDtl->retailer_commission;
        }
        // $charge = $commissionDtl->retailer_commission;
        $cashback=$ccf-$charge;
       // $app    =$this->rechargeApi_model->getTDS();
       // $TDS     =$cashback*($app->value/100);
        $TDS=0;
        $PayableCharge = $charge+$TDS;
        $totalAmount=$amount+$PayableCharge;
        $updatedBalance = $wallet_balance-$totalAmount; //update balance after deduction begin
        //insert DEBIT txn into tbl_wallet_trans_dtls table
          $wallet_trans_info = array(
            'service_id' =>5,
            'order_id' => $order_id, 
            'user_id' => $user_id, 
            'operator_id' => $operator_id,
            'api_id' => $api_details->api_id,
            'transaction_status' =>'Success',
            'transaction_type' => "DEBIT",
            'payment_type' => "SERVICE",
            'payment_mode' => "PAID FOR DMT, ACCOUNT NUMBER ".$account_no.", AMOUNT ".$amount.", CHARGE ".$PayableCharge,
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
          
         // Store Starting in Wallet Table
            //DB::beginTransactions();
            // try{
              DB::table('tbl_wallet_trans_dtls')->insert($wallet_trans_info);
            //   DB::commit();
            // }
            // catch (\Exception $e) {
            // DB::rollback();
            // return "something went wrong";
            // }
          
        //  Ends Here
        
          //update balance into users table 
          $userInfo=array('wallet_balance' => $updatedBalance);
          $updateBalQry = DB::table('tbl_users')->where('userId', $user_id)->update($userInfo);
          //update balance after deduction end
        
        # Add New Transaction
        
           $trans_info = array(
                                'transaction_id' =>'',
                                'transaction_status' =>'PENDING', 
                                'service_id' => 5, 
                                'operator_id'=>54,
                                'api_id' => $api_details->api_id,
                                'trans_date' => date("Y-m-d H:i:s"),
                                'order_id' => $this->isValid($order_id),  
                                'mobileno' =>$request->sender_mobile_number, 
                                'user_id' => $this->isValid($user_id),          
                                'total_amount' => $this->isValid($amount),
                                'charge_amount' =>"0",
                                'transaction_type' =>$transaction_type, //add
                                'bank_transaction_id' =>'', //add
                                'imps_name' =>$account_holder_name, //add
                                'recipient_id' =>$recipient_id, //add
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
        $txn_id = DB::table('tbl_transaction_dtls')->insert($trans_info);
                        
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
          
          $Params["beneId"]= $receipt_details->cfree_beneficiaryid;
          $Params["transferId"]= $order_id;
          $Params["amount"] = $amount;
          $Params["transferMode"] = strtolower($transaction_type);
        
          $post_data = json_encode($Params, JSON_UNESCAPED_SLASHES);
          
          $api_info = array(
              'service_id' => 5, 
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
          //$api_log_id = $this->apiLog_model->addApiLogDetails($api_info);
          $url="https://payout-api.cashfree.com/payout/v1/requestTransfer";
          $ch = curl_init($url);
          curl_setopt($ch, CURLOPT_POST, true);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
          curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization:Bearer " . $token)); 
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
          $result_json = curl_exec($ch);
          
          curl_close($ch);
        $result =  json_decode($result_json, true); 
       
        //Cashfree Transaction complete
        
        #Update Api Log Table
        $update_api_dtls_arr =  array('response' => json_encode($result), 'updated_on'=>date('Y-m-d H:i:s') );
  //      $update_api_log = ApiLogDetail::where('order_id', $order_id)->update('tbl_apilog_dts', $update_api_dtls_arr );
        
        
        
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
        
        
        #Checking Transaction is success or failure
        if( ( in_array($result['status'], $pending_arr)) || (in_array($result['status'], $success_arr))  ) {
        
        $trans_record = TransactionDetail::where('order_id', $order_id)->first();
        $trans_update_arr = array(
                                      'transaction_status' =>$result['status'],
                                      'bank_transaction_id' =>$result['data']['utr'],
                                      'order_status' => $oStatus,
                                );
                                     
        $update_tras_record = TransactionDetail::where('order_id', $order_id)->update('tbl_transaction_dtls', $trans_update_arr);
        $trans_wallet_update_arr = array(
                                      'transaction_id' =>$result['data']['utr'],
                                      'bank_trans_id' =>$result['data']['utr']
                                    );
        $update_tras_records = WalletTransactionDetail::where('order_id', $order_id)->update('tbl_wallet_trans_dtls', $trans_wallet_update_arr);
            
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
            
            $apiupdate=ApiLogDetail::where(api_id,$api_details->api_id)->update($data);
            //update balance based on api id in apisetting table developed by susmitha end  
            //update sender avaland used limit start
            $sender_det  =array("sender_mobile_number"=>$mobile);
            
            $sender_details =DMTSender::where('tbl_sender_dts',$sender_mobile_no)->first();
            $available_limit=($sender_details->available_limit_crazy)-($amount);
            $used_limit=($sender_details->used_limit_crazy)+($amount);
            $sender_detupdate=array("available_limit_crazy"=>$available_limit,
                                  "used_limit_crazy"=>$used_limit);
            //print_r($sender_detupdate);
            $updatesender=DMTSender::where('sender_mobile_number',$sender_mobile_no)->update('tbl_sender_dts',$sender_detupdate);
            //update sender avaland used limit end
            $recipent_ver  =array("recipient_id"=>$data['recipient_id'],"is_verified"=>"Y");
            $recipent_ver  =array("recipient_id"=>$recipient_id,"is_verified"=>"Y");
            $benificiary_details =DMTSenderBeneficaryList::where('recipient_id',$recipient_id);
            
            // if(empty($benificiary_details)){
            //   $updatebeni=array("is_verified"=>"Y","verified_name"=>$receipt_details->recipient_name);
            // $where=array('recipient_id',$data['recipient_id']); 
            // $this->db->where($where);
            // $this->db->update('tbl_dmt_benificiary_dtls',$updatebeni);
            // }
            
            $money_re=DB::table('tbl_transaction_dtls')->join('tbl_dmt_benificiary_dtls', 'tbl_dmt_benificiary_dtls.recipient_id', '=', 'tbl_transaction_dtls.recipient_id')
            ->join('tbl_services_type', 'tbl_services_type.service_id', '=', 'tbl_transaction_dtls.service_id')
            ->where('id',$txn_id)->orWhere('id_deleted',0)->orderBy("TransTbl.id", "desc")->first();
            
            
            // print_r($money_re);
            $response = array(
              'status' => "true",
              'msg' => "Success",
              'result' => $result,
              'money'=>$money_re
            );
                        
            //Send SMS 
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
            
            if(is_numeric($role_id) && intval($role_id) <= 4){                
              $walletUserID = $user_id;
              $walletRoleID = $role_id;
              $isUserBalanceUpdated = false;
              
              for($i=$walletRoleID;$i>=1;$i--){                
              
                if($i==3 || $i==4 ){
                  $isUserBalanceUpdated = true;
                  $userParentID=DB::table( DB::raw('select userId,roleId,wallet_balance from tbl_users where isDeleted=0 and userId in (select parent_user_id from tbl_users where userId='.$userID)->first());
               //   $userParentID = $this->rechargeApi_model->getUserParentID($walletUserID);
                  if ($isUserBalanceUpdated && $userParentID && ( $userParentID->roleId==3||$userParentID->roleId==4) ) {
                    $walletUserID = $userParentID->userId;
                    $walletRoleID = $userParentID->roleId;
                    $updatedBalance = $userParentID->wallet_balance;
                  }
                  continue;
                }
                $walletAmt = 0;
                $walletBal = 0;    
                $distds='';             
               // $userParentID = $this->rechargeApi_model->getUserParentID($walletUserID);
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
                //    $apptds    =$this->rechargeApi_model->getTDS();
                    //$distds=$ds*($apptds->value/100);
                    $distds=0;
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


                  $wallet_txn_id = DB::table('tbl_wallet_trans_dtls')->insert($wallet_trans_info);
                  //update balance into users table            
                   $userInfo=array('wallet_balance' => $walletBal);
                    $updateBalQry = DB::table('tbl_users')->where('userId', $user_id)->update($userInfo);
                //  $updateBalQry = $this->rechargeApi_model->updateUserBalance($walletUserID,$walletBal);
                  //update balance after deduction end
                }
                $isUserBalanceUpdated = true;
              }
            }
            //commission wallet txn end
          }else{
              
               $isUserBalanceUpdated = true;
                $userID=$user_id;
                  $userParentID=DB::table( DB::raw('select userId,roleId,wallet_balance from tbl_users where isDeleted=0 and userId in select parent_user_id from tbl_users where userId='.$userID))->first();
               //   $userParentID = $this->rechargeApi_model->getUserParentID($walletUserID);
                if ($isUserBalanceUpdated && $userParentID && ( $userParentID->roleId==3||$userParentID->roleId==4) ) {
                    $walletUserID = $userParentID->userId;
                    $walletRoleID = $userParentID->roleId;
                    $updatedBalance = $userParentID->wallet_balance;
                  }
                
                $walletAmt = 0;
                $walletBal = 0;    
                $distds='';        
                
               // $userParentID = $this->rechargeApi_model->getUserParentID($walletUserID);
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
                //    $apptds    =$this->rechargeApi_model->getTDS();
                    //$distds=$ds*($apptds->value/100);
                    $distds=0;
                    $walletAmt=$ds-$distds; 
                  $walletBal = $updatedBalance+$distributor_commission;
                }else if($walletRoleID == 1){ //Admin
                  $walletAmt = $admin_commission;
                  $walletBal = $updatedBalance+$admin_commission;
                }
                else { }
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
                                    'bank_transaction_id' =>'',
                                    'order_status' => $oStatus,
          );
          $update_tras_record = DB::table('tbl_transaction_dtls')->where('order_id', $order_id)->update($trans_update_arr);
         
          //update balance after deduction begin
        //$updatedBalance = $wallet_balance-$amount;
       
          $userbalance =  DB::table('tbl_users')->where('userId', Auth::user()->userId)->first();
          $wallet_balance=$userbalance->wallet_balance;
          $updatedBalance = $wallet_balance+$totalAmount; 
          //insert DEBIT txn into tbl_wallet_trans_dtls table
          $wallet_trans_info = array(
            'service_id' =>5,
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
          ); $wallet_txn_id = DB::table('tbl_wallet_trans_dtls')->insert($wallet_trans_info);
                  //update balance into users table            
                   $userInfo=array('wallet_balance' => $walletBal);
                    $updateBalQry = DB::table('tbl_users')->where('userId', $user_id)->update($userInfo);
                    
          //update balance after deduction end
          $response = array(
            'status' => "false",
            'msg' => "failed",
            'result' => $result
          );        
        }
    } 
 public function getCommissionDetails($user_package_id,$service_id,$operator_id,$amount)
    {
        $commission=PackageCommissionDetail::where('from_range','<',$amount)
                    ->where('to_range','>',$amount)
                    ->where('service_id','=',5)
                    ->where('pkg_id','=',$user_package_id)
                    ->where('operator_id','=',54)
                    ->get();
        if(!empty($commission)){
            return $commission;
        } else {
            return array();
        }
    }
    
    public function get_order_id()
    {
          $last_order_id = file_get_contents("https://www.paymamaapp.in/admin/admin/txn_dmt_order_id.txt");  
          $replace=str_replace("JB","",$last_order_id);
          $sno_order_id  =intval($replace)+1;
          $order_id ="JB".$sno_order_id;
          
          $clientres= DB::table('tbl_transaction_dtls')
            ->join('tbl_apilog_dts', 'tbl_transaction_dtls.order_id', '=', 'tbl_apilog_dts.order_id')
            ->join('tbl_wallet_trans_dtls', 'tbl_transaction_dtls.order_id', '=', 'tbl_wallet_trans_dtls.order_id')
             
             // joining the contacts table , where user_id and contact_user_id are same
            ->select('tbl_transaction_dtls.order_id')
            ->where('tbl_transaction_dtls.order_id',$order_id)
            ->orWhere('tbl_apilog_dts.order_id', $order_id)
            ->orWhere('tbl_wallet_trans_dtls.order_id', $order_id)
            ->get();
          
          if(!empty($clientres)){
            $replaced=str_replace('"','',$order_id);
           File::put('admin/admin/txn_order_id.txt', $replaced);
            return json_encode($order_id);
          }
          else
          {
            $order=array('order_id'=>$order_id,'sno_order_id'=>$sno_order_id);
           File::put('admin/admin/txn_dmt_order_id.txt', $order_id);
            return json_encode($order_id);
          }
    }
    
    public function writeTxnOrderID($order_id){
        //Storage::put('txn_order_id.txt',$order_id);
       
      }
       public function isValid($str){
        if(isset($str) && $str != null)
          return $str;
        else
          return '';
      }
      
      
    public function failedRezorpayTransfer($trans_info, $rasor_status){
    
    $userbalance = User::where('userId', $trans_info['user_id'])->first();
    
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
        

        
    
    $wallet_txn_id = WalletTransactionDetail::insert($wallet_trans_info);
    //update balance into users table       
   
    $updateBalQry = User::where('userId', $userId)->update('wallet_balance', $wallet_balance);
    return true;
   }
}

?>