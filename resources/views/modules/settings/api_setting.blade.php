@extends('layouts.full')

@section('page_content')

<section>
<!-- This page plugin CSS -->
<link href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css"
        href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('dist\setting\css\api_setting_list.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('template_assets\other\css\bootstrap-toggle.min.css') }}">

<!-- API Setting table starts -->
<div class="row">
    <div class="col-12">
        <div class="material-card card">
            <div class="card-body">
                <h4 class="card-title">API Setting</h4>
                <br>
                <div class="row card-title">
                    <div class="col-12 text-right">
                        <button type="button" class="btn btn-primary btn-md add-service-btn" data-toggle="modal" data-target="#apiSettingAddModal"><i class="fa fa-plus"></i> Add API Setting</button>
                    </div>
                </div>
                <br>
                <div class="table-responsive">
                    <table id="api-setting-table" class="table table-striped table-sm border is-data-table">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>API Name</th>
                                <th>API URL</th>
                                <th>API Details</th>
                                <th>Username</th>
                                <th>Balance</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($apiSettings as $index => $setting)
                                <tr>
                                    <td>{{ $index+1 }}</td>
                                    <td>{{ $setting->api_name }}</td>
                                    <td>{{ $setting->api_url }}</td>
                                    <td>{{ $setting->api_dtls }}</td>
                                    <td>{{ $setting->username }}</td>
                                    <td>{{ $setting->balance }}</td>
                                    <td class="text-center">
                                        @if($setting->activated_status == Config::get('constants.ACTIVE'))
                                            <input checked id="status-btn_{{ $index+1 }}" class="status-btn" type="checkbox" data-id="{{ $setting->api_id }}" data-on="Active" data-off="Inactive" data-onstyle="success" data-toggle="toggle" data-width="90" data-style="ios" data-style="slow">                                          
                                        @else
                                            <input id="status-btn_{{ $index+1 }}" class="status-btn" type="checkbox" data-id="{{ $setting->api_id }}" data-on="Active" data-off="Inactive" data-onstyle="success" data-toggle="toggle" data-width="90" data-style="ios" data-style="slow">                                          
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-primary edit-btn" title="Edit" value="{{ $setting->api_id }}">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-info ch-pwd_btn" title="Change Password" value="{{ $setting->api_id }}">
                                                <i class="fa fa-key"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="{{ $setting->api_id }}" title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-secondary" title="Setting">
                                                <i class="fa fa-cog"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Sr No</th>
                                <th>API Name</th>
                                <th>API URL</th>
                                <th>API Details</th>
                                <th>Username</th>
                                <th>Balance</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- API Setting table ends -->

<!-- API Setting Add modal starts -->
<div class="modal" id="apiSettingAddModal" tabindex="-1" role="dialog" aria-labelledby="serviceTypeAddModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel1"><span class="modal-action-name"></span> API Setting</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form method="post" action="{{ route('api_setting') }}" id="addApiSettingForm">
            @csrf
                <div class="modal-body">
                        <input type="hidden" name="api_id" id="api_id" value="0">
                        <div class="form-group">
                            <label for="api_name">API Name</label>
                            <input type="text" class="form-control" id="api_name" name="api_name">
                        </div>

                        <div class="form-group">
                            <label for="api_url">API URL</label>
                            <input type="text" class="form-control" id="api_url" name="api_url">
                        </div>

                        <div class="form-group">
                            <label for="api_dtls">Description</label>
                            <input type="text" class="form-control" id="api_dtls" name="api_dtls">
                        </div>

                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username">
                        </div>

                        <div class="row" id="pwdRow">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password" name="password">
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="password_confirmation">Confirm Password</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                                </div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary form-submit-btn">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- API Setting Add modal ends -->

<!-- API Setting Change Pwd modal starts -->
<div class="modal" id="apiSettingChPwdModal" tabindex="-1" role="dialog" aria-labelledby="addApiSettingForm">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel1">Change Password</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form method="post" action="{{ route('api_setting_ch_pwd') }}" id="chPwdForm">
            @csrf
                <div class="modal-body">
                        <input type="hidden" name="ch_pwd_api_id" id="ch_pwd_api_id" value="0">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="ch_pwd_password" name="ch_pwd_password">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="password_confirmation">Confirm Password</label>
                                    <input type="password" class="form-control" id="ch_pwd_password_confirmation" name="ch_pwd_password_confirmation">
                                </div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- API Setting Change Pwd modal ends -->

</section>

<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<!--Datable plugins -->
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('template_assets/dist/js/pages/datatable/datatable-basic.init.js') }}"></script>
<!-- Datatable plugin ends -->
<script src="template_assets\other\js\bootstrap-toggle.min.js"></script>
<script src="template_assets\other\js\sweetalert.min.js"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script src="{{ asset('dist\setting\js\apiSettingFormvalidation.js') }}"></script>
<script src="{{ asset('dist\setting\js\apiSetting.js') }}"></script>
@endsection
