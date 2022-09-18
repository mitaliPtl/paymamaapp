@extends('layouts.full_new')

@section('page_content')

<section>
<!-- This page plugin CSS -->
<link href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css"
        href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('template_assets\other\css\bootstrap-toggle.min.css') }}">
<link rel="stylesheet" href="{{ asset('dist\reports\css\reports.css') }}">
<!-- User table starts -->
<!DOCTYPE html>
<style>
    .table>thead>tr>th{
        background:white !important;
        text-transform:uppercase;
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="material-card card">
            <div class="card-body">
                <h4 class="card-title">Verification User List</h4>
                <br>

                <!-- Filter Section -->
                <div class="row">
                    <!-- <div class="col-12 text-right mb-2">
                        <a href="{{$_SERVER['REQUEST_URI']}}">
                            <button type="button" title="Refresh" class="btn btn-outline-primary btn-circle btn-md mr-2"><i class="mdi mdi-rotate-right"></i></button>
                        </a>
                        <button type="button" title="Apply Filter" class="btn btn-outline-info btn-circle btn-md mr-2" data-toggle="collapse" data-target="#filterBox"><i class="fa fa-filter"></i></button>
                    </div> -->

                 
                </div>
                
                <!-- Filter Section ends -->
                <br>
                
 

<div class="">
 

  <!-- Modal -->
  
  
 
  
</div>
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>


    
                <div class="table-responsive">
                    @if(Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN'))
                    @if($isspam)
                    <div class="col-12 text-right">
                        <a type="button" href="{{ route('verification_list') }}" title="Active Users" class="btn btn-primary btn-md " style="color:white;"> All Users </a>
                    </div>
                    @else
                    <div class="col-12 text-right">
                        <a type="button" href="{{ route('spam_verification_list', 'spam') }}" title="SPAM Users" class="btn btn-danger btn-md " style="color:white;"><i class="fa fa-ban"></i> SPAM Users </a>
                    </div>
                    @endif
                    @endif
                    @if($request->role_id == Config::get('constants.ADMIN'))
                    <!-- <input type="hidden" name="menulist" id="menulist" value="{{-- json_encode(Config::get('constants.MENU_ALIAS')) --}}"> -->
                    <input type="hidden" name="menulist" id="menulist" value="{{ json_encode($all_menu) }}">
                    
                    @else
                        <form action="{{ $_SERVER['REQUEST_URI'] }}" method="post">
                        @csrf
                            <input type="hidden" id="is_export" name="is_export" value="0">
                            <div class="row" style="margin-left:20px;">
                                <div class="form-group">
                                    <label>From Date</label>
                                    <input type="date" class="form-control" id="" name="" value="{{ $request->from_date}}" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label>To Date</label>
                                    <input type="date" class="form-control" id="" name="" value="{{ $request->from_date}}" placeholder="">
                                </div>
                            
                            <div class="form-group">
                                    <label><br></label>
                                    <button class="btn btn-md btn-outline-primary btn-md success-grad" id="filter-submit-btn" type="submit" style="margin-top:30px;"><i class="fa fa-filter"></i> Filter</button>
                            </div>
                            </div>
                        </form>
                         <a type="button" href="{{ route('retailer_verification_list', '4') }}" class="btn btn-success btn-md " style="color:white;">Retailer</a>
                         <a type="button" href="{{ route('retailer_verification_list', '2') }}" class="btn btn-success btn-md " style="color:white;">Distributor</a>
                         <a type="button" href="{{ route('retailer_verification_list', '7') }}" class="btn btn-success btn-md " style="color:white;">Master Distributor</a>
                        
                    <table id="recharge-report-table" class="table table-striped table-bordered table-sm border is-data-table">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Date</th>
                                <th>UserType</th>
                                <th>Parent User Type</th>
                                <th>Parent User Name</th>
                                <th>Mobile Number</th>
                                <th>Telecom Name</th>
                                <th>Telecom Alternate Mobile No</th>
                                <th>Telecom Email</th>
                                <th>Telecom Address</th>
                                <th>Aadhar Number</th>
                                <th>Aadhar  Name</th>
                                <th>Aadhar Address</th>
                                <th>Pan No.</th>
                                <th>Pan Name</th>
                                <th>Bank Account No.</th>
                                <th>Account Holder Name</th>
                                <th>IFSC Code</th>
                                <th>Bank Name</th>
                                <th>Branch Name</th>
                                <th>Selfie Score</th>
                                <th>Email Id</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $index=>$user)
                                <tr>
                                 
                                 <td>{{ $index+1 }}</td>
                                 <td>{{date('d-m-Y',strtotime($user->created_at))}}</td>
                                 <td>
                                     @if($user->roleID==4)
                                     RETAILER
                                     @elseif($user->roleID==2)
                                     DISTRIBUTOR
                                     @else
                                     MASTER DISTRIBUTOR
                                     @endif
                                 </td>
                                 <td>
                                     
                                     @if($user->roleID==4)
                                     DISTRIBUTOR
                                     @elseif($user->roleID==2)
                                     MASTER DISTRIBUTOR
                                     @else
                                     ADMIN
                                     @endif
                                     
                                     
                                 </td>
                                 <td>
                                     @php
                                               $parentuserid=$user['parent_user_id'];
                                              $results = DB::select( DB::raw("SELECT * FROM tbl_users WHERE userId = :somevariable"), array(
                                                   'somevariable' => $parentuserid,
                                                 ));
                                                 
                                                 $array = json_decode(json_encode($results), true);
                                               
                                               foreach($array as $resulta) {
                                                    echo $resulta['first_name'].'&nbsp;&nbsp;'.$resulta['last_name'];
                                                }
                                      @endphp
                                 </td>
                                 <td>{{ $user->mobile_number }}</td>
                                    <td>{{ $user->telecome_name }}</td>
                                    <td>{{ $user->alternate_mob_no }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->address }}</td>
                                    <td>{{ $user->aadhar_number }}</td>
                                    <td>{{ $user->aadhar_name }}</td>
                                    <td>{{ $user->aadhar_address }}</td>
                                    <td>{{ $user->pan_number }}</td>
                                    <td>{{ $user->pan_name }}</td>
                                    <td>{{ $user->account_number }}</td>
                                    <td>{{ $user->bank_account_name }}</td>
                                    <td>{{ $user->ifsc_code }}</td>
                                    <td>{{ $user->bank_name }}</td>
                                    <td>{{ $user->branch_name }}</td>
                                    <td>{{ $user->success_score }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
@if($user->is_ocr==1)
<button type="button" class="btn btn-primary btn-sm" id="viewkyc"  data-toggle="modal" data-target="#myModalaadhar{{ $user->id }}" data-email="{{ $user->id }}">View Aadhar Image</button>&nbsp;&nbsp;
@endif
<button type="button" class="btn btn-info btn-sm" id="viewkyc"  data-toggle="modal" data-target="#myModal{{ $user->id }}" data-email="{{ $user->id }}">View KYC</button><br>
<button type="button" class="btn btn-info btn-primary" style="background-color:#6868d1;color:white;" id="viewbusiness"  data-toggle="modal" data-target="#myModals{{ $user->id }}">View Business</button>
                                           @if($isspam)
                                                <a type="button" class="btn btn-sm btn-success spam_btn" title="SPAM" href="/remove_verification_spam/{{ $user->id }}">
                                                        <!-- <i class="fa fa-ban"></i> -->
                                                        Remove
                                                </a>
                                            @else
<a type="button" class="btn btn-sm btn-danger spam_btn" title="SPAM" href="{{ route('verification_spam',$user->id)  }}">
                                                    <i class="fa fa-ban"></i>&nbsp; Spam
                                            </a>
                                            @endif
                                            <button type="button" class="btn btn-sm btn-danger delete_btn" style="background-color:#d33535;" title="DELETE" value="{{ $user->id }}">
                                                    <i class="fa fa-trash"></i>
                                            </button>
                                            <a type="button" class="btn btn-sm btn-primary spam_btn" title="SPAM" style="background:green;color:white;" href="{{ route('manualverify',$user->id)  }}">
                                                   &nbsp; Verify
                                            </a>

 <div class="modal fade" id="myModalaadhar{{ $user->id }}" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        
        <div class="modal-body">
            <div class="row">
                @php
                        $aadhar_front = $user['aadhar_front'];
                     
                     $resultsfront = DB::select( DB::raw("SELECT * FROM tbl_files WHERE id = :somevariable"), array(
                       'somevariable' => $aadhar_front,
                     ));
                     
                     $aadhar='';
                     $aadharname='';
                     
                     foreach ($resultsfront as $values) 
                     {
                        $aadhar = $values->file_path;
                        $aadharname = $values->name;
                     }

                     $aadhar_back = $user['aadhar_back'];
                     
                     $resultsback = DB::select( DB::raw("SELECT * FROM tbl_files WHERE id = :somevariable"), array(
                       'somevariable' => $aadhar_back,
                     ));
                     
                     $aadharback='';
                     $aadharbackname='';
                     
                     foreach ($resultsback as $value) 
                     {
                        $aadharback = $value->file_path;
                        $aadharbackname = $value->name;
                     }
                    
                @endphp
                <div class="col-sm-6">
                    <h3>AADHAR FRONT</h3>
                    
                    <img src="https://paymamaapp.in{{ $aadhar }}{{$aadharname}}" style="height:320px;width:100%;">
                </div>
                <div class="col-sm-6">
                    <h3>AADHAR BACK</h3>
                    <img src="https://paymamaapp.in{{ $aadharback }}{{$aadharbackname}}" style="height:320px;width:100%">
                </div>
            </div>
            
            
            <!--<p>Your room number is: <span class="roomNumber"></span>.</p>
          <p>Some text in the modal.</p>-->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
 <div class="modal fade" id="myModals{{ $user->id }}" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        
        <div class="modal-body">
          <form class="">
              <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Business Name</label>
                        <input type="text" class="form-control" name="" value="{{ $user->business_name }}" disabled>
                    </div>
                </div>
                </div>
                <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Business Address</label>
                        <input type="text" class="form-control" name="" value="{{ $user->business_address }}" disabled>
                    </div>
                </div>
                </div>
                @php
                     $district_id = $user['district_id'];
                   
                     $result = DB::select( DB::raw("SELECT * FROM tbl_district_mst WHERE city_id = :somevariable"), array(
                       'somevariable' => $district_id,
                     ));
                     $array='';
                     
                     foreach ($result as $value) 
                     {
                        $array = $value->city_name;
                     }
                     
                      $state_id = $user['state_id'];
                     
                     $results = DB::select( DB::raw("SELECT * FROM tbl_state_mst WHERE state_id = :somevariable"), array(
                       'somevariable' => $state_id,
                     ));
                     
                     $state='';
                     
                     foreach ($results as $value) 
                     {
                        $state = $value->state_name;
                     }
                     
                     $shop_inside_image = $user['shop_inside_image'];
                     
                     $resultspan = DB::select( DB::raw("SELECT * FROM tbl_files WHERE id = :somevariable"), array(
                       'somevariable' => $shop_inside_image,
                     ));
                     
                     $shop_inside='';
                     
                     foreach ($resultspan as $value) 
                     {
                        $shop_inside = $value->file_path;
                     }
                     
                     
                     $shop_front_image = $user['shop_front_image'];
                     
                     $resultspan = DB::select( DB::raw("SELECT * FROM tbl_files WHERE id = :somevariable"), array(
                       'somevariable' => $shop_front_image,
                     ));
                     
                     $shop_front='';
                     
                     foreach ($resultspan as $value) 
                     {
                        $shop_front = $value->file_path;
                     }
                     
                      $category = $user['business_category'];
                     
                     $resultspans = DB::select( DB::raw("SELECT * FROM tbl_category WHERE category_id = :somevariable"), array(
                       'somevariable' => $category
                     ));
                     
                     $category_name='';
                     
                     foreach ($resultspans as $values) 
                     {
                        $category_name = $values->category;
                     }
                
                @endphp
                <div class="row">
                  <div class="col-sm-4">
                    <div class="form-group">
                        <label>State</label>
                        
                        <input type="text" class="form-control" name="" value="{{ $state }}" disabled>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-group">
                        <label>City</label>
                        <input type="text" class="form-control" name="" value="{{ $array }}" disabled>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-group">
                        <label>Zip Code</label>
                        <input type="text" class="form-control" name="" value="{{ $user->shop_long }}" disabled>
                    </div>
                  </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                    <div class="form-group">
                        <label>Category</label>
                        <input type="text" class="form-control" name="" value="{{ $category_name }}" disabled>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-group">
                        <label>Shop Latitude</label>
                        <input type="text" class="form-control" name="" value="{{ $user->shop_lat }}" disabled>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-group">
                        <label>Shop Longitude</label>
                        <input type="text" class="form-control" name="" value="{{ $user->shop_long }}" disabled>
                    </div>
                  </div>
                </div>
                
                <div class="row">
                    <div class="col-sm-12">
                    <h3>Shop Front Image</h3>
                    <img src="https://paymamaapp.in/admin{{ $shop_front }}" style="height:460px;width:100%;">
                </div>
                <div class="col-sm-12">
                    <h3>Shop Inside</h3>
                    <img src="https://paymamaapp.in/admin{{ $shop_inside }}" style="height:460px;width:100%">
                </div>
                
            </div>
                <div class="row">
                    <div class="col-sm-6">
                      
                    </div>
                </div>
                
          </form>
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  <div class="modal fade" id="myModal{{ $user->id }}" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        
        <div class="modal-body">
            <div class="row">
                @php
                        $selfie_id = $user['selfie_id'];
                     
                     $resultsselfie = DB::select( DB::raw("SELECT * FROM tbl_files WHERE id = :somevariable"), array(
                       'somevariable' => $selfie_id,
                     ));
                     
                     $selfie='';
                     
                     foreach ($resultsselfie as $value) 
                     {
                        $selfie = $value->file_path;
                     }

                     $pan_id = $user['pan_id'];
                     
                     $resultspan = DB::select( DB::raw("SELECT * FROM tbl_files WHERE id = :somevariable"), array(
                       'somevariable' => $pan_id,
                     ));
                     
                     $pan='';
                     
                     foreach ($resultspan as $value) 
                     {
                        $pan = $value->file_path;
                     }
                    
                @endphp
                <div class="col-sm-6">
                    <h3>Selfie</h3>
                    
                    <img src="https://paymamaapp.in/admin{{ $selfie }}" style="height:320px;width:100%;">
                </div>
                <div class="col-sm-6">
                    <h3>Pan</h3>
                    <img src="https://paymamaapp.in/admin{{ $pan }}" style="height:320px;width:100%">
                </div>
            </div>
            <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                        <label>Pic Latitude</label>
                        <input type="text" class="form-control" name="" value="{{ $user->pic_lat }}" disabled>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                        <label>Pic Longitude</label>
                        <input type="text" class="form-control" name="" value="{{ $user->pic_lang }}" disabled>
                    </div>
                  </div>
                </div>
            
            <!--<p>Your room number is: <span class="roomNumber"></span>.</p>
          <p>Some text in the modal.</p>-->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  
<br>
 </td>

    <script>
$(document).ready(function(){
     var myRoomNumber;
    $("#viewkyc").click(function(){
         myRoomNumber = $(this).attr('data-email');
         $("#myModal").modal("show");
     
    });
    $("#myModal").on('show.bs.modal', function () {
       $(this).find('.roomNumber').text(myRoomNumber);
    });
});


$(document).ready(function(){
     var myRoomNumber;
    $("#viewbusiness").click(function(){
         myRoomNumber = $(this).attr('data-email');
         $("#myModals").modal("show");
     
    });
    $("#myModals").on('show.bs.modal', function () {
       $(this).find('.roomNumber').text(myRoomNumber);
    });
});
</script>

 
  

                                      
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Sr No</th>
                                <th>Date</th>
                                <th>UserType</th>
                                <th>Parent User Type</th>
                                <th>Parent User Name</th>
                                <th>Mobile Number</th>
                                <th>Telecom Name</th>
                                <th>Telecom Alternate Mobile No</th>
                                <th>Telecom Email</th>
                                <th>Telecom Address</th>
                                <th>Aadhar Number</th>
                                <th>Aadhar  Name</th>
                                <th>Aadhar Address</th>
                                <th>Pan No.</th>
                                <th>Pan Name</th>
                                <th>Bank Account No.</th>
                                <th>Account Holder Name</th>
                                <th>IFSC Code</th>
                                <th>Bank Name</th>
                                <th>Branch Name</th>
                                <th>Selfie Score</th>
                                <th>Email Id</th>
                                <th>Action</th>
                                </tr>
                        </tfoot>
                    </table>
                    @endif
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
<script>
function openDialog1(View Business) {
    var uid = View Business.value;
    console.log(uid);
    document.getElementById("allow_pg_user_id").value = uid;
    $('#myModals').modal('show');
}
</script>
@endsection
