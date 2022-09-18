@extends('layouts.full')

@section('page_content')

<section>
<!-- This page plugin CSS -->
<link href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css"
        href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('template_assets\other\css\bootstrap-toggle.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('dist\application\css\slidder_banner.css') }}">
<input type="hidden" id="website_url" value="{{ Config::get('constants.WEBSITE_BASE_URL')}}">
<!-- Slidder Banner table starts -->
<div class="row">
    <div class="col-12">
        <div class="material-card card">
            <div class="card-body">
                <h4 class="card-title">Slidder Banners</h4>
                <br>
                <div class="row card-title">
                    <div class="col-12 text-right">
                        <button type="button" class="btn btn-primary btn-md add-service-btn" id="load-add-modal"><i class="fa fa-plus"></i> Add New</button>
                    </div>
                </div>
                <br>
                    <table id="slidder-banner-table" class="table table-striped table-sm border is-data-table">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Role Name</th>
                                <th>Platform</th>
                                <th>Location</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($slidderBanners as $index => $banner)
                                <tr>
                                    <td>{{ $index+1 }}</td>
                                    <td>{{ $banner->role_id ? \App\Role::getNameById($banner->role_id) : '' }}</td>
                                    <td>{{ $banner->platform }}</td>
                                    <td>{{ $banner->location }}</td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-primary edit-btn" title="Edit" value="{{ $banner->id }}">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-warning redirect-btn" data-id="{{ $banner->id }}" value="{{ $banner->id }}"  title="Link">
                                                <i class="fa fa-link"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="{{ $banner->id }}" title="Delete">
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
                                <th>Role Name</th>
                                <th>Platform</th>
                                <th>Location</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Slidder Banner table ends -->

<!-- Slidder Banner Add modal starts -->
<div class="modal" id="slidderBannerAddModal" tabindex="-1" role="dialog" aria-labelledby="serviceTypeAddModal" ng-app="myApp" ng-controller="slidderBannerCtrl">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel1"><span class="modal-action-name"></span> Slidder Banner</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form method="post" action="{{ route('create_slidder_banner') }}" id="addSlidderBannerForm">
            @csrf
                <div class="modal-body">
                        <input type="hidden" name="slidder_banner_id" id="slidder_banner_id">
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="service_name">User Type</label>
                                    <select class="form-control custom-select" id="role_id" name="role_id">
                                        <option selected disabled>Select</option>
                                        @foreach($allRoles as $role)
                                            <option value="{{ $role->roleId}}" data-alias="{{ $role->alias }}">{{ $role->role}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="form-group">
                                    <label for="alias">Platform</label>
                                    <select name="platform" id="platform" class="form-control">
                                        <option value="" selected disabled>Select</option>
                                        <option value="WEBSITE">Website</option>
                                        <option value="ANDROID_APP">Android App</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="form-group">
                                    <label for="alias">Location</label>
                                    <input type="text" class="form-control" name="location" id="location" placeholder="Enter Location">
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                                <div class="form-group">
                                    <label for="redirect_link">Redirect Link</label>
                                    <input type="text" id="redirect_link" name="redirect_link"  class="form-control " placeholder="Enter Redirect Link" >                                   

                                </div>
                        </div>
                        <div class="row">z
                            <div class="col-12 text-center mb-2">
                                <button type="button" class="btn btn-md btn-cyan" id="banner-file-up-btn">Upload File</button>
                                <input type="text" class="hide-width" name="image_file_ids"  id="image_file_ids">
                                <div>
                                    <label id="image_file_ids-error" style="color:#ff5050" class="error hide-this" for="image_file_ids">This field is required</label>
                                </div>
                            </div>

                            <div class="col-12 text-center" ng-if="bannerFileList.length">
                                <ng-template ng-if="bannerFileList.length" ng-repeat="filePath in bannerFileList track by $index">
                                    <img ng-if="filePath" class="mb-2" src="{{ ('<%= filePath %>') }}" style="width:80px;height:70px;border:1px solid #80808029">
                                </ng-template>
                            </div>
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
<!-- Slidder Banner Add modal ends -->

<!-- Slidder Banner Add modal starts -->
<div class="modal" id="redirectBannerModal" tabindex="-1" role="dialog" aria-labelledby="serviceTypeAddModal" ng-app="myApp" ng-controller="slidderBannerCtrl">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel1"><span class="modal-action-name"></span> Slidder Banner</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form method="post" action="{{ route('redirect_banner') }}" id="redirectBannerForm">
            @csrf
                <div class="modal-body">
                        <input type="hidden" name="redirect_banner_id" id="redirect_banner_id">
                        <input type="text" class="hide-width" name="re_image_file_ids"  id="re_image_file_ids">
                     
                            <div class="col-12 text-center" id="redirect_links">
                                  <div class="row">
                                        <div class="col-4">
                                            <img  class="mb-2" src="" style="width:80px;height:70px;border:1px solid #80808029">
                                        </div>
                                        <div class="col-8">
                                            <input type="text" id="redirect_link" name="redirect_link[]"  class="form-control " placeholder="Enter Redirect Link" >                                   

                                        </div>
                                    </div>
                               
                            </div>
                                               
                        
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary submit-btn">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Slidder Banner Add modal ends -->

<!-- File Upload modal starts -->
<div class="modal" id="bannerFileUploadMdl" role="dialog">
    <div class="modal-dialog modal-sm" role="document" style="margin-left:530px">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel1">Upload File</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <input type="hidden" id="btn-data">
            <form id="bannerFileUploadForm" enctype="multipart/form-data">
            @csrf
                <div class="custom-file">
                    <input type="file" name="file" class="custom-file-input" id="choosebannerFile" required>
                    <label class="custom-file-label" for="chooseFile">Select file</label>
                </div>

                <button type="submit" id="banner-file-submit-btn" class="btn btn-info btn-block mt-4">
                    Upload File
                </button>
            </form>
        </div>
    </div>
</div>
<!-- File uplaod modal ends -->

</section>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<!--Datable plugins -->
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('template_assets/dist/js/pages/datatable/datatable-basic.init.js') }}"></script>
<!-- Datatable plugin ends -->
<script src="template_assets\other\js\bootstrap-toggle.min.js"></script>
<script src="template_assets\other\js\sweetalert.min.js"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script src="{{ asset('template_assets\other\js\angular.min.js') }}"></script>
<script src="{{ asset('dist\application\js\slidderBannerFormValidation.js') }}"></script>
<script src="{{ asset('dist\application\js\slidderBanner.js') }}"></script>
@endsection
