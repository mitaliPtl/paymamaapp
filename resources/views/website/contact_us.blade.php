 @include('website.header')
<div class="blank-space"></div>
 <section class="BannerMain" style="margin-top:-100px;">
            <div class="BannerInner contactusBanner" style="background-image: url({{ asset('/template_assets/assets/images/background/contact-banner.png') }}); background-size:100% 100% !important">
                <div class="Wrapper">
                    <div class="PageHeading"> 
                        <div class="heading">
                            <h2 style="font-size: 64px !important;color:white;font-family: 'Cambria-Regular'" uk-scrollspy="cls: uk-animation-slide-bottom; repeat: false">Contact Us</h2>
                            <p style="font-size:20px;color:white;font-weight:200;" uk-scrollspy="cls: uk-animation-slide-bottom; repeat: false">Become a business partner!! <br>We’re here.</p>
                            
                        </div>
                  <!--<div class="social-icon">
                                <a href="https://www.facebook.com/smartpayindia/">
                                    <img src="{{ asset('template_assets/assets/images/fb.PNG') }}" alt="" class="img-fluid">
                                </a>
                                <a href="https://instagram.com/smartpay_india?igshid=nk7ih6odsivx">
                                    <img src="{{ asset('template_assets/assets/images/instagram.PNG') }}" alt="" class="img-fluid">
                                </a>
                                <img src="{{ asset('template_assets/assets/images/linkedin.PNG') }}" alt="" class="img-fluid">
                                <img src="{{ asset('template_assets/assets/images/twitter.PNG') }}" alt="" class="img-fluid">

                            </div>-->
        </section>
 <section class="ContactForm">
            <div class="Wrapper">
                <div class="row">
                    <div class="col-sm-5">
                        <h3 style="font-family: 'Cambria-Regular';
    font-size: 60px !important;
    color: #000;
    line-height: 59px;
    text-align: center;
    margin-bottom: 20px; ">We want to <br>hear from you</h3>
                        <p style="font-size: 27px;
    color: #2b2b2b;
    margin: 0;
    text-align: center;">Come be a part of Growth...<br> as<br> “Growth Drives Everyone”</p>
    <br><br>

                        <div class="details jz">
                            <ul uk-scrollspy="cls: uk-animation-slide-bottom;target: li; repeat: false">
                                <li>
                                    <span></span>
                                    <div class="text">
                                        <h4 style="font-family: 'Cambria-Regular';font-size: 24px;color: #000;   margin-bottom: 20px;">Regd. Office:</h4>
                                        <p style="line-height: 30px;font-size:19px;">SH-4, Gv Complex, Opp Word & Deed School, Hayathnagar, R.R Dist -501505, Telangana, India</p>
                                    </div>
                                </li>
                                <li>
                                    <span></span>
                                    <div class="text">
                                        <h4 style="font-family: 'Cambria-Regular';font-size: 24px;color: #000;   margin-bottom: 20px;">Customer Care:</h4>
                                        <p style="line-height: 30px;font-size:19px;"><a  style="line-height: 30px;font-size:19px;"  href="tel:+918374913154"> 040-29563154</a></p>
                                    </div>
                                </li>
                                <style>
                                    .jz ul li.last:after{
                                        display:none;
                                    }
                                </style>
                                <li class="last">
                                    <span></span>
                                    <div class="text">
                                        <h4 style="font-family: 'Cambria-Regular';font-size: 24px;color: #000;   margin-bottom: 20px;">Email:</h4>
                                        <p style="line-height: 30px;font-size:16px;"><a  style="line-height: 30px;font-size:19px;"  href="mailto:
