@extends('layouts.full')

@section('page_content')

<div class="main-wrapper">
    <!-- ============================================================== -->
    <div class="error-box">
        <div class="error-body text-center">
            <label class="error-title">403</label>
            <h3 class="text-uppercase error-subtitle">FORBIDDON ERROR!</h3>
            <p class="text-muted mt-4 mb-4">YOU DON'T HAVE PERMISSION TO ACCESS THE PAGE.</p>
            <a href="{{ Auth::user()->roleId == Config::get('constants.ADMIN') ? url('/admin-home') : url('/home') }}" class="btn btn-info btn-rounded waves-effect waves-light mb-5">Back to home</a> </div>
    </div>
    <!-- ============================================================== -->
</div>

<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script> 
@endsection