{{-- @extends('layouts.full') --}}
@extends('layouts.full_new')
@section('page_content')

<!-- <section> -->
<div class="page-content container-fluid">

<!-- This page plugin CSS -->
<link href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css"
        href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('template_assets\other\css\bootstrap-toggle.min.css') }}">
<!-- Operator table starts -->
<style>
    th {
  text-transform: uppercase;
}
.table-bordered td, .table-bordered th {
    font-size: 17px;
}
</style>
<div class="row">
    <div class="col-12">
        <div class="material-card card">
            <div class="card-body">
                <h4 class="card-title">Operator Helpline</h4>
                <!-- Filter Section -->
                <div class="row">
                    <div class="col-12 text-right mb-2">
                        <a href="{{$_SERVER['REQUEST_URI']}}">
                            <button type="button" title="Refresh" class="btn btn-outline-primary btn-circle btn-md mr-2"><i class="mdi mdi-rotate-right"></i></button>
                        </a>
                        <button type="button" title="Apply Filter" class="btn btn-outline-info btn-circle btn-md mr-2" data-toggle="collapse" data-target="#filterBox"><i class="fa fa-filter"></i></button>
                    </div>

                    <!-- <div class="col-10">
                    <div class="collapse show" id="filterBox">
                        {{-- <form action="{{ $_SERVER['REQUEST_URI'] }}" method="post">
                        @csrf
                            <div class="row">

                                <div class="col-3">
                                    <select name="service_id" id="service_id" class="form-control">
                                        <option disabled selected>Select Service Type</option>
                                            @foreach($servicesTypes as $type)
                                                @if($type->service_id == $request->service_id)
                                                    <option value="{{ $type->service_id }}" selected> {{ $type->service_name }}</option>
                                                @else
                                                    <option value="{{ $type->service_id}}">{{ $type->service_name}}</option>
                                                @endif
                                            @endforeach
                                    </select>
                                </div>

                                <div class="col-2">
                                    <button class="btn btn-md btn-outline-primary" id="filter-submit-btn" type="submit"><i class="fa fa-filter"></i> Filter</button>
                                </div>
                            </div>
                        </form> --}}
                    </div>
                    </div> -->
                    <div class="col-2 text-right">
                    <div class="collapse text-right" id="exportBox">
                        <div class="btn-group">
                            @if(isset($rechargeReports) && $rechargeReports)
                                <button type="submit"  id="pdf-btn" class="btn btn-md btn-warning"><i class="mdi mdi-file-pdf"></i> PDF</button>
                            @else
                                <button type="submit"  id="pdf-btn" class="btn btn-md btn-warning" disabled><i class="mdi mdi-file-pdf"></i> PDF</button>
                            @endif
                        </div>
                    </div>
                    </div>
                </div>
                <!-- Filter Section ends -->
                <br>
                <div class="table-responsive">
                    <!-- <table id="operator-helpline-table" class="table table-striped table-sm table-sm table-bordered is-data-table"> -->
                    <table id="operator-helpline-table" class="table table-striped table-bordered table-sm border is-data-table">

                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Operator Name</th>
                                <th>Helpline No.</th>
                                <!-- <th class="text-center">Status</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($servicesTypes as $index => $operator)
                                <tr>
                                    <td>{{ $index+1 }}</td>
                                    <td>{{ $operator->operator_name }}</td>
                                    <td>{{ $operator->helpline_no }}</td>
                                    <!-- <td class="text-center">
                                        {{-- @if($operator->activated_status == Config::get('constants.ACTIVE'))
                                            <input checked id="status-btn_{{ $index+1 }}" class="status-btn" disabled type="checkbox" data-id="{{ $operator->operator_id }}" data-on="Active" data-off="Inactive" data-onstyle="success" data-toggle="toggle" data-width="90" data-style="ios" data-style="slow">                                          
                                        @else
                                            <input id="status-btn_{{ $index+1 }}" class="status-btn" type="checkbox" disabled data-id="{{ $operator->operator_id }}" data-on="Active" data-off="Inactive" data-onstyle="success" data-toggle="toggle" data-width="90" data-style="ios" data-style="slow">                                          
                                        @endif --}}
                                    </td> -->
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Sr No</th>
                                <th>Operator Name</th>
                                <th>Helpline No.</th>
                                <!-- <th class="text-center">Status</th> -->
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Operator table ends -->

<!-- Operator Add modal starts -->
<div class="modal" id="operatorAddModal" tabindex="-1" role="dialog" aria-labelledby="operatorAddModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel1"><span class="modal-action-name"></span> Operator</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form method="post" action="{{ route('operator') }}" id="addOperatorForm">
            @csrf
                <div class="modal-body">
                        <input type="hidden" id="operator_id" name="operator_id" value="0">
                        <div class="form-group">
                            <label for="operator_name">Operator Name</label>
                            <input type="text" class="form-control" id="operator_name" name="operator_name">
                        </div>

                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="operator_code">Operator Code</label>
                                    <input type="text" class="form-control" id="operator_code" name="operator_code">
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="form-group">
                                    <label for="helpline_no">Helpline No.</label>
                                    <input type="text" class="form-control" id="helpline_no" name="helpline_no">
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="form-group">
                                    <label for="service_type">Service Type</label>
                                    <select name="service_id" id="service_id" class="form-control">
                                        <option disabled selected>Select</option>
                                        @foreach($servicesTypes as $sType)
                                            <option value="{{ $sType->service_id }}"> {{ $sType->service_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary form-submit-btn">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Operator Add modal ends -->
</div>
<!-- </section> -->

<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<!--Datable plugins -->
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('template_assets/dist/js/pages/datatable/datatable-basic.init.js') }}"></script>

<!-- Datatable plugin ends -->
<script src="template_assets\other\js\bootstrap-toggle.min.js"></script>
<script src="template_assets\other\js\sweetalert.min.js"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script src="{{ asset('dist\operator\js\operatorFormValidation.js') }}"></script>
<script src="{{ asset('dist\operator\js\operator.js') }}"></script>
@endsection
