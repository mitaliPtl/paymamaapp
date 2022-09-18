{{-- @extends('layouts.full') --}}
@extends('layouts.full_new')
@section('page_content')

@if( Auth::user()->roleId != Config::get('constants.DISTRIBUTOR'))
<section>
@endif
<!-- This page plugin CSS -->
<link rel="stylesheet" type="text/css" href="{{ asset('dist\bank\css\trans_rev_bal.css') }}">
@if( Auth::user()->roleId == Config::get('constants.DISTRIBUTOR'))
<div class="page-content container-fluid">
@endif

<style>
    th {
  text-transform: uppercase;
}
</style>
<!-- Transfer Revert table starts -->
<div class="row">
    <div class="col-12">
        <div class="material-card card">
            <div class="card-body">
                <h4 class="card-title">Transfer Revert Balance</h4>
                <br>
                    <form action="{{ route('find_user') }}" method="post">
                    @csrf
                        <div class="row">
                            <div class="col-3">
                                <div class="form-group btn-group">
                                    <input type="text" class="form-control" value="{{$request->mobile ? $request->mobile : ''}}"  required name="mobile" id="mobile" placeholder="Search Username/Mobile">
                                    <button type="submit" class="btn btn-primary btn-md"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                            <div class="col-6">
                                <!-- @if ($message = Session::get('success'))
                                    <div class="alert alert-success alert-block" style="max-height:40px">
                                        <button type="button" class="close" data-dismiss="alert">×</button>	
                                            <strong>{{ $message }}</strong>
                                    </div>
                                @endif -->


                                @if ($message = Session::get('error'))
                                    <div class="alert alert-danger alert-block" style="max-height:40px">
                                        <button type="button" class="close" data-dismiss="alert">×</button>	
                                            <strong>{{ $message }}</strong>
                                    </div>
                                @endif
                            </div>
                            <div class="col-3 text-right">
                            <a type="button" href="{{ route('transfer_revert_balance') }}" class="btn btn-sm" ><i class="mdi mdi-refresh fa-2x"></i></a>
                            </div>
                        </div>
                    </form>

                    <table id="trans_rev_bal-table" class="table table-sm table-striped table-sm border">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Member Name</th>
                                <th>Role</th>
                                <th>Parent Name</th>
                                <th>Mobile No.</th>
                                <th>Balance</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($users)== 0)
                            <tr>
                                <td class="text-center" colspan="8">Data not found!</td>
                            </tr>
                            @endif
                            @foreach($users as $index => $data)
                                <tr>
                                    <td>{{ $index+1 }}</td>
                                    <td>{{ $data->user_name }}</td>
                                    <td>{{ $data->role_name }}</td>
                                    <td>{{ $data->parent_user_name }}</td>
                                    <td>{{ $data->mobile }}</td>
                                    <td>{{ $data->wallet_balance }}</td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-danger revert-btn" title="Revert" value="{{ $data }}">
                                                <i class="fa fa-reply"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-success transfer-btn" title="Transfer" value="{{ $data }}">
                                                <i class="fa fa-share"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Sr No</th>
                                <th>Member Name</th>
                                <th>Role</th>
                                <th>Parent Name</th>
                                <th>Mobile No.</th>
                                <th>Balance</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Transfer Revert table ends -->

