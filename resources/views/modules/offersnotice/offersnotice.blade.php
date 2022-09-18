
@extends('layouts.full')

@section('page_content')

<!-- This page plugin CSS -->
<link href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css"
        href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
<!-- <link rel="stylesheet" type="text/css" href="{{ asset('template_assets\other\css\flatpickr.min.css') }}"> -->
<!-- <link rel="stylesheet" href="{{ asset('dist\reports\css\reports.css') }}"> -->
<link href="{{ asset('template_assets\other\css\select2.min.css') }}" type="text/css" rel="stylesheet">

<section>
        <!-- Complaint Reports table starts -->
<div class="row">
    <div class="col-12">
        <div class="material-card card">
            <div class="card-body">
                <h4 class="card-title">Offers & Notices </h4>
                <div class="row">
                    <div class="col-12 ">
                       
                        <!-- <a href="{{$_SERVER['REQUEST_URI']}}">
                            <button type="button" title="Refresh" class="btn btn-outline-primary btn-circle btn-md mr-2"><i class="mdi mdi-rotate-right"></i></button>
                        </a> -->
                        
                        <!-- <button type="button" title="ADD Template" class="btn btn-outline-info btn-md add-template-btn" data-toggle="collapse" data-target="#add-template"><i class="fa fa-pluse"></i>Add Template</button> -->
                        <!-- <button type="button" title="Export" class="btn btn-outline-dark btn-circle btn-md mr-3" data-toggle="collapse" data-target="#exportBox"><i class="fa fa-download"></i></button> -->
                    </div>
                    
                </div>
                <div class="row card-title">
                    <div class="col-12 text-right">
                        <button type="button" title="ADD Office/Notice" class="btn btn-primary btn-md add-offersnotice-btn" data-toggle="collapse" data-target="#add-offersnotice"><i class="fa fa-plus"></i> Add Offer/Notice </button>
                    </div>
                </div>
                <br>
                <div class="table-responsive">
                    <input type="hidden" name="all_offersnotice" id ="all_offersnotice" value="{{ json_encode($all_offers_notice) }}">
                    <input type="hidden" name="offers" id ="offers" value="{{ json_encode($offers) }}">
                    <table id="recharge-report-table" class="table table-striped table-sm border is-data-table">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                @foreach($offernoticeListTH as $indexTh => $valueTh)
                                    @if($valueTh['name'] != 'ID')
                                    <th> {{ $valueTh['name'] }}</th>
                                    @endif
                                @endforeach
                                <th>Action </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($all_offers_notice as $index => $value)
                                <tr>
                                    <td>{{ $index+1 }}</td>
                                    <td >{{ $value['notice_title'] }}</td>
                                    <td >{{ $value['notice_description'] }}</td>
                                    <td >{{ $value['notice_type'] }}</td>
                                    <td >
                                        
                                        @foreach($value['notice_visible'] as $role_key => $role_value)
                                            @foreach($all_roles as $r_key => $r_value)
                                                @if( $value['notice_visible'][$role_key] == $r_value['roleId'] )
                                                     {{ $r_value['role'] }}<br>
                                                @endif
                                            @endforeach
                                        @endforeach
                                    </td>
                                    <td >{{ $value['created_on'] }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info edit-offersnotice-btn" title="Edit" value="{{ $index }}">
                                                    <i class="fa fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-info delete-offersnotice-btn" id="delete-offersnotice-btn" title="Delete" value="{{ $index }}">
                                                    <i class="fa fa-trash"></i>
                                        </button>
                                        @if(!empty($offers[$index]['file_path']))
                                        <a href="{{ $offers[$index]['file_path'] }}" target="_blank" class="btn btn-sm btn-warning view-btn" data-id="{{ $index }}" title="View">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        @endif
                                    </td>
                                    
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Sr No</th>
                                @foreach($offernoticeListTH as $indexTh => $valueTh)
                                    @if($valueTh['name']  != 'ID')
                                    <th> {{ $valueTh['name']  }}</th>
                                    @endif
                                @endforeach
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


    <!-- add modal starts -->
    <div class="modal" id="add-offersnotice" role="dialog">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel1" style="margin-left:60px">Add </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="addoffersnotice_Form" action="{{ route('add_offersnotice') }}" method="post" enctype="multipart/form-data">
                @csrf
                    <!-- <input type="hidden" name="change_complaint_id" id="change_complaint_id"> -->
                    <div class="form-group container mt-2">
                        <label for="offersnotice_role">Visible To</label>
                        <!-- <select name="offersnotice_role" id="offersnotice_role" class="form-control" required>
                            <option selected disabled value="">Select Role</option>
                            @foreach($all_roles as $role_key => $role_value)
                                <option value="{{ $role_value['roleId'] }}" id="tran-sts-success-option"> {{ $role_value['role'] }} </option>
                            @endforeach -->

                            <select required name="offersnotice_role[]" id="offersnotice_role" class="form-control" multiple="multiple" style="width: 100%">
                            @foreach($all_roles as $role_key => $role_value)
                                <option value="{{ $role_value['roleId'] }}"> {{ $role_value['role'] }}</option>
                             @endforeach
                             </select>
                            
                        </select>
                    </div>

                    <div class="form-group container mt-2">
                        <label for="offersnotice_type">Type</label>
                        <select name="offersnotice_type" id="offersnotice_type" class="form-control" required>
                            <option selected disabled value="">Select Type</option>
                            @foreach($all_type as $type_key => $type_value)
                                <option value="{{ $type_value }}" id="tran-sts-success-option"> {{ $type_key }} </option>
                            @endforeach
                            
                        </select>
                    </div>
                    
                    <div class="col-12">
                        <label for="title">Title</label>
                        <br>
                        <div class="form-group">
                            <textarea type="text" class="form-control" id="title" name="title" required></textarea>
                        </div>
                    </div>
                   
                    <div class="col-12">
                        <label for="default_time">Description</label>
                        <br>
                        <div class="form-group">
                            <textarea type="text" class="form-control" id="description" name="description" ></textarea>
                        </div>
                    </div>
                    <div class="col-12" id="uploade-div">
                                <div class="form-group">
                                    <button type="button" id="form-file-up-btn" class="btn btn-warning btn-md" style="width:100%"><i class="mdi mdi-upload"></i> Upload Image</button>
                                    <input type="hidden" class="form-control" id="uploaded_file_id" name="uploaded_file_id">
                                </div>
                    </div>

                    <button type="submit" id="add-offersnotice-btn" class="btn btn-info btn-block mt-4">
                        ADD
                    </button>
                </form>
            </div>
        </div>
    </div>
    <!-- add  modal ends -->

    <!-- add modal starts -->
    <div class="modal" id="edit-offersnotice" role="dialog">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel1" style="margin-left:60px">Edit </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <input type="hidden" name="all_roles" id="all_roles" value="{{ json_encode($all_roles) }}">

                <form id="editoffersnotice_Form" action="{{ route('edit_offersnotice') }}" method="post" enctype="multipart/form-data">
                @csrf
                    <input type="hidden" name="edit_offersnotice_id" id="edit_offersnotice_id">
                    <div class="form-group container mt-2">
                        <label for="edit_offersnotice_role">Visible To</label>
                            
                            <!-- <select name="edit_offersnotice_role" id="edit_offersnotice_role" class="form-control" required>
                            <option selected disabled value="">Select Role</option>
                            @foreach($all_roles as $role_key => $role_value)
                                <option value="{{ $role_value['roleId'] }}" id="tran-sts-success-option"> {{ $role_value['role'] }} </option>
                            @endforeach -->

                            <select required name="edit_offersnotice_role[]" id="edit_offersnotice_role" class="form-control" multiple="multiple" style="width: 100%">
                            @foreach($all_roles as $role_key => $role_value)
                                <option value="{{ $role_value['roleId'] }}"> {{ $role_value['role'] }}</option>
                             @endforeach
                             </select>

                        </select>
                    </div>

                    <div class="form-group container mt-2">
                        <label for="edit_offersnotice_type">Type</label>
                        <select name="edit_offersnotice_type" id="edit_offersnotice_type" class="form-control" required>
                            <option selected disabled value="">Select Type</option>
                            @foreach($all_type as $type_key => $type_value)
                                <option value="{{ $type_value }}" id="tran-sts-success-option"> {{ $type_key }} </option>
                            @endforeach
                            
                        </select>
                    </div>
                    
                    <div class="col-12">
                        <label for="edit_title">Title</label>
                        <br>
                        <div class="form-group">
                            <textarea type="text" class="form-control" id="edit_title" name="edit_title" required></textarea>
                        </div>
                    </div>
                   
                    <div class="col-12">
                        <label for="default_time">Description</label>
                        <br>
                        <div class="form-group">
                            <textarea type="text" class="form-control" id="edit_description" name="edit_description" ></textarea>
                        </div>
                    </div>
                        <div class="col-12" id="uploade-div">
                                <div class="form-group">
                                    <button type="button" id="edit-form-file-up-btn" class="btn btn-warning btn-md" style="width:100%"><i class="mdi mdi-upload"></i> Upload Image</button>
                                    <input type="hidden" class="form-control" id="edit-uploaded_file_id" name="edit-uploaded_file_id">
                                </div>
                            </div>

                    <button type="submit" id="edit-offersnotice-btn" class="btn btn-info btn-block mt-4">
                        UPDATE
                    </button>
                </form>
            </div>
        </div>
    </div>
    <!-- add  modal ends -->

     <!--  Change Delete   starts -->
     <div class="modal" id="daleteoffersnoticeModel" tabindex="-1" role="dialog" aria-labelledby="changeTimeModel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel1"><span class="modal-action-name"></span>Delete Template</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form method="post" action="{{ route('delete_offersnotice') }}" id="delete_offersnotice_form">
                @csrf
                    <div class="modal-body">
                        <input type="hidden" name="delete_offersnotice_id" id="delete_offersnotice_id">

                        <div class="row">
                            <div class="col-12">
                                <label for="">Are You Sure???</label>
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
    <!--  Change Delete  ends -->

     <!--  Change view   starts -->
     <div class="modal" id="viewoffersnoticeModel" tabindex="-1" role="dialog" aria-labelledby="changeTimeModel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel1"><span class="modal-action-name"></span> Image</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form method="post" action="{{ route('view_offersnotice') }}" id="view_offersnotice_form">
                @csrf
                    <div class="modal-body">
                        <input type="hidden" name="view_offersnotice_id" id="view_offersnotice_id">

                        <div class="row">
                            <div class="col-12">
                                <img src="" class="img-fluid" id="image_id">
                            </div>
                            
                           

                        </div>
                    </div>
                    <div class="modal-footer">
                        <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                        <button type="submit" class="btn btn-info btn-block submit-btn">Update </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--  Change view  ends -->


    <!-- File Upload modal starts -->
    <div class="modal" id="fileUploadModal" role="dialog">
            <div class="modal-dialog modal-sm" role="document" style="margin-left:525px">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLabel1"><span class="modal-action-name"></span>Upload File</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <form id="fileUploadForm" enctype="multipart/form-data">
                    @csrf
                        <div class="custom-file">
                            <input type="file" name="file" class="custom-file-input" id="chooseFile" required>
                            <label class="custom-file-label" for="chooseFile">Select file</label>
                        </div>

                        <button type="submit" id="file-upload-btn" class="btn btn-info btn-block mt-4">
                            Upload File
                        </button>
                    </form>
                </div>
            </div>
    </div>
    <!-- File uplaod modal ends -->

    <!-- File Upload modal starts -->
    <div class="modal" id="edit-fileUploadModal" role="dialog">
            <div class="modal-dialog modal-sm" role="document" style="margin-left:525px">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLabel1"><span class="modal-action-name"></span>Upload File</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <form id="edit-fileUploadForm" enctype="multipart/form-data">
                    @csrf
                        <div class="custom-file">
                            <input type="file" name="file" class="custom-file-input" id="chooseFile" required>
                            <label class="custom-file-label" for="chooseFile">Select file</label>
                        </div>

                        <button type="submit" id="file-upload-btn" class="btn btn-info btn-block mt-4">
                            Upload File
                        </button>
                    </form>
                </div>
            </div>
    </div>
    <!-- File uplaod modal ends -->

</section>

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
<script src="{{ asset('dist\offersnotice\js\offersnotice.js') }}"></script>

@endsection