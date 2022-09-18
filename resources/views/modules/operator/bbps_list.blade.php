@extends('layouts.full')

@section('page_content')

<section>
<!-- This page plugin CSS -->
<link href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css"
        href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('template_assets\other\css\bootstrap-toggle.min.css') }}">

<!-- <link rel="stylesheet" type="text/css" href="{{--asset('template_assets/assets/libs/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css') --}}"> -->
<!-- Biller table starts -->
<div class="row">
    <div class="col-12">
        <div class="material-card card">
            <div class="card-body">
                <h4 class="card-title">BBPS Management</h4>
                <br>
                <div class="row card-title">
                    <div class="col-12 text-right mb-2">
                           
                        <a href="{{$_SERVER['REQUEST_URI']}}">
                            <button type="button" title="Refresh" class="btn btn-outline-primary btn-circle btn-md mr-2"><i class="mdi mdi-rotate-right"></i></button>
                        </a>

                        <button type="button" title="Apply Filter" class="btn btn-outline-info btn-circle btn-md mr-2" data-toggle="collapse" data-target="#filterBox"><i class="fa fa-filter"></i></button>
                        <!-- <button type="button" title="Export" class="btn btn-outline-dark btn-circle btn-md mr-3" data-toggle="collapse" data-target="#exportBox"><i class="fa fa-download"></i></button> -->
                    </div>
                    
                    <div class="col-11">
                    <div class="collapse show" id="filterBox">
                    @if(isset($filtersList) && $filtersList)
                        <form action="{{ $_SERVER['REQUEST_URI'] }}" method="post">
                        @csrf
                            <input type="hidden" id="is_export" name="is_export" value="0">
                            <div class="row">

                            @foreach($filtersList as $i => $filter)
                                <div class="filter-elements">

                                     
                                        @if($filter['name'] == "filter_operator_name")
                                            <select name="{{ $filter['name'] }}" id="{{ $filter['id'] }}" class="form-control">
                                                <option value="" selected>{{ $filter['label'] }}</option>
                                                @foreach($operators as $operator)
                                                    @if($operator->billerCategory == $request->filter_operator_name)
                                                        <option value="{{ $operator->billerCategory }}" selected> {{ $operator->billerCategory }}</option>
                                                    @else
                                                        <option value="{{ $operator->billerCategory }}"> {{ $operator->billerCategory }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        @endif


                                       

                    
                                </div>
                                @endforeach

                                <div class="filter-elements">
                                    <button class="btn btn-md btn-outline-primary" id="filter-submit-btn" type="submit"><i class="fa fa-filter"></i> Filter</button>
                                </div>
                            </div>
                        </form>
                    @endif
                    </div>
                    </div>
                    <div class="col-12 text-right">
                        <button type="button" class="btn btn-primary btn-md add-bbps-btn" data-toggle="modal" data-target="#addBbpsBiller"><i class="fa fa-plus"></i> Add Biller</button>
                    </div>
                </div>
                <br>
                <div class="table-responsive">
                    <table id="biller-table" class="table table-striped table-sm table-sm table-bordered is-data-table">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Biller Name</th>
                                <th>Biller ID</th>
                                <th>Biller Category </th>
                                <th>Icon</th>
                                <th>Customize Input Param</th>
                                <th>Customize</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                                // print_r(json_decode($bbps_list, true));
                        ?>
                            @foreach($bbps_list as $index => $value)
                                <tr>
                                
                                        <td>{{ $index+1 }}</td>
                                        <td>{{ $value['billerName'] }}</td>
                                        <td>{{ $value['billerId'] }}</td>
                                        <td>{{ $value['billerCategory'] }}</td> 
                                        <td>
                                            {{-- $valuebillerIcon --}}
                                            @if( $value['billerIcon'] )
                                            <img src="{{ $value['billerIcon'] }}" alt="{{ $value['billerName'] }}" style="width:100px;"> 
                                            @endif
                                        </td>
                                        <td>{{ $value['billercustomizeInputParams'] }}</td> 
                                        <td>{{ $value['billercustomize'] }}</td> 
                                        <td class="text-center">
                                            <button type="button" id="form-file-up-btn" class="btn btn-sm btn-warning upload-btn" data-id="{{ $value['id'] }}" title="Upload" value="{{ $value['id'] }}">
                                                <i class="fa fa-upload"></i>
                                              
                                            </button>
                                            <button type="button" id="custom_param" class="btn btn-sm btn-primary " title="Update" onclick="customParams( '{{ $value['billercustomizeInputParams'] }}' , '{{ $value['billercustomize'] }}', '{{ $value['id'] }}' )">
                                                <i class="fa fa-edit"></i>
                                              
                                            </button>
                                    </td>   
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Sr No</th>
                                <th>Biller Name</th>
                                <th>Biller ID</th>
                                <th>Biller Category </th>
                                <th>Icon</th>
                                <th>Customize Input Param</th>
                                <th>Customize</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Biller table ends -->
<form id="uploadBillerForm" method="post" action="{{  route('upload_biller_image') }}">
@csrf
        <input type="hidden" name="biller_id" id="biller_id">
        <input type="hidden" name="uploaded_file_id" id="uploaded_file_id">
</form>

    <!-- File Upload modal starts -->
    <div class="modal" id="fileUploadModal" role="dialog">
            <div class="modal-dialog modal-sm" role="document" style="margin-left:525px">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLabel1"><span class="modal-action-name"></span>Upload File</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <form id="fileUploadForm" enctype="multipart/form-data">
                    @csrf
                        <div class="custom-file">
                            <input type="file" name="file" class="custom-file-input" id="chooseFile" required>
                            <label class="custom-file-label" for="chooseFile">Select file</label>
                        </div>
                        <input type="hidden" name="selected_biller" id="selected_biller">
                        <button type="submit" id="file-upload-btn" class="btn btn-info btn-block mt-4">
                            Upload File
                        </button>
                    </form>
                </div>
            </div>
    </div>
    <!-- File uplaod modal ends -->

    <!-- add modal starts -->
    <div class="modal" id="addBbpsBiller" role="dialog">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel1" style="margin-left:60px">Add Biller</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="addBbpsBillerForm" action="{{ route('add_bbps_biller') }}" method="post">
                @csrf
                    <!-- <input type="hidden" name="change_complaint_id" id="change_complaint_id"> -->
                   
                    <div class="col-12">
                        <label for="default_time">Biller ID </label>
                        <br>
                        <div class="form-group">
                            <textarea type="text" class="form-control" id="newbiller_id" name="newbiller_id" required></textarea>
                        </div>
                    </div>

                    <button type="submit" id="add-biller-id-btn" class="btn btn-info btn-block mt-4">
                        ADD
                    </button>
                </form>
            </div>
        </div>
    </div>
    <!-- add  modal ends -->

     <!-- edit custom param modal starts -->
    <div class="modal" id="updateCustomParamModel" role="dialog">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1" style="margin-left:60px"> Customize Input Params</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="updateCustomParamForm" action="{{ route('update_custom_param') }}" method="post">
                @csrf
                    <input type="hidden" name="input_params_id" id="input_params_id">
                   
                    <div class="col-12">
                        <label for="default_time">Input Params </label>
                        <br>
                        <div class="form-group">
                            <textarea type="text" class="form-control" id="input_params" name="input_params" ></textarea>
                        </div>
                    </div>

                    <div class="col-12">
                        <input class="custom_param_status" name="custom_param_status" id="custom_param_status" value="Yes" type="checkbox" data-size="mini" />
                        <label for="input_params"> YES</label>
                    </div>

                    <button type="submit" id="update-custom-input-btn" class="btn btn-info btn-block mt-4">
                        UPDATE
                    </button>
                </form>
            </div>
        </div>
    </div>
    <!-- edit custom param  modal ends -->


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
<script src="{{ asset('dist\operator\js\operatorFormValidation.js') }}"></script>
<script src="{{ asset('dist\operator\js\bill_management.js') }}"></script>

@endsection
