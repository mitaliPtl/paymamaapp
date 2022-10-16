<?php
namespace App\Http\Controllers\ServiceType;

use App\Http\Controllers\Controller;
use App\UserLoginSessionDetail;
use App\OperatorSetting;
use App\TransactionDetail;
use DB;
use Auth;
use Config;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\User;
// use Request;
class MoneyTransferController extends Controller
{
    /**
     * Services View
     */
    public function index(Request $request)
    {
        if ($request->input('money_transfer')) {
            $data['money_trns_type'] = $request->input('money_transfer');
            $data['page_name'] = "customer_mobile";
            if ($request->input('money_transfer') == 'SMART_MONEY') {
                $data['operator_id'] = 40;  

            }elseif($request->input('money_transfer') == 'CRAZY_MONEY') {

                $data['operator_id']= 21;

            }elseif($request->input('money_transfer') == 'BHIM_UPI') {
                $data['operator_id'] = 42;
            }
           
            return view("modules.service_type.mobile_no_money_trans", compact('data'));


        }else {
            $apiKey = UserLoginSessionDetail::getUserApikey(Auth::user()->userId) ? UserLoginSessionDetail::getUserApikey(Auth::user()->userId) : '';
            $bankList = $this->getBankList("21");
            $moneyTranTypes = Config::get('constants.MONEY_TRANSFER.TYPE');

            $appDtls = DB::table('tbl_application_details')->whereIn('alias', ['Paytm Limit', 'upilimit', 'crazymoneylimit'])->get();
            $appDtls = (count($appDtls)>0)? $appDtls : [];
            $transfer_limits=[];
            foreach ($appDtls as $key => $value) {
                if ( $value->alias == 'Paytm Limit') {
                    $transfer_limits['smart_money'] = $value->value;
                }
                if ( $value->alias == 'upilimit') {
                    $transfer_limits['upi_money'] = $value->value;
                }
                if ( $value->alias == 'crazymoneylimit') {
                    $transfer_limits['crazy_money'] = $value->value;
                }
               
            }
            return view("modules.service_type.money_transfer", compact('apiKey', 'moneyTranTypes','bankList', 'transfer_limits'));
        }
    }

