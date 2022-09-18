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
                    <h4 class="card-title">Mobile Verification</h4>
                        <form method="post" action="{{ route('validate_card_otp') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="otp">Enter an OTP received on your mobile</label>
                                            <input type="text" maxlength="5" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" class="form-control" name="otp" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn primary btn-lg" style="width:150px;background-color:green;color:white;">Verify</button>
                                        <a type="button" onclick="document.getElementById('resend').submit();" class="btn success-grad btn-lg" style="width:150px;color:white;">Resend</a>
                                    </div>
                                </div>
                        </div>
                    </form>
                    <form id="resend" method="post" action="{{ route('resend_card_otp') }}" style="display:none;">@csrf</form>
                </div>
            </div>
        </div>
    </section>

</div>
<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
@endsection
