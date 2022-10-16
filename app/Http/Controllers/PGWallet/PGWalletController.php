<?php

namespace App\Http\Controllers\PGWallet;

use App\Http\Controllers\Controller;
use App\PymtGtwayMdChargeDtl;
use App\SmsTemplate;
use App\User;
use App\WalletTransactionDetail;
use App\PGWalletTransactionDetail;
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
use Session;

class PGWalletController extends Controller
{
    /**
     * Online Payment View
     */
    public function index()
    {
        
        return view('modules.PGWallet.pg_wallet_wallet');
        // return view('modules.payment.payment_gateway_report', compact('report', 'reportTH', 'filtersList', 'request', 'total_amt'));
    }
    
    public function storeWallet(Request $request) {
        $validator = Validator::make($request->all(), [
            'amount' => 'required',
            'mpin' => 'required',
        ]);

            $amount = $request->amount;
            $userId = Auth::user()->userId;
            $user = User::find((int) $userId);
            
            if($request->mpin != $user->mpin) {
				Session::put('error', 'Invalid MPIN...!');
                return redirect('pg-wallet-wallet');
				exit;
            }
            
            $user_id = $user->userId;
            
            //Deduct From PG Wallet
            $userBalance = $user->pg_wallet_balance;
            $balance = (($userBalance ? (float) $userBalance : 0) - ((float) $amount));
            $user->pg_wallet_balance = (float) $balance;
            $userUpdresponse = $user->save();
            $charge_mode="PG WALLET";
            $method="WALLET";
            
            $PGWalletResponse = PGWalletTransactionDetail::create([
                'order_id' => $this->createPGWalletOrderID(),
                'user_id' => $user->userId,
                'transaction_status' => 'SUCCESS',
                'response_msg' => 'SUCCESS',
                'bank_trans_id' => $response[0]['bank_reference'] ?? '',
                'transaction_type' => 'DEBIT',
                'transaction_id' => $this->createWalletOrderID(),
                'trans_date' => date("Y-m-d G:i:s"),
                'payment_type' => 'LOAD_WALLET',
                'payment_mode' => 'Wallet Load through '.strtolower(str_replace('_',' ',$charge_mode)).', '.$method.', Amount '.$amount ,
                'total_amount' => (float) $amount,
                'balance' => $balance,
            ]);
    
            if($PGWalletResponse) {
                $user = User::find((int) $user_id);
                //Credit To Main Wallet
                $userBalance = $user->wallet_balance;
                $balance = (($userBalance ? (float) $userBalance : 0) + ((float) $amount));
                $user->wallet_balance = (float) $balance;
                $userUpdresponse = $user->save();
            
                $load_wallet = WalletTransactionDetail::create([
                    'order_id' => $this->createWalletOrderID(),
                    'user_id' => $user_id,
                    'role_id' => $user->roleId,
                    'transaction_status' => 'SUCCESS',
                    'bank_trans_id' => $response[0]['bank_reference'] ?? '',
                    'transaction_type' => 'CREDIT',
                    'transaction_id' => $this->createPGWalletOrderID(),
                    'trans_date' => date("Y-m-d G:i:s"),
                    'payment_type' => 'LOAD_WALLET',
                    'payment_mode' => $charge_mode ,
                    'payment_method' => $method,
                    'total_amount' => (float) $amount,
                    'response_msg' => 'SUCCESS',
                    'payment_status' => 'SUCCESS',
                    'balance' => $balance, 
                ]);
                if($load_wallet) {
					$request->session()->put('success', 'Wallet transfer done Successfully...!');
                    return redirect('pg-wallet-wallet');
					exit;
                }else{
					$request->session()->put('error', 'Wallet transfer Failed...!');
					return redirect('pg-wallet-wallet');
					exit;
				}
            }else {
				$request->session()->put('error', 'Wallet transfer Failed...!');
				return redirect('pg-wallet-wallet');
				exit;
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
                $result[$key]['transaction_type'] = $value['transaction_type'];
                $result[$key]['trans_date'] = $value['trans_date'];
                $result[$key]['gateway_mode'] = $value['gateway_mode'];
                $result[$key]['total_amount'] = $value['total_amount'];
                $result[$key]['payment_type'] = $value['payment_type'];
                $result[$key]['payment_mode'] = $value['payment_mode'];
                $result[$key]['balance'] = $value['balance'];
            }
        }

