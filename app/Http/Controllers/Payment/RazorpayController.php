<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\PymtGtwayMdChargeDtl;
use App\SmsTemplate;
use App\User;
use App\UserLoginSessionDetail;
use App\WalletTransactionDetail;
use App\PGWalletTransactionDetail;
use App\PGBenificiaryDetails;
use App\MasterBenificiaryDetails;
use App\Sender;
use App\VirtualTransactionDetail;
use App\PaymentGatewayReport;
use Auth;
use PDF;
use DB;
use Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use PaytmWallet;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Razorpay\Api\Api;
// use App\Packages\Razorpay\Razorpay;
use Session;
use Mail;

class RazorpayController extends Controller
{
    /**
     * Redirect the user to the Payment Gateway.
     *
     * @return Response
     */
    public function onlinePayment(Request $request)
    {
        // $userBalance = WalletTransactionDetail::where('user_id', Auth::user()->userId)->orderBy('updated_on', 'DESC')->pluck('balance');
        //$request->session()->forget(['sender_details']);
        $request->session()->forget('sender_details');   
        $options = (Auth::user()->pg_options);
        if($options == "") {
            $options = array();
        } else {
            $options = json_decode($options,true);
        }
        // return $options;
        // return $userBalance;
        
         $request->session()->put('sender_details', [
                    'mobile_number'=> $request->mobile_number,
                    'aadhar_number'=> $request->aadhar_number,
                    'pan_number'=> $request->pan_number,
                    'account_holder_name'=> $request->account_holder_name,
                    'account_number'=> $request->account_number,
                    'bank_name'=> $request->bank_name,
                ]);
            
            
            if($request->session()->get('sender_details')){
                return redirect('online_payment');
            }
        
        return view('modules.payment.add_money',compact('options'));
        // return view('modules.payment.payment_gateway_report', compact('report', 'reportTH', 'filtersList', 'request', 'total_amt'));
    }
     
    public function payRazorPay(Request $request)
    {
        // print_r($request->all());die();
        include_once(app_path() .'/Packages/Razorpay/Razorpay.php');
        
        $options = (Auth::user()->pg_options);
        if($options == "") {
            $options = array();
        } else {
            $options = json_decode($options,true);
        }
         
        // $key = Config::get('constants.RAZORPAY_KEY');
        // $secret = Config::get('constants.RAZORPAY_SECRET');
        
        $key = "rzp_test_Dr4xfBx0Gj1Q6A";
        $secret = "exiUoU3oAsv1PKhJu8TgET3w";
        
        //$key = "rzp_live_OtE6fx5bIhczJV";
        //$secret = "DTT1nWluDTg4ZgevAPgsSFjx";
        if($request->pay_mode == 'upi'){
            $method='upi';
            $type='upi';
        }elseif($request->pay_mode == 'rupay_card'){
            $method='card';
            $type='rupay_card';
        }elseif($request->pay_mode == 'debit_card'){
            $method='card';
            $type='debit';
        }elseif($request->pay_mode == 'credit_card'){
            $method='card';
            $type='credit';
        }elseif($request->pay_mode == 'prepaid_card'){
            $method='card';
            $type='ppc';
        }elseif($request->pay_mode == 'corporate_card'){
            $method='card';
            $type='corporate_card';
        }elseif($request->pay_mode == 'net_banking'){
            $method='netbanking';
            $type='netbanking';
        }elseif($request->pay_mode == 'wallet'){
            $method='wallet';
            $type='wallet';
        }
        $config=json_decode("config:{
                            display: {
                              blocks: {
                                banks: {
                                  name: 'Pay using $method',
                                  instruments: [
                                    {
                                      method: $method,
                                      types: $type
                                    }
                                  ],
                                },
                              },
                             
                            },
                          }");
                      
        $api = new Api($key, $secret);
                //Check if the Method of Payment is not available
                if($method != ''){
                        $order = $api->order->create(array(
                            'amount' => $request->pay_amount*100,
                            'method'=>$method,
                            //'types'=>$type,
                            'currency' => 'INR',
                            'notes' => array(
                                    'username' => Auth::user()->username,
                                    ),
                        )
                        );
                }else{
                        $order = $api->order->create(array(
                            'amount' => $request->pay_amount*100,
                            'currency' => 'INR',
                            'notes' => array(
                                        'username' => Auth::user()->username,
                                        )
                        )
                        );    
                }
        
               
            
            $response = [
                'orderId' => $order['id'],
                'razorpayId' => $key,
                'amount' => $request->pay_amount*100,
                'name' => Auth::user()->name,
                'currency' => 'INR',
                'email' => Auth::user()->email,
                'contactNumber' => Auth::user()->mobile,
                'store_name' => Auth::user()->store_name,
                'address' => "",
                'description' => "Payment to Paymama",
                'method'=>$method,
                'types'=>$type,
            ];
            //print_r($response);die; 
           
        return view('modules.payment.online_payment', compact('response', 'options'));
    }

