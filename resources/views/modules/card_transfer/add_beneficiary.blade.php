@extends('layouts.full_new')
@section('page_content')
<link rel="stylesheet" type="text/css" href="{{ asset('template_new/assets/libs/select2/dist/css/select2.min.css') }}">
<div class="page-content container-fluid">
    @if(Session::has('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>SUCCESS</strong>  {{ Session::get('success') }}
        {{  Session::forget('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @elseif(Session::has('error')) 

    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>FAILED</strong>  {{ Session::get('error') }}
        {{  Session::forget('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
    
   <div id="loader"></div>
        <div class="row" style="margin-left:20px;">
            <div class="col-7">
                <div class="row">
                    <div class="card card-body">
                        <h3 class="card-title">ADD BENEFICIARY</h3>
                        <hr style="border:1px solid red" />
                        <div class="col-12">
                            <form method="post" action="{{ route('add_cc_beneficiary') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-6">
                                               <div class="form-group">
                                                    <label for="accNumber">Account Number</label>
                                                    <input type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" class="form-control" name="accNumber" id="accNumber" required="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                               <div class="form-group">
                                                    <label for="accIfsc">IFSC Code</label>
                                                    <input type="text" maxlength="12" class="form-control" name="accIfsc" id="accIfsc" required="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                               <div class="form-group">
                                                    <label for="bank">Bank</label>
                                                    <select class="select2 form-control custom-select select2-hidden-accessible" style="width: 100%; height:50px;font-size:16px;" name="bank_code" onchange="selected_Bank(this);" id="select_bank_code" data-select2-id="select_bank_code" >
                                                        <option disabled selected>Select Bank</option>    
                                                        @foreach($banks['result']['bank_list'] as $i =>$bank)
                                                            @if(isset($request->bank_code) && ($request->bank_code == $bank['bank_code']) )
                                                                <option value="{{ $bank['bank_code'] }}"  selected>{{ $bank['bank_name'] }} </option>
                                                            @else
                                                            <option value="{{ $bank['bank_code'] }}"  >{{ $bank['bank_name'] }} </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <input type="hidden" name="mobile_no" value="{{ $request->mobile_no }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-12" style="margin-left:-2px;">
                                        <button type="submit" class="btn primary btn-lg" style="width:150px;background-color:green;color:white;">Verify</button>
                                    </div>
                                </div>
                            </form>
                            <style>
                                label{font-size:20px;}
                            </style>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </section>

</div>
<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>

<script src="{{ asset('template_new/assets/libs/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ asset('template_new/assets/libs/select2/dist/js/select2.min.js') }}"></script>
<script src="{{ asset('template_new/dist/js/pages/forms/select2/select2.init.js') }}"></script>
@endsection