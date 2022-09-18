
{{-- @extends('layouts.full') --}}
@extends('layouts.full_new')
@section('page_content')

<!-- This page plugin CSS -->
<link href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css"
        href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
<!-- <link rel="stylesheet" type="text/css" href="{{ asset('template_assets\other\css\flatpickr.min.css') }}"> -->
<?php
        // print_r($all_offers_notice);
        // exit();
?>
<!-- <section> -->
<div class="page-content container-fluid">
<div class="row">
    <div class="col-12">
        <div class="material-card card">
            <div class="card-body">
                <h4 class="card-title">{{ $offerType }}S </h4>

            </div>
        </div>
    </div>
</div>
        <div class="page-content container-fluid">
            <div class="row">
                @foreach($all_offers_notice as $offers_key => $offers_value)
                    

                    <div class="col-md-6 col-lg-4">
                        <div class="card">
                            @if(!empty($offers_value['file_path']) && $offers_value['file_path'])
                                <img class="card-img-top" src="{{ $offers_value['file_path'] }}" alt="Card image cap">
                            @endif
                            <div class="card-body">
                                <div class="d-flex no-block align-items-center mb-3">
                                    <span class="text-muted"><i class="ti-calendar"></i> {{ $offers_value['created_on'] }}</span>
                                    <!-- <div class="ml-3">
                                        <a href="javascript:void(0)" class="link"><i class="ti-heart"></i> 30</a>
                                    </div> -->
                                </div>
                                <h3 class="mt-3">{{ $offers_value['notice_title'] }}</h3>
                                <p class="mt-3 font-light">{{ $offers_value['notice_description'] }}.</p>
                                <!-- <button class="btn btn-success btn-rounded waves-effect waves-light mt-2 text-white">Read more</button> -->
                            </div>
                        </div>
                    </div>

                @endforeach
            </div>
        </div>
    
</div>
<!-- </section> -->

<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<!--Datable plugins -->
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('template_assets/dist/js/pages/datatable/datatable-basic.init.js') }}"></script>
<!-- Datatable plugin ends -->
<script src="{{ asset('template_assets\other\js\select2.min.js') }}"></script>

<!-- <script src="{{ asset('template_assets\other\js\flatpickr') }}"></script> -->
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<!-- <script src="{{ asset('dist\reports\js\rechargeReport.js') }}"></script> -->
<!-- <script src="{{ asset('dist\complaint\js\complaint.js') }}"></script> -->
<script src="{{ asset('dist\offersnotice\js\offersnotice.js') }}"></script>

@endsection