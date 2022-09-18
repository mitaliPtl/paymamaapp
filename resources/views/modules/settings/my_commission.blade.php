{{-- @extends('layouts.full') --}}
@extends('layouts.full_new')
@section('page_content')
<div class="page-content container-fluid">
<section ng-app="myApp" ng-controller="packCommDtlsCtrl">

<style>
    th {
                text-transform: uppercase;
    }
</style>
<!-- This page plugin CSS -->
<link href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css"
        href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('template_assets\other\css\bootstrap-toggle.min.css') }}">

<!-- Package Commision Details table starts -->
<input type="hidden" id="hiddenOperatorsList" value="{{ $operators }}">
<input type="hidden" id="hiddenPackageCommDetails" value="{{ $packageCommDetails }}">

<input type="hidden" id="mobilePrepaidAlias" value="{{ Config::get('constants.SERVICE_TYPE_ALIAS.MOBILE_PREPAID') }}">
<input type="hidden" id="mobilePostpaidAlias" value="{{ Config::get('constants.SERVICE_TYPE_ALIAS.MOBILE_POSTPAID') }}">
<input type="hidden" id="dthAlias" value="{{ Config::get('constants.SERVICE_TYPE_ALIAS.DTH') }}">
<input type="hidden" id="billPaymentsAlias" value="{{ Config::get('constants.SERVICE_TYPE_ALIAS.BILL_PAYMENTS') }}">
<input type="hidden" id="moneyTransferAlias" value="{{ Config::get('constants.SERVICE_TYPE_ALIAS.MONEY_TRANSFER') }}">
<input type="hidden" id="aepsAlias" value="{{ Config::get('constants.SERVICE_TYPE_ALIAS.AEPS') }}">
<input type="hidden" id="upiTransferAlias" value="{{ Config::get('constants.SERVICE_TYPE_ALIAS.UPI_TRANSFER') }}">
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
                                @if($serviceAlias == "" || $serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.MOBILE_PREPAID') || $serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.MOBILE_POSTPAID') || $serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.DTH') || $serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.BILL_PAYMENTS'))
                                <div class="col-7"></div>
                                @endif
                                <!-- <div class="col-4">
                                    <div class="form-group">
                                        <label for="pkg_id">Package Settings</label>
                                        <select name="pkg_id" id="pkg_id" class="form-control">
                                            <option disabled selected>Select</option>
                                            {{-- @foreach($packageSettings as $setting)
                                                @if($setting->package_id == $request['pkg_id'])
                                                <option value="{{ $setting->package_id }}" selected> {{ $setting->package_name }}</option>
                                                @else
                                                <option value="{{ $setting->package_id }}"> {{ $setting->package_name }}</option>
                                                @endif
                                            @endforeach --}}
                                        </select>
                                    </div>
                                </div> -->
                                <!-- <input type="hidden" id="pkg_id" name="pkg_id" value="{{-- $setting->package_id --}}"> -->
                                <input type="hidden" id="pkg_id" name="pkg_id" value="{{  $currentPkgId }}">
                                <div class="col-5" style="float:right">
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

                                @if($serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.MONEY_TRANSFER') || $serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.UPI_TRANSFER') || $serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.AEPS'))
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="operator_id">Operator</label>
                                        <select name="operator_id" id="operator_id" class="form-control">
                                            <option disabled selected>Select</option>
                                            @foreach($operators as $operator)
                                                @if($operator->operator_id == $request['operator_id'])
                                                    <option value="{{ $operator->operator_id }}" selected> {{ $operator->operator_name }}</option>
                                                @else
                                                    <option value="{{ $operator->operator_id }}"> {{ $operator->operator_name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                @endif
                                <button type="submit" id="filter-submit-btn" style="display:none">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
                @if($serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.MOBILE_PREPAID') || $serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.MOBILE_POSTPAID') || $serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.DTH') || $serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.BILL_PAYMENTS'))
                <!-- Mobile/Dth/Bill Payments Table starts-->
                <div class="table-responsive" id="pack-comm-dtls-table-div-1">
                    <!-- <table id="pack-comm-dtls-table-1" class="table table-striped table-sm border is-data-table-pkcm"> -->
                    <table id="pack-comm-dtls-table-1" class="table table-striped table-bordered table-sm border is-data-table is-data-table-pkcm">

                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Operator Name</th>
                                <th>Commission</th>

                                <th>Commission Type</th>

                            </tr>
                        </thead>
                        <tbody>
                                <tr ng-repeat="data in mTranAEPSSaveList" set-data-table>
                                    <td ng-bind="$index+1"></td>
                                    <td ng-bind="data['operator_name']"></td>
                                    
                                    @if(Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.DISTRIBUTOR'))
                                        <td ng-bind="data['distributor_commission']"></td>
                                    @endif
                                    @if(Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.RETAILER'))
                                        <td ng-bind="data['retailer_commission']"></td>
                                    @endif
                                    <td ng-bind="data['commission_type']"></td>
                                </tr>
                        </tbody>
                        <tfoot>
                                <th>Sr No</th>
                                <th>Operator Name</th>

                                @if(Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.DISTRIBUTOR'))
                                    <th>Dis</th>
                                @endif
                                @if(Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.RETAILER'))
                                    <th>RT</th>
                                @endif

                                <th>Commission Type</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- Mobile/Dth/Bill Payments Table ends-->
                
                @elseif($serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.MONEY_TRANSFER') || $serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.UPI_TRANSFER') || $serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.AEPS'))
               <!-- Money Transfer/AEPS Table starts-->
               <div class="table-responsive" id="pack-comm-dtls-div-2">
                    <table id="pack-comm-dtls-table-2" class="table table-striped table-sm border is-data-table-pkcm">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>From</th>
                                <th>To</th>
                                @if($serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.MONEY_TRANSFER'))
                                <th class="text-center">CCF
                                    <input type="text" id="ccf-type-dd"  class=" form-control text-center" readonly>
                                </th>
                                @endif
                                <th class="text-center">Commission
                                    @if(Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.DISTRIBUTOR'))
                                        <input type="text" class="form-control label text-center" id="dis-type-dd" readonly>
                                    @endif

                                    @if(Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.RETAILER'))
                                        <input type="text" class="form-control text-center" id="rt-type-dd" readonly>
                                    @endif
                                </th>
                            </tr>
                            
                        </thead>
                        <tbody>
                            <tr ng-repeat="data in mTranAEPSSaveList track by $index" ng-if="!data['distributor_commission'] && !data['retailer_commission']">
                                <td class="text-center" colspan="5">No data found!</td>
                            </tr>
                            <tr ng-repeat="data in mTranAEPSSaveList track by $index" ng-if="data['distributor_commission'] || data['retailer_commission']" set-data-table>
                                <td ng-bind="$index+1"></td>
                                <td ng-bind="data['from_range']"></td>
                                <td ng-bind="data['to_range']"></td>
                                @if($serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.MONEY_TRANSFER'))
                                <td class="text-center" ng-bind="data['ccf_commission']"></td>
                                @endif
                               
                                @if(Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.DISTRIBUTOR'))
                                    <td class="text-center" ng-bind="data['distributor_commission']"></td>
                                @endif

                                @if(Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.RETAILER'))
                                    <td class="text-center" ng-bind="data['retailer_commission']"></td>
                                @endif
                                
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Money Transfer/AEPS Table ends-->
                @else
                <!-- All Table starts-->
                <div class="table-responsive" id="pack-comm-dtls-div-3">
                    <table id="pack-comm-dtls-table-3" class="table table-striped table-sm border">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Operator Name</th>
                                @if(Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.DISTRIBUTOR'))
                                <th>Dis</th>
                                @endif
                                @if(Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.RETAILER'))
                                <th>RT</th>
                                @endif
                                <th class="text-center">Commission Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center" colspan="8">Data not found! </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Sr No</th>
                                <th>Operator Name</th>
                                @if(Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.DISTRIBUTOR'))
                                <th>Dis</th>
                                @endif
                                @if(Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.RETAILER'))
                                <th>RT</th>
                                @endif
                                <th class="text-center">Commission Type</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- All Table ends-->
                @endif

            </div>
        </div>
    </div>
</div>
<!-- Package commision details table ends -->

</section>
</div>
<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<!--Datable plugins -->
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('template_assets/dist/js/pages/datatable/datatable-basic.init.js') }}"></script>

<!-- Datatable plugin ends -->
<script src="template_assets\other\js\bootstrap-toggle.min.js"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script src="{{ asset('dist\setting\js\packCommDtlsFormValidation.js') }}"></script>
<script src="{{ asset('template_assets\other\js\angular.min.js') }}"></script>
<script src="{{ asset('dist\setting\js\packCommDtls.js') }}"></script>
@endsection
