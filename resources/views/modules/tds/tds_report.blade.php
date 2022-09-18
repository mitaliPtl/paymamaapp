@extends('layouts.full')

@section('page_content')
<html  xmlns="http://www.w3.org/1999/xhtml">
<!-- This page plugin CSS -->
<link href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css"
        href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('template_assets\other\css\flatpickr.min.css') }}">
<link rel="stylesheet" href="{{ asset('dist\reports\css\reports.css') }}">
<!-- <script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"> 
</script>  -->
<section>
        <!-- Complaint Reports table starts -->
<div class="row">
    <div class="col-12">
        <div class="material-card card">
            <div class="card-body">
                <h4 class="card-title"> TDS Report </h4>
                <div class="row">
                    <div class="col-12 text-right mb-2">
                        

                        @if(isset($total_amt) && $total_amt)
                        <button type="button" title="Total Retailer Commission" class="btn btn-danger info-button btn-md mr-2">  Amount: {{ $total_amt }}</button>
                        @endif
                        @if(isset($total_cashback) && $total_cashback)
                        <button type="button" title="Total Admin Commission" class="btn btn-cyan info-button btn-md mr-2"> Cashback: {{ $total_cashback }}</button>
                        @endif
                        @if(isset($total_tds) && $total_tds)
                        <button type="button" title="Total TDS" class="btn btn-light info-button btn-md mr-2"> TDS: {{ $total_tds }}</button>
                        @endif

                        <a href="{{$_SERVER['REQUEST_URI']}}">
                            <button type="button" title="Refresh" class="btn btn-outline-primary btn-circle btn-md mr-2"><i class="mdi mdi-rotate-right"></i></button>
                        </a>
                        <a id="dlink"  style="display:none;"></a>
                        <!-- <button type="button" id="btnExport" value="Export" onclick="Export()" >Export</button> -->
                        <button type="button" title="Apply Filter" class="btn btn-outline-info btn-circle btn-md mr-2" data-toggle="collapse" data-target="#filterBox"><i class="fa fa-filter"></i></button>
                        <!-- <button type="button" title="Export" onclick="Export()" class="btn btn-outline-dark btn-circle btn-md mr-3" data-toggle="collapse" data-target="#exportBox"><i class="fa fa-download"></i></button> -->
                        <button type="button" title="Export" onclick="Export()" class="btn btn-outline-dark btn-circle btn-md mr-3" ><i class="fa fa-download"></i></button>
                    </div>
                    
                    <div class="col-11">
                    <div class="collapse show" id="filterBox">
                    @if(isset($filtersList) && $filtersList)
                        <form action="{{ $_SERVER['REQUEST_URI'] }}" method="post">
                        @csrf
                            <input type="hidden" id="is_export" name="is_export" value="0">
                            @if(isset($user_id_form))
                                <input type="hidden" id="user_id_form" name="user_id_form" value="{{ $user_id_form }} ">
                            @endif
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
                                    <button class="btn btn-md btn-outline-primary" id="filter-submit-btn" type="submit"><i class="fa fa-filter"></i> Filter</button>
                                </div>
                            </div>

                        </form>
                    @endif
                    </div>
                    </div>
                    <div class="col-1 text-right">
                    <div class="collapse text-right" id="exportBox">
                        <div class="btn-group filter-elements">
                            @if(isset($tds_report) && $tds_report)
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
                    <table id="recharge-report-table" name="recharge-report-table" class="table table-striped table-sm border is-data-table">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                @foreach($reportTH as $i => $head)
                                    <th>{{ $reportTH[$i]['name'] }}</th>
                                @endforeach
                               
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tds_report as $index => $report)
                                <tr>
                                    <td>{{ $index+1 }}</td>
                                    @foreach($reportTH as $i => $head)
                                        @if($head['label'] == 'createdDtm')
                                            <td>
                                                {{isset($tds_report[$index]['createdDtm']) && $tds_report[$index]['createdDtm'] ? (date('d/m/y H:i:s',strtotime($tds_report[$index]['createdDtm']))) : '' }}
                                            </td>
                                        
                                        @elseif($head['label'] == 'action')
                                            <td class="btn-group">
                                                    <button type="button" value="{{ $tds_report[$index]['user_id'] }}" class="btn btn-sm btn-warning view-tds" title="View" href="">
                                                            <i class="fa fa-eye"></i>
                                                    </button>

                                                    <a type="button" class="btn btn-sm btn-primary" title="TDS" href="{{ route('view_tds',$tds_report[$index]['user_id']) }}">
                                                    <!-- <i class="fa fa-edit"></i> -->
                                                        TDS
                                                    </a>
                            
                                        
                                            </td>
                                        @else
                                            <td> {{ $tds_report[$index][$head['label']]}} </td>
                                        @endif
                                    @endforeach
                                    
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Sr No</th>
                                @foreach($reportTH as $i => $head)
                                    <th>{{ $reportTH[$i]['name'] }}</th>
                                @endforeach
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!--  Reports table ends -->

<form id="viewTDS" action="{{ route('tds_history_ByDate') }}" style="display: none;" method="post">
@csrf
    <input type="hidden" name="from_date_form" id="from_date_form" value="{{ date('m-01-Y') }}">
    <input type="hidden" name="to_date_form" id="to_date_form">
    <input type="hidden" name="user_id_form" id="user_id_form">
</form>


</section>

<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<!--Datable plugins -->
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('template_assets/dist/js/pages/datatable/datatable-basic.init.js') }}"></script>
<!-- Datatable plugin ends -->
<script src="{{ asset('template_assets\other\js\flatpickr') }}"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<!-- <script src="{{ asset('dist\reports\js\rechargeReport.js') }}"></script> -->
<script src="{{ asset('dist\tds\js\tds.js') }}"></script>
<script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"> 

<script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>


</script> 

@endsection