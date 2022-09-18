@extends('layouts.full')

@section('page_content')

<section>
<!-- This page plugin CSS -->
<link href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css"
        href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
<!-- <link rel="stylesheet" type="text/css" href="{{ asset('dist\setting\css\package_setting_list.css') }}"> -->
<link rel="stylesheet" type="text/css" href="{{ asset('template_assets\other\css\bootstrap-toggle.min.css') }}">

<!-- Package Setting table starts -->
<div class="row">
    <div class="col-12">
        <div class="material-card card">
            <div class="card-body">
                <h4 class="card-title">General Setting</h4>
                <br>
                <div class="row card-title">
                    <!-- <div class="col-12 text-right">
                        <button type="button" class="btn btn-primary btn-md add-service-btn" data-toggle="modal" data-target="#packageSettingAddModal"><i class="fa fa-plus"></i> Add Package Setting</button>
                    </div> -->
                </div>
                <br>
                <div>

               
                           
                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs customtab" role="tablist">
                                    <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#social-media" role="tab"><span class="hidden-sm-up"><i class="ti-social-media"></i></span> <span class="hidden-xs-down">Social Media</span></a> </li>
                                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#verification-charges" role="tab"><span class="hidden-sm-up"><i class="ti-verification-charges"></i></span> <span class="hidden-xs-down">Verification charges</span></a> </li>
                                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#money-trans-limit" role="tab"><span class="hidden-sm-up"><i class="ti-money-trans-limit"></i></span> <span class="hidden-xs-down">Money Transfer Limit</span></a> </li>
                                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#company" role="tab"><span class="hidden-sm-up"><i class="ti-company"></i></span> <span class="hidden-xs-down">Company</span></a> </li>
                                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#other-setting" role="tab"><span class="hidden-sm-up"><i class="ti-other-setting"></i></span> <span class="hidden-xs-down">Other</span></a> </li>
                                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#qr-code" role="tab"><span class="hidden-sm-up"><i class="ti-qr-code"></i></span> <span class="hidden-xs-down">QR Code</span></a> </li>
                                </ul>
                                <!-- Tab panes -->
                                <div class="tab-content p-4">
                                    <div class="tab-pane active" id="social-media" role="tabpanel">
                                        <div class="">
                                            <form action="{{ route('update_social_media') }}" method="post">
                                                @csrf
                                                <h3>Social Media </h3>
                                                <br>
                                                <br>
                                                @foreach($social_data as $social_key => $social_value)
                                                <div class="form-group">
                                                    <div class="row">
                                                        <label class="col-lg-2"><h4>{{ $social_value->name }}</h4></label>
                                                        <div class="col-lg-8">
                                                            <div class="row">
                                                                
                                                                <div class="col-md-11">
                                                                    <input type="text" class="form-control" value="{{ $social_value->value }}" name="{{ $social_value->alias }}" placeholder="{{ $social_value->name }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach

                                                <!-- <div class="form-group">
                                                    <div class="row">
                                                        <label class="col-lg-2"><h4>Facebook </h4></label>
                                                        <div class="col-lg-8">
                                                            <div class="row">
                                                                
                                                                <div class="col-md-11">
                                                                    <input type="text" class="form-control" name="facebook_val" placeholder="Facebook">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="row">
                                                        <label class="col-lg-2"><h4>Instagram </h4></label>
                                                        <div class="col-lg-8">
                                                            <div class="row">
                                                                
                                                                <div class="col-md-11">
                                                                    <input type="text" class="form-control" name="instagram_val" placeholder="Instagram">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="row">
                                                        <label class="col-lg-2"><h4>Twitter </h4></label>
                                                        <div class="col-lg-8">
                                                            <div class="row">
                                                                
                                                                <div class="col-md-11">
                                                                    <input type="text" class="form-control" name="twitter_val" placeholder="Twitter">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="row">
                                                        <label class="col-lg-2"><h4>YouTube </h4></label>
                                                        <div class="col-lg-8">
                                                            <div class="row">
                                                                
                                                                <div class="col-md-11">
                                                                    <input type="text" class="form-control" name="youtube_val" placeholder="YouTube">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> -->
                                                
                                                <div class="text-center">
                                                        <button type="submit" class="btn btn-info">Update</button>
                                                        <!-- <button type="reset" class="btn btn-dark">Reset</button> -->
                                                </div> 
                                            </form>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="verification-charges" role="tabpanel">
                                            <form action="{{ route('update_verify_charges') }}" method="post">
                                                @csrf
                                                <h3>Verification Charges  </h3>
                                                <br>
                                                <br>
                                                @foreach($verifycharges_data as $vc_key => $vc_value)
                                                <div class="form-group">
                                                    <div class="row">
                                                        <label class="col-lg-2"><h4>{{ $vc_value->name }}</h4></label>
                                                        <div class="col-lg-8">
                                                            <div class="row">
                                                                
                                                                <div class="col-md-11">
                                                                    <input type="text" class="form-control" value="{{ $vc_value->value }}" name="{{ $vc_value->id }}" placeholder="{{ $vc_value->name }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                                <div class="text-center">
                                                        <button type="submit" class="btn btn-info">Update</button>
                                                        <!-- <button type="reset" class="btn btn-dark">Reset</button> -->
                                                </div> 
                                            </form>
                                    </div>
                                    <div class="tab-pane" id="money-trans-limit" role="tabpanel">
                                            <form action="{{ route('update_paylimit') }}" method="post">
                                                @csrf
                                                <h3>Payment Limit </h3>
                                                <br>
                                                <br>
                                                @foreach($paylimit_data as $pl_key => $pl_value)
                                                <div class="form-group">
                                                    <div class="row">
                                                        <label class="col-lg-2"><h4>{{ $pl_value->name }}</h4></label>
                                                        <div class="col-lg-8">
                                                            <div class="row">
                                                                
                                                                <div class="col-md-11">
                                                                    @if($pl_value->alias != 'upimode')
                                                                    <input type="text" class="form-control" value="{{ $pl_value->value }}" name="{{ $pl_value->id }}" placeholder="{{ $pl_value->name }}">
                                                                    @else
                                                                    <select class="form-control" name="{{ $pl_value->id }}" placeholder="{{ $pl_value->name }}">
                                                                        <option value="12" {{ ($pl_value->value == "12" ? "selected":"") }}>PAYTM</option>
                                                                        <option value="13" {{ ($pl_value->value == "13" ? "selected":"") }}>HYPTO</option>
                                                                        <option value="14" {{ ($pl_value->value == "14" ? "selected":"") }}>RAZORPAY</option>
                                                                    </select>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                                <div class="text-center">
                                                        <button type="submit" class="btn btn-info">Update</button>
                                                        <!-- <button type="reset" class="btn btn-dark">Reset</button> -->
                                                </div> 
                                            </form>
                                    </div>

                                    <div class="tab-pane" id="company" role="tabpanel">
                                            <form action="{{ route('update_company') }}" method="post">
                                                @csrf
                                                <h3>Company Details </h3>
                                                <br>
                                                <br>
                                                @foreach($company_data as $c_key => $c_value)
                                                <div class="form-group">
                                                    <div class="row">
                                                        <label class="col-lg-2"><h4>{{ $c_value->name }}</h4></label>
                                                        <div class="col-lg-8">
                                                            <div class="row">
                                                                
                                                                <div class="col-md-11">
                                                                    <input type="text" class="form-control" value="{{ $c_value->value }}" name="{{ $c_value->id }}" placeholder="{{ $c_value->name }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                                <div class="text-center">
                                                        <button type="submit" class="btn btn-info">Update</button>
                                                        <!-- <button type="reset" class="btn btn-dark">Reset</button> -->
                                                </div> 
                                            </form>
                                    </div>
                                    <div class="tab-pane" id="other-setting" role="tabpanel">
                                            <form action="{{ route('update_other') }}" method="post">
                                                @csrf
                                                <h3>Other Details </h3>
                                                <br>
                                                <br>
                                                @foreach($other_data as $o_key => $o_value)
                                                <div class="form-group">
                                                    <div class="row">
                                                        <label class="col-lg-2"><h4>{{ $o_value->name }}</h4></label>
                                                        <div class="col-lg-8">
                                                            <div class="row">
                                                                
                                                                <div class="col-md-11">
                                                                    <input type="text" class="form-control" value="{{ $o_value->value }}" name="{{ $o_value->id }}" placeholder="{{ $o_value->name }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                                <div class="form-group">
                                                    <div class="row">
                                                        <label class="col-lg-2"><h4>PG Mode</h4></label>
                                                        <div class="col-lg-8">
                                                            <div class="row">
                                                                <div class="col-md-11">
                                                                    <select class="form-control" required name="pg_mode">
                                                                        <option value="CASHFREE" selected>CASHFREE</option>
                                                                        <option value="PAYTM">PAYTM</option>
                                                                        <option value="RAZORPAY">RAZORPAY</option>
                                                                        <option value="PAYU">PAYU</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="text-center">
                                                        <button type="submit" class="btn btn-info">Update</button>
                                                        <!-- <button type="reset" class="btn btn-dark">Reset</button> -->
                                                </div> 
                                            </form>
                                    </div>
                                    <div class="tab-pane" id="qr-code" role="tabpanel">
                                            <form action="{{ route('update_qr_code') }}" method="post" enctype="multipart/form-data">
                                                @csrf
                                                <h3>Other Details </h3>
                                                <br>
                                                <br>
                                                
                                                <div class="form-group">
                                                    <div class="row">
                                                        <label class="col-lg-2"><h4>QR Code </h4></label>
                                                        <div class="col-lg-8">
                                                            <div class="row">
                                                                
                                                                <div class="col-md-6">
                                                                    <input type="text" class="form-control" value="@if(isset($qrCodeFile->name)) {{ $qrCodeFile->name }} @endif" name="qr_code_name" placeholder="">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="custom-file">
                                                                        <input type="file" name="file" class="custom-file-input" id="chooseFile">
                                                                        <label class="custom-file-label" for="chooseFile">Select file</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6" style="padding: 20px;">
                                                                    @if(isset($qrCodeFile->value))
                                                                    <img src="{{ Config::get('constants.WEBSITE_BASE_URL') }}{{ $qrCodeFile->value }}" alt="" style="width: 50%;">
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="text-center">
                                                        <button type="submit" class="btn btn-info">Update</button>
                                                        <!-- <button type="reset" class="btn btn-dark">Reset</button> -->
                                                </div> 
                                            </form>
                                    </div>
                                </div>
                              
                       
                   
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Package Setting table ends -->



</section>

<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<!--Datable plugins -->
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('template_assets/dist/js/pages/datatable/datatable-basic.init.js') }}"></script>
<!-- Datatable plugin ends -->
<script src="template_assets\other\js\bootstrap-toggle.min.js"></script>
<script src="template_assets\other\js\sweetalert.min.js"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script src="{{ asset('dist\setting\js\packageSettingFormvalidation.js') }}"></script>
<script src="{{ asset('dist\setting\js\package_setting_list.js') }}"></script>
@endsection
