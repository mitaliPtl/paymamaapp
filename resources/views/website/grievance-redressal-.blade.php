@include('website.header')
<div class="blank-space"></div>
<style>
    section.BannerMain .BannerInner{
        height:440px !important;
    }
</style>
<section class="BannerMain" style="margin-top:-100px;">
            <div class="BannerInner" style="background-image: url({{ asset('/template_assets/assets/images/background/grievance-redressal-banner.png') }}">
                <div class="Title" uk-scrollspy="cls: uk-animation-slide-bottom; target: h2; repeat: false">
                    <h2>Grievance Redressal Policy</h2>
                    
                </div>
            </div>
        </section>
          <style>
            td{
                font-size:17px;
            }
             .termsh4{
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
    text-align: center;">Grievance Redressal Policy</h1>
        <hr>
        <br>
        <h3 class="termsh4">Objectives and Scope</h3>
        <p class="terms"  style="font-size: 17px;
    color: rgba(43,43,43,0.8);
    line-height: 30px;
    font-weight: 500;
    margin: 20px 0;">
        Naidu Software Technologies Private Limited. is committed towards its customers and has put together the undermentioned Grievance Redressal Policy for an effective resolution of all complaints received by PayMama.

        </p>

        <p class="terms">
            This policy outlines a structured grievance redressal mechanism available to customers and external partners for escalating their complaints to obtain a resolution. This policy covers all the products/services offered by PayMama. It aims to improve customer experience and service delivery by an effective resolution of customer complaints through proper channelized approach, review mechanism and prompt resolution of all customer grievances. PayMama strives to provide best-in-class service delivery and has a dedicated customer care team that operates 24X7 for addressing such complaints.
        </p>
       <h3 class="termsh4">Redressal Matrix</h3>
      <div class="table-responsive-md">
                    <table class="tableui table" width="100%" cellpadding="0" cellspacing="0">
                        <thead>
                            <tr>
                                <td>Type</td>
                                <td>Name &amp; Contact Details</td>
                                <td>Remarks</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="nowrap">Level 1</td>
                                <td>
                                    <strong>PayMama Customer Care</strong><br>
                                    040-29563154<br>
                                    <a href="mailto:customercare@paymamaapp.in">
                                        customercare@paymamaapp.in
                                    </a>
                                </td>
                                <td>PayMama Customer Care team will acknowledge the grievance/complaint with a ticket number and an expected resolution time on the receipt of complaint. If a case needs extra time, PayMama will inform the same along with reasons to the customer</td>
                            </tr>
                            <tr>
                                <td class="nowrap">Level 2</td>
                                <td>
                                    <strong>Mr. Hemanth Thati</strong><br>
                                    Head – Customer Experience<br>
                                    <a href="mailto:hemanthnaidu.thati@paymamaapp.in">
                                        hemanthnaidu.thati@paymamaapp.in
                                    </a>
                                </td>
                                <td>In case Level 1 resolution is not satisfactory, customer can escalate the same to Customer experience head along with original ticket number mandatorily. First response – 2 business days. Final response – as per complaint type</td>
                            </tr>
                            <tr>
                                <td class="nowrap">Level 3</td>
                                <td>
                                    <strong>Mr. Srikanth Thati</strong><br>
                                    Grievance Officer<br>
                                    <a href="mailto:Srikanthnaidu.thati@paymamaapp.in">
                                        Srikanthnaidu.thati@paymamaapp.in
                                    </a>
                                </td>
                                <td>In case Level 2 resolution is not satisfactory, customer can escalate the same to Grievance Team along with original ticket number mandatorily. First response – 2 business days. Final response – as per complaint type</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
        </div>
<div style="margin-top:3px;">
@include('website.footer')
</div>
