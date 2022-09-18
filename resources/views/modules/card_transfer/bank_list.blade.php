@extends('layouts.full_new')
<link rel="stylesheet" type="text/css" href="{{ asset('template_new/assets/libs/select2/dist/css/select2.min.css') }}">
@section('page_content')

<div class="page-breadcrumb border-bottom">
    <div class="row">
        <div class="col-lg-3 col-md-4 col-xs-12 justify-content-start d-flex align-items-center">
            <h5 class="font-medium text-uppercase mb-0" id="div_heading">Bank List</h5>
        </div>
    </div>
</div>
<div class="page-content container-fluid"  id="sender_details">
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
    <div class="card material-card">
        <div class="card-body">
            <div class="table-responsive m-t-40">
                <table id="config-table" class="table display table-bordered table-striped no-wrap">
                    <tr>
                        <td><b>NAME : {{ ( isset($ccsender['name']) ) ? $ccsender['name'] : '' }}</b></td>
                        <td><b>PAN : {{ isset($ccsender['pan']) ? $ccsender['pan'] : '' }}</b></td>
                    </tr>
                    <tr>
                        <td><b>MOBILE No : {{ isset($ccsender['mobile']) ? $ccsender['mobile'] : '' }}</b></td>
                        <td><b>LIMIT : {{ isset($ccsender['trans_limit']) ? $ccsender['trans_limit'] : '' }}</b></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="card material-card">
        <div class="card-body">
            <h5 class="card-title mt-2">Receiver Accounts</h5>
            <button type="button" style="float:right;margin-bottom: 5px;" class="btn-primary btn add-beneficiary-btn success-grad font-22" onclick="document.getElementById('addBenForm').submit();">Add Beneficiary</button>
            <form id="addBenForm" method="post" action="{{ route('new_cc_beneficiary') }}">
                @csrf
                 <input type="hidden" name="mobile_no" value="{{ isset($ccsender['mobile']) ? $ccsender['mobile'] : '' }}" id="mobile_no">
            </form>
            <form id="moneytransferMoneyForm" method="post" action="{{ route('transfer_money') }}">
                @csrf
                 <input type="hidden" name="mobile_no" value="{{ isset($ccsender['mobile']) ? $ccsender['mobile'] : '' }}" id="mobile_no">
                <input type="hidden" name="benificiary" value="" id="benificiary_id">
            </form>
            <div class="table-responsive m-t-40">
                <table id="config-table" class="table display table-bordered table-striped no-wrap ">
                    <thead>
                        <tr>
                            <td><b>Sr</b></td>
                            <td><b>NAME</b></td>
                            <td><b>BANK NAME</b></td>
                            <td><b>IFSC CODE</b></td>
                            <td><b>ACCOUNT NUMBER</b></td>
                            <td><b>ACTION</b></td>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($ccbanks) > 0)
                            @foreach($ccbanks as $id => $key)
                            <tr>
                                <td>{{ $id+1 }} </td>
                                <td>{{ $key['bank_acc_name'] }}</td>
                                <td>{{ $key['bank_name'] }}</td>
                                <td>{{ $key['bank_acc_ifsc'] }}</td>
                                <td>{{ $key['bank_acc_no'] }}</td>
                                <td><button type="button" class="btn trasfer-btn btn-success" onclick="startTransaction( {{ $key['id'] }} )" ><i   class="ti-share"></i></button></td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    function startTransaction(recip_id){
  
       $('#benificiary_id').val(recip_id);
       $('#moneytransferMoneyForm').submit();
       
    }
</script>
<script src="{{ asset('template_new\assets\libs\select2\dist\js\select2.full.min.js') }}"></script>
<script src="{{ asset('template_new\assets\libs\select2\dist\js\select2.min.js') }}"></script>
<script src="{{ asset('template_new\dist\js\pages\forms\select2\select2.init.js') }}"></script>
@endsection


