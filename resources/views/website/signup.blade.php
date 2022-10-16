<!DOCTYPE html>
<html dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('template_assets/assets/images/favicon_sm_py.png') }}">
    <title>SMARTPAY - Making India Digital</title>
    <link rel="canonical" href="https://www.wrappixel.com/templates/ampleadmin/" />
    <!-- Custom CSS -->
    <link href="{{ asset('template_assets/dist/css/style.min.css') }}" rel="stylesheet">
    
<![endif]-->
</head>

<body  style="background-image: url({{ asset('/template_assets/assets/images/background/loginbackground.jpeg') }});background-size:cover;background-repeat:no-repeat;">
    <div class="main-wrapper">
        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->
        <div class="preloader">
            <div class="lds-ripple">
                <div class="lds-pos"></div>
                <div class="lds-pos"></div>
            </div>
        </div>
<div class="blank-space"></div>
    <section class="fxt-template-animation fxt-template-layout4">

        <div class="container-fluid">
            <div class="row" style="padding:120px 280px 280px 400px;">
 <style>
        /* jssor slider loading skin spin css */
        .jssorl-009-spin img {
            animation-name: jssorl-009-spin;
            animation-duration: 1.6s;
            animation-iteration-count: infinite;
            animation-timing-function: linear;
        }

        @keyframes jssorl-009-spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }


        .jssorb052 .i {position:absolute;cursor:pointer;}
        .jssorb052 .i .b {fill:#000;fill-opacity:0.3;}
        .jssorb052 .i:hover .b {fill-opacity:.7;}
        .jssorb052 .iav .b {fill-opacity: 1;}
        .jssorb052 .i.idn {opacity:.3;}

        .jssora053 {display:block;position:absolute;cursor:pointer;}
        .jssora053 .a {fill:none;stroke:#fff;stroke-width:640;stroke-miterlimit:10;}
        .jssora053:hover {opacity:.8;}
        .jssora053.jssora053dn {opacity:.5;}
        .jssora053.jssora053ds {opacity:.3;pointer-events:none;}
    </style>
                <div class="fxt-bg-color" style="border-radius:0px;z-index:0;background-color: white;height:550px;padding:25px;">
                     <div class="auth-box">
                <div id="loginform">
                    <div class="logo" style="text-align:center;">
                        <span class="db"><img src="{{ asset('template_assets/assets/images/logos/Paymama_330x90.png') }}" alt="logo"  style="width:80%;"/></span>
                        <br>
                        <hr>
                        <h3 class="font-medium mb-3" style="text-align: center;">SIGNUP</h3>
                        <hr>
                    </div>
                    <!-- Form -->
                    <div class="row">
                        <div class="col-12">
                            @if (session('success'))
                                  <div class="alert alert-success">
                                     {!! session('success') !!}
                                  </div>
                            @endif
                            @if (session('error'))
                                  <div class="alert alert-danger">
                                     {!! session('error') !!}
                                  </div>
                            @endif
                            <form class="form-horizontal mt-3" id="loginform" method="POST" action="{{ route('signup') }}">
                            @csrf
                                <input type="hidden" id="send_otp" name="sendOtp" value="0">
                                <div class="row">
                                    <div class="form-group input-group mb-3">
                                        <div class="col-md-6 input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="ti-user"></i></span>
                                            <input id="firstName" type="text"  class="form-control form-control-lg" placeholder="First Name" aria-label="First Name" aria-describedby="basic-addon1" name="firstName" value="{{ old('firstName') }}" required autofocus>
                                        </div>
                                        <div class="col-md-6 input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="ti-user"></i></span>
                                            <input id="lastName" type="text"  class="form-control form-control-lg" placeholder="Last Name" aria-label="Last Name" aria-describedby="basic-addon1" name="lastName" value="{{ old('lastName') }}" required autofocus>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group input-group mb-3">
                                        <div class="col-md-6 input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="ti-mobile"></i></span>
                                            <input id="mobileNo" type="number"  class="form-control form-control-lg" placeholder="Mobile Number" aria-label="Mobile No" aria-describedby="basic-addon1" name="mobileNo" value="{{ old('mobileNo') }}" required autofocus>
                                        </div>
                                        <div class="col-md-6 input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="ti-email"></i></span>
                                            <input id="email" type="email"  class="form-control form-control-lg" placeholder="Email ID" aria-label="Email" aria-describedby="basic-addon1" name="email" value="{{ old('email') }}" required autofocus>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group input-group mb-3">
                                        <div class="col-md-6 input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="ti-briefcase"></i></span>
                                            <input id="business" type="text"  class="form-control form-control-lg" placeholder="Enter Business Name" aria-label="Business Name" aria-describedby="basic-addon1" name="business" value="{{ old('business') }}" required autofocus>
                                        </div>
                                        <div class="col-md-6 input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="ti-user"></i></span>
                                            <input id="distributorId" type="text" class="form-control form-control-lg" placeholder="Enter Distributor ID (optional)" aria-label="Distributor Id" aria-describedby="basic-addon1" name="distributorId" value="" autofocus>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group input-group mb-3"  id="otpGroup" style="display:none;">
                                        <div class="col-md-12 input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="ti-lock"></i></span>
                                            <input id="otp" type="number" class="form-control form-control-lg" placeholder="Enter OTP" aria-label="Otp" aria-describedby="basic-addon1" name="otp" value="" autofocus>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group text-center">
                                    <div class="col-xs-12 pb-3">
                                        <button class="btn btn-block btn-lg btn-primary" id="sendOtp" type="button">Send OTP</button>
                                    </div>
                                    <div class="col-xs-12 pb-3">
                                        <button class="btn btn-block btn-lg btn-info" id="submitFormButton" disabled type="submit">Sign Up</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
            </div>
                   
                </div>
            </div>
        </div>
    </section>

   
</div>
</div>
    <!-- ============================================================== -->
    <!-- All Required js -->
    <!-- ============================================================== -->
    <script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <!-- <script src="../../assets/libs/jquery/dist/jquery.min.js"></script> -->
    <!-- Bootstrap tether Core JavaScript -->
    <script src="{{ asset('template_assets/assets/libs/popper.js/dist/umd/popper.min.js') }}"></script>
    <!-- <script src="../../assets/libs/popper.js/dist/umd/popper.min.js"></script> -->
    <script src="{{ asset('template_assets/assets/libs/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- <script src="../../assets/libs/bootstrap/dist/js/bootstrap.min.js"></script> -->
    <!-- ============================================================== -->
    <!-- This page plugin js -->
    <!-- ============================================================== -->
    <script>
    $(document).ready(function(){
        var userOtp = 0;
        $('[data-toggle="tooltip"]').tooltip();
        $(".preloader").fadeOut();
        // ============================================================== 
        // Login and Recover Password 
        // ============================================================== 
        $('#to-recover').on("click", function() {
            $("#loginform").slideUp();
            $("#recoverform").fadeIn();
        });
        
        $("#sendOtp").on("click", function(e) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                }
            });
            e.preventDefault();
            var formData = {
                firstName: jQuery('#firstName').val(),
                lastName: jQuery('#lastName').val(),
                mobileNo: jQuery('#mobileNo').val(),
                email: jQuery('#email').val(),
                business: jQuery('#business').val(),
                distributorId: jQuery('#distributorId').val(),
                sendOtp: jQuery('#send_otp').val(),
            };
            var type = "POST";
            var ajaxurl = 'signup';
            $.ajax({
                type: type,
                url: ajaxurl,
                data: formData,
                dataType: 'json',
                success: function (response) {
                    console.log(response, response.otp);
                    userOtp = response.otp;
                    console.log(userOtp);
                },
                error: function (response) {
                    console.log(response);
                }
            });
            $("#otpGroup").css("display", "block");
            $("#send_otp").val("1");
            $("#submitFormButton").prop("disabled", false);
        });
        $("#submitFormButton").on("click", function() {
             $("#send_otp").val("0");
        });
    });
    </script>
</body>

</html>