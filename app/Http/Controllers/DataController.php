<?php

namespace App\Http\Controllers;
use App\Complaint;
use App\BalanceRequest;
use App\KycDetail;
use App\Role;
use App\TransactionDetail;
use App\ApiSetting;
use App\User;
use App\OperatorSetting;
use App\OffersNotice;
use App\ServicesType;
use Auth;
use DB;
use Config;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\UserLoginSessionDetail;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\HomeController;

class DataController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
   
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $totalUser = User::all()->count();
        $rTCount = User::where('roleId', Role::getIdFromAlias(Config::get('constants.ROLE_ALIAS.RETAILER')))->get()->count();
        $dTCount = User::where('roleId', Role::getIdFromAlias(Config::get('constants.ROLE_ALIAS.DISTRIBUTOR')))->get()->count();
        $fosCount = User::where('roleId', Role::getIdFromAlias(Config::get('constants.ROLE_ALIAS.FOS')))->get()->count();
        $totalFund = User::where('roleId', '!=', Role::getIdFromAlias(Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN')))->pluck('wallet_balance')->sum();
        // $totalApiBalance = ApiSetting::pluck('balance')->sum();
        $totalApiBalance = $this->totalAPISetting();
        $totalFundWithAdmin = User::pluck('wallet_balance')->sum();
        $newMembersCount = User::whereMonth('createdDtm', now()->month)->count();
        $pendingBalReq = BalanceRequest::where('status', "PENDING")->get()->count();
        $pendingKYCReq = KycDetail::where('status', "PENDING")->get()->count();
        $pendingComplaints = Complaint::where('complaint_status', "PENDING")->get()->count();

        $transaction['success'] = TransactionDetail::where('order_status', "SUCCESS")
        // ->whereMonth('trans_date', now()->month)
            ->get()->count();
        $transaction['pending'] = TransactionDetail::where('order_status', "PENDING")
        // ->whereMonth('trans_date', now()->month)
            ->get()->count();
        $transaction['failed'] = TransactionDetail::where('order_status', "FAILED")
        // ->whereMonth('trans_date', now()->month)
            ->get()->count();

        $serviceList = Config::get('constants.RECHARGE_BILL_PAYENTS');

        $isSetloginSession = $this->setUserLoginSession();

        //Recharge and Bill Payment
        $apiKey = UserLoginSessionDetail::getUserApikey(Auth::user()->userId);
        $operatorList = OperatorSetting::with('servicesType')->where('is_deleted', Config::get('constants.NOT-DELETED'))
                                ->where('activated_status', Config::get('constants.ACTIVE'))
                                ->leftJoin('tbl_files', 'tbl_operator_settings.operator_logo_file_id', '=', 'tbl_files.id')
                                ->get();
        $paymentType = isset($request->service) && $request->service ? $request->service : "mobile";
        $biller=[];
        $biller =  $this->getBillers($apiKey, Auth::user()->userId, Auth::user()->roleId, $paymentType);
        $user_dtls = Auth::user();
        //End Recharge and bill
        $biller_data = [];
        

        $biller_data['operator_id'] = OperatorSetting::where('operator_name', 'like', $paymentType)->get()->first();
                $biller_data['operator_id'] = $biller_data['operator_id']['operator_id'];
        if (empty($biller_data['operator_id']) ) {
            $biller_data['operator_id'] = Config::get('constants.SERVICE_ID.'.$paymentType);
        }
        if( ($paymentType == 'electricity') || ($paymentType == 'water') || ($paymentType == 'education') ) {
            $all_states = DB::table('tbl_state_mst')->where('country_id', '233')->get();
            $biller_data['all_states'] = (count($all_states)>0)? $all_states : [];
        }
        
        if( ($paymentType == 'electricity_new') ) {
            \DB::statement("SET SQL_MODE=''");
            $all_states = DB::table('tbl_payrcnow')->groupBy('state_name')->orderBy('state_name','ASC')->get();
            $biller_data['all_states'] = (count($all_states)>0)? $all_states : [];
            
            $biller_data['operator_id'] = OperatorSetting::where('operator_name', 'like', 'electricity')->get()->first();
            $biller_data['operator_id'] = $biller_data['operator_id']['operator_id'];
            if (empty($biller_data['operator_id']) ) {
                $biller_data['operator_id'] = Config::get('constants.SERVICE_ID.'.$paymentType);
            }
        }

        $reqBody_slider = [
                            "token"=>  $apiKey,
                            "user_id"=> $user_dtls->userId,
                            "role_id"=> $user_dtls->roleId,
                            "type"=> "WEBSITE",
                            ];
        $homeSlider = Http::post( Config::get('constants.HOME_BANNER_SLIDER') , $reqBody_slider);
        $homeSlider = (isset($homeSlider) && $homeSlider) ? $homeSlider->json() : [];
        // print_r($homeSlider);die();
        $all_offers_notice = OffersNotice::where('notice_isDeleted','0')->where('notice_type',Config::get('constants.OFFERS_NOTICE_TYPE.ALERT'))->get();
        $all_offers_notice = $this->modified_RT_DT($all_offers_notice,$user_dtls->roleId);
        return view('home_new', compact('homeSlider','biller_data','biller','apiKey','operatorList','paymentType', 'serviceList', 'pendingBalReq', 'totalUser', 'rTCount', 'dTCount', 'fosCount', 'pendingKYCReq', 'totalFundWithAdmin', 'totalFund', 'newMembersCount', 'transaction','totalApiBalance', 'pendingComplaints', 'all_offers_notice'));
    }
    
    public function modified_RT_DT($records, $role_id){
        $result = [];
        $index = 0;
        foreach ($records as $record_key => $record_value) {
           $row =  json_decode($record_value['notice_visible'], true);

           if (in_array($role_id, $row))
           {
                $result[$index] = $record_value;
                $index++;
           }
        }

        return $result;
    }

    public function totalAPISetting(){
        $totalApiBalance =0;
        $apis =  ApiSetting::get();
        foreach ($apis as $key => $value) {
            $blnc = 0;
           if ( isset($value->balance) && $value->balance ) {
            $blnc = $value->balance;
           }

           $totalApiBalance = $totalApiBalance + (float) $blnc;
        }
        return $totalApiBalance;
    }

    public function index_new()
    {
        $totalUser = User::all()->count();
        $rTCount = User::where('roleId', Role::getIdFromAlias(Config::get('constants.ROLE_ALIAS.RETAILER')))->get()->count();
        $dTCount = User::where('roleId', Role::getIdFromAlias(Config::get('constants.ROLE_ALIAS.DISTRIBUTOR')))->get()->count();
        $fosCount = User::where('roleId', Role::getIdFromAlias(Config::get('constants.ROLE_ALIAS.FOS')))->get()->count();
        $totalFund = User::where('roleId', '!=', Role::getIdFromAlias(Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN')))->pluck('wallet_balance')->sum();
        $totalApiBalance = ApiSetting::pluck('balance')->sum();
        $totalFundWithAdmin = User::pluck('wallet_balance')->sum();
        $newMembersCount = User::whereMonth('createdDtm', now()->month)->count();
        $pendingBalReq = BalanceRequest::where('status', "PENDING")->get()->count();
        $pendingKYCReq = KycDetail::where('status', "PENDING")->get()->count();
        $pendingComplaints = Complaint::where('complaint_status', "PENDING")->get()->count();

        $transaction['success'] = TransactionDetail::where('order_status', "SUCCESS")
        // ->whereMonth('trans_date', now()->month)
            ->get()->count();
        $transaction['pending'] = TransactionDetail::where('order_status', "PENDING")
        // ->whereMonth('trans_date', now()->month)
            ->get()->count();
        $transaction['failed'] = TransactionDetail::where('order_status', "FAILED")
        // ->whereMonth('trans_date', now()->month)
            ->get()->count();

        $serviceList = Config::get('constants.RECHARGE_BILL_PAYENTS');

        $isSetloginSession = $this->setUserLoginSession();
        return view('home', compact('serviceList', 'pendingBalReq', 'totalUser', 'rTCount', 'dTCount', 'fosCount', 'pendingKYCReq', 'totalFundWithAdmin', 'totalFund', 'newMembersCount', 'transaction','totalApiBalance', 'pendingComplaints'));
    }

    /**
     * Set User's Login Session
     */
    public function setUserLoginSession()
    {   
        $response =null;
        $loginSession = UserLoginSessionDetail::where('user_id', Auth::id())
            ->where('role_id', Auth::user()->roleId)->get()->first();

        if (isset($loginSession) && $loginSession) {
            // $loginSession->apiKey =  Str::random(32);
            // $response = $loginSession->save();
        } else {
            $response = UserLoginSessionDetail::create([
                'user_id' => Auth::id(),
                'role_id' => Auth::user()->roleId,
                'apiKey' => Str::random(32),
            ]);
        }
        return  $response;
    }

    /**
     * View for Permission denied page
     */
    public function permissionDenied()
    {
        return view('layouts.security.no-permission');
    }
    
    public function verify2fa()
    {
        return view('layouts.security.verify-two-factor');
    }
    
    public function check2fa(Request $request)
    {
        // print_r($request->all());die();
        $message = "INVALID CODE";
        if(isset($request->otp) && $request->otp && Session::has('userId') && Session::get('username')  && Session::get('password')) {
            $userId = Session::get('userId');
            $user = User::where('userId', $userId)->first();
            $roleId = $user->roleId;
            if($user) {
                $ga = new \App\Packages\Authenticator\Authenticator();
                $backup_pass = false;
        		$otp = $request->otp;
        		$checkResult = $ga->verify($user->tfa_secret, $otp);
        		if($user->tfa_codes) {
        		    $backup_pass = false;
        			$backup_codes = explode(',' , $user->tfa_codes);
        			if (in_array($otp, $backup_codes)) {
        			    $backup_pass = true;
        				$key = array_search($otp, $backup_codes);
        				unset($backup_codes[$key]);
        				$user->tfa_codes = implode(',' , $backup_codes);
        			}
        		}
        		if($checkResult || $backup_pass == true) {
        		    $credentials = array("username"=>Session::get('username'),"password"=>Session::get('password'));
        		    Auth::attempt($credentials);
        		    $user->last_login_ip = $this->getRealIpAddr();
        		    $user->save();
        		    Session::forget('userId');
        		    Session::forget('username');
        		    Session::forget('password');
        		    if ($roleId == Config::get('constants.ADMIN')) {
        		        
        		        return redirect()->route('admin-home');
        		    } else {
        		        return redirect('/home');
        		    }
        		}
            }
        }
        return redirect('verify-2fa')->with('message', $message);
    }
    
    public function getRealIpAddr() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
          $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
          $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
     	} else {
          $ip = $_SERVER['REMOTE_ADDR'];
        }
        // return $ip;
        $ips = explode(',', $ip);
        return $ips[0];
    }

    public function getBillers($apiKey, $user_id, $role_id, $paymentType){

        $operator_alise = 'constants.SERVICE_ID.'.$paymentType;
        // $op_id = Config::get($operator_alise);

        $apiUrl = Config::get('constants.BILL_PAYMENTS_API.ELECTRICITY.GET_BILLER_LIST');
        // print_r($apiUrl);
        $req = '{
            "token" : "'.$apiKey.'",
            "user_id" : "'.$user_id.'",
            "role_id" : "'.$role_id.'",
            "operatorID": "'.Config::get($operator_alise).'"
            
        }';

        $curl = curl_init();

        curl_setopt_array($curl, array(CURLOPT_URL => $apiUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $req,
        CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: application/json",
            "postman-token: 8956c2ad-a7ce-3670-4189-a610d23829c5"
        ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        return  $err;
        } else {
          return json_decode($response, true);
        }
    }

    public function getStates(Request $request){
        $result = [];
        return $all_states = DB::table('tbl_state_mst')->where('country_id', '233')->get();

        if (count($all_states)) {
            $result = $this->sendSuccess($all_states, "Success!!");
            // print_r($biller);
        } else {
            $result = $this->sendError("Not available");
            // print_r('error');

        }
        return  $result;
    }

    public function showInvoice($order, $subcharge, Request $request){
        $data = [];
        $transDtls = TransactionDetail::where('order_id', $order)->get()->first();
        
        $service_type = ServicesType::where('service_id', $transDtls['service_id'])->pluck('alias')->first();
        

        if( ( $service_type == 'money_transfer' ) || ( $service_type == 'upi_transfer' ) ){
            $surcharge = $subcharge;
            // $this->showInvoiceDMT($order, $subcharge, $transDtls);
            $ttl_amt=0;
            $tranDtls_gid=[];
            $user_id = Auth::user()->userId;
            // $appDetail = ApplicationDetail::select(['alias','value'])->get(); 
            $tranDtls = TransactionDetail:: leftJoin('tbl_dmt_benificiary_dtls', 'tbl_transaction_dtls.recipient_id', '=', 'tbl_dmt_benificiary_dtls.recipient_id')
                            // ->leftJoin('tbl_users', 'tbl_transaction_dtls.user_id', '=', 'tbl_users.userId')
                            ->where('id_deleted', Config::get('constants.NOT-DELETED'))
                            ->where('tbl_transaction_dtls.user_id', $user_id)
                            ->where('tbl_transaction_dtls.order_id',$order)
                            // ->limit(1)
                            ->get();
            if (isset($tranDtls) && count($tranDtls) > 0) {
                
                
                if(!empty($tranDtls[0]['group_id']) && $tranDtls[0]['group_id']){

                    $grp_id = $tranDtls[0]['group_id'];
                    $tranDtls_gid = TransactionDetail:: leftJoin('tbl_dmt_benificiary_dtls', 'tbl_transaction_dtls.recipient_id', '=', 'tbl_dmt_benificiary_dtls.recipient_id')
                                                        // ->leftJoin('tbl_users', 'tbl_transaction_dtls.user_id', '=', 'tbl_users.userId')
                                                        ->where('tbl_transaction_dtls.user_id', $user_id)
                                                        ->where('id_deleted', Config::get('constants.NOT-DELETED'))
                                                        ->where('tbl_transaction_dtls.group_id', $grp_id)
                                                        ->get();
                    foreach($tranDtls_gid as $tranDtls_gid_key => $tranDtls_gid_value){
                    $ttl_amt = (int)$ttl_amt + (int) $tranDtls_gid_value['total_amount'];
                    }
                }else{
                    $ttl_amt = (int)$tranDtls[0]['total_amount'];
                }

            $user = User::where("userId", "=", $user_id)->get() ;
           
            // $response = $this->exportPDF($tranDtls, $tranDtls_gid, $surcharge, $ttl_amt, $user);

            $final_amt = (int)$surcharge +(int)$ttl_amt ;
            $user = $user[0];
            $tranDtls = $tranDtls[0];
            $fileName = $user['first_name'];
            
            return view('modules.service_type.reciptDMT', compact('tranDtls', 'tranDtls_gid', 'surcharge', 'final_amt', 'fileName', 'user', 'ttl_amt'));
            }
        }else {
            
        
        
            $user_dtls =Auth::user();
            $data['user_details'] = [
                                        "shop_name"=> $user_dtls['store_name'],
                                        "email"=> $user_dtls['email'],
                                        "mobile"=> $user_dtls['mobile'],

                                ];

            $resp_msg = json_decode( $transDtls['response_msg'], true);

            $consumer_dtls['customer_name'] = (isset($resp_msg['RespCustomerName'])) ? $resp_msg['RespCustomerName'] : '';
            $consumer_dtls['bill_date'] = (isset($resp_msg['RespBillDate'])) ? $resp_msg['RespBillDate'] : '';
            $consumer_dtls['bill_no'] = (isset($resp_msg['RespBillNumber'])) ? $resp_msg['RespBillNumber'] : '';
            if ($transDtls['billerID']) {
                $biller_name = DB::table('tbl_bbps_list')->where('billerId', $transDtls['billerID'])->pluck('billerName')->first();
                $consumer_dtls['biller_name'] =  (isset($biller_name)) ? $biller_name : '';
            }

            $data['consumer_dtls'] = $consumer_dtls;
            $data['inputParams'] = (isset($resp_msg['inputParams']['input'])) ? $resp_msg['inputParams']['input'] : [];

            $transaction_id = (isset($resp_msg['txnRefId']) ) ? $resp_msg['txnRefId'] : '';
            $status = (isset($resp_msg['responseReason']) ) ? $resp_msg['responseReason'] : '';
            $amount = (isset($transDtls['basic_amount']) ) ?  $transDtls['basic_amount']  : '';

            $data['bill_row'] = [
                                    "transaction_id" => $transaction_id ,
                                    "order_id" => $order,
                                    "status" => $status,
                                    "amount" => $amount
                                ];
                            
            $data['subcharge'] = $subcharge;
            $data['basic_amount'] = $amount;
            $data['total_amount'] = $amount + $subcharge;
            
        
            // print_r($data);
            
            
            return view('modules.service_type.recipt', compact('data'));
        }
    }

    
    public function showInvoiceDMT($order_id, $surcharge, $transDtls ){
        // $result = [];
        $ttl_amt=0;
        $tranDtls_gid=[];
        $user_id = Auth::user()->userId;
        
        $tranDtls = TransactionDetail:: leftJoin('tbl_dmt_benificiary_dtls', 'tbl_transaction_dtls.recipient_id', '=', 'tbl_dmt_benificiary_dtls.recipient_id')
                        // ->leftJoin('tbl_users', 'tbl_transaction_dtls.user_id', '=', 'tbl_users.userId')
                        ->where('id_deleted', Config::get('constants.NOT-DELETED'))
                        ->where('tbl_transaction_dtls.user_id', $user_id)
                        ->where('tbl_transaction_dtls.order_id',$order_id)
                        // ->limit(1)
                        ->get();
        if (isset($tranDtls) && count($tranDtls) > 0) {
            
            
            if(!empty($tranDtls[0]['group_id']) && $tranDtls[0]['group_id']){

                $grp_id = $tranDtls[0]['group_id'];
                $tranDtls_gid = TransactionDetail:: leftJoin('tbl_dmt_benificiary_dtls', 'tbl_transaction_dtls.recipient_id', '=', 'tbl_dmt_benificiary_dtls.recipient_id')
                                                    // ->leftJoin('tbl_users', 'tbl_transaction_dtls.user_id', '=', 'tbl_users.userId')
                                                    ->where('tbl_transaction_dtls.user_id', $user_id)
                                                    ->where('id_deleted', Config::get('constants.NOT-DELETED'))
                                                    ->where('tbl_transaction_dtls.group_id', $grp_id)
                                                    ->get();
                foreach($tranDtls_gid as $tranDtls_gid_key => $tranDtls_gid_value){
                   $ttl_amt = (int)$ttl_amt + (int) $tranDtls_gid_value['total_amount'];
                }
            }else{
                $ttl_amt = (int)$tranDtls[0]['total_amount'];
            }

            $user = User::where("userId", "=", $user_id)->get() ;
           
            // $response = $this->exportPDF($tranDtls, $tranDtls_gid, $surcharge, $ttl_amt, $user);

            $final_amt = (int)$surcharge +(int)$ttl_amt ;
            $user = $user[0];
            $tranDtls = $tranDtls[0];
            $fileName = $user['first_name'];
            
            return view('modules.service_type.reciptDMT', compact('tranDtls', 'tranDtls_gid', 'surcharge', 'final_amt', 'fileName', 'user', 'ttl_amt'));
            
        } 

    }
    
    public function qr($id) {
        header ('Content-Type: image/png');
        echo file_get_contents("https://api.apiclub.in/api/fetch_qr/".$id);
    }
    
    public function tester() {
        return User::getIdbyUsername("DT10007");
    }


}
