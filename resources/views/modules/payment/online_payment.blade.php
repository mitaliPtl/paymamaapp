{{--@extends('layouts.full') --}}
@extends('layouts.full_new')

@section('page_content')
<style>
.btn-outline-primary {
     color:#276109;
     border-color:#276109
}
.btn-outline-success:hover {
     color:#fff;
     background-color:#276109;
     border-color:#276109
}
.btn-outline-primary.focus,
.btn-outline-primary:focus {
     box-shadow:0 0 0 .2rem rgba(59,136,148,1)
}
.btn-outline-primary:hover, .btn-outline-primary:focus, .btn-outline-primary:active, .btn-outline-primary.active, .open>.dropdown-toggle.btn-outline-primary {
    color: #fff;
    background-color: #276109;
    border-color: #276109; /*set the color you want here*/
}
.btn-check:active + .btn-outline-primary,
.btn-check:checked + .btn-outline-primary,
.btn-outline-primary.active,
.btn-outline-primary.dropdown-toggle.show,
.btn-outline-primary:active {
    background-color: #276109 !important;
    color: white;
    border-color: #ffffff;
}
</style>
<script>
    var ready = false;
    $(document).ready(function () {
        ready = true;
    });
    $(window).on('load', function() {
        ready = true;
    });
    if(ready == false) {
        $('.preloader').css("display", "block");
    }
</script>
@isset($orderid)

<script type="text/javascript">
$(window).on('load', function() {
    $('#myModalss').modal('show');
});
</script>
@endisset
<script type="text/javascript">
    @if(Session::has('msuccess'))
    $(window).on('load', function() {
        $('#successModal').modal('show');
    });
    @endif
    @if(Session::has('merror'))
    $(window).on('load', function() {
        $('#errorModal').modal('show');
    });
    @endif
</script>
  @isset($orderid)
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
    <div class="modal fade" id="myModalss" role="dialog">
            <div class="modal-dialog">
    
              <!-- Modal content-->
              <div class="modal-content" style="width:870px !important;">
        
                <div class="modal-body" style="border:11px solid green;">
                   <div class="row">
                        <div class="col-4"></div>
                        <div class="col-4"><i class="far fa-check-circle" style="color:green;font-size: 100px;line-height: 1.1;margin-top: 20px;margin-bottom: 20px;margin-left: 50px;display: inline-block !important;"></i></div>
                        <div class="col-4"></div>
                    </div>
                    <hr style="border:1px solid red;">
                    <h2 style="color:green;text-align:center;font-weight:600;">Transaction Successfull</h2>
                    <br>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered table stripped">
                                    <tr>
                                        <td style="font-size:17px;">Amount</td><td style="font-size:17px;">{{$amount}}</td>
                                    </tr>
                                     <tr>
                                        <td style="font-size:17px;">Order ID</td><td style="font-size:17px;">{{$orderid}}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:17px;">Mode</td><td style="font-size:17px;">{{$mode}}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:17px;">RRN</td><td style="font-size:17px;">{{$rrn}}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:17px;">Date & Time</td><td style="font-size:17px;">{{$date}}</td>
                                    </tr>
                                </table>   
                           
                            </div>
                   
                   </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-lg btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
      
    </div>
  </div>
  </div>
  @endisset
<div class="page-content container-fluid">
<section>
<!-- This page plugin CSS -->
<link href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css"
        href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('template_assets\other\css\bootstrap-toggle.min.css') }}">
<!-- <link rel="stylesheet" type="text/css" href="{{ asset('dist\payment\css\online_payment.css') }}"> -->
<!-- Online Payment starts -->
<div class="page-breadcrumb border-bottom mb-3 mt-3">
    <div class="row">
        <div class="col-lg-4 col-md-4 col-xs-12 align-self-center">
            <h2 class="font-medium text-uppercase mb-0">Online Payment</h2>
        </div>
        <div class="col-lg-4 col-md-4 col-xs-12 align-self-center">
            <h2 class="font-medium text-uppercase mb-0">Minimum Load : Rs. {{ Auth::user()->min_amount_deposit }}</h2>
        </div>
        <div class="col-lg-4 col-md-4 col-xs-12 align-self-center">
            <h2 class="font-medium text-uppercase mb-0">Maximum Load : Rs. {{ Auth::user()->max_amount_deposit }}</h2>
        </div>
    </div>
