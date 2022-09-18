<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use PaytmWallet;
use App\PaytmChecksum;
use Auth;
use Config;
use DB;

class PaytmController extends Controller
{
    /**
     * Redirect the user to the Payment Gateway.
     *
     * @return Response
     */
    public function pay(Request $request)
    {

       
        
        $payment = PaytmWallet::with('receive');
       
        $payment->prepare([
            'order' => $request->order_id, // your order id taken from cart
            'user' => $request->user_id, // your user id
            'mobile_number' => $request->mobile, // your customer mobile no
            'email' => $request->email, // your user email address
            'amount' => $request->amount, // amount will be paid in INR.
            'callback_url' => URL::to($request->call_back_url), // callback URL
        ]);
        
        return $payment->receive();
    }

    /**
     * Obtain the payment information.
     *
     * @return Object
     */
    public function paymentCallback()
    {
        $transaction = PaytmWallet::with('receive');

        $response = $transaction->response(); // To get raw response as array
        //Check out response parameters sent by paytm here -> http://paywithpaytm.com/developer/paytm_api_doc?target=interpreting-response-sent-by-paytm
    
        dd("From PayTM");
        die;

        if ($transaction->isSuccessful()) {
            
        } else if ($transaction->isFailed()) {
            //Transaction Failed
        } else if ($transaction->isOpen()) {
            //Transaction Open/Processing
        }
        $transaction->getResponseMessage(); //Get Response Message If Available
        //get important parameters via public methods
        $transaction->getOrderId(); // Get order id

        $transaction->getTransactionId(); // Get transaction id
    }

   
    public function getupiPaytmTransactionApiGatewayDetails(Request $req) {
        $data =  json_decode(file_get_contents('php://input'), true);
        //print_r($data);
        $this->authenticateUser($data);         
        if(!empty($data['amount'])){
            $user_id            = $data['user_id'];
            $amount             = $data['amount'];
            //$payment_type       =$data['payment_type'];
            $last_txn_id = file_get_contents("admin/txn_order_id.txt");              
            $order_id = intval($last_txn_id)+1;
            $this->writeTxnOrderID($order_id);

            $this->db->select('*');
            $this->db->from('tbl_payment_gateway_integation');
            $this->db->where('id', "1");
            $query = $this->db->get();
            $payment_dtls = $query->row();
            $mid = $payment_dtls->merchant_id;
            $merchantKey = $payment_dtls->working_key;
            $env = $payment_dtls->environment;
            $currency = $payment_dtls->currency;
            $url='';
            //$order_id1="OREDRID_".$order_id-1;
            // $url = "https://securegw-stage.paytm.in/theia/api/v1/processTransaction?mid=YOUR_MID_HERE&orderId=ORDERID_98765";
            if($payment_dtls->environment == "testing"){
            // testing
            $url = "https://securegw-stage.paytm.in/theia/api/v1/processTransaction?mid=".$mid."&orderId=".$order_id;
            }else{
            // live
            $url = "https://securegw.paytm.in/theia/api/v1/processTransaction?mid=".$mid."&orderId=".$order_id1;
            }
        
            $txntoken='912ee285913a4c75af02f2aed856d5a91602940327866';
            
            $payment_type='UPI_INTENT';
            $paytmParams = array();
            $paytmParams["body"] = array(
            "requestType" => "NATIVE",
            "mid"         => $mid,
            "orderId"     => $order_id,
            "paymentMode" =>$payment_type,
                //"authMode"    => "otp"
            
            );
            //print_r($paytmParams["body"]);
            $paytmParams["head"] = array(
            "version"=>1,
            "requestTimestamp"=>'1588402269',
            "channelId"=>"WAP",
            "txnToken"    => $txntoken
            );

            $post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);



            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json")); 
            $response = curl_exec($ch);
            print_r($response);
            exit();
           
            $result = array(
                'orderId' => $order_id,
                'merchant_id' => $mid,
                'callbackurl' => base_url()."transactions/paytm_transaction_status/".$order_id,
                'paytm_txn_start_url' => $paymenturl."",
                'response' => $response1.""
            );
            $response = array(
                'status' => "true",
                'msg' => "Success",
                'result' => $result
            );
        }else{
        $response = array(
            'status' => "false",
            'msg' => "Invalid Request",
            'result' => null
        );
        }
        echo json_encode($response);
        //exit;    
    }


    public function getPaytmTransactionApiGatewayDetails(Request $request){
      
        if(isset($request->amount)){
            $user_id = $request->user_id;
            $amount = $request->amount;

            $old_oid =  file_get_contents("admin/admin/txn_order_id.txt"); 
            $order_id = (int)$old_oid+1;
            file_put_contents('admin/admin/txn_order_id.txt', $order_id."");
            
            $payment_dtls = DB::table('tbl_payment_gateway_integation')->where('id', '1')->get();
            $payment_dtls = $payment_dtls[0];
            $mid = $payment_dtls->merchant_id;//"XtrSQa90965375513903";//"ASJCcA23692100733906";
            $merchantKey = $payment_dtls->working_key;//"Cw7UhxxDlP3x#IFf";//"9UtzE2DUArQnP5fy";
            $env = $payment_dtls->environment;
            $currency = $payment_dtls->currency;

            $paytmParams = array();
            $callback_url ='';
            // print_r($payment_dtls);
            if($payment_dtls->environment == "testing"){
             //staging
            $callback_url = "https://securegw-stage.paytm.in/theia/paytmCallback?ORDER_ID=".$order_id."";
            }else{
             //production
             $callback_url = "https://securegw.paytm.in/theia/paytmCallback?ORDER_ID=".$order_id."";
            }

            $paytmParams["body"] = array(
                "requestType"   => "Payment",
                "mid"           => $mid,
                "websiteName"   => "DEFAULT",
                "orderId"       => $order_id,
                "callbackUrl"   => $callback_url,
                "txnAmount"     => array(
                    "value"     => $amount,
                    "currency"  => $currency,
                ),
                "userInfo"      => array(
                    "custId"    => $user_id,
                ),
            );
            // print_r($callback_url);

              /*
        * Generate checksum by parameters we have in body
        * Find your Merchant Key in your Paytm Dashboard at https://dashboard.paytm.com/next/apikeys 
        */
        $checksum = PaytmChecksum::generateSignature(json_encode($paytmParams["body"], JSON_UNESCAPED_SLASHES), $merchantKey);
        $paytmParams["head"] = array(
            "signature" => $checksum
        );

        $post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);
        $url = '';
        if($payment_dtls->environment == "testing"){
        $url = "https://securegw-stage.paytm.in/theia/api/v1/initiateTransaction?mid=".$mid."&orderId=".$order_id;
        }else{
         $url = "https://securegw.paytm.in/theia/api/v1/initiateTransaction?mid=".$mid."&orderId=".$order_id;
        }
        // print_r($url);
         $ch = curl_init($url);
         curl_setopt($ch, CURLOPT_POST, 1);
         curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
         curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json")); 
         $response1 = curl_exec($ch);

        //$response2 = json_decode($response1,true);
        //print_r($response);
        if($payment_dtls->environment == "testing"){
            //staging url
            $paymenturl = "https://securegw-stage.paytm.in/theia/api/v1/showPaymentPage";
        }else{
            //production url
            $paymenturl = "https://securegw.paytm.in/theia/api/v1/showPaymentPage";
        }
        $result = array(
            'orderId' => $order_id,
            'merchant_id' => $mid,
            'callbackurl' => Config::get('constants.WEBSITE_BASE_URL')."transactions/paytm_transaction_status/".$order_id,
            'paytm_txn_start_url' => $paymenturl."",
            'response' => $response1.""
        );
        $response = array(
            'status' => "true",
            'msg' => "Success",
            'result' => $result
        );

        $statusMsg = 'Success';

        // $statusMsg = $url." ===== ".$post_data;
        return $this->sendSuccess($response, $statusMsg);

        }else{

            return $this->sendError("Invalid Request");
            // $response = array(
            //   'status' => "false",
            //   'msg' => "Invalid Request",
            //   'result' => null
            // );
        }
    }

    public function getupiPaytmTransactionApiGatewayDetails_new(Request $request){
        // $old_oid =  file_get_contents("admin/admin/txn_order_id.txt"); 
        // $order_id = (int)$old_oid+1;
        
        if(!empty($order_id = $request->order_id)){

            // file_put_contents('admin/admin/txn_order_id.txt', $order_id."");

            $payment_dtls = DB::table('tbl_payment_gateway_integation')->where('id', '1')->get();
            $payment_dtls = $payment_dtls[0];
            $mid = $payment_dtls->merchant_id;
            $merchantKey = $payment_dtls->working_key;
            $env = $payment_dtls->environment;
            $currency = $payment_dtls->currency;
            if($payment_dtls->environment == "testing"){
                $url = "https://securegw-stage.paytm.in/theia/api/v1/processTransaction?mid=".$mid."&orderId=".$order_id;
            }else{
                $url = "https://securegw.paytm.in/theia/api/v1/processTransaction?mid=".$mid."&orderId=".$order_id;
            }       
            
            // $txntoken='912ee285913a4c75af02f2aed856d5a91602940327866';
            $txntoken= $request->txntoken;
            
            $payment_type='UPI_INTENT';
            $paytmParams = array();
            $paytmParams["body"] = array(
            "requestType" => "NATIVE",
            "mid"         => $mid,
            "orderId"     => $order_id,
            "paymentMode" =>$payment_type,
                //"authMode"    => "otp"
            
            );

            $paytmParams["head"] = array(
                "version"=>1,
                "requestTimestamp"=>'1588402269',
                "channelId"=>"WAP",
                "txnToken"    => $txntoken
                );

                $post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json")); 
                $response = curl_exec($ch);
                // print_r($response);
                // $response = json_decode($response, true);
                // $statusMsg = $url. "=====".$post_data;
                $statusMsg = "Success";
                // $success = $request;
                return $this->sendSuccess($response, $statusMsg);
        }else{
            return $this->sendError("Invalid Payment Mode!!");
        }
            // $response = array(
            //     'status' => "false",
            //     'msg' => "Invalid Request",
            //     'result' => null
            // );
            // }
            // echo json_encode($response);
            
        // print_r($content);
        // if($request){
        //     $statusMsg = $content;
        //     $success = $request;
        //     return $this->sendSuccess($success, $statusMsg);
        // }else{
           
        // }

    }
  
    
}