hello@paymamaapp.in">
hello@paymamaapp.in</a></p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-7">
                        <style type="text/css">
                            section.BannerMain .BannerInner{
                                height:450px;
                            }
                        </style>
                        <div class="FormBox" style="margin-top:-234px;padding:0px;
  border-radius: 25px;">
                                  <div class="" style="background-color: #f2f2f2;padding:35px;border-radius:30px 30px 0px 0px">
                                  <h3  style="font-family: 'Cambria-Regular';
    font-size: 30px;
    color: #2b2b2b;
    line-height: 30px;"><img src="{{asset('template_assets/assets/images/icon/contact-icon.PNG')}}" style="height:64px;width:66px;">&nbsp;&nbsp;&nbsp;Send us your inquiry</h3>
                                  </div>
                            <div class="FormInner" style="padding:30px;background-color: white;">
                                <form name="contact" id="contact" class="ContactFormMain" method="post" action="inquirysubmit">
                                    <div class="row" id="messageContact" style="display: none;"></div>
                                    <div class="row" >
                                        @csrf
                                        <div class="col-lg-12 col-xl-6 form-group">
                                            <input type="text" name="name" id="Yourname" placeholder="Full Name" class="form-control" />
                                            
                                        </div>
                                        <div class="col-lg-12 col-xl-6 form-group">
                                            <input type="text" name="number" id="UserPhone" onKeyPress="return isNumberKey(event)" placeholder="Mobile Number" class="form-control" />
                                            
                                        </div>
                                    </div>
                                    <div class="row" >
                                        <div class="col-lg-12 col-xl-6 form-group">
                                            <input type="email" name="email" id="UserEmail" placeholder="Email address" class="form-control" />
                                            
                                        </div>
                                        <div class="col-lg-12 col-xl-6 form-group">
                                            <select class="form-control" name="identity" id="identity">
                                                <option value="" selected>Your Identity</option>
                                                <option value="Distributor">Distributor</option>
                                                <option value="Retailer">Retailer</option>
                                                <option value="Alliance">Alliance</option>
                                                <option value="Others">Others</option>
                                            </select>
                                            <span class="dropArrow"></span>
                                        </div>
                                    </div>
                                    <div class="row" >
                                        <div class="col-md-12 form-group">
                                            <textarea class="form-control" name="message" id="Message" placeholder="Message" rows="10"></textarea>
                                            
                                        </div>
                                    </div>
                                    <div class="buttonRow form-group p-0 mb-2">
                                        <button class="button Primary btn btn-primary" type="submit" name="btnsignincontact" id="btnsignincontact" value="Send Message!" style="background-color: #d40c2f;color:white;border-radius: 36px;width:100%;padding-top:20px;padding-bottom:20px;font-size:22px;">Send Message</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
         <style>
            section.BannerMain .contactusBanner
            {
                height:500px !important;
            }
          .jz  ul li {
    display: -webkit-box;
    display: -webkit-flex;
    display: -moz-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-pack: Flex-start;
    -ms-flex-pack: Flex-start;
    -webkit-justify-content: Flex-start;
    -moz-justify-content: Flex-start;
    justify-content: Flex-start;
    -webkit-box-align: Flex-start;
    -ms-flex-align: Flex-start;
    -webkit-align-items: Flex-start;
    -moz-align-items: Flex-start;
    align-items: Flex-start;
    margin-bottom: 30px;
    position: relative;
}
.jz ul li:after {
    content: '';
    position: absolute;
    top: 35px;
    left: 15px;
    width: 4px;
    height: 100%;
    background-color: #96b2c5;
}

