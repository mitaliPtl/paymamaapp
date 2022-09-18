<?php

namespace App\Http\Controllers\ServiceType;

use App\Http\Controllers\Controller;

use App\UserLoginSessionDetail;

use App\OperatorSetting;

use App\TransactionDetail;

use App\WalletTransactionDetail;

use App\PackageCommissionDetail;

use App\ApiLogDetail;

use App\ApiSetting;

use DB;

use Auth;

use Config;

// use Session;

use PDF;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Http;

use Illuminate\Support\Facades\Session;

use App\User;

use App\ServicesType;

use Illuminate\Support\Facades\Validator;

use Illuminate\Contracts\Encryption\DecryptException;

use Illuminate\Support\Facades\Crypt;

use Illuminate\Cache\RateLimiter;

use Response;

use App\City;

use App\State;





class AepsController extends Controller

{

   public $pub_key = '-----BEGIN CERTIFICATE-----
MIIGIjCCBAqgAwIBAgIJAONANUQho7nLMA0GCSqGSIb3DQEBCwUAMIGlMQswCQYD
VQQGEwJJTjESMBAGA1UECAwJVGVsYW5nYW5hMRIwEAYDVQQHDAlIeWRlcmFiYWQx
JTAjBgNVBAoMHFRhcGl0cyBUZWNobm9sb2dpZXMgUHZ0LiBMdGQxETAPBgNVBAsM
CFNhaSBCYWJhMRYwFAYDVQQDDA1zYWlAdGFwaXRzLmluMRwwGgYJKoZIhvcNAQkB
Fg1zYWlAdGFwaXRzLmluMB4XDTE3MDYwOTA2NTAyN1oXDTI3MDYwNzA2NTAyN1ow
gaUxCzAJBgNVBAYTAklOMRIwEAYDVQQIDAlUZWxhbmdhbmExEjAQBgNVBAcMCUh5
ZGVyYWJhZDElMCMGA1UECgwcVGFwaXRzIFRlY2hub2xvZ2llcyBQdnQuIEx0ZDER
MA8GA1UECwwIU2FpIEJhYmExFjAUBgNVBAMMDXNhaUB0YXBpdHMuaW4xHDAaBgkq
hkiG9w0BCQEWDXNhaUB0YXBpdHMuaW4wggIiMA0GCSqGSIb3DQEBAQUAA4ICDwAw
ggIKAoICAQC/aknTgu/K/hZRHwUkbPUpynOK/CJRErPjv2wwaBe8ViQFvjgXABW1
9zcwIS5tMj0yrh1FJec7q3ni+eOdj9rX0F6zg3DcWjguvJEF+ZKj5OV0Ys5xsq5E
opl5GcLmnfVtsM/kgFd0JlDtg7JtM7z0+yvyqPyNd67gmjNX35OZvMneYIL6OSeb
PqSHP+M/BIcQBCyLXcDxz1BQMv83N4H28zgMxwO50RtWhyzdj97A7nw6Z/nVnVCP
H4da+/Kbi0Bj1Jconr98mcL0naX+moeLxcYlaBDM+Y7IY+mx2trDb60Ib77LvSpX
u+h55aSDJw7WdyHrgjeN8qbafoUBOyv5HeFDPbzICSds9jPN3P6vDWSYpfTXWi8I
TQt7TilbUBj8RVSceOhvkIq2Ce9/qVqcDGHUA4S1Ngvw8GOLZWTu/UB39cPE43zv
ToFok/3M3/oCzGqUVa8iFIudxMjTk+6XgbGTGSnGDm7FBHNpE1AORgB88cC0PqZA
jXsH5xl6kbf8i5OjJEcs0k/IHyvky/dSzfgJ7jszRPSGTFIZnp7nEmYLyqUuJV8A
AcED0R4ZXKntynYf049Sd2vsWV/kV1tSi6NrYtIzSZIAx70Yr3WQgqS2Afy/xrV9
Nyzuxzc4Sk+NxvdnJvxbyZgA/6XGbUwLjS6UdnKL02UrLb04r/jzpwIDAQABo1Mw
UTAdBgNVHQ4EFgQUcZrktj8xxx1zjcGa8NbPDDrcJhAwHwYDVR0jBBgwFoAUcZrk
tj8xxx1zjcGa8NbPDDrcJhAwDwYDVR0TAQH/BAUwAwEB/zANBgkqhkiG9w0BAQsF
AAOCAgEATnBaXUyFUxnYIroa7reuojl+PvNRpd3T4svOVar2nrOiZhPbb6PeimNA
kovR7FgijT7UXpqDvxuEhLnSN4U+lAA934d4yN6SiDdpXFefHl8vlUv9rrz5JiUW
0shX9O6uMT8POYhP6bzOk1I1w3H4QCLn9KxSpO265uRd3vn3Tzbb77N89qlJ/9CX
XVp2Og6XGKbmrdEb04qbFIOuxmW2IYWHHtuG8PEeNITCh4qzenZ49EB/gOhgIm7c
ckH9OLyOHfDLANFfIIoityyXX2DSVyPNtMPg1sq9YIw907q+0K9KzGZzcF8FNSL6
KZTE8URvr/ZU00qcM4lHZbKBxjBrA1rIDD8IIPhH+7vWCAcT88XJcpLCAL9vZ1bH
8GFd9Eu08SEhhlQ3xfJJNq3W/P4TrJIDxukmClRPXb7uKya+HlrkIP04ael1Gu1Z
LdsM/sE+1Cte+nCG+XrVWzQXB1OxRtbQt3U5rHWsh/zaq+IOdc03Nd34Ceqnm7OB
hMVCuyUmwMjrBoG2XaLIhZKUtIsmT88WryAG4wo+MmEdYcaBXmHZ49t/60CzcMCN
IqLI220tUFpA8SJepQQKahs0ZG2S2PqyrrH0nM0++2sm3ETfxZKDFOylBPmrrbSW
8Tmvt2QQ1A1ACYN5GIwcc52Ib5Y0nBBP32gQVjqLQbZG4XjdhKk=
-----END CERTIFICATE-----';






    public function index()
    {

        $serviceTypeAlias = Config::get('constants.SERVICE_TYPE_ALIAS.AEPS');

        $servicesTypes = ServicesType::where(['is_deleted' => Config::get('constants.NOT-DELETED'), 'alias' => $serviceTypeAlias, 'activated_status' => 'YES'])->count();

        if ($servicesTypes < 1) {

            return back()->with('error', 'AEPS is deactivated');
        }

        $user = User::where('userId', Auth::user()->userId)->first();

        if ($user->aeps_kyc == Config::get('constants.DELETED')) {

            $aeps_bank = file_get_contents('https://fingpayap.tapits.in/fpaepsservice/api/bankdata/bank/details');

            //  if(Auth::user()->userId == 112 or Auth::user()->userId == 197)

            //   if(Auth::user()->userId == 112 or Auth::user()->userId == 125 or Auth::user()->userId == 183  or Auth::user()->userId == 135  or Auth::user()->userId == 188 or Auth::user()->userId == 171)

            //     {

            //       return view('modules.aeps.show', compact('user','aeps_bank'));

            //     }

            //     else

            //     {



            //       return redirect('home');   

            //     }

            //   return redirect()->back()->with('merror', $response['message'] ?? "Something went wrong!")->with('messages', $msg);







            return view('modules.aeps.show', compact('user', 'aeps_bank'));
        } else {

            $aeps_state = file_get_contents('https://fingpayap.tapits.in/fpaepsweb/api/onboarding/getstates');

            return redirect()->route('onboarding')->with('error', 'Please complete the Onboarding process to continue.')->with('aeps', $aeps_state);
        }
    }



    public function uatindex()
    {

        $serviceTypeAlias = Config::get('constants.SERVICE_TYPE_ALIAS.AEPS');

        $servicesTypes = ServicesType::where(['is_deleted' => Config::get('constants.NOT-DELETED'), 'alias' => $serviceTypeAlias, 'activated_status' => 'YES'])->count();

        if ($servicesTypes < 1) {

            return back()->with('error', 'AEPS is deactivated');
        }

        $user = User::where('userId', Auth::user()->userId)->first();

        if ($user->aeps_kyc == Config::get('constants.DELETED')) {

            $aeps_bank = file_get_contents('https://fingpayap.tapits.in/fpaepsservice/api/bankdata/bank/details');

            //  if(Auth::user()->userId == 112 or Auth::user()->userId == 197)

            //   if(Auth::user()->userId == 112 or Auth::user()->userId == 125 or Auth::user()->userId == 183  or Auth::user()->userId == 135  or Auth::user()->userId == 188 or Auth::user()->userId == 171)

            //     {

            //       return view('modules.aeps.show', compact('user','aeps_bank'));

            //     }

            //     else

            //     {



            //       return redirect('home');   

            //     }

            //   return redirect()->back()->with('merror', $response['message'] ?? "Something went wrong!")->with('messages', $msg);







            return view('modules.aeps.show', compact('user', 'aeps_bank'));
        } else {

            return redirect()->route('onboarding')->with('error', 'Please complete the Onboarding process to continue.');
        }
    }





    //Status Check Function

    public function statuscheck()
    {

        $order_id = "PM102021";

        $check_txn = TransactionDetail::where('order_id', $order_id)->first();

        $user_id = $check_txn->user_id;

        $aeps_user = User::where('userId', $user_id)->first();

        $api_id = $check_txn->api_id;

        $api_details = ApiSetting::where('api_id', $api_id)->first();

        $super_mid = "smartpaytechd";

        $fp_pass = "1234d";

        $string = $check_txn->transaction_id . "+" . strtolower($aeps_user->aeps_mid) . "+" . $super_mid;

        //$hash = "pSNi+Gvil9rJT70cZfmmUnh9md3bfYPscehGnEQgGGw=";

        $hashedvalue = hash("sha256", $string, true);



        $hty = base64_encode($hashedvalue);





        $values = array(



            "merchantLoginId" => $aeps_user->username,

            "merchantPassword" => md5($aeps_user->password),

            "superMerchantId" => 1015,

            "superMerchantPassword" => md5($fp_pass),

            "merchantTranId" => $check_txn->transaction_id,

            "hash" => $hty

        );



        //$values='{"merchantLoginId":"FINGPAY1234","merchantPassword":"e6e061838856bf47e1de730719fb2609","superMerchantId":2,"superMerchantPassword":"796c3ee556ac31f3754a38cfd15b8044","merchantTranId":"123456","hash":"oeFNf527cE911LaCzS9wiYBo/7E5C7QsvwHqrAykpyU="}';

        $values = json_encode($values);





        $url = "https://fingpayap.tapits.in/fpaepsweb/api/auth/merchantInfo/statusCheckV2/merchantLoginId/cashWithdrawal/v2";

        $curl = curl_init();

        curl_setopt_array($curl, array(

            CURLOPT_URL => $url,

            CURLOPT_RETURNTRANSFER => true,

            CURLOPT_SSL_VERIFYPEER => true,

            CURLOPT_SSL_VERIFYHOST => 2,

            CURLOPT_TIMEOUT => 30,

            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,

            CURLOPT_CUSTOMREQUEST => "POST",

            CURLOPT_POSTFIELDS => $values,

            // CURLOPT_HTTPHEADER => $header

            CURLOPT_HTTPHEADER => array('Content-Type: application/json')

        ));





        $response1 = curl_exec($curl);

        return $response = json_decode($response1, true);

        $err = curl_error($curl);

        $info = curl_getinfo($curl);

        curl_close($curl);



        if ($response['data'][0]['transactionStatus'] == 1 or $response['data'][0]['transactionStatus'] == true  or $response['data'][0]['transactionStatus'] == "1" or $response['data'][0]['transactionStatus'] == "true") {

            if ($check_txn->service_id == 6) {

                //cash withdrawal

                $service_type = "cash_withdrawal";
            } else if ($check_txn->service_id == 9) {

                //aadhar pay

                $service_type = "aadhar_payment";
            } else if ($check_txn->service_id == 11) {

                //balance inquiry

                $service_type = "balance_enquiry";
            } else {

                //Mini Statement

                $service_type = "mini_statement";
            }



            $bank_name = $response['data'][0]['bankName'];

            $aadhar = $response['data'][0]['aadhaarNumber'];

            $mobile = $response['data'][0]['mobileNumber'];

            $operator_id = $check_txn->operator_id;

            $amount = $check_txn->transactionAmount;

            $user_id = $user_id;

            $transaction_id = $response['data'][0]['transactionStatus'];

            $order_id = $response['data'][0]['transactionStatus'];

            $service_id = $check_txn->service_id;

            $service_type = $service_type;

            //$success = $this->add_success_comm($bank_name,$aadhar,$mobile,$operator_id,$amount,$user_id,$aeps_user->roleId,$aeps_user->parent_user_id,$transaction_id,$order_id,$check_txn->api_id,$service_id,$service_type);

        } else {

            //$update_txn = $this->update_aeps_txn($fpTransactionId,$transaction_msg,$amount,$aeps_balance,$clientreferenceid,$rrnno,$operator_id,$user_id,$user->roleId,$transaction_id,$order_id,$api_details->api_id,$service_id,"FAILED",$mobile,$aadharNumber);

        }
    }





    //Ends Here





    public function aadharpay()
    {

        $serviceTypeAlias = Config::get('constants.SERVICE_TYPE_ALIAS.AEPS');

        $servicesTypes = ServicesType::where(['is_deleted' => Config::get('constants.NOT-DELETED'), 'alias' => $serviceTypeAlias, 'activated_status' => 'YES'])->count();

        if ($servicesTypes < 1) {

            return back()->with('error', 'AEPS is deactivated');
        }

        $user = User::where('userId', Auth::user()->userId)->first();

        if ($user->aeps_kyc == Config::get('constants.DELETED')) {

            $aeps_bank = file_get_contents('https://fingpayap.tapits.in/fpaepsservice/api/bankdata/bank/details');

            if (Auth::user()->userId == 112 or Auth::user()->userId == 125 or Auth::user()->userId == 183 or Auth::user()->userId == 135  or Auth::user()->userId == 188 or Auth::user()->userId == 171) {

                return view('modules.aeps.aadharpay', compact('user', 'aeps_bank'));
            } else {



                return redirect('home');
            }
        } else {

            return redirect()->route('onboarding')->with('error', 'Please complete the Onboarding process to continue.');
        }
    }



    public function aeps_transaction(Request $request)
    {

        // $aeps_bank = file_get_contents('https://fingpayap.tapits.in/fpaepsservice/api/bankdata/bank/details');

        // $ministatement="running";

        //  $user = User::where('userId', Auth::user()->userId)->first();



        // return redirect()->back()->with('ministatement', 'Required fields cannot be empty')->with('merror', 'Invalid Mpin');





        // $msg = "Aadhar Pay Request Processed Successfully<br>";

        // $msg .= "Transaction Amount :- ₹ 100<br>";

        // $msg .= "Current Balance :- ₹ 100<br>";

        // $msg .= "Transaction ID :- 123<br>";

        // $msg .= "Date and Time :- ".date('d/m/Y H:i:s')."<br>";

        // $msg .= "Kotak Mahindra Bank<br>";

        // return redirect()->back()->with('msuccess', $msg);

        $validator = Validator::make($request->all(), [

            // 'latitude' => 'required',

            // 'longitude' => 'required',

            'aadharNumber' => 'required',

            'mobileNumber' => 'required',

            'service_type' => 'required',

            'amount' => 'required_if:service_type,cash_withdrawal,aadhar_payment',

            'bank_name' => 'required',

            // 'txtPidData' => 'required',

            'mpin' => 'required',

            // 'PidOptions' => 'required'

        ]);



        if ($validator->fails()) {

            return redirect()->back()->with('error', 'Required fields cannot be empty');
        }

        $lat = $request->input('latitude');

        $long = $request->input('longitude');

        $aadharNumber = str_replace('-', '', $request->input('aadharNumber'));

        $mobile = $request->input('mobileNumber');

        $deviceId = '0610936'; //need to check for other biometric devices

        $service_type = $request->input('service_type');

        $bank_id = $request->input('bank_name');

        $mpin = $request->input('mpin');

        $amount = $request->input('amount') ?? "0.00";

        $user_id = Auth::user()->userId;

        $user = User::where(['userId' => Auth::user()->userId, 'mpin' => $mpin])->first();



        if (empty($user) || !$user) {

            return redirect()->back()->with('merror', 'Invalid Mpin')->with('merror', 'Invalid Mpin');
        }





        //print_r(json_encode($captureResponse));die();

        // $captureResponse = "";

        if ($service_type == 'balance_enquiry') {

            $captureResponse = $this->captureData("", $request->input('txtPidDatabalance'));

            if ($request->input('capturescore1') == null) {

                return redirect()->back()->with('pkmkb', 'kindly Capture Fingerprint');
            }

            $service_id = "11"; //fetch

            $operator_id = "46"; //fetch

            $api_id = OperatorSetting::where(['operator_id' => $operator_id, 'service_id' => $service_id])->first() ?? "00";

            if (!$api_id) {

                return redirect()->back()->with('merror', 'Something went wrong');
            }

            $api_details = ApiSetting::where(['api_id' => $api_id->default_api_id, 'is_deleted' => 0, 'activated_status' => 'YES'])->first();

            if (!$api_details) {

                return redirect()->back()->with('merror', 'Something went wrong');
            }



            $old_oid =  file_get_contents("admin/admin/txn_order_id.txt");

            $sno_order_id = (int)$old_oid + 1;

            $order_id = "PM" . $sno_order_id;

            file_put_contents('admin/admin/txn_order_id.txt', $sno_order_id . "");





            $transaction_id = rand(00000000000000, 99999999999999);

            $passaeps = $user->aeps_password;

            $url = $api_details->api_url;

            $values = array(

                "superMerchantId" => $api_details->key_id,

                "latitude" => $lat,

                "longitude" => $long,

                "mobileNumber" => $mobile,

                "languageCode" => "en",

                "paymentType" => "B",

                "transactionType" => "BE",

                "merchantUserName" => $user->aeps_mid,

                "merchantPin" => md5($passaeps),

                "merchantTransactionId" => $transaction_id,

                "cardnumberORUID" => array(

                    "adhaarNumber" => $this->isAadharValid($aadharNumber) ? $aadharNumber : "",

                    "indicatorforUID" => "0",

                    "nationalBankIdentificationNumber" => $bank_id

                ),

                "captureResponse" => $captureResponse

            );

            //Transaction Table Entryz

            $transaction_msg = "";

            $aeps_balance = "";

            $clientreferenceid = "";

            $rrnno = "";

            $status = "PENDING";

            $bank_name = "";



            $aeps_bank = file_get_contents("https://fingpayap.tapits.in/fpaepsservice/api/bankdata/bank/details");

            $aeps_bank = json_decode($aeps_bank, true);

            $bank_name = '';

            foreach ($aeps_bank['data'] as $bank) {

                if ($bank['iinno'] == $bank_id) {

                    $bank_name = $bank['bankName'];
                }
            }

            $bankname = $bank_name;

            $txnTbl = $this->add_wallet_table($bankname, $mobile, $aadharNumber, $bank_name, $operator_id, $amount, $user_id, $user->roleId, $transaction_id, $order_id, $api_details->api_id, $service_id);





            // print_r(json_encode($values));die();

            $response = $this->aeps_api($url, $values, 'J9:H5:4D:9D:0Q');

            //test

            // $response = array(

            //     'status' => 'true',

            //     'data' => array(

            //         'balanceAmount' => '100.00',

            //         'merchantTransactionId' => $transaction_id,

            //         'requestTransactionTime' => date('d/m/Y H:i:s')

            //     )

            // );



            //test end

            $aeps_bank = file_get_contents("https://fingpayap.tapits.in/fpaepsservice/api/bankdata/bank/details");

            $aeps_bank = json_decode($aeps_bank, true);

            $bank_name = '';

            foreach ($aeps_bank['data'] as $bank) {

                if ($bank['iinno'] == $bank_id) {

                    $bank_name = $bank['bankName'];
                }
            }

            //$response="true";

            //if($response['status'] == 'true') {

            if ($response) {



                $aadharNumber = str_repeat('X', strlen($aadharNumber) - 4) . substr($aadharNumber, -4);



                foreach ($aeps_bank['data'] as $bank) {

                    if ($bank['iinno'] == $bank_id) {

                        $bank_name = $bank['bankName'];
                    }
                }

                // $txnTbl = $this->add_wallet_table($mobile,$aadharNumber,$bank_name,$operator_id,$amount,$user_id,$user->roleId,$transaction_id,$order_id,$api_details->api_id,$service_id);



                $msg = "Current Balance - ₹ " . $response['data']['balanceAmount'] . "\n";

                $msg = "Balance Fetched Successfully\n";

                $transaction_amount = $response['data']['transactionAmount'];

                $balance_amount = $response['data']['balanceAmount'];

                $merchantTransactionId = $response['data']['merchantTransactionId'];

                $requestTransactionTime = $response['data']['requestTransactionTime'];

                $clientreferenceid = $response['data']['fpTransactionId'];

                $rrnno = $response['data']['bankRRN'];

                $bank_name = $bank_name;

                // $msg .= $bank_name."\n";

                $transactionAmount = $response['data']['transactionAmount'];

                $aadharNumber = str_repeat('X', strlen($aadharNumber) - 4) . substr($aadharNumber, -4);

                $aeps_balance = $balance_amount;

                $transaction_msg = $response['message'];

                $type = "balanceinquiry";

                $transaction_msg = $response['message'];

                $fpTransactionId = $response['data']['fpTransactionId'];



                //  $msg = "Balance Fetched Successfully\n";

                //  $transaction_amount=0;

                // $balance_amount=0;

                // $merchantTransactionId=0;

                // $requestTransactionTime = 0;

                // $clientreferenceid = 0;

                // $rrnno = 0;

                // $bank_name = $bank_name;

                // // $msg .= $bank_name."\n";

                // $transactionAmount = 0;

                // $aadharNumber=str_repeat('X', strlen($aadharNumber) - 4) . substr($aadharNumber, -4);

                // $aeps_balance=0;

                // $transaction_msg=0;

                // $type="balanceinquiry";

                // $transaction_msg="worked";

                $update_txn = $this->update_aeps_txn($fpTransactionId, $transaction_msg, $amount, $aeps_balance, $clientreferenceid, $rrnno, $operator_id, $user_id, $user->roleId, $transaction_id, $order_id, $api_details->api_id, $service_id, "SUCCESS", $mobile, $aadharNumber);



                //Update Source and Ipadress

                $source = 'WEB';



                if (strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'mobile') || strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'android') || strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'okhttp')) {

                    $source = 'MOBILE';
                }



                $trans_update_arr = array(

                    'ip_address' => $this->getRealIpAddr(),

                    'source' => $source

                );

                $updateTxn = TransactionDetail::where('order_id', $order_id)->update($trans_update_arr);



                //Ends Here





                //$add_txn = $this->add_aeps_txn($transaction_msg,$amount,$aeps_balance,$clientreferenceid,$rrnno,$operator_id,$user_id,$user->roleId,$transaction_id,$order_id,$api_details->api_id,$service_id,"SUCCESS",$mobile,$aadharNumber);

                Session::put('clientreferenceid', $clientreferenceid);

                Session::put('balanceinquiry', $type);

                Session::put('bankrrn', $rrnno);

                Session::put('transaction_id', $transaction_id);

                Session::put('order_id', $order_id);

                Session::put('aadharNumber', $aadharNumber);

                Session::put('bank_name', $bank_name);

                Session::put('msuccess1', $msg);

                Session::put('transaction_amount', $transaction_amount);

                Session::put('balance_amount', $balance_amount);

                Session::put('merchanttransactionid', $merchantTransactionId);

                Session::put('time', $requestTransactionTime);





                return redirect()->route('aeps');







