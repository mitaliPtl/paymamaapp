<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Colorlib Templates">
    <meta name="author" content="Colorlib">
    <meta name="keywords" content="Colorlib Templates">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Title Page-->
    <title>Sign Up</title>

    <!-- Vendor CSS-->
    <link href="{{ asset('template_assets/dist/css/select2.min.css') }}" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="{{ asset('template_assets/dist/css/signup.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
    .rs-select2 .select2-container .select2-selection--single .select2-selection__arrow {
        display: none;
    }
    .template-demo > .btn{
        margin-right: 0.5rem !important;
    }

    .template-demo{
        margin-top: 0.5rem !important;
    }
    .btn-social-icon {
        line-height: 50px;;
    }
    .btn-facebook {
        background: #3b579d;
        color: #ffffff;
    }
    .btn-youtube {
        background: #e52d27;
        color: #ffffff;
    }

    .btn-twitter {
        background: #2caae1;
        color: #ffffff;
    }
    .btn-instagram {
        background: #dc4a38;
        color: #ffffff;
    }
    </style>
</head>

<body style="background-image: url({{ asset('/template_assets/assets/images/background/loginbackground.jpeg') }});background-size:cover;background-repeat:no-repeat;">
    <div class="page-wrapper bg-red font-robo" style="padding-top: 45px;">
        <div class="wrapper wrapper--w1050">
            <div class="card card-2" style="min-height: 535px;">
            @if( Session::has("success") )
                <div class="alert alert-success alert-block" role="alert">
                    <button class="close" data-dismiss="alert"></button>
                    {{ Session::get("success") }}
                </div>
            @endif
                <div class="card-heading" style="background-image: url({{ asset('/template_assets/assets/images/services/aeps.jpg') }});background-size:cover;background-repeat:no-repeat;width:360px;"></div>
                <div class="card-body" style="padding: 10px 40px !important;max-width: 540px;">
                    <img src="{{ asset('/template_assets/assets/images/logos/PAYMAMA_logos.png') }}" alt="Paymama Logo" style="margin-left: 90px;">
                    <hr>
                    <h2 class="title" style="text-align:center; padding-top: 20px;margin-bottom: 20px !important;"><u style="text-decoration-color: red;">SIGNUP</u></h2>
                    <hr>
                    <span class="error" style="color:red;"></span>
                    <form id="signupForm" method="POST" action="{{ route('signup') }}" autocomplete="off">
                        @csrf   
                        <div class="row row-space" style="margin-top: 20px;">
                            <div class="col-2">
                                <div class="input-group">
                                    <input class="input--style-2" autocomplete="nope" type="text" required placeholder="First Name" id="firstName" name="firstName">
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="input-group">
                                    <input class="input--style-2" autocomplete="nope" type="text" required placeholder="Last Name" id="lastName" name="lastName">
                                </div>
                            </div>
                        </div>
                        <div class="row row-space">
                            <div class="col-2">
                                <div class="input-group">
                                    <span id="mobileNoErr" style='color:red;'></span>
                                    <input class="input--style-2" autocomplete="nope" type="number" required placeholder="Mobile Number" id="mobileNo" name="mobileNo">
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="input-group">
                                    <span id="emailErr" style='color:red;'></span>
                                    <input class="input--style-2" autocomplete="nope" type="email" required placeholder="Email ID" id="email" name="email">
                                </div>
                            </div>
                        </div>
                        <div class="row row-space">
                            <div class="col-2">
                                <div class="input-group">
                                    <input class="input--style-2" autocomplete="nope" type="text" required placeholder="Enter Business Name" id="business" name="business">
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="input-group">
                                    <div class="rs-select2 js-select-simple select--no-search">
                                        <select name="userType" id="userType" required>
                                            <option disabled="disabled" selected="selected">-- Select User --</option>
                                            <option value="4">Retailer</option>
                                            <option value="2">Distributor</option>
                                        </select>
                                        <div class="select-dropdown"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row row-space" id="otpGroup" style="display: none;">
                            <div class="col-2">
                                <div class="input-group">
                                    <input class="input--style-2" autocomplete="nope" type="number" placeholder="Enter OTP" id="otp" name="otp">
                                </div>
                            </div>
                        </div>
                        <div class="row row-space">
                            <div class="col-2">
                                <button class="btn btn--radius btn--blue" id="sendOtp" type="button">Send OTP</button>
                            </div>
                            <div class="col-2">
                                <button class="btn btn--radius btn--green" style="float:left;" disabled id="submitFormButton" type="submit">Sign Up</button>
                            </div>
                        </div>
                        <div class="row row-space" style="justify-content: center;padding: 10px 10px;">
                            <div class="template-demo">
                                <button type="button" class="btn btn-social-icon btn-facebook"><i class="fa fa-facebook"></i></button>
                                <button type="button" class="btn btn-social-icon btn-instagram"><i class="fa fa-instagram"></i></button>
                                <button type="button" class="btn btn-social-icon btn-twitter"><i class="fa fa-twitter"></i></button>
                                <button type="button" class="btn btn-social-icon btn-youtube"><i class="fa fa-youtube"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- All Required js -->
    <!-- ============================================================== -->
    <script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('template_assets/dist/js/jquery.validate.min.js') }}"></script>
    <!-- <script src="../../assets/libs/jquery/dist/jquery.min.js"></script> -->
    <!-- Bootstrap tether Core JavaScript -->
    <script src="{{ asset('template_assets/assets/libs/popper.js/dist/umd/popper.min.js') }}"></script>
    <!-- <script src="../../assets/libs/popper.js/dist/umd/popper.min.js"></script> -->
    <script src="{{ asset('template_assets/assets/libs/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- <script src="../../assets/libs/bootstrap/dist/js/bootstrap.min.js"></script> -->
    <!-- ============================================================== -->
    <!-- This page plugin js -->
    <!-- Vendor JS-->
    <script src="{{ asset('template_assets/dist/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            try {
                var selectSimple = $('.js-select-simple');
            
                selectSimple.each(function () {
                    var that = $(this);
                    var selectBox = that.find('select');
                    var selectDropdown = that.find('.select-dropdown');
                    selectBox.select2({
                        dropdownParent: selectDropdown
                    });
                });
            
            } catch (err) {
                console.log(err);
            }

            $("#sendOtp").on("click", function(e) {
                if ($("#signupForm")[0].checkValidity()) {
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
                        userType: jQuery('#userType').val(),
                    };
                    var type = "POST";
                    var ajaxurl = 'signup';
                    $.ajax({
                        type: type,
                        url: ajaxurl,
                        data: formData,
                        dataType: 'json',
                        success: function (response) {
                            if (response.code == 200) {
                                userOtp = response.otp;
                                $('div.flash-message').append('<div class="alert alert-success">' + response.success + '</div>')
                                $("#otpGroup").css("display", "block");
                                $('#otp').attr('required', true);
                                $("#submitFormButton").prop("disabled", false);
                                $("#sendOtp").prop("disabled", true);
                            } else {
                                if (typeof response.message == 'object') {
                                    if (response.message.mobileNo) {
                                        $("#mobileNoErr").html(response.message.mobileNo[0]);
                                    } else {
                                        $(".error").html(response.message);
                                    }
                                    if (response.message.email) {
                                        $("#emailErr").html(response.message.email[0]);
                                    } else {
                                        $(".error").html(response.message);
                                    }
                                } else {
                                    $(".error").html(response.message);
                                }
                                setTimeout(function() {
                                    $("#signupForm")[0].reset();
                                    $("#mobileNoErr").html("");
                                    $("#emailErr").html("");
                                    $(".error").html("");
                                }, 2000);
                            }
                        },
                        error: function (response) {
                            console.log('here');
                        }
                    });
                } else {
                    //Validate Form
                    $("#signupForm")[0].reportValidity();
                }
            });
        });
    </script>

</body><!-- This templates was made by Colorlib (https://colorlib.com) -->

</html>
<!-- end document-->