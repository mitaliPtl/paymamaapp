{{-- @extends('layouts.full') --}}
@extends('layouts.full_new')
@section('page_content')



<!-- This page plugin CSS -->
<link href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css"
        href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('template_assets/other/css/flatpickr.min.css') }}">
<link rel="stylesheet" href="{{ asset('dist/reports/css/reports.css') }}">

@if( Auth::user()->roleId != Config::get('constants.DISTRIBUTOR') ||  Auth::user()->roleId != Config::get('constants.MASTER_DISTRIBUTOR'))
<section>
@endif
@if( Auth::user()->roleId == Config::get('constants.DISTRIBUTOR') || Auth::user()->roleId == Config::get('constants.MASTER_DISTRIBUTOR'))
<div class="page-content container-fluid" style="width: 98%; margin-left: 20px;height:790px !important;">
@endif
<style>
    th {
  text-transform: uppercase;
}
</style>
        <!-- Complaint Reports table starts -->
<div class="row">
    <div class="col-12">
        <div class="material-card card">
            <div class="card-body">
                <h4 class="card-title" style="font-weight:bold;color:#BE1D2C;">{{ $pageName }}  CREDIT REPORT</h4>
                <div class="row">
                    <div class="col-12 text-right mb-2">
                       
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
                                    <button class="btn btn-lg btn-outline-primary success-grad" style="height: calc(2.1rem + .75rem + 2px);" id="filter-submit-btn" type="submit"><i class="fa fa-filter"></i> Filter</button>
                                </div>
                            </div>
                        </form>
                    @endif
                    </div>
                    </div>
                    <div class="col-1 text-right">
                    <div class="collapse text-right" id="exportBox">
                        <div class="btn-group filter-elements">
                            @if(isset($records) && $records)
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
                    <table id="recharge-report-table" class="table display table-bordered table-striped no-wrap">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <!-- <th>Name</th> -->
                                <th>DATE & TIME</th>
                                <th>ORDER ID</th> 
                                <th>Type</th>
                                <th>Debit</th>
                                <th>Credit</th>
                                <th>REMAINING BALANCE AMOUNT</th>
                                
                               
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                           // print_r($records);
                        ?>
                            @foreach($records as $index => $record_value)
                                <tr>
                                    <td>{{ $index+1 }}</td>
                                    
                                    <td>{{ date('d/m/y H:i:s',strtotime($record_value->trans_date)) }}</td>
                                    <td>{{ $record_value->order_id }}</td>
                                    <td>{{ $record_value->transaction_type }}</td>
                                      
                                    @if($record_value->transaction_type == 'DEBIT')
                                        <td>{{ $record_value->amount }}</td>
                                    @else
                                        <td>0</td>
                                    @endif 
                                    @if($record_value->transaction_type == 'CREDIT')
                                        <td>{{ $record_value->amount }}</td>
                                    @else
                                        <td>0</td>
                                    @endif 
                                    <td>{{ $record_value->balance }}</td>   
                                    
                                    
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Sr No</th>
                                <!-- <th>Name</th> -->
                                <th>DATE & TIME</th>
                                <th>ORDER ID</th> 
                                <th>Type</th>
                                <th>Debit</th>
                                <th>Credit</th>
                                <th>REMAINING BALANCE AMOUNT</th>
                                    
                               
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Complaint Reports table ends -->



@if( Auth::user()->roleId == Config::get('constants.DISTRIBUTOR') || Auth::user()->roleId == Config::get('constants.MASTER_DISTRIBUTOR'))
</div>
@endif

@if( Auth::user()->roleId != Config::get('constants.DISTRIBUTOR') || Auth::user()->roleId != Config::get('constants.MASTER_DISTRIBUTOR'))
</section>
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