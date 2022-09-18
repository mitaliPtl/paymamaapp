<?php

/* import checksum generation utility */
require_once("./PaytmChecksum.php");

/* initialize an array */
$paytmParams = array();

/* add parameters in Array */
$paytmParams["MID"] = "MuPCUf88022572280280";
$paytmParams["ORDERID"] = "YOUR_ORDERID_HERE";
$merchantKey = "sH0Vw7eNItPFh%M5";

/**
* Generate checksum by parameters we have
* Find your Merchant Key in your Paytm Dashboard at https://dashboard.paytm.com/next/apikeys 
*/
$paytmChecksum = PaytmChecksum::generateSignature($paytmParams, $merchantKey);

if($paytmChecksum){

    $response['status'] = true;
    $response['message'] = "Success!!";
    $response['result'] = [
        "token" => $paytmChecksum
    ];
}else{
    
    $response['status'] = false;
    $response['message'] = "Failure!!";
}

 echo json_encode($response);

