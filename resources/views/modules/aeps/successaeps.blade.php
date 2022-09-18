@extends('layouts.full_new')
@section('page_content')

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
        <div class="row">
            <div class="col-12">
                <div class="card card-body">
                     <style>
                        legend{
                           
                            color:#be1d2c !important;
                            font-weight:normal;
                            font-size:19px;
                        }
                    </style>
                     <h4 class="" style="text-align:center;background:#be1d2c;color:white;padding:15px;">VERIFICATION SUCCESFULL</h4>
                        <br>
                        <fieldset style="border:1px solid #e1e1e1;padding:20px;">
                                <legend style="color:blue;font-weight:bold;width:23% !important;;">VERIFICATION SUCCESFULL</legend>
                                      <center><img src="{{asset('template_assets/download.png')}}" style="height:50px;width:50px;"/>
                                      <h2 style="margin-top:15px;">You have Succesfully Completed Biometric Verification</h2>
                                      <h2>Start using AEPS, AadharPay & ICICI Cash Deposit Services.</h2></center>
                                </legend>
                        </fieldset>
                         <style>
                            legend{
                               
                                color:#be1d2c !important;
                                font-weight:normal;
                                font-size:15px;
                            }
                        </style>
                    
                </div>
            </div>
        </div>
    </section>

</div>
<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
@endsection
