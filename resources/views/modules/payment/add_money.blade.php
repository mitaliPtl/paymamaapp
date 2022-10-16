{{-- @extends('layouts.full') --}}
@extends('layouts.full_new')

@section('page_content')

<!-- <section> -->
<div class="page-content container-fluid">
<!-- This page plugin CSS -->
<link href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css"
        href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('template_assets/other/css/bootstrap-toggle.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('template_assets/other/css/flatpickr.min.css') }}">

<link rel="stylesheet" type="text/css" href="{{ asset('dist/bank/css/balance_request.css') }}">
<style>
    th {
  text-transform: uppercase;
}
.error{
    color: red;
}
</style>
<!-- Balance Request table starts -->
                @if(Session::has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>SUCCESS</strong>  {{ Session::get('success') }}.{{  Session::forget('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @elseif(Session::has('error')) 

                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>FAILED</strong>  {{ Session::get('error') }}.
                        {{  Session::forget('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
<div class="row">
               
    <div class="col-12">
        <div class="material-card card">
            <div class="card-body">
                <h4 class="card-title">Add Money</h4>
               
                
               <form method="get" action="" id="addMoneyReqForm">
            @csrf
                <div class="modal-body">
                        <input type="hidden" name="id" id="id">
                        
                        <div class="row">
                        <div class="col-6">
                                <div class="form-group">
                                    <label for="mobile_number">Mobile Number</label>
                                    <input type="number" class="form-control" id="mobile_number" name="mobile_number" required="required">
                                </div>
                            </div>
                            
                        <div class="col-6">
                                <div class="form-group">
                                    <label for="aadhar_number">Aadhaar Number</label>
                                    <input type="number" class="form-control" id="aadhar_number" name="aadhar_number" required="required">
                                </div>
                            </div>
                            
                        </div>
                        
                        <div class="row">
                        <div class="col-6">
                                <div class="form-group">
                                    <label for="pan_number">Pan Number</label>
                                    <input type="text" class="form-control" id="pan_number" name="pan_number" required="required">
                                </div>
                            </div>
                            
                        <div class="col-6">
                                <div class="form-group">
                                    <label for="account_holder_name">Account Holder Name</label>
                                    <input type="text" class="form-control" id="account_holder_name" name="account_holder_name" required="required">
                                </div>
                            </div>
                            
                        </div>
                        
                        
                        <div class="row">
                        <div class="col-6">
                                <div class="form-group">
                                    <label for="account_number">Account Number</label>
                                    <input type="text" class="form-control" id="account_number" name="account_number" required="required">
                                </div>
                            </div>
                            
                        <div class="col-6">
                                <div class="form-group">
                                    <label for="bank_name">Bank Name</label>
                                    <input type="text" class="form-control" id="bank_name" name="bank_name" required="required">
                                </div>
                            </div>
                        </div>
                       
                        
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-lg" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-lg submit-btn success-grad" style="width: 10%;">   Add   </button>
                </div>
            </form>
                    
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Balance Request table ends -->
</div>
<!-- </section> -->



<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<!--Datable plugins -->
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('template_assets/dist/js/pages/datatable/datatable-basic.init.js') }}"></script>
<!-- Datatable plugin ends -->
<script src="{{ asset('template_assets\other\js\flatpickr.js') }}"></script>

<script src="template_assets\other\js\bootstrap-toggle.min.js"></script>
<script src="template_assets\other\js\sweetalert.min.js"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script src="{{ asset('dist/bank/js/paymentValidation.js') }}"></script>
@endsection