                // return redirect()->back()->with('msuccess', $msg);

            } else {

                $transaction_msg = $response['message'];

                $fpTransactionId = $response['data']['fpTransactionId'];

                $update_txn = $this->update_aeps_txn($fpTransactionId, $transaction_msg, $amount, $aeps_balance, $clientreferenceid, $rrnno, $operator_id, $user_id, $user->roleId, $transaction_id, $order_id, $api_details->api_id, $service_id, "FAILED", $mobile, $aadharNumber);

                //Ends 

                Session::put('pkmkb', $response['message'] ?? "Something went wrong!");

                return redirect()->back()->with('pkmkb', $response['message'] ?? "Something went wrong!");
            }
        } elseif ($service_type == 'mini_statement') {



            $captureResponse = $this->captureData("", $request->input('txtPidDatamini'));

            if ($request->input('capturescore2') == null) {

                return redirect()->back()->with('error', 'kindly Capture Fingerprint');
            }

            $service_id = "12"; //fetch

            $operator_id = "53"; //fetch

            $api_id = OperatorSetting::where(['operator_id' => $operator_id, 'service_id' => $service_id])->first() ?? "00";

            if (!$api_id) {

                return redirect()->back()->with('merror', 'Something went wrong');
            }

            $api_details = ApiSetting::where(['api_id' => $api_id->default_api_id, 'is_deleted' => 0, 'activated_status' => 'YES'])->first();

            if (!$api_details) {

                return redirect()->back()->with('merror', 'Something went wrong');
            }



            $old_oid =  file_get_contents("admin/admin/txn_order_id.txt");

            $sno_order_id = (int)$old_oid + 1;

            $order_id = "PM" . $sno_order_id;

            file_put_contents('admin/admin/txn_order_id.txt', $sno_order_id . "");





            $transaction_id = rand(00000000000000, 99999999999999);

            $passaeps = $user->aeps_password;

            $url = $api_details->api_url;

            $values = array(

                "superMerchantId" => $api_details->key_id,

                "latitude" => $lat,

                "longitude" => $long,

                "mobileNumber" => $mobile,

                "languageCode" => "en",

                "paymentType" => "B",

                "transactionType" => "MS",

                "merchantUserName" => $user->aeps_mid,

                "merchantPin" => md5($passaeps),

                "merchantTransactionId" => $transaction_id,

                "cardnumberORUID" => array(

                    "adhaarNumber" => $this->isAadharValid($aadharNumber) ? $aadharNumber : "",

                    "indicatorforUID" => "0",

                    "nationalBankIdentificationNumber" => $bank_id

                ),

                "captureResponse" => $captureResponse

            );

            //Transaction Table Entryz

            $transaction_msg = "";

            $aeps_balance = "";

            $clientreferenceid = "";

            $rrnno = "";

            $status = "PENDING";

            $bank_name = "";

            //Make Wallet Table Entry

            $aadharNumber = str_repeat('X', strlen($aadharNumber) - 4) . substr($aadharNumber, -4);

            $aeps_bank = file_get_contents("https://fingpayap.tapits.in/fpaepsservice/api/bankdata/bank/details");

            $aeps_bank = json_decode($aeps_bank, true);

            $bank_name = '';

            foreach ($aeps_bank['data'] as $bank) {

                if ($bank['iinno'] == $bank_id) {

                    $bank_name = $bank['bankName'];
                }
            }

            $bankname = $bank_name;

            $txnTbl = $this->add_wallet_table($bankname, $mobile, $aadharNumber, $bank_name, $operator_id, $amount, $user_id, $user->roleId, $transaction_id, $order_id, $api_details->api_id, $service_id);



            $response = $this->aeps_api($url, $values, 'J9:H5:4D:9D:0Q');

            //$this->api_log($service_id,$api_details,$order_id,$user_id,json_encode($values),json_encode($response),"ministatement");

            //add api logs

            // $response = '{"status":true,"message":"Request Completed","data":{"terminalId":"PMU2196755258","requestTransactionTime":"20/09/2021 14:54:05","transactionAmount":0.0,"transactionStatus":"successful","balanceAmount":-17.7,"strMiniStatementBalance":null,"bankRRN":"126314643407","transactionType":"MS","fpTransactionId":"MSBS2196755200921145405036I","merchantTxnId":null,"errorCode":null,"errorMessage":null,"merchantTransactionId":null,"bankAccountNumber":null,"ifscCode":null,"bcName":null,"transactionTime":null,"agentId":0,"issuerBank":null,"customerAadhaarNumber":null,"customerName":null,"stan":null,"rrn":null,"uidaiAuthCode":null,"bcLocation":null,"demandSheetId":null,"mobileNumber":null,"urnId":null,"miniStatementStructureModel":[{"date":"14/09","txnType":"Dr","amount":"10020.0","narration":" POS/W/2570434579 "},{"date":"14/09","txnType":"Cr","amount":"10020.0","narration":" POS/D/rom: THATI "},{"date":"07/09","txnType":"Dr","amount":"2299.0","narration":" POS/W/1250167691 "},{"date":"06/09","txnType":"Cr","amount":"499.0","narration":" POS/D/1249212217 "},{"date":"01/09","txnType":"Cr","amount":"499.0","narration":" POS/D/1244150016 "},{"date":"01/09","txnType":"Cr","amount":"798.0","narration":" POS/D/1244150010 "},{"date":"31/08","txnType":"Cr","amount":"503.0","narration":" POS/D/2432307271 "},{"date":"26/08","txnType":"Dr","amount":"1823.0","narration":" POS/W/1238209539 "},{"date":"26/08","txnType":"Cr","amount":"1823.0","narration":" POS/D/rom: THATI "}],"miniOffusStatementStructureModel":null,"miniOffusFlag":false,"transactionRemark":null,"bankName":null,"prospectNumber":null,"internalReferenceNumber":null,"biTxnType":null,"subVillageName":null,"userProfileResponseModel":null,"hindiErrorMessage":null,"loanAccNo":null,"responseCode":"00","fpkAgentId":null},"statusCode":10000}';

            //$response = json_decode($response1,true);

            $this->api_log($transaction_id, $service_id, $api_details, $order_id, $user_id, json_encode($values), "PENDING", "ministatement");



            //Update api logs

            $this->update_api_log($service_id, $api_details, $order_id, $user_id, json_encode($values), json_encode($response), "ministatement");

            //Ends

            if ($response['status'] == 'true' && $response['data']['transactionStatus'] == 'successful') {



                //  foreach($aeps_bank['data'] as $bank) {

                //     if($bank['iinno'] == $bank_id) {

                //         $bank_name = $bank['bankName'];

                //     }

                // }

                //  $txnTbl = $this->add_wallet_table($mobile,$aadharNumber,$bank_name,$operator_id,$amount,$user_id,$user->roleId,$transaction_id,$order_id,$api_details->api_id,$service_id);



                $aeps_bank = file_get_contents("https://fingpayap.tapits.in/fpaepsservice/api/bankdata/bank/details");

                $aeps_bank = json_decode($aeps_bank, true);

                $bank_name = '';

                foreach ($aeps_bank['data'] as $bank) {

                    if ($bank['iinno'] == $bank_id) {

                        $bank_name = $bank['bankName'];
                    }
                }

                $resdata = $response['data']['miniStatementStructureModel'];

                $transaction_amount = $response['data']['transactionAmount'];

                $balance_amount = $response['data']['balanceAmount'];

                $merchantTransactionId = $response['data']['merchantTransactionId'];

                $requestTransactionTime = $response['data']['requestTransactionTime'];

                $clientreferenceid = $response['data']['fpTransactionId'];

                $rrnno = $response['data']['bankRRN'];

                $bank_name = $bank_name;

                // $msg .= $bank_name."\n";

                $transactionAmount = $response['data']['transactionAmount'];

                $aadharNumber = str_repeat('X', strlen($aadharNumber) - 4) . substr($aadharNumber, -4);

                $aeps_balance = $balance_amount;

                $transaction_msg = $response['message'];

                $ministatement = $transaction_msg;

                if(isset($request['data']['miniStatementStructureModel']))
                {
                    $tableBody = $request['data']['miniStatementStructureModel'];
                }
                else
                {
                   $tableBody = "";
                }
                

                $transaction_msg = $response['message'];

                
                 if(isset($response['data']['fpTransactionId']))
                {
                    $fpTransactionId = $response['data']['fpTransactionId'];
                }
                else
                {
                   $fpTransactionId = $response['data']['fpTransactionId'];
                }
                

                $update_txn = $this->update_aeps_txn($fpTransactionId, $transaction_msg, $amount, $aeps_balance, $clientreferenceid, $rrnno, $operator_id, $user_id, $user->roleId, $transaction_id, $order_id, $api_details->api_id, $service_id, "FAILED", $mobile, $aadharNumber);



                //    $add_txn = $this->add_aeps_txn($transaction_msg,$amount,$aeps_balance,$clientreferenceid,$rrnno,$operator_id,$user_id,$user->roleId,$transaction_id,$order_id,$api_details->api_id,$service_id,"SUCCESS",$mobile,$aadharNumber);

                //return redirect()->back()->with('clientreferenceid', $clientreferenceid)->with('bankrrn', $rrnno)->with('transaction_id', $transaction_id)->with('order_id', $order_id)->with('aadharNumber', $aadharNumber)->with('bank_name', $bank_name)->with('msuccess', $msg)->with('transaction_amount', $transaction_amount)->with('balance_amount', $balance_amount)->with('merchanttransactionid', $merchantTransactionId)->with('time', $requestTransactionTime);

                $aadharNumber = str_repeat('X', strlen($aadharNumber) - 4) . substr($aadharNumber, -4);



                $aadhar = $aadharNumber;

                $amount = 50;

                $transaction_id = "PAYMAMA" . rand(00000000000000, 99999999999999);

                $success = $this->add_success_comm($bank_name, $aadhar, $mobile, $operator_id, $amount, $user_id, $user->roleId, $user->parent_user_id, $transaction_id, $order_id, $api_details->api_id, $service_id, $service_type);





                //return $this->texportPDF($response,$bank_name);

                // return view('modules.aeps.show', compact('tableBody','ministatement'));

                Session::put('ministatement', $response['message']);

                Session::put('tableBody', $resdata);

                Session::put('balance_amount', $balance_amount);

                Session::put('datetime', $requestTransactionTime);

                Session::put('order_id', $order_id);

                Session::put('aadharNumber', $aadharNumber);

                Session::put('bank_name', $bank_name);



                return redirect()->route('aeps');
            } else {

                $aeps_bank = file_get_contents("https://fingpayap.tapits.in/fpaepsservice/api/bankdata/bank/details");

                $aeps_bank = json_decode($aeps_bank, true);

                $bank_name = '';

                foreach ($aeps_bank['data'] as $bank) {

                    if ($bank['iinno'] == $bank_id) {

                        $bank_name = $bank['bankName'];
                    }
                }
                 if(isset($response['data']['miniStatementStructureModel']))
                {
                   $resdata = $response['data']['miniStatementStructureModel'];
                }
                else
                {
                    $resdata = "";
                }
                
                   if(isset($response['data']['transactionAmount']))
                {
                  $transaction_amount = $response['data']['transactionAmount'];
                }
                else
                {
                    $transaction_amount = "";
                }
                
                    if(isset($response['data']['balanceAmount']))
                {
                   $balance_amount = $response['data']['balanceAmount'];
                }
                else
                {
                     $balance_amount =  "";
                }
                
                   if(isset($response['data']['merchantTransactionId']))
                {
                    $merchantTransactionId = $response['data']['merchantTransactionId'];
                }
                else
                {
                   $merchantTransactionId =  "";
                }
               
               if(isset($response['data']['requestTransactionTime']))
                {
                     $requestTransactionTime = $response['data']['requestTransactionTime'];
                }
                else
                {
                    $requestTransactionTime =  "";
                }
               
               if(isset($response['data']['requestTransactionTime']))
                {
                   
                $requestTransactionTime = $response['data']['requestTransactionTime'];
                }
                else
                {
                  
                $requestTransactionTime = "";
                }
                
                 if(isset($response['data']['fpTransactionId']))
                {
                   
                  $clientreferenceid = $response['data']['fpTransactionId'];
                }
                else
                {
                  
                $clientreferenceid = "";
                }
                 if(isset($response['data']['bankRRN']))
                {
                   
               $rrnno = $response['data']['bankRRN'];
                }
                else
                {
                  
              $rrnno ="";
                }
               

               

              

               


              

                

                $bank_name = $bank_name;

                // $msg .= $bank_name."\n";

             if(isset($response['data']['transactionAmount']))
                {
                   
               $transactionAmount = $response['data']['transactionAmount'];
                }
                else
                {
                  
             $transactionAmount = ""; 
                }
               
                

                $aadharNumber = str_repeat('X', strlen($aadharNumber) - 4) . substr($aadharNumber, -4);

                $aeps_balance = $balance_amount;

                $transaction_msg = $response['message'];

                $ministatement = $transaction_msg;

                if(isset($response['data']['miniStatementStructureModel']))
                {
                   
                $tableBody = $response['data']['miniStatementStructureModel'];
                }
                else
                {
                  
             $tableBody = "";
                }
               
                

                $transaction_msg = $response['message'];

                

 if(isset($response['data']['miniStatementStructureModel']))
                {
                   
             $fpTransactionId = $response['data']['fpTransactionId'];
                }
                else
                {
                  
           $fpTransactionId ="";
                }

                $update_txn = $this->update_aeps_txn($fpTransactionId, $transaction_msg, $amount, $aeps_balance, $clientreferenceid, $rrnno, $operator_id, $user_id, $user->roleId, $transaction_id, $order_id, $api_details->api_id, $service_id, "FAILED", $mobile, $aadharNumber);

                // $add_txn = $this->add_txn($operator_id,$user_id,$user->roleId,$transaction_id,$order_id,$api_details->api_id,$service_id,"FAILED",$mobile);

                Session::put('pkmkb', $response['message'] ?? "Something went wrong!");

                return redirect()->route('aeps');
            }
        } elseif ($service_type == 'aadhar_payment') {

            $captureResponse = $this->captureData("", $request->input('txtPidData'));

            $service_id = "9"; //fetch

            $operator_id = "44"; //fetch

            $api_id = OperatorSetting::where(['operator_id' => $operator_id, 'service_id' => $service_id])->first() ?? "00";

            if (!$api_id) {

                return redirect()->back()->with('merror', 'Something went wrong');
            }

            $api_details = ApiSetting::where(['api_id' => $api_id->default_api_id, 'is_deleted' => 0, 'activated_status' => 'YES'])->first();

            if (!$api_details) {

                return redirect()->back()->with('merror', 'Something went wrong');
            }



            $old_oid =  file_get_contents("admin/admin/txn_order_id.txt");

            $sno_order_id = (int)$old_oid + 1;

            $order_id = "PM" . $sno_order_id;

            file_put_contents('admin/admin/txn_order_id.txt', $sno_order_id . "");



            $transaction_id = "PAYMAMA" . rand(00000000000000, 99999999999999);

            //  $transaction_id = rand(00000000000000,99999999999999);

            // $txnTbl = $this->add_wallet_table($operator_id,$amount,$user_id,$user->roleId,$transaction_id,$order_id,$api_details->api_id,$service_id);

            $passaeps = $user->aeps_password;

            $url = $api_details->api_url;





            $values = array(

                "superMerchantId" => $api_details->key_id,

                "latitude" => $lat,

                "longitude" => $long,

                "mobileNumber" => $mobile,

                "languageCode" => "en",

                "paymentType" => "B",

                "transactionType" => "M",

                "transactionAmount" => $amount,

                "merchantUserName" => $user->aeps_mid,

                "merchantPin" => md5($passaeps),

                "merchantTransactionId" => $transaction_id,

                "cardnumberORUID" => array(

                    "adhaarNumber" => $this->isAadharValid($aadharNumber) ? $aadharNumber : "",

                    "indicatorforUID" => "0",

                    "nationalBankIdentificationNumber" => $bank_id

                ),

                "captureResponse" => $captureResponse

            );



            //Update api logs

            $this->api_log($transaction_id, $service_id, $api_details, $order_id, $user_id, json_encode($values), "PENDING", "cashwithdrawal");

            //Ends





            //Check Duplicate Transaction Starts

            $min = "5";

            $cDate = date('Y-m-d H:i:s');

            $newtimestamp = strtotime($cDate . ' - ' . $min . ' minute');

            $cDate1 = date('Y-m-d H:i:s', $newtimestamp);

            //$che="SELECT * from tbl_transaction_dtls WHERE  trans_date >= '".$cDate1."' AND trans_date < '".$cDate."' AND bank_account_no='".$accountNumber."'";

            $check = TransactionDetail::where([

                ['trans_date', '>=', $cDate1],

                ['trans_date', '<', $cDate],

                ['aadharnumber', '=', $aadharNumber], ['total_amount', '=', $amount]
            ])->count();



            if ($check > 0) {

                $response = array(

                    'status' => "false",

                    'msg' => "Same receipt and amount just now hit one Trasaction so Try again after 5 minute",

                    'result' => ""

                );

                if (isset($request->user_id)) {

                    $statusMsg = "Same receipt and amount just now hit one Trasaction so Try again after 5 minute";

                    $success = "false";

                    return $this->sendSuccess($success, $statusMsg);
                }







                $msg = "Duplicate Transaction";

                return redirect()->back()->with('merror', $response['message'] ?? "Duplicate Transaction")->with('messages', $msg);

                exit;
            }

            //Check Duplicate Transaction Ends



            //Transaction Table Entryz

            $transaction_msg = "";

            $aeps_balance = "";

            $clientreferenceid = "";

            $rrnno = "";

            $status = "PENDING";

            $aeps_bank = file_get_contents("https://fingpayap.tapits.in/fpaepsservice/api/bankdata/bank/details");

            $aeps_bank = json_decode($aeps_bank, true);

            $bank_name = '';

            foreach ($aeps_bank['data'] as $bank) {

                if ($bank['iinno'] == $bank_id) {

                    $bank_name = $bank['bankName'];
                }
            }

            $bankname = $bank_name;

            $txnTbl = $this->add_wallet_table($bankname, $mobile, $aadharNumber, $bank_name, $operator_id, $amount, $user_id, $user->roleId, $transaction_id, $order_id, $api_details->api_id, $service_id);

            //         	return redirect()->back()->with('merror', $response['message'] ?? "Transaction Entry Done & Wallet Updated")->with('messages', $msg);

            // //         exit;//Ends 







            //         $msg="Transaction Entry Done";

            // 		return redirect()->back()->with('merror', $response['message'] ?? "Transaction Entry Done")->with('messages', $msg);

            //         exit;





            //Api Hit

            $key = '';

            $num = '';

            $device_id = 'J9:H5:4D:9D:0Q';

            $mt_rand = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15);

            foreach ($mt_rand as $chr) {

                $num .= $chr;

                $key .= chr($chr);
            }

            $iv = '06f2f04cc530364f';

            $ciphertext_raw = openssl_encrypt(json_encode($values), 'AES-128-CBC', $key, $options = OPENSSL_RAW_DATA, $iv);

            $request = base64_encode($ciphertext_raw);

            openssl_public_encrypt($key, $crypttext, $this->pub_key);

            $concat = json_encode($values);

            $hashedvalue = hash("sha256", $concat, true);

            $hashedapi = hash("sha256", "6466829c00b1e9f4325e3c5104be6bb111f77140ecb6a19307cf46cf2cf0e15e", true);



            $newhashed = $hashedvalue . $hashedapi;

            $header = [

                'Content-Type: text/xml',

                'trnTimestamp:' . date('d/m/Y H:i:s'),

                'hash:' . base64_encode($hashedvalue),

                'deviceIMEI:' . $device_id,

                'eskey:' . base64_encode($crypttext)

            ];



            $curl = curl_init();

            curl_setopt_array($curl, array(

                CURLOPT_URL => $url,

                CURLOPT_RETURNTRANSFER => true,

                CURLOPT_SSL_VERIFYPEER => true,

                CURLOPT_SSL_VERIFYHOST => 2,

                CURLOPT_TIMEOUT => 30,

                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,

                CURLOPT_CUSTOMREQUEST => "POST",

                CURLOPT_POSTFIELDS => $request,

                CURLOPT_HTTPHEADER => $header

            ));



            $response1 = curl_exec($curl);

            $array = json_decode($response1, true);

            $response = $array;

            $err = curl_error($curl);

            $info = curl_getinfo($curl);

            curl_close($curl);



            $logfile = 'aepsresponselog.txt';

            $log = 'URL - ' . $url . "\n";

            $log .= 'RAW REQUEST - ' . json_encode($values) . "\n";

            $log .= 'REQUEST - ' . $request . "\n";

            $log .= 'RESPONSE - ' . $response1 . "\n\n";

            file_put_contents($logfile, $log, FILE_APPEND | LOCK_EX);





            //   $response=$array;

            //  Api Hit End



            //Test Mode Started



            // $array='{"status":true,"message":"Request Completed","data":{"terminalId":"FAA26671","requestTransactionTime":"15/11/2021 17:50:27","transactionAmount":100,"transactionStatus":"successful","balanceAmount":100,"strMiniStatementBalance":null,"bankRRN":"131917885071","transactionType":"CW","fpTransactionId":"CWBB2196755151121175027054I","merchantTxnId":null,"errorCode":null,"errorMessage":null,"merchantTransactionId":"94591564616128","bankAccountNumber":null,"ifscCode":null,"bcName":null,"transactionTime":null,"agentId":0,"issuerBank":null,"customerAadhaarNumber":null,"customerName":null,"stan":null,"rrn":null,"uidaiAuthCode":null,"bcLocation":null,"demandSheetId":null,"mobileNumber":null,"urnId":null,"miniStatementStructureModel":null,"miniOffusStatementStructureModel":null,"miniOffusFlag":false,"transactionRemark":null,"bankName":null,"prospectNumber":null,"internalReferenceNumber":null,"biTxnType":null,"subVillageName":null,"userProfileResponseModel":null,"hindiErrorMessage":null,"loanAccNo":null,"responseCode":"00","fpkAgentId":null},"statusCode":10000}';



            //Test Mode End

            //$response=$array;



            //Update api logs

            $this->update_api_log($service_id, $api_details, $order_id, $user_id, json_encode($values), json_encode($response), "aadhar_payment");

            //Ends



            //test

            // $response = array(

            //     'status' => 'true',

            //     'data' => array(

            //         'transactionStatus' => 'successful',

            //         'transactionAmount' => $amount,

            //         'balanceAmount' => '100.00',

            //         'merchantTransactionId' => $transaction_id,

            //         'requestTransactionTime' => date('d/m/Y H:i:s')

            //     )

            // );

            //test end

            // $this->api_log($service_id,$api_details,$order_id,$user_id,json_encode($values),json_encode($response),"aadharpay"); //add api logs

            // if($response) {

            if ($response['status'] == 'true' && $response['data']['transactionStatus'] == 'successful') {

                $aadharNumber = str_repeat('X', strlen($aadharNumber) - 4) . substr($aadharNumber, -4);

                $aeps_bank = file_get_contents("https://fingpayap.tapits.in/fpaepsservice/api/bankdata/bank/details");

                $aeps_bank = json_decode($aeps_bank, true);

                $bank_name = '';

                foreach ($aeps_bank['data'] as $bank) {

                    if ($bank['iinno'] == $bank_id) {

                        $bank_name = $bank['bankName'];
                    }
                }

                //$txnTbl = $this->add_wallet_table($mobile,$aadharNumber,$bank_name,$operator_id,$amount,$user_id,$user->roleId,$transaction_id,$order_id,$api_details->api_id,$service_id);

                //Distribute Commision 

                $aadhar = $aadharNumber;

                $success = $this->add_success_comm($bank_name, $aadhar, $mobile, $operator_id, $amount, $user_id, $user->roleId, $user->parent_user_id, $transaction_id, $order_id, $api_details->api_id, $service_id, $service_type);

                $aeps_bank = file_get_contents("https://fingpayap.tapits.in/fpaepsservice/api/bankdata/bank/details");

                $aeps_bank = json_decode($aeps_bank, true);

                // $bank_name = '';





                $msg = "Aadhar Pay Request Processed Successfully\n";

                $transaction_amount = $response['data']['transactionAmount'];

                $balance_amount = $response['data']['balanceAmount'];

                $merchantTransactionId = $response['data']['merchantTransactionId'];

                $requestTransactionTime = $response['data']['requestTransactionTime'];

                $clientreferenceid = $response['data']['fpTransactionId'];

                $rrnno = $response['data']['bankRRN'];

                $bank_name = $bank_name;

                // $msg .= $bank_name."\n";

                $transactionAmount = $response['data']['transactionAmount'];



                $aeps_balance = $balance_amount;

                $transaction_msg = $response['message'];



                /*Test Mode*/

                // $transaction_amount=100;

                // $balance_amount=100;

                // $merchantTransactionId="";

                // $requestTransactionTime ="";

                // $clientreferenceid = "";

                // $rrnno = "";

                // $bank_name = $bank_name;

                // // $msg .= $bank_name."\n";

                // $transactionAmount = "";

                // $aadharNumber=str_repeat('X', strlen($aadharNumber) - 4) . substr($aadharNumber, -4);

                // $aeps_balance="";

                // $transaction_msg="";

                //Transaction Table Entry

                $fpTransactionId = $response['data']['fpTransactionId'];

                $update_txn = $this->update_aeps_txn($fpTransactionId, $transaction_msg, $amount, $aeps_balance, $clientreferenceid, $rrnno, $operator_id, $user_id, $user->roleId, $transaction_id, $order_id, $api_details->api_id, $service_id, "SUCCESS", $mobile, $aadharNumber);

                //Ends 

                $msg = "Transaction Entry Updated";

                // return redirect()->back()->with('merror', $response['message'] ?? "Transaction Entry Updated")->with('messages', $msg);

                // exit;

                Session::put('clientreferenceid', $clientreferenceid);

                Session::put('bankrrn', $rrnno);

                Session::put('transaction_id', $transaction_id);

                Session::put('order_id', $order_id);

                Session::put('aadharNumber', $aadharNumber);

                Session::put('bank_name', $bank_name);

                Session::put('msuccess1', $msg);

                Session::put('transaction_amount', $transaction_amount);

                Session::put('balance_amount', $balance_amount);

                Session::put('merchanttransactionid', $merchantTransactionId);

                Session::put('time', $requestTransactionTime);

                return redirect()->back();
            } else {



                $transaction_msg = $response['message'];

                $fpTransactionId = $response['data']['fpTransactionId'];

                $update_txn = $this->update_aeps_txn($fpTransactionId, $transaction_msg, $amount, $aeps_balance, $clientreferenceid, $rrnno, $operator_id, $user_id, $user->roleId, $transaction_id, $order_id, $api_details->api_id, $service_id, "FAILED", $mobile, $aadharNumber);

                //Ends 

                $msg = "Transaction Entry Updated";

                Session::put('pkmkb', $transaction_msg);

                // $add_txn = $this->update_aeps_txn($operator_id,$user_id,$user->roleId,$transaction_id,$order_id,$api_details->api_id,$service_id,"FAILED",$mobile);

                //  $failed = $this->add_failed_balance($operator_id,$amount,$user_id,$user->roleId,$transaction_id,$order_id,$api_details->api_id,$service_id);



                $msg = $response['message'];

                return redirect()->back();
            }
        } elseif ($service_type == 'cash_withdrawal') {



            //  Fingerprint Data Capturing



            $captureResponse = $this->captureData("", $request->input('txtPidData'));

            //End



            if ($request->input('capturescore') == null) {

                return redirect()->back()->with('error', 'kindly Capture Fingerprint');
            }







            $service_id = "6"; //fetch

            $operator_id = "23"; //fetch







            $api_id = OperatorSetting::where(['operator_id' => $operator_id, 'service_id' => $service_id])->first() ?? "00";

            if (!$api_id) {

                return redirect()->back()->with('merror', 'Something went wrong');
            }

            $api_details = ApiSetting::where(['api_id' => $api_id->default_api_id, 'is_deleted' => 0, 'activated_status' => 'YES'])->first();





            if (!$api_details) {

                return redirect()->back()->with('merror', 'Something went wrong');
            }



            $old_oid =  file_get_contents("admin/admin/txn_order_id.txt");

            $sno_order_id = (int)$old_oid + 1;

            $order_id = "PM" . $sno_order_id;

            file_put_contents('admin/admin/txn_order_id.txt', $sno_order_id . "");





            $transaction_id = "PAYMAMA" . rand(00000000000000, 99999999999999);





            // $this->add_wallet_table($operator_id,$amount,$user_id,$user->roleId,$transaction_id,$order_id,$api_details->api_id,$service_id);

            $passaeps = $user->aeps_password;

            $url = $api_details->api_url;







            $values = array(

                "superMerchantId" => $api_details->key_id,

                "latitude" => $lat,

                "longitude" => $long,

                "mobileNumber" => $mobile,

                "languageCode" => "en",

                "paymentType" => "B",

                "transactionType" => "CW",

                "transactionAmount" => $amount,

                "merchantUserName" => $user->aeps_mid,

                "merchantPin" => md5($passaeps),

                "merchantTranId" => $transaction_id,

                "cardnumberORUID" => array(

                    "adhaarNumber" => $this->isAadharValid($aadharNumber) ? $aadharNumber : "",

                    "indicatorforUID" => "0",

                    "nationalBankIdentificationNumber" => $bank_id

                ),

                "captureResponse" => $captureResponse

            );



            //Update api logs

            $this->api_log($transaction_id, $service_id, $api_details, $order_id, $user_id, json_encode($values), "PENDING", "cashwithdrawal");

            //Ends





            //Check Duplicate Transaction Starts

            //           $min="5";

            //           $cDate=date('Y-m-d H:i:s');

            //           $newtimestamp = strtotime($cDate.' - '.$min.' minute');

            //           $cDate1= date('Y-m-d H:i:s', $newtimestamp);

            //             //$che="SELECT * from tbl_transaction_dtls WHERE  trans_date >= '".$cDate1."' AND trans_date < '".$cDate."' AND bank_account_no='".$accountNumber."'";

            //             $check=TransactionDetail::where([

            //             ['trans_date', '>=', $cDate1],

            //             ['trans_date', '<', $cDate],

            //             ['aadharnumber', '=', $aadharNumber],['total_amount', '=', $amount]])->count();





            //           if($check > 0){

            //           $response = array(

            //                 'status' => "false",

            //                 'msg' => "Same receipt and amount just now hit one Trasaction so Try again after 5 minute",

            //                 'result' =>""

            //             );

            //             if(isset($request->user_id))

            //             {

            //                 $statusMsg = "Same receipt and amount just now hit one Trasaction so Try again after 5 minute";

            //                 $success = "false";

            //                 return $this->sendSuccess($success, $statusMsg);

            //             }







            //         $msg="Duplicate Transaction";

            // 		return redirect()->back()->with('pkmkb', $response['message'] ?? "Duplicate Transaction")->with('messages', $msg);

            //         exit;

            //         }

            //Check Duplicate Transaction Ends



            //Transaction Table Entryz

            $transaction_msg = "";

            $aeps_balance = "";

            $clientreferenceid = "";

            $rrnno = "";

            $status = "PENDING";

            $bank_name = "";

            //Make Wallet Table Entry

            $aeps_bank = file_get_contents("https://fingpayap.tapits.in/fpaepsservice/api/bankdata/bank/details");

            $aeps_bank = json_decode($aeps_bank, true);

            $bank_name = '';

            foreach ($aeps_bank['data'] as $bank) {

                if ($bank['iinno'] == $bank_id) {

                    $bank_name = $bank['bankName'];
                }
            }

            $bankname = $bank_name;

            $txnTbl = $this->add_wallet_table($bankname, $mobile, $aadharNumber, $bank_name, $operator_id, $amount, $user_id, $user->roleId, $transaction_id, $order_id, $api_details->api_id, $service_id);

            //         	return redirect()->back()->with('merror', $response['message'] ?? "Transaction Entry Done & Wallet Updated")->with('messages', $msg);

            // //         exit;//Ends 





            $msg = "Transaction Entry Done";

            // return redirect()->back()->with('merror', $response['message'] ?? "Transaction Entry Done")->with('messages', $msg);

            // //         exit;



            //Live Mode 



            // Api Hit

            $key = '';

            $num = '';

            $device_id = 'J9:H5:4D:9D:0Q';

            $mt_rand = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15);

            foreach ($mt_rand as $chr) {

                $num .= $chr;

                $key .= chr($chr);
            }

            $iv = '06f2f04cc530364f';

            $ciphertext_raw = openssl_encrypt(json_encode($values), 'AES-128-CBC', $key, $options = OPENSSL_RAW_DATA, $iv);

            $request = base64_encode($ciphertext_raw);

            openssl_public_encrypt($key, $crypttext, $this->pub_key);

            $concat = json_encode($values);

            $hashedvalue = hash("sha256", $concat, true);

            $hashedapi = hash("sha256", "6466829c00b1e9f4325e3c5104be6bb111f77140ecb6a19307cf46cf2cf0e15e", true);



            $newhashed = $hashedvalue . $hashedapi;

            $header = [

                'Content-Type: text/xml',

                'trnTimestamp:' . date('d/m/Y H:i:s'),

                'hash:' . base64_encode($hashedvalue),

                'deviceIMEI:' . $device_id,

                'eskey:' . base64_encode($crypttext)

            ];



            // $curl = curl_init();

            // curl_setopt_array($curl, array(

            //     CURLOPT_URL => $url,

            //     CURLOPT_RETURNTRANSFER => true,

            //     CURLOPT_SSL_VERIFYPEER => true, 

            //     CURLOPT_SSL_VERIFYHOST => 2,

            //     CURLOPT_TIMEOUT => 30,

            //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,

            //     CURLOPT_CUSTOMREQUEST => "POST",

            //     CURLOPT_POSTFIELDS => $request,

            //     CURLOPT_HTTPHEADER => $header

            // ));

            // $response = curl_exec($curl);

            // $array = json_decode($response, true);

            // $err = curl_error($curl);

            // $info = curl_getinfo($curl);

            // curl_close($curl);

            // $logfile = 'aepsresponselog.txt';

            // $log = 'URL - '.$url."\n";

            // $log .= 'RAW REQUEST - '.json_encode($values)."\n";

            // $log .= 'REQUEST - '.$request."\n";

            // $log .= 'RESPONSE - '.$response."\n\n";

            // file_put_contents($logfile, $log, FILE_APPEND | LOCK_EX);



            $response = $this->aeps_api($url, $values, 'J9:H5:4D:9D:0Q');



            //     //Api Hit End





            // $response=$array;





            //Testing Mode    

            //$array='{"status":true,"message":"Request Completed","data":{"terminalId":"FAA26671","requestTransactionTime":"15/11/2021 17:50:27","transactionAmount":100,"transactionStatus":"successful","balanceAmount":100,"strMiniStatementBalance":null,"bankRRN":"131917885071","transactionType":"CW","fpTransactionId":"CWBB2196755151121175027054I","merchantTxnId":null,"errorCode":null,"errorMessage":null,"merchantTransactionId":"94591564616128","bankAccountNumber":null,"ifscCode":null,"bcName":null,"transactionTime":null,"agentId":0,"issuerBank":null,"customerAadhaarNumber":null,"customerName":null,"stan":null,"rrn":null,"uidaiAuthCode":null,"bcLocation":null,"demandSheetId":null,"mobileNumber":null,"urnId":null,"miniStatementStructureModel":null,"miniOffusStatementStructureModel":null,"miniOffusFlag":false,"transactionRemark":null,"bankName":null,"prospectNumber":null,"internalReferenceNumber":null,"biTxnType":null,"subVillageName":null,"userProfileResponseModel":null,"hindiErrorMessage":null,"loanAccNo":null,"responseCode":"00","fpkAgentId":null},"statusCode":10000}';



            //Testing Mode End 



            //$response=$array;





            //Update api logs

            $this->update_api_log($service_id, $api_details, $order_id, $user_id, json_encode($values), json_encode($response), "cashwithdrawal");

            //Ends








        if($response){

            if ($response['status'] == 'true' || isset($response['data']['transactionStatus'])) {
           // if ($response['status'] == 'true') {


                $aadharNumber = str_repeat('X', strlen($aadharNumber) - 4) . substr($aadharNumber, -4);

                $aeps_bank = file_get_contents("https://fingpayap.tapits.in/fpaepsservice/api/bankdata/bank/details");

                $aeps_bank = json_decode($aeps_bank, true);

                $bank_name = '';

                foreach ($aeps_bank['data'] as $bank) {

                    if ($bank['iinno'] == $bank_id) {

                        $bank_name = $bank['bankName'];
                    }
                }





                //Distribute Commision 

                $aadhar = $aadharNumber;

                $success = $this->add_success_comm($bank_name, $aadhar, $mobile, $operator_id, $amount, $user_id, $user->roleId, $user->parent_user_id, $transaction_id, $order_id, $api_details->api_id, $service_id, $service_type);

                //End





                $aeps_bank = file_get_contents("https://fingpayap.tapits.in/fpaepsservice/api/bankdata/bank/details");

                $aeps_bank = json_decode($aeps_bank, true);

                $bank_name = '';

                foreach ($aeps_bank['data'] as $bank) {

                    if ($bank['iinno'] == $bank_id) {

                        $bank_name = $bank['bankName'];
                    }
                }



                $msg = "Cash Withdrawal Request Processed Successfully";

                /*Live Mode*/

                $transaction_amount = $response['data']['transactionAmount'];

                $balance_amount = $response['data']['balanceAmount'];

                $merchantTransactionId = $response['data']['merchantTransactionId'];

                $requestTransactionTime = $response['data']['requestTransactionTime'];

                $clientreferenceid = $response['data']['fpTransactionId'];

                $rrnno = $response['data']['bankRRN'];

                $fpTransactionId = $response['data']['fpTransactionId'];

                $bank_name = $bank_name;

                // $msg .= $bank_name."\n";

                $transactionAmount = $response['data']['transactionAmount'];

                $aadharNumber = str_repeat('X', strlen($aadharNumber) - 4) . substr($aadharNumber, -4);

                $aeps_balance = $balance_amount;

                $transaction_msg = $response['message'];



                // /*Test Mode*/

                // $transaction_amount=100;

                // $balance_amount=100;

                // $merchantTransactionId="";

                // $requestTransactionTime ="";

                // $clientreferenceid = "";

                // $rrnno = "";

                // $bank_name = $bank_name;

                // // $msg .= $bank_name."\n";

                // $transactionAmount = "";

                // $aadharNumber=str_repeat('X', strlen($aadharNumber) - 4) . substr($aadharNumber, -4);

                // $aeps_balance="";

                // $transaction_msg="";



                //Transaction Table Entry

                $fpTransactionId = $response['data']['fpTransactionId'];

                $update_txn = $this->update_aeps_txn($fpTransactionId, $transaction_msg, $amount, $aeps_balance, $clientreferenceid, $rrnno, $operator_id, $user_id, $user->roleId, $transaction_id, $order_id, $api_details->api_id, $service_id, "SUCCESS", $mobile, $aadharNumber);

                //Ends 

                // $msg="Transaction Entry Updated";

                // return redirect()->back()->with('merror', $response['message'] ?? "Transaction Entry Updated")->with('messages', $msg);

                // exit;

                //return $response;

                //Session::put('pkmkb',$clientreferenceid);

                Session::put('clientreferenceid', $clientreferenceid);

                Session::put('bankrrn', $rrnno);

                Session::put('transaction_id', $transaction_id);

                Session::put('order_id', $order_id);

                Session::put('aadharNumber', $aadharNumber);

                Session::put('bank_name', $bank_name);

                Session::put('msuccess1', $msg);

                Session::put('transaction_amount', $transaction_amount);

                Session::put('balance_amount', $balance_amount);

                Session::put('merchanttransactionid', $merchantTransactionId);

                Session::put('time', $requestTransactionTime);





                return redirect()->route('aeps');
            }



            //     else if($response== null || $response == null  || str_replace('"','',$response) == 'null' || $response['status'] == '"null"' || $response['status'] == null)

            //     {

            //          $aadharNumber=str_repeat('X', strlen($aadharNumber) - 4) . substr($aadharNumber, -4);

            //         $transaction_msg=$response['message'];

            //         $fpTransactionId = $response['data']['fpTransactionId'];

            //         $update_txn = $this->update_aeps_txn($fpTransactionId,$transaction_msg,$amount,$aeps_balance,$clientreferenceid,$rrnno,$operator_id,$user_id,$user->roleId,$transaction_id,$order_id,$api_details->api_id,$service_id,"PENDING",$mobile,$aadharNumber);

            //         //Ends 

            //         $msg="Transaction Entry Updated";



            // // $add_txn = $this->update_aeps_txn($operator_id,$user_id,$user->roleId,$transaction_id,$order_id,$api_details->api_id,$service_id,"FAILED",$mobile);

            ////  $failed = $this->add_failed_balance($operator_id,$amount,$user_id,$user->roleId,$transaction_id,$order_id,$api_details->api_id,$service_id);

            //  $msg=$response['message'];

            // Session::put('pkmkb',$response['message']);

            //  return redirect()->back()->with('pkmkb', 'TRANSACTION PENDING' ?? "Something went wrong!")->with('messages', 'TRANSACTION PENDING');

            //     }



            else {
                if(isset($response['data']['fpTransactionId']) && $response['data']['fpTransactionId'] != '') {
                $clientreferenceid = $response['data']['fpTransactionId'];
                }
                else
                {
                $clientreferenceid = "";
                }
                
                if(isset($response['data']['bankRRN']) && $response['data']['bankRRN'] != '') {
                 $rrnno = $response['data']['bankRRN'];
                }
                else
                {
                  $rrnno = "";
                }
                
                if(isset($response['data']['fpTransactionId']) && $response['data']['fpTransactionId'] != '') {
                $clientreferenceid = $response['data']['fpTransactionId'];
                }
                else
                {
                $clientreferenceid = "";
                }
                
                
                if(isset($response['data']['fpTransactionId']) && $response['data']['fpTransactionId'] != '') {
                $clientreferenceid = $response['data']['fpTransactionId'];
                }
                else
                {
                $clientreferenceid = "";
                }
                
                
                if(isset($response['data']['fpTransactionId']) && $response['data']['fpTransactionId'] != '') {
                $fpTransactionId = $response['data']['fpTransactionId'];
                }
                else
                {
                $fpTransactionId = "";
                }
              

                

                $aadharNumber = str_repeat('X', strlen($aadharNumber) - 4) . substr($aadharNumber, -4);

                $transaction_msg = $response['message'];

                $update_txn = $this->update_aeps_txn($fpTransactionId, $transaction_msg, $amount, $aeps_balance, $clientreferenceid, $rrnno, $operator_id, $user_id, $user->roleId, $transaction_id, $order_id, $api_details->api_id, $service_id, "FAILED", $mobile, $aadharNumber);

                //Ends 



                Session::put('pkmkb', $response['message']);



                // $add_txn = $this->update_aeps_txn($operator_id,$user_id,$user->roleId,$transaction_id,$order_id,$api_details->api_id,$service_id,"FAILED",$mobile);

                //  $failed = $this->add_failed_balance($operator_id,$amount,$user_id,$user->roleId,$transaction_id,$order_id,$api_details->api_id,$service_id);

                // return $msg=$response['message'];



                // return redirect()->back()->with('pkmkb', $response['message']);

                return redirect()->route('aeps');
            } }
        } elseif ($service_type == 'generate_otp') {

            $url = "https://fingpayap.tapits.in/fpaepsservice/api/CashDeposit/merchant/php/generate/otp";

            $passaeps = $user->aeps_password;

            $values = array(

                "superMerchantId" => $this->common('supermid'),

                "latitude" => $lat,

                "longitude" => $long,

                "mobileNumber" => $mobile,

                "languageCode" => "en",

                "paymentType" => "B",

                "secretKey" => "", //pending

                "transactionType" => "CDO",

                "accountNumber" => "", //acc

                "amount" => $amount,

                "merchantUserName" => $user->aeps_mid,

                "merchantPin" => md5($passaeps),

                "merchantTranId" => $transaction_id,

                "iin" => $bank_id,

            );



            $response = $this->aeps_api($url, $values, 'J9:H5:4D:9D:0Q');

            // print_r($response);die();

            if ($response['status'] == 'true' && $response['message'] == 'Request Completed') {

                return redirect()->route('aeps_otp')->with('success', 'We have sent an OTP to your mobile number.');
            } else {

                return redirect()->back()->with('error', $response['message']);
            }
        } elseif ($service_type == 'cash_deposit') {

            $service_id = "10"; //fetch

            $operator_id = "45"; //fetch

            $api_id = OperatorSetting::where(['operator_id' => $operator_id, 'service_id' => $service_id])->first() ?? "00";

            if (!$api_id) {

                return redirect()->back()->with('merror', 'Something went wrong');
            }

            $api_details = ApiSetting::where(['api_id' => $api_id->default_api_id, 'is_deleted' => 0, 'activated_status' => 'YES'])->first();

            if (!$api_details) {

                return redirect()->back()->with('merror', 'Something went wrong');
            }



            $old_oid =  file_get_contents("admin/admin/txn_order_id.txt");

            $sno_order_id = (int)$old_oid + 1;

            $order_id = "PM" . $sno_order_id;

            file_put_contents('admin/admin/txn_order_id.txt', $sno_order_id . "");





            $transaction_id = rand(00000000000000, 99999999999999);

            $txnTbl = $this->add_wallet_table($operator_id, $amount, $user_id, $user->roleId, $transaction_id, $order_id, $api_details->api_id, $service_id);



            $url = $api_details->api_url;

            $passaeps = $user->aeps_password;

            $values = array(

                "superMerchantId" => $api_details->key_id,

                "latitude" => $lat,

                "longitude" => $long,

                "mobileNumber" => $mobile,

                "languageCode" => "en",

                "paymentType" => "B",

                "transactionType" => "CD",

                "transactionAmount" => $amount,

                "merchantUserName" => $user->aeps_mid,

                "merchantPin" => md5($passaeps),

                "merchantTransactionId" => $transaction_id,

                "cardnumberORUID" => array(

                    "adhaarNumber" => $this->isAadharValid($aadharNumber) ? $aadharNumber : "",

                    "indicatorforUID" => "0",

                    "nationalBankIdentificationNumber" => $bank_id

                ),

                "captureResponse" => $captureResponse

            );

            $response = $this->aeps_api($url, $values, 'J9:H5:4D:9D:0Q');

            // print_r($response);die();



            //test

            // $response = array(

            //     'status' => 'true',

            //     'data' => array(

            //         'transactionStatus' => 'successful',

            //         'transactionAmount' => $amount,

            //         'balanceAmount' => '100.00',

            //         'merchantTransactionId' => $transaction_id,

            //         'requestTransactionTime' => date('d/m/Y H:i:s')

            //     )

            // );

            //test end

            $success = $this->add_success_comm($operator_id, $amount, $user_id, $user->roleId, $user->parent_user_id, $transaction_id, $order_id, $api_details->api_id, $service_id, $service_type);

            $this->api_log($service_id, $api_details, $order_id, $user_id, json_encode($values), json_encode($response), "cashdeposit"); //add api logs

            if ($response['status'] == 'true' && $response['data']['transactionStatus'] == 'successful') {

                $aeps_bank = file_get_contents("https://fingpayap.tapits.in/fpaepsservice/api/bankdata/bank/details");

                $aeps_bank = json_decode($aeps_bank, true);

                $bank_name = '';

                foreach ($aeps_bank['data'] as $bank) {

                    if ($bank['iinno'] == $bank_id) {

                        $bank_name = $bank['bankName'];
                    }
                }

                $msg = "Aadhar Pay Request Processed Successfully\n";

                $msg .= "Transaction Amount :- ₹ " . $response['data']['transactionAmount'] . "\n";

                $msg .= "Current Balance :- ₹ " . $response['data']['balanceAmount'] . "\n";

                $msg .= "Transaction ID :- " . $response['data']['merchantTransactionId'] . "\n";

                $msg .= "Order ID :- " . $order_id . "\n";

                $msg .= "Date and Time :- " . $response['data']['requestTransactionTime'] . "\n";

                $msg .= $bank_name . "\n";

                $amount = $response['data']['transactionAmount'];

                return redirect()->back()->with('msuccess', $msg);
            } else {

                $failed = $this->add_failed_balance($operator_id, $amount, $user_id, $user->roleId, $transaction_id, $order_id, $api_details->api_id, $service_id);

                return redirect()->back()->with('merror', $response['message']);
            }
        } else {

            return redirect()->back()->with('merror', 'Something went wrong!');
        }
    }





    public function aepscallback(Request $request)

    {



        $data = $request->all();



        // CURRENCY=INR&GATEWAYNAME=PPBL&RESPMSG=Txn+Success&BANKNAME=&PAYMENTMODE=UPI&CUSTID=2&MID=xRiYem08825900198760&MERC_UNQ_REF=&RESPCODE=01&TXNID=20211020111212800110168583056324772&TXNAMOUNT=10.00&ORDERID=71684&STATUS=TXN_SUCCESS&BANKTXNID=129365656551&TXNDATETIME=2021-10-20+21%3A15%3A00.0&TXNDATE=2021-10-20

        // Log::info('AEPSCALLBACK: '.json_encode($data));



        $check_txn = TransactionDetail::where('fpTransactionId', $data['fpTransactionId'])->first();

        $user = User::where('userId', $check_txn['user_id'])->first();



        //  $user = User::where('userId', $check_txn['user_id'])->first();

        if ($check_txn['transaction_status'] == 'PENDING') {



            //if webhook response is success

            if ($data['transactionStatus'] == 'S') {

                $bank_name = $check_txn['aeps_bank_id'];

                $aadhar = $data['aadhaarNumber'];

                $mobile = $data['mobile'];

                $operator_id = $check_txn['operator_id'];

                $amount = $data['amount'];

                $user_id = $check_txn['user_id'];

                $transaction_id = $check_txn['transaction_id'];

                $order_id = $check_txn['order_id'];

                #Get Details of API

                $api_id = $check_txn['api_id'];

                #Get Details of User



                $service_id = $check_txn['service_id'];

                $service = ServicesType::where('service_id', $service_id)->first();

                $service_type = $service['service_name'];

                $success = $this->add_success_comm($bank_name, $aadhar, $mobile, $operator_id, $amount, $user_id, $user->roleId, $user->parent_user_id, $transaction_id, $order_id, $api_id, $service_id, $service_type);

                //End









                $msg = "Cash Withdrawal Request Processed Successfully";

                /*Live Mode*/

                $transaction_amount = $amount;

                $balance_amount = $check_txn['aeps_balance'];;

                $merchantTransactionId = $transaction_id;

                $requestTransactionTime = $check_txn['trans_date'];

                $clientreferenceid = $check_txn['client_reference_id'];

                $rrnno = $check_txn['rrnno'];

                $bank_name = $bank_name;

                // $msg .= $bank_name."\n";

                $transactionAmount = $amount;

                $aadharNumber = $aadhar;

                $aeps_balance = $check_txn['aeps_balance'];

                $transaction_msg = $check_txn['transaction_msg'];



                // /*Test Mode*/

                // $transaction_amount=100;

                // $balance_amount=100;

                // $merchantTransactionId="";

                // $requestTransactionTime ="";

                // $clientreferenceid = "";

                // $rrnno = "";

                // $bank_name = $bank_name;

                // // $msg .= $bank_name."\n";

                // $transactionAmount = "";

                // $aadharNumber=str_repeat('X', strlen($aadharNumber) - 4) . substr($aadharNumber, -4);

                // $aeps_balance="";

                // $transaction_msg="";



                //Transaction Table Entry

                $fpTransactionId = $response['data']['fpTransactionId'];

                $update_txn = $this->update_aeps_txn($fpTransactionId, $transaction_msg, $amount, $aeps_balance, $clientreferenceid, $rrnno, $operator_id, $user_id, $user->roleId, $transaction_id, $order_id, $api_details->api_id, $service_id, "SUCCESS", $mobile, $aadharNumber);
            }



            //if webhook response is failed

            else if ($data['transactionStatus'] == 'I') {

                // $walletResponse = $this->updateWalletTransaction($response, 'PENDING');

                // if ($walletResponse) {

                //     return false;

                // }

            } else {

                $bank_name = $check_txn['aeps_bank_id'];

                $aadhar = $data['aadhaarNumber'];

                $mobile = $data['mobile'];

                $operator_id = $check_txn['operator_id'];

                $amount = $data['amount'];

                $user_id = $check_txn['user_id'];

                $transaction_id = $check_txn['transaction_id'];

                $order_id = $check_txn['order_id'];

                #Get Details of API

                $api_id = $check_txn['api_id'];

                #Get Details of User



                $service_id = $check_txn['service_id'];

                $service = ServicesType::where('service_id', $service_id)->first();

                $service_type = $service['service_name'];

                $success = $this->add_success_comm($bank_name, $aadhar, $mobile, $operator_id, $amount, $user_id, $user->roleId, $user->parent_user_id, $transaction_id, $order_id, $api_id, $service_id, $service_type);

                //End





                $aeps_bank = file_get_contents("https://fingpayap.tapits.in/fpaepsservice/api/bankdata/bank/details");

                $aeps_bank = json_decode($aeps_bank, true);

                $bank_name = '';

                foreach ($aeps_bank['data'] as $bank) {

                    if ($bank['iinno'] == $bank_id) {

                        $bank_name = $bank['bankName'];
                    }
                }



                $msg = "Cash Withdrawal Request Processed Successfully";

                /*Live Mode*/

                $transaction_amount = $amount;

                $balance_amount = $check_txn['aeps_balance'];;

                $merchantTransactionId = $transaction_id;

                $requestTransactionTime = $check_txn['trans_date'];

                $clientreferenceid = $check_txn['client_reference_id'];

                $rrnno = $check_txn['rrnno'];

                $bank_name = $bank_name;

                // $msg .= $bank_name."\n";

                $transactionAmount = $amount;

                $aadharNumber = $aadhar;

                $aeps_balance = $check_txn['aeps_balance'];

                $transaction_msg = $check_txn['transaction_msg'];

                $fpTransactionId = $response['data']['fpTransactionId'];

                $update_txn = $this->update_aeps_txn($fpTransactionId, $transaction_msg, $amount, $aeps_balance, $clientreferenceid, $rrnno, $operator_id, $user_id, $user->roleId, $transaction_id, $order_id, $api_details->api_id, $service_id, "FAILED", $mobile, $aadharNumber);
            }
        }



        return false;
    }







    //ICICI Cash Deposit Generate OTP



    public function icicicashdeposit(Request $request)
    {



        $accountno = $request->input('accountno');



        $mobile = $request->input('mobileNumber');

        $mpin = $request->input('mpin');

        $amount = $request->input('amount');

        // $msg = "Aadhar Pay Request Processed Successfully<br>";

        // $msg .= "Transaction Amount :- ₹ 100<br>";

        // $msg .= "Current Balance :- ₹ 100<br>";

        // $msg .= "Transaction ID :- 123<br>";

        // $msg .= "Date and Time :- ".date('d/m/Y H:i:s')."<br>";

        // $msg .= "Kotak Mahindra Bank<br>";

        // return redirect()->back()->with('msuccess', $msg);







        $lat = $request->input('latitude');

        $long = $request->input('longitude');

        $aadharNumber = $request->input('accountnumber');

        $mobile = $request->input('mobileNumber');

        $deviceId = '0610936'; //need to check for other biometric devices

        $service_type = $request->input('service_type');

        $mpin = $request->input('mpin');

        //$mpin=1234;

        //$amount = $request->input('amount') ?? "0.00";



        if (isset($request->user_id)) {

            $user_id = $request->user_id;

            $user = User::where(['userId' => $user_id, 'mpin' => $mpin])->first();
        } else {

            $user_id = Auth::user()->userId;

            $user = User::where(['userId' => Auth::user()->userId, 'mpin' => $mpin])->first();
        }





        if (empty($user) || !$user) {

            if (isset($request->user_id)) {

                $statusMsg = "Invalid Mpin";

                $success = "false";

                return $this->sendSuccess($success, $statusMsg);
            } else {

                return redirect()->back()->with('error', 'Invalid Mpin');
            }
        }

        if ($user) {

            $wallet_balance = $user->wallet_balance;

            $min_balance = $user->min_balance;

            $user_package_id = $user->package_id;

            $commissiondet = PackageCommissionDetail::where([['from_range', '<=', $amount], ['to_range', '>=', $amount], ['service_id', '=', 10], ['pkg_id', '=', $user_package_id], ['operator_id', 45]])->first();

            $charge = $commissiondet->retailer_commission;

            $totalAmount = $amount + $charge;

            if (!is_numeric($wallet_balance) || !is_numeric($min_balance) || !is_numeric($amount) || $wallet_balance + $amount < $min_balance || $wallet_balance < $totalAmount) {

                if (isset($request->user_id)) {

                    $statusMsg = "You have Insufficient Balance";

                    $success = "false";

                    return $this->sendSuccess($success, $statusMsg);
                } else {

                    return redirect()->back()->with('error', 'You have Insufficient Balance');
                }
            }
        }

        //$captureResponse = $this->captureData("",$request->input('txtPidData'));

        // print_r(json_encode($captureResponse));die();

        // $captureResponse = "";



        $url = "https://fingpayap.tapits.in/fpaepsservice/api/CashDeposit/merchant/php/generate/otp";

        $transaction_id = rand(00000000000000, 99999999999999);

        $passaeps = $user->aeps_password;

        $values = array(

            "superMerchantId" => 1015,

            "latitude" => $lat,

            "longitude" => $long,

            "mobileNumber" => $mobile,

            "languageCode" => "en",

            "paymentType" => "B",

            "secretKey" => "", //pending

            "transactionType" => "CDO",

            "accountNumber" => $accountno, //acc

            "amount" => $amount,

            "merchantUserName" => $user->aeps_mid,

            // "merchantPin" => md5('e8dcb850c71e3dd4a1052bb4b1d3caca'),

            "merchantPin" => md5($passaeps),

            "merchantTranId" => $transaction_id,

            "iin" => "508534",

            "secretKey" => "6466829c00b1e9f4325e3c5104be6bb111f77140ecb6a19307cf46cf2cf0e15e",

            "requestRemarks" => "Cash Deposit from this:9560620395",

            "fingpayTransactionId" => "",

            "otp" => "",

            "cdPkId" => "0",

            "paymentType" => "B"

        );



        $response = $this->aeps_iciciapi($url, $values, 'J9:H5:4D:9D:0Q');



        if (isset($request->user_id)) {

            if ($response['message'] == 'Message successfully sent to the entered mobile number.') {

                $response['message'] = "Otp Successfully sent.";
            }



            return $response;
        } else {

            //$reponse;



            if ($response['message'] == 'Message successfully sent to the entered mobile number.') {

                return view('modules.icici.onboarding', ['amount' => $amount, 'mpin' => $mpin, 'mobile' => $mobile, 'lat' => $lat, 'long' => $long, 'accountNumber' => $accountno, 'transaction_id' => $transaction_id, 'mobile' => $mobile, 'ftransid' => $response['data']['fingpayTransactionId'], 'cdPkId' => $response['data']['cdPkId']]);
            } else {

                return redirect()->back()->with('error', $response['message']);
            }
        }
    }

    public function iciciresendcashdeposit(Request $request)
    {





        // $msg = "Aadhar Pay Request Processed Successfully<br>";

        // $msg .= "Transaction Amount :- ₹ 100<br>";

        // $msg .= "Current Balance :- ₹ 100<br>";

        // $msg .= "Transaction ID :- 123<br>";

        // $msg .= "Date and Time :- ".date('d/m/Y H:i:s')."<br>";

        // $msg .= "Kotak Mahindra Bank<br>";

        // return redirect()->back()->with('msuccess', $msg);

        $validator = Validator::make($request->all(), [



            // 'PidOptions' => 'required',

        ]);



        $amount = $request->input('amount');

        $lat = $request->input('latitude');

        $long = $request->input('longitude');

        $accountno = $request->input('accountnumber');



        $mobile = $request->input('mobileNumber');

        $deviceId = '0610936'; //need to check for other biometric devices

        $service_type = $request->input('service_type');

        $mpin = $request->input('mpin');

        //$mpin=1234;

        //$amount = $request->input('amount') ?? "0.00";

        if (isset($request->user_id)) {

            $user_id = $request->user_id;
        } else {

            $user_id = Auth::user()->userId;
        }

        $user = User::where(['userId' => $user_id, 'mpin' => $mpin])->first();





        if (empty($user) || !$user) {

            return redirect()->back()->with('error', 'Invalid Mpin');
        }

        if ($user) {

            $wallet_balance = $user->wallet_balance;

            $min_balance = $user->min_balance;

            $user_package_id = $user->package_id;

            $commissiondet = PackageCommissionDetail::where([['from_range', '<=', $amount], ['to_range', '>=', $amount], ['service_id', '=', 10], ['pkg_id', '=', $user_package_id], ['operator_id', 45]])->first();

            $charge = $commissiondet->retailer_commission;

            $totalAmount = $amount + $charge;

            if (!is_numeric($wallet_balance) || !is_numeric($min_balance) || !is_numeric($amount) || $wallet_balance + $amount < $min_balance || $wallet_balance < $totalAmount) {

                return redirect()->back()->with('error', 'You have Insufficient Balance');
            }
        }

        //$captureResponse = $this->captureData("",$request->input('txtPidData'));

        // print_r(json_encode($captureResponse));die();

        // $captureResponse = "";



        $url = "https://fingpayap.tapits.in/fpaepsservice/api/CashDeposit/merchant/php/generate/otp";

        $transaction_id = rand(00000000000000, 99999999999999);

        $passaeps = $user->aeps_password;

        $values = array(

            "superMerchantId" => 1015,

            "latitude" => $lat,

            "longitude" => $long,

            "mobileNumber" => $mobile,

            "languageCode" => "en",

            "paymentType" => "B",

            "secretKey" => "", //pending

            "transactionType" => "CDO",

            "accountNumber" => $accountno, //acc

            "amount" => $amount,

            "merchantUserName" => $user->aeps_mid,

            "merchantPin" => md5($passaeps),

            "merchantTranId" => $transaction_id,

            "iin" => "508534",

            "subMerchantId" => "",

            "secretKey" => "6466829c00b1e9f4325e3c5104be6bb111f77140ecb6a19307cf46cf2cf0e15e",

            "requestRemarks" => "Cash Deposit from this:9560620395",

            "fingpayTransactionId" => "",

            "otp" => "",

            "cdPkId" => "0",

            "paymentType" => "B"





        );

        $response = $this->aeps_iciciapi($url, $values, 'J9:H5:4D:9D:0Q');

        if (isset($request->user_id)) {

            return $response;
        } else {



            //$reponse;



            if ($response['message'] == 'Message successfully sent to the entered mobile number.') {

                return view('modules.icici.onboarding', ['amount' => $amount, 'mpin' => $mpin, 'mpin' => $mpin, 'mobile' => $mobile, 'lat' => $lat, 'long' => $long, 'accountNumber' => $accountno, 'transaction_id' => $transaction_id, 'mobile' => $mobile, 'ftransid' => $response['data']['fingpayTransactionId'], 'cdPkId' => $response['data']['cdPkId']]);
            } else {

                return redirect()->back()->with('error', $response['message']);
            }
        }
    }



    //Ends Here

    public function validate_iciciotp(Request $request)
    {

        $transaction_id = $request->input('transaction_id');

        $lat = $request->input('latitude');

        $long = $request->input('longitude');

        $accountNumber = $request->input('accountNumber');

        $mobile = $request->input('mobile');

        $otp = $request->input('otp');

        $fingpayTransactionId = $request->input('fingpayTransactionId');

        $cdPkId = $request->input('cdPkId');





        if (isset($request->user_id)) {

            $user_id = $request->user_id;

            $user = User::where(['userId' => $user_id])->first();



            if ($otp == '') {

                $statusMsg = "Kindly Enter OTP";

                $success = "false";

                return $this->sendSuccess($success, $statusMsg);
            }
        } else {

            $validator = Validator::make($request->all(), [

                'otp' => 'required',

            ]);



            if ($validator->fails()) {

                return redirect()->back()->with('error', 'Please enter OTP to continue');
            }

            $user = User::where('userId', Auth::user()->userId)->first();
        }





        $url = "https://fingpayap.tapits.in/fpaepsservice/api/CashDeposit/merchant/php/validate/otp";

        $passaeps = $user->aeps_password;

        $values = array(

            "superMerchantId" => 1015,

            "merchantUserName" => $user->aeps_mid,

            //"merchantPin" => md5($user->aeps_password),

            "merchantPin" => md5($passaeps),

            "subMerchantId" => "",

            "secretKey" => "6466829c00b1e9f4325e3c5104be6bb111f77140ecb6a19307cf46cf2cf0e15e",

            "mobileNumber" => $mobile,

            "iin" => "508534",

            "transactionType" => "CDO",

            "latitude" => $lat,

            "longitude" => $long,

            "requestRemarks" => "Cash Deposit from this:9560620395",

            "merchantTranId" => $transaction_id,

            "accountNumber" => $accountNumber, //acc

            "amount" => $request->input('amount'),

            "fingpayTransactionId" => $fingpayTransactionId,

            "otp" => $otp,

            "cdPkId" => $cdPkId,

            "paymentType" => "B"

        );

        $response = $this->aeps_iciciapi($url, $values, 'J9:H5:4D:9D:0Q');

        $amount = $request->input('amount');



        if (isset($request->user_id)) {

            return $response;
        } else {

            if ($response['message'] == 'Request Completed') {

                return view('modules.icici.onboarding', ['amount' => $amount, 'otp' => $otp, 'lat' => $lat, 'long' => $long, 'accountNumber' => $accountNumber, 'transaction_id' => $transaction_id, 'mobile' => $mobile, 'beneficiaryName' => $response['data']['beneficiaryName'], 'ftransid' => $response['data']['fingpayTransactionId'], 'cdPkId' => $response['data']['cdPkId']]);

                //  $user->primaryKeyId = $response['data']['primaryKeyId'];

                //$user->encodeFPTxnId = $response['data']['encodeFPTxnId'];

                //$user->save();

                // return redirect()->route('aeps_')->with('success', $response['message']);

            } else {

                return redirect('icici/icicionboarding')->with('error', $response['message']);
            }
        }
    }

    public function validate_transaction(Request $request)
    {







        $service_id = "10"; //fetch

        $operator_id = "45"; //fetch

        $api_id = OperatorSetting::where(['operator_id' => $operator_id, 'service_id' => $service_id])->first() ?? "00";

        if (!$api_id) {

            return $this->sendError('Something went wrong');
        }

        $api_details = ApiSetting::where(['api_id' => $api_id->default_api_id, 'is_deleted' => 0, 'activated_status' => 'YES'])->first();

        if (!$api_details) {

            return $this->sendError('Something went wrong');
        }



        $old_oid =  file_get_contents("admin/admin/txn_order_id.txt");

        $sno_order_id = (int)$old_oid + 1;

        $order_id = "PM" . $sno_order_id;

        file_put_contents('admin/admin/txn_order_id.txt', $sno_order_id . "");









        $transaction_id = $request->input('transaction_id');

        $lat = $request->input('lat');

        $long = $request->input('long');

        $accountNumber = $request->input('accountNumber');

        $amount = $request->input('amount');

        $mobile = $request->input('mobile');

        $otp = $request->input('otp');

        $fingpayTransactionId = $request->input('fingpayTransactionId');

        $cdPkId = $request->input('cdPkId');

        $accountHoldername = $request->input('beneficiaryName');

        $bank_transaction_id = $request->input('bankRrn');



        if (isset($request->user_id)) {

            $user = User::where('userId', $request->user_id)->first();
        } else {

            $user = User::where('userId', Auth::user()->userId)->first();
        }

        $transaction_id = rand(00000000000000, 99999999999999);

        //$amount=1;

        $user_id = $user->userId;

        //Entry Wallet     

        //$txnTbl = $this->add_wallet_table($operator_id,$amount,$user_id,$user->roleId,$transaction_id,$order_id,$api_details->api_id,$service_id);



        $url = $api_details->api_url;

        $url = "https://fingpayap.tapits.in/fpaepsservice/api/CashDeposit/merchant/php/transaction";

        $passaeps = $user->aeps_password;

        $values = array(

            "superMerchantId" => 1015,

            "merchantUserName" => $user->aeps_mid,

            //"merchantPin" => md5($user->aeps_password),

            "merchantPin" => md5($passaeps),

            "subMerchantId" => "",

            "secretKey" => "6466829c00b1e9f4325e3c5104be6bb111f77140ecb6a19307cf46cf2cf0e15e",

            "mobileNumber" => $mobile,

            "iin" => "508534",

            "transactionType" => "CDO",

            "latitude" => $lat,

            "longitude" => $long,

            "requestRemarks" => "Cash Deposit from this:9560620395",

            "merchantTranId" => $transaction_id,

            "accountNumber" => $accountNumber, //acc

            "amount" => $amount,

            "fingpayTransactionId" => $fingpayTransactionId,

            "otp" => $otp,

            "cdPkId" => $cdPkId,

            "paymentType" => "B"

        );



        $service_id = 10;



        $superMerchantId = $user->username;

        $smartid = $order_id;

        $bankName = "ICICI";



        //Check Duplicate Transaction

        $min = "1";

        $cDate = date('Y-m-d H:i:s');

        $newtimestamp = strtotime($cDate . ' - ' . $min . ' minute');

        $cDate1 = date('Y-m-d H:i:s', $newtimestamp);

        //$che="SELECT * from tbl_transaction_dtls WHERE  trans_date >= '".$cDate1."' AND trans_date < '".$cDate."' AND bank_account_no='".$accountNumber."'";

        $check = TransactionDetail::where([

            ['trans_date', '>=', $cDate1],

            ['trans_date', '<', $cDate],

            ['bank_account_no', '=', $accountNumber], ['total_amount', '=', $amount]
        ])->count();



        if ($check > 0) {

            $response = array(

                'status' => "false",

                'msg' => "Same receipt and amount just now hit one Trasaction so Try again after 5 minute",

                'result' => ""

            );

            if (isset($request->user_id)) {

                $statusMsg = "Same receipt and amount just now hit one Trasaction so Try again after 5 minute";

                $success = "false";

                return $this->sendSuccess($success, $statusMsg);
            }



            return redirect('icici/icicionboarding')->with('error', 'Same Account No. With Same Amount just now hit one Trasaction so Try again after 1 minute');

            exit;
        }



        $success = $this->add_icici_transaction_comm($smartid, $bankName, $accountHoldername, $mobile, $superMerchantId, $accountNumber, 45, $amount, $user_id, $user->roleId, $user->parent_user_id, $transaction_id, $order_id, $api_details->api_id, 10, 'icici_cash_deposit', $bank_transaction_id);



        $response = "SUCCESS";

        $this->api_log($transaction_id, $service_id, $api_details, $order_id, $user_id, json_encode($values), json_encode($response), "cashdeposit"); //add api logs



        //Ends Here

        $response = $this->aeps_iciciapi($url, $values, 'J9:H5:4D:9D:0Q');

        //$success = $this->add_icici_transaction_comm(45,$amount,$user_id,$user->roleId,$user->parent_user_id,$transaction_id,$order_id,$api_details->api_id,10,'icici_cash_deposit');



        // print_r($response);



        if ($response) {



            $operator_id = 45;

            $service_id = 10;

            $msg = $response['message'];

            $transaction_id = $transaction_id;

            $order_id = $order_id;



            $mobile = $mobile;

            $accountNumber = $response['data']['accountNumber'];

            if ($response['status'] == "true" or $response['status'] == true) {

                $accountHoldername = $accountHoldername;

                $acno = $response['data']['accountNumber'];

                $msg = $response['message'];

                $fingpayTransactionId = $response['data']['fingpayTransactionId'];

                $accountNumber = $response['data']['accountNumber'];

                $fpRrn = $response['data']['fpRrn'];

                $beneficiaryName = $response['data']['beneficiaryName'];

                $rrn = $fpRrn;

                // $failed = $this->add_icici_failed_transaction_comm($accountNumber,$mobile,$operator_id,$amount,$user_id,$user->roleId,$transaction_id,$order_id,$api_details->api_id,$service_id);

                $success = $this->add_icici_after_transaction_comm($beneficiaryName, $fpRrn, $fingpayTransactionId, $accountNumber, $mobile, $msg, $rrn, $operator_id, $amount, $user_id, $user->roleId, $user->parent_user_id, $transaction_id, $order_id, $api_details->api_id, 10, 'icici_cash_deposit');



                $aeps_bank = file_get_contents("https://fingpayap.tapits.in/fpaepsservice/api/bankdata/bank/details");

                $aeps_bank = json_decode($aeps_bank, true);

                $bank_name = '';

                foreach ($aeps_bank['data'] as $bank) {

                    if ($bank['iinno'] == '508534') {

                        $bank_name = $bank['bankName'];
                    }
                }
            } else if ($response['status'] == "false" or $response['status'] == false or $msg == "ERR:Invalid Transaction" or $msg == "ERR:Insufficient Balance" or $msg == "Insufficient Balance") {

                $response_msg = $msg;

                $status = "FAILED";



                $accountHoldername = $accountHoldername;

                $acno = $response['data']['accountNumber'];

                $msg = $response['message'];

                $fingpayTransactionId = $response['data']['fingpayTransactionId'];

                $accountNumber = $response['data']['accountNumber'];

                $fpRrn = $response['data']['fpRrn'];

                $beneficiaryName = $response['data']['beneficiaryName'];

                $rrn = $fpRrn;

                $failed = $this->add_icici_failed_transaction_comm($beneficiaryName, $fpRrn, $fingpayTransactionId, $response_msg, $accountNumber, $mobile, $operator_id, $amount, $user_id, $user->roleId, $transaction_id, $order_id, $api_details->api_id, $service_id);



                if (isset($request->user_id)) {

                    return $response;
                } else {

                    return view('modules.icici.onboarding', ['amount' => $amount, 'orderid' => $order_id, 'acholdername' => $accountHoldername, 'acno' => $response['data']['accountNumber'], 'msg' => $response['message'], 'fingpayTransactionId' => $response['data']['fingpayTransactionId'], 'accountNumber' => $response['data']['accountNumber'], 'fpRrn' => $response['data']['fpRrn'], 'beneficiaryName' => $response['data']['beneficiaryName']]);

                    return $this->sendError($response['message']);
                }
            } else if ($msg == "Transaction is in process, please check history after 2 minutes.") {

                $status = "PENDING";
            } else {
            }



            //$add_txn = $this->add_txn($operator_id,$user_id,4,$transaction_id,$order_id,21,$service_id,$status,$mobile);

            if (isset($request->user_id)) {

                return $response;
            } else {

                return view('modules.icici.onboarding', ['amount' => $amount, 'orderid' => $order_id, 'acholdername' => $accountHoldername, 'acno' => $response['data']['accountNumber'], 'msg' => $response['message'], 'fingpayTransactionId' => $response['data']['fingpayTransactionId'], 'accountNumber' => $response['data']['accountNumber'], 'fpRrn' => $response['data']['fpRrn'], 'beneficiaryName' => $response['data']['beneficiaryName']]);
            }

            //  $user->primaryKeyId = $response['data']['primaryKeyId'];

            //$user->encodeFPTxnId = $response['data']['encodeFPTx3nId'];

            //$user->save();

            // return redirect()->route('aeps_')->with('success', $response['message']);

        } else {

            return redirect()->back()->with('error', $response['message']);
        }
    }



    public function fpbankList()
    {

        $aeps_bank = file_get_contents("https://fingpayap.tapits.in/fpaepsservice/api/bankdata/bank/details");

        $aeps_bank = json_decode($aeps_bank, true);

        return $this->sendSuccess($aeps_bank['data']);
    }



    public function aeps_transaction_api(Request $request)
    {



        $lat = $request->latitude;

        $long = $request->longitude;

        $aadharNumber = $request->aadharNumber;

        $mobile = $request->mobileNumber;

        $deviceId = '0610936'; //need to check for other biometric devices

        $service_type = $request->service_type;

        $bank_id = $request->bank_name;

        $mpin = $request->mpin;

        $amount = $request->amount ?? "0.00";

        $user_id = $request->user_id;

        $user = User::where(['userId' => $user_id, 'mpin' => $mpin])->first();



        $device_id = $request->device_id;

        if (empty($user) || !$user) {

            return "Invalid Mpin";
        }

        $newxml = '<?xml version="1.0"?>' . $request->txtPidData;



        /*To be uncommented*/



        //$captureResponse = $this->captureData("",$newxml);



        // print_r(json_encode($captureResponse));die();

        //$captureResponse = "";



        if ($service_type == 'balance_enquiry') {

            // $captureResponse = $this->captureData("",$request->input('txtPidDatabalance'));

            //  if($request->input('capturescore1') == null)

            // {

            //     return redirect()->back()->with('error', 'kindly Capture Fingerprint');

            // }

            $service_id = "11"; //fetch

            $operator_id = "46"; //fetch

            $api_id = OperatorSetting::where(['operator_id' => $operator_id, 'service_id' => $service_id])->first() ?? "00";

            if (!$api_id) {

                return redirect()->back()->with('merror', 'Something went wrong');
            }

            $api_details = ApiSetting::where(['api_id' => $api_id->default_api_id, 'is_deleted' => 0, 'activated_status' => 'YES'])->first();

            if (!$api_details) {

                return redirect()->back()->with('merror', 'Something went wrong');
            }



            $old_oid =  file_get_contents("admin/admin/txn_order_id.txt");

            $sno_order_id = (int)$old_oid + 1;

            $order_id = "PM" . $sno_order_id;

            file_put_contents('admin/admin/txn_order_id.txt', $sno_order_id . "");





            $transaction_id = rand(00000000000000, 99999999999999);

            $passaeps = $user->aeps_password;

            $url = $api_details->api_url;

            $values = array(

                "superMerchantId" => $api_details->key_id,

                "latitude" => $lat,

                "longitude" => $long,

                "mobileNumber" => $mobile,

                "languageCode" => "en",

                "paymentType" => "B",

                "transactionType" => "BE",

                "merchantUserName" => $user->aeps_mid,

                "merchantPin" => md5($passaeps),

                "merchantTransactionId" => $transaction_id,

                "cardnumberORUID" => array(

                    "adhaarNumber" => $this->isAadharValid($aadharNumber) ? $aadharNumber : "",

                    "indicatorforUID" => "0",

                    "nationalBankIdentificationNumber" => $bank_id

                ),

                //"captureResponse" => $captureResponse

            );

            //Transaction Table Entryz

            $transaction_msg = "";

            $aeps_balance = "";

            $clientreferenceid = "";

            $rrnno = "";

            $status = "PENDING";

            $bank_name = "";



            $aeps_bank = file_get_contents("https://fingpayap.tapits.in/fpaepsservice/api/bankdata/bank/details");

            $aeps_bank = json_decode($aeps_bank, true);

            $bank_name = '';

            foreach ($aeps_bank['data'] as $bank) {

                if ($bank['iinno'] == $bank_id) {

                    $bank_name = $bank['bankName'];
                }
            }

            $bankname = $bank_name;

            $txnTbl = $this->add_wallet_table($bankname, $mobile, $aadharNumber, $bank_name, $operator_id, $amount, $user_id, $user->roleId, $transaction_id, $order_id, $api_details->api_id, $service_id);





            // print_r(json_encode($values));die();

            //$response = $this->aeps_api($url,$values,'J9:H5:4D:9D:0Q');

            //test

            $response = array(

                'status' => 'true',

                'data' => array(

                    'balanceAmount' => '100.00',

                    'merchantTransactionId' => $transaction_id,

                    'requestTransactionTime' => date('d/m/Y H:i:s')

                )

            );



            //test end

            $aeps_bank = file_get_contents("https://fingpayap.tapits.in/fpaepsservice/api/bankdata/bank/details");

            $aeps_bank = json_decode($aeps_bank, true);

            $bank_name = '';

            foreach ($aeps_bank['data'] as $bank) {

                if ($bank['iinno'] == $bank_id) {

                    $bank_name = $bank['bankName'];
                }
            }





            if ($response['status'] == 'true') {

                $msg = "Current Balance - ₹ " . $response['data']['balanceAmount'];

                // $msg .= "Balance Fetched Successfully\n";

                $msg .= $bank_name;

                $msg .= "Transaction ID :- " . $response['data']['merchantTransactionId'];

                $msg .= "Date and Time :- " . $response['data']['requestTransactionTime'];

                $add_txn = $this->add_txn($operator_id, $user_id, $user->roleId, $transaction_id, $order_id, $api_details->api_id, $service_id, "SUCCESS", $mobile);

                $response = array(

                    'status' => 'true',

                    'message' => 'Balance Inquiry Successfull',



                    'data' => array(

                        'amount' => $response['data']['balanceAmount'],

                        'aadhar_number' => $aadharNumber,

                        'date_time' => $response['data']['requestTransactionTime'],

                        'order_status' => "success",

                        'client_reference_id' => 0,

                        'bank_name' => $bank_name,

                        'order_id' => $order_id,

                        'response_message' => 'Balance Inquiry Successfull'
                    )

                );

                return json_encode($response);

                //return $this->sendSuccess($msg);



            } else {

                $response = array(

                    'status' => 'false',

                    'message' => 'Balance Inquiry Failed',

                    'data' => array(

                        'amount' => 0,

                        'aadhar_number' => '',

                        'date_time' => '',

                        'order_status' => '',

                        'client_reference_id' => '',

                        'bank_name' => '',

                        'order_id' => '',

                        'response_message' => $response['message']
                    )

                );



                $add_txn = $this->add_txn($operator_id, $user_id, $user->roleId, $transaction_id, $order_id, $api_details->api_id, $service_id, "FAILED", $mobile);

                return json_encode($response);

                return $this->sendError($response['message']);
            }
        } elseif ($service_type == 'aadhar_payment') {

            // $captureResponse = $this->captureData("",$request->input('txtPidData'));

            $service_id = "9"; //fetch

            $operator_id = "44"; //fetch

            $api_id = OperatorSetting::where(['operator_id' => $operator_id, 'service_id' => $service_id])->first() ?? "00";

            if (!$api_id) {

                return redirect()->back()->with('merror', 'Something went wrong');
            }

            $api_details = ApiSetting::where(['api_id' => $api_id->default_api_id, 'is_deleted' => 0, 'activated_status' => 'YES'])->first();

            if (!$api_details) {

                return redirect()->back()->with('merror', 'Something went wrong');
            }



            $old_oid =  file_get_contents("admin/admin/txn_order_id.txt");

            $sno_order_id = (int)$old_oid + 1;

            $order_id = "PM" . $sno_order_id;

            file_put_contents('admin/admin/txn_order_id.txt', $sno_order_id . "");



            $transaction_id = "PAYMAMA" . rand(00000000000000, 99999999999999);

            //  $transaction_id = rand(00000000000000,99999999999999);

            // $txnTbl = $this->add_wallet_table($operator_id,$amount,$user_id,$user->roleId,$transaction_id,$order_id,$api_details->api_id,$service_id);

            $passaeps = $user->aeps_password;

            $url = $api_details->api_url;





            $values = array(

                "superMerchantId" => $api_details->key_id,

                "latitude" => $lat,

                "longitude" => $long,

                "mobileNumber" => $mobile,

                "languageCode" => "en",

                "paymentType" => "B",

                "transactionType" => "M",

                "transactionAmount" => $amount,

                "merchantUserName" => $user->aeps_mid,

                "merchantPin" => md5($passaeps),

                "merchantTransactionId" => $transaction_id,

                "cardnumberORUID" => array(

                    "adhaarNumber" => $this->isAadharValid($aadharNumber) ? $aadharNumber : "",

                    "indicatorforUID" => "0",

                    "nationalBankIdentificationNumber" => $bank_id

                ),

                // "captureResponse" => $captureResponse

            );



            //Update api logs

            $this->api_log($transaction_id, $service_id, $api_details, $order_id, $user_id, json_encode($values), "PENDING", "cashwithdrawal");

            //Ends





            //Check Duplicate Transaction Starts

            $min = "1";

            $cDate = date('Y-m-d H:i:s');

            $newtimestamp = strtotime($cDate . ' - ' . $min . ' minute');

            $cDate1 = date('Y-m-d H:i:s', $newtimestamp);

            //$che="SELECT * from tbl_transaction_dtls WHERE  trans_date >= '".$cDate1."' AND trans_date < '".$cDate."' AND bank_account_no='".$accountNumber."'";

            $check = TransactionDetail::where([

                ['trans_date', '>=', $cDate1],

                ['trans_date', '<', $cDate],

                ['aadharnumber', '=', $aadharNumber], ['total_amount', '=', $amount]
            ])->count();



            if ($check > 0) {

                $response = array(

                    'status' => "false",

                    'msg' => "Same receipt and amount just now hit one Trasaction so Try again after 5 minute",

                    'result' => ""

                );

                if (isset($request->user_id)) {

                    $statusMsg = "Same receipt and amount just now hit one Trasaction so Try again after 5 minute";

                    $success = "false";

                    return $this->sendSuccess($success, $statusMsg);
                }







                $msg = "Duplicate Transaction";

                return redirect()->back()->with('merror', $response['message'] ?? "Duplicate Transaction")->with('messages', $msg);

                exit;
            }

            //Check Duplicate Transaction Ends



            //Transaction Table Entryz

            $transaction_msg = "";

            $aeps_balance = "";

            $clientreferenceid = "";

            $rrnno = "";

            $status = "PENDING";

            $aeps_bank = file_get_contents("https://fingpayap.tapits.in/fpaepsservice/api/bankdata/bank/details");

            $aeps_bank = json_decode($aeps_bank, true);

            $bank_name = '';

            foreach ($aeps_bank['data'] as $bank) {

                if ($bank['iinno'] == $bank_id) {

                    $bank_name = $bank['bankName'];
                }
            }

            $bankname = $bank_name;

            $txnTbl = $this->add_wallet_table($bankname, $mobile, $aadharNumber, $bank_name, $operator_id, $amount, $user_id, $user->roleId, $transaction_id, $order_id, $api_details->api_id, $service_id);

            //         	return redirect()->back()->with('merror', $response['message'] ?? "Transaction Entry Done & Wallet Updated")->with('messages', $msg);

            // //         exit;//Ends 







            //         $msg="Transaction Entry Done";

            // 		return redirect()->back()->with('merror', $response['message'] ?? "Transaction Entry Done")->with('messages', $msg);

            //         exit;





            //Api Hit

            // $key = '';

            // $num = '';

            // $device_id='J9:H5:4D:9D:0Q';

            // $mt_rand = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15);

            // foreach ($mt_rand as $chr) {

            //     $num .= $chr;          

            //     $key .= chr($chr);         

            // }

            // $iv = '06f2f04cc530364f';

            // $ciphertext_raw = openssl_encrypt(json_encode($values), 'AES-128-CBC', $key, $options=OPENSSL_RAW_DATA, $iv);

            // $request = base64_encode($ciphertext_raw);

            // openssl_public_encrypt($key,$crypttext,$this->pub_key);

            // $concat=json_encode($values);

            // $hashedvalue=hash("sha256",$concat, true);

            // $hashedapi=hash("sha256","6466829c00b1e9f4325e3c5104be6bb111f77140ecb6a19307cf46cf2cf0e15e", true);



            // $newhashed=$hashedvalue.$hashedapi;

            // $header = [         

            //     'Content-Type: text/xml',             

            //     'trnTimestamp:'.date('d/m/Y H:i:s'),         

            //     'hash:'.base64_encode($hashedvalue),         

            //     'deviceIMEI:'.$device_id,         

            //     'eskey:'.base64_encode($crypttext)         

            // ];



            // $curl = curl_init();

            // curl_setopt_array($curl, array(

            //     CURLOPT_URL => $url,

            //     CURLOPT_RETURNTRANSFER => true,

            //     CURLOPT_SSL_VERIFYPEER => true, 

            //     CURLOPT_SSL_VERIFYHOST => 2,

            //     CURLOPT_TIMEOUT => 30,

            //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,

            //     CURLOPT_CUSTOMREQUEST => "POST",

            //     CURLOPT_POSTFIELDS => $request,

            //     CURLOPT_HTTPHEADER => $header

            // ));



            // $response = curl_exec($curl);

            // $array = json_decode($response, true);

            // $err = curl_error($curl);

            // $info = curl_getinfo($curl);

            // curl_close($curl);

            // $logfile = 'aepsresponselog.txt';

            // $log = 'URL - '.$url."\n";

            // $log .= 'RAW REQUEST - '.json_encode($values)."\n";

            // $log .= 'REQUEST - '.$request."\n";

            // $log .= 'RESPONSE - '.$response."\n\n";

            // file_put_contents($logfile, $log, FILE_APPEND | LOCK_EX);



            //     $response = $this->aeps_api($url,$values,'J9:H5:4D:9D:0Q');

            //  Api Hit End



            //Test Mode Started



            // $array='{"status":true,"message":"Request Completed","data":{"terminalId":"FAA26671","requestTransactionTime":"15/11/2021 17:50:27","transactionAmount":100,"transactionStatus":"successful","balanceAmount":100,"strMiniStatementBalance":null,"bankRRN":"131917885071","transactionType":"CW","fpTransactionId":"CWBB2196755151121175027054I","merchantTxnId":null,"errorCode":null,"errorMessage":null,"merchantTransactionId":"94591564616128","bankAccountNumber":null,"ifscCode":null,"bcName":null,"transactionTime":null,"agentId":0,"issuerBank":null,"customerAadhaarNumber":null,"customerName":null,"stan":null,"rrn":null,"uidaiAuthCode":null,"bcLocation":null,"demandSheetId":null,"mobileNumber":null,"urnId":null,"miniStatementStructureModel":null,"miniOffusStatementStructureModel":null,"miniOffusFlag":false,"transactionRemark":null,"bankName":null,"prospectNumber":null,"internalReferenceNumber":null,"biTxnType":null,"subVillageName":null,"userProfileResponseModel":null,"hindiErrorMessage":null,"loanAccNo":null,"responseCode":"00","fpkAgentId":null},"statusCode":10000}';



            //Test Mode End

            //$response=$array;







            //test

            $response = array(

                'status' => 'true',

                'data' => array(

                    'transactionStatus' => 'successful',

                    'transactionAmount' => $amount,

                    'balanceAmount' => '100.00',

                    'merchantTransactionId' => $transaction_id,

                    'requestTransactionTime' => date('d/m/Y H:i:s'),

                    'bankRRN' => '123456'

                )

            );

            //Update api logs

            $this->update_api_log($service_id, $api_details, $order_id, $user_id, json_encode($values), json_encode($response), "cashwithdrawal");

            //Ends

            if ($response['status'] == 'true' && $response['data']['transactionStatus'] == 'successful') {

                //$txnTbl = $this->add_wallet_table($operator_id,$amount,$user_id,$user->roleId,$transaction_id,$order_id,$api_details->api_id,$service_id);

                //$success = $this->add_success_comm($operator_id,$amount,$user_id,$user->roleId,$user->parent_user_id,$transaction_id,$order_id,$api_details->api_id,$service_id,$service_type);

                $aeps_bank = file_get_contents("https://fingpayap.tapits.in/fpaepsservice/api/bankdata/bank/details");

                $aeps_bank = json_decode($aeps_bank, true);

                $bank_name = '';

                foreach ($aeps_bank['data'] as $bank) {

                    if ($bank['iinno'] == $bank_id) {

                        $bank_name = $bank['bankName'];
                    }
                }

                $aadhar = $aadharNumber;

                $success = $this->add_success_comm($bank_name, $aadhar, $mobile, $operator_id, $amount, $user_id, $user->roleId, $user->parent_user_id, $transaction_id, $order_id, $api_details->api_id, $service_id, $service_type);



                $msg = "Aadhar Pay Request Processed Successfully";

                $res['amount'] = $response['data']['transactionAmount'];

                $res['aadhar_number'] = $aadharNumber;

                $res['transaction_id'] = $transaction_id;

                $res['order_id'] = $order_id;

                $res['date_time'] = $response['data']['requestTransactionTime'];

                $res['order_status'] = $response['data']['transactionStatus'];

                $res['available_balance'] = $response['data']['balanceAmount'];

                $res['client_reference_id'] = '';

                $res['rrn_number'] = $response['data']['bankRRN'];

                $res['order_id'] = $order_id;



                $res['bank_name'] = $bank_name;

                $res['response_message'] = $msg;

                $amount = $response['data']['transactionAmount'];

                $response = [

                    'status' => 'true',

                    'data' => $res,

                    'message' => $msg,

                ];

                return response()->json($response, 200);

                return $this->sendSuccess($res, $msg);
            } else {

                $msg = "Aadhar Pay Failed";

                $res['amount'] = 0;

                $res['aadhar_number'] = '';

                $res['transaction_id'] = '';

                $res['order_id'] = '';

                $res['date_time'] = '';

                $res['order_status'] = '';

                $res['available_balance'] = 0;

                $res['client_reference_id'] = '';

                $res['rrn_number'] = '';

                $res['order_id'] = '';



                $res['bank_name'] = '';

                $res['response_message'] = $response['message'];

                $amount = 0;

                $response = [

                    'status' => 'false',

                    'data' => $res,

                    'message' => $msg,

                ];

                return response()->json($response, 200);

                $success = $this->add_success_comm($operator_id, $amount, $user_id, $user->roleId, $user->parent_user_id, $transaction_id, $order_id, $api_details->api_id, $service_id, $service_type);

                //  $failed = $this->add_failed_balance($operator_id,$amount,$user_id,$user->roleId,$transaction_id,$order_id,$api_details->api_id,$service_id);

                return $this->sendError($response['message']);
            }
        } elseif ($service_type == 'cash_withdrawal') {



            $service_id = "6"; //fetch



            $operator_id = "23"; //fetch

            $api_id = OperatorSetting::where(['operator_id' => $operator_id, 'service_id' => $service_id])->first() ?? "00";

            if (!$api_id) {

                return $this->sendError('Something went wrong');
            }

            $api_details = ApiSetting::where(['api_id' => $api_id->default_api_id, 'is_deleted' => 0, 'activated_status' => 'YES'])->first();

            if (!$api_details) {

                return $this->sendError('Something went wrong');
            }



            $old_oid =  file_get_contents("admin/admin/txn_order_id.txt");

            $sno_order_id = (int)$old_oid + 1;

            $order_id = "PM" . $sno_order_id;

            file_put_contents('admin/admin/txn_order_id.txt', $sno_order_id . "");

            $transaction_id = "PAYMAMA" . rand(00000000000000, 99999999999999);



            /*To be uncommented*/

            // $this->add_wallet_table($operator_id,$amount,$user_id,$user->roleId,$transaction_id,$order_id,$api_details->api_id,$service_id);





            //Check Duplicate Transaction Starts

            $min = "1";

            $cDate = date('Y-m-d H:i:s');

            $newtimestamp = strtotime($cDate . ' - ' . $min . ' minute');

            $cDate1 = date('Y-m-d H:i:s', $newtimestamp);

            $check = TransactionDetail::where([

                ['trans_date', '>=', $cDate1],

                ['trans_date', '<', $cDate],

                ['aadharnumber', '=', $aadharNumber], ['total_amount', '=', $amount]
            ])->count();



            if ($check > 0) {

                $response = array(

                    'status' => "false",

                    'msg' => "Same receipt and amount just now hit one Trasaction so Try again after 5 minute",

                    'result' => ""

                );



                if (isset($request->user_id)) {

                    $statusMsg = "Same receipt and amount just now hit one Trasaction so Try again after 5 minute";

                    $success = "false";

                    return $this->sendSuccess($success, $statusMsg);
                }



                $msg = "Duplicate Transaction";

                return redirect()->back()->with('merror', $response['message'] ?? "Duplicate Transaction")->with('messages', $msg);

                exit;
            }

            //Check Duplicate Transaction Ends



            //Transaction Table Entryz

            $transaction_msg = "";

            $aeps_balance = "";

            $clientreferenceid = "";

            $rrnno = "";

            $status = "PENDING";

            $bank_name = "";

            //Make Wallet Table Entry

            $aeps_bank = file_get_contents("https://fingpayap.tapits.in/fpaepsservice/api/bankdata/bank/details");

            $aeps_bank = json_decode($aeps_bank, true);

            $bank_name = '';

            foreach ($aeps_bank['data'] as $bank) {

                if ($bank['iinno'] == $bank_id) {

                    $bank_name = $bank['bankName'];
                }
            }

            $bankname = $bank_name;

            $txnTbl = $this->add_wallet_table($bankname, $mobile, $aadharNumber, $bank_name, $operator_id, $amount, $user_id, $user->roleId, $transaction_id, $order_id, $api_details->api_id, $service_id);



            $url = $api_details->api_url;

            $passaeps = $user->aeps_password;

            $values = array(

                "superMerchantId" => $api_details->key_id,

                "latitude" => $lat,

                "longitude" => $long,

                "mobileNumber" => $mobile,

                "languageCode" => "en",

                "paymentType" => "B",

                "transactionType" => "CW",

                "transactionAmount" => $amount,

                "merchantUserName" => $user->aeps_mid,

                "merchantPin" => md5($passaeps),

                "merchantTranId" => $transaction_id,

                "cardnumberORUID" => array(

                    "adhaarNumber" => $this->isAadharValid($aadharNumber) ? $aadharNumber : "",

                    "indicatorforUID" => "0",

                    "nationalBankIdentificationNumber" => $bank_id

                ),

                // "captureResponse" => $captureResponse

            );



            //Update api logs

            $this->api_log($transaction_id, $service_id, $api_details, $order_id, $user_id, json_encode($values), "PENDING", "cashwithdrawal");

            //Ends





            // return $response;

            $msg = "Transaction Entry Done";



            /*To be uncommented*/

            // Api Hit

            // $key = '';

            // $num = '';

            // $device_id='J9:H5:4D:9D:0Q';

            // $mt_rand = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15);

            // foreach ($mt_rand as $chr) {

            //     $num .= $chr;          

            //     $key .= chr($chr);         

            // }

            // $iv = '06f2f04cc530364f';

            // $ciphertext_raw = openssl_encrypt(json_encode($values), 'AES-128-CBC', $key, $options=OPENSSL_RAW_DATA, $iv);

            // $request = base64_encode($ciphertext_raw);

            // openssl_public_encrypt($key,$crypttext,$this->pub_key);

            // $concat=json_encode($values);

            // $hashedvalue=hash("sha256",$concat, true);

            // $hashedapi=hash("sha256","6466829c00b1e9f4325e3c5104be6bb111f77140ecb6a19307cf46cf2cf0e15e", true);



            // $newhashed=$hashedvalue.$hashedapi;

            // $header = [         

            //     'Content-Type: text/xml',             

            //     'trnTimestamp:'.date('d/m/Y H:i:s'),         

            //     'hash:'.base64_encode($hashedvalue),         

            //     'deviceIMEI:'.$device_id,         

            //     'eskey:'.base64_encode($crypttext)         

            // ];



            // $curl = curl_init();

            // curl_setopt_array($curl, array(

            //     CURLOPT_URL => $url,

            //     CURLOPT_RETURNTRANSFER => true,

            //     CURLOPT_SSL_VERIFYPEER => true, 

            //     CURLOPT_SSL_VERIFYHOST => 2,

            //     CURLOPT_TIMEOUT => 30,

            //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,

            //     CURLOPT_CUSTOMREQUEST => "POST",

            //     CURLOPT_POSTFIELDS => $request,

            //     CURLOPT_HTTPHEADER => $header

            // ));

            // $response = curl_exec($curl);

            // $array = json_decode($response, true);

            // $err = curl_error($curl);

            // $info = curl_getinfo($curl);

            // curl_close($curl);

            // $logfile = 'aepsresponselog.txt';

            // $log = 'URL - '.$url."\n";

            // $log .= 'RAW REQUEST - '.json_encode($values)."\n";

            // $log .= 'REQUEST - '.$request."\n";

            // $log .= 'RESPONSE - '.$response."\n\n";

            // file_put_contents($logfile, $log, FILE_APPEND | LOCK_EX);



            //$response = $this->aeps_api($url,$values,'J9:H5:4D:9D:0Q');

            $response = "true";

            //Update api logs

            $this->update_api_log($service_id, $api_details, $order_id, $user_id, json_encode($values), json_encode($response), "cashwithdrawal");

            //Ends





            if ($response) {



                // if($response['status'] == 'true' && $response['data']['transactionStatus']=='successful') {

                //   $this->add_wallet_table($operator_id,$amount,$user_id,$user->roleId,$transaction_id,$order_id,$api_details->api_id,$service_id);

                $aadharNumber = str_repeat('X', strlen($aadharNumber) - 4) . substr($aadharNumber, -4);

                $aeps_bank = file_get_contents("https://fingpayap.tapits.in/fpaepsservice/api/bankdata/bank/details");

                $aeps_bank = json_decode($aeps_bank, true);

                $bank_name = '';

                foreach ($aeps_bank['data'] as $bank) {

                    if ($bank['iinno'] == $bank_id) {

                        $bank_name = $bank['bankName'];
                    }
                }





                //Distribute Commision 

                $aadhar = $aadharNumber;



                $success = $this->add_success_comm($bank_name, $aadhar, $mobile, $operator_id, $amount, $user_id, $user->roleId, $user->parent_user_id, $transaction_id, $order_id, $api_details->api_id, $service_id, $service_type);

                //End



                // /*Test Mode*/

                $transaction_amount = 100;

                $balance_amount = 100;

                $merchantTransactionId = "";

                $requestTransactionTime = "";

                $clientreferenceid = "";

                $rrnno = "";

                $bank_name = $bank_name;

                // $msg .= $bank_name."\n";

                $transactionAmount = "";

                $aadharNumber = str_repeat('X', strlen($aadharNumber) - 4) . substr($aadharNumber, -4);

                $aeps_balance = "";

                $transaction_msg = "";

                $fpTransactionId = $response['data']['fpTransactionId'];

                //Transaction Table Entry

                $update_txn = $this->update_aeps_txn($fpTransactionId, $transaction_msg, $amount, $aeps_balance, $clientreferenceid, $rrnno, $operator_id, $user_id, $user->roleId, $transaction_id, $order_id, $api_details->api_id, $service_id, "SUCCESS", $mobile, $aadharNumber);

                //Ends 

                $res = 0;

                //$amount = $response['data']['transactionAmount'];



                $msg = "Cash Withdrawal Succesfull";

                $res = array();



                /*To be uncommented*/

                // $res['amount'] = $response['data']['transactionAmount'];

                // $res['aadhar_number'] = str_repeat('X', strlen($aadharNumber) - 4) . substr($aadharNumber, -4);

                // $res['balance_amount'] =$response['data']['balanceAmount'];

                // $res['transaction_id'] =$response['data']['merchantTransactionId'];

                // $res['order_id'] = $order_id;

                // $res['date_time'] = $response['data']['requestTransactionTime'];

                // $res['order_status'] =$response['data']['transactionStatus'];

                // $res['available_balance'] =$response['data']['balanceAmount'];

                // $res['client_reference_id'] = $response['data']['fpTransactionId'];

                // $res['rrn_number'] = $response['data']['bankRRN'];

                // $res['response_message'] ="Cash Withdrawal Request Processed Successfully";



                $res['amount'] = 100;

                $res['aadhar_number'] = str_repeat('X', strlen($aadharNumber) - 4) . substr($aadharNumber, -4);

                $res['balance_amount'] = 0;

                $res['transaction_id'] = 123456;

                $res['order_id'] = $order_id;

                $res['date_time'] = '';

                $res['order_status'] = '';

                $res['available_balance'] = 0;

                $res['client_reference_id'] = '';

                $res['rrn_number'] = '';



                $res['response_message'] = "Cash Withdrawal Request Processed Successfully";



                $res['bank_name'] = '';



                $amount = '';



                $response = [

                    'status' => 'true',

                    'data' => $res,

                    'message' => $msg,

                ];

                return response()->json($response, 200);
            } else {





                //   $run=$this->api_log($service_id,$api_details,$order_id,$user_id,json_encode($values),json_encode($response),"cashwithdrawal"); //add api logs

                $msg = "Cash Withdrawal Failed";

                $res['amount'] = 0;

                $res['aadhar_number'] = '';

                $res['balance_amount'] = 0;

                $res['transaction_id'] = '';

                $res['order_id'] = $order_id;

                $res['date_time'] = '';

                $res['order_status'] = '';

                $res['available_balance'] = 0;

                $res['client_reference_id'] = '';

                $res['rrn_number'] = '';



                $res['response_message'] = $response['message'];



                $res['bank_name'] = '';



                $amount = '';

                $response = [

                    'status' => 'false',

                    'data' => $res,

                    'message' => $msg,

                ];

                return response()->json($response, 200);

                // $success = $this->add_success_comm($operator_id,$amount,$user_id,$user->roleId,$user->parent_user_id,$transaction_id,$order_id,$api_details->api_id,$service_id,$service_type);

                //  $failed = $this->add_failed_balance($operator_id,$amount,$user_id,$user->roleId,$transaction_id,$order_id,$api_details->api_id,$service_id);

                return $this->sendError($response['message']);
            }
        } elseif ($service_type == 'cash_deposit') {

            $service_id = "10"; //fetch

            $operator_id = "45"; //fetch

            $api_id = OperatorSetting::where(['operator_id' => $operator_id, 'service_id' => $service_id])->first() ?? "00";

            if (!$api_id) {

                return $this->sendError('Something went wrong');
            }

            $api_details = ApiSetting::where(['api_id' => $api_id->default_api_id, 'is_deleted' => 0, 'activated_status' => 'YES'])->first();

            if (!$api_details) {

                return $this->sendError('Something went wrong');
            }



            $old_oid =  file_get_contents("admin/admin/txn_order_id.txt");

            $sno_order_id = (int)$old_oid + 1;

            $order_id = "PM" . $sno_order_id;

            file_put_contents('admin/admin/txn_order_id.txt', $sno_order_id . "");





            $transaction_id = rand(00000000000000, 99999999999999);

            $txnTbl = $this->add_wallet_table($operator_id, $amount, $user_id, $user->roleId, $transaction_id, $order_id, $api_details->api_id, $service_id);



            $url = $api_details->api_url;

            $passaeps = $user->aeps_password;

            $values = array(

                "superMerchantId" => $api_details->key_id,

                "latitude" => $lat,

                "longitude" => $long,

                "mobileNumber" => $mobile,

                "languageCode" => "en",

                "paymentType" => "B",

                "transactionType" => "CD",

                "transactionAmount" => $amount,

                "merchantUserName" => $user->aeps_mid,

                "merchantPin" => md5($passaeps),

                "merchantTransactionId" => $transaction_id,

                "cardnumberORUID" => array(

                    "adhaarNumber" => $this->isAadharValid($aadharNumber) ? $aadharNumber : "",

                    "indicatorforUID" => "0",

                    "nationalBankIdentificationNumber" => $bank_id

                ),

                "captureResponse" => $captureResponse

            );

            // $response = $this->aeps_api($url,$values,$device_id);



            //test

            $response = array(

                'status' => 'true',

                'data' => array(

                    'transactionStatus' => 'successful',

                    'transactionAmount' => $amount,

                    'balanceAmount' => '100.00',

                    'merchantTransactionId' => $transaction_id,

                    'requestTransactionTime' => date('d/m/Y H:i:s')

                )

            );

            //test end

            $this->api_log($service_id, $api_details, $order_id, $user_id, json_encode($values), json_encode($response), "cashdeposit"); //add api logs

            if ($response['status'] == 'true' && $response['data']['transactionStatus'] == 'successful') {

                $success = $this->add_success_comm($operator_id, $amount, $user_id, $user->roleId, $user->parent_user_id, $transaction_id, $order_id, $api_details->api_id, $service_id, $service_type);

                $aeps_bank = file_get_contents("https://fingpayap.tapits.in/fpaepsservice/api/bankdata/bank/details");

                $aeps_bank = json_decode($aeps_bank, true);

                $bank_name = '';

                foreach ($aeps_bank['data'] as $bank) {

                    if ($bank['iinno'] == $bank_id) {

                        $bank_name = $bank['bankName'];
                    }
                }

                $msg = "Aadhar Pay Request Processed Successfully";

                $res['amount'] = $response['data']['transactionAmount'];

                $res['balance_amount'] = $response['data']['balanceAmount'];

                $res['transaction_id'] = $response['data']['merchantTransactionId'];

                $res['order_id'] = $order_id;

                $res['transaction_time'] = $response['data']['requestTransactionTime'];

                $res['bank_name'] = $bank_name;

                $amount = $response['data']['transactionAmount'];

                return $this->sendSuccess($res, $msg);
            } else {

                $failed = $this->add_failed_balance($operator_id, $amount, $user_id, $user->roleId, $transaction_id, $order_id, $api_details->api_id, $service_id);

                return $this->sendError($response['message']);
            }
        } elseif ($service_type == 'mini_statement') {



            $service_id = "12"; //fetch

            $operator_id = "47"; //fetch

            $api_id = OperatorSetting::where(['operator_id' => $operator_id, 'service_id' => $service_id])->first() ?? "00";

            if (!$api_id) {

                return "some not found";
            }

            $api_details = ApiSetting::where(['api_id' => $api_id->default_api_id, 'is_deleted' => 0, 'activated_status' => 'YES'])->first();

            if (!$api_details) {

                return "api not found";
            }



            $old_oid =  file_get_contents("admin/admin/txn_order_id.txt");

            $sno_order_id = (int)$old_oid + 1;

            $order_id = "PM" . $sno_order_id;

            file_put_contents('admin/admin/txn_order_id.txt', $sno_order_id . "");





            $transaction_id = rand(00000000000000, 99999999999999);



            $url = $api_details->api_url;

            $passaeps = $user->aeps_password;



            $values = array(

                "superMerchantId" => $api_details->key_id,

                "latitude" => $lat,

                "longitude" => $long,

                "mobileNumber" => $mobile,

                "languageCode" => "en",

                "paymentType" => "B",

                "transactionType" => "MS",

                "merchantUserName" => $user->aeps_mid,

                "merchantPin" => md5($passaeps),

                "merchantTransactionId" => $transaction_id,

                "cardnumberORUID" => array(

                    "adhaarNumber" => $this->isAadharValid($aadharNumber) ? $aadharNumber : "",

                    "indicatorforUID" => "0",

                    "nationalBankIdentificationNumber" => $bank_id

                ),

                "captureResponse" => $captureResponse

            );



            $response = $this->aeps_api($url, $values, 'J9:H5:4D:9D:0Q');



            // $response = '{"status":true,"message":"Request Completed","data":{"terminalId":"PMU2196755258","requestTransactionTime":"20/09/2021 14:54:05","transactionAmount":0.0,"transactionStatus":"successful","balanceAmount":-17.7,"strMiniStatementBalance":null,"bankRRN":"126314643407","transactionType":"MS","fpTransactionId":"MSBS2196755200921145405036I","merchantTxnId":null,"errorCode":null,"errorMessage":null,"merchantTransactionId":null,"bankAccountNumber":null,"ifscCode":null,"bcName":null,"transactionTime":null,"agentId":0,"issuerBank":null,"customerAadhaarNumber":null,"customerName":null,"stan":null,"rrn":null,"uidaiAuthCode":null,"bcLocation":null,"demandSheetId":null,"mobileNumber":null,"urnId":null,"miniStatementStructureModel":[{"date":"14/09","txnType":"Dr","amount":"10020.0","narration":" POS/W/2570434579 "},{"date":"14/09","txnType":"Cr","amount":"10020.0","narration":" POS/D/rom: THATI "},{"date":"07/09","txnType":"Dr","amount":"2299.0","narration":" POS/W/1250167691 "},{"date":"06/09","txnType":"Cr","amount":"499.0","narration":" POS/D/1249212217 "},{"date":"01/09","txnType":"Cr","amount":"499.0","narration":" POS/D/1244150016 "},{"date":"01/09","txnType":"Cr","amount":"798.0","narration":" POS/D/1244150010 "},{"date":"31/08","txnType":"Cr","amount":"503.0","narration":" POS/D/2432307271 "},{"date":"26/08","txnType":"Dr","amount":"1823.0","narration":" POS/W/1238209539 "},{"date":"26/08","txnType":"Cr","amount":"1823.0","narration":" POS/D/rom: THATI "}],"miniOffusStatementStructureModel":null,"miniOffusFlag":false,"transactionRemark":null,"bankName":null,"prospectNumber":null,"internalReferenceNumber":null,"biTxnType":null,"subVillageName":null,"userProfileResponseModel":null,"hindiErrorMessage":null,"loanAccNo":null,"responseCode":"00","fpkAgentId":null},"statusCode":10000}';

            // $response = json_decode($response,true);



            if ($response['status'] == 'true' && $response['data']['transactionStatus'] == 'successful') {

                $aeps_bank = file_get_contents("https://fingpayap.tapits.in/fpaepsservice/api/bankdata/bank/details");

                $aeps_bank = json_decode($aeps_bank, true);

                $bank_name = '';

                foreach ($aeps_bank['data'] as $bank) {

                    if ($bank['iinno'] == $bank_id) {

                        $bank_name = $bank['bankName'];
                    }
                }



                $add_txn = $this->add_txn($operator_id, $user_id, $user->roleId, $transaction_id, $order_id, $api_details->api_id, $service_id, "SUCCESS", $mobile);

                // $response = json_decode($response, true);

                $response['data']['bcName'] = $bank_name;







                $response['data']['bcName'] = $bank_name;

                $response['data']['client_reference_id'] = '';

                $response['data']['order_id'] = $order_id;

                $response = json_encode($response);

                $run = $this->api_log($service_id, $api_details, $order_id, $user_id, json_encode($values), json_encode($response), "ministatement");



                return $response;

                return $this->texportPDF($response, $bank_name);

                // return redirect()->back()->with('msuccess', $response['message']);

            } else {



                $response['data']['bcName'] = '';







                $response['data']['bcName'] = '';

                $response['data']['client_reference_id'] = '';

                $response['data']['order_id'] = $order_id;

                $response = json_encode($response);

                $run = $this->api_log($service_id, $api_details, $order_id, $user_id, json_encode($values), json_encode($response), "ministatement");





                $add_txn = $this->add_txn($operator_id, $user_id, $user->roleId, $transaction_id, $order_id, $api_details->api_id, $service_id, "FAILED", $mobile);

                return $response;

                $add_txn = $this->add_txn($operator_id, $user_id, $user->roleId, $transaction_id, $order_id, $api_details->api_id, $service_id, "FAILED", $mobile);

                return redirect()->back()->with('merror', $response['message'] ?? "Something went wrong!");
            }
        }
    }



    public function texportPDF($request, $bankName)

    {

        // $response,$bankname

        // $request = '{"status":true,"message":"Request Completed","data":{"terminalId":"PMU2196755258","requestTransactionTime":"20/09/2021 14:54:05","transactionAmount":0.0,"transactionStatus":"successful","balanceAmount":-17.7,"strMiniStatementBalance":null,"bankRRN":"126314643407","transactionType":"MS","fpTransactionId":"MSBS2196755200921145405036I","merchantTxnId":null,"errorCode":null,"errorMessage":null,"merchantTransactionId":null,"bankAccountNumber":null,"ifscCode":null,"bcName":null,"transactionTime":null,"agentId":0,"issuerBank":null,"customerAadhaarNumber":null,"customerName":null,"stan":null,"rrn":null,"uidaiAuthCode":null,"bcLocation":null,"demandSheetId":null,"mobileNumber":null,"urnId":null,"miniStatementStructureModel":[{"date":"14/09","txnType":"Dr","amount":"10020.0","narration":" POS/W/2570434579 "},{"date":"14/09","txnType":"Cr","amount":"10020.0","narration":" POS/D/rom: THATI "},{"date":"07/09","txnType":"Dr","amount":"2299.0","narration":" POS/W/1250167691 "},{"date":"06/09","txnType":"Cr","amount":"499.0","narration":" POS/D/1249212217 "},{"date":"01/09","txnType":"Cr","amount":"499.0","narration":" POS/D/1244150016 "},{"date":"01/09","txnType":"Cr","amount":"798.0","narration":" POS/D/1244150010 "},{"date":"31/08","txnType":"Cr","amount":"503.0","narration":" POS/D/2432307271 "},{"date":"26/08","txnType":"Dr","amount":"1823.0","narration":" POS/W/1238209539 "},{"date":"26/08","txnType":"Cr","amount":"1823.0","narration":" POS/D/rom: THATI "}],"miniOffusStatementStructureModel":null,"miniOffusFlag":false,"transactionRemark":null,"bankName":null,"prospectNumber":null,"internalReferenceNumber":null,"biTxnType":null,"subVillageName":null,"userProfileResponseModel":null,"hindiErrorMessage":null,"loanAccNo":null,"responseCode":"00","fpkAgentId":null},"statusCode":10000}';

        // $request = json_decode($request,true);



        $resdata = $request['data']['miniStatementStructureModel'];

        $balance = $request['data']['balanceAmount'];

        $date = $request['data']['requestTransactionTime'];

        $img = 'https://paymamaapp.in/template_assets/assets/images/logos/logo-light-text.png';

        $fileName = 'ministatement';

        // $bankName = 'Kotak Mahindra Bank';

        $tableHead = array('Sr no', 'Date', 'Type', 'Amount', 'Narration');

        $tableBody = $resdata;



        $footer = array(

            'Bank Name' => $bankName,

            'Current Account Balance' => 'Rs. ' . $balance,

            'Statement Generated On' => $date,

            'Merchant Name' => Auth::user()->store_name ?? ""

        );

        $pdf = PDF::loadView('export.mini_statement', compact('fileName', 'img', 'tableHead', 'tableBody', 'footer'));

        $pdf->setPaper('a4');

        return $pdf->download($fileName . '.pdf');

        // return $response;

        // return $pdf->stream();

    }



    public function onboarding()
    {

        $serviceTypeAlias = Config::get('constants.SERVICE_TYPE_ALIAS.AEPS');

        $servicesTypes = ServicesType::where(['is_deleted' => Config::get('constants.NOT-DELETED'), 'alias' => $serviceTypeAlias, 'activated_status' => 'YES'])->count();

        if ($servicesTypes < 1) {

            return back()->with('error', 'AEPS is deactivated');
        }

        $user = User::where('userId', Auth::user()->userId)->first();

        if ($user->aeps_kyc == Config::get('constants.DELETED')) {

            return redirect()->route('aeps');
        } else {

            $aeps_state = file_get_contents('https://fingpayap.tapits.in/fpaepsweb/api/onboarding/getstates');



            return view('modules.aeps.onboarding', compact('user', 'aeps_state'));
        }
    }

    public function uatonboarding()
    {

        $serviceTypeAlias = Config::get('constants.SERVICE_TYPE_ALIAS.AEPS');

        $servicesTypes = ServicesType::where(['is_deleted' => Config::get('constants.NOT-DELETED'), 'alias' => $serviceTypeAlias, 'activated_status' => 'YES'])->count();

        if ($servicesTypes < 1) {

            return back()->with('error', 'AEPS is deactivated');
        }

        $user = User::where('userId', Auth::user()->userId)->first();

        if ($user->aeps_kyc == Config::get('constants.DELETED')) {

            return redirect()->route('aeps');
        } else {

            $aeps_state = file_get_contents('https://fingpayap.tapits.in/fpaepsweb/api/onboarding/getstates');

            return view('modules.aeps.uatonboarding', compact('user', 'aeps_state'));
        }
    }



    //ICICI Onboarding

    public function icicionboarding()
    {



        $aeps_state = file_get_contents('https://fingpayap.tapits.in/fpaepsweb/api/onboarding/getstates');

        return view('modules.icici.onboarding', compact('aeps_state'));
    }



    //ICICI Onboarding ends

    public function getcity(Request $request)



    {



        $state = $request->state;



        $city = City::where('state_id', $state)->get();



        $value = array();







        foreach ($city as $values) {







            $value[] = $values['city_id'] . "-" . $values['city_name'];
        }



        return $value;
    }

    public function aeps_onboarding(Request $request)
    {



        // $validator = Validator::make($request->all(), [

        //     'latitude' => 'required',

        //     'longitude' => 'required',

        //     'aadharNumber' => 'required',

        //     'mobileNumber' => 'required',

        //     'name' => 'required',

        //     'pincode' => 'required',

        //     'city' => 'required',

        //     'email' => 'required',

        //     'pan' => 'required',

        //     'district' => 'required',

        //     'address' => 'required',

        //     'state_id' => 'required'

        // ]);



        // if ($validator->fails()) {

        //     return redirect()->back()->with('error', 'Required fields cannot be empty');

        // }



        $lat = $request->input('latitude');

        $long = $request->input('longitude');

        $aadharNumber = $request->input('aadharNumber');

        $mobile = $request->input('mobileNumber');

        $deviceId = '0610936'; //need to check for other biometric devices

        $name = $request->input('name');

        $pincode = $request->input('pincode');

        $city = $request->input('city');

        $email = $request->input('email');

        $pan = $request->input('pan');

        $values = '';

        if (isset($request->user_id)) {

            $user_id = $request->user_id;

            $user = User::where(['userId' => $user_id])->first();
        } else {

            $user = User::where('userId', Auth::user()->userId)->first();
        }

        if ($user->aeps_mid != "" && $user->aeps_password != "") {

            $process = 'true';

            $merchantLoginId = $user->aeps_mid;

            $password = $user->aeps_password;
        } else {

            $password = random_int(100000, 999999);

            $passaeps = $user->aeps_password;

            $merData = array(

                "merchantName" => $name,

                "merchantLoginId" => $user->username,

                "merchantLoginPin" => $password,

                "merchantPhoneNumber" => $mobile,

                "companyLegalName" => $user->store_name,

                "companyMarketingName" => $user->store_name,

                "merchantBranch" => "XXX", //need to understand

                "emailId" => $email,

                "merchantPinCode" => $pincode,

                "merchantCityName" => $city,

                "merchantDistrictName" => $request->input('district'),

                // "cancellationCheckImages" => $_POST['cheque'] ?? "",

                // "shopAndPanImage" => "",

                // "ekycDocuments" => "",

                "merchantAddress" => array(

                    "merchantAddress" => $request->input('address'),

                    "merchantState" => $request->input('state_id')

                ),

                "kyc" => array(

                    "userPan" => $pan,

                    "aadhaarNumber" => $this->isAadharValid($aadharNumber) ?? "",

                ),

            );



            $values = array(

                "username" => $this->common('username'),

                "password" => md5($this->common('password')),

                // "password" => "e8dcb850c71e3dd4a1052bb4b1d3caca",

                "latitude" => $lat, //$latitude,

                "longitude" => $long, //$longitude,

                "superMerchantId" => 1015,

                "merchants" => array($merData)

            );





            $url = "https://fingpayap.tapits.in/fpaepsweb/api/onboarding/merchant/creation/php/m1";

            $response = $this->aeps_api($url, $values, 'J9:H5:4D:9D:0Q');


            if ($response['status'] == 'true' && ($response['data']['merchants'][0]['status'] = 'Successfully Created' || $response['data']['merchants'][0]['status'] = 'Successfully Updated')) {

                $process = 'true';
            } else {

                $process = 'false';
            }
        }



        if ($process == 'true') {

            Session::put('aeps_mobile', $mobile);

            Session::put('aeps_aadhar', $aadharNumber);

            Session::put('aeps_pan', $pan);

            $url1 = "https://fpekyc.tapits.in/fpekyc/api/ekyc/merchant/php/sendotp";

            $values1 = array(

                "superMerchantId" => $this->common('supermid'),

                "merchantLoginId" => $user->username,

                "transactionType" => "EKY",

                "mobileNumber" => $mobile,

                "aadharNumber" => $aadharNumber,

                "panNumber" => $pan,

                // "matmSerialNumber" => "",

                "latitude" => $lat,

                "longitude" => $long

            );



            $response = $this->aeps_api($url1, $values1, 'J9:H5:4D:9D:0Q');







            // print_r($response);die();

            if ($response['status'] == 'true' && $response['message'] == 'Request Completed') {

                $user->aeps_mid = $user->username;

                $user->aeps_password = $password;

                $user->primaryKeyId = $response['data']['primaryKeyId'];

                $user->encodeFPTxnId = $response['data']['encodeFPTxnId'];

                $user->aeps_kyc = '0';

                $user->save();

                if (isset($request->user_id)) {

                    return $response;
                }

                return redirect()->route('aeps_otp')->with('success', 'We have sent an OTP to your mobile number.');
            } else {

                if (isset($request->user_id)) {

                    return $response;
                }

                return redirect()->back()->with('error', $response['message']);
            }
        } else {

            if (isset($request->user_id)) {

                return $response;
            }

            return redirect()->back()->with('error', $response['message']);
        }
    }



    public function aeps_otp()
    {

       
            return view('modules.aeps.otp');
       

    }



    public function resend_otp(Request $request)
    {

        $user = User::where('userId', Auth::user()->userId)->first();

        $url = "https://fpekyc.tapits.in/fpekyc/api/ekyc/merchant/php/resendotp";

        $values = array(

            "superMerchantId" => $this->common('supermid'),

            "merchantLoginId" => $user->username,

            "primaryKeyId" => $user->primaryKeyId,

            "encodeFPTxnId" => $user->encodeFPTxnId

        );



        $response = $this->aeps_api($url, $values, 'J9:H5:4D:9D:0Q');

        if ($response['status'] == 'true' && $response['message'] == 'Request Completed') {

            $user->primaryKeyId = $response['data']['primaryKeyId'];

            $user->encodeFPTxnId = $response['data']['encodeFPTxnId'];

            $user->aeps_kyc = '0';

            $user->save();

            return redirect()->route('aeps_otp')->with('success', 'We have resend an OTP to your mobile number.');
        } else {

            return redirect()->back()->with('error', $response['message']);
        }
    }



    public function resend_icici_otp(Request $request)
    {

        $user = User::where('userId', Auth::user()->userId)->first();

        $url = "https://fpekyc.tapits.in/fpekyc/api/ekyc/merchant/php/resendotp";

        $values = array(

            "superMerchantId" => $this->common('supermid'),

            "merchantLoginId" => $user->username,

            "primaryKeyId" => $user->primaryKeyId,

            "encodeFPTxnId" => $user->encodeFPTxnId

        );



        $response = $this->aeps_api($url, $values, 'J9:H5:4D:9D:0Q');

        if ($response['status'] == 'true' && $response['message'] == 'Request Completed') {

            $user->primaryKeyId = $response['data']['primaryKeyId'];

            $user->encodeFPTxnId = $response['data']['encodeFPTxnId'];

            $user->aeps_kyc = '0';

            $user->save();

            return redirect()->route('aeps_otp')->with('success', 'We have resend an OTP to your mobile number.');
        } else {

            return redirect()->back()->with('error', $response['message']);
        }
    }



    public function validate_otp(Request $request)
    {



        if (!isset($request->user_id)) {

            $validator = Validator::make($request->all(), [

                'otp' => 'required',

            ]);



            if ($validator->fails()) {

                return redirect()->back()->with('error', 'Please enter OTP to continue');
            }

            $user = User::where('userId', Auth::user()->userId)->first();
        } else {

            if ($request->input('otp') == '') {

                $statusMsg = "Kindly Enter OTP";

                $success = "false";

                return $this->sendSuccess($success, $statusMsg);
            }

            $user = User::where('userId', $request->user_id)->first();
        }

        $url = "https://fpekyc.tapits.in/fpekyc/api/ekyc/merchant/php/validateotp";

        $values = array(

            "superMerchantId" => 1015,

            "merchantLoginId" => $user->aeps_mid,

            "otp" => $request->input('otp'),

            "primaryKeyId" => $user->primaryKeyId,

            "encodeFPTxnId" => $user->encodeFPTxnId

        );



        $response = $this->aeps_api($url, $values, 'J9:H5:4D:9D:0Q');





        if ($response['status'] == true || $response['message'] == 'Request Completed') {

            $user->primaryKeyId = $response['data']['primaryKeyId'];

            $user->encodeFPTxnId = $response['data']['encodeFPTxnId'];

            $user->save();

            if (isset($request->user_id)) {

                return $response;
            }

            return redirect()->route('aeps_ekyc')->with('success', $response['message']);
        } else {

            // return $response;

            return redirect()->back()->with('error', $response['message']);
        }
    }





    public function aeps_ekyc()
    {

        if (Session::has('success') || Session::has('aeps_aadhar')) {

            return view('modules.aeps.ekyc');
        }

        // return view('modules.aeps.ekyc');

        return abort(404);
    }



    public function complete_kyc(Request $request)
    {

        // print_r($request->all());exit;

        if (!isset($request->user_id)) {

            $validator = Validator::make($request->all(), [

                'txtPidData' => 'required',

                'PidOptions' => 'required',

            ]);



            if ($validator->fails()) {

                return redirect()->back()->with('error', 'Please enter OTP to continue');
            }
        }

        if (isset($request->user_id)) {

            $aadharwill = $request->aadharNumber;

            $user = User::where('userId', $request->user_id)->first();
        } else {

            $aadharwill = Session::get('aeps_aadhar');

            $user = User::where('userId', Auth::user()->userId)->first();
        }



        $url = "https://fpekyc.tapits.in/fpekyc/api/ekyc/merchant/php/biometric";

        $newxml = '<?xml version="1.0"?>' . $request->txtPidData;

        //$captureResponse = $this->captureData("ekyc",$newxml);

        $captureResponse = $this->captureData("ekyc", $request->input('txtPidData'));



        if (isset($request->user_id)) {

            $values = array(

                "superMerchantId" => $this->common('supermid'),

                "merchantLoginId" => $user->aeps_mid,

                "primaryKeyId" => $user->primaryKeyId,

                "encodeFPTxnId" => $user->encodeFPTxnId,

                "requestRemarks" => "",

                "cardnumberORUID" => array("adhaarNumber" => $request->aadharNumber, "indicatorforUID" => "0", "nationalBankIdentificationNumber" => null),

                "captureResponse" => $captureResponse

            );
        } else {

            $values = array(

                "superMerchantId" => $this->common('supermid'),

                "merchantLoginId" => $user->aeps_mid,

                "primaryKeyId" => $user->primaryKeyId,

                "encodeFPTxnId" => $user->encodeFPTxnId,

                "requestRemarks" => "",

                "cardnumberORUID" => array("adhaarNumber" => Session::get('aeps_aadhar'), "indicatorforUID" => "0", "nationalBankIdentificationNumber" => null),

                "captureResponse" => $captureResponse

            );
        }



        $response = $this->aeps_api($url, $values, 'J9:H5:4D:9D:0Q');



        if (isset($request->user_id)) {

            return $response;
        }



        if (!$response['status'] || $response['message'] == "Ekyc already Verified") {

            Session::forget('aeps_mobile');

            Session::forget('aeps_aadhar');

            Session::forget('aeps_pan');

            $user->aeps_kyc = 1;

            $user->save();

            return redirect()->route('aeps')->with('success', $response['message']);
        } else {

            return redirect()->back()->with('error', $response['message']);
        }
    }

    public function aeps_iciciapi($url, $value, $device_id)
    {



        $key = '';

        $num = '';

        $mt_rand = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15);

        foreach ($mt_rand as $chr) {

            $num .= $chr;

            $key .= chr($chr);
        }

        $iv = '06f2f04cc530364f';

        $ciphertext_raw = openssl_encrypt(json_encode($value), 'AES-128-CBC', $key, $options = OPENSSL_RAW_DATA, $iv);

        $request = base64_encode($ciphertext_raw);



        openssl_public_encrypt($key, $crypttext, $this->pub_key);

        $concat = json_encode($value) . "6466829c00b1e9f4325e3c5104be6bb111f77140ecb6a19307cf46cf2cf0e15e";

        $hashedvalue = hash("sha256", $concat, true);

        $hashedapi = hash("sha256", "6466829c00b1e9f4325e3c5104be6bb111f77140ecb6a19307cf46cf2cf0e15e", true);



        $newhashed = $hashedvalue . $hashedapi;

        $header = [

            'Content-Type: text/xml',

            'trnTimestamp:' . date('d/m/Y H:i:s'),

            'hash:' . base64_encode($hashedvalue),

            'deviceIMEI:' . $device_id,

            'eskey:' . base64_encode($crypttext)

        ];



        $curl = curl_init();

        curl_setopt_array($curl, array(

            CURLOPT_URL => $url,

            CURLOPT_RETURNTRANSFER => true,

            CURLOPT_SSL_VERIFYPEER => true,

            CURLOPT_SSL_VERIFYHOST => 2,

            CURLOPT_TIMEOUT => 30,

            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,

            CURLOPT_CUSTOMREQUEST => "POST",

            CURLOPT_POSTFIELDS => $request,

            CURLOPT_HTTPHEADER => $header

        ));

        $response = curl_exec($curl);

        $array = json_decode($response, true);

        $err = curl_error($curl);

        $info = curl_getinfo($curl);

        curl_close($curl);

        $logfile = 'aepsresponselog.txt';

        $log = 'URL - ' . $url . "\n";

        $log .= 'RAW REQUEST - ' . json_encode($value) . "\n";

        $log .= 'REQUEST - ' . $request . "\n";

        $log .= 'RESPONSE - ' . $response . "\n\n";

        file_put_contents($logfile, $log, FILE_APPEND | LOCK_EX);

        return $array;
    }

    public function aeps_api($url, $value, $device_id)
    {



        $key = '';

        $num = '';

        $mt_rand = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15);

        foreach ($mt_rand as $chr) {

            $num .= $chr;

            $key .= chr($chr);
        }

        $iv = '06f2f04cc530364f';

        $ciphertext_raw = openssl_encrypt(json_encode($value), 'AES-128-CBC', $key, $options = OPENSSL_RAW_DATA, $iv);

        $request = base64_encode($ciphertext_raw);

        openssl_public_encrypt($key, $crypttext, $this->pub_key);

        $concat = json_encode($value);

        $hashedvalue = hash("sha256", $concat, true);

        $hashedapi = hash("sha256", "6466829c00b1e9f4325e3c5104be6bb111f77140ecb6a19307cf46cf2cf0e15e", true);



        $newhashed = $hashedvalue . $hashedapi;

        $header = [

            'Content-Type: text/xml',

            'trnTimestamp:' . date('d/m/Y H:i:s'),

            'hash:' . base64_encode($hashedvalue),

            'deviceIMEI:' . $device_id,

            'eskey:' . base64_encode($crypttext)

        ];



        $curl = curl_init();

        curl_setopt_array($curl, array(

            CURLOPT_URL => $url,

            CURLOPT_RETURNTRANSFER => true,

            CURLOPT_SSL_VERIFYPEER => true,

            CURLOPT_SSL_VERIFYHOST => 2,

            CURLOPT_TIMEOUT => 30,

            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,

            CURLOPT_CUSTOMREQUEST => "POST",

            CURLOPT_POSTFIELDS => $request,

            CURLOPT_HTTPHEADER => $header

        ));

        $response = curl_exec($curl);

        $array = json_decode($response, true);

        $err = curl_error($curl);

        $info = curl_getinfo($curl);

        curl_close($curl);

        $logfile = 'aepsresponselog.txt';

        $log = 'URL - ' . $url . "\n";

        $log .= 'RAW REQUEST - ' . json_encode($value) . "\n";

        $log .= 'REQUEST - ' . $request . "\n";

        $log .= 'RESPONSE - ' . $response . "\n\n";

        file_put_contents($logfile, $log, FILE_APPEND | LOCK_EX);

        return $array;
    }



    public function isAadharValid($num)
    {

        settype($num, "string");

        $expectedDigit = substr($num, -1);

        $actualDigit = $this->CheckAadharDigit(substr($num, 0, -1));

        return ($expectedDigit == $actualDigit) ? $expectedDigit == $actualDigit : 0;
    }



    public function CheckAadharDigit($partial)
    {



        $dihedral = array(

            array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9),

            array(1, 2, 3, 4, 0, 6, 7, 8, 9, 5),

            array(2, 3, 4, 0, 1, 7, 8, 9, 5, 6),

            array(3, 4, 0, 1, 2, 8, 9, 5, 6, 7),

            array(4, 0, 1, 2, 3, 9, 5, 6, 7, 8),

            array(5, 9, 8, 7, 6, 0, 4, 3, 2, 1),

            array(6, 5, 9, 8, 7, 1, 0, 4, 3, 2),

            array(7, 6, 5, 9, 8, 2, 1, 0, 4, 3),

            array(8, 7, 6, 5, 9, 3, 2, 1, 0, 4),

            array(9, 8, 7, 6, 5, 4, 3, 2, 1, 0)

        );

        $permutation = array(

            array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9),

            array(1, 5, 7, 6, 2, 8, 3, 0, 9, 4),

            array(5, 8, 0, 3, 7, 9, 6, 1, 4, 2),

            array(8, 9, 1, 6, 0, 4, 3, 5, 2, 7),

            array(9, 4, 5, 3, 1, 2, 6, 8, 7, 0),

            array(4, 2, 8, 6, 5, 7, 3, 9, 0, 1),

            array(2, 7, 9, 3, 8, 0, 6, 4, 1, 5),

            array(7, 0, 4, 6, 9, 1, 3, 2, 5, 8)

        );



        $inverse = array(0, 4, 3, 2, 1, 5, 6, 7, 8, 9);



        settype($partial, "string");

        $partial = strrev($partial);

        $digitIndex = 0;

        for ($i = 0; $i < strlen($partial); $i++) {

            $digitIndex = $dihedral[$digitIndex][$permutation[($i + 1) % 8][$partial[$i]]];
        }

        return $inverse[$digitIndex];
    }



    public function generate_hash($json, $input)
    {

        $key = '';

        $mt_rand = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15);

        foreach ($mt_rand as $chr) {

            $key .= chr($chr);
        }

        $iv =   '06f2f04cc530364f';

        $ciphertext_raw = openssl_encrypt($json, 'AES-128-CBC', $key, $options = OPENSSL_RAW_DATA, $iv);

        $request = base64_encode($ciphertext_raw);

        // $fp = fopen("fingpay_public_production.cer","r");

        // $pub_key_string = fread($fp,8192);

        // fclose($fp);

        openssl_public_encrypt($key, $crypttext, $this->pub_key);

        if ($input == "request") {

            return $request;
        } else {

            return base64_encode($crypttext);
        }
    }



    public function common($fetch = null)
    {

        $username = "smartpaytechd"; //username of super merchant

        $password = "1234d"; //password of super merchant

        $supermid = "1015"; //super merchant id

        $submid = "SUB1234"; //sub merchant id

        if ($fetch == "username") {

            return $username;
        } elseif ($fetch == "password") {

            return $password;
        } elseif ($fetch == "supermid") {

            return $supermid;
        } elseif ($fetch == "submid") {

            return $supermid;
        }
    }



    public function captureData($type = "", $captureResponse)
    {



        if ($type == "ekyc") {

            $PidOptions = '<?xml version="1.0"?> <PidOptions ver="1.0"><Opts env="P" fCount="1" fType="1" iCount="0" format="0" pidVer="2.0" timeout="15000" wadh="E0jzJ/P8UopUHAieZn8CKqS4WPMi5ZSYXgfnlfkWjrc=" posh="UNKNOWN" /></PidOptions>';
        } else {

            $PidOptions = '<?xml version=\"1.0\"?> <PidOptions ver=\"1.0\"><Opts env=\"P\" fCount=\"1\" fType=\"0\" iCount=\"0\" format=\"0\" pidVer=\"2.0\" timeout=\"15000\" posh=\"UNKNOWN\"  /></PidOptions>';
        }



        $xmlCaptureResponse = simplexml_load_string($captureResponse);



        $piData = $xmlCaptureResponse->Data;

        $Hmac = $xmlCaptureResponse->Hmac;

        $Skey = $xmlCaptureResponse->Skey;



        preg_match_all('#iCount=([^\s]+)#', $PidOptions, $matches);
        $iCount = str_replace('"', "", $matches[1][0]);

        preg_match_all('#errInfo=([^\s]+)#', $PidOptions, $matches);
        $pCount = '0';

        // $pType=str_replace('"',"",$matches[1][0]);

        preg_match_all('#errCode=([^\s]+)#', $captureResponse, $matches);
        $pType = '0';
        $errCode = str_replace('"', "", $matches[1][0]);

        preg_match_all('#errInfo=([^\s]+)#', $captureResponse, $matches);
        $errInfo = str_replace('"', "", $matches[1][0]);

        preg_match_all('#fCount=([^\s]+)#', $captureResponse, $matches);
        $fCount = str_replace('"', "", $matches[1][0]);

        preg_match_all('#fType=([^\s]+)#', $captureResponse, $matches);
        $fType = str_replace('"', "", $matches[1][0]);

        preg_match_all('#mi=([^\s]+)#', $captureResponse, $matches);
        $mi = str_replace('"', "", $matches[1][0]);

        preg_match_all('#rdsId=([^\s]+)#', $captureResponse, $matches);
        $rdsId = str_replace('"', "", $matches[1][0]);

        preg_match_all('#rdsVer=([^\s]+)#', $captureResponse, $matches);
        $rdsVerold = str_replace('"', "", $matches[1][0]);
        $rdsVer = str_replace('>', "", $rdsVerold);

        preg_match_all('#nmPoints=([^\s]+)#', $captureResponse, $matches);
        $nmPoints = str_replace('"', "", $matches[1][0]);

        preg_match_all('#qScore=([^\s]+)#', $captureResponse, $matches);
        $qScoreold = str_replace('"', "", $matches[1][0]);
        $qScore = str_replace('\/>', "", $qScoreold);



        preg_match_all('#dpId=([^\s]+)#', $captureResponse, $matches);
        $dpId = str_replace('"', "", $matches[1][0]);

        preg_match_all('#mc=([^\s]+)#', $captureResponse, $matches);
        $mc = str_replace('"', "", $matches[1][0]);

        preg_match_all('#dc=([^\s]+)#', $captureResponse, $matches);
        $dcc = str_replace('"', "", $matches[1][0]);
        $dc = str_replace('>', '', $dcc);

        preg_match_all('#ci=([^\s]+)#', $captureResponse, $matches);
        $cida = str_replace('"', "", $matches[1][0]);



        preg_match_all('#type=([^\s]+)#', $captureResponse, $matches);
        $PidData = str_replace('"', "", $matches[1][0]);

        $x = explode(">", $PidData);

        $PidDatatype = $x[0];



        $cidata = explode(">", $cida);

        $ci = $cidata[0];

        preg_match_all('#type="X">([^\s]+)#', $captureResponse, $matches);
        $Piddata = str_replace('"', "", $matches[1][0]);

        $values = array(

            "errCode" => $errCode,

            "errInfo" => $errInfo,

            "fType" => $fType,

            "fCount" => $fCount,

            "iCount" => $iCount,

            "pCount" => $pCount,

            "pType" => $pType,

            "nmPoints" => "$nmPoints",

            "qScore" => "$qScore",

            "dpID" => $dpId,

            "rdsID" => $rdsId,

            "rdsVer" => $rdsVer,

            "dc" => "$dc",

            "mc" => "$mc",

            "mi" => $mi,

            "ci" => $ci,

            "sessionKey" => "$Skey",

            "hmac" => "$Hmac",

            "PidDatatype" => $PidDatatype,

            "Piddata" => "$piData",

        );

        return $values;
    }



    public function add_txn($operator_id, $user_id, $role_id, $transaction_id, $order_id, $api_id, $service_id, $status, $mobile)
    {

        $source = 'WEB';

        if (strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'mobile') || strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'android') || strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'okhttp')) {

            $source = 'MOBILE';
        }

        $trans_info = array(

            'transaction_id' => $transaction_id,

            'transaction_status' => $status,

            'service_id' => $service_id,

            'operator_id' => $operator_id,

            'api_id' => $api_id, //change api id

            'trans_date' => date("Y-m-d H:i:s"),

            'order_id' => $this->isValid($order_id),

            'mobileno' => $mobile . "",

            'user_id' => $this->isValid($user_id),

            'total_amount' => "0",

            'charge_amount' => "0",

            'transaction_type' => "",

            // 'bank_transaction_id' => $data['bank_transaction_id'],

            // 'bill_orderId' => $data['merchant_order_id'],

            // 'recipient_id' => $data['recipient_id'], //add

            'charges_tax' => "0", //add

            'commission' => "0", //add

            'commission_tax' => "0", //add

            'commission_tds' => "0", //add

            'debit_amount' => "0",

            'balance' => "0",

            'order_status' => $status,

            'transaction_msg' => "",

            'CCFcharges' => "0",

            'Cashback' => "0",

            'TDSamount' => "0",

            'PayableCharge' => "0",

            'FinalAmount' => "0",

            'request_amount' => "0",

            'updated_on' => date('Y-m-d H:i:s'),

            'ip_address' => $this->getRealIpAddr(),

            'source' => $source

        );

        $txn_id = TransactionDetail::insert($trans_info);

        if ($txn_id) {

            return true;
        } else {

            return false;
        }
    }

    public function add_aeps_txn($transaction_msg, $amount, $aeps_balance, $clientreferenceid, $rrnno, $operator_id, $user_id, $role_id, $transaction_id, $order_id, $api_id, $service_id, $status, $mobile, $aadharnumber)
    {

        $source = 'WEB';

        if (strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'mobile') || strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'android') || strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'okhttp')) {

            $source = 'MOBILE';
        }

        $trans_info = array(

            'transaction_id' => $transaction_id,

            'transaction_status' => $status,

            'service_id' => $service_id,

            'operator_id' => $operator_id,

            'api_id' => $api_id, //change api id

            'trans_date' => date("Y-m-d H:i:s"),

            'order_id' => $this->isValid($order_id),

            'mobileno' => $mobile . "",

            'user_id' => $this->isValid($user_id),

            'total_amount' => "0",

            'charge_amount' => "0",

            'transaction_type' => "",

            // 'bank_transaction_id' => $data['bank_transaction_id'],

            // 'bill_orderId' => $data['merchant_order_id'],

            // 'recipient_id' => $data['recipient_id'], //add

            'charges_tax' => "0", //add

            'commission' => "0", //add

            'commission_tax' => "0", //add

            'commission_tds' => "0", //add

            'debit_amount' => "0",

            'balance' => "0",

            'order_status' => $status,

            'transaction_msg' => "",

            'CCFcharges' => "0",

            'Cashback' => "0",

            'TDSamount' => "0",

            'PayableCharge' => "0",

            'FinalAmount' => "0",

            'request_amount' => "0",

            'updated_on' => date('Y-m-d H:i:s'),

            'ip_address' => $this->getRealIpAddr(),

            'source' => $source,

            'aadharnumber' => $aadharnumber,

            //'client_reference_id'=>$clientreferenceid,

            //'rrnno'=>$rrnno,

            // 'aeps_balance'=>$aeps_balance,

            //'total_amount'=>$amount,

            //'transaction_msg'=>$transaction_msg

        );

        $txn_id = TransactionDetail::insert($trans_info);

        if ($txn_id) {

            return true;
        } else {

            return false;
        }
    }



    public function update_aeps_txn($fpTransactionId, $transaction_msg, $amount, $aeps_balance, $clientreferenceid, $rrnno, $operator_id, $user_id, $role_id, $transaction_id, $order_id, $api_id, $service_id, $status, $mobile, $aadharnumber)
    {

        $source = 'WEB';

        if (strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'mobile') || strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'android') || strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'okhttp')) {

            $source = 'MOBILE';
        }

        $trans_info = array(

            'transaction_id' => $transaction_id,

            'transaction_status' => $status,

            'service_id' => $service_id,

            'operator_id' => $operator_id,

            'api_id' => $api_id, //change api id

            'trans_date' => date("Y-m-d H:i:s"),

            'order_id' => $this->isValid($order_id),

            'mobileno' => $mobile . "",

            'user_id' => $this->isValid($user_id),

            'total_amount' => "0",

            'charge_amount' => "0",

            'transaction_type' => "",

            // 'bank_transaction_id' => $data['bank_transaction_id'],

            // 'bill_orderId' => $data['merchant_order_id'],

            // 'recipient_id' => $data['recipient_id'], //add

            'charges_tax' => "0", //add

            'commission' => "0", //add

            'commission_tax' => "0", //add

            'commission_tds' => "0", //add

            'debit_amount' => "0",

            'balance' => "0",

            'order_status' => $status,

            'transaction_msg' => "$transaction_msg",

            'response_msg' => "$transaction_msg",

            'CCFcharges' => "0",

            'Cashback' => "0",

            'TDSamount' => "0",

            'PayableCharge' => "0",

            'FinalAmount' => "0",

            'request_amount' => "0",

            'updated_on' => date('Y-m-d H:i:s'),

            'client_reference_id' => $clientreferenceid,

            'rrnno' => $rrnno,

            'aeps_balance' => $aeps_balance,

            'total_amount' => $amount,

            'transaction_msg' => $transaction_msg,

            'aadharnumber' => $aadharnumber,

            'fpTransactionId' => $fpTransactionId,

            'ip_address' => $this->getRealIpAddr(),

            'source' => $source

        );



        $txn_id = TransactionDetail::where('order_id', $order_id)->update($trans_info);

        if ($txn_id) {

            return true;
        } else {

            return false;
        }
    }



    public function add_icicitxn($operator_id, $user_id, $role_id, $transaction_id, $order_id, $api_id, $service_id, $status, $mobile, $response, $account_no, $account_holder_name, $amount, $msg)
    {

        $source = 'WEB';

        if (strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'mobile') || strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'android') || strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'okhttp')) {

            $source = 'MOBILE';
        }

        $trans_info = array(

            'transaction_id' => $transaction_id,

            'transaction_status' => $status,

            'service_id' => $service_id,

            'operator_id' => $operator_id,

            'api_id' => $api_id, //change api id

            'trans_date' => date("Y-m-d H:i:s"),

            'order_id' => $this->isValid($order_id),

            'mobileno' => $mobile . "",

            'user_id' => $this->isValid($user_id),

            'total_amount' => "0",

            'charge_amount' => "0",

            'transaction_type' => "",

            // 'bank_transaction_id' => $data['bank_transaction_id'],

            // 'bill_orderId' => $data['merchant_order_id'],

            // 'recipient_id' => $data['recipient_id'], //add

            'charges_tax' => "0", //add

            'commission' => "0", //add

            'commission_tax' => "0", //add

            'commission_tds' => "0", //add

            'debit_amount' => "0",

            'balance' => "0",

            'order_status' => $status,

            'transaction_msg' => "",

            'CCFcharges' => "0",

            'Cashback' => "0",

            'TDSamount' => "0",

            'PayableCharge' => "0",

            'FinalAmount' => "0",

            'request_amount' => "0",

            'updated_on' => date('Y-m-d H:i:s'),

            'ip_address' => $this->getRealIpAddr(),

            'source' => $source,

            'transactionamount' => $transactionamount,

            'response_msg' => $msg,

            'account_no' => $account_no,

            'account_holder_name' => $account_holder_name

        );

        $txn_id = TransactionDetail::insert($trans_info);

        if ($txn_id) {

            return true;
        } else {

            return false;
        }
    }





    public function add_wallet_table($bankname, $mobile, $aadhar, $bank_name, $operator_id, $amount, $user_id, $role_id, $transaction_id, $order_id, $api_id, $service_id)
    {





        $source = 'WEB';

        if (strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'mobile') || strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'android') || strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'okhttp')) {

            $source = 'APP';
        }

        $user = User::where('userId', $user_id)->first();

        if ($user) {

            $wallet_balance = $user->wallet_balance;

            $min_balance = $user->min_balance;

            $user_package_id = $user->package_id;

            //$commissiondet = PackageCommissionDetail::where([['from_range','<=',$amount],['to_range','>=',$amount],['service_id','=',$service_id],['pkg_id','=',$user_package_id],['operator_id',$operator_id]])->first();

            //$charge = $commissiondet->retailer_commission;

            //$totalAmount = $amount + $charge;

            // if(!is_numeric($wallet_balance) || !is_numeric($min_balance) || !is_numeric($amount) || $wallet_balance+$amount < $min_balance || $wallet_balance < $totalAmount) {

            //     return false;

            // }

            if ($service_id == 6 || $service_id == 11 || $service_id == 12) {

                $transaction_detail = "Mobile : " . $mobile . ",Aadhar : " . $aadhar . ",Bank : " . $bank_name . ",Amount :" . $amount;
            } else if ($service_id == 9) {

                $transaction_detail = "Mobile : " . $mobile . ",Aadhar : " . $aadhar . ",Bank : " . $bank_name . ",Amount :" . $amount;
            } else {

                $transaction_detail = "";
            }



            // $wallet_trans_info = array(

            //     'service_id' => $service_id,

            //     'order_id' => $this->isValid($order_id),

            //     'user_id' => $user_id, 

            //     'operator_id' => $operator_id,

            //     'api_id' => $api_id,

            //     'transaction_status' =>'Success',

            //     'transaction_type' => "CREDIT",

            //     'payment_type' => "SERVICE",

            //     'payment_mode' => $transaction_detail,

            //     'transaction_id' => $transaction_id,               

            //     'trans_date' => date("Y-m-d H:i:s"),  

            //     'total_amount' => $amount,

            //     'charge_amount' => "0.00",

            //     'balance' => $wallet_balance+$amount,

            //     'CCFcharges' => "0",

            //     'Cashback' => "0",

            //     'TDSamount' => "0.00",

            //     'PayableCharge' => "0.00",

            //     'FinalAmount' => $amount,

            //     'updated_on' => date('Y-m-d H:i:s'),

            // );

            // $wallet_txn_id = WalletTransactionDetail::create($wallet_trans_info);

            // $userUpdresponse = User::find((int) $user_id)->update(['wallet_balance' => (float) $wallet_balance+$amount]);



            $trans_info = array(

                'user_id' => $user_id,

                'transaction_id' => $transaction_id,

                'recipient_id' => $user->username,

                'transaction_status' => 'PENDING',

                'service_id' => $service_id,

                'api_id' => $api_id,

                'trans_date' => date("Y-m-d H:i:s"),

                'order_id' => $order_id,

                'mobileno' => $user->mobile,

                'operator_id' => $this->isValid($operator_id),

                'total_amount' => $amount,

                'charge_amount' => "0.00",

                'basic_amount' => $amount,

                'balance' => $wallet_balance - $amount,

                'order_status' => 'SUCCESS',

                'aadharnumber' => $aadhar,

                'transaction_msg' => 'SUCCESS',

                'request_amount' => $amount,

                'updated_on' => date('Y-m-d H:i:s'),

                'ip_address' => $this->getRealIpAddr(),

                'source' => $source,

                'aeps_bank_id' => $bankname

            );



            $updateTxn = TransactionDetail::create($trans_info);



            if ($updateTxn) {

                return true;
            } else {

                return false;
            }
        }
    }



    public function add_success_comm($bank_name, $aadhar, $mobile, $operator_id, $amount, $user_id, $role_id, $parent_user_id, $transaction_id, $order_id, $api_id, $service_id, $service_type)
    {



        $source = 'WEB';



        if (strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'mobile') || strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'android') || strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'okhttp')) {

            $source = 'MOBILE';
        }



        $user = User::where('userId', $user_id)->first();





        //Make Wallet Table Debit

        $wallet_balance = $user->wallet_balance;

        $min_balance = $user->min_balance;

        $user_package_id = $user->package_id;



        $commissiondet = PackageCommissionDetail::where([['from_range', '<=', $amount], ['to_range', '>=', $amount], ['service_id', '=', $service_id], ['pkg_id', '=', $user_package_id], ['operator_id', $operator_id]])->first();



        $charge = $commissiondet->retailer_commission;



        $totalAmount = $amount + $charge;

        if ($wallet_balance == 545454545455454) {

            //if(!is_numeric($wallet_balance) || !is_numeric($min_balance) || !is_numeric($amount) || $wallet_balance+$amount < $min_balance || $wallet_balance < $totalAmount) {

            //return false;

        }

        if ($service_id == 6 || $service_id == 11 || $service_id == 12) {

            $transaction_detail = "Mobile : " . $mobile . ",Aadhar : " . $aadhar . ",Bank : " . $bank_name . ",Amount :" . $amount;
        } else if ($service_id == 9) {

            $transaction_detail = "Mobile : " . $mobile . ",Aadhar : " . $aadhar . ",Bank : " . $bank_name . ",Amount :" . $amount;
        } else {

            $transaction_detail = "";
        }



        if ($service_id == 6)  //cash withdrawal

        {

            $wallet_trans_info = array(

                'service_id' => $service_id,

                'order_id' => $this->isValid($order_id),

                'user_id' => $user_id,

                'operator_id' => $operator_id,

                'api_id' => $api_id,

                'transaction_status' => 'Success',

                'transaction_type' => "CREDIT",

                'payment_type' => "SERVICE",

                'payment_mode' => $transaction_detail,

                'transaction_id' => $transaction_id,

                'trans_date' => date("Y-m-d H:i:s"),

                'total_amount' => $amount,

                'charge_amount' => "0.00",

                'balance' => $wallet_balance + $amount,

                'CCFcharges' => "0",

                'Cashback' => "0",

                'TDSamount' => "0.00",

                'PayableCharge' => "0.00",

                'FinalAmount' => $amount,

                'updated_on' => date('Y-m-d H:i:s'),

            );

            $wallet_txn_id = WalletTransactionDetail::create($wallet_trans_info);

            $userUpdresponse = User::find((int) $user_id)->update(['wallet_balance' => (float) $wallet_balance + $amount]);
        } elseif ($service_id == 9) // Aadhar Payment 

        {

            $wallet_trans_info = array(

                'service_id' => $service_id,

                'order_id' => $this->isValid($order_id),

                'user_id' => $user_id,

                'operator_id' => $operator_id,

                'api_id' => $api_id,

                'transaction_status' => 'Success',

                'transaction_type' => "CREDIT",

                'payment_type' => "SERVICE",

                'payment_mode' => $transaction_detail,

                'transaction_id' => $transaction_id,

                'trans_date' => date("Y-m-d H:i:s"),

                'total_amount' => $amount,

                'charge_amount' => "0.00",

                'balance' => $wallet_balance + $amount,

                'CCFcharges' => "0",

                'Cashback' => "0",

                'TDSamount' => "0.00",

                'PayableCharge' => "0.00",

                'FinalAmount' => $amount,

                'updated_on' => date('Y-m-d H:i:s'),

            );

            $wallet_txn_id = WalletTransactionDetail::create($wallet_trans_info);

            $userUpdresponse = User::find((int) $user_id)->update(['wallet_balance' => (float) $wallet_balance + $amount]);
        } else {
        }













        //End Wallet Credit 

        $userbalance = User::where('userId', $user_id)->first();



        $wallet_balance = $userbalance->wallet_balance;

        $min_balance = $userbalance->min_balance;

        $user_package_id = $userbalance->package_id;



        $commissiondet = PackageCommissionDetail::where([['from_range', '<=', $amount], ['to_range', '>=', $amount], ['service_id', '=', $service_id], ['pkg_id', '=', $user_package_id], ['operator_id', $operator_id]])->first();





        $totalAmount = $commissiondet->retailer_commission;

        #random check

        if ($wallet_balance == 545454545455454) {



            //  if(!is_numeric($wallet_balance) || !is_numeric($min_balance) || !is_numeric($totalAmount) || $wallet_balance-$totalAmount < $min_balance || $wallet_balance < $totalAmount) {

            // return false;

        } else { //get all commission details by package id

            $commissionDtl = PackageCommissionDetail::where(['service_id' => $service_id, 'pkg_id' => $user_package_id, 'operator_id' => $operator_id, 'is_deleted' => 0])->get();

            foreach ($commissionDtl as $test) {

                foreach (json_decode($test, true) as $commsnKey => $commsnVal) {

                    if ($commsnKey == "commission_type" && $commsnVal == "Range") {

                        $commissionDtl = PackageCommissionDetail::where([['from_range', '<=', $amount], ['to_range', '>=', $amount], ['service_id', '=', $service_id], ['pkg_id', '=', $user_package_id], ['operator_id', $operator_id]])->first();

                        break;
                    }
                }
            }

            if ($commissionDtl) {

                if ($commissionDtl->commission_type == "Rupees") {

                    $admin_commission = $commissionDtl->admin_commission;

                    $md_commission = $commissionDtl->md_commission;

                    $api_commission = $commissionDtl->api_commission;

                    $distributor_commission = $commissionDtl->distributor_commission;

                    $retailer_commission = $commissionDtl->retailer_commission;
                } else if ($commissionDtl->commission_type == "Percent") {

                    $admin_commission = ($amount * $commissionDtl->admin_commission) / 100;

                    $md_commission = ($amount * $commissionDtl->md_commission) / 100;

                    $api_commission = ($amount * $commissionDtl->api_commission) / 100;

                    $distributor_commission = ($amount * $commissionDtl->distributor_commission) / 100;

                    $retailer_commission = ($amount * $commissionDtl->retailer_commission) / 100;
                } else if ($commissionDtl->commission_type == "Range") {

                    if ($commissionDtl->admin_commission_type == "Rupees")

                        $admin_commission = $commissionDtl->admin_commission;

                    else

                        $admin_commission = ($amount * $commissionDtl->admin_commission) / 100;

                    if ($commissionDtl->md_commission_type == "Rupees")

                        $md_commission = $commissionDtl->md_commission;

                    else

                        $md_commission = ($amount * $commissionDtl->md_commission) / 100;

                    if ($commissionDtl->distributor_commission_type == "Rupees")

                        $distributor_commission = $commissionDtl->distributor_commission;

                    else

                        $distributor_commission = ($amount * $commissionDtl->distributor_commission) / 100;

                    if ($commissionDtl->retailer_commission_type == "Rupees")

                        $retailer_commission = $commissionDtl->retailer_commission;

                    else

                        $retailer_commission = ($amount * $commissionDtl->retailer_commission) / 100;

                    $api_commission = $commissionDtl->api_commission;
                }
            }
        }

        $ccf = $commissionDtl->ccf_commission;

        if ($commissionDtl->retailer_commission_type == 'Percent') {

            $charge = $amount * ($commissionDtl->retailer_commission / 100);
        } elseif ($commissionDtl->retailer_commission_type == 'Rupees') {

            $charge = $commissionDtl->retailer_commission;
        }

        $updatedBalance = $wallet_balance; //update balance after deduction begin



        if (is_numeric($role_id) && intval($role_id) <= 4) {

            $walletUserID = $user_id;

            $walletRoleID = $role_id;

            $isUserBalanceUpdated = false;

            for ($i = $walletRoleID; $i >= 1; $i--) {

                if ($i == 3) {

                    $isUserBalanceUpdated = true;

                    $user = User::where(['userId' => $walletUserID])->first();

                    $userParentID = User::where(['userId' => $user->parent_user_id])->first();

                    if ($isUserBalanceUpdated && $userParentID && $userParentID->roleId == 3) {

                        $walletUserID = $userParentID->userId;

                        $walletRoleID = $userParentID->roleId;

                        $updatedBalance = $userParentID->wallet_balance;
                    }

                    continue;
                }

                $walletAmt = 0;

                $walletBal = 0;

                $user = User::where(['userId' => $walletUserID])->first();

                $userParentID = User::where(['userId' => $user->parent_user_id])->first();



                if ($isUserBalanceUpdated && $userParentID) {

                    $walletUserID = $userParentID->userId;

                    $walletRoleID = $userParentID->roleId;

                    $updatedBalance = $userParentID->wallet_balance;
                }

                if ($walletRoleID == 4) { //Retailer

                    $walletAmt = $retailer_commission;

                    $walletBal = $updatedBalance + $retailer_commission;

                    if ($service_type == "aadhar_payment" || $service_type == "cash_deposit") {

                        $walletBal = $updatedBalance - $retailer_commission;
                    }



                    //Updating commision in Transaction Table

                    $trans_update_arr1 = array(

                        'retailer_commision' => abs($walletAmt)

                    );

                    $updateTxn1 = TransactionDetail::where('order_id', $order_id)->update($trans_update_arr1);
                } else if ($walletRoleID == 2) { //Distributor

                    $walletAmt = $distributor_commission;

                    $walletBal = $updatedBalance + $distributor_commission;



                    //Updating commision in Transaction Table

                    $trans_update_arr2 = array(

                        'distributor_commision' => abs($walletAmt)

                    );

                    $updateTxn2 = TransactionDetail::where('order_id', $order_id)->update($trans_update_arr2);
                } else if ($walletRoleID == 1) { //Admin

                    $walletAmt = $admin_commission;

                    $walletBal = $updatedBalance + $admin_commission;



                    //Updating commision in Transaction Table

                    $trans_update_arr3 = array(

                        'admin_commision' => abs($walletAmt)

                    );

                    $updateTxn3 = TransactionDetail::where('order_id', $order_id)->update($trans_update_arr3);
                }

                if (is_numeric($walletAmt) && is_numeric($walletBal)) {

                    $transType = "CREDIT";

                    if ($walletAmt < 0) {

                        $transType = "DEBIT";
                    }

                    if (($service_type == "aadhar_payment" || $service_type == "cash_deposit") && $walletRoleID == 4) {

                        $transType = "DEBIT";
                    }



                    if ($service_id == 6 || $service_id == 11 || $service_id == 12) {

                        $transaction_detail = "Mobile : " . $mobile . ",Aadhar : " . $aadhar . ",Bank : " . $bank_name . ",Amount :" . $amount . ",Comm  : $charge";
                    } else if ($service_id == 9) {

                        $transaction_detail = "Mobile : " . $mobile . ",Aadhar : " . $aadhar . ",Bank : " . $bank_name . ",Amount :" . $amount . ",Charge : " . $charge;
                    } else {

                        $transaction_detail = "";
                    }





                    $wallet_trans_info = array(

                        'service_id' => $service_id,

                        'order_id' => $order_id,

                        'user_id' => $walletUserID,

                        'operator_id' => $operator_id,

                        'api_id' => $api_id,

                        'transaction_status' => 'SUCCESS',

                        'transaction_type' => $transType,

                        'payment_type' => "COMMISSION",

                        'payment_mode' => $transaction_detail,

                        //   'transaction_id' => "12121215515",

                        'transaction_id' => $transaction_id,

                        'trans_date' => date("Y-m-d H:i:s"),

                        'total_amount' => abs($walletAmt),

                        'charge_amount' => "0.00",

                        'balance' => $walletBal,

                        'updated_on' => date('Y-m-d H:i:s'),

                    );

                    // echo $walletUserID."\n";

                    $wallet_txn_id = WalletTransactionDetail::create($wallet_trans_info);

                    $updateBalQry = User::find((int) $walletUserID)->update(['wallet_balance' => (float) $walletBal]);
                }

                $isUserBalanceUpdated = true;
            }
        }

        $trans_update_arr = array(

            'transaction_id' => $transaction_id,

            'transaction_status' => 'SUCCESS',

            'order_status' => 'SUCCESS',

            'updated_on' => date('Y-m-d H:i:s'),

            'ip_address' => $this->getRealIpAddr(),

            'source' => $source

        );

        $updateTxn = TransactionDetail::where('order_id', $order_id)->update($trans_update_arr);

        if ($updateTxn) {

            return true;
        } else {

            return false;
        }
    }

    //ICICI Cash Deposit Transaction entry before firing api to icici

    public function add_icici_transaction_comm($smartid, $bankName, $accountHoldername, $mobile, $superMerchantId, $accountNumber, $operator_id, $amount, $user_id, $role_id, $parent_user_id, $transaction_id, $order_id, $api_id, $service_id, $service_type, $bank_transaction_id)
    {

        $source = 'WEB';

        if (strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'mobile') || strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'android') || strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'okhttp')) {

            $source = 'APP';
        }

        $user = User::where('userId', $user_id)->first();



        if ($user) {

            $wallet_balance = $user->wallet_balance;

            $min_balance = $user->min_balance;

            $user_package_id = $user->package_id;



            $commissiondet = PackageCommissionDetail::where([['from_range', '<=', $amount], ['to_range', '>=', $amount], ['service_id', '=', $service_id], ['pkg_id', '=', $user_package_id], ['operator_id', $operator_id]])->first();



            $charge = $commissiondet->retailer_commission;

            $totalAmount = $amount + $charge;

            if (!is_numeric($wallet_balance) || !is_numeric($min_balance) || !is_numeric($amount) || $wallet_balance + $amount < $min_balance || $wallet_balance < $totalAmount) {

                return false;
            }

            $wallet_trans_info = array(

                'service_id' => $service_id,

                'order_id' => $this->isValid($order_id),

                'user_id' => $user_id,

                'operator_id' => $operator_id,

                'api_id' => $api_id,

                'transaction_status' => 'Success',

                'transaction_type' => "DEBIT",

                'payment_type' => "SERVICE",

                'payment_mode' => "PAID FOR ICICI DEPOSIT ACCOUNT NUMBER : " . $accountNumber . ", MOBILE NUMBER : " . $mobile . ",AMOUNT :" . $amount,

                'transaction_id' => $transaction_id,

                'trans_date' => date("Y-m-d H:i:s"),

                'total_amount' => $amount,

                'charge_amount' => "0.00",

                'balance' => $wallet_balance - $amount,

                'CCFcharges' => "0",

                'Cashback' => "0",

                'TDSamount' => "0.00",

                'PayableCharge' => "0.00",

                'FinalAmount' => $amount,

                'updated_on' => date('Y-m-d H:i:s'),

            );



            $wallet_txn_id = WalletTransactionDetail::create($wallet_trans_info);

            $userUpdresponse = User::find((int) $user_id)->update(['wallet_balance' => (float) $wallet_balance - $amount]);

            $trans_info = array(

                'user_id' => $user_id,

                'transaction_id' => $transaction_id,

                'recipient_id' => $user->username,

                'transaction_status' => 'SUCCESS',

                'service_id' => $service_id,

                'api_id' => $api_id,

                'trans_date' => date("Y-m-d H:i:s"),

                'order_id' => $order_id,

                'mobileno' => $user->mobile,

                'operator_id' => $this->isValid($operator_id),

                'total_amount' => $amount,

                'charge_amount' => "0.00",

                'basic_amount' => $amount,

                'balance' => $wallet_balance - $amount,

                'order_status' => 'SUCCESS',

                'transaction_msg' => 'SUCCESS',

                'request_amount' => $amount,

                'updated_on' => date('Y-m-d H:i:s'),

                'ip_address' => $this->getRealIpAddr(),

                'source' => $source,

                'bank_account_no' => $accountNumber,

                'mobileno' => $mobile,

                'superMerchantId' => $superMerchantId,

                'transactionamount' => $amount,

                'account_holder_name' => $accountHoldername,

                'rrnno' => $bank_transaction_id

            );



            // print_r(json_encode($trans_info));die();

            $updateTxn = TransactionDetail::create($trans_info);

            if ($wallet_txn_id && $userUpdresponse && $updateTxn) {

                return true;
            } else {

                return false;
            }
        }
    }



    //Ends Here



    //ICICI Cash Deposit Transaction entry After Success api to icici



    public function add_icici_after_transaction_comm($beneficiaryName, $fpRrn, $fingpayTransactionId, $accountNumber, $mobile, $msg, $rrn, $operator_id, $amount, $user_id, $role_id, $parent_user_id, $transaction_id, $order_id, $api_id, $service_id, $service_type)
    {



        $source = 'WEB';

        if (strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'mobile') || strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'android') || strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'okhttp')) {

            $source = 'MOBILE';
        }

        $userbalance = User::where('userId', $user_id)->first();

        $wallet_balance = $userbalance->wallet_balance;

        $min_balance = $userbalance->min_balance;

        $user_package_id = $userbalance->package_id;



        $commissiondet = PackageCommissionDetail::where([['from_range', '<=', $amount], ['to_range', '>=', $amount], ['service_id', '=', 10], ['pkg_id', '=', 1], ['operator_id', 45]])->first();



        $totalAmount = $commissiondet->retailer_commission;



        if (!is_numeric($wallet_balance) || !is_numeric($min_balance) || !is_numeric($totalAmount) || $wallet_balance - $totalAmount < $min_balance || $wallet_balance < $totalAmount) {

            return false;
        } else { //get all commission details by package id

            $commissionDtl = PackageCommissionDetail::where(['service_id' => 10, 'pkg_id' => 1, 'operator_id' => 45, 'is_deleted' => 0])->get();

            foreach ($commissionDtl as $test) {

                foreach (json_decode($test, true) as $commsnKey => $commsnVal) {

                    if ($commsnKey == "commission_type" && $commsnVal == "Range") {

                        $commissionDtl = PackageCommissionDetail::where([['from_range', '<=', $amount], ['to_range', '>=', $amount], ['service_id', '=', 10], ['pkg_id', '=', 1], ['operator_id', 45]])->first();

                        break;
                    }
                }
            }

            if ($commissionDtl) {

                if ($commissionDtl->commission_type == "Rupees") {

                    $admin_commission = $commissionDtl->admin_commission;

                    $md_commission = $commissionDtl->md_commission;

                    $api_commission = $commissionDtl->api_commission;

                    $distributor_commission = $commissionDtl->distributor_commission;

                    $retailer_commission = $commissionDtl->retailer_commission;
                } else if ($commissionDtl->commission_type == "Percent") {

                    $admin_commission = ($amount * $commissionDtl->admin_commission) / 100;

                    $md_commission = ($amount * $commissionDtl->md_commission) / 100;

                    $api_commission = ($amount * $commissionDtl->api_commission) / 100;

                    $distributor_commission = ($amount * $commissionDtl->distributor_commission) / 100;

                    $retailer_commission = ($amount * $commissionDtl->retailer_commission) / 100;
                } else if ($commissionDtl->commission_type == "Range") {

                    if ($commissionDtl->admin_commission_type == "Rupees")

                        $admin_commission = $commissionDtl->admin_commission;

                    else

                        $admin_commission = ($amount * $commissionDtl->admin_commission) / 100;

                    if ($commissionDtl->md_commission_type == "Rupees")

                        $md_commission = $commissionDtl->md_commission;

                    else

                        $md_commission = ($amount * $commissionDtl->md_commission) / 100;

                    if ($commissionDtl->distributor_commission_type == "Rupees")

                        $distributor_commission = $commissionDtl->distributor_commission;

                    else

                        $distributor_commission = ($amount * $commissionDtl->distributor_commission) / 100;

                    if ($commissionDtl->retailer_commission_type == "Rupees")

                        $retailer_commission = $commissionDtl->retailer_commission;

                    else

                        $retailer_commission = ($amount * $commissionDtl->retailer_commission) / 100;

                    $api_commission = $commissionDtl->api_commission;
                }
            }
        }

        $ccf = $commissionDtl->ccf_commission;

        if ($commissionDtl->retailer_commission_type == 'Percent') {

            $charge = $amount * ($commissionDtl->retailer_commission / 100);
        } elseif ($commissionDtl->retailer_commission_type == 'Rupees') {

            $charge = $commissionDtl->retailer_commission;
        }

        $updatedBalance = $wallet_balance; //update balance after deduction begin



        if (is_numeric($role_id) && intval($role_id) <= 4) {

            $walletUserID = $user_id;

            $walletRoleID = $role_id;

            $isUserBalanceUpdated = false;

            for ($i = $walletRoleID; $i >= 1; $i--) {

                if ($i == 3) {

                    $isUserBalanceUpdated = true;

                    $user = User::where(['userId' => $walletUserID])->first();

                    $userParentID = User::where(['userId' => $user->parent_user_id])->first();

                    if ($isUserBalanceUpdated && $userParentID && $userParentID->roleId == 3) {

                        $walletUserID = $userParentID->userId;

                        $walletRoleID = $userParentID->roleId;

                        $updatedBalance = $userParentID->wallet_balance;
                    }

                    continue;
                }

                $walletAmt = 0;

                $walletBal = 0;

                $user = User::where(['userId' => $walletUserID])->first();

                $userParentID = User::where(['userId' => $user->parent_user_id])->first();



                if ($isUserBalanceUpdated && $userParentID) {

                    $walletUserID = $userParentID->userId;

                    $walletRoleID = $userParentID->roleId;

                    $updatedBalance = $userParentID->wallet_balance;
                }

                if ($walletRoleID == 4) { //Retailer

                    $walletAmt = $retailer_commission;

                    $walletBal = $updatedBalance + $retailer_commission;

                    if ($service_type == "aadhar_payment" || $service_type == "cash_deposit" || $service_type == "icici_cash_deposit") {

                        $walletBal = $updatedBalance + $retailer_commission;

                        //Updating commision in Transaction Table

                        $trans_update_arr1 = array(

                            'retailer_commision' => abs($walletAmt)

                        );

                        $updateTxn1 = TransactionDetail::where('order_id', $order_id)->update($trans_update_arr1);
                    }
                } else if ($walletRoleID == 2) { //Distributor

                    $walletAmt = $distributor_commission;

                    $walletBal = $updatedBalance + $distributor_commission;

                    $trans_update_arr1 = array(

                        'distributor_commision' => abs($walletAmt)

                    );

                    $updateTxn1 = TransactionDetail::where('order_id', $order_id)->update($trans_update_arr1);
                } else if ($walletRoleID == 1) { //Admin

                    $walletAmt = $admin_commission;

                    $walletBal = $updatedBalance + $admin_commission;

                    $trans_update_arr1 = array(

                        'admin_commision' => abs($walletAmt)

                    );

                    $updateTxn1 = TransactionDetail::where('order_id', $order_id)->update($trans_update_arr1);
                }

                if (is_numeric($walletAmt) && is_numeric($walletBal)) {

                    $transType = "CREDIT";

                    if ($walletAmt < 0) {

                        $transType = "DEBIT";
                    }

                    if (($service_type == "aadhar_payment" || $service_type == "cash_deposit" || $service_type == "icici_cash_deposit") && $walletRoleID == 4) {

                        $transType = "CREDIT";
                    }

                    $wallet_trans_info = array(

                        'service_id' => $service_id,

                        'order_id' => $order_id,

                        'user_id' => $walletUserID,

                        'operator_id' => $operator_id,

                        'api_id' => $api_id,

                        'transaction_status' => 'SUCCESS',

                        'transaction_type' => $transType,

                        'payment_type' => "COMMISSION",

                        'payment_mode' => "COMMISSION FOR ICICI DEPOSIT ACCOUNT NUMBER : " . $accountNumber . " ,MOBILE NUMBER : " . $mobile . " ,AMOUNT : " . $amount,

                        'transaction_id' => "12121215515",

                        //'transaction_id' => $transaction_id,               

                        'trans_date' => date("Y-m-d H:i:s"),

                        'total_amount' => abs($walletAmt),

                        'charge_amount' => "0.00",

                        'balance' => $walletBal,

                        'updated_on' => date('Y-m-d H:i:s'),

                    );

                    // echo $walletUserID."\n";

                    $wallet_txn_id = WalletTransactionDetail::create($wallet_trans_info);

                    $updateBalQry = User::find((int) $walletUserID)->update(['wallet_balance' => (float) $walletBal]);
                }

                $isUserBalanceUpdated = true;
            }
        }

        $trans_update_arr = array(

            'transaction_id' => $transaction_id,

            'transaction_status' => 'SUCCESS',

            'order_status' => 'SUCCESS',

            'updated_on' => date('Y-m-d H:i:s'),

            'ip_address' => $this->getRealIpAddr(),

            'source' => $source,

            'commission' => $retailer_commission,

            'response_msg' => $msg,

            'rrnno' => $rrn,

            'account_holder_name' => $beneficiaryName,

            'fpTransactionId' => $fingpayTransactionId,

        );

        $updateTxn = TransactionDetail::where('order_id', $order_id)->update($trans_update_arr);

        if ($updateTxn) {

            return true;
        } else {

            return false;
        }
    }

    //Ends Here



    //ICICI Cash Deposit Transaction entry after failed response api to icici

    public function add_icici_failed_transaction_comm($beneficiaryName, $fpRrn, $fingpayTransactionId, $response_msg, $accountNumber, $mobile, $operator_id, $amount, $user_id, $role_id, $transaction_id, $order_id, $api_id, $service_id)
    {



        $source = 'WEB';

        if (strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'mobile') || strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'android') || strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'okhttp')) {

            $source = 'MOBILE';
        }

        $user = User::where('userId', $user_id)->first();

        $walletBal = $user->wallet_balance + $amount;



        $wallet_trans_info = array(

            'service_id' => $service_id,

            'order_id' => $order_id,

            'user_id' => $user_id,

            'operator_id' => $operator_id,

            'api_id' => $api_id,

            'transaction_status' => 'FAILED',

            'transaction_type' => 'CREDIT',

            'payment_type' => "REFUND",

            'payment_mode' => "REFUND FOR ICICI DEPOSIT ACCOUNT NUMBER : " . $accountNumber . " ,MOBILE NUMBER : " . $mobile . " ,AMOUNT :" . $amount,

            'transaction_id' => $transaction_id,

            'trans_date' => date("Y-m-d H:i:s"),

            'total_amount' => abs($amount),

            'charge_amount' => "0.00",

            'balance' => $walletBal,

            'updated_on' => date('Y-m-d H:i:s'),



        );

        $wallet_txn_id = WalletTransactionDetail::create($wallet_trans_info);

        $updateBalQry = User::find((int) $user_id)->update(['wallet_balance' => (float) $walletBal]);

        $trans_update_arr = array(

            'transaction_id' => $transaction_id,

            'transaction_status' => 'FAILED',

            'order_status' => 'FAILED',

            'updated_on' => date('Y-m-d H:i:s'),

            'ip_address' => $this->getRealIpAddr(),

            'source' => $source,

            'response_msg' => $response_msg,

            'rrnno' => $fpRrn,

            'client_reference_id' => $fingpayTransactionId



        );

        $updateTxn = TransactionDetail::where('order_id', $order_id)->update($trans_update_arr);

        if ($updateTxn && $updateBalQry && $wallet_txn_id) {

            return true;
        } else {

            return false;
        }
    }



    //Ends Here



    public function add_failed_balance($operator_id, $amount, $user_id, $role_id, $transaction_id, $order_id, $api_id, $service_id)
    {

        $source = 'WEB';

        if (strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'mobile') || strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'android') || strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'okhttp')) {

            $source = 'MOBILE';
        }

        $user = User::where('userId', $user_id)->first();

        $walletBal = $user->wallet_balance + $amount;



        $wallet_trans_info = array(

            'service_id' => $service_id,

            'order_id' => $order_id,

            'user_id' => $user_id,

            'operator_id' => $operator_id,

            'api_id' => $api_id,

            'transaction_status' => 'FAILED',

            'transaction_type' => 'CREDIT',

            'payment_type' => "REFUND",

            'payment_mode' => "Refund for AEPS",

            'transaction_id' => $transaction_id,

            'trans_date' => date("Y-m-d H:i:s"),

            'total_amount' => abs($amount),

            'charge_amount' => "0.00",

            'balance' => $walletBal,

            'updated_on' => date('Y-m-d H:i:s'),

        );

        $wallet_txn_id = WalletTransactionDetail::create($wallet_trans_info);

        $updateBalQry = User::find((int) $user_id)->update(['wallet_balance' => (float) $walletBal]);

        $trans_update_arr = array(

            'transaction_id' => $transaction_id,

            'transaction_status' => 'FAILED',

            'order_status' => 'FAILED',

            'updated_on' => date('Y-m-d H:i:s'),

            'ip_address' => $this->getRealIpAddr(),

            'source' => $source

        );

        $updateTxn = TransactionDetail::where('order_id', $order_id)->update($trans_update_arr);

        if ($updateTxn && $updateBalQry && $wallet_txn_id) {

            return true;
        } else {

            return false;
        }
    }



    public function api_log($transaction_id, $service_id, $api_details, $order_id, $user_id, $request, $result, $method)
    {

        $api_info = array(

            'service_id' => $service_id . "",

            'api_id' => $api_details->api_id . "",

            'api_name' => $api_details->api_name . "",

            'api_method' => $method,

            'api_url' => $api_details->api_url . "",

            'order_id' => $order_id . "",

            'user_id' => $user_id . "",

            'request_input' => json_encode($request) . "",

            'request' => json_encode($request) . "",

            'response' => json_encode($result) . "",

            'access_type' => "APP",

            'updated_on' => date('Y-m-d H:i:s'),

            'transaction_id' => $transaction_id

        );

        $insert_log = ApiLogDetail::insert($api_info);

        if ($insert_log) {

            return true;
        } else {

            return false;
        }
    }

    public function update_api_log($service_id, $api_details, $order_id, $user_id, $request, $result, $method)
    {





        $api_info = array(

            'service_id' => $service_id . "",

            'api_id' => $api_details->api_id . "",

            'api_name' => $api_details->api_name . "",

            'api_method' => $method,

            'api_url' => $api_details->api_url . "",

            'order_id' => $order_id . "",

            'user_id' => $user_id . "",

            'request_input' => json_encode($request) . "",

            'request' => json_encode($request) . "",

            'response' => json_encode($result) . "",

            'access_type' => "APP",

            'updated_on' => date('Y-m-d H:i:s'),

        );



        $insert_log = ApiLogDetail::where('order_id', $order_id)->update($api_info);

        if ($insert_log) {

            return true;
        } else {

            return false;
        }
    }



    public function isValid($str)
    {

        if (isset($str) && $str != null)

            return $str;

        else

            return '';
    }



    public function get_order_id()
    {

        $last_txn_id = file_get_contents("admin/admin/txn_order_id.txt");

        $sno_client_id = intval($last_txn_id) + 1;

        $client_id = "SP" . $sno_client_id;

        $clientres = $this->transactions_model->check_order_id($client_id);

        if (!empty($clientres)) {

            write_file('admin/admin/txn_order_id.txt', $sno_client_id . "");

            $this->get_order_id();
        } else {

            $order = array('order_id' => $client_id, 'sno_order_id' => $sno_client_id);

            write_file('admin/admin/txn_order_id.txt', $sno_client_id . "");

            return json_encode($order);
        }
    }



    public function getRealIpAddr()
    {

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {

            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {

            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {

            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;

        // $ips = explode(',', $ip);

        // return $ips[0];

    }

    public function getcharge(Request $request)

    {

        $amount = $request->amount;

        $user_id = Auth::user()->userId;

        $user = User::where('userId', $user_id)->first();

        $user_package_id = $user->package_id;

        $commissionDtl = PackageCommissionDetail::where([['from_range', '<=', $amount], ['to_range', '>=', $amount], ['service_id', '=', 9], ['pkg_id', '=', $user_package_id], ['operator_id', 44]])->first();



        if ($commissionDtl) {

            if ($commissionDtl->commission_type == "Rupees") {

                $admin_commission = $commissionDtl->admin_commission;

                $md_commission = $commissionDtl->md_commission;

                $api_commission = $commissionDtl->api_commission;

                $distributor_commission = $commissionDtl->distributor_commission;

                $retailer_commission = $commissionDtl->retailer_commission;
            } else if ($commissionDtl->commission_type == "Percent") {

                $admin_commission = ($amount * $commissionDtl->admin_commission) / 100;

                $md_commission = ($amount * $commissionDtl->md_commission) / 100;

                $api_commission = ($amount * $commissionDtl->api_commission) / 100;

                $distributor_commission = ($amount * $commissionDtl->distributor_commission) / 100;

                $retailer_commission = ($amount * $commissionDtl->retailer_commission) / 100;
            } else if ($commissionDtl->commission_type == "Range") {

                if ($commissionDtl->admin_commission_type == "Rupees")

                    $admin_commission = $commissionDtl->admin_commission;

                else

                    $admin_commission = ($amount * $commissionDtl->admin_commission) / 100;

                if ($commissionDtl->md_commission_type == "Rupees")

                    $md_commission = $commissionDtl->md_commission;

                else

                    $md_commission = ($amount * $commissionDtl->md_commission) / 100;

                if ($commissionDtl->distributor_commission_type == "Rupees")

                    $distributor_commission = $commissionDtl->distributor_commission;

                else

                    $distributor_commission = ($amount * $commissionDtl->distributor_commission) / 100;

                if ($commissionDtl->retailer_commission_type == "Rupees")

                    $retailer_commission = $commissionDtl->retailer_commission;

                else

                    $retailer_commission = ($amount * $commissionDtl->retailer_commission) / 100;

                $api_commission = $commissionDtl->api_commission;
            }
        }

        //$ccf = $commissionDtl->ccf_commission;



        if ($commissionDtl->retailer_commission_type == 'Percent') {

            $charge = $amount * ($commissionDtl->retailer_commission / 100);
        } elseif ($commissionDtl->retailer_commission_type == 'Rupees') {

            $charge = $commissionDtl->retailer_commission;
        }

        return $charge;
    }
}
