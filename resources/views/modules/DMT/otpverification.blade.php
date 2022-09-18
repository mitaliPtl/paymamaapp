@extends('layouts.full_new')
<!-- This Page CSS -->
<link rel="stylesheet" type="text/css" href="{{ asset('template_new/assets/libs/select2/dist/css/select2.min.css') }}">
@section('page_content')
   <div class="page-breadcrumb border-bottom">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-xs-12 justify-content-start d-flex align-items-center">
                        <h5 class="font-medium text-uppercase mb-0">VERIFY OTP</h5>
                    </div>
                    <div class="col-lg-8 col-md-8 col-xs-12 d-flex justify-content-start justify-content-md-end align-self-center">
                        <nav aria-label="breadcrumb" class="mt-2">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Money Transfer</li>
                            </ol>
                        </nav>
                      
                    </div>
                </div>
            </div>
      <div class="row" style="padding-bottom:470px !important;margin-left:5px;padding:15px;">
          
                    <div class="col-12">
                    
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->

            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="page-content container-fluid">
                    @if(isset($data['error']) )

                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>FAILED</strong> {{ $data['error'] }} .
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
              
                 <div class="row">
                    <div class="col-5">
                        <div class="material-card card">
                            <div class="card-body">
                             <!--  <form action="" id="verifyOTPForm" method="post">-->
                                <form action="verify_dmt_otp" id="verifyOTPForm" method="post" autocomplete="off" >
                                <!-- <form id="verifyOTPForm"> -->
                                    @csrf
                                    <input type="hidden" id="mobile_no" name="mobile_no" value="{{  $sender_dtls['sender_mobile_number'] }}">
                                    <input type="hidden" id="user_id" name="user_id" value="{{ Auth::user()->userId }}">
                                    <input type="hidden" id="role_id" name="role_id" value="{{ Auth::user()->roleId }}">
                                    <input type="hidden" id="user_token" name="user_token" value="{{ App\UserLoginSessionDetail::getUserApikey(Auth::user()->userId) }}">
                                    
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12">
                                            <div class="form-group">
                                                <label class="card-title" for="reg_otp"><i class="mdi mdi-cellphone"></i> MOBILE NUMBER</label>
                                                <input type="text" name="mob_no" id="mob_no" class="form-control" value="{{  $sender_dtls['sender_mobile_number'] }}"   placeholder="Enter" required readonly>
                                                <span id="error_msg" style="color:red"></span>
                                            </div>
                                        </div>
                                     </div>
                                      <div class="row">
                                        <div class="col-md-12 col-sm-12">
                                            <div class="form-group">
                                                <label class="card-title" for="reg_otp"><i class="mdi mdi-cellphone"></i>ENTER OTP</label>
                                                <input type="text" name="reg_otp" id="reg_otp" class="form-control"   placeholder="Enter OTP" required>
                                                <span id="error_msg" style="color:red"></span>
                                            </div>
                                        </div>
                                         </div>
                                          <div class="row">
                                        <div class="col-md-2 col-sm-12">
                                        <button type="submit" class="btn success-grad btn-lg" id="verify-otp-sb-btns" style="width:120px;margin-top:38px; height: calc(2.1rem + .75rem + 2px);">Verify</button>
                                        
                                            
                                        </div>
                                         </div>
                                     
                                   
                                </form>
                                <form action="resend_dmt_otp" id="verifyOTPForm" method="post" autocomplete="off"  style="margin-left:150px;margin-top:-100px;">
                                <!-- <form id="verifyOTPForm"> -->
                                    @csrf
                                    <input type="hidden" id="mobile_no" name="mobile_no" value="{{  $sender_dtls['sender_mobile_number'] }}">
                                    <button type="submit" class="btn btn-primary btn-lg" id="verify-otp-sb-btns" style="background:#35a753;margin-top:38px; height: calc(2.1rem + .75rem + 2px);">Resend OTP</button>
                                </form>
                               
                            </div>
                        </div>
                    </div>
                </div>        
            </div>         
            </div>
                      
                    </div>
                </div>        
                
              
           <script>
               function isNumber(evt) {
                        evt = (evt) ? evt : window.event;
                        var charCode = (evt.which) ? evt.which : evt.keyCode;
                        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                            return false;
                        }
                        return true;
                    }
           </script>

    <!-- ============================================================== -->
    <!-- END Add Beneficiary -->
    <!-- ============================================================== -->
<script src="{{ asset('dist\service_type\js\custom_moneyTrans.js') }}"></script>
<script src="{{ asset('template_new\assets\libs\select2\dist\js\select2.full.min.js') }}"></script>
<script src="{{ asset('template_new\assets\libs\select2\dist\js\select2.min.js') }}"></script>
<script src="{{ asset('template_new\dist\js\pages\forms\select2\select2.init.js') }}"></script>

    
@endsection


