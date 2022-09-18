@include('website.header')
<div class="blank-space"></div>
<section class="BannerMain" style="margin-top:-100px;">
            <div class="BannerInner" style="background-image: url({{ asset('template_assets/assets/images/background/about_banner.png') }}">
                <div class="Title" uk-scrollspy="cls: uk-animation-slide-bottom; target: h2; repeat: false">
                    <h2>Who We are</h2>
                    <p uk-scrollspy="cls: uk-animation-slide-bottom; repeat: false">Leverage Possibilities And Opportunities</p>
                </div>
            </div>
        </section>
        <style>
            p{
                letter-spacing: 2px !important;
                text-align: justify;
            }
            @media only screen and (max-width: 767px)
            {
                section.BannerMain .BannerInner 
                {
                    height: 300px;
                }
            }
            
            section.BannerMain .BannerInner .Title h2 {
                font-family: "Cambria-Regular";
                font-size: 44px;
                color: #fff;
                text-shadow: 2px 2px 3px rgb(0 0 0 / 30%);
                margin: 0;
            }
            [class*='uk-animation-'] 
            {
                animation-duration: 0.5s;
                animation-timing-function: ease-out;
                animation-fill-mode: both;
            }
            .uk-animation-slide-bottom {
                animation-name: uk-fade-bottom;
            }
            section.BannerMain .BannerInner .Title p {
                font-size: 20px;
                color: #fff;
                text-shadow: 2px 2px 3px rgb(0 0 0 / 30%);
                margin: 0;
            }
            section.BannerMain .BannerInner .Title {
                text-align: center;
            }
            section.BannerMain .BannerInner 
            {
                    width: 100%;
                    height: 400px;
                    background-repeat: no-repeat;
                    background-size: cover;
                    background-position: center;
                
            }
            .button, footer .FooterParent .connectWithUs .FooterSocials a, section.BannerMain .BannerbottomSection .signUpContent, section.AwordWiningSection .addServices, section.DigitalDostSection .DigitalDostRow, section.OurPartnersSection .partnersList, section.signUpSection .signUPInner .signUpForm .row, section.BannerMain .BannerInner, section .ServicesDetails .List .ListInner, section.BannerMain .contactusBanner .PageHeading .socialMedia a, section.ContactForm .contactDetails .leftDetails .details ul li span, section.ContactForm .contactDetails .RightForm .FormBox .heading span, section.ContactForm .contactDetails .RightForm .FormBox .FormInner form .row, section.BannerMain .contactusBanner .PageHeading .heading .socialMedia a, section.OurValuePurpose .ValuePurposeSectionInner .SectionBox .text ul, section.ourJourny .OurJournySlider .OurJnr-Content .swiper-slide .slideInner .Title, section.ourJourny .OurJournySlider .OurJnr-List .swiper-wrapper .swiper-slide .cirl, section.ourJourny .OurJournySlider .SliderArrow, section.ourJourny .OurJournySlider .SliderArrow .ArrowSlide, .PopupMain .PopupCenter .PopupBox .CloseIcon, .dtr-details .dtr-data, footer .FooterParent .connectWithUs .FooterSocials, section.AwordWiningSection .addServices .leftService ul li, section.AwordWiningSection .addServices .rightService ul li, section.BannerMain .contactusBanner .PageHeading .socialMedia, section.ContactForm .contactDetails .RightForm .FormBox .heading, section.BannerMain .contactusBanner .PageHeading .heading .socialMedia, header.HeaderMain .MinMenu, .IconButton, .dropdown-menu ul li a, header.HeaderMain .MinMenu nav.NavbarMain ul, section.BannerMain .owl-carousel .owl-item .item .slideInner, section.AdvSection .AdvInner, section.ourJourny .OurJournySlider .OurJnr-List .swiper-wrapper, table.dataTable>tbody>tr.child ul.dtr-details>li
            {
                display: -webkit-box;
                display: -webkit-flex;
                display: -moz-flex;
                display: -ms-flexbox;
                display: flex;
                -webkit-box-direction: normal;
                -webkit-box-orient: horizontal;
                -webkit-flex-direction: row;
                -moz-flex-direction: row;
                -ms-flex-direction: row;
                flex-direction: row;
            }
            .button, footer .FooterParent .connectWithUs .FooterSocials a, section.BannerMain .BannerbottomSection .signUpContent, section.AwordWiningSection .addServices, section.DigitalDostSection .DigitalDostRow, section.OurPartnersSection .partnersList, section.signUpSection .signUPInner .signUpForm .row, section.BannerMain .BannerInner, section .ServicesDetails .List .ListInner, section.BannerMain .contactusBanner .PageHeading .socialMedia a, section.ContactForm .contactDetails .leftDetails .details ul li span, section.ContactForm .contactDetails .RightForm .FormBox .heading span, section.ContactForm .contactDetails .RightForm .FormBox .FormInner form .row, section.BannerMain .contactusBanner .PageHeading .heading .socialMedia a, section.OurValuePurpose .ValuePurposeSectionInner .SectionBox .text ul, section.ourJourny .OurJournySlider .OurJnr-Content .swiper-slide .slideInner .Title, section.ourJourny .OurJournySlider .OurJnr-List .swiper-wrapper .swiper-slide .cirl, section.ourJourny .OurJournySlider .SliderArrow, section.ourJourny .OurJournySlider .SliderArrow .ArrowSlide, .PopupMain .PopupCenter .PopupBox .CloseIcon
            {
                    -webkit-box-align: center;
                    -ms-flex-align: center;
                    -webkit-align-items: center;
                    -moz-align-items: center;
                    align-items: center;
                    -webkit-box-pack: center;
                    -ms-flex-pack: center;
                    -webkit-justify-content: center;
                    -moz-justify-content: center;
                    justify-content: center;
            }
            section .SingleLineHeading h3
            {
                font-family: "Cambria-Regular";
                font-size: 52px;
                color: #2b2b2b;
                text-align: center;
                margin: 0;
            }
            section.OurValuePurpose {
                position: relative;
            }
            section.OurValuePurpose .ValuePurposeSectionInner {
                display: -webkit-box;
                display: -webkit-flex;
                display: -moz-flex;
                display: -ms-flexbox;
                display: flex;
                -webkit-box-pack: center;
                -ms-flex-pack: center;
                -webkit-justify-content: center;
                -moz-justify-content: center;
                justify-content: center;
                -webkit-box-align: Flex-start;
                -ms-flex-align: Flex-start;
                -webkit-align-items: Flex-start;
                -moz-align-items: Flex-start;
                align-items: Flex-start;
                position: relative;
                z-index: 2;
            }
            section.OurValuePurpose .ValuePurposeSectionInner .SectionBox {
                width: 50%;
                padding: 80px;
                text-align: center;
                position: relative;
            }

            .uk-animation-slide-left {
                animation-name: uk-fade-left;
            }
            [class*='uk-animation-'] {
                animation-duration: 0.5s;
                animation-timing-function: ease-out;
                animation-fill-mode: both;
            }
            .uk-animation-slide-right {
                animation-name: uk-fade-right;
            }
            section.OurValuePurpose:before {
                left: 0;
                background-color: #ff6633;
                border-right: 1px solid #fff;
            }
            section.OurValuePurpose:before, section.OurValuePurpose:after {
                content: '';
                position: absolute;
                top: 0;
                width: 50%;
                height: 100%;
                border-bottom: 2px solid #fff;
                z-index: 1;
            }
            section.OurValuePurpose:after {
                right: 0;
                background-color: #f9a03f;
                border-left: 1px solid #fff;
            }
            section.OurValuePurpose .ValuePurposeSectionInner .SectionBox .icon svg {
                width: 85px;
                height: 85px;
            }
            section.OurValuePurpose .ValuePurposeSectionInner .SectionBox .text h3 {
                font-family: "Cambria-Bold";
                font-size: 30px;
                color: #fff;
                margin-top: 0px;
                margin-bottom: 20px;
                transition: all 0.8s cubic-bezier(0.2, 0.8, 0.2, 1);
            }
            section.OurValuePurpose .ValuePurposeSectionInner .SectionBox .text p {
                font-size: 17px;
                color: #fff;
                margin: 0;
                margin-bottom: 10px;
                transition: all 0.8s cubic-bezier(0.2, 0.8, 0.2, 1);
            }
            section.OurValuePurpose .ValuePurposeSectionInner .SectionBox .text ul {
                -webkit-flex-wrap: wrap;
                -moz-flex-wrap: wrap;
                -ms-flex-wrap: wrap;
                flex-wrap: wrap;
                transition: all 0.8s cubic-bezier(0.2, 0.8, 0.2, 1);
            }
            @media only screen and (max-width: 767px)
            {
                section.OurValuePurpose .ValuePurposeSectionInner .SectionBox {
                padding: 60px 0;
                width: 100%;
                border-bottom: 2px solid #fff;
            }
            @media only screen and (max-width: 767px)
            {
            section.OurValuePurpose .ValuePurposeSectionInner {
                -webkit-box-direction: normal;
                -webkit-box-orient: vertical;
                -webkit-flex-direction: column;
                -moz-flex-direction: column;
                -ms-flex-direction: column;
                flex-direction: column;
            }
            }
            @media only screen and (max-width: 767px)
            {
            section.OurValuePurpose .ValuePurposeSectionInner .SectionBox:before {
                content: '';
                position: absolute;
                top: 0;
                right: -20px;
                left: -20px;
                width: auto;
                height: 100%;
                background-color: #ff6633;
                z-index: -1;
            }
              section.OurValuePurpose:before {
             
                background-color: #f9a03f !important;
                z-index: -1;
            }
            }
        </style>
<div class="" style="padding:30px;">
    <br>

        <section class="AboutContent">
            <div class="Wrapper">
                <div class="SingleLineHeading" uk-scrollspy="cls: uk-animation-slide-bottom; repeat: false">
                    <br>
                    <h3>About Us</h3>
                    <br>
                </div>
                <div class="innerContent" uk-scrollspy="cls: uk-animation-slide-bottom; target: > p; repeat: false">
                            <h3>THE SMARTPAY STORY</h3>
                            <br>
        <p>WE BELIEVE THAT, THE BEST SERVICE MAKES A CUSTOMER HAPPY</p>
        <p style="text-align: left;">SMARTPAY (SMARTPAY TECHNOLOGIES INDIA OPC PRIVATE LIMITED)
            <!--<img class="" style="float:right; width:32%" src="{{ asset('dist/welcome/images/about_us_smartpay.png') }}" >-->
        </p>
        <p>Entered In To B2B Network<br>
            Trusted Platform For Retailers And Distributors Provides Recharge, Bill Payments And Financial Services.
        </p>
        <p >SmartPay is ultimate platform for distributors and retailers who provide online payment services. We assure our clients and customers safe, secure and convenient mode of transactions. Indeed, we are unique digital platform that caters attractive and instant (real time) rewards as well as commission to our B2B partners such as distributors, retailers, clients etc., through our own developed safe and secure interface.</p>
        <p >To meet the futuristic digital and online financial solutions, SmartPay believes to facilitate each and every people of the country one of most trusted, reliable and quickest online payment solution gateways. We believe to work with all premier financial institutions and organizations who are leading the economy of country whether they are governmental or non-governmental bodies. Through our safe, secure, reliable and quickest payment gateway we believe to connect Semi-urban &amp; Rural India to the mainstream of economic development.</p>
        <p >SmartPay believes to train, assist and work with DMR distribution networks as per mandates given by the banks. Become a partner with us! Come and join the team of fastest growing distribution network of smartpay and become and authorized Distributor.</p>
        <p >We are committed to provide hassle free services round the clock to our valuable clients. Our team of technical support is available all time to provide end to end support to the client.</p>
        <p >Our recharge services are available 24X7. We understand your business needs and provide best possible solution. We enable you to transform your business and boost your business growth.</p>
                    <p style="font-size: 24px; margin-top: 30px;"><i>Let’s together “move ahead towards Atmanirbhar भारत through Digitalisation by ‘Being Digital’ now, now, now”</i></p>
                </div>
            </div>
        </section>
</div>
  <section class="OurValuePurpose">
            <div class="Wrapper">
                <div class="ValuePurposeSectionInner">
                    <div class="SectionBox col-sm-6 col-xs-12" uk-scrollspy="cls: uk-animation-slide-left; repeat: false">
                        <div class="icon">
                            <svg  viewBox="0 0 87 87">
                                <path fill-rule="evenodd"  fill="rgb(255, 255, 255)" d="M86.510,55.568 L60.291,73.570 C60.247,73.600 60.201,73.627 60.153,73.651 C56.399,75.499 52.152,76.099 48.032,75.362 L32.874,72.571 C32.850,72.567 32.827,72.561 32.803,72.555 C31.699,72.276 30.529,72.427 29.532,72.979 L22.080,77.689 L24.473,85.540 C24.505,85.647 24.522,85.758 24.522,85.869 C24.522,86.494 24.016,87.000 23.391,87.000 L5.178,87.000 C4.607,87.000 4.125,86.573 4.056,86.006 L0.008,52.819 C0.003,52.773 0.000,52.728 0.000,52.682 C0.000,52.058 0.506,51.552 1.131,51.552 L13.273,51.552 C13.771,51.552 14.210,51.877 14.355,52.353 L15.781,57.033 C16.996,56.669 18.118,56.044 19.067,55.202 L21.578,52.967 C21.601,52.945 21.625,52.926 21.649,52.907 C27.004,48.858 34.179,48.157 40.216,51.094 L41.256,51.597 C42.731,52.308 44.346,52.678 45.983,52.680 L53.543,52.680 L59.224,49.591 C62.151,47.905 65.821,48.237 68.399,50.420 L69.454,49.852 C69.462,49.847 69.471,49.843 69.479,49.839 C71.862,48.631 74.990,49.480 76.795,51.670 L77.242,51.429 C80.628,49.596 84.856,50.753 86.838,54.053 C87.147,54.566 87.003,55.230 86.510,55.568 ZM12.436,53.813 L2.407,53.813 L6.178,84.739 L21.865,84.739 L12.436,53.813 ZM60.345,51.554 C60.337,51.559 60.327,51.564 60.318,51.569 L57.908,52.877 C59.074,53.132 60.174,53.627 61.137,54.331 L66.192,51.609 C64.399,50.527 62.158,50.506 60.345,51.554 ZM70.513,51.849 L62.846,55.979 C63.433,56.724 63.893,57.560 64.208,58.454 L64.792,58.146 L74.734,52.782 C73.626,51.725 71.891,51.159 70.513,51.849 ZM78.316,53.418 L65.853,60.139 L64.674,60.761 C64.685,60.941 64.693,61.122 64.693,61.305 C64.693,61.930 64.187,62.436 63.562,62.436 C63.545,62.436 63.528,62.435 63.511,62.435 L54.909,62.046 C52.390,61.932 49.866,62.048 47.368,62.394 C46.757,62.472 46.196,62.047 46.106,61.438 C46.014,60.821 46.440,60.246 47.058,60.154 C49.692,59.790 52.355,59.667 55.012,59.787 L62.321,60.118 C61.746,57.115 59.123,54.943 56.066,54.938 L45.982,54.938 C44.004,54.936 42.052,54.489 40.271,53.630 L39.231,53.127 C33.971,50.568 27.720,51.169 23.044,54.684 L20.569,56.888 C19.377,57.949 17.967,58.737 16.440,59.197 L21.394,75.446 L28.349,71.050 C28.366,71.039 28.384,71.028 28.401,71.019 C29.895,70.180 31.652,69.942 33.316,70.353 L48.437,73.137 C52.051,73.783 55.776,73.266 59.077,71.659 L84.259,54.370 C82.722,52.743 80.284,52.353 78.316,53.418 ZM13.330,83.622 C11.365,83.622 9.772,82.028 9.772,80.063 C9.774,78.098 11.366,76.506 13.330,76.504 C15.296,76.504 16.889,78.097 16.889,80.063 C16.889,82.028 15.296,83.622 13.330,83.622 ZM13.330,78.765 L13.330,78.765 C12.614,78.766 12.034,79.347 12.032,80.063 C12.032,80.780 12.614,81.361 13.330,81.361 C14.047,81.361 14.628,80.780 14.628,80.063 C14.628,79.346 14.047,78.765 13.330,78.765 ZM77.411,40.400 L58.476,40.400 L58.476,42.994 C58.476,43.618 57.969,44.124 57.345,44.124 L29.684,44.124 C29.060,44.124 28.554,43.618 28.554,42.994 L28.554,40.400 L9.588,40.400 C8.964,40.400 8.458,39.893 8.458,39.269 L8.458,31.932 C8.458,27.339 12.120,23.584 16.712,23.467 C17.080,23.458 17.430,23.629 17.649,23.925 C17.904,24.270 18.201,24.582 18.533,24.854 C20.750,26.669 24.019,26.342 25.833,24.125 C26.084,23.819 26.396,23.437 26.997,23.482 C28.361,23.565 29.684,23.981 30.851,24.691 C30.983,24.538 31.119,24.386 31.262,24.240 C32.990,22.463 35.347,21.434 37.826,21.377 C38.195,21.367 38.545,21.538 38.764,21.835 C39.063,22.240 39.412,22.607 39.802,22.926 C42.404,25.055 46.239,24.671 48.368,22.070 C48.654,21.722 48.949,21.368 49.533,21.391 C52.097,21.546 54.490,22.733 56.165,24.681 C57.421,23.919 58.855,23.501 60.323,23.468 C60.691,23.458 61.041,23.629 61.260,23.926 C61.516,24.271 61.813,24.583 62.145,24.854 C64.362,26.669 67.630,26.343 69.445,24.126 C69.696,23.819 70.006,23.438 70.609,23.482 C75.068,23.766 78.539,27.465 78.542,31.932 L78.542,39.269 C78.542,39.893 78.036,40.400 77.411,40.400 ZM27.381,25.793 C27.182,26.017 26.969,26.229 26.743,26.428 C23.657,29.148 18.950,28.851 16.230,25.765 C13.101,26.137 10.738,28.781 10.718,31.932 L10.718,38.139 L28.554,38.139 L28.554,30.887 C28.552,29.387 28.906,27.908 29.587,26.572 C28.909,26.174 28.160,25.909 27.381,25.793 ZM56.215,30.887 C56.212,27.264 53.538,24.198 49.949,23.702 C49.691,23.998 49.412,24.276 49.115,24.532 C45.625,27.547 40.353,27.161 37.339,23.672 C33.646,24.068 30.838,27.173 30.815,30.887 L30.815,41.864 L56.215,41.864 L56.215,30.887 ZM76.281,31.932 C76.278,28.861 74.031,26.251 70.994,25.793 C70.794,26.018 70.580,26.230 70.354,26.429 C67.267,29.149 62.560,28.851 59.840,25.764 C58.990,25.857 58.170,26.129 57.431,26.559 C58.119,27.898 58.477,29.382 58.476,30.887 L58.476,38.139 L76.281,38.139 L76.281,31.932 ZM65.305,23.282 C61.080,23.282 57.654,19.856 57.654,15.631 L57.654,12.370 C57.654,8.145 61.080,4.719 65.305,4.719 C69.531,4.719 72.957,8.145 72.957,12.370 L72.957,15.631 C72.957,19.856 69.531,23.282 65.305,23.282 ZM70.696,12.370 C70.696,12.369 70.696,12.369 70.696,12.368 C70.695,9.391 68.281,6.978 65.304,6.979 C62.327,6.980 59.914,9.393 59.915,12.370 L59.915,15.631 C59.915,15.631 59.915,15.632 59.915,15.633 C59.915,18.610 62.329,21.023 65.306,21.022 C68.283,21.022 70.696,18.608 70.696,15.631 L70.696,12.370 ZM43.555,20.803 C38.816,20.825 34.956,17.002 34.934,12.263 L34.934,8.539 C34.956,3.831 38.767,0.020 43.474,-0.002 C48.213,-0.024 52.073,3.800 52.095,8.539 L52.095,12.263 C52.073,16.971 48.262,20.781 43.555,20.803 ZM49.834,8.539 C49.815,5.075 47.012,2.272 43.549,2.254 C40.059,2.235 37.214,5.049 37.195,8.539 L37.195,12.263 C37.214,15.727 40.017,18.530 43.480,18.548 C46.970,18.567 49.815,15.753 49.834,12.263 L49.834,8.539 ZM21.694,23.282 C17.469,23.282 14.043,19.856 14.043,15.631 L14.043,12.370 C14.043,8.145 17.469,4.719 21.694,4.719 C25.920,4.719 29.345,8.145 29.345,12.370 L29.345,15.631 C29.345,19.856 25.920,23.282 21.694,23.282 ZM27.085,12.370 C27.085,12.369 27.085,12.369 27.085,12.368 C27.084,9.391 24.670,6.978 21.693,6.979 C18.716,6.980 16.303,9.393 16.304,12.370 L16.304,15.631 C16.304,15.631 16.304,15.632 16.304,15.633 C16.304,18.610 18.718,21.023 21.695,21.022 C24.672,21.022 27.085,18.608 27.085,15.631 L27.085,12.370 Z"/>
                            </svg>
                        </div>
                        <div class="text">
                            <h3>Our Values</h3>
                            <p>SmartPay has always been a values-driven organization. These values continue to direct our growth, development and business. The five core SmartPay values that are the foundation of the way we do business are:</p>
                          
                        </div>
                    </div>
                    <div class="SectionBox" uk-scrollspy="cls: uk-animation-slide-right; repeat: false">
                        <div class="icon">
                            <svg viewBox="0 0 82 82">
                                <path fill-rule="evenodd"  fill="rgb(255, 255, 255)" d="M42.439,82.000 C64.253,82.000 82.000,64.253 82.000,42.439 C82.000,20.626 64.253,2.879 42.439,2.879 C36.140,2.879 29.860,4.408 24.276,7.302 L23.746,7.577 L16.649,0.480 C16.338,0.169 15.923,-0.000 15.495,-0.000 C15.378,-0.000 15.260,0.012 15.143,0.038 C14.597,0.157 14.153,0.541 13.958,1.066 L10.457,10.458 L1.065,13.959 C0.540,14.154 0.156,14.597 0.037,15.144 C-0.082,15.691 0.083,16.254 0.479,16.649 L7.576,23.747 L7.301,24.277 C4.407,29.861 2.878,36.141 2.878,42.439 C2.878,64.253 20.625,82.000 42.439,82.000 ZM16.136,4.596 L25.593,14.053 L24.177,21.863 L13.631,11.317 L16.136,4.596 ZM42.439,54.911 C49.316,54.911 54.911,49.316 54.911,42.439 C54.911,35.562 49.316,29.967 42.439,29.967 C39.917,29.967 37.484,30.721 35.405,32.147 L34.845,32.531 L27.551,25.237 L28.264,24.667 C32.275,21.461 37.309,19.696 42.439,19.696 C54.979,19.696 65.182,29.899 65.182,42.439 C65.182,54.980 54.979,65.182 42.439,65.182 C29.898,65.182 19.695,54.980 19.695,42.439 C19.695,37.310 21.461,32.276 24.666,28.265 L25.236,27.551 L32.530,34.846 L32.147,35.405 C30.720,37.485 29.966,39.917 29.966,42.439 C29.966,49.316 35.561,54.911 42.439,54.911 ZM43.596,41.282 L37.151,34.837 L38.123,34.318 C39.446,33.613 40.938,33.240 42.439,33.240 C47.511,33.240 51.638,37.367 51.638,42.439 C51.638,47.511 47.511,51.638 42.439,51.638 C37.366,51.638 33.240,47.511 33.240,42.439 C33.240,40.939 33.612,39.447 34.317,38.124 L34.836,37.151 L41.281,43.596 C41.591,43.905 42.002,44.076 42.439,44.076 C42.876,44.076 43.287,43.905 43.596,43.596 C43.905,43.287 44.075,42.876 44.075,42.439 C44.075,42.002 43.905,41.591 43.596,41.282 ZM4.595,16.136 L11.316,13.632 L21.863,24.178 L14.052,25.594 L4.595,16.136 ZM9.501,27.225 L9.993,26.163 L12.343,28.514 C12.721,28.892 13.263,29.062 13.793,28.967 L20.961,27.668 L20.047,29.208 C17.676,33.205 16.422,37.780 16.422,42.439 C16.422,56.785 28.093,68.456 42.439,68.456 C56.784,68.456 68.456,56.785 68.456,42.439 C68.456,28.094 56.784,16.423 42.439,16.423 C37.780,16.423 33.205,17.676 29.207,20.048 L27.667,20.961 L28.967,13.793 C29.062,13.265 28.893,12.724 28.513,12.344 L26.163,9.994 L27.224,9.502 C31.958,7.310 37.219,6.152 42.439,6.152 C62.448,6.152 78.727,22.430 78.727,42.439 C78.727,62.448 62.448,78.727 42.439,78.727 C22.430,78.727 6.151,62.448 6.151,42.439 C6.151,37.219 7.309,31.958 9.501,27.225 Z"/>
                            </svg>
                        </div>
                        <div class="text">
                            <h3>Our Purpose</h3>
                            <p>Build and Develop the Largest Hyperlocal Network by empowering every Retail stores through state-of-the-art Technology.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div class="" style="margin-top:0px;margin-bottom:0px;background-color: #f6f6f6;">
            <center><h1 style="padding-top:50px;">Our Score Card</h1></center>
            <br>
            <div class="row" style="padding-bottom:50px;">
                <div class="col-sm-2">
                    <center>
                    <img src="{{asset('template_assets/assets/images/icon1.PNG')}}" style="height:75px;width:100px;">
                    <h2>200000+</h2>
                    <p>Retailers</p>
                </div>
                <div class="col-sm-2">
                    <center>
                    <img src="{{asset('template_assets/assets/images/icon2.PNG')}}" style="height:75px;width:100px;">
                    <h2>400000+</h2>
                    <p>Daily Transactions</p>
                </div>
                <div class="col-sm-2">
                    <center>
                    <img src="{{asset('template_assets/assets/images/icon3.PNG')}}" style="height:75px;width:100px;">
                    <h2>99.9%</h2>
                    <p>Success Rate</p>
                </div>
                 <div class="col-sm-2">
                    <center>
                    <img src="{{asset('template_assets/assets/images/icon4.PNG')}}" style="height:75px;width:100px;">
                    <h2>22 CR.+</h2>
                    <p>No. of Customers served</p>
                </div>
                <div class="col-sm-2">
                    <center>
                    <img src="{{asset('template_assets/assets/images/icon5.PNG')}}" style="height:75px;width:100px;">
                    <h2>28</h2>
                    <p>Stats</p>
                </div>
               
                <div class="col-sm-2">
                    <center>
                    <img src="{{asset('template_assets/assets/images/icon6.PNG')}}" style="height:75px;width:100px;">
                    <h2>8</h2>
                    <p>Union Territories</p>
                </div>
            </div>
        </div>


            
        <!-- <p >We at Crazeshope believe in high quality and exceptional customer service. But most importantly, we believe shopping should be  right, not a luxury,</p>
        <p>So, we strive to deliver the best products at the most affordable prices, and ship them to you regardless of where you are located.</p> -->

    </div>
<div style="margin-top:3px;">
@include('website.footer')
</div>