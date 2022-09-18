@extends('layouts.full')

@section('page_content')

<section>
<!-- This page plugin CSS -->
<link href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css"
        href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('template_assets\other\css\bootstrap-toggle.min.css') }}">
<!-- Operator table starts -->
<div class="row">
    <div class="col-12">
        <div class="material-card card">
            <div class="card-body">
                <h4 class="card-title">Operator</h4>
                <br>
                <div class="row card-title">
                    <div class="col-12 text-right">
                        <button type="button" class="btn btn-primary btn-md add-service-btn" data-toggle="modal" data-target="#operatorAddModal"><i class="fa fa-plus"></i> Add Operator</button>
                    </div>
                </div>
                <br>
                <div class="table-responsive">
                    <table id="operator-table" class="table table-striped table-sm table-sm table-bordered is-data-table">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Operator Name</th>
                                <th>Operator Code</th>
                                <th>Helpline No.</th>
                                <th>Service Type</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($operators as $index => $operator)
                                <tr>
                                    <td>{{ $index+1 }}</td>
                                    <td>{{ $operator->operator_name }}</td>
                                    <td>{{ $operator->operator_code }}</td>
                                    <td>{{ $operator->helpline_no }}</td>
                                    <td>
                                        @foreach($servicesTypes as $sType)
                                            @if($operator->service_id == $sType->service_id)
                                                {{ $sType->service_name }}
                                            @endif
                                        @endforeach 
                                    </td>
                                    <td class="text-center">
                                        @if($operator->activated_status == Config::get('constants.ACTIVE'))
                                            <input checked id="status-btn_{{ $index+1 }}" class="status-btn" type="checkbox" data-id="{{ $operator->operator_id }}" data-on="Active" data-off="Inactive" data-onstyle="success" data-toggle="toggle" data-width="90" data-style="ios" data-style="slow">                                          
                                        @else
                                            <input id="status-btn_{{ $index+1 }}" class="status-btn" type="checkbox" data-id="{{ $operator->operator_id }}" data-on="Active" data-off="Inactive" data-onstyle="success" data-toggle="toggle" data-width="90" data-style="ios" data-style="slow">                                          
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-primary edit-btn" title="Edit" value="{{ $operator->operator_id }}">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            
                                            <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="{{ $operator->operator_id }}" title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Sr No</th>
                                <th>Operator Name</th>
                                <th>Operator Code</th>
                                <th>Helpline No.</th>
                                <th>Service Type</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
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

                            <div class="col-4">
                                <div class="form-group">
                                    <label for="color_code">Color Code</label>
                                    <input type="text" class="form-control" id="color_code" name="color_code">
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

</section>

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
