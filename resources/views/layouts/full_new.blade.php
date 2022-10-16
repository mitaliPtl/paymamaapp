<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('public/template_assets/PayMama Icon.png') }}">
    <title>PayMama - Business Made Easy</title>
    <link rel="canonical" href="https://www.wrappixel.com/templates/ampleadmin/" />

    @if( ( Auth::user()->roleId == Config::get('constants.RETAILER')) || ( Auth::user()->roleId == Config::get('constants.DISTRIBUTOR')) || ( Auth::user()->roleId == Config::get('constants.MASTER_DISTRIBUTOR')))

    <!-- chartist CSS -->
    <link href="{{ asset('template_new/assets/libs/chartist/dist/chartist.min.css') }}" rel="stylesheet">
    <link href="{{ asset('template_new/assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.css') }}" rel="stylesheet">
    <!--c3 CSS -->
    <link href="{{ asset('template_new/assets/libs/morris.js/morris.css') }}" rel="stylesheet">
    <link href="{{ asset('template_new/assets/extra-libs/c3/c3.min.css') }}" rel="stylesheet">

    <!-- needed css -->
    <link href="{{ asset('template_new/dist/css/style.min.css') }}" rel="stylesheet">
    <link href="{{ asset('template_new/dist/css/infinite-slider.css') }}" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->


    <!-- <link rel="stylesheet" type="text/css"
        href="{{ asset('template_new/assets/extra-libs/datatables.net-bs4/css/responsive.dataTables.min.css') }}"> -->
    <!-- Custom CSS -->




    <script src="{{ asset('template_new/dist/js/jquery-2.2.0.min.js') }}" type="text/javascript"></script>
    <script src="//cdn.jsdelivr.net/npm/jquery.marquee@1.6.0/jquery.marquee.min.js" type="text/javascript"></script>
    <script src="{{ asset('template_new/dist/js/slick.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.customer-logos').slick({
                slidesToShow: 6,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 2000,
                arrows: false,
                dots: false,
                pauseOnHover: false,
                responsive: [{
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 4
                    }
                }, {
                    breakpoint: 520,
                    settings: {
                        slidesToShow: 3
                    }
                }]
            });
        });
    </script>

    @else
    <!-- chartist CSS -->
    <link href="{{ asset('template_assets/assets/libs/chartist/dist/chartist.min.css') }}" rel="stylesheet">
    <link href="{{ asset('template_assets/assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.css') }}" rel="stylesheet">
    <!-- The link has been commented here and added to the individual page -->

    <!--c3 CSS -->
    <!-- <link href="../../assets/libs/morris.js/morris.css" rel="stylesheet"> -->
    <!-- <link href="{{ asset('template_assets/assets/libs/morris.js/morris.css') }}" rel="stylesheet"> -->
    <!-- <link href="../../assets/extra-libs/c3/c3.min.css" rel="stylesheet"> -->
    <link href="{{ asset('template_assets/assets/extra-libs/c3/c3.min.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
    <!-- <link href="../../assets/libs/fullcalendar/dist/fullcalendar.min.css" rel="stylesheet" /> -->
    <link href="{{ asset('template_assets/assets/libs/fullcalendar/dist/fullcalendar.min.css') }}" rel="stylesheet" />
    <!-- <link href="../../assets/extra-libs/calendar/calendar.css" rel="stylesheet" /> -->
    <link href="{{ asset('template_assets/assets/extra-libs/calendar/calendar.css') }}" rel="stylesheet" />
    <!-- needed css -->
    <!-- <link href="../../dist/css/style.min.css" rel="stylesheet"> -->
    <link href="{{ asset('template_assets/dist/css/style.min.css') }}" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
    <link rel="stylesheet" type="text/css" href="{{ asset('template_assets\other\css\toastr.min.css') }}">

    <!-- Custom Style -->
    <link rel="stylesheet" type="text/css" href="{{ asset('dist\shared\css\full.css') }}">
    @endif
</head>

