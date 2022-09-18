{{-- @extends('layouts.full') --}}
@extends('layouts.full_new')
@section('page_content')



<div class="page-content container-fluid">
    <!--<link rel="stylesheet" type="text/css" href="https://paymamaapp.in/template_assets/other/css/bootstrap-toggle.min.css">-->
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
            
                    <form method="post" action="{{ route('updateretailerid',isset($userById->userId) ? $userById->userId : '') }}" id="editUserForm">
                
                @csrf
                <input type="hidden" class="form-control" id="ids" name="ids" value="{{ isset($userById->userId) ? $userById->userId : '' }}">
                    <input type="hidden" class="form-control" id="ids" name="source" value="0">
                    <div class="row" style="">
                                <div class="col-6">
                                    <div class="card card-body" style="">
                                        <center><h4 style="font-weight:bold;">PERSONAL INFORMATION</h4></center>
                                    <hr style="border:1px solid red;">
                                    
                            <div class="form-group">
                                        <label for="store_name">USER FULL NAME</label>
                                        <input type="text" class="form-control" id="store_name" name="" value="{{ isset($userById->bank_account_name) ? $userById->bank_account_name : '' }}" disabled>
                            </div>
                            <div class="form-group">
                                        <label for="store_name">MOBILE NUMBER</label>
                                        <input type="text" class="form-control" id="store_name" name="mobile" value="{{ isset($userById->mobile) ? $userById->mobile : '' }}" disabled>
                            </div>
                            <div class="form-group">
                                        <label for="store_name">EMAIL ID</label>
                                        <input type="text" class="form-control" id="store_name" name="email_id" value="{{ isset($userById->email) ? $userById->email : '' }}"  disabled>
                            </div>
                            <div class="form-group">
                                        <label for="store_name">TELEGRAM ID</label>
                                        <input type="text" class="form-control" id="store_name" name="telegram_id" value="{{ isset($userById->telegram_no) ? $userById->telegram_no : '' }}">
                            </div>
                            <div class="form-group">
                                        <label for="store_name">VIRTUAL ACCOUNT NUMBER</label>
                                        <input type="text" class="form-control" id="store_name" name="virtual_account_no" value="{{ isset($userById->va_account_number) ? $userById->va_account_number : '' }}"  disabled>
                            </div>
                            <div class="form-group">
                                        <label for="store_name">IFSC CODE</label>
                                        <input type="text" class="form-control" id="store_name" name="ifsc_code" value="{{ isset($userById->va_ifsc_code) ? $userById->va_ifsc_code : '' }}"  disabled>
                            </div>
                             <div class="form-group">
                                        <label for="store_name">APPROVED PACKAGE</label>
                                       <select class="form-control" name="package"  disabled>
                                            <option>Select UserType</option>
                                            @foreach($allPackages as $packages)
                                            <option value="{{$packages->package_id}}" @if($userById->package_id==$packages->package_id) Selected @endif>{{$packages->package_name}}</option>
                                            @endforeach
                                        </select>
                            </div>
                               <div class="form-group">
                                        <label for="store_name">MINIMUM BALANCE</label>
                                        <input type="text" class="form-control" id="store_name" name="min_balance" value="{{ isset($userById->min_balance) ? $userById->min_balance : '' }}"  disabled>
                            </div>
                               <div class="form-group">
                                        <label for="store_name">MINIMUM WALLET LOAD</label>
                                        <input  disabled type="text" class="form-control" id="store_name" name="min_amount_deposit" value="{{ isset($userById->min_amount_deposit) ? $userById->min_amount_deposit : '' }}">
                            </div>
                               <div class="form-group">
                                        <label for="store_name">MAXIMUM WALLET LOAD</label>
                                        <input   disabled type="text" class="form-control" id="store_name" name="max_amount_deposit" value="{{ isset($userById->max_amount_deposit) ? $userById->max_amount_deposit : '' }}">
                            </div>
                            
                        
                                    </div>
                                </div>
                                <div class="col-6" style="">
                                   <div class=" card card-body"  style="height: 669px;">
                                        <center><h4 style="font-weight:bold;">BUSINESS INFORMATION</h4></center>
                                        <hr style="border:1px solid red;">
                                        
                                       
                                            <!--<img src="{{asset('template_assets/assets/images/background/img3.jpg')}}" style="border-radius:60%;height:100px;width:50%;">
                                            <center><h4 style="font-weight:800;">Score:90%</h4></center>-->
                                        <div class="form-group">
                                            <label for="aadhar_no">BUSINESS NAME</label>
                                            <input  disabled type="text" class="form-control" id="aadhar_no" name="business_name" value=" {{  $userById->ekyc->business_name  }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="store_name">BUSINESS ADDRESS</label>
                                            <textarea  disabled class="form-control" name="business_address" rows="5" style="width:100%;height:90px !important;">{{ isset($userById->ekyc->business_address	) ? $userById->ekyc->business_address : '' }}</textarea>
                                            </div>
                                        <div class="form-group">
                                            <label for="store_name">STATE</label>
                                            <input disabled type="text" class="form-control" name="state_name" value=" {{  $userById->ekyc->state  }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="store_name">CITY</label>
                                            <input disabled type="text" class="form-control" name="city_name" value=" {{  $userById->ekyc->city  }}">
                                          
                                        </div>
                                        <div class="form-group">
                                            <label for="store_name">PINCODE</label>
                                            <input type="text" class="form-control" id="store_name" name="pincode" value="{{  $userById->ekyc->pincode  }}"  disabled>
                                        </div>
                                        <div class="form-group">
                                            <label for="store_name">BUSINESS CATEGORY</label>
                                            <input type="text" disabled class="form-control" id="store_name" name="category_name" value="{{  $userById->ekyc->category }}">
                                        </div>
                                    </div>
                                </div>
                                
                                
                    </div></div>
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

            </div>
        </div>
    </div>
    <style>
        .form-control{
            height:41px !important;
        }
        label
        {
            font-weight:bold;
        }
    </style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" ></script>
    <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css"  />-->
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
                            </script>
                            <script>
                              $(function() {
                                $('#type').change(function() {
                                    
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
                                })
                              })
                        </script>
</section>
</div>
<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script src="https://paymamaapp.in/template_assets/other/js/bootstrap-toggle.min.js"></script>
<script src="{{ asset('template_assets\other\js\angular.min.js') }}"></script>
<script src="{{ asset('dist/user/js/addUser.js') }}"></script>

@endsection
