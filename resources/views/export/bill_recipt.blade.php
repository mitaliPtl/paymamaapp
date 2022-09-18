<!DOCTYPE html>

<html>

<head>
    <meta charset="utf-8">
    <title>{{ $fileName }}</title>
    <!-- CSS -->
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous"> -->


</head>
<style>
    .shope_n {
        color: blue;
    }
    
    .row:after {
        content: "";
        display: table;
        clear: both;
    }
    
    .icon-col {
        float: left;
        width: 50%;
        /* padding: 10px;
            height: 300px; Should be removed. Only for demonstration */
    }
    
    .invoice_table {
        float: left;
        width: 50%;
    }
    
    tr,
    th,
    td {
        padding: 5px;
    }
    
    .text-side {
        float: right;
    }
    /* Tables */
    
    .Table-Normal {
        position: relative;
        /* //display: block; */
        margin: 10px auto;
        padding: 0;
        width: 100%;
        height: auto;
        border-collapse: collapse;
        text-align: center;
    }
    
    .Table-Normal thead tr {
        background-color: #E9ECEF;
        font-weight: bold;
    }
    
    .Table-Normal tr {
        margin: 0;
        padding: 0;
        border: 0;
        /* border: 1px solid #999; */
        width: 100%;
    }
    
    .Table-Normal tr td {
        margin: 0;
        padding: 4px 8px;
        border: 0;
        /* border: 1px solid #999; */
    }
    
    .tr-border {
        border-top: #999;
    }
    
    tr.border_bottom td,
    tr.border_bottom th {
        border-top: 1px solid #E9ECEF;
    }
    
    .body-style {
        font-family: Rubik, sans-serif;
        font-size: .875rem;
        line-height: 1.5;
    }
</style>