</div>
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
        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <div class="row">
            <!-- Column -->
            @if(Auth::user()->pg_status == '1')
            <div class="col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button>	
                                    <strong>{{ $message }}</strong>
                            </div>
                        @endif
                        @if ($message = Session::get('error'))
                            <div class="alert alert-danger alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button>	
                                    <strong>{{ $message }}</strong>
                            </div>
                        @endif
                        <form action="{{ route('payment') }}" method="post">
                            @csrf
                            <div class="row row-sm">
                                <div class="col-lg-1 col-md-1"></div>
                                <div class="col-lg-4 col-md-4">
                                    @php $i = 1;@endphp
                                    @foreach($options as $option)
                                    @if($option['status'] == '1')
                                    @php $id = $i++ @endphp 
                                    <div class="row">
                                        <input type="radio" hidden class="btn-check" name="pay_mode" id="option{{ $id }}" value="{{ strtolower($option['mode']) }}" autocomplete="off" required>
                                        <label style="width:100%" class="btn btn-outline-primary font-weight-medium me-2 mb-2 font-16" for="option{{ $id }}">{{ str_replace('_',' ',$option['mode']) }}</label>
                                    </div>
                                    <br>
                                    @endif
                                    @endforeach
                                </div>
                                <div class="col-lg-7 col-md-7">
                                    <div class="row row-sm">
                                        <div class="col-lg-1 col-md-1"></div>
                                        <div class="col-lg-10 col-md-10">
                                            <div class="align-items-center justify-content-center">
                                                <h1 class="mt-0"><i class="mdi mdi-monitor text-info" style="font-size:80px;"></i></h1>
                                                <h3 class="font-medium text-uppercase mb-0">PAYMAMA GATEWAY</h3>
                                            </div>
                                        </div>
                                        <div class="col-lg-1 col-md-1"></div>
                                    </div>
                                    <br>
                                    <div class="row row-sm">
                                        <div class="col-lg-1 col-md-1"></div>
                                        <div class="col-lg-10 col-md-10">
                                            <input type="text" class="form-control" name="pay_amount" required placeholder="Enter an Amount"/>
                                        </div>
                                        <div class="col-lg-1 col-md-1"></div>
                                    </div>
                                    <br>
                                    <div class="row row-sm">
                                        <div class="col-lg-1 col-md-1"></div>
                                        <div class="col-lg-10 col-md-10">
                                            <button type="submit" class="form-control btn success-grad font-18">PAY NOW</button>
                                        </div>
                                        <div class="col-lg-1 col-md-1"></div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <h3 class="font-medium text-uppercase mb-0">Payment Gateway Charges</h3><br>
                        <table class="table table-bordered">
                            <tr>
                                <th>MODE</th>
                                <th>CHARGE</th>
                            </tr>
                            @foreach($options as $pgOptions)
                            @if($pgOptions['status'] == '1')
                            <tr>
                                <td>{{ str_replace('_',' ',$pgOptions['mode']) }}</td>
                                <td>{{ $pgOptions['charge'] . $pgOptions['type'] }}</td>
                            </tr>
                            @endif
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
             <!--Column -->
             @else
             <div class="col-lg-12 col-md-4">
                 <div class="card">
                     <div class="card-body text-center">
                        <h2>You are not allowed to perform this action please contact customer support</h2>
                        <h4>+918374913154 , 040-29563154</h4>
                    </div>
                 </div>
             </div>
             @endif
        </div>
</div>
@if(isset($response))
@php
//print_r($response);
@endphp
<form action="{{ route('paymentStatus') }}" method="POST" hidden style="display:none;">
    <input type="hidden" value="{{csrf_token()}}" name="_token"/>
    <input type="hidden" class="form-control" id="rzp_paymentid" name="rzp_paymentid">
    <input type="hidden" class="form-control" id="rzp_orderid" name="rzp_orderid">
    <input type="hidden" class="form-control" id="rzp_signature" name="rzp_signature">
    <input type="hidden" name="user_id" id="user_id" value="{{ Auth::user()->userId }}">
    <input type="hidden" name="username" id="username" value="{{ Auth::user()->username }}">
    <input type="hidden" name="mobile" id="mobile" value="{{ Auth::user()->mobile }}">
    <input type="hidden" name="name" id="name" value="{{ Auth::user()->name }}">
    <input type="hidden" name="email" id="email" value="{{ Auth::user()->email }}">
    <button type="submit" id="rzp-paymentresponse" hidden class="btn btn-primary"></button>
</form>
<input type="hidden" value="{{ $response['razorpayId'] }}" id="razorpayId">
<input type="hidden" value="{{ $response['amount'] }}" id="rzp_amount">
<input type="hidden" value="{{ $response['currency'] }}" id="rzp_currency">
<input type="hidden" value="PayMama" id="rzp_brandname">
<input type="hidden" value="{{ $response['description'] }}" id="rzp_description">
<input type="hidden" value="{{ $response['orderId'] }}" id="rzp_orderId">
<input type="hidden" value="{{ $response['name'] }}" id="rzp_name">
<input type="hidden" value="{{ $response['store_name'] }}" id="rzp_store_name">
<input type="hidden" value="{{ $response['email'] }}" id="rzp_email">
<input type="hidden" value="{{ $response['contactNumber'] }}" id="rzp_mobile">
<input type="hidden" value="{{ $response['method'] }}" id="rzp_method">
<input type="hidden" value="{{ $response['types'] }}" id="rzp_types">
<button type="submit" id="pay_now" style="display:none;"></button>
@endif
<!-- Online Payment ends -->

</section>
</div>
<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<!--Datable plugins -->
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('template_assets/dist/js/pages/datatable/datatable-basic.init.js') }}"></script>
<!-- Datatable plugin ends -->
<script src="template_assets\other\js\bootstrap-toggle.min.js"></script>
<script src="template_assets\other\js\sweetalert.min.js"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<!-- <script src="{{ asset('dist\payment\js\serviceTypeFormValidation.js') }}"></script> -->
@if(isset($response))
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src="{{ asset('dist\payment\razorpay.js') }}"></script> 
@endif
@endsection
