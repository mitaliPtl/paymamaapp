<?php

namespace App\Http\Controllers\Settings;

use App\ApiSetting;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Reports\TransactionReportsController;
use App\OperatorDetail;
use App\TransactionDetail;
use App\User;
use App\WalletTransactionDetail;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use DateTime;

class ApiConfigurationController extends Controller
{
    
    /**
     * Mobile Recharge API using Robotics API Service
     */
    public function rechargeByMRobotics($request, $api, $srvOpCode = null)
    {
        $response = null;

        $requestBody = [
            'api_token' => $api->api_token,
            // 'mobile_no' => $request->mobileno,
            // 'amount' => $request->total_amount,
            // 'company_id' => $srvOpCode,
            'order_id' => $request->order_id,
            // 'is_stv' => false,
        ];
      
        
        $response = Http::post($api->api_trn_status_url, $requestBody);
       
        
        $response = isset($response) && $response ? $response->json() : [];
        
        $success['actual_request'] = $requestBody;
        $success['actual_response'] = $response;
        $success['message'] = isset($response['data']['response']) ? $response['data']['response'] : '';
       
        if (isset($response['data']['status']) && $response['data']['status'] == "success") {
            $success['message'] = $response['data']['response'];
            $success['transaction_status'] = 'SUCCESS';
            $success['mobile_no'] = $response['data']['mobile_no'];
            $success['transaction_id'] = $response['data']['tnx_id'];
            $success['api_balance'] = isset($response['data']['balance']) ? '0' : '0';

            $result['status'] = true;
            $result['result'] = $success;
           
            return $result;
           
        } else if (isset($response['data']['status']) && $response['data']['status'] == "pending") {
            $success['message'] = $response['data']['response'];
            $success['transaction_status'] = 'PENDING';
            $success['mobile_no'] = $response['data']['mobile_no'];
            $success['transaction_id'] = $response['data']['tnx_id'];
            $success['api_balance'] = isset($response['data']['balance']) ? '0' : '0';

            $result['status'] = true;
            $result['result'] = $success;

            return $result;
        } else if (isset($response['data']['status']) && $response['data']['status'] == "failure") {
            $success['message'] = isset($response['data']['response']) ? $response['data']['response'] : 'FAILED';
            $success['transaction_status'] = 'FAILED';
            $success['mobile_no'] = isset($response['data']['mobile_no']) ? $response['data']['mobile_no'] : '';
            $success['transaction_id'] = isset($response['data']['tnx_id']) ? $response['data']['tnx_id'] : '';
            $success['api_balance'] = isset($response['data']['balance']) ? '0' : '0';

            $result['status'] = true;
            $result['result'] = $success;

            return $result;
        } else {
           
            $result['status'] = false;
            $success['transaction_status'] = 'PENDING';
            $result['result'] = $success;
            $result['actual_response'] = $response;

            return $result;
        }
       
    }


    /**
     * Mobile Recharge API using Techno Payment  Service
     */
    public function rechargeByTechnoPayment($request, $api, $srvOpCode = null)
    {
        
        $response = null;
       
        $requestBody = [
            'UserID' => $api->username,
            'Token' => $api->api_token,
            'RPID' => $request->transaction_id,
            'AgentID' => $request->order_id,
            'Format' => "1",
        ];
        
        $response = Http::get($api->api_trn_status_url, $requestBody);
        
        
        $response = isset($response) && $response ? $response->json() : [];

        $success['actual_request'] = $requestBody;
        $success['actual_response'] = $response;
        $success['message'] = isset($response['msg']) ? $response['msg'] : '';

        if (isset($response['status']) && $response['status'] == "2") {
            $success['message'] = $response['msg'];
            $success['transaction_status'] = 'SUCCESS';
            $success['mobile_no'] = $response['account'];
            $success['transaction_id'] = $response['rpid'];
            $success['api_balance'] = $response['bal'];

            $result['status'] = true;
            $result['result'] = $success;

            return $result;
        } else if (isset($response['status']) && $response['status'] == "1") {
            $success['message'] = $response['msg'];
            $success['transaction_status'] = 'PENDING';
            $success['mobile_no'] = $response['account'];
            $success['transaction_id'] = $response['rpid'];
            $success['api_balance'] = $response['bal'];

            $result['status'] = true;
            $result['result'] = $success;

            return $result;
        } else if (isset($response['status']) && $response['status'] == "3") {
            $success['message'] = isset($response['msg']) ? $response['msg'] : '';
            $success['transaction_status'] = 'FAILED';
            $success['mobile_no'] = isset($response['account']) ? $response['account'] : '';
            $success['transaction_id'] = isset($response['rpid']) ? $response['rpid'] : '';
            $success['api_balance'] = isset($response['bal']) ? $response['bal'] : '';

            $result['status'] = true;
            $result['result'] = $success;

            return $result;
        } else {
            $result['status'] = false;
            $success['transaction_status'] = 'PENDING';
            $result['result'] = $success;

            return $result;
        }
    }
    
