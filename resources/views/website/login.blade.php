<!DOCTYPE html>
<html dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
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
            <div class="row" style="padding:120px 280px 280px 280px;">
                <div class="col-md-2"></div>
                <div class="col-md-4 col-12 fxt-bg-wrap" style="border-radius:30px;border:0px solid white;">
                   <script src="{{ asset('template_assets/assets/libs/jquery/dist/jssor.slider-27.5.0.min.js') }}"></script>
    <script type="text/javascript">
        jssor_1_slider_init = function() {

            var jssor_1_SlideoTransitions = [
              [{b:0,d:600,y:-290,e:{y:27}}],
              [{b:0,d:1000,y:185},{b:1000,d:500,o:-1},{b:1500,d:500,o:1},{b:2000,d:1500,r:360},{b:3500,d:1000,rX:30},{b:4500,d:500,rX:-30},{b:5000,d:1000,rY:30},{b:6000,d:500,rY:-30},{b:6500,d:500,sX:1},{b:7000,d:500,sX:-1},{b:7500,d:500,sY:1},{b:8000,d:500,sY:-1},{b:8500,d:500,kX:30},{b:9000,d:500,kX:-30},{b:9500,d:500,kY:30},{b:10000,d:500,kY:-30},{b:10500,d:500,c:{x:125.00,t:-125.00}},{b:11000,d:500,c:{x:-125.00,t:125.00}}],
              [{b:0,d:600,x:535,e:{x:27}}],
              [{b:-1,d:1,o:-1},{b:0,d:600,o:1,e:{o:5}}],
              [{b:-1,d:1,c:{x:250.0,t:-250.0}},{b:0,d:800,c:{x:-250.0,t:250.0},e:{c:{x:7,t:7}}}],
              [{b:-1,d:1,o:-1},{b:0,d:600,x:-570,o:1,e:{x:6}}],
              [{b:-1,d:1,o:-1,r:-180},{b:0,d:800,o:1,r:180,e:{r:7}}],
              [{b:0,d:1000,y:80,e:{y:24}},{b:1000,d:1100,x:570,y:170,o:-1,r:30,sX:9,sY:9,e:{x:2,y:6,r:1,sX:5,sY:5}}],
              [{b:2000,d:600,rY:30}],
              [{b:0,d:500,x:-105},{b:500,d:500,x:230},{b:1000,d:500,y:-120},{b:1500,d:500,x:-70,y:120},{b:2600,d:500,y:-80},{b:3100,d:900,y:160,e:{y:24}}],
              [{b:0,d:1000,o:-0.4,rX:2,rY:1},{b:1000,d:1000,rY:1},{b:2000,d:1000,rX:-1},{b:3000,d:1000,rY:-1},{b:4000,d:1000,o:0.4,rX:-1,rY:-1}]
            ];

            var jssor_1_options = {
              $AutoPlay: 1,
              $Idle: 2000,
              $CaptionSliderOptions: {
                $Class: $JssorCaptionSlideo$,
                $Transitions: jssor_1_SlideoTransitions,
                $Breaks: [
                  [{d:2000,b:1000}]
                ]
              },
              $ArrowNavigatorOptions: {
                $Class: $JssorArrowNavigator$
              },
              $BulletNavigatorOptions: {
                $Class: $JssorBulletNavigator$
              }
            };

            var jssor_1_slider = new $JssorSlider$("jssor_1", jssor_1_options);

            /*#region responsive code begin*/

            var MAX_WIDTH = 1360;

            function ScaleSlider() {
                var containerElement = jssor_1_slider.$Elmt.parentNode;
                var containerWidth = containerElement.clientWidth;

                if (containerWidth) {

                    var expectedWidth = Math.min(MAX_WIDTH || containerWidth, containerWidth);

                    jssor_1_slider.$ScaleWidth(expectedWidth);
                }
                else {
                    window.setTimeout(ScaleSlider, 30);
                }
            }

            ScaleSlider();

            $Jssor$.$AddEvent(window, "load", ScaleSlider);
            $Jssor$.$AddEvent(window, "resize", ScaleSlider);
            $Jssor$.$AddEvent(window, "orientationchange", ScaleSlider);
            /*#endregion responsive code end*/
        };
    </script>
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
  <!--<div id="iview" class="main-slider">
  
  <div data-iview:thumbnail="img/bn1.jpg" data-iview:image="Website page-min.jpg" data-iview:transition="block-drop-random" >
      <div class="container">
        <div class="iview-caption  bg-no-caption" data-x="260" data-y="293" data-transition="expandLeft">
          <div class="custom-caption">
            <p class="slide-title bg-color_second">A Team Of Medical Professionals</p>
            <p class="slide-title_second">To Take Care Of Your Health</p>
          </div>
        </div>
      </div>
    </div>
    
    
    <div data-iview:thumbnail="img/bn2.png" data-iview:image="Website page_2-min.jpg" data-iview:transition="block-drop-random" >
      <div class="container">
        <div class="iview-caption bg-no-caption" data-x="660" data-y="143" data-transition="expandLeft">
          <div class="custom-caption">
            <p class="slide-title bg-color_second">A Team Of Medical Professionals</p>
            <p class="slide-title_second">To Take Care Of Your Health</p>
          </div>
        </div>
      </div>
    </div>
    
    <div data-iview:thumbnail="img/bn2.png" data-iview:image="Website page-3-min.jpg" data-iview:transition="block-drop-random" >
      <div class="container">
        <div class="iview-caption bg-no-caption" data-x="660" data-y="143" data-transition="expandLeft">
          <div class="custom-caption">
            <p class="slide-title bg-color_second">A Team Of Medical Professionals</p>
            <p class="slide-title_second">To Take Care Of Your Health</p>
          </div>
        </div>
      </div>
    </div>
    
    <div data-iview:thumbnail="img/bn2.png" data-iview:image="Website page-4-min (2).jpg" data-iview:transition="block-drop-random" >
      <div class="container">
        <div class="iview-caption bg-no-caption" data-x="660" data-y="143" data-transition="expandLeft">
          <div class="custom-caption">
            <p class="slide-title bg-color_second">A Team Of Medical Professionals</p>
            <p class="slide-title_second">To Take Care Of Your Health</p>
          </div>
        </div>
      </div>
    </div>
    
    <div data-iview:thumbnail="img/bn2.png" data-iview:image="Website page-5-min.jpg" data-iview:transition="block-drop-random" >
      <div class="container">
        <div class="iview-caption bg-no-caption" data-x="660" data-y="143" data-transition="expandLeft">
          <div class="custom-caption">
            <p class="slide-title bg-color_second">A Team Of Medical Professionals</p>
            <p class="slide-title_second">To Take Care Of Your Health</p>
          </div>
        </div>
      </div>
    </div>
    
    
  </div>--><!-- end iview -->
