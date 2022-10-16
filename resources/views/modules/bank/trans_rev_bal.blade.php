{{-- @extends('layouts.full') --}}
@extends('layouts.full_new')
@section('page_content')

@if( Auth::user()->roleId != Config::get('constants.DISTRIBUTOR') || Auth::user()->roleId == Config::get('constants.MASTER_DISTRIBUTOR') )
<section>
@endif
<!-- This page plugin CSS -->
<link rel="stylesheet" type="text/css" href="{{ asset('dist\bank\css\trans_rev_bal.css') }}">
@if( Auth::user()->roleId == Config::get('constants.DISTRIBUTOR') || Auth::user()->roleId == Config::get('constants.MASTER_DISTRIBUTOR') )
<div class="page-content container-fluid" style="width: 98%;margin-left:20px;height:860px !important;">
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
                <h4 class="card-title" style="font-weight:bold;color:#BE1D2C;">WALLET TRANSFER</h4>
                <br>
                    <form action="{{ route('find_user') }}" method="post">
                    @csrf
                        <div class="row" style="margin-left:1.1%;">
                            <div class="col-3">
                                <div class="form-group btn-group">
                                    <input type="text" style="width:300px;" class="form-control" value="{{$request->mobile ? $request->mobile : ''}}"  required name="mobile" id="mobile" placeholder="Search Username/Mobile">
                                    <button type="submit" class="btn btn-primary btn-md success-grad "><i class="fa fa-search" style="font-size:22px"></i></button>
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
                    
                    @foreach($users as $index => $data)
                    <form method="post" action="{{ route('transfer_balance') }}" id="transferBalReqForm">
                @csrf
                <div class="modal-body">
                        <div class="row">
                            <div class="col-5">
                                
                                <style>
                                  td{
                                      border:1px solid #7f7f7f3b !important;
                                  }
                                </style>
                            </div>
                        </div>
                        <input type="hidden" name="user_id" id="trans_user_id" value="{{ $data->userId }}">
                        <input type="hidden" name="user_mobile" id="trans_user_mobile"  value="{{ $data->mobile }}">
                        <input type="hidden" name="role_id" id="trans_user_role_id"  value="{{ $data->parent_role_id }}">
                        <div class="row">
                        
                             <div class="col-8">
                                 
                                <div class="form-group">
                                    <table class="table table-default" style="width:63%;margin-left:1.8%">
                                    <tbody>
                                    
                                        <tr>
                                            <td class="label font-default"  style="font-size: 20px;width: 150px;"><i class="mdi mdi-account-outline"></i> User ID</td>
                                            <td class="label font-default"  style="font-size: 20px;"> <span id="tr_user_name"></span>{{ $data->username }}</td>
                                            </tr>
                                              <tr>
                                            <td class="label font-sm"  style="font-size: 20px;"><i class="mdi mdi-cellphone"></i> Business Name </td>
                                            <td class="label font-sm"  style="font-size: 20px;"><span id="tr_user_mobile"></span>{{ $data->store_name }}</td>
                                            </tr>
                                            <tr>
                                            <td class="label font-sm"  style="font-size: 20px;width: 150px;"><i class="mdi mdi-account"></i> Full Name </td>
                                            <td class="label font-sm"  style="font-size: 20px;"><span id="tr_user_name1"></span>{{ $data->first_name }} </td>
                                            </tr>
                                            
                                            <!--<tr>-->
                                            <!--<td class="label font-sm"  style="font-size: 20px;"><i class="mdi mdi-account-network"></i> User Type </td>-->
                                            <!--<td class="label font-sm"  style="font-size: 20px;"><span id="tr_user_role"></span>@php if($data->roleId == 2) { echo 'DISTRIBUTOR'; } @endphp</td>-->
                                            <!--</tr>-->
                                            
                                            <tr>
                                            <td class="label font-sm"  style="font-size: 20px;"><i class="mdi mdi-cellphone"></i> Mobile Number</td>
                                            <td class="label font-sm"  style="font-size: 20px;"><span id="tr_user_mobile"></span>{{ $data->mobile }}</td>
                                        </tr>
                                        
                                        
                                        
                                        <tr>
                                        <td class="label font-sm"  style="font-size: 20px;"><i class="mdi mdi-email"></i> Email Id</td>
                                            <td class="label font-sm"  style="font-size: 20px;"><span id="tr_user_role"></span>{{ $data->email }}</td>
                                        </tr>
                                        
                                      
                                        
                                        <tr>
                                        <td class="label font-sm"  style="font-size: 20px;width: 200px;"><i class="mdi mdi-wallet"></i> Wallet Amount</td>
                                            <td class="label font-sm"  style="font-size: 20px;xolor:blue"><strong><i class="mdi mdi-currency-inr"></i> <span id="tr_user_balance"></span>{{ $data->wallet_balance }}/-</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                    
                    
                                   
                                    <!-- <label for="amount">Tranfer Type</label> -->
                                    <input type="hidden" class="form-control" name="transfer_type" value="transfer"  style="height: 25px; float: left; "/>
                                    <input type="hidden" id="bbb" class="form-control" name="payment_type"  style="height: 25px; float: left; "/>
                            </div>
                            <script type="text/javascript">
                                function setValue(value) {
                                    document.getElementById('bbb').value = value;
                                }
                            </script>
                            
                            <div class="col-8">
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
                                    
                                 
                                   
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="form-group">
                                    <label for="amount" style="font-size:22px !important;">Amount</label>
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
                            
                            <div class="col-8">
                                <div class="form-group">
                                    <label for="message" style="font-size:22px !important;">MPIN</label>
                                    <input type="text" class="form-control"  name="mpin">
                                </div>
                                    @if(Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.DISTRIBUTOR') || Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.MASTER_DISTRIBUTOR') )
                            <button type="submit" class="btn btn-lg " name="set_Value" id="set_Value" value="CASH" onclick="setValue(this.value)"  style=" float:right; width:150px;background-color:green;
                             color: white;
                            border-color: #ffffff;width:100px;">Cash</button>
                                            <button type="submit" class="btn btn-lg success-grad pull-right" style=" float:right; background-image: linear-gradient(to right, #251c63 , #dc182d);
                             color: white;
                            border-color: #ffffff;width:100px;" name="set_Value" id="set_Value" value="CREDIT" onclick="setValue(this.value)" > Credit</button>
                            
                                    @endif
                            </div>
                           
                               
                    
                        </div>
                </div>
                <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
    <!--                <button type="submit" class="btn btn-lg success-grad " style=" background-image: linear-gradient(to right, #251c63 , #dc182d);-->
    <!-- color: white;-->
    <!--border-color: #ffffff;"><i class="fa fa-reply"></i> Revert</button>-->
    
                </div>
            </form>
                    @endforeach
                    
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

</div>

<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script src="{{ asset('dist\bank\js\transRevBalValidation.js') }}"></script>
<script src="{{ asset('dist\bank\js\transRevBal.js') }}"></script>
@endsection
