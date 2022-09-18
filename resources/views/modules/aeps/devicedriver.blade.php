@extends('layouts.full_new')
@section('page_content')


<link rel="stylesheet" type="text/css" href="{{ asset('template_new/assets/libs/select2/dist/css/select2.min.css') }}">
<style>
    .nav-link {
        font-size: 21px;
        padding: 10px;
    }

    .progress {
        margin: 10px;
        width: 700px;
    }

    .select2-selection__arrow {
        margin-top: 8px;
    }

    .select2-selection__rendered {
        margin-top: 4px;
    }

    .select2-container--default .select2-selection--single {
        border: 1px solid #e1e1e1;
    }

    .nav-tabs .nav-menus.active {
        color: #ffffff;
        background-color: #008000;
        border: none !important;
    }
</style>


<div class="page-content container-fluid">
    <section>
        <div class="row" style="padding:20px;margin-top:-15px;">
            <h1>Aeps Device Drivers</h1>
        </div>
        <div class="row">
            <div class="col-5">
                <div class="card" style="width: 25rem;">
                <div class="d-flex justify-content-center" >
                    <img class="card-img-top mt-3" src="{{ asset('template_assets/mantra.png') }}" style="height: 15rem; width:15rem;" alt="Card image cap">
                    </div>
                    <div class="card-body">
                    <div class="d-flex justify-content-center" >
                        <h2 >Mantra</h2>
                        </div>
                        <div class="d-flex justify-content-center" >
                        <a href="{{ asset('template_assets/drivers/MantraRDService_1.0.4 .zip') }}" download class="btn" style="background-color:green;color:white;border-radius: 15px;"><h2>Download</h2></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-5">
                <div class="card" style="width: 25rem;">
                <div class="d-flex justify-content-center" >
                    <img class="card-img-top mt-3" src="{{ asset('template_assets/morpho.png') }}" style="height: 15rem; width:15rem;" alt="Card image cap">
                    </div>
                    <div class="card-body">
                    <div class="d-flex justify-content-center" >
                        <h2 >Morpho</h2>
                        </div>
                        <div class="d-flex justify-content-center" >
                        <a href="{{ asset('template_assets/drivers/Morpho1300E3.rar') }}" download class="btn" style="background-color:green;color:white;border-radius: 15px;"><h2>Download</h2></a>
                        </div>
                        
                    </div>
                </div>
            </div>

            <div class="col-1"></div>

            <div class="col-5">
                <div class="card" style="width: 25rem;">
                <div class="d-flex justify-content-center" >
                    <img class="card-img-top mt-3" src="{{ asset('template_assets/startek.jpg') }}" style="height: 15rem; width:15rem;" alt="Card image cap">
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-center" >
                        <h2 >Startek</h2>
                        </div>
                        <div class="d-flex justify-content-center" >
                        <a href="{{ asset('template_assets/drivers/startek.zip') }}" download class="btn" style="background-color:green;color:white;border-radius: 15px;"> <h2>Download</h2></a>
                        </div>
                      
                    </div>
                </div>
            </div>

            <div class="col-5">
                <div class="card" style="width: 25rem;">
                <div class="d-flex justify-content-center" >
                    <img class="card-img-top mt-3" src="{{ asset('template_assets/secugen.png') }}" style="height: 15rem; width:15rem;" alt="Card image cap">
                    </div>
                    <div class="card-body">
                    <div class="d-flex justify-content-center" >
                        <h2 >Secugen</h2>
                        </div>
                        <div class="d-flex justify-content-center" >
                        <a href="{{ asset('template_assets/drivers/SECUGEN.zip') }}" download class="btn" style="background-color:green;color:white;border-radius: 15px;"><h2>Download</h2></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-1"></div>
        </div>

    </section>
</div>
<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>

<script src="{{ asset('template_new\assets\libs\select2\dist\js\select2.full.min.js') }}"></script>
<script src="{{ asset('template_new\assets\libs\select2\dist\js\select2.min.js') }}"></script>
<script src="{{ asset('template_new\dist\js\pages\forms\select2\select2.init.js') }}"></script>


@endsection