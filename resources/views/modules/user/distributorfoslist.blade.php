@extends('layouts.full_new')

@section('page_content')


<!-- This page plugin CSS -->
<link href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('template_assets\other\css\bootstrap-toggle.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('dist\user\css\user_list.css') }}">
<!-- User table starts -->
<div class="page-content container-fluid">
    <!-- ============================================================== -->
    <!-- Card Group  -->
    <!-- ============================================================== -->

    <div class="card-group">
        <div class="card p-2 p-lg-3">
            <h4 class="font-weight-bold text-dark">Fos List</h4>

            <h4 class="card-title ">Filter</h4>
            <hr>
            <div class="p-lg-3 p-2">
                <form action="/distributor_fos_list">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-sm-2">
                            <div class="form-group">
                                <label for="exampleInputEmail1" class="font-weight-bold">AGENT ID</label>
                                <input type="text" class="form-control" name="agentid" placeholder="Agent Id" value="{{request()->input('agentid')}}">
                            </div>
                        </div>

                        <div class="col-12 col-sm-2">
                            <div class="form-group">
                                <label for="exampleInputEmail1" class="font-weight-bold">AGENT NAME</label>
                                <input type="text" class="form-control" name="agentname" placeholder="Agent Name" value="{{request()->input('agentname')}}">
                            </div>
                        </div>


                        <div class="col-12 col-sm-2">
                            <div class="form-group">
                                <label for="exampleInputEmail1" class="font-weight-bold">MOBILE NUMBER</label>
                                <input type="number" class="form-control" name="agentmobile" placeholder="Mobile No" value="{{request()->input('agentmobile')}}">
                            </div>
                        </div>



                        <button type="submit" class="btn btn-lg success-grad " style="height: 40px;margin-top:30px;height: calc(2.1rem + .75rem + 2px);">Submit</button>
                </form>

            </div>
        </div>
    </div>
</div>

<!-- tablesection -->
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-12">

                <div class="table-responsive">

                    <table id="fosdatatable" class="table table-sm table-centered table-striped table-bordered  border ">
                        <thead>
                            <tr>

                                <th>S NO</th>
                                <th>FOS ID</th>
                                <th>FULL NAME</th>
                                <th>MOBILE NUMBER</th>
                                <!-- <th>Role</th> -->
                                <th>AVAILABLE BALANCE</th>
                                <th>LOGIN STATUS</th>
                                <th class="text-center">ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($userList as $index => $user)
                            <tr>
                                <td>{{$index+1}}</td>
                                <td>{{$user->username}}</td>
                                <td>{{$user->first_name}} {{$user->last_name}}</td>
                                <td>{{$user->mobile}}</td>
                                <td>{{$user->wallet_balance}}</td>
                                @if($user->activated_status == 'YES')
                                <td><button class="btn btn-success">ACTIVE</button></td>
                                @else
                                <td><button class="btn btn-warning">INACTIVE</button></td>
                                @endif

                                <td>
                                    <a type="button" class="btn btn-sm btn-primary mx-1 my-1" title="Edit" href="{{ route('edit_user',$user->userId) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger revert-btn" title="Revert" value="{{ $user }}">
                                        <i class="fa fa-reply"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-success transfer-btn" title="Transfer" value="{{ $user }}">
                                        <i class="fa fa-share"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>S No</th>
                                <th>FOS ID</th>
                                <th>FULL NAME</th>
                                <th>MOBILE NUMBER</th>
                                <!-- <th>Role</th> -->
                                <th>AVAILABLE BALANCE</th>
                                <th>LOGIN STATUS</th>
                                <th class="text-center">Action</th>

                            </tr>
                        </tfoot>
                    </table>

                </div>
            </div>
        </div>

    </div>
</div>
</div>
<!-- User table ends -->


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






<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<!--Datable plugins -->
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('template_assets/dist/js/pages/datatable/datatable-basic.init.js') }}"></script>
<!-- Datatable plugin ends -->
<script src="template_assets\other\js\bootstrap-toggle.min.js"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script src="{{ asset('dist\user\js\userList.js') }}"></script>
<script>
    function openDialog(id) {
        var uid = id;
        console.log(uid);
        document.getElementById("allow_pg_user_id").value = uid;
        $('#pg-options').modal('show');
    }

    function openDialog1(pg) {
        var uid = pg.value;
        console.log(uid);
        document.getElementById("allow_pg_user_id").value = uid;
        $('#pg-services').modal('show');
    }
</script>

<script>
    $(document).ready(function() {
        $('#fosdatatable').DataTable({
            "pageLength": 10
        });
    });
</script>


<script src="{{ asset('dist\bank\js\transRevBalValidation.js') }}"></script>
<script src="{{ asset('dist\bank\js\transRevBal.js') }}"></script>
@endsection