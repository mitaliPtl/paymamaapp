<!DOCTYPE html>

<html>

<head>
    <meta charset="utf-8">
    <title>{{ $data['consumer_dtls']['customer_name'] }}</title>
    <!-- CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">


</head>
<style>
    .success-grad {
    background-image: linear-gradient(to right, #251c63 , #dc182d);
    color: white;
    border-color: #ffffff;
    }
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
    
    /* tr,
    th,
    td {
        padding: 5px;
    }
     */
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
    .table td, .table th {
    padding: .50rem;
    }
</style>

<body class="body-style">
    <section class="container" style="margin-top: 50px;">

        <div class="modal-body">
            <div class="row">
                <div class="col-8 icon-col">
                    <!-- <img class="mt-3" src="https://smartpaytech.in/public/template_assets/assets/images/logos/logo-text-flat.png" style="width:250px"> -->
                    <img  src="https://smartpaytech.in/public/template_assets/assets/images/login_big_sm_py_recipt.png" style="width:150px">
                    <img  src="https://smartpaytech.in/public/template_assets/assets/images/logos/BeAssured.png" style="width:130px">
                    
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
                                <th class="" style="text-align: left">Shop Name</th>
                                <td class="text-right"><span id="#user_shope_name" class="user_shope_name">{{ $data['user_details']['shop_name'] }}</span></td>
                            </tr>
                            <tr class="border_bottom">
                                <th class="" style="text-align: left">Mobile. No.</th>
                                <td class="label text-right text-right user_mobile_no" id="user_mobile_no" > {{ $data['user_details']['mobile'] }}</td>
                            </tr>
                            <tr class="border_bottom">
                                <th class="" style="text-align: left">Email</th>
                                <td class="label text-right  text-right user_email" id="user_email">{{ $data['user_details']['email'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                    <table class="table   " style="float:left; width: 100%">
                        <tbody>
                            <tr class="border_bottom">
                                <td style="text-align: left">Customer Name :</td>
                                <th class="text-right label sender_mobile_number " id="sender_mobile_number" style="text-align: right">{{ $data['consumer_dtls']['customer_name'] }}</td>

                            </tr>
                            <tr class="border_bottom">
                                <td style="text-align: left">Date:</th>
                                    <th class="text-right label transaction_type" id="transaction_type" style="text-align: right">{{ $data['consumer_dtls']['bill_date'] }}</th>

                            </tr>
                            <tr class="border_bottom">
                                <td style="text-align: left">Bill No:</td>
                                <th class="text-right label transaction_date" id="#transaction_date" style="text-align: right">
                                   
                                {{ $data['consumer_dtls']['bill_no'] }}
                                </th>

                            </tr> 
                            <tr class="border_bottom">
                                <td style="text-align: left">Biller name:</td>
                                <th class="text-right label transaction_date" id="#transaction_date" style="text-align: right">
                                   
                                {{ $data['consumer_dtls']['biller_name'] }}
                                </th>

                            </tr>
                            
                        </tbody>
                    </table>
                    </div>
                    <div class="col-6">
                    <table class="table " style="float:right; width: 100%">
                        <tbody>
                                    
                                 
                                @if (array_key_exists("paramName",$data['inputParams']))
                                    <tr class="border_bottom">

                                    <td style="text-align: left"> {{ $data['inputParams']['paramName'] }} </td>
                                    <th class="text-right label recipient_name" id="recipient_name" style="text-align: right"> {{ $data['inputParams']['paramValue'] }} </th>
                                    </tr>
                                @else
                                    @foreach($data['inputParams'] as $key => $value)

                                    <tr class="border_bottom">

                                        <td style="text-align: left"> {{ $value['paramName'] }} </td>
                                        <th class="text-right label recipient_name" id="recipient_name" style="text-align: right"> {{ $value['paramValue'] }} </th>
                                    </tr>
                                    @endforeach

                                @endif

                                  
                            
                        </tbody>
                    </table>
                </div>

            </div>

           

            <div class="row">
                <div class="col-12">
                    <table class="table invoice-table Table-Normal" >
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
                                <td class="bank_account_number" id="bank_account_number"> {{ $data['bill_row']['transaction_id'] }}</td>
                                <td class="transaction_id" id="transaction_id">  {{ $data['bill_row']['order_id'] }} </td>
                                <td class="reference_number" id="reference_number" style="color:#2CD07E">{{ $data['bill_row']['status'] }}</td>
                                <!-- <td class="text-success" style="color:#2CD07E"><i class="fa fa-check-circle"></i> SUCCESS</td> -->
                                <th class="label" style="text-align: left;"><i class="mdi mdi-currency-inr"></i>
                                    <span class="transaction_amount" id="transaction_amount">
                                        <img src="https://smartpaytech.in/public/template_assets/assets/images/icon/icon-rupee.png">
                                        {{ $data['bill_row']['amount'] }}
                                        </span></th>
                            </tr>
                           

                           
                            <tr>
                                <td colspan="3"></td>
                                <td style="text-align: left; border: 1px solid #E9ECEF; width: 126px;">Basic Amount :</td>
                                <th class="label" style="border: 1px solid #E9ECEF; text-align: left;"><i class="mdi mdi-currency-inr"></i>
                                    <span id="transaction_amount" class="transaction_amount">
                                        <img src="https://smartpaytech.in/public/template_assets/assets/images/icon/icon-rupee.png">
                                        {{ $data['basic_amount'] }}
                                        </span></th>
                            </tr>
                            <tr>
                                <td colspan="3" style="text-align: left"></td>
                                <td style="text-align: left; border: 1px solid #E9ECEF;">Surcharge:</td>
                                <th class="label" style="border: 1px solid #E9ECEF; text-align: left;"> <i class="mdi mdi-currency-inr"></i> <span id="surCharge" class="surCharge">
                                    <img src="https://smartpaytech.in/public/template_assets/assets/images/icon/icon-rupee.png">
                                    {{ $data['subcharge'] }}
                                    </span></th>
                            </tr>
                            <tr>
                                <td class="label text-success " colspan="3"></td>
                                <td class="label text-success " style="text-align: left; color:#2CD07E; border: 1px solid #E9ECEF;">Total:</td>
                                <th class="label text-success" style="border: 1px solid #E9ECEF; text-align: left;"><i class="mdi mdi-currency-inr"></i>
                                    <span id="total_amount" class="total_amount" style="color:#2CD07E">
                                            <img src="https://smartpaytech.in/public/template_assets/assets/images/icon/icon-rupee.png">
                                            {{ $data['total_amount'] }}
                                        </span></th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-2">
                <button type="button" onclick="printRecipt()" id="print_button" class="btn success-grad btn-sm" style="color:white;"><i class="mdi mdi-printer"></i> Print Invoice </button>
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