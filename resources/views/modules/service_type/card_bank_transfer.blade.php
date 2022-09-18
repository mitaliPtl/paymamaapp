@extends('layouts.full_new')
@section('page_content')

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
                        <h3 class="card-title">CARD TO BANK TRANFER</h3>
                        <hr style="border:1px solid red" />
                        <div class="col-12">
                            <form method="post" action="{{ route('verify_card_user') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-6">
                                               <div class="form-group">
                                                    <label for="mobileNumber">Customer Mobile Number</label>
                                                    <input type="number" maxlength="10" class="form-control" name="mobileNumber" required="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                               <div class="form-group">
                                                    <label for="mobileNumber">Customer Pan Number</label>
                                                    <input type="text" maxlength="10" class="form-control" name="panNumber" required="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                        </div>
                                    </div>
                                    <div class="col-md-12" style="margin-left:-2px;">
                                        <button type="submit" class="btn primary btn-lg" style="width:150px;background-color:green;color:white;">Submit</button>
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
@endsection