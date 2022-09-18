@extends('layouts.full')

@section('page_content')

<section>
<!-- This page plugin CSS -->
<link href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css"
        href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('template_assets\other\css\bootstrap-toggle.min.css') }}">

<!-- SMS Gateway Setting table starts -->
<div class="row">
    <div class="col-12">
        <div class="material-card card">
            <div class="card-body">
                <h4 class="card-title">SMS Gateway Setting</h4>
                <br>
                <div class="row card-title">
                    <div class="col-12 text-right">
                        <button type="button" class="btn btn-primary btn-md add-service-btn" data-toggle="modal" data-target="#smsGateSettingAddModal"><i class="fa fa-plus"></i> Add</button>
                    </div>
                </div>
                <br>
                <div class="table-responsive">
                    <table id="sms-gateway-setting-table" class="table table-striped table-sm border is-data-table">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>API Name</th>
                                <th>API URL</th>
                                <th>Username</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($smsGateSettings as $index => $setting)
                                <tr>
                                    <td>{{ $index+1 }}</td>
                                    <td>{{ $setting->api_name }}</td>
                                    <td>{{ $setting->api_url }}</td>
                                    <td>{{ $setting->username }}</td>
                                    <td class="text-center">
                                        @if($setting->activated_status == Config::get('constants.ACTIVE'))
                                            <input checked id="status-btn_{{ $index+1 }}" class="status-btn" type="checkbox" data-id="{{ $setting->id }}" data-on="Active" data-off="Inactive" data-onstyle="success" data-toggle="toggle" data-width="90" data-style="ios" data-style="slow">                                          
                                        @else
                                            <input id="status-btn_{{ $index+1 }}" class="status-btn" type="checkbox" data-id="{{ $setting->id }}" data-on="Active" data-off="Inactive" data-onstyle="success" data-toggle="toggle" data-width="90" data-style="ios" data-style="slow">                                          
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-primary edit-btn" title="Edit" value="{{ $setting->id }}">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-info ch-pwd_btn" title="Change Password" value="{{ $setting->id }}">
                                                <i class="fa fa-key"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="{{ $setting->id }}" title="Delete">
                                                <i class="fa fa-trash"></i>
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
                                <th>Username</th>
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
<!-- SMS Gateway Setting table ends -->

<!-- SMS Gateway Setting Add modal starts -->
<div class="modal" id="smsGateSettingAddModal" tabindex="-1" role="dialog" aria-labelledby="smsGateSettingAddModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel1"><span class="modal-action-name"></span> SMS Gateway</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form method="post" action="{{ route('sms_gate_setting') }}" id="addSmsGateSettingForm">
            @csrf
                <div class="modal-body">
                        <input type="hidden" name="id" id="id" value="0">
                        <div class="form-group">
                            <label for="api_name">API Name</label>
                            <input type="text" class="form-control" id="api_name" name="api_name">
                        </div>

                        <div class="form-group">
                            <label for="api_url">API URL</label>
                            <input type="text" class="form-control" id="api_url" name="api_url">
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
<!-- SMS Gateway Setting Add modal ends -->

<!-- SMS Gateway Setting Change Pwd modal starts -->
<div class="modal" id="smsGateSettingChPwdModal" tabindex="-1" role="dialog" aria-labelledby="smsGateSettingChPwdModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel1">Change Password</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form method="post" action="{{ route('sms_gate_ch_pwd') }}" id="chPwdForm">
            @csrf
                <div class="modal-body">
                        <input type="hidden" name="ch_pwd_id" id="ch_pwd_id" value="0">
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
<!-- SMS Gateway Setting Change Pwd modal ends -->

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
<script src="{{ asset('dist\setting\js\smsGateSettingFormvalidation.js') }}"></script>
<script src="{{ asset('dist\setting\js\smsGateSetting.js') }}"></script>
@endsection
