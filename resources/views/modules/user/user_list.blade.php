@extends('layouts.full_new')

@section('page_content')


<!-- This page plugin CSS -->
<link href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css"
        href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('template_assets\other\css\bootstrap-toggle.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('dist\user\css\user_list.css') }}">
<!-- User table starts -->
<div class="page-content container-fluid">
<div class="">
    <div class="col-12">
        <div class="material-card card">
            <div class="card-body">
                <h4 class="card-title">User List</h4>
                <br>
                <!-- Filter Section -->
                <div class="row">
                    <!-- <div class="col-12 text-right mb-2">
                        <a href="{{$_SERVER['REQUEST_URI']}}">
                            <button type="button" title="Refresh" class="btn btn-outline-primary btn-circle btn-md mr-2"><i class="mdi mdi-rotate-right"></i></button>
                        </a>
                        <button type="button" title="Apply Filter" class="btn btn-outline-info btn-circle btn-md mr-2" data-toggle="collapse" data-target="#filterBox"><i class="fa fa-filter"></i></button>
                    </div> -->

                    <div class="col-10">
                    <div class="collapse show" id="filterBox">
                        <form action="{{ $_SERVER['REQUEST_URI'] }}" method="post">
                        @csrf
                            @if(!isset($request->role_alias))
                            <div class="row">

                                <!-- <div class="col-3">
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
                                </div> -->
                                @if(!$isspam)
                                <div class="col-10 ">
                                    <div class="btn-group">
                                        <input type="hidden"  name="role_id" id="role_id_inp" value="{{ isset($request->role_id) ? $request->role_id : '' }}">
                                        @foreach($allRoles as $role)
                                            {{-- @if($role->alias != Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN')) --}}
                                                <button type="button" value="{{ $role->roleId }}" class="btn role-tab {{ $role->roleId == $request->role_id ? 'btn-primary' : 'btn-light'}}">{{ $role->role }}</button>
                                                {{-- @endif --}}
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                <div class="col-2 hide-this">
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
                    @if(Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN'))
                    @if($isspam)
                    <div class="col-12 text-right">
                        <a type="button" href="{{ route('user_list') }}" title="Active Users" class="btn btn-primary btn-md " style="color:white;"> All Users </a>
                    </div>
                    @else
                    <div class="col-12 text-right">
                        <a type="button" href="{{ route('spam_list', 'spam') }}" title="SPAM Users" class="btn btn-danger btn-md " style="color:white;"><i class="fa fa-ban"></i> SPAM Users </a>
                    </div>
                    @endif
                    @endif
                </div>
                
                <!-- Filter Section ends -->
                <br>
                <div class="table-responsive">
                    @if($request->role_id == Config::get('constants.ADMIN'))
                    <!-- <input type="hidden" name="menulist" id="menulist" value="{{-- json_encode(Config::get('constants.MENU_ALIAS')) --}}"> -->
                    <input type="hidden" name="menulist" id="menulist" value="{{ json_encode($all_menu) }}">
                    <table id="user-table" class="table table-striped table-sm border is-data-table">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Username</th>
                                <th>Mobile</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($userList as $index => $user)
                                <tr>
                                    <td>{{ $index+1 }}</td>
                                    <td>{{ $user->username }}</td>
                                   
                                   
                                    <td>{{ $user->mobile }}</td>
                                   
                                   
                                   
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
                                        <a type="button" class="btn btn-sm btn-primary" title="Edit" href="{{ route('edit_subadmin',$user->userId) }}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                       
                                        @if(Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN'))
                                            <a type="button" class="btn btn-sm btn-warning ch-pwd_btn" title="Reset Password" href="{{ route('reset_user_pwd',$user->userId) }}">
                                                    <i class="fa fa-key"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger delete_btn" title="DELETE" value="{{ $user->userId }}">
                                                    <i class="fa fa-trash"></i>
                                            </button>
                                            <button type="button" title="Permission" value="{{ (isset($user->userId))? $user->userId: '' }}"  class="btn btn-sm btn-success btn-md permission-btn" value="" >
                                                <i class="fa fa-bars" aria-hidden="true"></i>  
                                            </button>

                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Sr No</th>
                                <th>Username</th>
                                <th>Mobile</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                                
                        </tfoot>
                    </table>
                    @else
                    <table id="user-table" class="table table-striped table-sm border is-data-table">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Username</th>
                                <th>Store Name</th>
                                <th>Parent</th>
                                <!-- <th>Role</th> -->
                                <th>Mobile</th>
                                <th>Balance</th>
                                <th>Package</th>
                                <th>Reg Date</th>
                                <th>Min. Bal.</th>
                                <th>Last Activity</th>
                                <th>Last Login IP</th>
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
                                    <td>{{ $user->username }}</td>
                                    <td>
                                         @if(isset($user['ekyc']['business_name']))
                                        {{ $user['ekyc']['business_name'] }}
                                        @else
                                        @endif
                                        </td>
                                    <td>
                                        @if(isset($user['parentuser']['ekyc']['business_name']))
                                        {{$user['parentuser']['ekyc']['business_name']}}
                                        @else
                                        @endif
                                       </td>
                                    <!-- <td>
                                        @foreach($allRoles as $role)
                                            @if($role->roleId == $user->roleId)
                                                {{ $role->role }}
                                            @endif
                                        @endforeach
                                    </td> -->
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
                                    <td>
                                    {{ $user->last_activity ?  $user->last_activity : '' }}
                                    </td>
                                    <td>
                                    {{ $user->last_login_ip ?  $user->last_login_ip : '' }}
                                    </td>
                                    @if(Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN'))
                                        
                                        <td class="label">
                                            <a href="{{ route('edit_userekyc',$user->userId) }}"
                                             class="{{ isset($user->ekyc->complete_kyc) && $user->ekyc->complete_kyc == '1' ?  'text-success' : (isset($user->ekyc->complete_kyc) && $user->ekyc->complete_kyc == '0' ? 'text-warning' : 'text-danger') }}">
                                                {{ isset($user->ekyc->complete_kyc) && $user->ekyc->complete_kyc == '1' ?  'APPROVED' : (isset($user->ekyc->complete_kyc) && $user->ekyc->complete_kyc == '0' ? 'PENDING' : 'NA') }}
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
                                        <a type="button" class="btn btn-sm btn-primary" title="TDS" href="{{ route('view_tds',$user->userId) }}">
                                            <!-- <i class="fa fa-edit"></i> -->
                                            TDS
                                        </a>
                                        @if(Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN'))
                                            <a type="button" class="btn btn-sm btn-warning ch-pwd_btn" title="Reset Password" href="{{ route('reset_user_pwd',$user->userId) }}">
                                                    <i class="fa fa-key"></i>
                                            </a>
                                            @if($isspam)
                                                <a type="button" class="btn btn-sm btn-success spam_btn" title="SPAM" href="{{ route('remove_spam',$user->userId)  }}">
                                                        <!-- <i class="fa fa-ban"></i> -->
                                                        Remove
                                                </a>
                                            @else
                                            <a type="button" class="btn btn-sm btn-danger spam_btn" title="SPAM" href="{{ route('user_spam',$user->userId)  }}">
                                                    <i class="fa fa-ban"></i>
                                            </a>
                                            <button type="button" title="Services" value="{{ (isset($user['user_services']))? $user['user_services']: '' }}"  class="btn btn-success btn-md services-btn" value="" data-toggle="collapse" data-target="#user-services"><i class="fa fa-handshake" aria-hidden="true"></i>  </button>
                                            <button type="button" class="btn btn-sm btn-danger delete_btn" title="DELETE" value="{{ $user->userId }}">
                                                    <i class="fa fa-trash"></i>
                                            </button>
                                               
                                            @endif
                                            
                                            @if($user->va_id == "")
                                            <button type="button" class="btn btn-sm btn-danger" title="VA">
                                                    VA
                                            </button>
                                            @else
                                            <button type="button" class="btn btn-sm btn-success" title="VA">
                                                    VA
                                            </button>
                                            @endif
                                            <button type="button" title="PG Services" class="btn btn-sm btn-danger pg-options-btn" value="{{ (isset($user['pg_options'])) ? $user['pg_options'] : '' }}" onclick="openDialog({{ $user->userId }});" data-toggle="collapse" data-target="#pg-options">
                                                PG
                                            </button>
                                            <a type="button" class="btn btn-sm btn-danger spam_btn" title="QR" href="{{ route('regenerate_qr',$user->userId)  }}">
                                                    <i class="fa fa-qrcode"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Sr No</th>
                                <th>Username</th>
                                <th>Store Name</th>
                                <th>Parent</th>
                                <!-- <th>Role</th> -->
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
                    @endif
                </div>
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

<!-- add modal starts -->
<div class="modal" id="user-services" role="dialog">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel1" style="margin-left:60px">User Services</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="updateUserServicesForm" action="{{ route('update_user_sesrvices') }}" method="post">
                @csrf
                <input type="hidden" name="allow_service_user_id" id="allow_service_user_id">
                  <div class="col-12" id="user_service_dtls">
                    @if(isset($user['user_services']))

                    @else
                        @if(count($allServices) >0)
                            @foreach($allServices as $service_key => $service_value)
                            <div class="row">
                                <div class = "col-md-6">{{ $service_value->service_name }}</div>
                                <div class = "col-md-6">
                                        <fieldset class="checkbox">
                                            <label>
                                                <input type="checkbox" value="" name="{{ $service_value->alias }}"> Allowed
                                            </label>
                                        </fieldset>
                                </div>
                            </div>
                            @endforeach
                        @endif
                    @endif
                    </div>
                   
                   

                    <button type="submit" id="" class="btn btn-info btn-block mt-4">
                        Update
                    </button>
                </form>
            </div>
        </div>
    </div>
    <!-- add  modal ends -->
    
<!-- pg modal starts -->
<div class="modal" id="pg-options" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel1" style="margin-left:60px">PG Services</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form action="{{ route('update_user_pg_sesrvices') }}" method="post">
            @csrf
            <input type="hidden" name="allow_pg_user_id" id="allow_pg_user_id">
              <div class="col-12" id="user_pg_dtls">
                  <br>
                    <div class="row">
                        <div class="col-md-3">PG Status</div>
                        <div class="col-md-3">
                            <fieldset class="checkbox">
                                <label>
                                    <input type="checkbox" value="1" name="pg_status" id="pg_status"> Allowed
                                </label>
                            </fieldset>
                        </div>
                        <div class="col-md-3"></div>
                        <div class="col-md-3"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">CREDIT CARD</div>
                        <div class="col-md-3">
                            <fieldset class="checkbox">
                                <label>
                                    <input type="checkbox" value="1" name="credit_card" id="credit_card"> Allowed
                                </label>
                            </fieldset>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" name="cc_charge_type">
                                <option value="%" selected>%</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" value="" name="cc_charge_mode">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">DEBIT CARD</div>
                        <div class="col-md-3">
                            <fieldset class="checkbox">
                                <label>
                                    <input type="checkbox" value="1" name="debit_card" id="debit_card"> Allowed
                                </label>
                            </fieldset>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" name="dc_charge_type">
                                <option value="%" selected>%</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" value="" name="dc_charge_mode">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">RUPAY CARD</div>
                        <div class="col-md-3">
                            <fieldset class="checkbox">
                                <label>
                                    <input type="checkbox" value="1" name="rupay_card" id="rupay_card"> Allowed
                                </label>
                            </fieldset>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" name="rc_charge_type">
                                <option value="%" selected>%</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" value="" name="rc_charge_mode">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">UPI</div>
                        <div class="col-md-3">
                            <fieldset class="checkbox">
                                <label>
                                    <input type="checkbox" value="1" name="upi" ="upi"> Allowed
                                </label>
                            </fieldset>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" name="upi_charge_type">
                                <option value="%" selected>%</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" value="" name="upi_charge_mode">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">Wallet</div>
                        <div class="col-md-3">
                            <fieldset class="checkbox">
                                <label>
                                    <input type="checkbox" value="1" name="wallet" id="wallet"> Allowed
                                </label>
                            </fieldset>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" name="wa_charge_type">
                                <option value="%" selected>%</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" value="" name="wa_charge_mode">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">Net Banking</div>
                        <div class="col-md-3">
                            <fieldset class="checkbox">
                                <label>
                                    <input type="checkbox" value="1" name="net_banking" id="net_banking"> Allowed
                                </label>
                            </fieldset>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" name="nb_charge_type">
                                <option value="%" selected>%</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" value="" name="nb_charge_mode">
                        </div>
                    </div>
                </div>
                <button type="submit" id="" class="btn btn-info btn-block mt-4">
                    Update
                </button>
            </form>
        </div>
    </div>
</div>
    <!-- pg  modal ends -->

    <!-- Delete modal starts -->
    <div class="modal" id="user-delete" role="dialog">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel1">Delete</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="deleteUserForm" action="{{ route('delete_user') }}" method="post">
                @csrf
                <input type="hidden" name="delete_user_id" id="delete_user_id">
                    <div class="col-12">
                        Are You Sure?
                    </div>
                
                   

                    <button type="submit" id="" class="btn btn-info btn-block mt-4">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
    <!-- Delete  modal ends -->

    <!-- permission modal starts -->
    <div class="modal" id="user-permission" role="dialog">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel1" style="margin-left:60px">User Permission</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="updateUserPermissionForm" action="{{ route('update_user_permisssion') }}" method="post">
                @csrf
                <input type="hidden" name="permission_id" id="permission_id">
                  <div class="col-12" id="user_permission">
                   
                    </div>
                   
                   

                    <button type="submit" id="" class="btn btn-info btn-block mt-4">
                        Update
                    </button>
                </form>
            </div>
        </div>
    </div>
    <!-- permission  modal ends -->


<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<!--Datable plugins -->
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('template_assets/dist/js/pages/datatable/datatable-basic.init.js') }}"></script>
<!-- Datatable plugin ends -->
<script src="template_assets\other\js\bootstrap-toggle.min.js"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script src="{{ asset('dist\user\js\userList.js') }}"></script>
<script>
function openDialog(id) {
    var uid = id;
    console.log(uid);
    document.getElementById("allow_pg_user_id").value = uid;
    $('#pg-options').modal('show');
}
function openDialog1(pg) {
    var uid = pg.value;
    console.log(uid);
    document.getElementById("allow_pg_user_id").value = uid;
    $('#pg-services').modal('show');
}
</script>
@endsection
