<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Auth;
use Config;
use DB;
use App\User;
use App\PaymentGatewayReport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\WalletTransactionDetail;
use App\PymtGtwayMdChargeDtl;
use App\SmsTemplate;
use Razorpay\Api\Api;
// use App\Packages\Razorpay\Razorpay;

class RazorpayController extends Controller
{
    /**
     * Redirect the user to the Payment Gateway.
     *
     * @return Response
     */
    public function pay(Request $request)
    {
        // print_r($request->all());die();
        include_once(app_path() .'/Packages/Razorpay/Razorpay.php');
        
        // $key = Config::get('constants.RAZORPAY_KEY');
        // $secret = Config::get('constants.RAZORPAY_SECRET');
        $key = "rzp_test_9GXxSUKBJXhTBM";
        $secret = "vUEDIu3XkrCh3lRo1FmdbhKx";
        $api = new Api($key, $secret);
        $order = $api->order->create(array(
            'amount' => $request->pay_amount*100,
            'currency' => 'INR',
            'notes' => array(
                'username' => Auth::user()->username
            )
        )
        );
        
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
            'description' => "Payment to SmartPay",
        ];
        
        return view('modules.payment.online_payment', compact('response'));
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
        $key = "rzp_test_9GXxSUKBJXhTBM";
        $secret = "vUEDIu3XkrCh3lRo1FmdbhKx";
        $api = new Api($key, $secret);
        $attributes  = array('razorpay_signature'  => $request->rzp_signature,  'razorpay_payment_id'  => $request->rzp_paymentid ,  'razorpay_order_id' => $request->rzp_orderid);
        $order  = $api->utility->verifyPaymentSignature($attributes);
        // print_r($order);die();
        if($order) {
            echo "ok";die();
        } else{
            echo "not ok";die();
        }
        $generated_signature = hash_hmac("sha256",$request->rzp_orderid + "|" + $request->rzp_paymentid, $secret);
        