<body class="body-style">
    <section>

        <div class="modal-body">
            <div class="row">
                <div class="col-8 icon-col">
                    <!-- <img class="mt-3" src="https://smartpaytech.in/public/template_assets/assets/images/logos/logo-text-flat.png" style="width:250px"> -->
                    <img  src="https://smartpaytech.in/public/template_assets/assets/images/login_big_sm_py.png" style="width:150px">
                    <img  src="https://smartpaytech.in/public/template_assets/assets/images/logos/BeAssured.png" style="width:130px">
                    
                </div>
                <div class="col-4 invoice_table">
                    <table class="table table-sm invoice-table " style="float:right">
                        <thead>
                            <tr class="border_bottom">
                                <th colspan="2" class="text-center" style="background: #E9ECEF">INVOICE</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="">
                                <th class="font-sm" style="text-align: left">Shope Name</th>
                                <td class="font-sm text-right"><span id="#user_shope_name" class="user_shope_name">{{ $tranDtls['store_name'] }}</span></td>
                            </tr>
                            <tr class="border_bottom">
                                <th class="font-sm" style="text-align: left">Mobile. No.</th>
                                <td class="label text-right font-sm text-right user_mobile_no" id="user_mobile_no" ng-bind="">{{ $tranDtls['mobile'] }}</td>
                            </tr>
                            <tr class="border_bottom">
                                <th class="font-sm" style="text-align: left">Email</th>
                                <td class="label text-right font-sm text-right user_email" id="user_email" ng-bind="">{{ $tranDtls['email'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                    <table class="table table-sm  " style="float:left; width: 49%">
                        <tbody>
                            <tr class="border_bottom">
                                <td style="text-align: left">Customer Name :</td>
                                <th class="text-right label sender_mobile_number " id="sender_mobile_number" style="text-align: right">{{ $response_msg['RespCustomerName'] }}</td>

                            </tr>
                            <tr class="border_bottom">
                                <td style="text-align: left">Date:</th>
                                    <th class="text-right label transaction_type" id="transaction_type" style="text-align: right">{{ $tranDtls['trans_date'] }}</th>

                            </tr>
                            <tr class="border_bottom">
                                <td style="text-align: left">Bill No:</td>
                                <th class="text-right label transaction_date" id="#transaction_date" style="text-align: right">
                                    @if(isset($response_msg['RespBillNumber']))
                                        {{ $response_msg['RespBillNumber'] }}
                                    @endif
                                    
                                </th>

                            </tr>
                            <tr class="border_bottom">
                                <td style="text-align: left">Biller Name:</td>
                                <th class="text-right label transaction_date" id="#transaction_date" style="text-align: right">{{ $tranDtls['billerName'] }}</th>

                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-sm" style="float:right; width: 49%">
                        <tbody>
                            @if($response_msg)

                                @if (array_key_exists("paramName",$response_msg['inputParams']['input']))
                                    <tr class="border_bottom">

                                    <td style="text-align: left"> {{ $response_msg['inputParams']['input']['paramName'] }} </td>
                                    <th class="text-right label recipient_name" id="recipient_name" style="text-align: right"> {{ $response_msg['inputParams']['input']['paramValue'] }} </th>
                                    </tr>
                                @else
                                    @foreach($response_msg['inputParams']['input'] as $key => $value)

                                    <tr class="border_bottom">

                                        <td style="text-align: left"> {{ $value['paramName'] }} </td>
                                        <th class="text-right label recipient_name" id="recipient_name" style="text-align: right"> {{ $value['paramValue'] }} </th>
                                    </tr>
                                    @endforeach

                                @endif
                            @endif
                            <!-- <tr class="border_bottom">

                                <td style="text-align: left">Account No:</td>
                                <th class="text-right label bank_account_number" id="bank_account_number" style="text-align: right">{{ $tranDtls['bank_account_number'] }}</th>
                            </tr>
                            <tr class="border_bottom">

                                <td style="text-align: left">IFSC Code:</td>
                                <th class="text-right label ifsc" id="ifsc" ng-bind="singleRcpInfo['ifsc']" style="text-align: right"> {{ $tranDtls['ifsc'] }}</th>
                            </tr> -->
                        </tbody>
                    </table>
                </div>

            </div>

            <!-- <div class="row">
                <div class="col-6">
                    <table class="table table-sm invoice-table Table-Normal">
                        <tbody>
                            <tr>
                                <td style="text-align: left">Sender Mobile No.:</td>
                                <th class="text-right label sender_mobile_number " id="sender_mobile_number" style="text-align: right">{{ $tranDtls['mobileno'] }}</td>
                                    <td style="text-align: left">Beneficiary Name:</td>
                                    <th class="text-right label recipient_name" id="recipient_name" style="text-align: right">{{ $tranDtls['imps_name'] }}</th>
                            </tr>
                            <tr>
                                <td style="text-align: left">Transfer Type:</th>
                                    <th class="text-right label transaction_type" id="transaction_type" style="text-align: right">{{ $tranDtls['transaction_type'] }}</th>
                                    <td style="text-align: left">Account No:</td>
                                    <th class="text-right label bank_account_number" id="bank_account_number" style="text-align: right">{{ $tranDtls['bank_account_number'] }}</th>
                            </tr>
                            <tr>
                                <td style="text-align: left">Date:</td>
                                <th class="text-right label transaction_date" id="#transaction_date" style="text-align: right">{{ $tranDtls['trans_date'] }}</th>
                                <td style="text-align: left">IFSC Code:</td>
                                <th class="text-right label ifsc" id="ifsc" ng-bind="singleRcpInfo['ifsc']" style="text-align: right"> {{ $tranDtls['ifsc'] }}</th>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
 -->


            <div class="row">
                <div class="col-12">
                    <table class="table table-sm invoice-table Table-Normal" >
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Transaction ID</th>
                                <th>Smart ID</th>
                                <th>Status</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody >

                           <tr >
                                <td ng-bind="'1'">1</td>
                                <td class="bank_account_number" id="bank_account_number">{{ $response_msg['txnRefId'] }}</td>
                                <td class="transaction_id" id="transaction_id">  {{ $tranDtls['order_id'] }} </td>
                                <td class="reference_number" id="reference_number" style="color:#2CD07E">{{ $response_msg['responseReason'] }}</td>
                                <!-- <td class="text-success" style="color:#2CD07E"><i class="fa fa-check-circle"></i> SUCCESS</td> -->
                                <th class="label" style="text-align: left;"><i class="mdi mdi-currency-inr"></i>
                                    <span class="transaction_amount" id="transaction_amount">
                                        <img src="https://uat.smartpaytech.in/public/template_assets/assets/images/icon/icon-rupee.png">
                                        Rs.{{ $tranDtls['request_amount'] }}</span></th>
                            </tr>
                           

                           
                            <tr>
                                <td colspan="3"></td>
                                <td style="text-align: left; border: 1px solid #E9ECEF; width: 100px;">Basic Amount :</td>
                                <th class="label" style="border: 1px solid #E9ECEF; text-align: left;"><i class="mdi mdi-currency-inr"></i>
                                    <span id="transaction_amount" class="transaction_amount">
                                        <img src="https://uat.smartpaytech.in/public/template_assets/assets/images/icon/icon-rupee.png">
                                        Rs.{{ $tranDtls['request_amount'] }}</span></th>
                            </tr>
                            <tr>
                                <td colspan="3" style="text-align: left"></td>
                                <td style="text-align: left; border: 1px solid #E9ECEF;">Surcharge:</td>
                                <th class="label" style="border: 1px solid #E9ECEF; text-align: left;"> <i class="mdi mdi-currency-inr"></i> <span id="surCharge" class="surCharge">
                                    <img src="https://uat.smartpaytech.in/public/template_assets/assets/images/icon/icon-rupee.png">
                                    Rs.{{ $surcharges }}</span></th>
                            </tr>
                            <tr>
                                <td class="label text-success " colspan="3"></td>
                                <td class="label text-success " style="text-align: left; color:#2CD07E; border: 1px solid #E9ECEF;">Total:</td>
                                <th class="label text-success" style="border: 1px solid #E9ECEF; text-align: left;"><i class="mdi mdi-currency-inr"></i>
                                    <span id="total_amount" class="total_amount" style="color:#2CD07E">
                                            <img src="https://uat.smartpaytech.in/public/template_assets/assets/images/icon/icon-rupee.png">
                                        Rs.{{ $grand_ttl }}</span></th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- Modal body ends-->
        <div class="row">
            <div class="col-12 text-center" style="text-align: center">
                <span class="label" style="font-size:10px; text-align: center">[Thank You for using Smart Pay]</span>
            </div>
        </div>
    </section>
</body>

</html>