{{-- @extends('layouts.full')  --}}
@extends('layouts.full_new')

@section('page_content')

<!-- <section> -->
<!-- This page plugin CSS -->
<link href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css"
        href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('template_assets\other\css\bootstrap-toggle.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('dist\bank\css\bank_account.css') }}">
<style>
    th {
  text-transform: uppercase;
}
</style>
<div class="page-content container-fluid">

<!-- Bank Account table starts -->
<div class="row">
    <div class="col-12">
        <div class="material-card card">
            <div class="card-body">
                <h4 class="card-title">Bank Account</h4>
                <br>
                @if( Auth::user()->roleId == Config::get('constants.ADMIN'))
                <div class="row card-title">
                    <div class="col-12 text-right">
                        @if(isset($toatl_amt) && $toatl_amt)
                        <button type="button" title="Total Amount" class="btn btn-light info-button btn-md mr-2">Total Amount: {{ $toatl_amt }}</button>
                        @endif
                        <button type="button" class="btn btn-primary btn-md add-money-btn" data-toggle="modal" data-target="#addMoneydModal"><i class="fa fa-plus"></i> Add Money</button>
                        <button type="button" class="btn btn-primary btn-md add-bank-ac-btn" data-toggle="modal" data-target="#bankAcAddModal"><i class="fa fa-plus"></i> Add Bank Account</button>
                    </div>
                </div>
                @endif
                <br>
                @if( Auth::user()->roleId != Config::get('constants.ADMIN'))
                    <table id="va-ac-table" class="table table-striped table-sm border" style="text-align:center;">
                        <thead>
                            <h4 class="card-title">Virtual Account</h4>
                            <tr>
                                <th>SR NO</th>
                                <th>ACCOUNT HOLDER NAME</th>
                                <th>ACCOUNT NUMBER</th>
                                <th>BANK NAME</th>
                                <th>IFSC CODE</th>
                                <th>MODE</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="font-size:16px;">
                                <td>1</td>
                                <td>{{ $va['account_holder'] }}</td>
                                <td>{{ $va['account_number'] }}</td>
                                <td>{{ $va['bank_name'] }}</td>
                                <td>{{ $va['ifsc_code'] }}</td>
                                <td>IMPS, NEFT, RTGS</td>
                            </tr>
                        </tbody>
                    </table>
                @endif
                <br>
                    <!-- <table id="bank-ac-table" class="table table-striped table-sm border is-data-table"> -->
                    <table id="bank-ac-table" class="table table-striped table-bordered table-sm border is-data-table">

                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Bank Name</th>
                                <th>Account Number</th>
                                <th>IFSC Code</th>
                                @if( Auth::user()->roleId == Config::get('constants.ADMIN'))
                                <th>Balance </th>
                                @endif
                                <th>Address</th>
                                <th>Icon</th>
                                @if( Auth::user()->roleId == Config::get('constants.ADMIN'))
                                    <th class="text-center">Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bankAccounts as $index => $account)
                                <tr>
                                    <td>{{ $index+1 }}</td>
                                    <td>{{ $account->bank_name }}</td>
                                    <td>{{ $account->account_no }}</td>
                                    <td>{{ $account->ifsc_code }}</td>
                                    @if( Auth::user()->roleId == Config::get('constants.ADMIN'))
                                    <td>{{ $account->balance }}</td>
                                    @endif
                                    <td>{{ $account->address }}</td>
                                    <td>
                                        @if( $account->bank_icon)
                                        <img src="{{ $account->bank_icon }}" alt="{{ $account->bank_name }}" style="width:50px;">
                                        @endif
                                    </td>
                                    @if( Auth::user()->roleId == Config::get('constants.ADMIN'))
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button type="button" id="form-file-up-btn" class="btn btn-sm btn-warning upload-btn" data-id="{{ $account->id }}" title="Upload Logo" value="{{ $account->id }}">
                                                <i class="fa fa-upload"></i>
                                              
                                            </button>
                                            <button type="button" class="btn btn-sm btn-primary edit-btn" title="Edit" value="{{ $account->id }}">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="{{ $account->id }}" title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Sr No</th>
                                <th>Bank Name</th>
                                <th>Account Number</th>
                                <th>IFSC Code</th>
                                <th>Address</th>
                                <th>Icon</th>
                                @if( Auth::user()->roleId == Config::get('constants.ADMIN'))
                                    <th class="text-center">Action</th>
                                @endif
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
<div class="modal" id="bankAcAddModal" tabindex="-1" role="dialog" aria-labelledby="bankAcAddModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel1"><span class="modal-action-name"></span> Bank Account</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form method="post" action="{{ route('bank_account') }}" id="addBankAcForm">
            @csrf
                <div class="modal-body">
                        <input type="hidden" name="id" id="id">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="bank_name">Bank Name</label>
                                    <input type="text" class="form-control" id="bank_name" name="bank_name">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="account_no">Account No.</label>
                                    <input type="text" class="form-control" id="account_no" name="account_no">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="ifsc_code">IFSC Code</label>
                                    <input type="text" class="form-control" id="ifsc_code" name="ifsc_code">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <input type="text" class="form-control" id="address" name="address">
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


<!--  Add Money modal starts -->
<div class="modal" id="addMoneydModal" tabindex="-1" role="dialog" aria-labelledby="addMoneydModal">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel1"><span class="modal-action-name"></span> Add Money</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form method="post" action="{{ route('add_money') }}" id="addBankAcForm">
            @csrf
                <div class="modal-body">
                    <div class="form-group container mt-2">
                        <label for="bank_acc">Select Account</label>
                        <select name="bank_acc" id="bank_acc" class="form-control" required>
                            <option selected disabled value="">Select</option>
                            <option value="Wallet" id="tran-sts-success-option"> Wallet </option>
                            
                            @foreach($bank_acc as $acc_key => $acc_value)
                                <option value="{{ $acc_value['id'] }}" id="tran-sts-success-option"> {{ $acc_value['bank_name'] }} </option>
                            @endforeach
                            
                        </select>
                    </div>
                    
                    <div class="form-group  container mt-2">
                        <label for="amount">Amount.</label>
                        <input type="number" class="form-control" id="amount" name="amount" required>
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
<!--  Add Money modal ends -->


<!-- Biller table ends -->
<form id="uploadBillerForm" method="post" action="{{  route('upload_logo') }}">
@csrf
        <input type="hidden" name="acc_id" id="acc_id">
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
<script src="{{ asset('dist\bank\js\bankAcFormValidation.js') }}"></script>
<script src="{{ asset('dist\bank\js\bank_account.js') }}"></script>
@endsection
