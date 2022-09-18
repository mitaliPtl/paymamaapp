@extends('layouts.full_new')
@section('page_content')

<link rel="stylesheet" type="text/css" href="{{ asset('template_new/assets/libs/select2/dist/css/select2.min.css') }}">
<style>
    .nav-link{
        font-size:21px;
        padding:10px;
    }
   .progress {
      margin: 10px;
      width: 700px;
      
      font-size:21px;
    }
    .progress-bar{
        background:#008000;
    }
    .select2-selection__arrow{
        margin-top:8px;
    }
    .select2-selection__rendered{
        margin-top:4px;
    }
    .select2-container--default .select2-selection--single{
        border:1px solid #e1e1e1;
    }
    .nav-tabs .nav-menus.active {
    color: #ffffff;
    background-color: #008000;
    border:none !important;
    }
</style>
<script>
    showPosition();
    function showPosition() {
        if(navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var positionInfo = "Latitude: " + position.coords.latitude + ", " + "Longitude: " + position.coords.longitude;
               
                $('#latitude').val(position.coords.latitude);
                $('#longitude').val(position.coords.longitude);
                //document.getElementById("latitude").value = position.coords.latitude;
               // document.getElementById("longitude").value = position.coords.longitude;
                $('#infoDiv').attr('class','alert alert-dismissible fade show alert-success');
                $('#info').css('color','green');
                $('#info').text('Location fetched successfully');
            });
        } else {
            $('#infoDiv').attr('class','alert alert-dismissible fade show alert-danger');
            $('#info').css('color','red');
            $('#info').text('Oops, your browser does not support geolocation.');
        }
    }
</script>
<script type="text/javascript">
    @if(Session::has('msuccess'))
    window.onload = function() {
       // $('#successModal').modal('show');
        $('#successModalnew').modal('show');
    };
    @endif
    @if(Session::has('merror'))
    window.onload = function() {
       // $('#errorModal').modal('show');
        $('#errorModalnew').modal('show');
    };
    @endif
