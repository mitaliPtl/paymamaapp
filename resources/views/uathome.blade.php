@php
ini_set('memory_limit', '-1');
@endphp

@extends('layouts.full_new')

@section('page_content')

@if( Auth::user()->roleId == Config::get('constants.RETAILER'))
    <link rel="stylesheet" type="text/css" href="{{ asset('template_new/assets/libs/select2/dist/css/select2.min.css') }}">
    <style>
        .table-centered tbody > tr td{
            text-align: center;
        }
        .equal-cols tbody > tr td{
            width:50%;
            font-size: 20px;
        }
        .pointer_class {cursor: pointer;}
        .colon-algin{
            float:right;
        }
        .home-bbps-title{
            font-size : 15px;
            font-weight: 500;
        }
    </style>
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="page-content container-fluid">
                <!-- ============================================================== -->
                     @if(Session::has('success_msg'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>SUCCESS</strong>  {{ Session::get('success_msg') }}.{{  Session::forget('success_msg') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @elseif(Session::has('error_msg')) 

                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>FAILED</strong>  {{ Session::get('error_msg') }}.
                        {{  Session::forget('error_msg') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                {{-- json_encode($homeSlider['result'][0]['data']) --}}
                {{-- Session::get('user_notification') --}}
                {{-- Session::get('user_notification_count') --}}
                
                    <div class="row">
                        <div class="" style="padding-right:30px;">
                            
 <script src="{{ asset('jssor.slider-27.5.0.min.js') }}" type="text/javascript"></script>
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

            var MAX_WIDTH = 2530;

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
            animation-duration: 3.6s;
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
    <script type="text/javascript">jssor_1_slider_init();</script>
                            <section class="logo-carousel slider">
                           
                                <!-- <div class="slide"><img src="{{ asset('template_new/img/01.jpg') }}"></div> -->
                                <!-- <div class="slide"><img src="{{ asset('template_new/img/02.jpg') }}"></div>
                                <div class="slide"><img src="{{ asset('template_new/img/03.jpg') }}"></div>
                                <div class="slide"><img src="{{ asset('template_new/img/04.jpg') }}"></div>
                                <div class="slide"><img src="{{ asset('template_new/img/05.jpg') }}"></div>
                                <div class="slide"><img src="{{ asset('template_new/img/06.jpg') }}"></div>
                                <div class="slide"><img src="{{ asset('template_new/img/07.jpg') }}"></div> -->
                            </section>
                        </div>
                    </div>
                    <br>
                    <div class="row" style="margin-top:-50px;">
                        @if(isset($all_offers_notice))
                        <marquee width="100%" direction="left">
                            @foreach($all_offers_notice as $notices)
                            <label style="margin-right:40px;font-size:20px;color:red;">{{ $notices->notice_title }} : {{ $notices->notice_description }}</label>
                            @endforeach
                        </marquee>
                         @endif
                    </div>
                <hr>
<div class="modal fade" id="myModal" role="dialog" style="margin-top:10%; 
  z-index: 1060 !important;">
                            <div class="modal-dialog">
                            
                              <!-- Modal content-->
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                                  <h4 class="modal-title">This Services is Not Active For You, Kindly Contact PayMama Sales Department for Activation : 918374913154</h4>
                                </div>
                               
                              </div>
                              
                            </div>
                          </div>
                <div class="row">
                    @php $userid=Auth::user()->userId @endphp
                        @php
                         $results = DB::select( DB::raw("SELECT * FROM tbl_user_services WHERE user_id = :somevariable and service_id= :serviceid"), array(
                                     'somevariable' => $userid,
                                       'serviceid' => 5
                                     ));
                         $array = json_decode(json_encode($results), true);
                         
                          $aepsresults = DB::select( DB::raw("SELECT * FROM tbl_user_services WHERE user_id = :somevariable and service_id= :serviceid"), array(
                                     'somevariable' => $userid,
                                       'serviceid' => 6
                                     ));
                         $aepsarray = json_decode(json_encode($aepsresults), true);
                         
                          $aadharpayresults = DB::select( DB::raw("SELECT * FROM tbl_user_services WHERE user_id = :somevariable and service_id= :serviceid"), array(
                                     'somevariable' => $userid,
                                       'serviceid' => 9
                                     ));
                         $aadharpayarray = json_decode(json_encode($aadharpayresults), true);
                         
                         $iciciresults = DB::select( DB::raw("SELECT * FROM tbl_user_services WHERE user_id = :somevariable and service_id= :serviceid"), array(
                                     'somevariable' => $userid,
                                       'serviceid' => 10
                                     ));
                         $iciciarray = json_decode(json_encode($iciciresults), true);
                         $bhimupiresults = DB::select( DB::raw("SELECT * FROM tbl_user_services WHERE user_id = :somevariable and service_id= :serviceid"), array(
                                     'somevariable' => $userid,
                                       'serviceid' => 7
                                     ));
                         $bhimupiarray = json_decode(json_encode($bhimupiresults), true);
                         $billresults = DB::select( DB::raw("SELECT * FROM tbl_user_services WHERE user_id = :somevariable and service_id= :serviceid"), array(
                                     'somevariable' => $userid,
                                       'serviceid' => 4
                                     ));
                         $billarray = json_decode(json_encode($billresults), true);
                        @endphp
                            @if($array[0]['status']==1)
                    <div class="col pointer_class">
                        <div class="service-block1 bg-purple" onclick="gotoLink( '{{ route('money_transfer') }}' )">
                           <a class=" " id="FlightBookingTab" href="{{ route('money_transfer') }}"
                                onclick="javascript:gotoflight();"></a>
                          
                             
                          
                            <i>
                                <img src="{{ asset('template_new/img/3_moneytransfer_ic.png') }}">
                            </i>
                            <div class="srv-cnt">
                                <h3>Money Transfer</h3>

                            </div>
                        </div>
                    </div>
                      @else
                      <div class="col pointer_class">
                        <div class="service-block1 bg-purple" data-toggle="modal" data-target="#myModal">
                           <a class=" " id="FlightBookingTab" data-toggle="modal" data-target="#myModal"></a>
                          
                             
                          
                            <i>
                                <img src="{{ asset('template_new/img/3_moneytransfer_ic.png') }}">
                            </i>
                            <div class="srv-cnt">
                                <h3>Money Transfer</h3>

                            </div>
                        </div>
                    </div>
                        @endif
                      @if($bhimupiarray[0]['status']==1)
                  <div class="col pointer_class" >
                        <div class="service-block1 bg-skyblue" onclick="gotoLink( '{{ route('money_transfer',['money_transfer'=>'BHIM_UPI']) }}' )">
                            <a class=" " id="FlightBookingTab" href="javascript:void(0);"
                                ></a>

                            <i>
                                <img src="{{ asset('template_new/img/bhim-upi.png') }}">
                            </i>
                            <div class="srv-cnt">
                                <h3>BHIM UPI</h3>

                            </div>
                        </div>
                    </div>
                    @else
                    <div class="col pointer_class" >
                        <div class="service-block1 bg-skyblue" data-toggle="modal" data-target="#myModal">
                            <a class=" " id="FlightBookingTab" data-toggle="modal" data-target="#myModal"
                                ></a>

                            <i>
                                <img src="{{ asset('template_new/img/bhim-upi.png') }}">
                            </i>
                            <div class="srv-cnt">
                                <h3>BHIM UPI</h3>

                            </div>
                        </div>
                    </div>
                    @endif   
                    
                     @if($aepsarray[0]['status']==1)
                    <div class="col pointer_class">
                        <div class="service-block1 bg-blue"   onclick="gotoLink( '{{ route('aeps') }}' )">
                            <a class="block-link" id="FlightBookingTab" href="{{ route('aeps') }}"></a>

                            <i>
                                <img src="{{ asset('template_assets/AEPS-ICON.png') }}">
                            </i>
                            <div class="srv-cnt">
                                <h3>AEPS</h3>

                            </div>
                        </div>
                    </div>
                    @else
                    <div class="col pointer_class">
                        <div class="service-block1 bg-blue" data-toggle="modal" data-target="#myModal">
                            <a class="block-link" id="FlightBookingTab" data-toggle="modal" data-target="#myModal"></a>

                            <i>
                                <img src="{{ asset('template_assets/AEPS-ICON.png') }}">
                            </i>
                            <div class="srv-cnt">
                                <h3>AEPS</h3>

                            </div>
                        </div>
                    </div>
                    @endif
                     @if($aadharpayarray[0]['status']==1)
                    <div class="col pointer_class">
                        <div class="service-block1 bg-light-blue"   onclick="gotoLink( '{{ route('aadharpay') }}' )"  target="_blank"> 
                            <a class="block-link" id="FlightBookingTab" href="{{ route('aadharpay') }}" target="_blank"></a>

                            <i>
                                <img src="{{ asset('template_assets/AADHARPAY-ICON.png') }}">
                            </i>
                            <div class="srv-cnt">
                                <h3>AADHAR PAY</h3>

                            </div>
                        </div>
                    </div>
                    @else
                    <div class="col pointer_class">
                        <div class="service-block1 bg-light-blue" data-toggle="modal" data-target="#myModal">
                            <a class="block-link" id="FlightBookingTab" data-toggle="modal" data-target="#myModal"></a>

                            <i>
                                <img src="{{ asset('template_assets/AADHARPAY-ICON.png') }}">
                            </i>
                            <div class="srv-cnt">
                                <h3>AADHAR PAY</h3>

                            </div>
                        </div>
                    </div>
                    @endif
                    <!--<div class="col pointer_class" >-->
                    <!--    <div class="service-block1 bg-light-blue"  onclick="gotoLink( '{{ route('home',['service' => 'fast_tag']) }}' )">-->
                    <!--        <a class="block-link" id="FlightBookingTab" href="javascript:void(0);"-->
                    <!--            onclick="javascript:gotoflight();"></a>-->

                    <!--        <i>-->
                    <!--            <img src="{{ asset('template_new/img/fasttag.png') }}">-->
                    <!--        </i>-->
                    <!--        <div class="srv-cnt">-->
                    <!--            <h3>Fast Tag</h3>-->

                    <!--        </div>-->
                    <!--    </div>-->
                    <!--</div>-->
                     @if($iciciarray[0]['status']==1)
                    <div class="col pointer_class">
                        <div class="service-block1 bg-red" onclick="gotoLink( '{{ route('icicionboarding') }}' )">
                            <a class="block-link" id="FlightBookingTab" href="{{ route('icicionboarding') }}"></a>

                            <i>
                                <img src="{{ asset('template_assets/ICICI-ICON.png') }}">
                            </i>
                            <div class="srv-cnt">
                                <h3>ICICI Cash Deposit</h3>

                            </div>
                        </div>
                    </div>
                    @else
                    <div class="col pointer_class">
                        <div class="service-block1 bg-red"  data-toggle="modal" data-target="#myModal">
                            <a class="block-link" id="FlightBookingTab" data-toggle="modal" data-target="#myModal"></a>

                            <i>
                                <img src="{{ asset('template_assets/ICICI-ICON.png') }}">
                            </i>
                            <div class="srv-cnt">
                                <h3>ICICI Cash Deposit</h3>

                            </div>
                        </div>
                    </div>
                    @endif

                </div>

                <br>




                <div class="row">
                    <input type="hidden" id="recharge_api" value="{{ Config::get('constants.RECHARGE_API') }}">
                    <!-- Hidden Fields required during form submition -->
                    <input type="hidden" id="api_key" name="{{ Config::get('constants.RECH_MOB_DTH_RQ_KEY.API_KEY') }}" value="{{ $apiKey }}">           
                    <input type="hidden" id="user_id" name="{{ Config::get('constants.RECH_MOB_DTH_RQ_KEY.USER_ID') }}" value="{{ Auth::user()->userId }}">           
                    <input type="hidden" id="role_id" name="{{ Config::get('constants.RECH_MOB_DTH_RQ_KEY.ROLE_ID') }}" value="{{ Auth::user()->roleId }}"> 
                    <input type="hidden" id="operator-list" value="{{ json_encode($operatorList) }}">
                    <input type="hidden" value="{{ Config::get('constants.BILL_PAYMENTS_API.ELECTRICITY.GET_BILLER_DETAILS') }}" id="get_biller_details" name="get_biller_details">
                    <input type="hidden" value="{{ Config::get('constants.BILL_PAYMENTS_API.ELECTRICITY.PAY_BILL') }}" id="pay_bill" name="pay_bill">
                    <input type="hidden" value="{{ $biller_data['operator_id'] }}" id="operator_id" >
                    <input type="hidden" id="biller_by_id_api" value="{{ Config::get('constants.BILL_PAYMENTS_API.ELECTRICITY.GET_BILLER_BY_BILLER_ID') }}">
                    <input type="hidden" id="web_url" value="{{ Config::get('constants.WEBSITE_BASE_URL') }}">
                    <input type="hidden" id="payment_type" value="{{ $paymentType }}">

                    <!-- Hidden fields ends -->
                   
                    <div class="col-sm-12 col-md-12">
                        <div class=""  style="background-color:white;padding:20px;border-radius:25px;">
                            <div class="row">
                                @php
                                $dthresults = DB::select( DB::raw("SELECT * FROM tbl_user_services WHERE user_id = :somevariable and service_id= :serviceid"), array(
                                     'somevariable' => $userid,
                                       'serviceid' => 3
                                     ));
                                 $dtharray = json_decode(json_encode($dthresults), true);
                                 
                                 $mobileresults = DB::select( DB::raw("SELECT * FROM tbl_user_services WHERE user_id = :somevariable and service_id= :serviceid"), array(
                                             'somevariable' => $userid,
                                               'serviceid' => 1
                                             ));
                                 $mobilearray = json_decode(json_encode($mobileresults), true);
                                        @endphp
                                
                                
                                @foreach($serviceList as $i => $service)
                               
                                    @if($service['name']!='Life Insurance')
                                    <div class="col-md-2" style="padding-bottom:20px;border-right: 1px solid #e8e8e8;border-bottom: 1px solid #e8e8e8;"><a class="nav-link"  href="{{ route('home',['service' => $service['key']]) }}"
                                        role="tab"><span class="hidden-sm-up">
                                           <center> <img style="height:65px;width:65px;margin:5px;" src="{{ asset('template_new/img/') }}/{{ $service['logo'] }}" class="img-responsive mb-2"></span> 
                                            <span class="" style="margin-left:3px;font-size:15px;margin-top:2px;color:black;">{{ $service['name'] }}</span></center></a>
                                    </div>
                                    @else
                                    
                                    @endif
                             
                                @endforeach
                                <!--Additional Icon Started-->
                                <div class="col-md-2" style="border-right: 1px solid #e8e8e8;border-bottom: 1px solid #e8e8e8;"><a class="nav-link"  href="#"
                                        role="tab"><span class="hidden-sm-up">
                                           <center> <img style="height:57px;width:57px;margin:10px;" src="{{ asset('template_assets/PIPELINE-GAS-ICON.png') }}" class="img-responsive mb-2"></span> 
                                            <span class="" style="margin-left:3px;font-size:15px;margin-top:2px;color:black;">Pipeline Gas</span></center></a>
                                </div>
                                <div class="col-md-2" style="border-right: 1px solid #e8e8e8;border-bottom: 1px solid #e8e8e8;"><a class="nav-link"  href="#"
                                        role="tab"><span class="hidden-sm-up">
                                           <center> <img style="height:57px;width:50px;margin:14px;" src="{{ asset('template_assets/CABLE-TV-PNG-ICON.png') }}" class="img-responsive mb-2"></span> 
                                            <span class="" style="margin-left:3px;font-size:15px;margin-top:2px;color:black;">Cable Tv</span></center></a>
                                </div>
                                <div class="col-md-2" style="border-right: 1px solid #e8e8e8;border-bottom: 1px solid #e8e8e8;"><a class="nav-link"  href="#"
                                        role="tab"><span class="hidden-sm-up">
                                           <center> <img style="height:57px;width:50px;margin:14px;" src="{{ asset('template_assets/SUBSCRIPTION-ICON.png') }}" class="img-responsive mb-2"></span> 
                                            <span class="" style="margin-left:3px;font-size:15px;margin-top:2px;color:black;">Subscription</span></center></a>
                                </div>
                                <div class="col-md-2" style="border-right: 1px solid #e8e8e8;border-bottom: 1px solid #e8e8e8;"><a class="nav-link"  href="#"
                                        role="tab"><span class="hidden-sm-up">
                                           <center> <img style="height:57px;width:50px;margin:14px;" src="{{ asset('template_assets/MUNICIPAL-TAX-ICON.png') }}" class="img-responsive mb-2"></span> 
                                            <span class="" style="margin-left:3px;font-size:15px;margin-top:2px;color:black;">Municipal Tax</span></center></a>
                                </div>
                                <div class="col-md-2" style="border-right: 1px solid #e8e8e8;border-bottom: 1px solid #e8e8e8;"><a class="nav-link"  href="#"
                                        role="tab"><span class="hidden-sm-up">
                                           <center> <img style="height:57px;width:50px;margin:14px;" src="{{ asset('template_assets/AMAZONPAY-ICON.png') }}" class="img-responsive mb-2"></span> 
                                            <span class="" style="margin-left:3px;font-size:15px;margin-top:2px;color:black;">AmazonPay</span></center></a>
                                </div>
                                <!--Ends Here-->
                            </div>
                        
                            <!-- Tab panes -->
                            <div class="tab-content p-4" style="margin-left:-10px;">
                                <div class="alert alert-dismissible fade show" role="alert" id="alert_block" style="display:none;">
                                    <strong id="alert_head">SUCCESS</strong> <span id="alert_msg"> </span> .
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @foreach($serviceList as $i => $service)
                                @if($service['key'] == "mobile")
                                <div class="tab-pane {{ $service['key'] == $paymentType ? 'active' : '' }}" id="{{ $service['key']}}" role="tabpanel">
                                    <input type="hidden" name="service_type" id="">
                                    <div class="row">
                                        <div class="col-md-5 col-sm-12">
                                            <h2 class="SecTitle">Mobile Recharge</h2>
                                            <form id="recharge_prepaid" name="recharge_prepaid" method="post"
                                               class="CustomForm fl-form ng-pristine ng-valid" autocomplete="off">
                                                <input type="hidden" id="hidSubmitRecharge" name="hidSubmitRecharge"
                                                    value="Success" autocomplete="off">
                                                <div class="form-group">

                                                    <div class="fl-wrap fl-wrap-input">
                                                        <input type="text" class="form-control fl-input"
                                                            onkeydown="getCustName()" onkeyup="getOperator()"
                                                            id="txtMobileNo" maxlength="10" name="txtMobileNo"
                                                            placeholder="Mobile number" tabindex="1"
                                                            onkeypress="return isNumeric(event);"
                                                            data-placeholder="Enter Mobile Number.">
                                                    </div>
                                                   
                                                </div>



                                                <div class="form-group">

                                                    <div class="fl-wrap fl-wrap-select">
                                                        <select class="form-control fl-select" name="ddlOperator"
                                                            placeholder="Select company name." tabindex="2"
                                                            id="ddlOperator" data-placeholder="Select company name.">
                                                            <option value="">Operator</option>
                                                            <option value="">--Select--</option>
                                                            @if(isset($operatorList))
                                                                @foreach($operatorList as $i => $operator)
                                                                    @if($operator['servicesType']['alias'] == Config::get('constants.SERVICE_TYPE_ALIAS.MOBILE_PREPAID'))
                                                            
                                                            
                                                                    <option value="{{ $operator['operator_id'] }}">{{ $operator['operator_name'] }}</option>
                                                                    @endif
                                                                   
                                                                @endforeach
                                                            @endif
                                                            <!-- <option value="76">Tata Indicom Prepaid</option>
                                                            <option value="81">BSNL TopUp</option>
                                                            <option value="82">BSNL (Validity / Special)</option>
                                                            <option value="83">Vodafone Prepaid</option>
                                                            <option value="91">MTNL TopUp</option>
                                                            <option value="92">MTNL (Validity / Special)</option>
                                                            <option value="94">JIO</option> -->
                                                        </select>
                                                    </div>
                                                </div>

                                                <input type="hidden" id="opCircle" value=''>
                                                <div class="form-group">

                                                    <div class="fl-wrap fl-wrap-input fl-has-focus">
                                                        <input type="text"
                                                            style="cursor:text !important;background-color: white; "
                                                            class="form-control fl-input" id="txtAmount" maxlength="5"
                                                            name="txtAmount" onkeypress="return isNumeric(event);"
                                                            placeholder="Amount" tabindex="3"
                                                            onfocus="if (this.hasAttribute('readonly')) { this.removeAttribute('readonly'); this.blur(); this.focus();  }"
                                                            data-placeholder="Enter Amount"   autocomplete="off">
                                                            <div class="row" style="padding-top: 5px;">
                                                                <!-- <div class="col-2">
                                                                <button type="button" class="btn  btn-success" id="view_plans" style="display:none"> Plans</button>

                                                                </div> -->
                                                                <div class="col-3">
                                                                <button type="button" class="btn  btn-info" id="121_offers" style="display:none"> 121 Offers</button>

                                                                </div>
                                                            </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">

                                                    <div class="fl-wrap fl-wrap-input fl-has-focus">
                                                        <input type="password"
                                                            style="cursor:text !important;background-color: white;"
                                                            class="form-control fl-input" id="txtMobileTpin"
                                                            maxlength="4" name="txtTpin"
                                                            onkeypress="return isNumeric(event);" placeholder="MPIN"
                                                            tabindex="4"
                                                            onfocus="if (this.hasAttribute('readonly')) { this.removeAttribute('readonly'); this.blur(); this.focus();  }"
                                                            data-placeholder="Enter MPIN"  autocomplete="off">
                                                            <span class="error-mpin" style="color:red;  display:none;">MPIN is required</span>
                                                    </div>
                                                </div>
                                                
                                                <div class="FormButtons">
                                                    <div class="row">
                                                        <div class="col-2">
                                                        <input type="button"   class="btn btn-lg  success-grad" value="Submit"
                                                        id="btnRecharge" name="btnRecharge" tabindex="5"
                                                        onclick="confirmSubmit()">
                                                        </div>

                                                        <div class="col-3">
                                                        
                                                        <input type="button"
                                                        class="btn btn-lg success-grad" value="Check Offer"
                                                        id="view_plans" style="display:none" tabindex="5">
                                                        </div>
                                                    </div>
                                                    &nbsp;&nbsp;
                                                        <!-- <button id="subcharge_btn" type="button" class="btn success-grad " >Download Recipt </button> -->
                                                        
                                                        <!-- <button type="button" class="btn  btn-success" id="view_plans" style="display:none"> Check Offer</button> -->

                                                </div>
                                            </form>




                                        </div>
                                        <div class="col-md-7 col-sm-12" id="allPlanModal" style="display: none;">
                                            <ul class="nav nav-tabs customtab" role="tablist">
                                                <?php
                                                        $planTypes = ['121 Offer','TUP', 'FTT', '2G', '3G', 'SMS', 'LSC', 'OTR', 'RMG'];
                                                ?>
                                                @foreach($planTypes as $p_key => $p_value)
                                                <li class="nav-item">
                                                    <a id="nav-link-{{ $p_key }}" onclick="getAvailablePlans('{{ $p_value }}')" class="nav-link" data-toggle="pill" href="javascript:void(0)" >
                                                        <span >{{ $p_value }}</span>
                                                    </a>
                                                </li>
                                                @endforeach
                                            </ul> 

                                            <div class="row" >
                                                <div class="col-12">
                                                    <table class="table table-sm table-centered table-striped table-bordered  border" id="plan-table">
                                                        <tbody id="plan_info">
                                                            <tr>
                                                                <td class="label">Amount</td>
                                                                <td class="label" >Details</td>
                                                                <td class="label">Talktime</td>
                                                                <td class="label">Validity</td>
                                                            </tr>
                                                            <tr class="plan_info" id="plan_info">
                                                                
                                                            </tr>

                                                            <!-- <tr ng-repeat="plan in allPlans" style="cursor:pointer" ng-click="setFinalAmnt(plan.amount)">
                                                                <td colspan="4" ng-if="plan.error" ng-bind="plan.error"></td>
                                                                <td ng-if="!plan.error" ><i class="mdi mdi-currency-inr"></i> <span ng-bind="plan.amount"></span></td>
                                                                <td ng-if="!plan.error" ng-bind="plan.detail"></td>
                                                                <td ng-if="!plan.error" ng-bind="plan.talktime"></td>
                                                                <td ng-if="!plan.error" ng-bind="plan.validity"></td>
                                                            </tr> -->
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-7 col-sm-12" id="offerModal" style="display: none;">
                                           
                                            <div class="row" >
                                                <div class="col-12">
                                                    <table class="table table-sm table-centered table-striped table-bordered  border" id="offer-table">
                                                        <tbody id="offer_info">
                                                            <tr>
                                                                <td class="label">Price</td>
                                                                <td class="label" >Details</td>
                                                               
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @elseif($service['key'] == "dth")
                                <div class="tab-pane {{ $service['key'] == $paymentType ? 'active' : '' }}" id="{{ $service['key']}}" role="tabpanel">
                                <input type="hidden" name="service_type" id="">
                                    <div class="row">
                                        <div class="col-md-5 col-sm-12">
                                            <h2 class="SecTitle">DTH Recharge</h2>
                                            <form id="recharge_dth" name="recharge_dth" method="post"
                                               class="CustomForm fl-form ng-pristine ng-valid" autocomplete="off">
                                                <input type="hidden" id="hidSubmitRechargeDTH" name="hidSubmitRechargeDTH"
                                                    value="Success" autocomplete="off">

                                                
                                                <div class="form-group">

                                                    <div class="fl-wrap fl-wrap-select">
                                                        <select class="form-control fl-select" name="ddlOperator_dth"
                                                            placeholder="Select company name." tabindex="2"
                                                            id="ddlOperator_dth" data-placeholder="Select company name.">
                                                            <option value="">Operator</option>
                                                            <option value="">--Select--</option>
                                                            @if(isset($operatorList))
                                                                @foreach($operatorList as $i => $operator)
                                                                    @if($operator['servicesType']['alias'] == Config::get('constants.SERVICE_TYPE_ALIAS.DTH'))
                                                            
                                                            
                                                                    <option value="{{ $operator['operator_id'] }}">{{ $operator['operator_name'] }}</option>
                                                                    @endif
                                                                
                                                                @endforeach
                                                            @endif
                                                            
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">

                                                    <div class="fl-wrap fl-wrap-input">
                                                        <input type="text" class="form-control fl-input"
                                                           
                                                            id="txtCustomerNo" name="txtCustomerNo"
                                                            placeholder="Customer ID" tabindex="1"
                                                          
                                                            onkeyup="accountInfo();"
                                                            data-placeholder="Enter Customer ID "  autocomplete="off">
                                                            <div class="row" style="padding-top: 5px;">
                                                                <div class="col-2">
                                                                <button type="button" class="btn  btn-info" id="view_plans_dth" style="display:none"> Plans</button>

                                                                </div>
                                                                
                                                            </div>
                                                    </div>
                                                   
                                                </div>



                                             
                                                <input type="hidden" id="opCircle" value=''>
                                                <div class="form-group">

                                                    <div class="fl-wrap fl-wrap-input fl-has-focus">
                                                        <input type="text"
                                                            style="cursor:text !important;background-color: white; "
                                                            class="form-control fl-input" id="txtAmountDTH" maxlength="5"
                                                            name="txtAmountDTH" onkeypress="return isNumeric(event);"
                                                            placeholder="Amount" tabindex="3"
                                                            onfocus="if (this.hasAttribute('readonly')) { this.removeAttribute('readonly'); this.blur(); this.focus();  }"
                                                            data-placeholder="Enter Amount" >
                                                            <div class="row" style="padding-top: 5px;">
                                                                <div class="col-2">
                                                                <button type="button" class="btn  btn-success" id="view_plans" style="display:none"> Plans</button>

                                                                </div>
                                                                
                                                            </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">

                                                    <div class="fl-wrap fl-wrap-input fl-has-focus">
                                                        <input type="password"
                                                            style="cursor:text !important;background-color: white;"
                                                            class="form-control fl-input" id="txtTpin_DTH"
                                                            maxlength="4" name="txtTpin_DTH"
                                                            onkeypress="return isNumeric(event);" placeholder="MPIN"
                                                            tabindex="4"
                                                            onfocus="if (this.hasAttribute('readonly')) { this.removeAttribute('readonly'); this.blur(); this.focus();  }"
                                                            data-placeholder="Enter MPIN">
                                                            <span class="error-mpin" style="color:red;  display:none;">MPIN is required</span>
                                                    </div>
                                                </div>

                                                <div class="FormButtons">
                                                    <input type="button"   class="btn btn-lg  success-grad" value="Submit"
                                                        id="btnRecharge" name="btnRecharge" tabindex="5"
                                                        onclick="confirmSubmit()">&nbsp;&nbsp;
                                                        <!-- <input type="button"
                                                        class="btn  btn-lg btn-success view-plan-btn" value="Check Offer"
                                                        id="btnOffer" name="btnOffer" tabindex="5"
                                                        onclick="getRoffer()"> -->
                                                </div>
                                            </form>




                                        </div>
                                        <div class="col-md-7 col-sm-12" id="allPlanModal" style="display: none;">
                                            <ul class="nav nav-tabs customtab" role="tablist">
                                                <?php
                                                        $planTypes = ['TUP', 'FTT', '2G', '3G', 'SMS', 'LSC', 'OTR', 'RMG'];
                                                ?>
                                                @foreach($planTypes as $p_key => $p_value)
                                                <li class="nav-item">
                                                    <a id="nav-link-{{ $p_key }}" onclick="getAvailablePlans('{{ $p_value }}')" class="nav-link" data-toggle="pill" href="javascript:void(0)" >
                                                        <span >{{ $p_value }}</span>
                                                    </a>
                                                </li>
                                                @endforeach
                                            </ul> 

                                            <div class="row" >
                                                <div class="col-12">
                                                    <table class="table table-sm table-centered table-striped table-bordered  border" id="plan-table">
                                                        <tbody id="plan_info">
                                                            <tr>
                                                                <td class="label">Amount</td>
                                                                <td class="label" >Details</td>
                                                                <td class="label">Talktime</td>
                                                                <td class="label">Validity</td>
                                                            </tr>
                                                            <tr class="plan_info" id="plan_info">
                                                                
                                                            </tr>

                                                            <!-- <tr ng-repeat="plan in allPlans" style="cursor:pointer" ng-click="setFinalAmnt(plan.amount)">
                                                                <td colspan="4" ng-if="plan.error" ng-bind="plan.error"></td>
                                                                <td ng-if="!plan.error" ><i class="mdi mdi-currency-inr"></i> <span ng-bind="plan.amount"></span></td>
                                                                <td ng-if="!plan.error" ng-bind="plan.detail"></td>
                                                                <td ng-if="!plan.error" ng-bind="plan.talktime"></td>
                                                                <td ng-if="!plan.error" ng-bind="plan.validity"></td>
                                                            </tr> -->
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-7 col-sm-12" id="account_details" style="display: none;">
                                           <h4> Account Info </h4>
                                            <div class="row" >
                                                <div class="col-12">
                                                    <table class="table table-sm equal-cols table-striped table-bordered  border" id="offer-table">
                                                        <tbody id="offer_info">
                                                           <tr>
                                                                <td class="label"><i class="mdi mdi-account-box"></i> Customer Id:</td>
                                                                <td id="dthAcInfo_vc"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="label"><i class="mdi mdi-account"></i> Name:</td>
                                                                <td  id="dthAcInfo_Name"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="label"><i class="mdi mdi-cellphone"></i> Registered No.:</td>
                                                                <td  id="dthAcInfo_Rmn"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="label"> Current Balance:<i class="mdi mdi-currency-inr"></i></td>
                                                                <td id="dthAcInfo_Balance"></td>
                                                            </tr>

                                                            <tr>
                                                                <td class="label"><i class="mdi mdi-television"></i> Plan:</td>
                                                                <td id="dthAcInfo_Plan"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="label"><i class="mdi mdi-calendar"></i> Next Recharge Date:</td>
                                                                <td  id="dthAcInfo_Next_Recharge_Date"></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @else
                                    @if($paymentType == $service['key'])
                                    <input type="hidden" id="service_name" value="{{ $service['name'] }}">
                                    <input type="hidden" id="bill_order_id" value="" >
                                    <div class="tab-pane {{ $service['key'] == $paymentType ? 'active' : '' }}" id="{{ $service['key']}}" role="tabpanel"> 
                                        @if( ($service['key'] == "electricity") || ($service['key'] == "water") )
                                        <input type="hidden" id="state_biller_api" value="{{ Config::get('constants.BILL_PAYMENTS_API.ELECTRICITY.GET_BILLER_BY_STATE') }}">
                                        <div class="row">
                                            <div class="col-md-5 col-sm-12">
                                                <!-- <h2 class="SecTitle">{{-- $service['name'] --}}</h2> -->
                                                <div class="SecTitle" style="font-size: 1.5rem;">{{ $service['name'] }} <img src="{{ asset('template_new/img/') }}/BharatBillPayLogo.jpg" class="" style="float: right;"></div>
                                                
                                                <form id="electricity_form" name="electricity_form" method="post"
                                                                        class="CustomForm fl-form ng-pristine ng-valid" autocomplete="off">
                                                <div class="form-group">

                                                    <div class="fl-wrap fl-wrap-select">
                                                        <select class="form-control fl-select" name="electricity_state"
                                                            placeholder="Select State." tabindex="2" onchange="onBillerState(this.id)"
                                                            id="electricity_state" data-placeholder="Select State.">
                                                        
                                                            <option value="">--Select State--</option>
                                                            @foreach($biller_data['all_states'] as $state_key => $state_value)
                                                                <option value="{{ $state_value->state_code }}">{{ $state_value->state_name }}</option>
                                                            @endforeach
                                                    
                                                            
                                                        </select>
                                                    </div>
                                                    

                                                </div>
                                                <div class="form-group">
                                                    <div class="fl-wrap fl-wrap-select">
                                                        <select class="form-control fl-select" name="elect_biller"
                                                                placeholder="Select Biller." tabindex="2" onchange=""
                                                                id="elect_biller" data-placeholder="Select State.">
                                                    
                                                                <option value="">--Select Biller--</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div id="electric_inputparam">

                                                </div>
                                                </form>
                                            </div>

                                            <div class="col-md-7 col-sm-12" id="bill_details" style="display: none;">
                                                <h4> Account Info </h4>
                                                    <div class="row" >
                                                        <div class="col-12">
                                                            <table class="table table-sm equal-cols table-striped table-bordered  border" id="bill-table">
                                                                    <tbody id="bill_info">
                                                                    </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="col-12">
                                                            <div id="payAmt">
                                                                 
                                                              
                                                                
                                                
                                                            </div>
                                                            <!-- <button id="pay_btn" class="btn btn-block success-grad btn-lg" onclick="procced_pay()"> Pay Now</button> -->
                                                            <button type="button" id="pay_btn_test" class="btn btn-block success-grad btn-lg" > Pay Now</button>

                                                            
                                                        </div>
                                                    </div>
                                            </div>

                                            <div class="col-md-7 col-sm-12" id="bill_paid" style="display: none;">
                                                <h4> Account Info </h4>
                                                    <div class="row" >
                                                        <div class="col-12">
                                                            <table class="table table-sm equal-cols table-striped table-bordered  border" id="bill-table">
                                                                    <tbody id="bill_paid_info">
                                                                    </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="col-12">
                                                            <div id="paid_payAmt">
                                                                 
                                                              
                                                                
                                                
                                                            </div>
                                                            <button id="subcharge_btn" type="button" class="btn btn-block success-grad " >Download Recipt </button>
                                                            
                                                        </div>
                                                    </div>
                                            </div>


                                        </div>
                                        @elseif($service['key'] == "electricity_new")
                                        <input type="hidden" id="state_biller_api" value="{{ Config::get('constants.BILL_PAYMENTS_API.ELECTRICITY.GET_BILLER_BY_STATE') }}">
                                        <input type="hidden" value="{{ Config::get('constants.BILL_PAYMENTS_API.ELECTRICITY.GET_BILLER_DETAILS_NEW') }}" id="get_biller_details_new" name="get_biller_details_new">
                                        <input type="hidden" value="{{ Config::get('constants.BILL_PAYMENTS_API.ELECTRICITY.PAY_BILL_NEW') }}" id="pay_bill_new" name="pay_bill_new">
                                        <div class="row">
                                            <div class="col-md-5 col-sm-12">
                                                <!-- <h2 class="SecTitle">{{-- $service['name'] --}}</h2> -->
                                                <div class="SecTitle" style="font-size: 1.5rem;">{{ $service['name'] }} <img src="{{ asset('template_new/img/') }}/BharatBillPayLogo.jpg" class="" style="float: right;"></div>
                                                
                                                <form id="electricity_form" name="electricity_form" method="post"
                                                                        class="CustomForm fl-form ng-pristine ng-valid" autocomplete="off">
                                                <div class="form-group">

                                                    <div class="fl-wrap fl-wrap-select">
                                                        <select class="form-control fl-select" name="electricity_state_new"
                                                            placeholder="Select State." tabindex="2" onchange="onBillerStateNew(this.id)"
                                                            id="electricity_state_new" data-placeholder="Select State.">
                                                        
                                                            <option value="">--Select State--</option>
                                                            @foreach($biller_data['all_states'] as $state_key => $state_value)
                                                                <option value="{{ $state_value->state_code }}">{{ $state_value->state_name }}</option>
                                                            @endforeach
                                                    
                                                            
                                                        </select>
                                                    </div>
                                                    

                                                </div>
                                                <div class="form-group">
                                                    <div class="fl-wrap fl-wrap-select">
                                                        <select class="form-control fl-select" name="elect_biller_new"
                                                                placeholder="Select Biller." tabindex="2" onchange=""
                                                                id="elect_biller_new" data-placeholder="Select State.">
                                                    
                                                                <option value="">--Select Biller--</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div id="electric_inputparam">

                                                </div>
                                                </form>
                                            </div>

                                            <div class="col-md-7 col-sm-12" id="bill_details" style="display: none;">
                                                <h4> Account Info </h4>
                                                    <div class="row" >
                                                        <div class="col-12">
                                                            <table class="table table-sm equal-cols table-striped table-bordered  border" id="bill-table">
                                                                    <tbody id="bill_info">
                                                                    </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="col-12">
                                                            <div id="payAmt">
                                                                 
                                                              
                                                                
                                                
                                                            </div>
                                                            <!-- <button id="pay_btn" class="btn btn-block success-grad btn-lg" onclick="procced_pay()"> Pay Now</button> -->
                                                            <button type="button" id="pay_btn_new" class="btn btn-block success-grad btn-lg" > Pay Now</button>

                                                            
                                                        </div>
                                                    </div>
                                            </div>

                                            <div class="col-md-7 col-sm-12" id="bill_paid" style="display: none;">
                                                <h4> Account Info </h4>
                                                    <div class="row" >
                                                        <div class="col-12">
                                                            <table class="table table-sm equal-cols table-striped table-bordered  border" id="bill-table">
                                                                    <tbody id="bill_paid_info">
                                                                    </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="col-12">
                                                            <div id="paid_payAmt">
                                                                 
                                                              
                                                                
                                                
                                                            </div>
                                                            <button id="subcharge_btn" type="button" class="btn btn-block success-grad " >Download Recipt </button>
                                                            
                                                        </div>
                                                    </div>
                                            </div>


                                        </div>
                                        @elseif($service['key'] == "postpaid")
                                        <input type="hidden" id="state_biller_api" value="{{ Config::get('constants.BILL_PAYMENTS_API.ELECTRICITY.GET_BILLER_BY_STATE') }}">
                                        <div class="row">
                                            <div class="col-md-5 col-sm-12">
                                                <!-- <h2 class="SecTitle">{{-- $service['name'] --}}</h2> -->
                                                <div class="SecTitle" style="font-size: 1.5rem;">{{ $service['name'] }} <img src="{{ asset('template_new/img/') }}/BharatBillPayLogo.jpg" class="" style="float: right;"></div>

                                                <form id="electricity_form" name="electricity_form" method="post"
                                                                        class="CustomForm fl-form ng-pristine ng-valid" autocomplete="off">
                                               
                                                <div class="form-group">
                                                    <div class="fl-wrap fl-wrap-select">
                                                        <select class="form-control fl-select" name="elect_biller"
                                                                placeholder="Select Biller." tabindex="2" onchange=""
                                                                id="elect_biller" data-placeholder="Select State.">
                                                                <option value="">--Select Operator--</option>
                                                                @if(isset($biller['result']))
                                                                    @foreach($biller['result'] as $i => $operator)
                                                                    <option value="{{ $operator['billerId'] }}">{{ $operator['billerName'] }}</option>
                                                                    @endforeach
                                                                @endif
                                                               
                                                        </select>
                                                    </div>
                                                </div>
                                                <div id="electric_inputparam">

                                                </div>
                                                </form>
                                            </div>

                                            <div class="col-md-7 col-sm-12" id="bill_details" style="display: none;">
                                                <h4> Account Info </h4>
                                                    <div class="row" >
                                                        <div class="col-12">
                                                            <table class="table table-sm equal-cols table-striped table-bordered  border" id="bill-table">
                                                                    <tbody id="bill_info">
                                                                    </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="col-12">
                                                            <div id="payAmt">
                                                                 
                                                              
                                                                
                                                
                                                            </div>
                                                            <!-- <button id="pay_btn" class="btn btn-block success-grad btn-lg" onclick="procced_pay()"> Pay Now</button> -->
                                                            <button type="button" id="pay_btn_test" class="btn btn-block success-grad btn-lg" > Pay Now</button>

                                                        </div>
                                                    </div>
                                            </div>

                                            <div class="col-md-7 col-sm-12" id="bill_paid" style="display: none;">
                                                <h4> Account Info </h4>
                                                    <div class="row" >
                                                        <div class="col-12">
                                                            <table class="table table-sm equal-cols table-striped table-bordered  border" id="bill-table">
                                                                    <tbody id="bill_paid_info">
                                                                    </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="col-12">
                                                            <div id="paid_payAmt">
                                                                 
                                                              
                                                                
                                                
                                                            </div>
                                                            <button id="subcharge_btn" type="button" class="btn btn-block success-grad " >Download Recipt </button>
                                                        </div>
                                                    </div>
                                            </div>


                                        </div>
                                        @elseif ($service['key'] == "education") 
                                        <input type="hidden" id="state_biller_api" value="{{ Config::get('constants.BILL_PAYMENTS_API.ELECTRICITY.GET_BILLER_BY_STATE') }}">
                                        <input type="hidden" id="city_by_state_api" value="{{ Config::get('constants.BILL_PAYMENTS_API.ELECTRICITY.GET_CITY_BY_STATE_CODE') }}">
                                        <div class="row">
                                            <div class="col-md-5 col-sm-12">
                                                <!-- <h2 class="SecTitle">{{-- $service['name'] --}}</h2> -->
                                                <div class="SecTitle" style="font-size: 1.5rem;">{{ $service['name'] }} <img src="{{ asset('template_new/img/') }}/BharatBillPayLogo.jpg" class="" style="float: right;"></div>

                                                <form id="electricity_form" name="electricity_form" method="post"
                                                                        class="CustomForm fl-form ng-pristine ng-valid" autocomplete="off">
                                                <div class="form-group">

                                                    <div class="fl-wrap fl-wrap-select">
                                                        <select class="form-control fl-select" name="electricity_state"
                                                            placeholder="Select State." tabindex="2" onchange="onBillerState(this.id)"
                                                            id="electricity_state" data-placeholder="Select State.">
                                                        
                                                            <option value="">--Select State--</option>
                                                            @foreach($biller_data['all_states'] as $state_key => $state_value)
                                                                <option value="{{ $state_value->state_code }}">{{ $state_value->state_name }}</option>
                                                            @endforeach
                                                    
                                                            
                                                        </select>
                                                    </div>
                                                    

                                                </div>

                                                <div class="form-group">

                                                    <div class="fl-wrap fl-wrap-select">
                                                        <select class="form-control fl-select" name="electricity_city"
                                                            placeholder="Select City." tabindex="2" onchange="onBillerState(this.id)"
                                                            id="electricity_city" data-placeholder="Select City.">
                                                        
                                                            <option value="">--Select City--</option>
                                                           

                                                            
                                                        </select>
                                                    </div>


                                                </div>

                                                <div class="form-group">
                                                    <div class="fl-wrap fl-wrap-select">
                                                        <select class="form-control fl-select" name="elect_biller"
                                                                placeholder="Select Biller." tabindex="2" onchange=""
                                                                id="elect_biller" data-placeholder="Select State.">
                                                    
                                                                <option value="">--Select Biller--</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div id="electric_inputparam">

                                                </div>
                                                </form>
                                            </div>

                                            <div class="col-md-7 col-sm-12" id="bill_details" style="display: none;">
                                                <h4> Account Info </h4>
                                                    <div class="row" >
                                                        <div class="col-12">
                                                            <table class="table table-sm equal-cols table-striped table-bordered  border" id="bill-table">
                                                                    <tbody id="bill_info">
                                                                    </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="col-12">
                                                            <div id="payAmt">
                                                                 
                                                              
                                                                
                                                
                                                            </div>
                                                            <!-- <button id="pay_btn" class="btn btn-block success-grad btn-lg" onclick="procced_pay()"> Pay Now</button> -->
                                                            <button type="button" id="pay_btn_test" class="btn btn-block success-grad btn-lg" > Pay Now</button>

                                                        </div>
                                                    </div>
                                            </div>

                                            <div class="col-md-7 col-sm-12" id="bill_paid" style="display: none;">
                                                <h4> Account Info </h4>
                                                    <div class="row" >
                                                        <div class="col-12">
                                                            <table class="table table-sm equal-cols table-striped table-bordered  border" id="bill-table">
                                                                    <tbody id="bill_paid_info">
                                                                    </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="col-12">
                                                            <div id="paid_payAmt">
                                                                 
                                                              
                                                                
                                                
                                                            </div>
                                                            <button id="subcharge_btn" type="button" class="btn btn-block success-grad " >Download Recipt </button>
                                                            
                                                        </div>
                                                    </div>
                                            </div>


                                        </div>
                                        @else

                                        <div class="row">
                                            <div class="col-md-5 col-sm-12">
                                                <!-- <h2 class="SecTitle">{{-- $service['name'] --}}</h2> -->
                                                <div class="SecTitle" style="font-size: 1.5rem;">{{ $service['name'] }} <img src="{{ asset('template_new/img/') }}/BharatBillPayLogo.jpg" class="" style="float: right;"></div>

                                                <form id="electricity_form" name="electricity_form" method="post"
                                                                        class="CustomForm fl-form ng-pristine ng-valid" autocomplete="off">
                                                
                                                <div class="form-group">
                                                    <div class="fl-wrap fl-wrap-select">
                                                        <select class="form-control fl-select" name="elect_biller"
                                                                placeholder="Select Biller." tabindex="2" onchange=""
                                                                id="elect_biller" data-placeholder="Select State.">
                                                    
                                                                <option value="">--Select Biller--</option>
                                                                @if(isset($biller) && (count($biller)>0) && $biller['result'] )
                                                                    @foreach( $biller['result'] as $biller_key => $biller_value)
                                                                        <option value="{{ $biller_value['billerId'] }}">{{ $biller_value['billerName'] }}</option>                                                            
                                                                    @endforeach
                                                                @endif
                                                        </select>
                                                    </div>
                                                </div>
                                                <div id="electric_inputparam">

                                                </div>
                                                </form>
                                            </div>

                                            <div class="col-md-7 col-sm-12" id="bill_details" style="display: none;">
                                                <h4> Account Info </h4>
                                                    <div class="row" >
                                                        <div class="col-12">
                                                            <table class="table table-sm equal-cols table-striped table-bordered  border" id="bill-table">
                                                                    <tbody id="bill_info">
                                                                    </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="col-12">
                                                            <div id="payAmt">
                                                                 
                                                              
                                                                
                                                
                                                            </div>
                                                            <!-- <button id="pay_btn" class="btn btn-block success-grad btn-lg" onclick="procced_pay()"> Pay Now</button> -->
                                                            <button type="button" id="pay_btn_test" class="btn btn-block success-grad btn-lg" > Pay Now</button>
                                                        </div>
                                                    </div>
                                            </div>

                                            <div class="col-md-7 col-sm-12" id="bill_paid" style="display: none;">
                                                <h4> Account Info </h4>
                                                    <div class="row" >
                                                        <div class="col-12">
                                                            <table class="table table-sm equal-cols table-striped table-bordered  border" id="bill-table">
                                                                    <tbody id="bill_paid_info">
                                                                    </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="col-12">
                                                            <div id="paid_payAmt">
                                                                 
                                                              
                                                                
                                                
                                                            </div>
                                                            
                                                        </div>
                                                        <button id="subcharge_btn" type="button" class="btn btn-block success-grad " >Download Recipt </button>

                                                    </div>
                                            </div>


                                        </div>

                                        @endif
                                    </div>
                                    @endif
                                @endif
                                <!-- <div class="tab-pane" id="craditcard" role="tabpanel">Cradit Card Bill Form</div>
                                <div class="tab-pane" id="education" role="tabpanel">Education Form</div>
                                <div class="tab-pane" id="lifeinssurance" role="tabpanel">Life Insurance Form</div>
                                <div class="tab-pane" id="insurance" role="tabpanel">Insurance Form</div>
                                <div class="tab-pane" id="broadband" role="tabpanel">Broadband Form</div>
                                <div class="tab-pane" id="waterbill" role="tabpanel">Water Bill Form</div>
                                <div class="tab-pane" id="lpg" role="tabpanel">LPG Bill Form</div> -->

                            @endforeach
                            </div>
                            </div>
                            <!-- <div class="card-body pt-0">

                            </div> -->
                        </div>


                    </div>
                </div>

                <!-- end tabs -->
        <!-- Surcharge Modal starts -->
        <div class="modal fade" id="surchargeModal">
            <div class="modal-dialog modal-md">
            <div class="modal-content">
            
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Surcharge ? </h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
            
                <!-- Modal body -->
                    <div class="modal-body">
                        <div class="form-group btn-group">
                            <button type="button" class="btn btn-warning" style="pointer-events:none"><i class="mdi mdi-currency-inr"></i></button>
                            
                            <input type="hidden" value="" id="resp_order_id" class="" >
                        
                            <input type="text" id="inputsurCharge" class="form-control" placeholder="Enter here" ng-model="surCharge">
                            <button type="button" onclick="viewInvice()" class="btn btn-info success-grad"  >Proceed</button>
                        </div>
                    </div>
            </div>
            </div>
        </div>
        <!-- Surcharge Modal ends -->


        <!-- confirmation Modal starts -->
        <div class="modal fade" id="comfirmtionModal">
            <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
            
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Confirmation </h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                
                <!-- Modal body -->
                    <div class="modal-body">
                        <center>
                            <div class="col-8">
                            <table id="confirmation-table" class="table" style="font-size: 18px">
                                
                                <tr>
                                
                                    <th class="text-red" >
                                    @if ($paymentType == 'mobile')
                                    Mobile No. <span class="colon-algin">:</span>
                                    @elseif($paymentType == 'dth')
                                    Customer ID <span class="colon-algin">:</span>
                                    @endif
                                    </th>
                                    <td id="confirm_customer_value">  </td>
                                </tr>
                                <tr>
                                    <th class="text-red">
                                                Operator <span class="colon-algin">:</span>
                                    </th>
                                    <td id="confirm_operator_value"> </td>
                                </tr>
                                
                            
                                <tr>
                                    <th class="text-red">Amount <span class="colon-algin">:</span> </th>
                                    <td id="confirm_amt_value"></td>
                                </tr>
                            
                            </table>
                            </div>
                            
                    
                        </center>
                    
                    </div>
                    <div class="modal-footer" style="justify-content: center;">
                        <button type="button" class="btn btn-primary success-grad btn-lg" id="confirmed_recharge">Confirm</button>
                        <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal">Close</button>
                    </div>
            </div>
            </div>
        </div>
        <!-- confirmation Modal ends -->

        <!-- verification Modal starts -->
        <div class="modal fade" id="rechargeDoneModel">
            <div class="modal-dialog modal-md">
            <div class="modal-content">
            
                <!-- Modal Header -->
                <div class="modal-header">
                    <!-- <h4 class="modal-title">Verification </h4> -->
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                
                <!-- Modal body -->
                    <div class="modal-body">
                        <center>

                        <input type="hidden" id="web_url" value="{{ Config::get('constants.WEBSITE_BASE_URL') }}">
                        <!-- <form action="{{-- route('preview_recipt') --}}" method="post"> -->
                            <!-- @csrf -->
                            
                                <img src="{{ asset('template_new/img/verify_ic.png') }}" alt="verified" style="width: 75px; display:none;" id="resp_success_logo">
                                <img src="{{ asset('template_new/img/pending_ic.png') }}" alt="pending" style="width: 95px; display:none;" id="resp_failed_logo" >
                                <h3 class="text-success" id="modal_resp_msg"></h3>
                    
                                <div class="col-8">
                                    <table id="recharge_resp-table" class="table" style="font-size: 18px">
                                        
                                        <tr>
                                        
                                            <th class="text-red" >
                                            @if ($paymentType == 'mobile')
                                            Mobile No. <span class="colon-algin">:</span>
                                            @elseif($paymentType == 'dth')
                                            Customer ID <span class="colon-algin">:</span>
                                            @endif
                                            </th>
                                            <td id="recharge_resp_customer_value">  </td>
                                        </tr>
                                        <tr>
                                            <th class="text-red">
                                                        Operator <span class="colon-algin">:</span>
                                            </th>
                                            <td id="recharge_resp_operator_value"> </td>
                                        </tr>
                                        
                                        
                                        <tr>
                                            <th class="text-red">Amount <span class="colon-algin">:</span></th>
                                            <td id="recharge_resp_amt_value"></td>
                                        </tr>
                                        
                                    </table>
                                </div>
                            
                    
                    
                    
            
                        
                            
                            
                        
                        <!-- </form> -->
                        </center>
                    </div>
                    <div class="modal-footer" style="justify-content: center;">
                        <button type="button" class="btn btn-primary success-grad btn-lg" id="recharge_ok">OK</button>
                    </div>
            </div>
            </div>
        </div>
        <!-- verification Modal ends -->


        <!--All BBPS confirmation Modal starts -->
        <div class="modal fade" id="comfirmtionBBPSModal">
            <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
            
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Confirmation </h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                
                <!-- Modal body -->
                    <div class="modal-body">
                        <center>
                            <div class="col-8">
                            <table id="confirmation-bbps-table" class="table" style="font-size: 18px">
                                
                            
                            
                            </table>
                            </div>
                            
                    
                        </center>
                    
                    </div>
                    <div class="modal-footer" style="justify-content: center;">
                        <button type="button" class="btn btn-primary success-grad btn-lg" id="confirmed_bbps">Confirm</button>
                        <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal">Close</button>
                    </div>
            </div>
            </div>
        </div>
        <!-- All BBPS Modal ends -->

                <!-- ============================================================== -->
                <!-- footer -->
                <!-- ============================================================== -->
                <!-- <footer class="footer text-center"> -->
                    <!-- Copyright @ SMARTPAY - Making India Digital. -->
                    <!-- <a href="https://wrappixel.com">WrapPixel</a>. -->
                <!-- </footer> -->
                <!-- ============================================================== -->
                <!-- End footer -->
                <!-- ============================================================== -->
    </div>

            
  
    <script src="{{ asset('dist\service_type\js\custom_service.js') }}"></script>
    <!-- <script src="{{ asset('dist\service_type\js\serviceValidation.js') }}"></script> -->
    <script src="{{ asset('template_new\assets\libs\select2\dist\js\select2.full.min.js') }}"></script>
    <script src="{{ asset('template_new\assets\libs\select2\dist\js\select2.min.js') }}"></script>
    <script src="{{ asset('template_new\dist\js\pages\forms\select2\select2.init.js') }}"></script>
    <script>
    function gotoLink(link){
            window.location = link;
            
        }
    </script>
@else
@if( Auth::user()->roleId != Config::get('constants.DISTRIBUTOR'))
<section>
@endif
    <!-- ============================================================== -->
    <!-- Container fluid Admin starts -->
    <!-- ============================================================== -->
    @if( Auth::user()->roleId == Config::get('constants.ADMIN'))
        <div class="page-content container-fluid">
            <!-- ============================================================== -->
            <!-- Card Group  -->
            <!-- ============================================================== -->
            <div class="card-group" style="margin-top:30px">
                <div class="card p-2 p-lg-3">
                    <div class="p-lg-3 p-2">
                        <div class="d-flex align-items-center">
                            <button class="btn btn-circle btn-danger text-white btn-lg" href="javascript:void(0)">
                            <i class="mdi mdi-account-convert"></i>
                        </button>
                            <div class="ml-4" style="width: 38%;">
                                <h4 class="font-light">Total Distributors</h4>
                                <div class="progress">
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $totalUser ? ($dTCount/$totalUser)*100 : $totalUser }}%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="40"></div>
                                </div>
                            </div>
                            <div class="ml-auto">
                                <h2 class="display-7 mb-0">{{ $dTCount }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card p-2 p-lg-3">
                    <div class="p-lg-3 p-2">
                        <div class="d-flex align-items-center">
                            <button class="btn btn-circle btn-cyan text-white btn-lg" href="javascript:void(0)">
                            <i class="mdi mdi-account-outline"></i>
                        </button>
                            <div class="ml-4" style="width: 38%;">
                                <h4 class="font-light">Total FOSs</h4>
                                <div class="progress">
                                    <div class="progress-bar bg-cyan" role="progressbar" style="width: {{$totalUser ? ($fosCount/$totalUser)*100 : $totalUser }}%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="40"></div>
                                </div>
                            </div>
                            <div class="ml-auto">
                                <h2 class="display-7 mb-0">{{ $fosCount }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card p-2 p-lg-3">
                    <div class="p-lg-3 p-2">
                        <div class="d-flex align-items-center">
                            <button class="btn btn-circle btn-warning text-white btn-lg" href="javascript:void(0)">
                            <i class="mdi mdi-account-switch"></i>
                        </button>
                            <div class="ml-4" style="width: 38%;">
                                <h4 class="font-light">Total Retailers</h4>
                                <div class="progress">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $totalUser ? ($rTCount/$totalUser)*100 : $totalUser }}%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="40"></div>
                                </div>
                            </div>
                            <div class="ml-auto">
                                <h2 class="display-7 mb-0">{{ $rTCount }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-group" style="margin-top:30px">
                <div class="card p-2 p-lg-3">
                    <div class="p-lg-3 p-2">
                        <div class="d-flex align-items-center">
                            <button class="btn btn-circle btn-info text-white btn-lg" href="javascript:void(0)">
                            <i class="mdi mdi-cash-100"></i>
                        </button>
                            <div class="ml-4" style="width: 38%;">
                                <h4 class="font-light">Total API Balance</h4>
                                <div class="progress">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: {{ $totalApiBalance ? ($totalApiBalance/$totalApiBalance)*100 : $totalApiBalance }}%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="40"></div>
                                </div>
                            </div>
                            <div class="ml-auto">
                                <h3 class="display-9 mb-0"><i class="mdi mdi-currency-inr"></i>{{ $totalApiBalance }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card p-2 p-lg-3">
                    <div class="p-lg-3 p-2">
                        <div class="d-flex align-items-center">
                            <button class="btn btn-circle btn-success text-white btn-lg" href="javascript:void(0)">
                            <i class="mdi mdi-cash-100"></i>
                        </button>
                            <div class="ml-4" style="width: 38%;">
                                <h4 class="font-light">Total Fund</h4>
                                <div class="progress">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $totalFundWithAdmin ? ($totalFund/$totalFundWithAdmin)*100 : $totalFundWithAdmin }}%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="40"></div>
                                </div>
                            </div>
                            <div class="ml-auto">
                                <h3 class="display-8 mb-0"><i class="mdi mdi-currency-inr"></i>{{ $totalFund }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card p-2  p-lg-3">
                    <div class="p-lg-3 p-2">
                        <div class="d-flex align-items-center">
                            <button class="btn btn-circle btn-cyan text-white btn-lg" href="javascript:void(0)">
                            <i class="mdi mdi-account-multiple"></i>
                        </button>
                            <div class="ml-4" style="width: 38%;">
                                <h4 class="font-light">New Members</h4>
                                <div class="progress">
                                    <div class="progress-bar bg-cyan" role="progressbar" style="width: {{ $totalUser ? ($newMembersCount/$totalUser) * 100 : $totalUser }}%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="40"></div>
                                </div>
                            </div>
                            <div class="ml-auto">
                                <h3 class="display-8 mb-0">{{ $newMembersCount }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Column --> 
                <div class="col-lg-3 col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <h1 class="mt-0"><i class="fa fa-hourglass-half {{ $pendingBalReq ? 'fa-spin' : ''}} text-warning"></i></h1>
                            <h4 class="font-light">Pending Balance Request</h4>
                            <a href="{{ route('balance_request') }}" class="btn  btn-lg card-btn btn-warning" id="pending-bal-req-btn" style="border-radius:50%">{{ $pendingBalReq ? $pendingBalReq : 00 }}</a>                        
                        </div>
                    </div>
                </div>
                <!-- Column -->

                <!-- Column --> 
                <div class="col-lg-3 col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <h1 class="mt-0"><i class="fa fa-hourglass-half {{ $pendingKYCReq ? 'fa-spin' : ''}} text-primary"></i></h1>
                            <h4 class="font-light">Pending KYC Request</h4>
                            <a href="{{ route('user_list') }}" class="btn  btn-lg card-btn btn-primary" style="border-radius:50%">{{ $pendingKYCReq ? $pendingKYCReq : 00 }}</a>                        
                        </div>
                    </div>
                </div>
                <!-- Column -->

                 <!-- Column --> 
                 <div class="col-lg-3 col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <h1 class="mt-0"><i class="fa fa-hourglass-half {{ $pendingComplaints ? 'fa-spin' : ''}} text-danger"></i></h1>
                            <h4 class="font-light">Pending Complaints</h4>
                            <a href="{{ route('complaints',['service_type'=>'COMPLAINT']) }}" class="btn  btn-lg card-btn btn-danger" style="border-radius:50%">{{ $pendingComplaints ? $pendingComplaints : 00 }}</a>                        
                        </div>
                    </div>
                </div>
                <!-- Column -->
            </div>
            <!-- ============================================================== -->
            <!-- Transaction Section  -->
            <!-- ============================================================== -->
            <div class="row">
                <div class="col-md-12 col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title text-uppercase">
                                <span>Transactions Till Date</span>
                                </h5>
                                <ul class="list-style-none country-state mt-4" style="cursor:pointer" onclick="location.href='{{ route('day_book')}}'">
                                    <li class="mb-4">
                                        <h2 class="mb-0">{{ ($transaction['success'] + $transaction['pending'] + $transaction['failed']) }}</h2>
                                        <small>Total Transaction</small>
                                    </li>
                                    <li class="mb-4">
                                        <h2 class="mb-0">{{ $transaction['success'] }}</h2>
                                        <small>Success</small>
                                        <div class="float-right">{{ $transaction['success'] ? (int) (($transaction['success']/($transaction['success'] + $transaction['pending'] + $transaction['failed'])) * 100) : $transaction['success'] }}% <i class="fas fa-level-up-alt text-success"></i></div>
                                        <div class="progress">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $transaction['success'] ? ($transaction['success']/($transaction['success'] + $transaction['failed'])) * 100 : $transaction['success']}}%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </li>
                                    <li class="mb-4">
                                        <h2 class="mb-0">{{ $transaction['pending'] }}</h2>
                                        <small>Pending</small>
                                        <div class="float-right">{{ $transaction['pending'] ? (int) (($transaction['pending']/($transaction['success'] + $transaction['pending'] + $transaction['failed'])) * 100) : $transaction['pending'] }}% <i class="fas fa-level-up-alt text-warning"></i></div>
                                        <div class="progress">
                                            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $transaction['pending'] ? ($transaction['pending']/($transaction['success'] + $transaction['failed'])) * 100 : $transaction['pending'] }}%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </li>
                                    <li class="mb-4">
                                        <h2 class="mb-0">{{ $transaction['failed'] }}</h2>
                                        <small>Failure</small>
                                        <div class="float-right">{{$transaction['failed'] ? (int) (($transaction['failed']/($transaction['success'] + $transaction['pending'] + $transaction['failed'])) * 100) : $transaction['failed'] }}% <i class="fas fa-level-up-alt text-danger"></i></div>
                                        <div class="progress">
                                            <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $transaction['failed'] ? (($transaction['failed']/($transaction['success'] + $transaction['failed'])) * 100) : $transaction['failed'] }}%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    @endif
    <!-- ============================================================== -->
    <!-- End Container fluid Admin ends  -->
    <!-- ============================================================== -->

    <!-- ============================================================== -->
    <!-- Container fluid Distributor starts -->
    <!-- ============================================================== -->
    @if( Auth::user()->roleId == Config::get('constants.DISTRIBUTOR'))
        <div class="page-content container-fluid">
            <!-- ============================================================== -->
            <!-- Card Group  -->
            <!-- ============================================================== -->
            <div class="card-group" style="margin-top:30px">
                <div class="card p-2 p-lg-3">
                    <div class="p-lg-3 p-2">
                        <div class="d-flex align-items-center">
                            <button class="btn btn-circle btn-danger text-white btn-lg" href="javascript:void(0)">
                            <i class="ti-clipboard"></i>
                        </button>
                            <div class="ml-4" style="width: 38%;">
                                <h4 class="font-light">Total Distributors</h4>
                                <div class="progress">
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="40"></div>
                                </div>
                            </div>
                            <div class="ml-auto">
                                <h2 class="display-7 mb-0">00</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- Products yearly sales, Weather Cards Section  -->
            <!-- ============================================================== -->
           
        </div>
    @endif
    <!-- ============================================================== -->
    <!-- End Container fluid Distributor ends  -->
    <!-- ============================================================== -->

    <!-- ============================================================== -->
    <!-- Container fluid Fos starts -->
    <!-- ============================================================== -->
    @if( Auth::user()->roleId == Config::get('constants.FOS'))
        <div class="page-content container-fluid">
            <!-- ============================================================== -->
            <!-- Card Group  -->
            <!-- ============================================================== -->
            <div class="card-group" style="margin-top:30px">
                
                <div class="card p-2 p-lg-3">
                    <div class="p-lg-3 p-2">
                        <div class="d-flex align-items-center">
                            <button class="btn btn-circle btn-cyan text-white btn-lg" href="javascript:void(0)">
                            <i class="ti-wallet"></i>
                        </button>
                            <div class="ml-4" style="width: 38%;">
                                <h4 class="font-light">Total FOSs</h4>
                                <div class="progress">
                                    <div class="progress-bar bg-cyan" role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="40"></div>
                                </div>
                            </div>
                            <div class="ml-auto">
                                <h2 class="display-7 mb-0">00</h2>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            <!-- ============================================================== -->
            <!-- Products yearly sales, Weather Cards Section  -->
            <!-- ============================================================== -->
           
        </div>
    @endif
    <!-- ============================================================== -->
    <!-- End Container fluid Fos ends  -->
    <!-- ============================================================== -->

    <!-- ============================================================== -->
    <!-- Container fluid Retailer starts -->
    <!-- ============================================================== -->
    @if( Auth::user()->roleId == Config::get('constants.RETAILER'))
        <div class="page-content container-fluid">

        <hr>
            <div class="col-12">

            
            <div class="row">
                    <div class="container-fluid">
                        <section class="logo-carousel slider">
                            <div class="slide"><img src="{{ asset('template_new/img/01.jpg') }}"></div>
                            <div class="slide"><img src="{{ asset('template_new/img/02.jpg') }}"></div>
                            <div class="slide"><img src="{{ asset('template_new/img/03.jpg') }}"></div>
                            <div class="slide"><img src="{{ asset('template_new/img/04.jpg') }}"></div>
                            <div class="slide"><img src="{{ asset('template_new/img/05.jpg') }}"></div>
                            <div class="slide"><img src="{{ asset('template_new/img/06.jpg') }}"></div>
                            <div class="slide"><img src="{{ asset('template_new/img/07.jpg') }}"></div>
                        </section>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- Card Group  -->
            <!-- ============================================================== -->
            <div class="card-group" style="margin-top:30px">
                
                <div class="card p-2 p-lg-3">
                    <div class="p-lg-3 p-2">
                        <div class="d-flex align-items-center">
                            <button class="btn btn-circle btn-warning text-white btn-lg" href="javascript:void(0)">
                            <i class="fas fa-dollar-sign"></i>
                        </button>
                            <div class="ml-4" style="width: 38%;">
                                <h4 class="font-light">Total Retailers</h4>
                                <div class="progress">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="40"></div>
                                </div>
                            </div>
                            <div class="ml-auto">
                                <h2 class="display-7 mb-0">00</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-group">
                <!-- Recharge & Bill payments starts -->
                <div class="card">
                    <div class="row m-3">
                        <div class="col-12 mb-3">
                            <div class="card-title">Recharge & Bill Payments</div>
                        </div>
                        @foreach($serviceList as $i => $service)
                            <div class="col-2 text-center">
                                <a class="btn btn-circle btn-light text-white btn-lg mb-1" href="{{ route($service['route'],['type' => $service['key']]) }}">
                                    <i class="{{ $service['icon'] }}" style="color:#3c1c5d"></i>
                                </a>
                                <div><span>{{ $service['name'] }}</span></div>
                            </div>
                        @endforeach   
                        <div class="col-12 pb-3"></div>                   
                    </div>
                </div>
                <!-- Recharge & Bill payments ends -->
            </div>
            <!-- ============================================================== -->
            <!-- Products yearly sales, Weather Cards Section  -->
            <!-- ============================================================== -->
        </div>
    @endif
    <!-- ============================================================== -->
    <!-- End Container fluid Retailer ends  -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- footer -->
    <!-- ============================================================== -->
    <!-- <footer class="footer text-center">
        Copyright @ SMARTPAY - Making India Digital.
    </footer> -->
    <!-- ============================================================== -->
    <!-- End footer -->
    <!-- ============================================================== -->

@if( Auth::user()->roleId != Config::get('constants.DISTRIBUTOR'))
    </section>
@endif


<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script> 


@endif


@endsection