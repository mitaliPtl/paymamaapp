@extends('layouts.full')

@section('page_content')

<section>
    <div class="row">
        <div class="col-12">
            <div class="card card-body">
                <h4 class="card-title">Add API Setting</h4>

                <form method="post" action="{{ route('create_api_setting') }}" id="addApiSettingForm">
                @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="api_name">API Name</label>
                                <input type="text" class="form-control" id="api_name" name="api_name">
                            </div>

                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" id="username" name="username">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="api_dtls">Description</label>
                                <input type="text" class="form-control" id="api_dtls" name="api_dtls">
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control" id="password" name="password">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password_confirmation">Confirm Password</label>
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                                    </div>   
                                </div>
                            </div>                                                    
                        </div>

                        <div class="col-md-12">
                            <button type="submit" class="btn btn-success mr-2">Submit</button>
                            <button type="button" class="btn btn-dark">Cancel</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</section>
<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script src="{{ asset('dist/user/js/addApiSetting.js') }}"></script>
@endsection