    /**
     * Obtain the payment information.
     *
     * @return Object
     */
    public function paymentCallback(Request $request)
    {
        // print_r($request->all());die();
        include_once(app_path() .'/Packages/Razorpay/Razorpay.php');
        
        // $key = Config::get('constants.RAZORPAY_KEY');
        // $secret = Config::get('constants.RAZORPAY_SECRET');
        
        $key = "rzp_test_Dr4xfBx0Gj1Q6A";
        $secret = "exiUoU3oAsv1PKhJu8TgET3w";
        
        //$key = "rzp_live_OtE6fx5bIhczJV";
        //$secret = "DTT1nWluDTg4ZgevAPgsSFjx";
        
        $api = new Api($key, $secret);
       
       $attributes  = array( 'razorpay_order_id' => $request->rzp_orderid, 'razorpay_payment_id'  => $request->rzp_paymentid, 'razorpay_signature'  => $request->rzp_signature);
       $order  = $api->utility->verifyPaymentSignature($attributes);
       //print_r($attributes);
       $razorPaySignature="".$request->rzp_orderid."|".$request->rzp_paymentid."";
       $generated_signature = hash_hmac("sha256", $razorPaySignature, $secret);
       //print_r($generated_signature);
        
        if ($generated_signature == $request->rzp_signature) {
            $paymentId  = $request->rzp_paymentid;
            
            $payment = $api->payment->fetch($paymentId);
            
            $order = $api->order->fetch($payment['order_id']);
            
            $method = $payment->method;
                    
            //$payment = $api->payment->fetch($paymentId);
            if(isset($payment['card_id'])){
                $card = $api->card->fetch($payment['card_id']);
                $cardType=$card['type'];
                //print_r($card);die;
              if($method == 'card'){
                    if($cardType == 'debit'){
                        $method=$method;
                        $charge_mode="DEBIT_CARD";
                    }elseif($cardType == 'credit'){
                        $method=$method;
                        $charge_mode="CREDIT_CARD";
                    }elseif($cardType == 'rupay'){
                        $method=$method;
                        $charge_mode="RUPAY_CARD";
                    }elseif($cardType == 'corporate'){
                        $method=$method;
                        $charge_mode="CORPORATE_CREDIT_CARD";
                    }elseif($cardType == 'ppc'){
                        $method=$method;
                        $charge_mode="PREPAID_CARD";
                    }
                }            
            }else{
                  if($method == 'upi'){
                    $method=$method;
                    $charge_mode="UPI";
                }elseif($method == 'wallet'){
                    $method=$method;
                    $charge_mode="WALLET";
                }
            }
            
            $order_id = $this->createOrderID();
            $amount = $payment['amount']/100;
            $userId = Auth::user()->userId;
            $user = User::find((int) $request->user_id);
            
            if(!$user) {
                return redirect('online_payment')->with('error', "Signature Mismatch!");
            }
            
            $user_id = $user->userId;
            $userBalance = $user->pg_wallet_balance;
            $smsData = [];

            $smsData['last_balance_amount'] = $userBalance;
            $balance = (((float) $amount) + ($userBalance ? (float) $userBalance : 0));
            $smsData['updated_balance_amount'] = $balance;
            $smsData['amount'] = $amount;

            $smsData['mobile'] = $user->mobile;
            
            if(!empty($payment['acquirer_data'])){
                if(isset($payment['acquirer_data']['rrn'])){
                    $bank_trans_id=$payment['acquirer_data']['rrn'];
                }elseif(isset($payment['acquirer_data']['auth_code'])){
                    $bank_trans_id=$payment['acquirer_data']['auth_code'];
                }
            }
            
            if(isset($payment['vpa']) ){
                $card_details=$payment['vpa'];
            }elseif(isset($card['last4'])){
                $card_details="".$card['network']." - ".$card['type']." - Ending - ".$card['last4']."";
            }
            
            $walletResponse = PGWalletTransactionDetail::create([
                'order_id' => $order_id,
                'user_id' => $user->userId,
                'transaction_status' => $payment['status'],
                'response_msg' => $payment['created_at'],
                'bank_trans_id' => $bank_trans_id,
                'transaction_type' => $charge_mode,
                'transaction_id' => $payment['order_id'],
                'trans_date' => date("Y-m-d G:i:s",strtotime($payment['created_at'])),
                'payment_type' => 'LOAD_WALLET',
                'payment_mode' => 'Wallet Load through '.strtolower(str_replace('_',' ',$charge_mode)).', '.$method.', Amount '.$amount ,
                'total_amount' => (float) $amount,
                'balance' => $balance,
            ]);
            
            if($walletResponse) {
                $user = User::find((int) $user_id);
                $user->pg_wallet_balance = (float) $balance;
                $userUpdresponse = $user->save();
                
                $pymt_gtwy_report = PaymentGatewayReport::create([
                    'order_id' => $order_id,
                    'user_id' => $user_id,
                    'role_id' => $user->roleId,
                    'transaction_status' => $payment['status'],
                    'bank_trans_id' => $bank_trans_id,
                    'transaction_type' => $charge_mode,
                    'transaction_id' => $payment['order_id'],
                    'trans_date' => date("Y-m-d G:i:s",strtotime($payment['created_at'])),
                    'payment_id' => $payment['id'],
                    'payment_type' => $charge_mode,
                    'payment_mode' => $method,
                    'payment_method' => $card_details,
                    'total_amount' => (float) $amount,
                    'response_msg' => $payment['description'],
                    'payment_status' => $payment['status'],
                    'balance' => $balance, 
                ]);
                // $pymt_gtwy_report = 1;
                $response = $pymt_gtwy_report;
                if ($response) {
                    $walletResponse['payment_mode'] = strtolower($charge_mode);
                    $walletResponse['order_id'] = $order_id;
                    $walletResponse['user_id'] = $user_id;
                    $walletResponse['total_amount'] = $amount;
                    $walletResponse['transaction_status'] = $payment['status'];
                    $walletResponse['response_msg'] = $payment['description'];
                    $walletResponse['bank_trans_id'] = $bank_trans_id;
                    $walletResponse['transaction_id'] = $payment['order_id'];
                    $walletResponse['payment_method'] = $charge_mode;
                    $walletResponse['trans_date'] = date("Y-m-d G:i:s",strtotime($payment['created_at']));
                    if($payment['status'] == 'captured'){
                        $PaymentGatewayDeductions=$this->deductPymtGtwayCharges($walletResponse, $user);
                    }else{
                        $PaymentGatewayDeductions="";
                    }
                    
                    //deduct charge
                    if($PaymentGatewayDeductions) {
                        $success["success"] = "Success!!";
                        $statusMsg = "Transaction updated successfully!!";
                        $msgRes = $this->sendSmswithTransactionInfo($smsData);
                        // $msgRes = 1;
                        if ($msgRes) {
                            $msg = "Dear Paymamma User, Your wallet is credited with Rs ".$amount;
                            $this->send_telegram($msg,$user->telegram_no);
                            $this->sendSms($msgRes, $smsData['mobile']);
                            $this->notifyWithTransactionInfo($smsData, $user_id);
                            $this->sendSuccess($success, $statusMsg);
                            return redirect('online_payment')->with('success', "Payment Successful!");
                        }
                    } else {
                        return redirect('online_payment')->with('error', "Something went wrong!");
                    }
                } else {
                    return redirect('online_payment')->with('error', "Something went wrong!");
                }
            }
        } else {
            return redirect('online_payment')->with('error', "Signature mismatch!");
        }

    }
    
