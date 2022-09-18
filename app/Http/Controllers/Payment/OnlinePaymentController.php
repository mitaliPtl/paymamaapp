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
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
use Mail;

class OnlinePaymentController extends Controller
{
    /**
     * Online Payment View
     */
    public function onlinePayment()
    {
        // $userBalance = WalletTransactionDetail::where('user_id', Auth::user()->userId)->orderBy('updated_on', 'DESC')->pluck('balance');
        $options = (Auth::user()->pg_options);
        if($options == "") {
            $options = array();
        } else {
            $options = json_decode($options,true);
        }
        // return $options;
        // return $userBalance;
        return view('modules.payment.online_payment',compact('options'));
        // return view('modules.payment.payment_gateway_report', compact('report', 'reportTH', 'filtersList', 'request', 'total_amt'));
    }

    /**
     * Callback For Payment
     */
    public function onlinePaymentStatus()
    {
        
        $transaction = PaytmWallet::with('receive');
       
        $response = $transaction->response(); // To get raw response as array
        return $response;
        if ($response['STATUS'] == 'TXN_SUCCESS') {
            $walletResponse = $this->updateWalletTransaction($response, "SUCCESS");
            if ($walletResponse) {
                return redirect('online_payment')->with('success', "Payment Successful!");
            }

        } else if($response['STATUS'] == 'PENDING') {
            $walletResponse = $this->updateWalletTransaction($response, 'PENDING');
            if ($walletResponse) {
                return redirect('online_payment')->with('error', "Payment Pending!");
            }
        }else {
            $walletResponse = $this->updateWalletTransaction($response, 'FAILURE');
            if ($walletResponse) {
                return redirect('online_payment')->with('error', $response['RESPMSG']);
            }
        }
        // if ($transaction->isSuccessful()) {
        //     $walletResponse = $this->updateWalletTransaction($response, "SUCCESS");
        //     if ($walletResponse) {
        //         return redirect('online_payment')->with('success', "Payment Successful!");
        //     }

        // } else if ($transaction->isFailed()) {
        //     $walletResponse = $this->updateWalletTransaction($response, 'FAILURE');
        //     if ($walletResponse) {
        //         return redirect('online_payment')->with('error', "Payment Failed!");
        //     }
        // }
    }
    
    
    public function paytm_webhook(Request $request) {
        $data = $request->all();
        // CURRENCY=INR&GATEWAYNAME=PPBL&RESPMSG=Txn+Success&BANKNAME=&PAYMENTMODE=UPI&CUSTID=2&MID=xRiYem08825900198760&MERC_UNQ_REF=&RESPCODE=01&TXNID=20211020111212800110168583056324772&TXNAMOUNT=10.00&ORDERID=71684&STATUS=TXN_SUCCESS&BANKTXNID=129365656551&TXNDATETIME=2021-10-20+21%3A15%3A00.0&TXNDATE=2021-10-20
         Log::info('PayloadWebhook: '.json_encode($data));
        $check_txn = WalletTransactionDetail::where('order_id', $data['ORDERID'])->get();
        if(isset($check_txn) && count($check_txn)) {
            return $this->sendError("Transaction already processed!!");
        } else {
            $response = $data;
            if ($response['STATUS'] == 'TXN_SUCCESS') {
                $walletResponse = $this->updateWalletTransaction($response, "SUCCESS");
                if ($walletResponse) {
                    return true;
                }
    
            } else if($response['STATUS'] == 'PENDING') {
                $walletResponse = $this->updateWalletTransaction($response, 'PENDING');
                if ($walletResponse) {
                    return false;
                }
            }else {
                $walletResponse = $this->updateWalletTransaction($response, 'FAILURE');
                if ($walletResponse) {
                    return false;
                }
            }
        }
        return false;
    }

