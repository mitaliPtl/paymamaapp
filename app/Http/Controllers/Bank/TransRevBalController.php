<?php

namespace App\Http\Controllers\Bank;

use App\BankAccount;
use App\TransferCreditReport;
use App\Http\Controllers\Controller;
use App\Role;
use App\SmsTemplate;
use App\TransRevBal;
use App\User;
use App\WalletTransactionDetail;
use Auth;
use App\SmsGatewaySetting;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
class TransRevBalController extends Controller
{
    /**
     * Transfer Revert view
     */
    public function index(Request $request)
    {
        $users = [];
        if (isset($request->mobile) && $request->mobile) {
            $users = $this->findUser($request);
            $users = $this->modifyUsers($users);
        }
        
        $dtPaymentType = Config::get('constants.DT_PAYMENT_TYPE');
        $bankAccounts = BankAccount::where('is_deleted', Config::get('constants.NOT-DELETED'))->get();
        $bankAccounts = $this->mofifyBankAccounts($bankAccounts);
        return view('modules.bank.trans_rev_bal', compact('users', 'bankAccounts', 'request','dtPaymentType'));
    }

    /**
     * Modify Bank Accounts
     */
    public function mofifyBankAccounts($accounts)
    {
        $result = [];
        $paymtType = Config::get('constants.BANK_TRANS_MODE');
        if ($accounts) {
            $ac_key = [];
            foreach ($accounts as $i => $ac) {
                $ac_key['type'] = "label";
                $ac_key['name'] = $ac->bank_name . '_' . $ac->account_no;
                $ac_key['value'] = "";
                array_push($result, $ac_key);
                $ac_key = [];
                $pmt_key = [];
                foreach ($paymtType as $pmtInd => $type) {
                    $pmt_key['type'] = "mode";
                    $pmt_key['value'] = $ac->bank_name . '_' . $ac->account_no . '_' . $type;
                    $pmt_key['name'] = $type;
                    array_push($result, $pmt_key);
                    $pmt_key = [];
                }

            }
        }
        return $result;
    }

    /**
     * Modify Users Response
     */
    public function modifyUsers($users)
    {
        $result = [];
        if ($users) {
            foreach ($users as $i => $user) {
                $users[$i]['user_name'] = User::getStoreNameById($user->userId);
                $users[$i]['parent_user_name'] = User::getStoreNameById($user->parent_user_id);
                $users[$i]['role_name'] = isset(Role::where('roleId', $user->roleId)->pluck('role')[0]) ? Role::where('roleId', $user->roleId)->pluck('role')[0] : '';
            }
            $result = $users;
        }
        return $result;
    }

    /**
     * Find User
     */
    public function findUser($request)
    {
        $result = [];
        if ($request->mobile) {
            $users = User::where('mobile', $request->mobile)
                ->orWhere('username', $request->mobile)
                ->where('isDeleted', Config::get('constants.NOT-DELETED'))->where('activated_status', Config::get('constants.ACTIVE'))->get();
            if ($users) {
                $result = $users;
            }
        }

        return $result;
    }

