{{-- @extends('layouts.full') --}}
@extends('layouts.full_new')
@section('page_content')

@if( Auth::user()->roleId != Config::get('constants.DISTRIBUTOR'))
<section>
@endif
<style>
    th {
  text-transform: uppercase;
}
</style>
<!-- This page plugin CSS -->
<link href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css"
        href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('dist/bank/css/allTransfer.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('template_assets/other/css/flatpickr.min.css') }}">
@if( Auth::user()->roleId == Config::get('constants.DISTRIBUTOR'))
<div class="page-content container-fluid">
@endif
<!-- Transfer Revert table starts -->
<div class="row">
    <div class="col-12">
        <div class="material-card card">
            <div class="card-body">
                <h4 class="card-title">All Transfer</h4>
                <br>
                    <form action="{{ $_SERVER['REQUEST_URI'] }}" method="post">
                    @csrf
                        <div class="row">
                            <div class="col-2">
                                <div class="form-group">
                                    <input type="text" id="from_date" name="from_date"  class="form-control flat-picker"  value="{{ $request->from_date }}" placeholder="From Date">
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-group">
                                    <input type="text" id="to_date" name="to_date"  class="form-control flat-picker"  value="{{ $request->to_date }}" placeholder="To Date">
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-lg success-grad" style="height: calc(2.1rem + .75rem + 2px);"><i class="fa fa-filter"></i> Filter</button>
                                </div>
                            </div>
                            <div class="col-6 text-right">
                            <a type="button" href="{{ $_SERVER['REQUEST_URI'] }}" class="btn btn-sm" ><i class="mdi mdi-refresh fa-2x"></i></a>
                            </div>
                        </div>
                    </form>

                    <table id="all-transfer-table" class="table table-sm table-striped table-sm border is-data-table">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                @if(Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN'))
                                <th>Transfer By</th>
                                @endif
                                <th>Transfer To</th>
                                <th>Transfer Type</th>
                                @if(Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.DISTRIBUTOR'))
                                <th>Payment Type</th>
                                @endif
                                <th>Mobile No.</th>
                                <th>Transfer Date</th>
                                @if(Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN'))
                                <th>Bank</th>
                                <th>Reference Id</th>
                                @endif                                
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allTransfers as $index => $data)
                                <tr>
                                    <td>{{ $index+1 }}</td>
                                    @if(Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN'))
                                        <td>{{ $data->transfered_by ? $data->transfered_by : '' }}</td>
                                    @endif   
                                   
                                    <!-- <td>{{-- $data->first_name ? $data->first_name : '' --}}</td> -->
                                    <td>{{ $data->username ? $data->username : '' }}</td>
                                    <td class="label {{ $data->transfer_type == 'CREDIT' ? 'text-success' : 'text-warning' }}">{{ $data->transfer_type == "CREDIT" ? 'TRANSFER' : 'REVERT' }}</td>
                                    @if(Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.DISTRIBUTOR'))
                                    <td>{{ $data->payment_type }}</td>
                                    @endif
                                    <td>{{ $data->mobile_no }}</td>
                                    <td>{{ isset($data->trans_date) ? date('d/m/y H:m:s', strtotime($data->trans_date)) : ''}}</td>
                                    @if(Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN'))
                                        <td>{{ $data->bank }}</td>
                                        <td>{{ $data->reference_id }}</td>
                                    @endif   
                                    <td>{{ $data->amount }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Sr No</th>
                                @if(Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN'))
                                <th>Transfer By</th>
                                @endif
                                <th>Transfer To</th>
                                <th>Transfer Type</th>
                                @if(Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.DISTRIBUTOR'))
                                <th>Payment Type</th>
                                @endif
                                <th>Mobile No.</th>
                                <th>Transfer Date</th>
                                @if(Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN'))
                                <th>Bank</th>
                                <th>Reference Id</th>
                                @endif                                
                                <th>Amount</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- All Transfer table ends -->
@if( Auth::user()->roleId == Config::get('constants.DISTRIBUTOR'))
</div >
@endif
@if( Auth::user()->roleId != Config::get('constants.DISTRIBUTOR'))
</section>
@endif
<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<!--Datable plugins -->
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('template_assets/dist/js/pages/datatable/datatable-basic.init.js') }}"></script>
<!-- Datatable plugin ends -->
<script src="{{ asset('template_assets/other/js/flatpickr.js') }}"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script src="{{ asset('dist/bank/js/allTransfer.js') }}"></script>
@endsection
