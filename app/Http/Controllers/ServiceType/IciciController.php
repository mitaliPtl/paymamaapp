<?php

namespace App\Http\Controllers\ServiceType;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\BankAccount;
use App\User;
use Config;
use Auth;
use DB;
use App\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class IciciController extends Controller
{
    public $pubkey = '-----BEGIN CERTIFICATE-----
MIIFiTCCA3GgAwIBAgIJAPhKHX+xSWb7MA0GCSqGSIb3DQEBBQUAMFsxCzAJBgNVBAYTAklOMRQw
EgYDVQQIDAtNQUhBUkFTSFRSQTEPMA0GA1UEBwwGTVVNQkFJMRcwFQYDVQQKDA5JQ0lDSSBCYW5r
IEx0ZDEMMAoGA1UECwwDQlRHMB4XDTE3MDkyNTA4NTcwM1oXDTIwMDYyMDA4NTcwM1owWzELMAkG
A1UEBhMCSU4xFDASBgNVBAgMC01BSEFSQVNIVFJBMQ8wDQYDVQQHDAZNVU1CQUkxFzAVBgNVBAoM
DklDSUNJIEJhbmsgTHRkMQwwCgYDVQQLDANCVEcwggIiMA0GCSqGSIb3DQEBAQUAA4ICDwAwggIK
AoICAQCpyw5vtvzONTBwIB89oI6tNmONluYlac/IGsOIJgz/NHUbvONTQasTEcFNAQLgGkljV3ZN
o2ld8Yl6njjAqd1RFfNLbcNDq5AzWRqHEvIfbdcna/wRCz1KUVS+GyZjjoDBovoAZFNo/jM6WU6D
bA4iDW7KaSkTgczt6/0vNo5/BpiDluFNLUUHtlM6D4l9ZFw/A9xoE7jms5saTCoYMz/3Vgpr6lmp
g7gckfHmHEfecSwT0N639+wGEAGdfxzAr3yEc6yCE9XjBIRiTFafBJO32SeO6LQsjl8YGa7mYsQN
Yj+Xt2+kztyq4/M5/I5En3rWVKhP6s4o7bB10uZPO2DHEo49OHnCr2MVq0lwco341xGKPaVwZ9oI
fZX6Jh7ca0y3hTXABZrA5sXfmYwaxYxz/4o1JYeiYjqSvYcKnNt7c7pcpYLKiBC/6RENxVgoNqnY
QJZj/mYkcmvNPFmHvnAGtmnRA+hm06we0dMUO0ZQJhSqP6sfM5oDeZqMAIy291YWW7Hpoimti8db
GD+pMFQxjzS5cuxPl/JjHfPRLUx/MSf26Xu1hhgfh4/9lseuNAjuHfqQS/KiT6BnpuqoMpXkx9K0
FPcfrd8TdHhuGGihuyEtEfj+3G2uMSYE4xEmDx5BQCTXA6x5I6IQyNUN+IorkbDTOJfB2tjxhbQz
rgITHQIDAQABo1AwTjAdBgNVHQ4EFgQUWI7/jLcNvrchEffA3NCjgmTDHSMwHwYDVR0jBBgwFoAU
WI7/jLcNvrchEffA3NCjgmTDHSMwDAYDVR0TBAUwAwEB/zANBgkqhkiG9w0BAQUFAAOCAgEAlfzy
H4x6x7QUtFuL8liD6WO6Vn8W9r/vuEH3YlpiWpnggzRPq2tnDZuJ+3ohB//PSBtCHKDu28NKJMLE
NqAVpgFtashkFlmMAXTpy4vYnTfj3MyAHYr9fwtvEmUKEfiIIC1WXDQzWWP4dFLdJ//jint9bdyM
Iqx+H5ddPXmfWXwAsCs3GlXGVwEmtcc9v7OliCHyyO2s++L+ATz5FoyxKCmZyn1GHD3gmvFjXicI
WB+Us1uRkrDFO8clS1hWvmvF/ghfGYmlKOqTzu/TCY4d9u/CciNesens3iSHEgs58r/9gaxwpiEs
tRolx9eVjkem1ZI5IUCUbRC40r8sL+eEObcwhVV87nrKH2l0BX8nM/ux0lqAkRO+Ek9tdP5TmHT0
XE2E/PMJO7/AlzYvN3oznT9ZeKfu6WbNIZrFCcO6GsoNi8+pKZsWuSePbrhRQC+d3whHS7tAanS8
+6gbPMMoAfkSKt0yaogld6RI2Af1C6QerxZR2LcJM5ni8eCz1cIvS3XSpkG5hcRMXHJAGkc5GAoE
Dj08gZbQVtE4FeJRfTJoX6cpXM6cBODsi8xKzpBCGNNcA/p4r/6XGg2csXyKCCLrVtk0VNKyr/Ba
6T5dfbbuzGcbL/dVd5d/7A9cGJTkk2gRxIL6bBMKn0Qm68mSDUhVFg001zi0JR3nOy9M6Hs=
-----END CERTIFICATE-----';

    public $privkey = '-----BEGIN RSA PRIVATE KEY-----
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
-----END RSA PRIVATE KEY-----';

    
    private function icici_api($url,$values,$type="") {
        $request = $this->icici_encrypt(json_encode($values));
        
        $response = Http::contentType("text/plain")
        ->withHeaders([
            "accept" => "*/*",
            "content-length" => "684",
            "APIKEY" => "dudz0SdWPywthDFRVGn2BWjXNG9NpIAl"
        ])->send('POST',$url, [
            'body' => $request
        ])->body();
        $logfile = 'iciciresponselog.txt';
        $log = 'URL - '.$url."\n";
        $log .= 'RAW REQUEST - '.json_encode($values)."\n";
        $log .= 'REQUEST - '.$request."\n";
        $log .= 'RAW RESPONSE - '.$response."\n";
        
        if($type == 'statement') {
            $body = json_decode($response);
            $data = base64_decode($body->encryptedData);
            $key = $this->icici_decrypt($body->encryptedKey);
            $iv_size = openssl_cipher_iv_length('AES-128-CBC');
            $iv = substr($data, 0, $iv_size);
            if(16 !== strlen($key)) $key = hash('MD5', $key, true);
            $decrypted_response = openssl_decrypt(substr($data, $iv_size), 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
            $log .= 'RESPONSE - '.$decrypted_response."\n\n";
            file_put_contents($logfile, $log, FILE_APPEND | LOCK_EX);
            return $decrypted_response;
        } else {
            $decrypted_response = $this->icici_decrypt($response);
        }
        $log .= 'RESPONSE - '.$decrypted_response."\n\n";
        file_put_contents($logfile, $log, FILE_APPEND | LOCK_EX);
        return $decrypted_response;
        
    }
    
    private function icici_encrypt($data) {
        if (openssl_public_encrypt($data, $encrypted, $this->pubkey)) {
            $data = base64_encode($encrypted);
        } else {
            $data = "";
        }
        return $data;
    }
    
    private function icici_decrypt($data) {
        if (openssl_private_decrypt(base64_decode($data), $decrypted, $this->privkey)) {
            $data = $decrypted;
        } else {
            $data = "";
        }
        return $data;
    }
    
    public function icici_balance_inquiry() {
        $url = "https://apibankingone.icicibank.com/api/Corporate/CIB/v1/BalanceInquiry";
    	$values = array("AGGRID"=>"OTOE0417","CORPID"=>"577837866","USERID"=>"THATISRI","URN"=>"SR208802360","ACCOUNTNO"=>"000405569225");
        $icici = $this->icici_api($url,$values);
        $resp = json_decode($icici);
        // return $icici;
        if(isset($resp->RESPONSE) && $resp->RESPONSE == 'SUCCESS') {
            $bal = $resp->EFFECTIVEBAL;
        } else {
            $bal = "0.00";
        }
        return $bal;
    }
    
    public function icici_account_statement(Request $request) {
        $fromDate = $toDate = date("d-m-Y");
        if ($request->has('from_date') && isset($request->from_date)) {
            $fromDate = $request->get('from_date');
            $fromDate = date("d-m-Y", strtotime($fromDate));
        }

        if ($request->has('to_date') && isset($request->to_date)) {
            $toDate = $request->get('to_date');
            $toDate = date("d-m-Y", strtotime($toDate));
        }
        // $fromDate = "01-01-2016";
        // $toDate = "30-12-2016";
        $url = "https://apibankingone.icicibank.com/api/Corporate/CIB/v1/AccountStatement";
    	$values = array("AGGRID"=>"OTOE0417","CORPID"=>"577837866","USERID"=>"THATISRI","URN"=>"SR208802360","ACCOUNTNO"=>"000405569225","FROMDATE"=>$fromDate,"TODATE"=>$toDate);
    	$all_txn = $this->icici_api($url,$values,'statement');
    // 	return $all_txn;
    	$all_txn = json_decode($all_txn);
        if(isset($all_txn->RESPONSE) && $all_txn->RESPONSE == 'SUCCESS') {
            $all_txn = $all_txn->Record;
            if(!is_null($all_txn) && count($all_txn) == 1) {
                $all_txn = [$all_txn];
            }
        } else {
            $all_txn = array();
        }
        // return $all_txn;
        $balance = $this->icici_balance_inquiry();
        // $balance = 0.00;
    	return view('modules.payment.icici_statement', compact('all_txn', 'balance'));
    }
    
    public function test() {
        echo file_get_contents("https://mobilerechargenow.com/api/bill-fetch.php?username=MRN1018451&apikey=9845447941&format=json&no=102431088&operator=TSSP&txnid=".time());
    }
    
}

?>