    public function deductPymtGtwayCharges($walletResponse, $user)
    {
        $userDBRes = null;
        $response = null;
        if (isset($user->pg_options) && !empty($user->pg_options)) {
            $options = json_decode($user->pg_options,true);
            if(!isset($options[$walletResponse['payment_mode']])) {
                return $response;
            }
            $options = $options[$walletResponse['payment_mode']];

            if ($options) {
                $debitChrgTypeAmt = $options['type'] == "RS" ? $options['charge'] : ($options['charge'] / 100 * $walletResponse['total_amount']);
                
                if($debitChrgTypeAmt > 0) {
                    $user->pg_wallet_balance = $user->pg_wallet_balance - $debitChrgTypeAmt;
                    $userDBRes = $user->save();
                    if ($userDBRes) {
                        $walletResponse = PGWalletTransactionDetail::create([
                            'order_id' => isset($walletResponse['order_id']) ? $walletResponse['order_id'] : '',
                            'user_id' => $walletResponse['user_id'],
                            'transaction_status' => isset($walletResponse['transaction_status']) ? $walletResponse['transaction_status'] : '',
                            'response_msg' => isset($walletResponse['response_msg']) ? $walletResponse['response_msg'] : '',
                            'bank_trans_id' => isset($walletResponse['bank_trans_id']) ? $walletResponse['bank_trans_id'] : '',
                            'transaction_type' => "DEBIT",
                            'transaction_id' => isset($walletResponse['transaction_id']) ? $walletResponse['transaction_id'] : '',
                            'trans_date' => isset($walletResponse['trans_date']) ?  Carbon::parse($walletResponse['trans_date'])->addSeconds(1) : '',
                            'payment_type' => Config::get('constants.PAYMENT_TYPE.PAYMT_WALLET'),
                            // 'payment_type' => 'Payment Gateway Load',
                            'payment_mode' => 'Payment Gateway Charge, '.strtolower(str_replace('_',' ',$options['mode'])).', '.$walletResponse['payment_method'].', Amount '.$walletResponse['total_amount'],
                            'gateway_mode' => $options['mode'],
                            'total_amount' => $debitChrgTypeAmt,
                            'balance' => $user->wallet_balance,
                        ]);
                        $response = $walletResponse;
                    }
                } else {
                    return true;
                }
            }
        }

        return $response;
    }
    