        if ($generated_signature == $request->rzp_signature) {
            $orderId  = $request->rzp_orderid;
            
            $payment = $api->payment->fetch($orderId);
            $method = $payment->method;
            // Methods card, netbanking, wallet, emi, upi
            if($method == 'card') {
                $card = Http::withBasicAuth($key, $secret)->get('https://api.razorpay.com/v1/payments/'.$orderId.'/card')->json();
                print_r($card); die();
                if($card['type'] == 'debit') {
                    if($card['network'] == 'RuPay') {
                        $mode = 'RUPAY_CARD';
                        $charge_mode = 'PAYTMCC';
                    } else {
                        $mode = 'DEBIT_CARD';
                        $charge_mode = 'DC';
                    }
                } elseif($card['type'] == 'credit') {
                    $mode = 'CREDIT_CARD';
                    $charge_mode = 'CC';
                }
            } else {
                print_r($payment);die();
                $mode = strtoupper($method);
                if($mode == 'UPI') {
                    $charge_mode = $mode;
                } elseif($mode == 'NETBANKING') {
                    $charge_mode = 'NB';
                } else {
                    $charge_mode = 'CC';
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
            $userBalance = $user->wallet_balance;
            $smsData = [];

            $smsData['last_balance_amount'] = $userBalance;
            $balance = (((float) $amount) + ($userBalance ? (float) $userBalance : 0));
            $smsData['updated_balance_amount'] = $balance;
            $smsData['amount'] = $amount;

            $userMobNo = User::where('va_id', $account['id'])->pluck('mobile')->first();
            if ($userMobNo) {
                $smsData['mobile'] = $userMobNo;
            }
    
            $walletResponse = WalletTransactionDetail::create([
                'order_id' => $order_id,
                'user_id' => $user->userId,
                'transaction_status' => 'SUCCESS',
                'response_msg' => 'SUCCESS',
                'bank_trans_id' => !empty($payment['acquirer_data']) ? $payment['acquirer_data']['rrn'] : '',
                'transaction_type' => 'CREDIT',
                'transaction_id' => $orderId,
                'trans_date' => date("Y-m-d G:i:s",$payment['created_at']),
                'payment_type' => 'LOAD_WALLET', //check
                'payment_mode' => $chargeType[0]['mode'] ,
                'total_amount' => (float) $amount,
                'balance' => $balance,
            ]);
    
            if($walletResponse) {
                $user = User::find((int) $user_id);
                $user->wallet_balance = (float) $balance;
                $userUpdresponse = $user->save();

                $pymt_gtwy_report = PaymentGatewayReport::create([
                    'order_id' => $orderId,
                    'user_id' => $user_id,
                    'transaction_status' => 'SUCCESS',
                    'bank_trans_id' => !empty($payment['acquirer_data']) ? $payment['acquirer_data']['rrn'] : '',
                    'transaction_type' => 'CREDIT',
                    'transaction_id' => $payment['id'],
                    'trans_date' => date("Y-m-d G:i:s",$payment['created_at']),
                    'payment_type' => $method,
                    'payment_mode' => $chargeType[0]['mode'] ,
                    'total_amount' => (float) $amount,
                    'balance' => $balance, 
                ]);
                // $pymt_gtwy_report = 1;
                $response = $pymt_gtwy_report;
                if ($response) {
                    $walletResponse = [];
                    $walletResponse['pymt_gt_payment_mode'] = $charge_mode;
                    $walletResponse['order_id'] = $order_id;
                    $walletResponse['user_id'] = $user_id;
                    $walletResponse['transaction_id'] = $payment['id'];
                    $walletResponse['trans_date'] = $payment;
                    
                    //deduct charge
                    if($this->deductPymtGtwayCharge($walletResponse, $user)) {
                        $success["success"] = "Success!!";
                        $statusMsg = "Transaction updated successfully!!";
                        $msgRes = $this->sendSmswithTransactionInfo($smsData);
                        // $msgRes = 1;
                        if ($msgRes) {
                            $msg = "Dear SMART PAY User, Your wallet is credited with Rs ".$amount;
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
    
    /**
     * Deduct Payment Gateway Charge On Successfull Transaction
     */
    public function deductPymtGtwayCharge($walletResponse, $user)
    {
        $userDBRes = null;
        $response = null;
        
        if (isset($walletResponse->pymt_gt_payment_mode)) {
            $chargeDtl = PymtGtwayMdChargeDtl::where('code', $walletResponse->pymt_gt_payment_mode)->get()->first();

            if ($chargeDtl) {
                $debitChrgTypeAmt = $chargeDtl->charge_type == "RS" ? $chargeDtl->charge : ($chargeDtl->charge / 100 * $walletResponse->total_amount);

                $user->wallet_balance = $user->wallet_balance - $debitChrgTypeAmt;

                $userDBRes = $user->save();

                if ($userDBRes) {
                    $walletResponse = WalletTransactionDetail::create([
                        'order_id' => isset($walletResponse->order_id) ? $walletResponse->order_id : '',
                        'user_id' => $walletResponse->user_id,
                        'transaction_status' => 'SUCCESS',
                        'response_msg' => 'SUCCESS',
                        'bank_trans_id' => '',
                        'transaction_type' => "DEBIT",
                        'transaction_id' => isset($walletResponse->transaction_id) ? $walletResponse->transaction_id : '',
                        'trans_date' => isset($walletResponse->trans_date) ?  Carbon::parse($walletResponse->trans_date)->addSeconds(1) : '',
                        'payment_type' => Config::get('constants.PAYMENT_TYPE.PAYMT_WALLET'),
                        'payment_mode' => Config::get('constants.PAYMENT_GTWAY_TYPE.PYMT_GTWY_CHRG'),
                        'gateway_mode' => $chargeDtl->mode,
                        'total_amount' => $debitChrgTypeAmt,
                        'balance' => $user->wallet_balance,
                    ]);

                    if($walletResponse) {
                        return true;
                    }
                }
            }
        }

        return false;
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
