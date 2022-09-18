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
                    <h4 class="card-title">ICICI Bank Reports</h4>
                    <div class="row">
                        <div class="col-12 text-right mb-2">
                            <button type="button" title="Total Amount" class="btn btn-light info-button btn-md mr-2">Total Amount : {{ $balance }}</button>
                            <a href="{{$_SERVER['REQUEST_URI']}}">
                                <button type="button" title="Refresh" class="btn btn-outline-primary btn-circle btn-md mr-2"><i class="mdi mdi-rotate-right"></i></button>
                            </a>
    
                            <button type="button" title="Apply Filter" class="btn btn-outline-info btn-circle btn-md mr-2" data-toggle="collapse" data-target="#filterBox"><i class="fa fa-filter"></i></button>
                            <button type="button" title="Export" class="btn btn-outline-dark btn-circle btn-md mr-3" data-toggle="collapse" data-target="#exportBox"><i class="fa fa-download"></i></button>
                        </div>
                        <div class="col-11">
                            <div class="collapse show" id="filterBox">
                                <form action="{{ $_SERVER['REQUEST_URI'] }}" method="post">
                                    @csrf
                                    <input type="hidden" id="is_export" name="is_export" value="0">
                                    <div class="row">
                                        <div class="filter-elements">
                                            <input type="text" class="form-control" id="from_date" name="from_date" value="{{ $request->from_date ?? "" }}" placeholder="From Date">
                                        </div>
                                        <div class="filter-elements">
                                            <input type="text" class="form-control" id="to_date" name="to_date" value="{{ $request->to_date ?? "" }}" placeholder="To Date">
                                        </div>
                                        <div class="filter-elements">
                                            <button class="btn btn-md btn-outline-primary btn-lg success-grad" id="filter-submit-btn" type="submit"><i class="fa fa-filter"></i> Filter</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <div class="col-1 text-right">
                            <div class="collapse text-right" id="exportBox">
                                <div class="btn-group filter-elements">
                                    <button type="submit" id="pdf-btn" class="btn btn-sm btn-warning"><i class="mdi mdi-file-pdf"></i> PDF</button>
                                </div>
                            </div>
                        </div>
                      
                    </div>
                    <br>
                    <div class="table-responsive">
                        <table id="recharge-report-table" class="table table-striped table-sm border is-data-table">
                            <thead>
                                <tr>
                                    <th>Sr No.</th>
                                    <th>Date</th>
								    <th>Remarks</th>
								    <th>TXN ID</th>
									<th>Amount</th>
									<th>Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($all_txn as $index => $value)
                                    <tr>
                                        <td>{{ $index+1 }}</td>
                                        <td>{{ $value->TXNDATE }}</td>
                                        <td>{{ $value->REMARKS }}</td>
                                        <td>{{ $value->TRANSACTIONID }}</td>
                                        <td>{{ $value->AMOUNT }}</td>
                                        <td>{{ $value->TYPE }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- Recharge Reports table ends -->
</div>

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
