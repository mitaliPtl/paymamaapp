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
<script type="text/javascript">
    @if(Session::has('msuccess'))
    window.onload = function() {
        $('#successModal').modal('show');
    };
    @endif
    @if(Session::has('merror'))
    window.onload = function() {
        $('#errorModal').modal('show');
    };
    @endif
</script>
<script src="{{ asset('dist/other/js/aeps.js') }}"></script>
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
        <div class="row">
            <div class="col-12">
                <div class="card card-body">
                    <h4 class="card-title">Mobile Verification</h4>
                        <form method="post" action="{{ route('aeps_transaction') }}">
                        @csrf
                        <div class="row" style="display:none">	
    						<div class="col-md-12 col-lg-12 col-xl-12">
							  <div class="form-group">
									<label class="main-content-label tx-11 tx-medium tx-gray-600 control-label">Captured Data</label>
									<textarea type="text" class="form-control" id="txtPidData" rows="5" name="txtPidData" readonly required></textarea>
								    <textarea type="text" class="form-control" id="txtPidOptions" name="PidOptions" readonly style="width: 100%; height: 100px;" required></textarea>
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
        </div>
    </section>

</div>
<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script>
    $('#service_type').on('change', function () {
         var aepsService = $("#service_type").val();
         console.log(aepsService);
         if(aepsService == 'cash_withdrawal' || aepsService == 'aadhar_payment') {
             $('#aepsAmountDiv').css('display','block');
             $('#aepsAmount').prop('required',true);
         } else {
             $('#aepsAmountDiv').css('display','none');
             $('#aepsAmount').prop('required',false);
         }
    });
</script>
@endsection
