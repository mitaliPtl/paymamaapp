@extends('layouts.full_new')
@section('page_content')


<!-- This page plugin CSS -->
<link href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css"
        href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('template_assets\other\css\flatpickr.min.css') }}">
<link rel="stylesheet" href="{{ asset('dist\reports\css\reports.css') }}">

<style>
    th {
  text-transform: uppercase;
}
</style>
<div class="page-content container-fluid">
<!-- Recharge Reports table starts -->
<div class="row">
    <div class="col-12">
       
        <div class="material-card card">
            <div class="card-body">
                <h4 class="card-title">{{ $pageName }} Reports</h4>
                <div class="row">
                    <div class="col-12 text-right mb-2">
                        @if( ($pageName == 'Money Transfer') || ($pageName == 'UPI TRANSFER') )
                            @if(isset($total['total_amt']) && $total['total_amt'])
                            <button type="button" title="Total Amount" class="btn btn-success  info-button btn-md mr-2">AMOUNT: {{ $total['total_amt'] }}</button>
                            @endif

                            @if(isset($total['PayableCharge']) && $total['PayableCharge'])
                            <button type="button" title="Total Amount" class="btn btn-info  info-button btn-md mr-2">CHARGE: {{ (float)$total['PayableCharge'] - (float)$total['TDSamount']  }}</button>
                            @endif

                            @if(isset($total['TDSamount']) && $total['TDSamount'])
                            <button type="button" title="Total Amount" class="btn btn-danger  info-button btn-md mr-2">TDS: {{ $total['TDSamount'] }}</button>
                            @endif

                            @if(isset($total['FinalAmount']) && $total['FinalAmount'])
                            <button type="button" title="Total Amount" class="btn btn-primary  info-button btn-md mr-2">NET PAYABLE: {{ $total['FinalAmount'] }}</button>
                            @endif
                            
                        @else
                            @if(isset($totalCommission['total_amount']) && $totalCommission['total_amount'])
                            <button type="button" title="Total Amount" class="btn btn-light info-button btn-md mr-2">Total Amount: {{ $totalCommission['total_amount'] }}</button>
                            @endif

                            @if(isset($totalCommission['total_rt_comm']) && $totalCommission['total_rt_comm'])
                            <button type="button" title="Total Retailer Commission" class="btn btn-danger info-button btn-md mr-2">Total RT Com: {{ $totalCommission['total_rt_comm'] }}</button>
                            @endif
                            @if(isset($totalCommission['total_dt_comm']) && $totalCommission['total_dt_comm'])
                            <button type="button" title="Total Distributor Commission" class="btn btn-warning info-button btn-md mr-2">Total DT Com: {{ $totalCommission['total_dt_comm'] }}</button>
                            @endif
                            @if(isset($totalCommission['total_ad_comm']) && $totalCommission['total_ad_comm'])
                            <button type="button" title="Total Admin Commission" class="btn btn-cyan info-button btn-md mr-2">Total Admin Com: {{ $totalCommission['total_ad_comm'] }}</button>
                            @endif

                            @if(isset($total['total_amt']) && $total['total_amt'])
                            <button type="button" title="Total Amount" class="btn btn-light info-button btn-md mr-2">Total Amount: {{ $total['total_amt'] }}</button>
                            @endif

                            @if(isset($total['total_trans_charges']) && $total['total_trans_charges'])
                            <button type="button" title="Total Amount" class="btn btn-light info-button btn-md mr-2">Total Transfer Charges: {{ $total['total_trans_charges'] }}</button>
                            @endif

                            @if(isset($total['total_trans_charges']) && $total['total_trans_charges'])
                            <button type="button" title="Total Amount" class="btn btn-light info-button btn-md mr-2">Total Transfer Charges: {{ $total['total_trans_charges'] }}</button>
                            @endif

                            @if(isset($total['PayableCharge']) && $total['PayableCharge'])
                            <button type="button" title="Total Amount" class="btn btn-light info-button btn-md mr-2">Total Payable Charges: {{ $total['PayableCharge'] }}</button>
                            @endif

                            @if(isset($total['FinalAmount']) && $total['FinalAmount'])
                            <button type="button" title="Total Amount" class="btn btn-light info-button btn-md mr-2">Total Final Amount: {{ $total['FinalAmount'] }}</button>
                            @endif

                            @if(isset($total['CCFcharges']) && $total['CCFcharges'])
                            <button type="button" title="Total Amount" class="btn btn-light info-button btn-md mr-2">Total CCF Charges: {{ $total['CCFcharges'] }}</button>
                            @endif

                            @if(isset($total['Cashback']) && $total['Cashback'])
                            <button type="button" title="Total Amount" class="btn btn-light info-button btn-md mr-2">Total Cashback: {{ $total['Cashback'] }}</button>
                            @endif

                            @if(isset($total['TDSamount']) && $total['TDSamount'])
                            <button type="button" title="Total Amount" class="btn btn-light info-button btn-md mr-2">Total TDS Amount : {{ $total['TDSamount'] }}</button>
                            @endif

                            @if(isset($total['charge_amount']) && $total['charge_amount'])
                            <button type="button" title="Total Amount" class="btn btn-light info-button btn-md mr-2">Total Charge Amount : {{ $total['charge_amount'] }}</button>
                            @endif
                        @endif
                        
                        <a href="{{$_SERVER['REQUEST_URI']}}">
                            <button type="button" title="Refresh" class="btn btn-outline-primary btn-circle btn-md mr-2"><i class="mdi mdi-rotate-right"></i></button>
                        </a>

                        <button type="button" title="Apply Filter" class="btn btn-outline-info btn-circle btn-md mr-2" data-toggle="collapse" data-target="#filterBox"><i class="fa fa-filter"></i></button>
                        <!-- <button type="button" title="Export" class="btn btn-outline-dark btn-circle btn-md mr-3" data-toggle="collapse" data-target="#exportBox"><i class="fa fa-download"></i></button> -->
                    </div>
                    
                    <div class="col-11">
                    <div class="collapse show" id="filterBox">
                    @if(isset($filtersList) && $filtersList)
                        <form action="{{ $_SERVER['REQUEST_URI'] }}" method="post">
                        @csrf
                            <input type="hidden" id="is_export" name="is_export" value="0">
                            <div class="row">

                            @foreach($filtersList as $i => $filter)
                                <div class="filter-elements">

                                        @if($filter['name'] == "from_date")
                                            <input type="text" class="form-control" id="{{ $filter['id'] }}" name="{{ $filter['name'] }}" value="{{ $request->from_date}}" placeholder="{{ $filter['label'] }}">
                                        @endif

                                        @if($filter['name'] == "to_date")
                                            <input type="text" class="form-control" id="{{ $filter['id'] }}" name="{{ $filter['name'] }}" value="{{ $request->to_date}}" placeholder="{{ $filter['label'] }}">
                                        @endif

                                        @if($filter['name'] == "username_mobile")
                                            <input type="text" class="form-control" id="{{ $filter['id'] }}" name="{{ $filter['name'] }}" value="{{ $request[$filter['name']]}}" placeholder="{{ $filter['label'] }}">
                                        @endif

                                        @if($filter['name'] == "api_id")
                                            <select name="{{ $filter['name'] }}" id="{{ $filter['id'] }}" class="form-control">
                                                <option value="" selected>{{ $filter['label'] }}</option>
                                                @foreach($apiSettings as $setting)
                                                    @if($setting->api_id == $request->api_id)
                                                        <option value="{{ $setting->api_id }}" selected> {{ $setting->api_name }}</option>
                                                    @else
                                                        <option value="{{ $setting->api_id }}"> {{ $setting->api_name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        @endif

                                        @if($filter['name'] == "service_id")
                                            <select name="{{ $filter['name'] }}" id="{{ $filter['id'] }}" class="form-control">
                                                <option selected value="">{{ $filter['label'] }}</option>
                                                @foreach($servicesTypes as $service)
                                                    @if($service->service_id == $request->service_id)
                                                        <option value="{{ $service->service_id }}" selected> {{ $service->service_name }}</option>
                                                    @else
                                                        <option value="{{ $service->service_id }}"> {{ $service->service_name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        @endif

                                        @if($filter['name'] == "operator_id")
                                            <select name="{{ $filter['name'] }}" id="{{ $filter['id'] }}" class="form-control" style="min-width:150px">
                                                <option value="" selected>{{ $filter['label'] }}</option>
                                                @foreach($operators as $operator)
                                                    @if($operator->operator_id == $request->operator_id)
                                                        <option value="{{ $operator->operator_id }}" selected> {{ $operator->operator_name }}</option>
                                                    @else
                                                        <option value="{{ $operator->operator_id }}"> {{ $operator->operator_name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        @endif
                                        

                                        @if($filter['name'] == "order_status")
                                            <select name="{{ $filter['name'] }}" id="{{ $filter['id'] }}" class="form-control">
                                                    <option value="" selected>{{ $filter['label'] }}</option>
                                                    @if($request->order_status == "SUCCESS")
                                                        <option value="SUCCESS" selected>Success</option>
                                                        <option value="PENDING">Pending</option>
                                                        <option value="FAILED">Failed</option>
                                                    @elseif($request->order_status == "PENDING")
                                                        <option value="SUCCESS">Success</option>
                                                        <option value="PENDING" selected>Pending</option>
                                                        <option value="FAILED">Failed</option>
                                                    @elseif($request->order_status == "FAILED")
                                                        <option value="SUCCESS">Success</option>
                                                        <option value="PENDING">Pending</option>
                                                        <option value="FAILED" selected>Failed</option>
                                                    @else
                                                        <option value="SUCCESS">Success</option>
                                                        <option value="PENDING">Pending</option>
                                                        <option value="FAILED">Failed</option>
                                                    @endif
                                            </select>
                                        @endif
                                </div>
                                @endforeach

                                <div class="filter-elements">
                                    <button class="btn btn-md btn-outline-primary btn-lg success-grad" id="filter-submit-btn" type="submit" style="height: calc(2.1rem + .75rem + 2px);"><i class="fa fa-filter"></i> Filter</button>
                                </div>
                            </div>
                        </form>
                    @endif
                    </div>
                    </div>
                    <div class="col-1 text-right">
                    <div class="collapse text-right" id="exportBox">
                        <div class="btn-group filter-elements">
                            @if(isset($rechargeReports) && $rechargeReports)
                                <button type="submit" id="pdf-btn" class="btn btn-sm btn-warning"><i class="mdi mdi-file-pdf"></i> PDF</button>
                            @else
                                <button type="submit"  id="pdf-btn" class="btn btn-sm btn-warning" disabled><i class="mdi mdi-file-pdf"></i> PDF</button>
                            @endif
                        </div>
                    </div>
                    </div>
                  
                </div>
                <br>
                
                <div class="table-responsive">
                    {{-- @if( Auth::user()->roleId == Config::get('constants.RETAILER')) --}}
                    <!-- <table id="config-table" class="table display table-bordered table-striped no-wrap dataTable no-footer dtr-inline report-class" > -->
                    {{-- @else --}}
                    <table id="recharge-report-table" class="table table-striped table-bordered table-sm border is-data-table">
                    {{-- @endif --}}
                        <thead>
                            <tr>
                                <th>S. NO</th>
                                @foreach($rechargeReportTH as $i => $head)
                                    <th>{{ $rechargeReportTH[$i]['name'] }}</th>
                                @endforeach
                               
                            </tr>
                        </thead>
                        <tbody>
                           
                            @foreach($rechargeReports as $index => $report)
                            
                                <tr>
                                    <td>{{ $index+1 }}</td>
                                    @foreach($rechargeReportTH as $i => $head)
                                 
                                        @if($head['label'] == 'trans_date')
                                            <td>
                                                {{isset($rechargeReports[$index]['trans_date']) && $rechargeReports[$index]['trans_date'] ? (date('d/m/y H:i:s',strtotime($rechargeReports[$index]['trans_date']))) : '' }}
                                            </td>
                                        @elseif($head['label'] == 'order_status')
                                            <td class="{{ $rechargeReports[$index][$head['label']] == 'SUCCESS' ? 'text-success' : ($rechargeReports[$index][$head['label']] == 'PENDING' ? 'text-warning' : 'text-danger') }}">
                                             {{ $rechargeReports[$index][$head['label']]}} 
                                             </td>
                                        @elseif($head['label'] == 'transaction_status')
                                            <td class="{{ $rechargeReports[$index][$head['label']] == 'Successful' ? 'text-success' : ($rechargeReports[$index][$head['label']] == 'PENDING' ? 'text-warning' : 'text-danger') }}">
                                             {{ $rechargeReports[$index][$head['label']]}} 
                                             </td>
                                             @elseif($head['label'] == 'superMerchantId')
                                            <td class="{{ $rechargeReports[$index][$head['label']] }}">
                                             {{ $rechargeReports[$index][$head['label']]}} 
                                             
                                             </td>
                                             @elseif($head['label'] == 'account_no')
                                            <td class="{{ $rechargeReports[$index][$head['label']] }}">
                                             {{ $rechargeReports[$index]['account_no']}} 
                                             
                                             </td>
                                              @elseif($head['label'] == 'order_id')
                                            <td class="{{ $rechargeReports[$index][$head['label']] }}">
                                             {{ $rechargeReports[$index][$head['label']]}} 
                                             
                                             </td>
                                           
                                              @elseif($head['label'] == 'aeps_bank_id')
                                              <td>
                                                {{ $rechargeReports[$index][$head['label']]}} 
                                              </td>
 
                                              </td>
                                              @elseif($head['label'] == 'user_mobile_no')
                                              <td>
                                                {{ $rechargeReports[$index][$head['label']]}} 
                                              </td>
                                             @elseif($head['label'] == 'recipient_id')
                                            <td class="{{ $rechargeReports[$index]['recipient_id'] }}">
                                             
                                             @php
                                             
                                              $var=$rechargeReports[$index]['recipient_id'];
                                              $dta=$var;
                                              if($dta != '')
                                              {
                                              $results = DB::select( DB::raw("SELECT * FROM tbl_dmt_benificiary_dtls WHERE recipient_id = :somevariable"), array(
                                                   'somevariable' => $dta,
                                                 ));
                                                 $array = json_decode(json_encode($results), true);
                                                $bankname=$array[0]['bank_name'];
                                              }
                                              else
                                              {
                                              $bankname='';
                                              }
                                              @endphp
                                             {{$bankname}} 
                                             </td>
                                             @elseif($head['label'] == 'bank_nameicici')
                                            <td class="{{ $rechargeReports[$index]['order_id'] }}">
                                             ICICI BANK
                                            
                                             </td>
                                              @elseif($head['label'] == 'api_alias')
                                             <td class="{{ $rechargeReports[$index]['api_alias'] }}">
                                             @if($rechargeReports[$index]['api_alias'] == 'rezorpay')
                                             RAZORPAY
                                             @else
                                             {{strtoupper($rechargeReports[$index]['api_alias'])}}
                                             @endif
                                            
                                             </td>
                                             @elseif($head['label'] == 'account_no')
                                            <td class="{{ $rechargeReports[$index]['account_no'] }}">
                                             {{ $rechargeReports[$index][$head['label']]}}
                                             </td>
                                             @elseif($head['label'] == 'transaction_id')
                                            <td class="{{ $rechargeReports[$index]['transaction_id'] }}">
                                              {{$rechargeReports[$index]['transaction_id']}}
                                             
                                             </td>
                                              @elseif($head['label'] == 'payment_type')
                                            <td class="{{ $rechargeReports[$index]['payment_type'] }}">
                                              {{strtoupper($rechargeReports[$index]['payment_type'])}}
                                             
                                             </td>
                                             
                                        @elseif($head['label'] == 'action')
                                            <td class="btn-group">
                                              
                                                @if(array_key_exists("order_status",$rechargeReports[$index]))
                                                    @if($rechargeReports[$index]['order_status'] != 'FAILED')
                                                   
                                                       @if($pageName == 'Money Transfer' or $pageName == 'ICICI_CASH_DEPOSIT' or $pageName == 'AEPS' or $pageName == 'AADHAR PAY' or $pageName == 'MINI STATEMENT')
                                                        <!-- <button type="button" id="view-invoice"  onclick="getSurcharge( {{ json_encode($report) }}, {{ $rechargeReports_forinvoice[$index] }}, {{ $index }})" class="btn btn-warning btn-sm"><i class="mdi mdi-printer"></i> View </button> -->
                                                        <button type="button" id="view-recipt"  onclick="getBillSurcharge( `{{ $rechargeReports[$index]['order_id'] }}` )" class="btn btn-warning btn-sm"><i class="mdi mdi-printer"></i> View </button>
                                                        @else
                                                            @if(($rechargeReports[$index]['order_status'] == 'PENDING') && ($pageName != 'UPI TRANSFER') )
                                                        
                                                            <a class="btn btn-sm btn-cyan sync-transaction-btn" href="{{ route('sync_transaction',$rechargeReports[$index]['order_id']) }}" title="Sync Transaction" data-toggle="tooltip"><i class="mdi mdi-sync"></i></a>
                                                            @endif
                                                        @endif
                                                        @if($user_dtls->roleId == Config::get('constants.RETAILER'))
                                                        <button class="btn btn-sm btn-danger add-complaint-btn" value="{{ $rechargeReports[$index]['order_id'] }}" title="Complaint" data-toggle="tooltip"><i class="mdi mdi-alert"></i></button>
                                                        @endif
                                                        @if($pageName == 'UPI TRANSFER' && Auth::userRoleAlias() != Config::get('constants.ROLE_ALIAS.DISTRIBUTOR') )
                                                            <!-- <button type="button" id="view-invoice"  onclick="getSurcharge( {{ json_encode($report) }}, {{ $rechargeReports_forinvoice[$index] }}, {{ $index }})" class="btn btn-warning btn-sm"><i class="mdi mdi-printer"></i> View </button> -->
                                                            <button type="button" id="view-recipt"  onclick="getBillSurcharge( `{{ $rechargeReports[$index]['order_id'] }}` )" class="btn btn-warning btn-sm"><i class="mdi mdi-printer"></i> View </button>
                                                   
                                                        @endif
                                                    @endif

                                                    @if(Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN') )
                                                        @if($rechargeReports[$index]['order_status'] == 'PENDING')
                                                            <button class="btn btn-sm btn-warning change-pending-status-btn" value="{{ $rechargeReports[$index]['order_id'] }}" title="Change Status" data-toggle="tooltip"><i class="mdi mdi-shuffle-variant"></i></button>
                                                        @elseif($rechargeReports[$index]['order_status'] == 'SUCCESS')
                                                            <button class="btn btn-sm btn-warning change-success-status-btn" value="{{ $rechargeReports[$index]['order_id'] }}" title="Change Status" data-toggle="tooltip"><i class="mdi mdi-shuffle-variant"></i></button>
                                                        @endif
                                                    @endif

                                                @elseif(array_key_exists("transaction_status",$rechargeReports[$index]))
                                                    @if($rechargeReports[$index]['transaction_status'] != 'FAILED')
                                                        @if($pageName == 'Bill Payment')
                                                        <!-- <button type="button" id="view-invoice"  onclick="getSurchargeBill(  {{ $rechargeReports_forinvoice[$index] }}, {{ $index }})" class="btn btn-warning btn-sm"><i class="mdi mdi-printer"></i> View </button> -->
                                                        <button type="button" id="view-recipt" onclick="getBillSurcharge( `{{ $rechargeReports[$index]['order_id'] }}` )" class="btn btn-warning btn-sm"><i class="mdi mdi-printer"></i> View </button>
                                                            @if($user_dtls->roleId == Config::get('constants.RETAILER'))
                                                            <button class="btn btn-sm btn-danger add-complaint-btn" value="{{ $rechargeReports[$index]['order_id'] }}" title="Complaint" data-toggle="tooltip"><i class="mdi mdi-alert"></i></button>
                                                            @endif

                                                        @endif
                                                    @endif
                                                   
                                                   
                                                @endif
                        </td>
                                        @elseif(($head['label'] == 'PayableCharge') && ( ($pageName == 'Money Transfer') || ($pageName == 'UPI TRANSFER') ) )
                                            <td> {{ (float) $report['PayableCharge'] - (float) $report['TDSamount'] }}</td>
                                           
                                        @elseif( ($pageName == 'Bill Payment') && ($head['label'] == 'response_msg')   )
                                        
                                            <td> {{-- $rechargeReports[$index][$head['label']] --}} 
                                            @php
                                                $param_status = '';
                                                if(array_key_exists("transaction_status",$rechargeReports[$index]))
                                                {
                                                    if($rechargeReports[$index]['transaction_status'] != 'FAILED') {
                                                        $param_status = "SUCCESS";
                                                    }  
                                                }elseif(array_key_exists("order_status",$rechargeReports[$index]))
                                                {
                                                    if($rechargeReports[$index]['order_status'] != 'FAILED') {
                                                        $param_status = "SUCCESS";
                                                    }
                                                }
                                                if($param_status == 'SUCCESS'){
                                                    $resp_msg = json_decode($rechargeReports[$index][$head['label']], true);
                                                    if(array_key_exists("paramName",$resp_msg['inputParams']['input']))
                                                    {
                                            @endphp
                                                        {{ $resp_msg['inputParams']['input']['paramName'] }} :  {{ $resp_msg['inputParams']['input']['paramValue'] }}
                                            @php
                                                    }else{
                                            
                                                        foreach($resp_msg['inputParams']['input'] as $inputparam){
                                            @endphp 
                                                            {{ $inputparam['paramName'] }} : {{ $inputparam['paramValue'] }}<br>
                                            @php
                                                        }
                                           
                                                    }
                                                }
                                            @endphp
                                            </td>
                                           
                                            
                                        @else
                                            <td> {{ $rechargeReports[$index][$head['label']]}} </td>
                                        @endif
                                    @endforeach
                                    
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Sr No</th>
                                @foreach($rechargeReportTH as $i => $head)
                                    <th>{{ $rechargeReportTH[$i]['name'] }}</th>
                                @endforeach
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
            </div>
        </div>
    </div>
</div>
<!-- Recharge Reports table ends -->
</div>
 <!-- Change Status modal starts -->
 <div class="modal" id="chgStatusModal" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel1" style="margin-left:60px">Change Status</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form id="chgTranStatusForm" action="{{ route('change_transaction_status') }}" method="post">
            @csrf
                <input type="hidden" name="transaction_id" id="selected_transaction_id">
                <div class="form-group container mt-2">
                    <label for="transaction_status">Select Status</label>
                    <select name="transaction_status" id="transaction_status" class="form-control" required>
                        <option selected disabled value="">Select</option>
                        <option value="SUCCESS" id="tran-sts-success-option">SUCCESS</option>
                        <option value="FAILED">FAILED</option>
                    </select>
                </div>

                <button type="submit" id="change-tran-status-btn" class="btn btn-info btn-block mt-4">
                    Update
                </button>
            </form>
        </div>
    </div>
</div>
<!-- Change Status modal ends -->
   
    @if($pageName == 'Money Transfer' || ($pageName == 'Bill Payment') || ($pageName == 'UPI TRANSFER') || ($pageName == 'AEPS') || ($pageName == 'AADHAR PAY') || ($pageName == 'MINI STATEMENT'))
    <!-- Surcharge Modal starts -->
    <div class="modal fade" id="surchargeModal">
        <div class="modal-dialog modal-md">
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
                        
                        <input type="hidden" id="recipt_ordere_id" value="">
                        <input type="hidden" id="web_url" value="{{ Config::get('constants.WEBSITE_BASE_URL') }}">

                        <!-- <input type="hidden" value="{{--@if($rechargeReports_forinvoice ) {{ json_encode($rechargeReports_forinvoice) }} @endif --}}" id="rechargeReports_forinvoice" class="rechargeReports_forinvoice" > -->
                       
                        <input type="text" id="inputsurCharge" class="form-control" placeholder="Enter here" ng-model="surCharge">
                        <!-- <button type="button" onclick="viewInvice( '{{ $pageName }}' )" class="btn btn-info" ng-disabled="!surCharge" ng-click="showInvoice()">Proceed</button> -->
                        <button type="button" onclick="showInvice()" class="btn btn-info success-grad" ng-disabled="!surCharge" ng-click="showInvoice()">Proceed</button>
                    </div>
                </div>
        </div>
        </div>
    </div>
    <!-- Surcharge Modal ends -->
    @endif

    
    @if(isset($templates) && $templates)
    <!--    Complaint  modal starts -->
    <div class="modal" id="addComplaintModel" tabindex="-1" role="dialog" aria-labelledby="addComplaintModel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel1"><span class="modal-action-name"></span>Compplaint</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form method="post" action="{{ route('add_complaint') }}" id="add_complaint">
                @csrf
                    <div class="modal-body">
                        <input type="hidden" name="complaint_order_id" id="complaint_order_id">

                        <div class="row">
                            <div class="col-12">
                                <label for="edit_temp_service">Select Complaint</label>
                                <select name="selected_comp" id="edit_temp_service" class="form-control" required>
                                    <option selected disabled value="">Select</option>
                                    @foreach($templates as $templates_key => $templates_value)
                                        <option value="{{ $templates_value['template_id'] }}" id="tran-sts-success-option"> {{ $templates_value['template'] }} </option>
                                    @endforeach
                                </select>
                               
                            </div>
                            
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-info submit-btn"> Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--   Complaint modal ends -->
    @endif

    @if(($pageName == 'Money Transfer') || ($pageName == 'UPI TRANSFER') )
    <!-- Invoice Modal starts -->
    <div class="modal fade" id="invoiceModal" style="background:white">
        <div class="modal-dialog" style="max-width:80%">
        <div class="modal-content">
        
            <!-- Modal body -->
                <div class="modal-body">
                    <div class="row">
                        <div class="col-8">
                            <!-- <img class="mt-3" src="{{-- asset('template_assets/assets/images/logos/logo-text-flat.png') --}}" style="width:30%"> -->
                            <img src="{{ asset('template_assets/assets/images/login_big_sm_py.png') }}" style="width:22%">
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
                                        <td class="label text-right font-sm text-right user_mobile_no" id="user_mobile_no" ng-bind="">{{ $user_dtls->mobile }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-sm">Email</td>
                                        <td class="label text-right font-sm text-right user_email" id="user_email" ng-bind="">{{ $user_dtls->email }}</td>
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
                                        <td class="text-right label sender_mobile_number " id="sender_mobile_number" ng-bind="transactionSum['sender_mobile_number']"></td>
                                    </tr>
                                    <tr>
                                        <td>Transfer Type:</td> 
                                        <td class="text-right label transaction_type" id="transaction_type" ng-bind="transactionSum['transaction_type']"></td>
                                    </tr>
                                    <tr>
                                        <td>Date:</td>
                                        <td class="text-right label transaction_date" id="#transaction_date" ng-bind="transactionSum['fund_transfer_status'] ? transactionSum['fund_transfer_status'][0]['reference_number'] : transactionSum['reference_number']"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-6">
                            <table class="table table-sm invoice-table">
                                <tbody>
                                    <tr>
                                        <td>Beneficiary Name:</td>
                                        <td class="text-right label recipient_name" id="recipient_name" ng-bind="singleRcpInfo['recipient_name']"></td>
                                    </tr>
                                    <tr>
                                        <td>
                                        @if($pageName == 'UPI TRANSFER') 
                                            UPI Id:
                                        @else
                                            Account No:
                                        @endif
                                        </td>
                                        <td class="text-right label bank_account_number_top" id="bank_account_number_top" ng-bind="singleRcpInfo['bank_account_number']"></td>
                                    </tr>
                                    @if($pageName == 'UPI TRANSFER') 
                                           
                                    @else
                                    <tr>
                                        
                                        <td>IFSC Code:</td>
                                        <td class="text-right label ifsc" id="ifsc" ng-bind="singleRcpInfo['ifsc']"></td>
                                    </tr>
                                    @endif
                                    
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
                                        <th>
                                            @if($pageName == 'UPI TRANSFER') 
                                                UPI Id.
                                            @else
                                                Account No.
                                            @endif
                                        </th>
                                        <th>Transaction ID</th>
                                        <th>
                                            
                                            @if($pageName == 'UPI TRANSFER') 
                                                SMART ID
                                            @else
                                                Ref. No
                                            @endif
                                        </th>
                                        <th>Status</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                @if($pageName == 'UPI TRANSFER') 
                                    <tbody >
                                        <tr>
                                            <td>1</td>
                                            <td id="upi_bank_account_number"></td>
                                            <td id="upi_transaction_id"></td>
                                            <td id="upi_reference_number"></td>
                                            <td class="text-success"><i class="fa fa-check-circle"></i> SUCCESS</td>
                                            <td class="label"><i class="mdi mdi-currency-inr"></i> <span class="transaction_amount" id="upi_transaction_amount"></span></td>
                                            
                                        </tr>

                                    </tbody>
                                @else
                                    <tbody id="same_group_id_row">
                                    
                                   
                                    </tbody>
                                @endif
                            </table>
                        </div>
                    </div>

                    <div class="row">
                    <div class="col-8">
                        <div class="btn-group mt-5 btn-print">
                            <button type="button" onclick="printRecipt()"  ng-click="printInvoice()" class="btn btn-warning btn-sm"><i class="mdi mdi-printer"></i> Print Invoice </button>
                            <button type="button"  class="btn btn-light btn-sm" data-dismiss="modal">Cancel</button>
                        </div>
                     </div>
                        <div class="col-4">
                            <table class="table table-sm invoice-table table-bordered">
                            
                                <tbody>
                                    <tr>
                                        <td>Basic Amount :</td>
                                        <td class="label"><i class="mdi mdi-currency-inr"></i> <span id="basic_amt" class="basic_amt" ng-bind="transactionSum['transaction_amount']"></span></td>
                                    </tr>
                                    <tr>
                                        <td>Surcharge:</td>
                                        <td class="label"><span> <i class="mdi mdi-currency-inr"></i> <span id="surCharge" class="surCharge" ng-bind="surCharge"></span></td>
                                    </tr>
                                    
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th class="label text-success">Total:</th>
                                        <th class="label text-success"><i class="mdi mdi-currency-inr"></i> <span id="total_amount" class="total_amount" ng-bind="sum(transactionSum['transaction_amount'],surCharge)"></span></th>
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
    @endif

    @if(($pageName == 'Bill Payment')  )
    <!-- Invoice Modal starts -->
    <div class="modal fade" id="invoiceModalBill" style="background:white">
        <div class="modal-dialog" style="max-width:80%">
        <div class="modal-content">
        
            <!-- Modal body -->
                <div class="modal-body">
                    <div class="row">
                        <div class="col-8">
                            <!-- <img class="mt-3" src="{{ asset('template_assets/assets/images/logos/logo-text-flat.png') }}" style="width:30%"> -->
                            <img class="" src="{{ asset('template_assets/assets/images/login_big_sm_py.png') }}" style="width:22%">
                            <img class="" src="{{ asset('template_assets/assets/images/logos/BeAssured.png') }}" style="width:18%">
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
                                        <td>Date</td> 
                                        <td class="text-right label bill_date" id="bill_date" ></td>
                                    </tr>
                                    <tr>
                                        <td>Bill No:</td>
                                        <td class="text-right label bill_no" id="bill_no" ></td>
                                    </tr>
                                    <tr>
                                        <td>Biller Name:</td>
                                        <td class="text-right label bill_no" id="biller_name" ></td>
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
                            <button type="button" onclick="printRecipt()"  class="btn btn-warning btn-sm"><i class="mdi mdi-printer"></i> Print Invoice </button>
                            <!-- <button type="button" onclick="printRecipt()"  class="btn btn-warning btn-sm"><i class="mdi mdi-printer"></i> Print Invoice </button> -->
                            <a type="button" href="{{ $_SERVER['REQUEST_URI'] }}" class="btn btn-light btn-sm" data-dismiss="modal">Cancel</a>
                        </div>
                        </div>
                        <div class="col-4">
                            <table class="table table-sm invoice-table table-bordered">
                            
                                <tbody>
                                    <tr>
                                        <td>Basic Amount :</td>
                                        <td class="label"><i class="mdi mdi-currency-inr"></i> <span id="bill_basic_amt" class="bill_basic_amt" ></span></td>
                                    </tr>
                                    <tr>
                                        <td>Surcharge:</td>
                                        <td class="label"><span> <i class="mdi mdi-currency-inr"></i> <span id="bill_surCharge" class="bill_surCharge" ></span></td>
                                    </tr>
                                    
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th class="label text-success">Total:</th>
                                        <th class="label text-success"><i class="mdi mdi-currency-inr"></i> <span id="bill_total_amount" class="bill_total_amount"></span></th>
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
    @endif




<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<!--Datable plugins -->
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('template_assets/dist/js/pages/datatable/datatable-basic.init.js') }}"></script>
<!-- Datatable plugin ends -->
<script src="{{ asset('template_assets/other/js/flatpickr.js') }}"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script src="{{ asset('dist\reports\js\rechargeReport.js') }}"></script>
@endsection