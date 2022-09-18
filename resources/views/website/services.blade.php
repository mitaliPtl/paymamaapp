@include('website.header')
<div class="blank-space"></div>
<style>
    section.BannerMain .BannerInner{
        height:500px !important;
    }
</style>
<section class="BannerMain" style="margin-top:-100px;">
            <div class="BannerInner" style="background-image: url({{ asset('/template_assets/assets/images/background/services.png') }}">
                <div class="Title" uk-scrollspy="cls: uk-animation-slide-bottom; target: h2; repeat: false">
                    <h2>DIGITAL TRANSFORMATION</h2>
                    <p style="text-align:center;font-size:18px;color:white !important;" uk-scrollspy="cls: uk-animation-slide-bottom; repeat: false">Live Your Idea With Digital Innovation</p>
                </div>
            </div>
        </section>
        <style>
            h3{
                font-family: "Cambria-Bold";
                font-size:32px !important;
                font-weight: 600 !important;
            }
            p{
                font-size: 17px !important;
    color: #2b2b2b !important;
    line-height: 35px !important;
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
    <br>

        <section class="AboutContent">
            <div class="Wrapper" style="width:100%;">
                <div class="SingleLineHeading" uk-scrollspy="cls: uk-animation-slide-bottom; repeat: false">
                    <h3 style="color:#d40b2e;font-size:16px;">Our Services</h3><br>
                </div>
                 <div class="Title" style="text-align:center;">
                        <h2 style="font-family: 'Cambria-Regular';
    font-size: 52px;
    color: #2b2b2b;">One App - Multiple Services, Superior Margins,<br/>More Earnings</h2>
                    </div>
                    <div class="content" style="text-align:center;">
                        <p style="text-align:center;letter-spacing: 0.5px;">At PayMama, we give not just the Best but Legendary satisfaction to our Customers,<br>through Service Excellence.</p>
                    </div>
                     <div class="row" style="background-color:rgba(209, 211, 246, 0.7);padding:30px;margin-top:40px;">
                        <div class="col-sm-3">
                         <img src="{{asset('template_assets/assets/images/services/recharges.jpg')}}" alt="img" style="width:100%;height:550px;"> 
                        </div>
                        <div class="col-sm-9">
                             <div class="Title">
                                    <h3>Mobile / DTH Recharge</h3>
                                    <p style="text-align:justify">Recharges made simple and easy</p>
                                </div>
                                <div class="Text">
                                    <p style="text-align:justify" class="mb-4">Mobile and DTH Recharges being a day to day requirement, a PayMama retailer can provide Mobile and DTH recharge services of all the Operators in India to their customers without any hassles.</p>
                                    <p style="text-align:justify" >Customers can just walk in and get their mobiles/DTH recharged easily in a secure way using the service provided at PayMama Retail store. While PayMama retailers get to provide their customers with more services; they also earn an extra income by rendering these services.</p>
                                </div>
                        </div>
                    </div>
                    <div class="row" style="background-color: rgba(246, 214, 214, 0.7);padding:30px;margin-top:40px;">
                        <div class="col-sm-3">
                         <img src="{{asset('template_assets/assets/images/services/bill payment.jpg')}}"  style="width:100%;height:550px;" alt="img"> 
                        </div>
                        <div class="col-sm-9">
                             <div class="Title">
                                    <h3>Bill Payments</h3>
                                    <p style="text-align:justify">Bill Payments made simple and easy</p>
                                </div>
                                <div class="Text">
                                    <p style="text-align:justify" class="mb-4">Utility Bill Payments being a day to day requirement, a PayMama retailer can provide Utility Bill Payment services of all the Operators in India to their customers without any hassles.</p>
                                    <p style="text-align:justify" >Customers can just walk in  and get their electricity, water, gas, mobile, etc. bills paid easily in a secure way using the services provided at PayMama Retail store. While PayMama retailers get to provide their customers with more services; they also earn an extra income by rendering these services.</p>
                                </div>
                        </div>
                    </div>
                    <div class="row" style="padding:30px;margin-top:40px;background-color: rgba(186, 228, 244, 0.7);">
                        <div class="col-sm-3">
                         <img src="{{asset('template_assets/assets/images/services/bhimupi.jpg')}}"  style="width:100%;height:550px;" alt="img"> 
                        </div>
                        <div class="col-sm-9">
                            <div class="Title">
                                    <h3>Domestic Money Transfer</h3>
                                    <p style="text-align:justify">Money Transfers made Simple, Swift and Safe- anytime, anywhere</p>
                                </div>
                                <div class="Text">
                                    <p style="text-align:justify" class="mb-4">Now, transferring money has become safe, easier and simple than never before. The Customers do not need to visit Bank branches for transferring money, they just need to visit their nearest PayMama Retail store! PayMama Retailer can meet the remittance need of migrants, unbanked and underbanked population of India with Money Transfer facility anytime of the day.
                                    </p>
                                    <p style="text-align:justify" >Through PayMama’s Money Transfer Service, the Retailers can help their Customers to transfer money to any bank account in India, instantly at anytime, anywhere even after banking hours and on bank holidays.The sender do not need to have a bank account to transfer money to his near and dear ones, he just needs to pay a minimal transaction fee to the Retailer to execute the transaction.</p>
                                </div>
                        </div>
                    </div>
                     <div class="row" style="background-color: rgb(249, 217, 217);padding:30px;margin-top:40px;">
                        <div class="col-sm-3">
                          <img src="{{asset('template_assets/assets/images/services/bhimupi.jpg')}}"  style="width:100%;height:550px;" alt="img"> 
                        </div>
                        <div class="col-sm-9">
                             <div class="Title">
                                    <h3>BHIM UPI TRANSFER</h3>
                                    <p style="text-align:justify">Banking Services made easy – anytime, anywhere</p>
                                </div>
                                <div class="Text">
                                    <p style="text-align:justify" class="mb-4">Now, withdrawing cash from your bank account has become safe and easier than never before. The Customers do not need to visit Bank branches or ATMs for basic banking functions like cash withdrawal or balance enquiry. They just need to visit their nearest PayMama Retail store! PayMama Retailers can provide their customers with Micro ATM Services at his Store with ease and convenience.</p>
                                    <p style="text-align:justify">Through PayMama’s MATM Service, the Retailers can help their Customers to conduct banking transactions.</p>
                                </div>
                        </div>
                    </div>
                    <div class="row" style="background-color: rgba(235, 202, 217, 0.7);padding:30px;margin-top:40px;">
                        <div class="col-sm-3">
                          <img src="{{asset('template_assets/assets/images/services/aeps.jpg')}}"  style="width:100%;height:550px;" alt="img"> 
                        </div>
                        <div class="col-sm-9">
                             <div class="Title">
                                    <h3>AEPS</h3>
                                    <p style="text-align:justify">Banking Services made easy – anytime, anywhere</p>
                                </div>
                                <div class="Text">
                                    <p style="text-align:justify" class="mb-4">Now, withdrawing cash from your bank account has become safe and easier than never before. The Customers do not need to visit Bank branches or ATMs for basic banking functions like cash withdrawal or balance enquiry. They just need to visit their nearest PayMama Retail store! PayMama Retailer can provide their customers with Aadhaar Banking service through AEPS(Aadhaar Enabled Payment System) powered digital banking service.</p>
                                    <p style="text-align:justify" >Through PayMama’s Aadhaar Banking Service, the Retailers can help their Customers to conduct banking transactions with their Aadhaar number and  finger authentication.</p>
                                </div>
                        </div>
                    </div>
                    <div class="row" style="background-color: rgba(235, 202, 217, 0.7);padding:30px;margin-top:40px;">
                        <div class="col-sm-3">
                          <img src="{{asset('template_assets/assets/images/services/aeps.jpg')}}"  style="width:100%;height:550px;" alt="img"> 
                        </div>
                        <div class="col-sm-9">
                             <div class="Title">
                                    <h3>Aadhaar Pay</h3>
                                    <p style="text-align:justify">Banking Services made easy – anytime, anywhere</p>
                                </div>
                                <div class="Text">
                                    <p style="text-align:justify" class="mb-4">Now, withdrawing cash from your bank account has become safe and easier than never before. The Customers do not need to visit Bank branches or ATMs for basic banking functions like cash withdrawal or balance enquiry. They just need to visit their nearest PayMama Retail store! PayMama Retailer can provide their customers with Aadhaar Banking service through AEPS(Aadhaar Enabled Payment System) powered digital banking service.</p>
                                    <p style="text-align:justify" >Through PayMama’s Aadhaar Banking Service, the Retailers can help their Customers to conduct banking transactions with their Aadhaar number and  finger authentication.</p>
                                </div>
                        </div>
                    </div>
                    <div class="row" style="background-color: rgba(201, 232, 219, 0.7);padding:30px;margin-top:40px;">
                        <div class="col-sm-3">
                         <img src="{{asset('template_assets/assets/images/icici_bank_logo_symbol.png')}}"  style="margin-top:50px;width:100%;height:100px;" alt="img">  
                        </div>
                        <div class="col-sm-9">
                               <div class="Title">
                                    <h3>ICICI Cash Deposit</h3>
                                    <p style="text-align:justify">Flight Bookings made simple and easy</p>
                                </div>
                                <div class="Text">
                                    <p style="text-align:justify" class="mb-4">PayMama retailer can provide Flight Booking services to their customers at competitive rates and great commissions without any hassles.</p>
                                    <p style="text-align:justify" >Customers can just walk in  and get their travel bookings done easily in a secure way using the services provided at PayMama Retail store. While PayMama retailers get to provide their customers with more services; they also earn an extra income by rendering these services.</p>
                                </div>
                        </div>
                    </div>
                    <div class="row" style="background-color: rgba(196, 228, 238, 0.75);padding:30px;margin-top:40px;">
                        <div class="col-sm-3">
                          <img src="{{asset('template_assets/assets/images/services/busflight.jpg')}}"  style="width:100%;height:550px;" alt="img">  
                        </div>
                        <div class="col-sm-9">
                              <div class="Title">
                                    <h3>Bus & Flight Ticket</h3>
                                    <p style="text-align:justify">Bus & Flight Bookings made simple and easy</p>
                                </div>
                                <div class="Text">
                                    <p style="text-align:justify" class="mb-4">Travel bookings being a basic requirement, a PayMama retailer can provide Bus Booking services of the comprehensive range of Bus Operators in India to their customers without any hassles.</p>
                                    <p style="text-align:justify" >Customers can just walk in  and get their travel bookings done easily in a secure way using the services provided at PayMama Retail store. While PayMama retailers get to provide their customers with more services; they also earn an extra income by rendering these services.</p>
                                </div>
                        </div>
                    </div>
                    <div class="row" style="background-color: rgb(249, 217, 217);padding:30px;margin-top:40px;">
                        <div class="col-sm-3">
                           <img src="{{asset('template_assets/assets/images/services/qrcodepayment.jpg')}}"  style="width:100%;height:550px;" alt="img"> 
                        </div>
                        <div class="col-sm-9">
                             <div class="Title">
                                    <h3>QR Code Payments</h3>
                                    <p style="text-align:justify">Banking Services made easy – anytime, anywhere</p>
                                </div>
                                <div class="Text">
                                    <p style="text-align:justify" class="mb-4">Now, withdrawing cash from your bank account has become safe and easier than never before. The Customers do not need to visit Bank branches or ATMs for basic banking functions like cash withdrawal or balance enquiry. They just need to visit their nearest PayMama Retail store! PayMama Retailers can provide their customers with Micro ATM Services at his Store with ease and convenience.</p>
                                    <p style="text-align:justify">Through PayMama’s MATM Service, the Retailers can help their Customers to conduct banking transactions.</p>
                                </div>
                        </div>
                    </div>
                    
                    <div class="row" style="background-color: rgba(247, 229, 217, 0.7);padding:30px;margin-top:40px;">
                        <div class="col-sm-3">
                          <img src="{{asset('template_assets/assets/images/services/loadwallet.jpg')}}"  style="width:100%;height:550px;" alt="img"> 
                        </div>
                        <div class="col-sm-9">
                             <div class="Title">
                                    <h3>Wallet Load</h3>
                                    <p style="text-align:justify">Cash Management Services made simple and easy</p>
                                </div>
                                <div class="Text">
                                    <p style="text-align:justify" class="mb-4">Now, payments of EMIs, cash drops of E-commerce companies, Hyperlocal delivery companies, etc. have been made easy, convenient and simpler. PayMama retailer can provide these services to their customers without any hassles.</p>
                                    <p style="text-align:justify" >Customers do not need to search any bank or the company’s branches, Customers just need to visit their nearest PayMama Retail store! PayMama Retailer can provide their customers with CMS facility anytime of the day.</p>
                                </div>
                        </div>
                    </div>
                <!--   
                    
                    
                     <div class="row" style="background-color: rgba(201, 232, 219, 0.7);padding:30px;margin-top:40px;">
                        <div class="col-sm-3">
                         <img src="{{asset('template_assets/assets/images/flight-booking.svg')}}" alt="img"> 
                        </div>
                        <div class="col-sm-9">
                               <div class="Title">
                                    <h3>Flight Ticket Booking</h3>
                                    <p style="text-align:justify">Flight Bookings made simple and easy</p>
                                </div>
                                <div class="Text">
                                    <p style="text-align:justify" class="mb-4">PayMama retailer can provide Flight Booking services to their customers at competitive rates and great commissions without any hassles.</p>
                                    <p style="text-align:justify" >Customers can just walk in  and get their travel bookings done easily in a secure way using the services provided at PayMama Retail store. While PayMama retailers get to provide their customers with more services; they also earn an extra income by rendering these services.</p>
                                </div>
                        </div>
                    </div>
                    <div class="row" style="background-color: rgb(210, 226, 248);padding:30px;margin-top:40px;">
                        <div class="col-sm-3">
                         <img src="{{asset('template_assets/assets/images/wholesale-market.svg')}}" alt="img">
                        </div>
                        <div class="col-sm-9">
                               <div class="Title">
                                    <h3>Wholesale Market</h3>
                                    <p style="text-align:justify">Selling made simple and easy</p>
                                </div>
                                <div class="Text">
                                    <p style="text-align:justify" class="mb-4">Become a Vendor, start selling on PayMama – your next big sales channel. Multiply your customers, increase your income with PayMama.</p>
                                
                                </div>
                        </div>
                    </div>
                    <div class="row" style="background-color: rgb(230, 221, 244);padding:30px;margin-top:40px;">
                        <div class="col-sm-3">
                         <img src="{{asset('template_assets/assets/images/bulk-money-tansfer.svg')}}" alt="img">
                        </div>
                        <div class="col-sm-9">
                                   <div class="Title">
                                    <h3>Bulk Payouts</h3>
                                    <p style="text-align:justify">Vendor Payments made simple and easy</p>
                                </div>
                                <div class="Text">
                                    <p style="text-align:justify" class="mb-4">Businesses and Retailers of all sizes can avail the Bulk Payout solution to digitize their Vendor Payments.</p>
                                    <p style="text-align:justify">One need not make payments in cash for its supplies, anymore. PayMama Users can on-board its Vendors through Digitized registration of the Payees with end-to-end verification in an efficient and easy manner.
Reduce your overhead expenses behind your payment processing system and ensure minimal manual intervention. Through the robust mechanism of PayMama, ensure that your vendors receive payments on time.</p>
                                
                                </div>
                        </div>
                    </div>-->

              
            </div>
        </section>
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