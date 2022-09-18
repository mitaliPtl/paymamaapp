@extends('layouts.full')

@section('page_content')

<section>
<!-- This page plugin CSS -->
<link href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css"
        href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('template_assets\other\css\bootstrap-toggle.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('dist\user\css\user_list.css') }}">

<!-- User table starts -->
<div class="row">
    <div class="col-12">
        <div class="material-card card">
            <div class="card-body">
                <h4 class="card-title">User List</h4>
                <br>

                <!-- Filter Section -->
                <div class="row">
                    <div class="col-12 text-right mb-2">
                        <a href="{{$_SERVER['REQUEST_URI']}}">
                            <button type="button" title="Refresh" class="btn btn-outline-primary btn-circle btn-md mr-2"><i class="mdi mdi-rotate-right"></i></button>
                        </a>
                        <button type="button" title="Apply Filter" class="btn btn-outline-info btn-circle btn-md mr-2" data-toggle="collapse" data-target="#filterBox"><i class="fa fa-filter"></i></button>
                    </div>

                    <div class="col-10">
                    <div class="collapse show" id="filterBox">
                        <form action="{{ $_SERVER['REQUEST_URI'] }}" method="post">
                        @csrf
                            @if(!isset($request->role_alias))
                            <div class="row">

                                <div class="col-3">
                                    <select name="role_id" id="role_id" class="form-control">
                                        <option disabled selected>Select Role</option>
                                        @foreach($allRoles as $role)
                                            @if($role->roleId == $request->role_id)
                                                <option value="{{ $role->roleId }}" selected> {{ $role->role }}</option>
                                            @elseif(Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.DISTRIBUTOR') && ($role->alias == Config::get('constants.ROLE_ALIAS.RETAILER')))
                                                <option value="{{ $role->roleId}}" data-alias="{{ $role->alias }}">{{ $role->role}}</option>
                                            @elseif(Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.DISTRIBUTOR') && ($role->alias == Config::get('constants.ROLE_ALIAS.FOS')))
                                            <option value="{{ $role->roleId}}" data-alias="{{ $role->alias }}">{{ $role->role}}</option>
                                            @elseif(Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN')) 
                                                <option value="{{ $role->roleId}}" data-alias="{{ $role->alias }}">{{ $role->role}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-2">
                                    <button class="btn btn-md btn-outline-primary" id="filter-submit-btn" type="submit"><i class="fa fa-filter"></i> Filter</button>
                                </div>
                            </div>
                            @endif
                        </form>
                    </div>
                    </div>
                    <div class="col-2 text-right">
                    <div class="collapse text-right" id="exportBox">
                        <div class="btn-group">
                            @if(isset($rechargeReports) && $rechargeReports)
                                <button type="submit"  id="pdf-btn" class="btn btn-md btn-warning"><i class="mdi mdi-file-pdf"></i> PDF</button>
                            @else
                                <button type="submit"  id="pdf-btn" class="btn btn-md btn-warning" disabled><i class="mdi mdi-file-pdf"></i> PDF</button>
                            @endif
                        </div>
                    </div>
                    </div>
                </div>
                <!-- Filter Section ends -->
                <br>
                <div class="table-responsive">
                    <table id="user-table" class="table table-striped table-sm border is-data-table">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Name</th>
                                <th>Parent Name</th>
                                <th>Role</th>
                                <th>Mobile</th>
                                <th>Balance</th>
                                <th>Package</th>
                                <th>Reg Date</th>
                                <th>Min. Bal.</th>
                                <th>Last Activty</th>
                                @if(Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN'))
                                    <th>KYC Status</th>
                                @endif
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($userList as $index => $user)
                                <tr>
                                    <td>{{ $index+1 }}</td>
                                    <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                                    <td>
                                        @foreach($allRoles as $role)
                                            @if($role->roleId == $user->parent_role_id)
                                                {{ $role->role }}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach($allRoles as $role)
                                            @if($role->roleId == $user->roleId)
                                                {{ $role->role }}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>{{ $user->mobile }}</td>
                                    <td>{{ $user->wallet_balance }}</td>
                                    <td>
                                        @foreach($allPackages as $package)
                                            @if($package->package_id == $user->package_id)
                                                {{ $package->package_name }}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        {{ $user->createdDtm ? date('d/m/Y', strtotime($user->createdDtm)) :'' }}
                                    </td>
                                    <td>{{ $user->min_balance }}</td>
                                    <td>{{ $user->last_activity ?  substr($user->last_activity,0,8) : '' }}</td>
                                    @if(Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN'))

                                        <td class="label">
                                            <a href="javascript:void(0)" onclick="loadKycStatusMdl({{$user['kyc_dtls']}})"
                                             class="{{ isset($user['kyc_dtls']['status']) && $user['kyc_dtls']['status'] == 'APPROVED' ?  'text-success' : (isset($user['kyc_dtls']['status']) && $user['kyc_dtls']['status'] == 'PENDING' ? 'text-warning' : 'text-danger') }}">
                                                {{ isset($user['kyc_dtls']['status']) ?  $user['kyc_dtls']['status'] : '' }} 
                                            </a>
                                        </td>

                                    @endif
                                    <td class="text-center">
                                        @if($user->activated_status == Config::get('constants.ACTIVE'))
                                            <input checked id="status-btn_{{ $index+1 }}" class="status-btn" type="checkbox"
                                             data-id="{{ $user->userId }}" data-on="Active" data-off="Inactive" data-onstyle="success"
                                              data-toggle="toggle" data-width="90" data-style="ios" data-style="slow"
                                              {{ (Auth::userRoleAlias() != Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN')) ? 'disabled' : '' }}>
                                        @else
                                            <input id="status-btn_{{ $index+1 }}" class="status-btn" type="checkbox"
                                             data-id="{{ $user->userId }}" data-on="Active" data-off="Inactive" data-onstyle="success"
                                              data-toggle="toggle" data-width="90" data-style="ios" data-style="slow"
                                              {{ (Auth::userRoleAlias() != Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN')) ? 'disabled' : '' }}>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a type="button" class="btn btn-sm btn-primary" title="Edit" href="{{ route('edit_user',$user->userId) }}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <<th>Sr No</th>
                                <th>Name</th>
                                <th>Parent Name</th>
                                <th>Role</th>
                                <th>Mobile</th>
                                <th>Balance</th>
                                <th>Package</th>
                                <th>Reg Date</th>
                                <th>Min. Bal.</th>
                                <th>Last Activty</th>
                                @if(Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN'))
                                    <th>KYC Status</th>
                                @endif
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- User table ends -->

<!-- Update KYC Status modal starts -->
<div class="modal" id="updateKYCStatusModal" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel1">Approve KYC</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form id="updateKycStatusForm" action="{{ route('update_kyc') }}" method="post">
            @csrf
                <input type="hidden" id="kyc_row_id" name="kyc_id" value="">
                <div class="row m-4">
                    <div class="col-3 border-bottom">
                        <div class="form-group">
                            <label class="label font-sm" for="">PAN Card (Front)</label>
                            <input type="hidden" id="ad-pan_front_file_id" name="pan_front_file_id"><br>
                            <select name="pan_front_file_status" id="ad-pan_front_file_status" class="kyc-status-dd">
                                <option disabled selected>Select Status</option>
                                <option value="APPROVED">APPROVED</option>
                                <option value="PENDING">PENDING</option>
                                <option value="DECLINED">DECLINED</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-3 border-right border-bottom" id="pan_front_img">
                        <a target="_blank">
                            <img src="" alt="" style="height:60px;width:100%;border:1px solid lightgrey">
                        </a>
                    </div>

                    <div class="col-3 border-bottom">
                        <div class="form-group">
                            <label class="label font-sm" for="">Aadhar Card (Front)</label>
                            <input type="hidden" id="ad-aadhar_front_file_id" name="aadhar_front_file_id">
                            <select name="aadhar_front_file_status" id="ad-aadhar_front_file_status" class="kyc-status-dd">
                                <option disabled selected>Select Status</option>
                                <option value="APPROVED">APPROVED</option>
                                <option value="PENDING">PENDING</option>
                                <option value="DECLINED">DECLINED</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-3 border-bottom" id="aadhar_front_img">
                        <a target="_blank">
                            <img src="" alt="" style="height:60px;width:100%;border:1px solid lightgrey">
                        </a>
                    </div>

                    <div class="col-3 mt-4 border-bottom">
                        <div class="form-group">
                            <label class="label font-sm" for="">Aadhar Card (Back)</label>
                            <input type="hidden" id="ad-aadhar_back_file_id" name="aadhar_back_file_id">
                            <select name="aadhar_back_file_status" id="ad-aadhar_back_file_status" class="kyc-status-dd">
                                <option disabled selected>Select Status</option>
                                <option value="APPROVED">APPROVED</option>
                                <option value="PENDING">PENDING</option>
                                <option value="DECLINED">DECLINED</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-3 mt-4 border-right border-bottom" id="aadhar_back_img">
                        <a target="_blank">
                            <img  src="" alt="" style="height:60px;width:100%;border:1px solid lightgrey">
                        </a>
                    </div>

                    <div class="col-3 mt-4 border-bottom">
                        <div class="form-group">
                            <label class="label font-sm" for="">Store Photo (Front)</label>
                            <input type="hidden" id="ad-photo_front_file_id" name="photo_front_file_id">
                            <select name="photo_front_file_status" id="ad-photo_front_file_status" class="kyc-status-dd">
                                <option disabled selected>Select Status</option>
                                <option value="APPROVED">APPROVED</option>
                                <option value="PENDING">PENDING</option>
                                <option value="DECLINED">DECLINED</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-3 mt-4 border-bottom" id="photo_front_img">
                        <a target="_blank">
                            <img  src="" alt="" style="height:60px;width:100%;border:1px solid lightgrey">
                        </a>
                    </div>
                    <div class="col-3 mt-4">
                        <div class="form-group">
                            <label class="label font-sm" for="">Store Photo (Inner)</label>
                            <input type="hidden" id="ad-photo_inner_file_id" name="photo_inner_file_id">
                            <select name="photo_inner_file_status" id="ad-photo_inner_file_status" class="kyc-status-dd">
                                <option disabled selected>Select Status</option>
                                <option value="APPROVED">APPROVED</option>
                                <option value="PENDING">PENDING</option>
                                <option value="DECLINED">DECLINED</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-3 mt-4" id="photo_inner_img">
                        <a target="_blank">
                            <img  src="" alt="" style="height:60px;width:100%;border:1px solid lightgrey">
                        </a>
                    </div>

                    <!-- <div class="col-6 mt-4">
                        <div class="form-group">
                            <label class="label" for="ad-kyc-status">KYC Status</label> -->
                            <input type="hidden" class="form-control label" name="status" id="ad-kyc-status" readonly>
                            <!-- <select name="status" id="ad-kyc-status" class="form-control" readonly>
                                <option disabled selected>Select KYC Status</option>
                                <option value="APPROVED">APPROVED</option>
                                <option value="PENDING">PENDING</option>
                                <option value="DECLINED">DECLINED</option>
                            </select> -->
                        <!-- </div>
                    </div> -->
                </div>

                <button type="submit" id="update-kyc-status-btn" class="btn btn-info btn-block mt-4">
                    Update
                </button>
            </form>
        </div>
    </div>
</div>
<!-- Update KYC Status modal ends -->

</section>

<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<!--Datable plugins -->
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('template_assets/dist/js/pages/datatable/datatable-basic.init.js') }}"></script>
<!-- Datatable plugin ends -->
<script src="template_assets\other\js\bootstrap-toggle.min.js"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script src="{{ asset('dist\user\js\userList.js') }}"></script>
@endsection
