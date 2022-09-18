<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\PymtGtwayMdChargeDtl;
use App\SmsTemplate;
use App\User;
use App\WalletTransactionDetail;
use App\VirtualTransactionDetail;
use App\PaymentGatewayReport;
use Auth;
use PDF;
use DB;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PaytmWallet;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Mail;

class WebhookController extends Controller
{   
    public function index(Request $request)
    {
        
        $data = $request->all();
        $event = isset($data['event']) ? $data['event'] : "";
        $payload = isset($data['payload']) ? $data['payload'] : "";
        $smsData = [];
        $balance = 0;
        
        if($event == 'virtual_account.credited' && $payload != "") {
            $payment = $payload['payment']['entity'];
            $account = $payload['virtual_account']['entity'];
            if($payment['status'] == 'captured') {
                $amount = $payment['amount']/100;
                $user = User::where('va_id', $account['id'])->get();
                $check_txn = WalletTransactionDetail::where('transaction_id', $payment['id'])->get();
                if(isset($check_txn) && count($check_txn)) {
                    return $this->sendError("Transaction already processed!!");
                }
                
                if (isset($user) && count($user)) {
                    $user_id = $user[0]['userId'];
                    $userBalance = $user[0]['wallet_balance'];

                    $smsData['last_balance_amount'] = $userBalance;
                    $balance = (((float) $amount) + ($userBalance ? (float) $userBalance : 0));
                    $smsData['updated_balance_amount'] = $balance;
                    $smsData['amount'] = $amount;
    
                    $userMobNo = User::where('va_id', $account['id'])->pluck('mobile')->first();
                    if ($userMobNo) {
                        $smsData['mobile'] = $userMobNo;
                    }
                    
                    $method = $payment['method'] == 'upi' ? 'UPI' : 'BANK';
                    
                    $orderId = "VA".$this->createOrderID();
    
                    $walletResponse = WalletTransactionDetail::create([
                        'order_id' => $orderId,
                        'user_id' => $user_id,
                        'transaction_status' => 'SUCCESS',
                        'response_msg' => 'SUCCESS',
                        'bank_trans_id' => !empty($payment['acquirer_data']) ? $payment['acquirer_data']['rrn'] : '',
                        'transaction_type' => 'CREDIT',
                        'transaction_id' => $payment['id'],
                        'trans_date' => date("Y-m-d G:i:s",$payment['created_at']),
                        'payment_type' => 'LOAD_WALLET', //check
                        'payment_mode' => 'Money Added Via Virtual Account ('.$method.')' ,
                        'total_amount' => (float) $amount,
                        'balance' => $balance,
                    ]);
    
                    if($walletResponse) {
                        $user = User::find((int) $user_id);
                        $user->wallet_balance = (float) $balance;
                        $userUpdresponse = $user->save();
    
                        $pymt_gtwy_report = VirtualTransactionDetail::create([
                            'order_id' => $orderId,
                            'user_id' => $user_id,
                            'transaction_status' => 'SUCCESS',
                            'bank_trans_id' => !empty($payment['acquirer_data']) ? $payment['acquirer_data']['rrn'] : '',
                            'transaction_type' => 'CREDIT',
                            'transaction_id' => $payment['id'],
                            'trans_date' => date("Y-m-d G:i:s",$payment['created_at']),
                            'payment_type' => $method,
                            'payment_mode' => 'Money Added Via Virtual Account ('.$method.')' ,
                            'total_amount' => (float) $amount,
                            'balance' => $balance, 
                        ]);
                        // $pymt_gtwy_report = 1;
                        $response = $pymt_gtwy_report;
                        if ($response) {
                            $success["success"] = "Success!!";
                            $statusMsg = "Transaction updated successfully!!";
                            $msgRes = $this->sendSmswithTransactionInfo($smsData);
                            // $msgRes = 1;
                            if ($msgRes) {
                                $msg = "Dear PAYMAMA User, Your wallet is credited with Rs ".$amount;
                                $this->send_telegram($msg,$user[0]['telegram_no']);
                                $this->sendSms($msgRes, $smsData['mobile']);
                                $this->notifyWithTransactionInfo($smsData, $user_id);
                                return $this->sendSuccess($success, $statusMsg);
                            }
                        } else {
                            $this->sendError("Failed to update transaction!!");
                        }
                    }
                }
            }
        }
    }
    
