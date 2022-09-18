<?php

// echo Auth::user()->roleId;
// exit();
?>

@extends('layouts.full_new')
@if( Auth::user()->roleId == Config::get('constants.RETAILER'))


@section('page_content')

            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="page-content container-fluid">
                @if(isset($data))
                        @if(isset($data['success']) )
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>SUCCESS</strong> {{ $data['success'] }} .
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @elseif(isset($data['error']) )

                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>FAILED</strong> {{ $data['error'] }} .
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @endif
                    @endif

                    @if(Session::has('success') )
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>SUCCESS</strong> {{ Session::get('success') }} .
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @elseif(Session::has('error')) 

                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>FAILED</strong> {{ Session::get('error') }} .
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @endif
                <!-- ============================================================== -->
                <div class="row">
                   
                    <div class="col-md-12 col-lg-4 moneytransfer">
                        <a href="{{ route('money_transfer',['money_transfer'=>'SMART_MONEY']) }}">
                            <div class="card material-card">
                                <div class="p-4">
                                    <div class="pl-4">
                                        <CENTER> <img src="{{ asset('template_new/img/smart_money_ic.png') }}">
                                            <div class="money-title"><span class="smart-title"><b>Smart</b></span> <b>Money</b></div>
                                            <button class="btn success-grad btn-rounded text-white text-uppercase font-27">
                                             ₹ {{ (isset($transfer_limits['smart_money']))? number_format($transfer_limits['smart_money'])  : '' }}
                                            </button>
                                    </div>
                                </div>

                            </div>
                        </a>
                    </div>
                    <div class="col-md-12 col-lg-4 moneytransfer">
                        <a href="{{ route('money_transfer',['money_transfer'=>'CRAZY_MONEY']) }}">
                            <div class="card material-card">
                                <div class="p-4">
                                    <div class="pl-4">
                                        <CENTER> <img src="{{ asset('template_new/img/crazy_money_ic.png') }}">
                                            <div class="money-title"><span class="smart-title"><b>Crazy</b></span> <b>Money</b></div>

                                            <button class="btn success-grad btn-rounded text-white text-uppercase font-27">
                                            ₹ {{ (isset($transfer_limits['crazy_money']))? number_format($transfer_limits['crazy_money']) : '' }}
                                            </button>
                                        </CENTER>
                                    </div>
                                </div>

                            </div>
                        </a>
                    </div>

                    <div class="col-md-12 col-lg-4 moneytransfer">
                        <a href="{{ route('money_transfer',['money_transfer'=>'BHIM_UPI']) }}">
                            <div class="card material-card">
                                <div class="p-4">
                                    <div class="pl-4">
                                        <CENTER> <img src="{{ asset('template_new/img/bhim.png') }}">
                                            <div class="money-title"><span class="smart-title"><b>BHIM</b></span> <b>UPI</b></div>
                                            <button class="btn success-grad btn-rounded text-white text-uppercase font-27">
                                            ₹ {{ (isset($transfer_limits['upi_money']))? number_format($transfer_limits['upi_money']) : '' }}
                                            </button>
                                        </CENTER>
                                    </div>
                                </div>

                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- ============================================================== -->


@endsection
@else


@section('page_content')
<link rel="stylesheet" type="text/css" href="{{ asset('template_assets\other\css\flatpickr.min.css') }}">
<link rel="stylesheet" href="{{ asset('dist\service_type\css\moneyTransfer.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('template_assets\assets\libs\select2\dist\css\select2.min.css') }}">

