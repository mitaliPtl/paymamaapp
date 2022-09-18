@extends('layouts.full')

@section('page_content')
<link rel="stylesheet" type="text/css" href="{{ asset('dist\service_type\css\service.css') }}">
<section ng-app="myApp" ng-controller="rechargeCtrl">
<div class="row">
    <!-- Hidden Fields Starts-->
    <input type="hidden" id="operator-list" value="{{ json_encode($operatorList) }}">
    <!-- Hidden Fields Ends -->

    <div class="col-12">
        <div class="material-card card" style="padding-bottom:100%">
            <div class="card-body">
                <h4 class="card-title">Recharge & Bill Payments</h4>
                <br>
                <button type="button" id="clearMobScopes" class="hide-this" ng-click="clearMobScopes()"></button>
                <button type="button" id="clearDTHScopes" class="hide-this" ng-click="clearDTHScopes()"></button>
                <ul class="nav nav-pills mb-4">
                    @foreach($serviceList as $i => $service)
                        @if($service['key'] == $paymentType)
                            
                            <!-- <li class="nav-item"><a class="nav-link active" data-toggle="pill" href="#{{-- $service['key'] --}}" onclick="resetFormId('{{--$service['key']--}}')">{{-- $service['name'] --}}</a></li> -->
                            <li class="nav-item"><a class="nav-link active"  href="{{ route($service['route'],['type' => $service['key']]) }}">{{ $service['name'] }}</a></li>
                           
                        @else
                           
                            <!-- <li class="nav-item"><a class="nav-link " data-toggle="pill" href="#other_recharge" onclick="resetFormId('{{--$service['key']--}}')">{{-- $service['name'] --}}</a></li> -->
                            <li class="nav-item"><a class="nav-link "  href="{{ route($service['route'],['type' => $service['key']]) }}" >{{ $service['name'] }}</a></li>
                           
                        @endif
                    @endforeach
                </ul> 
                <div class="tab-content">
                    <input type="hidden" id="recharge_api" value="{{ Config::get('constants.RECHARGE_API') }}">

                    <!-- Hidden Fields required during form submition -->
                    <input type="hidden" id="api_key" name="{{ Config::get('constants.RECH_MOB_DTH_RQ_KEY.API_KEY') }}" value="{{ $apiKey }}">           
                    <input type="hidden" id="user_id" name="{{ Config::get('constants.RECH_MOB_DTH_RQ_KEY.USER_ID') }}" value="{{ Auth::user()->userId }}">           
                    <input type="hidden" id="role_id" name="{{ Config::get('constants.RECH_MOB_DTH_RQ_KEY.ROLE_ID') }}" value="{{ Auth::user()->roleId }}">           
                    <!-- Hidden fields ends -->
                    
                    @foreach($serviceList as $i => $service)
                               

                        @if($service['key'] == "mobile")
                            <div class="tab-pane {{ $service['key'] == $paymentType ? 'active' : '' }}" id="{{ $service['key']}}">
                                <h4 class="mb-5"><i class="{{ $service['icon'] }}"></i> Phone Recharge</h4>
                                <form id="mobileRechargeForm">
                                    <div class="row">                                 
                                        <input type="hidden" name="payment_type" value="mobile">           
                                        <div class="col-sm-12 col-md-3 col-lg-2">
                                            <div>
                                                <label>Select Service Type</label>
                                            </div>
                                            <ul class="radio-li">
                                                <li class="form-check mr-2">
                                                    <input type="radio" class="form-check-input" value="Prepaid" id="prepaid" name="service_type">
                                                    <label class="form-check-label" for="prepaid">Prepaid</label>
                                                </li>

                                                <li class="form-check">
                                                    <input type="radio" class="form-check-input" value="Postpaid" id="postpaid" name="service_type">
                                                    <label class="form-check-label" for="postpaid">Postpaid</label>
                                                </li>
                                            </ul>
                                            <label id="service_type-error" style="color:#ff5050;display:none" class="error" for="service_type">This field is required</label>
                                        </div>

                                        <div class="col-sm-12 col-md-2">
                                            <div class="form-group">
                                                <label for="mobile_no">Mobile Number</label>
                                                <input type="number" class="form-control" id="phone_mobile_no" name="{{ Config::get('constants.RECH_MOB_DTH_RQ_KEY.MOBILE_NO') }}">
                                            </div>
                                        </div>

                                        <div class="col-sm-12 col-md-2">
                                            <div class="form-group">
                                                <label for="operator_id">Select Operator</label>
                                                <select name="{{ Config::get('constants.RECH_MOB_DTH_RQ_KEY.OPERATOR_ID') }}" id="all_operator_id" class="form-control">
                                                <option disabled selected value="">Select</option>
                                                @if(isset($operatorList))
                                                @foreach($operatorList as $i => $operator)
                                                    @if($operator['servicesType']['alias'] == Config::get('constants.SERVICE_TYPE_ALIAS.MOBILE_PREPAID'))
                                                        <option value="{{ $operator['operator_id'] }}">{{ $operator['operator_name'] }}</option>
                                                    @endif
                                                    @if($operator['servicesType']['alias'] == Config::get('constants.SERVICE_TYPE_ALIAS.MOBILE_POSTPAID'))
                                                        <option value="{{ $operator['operator_id'] }}">{{ $operator['operator_name'] }}</option>
                                                    @endif
                                                @endforeach
                                                @endif
                                                </select>

                                                <select name="{{ Config::get('constants.RECH_MOB_DTH_RQ_KEY.OPERATOR_ID') }}" id="pre_operator_id" class="form-control hide-this">
                                                    <option disabled selected>Select</option>
                                                    @if(isset($operatorList))
                                                    @foreach($operatorList as $i => $operator)
                                                        @if($operator['servicesType']['alias'] == Config::get('constants.SERVICE_TYPE_ALIAS.MOBILE_PREPAID'))
                                                            <option value="{{ $operator['operator_id'] }}">{{ $operator['operator_name'] }}</option>
                                                        @endif
                                                    @endforeach
                                                    @endif
                                                </select>

                                                <select name="{{ Config::get('constants.RECH_MOB_DTH_RQ_KEY.OPERATOR_ID') }}" id="post_operator_id" class="form-control hide-this">
                                                    <option disabled selected>Select</option>
                                                    @if(isset($operatorList))
                                                    @foreach($operatorList as $i => $operator)
                                                        @if($operator['servicesType']['alias'] == Config::get('constants.SERVICE_TYPE_ALIAS.MOBILE_POSTPAID'))
                                                            <option value="{{ $operator['operator_id'] }}">{{ $operator['operator_name'] }}</option>
                                                        @endif
                                                    @endforeach
                                                    @endif
                                                </select>
                                                <label id="all_operator_id-error" class="error hide-this" for="all_operator_id">This field is required</label>
                                                <label id="pre_operator_id-error" class="error hide-this" for="pre_operator_id">This field is required</label>
                                                <label id="post_operator_id-error" class="error hide-this" for="post_operator_id">This field is required</label>
                                            </div>
                                        </div>
                                       
                                        <div class="col-sm-12 col-md-3">
                                            <div class="form-group">
                                                <label for="amount">Amount 
                                                    <button type="button" class="btn btn-sm btn-warning ml-2 view-plan-btn" data-target="#allPlanModal" data-toggle="modal" ng-if="opCircle">View Plan</button>
                                                    <button type="button" class="btn btn-sm btn-danger ml-2 view-plan-btn" data-target="#offers121Modal" data-toggle="modal" ng-if="offers121Data.length">121 Offers</button>
                                                </label>
                                                <input type="number" class="form-control" id="phone_amount" name="{{ Config::get('constants.RECH_MOB_DTH_RQ_KEY.AMOUNT') }}">
                                            </div>
                                        </div>
                                        
                                        <div class="col-2">
                                            <button type="button" id="mobile-submit-btn" class="btn btn-primary btn-md proceed-button"><i class="fa fa-spinner fa-pulse fa-1x hide-this"></i> Proceed</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @elseif($service['key'] == "dth")

                        
                            <div class="tab-pane {{ $service['key'] == $paymentType ? 'active' : '' }}" id="{{ $service['key']}}">
                                <h4 class="mb-5"><i class="{{ $service['icon'] }}"></i> DTH Recharge</h4>
                                <form id="dthRechargeForm">
                                     
                                    <div class="row">
                                        <input type="hidden" name="payment_type" value="dth"> 
                                        <div class="col-sm-12 col-md-2">
                                            <div class="form-group">
                                                <label for="operator_id">Select Operator</label>
                                                <select name="{{ Config::get('constants.RECH_MOB_DTH_RQ_KEY.OPERATOR_ID') }}" id="dth_operator_id" class="form-control">
                                                    <option disabled selected>Select</option>
                                                    @foreach($operatorList as $i => $operator)
                                                    @if($operator['servicesType']['alias'] == Config::get('constants.SERVICE_TYPE_ALIAS.DTH'))
                                                            <option value="{{ $operator['operator_id'] }}">{{ $operator['operator_name'] }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-2">
                                            <div class="form-group">
                                                <!-- Key is "mobile_no" for Customer ID -->
                                                <label for="mobile_no">Customer ID
                                                    <button type="button" class="btn btn-sm btn-danger ml-2 view-plan-btn" data-target="#dthAcInfoModal" data-toggle="modal" ng-if="dthAcInfo">Account Info</button>
                                                </label>
                                                <input type="text" class="form-control" id="dth_mobile_no" name="{{ Config::get('constants.RECH_MOB_DTH_RQ_KEY.MOBILE_NO') }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-2">
                                            <div class="form-group">
                                                <label for="amount">Amount
                                                    <button type="button" class="btn btn-sm btn-warning ml-2 view-plan-btn" data-target="#allPlanModal" data-toggle="modal" ng-if="allDTHPlans.length">View Plan</button>
                                                </label>
                                                <input type="number" class="form-control" id="dth_amount" name="{{ Config::get('constants.RECH_MOB_DTH_RQ_KEY.AMOUNT') }}">
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <button type="button" id="dth-submit-btn" class="btn btn-primary btn-md proceed-button"><i class="fa fa-spinner fa-pulse fa-1x hide-this"></i> Proceed</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        

                        @else
                            <!-- <input type="hidden" value="{{-- $pay_response --}} " id="pay_response" name="pay_response"> -->

                            <input type="hidden" id="operator_{{ $service['key'] }}" name="operator_{{ $service['key'] }}" value="17">
                            <input type="hidden" value="{{ Config::get('constants.BILL_PAYMENTS_API.ELECTRICITY.GET_BILLER_LIST') }}" id="get_biller_list" name="get_biller_list">
                            <input type="hidden" value="{{ Config::get('constants.BILL_PAYMENTS_API.ELECTRICITY.GET_BILLER_DETAILS') }}" id="get_biller_details" name="get_biller_details">
                            <input type="hidden" value="{{ Config::get('constants.BILL_PAYMENTS_API.ELECTRICITY.PAY_BILL') }}" id="pay_bill" name="pay_bill">
                            <input type="hidden" value="{{ json_encode($biller) }}" id="biller_list" name="biller_list">
                            <div class="tab-pane {{ $service['key'] == $paymentType ? 'active' : '' }}" id="{{ $service['key']}}">
                            <!-- <div class="tab-pane {{-- $service['key'] == $paymentType ? 'active' : '' --}}" id="other_recharge"> -->
                                <h4 class="mb-5"><i class="{{ $service['icon'] }}"></i> {{ $service['name'] }}</h4>
                                <div id="error_msg" style="color:red;"></div>
                                <form id="electricityPaymentForm" >
                                    <div class="row">
                                        
                                        <div class="col-sm-12 col-md-2">
                                            <div class="form-group">
                                                <label for="bill_type">Select Oparator</label>
                                                <select name="bill_type" id="bill_type" class="form-control bill_type">
                                                    <option disabled selected>Select</option>
                                                    @if(isset($biller) && (count($biller)>0) && $biller['result'] )
                                                        @foreach( $biller['result'] as $biller_key => $biller_value)
                                                            <option value="{{ $biller_value['billerId'] }}">{{ $biller_value['billerName'] }}</option>                                                            
                                                        @endforeach
                                                    @endif
                                                  
                                                    <!-- <option value="prepaid">Apartments</option> -->
                                                </select>
                                            </div>
                                        </div>
                                        <div id="inputParams" class="inputParams">
                                        </div>
                                        
                                        <!-- <div class="col-sm-12 col-md-2">
                                            <div class="form-group">
                                                <label for="state_id">Select State</label>
                                                <select name="state_id" id="state_id" class="form-control">
                                                    <option disabled selected>Select</option>
                                                    <option value="prepaid">Maharashtra</option>
                                                    <option value="prepaid">Karnataka</option>
                                                </select>
                                            </div>
                                        </div> -->
                                        <div class="col-sm-12 col-md-2">
                                            <button type="submit" class="btn btn-primary btn-md proceed-button" id="view-bill-elect">Proceed</button>
                                           
                                        </div>
                                        <div class="col-sm-12 col-md-1" style="display: none;" id="view_loader"> 
                                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </div>
                                </form>

                                <div id="bill_details" style="display:none;">
                                        <div class="row">
                                            <div class="col-sm-12 col-md-2">

                                            </div>
                                            <div class="col-sm-12 col-md-2">
                                                
                                            </div>
                                        </div>
                                </div>
                            </div>
                        @endif
                        
                       

                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mpin Modal starts -->
<div class="modal fade" id="mpinModal">
    <div class="modal-dialog modal-sm">
    <div class="modal-content">
    
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Enter Mpin </h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <form action="javascript:void(0)" id="mpinForm">
            <div class="modal-body">
                <div class="form-group btn-group">
                    <button type="button" class="btn btn-warning" style="pointer-events:none"><i class="mdi mdi-account-key"></i></button>
                    <input type="number" class="form-control text-center" placeholder="Enter here" id="mpin" required>
                    <button type="submit" class="btn btn-info hide-this" id="mobile-mpin-btn">Proceed</button>
                    <button type="submit" class="btn btn-info hide-this" id="dth-mpin-btn">Proceed</button>
                </div>
            </div>
        </form>
    </div>
    </div>
</div>
<!-- Mpin Modal ends -->

<!-- All Plan modal starts -->
<div class="modal" id="allPlanModal" tabindex="-1" role="dialog" aria-labelledby="allPlanModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius:0px">
            <div class="modal-header">
                <span class="modal-title" id="exampleModalLabel1"><span class="modal-action-name"></span>Available Plans</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <!-- Mobile Recharge Plan starts -->
            <div class="modal-body" ng-if="opCircle">
                <ul class="nav nav-pills all-plan-pills mb-4">
                   
                    <li class="nav-item" ng-repeat="type in planType"><a id="nav-link-<%= $index+1 %>" ng-click="getAvailablePlans(type)" class="nav-link" data-toggle="pill" href="javascript:void(0)" ><span ng-bind="type"></span></a></li>
                       
                </ul> 

                <div class="row" ng-if="allPlans.length">
                    <div class="col-12">
                        <table class="table table-sm table-centered">
                            <tbody>
                                <tr>
                                    <td class="label">Amount</td>
                                    <td class="label">Details</td>
                                    <td class="label">Talktime</td>
                                    <td class="label">Validity</td>
                                </tr>
                                <tr class="table-spinner hide-this">
                                    <td colspan="4" style="padding-top:25px;">
                                        <i class="fa fa-spinner fa-2x fa-spin"></i>
                                       <div> Loading...please wait!</div>
                                    </td>
                                </tr>

                                <tr ng-repeat="plan in allPlans" style="cursor:pointer" ng-click="setFinalAmnt(plan.amount)">
                                    <td colspan="4" ng-if="plan.error" ng-bind="plan.error"></td>
                                    <td ng-if="!plan.error" ><i class="mdi mdi-currency-inr"></i> <span ng-bind="plan.amount"></span></td>
                                    <td ng-if="!plan.error" ng-bind="plan.detail"></td>
                                    <td ng-if="!plan.error" ng-bind="plan.talktime"></td>
                                    <td ng-if="!plan.error" ng-bind="plan.validity"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Mobile Recharge Plan Ends -->

            <!-- DTH Recharge Plan starts -->
            <div class="modal-body" ng-if="allDTHPlans.length">

                <div class="row" ng-if="allDTHPlans.length">
                    <div class="col-12">
                        <table class="table table-sm table-centered">
                            <tbody>
                                <tr>
                                    <td class="label">Amount</td>
                                    <td class="label">Plan Name</td>
                                    <td class="label">Details</td>
                                    <td class="label">Validity</td>
                                </tr>
                                <tr class="table-spinner hide-this">
                                    <td colspan="4" style="padding-top:25px;">
                                        <i class="fa fa-spinner fa-2x fa-spin"></i>
                                       <div> Loading...please wait!</div>
                                    </td>
                                </tr>

                                <tr ng-repeat="plan in allDTHPlans" style="cursor:pointer" ng-click="setFinalDTHAmnt(plan.Amount)">
                                    <td colspan="4" ng-if="plan.error" ng-bind="plan.error"></td>
                                    <td ng-if="!plan.error" ><i class="mdi mdi-currency-inr"></i> <span ng-bind="plan.Amount"></span></td>
                                    <td ng-if="!plan.error" ng-bind="plan.PlanName"></td>
                                    <td ng-if="!plan.error" ng-bind="plan.Description"></td>
                                    <td ng-if="!plan.error" ng-bind="plan.Validity"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- DTH Recharge Plan Ends -->
        </div>
    </div>
</div>
<!-- All Plan modal ends -->

<!-- 121 Offers modal starts -->
<div class="modal" id="offers121Modal" tabindex="-1" role="dialog" aria-labelledby="offers121Modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius:0px">
            <div class="modal-header">
                <span class="modal-title" id="exampleModalLabel1"><span class="modal-action-name"></span>Available Offers</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>

            <div class="modal-body" ng-if="offers121Data.length">

                <div class="row" ng-if="offers121Data.length">
                    <div class="col-12">
                        <table class="table table-sm table-centered">
                            <tbody>
                                <tr>
                                    <td class="label">Price</td>
                                    <td class="label">Details</td>
                                </tr>
                                <tr class="table-spinner hide-this">
                                    <td colspan="4" style="padding-top:25px;">
                                        <i class="fa fa-spinner fa-2x fa-spin"></i>
                                       <div> Loading...please wait!</div>
                                    </td>
                                </tr>

                                <tr ng-repeat="plan in offers121Data" style="cursor:pointer" ng-click="setFinalAmnt(plan.price)">
                                    <td ><i class="mdi mdi-currency-inr"></i> <span ng-bind="plan.price"></span></td>
                                    <td ng-bind="plan.ofrtext"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- 121 Offers modal ends -->

<!-- DTH Account Info modal starts -->
<div class="modal" id="dthAcInfoModal" tabindex="-1" role="dialog" aria-labelledby="dthAcInfoModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius:0px">
            <div class="modal-header">
                <span class="modal-title" id="exampleModalLabel1"><span class="modal-action-name"></span><i class="mdi mdi-television-guide"></i> Account Information</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>

            <div class="modal-body" ng-if="dthAcInfo">

                <div class="row">
                    <div class="col-12">
                        <table class="table table-sm table-font-sm">
                            <tbody>
                                <tr>
                                    <td class="label"><i class="mdi mdi-account"></i> Name:</td>
                                    <td  ng-bind="dthAcInfo.Name"></td>
                                    <td class="label"><i class="mdi mdi-account-box"></i> Customer Id:</td>
                                    <td ng-bind="dthAcInfo.vc"></td>
                                </tr>

                                <tr>
                                    <td class="label"><i class="mdi mdi-cellphone"></i> Registered No.:</td>
                                    <td  ng-bind="dthAcInfo.Rmn"></td>
                                    <td class="label">Current Balance: <i class="mdi mdi-currency-inr"></i></td>
                                    <td ng-bind="dthAcInfo.Balance"></td>
                                </tr>

                                <tr>
                                    <td class="label"><i class="mdi mdi-calendar"></i> Next Recharge Date:</td>
                                    <td  ng-bind="dthAcInfo['Next Recharge Date']"></td>
                                    <td class="label"><i class="mdi mdi-television"></i> Plan:</td>
                                    <td ng-bind="dthAcInfo.Plan"></td>
                                </tr>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- DTH Account Info modal ends -->

<!--  Pay Bill modal starts -->
<div class="modal" id="payBillModel" tabindex="-1" role="dialog" aria-labelledby="payBillModel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel1"><span class="modal-action-name"></span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <!-- <form method="post" action="{{-- route('pay_elect_bill') --}}" id="pay_elect_bill"> -->
            <form method="post" action="" id="pay_elect_bill">
            @csrf
                <div class="modal-body">
                    <input type="hidden" name="pay_token" id="pay_token">
                    <input type="hidden" name="pay_user_id" id="pay_user_id">
                    <input type="hidden" name="pay_role_id" id="pay_role_id">
                    <input type="hidden" name="pay_biller_id" id="pay_biller_id">
                    <input type="hidden" name="pay_order_id" id="pay_order_id">
                    <input type="hidden" name="pay_operator_id" id="pay_operator_id" value="17">
                   
                   

                    <div class="row">

                        <div class="col-12">
                            <div class="form-group">
                                <label for="payamount"> Amount</label>
                                <input type="number" class="form-control" id="payamount" name="payamount" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="pay_mpin">Enter MPin</label>
                                <input type="number" class="form-control" id="pay_mpin" name="pay_mpin" required>
                            </div>
                        </div>

                        
                        
                    </div>
                </div>
                <div class="modal-footer">
                   
                    <button type="button" class="btn btn-primary btn-block submit-btn sub-pay" id="sub-pay"><i class="fa fa-paper-plane"></i> PAY</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Pay Bill modal ends -->


<!--  Pay Bill modal starts -->
<div class="modal" id="payBillRecipt" tabindex="-1" role="dialog" aria-labelledby="payBillRecipt" style="background:white">
    <!-- <input type="hidden" id="pay_result" value="{{-- json_encode($pay_result) --}}"> -->
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel1"><span class="modal-action-name"></span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <!-- <form method="post" action="{{-- route('pay_elect_bill') --}}" id="pay_elect_bill"> -->
           
                <div class="modal-body">
                   <div class="row" id="showRecipt" style="display:none">
                       
                        <a class="btn btn-warning btn-sm  " id="" onclick="subCharges()"> <i class="fa fa-print"></i>Print </a>
                    </div>
                    <div class="row" id="failedRecipt" style="display:none">
                       
                        
                    </div>
                </div>
                <div class="modal-footer">
                   
                    <a href="{{ $_SERVER['REQUEST_URI'] }}" class="btn btn-primary btn-block submit-btn sub-pay" id="sub-pay"> OK </a>
                </div>
        </div>
    </div>
</div>
<!-- Pay Bill modal ends -->


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
                                    <td class="font-sm">Shope Name  </td>
                                    <td class="font-sm text-right"><span id="#user_shope_name" class="user_shope_name">{{ $user_dtls->store_name }}</span></td>
                                </tr>
                                <tr>
                                    <td class="font-sm">Mobile. No.</td>
                                    <td class="label text-right font-sm text-right user_mobile_no" id="user_mobile_no">{{ $user_dtls->mobile }}</td>
                                </tr>
                                <tr>
                                    <td class="font-sm">Email</td>
                                    <td class="label text-right font-sm text-right user_email" id="user_email">{{ $user_dtls->email }}</td>
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
                                    <td>Customer Name :</td>
                                    <td class="text-right label customer_name " id="customer_name" ></td>
                                </tr>
                                <tr>
                                    <td>Bill Date</td> 
                                    <td class="text-right label bill_date" id="bill_date" ></td>
                                </tr>
                                <tr>
                                    <td>Bill No:</td>
                                    <td class="text-right label bill_no" id="bill_no" ></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-6">
                        <table class="table table-sm invoice-table input_param">
                            <tbody id="input_param">
                                <tr>
                                    <td>Consumer No.:</td>
                                    <td class="text-right label consumer_no" id="consumer_no" ></td>
                                </tr>
                                <tr>
                                    <td>BU No:</td>
                                    <td class="text-right label bu_no" id="bu_no" ></td>
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
                                    <th>Transaction ID</th>
                                    <th>Smart ID</th>
                                    <th>Status</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody id="bill_row">
                               
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row">
                <div class="col-8">
                    <div class="btn-group mt-5 btn-print">
                        <button type="button" onclick="subCharges()"  class="btn btn-warning btn-sm"><i class="mdi mdi-printer"></i> Print Invoice </button>
                        <!-- <button type="button" onclick="printRecipt()"  class="btn btn-warning btn-sm"><i class="mdi mdi-printer"></i> Print Invoice </button> -->
                        <a type="button" href="{{ $_SERVER['REQUEST_URI'] }}" class="btn btn-light btn-sm" data-dismiss="modal">Cancel</a>
                    </div>
                    </div>
                    <div class="col-4">
                        <table class="table table-sm invoice-table table-bordered">
                        
                            <tbody>
                                <tr>
                                    <td>Basic Amount :</td>
                                    <td class="label"><i class="mdi mdi-currency-inr"></i> <span id="basic_amt" class="basic_amt" ></span></td>
                                </tr>
                                <tr>
                                    <td>Surcharge:</td>
                                    <td class="label"><span> <i class="mdi mdi-currency-inr"></i> <span id="surCharge" class="surCharge" ></span></td>
                                </tr>
                                
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="label text-success">Total:</th>
                                    <th class="label text-success"><i class="mdi mdi-currency-inr"></i> <span id="total_amount" class="total_amount"></span></th>
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
                    
                    <input type="hidden" id="bill_forinvoice" class="bill_forinvoice" >
                    
                    <input type="text" id="inputsurCharge" class="form-control" placeholder="Enter here" ng-model="surCharge">
                    <button type="button" onclick="printRecipt()" class="btn btn-info" ng-disabled="!surCharge" ng-click="showInvoice()">Proceed</button>
                </div>
            </div>
    </div>
    </div>
</div>
<!-- Surcharge Modal ends -->


</section>
<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script src="{{ asset('template_assets\other\js\jquery.inputmask.js') }}"></script>
<script src="{{ asset('template_assets\other\js\angular.min.js') }}"></script>
<script src="{{ asset('dist\service_type\js\service.js') }}"></script>
<script src="{{ asset('dist\service_type\js\serviceValidation.js') }}"></script>
<script src="{{ asset('dist\bank\js\balanceReqValidation.js') }}"></script>

@endsection