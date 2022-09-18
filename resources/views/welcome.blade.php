@include('website.header')
<!-- <link href="{{ asset('dist/welcome/css/home3.css') }}" rel="stylesheet"> -->
            
<!-- <header style="background:url({{url('dist/welcome/images/slider_img1.png')}}) no-repeat; 
                            position: relative;
                            background-size: cover; object-fit: cover;"> -->
<header >
    <link rel="stylesheet" href="{{ asset('dist/setting/css/main.css') }}">
              
                        
             </header>

            <div class="blank-space"></div>
             <section class="BannerMain" style="margin-top:-87px;">
            <div class="fadeOut owl-carousel owl-theme" style="height:600px;">
                <div class="item" style="background-image: url({{ asset('dist/welcome/images/banner-1.png') }});background-size:cover;height:600px;">
                    <div class="Wrapper">
                        <div class="slideInner">
                            <div class="imageSection">
                                <!-- <img src="https://masterpay.pro/assets_theme2/img/user-1.png" alt=""> -->
                            </div>
                            <!--<div class="RightSection">-->
                            <!--    <div class="Title">-->
                            <!--        <h2><span>Paymama</span> Ki Services Lao, Double Dhandha Badhao.</h2>-->
                            <!--    </div>-->
                            <!--    <div class="appDwonload">-->
                            <!--        <h3>Download mobile app</h3>-->
                            <!--        <a href="https://play.google.com/store/apps/details?id=com.champion.mpay" target="_blank"><img src="assets_theme2/img/playstore.svg" alt=""></a>-->
                            <!--    </div>-->
                            <!--    <div class="tagline">-->
                            <!--        <h3>Aapka Apna <span>Digital Dost</span></h3>-->
                            <!--    </div>-->
                            <!--</div>-->
                        </div>
                    </div>
                </div>
                <div class="item" style="background-image: url({{ asset('dist/welcome/images/banner-2.png') }});background-size:cover;height:600px;">
                    <div class="Wrapper">
                        <div class="slideInner">
                            <div class="imageSection">
                                <!-- <img src="https://masterpay.pro/assets_theme2/img/sbi.png" alt=""> -->
                            </div>
                            <!--<div class="RightSection">-->
                            <!--    <div class="Title">-->
                            <!--        <h2>Be Vocal to go Local for<br> a better <span><i style="margin-right:10px">कल </i> </span> with <span>Paymama</span></h2>-->
                            <!--    </div>-->
                            <!--    <div class="appDwonload">-->
                            <!--        <h3>Download mobile app</h3>-->
                            <!--        <a href="https://play.google.com/store/apps/details?id=com.champion.mpay" target="_blank"><img src="assets_theme2/img/playstore.svg" alt=""></a>-->
                            <!--    </div>-->
                            <!--    <div class="tagline">-->
                            <!--        <h3>Aapka Apna <span>Digital Dost</span></h3>-->
                            <!--    </div>-->
                            <!--</div>-->
                        </div>
                    </div>
                </div>
                <div class="item" style="background-image: url({{ asset('dist/welcome/images/banner-3.png') }});height:600px;">
                    <div class="Wrapper">
                        <div class="slideInner">
                            <div class="imageSection">
                                <!-- <img src="https://masterpay.pro/assets_theme2/img/sbi.png" alt=""> -->
                            </div>
                            <!--<div class="RightSection">-->
                            <!--    <div class="Title">-->
                            <!--        <h2>More opportunities, More Earnings only at <span>MasterPay</span></h2>-->
                            <!--    </div>-->
                            <!--    <div class="appDwonload">-->
                            <!--        <h3>Download mobile app</h3>-->
                            <!--        <a href="https://play.google.com/store/apps/details?id=com.champion.mpay" target="_blank"><img src="assets_theme2/img/playstore.svg" alt=""></a>-->
                            <!--    </div>-->
                            <!--    <div class="tagline">-->
                            <!--        <h3>Aapka Apna <span>Digital Dost</span></h3>-->
                            <!--    </div>-->
                            <!--</div>-->
                        </div>
                    </div>
                </div>
                <div class="item" style="background-image: url({{ asset('dist/welcome/images/banner-1.png') }});">
                    <div class="Wrapper">
                        <div class="slideInner">
                            <div class="imageSection">
                                <!-- <img src="https://masterpay.pro/assets_theme2/img/sbi.png" alt=""> -->
                            </div>
                            <!--<div class="RightSection">-->
                            <!--    <div class="Title">-->
                            <!--        <h2>Baniyein Atmanirbhar, <span>MasterPay</span> ke sung</h2>-->
                                    
                            <!--    </div>-->
                            <!--    <div class="appDwonload">-->
                            <!--        <h3>Download mobile app</h3>-->
                            <!--        <a href="https://play.google.com/store/apps/details?id=com.champion.mpay" target="_blank"><img src="assets_theme2/img/playstore.svg" alt=""></a>-->
                            <!--    </div>-->
                            <!--    <div class="tagline">-->
                            <!--        <h3>Aapka Apna <span>Digital Dost</span></h3>-->
                            <!--    </div>-->
                            <!--</div>-->
                        </div>
                    </div>
                </div>
            </div>

            <!--<div class="BannerbottomSection">
                <div class="Wrapper">
                    <div class="signUpContent">
                        <div class="content" uk-scrollspy="cls: uk-animation-slide-left; repeat: false">
                            <h3>Let’s together “move ahead towards Atmanirbhar भारत </h3>
                            <p>through Digitalisation by ‘Being Digital’ now, now, now”</p>
                        </div>
                        <div class="singUpbutton" uk-scrollspy="cls: uk-animation-slide-right; repeat: false">
                            <a class="ScrollLink" href="#SignUPSection">Sign up Now!</a>
                        </div>
                    </div>
                </div>
            </div>-->
        </section>
           <script src="{{ asset('dist/setting/js/owl.carousel.min.js') }}"></script>
    <script>
        // Owl Carousel Slider
        $(document).ready(function() {
            $(".BannerMain .owl-carousel").owlCarousel({
                animateIn: 'fadeIn', // add this
                animateOut: 'fadeOut', // and this
                autoplay: true,
                loop: true,
                dotClass: true,
                navigation: true,
                singleItem: true,
                transitionStyle: "fade",
                margin: 0,
                items: 1,
            });

            $('.ServiceSlider .owl-carousel').owlCarousel({
                loop: true,
                nav: false,
                autoplay: true,
                items: 1.5,
                responsive: {
                    0: {
                        items: 1.15
                    },
                    600: {
                        items: 1.5
                    },
                    880: {
                        items: 2.5
                    },
                    1280: {
                        items: 3.5
                    },
                    1480: {
                        items: 4.5
                    }
                }
            })
        });
    </script>
            <!-- services Start -->
             <div class="container" style="margin-top:50px;margin-bottom:50px;">
                    <div class="row">
                        <div class="col-md-3 service_item" >
                            <div class="col-md-12">
                                <img src="{{ asset('dist/welcome/images/Best_Commission_ic.png') }}" alt="">
                            </div>
                            <div class="col-md-12">
                                <span class="serivce_header">BEST COMMISSION</span>
                                <p class="service_desc"> Best Commission in Electricity Bill Payment 1% Margin</p>
                            </div>
                        </div>
                        <div class="col-md-3 service_item">
                            <div class="col-md-12">
                                <img src="{{ asset('dist/welcome/images/Best_Support_ic.png') }}" alt="">
                            </div>
                            <div class="col-md-12">
                                <span class="serivce_header">BEST SUPPORT</span>
                                <p class="service_desc">24X7 Whatsapp & Chat Support Available</p>
                            </div>
                        </div>
                        <div class="col-md-3 service_item">
                            <div class="col-md-12">
                                <img src="{{ asset('dist/welcome/images/Super_Fast_ic.png') }}" alt="">
                            </div>
                            <div class="col-md-12">
                                <span class="serivce_header">SUPER FAST </span>
                                <p class="service_desc">Smart Pay Provides Super Fast Service with Highest Succes Ratio</p>
                            </div>
                        </div>
                        <div class="col-md-3 service_item">

                            <div class="col-md-12">
                                <img src="{{ asset('dist/welcome/images/trusted_ic.png') }}" alt="">
                            </div>
                            <div class="col-md-12">
                            <span class="serivce_header">TRUSTED</span>
                                <p class="service_desc"> Smart Pay is 100% Trustable With the Mutual Cooperation & Transparency</p>
                            </div>
                        </div>
                    </div>
             </div>
            <!-- services End -->
            
            <!-- about Start -->
            <div class="containr-fluid about-section">
                            <div class="about-imgdiv">
                                <img src="{{ asset('dist/welcome/images/about_us_img.png') }}" class="img-fluid about-cover-img" alt="">
                                <img src="{{ asset('dist/welcome/images/about_us_below_img.png') }}" alt="" class="img-fluid about-cover-img" >
                            </div>
                            <div class="about-icon_t  col-md-3">
                                <img src="{{ asset('dist/welcome/images/smartpay_ic.png') }}" class="img-fluid " alt="" >

                            </div>
                            <!-- <img src="{{ asset('dist/welcome/images/smartpay_ic.png') }}" alt="" class="img-fluid about-icon" > -->
                            <div class="col-md-6 about-para_t">

                               
                                <p class="" style="color:white;"> Our recharge serices are available 24X7. We understant your business needs and provide best possible solution. 
                                    We enableyou to transform your business and bost your growth.
                                </p>
                                <p class="" style="color:white;">
                                    We are commited to provide hassle free services round the clock
                                    to our valuable clients. Our team of technical support is available all
                                    time to provide end to end support to the client.
                                </p>
                                <p class="" style="color:white;">
                                    SamrtPay believes to train, assit and work with DMR distribution network
                                    as per mandetes given by th banks. Become a partner with us! Come and join
                                    the team of fastest growing distribution network of samrtpay and become and 
                                    authorized Distributor.
                                </p>
                            
                            </div>

                            <div class="about-para-sec">
                                <p class="" style="color:black;">
                                    Our recharge serices are available 24X7. We understant your business needs and provide best possible solution. 
                                    We enableyou to transform your business and bost your growth.
                                </p>
                                <p class="" style="color:black;">
                                We are commited to provide hassle free services round the clock
                                    to our valuable clients. Our team of technical support is available all
                                    time to provide end to end support to the client.
                                </p>
                            </div>
                        <div>
                            <img src="{{ asset('dist/welcome/images/her_dukandar_img.png') }}" alt="" class="img-fluid about-person" >

                        </div>
                            
            </div>
            <!-- <div class="container-fluid" class="">
               
                    
                            <img src="{{ asset('dist/welcome/images/about_us_img.png') }}" alt="" class="img-fluid about-banner" >
                            <div class="about-para">

                               
                                <p class=""> Our recharge serices are available 24X7. We understant your business needs and provide best possible solution. 
                                    We enableyou to transform your business and bost your growth.
                                </p>
                                <p class="">
                                    We are commited to provide hassle free services round the clock
                                    to our valuable clients. Our team of technical support is available all
                                    time to provide end to end support to the client.
                                </p>
                                <p class="">
                                    SamrtPay believes to train, assit and work with DMR distribution network
                                    as per mandetes given by th banks. Become a partner with us! Come and join
                                    the team of fastest growing distribution network of samrtpay and become and 
                                    authorized Distributor.
                                </p>
                            
                            </div>
                            <img src="{{ asset('dist/welcome/images/smartpay_ic.png') }}" alt="" class="img-fluid about-icon" >


                            <img src="{{ asset('dist/welcome/images/about_us_below_img.png') }}" alt="" class="img-fluid " >
 
                            <div class="about-para-sec">
                                <p class="">
                                    Our recharge serices are available 24X7. We understant your business needs and provide best possible solution. 
                                    We enableyou to transform your business and bost your growth.
                                </p>
                                <p class="">
                                We are commited to provide hassle free services round the clock
                                    to our valuable clients. Our team of technical support is available all
                                    time to provide end to end support to the client.
                                </p>
                            </div>
                            
              
                                
            </div> -->
            <!-- about End -->

            <div class="blank-space"></div>

            <!-- Our serices Start -->
            <div class="container">
                <div class="our-serice">
                    <h2 class="multicolortext">OUR SERVICES</h2>
                    <p>
                        At <b>PAYMAMA</b>, we give not just the Best but Legendary satisfaction to our<br>
                        Customers, through Service Excellence
                    </p>
                </div>
                    
                <div class="row">

                    <div class="col-md-4 pt-3" >
                        <div class="card text-center service-card" style="">
                            <div class="card-body ">

                                <div class="middle-items ">
                                    <img src="{{ asset('dist/welcome/images/Prepaid_Recharge_ic.png') }}" alt="" 
                                        class="img-fluid " >
                                    <h5 class="card-title serivce_list">PREPAID RECHARGE</h5>
                                </div>
                           
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 pt-3">
                        <div class="card text-center service-card" style="">
                            <div class="card-body ">

                                <div class="middle-items ">
                                    <img src="{{ asset('dist/welcome/images/Postpaid_Recharge_ic.png') }}" alt="" 
                                        class="img-fluid " >
                                    <h5 class="card-title serivce_list">POSTPAID RECHARGE</h5>
                                </div>
                           
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 pt-3">
                        <div class="card text-center service-card" style="">
                            <div class="card-body ">

                                <div class="middle-items ">
                                    <img src="{{ asset('dist/welcome/images/DTH_Recharge_ic.png') }}" alt="" 
                                        class="img-fluid " >
                                    <h5 class="card-title serivce_list">DTCH RECHARGE</h5>
                                </div>
                           
                            </div>
                        </div>
                    </div>
                    
                    
                </div>

                <div class="row">
                    <div class="col-md-4 pt-3">
                        <div class="card text-center service-card" style="">
                            <div class="card-body ">

                                <div class="middle-items ">
                                    <img src="{{ asset('dist/welcome/images/Money_Transfer_ic.png') }}" alt="" 
                                        class="img-fluid " >
                                    <h5 class="card-title serivce_list">MONEY TRANSFER</h5>
                                </div>
                           
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 pt-3">
                        <div class="card text-center service-card" style="">
                            <div class="card-body ">

                                <div class="middle-items ">
                                    <img src="{{ asset('dist/welcome/images/AEPS_withdrawal_ic.png') }}" alt="" 
                                        class="img-fluid " >
                                    <h5 class="card-title serivce_list"> AEPS WITHDRAWAL</h5>
                                </div>
                           
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 pt-3">
                        <div class="card text-center service-card" style="">
                            <div class="card-body ">

                                <div class="middle-items ">
                                    <img src="{{ asset('dist/welcome/images/Electricity_ic.png') }}" alt="" 
                                        class="img-fluid " >
                                    <h5 class="card-title serivce_list">ELECTRICITY</h5>
                                </div>
                           
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="blank-space"></div> -->
                <div class="row view-more" >
                    <div class="col text-center">
                        <a href="services"><button type="button" class="btn btn-outline-danger btn-lg view-more-btn">VIEW MORE</button></a>
                    </div>
                </div>
            </div>
            <!-- Our serices End -->

            <div class="blank-space"></div>

            <div class="container-fluid download-section" style="padding-left:0px; padding-right:0px;">
                    <div class="download-imgdiv">
                            <img src="{{ asset('dist/welcome/images/download_img.png') }}" class="img-fluid down-cover-img" alt="" st>
                    </div>
                    <div class="download-mob  col-md-3">
                        <img src="{{ asset('dist/welcome/images/front_mobile_img.png') }}" class="img-fluid " alt="" >

                    </div>

                    <div class=" col-md-6 download_desc">
                        <p class="down-head">
                            The best rated recharge app for Android is here
                        </p>
                        <p class="down_des">
                            Now do quick recharge & simply money transfer, instant
                            updates and a great user experience on your Android phone
                        </p>
                    </div>
                    <div class="col-md-6 down_points">
                        <div class="row ">
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-3 point-img" >
                                        <img src="{{ asset('dist/welcome/images/checkmark_ic.png') }}" alt="" class="img-fluid"> 
                                    </div>
                                    <div class="col-md-9 point-text">
                                        <p style="color:white;">High Margin in The Industry</p>
                                    </div>
                                </div>
                               
                                
                            </div>
                            
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-3 point-img" >
                                        <img src="{{ asset('dist/welcome/images/checkmark_ic.png') }}" alt="" class="img-fluid"> 
                                    </div>
                                    <div class="col-md-9 point-text">
                                        <p style="color:white;">100% Secure</p>
                                    </div>
                                </div>
                               
                                
                            </div>

                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-3 point-img" >
                                        <img src="{{ asset('dist/welcome/images/checkmark_ic.png') }}" alt="" class="img-fluid"> 
                                    </div>
                                    <div class="col-md-9 point-text">
                                        <p style="color:white;">Easy to Use</p>
                                    </div>
                                </div>
                               
                                
                            </div>

                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-3 point-img" >
                                        <img src="{{ asset('dist/welcome/images/checkmark_ic.png') }}" alt="" class="img-fluid"> 
                                    </div>
                                    <div class="col-md-9 point-text">
                                        <p style="color:white;">Multiple Services</p>
                                    </div>
                                </div>
                               
                                
                            </div>

                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-3 point-img" >
                                        <img src="{{ asset('dist/welcome/images/checkmark_ic.png') }}" alt="" class="img-fluid"> 
                                    </div>
                                    <div class="col-md-9 point-text">
                                        <p style="color:white;">24X7 Dedicated Customer Support</p>
                                    </div>
                                </div>
                               
                                
                            </div>


                            
                        </div>
                    </div>
                    <div class="google-play">
                        <img src="{{ asset('dist/welcome/images/googleplay_img.png') }}" alt="" class="img-fluid">
                    </div>
            </div>

        </section>
        <section class="OurPartnersSection" style="background-color: #f2f2f2;padding-top:40px; padding-bottom:50px;">
            <div class="Wrapper">
                <br>
                <div class="SingleLineHeading" uk-scrollspy="cls: uk-animation-slide-bottom; repeat: false">
                   <h2 class="multicolortext" style="text-align:center;">OUR PARTNERS</h2>
                </div>
                <br><br>
                    <div class="row">
                    <div class="col-sm-1"></div>
                   <div class="col-sm-2">
                        <img src="{{asset('template_assets/assets/images/sbi.png')}}" style="width:90%;" alt="img">
                    </div>
                    <div class="col-sm-2">
                        <img src="{{asset('template_assets/assets/images/paytem.png')}}" style="width:90%;"  alt="img">
                    </div>
                    <div class="col-sm-2">
                        <img src="{{asset('template_assets/assets/images/axis.png')}}"  style="width:90%;" alt="img">
                    </div>
                    <div class="col-sm-2">
                        <img src="{{asset('template_assets/assets/images/fino.png')}}" style="width:90%;"  alt="img">
                    </div>
                    <div class="col-sm-2">
                        <img src="{{asset('template_assets/assets/images/airtel-payment-bank.png')}}" style="width:90%;"  alt="img">
                    </div>
                    <div class="col-sm-1"></div>
                </div>
                
            </div>
            <br>
        </section>
    @include('website.footer')



