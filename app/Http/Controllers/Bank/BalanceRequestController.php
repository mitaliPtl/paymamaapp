<?php

namespace App\Http\Controllers\Bank;

use App\BalanceRequest;
use App\BankAccount;
use App\File;
use App\Http\Controllers\Controller;
use App\Role;
use App\SmsTemplate;
use App\User;
use App\ApplicationDetail;
use App\WalletTransactionDetail;
use App\ExpensesReport;
use Auth;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BalanceRequestController extends Controller
{
    /**
     * Balance Request View
     */
    public function index(Request $request)
    {
        
        $bankAccounts = BankAccount::select('bank_name')->distinct()->where('is_deleted', Config::get('constants.NOT-DELETED'))->get();
        if($request->get('bank_name')){
            $bankName=$request->get('bank_name');
            $bankAccountNumbers = BankAccount::where('is_deleted', Config::get('constants.NOT-DELETED'))->where('bank_name', 'like', '%'.$bankName.'%')->get();
        }

        return view('modules.bank.balance_request', compact('bankAccounts', 'request'));
    }
    /**
     * Get the Account Numbers of Bank by Bank Name
     */
     
     public function get_bankAccountNumbers(Request $request){
        $bankName=$request->bank_name;
        //print_r($bankName);
        
        $data['bankAccounts'] = BankAccount::where('is_deleted', Config::get('constants.NOT-DELETED'))->where('bank_name', 'like', '%'.$bankName.'%')->get();
        
        return response()->json($data);
     }
     /**
     * Get the Accounts Mode by Account Number
     */
     
     public function get_bankAccountMode(Request $request){
        $acNumber=$request->account_number;
        //echo $acNumber;
        $bankAccounts = BankAccount::where('is_deleted', Config::get('constants.NOT-DELETED'))->where('account_no', 'like', '%'.$acNumber.'%')->get();
        
        $data['mode']=explode("," ,$bankAccounts[0]['mode']);
        
        
        return response()->json($data);
     }
    /**
     * Get the Reports 
     */
     public function balance_request_report(Request $request){
        if (Auth::user()->userId == Config::get('constants.ADMIN')) {
            $balanceRequests = BalanceRequest::orderBy('trans_date', 'DESC');
            // ->get();
        } else {
            $balanceRequests = BalanceRequest::where('user_id', Auth::user()->userId)->orderBy('trans_date', 'DESC');
            // ->get();
        }
        if ($request->has('from_date') && isset($request->from_date)) {
            $fromDate = $request->get('from_date');
            $balanceRequests->whereDate('trans_date', '>=', $fromDate);
        }else{
            $fromDate = now();
            $balanceRequests->whereDate('trans_date', '>=', $fromDate);
        }

        if ($request->has('to_date') && isset($request->to_date)) {
            $toDate = $request->get('to_date');
            $balanceRequests->whereDate('trans_date', '<=', $toDate);
        }

        $balanceRequests = $balanceRequests->get();
        
        $qrCodeFilePath = "";
        $qrCodeResponse = File::where('name', 'qr_code')->first();
        if (isset($qrCodeResponse) && $qrCodeResponse->file_path) {
            $qrCodeFilePath = $qrCodeResponse->file_path;
        }
        $filtersList = Config::get('constants.BALANCE_REQUEST_FILTER');
        $balanceRequests = $this->modifyBalanceReq($balanceRequests);
        $bankAccounts = BankAccount::select('bank_name')->distinct()->where('is_deleted', Config::get('constants.NOT-DELETED'))->get();
        //$bankAccounts = $this->mofifyBankAccounts($bankAccounts);
        
        //$bankAccountsBalance = BankAccount::where('is_deleted', Config::get('constants.NOT-DELETED'))->get();
        

        $total_amt = $this->calcTotalAmount($balanceRequests);
        return view('modules.bank.balance_request_report', compact('balanceRequests', 'bankAccounts', 'qrCodeFilePath', 'total_amt', 'request','filtersList'));
     }
     
    
    
    /**
     * Calculate Total Request Amount
     */
    public function calcTotalAmount($record){
        $total = 0.000;

        foreach ($record as $key => $value) {
            $total = (float) $total + (float) $value['amount'];
        }

        return $total;
    }

    /**
     * Modify balance Request
     */
    public function modifyBalanceReq($balanceRequests)
    {
        $result = null;
        if ($balanceRequests) {
            foreach ($balanceRequests as $i => $request) {
                $balanceRequests[$i]['user_name'] = "";
                $balanceRequests[$i]['reciept_src'] = "";
                $balanceRequests[$i]['user_name'] = User::getStoreNameById($request->user_id);
                $balanceRequests[$i]['reciept_src'] = isset(File::where('id', $request->receipt_file)->pluck('file_path')[0]) ? File::where('id', $request->receipt_file)->pluck('file_path')[0] : '';
            }
            $result = $balanceRequests;
        }
        return $result;
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
     * Store Bank Account
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bank_name' => 'required|string|max:255',
            'reference_id' => 'required|string|max:255',
            'amount' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        
        $response = BalanceRequest::create([
            "deposit_date"=>$request->get('date_deposited'),
            "account_holder_name"=>$request->get('account_holder_name'),
            "account_holder_bank_name"=>$request->get('account_holder_bank_name'),
            "account_holder_mode"=>$request->get('account_holder_mode'),
            'transaction_id' => $this->generate_PMPR_id(),
            'bank' => "".$request->get('bank_name')."-".$request->get('account_number')."",
            'mode' => $request->get('transfer_mode'),
            'reference_id' => $request->get('reference_id'),
            'amount' => $request->get('amount'),
            'user_id' => Auth::user()->userId,
            'role' => (Role::where('roleId', Auth::user()->roleId)->pluck('role') ? Role::where('roleId', Auth::user()->roleId)->pluck('role')[0] : ''),
            'mobile_no' => Auth::user()->mobile,
            'message' => $request->get('message'),
            'receipt_file' => $request->get('receipt_file'),
            'trans_date' => now(),
            'status' => "PENDING",
        ]);

        if ($response) {
            
            return redirect('/balance_request_report')->with(['success', "Balance Request sent!"]);
        }else{
            return redirect('/balance_request')->with('error', "Oops, Something went Wrong Please try again...!");
        }
    }


        public function generate_PMPR_id(){
            $postFix="PMPR";
            $latestRow= BalanceRequest::latest()->first();
            $latestRowId=$latestRow->id + 1;
            $transactionId=$postFix.$latestRowId;
            //print_r($transactionId);
            return $transactionId;
        }
   

    /**
     * Balance Request Reply
     */
    public function balReqReply(Request $request)
    {
        $response = "";
        if ($request->admin_reply) {
            $balReq = BalanceRequest::findOrFail((int) $request->bal_req_id);
            $balReq->admin_reply = $request->admin_reply;
            $response = $balReq->save();
        }
        if ($response) {
            return redirect('/balance_request')->with('success', "Reply sent successfully!!");
        }
    }

    /**
     * Transfer Balance
     */
    public function transferBalance(Request $request)
    {
        $response = "";
        $smsData = [];
       
        if ($request->mpin) {
            $balReq = BalanceRequest::findOrFail((int) $request->trans_req_id);
            // $bnk_name = explode('_', $balReq->bank);
            // $bnk_name = $bnk_name[0];
            // $bnk_dtls = $this->getBankDtls($bnk_name);
            // print_r($bnk_dtls['bank_name']);
            // $blnce = (float) $bnk_dtls[0]['balance'] + (float) $balReq->amount;
            // exit();

            if ($balReq) {
                $user = User::find((int) $balReq->user_id);
                $smsData['last_balance_amount'] = $user->wallet_balance;
                $user->wallet_balance = $user->wallet_balance + $balReq->amount;
                $userResponse = $user->save();
            }
            $smsData['updated_balance_amount'] = $user->wallet_balance;
            $smsData['mobile'] = $user->mobile;

            if ($userResponse) {
                // ASK
                $walletResponse = WalletTransactionDetail::create([
                    'order_id' => $balReq->reference_id,
                    'user_id' => $balReq->user_id,
                    'transaction_status' => "SUCCESS",
                    'response_msg' => "",
                    'bank_trans_id' => $balReq->reference_id,
                    'transaction_type' => "CREDIT",
                    'transaction_id' => "",
                    'trans_date' => now(),
                    'payment_type' => Config::get('constants.PAYMENT_TYPE.PAYMT_WALLET'),
                    'payment_mode' => Config::get('constants.PAYMENT_GTWAY_TYPE.DIRECT_TRANSFER'),
                    'total_amount' => $balReq->amount,
                    'balance' => $user->wallet_balance,
                ]);
            }
            $smsData['amount'] = $balReq->amount;
            if ($walletResponse) {
                $balReq->status = "COMPLETED";
                $balReq->admin_reply = $request->message;
                $balReqResponse = $balReq->save();
                if ($balReqResponse) {
                    $msgRes = $this->sendSmswithTransactionInfo($smsData);
                    // $msgRes = TRUE;
                    if ($msgRes) {
                        $blnce = 0.000;
                        $bnk_name = '';
                        $bnk_id = '';
                        $bnk_name = '';
                       
                        // $bnk_name = $balReq->bank;
                        if($balReq->bank == 'QR_CODE'){
                           
                            $bnk_name = 'ICICI BANK';
                            $bnk_dtls = $this->getBankDtls($bnk_name);
                            $blnce = (float) $bnk_dtls['balance'] + (float) $balReq->amount;
                            $bnk_id = $bnk_dtls->id;
                            $bnk_name = $bnk_dtls->bank_name;

                        }else{

                            $bnk_name = explode('_', $balReq->bank);
                            $bnk_name = $bnk_name[0];
                            $bnk_dtls = $this->getBankDtls($bnk_name);
                            $blnce = (float) $bnk_dtls['balance'] + (float) $balReq->amount;
                            $bnk_id = $bnk_dtls->id;
                            $bnk_name = $bnk_dtls->bank_name;

                        }
                       
                        $expenses_report = ExpensesReport::create([
                                                                        "user_id"=> $balReq->user_id,
                                                                        "bank_id"=> $bnk_id,
                                                                        // "category_id" => '',
                                                                        "date" => now(),
                                                                        "category_bank" => "DEPOSIT",
                                                                        "account_name" => $bnk_dtls->bank_name,
                                                                        "description"=> $request->message,
                                                                        "cr_dr"=> "CREDIT",
                                                                        "amount" => $balReq->amount,
                                                                        "balance" => $blnce
                                                                ]);

                        $bnk_blnce = BankAccount::where('id', $bnk_id)->update([ "balance" =>$blnce ]);

                        return back()->with('success', "Balance Transfered Successfully !!");
                    }
                }

            } else {
                return redirect('/balance_request')->with('error', "Transfer Failed!!");
            }
        }
    }

    public function getBankDtls($bank_name){

        $bankDtls = BankAccount::where('bank_name', $bank_name)->get();
        return $bankDtls[0];
    }

    /**
     * Send Sms to User on Successfull Transaction from Payment Gateway
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

    /**
     * Get QR Code API
     */
    public function getQRCode(Request $request)
    {
        $fetchuserid=User::where('userId',$request->user_id)->first();
        $qr_id=$fetchuserid->qr_id;
        $qrCodeFilePath = [];
        $qrCodeResponse = ApplicationDetail::where('alias', 'qr_code')->first();
        if (isset($qrCodeResponse) ) {
            $qrCodeFilePath['file_name'] = $fetchuserid->store_name;
            $qrCodeFilePath['file_path'] = "https://api.apiclub.in/api/fetch_qr/".$qr_id;   //$qrCodeResponse->value;
        }

        if (isset($qrCodeFilePath)) {
            $statusMsg = "Success!!";
            return $this->sendSuccess($qrCodeFilePath, $statusMsg);
        } else {
            return $this->sendError("Failure!!");
        }
    }

    /**
     * Get Bank List API
     */
    public function getBRBankListAPI()
    {
        $bankAccounts = BankAccount::where('is_deleted', Config::get('constants.NOT-DELETED'))->get();
        $bankAccounts = $this->mofifyBankAccounts($bankAccounts);
        $response = "";
        $msg = "";
        if (isset($bankAccounts) && !empty($bankAccounts)) {
            $msg = "Success!!";
            $response = $this->sendSuccess($bankAccounts, $msg);
        } else {
            $msg = "Failure!!";
            $response = $this->sendError($msg);
        }

        return $response;
    }

    /**
     * Balance Request API
     */
    public function balanceRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bank' => 'required|string|max:255',
            'reference_id' => 'required|string|max:255',
            'amount' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $response = BalanceRequest::create([
            'bank' => $request->get('bank'),
            'mode' => $request->get('mode'),
            'reference_id' => $request->get('reference_id'),
            'amount' => $request->get('amount'),
            'user_id' => $request->user_id,
            'role' => (isset(Role::where('roleId', $request->role_id)->pluck('role')[0]) ? Role::where('roleId', $request->role_id)->pluck('role')[0] : ''),
            'mobile_no' => (isset(User::where('userId', $request->user_id)->pluck('mobile')[0]) ? User::where('userId', $request->user_id)->pluck('mobile')[0] : ''),
            'message' => $request->get('message'),
            'receipt_file' => $request->get('receipt_file'),
            'trans_date' => now(),
            'status' => "PENDING",
        ]);

        if ($response) {
            $success['status'] = "Success!!";
            $statusMsg = "Balance request sent successfully!!";
            return $this->sendSuccess($success, $statusMsg);
        } else {
            return $this->sendError("Failure!!");
        }
    }

    /**
     * Balance Request Report API
     */
    public function balanceRequestReportApi(Request $request)
    {
        
        $balanceRequests = BalanceRequest::where('user_id', $request->user_id)->orderBy('trans_date', 'DESC');
        
        if ($request->has('from_date') && isset($request->from_date)) {
            $fromDate = $request->get('from_date');
            $balanceRequests->whereDate('trans_date', '>=', $fromDate);
        } else {
            $fromDate = now();
            $balanceRequests->whereDate('trans_date', $fromDate);
        }

        if ($request->has('to_date') && isset($request->to_date)) {
            $toDate = $request->get('to_date');
            $balanceRequests->whereDate('trans_date', '<=', $toDate);
        }

        $balanceRequests = $balanceRequests->get();

        if (isset($balanceRequests) && count($balanceRequests) > 0) {
            
            $balanceRequests = $balanceRequests->makeHidden([
                'id',
                'user_id',
                'role',
                'mobile_no',
                'created_at',
                'updated_at'
            ]);

            $statusMsg = "Success!!";
            return $this->sendSuccess($balanceRequests, $statusMsg);
        } else {
            return $this->sendError("No records found!!");
        }
    }

    public function declineBalRequest($id = null, Request $request)
    {
        // print_r($request->all());
        // print_r($id);

        $decline = BalanceRequest::where('id', $id)->update(['status'=>'DECLINE']);
        if($decline){
            return back()->with('success', "Balance Request Declined !!");
        }else{
            return back()->with('error', "Balance Request Not Declined !!");
        }
    }
}
