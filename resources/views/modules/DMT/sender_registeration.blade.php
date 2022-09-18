@extends('layouts.full_new')
<!-- This Page CSS -->
<link rel="stylesheet" type="text/css" href="{{ asset('template_new/assets/libs/select2/dist/css/select2.min.css') }}">
@section('page_content')
<div class="page-breadcrumb border-bottom">
                <div class="row">
                    <div class="col-lg-3 col-md-4 col-xs-12 justify-content-start d-flex align-items-center">
                        <h5 class="font-medium text-uppercase mb-0">SENDER REGISTRATION</h5>
                    </div>
                    <div class="col-lg-9 col-md-8 col-xs-12 d-flex justify-content-start justify-content-md-end align-self-center">
                        <nav aria-label="breadcrumb" class="mt-2">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Money Transfer</li>
                            </ol>
                        </nav>
                      
                    </div>
                </div>
            </div>
      <div class="row" style="margin-left:2px;padding:15px;">
          
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
                    <div class="col-12" style="margin-top:30px;">
                        <div class="material-card card">
                            <div class="card-body">
                                <form action="{{ route('register_dmt_sender') }}" method="post" id="senderMobForm" autocomplete="off" >
                                    @csrf
                                    
                                 
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="card-title" for="sender_mobile_number">FIRST NAME:</label>
                                        </div>
                                        <div class="col-md-4">
                                                <input type="text" name="reg_first_name" id="reg_first_name"  class="form-control"   placeholder="Enter First Name"required>
                                                <span id="message"></span>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="card-title" for="sender_mobile_number"> LAST NAME:</label>
                                        </div>
                                        <div class="col-md-4">
                                        
                                                <input type="text" name="reg_last_name" id="reg_last_name"  class="form-control"   placeholder="Enter Last Name"required>
                                                <span id="message"></span>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="card-title" for="sender_mobile_number">PINCODE:</label>       
                                        </div>
                                        <div class="col-md-4">
                                                <input type="text" name="pincode" id="pincode" class="form-control"  placeholder="Enter Pincode"required>
                                                <span id="message"></span>
                                        </div>
                                    </div>
                                    <hr>
                                   
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="card-title" for="sender_mobile_number">MOBILE NUMBER:</label>       
                                        </div>
                                        <div class="col-md-4">
                                                <input type="text" name="mobile_no" id="mobile_no" class="form-control"  placeholder="Enter Mobile No" value="{{ $sendermobileno }}" required>
                                                <span id="message"></span>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="col-md-12 col-sm-12 " style="text-align:center;">
                                           
                                            <button type="submit" class="btn success-grad btn-lg" id="sender-mob-sb-btn" style="margin-top:38px">Submit</button>
                                            
                                    </div>
                                </form>
                               
                            </div>
                        </div>
                    </div>
                </div>          
            </div>
                      
                    </div>
                </div>        
                <div style="height:430px;"></div>
              
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


