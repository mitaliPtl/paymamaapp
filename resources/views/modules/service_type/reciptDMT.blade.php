<!DOCTYPE html>

<html>

<head>
    <meta charset="utf-8">
    <title>{{ $fileName }}</title>
    <!-- CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">


</head>
<style>
    th{
        font-weight:bold;
    }
    td{
        font-weight:bold;
    }
    .success-grad {
    background-image: linear-gradient(to right, #251c63 , #dc182d);
    color: white;
    border-color: #ffffff;
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
        font-size: 1rem;
        line-height: 1.5;
    }
</style>

<body class="body-style">
    <section class="container" style="margin-top: 50px;height:300px !important;">
    
        <div class="modal-body">
                <div class="row">
                                <div class="col-6">
                                <img src="{{asset('template_assets/assets/images/logos/Paymama_330x90.png')}}" style="height:55px;width:260px;">
                               
                                
                                
                            </div>
                             <div class="col-6">
                               
                                @if($tranDtls['service_id'] || $serviceid==6 || $serviceid==11 || $serviceid==12 || $serviceid==9)
                                
                                <img src="{{asset('template_assets/icicibank.png')}}" style="float:right;margin-left:20px;margin-top:-10px;height:55px;width:260px;">
                                @endif
                            </div>
                </div>
             @if($serviceid!=6 && $serviceid!=11 && $serviceid!=12 && $serviceid!=9)   
            <div class="row">
                                <div class="col-4">
                                <img src="{{asset('template_assets/assets/images/logos/Paymama_330x90.png')}}" style="height:55px;width:260px;">
                               
                                
                                
                            </div>
                             <div class="col-4">
                               
                                @if($tranDtls['service_id'] || $serviceid==6 || $serviceid==11 || $serviceid==12 || $serviceid==9)
                                
                                <img src="{{asset('template_assets/icicibank.png')}}" style="margin-left:20px;margin-top:-10px;height:55px;width:260px;">
                                @endif
                            </div>
               
               
                <div class="col-4 invoice_table">
            
                    <table class="table  invoice-table " style="float:right">
                        <thead>
                            <tr class="border_bottom">
                                <th colspan="2" class="text-center" style="background: #E9ECEF">INVOICE</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="">
                                <th class="" style="text-align: left">Merchant Store Name</th>
                                <td class="text-right"><span id="#user_shope_name" class="user_shope_name">{{ $user['store_name'] }}</span></td>
                            </tr>
                            <tr class="border_bottom">
                                <th class="" style="text-align: left">Mobile. No.</th>
                                <td class="label text-right text-right user_mobile_no" id="user_mobile_no" ng-bind="">{{ $user['mobile'] }}</td>
                            </tr>
                            <tr class="border_bottom">
                                <th class="" style="text-align: left">Email</th>
                                <td class="label text-right  text-right user_email" id="user_email" ng-bind="">{{ $user['email'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                 
                </div>
            
             
            <div class="row">
                <div class="col-6">
                    
                    <table class="table   " style="float:left; width: 100%">
                        <tbody>
                            <tr class="border_bottom">
                                <td style="text-align: left">Sender Mobile No.:</td>
                                <th class="text-right label sender_mobile_number " id="sender_mobile_number" style="text-align: right">{{ $tranDtls['mobileno'] }}</td>

                            </tr>
                            <tr class="border_bottom">
                                <td style="text-align: left">Transfer Type:</th>
                                    <th class="text-right label transaction_type" id="transaction_type" style="text-align: right">{{ $tranDtls['transaction_type'] }}</th>

                            </tr>
                            <tr class="border_bottom">
                                <td style="text-align: left">Date:</td>
                                <th class="text-right label transaction_date" id="#transaction_date" style="text-align: right">{{ $tranDtls['trans_date'] }}</th>

                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-6">
                    <table class="table " style="float:right; width: 100%">
                        <tbody>
                            <tr class="border_bottom">

                                <td style="text-align: left">Beneficiary Name:</td>
                                <th class="text-right label recipient_name" id="recipient_name" style="text-align: right">{{ $tranDtls['imps_name'] }}</th>
                            </tr>
                            <tr class="border_bottom">
                               
                                <td style="text-align: left">
                                @if($tranDtls['transaction_type'] == 'UPI')
                                    UPI Id:
                                @else
                                    Account No:
                                @endif
                                
                            
                                </td>
                                <th class="text-right label bank_account_number" id="bank_account_number" style="text-align: right">{{ $tranDtls['bank_account_number'] }}</th>
                            </tr>
                            <tr class="border_bottom">
                                @if($tranDtls['transaction_type'] == 'UPI')
                                    
                                @else
                                    <td style="text-align: left">IFSC Code:</td>
                                    <th class="text-right label ifsc" id="ifsc" ng-bind="singleRcpInfo['ifsc']" style="text-align: right"> {{ $tranDtls['ifsc'] }}</th>
                                @endif
                                
                                
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
            @endif
</div>
     

            <div class="row">
                <div class="col-12">
                        @if($serviceid==6 || $serviceid==11 || $serviceid==12 || $serviceid==9)
                    <table class="table table-bordered table stripped" style="width:100%;">
                        <thead>
                            <tr class="border_bottom">
                                @if($serviceid==6)
                                <th colspan="2" class="text-center" style="background: #E9ECEF;text-transform:uppercase;">Aeps Cash Withdrawal Receipt</th>
                                @elseif($serviceid==11)
                                <th colspan="2" class="text-center" style="background: #E9ECEF;text-transform:uppercase;">Aeps Balance Enquiry Receipt</th>
                                @elseif($serviceid==9)
                                <th colspan="2" class="text-center" style="background: #E9ECEF;text-transform:uppercase;">Aadhar Pay Receipt</th>
                                @elseif($serviceid==12)
                                <th colspan="2" class="text-center" style="background: #E9ECEF;text-transform:uppercase;">Aeps Mini Statement Receipt</th>
                                @endif
                                
                            </tr>
                        </thead>
                         <tr class="">
                                <td class="" style="text-align: left">MERCHANT STORE NAME</th>
                                <td><span id="#user_shope_name" class="user_shope_name">{{ $user['store_name'] }}</span></td>
                            </tr>
                            <tr class="border_bottom">
                                <td class="" style="text-align: left">MOBILE NO.</td>
                                <td>{{ $user['mobile'] }}</td>
                            </tr>
                            <tr class="border_bottom">
                                <td class="" style="text-align: left">EMAIL</td>
                                <td>{{ $user['email'] }}</td>
                            </tr>
                                    @if($serviceid==11 or $serviceid==12)
                                    @else
                                    
                                    <tr>
                                        <td style="font-size:15px;">AMOUNT</td><td style="font-size:15px;">{{ $amount }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td style="font-size:15px;">DATE & TIME</td><td style="font-size:15px;">{{ $trans_date }}</td>
                                    </tr>
                                     <tr>
                                        <td style="font-size:15px;">AVAILABLE BALANCE</td><td style="font-size:15px;">{{ $aeps_balance }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:15px;">AADHAR NUMBER</td><td style="font-size:15px;">{{ $aadharnumber }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:15px;">BANK</td><td style="font-size:15px;">{{ $bank_name }}</td>
                                    </tr>
                                    
                                    <tr>
                                        <td style="font-size:15px;">ORDER ID</td><td style="font-size:15px;">{{ $order_id }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:15px;">TRANSACTION ID</td><td style="font-size:15px;">{{ $transaction_id }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:15px;">CLIENT REFRENCE ID</td><td style="font-size:15px;">{{ $client_reference_id }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:15px;">RRN NUMBER</td><td style="font-size:15px;">{{ $rrnno }}</td>
                                    </tr>
                                   
                                    
                                </table>   
                    @else
                    <table class="table invoice-table Table-Normal">
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>
                                        
                                @if($tranDtls['transaction_type'] == 'UPI')
                                    UPI Id.
                                @else
                                    Account No.
                                @endif
                                </th>
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
                                <td class="bank_account_number" id="bank_account_number"> {{ $tranDtls_gid_value['bank_account_number'] }}</td>
                                <td class="transaction_id" id="transaction_id">{{ $tranDtls_gid_value['bank_transaction_id'] }}</td>
                                <td class="reference_number" id="reference_number">{{ $tranDtls_gid_value['order_id'] }}</td>
                                <td class="text-success" style="color:#2CD07E"><i class="fa fa-check-circle"></i> SUCCESS</td>
                                <th class="label" style="text-align: left;"><i class="mdi mdi-currency-inr"></i>
                                    <span class="transaction_amount" id="transaction_amount">
                                        <img src="https://smartpaytech.in/public/template_assets/assets/images/icon/icon-rupee.png">
                                        Rs. {{ $tranDtls_gid_value['total_amount'] }}</span></th>
                            </tr>
                            @endforeach @else

                            <tr>
                                <td>1</td>
                                <td class="bank_account_number" id="bank_account_number"> {{ $tranDtls['bank_account_number'] }}</td>
                                <td class="transaction_id" id="transaction_id">{{ $tranDtls['bank_transaction_id'] }}</td>
                                <td class="reference_number" id="reference_number">{{ $tranDtls['order_id'] }}</td>
                                <td class="text-success" style="color:#2CD07E"><i class="fa fa-check-circle"></i> SUCCESS</td>
                                <th class="label" style="text-align: left;"><i class="mdi mdi-currency-inr"></i> <span class="transaction_amount" id="transaction_amount">
                                        <img src="https://smartpaytech.in/public/template_assets/assets/images/icon/icon-rupee.png">
                                        Rs.{{ $tranDtls['total_amount'] }}</span></th>
                            </tr>
                            @endif
                            <tr>
                                <td colspan="4"></td>
                                <td style="text-align: left; border: 1px solid #E9ECEF; width: 126px;">Basic Amount :</td>
                                <th class="label" style="border: 1px solid #E9ECEF; text-align: left;"><i class="mdi mdi-currency-inr"></i>
                                    <span id="transaction_amount" class="transaction_amount">
                                        <img src="https://smartpaytech.in/public/template_assets/assets/images/icon/icon-rupee.png">
                                        Rs.{{ $ttl_amt }}</span></th>
                            </tr>
                            <tr>
                                <td colspan="4" style="text-align: left"></td>
                                <td style="text-align: left; border: 1px solid #E9ECEF;">Surcharge:</td>
                                <th class="label" style="border: 1px solid #E9ECEF; text-align: left;"> <i class="mdi mdi-currency-inr"></i> <span id="surCharge" class="surCharge">
                                    <img src="https://smartpaytech.in/public/template_assets/assets/images/icon/icon-rupee.png">
                                    Rs.{{ $surcharge }}</span></th>
                            </tr>
                            <tr>
                                <td class="label text-success " colspan="4"></td>
                                <td class="label text-success " style="text-align: left; color:#2CD07E; border: 1px solid #E9ECEF;">Total:</td>
                                <th class="label text-success" style="border: 1px solid #E9ECEF; text-align: left;"><i class="mdi mdi-currency-inr"></i>
                                    <span id="total_amount" class="total_amount" style="color:#2CD07E">
                                            <img src="https://smartpaytech.in/public/template_assets/assets/images/icon/icon-rupee.png">
                                            Rs.{{ $final_amt }}</span></th>
                            </tr>
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-2">
                <button type="button" onclick="printRecipt()"  id="print_button" class="btn btn-sm success-grad" style="color:white;"><i class="mdi mdi-printer"></i> Print Invoice </button>
            </div>
        </div>
        <!-- Modal body ends-->
        <div class="row">
            <div class="col-12 text-center" style="text-align: center">
                <span class="label" style="font-size:10px; text-align: center">[ThankYou for using www.paymamaapp.in]</span>
            </div>
        </div>
    </section>
    <script>
        function printRecipt() {
                // $('#print_button').css('display', 'none');
                document.getElementById("print_button").style.display = "none";
                window.print();
                // $('#print_button').css('display', 'block');
                document.getElementById("print_button").style.display = "block";

            }
    </script>
</body>

</html>