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
                <h4 class="card-title">Balance Request</h4>
               
                
               <form method="post" action="{{ route('send_balance_request') }}" id="addBalanceReqForm">
            @csrf
                <div class="modal-body">
                        <input type="hidden" name="id" id="id">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="bank">Transfer Bank</label>
                                    <select class="form-control" id="bank_name" name="bank_name" required="required"> 
                                        <option value="">Select Bank</option>
                                        @foreach($bankAccounts as $i => $account)
                                                <option value="{{ $account['bank_name'] }}">{{ $account['bank_name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="bank">Account Number</label>
                                    <select class="form-control" id="account_number" name="account_number" required="required" onchange="getMode(this.value)"> 
                                        <option disabled selected>Select Account</option>
                                    </select>
                                </div>
                            </div>
                         </div>
                        
                        <div class="row">
                        <div class="col-6">
                                <div class="form-group">
                                    <label for="transefer_mode">Transfer Mode</label>
                                    <select class="form-control" id="transfer_mode" name="transfer_mode" required="required"> 
                                        <option disabled selected>Transfer Mode</option>
                                    </select>
                                </div>
                            </div>
                            
                        <div class="col-6">
                                <div class="form-group">
                                    <label for="amount">Amount</label>
                                    <input type="number" class="form-control" id="amount" name="amount" required="required">
                                </div>
                            </div>
                            
                        </div>
                        
                        <div class="row">
                        <div class="col-6">
                                <div class="form-group">
                                    <label for="amount">Date</label>
                                    <input type="date" class="form-control" id="date_deposited" name="date_deposited" required="required">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="account_holder_name">Depositer's Name</label>
                                    <input type="text" class="form-control" id="account_holder_name" name="account_holder_name" required="required">
                                </div>
                            </div>
                            
                        </div>
                        
                        
                        
                        <div class="row">
                        <div class="col-6">
                                <div class="form-group">
                                    <label for="account_holder_bank_name">Depositer's Bank Name</label>
                                    <input type="text" class="form-control" id="account_holder_bank_name" name="account_holder_bank_name" required="required">
                                </div>
                            </div>
                        <div class="col-6">
                                <div class="form-group">
                                    <label for="reference_id">UTR Number</label>
                                    <input type="text" class="form-control" id="reference_id" name="reference_id" required="required">
                                </div>
                            </div>
                            
                            
                        </div>
                        <div class="row">
                        
                        <div class="col-12">
                                <div class="form-group">
                                    <label for="message">Remark</label>
                                    <input type="text" class="form-control" id="message" name="message" required="required">
                                </div>
                            </div>
                          
                         
                        </div>
                        
                        <div class="row">
                            <div class="col-6" id="receiptFile-div">
                                <div class="form-group">
                                    <button type="button" id="form-file-up-btn" class="btn btn-warning btn-md" style="width:100%"><i class="mdi mdi-upload"></i> Upload Receipt</button>
                                    <input type="hidden" class="form-control" id="uploaded_file_id" name="receipt_file" required="required">
                                </div>
                            </div>

                          
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-lg" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-lg submit-btn success-grad">Add</button>
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
<script>
 
$(document).ready(function() {
 
    $('#bank_name').on('change', function() {
            var bank_name = this.value;
            //$("#account_number").empty();
             $("#account_number").html('<option value="">Select Account Number</option>');
             $("#transfer_mode").html('<option value="">Select Mode</option>');
            $.ajax({
                url:"{{url('get_bank_account_numbers')}}",
                type: "POST",
                data: {
                    bank_name: bank_name,
                    _token: '{{csrf_token()}}'
                },
                dataType : 'json',
                success: function(result){
                    $.each(result.bankAccounts,function(key, value){
                    $("#account_number").append('<option value="'+value.account_no+'">'+value.account_no+'</option>');
                    });
                }
            });
         
         
    });  
    
    
    $('#account_number').on('change', function() {
            var account_number = this.value;
            //$("#account_number").empty();
            $("#transfer_mode").html('<option value="">Select Mode</option>');
            $.ajax({
                url:"{{url('get_bank_account_mode')}}",
                type: "POST",
                data: {
                    account_number: account_number,
                    _token: '{{csrf_token()}}'
                },
                dataType : 'json',
                success: function(result){
                    $.each(result.mode,function(key, value){
                    $("#transfer_mode").append('<option value="'+value+'">'+value+'</option>');
                    });
                }
            });
           
    });  
    
   
 
   
});
</script>
@endsection