    /**
     * Update Wallet Transaction Table with order
     */
    public function updateWalletTransaction($orderResponse, $paymentStatus)
    {
        $response = null;
        $balance = 0;
        $smsData = [];

        // $userBalance = WalletTransactionDetail::where('user_id', Auth::user()->userId)->orderBy('trans_date', 'DESC')->pluck('balance');
        $user_id = $orderResponse['CUSTID'];
        $userBalance = User::where('userId', $user_id)->get();
        $role_id = $userBalance[0]['roleId'];

        if (isset($userBalance) && count($userBalance)) {
            $userBalance = $userBalance[0]['wallet_balance'];
        } else {
            $userBalance = 0;
        }

        $smsData['last_balance_amount'] = $userBalance;

        if ($paymentStatus == "SUCCESS") {
            $balance = (((float) $orderResponse['TXNAMOUNT']) + ($userBalance ? (float) $userBalance : 0));
        } else {
            $balance = (float) $userBalance;
        }
        
        $gateway_mode = '';   
        if(isset($orderResponse['PAYMENTMODE'])){
            $chargeDtl = PymtGtwayMdChargeDtl::where('code', $orderResponse['PAYMENTMODE'])->get()->first();
            $gateway_mode = $chargeDtl->mode;
        }
        
        $walletResponse = WalletTransactionDetail::create([
            'order_id' => isset($orderResponse['ORDERID']) ? $orderResponse['ORDERID'] : '',
            'user_id' => $user_id,
            'transaction_status' => isset($orderResponse['STATUS']) ? $orderResponse['STATUS'] : '',
            'response_msg' => isset($orderResponse['RESPMSG']) ? $orderResponse['RESPMSG'] : '',
            'bank_trans_id' => isset($orderResponse['BANKTXNID']) ? $orderResponse['BANKTXNID'] : '',
            'transaction_type' => $paymentStatus == "SUCCESS" ? 'CREDIT' : '',
            'transaction_id' => isset($orderResponse['TXNID']) ? $orderResponse['TXNID'] : '',
            'trans_date' => isset($orderResponse['TXNDATE']) ? $orderResponse['TXNDATE'] : '',
            'payment_type' => Config::get('constants.PAYMENT_TYPE.PAYMT_WALLET'),
            'payment_mode' =>  $paymentStatus == "SUCCESS" ?  Config::get('constants.PAYMENT_GTWAY_TYPE.PAYMT_GATEWAY') : Config::get('constants.PAYMENT_GTWAY_TYPE.TRN_FAILURE'),
            'gateway_mode' => $gateway_mode,
            'total_amount' => isset($orderResponse['TXNAMOUNT']) ? $orderResponse['TXNAMOUNT'] : '',
            'balance' => $balance,
        ]);
        $debitChrgTypeAmt = $chargeDtl->charge_type == "RS" ? $chargeDtl->charge : ($chargeDtl->charge / 100 * $walletResponse->total_amount);
        
        $pgResponse = DB::table('tbl_payment_gateway_report')->insert([
            'user_id' => $user_id,
            'role_id' => $role_id,
            'order_id' => isset($orderResponse['ORDERID']) ? $orderResponse['ORDERID'] : '',
            'transaction_id' => isset($orderResponse['TXNID']) ? $orderResponse['TXNID'] : '',
            'transaction_status' => isset($orderResponse['STATUS']) ? $orderResponse['STATUS'] : '',
            'response_msg' => isset($orderResponse['RESPMSG']) ? $orderResponse['RESPMSG'] : '',
            'bank_trans_id' => isset($orderResponse['BANKTXNID']) ? $orderResponse['BANKTXNID'] : '',
            'total_amount' => isset($orderResponse['TXNAMOUNT']) ? $orderResponse['TXNAMOUNT'] : '',
            'charges' => $debitChrgTypeAmt,
            'trans_date' => isset($orderResponse['TXNDATE']) ? $orderResponse['TXNDATE'] : '',
            'payment_status' => isset($orderResponse['STATUS']) ? $orderResponse['STATUS'] : '',
            'payment_mode' =>  $gateway_mode
        ]);

        if ($paymentStatus == "SUCCESS" && $walletResponse) {
            $user = User::find((int) $user_id);
            $smsData['amount'] = $walletResponse->total_amount;
            $smsData['updated_balance_amount'] = $balance;
            $smsData['mobile'] = $user->mobile;

            $user->wallet_balance = (float) $balance;
            $userUpdresponse = $user->save();

            $deductRes = null;
            if($userUpdresponse && $walletResponse){
                $walletResponse['pymt_gt_payment_mode'] =  isset($orderResponse['PAYMENTMODE']) ? $orderResponse['PAYMENTMODE'] : '';
                $response = $this->deductPymtGtwayCharge($walletResponse, $user);
            }

            $this->sendSmswithTransactionInfo($smsData);
            $this->notifyWithTransactionInfo($smsData, $user->userId);

        } else {
            $response = $walletResponse;
        }

        return $response;
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
                        'transaction_status' => isset($walletResponse->transaction_status) ? $walletResponse->transaction_status : '',
                        'response_msg' => isset($walletResponse->response_msg) ? $walletResponse->response_msg : '',
                        'bank_trans_id' => isset($walletResponse->bank_trans_id) ? $walletResponse->bank_trans_id : '',
                        'transaction_type' => "DEBIT",
                        'transaction_id' => isset($walletResponse->transaction_id) ? $walletResponse->transaction_id : '',
                        'trans_date' => isset($walletResponse->trans_date) ?  Carbon::parse($walletResponse->trans_date)->addSeconds(1) : '',
                        'payment_type' => Config::get('constants.PAYMENT_TYPE.PAYMT_WALLET'),
                        'payment_mode' => Config::get('constants.PAYMENT_GTWAY_TYPE.PYMT_GTWY_CHRG'),
                        'gateway_mode' => $chargeDtl->mode,
                        'total_amount' => $debitChrgTypeAmt,
                        'balance' => $user->wallet_balance,
                    ]);

                    $response = $walletResponse;
                }
            }
        }

        return $response;
    }

    /**
     * Add money from payment gateway API
     */
    public function addMoneyPymtGtWayApi_old(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'user_id' => 'required',
            'transaction_status' => 'required|string',
            'response_msg' => 'required|string',
            'bank_trans_id' => 'required',
            'transaction_id' => 'required',
            'trans_date' => 'required',
            'total_amount' => 'required',
            'payment_status' => 'required',
            'payment_mode' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->messages()->first());
        }

        if(isset($request->payment_mode) && $request->payment_mode){
            $chargeDtl = PymtGtwayMdChargeDtl::where('code', $request->payment_mode)->get()->first();

            if(!$chargeDtl){
                return $this->sendError("Invalid Payment Mode!!");
            }
        }


        $smsData = [];
        $response = null;
        $balance = 0;

        // $userBalance = WalletTransactionDetail::where('user_id', $request->user_id)->orderBy('trans_date', 'DESC')->pluck('balance');
        $userBalance = User::where('userId', $request->user_id)->get();
        if (isset($userBalance) && count($userBalance)) {
            $userBalance = $userBalance[0]['wallet_balance'];
        } else {
            $userBalance = 0;
        }

        $smsData['last_balance_amount'] = $userBalance;

        if ($request->payment_status == "SUCCESS" || $request->payment_status == "TXN_SUCCESS") {
            $balance = (((float) $request->total_amount) + ($userBalance ? (float) $userBalance : 0));
        } else {
            $balance = (float) $userBalance;
        }

        $smsData['updated_balance_amount'] = $balance;
        $smsData['amount'] = $request->total_amount;

        $userMobNo = User::where('userId', $request->user_id)->pluck('mobile')->first();
        if ($userMobNo) {
            $smsData['mobile'] = $userMobNo;
        }

        $walletResponse = WalletTransactionDetail::create([
            'order_id' => $request->order_id,
            'user_id' => $request->user_id,
            'transaction_status' => $request->transaction_status,
            'response_msg' => $request->response_msg,
            'bank_trans_id' => $request->bank_trans_id,
            'transaction_type' => $request->payment_status == "TXN_SUCCESS" ? 'CREDIT' : '',
            'transaction_id' => $request->transaction_id,
            'trans_date' => $request->trans_date,
            'payment_type' => Config::get('constants.PAYMENT_TYPE.PAYMT_WALLET'),
            'payment_mode' => Config::get('constants.PAYMENT_GTWAY_TYPE.PAYMT_GATEWAY'),
            'total_amount' => $request->total_amount,
            'balance' => $balance,
        ]);
       

        if ( ($request->payment_status == "SUCCESS" || $request->payment_status == "TXN_SUCCESS") && $walletResponse) {
            $user = User::find((int) $request->user_id);
            $user->wallet_balance = (float) $balance;
            $userUpdresponse = $user->save();

            $deductRes = null;
            if($userUpdresponse && $walletResponse){
                $walletResponse['pymt_gt_payment_mode'] =  isset($request['payment_mode']) ? $request['payment_mode'] : '';
                $response = $this->deductPymtGtwayCharge($walletResponse, $user);
            }
        } else {
            $response = $walletResponse;
        }

        if ($response) {
            $success["success"] = "Success!!";
            $statusMsg = "Transaction updated successfully!!";
            $msgRes = $this->sendSmswithTransactionInfo($smsData);
            if ($msgRes) {
                $this->sendSms($msgRes, $smsData['mobile']);
                return $this->sendSuccess($success, $statusMsg);
            }
        } else {
            $this->sendError("Failed to update transaction!!");
        }
    }
    public function addMoneyPymtGtWayApi(Request $request)
    {
        //return $this->sendError("Transaction already processed!!");
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'user_id' => 'required',
            'transaction_status' => 'required|string',
            'response_msg' => 'required|string',
            'bank_trans_id' => 'required',
            'transaction_id' => 'required',
            'trans_date' => 'required',
            'total_amount' => 'required',
            'payment_status' => 'required',
            'payment_mode' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->messages()->first());
        }

        Log::info('PayloadApi: '.json_encode($request));
        $check_txn = WalletTransactionDetail::where('order_id', $request->order_id)->get();
        if(isset($check_txn) && count($check_txn)) {
            return $this->sendError("Transaction already processed!!");
        }
       
        if(isset($request->payment_mode) && $request->payment_mode){
            $chargeDtl = PymtGtwayMdChargeDtl::where('code', $request->payment_mode)->get()->first();

            if(!$chargeDtl){
                return $this->sendError("Invalid Payment Mode!!");
            }
        }


        $smsData = [];
        $response = null;
        $balance = 0;

        // $userBalance = WalletTransactionDetail::where('user_id', $request->user_id)->orderBy('trans_date', 'DESC')->pluck('balance');
        $userBalance = User::where('userId', $request->user_id)->get();
        if (isset($userBalance) && count($userBalance)) {
            $userBalance = $userBalance[0]['wallet_balance'];
        } else {
            $userBalance = 0;
        }
        

        $smsData['last_balance_amount'] = $userBalance;

        if ( $request->payment_status == "TXN_SUCCESS") {
            $balance = (((float) $request->total_amount) + ($userBalance ? (float) $userBalance : 0));
        } else {
            $balance = (float) $userBalance;
        }
       
        $smsData['updated_balance_amount'] = $balance;
        $smsData['amount'] = $request->total_amount;

        $userMobNo = User::where('userId', $request->user_id)->pluck('mobile')->first();
        if ($userMobNo) {
            $smsData['mobile'] = $userMobNo;
        }
        
        
        $walletResponse = WalletTransactionDetail::create([
            'order_id' => $request->order_id,
            'user_id' => $request->user_id,
            'transaction_status' => $request->transaction_status,
            'response_msg' => $request->response_msg,
            'bank_trans_id' => $request->bank_trans_id,
            'transaction_type' => $request->payment_status == "TXN_SUCCESS" ? 'CREDIT' : '',
            'transaction_id' => $request->transaction_id,
            'trans_date' => $request->trans_date,
            'payment_type' => Config::get('constants.PAYMENT_TYPE.PAYMT_WALLET'),
            'payment_mode' => Config::get('constants.PAYMENT_GTWAY_TYPE.PAYMT_GATEWAY'),
            'total_amount' => $request->total_amount,
            'balance' => $balance,
        ]);

        if ( ( $request->payment_status == "TXN_SUCCESS")  && $walletResponse) {
           
            $user = User::find((int) $request->user_id);
            
            $user->wallet_balance = (float) $balance;
            $userUpdresponse = $user->save();
          
            $deductRes = null;
            // if($userUpdresponse && $walletResponse){
            //     $walletResponse['pymt_gt_payment_mode'] =  isset($request['payment_mode']) ? $request['payment_mode'] : '';
            //     $response = $this->deductPymtGtwayCharge($walletResponse, $user);
            // }
            $chargeType = PymtGtwayMdChargeDtl::where('code', $request->payment_mode)->get();
            $pymt_gtwy_report = PaymentGatewayReport::create([
                                                                'user_id' => $request->user_id,
                                                                'role_id' => $request->role_id,
                                                                'order_id' => $request->order_id,
                                                                'transaction_id' => $request->transaction_id,
                                                                'transaction_status' => $request->transaction_status,
                                                                'response_msg' => $request->response_msg,
                                                                'bank_trans_id' => $request->bank_trans_id,
                                                                'total_amount' => $request->total_amount,
                                                                'trans_date' => $request->trans_date,
                                                                'payment_status' => $request->payment_status,
                                                                'payment_mode' => $chargeType[0]['mode']   
                                                            ]);
            // $add_charges = $this->addCharges($request);

                $response = $pymt_gtwy_report;
        } else {
           
            $response = $walletResponse;
        }
       
       
        if ($response) {
            $success["success"] = "Success!!";
            $statusMsg = "Transaction updated successfully!!";
            $msgRes = $this->sendSmswithTransactionInfo($smsData);
            if ($msgRes) {
                $this->sendSms($msgRes, $smsData['mobile']);
                $this->notifyWithTransactionInfo($smsData, $request->user_id);

                return $this->sendSuccess($success, $statusMsg);
            }
        } else {
            $this->sendError("Failed to update transaction!!");
        }
    }

    public function addCharges($request)
    {
        if (isset($request->payment_mode)) {
            $chargeDtl = PymtGtwayMdChargeDtl::where('code', $request->payment_mode)->get()->first();

            if ($chargeDtl) {
                $debitChrgTypeAmt = $chargeDtl->charge_type == "RS" ? $chargeDtl->charge : ($chargeDtl->charge / 100 * $request->total_amount);

                $update_charges =  PaymentGatewayReport::where('order_id', $request->order_id)->update(['charges'=>$debitChrgTypeAmt]);
            }
        }

        return true;
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

    public function paymentGatewayReport(Request $request){
        
        $report = $this->filter($request);
        $report = $this->modifyReport($report);
        $filtersList = Config::get('constants.PAYMENT_GATEWAY_FILTER');
        $reportTH = Config::get('constants.PAYMENT_GATEWAY_REPORT');

        $export_file_name = 'payment_Gateway_Report' . date('Y_m_d_H:i:s');
        if (isset($request->is_export) && $request->is_export == 1) {
            $response = $this->exportPDF($export_file_name, $reportTH, $report);
            return $response;
        }
        $total_amt = $this->calcTotalCharges($report);
        // print_r($total_amt);
        // exit();
        return view('modules.payment.payment_gateway_report', compact('report', 'reportTH', 'filtersList', 'request', 'total_amt'));

    }

    public function paymentGatewayCharges(Request $request){
        $result = [];
        $chargeList = PymtGtwayMdChargeDtl::get();   
        if(count($chargeList) > 0){
            foreach ($chargeList as $key => $value) {
                $result[$key]['charge_type'] = $value->mode;
               if($value->charge_type == 'RS'){ 
                // $result[$key]['charge'] = $value->charge_type." ".$value->charge;
                $result[$key]['charge'] = "Rs ".$value->charge;
               }else{
                    $result[$key]['charge'] =$value->charge." ".$value->charge_type;
               }
            }
        }
                
        return $this->sendSuccess($result, "Success!!");
    }
    
    public function paymentGatewayLimits(Request $request){
       
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'token' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->messages()->first());
        }
        
        $userBalance = User::where('userId', $request->user_id)->get();
        if (isset($userBalance) && count($userBalance)) {
            $user = $userBalance[0];
            $result['max_amount'] = $userBalance[0]['max_amount_deposit'];
            $result['min_amount'] = $userBalance[0]['min_amount_deposit'];
            return $this->sendSuccess($result, "Success!!");
        }
        return $this->sendError("Not Found!!");
    }

    public function modifyReport($report){
        $result = [];
        if(count($report)>0){
            foreach ($report as $key => $value) {
                $result[$key]['order_id'] = $value['order_id'];
                $result[$key]['store_name'] = $value['store_name'];
                $result[$key]['transaction_status'] = $value['transaction_status'];
                $result[$key]['transaction_id'] = $value['transaction_id'];
                $result[$key]['trans_date'] = $value['trans_date'];
                $result[$key]['gateway_mode'] = $value['gateway_mode'];
                $result[$key]['total_amount'] = $value['total_amount'];
            }
        }

        return $result;
    }

    public function filter($request)
    {
        $report = WalletTransactionDetail::where('payment_mode', Config::get('constants.PAYMENT_GTWAY_TYPE.PAYMT_GATEWAY'))
                                            // ->where('transaction_status', 'SUCCESS')
                                            ->where('transaction_type', 'CREDIT')
                                            ->leftJoin('tbl_users', 'tbl_wallet_trans_dtls.user_id', '=', 'tbl_users.userId')
                                            ->orderBy('trans_date', 'DESC');
                                           
        
        if ($request->has('from_date') && isset($request->from_date)) {
            $fromDate = $request->get('from_date');
            $report->whereDate('trans_date', '>=', $fromDate);
        } else {
            $fromDate = now();
            $report->whereDate('trans_date', $fromDate);
        }

        if ($request->has('to_date') && isset($request->to_date)) {
            $toDate = $request->get('to_date');
            $report->whereDate('trans_date', '<=', $toDate);
        }
        $report = $report->get();
        return $report;
    }

     /**
     * Export PDF
     */
    public function exportPDF($fileName, $tableHead, $tableBody)
    {
        $pdf = PDF::loadView('export.pdf', compact('fileName', 'tableHead', 'tableBody'));
        $pdf->setPaper('A4', 'landscape');
        $response = $pdf->download($fileName . '.pdf');
        return $response;
    }

    public function calcTotalCharges($report){
        $totalAmt=0.00;

        if(count($report)>0){
            foreach ($report as $key => $value) {
                $totalAmt += isset($value['total_amount']) ? ((float) $value['total_amount']) : 0;
            }
        }

        return $totalAmt;
    }

    
    public function getPaymentGatewayReportAPI(Request $request){
        $payment_report = PaymentGatewayReport::where('user_id',  $request->user_id);

        if ($request->has('from_date') && isset($request->from_date)) {
            $fromDate = $request->get('from_date');
            $payment_report->whereDate('trans_date', '>=', $fromDate);
        } else {
            $fromDate = date('Y-m-d');
            $payment_report->whereDate('trans_date', '>=', $fromDate);
        }

        if ($request->has('to_date') && isset($request->to_date)) {
            $toDate = $request->get('to_date');
            $payment_report->whereDate('trans_date', '<=', $toDate);
        }
        
        //payment mode should be "CARD","UPI","NET BANKING","WALLET"
        if ($request->has('mode') && isset($request->mode)) {
            $mode = $request->get('mode');
            $payment_report->where('payment_mode', strtoupper($mode));
        }
        
        //payment status should be "SUCCESS","FAILED","PENDING","REFUND"
        if ($request->has('status') && isset($request->status)) {
            $status = $request->get('status');
            $payment_report->where('payment_status', $status);
        }
        $payment_report = $payment_report->get()->toJson();
        foreach(json_decode($payment_report,true) as $data) {
            $response[] = array(
                'id'=>$data['id'],
                'user_id'=>$data['user_id'],
                'role_id'=>$data['role_id'],
                'order_id'=>$data['order_id'],
                'transaction_id'=>$data['transaction_id'],
                'transaction_status'=>$data['transaction_status'],
                'response_msg'=>$data['response_msg'],
                'bank_trans_id'=>$data['bank_trans_id'],
                'total_amount'=>$data['total_amount'],
                'charges'=>$data['charges'],
                'trans_date'=>$data['trans_date'],
                'payment_status' => $data['payment_status'],
                'payment_mode' => $data['payment_mode'],
                'name'=>"CREDIT (".$data['payment_mode'].")",
                'final_amount'=>$data['total_amount'] - $data['charges'],
            );
        }
        if(count(json_decode($payment_report,true))>0){
            $statusMsg = "Success!!";

            return $this->sendSuccess($response, $statusMsg);

        }
           return $this->sendError("Not Found!!");

    }
    
    public function getVirtualAccountReportAPI(Request $request){
        $payment_report = VirtualTransactionDetail::where('user_id',  $request->user_id);

        if ($request->has('from_date') && isset($request->from_date)) {
            $fromDate = $request->get('from_date');
            $payment_report->whereDate('trans_date', '>=', $fromDate);
        } else {
            $fromDate = date('Y-m-d');
            $payment_report->whereDate('trans_date', '>=', $fromDate);
        }

        if ($request->has('to_date') && isset($request->to_date)) {
            $toDate = $request->get('to_date');
            $payment_report->whereDate('trans_date', '<=', $toDate);
        }
        
        //payment type should be "UPI","BANK"
        $mode = "BANK";
        $payment_report->where('payment_type', strtoupper($mode));
        
        if ($request->has('to_date') && isset($request->to_date)) {
            $toDate = $request->get('to_date');
            $payment_report->whereDate('trans_date', '<=', $toDate);
        }

        $payment_report = $payment_report->get()->toJson();
        // print_r($payment_report);die();
        foreach(json_decode($payment_report,true) as $data) {
            $response[] = array(
                'id'=>$data['id'],
                'user_id'=>$data['user_id'],
                'order_id'=>$data['order_id'],
                'transaction_id'=>$data['transaction_id'],
                'transaction_status'=>$data['transaction_status'],
                'response_msg'=>'',
                'bank_trans_id'=>$data['bank_trans_id'],
                'total_amount'=>$data['total_amount'],
                'charges'=>$data['charge_amount'],
                'trans_date'=>$data['trans_date'],
                'payment_status' => $data['transaction_status'],
                'payment_mode' => $data['payment_mode'],
                'name'=>$data['transaction_type'] . " (".$data['payment_type'].")",
            );
        }
        if(count(json_decode($payment_report,true))>0){
            $statusMsg = "Success!!";

            return $this->sendSuccess($response, $statusMsg);

        }
           return $this->sendError("Not Found!!");

    }


    public function geQrCodeReportAPI(Request $request){
        $payment_report = VirtualTransactionDetail::where('user_id',  $request->user_id);

        if ($request->has('from_date') && isset($request->from_date)) {
            $fromDate = $request->get('from_date');
            $payment_report->whereDate('trans_date', '>=', $fromDate);
        } else {
            $fromDate = date('Y-m-d');
            $payment_report->whereDate('trans_date', '>=', $fromDate);
        }

        if ($request->has('to_date') && isset($request->to_date)) {
            $toDate = $request->get('to_date');
            $payment_report->whereDate('trans_date', '<=', $toDate);
        }
        
        //payment type should be "UPI","BANK"
        $mode = "UPI";
        $payment_report->where('payment_type', strtoupper($mode));
        
        if ($request->has('to_date') && isset($request->to_date)) {
            $toDate = $request->get('to_date');
            $payment_report->whereDate('trans_date', '<=', $toDate);
        }

        $payment_report = $payment_report->get()->toJson();
        // print_r($payment_report);die();
        foreach(json_decode($payment_report,true) as $data) {
            $response[] = array(
                'id'=>$data['id'],
                'user_id'=>$data['user_id'],
                'order_id'=>$data['order_id'],
                'transaction_id'=>$data['transaction_id'],
                'transaction_status'=>$data['transaction_status'],
                'response_msg'=>'',
                'bank_trans_id'=>$data['bank_trans_id'],
                'total_amount'=>$data['total_amount'],
                'charges'=>$data['charge_amount'],
                'trans_date'=>$data['trans_date'],
                'payment_status' => $data['transaction_status'],
                'payment_mode' => $data['payment_mode'],
                'name'=>$data['transaction_type'] . " (".$data['payment_type'].")",
            );
        }
        if(count(json_decode($payment_report,true))>0){
            $statusMsg = "Success!!";

            return $this->sendSuccess($response, $statusMsg);

        }
           return $this->sendError("Not Found!!");

    }


    public function getPaymentGatewayReport(Request $request){
        $report = PaymentGatewayReport::orderBy('trans_date', 'DESC');

        if(Auth::user()->roleId != Config::get('constants.ADMIN')){
           $report = $report->where('user_id', Auth::user()->userId);
        }

        if ($request->has('from_date') && isset($request->from_date)) {
            $fromDate = $request->get('from_date');
            $report->whereDate('trans_date', '>=', $fromDate);
        } else {
            $fromDate = date('Y-m-d');
            $report->whereDate('trans_date', '>=', $fromDate);
        }

        if ($request->has('to_date') && isset($request->to_date)) {
            $toDate = $request->get('to_date');
            $report->whereDate('trans_date', '<=', $toDate);
        } else {
            $toDate = date('Y-m-d');
            $report->whereDate('trans_date', '<=', $toDate);
        }

        if ($request->has('filter_payment_mode') && isset($request->filter_payment_mode)) {
            
            $report->where('payment_mode', $request->filter_payment_mode);
        }

        $report = $report->get();
        $report = (count($report) > 0) ? $report :[];

        $filtersList = Config::get('constants.USER_PAYMENT_GATEWAY_FILTER');
        $reportTH = Config::get('constants.USER_PAYMENT_GATEWAY_REPORT');

        if(Auth::user()->roleId == Config::get('constants.ADMIN')){
           $report =  $this->setUserInfo($report);
        }




        $export_file_name = 'payment_Gateway_Report' . date('Y_m_d_H:i:s');
        if (isset($request->is_export) && $request->is_export == 1) {
            $response = $this->exportPDF($export_file_name, $reportTH, $payment_report);
            return $response;
        }
        $total_amt = $this->calcTotalUserPaymentgatewayReport($report);
        // $total_amt = 0;

        $payment_charges = PymtGtwayMdChargeDtl::get();
        // $payment_charges = ['mode'=>'CREDIT_CARD','mode'=>'DEBIT_CARD','mode'=>'NET_BANKING','mode'=>'UPI','mode'=>'WALLET'];

        return view('modules.payment.user_payment_gateway_report', compact('report', 'reportTH', 'filtersList', 'request', 'total_amt', 'payment_charges'));
        // if(count($payment_report)>0){
        //     $statusMsg = "Success!!";

        //     return $this->sendSuccess($payment_report, $statusMsg);

        // }
        //    return $this->sendError("Not Found!!");

    }
    
    public function getVirtualAccountReport(Request $request){
        $report = VirtualTransactionDetail::orderBy('trans_date', 'DESC');

        if(Auth::user()->roleId != Config::get('constants.ADMIN')){
            
           $report = $report->where('user_id', Auth::user()->userId);
        }

        if ($request->has('from_date') && isset($request->from_date)) {
            $fromDate = $request->get('from_date');
            $report->whereDate('trans_date', '>=', $fromDate);
        } else {
            $fromDate = date('Y-m-d');
            $report->whereDate('trans_date', '>=', $fromDate);
        }

        if ($request->has('to_date') && isset($request->to_date)) {
            $toDate = $request->get('to_date');
            $report->whereDate('trans_date', '<=', $toDate);
        } else {
            $toDate = date('Y-m-d');
            $report->whereDate('trans_date', '<=', $toDate);
        }

        $report->where('payment_type',"BANK");

        $report = $report->get();
        $report = (count($report) > 0) ? $report :[];

        $filtersList = Config::get('constants.USER_PAYMENT_GATEWAY_FILTER');
        $reportTH = Config::get('constants.USER_VIRTUAL_ACCOUNT_REPORT');

        if(Auth::user()->roleId == Config::get('constants.ADMIN')){
          $report =  $this->setUserInfo($report);
        }

        $export_file_name = 'virtual_account_Report' . date('Y_m_d_H:i:s');
        if (isset($request->is_export) && $request->is_export == 1) {
            $response = $this->exportPDF($export_file_name, $reportTH, $payment_report);
            return $response;
        }
        $total_amt = $this->calcTotalUserPaymentgatewayReport($report);
        // $total_amt = 0;

        $payment_charges[0]['mode'] = 'BANK';
        $payment_charges[1]['mode'] = 'UPI';
        return view('modules.payment.user_virtual_account_report', compact('report', 'reportTH', 'filtersList', 'request', 'total_amt', 'payment_charges'));
    }
    
    public function getQrCodeReport(Request $request){
        $report = VirtualTransactionDetail::orderBy('trans_date', 'DESC');
        // print_r(json_encode($report));die();

        if(Auth::user()->roleId != Config::get('constants.ADMIN')){
            
           $report = $report->where('user_id', Auth::user()->userId);
        }

        if ($request->has('from_date') && isset($request->from_date)) {
            $fromDate = $request->get('from_date');
            $report->whereDate('trans_date', '>=', $fromDate);
        } else {
            $fromDate = date('Y-m-d');
            $report->whereDate('trans_date', '>=', $fromDate);
        }

        if ($request->has('to_date') && isset($request->to_date)) {
            $toDate = $request->get('to_date');
            $report->whereDate('trans_date', '<=', $toDate);
        } else {
            $toDate = date('Y-m-d');
            $report->whereDate('trans_date', '<=', $toDate);
        }

            
        $report->where('payment_type',"UPI");

        $report = $report->get();
        $report = (count($report) > 0) ? $report :[];

        $filtersList = Config::get('constants.USER_PAYMENT_GATEWAY_FILTER');
        $reportTH = Config::get('constants.USER_VIRTUAL_ACCOUNT_REPORT');

        if(Auth::user()->roleId == Config::get('constants.ADMIN')){
          $report =  $this->setUserInfo($report);
        }

        $export_file_name = 'qr_code_report' . date('Y_m_d_H:i:s');
        if (isset($request->is_export) && $request->is_export == 1) {
            $response = $this->exportPDF($export_file_name, $reportTH, $payment_report);
            return $response;
        }
        $total_amt = $this->calcTotalUserPaymentgatewayReport($report);
        // $total_amt = 0;

        $payment_charges[0]['mode'] = 'BANK';
        $payment_charges[1]['mode'] = 'UPI';
        return view('modules.payment.user_qr_code_report', compact('report', 'reportTH', 'filtersList', 'request', 'total_amt', 'payment_charges'));
    }
        
    
    public function setUserInfo($records){
        if (count($records)>0) {
            foreach ($records as $key => $value) {
                $records[$key]['store_name'] = User::getStoreNameById($value->user_id);
                $records[$key]['username'] = User::getUsernameById($value->user_id);
                $records[$key]['mobile'] = User::getMobileById($value->user_id);
            }
        }

        return $records;
    }
    public function calcTotalUserPaymentgatewayReport($report){
        $totalAmt=0.00;
        $totalCharge = 0.00;
        if(count($report)>0){
            foreach ($report as $key => $value) {
                $totalAmt += isset($value['total_amount']) ? ((float) $value['total_amount']) : 0;
                $totalCharge += isset($value['charges']) ? ((float) $value['charges']) : 0;
            }
        }
        $total= [
            'total_amount' => $totalAmt,
            'total_charges' => $totalCharge
        ];
        return $total;
    }

    public function chargesSetting(Request $request){
       
        $all_charges = DB::table('tbl_pymt_gtway_md_chrge_dtls')->get();
        $all_charges = (count($all_charges)>0) ? $all_charges : [];

      
        return view('modules.payment.payment_gateway_charges', compact('all_charges'));

    }
    public function updateChargesSetting(Request $request){

        $update_charge = DB::table('tbl_pymt_gtway_md_chrge_dtls')->where('id', $request->charge_id)
                                ->update(['charge'=> $request->charges,
                                            'charge_type'=> $request->charge_type]);
        if ($update_charge) {
            return redirect('/charges_setting')->with('success', 'Charges Updated !!');
        }
        return redirect('/charges_setting')->with('error', 'Charges Not Updated !!');

    }
    
    public function payCashfree(Request $request) {
        
        $validator = Validator::make($request->all(), [
            'pay_mode' => 'required',
            'pay_amount' => 'required',
            '_token' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('merror', $validator->messages()->first());
        }
        
        if(Auth::user()->min_amount_deposit > $request->pay_amount) {
            return redirect()->back()->with('merror', 'Minimum Deposit amount is Rs. '.Auth::user()->min_amount_deposit);
        }
        if(Auth::user()->max_amount_deposit < $request->pay_amount) {
            return redirect()->back()->with('merror', 'Maximum Deposit amount is Rs. '.Auth::user()->max_amount_deposit);
        }
        $order = round(microtime(true) * 1000);
        $returnUrl = URL::to('payment/status?order_id={order_id}&order_token={order_token}');
        $cashfree_key = DB::table('tbl_payment_gateway_integation')->where('payment_gateway_name', 'CASHFREE')->get()->first();
        $appId = $cashfree_key->merchant_id;
        $secretKey = $cashfree_key->working_key;
        if($cashfree_key->environment == 'production') {
            $mode = "PROD";
        } else {
            $mode = "TEST";
        }
        $customerName = Auth::user()->first_name . " " . Auth::user()->last_name;
        $customerEmail = Auth::user()->email;
        $customerPhone = Auth::user()->mobile;
        
        $pay_mode = $request->pay_mode;
        $paymode = '';
        if($pay_mode == "debit_card") {
            $paymode = 'dc';
        } elseif($pay_mode == "rupay_card") {
            $paymode = 'dc';
        } elseif($pay_mode == "credit_card") {
            $paymode = 'cc';
        } elseif($pay_mode == "net_banking") {
            $paymode = 'nb';
        } elseif($pay_mode == "upi") {
            $paymode = 'upi';
        } elseif($pay_mode == "wallet") {
            $paymode = 'app';
        } elseif($pay_mode == "corporate_card") {
            $paymode = 'ccc';
        } elseif($pay_mode == "prepaid_card") {
            $paymode = 'ppc';
        } else {
            return redirect()->back()->with('merror', "Please select valid payment mode");
        }
        if ($mode == "PROD") {
          $url = "https://api.cashfree.com/pg/orders";
        } else {
          $url = "https://sandbox.cashfree.com/pg/orders";
        }
        
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'x-api-version' => '2021-05-21',
            'x-client-id' => $appId,
            'x-client-secret' => $secretKey
        ])->post($url,[
            'order_id' => "$order",
            'order_amount' => $request->pay_amount,
            'order_currency' => 'INR',
            'customer_details' => [
                'customer_id' => Auth::user()->username,
                'customer_email' => $customerEmail,
                'customer_phone' => $customerPhone
            ],
            'order_meta' => [
                'return_url' => $returnUrl,
                'payment_methods' => $paymode
            ]
        ])->json();
        // Log::info('CASHFREE REQUEST: '.json_encode($response));
        // return $returnUrl;
        // return $response;
        if(isset($response['payment_link'])) {
            return redirect($response['payment_link']);
        }
        return redirect()->back()->with('merror', 'Something went wrong!');
    }
    
    public function tester1($orderId) {
        $appId = "154737df6e013a956a8cb5e931737451";
        $secretKey = "89a6bb78c3a13270bc8411b7936422d690b7428";
        $baseurl = 'https://api.cashfree.com/pg/orders/';
        $mode = "PROD";
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'x-api-version' => '2021-05-21',
            'x-client-id' => $appId,
            'x-client-secret' => $secretKey
        ])->get($baseurl.$orderId.'/payments')->json();
        return $response;
    }
    
    public function paymentCallback(Request $request) {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect('online_payment')->with('merror', "Error from Payment Partner");
        }
        
        $orderId = $request->order_id;
        $check_txn = PaymentGatewayReport::where('transaction_id', $orderId)->get();
        if(isset($check_txn) && count($check_txn)) {
            // return redirect('online_payment')->with('merror', "This transaction have already been processed");
            return redirect('home');
        }
        
        $cashfree_key = DB::table('tbl_payment_gateway_integation')->where('payment_gateway_name', 'CASHFREE')->get()->first();
        $appId = $cashfree_key->merchant_id;
        $secretKey = $cashfree_key->working_key;
        if($cashfree_key->environment == 'production') {
            $mode = "PROD";
            $baseurl = "https://api.cashfree.com/pg/orders/";
        } else {
            $mode = "TEST";
            $baseurl = "https://sandbox.cashfree.com/pg/orders/";
        }
        
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'x-api-version' => '2021-05-21',
            'x-client-id' => $appId,
            'x-client-secret' => $secretKey
        ])->get($baseurl.$orderId.'/payments')->json();
        Log::info('CASHFREE ORDER DATA : '.json_encode($response));
        // return $response;
        $charge_mode = "Charge mode error";
        $method = "";
        if(isset($response[0]['cf_payment_id']) && $response[0]['payment_status'] == 'SUCCESS') {
            if(array_key_exists("card",$response[0]['payment_method'])) {
                $method = $response[0]['payment_method']['card']['card_number'];
                if($response[0]['payment_method']['card']['card_type'] == 'credit_card') {
                    $charge_mode = "CREDIT_CARD";
                } elseif($response[0]['payment_method']['card']['card_type'] == 'debit_card') {
                    $charge_mode = "DEBIT_CARD";
                    if($response[0]['payment_method']['card']['card_network'] == 'rupay') {
                        $charge_mode = "RUPAY_CARD";
                    }
                } elseif($response[0]['payment_method']['card']['card_type'] == 'corporate_credit_card') {
                    $charge_mode = "CORPORATE_CARD";
                } elseif($response[0]['payment_method']['card']['card_type'] == 'prepaid_card') {
                    $charge_mode = "PREPAID_CARD";
                }
            } elseif(array_key_exists("upi",$response[0]['payment_method'])) {
                $method = 'QRCODE';
                if(isset($response[0]['payment_method']['upi']['upi_id'])) {
                    $method = $response[0]['payment_method']['upi']['upi_id'];
                }
                $charge_mode = "UPI";
            } elseif(array_key_exists("netbanking",$response[0]['payment_method'])) {
                $charge_mode = "NET_BANKING";
                if(isset($response[0]['payment_method']['netbanking']['netbanking_bank_name'])) {
                    $method = $response[0]['payment_method']['netbanking']['netbanking_bank_name'];
                }
            } elseif(array_key_exists("app",$response[0]['payment_method'])) {
                $charge_mode = "WALLET";
                if(isset($response[0]['payment_method']['app']['channel'])) {
                    $method = $response[0]['payment_method']['app']['channel'];
                }
            } elseif(array_key_exists("others",$response[0]['payment_method'])) {
                $method = $response[0]['payment_method']['others']['card_number'];
                if($response[0]['payment_method']['others']['payment_mode'] == 'CORPORATE_CREDIT_CARD' || $response[0]['payment_group'] == 'corporate_credit_card') {
                    $charge_mode = "CORPORATE_CREDIT_CARD";
                }
            }
            
            if($method == "") {
                $method = $charge_mode;
            } else {
                $method = strtoupper($method);
            }
            
            // die("CHARGE MODE => ".$charge_mode);
            
            $order_id = $this->createOrderID();
            $amount = $response[0]['payment_amount'];
            $userId = Auth::user()->userId;
            $user = User::find((int) $userId);
            
            if(!$user) {
                return redirect('online_payment')->with('merror', "Signature Mismatch!");
            }
            
            $user_id = $user->userId;
            $userBalance = $user->wallet_balance;
            $smsData = [];
    
            $smsData['last_balance_amount'] = $userBalance;
            $balance = (((float) $amount) + ($userBalance ? (float) $userBalance : 0));
            $smsData['updated_balance_amount'] = $balance;
            $smsData['amount'] = $amount;
            $smsData['mobile'] = $user->mobile;
    
            $walletResponse = WalletTransactionDetail::create([
                'order_id' => $order_id,
                'user_id' => $user->userId,
                'transaction_status' => 'SUCCESS',
                'response_msg' => 'SUCCESS',
                'bank_trans_id' => $response[0]['bank_reference'] ?? '',
                'transaction_type' => 'CREDIT',
                'transaction_id' => $orderId,
                'trans_date' => date("Y-m-d G:i:s",strtotime($response[0]['payment_time'])),
                'payment_type' => 'LOAD_WALLET',
                'payment_mode' => 'Wallet Load through '.strtolower(str_replace('_',' ',$charge_mode)).', '.$method.', Amount '.$amount ,
                'total_amount' => (float) $amount,
                'balance' => $balance,
            ]);
    
            if($walletResponse) {
                $user = User::find((int) $user_id);
                $user->wallet_balance = (float) $balance;
                $userUpdresponse = $user->save();
    
                $pymt_gtwy_report = PaymentGatewayReport::create([
                    'order_id' => $order_id,
                    'user_id' => $user_id,
                    'role_id' => $user->roleId,
                    'transaction_status' => 'SUCCESS',
                    'bank_trans_id' => $response[0]['bank_reference'] ?? '',
                    'transaction_type' => 'CREDIT',
                    'transaction_id' => $orderId,
                    'trans_date' => date("Y-m-d G:i:s",strtotime($response[0]['payment_time'])),
                    'payment_type' => $charge_mode,
                    'payment_mode' => $charge_mode ,
                    'payment_method' => $method,
                    'total_amount' => (float) $amount,
                    'response_msg' => 'SUCCESS',
                    'payment_status' => 'SUCCESS',
                    'balance' => $balance, 
                ]);
                if($pymt_gtwy_report) {
                    // $walletResponse = [];
                    $walletResponse['payment_mode'] = strtolower($charge_mode);
                    $walletResponse['order_id'] = $order_id;
                    $walletResponse['user_id'] = $user_id;
                    $walletResponse['total_amount'] = $amount;
                    $walletResponse['transaction_status'] = 'SUCCESS';
                    $walletResponse['response_msg'] = 'SUCCESS';
                    $walletResponse['bank_trans_id'] = $response[0]['bank_reference'];
                    $walletResponse['transaction_id'] = $orderId;
                    $walletResponse['payment_method'] = $method;
                    $walletResponse['trans_date'] = date("Y-m-d G:i:s",strtotime($response[0]['payment_time']));
                    
                    if($this->deductPymtGtwayCharge1($walletResponse, $user)) {
                        $success["success"] = "Success!!";
                        $statusMsg = "Transaction updated successfully!!";
                        $msgRes = $this->sendSmswithTransactionInfo($smsData);
                        if ($msgRes) {
                            $msg = "Dear SMART PAY User, Your wallet is credited with Rs ".$amount;
                            $this->send_telegram($msg,$user->telegram_no);
                            $this->sendSms($msgRes, $smsData['mobile']);
                            $this->notifyWithTransactionInfo($smsData, $user_id);
                            $data = array(
                                'email' => $user->email,
                                'name' => $user->first_name . " " . $user->last_name,
                                'updated_bal' => $balance,
                                'amount' => $amount,
                                'last_bal' => $userBalance,
                                'type' => 'Credited'
                            );
                            $send_email = Mail::send('mail.wallet',$data, function($msg) use($data) {
                                $msg->to($data['email'], $data['name']);
                                $msg->subject('Transaction Successful - PayMama');
                                $msg->from('hello@paymamaapp.in','PayMama - Business Made Easy');
                            });
                            // $this->sendSuccess($success, $statusMsg);
                            
                            $msg = "Payment Successful!\n";
                            $msg .= "Transaction Amount :- ₹ ".$amount."\n";
                            $msg .= "Order ID :- ".$order_id."\n";
                            $msg .= "RRN :- ".$response[0]['bank_reference'];
                            $msg .= "Date and Time :- ".date("Y-m-d G:i:s",strtotime($response[0]['payment_time']))."\n";
                            $options = (Auth::user()->pg_options);
                            if($options == "") {
                                $options = array();
                            } else {
                                $options = json_decode($options,true);
                            }
                            return view('modules.payment.online_payment',['amount'=>$amount,'orderid'=>$order_id,'rrn'=>$response[0]['bank_reference'],'date'=>date("Y-m-d G:i:s",strtotime($response[0]['payment_time'])),'mode'=>str_replace('_',' ',$charge_mode),'options'=>$options]);
                        }
                    } else {
                        return redirect('online_payment')->with('merror', "Something went wrong!!");
                    }
                } else {
                    return redirect('online_payment')->with('merror', "Something went wrong!");
                }
            }
        } else {
            // return $response[0]['payment_status'] . ' => ' . $response[0]['payment_message'];
            return redirect('online_payment')->with('merror', "Payment failed");
        }


    }
    
    public function deductPymtGtwayCharge1($walletResponse, $user)
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
                    $user->wallet_balance = $user->wallet_balance - $debitChrgTypeAmt;
                    $userDBRes = $user->save();
                    if ($userDBRes) {
                        $walletResponse = WalletTransactionDetail::create([
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
    
    public function createOrderID(){
        $max_id = PaymentGatewayReport::max('id');
        $max_id = 1+(int)$max_id;
        $newID = "PG".$max_id;
        return $newID;
    }
    
    public function send_telegram($msg,$chat_id) {
        $telegram = new \App\Packages\Telegram\Telegram(Config::get('constants.TELEGRAM_BOT_ID'));
        $content = array('chat_id' => $chat_id, 'text' => $msg, 'parse_mode' => 'HTML');
        $telegram->sendMessage($content);
        return true;
    }

}
