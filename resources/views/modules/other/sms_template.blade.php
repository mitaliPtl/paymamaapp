@extends('layouts.full')

@section('page_content')

<section>
<!-- This page plugin CSS -->
<link href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css"
        href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('template_assets\other\css\bootstrap-toggle.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('dist\other\css\sms_template.css') }}">

<!-- Sms Template table starts -->
<div class="row">
    <div class="col-12">
        <div class="material-card card">
            <div class="card-body">
                <h4 class="card-title">SMS Template</h4>
                <br>
                <div class="row card-title">
                    <div class="col-12 text-right">
                        <button type="button" class="btn btn-primary btn-md add-service-btn" data-toggle="modal" data-target="#smsTemplateAddModal"><i class="fa fa-plus"></i> Add SMS Template</button>
                    </div>
                </div>
                <br>
                    <table id="sms-template-table" class="table table-striped table-sm border is-data-table">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>SMS</th>
                                <th>Template</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($smsTemplates as $index => $template)
                                <tr>
                                    <td>{{ $index+1 }}</td>
                                    <td>{{ $template->sms_name }}</td>
                                    <td>{{ $template->template }}</td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-primary edit-btn" title="Edit" value="{{ $template->id }}">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="{{ $template->id }}" title="Delete">
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
                                <th>SMS</th>
                                <th>Template</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Sms Template table ends -->

<!-- Sms template Add modal starts -->
<div class="modal" id="smsTemplateAddModal" tabindex="-1" role="dialog" aria-labelledby="smsTemplateAddModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel1"><span class="modal-action-name"></span> SMS Template</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form method="post" action="{{ route('sms_template') }}" id="addSmsTemplateForm">
            @csrf
                <div class="modal-body">
                        <input type="hidden" name="id" id="id">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="sms_name">SMS </label>
                                    <input type="text" class="form-control" id="sms_name" name="sms_name">
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="alias">Alias</label>
                                    <select type="text" class="form-control" id="alias" name="alias">
                                        <option disabled selected>Select</option>
                                        @foreach($smsTemplateAlias  as $key => $value)
                                            <option value="{{ $value['name'] }}" data-allowed-tags="{{ json_encode($value['allowed_tags']) }}"> {{ $key }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>                        
                        
                        <div class="form-group">
                            <label for="template">Template <i class="fa fa-info-circle  fa-spin text-warning tags-info" ></i></label>
                            <textarea type="text" class="form-control" id="template" name="template"></textarea>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary submit-btn">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Sms Template Add modal ends -->

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
<script src="{{ asset('dist\other\js\smsTemplateFormValidation.js') }}"></script>
<script src="{{ asset('dist\other\js\smsTemplate.js') }}"></script>
@endsection
