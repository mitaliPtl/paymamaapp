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
                  
                   <div class="row">
                                <div class="col-12">
                                <img src="{{asset('template_assets/assets/images/logos/Paymama_330x90.png')}}" style="height:45px;width:160px;">
                               
                                
                                @if($tranDtls['service_id'] == 10)
                                
                                <img src="{{asset('template_assets/icicibank.png')}}" style="margin-left:20px;margin-top:-10px;height:45px;width:160px;">
                                @endif
                            </div>
                </div>
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
                                <th class="font-sm" style="text-align: left">Shop Name</th>
                                <td class="font-sm text-right"><span id="#user_shope_name" class="user_shope_name">{{ $user['store_name'] }}</span></td>
                            </tr>
                            <tr class="border_bottom">
                                <th class="font-sm" style="text-align: left">Mobile. No.</th>
                                <td class="label text-right font-sm text-right user_mobile_no" id="user_mobile_no" ng-bind="">{{ $user['mobile'] }}</td>
                            </tr>
                            <tr class="border_bottom">
                                <th class="font-sm" style="text-align: left">Email</th>
                                <td class="label text-right font-sm text-right user_email" id="user_email" ng-bind="">{{ $user['email'] }}</td>
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
                                <td style="text-align: left">Sender Mobile No.:</td>
                                <th class="text-right label sender_mobile_number " id="sender_mobile_number" style="text-align: right">{{ $tranDtls['mobileno'] }}</td>

                            </tr>
                            @if($tranDtls['service_id'] == 10)
                            <tr class="border_bottom">
                                <td style="text-align: left">Client Reference No:</th>
                                    <th class="text-right label transaction_type" id="transaction_type" style="text-align: right">{{ $tranDtls['client_reference_id'] }}</th>

                            </tr>
                             @else 
                              <tr class="border_bottom">
                                <td style="text-align: left">Transfer Type:</th>
                                    <th class="text-right label transaction_type" id="transaction_type" style="text-align: right">{{ $tranDtls['transaction_type'] }}</th>

                            </tr>
                             
                             @endif
                            <tr class="border_bottom">
                                <td style="text-align: left">Date:</td>
                                <th class="text-right label transaction_date" id="#transaction_date" style="text-align: right">{{ $tranDtls['trans_date'] }}</th>

                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-sm" style="float:right; width: 49%">
                        <tbody>
                            <tr class="border_bottom">

                                <td style="text-align: left">Beneficiary Name:</td>
                                <th class="text-right label recipient_name" id="recipient_name" style="text-align: right">@if($tranDtls['service_id'] == 10){{ $tranDtls['account_holder_name'] }} @else {{ $tranDtls['imps_name'] }}@endif</th>
                            </tr>
                            <tr class="border_bottom">
                               
                                <td style="text-align: left">
                                @if($tranDtls['transaction_type'] == 'UPI')
                                    UPI Id:
                                @else
                                    Account No:
                                @endif
                                
                            
                                </td>
                                <th class="text-right label bank_account_number" id="bank_account_number" style="text-align: right">@if($tranDtls['service_id'] == 10){{ $tranDtls['bank_account_no'] }} @else {{ $tranDtls['bank_account_number'] }}@endif</th>
                            </tr>
                            <tr class="border_bottom">
                                @if($tranDtls['service_id'] == 10)
                                
                                <td style="text-align: left">Bank Name:</td>
                                    <th class="text-right label ifsc" id="ifsc" ng-bind="singleRcpInfo['ifsc']" style="text-align: right">ICICI BANK</th>
                                
                                @elseif($tranDtls['transaction_type'] == 'UPI')
                                    
                                @else
                                    <td style="text-align: left">IFSC Code:</td>
                                    <th class="text-right label ifsc" id="ifsc" ng-bind="singleRcpInfo['ifsc']" style="text-align: right"> {{ $tranDtls['ifsc'] }}</th>
                                @endif
                                
                                
                            </tr>
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
                    <table class="table table-sm invoice-table Table-Normal">
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>
                                        
                                @if($tranDtls['transaction_type'] == 'UPI' && $tranDtls['service_id'] != 10)
                                    UPI Id.
                                @elseif($tranDtls['service_id'] != 10)
                                    Account No.
                                @endif
                                </th>
                                @if($tranDtls['service_id'] == 10)
                                <th>RRN No.</th>
                                @endif
                                <th>Transaction ID</th>
                                <th>Smart ID</th>
                                <th>Status</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>

                            @if(count($tranDtls_gid) > 0 ) @foreach($tranDtls_gid as $index => $tranDtls_gid_value)
                            <tr>
                                <td ng-bind="'1'">{{ $index+1 }}</td>
                                <td class="bank_account_number" id="bank_account_no"> @if($tranDtls['service_id'] == 10){{ $tranDtls['bank_account_no'] }} @else {{ $tranDtls['bank_account_no'] }}@endif</td>
                                @if($tranDtls['service_id'] == 10)
                                <td class="transaction_id" id="transaction_id"> @if($tranDtls['service_id'] == 10){{ $tranDtls['rrnno'] }} @else {{ $tranDtls_gid_value['bank_transaction_id'] }}@endif</td>
                                @endif
                                <td class="transaction_id" id="transaction_id">@if($tranDtls['service_id'] == 10){{ $tranDtls['transaction_id'] }} @else {{ $tranDtls_gid_value['bank_transaction_id'] }}@endif</td>
                                <td class="reference_number" id="reference_number">{{ $tranDtls_gid_value['order_id'] }}</td>
                                <td class="text-success" style="color:#2CD07E"><i class="fa fa-check-circle"></i> SUCCESS</td>
                                <th class="label" style="text-align: left;"><i class="mdi mdi-currency-inr"></i>
                                    <span class="transaction_amount" id="transaction_amount">
                                        <img src="https://smartpaytech.in/public/template_assets/assets/images/icon/icon-rupee.png">
                                        Rs. {{ $tranDtls_gid_value['total_amount'] }}</span></th>
                            </tr>
                            @endforeach @else
                            
                            <tr>
                               @if($tranDtls['service_id'] == 10)
                               
                                 <td ng-bind="'1'">1</td>
                                
                                @if($tranDtls['service_id'] == 10)
                                <td class="transaction_id" id="transaction_id"> @if($tranDtls['service_id'] == 10){{ $tranDtls['rrnno'] }} @else {{ $tranDtls_gid_value['bank_transaction_id'] }}@endif</td>
                                @lse
                                <td class="bank_account_number" id="bank_account_no"> @if($tranDtls['service_id'] == 10){{ $tranDtls['bank_account_no'] }} @else {{ $tranDtls['bank_account_no'] }}@endif</td>
                                @endif
                                <td class="transaction_id" id="transaction_id">@if($tranDtls['service_id'] == 10){{ $tranDtls['transaction_id'] }} @else {{ $tranDtls_gid_value['bank_transaction_id'] }}@endif</td>
                                <td class="reference_number" id="reference_number">{{ $tranDtls['order_id'] }}</td>
                                <td class="text-success" style="color:#2CD07E"><i class="fa fa-check-circle"></i> SUCCESS</td>
                                <th class="label" style="text-align: left;"><i class="mdi mdi-currency-inr"></i>
                                    <span class="transaction_amount" id="transaction_amount">
                                        <img src="https://smartpaytech.in/public/template_assets/assets/images/icon/icon-rupee.png">
                                        Rs. {{ $tranDtls['total_amount'] }}</span></th>
                           
                                @else
                                
                            
                                <td>1</td>
                                <td class="bank_account_number" id="bank_account_number"> {{ $tranDtls['bank_account_number'] }}</td>
                                <td class="transaction_id" id="transaction_id">{{ $tranDtls['bank_transaction_id'] }}</td>
                                <td class="reference_number" id="reference_number">{{ $tranDtls['order_id'] }}</td>
                                <td class="text-success" style="color:#2CD07E"><i class="fa fa-check-circle"></i> SUCCESS</td>
                                <th class="label" style="text-align: left;"><i class="mdi mdi-currency-inr"></i> <span class="transaction_amount" id="transaction_amount">
                                        <img src="https://smartpaytech.in/public/template_assets/assets/images/icon/icon-rupee.png">
                                        Rs.{{ $tranDtls['total_amount'] }}</span></th>
                                        @endif
                            </tr>
                            @endif
                            <tr>
                                
                                 @if($tranDtls['service_id'] == 10)
                                 <td colspan="5"></td>
                                 @else
                                 <td colspan="4"></td>
                                 @endif
                                <td style="text-align: left; border: 1px solid #E9ECEF; width: 100px;">Basic Amount :</td>
                                <th class="label" style="border: 1px solid #E9ECEF; text-align: left;"><i class="mdi mdi-currency-inr"></i>
                                    <span id="transaction_amount" class="transaction_amount">
                                        <img src="https://smartpaytech.in/public/template_assets/assets/images/icon/icon-rupee.png">
                                        Rs.{{ $ttl_amt }}</span></th>
                            </tr>
                            <tr>
                                @if($tranDtls['service_id'] == 10)
                                 <td colspan="5"></td>
                                 @else
                                 <td colspan="4"></td>
                                 @endif
                                <td style="text-align: left; border: 1px solid #E9ECEF;">Surcharge:</td>
                                <th class="label" style="border: 1px solid #E9ECEF; text-align: left;"> <i class="mdi mdi-currency-inr"></i> <span id="surCharge" class="surCharge">
                                    <img src="https://smartpaytech.in/public/template_assets/assets/images/icon/icon-rupee.png">
                                    Rs.{{ $surcharge }}</span></th>
                            </tr>
                            <tr>
                                @if($tranDtls['service_id'] == 10)
                                 <td colspan="5"></td>
                                 @else
                                 <td colspan="4"></td>
                                 @endif
                                <td class="label text-success " style="text-align: left; color:#2CD07E; border: 1px solid #E9ECEF;">Total:</td>
                                <th class="label text-success" style="border: 1px solid #E9ECEF; text-align: left;"><i class="mdi mdi-currency-inr"></i>
                                    <span id="total_amount" class="total_amount" style="color:#2CD07E">
                                            <img src="https://smartpaytech.in/public/template_assets/assets/images/icon/icon-rupee.png">
                                            Rs.{{ $final_amt }}</span></th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- Modal body ends-->
        <div class="row">
            <div class="col-12 text-center" style="text-align: center">
                <span class="label" style="font-size:10px; text-align: center">[Thank You for using www.paymamaapp.in]</span>
            </div>
        </div>
    </section>
</body>

</html>