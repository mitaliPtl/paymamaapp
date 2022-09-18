@extends('layouts.full_new')
@section('page_content')

<div class="page-content container-fluid">
    <link rel="stylesheet" type="text/css" href="{{ asset('template_assets/other/css/bootstrap-toggle.min.css') }}">
        
    <section ng-app="myApp" ng-controller="addEditUserCtrl">
        <div class="row">
            <div class="col-12">
                <div class="">
                    <form method="post" action="{{ route('update_userekyc',isset($user->ekyc->user_id) ? $user->ekyc->user_id : '') }}" id="editUserForm">
                    @csrf
                        <input type="hidden" class="form-control" id="ids" name="ids" value="{{ isset($user->ekyc->user_id) ? $user->ekyc->user_id : '' }}">
                        <div class="row" style="">
                            <div class="col-2" style="padding:10px;">
                                <div class="card card-body">
                                    <center><h4 style="font-weight:bold;">AADHAAR INFORMATION</h4></center>
                                    <hr style="border:1px solid red;">
                                    <div class="form-group" style="height:100px;width:100%;">
                                        <img src="{{asset('template_assets/Aadhaar_card.png')}}" style="height:100px;width:100%;">
                                    </div>
                                    <div class="form-group">
                                        <label for="aadhaar_name">AADHAAR HOLDER NAME</label>
                                        <input type="text" class="form-control" id="aadhaar_name" name="aadhaar_name" value="{{ isset($user->ekyc->aadhaar_name) ? $user->ekyc->aadhaar_name : '' }}" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label for="aadhaar_no">AADHAAR NUMBER</label>
                                        <input type="text" class="form-control" id="aadhaar_no" name="aadhaar_no" value="{{ isset($user->ekyc->aadhaar_no) ? $user->ekyc->aadhaar_no : '' }}" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label for="mobile">MOBILE</label>
                                        <input type="text" class="form-control" id="mobile" name="mobile" value="{{ isset($user->ekyc->mobile) ? $user->ekyc->mobile : '' }}" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label for="share_code">SHARE CODE</label>
                                        <input type="text" class="form-control" id="share_code" value="{{ isset($user->ekyc->share_code) ? $user->ekyc->share_code : '' }}" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label for="aadhaar_address">AADHAAR ADDRESS</label>
                                        <textarea class="form-control" id="aadhaar_address" name="aadhaar_address" rows="4" style="width:100%;height:100px !important;" disabled>{{ isset($user->ekyc->aadhaar_address) ? $user->ekyc->aadhaar_address : '' }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="aadhaar_kyc">AADHAAR STATUS</label>
                                        <select id="aadhaar_kyc" name="aadhaar_kyc" class="form-control">
                                            <option value="0" @if($user->ekyc->aadhaar_kyc=='0') Selected @endif>Not Submitted</option>
                                            <option value="1" @if($user->ekyc->aadhaar_kyc=='1') Selected @endif>Pending</option>
                                            <option value="2" @if($user->ekyc->aadhaar_kyc=='2') Selected @endif>Verified</option>
                                            <option value="3" @if($user->ekyc->aadhaar_kyc=='3') Selected @endif>Rejected</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-2" style="padding:10px;height: 669px;">
                                <div class=" card card-body" >
                                    <center><h4 style="font-weight:bold;">PAN CARD INFORMATION</h4></center>
                                    <hr style="border:1px solid red;">
                                    <div class="form-group" style="height:100px;width:100%;">
                                        <img src="{{asset('template_assets/Pan_card.png')}}" style="height:100px;width:100%;">
                                    </div>
                                    <div class="form-group">
                                        <label for="pan_name">PAN HOLDER NAME</label>
                                        <input type="text" class="form-control" id="pan_name" name="pan_name" value="{{ isset($user->ekyc->pan_name) ? $user->ekyc->pan_name : '' }}" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label for="pan_no">PAN NUMBER</label>
                                        <input type="text" class="form-control" id="pan_no" name="pan_no" value="{{$user->ekyc->pan_no}}" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label for="store_name">PAN IMAGE</label>
                                        <button style="width: 100%;height: 38px;font-size: 19px;" type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#panmodal" data-email="{{ $user->ekyc->id }}">View</button>
                                    </div>
                                    <div class="modal fade" id="panmodal" role="dialog">
                                            <div class="modal-dialog">
                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            @php
                                                            $pan_id = $user['ekyc']['pan_file'];
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
                                    
                                    @php
                                    $aadhar_file = $user['ekyc']['zip_file'];
                                    $resultsfront = DB::select( DB::raw("SELECT * FROM tbl_files WHERE id = :somevariable"), array(
                                        'somevariable' => $aadhar_file,
                                    ));
                                    $aadhar_file='';
                                    foreach ($resultsfront as $values) 
                                    {
                                        $aadhar_file = $values->file_path;
                                    }
                                    @endphp
                                    <div class="form-group">
                                        <label for="store_name">DOWNLOAD AADHAAR ZIP</label>
                                        <a href="https://paymamaapp.in/public{{ $aadhar_file }}" style="width: 100%;height: 38px;font-size: 19px;" role="button" class="btn btn-success btn-sm">View</a>
                                    </div>
                                    <div class="form-group">
                                        <label for="store_name">AADHAAR IMAGE</label>
                                        <button style="width: 100%;height: 38px;font-size: 19px;" type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#aadharmodal">View</button>
                                    </div>
                                    <div class="modal fade" id="aadharmodal" role="dialog">
                                        <div class="modal-dialog">
                                            <!-- Modal content-->
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <div class="row">
                                                        @php
                                                        $aadhar_front = $user['ekyc']['aadhaar_image'];
                                                        $resultsfront = DB::select( DB::raw("SELECT * FROM tbl_files WHERE id = :somevariable"), array(
                                                            'somevariable' => $aadhar_front,
                                                        ));
                                                        $aadhar='';
                                                        foreach ($resultsfront as $values) 
                                                        {
                                                            $aadhar = $values->file_path;
                                                        }
                                                        @endphp
                                                        <div class="col-sm-12">
                                                            <img src="https://paymamaapp.in/public{{ $aadhar }}" style="height:420px;width:100%;">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="pan_kyc">PAN STATUS</label>
                                        <select id="pan_kyc" name="pan_kyc" class="form-control">
                                            <option value="0" @if($user->ekyc->pan_kyc=='0') Selected @endif>Not Submitted</option>
                                            <option value="1" @if($user->ekyc->pan_kyc=='1') Selected @endif>Pending</option>
                                            <option value="2" @if($user->ekyc->pan_kyc=='2') Selected @endif>Verified</option>
                                            <option value="3" @if($user->ekyc->pan_kyc=='3') Selected @endif>Rejected</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-2" style="padding:10px;">
                                <div class="card card-body">
                                    <center><h4 style="font-weight:bold;">BANK INFORMATION</h4></center>
                                    <hr style="border:1px solid red;">
                                    <div class="form-group" style="height:100px;width:100%;">
                                        <img src="{{asset('template_assets/Bank_Verification.png')}}" style="height:100px;width:100%;">
                                    </div>
                                    <div class="form-group">
                                        <label for="acc_name">ACCOUNT HOLDER NAME</label>
                                        <input type="text" class="form-control" id="acc_name" name="acc_name" value="{{ isset($user->ekyc->acc_name) ? $user->ekyc->acc_name : '' }}">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="acc_no">ACCOUNT NUMBER</label>
                                        <input type="text" class="form-control" id="acc_no" name="acc_no" value="{{ isset($user->ekyc->acc_no) ? $user->ekyc->acc_no : '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="bank_name">BANK NAME</label>
                                        <input type="text" class="form-control" id="bank_name" name="bank_name" value="{{ isset($user->ekyc->bank_name) ? $user->ekyc->bank_name : '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="ifsc_code">IFSC CODE</label>
                                        <input type="text" class="form-control" id="ifsc_code" name="ifsc_code" value="{{ isset($user->ekyc->ifsc_code) ? $user->ekyc->ifsc_code : '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="branch_name">BRANCH NAME</label>
                                        <input type="text" class="form-control" id="branch_name" name="branch_name" value="{{ isset($user->ekyc->branch_name) ? $user->ekyc->branch_name : '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="bank_kyc">BANK STATUS</label>
                                        <select id="bank_kyc" name="bank_kyc" class="form-control">
                                            <option value="0" @if($user->ekyc->bank_kyc=='0') Selected @endif>Not Submitted</option>
                                            <option value="1" @if($user->ekyc->bank_kyc=='1') Selected @endif>Pending</option>
                                            <option value="2" @if($user->ekyc->bank_kyc=='2') Selected @endif>Verified</option>
                                            <option value="3" @if($user->ekyc->bank_kyc=='3') Selected @endif>Rejected</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-2" style="padding:10px;">
                                <div class=" card card-body" >
                                    <center><h4 style="font-weight:bold;">SELFIE INFORMATION</h4></center>
                                    <hr style="border:1px solid red;">
                                    <div class="form-group" style="border:1px solid black;height:190px;width:100%;border-radius:50%;">
                                        @php
                                        $selfie_id = $user['ekyc']['selfie_image'];
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
                                    <div class="form-group">
                                        <label for="aadhar_no">LATITUDE & LONGITUDE</label>
                                        <a target="_blank" href="https://maps.google.com/?q={{ $user->ekyc->latitude }},{{$user->ekyc->longitude}}"><button style="width: 100%;height: 38px;font-size: 19px;" type="button" class="btn btn-success btn-sm">View</button></a>
                                    </div>
                                    <div class="form-group">
                                        <label for="store_name">SHOP LATITUDE & LONGITIDE</label>
                                        <a target="_blank" href="https://maps.google.com/?q={{ $user->ekyc->blat }},{{$user->ekyc->blong}}"><button style="width: 100%;height: 38px;font-size: 19px;" type="button" class="btn btn-success btn-sm" >View</button></a>
                                    </div>
                                    <div class="form-group">
                                        <label for="store_name">SHOP INSIDE IMAGE</label>
                                        <button style="width: 100%;height: 38px;font-size: 19px;" type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#insidemodal">View</button>
                                    </div>
                                    <div class="form-group">
                                        <label for="store_name">SHOP OUTSIDE IMAGE</label>
                                        <button style="width: 100%;height: 38px;font-size: 19px;" type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#outsidemodal">View</button>
                                    </div>
                                    <div class="form-group">
                                        <label for="selfie_kyc">SELFIE STATUS</label>
                                        <select id="selfie_kyc" name="selfie_kyc" class="form-control">
                                            <option value="0" @if($user->ekyc->selfie_kyc=='0') Selected @endif>Not Submitted</option>
                                            <option value="1" @if($user->ekyc->selfie_kyc=='1') Selected @endif>Pending</option>
                                            <option value="2" @if($user->ekyc->selfie_kyc=='2') Selected @endif>Verified</option>
                                            <option value="3" @if($user->ekyc->selfie_kyc=='3') Selected @endif>Rejected</option>
                                        </select>
                                    </div>
                                </div>  
                            </div>
                            <!--Shop inside modal start here-->
                            <div class="modal fade" id="insidemodal" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <div class="row">
                                                @php
                                                $inside_image = $user['ekyc']['inside_image'];
                                                $resultsselfie = DB::select( DB::raw("SELECT * FROM tbl_files WHERE id = :somevariable"), array(
                                                    'somevariable' => $inside_image,
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
                            <!--Shop Outside Modal Start here-->
                            <div class="modal fade" id="outsidemodal" role="dialog">
                                <div class="modal-dialog">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <div class="row">
                                                @php
                                                $front_image = $user['ekyc']['front_image'];
                                                $resultsselfie = DB::select( DB::raw("SELECT * FROM tbl_files WHERE id = :somevariable"), array(
                                                   'somevariable' => $front_image,
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
                                  
                            <div class="col-2" style="padding:10px;">
                               <div class=" card card-body" >
                                   <center><h4 style="font-weight:bold;">BUSINESS INFORMATION</h4></center>
                                   <hr style="border:1px solid red;">
                                    <div class="form-group">
                                        <label for="business_name">BUSINESS NAME</label>
                                        <input type="text" class="form-control" id="business_name" name="business_name" value=" {{ isset($user->ekyc->business_name) ? $user->ekyc->business_name : '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="business_address">BUSINESS ADDRESS</label>
                                        <textarea class="form-control" name="business_address" rows="5" style="width:100%;height:90px !important;">{{ isset($user->ekyc->business_address) ? $user->ekyc->business_address : '' }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="store_name">STATE</label>
                                        <select name="state" class="form-control">
                                            @foreach($allStates as $states)
                                            <option value="{{$states->state_id}}" @if($user->ekyc->state==$states->state_name) Selected @endif>{{$states->state_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="city">CITY</label>
                                        <input type="text" class="form-control" id="city" name="city" value=" {{  $user->ekyc->city  }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="pincode">PINCODE</label>
                                        <input type="text" class="form-control" id="pincode" name="pincode" value="{{  $user->ekyc->pincode  }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="category">BUSINESS CATEGORY</label>
                                        <select name="category" class="form-control">
                                            @foreach($allCat as $cat)
                                            <option value="{{$cat->store_category_name}}" @if($user->ekyc->category==$cat->store_category_name) Selected @endif>{{$cat->store_category_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="business_kyc">BUSINESS STATUS</label>
                                        <select id="business_kyc" name="business_kyc" class="form-control">
                                            <option value="0" @if($user->ekyc->business_kyc=='0') Selected @endif>Not Submitted</option>
                                            <option value="1" @if($user->ekyc->business_kyc=='1') Selected @endif>Pending</option>
                                            <option value="2" @if($user->ekyc->business_kyc=='2') Selected @endif>Verified</option>
                                            <option value="3" @if($user->ekyc->business_kyc=='3') Selected @endif>Rejected</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-success" style="font-size: 20px;" id="submitEkyc">Submit</button>
                    </form>
                </div>
            </div>
        </div>
        <div id="errorModal" class="modal" tabindex="-1" role="document">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body" style="text-align: center;padding-left: 20px;padding-right: 20px;">
                        <i class="fas fa-times text-danger" style="font-size: 100px;line-height: 1.1;margin-top: 20px;display: inline-block !important;"></i>
                        <h2>Error!</h2>
                        <h4>Unable to Update</h4>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div id="successModal" class="modal" tabindex="-1" role="document">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body" style="text-align: center;padding-left: 20px;padding-right: 20px;">
                        <i class="far fa-check-circle text-success" style="font-size: 100px;line-height: 1.1;margin-top: 20px;margin-bottom: 20px;display: inline-block !important;"></i>
                        <h2>Success!</h2>
                        <h4>KYC Updated successful</h4>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Close</button>
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
    </section>
</div>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" ></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css"/>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script src="{{ asset('template_assets/other/js/bootstrap-toggle.min.js') }}"></script>
<script src="{{ asset('template_assets/other/js/angular.min.js') }}"></script>
<!--<script src="{{ asset('dist/user/js/addUser.js') }}"></script>-->

<script>
$(document).ready(function () {
    $(document).on("click", "#submitEkyc", function() {
        $('.preloader').css("display", "block");
        var user_id = $('input[id=ids]').val();
        var aadhaar_kyc = $('select[id=aadhaar_kyc]').val();
        var pan_kyc = $('select[id=pan_kyc]').val();
        var bank_kyc = $('select[id=bank_kyc]').val();
        var business_kyc = $('select[id=business_kyc]').val();
        var selfie_kyc = $('select[id=selfie_kyc]').val();
        var CSRF_TOKEN = '{{ csrf_token() }}';
        $.ajax({
            type:'POST',
            url: '{{ route("update_userekyc") }}',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            },
            dataType: "json",
            data: { _token: CSRF_TOKEN,user_id: user_id,aadhaar_kyc: aadhaar_kyc,pan_kyc: pan_kyc,bank_kyc: bank_kyc,business_kyc: business_kyc,selfie_kyc: selfie_kyc},
            success:function(resp) {
                $('.preloader').css("display", "none");
                if(resp.status) {
                    $('#successModal').modal('show');
                } else {
                    $('#errorModal').modal('show');
                }
            },
            error:function(resp){
                $('.preloader').css("display", "none");
                $('#errorModal').modal('show');
            }
        });
    });
});
</script>

@endsection
