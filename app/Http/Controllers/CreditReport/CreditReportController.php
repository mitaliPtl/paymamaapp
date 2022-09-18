<?php

namespace App\Http\Controllers\CreditReport;
use App\Complaint;
use App\Http\Controllers\Controller;
use App\User;
use App\TransactionDetail;
use App\TransferCreditReport;

use App\WalletTransactionDetail;
use Auth;
use Config;
use DB;
use App\SmsTemplate;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use PDF;
use Illuminate\Support\Facades\Validator;

class CreditReportController extends Controller
{
    public function index(Request $request)
    { 
            // print_r($request->all());
            $user_type = $request->input('report');
            $loggeduser = Auth::user()->userId;

            

            // $loggedrole = Config::get('constants.CREDIT_REPORT_FILTER');

            // $serviceType = "CREDIT_REPORT";
            // $recordsTH = $this->setTableHeader($loggeduser, $serviceType);
            // $recordsTH = Config::get('constants.CREDIT_REPORT_RT_TD');
            
           
            $records = $this->getRelatedUsers($loggeduser, $user_type, $request);
            // $records = $this->modifyRecords($recordsTH, $records);
            // print_r($records);
            // exit();
            // $creditReport = TransferCreditReport::where('transfer_by_id', $loggeduser)
            //                                     ->where('transfer_to_role', Config::get($user_type))
            //                                     ->get();
            // print_r($records);
            $filtersList = Config::get('constants.CREDIT_REPORT_FILTER');
            $pageName= $user_type;
           return view('modules.credit_records.credit_records', compact('records', 'pageName', 'filtersList', 'request')); 

    }

    
    
    public function getRelatedUsers($user_id, $user_type, $request){
        $user_type = "constants.".$user_type;

        $users = User::where('roleId', Config::get($user_type))
                        ->where('isDeleted', Config::get('constants.NOT-DELETED'))
                        ->where('isSpam', '0');
                        // ->get();
        if ($request->role_id == Config::get('constants.FOS')) {
            $users = $users ->where('fos_id', $user_id);
        }else{
            $users = $users ->where('parent_user_id', $user_id);

        }
        if(isset($request->filter_value) && $request->filter_value){
            $filter_value = $request->filter_value;
            $users = $users->where(function($q) use ($filter_value) {
                                        $q->where('tbl_users.store_name','like', $filter_value."%")
                                        ->orWhere('tbl_users.username','like', $filter_value."%")
                                        ->orWhere('tbl_users.mobile', 'like', $filter_value."%")
                                        ->orWhere('tbl_users.first_name','like', $filter_value.'%')
                                        ->orWhere('tbl_users.last_name','like', $filter_value.'%');
                                    });
        }

        $users = $users->get();
        return $users;
                        
    }
     /**
     * Filter Transaction Reports Data
     */
    public function filter($loggeduser, $user_type, $request)
    {
        $user_type = "constants.".$user_type;
        $creditReport = TransferCreditReport::leftJoin('tbl_users', 'tbl_transfer_credit_report.transfer_to_id', '=', 'tbl_users.userId')
                                            ->where('transfer_by_id', $loggeduser)
                                            ->where('transfer_to_role', Config::get($user_type));
                                                
        
        if ($request->has('from_date') && isset($request->from_date)) {
            $fromDate = $request->get('from_date');
            $creditReport->whereDate('trans_date', '>=', $fromDate);
        } else {
            $fromDate = now();
            $creditReport->whereDate('trans_date', $fromDate);
        }

        if ($request->has('to_date') && isset($request->to_date)) {
            $toDate = $request->get('to_date');
            $creditReport->whereDate('trans_date', '<=', $toDate);
        }

        // if (isset($request->api_id) && $request->api_id) {
        //     $tranDtls->where('tbl_transaction_dtls.api_id', $request->api_id);
        // }

        // if (isset($request->service_id) && $request->service_id) {
        //     $tranDtls->where('tbl_transaction_dtls.service_id', $request->get('service_id'));
        // }

        // if (isset($request->operator_id) && $request->operator_id) {
        //     $tranDtls->where('tbl_transaction_dtls.operator_id', $request->get('operator_id'));
        // }

        // if (isset($request->order_status) && $request->order_status) {
        //     $tranDtls->where('tbl_transaction_dtls.order_status', $request->get('order_status'));
        // }

        // if (Auth::id() != Config::get('constants.ADMIN')) {
        //     if (Auth::id()) {
        //         $childResponse = User::where('parent_user_id', Auth::id())->pluck('userId');
        //         if (count($childResponse) > 0) {
        //             $childResponse = $childResponse->toArray();
        //             array_push($childResponse, Auth::id());
        //             $tranDtls->whereIn('tbl_transaction_dtls.user_id', $childResponse);
        //         } else {
        //             $tranDtls->where('tbl_transaction_dtls.user_id', Auth::user()->userId);
        //         }
        //     }
        // } else {
        //     if (isset($request->username_mobile) && $request->username_mobile) {
        //         $userId = User::where('mobile', $request->username_mobile)
        //             ->orWhere('username', $request->username_mobile)->pluck('userId')->first();
        //         if ($userId) {
        //             $tranDtls = $tranDtls->where('tbl_transaction_dtls.user_id', $userId);
        //         }
        //     }
        // }

        return $creditReport->get();
    }

