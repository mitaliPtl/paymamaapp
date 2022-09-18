@extends('layouts.full_new')
<!-- This Page CSS -->
<link rel="stylesheet" type="text/css" href="{{ asset('template_new/assets/libs/select2/dist/css/select2.min.css') }}">
@section('page_content')
 <div class="page-breadcrumb border-bottom">
                <div class="row">
                    <div class="col-lg-3 col-md-4 col-xs-12 justify-content-start d-flex align-items-center">
                        <h5 class="font-medium text-uppercase mb-0">GET CUSTOMER INFO</h5>
                    </div>
                    <!-- <div class="col-lg-9 col-md-8 col-xs-12 d-flex justify-content-start justify-content-md-end align-self-center">
                        <nav aria-label="breadcrumb" class="mt-2">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Money Trasnfer</li>
                            </ol>
                        </nav>
                      
                    </div> -->
                </div>
            </div>
      <div class="row" style="margin-left:20px;margin-top:40px;">
                    <div class="col-12">
                        <div class="material-card card" style="">
                            <div class="card-body">
                                <form action="{{ route('get_dmt_sender_details') }}" method="post" id=""  autocomplete="off">
                                    @csrf
                                   
                                    <div class="row">
                                        <div class="col-md-3 col-sm-12">
                                            <div class="form-group">
                                                <label class="card-title blue-font" for="sender_mobile_number"><i class="mdi mdi-cellphone"></i> Sender Mobile:</label>
                                                <input type="text" name="sender_mobile_number" id="sender_mobile_number" onkeypress="return isNumber(event)" class="form-control" minlength="10" maxlength="10"  placeholder="Enter Mobile No">
                                                <span id="message"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-1 col-sm-12 align-text-bottom" style="text-align: center;">
                                            <h4 class="red-font"><b>OR</b></h4>
                                        </div>
                                       
                                        <div class="col-md-3 col-sm-12">
                                            <div class="form-group">
                                                <label class="card-title blue-font" for="sender_acc_number "><i class="mdi mdi-cellphone"></i> Account No:</label>
                                                <input type="text" name="sender_acc_number" id="sender_acc_number" class="form-control"  placeholder="Enter Account No" >
                                                <span id="message"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-sm-12">
                                        <button type="submit" class="btn success-grad btn-lg" id="sender-mob-sb-btn" style="margin-top:38px; height: calc(2.1rem + .75rem + 2px);">Search</button>
                                            
                                        </div>
                                
                                
                                    </div>
                                </form>
                               
                            </div>
                        </div>
                    </div>
                </div>        
                <div style="height:550px;"></div>
              
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