    /**
     * Mobile Recharge API using Ambika API Service
     */
    public function rechargeByAmbika($request, $api, $srvOpCode = null)
    {
        
        $response = null;
       
        $requestBody = [
            'UserID' => $api->username,
            'Token' => $api->api_token,
            'RPID' => $request->transaction_id,
            'AgentID' => $request->order_id,
            'Format' => "1",
        ];
        
        $response = Http::get($api->api_trn_status_url, $requestBody);
        
        
        $response = isset($response) && $response ? $response->json() : [];

        $success['actual_request'] = $requestBody;
        $success['actual_response'] = $response;
        $success['message'] = isset($response['MSG']) ? $response['MSG'] : '';

        if (isset($response['STATUS']) && $response['STATUS'] == "SUCCESS") {
            $success['message'] = $response['MSG'];
            $success['transaction_status'] = 'SUCCESS';
            $success['mobile_no'] = $response['ACCOUNT'];
            $success['transaction_id'] = $response['RPID'];
            $success['api_balance'] = $response['BAL'];

            $result['status'] = true;
            $result['result'] = $success;

            return $result;
        } else if (isset($response['STATUS']) && $response['STATUS'] == "PENDING") {
            $success['message'] = $response['MSG'];
            $success['transaction_status'] = 'PENDING';
            $success['mobile_no'] = $response['ACCOUNT'];
            $success['transaction_id'] = $response['RPID'];
            $success['api_balance'] = $response['BAL'];

            $result['status'] = true;
            $result['result'] = $success;

            return $result;
        } else if (isset($response['STATUS']) && $response['STATUS'] == "FAILED") {
            $success['message'] = isset($response['MSG']) ? $response['MSG'] : '';
            $success['transaction_status'] = 'FAILED';
            $success['mobile_no'] = isset($response['ACCOUNT']) ? $response['ACCOUNT'] : '';
            $success['transaction_id'] = isset($response['RPID']) ? $response['RPID'] : '';
            $success['api_balance'] = isset($response['BAL']) ? $response['BAL'] : '';

            $result['status'] = true;
            $result['result'] = $success;

            return $result;
        } else {
            $result['status'] = false;
            $success['transaction_status'] = 'PENDING';
            $result['result'] = $success;

            return $result;
        }
    }

    /**
     * Mobile Recharge API using Ambika New API Service
     */
    public function rechargeByAmbikaNew($request, $api, $srvOpCode = null)
    {
        
        $response = null;
       
        $requestBody = [
            'UserID' => $api->username,
            'Token' => $api->api_token,
            'RPID' => $request->transaction_id,
            'AgentID' => $request->order_id,
            'Format' => "1",
        ];
        
        $response = Http::get($api->api_trn_status_url, $requestBody);
        
        
        $response = isset($response) && $response ? $response->json() : [];

        $success['actual_request'] = $requestBody;
        $success['actual_response'] = $response;
        $success['message'] = isset($response['msg']) ? $response['msg'] : '';

        if (isset($response['status']) && $response['status'] == "2") {
            $success['message'] = $response['msg'];
            $success['transaction_status'] = 'SUCCESS';
            $success['mobile_no'] = $response['account'];
            $success['transaction_id'] = $response['rpid'];
            $success['api_balance'] = $response['bal'];

            $result['status'] = true;
            $result['result'] = $success;

            return $result;
        } else if (isset($response['status']) && $response['status'] == "1") {
            $success['message'] = $response['msg'];
            $success['transaction_status'] = 'PENDING';
            $success['mobile_no'] = $response['account'];
            $success['transaction_id'] = $response['rpid'];
            $success['api_balance'] = $response['bal'];

            $result['status'] = true;
            $result['result'] = $success;

            return $result;
        } else if (isset($response['status']) && $response['status'] == "3") {
            $success['message'] = isset($response['msg']) ? $response['msg'] : '';
            $success['transaction_status'] = 'FAILED';
            $success['mobile_no'] = isset($response['account']) ? $response['account'] : '';
            $success['transaction_id'] = isset($response['rpid']) ? $response['rpid'] : '';
            $success['api_balance'] = isset($response['bal']) ? $response['bal'] : '';

            $result['status'] = true;
            $result['result'] = $success;

            return $result;
        } else {
            $result['status'] = false;
            $success['transaction_status'] = 'PENDING';
            $result['result'] = $success;

            return $result;
        }
    }

