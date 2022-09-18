@extends('layouts.full_new')
@section('page_content')

<div class="page-content container-fluid">
    @if(Session::get('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>SUCCESS</strong>  {{ Session::get('success') }}
        {{  Session::forget('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @elseif(Session::get('error')) 

    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>FAILED</strong>  {{ Session::get('error') }}
        {{  Session::forget('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card card-body">
                     <style>
                        legend{
                           
                            color:#be1d2c !important;
                            font-weight:normal;
                            font-size:19px;
                        }
                    </style>
                     <h4 class="" style="text-align:center;background:#be1d2c;color:white;padding:15px;">OTP VERIFICATION</h4>
                        <br>
                        <fieldset style="border:1px solid #e1e1e1;padding:20px;">
                                <legend style="color:blue;font-weight:bold;width:13% !important;;">KYC DETAILS</legend>
                                        <form method="post" action="{{ route('validate_otp') }}">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label for="otp" style="font-size:19px;">Enter an OTP received on your mobile</label>
                                                            <input type="text" maxlength="10" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" class="form-control" name="otp" required>
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-success mr-2" style="font-size:18px;width: 150px;background-color: green;color: white;">Verify</button>
                                                <a type="button" href="javascript:;" onclick="document.getElementById('resend').submit();" class="btn btn-dark" style="font-size:18px;width: 150px;    background-image: linear-gradient(to right, #251c63 , #dc182d);color: white;">Resend</a>
                                            </div>
                                        </div>
                                    </form>
                                </legend>
                        </fieldset>
                    <form id="resend" method="post" action="{{ route('resend_otp') }}" style="display:none;">@csrf</form>
                </div>
            </div>
        </div>
    </section>

</div>
<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
@endsection
