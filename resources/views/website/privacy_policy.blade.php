@include('website.header')
<div class="blank-space"></div>
<style>
    section.BannerMain .BannerInner{
        height:440px !important;
    }
</style>
<section class="BannerMain" style="margin-top:-100px;">
            <div class="BannerInner" style="background-image: url({{ asset('/template_assets/assets/images/privacy-banner.png') }}">
                <div class="Title" uk-scrollspy="cls: uk-animation-slide-bottom; target: h2; repeat: false">
                    <h2>Privacy Policy</h2>
                    <p class="terms"  style="text-align:center;font-size:21px;color:white !important;" uk-scrollspy="cls: uk-animation-slide-bottom; repeat: false">We respect your Privacy</p>
                </div>
            </div>
        </section>
        <style>
            h4{
                font-family: "Cambria-Bold";
                font-weight: 600 !important;
                color:#000;
            }
            h3{
                font-family: "Cambria-Bold";
                font-weight: 600 !important;
                color:#000;
            }
            .terms{
                text-align: justify;
                font-size: 17px;
    color: rgba(43,43,43,0.8) !important;
    line-height: 30px;
    font-weight: 600 !important;
    font-family:'Montserrat',sans-serif;
    margin: 20px 0;letter-spacing: 0.5px;
            }
            
            @media only screen and (max-width: 767px)
            {
                section.BannerMain .BannerInner 
                {
                    height: 300px;
                }
            }
            ul li.terms::before {
    content: '';
    position: absolute;
    top: 12px;
    right:10px;
    left: 0;
    width: 6px;
    height: 6px;
    background: #d40b2e;
}
            section.BannerMain .BannerInner .Title h2 {
                font-family: "Cambria-Regular";
                font-size: 64px;
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
<div class="" style="padding-left:30px;padding-right:30px;padding-top:50px;padding-bottom:50px;">

        <h1 style="font-family: 'Cambria-Regular';
    font-size: 40px;
    color: #000;
    margin-bottom: 50px;
    text-align: center;">Privacy Policy</h1>
        <hr>

        <p class="terms"  style="font-size: 17px;
    color: rgba(43,43,43,0.8);
    line-height: 30px;
    font-weight: 500;
    margin: 20px 0;">
        Naidu Software Technologies Private Limited (“NSTPL”), having registered office at SH-4, GV Complex, Opp Word & Deed School, Hayathnagar, R.R Dist -501505,Telangana. and corporate office at Shop No -4, GV Complex, Opp Word & Deed School, Hayathnagar, R.R Dist - 501505,Telangana.hereinafter referred to as PayMama. PayMama, values Your trust & respect Your privacy. This Privacy Policy provides You with details about the manner in which Your data is collected, stored & used by us. You are advised to read this Privacy Policy carefully and learn about our information gathering and dissemination practices. By visiting PayMama's website https://paymamaapp.in applications, you expressly give us consent to use & disclose your personal information in accordance with this Privacy Policy. If You do not agree to the terms of the policy, please do not use or access PayMama website or mobile applications.

        </p>
        <p class="terms" >Note: Our privacy policy may change at any time without prior notification. To make sure that you are aware of any changes, kindly review the policy periodically. This Privacy Policy shall apply uniformly to PayMama desktop website and PayMama mobile applications.</p>

        <h3>GENERAL:</h3>
        <p class="terms"  style="">
           PayMama will not sell, share or rent Your personal information to any 3rd party or use Your email address/mobile number for unsolicited emails and/or SMS. Any emails and/or SMS sent by PayMama will only be in connection with the provision of agreed services & products and this Privacy Policy. Periodically, PayMama may reveal general statistical information about PayMama & its users, such as number of visitors, number and type of goods and services purchased, etc. PayMama reserves the right to communicate Your personal information to any third party that makes a legally compliant request for its disclosure.
        </p>

        <h3>PERSONAL INFORMATION:</h3>
        <p class="terms" >
            Personal Information means and includes all information that can be linked to a specific individual or to identify any individual, such as name, address, telephone number, email ID, credit card number, cardholder name, card expiration date, information about Your mobile phone, DTH service, data card, electricity connection and any details that may have been voluntarily provided by the user in connection with availing any of the services on PayMama Wallet. When You browse through PayMama, PayMama may collect information regarding the domain and host from which You access the internet, the Internet Protocol [IP] address of the computer or Internet service provider [ISP] You are using, and anonymous site statistical data.
        </p>

        <h4>USE OF PERSONAL INFORMATION</h4>
        <p class="terms" >
            PayMama uses personal information to provide You with services & products You explicitly requested for, to resolve disputes, troubleshoot concerns, help promote safe services, collect money, measure consumer interest in our services, inform You about offers, products, services, updates, customize Your experience, detect & protect us against error, fraud and other criminal activity, enforce our terms and conditions, etc. PayMama also uses Your contact information to send You offers based on Your previous orders and interests.
            We may disclose personal information if required to do so by law or in the good faith belief that such disclosure is reasonably necessary to respond to subpoenas, court orders, or other legal process.
.
        </p>

        <h4>COOKIES</h4>
        <p class="terms" >
            A “cookie” is a small piece of information stored by a web server on a web browser so it can be later read back from that browser. Cookies are useful for enabling the browser to remember information specific to a given user. For instance, we use cookies to help us remember and process the items in your shopping cart. They are also used to help us understand your preferences based on previous or current site activity, which enables us to provide you with improved services. We also use cookies to help us compile aggregate data about site traffic and site interaction so that we can offer better site experiences and tools in the future.
        </p>
        
        <h4>We use cookies to:</h4>
        
            <ul>

           <li class="terms" >&nbsp;&nbsp;Help remember and process the transaction through the PayMama wallet.</li>
          <li class="terms" >&nbsp;&nbsp;Understand and save user's preferences for future visits.</li>
           <li class="terms" >&nbsp;&nbsp;Compile aggregate data about site traffic and site interactions in order to offer better site experiences and tools in the future. We may also use trusted third party services that track this information on our behalf.</li>
             <p class="terms" >     You can choose to have your computer warn you each time a cookie is being sent, or you can choose to turn off all cookies. You do this through your browser (like Internet Explorer) settings. Each browser is a little different, so look at your browser's Help menu to learn the correct way to modify your cookies.
                  If users disable cookies in their browser:
                  If you disable cookies off, some features will be disabled. It will turn off some of the features that make your site experience more efficient and some of our services will not function properly.

        </p>
        <h4>LINKS TO OTHER SITES/APPS</h4>
        <p class="terms" >
            Occasionally, at our discretion, we may include or offer third party products or services on our website. These third-party sites have separate and independent privacy policies. PayMama therefore has no responsibility or liability for the content and activities of these linked sites. Nonetheless, we seek to protect the integrity of our site and welcome any feedback about these sites.
        </p>

        <h4>SECURITY</h4>
        <p class="terms" >
           PayMama has stringent security measures in place to protect the loss, misuse, and alteration of the information under our control. Whenever You change or access Your account information, PayMama offers the use of a secure server. Once Your information is in our possession PayMama adheres to strict security guidelines, protecting it against unauthorized access.
        </p>

        <!--<p class="terms" >
            If you visit our login page, we will set a temporary cookie to determine if your browser accepts cookies. This cookie contains 
            no personal data and is discarded when you close your browser.
        </p>-->

        <h4>CONSENT:</h4>
        <p class="terms" >
            By using PayMama and/or by providing Your information, You consent to the collection and use of the information You disclose on PayMama in accordance with this Privacy Policy, including but not limited to Your consent for sharing Your information as per this privacy policy.
        </p>
        </div>
<div style="margin-top:3px;">
@include('website.footer')
</div>