</script>
<script src="{{ asset('dist/other/js/aeps.js') }}"></script>
<!--New Transaction Success screen as icici-->
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
            margin-left:52%;
            padding: 20px 20px 20px 20px;
            -webkit-animation-name: modal-animation;
            -webkit-animation-duration: 0.5s;
            animation-name: modal-animation;
            animation-duration: 0.5s;
        }
        .modals .modal-contents {
            margin-left:158%;
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
    @if(Session::get('msuccess1'))
    <script type="text/javascript">
        $(window).on('load', function() {
            $('#successModalnew').modal('show');
        });
    </script>
     <div class="modal fade" id="successModalnew" role="dialog">
            <div class="modal-dialog">
    
              <!-- Modal content-->
              <div class="modal-content" style="width:870px !important;">
        
                <div class="modal-body" style="border:11px solid green;">
                   <div class="row">
                                <div class="col-md-6 col-lg-6 col-sm-12"><img src="{{asset('template_assets/assets/images/logos/Paymama_330x90.png')}}" style="margin-top:10px;height:67px;width:85%;margin-bottom:12px;"></div>
                                <div class="col-md-6 col-lg-6 col-sm-12"><img src="{{asset('template_assets/icicibank.png')}}" style="margin-left:18px;margin-right:-10px;margin-top:0px;height:67px;width:85%;margin-bottom:12px;"></div>
                            </div>
                    <hr style="border:1px solid red;">
                   
                    <h2 style="color:green;text-align:center;font-weight:600;">
                        <i class="far fa-check-circle text-success" style="font-size: 74px;line-height: 1.1;color:#008000!important; display: inline-block !important;"></i>
                        <center><h2>Success!</h2><br>
                        <h1 style="text-align:center" style="font-size:25px !important;">{{ Session::get('msuccess') }}</h1></center>
                       
                    <br>
                    <style>
                        td{
                            font-weight:normal !important;
                        }
                    </style>
                        <div class="row">
                            <div class="col-md-12">
                                 <table class="table table-bordered table stripped">
                                    
                                   <tr>
                                        <td style="font-size:17px;">AMOUNT</td><td style="font-size:17px;">{{ Session::get('transaction_amount') }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:17px;">DATE & TIME</td><td style="font-size:17px;">{{ Session::get('time') }}</td>
                                    </tr>
                                     <tr>
                                        <td style="font-size:17px;">AVAILABLE BALANCE</td><td style="font-size:17px;">{{ Session::get('balance_amount') }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:17px;">AADHAR NUMBER</td><td style="font-size:17px;">{{ Session::get('aadharNumber') }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:17px;">BANK</td><td style="font-size:17px;">{{ Session::get('bank_name') }}</td>
                                    </tr>
                                    
                                    <tr>
                                        <td style="font-size:17px;">ORDER ID</td><td style="font-size:17px;">{{ Session::get('order_id') }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:17px;">TRANSACTION ID</td><td style="font-size:17px;">{{ Session::get('transaction_id') }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:17px;">CLIENT REFRENCE ID</td><td style="font-size:17px;">{{ Session::get('clientreferenceid') }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:17px;">RRN NUMBER</td><td style="font-size:17px;">{{ Session::get('bankrrn') }}</td>
                                    </tr>
                                   
                                </table>    
                           
                            </div>
                   
                   </div>
            <div class="modal-footer">
               <!--<button type="button" id="view-recipt"  onclick="getBillSurcharge( `{{ Session::get('order_id') }}` )" class="btn btn-warning btn-sm"><i class="mdi mdi-printer"></i> View </button>-->
            
                     <button type="button" class="btn btn-lg success-grad" onclick="getBillSurcharge( `{{ Session::get('order_id') }}` )" data-dismiss="modal" onclick="window.print();">Print Receipt</button>
                <button type="button" class="btn btn-lg btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
      
    </div>
  </div>
  </div>
  {{Session::forget('msuccess1')}}
    @elseif(Session::get('pkmkb'))
    <script type="text/javascript">
    $(window).on('load', function() {
        $('#errorModalnew').modal('show');
    });
    </script>
      <div class="modal fade" id="errorModalnew" role="dialog">
            <div class="modal-dialog">
    
            
              <div class="modal-content" style="width:870px !important;">
        
                <div class="modal-body" style="border:11px solid green;">
                   <div class="row">
                                <div class="col-6"><img src="{{asset('template_assets/assets/images/logos/Paymama_330x90.png')}}" style="margin-top:10px;height:90px;width:330px;margin-bottom:12px;"></div>
                                <div class="col-6"><img src="{{asset('template_assets/icicibank.png')}}" style="margin-left:18px;margin-right:-10px;margin-top:0px;height:90px;width:330px;margin-bottom:12px;"></div>
                            </div>
                    <hr style="border:1px solid red;">
                   
                    <h3 style="color:red;text-align:center;font-weight:600;">
                       {{ Session::get('pkmkb') }}</h3>
                       
                    <br>
                        <div class="row">
                            <div class="col-md-12">
                                  
                           
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
  {{Session::forget('pkmkb')}}
  @else
  @endif
    @if(Session::has('msuccess'))
    <div class="modal fade" id="successModalnew" role="dialog">
            <div class="modal-dialog">
    
              <!-- Modal content-->
              <div class="modal-content" style="width:870px !important;">
        
                <div class="modal-body" style="border:11px solid green;">
                    <div class="row">
                                <div class="col-6"><img src="{{asset('template_assets/assets/images/logos/Paymama_330x90.png')}}" style="margin-top:10px;height:90px;width:330px;margin-bottom:12px;"></div>
                                <div class="col-6"><img src="{{asset('template_assets/icicibank.png')}}" style="margin-left:18px;margin-right:-10px;margin-top:0px;height:90px;width:330px;margin-bottom:12px;"></div>
                    </div>
                    <hr style="border:1px solid red;">
                   
                    <h2 style="color:green;text-align:center;font-weight:600;">
                       {{ Session::get('msuccess') }}</h2>
                       
                    <br>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered table stripped">
                                    
                                   <tr>
                                        <td style="font-size:17px;">AMOUNT</td><td style="font-size:17px;">{{ Session::get('transaction_amount') }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:17px;">DATE & TIME</td><td style="font-size:17px;">{{ Session::get('time') }}</td>
                                    </tr>
                                     <tr>
                                        <td style="font-size:17px;">AVAILABLE BALANCE</td><td style="font-size:17px;">{{ Session::get('balance_amount') }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:17px;">AADHAR NUMBER</td><td style="font-size:17px;">{{ Session::get('aadharNumber') }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:17px;">BANK</td><td style="font-size:17px;">{{ Session::get('bank_name') }}</td>
                                    </tr>
                                    
                                    <tr>
                                        <td style="font-size:17px;">ORDER ID</td><td style="font-size:17px;">{{ Session::get('order_id') }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:17px;">TRANSACTION ID</td><td style="font-size:17px;">{{ Session::get('transaction_id') }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:17px;">CLIENT REFRENCE ID</td><td style="font-size:17px;">{{ Session::get('clientreferenceid') }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:17px;">RRN NUMBER</td><td style="font-size:17px;">{{ Session::get('bankrrn') }}</td>
                                    </tr>
                                   
                                </table>   
                           
                            </div>
                   
                   </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-lg success-grad"  onclick="getBillSurcharge( `{{ Session::get('order_id') }}` )" data-dismiss="modal">Print Receipt</button>
                <button type="button" class="btn btn-lg btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
      
    </div>
  </div>
  </div>
    @endif
    
    @if(Session::has('merror'))
    <div class="modal fade" id="errorModalnew" role="dialog">
            <div class="modal-dialog">
    
              <!-- Modal content-->
              <div class="modal-content" style="width:870px !important;">
        
                <div class="modal-body" style="border:11px solid green;">
                   <div class="row">
                                <div class="col-6"><img src="{{asset('template_assets/assets/images/logos/Paymama_330x90.png')}}" style="margin-top:10px;height:90px;width:330px;margin-bottom:12px;"></div>
                                <div class="col-6"><img src="{{asset('template_assets/icicibank.png')}}" style="margin-left:18px;margin-right:-10px;margin-top:0px;height:90px;width:330px;margin-bottom:12px;"></div>
                            </div>
                    <hr style="border:1px solid red;">
                   
                    <h3 style="color:red;text-align:center;font-weight:600;">
                       {{ Session::get('merror') }}</h3>
                       
                    <br>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered table stripped">
                                    
                                   
                                    
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
    @endif

<!--Ends Here-->
<div class="page-content container-fluid">
    <div id="successModal" class="modal" tabindex="-1" role="document">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body" style="text-align: center;padding-left: 20px;padding-right: 20px;">
                    <i class="far fa-check-circle text-success" style="font-size: 100px;line-height: 1.1;margin-top: 20px;margin-bottom: 20px;display: inline-block !important;"></i>
                    <h2>Success!</h2>
                    <h4>{{ Session::get('msuccess') }} {{  Session::forget('msuccess') }}</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div id="errorModal" class="modal" tabindex="-1" role="document">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body" style="text-align: center;padding-left: 20px;padding-right: 20px;">
                    <i class="fas fa-times text-danger" style="font-size: 100px;line-height: 1.1;margin-top: 20px;display: inline-block !important;"></i>
                    <h2>Error!</h2>
                    <h4>{{ Session::get('merror') }} {{  Session::forget('merror') }}</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
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
    <div class="alert alert-dismissible fade show alert-danger" id="infoDiv" role="alert">
        <strong><span id="info" style="color:red;"></span></strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    
    <section>
         <div class="row" style="padding:20px;margin-top:-15px;">
                    
                        <div class="card card-body">
                        <div class="row">
                        <div class="col-7">
                          <div class="row" style="border-bottom:3px solid red; position: relative;">
                               <div class="col-6"><img src="{{asset('template_assets/assets/images/logos/Paymama_330x90.png')}}" style="margin-top:10px;height:60px;width:80%;margin-bottom:12px;"></div>
                                <div class="col-6"><img src="{{asset('template_assets/icicibank.png')}}" style="margin-left:18px;margin-right:-10px;margin-top:0px;height:60px;width:80%;margin-bottom:12px;"></div>
                              </div>          
                            <br>
                        <nav style="margin-bottom:15px;">
                            <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                                <a class="nav-item nav-link nav-menus active" id="nav-contact-tab" data-toggle="tab" href="#nav-cashwithdrawal" role="tab" aria-controls="nav-contact" aria-selected="false" style="border-radius:20px;">Aadhar Pay</a>
                                
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                           
                            <div class="tab-pane fade show active" id="nav-cashwithdrawal" role="tabpanel" aria-labelledby="nav-cashwithdrawal">
                                <form method="post"  autocomplete="off"  action="{{ route('aeps_transaction') }}" onfocus="showPosition()">
                            @csrf
                            <input type="hidden" id="longitude" class="form-control" name="longitude">
                            <input type="hidden" id="latitude" class="form-control" name="latitude">
                            <input type="hidden" id="" class="form-control" name="service_type" value="aadhar_payment">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-6">
                                          <div class="form-group">
                                                <label for="bank">Select Bank</label><br>
                                                <select class="select2 form-control custom-select" id="bank_name1" name="bank_name" required="" style="height:52px;width:100%;">
                                                    @foreach(json_decode($aeps_bank,true)['data'] as $id=>$bank)
                                                    @if($id != 0)
                									<option value="{{ $bank['iinno'] }}">{{ $bank['bankName'] }}</option>
                									@endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                       
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="name">Aadhar Number</label>
                                                <input type="text" class="form-control aadharNumber" id="" name="aadharNumber" maxlength="14" required="">
                                                
                                            </div>
                                        </div>
                                       
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-6">
                                           <div class="form-group">
                                                <label for="name">Mobile Number</label>
                                                <input type="text" class="form-control" name="mobileNumber" maxlength="10" required="">
                                            </div>
                                        </div>
                                       
                                    </div>
                                    <div class="row">
                                       <div class="col-6">
                                           <div class="form-group">
                                            <label for="name">Amount</label>
                                            <input type="text" id="aepsAmount" class="form-control" name="amount" maxlength="6" onkeyup="getcharge();">
                                            
                                            <!--<b>Charge:Rs. </b>-->
                                            <!--<span class="result" style="font-size:15px;">After writing Amount, Just Press Tab to Know Charge</span>-->
                                           </div>
                                           
                                        </div>
                                    </div>
                                    <div class="row">
                                       <div class="col-6">
                                           <div class="form-group">
                                            <label for="name">Charge:Rs </label>
                                            <input id="result_charge" class="form-control" type="text" id="" name="" maxlength="6" readonly>
                                            
                                            
                                           </div>
                                           
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                           <div class="form-group">
                                                <label for="name">MPIN</label>
                                                <input type="text" id="mpin" class="form-control" maxlength="4" name="mpin" required>
                                            </div>
                                        </div>
                                       
                                    </div>
                                    <div style="">
                	                <textarea style="display:none" type="hidden" class="form-control" id="txtPidDatas" rows="5" name="txtPidData"></textarea>
									<textarea  style="display:none" type="hidden" class="form-control" id="txtPidData" rows="5" name="txtPidData"></textarea>
								    <textarea style="display:none"  type="hidden" class="form-control" id="txtPidOptions" name="PidOptions" style="width: 100%; height: 100px;"></textarea>
								    <input type="hidden" class="form-control" id="txtDeviceInfo" style="width: 100%; height: 160px;">
								    <!--<input type="text" id="longitude" class="form-control" name="longitude" required>-->
								    <!--<input type="text" id="latitude" class="form-control" name="latitude" required>-->
								</div>
                                    
                                  
                                </div>
        
                                <div class="col-md-12">
                                    <button type="submit" class="btn primary btn-lg" style="width:150px;background-color:green;color:white;">Submit</button>
                                    <button type="reset" class="btn success-grad btn-lg" style="width:150px;">Clear Data</button>
                                   <!-- <a type="button" href="{{ route('home') }}" class="btn btn-dark">Cancel</a>-->
                                </div>
                            </div>
                    </form>
                            </div>
                        </div>
                    
                       
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" ></script>
                       
                                   <script>
                            $(document).on("focusout","#aepsAmount",function(){
                            var amount=$('#aepsAmount').val();
                                    
                                    // var user_id = $(this).data('id'); 
                                    // var service_id = $(this).data('service_id'); 
                                    
                                    $.ajax({
                                        type: "GET",
                                        dataType: "json",
                                        url: '/getcharge',
                                        data: {'amount': amount},
                                        success: function(data){
                                           $("#result_charge").val(data);    
                                          
                                        }
                                    });
                            });
                                 
                              
                    </script>
                    <style>
                        .nav-tabs
                        {
                            border:none !important;
                        }
                        label{
                        font-size:20px;
                            
                        }
                        :root {
                          --borderWidth: 7px;
                          --height: 24px;
                          --width: 12px;
                          --borderColor: #78b13f;
                        }
                        
                        .check {
                          display: inline-block;
                          height: var(--height);
                          width: var(--width);
                         
                        }
                        
                        a:active{
                           
                              content: '✓';
                            
                        }
                        a.active:after { 
                            /*content: "∧"; */
                             content: '✓';
                             color:green;
                             padding-left:10px;
                            font-family: FontAwesome;
                            font-style: normal;
                            font-weight: bold;
                            text-decoration: inherit;
                        }
                    </style>
                    
                    </div>
                                    <!--Sidebar fingerprint Zone-->
                                    <div class="col-5 well" style="border-left:3px solid red;">
                                        <nav>
                                            <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                                              <a style="border:none !important;" class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-mantra" role="tab" aria-controls="nav-home" aria-selected="true"><div class="mantra"></div><img src="{{ asset('template_assets/mantra.png') }}" style="height:100px;width:100%;">Mantra</a>
                                                <a  style="border:none !important;" class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-morpho" role="tab" aria-controls="nav-profile" aria-selected="false"><div class="morpho"></div><img src="{{ asset('template_assets/morpho.png') }}" style="height:100px;width:100%;">Morpho</a>
                                                <a  style="border:none !important;" class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-startek" role="tab" aria-controls="nav-profile" aria-selected="false"><div class="Startek"></div><img src="{{ asset('template_assets/startek.jpg') }}" style="height:100px;width:100%;">Startek</a>
                                                <a  style="border:none !important;" class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-secugen" role="tab" aria-controls="nav-profile" aria-selected="false"><div class="secugen"></div><img src="{{ asset('template_assets/secugen.png') }}" style="height:100px;width:100%;">Secugen</a>
                                           </div>
                                        </nav>
                                         <div class="tab-content" id="nav-tabContent">
                                            <div class="tab-pane fade show active" id="nav-mantra" role="tabpanel" aria-labelledby="nav-home-tab" style="padding:20px;">
                                                <br>
                                                
                                               <div class="row">
                                                   <div class="col-sm-3">
                                                       <img src="{{ asset('template_assets/fingerprint.png') }}" style="height:100px;width:100px;border:1px solid #e1e1e1;">
                                                   </div>
                                                   <div class="col-sm-6" style="margin-left:15px;">
                                                       <div class="form-group">
                                                           
                                                            <label for="name"></label>
                                                            <!--<input type="button" id="btnRDInfo" onclick="discoverstartek();"/>-->
                                                            <button type="button"  style="font-size:19px;background-color:#008000 !important;color:white  !important;" onclick="CaptureAvdm();" class="form-control btn btn-success mr-2">Capture</button>
                                                       </div>
                                                   </div>
                                               </div>
                                               
                                               <br>
                                              
                                            </div>
                                            <div class="tab-pane fade" id="nav-startek" role="tabpanel" aria-labelledby="nav-home-tab" style="padding:20px;">
                                                <br>
                                               <div class="row">
                                                   <div class="col-sm-3">
                                                       <img src="{{ asset('template_assets/fingerprint.png') }}" style="height:100px;width:100px;border:1px solid #e1e1e1;">
                                                   </div>
                                                   <div class="col-sm-6" style="margin-left:15px;">
                                                       <div class="form-group">
                                                            <label for="name"></label>
                                                            <!--<input type="button" id="btnRDInfo" onclick="discoverstartek();"/>-->
                                                            <button type="button" style="font-size:19px;background-color:#008000 !important;color:white  !important;" onclick="captureFPAuth();" class="form-control btn btn-success mr-2">Capture</button>
                                                       </div>
                                                   </div>
                                               </div>
                                               
                                               <br>
                                               
                                            </div>
                                            
                                        </div>
                                         <div class="row">
                                                    <div class="progress" style="height:36px;">
                                                      <div id="dynamic" class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                                                        <span id="current-progress"></span>
                                                      </div>
                                                    </div> 
                                                    <center>
                                                    <p style="font-size:20px;margin-left:10px;">Recommended Fingerprint Score is 70%</p>
                                                    </center>
                                               </div>
                                        <br>
                                        <h3>Note:-</h3>
                                        <hr>
                                        <br>
                                        <p style="font-size:20px;">1. 30 Mins Time for 2nd Transaction for Same User.</p>
                                        <br><br>
                                        <p style="font-size:20px;">2.10,000/- Per Transaction.</p>
                                        <br><br>
                                        <p style="font-size:20px;">3. Aeps Helpline: 9133622161.</p>
                                    </div>
                                    <!--Sidebar Fingerprint Zone Ends-->
                            </div>
                    
                </div>
                    
                </div>
                	<!--<label class="main-content-label tx-11 tx-medium tx-gray-600 control-label">Captured Data</label>-->
                	            
        <!--<div class="row">
            <div class="col-12">
                <div class="card card-body">
                    <h4 class="card-title">Mobile Verification</h4>
                        <form method="post" action="{{ route('aeps_transaction') }}">
                        @csrf
                        <div class="row" style="display:none">	
    						<div class="col-md-12 col-lg-12 col-xl-12">
							  <div class="form-group">
									<label class="main-content-label tx-11 tx-medium tx-gray-600 control-label">Captured Data</label>
									<textarea type="text" class="form-control" id="txtPidData" rows="5" name="txtPidData" readonly></textarea>
								    <textarea type="text" class="form-control" id="txtPidOptions" name="PidOptions" readonly style="width: 100%; height: 100px;"></textarea>
								    <input type="text" class="form-control" id="txtDeviceInfo" style="width: 100%; height: 160px;">
								    <input type="text" id="longitude" class="form-control" name="longitude" required>
								    <input type="text" id="latitude" class="form-control" name="latitude" required>
								</div>
    						</div>
    					</div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="device_type">Select Biometric Device</label>
                                            <select class="form-control custom-select" id="device_type">
            									<option value="mantra" selected>Mantra</option>
            									<option value="morpho">Morpho</option>
            									<option value="startek">Startek</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="device_type">Select Service</label>
                                            <select class="form-control custom-select" id="service_type" name="service_type">
            									<option value="cash_withdrawal" selected>Cash Withdrawal</option>
            									<option value="balance_enquiry">Balance Enquiry</option>
            									<option value="mini_statement">Mini Statement</option>
            									<option value="aadhar_payment">Aadhar Payment</option>
            									<option value="cash_deposit">ICICI Cash Deposit</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="name"></label>
                                                    <button type="button" onclick="discoverAvdm();" class="form-control btn btn-success mr-2">Discover Device</button>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="name"></label>
                                                    <button type="button" onclick="CaptureAvdm();" class="form-control btn btn-success mr-2">Capture</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="name">Aadhar Number</label>
                                            <input type="text" class="form-control" name="aadharNumber" maxlength="12" required="">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="bank">Select Bank</label>
                                            <select class="form-control custom-select" id="bank_name" name="bank_name" required="">
                                                @foreach(json_decode($aeps_bank,true)['data'] as $id=>$bank)
                                                @if($id != 0)
            									<option value="{{ $bank['iinno'] }}">{{ $bank['bankName'] }}</option>
            									@endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="name">Mobile Number</label>
                                            <input type="text" class="form-control" name="mobileNumber" maxlength="10" required="">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4" id="aepsAmountDiv">
                                        <div class="form-group">
                                            <label for="name">Amount</label>
                                            <input type="text" id="aepsAmount" class="form-control" name="amount" maxlength="6">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="name">MPIN</label>
                                            <input type="text" id="mpin" class="form-control" maxlength="4" name="mpin" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success mr-2">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>-->
    </section>

</div>
<div class="modal fade" id="surchargeModal">
        <div class="modal-dialog modal-md">
        <div class="modal-content">
        
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Surcharge ? </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
           
            <!-- Modal body -->
                <div class="modal-body">
                    <div class="form-group btn-group">
                        <button type="button" class="btn btn-warning" style="pointer-events:none"><i class="mdi mdi-currency-inr"></i></button>
                        
                        <input type="hidden" id="recipt_ordere_id" value="">
                        <input type="hidden" id="web_url" value="{{ Config::get('constants.WEBSITE_BASE_URL') }}">

                       
                        <input type="text" id="inputsurCharge" class="form-control" placeholder="Enter here" ng-model="surCharge">
                        <button type="button" onclick="showInvice()" class="btn btn-info success-grad" ng-disabled="!surCharge" ng-click="showInvoice()">Proceed</button>
                    </div>
                </div>
        </div>
        </div>
    </div>
<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>

<script src="{{ asset('template_new\assets\libs\select2\dist\js\select2.full.min.js') }}"></script>
    <script src="{{ asset('template_new\assets\libs\select2\dist\js\select2.min.js') }}"></script>
    <script src="{{ asset('template_new\dist\js\pages\forms\select2\select2.init.js') }}"></script>
        <script src="{{ asset('dist\reports\js\rechargeReport.js') }}"></script>
<script>
    $('#service_type').on('change', function () {
         var aepsService = $("#service_type").val();
         console.log(aepsService);
         if(aepsService == 'cash_withdrawal' || aepsService == 'aadhar_payment' || aepsService == 'cash_deposit') {
             $('#aepsAmountDiv').css('display','block');
             $('#aepsAmount').prop('required',true);
         } else {
             $('#aepsAmountDiv').css('display','none');
             $('#aepsAmount').prop('required',false);
         }
    });
    $('.aadharNumber').keyup(function() {
        var foo = $(this).val().split("-").join(""); // remove hyphens
        if (foo.length > 0) {
        foo = foo.match(new RegExp('.{1,4}', 'g')).join("-");
        }
        $(this).val(foo);
    });   
                                                    
</script>
@endsection
