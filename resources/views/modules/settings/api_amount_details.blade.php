@extends('layouts.full')

@section('page_content')

<section>
<!-- This page plugin CSS -->
<link href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css"
        href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('dist\setting\css\api_setting_list.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('template_assets\other\css\bootstrap-toggle.min.css') }}">

<!-- API Amount Details table starts -->
<div class="row">
    <div class="col-12">
        <div class="material-card card">
            <div class="card-body">
                <h4 class="card-title">Transfer Recharge API By Recharge Amount</h4>
                <br>
                <div class="row card-title">
                    <div class="col-12 text-right">
                        <button type="button" class="btn btn-primary btn-md add-service-btn" data-toggle="modal" data-target="#apiAmountDetailsAddModal"><i class="fa fa-plus"></i> Add Amount</button>
                    </div>
                </div>
                <br>
                <div class="table-responsive">
                    <table id="api-amount-dtls-table" class="table table-striped table-sm border is-data-table">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Add Date</th>
                                <th>API Name</th>
                                <th>Operator Name</th>
                                <th>Amount</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($apiAmountDetails as $index => $data)
                                <tr>
                                    <td>{{ $index+1 }}</td>
                                    <td>{{ $data->add_date }}</td>
                                    <td>
                                        @foreach($apiSettings as $setting)
                                            @if($setting->api_id== $data->api_id)
                                                {{$setting->api_name}}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach($operators as $operator)
                                            @if($operator->operator_id== $data->operator_id)
                                                {{$operator->operator_name}}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>{{ $data->amount }}</td>
                                    <td class="text-center">
                                        @if($data->activated_status == Config::get('constants.ACTIVE'))
                                            <input checked id="status-btn_{{ $index+1 }}" class="status-btn" type="checkbox" data-id="{{ $data->id }}" data-on="Active" data-off="Inactive" data-onstyle="success" data-toggle="toggle" data-width="90" data-style="ios" data-style="slow">                                          
                                        @else
                                            <input id="status-btn_{{ $index+1 }}" class="status-btn" type="checkbox" data-id="{{ $data->id }}" data-on="Active" data-off="Inactive" data-onstyle="success" data-toggle="toggle" data-width="90" data-style="ios" data-style="slow">                                          
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-primary edit-btn" title="Edit" value="{{ $data->id }}">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="{{ $data->id }}" title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                                <th>Sr No</th>
                                <th>Add Date</th>
                                <th>API Name</th>
                                <th>Operator Name</th>
                                <th>Amount</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- API Amount Details table ends -->

<!-- API Amount Details Add modal starts -->
<div class="modal" id="apiAmountDetailsAddModal" tabindex="-1" role="dialog" aria-labelledby="apiAmountDetailsAddModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel1"><span class="modal-action-name"></span> API Amount Details</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form method="post" action="{{ route('api_amount_details') }}" id="addApiAmountDetailsForm">
            @csrf
                <div class="modal-body">
                        <input type="hidden" name="id" id="id" value="0">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="operator_id">Operator</label>
                                    <select name="operator_id" id="operator_id" class="form-control">
                                        <option disabled selected>Select</option>
                                        @foreach($operators as $operator)
                                            <option value="{{ $operator->operator_id }}"> {{ $operator->operator_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="api_id">API Name</label>
                                    <select name="api_id" id="api_id" class="form-control">
                                        <option disabled selected>Select</option>
                                        @foreach($apiSettings as $setting)
                                            <option value="{{ $setting->api_id }}"> {{ $setting->api_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="text" class="form-control" id="amount" name="amount">
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
<!-- API Amount Details modal ends -->

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
<script src="{{ asset('dist\setting\js\apiAmountDetailsFormvalidation.js') }}"></script>
<script src="{{ asset('dist\setting\js\apiAmountDetails.js') }}"></script>
@endsection
