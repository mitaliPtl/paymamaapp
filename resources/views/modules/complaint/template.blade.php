@extends('layouts.full')

@section('page_content')

<!-- This page plugin CSS -->
<link href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css"
        href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('template_assets\other\css\flatpickr.min.css') }}">
<link rel="stylesheet" href="{{ asset('dist\reports\css\reports.css') }}">

<section>
        <!-- Complaint Reports table starts -->
<div class="row">
    <div class="col-12">
        <div class="material-card card">
            <div class="card-body">
                <h4 class="card-title">Templates </h4>
                <div class="row">
                    <div class="col-12 ">
                       
                        <!-- <a href="{{$_SERVER['REQUEST_URI']}}">
                            <button type="button" title="Refresh" class="btn btn-outline-primary btn-circle btn-md mr-2"><i class="mdi mdi-rotate-right"></i></button>
                        </a> -->

                        <!-- <button type="button" title="ADD Template" class="btn btn-outline-info btn-md add-template-btn" data-toggle="collapse" data-target="#add-template"><i class="fa fa-pluse"></i>Add Template</button> -->
                        <!-- <button type="button" title="Export" class="btn btn-outline-dark btn-circle btn-md mr-3" data-toggle="collapse" data-target="#exportBox"><i class="fa fa-download"></i></button> -->
                    </div>
                    <div class="col-11 text-right">
                        <div class="collapse show " id="filterBox">
                        
                        
                            <form action="{{ $_SERVER['REQUEST_URI'] }}" method="post">
                            @csrf
                                <input type="hidden" id="is_export" name="is_export" value="0">
                                <div class="row">

                            
                                    <div class="filter-elements">

                                        

                                        
                                                <select name="selected_service" id="selected_service" class="form-control">
                                                    <option selected value="">Select Service</option>
                                                    @foreach($services as $service_key => $service_value)
                                                        <option value="{{ $service_value['service_id'] }}" id="tran-sts-success-option"> {{ $service_value['service_name'] }} </option>
                                                    @endforeach
                                                </select>
                                            
                    
                                    </div>
                                
                                    <div class="filter-elements">
                                        <button class="btn btn-md btn-outline-primary" id="filter-submit-btn" type="submit"><i class="fa fa-filter"></i> Filter</button>
                                    </div>
                                </div>
                            </form>
                    
                    
                        </div>
                    </div>
                    
                </div>
                <div class="row card-title">
                    <div class="col-12 text-right">
                        <button type="button" title="ADD Template" class="btn btn-primary btn-md add-template-btn" data-toggle="collapse" data-target="#add-template"><i class="fa fa-plus"></i> Add Template </button>
                    </div>
                </div>
                <br>
                <div class="table-responsive">
                    <input type="hidden" name="all_template" id ="all_template" value="{{ json_encode($templates) }}">
                    <table id="recharge-report-table" class="table table-striped table-sm border is-data-table">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Service Type</th>
                                <th>Role</th>
                                <th>Template</th>
                                <th>Timing</th>
                                <th>Action </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($templates as $index => $template)
                                <tr>
                                    <td>{{ $index+1 }}</td>
                                    <td >{{ $template['service_name'] }}</td>
                                    <td >{{ $template['role'] }}</td>
                                    <td >{{ $template['template'] }}</td>
                                    <td >{{ $template['timing'] }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info edit-templt-btn" title="Edit" value="{{ $index }}">
                                                    <i class="fa fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-info delete_templt-btn" title="Delete" value="{{ $template['template_id'] }}">
                                                    <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                    
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Sr No</th>
                                <th>Service Type</th>
                                <th>Role</th>
                                <th>Template</th>
                                <th>Timing</th>
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



<!--  RequestReply modal starts -->
<div class="modal" id="complaintReplyModal" tabindex="-1" role="dialog" aria-labelledby="complaintReplyModal">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel1"><span class="modal-action-name"></span>Your Reply</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form method="post" action="{{ route('complaint_reply') }}" id="complaint_reply">
            @csrf
                <div class="modal-body">
                    <input type="hidden" name="complaint_id" id="complaint_id">

                    <div class="row">
                        <div class="col-12">
                            <label for="admin_reply">Enter Reply</label>
                            <br>
                            <div class="form-group">
                                <textarea type="text" class="form-control" id="admin_reply" name="admin_reply" required></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-info submit-btn"><i class="fa fa-reply"></i> Send</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--  Request Reply modal ends -->

    <!-- add modal starts -->
    <div class="modal" id="add-template" role="dialog">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel1" style="margin-left:60px">Add Template</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="chgCompltStatusForm" action="{{ route('add_template') }}" method="post">
                @csrf
                    <!-- <input type="hidden" name="change_complaint_id" id="change_complaint_id"> -->
                    <div class="form-group container mt-2">
                        <label for="template_role">Select Role</label>
                        <select name="template_role" id="template_role" class="form-control" required>
                            <option selected disabled value="">Select</option>
                            @foreach($roles as $role_key => $role_value)
                                <option value="{{ $role_value['roleId'] }}" id="tran-sts-success-option"> {{ $role_value['role'] }} </option>
                            @endforeach
                            
                        </select>
                    </div>
                    <div class="form-group container mt-2">
                        <label for="template_service">Select Service</label>
                        <select name="template_service" id="template_service" class="form-control" required>
                            <option selected disabled value="">Select</option>
                            @foreach($services as $service_key => $service_value)
                                <option value="{{ $service_value['service_id'] }}" id="tran-sts-success-option"> {{ $service_value['service_name'] }} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="default_time">Enter Template</label>
                        <br>
                        <div class="form-group">
                            <textarea type="text" class="form-control" id="template_text" name="template_text" required></textarea>
                        </div>
                    </div>
                   
                    <div class="col-12">
                        <label for="default_time">Enter Time</label>
                        <br>
                        <div class="form-group">
                            <textarea type="text" class="form-control" id="default_time" name="default_time" required></textarea>
                        </div>
                    </div>
                   

                    <button type="submit" id="add-template-btn" class="btn btn-info btn-block mt-4">
                        ADD
                    </button>
                </form>
            </div>
        </div>
    </div>
    <!-- add  modal ends -->

    <!-- Edit  modal starts -->
    <div class="modal" id="edit-template" role="dialog">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel1" style="margin-left:60px">Edit Template</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="chgCompltStatusForm" action="{{ route('edit_template') }}" method="post">
                @csrf
                    <input type="hidden" name="edit_temp_id" id="edit_temp_id">
                    <div class="form-group container mt-2">
                        <label for="edit_temp_role">Select Role</label>
                        <select name="edit_temp_role" id="edit_temp_role" class="form-control" required>
                            <option selected disabled value="">Select</option>
                            @foreach($roles as $role_key => $role_value)
                                <option value="{{ $role_value['roleId'] }}" id="tran-sts-success-option"> {{ $role_value['role'] }} </option>
                            @endforeach
                            
                        </select>
                    </div>
                    <div class="form-group container mt-2">
                        <label for="edit_temp_service">Select Service</label>
                        <select name="edit_temp_service" id="edit_temp_service" class="form-control" required>
                            <option selected disabled value="">Select</option>
                            @foreach($services as $service_key => $service_value)
                                <option value="{{ $service_value['service_id'] }}" id="tran-sts-success-option"> {{ $service_value['service_name'] }} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="default_time">Enter Template</label>
                        <br>
                        <div class="form-group">
                            <textarea type="text" class="form-control" id="edit_temp_text" name="edit_temp_text" required></textarea>
                        </div>
                    </div>
                   
                    <div class="col-12">
                        <label for="default_time">Enter Time</label>
                        <br>
                        <div class="form-group">
                            <textarea type="text" class="form-control" id="edit_default_time" name="edit_default_time" required></textarea>
                        </div>
                    </div>
                   

                    <button type="submit" id="edit-template-btn" class="btn btn-info btn-block mt-4">
                        UPDATE
                    </button>
                </form>
            </div>
        </div>
    </div>
    <!-- Edit  modal ends -->

    <!--  Change Default  time modal starts -->
    <div class="modal" id="daleteTemplateModel" tabindex="-1" role="dialog" aria-labelledby="changeTimeModel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel1"><span class="modal-action-name"></span>Delete Template</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form method="post" action="{{ route('delete_template') }}" id="delete_template">
                @csrf
                    <div class="modal-body">
                        <input type="hidden" name="delete_temp_id" id="change_time_complaint_id">

                        <div class="row">
                            <div class="col-12">
                                <label for="admin_reply">Are You Sure???</label>
                                <br>
                                <!-- <div class="form-group">
                                    <textarea type="text" class="form-control" id="change_time" name="change_time" required></textarea>
                                </div> -->
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-info submit-btn">Sure </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--  Change Default  time modal ends -->
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
<script src="{{ asset('dist\complaint\js\complaint.js') }}"></script>

@endsection