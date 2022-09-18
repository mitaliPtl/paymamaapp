<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('template_assets/assets/images/favicon_sm_py.png') }}">
        <title>PAYMAMA - Making India Digital</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">


        <!-- CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
        <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
        <link href="//db.onlinewebfonts.com/c/758d40d7ca52e3a9bff2655c7ab5703c?family=Cambria" rel="stylesheet" type="text/css"/>
        <!-- jQuery and JS bundle w/ Popper.js -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
        
        <link href="{{ url('dist/welcome/css/home3.css') }}" rel="stylesheet">
    </head>
    <body>
    
        <section style="">
            <!-- <body style="background:url({{url('template_assets/assets/images/home.png')}}) no-repeat;"> -->
            <!-- <body style="background:url({{url('dist/welcome/images/slider_img.png')}}) no-repeat; 
                           
                            background-size: cover; position: relative"> -->
                           
                        <!-- top nav bar  START-->
                        <nav class="navbar navbar-expand-lg">
                            <div class="row">
                                <style>
                                    ul li {
                                        padding-right: 26px !important;
                                    }
                                </style>
                                    <a class="navbar-brand" href=""><img src="{{ asset('template_assets/assets/images/logos/PAYMAMA_logos.png') }}"  style="height:60px;width:250px;margin-left:7px;"></a>
                                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                                        <span class="navbar-toggler-icon"></span>
                                    </button>
                                    
                                    <div class="collapse navbar-collapse" style="float:right;" id="navbarNav">
                                        <ul class=" navbar-nav ml-auto" style="margin-top:10px;float:right;margin-left:130px !important;">
                                            <li class="nav-item ">
                                                <a class="nav-link top-nav"  style="font-size:16px;font-family: 'Montserrat',sans-serif;color:black;" href="{{ url('/') }}">HOME </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link top-nav"  style="font-size:16px;font-family: 'Montserrat',sans-serif;color:black;" href="{{ url('/about_us') }}">ABOUT US </a>
                                            </li>
                                            <!-- <li class="nav-item">
                                                <a class="nav-link top-nav" href="#">Creazy Shop </a>
                                            </li> -->
                                            <li class="nav-item">
                                                <a class="nav-link top-nav "  style="font-size:16px;color:black;font-family: 'Montserrat',sans-serif;" href="{{url('/services')}}">SERVICES </a>
                                            </li>
                                            <!-- <li class="nav-item">
                                                <a class="nav-link top-nav" href="#">IT Services </a>
                                            </li> -->
                                            <li class="nav-item">
                                                <a class="nav-link top-nav"  style="font-size:16px;color:black;font-family: 'Montserrat',sans-serif;" href="{{ url('/contact_us') }}">CONTACT US</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link top-nav" style="font-size:16px;color:black;font-family: 'Montserrat',sans-serif;" href="#">REGISTER WITH US </a>
                                            </li>
                                            
                                            <li class="nav-item ">
                                                @if (Route::has('login'))
                                                    
                                                        @auth
                                                            <a href="{{  Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN') ? url('/admin-home') : url('/home') }}"
                                                                class="btn btn-login active" role="button" aria-pressed="true" style="color:black;font-family: 'Montserrat',sans-serif;" href="#">Dashboard</a>
                                                            <!-- <a class="nav-link"  href="{{  Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN') ? url('/admin-home') : url('/home') }}">Home</a> -->
                                                        @else
                                                            <!-- <a href="{{ url('/login') }}">Login</a> -->
                                                            <a style="margin-top:-6px;" href="{{ url('/login') }}" class="btn btn-login active" role="button" aria-pressed="true"><button type="button" class="btn btn-primary" style="background-color:#0f24a1;color:white;font-family: 'Montserrat',sans-serif;" href="#">Login</button></a>
                                                            @if (Route::has('signup'))
                                                                <a style="margin-top:-6px;" class="btn btn-login active" href="{{ route('signup') }}"><button type="button" class="btn btn-primary" style="background-color:#0f24a1;color:white;font-family: 'Montserrat',sans-serif;" href="#">Signup</button></a>
                                                            @endif
                                                        @endauth
                                                
                                                @endif
                                            </li>
                                            
                                        </ul>
                                    </div>
                            </div> 
                        </nav>

                        
<!--             
                        <header style="background:url({{url('dist/welcome/images/slider_img1.png')}}) no-repeat; 
                            position: relative;
                            background-size: cover; object-fit: cover;">
                        <img class="banner-mobile" src="{{ asset('dist/welcome/images/slider_mobile_img.png') }}" alt="">
                        <p class="title-one">Recharge from anywhere</p>
                        <p class="title-two">in india, Anytime...</p>
                        <p class="des-one">
                                Your online recharge mobile transaction is completely guaranteed and secure.<br>
                                Earn Hight Profit & get bonus anytime, anywhere
                        </p>
                        <p class="des-two"><i>Aapka apna <u><b> recharge</b></u> app</i></p>

                        
             </header>

            <div class="blank-space"></div> -->
