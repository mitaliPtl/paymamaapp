@include('website.header')
<div class="blank-space"></div>
<style>
    section.BannerMain .BannerInner{
        height:500px !important;
    }
</style>
<section class="BannerMain" style="margin-top:-100px;">
            <div class="BannerInner" style="background-image: url({{ asset('/template_assets/assets/images/background/terms-conditions-banner.png') }}">
                <div class="Title" uk-scrollspy="cls: uk-animation-slide-bottom; target: h2; repeat: false">
                    <h2>Terms & Conditions</h2>
                    <!--<p class="terms"  style="text-align:center;font-size:21px;color:white !important;" uk-scrollspy="cls: uk-animation-slide-bottom; repeat: false">We respect your Privacy</p>-->
                </div>
            </div>
        </section>
        <style>
            .digits{
                color:rgb(212 11 46);
            }
            .termsh4{
                font-family: "Cambria-Bold";
                font-weight: 600 !important;
                color:#000;
            }
            .terms{
                font-size: 17px;
    color: rgba(43,43,43,0.8) !important;
    line-height: 30px;
    font-weight: 600 !important;
    font-family:'Montserrat',sans-serif;
    margin: 20px 0;letter-spacing: 0.5px;
    text-align: justify;
            }
            .termsli{
                font-size: 17px;
    color: rgba(43,43,43,0.8) !important;
    line-height: 30px;
    font-weight: 600 !important;
    font-family:'Montserrat',sans-serif;
    margin: 20px 0;letter-spacing: 0.5px;
    text-align: justify;
            }
            .termsul{
                list-style-type: disc !important;
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
    text-align: center;">Introduction to Terms & Conditions</h1>
        <hr>

        <p class="terms"  style="font-size: 17px;
    color: rgba(43,43,43,0.8);
    line-height: 30px;
    font-weight: 500;
    margin: 20px 0;">
        This document is an electronic record in terms of Information Technology Act, 2000 and rules there under as applicable and the amended provisions pertaining to electronic records in various statutes as amended by the Information Technology Act, 2000. This electronic record is generated by a computer system and does not require any physical or digital signatures.
        </p>
        <p class="terms" ><strong>This document is published in accordance with the provisions of Rule 3 (1) of the Information Technology (Intermediaries guidelines) Rules, 2011 that require publishing the rules and regulations, privacy policy and Terms of Use for access or usage of www.paymamaapp.in website / Mobile Application.</strong></p>
        <p class="terms" >This web page represents a legal document; that serves as our Terms of Use and it governs the legal terms of our website, www.paymamaapp.in, sub-domains, and any associated web-based and mobile applications (collectively, "Website"), as owned and operated by Naidu Software Technologies Private Limited.</p>

        <h3 class="termsh4" >INTRODUCTION:</h3>
        <p class="terms"  style="">
           Naidu Software Technologies Private Limited maintains this website (“the Site”) for your personal information, education and communication purpose. Feel free to browse the website, but please read these terms and conditions before doing so. This website contains many of the valuable trademarks, names, titles, logos, images, designs, copyrights and other proprietary materials owned and registered by Naidu Software Technologies Private Limited and used by the company. Please read these Website terms and Conditions carefully before using www.paymamaapp.in / Mobile Application (hereinafter "Website"). The website/Mobile Application is available for your use, only on the condition that you agree to the terms and conditions set forth below. If you do not agree to all of the terms of use, do not access or use the website/Mobile Application, but you are required to immediately quit the website/Mobile Application. By accessing or using the website/Mobile application, you and the entity you are authorized to represent ("you" or "your") signify your agreement to be bound by the terms and conditions. These terms and conditions apply to all Users of this Website/Mobile Application (including but not limited to Members/Merchants). Please read these terms and conditions carefully, as they affect your legal rights.
        </p>

        <h3 class="termsh4" >USER ELIGIBILITY.</h3>
        <p class="terms" >
            The Website/Mobile application is operated by Naidu Software Technologies Private Limited and available only to entities and persons over the age of legal majority who can form legally binding agreement(s) under applicable law. If you do not qualify, you are not permitted to use the Website/Mobile Application.
        </p>

        <h4 class="termsh4" >ACCEPTANCE OF TERMS.</h4>
        <p class="terms" >
            Your agreement to comply with and be bound by these terms and conditions is deemed to occur upon your first use of the Website and its Mobile Application. By using the Website/Mobile Application and agreeing to these terms and conditions. Your access to and use of the Website/Mobile Application or Services is subject (unless expressly stated otherwise) exclusively to these terms and conditions. You will not use the Website/Mobile Application for any purpose that is unlawful or prohibited by these terms and conditions. By using the Website/Mobile Application you are fully accepting the terms, conditions and disclaimers contained in these terms and conditions.
.
        </p>
        <p class="terms" >
            If you are entering into these terms and conditions on behalf of your employer or acting as an employee, you warrant that you are authorized to enter into legally binding contracts on behalf of your employer. You further warrant that your employer agrees to be bound by these terms and conditions.
        </p>
        <p class="terms" >
            The Website/ Mobile Application, Tools and Services are directed solely for use in jurisdictions within the Territory of India. Company makes no representation that any Tools or Services provided on the Website/Mobile Application are appropriate or available for use outside India. Those who access the Website/Mobile Application and use the Tools and Services from locations outside India than such access and use is illegal. Company reserves the right to limit the availability of the Website/Mobile Application, the tools or the Services to any person, geographic area, or jurisdiction we so desire, at any time and in our sole discretion.
        </p>

        <h4 class="termsh4" >MODIFICATION OF THESE TERMS OF USE</h4>
        <p class="terms" >
           Need for registration: You must Register in the Website/Mobile Application with your true and correct information including but not limited to Full name, Firm/Company name, Full address, Mobile number, Email Id, Pan Number, Aadhar Number, GST number, etc.
        </p>
        <b><h3 class="termsh4" >Your Obligations:</p></h3>
        <br>
        
        <h4 class="termsh4" >We use cookies to:</h4>
        
        <ol>

           <li class="terms" > By accessing and using the Website/Mobile Application, you represent, warrant and covenant (a) to provide true, accurate, current and complete information about you as may be requested by any registration forms or templates on the Site or Mobile App or otherwise provided to you by Company; (b) to ensure that your User account is not shared or accessed by an individual other than yourself; (c) not to register or log-in on behalf of an individual other than yourself nor to allow any other individual to register or log-in under your User account; (d) not to use another User’s account; (e) to maintain the security of your password, User ID and/or other access methods you may be granted as a User; (f) to maintain and promptly update your registration data, and any other information you provide to us, to keep it accurate, current and complete; (g) that you are fully responsible for any and all use of your account and for any and all activities that occur through the use of any password, User ID or other access methods you may be granted, whether or not such use is authorized by you; and (h) not to access or attempt to access any password-protected portions of the Site without an authorized access method or through any means other than by utilizing your authorized access method on the appropriate Site or Mobile App page</li>
          <li class="terms" > Declaration regarding non-requirement to be registered under the Central / State/ UT/ Integrated Goods and Services Tax Act, 2017:
          <p class="terms" >As per Goods and Services Tax Act, 2017, if you are not required to get registered under the Act, then You hereby declare the following:</p>
          <ul class="termsul" >
            <li class="terms" > “I do hereby state that I am not required to get myself registered under the Goods and Services Tax Act, 2017 as I have the turnover below the taxable limit as specified under the Goods and Services Tax Act, 2017.</li>
            <li class="terms" > I hereby also confirm that if during any financial year we decide or require to register under the GST in that case I undertake to provide all the requisite information and documents.</li>
            <li class="terms" >I also understand that quoting of any false information in this declaration shall render me liable for all applicable legal and penal consequences, for which I shall be solely responsible.</li>
            <li class="terms" >I request you to treat this Electronic Acceptance as a declaration regarding non-requirement to be registered under the Goods and Service Tax Act, 2017.”</li>
            <li class="terms" >If you are a registered dealer than you need to update your KYCs at the Accounts Department, Contact no.: +91-8374913154</li>
       
          </ul>
            </li>
           <li class="terms" ><b> Privacy:</b> Note that any information you provide or that we otherwise collect about you as part of your registration is subject to the Company Privacy Policy. We reserve the right to disclose, in accordance with the Company Privacy Policy, any information about our Users, including registration data, in order to (a) satisfy any applicable law, rule, regulation, legal process, subpoena or governmental request; (b) enforce the T&C, and/or; (c) protect the rights, property or safety of Company, its Third Party Providers, the Subscribed Members, its other subscribed members, its Users and/or the public.</li>
       </ol>
    <h3 class="termsh4" >OUR WEB PORTAL – RATES, SERVICES AND TARIFF</h3>
    <p class="terms" >In addition to any other terms and conditions that may be applicable to such transaction as provided in these terms of use and the Service Agreement(s), our Web Portal Rates; service applicable to you and the Tariff (to the extent it is applicable to you) in effect at the time of provision of services govern your use of Our web Portal's/Mobile Applications related services.</p>
    <h3 class="termsh4" >RULES ABOUT USE OF THE SERVICE AND THE WEBSITE / MOBILE APPLICATION:</h3>
    <ol>
        <li class="terms" > We will use reasonable endeavors to correct any errors or omissions as soon as practicable after being notified of them. However, we do not guarantee that the Services or the Website /Mobile Application will be free of faults, and we do not accept liability for any such faults, errors or omissions. In the event of any such error, fault or comission, you should report it by contacting us at our Customer care no. +91-8374913154</li>
        <li class="terms" > We do not warrant that your use of the Services or the Website /Mobile Application will be uninterrupted. Notwithstanding that we will try to allow uninterrupted access to the Services and the Website /Mobile Application, access to the Services and the Website /Mobile Application may be suspended, restricted or terminated at any time.</li>
        <li class="terms" >We do not give any warranty that the Services and the Website/Mobile Application are free from viruses or anything else which may have a harmful effect on any technology.</li>
        <li class="terms" > We reserve the right to change, modify, substitute, suspend or remove without notice any information or Services on the Website/Mobile Application from time to time. Your access to the Website/Mobile Application and/or the Services may also be occasionally restricted to allow for repairs, maintenance or the introduction of new facilities or services. We will attempt to restore such access as soon as we reasonably can. For the avoidance of doubt, we reserve the right to withdraw any information or Services from the Website /Mobile Application at any time.</li>
        <li class="terms" > We reserve the right to block access to and/or to edit or remove any material which in our reasonable opinion may give rise to a breach of these Terms and Conditions of Use.</li>
        <li class="terms" >The custody of entire data, information to be collected from third parties the responsibilities shall be deemed to shift to the concerned channel partner, right from the beginning. Subject to the terms and conditions of this Agreement, our website/Mobile Application hereby grants you a limited, revocable, non-transferable and non-exclusive license to access and use of the website/Mobile Application. Any breach of this Agreement shall result in the immediate revocation of the license granted by us without notice to you.</li>
        <li class="terms" >You may not make any commercial use of any of the information provided on the website/Mobile Application or make any use of the website/Mobile Application for the benefit of another business unless explicitly permitted by us in in advance.</li>
    </ol>
    <h3 class="termsh4" >USE OF SITE</h3>
<p class="terms" >Subject to the terms and conditions of this Agreement, www.paymamaapp.in  / Mobile Application hereby grants you a limited, revocable, non-transferable and non-exclusive license to access and use the website/Mobile Application. Any breach of this Agreement shall result in the immediate revocation of the license granted in this paragraph without notice to you. Except as permitted in the paragraph above, you may not reproduce, distribute, display, sell, lease, transmit, create derivative works from, translate, modify, reverse-engineer, disassemble, decompile or otherwise exploit this website or any portion of it unless expressly permitted by website in writing. You may not make any commercial use of any of the information provided on the website or make any use of the website for the benefit of another business unless explicitly permitted by PayMama in advance. www.paymamaapp.in  / Mobile Application reserves the right to refuse service, terminate accounts in its discretion, including without limitation, if PayMama believes that customer conduct violates applicable law or is harmful to websites interests or customer stores data which is illegal by law on the server.</p>
<p class="terms" >You shall not upload, distribute, or otherwise publish through this website/Mobile Application any of the Content, information, or other material that –</p>
<p class="terms" ><span class="digits">A.</span>  Violates or infringes the copyrights, patents, trademarks, service marks, trade secrets, or other proprietary rights of any person;</p>
<p class="terms" ><span class="digits">B.</span>  is libellous, threatening, defamatory, obscene, indecent, pornographic, or could give rise to any civil or criminal liability under Indian law; or</p>
<p class="terms" ><span class="digits">C.</span>  includes any bugs, viruses, worms, trap doors, Trojan horses or other harmful code or properties; or</p>
<p class="terms" ><span class="digits">D.</span>  criminal or tortuous activity, including pornography, child pornography, fraud, trafficking in obscene material, drug dealing, gambling, harassment, stalking, spamming, spamming, sending of viruses or other harmful files, copyright infringement, patent infringement, or theft of trade secrets; or</p>
<p class="terms" ><span class="digits">E.</span>  Using any information obtained from the website/Mobile Application in order to harass, abuse, or harm another person;</p>
<h3 class="termsh4" >IT ACT, 2000 COMPLIANCE</h3>
<p class="terms" >Under Rule 3 of Rules made under IT Act, 2000 vide powers conferred by clause (zg) of sub-section (2) of section 87 read with sub-section (2) of section 79 :</p>
<p class="terms" >Users of computer resource not to host, display, upload, modify, publish, transmit, update or share any information that —</p>
<ol>
<li class="terms" > belongs to another person and to which the user does not have any right to;</li>
<li class="terms" >is grossly harmful, harassing, blasphemous, defamatory, obscene, pornographic, pedophilic, libellous, invasive of another's privacy, hateful, or racially, ethnically objectionable, disparaging, relating or encouraging money laundering or gambling, or otherwise unlawful in any manner whatever;</li>
<li class="terms" >harm minors in any way;</li>
<li class="terms" >infringes any patent, trademark, copyright or other proprietary rights;</li>
<li class="terms" > violates any law for the time being in force;</li>
<li class="terms" >deceives or misleads the addressee about the origin of such messages or communicates any information which is grossly offensive or menacing in nature;</li>
<li class="terms" > impersonate another person;</li>
<li class="terms" > contains software viruses or any other computer code, files or programs designed to interrupt, destroy or limit the functionality of any computer resource;</li>
<li class="terms" >threatens the unity, integrity, defense, security or sovereignty of India, friendly relations with foreign states, or public order or causes incitement to the commission of any cognizable offence or prevents investigation of any offence or is insulting any other nation.</li>
<h3 class="termsh4" >TECHNICAL PROCESSING</h3>
<li class="terms" >In view of the global nature of the world wide web, the User understands and agrees that technical processing of tools of communication is (and may be) required to send and receive messages, to correspond / conform to the technical requirements of connecting networks, to correspond / conform to the limitations of The Service, or to correspond / conform to other, similar technical requirements.</li>
<h3 class="termsh4" >DATA PROTECTION</h3>
<li class="terms" >Our Website/Mobile application is not responsible for any corruption, misguiding or missing of submitted data from you regarding credit card, debit card or any other e-payment detail, e-wallet etc. Personal information supplied by user(s) during the use of website is governed by our privacy policy. Registration Information and certain other information about you are subject to our Privacy Policy.</li>
<h3 class="termsh4" >PRIVACY POLICY</h3>
<p class="terms" >Use of the website/Mobile Application Services is also governed by Our Privacy Policy</p>
<h3 class="termsh4" >DISCLAIMER</h3>
<p class="terms" >The Information, products and services provided on this website are provided “as is” and without warranty of any kind, express or implied. The Champion disclaims all express and implied warranties with regard to the information, products and services provided through this website, including without limitation implied warranties of merchantability, fitness for a particular purpose and non-infringement. Champion makes no representations whatsoever about any other website which you may access through this one or which may link to this website.</p>
<h3 class="termsh4" >LIMITATION OF LIABILITY</h3>
<p class="terms" >The features and services on the website are provided on an "as is" and "as available" basis, and website/Mobile Application hereby expressly disclaims any and all warranties, express or implied, including but not limited to any warranties of condition, quality, durability, performance, accuracy, reliability, merchantability or fitness for a particular purpose. All such warranties, representations, conditions, undertakings and terms are hereby excluded.</p>
<p class="terms" >Our Website/ Mobile Application is not responsible for any problems or technical malfunction of any telephone network or lines, computer online systems, servers or providers, computer equipment, software, failure of any email due to technical problems or traffic congestion on the Internet or on any of the www.paymamaapp.in  Services or combination thereof.</p>
<p class="terms" >Our Website/ Mobile Application is not responsible for any error, omission, interruption, loss, deletion, defect, theft, illegal transactions for any purpose, destruction or unauthorized access to, or alteration of any content you upload to the Our Website/ Mobile Application. Our Website/ Mobile Application is playing role of an intermediary and are not liable for any illegal transactions for any reason and purpose.</p>
<p class="terms" >Our Website/ Mobile Application may use reasonable efforts to include accurate and up-to-date information on the Website/ Mobile Application but our website/Mobile Application will not be responsible for any incorrect or inaccurate content uploaded on the website or in connection with the Website/ Mobile Application services, whether caused by users of the website/Mobile Application services or by any of the equipment or programming associated with or utilized in the website/Mobile Application services.</p>
<p class="terms" >All conditions, terms, representations and warranties relating to the website/Mobile application Services supplied under this Agreement, whether imposed by statute or operation of law or otherwise, that are not expressly stated in these terms and conditions including, without limitation, the implied warranty of satisfactory quality and fitness for a particular purpose are hereby excluded to the extent applicable under Indian laws.</p>
<p class="terms" >Our total aggregate liability to you for any claim in contract, tort, negligence or otherwise arising out of or in connection with the provision of the website/Mobile Application Services shall be limited to the charges paid by you in respect of the services which are the subject of any such claim.</p>
<p class="terms" >Under no circumstances shall website/Mobile Application be held liable for an delay or failure or disruption of the content or services delivered through the website/Mobile application resulting directly or indirectly from acts of nature, forces or causes beyond its reasonable control, including without limitation, Internet failures, computer, telecommunications or any other equipment failures, electrical power failures, strikes, labour disputes, riots, insurrections, civil disturbances, shortages of labour or materials, fires, flood, storms, explosions, Acts of God, natural calamities, war, governmental actions, orders of domestic or foreign courts or tribunals or non-performance of third parties.</p>
<p class="terms" >Website/Mobile Application shall not be held liable for any special, direct, indirect, punitive, incidental or consequential damages or any damages whatsoever (including but not limited to damages for loss of profits or savings, business interruption, loss of information), whether in contract, negligence, tort, strict liability or otherwise or any other damages resulting from any of the following:</p>
<ul class="termsul" >
<li class="terms" >   The use or the inability to use the website;
<li class="terms" > Any defect in goods, samples, data, information or services purchased or obtained from a User(s) or a third-party service provider through the website;</li>
<li class="terms" >Violation of Third Party Rights or claims or demands that User(s) manufacture, importation, distribution, offer, display, purchase, sale and/or use of products or services offered or displayed on the website may violate or may be asserted to violate Third Party Rights; or claims by any party that they are entitled to defense or indemnification in relation to assertions of rights, demands or claims by Third Party Rights claimants;</li>
<li class="terms" >Unauthorized access by third parties to data or private information of any User(s);</li>
<li class="terms" >Statements or conduct of any User(s) of the website; or</li>
<li class="terms" >Any matters relating to Premium Services however arising, including negligence.</li>
<h4 class="termsh4" >WEBSITE/MOBILE APPLICATIONS’ SERVICES.</h4>
<li class="terms">Upon registering yourself on this Website/Application, the User will become eligible to undertake Financial Services on the Platform provided by the financial institutions including but not limited to non-banking financial companies, banks, other payment systems as authorised to provide Financial Services under Applicable Laws (“Financial Service Provider”).</li>
<li class="terms" >The User hereby understands and acknowledges that the Company is not the provider of the Financial Services. Accordingly, the User acknowledges and agrees that the Company does not warrant, endorse, guarantee, or assume responsibility for any product or service advertised or offered by a third party including the Financial Services being provided by the Financial Service Providers utilizing Company Services or Platform or any hyperlinked website or service.</li>
<li class="terms" >The User hereby understands and acknowledge that in addition to this Terms of Use, the Financial Service Providers and Providers of other Services may have their separate terms and conditions with respect to availing Financial and other Services. Accordingly, the User understands and acknowledges that it may have to agree to such separate terms and conditions for availing certain Financial Services for undertaking Transactions.</li>
<h3 class="termsh4" >CANCELLATION AND REFUND</h3>
<ol>
<li class="terms" >
If you cancel any bus ticket for any reason in that case, the Website/Mobile Application shall not be liable to refund your payment.</li>
<li class="terms" >If any unnatural certainty happens and in that situation if any bus ticket or flight ticket gets cancelled in that situation the facility given by the ticket company will be given to you, the website /Mobile Application does not have any control over the aviation company in the cancellation of tickets.</li>
<li class="terms" >For any transaction that results in a cancellation of ticket booking, Retailer / Master Distributor agrees and acknowledges that the Website/Mobile Application may withhold the cancellation amount in a Reserve from Retailers/Masterpay Distributor’s wallet or your settlement amount. Website/Mobile Application may debit the amount of any cancellation amount and any associated Fees, fines, or from your Settlement Amount and/or Reserve.</li>
<li class="terms" >You will get your refund amount in your wallet and you have to use that within a one year of time.</li>
<h3 class="termsh4" >TERMS OF AGREEMENT FOR ONLINE PAYMENTS:</h3>
<ol>
<p class="terms" >
<h4 class="termsh4" ><span class="digits">1.</span>  Definitions and Interpretation:</h4>
<p class="terms" ><span class="digits">a).</span>  “Facility provider” shall mean the Internet Payment Gateway provider with which the PAYMAMA has an arrangement for facilitating online payment through their respective payment gateway.</p>
<p class="terms" ><span class="digits">b).</span>  “Merchant” shall mean the User/Retailer/Distributor/Super Distributor making Payments to the Company to avail the Services.</p>
<p class="terms" ><span class="digits">1.1.</span>  Subject to the terms and conditions of this Agreement PAYMAMA will provide to the Merchant the Services as and in the manner set forth.</p>
<p class="terms" ><span class="digits">1.2.</span>  This Agreement shall commence on the Activation Date and shall continue to be in effect unless terminated in accordance with (“Terms”).</p>
<p class="terms" >PAYMAMA is entitled to change/ update the terms of service, including PAYMAMA Fees as well as settlement timelines for merchant opting for any and all additional Services from PAYMAMA apart from the ones envisaged under this Agreement, without prior approval of such Merchant.</p>
<p class="terms" >Upon signing the agreement Merchant agree that payment gateway services provided by the PAYMAMA will be used for loading the wallet to carry out the digital financial services offered on the platform. The Merchant can use Credit Card/Debit card/ Net Banking / UPI to take the wallet load. The Merchant agrees that they take complete ownership of knowing the cardholder and have verified the owner and the details before taking the wallet load. The Merchant also agree and acknowledge resolving any dispute that arises during the process.</p>
<h4 class="termsh4" ><span class="digits">2.</span>   UNDERTAKINGS OF MERCHANT</h4>
<p class="terms" >Use of the Payment gateway facility: In using the facility, the Merchant agrees:</p>
<p class="terms" ><span class="digits">2.1.</span>  Not to use the Facility in any manner, or in furtherance of any activity, which constitutes a violation of any law or regulation or which may result in fraud on any person or which may cause the Payment Gateway Service Provider or MASTERPAY to be subject to investigation, prosecution or legal action.</p>
<p class="terms" ><span class="digits">2.2. </span>To use information regarding a Cardholder (including name, address, e-mail address, telephone numbers, and data regarding bank accounts or financial instruments) conveyed to Merchant by consumer software designed to access the Facility only for the purpose of completing the Transactions for which it was furnished, and not to sell or otherwise furnish such information to others unless the Merchant has an independent source of such information or obtains the express consent of such Cardholder.</p>
<p class="terms" ><span class="digits">2.3.</span>  Compliance with Law & Guidelines: Merchant shall at all times comply with applicable laws, rules and regulations in so far as relevant to its use of the Facility. If the utilisation of the Facility by the Merchant results in or may result in additional liability being placed on PAYMAMA, such utilisation shall be deemed to be a violation of this Agreement and PAYMAMA has all the right to revoke the facility and terminate the agreement.</p>
<p class="terms" ><span class="digits">2.4.</span>  The Merchant hereby confirms that PAYMAMA shall not be responsible for any Cardholder complaints regarding inaccuracy or deficiency in service or incorrect/ expired/ disputed and the Merchant shall be solely responsible and shall take such measures as may be required to resolve the same at its sole cost and expenses.
<p class="terms" ><span class="digits">2.5.</span>  Cardholder Support Services: To provide a second-level Query Resolution Support, Merchant shall co-operate with and assist the Company in connection with any inquiries for any Bill Payment request that are received regarding the validity or correctness of the data included in the payment information in respect of such Cardholders; notwithstanding anything provided herein or elsewhere in this Agreement, Merchant shall be responsible for resolving any Cardholder queries/ disputes relating to the Bill Payment request to them for any queries relating to execution of standing instructions issued by them to the Company.</p>
<h4 class="termsh4" ><span class="digits">3. </span> CHARGEBACKS</span> </h4>
<p class="terms" ><span class="digits">3.1. </span>If Payment Gateway Service provider communicates to PAYMAMA the receipt of a Chargeback from a Cardholder, then the Merchant will forthwith be notified of the Chargeback.</p>
<p class="terms" ><span class="digits">3.2.</span> The Merchant shall be entitled to furnish to PAYMAMA documents and information (“Chargeback Documents”) pertaining to the Transaction associated with the Chargeback in order to substantiate (i) the completion of the aforesaid Transaction and/or; (ii) delivery of goods / services sought by the Cardholder pursuant to the said Transaction. Provided the Merchant is desirous of furnishing the Chargeback Document, the Merchant shall do so within five (5) days (or such other period specified by the Payment Gateway Service Provider) of receiving notification of the Chargeback.</p>
<p class="terms" ><span class="digits">3.3.</span>  The Merchant agrees and acknowledges that
(i) if the Merchant is unable to furnish Chargeback Documents stipulated above;
(ii) the Payment Gateway Service Provider is not satisfied with the Chargeback Documents furnished by the Merchant, then the service provider shall be entitled to order the MASTERPAY to effect a reversal of the debit of the Chargeback Amount associated with the Chargeback such that the said Chargeback Amount is credited to the Cardholder’s Payment Instrument.</p>
<p class="terms" ><span class="digits">3.4.</span>  For any transaction that results in a Chargeback, Retailer / Distributor/ Super Distributor agrees and acknowledge that PAYMAMA may withhold the Chargeback amount in a Reserve from Retailers/Distributor/Super Distributors wallet or your settlement amount. PAYMAMA may debit the amount of any Chargeback and any associated Fees, fines, or from your Settlement Amount and/or Reserve. If PAYMAMA reasonably believe that a Chargeback is likely with respect to any transaction, PAYMAMA may withhold the amount of the potential Chargeback from payments otherwise due to you under this Agreement until such time that: (a) a Chargeback is assessed due to a Cardholder ’s complaint, in which case we will retain the funds; (b) the period of time under applicable law or regulation or Card Association Rules by which the Cardholder may dispute that the transaction has expired; or (c) we determine that a Chargeback on the transaction will not occur. If PAYMAMA is unable to recover funds related to a Chargeback for which you are liable, you will pay PAYMAMA the full amount of the Chargeback immediately upon demand. You agree to pay all costs and expenses, including without limitation attorneys’ fees and other legal expenses, incurred by or on behalf of us with respect to collection of all Outstanding Amounts unpaid by you. On the issuance of notice, PAYMAMA reserves the right to withhold from each settlement made during the Notice Period, a sum computed based on a Stipulated Percentage (defined hereinbelow) for a period of one hundred and twenty (120) days (“Withholding Term”) from the date of issuance. The sums so withheld shall be utilized towards settlement of Chargebacks. After processing such Chargebacks, PAYMAMA shall transfer the unutilized amounts, if any, to the Merchant forthwith upon completion of the Withholding Term. The ‘Stipulated Percentage’ is the proportion of the Chargeback Amounts out of the total Transaction Amounts settled during the subsistence of this Agreement.</p>
<p class="terms" ><span class="digits">3.5.</span>  Merchant agrees that payments made in respect of any order which Cardholder or anyone else disputes as a transaction not done by payer or delivery of services not received by Merchant or the allegation that transaction has been done by unknown persons which is disputed by Cardholder or payer or a charge/debit arising out of any alleged hacking, phishing, breach of security/ encryption of the end user’s Login/Password or debit card number or PIN has arisen and a request for Chargeback/refund has been made by the Cardholder then it is agreed by Merchant shall provide proof of delivery of service to the Cardholder and shall make adjustment to the Cardholder bill and shall refund amount to PAYMAMA in return shall refund such amount to Payment Gateway service provider who will then refund the Cardholder / payer or anyone disputing the Transaction.</p>
<h4 class="termsh4" ><span class="digits">4. </span>  INDEMNIFICATION</h4>
<p class="terms" ><span class="digits">4.1.</span> In addition to the above, the Merchant shall also comply with security practices and procedures as prescribed in the Information Technology Act, 2000 and the rules made thereunder and/or the RBI rules and Regulations. Any loss incurred to the PAYMAMA or the Payment Gateway Service Provider as a result of the Link being breached due to improper security on the part of the Merchant, its employees, contractors, agents, etc. Merchant agrees to indemnify, defend and hold harmless PAYMAMA and the Facility provider from any claims, actions, damages or losses arising out or in relation thereto.</p>
<p class="terms" ><span class="digits">4.2.</span>  The Merchant shall indemnify and hold PAYMAMA, its directors, officers, employees, agents representatives harmless from and against any and all losses, damages, costs or expenses (including reasonable attorney's fees) on account of claims, judgments, awards, settlements, fines, arising from</p>
<p class="terms" ><span class="digits">1.  (a)</span>  The transaction between the Payee and the Merchant (pursuant to the relationship between the Merchant and Payee, contractual or otherwise);</p>
<p class="terms" ><span class="digits">2.  (b)</span>  breach of security in respect of Payee’s Personal Data with such breach being attributable to the Merchant; and</p>
<p class="terms" ><span class="digits">3.  (c)</span>  fines, penalties imposed by the Sponsor Bank, NPCI or any other governmental authority on account of fraudulent Pay-out transactions initiated or effected by the Merchant.</p>
<p class="terms" ><span class="digits">4.3.</span>  Merchant hereby undertakes and agrees to indemnify at all times and hold harmless Company, from and against all actions, proceedings, claims, liabilities (including statutory liability), penalties, demands and costs, awards, damages, losses and/ or expenses however arising as a result of:</p>
<p class="terms" > <span class="digits">4.3.1</span>  Any breach of any Applicable Laws, GST and rules and regulations.</p>
<p class="terms" > <span class="digits">4.3.2</span>  Any breach or non-performance by the Merchant of any of the provisions of this Agreement and/or any Schedules, breach of confidentiality, Intellectual property rights, inaccuracy of Cardholder billing information, Chargeback and refunds, any fines, penalties or interest imposed directly or indirectly on PAYMAMA on account of Merchant under this Agreement and/or any schedules;</p>
<p class="terms" ><span class="digits">4.3.3</span>  Any claim or proceeding brought by Merchant (‘s) vendors/suppliers, the Cardholder or any other person against Company, in respect of any product/services offered by Merchant; or</p>
<p class="terms" ><span class="digits">4.3.4 </span> Any act, neglect or default of Merchant (‘s) agents, employees, licensees or Cardholder; or</p>
<p class="terms" ><span class="digits"> 4.3.5</span>  Any claim by any other party against PAYMAMA, arising from sub-clauses above. Merchant shall also fully indemnify and hold harmless PAYMAMA and the Facility provider against any loss, costs, expenses, demands or liability, whether direct or indirect, arising out of a claim by a third party that Merchant (‘s) services infringes any intellectual or industrial property rights of that third party.</p>
<p class="terms" >In the event of Company/the Facility provider being entitled to be indemnified pursuant to the provisions of this Agreement, PAYMAMA shall be entitled to accordingly and to such extent debit Merchant (‘s) with PAYMAMA and/or the Settlement Amount.</p>
<p class="terms" >Upon signing the agreement Merchant agree that payment gateway services provided by the PAYMAMA will be used for loading the wallet to carry out the digital financial services offered on the platform. The Merchant can use Credit Card/Debit card/ Net Banking / UPI to take the wallet load. The Merchant agrees that they take complete ownership of knowing the cardholder and have verified the owner and the details before taking the wallet load. The Merchant also agree and acknowledge resolving any dispute that arises during the process.</p>
<h4 class="termsh4" >AVAILABILITY OF A PRODUCT OR A SERVICE:</h4>
<p class="terms" >Despite the fact; that this website/Mobile Application may be accessed from locations where www.paymamaapp.in does not do business, we do not represent that we are doing business in those locations, or that its services or products will be available in any particular state, country or jurisdiction. This website/Mobile Application is not targeted to users in any particular locality. by accessing our website/Mobile application you agree and understand that our website/Mobile application and anything contained on website/Mobile Application will not be used to establish personal jurisdiction over our website/Mobile application; in locations where it is not currently doing business.</p>
<h4 class="termsh4" >INTELLECTUAL PROPERTY RIGHT POLICY</h4>
<p class="terms" >The entire contents of the website/Mobile Application are protected by international copyright and trademark laws. The owner of the copyrights and trademarks are Champion software Technologies Limited, its affiliates or other third party licensors. You may not allocate, distribute, or reproduce in any way any copyrighted material, Designs, trademarks or other Intellectual Property and proprietary Information belonging to others without obtaining the prior written consent of the owner of such proprietary rights. You may not modify, copy, reproduce, republish, upload, post, transmit, or distribute, in any manner, the material on the website, including text, slogans, logo, graphics, code and/or software. You may print and download portions of material from the different areas of the website/Mobile Application solely for your own Personal and non-commercial use provided that you agree not to change or delete any copyright or proprietary notices from the materials.</p>
<p class="terms" >You may not allocate, distribute, or reproduce in any way any copyrighted material, Designs, trademarks or other Intellectual proprietary Information belonging to others without obtaining the prior written consent of the owner of such proprietary rights. It is the policy of www.paymamaapp.in to terminate Membership privileges of any Member/User/Merchant who once infringes the copyright rights of others upon receipt of prompt notification to website/Mobile Application by the copyright owner or the copyright owner's legal agent/Advisor. Without limiting the foregoing, if you believe that your work has been copied and posted on the website Services in a way that constitutes copyright infringement, please provide us with the following information:</p>
<p class="terms" ><span class="digits">1.</span>   an electronic or physical signature of the person authorized to act on behalf of the owner of the copyright interest;</p>
<p class="terms" ><span class="digits">2.</span>   a description of the copyrighted work that you claim has been infringed;</p>
<p class="terms" ><span class="digits">3. </span>  a description of where the material that you claim is infringing is located on the website/Mobile Application Services;</p>
<p class="terms" ><span class="digits">4.</span>   your address, telephone number, and email address;</p>
<p class="terms" ><span class="digits">5.</span>   a written statement by you that you have a good faith belief that the disputed use is not authorized by the copyright owner, its agent, or the law;</p>
<p class="terms" ><span class="digits">6.</span>   a statement by you, made under penalty of perjury, that the above information in your notice is accurate and that you are the copyright owner or authorized to act on the copyright owner's behalf.</p>
<h3 class="termsh4" >INDEMNITY</h3>
<p class="terms" >You "The User" comprehend and agree to indemnify and hold www.paymamaapp.in and its parents, mobile application, affiliates, officers and employees, harmless from any claim or demand, including attorneys' fees, made by any third party due to or arising out of your use of the Service, your connection to the Service, your violation of the website/Mobile Application Agreement or any other provision of the Agreement, or the infringement or misappropriation by the User/Merchant, or a third party using User's computer, account or password to access and/or use the services, of any Intellectual Property Rights of any person or entity, or the use or misuse by the User or third parties of User's passwords or accounts, or your violation of any rights of another.<p class="terms" >
<h3 class="termsh4" >TERMINATION</h3>
<p class="terms" >This Agreement shall remain in full force and effect while you use the website/Mobile Application. However, our website/Mobile Application reserves the right to terminate access to certain areas or features of the website/Mobile Application at any time for any reason, with or without notice. Our Website/Mobile Application also reserves the universal right to deny access to particular users to any/all of its services/content without any prior notice/explanation in order to protect the interests of our website/Mobile Application and/or other visitors to the website. Our website/Mobile Application reserves the right to limit, deny or create different access to the website/Mobile Application and its features with respect to different user(s)/Merchant’s, or to change any of the features or introduce new features without prior notice. Our website/Mobile Application withholds the right to temporary or permanent termination of any user for any of the following reasons:</p>
<p class="terms" ><span class="digits">1.</span>   If it concludes that the user(s)/Merchant(s) have provided any false information in connection with the member account</h3> to MasterPay (website/Mobile Application), or are engaged in fraudulent or illegal activities.</p>
<p class="terms" ><span class="digits">2.</span>   The user(s) breach any provisions of the terms and conditions of use agreement and/or Agreement of website.</p>
<p class="terms" ><span class="digits">3.</span>   Utilize website to send spam messages or repeatedly publish the same product information.</p>
<p class="terms" ><span class="digits">4. </span>  Post any material to members that are not related to trade or business cooperation.</p>
<p class="terms" ><span class="digits">5. </span>  Impersonate or unlawfully use another companies name to post information or conduct business of any form</p>
<p class="terms" ><span class="digits">6.</span>   Any unauthorized access, use, modification, or control of the website/Mobile Application data base, network or related services.</p>
<h3 class="termsh4" >ASSOCIATION / LINKS TO THIRD PARTY</h3>
<p class="terms" >Our Website/mobile application contains links to third party websites "Linked Sites" ("Affiliates" / "Partner Sites"). These Linked websites are not under the control of PayMama and PayMama is not responsible for the products of any Linked website, including without limitation any link contained in a Linked website, or any changes or updates to a Linked website. Our website/Mobile Application is not responsible for web casting or any other form of transmission received from any Linked website. You are responsible for viewing and abiding by the privacy statements and terms of use posted at the Linked websites.</p>
<h3 class="termsh4" >TERMS AND CONDITIONS RELATING TO ADVERTISERS</h3>
<style type="text/css">
     li.termsli::before {
    content: '';
    position: absolute;
    top: 12px;
    right:10px;
    left: 0;
    width: 6px;
    height: 6px;
    background: #d40b2e;
}
</style>
<li class="termsli" >&nbsp;&nbsp;Advertiser agrees to use, and website/Mobile Application agrees to make available, the Network during the Term in order to place Ads from Advertiser on Publisher Websites according to the terms and conditions of this Agreement. As between Advertiser and website/Mobile Application, Advertiser shall be solely responsible for soliciting all Ads, placing Ads through the Network and responding to inquiries in connection therewith.</li>
<li class="termsli" > &nbsp;&nbsp; All copyright, database rights, trademarks and design rights in our website and in the material published on it belong to website/Mobile Application Advertisers cannot use it without prior permission of the Website owner.</li>
<li class="termsli" >&nbsp;&nbsp;  Advertiser shall be solely responsible if any kind of loss occurred to the user of the website.</li>
<h3 class="termsh4" >NOTICE</h3>
<p class="terms" >All notices or demands to or upon website shall be effective if in writing and shall be duly made when sent to Champion Software Technologies Limited having addressed at:-
SHOP NO - 4,GV COMPLEX,OPP WORD & DEED SCHOOL,HAYATHNAGAR,R.R DIST,TELANGANA,501505.
All notices or demands to or upon a User(s)/Merchant(s) shall be effective if either delivered personally, sent by courier, certified mail, by facsimile or email to the last-known correspondence, fax or email address provided by the User(s) to www.paymamaapp.in</p>
<h3 class="termsh4" >FEEDBACK</h3>
<p class="terms" >The thoughts written or reviews posted by visitors are of them and not moderated by us, any one thus felt offended please email us at: customercare@paymamaapp.in  and we will remove it from our website (if we feel the same within the framework of business, prevalent norms and practicability) of legality and freedom of speech.</p>
<h3 class="termsh4" >GOVERNING LAW AND DISPUTE RESOLUTIONS</h3>
<p class="terms">This Agreement shall be governed by and construed in accordance with Indian Law and you hereby submit to the exclusive jurisdiction of the Gujarat Courts.</p>
<h3 class="termsh4" >NON-WAIVER</p>
<p class="terms" >Any forbearance or failure by us to enforce a provision to which you are subject shall not affect our right to require such performance at any subsequent time, nor shall the waiver or forbearance by us of any breach of any provisions of the agreement herein be taken to be or held to be a waiver of the provision or provisions itself of themselves.</p>
<h3 class="termsh4" >SEVERABILITY</h3>
<p class="terms" >If any provision(s) of the Agreement is/are held by a court of competent jurisdiction to be contrary to law, or otherwise invalid or unenforceable, then such provision(s) shall be construed, as nearly as possible, to reflect the intentions of the parties with the other provisions remaining in full force and effect.</p>
<h3 class="termsh4" >STATUTE OF LIMITATIONS</h3>
<p class="terms" >User and website/Mobile Application agree that any cause of action arising out of or related to these Services must commence within one (1) month after the cause of action arose; otherwise, such cause of action is permanently barred. If you do not agree with any of our Terms and conditions mentioned above please do not read the material on any of our pages or do not accept our services.</p>
<h3 class="termsh4" >ENTIRE AGREEMENT</h3>
<p class="terms" >These Terms of Use set forth the entire understanding and agreement between you and Champion Software Technologies Limited with respect to the website/Mobile Application. You acknowledge that any other agreements between you and Naidu Software Technologies Private Limited with respect to the website/Mobile Application are superseded and of no force or effect.</p>
<p class="terms" >These Terms of Use were last modified: SEP, 2021</p>

 </div>
<div style="margin-top:3px;">
@include('website.footer')
</div>