    /**
     * Mobile Recharge API using Champion API Service
     */
    public function rechargeByChampion_old($request, $api, $srvOpCode = null)
    {
        // $success=[];
        // $result['status'] = false;
        // $success['transaction_status'] = 'Contact Admin';
        // $result['result'] = $success;

        // return $result;


        $response = null;

        $requestBody = null;

        $requestBody = [
            'login_id' => $api->username,
            'transaction_password' => base64_decode($api->password),
            // 'CLIENTID' => $request->response_msg['CLIENTID'],
            'CLIENTID' => $request->order_id,
            'response_type' => "XML",
        ];
        $t_pass = base64_decode($api->password);
        $url = "http://www.champrecharges.com/api_users/status?login_id=".$api->username."&transaction_password=".$t_pass."&CLIENTID=".$request->order_id."&response_type=XML";

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_TIMEOUT, 36000);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
                // if($api_details->api_id == "4"){
                //   curl_setopt($ch, CURLOPT_POST, 1);
                // }
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $response = curl_exec($ch);
                // print_r($result);

                curl_close($ch); 
       
       
        // $response = Http::get($api->api_trn_status_url, $requestBody);
        // print_r($response->xml());

        $xml = simplexml_load_string($response); 
        $xmlJSON = json_encode($xml);
        $jsonArray = json_decode($xmlJSON, true);
        $response = $jsonArray;
       

        // $response = isset($response) && $response ? $response->simplexml_load_string() : [];
        
      
        $success['actual_request'] = $requestBody;
        $success['actual_response'] = $response;
        $success['message'] = isset($response['MESSAGE']) ? $response['MESSAGE'] : '';

