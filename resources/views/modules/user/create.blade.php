{{-- @extends('layouts.full') --}}
@extends('layouts.full_new')
@section('page_content')



<div class="page-content container-fluid">
    <link rel="stylesheet" type="text/css" href="https://paymamaapp.in/template_assets/other/css/bootstrap-toggle.min.css">
    
<section ng-app="myApp" ng-controller="addEditUserCtrl">
    <input type="hidden" id="allRoles" value="{{ $allRoles }}">
    @if(isset($notAdminUserId) && $notAdminUserId)
        <input type="hidden" id="notAdminUserId" value="{{ isset($notAdminUserId) ? $notAdminUserId : '' }}">
    @endif
    @if(isset($userById->userId))
        <input type="hidden" id="user_id" value="{{ isset($userById->userId) ? $userById->userId : '' }}">
        @if(Auth::user()->roleId == Config::get('constants.DISTRIBUTOR'))
        <input type="hidden" id="hidden_parent_user_id" value="{{ Auth::user()->userId }}">

        @endif
    @endif
    <div class="row">
        <div class="col-12">
            <div class="">
                <!--<h4 class="card-title">{{ isset($userById->userId) ? 'Update' : 'Add New' }}  User</h4>-->
            
                    <form method="post" action="{{ route('updateuserid',isset($userById->userId) ? $userById->userId : '') }}" id="editUserForm">
                
                @csrf
                    <input type="hidden" class="form-control" id="ids" name="ids" value="{{ isset($userById->userId) ? $userById->userId : '' }}">
                    <div class="row" style="">
                        <div class="col-2" style="padding:10px;"><div class="card card-body" style="height: 669px;">
                                    <center><h4 style="font-weight:bold;">AADHAR INFORMATION</h4></center>
                                    <hr style="border:1px solid red;">
                                    <div class="form-group" style="height:100px;width:100%;">
                                        
                                       <img src="{{asset('template_assets/Aadhaar_card.png')}}" style="height:100px;width:100%;">
                                        </div>
                                    <div class="form-group">
                                        <label for="first_name">AADHAR HOLDER NAME</label>
                                        <input type="text" class="form-control" id="first_name" name="first_name" value="{{ isset($userById->ekyc->aadhaar_name) ? $userById->ekyc->aadhaar_name : '' }}" disabled>
                                        <input type="hidden" class="form-control" id="first_name" name="aadhar_name" value="{{ isset($userById->ekyc->aadhaar_name) ? $userById->ekyc->aadhaar_name : '' }}">
                                    </div>
                                      <div class="form-group">
                                        <label for="mobile">AADHAR NUMBER</label>
                                        <input type="text" class="form-control" id="aadhaar_no" name="" value="{{ isset($userById->ekyc->aadhaar_no) ? $userById->ekyc->aadhaar_no : '' }}" disabled>
                                        <input type="hidden" class="form-control" id="aadhaar_no" name="aadhaar_no" value="{{ isset($userById->ekyc->aadhaar_no) ? $userById->ekyc->aadhaar_no : '' }}">
                                        <!--@if(Auth::user()->roleId != Config::get('constants.ADMIN') && isset($userById->userId))
                                        <input type="number" class="form-control" id="mobile" name="mobile" value="{{ isset($userById->mobile) ? $userById->mobile : '' }}" readonly>
                                        @else
                                        <input type="number" class="form-control" id="mobile" name="mobile" value="{{ isset($userById->mobile) ? $userById->mobile : '' }}" >
                                        @endif-->
                                    </div>
                                    <div class="form-group">
                                        <label for="last_name">AADHAR ADDRESS </label>
                                        <textarea class="form-control" disabled rows="20" style="width:100%;height:180px !important;">{{ isset($userById->ekyc->aadhaar_address) ? $userById->ekyc->aadhaar_address : '' }}</textarea>
                                    </div>
                                 </div></div>
                                <div class="col-2" style="padding:10px;height: 669px;">
                                    <div class=" card card-body"  style="height: 669px;">
                                    <center><h4 style="font-weight:bold;">PAN INFORMATION</h4></center>
                                    <hr style="border:1px solid red;">
                                     <div class="form-group" style="height:100px;width:100%;">
                                        <img src="{{asset('template_assets/Pan_card.png')}}" style="height:100px;width:100%;">
                                      
                                        </div>
                                   
                                    <div class="form-group">
                                        <label for="last_name">PAN HOLDER NAME</label>
                                        <input type="text" class="form-control" disabled id="last_name" name="last_name" value="{{ isset($userById->ekyc->pan_name) ? $userById->ekyc->pan_name : '' }}">
                                         <input type="hidden" class="form-control"  id="last_name" name="pan_name" value="{{ isset($userById->ekyc->pan_name) ? $userById->ekyc->pan_name : '' }}">
                                    </div>
                                     <div class="form-group">
                                        <label for="alternate_mob_no">PAN NUMBER</label>
                                        <input type="text" class="form-control" disabled id="alternate_mob_no" name="" value="{{ isset($userById->ekyc->pan_no) ? $userById->ekyc->pan_no : '' }}">
                                         <input type="hidden" class="form-control"  id="alternate_mob_no" name="pan_no" value="{{ isset($userById->ekyc->pan_no) ? $userById->ekyc->pan_no : '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="aadhar_no">PAN CARD IMAGE</label>
                                        <br>
                                        <button style="width: 100%;height: 38px;font-size: 19px;" type="button" class="btn btn-success btn-sm" id="viewkyc"  data-toggle="modal" data-target="#panmodal" data-email="{{ isset($userById->id) ? $userById->id : '' }}">View</button>
                                    </div>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <div class="modal fade" id="aadharmodal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        
        <div class="modal-body">
            <div class="row">
                @php
                    $aadhar_front = $userById['ekyc']['zip_file'];
                     
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

                     $aadhar_back = $userById['ekyc']['aadhaar_image'];
                     
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
                    
                    <img src="https://paymamaapp.in/public{{$aadharname}}" style="height:320px;width:100%;">
                </div>
                <div class="col-sm-6">
                    <h3>AADHAR BACK</h3>
                    <img src="https://paymamaapp.in/public{{$aadharbackname}}" style="height:320px;width:100%">
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
    <div class="modal fade" id="panmodal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        
        <div class="modal-body">
            <div class="row">
                @php
                     $pan_id = $userById['ekyc']['pan_file'];
                     
                     $resultspan = DB::select( DB::raw("SELECT * FROM tbl_files WHERE id = :somevariable"), array(
                       'somevariable' => $pan_id,
                     ));
                     
                     $pan='';

                     foreach ($resultspan as $value) 
                     {
                        $pan = $value->file_path;
                     }
                    
                @endphp
                <div class="col-sm-12">
                    
                    <img src="https://paymamaapp.in/public{{ $pan }}" style="height:auto;width:100%">
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
                                    </div>
                                </div>
                                <div class="col-2" style="padding:10px;">
                                    <div class="card card-body"  style="height: 669px;">
                                        <center><h4 style="font-weight:bold;">BANK INFORMATION</h4></center>
                                        <hr style="border:1px solid red;">
                                        <div class="form-group" style="height:100px;width:100%;">
                                          <img src="{{asset('template_assets/Bank_Verification.png')}}" style="height:100px;width:100%;">
                                        </div>
                                   
                                        <div class="form-group">
                                            <label for="aadhar_no">ACCOUNT HOLDER NAME</label>
                                            <input type="text" class="form-control" @if( Auth::user()->roleId == 2 ) readonly @endif id="full_name" name="full_name" value="{{ isset($userById->ekyc->acc_name) ? $userById->ekyc->acc_name : '' }}">
                                        </div>
                                    
                                        <div class="form-group">
                                            <label for="telegram_no">ACCOUNT NUMBER</label>
                                            <input type="text" class="form-control" @if( Auth::user()->roleId == 2 ) readonly @endif id="bank_account_no" name="bank_account_no" value="{{ isset($userById->ekyc->acc_no) ? $userById->ekyc->acc_no : '' }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="store_name">BANK NAME</label>
                                            <input type="text" class="form-control" @if( Auth::user()->roleId == 2 ) readonly @endif id="bank_name" name="bank_name" value="{{ isset($userById->ekyc->bank_name) ? $userById->ekyc->bank_name : '' }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="store_name">IFSC CODE</label>
                                            <input type="text" class="form-control" @if( Auth::user()->roleId == 2 ) readonly @endif id="ifsc_code" name="store_name" value="{{ isset($userById->ekyc->ifsc_code) ? $userById->ekyc->ifsc_code : '' }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="store_name">BRANCH NAME</label>
                                            <input type="text" class="form-control" @if( Auth::user()->roleId == 2 ) readonly @endif id="branch_name" name="branch_name" value="{{ isset($userById->ekyc->branch_name) ? $userById->ekyc->branch_name : '' }}">
                                        </div>
                                        <button style="width: 100%;height: 38px;font-size: 19px;" type="button" class="btn btn-success btn-sm" id="updatebank">Update Details</button>
                                    </div>
                                </div>
                                <div class="col-2" style="padding:10px;">
                                    <div class=" card card-body"  style="height: 669px;">
                                   <center><h4 style="font-weight:bold;">SELFIE INFORMATION</h4></center>
                                   <hr style="border:1px solid red;">
                                     <div class="form-group" style="border:1px solid black;height:190px;width:100%;border-radius:50%;">
                                        @php
                                        $selfie_id = $userById['ekyc']['selfie_image'];
                                     
                                     $resultsselfie = DB::select( DB::raw("SELECT * FROM tbl_files WHERE id = :somevariable"), array(
                                       'somevariable' => $selfie_id,
                                     ));
                                     
                                     $selfie='';
                                     
                                     foreach ($resultsselfie as $value) 
                                     {
                                        $selfie = $value->file_path;
                                     }
                                     @endphp
                                        <img src="https://paymamaapp.in/public{{$selfie}}" style="height:190px;width:100%;border-radius:50%;">
                                        </div>
                                   <!-- <div class="form-group">
                                        @if($userById->selfie_id != '')
                                        @php
                                        $img = DB::select( DB::raw("SELECT * FROM tbl_files WHERE id = :somevariable"), array(
                                           'somevariable' => $userById->selfie_id
                                         ));
                                         $imgs = json_decode(json_encode($img), true);
                                         $selfiepath=$imgs[0]['file_path'];
                                         
                                        @endphp
                                        <img src="http://www.paymamaapp.in/admin{{$selfiepath}}" style="height:170px;width:60%;">
                                        @else
                                        <img src="{{asset('template_assets/assets/images/background/img3.jpg')}}" style="height:100px;width:100%;">
                                        @endif
                                        </div>-->
                                    
                                        <!--<img src="{{asset('template_assets/assets/images/background/img3.jpg')}}" style="border-radius:60%;height:100px;width:50%;">
                                        <center><h4 style="font-weight:800;">Score:90%</h4></center>-->
                                    <div class="form-group">
                                        <label for="aadhar_no">LATITUDE & LONGITUDE</label>
                                        <a target="_blank" href="https://maps.google.com/?q={{ isset($userById->ekyc->latitude) ? $userById->ekyc->latitude : '' }},{{ isset($userById->ekyc->longitude) ? $userById->ekyc->longitude : '' }}"><button style="width: 100%;height: 38px;font-size: 19px;" type="button" class="btn btn-success btn-sm" id="viewkyc">View</button></a>
                                        <!--<input type="text" class="form-control" id="aadhar_no" name="aadhar_no" value="Latitude: {{ $userById->pic_lat }} Longitude: {{$userById->pic_lan}}">-->
                                    </div>
                                   <div class="form-group">
                                        <label for="store_name">SHOP LATITUDE & LONGITIDE</label>
                                        <a target="_blank" href="https://maps.google.com/?q={{ isset($userById->ekyc->blat) ? $userById->ekyc->blat : '' }},{{ isset($userById->ekyc->blong) ? $userById->ekyc->blong : '' }}"><button style="width: 100%;height: 38px;font-size: 19px;" type="button" class="btn btn-success btn-sm" id="viewkyc">View</button></a>
                                      <!--  <input type="text" class="form-control" id="store_name" name="store_name" value="Latitude: {{ $userById->shop_lat }} Longitude: {{$userById->shop_lan}}">-->
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="store_name">SHOP INSIDE IMAGE</label>
                                      <button style="width: 100%;height: 38px;font-size: 19px;" type="button" class="btn btn-success btn-sm" id="viewkyc"  data-toggle="modal" data-target="#insidemodal" data-email="{{ $userById->id }}">View</button>
                                    <!--Shop inside modal start here-->
                                     <div class="modal fade" id="insidemodal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        
        <div class="modal-body">
            <div class="row">
                @php
                    $shop_inside_image = $userById['ekyc']['inside_image'];
                     
                     $resultsselfie = DB::select( DB::raw("SELECT * FROM tbl_files WHERE id = :somevariable"), array(
                       'somevariable' => $shop_inside_image,
                     ));
                     
                     $shop_inside_images='';
                     
                     foreach ($resultsselfie as $value) 
                     {
                        $shop_inside_images = $value->file_path;
                     }

                    
                @endphp
                
                <div class="col-sm-12">
                    
                    <img src="https://paymamaapp.in/public{{ $shop_inside_images }}" style="height:auto;width:100%">
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
                                    <!--Ends Here-->
                                    
                                    
                                    <!--Shop Outside Modal Start here-->
