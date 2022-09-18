@extends('layouts.full')

@section('page_content')

<section>
<!-- This page plugin CSS -->
<link href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css"
        href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('dist\setting\css\my_commission.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('template_assets\other\css\bootstrap-toggle.min.css') }}">

<!-- My Commission table starts -->
<div class="row">
    <div class="col-12">
        <div class="material-card card">
            <div class="card-body">
                <h4 class="card-title">My Commission Details
                    &nbsp; [ Active Package : 
                    @foreach($packageSettings as $setting)
                        @if($setting->package_id == $currentPkgId)
                            {{ $setting->package_name }} ]
                        @endif
                    @endforeach
                </h4>
                <br>
                <div class="row card-title">
                    <?php $serviceAlias = "" ?>
                    <?php $selectedServiceId = "" ?>
                    @foreach($servicesTypes as $sType)
                        @if($sType->service_id == $request['service_id'])
                            <?php $serviceAlias = $sType->alias ?>
                            <?php $selectedServiceId = $sType->service_id ?>
                        @endif
                    @endforeach
                    <input type="hidden" id="selectedServiceId" value="{{ $selectedServiceId }}">
                    <div class="col-6"></div>
                    <div class="col-6">
                        <form method="post" action="{{ route('filter_my_commission') }}" id="filterForm">
                        @csrf
                            <div class="row">
                                <div class="col-4"></div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="pkg_id">Package Settings</label>
                                        <select name="pkg_id" id="pkg_id" class="form-control">
                                            <option disabled selected>Select</option>
                                            @foreach($packageSettings as $setting)
                                                @if($setting->package_id == $request['pkg_id'])
                                                <option value="{{ $setting->package_id }}" selected> {{ $setting->package_name }}</option>
                                                @else
                                                <option value="{{ $setting->package_id }}"> {{ $setting->package_name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="service_id">Service Type</label>
                                        <select name="service_id" id="service_id" class="form-control">
                                            <option disabled selected>Select</option>
                                            @foreach($servicesTypes as $sType)
                                                @if($sType->service_id == $request['service_id'])
                                                    <option value="{{ $sType->service_id }}" data-service_alias="{{ $sType->alias }}" selected> {{ $sType->service_name }}</option>
                                                @else
                                                    <option value="{{ $sType->service_id }}" data-service_alias="{{ $sType->alias }}"> {{ $sType->service_name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <input type="hidden" id="serviceAlias" value="{{ $serviceAlias }}">
                                    </div>
                                </div>

                                <button type="submit" id="filter-submit-btn" style="display:none">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
                <br>
                    <table id="my-commission-table" class="table table-striped table-sm border is-data-table">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Operator Name</th>
                                <th>Commission</th>
                                <th>Commission Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($commissions as $index => $commission)
                                <tr>
                                    <td>{{ $index+1 }}</td>
                                    <td>{{ $commission->operator_name }}</td>
                                    @if(Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.DISTRIBUTOR'))
                                    <td>{{ $commission->distributor_commission }}</td>
                                        @if($serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.MONEY_TRANSFER') || $serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.AEPS'))
                                            <td>{{ $commission->distributor_commission_type }}</td>
                                        @else
                                            <td>{{ $commission->commission_type }}</td>
                                        @endif
                                    @endif

                                    @if(Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.RETAILER'))
                                    <td>{{ $commission->retailer_commission }}</td>
                                        @if($serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.MONEY_TRANSFER') || $serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.AEPS'))
                                            <td>{{ $commission->retailer_commission_type }}</td>
                                        @else
                                            <td>{{ $commission->commission_type }}</td>
                                        @endif
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Sr No</th>
                                <th>Operator Name</th>
                                <th>Commission</th>
                                <th>Commission Type</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- My commission table ends -->

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
<script src="{{ asset('dist\setting\js\myCommission.js') }}"></script>
@endsection
