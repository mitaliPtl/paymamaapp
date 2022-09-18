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
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card card-body">
                    <h4 class="card-title">OTP Verification</h4>
                        <form method="post" action="validate_iciciotp">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="otp">Enter an OTP received on your mobile</label>
                                        <input type="hidden" name="lat" value="{{$lat}}">
                                            <input type="hidden" name="long" value="{{$long}}">
                                            <input type="hidden" name="accountNumber" value="{{$accountNumber}}">
                                            <input type="hidden" name="transaction_id" value="{{$transaction_id}}">
                                            <input type="hidden" name="mobile" value="{{$mobile}}">
                                            <input type="hidden" name="fingpayTransactionId" value="{{$ftransid}}">
                                            <input type="hidden" name="cdPkId" value="{{$cdPkId}}">
                                            <input type="text" maxlength="10" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" class="form-control" name="otp" required>
                                        </div>
                                    </div>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success mr-2">Verify</button>
                                <a type="button" href="javascript:;" onclick="document.getElementById('resend').submit();" class="btn btn-dark">Resend</a>
                            </div>
                        </div>
                    </form>
                    <form id="resend" method="post" action="{{ route('resend_otp') }}" style="display:none;">@csrf</form>
                </div>
            </div>
        </div>
    </section>

</div>
<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
@endsection
