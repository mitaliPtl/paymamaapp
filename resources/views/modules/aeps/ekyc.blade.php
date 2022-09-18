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
<script src="{{ asset('dist/other/js/final-kyc.js') }}"></script>
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
    <div class="alert alert-dismissible fade show alert-danger" id="infoDiv" role="alert">
        <strong><span id="info" style="color:red;"></span></strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <section>
         <style>
        legend{
           
            color:#be1d2c !important;
            font-weight:normal;
            font-size:19px;
        }
    </style>
        <div class="row">
            <div class="col-12">
                <div class="card card-body">
                    <h4 class="" style="text-align:center;background:#be1d2c;color:white;padding:15px;">AEPS BIOMETRIC EKYC</h4>
                    
                    <p><strong>Please note :- Fingerprint Capture is required to complete onboarding process</strong></p>
                     <fieldset style="border:1px solid #e1e1e1;padding:20px;">
                            <legend style="color:blue;font-weight:bold;width:20% !important;">KYC VERIFICATION</legend>
                                <form method="post" action="{{ route('complete_kyc') }}">
                                @csrf
                                <div class="row" style="display:none">	
            						<div class="col-md-12 col-lg-12 col-xl-12">
        							  <div class="form-group">
        									<label class="main-content-label tx-11 tx-medium tx-gray-600 control-label">Captured Data</label>
        									<input type="text" class="form-control" id="txtPidData" name="txtPidData" readonly required="required">
        								    <input type="text" class="form-control" id="txtPidOptions" name="PidOptions" readonly style="width: 100%; height: 100px;" required="required">
        								    <input type="text" class="form-control" id="txtDeviceInfo" style="width: 100%; height: 160px;">
        								    <input type="text" id="longitude" class="form-control" name="longitude">
        								    <input type="text" id="latitude" class="form-control" name="latitude">
        								</div>
            						</div>
            					</div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="device_type" style="font-size:16px;">Select Biometric Device</label>
                                                    <select class="form-control custom-select" id="device_type" style="height:49px;font-size:20px;">
                    									<option value="mantra" selected>Mantra</option>
                    									<option value="morpho">Morpho</option>
                    									<option value="startek">Startek</option>
                    									<option value="secugen">Secugen</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="name" style="font-size:16px;">Aadhar Number</label>
                                                    <input type="text" class="form-control" name="aadharNumber" value="{{ Session::has('aeps_aadhar') ? Session::get('aeps_aadhar') : "" }}" readonly required="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <button type="button" onclick="discoverAvdm();" class="btn btn-success mr-2" style="font-size:18px;height:55px;width: 250px;background-color: green;color: white;">Discover Device</button>
                                        <button type="button" onclick="CaptureAvdm();" class="btn btn-success mr-2" style="font-size:18px;width: 250px; height:55px;   background-image: linear-gradient(to right, #251c63 , #dc182d);color: white;">Capture Fingerprint</button>
                                        <button type="submit" id="submit" style="display:none;font-size:18px !important;width: 250px !important;background-color: green !important;color: white !important;height:55px; !important" class="btn btn-success mr-2" style="">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </legend>
                    </fieldset>
    
                </div>
            </div>
        </div>
    </section>

</div>
<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
@endsection