<body>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="lds-ripple" style="top: calc(37% - 3.5px); width: 150px; height: 150px;">
            <img src="{{ asset('template_assets/PAYMAMAGIF.gif') }}" alt="" style="width:85px;">
            <!-- <div class="lds-pos"></div>
            <div class="lds-pos"></div> -->
        </div>
    </div>

    <div class="preloader_new" style="display:none;">
        <div class="lds-ripple" style="top: calc(37% - 3.5px); width: 150px; height: 150px;">
            <img src="{{ asset('template_assets/PAYMAMAGIF.gif') }}" alt="">
            <!-- <div class="lds-pos"></div>
            <div class="lds-pos"></div> -->
        </div>
    </div>

    <div class="preloader_blur" style="display:none;">
        <div class="lds-ripple" style="top: calc(37% - 3.5px); width: 150px; height: 150px;">
            <img src="{{ asset('template_new/img/paymama_gif.gif') }}" alt="" style="width:85px;">
            <!-- <div class="lds-pos"></div>
            <div class="lds-pos"></div> -->
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->

    @if( ( Auth::user()->roleId == Config::get('constants.RETAILER')) || ( Auth::user()->roleId == Config::get('constants.DISTRIBUTOR')) || ( Auth::user()->roleId == Config::get('constants.MASTER_DISTRIBUTOR')) )

    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <input type="hidden" id="loggedUserId" value="{{ Auth::id() }}">
        <input type="hidden" id="loggedUserMobileNo" value="{{ Auth::user()->mobile }}">
        <input type="hidden" id="loggedUserId" value="{{ Auth::user()->userId }}">
        <input type="hidden" id="loggedRoleId" value="{{ Auth::user()->roleId }}">
        <input type="hidden" id="loggedSessionToken" value="{{ App\UserLoginSessionDetail::getUserApikey(Auth::user()->userId) }}">
        <input type="hidden" id="roleAlias" value="{{ json_encode(Config::get('constants.ROLE_ALIAS')) }}">
        @if($message = Session::get('success'))
        <input type="hidden" id="session_success_msg" value="{{ $message }}">
        @elseif($message = Session::get('error'))
        <input type="hidden" id="session_error_msg" value="{{ $message }}">
        @endif
        <header class="topbar">
            <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                <div class="navbar-header border-right">
                    <!-- This is for the sidebar toggle which is visible on mobile only -->
                    <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i class="ti-menu ti-close"></i></a>
                    <a class="navbar-brand" href="{{  Auth::user()->roleId == Config::get('constants.ADMIN') ? url('/admin-home') : url('/home') }}">
                        <!-- Logo icon -->
                        <b class="logo-icon" style="padding:0px;">
                            <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                            <!-- Dark Logo icon -->
                            <img src="{{ asset('template_assets/paymamma.PNG') }}" alt="homepage" class="dark-logo" style="width: 214px; 
    height: 71px;
    margin-left: 25px;" />
                            <!-- Light Logo icon -->
                            <img src="{{ asset('template_assets/paymamma.PNG') }}" alt="homepage" class="light-logo" style="width: 214px;
    height: 71px;
    margin-left: 25px;">
                        </b>
                        <!--End Logo icon -->

                    </a>
                    <!-- ============================================================== -->
                    <!-- End Logo -->
                    <!-- ============================================================== -->
                    <!-- ============================================================== -->
                    <!-- Toggle which is visible on mobile only -->
                    <!-- ============================================================== -->
                    <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i class="ti-more"></i></a>
                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <div class="navbar-collapse collapse" id="navbarSupportedContent">
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav mr-auto toggle">
                        <li class="nav-item d-none d-md-block"><a class="nav-link sidebartoggler waves-effect waves-light" href="javascript:void(0)" data-sidebartype="mini-sidebar">
                                <i class="mdi mdi-menu font-18"></i></a></li>
                    </ul>

                    <ul class="navbar-nav">
                        
                        @if( ( Auth::user()->roleId == Config::get('constants.RETAILER')) )
                        <li class="nav-item header-img " style="display:block;">
                            <a class="nav-link waves-effect waves-dark" href="{{ route('online_payment') }}">

                                <img src="{{ asset('template_new/img/ic_add_money.png') }}" class="header-img"><span class="ml-2 font-medium">ADD MONEY</span>
                            </a>
                        </li>
                        @endif
                        <!-- 
                        <li class="nav-item header-img " style="display:block;">
                            <a class="nav-link waves-effect waves-dark" href="{{ route('online_payment') }}">

                                <img src="{{ asset('template_new/img/ic_add_money.png') }}" class="header-img"><span class="ml-2 font-medium">ADD MONEY</span>
                            </a>
                        </li> -->
                        
                        
                        <li class="nav-item header-img" id="balance_request_qrcode">
                            <a class="nav-link waves-effect waves-dark" href="javascript:void(0)">
                                <img src="{{ asset('template_new/img/barcode.png') }}"><span class="ml-1 font-medium">QR Code</span>
                            </a>
                        </li>

                        <li class="nav-item header-img" style="width: max-content;">
                            <a class="nav-link waves-effect waves-dark" href="#">
                                <img src="{{ asset('template_new/img/wallet_new.png') }}"><span class="ml-2 font-medium">{{ Auth::user()->wallet_balance ? sprintf('%.2f', Auth::user()->wallet_balance) : 0}}</span>
                            </a>
                        </li>
                       
                        @if( ( Auth::user()->roleId == Config::get('constants.RETAILER')))
                        <li class="nav-item header-img" style="width: max-content;">
                            <a class="nav-link waves-effect waves-dark" href="javascript:void(0)"  rel="PG Wallet" id="pg_wallet">
                                <img src="{{ asset('template_new/img/pg_wallet_new.png') }}"><span class="ml-2 font-medium">{{ Auth::user()->pg_wallet_balance ? sprintf('%.2f', Auth::user()->pg_wallet_balance) : 0}}</span>
                            </a>
                        </li>
                        @endif


                        <!-- ============================================================== -->
                        <!-- Messages -->
                        <!-- ============================================================== -->
                        <li class="nav-item header-img dropdown">
                            <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" id="2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <img src="{{ asset('template_new/img/notification_new.png') }}">
                                <div class="notify">
                                    <span class="heartbit"></span>
                                    <span class="point"></span>
                                </div>
                            </a>
                            @php
                            $count_notification = Session::get('user_notification_count');
                            $notification_list = json_decode(Session::get('user_notification'), true);
                            @endphp
                            <div class="dropdown-menu dropdown-menu-left mailbox animated bounceInDown" aria-labelledby="2">
                                <ul class="list-style-none">
                                    <li>
                                        <div class="drop-title border-bottom">
                                            @if($count_notification > 0)
                                            You have {{ $count_notification }} new messanges
                                            @endif
                                        </div>
                                    </li>
                                    <li>
                                        <div class="message-center message-body">
                                            @foreach($notification_list as $notify)
                                            <!-- Message -->
                                            <a href="javascript:void(0)" class="message-item">
                                                <!-- <span class="user-img"> <img src="../../assets/images/users/1.jpg" alt="user" class="rounded-circle"> <span class="profile-status online pull-right"></span> </span> -->
                                                <span class="mail-contnet">
                                                    <h5 class="message-title">{{ $notify['title'] }}</h5> <span class="mail-desc">{{ substr($notify['body'],0,30) }}...</span> <span class="time">{{ $notify['sended_on'] }}</span>
                                                </span>
                                            </a>
                                            @endforeach
                                            <!-- Message -->
                                            <!-- <a href="javascript:void(0)" class="message-item">
                                                <span class="user-img"> <img src="../../assets/images/users/2.jpg" alt="user" class="rounded-circle"> <span class="profile-status busy pull-right"></span> </span>
                                                <span class="mail-contnet">
                                                    <h5 class="message-title">Sonu Nigam</h5> <span class="mail-desc">I've sung a song! See you at</span> <span class="time">9:10 AM</span> </span>
                                            </a> -->
                                            <!-- Message -->
                                            <!-- <a href="javascript:void(0)" class="message-item">
                                                <span class="user-img"> <img src="../../assets/images/users/3.jpg" alt="user" class="rounded-circle"> <span class="profile-status away pull-right"></span> </span>
                                                <span class="mail-contnet">
                                                    <h5 class="message-title">Arijit Sinh</h5> <span class="mail-desc">I am a singer!</span> <span class="time">9:08 AM</span> </span>
                                            </a> -->
                                            <!-- Message -->
                                            <!-- <a href="javascript:void(0)" class="message-item">
                                                <span class="user-img"> <img src="../../assets/images/users/4.jpg" alt="user" class="rounded-circle"> <span class="profile-status offline pull-right"></span> </span>
                                                <span class="mail-contnet">
                                                    <h5 class="message-title">Pavan kumar</h5> <span class="mail-desc">Just see the my admin!</span> <span class="time">9:02 AM</span> </span>
                                            </a> -->
                                        </div>
                                    </li>
                                    <li>
                                        <a class="nav-link text-center link text-dark" href="{{ route('notifications') }}"> <b>See all Notifications</b> <i class="fa fa-angle-right"></i> </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY">

                            <a class="dropdown-item" href="javascript:void(0)"><i class="ti-user mr-1 ml-1"></i> My Profile</a>
                            <a class="dropdown-item" href="javascript:void(0)"><i class="ti-wallet mr-1 ml-1"></i> My Balance</a>
                            <a class="dropdown-item" href="javascript:void(0)"><i class="ti-email mr-1 ml-1"></i> Inbox</a>

                        </div>
                       



                        <!-- ============================================================== -->
                        <!-- User profile and search -->
                        <!-- ============================================================== -->
                        @include('modules.other.user_profile_new')
                        <!-- ============================================================== -->
                        <!-- User profile and search -->
                        <!-- ============================================================== -->
                    </ul>
                </div>
            </nav>
        </header>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        @if( Auth::user()->roleId == Config::get('constants.RETAILER'))
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <style>
            .hide-menu {
                font-weight: normal;
            }
        </style>
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">

                        <li class="sidebar-item active">
                            <a class="sidebar-link " href="{{ route('home') }}" aria-expanded="false">
                                <img src="{{ asset('template_new/img/sidebar/ic_dashborad.png') }}">
                                <span class="hide-menu" style="">Dashboard {{ (Session::has('payment_status') )? Session::get('payment_status') : '' }} </span></a>

                        </li>



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
                        @endphp

                        @if($array[0]['status']==1)
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('money_transfer') }}" aria-expanded="false">
                                <img src="{{ asset('template_new/img/sidebar/ic_money_transfer.png') }}">
                                <span class="hide-menu" style="">Money Transfer</span>
                            </a>
                        </li>
                        @else
                        <li class="sidebar-item">
                            <span class="hide-menu" style="">
                                <a class="sidebar-link" aria-expanded="false" data-toggle="modal" data-target="#myModal">
                                    <img src="{{ asset('template_new/img/sidebar/ic_money_transfer.png') }}">
                                    Money Transfer
                                </a>
                            </span>
                        </li>
                        @endif

                        <div class="modal fade" id="myModal" role="dialog" style="margin-top:10%; position: absolute !important;z-index: 1060 !important;">
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

                        @if($aepsarray[0]['status']==1)
                        <li class="sidebar-item">
                            <a class="sidebar-link" target="_blank" href="{{ route('aeps') }}" aria-expanded="false">
                                <img src="{{ asset('template_assets/AEPS-ICON.png') }}">
                                <span class="hide-menu" style="">AEPS</span>
                            </a>
                        </li>
                        @else


                        <li class="sidebar-item">

                            <span class="hide-menu" style="">

                                <a class="sidebar-link" aria-expanded="false" data-toggle="modal" data-target="#myModal">
                                    <img src="{{ asset('template_assets/AEPS-ICON.png') }}">
                                    AEPS

                                </a>
                            </span>
                        </li>
                        @endif
                        @if($aepsarray[0]['status']==1)
                        <li class="sidebar-item">
                            <a target="_blank" class="sidebar-link" href="{{ route('aadharpay') }}" aria-expanded="false">
                                <img src="{{ asset('template_assets/AADHARPAY-ICON.png') }}">
                                <span class="hide-menu" style="">Aadhar Pay</span>
                            </a>
                        </li>
                        @else


                        <li class="sidebar-item">

                            <span class="hide-menu" style="">

                                <a class="sidebar-link" aria-expanded="false" data-toggle="modal" data-target="#myModal">
                                    <img src="{{ asset('template_assets/AADHARPAY-ICON.png') }}">
                                    Aadhar Pay

                                </a>
                            </span>
                        </li>
                        @endif

                        @if($iciciarray[0]['status']==1)
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('icicionboarding') }}" aria-expanded="false">
                                <img src="{{ asset('template_assets/ICICI-ICON.png') }}">
                                <span class="hide-menu" style="">ICICI Cash Deposit</span>
                            </a>
                        </li>
                        @else


                        <li class="sidebar-item">

                            <span class="hide-menu" style="">

                                <a class="sidebar-link" aria-expanded="false" data-toggle="modal" data-target="#myModal">
                                    <img src="{{ asset('template_assets/ICICI-ICON.png') }}">ICICI Cash Deposit
                                </a>
                            </span>
                        </li>
                        @endif

                        @if($iciciarray[0]['status']==1)
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('card_bank') }}" aria-expanded="false">
                                <img src="{{ asset('template_new/img/sidebar/ic_money_transfer.png') }}">
                                <span class="hide-menu" style="">Card To Bank</span>
                            </a>
                        </li>
                        @else
                        <li class="sidebar-item">
                            <span class="hide-menu" style="">
                                <a class="sidebar-link" aria-expanded="false" data-toggle="modal" data-target="#myModal">
                                    <img src="{{ asset('template_new/img/sidebar/ic_money_transfer.png') }}">CARD TO BANK
                                </a>
                            </span>
                        </li>
                        @endif

                        
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <img src="{{ asset('template_new/img/sidebar/commissionreport_ic.png') }}">
                                <span class="hide-menu" style="">Payment Gateway</span>
                                <!-- <span class="badge badge-info badge-pill ml-auto mr-3 font-medium px-2 py-1">3</span> -->
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item">
                                    <a href="{{ route('pg-wallet-passbook') }}" class="sidebar-link">
                                        <i class="mdi mdi-toggle-switch"></i>
                                        <span class="hide-menu" style=""> Payment Gateway Passbook</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('pg-wallet-report') }}" class="sidebar-link">
                                        <i class="mdi mdi-tablet"></i>
                                        <span class="hide-menu" style=""> Payment Gateway Reports</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="" class="sidebar-link">
                                        <i class="mdi mdi-tablet"></i>
                                        <span class="hide-menu" style=""> Payment Gateway Payouts</span>
                                    </a>
                                </li>
                              </ul>
                        </li>


                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <img src="{{ asset('template_new/img/sidebar/txn_report_ic.png') }}">
                                <span class="hide-menu" style="">Transaction Reports </span>
                                <!-- <span class="badge badge-info badge-pill ml-auto mr-3 font-medium px-2 py-1">4</span> -->
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item">
                                    <a href="{{ route('transaction_report',['service_type'=>'RECHARGE']) }}" class="sidebar-link">
                                        <i class="mdi mdi-toggle-switch"></i>
                                        <span class="hide-menu" style=""> Recharge Reports</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('transaction_report',['service_type'=>'BILL_PAYMENTS']) }}" class="sidebar-link">
                                        <i class="mdi mdi-tablet"></i>
                                        <span class="hide-menu" style=""> Bill Payment Report</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('transaction_report',['service_type'=>'MONEY_TRANSFER']) }}" class="sidebar-link">
                                        <i class="mdi mdi-sort-variant"></i>
                                        <span class="hide-menu" style=""> Money Transfer Report</span>
                                    </a>
                                </li>

                                <li class="sidebar-item">
                                    <a href="{{ route('transaction_report',['service_type'=>'UPI_TRANSFER']) }}" class="sidebar-link" aria-expanded="false">
                                        <i class="mdi mdi-message-bulleted-off"></i>
                                        <span class="hide-menu" style="">BHIM UPI Report</span>
                                    </a>
                                </li>

                                <li class="sidebar-item">
                                    <a href="{{ route('transaction_report',['service_type'=>'AEPS']) }}" class="sidebar-link">
                                        <i class="mdi mdi-sort-variant"></i>
                                        <span class="hide-menu" style=""> AEPS Report</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('transaction_report',['service_type'=>'Mini_Statement']) }}" class="sidebar-link">
                                        <i class="mdi mdi-sort-variant"></i>
                                        <span class="hide-menu" style=""> Ministatement Report</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('transaction_report',['service_type'=>'AADHAR_PAY']) }}" class="sidebar-link">
                                        <i class="mdi mdi-sort-variant"></i>
                                        <span class="hide-menu" style=""> AADHAR PAY Report</span>
                                    </a>
                                </li>
                                <!-- <li class="sidebar-item">
                                    <a href="{{ route('transaction_report',['service_type'=>'ICICI_CASH_DEPOSIT']) }}" class="sidebar-link">
                                        <i class="mdi mdi-sort-variant"></i>
                                        <span class="hide-menu" style=""> ICICI CASH DEPOSIT Report</span>
                                    </a>
                                </li> -->

                                <li class="sidebar-item">
                                    <a class="sidebar-link" href="{{ route('passbook') }}">
                                        <i class="mdi mdi-sort-variant"></i>
                                        <span class="hide-menu" style="">Passbook / Account Report</span></a>
                                </li>

                                <li class="sidebar-item">
                                    <a href="{{ route('user_qr_code_account_report') }}" class="sidebar-link">
                                        <i class="mdi mdi-sort-variant"></i>
                                        <span class="hide-menu" style="">QR Code Report</span>
                                    </a>
                                </li>

                                <li class="sidebar-item">
                                    <a href="{{ route('user_virtual_account_report') }}" class="sidebar-link">
                                        <i class="mdi mdi-sort-variant"></i>
                                        <span class="hide-menu" style="">Virtual Account Report</span>
                                    </a>
                                </li>

                                <li class="sidebar-item">
                                    <a href="{{ route('user_payment_gateway_report') }}" class="sidebar-link">
                                        <i class="mdi mdi-sort-variant"></i>
                                        <span class="hide-menu" style="">Payment Gateway Report</span>
                                    </a>
                                </li>

                            </ul>
                        </li>
                    
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <img src="{{ asset('template_new/img/sidebar/commissionreport_ic.png') }}">
                                <span class="hide-menu" style="">Commission Reports </span>
                                <!-- <span class="badge badge-info badge-pill ml-auto mr-3 font-medium px-2 py-1">3</span> -->
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item">
                                    <a href="{{ route('commission_report',['service_type'=>'RECHARGE']) }}" class="sidebar-link">
                                        <i class="mdi mdi-toggle-switch"></i>
                                        <span class="hide-menu" style=""> Recharge Reports</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('commission_report',['service_type'=>'BILL_PAYMENTS']) }}" class="sidebar-link">
                                        <i class="mdi mdi-tablet"></i>
                                        <span class="hide-menu" style=""> Bill Payment Report</span>
                                    </a>
                                </li>

                                <!--<li class="sidebar-item">-->
                                <!--    <a href="{{ route('commission_report',['service_type'=>'AEPS']) }}" class="sidebar-link">-->
                                <!--        <i class="mdi mdi-sort-variant"></i>-->
                                <!--        <span class="hide-menu" style=""> AEPS Report</span>-->
                                <!--    </a>-->
                                <!--</li>-->


                            </ul>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('complaints',['service_type'=>'COMPLAINT']) }}" aria-expanded="false">
                                <img src="{{ asset('template_new/img/sidebar/complaint_box_ic.png') }}">
                                <span class="hide-menu" style="">Complaint Box</span></a>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <img src="{{ asset('template_new/img/sidebar/offers_notices_ic.png') }}">
                                <span class="hide-menu" style="">Offers & Notices</span>
                                <!-- <span class="badge badge-info badge-pill ml-auto mr-3 font-medium px-2 py-1">3</span> -->
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item">
                                    <a href="{{ route('offers-notice-dtrt', ['type'=>'OFFER']) }}" class="sidebar-link">
                                        <i class="mdi mdi-toggle-switch"></i>
                                        <span class="hide-menu" style=""> Offers</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('offers-notice-dtrt', ['type'=>'NOTICE']) }}" class="sidebar-link">
                                        <i class="mdi mdi-tablet"></i>
                                        <span class="hide-menu" style="">Notices</span>
                                    </a>
                                </li>

                            </ul>
                        </li>

                        <style>
                            .hide-menu {
                                color: black !important;
                            }
                        </style>

                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <img src="{{ asset('template_new/img/sidebar/add_money_ic.png') }}">
                                <span class="hide-menu" style="">Payment Request</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item">
                                    <a class="sidebar-link" href="{{ route('bank_account') }}" aria-expanded="false">
                                        <i class="mdi mdi-sort-variant"></i>
                                        <span class="hide-menu" style="">Bank Details</span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a class="sidebar-link" href="{{ route('balance_request') }}" aria-expanded="false">
                                        <i class="mdi mdi-sort-variant"></i>
                                        <span class="hide-menu" style="">Payment Request</span></a>
                                </li>
                            </ul>
                        </li>
                        <li class="sidebar-item">




                            <a class="sidebar-link" href="{{ route('aepsDeviceDriver') }}" aria-expanded="false">
                                <img src="{{ asset('template_assets/AEPS-ICON.png') }}">

                                <span class="hide-menu" style="">AEPS Device Driver</span>

                            </a>

                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('operator_helpline') }}" aria-expanded="false">
                                <img src="{{ asset('template_new/img/sidebar/supportblue_ic.png') }}">
                                <span class="hide-menu" style="">Support</span></a>
                        </li>




                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('logout') }}" aria-expanded="false">
                                <img src="{{ asset('template_new/img/sidebar/logout_ic.png') }}">
                                <span class="hide-menu" style="">Logout</span></a>
                        </li>

                        <li class="sidebar-item">
                            <span class="hide-menu sidebar-link">&nbsp;&nbsp;&nbsp;<strong>Current IP :- {{ Auth::user()->last_login_ip }}</strong></span>
                        </li>

                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        @elseif( Auth::user()->roleId == Config::get('constants.DISTRIBUTOR') || Auth::user()->roleId == Config::get('constants.MASTER_DISTRIBUTOR') )
        <!-- ============================================================== -->
        <!-- Distributor Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <style>
            .hide-menu {
                font-weight: normal;
            }
        </style>
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">

                        <li class="sidebar-item">
                        <span class="hide-menu" >
                            <a class="sidebar-link waves-effect waves-dark" href="{{ route('home') }}" aria-expanded="false">
                                <img src="{{ asset('template_new/img/sidebar/ic_dashborad.png') }}">
                                <span class="hide-menu" style="">Dashboard</span>
                            </a>
                            </span>
                        </li>
                       
                        <li class="sidebar-item">
                        <span class="hide-menu" >
                            <a class="sidebar-link waves-effect waves-dark" href="{{ route('home') }}" aria-expanded="false">
                                <img src="{{ asset('template_new/img/sidebar/ic_dashborad.png') }}">
                                <span class="hide-menu" style="">Graph View</span>
                            </a>
                            </span>
                        </li>
                        
                        @if( Auth::user()->roleId == Config::get('constants.DISTRIBUTOR'))
                        <li class="sidebar-item">
                        
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <img src="{{ asset('template_new/img/sidebar/txn_report_ic.png') }}">
                                <span class="hide-menu" style="">Retailer Management</span>
                                <!-- <span class="badge badge-info badge-pill ml-auto mr-3 font-medium px-2 py-1">4</span> -->
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">

                                <li class="sidebar-item">
                                    <a href="/create_new_retailer" class="sidebar-link">
                                        <i class="mdi mdi-toggle-switch"></i>
                                        <span class="hide-menu" style=""> Add New Retailer</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="/home" class="sidebar-link">
                                        <i class="mdi mdi-tablet"></i>
                                        <span class="hide-menu" style="">Retailer List</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="/credit_report?report=RETAILER" class="sidebar-link">
                                        <i class="mdi mdi-sort-variant"></i>
                                        <span class="hide-menu" style="">Retailer Credit Report</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="/member_passbook_pm" class="sidebar-link">
                                        <i class="mdi mdi-sort-variant"></i>
                                        <span class="hide-menu" style="">Retailer Account Report</span>
                                    </a>
                                </li>

                            </ul>
                            
                        </li>
                        @endif
                        
                        @if( Auth::user()->roleId == Config::get('constants.MASTER_DISTRIBUTOR'))
                        
                        <li class="sidebar-item">
                        
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <img src="{{ asset('template_new/img/sidebar/txn_report_ic.png') }}">
                                <span class="hide-menu" style="">Distributor Management</span>
                                <!-- <span class="badge badge-info badge-pill ml-auto mr-3 font-medium px-2 py-1">4</span> -->
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">

                                <li class="sidebar-item">
                                    <a href="/create_new_distributor" class="sidebar-link">
                                        <i class="mdi mdi-toggle-switch"></i>
                                        <span class="hide-menu" style="">Add New Distributor</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="/home" class="sidebar-link">
                                        <i class="mdi mdi-tablet"></i>
                                        <span class="hide-menu" style="">Distributor List</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="/credit_report?report=DISTRIBUTOR" class="sidebar-link">
                                        <i class="mdi mdi-sort-variant"></i>
                                        <span class="hide-menu" style="">Distributor Credit Report</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="/member_passbook_pm" class="sidebar-link">
                                        <i class="mdi mdi-sort-variant"></i>
                                        <span class="hide-menu" style="">Distributor Account Report</span>
                                    </a>
                                </li>
                                
                            </ul>
                        </li>
                        @endif
                        
                        @if( Auth::user()->roleId == Config::get('constants.DISTRIBUTOR'))
                        <li class="sidebar-item">
                        
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <img src="{{ asset('template_new/img/sidebar/txn_report_ic.png') }}">
                                <span class="hide-menu" style="">Fos Mangement</span>
                                <!-- <span class="badge badge-info badge-pill ml-auto mr-3 font-medium px-2 py-1">4</span> -->
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">

                                <li class="sidebar-item">
                                    <a href="/create_new_fos" class="sidebar-link">
                                        <i class="mdi mdi-toggle-switch"></i>
                                        <span class="hide-menu" style=""> Add new FOS</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="/distributor_fos_list" class="sidebar-link">
                                        <i class="mdi mdi-tablet"></i>
                                        <span class="hide-menu" style="">FOS List</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="/credit_report?report=FOS" class="sidebar-link">
                                        <i class="mdi mdi-sort-variant"></i>
                                        <span class="hide-menu" style="">FOS Credit Report</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('transaction_report',['service_type'=>'UPI_TRANSFER']) }}" class="sidebar-link">
                                        <i class="mdi mdi-sort-variant"></i>
                                        <span class="hide-menu" style="">FOS Account Report</span>
                                    </a>
                                </li>

                            </ul>
                            
                        </li>
                        @endif
                        
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                            <img src="{{ asset('template_new/img/sidebar/ic_dashborad.png') }}">
                                <span class="hide-menu" style="">Wallet Transfers </span>
                                <!-- <span class="badge badge-info badge-pill ml-auto mr-3 font-medium px-2 py-1">4</span> -->
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">

                                 <li class="sidebar-item">
                                    <a href="{{ route('transfer_revert_balance') }}" class="sidebar-link">
                                        <i class="mdi mdi-sort-variant"></i>
                                        <span class="hide-menu" style="">Wallet Transfer</span>
                                    </a>
                                </li>

                        
                                <li class="sidebar-item">
                                    <a href="/all_transfer" class="sidebar-link">
                                        <i class="mdi mdi-sort-variant"></i>
                                        <span class="hide-menu" style="">Wallet Transfer Report</span>
                                    </a>
                                </li>

                            </ul>
                        </li>
                        
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <img src="{{ asset('template_new/img/sidebar/txn_report_ic.png') }}">
                                <span class="hide-menu" style="">Reports </span>
                                <!-- <span class="badge badge-info badge-pill ml-auto mr-3 font-medium px-2 py-1">4</span> -->
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">

                                <li class="sidebar-item">
                                    <a href="{{ route('transaction_report',['service_type'=>'RECHARGE']) }}" class="sidebar-link">
                                        <i class="mdi mdi-toggle-switch"></i>
                                        <span class="hide-menu" style=""> Recharge Report</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('transaction_report',['service_type'=>'BILL_PAYMENTS']) }}" class="sidebar-link">
                                        <i class="mdi mdi-tablet"></i>
                                        <span class="hide-menu" style="">Bill Payment</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('transaction_report',['service_type'=>'MONEY_TRANSFER']) }}" class="sidebar-link">
                                        <i class="mdi mdi-sort-variant"></i>
                                        <span class="hide-menu" style="">Money Transfer Report</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('transaction_report',['service_type'=>'UPI_TRANSFER']) }}" class="sidebar-link">
                                        <i class="mdi mdi-sort-variant"></i>
                                        <span class="hide-menu" style="">Bhim UPI Report</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('transaction_report',['service_type'=>'AEPS']) }}" class="sidebar-link">
                                        <i class="mdi mdi-image-filter-vintage"></i>
                                        <span class="hide-menu" style="">AEPS Cash Withdrawal Report</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('transaction_report',['service_type'=>'Mini_Statement']) }}" class="sidebar-link" aria-expanded="false">
                                        <i class="mdi mdi-message-bulleted-off"></i>
                                        <span class="hide-menu" style="">Mini Statement Report</span>
                                    </a>
                                </li>


                                <li class="sidebar-item">
                                    <a href="{{ route('transaction_report',['service_type'=>'AADHAR_PAY']) }}" class="sidebar-link" aria-expanded="false">
                                        <i class="mdi mdi-message-bulleted-off"></i>
                                        <span class="hide-menu" style="">Aadhar Pay REPORT</span>
                                    </a>
                                </li>
                              <!--  <li class="sidebar-item">
                                    <a href="{{ route('transaction_report',['service_type'=>'ICICI_CASH_DEPOSIT']) }}" class="sidebar-link" aria-expanded="false">
                                        <i class="mdi mdi-message-bulleted-off"></i>
                                        <span class="hide-menu" style="">ICICI Deposit REPORT</span>
                                    </a>
                                </li> -->
                                <li class="sidebar-item">
                                    <a href="/user_payment_gateway_report" class="sidebar-link" aria-expanded="false">
                                        <i class="mdi mdi-message-bulleted-off"></i>
                                        <span class="hide-menu" style="">Payment Gateway Report</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="/user_qr_code_report" class="sidebar-link" aria-expanded="false">
                                        <i class="mdi mdi-message-bulleted-off"></i>
                                        <span class="hide-menu" style="">QR Code Report</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="/user_virtual_account_report" class="sidebar-link" aria-expanded="false">
                                        <i class="mdi mdi-message-bulleted-off"></i>
                                        <span class="hide-menu" style="">Virtual Account Report</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="/passbook" class="sidebar-link" aria-expanded="false">
                                        <i class="mdi mdi-message-bulleted-off"></i>
                                        <span class="hide-menu" style="">Passbook/Account Report</span>
                                    </a>
                                </li>

                            </ul>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                            <img src="{{ asset('template_new/img/sidebar/add_money_ic.png') }}">
                                <span class="hide-menu" style="">Payment Request </span>
                                <!-- <span class="badge badge-info badge-pill ml-auto mr-3 font-medium px-2 py-1">4</span> -->
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">

                                <li class="sidebar-item">
                                    <a href="/balance_request" class="sidebar-link">
                                        <i class="mdi mdi-toggle-switch"></i>
                                        <span class="hide-menu" style="">Payment Request</span>
                                    </a>
                                </li>
                        
                                <li class="sidebar-item">
                                    <a href="/bank_account" class="sidebar-link">
                                        <i class="mdi mdi-sort-variant"></i>
                                        <span class="hide-menu" style="">Bank Details</span>
                                    </a>
                                </li>
                               
                                <li class="sidebar-item">
                                    <a href="/balance_request_report" class="sidebar-link">
                                        <i class="mdi mdi-sort-variant"></i>
                                        <span class="hide-menu" style="">Balance Request Report</span>
                                    </a>
                                </li>

                            </ul>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark" href="/complaints?service_type=COMPLAINT" aria-expanded="false">
                            <img src="{{ asset('template_new/img/sidebar/complaint_box_ic.png') }}">
                                <span class="hide-menu" style="">Complaint Box</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                            <img src="{{ asset('template_new/img/sidebar/offers_notices_ic.png') }}">
                                <span class="hide-menu" style="">Offers & Notices </span>
                                <!-- <span class="badge badge-info badge-pill ml-auto mr-3 font-medium px-2 py-1">4</span> -->
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                        <li class="sidebar-item">
                                    <a href="/offers-notice-dtrt?type=OFFER" class="sidebar-link">
                                        <i class="mdi mdi-sort-variant"></i>
                                        <span class="hide-menu" style="">Offers</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="/offers-notice-dtrt?type=NOTICE" class="sidebar-link">
                                        <i class="mdi mdi-sort-variant"></i>
                                        <span class="hide-menu" style="">Notices</span>
                                    </a>
                                </li>
                        </ul>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark" href="{{ route('passbook') }}" aria-expanded="false">
                            <img src="{{ asset('template_new/img/sidebar/supportblue_ic.png') }}">
                                <span class="hide-menu" style="">Support</span>
                            </a>
                        </li>


                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark" href="{{ route('logout') }}" aria-expanded="false">
                                <img src="{{ asset('template_new/img/sidebar/logout_ic.png') }}">
                                <span class="hide-menu" style="">Logout</span>
                            </a>
                        </li>


                        <div class="devider"></div>


                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!-- ============================================================== -->
        <!-- Distributor End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        @endif
        <!-- ============================================================== -->
        <!-- Profile Pic Upload modal starts -->
        <div class="modal" id="updateProfilePicMdl" role="dialog">
            <div class="modal-dialog modal-sm" role="document" style="margin-left:530px">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLabel1"><span class="modal-action-name"></span>Update Profile Picture</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>

                    <form id="profilePicUploadForm" enctype="multipart/form-data">
                        @csrf
                        <div class="custom-file">
                            <input type="file" name="file" class="custom-file-input" id="chooseProfPicFile" required>
                            <label class="custom-file-label" for="chooseFile">Select file</label>
                        </div>

                        <button type="submit" id="profile-pic-upload-btn" class="btn btn-info btn-block mt-4">
                            Upload File
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <!-- Profile Pic uplaod modal ends -->



        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">

            @yield('page_content')
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
    </div>


    @else
    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <input type="hidden" id="loggedUserId" value="{{ Auth::id() }}">
        <input type="hidden" id="loggedUserMobileNo" value="{{ Auth::user()->mobile }}">
        <input type="hidden" id="roleAlias" value="{{ json_encode(Config::get('constants.ROLE_ALIAS')) }}">
        @if($message = Session::get('success'))
        <input type="hidden" id="session_success_msg" value="{{ $message }}">
        @elseif($message = Session::get('error'))
        <input type="hidden" id="session_error_msg" value="{{ $message }}">
        @endif
        <header class="topbar">
            <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                <div class="navbar-header border-right">
                    <!-- This is for the sidebar toggle which is visible on mobile only -->
                    <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i class="ti-menu ti-close"></i></a>
                    <a class="navbar-brand" href="{{  Auth::user()->roleId == Config::get('constants.ADMIN') ? url('/admin-home') : url('/home') }}">
                        <!-- Logo icon -->
                        <b class="logo-icon">
                            <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                            <!-- Dark Logo icon -->
                            <!-- <img src="../../assets/images/logos/logo-icon.png" alt="homepage" class="dark-logo" /> -->
                            <img src="{{ asset('template_assets/assets/images/logos/logo-icon-sm-py.png') }}" alt="homepage" class="dark-logo" />
                            <!-- Light Logo icon -->
                            <!-- <img src="../../assets/images/logos/logo-light-icon.png" alt="homepage" class="light-logo" /> -->
                            <img src="{{ asset('template_assets/assets/images/logos/logo-light-icon-sm-py.png') }}" alt="homepage" class="light-logo" />
                        </b>
                        <!--End Logo icon -->
                        <!-- Logo text -->
                        <span class="logo-text">
                            <!-- dark Logo text -->
                            <!-- <img src="../../assets/images/logos/logo-text.png" alt="homepage" class="dark-logo" /> -->
                            <img src="{{ asset('template_assets/assets/images/logos/logo-text.png') }}" alt="homepage" class="dark-logo" />
                            <!-- Light Logo text -->
                            <!-- <img src="../../assets/images/logos/logo-light-text.png" class="light-logo" alt="homepage" /> -->
                            <img src="{{ asset('template_assets/assets/images/logos/logo-light-text.png') }}" class="light-logo" alt="homepage" />
                        </span>
                    </a>
                    <!-- ============================================================== -->
                    <!-- End Logo -->
                    <!-- ============================================================== -->
                    <!-- ============================================================== -->
                    <!-- Toggle which is visible on mobile only -->
                    <!-- ============================================================== -->
                    <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i class="ti-more"></i></a>
                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <div class="navbar-collapse collapse" id="navbarSupportedContent">
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav float-left mr-auto">
                        <li class="nav-item d-none d-md-block"><a class="nav-link sidebartoggler waves-effect waves-light" href="javascript:void(0)" data-sidebartype="mini-sidebar"><i class="mdi mdi-menu font-18"></i></a></li>
                        <!-- ============================================================== -->
                        <!-- Messages -->
                        <!-- ============================================================== -->
                    </ul>
                    <!-- ============================================================== -->
                    <!-- Right side toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav float-right">
                        <!-- ============================================================== -->
                        <!-- Search -->
                        <!-- ============================================================== -->
                        <!-- <li class="nav-item search-box">
                            <form class="app-search d-none d-lg-block">
                                <input type="text" class="form-control" placeholder="Search...">
                                <a href="" class="active"><i class="fa fa-search"></i></a>
                            </form>
                        </li> -->
                        <li class="nav-item" style="margin-right:10px">
                            <div class="btn-group" style="margin-top:12px">
                                <button type="button" class="btn btn-light" style="pointer-events:none">
                                    <i class="mdi mdi-wallet"></i> <i class="mdi mdi-currency-inr"></i> {{ Auth::user()->wallet_balance ? sprintf('%.2f', Auth::user()->wallet_balance) : 0}}
                                </button>
                            </div>
                        </li>
                        @if( Auth::user()->roleId == Config::get('constants.DISTRIBUTOR') || Auth::user()->roleId == Config::get('constants.RETAILER') || ( Auth::user()->roleId == Config::get('constants.MASTER_DISTRIBUTOR')))
                        <!-- Wallet starts -->
                        <li class="nav-item" style="display:block;">
                            <div class="btn-group" style="margin-top:12px">
                                <button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="mdi mdi-wallet"></i> Load Wallet
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('online_payment') }}"><i class="mdi mdi-monitor"></i> ADD MONEY</a>
                                    <!-- <div class="dropdown-divider"></div> -->
                                </div>
                            </div>
                        </li>
                        <!-- Wallet Ends -->
                        @endif


                        <!-- ============================================================== -->
                        <!-- User profile and search -->
                        <!-- ============================================================== -->
                        @include('modules.other.user_profile')
                        <!-- ============================================================== -->
                        <!-- User profile and search -->
                        <!-- ============================================================== -->


                        <!-- <li class="nav-item">
                            @if ($message = Session::get('success'))
                                <div class="alert alert-success alert-block" style="max-height:40px">
                                    <button type="button" class="close" data-dismiss="alert">×</button>	
                                        <strong style="font-size:12px">{{ $message }}</strong>
                                </div>
                            @endif


                            @if ($message = Session::get('error'))
                                <div class="alert alert-danger alert-block" style="max-height:40px">
                                    <button type="button" class="close" data-dismiss="alert">×</button>	
                                        <strong style="font-size:12px">{{ $message }}</strong>
                                </div>
                            @endif
                        </li> -->
                    </ul>
                </div>
            </nav>
        </header>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->

        @if(Auth::user()->userId == '1')
        @if( Auth::user()->roleId == Config::get('constants.ADMIN'))
        <!-- ============================================================== -->
        <!-- Admin Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">

                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark" href="{{ route('admin-home') }}" aria-expanded="false">
                                <i class="mdi mdi-av-timer"></i>
                                <span class="hide-menu" style="">Dashboard</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <i class="mdi mdi-cart-outline"></i>
                                <span class="hide-menu" style="">Control Panel</span>
                            </a>

                            <ul aria-expanded="false" class="collapse first-level">

                                <li class="sidebar-item">
                                    <a class="has-arrow sidebar-link" href="javascript:void(0)" aria-expanded="false">
                                        <i class="mdi mdi-email-open-outline"></i>
                                        <span class="hide-menu" style="">API Management</span>
                                    </a>
                                    <ul aria-expanded="false" class="collapse second-level">

                                        <li class="sidebar-item">
                                            <a href="{{ route('service_type') }}" class="sidebar-link" aria-expanded="false">
                                                <i class=""></i>
                                                <span class="hide-menu" style="">Services</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('api_setting') }}" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu" style="">API Portal</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('operator') }}" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu" style="">Operators</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('operator_dtls') }}" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu" style="">API Operator Setting</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('operator_settings') }}" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu" style="">Transfer Recharge API</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('api_amount_details') }}" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu" style="">Transfer API by Amount</span>
                                            </a>
                                        </li>

                                    </ul>
                                </li>



                                <li class="sidebar-item">
                                    <a class="sidebar-link waves-effect waves-dark" href="{{ route('bbps_management') }}" aria-expanded="false">
                                        <i class="mdi mdi-account-outline"></i>
                                        <span class="hide-menu" style="">BBPS Management</span>
                                    </a>
                                </li>
                                <!-- <li class="sidebar-item">
                                    <a class="sidebar-link waves-effect waves-dark" href="{{ route('operator_settings') }}" aria-expanded="false">
                                        <i class="mdi mdi-account-outline"></i>
                                        <span class="hide-menu" style="">Operator Setting</span>
                                    </a>
                                </li> -->



                                <li class="sidebar-item">
                                    <a class="has-arrow sidebar-link" href="javascript:void(0)" aria-expanded="false">
                                        <i class="mdi mdi-email-open-outline"></i>
                                        <span class="hide-menu" style="">DMT Management </span>
                                    </a>
                                    <ul aria-expanded="false" class="collapse second-level">

                                        <li class="sidebar-item">
                                            <a href="{{ route('bank_list') }}" class="sidebar-link" aria-expanded="false">
                                                <i class=""></i>
                                                <span class="hide-menu" style="">Bank List</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('dmt_margin') }}" class="sidebar-link" aria-expanded="false">
                                                <i class=""></i>
                                                <span class="hide-menu" style="">DMT Margin</span>
                                            </a>
                                        </li>


                                    </ul>
                                </li>

                                <li class="sidebar-item">
                                    <a class="has-arrow sidebar-link" href="javascript:void(0)" aria-expanded="false">
                                        <i class="mdi mdi-email-open-outline"></i>
                                        <span class="hide-menu" style="">Margin Management</span>
                                    </a>
                                    <ul aria-expanded="false" class="collapse second-level">

                                        <li class="sidebar-item">
                                            <a href="{{ route('package_setting') }}" class="sidebar-link" aria-expanded="false">
                                                <i class=""></i>
                                                <span class="hide-menu" style="">Package Set</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('pack_comm_dtls') }}" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu" style="">Margin Set</span>
                                            </a>
                                        </li>


                                    </ul>
                                </li>

                                <li class="sidebar-item">
                                    <a class="has-arrow sidebar-link" href="javascript:void(0)" aria-expanded="false">
                                        <i class="mdi mdi-email-open-outline"></i>
                                        <span class="hide-menu" style="">PG Management</span>
                                    </a>
                                    <ul aria-expanded="false" class="collapse second-level">

                                        <li class="sidebar-item">
                                            <a href="{{ route('charges_setting') }}" class="sidebar-link" aria-expanded="false">
                                                <i class=""></i>
                                                <span class="hide-menu" style="">PG Charge Set</span>
                                            </a>
                                        </li>


                                    </ul>
                                </li>




                                <!-- 19-11-20 -->
                                <!-- <li class="sidebar-item">
                                    <a class="sidebar-link waves-effect waves-dark" href="{{ route('pay_gate_setting') }}" aria-expanded="false">
                                        <i class="mdi mdi-av-timer"></i>
                                        <span class="hide-menu" style="">Payment Gateway Setting</span>
                                    </a>
                                </li> -->
                                <!-- 19-11-20 -->
                                <!-- <li class="sidebar-item">
                                    <a class="sidebar-link waves-effect waves-dark" href="{{ route('sms_gate_setting') }}" aria-expanded="false">
                                        <i class="mdi mdi-av-timer"></i>
                                        <span class="hide-menu" style="">SMS Setting</span>
                                    </a>
                                </li> -->
                                <!-- 19-11-20 -->
                                <!-- <li class="sidebar-item">
                                    <a class="sidebar-link waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                        <i class="mdi mdi-av-timer"></i>
                                        <span class="hide-menu" style="">Sub Admin</span>
                                    </a>
                                </li> -->
                                <li class="sidebar-item">
                                    <a href="{{ route('general_setting') }}" class="sidebar-link">
                                        <i class="mdi mdi-bulletin-board"></i>
                                        <span class="hide-menu" style="">General Setting</span>
                                    </a>
                                </li>

                                <li class="sidebar-item">
                                    <a class="sidebar-link active" href="{{ route('day_book') }}" aria-expanded="false">
                                        <i class="mdi mdi-clipboard-text"></i>
                                        <span class="hide-menu" style="">Day Book</span>
                                    </a>
                                </li>


                            </ul>
                        </li>



                        <!-- Transaction Reports Starts -->
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <i class="mdi mdi-format-color-fill"></i>
                                <span class="hide-menu" style="">Reports Management </span>
                                <!-- <span class="badge badge-info badge-pill ml-auto mr-3 font-medium px-2 py-1">4</span> -->
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item">
                                    <a class="has-arrow sidebar-link" href="javascript:void(0)" aria-expanded="false">
                                        <i class="mdi mdi-email-open-outline"></i>
                                        <span class="hide-menu" style="">Transaction Report</span>
                                    </a>
                                    <ul aria-expanded="false" class="collapse second-level">

                                        <li class="sidebar-item">
                                            <a href="{{ route('transaction_report',['service_type'=>'RECHARGE']) }}" class="sidebar-link" aria-expanded="false">
                                                <i class=""></i>
                                                <span class="hide-menu" style="">Recharge Reports</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('transaction_report',['service_type'=>'BILL_PAYMENTS']) }}" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu" style="">Bill Payment Report</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('transaction_report',['service_type'=>'MONEY_TRANSFER']) }}" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu" style="">DMT Report</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('transaction_report',['service_type'=>'UPI_TRANSFER']) }}" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu" style="">BHIM UPI Report</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('transaction_report',['service_type'=>'AEPS']) }}" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu" style="">AEPS Report</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('transaction_report',['service_type'=>'Mini_Statement']) }}" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu" style="">Mini Statement Report</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('transaction_report',['service_type'=>'BALANCE_INQUIRY']) }}" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu" style="">Balance Inquiry Report</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('transaction_report',['service_type'=>'AADHAR_PAY']) }}" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu" style="">Aadhar Pay Report</span>
                                            </a>
                                        </li>
                                        <!-- <li class="sidebar-item">
                                    <a href="{{ route('transaction_report',['service_type'=>'ICICI_CASH_DEPOSIT']) }}" class="sidebar-link">
                                        <i class="mdi mdi-sort-variant"></i>
                                        <span class="hide-menu" style=""> ICICI CASH DEPOSIT Report</span>
                                    </a>
                                </li> -->

                                        <li class="sidebar-item">
                                            <a href="{{ route('icici_statement') }}" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-sort-variant"></i>
                                                <span class="hide-menu" style="">Bank Account Report</span>
                                            </a>
                                        </li>

                                    </ul>
                                </li>
                                <li class="sidebar-item">
                                    <a class="has-arrow sidebar-link" href="javascript:void(0)" aria-expanded="false">
                                        <i class="mdi mdi-email-open-outline"></i>
                                        <span class="hide-menu" style="">Commission Reports</span>
                                    </a>
                                    <ul aria-expanded="false" class="collapse second-level">

                                        <li class="sidebar-item">
                                            <a href="{{ route('commission_report',['service_type'=>'RECHARGE']) }}" class="sidebar-link" aria-expanded="false">
                                                <i class=""></i>
                                                <span class="hide-menu" style="">Recharge Reports</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('commission_report',['service_type'=>'BILL_PAYMENTS']) }}" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu" style="">Bill Payment Report</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('commission_report',['service_type'=>'MONEY_TRANSFER']) }}" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu" style="">DMT Report</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('commission_report',['service_type'=>'UPI_TRANSFER']) }}" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu" style="">BHIM UPI Report</span>
                                            </a>
                                        </li>


                                    </ul>
                                </li>

                                <li class="sidebar-item">
                                    <a class="has-arrow sidebar-link" href="javascript:void(0)" aria-expanded="false">
                                        <i class="mdi mdi-email-open-outline"></i>
                                        <span class="hide-menu" style="">SmartPay Reports</span>
                                    </a>
                                    <ul aria-expanded="false" class="collapse second-level">

                                        <li class="sidebar-item">
                                            <a href="{{ route('admin-home') }}" class="sidebar-link" aria-expanded="false">
                                                <i class=""></i>
                                                <span class="hide-menu" style="">Admin Transfer Reports</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('admin-home') }}" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu" style="">Admin Credit Report</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('admin-home') }}" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu" style="">Admin Wallet Report</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('admin-home') }}" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu" style="">Member Wallet Report</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('admin-home') }}" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu" style="">Panel Analysis</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('all_transfer') }}" class="sidebar-link">
                                                <i class="mdi mdi-view-day"></i>
                                                <span class="hide-menu" style="">All Transfer</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('all_transfer', ['trans'=>'ADMIN']) }}" class="sidebar-link">
                                                <i class="mdi mdi-view-day"></i>
                                                <span class="hide-menu" style="">Admin Transfer</span>
                                            </a>
                                        </li>


                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <!-- Transaction Reports Ends -->

                        <!-- MEMBER MANAGEMENT start -->
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <i class="mdi mdi-format-color-fill"></i>
                                <span class="hide-menu" style="">Member Management </span>
                                <span class="badge badge-info badge-pill ml-auto mr-3 font-medium px-2 py-1">4</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item">
                                    <a class="sidebar-link" href="{{ route('verification_list') }}" aria-expanded="false">
                                        <i class="mdi mdi-account-outline"></i>
                                        <span class="hide-menu" style="">Digital KYC</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('create_new') }}" class="sidebar-link">
                                        <i class="mdi mdi-comment-processing-outline"></i>
                                        <span class="hide-menu" style="">Member Registration</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a class="sidebar-link active" href="{{ route('user_list_ekyc') }}" aria-expanded="false">
                                        <i class="mdi mdi-clipboard-text"></i>
                                        <span class="hide-menu" style="">Member List Non-Ekyc</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a class="sidebar-link active" href="{{ route('user_list') }}" aria-expanded="false">
                                        <i class="mdi mdi-clipboard-text"></i>
                                        <span class="hide-menu" style="">Member List</span>
                                    </a>
                                </li>

                                <li class="sidebar-item">
                                    <a class="sidebar-link active" href="{{ route('user_list') }}" aria-expanded="false">
                                        <i class="mdi mdi-clipboard-text"></i>
                                        <span class="hide-menu" style="">Member Certificate</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a class="sidebar-link active" href="{{ route('create_subadmin') }}" aria-expanded="false">
                                        <i class="mdi mdi-clipboard-text"></i>
                                        <span class="hide-menu" style="">Sub Admin Registration</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a class="sidebar-link active" href="{{ route('user_list') }}" aria-expanded="false">
                                        <i class="mdi mdi-clipboard-text"></i>
                                        <span class="hide-menu" style="">Sub Admin Permission</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- MEMBER MANAGEMENT end -->

                        <!-- FOUND MANAGEMENT START -->
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <i class="mdi mdi-tune-vertical"></i>
                                <span class="hide-menu" style="">Fund Management </span>
                            </a>
                            <ul aria-expanded="false" class="collapse  first-level">
                                <li class="sidebar-item">
                                    <a href="{{ route('bank_account') }}" class="sidebar-link active">
                                        <i class="mdi mdi-view-quilt"></i>
                                        <span class="hide-menu" style="">Bank Account</span>
                                    </a>
                                </li>

                                <li class="sidebar-item">
                                    <a href="{{ route('balance_request_report') }}" class="sidebar-link">
                                        <i class="mdi mdi-view-day"></i>
                                        <span class="hide-menu" style="">Balance Request Report</span>
                                    </a>
                                </li>

                                <li class="sidebar-item">
                                    <a href="{{ route('user_payment_gateway_report') }}" class="sidebar-link">
                                        <i class="mdi mdi-apps"></i>
                                        <span class="hide-menu" style="">Payment Gateway Report</span>
                                    </a>
                                </li>

                                <li class="sidebar-item">
                                    <a href="{{ route('user_virtual_account_report') }}" class="sidebar-link">
                                        <i class="mdi mdi-apps"></i>
                                        <span class="hide-menu" style="">Virtual Account Report</span>
                                    </a>
                                </li>

                                <li class="sidebar-item">
                                    <a href="{{ route('user_qr_code_account_report') }}" class="sidebar-link">
                                        <i class="mdi mdi-apps"></i>
                                        <span class="hide-menu" style="">QR Code Report</span>
                                    </a>
                                </li>

                                <li class="sidebar-item">
                                    <a href="{{ route('transfer_revert_balance') }}" class="sidebar-link">
                                        <i class="mdi mdi-view-day"></i>
                                        <span class="hide-menu" style="">Fund Transfer/Revert </span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('all_transfer') }}" class="sidebar-link">
                                        <i class="mdi mdi-view-day"></i>
                                        <span class="hide-menu" style="">All Transfer</span>
                                    </a>
                                </li>

                            </ul>
                        </li>
                        <!-- FOUND MANAGEMENT END -->

                        <!-- Portal Management strat -->
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <i class="mdi mdi-apps"></i>
                                <span class="hide-menu" style="">Portal Management</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item">
                                    <a href="starter-kit.html" class="sidebar-link">
                                        <i class="mdi mdi-crop-free"></i>
                                        <span class="hide-menu" style="">Android App </span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="starter-kit.html" class="sidebar-link">
                                        <i class="mdi mdi-crop-free"></i>
                                        <span class="hide-menu" style="">Website</span>
                                    </a>
                                </li>

                                <li class="sidebar-item">
                                    <a href="starter-kit.html" class="sidebar-link">
                                        <i class="mdi mdi-crop-free"></i>
                                        <span class="hide-menu" style="">Login Panel</span>
                                    </a>
                                </li>


                                <li class="sidebar-item">
                                    <a href="{{ route('operator') }}" class="sidebar-link">
                                        <i class="mdi mdi-crop-free"></i>
                                        <span class="hide-menu" style="">Operator Helpline</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a class="has-arrow sidebar-link" href="javascript:void(0)" aria-expanded="false">
                                        <i class="mdi mdi-email-open-outline"></i>
                                        <span class="hide-menu" style="">Complaint Manage</span>
                                    </a>
                                    <ul aria-expanded="false" class="collapse second-level">

                                        <li class="sidebar-item">
                                            <a href="{{ route('complaints',['service_type'=>'COMPLAINT']) }}" class="sidebar-link" aria-expanded="false">
                                                <i class=""></i>
                                                <span class="hide-menu" style="">Compalint List</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('template') }}" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu" style="">Template</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="sidebar-item">
                                    <a class="sidebar-link waves-effect waves-dark" href="{{ route('slidder_banner') }}" aria-expanded="false">
                                        <i class="mdi mdi-av-timer"></i>
                                        <span class="hide-menu" style="">Slidder Banner Settings</span>
                                    </a>
                                </li>

                            </ul>
                        </li>
                        <!-- portal Management end -->

                        <!-- Office Management Start -->
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <i class="mdi mdi-apps"></i>
                                <span class="hide-menu" style="">Office Management</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">

                                <li class="sidebar-item">
                                    <a class="has-arrow sidebar-link" href="javascript:void(0)" aria-expanded="false">
                                        <i class="mdi mdi-email-open-outline"></i>
                                        <span class="hide-menu" style="">Office Expenses</span>
                                    </a>
                                    <ul aria-expanded="false" class="collapse second-level">

                                        <li class="sidebar-item">
                                            <a href="{{ route('office_expenses_report') }}" class="sidebar-link" aria-expanded="false">
                                                <i class=""></i>
                                                <span class="hide-menu" style="">Report</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('office_expenses_category') }}" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu" style="">Category</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>


                            </ul>
                        </li>
                        <!-- Office Management End -->

                        <!-- TDS Management Start -->
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <i class="mdi mdi mdi-crop-free"></i>
                                <span class="hide-menu" style="">TDS Management</span>
                                <!-- <span class="badge badge-info badge-pill ml-auto mr-3 font-medium px-2 py-1">2</span> -->
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <!-- <li class="sidebar-item">
                                            <a href="starter-kit.html" class="sidebar-link">
                                                <i class="mdi mdi-crop-free"></i>
                                                <span class="hide-menu" style="">TDS Charge Set </span>
                                            </a>
                                        </li> -->
                                <li class="sidebar-item">
                                    <a class="sidebar-link waves-effect waves-dark" href="{{ route('tds_report') }}" aria-expanded="false">
                                        <i class="mdi mdi-av-timer"></i>
                                        <span class="hide-menu" style=""> Report</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('tds_upload') }}" class="sidebar-link">
                                        <i class="mdi mdi-tablet"></i>
                                        <span class="hide-menu" style="">Upload Certificate</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- TDS Management End -->

                        <li class="sidebar-item">
                            <a href="starter-kit.html" class="sidebar-link">
                                <i class="mdi mdi-format-list-bulleted-type"></i>
                                <span class="hide-menu" style="">Crazypay Management</span>
                            </a>
                        </li>

                        <!-- News Management Start -->

                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <i class="mdi mdi-apps"></i>
                                <span class="hide-menu" style="">News Management</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item">
                                    <a href="{{ route('offers-notice') }}" class="sidebar-link">
                                        <i class="mdi mdi-crop-free"></i>
                                        <span class="hide-menu" style="">Offers & Notices </span>
                                    </a>
                                </li>

                                <li class="sidebar-item">
                                    <a class="has-arrow sidebar-link" href="javascript:void(0)" aria-expanded="false">
                                        <i class="mdi mdi-email-open-outline"></i>
                                        <span class="hide-menu" style="">SMS Management</span>
                                    </a>
                                    <ul aria-expanded="false" class="collapse second-level">

                                        <li class="sidebar-item">
                                            <a href="{{ route('sms_template') }}" class="sidebar-link" aria-expanded="false">
                                                <i class=""></i>
                                                <span class="hide-menu" style="">Template Management</span>
                                            </a>
                                        </li>

                                        <li class="sidebar-item">
                                            <a href="starter-kit.html" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu" style="">Bulk SMS</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                            </ul>
                        </li>
                        <!-- News Management End-->



                        <!-- Passbook Reports starts -->
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <i class="mdi mdi-format-color-fill"></i>
                                <span class="hide-menu" style="">Passbook </span>
                                <span class="badge badge-info badge-pill ml-auto mr-3 font-medium px-2 py-1">2</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item">
                                    <a class="sidebar-link waves-effect waves-dark" href="{{ route('passbook') }}" aria-expanded="false">
                                        <i class="mdi mdi-av-timer"></i>
                                        <span class="hide-menu" style="">My Passbook</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('member_passbook') }}" class="sidebar-link">
                                        <i class="mdi mdi-tablet"></i>
                                        <span class="hide-menu" style="">Member's Passbook</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- Passbook reports ends -->

                        <!-- Payment Getway Report -->




                        <!-- <li class="sidebar-item">
                                    <a class="has-arrow sidebar-link" href="javascript:void(0)" aria-expanded="false">
                                        <i class="mdi mdi-email-open-outline"></i>
                                        <span class="hide-menu" style="">Admin Account Report</span>
                                    </a>
                                    <ul aria-expanded="false" class="collapse second-level">
                                        <li class="sidebar-item">
                                            <a href="email-templete-alert.html" class="sidebar-link">
                                                <i class="mdi mdi-message-alert"></i>
                                                <span class="hide-menu" style="">Transfer Report</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="email-templete-basic.html" class="sidebar-link">
                                                <i class="mdi mdi-message-bulleted"></i>
                                                <span class="hide-menu" style="">Wallet Report</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li> -->

                        <!-- 19-11-20 -->
                        <!-- <li class="sidebar-item">
                                    <a href="ui-typography.html" class="sidebar-link">
                                        <i class="mdi mdi-format-line-spacing"></i>
                                        <span class="hide-menu" style="">Registration Charge Report</span>
                                    </a>
                                </li> -->
                        <!-- 19-11-20 -->
                        <!-- <li class="sidebar-item">
                                    <a href="ui-bootstrap.html" class="sidebar-link">
                                        <i class="mdi mdi-bootstrap"></i>
                                        <span class="hide-menu" style="">Credit Report</span>
                                    </a>
                                </li> -->
                        <!-- 19-11-20 -->
                        <!-- <li class="sidebar-item">
                                    <a href="ui-breadcrumb.html" class="sidebar-link">
                                        <i class="mdi mdi-equal"></i>
                                        <span class="hide-menu" style="">Expense Report</span>
                                    </a>
                                </li> -->
                        <!-- 19-11-20 -->
                        <!-- <li class="sidebar-item">
                                    <a href="ui-list-media.html" class="sidebar-link">
                                        <i class="mdi mdi-file-video"></i>
                                        <span class="hide-menu" style=""> API Fund Request Report</span>
                                    </a>
                                </li> -->
                        <!-- 19-11-20 -->
                        <!-- <li class="sidebar-item">
                                    <a href="ui-grid.html" class="sidebar-link">
                                        <i class="mdi mdi-view-module"></i>
                                        <span class="hide-menu" style=""> API Commission Report</span>
                                    </a>
                                </li> -->
                        <!-- <li class="sidebar-item">
                                    <a href="ui-carousel.html" class="sidebar-link">
                                        <i class="mdi mdi-view-carousel"></i>
                                        <span class="hide-menu" style="">Crazypay Report</span>
                                    </a>
                                </li> -->
                        <!-- <li class="sidebar-item">
                                    <a href="ui-scrollspy.html" class="sidebar-link">
                                        <i class="mdi mdi-crop-free"></i>
                                        <span class="hide-menu" style="">Complaint Report</span>
                                    </a>
                                </li> -->
                        <!-- <li class="sidebar-item">
                                    <a class="has-arrow sidebar-link" href="javascript:void(0)" aria-expanded="false">
                                        <i class="mdi mdi mdi-crop-free"></i>
                                        <span class="hide-menu" style="">Complaint</span>
                                    </a>
                                    <ul aria-expanded="false" class="collapse second-level">
                                        <li class="sidebar-item">
                                       
                                            <a href=" {{ route('complaints',['service_type'=>'COMPLAINT']) }}" class="sidebar-link">
                                                <i class="mdi mdi-message-alert"></i>
                                                <span class="hide-menu" style="">Complaint List</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('template') }}" class="sidebar-link">
                                                <i class="mdi mdi-message-bulleted"></i>
                                                <span class="hide-menu" style="">Template</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li> -->



                        <!-- </ul>
                        </li> -->









                        <!-- SMS Management Starts -->
                        <!-- <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <i class="mdi mdi-message-text"></i>
                                <span class="hide-menu" style="">SMS Management</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item">
                                    <a href="{{ route('sms_template') }}" class="sidebar-link">
                                        <i class="mdi mdi-message-draw"></i>
                                        <span class="hide-menu" style="">Templates</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="javascript:void(0)" class="sidebar-link">
                                        <i class="mdi mdi-message-processing"></i>
                                        <span class="hide-menu" style="">Send SMS</span>
                                    </a>
                                </li>
                            </ul>
                        </li> -->
                        <!-- Sms Management ends -->
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark" href="{{ route('downloadbackup') }}" aria-expanded="false">
                                <i class="mdi mdi-av-timer"></i>
                                <span class="hide-menu" style="">Download Backup</span>
                            </a>
                        </li>


                        <div class="devider"></div>

                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!-- ============================================================== -->
        <!-- Admin End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        @endif
        @else
        {{ $subadmin = DB::table('tbl_user_persmissions')->where('user_id', Auth::user()->userId)->get() }}
        <!-- ============================================================== -->
        <!--SUB Admin Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->

        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        @if(isset($subadmin[0]->dashboard) && ($subadmin[0]->dashboard == 1))
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark" href="{{ route('admin-home') }}" aria-expanded="false">
                                <i class="mdi mdi-av-timer"></i>
                                <span class="hide-menu" style="">Dashboard</span>
                            </a>
                        </li>
                        @endif

                        @if(isset($subadmin[0]->control_panel) && ($subadmin[0]->control_panel == 1))
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <i class="mdi mdi-cart-outline"></i>
                                <span class="hide-menu" style="">Control Panel</span>
                            </a>

                            <ul aria-expanded="false" class="collapse first-level">

                                <li class="sidebar-item">
                                    <a class="has-arrow sidebar-link" href="javascript:void(0)" aria-expanded="false">
                                        <i class="mdi mdi-email-open-outline"></i>
                                        <span class="hide-menu" style="">API Management</span>
                                    </a>
                                    <ul aria-expanded="false" class="collapse second-level">

                                        <li class="sidebar-item">
                                            <a href="{{ route('service_type') }}" class="sidebar-link" aria-expanded="false">
                                                <i class=""></i>
                                                <span class="hide-menu" style="">Services</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('api_setting') }}" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu" style="">API Portal</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('operator') }}" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu" style="">Operators</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('operator_dtls') }}" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu" style="">API Operator Setting</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('operator_settings') }}" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu" style="">Transfer Recharge API</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('api_amount_details') }}" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu" style="">Transfer API by Amount</span>
                                            </a>
                                        </li>

                                    </ul>
                                </li>



                                <li class="sidebar-item">
                                    <a class="sidebar-link waves-effect waves-dark" href="{{ route('bbps_management') }}" aria-expanded="false">
                                        <i class="mdi mdi-account-outline"></i>
                                        <span class="hide-menu" style="">BBPS Management</span>
                                    </a>
                                </li>
                                <!-- <li class="sidebar-item">
                                    <a class="sidebar-link waves-effect waves-dark" href="{{ route('operator_settings') }}" aria-expanded="false">
                                        <i class="mdi mdi-account-outline"></i>
                                        <span class="hide-menu" style="">Operator Setting</span>
                                    </a>
                                </li> -->



                                <li class="sidebar-item">
                                    <a class="has-arrow sidebar-link" href="javascript:void(0)" aria-expanded="false">
                                        <i class="mdi mdi-email-open-outline"></i>
                                        <span class="hide-menu" style="">DMT Management </span>
                                    </a>
                                    <ul aria-expanded="false" class="collapse second-level">

                                        <li class="sidebar-item">
                                            <a href="{{ route('bank_list') }}" class="sidebar-link" aria-expanded="false">
                                                <i class=""></i>
                                                <span class="hide-menu" style="">Bank List</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('dmt_margin') }}" class="sidebar-link" aria-expanded="false">
                                                <i class=""></i>
                                                <span class="hide-menu" style="">DMT Margin</span>
                                            </a>
                                        </li>


                                    </ul>
                                </li>

                                <li class="sidebar-item">
                                    <a class="has-arrow sidebar-link" href="javascript:void(0)" aria-expanded="false">
                                        <i class="mdi mdi-email-open-outline"></i>
                                        <span class="hide-menu" style="">Margin Management</span>
                                    </a>
                                    <ul aria-expanded="false" class="collapse second-level">

                                        <li class="sidebar-item">
                                            <a href="{{ route('package_setting') }}" class="sidebar-link" aria-expanded="false">
                                                <i class=""></i>
                                                <span class="hide-menu" style="">Package Set</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('pack_comm_dtls') }}" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu" style="">Margin Set</span>
                                            </a>
                                        </li>


                                    </ul>
                                </li>

                                <li class="sidebar-item">
                                    <a class="has-arrow sidebar-link" href="javascript:void(0)" aria-expanded="false">
                                        <i class="mdi mdi-email-open-outline"></i>
                                        <span class="hide-menu" style="">PG Management</span>
                                    </a>
                                    <ul aria-expanded="false" class="collapse second-level">

                                        <li class="sidebar-item">
                                            <a href="{{ route('charges_setting') }}" class="sidebar-link" aria-expanded="false">
                                                <i class=""></i>
                                                <span class="hide-menu" style="">PG Charge Set</span>
                                            </a>
                                        </li>


                                    </ul>
                                </li>




                                <!-- 19-11-20 -->
                                <!-- <li class="sidebar-item">
                                    <a class="sidebar-link waves-effect waves-dark" href="{{ route('pay_gate_setting') }}" aria-expanded="false">
                                        <i class="mdi mdi-av-timer"></i>
                                        <span class="hide-menu" style="">Payment Gateway Setting</span>
                                    </a>
                                </li> -->
                                <!-- 19-11-20 -->
                                <!-- <li class="sidebar-item">
                                    <a class="sidebar-link waves-effect waves-dark" href="{{ route('sms_gate_setting') }}" aria-expanded="false">
                                        <i class="mdi mdi-av-timer"></i>
                                        <span class="hide-menu" style="">SMS Setting</span>
                                    </a>
                                </li> -->
                                <!-- 19-11-20 -->
                                <!-- <li class="sidebar-item">
                                    <a class="sidebar-link waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                        <i class="mdi mdi-av-timer"></i>
                                        <span class="hide-menu" style="">Sub Admin</span>
                                    </a>
                                </li> -->
                                <li class="sidebar-item">
                                    <a href="{{ route('general_setting') }}" class="sidebar-link">
                                        <i class="mdi mdi-bulletin-board"></i>
                                        <span class="hide-menu" style="">General Setting</span>
                                    </a>
                                </li>

                                <li class="sidebar-item">
                                    <a class="sidebar-link active" href="{{ route('day_book') }}" aria-expanded="false">
                                        <i class="mdi mdi-clipboard-text"></i>
                                        <span class="hide-menu" style="">Day Book</span>
                                    </a>
                                </li>


                            </ul>
                        </li>
                        @endif

                        @if(isset($subadmin[0]->report_management) && ($subadmin[0]->report_management == 1))
                        <!-- Transaction Reports Starts -->
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <i class="mdi mdi-format-color-fill"></i>
                                <span class="hide-menu" style="">Reports Management </span>
                                <span class="badge badge-info badge-pill ml-auto mr-3 font-medium px-2 py-1">4</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item">
                                    <a class="has-arrow sidebar-link" href="javascript:void(0)" aria-expanded="false">
                                        <i class="mdi mdi-email-open-outline"></i>
                                        <span class="hide-menu" style="">Transaction Report</span>
                                    </a>
                                    <ul aria-expanded="false" class="collapse second-level">

                                        <li class="sidebar-item">
                                            <a href="{{ route('transaction_report',['service_type'=>'RECHARGE']) }}" class="sidebar-link" aria-expanded="false">
                                                <i class=""></i>
                                                <span class="hide-menu" style="">Recharge Reports</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('transaction_report',['service_type'=>'BILL_PAYMENTS']) }}" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu" style="">Bill Payment Report</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('transaction_report',['service_type'=>'MONEY_TRANSFER']) }}" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu" style="">DMT Report</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('transaction_report',['service_type'=>'UPI_TRANSFER']) }}" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu" style="">BHIM UPI Report</span>
                                            </a>
                                        </li>


                                    </ul>
                                </li>
                                <li class="sidebar-item">
                                    <a class="has-arrow sidebar-link" href="javascript:void(0)" aria-expanded="false">
                                        <i class="mdi mdi-email-open-outline"></i>
                                        <span class="hide-menu" style="">Commission Reports</span>
                                    </a>
                                    <ul aria-expanded="false" class="collapse second-level">

                                        <li class="sidebar-item">
                                            <a href="{{ route('commission_report',['service_type'=>'RECHARGE']) }}" class="sidebar-link" aria-expanded="false">
                                                <i class=""></i>
                                                <span class="hide-menu" style="">Recharge Reports</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('commission_report',['service_type'=>'BILL_PAYMENTS']) }}" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu" style="">Bill Payment Report</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('commission_report',['service_type'=>'MONEY_TRANSFER']) }}" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu" style="">DMT Report</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('commission_report',['service_type'=>'UPI_TRANSFER']) }}" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu" style="">BHIM UPI Report</span>
                                            </a>
                                        </li>


                                    </ul>
                                </li>

                                <li class="sidebar-item">
                                    <a class="has-arrow sidebar-link" href="javascript:void(0)" aria-expanded="false">
                                        <i class="mdi mdi-email-open-outline"></i>
                                        <span class="hide-menu" style="">SmartPay Reports</span>
                                    </a>
                                    <ul aria-expanded="false" class="collapse second-level">

                                        <li class="sidebar-item">
                                            <a href="{{ route('admin-home') }}" class="sidebar-link" aria-expanded="false">
                                                <i class=""></i>
                                                <span class="hide-menu" style="">Admin Transfer Reports</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('admin-home') }}" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu" style="">Admin Credit Report</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('admin-home') }}" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu" style="">Admin Wallet Report</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('admin-home') }}" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu" style="">Member Wallet Reprot</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('admin-home') }}" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu" style="">Panel Analysis</span>
                                            </a>
                                        </li>


                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <!-- Transaction Reports Ends -->
                        @endif

                        @if(isset($subadmin[0]->member_management) && ($subadmin[0]->member_management == 1))
                        <!-- MEMBER MANAGEMENT start -->
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <i class="mdi mdi-format-color-fill"></i>
                                <span class="hide-menu" style="">Member Management </span>
                                <span class="badge badge-info badge-pill ml-auto mr-3 font-medium px-2 py-1">4</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item">
                                    <a href="{{ route('create_member') }}" class="sidebar-link">
                                        <i class="mdi mdi-comment-processing-outline"></i>
                                        <span class="hide-menu" style="">Member Registration</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a class="sidebar-link active" href="{{ route('user_list') }}" aria-expanded="false">
                                        <i class="mdi mdi-clipboard-text"></i>
                                        <span class="hide-menu" style="">Member List</span>
                                    </a>
                                </li>

                                <li class="sidebar-item">
                                    <a class="sidebar-link active" href="{{ route('user_list') }}" aria-expanded="false">
                                        <i class="mdi mdi-clipboard-text"></i>
                                        <span class="hide-menu" style="">Member Certificate</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a class="sidebar-link active" href="{{ route('create_subadmin') }}" aria-expanded="false">
                                        <i class="mdi mdi-clipboard-text"></i>
                                        <span class="hide-menu" style="">Sub Admin Registration</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a class="sidebar-link active" href="{{ route('user_list') }}" aria-expanded="false">
                                        <i class="mdi mdi-clipboard-text"></i>
                                        <span class="hide-menu" style="">Sub Admin Permission</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- MEMBER MANAGEMENT end -->
                        @endif

                        @if(isset($subadmin[0]->fund_management) && ($subadmin[0]->fund_management == 1))
                        <!-- FOUND MANAGEMENT START -->
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <i class="mdi mdi-tune-vertical"></i>
                                <span class="hide-menu" style="">Fund Management </span>
                            </a>
                            <ul aria-expanded="false" class="collapse  first-level">
                                <li class="sidebar-item">
                                    <a href="{{ route('bank_account') }}" class="sidebar-link active">
                                        <i class="mdi mdi-view-quilt"></i>
                                        <span class="hide-menu" style="">Bank Account</span>
                                    </a>
                                </li>

                                <li class="sidebar-item">
                                    <a href="{{ route('balance_request') }}" class="sidebar-link">
                                        <i class="mdi mdi-view-day"></i>
                                        <span class="hide-menu" style="">Balance Request Report</span>
                                    </a>
                                </li>

                                <li class="sidebar-item">
                                    <a href="{{ route('user_payment_gateway_report') }}" class="sidebar-link">
                                        <img src="{{ asset('template_new/img/sidebar/add_money_ic.png') }}">
                                        <span class="hide-menu" style="">Payment Gateway Report</span>
                                    </a>
                                </li>

                                <li class="sidebar-item">
                                    <a href="{{ route('user_virtual_account_report') }}" class="sidebar-link">
                                        <img src="{{ asset('template_new/img/sidebar/add_money_ic.png') }}">
                                        <span class="hide-menu" style="">Virtual Account Report</span>
                                    </a>
                                </li>

                                <li class="sidebar-item">
                                    <a href="{{ route('transfer_revert_balance') }}" class="sidebar-link">
                                        <i class="mdi mdi-view-day"></i>
                                        <span class="hide-menu" style="">Fund Transfer/Revert </span>
                                    </a>
                                </li>
                                <!-- <li class="sidebar-item">
                                    <a href="{{ route('all_transfer') }}" class="sidebar-link">
                                        <i class="mdi mdi-view-day"></i>
                                        <span class="hide-menu" style="">All Transfer</span>
                                    </a>
                                </li> -->

                            </ul>
                        </li>
                        <!-- FOUND MANAGEMENT END -->
                        @endif

                        @if(isset($subadmin[0]->portal_management) && ($subadmin[0]->portal_management == 1))
                        <!-- Portal Management strat -->
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <i class="mdi mdi-apps"></i>
                                <span class="hide-menu" style="">Portal Management</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item">
                                    <a href="starter-kit.html" class="sidebar-link">
                                        <i class="mdi mdi-crop-free"></i>
                                        <span class="hide-menu" style="">Android App </span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="starter-kit.html" class="sidebar-link">
                                        <i class="mdi mdi-crop-free"></i>
                                        <span class="hide-menu" style="">Website</span>
                                    </a>
                                </li>

                                <li class="sidebar-item">
                                    <a href="starter-kit.html" class="sidebar-link">
                                        <i class="mdi mdi-crop-free"></i>
                                        <span class="hide-menu" style="">Login Panel</span>
                                    </a>
                                </li>


                                <li class="sidebar-item">
                                    <a href="{{ route('operator') }}" class="sidebar-link">
                                        <i class="mdi mdi-crop-free"></i>
                                        <span class="hide-menu" style="">Operator Helpline</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a class="has-arrow sidebar-link" href="javascript:void(0)" aria-expanded="false">
                                        <i class="mdi mdi-email-open-outline"></i>
                                        <span class="hide-menu" style="">Complaint Manage</span>
                                    </a>
                                    <ul aria-expanded="false" class="collapse second-level">

                                        <li class="sidebar-item">
                                            <a href="{{ route('complaints',['service_type'=>'COMPLAINT']) }}" class="sidebar-link" aria-expanded="false">
                                                <i class=""></i>
                                                <span class="hide-menu" style="">Compalint List</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('template') }}" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu" style="">Template</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="sidebar-item">
                                    <a class="sidebar-link waves-effect waves-dark" href="{{ route('slidder_banner') }}" aria-expanded="false">
                                        <i class="mdi mdi-av-timer"></i>
                                        <span class="hide-menu" style="">Slidder Banner Settings</span>
                                    </a>
                                </li>

                            </ul>
                        </li>
                        <!-- portal Management end -->
                        @endif


                        @if(isset($subadmin[0]->office_management) && ($subadmin[0]->office_management == 1))
                        <!-- Office Management Start -->
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <i class="mdi mdi-apps"></i>
                                <span class="hide-menu" style="">Office Management</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">

                                <li class="sidebar-item">
                                    <a class="has-arrow sidebar-link" href="javascript:void(0)" aria-expanded="false">
                                        <i class="mdi mdi-email-open-outline"></i>
                                        <span class="hide-menu" style="">Office Expenses</span>
                                    </a>
                                    <ul aria-expanded="false" class="collapse second-level">

                                        <li class="sidebar-item">
                                            <a href="{{ route('office_expenses_report') }}" class="sidebar-link" aria-expanded="false">
                                                <i class=""></i>
                                                <span class="hide-menu" style="">Report</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('office_expenses_category') }}" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu" style="">Category</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>


                            </ul>
                        </li>
                        <!-- Office Management End -->
                        @endif


                        @if(isset($subadmin[0]->tds_management) && ($subadmin[0]->tds_management == 1))
                        <!-- TDS Management Start -->
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <i class="mdi mdi mdi-crop-free"></i>
                                <span class="hide-menu" style="">TDS Management</span>
                                <!-- <span class="badge badge-info badge-pill ml-auto mr-3 font-medium px-2 py-1">2</span> -->
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <!-- <li class="sidebar-item">
                                        <a href="starter-kit.html" class="sidebar-link">
                                            <i class="mdi mdi-crop-free"></i>
                                            <span class="hide-menu" style="">TDS Charge Set </span>
                                        </a>
                                    </li> -->
                                <li class="sidebar-item">
                                    <a class="sidebar-link waves-effect waves-dark" href="{{ route('tds_report') }}" aria-expanded="false">
                                        <i class="mdi mdi-av-timer"></i>
                                        <span class="hide-menu" style=""> Report</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('tds_upload') }}" class="sidebar-link">
                                        <i class="mdi mdi-tablet"></i>
                                        <span class="hide-menu" style="">Upload Certificate</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- TDS Management End -->
                        @endif


                        @if(isset($subadmin[0]->crazypay_management) && ($subadmin[0]->crazypay_management == 1))
                        <li class="sidebar-item">
                            <a href="starter-kit.html" class="sidebar-link">
                                <i class="mdi mdi-format-list-bulleted-type"></i>
                                <span class="hide-menu" style="">Crazypay Management</span>
                            </a>
                        </li>
                        @endif


                        @if(isset($subadmin[0]->news_management) && ($subadmin[0]->news_management == 1))
                        <!-- News Management Start -->

                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <i class="mdi mdi-apps"></i>
                                <span class="hide-menu" style="">News Management</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item">
                                    <a href="{{ route('offers-notice') }}" class="sidebar-link">
                                        <i class="mdi mdi-crop-free"></i>
                                        <span class="hide-menu" style="">Offers & Notices </span>
                                    </a>
                                </li>

                                <li class="sidebar-item">
                                    <a class="has-arrow sidebar-link" href="javascript:void(0)" aria-expanded="false">
                                        <i class="mdi mdi-email-open-outline"></i>
                                        <span class="hide-menu" style="">SMS Management</span>
                                    </a>
                                    <ul aria-expanded="false" class="collapse second-level">

                                        <li class="sidebar-item">
                                            <a href="{{ route('sms_template') }}" class="sidebar-link" aria-expanded="false">
                                                <i class=""></i>
                                                <span class="hide-menu" style="">Template Management</span>
                                            </a>
                                        </li>

                                        <li class="sidebar-item">
                                            <a href="starter-kit.html" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu" style="">Bulk SMS</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>



                            </ul>
                        </li>
                        <!-- News Management End-->
                        @endif


                        @if(isset($subadmin[0]->passbook) && ($subadmin[0]->passbook == 1))
                        <!-- Passbook Reports starts -->
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <i class="mdi mdi-format-color-fill"></i>
                                <span class="hide-menu" style="">Passbook </span>
                                <span class="badge badge-info badge-pill ml-auto mr-3 font-medium px-2 py-1">2</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item">
                                    <a class="sidebar-link waves-effect waves-dark" href="{{ route('passbook') }}" aria-expanded="false">
                                        <i class="mdi mdi-av-timer"></i>
                                        <span class="hide-menu" style="">My Passbook</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('member_passbook') }}" class="sidebar-link">
                                        <i class="mdi mdi-tablet"></i>
                                        <span class="hide-menu" style="">Member's Passbook</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- Passbook reports ends -->
                        @endif

                        <!-- Payment Getway Report -->




                        <!-- <li class="sidebar-item">
                                    <a class="has-arrow sidebar-link" href="javascript:void(0)" aria-expanded="false">
                                        <i class="mdi mdi-email-open-outline"></i>
                                        <span class="hide-menu" style="">Admin Account Report</span>
                                    </a>
                                    <ul aria-expanded="false" class="collapse second-level">
                                        <li class="sidebar-item">
                                            <a href="email-templete-alert.html" class="sidebar-link">
                                                <i class="mdi mdi-message-alert"></i>
                                                <span class="hide-menu" style="">Transfer Report</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="email-templete-basic.html" class="sidebar-link">
                                                <i class="mdi mdi-message-bulleted"></i>
                                                <span class="hide-menu" style="">Wallet Report</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li> -->

                        <!-- 19-11-20 -->
                        <!-- <li class="sidebar-item">
                                    <a href="ui-typography.html" class="sidebar-link">
                                        <i class="mdi mdi-format-line-spacing"></i>
                                        <span class="hide-menu" style="">Registration Charge Report</span>
                                    </a>
                                </li> -->
                        <!-- 19-11-20 -->
                        <!-- <li class="sidebar-item">
                                    <a href="ui-bootstrap.html" class="sidebar-link">
                                        <i class="mdi mdi-bootstrap"></i>
                                        <span class="hide-menu" style="">Credit Report</span>
                                    </a>
                                </li> -->
                        <!-- 19-11-20 -->
                        <!-- <li class="sidebar-item">
                                    <a href="ui-breadcrumb.html" class="sidebar-link">
                                        <i class="mdi mdi-equal"></i>
                                        <span class="hide-menu" style="">Expense Report</span>
                                    </a>
                                </li> -->
                        <!-- 19-11-20 -->
                        <!-- <li class="sidebar-item">
                                    <a href="ui-list-media.html" class="sidebar-link">
                                        <i class="mdi mdi-file-video"></i>
                                        <span class="hide-menu" style=""> API Fund Request Report</span>
                                    </a>
                                </li> -->
                        <!-- 19-11-20 -->
                        <!-- <li class="sidebar-item">
                                    <a href="ui-grid.html" class="sidebar-link">
                                        <i class="mdi mdi-view-module"></i>
                                        <span class="hide-menu" style=""> API Commission Report</span>
                                    </a>
                                </li> -->
                        <!-- <li class="sidebar-item">
                                    <a href="ui-carousel.html" class="sidebar-link">
                                        <i class="mdi mdi-view-carousel"></i>
                                        <span class="hide-menu" style="">Crazypay Report</span>
                                    </a>
                                </li> -->
                        <!-- <li class="sidebar-item">
                                    <a href="ui-scrollspy.html" class="sidebar-link">
                                        <i class="mdi mdi-crop-free"></i>
                                        <span class="hide-menu" style="">Complaint Report</span>
                                    </a>
                                </li> -->
                        <!-- <li class="sidebar-item">
                                    <a class="has-arrow sidebar-link" href="javascript:void(0)" aria-expanded="false">
                                        <i class="mdi mdi mdi-crop-free"></i>
                                        <span class="hide-menu" style="">Complaint</span>
                                    </a>
                                    <ul aria-expanded="false" class="collapse second-level">
                                        <li class="sidebar-item">
                                       
                                            <a href=" {{ route('complaints',['service_type'=>'COMPLAINT']) }}" class="sidebar-link">
                                                <i class="mdi mdi-message-alert"></i>
                                                <span class="hide-menu" style="">Complaint List</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('template') }}" class="sidebar-link">
                                                <i class="mdi mdi-message-bulleted"></i>
                                                <span class="hide-menu" style="">Template</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li> -->



                        <!-- </ul>
                        </li> -->









                        <!-- SMS Management Starts -->
                        <!-- <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <i class="mdi mdi-message-text"></i>
                                <span class="hide-menu" style="">SMS Management</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item">
                                    <a href="{{ route('sms_template') }}" class="sidebar-link">
                                        <i class="mdi mdi-message-draw"></i>
                                        <span class="hide-menu" style="">Templates</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="javascript:void(0)" class="sidebar-link">
                                        <i class="mdi mdi-message-processing"></i>
                                        <span class="hide-menu" style="">Send SMS</span>
                                    </a>
                                </li>
                            </ul>
                        </li> -->
                        <!-- Sms Management ends -->



                        <div class="devider"></div>

                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!-- ============================================================== -->
        <!--SUB Admin End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        @endif

        @if( Auth::user()->roleId == Config::get('constants.DISTRIBUTOR') || ( Auth::user()->roleId == Config::get('constants.MASTER_DISTRIBUTOR')) )
        <!-- ============================================================== -->
        <!-- Distributor Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">

                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark" href="{{ route('home') }}" aria-expanded="false">
                                <i class="mdi mdi-av-timer"></i>
                                <span class="hide-menu" style="">Dashboard</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <i class="mdi mdi-format-color-fill"></i>
                                <span class="hide-menu" style="">Reports </span>
                                <span class="badge badge-info badge-pill ml-auto mr-3 font-medium px-2 py-1">4</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">

                                <li class="sidebar-item">
                                    <a href="{{ route('transaction_report',['service_type'=>'RECHARGE']) }}" class="sidebar-link">
                                        <i class="mdi mdi-toggle-switch"></i>
                                        <span class="hide-menu" style=""> Recharge Reports</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('transaction_report',['service_type'=>'BILL_PAYMENTS']) }}" class="sidebar-link">
                                        <i class="mdi mdi-tablet"></i>
                                        <span class="hide-menu" style="">Bill Payment Report</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('transaction_report',['service_type'=>'MONEY_TRANSFER']) }}" class="sidebar-link">
                                        <i class="mdi mdi-sort-variant"></i>
                                        <span class="hide-menu" style="">Money Transfer Report</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('transaction_report',['service_type'=>'UPI_TRANSFER']) }}" class="sidebar-link">
                                        <i class="mdi mdi-sort-variant"></i>
                                        <span class="hide-menu" style="">Bhim UPI</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('transaction_report',['service_type'=>'AEPS']) }}" class="sidebar-link">
                                        <i class="mdi mdi-image-filter-vintage"></i>
                                        <span class="hide-menu" style="">AEPS Report</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Commission Reports starts -->
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <i class="mdi mdi-format-color-fill"></i>
                                <span class="hide-menu" style="">Commission Reports </span>
                                <span class="badge badge-info badge-pill ml-auto mr-3 font-medium px-2 py-1">4</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item">
                                    <a href="{{ route('commission_report',['service_type'=>'RECHARGE']) }}" class="sidebar-link">
                                        <i class="mdi mdi-toggle-switch"></i>
                                        <span class="hide-menu" style=""> Recharge Reports</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('commission_report',['service_type'=>'BILL_PAYMENTS']) }}" class="sidebar-link">
                                        <i class="mdi mdi-tablet"></i>
                                        <span class="hide-menu" style="">Bill Payment Report</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('commission_report',['service_type'=>'MONEY_TRANSFER']) }}" class="sidebar-link">
                                        <i class="mdi mdi-sort-variant"></i>
                                        <span class="hide-menu" style="">DMT Report</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('commission_report',['service_type'=>'UPI_TRANSFER']) }}" class="sidebar-link">
                                        <i class="mdi mdi-sort-variant"></i>
                                        <span class="hide-menu" style="">UPI Transfer Report</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('commission_report',['service_type'=>'AEPS']) }}" class="sidebar-link">
                                        <i class="mdi mdi-image-filter-vintage"></i>
                                        <span class="hide-menu" style="">AEPS Report</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- commission reports ends -->


                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <i class="mdi mdi-tune-vertical"></i>
                                <span class="hide-menu" style="">Balance </span>
                            </a>
                            <ul aria-expanded="false" class="collapse  first-level">
                                <li class="sidebar-item">
                                    <a href="{{ route('bank_account') }}" class="sidebar-link active">
                                        <i class="mdi mdi-view-quilt"></i>
                                        <span class="hide-menu" style="">Bank Account</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('balance_request') }}" class="sidebar-link">
                                        <i class="mdi mdi-view-day"></i>
                                        <span class="hide-menu" style="">Balance Request</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('transfer_revert_balance') }}" class="sidebar-link">
                                        <i class="mdi mdi-view-day"></i>
                                        <span class="hide-menu" style="">Transfer/Revert Balance</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('all_transfer') }}" class="sidebar-link">
                                        <i class="mdi mdi-view-day"></i>
                                        <span class="hide-menu" style="">All Transfer</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <i class="mdi mdi-tune-vertical"></i>
                                <span class="hide-menu" style="">Credit Report </span>
                            </a>
                            <ul aria-expanded="false" class="collapse  first-level">
                                <li class="sidebar-item">
                                    <a href="{{ route('credit_report', ['report'=>'RETAILER'] ) }}" class="sidebar-link active">
                                        <i class="mdi mdi-view-quilt"></i>
                                        <span class="hide-menu" style="">Retailers</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('credit_report', ['report'=>'FOS']) }}" class="sidebar-link active">
                                        <i class="mdi mdi-view-quilt"></i>
                                        <span class="hide-menu" style="">FOS</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark" href="{{ route('passbook') }}" aria-expanded="false">
                                <i class="mdi mdi-av-timer"></i>
                                <span class="hide-menu" style="">Passbook / Account Report</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <i class="mdi mdi-format-color-fill"></i>
                                <span class="hide-menu" style="">Retailer Management </span>
                                <span class="badge badge-info badge-pill ml-auto mr-3 font-medium px-2 py-1">12</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item">
                                    <a href="{{ route('create_member',['role_alias' => Config::get('constants.ROLE_ALIAS.RETAILER')]) }}" class="sidebar-link">
                                        <i class="mdi mdi-toggle-switch"></i>
                                        <span class="hide-menu" style=""> Add New Retailer</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('user_list',['role_alias' => Config::get('constants.ROLE_ALIAS.RETAILER')]) }}" class="sidebar-link">
                                        <i class="mdi mdi-tablet"></i>
                                        <span class="hide-menu" style="">List of Retailers</span>
                                    </a>
                                </li>

                                <!-- 19-11-2020 -->
                                <!-- <li class="sidebar-item">
                                    <a href="ui-tab.html" class="sidebar-link">
                                        <i class="mdi mdi-sort-variant"></i>
                                        <span class="hide-menu" style="">Retailer Account Report</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="ui-tooltip-popover.html" class="sidebar-link">
                                        <i class="mdi mdi-image-filter-vintage"></i>
                                        <span class="hide-menu" style="">Operator Report</span>
                                    </a>
                                </li> -->
                            </ul>
                        </li>

                        <!-- user payment gateway Report -->
                        <li class="sidebar-item">
                            <a href="{{ route('user_payment_gateway_report') }}" class="sidebar-link">
                                <i class="mdi mdi-av-timer"></i>
                                <span class="hide-menu" style="">User Payment Gateway Report</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="{{ route('user_virtual_account_report') }}" class="sidebar-link">
                                <i class="mdi mdi-av-timer"></i>
                                <span class="hide-menu" style=""> User Virtual Account Report</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <i class="mdi mdi-format-color-fill"></i>
                                <span class="hide-menu" style="">FOS Management </span>
                                <span class="badge badge-info badge-pill ml-auto mr-3 font-medium px-2 py-1">12</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item">
                                    <a href="{{ route('create_member',['role_alias' => Config::get('constants.ROLE_ALIAS.FOS')]) }}" class="sidebar-link">
                                        <i class="mdi mdi-toggle-switch"></i>
                                        <span class="hide-menu" style=""> Add New FOS</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('user_list',['role_alias' => Config::get('constants.ROLE_ALIAS.FOS')]) }}" class="sidebar-link">
                                        <i class="mdi mdi-tablet"></i>
                                        <span class="hide-menu" style="">List Of FOS</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <i class="mdi mdi-av-timer"></i>
                                <span class="hide-menu" style="">Passbook</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark" href="{{ route('complaints',['service_type'=>'COMPLAINT']) }}" aria-expanded="false">
                                <i class="mdi mdi-av-timer"></i>
                                <span class="hide-menu" style="">Complaint Box</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark" href="{{ route('view_tds', Auth::user()->userId) }}" aria-expanded="false">
                                <i class="mdi mdi-av-timer"></i>
                                <span class="hide-menu" style="">TDS Certificate</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark" href="{{ route('my_commission') }}" aria-expanded="false">
                                <i class="mdi mdi-av-timer"></i>
                                <span class="hide-menu" style="">My Commission</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark" href="{{ route('operator_helpline') }}" aria-expanded="false">
                                <i class="mdi mdi-av-timer"></i>
                                <span class="hide-menu" style="">Operator Helpline</span>
                            </a>
                        </li>

                        <!-- <li class="sidebar-item">
                                    <a href="{{ route('all-offers-notice') }}" class="sidebar-link">
                                        <i class="mdi mdi-bell"></i>
                                        <span class="hide-menu" style="">Offers & Notice</span>
                                    </a>
                        </li> -->

                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <i class="mdi mdi-tune-vertical"></i>
                                <span class="hide-menu" style=""> Offers & Notice</span>
                            </a>
                            <ul aria-expanded="false" class="collapse  first-level">
                                <li class="sidebar-item">
                                    <a href="{{ route('offers-notice-dtrt', ['type'=>'OFFER']) }}" class="sidebar-link active">
                                        <i class="mdi mdi-view-quilt"></i>
                                        <span class="hide-menu" style="">Offers</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('offers-notice-dtrt', ['type'=>'NOTICE']) }}" class="sidebar-link active">
                                        <i class="mdi mdi-view-quilt"></i>
                                        <span class="hide-menu" style="">Notices</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- 19-11-2020 -->
                        <!-- <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <i class="mdi mdi-av-timer"></i>
                                <span class="hide-menu" style="">Support</span>
                            </a>
                        </li> -->

                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark" href="{{ route('logout') }}" aria-expanded="false">
                                <i class="mdi mdi-av-timer"></i>
                                <span class="hide-menu" style="">Logout</span>
                            </a>
                        </li>


                        <div class="devider"></div>


                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!-- ============================================================== -->
        <!-- Distributor End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        @endif

        @if( Auth::user()->roleId == Config::get('constants.RETAILER'))
        <!-- ============================================================== -->
        <!-- Retailer Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark" href="{{ route('home') }}" aria-expanded="false">
                                <i class="mdi mdi-av-timer"></i>
                                <span class="hide-menu" style="">Dashboard</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark" href="{{ route('money_transfer') }}" aria-expanded="false">
                                <i class="mdi mdi-currency-inr"></i>
                                <span class="hide-menu" style="">Money Transfer</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark" href="{{ route('aeps') }}" aria-expanded="false">
                                <img src="{{ asset('template_assets/AEPS-ICON.png') }}">
                                <span class="hide-menu" style="">AEPS</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <i class="mdi mdi-format-color-fill"></i>
                                <span class="hide-menu" style="">Reports </span>
                                <span class="badge badge-info badge-pill ml-auto mr-3 font-medium px-2 py-1">4</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item">
                                    <a href="{{ route('transaction_report',['service_type'=>'RECHARGE']) }}" class="sidebar-link">
                                        <i class="mdi mdi-toggle-switch"></i>
                                        <span class="hide-menu" style=""> Recharge Reports</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('transaction_report',['service_type'=>'BILL_PAYMENTS']) }}" class="sidebar-link">
                                        <i class="mdi mdi-tablet"></i>
                                        <span class="hide-menu" style="">Bill Payment Report</span>
                                    </a>
                                </li>

                                <!-- Comment For Aproval Start -->
                                <li class="sidebar-item">
                                    <a href="{{ route('transaction_report',['service_type'=>'MONEY_TRANSFER']) }}" class="sidebar-link">
                                        <i class="mdi mdi-sort-variant"></i>
                                        <span class="hide-menu" style="">Money Transfer Report</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('transaction_report',['service_type'=>'UPI_TRANSFER']) }}" class="sidebar-link">
                                        <i class="mdi mdi-sort-variant"></i>
                                        <span class="hide-menu" style="">Bhim UPI</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('transaction_report',['service_type'=>'AEPS']) }}" class="sidebar-link">
                                        <i class="mdi mdi-image-filter-vintage"></i>
                                        <span class="hide-menu" style="">AEPS Report</span>
                                    </a>
                                </li>

                                <li class="sidebar-item">
                                    <a href="{{ route('transaction_report',['service_type'=>'AADHAR_PAY']) }}" class="sidebar-link" aria-expanded="false">
                                        <i class="mdi mdi-message-bulleted-off"></i>
                                        <span class="hide-menu" style="">Aadhar Pay Report</span>
                                    </a>
                                </li>
                               <!-- <li class="sidebar-item">
                                    <a href="{{ route('transaction_report',['service_type'=>'ICICI_CASH_DEPOSIT']) }}" class="sidebar-link" aria-expanded="false">
                                        <i class="mdi mdi-message-bulleted-off"></i>
                                        <span class="hide-menu" style="">ICICI Cash Deposit Report</span>
                                    </a>
                                </li> -->
                                <!-- Comment For Aproval End -->

                            </ul>
                        </li>

                        <!-- Commission Reports starts -->
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <i class="mdi mdi-format-color-fill"></i>
                                <span class="hide-menu" style="">Commission Reports </span>
                                <span class="badge badge-info badge-pill ml-auto mr-3 font-medium px-2 py-1">4</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item">
                                    <a href="{{ route('commission_report',['service_type'=>'RECHARGE']) }}" class="sidebar-link">
                                        <i class="mdi mdi-toggle-switch"></i>
                                        <span class="hide-menu" style=""> Recharge Reports</span>
                                    </a>
                                </li>

                                <li class="sidebar-item">
                                    <a href="{{ route('commission_report',['service_type'=>'BILL_PAYMENTS']) }}" class="sidebar-link">
                                        <i class="mdi mdi-tablet"></i>
                                        <span class="hide-menu" style="">Bill Payment Report</span>
                                    </a>
                                </li>
                                <!--   <li class="sidebar-item">
                                        <a href="{{ route('commission_report',['service_type'=>'MONEY_TRANSFER']) }}" class="sidebar-link">
                                            <i class="mdi mdi-sort-variant"></i>
                                            <span class="hide-menu" style="">Money Transfer Report</span>
                                        </a>
                                    </li> -->

                                <!-- Comment For Aproval Start -->
                                <li class="sidebar-item">
                                    <a href="{{ route('commission_report',['service_type'=>'AEPS']) }}" class="sidebar-link">
                                        <i class="mdi mdi-image-filter-vintage"></i>
                                        <span class="hide-menu" style="">AEPS Report</span>
                                    </a>
                                </li>
                                <!-- Comment For Aproval ENd -->
                            </ul>
                        </li>
                        <!-- commission reports ends -->

                        <!-- user payment gateway Report -->
                        <li class="sidebar-item">
                            <a href="{{ route('user_payment_gateway_report') }}" class="sidebar-link">
                                <img src="{{ asset('template_new/img/sidebar/add_money_ic.png') }}">
                                <span class="hide-menu" style="">User Payment Gateway Report</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('user_virtual_account_report') }}" class="sidebar-link">
                                <img src="{{ asset('template_new/img/sidebar/add_money_ic.png') }}">
                                <span class="hide-menu" style="">User Virtual Account Report</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <i class="mdi mdi-tune-vertical"></i>
                                <span class="hide-menu" style="">Balance </span>
                            </a>
                            <ul aria-expanded="false" class="collapse  first-level">
                                <li class="sidebar-item">
                                    <a href="{{ route('bank_account') }}" class="sidebar-link active">
                                        <i class="mdi mdi-view-quilt"></i>
                                        <span class="hide-menu" style="">Bank Account</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('balance_request') }}" class="sidebar-link">
                                        <i class="mdi mdi-view-day"></i>
                                        <span class="hide-menu" style="">Balance Request</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark" href="{{ route('passbook') }}" aria-expanded="false">
                                <i class="mdi mdi-av-timer"></i>
                                <span class="hide-menu" style="">Passbook</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark" href="{{ route('complaints',['service_type'=>'COMPLAINT']) }}" aria-expanded="false">
                                <i class="mdi mdi-av-timer"></i>
                                <span class="hide-menu" style="">Complaint Box</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark" href="{{ route('view_tds', Auth::user()->userId) }}" aria-expanded="false">
                                <i class="mdi mdi-av-timer"></i>
                                <span class="hide-menu" style="">TDS Certificate</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark" href="{{ route('my_commission') }}" aria-expanded="false">
                                <i class="mdi mdi-av-timer"></i>
                                <span class="hide-menu" style="">My Commission</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark" href="{{ route('operator_helpline') }}" aria-expanded="false">
                                <i class="mdi mdi-av-timer"></i>
                                <span class="hide-menu" style="">Operator Helpline</span>
                            </a>
                        </li>

                        <!-- <li class="sidebar-item">
                                    <a href="{{ route('all-offers-notice') }}" class="sidebar-link">
                                        <i class="mdi mdi-bell"></i>
                                        <span class="hide-menu" style="">Offers & Notice</span>
                                    </a>
                        </li> -->

                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <i class="mdi mdi-tune-vertical"></i>
                                <span class="hide-menu" style=""> Offers & Notice</span>
                            </a>
                            <ul aria-expanded="false" class="collapse  first-level">
                                <li class="sidebar-item" style="list-style-type:disc important;">
                                    <a href="{{ route('offers-notice-dtrt', ['type'=>'OFFER']) }}" class="sidebar-link active">
                                        <i class="mdi mdi-view-quilt"></i>
                                        <span class="hide-menu" style="">Offers</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('offers-notice-dtrt', ['type'=>'NOTICE']) }}" class="sidebar-link active">
                                        <i class="mdi mdi-view-quilt"></i>
                                        <span class="hide-menu" style="">Notices</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- 19-11-2020 -->
                        <!-- <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <i class="mdi mdi-av-timer"></i>
                                <span class="hide-menu" style="">Support</span>
                            </a>
                        </li> -->

                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark" href="{{ route('logout') }}" aria-expanded="false">
                                <i class="mdi mdi-av-timer"></i>
                                <span class="hide-menu" style="">Logout</span>
                            </a>
                        </li>


                        <div class="devider"></div>
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!-- ============================================================== -->
        <!-- Retailer End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        @endif

        <!-- Update KYC modal starts -->
        <div class="modal" id="updateKYCModal" role="dialog">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLabel1">Update KYC</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <form id="updateKycForm" action="{{ route('update_kyc') }}" method="post">
                        @csrf
                        <input type="hidden" name="kyc_id" value="{{isset(Auth::userKycId()['id']) ? Auth::userKycId()['id'] : ''}}">
                        <input type="hidden" class="form-control label" name="status" value="{{isset(Auth::userKycId()['status']) ? Auth::userKycId()['status'] : ''}}" readonly>
                        <div class="row m-4">
                            <div class="col-3 border-bottom">
                                <div class="form-group">
                                    <label class="label font-sm" for="">PAN Card (Front)
                                        @if(isset(Auth::userKycId()['pan_front_file_status']) && Auth::userKycId()['pan_front_file_status'] == 'APPROVED')
                                        <i class="mdi mdi-checkbox-marked-circle text-success"></i>
                                        @endif
                                        @if(isset(Auth::userKycId()['pan_front_file_status']) && Auth::userKycId()['pan_front_file_status'] == 'PENDING')
                                        <i class="fa fa-hourglass-half text-warning"></i>
                                        @endif
                                        @if(isset(Auth::userKycId()['pan_front_file_status']) && Auth::userKycId()['pan_front_file_status'] == 'DECLINED')
                                        <i class="mdi mdi-close-circle text-danger"></i>
                                        @endif
                                    </label>
                                    <input type="hidden" name="pan_front_file_status" value="{{ isset(Auth::userKycId()['pan_front_file_status']) ? Auth::userKycId()['pan_front_file_status'] : '' }}">
                                    <input type="hidden" id="pan_front_file_id" name="pan_front_file_id" value="{{isset(Auth::userKycId()['pan_front_file_id']) ? Auth::userKycId()['pan_front_file_id'] : ''}}"><br>
                                    @if(!isset(Auth::userKycId()['pan_front_file_status']) || (isset(Auth::userKycId()['pan_front_file_status']) && Auth::userKycId()['pan_front_file_status'] != 'APPROVED'))
                                    <button id="pan-file-up-btn" onclick="openUploadModal('pan-file-up-btn','pan_front_file_id','pan_front_img_id')" class="btn btn-sm btn-light" type="button"><i class="mdi mdi-upload"></i> Upload</button>
                                    @endif
                                </div>
                            </div>
                            <div class="col-3 border-right border-bottom">
                                <img id="pan_front_img_id" src="{{isset(Auth::userKycId()['panFile']['file_path']) ? (Auth::userKycId()['panFile']['file_path']) : ''}}" alt="{{isset(Auth::userKycId()['panFile']['name']) ? Auth::userKycId()['panFile']['name'] : ''}}" style="height:60px;width:100%;border:1px solid lightgrey">
                            </div>

                            <div class="col-3 border-bottom">
                                <div class="form-group">
                                    <label class="label font-sm" for="">Aadhar Card (Front)
                                        @if(isset(Auth::userKycId()['aadhar_front_file_status']) && Auth::userKycId()['aadhar_front_file_status'] == 'APPROVED')
                                        <i class="mdi mdi-checkbox-marked-circle text-success"></i>
                                        @endif
                                        @if(isset(Auth::userKycId()['aadhar_front_file_status']) && Auth::userKycId()['aadhar_front_file_status'] == 'PENDING')
                                        <i class="fa fa-hourglass-half text-warning"></i>
                                        @endif
                                        @if(isset(Auth::userKycId()['aadhar_front_file_status']) && Auth::userKycId()['aadhar_front_file_status'] == 'DECLINED')
                                        <i class="mdi mdi-close-circle text-danger"></i>
                                        @endif
                                    </label>
                                    <input type="hidden" name="aadhar_front_file_status" value="{{ isset(Auth::userKycId()['aadhar_front_file_status']) ? Auth::userKycId()['aadhar_front_file_status'] : '' }}">
                                    <input type="hidden" id="aadhar_front_file_id" name="aadhar_front_file_id" value="{{isset(Auth::userKycId()['aadhar_front_file_id']) ? Auth::userKycId()['aadhar_front_file_id'] : ''}}"><br>

                                    @if(!isset(Auth::userKycId()['aadhar_front_file_status']) || (isset(Auth::userKycId()['aadhar_front_file_status']) && Auth::userKycId()['aadhar_front_file_status'] != 'APPROVED'))
                                    <button id="aadhar-front-file-up-btn" class="btn btn-sm btn-light" onclick="openUploadModal('aadhar-front-file-up-btn','aadhar_front_file_id','aadhar_front_img_id')" type="button"><i class="mdi mdi-upload"></i> Upload</button>
                                    @endif
                                </div>
                            </div>

                            <div class="col-3 border-bottom">
                                <img id="aadhar_front_img_id" src="{{isset(Auth::userKycId()['aadharFrontFile']['file_path']) ? Auth::userKycId()['aadharFrontFile']['file_path'] : ''}}" alt="{{isset(Auth::userKycId()['aadharFrontFile']['name']) ? Auth::userKycId()['aadharFrontFile']['name'] : ''}}" style="height:60px;width:100%;border:1px solid lightgrey">
                            </div>

                            <div class="col-3 mt-4 border-bottom">
                                <div class="form-group">
                                    <label class="label font-sm" for="">Aadhar Card (Back)
                                        @if(isset(Auth::userKycId()['aadhar_back_file_status']) && Auth::userKycId()['aadhar_back_file_status'] == 'APPROVED')
                                        <i class="mdi mdi-checkbox-marked-circle text-success"></i>
                                        @endif
                                        @if(isset(Auth::userKycId()['aadhar_back_file_status']) && Auth::userKycId()['aadhar_back_file_status'] == 'PENDING')
                                        <i class="fa fa-hourglass-half text-warning"></i>
                                        @endif
                                        @if(isset(Auth::userKycId()['aadhar_back_file_status']) && Auth::userKycId()['aadhar_back_file_status'] == 'DECLINED')
                                        <i class="mdi mdi-close-circle text-danger"></i>
                                        @endif
                                    </label>
                                    <input type="hidden" name="aadhar_back_file_status" value="{{ isset(Auth::userKycId()['aadhar_back_file_status']) ? Auth::userKycId()['aadhar_back_file_status'] : '' }}">
                                    <input type="hidden" id="aadhar_back_file_id" name="aadhar_back_file_id" value="{{isset(Auth::userKycId()['aadhar_back_file_id']) ? Auth::userKycId()['aadhar_back_file_id'] : ''}}"><br>

                                    @if(!isset(Auth::userKycId()['aadhar_back_file_status']) || (isset(Auth::userKycId()['aadhar_back_file_status']) && Auth::userKycId()['aadhar_back_file_status'] != 'APPROVED'))
                                    <button id="aadhar-back-file-up-btn" class="btn btn-sm btn-light" onclick="openUploadModal('aadhar-back-file-up-btn','aadhar_back_file_id','aadhar_back_img_id')" type="button"><i class="mdi mdi-upload"></i> Upload</button>
                                    @endif
                                </div>
                            </div>
                            <div class="col-3 mt-4 border-right border-bottom">
                                <img id="aadhar_back_img_id" src="{{ isset(Auth::userKycId()['aadharBackFile']['file_path']) ? (Auth::userKycId()['aadharBackFile']['file_path']) : ''}}" alt="{{isset(Auth::userKycId()['aadharBackFile']['name']) ? Auth::userKycId()['aadharBackFile']['name'] : ''}}" style="height:60px;width:100%;border:1px solid lightgrey">
                            </div>

                            <div class="col-3 mt-4 border-bottom">
                                <div class="form-group">
                                    <label class="label font-sm" for=""> Store Photo (Front)
                                        @if(isset(Auth::userKycId()['photo_front_file_status']) && Auth::userKycId()['photo_front_file_status'] == 'APPROVED')
                                        <i class="mdi mdi-checkbox-marked-circle text-success"></i>
                                        @endif
                                        @if(isset(Auth::userKycId()['photo_front_file_status']) && Auth::userKycId()['photo_front_file_status'] == 'PENDING')
                                        <i class="fa fa-hourglass-half text-warning"></i>
                                        @endif
                                        @if(isset(Auth::userKycId()['photo_front_file_status']) && Auth::userKycId()['photo_front_file_status'] == 'DECLINED')
                                        <i class="mdi mdi-close-circle text-danger"></i>
                                        @endif
                                    </label>
                                    <input type="hidden" name="photo_front_file_status" value="{{ isset(Auth::userKycId()['photo_front_file_status']) ? Auth::userKycId()['photo_front_file_status'] : '' }}">
                                    <input type="hidden" id="photo_front_file_id" name="photo_front_file_id" value="{{isset(Auth::userKycId()['photo_front_file_id']) ? Auth::userKycId()['photo_front_file_id'] : ''}}"><br>

                                    @if(!isset(Auth::userKycId()['photo_front_file_status']) || (isset(Auth::userKycId()['photo_front_file_status']) && Auth::userKycId()['photo_front_file_status'] != 'APPROVED'))
                                    <button id="photo-front-file-up-btn" class="btn btn-sm btn-light" onclick="openUploadModal('photo-front-file-up-btn','photo_front_file_id','photo_front_img_id')" type="button"><i class="mdi mdi-upload"></i> Upload</button>
                                    @endif
                                </div>
                            </div>
                            <div class="col-3 mt-4 border-bottom">
                                <img id="photo_front_img_id" src="{{isset(Auth::userKycId()['photoFrontFile']['file_path']) ? Auth::userKycId()['photoFrontFile']['file_path'] : ''}}" alt="{{isset(Auth::userKycId()['photoFrontFile']['name']) ? Auth::userKycId()['photoFrontFile']['name'] : ''}}" style="height:60px;width:100%;border:1px solid lightgrey">
                            </div>
                            <div class="col-3 mt-4">
                                <div class="form-group">
                                    <label class="label font-sm" for="">Store Photo (Inner)
                                        @if(isset(Auth::userKycId()['photo_inner_file_status']) && Auth::userKycId()['photo_inner_file_status'] == 'APPROVED')
                                        <i class="mdi mdi-checkbox-marked-circle text-success"></i>
                                        @endif
                                        @if(isset(Auth::userKycId()['photo_inner_file_status']) && Auth::userKycId()['photo_inner_file_status'] == 'PENDING')
                                        <i class="fa fa-hourglass-half text-warning"></i>
                                        @endif
                                        @if(isset(Auth::userKycId()['photo_inner_file_status']) && Auth::userKycId()['photo_inner_file_status'] == 'DECLINED')
                                        <i class="mdi mdi-close-circle text-danger"></i>
                                        @endif
                                    </label>
                                    <input type="hidden" name="photo_inner_file_status" value="{{ isset(Auth::userKycId()['photo_inner_file_status']) ? Auth::userKycId()['photo_inner_file_status'] : '' }}">
                                    <input type="hidden" id="photo_inner_file_id" name="photo_inner_file_id" value="{{isset(Auth::userKycId()['photo_inner_file_id']) ? Auth::userKycId()['photo_inner_file_id'] : ''}}"><br>

                                    @if(!isset(Auth::userKycId()['photo_inner_file_status']) || (isset(Auth::userKycId()['photo_inner_file_status']) && Auth::userKycId()['photo_inner_file_status'] != 'APPROVED'))
                                    <button id="photo-inner-file-up-btn" class="btn btn-sm btn-light" onclick="openUploadModal('photo-inner-file-up-btn','photo_inner_file_id','photo_inner_img_id')" type="button"><i class="mdi mdi-upload"></i> Upload</button>
                                    @endif
                                </div>
                            </div>
                            <div class="col-3 mt-4">
                                <img id="photo_inner_img_id" src="{{isset(Auth::userKycId()['photoInnerFile']['file_path']) ? Auth::userKycId()['photoInnerFile']['file_path'] : ''}}" alt="{{isset(Auth::userKycId()['photoInnerFile']['name']) ? Auth::userKycId()['photoInnerFile']['name'] : ''}}" style="height:60px;width:100%;border:1px solid lightgrey">
                            </div>
                            @if(isset(Auth::userKycId()['status']))
                            <!-- <div class="col-6 mt-4">
                                <div class="form-group">
                                    <label class="label" for="ad-kyc-status">KYC Status</label>
                                    <input type="text" class="form-control label" value="{{isset(Auth::userKycId()['status']) ? Auth::userKycId()['status'] : ''}}" readonly>
                                </div>
                            </div> -->
                            @endif
                        </div>

                        <button type="submit" id="update-kyc-btn" class="btn btn-info btn-block mt-4">
                            Update
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <!-- Update KYC modal ends -->

        <!-- Change Password modal starts -->
        <div class="modal" id="chgPwdModal" role="dialog">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLabel1" style="margin-left:60px">Change Password</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <form id="chgPwdForm" action="{{ route('change_user_Pwd') }}" method="post">
                        @csrf
                        <div class="form-group text-center" style="margin-top:20px">
                            <input type="password" class="form-control text-center" name="password" id="profile_password" placeholder="Enter New Password" autocomplete="off">
                        </div>

                        <div class="form-group text-center" style="margin-top:20px">
                            <input type="password" class="form-control text-center" name="password_confirmation" id="profile_password_confirmation" placeholder="Confirm Password" autocomplete="off">
                            <label id="password_confirmation-error" class="error hide-this text-danger" for="password_confirmation">This field is required.</label>
                        </div>

                        <div class="form-group text-center hide-this" id="pwd-otp-verify-div" style="margin-top:20px">
                            <input type="number" class="form-control text-center" name="verification_otp" id="pwd_verification_otp" placeholder="Enter 6 digit OTP you received." autocomplete="off">
                        </div>

                        <button type="submit" id="update-pwd-btn" class="btn btn-info btn-block mt-4">
                            Update
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <!-- Change Password modal ends -->

        <!-- Change Mpin modal starts -->
        <div class="modal" id="chgMpinModal" role="dialog">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLabel1" style="margin-left:60px">Change Mpin</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <form id="chgMpinForm" action="{{ route('change_user_mpin') }}" method="post">
                        @csrf
                        <div class="form-group text-center" style="margin-top:20px">
                            <input type="text text-center" class="form-control text-center" name="mpin" id="new-mpin" placeholder="Enter New Mpin" autocomplete="off">
                        </div>

                        <div class="form-group text-center hide-this" id="mpin-otp-verify-div" style="margin-top:20px">
                            <input type="number" class="form-control text-center" name="verification_otp" id="mpin_verification_otp" placeholder="Enter 6 digit OTP you received." autocomplete="off">
                        </div>

                        <button type="submit" id="update-mpin-btn" class="btn btn-info btn-block mt-4">
                            Update
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <!-- Change Mpin modal ends -->

        <!-- File Upload modal starts -->
        <div class="modal" id="kycFileUploadMdl" role="dialog">
            <div class="modal-dialog modal-sm" role="document" style="margin-left:530px">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLabel1">Upload File</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <input type="hidden" id="btn-data">
                    <input type="hidden" id="file-data">
                    <input type="hidden" id="img-data">
                    <form id="kycFileUploadForm" enctype="multipart/form-data">
                        @csrf
                        <div class="custom-file">
                            <input type="file" name="file" class="custom-file-input" id="chooseKycFile" required>
                            <label class="custom-file-label" for="chooseFile">Select file</label>
                        </div>

                        <button type="submit" id="kyc-file-upload-btn" class="btn btn-info btn-block mt-4">
                            Upload File
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <!-- File uplaod modal ends -->

        <!-- Profile Pic Upload modal starts -->
        <div class="modal" id="updateProfilePicMdl" role="dialog">
            <div class="modal-dialog modal-sm" role="document" style="margin-left:530px">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLabel1"><span class="modal-action-name"></span>Update Profile Picture</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>

                    <form id="profilePicUploadForm" enctype="multipart/form-data">
                        @csrf
                        <div class="custom-file">
                            <input type="file" name="file" class="custom-file-input" id="chooseProfPicFile" required>
                            <label class="custom-file-label" for="chooseFile">Select file</label>
                        </div>

                        <button type="submit" id="profile-pic-upload-btn" class="btn btn-info btn-block mt-4">
                            Upload File
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <!-- Profile Pic uplaod modal ends -->

        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            @yield('page_content')
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
    </div>
    @endif
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- customizer Panel -->
    <!-- ============================================================== -->
    <aside class="customizer" style="display:none">
        <a href="javascript:void(0)" class="service-panel-toggle">
            <i class="fa fa-spin fa-cog"></i>
        </a>
        <div class="customizer-body">
            <ul class="nav customizer-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">
                        <i class="mdi mdi-wrench font-20"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#chat" role="tab" aria-controls="chat" aria-selected="false">
                        <i class="mdi mdi-message-reply font-20"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">
                        <i class="mdi mdi-star-circle font-20"></i>
                    </a>
                </li>
            </ul>
            <div class="tab-content" id="pills-tabContent">
                <!-- Tab 1 -->
                <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                    <div class="p-3 border-bottom">
                        <!-- Sidebar -->
                        <h5 class="font-medium mb-2 mt-2">Layout Settings</h5>

                        <div class="custom-control custom-checkbox mt-2">
                            <input type="checkbox" class="custom-control-input sidebartoggler" name="collapssidebar" id="collapssidebar">
                            <label class="custom-control-label" for="collapssidebar">Collapse Sidebar</label>
                        </div>
                        <div class="custom-control custom-checkbox mt-2">
                            <input type="checkbox" class="custom-control-input" name="sidebar-position" id="sidebar-position">
                            <label class="custom-control-label" for="sidebar-position">Fixed Sidebar</label>
                        </div>
                        <div class="custom-control custom-checkbox mt-2">
                            <input type="checkbox" class="custom-control-input" name="header-position" id="header-position">
                            <label class="custom-control-label" for="header-position">Fixed Header</label>
                        </div>
                        <div class="custom-control custom-checkbox mt-2">
                            <input type="checkbox" class="custom-control-input" name="boxed-layout" id="boxed-layout">
                            <label class="custom-control-label" for="boxed-layout">Boxed Layout</label>
                        </div>
                    </div>
                    <div class="p-3 border-bottom">
                        <!-- Logo BG -->
                        <h5 class="font-medium mb-2 mt-2">Logo Backgrounds</h5>
                        <ul class="theme-color">
                            <li class="theme-item">
                                <a href="javascript:void(0)" class="theme-link" data-logobg="skin1"></a>
                            </li>
                            <li class="theme-item">
                                <a href="javascript:void(0)" class="theme-link" data-logobg="skin2"></a>
                            </li>
                            <li class="theme-item">
                                <a href="javascript:void(0)" class="theme-link" data-logobg="skin3"></a>
                            </li>
                            <li class="theme-item">
                                <a href="javascript:void(0)" class="theme-link" data-logobg="skin4"></a>
                            </li>
                            <li class="theme-item">
                                <a href="javascript:void(0)" class="theme-link" data-logobg="skin5"></a>
                            </li>
                            <li class="theme-item">
                                <a href="javascript:void(0)" class="theme-link" data-logobg="skin6"></a>
                            </li>
                        </ul>
                        <!-- Logo BG -->
                    </div>
                    <div class="p-3 border-bottom">
                        <!-- Navbar BG -->
                        <h5 class="font-medium mb-2 mt-2">Navbar Backgrounds</h5>
                        <ul class="theme-color">
                            <li class="theme-item">
                                <a href="javascript:void(0)" class="theme-link" data-navbarbg="skin1"></a>
                            </li>
                            <li class="theme-item">
                                <a href="javascript:void(0)" class="theme-link" data-navbarbg="skin2"></a>
                            </li>
                            <li class="theme-item">
                                <a href="javascript:void(0)" class="theme-link" data-navbarbg="skin3"></a>
                            </li>
                            <li class="theme-item">
                                <a href="javascript:void(0)" class="theme-link" data-navbarbg="skin4"></a>
                            </li>
                            <li class="theme-item">
                                <a href="javascript:void(0)" class="theme-link" data-navbarbg="skin5"></a>
                            </li>
                            <li class="theme-item">
                                <a href="javascript:void(0)" class="theme-link" data-navbarbg="skin6"></a>
                            </li>
                        </ul>
                        <!-- Navbar BG -->
                    </div>
                    <div class="p-3 border-bottom">
                        <!-- Logo BG -->
                        <h5 class="font-medium mb-2 mt-2">Sidebar Backgrounds</h5>
                        <ul class="theme-color">
                            <li class="theme-item">
                                <a href="javascript:void(0)" class="theme-link" data-sidebarbg="skin1"></a>
                            </li>
                            <li class="theme-item">
                                <a href="javascript:void(0)" class="theme-link" data-sidebarbg="skin2"></a>
                            </li>
                            <li class="theme-item">
                                <a href="javascript:void(0)" class="theme-link" data-sidebarbg="skin3"></a>
                            </li>
                            <li class="theme-item">
                                <a href="javascript:void(0)" class="theme-link" data-sidebarbg="skin4"></a>
                            </li>
                            <li class="theme-item">
                                <a href="javascript:void(0)" class="theme-link" data-sidebarbg="skin5"></a>
                            </li>
                            <li class="theme-item">
                                <a href="javascript:void(0)" class="theme-link" data-sidebarbg="skin6"></a>
                            </li>
                        </ul>
                        <!-- Logo BG -->
                    </div>
                </div>
                <!-- End Tab 1 -->
                <!-- Tab 2 -->
                <div class="tab-pane fade" id="chat" role="tabpanel" aria-labelledby="pills-profile-tab">
                    <ul class="mailbox list-style-none mt-3">
                        <li>
                            <div class="message-center chat-scroll">
                                <a href="javascript:void(0)" class="message-item" id='chat_user_1' data-user-id='1'>
                                    <span class="user-img">
                                        <img src="{{ asset('template_assets/assets/images/users/1.jpg') }}" alt="user" class="rounded-circle">
                                        <!-- <img src="../../assets/images/users/1.jpg" alt="user" class="rounded-circle"> -->
                                        <span class="profile-status online pull-right"></span>
                                    </span>
                                    <div class="mail-contnet">
                                        <h5 class="message-title">Pavan kumar</h5>
                                        <span class="mail-desc">Just see the my admin!</span>
                                        <span class="time">9:30 AM</span>
                                    </div>
                                </a>
                                <!-- Message -->
                                <a href="javascript:void(0)" class="message-item" id='chat_user_2' data-user-id='2'>
                                    <span class="user-img">
                                        <img src="{{ asset('template_assets/assets/images/users/2.jpg') }}" alt="user" class="rounded-circle">
                                        <!-- <img src="../../assets/images/users/2.jpg" alt="user" class="rounded-circle"> -->
                                        <span class="profile-status busy pull-right"></span>
                                    </span>
                                    <div class="mail-contnet">
                                        <h5 class="message-title">Sonu Nigam</h5>
                                        <span class="mail-desc">I've sung a song! See you at</span>
                                        <span class="time">9:10 AM</span>
                                    </div>
                                </a>
                                <!-- Message -->
                                <a href="javascript:void(0)" class="message-item" id='chat_user_3' data-user-id='3'>
                                    <span class="user-img">
                                        <img src="{{ asset('template_assets/assets/images/users/3.jpg') }}" alt="user" class="rounded-circle">
                                        <!-- <img src="../../assets/images/users/3.jpg" alt="user" class="rounded-circle"> -->
                                        <span class="profile-status away pull-right"></span>
                                    </span>
                                    <div class="mail-contnet">
                                        <h5 class="message-title">Arijit Sinh</h5>
                                        <span class="mail-desc">I am a singer!</span>
                                        <span class="time">9:08 AM</span>
                                    </div>
                                </a>
                                <!-- Message -->
                                <a href="javascript:void(0)" class="message-item" id='chat_user_4' data-user-id='4'>
                                    <span class="user-img">
                                        <img src="{{ asset('template_assets/assets/images/users/4.jpg') }}" alt="user" class="rounded-circle">
                                        <!-- <img src="../../assets/images/users/4.jpg" alt="user" class="rounded-circle"> -->
                                        <span class="profile-status offline pull-right"></span>
                                    </span>
                                    <div class="mail-contnet">
                                        <h5 class="message-title">Nirav Joshi</h5>
                                        <span class="mail-desc">Just see the my admin!</span>
                                        <span class="time">9:02 AM</span>
                                    </div>
                                </a>
                                <!-- Message -->
                                <!-- Message -->
                                <a href="javascript:void(0)" class="message-item" id='chat_user_5' data-user-id='5'>
                                    <span class="user-img">
                                        <img src="{{ asset('template_assets/assets/images/users/5.jpg') }}" alt="user" class="rounded-circle">
                                        <!-- <img src="../../assets/images/users/5.jpg" alt="user" class="rounded-circle"> -->
                                        <span class="profile-status offline pull-right"></span>
                                    </span>
                                    <div class="mail-contnet">
                                        <h5 class="message-title">Sunil Joshi</h5>
                                        <span class="mail-desc">Just see the my admin!</span>
                                        <span class="time">9:02 AM</span>
                                    </div>
                                </a>
                                <!-- Message -->
                                <!-- Message -->
                                <a href="javascript:void(0)" class="message-item" id='chat_user_6' data-user-id='6'>
                                    <span class="user-img">
                                        <img src="{{ asset('template_assets/assets/images/users/6.jpg') }}" alt="user" class="rounded-circle">
                                        <!-- <img src="../../assets/images/users/6.jpg" alt="user" class="rounded-circle"> -->
                                        <span class="profile-status offline pull-right"></span>
                                    </span>
                                    <div class="mail-contnet">
                                        <h5 class="message-title">Akshay Kumar</h5>
                                        <span class="mail-desc">Just see the my admin!</span>
                                        <span class="time">9:02 AM</span>
                                    </div>
                                </a>
                                <!-- Message -->
                                <!-- Message -->
                                <a href="javascript:void(0)" class="message-item" id='chat_user_7' data-user-id='7'>
                                    <span class="user-img">
                                        <img src="{{ asset('template_assets/assets/images/users/7.jpg') }}" alt="user" class="rounded-circle">
                                        <!-- <img src="../../assets/images/users/7.jpg" alt="user" class="rounded-circle"> -->
                                        <span class="profile-status offline pull-right"></span>
                                    </span>
                                    <div class="mail-contnet">
                                        <h5 class="message-title">Pavan kumar</h5>
                                        <span class="mail-desc">Just see the my admin!</span>
                                        <span class="time">9:02 AM</span>
                                    </div>
                                </a>
                                <!-- Message -->
                                <!-- Message -->
                                <a href="javascript:void(0)" class="message-item" id='chat_user_8' data-user-id='8'>
                                    <span class="user-img">
                                        <img src="{{ asset('template_assets/assets/images/users/8.jpg') }}" alt="user" class="rounded-circle">
                                        <!-- <img src="../../assets/images/users/8.jpg" alt="user" class="rounded-circle"> -->
                                        <span class="profile-status offline pull-right"></span>
                                    </span>
                                    <div class="mail-contnet">
                                        <h5 class="message-title">Varun Dhavan</h5>
                                        <span class="mail-desc">Just see the my admin!</span>
                                        <span class="time">9:02 AM</span>
                                    </div>
                                </a>
                                <!-- Message -->
                            </div>
                        </li>
                    </ul>
                </div>
                <!-- End Tab 2 -->
                <!-- Tab 3 -->
                <div class="tab-pane fade p-3" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                    <h6 class="mt-3 mb-3">Activity Timeline</h6>
                    <div class="steamline">
                        <div class="sl-item">
                            <div class="sl-left bg-success">
                                <i class="ti-user"></i>
                            </div>
                            <div class="sl-right">
                                <div class="font-medium">Meeting today
                                    <span class="sl-date"> 5pm</span>
                                </div>
                                <div class="desc">you can write anything </div>
                            </div>
                        </div>
                        <div class="sl-item">
                            <div class="sl-left bg-info">
                                <i class="fas fa-image"></i>
                            </div>
                            <div class="sl-right">
                                <div class="font-medium">Send documents to Clark</div>
                                <div class="desc">Lorem Ipsum is simply </div>
                            </div>
                        </div>
                        <div class="sl-item">
                            <div class="sl-left">
                                <img class="rounded-circle" alt="user" src="{{ asset('template_assets/assets/images/users/2.jpg') }}">
                            </div>
                            <!-- <img class="rounded-circle" alt="user" src="../../assets/images/users/2.jpg"> </div> -->
                            <div class="sl-right">
                                <div class="font-medium">Go to the Doctor
                                    <span class="sl-date">5 minutes ago</span>
                                </div>
                                <div class="desc">Contrary to popular belief</div>
                            </div>
                        </div>
                        <div class="sl-item">
                            <div class="sl-left">
                                <img class="rounded-circle" alt="user" src="{{ asset('template_assets/assets/images/users/1.jpg') }}">
                            </div>
                            <!-- <img class="rounded-circle" alt="user" src="../../assets/images/users/1.jpg"> </div> -->
                            <div class="sl-right">
                                <div>
                                    <a href="javascript:void(0)">Stephen</a>
                                    <span class="sl-date">5 minutes ago</span>
                                </div>
                                <div class="desc">Approve meeting with tiger</div>
                            </div>
                        </div>
                        <div class="sl-item">
                            <div class="sl-left bg-primary">
                                <i class="ti-user"></i>
                            </div>
                            <div class="sl-right">
                                <div class="font-medium">Meeting today
                                    <span class="sl-date"> 5pm</span>
                                </div>
                                <div class="desc">you can write anything </div>
                            </div>
                        </div>
                        <div class="sl-item">
                            <div class="sl-left bg-info">
                                <i class="fas fa-image"></i>
                            </div>
                            <div class="sl-right">
                                <div class="font-medium">Send documents to Clark</div>
                                <div class="desc">Lorem Ipsum is simply </div>
                            </div>
                        </div>
                        <div class="sl-item">
                            <div class="sl-left">
                                <img class="rounded-circle" alt="user" src="{{ asset('template_assets/assets/images/users/4.jpg') }}">
                            </div>
                            <!-- <img class="rounded-circle" alt="user" src="../../assets/images/users/4.jpg"> </div> -->
                            <div class="sl-right">
                                <div class="font-medium">Go to the Doctor
                                    <span class="sl-date">5 minutes ago</span>
                                </div>
                                <div class="desc">Contrary to popular belief</div>
                            </div>
                        </div>
                        <div class="sl-item">
                            <div class="sl-left">
                                <img class="rounded-circle" alt="user" src="{{ asset('template_assets/assets/images/users/6.jpg') }}">
                            </div>
                            <!-- <img class="rounded-circle" alt="user" src="../../assets/images/users/6.jpg"> </div> -->
                            <div class="sl-right">
                                <div>
                                    <a href="javascript:void(0)">Stephen</a>
                                    <span class="sl-date">5 minutes ago</span>
                                </div>
                                <div class="desc">Approve meeting with tiger</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Tab 3 -->
            </div>
        </div>
    </aside>
    <div class="chat-windows"></div>

    @if( ( Auth::user()->roleId == Config::get('constants.RETAILER')) || ( Auth::user()->roleId == Config::get('constants.DISTRIBUTOR')) || ( Auth::user()->roleId == Config::get('constants.MASTER_DISTRIBUTOR')) )
    <!-- KYC Modal starts -->
    <div class="modal fade" id="KycMsgModal">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <!-- <h4 class="modal-title">KYC </h4> -->
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <center>
                        <h3>Kindly Update Your KYC by Using SMARTPAY 2.0 Android APP</h3>


                    </center>

                </div>
                <div class="modal-footer" style="justify-content: center;">

                    <button type="button" class="btn btn-secondary btn-lg success-grad" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
    <!-- KYC Modal ends -->


    <!-- QR modal starts -->
    <div class="modal" id="balanceReqQRModal" tabindex="-1" role="dialog" aria-labelledby="balanceReqModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel1"><span class="modal-action-name"></span> Scan QR Code and Pay</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>

                <div class="modal-body">
                    <div class="col-12">
                        @php
                        $qr_id = DB::table('tbl_users')->where('userId', Auth::user()->userId)->pluck('qr_id')->first();
                        @endphp
                        @if (isset($qr_id) && $qr_id != "")
                        <div class="col-12 mt-2  text-center hide-this" id="qr-code-div">
                            <!--<h4>Scan QR Code</h4>-->
                            <img src="https://paymamaapp.in/qr/{{$qr_id}}" alt="QR Code" style="width:80%">

                        </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="https://paymamaapp.in/qr/{{$qr_id}}" download="paymmama_qr_code" class="btn btn-primary submit-btn">Download</a>
                    <button type="button" class="btn btn-default submit-btn" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Balance Request Add modal ends -->

    <!-- Balance Request Add modal starts -->
    <div class="modal" id="balanceReqQRModal1" tabindex="-1" role="dialog" aria-labelledby="balanceReqModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel1"><span class="modal-action-name"></span> Request Balance</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form method="post" action="{{ route('send_balance_request') }}" id="addBalanceReqForm">
                    @csrf
                    <div class="modal-body">
                        <div class="col-12">


                            <input type="hidden" name="id" id="id">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="amount">QR Code</label>
                                        <input type="text" class="form-control" id="bank" name="bank" value="QR_CODE" readonly>
                                    </div>
                                </div>


                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="amount">Amount</label>
                                        <input type="number" class="form-control" id="amount" name="amount">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="reference_id">Ref. Id</label>
                                        <input type="text" class="form-control" id="reference_id" name="reference_id">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="message">Remark</label>
                                        <input type="text" class="form-control" id="message" name="message">
                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                @php
                                $qrCodeFilePath = "";
                                $qrCodeResponse = DB::table('tbl_files')->where('name', 'qr_code')->first();
                                if (isset($qrCodeResponse) && $qrCodeResponse->file_path) {
                                $qrCodeFilePath = $qrCodeResponse->file_path;
                                }

                                @endphp
                                <div class="col-12 mt-2  text-center hide-this" id="qr-code-div">
                                    <h4>Scan QR Code</h4>
                                    <img src="{{ $qrCodeFilePath }}" alt="QR Code" style="width:60%">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary submit-btn">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Balance Request Add modal ends -->
