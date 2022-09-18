@extends('layouts.full')

@section('page_content')

<!-- This page plugin CSS -->
<link href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css"
        href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('template_assets\other\css\flatpickr.min.css') }}">
<link rel="stylesheet" href="{{ asset('dist\reports\css\reports.css') }}">

<section>
        <!-- Category Reports table starts -->
<div class="row">
    <div class="col-12">
        <div class="material-card card">
            <div class="card-body">
                <h4 class="card-title"> Category </h4>
                <div class="row">
                    <div class="col-12 ">
                       
                        <!-- <a href="{{$_SERVER['REQUEST_URI']}}">
                            <button type="button" title="Refresh" class="btn btn-outline-primary btn-circle btn-md mr-2"><i class="mdi mdi-rotate-right"></i></button>
                        </a> -->
                        
                        <!-- <button type="button" title="ADD Template" class="btn btn-outline-info btn-md add-template-btn" data-toggle="collapse" data-target="#add-template"><i class="fa fa-pluse"></i>Add Template</button> -->
                        <!-- <button type="button" title="Export" class="btn btn-outline-dark btn-circle btn-md mr-3" data-toggle="collapse" data-target="#exportBox"><i class="fa fa-download"></i></button> -->
                    </div>
                   
                    
                </div>
                <div class="row card-title">
                    <div class="col-12 text-right">
                        <button type="button" title="ADD Category" class="btn btn-primary btn-md add-category-btn" data-toggle="collapse" data-target="#add-category"><i class="fa fa-plus"></i> Add Category </button>
                    </div>
                </div>
                <br>
                <div class="table-responsive">
                    <input type="hidden" name="categories" id ="categories" value="{{ json_encode($categories) }}">
                    <table id="recharge-report-table" class="table table-striped table-sm border is-data-table">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Category </th>
                                <!-- <th>Created</th> -->
                                <th>Action </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $index => $category)
                                <tr>
                                    <td>{{ $index+1 }}</td>
                                    <td >{{ $category['category'] }}</td>
                                    <!-- <td >{{ $category['category_created_on'] }}</td> -->
                                   
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info edit-category-btn" title="Edit" value="{{ $index }}">
                                                    <i class="fa fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-info delete-category-btn" title="Delete" value="{{ $category['category_id'] }}">
                                                    <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                    
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                            <th>Sr No</th>
                                <th>Category </th>
                                <!-- <th>Created</th> -->
                                <th>Action </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Category Reports table ends -->

    <!-- add modal starts -->
    <div class="modal" id="add-category" role="dialog">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel1" style="margin-left:60px">Add Category</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="addCategoryForm" action="{{ route('add_categoryOfficeExpenses') }}" method="post">
                @csrf
                    <!-- <input type="hidden" name="change_complaint_id" id="change_complaint_id"> -->
                    <div class="col-12">
                        <label for="default_time">Enter Categroy</label>
                        <br>
                        <div class="form-group">
                            <textarea type="text" class="form-control" id="category_text" name="category_text" required></textarea>
                        </div>
                    </div>
                   

                    <button type="submit" id="add-category-btn" class="btn btn-info btn-block mt-4">
                        ADD
                    </button>
                </form>
            </div>
        </div>
    </div>
    <!-- add  modal ends -->

    <!-- edit modal starts -->
    <div class="modal" id="edit-category" role="dialog">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel1" style="margin-left:60px">Edit Category</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="editCategoryForm" action="{{ route('edit_categoryOfficeExpenses') }}" method="post">
                @csrf
                    <input type="hidden" name="edit_category_id" id="edit_category_id">
                    <div class="col-12">
                        <label for="default_time">Enter Categroy</label>
                        <br>
                        <div class="form-group">
                            <textarea type="text" class="form-control" id="edit_category_text" name="edit_category_text" required></textarea>
                        </div>
                    </div>
                   

                    <button type="submit" id="edit-category-btn" class="btn btn-info btn-block mt-4">
                        UPDATE
                    </button>
                </form>
            </div>
        </div>
    </div>
    <!-- edit  modal ends -->

    <!-- Delete modal starts -->
    <div class="modal" id="delete-category" role="dialog">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel1" style="margin-left:60px">Delete Category</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="deleteCategoryForm" action="{{ route('delete_categoryOfficeExpenses') }}" method="post">
                @csrf
                    <input type="hidden" name="delete_category_id" id="delete_category_id">
                    <div class="col-12">
                        <label for="default_time">Are you sure want to delete Category ?</label>
                        <br>
                        <!-- <div class="form-group">
                            <textarea type="text" class="form-control" id="delete_category_text" name="edit_category_text" required></textarea>
                        </div> -->
                    </div>
                   

                    <button type="submit" id="delete-category-btn" class="btn btn-info btn-block mt-4">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
    <!-- Delete  modal ends -->


</section>

<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<!--Datable plugins -->
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('template_assets/dist/js/pages/datatable/datatable-basic.init.js') }}"></script>
<!-- Datatable plugin ends -->
<script src="{{ asset('template_assets\other\js\flatpickr') }}"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<!-- <script src="{{ asset('dist\reports\js\rechargeReport.js') }}"></script> -->
<script src="{{ asset('dist\officeexpenses\js\category.js') }}"></script>

@endsection