    /**
     * Razorpay Payout Transactions
     */
    
    public function razorpay_bankTransfer(Request $request){
        if (!$request->input('money_transfer')) {
            $data['money_trns_type'] = $request->input('money_transfer');
            $data['page_name'] = "customer_mobile";
            if ($request->input('money_transfer') == 'RAZORPAY') {
                $data['operator_id'] = 56;  
                $data['operator_name']= 'RAZORPAY';

            }elseif($request->input('money_transfer') == 'CASHFREE') {
                $data['operator_id']= 55;
                $data['operator_name']= 'CASHFREE';
            }else{
                $data['operator_id'] = 56;
                $data['operator_name']= 'RAZORPAY';
            }
           
            return view("modules.PGWallet.pg_mobile_number_money_transfer", compact('data'));

            }else{
            
            $bankList = $this->getBankList('21');
            $moneyTranTypes = Config::get('constants.BANK_TRANSFER.TYPE');
            
            return view("modules.PGWallet.pg_money_transfer", compact('moneyTranTypes','bankList'));
        }
        
    }
    
    /**
     * Get Bank List
     */
    public function getBankList($operator_id){
        $result = [];
        $response = Http::post(Config::get('constants.MONEY_TRANSFER.GET_BANK_LIST'), [
            'token' => UserLoginSessionDetail::getUserApikey(Auth::user()->userId),
            'user_id' => Auth::user()->userId,
            'role_id' => Auth::user()->roleId,
            'operatorID'=> $operator_id,
        ]);

        if (isset($response['result']['bank_list'])) {
            $result = $response['result']['bank_list'];
        }

        return $result;
    }
    