    /**
     * Transfer Balance
     */
    public function transferBalance(Request $request)
    {
         
        
        if ($request->mpin) {
            if (Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN')){
                $validator = Validator::make($request->all(), [
                    'bank' => 'required|string|max:255',
                    'reference_id' => 'required|string|max:255',
                    'amount' => 'required|string|max:255',
                    'mpin' => 'required|string|max:255',
                ]);
            }else{
                $validator = Validator::make($request->all(), [
                    'payment_type' => 'required|string|max:255',
                    'amount' => 'required|string|max:255',
                    'mpin' => 'required|string|max:255',
                ]);
            }
           

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }

            $loggedUser = User::find((int) Auth::id());

            if (isset($request->amount) && $request->amount) {
                if (Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.DISTRIBUTOR')) {
                    if($loggedUser->wallet_balance < $loggedUser->min_balance){
                        return back()->with('error', "Insufficient Balance!!");
                    }

                    if ($loggedUser && ($loggedUser->wallet_balance > $request->amount)) {
                        $loggedUser->wallet_balance = $loggedUser->wallet_balance - $request->amount;
                        $loggedUserResponse = $loggedUser->save();
                    } else {
                        return back()->with('error', "Insufficient Balance!!");
                    }
                }
            }

            $smsData = [];
            $user = User::find((int) $request->user_id);
            $smsData['last_balance_amount'] = $user->wallet_balance;
            $user->wallet_balance = $user->wallet_balance + $request->amount;
            $userResponse = $user->save();

            $smsData['amount'] = $request->amount;
            $smsData['updated_balance_amount'] = $user->wallet_balance;
            $smsData['mobile'] = $user->mobile;

            if ($userResponse) {
                $trnRvresponse = TransRevBal::create([
                    'bank' => $request->get('bank'),
                    'reference_id' => $request->get('reference_id'),
                    'payment_type' => $request->get('payment_type'),
                    'amount' => $request->get('amount'),
                    'user_id' => $request->get('user_id'),
                    'role' => $request->get('role_id'),
                    'mobile_no' => $request->get('user_mobile'),
                    'balance' => $user->wallet_balance,
                    'trans_date' => now(),
                    'transfer_type' => "CREDIT",
                    'transfered_by' => Auth::id(),
                ]);
            }

            if ($trnRvresponse) {
                if (Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.DISTRIBUTOR')) {
                    $walletDebitResponse = WalletTransactionDetail::create([
                        'order_id' => $request->reference_id,
                        'user_id' => Auth::id(),
                        'transaction_status' => "SUCCESS",
                        'response_msg' => "",
                        'bank_trans_id' => $request->reference_id,
                        'transaction_type' => "DEBIT",
                        'transaction_id' => "",
                        'trans_date' => now(),
                        'payment_type' => Config::get('constants.PAYMENT_TYPE.PAYMT_WALLET'),
                        'payment_mode' => Config::get('constants.PAYMENT_GTWAY_TYPE.DIRECT_TRANSFER'),
                        'total_amount' => $request->amount,
                        'balance' => $loggedUser->wallet_balance,
                    ]);
                } else {
                    $walletDebitResponse = true;
                }

                if ($walletDebitResponse) {
                    $walletResponse = WalletTransactionDetail::create([
                        'order_id' => $request->reference_id,
                        'user_id' => $request->user_id,
                        'transaction_status' => "SUCCESS",
                        'response_msg' => "",
                        'bank_trans_id' => $request->reference_id,
                        'transaction_type' => "CREDIT",
                        'transaction_id' => "",
                        'trans_date' => now(),
                        'payment_type' => Config::get('constants.PAYMENT_TYPE.PAYMT_WALLET'),
                        'payment_mode' => Config::get('constants.PAYMENT_GTWAY_TYPE.DIRECT_TRANSFER'),
                        'total_amount' => $request->amount,
                        'balance' => $user->wallet_balance,
                    ]);

                        if ($loggedUser->roleId == Config::get('constants.DISTRIBUTOR')) {
                            
                            $use_debit_value = (float)$user->distributor_credit + (float) $request->amount;
                            $update_debit = User::where('userId', $request->user_id)
                                                ->update(['distributor_credit'=> $use_debit_value]);

                        }elseif ($loggedUser->roleId == Config::get('constants.FOS') ) {

                            $use_debit_value = (float)$user->fos_credit + (float) $request->amount;
                            $update_debit = User::where('userId', $request->user_id)
                                                ->update(['fos_credit'=> $use_debit_value]);

                        }    

                        // $use_debit_value = (float)$user->distributor_credit + (float) $request->amount;
                        // $update_debit = User::where('userId', $request->user_id)
                        //                     ->update(['distributor_credit'=> $use_debit_value]);
                                             
                        if($request->payment_type == 'CREDIT'){
                            $transfer_credit = TransferCreditReport::create([
                                                                                'reference_id' => $request->reference_id,
                                                                                // 'payment_type' => $request->payment_type,
                                                                                'transfer_by_id' => $loggedUser->userId,
                                                                                'transfer_by_role' => $loggedUser->roleId,
                                                                                'transfer_to_id' => $request->user_id,
                                                                                'transfer_to_role' =>  $request->role_id,
                                                                                'mobile_transfer_by' =>$request->user_mobile,
                                                                                'amount'=> $request->amount,
                                                                                'trans_date' => now(),
                                                                                'transaction_type' => "CREDIT",
                                                                                'balance' => $use_debit_value,
                                                                                'transfer_type' => $request->payment_type,
                                                                                'created'=>now()

                                                                            ]);
                        }
                        
                }

                if ($walletResponse) {
                    $msgRes = $this->sendSmswithTransactionInfo($smsData);
                    $this->notifyWithTransactionInfo($smsData, $request->user_id);

                    // $msgRes = true;
                    if ($msgRes) {
                        return back()->with('success', "Transfered Successfully !!");
                    }
                }

            }
        }
    }

    /**
     * Send Sms to User on Successfull Transaction
     */
    public function sendSmswithTransactionInfo($smsData)
    {
        $msg = "";
        $result = null;

        $SmsBalAddTemplate = SmsTemplate::where('alias', Config::get('constants.SMS_TEMPLATE_ALIAS.BALANCE_ADDED.name'))->first();
        if (isset($SmsBalAddTemplate)) {
            $msg = __($SmsBalAddTemplate->template, [
                "last_balance_amount" => $smsData['last_balance_amount'],
                "amount" => $smsData['amount'],
                "updated_balance_amount" => $smsData['updated_balance_amount'],
            ]);
        }

        if ($msg) {
            if ($smsData['mobile']) {
                $result = $this->sendSms($msg, $smsData['mobile'],$SmsBalAddTemplate->template_id);
            }

        }

        return $result;
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

    /**
     * Send Sms to User on Successfull DEBIT Transaction
     */
    public function sendDBSmswithTransactionInfo($smsData)
    {
        $msg = "";
        $result = null;

        $SmsBalAddTemplate = SmsTemplate::where('alias', Config::get('constants.SMS_TEMPLATE_ALIAS.BALANCE_DEDUCT.name'))->first();
        if (isset($SmsBalAddTemplate)) {
            $msg = __($SmsBalAddTemplate->template, [
                "last_balance_amount" => $smsData['last_balance_amount'],
                "amount" => $smsData['amount'],
                "updated_balance_amount" => $smsData['updated_balance_amount'],
            ]);
        }

        if ($msg) {
            if ($smsData['mobile']) {
                $result = $this->sendSms($msg, $smsData['mobile'], $SmsBalAddTemplate->template_id);
            }

        }

        return $result;
    }

    public function sendDBNotifywithTransactionInfo($smsData, $user_id){
        $msg = "";
        $result = null;

        $NotifyBalAddTemplate = SmsTemplate::where('alias', Config::get('constants.SMS_TEMPLATE_ALIAS.BALANCE_DEDUCT.name'))->get()->first();

        if (isset($NotifyBalAddTemplate)) {
            $msg = __($NotifyBalAddTemplate->notification, [
                "last_balance_amount" => $smsData['last_balance_amount'],
                "amount" => $smsData['amount'],
                "updated_balance_amount" => $smsData['updated_balance_amount'],
            ]);
        }
        if ($msg) {

            $user_session = DB::table('tbl_users_login_session_dtl')->where('user_id', $user_id)->get()->first();
            if ( $user_session) {
                // $notmsg = 'Dear SMART PAY User, Your wallet is credited with Rs :amount , Last Balance Rs :last_balance_amount . Updated Balance Rs :updated_balance_amount, Helpline : 040-29563154';
                $this->sendNotification($user_session->firebase_token, $NotifyBalAddTemplate->sms_name, $msg, $user_id);
            }
        }
        return true;
    }

    /**
     * Revert Balance
     */
    public function revertBalance(Request $request)
    {
       
       
        if ($request->mpin) {
            if (Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN')){
                $validator = Validator::make($request->all(), [
                    'bank' => 'required|string|max:255',
                    'reference_id' => 'required|string|max:255',
                    'revert_amount' => 'required|string|max:255',
                    'amount_sent' => 'required|string|max:255',
                    'mpin' => 'required|string|max:255',
                ]);
            }else{
                $validator = Validator::make($request->all(), [
                    'payment_type' => 'required|string|max:255',
                    'revert_amount' => 'required|string|max:255',
                    'amount_sent' => 'required|string|max:255',
                    'mpin' => 'required|string|max:255',
                ]);
            }

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }

            if (Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN')){
            $trnRvresponse = TransRevBal::where('bank', $request->bank)
                ->where('reference_id', $request->reference_id)
                ->where('user_id', $request->user_id)
                ->where('amount', $request->amount_sent)->get();
            }else{
                $trnRvresponse = TransRevBal::where('payment_type', $request->payment_type)
                ->where('user_id', $request->user_id)
                ->where('amount', $request->amount_sent)->get();
            }

            $dbSmsData = [];
            // if (isset($trnRvresponse) && count($trnRvresponse) > 0) {
                $user = User::find((int) $request->user_id);
                $dbSmsData['last_balance_amount'] = $user->wallet_balance;
                $user->wallet_balance = $user->wallet_balance - $request->revert_amount;
                $userResponse = $user->save();

                $dbSmsData['amount'] = $request->revert_amount;
                $dbSmsData['updated_balance_amount'] = $user->wallet_balance;
                $dbSmsData['mobile'] = $user->mobile;

                if ($userResponse && Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.DISTRIBUTOR')) {
                    $loggedUser = User::find((int) Auth::id());
                    $crSmsData['last_balance_amount'] = $loggedUser->wallet_balance;

                    if ($loggedUser) {
                        $loggedUser->wallet_balance = $loggedUser->wallet_balance + $request->revert_amount;
                        $loggedUserResponse = $loggedUser->save();

                        
                        $crSmsData['amount'] = $request->revert_amount;
                        $crSmsData['updated_balance_amount'] = $loggedUser->wallet_balance;
                        $crSmsData['mobile'] = $loggedUser->mobile;

                        
                        $walletDTCRResponse = WalletTransactionDetail::create([
                            'user_id' => Auth::id(),
                            'transaction_status' => "SUCCESS",
                            'response_msg' => "",
                            'transaction_type' => "CREDIT",
                            'transaction_id' => "",
                            'trans_date' => now(),
                            'payment_type' => Config::get('constants.PAYMENT_TYPE.PAYMT_WALLET'),
                            'payment_mode' => Config::get('constants.PAYMENT_GTWAY_TYPE.REVERT'),
                            'total_amount' => $request->revert_amount,
                            'balance' => $loggedUser->wallet_balance,
                        ]);

                        if($walletDTCRResponse){
                            $this->sendSmswithTransactionInfo($crSmsData);
                            $this->notifyWithTransactionInfo($smsData, $request->user_id);
                        }
                    }
                }

                $trnRvresponse = TransRevBal::create([
                    'bank' => $request->get('bank'),
                    'reference_id' => $request->get('reference_id'),
                    'payment_type' => $request->get('payment_type'),
                    'amount' => $request->get('revert_amount'),
                    'user_id' => $request->get('user_id'),
                    'role' => $request->get('role_id'),
                    'mobile_no' => $request->get('user_mobile'),
                    'balance' => $user->wallet_balance,
                    'trans_date' => now(),
                    'transfer_type' => "DEBIT",
                    'transfered_by' => Auth::user()->userId,
                ]);

                $walletDBResponse = WalletTransactionDetail::create([
                    'order_id' => $request->reference_id,
                    'user_id' => $request->user_id,
                    'transaction_status' => "SUCCESS",
                    'response_msg' => "",
                    'bank_trans_id' => $request->reference_id,
                    'transaction_type' => "DEBIT",
                    'transaction_id' => "",
                    'trans_date' => now(),
                    'payment_type' => Config::get('constants.PAYMENT_TYPE.PAYMT_WALLET'),
                    'payment_mode' => Config::get('constants.PAYMENT_GTWAY_TYPE.REVERT'),
                    'total_amount' => $request->revert_amount,
                    'balance' => $user->wallet_balance,
                ]);

                if ($walletDBResponse) {
                    $loggedUser = User::find((int) Auth::id());
                    $use_debit_value = (float)$user->distributor_credit - (float) $request->revert_amount;
                    $update_debit = User::where('userId', $request->get('user_id'))
                                        ->update(['distributor_credit'=> $use_debit_value]);

                    if($request->payment_type == 'CREDIT'){

                        
                        $transfer_credit = TransferCreditReport::create([
                                                        'reference_id' => $request->reference_id,
                                                        // 'payment_type' => $request->payment_type, 
                                                        'transfer_by_id' =>  $loggedUser->userId,
                                                        'transfer_by_role' => $loggedUser->roleId,
                                                        'transfer_to_id' => $request->get('user_id'),
                                                        'transfer_to_role' => $request->get('role_id'),
                                                        'mobile_transfer_by' =>$request->user_mobile,
                                                        'amount'=> $request->revert_amount,
                                                        'trans_date' => now(),
                                                        'transaction_type' => "DEBIT",
                                                        'balance' =>  $user->wallet_balance,
                                                        'transfer_type' => "REVERT",
                                                        'created'=>now()
    
                                            ]);
                    }

                    $dbsmsRes = $this->sendDBSmswithTransactionInfo($dbSmsData);
                    $dbNotifyRes = $this->sendDBNotifywithTransactionInfo($dbSmsData, $request->user_id);
                    // $dbsmsRes = true;
                    if($dbsmsRes){
                        return back()->with('success', "Revert Successful!!");
                    }
                }
            // } else {
            //     return back()->with('error', "Entered Transaction record not found!!");
            // }
        }
    }

    public function sendOPTBeforeRevert(Request $request){

        if(isset($request->user_id) && $request->user_id){
            $send_otp = $this->sendRevertOtp($request);
            $statusMsg = "Success !!";
            return $this->sendSuccess($send_otp, $statusMsg);
        }
    }
    public function revertBalanceAPI(Request $request)
    {
        

        $customer_user_id = $request->customer_user_id;
        $customer_role_id = $request->customer_role_id;
        if ($request->mpin) {
            if ($request->user_id == Config::get('constants.ADMIN')){
                $validator = Validator::make($request->all(), [
                    'bank' => 'required|string|max:255',
                    'reference_id' => 'required|string|max:255',
                    'revert_amount' => 'required|string|max:255',
                    'amount_sent' => 'required|string|max:255',
                    'mpin' => 'required|string|max:255',
                ]);
            }else{
                $validator = Validator::make($request->all(), [
                    'payment_type' => 'required|string|max:255',
                    'revert_amount' => 'required|string|max:255',
                    'amount_sent' => 'required|string|max:255',
                    'mpin' => 'required|string|max:255',
                ]);
            }

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }

            if ($request->user_id == Config::get('constants.ADMIN')){
            $trnRvresponse = TransRevBal::where('bank', $request->bank)
                ->where('reference_id', $request->reference_id)
                ->where('user_id', $customer_user_id)
                ->where('amount', $request->amount_sent)->get();
            }else{
                $trnRvresponse = TransRevBal::where('payment_type', $request->payment_type)
                ->where('user_id', $customer_user_id)
                ->where('amount', $request->amount_sent)->get();
            }

            $dbSmsData = [];
            // if (isset($trnRvresponse) && count($trnRvresponse) > 0) {
                $user = User::find((int) $customer_user_id);
                $dbSmsData['last_balance_amount'] = $user->wallet_balance;
                $user->wallet_balance = $user->wallet_balance - $request->revert_amount;
                $userResponse = $user->save();

                $dbSmsData['amount'] = $request->revert_amount;
                $dbSmsData['updated_balance_amount'] = $user->wallet_balance;
                $dbSmsData['mobile'] = $user->mobile;

                if ($userResponse && $request->role_id == Config::get('constants.DISTRIBUTOR')) {
                    $loggedUser = User::find((int) $request->user_id); 
                    $crSmsData['last_balance_amount'] = $loggedUser->wallet_balance;

                    if ($loggedUser) {
                        $loggedUser->wallet_balance = $loggedUser->wallet_balance + $request->revert_amount;
                        $loggedUserResponse = $loggedUser->save();

                        
                        $crSmsData['amount'] = $request->revert_amount;
                        $crSmsData['updated_balance_amount'] = $loggedUser->wallet_balance;
                        $crSmsData['mobile'] = $loggedUser->mobile;

                        $walletDTCRResponse = WalletTransactionDetail::create([
                            'user_id' => $request->user_id,
                            'transaction_status' => "SUCCESS",
                            'response_msg' => "",
                            'transaction_type' => "CREDIT",
                            'transaction_id' => "",
                            'trans_date' => now(),
                            'payment_type' => Config::get('constants.PAYMENT_TYPE.PAYMT_WALLET'),
                            'payment_mode' => Config::get('constants.PAYMENT_GTWAY_TYPE.REVERT'),
                            'total_amount' => $request->revert_amount,
                            'balance' => $loggedUser->wallet_balance,
                        ]);

                        if($walletDTCRResponse){
                            $this->sendSmswithTransactionInfo($crSmsData);
                            $this->notifyWithTransactionInfo($crSmsData, $request->user_id);
                        }
                    }
                }

                $trnRvresponse = TransRevBal::create([
                    'bank' => $request->get('bank'),
                    'reference_id' => $request->get('reference_id'),
                    'payment_type' => $request->get('payment_type'),
                    'amount' => $request->get('revert_amount'),
                    'user_id' => $request->get('customer_user_id'),
                    'role' => $customer_role_id,
                    // 'role' => $request->get('role_id'),
                    'mobile_no' => $request->get('user_mobile'),
                    'balance' => $user->wallet_balance,
                    'trans_date' => now(),
                    'transfer_type' => "DEBIT",
                    'transfered_by' => $request->user_id,
                ]);

                $walletDBResponse = WalletTransactionDetail::create([
                    'order_id' => $request->reference_id,
                    'user_id' => $customer_user_id,
                    'transaction_status' => "SUCCESS",
                    'response_msg' => "",
                    'bank_trans_id' => $request->reference_id,
                    'transaction_type' => "DEBIT",
                    'transaction_id' => "",
                    'trans_date' => now(),
                    'payment_type' => Config::get('constants.PAYMENT_TYPE.PAYMT_WALLET'),
                    'payment_mode' => Config::get('constants.PAYMENT_GTWAY_TYPE.REVERT'),
                    'total_amount' => $request->revert_amount,
                    'balance' => $user->wallet_balance,
                ]);

                if ($walletDBResponse) {

                    $use_debit_value = (float)$user->distributor_credit - (float) $request->revert_amount;
                        $update_debit = User::where('userId', $customer_user_id)
                                            ->update(['distributor_credit'=> $use_debit_value]);

                    if($request->payment_type == 'CREDIT'){

                        $loggedUser = User::find((int) Auth::id());
                        $transfer_credit = TransferCreditReport::create([
                                                        'reference_id' => $request->reference_id,
                                                        // 'payment_type' => $request->payment_type, 
                                                        'transfer_by_id' =>  $request->user_id,
                                                        'transfer_by_role' => $request->role_id,
                                                        'transfer_to_id' =>  $customer_user_id,
                                                        'transfer_to_role' => $customer_role_id,
                                                        'mobile_transfer_by' =>$user->mobile,
                                                        'amount'=> $request->revert_amount,
                                                        'trans_date' => now(),
                                                        'transaction_type' => "DEBIT",
                                                        'balance' =>  $user->wallet_balance,
                                                        'transfer_type' => "REVERT",
                                                        'created'=>now()
    
                                            ]);
                    }

                    $dbsmsRes = $this->sendDBSmswithTransactionInfo($dbSmsData);
                    $dbNotifyRes = $this->sendDBNotifywithTransactionInfo($dbSmsData, $customer_user_id);

                    // $dbsmsRes = true;
                    if($dbsmsRes){
                        // return back()->with('success', "Revert Successful!!");
                        $statusMsg = "Revert Successful!!";
                        return $this->sendSuccess($dbsmsRes, $statusMsg);
                    }
                }
            // } else {
            //     return back()->with('error', "Entered Transaction record not found!!");
            // }
        }
    }

    
    /**
     * Send Revert Otp to the Retailer
     */
    public function sendRevertOtp(Request $request){
        if(isset($request->recep_id) && ($request->revert_amount)){
            $otp = rand(100000, 999999);
            $recpUser = User::where('userId',$request->recep_id)->first();
            $recpUser->logged_otp = $otp;
            $recpUserRes = $recpUser->save();

            if($recpUserRes){
                $revertSmsData['mobile'] = $recpUser->mobile;
                $revertSmsData['otp'] = $otp;
                $revertSmsData['revert_amount'] = $request->revert_amount;
                $revertSmsRes = $this->sendRevertOtpSms($revertSmsData);
                $this->sendRevertOtpNotify($revertSmsData, $request->recep_id);
                // $revertSmsRes =true;
                $return_msg = 'Revert OTP sent to Retailer!!';
                if($revertSmsRes){
                    return  $return_msg ;
                }
            }
        }
    }

    /**
     *Verify Retailer Revert OTP
     */
    public function verifyRevertOTPMpin(Request $request)
    {
        if(isset($request->recp_id)){
            $userResponse = User::where('userId',$request->recp_id)->first();

            if(isset($userResponse)){
                if($userResponse->logged_otp == $request->otp){
                    return response()->json("true");
                }else{
                    return response()->json(0);
                }
            }
        }
       
        return response()->json(0);
    }

    /**
     * Send Revert Sms to Retailer on Transaction Info
     */
    public function sendRevertOtpSms($smsData)
    {
        $msg = "";
        $result = null;

        $SmsTemplate = SmsTemplate::where('alias', Config::get('constants.SMS_TEMPLATE_ALIAS.REVERT_OTP_MSG.name'))->pluck('template')->first();
        $template_id = SmsTemplate::where('alias', Config::get('constants.SMS_TEMPLATE_ALIAS.REVERT_OTP_MSG.name'))->pluck('template_id')->first();
        if (isset($SmsTemplate)) {
            $msg = __($SmsTemplate, [
                "otp " => $smsData['otp'],
                "revert_amount" => $smsData['revert_amount']
            ]);
        }

        if ($msg) {
            if ($smsData['mobile']) {
                $result = $this->sendSms($msg, $smsData['mobile'], $template_id);
            }

        }

        return $result;
    }

    public function sendRevertOtpNotify($data, $user_id)
    {
        $msg = "";
        $SmsTemplatenNotify = SmsTemplate::where('alias', Config::get('constants.SMS_TEMPLATE_ALIAS.REVERT_OTP_MSG.name'))->get()->first();
        if (isset($SmsTemplatenNotify)) {
            $msg = __($SmsTemplatenNotify->notification, [
                "otp " => $data['otp'],
                "revert_amount" => $data['revert_amount']
            ]);
        }
        if ($msg) {
            $user_session = DB::table('tbl_users_login_session_dtl')->where('user_id', $user_id)->get()->first();
            if ( $user_session) {
                // $notmsg = 'Dear SMART PAY User, Your wallet is credited with Rs :amount , Last Balance Rs :last_balance_amount . Updated Balance Rs :updated_balance_amount, Helpline : 040-29563154';
                $this->sendNotification($user_session->firebase_token, $SmsTemplatenNotify->sms_name, $msg,  $user_id);
            }
        }
        return true;
    }
    /**
     * All Transfer View
     */
    public function allTransfer(Request $request)
    {
        $allTransfers = $this->filter($request);
        $allTransfers = $this->modifyAllTransfer($allTransfers);
        return view('modules.bank.all_transfer', compact('allTransfers', 'request'));
    }

    /**
     * Modify all Transfer Response
     */
    public function modifyAllTransfer($allTransfers)
    {
        $result = [];
        if ($allTransfers) {
            foreach ($allTransfers as $i => $transfer) {
                // $allTransfers[$i]['first_name'] = isset(User::where('userId', $transfer->user_id)->pluck('first_name')[0]) ? User::where('userId', $transfer->user_id)->pluck('first_name')[0] : '';
                // $allTransfers[$i]['transfered_by'] = isset(User::where('userId', $transfer->transfered_by)->pluck('first_name')[0]) ? User::where('userId', $transfer->transfered_by)->pluck('first_name')[0] . ' ' . User::where('userId', $transfer->transfered_by)->pluck('last_name')[0] : '';
                $allTransfers[$i]['store_name'] = User::getStoreNameById($transfer->user_id);
                $allTransfers[$i]['username'] = User::getUsernameById($transfer->user_id);
                // $allTransfers[$i]['transfered_by'] = User::getStoreNameById($transfer->transfered_by);
                $transfered_by = User::getStoreNameById($transfer->transfered_by);
                if ($transfered_by) {
                    $allTransfers[$i]['transfered_by'] = $transfered_by;
                }else {
                    $allTransfers[$i]['transfered_by'] = User::where('userId', $transfer->transfered_by)->pluck('first_name')->first();

                }
                
            }
            $result = $allTransfers;
        }
        return $result;
    }

    /**
     * Filter Transaction Reports Data
     */
    public function filter($request, $isApi=null)
    {
        $dataDetails = TransRevBal::orderBy('trans_date', 'DESC');
        
        if ($request->has('from_date') && isset($request->from_date)) {
            $fromDate = $request->get('from_date');
            $dataDetails->whereDate('trans_date', '>=', $fromDate);
        }else{
            $fromDate = now();
            $dataDetails->whereDate('trans_date', '>=', $fromDate);
        }

        if ($request->has('to_date') && isset($request->to_date)) {
            $toDate = $request->get('to_date');
            $dataDetails->whereDate('trans_date', '<=', $toDate);
        }

        if ($request->has('transfer_type') && isset($request->transfer_type)) {
            $transfer_type = $request->get('transfer_type');
            if ($transfer_type == 'REVERT') {
                $dataDetails->where('transfer_type', 'DEBIT');
            }else{
                $dataDetails->where('transfer_type', $transfer_type);
            }
        }

        if($isApi){
            if($request->role_id != Config::get('constants.ADMIN')){
                $dataDetails->where('transfered_by', $request->user_id);
            }

        }else{
            if(Auth::userRoleAlias() != Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN')){
                $dataDetails->where('transfered_by', Auth::id());
            }
            if ( ($request->has('trans') && isset($request->trans) ) && ($request->trans == 'ADMIN') ){
                $dataDetails->where('transfered_by', Auth::id());
            }
        }
        // $dataDetails->leftJoin('tbl_users', 'tbl_transfer_revert_balances.user_id', '=', 'tbl_users.userId');

        return $dataDetails->orderBy('id', 'DESC')->get();
    }

    /**
     * Transfer Balance
     */
    public function transferBalanceApi(Request $request)
    {

     
        if ($request->role_id == Config::get('constants.ADMIN')){
            $validator = Validator::make($request->all(), [
                'bank' => 'required|string|max:255',
                'reference_id' => 'required|string|max:255',
                'amount' => 'required|string|max:255',
                'mpin' => 'required|string|max:255',
            ]);
        }else{
            $validator = Validator::make($request->all(), [
                'payment_type' => 'required|string|max:255',
                'amount' => 'required|string|max:255',
                'mpin' => 'required|string|max:255',
            ]);
        }
        // $validator = Validator::make($request->all(), [
        //     'bank' => 'required|string|max:255',
        //     'reference_id' => 'required|string|max:255',
        //     'amount' => 'required|string|max:255',
        //     'mpin' => 'required|numeric',
        //     'user_id' => 'required|numeric',
        // ]);

        $smsData = [];
        $user = User::find((int) $request->user_id);

        if ($validator->fails()) {
            return $this->sendError("Validation Error!!", $validator->errors());
        }

        if (isset($request->mpin) && $request->mpin) {
            if ($user->mpin != $request->mpin) {
                return $this->sendError("Invalid MPIN!!");
            }
        }

        if (isset($request->amount) && $request->amount) {
            // if (Role::getAliasFromId($request->role_id) == Config::get('constants.ROLE_ALIAS.DISTRIBUTOR')) {
            if ((Role::getAliasFromId($request->role_id) == Config::get('constants.ROLE_ALIAS.DISTRIBUTOR')) || (Role::getAliasFromId($request->role_id) == Config::get('constants.ROLE_ALIAS.FOS'))) {

                if($user->wallet_balance < $user->min_balance){
                    return $this->sendError("Insufficient Balance!!");
                }

                if ($user && ($user->wallet_balance > $request->amount)) {
                    $user->wallet_balance = $user->wallet_balance - $request->amount;
                    $userWalletUpResponse = $user->save();
                } else {
                    return $this->sendError("Insufficient Balance!!");
                }
            }
        }
        

        if($userWalletUpResponse){
            $beneficiary = User::find((int) $request->beneficiary_id);
            $smsData['last_balance_amount'] = $beneficiary->wallet_balance;
            $beneficiary->wallet_balance = $beneficiary->wallet_balance + $request->amount;
            $userResponse = $beneficiary->save();
    
            $smsData['amount'] = $request->amount;
            $smsData['updated_balance_amount'] = $beneficiary->wallet_balance;
            $smsData['mobile'] = $beneficiary->mobile;
        }
        

        if ($userResponse) {
            $trnRvresponse = TransRevBal::create([
                'bank' => $request->get('bank'),
                'reference_id' => $request->get('reference_id'),
                'payment_type' => $request->get('payment_type'),
                'amount' => $request->get('amount'),
                'user_id' => $request->get('beneficiary_id'),
                'role' => User::where('userId', $request->beneficiary_id)->pluck('roleId')->first(),
                'mobile_no' => $beneficiary->mobile,
                'balance' => $beneficiary->wallet_balance,
                'trans_date' => now(),
                'transfer_type' => "CREDIT",
                'transfered_by' => $request->get('user_id'),
            ]);
        }

        if ($trnRvresponse) {

            if (Role::getAliasFromId($request->role_id)  == Config::get('constants.ROLE_ALIAS.DISTRIBUTOR')) {
                $walletDebitResponse = WalletTransactionDetail::create([
                    'order_id' => $request->reference_id,
                    'user_id' => $request->user_id,
                    'transaction_status' => "SUCCESS",
                    'response_msg' => "",
                    'bank_trans_id' => $request->reference_id,
                    'transaction_type' => "DEBIT",
                    'transaction_id' => "",
                    'trans_date' => now(),
                    'payment_type' => Config::get('constants.PAYMENT_TYPE.PAYMT_WALLET'),
                    'payment_mode' => Config::get('constants.PAYMENT_GTWAY_TYPE.DIRECT_TRANSFER'),
                    'total_amount' => $request->amount,
                    'balance' => $user->wallet_balance,
                ]);
            } else {
                $walletDebitResponse = true;
            }

            if($walletDebitResponse){
                $walletResponse = WalletTransactionDetail::create([
                    'order_id' => $request->reference_id,
                    'user_id' => $request->beneficiary_id,
                    'transaction_status' => "SUCCESS",
                    'response_msg' => "",
                    'bank_trans_id' => $request->reference_id,
                    'transaction_type' => "CREDIT",
                    'transaction_id' => "",
                    'trans_date' => now(),
                    'payment_type' => Config::get('constants.PAYMENT_TYPE.PAYMT_WALLET'),
                    'payment_mode' => Config::get('constants.PAYMENT_GTWAY_TYPE.DIRECT_TRANSFER'),
                    'total_amount' => $request->amount,
                    'balance' => $beneficiary->wallet_balance,
                ]);
                
                // $use_debit_value = (float)$user->distributor_credit + (float) $request->amount;
                       
                if($request->payment_type == 'CREDIT'){
                    $beneficiary = User::find((int) $request->beneficiary_id);

                    if ($request->role_id == Config::get('constants.DISTRIBUTOR')) {
                        $bene_dist_credit = 0;
                        if (is_null($beneficiary->distributor_credit)) {
                           $bene_dist_credit = 0;
                        }else {
                            $bene_dist_credit = $beneficiary->distributor_credit;
                        }
                        // $use_debit_value = (float)$beneficiary->distributor_credit + (float) $request->amount;
                        $use_debit_value = (float)$bene_dist_credit + (float) $request->amount;
                        $update_debit = User::where('userId', $request->beneficiary_id)
                                            ->update(['distributor_credit'=> $use_debit_value]);

                    }elseif ($request->role_id == Config::get('constants.FOS') ) {
                        $bene_fos_credit = 0;
                        if (is_null($beneficiary->fos_credit)) {
                            $bene_fos_credit = 0;
                         }else {
                             $bene_fos_credit = $beneficiary->fos_credit;
                         }
                        $use_debit_value = (float)$bene_fos_credit + (float) $request->amount;
                        $update_debit = User::where('userId', $request->beneficiary_id)
                                            ->update(['fos_credit'=> $use_debit_value]);

                    }
                    


                    $transfer_credit = TransferCreditReport::create([
                                                    'reference_id' => $request->reference_id,
                                                    // 'payment_type' => $request->payment_type,
                                                    'transfer_by_id' =>  $request->user_id,
                                                    'transfer_by_role' => $request->role_id,
                                                    'transfer_to_id' => $beneficiary->userId,
                                                    'transfer_to_role' =>  $beneficiary->roleId,
                                                    'mobile_transfer_by' =>$beneficiary->mobile,
                                                    'amount'=> $request->amount,
                                                    'trans_date' => now(),
                                                    'transaction_type' => "CREDIT",
                                                    'balance' => $use_debit_value,
                                                    'transfer_type' => $request->payment_type,
                                                    'created'=>now()

                                        ]);
                }

            }            

            if ($walletResponse) {
                $msgRes = $this->sendSmswithTransactionInfo($smsData);
                $notifyRes = $this->notifyWithTransactionInfo($smsData, $request->beneficiary_id);
                // $msgRes = true;
                if ($msgRes) {
                    $success['success'] = "Success!!";
                    $statusMsg = "Fund transfered successfully!!";
                    return $this->sendSuccess($success, $statusMsg);
                }
            }

        }
    }

    public function allTransferApi(Request $request){

        $isApi = true;
        $allTransfers = $this->filter($request , $isApi);
        $allTransfers = $this->modifyAllTransfer($allTransfers);
        if(count($allTransfers)>0){
            $success = $allTransfers;
            $statusMsg = "Success!!";
            return $this->sendSuccess($success, $statusMsg);
        }
        return $this->sendError("No record Found");
    }


    public function reportSummeryAPI(Request $request){
       
        $today = now();

        $report=[
                        "transfer" => 0.00,
                        "earned"=>  0.00,
                        "credit" => 0.00
                ];
        $tranfer_report_credit = TransRevBal:: whereDate('trans_date',$today)
                                        ->where('transfered_by', $request->user_id)
                                        ->where('transfer_type', 'CREDIT')
                                        ->pluck('amount')
                                        ->sum();
        $tranfer_report_revert = TransRevBal:: whereDate('trans_date',$today)
                                        ->where('transfered_by', $request->user_id)
                                        ->where('transfer_type', 'DEBIT')
                                        ->pluck('amount')
                                        ->sum();
        $transfer = (float) $tranfer_report_credit - (float) $tranfer_report_revert;
        $report['transfer'] = (int) $transfer;

        $report_credit = TransferCreditReport:: whereDate('trans_date',$today)
                                        ->where('transfer_by_id', $request->user_id)
                                        ->where('transaction_type', 'CREDIT')
                                        ->pluck('amount')
                                        ->sum();
        $report_debit = TransferCreditReport:: whereDate('trans_date',$today)
                                        ->where('transfer_by_id', $request->user_id)
                                        ->where('transaction_type', 'DEBIT')
                                        ->where('transfer_type', 'REVERT')
                                        ->pluck('amount')
                                        ->sum();
        $credit = (float) $report_credit - (float) $report_debit;
        $report['credit'] = (int) $credit;

        $earned_credit = WalletTransactionDetail:: whereDate('trans_date',$today)
                                                ->where('user_id', $request->user_id)
                                                ->where('transaction_type', 'CREDIT')
                                                ->where('payment_type', 'COMMISSION')
                                                ->pluck('total_amount')
                                                ->sum();
        $earned_debit = WalletTransactionDetail:: whereDate('trans_date',$today)
                                            ->where('user_id', $request->user_id)
                                            ->where('transaction_type', 'DEBIT')
                                            ->where('payment_type', 'COMMISSION')
                                            ->pluck('total_amount')
                                            ->sum();

        $earned = (float) $earned_credit - (float) $earned_debit;
        $report['earned'] = (int) $earned;
                                          
        $success = $report;
        $statusMsg = "Success!!";
        return $this->sendSuccess($success, $statusMsg);

       
    }


    public function balanceReceivedByFos(Request $request){

        $balanceDtls = WalletTransactionDetail::where('user_id', $request->user_id)
                                            ->where('transaction_type', 'CREDIT')
                                            ->where('payment_type', Config::get('constants.PAYMENT_TYPE.PAYMT_WALLET'))
                                            ->where('payment_mode', Config::get('constants.PAYMENT_GTWAY_TYPE.DIRECT_TRANSFER'));
        if ($request->has('from_date') && isset($request->from_date)) {
            $fromDate = $request->get('from_date');
            $balanceDtls->whereDate('trans_date', '>=', $fromDate);
        } 
        // else {
        //     $fromDate = now();
        //     $balanceDtls->whereDate('trans_date', $fromDate);
        // }

        if ($request->has('to_date') && isset($request->to_date)) {
            $toDate = $request->get('to_date');
            $balanceDtls->whereDate('trans_date', '<=', $toDate);
        } 

        $balanceDtls =$balanceDtls->get();

        if(count($balanceDtls)>0){
            
            $statusMsg = "Success!!";
            return $this->sendSuccess($balanceDtls, $statusMsg);
        }
        return $this->sendError("No record Found");

    }

}