    /**
     * Modify Recharge Reports List
     */
    public function modifyRecords($tableHeads, $reportList)
    {
        $result = [];
        if ($reportList) {
            foreach ($reportList as $repInd => $report) {
                $keyList = [];
                $reportList[$repInd]['mobile'] = "";
                // $reportList[$repInd]['response'] = "";
                foreach ($tableHeads as $headInd => $head) {
                    $keyList['id'] = $report['id'];

                    if (isset($report[$head['label']])) {
                        $label_val = $report[$head['label']];

                        

                        // if ($head['label'] == "trans_date") {
                        //     $label_val = substr($report['trans_date'], 0, 8);
                        // }

                        $keyList[$head['label']] = $label_val;
                    } else {

                        $keyList[$head['label']] = "";
                    }
                }
                array_push($result, $keyList);
                $keyList = [];

            }
        }

        return $result;
    }


    public function index_history($id, Request $request)
    { 
            // print_r($request->all());
            $user_type = $request->input('report');
            $loggeduser = Auth::user()->userId;
            // $loggedrole = Config::get('constants.CREDIT_REPORT_FILTER');

            // $serviceType = "CREDIT_REPORT";
            // $recordsTH = $this->setTableHeader($loggeduser, $serviceType);
            // $recordsTH = Config::get('constants.CREDIT_REPORT_RT_TD');
            
           
            $records = $this->filter($loggeduser, $user_type, $request);
            // $records = $this->modifyRecords($recordsTH, $records);
            // print_r($records);
            // exit();
            // $creditReport = TransferCreditReport::where('transfer_by_id', $loggeduser)
            //                                     ->where('transfer_to_role', Config::get($user_type))
            //                                     ->get();
            // print_r($records);
            $filtersList = Config::get('constants.CREDIT_REPORT_FILTER');
            $pageName= $user_type;
           return view('modules.credit_records.credit_records', compact('records', 'pageName', 'filtersList', 'request')); 

    }

    public function userCreditHistory($id , Request $request){

        // print_r($id);
        $user_id = Auth::user()->userId;
        $records = $this->getUserCreditHistory($id, $user_id, $request);
        // print_r($user_history);
        $user_dtls = $this->getUserDtlsById($id);
        // print_r($user_dtls['username']);
        $filtersList = Config::get('constants.CREDIT_REPORT_FILTER');
        $pageName= $user_dtls['store_name'];

        return view('modules.credit_records.credit_history', compact('records', 'pageName', 'filtersList', 'request')); 


       
    }
   
    public function getUserCreditHistory($id, $user_id, $request, $isAPI=null){

        // $user_type = "constants.".$user_type;
        $creditReport = TransferCreditReport::leftJoin('tbl_users', 'tbl_transfer_credit_report.transfer_to_id', '=', 'tbl_users.userId')
                                            ->where('transfer_by_id', $user_id)
                                            ->where('transfer_to_id', $id)
                                            ->orderBy('trans_date', 'DESC');
                                                
        
        if ($request->has('from_date') && isset($request->from_date)) {
            $fromDate = $request->get('from_date');
            $creditReport->whereDate('trans_date', '>=', $fromDate);
        }else {
            // if(!$isAPI){
                $fromDate = now();
                $creditReport->whereDate('trans_date', $fromDate);
                // }
        }

        if ($request->has('to_date') && isset($request->to_date)) {
            $toDate = $request->get('to_date');
            $creditReport->whereDate('trans_date', '<=', $toDate);
        }

        return $creditReport->get();

    }   

    public function getUserDtlsById($id){
            $user_dtls = User:: where('userId', $id)->get();
            return $user_dtls[0];
    }


