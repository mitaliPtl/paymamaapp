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
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('template_assets/assets/images/favicon_sm_py.png') }}">
    <title>SMARTPAY - Making India Digital</title>
	<link rel="canonical" href="https://www.wrappixel.com/templates/ampleadmin/" />


    <!-- chartist CSS -->
    <link href="{{ asset('template_assets/assets/libs/chartist/dist/chartist.min.css') }}" rel="stylesheet">
    <link href="{{ asset('template_assets/assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.css') }}" rel="stylesheet">
    <!-- The link has been commented here and added to the individual page -->

    <!--c3 CSS -->
    <!-- <link href="../../assets/libs/morris.js/morris.css" rel="stylesheet"> -->
    <link href="{{ asset('template_assets/assets/libs/morris.js/morris.css') }}" rel="stylesheet">
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

    <!-- new add -->
    <link href="{{ asset('template_new/dist/css/infinite-slider.css') }}" rel="stylesheet">
</head>

<body>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
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
                        <span class="logo-text" style="padding:0px;">
                             <!-- dark Logo text -->
                             <!-- <img src="../../assets/images/logos/logo-text.png" alt="homepage" class="dark-logo" /> -->
                              <img src="{{ asset('template_assets/paymamma.PNG') }}" alt="homepage" class="dark-logo" style="width: 214px;
    height: 71px;
    margin-left: 25px;"/>
                            <!-- Light Logo icon -->
                            <img src="{{ asset('template_assets/paymamma.PNG') }}" alt="homepage" class="light-logo" style="width: 214px;
    height: 71px;
    margin-left: 25px;">
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
                        @if( Auth::user()->roleId == Config::get('constants.DISTRIBUTOR') || Auth::user()->roleId == Config::get('constants.RETAILER'))
                        <!-- Wallet starts -->
                        <li class="nav-item">
                            <div class="btn-group" style="margin-top:12px">
                                <button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="mdi mdi-wallet"></i> Load Wallet
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('online_payment') }}"><i class="mdi mdi-monitor"></i> Online Palyment</a>
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
                                <span class="hide-menu">Dashboard</span>
                            </a>
                        </li> 

                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <i class="mdi mdi-cart-outline"></i>
                                <span class="hide-menu">Control Panel</span>
                            </a>

                            <ul aria-expanded="false" class="collapse first-level">

                                <li class="sidebar-item">
                                    <a class="has-arrow sidebar-link" href="javascript:void(0)" aria-expanded="false">
                                        <i class="mdi mdi-email-open-outline"></i>
                                        <span class="hide-menu">API Management</span>
                                    </a>
                                    <ul aria-expanded="false" class="collapse second-level">
                                        
                                        <li class="sidebar-item">
                                            <a href="{{ route('service_type') }}" class="sidebar-link" aria-expanded="false">
                                                <i class=""></i>
                                                <span class="hide-menu">Services</span>
                                            </a>
                                        </li><li class="sidebar-item">
                                            <a href="{{ route('api_setting') }}" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu">API Portal</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a  href="{{ route('operator') }}"  class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu">Operators</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a  href="{{ route('operator_dtls') }}" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu">API Operator Setting</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('operator_settings') }}" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu">Transfer Recharge API</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a  href="{{ route('api_amount_details') }}" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu">Transfer API by Amount</span>
                                            </a>
                                        </li>
                                        
                                    </ul>
                                </li>
                                
                           
                                
                                <li class="sidebar-item">
                                    <a class="sidebar-link waves-effect waves-dark" href="{{ route('bbps_management') }}" aria-expanded="false">
                                        <i class="mdi mdi-account-outline"></i>
                                        <span class="hide-menu">BBPS Management</span>
                                    </a>
                                </li>
                                <!-- <li class="sidebar-item">
                                    <a class="sidebar-link waves-effect waves-dark" href="{{ route('operator_settings') }}" aria-expanded="false">
                                        <i class="mdi mdi-account-outline"></i>
                                        <span class="hide-menu">Operator Setting</span>
                                    </a>
                                </li> -->

                               

                                <li class="sidebar-item">
                                    <a class="has-arrow sidebar-link" href="javascript:void(0)" aria-expanded="false">
                                        <i class="mdi mdi-email-open-outline"></i>
                                        <span class="hide-menu">DMT Management </span>
                                    </a>
                                    <ul aria-expanded="false" class="collapse second-level">
                                        
                                        <li class="sidebar-item">
                                            <a href="{{ route('bank_list') }}" class="sidebar-link" aria-expanded="false">
                                                <i class=""></i>
                                                <span class="hide-menu">Bank List</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('dmt_margin') }}" class="sidebar-link" aria-expanded="false">
                                                <i class=""></i>
                                                <span class="hide-menu">DMT Margin</span>
                                            </a>
                                        </li>
                                       
                                        
                                    </ul>
                                </li>

                                <li class="sidebar-item">
                                    <a class="has-arrow sidebar-link" href="javascript:void(0)" aria-expanded="false">
                                        <i class="mdi mdi-email-open-outline"></i>
                                        <span class="hide-menu">Margin  Management</span>
                                    </a>
                                    <ul aria-expanded="false" class="collapse second-level">
                                        
                                        <li class="sidebar-item">
                                            <a href="{{ route('package_setting') }}" class="sidebar-link" aria-expanded="false">
                                                <i class=""></i>
                                                <span class="hide-menu">Package Set</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('pack_comm_dtls') }}" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu">Margin Set</span>
                                            </a>
                                        </li>
                                       
                                        
                                    </ul>
                                </li>

                                <li class="sidebar-item">
                                    <a class="has-arrow sidebar-link" href="javascript:void(0)" aria-expanded="false">
                                        <i class="mdi mdi-email-open-outline"></i>
                                        <span class="hide-menu">PG  Management</span>
                                    </a>
                                    <ul aria-expanded="false" class="collapse second-level">
                                        
                                        <li class="sidebar-item">
                                            <a href="{{ route('charges_setting') }}" class="sidebar-link" aria-expanded="false">
                                                <i class=""></i>
                                                <span class="hide-menu">PG Charge Set</span>
                                            </a>
                                        </li>
                                       
                                        
                                    </ul>
                                </li>
                                
                             
                                
                                
                                <!-- 19-11-20 -->
                                <!-- <li class="sidebar-item">
                                    <a class="sidebar-link waves-effect waves-dark" href="{{ route('pay_gate_setting') }}" aria-expanded="false">
                                        <i class="mdi mdi-av-timer"></i>
                                        <span class="hide-menu">Payment Gateway Setting</span>
                                    </a>
                                </li> -->
                                <!-- 19-11-20 -->
                                <!-- <li class="sidebar-item">
                                    <a class="sidebar-link waves-effect waves-dark" href="{{ route('sms_gate_setting') }}" aria-expanded="false">
                                        <i class="mdi mdi-av-timer"></i>
                                        <span class="hide-menu">SMS Setting</span>
                                    </a>
                                </li> -->
                                <!-- 19-11-20 -->
                                <!-- <li class="sidebar-item">
                                    <a class="sidebar-link waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                        <i class="mdi mdi-av-timer"></i>
                                        <span class="hide-menu">Sub Admin</span>
                                    </a>
                                </li> -->
                                <li class="sidebar-item">
                                    <a href="{{ route('general_setting') }}" class="sidebar-link">
                                        <i class="mdi mdi-bulletin-board"></i>
                                        <span class="hide-menu">General Setting</span>
                                    </a>
                                </li>

                                <li class="sidebar-item">
                                    <a class="sidebar-link active" href="{{ route('day_book') }}" aria-expanded="false">
                                        <i class="mdi mdi-clipboard-text"></i>
                                        <span class="hide-menu">Day Book</span>
                                    </a>
                                </li>

                               
                            </ul>
                        </li>

                       

                        <!-- Transaction Reports Starts -->
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <i class="mdi mdi-format-color-fill"></i>
                                <span class="hide-menu">Reports Management </span>
                                <!-- <span class="badge badge-info badge-pill ml-auto mr-3 font-medium px-2 py-1">4</span> -->
                            </a>
                                <ul aria-expanded="false" class="collapse first-level">
                                    <li class="sidebar-item">
                                        <a class="has-arrow sidebar-link" href="javascript:void(0)" aria-expanded="false">
                                            <i class="mdi mdi-email-open-outline"></i>
                                            <span class="hide-menu">Transaction Report</span>
                                        </a>
                                        <ul aria-expanded="false" class="collapse second-level">
                                            
                                            <li class="sidebar-item">
                                                <a  href="{{ route('transaction_report',['service_type'=>'RECHARGE']) }}" class="sidebar-link" aria-expanded="false">
                                                    <i class=""></i>
                                                    <span class="hide-menu">Recharge Reports</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-item">
                                                <a href="{{ route('transaction_report',['service_type'=>'BILL_PAYMENTS']) }}" class="sidebar-link" aria-expanded="false">
                                                    <i class="mdi mdi-message-bulleted-off"></i>
                                                    <span class="hide-menu">Bill Payment Report</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-item">
                                                <a  href="{{ route('transaction_report',['service_type'=>'MONEY_TRANSFER']) }}"  class="sidebar-link" aria-expanded="false">
                                                    <i class="mdi mdi-message-bulleted-off"></i>
                                                    <span class="hide-menu">DMT Report</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-item">
                                                <a  href="{{ route('transaction_report',['service_type'=>'UPI_TRANSFER']) }}" class="sidebar-link" aria-expanded="false">
                                                    <i class="mdi mdi-message-bulleted-off"></i>
                                                    <span class="hide-menu">BHIM UPI Report</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-item">
                                                <a  href="{{ route('transaction_report',['service_type'=>'AEPS']) }}" class="sidebar-link" aria-expanded="false">
                                                    <i class="mdi mdi-message-bulleted-off"></i>
                                                    <span class="hide-menu">AEPS Report</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-item">
                                                <a  href="{{ route('transaction_report',['service_type'=>'AADHAR_PAY']) }}" class="sidebar-link" aria-expanded="false">
                                                    <i class="mdi mdi-message-bulleted-off"></i>
                                                    <span class="hide-menu">Aadhar Pay Report</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-item">
                                                <a  href="{{ route('transaction_report',['service_type'=>'ICICI_CASH_DEPOSIT']) }}" class="sidebar-link" aria-expanded="false">
                                                    <i class="mdi mdi-message-bulleted-off"></i>
                                                    <span class="hide-menu">ICICI Cash Deposit Report</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="sidebar-item">
                                        <a class="has-arrow sidebar-link" href="javascript:void(0)" aria-expanded="false">
                                            <i class="mdi mdi-email-open-outline"></i>
                                            <span class="hide-menu">Commission Reports</span>
                                        </a>
                                        <ul aria-expanded="false" class="collapse second-level">
                                            
                                            <li class="sidebar-item">
                                                <a  href="{{ route('commission_report',['service_type'=>'RECHARGE']) }}" class="sidebar-link" aria-expanded="false">
                                                    <i class=""></i>
                                                    <span class="hide-menu">Recharge Reports</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-item">
                                                <a href="{{ route('commission_report',['service_type'=>'BILL_PAYMENTS']) }}" class="sidebar-link" aria-expanded="false">
                                                    <i class="mdi mdi-message-bulleted-off"></i>
                                                    <span class="hide-menu">Bill Payment Report</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-item">
                                                <a href="{{ route('commission_report',['service_type'=>'MONEY_TRANSFER']) }}"   class="sidebar-link" aria-expanded="false">
                                                    <i class="mdi mdi-message-bulleted-off"></i>
                                                    <span class="hide-menu">DMT Report</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-item">
                                                <a  href="{{ route('commission_report',['service_type'=>'UPI_TRANSFER']) }}" class="sidebar-link" aria-expanded="false">
                                                    <i class="mdi mdi-message-bulleted-off"></i>
                                                    <span class="hide-menu">BHIM UPI Report</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>

                                    <li class="sidebar-item">
                                        <a class="has-arrow sidebar-link" href="javascript:void(0)" aria-expanded="false">
                                            <i class="mdi mdi-email-open-outline"></i>
                                            <span class="hide-menu">SmartPay Reports</span>
                                        </a>
                                        <ul aria-expanded="false" class="collapse second-level">
                                            
                                            <li class="sidebar-item">
                                                <a  href="{{ route('admin-home') }}" class="sidebar-link" aria-expanded="false">
                                                    <i class=""></i>
                                                    <span class="hide-menu">Admin Transfer Reports</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-item">
                                                <a href="{{ route('admin-home') }}" class="sidebar-link" aria-expanded="false">
                                                    <i class="mdi mdi-message-bulleted-off"></i>
                                                    <span class="hide-menu">Admin Credit Report</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-item">
                                                <a href="{{ route('admin-home') }}"   class="sidebar-link" aria-expanded="false">
                                                    <i class="mdi mdi-message-bulleted-off"></i>
                                                    <span class="hide-menu">Admin Wallet Report</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-item">
                                                <a  href="{{ route('admin-home') }}"  class="sidebar-link" aria-expanded="false">
                                                    <i class="mdi mdi-message-bulleted-off"></i>
                                                    <span class="hide-menu">Member Wallet Reprot</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-item">
                                                <a  href="{{ route('admin-home') }}"  class="sidebar-link" aria-expanded="false">
                                                    <i class="mdi mdi-message-bulleted-off"></i>
                                                    <span class="hide-menu">Panel Analysis</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-item">
                                                <a href="{{ route('all_transfer') }}" class="sidebar-link">
                                                    <i class="mdi mdi-view-day"></i>
                                                    <span class="hide-menu">All Transfer</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-item">
                                                <a href="{{ route('all_transfer', ['trans'=>'ADMIN']) }}" class="sidebar-link">
                                                    <i class="mdi mdi-view-day"></i>
                                                    <span class="hide-menu">Admin Transfer</span>
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
                                    <span class="hide-menu">Member Management </span>
                                    <span class="badge badge-info badge-pill ml-auto mr-3 font-medium px-2 py-1">4</span>
                                </a>
                                <ul aria-expanded="false" class="collapse first-level">
                                    <li class="sidebar-item">
                                        <a href="{{ route('create_member') }}" class="sidebar-link">
                                            <i class="mdi mdi-comment-processing-outline"></i>
                                            <span class="hide-menu">Member Registration</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a class="sidebar-link active" href="{{ route('user_list') }}" aria-expanded="false">
                                            <i class="mdi mdi-clipboard-text"></i>
                                            <span class="hide-menu">Member List</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a class="sidebar-link active" href="{{ route('user_list') }}" aria-expanded="false">
                                            <i class="mdi mdi-clipboard-text"></i>
                                            <span class="hide-menu">Member Certificate</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a class="sidebar-link active" href="{{ route('create_subadmin') }}" aria-expanded="false">
                                            <i class="mdi mdi-clipboard-text"></i>
                                            <span class="hide-menu">Sub Admin Registration</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a class="sidebar-link active" href="{{ route('user_list') }}" aria-expanded="false">
                                            <i class="mdi mdi-clipboard-text"></i>
                                            <span class="hide-menu">Sub Admin Permission</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!-- MEMBER MANAGEMENT end -->

                            <!-- FOUND MANAGEMENT START -->
                            <li class="sidebar-item">
                                <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                    <i class="mdi mdi-tune-vertical"></i>
                                    <span class="hide-menu">Fund Management </span>
                                </a>
                                <ul aria-expanded="false" class="collapse  first-level">
                                    <li class="sidebar-item">
                                        <a href="{{ route('bank_account') }}" class="sidebar-link active">
                                            <i class="mdi mdi-view-quilt"></i>
                                            <span class="hide-menu">Bank Account</span>
                                        </a>
                                    </li>
                                
                                    <li class="sidebar-item">
                                        <a href="{{ route('balance_request') }}" class="sidebar-link">
                                            <i class="mdi mdi-view-day"></i>
                                            <span class="hide-menu">Balance Request Report</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="{{ route('user_payment_gateway_report') }}" class="sidebar-link">
                                            <i class="mdi mdi-view-day"></i>
                                            <span class="hide-menu">Payment Gateway Report</span>
                                        </a>
                                    </li>
                                    
                                    <li class="sidebar-item">
                                        <a href="{{ route('user_virtual_account_report') }}" class="sidebar-link">
                                            <i class="mdi mdi-view-day"></i>
                                            <span class="hide-menu">Virtual Account Report</span>
                                        </a>
                                    </li>
                                    
                                    <li class="sidebar-item">
                                        <a href="{{ route('transfer_revert_balance') }}" class="sidebar-link">
                                            <i class="mdi mdi-view-day"></i>
                                            <span class="hide-menu">Fund Transfer/Revert </span>
                                        </a>
                                    </li>
                                    

                                </ul>
                            </li>
                            <!-- FOUND MANAGEMENT END -->

                            <!-- Portal Management strat -->
                            <li class="sidebar-item">
                                <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                    <i class="mdi mdi-apps"></i>
                                    <span class="hide-menu">Portal Management</span>
                                </a>
                                <ul aria-expanded="false" class="collapse first-level">
                                    <li class="sidebar-item">
                                        <a href="starter-kit.html" class="sidebar-link">
                                            <i class="mdi mdi-crop-free"></i>
                                            <span class="hide-menu">Android App </span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="starter-kit.html" class="sidebar-link">
                                            <i class="mdi mdi-crop-free"></i>
                                            <span class="hide-menu">Website</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="starter-kit.html" class="sidebar-link">
                                            <i class="mdi mdi-crop-free"></i>
                                            <span class="hide-menu">Login Panel</span>
                                        </a>
                                    </li>

                                    
                                    <li class="sidebar-item">
                                        <a  href="{{ route('operator') }}"  class="sidebar-link">
                                            <i class="mdi mdi-crop-free"></i>
                                            <span class="hide-menu">Operator Helpline</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a class="has-arrow sidebar-link" href="javascript:void(0)" aria-expanded="false">
                                            <i class="mdi mdi-email-open-outline"></i>
                                            <span class="hide-menu">Complaint Manage</span>
                                        </a>
                                        <ul aria-expanded="false" class="collapse second-level">
                                            
                                            <li class="sidebar-item">
                                                <a  href="{{ route('complaints',['service_type'=>'COMPLAINT']) }}" class="sidebar-link" aria-expanded="false">
                                                    <i class=""></i>
                                                    <span class="hide-menu">Compalint List</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-item">
                                                <a href="{{ route('template') }}" class="sidebar-link" aria-expanded="false">
                                                    <i class="mdi mdi-message-bulleted-off"></i>
                                                    <span class="hide-menu">Template</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                   
                                    <li class="sidebar-item">
                                        <a class="sidebar-link waves-effect waves-dark" href="{{ route('slidder_banner') }}" aria-expanded="false">
                                            <i class="mdi mdi-av-timer"></i>
                                            <span class="hide-menu">Slidder Banner Settings</span>
                                        </a>
                                    </li>
                                   
                                </ul>
                            </li>
                            <!-- portal Management end -->

                            <!-- Office Management Start -->
                            <li class="sidebar-item">
                                <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                    <i class="mdi mdi-apps"></i>
                                    <span class="hide-menu">Office Management</span>
                                </a>
                                <ul aria-expanded="false" class="collapse first-level">
                                    
                                    <li class="sidebar-item">
                                        <a class="has-arrow sidebar-link" href="javascript:void(0)" aria-expanded="false">
                                            <i class="mdi mdi-email-open-outline"></i>
                                            <span class="hide-menu">Office Expenses</span>
                                        </a>
                                        <ul aria-expanded="false" class="collapse second-level">
                                            
                                            <li class="sidebar-item">
                                                <a   href="{{ route('office_expenses_report') }}" class="sidebar-link" aria-expanded="false">
                                                    <i class=""></i>
                                                    <span class="hide-menu">Report</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-item">
                                                <a href="{{ route('office_expenses_category') }}" class="sidebar-link" aria-expanded="false">
                                                    <i class="mdi mdi-message-bulleted-off"></i>
                                                    <span class="hide-menu">Category</span>
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
                                        <span class="hide-menu">TDS Management</span>
                                        <!-- <span class="badge badge-info badge-pill ml-auto mr-3 font-medium px-2 py-1">2</span> -->
                                    </a>
                                    <ul aria-expanded="false" class="collapse first-level">
                                        <!-- <li class="sidebar-item">
                                            <a href="starter-kit.html" class="sidebar-link">
                                                <i class="mdi mdi-crop-free"></i>
                                                <span class="hide-menu">TDS Charge Set </span>
                                            </a>
                                        </li> -->
                                        <li class="sidebar-item">
                                            <a class="sidebar-link waves-effect waves-dark" href="{{ route('tds_report') }}" aria-expanded="false">
                                                <i class="mdi mdi-av-timer"></i>
                                                <span class="hide-menu"> Report</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('tds_upload') }}" class="sidebar-link">
                                                <i class="mdi mdi-tablet"></i>
                                                <span class="hide-menu">Upload Certificate</span>
                                            </a>
                                        </li>
                                    </ul>
                            </li>
                            <!-- TDS Management End -->

                            <li class="sidebar-item">
                                    <a href="starter-kit.html" class="sidebar-link">
                                        <i class="mdi mdi-format-list-bulleted-type"></i>
                                        <span class="hide-menu">Crazypay Management</span>
                                    </a>
                            </li>

                            <!-- News Management Start -->

                            <li class="sidebar-item">
                                <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                    <i class="mdi mdi-apps"></i>
                                    <span class="hide-menu">News Management</span>
                                </a>
                                <ul aria-expanded="false" class="collapse first-level">
                                    <li class="sidebar-item">
                                        <a href="{{ route('offers-notice') }}" class="sidebar-link">
                                            <i class="mdi mdi-crop-free"></i>
                                            <span class="hide-menu">Offers & Notices </span>
                                        </a>
                                    </li>
                                   
                                    <li class="sidebar-item">
                                        <a class="has-arrow sidebar-link" href="javascript:void(0)" aria-expanded="false">
                                            <i class="mdi mdi-email-open-outline"></i>
                                            <span class="hide-menu">SMS Management</span>
                                        </a>
                                        <ul aria-expanded="false" class="collapse second-level">
                                            
                                            <li class="sidebar-item">
                                                <a   href="{{ route('sms_template') }}"  class="sidebar-link" aria-expanded="false">
                                                    <i class=""></i>
                                                    <span class="hide-menu">Template Management</span>
                                                </a>
                                            </li>
                                           
                                            <li class="sidebar-item">
                                                <a href="starter-kit.html" class="sidebar-link" aria-expanded="false">
                                                    <i class="mdi mdi-message-bulleted-off"></i>
                                                    <span class="hide-menu">Bulk SMS</span>
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
                                <span class="hide-menu">Passbook </span>
                                <span class="badge badge-info badge-pill ml-auto mr-3 font-medium px-2 py-1">2</span>
                            </a>
                                <ul aria-expanded="false" class="collapse first-level">
                                    <li class="sidebar-item">
                                        <a class="sidebar-link waves-effect waves-dark" href="{{ route('passbook') }}" aria-expanded="false">
                                            <i class="mdi mdi-av-timer"></i>
                                            <span class="hide-menu">My Passbook</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="{{ route('member_passbook') }}" class="sidebar-link">
                                            <i class="mdi mdi-tablet"></i>
                                            <span class="hide-menu">Member's Passbook</span>
                                        </a>
                                    </li>
                                </ul>
                        </li>
                            <!-- Passbook reports ends -->

                            <!-- Payment Getway Report -->
                      
                        
                      
                        
                                <!-- <li class="sidebar-item">
                                    <a class="has-arrow sidebar-link" href="javascript:void(0)" aria-expanded="false">
                                        <i class="mdi mdi-email-open-outline"></i>
                                        <span class="hide-menu">Admin Account Report</span>
                                    </a>
                                    <ul aria-expanded="false" class="collapse second-level">
                                        <li class="sidebar-item">
                                            <a href="email-templete-alert.html" class="sidebar-link">
                                                <i class="mdi mdi-message-alert"></i>
                                                <span class="hide-menu">Transfer Report</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="email-templete-basic.html" class="sidebar-link">
                                                <i class="mdi mdi-message-bulleted"></i>
                                                <span class="hide-menu">Wallet Report</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li> -->

                                <!-- 19-11-20 -->
                                <!-- <li class="sidebar-item">
                                    <a href="ui-typography.html" class="sidebar-link">
                                        <i class="mdi mdi-format-line-spacing"></i>
                                        <span class="hide-menu">Registration Charge Report</span>
                                    </a>
                                </li> -->
                                <!-- 19-11-20 -->
                                <!-- <li class="sidebar-item">
                                    <a href="ui-bootstrap.html" class="sidebar-link">
                                        <i class="mdi mdi-bootstrap"></i>
                                        <span class="hide-menu">Credit Report</span>
                                    </a>
                                </li> -->
                                <!-- 19-11-20 -->
                                <!-- <li class="sidebar-item">
                                    <a href="ui-breadcrumb.html" class="sidebar-link">
                                        <i class="mdi mdi-equal"></i>
                                        <span class="hide-menu">Expense Report</span>
                                    </a>
                                </li> -->
                                <!-- 19-11-20 -->
                                <!-- <li class="sidebar-item">
                                    <a href="ui-list-media.html" class="sidebar-link">
                                        <i class="mdi mdi-file-video"></i>
                                        <span class="hide-menu"> API Fund Request Report</span>
                                    </a>
                                </li> -->
                                <!-- 19-11-20 -->
                                <!-- <li class="sidebar-item">
                                    <a href="ui-grid.html" class="sidebar-link">
                                        <i class="mdi mdi-view-module"></i>
                                        <span class="hide-menu"> API Commission Report</span>
                                    </a>
                                </li> -->
                                <!-- <li class="sidebar-item">
                                    <a href="ui-carousel.html" class="sidebar-link">
                                        <i class="mdi mdi-view-carousel"></i>
                                        <span class="hide-menu">Crazypay Report</span>
                                    </a>
                                </li> -->
                                <!-- <li class="sidebar-item">
                                    <a href="ui-scrollspy.html" class="sidebar-link">
                                        <i class="mdi mdi-crop-free"></i>
                                        <span class="hide-menu">Complaint Report</span>
                                    </a>
                                </li> -->
                                <!-- <li class="sidebar-item">
                                    <a class="has-arrow sidebar-link" href="javascript:void(0)" aria-expanded="false">
                                        <i class="mdi mdi mdi-crop-free"></i>
                                        <span class="hide-menu">Complaint</span>
                                    </a>
                                    <ul aria-expanded="false" class="collapse second-level">
                                        <li class="sidebar-item">
                                       
                                            <a href=" {{ route('complaints',['service_type'=>'COMPLAINT']) }}" class="sidebar-link">
                                                <i class="mdi mdi-message-alert"></i>
                                                <span class="hide-menu">Complaint List</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('template') }}" class="sidebar-link">
                                                <i class="mdi mdi-message-bulleted"></i>
                                                <span class="hide-menu">Template</span>
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
                                <span class="hide-menu">SMS Management</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item">
                                    <a href="{{ route('sms_template') }}" class="sidebar-link">
                                        <i class="mdi mdi-message-draw"></i>
                                        <span class="hide-menu">Templates</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="javascript:void(0)" class="sidebar-link">
                                        <i class="mdi mdi-message-processing"></i>
                                        <span class="hide-menu">Send SMS</span>
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
                                <span class="hide-menu">Dashboard</span>
                            </a>
                        </li> 
                        @endif

                        @if(isset($subadmin[0]->control_panel) && ($subadmin[0]->control_panel == 1))
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <i class="mdi mdi-cart-outline"></i>
                                <span class="hide-menu">Control Panel</span>
                            </a>

                            <ul aria-expanded="false" class="collapse first-level">

                                <li class="sidebar-item">
                                    <a class="has-arrow sidebar-link" href="javascript:void(0)" aria-expanded="false">
                                        <i class="mdi mdi-email-open-outline"></i>
                                        <span class="hide-menu">API Management</span>
                                    </a>
                                    <ul aria-expanded="false" class="collapse second-level">
                                        
                                        <li class="sidebar-item">
                                            <a href="{{ route('service_type') }}" class="sidebar-link" aria-expanded="false">
                                                <i class=""></i>
                                                <span class="hide-menu">Services</span>
                                            </a>
                                        </li><li class="sidebar-item">
                                            <a href="{{ route('api_setting') }}" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu">API Portal</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a  href="{{ route('operator') }}"  class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu">Operators</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a  href="{{ route('operator_dtls') }}" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu">API Operator Setting</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('operator_settings') }}" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu">Transfer Recharge API</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a  href="{{ route('api_amount_details') }}" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu">Transfer API by Amount</span>
                                            </a>
                                        </li>
                                        
                                    </ul>
                                </li>
                                
                           
                                
                                <li class="sidebar-item">
                                    <a class="sidebar-link waves-effect waves-dark" href="{{ route('bbps_management') }}" aria-expanded="false">
                                        <i class="mdi mdi-account-outline"></i>
                                        <span class="hide-menu">BBPS Management</span>
                                    </a>
                                </li>
                                <!-- <li class="sidebar-item">
                                    <a class="sidebar-link waves-effect waves-dark" href="{{ route('operator_settings') }}" aria-expanded="false">
                                        <i class="mdi mdi-account-outline"></i>
                                        <span class="hide-menu">Operator Setting</span>
                                    </a>
                                </li> -->

                               

                                <li class="sidebar-item">
                                    <a class="has-arrow sidebar-link" href="javascript:void(0)" aria-expanded="false">
                                        <i class="mdi mdi-email-open-outline"></i>
                                        <span class="hide-menu">DMT Management </span>
                                    </a>
                                    <ul aria-expanded="false" class="collapse second-level">
                                        
                                        <li class="sidebar-item">
                                            <a href="{{ route('bank_list') }}" class="sidebar-link" aria-expanded="false">
                                                <i class=""></i>
                                                <span class="hide-menu">Bank List</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('dmt_margin') }}" class="sidebar-link" aria-expanded="false">
                                                <i class=""></i>
                                                <span class="hide-menu">DMT Margin</span>
                                            </a>
                                        </li>
                                       
                                        
                                    </ul>
                                </li>

                                <li class="sidebar-item">
                                    <a class="has-arrow sidebar-link" href="javascript:void(0)" aria-expanded="false">
                                        <i class="mdi mdi-email-open-outline"></i>
                                        <span class="hide-menu">Margin  Management</span>
                                    </a>
                                    <ul aria-expanded="false" class="collapse second-level">
                                        
                                        <li class="sidebar-item">
                                            <a href="{{ route('package_setting') }}" class="sidebar-link" aria-expanded="false">
                                                <i class=""></i>
                                                <span class="hide-menu">Package Set</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('pack_comm_dtls') }}" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu">Margin Set</span>
                                            </a>
                                        </li>
                                       
                                        
                                    </ul>
                                </li>

                                <li class="sidebar-item">
                                    <a class="has-arrow sidebar-link" href="javascript:void(0)" aria-expanded="false">
                                        <i class="mdi mdi-email-open-outline"></i>
                                        <span class="hide-menu">PG  Management</span>
                                    </a>
                                    <ul aria-expanded="false" class="collapse second-level">
                                        
                                        <li class="sidebar-item">
                                            <a href="{{ route('charges_setting') }}" class="sidebar-link" aria-expanded="false">
                                                <i class=""></i>
                                                <span class="hide-menu">PG Charge Set</span>
                                            </a>
                                        </li>
                                       
                                        
                                    </ul>
                                </li>
                                
                             
                                
                                
                                <!-- 19-11-20 -->
                                <!-- <li class="sidebar-item">
                                    <a class="sidebar-link waves-effect waves-dark" href="{{ route('pay_gate_setting') }}" aria-expanded="false">
                                        <i class="mdi mdi-av-timer"></i>
                                        <span class="hide-menu">Payment Gateway Setting</span>
                                    </a>
                                </li> -->
                                <!-- 19-11-20 -->
                                <!-- <li class="sidebar-item">
                                    <a class="sidebar-link waves-effect waves-dark" href="{{ route('sms_gate_setting') }}" aria-expanded="false">
                                        <i class="mdi mdi-av-timer"></i>
                                        <span class="hide-menu">SMS Setting</span>
                                    </a>
                                </li> -->
                                <!-- 19-11-20 -->
                                <!-- <li class="sidebar-item">
                                    <a class="sidebar-link waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                        <i class="mdi mdi-av-timer"></i>
                                        <span class="hide-menu">Sub Admin</span>
                                    </a>
                                </li> -->
                                <li class="sidebar-item">
                                    <a href="{{ route('general_setting') }}" class="sidebar-link">
                                        <i class="mdi mdi-bulletin-board"></i>
                                        <span class="hide-menu">General Setting</span>
                                    </a>
                                </li>

                                <li class="sidebar-item">
                                    <a class="sidebar-link active" href="{{ route('day_book') }}" aria-expanded="false">
                                        <i class="mdi mdi-clipboard-text"></i>
                                        <span class="hide-menu">Day Book</span>
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
                                <span class="hide-menu">Reports Management </span>
                                <span class="badge badge-info badge-pill ml-auto mr-3 font-medium px-2 py-1">4</span>
                            </a>
                                <ul aria-expanded="false" class="collapse first-level">
                                    <li class="sidebar-item">
                                        <a class="has-arrow sidebar-link" href="javascript:void(0)" aria-expanded="false">
                                            <i class="mdi mdi-email-open-outline"></i>
                                            <span class="hide-menu">Transaction Report</span>
                                        </a>
                                        <ul aria-expanded="false" class="collapse second-level">
                                            
                                            <li class="sidebar-item">
                                                <a  href="{{ route('transaction_report',['service_type'=>'RECHARGE']) }}" class="sidebar-link" aria-expanded="false">
                                                    <i class=""></i>
                                                    <span class="hide-menu">Recharge Reports</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-item">
                                                <a href="{{ route('transaction_report',['service_type'=>'BILL_PAYMENTS']) }}" class="sidebar-link" aria-expanded="false">
                                                    <i class="mdi mdi-message-bulleted-off"></i>
                                                    <span class="hide-menu">Bill Payment Report</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-item">
                                                <a  href="{{ route('transaction_report',['service_type'=>'MONEY_TRANSFER']) }}"  class="sidebar-link" aria-expanded="false">
                                                    <i class="mdi mdi-message-bulleted-off"></i>
                                                    <span class="hide-menu">DMT Report</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-item">
                                                <a  href="{{ route('transaction_report',['service_type'=>'UPI_TRANSFER']) }}" class="sidebar-link" aria-expanded="false">
                                                    <i class="mdi mdi-message-bulleted-off"></i>
                                                    <span class="hide-menu">BHIM UPI Report</span>
                                                </a>
                                            </li>
                                        
                                            
                                        </ul>
                                    </li>
                                    <li class="sidebar-item">
                                        <a class="has-arrow sidebar-link" href="javascript:void(0)" aria-expanded="false">
                                            <i class="mdi mdi-email-open-outline"></i>
                                            <span class="hide-menu">Commission Reports</span>
                                        </a>
                                        <ul aria-expanded="false" class="collapse second-level">
                                            
                                            <li class="sidebar-item">
                                                <a  href="{{ route('commission_report',['service_type'=>'RECHARGE']) }}" class="sidebar-link" aria-expanded="false">
                                                    <i class=""></i>
                                                    <span class="hide-menu">Recharge Reports</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-item">
                                                <a href="{{ route('commission_report',['service_type'=>'BILL_PAYMENTS']) }}" class="sidebar-link" aria-expanded="false">
                                                    <i class="mdi mdi-message-bulleted-off"></i>
                                                    <span class="hide-menu">Bill Payment Report</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-item">
                                                <a href="{{ route('commission_report',['service_type'=>'MONEY_TRANSFER']) }}"   class="sidebar-link" aria-expanded="false">
                                                    <i class="mdi mdi-message-bulleted-off"></i>
                                                    <span class="hide-menu">DMT Report</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-item">
                                                <a  href="{{ route('commission_report',['service_type'=>'UPI_TRANSFER']) }}" class="sidebar-link" aria-expanded="false">
                                                    <i class="mdi mdi-message-bulleted-off"></i>
                                                    <span class="hide-menu">BHIM UPI Report</span>
                                                </a>
                                            </li>
                                        
                                            
                                        </ul>
                                    </li>

                                    <li class="sidebar-item">
                                        <a class="has-arrow sidebar-link" href="javascript:void(0)" aria-expanded="false">
                                            <i class="mdi mdi-email-open-outline"></i>
                                            <span class="hide-menu">SmartPay Reports</span>
                                        </a>
                                        <ul aria-expanded="false" class="collapse second-level">
                                            
                                            <li class="sidebar-item">
                                                <a  href="{{ route('admin-home') }}" class="sidebar-link" aria-expanded="false">
                                                    <i class=""></i>
                                                    <span class="hide-menu">Admin Transfer Reports</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-item">
                                                <a href="{{ route('admin-home') }}" class="sidebar-link" aria-expanded="false">
                                                    <i class="mdi mdi-message-bulleted-off"></i>
                                                    <span class="hide-menu">Admin Credit Report</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-item">
                                                <a href="{{ route('admin-home') }}"   class="sidebar-link" aria-expanded="false">
                                                    <i class="mdi mdi-message-bulleted-off"></i>
                                                    <span class="hide-menu">Admin Wallet Report</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-item">
                                                <a  href="{{ route('admin-home') }}"  class="sidebar-link" aria-expanded="false">
                                                    <i class="mdi mdi-message-bulleted-off"></i>
                                                    <span class="hide-menu">Member Wallet Reprot</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-item">
                                                <a  href="{{ route('admin-home') }}"  class="sidebar-link" aria-expanded="false">
                                                    <i class="mdi mdi-message-bulleted-off"></i>
                                                    <span class="hide-menu">Panel Analysis</span>
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
                                <span class="hide-menu">Member Management </span>
                                <span class="badge badge-info badge-pill ml-auto mr-3 font-medium px-2 py-1">4</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item">
                                    <a href="{{ route('create_member') }}" class="sidebar-link">
                                        <i class="mdi mdi-comment-processing-outline"></i>
                                        <span class="hide-menu">Member Registration</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a class="sidebar-link active" href="{{ route('user_list') }}" aria-expanded="false">
                                        <i class="mdi mdi-clipboard-text"></i>
                                        <span class="hide-menu">Member List</span>
                                    </a>
                                </li>

                                <li class="sidebar-item">
                                    <a class="sidebar-link active" href="{{ route('user_list') }}" aria-expanded="false">
                                        <i class="mdi mdi-clipboard-text"></i>
                                        <span class="hide-menu">Member Certificate</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a class="sidebar-link active" href="{{ route('create_subadmin') }}" aria-expanded="false">
                                        <i class="mdi mdi-clipboard-text"></i>
                                        <span class="hide-menu">Sub Admin Registration</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a class="sidebar-link active" href="{{ route('user_list') }}" aria-expanded="false">
                                        <i class="mdi mdi-clipboard-text"></i>
                                        <span class="hide-menu">Sub Admin Permission</span>
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
                                <span class="hide-menu">Fund Management </span>
                            </a>
                            <ul aria-expanded="false" class="collapse  first-level">
                                <li class="sidebar-item">
                                    <a href="{{ route('bank_account') }}" class="sidebar-link active">
                                        <i class="mdi mdi-view-quilt"></i>
                                        <span class="hide-menu">Bank Account</span>
                                    </a>
                                </li>
                            
                                <li class="sidebar-item">
                                    <a href="{{ route('balance_request') }}" class="sidebar-link">
                                        <i class="mdi mdi-view-day"></i>
                                        <span class="hide-menu">Balance Request Report</span>
                                    </a>
                                </li>

                                <li class="sidebar-item">
                                    <a href="{{ route('user_payment_gateway_report') }}" class="sidebar-link">
                                        <i class="mdi mdi-view-day"></i>
                                        <span class="hide-menu">Payment Gateway Report</span>
                                    </a>
                                </li>
                                
                                <li class="sidebar-item">
                                    <a href="{{ route('user_virtual_account_report') }}" class="sidebar-link">
                                        <i class="mdi mdi-view-day"></i>
                                        <span class="hide-menu">Virtual Account Report</span>
                                    </a>
                                </li>
                                
                                <li class="sidebar-item">
                                    <a href="{{ route('transfer_revert_balance') }}" class="sidebar-link">
                                        <i class="mdi mdi-view-day"></i>
                                        <span class="hide-menu">Fund Transfer/Revert </span>
                                    </a>
                                </li>
                                <!-- <li class="sidebar-item">
                                    <a href="{{ route('all_transfer') }}" class="sidebar-link">
                                        <i class="mdi mdi-view-day"></i>
                                        <span class="hide-menu">All Transfer</span>
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
                                <span class="hide-menu">Portal Management</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item">
                                    <a href="starter-kit.html" class="sidebar-link">
                                        <i class="mdi mdi-crop-free"></i>
                                        <span class="hide-menu">Android App </span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="starter-kit.html" class="sidebar-link">
                                        <i class="mdi mdi-crop-free"></i>
                                        <span class="hide-menu">Website</span>
                                    </a>
                                </li>

                                <li class="sidebar-item">
                                    <a href="starter-kit.html" class="sidebar-link">
                                        <i class="mdi mdi-crop-free"></i>
                                        <span class="hide-menu">Login Panel</span>
                                    </a>
                                </li>

                                
                                <li class="sidebar-item">
                                    <a  href="{{ route('operator') }}"  class="sidebar-link">
                                        <i class="mdi mdi-crop-free"></i>
                                        <span class="hide-menu">Operator Helpline</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a class="has-arrow sidebar-link" href="javascript:void(0)" aria-expanded="false">
                                        <i class="mdi mdi-email-open-outline"></i>
                                        <span class="hide-menu">Complaint Manage</span>
                                    </a>
                                    <ul aria-expanded="false" class="collapse second-level">
                                        
                                        <li class="sidebar-item">
                                            <a  href="{{ route('complaints',['service_type'=>'COMPLAINT']) }}" class="sidebar-link" aria-expanded="false">
                                                <i class=""></i>
                                                <span class="hide-menu">Compalint List</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('template') }}" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu">Template</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                
                                <li class="sidebar-item">
                                    <a class="sidebar-link waves-effect waves-dark" href="{{ route('slidder_banner') }}" aria-expanded="false">
                                        <i class="mdi mdi-av-timer"></i>
                                        <span class="hide-menu">Slidder Banner Settings</span>
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
                                <span class="hide-menu">Office Management</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                
                                <li class="sidebar-item">
                                    <a class="has-arrow sidebar-link" href="javascript:void(0)" aria-expanded="false">
                                        <i class="mdi mdi-email-open-outline"></i>
                                        <span class="hide-menu">Office Expenses</span>
                                    </a>
                                    <ul aria-expanded="false" class="collapse second-level">
                                        
                                        <li class="sidebar-item">
                                            <a   href="{{ route('office_expenses_report') }}" class="sidebar-link" aria-expanded="false">
                                                <i class=""></i>
                                                <span class="hide-menu">Report</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('office_expenses_category') }}" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu">Category</span>
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
                                    <span class="hide-menu">TDS Management</span>
                                    <!-- <span class="badge badge-info badge-pill ml-auto mr-3 font-medium px-2 py-1">2</span> -->
                                </a>
                                <ul aria-expanded="false" class="collapse first-level">
                                    <!-- <li class="sidebar-item">
                                        <a href="starter-kit.html" class="sidebar-link">
                                            <i class="mdi mdi-crop-free"></i>
                                            <span class="hide-menu">TDS Charge Set </span>
                                        </a>
                                    </li> -->
                                    <li class="sidebar-item">
                                        <a class="sidebar-link waves-effect waves-dark" href="{{ route('tds_report') }}" aria-expanded="false">
                                            <i class="mdi mdi-av-timer"></i>
                                            <span class="hide-menu"> Report</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="{{ route('tds_upload') }}" class="sidebar-link">
                                            <i class="mdi mdi-tablet"></i>
                                            <span class="hide-menu">Upload Certificate</span>
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
                                    <span class="hide-menu">Crazypay Management</span>
                                </a>
                        </li>
                        @endif


                        @if(isset($subadmin[0]->news_management) && ($subadmin[0]->news_management == 1))
                        <!-- News Management Start -->

                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <i class="mdi mdi-apps"></i>
                                <span class="hide-menu">News Management</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item">
                                    <a href="{{ route('offers-notice') }}" class="sidebar-link">
                                        <i class="mdi mdi-crop-free"></i>
                                        <span class="hide-menu">Offers & Notices </span>
                                    </a>
                                </li>
                                
                                <li class="sidebar-item">
                                    <a class="has-arrow sidebar-link" href="javascript:void(0)" aria-expanded="false">
                                        <i class="mdi mdi-email-open-outline"></i>
                                        <span class="hide-menu">SMS Management</span>
                                    </a>
                                    <ul aria-expanded="false" class="collapse second-level">
                                        
                                        <li class="sidebar-item">
                                            <a   href="{{ route('sms_template') }}"  class="sidebar-link" aria-expanded="false">
                                                <i class=""></i>
                                                <span class="hide-menu">Template Management</span>
                                            </a>
                                        </li>
                                        
                                        <li class="sidebar-item">
                                            <a href="starter-kit.html" class="sidebar-link" aria-expanded="false">
                                                <i class="mdi mdi-message-bulleted-off"></i>
                                                <span class="hide-menu">Bulk SMS</span>
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
                                <span class="hide-menu">Passbook </span>
                                <span class="badge badge-info badge-pill ml-auto mr-3 font-medium px-2 py-1">2</span>
                            </a>
                                <ul aria-expanded="false" class="collapse first-level">
                                    <li class="sidebar-item">
                                        <a class="sidebar-link waves-effect waves-dark" href="{{ route('passbook') }}" aria-expanded="false">
                                            <i class="mdi mdi-av-timer"></i>
                                            <span class="hide-menu">My Passbook</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="{{ route('member_passbook') }}" class="sidebar-link">
                                            <i class="mdi mdi-tablet"></i>
                                            <span class="hide-menu">Member's Passbook</span>
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
                                        <span class="hide-menu">Admin Account Report</span>
                                    </a>
                                    <ul aria-expanded="false" class="collapse second-level">
                                        <li class="sidebar-item">
                                            <a href="email-templete-alert.html" class="sidebar-link">
                                                <i class="mdi mdi-message-alert"></i>
                                                <span class="hide-menu">Transfer Report</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="email-templete-basic.html" class="sidebar-link">
                                                <i class="mdi mdi-message-bulleted"></i>
                                                <span class="hide-menu">Wallet Report</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li> -->

                                <!-- 19-11-20 -->
                                <!-- <li class="sidebar-item">
                                    <a href="ui-typography.html" class="sidebar-link">
                                        <i class="mdi mdi-format-line-spacing"></i>
                                        <span class="hide-menu">Registration Charge Report</span>
                                    </a>
                                </li> -->
                                <!-- 19-11-20 -->
                                <!-- <li class="sidebar-item">
                                    <a href="ui-bootstrap.html" class="sidebar-link">
                                        <i class="mdi mdi-bootstrap"></i>
                                        <span class="hide-menu">Credit Report</span>
                                    </a>
                                </li> -->
                                <!-- 19-11-20 -->
                                <!-- <li class="sidebar-item">
                                    <a href="ui-breadcrumb.html" class="sidebar-link">
                                        <i class="mdi mdi-equal"></i>
                                        <span class="hide-menu">Expense Report</span>
                                    </a>
                                </li> -->
                                <!-- 19-11-20 -->
                                <!-- <li class="sidebar-item">
                                    <a href="ui-list-media.html" class="sidebar-link">
                                        <i class="mdi mdi-file-video"></i>
                                        <span class="hide-menu"> API Fund Request Report</span>
                                    </a>
                                </li> -->
                                <!-- 19-11-20 -->
                                <!-- <li class="sidebar-item">
                                    <a href="ui-grid.html" class="sidebar-link">
                                        <i class="mdi mdi-view-module"></i>
                                        <span class="hide-menu"> API Commission Report</span>
                                    </a>
                                </li> -->
                                <!-- <li class="sidebar-item">
                                    <a href="ui-carousel.html" class="sidebar-link">
                                        <i class="mdi mdi-view-carousel"></i>
                                        <span class="hide-menu">Crazypay Report</span>
                                    </a>
                                </li> -->
                                <!-- <li class="sidebar-item">
                                    <a href="ui-scrollspy.html" class="sidebar-link">
                                        <i class="mdi mdi-crop-free"></i>
                                        <span class="hide-menu">Complaint Report</span>
                                    </a>
                                </li> -->
                                <!-- <li class="sidebar-item">
                                    <a class="has-arrow sidebar-link" href="javascript:void(0)" aria-expanded="false">
                                        <i class="mdi mdi mdi-crop-free"></i>
                                        <span class="hide-menu">Complaint</span>
                                    </a>
                                    <ul aria-expanded="false" class="collapse second-level">
                                        <li class="sidebar-item">
                                       
                                            <a href=" {{ route('complaints',['service_type'=>'COMPLAINT']) }}" class="sidebar-link">
                                                <i class="mdi mdi-message-alert"></i>
                                                <span class="hide-menu">Complaint List</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('template') }}" class="sidebar-link">
                                                <i class="mdi mdi-message-bulleted"></i>
                                                <span class="hide-menu">Template</span>
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
                                <span class="hide-menu">SMS Management</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item">
                                    <a href="{{ route('sms_template') }}" class="sidebar-link">
                                        <i class="mdi mdi-message-draw"></i>
                                        <span class="hide-menu">Templates</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="javascript:void(0)" class="sidebar-link">
                                        <i class="mdi mdi-message-processing"></i>
                                        <span class="hide-menu">Send SMS</span>
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

        @if( Auth::user()->roleId == Config::get('constants.DISTRIBUTOR'))
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
                                <span class="hide-menu">Dashboard</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <i class="mdi mdi-format-color-fill"></i>
                                <span class="hide-menu">Reports </span>
                                <span class="badge badge-info badge-pill ml-auto mr-3 font-medium px-2 py-1">4</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">

                                <li class="sidebar-item">
                                    <a href="{{ route('transaction_report',['service_type'=>'RECHARGE']) }}" class="sidebar-link">
                                        <i class="mdi mdi-toggle-switch"></i>
                                        <span class="hide-menu"> Recharge Reports</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('transaction_report',['service_type'=>'BILL_PAYMENTS']) }}" class="sidebar-link">
                                        <i class="mdi mdi-tablet"></i>
                                        <span class="hide-menu">Bill Payment Report</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('transaction_report',['service_type'=>'MONEY_TRANSFER']) }}" class="sidebar-link">
                                        <i class="mdi mdi-sort-variant"></i>
                                        <span class="hide-menu">Money Transfer Report</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('transaction_report',['service_type'=>'UPI_TRANSFER']) }}" class="sidebar-link">
                                        <i class="mdi mdi-sort-variant"></i>
                                        <span class="hide-menu">Bhim UPI</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('transaction_report',['service_type'=>'AEPS']) }}" class="sidebar-link">
                                        <i class="mdi mdi-image-filter-vintage"></i>
                                        <span class="hide-menu">AEPS Report</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('transaction_report',['service_type'=>'AEPS']) }}" class="sidebar-link">
                                        <i class="mdi mdi-image-filter-vintage"></i>
                                        <span class="hide-menu">AEPS Report</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('transaction_report',['service_type'=>'AADHAR_PAY']) }}" class="sidebar-link">
                                        <i class="mdi mdi-image-filter-vintage"></i>
                                        <span class="hide-menu">AADHAR PAY Report</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('transaction_report',['service_type'=>'ICICI_CASH_DEPOSIT']) }}" class="sidebar-link">
                                        <i class="mdi mdi-image-filter-vintage"></i>
                                        <span class="hide-menu">ICICI CASH DEPOSIT Report</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Commission Reports starts -->
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <i class="mdi mdi-format-color-fill"></i>
                                <span class="hide-menu">Commission Reports </span>
                                <span class="badge badge-info badge-pill ml-auto mr-3 font-medium px-2 py-1">4</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                    <li class="sidebar-item">
                                        <a href="{{ route('commission_report',['service_type'=>'RECHARGE']) }}" class="sidebar-link">
                                            <i class="mdi mdi-toggle-switch"></i>
                                            <span class="hide-menu"> Recharge Reports</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="{{ route('commission_report',['service_type'=>'BILL_PAYMENTS']) }}" class="sidebar-link">
                                            <i class="mdi mdi-tablet"></i>
                                            <span class="hide-menu">Bill Payment Report</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="{{ route('commission_report',['service_type'=>'MONEY_TRANSFER']) }}" class="sidebar-link">
                                            <i class="mdi mdi-sort-variant"></i>
                                            <span class="hide-menu">DMT Report</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="{{ route('commission_report',['service_type'=>'UPI_TRANSFER']) }}" class="sidebar-link">
                                            <i class="mdi mdi-sort-variant"></i>
                                            <span class="hide-menu">UPI Transfer Report</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="{{ route('commission_report',['service_type'=>'AEPS']) }}" class="sidebar-link">
                                            <i class="mdi mdi-image-filter-vintage"></i>
                                            <span class="hide-menu">AEPS Report</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!-- commission reports ends -->
                            

                            <li class="sidebar-item">
                                <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                    <i class="mdi mdi-tune-vertical"></i>
                                    <span class="hide-menu">Balance </span>
                                </a>
                                <ul aria-expanded="false" class="collapse  first-level">
                                    <li class="sidebar-item">
                                        <a href="{{ route('bank_account') }}" class="sidebar-link active">
                                            <i class="mdi mdi-view-quilt"></i>
                                            <span class="hide-menu">Bank Account</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="{{ route('balance_request') }}" class="sidebar-link">
                                            <i class="mdi mdi-view-day"></i>
                                            <span class="hide-menu">Balance Request</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="{{ route('transfer_revert_balance') }}" class="sidebar-link">
                                            <i class="mdi mdi-view-day"></i>
                                            <span class="hide-menu">Transfer/Revert Balance</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="{{ route('all_transfer') }}" class="sidebar-link">
                                            <i class="mdi mdi-view-day"></i>
                                            <span class="hide-menu">All Transfer</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            
                            <li class="sidebar-item">
                                <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                    <i class="mdi mdi-tune-vertical"></i>
                                    <span class="hide-menu">Credit Report </span>
                                </a>
                                <ul aria-expanded="false" class="collapse  first-level">
                                    <li class="sidebar-item">
                                        <a href="{{ route('credit_report', ['report'=>'RETAILER'] ) }}" class="sidebar-link active">
                                            <i class="mdi mdi-view-quilt"></i>
                                            <span class="hide-menu">Retailers</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="{{ route('credit_report', ['report'=>'FOS']) }}" class="sidebar-link active">
                                            <i class="mdi mdi-view-quilt"></i>
                                            <span class="hide-menu">FOS</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="sidebar-item">
                                <a class="sidebar-link waves-effect waves-dark" href="{{ route('passbook') }}" aria-expanded="false">
                                    <i class="mdi mdi-av-timer"></i>
                                    <span class="hide-menu">Passbook</span>
                                </a>
                            </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <i class="mdi mdi-format-color-fill"></i>
                                <span class="hide-menu">Retailer Management </span>
                                <span class="badge badge-info badge-pill ml-auto mr-3 font-medium px-2 py-1">12</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item">
                                    <a href="{{ route('create_member',['role_alias' => Config::get('constants.ROLE_ALIAS.RETAILER')]) }}" class="sidebar-link">
                                        <i class="mdi mdi-toggle-switch"></i>
                                        <span class="hide-menu"> Add New Retailer</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('user_list',['role_alias' => Config::get('constants.ROLE_ALIAS.RETAILER')]) }}" class="sidebar-link">
                                        <i class="mdi mdi-tablet"></i>
                                        <span class="hide-menu">List of Retailers</span>
                                    </a>
                                </li>

                                <!-- 19-11-2020 -->
                                <!-- <li class="sidebar-item">
                                    <a href="ui-tab.html" class="sidebar-link">
                                        <i class="mdi mdi-sort-variant"></i>
                                        <span class="hide-menu">Retailer Account Report</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="ui-tooltip-popover.html" class="sidebar-link">
                                        <i class="mdi mdi-image-filter-vintage"></i>
                                        <span class="hide-menu">Operator Report</span>
                                    </a>
                                </li> -->
                            </ul>
                        </li>

                         <!-- user payment gateway Report -->
                         <li class="sidebar-item">
                                    <a href="{{ route('user_payment_gateway_report') }}" class="sidebar-link">
                                        <i class="mdi mdi-format-list-bulleted-type"></i>
                                        <span class="hide-menu">User Payment Gateway Report</span>
                                    </a>
                        </li>
                        
                        <li class="sidebar-item">
                                        <a href="{{ route('user_virtual_account_report') }}" class="sidebar-link">
                                            <i class="mdi mdi-view-day"></i>
                                            <span class="hide-menu">User Virtual Account Report</span>
                                        </a>
                                    </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <i class="mdi mdi-format-color-fill"></i>
                                <span class="hide-menu">FOS Management </span>
                                <span class="badge badge-info badge-pill ml-auto mr-3 font-medium px-2 py-1">12</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item">
                                    <a href="{{ route('create_member',['role_alias' => Config::get('constants.ROLE_ALIAS.FOS')]) }}" class="sidebar-link">
                                        <i class="mdi mdi-toggle-switch"></i>
                                        <span class="hide-menu"> Add New FOS</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('user_list',['role_alias' => Config::get('constants.ROLE_ALIAS.FOS')]) }}" class="sidebar-link">
                                        <i class="mdi mdi-tablet"></i>
                                        <span class="hide-menu">List Of FOS</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <i class="mdi mdi-av-timer"></i>
                                <span class="hide-menu">Passbook</span>
                            </a>
                        </li>
                     
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark" href="{{ route('complaints',['service_type'=>'COMPLAINT']) }}" aria-expanded="false">
                                <i class="mdi mdi-av-timer"></i>
                                <span class="hide-menu">Complaint Box</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark" href="{{ route('view_tds', Auth::user()->userId) }}" aria-expanded="false">
                                <i class="mdi mdi-av-timer"></i>
                                <span class="hide-menu">TDS Certificate</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark" href="{{ route('my_commission') }}" aria-expanded="false">
                                <i class="mdi mdi-av-timer"></i>
                                <span class="hide-menu">My Commission</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark" href="{{ route('operator_helpline') }}" aria-expanded="false">
                                <i class="mdi mdi-av-timer"></i>
                                <span class="hide-menu">Operator Helpline</span>
                            </a>
                        </li>

                        <!-- <li class="sidebar-item">
                                    <a href="{{ route('all-offers-notice') }}" class="sidebar-link">
                                        <i class="mdi mdi-bell"></i>
                                        <span class="hide-menu">Offers & Notice</span>
                                    </a>
                        </li> -->

                        <li class="sidebar-item">
                                <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                    <i class="mdi mdi-tune-vertical"></i>
                                    <span class="hide-menu"> Offers & Notice</span>
                                </a>
                                <ul aria-expanded="false" class="collapse  first-level">
                                    <li class="sidebar-item">
                                        <a href="{{ route('offers-notice-dtrt', ['type'=>'OFFER']) }}" class="sidebar-link active">
                                            <i class="mdi mdi-view-quilt"></i>
                                            <span class="hide-menu">Offers</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="{{ route('offers-notice-dtrt', ['type'=>'NOTICE']) }}" class="sidebar-link active">
                                            <i class="mdi mdi-view-quilt"></i>
                                            <span class="hide-menu">Notices</span>
                                        </a>
                                    </li>
                                </ul>
                        </li>
                        
                        <!-- 19-11-2020 -->
                        <!-- <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <i class="mdi mdi-av-timer"></i>
                                <span class="hide-menu">Support</span>
                            </a>
                        </li> -->

                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark" href="{{ route('logout') }}" aria-expanded="false">
                                <i class="mdi mdi-av-timer"></i>
                                <span class="hide-menu">Logout</span>
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
                                <span class="hide-menu">Dashboard</span>
                            </a>
                        </li>
                        @php $userid=Auth::user()->userId @endphp
                        @php
                         $results = DB::select( DB::raw("SELECT * FROM tbl_user_services WHERE user_id = :somevariable and service_id= :serviceid"), array(
                                     'somevariable' => $userid,
                                       'serviceid' => 5
                                     ));
                         $array = json_decode(json_encode($results), true);
                                    
                                     
                                    @endphp
                                    
                                     @if($array[0]['status']==1)
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('money_transfer') }}" aria-expanded="false">
                                <img src="{{ asset('template_new/img/sidebar/ic_money_transfer.png') }}">
                                <span class="hide-menu">Money Transfer</span>
                            </a>
                        </li>
                        @else
                       
  <script src="http://twitter.github.com/bootstrap/assets/js/bootstrap-tooltip.js"></script>
                         <li class="sidebar-item">
                             
                                
                                <span class="hide-menu">
                                    <div class="popup" onclick="myFunction()">
                                        <a class="sidebar-link" aria-expanded="false">
                                                <img src="{{ asset('template_new/img/sidebar/ic_money_transfer.png') }}">
                                                <span class="hide-menu" data-toggle="tooltip" data-placement="right" title="This Services is Not Active For You, Kindly Contact PayMama Sales Department for Activation : 918374913154">
                                                 Money Transfer
                                                </span>
                                        </a>
                                        
                                     
                                    </div>
                                     
                                </span>
                            
                            
                           
                        </li>
                        @endif

                        <script>
                            // When the user clicks on div, open the popup
                            function myFunction() {
                              var popup = document.getElementById("myPopup");
                              popup.classList.toggle("show");
                            }
                            </script>
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <i class="mdi mdi-format-color-fill"></i>
                                <span class="hide-menu">Reports </span>
                                <span class="badge badge-info badge-pill ml-auto mr-3 font-medium px-2 py-1">4</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item">
                                    <a href="{{ route('transaction_report',['service_type'=>'RECHARGE']) }}" class="sidebar-link">
                                        <i class="mdi mdi-toggle-switch"></i>
                                        <span class="hide-menu"> Recharge Reports</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('transaction_report',['service_type'=>'BILL_PAYMENTS']) }}" class="sidebar-link">
                                        <i class="mdi mdi-tablet"></i>
                                        <span class="hide-menu">Bill Payment Report</span>
                                    </a>
                                </li>

                                 <!-- Comment For Aproval Start -->
                                <li class="sidebar-item">
                                    <a href="{{ route('transaction_report',['service_type'=>'MONEY_TRANSFER']) }}" class="sidebar-link">
                                        <i class="mdi mdi-sort-variant"></i>
                                        <span class="hide-menu">Money Transfer Report</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('transaction_report',['service_type'=>'UPI_TRANSFER']) }}" class="sidebar-link">
                                        <i class="mdi mdi-sort-variant"></i>
                                        <span class="hide-menu">Bhim UPI</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('transaction_report',['service_type'=>'AEPS']) }}" class="sidebar-link">
                                        <i class="mdi mdi-image-filter-vintage"></i>
                                        <span class="hide-menu">AEPS Report</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('transaction_report',['service_type'=>'AADHAR_PAY']) }}" class="sidebar-link">
                                        <i class="mdi mdi-image-filter-vintage"></i>
                                        <span class="hide-menu">AADHAR PAY Report</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('transaction_report',['service_type'=>'ICICI_CASH_DEPOSIT']) }}" class="sidebar-link">
                                        <i class="mdi mdi-image-filter-vintage"></i>
                                        <span class="hide-menu">ICICI CASH DEPOSIT Report</span>
                                    </a>
                                </li>
                                 <!-- Comment For Aproval End -->
                                 
                            </ul>
                        </li>

                        <!-- Commission Reports starts -->
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <i class="mdi mdi-format-color-fill"></i>
                                <span class="hide-menu">Commission Reports </span>
                                <span class="badge badge-info badge-pill ml-auto mr-3 font-medium px-2 py-1">4</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                    <li class="sidebar-item">
                                        <a href="{{ route('commission_report',['service_type'=>'RECHARGE']) }}" class="sidebar-link">
                                            <i class="mdi mdi-toggle-switch"></i>
                                            <span class="hide-menu"> Recharge Reports</span>
                                        </a>
                                    </li>
                                    
                                    <li class="sidebar-item">
                                        <a href="{{ route('commission_report',['service_type'=>'BILL_PAYMENTS']) }}" class="sidebar-link">
                                            <i class="mdi mdi-tablet"></i>
                                            <span class="hide-menu">Bill Payment Report</span>
                                        </a>
                                    </li>
                                   <!--   <li class="sidebar-item">
                                        <a href="{{ route('commission_report',['service_type'=>'MONEY_TRANSFER']) }}" class="sidebar-link">
                                            <i class="mdi mdi-sort-variant"></i>
                                            <span class="hide-menu">Money Transfer Report</span>
                                        </a>
                                    </li> -->
                                    
                                    <!-- Comment For Aproval Start -->
                                    <li class="sidebar-item">
                                        <a href="{{ route('commission_report',['service_type'=>'AEPS']) }}" class="sidebar-link">
                                            <i class="mdi mdi-image-filter-vintage"></i>
                                            <span class="hide-menu">AEPS Report</span>
                                        </a>
                                    </li>
                                     <!-- Comment For Aproval ENd -->
                                </ul>
                            </li>
                            <!-- commission reports ends -->

                <!-- user payment gateway Report -->
                        <li class="sidebar-item">
                                    <a href="{{ route('user_payment_gateway_report') }}" class="sidebar-link">
                                        <i class="mdi mdi-format-list-bulleted-type"></i>
                                        <span class="hide-menu">User Payment Gateway Report</span>
                                    </a>
                        </li>   
                        <li class="sidebar-item">
                                        <a href="{{ route('user_virtual_account_report') }}" class="sidebar-link">
                                            <i class="mdi mdi-view-day"></i>
                                            <span class="hide-menu">User Virtual Account Report</span>
                                        </a>
                                    </li>
                            <li class="sidebar-item">
                                <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                    <i class="mdi mdi-tune-vertical"></i>
                                    <span class="hide-menu">Balance </span>
                                </a>
                                <ul aria-expanded="false" class="collapse  first-level">
                                <li class="sidebar-item">
                                        <a href="{{ route('bank_account') }}" class="sidebar-link active">
                                            <i class="mdi mdi-view-quilt"></i>
                                            <span class="hide-menu">Bank Account</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="{{ route('balance_request') }}" class="sidebar-link">
                                            <i class="mdi mdi-view-day"></i>
                                            <span class="hide-menu">Balance Request</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark" href="{{ route('passbook') }}" aria-expanded="false">
                                <i class="mdi mdi-av-timer"></i>
                                <span class="hide-menu">Passbook</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark" href="{{ route('complaints',['service_type'=>'COMPLAINT']) }}" aria-expanded="false">
                                <i class="mdi mdi-av-timer"></i>
                                <span class="hide-menu">Complaint Box</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark" href="{{ route('view_tds', Auth::user()->userId) }}" aria-expanded="false">
                                <i class="mdi mdi-av-timer"></i>
                                <span class="hide-menu">TDS Certificate</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark" href="{{ route('my_commission') }}" aria-expanded="false">
                                <i class="mdi mdi-av-timer"></i>
                                <span class="hide-menu">My Commission</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark" href="{{ route('operator_helpline') }}" aria-expanded="false">
                                <i class="mdi mdi-av-timer"></i>
                                <span class="hide-menu">Operator Helpline</span>
                            </a>
                        </li>

                        <!-- <li class="sidebar-item">
                                    <a href="{{ route('all-offers-notice') }}" class="sidebar-link">
                                        <i class="mdi mdi-bell"></i>
                                        <span class="hide-menu">Offers & Notice</span>
                                    </a>
                        </li> -->

                        <li class="sidebar-item">
                                <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                    <i class="mdi mdi-tune-vertical"></i>
                                    <span class="hide-menu"> Offers & Notice</span>
                                </a>
                                <ul aria-expanded="false" class="collapse  first-level">
                                    <li class="sidebar-item">
                                        <a href="{{ route('offers-notice-dtrt', ['type'=>'OFFER']) }}" class="sidebar-link active">
                                            <i class="mdi mdi-view-quilt"></i>
                                            <span class="hide-menu">Offers</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="{{ route('offers-notice-dtrt', ['type'=>'NOTICE']) }}" class="sidebar-link active">
                                            <i class="mdi mdi-view-quilt"></i>
                                            <span class="hide-menu">Notices</span>
                                        </a>
                                    </li>
                                </ul>
                        </li>

                        <!-- 19-11-2020 -->
                        <!-- <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <i class="mdi mdi-av-timer"></i>
                                <span class="hide-menu">Support</span>
                            </a>
                        </li> -->

                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark" href="{{ route('logout') }}" aria-expanded="false">
                                <i class="mdi mdi-av-timer"></i>
                                <span class="hide-menu">Logout</span>
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
                                    <input type="hidden" id="pan_front_file_id" name="pan_front_file_id"
                                     value="{{isset(Auth::userKycId()['pan_front_file_id']) ? Auth::userKycId()['pan_front_file_id'] : ''}}"><br>
                                     @if(!isset(Auth::userKycId()['pan_front_file_status']) || (isset(Auth::userKycId()['pan_front_file_status']) && Auth::userKycId()['pan_front_file_status'] != 'APPROVED'))
                                        <button id="pan-file-up-btn" onclick="openUploadModal('pan-file-up-btn','pan_front_file_id','pan_front_img_id')" class="btn btn-sm btn-light" type="button"><i class="mdi mdi-upload"></i> Upload</button>
                                     @endif
                                    </div>
                            </div>
                            <div class="col-3 border-right border-bottom">
                                <img id="pan_front_img_id" src="{{isset(Auth::userKycId()['panFile']['file_path']) ? (Auth::userKycId()['panFile']['file_path']) : ''}}"
                                 alt="{{isset(Auth::userKycId()['panFile']['name']) ? Auth::userKycId()['panFile']['name'] : ''}}" style="height:60px;width:100%;border:1px solid lightgrey">
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
                                    <input type="hidden" id="aadhar_front_file_id" name="aadhar_front_file_id"
                                    value="{{isset(Auth::userKycId()['aadhar_front_file_id']) ? Auth::userKycId()['aadhar_front_file_id'] : ''}}"><br>
                                    
                                    @if(!isset(Auth::userKycId()['aadhar_front_file_status']) || (isset(Auth::userKycId()['aadhar_front_file_status']) && Auth::userKycId()['aadhar_front_file_status'] != 'APPROVED'))
                                    <button id="aadhar-front-file-up-btn" class="btn btn-sm btn-light" onclick="openUploadModal('aadhar-front-file-up-btn','aadhar_front_file_id','aadhar_front_img_id')" type="button"><i class="mdi mdi-upload"></i> Upload</button>
                                     @endif
                                </div>
                            </div>

                            <div class="col-3 border-bottom">
                                <img id="aadhar_front_img_id" src="{{isset(Auth::userKycId()['aadharFrontFile']['file_path']) ? Auth::userKycId()['aadharFrontFile']['file_path'] : ''}}"
                                 alt="{{isset(Auth::userKycId()['aadharFrontFile']['name']) ? Auth::userKycId()['aadharFrontFile']['name'] : ''}}" style="height:60px;width:100%;border:1px solid lightgrey">
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
                                    <input type="hidden" id="aadhar_back_file_id" name="aadhar_back_file_id"
                                    value="{{isset(Auth::userKycId()['aadhar_back_file_id']) ? Auth::userKycId()['aadhar_back_file_id'] : ''}}"><br>

                                    @if(!isset(Auth::userKycId()['aadhar_back_file_status']) || (isset(Auth::userKycId()['aadhar_back_file_status']) && Auth::userKycId()['aadhar_back_file_status'] != 'APPROVED'))
                                    <button id="aadhar-back-file-up-btn" class="btn btn-sm btn-light" onclick="openUploadModal('aadhar-back-file-up-btn','aadhar_back_file_id','aadhar_back_img_id')" type="button"><i class="mdi mdi-upload"></i> Upload</button>
                                     @endif
                                </div>
                            </div>
                            <div class="col-3 mt-4 border-right border-bottom">
                                <img id="aadhar_back_img_id" src="{{ isset(Auth::userKycId()['aadharBackFile']['file_path']) ? (Auth::userKycId()['aadharBackFile']['file_path']) : ''}}"
                                 alt="{{isset(Auth::userKycId()['aadharBackFile']['name']) ? Auth::userKycId()['aadharBackFile']['name'] : ''}}" style="height:60px;width:100%;border:1px solid lightgrey">
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
                                    <input type="hidden" id="photo_front_file_id" name="photo_front_file_id"
                                    value="{{isset(Auth::userKycId()['photo_front_file_id']) ? Auth::userKycId()['photo_front_file_id'] : ''}}"><br>
                                    
                                    @if(!isset(Auth::userKycId()['photo_front_file_status']) || (isset(Auth::userKycId()['photo_front_file_status']) && Auth::userKycId()['photo_front_file_status'] != 'APPROVED'))
                                    <button id="photo-front-file-up-btn" class="btn btn-sm btn-light" onclick="openUploadModal('photo-front-file-up-btn','photo_front_file_id','photo_front_img_id')" type="button"><i class="mdi mdi-upload"></i> Upload</button>
                                     @endif
                                </div>
                            </div>
                            <div class="col-3 mt-4 border-bottom">
                                <img id="photo_front_img_id" src="{{isset(Auth::userKycId()['photoFrontFile']['file_path']) ? Auth::userKycId()['photoFrontFile']['file_path'] : ''}}"
                                 alt="{{isset(Auth::userKycId()['photoFrontFile']['name']) ? Auth::userKycId()['photoFrontFile']['name'] : ''}}" style="height:60px;width:100%;border:1px solid lightgrey">
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
                                    <input type="hidden" id="photo_inner_file_id" name="photo_inner_file_id"
                                    value="{{isset(Auth::userKycId()['photo_inner_file_id']) ? Auth::userKycId()['photo_inner_file_id'] : ''}}"><br>
                                    
                                    @if(!isset(Auth::userKycId()['photo_inner_file_status']) || (isset(Auth::userKycId()['photo_inner_file_status']) && Auth::userKycId()['photo_inner_file_status'] != 'APPROVED'))
                                    <button id="photo-inner-file-up-btn" class="btn btn-sm btn-light" onclick="openUploadModal('photo-inner-file-up-btn','photo_inner_file_id','photo_inner_img_id')" type="button"><i class="mdi mdi-upload"></i> Upload</button>
                                     @endif
                                </div>
                            </div>
                            <div class="col-3 mt-4">
                                <img id="photo_inner_img_id" src="{{isset(Auth::userKycId()['photoInnerFile']['file_path']) ? Auth::userKycId()['photoInnerFile']['file_path'] : ''}}"
                                 alt="{{isset(Auth::userKycId()['photoInnerFile']['name']) ? Auth::userKycId()['photoInnerFile']['name'] : ''}}" style="height:60px;width:100%;border:1px solid lightgrey">
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
                            <input type="text text-center" class="form-control text-center"  name="mpin" id="new-mpin" placeholder="Enter New Mpin" autocomplete="off">
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
                                <img class="rounded-circle" alt="user" src="{{ asset('template_assets/assets/images/users/2.jpg') }}"> </div>
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
                                <img class="rounded-circle" alt="user" src="{{ asset('template_assets/assets/images/users/1.jpg') }}"> </div>
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
                                <img class="rounded-circle" alt="user" src="{{ asset('template_assets/assets/images/users/4.jpg') }}"> </div>
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
                                <img class="rounded-circle" alt="user" src="{{ asset('template_assets/assets/images/users/6.jpg') }}"> </div>
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

</html>
