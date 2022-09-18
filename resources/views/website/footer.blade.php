<link rel="stylesheet" href="{{ asset('dist/setting/css/main.css') }}">
<!--<div class="blank-space"></div>-->
<footer style="padding-top:0px;">
    <div class="Wrapper">
        <div class="row" style="margin-bottom:30px;">
            <div class="col-lg-4">
            </div>
            <div class="col-lg-4">
                <center>
           
            </center>
            </div>
            <div class="col-lg-3">
            </div>
            
        </div>
        <style>
            ul li {
  position: relative !important;
    padding-bottom: 10px !important;
}

ul {
  list-style: none !important;
}

.address li:before{
   content: '';
   position: absolute !important;
   border-right:2px solid black !important;
   border-bottom:2px solid black !important;
   width:10px !important;
   height:10px !important;
   top: calc(50% - 4px) !important;
   left: -20px !important;
   transform: translateY(-50%) rotate(-45deg) !important;

}
.address li{
    margin-top:10px !important;

}
.address li a{
    font-size:20px;
    line-height: 1.5 !important;
    padding-top:10px !important;

}
footer .FooterParent .menuLinks ul li {
    width: 100%;
     padding: 0px 0 !important; 
}

footer .FooterParent .address>li {
    margin-bottom: 0px !important;
}
footer .FooterParent .footerLogo
{
    max-width:260px;
}
        </style>
        <div class="FooterParent row" uk-scrollspy="cls: uk-animation-fade; target: > div; delay: 300;  repeat: false">
            <div class="col-lg-3 col-md-6" style="margin-left:-20px;">
                 <div class="footerLogo" style="padding-left: 0px;margin-left:27px;">
                   <img  src="{{ asset('template_assets/assets/images/logos/PAYMAMA_logos.png') }}" alt="" class="img-fluid" style="height:60px !important;width:290px !important;">
                </div>
                <ul class="address" style="list-style-type: none !important;">
                    
                        <h5 style="color:#d40b2e !important;"><a href="#"  style="color:#d40b2e !important;font-family: 'Cambria-Bold';font-weight:bold !important;">NAIDU SOFTWARE TECHNOLOGIES PVT. LTD.</a></h5>
                        <p style="color:#2b2b2b;font-family: 'Montserrat',sans-serif !important;font-size:17px;">SH-4, Gv Complex,<br>Opp Word & Deed School, Hayathnagar, R.R Dist -501505,<br> Telangana, India</p>
                   
                        <p><span style="color:black !important;font-family: 'Montserrat',sans-serif !important;font-size:17px;">Call:</span><a  style="color:#2b2b2b;font-size:17px;" href="tel:+918374913154">+91 8374913154</a></p>
                        <p><span style="color:#2b2b2b;font-family: 'Montserrat',sans-serif !important;font-size:17px;">Mail:</span><a  style="color:black !important;font-size:17px;" href="mailto:sales@paymamaapp.in">sales@paymamaapp.in</a></p>
                   
                </ul>
            </div>
            <div class="col-lg-3 col-md-6 menuLinks" style="padding-left: 55px;">
                <h4 style="color:#d40b2e !important;font-family: 'Cambria-Bold' !important;font-weight:bold !important;margin-left:-20px;">NAVIGATION</h4>
                <ul class="address" style="list-style-type: none;">
                    <li style="padding-top:5px;"><a href = "{{ url('/') }}" style="color:#2b2b2b;">Home </a></li>
                    <li><a href = "{{ url('/services') }}" style="font-family: 'Montserrat',sans-serif;color:#2b2b2b;">Services</a></li>

                                    <li><a href = "{{ url('/about_us') }}" style="font-family: 'Montserrat',sans-serif;color:black !important;">About Us </a></li>
                                    <li><a href = "{{ url('/contact_us') }}" style="font-family: 'Montserrat',sans-serif !important;color:black !important;">Contact Us </a></li>
                                    <li><a href = "{{ url('/about_us') }}" style="font-family: 'Montserrat',sans-serif !important;color:#2b2b2b;">IT Service's</a></li>
                                    
                </ul>
            </div>
            <div class="col-lg-3 col-md-6 menuLinks" style="padding-left: 55px;">
                <h4 style="color:#d40b2e;font-weight:bold;margin-left:-20px;font-family: 'Cambria-Bold';">OTHER LINKS</h4>
                <ul class="address" style="list-style-type: none;">
                    <!-- <li><a href="#">FAQs</a></li> -->
                                    <li><a href = "" style="color:#2b2b2b;font-family: 'Montserrat',sans-serif !important;">Career</a> </li>
                                    <li><a  style="color:#2b2b2b;font-family: 'Montserrat',sans-serif !important !important;" href = "{{ url('/privacy_policy') }}">Privacy Policy</a></li>
                                    <li><a  style="color:#2b2b2b;font-family: 'Montserrat',sans-serif !important;" href = "{{ url('/terms_conditions') }}">Terms & Condition's</a> </li>
                                    <li><a  style="color:#2b2b2b;font-family: 'Montserrat',sans-serif !important;" href = "{{ url('/grievance-redressal-policy') }}">Grievance Redressal Policy</a> </li>
                                    <li><a  style="color:#2b2b2b;font-family: 'Montserrat',sans-serif !important;" href = "{{ url('/refund_and_returnpolicy') }}">Refund & Cancellation Policy</a> </li>
                                    
                </ul>
            </div>
            <div class="col-lg-3 col-md-6 connectWithUs" style="padding-left: 55px;">
                <h5 style="color:#d40b2e;font-weight:bold;font-family: 'Cambria-Bold';">CONNECT WITH US</h5>
                <div class="social-icon">
                                <a href="https://www.facebook.com/smartpayindia/">
                                    <img src="{{ asset('template_assets/assets/images/fb.PNG') }}" alt="" class="img-fluid">
                                </a>
                                <a href="https://instagram.com/smartpay_india?igshid=nk7ih6odsivx">
                                    <img src="{{ asset('template_assets/assets/images/instagram.PNG') }}" alt="" class="img-fluid">
                                </a>
                                <img src="{{ asset('template_assets/assets/images/linkedin.PNG') }}" alt="" class="img-fluid">
                                <img src="{{ asset('template_assets/assets/images/twitter.PNG') }}" alt="" class="img-fluid">

                            </div>
                            <br>
                <h5 style="color:#d40b2e;font-weight:bold;font-family: 'Cambria-Bold';">DOWNLOAD MOBILE APP</h5>
                <img src="{{ asset('template_assets/assets/images/googleplay.PNG') }}" alt="" class="img-fluid">
               
            </div>
        </div>
    </div>
    <div class="FooterBottom">
        <div class="Wrapper" style="text-align: center !important;">
            <center><p style="color:#2b2b2b;text-align: center;">Copyright ©️ 2021, PayMama Powered by Naidu Software Technologies Private Limited. All Rights Reserved.</p></center>
        </div>
    </div>
   
</footer>  
  
    <script src="{{ asset('dist/welcome/js/home.js') }}"></script>
    </body>
</html>
