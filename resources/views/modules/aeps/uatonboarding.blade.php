@extends('layouts.full_new')
@section('page_content')
<script>
    showPosition();
    function showPosition() {
        if(navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var positionInfo = "Latitude: " + position.coords.latitude + ", " + "Longitude: " + position.coords.longitude;
                document.getElementById("latitude").value = position.coords.latitude;
                document.getElementById("longitude").value = position.coords.longitude;
            });
        } else {
           alert("Sorry, your browser does not support geolocation.");
        }
    }
</script>
<div class="page-content container-fluid">
    @if(Session::has('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>SUCCESS</strong>  {{ Session::get('success') }}
        {{  Session::forget('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @elseif(Session::has('error')) 

    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>FAILED</strong>  {{ Session::get('error') }}
        {{  Session::forget('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
    <section>
        <div class="row">
             
            <div class="col-12">
                <div class="card card-body">
                    <h4 class="" style="text-align:center;background:#be1d2c;color:white;padding:15px;">AEPS ONBOARDING KYC</h4>
                        <br>
                        <style>
                            label{
                                font-size:16px;
                            }
                        </style>
                        <form method="post" action="{{ route('aeps_onboarding') }}" onfocus="showPosition()">
                        @csrf
                        <input type="hidden" id="longitude" class="form-control" name="longitude">
                        <input type="hidden" id="latitude" class="form-control" name="latitude">
                        <fieldset style="border:1px solid #e1e1e1;padding:20px;">
                            <legend style="color:blue;font-weight:bold;width:20% !important;">BUSINESS DETAILS</legend>
                        <div class="row">
                            
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="mid">Login ID (Password will be generated after successful registration)</label>
                                            <input type="text" class="form-control" name="mid" value="{{ isset($user->username) ? $user->username : '' }}" readonly required="">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="name">Full Name</label>
                                            <input type="text" class="form-control" name="name" value="{{ isset($user->first_name) ? $user->first_name : '' }} {{ isset($user->last_name) ? $user->last_name : '' }}" required="">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="email">Email Id</label>
                                            <input type="email" class="form-control" name="email" value="{{ isset($user->email) ? $user->email : '' }}" readonly required="">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="mobileNumber">Mobile Number</label>
                                            <input type="number" class="form-control" name="mobileNumber" value="{{ isset($user->mobile) ? $user->mobile : '' }}" readonly required="">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="address">Address</label>
                                            <input type="text" class="form-control" name="address" value="{{ isset($user->address) ? $user->address : '' }}" required="">
                                        </div>
                                    </div>
                                    <!--<div class="col-6">-->
                                    <!--    <div class="form-group">-->
                                    <!--        <label for="district">District</label>-->
                                    <!--        <input type="text" class="form-control" name="district" required="">-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                </div>
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="pincode">Pincode</label>
                                            <input type="text" class="form-control" name="pincode" value="{{ isset($user->zip_code) ? $user->zip_code : '' }}" required="">
                                        </div>
                                    </div>
                                     <div class="col-4">
                                        <div class="form-group">
                                            <label for="state">State</label>
                                            <select class="form-control custom-select" id="state_id" name="state_id" required="" style="height:47px;font-size:20px;" onchange="getcity()">
                                                @foreach(json_decode($aeps_state,true) as $state)
            									<option value="{{ $state['state_id'] }}" style="font-size:17px;">{{ $state['state_name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="city">City</label>
                                            <select name="city_id" class="form-control"  id="response">
                                        </select>
                     <!--                       <select class="form-control custom-select" id="state_id" name="city_id" required="" style="height:47px;font-size:20px;">-->
                     <!--                           @foreach(json_decode($aeps_city,true) as $city)-->
            									<!--<option value="{{ $city['city_id'] }}">{{ $city['city_name'] }}</option>-->
                     <!--                           @endforeach-->
                     <!--                       </select>-->
                                        </div>
                                       
                                    </div>
                                   
                                </div>
                                </fieldset>
                                <br>
                                <fieldset style="border:1px solid #e1e1e1;padding:20px;">
                                <legend style="color:blue;font-weight:bold;width:13% !important;;">KYC DETAILS</legend>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="aadharNumber">Aadhar Number</label>
                                            <input type="text" class="form-control" id="aadharNumber" name="aadharNumber" value="{{ isset($user->aadhar_no) ? str_replace('-','',$user->aadhar_no) : '' }}" required="">
                                        </div>
                                    </div>
    
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="pan">Pan Number</label>
                                            <input type="text" class="form-control" id="pan" name="pan" value="{{ isset($user->pan_no) ? $user->pan_no : '' }}" required="">
                                        </div>
                                    </div>
                                </div>
                                </fieldset>
                                 <div class="col-md-12" style="margin-top:23px;">
                                 <button type="submit" class="btn btn-success mr-2" style="font-size:18px;width: 150px;background-color: green;color: white;">Submit</button>
                                <a type="button" href="{{ route('home') }}" class="btn btn-dark" style="font-size:18px;width: 150px;    background-image: linear-gradient(to right, #251c63 , #dc182d);color: white;">Cancel</a>
                            </div>
                            </div>
    
                           
                        </div>
                    </form>
                        </fieldset>
    
                </div>
            </div>
        </div>
    </section>
    <style>
        legend{
           
            color:#be1d2c !important;
            font-weight:normal;
            font-size:19px;
        }
    </style>
</div>
<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script>
    function getcity()
    {
        var state=$('#state_id').val();
        $.ajax({
                type: "GET",
                dataType: "json",
                url: '/getcity',
                data: {'state': state},
                success: function(result){
                    $("#response").empty();
                  $.each(result,function(index,value,username){
                   var splitted = value.split("-"); 
                  
                   $("#response").append('<option value="'+splitted[0]+'" name="city_id">'+splitted[1]+'</option>');
                 });   
                }
             });
    }
</script>
<!--<script src="{{ asset('template_assets/other/js/angular.min.js') }}"></script>-->
@endsection
