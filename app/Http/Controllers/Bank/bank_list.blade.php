@extends('layouts.full')

@section('page_content')

<section>
<!-- This page plugin CSS -->
<link href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css"
        href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('template_assets\other\css\bootstrap-toggle.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('dist\bank\css\bank_account.css') }}">

<!-- Bank Account table starts -->
<div class="row">
    <div class="col-12">
        <div class="material-card card">
            <div class="card-body">
                <h4 class="card-title">Bank List</h4>
                <br>
                
                <div class="row card-title">
                    <div class="col-12 text-right">
                              
                       
                        <button type="button" class="btn btn-primary btn-md add-bank-btn" data-toggle="modal" data-target="#bankAddModal"><i class="fa fa-plus"></i> Add Bank Account</button>
                    </div>
                </div>
               
                <br>
                    <table id="bank-ac-table" class="table table-striped table-sm border is-data-table">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Bank Icon</th>
                                <th>Bank Name</th>
                                <th>Shortcode </th>
                                <th>IFSC Prefix</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($all_banks as $index => $value)
                                <tr>
                                    <td>{{ $index+1 }}</td>
                                    <td>
                                        @if( $value->bank_icon)
                                        <img src="{{ $value->bank_icon }}" alt="{{ $value->BANK_NAME }}" style="width:50px;">
                                        @endif
                                    </td>
                                    <td>{{ $value->BANK_NAME }}</td>
                                    <td>{{ $value->ShortCode }}</td>
                                    <td>{{ $value->ifsc_prefix }}</td>
                                    
                                   
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button type="button" id="form-file-up-btn" class="btn btn-sm btn-warning upload-bnk-btn" data-id="{{ $value->BankID }}" title="Upload Logo" value="{{ $value->BankID }}">
                                                <i class="fa fa-upload"></i>
                                              
                                            </button>
                                            <button type="button" class="btn btn-sm btn-primary edit-bnk-btn" title="Edit" value="{{ $value->BankID }}">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <!-- <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="{{-- $value->BankID --}}" title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </button> -->
                                        </div>
                                    </td>
                                    
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                            <th>Sr No</th>
                                <th>Bank Icon</th>
                                <th>Bank Name</th>
                                <th>Shortcode </th>
                                <th>IFSC Prefix</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Bank Account table ends -->

<!-- Bank Account Add modal starts -->
<div class="modal" id="bankAddModal" tabindex="-1" role="dialog" aria-labelledby="bankAddModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel1"><span class="modal-action-name"></span> Bank Account</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form method="post" action="{{ route('add_bank') }}" id="addBankForm">
            @csrf
                <div class="modal-body">
                        <input type="hidden" name="bank_id" id="bank_id">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="bank_name">Bank Name</label>
                                    <input type="text" class="form-control" id="bank_name" name="bank_name">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="shortcode">ShortCode</label>
                                    <input type="text" class="form-control" id="shortcode" name="shortcode">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="ifsc_prefix">IFSC Prefix</label>
                                    <input type="text" class="form-control" id="ifsc_prefix" name="ifsc_prefix ">
                                </div>
                            </div>
                            
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary submit-btn">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Bank Account Add modal ends -->




<!-- Biller table ends -->
<form id="uploadBillerForm" method="post" action="{{  route('bank_logo') }}">
@csrf
        <input type="hidden" name="bank_id_logo" id="bank_id_logo">
        <input type="hidden" name="logo_id" id="logo_id">
</form>

  <!-- File Upload modal starts -->
  <div class="modal" id="fileUploadModal" role="dialog">
            <div class="modal-dialog modal-sm" role="document" style="margin-left:525px">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLabel1"><span class="modal-action-name"></span>Upload Logo</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <form id="fileUploadForm" enctype="multipart/form-data">
                    @csrf
                        <div class="custom-file">
                            <input type="file" name="file" class="custom-file-input" id="chooseFile" required>
                            <label class="custom-file-label" for="chooseFile">Select file</label>
                        </div>
                        <input type="hidden" name="selected_acc" id="selected_acc">
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
<script src="template_assets\other\js\bootstrap-toggle.min.js"></script>
<script src="template_assets\other\js\sweetalert.min.js"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script src="{{ asset('dist\bank\js\bankAcFormValidation.js') }}"></script>
<script src="{{ asset('dist\bank\js\bank_account.js') }}"></script>
@endsection