    public function razorpay_getSenderDetails(Request $request){
    
        $operator_id='';
        if ($request->operator_name == 'RAZORPAY') {
            $operator_id= 56;  
            $data['operator_name']= 'RAZORPAY';
        }elseif($request->operator_name == 'CASHFREE') {
            $operator_id= 55;
            $data['operator_name']= 'CASHFREE';  
        }else{
            $operator_id = 56;
            $data['operator_name']= 'RAZORPAY';
        }

        $data=[];
        
        if (isset($request->sender_mobile_number)) {
            $data['sender_mobile_number'] = $request->sender_mobile_number;
            $data['page_name'] = "sender_details";
        }
        
        $data ['request']= $request->all();
        //Get the PG Benificary Details from Mobile Number        
        $sender_receipient_list = PGBenificiaryDetails::where('sender_mobile_number', $data['sender_mobile_number'])->get();
        $sender_receipient_list = isset($sender_receipient_list) && $sender_receipient_list ?  $sender_receipient_list : [];
        //Get the MASTER Benificary Details from Mobile Number        
        $master_sender_receipient_list = MasterBenificiaryDetails::where('sender_mobile_number', $data['sender_mobile_number'])->get();
        $master_sender_receipient_list = isset($sender_receipient_list) && $sender_receipient_list ?  $sender_receipient_list : [];
        
        // print_r($response);
        $data['operator_name'] =$request->operator_name;
        $data['mobile_no'] = $request->sender_mobile_number;
        
        //END get Benificary details
         if (count($sender_receipient_list) == 0) {
            $data['error'] = "Benificary Not Found PG of this mobile no. Please Register it!!";
            $data['operator_name']= $data['operator_name'];
            }elseif(count($master_sender_receipient_list) == 0){
            $data['page_name'] = "add_beneficiary";
            $data['error'] = "Benificary Not Found Master of this mobile no. Please Register it!!";
            $data['operator_name']= $data['operator_name'];
            }
        //Get the Bank List
        $data['bankList'] = $this->getAllBankList();
        $data['bank_ifsc']=[];
        foreach ($data['bankList']['result']['bank_list'] as $key => $value) {
            $data['bank_ifsc'][$value['bank_code']] = $value['ifsc_prefix'];
        }


        
       
        $sender_receipient_list=[];
        $sender_by_acc=[];

        return view("modules.PGWallet.pg_mobile_number_money_transfer", compact('data', 'sender_receipient_list', 'sender_by_acc'));
       
    }
    
