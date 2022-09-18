<?php

namespace App\Http\Controllers\Other;
use App\Http\Controllers\Controller;
use App\BalanceRequest;
use App\KycDetail;
use App\Role;
use App\Complaint;
use App\OperatorSetting;
use App\TransactionDetail;
use App\WalletTransactionDetail;
use App\VirtualTransactionDetail;
use App\PaymentGatewayReport;
use App\ApiSetting;
use App\User;
use Auth;
use DB;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramController extends Controller
{
    public function telegram(Request $request)
    {
        /*
        start - Start the Bot Now
        mybalance - Know your Current Account Balance
        myvirtualaccount - Know your Virtual Account Details
        lastrechargestatus - Know your last Recharge Status
        complainstatus - Know your complain status
        myqrcode - Get your Payment QR Code
        last5transactions - Last 5 transaction details
        distributorinfo - Distributor or Fos Info
        supportnumber - Get Support Contact Number
        operatorhelpline - Get Operator Contact Details
        */
        $telegram = new \App\Packages\Telegram\Telegram(Config::get('constants.TELEGRAM_BOT_ID'));
        $result = $telegram->getData();
        // $callbackQuery = $telegram->Callback_Query();
        $callbackQuery = isset($result['callback_query']) ? $result['callback_query'] : "";
        $messageQuery = isset($result['message']) ? $result['message'] : "";
        $chat_id = "";
        $text = "";
        
        if($messageQuery != "") {
            $text = $result['message']['text'];
            $chat_id = $result['message']['chat']['id'];
            // $this->send_telegram($text,$chat_id);
            // exit();
        } elseif ($callbackQuery != "") {
            $text = $telegram->Callback_Data();
            $chat_id = $telegram->Callback_ChatID();
            // $this->send_telegram($text,$chat_id);
            // exit();
        } else {
            exit();
            
        }
        
        
        
        
        if($text == "/start" || $text == "start") {
            $option = array(
                array($telegram->buildInlineKeyBoardButton("My Account Balance", "","/mybalance"), $telegram->buildInlineKeyBoardButton("My Virtual Account", "","/myvirtualaccount")),
                array($telegram->buildInlineKeyBoardButton("Transaction Status", "","/rechargestatus"), $telegram->buildInlineKeyBoardButton("Complain Status", "","/complainstatus")),
                array($telegram->buildInlineKeyBoardButton("Last 5 Transactions", "","/last5transactions"), $telegram->buildInlineKeyBoardButton("Distributor & FOS Info", "","/distributorinfo")),
                array($telegram->buildInlineKeyBoardButton("PayMama Support", "","/supportnumber"), $telegram->buildInlineKeyBoardButton("Operator Helpline", "","/operatorhelpline")),
                array($telegram->buildInlineKeyBoardButton("My Telegram ID", "","/mychatid"), $telegram->buildInlineKeyBoardButton("How to Connect?", "","/howto")),
            );
            $keyb = $telegram->buildInlineKeyBoard($option);
            // $msg = "Hi,\n";
            $msg = "Please update this telegram ID : ".$chat_id." on your PayMama Profile Section.";
            $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => $msg);
            $telegram->sendMessage($content);
            exit();
        } elseif($text == "/mychatid" || $text == "mychatid") {
            $msg = "Please update this telegram ID : ".$chat_id." on your PayMama Profile Section.";
            $this->send_telegram($msg,$chat_id);
            exit();
        } elseif($text == "/howto" || $text == "howto") {
            $msg = "Please update this telegram ID : ".$chat_id." on your PayMama Profile Section.";
            $this->send_telegram($msg,$chat_id);
            exit();
        }
        

        $check_user = $this->check_user($chat_id);
        if(!$check_user) {
            $msg = 'Oops! Seems like you haven\'t linked telegram with your PayMama Account.';
            $this->send_telegram($msg,$chat_id);
            exit();
        }
        if($text == "/mybalance" || $text == "mybalance") {
            $msg = "Your current wallet balance is Rs. ".$check_user['wallet_balance'];
        } elseif($text == "/rechargestatus" || $text == "rechargestatus") {
            $msg = "Please send your recharge ID starting with SP";
        } elseif($text == "/complainstatus" || $text == "complainstatus") {
            $msg = "Please send your Complain ID starting with CM";
        } elseif($text == "/myvirtualaccount" || $text == "myvirtualaccount") {
            $msg = "Oops! Please complete your KYC first to generate your virtual account";
            // need to check account approve status, discussion with srikant pending
            if($check_user['va_id'] != "") {
                $bank_name = "IDFC FIRST BANK";
                $holder_name = "PAYMAMA";
                $msg = "Please find your Virtual Account details mentioned below :-\n\n";
                $msg .= "<b>Account Holder Name :- ".$holder_name."</b>\n";
                $msg .= "<b>Account Number :- ".$check_user['va_account_number']."</b>\n";
                $msg .= "<b>IFSC Code :- ".$check_user['va_ifsc_code']."</b>\n";
                $msg .= "<b>Bank Name :- ".$bank_name."</b>\n";
                $msg .= "<b>UPI Address :- ".$check_user['va_upi_id']."</b>\n\n";
            } else {
                $msg = "Oops! Please complete your KYC first to generate your virtual account";
            }
        } elseif($text == "/support" || $text == "support" || $text == "/help" || $text == "help") {
            $msg = "This is for help menu";
        } elseif($text == "last5transactions" || $text == "/last5transactions") {
            $check_txn = TransactionDetail::where('user_id', $check_user['userId'])->limit(5)->orderBy('trans_date','desc')->get();
            if(isset($check_txn) && count($check_txn)) {
                $msg = "Here is your last ".count($check_txn)." transactions details :-\n\n";
                // $msg = "\n";
                foreach($check_txn as $txn) {
                    $fetch = $this->getTrans($txn['order_id'],$check_user['userId']);
                    foreach($fetch as $id) {
                        $msg .= $id."\n";
                    }
                    $msg .= "\n";
                }
            } else {
                $msg = "Oops, no transaction found!";
            }
        } elseif(strpos($text, "CM") === 0 || strpos($text, "cm") === 0) {
            $complaint = Complaint::where('complaint_id', strtoupper($text))->where('user_id', $check_user['userId'])->get();
            if(isset($complaint) && count($complaint)) {
                $transaction = TransactionDetail::where('order_id', $complaint[0]['order_id'])->where('user_id', $check_user['userId'])->get();
                $operator_id = $transaction[0]['operator_id'];
                $operator = OperatorSetting::where('operator_id', $operator_id)->get();
                $template = DB::table('tbl_template')->where('template_id', '=', $complaint[0]['template_id'])->first();
                $msg = "Your complaint status for ".strtoupper($complaint[0]['complaint_id'])." is given below :- \n\n";
                $msg .= "Smart ID - ".$complaint[0]['order_id']."\n";
                if($transaction[0]['bank_transaction_id'] != "") {
                    $msg .= "Bank Reference ID - ".$transaction[0]['bank_transaction_id']."\n";
                } else {
                    $msg .= "Transaction ID - ".$complaint[0]['transaction_id']."\n";
                }
                $msg .= "Complaint Date - ".$complaint[0]['complaint_date']."\n";
                $msg .= "Complaint Status - ".strtoupper($complaint[0]['complaint_status'])."\n";
                $msg .= "Mobile Number - ".$transaction[0]['mobileno']."\n";
                $msg .= "Operator - ".$operator[0]['operator_name']."\n";
                $msg .= "Order Status - ".strtoupper($transaction[0]['order_status'])."\n";
                $msg .= "Message - ".$template->template."\n";
                $msg .= "Admin Reply - ".$complaint[0]['admin_reply']."\n";
                $msg .= "Last Update - ".$complaint[0]['admin_reply_date'];
            } else {
                $msg = "Oops, no complaints found with this complaint ID ".$text;
            }
        } elseif(strpos($text, "SP_old") === 0 || strpos($text, "sp_old") === 0) {
            $transaction = TransactionDetail::where('order_id', strtoupper($text))->where('user_id', $check_user['userId'])->get();
            if(isset($transaction) && count($transaction)) {
                $operator_id = $transaction[0]['operator_id'];
                $operator = OperatorSetting::where('operator_id', $operator_id)->get();
                $msg = "Your Transaction status for ".strtoupper($transaction[0]['order_id'])." is give below :- \n\n";
                if($transaction[0]['transaction_type'] == "") {
                    $msg .= "Mobile Number - ".$transaction[0]['mobileno']."\n";
                }
                if($transaction[0]['billerID'] != "") {
                    $billers = DB::table('tbl_bbps_list')->where('billerId', '=', $transaction[0]['billerID'])->first();
                    if(isset($billers)) {
                        $msg .= "Biller Name - ".$billers->billerName."\n";
                    }
                    $resp = json_decode($transaction[0]['response_msg'],true);
                    // $msg .= json_encode($transaction[0]['response_msg'])."\n\n";
                    $msg .= "Account Number - ".$resp['inputParams']['input']['paramName'] . " : " . $resp['inputParams']['input']['paramValue']."\n";
                    $msg .= "Customer Name - ".$resp['RespCustomerName']."\n";
                    
                }
                $msg .= "Operator - ".$operator[0]['operator_name']."\n";
                if($transaction[0]['transaction_type'] != "") {
                    $msg .= "Transaction Type - ".$transaction[0]['transaction_type']."\n";
                    // $msg .= "Beneficiary Name - ".$transaction[0]['imps_name']."\n";
                }
                if($transaction[0]['recipient_id'] != ""){
                    $data = DB::table('tbl_dmt_benificiary_dtls')->where('recipient_id', '=', $transaction[0]['recipient_id'])->first();
                    if(isset($data)) {
                        $msg .= "Beneficiary Name - ".$data->recipient_name."\n";
                        if($transaction[0]['transaction_type'] == "UPI") {
                            $msg .= "UPI ID - ".$data->bank_account_number."\n";
                        } else {
                            $msg .= "Beneficiary Account Number - ".$data->bank_account_number."\n";
                            $msg .= "Bank Name - ".$data->bank_name."\n";
                            $msg .= "IFSC Code - ".$data->ifsc."\n";
                        }
                        $msg .= "Sender Number - ".$data->sender_mobile_number."\n";
                    }
                }
                $msg .= "Amount - ".$transaction[0]['total_amount']."\n";
                $msg .= "Commission - ".$this->getComissionByOrdId($transaction[0]['order_id'], $check_user['userId'])."\n";
                if($transaction[0]['CCFcharges'] != "" && $transaction[0]['Cashback'] != "") {
                    $ccf = $transaction[0]['CCFcharges'];
                    $cashback = $transaction[0]['Cashback'];
                    $charge = $ccf - $cashback;
                    // $msg .= "CCF Charge - ".$transaction[0]['CCFcharges']."\n";
                    // $msg .= "Cashback - ".$transaction[0]['Cashback']."\n";
                    $msg .= "Charge - ".$charge."\n";
                }
                if($transaction[0]['TDSamount'] != "") {
                    $msg .= "TDS Charge - ".$transaction[0]['TDSamount']."\n";
                }
                // if($transaction[0]['PayableCharge'] != "") {
                //     $msg .= "Payable Charge - ".$transaction[0]['PayableCharge']."\n";
                // }
                if($transaction[0]['FinalAmount'] != "") {
                    $msg .= "Net Payable - ".$transaction[0]['FinalAmount']."\n";
                }
                if($transaction[0]['bank_transaction_id'] != "") {
                    $msg .= "Bank Reference ID - ".$transaction[0]['bank_transaction_id']."\n";
                }
                if($transaction[0]['transaction_id'] != "" && $transaction[0]['transaction_type'] == "") {
                    $msg .= "Transaction ID - ".$transaction[0]['transaction_id']."\n";
                }
                $msg .= "Status - ".strtoupper($transaction[0]['order_status'])."\n";
                $msg .= "Transaction Date - ".$transaction[0]['trans_date'];
                
            } else {
                $msg = "Oops, no transactions found with this order ID ".$text;
            }
        } elseif(strpos($text, "SP") === 0 || strpos($text, "sp") === 0) {
            $transaction = TransactionDetail::where('order_id', strtoupper($text))->where('user_id', $check_user['userId'])->get();
            if(isset($transaction) && count($transaction)) {
                $msg = "Your Transaction status for ".strtoupper($transaction[0]['order_id'])." is given below :- \n\n";
                $fetch = $this->getTrans(strtoupper($text),$check_user['userId']);
                foreach($fetch as $id) {
                    $msg .= $id."\n";
                }
            } else {
                $msg = "Oops, no transaction found!";
            }
        } elseif($text == "/distributorinfo" || $text == "distributorinfo") {
            $msg = "";
            if($check_user['parent_user_id'] -= "0") {
                $distributor = $this->check_user_id($check_user['parent_user_id']);
                if($distributor) {
                    $msg .= "Distributor Info :-\n";
                    $msg .= "Name - ".$distributor['first_name'] . " " . $distributor['last_name']."\n";
                    $msg .= "Mobile Number - ".$distributor['mobile']."\n";
                    $msg .= "Address - ".$distributor['address']."\n\n";
                }
            }
            if($check_user['fos_id'] != "0") {
                $fos = $this->check_user_id($check_user['fos_id']);
                if($fos) {
                    $msg .= "FOS Info :-\n";
                    $msg .= "Name - ".$fos['first_name'] . " " . $fos['last_name']."\n";
                    $msg .= "Mobile Number - ".$fos['mobile']."\n";
                    $msg .= "Address - ".$fos['address']."\n";
                }
            }
            if($check_user['fos_id'] == "0" && $check_user['parent_user_id'] == "0") {
                $msg .= "Oops! you do not have any Distributor or FOS mapped with your account.";
            }
        } elseif($text == "/supportnumber" || $text == "supportnumber") {
            $msg = "CUSTOMER CARE : 040-29563154\n\n";
            $msg .= "SALES DEPARTMENT : +918374913154\n\n";
            $msg .= "WHATSAPP/TELEGRAM SUPPORT : 8374913154\n\n";
            $msg .= "EMAIL : support@paymamaapp.in\n\n";
            $msg .= "Website : www.paymamaapp.in\n\n";
            $msg .= "Retailer Android App  : https://play.google.com/store/apps/details?id=com.paymama.retailer\n\n";
            $msg .= "Distributor Android App : https://play.google.com/store/apps/details?id=com.paymama.distributor\n\n";
            $msg .= "FOS Android App : https://play.google.com/store/apps/details?id=com.paymama.fos\n\n\n";
            $msg .= "<b>SOCIAL MEDIA</b>\n\n";
            $msg .= "INSTAGRAM : https://www.instagram.com/smartpay_india/\n\n";
            $msg .= "FACEBOOK : https://www.facebook.com/smartpayindia/\n\n";
            $msg .= "TWITTER : https://twitter.com/smartpayindia/\n\n";
            $msg .= "YOUTUBE :\n\n\n";
            $msg .= "NAIDU SOFTWARE TECHNOLOGIES PRIVATE LIMITED\n\n";
            $msg .= "CIN : U74999TG2020OPC140535\n\n";
            $msg .= "GSTIN : 36ABDCS7080H1Z4\n\n";
            $msg .= "ADDRESS : GV COMPLEX,OPP PADMAVATHI FUNCTION HALL,HAYATHNAGAR,RANGAREDDY,TELANGANA,501505";
        } elseif($text == "/operatorhelpline" || $text == "operatorhelpline") {
            $msg = "<b>OPERATOR HELPLINE</b>\n\n";
            $msg .= "<b>MOBILE PREPAID</b>\n\n";
            $msg .= "<b>VI : 199</b>\n";
            $msg .= "<b>AIRTEL : 121</b>\n";
            $msg .= "<b>BSNL : 18003451500</b>\n";
            $msg .= "<b>RELIANCE JIO : 18008899999</b>\n\n";
            
            $msg .= "<b>DTH</b>\n\n";
            
            $msg .= "<b>AIRTEL DIGITAL TV : 18001028080</b>\n";
            $msg .= "<b>TATA SKY : 18002086633</b>\n";
            $msg .= "<b>SUNDIRECT : 18001237575</b>\n";
            $msg .= "<b>VIDEOCON : 09115691156</b>\n";
            $msg .= "<b>DISH TV : 09501795017</b>\n";
        } elseif($text == "/mychatid" || $text == "mychatid") {
            $msg = "Your Telegram ID is : ".$chat_id.". Please update the same on your PayMama Profile Section.";
        } elseif($text == "/howto" || $text == "howto") {
        } else {
            $msg = "Oops, I do not understand this language.";
        }
        $content = array('chat_id' => $chat_id, 'text' => $msg, 'parse_mode' => 'HTML');
        $telegram->sendMessage($content);
    }

    public function check_user($chat_id) {
        $user = User::where('telegram_no', $chat_id)->get();
        if (isset($user) && count($user)) {
            return $user[0];
        } else {
            return false;
        }

    }
    
    public function check_user_id($userId) {
        $user = User::where('userId', $userId)->get();
        if (isset($user) && count($user)) {
            return $user[0];
        } else {
            return false;
        }

    }
    
    public function send_telegram($msg,$chat_id) {
        $telegram = new \App\Packages\Telegram\Telegram(Config::get('constants.TELEGRAM_BOT_ID'));
        $content = array('chat_id' => $chat_id, 'text' => $msg, 'parse_mode' => 'HTML');
        $telegram->sendMessage($content);
        return true;
    }
    
    public function checkKyc($userId)
    {
        $kycDetail = KycDetail::where('user_id', $userId)->get();
        if ($kycDetail) {
            if(!empty($kycDetail) && $kycDetail[0]['status'] == "APPROVED"){
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }
    
    public function getComissionByOrdId($ord_id,$userId){
        $cmm_Amt = '0.00';
        $commission =  WalletTransactionDetail::select('user_id','total_amount')->where('id_deleted', Config::get('constants.NOT-DELETED'))
                                                ->where('user_id', $userId)
                                                ->where('order_id', $ord_id)
                                                ->where('payment_type', 'COMMISSION')
                                                ->where('transaction_type', 'CREDIT')
                                               ->get();
        if(isset($commission) && count($commission)) {
            foreach($commission as $comm_key => $comm_value){
                $cmm_Amt = $comm_value['total_amount'];
            }
        } else {
            $cmm_Amt = "0.00";
        }
        return $cmm_Amt;
    }
    
    public function getUserRoleById($user_id){
        $userRole = User::where('userId', $user_id)->get();
        return $userRole[0]['roleId'];
    }
    
    public function getTrans($order,$userId) {
        $transaction = TransactionDetail::where('order_id', strtoupper($order))->where('user_id', $userId)->get();
        if(isset($transaction) && count($transaction)) {
            $operator_id = $transaction[0]['operator_id'];
            $operator = OperatorSetting::where('operator_id', $operator_id)->get();
            $msg [] = "Smart ID - ".strtoupper($transaction[0]['order_id']);
            if($transaction[0]['transaction_type'] == "") {
                $msg[] = "Mobile Number - ".$transaction[0]['mobileno'];
            }
            if($transaction[0]['billerID'] != "") {
                $billers = DB::table('tbl_bbps_list')->where('billerId', '=', $transaction[0]['billerID'])->first();
                if(isset($billers)) {
                    $msg[] = "Biller Name - ".$billers->billerName;
                }
                $resp = json_decode($transaction[0]['response_msg'],true);
                // $msg .= json_encode($transaction[0]['response_msg'])."\n\n";
                $msg[] = "Account Number - ".$resp['inputParams']['input']['paramName'] . " : " . $resp['inputParams']['input']['paramValue'];
                $msg[] = "Customer Name - ".$resp['RespCustomerName'];
                
            }
            $msg[] = "Operator - ".$operator[0]['operator_name'];
            if($transaction[0]['transaction_type'] != "") {
                $msg[] = "Transaction Type - ".$transaction[0]['transaction_type'];
                // $msg[] = "Beneficiary Name - ".$transaction[0]['imps_name'];
            }
            if($transaction[0]['recipient_id'] != ""){
                $data = DB::table('tbl_dmt_benificiary_dtls')->where('recipient_id', '=', $transaction[0]['recipient_id'])->first();
                if(isset($data)) {
                    $msg[] = "Beneficiary Name - ".$data->recipient_name;
                    if($transaction[0]['transaction_type'] == "UPI") {
                        $msg[] = "UPI ID - ".$data->bank_account_number;
                    } else {
                        $msg[] = "Beneficiary Account Number - ".$data->bank_account_number;
                        $msg[] = "Bank Name - ".$data->bank_name;
                        $msg[] = "IFSC Code - ".$data->ifsc;
                    }
                    $msg[] = "Sender Number - ".$data->sender_mobile_number;
                }
            }
            $msg[] = "Amount - ".$transaction[0]['total_amount'];
            $commission = $this->getComissionByOrdId(strtoupper($order), $userId);
            $msg[] = "Commission - ".$commission;
            if($transaction[0]['CCFcharges'] != "" && $transaction[0]['Cashback'] != "") {
                $ccf = $transaction[0]['CCFcharges'];
                $cashback = $transaction[0]['Cashback'];
                $charge = $ccf - $cashback;
                // $msg[] = "CCF Charge - ".$transaction[0]['CCFcharges'];
                // $msg[] = "Cashback - ".$transaction[0]['Cashback'];
                $msg[] = "Charge - ".$charge;
            }
            if($transaction[0]['TDSamount'] != "") {
                $msg[] = "TDS Charge - ".$transaction[0]['TDSamount'];
            }
            // if($transaction[0]['PayableCharge'] != "") {
            //     $msg[] = "Payable Charge - ".$transaction[0]['PayableCharge'];
            // }
            if($transaction[0]['FinalAmount'] != "") {
                $msg[] = "Net Payable - ".$transaction[0]['FinalAmount'];
            }
            if($transaction[0]['bank_transaction_id'] != "") {
                $msg[] = "Bank Reference ID - ".$transaction[0]['bank_transaction_id'];
            }
            if($transaction[0]['transaction_id'] != "" && $transaction[0]['transaction_type'] == "") {
                $msg[] = "Transaction ID - ".$transaction[0]['transaction_id'];
            }
            $msg[] = "Status - ".strtoupper($transaction[0]['order_status']);
            $msg[] = "Transaction Date - ".$transaction[0]['trans_date'];
            
        } else {
            $msg[] = "Oops, no transactions found with this order ID ".$text;
        }
        return $msg;
    }
    
    public function telegramOrderAPI(Request $request) {
        $transaction = TransactionDetail::where('order_id', strtoupper($request->order_id))->get();
        if(isset($transaction) && count($transaction)) {
            $msg = "Your Transaction status for ".strtoupper($transaction[0]['order_id'])." is given below :- \n\n";
            $user_id = $transaction[0]['user_id'];
            $userRole = User::where('userId', $user_id)->get();
            $chat_id = $userRole[0]['telegram_no'];
            if(empty($chat_id)) {
                return false;
            }
            $fetch = $this->getTrans(strtoupper($request->order_id),$user_id);
            foreach($fetch as $id) {
                $msg .= $id."\n";
            }
        } else {
            return false;
        }
        $this->send_telegram($msg,$chat_id);
        return true;
    }
}