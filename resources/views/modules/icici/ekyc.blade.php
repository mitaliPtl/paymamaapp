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
        <div class="row">
            <div class="col-12">
                <div class="card card-body">
                    <h4 class="card-title">AEPS Biometric EKYC</h4>
                    <p><strong>Please note :- Fingerprint Capture is required to complete onboarding process</strong></p>
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
                                            <label for="device_type">Select Biometric Device</label>
                                            <select class="form-control custom-select" id="device_type">
            									<option value="mantra" selected>Mantra</option>
            									<option value="morpho">Morpho</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="name">Aadhar Number</label>
                                            <input type="text" class="form-control" name="aadharNumber" value="{{ Session::has('aeps_aadhar') ? Session::get('aeps_aadhar') : "" }}" readonly required="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button type="button" onclick="discoverAvdm();" class="btn btn-success mr-2">Discover Device</button>
                                <button type="button" onclick="CaptureAvdm();" class="btn btn-success mr-2">Capture Fingerprint</button>
                                <button type="submit" id="submit" style="display:none;" class="btn btn-success mr-2">Submit</button>
                            </div>
                        </div>
                    </form>
    
                </div>
            </div>
        </div>
    </section>

</div>
<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
@endsection
