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
        <div class="card">
            <div class="card-body">
            <form action="{{ route('upload_tds') }}" method="post">
                <h4 class="card-title">ADD TDS </h4>
                <div class="row">
                    <input type="hidden" id="users" name="users" value="{{ json_encode($users) }}">
               
                    @csrf
                    <div class="col-md-2 ">
                        <div class="form-group">
                            <label for="role_id">Select User Type</label>
                            <select class="form-control custom-select" id="role_id" name="role_id" required> 
                                <option selected="" disabled="" value="Select">Select</option>
                               
                                    <option  value="2">DISTRIBUTOR </option>
                                    <option  value="4">RETAILER </option>
                               
                            </select> 
                        </div> 
                    </div>

                    <div class="col-md-3 ">
                        <div class="form-group">
                            <label for="user_id">Select User</label>
                            <select class="form-control custom-select" id="user_id" name="user_id" required> 
                                <option selected="" disabled="" value="Select">Select</option>
                               
                            </select> 
                        </div> 
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                                            <label for="tds_period">TDS Period</label>
                                            <input type="text" class="form-control" id="tds_period" name="tds_period" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                                    <label for="tds_period">Upload PDF</label>
                                            <!-- <div class="input-group-prepend">
                                                <span class="input-group-text">PDF</span>
                                            </div> -->
                                            <div class="custom-file">
                                            <button type="button" id="form-file-up-btn" class="btn btn-primary mr-2">Select </button>
                                                <!-- <label class="custom-file-label" for="inputGroupFile01">Choose file</label> -->
                                            </div>
                               

                                            <input type="hidden" class="form-control" id="uploaded_file_id" name="uploaded_file_id">
                        </div>
                    </div>
                   <div class="col-md-2">
                                <button type="submit"  class="btn btn-success mr-2">Upload </button>
                                
                   </div>
                        
                   
                </div>
            </form>
                <div class="row card-title">
                    <div class="col-12 text-right">
                        <!-- <button type="button" title="ADD Office/Notice" class="btn btn-primary btn-md add-offersnotice-btn" data-toggle="collapse" data-target="#add-offersnotice"><i class="fa fa-plus"></i> Add Offer/Notice </button> -->
                    </div>
                </div>
                <br>
             
            </div>
        </div>
    </div>

   
</div>
<!-- Complaint Reports table ends -->

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
<script src="{{ asset('dist\tds\js\tds.js') }}"></script>

@endsection