        if (isset($response['STATUS']) && $response['STATUS'] == "Success") {
            $success['transaction_status'] = 'SUCCESS';
            $success['mobile_no'] = $response['MOBILE'];
            $success['api_balance'] = $response['BALANCE'];

            $result['status'] = true;
            $result['result'] = $success;

            return $result;
        } else if (isset($response['STATUS']) && $response['STATUS'] == "Pending") {
            $success['transaction_status'] = 'PENDING';
            $success['mobile_no'] = $response['MOBILE'];
            $success['api_balance'] = $response['BALANCE'];

            $result['status'] = true;
            $result['result'] = $success;

            return $result;
        } else if (isset($response['STATUS']) && $response['STATUS'] == "Failure") {
            $success['transaction_status'] = 'FAILED';
            $success['mobile_no'] = isset($response['MOBILE']) ? $response['MOBILE'] : '';
            $success['api_balance'] = isset($response['BALANCE']) ? $response['BALANCE'] : '';

            $result['status'] = true;
            $result['result'] = $success;

            return $result;
        } else {
            $result['status'] = false;
            $success['transaction_status'] = 'PENDING';
            $result['result'] = $success;

            return $result;
        }
    }

    public function rechargeByChampion($request, $api, $srvOpCode = null)
    {

        $response = null;

        $requestBody = null;

        $requestBody = [
            'login_id' => $api->username,
            'transaction_password' => base64_decode($api->password),
            // 'CLIENTID' => $request->response_msg['CLIENTID'],
            'CLIENTID' => $request->order_id,
            'response_type' => "CSV",
        ];
        $t_pass = base64_decode($api->password);
        // $url = "http://www.champrecharges.com/api_users/status?login_id=".$api->username."&transaction_password=".$t_pass."&CLIENTID=".$request->order_id."&response_type=CSV";
        $url = $api->api_trn_status_url . $api->username."&transaction_password=".$t_pass."&CLIENTID=".$request->order_id."&response_type=CSV";
        
       
                $curl = curl_init();

                curl_setopt_array($curl, array(
                  CURLOPT_URL => $url,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'GET',
                  
                ));
                
                $api_response = curl_exec($curl);
                
                curl_close($curl);
                // echo $api_response;
                
                // print_r($api_response);
                // exit();
                $response_arr = explode(',', $api_response);
                $reresponse['MESSAGE'] = $response_arr[0];
                $reresponse['STATUS'] = $response_arr[5];
                $reresponse['MOBILE'] = $response_arr[3]; 
      
        $success['actual_request'] = $requestBody;
        $success['actual_response'] = $response;
        $success['message'] = isset($response['MESSAGE']) ? $response['MESSAGE'] : '';

        if (isset($response['STATUS']) && $response['STATUS'] == "Success") {
            $success['transaction_status'] = 'SUCCESS';
            $success['mobile_no'] = $response['MOBILE'];
            // $success['api_balance'] = $response['BALANCE'];

            $result['status'] = true;
            $result['result'] = $success;

            return $result;
        } else if (isset($response['STATUS']) && $response['STATUS'] == "Pending") {
            $success['transaction_status'] = 'PENDING';
            $success['mobile_no'] = $response['MOBILE'];
            // $success['api_balance'] = $response['BALANCE'];

            $result['status'] = true;
            $result['result'] = $success;

            return $result;
        } else if (isset($response['STATUS']) && $response['STATUS'] == "Failure") {
            $success['transaction_status'] = 'FAILED';
            $success['mobile_no'] = isset($response['MOBILE']) ? $response['MOBILE'] : '';
            // $success['api_balance'] = isset($response['BALANCE']) ? $response['BALANCE'] : '';

            $result['status'] = true;
            $result['result'] = $success;

            return $result;
        } else {
            $result['status'] = false;
            $success['transaction_status'] = 'PENDING';
            $result['result'] = $success;

            return $result;
        }
    }

    /**
     * Mobile Recharge API using SamriddhiPay API Service
     */
    public function rechargeBySamriddhipay($request, $api, $srvOpCode = null)
    {
        $response = null;
        $requestBody = null;

        $requestBody = [
            'username' => $api->username,
            'apiToken' => $api->api_token,
            'userRcId' => $request->order_id,
        ];

        
        $response = Http::post($api->api_trn_status_url, $requestBody);
        
        $response = isset($response) && $response ? $response->json() : [];

        $success['actual_request'] = $requestBody;
        $success['actual_response'] = $response;
        $success['message'] = isset($response['Message']) ? $response['Message'] : '';

        if (isset($response['Response']) && $response['Response'] == "Success" && $response['rechargeStatus'] == "Success") {
            $success['transaction_status'] = 'SUCCESS';
            $success['mobile_no'] = $response['mobileNumber'];
            $success['api_balance'] = "";

            $result['status'] = true;
            $result['result'] = $success;

            return $result;
        } else if (isset($response['Response']) && $response['Response'] == "Success" && $response['rechargeStatus'] == "Pending") {
            $success['transaction_status'] = 'PENDING';
            $success['mobile_no'] = $response['mobileNumber'];
            $success['api_balance'] = "";

            $result['status'] = true;
            $result['result'] = $success;

            return $result;
        } else if (isset($response['Response']) && $response['Response'] == "Success" && $response['rechargeStatus'] == "Failure") {
            $success['transaction_status'] = 'FAILED';
            $success['mobile_no'] = isset($response['mobileNumber']) ? $response['mobileNumber'] : '';
            $success['api_balance'] = "";

            $result['status'] = true;
            $result['result'] = $success;

            return $result;
        } else if (isset($response['Response']) && $response['Response'] == "Fail") {
            $result['status'] = false;
            $success['transaction_status'] = 'PENDING';
            $result['result'] = $success;

            return $result;
        }
    }

     /**
     * Mobile Recharge API using  API MASTER Service
     */
    public function rechargeByApiMaster($request, $api, $srvOpCode = null)
    {
        $response = null;
        $requestBody = null;
        
        $requestBody = [
            'username' => $api->username,
            'token' => $api->api_token,
            'order_id' => $request->order_id,
        ];

       
        $response = Http::post($api->api_trn_status_url, $requestBody);
        
        $response = isset($response) && $response ? $response->json() : [];
       
        $success['actual_request'] = $requestBody;
        $success['actual_response'] = $response;
        $success['message'] = isset($response['status']) ? $response['status'] : '';

        if (isset($response['status']) && $response['status'] == "SUCCESS") {
            $success['transaction_status'] = 'SUCCESS';
            if (array_key_exists("MOBILE",$response))
           { $success['mobile_no'] = $response['MOBILE']; }
            // $success['api_balance'] = $response['BALANCE'];

            $result['status'] = true;
            $result['result'] = $success;

            return $result;
        } else if (isset($response['STATUS']) && $response['STATUS'] == "PENDING") {
            $success['transaction_status'] = 'PENDING';
            if (array_key_exists("MOBILE",$response))
            { $success['mobile_no'] = $response['MOBILE']; }
            // $success['api_balance'] = $response['BALANCE'];

            $result['status'] = true;
            $result['result'] = $success;

            return $result;
        } else if (isset($response['STATUS']) && $response['STATUS'] == "FAILED") {
            $success['transaction_status'] = 'FAILED';
            if (array_key_exists("MOBILE",$response))
            { $success['mobile_no'] = isset($response['MOBILE']) ? $response['MOBILE'] : ''; }
            // $success['api_balance'] = isset($response['BALANCE']) ? $response['BALANCE'] : '';

            $result['status'] = true;
            $result['result'] = $success;

            return $result;
        } else {
            $result['status'] = false;
            $success['transaction_status'] = 'PENDING';
            $result['result'] = $success;

            return $result;
        }
    }

    /**
     * Check Transaction Status from Api Service Side
     */
    public function checkServiceTransactionStatus($tranDtl)
    {
        
        
        $response = null;
        $usingApi = ApiSetting::where('api_id', $tranDtl->api_id)->first();
       
        $srvOpCode = OperatorDetail::where('operator_id', $tranDtl->operator_id)
            ->where('service_id', $tranDtl->service_id)
            ->where('api_id', $tranDtl->api_id)
            ->pluck('operator_code')->first();

        $usingMethod = isset($usingApi->api_alias) && $usingApi->api_alias ? Config::get('constants.API_ALIAS_METHOD.' . $usingApi->api_alias) : '';
        
        $response = $this->$usingMethod($tranDtl, $usingApi, $srvOpCode);
       
        if ($response['result']['transaction_status'] != 'PENDING') {
            $transactionCtrl = new TransactionReportsController();
            
            $request = new Request;
            $request['order_id'] = $tranDtl->order_id;
            $request['transaction_status'] = $response['result']['transaction_status'];
            
            $test = $transactionCtrl->changeTransactionStatusApi($request);
        }
        return $response;
    }

      /**
     * Sync Transaction from Portal     
     */
    public function syncTransaction($id)
    {
    
        $tranDtl = TransactionDetail:: where('order_id', '=', $id)->get();
        $tranDtl = $tranDtl[0];
       
      
        if ($tranDtl) {
           
            
           
            if ($tranDtl['order_status'] == 'PENDING') {

                if($this->checkSynchTime( $tranDtl['sync_time'])) {
                    return back()->with('error', " Wait For One Minute");
                }

                // if(strtotime($tranDtl['sync_time']) > strtotime("-1 minutes")) {
                //     return back()->with('error', " Wait For One Minute");
                // }
    
                $wllt_order= WalletTransactionDetail:: where('order_id', '=', $id)->where('payment_mode', 'Refund for Failure Recharge')->get();
                if (count($wllt_order)>0) {
                    
                    return back()->with('error', " Refound Already Done");
                }
           
                $response = $this->checkServiceTransactionStatus($tranDtl);
            
                if ($response) {
                    $update_sync_time = TransactionDetail::where('order_id', $id)->update(['sync_time' => now()]);
                    
                    return back()->with('success', "Synced Successfully!!");
                }
            }else {
           
                return back()->with('error', " Not Allowed!!");
            }
        } else {
           
            return back()->with('error', "No such Transaction found!!");
        }
    }

     /**
     * Sync Transaction from API
     */
    public function syncTransactionAPI(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->messages()->first());
        }

        $tranDtl = TransactionDetail::where('order_id',$request->order_id)->where('user_id',$request->user_id)->first();
        if ($tranDtl) {

            if(strtotime($tranDtl['sync_time']) > strtotime("-1 minutes")) {
                // return back()->with('error', " Wait For One Minute");
                return $this->sendError("Wait For One a Minute");
            }

            $wllt_order= WalletTransactionDetail:: where('order_id', '=', $request->order_id)->where('payment_mode', 'Refund for Failure Recharge')->get();
            if (count($wllt_order)>0) {
                // return back()->with('error', " Refound Already Done");
                return $this->sendError("Refound Already Done");

            }

            $response = $this->checkServiceTransactionStatus($tranDtl);
            if ($response) {
                $update_sync_time = TransactionDetail::where('order_id', $request->order_id)->update(['sync_time' => now()]);
                $msg = "Success!!";
                return $this->sendSuccess("Synced Successfully!!",$msg);
            }
        } else {
            return $this->sendError("No such Transaction found!!");
        }
    }

    public function checkSynchTime( $syn_time){
        // $t_date = strtotime($trans_date);

        // $newformat = date('Y-m-d',$t_date);

        $date1 = new DateTime($syn_time);
        $date2 = new DateTime(date('Y-m-d h:i:s'));
        
        $interval = $date1->diff($date2);
        print_r($interval);
        print_r($date2);
        if (($interval->i >0) || ($interval->h >0) || ($interval->d > 0) || ($interval->m >0) ||  ($interval->y >0)){
           return false;
        }
        return true;
    }
}
