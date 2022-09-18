@extends('layouts.full')

@section('page_content')

<section>
<!-- This page plugin CSS -->
<link href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css"
        href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
<!-- <link rel="stylesheet" type="text/css" href="{{ asset('dist\setting\css\package_setting_list.css') }}"> -->
<link rel="stylesheet" type="text/css" href="{{ asset('template_assets\other\css\bootstrap-toggle.min.css') }}">

<!-- Package Setting table starts -->
<div class="row">
    <div class="col-12">
        <div class="material-card card">
            <div class="card-body">
                <h4 class="card-title">Package Setting</h4>
                <br>
                <div class="row card-title">
                    <div class="col-12 text-right">
                        <button type="button" class="btn btn-primary btn-md add-service-btn" data-toggle="modal" data-target="#packageSettingAddModal"><i class="fa fa-plus"></i> Add Package Setting</button>
                    </div>
                </div>
                <br>
                <div class="table-responsive">
                    <table id="package-setting-table" class="table table-striped table-sm border is-data-table">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Package Name</th>
                                <th>Package Desc.</th>
                                <th>Retailer Cost</th>
                                <th>Distributor Cost</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($packageSettings as $index => $setting)
                                <tr>
                                    <td>{{ $index+1 }}</td>
                                    <td>{{ $setting->package_name }}</td>
                                    <td>{{ $setting->package_descr }}</td>
                                    <td>{{ $setting->retailer_cost }}</td>
                                    <td>{{ $setting->distributor_cost }}</td>
                                    <td class="text-center">
                                        @if($setting->activated_status == Config::get('constants.ACTIVE'))
                                            <input checked id="status-btn_{{ $index+1 }}" class="status-btn" type="checkbox" data-id="{{ $setting->package_id }}" data-on="Active" data-off="Inactive" data-onstyle="success" data-toggle="toggle" data-width="90" data-style="ios" data-style="slow">                                          
                                        @else
                                            <input id="status-btn_{{ $index+1 }}" class="status-btn" type="checkbox" data-id="{{ $setting->package_id }}" data-on="Active" data-off="Inactive" data-onstyle="success" data-toggle="toggle" data-width="90" data-style="ios" data-style="slow">                                          
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-primary edit-btn" title="Edit" value="{{ $setting->package_id }}">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="{{ $setting->package_id }}" title="Delete">
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
                                <th>Package Name</th>
                                <th>Package Desc.</th>
                                <th>Retailer Cost</th>
                                <th>Distributor Cost</th>
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
<!-- Package Setting table ends -->

<!-- Package Setting Add modal starts -->
<div class="modal" id="packageSettingAddModal" tabindex="-1" role="dialog" aria-labelledby="serviceTypeAddModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel1"><span class="modal-action-name"></span> Package Setting</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form method="post" action="{{ route('package_setting') }}" id="addPackageSettingForm">
            @csrf
                <div class="modal-body">
                        <input type="hidden" name="package_id" id="package_id" value="0">
                        <div class="form-group">
                            <label for="package_name">Package Name</label>
                            <input type="text" class="form-control" id="package_name" name="package_name">
                        </div>

                        <div class="form-group">
                            <label for="package_descr">Package Description</label>
                            <input type="text" class="form-control" id="package_descr" name="package_descr">
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="retailer_cost">Retailer Cost</label>
                                    <input type="text" class="form-control" id="retailer_cost" name="retailer_cost">
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="distributor_cost">Distributor Cost</label>
                                    <input type="text" class="form-control" id="distributor_cost" name="distributor_cost">
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
<!-- Package Setting Add modal ends -->


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
<script src="{{ asset('dist\setting\js\packageSettingFormvalidation.js') }}"></script>
<script src="{{ asset('dist\setting\js\package_setting_list.js') }}"></script>
@endsection