    public function creditReturn(Request $request){
        // print_r($request->all());
        $dbSmsData = [];
        $user = User::find((int) $request->user_id);
        $dbSmsData['last_balance_amount'] = $user->wallet_balance;
        $user->wallet_balance = $user->wallet_balance - $request->return_amt;
        $userResponse = $user->save();

        $dbSmsData['amount'] = $request->revert_amount;
        $dbSmsData['updated_balance_amount'] = $user->wallet_balance;
        $dbSmsData['mobile'] = $user->mobile;

        $loggedUser = User::find((int) Auth::id());
        $crSmsData['last_balance_amount'] = $loggedUser->wallet_balance;

        if ($loggedUser) {
            $loggedUser->wallet_balance = (float)$loggedUser->wallet_balance + (float)$request->return_amt;
            $loggedUserResponse = $loggedUser->save();

            
            $crSmsData['amount'] = $request->return_amt;
            $crSmsData['updated_balance_amount'] = $loggedUser->wallet_balance;
            $crSmsData['mobile'] = $loggedUser->mobile;
            $this->sendSmswithTransactionInfo($crSmsData);
            $this->notifyWithTransactionInfo($crSmsData, $loggedUser->userId);
            // $walletDTCRResponse = WalletTransactionDetail::create([
            //     'user_id' => Auth::id(),
            //     'transaction_status' => "SUCCESS",
            //     'response_msg' => "",
            //     'transaction_type' => "CREDIT",
            //     'transaction_id' => "",
            //     'trans_date' => now(),
            //     'payment_type' => Config::get('constants.PAYMENT_TYPE.PAYMT_WALLET'),
            //     'payment_mode' => Config::get('constants.PAYMENT_GTWAY_TYPE.REVERT'),
            //     'total_amount' => $request->return_amt,
            //     'balance' => $loggedUser->wallet_balance,
            // ]);

            // if($walletDTCRResponse){
            //     $this->sendSmswithTransactionInfo($crSmsData);
            // }
        }

        // $walletDBResponse = WalletTransactionDetail::create([
        //     // 'order_id' => $request->reference_id,
        //     'user_id' => $request->user_id,
        //     'transaction_status' => "SUCCESS",
        //     'response_msg' => "",
        //     'bank_trans_id' => $request->reference_id,
        //     'transaction_type' => "DEBIT",
        //     'transaction_id' => "",
        //     'trans_date' => now(),
        //     'payment_type' => Config::get('constants.PAYMENT_TYPE.PAYMT_WALLET'),
        //     'payment_mode' => Config::get('constants.PAYMENT_GTWAY_TYPE.RETURN'),
        //     'total_amount' => $request->return_amt,
        //     'balance' => $user->wallet_balance,
        // ]);
        $use_debit_value = (float)$user->distributor_credit - (float) $request->return_amt;
        $update_debit = User::where('userId', $request->user_id)
                            ->update(['distributor_credit'=> $use_debit_value]);
        $transfer_credit = TransferCreditReport::create([
                                                // 'reference_id' => $request->reference_id,
                                                // 'payment_type' => $request->payment_type,
                                                'transfer_by_id' => $loggedUser->userId,
                                                'transfer_by_role' => $loggedUser->roleId,
                                               'transfer_to_id' => $request->user_id,
                                               'transfer_to_role' =>  $user->roleId,
                                               'mobile_transfer_by' =>$user->mobile,
                                               'amount'=> $request->return_amt,
                                                'trans_date' => now(),
                                                'transaction_type' => "DEBIT",
                                                'balance' => $use_debit_value,
                                                'transfer_type' => "CREDIT_RETURN",
                                                'created'=>now()

                                ]);
        if ($transfer_credit) {
            $dbsmsRes = $this->sendDBSmswithTransactionInfo($dbSmsData);
            $dbNotifyRes = $this->sendDBNotifywithTransactionInfo($dbSmsData, $request->user_id);

            if($dbsmsRes){
                return back()->with('success', "Revert Successful!!");
            }
        }

    }