        return $result;
    }
	
	 public function paymentGatewayPassbook(Request $request){
        
        $report = $this->filter($request);
        $report = $this->modifyReport($report);
        
        // print_r($report);
        // exit();
        return view('modules.PGWallet.pg_passbook_report', compact('report', 'request'));

    }
	
    public function paymentGatewayReport(Request $request){
        
        $loggedInRole = Auth::user()->roleId;
        $serviceType="PG_REPORT";
        $filtersList = $this->setFilterList($loggedInRole, $serviceType);
        $paymentGatewayReportTH = $this->setTableHeader($loggedInRole, $serviceType);
        
        $reports=$this->filterPGReports($request);
        //$reports = $this->modifyReport($reports);
         
         //print_r($reports);
        // exit();
        return view('modules.PGWallet.pg_report', compact('reports', 'filtersList', 'paymentGatewayReportTH', 'request'));

    }
    
    /**
     * Set Filter data here
     */
    public function setFilterList($loggedInRole, $serviceType = null)
    {
        $filterLists = [];
        $strAppend = "";
        $strAppend = "_ADMIN_FILTER";
        if ($loggedInRole == Config::get('constants.ADMIN')) {

            $strAppend = "_ADMIN_FILTER";

        } else if ($loggedInRole == Config::get('constants.DISTRIBUTOR')) {

            $strAppend = "_DIS_FILTER";

        } else if ($loggedInRole == Config::get('constants.MASTER_DISTRIBUTOR')) {

            $strAppend = "_MD_FILTER";

        } else if ($loggedInRole == Config::get('constants.RETAILER')) {

            $strAppend = "_RT_FILTER";
        }

        if ($serviceType) {
            $tableConstFD = "constants." . $serviceType . $strAppend;
            $filterLists = Config::get($tableConstFD);
        } else {
            $tableConstFD = "constants.ALL_SRVC_TYP" . $strAppend;
            $filterLists = Config::get($tableConstFD);
        }

        return $filterLists;
    }

    /**
     * Get Table Header
     */
    public function setTableHeader($loggedInRole, $serviceType = null)
    {
        $rechargeReportTH = [];
        $strAppend = "";
        $strAppend = "_ADMIN_TD";
        if ($loggedInRole == Config::get('constants.ADMIN')) {

            $strAppend = "_ADMIN_TD";

        } else if ($loggedInRole == Config::get('constants.DISTRIBUTOR')) {

            $strAppend = "_DIS_TD";

        }  else if ($loggedInRole == Config::get('constants.MASTER_DISTRIBUTOR')) {

            $strAppend = "_MD_TD";

        } else if ($loggedInRole == Config::get('constants.RETAILER')) {

            $strAppend = "_RT_TD";
        }

        if ($serviceType) {
            $tableConstHD = "constants." . $serviceType . $strAppend;
            $rechargeReportTH = Config::get($tableConstHD);
        }

        return $rechargeReportTH;
    }

    public function filterPGReports($request)
    {
        $report = PaymentGatewayReport::where('user_id', Auth::user()->userId)
                                        ->leftJoin('tbl_users', 'tbl_payment_gateway_report.user_id', '=', 'tbl_users.userId')
                                        ->orderBy('id', 'DESC');
                                           
        
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
    
    public function filter($request)
    {
        $report = PGWalletTransactionDetail::where('user_id', Auth::user()->userId)
                                            ->leftJoin('tbl_users', 'tbl_pg_wallet_trans_dtls.user_id', '=', 'tbl_users.userId')
                                            ->orderBy('id', 'DESC');
                                           
        
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
    
    public function createPGWalletOrderID(){
        $max_id = PGWalletTransactionDetail::max('id');
        $max_id = 1+(int)$max_id;
        $newID = "PG".$max_id;
        return $newID;
    }
    
    public function createWalletOrderID(){
        $max_id = WalletTransactionDetail::max('id');
        $max_id = 1+(int)$max_id;
        $newID = "SP".$max_id;
        return $newID;
    }
    
    public function send_telegram($msg,$chat_id) {
        $telegram = new \App\Packages\Telegram\Telegram(Config::get('constants.TELEGRAM_BOT_ID'));
        $content = array('chat_id' => $chat_id, 'text' => $msg, 'parse_mode' => 'HTML');
        $telegram->sendMessage($content);
        return true;
    }

}