    public function cashfree_webhook(Request $request) {
        $data = $request->all();
        Log::info('Payload: '.json_encode($data));
        $event = isset($data['event']) ? $data['event'] : "";
        $smsData = [];
        $balance = 0;
        $signature = $data['signature'] ?? "";
        if($signature == "") {
            return false;
        }
        unset($data["signature"]);
        ksort($data);
        $postData = "";
        foreach ($data as $key => $value){
            if (strlen($value) > 0) {
                $postData .= $value;
            }
        }
        $hash_hmac = hash_hmac('sha256', $postData, Config::get('constants.CASHFREE_COLLECT_SECRET'), true) ;
        $computedSignature = base64_encode($hash_hmac);
        if($signature == $computedSignature) {
            if($event == 'AMOUNT_COLLECTED') {
                $va_id = $data['vAccountId'];
                $amount = (float) $data['amount'];
                $remitter = $data['remitterName'];
                if(isset($data['isVpa']) && $data['isVpa']) {
                    $user = User::where('va_upi_id', $data['virtualVpaId'])->get();
                    $orderId = "QR".$this->createOrderID();
                    $method = 'UPI';
                    $payment_mode = "UPI ".$data['remitterVpa']." NAME ".$data['remitterName'];
                } else {
                    $user = User::where('va_id', $va_id)->get();
                    $orderId = "VA".$this->createOrderID();
                    $method = 'BANK';
                    $payment_mode = "WALLET LOADED VIA VIRTUAL ACCOUNT BANK ACCOUNT ".$data['remitterAccount']."  AMOUNT ".$amount;
                }
                $check_txn = WalletTransactionDetail::where('transaction_id', $data['referenceId'])->get();
                if(isset($check_txn) && count($check_txn)) {
                    return $this->sendError("Transaction already processed!!");
                }
                
                if (isset($user) && count($user)) {
                    $user_id = $user[0]['userId'];
                    $userBalance = $user[0]['wallet_balance'];
    
                    $smsData['last_balance_amount'] = $userBalance;
                    $balance = (float) (($amount) + ($userBalance ? $userBalance : 0.00));
                    $smsData['updated_balance_amount'] = $balance;
                    $smsData['amount'] = $amount;
    
                    $userMobNo = $user[0]['mobile'];
                    if ($userMobNo) {
                        $smsData['mobile'] = $userMobNo;
                    }
                    
    
                    $walletResponse = WalletTransactionDetail::create([
                        'order_id' => $orderId,
                        'user_id' => $user_id,
                        'transaction_status' => 'SUCCESS',
                        'response_msg' => 'SUCCESS',
                        'bank_trans_id' => $data['utr'],
                        'transaction_type' => 'CREDIT',
                        'transaction_id' => $data['referenceId'],
                        'trans_date' => date("Y-m-d G:i:s",strtotime($data['paymentTime'])),
                        'payment_type' => 'LOAD_WALLET', //check
                        'payment_mode' => $payment_mode ,
                        'total_amount' => $amount,
                        'balance' => $balance,
                    ]);
    
                    if($walletResponse) {
                        $user = User::find((int) $user_id);
                        $user->wallet_balance = $balance;
                        $userUpdresponse = $user->save();
                        $pymt_gtwy_report = VirtualTransactionDetail::create([
                            'order_id' => $orderId,
                            'user_id' => $user_id,
                            'transaction_status' => 'SUCCESS',
                            'bank_trans_id' => $data['utr'],
                            'transaction_type' => 'CREDIT',
                            'transaction_id' => $data['referenceId'],
                            'trans_date' => date("Y-m-d G:i:s",strtotime($data['paymentTime'])),
                            'payment_type' => $method,
                            'payment_mode' => $payment_mode ,
                            'total_amount' => $amount,
                            'balance' => $balance,
                            'mobile' => $userMobNo
                        ]);
                        $response = $pymt_gtwy_report;
                        if ($response) {
                            $data = array(
                                'email' => $user[0]['email'],
                                'name' => $user[0]['first_name'] . " " . $user[0]['last_name'],
                                'updated_bal' => $balance,
                                'amount' => $amount,
                                'last_bal' => $userBalance,
                                'type' => 'Credited'
                            );
                            /*$send_email = Mail::send('mail.wallet',$data, function($msg) use($data) {
                                $msg->to($data['email'], $data['name']);
                                $msg->subject('Transaction Successful - PayMama');
                                $msg->from('hello@paymamaapp.in','PayMama - Business Made Easy');
                            });*/
                            
                            $success["success"] = "Success!!";
                            $statusMsg = "Transaction updated successfully!!";
                            $msgRes = $this->sendSmswithTransactionInfo($smsData);
                            // $msgRes = 1;
                            if ($msgRes) {
                                $msg = "Dear PAYMAMA User, Your wallet is credited with Rs ".$amount;
                                $this->send_telegram($msg,$user[0]['telegram_no']);
                                $this->sendSms($msgRes, $smsData['mobile']);
                                $this->notifyWithTransactionInfo($smsData, $user_id);
                                return $this->sendSuccess($success, $statusMsg);
                            }
                        } else {
                            $this->sendError("Failed to update transaction!!");
                        }
                    }
                }
            }
        }
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
                // $notmsg = 'Dear PAYMAMA User, Your wallet is credited with Rs :amount , Last Balance Rs :last_balance_amount . Updated Balance Rs :updated_balance_amount, Helpline : 040-29563154';
                $this->sendNotification($user_session->firebase_token, $templatenNotify->sms_name, $msg, $user_id);
            }

        }
        return true;
    }
    
    public function createOrderID(){
        $max_id = VirtualTransactionDetail::max('id');
        $max_id = 1+(int)$max_id;
        $newID = $max_id;
        return $newID;
    }
}
?>