.jz ul li span {
    width: 35px;
    height: 35px;
    min-width: 35px;
    background-color: #d40b2e;
    -webkit-border-radius: 100px;
    -moz-border-radius: 100px;
    -ms-border-radius: 100px;
    -o-border-radius: 100px;
    border-radius: 100px;
    position: relative;
    margin-right: 20px;
}
           .jz ul li span:after {
    content: '';
    position: absolute;
    width: 15px;
    height: 15px;
    background-color: #d40b2e;
    -webkit-border-radius: 100px;
    -moz-border-radius: 100px;
    -ms-border-radius: 100px;
    -o-border-radius: 100px;
    border-radius: 100px;
    box-shadow: 0px 2px 2px 0px rgb(0 0 0 / 20%);
    border: 3px solid #fff;
    margin-top: 10px;
    margin-left: 10px;
}
            section.ContactForm {
    
    background-color: #f2f2f2;
}
            .form-control {
    width: 100%;
    padding: 15px 20px;
    border: 1px solid #f2f2f2;
    height: 72px;
    background-color: #f2f2f2;
    font-size: 20px;
    color: #2b2b2b;
    position: relative;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    -ms-border-radius: 5px;
    -o-border-radius: 5px;
    border-radius: 5px;
    outline: none;
    box-shadow: none;
    transition: 0.2s;
    transition-timing-function: ease;
    transition-timing-function: cubic-bezier(0.25, 0.1, 0.25, 1);
}
            section.ContactForm .contactDetails .RightForm .FormBox .FormInner {
                padding: 30px;
            }

            section.ContactForm .contactDetails .RightForm .FormBox {
                background-color: #fff;
                -webkit-border-radius: 20px;
                -moz-border-radius: 20px;
                -ms-border-radius: 20px;
                -o-border-radius: 20px;
                border-radius: 20px;
                box-shadow: 0px 4px 9px 0px rgb(0 0 0 / 10%);
                overflow: hidden;
                margin-top: 425px;
            }
            section.ContactForm .contactDetails .RightForm .FormBox .heading {
                background-color: #f2f2f2;
                padding: 35px;
            }
            .Wrapper {
                width: 94%;
                max-width: 1500px;
                margin: 0 auto;
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
                text-align: left;
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
            p{
                font-family: "Montserrat",sans-serif !important;
                letter-spacing: 1.5px;
                line-height: 35px;
            }
            h3{
                font-family: "Montserrat",sans-serif !important;
                letter-spacing: 1.5px;
                line-height: 35px;
            }
        </style>
        <div class="">
        <div class="row" style="padding:20px;">
            <div class="">
                <br>
                <center>
                    <br>
                        <h3 style="padding-left:93px;font-family: 'Cambria-Regular';font-size: 52px;color: #2b2b2b;text-align: center;
                        margin: 0;">
                    We are Located here</h3>
                    <p style="text-align: center;padding-left:327px;    font-size: 18px;
    color: rgba(43,43,43,0.7);">PayMama is a growth oriented, forward looking and fast paced technology and marketing
solution provider.</p>
                    <br>
                    </center>
                    </div>
                    <br>
                    <br>
                    <!--<div class="content">
                        <p>MasterPay is a growth oriented, forward looking and fast paced technology and marketing<br> solution provider.</p>
                    </div-->            

                    <div class="col-sm-6">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3808.904123234229!2d78.61223931487538!3d17.320174488118944!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTfCsDE5JzEyLjYiTiA3OMKwMzYnNTEuOSJF!5e0!3m2!1sen!2sin!4v1633077146723!5m2!1sen!2sin" frameborder="0" style="margin-top:30px;width:100%;height:400px;border:0" allowfullscreen></iframe>

                        <br>
                        <h4 style="font-family: 'Cambria-Regular';
    font-size: 34px;margin-top: 30px;">Head Office:</h4>
    <br>
                        <p style="font-size: 18px;color: #2b2b2b;line-height: 32px;">SH-4, Gv Complex, Opp Word & Deed School, Hayathnagar, R.R Dist -501505, Telangana, India</p>

                                        <p style="font-size: 18px;color: #2b2b2b;line-height: 32px;"><i class="fa fa-phone"></i>&nbsp;<a href="tel:040-29563154" style="color:black;">040-29563154</a></p>
                                         <p style="font-size: 18px;color: #2b2b2b;line-height: 32px;"><i class="fa fa-phone"></i>&nbsp;<a href="tel:+918374913154" style="color:black;">+91 837 491 3154</a></p>
                    </div>
                    <div class="col-sm-6">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3721.8613447088314!2d79.03864821424685!3d21.118093390100064!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bd4bf7ec6441f07%3A0xc4f014774bb66046!2sDolphin%20Techno!5e0!3m2!1sen!2sin!4v1632977065293!5m2!1sen!2sin" frameborder="0" style="margin-top:30px;width:100%;height:400px;border:0" allowfullscreen></iframe>
                        
                                        <br>
                        <h4 style="font-family: 'Cambria-Regular';
    font-size: 34px;margin-top:30px;">Nagpur office</h4>
    <br>
                        <p style="font-size: 18px;color: #2b2b2b;line-height: 32px;">94, Parekh Layout, Dronacharya Nagar,
                        Trimurti Nagar Square, Nagpur, India</p>
                        <p style="font-size: 18px;color: #2b2b2b;line-height: 32px;"><i class="fa fa-phone"></i>&nbsp;<a href="tel:+919561340134" style="color:black;">+91 956 134 0134</a></p>
                        <p style="font-size: 18px;color: #2b2b2b;line-height: 32px;"><i class="fa fa-phone"></i>&nbsp;<a href="tel:+919028012266" style="color:black;">+91 902 801 2266</a></p>
                    </div>
        </div>
    </div>
    <section class="OurPartnersSection" style="background-color: #f2f2f2;padding-top:40px; padding-bottom:50px;">
            <div class="Wrapper">
                <br>
                <div class="SingleLineHeading" uk-scrollspy="cls: uk-animation-slide-bottom; repeat: false">
                    <h3>Our Partners</h3>
                </div>
                <br><br>
                    <div class="">
                    <div class=""></div>
                   <div class="">
                        <img src="{{asset('template_assets/assets/images/sbi.png')}}" style="width:270px;height:138px;padding-right: 10px;padding-left: 20px;" alt="img">
                        <img src="{{asset('template_assets/assets/images/paytem.png')}}" style="width:270px;height:138px;padding-right: 10px;"  alt="img">
                         <img src="{{asset('template_assets/assets/images/axis.png')}}"  style="width:270px;height:138px;padding-right: 10px;" alt="img">
                          <img src="{{asset('template_assets/assets/images/fino.png')}}" style="width:270px;height:138px;padding-right: 10px;"  alt="img">
                           <img src="{{asset('template_assets/assets/images/airtel-payment-bank.png')}}" style="width:270px;height:138px;"  alt="img">
                    </div>
                    
                    <div class=""></div>
                </div>
                
            </div>
            <br>
        </section>
</div>
<div style="margin-top:3px;">
@include('website.footer')
</div>