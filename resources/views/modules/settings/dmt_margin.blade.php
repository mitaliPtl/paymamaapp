@extends('layouts.full')

@section('page_content')

<section >
<!-- This page plugin CSS -->
<link href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css"
        href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('template_assets\other\css\bootstrap-toggle.min.css') }}">




<input type="hidden" id="moneyTransferAlias" value="{{ Config::get('constants.SERVICE_TYPE_ALIAS.MONEY_TRANSFER') }}">
 
<div class="row">
    <div class="col-12">
        <div class="material-card card">
            <div class="card-body">
                <h4 class="card-title">DTM Margin</h4>
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
                   
                    <div class="col-6">
                        <form method="post" action="{{ route('dmt_margin_filter') }}" id="filterForm">
                        @csrf
                            <div class="row">
                               
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
                                                @if( $sType->alias  == Config::get('constants.SERVICE_TYPE_ALIAS.MONEY_TRANSFER') || $sType->alias  == 'upi_transfer') )
                                                    @if($sType->service_id == $request['service_id'])
                                                        <option value="{{ $sType->service_id }}" data-service_alias="{{ $sType->alias }}" selected> {{ $sType->service_name }}</option>
                                                    @else
                                                        <option value="{{ $sType->service_id }}" data-service_alias="{{ $sType->alias }}"> {{ $sType->service_name }}</option>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </select>
                                        <input type="hidden" id="serviceAlias" value="{{ $serviceAlias }}">
                                    </div>
                                </div>

                                @if($serviceAlias == Config::get('constants.SERVICE_TYPE_ALIAS.MONEY_TRANSFER') )
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
                <form action="{{ route('update_margin') }}" method="post">
                    @csrf
                    <input type="hidden" name="margin_pkg_id" id="margin_pkg_id" value= "@if(isset($request['pkg_id']) && $request['pkg_id']) {{ $request['pkg_id'] }} @endif">
                    <input type="hidden" name="margin_service_id" id="margin_service_id" value= "@if(isset($request['pkg_id']) && $request['service_id']) {{ $request['service_id'] }} @endif">
                    <input type="hidden" name="margin_op_id" id="margin_op_id" value= "@if(isset($request['operator_id']) && $request['operator_id']) {{ $request['operator_id'] }} @endif">
                    @if(count($dtmargin)>0)
                        @foreach($dtmargin as $dtmargin_key => $dtmargin_value)
                            <input type="hidden" name="user_{{ $dtmargin_value->role_id }}" id="user_{{ $dtmargin_value->role_id }}" value="{{ $dtmargin_value->id }}">
                        @endforeach
                    @endif
                    <div class="row">
                        @if(count($dtmargin)>0)
                            @foreach($dtmargin as $dtmargin_key => $dtmargin_value)
                                @if($dtmargin_value->role_id == Config::get('constants.RETAILER'))
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <h5>Retailer</h5>
                                        </div>
                                        <div class="col-md-8" id="retailer_inputs">
                                        @foreach(json_decode($dtmargin_value->margin, true) as $mar_key => $mar_val)
                                            
                                                <div class="form-group">
                                                    <input type="text" id="" name="r_margin[]"  class="form-control " placeholder="" value="{{ $mar_val }}">                                   
                                                </div>
                                            
                                            @endforeach
                                            </div>
                                        <div class="col-md-1">
                                            <i class="btn btn-primary" id="add_retailer_input"><i class="fa fa-plus"></i></i>
                                        </div>
                                    </div>
                                </div>
                                @elseif($dtmargin_value->role_id == Config::get('constants.DISTRIBUTOR'))
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <h5>Distributor</h5>
                                        </div>
                                        <div class="col-md-8" id="retailer_inputs">
                                            <div class="form-group">
                                                <input type="text" id="" name="d_margin"  class="form-control " placeholder="" value="{{ $dtmargin_value->margin }}">                                   
                                            </div>
                                        </div>
                                    
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        @else
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-2">
                                    <h5>Retailer</h5>
                                </div>
                                <div class="col-md-8" id="retailer_inputs">
                                                <div class="form-group">
                                                    <input type="text" id="" name="r_margin[]"  class="form-control " placeholder="" value="">                                   
                                                </div>
                                </div>
                                <div class="col-md-1">
                                    <i class="btn btn-primary" id="add_retailer_input"><i class="fa fa-plus"></i></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-2">
                                    <h5>Distributor</h5>
                                </div>
                                <div class="col-md-8" id="retailer_inputs">
                                                <div class="form-group">
                                                    <input type="text" id="" name="d_margin"  class="form-control " placeholder="" value="">                                   
                                                </div>
                                </div>
                            
                            </div>
                        </div>
                        @endif


                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-info">Update</button>
                        
                    </div>
                </form>
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
<!-- <script src="{{ asset('dist\setting\js\packCommDtlsFormValidation.js') }}"></script> -->

<script src="{{ asset('dist\setting\js\dmtMargin.js') }}"></script>
@endsection