<!-- Transfer modal starts -->
<div class="modal" id="transferModal" tabindex="-1" role="dialog" aria-labelledby="transferModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel1"><span class="modal-action-name"></span> Transfer To</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form method="post" action="{{ route('transfer_balance') }}" id="transferBalReqForm">
            @csrf
                <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <table class="table table-sm">
                                    <tbody>
                                        <tr>
                                            <td class="label font-sm"><i class="mdi mdi-account-outline"></i> <span id="tr_user_name"></span></td>
                                            <td class="label font-sm"><i class="mdi mdi-cellphone"></i> <span id="tr_user_mobile"></span></td>
                                        </tr>
                                        <tr>
                                            <td class="label font-sm"><i class="mdi mdi-account"></i> <span id="tr_user_role"></span></td>
                                            <td class="label font-sm"><i class="mdi mdi-wallet"></i><i class="mdi mdi-currency-inr"></i> <span id="tr_user_balance"></span>/-</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <input type="hidden" name="user_id" id="trans_user_id">
                        <input type="hidden" name="user_mobile" id="trans_user_mobile">
                        <input type="hidden" name="role_id" id="trans_user_role_id">
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    @if(Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN'))
                                    <label for="bank">Select Bank</label>
                                    <select class="form-control" name="bank"> 
                                        <option disabled selected>Select</option>
                                        @foreach($bankAccounts as $i => $account)
                                            @if($account['type'] == "label")
                                                <option disabled class="label text-info">{{ $account['name'] }}</option>
                                            @endif
                                            @if($account['type'] == "mode")
                                                <option value="{{ $account['value'] }}" class="font-sm">&nbsp;&nbsp;{{ $account['name'] }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @endif

                                    @if(Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.DISTRIBUTOR'))
                                    <label for="tran_payment_type">Payment Type</label>
                                    <select class="form-control" id="tran_payment_type" name="payment_type"> 
                                        <option disabled selected>Select</option>
                                        @foreach($dtPaymentType as $key => $type)
                                            <option value="{{ $key }}"> {{ $type }} </option>
                                        @endforeach
                                    </select>
                                    @endif
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="amount">Amount</label>
                                    <input type="number" class="form-control" name="amount">
                                </div>
                            </div>

                            @if(Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN'))
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="reference_id">Ref. Id</label>
                                    <input type="text" class="form-control" name="reference_id">
                                </div>
                            </div>
                            @endif
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="message">MPIN</label>
                                    <input type="text" class="form-control"  name="mpin">
                                </div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-info submit-btn"><i class="fa fa-share"></i> Transfer</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Transfer modal ends -->

<!-- Revert modal starts -->
<div class="modal" id="revertModal" tabindex="-1" role="dialog" aria-labelledby="revertModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel1"><span class="modal-action-name"></span> Revert From</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form method="post" action="{{ route('revert_balance') }}" id="revertBalReqForm">
            @csrf
                <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <table class="table table-sm">
                                    <tbody>
                                        <tr>
                                            <td class="label font-sm"><i class="mdi mdi-account-outline"></i> <span id="rv_user_name"></span></td>
                                            <td class="label font-sm"><i class="mdi mdi-cellphone"></i> <span id="rv_user_mobile"></span></td>
                                        </tr>
                                        <tr>
                                            <td class="label font-sm"><i class="mdi mdi-account"></i> <span id="rv_user_role"></span></td>
                                            <td class="label font-sm"><i class="mdi mdi-wallet"></i><i class="mdi mdi-currency-inr"></i> <span id="rv_user_balance"></span>/-</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <input type="hidden" name="user_id" id="rev_user_id">
                        <input type="hidden" name="user_mobile" id="rev_user_mobile">
                        <input type="hidden" name="role_id" id="rev_user_role_id">
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    @if(Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN'))
                                    <label for="bank">Select Bank</label>
                                    <select class="form-control" id="bank" name="bank"> 
                                        <option disabled selected>Select</option>
                                        @foreach($bankAccounts as $i => $account)
                                            @if($account['type'] == "label")
                                                <option disabled class="label text-info">{{ $account['name'] }}</option>
                                            @endif
                                            @if($account['type'] == "mode")
                                                <option value="{{ $account['value'] }}" class="font-sm">&nbsp;&nbsp;{{ $account['name'] }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @endif

                                    @if(Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.DISTRIBUTOR'))
                                    <label for="rev_payment_type">Payment Type</label>
                                    <select class="form-control" id="rev_payment_type" name="payment_type"> 
                                        <option disabled selected>Select</option>
                                        @foreach($dtPaymentType as $key => $type)
                                            <option value="{{ $key }}"> {{ $type }} </option>
                                        @endforeach
                                    </select>
                                    @endif
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="revert_amount">Revert Amount</label>
                                    <input type="number" class="form-control" name="revert_amount" id="revert_amount">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="amount_sent">Amount Sent</label>
                                    <input type="number" class="form-control" name="amount_sent">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            @if(Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN'))
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="reference_id">Ref. Id</label>
                                        <input type="text" class="form-control" id="reference_id" name="reference_id">
                                    </div>
                                </div>
                            @endif

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="mpin">Mpin</label>
                                    <input type="text" class="form-control" name="mpin">
                                </div>
                            </div>

                            @if(Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.DISTRIBUTOR'))
                            <div class="col-6 hide-this retailer-otp-div">
                                <div class="form-group">
                                    <label for="mpin">Enter OTP From Retailer</label>
                                    <input type="text" class="form-control" name="otp">
                                </div>
                            </div>
                            @endif
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    @if(Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN'))
                        <button type="submit" class="btn btn-info"><i class="fa fa-reply"></i> Revert</button>
                    @else
                        <button type="button" class="btn btn-info fake-revert"><i class="fa fa-reply"></i> Revert</button>
                    @endif
                    <button type="submit" class="btn btn-info submit-btn hide-this"><i class="fa fa-reply"></i> Proceed Revert</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Revert modal ends -->

@if( Auth::user()->roleId == Config::get('constants.DISTRIBUTOR'))
<div class="page-content container-fluid">
@endif

@if( Auth::user()->roleId != Config::get('constants.DISTRIBUTOR'))
</section>
@endif

<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script src="{{ asset('dist\bank\js\transRevBalValidation.js') }}"></script>
<script src="{{ asset('dist\bank\js\transRevBal.js') }}"></script>
@endsection
