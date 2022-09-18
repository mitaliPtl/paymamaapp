@extends('layouts.full')

@section('page_content')

<section>
<!-- This page plugin CSS -->
<link href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css"
        href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('template_assets\other\css\bootstrap-toggle.min.css') }}">
        <!-- API Setting table starts -->
<div class="row">
    <div class="col-12">
        <div class="material-card card">
            <div class="card-body">
                <h4 class="card-title">API Operator Details</h4>
                <br>
                <div class="row card-title">
                   
                    <div class="col-6"></div>
                    <div class="col-6">
                        <form method="post" action="{{ route('operator_dtls') }}" id="filterForm">
                        @csrf
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="api_id">API Setting</label>
                                        <select name="api_id" id="api_id" class="form-control">
                                            <option disabled selected>Select</option>
                                            @foreach($apiSettings as $setting)
                                                @if($setting->api_id == $request->api_id)
                                                <option value="{{ $setting->api_id }}" selected> {{ $setting->api_name }}</option>
                                                @else
                                                <option value="{{ $setting->api_id }}"> {{ $setting->api_name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="service_id">Service type</label>
                                        <select name="service_id" id="service_id" class="form-control">
                                            <option disabled selected>Select</option>
                                            @foreach($servicesTypes as $sType)
                                                @if($sType->service_id == $request->service_id)
                                                    <option value="{{ $sType->service_id }}" selected> {{ $sType->service_name }}</option>
                                                @else
                                                    <option value="{{ $sType->service_id }}"> {{ $sType->service_name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" id="filter-submit-btn" style="display:none">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="operator-dtls-table" class="table table-striped table-sm border is-data-table">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Operator Name</th>
                                <th>Operator Code</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($operators as $index => $operator)      
                                <?php
                                    $optDetailsOptId = 0;
                                    $optDetailsOptCode = '';
                                    if(count($operatorDetails)){
                                        for($optDtInd = 0; $optDtInd < count($operatorDetails); $optDtInd++ ){
                                            if($operator->operator_id == $operatorDetails[$optDtInd]['operator_id']){
                                                $optDetailsOptId = $operatorDetails[$optDtInd]['api_operator_id'];
                                                $optDetailsOptCode = $operatorDetails[$optDtInd]['operator_code'];
                                            }
                                        }
                                    }
                                
                                ?>                

                                <tr>
                                    <td>{{ $index+1 }}</td>
                                    <td>{{ $operator->operator_name }}</td>
                                    <td style="width:50%">
                                        <div class="form-group" style="width:80%">
                                            
                                            @if($optDetailsOptCode != '')
                                                <input type="text" class="form-control" id="operator_code_{{$operator->operator_id}}" name="operator_code" value="{{ $optDetailsOptCode }}">
                                            @else
                                                <input type="text" class="form-control" id="operator_code_{{$operator->operator_id}}" name="operator_code">
                                            @endif

                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">

                                        @if($optDetailsOptCode != '')
                                            <button type="button" class="btn btn-sm btn-outline-info save-op-dtls-btn" title="Edit" data-operator-id="{{$operator->operator_id}}" data-op-details-id="{{ $optDetailsOptId }}">
                                                <i class="fa fa-edit"></i> Edit
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-sm btn-outline-primary save-op-dtls-btn" title="Save" data-operator-id="{{$operator->operator_id}}" data-op-details-id="0">
                                                <i class="fa fa-save"></i> Save
                                            </button>
                                        @endif
                                        
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Sr No</th>
                                <th>Operator Name</th>
                                <th>Operator Code</th>
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

</section>

<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<!--Datable plugins -->
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('template_assets/dist/js/pages/datatable/datatable-basic.init.js') }}"></script>

<!-- Datatable plugin ends -->
<script src="template_assets\other\js\bootstrap-toggle.min.js"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script src="{{ asset('dist\operator\js\operatorDtlsFormValidation.js') }}"></script>
<script src="{{ asset('dist\operator\js\operatorDtls.js') }}"></script>
@endsection
