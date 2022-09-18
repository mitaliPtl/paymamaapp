
{{-- @extends('layouts.full') --}}
@extends('layouts.full_new')
@section('page_content')

<!-- This page plugin CSS -->
<link href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css"
        href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
<!-- <link rel="stylesheet" type="text/css" href="{{ asset('template_assets\other\css\flatpickr.min.css') }}"> -->
<!-- <link rel="stylesheet" href="{{ asset('dist\reports\css\reports.css') }}"> -->
<link href="{{ asset('template_assets\other\css\select2.min.css') }}" type="text/css" rel="stylesheet">
<style>
    th {
                text-transform: uppercase;
    }
</style>
<div class="page-content container-fluid">

<section>
    
        <!-- Complaint Reports table starts -->
<div class="row">
    <div class="col-12">
        <div class="material-card card">
            <div class="card-body">
                <h4 class="card-title"> TDS List  </h4>
                <div class="row">
                    <div class="col-12 ">
                       
                        <!-- <a href="{{$_SERVER['REQUEST_URI']}}">
                            <button type="button" title="Refresh" class="btn btn-outline-primary btn-circle btn-md mr-2"><i class="mdi mdi-rotate-right"></i></button>
                        </a> -->
                        
                        <!-- <button type="button" title="ADD Template" class="btn btn-outline-info btn-md add-template-btn" data-toggle="collapse" data-target="#add-template"><i class="fa fa-pluse"></i>Add Template</button> -->
                        <!-- <button type="button" title="Export" class="btn btn-outline-dark btn-circle btn-md mr-3" data-toggle="collapse" data-target="#exportBox"><i class="fa fa-download"></i></button> -->
                    </div>
                    
                </div>
                <!-- <div class="row card-title">
                    <div class="col-12 text-right">
                        <button type="button" title="ADD Office/Notice" class="btn btn-primary btn-md add-offersnotice-btn" data-toggle="collapse" data-target="#add-offersnotice"><i class="fa fa-plus"></i> Add Offer/Notice </button>
                    </div>
                </div> -->
                <br>
                <div class="table-responsive">
                   <!-- <table id="recharge-report-table" class="table table-striped table-sm border is-data-table"> -->
                    <table id="recharge-report-table" class="table table-striped table-bordered table-sm border is-data-table ">

                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Period</th>
                                <th>Date</th>
                                <th>Action </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tds_user as $tds_user_key => $tds_user_value)
                                <tr>
                                    <td>{{ $tds_user_key+1 }}</td>
                                    <td >{{ $tds_user_value['tds_period'] }}</td>
                                    <td >{{ $tds_user_value['created_on'] }}</td>
                                    <td>
                                        <!-- <button type="button" class="btn btn-sm btn-info edit-offersnotice-btn" title="Edit" value="{{ $tds_user_key }}">
                                                    <i class="fa fa-download"></i>
                                        </button> -->
                                        <a href="{{ $tds_user_value['file_path'] }}" target="_blank" class="btn btn-sm btn-warning view-btn" data-id="{{ $tds_user_key }}" title="View" download >
                                                <i class="fa fa-download"></i>
                                            </a>

                                    </td>
                                    
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Sr No</th>
                                <th>Period</th>
                                <th>Date</th>
                                <th>Action </th>
                               
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Complaint Reports table ends -->

</section>
</div>

<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<!--Datable plugins -->
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('template_assets/dist/js/pages/datatable/datatable-basic.init.js') }}"></script>
<!-- Datatable plugin ends -->
<script src="{{ asset('template_assets\other\js\select2.min.js') }}"></script>

<!-- <script src="{{ asset('template_assets\other\js\flatpickr') }}"></script> -->
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<!-- <script src="{{ asset('dist\reports\js\rechargeReport.js') }}"></script> -->
<!-- <script src="{{ asset('dist\complaint\js\complaint.js') }}"></script> -->
<script src="{{ asset('dist\tds\js\tds.js') }}"></script>

@endsection