<div id="jssor_1" style="position:relative;margin:0 auto;top:0px;left:-14px;width:850px;height:1370px !important;overflow:hidden;visibility:hidden;border:2px solid white;">
        <!-- Loading Screen -->
        <div data-u="loading" class="jssorl-009-spin" style="position:absolute;top:0px;left:0px;width:100%;height:100%;text-align:center;background-color:rgba(0,0,0,0.7);">
            <img style="margin-top:-19px;position:relative;top:50%;width:38px;height:38px;" src="img/spin.svg" />
        </div>
        <div data-u="slides" style="cursor:default;position:relative;top:0px;left:0px;width:850px;height:1380px;overflow:hidden;">
            <div data-p="170" style="">
                <img data-u="image" src="{{asset('template_assets/assets/images/services/recharges.jpg')}}" />
            </div>
            <div data-p="170">
                <img data-u="image" src="{{asset('template_assets/assets/images/services/bill payment.jpg')}}" />
               
            </div>
            <div data-p="170">
                <img data-u="image" src="{{asset('template_assets/assets/images/services/bhimupi.jpg')}}" />
  
            </div>
            <div data-p="170">
                <img data-u="image" src="{{asset('template_assets/assets/images/services/aeps.jpg')}}" />
               
            </div>
            <!--<div data-p="170">
                <img data-u="image" src="{{asset('template_assets/assets/images/icici_bank_logo_symbol.png')}}" />
                
            </div>-->
            <div data-p="170">
                <img data-u="image" src="{{asset('template_assets/assets/images/services/busflight.jpg')}}" />
                
            </div>
            <div data-p="170">
                <img data-u="image" src="{{asset('template_assets/assets/images/services/qrcodepayment.jpg')}}" />
                
            </div>
            <div data-p="170">
                <img data-u="image" src="{{asset('template_assets/assets/images/services/loadwallet.jpg')}}" />
                
            </div>
           
            
            
        </div>
        <!-- Bullet Navigator -->
        <div data-u="navigator" class="jssorb052" style="position:absolute;bottom:12px;right:12px;" data-autocenter="1" data-scale="0.5" data-scale-bottom="0.75">
            <div data-u="prototype" class="i" style="width:16px;height:16px;">
                <svg viewbox="0 0 16000 16000" style="position:absolute;top:0;left:0;width:100%;height:100%;">
                    <circle class="b" cx="8000" cy="8000" r="5800"></circle>
                </svg>
            </div>
        </div>
        <!-- Arrow Navigator -->
        <div data-u="arrowleft" class="jssora053" style="width:55px;height:55px;top:0px;left:25px;" data-autocenter="2" data-scale="0.75" data-scale-left="0.75">
            <svg viewbox="0 0 16000 16000" style="position:absolute;top:0;left:0;width:100%;height:100%;">
                <polyline class="a" points="11040,1920 4960,8000 11040,14080 "></polyline>
            </svg>
        </div>
        <div data-u="arrowright" class="jssora053" style="width:55px;height:55px;top:0px;right:25px;" data-autocenter="2" data-scale="0.75" data-scale-right="0.75">
            <svg viewbox="0 0 16000 16000" style="position:absolute;top:0;left:0;width:100%;height:100%;">
                <polyline class="a" points="4960,1920 11040,8000 4960,14080 "></polyline>
            </svg>
        </div>
    </div>
     <script type="text/javascript">jssor_1_slider_init();</script>
                    
                </div>
                <div class="col-md-4 col-12 fxt-bg-color" style="border-radius:0px;z-index:0;background-color: white;height:422px;padding:25px;">
                     <div class="auth-box">
                <div id="loginform">
                    <div class="logo">
                        <span class="db"><img src="{{ asset('template_assets/assets/images/logos/Paymama_finallogo.png') }}" alt="logo"  style="width:80%;"/></span>
                        <br>
                        <hr>
                        <h3 class="font-medium mb-3" style="text-align: center;">LOGIN</h3>
                        <hr>
                    </div>
                    <!-- Form -->
                    <div class="row">
                        <div class="col-12">
                            @if($errors->has('mobile'))
                            <div class="alert alert-danger alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                Mobile or Password mismatch                    
                            </div>
                            @enderror

                            @if($errors->has('email'))
                            <div class="alert alert-danger alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                Email or Password mismatch                    
                            </div>
                            @enderror

                            @if($errors->has('username'))
                            <div class="alert alert-danger alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                Username or Password mismatch                    
                            </div>
                            @enderror

                            @error('password')
                            <div class="alert alert-danger alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                Password mismatch                    
                            </div>
                            @enderror

                            <form class="form-horizontal mt-3" id="loginform" method="POST" action="{{ route('login') }}">
                            @csrf
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class="ti-user"></i></span>
                                    </div>

                                    <input id="login" type="text"  class="form-control form-control-lg {{ $errors->has('email') || $errors->has('mobile') || $errors->has('username') ? 'is-invalid' :''}}" placeholder="Email/Mobile/Username" aria-label="Email" aria-describedby="basic-addon1" name="login" value="" required autofocus>
                                   
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon2"><i class="ti-pencil"></i></span>
                                    </div>
                                    <input id="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" placeholder="Password" aria-label="Password" aria-describedby="basic-addon1" name="password" required autocomplete="current-password">
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="customCheck1">
                                            <label class="custom-control-label" for="customCheck1">Remember me</label>
                                            <a href="javascript:void(0)" id="to-recover" class="text-dark float-right"><i class="fa fa-lock mr-1"></i> Forgot Password?</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group text-center">
                                    <div class="col-xs-12 pb-3">
                                        <button class="btn btn-block btn-lg btn-info" type="submit">Sign In</button>
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
    $('[data-toggle="tooltip"]').tooltip();
    $(".preloader").fadeOut();
    // ============================================================== 
    // Login and Recover Password 
    // ============================================================== 
    $('#to-recover').on("click", function() {
        $("#loginform").slideUp();
        $("#recoverform").fadeIn();
    });
    </script>
</body>

</html>