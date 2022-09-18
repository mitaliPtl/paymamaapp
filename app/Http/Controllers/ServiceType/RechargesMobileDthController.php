<?php

namespace App\Http\Controllers\ServiceType;

use App\Http\Controllers\Controller;
use App\OperatorSetting;
use App\ServicesType;
use App\UserLoginSessionDetail;
use DB;
use Auth;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RechargesMobileDthController extends Controller
{
    // Operator API info
    protected $apiUsername = "G455289818";
    protected $apikey = "5351530667";
    protected $mobileInfoUrl = "https://mobilerechargenow.com/api/mobileinfo.php";
    protected $mobileRechPlnUrl = "https://mobilerechargenow.com/recharge-plan.php";
    protected $dthPlanUrl = "https://mobilerechargenow.com/dth-plan.php";

    // 121 Offers API Info
    protected $api121MemberId = "3617";
    // protected $api121MemberId = "441684";
    protected $api121Pwd = "Smartpay@8443";
    // protected $api121Pwd = "441684";
    protected $api121OffersUrl = "http://planapi.in/api/Mobile/RofferCheck";
    protected $api121MbInfoUrl = "http://planapi.in/api/Mobile/operatorFetchNew";
    protected $api121MbRchPlanUrl = "http://planapi.in/api/Mobile/Operatorplan";

    /**
     * Services View
     */
    public function index(Request $request)
    {
        $biller=[];
        $apiKey = UserLoginSessionDetail::getUserApikey(Auth::user()->userId);
        // $operatorList = $this->getOperatorData();
        $operatorList = OperatorSetting::with('servicesType')->where('is_deleted', Config::get('constants.NOT-DELETED'))->where('activated_status', Config::get('constants.ACTIVE'))->get();
        $paymentType = isset($request->type) && $request->type ? $request->type : "";
        $serviceList = Config::get('constants.RECHARGE_BILL_PAYENTS');

        // $operatorList = OperatorSetting::where('operator_name', $request->paymentType)->get();
       
           $biller =  $this->getBillers($apiKey, Auth::user()->userId, Auth::user()->roleId, $paymentType);
      
        // print_r($biller);
        // exit();
        $user_dtls = Auth::user();
        // $pay_result = json_decode($pay_result, true);
        return view("modules.service_type.recharges", compact('apiKey', 'paymentType', 'operatorList', 'serviceList', 'biller', 'user_dtls'));
    }

    

    /**
     * Get Operator Details
     */
    public function getOperatorData()
    {
        $result = [];
        $response = Http::post(Config::get('constants.OPERATOR.GET_LIST_URL'), [
            'token' => UserLoginSessionDetail::getUserApikey(Auth::user()->userId),
            'user_id' => Auth::user()->userId,
            'role_id' => Auth::user()->roleId,
        ]);

        if ($response) {
            $result = $response['result']['operatorInfo'];
        }

        return $result;
    }

    /**
     * Get Operator Mobile Info Details
     */
    public function getOperatorMobileInfo(Request $request)
    {
        $result = null;

        if (isset($request->mobile) && $request->mobile) {
            $response = Http::get($this->api121MbInfoUrl, [
                'ApiUserID' => $this->api121MemberId,
                'ApiPassword' => $this->api121Pwd,
                'Mobileno' => $request->mobile,
            ]);

            if ($response) {
                $result = $response;
            }
        }

        if (isset($request->request_from) && $request->request_from == "API") {

            $operator = [];

            if (isset($response) && $response['STATUS'] == "1" && $response['OpCode']) {
                $operator = OperatorSetting::select(['operator_id', 'operator_name'])
                    ->where('offers_121_op_code', $response['OpCode'])->first();
                if (!$operator) {
                    return $this->sendError("Operator not Found");
                }
            } else {
                return $this->sendError("Operator not Found");
            }

            $operator['circle'] = isset($response['CircleCode']) ? $response['CircleCode'] : 1;

            $result = $this->sendSuccess($operator, "Success!!");
        }
        return $result;
    }

    /**
     * Get Operator Mobile Recharge Plan Details
     */
    public function getOperatorRechargePlans(Request $request)
    {
        $result = null;

        if (isset($request->circle) && $request->circle) {
            $operator = "";
            if (isset($request->request_from) && $request->request_from == "API") {
                if (isset($request->operator_id) && $request->operator_id) {
                    $operator = OperatorSetting::where('operator_id', $request->operator_id)->pluck('offers_121_op_code')->first();
                    
                    if(!isset($operator)){
                        return $this->sendError("Invalid Operator Id!!");
                    }
                } else {
                    return $this->sendError("Invalid Operator Id!!");
                }
            } else {
                $operator = $request->operator;
            }

            $response = Http::get($this->api121MbRchPlanUrl, [
                'apimember_id' => $this->api121MemberId,
                'api_password' => $this->api121Pwd,
                'cricle' => $request->circle,
                'operatorcode' => $operator
            ]);

            if ($response) {
                $result = $response;
            }
        }

        $type = "";
        if(isset($request->type) && $request->type){
            if($request->type == "TUP"){
                $type = "TOPUP";
            }else if($request->type == "FTT"){
                $type = "FULLTT";
            }else if($request->type == "2G" || $request->type == "3G"){
                $type = "DATA";
            }else if($request->type == "SMS"){
                $type = "SMS";
            }else if($request->type == "LSC"){
                $type = "FRC";
            }else if($request->type == "RMG"){
                $type = "Romaing";
            }else if($request->type == "OTR"){
                $type = "STV";
            }else{
                return $this->sendError("Recharge plan not found!!");
            }
        }else{
            return $this->sendError("Please mention the type!!");
        }

        if (isset($request->request_from) && $request->request_from == "API") {
            if (isset($result['RDATA']) && isset($result['RDATA'][$type ])) {
                $planList = $this->modifyDthPlanData($result['RDATA'][$type ]);
                return $this->sendSuccess($planList, "Success!!");
            }else{
                return $this->sendError("Data not found!!");
            }
        }

        return $result;
    }

     /**
     * Modify DTH Plan Data
     */
    public function modifyDthPlanData($data)
    {
        $result = [];

        if ($data) {
            for ($i = 0; $i < count((array) $data); $i++) {
                $key=[];
                $key['amount'] = $data[$i]['rs'];
                $key['detail'] = $data[$i]['desc'];
                $key['talktime'] = "";
                $key['validity'] = $data[$i]['validity'];
                array_push($result, $key);
            }
        }

        return $result;
    }

    /**
     * Modify List for API Response
     */
    public function modifyAPIListData($data)
    {
        $result = [];

        if ($data) {
            for ($i = 0; $i < count((array) $data); $i++) {
                array_push($result, $data[$i]);
            }
        }

        return $result;
    }

    /**
     * Get DTH Plan Info
     */
    public function getDTHPlanInfo(Request $request)
    {
        $result = null;

        if (isset($request->operator_id) && $request->operator_id) {
            $operator = "";
            if (isset($request->request_from) && $request->request_from == "API") {
                $dthServiceId = ServicesType::where('alias', Config::get('constants.SERVICE_TYPE_ALIAS.DTH'))->pluck('service_id')->first();
                $operator = OperatorSetting::where('operator_id', $request->operator_id)
                    ->where('service_id', $dthServiceId)
                    ->pluck('offers_121_op_code')->first();
                if (!isset($operator)) {
                    return $this->sendError("Invalid Operator Id!!");
                }
            } else {
                $operator = $request->operator_id;
            }
            $response = Http::get($this->api121MbRchPlanUrl, [
                'apimember_id' => $this->apiUsername,
                'api_password' => $this->apikey,
                'operatorcode' => $operator,
            ]);

            if ($response) {
                $result = $response;
            }
        } else {
            return $this->sendError("Invalid Operator Id!!");
        }

        if (isset($request->request_from) && $request->request_from == "API") {
            if (isset($result) && $result) {
                $result = $this->modifyAPIListData($result);
                $result = $this->sendSuccess($result, "Success!!");
            } else {
                $result = $this->sendError("No plans available");
            }
        }

        return $result;
    }

    /**
     * Get one to One Offer info
     */
    public function get121OffersInfo(Request $request)
    {
        $result = null;

        if (isset($request->mobile)) {
            $operator = "";
            if (isset($request->request_from) && $request->request_from == "API") {
                if (isset($request->operator_id) && $request->operator_id) {
                    $operator = OperatorSetting::where('operator_id', $request->operator_id)->pluck('offers_121_op_code')->first();
                } else {
                    return $this->sendError("Invalid Operator Id!!");
                }
            } else {
                $operator = $request->operator_code;
            }

            $response = Http::get($this->api121OffersUrl, [
                'apimember_id' => $this->api121MemberId,
                'api_password' => $this->api121Pwd,
                'mobile_no' => $request->mobile,
                'operator_code' => $operator,
            ]);

            if ($response) {
                $result = $response;
            }

        }

        if (isset($request->request_from) && $request->request_from == "API") {
            if (isset($result['RDATA']) && $result['RDATA']) {
                $result = $this->sendSuccess($result['RDATA'], "Success!!");
            } else {
                $result = $this->sendError("No offers available");
            }

        }

        return $result;
    }

    /**
     * Get DTH Info API
     */
    public function getDTHAcInfo(Request $request)
    {
        $result = null;

        if (isset($request->mobile) && isset($request->operator_id)) {
            
            $url = Config::get('constants.WEBSITE_BASE_URL')."admin/index.php/RechargeApi/getDthInfo";
            $response = Http::post($url, [
                'user_id' => Auth::id(),
                'role_id' => Auth::user()->roleId,
                'token' => Auth::apiKey(),
                'mobileNumber' => $request->mobile,
                'operatorID' => $request->operator_id,
            ]);

            if ($response) {
                $result = $response;
            }

        }

        return $result;
    }

    public function payElectBill(Request $request){
        print_r($request->all());

        // $check_mpin = $this->verifyUserMpin($request->pay_user_id, $request->mpin);

        // if($check_mpin){
        //         $call_pay_bill = $this->payBillAPI($request);
        //         if($call_pay_bill['response']['status'] == true)

        // }else{
        //     return back()->with('error', 'MPIN Does Not Matched');
        // }

    }

    public function verifyUserMpin( $user_id, $mpin )
    {
        $response = User::where('userId', $user_id)->where('mpin', $mpin)->get();

        if (count($response) == 0) {
            return response()->json(0);
        }
        return response()->json("true");
    }

    public function payBillAPI($request){

        $apiUrl = Config::get('constants.BILL_PAYMENTS_API.ELECTRICITY.PAY_BILL');
        $curl = curl_init();

        curl_setopt_array($curl, array(CURLOPT_URL => $apiUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => '{
                                    "token" : "'.$request->pay_token.'",
                                    "user_id" : "'.$request->pay_user_id.'",
                                    "role_id" : "'.$request->pay_role_id.'",
                                    "billerID" : "'.$request->pay_biller_id.'",
                                    "billPayType": "normal",
                                    "orderId": "'.$request->pay_order_id.'",
                                    "amount":'.$request->amount.',
                                    "mpin":"'.$request->mpin.'",
                                    "operatorID": "17"
                                }',
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

        // print_r($req);
        // print_r($paymentType);

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

    public function getBillerInfo(Request $request){
        $result =[];
        $biller = DB::table('tbl_bbps_list')->where('billerId', $request->biller_id)->get();
        // print_r($biller);
        $result = $this->sendSuccess($biller, "Success!!");
        return  $result;
    }

    public function getBillerByStateCode(Request $request){

        if($request->biller_cat == "Electricity New") {
            $biller = DB::table('tbl_payrcnow');
            if ($request->city_name) {
                $biller = $biller->where('state_code', 'like', '%'. $request->state_code.'-'.$request->city_name.'%');
            }else {
                $biller = $biller->where('state_code', 'like', '%'. $request->state_code.'%');
            }
                            
                $biller = $biller->orderBy('biller_name','ASC')->get();
        } else {
            $biller = DB::table('tbl_bbps_list')->where('billerCategory','like', '%'.$request->biller_cat.'%');
            if ($request->city_name) {
                $biller = $biller->where('billerCoverage', 'like', '%'. $request->state_code.'-'.$request->city_name.'%');
            }else {
                $biller = $biller->where('billerCoverage', 'like', '%'. $request->state_code.'%');
            }
                            
                $biller = $biller->orderBy('billerName','ASC')->get();
         
            // $biller  = (count($biller)>0)? $biller ? [] ;
        }
            
        $result = $this->sendSuccess($biller, "Success");
        return  $result;
    }
 
    public function getCityByStateCode(Request $request){
        $cities = [];
        $state= DB::table('tbl_state_mst')->where('state_code', $request->state_code)->get()->first();
        if ($state) {
            $cities= DB::table('tbl_district_mst')->where('state_id', $state->state_id)->orderBy('city_name','ASC')->get();
            $cities = (count($cities)>0) ? $cities : [];
            $result = $this->sendSuccess($cities, "Success");
            return  $result;
        }
        $result = $this->sendSuccess($cities, "Success");
        return  $result;

    }
}