    public function index_new(Request $request)
    {
        if ($request->input('money_transfer')) {
            $data['money_trns_type'] = $request->input('money_transfer');
            $data['page_name'] = "customer_mobile";
            if ($request->input('money_transfer') == 'SMART_MONEY') {
                $data['operator_id'] = 40;  

            }elseif($request->input('money_transfer') == 'CRAZY_MONEY') {

                $data['operator_id']= 21;

            }elseif($request->input('money_transfer') == 'BHIM_UPI') {
                $data['operator_id'] = 42;
            }
           
            return view("modules.service_type.mobile_no_money_trans", compact('data'));


        }else {
            $apiKey = UserLoginSessionDetail::getUserApikey(Auth::user()->userId) ? UserLoginSessionDetail::getUserApikey(Auth::user()->userId) : '';
            $bankList = $this->getBankList('21');
            $moneyTranTypes = Config::get('constants.MONEY_TRANSFER.TYPE');
            return view("modules.service_type.money_transfer_new", compact('apiKey', 'moneyTranTypes','bankList'));
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

    public function getSenderDetails(Request $request){
    
        $operator_id='';
        $receipient_api = Config::get('constants.MONEY_TRANSFER.GET_RECEIPIENT_LIST');
        if ($request->operator_name == 'SMART_MONEY') {
            $operator_id= 40;  

        }elseif($request->operator_name == 'CRAZY_MONEY') {

            // $operator_id= 21;
            $operator_id= 40;  

        }elseif($request->operator_name == 'BHIM_UPI') {
            $operator_id = 42;
            $receipient_api = Config::get('constants.MONEY_TRANSFER.GET_RECEIPIENT_LIST_UPI');

        }

        //START get sender details
        $requestBody = [
            'sender_mobile_number' => $request->sender_mobile_number,
            'token' => UserLoginSessionDetail::getUserApikey(Auth::user()->userId) ? UserLoginSessionDetail::getUserApikey(Auth::user()->userId) : '',
            'user_id' => Auth::user()->userId,
            'role_id' => Auth::user()->roleId,
            'operatorID' => $operator_id
        ];
      
        $data=[];
        $data['requestBody'] = json_encode($requestBody);
        // print_r(json_encode($requestBody));die();
        if (isset($request->sender_mobile_number)) {
            $requestBody['sender_mobile_number'] = $request->sender_mobile_number;
            $data['page_name'] = "sender_details";
        }elseif (isset($request->sender_acc_number)) {
            $requestBody['account_number'] = $request->sender_acc_number;
            $data['page_name'] = "sender_details_acc_no";

        }
         //delete beneficiary
        //  $request->action = 'deleted';
        if (($request->action) && ($request->action == 'delete') ){
            // $request->action = '';
            $request->merge(['action' => 'deleted']);

            $delete_response = $this->deleteBeneficiary($request,  $requestBody);
            if ($delete_response['status']=='true') {
                $data['success'] = $delete_response['msg'];
                
            }else {
                    $data['error'] = $delete_response['msg'];
                    
            }
            
        }

        $data ['request']= $request->all();
        
        $sender_dtls = Http::post(Config::get('constants.MONEY_TRANSFER.GET_SENDER_DTLS_API'), $requestBody);
        $sender_dtls = isset($sender_dtls) && $sender_dtls ? $sender_dtls->json() : [];
        //END get sender details
       // print_r($sender_dtls);
        
        // START get recipent details
        $sender_receipient_list = Http::post($receipient_api, $requestBody);
        
        $sender_receipient_list = isset($sender_receipient_list) && $sender_receipient_list ? $sender_receipient_list->json() : [];
        // END get recipent details
        
        $sender_by_acc = Http::post(Config::get('constants.WEBSITE_BASE_URL').'admin/index.php/MoneyTransferApi/get_sender_byaccountnum', $requestBody);
        // print_r($sender_by_acc->json());die();
        $sender_by_acc = isset($sender_by_acc) && $sender_by_acc ? $sender_by_acc->json() : [];


        // print_r($requestBody);
        // exit();
        //bank List
        // $data['bankList'] = Http::post(Config::get('constants.MONEY_TRANSFER.GET_BANK_LIST') , $requestBody);
        // $data['bankList'] = isset($data['bankList']) && $data['bankList'] ? $data['bankList']->json() : [];

        $data['bankList'] = $this->getAllBankList();

        // print_r($data['bankList']);
       
        $data['bank_ifsc']=[];
        foreach ($data['bankList']['result']['bank_list'] as $key => $value) {
            $data['bank_ifsc'][$value['bank_code']] = $value['ifsc_prefix'];
        }


        // print_r($response);
        $data['operator_name'] =$request->operator_name;
        // print_r($request->all());
        // print_r($sender_dtls);
        // exit();
        $data['mobile_no'] = $request->sender_mobile_number;
        if(isset($sender_dtls))
        {
        if ($sender_dtls['status'] == 'false') {
            $data['page_name'] = "register_mobile_no";
            $data['error'] = "Sender Not Found of this mobile no. Please Register it!!";
        }
        }
        if ((isset($request->sender_acc_number)) && ($sender_by_acc['status']== 'true') ){
            $data['page_name'] = "sender_details_acc_no";
            if (count($sender_by_acc['result']) < 1) {
                
                // $data['error'] = "No Account has Registered with this Account Number. Please Register it!!";
                $data['error'] = "No Record Found";
               
            }else{
                $data['error'] = null;
            }
            
        }
        
        // print_r($sender_by_acc);
        // exit();
        

        return view("modules.service_type.mobile_no_money_trans", compact('data', 'sender_dtls', 'sender_receipient_list', 'sender_by_acc'));
       
    }

    public function registerSender(Request $request){
        // print_r($request->all());
        // exit();
        $data = [];
        $opt_const = 'constants.MONEY_TRANSFER_OPERATOR.'.$request->operator_name;
        if ($request->operator_name == 'CRAZY_MONEY') {
            $opt_const = 'constants.MONEY_TRANSFER_OPERATOR.SMART_MONEY';
            $data['smartpay'] = 'SMART_MONEY' ;

        }
        $operator_id = OperatorSetting::where('operator_name', Config::get($opt_const))->pluck('operator_id')->first();
        
        $requestBody = [
                            "sender_mobile_number" => $request->mobile_no,
                            "token" =>  UserLoginSessionDetail::getUserApikey(Auth::user()->userId),
                            "user_id" => Auth::user()->userId,
                            "role_id" => Auth::user()->roleId,
                            "operatorID"=> $operator_id,
                            "first_name"=> $request->reg_first_name,
                            "last_name"=> $request->reg_last_name,
                            "pincode"=> $request->pincode
                        ];

        $response = Http::post(Config::get('constants.MONEY_TRANSFER.CREATE_SENDER_API'), $requestBody);
        $response = isset($response) && $response ? $response->json() : [];
        
        $sender_receipient_list = $response;
        
        // $response['status'] = 'true';
        if ($response['status'] == 'false') {
            $data['error'] = 'Went Wrong';
           return redirect('/money_transfer');
        }
        $data['success'] = 'Verify OTP';
        $data['mobile_no'] = $request->mobile_no;
        $data['page_name'] = "verify_otp";
        $data['operator_id'] =  $operator_id;
        $data['operator_name'] =  $request->operator_name;
        $data['requestBody'] = json_encode($requestBody);
        return view("modules.service_type.mobile_no_money_trans", compact('data', 'sender_receipient_list'));
    }

    public function verifyOTP(Request $request){
        
        //START get sender details
        $requestBody = [
            'sender_mobile_number' => $request->mobile_no,
            'token' => UserLoginSessionDetail::getUserApikey(Auth::user()->userId) ? UserLoginSessionDetail::getUserApikey(Auth::user()->userId) : '',
            'user_id' => Auth::user()->userId,
            'role_id' => Auth::user()->roleId,
            'operatorID' => $request->operator_id
        ];
       
        $data['requestBody'] = json_encode($requestBody);
        // print_r($requestBody);
        
        $sender_dtls = Http::post(Config::get('constants.MONEY_TRANSFER.GET_SENDER_DTLS_API'), $requestBody);
        $sender_dtls = isset($sender_dtls) && $sender_dtls ? $sender_dtls->json() : [];
        //END get sender details


        // START get recipent details
        $sender_receipient_list = Http::post(Config::get('constants.MONEY_TRANSFER.GET_RECEIPIENT_LIST'), $requestBody);
        $sender_receipient_list = isset($sender_receipient_list) && $sender_receipient_list ? $sender_receipient_list->json() : [];
        // END get recipent details

        // print_r($response);
        $operator_name = OperatorSetting::where('operator_id', $request->operator_id)->pluck('operator_name')->first();
        $data['operator_name'] =$request->operator_name;
        
        $data['page_name'] = "sender_details";
        $data['mobile_no'] = $request->mobile_no;
        if ($sender_dtls['status'] == 'false') {
            $data['error'] = $sender_dtls['msg'];
            $data['page_name'] = "register_mobile_no";
        }
        else{
            $data['success'] = 'Sender is created';
            $request->merge(["sender_mobile_number"=>$request->mobile_no]);
        //  return $request;
        // $request->merge(["sender_acc_number"=> ""]);
       // return redirect('get_sender_details', [$request]);
        
          //  return redirect('/money_transfer')->with('sender_mobile_number','operator_name');
        }
       //  $request->merge(["sender_mobile_number"=>$request->mobile_no]);
        // $request->merge(["sender_acc_number"=> ""]);
        // $this->getSenderDetails($request);

        // exit();
        
        //return view("modules.service_type.mobile_no_money_trans", compact('data', 'sender_dtls', 'sender_receipient_list'));
        
        /*Get sender code starts*/
        
    
        $operator_id='';
        $receipient_api = Config::get('constants.MONEY_TRANSFER.GET_RECEIPIENT_LIST');
        if ($request->operator_name == 'SMART_MONEY') {
            $operator_id= 40;  

        }elseif($request->operator_name == 'CRAZY_MONEY') {

            // $operator_id= 21;
            $operator_id= 40;  

        }elseif($request->operator_name == 'BHIM_UPI') {
            $operator_id = 42;
            $receipient_api = Config::get('constants.MONEY_TRANSFER.GET_RECEIPIENT_LIST_UPI');

        }

        //START get sender details
        $requestBody = [
            'sender_mobile_number' => $request->sender_mobile_number,
            'token' => UserLoginSessionDetail::getUserApikey(Auth::user()->userId) ? UserLoginSessionDetail::getUserApikey(Auth::user()->userId) : '',
            'user_id' => Auth::user()->userId,
            'role_id' => Auth::user()->roleId,
            'operatorID' => $operator_id
        ];
      
        $data=[];
        $data['requestBody'] = json_encode($requestBody);
        // print_r(json_encode($requestBody));die();
        if (isset($request->sender_mobile_number)) {
            $requestBody['sender_mobile_number'] = $request->sender_mobile_number;
            $data['page_name'] = "sender_details";
        }elseif (isset($request->sender_acc_number)) {
            $requestBody['account_number'] = $request->sender_acc_number;
            $data['page_name'] = "sender_details_acc_no";

        }
         //delete beneficiary
        //  $request->action = 'deleted';
        if (($request->action) && ($request->action == 'delete') ){
            // $request->action = '';
            $request->merge(['action' => 'deleted']);

            $delete_response = $this->deleteBeneficiary($request,  $requestBody);
            if ($delete_response['status']=='true') {
                $data['success'] = $delete_response['msg'];
                
            }else {
                    $data['error'] = $delete_response['msg'];
                    
            }
            
        }

        $data ['request']= $request->all();

        $sender_dtls = Http::post(Config::get('constants.MONEY_TRANSFER.GET_SENDER_DTLS_API'), $requestBody);
        $sender_dtls = isset($sender_dtls) && $sender_dtls ? $sender_dtls->json() : [];
        //END get sender details

        // START get recipent details
        $sender_receipient_list = Http::post($receipient_api, $requestBody);
        
        $sender_receipient_list = isset($sender_receipient_list) && $sender_receipient_list ? $sender_receipient_list->json() : [];
        // END get recipent details
        
        $sender_by_acc = Http::post(Config::get('constants.WEBSITE_BASE_URL').'admin/index.php/MoneyTransferApi/get_sender_byaccountnum', $requestBody);
        // print_r($sender_by_acc->json());die();
        $sender_by_acc = isset($sender_by_acc) && $sender_by_acc ? $sender_by_acc->json() : [];


        // print_r($requestBody);
        // exit();
        //bank List
        // $data['bankList'] = Http::post(Config::get('constants.MONEY_TRANSFER.GET_BANK_LIST') , $requestBody);
        // $data['bankList'] = isset($data['bankList']) && $data['bankList'] ? $data['bankList']->json() : [];

        $data['bankList'] = $this->getAllBankList();

        // print_r($data['bankList']);
       
        $data['bank_ifsc']=[];
        foreach ($data['bankList']['result']['bank_list'] as $key => $value) {
            $data['bank_ifsc'][$value['bank_code']] = $value['ifsc_prefix'];
        }


        // print_r($response);
        $data['operator_name'] =$request->operator_name;
        // print_r($request->all());
        // print_r($sender_dtls);
        // exit();
        $data['mobile_no'] = $request->sender_mobile_number;
        if ($sender_dtls['status'] == 'false') {
            $data['page_name'] = "register_mobile_no";
            $data['error'] = "Sender Not Found of this mobile no. Please Register it!!";
        }
        if ((isset($request->sender_acc_number)) && ($sender_by_acc['status']== 'true') ){
            $data['page_name'] = "sender_details_acc_no";
            if (count($sender_by_acc['result']) < 1) {
                
                // $data['error'] = "No Account has Registered with this Account Number. Please Register it!!";
                $data['error'] = "No Record Found";
               
            }else{
                $data['error'] = null;
            }
            
        }
        
        // print_r($sender_by_acc);
        // exit();
        

        return view("modules.service_type.mobile_no_money_trans", compact('data', 'sender_dtls', 'sender_receipient_list', 'sender_by_acc'));
       
    
        
        /*Ends */
    }

    public function transferMoney(Request $request){
        //  print_r($request->all());
        // exit();
       


        //if refresh success page then it will go money_transfer
        if( Session::has('payment_status') )  {
            return redirect('/money_transfer');
        }
        
        $opt_const = 'constants.MONEY_TRANSFER_OPERATOR.'.$request->operator_name;
        $operator_id = OperatorSetting::where('operator_name', Config::get($opt_const))->pluck('operator_id')->first();
        $receipientDtls = DB::table('tbl_dmt_benificiary_dtls')->where('recipient_id', $request->benificiary)->get()->first();


        $data['requestBody'] = [
                                'sender_mobile_number' => $request->mobile_no,
                                'token' => UserLoginSessionDetail::getUserApikey(Auth::user()->userId),
                                'user_id' => Auth::user()->userId,
                                'role_id' => Auth::user()->roleId,
                                'operatorID' => $operator_id,
                                'bank_account_number' => $receipientDtls->bank_account_number,
                                'bank_code' => $receipientDtls->bank_code,
                                'ifsc' =>$receipientDtls->ifsc,
                                'reference_number' =>'121'
                            ];

       $data['mobile_no'] = $request->mobile_no;
       $data['operator_name'] = $request->operator_name;
       $data['page_name'] = "transfer_form";
        $money_transfer_response=[];
       if (isset($request->mpin)) {

          return $hello="hii";
       
            $money_transfer_response = $this->doMoneyTransfer($request);
            // print_r($request->all());
            // print_r($money_transfer_response);
            // exit;
            Session::put('payment_status', 'called' );
            
            
            if ($money_transfer_response['status'] == "true"){
                
                if (array_key_exists("order_no",$money_transfer_response['money'])){
                    $data['order_id'] =$money_transfer_response['money']['order_no'];
                }else{
                    $data['order_id'] =  $money_transfer_response['money'][0]['order_no'];
                }
                return view("modules.service_type.success_money_trans", compact('receipientDtls', 'data', 'money_transfer_response', 'request'));
               
                
            } elseif ($money_transfer_response['status'] == "false") {
                $data['error'] = $money_transfer_response['msg'];
                return view("modules.service_type.process_money_trans", compact('receipientDtls', 'money_transfer_response', 'data', 'request'));
            }
        
       }

       if ($receipientDtls) {
        //    print_r($receipientDtls);
            if ($request->operator_name == 'CRAZY_MONEY') {
                $opt_const = 'constants.MONEY_TRANSFER_OPERATOR.SMART_MONEY';
                $operator_id = OperatorSetting::where('operator_name', Config::get($opt_const))->pluck('operator_id')->first();
                $data['requestBody']['smartpay'] = $operator_id;
            }
            $data['page_name'] = "transfer_form";
            return view("modules.service_type.process_money_trans", compact('receipientDtls', 'data', 'request'));

       }
       return redirect('/money_transfer')->with('error', 'Benificiary Not Found');

    }

    public function doMoneyTransfer( $request, $isAPI=''){

        $opt_const = 'constants.MONEY_TRANSFER_OPERATOR.'.$request->operator_name;
        $operator_id = OperatorSetting::where('operator_name', Config::get($opt_const))->pluck('operator_id')->first();


        $reqBody = [];
        if ($isAPI == 'API') {
            $reqBody = [
                "sender_mobile_number"=> $request->mobile_no,
                "token"=>  $request->token,
                "user_id"=> $request->user_id,
                "role_id"=> $request->role_id,
                "recipient_id"=> $request->benificiary,
                "transaction_amount"=> $request->transfer_amount,
                "transaction_type"=> $request->trans_type,
                "operatorID"=> $operator_id,
                "mpin"=> $request->mpin,
                "access_type"=> 'WEB'
               

            ];
        } else {
            $reqBody = [
                "sender_mobile_number"=> $request->mobile_no,
                "token"=>  UserLoginSessionDetail::getUserApikey(Auth::user()->userId),
                "user_id"=> Auth::user()->userId,
                "role_id"=> Auth::user()->roleId,
                "recipient_id"=> $request->benificiary,
                "transaction_amount"=> $request->transfer_amount,
                "transaction_type"=> $request->trans_type,
                "operatorID"=> $operator_id,
                "mpin"=> $request->mpin,

            ];
        }
        
        $transfer_api = Config::get('constants.MONEY_TRANSFER.FUND_TRN_API');
        if ($request->operator_name == 'BHIM_UPI') {
            $reqBody['transaction_type'] = 'UPI';
            $transfer_api = Config::get('constants.MONEY_TRANSFER.FUND_TRN_API_UPI');
        }
        $money_transfer_result = Http::post($transfer_api, $reqBody);
        //$money_transfer_result = (isset($money_transfer_result) && $money_transfer_result) ? $money_transfer_result->json() : [];
        // $money_transfer_str = '{"status":"true","msg":"Success","result":{"status":"SUCCESS","statusCode":"DE_001","statusMessage":"Successful disbursal to Bank Account is done","result":{"mid":"SMARTP98462961075221","orderId":"SP43942","paytmOrderId":"202104201207104363553940","amount":"1.00","commissionAmount":"3.00","tax":"0.54","rrn":"111012803504","beneficiaryName":null,"isCachedData":null,"cachedTime":null,"reversalReason":null}},"money":{"order_no":"SP43942","trans_date":"2021-04-20 12:07:16","name":"SMARTPAY TECHNOLOGIE","sender_no":"9970898880","mode":"IMPS","amount":"1","charge_amount":"0","service_name":"MONEY TRANSFER","bank_transaction_id":"202104201207104363553940","order_status":"SUCCESS","remarks":"","CCFcharges":"10","Cashback":"2","TDSamount":"0","PayableCharge":"8","FinalAmount":"9","bank_account_number":"630505034125","ifsc":"ICIC0006305"}}';
        // $money_transfer_result = json_decode($money_transfer_str, true);
        $money_transfer_result=$money_transfer_result->json();
        return $money_transfer_result;
        
    }

    public function doMoneyTransfer_API(Request $request){
        $money_transfer_response = $this->doMoneyTransfer($request, 'API');
        // print_r($money_transfer_response);
        return json_encode($money_transfer_response);
    }
    public function addBeneficiary(Request $request){
   
        $data['page_name'] = "add_beneficiary";
        $data['mobile_no'] = $request->mobile_no;
        $data['operator_name'] = $request->operator_name;
        $opt_const = 'constants.MONEY_TRANSFER_OPERATOR.'.$request->operator_name;
        $operator_id = OperatorSetting::where('operator_name', Config::get($opt_const))->pluck('operator_id')->first();
        $reqBody = [
                        "sender_mobile_number" => $request->mobile_no,
                        "token" =>  UserLoginSessionDetail::getUserApikey(Auth::user()->userId),
                        "user_id" => Auth::user()->userId,
                        "role_id" => Auth::user()->roleId,
                        "operatorID"=> $operator_id
                    ];


        $data['bankList'] = Http::post(Config::get('constants.MONEY_TRANSFER.GET_BANK_LIST') , $reqBody);
        $data['bankList'] = isset($data['bankList']) && $data['bankList'] ? $data['bankList']->json() : [];
        $data['bank_ifsc']=[];
       
        if ($data['bankList']['status'] =='false' ) {
            $data['error'] = $data['bank_ifsc']['msg'];
            return redirect('/money_transfer');
        }
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
       
        return view("modules.service_type.mobile_no_money_trans", compact('data', 'request'));
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

    public function deleteBeneficiary($request, $reqBody){

        $reqBody['recipient_id'] = $request->delete_beneficiary_id;

        $response = Http::post(Config::get('constants.MONEY_TRANSFER.DELETE_RECEP_API') , $reqBody);
        $response = isset($response) && $response ? $response->json() : [];
        return $response; 
    }

    public function deleteBeneficiaryAPI($response_status, $response_msg, Request $request){
        $response_msg=str_replace("_", " ", $response_msg);
        if ($response_status == 'true') { 
            Session::put('success_msg', $response_msg );
        }else {
            Session::put('error_msg', $response_msg );
        }
        
        return true;

       

        // $reqBody['recipient_id'] = $request->delete_beneficiary_id;

        // $response = Http::post(Config::get('constants.MONEY_TRANSFER.DELETE_RECEP_API') , $reqBody);
        // $response = isset($response) && $response ? $response->json() : [];
        // return $response; 
    }

    public function receiptData($order, $subcharge, Request $request){
        $response= [];
        $result = [];
        $transDtls = TransactionDetail::where('order_id', $order)->get()->first();

        if ($transDtls) {
            $userDtls = User::where('userId', $transDtls->user_id)->get()->first();
            $result['user_details']['store_name'] = $userDtls->store_name;
            $result['user_details']['mobile'] = $userDtls->mobile;
            $result['user_details']['email'] = $userDtls->email;

            $beneficiaryDtls = DB::table('tbl_dmt_benificiary_dtls')->where('recipient_id', $transDtls->recipient_id)->get()->first();
            $result['beneficiary']['sender_mobile_number'] = $beneficiaryDtls->sender_mobile_number;
            $result['beneficiary']['transfer_type'] = $transDtls->transaction_type;
            $result['beneficiary']['trans_date'] = $transDtls->trans_date;
            $result['beneficiary']['beneficiary_name'] = $beneficiaryDtls->recipient_name;
            $result['beneficiary']['account_no'] = $beneficiaryDtls->bank_account_number;
            if ($transDtls->transaction_type != 'UPI') {
                $result['beneficiary']['ifsc'] = $beneficiaryDtls->ifsc;
            }
            

            $result['basic_amount'] = 0;
            $result['subcharge'] = $subcharge;
            $result['total_amount'] =  0;
            // check multiple transfer
            if (isset($transDtls->group_id)) {
                $transDtls_bygroup = TransactionDetail::where('group_id', $transDtls->group_id)->get();
                if (count( $transDtls_bygroup)>0) {
                    foreach ($transDtls_bygroup as $key => $value) {
                        $result['transfer_records'][$key]['account_no'] =  $beneficiaryDtls->bank_account_number;
                        $result['transfer_records'][$key]['bank_transaction_id'] =  $transDtls->bank_transaction_id;
                        $result['transfer_records'][$key]['order_id'] =  $transDtls_bygroup->order_id;
                        $result['transfer_records'][$key]['transaction_status'] =  $transDtls_bygroup->transaction_status;
                        $result['transfer_records'][$key]['amount'] =  $transDtls_bygroup->total_amount;

                        $result['basic_amount'] =(float) $result['basic_amount'] + (float)$transDtls_bygroup->total_amount;
                       
                    }
                    $result['total_amount'] = (float) $result['basic_amount'] + (float) $result['subcharge'];
                }

            }else {
                $result['transfer_records'][0]['account_no'] =  $beneficiaryDtls->bank_account_number;
                $result['transfer_records'][0]['bank_transaction_id'] =  $transDtls->bank_transaction_id;
                $result['transfer_records'][0]['order_id'] =  $transDtls->order_id;
                $result['transfer_records'][0]['transaction_status'] =  $transDtls->transaction_status;
                $result['transfer_records'][0]['amount'] =  $transDtls->total_amount;
                $result['basic_amount'] = (float) $transDtls->total_amount;
                $result['total_amount'] = (float) $result['basic_amount'] + (float) $result['subcharge'];
            }

            
            if ($transDtls->transaction_type != 'UPI') {
                $response['operator'] = 'money_transfer'; 
            }else {
                $response['operator'] = 'upi_transfer'; 
            }
            $response['status'] = 'true';
            $response['message'] = 'Success';
            $response['result'] = $result;
        }else {
            $response['status'] = 'false';
            $response['message'] = 'Order Not found';
            $response['result'] = [];
        }
        

        return $response;
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
}