    public function creditReturnAPI(Request $request){

        $dbSmsData = [];
        $user = User::find((int) $request->beneficiary_id);
        // $dbSmsData['last_balance_amount'] = $user->wallet_balance;
        // $user->wallet_balance = $user->wallet_balance - $request->amount;
        // $userResponse = $user->save();

        $dbSmsData['amount'] = $request->amount;
        $dbSmsData['updated_balance_amount'] = $user->wallet_balance;
        $dbSmsData['last_balance_amount'] = $user->wallet_balance;
        $dbSmsData['mobile'] = $user->mobile;

        $loggedUser = User::find((int) $request->user_id);
        $crSmsData['last_balance_amount'] = $loggedUser->wallet_balance;

        if ($loggedUser) {
            // $loggedUser->wallet_balance = (float)$loggedUser->wallet_balance + (float)$request->amount;
            // $loggedUserResponse = $loggedUser->save();

            
            $crSmsData['amount'] = $request->amount;
            $crSmsData['updated_balance_amount'] = $loggedUser->wallet_balance;
            $crSmsData['mobile'] = $loggedUser->mobile;
            // $this->sendSmswithTransactionInfo($crSmsData);
            // if($walletDTCRResponse){
            //     $this->sendSmswithTransactionInfo($crSmsData);
            // }
        }

       
        if($request->role_id == Config::get('constants.FOS')){

            $use_debit_value = (float)$user->fos_credit - (float) $request->amount;
            $update_debit = User::where('userId', $request->beneficiary_id)
                                ->update(['fos_credit'=> $use_debit_value]);

        }elseif($request->role_id == Config::get('constants.DISTRIBUTOR')){

            $use_debit_value = (float)$user->distributor_credit - (float) $request->amount;
            $update_debit = User::where('userId', $request->beneficiary_id)
                            ->update(['distributor_credit'=> $use_debit_value]);
        }
        

        $transfer_credit = TransferCreditReport::create([
                                                // 'reference_id' => $request->reference_id,
                                                // 'payment_type' => $request->payment_type,
                                                'transfer_by_id' => $loggedUser->userId,
                                                'transfer_by_role' => $loggedUser->roleId,
                                               'transfer_to_id' => $request->beneficiary_id,
                                               'transfer_to_role' =>  $user->roleId,
                                               'mobile_transfer_by' =>$user->mobile,
                                               'amount'=> $request->amount,
                                                'trans_date' => now(),
                                                'transaction_type' => "DEBIT",
                                                'balance' => $use_debit_value,
                                                'transfer_type' => "CREDIT_RETURN",
                                                'created'=>now()

                                ]);
        if ($transfer_credit) {
            // $dbsmsRes = $this->sendDBSmswithTransactionInfo($dbSmsData);
            // $dbNotifyRes = $this->sendDBNotifywithTransactionInfo($dbSmsData, $request->beneficiary_id);

            $dbsmsRes = true;
            if($dbsmsRes){
              $statusMsg = "Success!!";

                return $this->sendSuccess($transfer_credit, $statusMsg);
            }
        }else{
            return $this->sendError("Credit Return");
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
                $result = $this->sendSms($msg, $smsData['mobile']);
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
                $result = $this->sendSms($msg, $smsData['mobile']);
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

    public function getDisUsersAPI(Request $request){

        $user_type = $request->user_type;
        $loggeduser =$request->user_id;
        $records = $this->getRelatedUsers($loggeduser, $user_type, $request);
        
        if(count($records)>0){
            $records = $this->modifyUsers($records, $request->role_id);
            $statusMsg = "Success!!";

            return $this->sendSuccess($records, $statusMsg);

        }
           return $this->sendError("Not Found!!");
        
       
    }    

    public function modifyUsers($userlist, $role_id){
        
        $result_ = [];
         foreach ($userlist as $i => $value) {
            $result_[$i]['user_id'] = $value->userId;
            $result_[$i]['role_id'] = $value->roleId;
            $result_[$i]['store_name'] = $value->store_name;
            $result_[$i]['username'] = $value->username;
            $result_[$i]['mobile'] = $value->mobile;
            $result_[$i]['total_sales'] = (int) TransactionDetail::getSalesByUserId($value->userId);

            if($role_id == Config::get('constants.DISTRIBUTOR')){   
                $result_[$i]['distributor_credit'] = (int)$value->distributor_credit; 
            }
            $result_[$i]['fos_credit'] = (int)$value->fos_credit;
           }

           return $result_;
    }

    public function userCreditHistoryAPI(Request $request){
        $user_id = $request->user_id;
        $beneficiary_id = $request->beneficiary_id;
        $isAPI = true;
        $records = $this->getUserCreditHistory($beneficiary_id, $user_id, $request, $isAPI);
        $user_dtls = $this->getUserDtlsById($beneficiary_id);
        if(count($records)>0){
            $records = $this->modifyUsersHistroy($records, $user_dtls);
            
            $statusMsg = "Success!!";

            return $this->sendSuccess($records, $statusMsg);

        }
           return $this->sendError("Today, No Credit History Found");
        
    }

    public function modifyUsersHistroy($records, $user_dtls){
        $result_ = [];
        foreach ($records as $i => $value) {
           $result_[$i]['trans_date'] = $value->trans_date;
           $result_[$i]['transfer_type'] = $value->transfer_type;
           $result_[$i]['transaction_type'] = $value->transaction_type;
           $result_[$i]['amount'] = (int)$value->amount;
           $result_[$i]['balance'] = (int)$value->balance;
          }
        //   $result_['user_info']['store_name'] = $user_dtls->store_name;
        //   $result_['user_info']['username'] = $user_dtls->username;
        //   $result_['user_info']['user_id'] = $user_dtls->userId;
        //   $result_['user_info']['role_id'] = $user_dtls->roleId;
          
        //   $result_['store_name'] = $user_dtls->store_name;
        //   $result_['username'] = $user_dtls->username;
        //   $result_['user_id'] = $user_dtls->userId;
        //   $result_['role_id'] = $user_dtls->roleId;
          

          return $result_;
    }
  
   
}