    public function getAllBankList(){

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
    
    /**
     * Add Payment Gateway Benificary Details
     */
     public function addBeneficiary(Request $request){
   
        $data['page_name'] = "add_beneficiary";
        $data['mobile_no'] = $request->mobile_no;
        $data['operator_name'] = $request->operator_name;
        $opt_const = 'constants.MONEY_TRANSFER_OPERATOR.'.$request->operator_name;
        $operator_id = OperatorSetting::where('operator_name', Config::get($opt_const))->pluck('operator_id')->first();
        

        $data['bankList'] = DB::table('tbl_bank_list')->orderBy(trim('BANK_NAME'),'ASC')->get();
        $data['bankList'] = isset($data['bankList']) && $data['bankList'] ? $data['bankList']->json() : [];
        $data['bank_ifsc']=[];
       
        foreach ($data['bankList']['result']['bank_list'] as $key => $value) {
            $data['bank_ifsc'][$value['bank_code']] = $value['ifsc_prefix'];
        }
        
        $data['account_verified'] ='0';
        if (isset($request->action) && ($request->action == 'verify_account')) {
            $verify_accc_resp =$this->verifyBankAccount($request, $reqBody);
            $data['verify_accc_resp'] =$verify_accc_resp;
            if ($verify_accc_resp['status'] == "true") {
                // $data['success'] = $verify_accc_resp['msg'];
                $data['success'] = "Bank Account is Verified";
                $data['account_verified'] ='1';
                
                if (isset($verify_accc_resp['result']['verify_account_holder'])) {
                    $request->beneficiary_name = $verify_accc_resp['result']['verify_account_holder'];
                    $data['success'] = "Bank Account is Verified. Account Holder Name : ".$verify_accc_resp['result']['verify_account_holder'];
                }

            }else {
                $data['error'] = $verify_accc_resp['msg'];
            }
            
            
        }elseif (isset($request->action) && ($request->action == 'add_beneficiary') ){
            $add_bene_resp =$this->submitBankAccount($request, $reqBody);
            $data['add_bene_resp'] =$add_bene_resp;
            if ($add_bene_resp['status'] == "true") {
                // $data['success'] = $verify_accc_resp['msg'];
                $data['success'] = "Bank Account is added";
                // $data['account_verified'] ='1';


                 //START get sender details
                $requestBody = [
                    'sender_mobile_number' => $request->mobile_no,
                    'token' => UserLoginSessionDetail::getUserApikey(Auth::user()->userId) ? UserLoginSessionDetail::getUserApikey(Auth::user()->userId) : '',
                    'user_id' => Auth::user()->userId,
                    'role_id' => Auth::user()->roleId,
                    'operatorID' => $request->operator_id
                ];
            
                // print_r($requestBody);
                
                $sender_dtls = Http::post(Config::get('constants.MONEY_TRANSFER.GET_SENDER_DTLS_API'), $requestBody);
                $sender_dtls = isset($sender_dtls) && $sender_dtls ? $sender_dtls->json() : [];
                //END get sender details


                // START get recipent details
                $sender_receipient_list = Http::post(Config::get('constants.MONEY_TRANSFER.GET_RECEIPIENT_LIST'), $requestBody);
                $sender_receipient_list = isset($sender_receipient_list) && $sender_receipient_list ? $sender_receipient_list->json() : [];
                // END get recipent details

                $data['page_name'] = "sender_details";
                $data['mobile_no'] = $request->mobile_no;


                // return view("modules.service_type.mobile_no_money_trans", compact('data', 'sender_dtls', 'sender_receipient_list'));
                return redirect('/money_transfer')->with('success', 'Beneficiary Added SuccessFully');
            }else {
                $data['error'] = $add_bene_resp['msg'];
               
            }
        }
       
        return view("modules.PGWallet.mobile_number_money_transfer", compact('data', 'request'));
    }
    
    public function verifyBankAccount($request, $reqBody){
        $reqBody["bank_account_number"] = $request->beneficiary_acc_no;
        $reqBody["bank_code"] = $request->bank_code;
        $reqBody["ifsc"] = $request->beneficiary_ifsc;
        $reqBody['reference_number'] = 121;
        $response = Http::post(Config::get('constants.MONEY_TRANSFER.VERIFY_BNK_AC') , $reqBody);
        $response = isset($response) && $response ? $response->json() : [];
        return $response;
    }
    
    public function submitBankAccount($request, $reqBody){
        $reqBody["bank_account_number"] = $request->beneficiary_acc_no;
        $reqBody["bank_code"] = $request->bank_code;
        $reqBody["ifsc"] = $request->beneficiary_ifsc;
        $reqBody['recipient_name'] = $request->beneficiary_name;
        $reqBody['recipient_mobile_number'] = $request->beneficiary_mobile;
        $response = Http::post(Config::get('constants.MONEY_TRANSFER.CREATE_RECEPIENT_API') , $reqBody);
        $response = isset($response) && $response ? $response->json() : [];
        return $response;
    }
    
    
    /**
     * Send Sms to User on Successfull Transaction from Payment Gateway
     */
    public function sendSmswithTransactionInfo($smsData)
    {
        $msg = "";
        $result = null;

        $SmsBalAddTemplate = SmsTemplate::where('alias', Config::get('constants.SMS_TEMPLATE_ALIAS.BALANCE_ADDED'))->first();
        if (isset($SmsBalAddTemplate)) {
            $msg = __($SmsBalAddTemplate->template, [
                "last_balance_amount" => $smsData['last_balance_amount'],
                "amount" => $smsData['amount'],
                "updated_balance_amount" => $smsData['updated_balance_amount'],
            ]);
        }

        return $msg;
    }
    
    public function send_telegram($msg,$chat_id) {
        $telegram = new \App\Packages\Telegram\Telegram(Config::get('constants.TELEGRAM_BOT_ID'));
        $content = array('chat_id' => $chat_id, 'text' => $msg, 'parse_mode' => 'HTML');
        $telegram->sendMessage($content);
        return true;
    }

    public function notifyWithTransactionInfo($data, $user_id){
        $msg = "";
        $templatenNotify = SmsTemplate::where('alias', Config::get('constants.SMS_TEMPLATE_ALIAS.BALANCE_ADDED.name'))->get()->first();
        if (isset($templatenNotify)) {
            $msg = __($templatenNotify->notification, [
                "last_balance_amount" => $data['last_balance_amount'],
                "amount" => $data['amount'],
                "updated_balance_amount" => $data['updated_balance_amount'],
            ]);
        }
        if ($msg) {
            
            $user_session = DB::table('tbl_users_login_session_dtl')->where('user_id', $user_id)->get()->first();
            if ( $user_session) {
                // $notmsg = 'Dear SMART PAY User, Your wallet is credited with Rs :amount , Last Balance Rs :last_balance_amount . Updated Balance Rs :updated_balance_amount, Helpline : 040-29563154';
                $this->sendNotification($user_session->firebase_token, $templatenNotify->sms_name, $msg, $user_id);
            }

        }
        return true;
    }
    
    
    public function createOrderID(){
        $max_id = PaymentGatewayReport::max('id');
        $max_id = 1+(int)$max_id;
        $newID = "PG".$max_id;
        return $newID;
    }
    
    
   
  
    
}
