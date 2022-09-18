{{-- @extends('layouts.full')  --}}
@extends('layouts.full_new')

@section('page_content')


<!-- This page plugin CSS -->
<link href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css"
        href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('template_assets\other\css\flatpickr.min.css') }}">
<link rel="stylesheet" href="{{ asset('dist\reports\css\reports.css') }}">

<div class="page-content container-fluid">
<!-- Recharge Reports table starts -->
    <div class="row">
        <div class="col-12">
            <div class="material-card card">
                <div class="card-body">
                    <h4 class="card-title">QR CODE PAYMENT REPORTS</h4>
                    <div class="row">
                        <div class="col-12 text-right mb-2">
                            
    
                            @if(isset($total_amt['total_amount']) && $total_amt['total_amount'])
                            <button type="button" title="Total Amount" class="btn btn-light info-button btn-md mr-2">Total Amount : {{ $total_amt['total_amount'] }}</button>
                            @endif
    
                            <a href="{{$_SERVER['REQUEST_URI']}}">
                                <button type="button" title="Refresh" class="btn btn-outline-primary btn-circle btn-md mr-2"><i class="mdi mdi-rotate-right"></i></button>
                            </a>
    
                            <button type="button" title="Apply Filter" class="btn btn-outline-info btn-circle btn-md mr-2" data-toggle="collapse" data-target="#filterBox"><i class="fa fa-filter"></i></button>
                            <button type="button" title="Export" class="btn btn-outline-dark btn-circle btn-md mr-3" data-toggle="collapse" data-target="#exportBox"><i class="fa fa-download"></i></button>
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
                                    </div>
                                    @endforeach
    
                                    <div class="filter-elements">
                                        <button class="btn btn-md btn-outline-primary btn-lg success-grad" id="filter-submit-btn" type="submit"><i class="fa fa-filter"></i> Filter</button>
                                    </div>
                                </div>
                            </form>
                        @endif
                        </div>
                        </div>
                        <div class="col-1 text-right">
                        <div class="collapse text-right" id="exportBox">
                            <div class="btn-group filter-elements">
                                @if(isset($report) && $report)
                                    <button type="submit" id="pdf-btn" class="btn btn-sm btn-warning"><i class="mdi mdi-file-pdf"></i> PDF</button>
                                @else
                                    <button type="submit"  id="pdf-btn" class="btn btn-sm btn-warning" disabled><i class="mdi mdi-file-pdf"></i> PDF</button>
                                @endif
                            </div>
                        </div>
                        </div>
                      
                    </div>
                    <br>
                    <style>
                        td{
                            border: 1px solid rgba(120,130,140,.13) !important;
                        }
                         th{
                            border: 1px solid rgba(120,130,140,.13) !important;
                        }
                    </style>
                    <div class="table-responsive">
                        <table id="recharge-report-table" class="table table-striped table-sm border is-data-table" style="border: 1px solid rgba(120,130,140,.13)!important;">
                            <thead>
                                <tr>
                                    <th>SR NO</th>
                                    @foreach($reportTH as $i => $head)
                                        @if(Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN'))
                                        <th>{{ $reportTH[$i]['name'] }}</th>
                                        @else
                                            @if($head['label'] == 'store_name' || $head['label'] == 'username' )
                                                @continue
                                            @else
                                            <th>{{ $reportTH[$i]['name'] }}</th>
                                            @endif
                                        @endif
                                    @endforeach
                                   
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($report as $index => $value)
                                    <tr>
                                        <td>{{ $index+1 }}</td>
                                        @foreach($reportTH as $i => $head)
                                            @if(Auth::userRoleAlias() != Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN'))
                                            @if($head['label'] == 'store_name' || $head['label'] == 'username' )
                                                @continue
                                            @endif
                                            @endif
                                            @if($head['label'] == 'trans_date')
                                                <td>
                                                    {{isset($report[$index]['trans_date']) && $report[$index]['trans_date'] ? (date('d/m/y H:i:s',strtotime($report[$index]['trans_date']))) : '' }}
                                                </td>
                                            @elseif($head['label'] == 'transaction_status')
                                                <td class="{{ $report[$index][$head['label']] == 'SUCCESS' ? 'text-success' : ($report[$index][$head['label']] == 'SUUCCESS' ? 'text-success' : ($report[$index][$head['label']] == 'PENDING' ? 'text-warning' : 'text-danger')) }}">
                                                {{ $report[$index][$head['label']]}}
                                                
                                                </td>
                                            @else
                                                <td> {{ $report[$index][$head['label']] }} </td>
                                            @endif
                                        @endforeach
                                        
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>SR NO</th>
                                    @foreach($reportTH as $i => $head)
                                    @if(Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN'))
                                        <th>{{ $reportTH[$i]['name'] }}</th>
                                        @else
                                            @if($head['label'] == 'store_name' || $head['label'] == 'username' )
                                                @continue
                                            @else
                                            <th>{{ $reportTH[$i]['name'] }}</th>
                                            @endif
                                        @endif
                                    @endforeach
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Recharge Reports table ends -->

<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<!--Datable plugins -->
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('template_assets/dist/js/pages/datatable/datatable-basic.init.js') }}"></script>
<!-- Datatable plugin ends -->
<script src="{{ asset('template_assets/other/js/flatpickr.js') }}"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script src="{{ asset('dist/reports/js/rechargeReport.js') }}"></script>
@endsection