<div class="modal fade" id="outsidemodal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        
        <div class="modal-body">
            <div class="row">
                @php
                    $shop_outside_image = $userById['ekyc']['front_image'];
                     
                     $resultsselfie = DB::select( DB::raw("SELECT * FROM tbl_files WHERE id = :somevariable"), array(
                       'somevariable' => $shop_outside_image,
                     ));
                     
                     $shop_outside_images='';
                     
                     foreach ($resultsselfie as $value) 
                     {
                        $shop_outside_images = $value->file_path;
                     }

                    
                @endphp
                
                <div class="col-sm-12">
                    
                    <img src="https://paymamaapp.in/public{{ $shop_outside_images }}" style="height:auto;width:100%">
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>

                                    <!--Ends Here-->
                                    
                                    
                                    </div>
                                    <div class="form-group">
                                        <label for="store_name">SHOP OUTSIDE IMAGE</label>
                                        <button style="width: 100%;height: 38px;font-size: 19px;" type="button" class="btn btn-success btn-sm" id="viewkyc"  data-toggle="modal" data-target="#outsidemodal" data-email="{{ $userById->id }}">View</button>
                                    </div>
                                    
                                  </div>  
                                </div>
                                <div class="col-2" style="padding:10px;">
                                   <div class=" card card-body"  style="height: 669px;">
                                    <center><h4 style="font-weight:bold;">BUSINESS INFORMATION</h4></center>
                                    <hr style="border:1px solid red;">
                                    
                                   
                                        <!--<img src="{{asset('template_assets/assets/images/background/img3.jpg')}}" style="border-radius:60%;height:100px;width:50%;">
                                        <center><h4 style="font-weight:800;">Score:90%</h4></center>-->
                                    <div class="form-group">
                                        <label for="aadhar_no">BUSINESS NAME</label>
                                        <input type="text" class="form-control" @if( Auth::user()->roleId == 2 ) readonly @endif id="aadhar_no" name="business_name" value=" {{ isset($userById->ekyc->business_name) ? $userById->ekyc->business_name : '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="store_name">BUSINESS ADDRESS</label>
                                        <textarea class="form-control" @if( Auth::user()->roleId == 2 ) readonly @endif name="business_address" rows="5" style="width:100%;height:90px !important;">{{ isset($userById->ekyc->business_address) ? $userById->ekyc->business_address : '' }}</textarea>
                                        </div>
                                    <div class="form-group">
                                        <label for="store_name">STATE</label>
                                        <input type="text" class="form-control" @if( Auth::user()->roleId == 2 ) readonly @endif name="state_name" value=" {{ isset($userById->ekyc->state) ? $userById->ekyc->state : '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="store_name">CITY</label>
                                        <input type="text" class="form-control" @if( Auth::user()->roleId == 2 ) readonly @endif name="city_name" value=" {{ isset($userById->ekyc->city) ? $userById->ekyc->city : '' }}">
                                      
                                    </div>
                                    <div class="form-group">
                                        <label for="store_name">PINCODE</label>
                                        <input type="text" class="form-control" @if( Auth::user()->roleId == 2 ) readonly @endif id="store_name" name="pincode" value="{{ isset($userById->ekyc->pincode) ? $userById->ekyc->pincode : '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="store_name">BUSINESS CATEGORY</label>
                                        <input type="text" class="form-control" @if( Auth::user()->roleId == 2 ) readonly @endif id="store_name" name="category_name" value="{{ isset($userById->ekyc->category) ? $userById->ekyc->category : '' }}">
                                    </div>
                                    </div>
                                </div>
                    </div></div>
                    <hr style="1px dotted grey">
                    <div class="card card-body" style="margin-top:-56px;">
                        <div class="row" style="margin-top:-7px;">
                       
                        <div class="col-2">
                            <div class="form-group">
                                        <label for="store_name">USER FULL NAME</label>
                                        <input type="text" class="form-control" id="store_name" @if( Auth::user()->roleId == 2 ) readonly @endif name="user_full_name" value="{{ isset($userById->first_name) ? $userById->first_name : '' }}">
                            </div>
                            <div class="form-group">
                                        <label for="store_name">MOBILE NUMBER</label>
                                        <input type="text" class="form-control" id="store_name" @if( Auth::user()->roleId == 2 ) readonly @endif name="mobile" value="{{ isset($userById->mobile) ? $userById->mobile : '' }}">
                            </div>
                            <div class="form-group">
                                        <label for="store_name">EMAIL ID</label>
                                        <input type="text" class="form-control" id="store_name" @if( Auth::user()->roleId == 2 ) readonly @endif name="email_id" value="{{ isset($userById->email) ? $userById->email : '' }}">
                            </div>
                            <div class="form-group">
                                        <label for="store_name">TELEGRAM ID</label>
                                        <input type="text" class="form-control" id="store_name" @if( Auth::user()->roleId == 2 ) readonly @endif name="telegram_id" value="{{ isset($userById->telegram_no) ? $userById->telegram_no : '' }}">
                            </div>
                            <div class="form-group">
                                        <label for="store_name">VIRTUAL ACCOUNT NUMBER</label>
                                        <input type="text" class="form-control" id="store_name" @if( Auth::user()->roleId == 2 ) readonly @endif  name="virtual_account_no" value="{{ isset($userById->va_account_number) ? $userById->va_account_number : '' }}">
                            </div>
                            <div class="form-group">
                                        <label for="store_name">IFSC CODE</label>
                                        <input type="text" class="form-control" id="store_name" @if( Auth::user()->roleId == 2 ) readonly @endif name="ifsc_code" value="{{ isset($userById->va_ifsc_code) ? $userById->va_ifsc_code : '' }}">
                            </div>
                            <div class="form-group">
                                        <label for="store_name">VIRTUAL UPI ADDRESS</label>
                                        <input type="text" class="form-control" id="store_name" @if( Auth::user()->roleId == 2 ) readonly @endif name="virtual_upi_address" value="{{ isset($userById->va_upi_id) ? $userById->va_upi_id : '' }}">
                            </div>
                            <div class="form-group">
                                        <label for="store_name">VIRTUAL ACCOUNT ID</label>
                                        <input type="text" class="form-control" id="virtual_account_id" @if( Auth::user()->roleId == 2 ) readonly @endif name="virtual_account_id" value="{{ isset($userById->va_id) ? $userById->va_id : '' }}">
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                        <label for="store_name">USERTYPE</label>
                                         <select class="form-control" @if( Auth::user()->roleId == 2 ) readonly @endif name="usertype">
                                            <option>Select UserType</option>
                                            @foreach($allRoles as $roles)
                                            <option value="{{$roles->roleId}}" @if($userById->roleId==$roles->roleId) Selected @endif>{{$roles->role}}</option>
                                            @endforeach
                                        </select>
                                       
                            </div>
                          
                            <div class="form-group">
                                         @php
                                        $parentname = DB::select( DB::raw("SELECT * FROM tbl_users WHERE userId = :somevariable"), array(
                                           'somevariable' => $userById->parent_user_id
                                         ));
                                         $parentnames = json_decode(json_encode($parentname), true);
                                         $parentnamefinal=$parentnames[0]['first_name'];
                                         
                                        @endphp
                                        <label for="store_name">PARENT USERTYPE</label>
                                        <!--<select class="form-control">
                                            <option>Select UserType</option>
                                            @foreach($allRoles as $roles)
                                            <option @if($userById->parent_role_id==$roles->roleId) Selected @endif>{{$roles->role}}</option>
                                            @endforeach
                                        </select>-->
                                         <select class="form-control" @if( Auth::user()->roleId == 2 ) readonly @endif id="type" name="parent_role_id">
                                            <option value="">--Select UserType--</option>
                                            <option value="4"  @if($userById->parent_role_id==4) Selected @endif>RETAILER</option>
                                            <option value="2"  @if($userById->parent_role_id==2) Selected @endif>DISTRIBUTOR</option>
                                            <option value="7"  @if($userById->parent_role_id==7) Selected @endif>MASTER DISTRIBUTOR</option>
                                            <option value="1"  @if($userById->parent_role_id==1) Selected @endif>ADMIN</option>
                                        </select>
                           
                            </div>
                            <div class="form-group" id="displaynow">
                                        <label for="store_name">PARENT USERNAME</label>
                                         @php
                                        $parentname = DB::select( DB::raw("SELECT * FROM tbl_users WHERE userId = :somevariable"), array(
                                           'somevariable' => $userById->parent_user_id
                                         ));
                                         $parentnames = json_decode(json_encode($parentname), true);
                                         $parentnamefinal=$parentnames[0]['first_name'];
                                         
                                        @endphp
                                        <select class="form-control" @if( Auth::user()->roleId == 2 ) readonly @endif name="parent_user_id" id="disabledthen">
                                            
                                            @foreach($allUser as $user)
                                            @if($userById->parent_role_id==$userById->roleId)
                                            <option value="{{$userById->userId}}" @if($userById->parent_user_id==$userById->userId) Selected @endif>{{$userById->first_name}}&nbsp;&nbsp;{{$userById->last_name}}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                        
                                        
                            </div>
                          <div class="form-group" id="displaythen" style="display:none">
                                        <label for="store_name">PARENT USERNAME</label>
                                         @php
                                        $parentname = DB::select( DB::raw("SELECT * FROM tbl_users WHERE userId = :somevariable"), array(
                                           'somevariable' => $userById->parent_user_id
                                         ));
                                         $parentnames = json_decode(json_encode($parentname), true);
                                         $parentnamefinal=$parentnames[0]['first_name'];
                                         
                                        @endphp
                                        <!--<select class="form-control">
                                            <option>Select PARENT USERNAME</option>
                                            @foreach($allUser as $user)
                                            <option @if($userById->parent_user_id==$userById->userId) Selected @endif>{{$userById->first_name}}&nbsp;&nbsp;{{$userById->last_name}}</option>
                                            @endforeach
                                        </select>-->
                                        <select name="parent_user_id" @if( Auth::user()->roleId == 2 ) readonly @endif class="form-control"  id="response">
                                        </select>
                                        
                            </div>
                            <div class="form-group">
                                <label for="store_name">FOS USER</label>
                                <input type="text" class="form-control" id="store_name" name="fos" value="{{ isset($userById->fos_id) ? $userById->fos_id : '' }}">
                            </div>
                               <div class="form-group">
                                        <label for="store_name">APPROVED PACKAGE</label>
                                       <select class="form-control" @if( Auth::user()->roleId == 2 ) readonly @endif name="package">
                                            <option>Select UserType</option>
                                            @foreach($allPackages as $packages)
                                            <option value="{{$packages->package_id}}" @if($userById->package_id==$packages->package_id) Selected @endif>{{$packages->package_name}}</option>
                                            @endforeach
                                        </select>
                            </div>
                               <div class="form-group">
                                        <label for="store_name">MINIMUM BALANCE</label>
                                        <input type="text" class="form-control" @if( Auth::user()->roleId == 2 ) readonly @endif id="store_name" name="min_balance" value="{{ isset($userById->min_balance) ? $userById->min_balance : '' }}">
                            </div>
                               <div class="form-group">
                                        <label for="store_name">MINIMUM WALLET LOAD</label>
                                        <input type="text" class="form-control" @if( Auth::user()->roleId == 2 ) readonly @endif id="store_name" name="min_amount_deposit" value="{{ isset($userById->min_amount_deposit) ? $userById->min_amount_deposit : '' }}">
                            </div>
                               <div class="form-group">
                                        <label for="store_name">MAXIMUM WALLET LOAD</label>
                                        <input type="text" class="form-control" @if( Auth::user()->roleId == 2 ) readonly @endif id="store_name" name="max_amount_deposit" value="{{ isset($userById->max_amount_deposit) ? $userById->max_amount_deposit : '' }}">
                            </div>
                        </div>
                        <div class="col-4" style="border:1px solid red;"><br>
                        
                            <center><h3 style="font-weight:700;">ACTIVATE / DE-ACTIVATE SERVICES</h3></center>
                            <hr style="border:1px solid red;">
                            <br>
                            <div class="row">
                            <div class="col-sm-6">
                                <table class="table table-bordered">
                                <tr>
                                    <th>SERVICES</th>
                                    <th>STATUS</th>
                                </tr>
                                 @foreach($allServices as $service_key => $service_value)
                            
                                    @php
                                    $results = DB::select( DB::raw("SELECT * FROM tbl_user_services WHERE user_id = :somevariable and service_id= :serviceid"), array(
                                       'somevariable' => $userById->userId,
                                       'serviceid' => $service_value->service_id
                                     ));
                                     $array = json_decode(json_encode($results), true);
                                    
                                     
                                    @endphp
                                    @if($service_value->service_id < 8 && $service_value->service_id !=2 && $service_value->service_id !=8 && $service_value->service_id !=11)
                                    <tr>
                                         
                                        <td>
                                          @if($service_value->service_name == 'MOBILE PREPAID')
                                            
                                           MOBILE RECHARGE
                                           @elseif($service_value->service_name == 'DTH')
                                            
                                           DTH RECHARGE
                                           @elseif($service_value->service_name == 'UPI TRANSFER')
                                            
                                           BHIM UPI TRANSFER
                                         
                                          @else
                                          {{strtoupper($service_value->service_name)}}
                                          @endif
                                        </td>
                                        <td>
                                           <input data-id="{{$userById->userId}}" @if( Auth::user()->roleId == 2 ) disabled @endif data-service_id="{{$service_value->service_id}}" class="toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="ON" data-off="OFF"  @if($array[0]['status']==1) checked  @else @endif>
                                        </td>
                                    </tr>
                                    @endif
                                   @endforeach
                                    
                                    
                            </table>
                            </div>
                            <div class="col-sm-6" style="margin-left:0px;">
                                <table class="table table-bordered">
                                <tr>
                                    <th>SERVICES</th>
                                    <th>STATUS</th>
                                </tr>
                                 @foreach($allServices as $service_key => $service_value)
                            
                                    @php
                                    $results = DB::select( DB::raw("SELECT * FROM tbl_user_services WHERE user_id = :somevariable and service_id= :serviceid"), array(
                                       'somevariable' => $userById->userId,
                                       'serviceid' => $service_value->service_id
                                     ));
                                     $array = json_decode(json_encode($results), true);
                                    
                                     
                                    @endphp
                                    @if($service_value->service_id > 8 && $service_value->service_id !=2 && $service_value->service_id !=8 && $service_value->service_id !=11 && $service_value->service_id !=12)
                                    <tr>
                                         
                                        <td>
                                            @if($service_value->service_name == 'MOBILE PREPAID')
                                            
                                           MOBILE RECHARGE
                                            @elseif($service_value->service_name == 'DTH')
                                            
                                           DTH RECHARGE
                                           @elseif($service_value->service_name == 'AEPS')
                                            
                                           BHIM UPI
                                          @else
                                          {{strtoupper($service_value->service_name)}}
                                          @endif
                                          
                                        </td>
                                        <td>
                                            <?php 
                                            echo Auth::user()->roleId;
                                            ?>
                                    <input data-id="{{$userById->userId}}" @if( Auth::user()->roleId == 2 ) disabled @endif data-service_id="{{$service_value->service_id}}" class="toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="ON" data-off="OFF" @if($array[0]['status']==1) checked  @else @endif>
                     
                                        </td>
                                    </tr>
                                    @endif
                                   @endforeach


                                    
                                    
                            </table>
                            </div>
                            </div>

                            </div>
                            
                        <div class="col-3" style="border:1px solid red;margin-left:30px;">
                            <br>
                            <center><h3 style="font-weight:700;">PAYMENT GATEWAY &nbsp;&nbsp;
                            <input type="checkbox" style="margin-top:-10px;" name="pg_status" value="1" @if($userById['pg_status'] == '1') checked @endif>
                                              
                                              
                                              </h3></center>
                            <hr style="border:1px solid red;">
                            <br>
                            <table class="table table-bordered">
                                <tr>
                                    <th>MODE</th>
                                    <th>CHARGE</th>
                                    <th>STATUS</th>
                                </tr>
                                @php $userPg = $userById['pg_options'];
                                if($userPg != "") {
                                    $userPg = json_decode($userPg,true);
                                } else {
                                    $userPg = "";
                                }
                                @endphp
                                @if(is_array($userPg))
                                @foreach($userPg as $pgOptions)
                                <tr>
                                    <td>{{ str_replace('_',' ',$pgOptions['mode']) }}</td>
                                    <td><input type="text" name="{{ strtolower($pgOptions['mode']) }}_charge" @if( Auth::user()->roleId == 2 ) readonly @endif class="form-control" value="{{ $pgOptions['charge'] }}"></td>
                                    <td><input type="checkbox" name="{{ strtolower($pgOptions['mode']) }}_status" @if( Auth::user()->roleId == 2 ) readonly @endif class="form-check-input success" value="1" @if($pgOptions['status'] == '1') checked @endif ></td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td>UPI</td>
                                    <td><input type="text" name="upi_charge" @if( Auth::user()->roleId == 2 ) readonly @endif class="form-control" value=""></td>
                                    <td><input type="checkbox"  @if( Auth::user()->roleId == 2 ) disabled @endif name="upi_status" class="form-check-input success" value="1"></td>
                                </tr>
                                <tr>
                                    <td>RUPAY CARD</td>
                                    <td><input type="text" @if( Auth::user()->roleId == 2 ) readonly @endif name="rupay_card_charge" class="form-control" value=""></td>
                                    <td><input type="checkbox" @if( Auth::user()->roleId == 2 ) disabled @endif name="rupay_card_status" class="form-check-input success" value="1"></td>
                                </tr>
                                <tr>
                                    <td>DEBIT CARD</td>
                                    <td><input type="text" @if( Auth::user()->roleId == 2 ) readonly @endif name="credit_card_charge" class="form-control" value=""></td>
                                    <td><input type="checkbox" @if( Auth::user()->roleId == 2 ) disabled @endif name="credit_card_status" class="form-check-input success" value="1"></td>
                                </tr>
                                <tr>
                                    <td>CREDIT CARD</td>
                                    <td><input type="text" @if( Auth::user()->roleId == 2 ) readonly @endif name="debit_card_charge" class="form-control" value=""></td>
                                    <td><input type="checkbox" @if( Auth::user()->roleId == 2 ) disabled @endif name="debit_card_status" class="form-check-input success" value="1"></td>
                                </tr>
                                <tr>
                                    <td>PREPAID CARD</td>
                                    <td><input type="text" @if( Auth::user()->roleId == 2 ) readonly @endif name="prepaid_card_charge" class="form-control" value=""></td>
                                    <td><input type="checkbox" name="prepaid_card_status" @if( Auth::user()->roleId == 2 ) disabled @endif class="form-check-input success" value="1"></td>
                                </tr>
                                <tr>
                                    <td>CORPORATE CARD</td>
                                    <td><input type="text" @if( Auth::user()->roleId == 2 ) readonly @endif name="corporate_card_charge" class="form-control" value=""></td>
                                    <td><input type="checkbox" @if( Auth::user()->roleId == 2 ) disabled @endif name="corporate_card_status" class="form-check-input success" value="1"></td>
                                </tr>
                                <tr>
                                    <td>WALLET</td>
                                    <td><input type="text" @if( Auth::user()->roleId == 2 ) readonly @endif name="wallet_charge" class="form-control" value=""></td>
                                    <td><input type="checkbox" @if( Auth::user()->roleId == 2 ) disabled @endif name="wallet_status" class="form-check-input success" value="1"></td>
                                </tr>
                                <tr>
                                    <td>NET BANKING</td>
                                    <td><input type="text"  @if( Auth::user()->roleId == 2 ) readonly @endif name="net_banking_charge" class="form-control" value=""></td>
                                    <td><input type="checkbox" @if( Auth::user()->roleId == 2 ) disabled @endif name="net_banking_status" class="form-check-input success" value="1"></td>
                                </tr>
                                
                                <!--<tr>-->
                                    
                                <!--    <td>UPI MODE</td>-->
                                <!--    <td>0%</td>-->
                                <!--     <td>-->
                                <!--            @if($array[0]['status']==1)-->
                                                
                                <!--            <input checked id="status-btn_" class="status-btn" type="checkbox"-->
                                <!--             data-id="{{ $userById->userId }}" data-on="Active" data-off="Inactive" data-onstyle="success"-->
                                <!--              data-toggle="toggle" data-width="90" data-style="ios" data-style="slow"-->
                                <!--              {{ (Auth::userRoleAlias() != Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN')) ? 'disabled' : '' }}>-->
                                <!--        @else-->
                                <!--            <input id="status-btn_" class="status-btn" type="checkbox"-->
                                <!--             data-id="{{ $userById->userId }}" data-on="Active" data-off="Inactive" data-onstyle="success"-->
                                <!--              data-toggle="toggle" data-width="90" data-style="ios" data-style="slow"-->
                                <!--              {{ (Auth::userRoleAlias() != Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN')) ? 'disabled' : '' }}>-->
                                <!--        @endif-->
                                            
                                <!--        </td>-->
                                <!--</tr>-->
                                <!--<tr>-->
                                <!--    <td>RUPAY CARD</td>-->
                                <!--    <td>0%</td>-->
                                <!--     <td>-->
                                <!--            @if($array[0]['status']==1)-->
                                                
                                <!--            <input checked id="status-btn_" class="status-btn" type="checkbox"-->
                                <!--             data-id="{{ $userById->userId }}" data-on="Active" data-off="Inactive" data-onstyle="success"-->
                                <!--              data-toggle="toggle" data-width="90" data-style="ios" data-style="slow"-->
                                <!--              {{ (Auth::userRoleAlias() != Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN')) ? 'disabled' : '' }}>-->
                                <!--        @else-->
                                <!--            <input id="status-btn_" class="status-btn" type="checkbox"-->
                                <!--             data-id="{{ $userById->userId }}" data-on="Active" data-off="Inactive" data-onstyle="success"-->
                                <!--              data-toggle="toggle" data-width="90" data-style="ios" data-style="slow"-->
                                <!--              {{ (Auth::userRoleAlias() != Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN')) ? 'disabled' : '' }}>-->
                                <!--        @endif-->
                                            
                                <!--        </td>-->
                                <!--</tr>-->
                                <!--<tr>-->
                                <!--    <td>PAYTM WALLET</td>-->
                                <!--    <td>2%</td>-->
                                <!--     <td>-->
                                <!--            @if($array[0]['status']==1)-->
                                                
                                <!--            <input checked id="status-btn_" class="status-btn" type="checkbox"-->
                                <!--             data-id="{{ $userById->userId }}" data-on="Active" data-off="Inactive" data-onstyle="success"-->
                                <!--              data-toggle="toggle" data-width="90" data-style="ios" data-style="slow"-->
                                <!--              {{ (Auth::userRoleAlias() != Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN')) ? 'disabled' : '' }}>-->
                                <!--        @else-->
                                <!--            <input id="status-btn_" class="status-btn" type="checkbox"-->
                                <!--             data-id="{{ $userById->userId }}" data-on="Active" data-off="Inactive" data-onstyle="success"-->
                                <!--              data-toggle="toggle" data-width="90" data-style="ios" data-style="slow"-->
                                <!--              {{ (Auth::userRoleAlias() != Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN')) ? 'disabled' : '' }}>-->
                                <!--        @endif-->
                                            
                                <!--        </td>-->
                                <!--</tr>-->
                                <!--<tr>-->
                                <!--    <td>DEBIT CARD</td>-->
                                <!--    <td>2%</td>-->
                                <!--     <td>-->
                                <!--            @if($array[0]['status']==1)-->
                                                
                                <!--            <input checked id="status-btn_" class="status-btn" type="checkbox"-->
                                <!--             data-id="{{ $userById->userId }}" data-on="Active" data-off="Inactive" data-onstyle="success"-->
                                <!--              data-toggle="toggle" data-width="90" data-style="ios" data-style="slow"-->
                                <!--              {{ (Auth::userRoleAlias() != Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN')) ? 'disabled' : '' }}>-->
                                <!--        @else-->
                                <!--            <input id="status-btn_" class="status-btn" type="checkbox"-->
                                <!--             data-id="{{ $userById->userId }}" data-on="Active" data-off="Inactive" data-onstyle="success"-->
                                <!--              data-toggle="toggle" data-width="90" data-style="ios" data-style="slow"-->
                                <!--              {{ (Auth::userRoleAlias() != Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN')) ? 'disabled' : '' }}>-->
                                <!--        @endif-->
                                            
                                <!--        </td>-->
                                <!--</tr>-->
                                <!--<tr>-->
                                <!--    <td>CREDIT CARD</td>-->
                                <!--    <td>2%</td>-->
                                <!--     <td>-->
                                <!--            @if($array[0]['status']==1)-->
                                                
                                <!--            <input checked id="status-btn_" class="status-btn" type="checkbox"-->
                                <!--             data-id="{{ $userById->userId }}" data-on="Active" data-off="Inactive" data-onstyle="success"-->
                                <!--              data-toggle="toggle" data-width="90" data-style="ios" data-style="slow"-->
                                <!--              {{ (Auth::userRoleAlias() != Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN')) ? 'disabled' : '' }}>-->
                                <!--        @else-->
                                <!--            <input id="status-btn_" class="status-btn" type="checkbox"-->
                                <!--             data-id="{{ $userById->userId }}" data-on="Active" data-off="Inactive" data-onstyle="success"-->
                                <!--              data-toggle="toggle" data-width="90" data-style="ios" data-style="slow"-->
                                <!--              {{ (Auth::userRoleAlias() != Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN')) ? 'disabled' : '' }}>-->
                                <!--        @endif-->
                                            
                                <!--        </td>-->
                                <!--</tr>-->
                                <!--<tr>-->
                                <!--    <td>NET BANKING</td>-->
                                <!--    <td>2.99%</td>-->
                                <!--     <td>-->
                                <!--            @if($array[0]['status']==1)-->
                                                
                                <!--            <input checked id="status-btn_" class="status-btn" type="checkbox"-->
                                <!--             data-id="{{ $userById->userId }}" data-on="Active" data-off="Inactive" data-onstyle="success"-->
                                <!--              data-toggle="toggle" data-width="90" data-style="ios" data-style="slow"-->
                                <!--              {{ (Auth::userRoleAlias() != Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN')) ? 'disabled' : '' }}>-->
                                <!--        @else-->
                                <!--            <input id="status-btn_" class="status-btn" type="checkbox"-->
                                <!--             data-id="{{ $userById->userId }}" data-on="Active" data-off="Inactive" data-onstyle="success"-->
                                <!--              data-toggle="toggle" data-width="90" data-style="ios" data-style="slow"-->
                                <!--              {{ (Auth::userRoleAlias() != Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN')) ? 'disabled' : '' }}>-->
                                <!--        @endif-->
                                            
                                <!--        </td>-->
                                <!--</tr>-->
                                @endif
                                <tr>
                                    <td>VIRTUAL COLLECT</td>
                                    <td>0%</td>
                                     <td>
                                            @if($array[0]['status']==1)
                                                
                                            <input checked id="status-btn_" class="status-btn" type="checkbox"
                                             data-id="{{ $userById->userId }}" data-on="Active" data-off="Inactive" data-onstyle="success"
                                              data-toggle="toggle" data-width="90" data-style="ios" data-style="slow"
                                              {{ (Auth::userRoleAlias() != Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN')) ? 'disabled' : '' }}>
                                        @else
                                            <input id="status-btn_" class="status-btn" type="checkbox"
                                             data-id="{{ $userById->userId }}" data-on="Active" data-off="Inactive" data-onstyle="success"
                                              data-toggle="toggle" data-width="90" data-style="ios" data-style="slow"
                                              {{ (Auth::userRoleAlias() != Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN')) ? 'disabled' : '' }}>
                                        @endif
                                            
                                        </td>
                                </tr>
                            </table>
                            <div class="form-group">
                                        <label for="store_name">QR CODE URL</label>
                                        <button style="width: 100%;height: 38px;font-size: 19px;" type="button" class="btn btn-success btn-sm" id="viewqr"  data-toggle="modal" data-target="#qrmodal" data-email="{{ $userById->id }}">View</button>
                                        <!--<input type="text" class="form-control" id="store_name" name="store_name" value="{{ isset($userById->first_name) ? $userById->first_name : '' }}">-->
                            </div>
                            <div class="form-group">
                                        <label for="store_name">AUTHORIZE CERTIFICATE URL</label>
                                        <button style="width: 100%;height: 38px;font-size: 19px;" type="button" class="btn btn-success btn-sm" id="viewautho"  data-toggle="modal" data-target="#outsidemodal" data-email="{{ $userById->id }}">View</button>
                                        <!--<input type="text" class="form-control" id="store_name" name="store_name" value="{{ isset($userById->first_name) ? $userById->first_name : '' }}">-->
                            </div>
                        </div>
                        
                        </div>
                        </div>
                      <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-success mr-2">{{ isset($userById->userId) ? 'Update' : 'Submit' }}</button>
                            @if(Auth::user()->roleId == Config::get('constants.ADMIN'))
                                <a type="button" href="{{ route('user_list') }}" class="btn btn-dark">Cancel</a>
                            @else
                                <a type="button" href="{{ route('home') }}" class="btn btn-dark">Cancel</a>
                            @endif
                        </div>
                    </div>
                </form>
                
                <div class="modal fade" id="qrmodal" role="dialog">
                    <div class="modal-dialog">
                    
                      <!-- Modal content-->
                      <div class="modal-content">
                        
                        <div class="modal-body">
                            <div class="row">
                                @php
                                    $qr_id = $userById['qr_id'];
                                @endphp
                                
                                @if($qr_id != "")
                                <div class="col-sm-12">
                                    <img src="https://paymamaapp.in/qr/{{$qr_id}}" alt="QR Code" style="height:auto;width:100%">
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="modal-footer">
                            <a href="https://paymamaapp.in/qr/{{$qr_id}}" download="paymmama_qr_code" class="btn btn-primary">Download</a>
                            <form method="post" action="{{ route('updateuserqr',isset($userById->userId) ? $userById->userId : '') }}">
                                @csrf
                                <input type="hidden" name="qr_id" value="{{$qr_id}}">
                                <input type="hidden" name="user_id" value="{{$userById['userId']}}">
                                <button type="submit" class="btn btn-primary submit-btn">Regenerate</button>
                            </form>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                      </div>
                      
                    </div>
                  </div>

            </div>
        </div>
    </div>
    <style>
        .form-check-input {
            width: 1.375em;
            height: 1.375em;
            margin-top: .0625em;
            vertical-align: top;
            background-color: #eaedf5;
            background-repeat: no-repeat;
            background-position: center;
            background-size: contain;
            border: 0;
            appearance: none;
            color-adjust: exact
        }
        .form-check-input[type=checkbox] {
            border-radius: 4px
        }
        .form-check-input:active {
            filter: brightness(90%)
        }
        .form-check-input:focus {
            border-color: rgba(0, 0, 0, .25);
            outline: 0;
            box-shadow: 0 0 0 0 transparent
        }
        .form-check-input:checked {
            background-color: #2cabe3;
            border-color: #2cabe3
        }
        .form-check-input:checked[type=checkbox] {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M6 10l3 3l6-6'/%3e%3c/svg%3e")
        }
        .form-control{
            height:41px !important;
        }
        label
        {
            font-weight:bold;
        }
    </style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" ></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css"  />
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

                                   <script>
                              $(function() {
                                $('.toggle-class').change(function() {
                                    var status = $(this).prop('checked') == true ? 0 : 1; 
                                    var user_id = $(this).data('id'); 
                                    var service_id = $(this).data('service_id'); 
                                    
                                    $.ajax({
                                        type: "GET",
                                        dataType: "json",
                                        url: '/changeStatus',
                                        data: {'status': status, 'user_id': user_id,'service_id' : service_id},
                                        success: function(data){
                                            alert(data);
                                          console.log(data.success)
                                        }
                                    });
                                })
                              })
                              
                              $(function() {
                                $('#updatebank').click(function() {
                                    var user_id = $('#ids').val();
                                    var full_name = $('#full_name').val(); 
                                    var bank_account_no = $('#bank_account_no').val(); 
                                    var bank_name = $('#bank_name').val(); 
                                    var ifsc_code = $('#ifsc_code').val(); 
                                    var branch_name = $('#branch_name').val(); 
                                    
                                    $.ajax({
                                        type: "GET",
                                        dataType: "json",
                                        url: '/updatebankdetails',
                                        data: {'user_id': user_id, 'full_name': full_name,'bank_account_no' : bank_account_no,'ifsc_code' : ifsc_code,'branch_name' : branch_name,'bank_name' : bank_name},
                                        success: function(data){
                                            alert(data);
                                          console.log(data.success)
                                        }
                                    });
                                })
                              })
                            </script>
                            <script>
                              $(function() {
                                  check_parent_user();
                                $('#type').change(function() {
                                    check_parent_user();
                                    
                                })
                              })
                              function check_parent_user() {
                                  var role_id = $('#type').val(); 
                                    $.ajax({
                                          url: "{{ url('checkperroleforedituser') }}",
                                          method: 'get',
                                          data: {
                                             role: role_id
                                            
                                          },
                                          success: function(result){
                                              
                                             //console.log(result);
                                             $("#response").empty();
                                             $('#displaynow').hide();
                                             $("#disabledthen").attr("disabled", true)
                                             $('#displaythen').show();
                                             document.getElementById('displaythen').style.display = "block";
                                             // RESULT
                                              
                                                $.each(result,function(index,value,username){
                                                    var splitted = value.split("-"); 
                                                    $("#response").append('<option value="'+splitted[0]+'" name="days[]">'+splitted[1]+'</option>');
                                                });
                                             
                                          }});
                              }
                        </script>
</section>
</div>
<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script src="https://paymamaapp.in/template_assets/other/js/bootstrap-toggle.min.js"></script>
<script src="{{ asset('template_assets\other\js\angular.min.js') }}"></script>
<script src="{{ asset('dist/user/js/addUser.js') }}"></script>

@endsection
