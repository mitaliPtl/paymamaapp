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
				
				@php
					$success=Session::get('success');
					$error=Session::get('error');
					
					if($success != ''){
						echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>SUCCESS</strong>  '.$success.'
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
						
					}
					
					if($error != ''){
						echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        '.$error.'
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
					}
				@endphp
				
               
            
			@php
              Session::forget('error');
              Session::forget('success');
            @endphp
            
<div class="row">
               
    <div class="col-12">
        <div class="material-card card">
            <div class="card-body">
                <h4 class="card-title">Wallet 2 Wallet Transfer</h4>
               
               <form method="post" action="{{ route('pg-wallet-wallet-store') }}" id="addBalanceReqForm">
            @csrf
                <div class="modal-body">
                        <input type="hidden" name="id" id="id">
                      
                        
                        <div class="row">
                            
                        <div class="col-6">
                                <div class="form-group">
                                    <label for="amount">Amount</label>
                                    <input type="number" class="form-control" id="amount" name="amount" required="required">
                                </div>
                            </div>
                            
                         <div class="col-6">
                                <div class="form-group">
                                    <label for="amount">MPIN</label>
                                    <input type="number" class="form-control" id="mpin" name="mpin" required="required">
                                </div>
                            </div>    
                        </div>
                        
                        <div class="row">
                         <div class="col-12">
                                <div class="form-group">
                                    <label for="amount">Remarks</label>
                                    <input type="text" class="form-control" id="remarks" name="remarks" required="required">
                                </div>
                            </div>    
                        </div>
                </div>
                
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-lg submit-btn success-grad">Transfer</button>
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
<script src="{{ asset('dist\bank\js\balanceReqValidation.js') }}"></script>
<script src="{{ asset('dist\bank\js\balance_request.js') }}"></script>
@endsection