<section ng-app="myApp" ng-controller="moneyTransferCtrl">
   
    <div class="page-breadcrumb border-bottom mb-3 mt-3">
        <div class="row">
            <div class="col-lg-3 col-md-4 col-xs-12 align-self-center">
                <h5 class="font-medium text-uppercase mb-0">Domestic Money Transfer</h5>
            </div>
        </div>
    </div>
    
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="page-content container-fluid">
        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <div class="row">

            @foreach($moneyTranTypes as $i => $type)
            <!-- Column -->
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <h1 class="mt-0"><i class="{{ $type['icon'] }} text-info"></i></h1>
                        <h3><span class="text-danger">{{ $type['name'] }}</span> Money</h3>
                        <button class="btn  btn-sm card-btn {{ $i==0 ? 'btn-primary' : 'btn-light'}}" id="{{ $type['id'] }}" onclick="setActiveBtn('{{ $type['id'] }}')"><i class="mdi mdi-currency-inr"></i> {{ $type['minimum_amount'] }}/-</button>                        
                    </div>
                </div>
            </div>
            <!-- Column -->
            @endforeach
            
        </div>
        <!-- ============================================================== -->
        <!-- Table -->
        <!-- ============================================================== -->
        <!-- Hidden Fields required during form submition -->
        <input type="hidden" id="api_key" name="{{ Config::get('constants.RECH_MOB_DTH_RQ_KEY.API_KEY') }}" value="{{ $apiKey }}">           
        <input type="hidden" id="user_id" name="{{ Config::get('constants.RECH_MOB_DTH_RQ_KEY.USER_ID') }}" value="{{ Auth::user()->userId }}">           
        <input type="hidden" id="role_id" name="{{ Config::get('constants.RECH_MOB_DTH_RQ_KEY.ROLE_ID') }}" value="{{ Auth::user()->roleId }}">
        <input type="hidden" id="operator_id" name="{{ Config::get('constants.RECH_MOB_DTH_RQ_KEY.OPERATOR_ID') }}" value="smart">           
        
        <!-- Hidden fields ends -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <!-- <h5 class="card-title">PRODUCT SUMMARY</h5> -->

                        <div id="send-mob-dtls-section" >
                            <input type="hidden" id="sender_dtls_api" value="{{ Config::get('constants.MONEY_TRANSFER.GET_SENDER_DTLS_API') }}">
                            <input type="hidden" id="get_sender_recp_api" value="{{ Config::get('constants.MONEY_TRANSFER.GET_RECEIPIENT_LIST') }}">
                            <form id="senderMobForm">
                                <div class="row">
                                    <div class="col-md-3 col-sm-12">
                                        <div class="form-group">
                                            <label class="card-title" for="sender_mobile_number"><i class="mdi mdi-cellphone"></i> Sender Mobile Number</label>
                                            <input type="text" name="sender_mobile_number" id="sender_mobile_number" class="form-control" placeholder="Enter">
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12">
                                        <button type="button" class="btn btn-info btn-md" id="sender-mob-sb-btn" style="margin-top:35px"><i class="mdi mdi-send"></i> Send</button>
                                        <button type="button" class="btn btn-info btn-md hide-this" id="please-wait-send-mb" style="margin-top:35px"><i class="fa fa-spinner fa-pulse fa-1x"></i> &nbsp;&nbsp;Please wait...</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div id="sender-registration-section" class="hide-this">
                            <input type="hidden" id="create_sender_api" value="{{ Config::get('constants.MONEY_TRANSFER.CREATE_SENDER_API') }}">
                            <h5 class="card-title"><i class="mdi mdi-account-plus"></i> Sender Registration <i class="mdi mdi-keyboard-backspace bk-2-send-mob" style="cursor:pointer" title="Go Back"></i></h5>
                            <div class="row">
                                <div class="col-md-6 col-sm-12 sender-reg-col">
                                    <form id="senderRegForm">
                                        <div class="row">
                                            <div class="col-md-6 col-sm-12">
                                                <div class="form-group">
                                                    <label for="first_name">First Name</label>
                                                    <input type="text" class="form-control" name="first_name" id="first_name">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-12">
                                                <div class="form-group">
                                                    <label for="last_name">Last Name</label>
                                                    <input type="text" class="form-control" name="last_name" id="last_name">
                                                </div>
                                            </div>
                                            <!-- <div class="col-md-6 col-sm-12">
                                                <div class="form-group">
                                                    <label for="dob">Date of Birth</label>
                                                    <input type="text" class="form-control" name="dob" id="dob">
                                                    <label id="dob-error" class="error hide-this text-danger" for="dob">This field is required</label>
                                                </div>
                                            </div> -->
                                            <div class="col-md-4 col-sm-12">
                                                <div class="form-group">
                                                    <label for="pincode">Pincode</label>
                                                    <input type="text" class="form-control" name="pincode" id="pincode">
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-sm-12 text-center mb-4">
                                                <button type="button" class="btn btn-primary btn-md" id="registr-sender-btn" style="margin-top:28px">Send</button>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>

                        <div id="sender-details" class="hide-this">
                        <!-- <div id="sender-details"> -->
                            <div class="row mb-2">
                                <div class="col-4">
                                    <h5 class="card-title"><i class="mdi mdi-format-float-left"></i> Your Details
                                    <i class="mdi mdi-keyboard-backspace bk-2-send-mob" style="cursor:pointer" title="Go Back"></i>
                                    </h5>
                                </div>
                                <div class="col-4">
                                    <button type="button" id="add-beneficiary" class="btn btn-sm btn-warning" data-target="#addBeneficiaryMdl" data-toggle="modal"><i class="mdi mdi-currency-btc"></i> Add Beneficiary</button>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-6 border-bottom"></div>
                            </div>
                            
                            <div class="row">
                                <div class="col-4">
                                    <label id="sender-name">Name</label>
                                </div>
                                <div class="col-4">
                                    <label id="sender-av-limit" class="text-danger">Available Limit</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <label id="sender-mob-no">Number</label>
                                </div>
                                <div class="col-4">
                                    <label><i class="mdi mdi-currency-inr"></i></label>&nbsp;<label>25000 - 25000</label>
                                </div>
                            </div>

                            <!-- Sender Receipient section starts-->
                            <input type="hidden" id="delete_recep_api" value="{{ Config::get('constants.MONEY_TRANSFER.DELETE_RECEP_API') }}">
                            <div ng-if="senderReceipientList['length']" class="row receipient-div">
                                <div ng-repeat="receipient in senderReceipientList" class="col-md-6 col-sm-12" style="border:1px solid lightgray;">
                                    <div class="row mb-2 mt-4">
                                        <div class="col-6"><%= receipient['recipient_name'] %> <i class="fa fa-check-circle text-blue" title="Verified" ng-if="receipient['is_verified'] == 'Y'"></i><i class="fa fa-hourglass-half text-warning" title="Verification Pending" ng-if="receipient['is_verified'] == 'N'"></i></div>
                                        <div class="col-6 text-right">
                                            <button type="button" class="btn btn-light btn-sm" title="Payment" ng-click="loadPaymentModal(receipient)"><i class="mdi mdi-cash-100"></i> Payment</button>
                                            <button type="button" class="btn btn-danger btn-sm" title="<%= receipient['recipient_status'] == 'D' ? 'Already deleted' : 'Delete' %>" ng-disabled="receipient['recipient_status'] == 'D'" ng-click="deleteRecep(receipient['recipient_id'])"><i class="fa fa-trash"></i></button>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-12 border-bottom"></div>
                                    </div>

                                    <div class="row">
                                        <div class="col-4">Bank</div>
                                        <div class="col-4">Account No</div>
                                        <div class="col-4">IFSC</div>
                                    </div>

                                    <div class="row">
                                        <div class="col-4"><%= receipient['bank_name'] %></div>
                                        <div class="col-4"><%= receipient['bank_account_number'] %></div>
                                        <div class="col-4"><%= receipient['ifsc'] %></div>
                                    </div>

                                    <div class="row mb-3">
                                    </div>
                                </div>                                
                            </div>
                            
                            <!-- Sender Receipient section ends-->
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- End PAge Content -->
        <!-- ============================================================== -->
        
    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->

    <!-- OTP vaerification Modal starts -->
    <div class="modal fade" id="verifyOTPMdl">
        <div class="modal-dialog modal-sm">
        <div class="modal-content">
        
            <!-- Modal Header -->
            <div class="modal-header">
                <div class="textcenter">
                    <h4 class="modal-title text-danger text-uppercase ml-5">OTP Verification</h4>
                </div>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal body -->
            <div class="modal-body">
                <input type="hidden" id="verify_sender_reg_api" value="{{ Config::get('constants.MONEY_TRANSFER.VERIFY_SENDER_REG_API') }}">
                <input type="hidden" id="resend_otp_api" value="{{ Config::get('constants.MONEY_TRANSFER.RESEND_OTP_API') }}">
                <form id="verifySenderRegForm">
                    <div class="row">
                        <div class="col-12 mb-2">
                            <span class=" text-center text-dark">Please enter OTP you have received on</span>
                        </div>
                        <div class="col-12">
                            <h4 id="otp-mob-no" class="text-center text-dark">Number</h4>
                        </div>
                        
                        <div class="col-12 text-center">
                            <input id="otp-number" name="otp" type="text" class="form-control input-mask otp-mask" placeholder="OTP" autocomplete="off" style>     
                        </div>
                        <div class="col-12">
                            <a href="javascript:void(0)" id="resend-otp-lbl">Resend OTP?</a>
                            <div id="otp-timer-label" class="text-danger hide-this">Resend OTP after ( <span id="otp-resend-timer"></span> ) seconds.</div>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Modal footer -->
            <div class="modal-footer otp-mdl-footer text-center">  
                <button type="button" class="btn btn-info" id="otp-verify-btn">Verify</button>
                <button type="button" class="btn btn-info hide-this text-center" id="please-wait-varify-otp"><i class="fa fa-spinner fa-pulse fa-1x"></i> &nbsp;&nbsp;Please wait...</button>
            </div>
            
        </div>
        </div>
    </div>
    <!-- OTP verification modal ends -->

    <!-- Add Beneficiary Modal starts -->
    <div class="modal fade" id="addBeneficiaryMdl">
        <div class="modal-dialog modal-md">
        <div class="modal-content">
        
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title" >Add Beneficiary</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal body -->
            <input type="hidden" id="add_recepient_api" value="{{ Config::get('constants.MONEY_TRANSFER.CREATE_RECEPIENT_API') }}">
            <input type="hidden" id="verify_bank_ac_api" value="{{ Config::get('constants.MONEY_TRANSFER.VERIFY_BNK_AC') }}">
            <input type="hidden" id="get_bank_list_api" value="{{ Config::get('constants.MONEY_TRANSFER.GET_BANK_LIST') }}">
            <form id="beneficiaryRegForm">
                <div class="modal-body">
                        <div class="row">
                            <div class="col-6 mb-2">
                                <div class="form-group">
                                    <label for="recipient_name">Name</label>
                                    <input type="text" name="recipient_name" class="form-control">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="recipient_mobile_number">Mobile Number</label>
                                    <input type="text" name="recipient_mobile_number" class="form-control">
                                </div>
                            </div>
                            
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="bank_account_number">Account Number</label>
                                    <input type="text" name="bank_account_number" class="form-control">
                                </div>    
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="bank_code">Bank</label>
                                    <select name="bank_code" onchange="selected_Bank(this);" id="select_bank_code"  class="select2 form-control custom-select" style="width: 100%; height:36px;">
                                    <!-- <select type="text" name="bank_code" onchange="selected_Bank(this);" id="select_bank_code" class="form-control"> -->
                                        <option disabled selected>select</option>
                                        @foreach($bankList as $i =>$bank)
                                            <option value="{{ $bank['bank_code'] }}">{{ $bank['bank_name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>    
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="ifsc">IFSC</label>
                                    <input type="text" name="ifsc" class="form-control ifsc">
                                </div>    
                            </div>
                        </div>
                </div>
                
                <!-- Modal footer -->
                <div class="modal-footer otp-mdl-footer text-center">  
                    <button type="button" class="btn btn-info" id="bank-ac-verify-btn"><i id="bank-ac-verify-spinner" class="fa fa-spinner fa-pulse fa-1x hide-this"></i> Verify Accoount</button>
                    <button type="button" class="btn btn-primary" id="benf-reg-btn"> <i id="benf-reg-spinner" class="fa fa-spinner fa-pulse fa-1x hide-this"></i> Submit</button>
                </div>
            </form>
            
        </div>
        </div>
    </div>
    <!-- Add Beneficiary modal ends -->

    <!-- Payment Modal starts -->
    <div class="modal fade" id="paymentMdl">
        <div class="modal-dialog" style="max-width:400px">
        <div class="modal-content">
        
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title"><i class="mdi mdi-cash-100"></i> Payment </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal body -->
            <input type="hidden" id="fund_trn_api" value="{{ Config::get('constants.MONEY_TRANSFER.FUND_TRN_API') }}">
            <form id="paymentForm">
                <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <table class="table table-centered table-sm border-bottom mb-3">
                                    <thead>
                                        <tr>
                                            <th class="text-center" colspan="3"> <%= singleRcpInfo['recipient_name'] %> <i class="fa fa-check-circle text-blue" title="Verified" ng-if="singleRcpInfo['is_verified'] == 'Y'"></i> </th>
                                        </tr>
                                        <tr>
                                            <th class="font-sm">Bank</th>
                                            <th class="font-sm">Account No</th>
                                            <th class="font-sm">IFSC</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="font-sm"> <%= singleRcpInfo['bank_name'] %> </td>
                                            <td class="font-sm"> <%= singleRcpInfo['bank_account_number'] %> </td>
                                            <td class="font-sm"> <%= singleRcpInfo['ifsc'] %> </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-sm-12 col-md-12 mb-4 text-center">
                                <div>
                                    <label class="label">Transaction Type</label>
                                </div>
                                <ul class="radio-li">
                                    <li class="form-check mr-2">
                                        <input type="radio" class="form-check-input" value="IMPS" id="IMPS" name="transaction_type">
                                        <label class="form-check-label" for="IMPS">IMPS</label>
                                    </li>

                                    <li class="form-check">
                                        <input type="radio" class="form-check-input" value="NEFT" id="NEFT" name="transaction_type">
                                        <label class="form-check-label" for="NEFT">NEFT</label>
                                    </li>
                                </ul>
                                <label id="transaction_type-error" style="color:#ff5050;display:none" class="error" for="transaction_type">This field is required</label>
                            </div>

                            <div class="col-12 mb-2 text-center">
                                <div class="form-group">
                                    <label class="label" for="transaction_amount">Transfer Amount</label>
                                    <input type="text" name="transaction_amount" class="form-control text-center" style="width:50%;margin-left:25%" placeholder="Enter">
                                </div>
                            </div>
                            <div class="col-12 text-center">
                                <div class="form-group">
                                    <label class="label" for="remark">Remark(optional)</label>
                                    <input type="text" name="remark" class="form-control text-center" placeholder="Enter">
                                </div>
                            </div>
                            
                        </div>
                </div>
                
                <!-- Modal footer -->
                <div class="border-top mt-3 mb-3" style="padding:20px;padding-bottom:5px;">  
                    <div class="row">
                        <div class="col-6">
                            <button ng-if="singleRcpInfo['is_verified'] == 'N'" id="ac-verification-btn" type="button" class="btn btn-warning btn-md" ng-click="doVerification(singleRcpInfo)"><i id="ac-verification-spinner" class="fa fa-spinner fa-pulse fa-1x hide-this"></i> Verify Account</button>
                        </div>

                        <div class="col-6 text-right">
                            <button type="button" class="btn btn-info btn-md" ng-click="doPayment(singleRcpInfo)"><i class="fa fa-spinner fa-pulse fa-1x hide-this"></i> Proceed</button>
                        </div>
                    </div>
                </div>
            </form>
            
        </div>
        </div>
    </div>
    <!-- Payment Modal ends -->

    <!-- Confirm Payment Modal starts -->
    <div class="modal fade" id="confirmPaymentMdl">
        <div class="modal-dialog modal-sm">
        <div class="modal-content">
        
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title"><i class="mdi mdi-cash-100"></i> Confirm Payment </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal body -->
                <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <label class="label"><%= senderName %> is transfering Rs <%= transactionData['transaction_amount'] %></label>
                            </div>
                            <div class="col-12">
                                <table class="table table-centered table-sm mb-3">
                                    <tbody>
                                        <tr>
                                            <td class="font-sm text-danger"> Account No </td>
                                            <td class="font-sm"> <%= singleRcpInfo['bank_account_number'] %> </td>
                                        </tr>
                                        <tr>
                                            <td class="font-sm text-danger">IFSC Code</td>
                                            <td class="font-sm"> <%= singleRcpInfo['ifsc'] %></td>
                                        </tr>
                                        <tr>
                                            <td class="font-sm text-danger">Mode</td>
                                            <td class="font-sm"><%= transactionData['transaction_type'] %></td>
                                        </tr>
                                        <tr>
                                            <td class="font-sm text-danger">Beneficiary</td>
                                            <td class="font-sm"><%= singleRcpInfo['recipient_name'] %></td>
                                        </tr>
                                        <tr>
                                            <td class="font-sm text-danger">Amount</td>
                                            <td class="font-sm"><i class="mdi mdi-currency-inr"></i> <%= transactionData['transaction_amount'] %></td>
                                        </tr>
                                        <tr>
                                            <td class="font-sm text-danger">Total</td>
                                            <td class="font-sm"><i class="mdi mdi-currency-inr"></i> <%= transactionData['transaction_amount'] %></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                </div>
                
                <!-- Modal footer -->
                <div class="modal-footer">  
                    <div class="form-group" >
                    <label class="label" for="transaction_mpin">Enter MPIN</label>
                    <div class="form-group btn-group">
                        
                        <button type="button" class="btn btn-warning" style="pointer-events:none"><i class="mdi mdi-account-key"></i></button>
                        <input type="number" class="form-control text-center" name="transaction_mpin" placeholder="Enter MPIN" id="mpin" required>
                    </div>
                    </div>
                    
                    

                    <button type="button" class="btn btn-info" id="fund-trn-btn" ng-click="confirmPayment = true ; doPayment(singleRcpInfo)"><i id="fund-trn-spinner" class="fa fa-spinner fa-pulse fa-1x hide-this"></i> Confirm</button>
                </div>
            
        </div>
        </div>
    </div>
    <!-- Confirm Payment Modal ends -->

    <!-- Surcharge Modal starts -->
    <div class="modal fade" id="surchargeModal">
        <div class="modal-dialog modal-sm">
        <div class="modal-content">
        
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Surcharge ? </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal body -->
                <div class="modal-body">
                    <div class="form-group btn-group">
                        <button type="button" class="btn btn-warning" style="pointer-events:none"><i class="mdi mdi-currency-inr"></i></button>
                        <input type="text" class="form-control" placeholder="Enter here" ng-model="surCharge">
                        <button type="button" class="btn btn-info" ng-disabled="!surCharge" ng-click="showInvoice()">Proceed</button>
                    </div>
                </div>
        </div>
        </div>
    </div>
    <!-- Surcharge Modal ends -->

    <!-- Payment Summary Modal starts -->
    <div class="modal fade" id="pymtSummMdl">
        <div class="modal-dialog" style="max-width:400px">
        <div class="modal-content">
        
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title"><i class="mdi mdi-cash-100"></i> Transaction Status <i class="fa fa-check-circle" style="color:#32CD32"></i> </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal body -->
                <div class="modal-body">
                        <div class="row">
                            <div class="col-12 text-center">
                                <label class="text-success"><%= transactionSum['response_description'] %></label>
                            </div>

                            <div class="col-12">
                                <div class="row">
                                    <div class="col-6 font-sm">
                                        <div>Order No </div><span class="label" ng-bind="transactionSum['reference_number']"></span>
                                    </div>
                                    <div class="col-6 font-sm text-right">
                                        <div>Date</div><span ng-bind="transactionSum['fund_transfer_status'] ? transactionSum['fund_transfer_status'][0]['transaction_date'] : transactionSum['transaction_date'] "></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <table class="table table-sm mb-3" id="transaction_sum_table">
                                    <tbody>
                                        <tr>
                                            <td class="font-sm"><i class="mdi mdi-account"></i> Sender Mobile No: </td>
                                            <td class="font-sm text-right" ng-bind="transactionSum['sender_mobile_number']"></td>
                                        </tr>
                                        <tr>
                                            <td class="font-sm"> Account No: </td>
                                            <td class="font-sm text-right" ng-bind="singleRcpInfo['bank_account_number']"></td>
                                        </tr>
                                        <tr>
                                            <td class="font-sm">IFSC Code:</td>
                                            <td class="font-sm text-right" ng-bind="singleRcpInfo['ifsc']"></td>
                                        </tr>
                                        <tr>
                                            <td class="font-sm">Mode:</td>
                                            <td class="font-sm text-right" ng-bind="transactionSum['transaction_type']"></td>
                                        </tr>
                                        <tr>
                                            <td class="font-sm">Transfer Amount:</td>
                                            <td class="font-sm label text-right"><i class="mdi mdi-currency-inr"></i> <span ng-bind="transactionSum['transaction_amount']"></span></td>
                                        </tr>
                                        <tr>
                                            <td class="font-sm label">Total:</td>
                                            <td class="font-sm label text-right text-success"><i class="mdi mdi-currency-inr"></i> <span ng-bind="transactionSum['transaction_amount']"></span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                </div>
                
                <!-- Modal footer -->
                <div class="modal-footer">  
                    <button type="button" class="btn btn-sm btn-warning" ng-click="showSurchargeMdl()"><i class="mdi mdi-file-pdf"></i> Download Invoice</button>
                    <button type="button" class="btn btn-light" data-dismiss="modal">CLOSE</button>
                </div>
            
        </div>
        </div>
    </div>
    <!-- Payment Summarry Modal ends -->

    <!-- Invoice Modal starts -->
    <div class="modal fade" id="invoiceModal" style="background:white">
        <div class="modal-dialog" style="max-width:80%">
        <div class="modal-content">
        
            <!-- Modal body -->
                <div class="modal-body">
                    <div class="row">
                        <div class="col-8">
                            <img class="mt-3" src="{{ asset('template_assets/assets/images/logos/logo-text-flat.png') }}" style="width:30%">
                        </div>
                        <div class="col-4">
                            <table class="table table-sm invoice-table">
                                <thead>
                                    <tr>
                                        <th colspan="2"class="text-center">INVOICE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="font-sm">Date</td>
                                        <td class="font-sm text-right"><span ng-bind="transactionSum['fund_transfer_status'] ? transactionSum['fund_transfer_status'][0]['transaction_date'] : transactionSum['transaction_date'] "></span></td>
                                    </tr>
                                    <tr>
                                        <td class="font-sm">Ref. No.</td>
                                        <td class="label text-right font-sm text-right" ng-bind="transactionSum['fund_transfer_status'] ? transactionSum['fund_transfer_status'][0]['reference_number'] : transactionSum['reference_number']"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <table class="table table-sm invoice-table">
                                <tbody>
                                    <tr>
                                        <td>Sender Mobile No.:</td>
                                        <td class="text-right label" ng-bind="transactionSum['sender_mobile_number']"></td>
                                    </tr>
                                    <tr>
                                        <td>Transfer Type:</td>
                                        <td class="text-right label" ng-bind="transactionSum['transaction_type']"></td>
                                    </tr>
                                    <tr>
                                        <td>Ref. No.:</td>
                                        <td class="text-right label" ng-bind="transactionSum['fund_transfer_status'] ? transactionSum['fund_transfer_status'][0]['reference_number'] : transactionSum['reference_number']"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-6">
                            <table class="table table-sm invoice-table">
                                <tbody>
                                    <tr>
                                        <td>Beneficiary Name:</td>
                                        <td class="text-right label" ng-bind="singleRcpInfo['recipient_name']"></td>
                                    </tr>
                                    <tr>
                                        <td>Account No:</td>
                                        <td class="text-right label" ng-bind="singleRcpInfo['bank_account_number']"></td>
                                    </tr>
                                    <tr>
                                        <td>IFSC Code:</td>
                                        <td class="text-right label" ng-bind="singleRcpInfo['ifsc']"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <table class="table table-sm invoice-table">
                                <thead>
                                    <tr>
                                        <th>Sr. No.</th>
                                        <th>Account No.</th>
                                        <th>Transaction ID</th>
                                        <th>Ref. No</th>
                                        <th>Status</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-if="transactionSum['fund_transfer_status']" ng-repeat="data in transactionSum['fund_transfer_status']">
                                        <td ng-bind="$index+1"></td>
                                        <td ng-bind="singleRcpInfo['bank_account_number']"></td>
                                        <td ng-bind="data['transaction_id']"></td>
                                        <td ng-bind="data['reference_number']"></td>
                                        <td class="text-success"><i class="fa fa-check-circle"></i> SUCCESS</td>
                                        <td class="label"><i class="mdi mdi-currency-inr"></i> <span ng-bind="data['transaction_amount']"></span></td>
                                    </tr>
                                    <tr ng-if="!transactionSum['fund_transfer_status']">
                                        <td ng-bind="'1'"></td>
                                        <td ng-bind="singleRcpInfo['bank_account_number']"></td>
                                        <td ng-bind="transactionSum['transaction_id']"></td>
                                        <td ng-bind="transactionSum['reference_number']"></td>
                                        <td class="text-success"><i class="fa fa-check-circle"></i> SUCCESS</td>
                                        <td class="label"><i class="mdi mdi-currency-inr"></i> <span ng-bind="transactionSum['transaction_amount']"></span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                    <div class="col-8">
                        <div class="btn-group mt-5 btn-print">
                            <button type="button" ng-click="printInvoice()" class="btn btn-warning btn-sm"><i class="mdi mdi-printer"></i> Print Invoice </button>
                            <button type="button" class="btn btn-light btn-sm" data-dismiss="modal">Cancel</button>
                        </div>
                     </div>
                        <div class="col-4">
                            <table class="table table-sm invoice-table table-bordered">
                            
                                <tbody>
                                    <tr>
                                        <td>Basic Amount :</td>
                                        <td class="label"><i class="mdi mdi-currency-inr"></i> <span ng-bind="transactionSum['transaction_amount']"></span></td>
                                    </tr>
                                    <tr>
                                        <td>Surcharge:</td>
                                        <td class="label"><span> <i class="mdi mdi-currency-inr"></i> <span ng-bind="surCharge"></span></td>
                                    </tr>
                                    
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th class="label text-success">Total:</th>
                                        <th class="label text-success"><i class="mdi mdi-currency-inr"></i> <span ng-bind="sum(transactionSum['transaction_amount'],surCharge)"></span></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                
            <!-- Modal body ends-->
            <div class="row">
                <div class="col-12 text-center">
                    <span class="label" style="font-size:10px">[Thank You for using Smart Pay]</span>
                </div>
            </div>
    </div>
    <!-- Invoice Modal ends -->

</section>

<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script src="{{ asset('template_assets\other\js\jquery.inputmask.js') }}"></script>
<script src="template_assets\other\js\sweetalert.min.js"></script>
<script src="{{ asset('template_assets\other\js\flatpickr') }}"></script>
<script src="{{ asset('template_assets\other\js\angular.min.js') }}"></script>
<script src="{{ asset('dist\service_type\js\moneyTransfer.js') }}"></script>
<script src="{{ asset('dist\service_type\js\moneyTransferValidation.js') }}"></script>

<!-- This Page JS -->




<script src="{{ asset('template_assets\assets\libs\select2\dist\js\select2.full.min.js') }}"></script>
    <script src="{{ asset('template_assets\assets\libs\select2\dist\js\select2.min.js') }}"></script>
    <script src="{{ asset('template_assets\dist\js\pages\forms\select2\select2.init.js') }}"></script>
@endsection
@endif