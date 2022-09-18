@extends('layouts.full')

@section('page_content')

<section>
<!-- This page plugin CSS -->
<link href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css"
        href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
<link href="{{ asset('template_assets\other\css\select2.min.css') }}" type="text/css" rel="stylesheet">
<!-- Operator Settings table starts -->
<div class="row">
    <div class="col-12">
        <div class="material-card card">
            <div class="card-body">
                <h4 class="card-title">Transfer Recharge API</h4>
                <br>
                <div class="row card-title">
                    <div class="col-12 text-right">
                        <button type="button" class="btn btn-primary btn-md add-op-settings-btn" data-toggle="modal" data-target="#operatorSettingsAddModal"><i class="fa fa-edit"></i> Update</button>
                    </div>
                </div>
                <br>
                <div class="table-responsive">
                    <table id="operator-settings-table" class="table table-striped table-sm border is-data-table">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Operator Name</th>
                                <th>Service Type</th>
                                <th>Operator Code</th>
                                <th>API Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($operatorSettings as $index => $operatorSetting)
                                <tr>
                                    <td>{{ $index+1 }}</td>
                                    <!-- <td>{{ $operatorSetting->operator_id }}</td> -->
                                    <td>{{ $operatorSetting->operator_name }}</td>
                                    <td>
                                        @foreach($servicesTypes as $stype)
                                                @if($stype->service_id == $operatorSetting->service_id)
                                                        {{ $stype->service_name }}
                                                @endif
                                        @endforeach                                            
                                    </td>
                                    <td>{{ $operatorSetting->operator_code }}</td>
                                    <td>
                                        @foreach($apiSettings as $setting)
                                                @if($setting->api_id == $operatorSetting->default_api_id)
                                                        {{ $setting->api_name }}
                                                @endif
                                        @endforeach  
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Sr No</th>
                                <th>Operator Name</th>
                                <th>Service Type</th>
                                <th>Operator Code</th>
                                <th>API Name</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Operator Settings table ends -->

<!-- Operator Setting Add modal starts -->
<div class="modal" id="operatorSettingsAddModal" tabindex="-1" role="dialog" aria-labelledby="operatorSettingsAddModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel1">Update Transfer Recharge API</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form method="post" action="{{ route('operator_settings') }}" id="addOperatorSettingsForm">
            @csrf
                <div class="modal-body">
                        <input type="hidden" id="operator_settings_id" name="operator_settings_id" value="0">
                        <div class="form-group">
                            <label for="operator_id">Operator Name</label><br>
                            <select required name="operator_id[]" id="operator_id" class="form-control" multiple="multiple" style="width: 100%">
                             @foreach($operatorSettings as $operator)
                                <option value="{{ $operator->operator_id }}"> {{ $operator->operator_name }} :: {{isset($operator->servicesType) ? $operator->servicesType->service_name :''}}</option>
                             @endforeach
                             </select>
                             <br>
                             <label id="operator_id-error" class="error" for="operator_id" style="display:none;color:#ff5050">This field is required</label>
                        </div>

                        <div class="form-group">
                                <label for="default_api_id">API Name</label>
                                <select name="default_api_id" id="default_api_id" class="form-control">
                                <option disabled selected>Select</option>
                                @foreach($apiSettings as $setting)
                                        <option value="{{ $setting->api_id }}"> {{ $setting->api_name }}</option>
                                @endforeach
                                </select>
                        </div>
                        
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary form-submit-btn">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Operator Setting Add modal ends -->

</section>

<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<!--Datable plugins -->
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('template_assets/dist/js/pages/datatable/datatable-basic.init.js') }}"></script>

<!-- Datatable plugin ends -->
<script src="{{ asset('template_assets\other\js\select2.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script src="{{ asset('dist\operator\js\operatorSettingsValidation.js') }}"></script>
<script src="{{ asset('dist\operator\js\operatorSettings.js') }}"></script>
@endsection