<!-- PG Wallet Modal starts -->
    <div class="modal fade" id="pgModal">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <!-- <h4 class="modal-title">Transfer</h4> --> 
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <center>

                        <a href="{{ route('pg-wallet-wallet') }}" class="btn btn-primary btn-lg success-grad" id="pg_wallet_transfer">Wallet Transfer</a>
                        <a href="{{ route('pg-wallet-bank-transfer') }}" class="btn btn-primary btn-lg success-grad" id="bank_transfer">Bank Transfer</a>
                        

                    </center>

                </div>
                <div class="modal-footer" style="justify-content: center;">

                  <!--   <button type="button" class="btn btn-secondary btn-lg success-grad" data-dismiss="modal">Cancel</button>  -->
                </div>
            </div>
        </div>
    </div>
    <!-- PG Wallet Modal ends -->
    
     <!-- PG Wallet to Wallet Transer modal starts -->
    <div class="modal" id="pgWalletModal" role="dialog">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel1" style="margin-left:60px">Wallet Transfer</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="col-12">


                    <form id="chgPwdForm" action="" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group text-center" style="margin-top:20px">
                                <input type="text" class="form-control text-center" name="amount" id="profile_password" placeholder="Enter Amount" autocomplete="off">
                            </div>

                            <div class="form-group text-center" style="margin-top:20px">
                            <input type="text text-center" class="form-control text-center" name="mpin" id="new-mpin" placeholder="Enter New Mpin" autocomplete="off">
                        </div>
                            </div>

                            <div class="form-group text-center hide-this" id="pwd-otp-verify-div" style="margin-top:20px">
                                <input type="number" class="form-control text-center" name="verification_otp" id="pwd_verification_otp" placeholder="Enter 6 digit OTP you received." autocomplete="off">
                            </div>
                        </div>
                        <div class="modal-footer" style="justify-content: center;">

                            <button type="submit" class="btn btn-secondary btn-lg success-grad btn-block" id="update-pwd-btn">Update</button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
    <!-- PG Wallet to Wallet Transer modal ends -->
    
    <!-- PG Wallet to Bank Transer modal starts -->
    <div class="modal" id="pgBankModal" role="dialog">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel1" style="margin-left:60px">Bank Transfer</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="col-12">


                    <form id="chgPwdForm" action="" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group text-center" style="margin-top:20px">
                                <input type="text" class="form-control text-center" name="amount" id="profile_password" placeholder="Enter Amount" autocomplete="off">
                            </div>
                            
                            <div class="form-group text-center" style="margin-top:20px">
                                <select type="text" class="form-control text-center" name="bank" id="profile_password" placeholder="Enter Amount" autocomplete="off"> 
                                <option> Select Bank</option>
                                </select>
                            </div>
                        
                            <div class="form-group text-center" style="margin-top:20px">
                            <input type="text text-center" class="form-control text-center" name="mpin" id="new-mpin" placeholder="Enter New Mpin" autocomplete="off">
                        </div>
                            </div>

                            <div class="form-group text-center hide-this" id="pwd-otp-verify-div" style="margin-top:20px">
                                <input type="number" class="form-control text-center" name="verification_otp" id="pwd_verification_otp" placeholder="Enter 6 digit OTP you received." autocomplete="off">
                            </div>
                        </div>
                        <div class="modal-footer" style="justify-content: center;">

                            <button type="submit" class="btn btn-secondary btn-lg success-grad btn-block" id="update-pwd-btn">Update</button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
    <!-- PG Wallet to Bank Transer modal ends -->
    
    <!-- Security Modal starts -->
    <div class="modal fade" id="securityModal" style="margin-left:42%;width:23%;">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <!-- <h4 class="modal-title">KYC </h4> -->
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                       <center>


                        <button type="button" class="btn btn-secondary btn-lg success-grad" id="change_mpin" style="background:green;color:white;width:180px;">Change MPIN</button>

                    </center>
                    <br>
                    <center>

                        <button type="button" class="btn btn-primary btn-lg success-grad" id="change_pwd"  style="background:green;color:white;">Change Password</button>
                        
                    </center>
                 

                </div>
                <div class="modal-footer" style="">

                    <button type="button" class="btn btn-secondary btn-lg success-grad pull-right" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Security Modal ends -->
    
    

    <!-- Change Password modal starts -->
    <div class="modal" id="chgPwdModal" role="dialog">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel1" style="margin-left:60px">Change Password</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="col-12">


                    <form id="chgPwdForm" action="{{ route('change_user_Pwd') }}" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group text-center" style="margin-top:20px">
                                <input type="password" class="form-control text-center" name="password" id="profile_password" placeholder="Enter New Password" autocomplete="off">
                            </div>

                            <div class="form-group text-center" style="margin-top:20px">
                                <input type="password" class="form-control text-center" name="password_confirmation" id="profile_password_confirmation" placeholder="Confirm Password" autocomplete="off">
                                <label id="password_confirmation-error" class="error hide-this text-danger" for="password_confirmation">This field is required.</label>
                            </div>

                            <div class="form-group text-center hide-this" id="pwd-otp-verify-div" style="margin-top:20px">
                                <input type="number" class="form-control text-center" name="verification_otp" id="pwd_verification_otp" placeholder="Enter 6 digit OTP you received." autocomplete="off">
                            </div>
                        </div>
                        <div class="modal-footer" style="justify-content: center;">

                            <button type="submit" class="btn btn-secondary btn-lg success-grad btn-block" id="update-pwd-btn">Update</button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
    <!-- Change Password modal ends -->

    <!-- Change Mpin modal starts -->
    <div class="modal" id="chgMpinModal" role="dialog">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel1" style="margin-left:60px">Change Mpin</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="chgMpinForm" action="{{ route('change_user_mpin') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group text-center" style="margin-top:20px">
                            <input type="text text-center" class="form-control text-center" name="mpin" id="new-mpin" placeholder="Enter New Mpin" autocomplete="off">
                        </div>

                        <div class="form-group text-center hide-this" id="mpin-otp-verify-div" style="margin-top:20px">
                            <input type="number" class="form-control text-center" name="verification_otp" id="mpin_verification_otp" placeholder="Enter 6 digit OTP you received." autocomplete="off">
                        </div>
                    </div>
                    <div class="modal-footer" style="justify-content: center;">

                        <button type="submit" class="btn btn-secondary btn-lg success-grad btn-block" id="update-mpin-btn">Update</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <!-- Change Mpin modal ends -->

    <!-- Parent info Modal starts -->
    <div class="modal fade" id="parentInfoModal">
        <!-- <div class="modal-dialog modal-xl modal-dialog-centered"> -->
        <div class="modal-dialog modal-xl ">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <!-- <h4 class="modal-title">KYC </h4> -->
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <style>
                    .red_right_border {
                        border-right: 1.5px solid #dc182d !important;
                    }

                    .parent_title {
                        color: #262262;
                        font-weight: 600;
                        font-size: 1.9rem;
                    }

                    .parent_lable {
                        color: #bf1e2e;
                        font-weight: 600;
                        font-size: 1.5rem;
                    }

                    .parent_value {
                        color: #262262;
                        font-weight: 600;
                        font-size: 1.5rem;
                    }
                </style>
                <!-- Modal body -->
                <div class="modal-body">

                    <div class="col-12">
                        <div class="row" id="userparent_info">

                        </div>
                    </div>

                </div>
                <div class="modal-footer" style="justify-content: center;">


                </div>
            </div>
        </div>
    </div>
    <!-- Parent info Modal ends -->

    <!-- certificate  Modal starts -->
    <div class="modal fade" id="userCertificateModal" style="background:white">
        <!-- <div class="modal-dialog modal-xl modal-dialog-centered"> -->
        <div class="modal-dialog " style="max-width:80%">
            <div class="modal-content">


                <style>
                    @media print {
                        body * {
                            visibility: hidden;
                        }

                        #section-to-print,
                        #section-to-print * {
                            visibility: visible;
                        }

                        #section-to-print {
                            position: absolute;
                            left: 0;
                            top: 0;
                            /* transform: rotate(90deg); */

                        }
                    }
                </style>
                <!-- Modal body -->
                <div class="modal-body">

                    <button type="button" class="btn btn-lg success-grad" id="print-certificate-btn">Print</button><button type="button" class="btn btn-lg success-grad" id="download-certificate-btn">Download</button>
                    <div class="col-12">
                        <div class="row" id="section-to-print">
                            <center>
                                <img src="{{ asset('/template_assets/assets/images/certificate.jpeg') }}" alt="" style="width:89%">
                            </center>
                        </div>
                    </div>



                </div>

            </div>
        </div>
    </div>
    
    <!-- certificate  Modal ends -->
    <!-- ============================================================== -->
    <!-- footer -->
    <!-- ============================================================== -->
    <footer class="footer text-center text-dark">
        Copyright ©️ 2021, PayMama Powered by Naidu Software Technologies Private Limited. All Rights Reserved.
        <!-- <a href="https://wrappixel.com">WrapPixel</a>. -->
    </footer>
    <!-- ============================================================== -->
    <!-- End footer -->
    <!-- ============================================================== -->

    <script type="text/javascript">
        $(document).ready(function() {
            $('#myModal').appendTo("body");
            $('.logo-carousel').slick({
                slidesToShow: 3,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 1000,
                arrows: false,
                dots: false,
                pauseOnHover: false,
                responsive: [{
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 4
                    }
                }, {
                    breakpoint: 520,
                    settings: {
                        slidesToShow: 2
                    }
                }]
            });
        });
    </script>




    <!-- Bootstrap tether Core JavaScript -->
    <script src="{{ asset('template_new/assets/libs/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ asset('template_new/assets/libs/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- apps -->
    <script src="{{ asset('template_new/dist/js/app.min.js') }}"></script>
    <script src="{{ asset('template_new/dist/js/app.init.js') }}"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="{{ asset('template_new/assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js') }}"></script>
    <script src="{{ asset('template_new/assets/extra-libs/sparkline/sparkline.js') }}"></script>
    <!--Wave Effects -->
    <script src="{{ asset('template_new/dist/js/waves.js') }}"></script>
    <!--Menu sidebar -->
    <script src="{{ asset('template_new/dist/js/sidebarmenu.js') }}"></script>
    <!--Custom JavaScript -->
    <script src="{{ asset('template_new/dist/js/custom.min.js') }}"></script>
    <!-- This Page JS -->
    <script src="{{ asset('template_new/assets/libs/chartist/dist/chartist.min.js') }}"></script>
    <script src="{{ asset('template_new/dist/js/pages/chartist/chartist-plugin-tooltip.js') }}"></script>
    <script src="{{ asset('template_new/assets/extra-libs/c3/d3.min.js') }}"></script>
    <script src="{{ asset('template_new/assets/extra-libs/c3/c3.min.js') }}"></script>
    <script src="{{ asset('template_new/assets/libs/raphael/raphael.min.js') }}"></script>
    <script src="{{ asset('template_new/assets/libs/morris.js/morris.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('template_new/dist/js/pages/dashboards/dashboard1.js') }}"></script>
    <script src="{{ asset('template_new/assets/libs/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('template_new/assets/libs/fullcalendar/dist/fullcalendar.min.js') }}"></script>
    <script src="{{ asset('template_new/dist/js/pages/calendar/cal-init.js') }}"></script>
    <script>
        $('#calendar').fullCalendar('option', 'height', 650);
    </script>
    <script src="{{ asset('dist\shared\js\full.js') }}"></script>
    <script src="{{ asset('dist\shared\js\fullPageValidation.js') }}"></script>
    @else
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- <script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script> -->
    <!-- ============================================================== -->
    <!-- <script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script> -->
    <!-- <script src="../../assets/libs/jquery/dist/jquery.min.js"></script> -->
    <!-- Bootstrap tether Core JavaScript -->
    <script src="{{ asset('template_assets/assets/libs/popper.js/dist/umd/popper.min.js') }}"></script>
    <!-- <script src="../../assets/libs/popper.js/dist/umd/popper.min.js"></script> -->
    <script src="{{ asset('template_assets/assets/libs/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- <script src="../../assets/libs/bootstrap/dist/js/bootstrap.min.js"></script> -->
    <!-- apps -->
    <script src="{{ asset('template_assets/dist/js/app.min.js') }}"></script>
    <!-- <script src="../../dist/js/app.min.js"></script> -->
    <script src="{{ asset('template_assets/dist/js/app.init.mini-sidebar.js') }}"></script>
    <!-- <script src="../../dist/js/app.init.mini-sidebar.js"></script> -->
    <script src="{{ asset('template_assets/dist/js/app-style-switcher.js') }}"></script>
    <!-- <script src="../../dist/js/app-style-switcher.js"></script> -->
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="{{ asset('template_assets/assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js') }}"></script>
    <!-- <script src="../../assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script> -->
    <script src="{{ asset('template_assets/assets/extra-libs/sparkline/sparkline.js') }}"></script>
    <!-- <script src="../../assets/extra-libs/sparkline/sparkline.js"></script> -->
    <!--Wave Effects -->
    <script src="{{ asset('template_assets/dist/js/waves.js') }}"></script>
    <!-- <script src="../../dist/js/waves.js"></script> -->
    <!--Menu sidebar -->
    <script src="{{ asset('template_assets/dist/js/sidebarmenu.js') }}"></script>
    <!-- <script src="../../dist/js/sidebarmenu.js"></script> -->
    <!--Custom JavaScript -->
    <script src="{{ asset('template_assets/dist/js/custom.min.js') }}"></script>
    <!-- <script src="../../dist/js/custom.min.js"></script> -->

    <!-- This Page JS -->
    <script src="{{ asset('template_assets/assets/libs/chartist/dist/chartist.min.js') }}"></script>
    <script src="{{ asset('template_assets/dist/js/pages/chartist/chartist-plugin-tooltip.js') }}"></script>
    <!-- The link has been commented here and added to the individual page -->

    <script src="{{ asset('template_assets/assets/extra-libs/c3/d3.min.js') }}"></script>
    <!-- <script src="../../assets/extra-libs/c3/d3.min.js"></script> -->
    <script src="{{ asset('template_assets/assets/extra-libs/c3/c3.min.js') }}"></script>
    <!-- <script src="../../assets/extra-libs/c3/c3.min.js"></script> -->
    <script src="{{ asset('template_assets/assets/libs/raphael/raphael.min.js') }}"></script>
    <!-- <script src="../../assets/libs/raphael/raphael.min.js"></script> -->
    <script src="{{ asset('template_assets/assets/libs/morris.js/morris.min.js') }}"></script>
    <!-- <script src="../../assets/libs/morris.js/morris.min.js"></script> -->
    <!-- <script src="{{ asset('template_assets/dist/js/pages/dashboards/dashboard1.js') }}"></script> -->
    <!-- <script src="../../dist/js/pages/dashboards/dashboard1.js"></script> -->
    <script src="{{ asset('template_assets/assets/libs/moment/min/moment.min.js') }}"></script>
    <!-- <script src="../../assets/libs/moment/min/moment.min.js"></script> -->
    <script src="{{ asset('template_assets/assets/libs/fullcalendar/dist/fullcalendar.min.js') }}"></script>
    <!-- <script src="../../assets/libs/fullcalendar/dist/fullcalendar.min.js"></script> -->
    <script src="{{ asset('template_assets/dist/js/pages/calendar/cal-init.js') }}"></script>
    <!-- <script src="../../dist/js/pages/calendar/cal-init.js"></script> -->
    <!-- Toaster  -->
    <script src="{{ asset('template_assets\other\js\toastr.min.js') }}"></script>
    <script src="{{ asset('dist\shared\js\full.js') }}"></script>
    <script src="{{ asset('dist\shared\js\fullPageValidation.js') }}"></script>
    <script>
        $('#calendar').fullCalendar('option', 'height', 650);
    </script>
    @endif
</body>
</html>