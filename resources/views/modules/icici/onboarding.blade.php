@extends('layouts.full_new')
@section('page_content')

<script>

    showPosition();
    function showPosition() {
        if(navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var positionInfo = "Latitude: " + position.coords.latitude + ", " + "Longitude: " + position.coords.longitude;
                document.getElementById("latitude").value = position.coords.latitude;
                document.getElementById("longitude").value = position.coords.longitude;
            });
        } else {
           alert("Sorry, your browser does not support geolocation.");
        }
    }
</script>
<div class="page-content container-fluid">
    @if(Session::has('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>SUCCESS</strong>  {{ Session::get('success') }}
        {{  Session::forget('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @elseif(Session::has('error')) 

    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>FAILED</strong>  {{ Session::get('error') }}
        {{  Session::forget('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <section>
    <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <script type="text/javascript">
    $(window).on('load', function() {
     function disableF5(e) { if ((e.which || e.keyCode) == 116) e.preventDefault(); };
$(document).on("keydown", disableF5);
    });
  

// simply visual, let's you know when the correct iframe is selected

</script>

        @isset($ftransid)

       <script type="text/javascript">
    $(window).on('load', function() {
        $('#myModalotp').modal('show');
    });
    
    </script>
        @endisset
        @isset($msg)

       <script type="text/javascript">
    $(window).on('load', function() {
        $('#myModalss').modal('show');
    });
    </script>
        @endisset
        @isset($beneficiaryName)

       <script type="text/javascript">
    $(window).on('load', function() {
        $('#myModals').modal('show');
    });
    </script>
        @endisset
        <script type="text/javascript">
    $(window).on('load', function() {
        $('#deleteModal').modal('show');
    });
    </script>
   
                 
 @isset($beneficiaryName)
 <style>
        /* Styling modal */
        .modal:before {
            content: '';
            display: inline-block;
            height: 100%;
            vertical-align: middle;
        }
        .modals:before {
            content: '';
            display: inline-block;
            height: 100%;
            vertical-align: middle;
        }
          
        .modal-dialog {
            display: inline-block;
            vertical-align: middle;
        }
          
        .modal .modal-content {
            margin-left:94%;
            padding: 20px 20px 20px 20px;
            -webkit-animation-name: modal-animation;
            -webkit-animation-duration: 0.5s;
            animation-name: modal-animation;
            animation-duration: 0.5s;
        }
        .modals .modal-contents {
            margin-left:94%;
            padding: 20px 20px 20px 20px;
            -webkit-animation-name: modal-animation;
            -webkit-animation-duration: 0.5s;
            animation-name: modal-animation;
            animation-duration: 0.5s;
        }
       
          
        @-webkit-keyframes modal-animation {
            from {
                top: -100px;
                opacity: 0;
            }
            to {
                top: 0px;
                opacity: 1;
            }
        }
          
        @keyframes modal-animation {
            from {
                top: -100px;
                opacity: 0;
            }
            to {
                top: 0px;
                opacity: 1;
            }
        }
    </style>
         <div class="modal fade" id="myModals" role="dialog">
            <div class="modal-dialog">
    
              <!-- Modal content-->
              <div class="modal-content" style="width:620px !important;">
        
                <div class="modal-body" style="border:11px solid green;">
                     <h2 class="card-title" style="text-align:center;color:red;font-weight:600;">Verify Bank Details</h2>
                     <hr style="border:1px solid blue;">
                        <form method="post" action="validate_transaction">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="otp">Account Holder Name</label>
                                            <input type="hidden" name="otp" value="{{$otp}}">
                                            <input type="hidden" name="lat" value="{{$lat}}">
                                            <input type="hidden" name="long" value="{{$long}}">
                                            <input type="hidden" name="transaction_id" value="{{$transaction_id}}">
                                            <input type="hidden" name="mobile" value="{{$mobile}}">
                                            <input type="hidden" name="fingpayTransactionId" value="{{$ftransid}}">
                                            <input type="hidden" name="cdPkId" value="{{$cdPkId}}">
                                             <input type="hidden" name="accountNumber" value="{{$accountNumber}}" class="form-control">
                                              <input type="hidden" name="beneficiaryName" value="{{$beneficiaryName}}" class="form-control">
                                            <input type="text" class="form-control" name="" value="{{$beneficiaryName}}"  class="form-control" readonly>
                                          </div>
                                    
                                    <div class="form-group">
                                            <label for="otp">Account Number</label>
                                            <input type="text" name="" value="{{$accountNumber}}" class="form-control" readonly>
                                           
                                    </div>
                                    <div class="form-group">
                                            <label for="otp">Amount</label>
                                            <input type="text" name="amount" value="{{$amount}}" class="form-control" readonly>
                                           
                                    </div>
                                    </div>
                                    </div>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success btn-lg">Transfer</button>
                               <a type="button" href="{{ route('icicionboarding') }}" class="btn success-grad btn-lg">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            <div class="modal-footer">

            </div>
        </div>
      
    </div>
  </div>
  
  @endisset
  
  <!--Final Confirmation Popup-->
  @isset($msg)
   <style>
        /* Styling modal */
        .modal:before {
            content: '';
            display: inline-block;
            height: 100%;
            vertical-align: middle;
        }
        .modals:before {
            content: '';
            display: inline-block;
            height: 100%;
            vertical-align: middle;
        }
          
        .modal-dialog {
            display: inline-block;
            vertical-align: middle;
        }
          
        .modal .modal-content {
            margin-left:120%;
            padding: 20px 20px 20px 20px;
            -webkit-animation-name: modal-animation;
            -webkit-animation-duration: 0.5s;
            animation-name: modal-animation;
            animation-duration: 0.5s;
        }
        .modals .modal-contents {
            margin-left:94%;
            padding: 20px 20px 20px 20px;
            -webkit-animation-name: modal-animation;
            -webkit-animation-duration: 0.5s;
            animation-name: modal-animation;
            animation-duration: 0.5s;
        }
       
          
        @-webkit-keyframes modal-animation {
            from {
                top: -100px;
                opacity: 0;
            }
            to {
                top: 0px;
                opacity: 1;
            }
        }
          
        @keyframes modal-animation {
            from {
                top: -100px;
                opacity: 0;
            }
            to {
                top: 0px;
                opacity: 1;
            }
        }
    </style>
    <div class="modal fade" id="myModalss" role="dialog">
            <div class="modal-dialog">
    
              <!-- Modal content-->
              <div class="modal-content" style="width:870px !important;">
        
                <div class="modal-body" style="border:11px solid green;">
                   <div class="row">
                                <div class="col-6"><img src="{{asset('template_assets/assets/images/logos/Paymama_330x90.png')}}" style="margin-top:10px;height:90px;width:330px;margin-bottom:12px;"></div>
                                <div class="col-6"><img src="{{asset('template_assets/icicibank.png')}}" style="margin-left:18px;margin-right:-10px;margin-top:0px;height:90px;width:330px;margin-bottom:12px;"></div>
                            </div>
                    <hr style="border:1px solid red;">
                     @if($msg=="ERR:Invalid Transaction" or $msg=="ERR:Insufficient Balance" or $msg=="Insufficient Balance")
                        
                    <h2 style="color:red;text-align:center;font-weight:600;">
                       Transaction Failure</h2>
                    @elseif($msg=="Transaction is in process, please check history after 2 minutes.")
                     <h2 style="color:#db6614db;text-align:center;font-weight:600;">
                       Transaction in Process</h2>  
                    @else
                    <h2 style="color:green;text-align:center;font-weight:600;">
                       Transaction Successfull</h2>
                        @endif
                    <br>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered table stripped">
                                    
                                    <tr>
                                        <td style="font-size:17px;">Account Holder Name</td><td style="font-size:17px;">{{$acholdername}}</td>
                                    </tr>
                                     <tr>
                                        <td style="font-size:17px;">Account Number</td><td style="font-size:17px;">{{$acno}}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:17px;">Amount</td><td style="font-size:17px;">{{$amount}}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:17px;">Order ID</td><td style="font-size:17px;">{{$orderid}}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:17px;">RRN Number</td><td style="font-size:17px;">{{$fpRrn}}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:17px;">Transaction ID</td><td style="font-size:17px;">{{$fingpayTransactionId}}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:17px;">Client Reference ID</td><td style="font-size:17px;">{{$fpRrn}}</td>
                                    </tr>
                                     <tr>
                                        <td style="font-size:17px;">Response Message</td><td style="font-size:17px;">{{$msg}}</td>
                                    </tr>
                                    
                                    
                                </table>   
                           
                            </div>
                   
                   </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-lg success-grad" data-dismiss="modal">Print Receipt</button>
                <button type="button" class="btn btn-lg btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
      
    </div>
  </div>
  </div>
  @endisset
  <!--Final Confirmation Ends
  <!--Modal for veriying otp-->
  
  @isset($ftransid)
  @php
  if (isset($beneficiaryName)) 
  { }
  else if (isset($msg)) 
  { }
  else{
  @endphp
    <style>
        /* Styling modal */
        .modal:before {
            content: '';
            display: inline-block;
            height: 100%;
            vertical-align: middle;
        }
        .modals:before {
            content: '';
            display: inline-block;
            height: 100%;
            vertical-align: middle;
        }
          
        .modal-dialog {
            display: inline-block;
            vertical-align: middle;
        }
          
        .modal .modal-content {
            margin-left:122%;
            padding: 20px 20px 20px 20px;
            -webkit-animation-name: modal-animation;
            -webkit-animation-duration: 0.5s;
            animation-name: modal-animation;
            animation-duration: 0.5s;
        }
        .modals .modal-contents {
            margin-left:122%;
            padding: 20px 20px 20px 20px;
            -webkit-animation-name: modal-animation;
            -webkit-animation-duration: 0.5s;
            animation-name: modal-animation;
            animation-duration: 0.5s;
        }
       
          
        @-webkit-keyframes modal-animation {
            from {
                top: -100px;
                opacity: 0;
            }
            to {
                top: 0px;
                opacity: 1;
            }
        }
          
        @keyframes modal-animation {
            from {
                top: -100px;
                opacity: 0;
            }
            to {
                top: 0px;
                opacity: 1;
            }
        }
    </style>
  <div class="modal fade" id="myModalotp" role="dialog" style="margin-left:-5%;">
            <div class="modal-dialog">
    
              <!-- Modal content-->
              <div class="modal-content" style="width:443px !important;">
        
                <div class="modal-body">
                    <form method="post" action="validate_iciciotp">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="otp">Enter an OTP received on : <span style="color:red">{{$mobile}}</span></label>
                                        <input type="hidden" name="lat" value="{{$lat}}">
                                            <input type="hidden" name="long" value="{{$long}}">
                                            <input type="hidden" name="accountNumber" value="{{$accountNumber}}">
                                            <input type="hidden" name="transaction_id" value="{{$transaction_id}}">
                                            <input type="hidden" name="mobile" value="{{$mobile}}">
                                            <input type="hidden" name="fingpayTransactionId" value="{{$ftransid}}">
                                            <input type="hidden" name="cdPkId" value="{{$cdPkId}}">
                                            <input type="hidden" name="amount" value="{{$amount}}">
                                            <input type="text" maxlength="10" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" class="form-control" name="otp" required>
                                        </div>
                                    </div>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" style="margin-left:-18px;" class="btn success-grad btn-lg">Verify</button>
                                
                            </div>  
                        </div>
                    </form>
                    <form method="post" action="iciciresendcashdeposit">
                        @csrf <input type="hidden" name="mpin" value="{{$mpin}}">
                                     <input type="hidden" name="latitude" value="{{$lat}}">
                                            <input type="hidden" name="longitude" value="{{$long}}">
                                            <input type="hidden" name="accountnumber" value="{{$accountNumber}}">
                                            <input type="hidden" name="transaction_id" value="{{$transaction_id}}">
                                            <input type="hidden" name="mobileNumber" value="{{$mobile}}">
                                             <input type="hidden" name="amount" value="{{$amount}}">
                                            
                                <button type="submit" style="margin-left: 108px;
    margin-top: -66px;" class="btn btn-success btn-lg">Resend</button>
                                </form>
                    <form id="resend" method="post" action="{{ route('resend_otp') }}" style="display:none;">@csrf</form>
                </div>
            <div class="modal-footer">
            </div>
        </div>
      
    </div>
  </div>
  </div>
  <!--End Modal for Verifying otp-->
  @php
   }
   @endphp
   @endisset
   <div id="loader"></div>
   
        <div class="row" style="margin-left:20px;">
            <div class="col-7">
                <div class="row">
                    
                        <div class="card card-body">
                            <div class="row">
                                <div class="col-6"><img src="{{asset('template_assets/assets/images/logos/Paymama_330x90.png')}}" style="margin-top:10px;height:80px;width:260px;margin-bottom:12px;"></div>
                                <div class="col-6"><img src="{{asset('template_assets/icicibank.png')}}" style="margin-left:18px;margin-right:-10px;margin-top:0px;height:80px;width:230px;margin-bottom:12px;"></div>
                            </div>
                            
                            
                            
                            <hr style="border:1px solid red" />
                    <div class="col-12">
                        <form method="post" action="icicicashdeposit" onfocus="showPosition()" autocomplete="off">
                        @csrf
                        <input type="hidden" id="longitude" class="form-control" name="longitude">
                        <input type="hidden" id="latitude" class="form-control" name="latitude">
                        <input type="hidden" id="" class="form-control" name="service_type" value="generate_otp">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="address">Account Number</label>
                                            <input type="text" class="form-control" name="accountno" required="">
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                       <div class="form-group">
                                            <label for="mobileNumber">Customer Mobile Number</label>
                                            <input type="number" class="form-control" name="mobileNumber" required="">
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                       <div class="form-group">
                                            <label for="mobileNumber">Amount (Rs. 10000 Per Transaction)</label>
                                            <input type="number" class="form-control" name="amount"  required="">
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                       <div class="form-group">
                                            <label for="mobileNumber">Mpin</label>
                                            <input type="number" class="form-control" name="mpin"  required="">
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="row">
                                   
                                </div>
                              
                            </div>
    
                            <div class="col-md-12">
                                <button type="submit" class="btn success-grad btn-lg">Submit</button>
                               <!-- <a type="button" href="{{ route('home') }}" class="btn btn-dark">Cancel</a>-->
                            </div>
                        </div>
                    </form>
                    <style>
                        label{
                        font-size:20px;
                            
                        }
                        }
                    </style>
                    
                    </div>
                    
                </div>
                    
                </div>
            </div>
            
        </div>
    </section>

</div>
<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<!--<script src="{{ asset('template_assets/other/js/angular.min.js') }}"></script>-->
@endsection
