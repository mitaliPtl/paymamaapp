@extends('layouts.full')

@section('page_content')

<section ng-app="myApp" ng-controller="packCommDtlsCtrl">
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
<input type="hidden" id="icicdAlias" value="{{ Config::get('constants.SERVICE_TYPE_ALIAS.ICICI_CASH_DEPOSIT') }}">
<input type="hidden" id="apAlias" value="{{ Config::get('constants.SERVICE_TYPE_ALIAS.AADHAR_PAY') }}">
<input type="hidden" id="MinistatementAlias" value="{{ Config::get('constants.SERVICE_TYPE_ALIAS.Mini_Statement') }}">
<div class="row">
    <div class="col-12">
        <div class="material-card card">
            <div class="card-body">
                <h4 class="card-title">Package Commission Details</h4>
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
                        <form method="post" action="{{ route('pack_comm_dtls_filter') }}" id="filterForm">
                        @csrf
                            <div class="row">
                                @if($serviceAlias == "" || $serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.MOBILE_PREPAID') || $serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.MOBILE_POSTPAID') || $serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.DTH') || $serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.BILL_PAYMENTS'))
                                <div class="col-4"></div>
                                @endif
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

                                @if($serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.MONEY_TRANSFER') || $serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.Mini_Statement') || $serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.AEPS') || $serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.UPI_TRANSFER') || $serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.AADHAR_PAY')  || $serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.ICICI_CASH_DEPOSIT') || $serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.BANK_TRANSFER'))
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
                    <table id="pack-comm-dtls-table-1" class="table table-striped table-sm border is-data-table-pkcm">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Operator Name</th>

                                <th>API Charge/Commission</th>
                                <th>Admin</th>
                                <th>MD</th>
                                <th>Dis</th>
                                <th>RT</th>

                                <th>Commission Type</th>

                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                                <tr ng-repeat="data in mTranAEPSSaveList" set-data-table>
                                    <td ng-bind="$index+1"></td>
                                    <td ng-bind="data['operator_name']"></td>
                                    <td>
                                        <input type="text" class="form-control" ng-model="data['api_charge_commission']" required>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" ng-model="data['admin_commission']" required>
                                    </td>

                                    <td>
                                        <input type="text" class="form-control" ng-model="data['md_commission']" required>
                                    </td>

                                    <td>
                                        <input type="text" class="form-control" ng-model="data['distributor_commission']" required>
                                    </td>

                                    <td>
                                        <input type="text" class="form-control" ng-model="data['retailer_commission']" required>
                                    </td>

                                    <td>
                                        <select class="form-control" ng-model="data['commission_type']">
                                            <option disabled selected value="">Select</option>
                                            <option value="Rupees">Rupees</option>
                                            <option value="Percent">Percent %</option>
                                        </select>
                                    </td>

                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-outline-info" ng-click="saveSettings(data)" ng-if="data.pkg_commission_id" title="Edit" data-pk-cm-details-id="<%= data.pkg_commission_id %>">
                                                <i class="fa fa-edit"></i> Edit
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-primary" ng-click="saveSettings(data)" ng-if="!data.pkg_commission_id" title="Save" data-pk-cm-details-id="0">
                                                <i class="fa fa-save"></i> Save
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                        </tbody>
                        <tfoot>
                                <th>Sr No</th>
                                <th>Operator Name</th>

                                <th>API Charge/Commission</th>
                                <th>Admin</th>
                                <th>MD</th>
                                <th>Dis</th>
                                <th>RT</th>

                                <th>Commission Type</th>

                                <th class="text-center">Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- Mobile/Dth/Bill Payments Table ends-->
                
                @elseif($serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.MONEY_TRANSFER') || $serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.AEPS')  || $serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.Mini_Statement')  || $serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.UPI_TRANSFER') || $serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.BANK_TRANSFER'))
                <!-- Money Transfer/AEPS Table starts-->
                <div class="table-responsive" id="pack-comm-dtls-div-2">
                    <table id="pack-comm-dtls-table-2" class="table table-striped table-sm border is-data-table-pkcm">
                        <thead>
                            <tr>
                                <th rowspan="2">Sr No</th>
                                <th class="text-center">From</th>
                                <th class="text-center">To</th>
                                @if($serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.MONEY_TRANSFER')  || $serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.UPI_TRANSFER'))
                                <th>CCF</th>
                                @endif
                                <th>API Charge/Commission</th>
                                <th>Admin</th>
                                <th>MD</th>
                                <th>Dis</th>
                                <th>RT</th>
                                <th class="text-center" rowspan="2">
                                    Action
                                    <button type="button" class="btn btn-sm btn-info" ng-click="addRow()" title="Add Row"><i class="fa fa-plus"></i></button>
                                </th>
                            </tr>
                            <tr>
                                <th colspan="2" class="text-center">Commission Type</th>
                                @if($serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.MONEY_TRANSFER') || $serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.UPI_TRANSFER'))
                                <th>
                                    <select id="ccf-type-dd" class="form-control comm-type-dd" onchange="setComTypInp('ccf-type')">
                                        <option disabled selected>Select</option>
                                        <option value="Rupees">Rupees</option>
                                        <option value="Percent">Percent %</option>
                                    </select>
                                </th>
                                @endif
                                <th>
                                    <select id="api-charge-type-dd" class="form-control comm-type-dd" onchange="setComTypInp('api-charge-type')">
                                        <option disabled selected>Select</option>
                                        <option value="Rupees">Rupees</option>
                                        <option value="Percent">Percent %</option>
                                    </select>
                                </th>
                                <th>
                                    <select id="ad-type-dd" class="form-control comm-type-dd" onchange="setComTypInp('ad-type')">
                                        <option disabled selected>Select</option>
                                        <option value="Rupees">Rupees</option>
                                        <option value="Percent">Percent %</option>
                                    </select>
                                </th>
                                <th>
                                    <select id="md-type-dd" class="form-control comm-type-dd" onchange="setComTypInp('md-type')">
                                        <option disabled selected>Select</option>
                                        <option value="Rupees">Rupees</option>
                                        <option value="Percent">Percent %</option>
                                    </select>
                                </th>
                                <th>
                                    <select id="dis-type-dd" class="form-control comm-type-dd" onchange="setComTypInp('dis-type')">
                                        <option disabled selected>Select</option>
                                        <option value="Rupees">Rupees</option>
                                        <option value="Percent">Percent %</option>
                                    </select>
                                </th>
                                <th>
                                    <select id="rt-type-dd" class="form-control comm-type-dd" onchange="setComTypInp('rt-type')">
                                        <option disabled selected>Select</option>
                                        <option value="Rupees">Rupees</option>
                                        <option value="Percent">Percent %</option>
                                    </select>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="data in mTranAEPSSaveList track by $index" set-data-table>
                                <td ng-bind="$index+1"></td>
                                <td>
                                    <input type="text" id="from_range_<%= $index+1 %>" ng-model="data['from_range']" class="form-control" placeholder="From" required>
                                </td>
                                <td>
                                    <input type="text" id="to_range_<%= $index+1 %>" ng-model="data['to_range']" class="form-control" placeholder="To" required>
                                </td>
                                @if($serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.MONEY_TRANSFER') || $serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.UPI_TRANSFER'))
                                <td>
                                    <input type="text" id="ccf_com_type_<%= $index+1 %>" ng-model="data['ccf_commission_type']" class="form-control ccf-type-inp hide-this" readonly>
                                    <input type="text" class="form-control" ng-model="data['ccf_commission']" required>
                                </td>
                                @endif
                                <td>
                                    <input type="text" id="api_com_type_<%= $index+1 %>" ng-model="data['api_charge_commission_type']" class="form-control api-charge-type-inp hide-this" readonly>
                                    <input type="text" class="form-control" ng-model="data['api_charge_commission']" required>
                                </td>
                                <td>
                                    <input type="text" id="md_com_type_<%= $index+1 %>" ng-model="data['admin_commission_type']" class="form-control ad-type-inp hide-this" readonly>
                                    <input type="text" class="form-control" ng-model="data['admin_commission']" required>
                                </td>

                                <td>
                                    <input type="text" id="admin_com_type_<%= $index+1 %>" ng-model="data['md_commission_type']"  class="form-control md-type-inp hide-this" readonly>
                                    <input type="text" class="form-control" ng-model="data['md_commission']" required>
                                </td>

                                <td>
                                    <input type="text" id="distributor_com_type_<%= $index+1 %>" ng-model="data['distributor_commission_type']" class="form-control dis-type-inp hide-this" readonly>
                                    <input type="text" class="form-control" ng-model="data['distributor_commission']" required>
                                </td>

                                <td>
                                    <input type="text" id="retailer_com_type_<%= $index+1 %>" ng-model="data['retailer_commission_type']" class="form-control rt-type-inp hide-this" readonly>
                                    <input type="text" class="form-control" ng-model="data['retailer_commission']" required>
                                </td>

                                <td class="text-center">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-info" ng-click="saveSettings(data)" ng-if="data.pkg_commission_id" title="Edit" data-pk-cm-details-id="<%= data.pkg_commission_id %>">
                                            <i class="fa fa-edit"></i> Edit
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-primary" ng-click="saveSettings(data)" ng-if="!data.pkg_commission_id" title="Save" data-pk-cm-details-id="0">
                                            <i class="fa fa-save"></i> Save
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Money Transfer/AEPS Table ends-->
                <!--ICICI CASHDEPOSIT STARTS-->
                @elseif($serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.ICICI_CASH_DEPOSIT'))
               
                <div class="table-responsive" id="pack-comm-dtls-div-2">
                    <table id="pack-comm-dtls-table-2" class="table table-striped table-sm border is-data-table-pkcm">
                        <thead>
                            <tr>
                                <th rowspan="2">Sr No</th>
                                <th class="text-center">From</th>
                                <th class="text-center">To</th>
                                @if($serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.ICICI_CASH_DESPOSIT')  || $serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.UPI_TRANSFER'))
                                <th>CCF</th>
                                @endif
                                <th>API Charge/Commission</th>
                                <th>Admin</th>
                                <th>MD</th>
                                <th>Dis</th>
                                <th>RT</th>
                                <th class="text-center" rowspan="2">
                                    Action
                                    <button type="button" class="btn btn-sm btn-info" ng-click="addRow()" title="Add Row"><i class="fa fa-plus"></i></button>
                                </th>
                            </tr>
                            <tr>
                                <th colspan="2" class="text-center">Commission Type</th>
                                @if($serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.ICICI_CASH_DESPOSIT') || $serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.UPI_TRANSFER'))
                                <th>
                                    <select id="ccf-type-dd" class="form-control comm-type-dd" onchange="setComTypInp('ccf-type')">
                                        <option disabled selected>Select</option>
                                        <option value="Rupees">Rupees</option>
                                        <option value="Percent">Percent %</option>
                                    </select>
                                </th>
                                @endif
                                <th>
                                    <select id="api-charge-type-dd" class="form-control comm-type-dd" onchange="setComTypInp('api-charge-type')">
                                        <option disabled selected>Select</option>
                                        <option value="Rupees">Rupees</option>
                                        <option value="Percent">Percent %</option>
                                    </select>
                                </th>
                                <th>
                                    <select id="ad-type-dd" class="form-control comm-type-dd" onchange="setComTypInp('ad-type')">
                                        <option disabled selected>Select</option>
                                        <option value="Rupees">Rupees</option>
                                        <option value="Percent">Percent %</option>
                                    </select>
                                </th>
                                <th>
                                    <select id="md-type-dd" class="form-control comm-type-dd" onchange="setComTypInp('md-type')">
                                        <option disabled selected>Select</option>
                                        <option value="Rupees">Rupees</option>
                                        <option value="Percent">Percent %</option>
                                    </select>
                                </th>
                                <th>
                                    <select id="dis-type-dd" class="form-control comm-type-dd" onchange="setComTypInp('dis-type')">
                                        <option disabled selected>Select</option>
                                        <option value="Rupees">Rupees</option>
                                        <option value="Percent">Percent %</option>
                                    </select>
                                </th>
                                <th>
                                    <select id="rt-type-dd" class="form-control comm-type-dd" onchange="setComTypInp('rt-type')">
                                        <option disabled selected>Select</option>
                                        <option value="Rupees">Rupees</option>
                                        <option value="Percent">Percent %</option>
                                    </select>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="data in mTranAEPSSaveList track by $index" set-data-table>
                                <td ng-bind="$index+1"></td>
                                <td>
                                    <input type="text" id="from_range_<%= $index+1 %>" ng-model="data['from_range']" class="form-control" placeholder="From" required>
                                </td>
                                <td>
                                    <input type="text" id="to_range_<%= $index+1 %>" ng-model="data['to_range']" class="form-control" placeholder="To" required>
                                </td>
                                <!--@if($serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.ICICI_CASH_DEPOSIT') || $serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.ICICI_CASH_DEPOSIT'))-->
                                <!--<td>-->
                                <!--    <input type="text" id="ccf_com_type_<%= $index+1 %>" ng-model="data['ccf_commission_type']" class="form-control ccf-type-inp hide-this" readonly>-->
                                <!--    <input type="text" class="form-control" ng-model="data['ccf_commission']" required>-->
                                <!--</td>-->
                                <!--@endif-->
                                <td>
                                    <input type="text" id="api_com_type_<%= $index+1 %>" ng-model="data['api_charge_commission_type']" class="form-control api-charge-type-inp hide-this" readonly>
                                    <input type="text" class="form-control" ng-model="data['api_charge_commission']" required>
                                </td>
                                <td>
                                    <input type="text" id="md_com_type_<%= $index+1 %>" ng-model="data['admin_commission_type']" class="form-control ad-type-inp hide-this" readonly>
                                    <input type="text" class="form-control" ng-model="data['admin_commission']" required>
                                </td>

                                <td>
                                    <input type="text" id="admin_com_type_<%= $index+1 %>" ng-model="data['md_commission_type']"  class="form-control md-type-inp hide-this" readonly>
                                    <input type="text" class="form-control" ng-model="data['md_commission']" required>
                                </td>

                                <td>
                                    <input type="text" id="distributor_com_type_<%= $index+1 %>" ng-model="data['distributor_commission_type']" class="form-control dis-type-inp hide-this" readonly>
                                    <input type="text" class="form-control" ng-model="data['distributor_commission']" required>
                                </td>

                                <td>
                                    <input type="text" id="retailer_com_type_<%= $index+1 %>" ng-model="data['retailer_commission_type']" class="form-control rt-type-inp hide-this" readonly>
                                    <input type="text" class="form-control" ng-model="data['retailer_commission']" required>
                                </td>

                                <td class="text-center">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-info" ng-click="saveSettings(data)" ng-if="data.pkg_commission_id" title="Edit" data-pk-cm-details-id="<%= data.pkg_commission_id %>">
                                            <i class="fa fa-edit"></i> Edit
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-primary" ng-click="saveSettings(data)" ng-if="!data.pkg_commission_id" title="Save" data-pk-cm-details-id="0">
                                            <i class="fa fa-save"></i> Save
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!--ICICI CASHDEPOSIT ENDS-->
                 <!--AADHAR PAY STARTS-->
                 @elseif($serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.AADHAR_PAY'))
               
               <div class="table-responsive" id="pack-comm-dtls-div-2">
                   <table id="pack-comm-dtls-table-2" class="table table-striped table-sm border is-data-table-pkcm">
                       <thead>
                           <tr>
                               <th rowspan="2">Sr No</th>
                               <th class="text-center">From</th>
                               <th class="text-center">To</th>
                               @if($serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.MONEY_TRANSFER')  || $serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.UPI_TRANSFER'))
                               <th>CCF</th>
                               @endif
                               <th>API Charge/Commission</th>
                               <th>Admin</th>
                               <th>MD</th>
                               <th>Dis</th>
                               <th>RT</th>
                               <th class="text-center" rowspan="2">
                                   Action
                                   <button type="button" class="btn btn-sm btn-info" ng-click="addRow()" title="Add Row"><i class="fa fa-plus"></i></button>
                               </th>
                           </tr>
                           <tr>
                               <th colspan="2" class="text-center">Commission Type</th>
                               @if($serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.MONEY_TRANSFER') || $serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.UPI_TRANSFER'))
                               <th>
                                   <select id="ccf-type-dd" class="form-control comm-type-dd" onchange="setComTypInp('ccf-type')">
                                       <option disabled selected>Select</option>
                                       <option value="Rupees">Rupees</option>
                                       <option value="Percent">Percent %</option>
                                   </select>
                               </th>
                               @endif
                               <th>
                                   <select id="api-charge-type-dd" class="form-control comm-type-dd" onchange="setComTypInp('api-charge-type')">
                                       <option disabled selected>Select</option>
                                       <option value="Rupees">Rupees</option>
                                       <option value="Percent">Percent %</option>
                                   </select>
                               </th>
                               <th>
                                   <select id="ad-type-dd" class="form-control comm-type-dd" onchange="setComTypInp('ad-type')">
                                       <option disabled selected>Select</option>
                                       <option value="Rupees">Rupees</option>
                                       <option value="Percent">Percent %</option>
                                   </select>
                               </th>
                               <th>
                                   <select id="md-type-dd" class="form-control comm-type-dd" onchange="setComTypInp('md-type')">
                                       <option disabled selected>Select</option>
                                       <option value="Rupees">Rupees</option>
                                       <option value="Percent">Percent %</option>
                                   </select>
                               </th>
                               <th>
                                   <select id="dis-type-dd" class="form-control comm-type-dd" onchange="setComTypInp('dis-type')">
                                       <option disabled selected>Select</option>
                                       <option value="Rupees">Rupees</option>
                                       <option value="Percent">Percent %</option>
                                   </select>
                               </th>
                               <th>
                                   <select id="rt-type-dd" class="form-control comm-type-dd" onchange="setComTypInp('rt-type')">
                                       <option disabled selected>Select</option>
                                       <option value="Rupees">Rupees</option>
                                       <option value="Percent">Percent %</option>
                                   </select>
                               </th>
                           </tr>
                       </thead>
                       <tbody>
                           <tr ng-repeat="data in mTranAEPSSaveList track by $index" set-data-table>
                               <td ng-bind="$index+1"></td>
                               <td>
                                   <input type="text" id="from_range_<%= $index+1 %>" ng-model="data['from_range']" class="form-control" placeholder="From" required>
                               </td>
                               <td>
                                   <input type="text" id="to_range_<%= $index+1 %>" ng-model="data['to_range']" class="form-control" placeholder="To" required>
                               </td>
                               @if($serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.MONEY_TRANSFER') || $serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.UPI_TRANSFER'))
                               <td>
                                   <input type="text" id="ccf_com_type_<%= $index+1 %>" ng-model="data['ccf_commission_type']" class="form-control ccf-type-inp hide-this" readonly>
                                   <input type="text" class="form-control" ng-model="data['ccf_commission']" required>
                               </td>
                               @endif
                               <td>
                                   <input type="text" id="api_com_type_<%= $index+1 %>" ng-model="data['api_charge_commission_type']" class="form-control api-charge-type-inp hide-this" readonly>
                                   <input type="text" class="form-control" ng-model="data['api_charge_commission']" required>
                               </td>
                               <td>
                                   <input type="text" id="md_com_type_<%= $index+1 %>" ng-model="data['admin_commission_type']" class="form-control ad-type-inp hide-this" readonly>
                                   <input type="text" class="form-control" ng-model="data['admin_commission']" required>
                               </td>

                               <td>
                                   <input type="text" id="admin_com_type_<%= $index+1 %>" ng-model="data['md_commission_type']"  class="form-control md-type-inp hide-this" readonly>
                                   <input type="text" class="form-control" ng-model="data['md_commission']" required>
                               </td>

                               <td>
                                   <input type="text" id="distributor_com_type_<%= $index+1 %>" ng-model="data['distributor_commission_type']" class="form-control dis-type-inp hide-this" readonly>
                                   <input type="text" class="form-control" ng-model="data['distributor_commission']" required>
                               </td>

                               <td>
                                   <input type="text" id="retailer_com_type_<%= $index+1 %>" ng-model="data['retailer_commission_type']" class="form-control rt-type-inp hide-this" readonly>
                                   <input type="text" class="form-control" ng-model="data['retailer_commission']" required>
                               </td>

                               <td class="text-center">
                                   <div class="btn-group">
                                       <button type="button" class="btn btn-sm btn-outline-info" ng-click="saveSettings(data)" ng-if="data.pkg_commission_id" title="Edit" data-pk-cm-details-id="<%= data.pkg_commission_id %>">
                                           <i class="fa fa-edit"></i> Edit
                                       </button>
                                       <button type="button" class="btn btn-sm btn-outline-primary" ng-click="saveSettings(data)" ng-if="!data.pkg_commission_id" title="Save" data-pk-cm-details-id="0">
                                           <i class="fa fa-save"></i> Save
                                       </button>
                                   </div>
                               </td>
                           </tr>
                       </tbody>
                   </table>
               </div>
                @elseif($serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.Mini_Statement'))
               
               <div class="table-responsive" id="pack-comm-dtls-div-2">
                   <table id="pack-comm-dtls-table-2" class="table table-striped table-sm border is-data-table-pkcm">
                       <thead>
                           <tr>
                               <th rowspan="2">Sr No</th>
                               <th class="text-center">From</th>
                               <th class="text-center">To</th>
                               @if($serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.MONEY_TRANSFER')  || $serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.UPI_TRANSFER'))
                               <th>CCF</th>
                               @endif
                               <th>API Charge/Commission</th>
                               <th>Admin</th>
                               <th>MD</th>
                               <th>Dis</th>
                               <th>RT</th>
                               <th class="text-center" rowspan="2">
                                   Action
                                   <button type="button" class="btn btn-sm btn-info" ng-click="addRow()" title="Add Row"><i class="fa fa-plus"></i></button>
                               </th>
                           </tr>
                           <tr>
                               <th colspan="2" class="text-center">Commission Type</th>
                               @if($serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.MONEY_TRANSFER') || $serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.UPI_TRANSFER'))
                               <th>
                                   <select id="ccf-type-dd" class="form-control comm-type-dd" onchange="setComTypInp('ccf-type')">
                                       <option disabled selected>Select</option>
                                       <option value="Rupees">Rupees</option>
                                       <option value="Percent">Percent %</option>
                                   </select>
                               </th>
                               @endif
                               <th>
                                   <select id="api-charge-type-dd" class="form-control comm-type-dd" onchange="setComTypInp('api-charge-type')">
                                       <option disabled selected>Select</option>
                                       <option value="Rupees">Rupees</option>
                                       <option value="Percent">Percent %</option>
                                   </select>
                               </th>
                               <th>
                                   <select id="ad-type-dd" class="form-control comm-type-dd" onchange="setComTypInp('ad-type')">
                                       <option disabled selected>Select</option>
                                       <option value="Rupees">Rupees</option>
                                       <option value="Percent">Percent %</option>
                                   </select>
                               </th>
                               <th>
                                   <select id="md-type-dd" class="form-control comm-type-dd" onchange="setComTypInp('md-type')">
                                       <option disabled selected>Select</option>
                                       <option value="Rupees">Rupees</option>
                                       <option value="Percent">Percent %</option>
                                   </select>
                               </th>
                               <th>
                                   <select id="dis-type-dd" class="form-control comm-type-dd" onchange="setComTypInp('dis-type')">
                                       <option disabled selected>Select</option>
                                       <option value="Rupees">Rupees</option>
                                       <option value="Percent">Percent %</option>
                                   </select>
                               </th>
                               <th>
                                   <select id="rt-type-dd" class="form-control comm-type-dd" onchange="setComTypInp('rt-type')">
                                       <option disabled selected>Select</option>
                                       <option value="Rupees">Rupees</option>
                                       <option value="Percent">Percent %</option>
                                   </select>
                               </th>
                           </tr>
                       </thead>
                       <tbody>
                           <tr ng-repeat="data in mTranAEPSSaveList track by $index" set-data-table>
                               <td ng-bind="$index+1"></td>
                               <td>
                                   <input type="text" id="from_range_<%= $index+1 %>" ng-model="data['from_range']" class="form-control" placeholder="From" required>
                               </td>
                               <td>
                                   <input type="text" id="to_range_<%= $index+1 %>" ng-model="data['to_range']" class="form-control" placeholder="To" required>
                               </td>
                               @if($serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.MONEY_TRANSFER') || $serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.UPI_TRANSFER'))
                               <td>
                                   <input type="text" id="ccf_com_type_<%= $index+1 %>" ng-model="data['ccf_commission_type']" class="form-control ccf-type-inp hide-this" readonly>
                                   <input type="text" class="form-control" ng-model="data['ccf_commission']" required>
                               </td>
                               @endif
                               <td>
                                   <input type="text" id="api_com_type_<%= $index+1 %>" ng-model="data['api_charge_commission_type']" class="form-control api-charge-type-inp hide-this" readonly>
                                   <input type="text" class="form-control" ng-model="data['api_charge_commission']" required>
                               </td>
                               <td>
                                   <input type="text" id="md_com_type_<%= $index+1 %>" ng-model="data['admin_commission_type']" class="form-control ad-type-inp hide-this" readonly>
                                   <input type="text" class="form-control" ng-model="data['admin_commission']" required>
                               </td>

                               <td>
                                   <input type="text" id="admin_com_type_<%= $index+1 %>" ng-model="data['md_commission_type']"  class="form-control md-type-inp hide-this" readonly>
                                   <input type="text" class="form-control" ng-model="data['md_commission']" required>
                               </td>

                               <td>
                                   <input type="text" id="distributor_com_type_<%= $index+1 %>" ng-model="data['distributor_commission_type']" class="form-control dis-type-inp hide-this" readonly>
                                   <input type="text" class="form-control" ng-model="data['distributor_commission']" required>
                               </td>

                               <td>
                                   <input type="text" id="retailer_com_type_<%= $index+1 %>" ng-model="data['retailer_commission_type']" class="form-control rt-type-inp hide-this" readonly>
                                   <input type="text" class="form-control" ng-model="data['retailer_commission']" required>
                               </td>

                               <td class="text-center">
                                   <div class="btn-group">
                                       <button type="button" class="btn btn-sm btn-outline-info" ng-click="saveSettings(data)" ng-if="data.pkg_commission_id" title="Edit" data-pk-cm-details-id="<%= data.pkg_commission_id %>">
                                           <i class="fa fa-edit"></i> Edit
                                       </button>
                                       <button type="button" class="btn btn-sm btn-outline-primary" ng-click="saveSettings(data)" ng-if="!data.pkg_commission_id" title="Save" data-pk-cm-details-id="0">
                                           <i class="fa fa-save"></i> Save
                                       </button>
                                   </div>
                               </td>
                           </tr>
                       </tbody>
                   </table>
               </div>
               <!--AADHAR PAY ENDS-->
                @else
                <!-- All Table starts-->
                <div class="table-responsive" id="pack-comm-dtls-div-3">
                    <table id="pack-comm-dtls-table-3" class="table table-striped table-sm border">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Operator Name</th>
                                <th>API Charge/Commission</th>
                                <th class="text-center">Admin</th>
                                <th class="text-center">MD</th>
                                <th class="text-center">Dis</th>
                                <th class="text-center">RT</th>
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
                                <th class="text-center">Admin</th>
                                <th class="text-center">MD</th>
                                <th class="text-center">Dis</th>
                                <th class="text-center">RT</th>
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

<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<!--Datable plugins -->
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('template_assets/dist/js/pages/datatable/datatable-basic.init.js') }}"></script>

<!-- Datatable plugin ends -->
<script src="template_assets\other\js\bootstrap-toggle.min.js"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script src="{{ asset('dist\setting\js\packCommDtlsFormValidation.js') }}"></script>
<script src="{{ asset('template_assets\other\js\angular.min.js')}}"></script>
<script src="{{ asset('dist\setting\js\packCommDtls.js') }}"></script>
@endsection
