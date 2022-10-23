@extends('layouts.full')

@section('page_content')

<section>
<!-- This page plugin CSS -->
<link href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css"
        href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('template_assets\other\css\bootstrap-toggle.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('dist\user\css\user_list.css') }}">

<!-- Member List starts -->
<div class="row">
    <div class="col-12">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           <div class="material-card card">
            <div class="card-body">
                <h4 class="card-title">Member List</h4>
                <br>
                <div class="table-responsive">
                    <table id="user-table" class="table table-striped table-sm border is-data-table">
                        <thead>
                            <tr>
                                <th>SR NO</th>
                                <th>RETAILER ID</th>
                                <th>FULL NAME</th>
                                <th>BUSINESS NAME</th>
                                <th>MOBILE NUMBER</th>
                                <th>EMAIL ID</th>
                                <th>DISTRIBUTOR DETAILS</th>
                                <th>STATUS</th>
                                <th>PG STATUS</th>
                                <th class="text-center">ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($userList as $index => $user)
                                <tr>
                                    <td>{{ $index+1 }}</td>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                                    <td>{{ $user->store_name }}</td>
                                    <td>{{ $user->mobile }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->parentuser->userId }}, {{ $user->parentuser->store_name }}, {{ $user->parentuser->mobile }}</td>
                                    <td class="text-center">
                                        @if($user->activated_status == Config::get('constants.ACTIVE'))
                                            <input checked id="status-btn_{{ $index+1 }}" class="status-btn" type="checkbox"
                                             data-id="{{ $user->userId }}" data-on="Active" data-off="Inactive" data-onstyle="success"
                                              data-toggle="toggle" data-width="90" data-style="ios" data-style="slow"
                                              {{ (Auth::userRoleAlias() != Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN')) ? 'disabled' : '' }}>
                                        @else
                                            <input id="status-btn_{{ $index+1 }}" class="status-btn" type="checkbox"
                                             data-id="{{ $user->userId }}" data-on="Active" data-off="Inactive" data-onstyle="success"
                                              data-toggle="toggle" data-width="90" data-style="ios" data-style="slow"
                                              {{ (Auth::userRoleAlias() != Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN')) ? 'disabled' : '' }}>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->pg_status)
                                            <input checked id="status-btn_{{ $index+1 }}" class="status-btn" type="checkbox"
                                             data-id="{{ $user->userId }}" data-on="Active" data-off="Inactive" data-onstyle="success"
                                              data-toggle="toggle" data-width="90" data-style="ios" data-style="slow"
                                              {{ (Auth::userRoleAlias() != Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN')) ? 'disabled' : '' }}>
                                        @else
                                            <input id="status-btn_{{ $index+1 }}" class="status-btn" type="checkbox"
                                             data-id="{{ $user->userId }}" data-on="Active" data-off="Inactive" data-onstyle="success"
                                              data-toggle="toggle" data-width="90" data-style="ios" data-style="slow"
                                              {{ (Auth::userRoleAlias() != Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN')) ? 'disabled' : '' }}>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a type="button" class="btn btn-sm btn-primary" onclick="modesModal(this)" id="modeModal-{{ $user->userId }}" data-modes="{{ $user->pg_options }}" data-toggle="modal" data-target="#pgModesModal" title="Modes">
                                            <i class="fa-brands fa-modx"></i>
                                        </a>
                                        <a type="button" class="btn btn-sm btn-primary" title="Edit" href="{{ route('edit_user',$user->userId) }}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                            <th>SR NO</th>
                                <th>RETAILER ID</th>
                                <th>FULL NAME</th>
                                <th>BUSINESS NAME</th>
                                <th>MOBILE NUMBER</th>
                                <th>EMAIL ID</th>
                                <th>DISTRIBUTOR DETAILS</th>
                                <th>STATUS</th>
                                <th>PG STATUS</th>
                                <th class="text-center">ACTION</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Member List ends -->

<!-- Start - PG Mode Modal -->
<div class="modal fade" id="pgModesModal" tabindex="-1" role="dialog" aria-labelledby="pgModesModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Payment Gateway Modes</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="userId" />
        <table class="table table-bordered">
            <thead>
                <tr>
                    <td>MODE</td>
                    <td>STATUS</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>UPI</td>
                    <td><input id="mode_upi" class="status-btn" type="checkbox"
                        data-id="" data-on="Active" data-off="Inactive" data-onstyle="success"
                        data-toggle="toggle" data-width="90" data-style="ios" data-style="slow"></td>
                </tr>
                <tr>
                    <td>Rupay Card</td>
                    <td><input id="mode_rupay_card" class="status-btn" type="checkbox"
                        data-id="" data-on="Active" data-off="Inactive" data-onstyle="success"
                        data-toggle="toggle" data-width="90" data-style="ios" data-style="slow"></td>
                </tr>
                <tr>
                    <td>Debit Card</td>
                    <td><input id="mode_debit_card" class="status-btn" type="checkbox"
                        data-id="" data-on="Active" data-off="Inactive" data-onstyle="success"
                        data-toggle="toggle" data-width="90" data-style="ios" data-style="slow"></td>
                </tr>
                <tr>
                    <td>Credit Card</td>
                    <td><input id="mode_credit_card" class="status-btn" type="checkbox"
                        data-id="" data-on="Active" data-off="Inactive" data-onstyle="success"
                        data-toggle="toggle" data-width="90" data-style="ios" data-style="slow"></td>
                </tr>
                <tr>
                    <td>Prepaid Card</td>
                    <td><input id="mode_prepaid_card" class="status-btn" type="checkbox"
                        data-id="" data-on="Active" data-off="Inactive" data-onstyle="success"
                        data-toggle="toggle" data-width="90" data-style="ios" data-style="slow"></td>
                </tr>
                <tr>
                    <td>Corporate Card</td>
                    <td><input id="mode_corporate_card" class="status-btn" type="checkbox"
                        data-id="" data-on="Active" data-off="Inactive" data-onstyle="success"
                        data-toggle="toggle" data-width="90" data-style="ios" data-style="slow"></td>
                </tr>
                <tr>
                    <td>Net Banking</td>
                    <td><input id="mode_net_banking" class="status-btn" type="checkbox"
                        data-id="" data-on="Active" data-off="Inactive" data-onstyle="success"
                        data-toggle="toggle" data-width="90" data-style="ios" data-style="slow"></td>
                </tr>
                <tr>
                    <td>Wallet</td>
                    <td><input id="mode_wallet" class="status-btn" type="checkbox"
                        data-id="" data-on="Active" data-off="Inactive" data-onstyle="success"
                        data-toggle="toggle" data-width="90" data-style="ios" data-style="slow"></td>
                </tr>
                <tr>
                    <td>Virtual Collect</td>
                    <td><input id="mode_virtual_collect" class="status-btn" type="checkbox"
                        data-id="" data-on="Active" data-off="Inactive" data-onstyle="success"
                        data-toggle="toggle" data-width="90" data-style="ios" data-style="slow"></td>
                </tr>
            </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
<!-- End - PG Mode Modal -->

</section>

<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<!--Datable plugins -->
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('template_assets/dist/js/pages/datatable/datatable-basic.init.js') }}"></script>
<!-- Datatable plugin ends -->
<script src="template_assets\other\js\bootstrap-toggle.min.js"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script>
    var pgModes = [];
$('#pgModesModal').on('shown', function(){

});

function modesModal(element)
{
    debugger
    console.log(element);
    pgModes = $(element).attr('data-modes');
    console.log(pgModes);
}
</script>
@endsection
