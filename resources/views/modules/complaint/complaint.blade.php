{{-- @extends('layouts.full') --}}
@extends('layouts.full_new')
@section('page_content')


<!-- This page plugin CSS -->
<link href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('template_assets\other\css\flatpickr.min.css') }}">
<link rel="stylesheet" href="{{ asset('dist\reports\css\reports.css') }}">
<style>
  
                                 td{
                                      border:1px solid #7f7f7f14 !important;
                                  }
                                  th{
                                      border:1px solid #7f7f7f14 !important;
                                  }
                                </style>

<!-- <section> -->
<div class="page-content container-fluid">
    <!-- Complaint Reports table starts -->
    <div class="row">
        <div class="col-12">
            <div class="material-card card">
                <div class="card-body">
                    <h4 class="card-title" style="font-weight:bold;color:#BE1D2C;">{{ $pageName }} </h4>
                    <div class="row">
                        <div class="col-11">
                            <div class="collapse show" id="filterBox">
                                <form action="{{ $_SERVER['REQUEST_URI'] }}" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-2">
                                            <div class="form-group">
                                                <input type="text" id="from_date" name="from_date" class="form-control flat-picker" value="{{ $request->from_date }}" placeholder="From Date">
                                            </div>
                                        </div>
                                        <div class="col-2" style="margin-left:-20px">
                                            <div class="form-group">
                                                <input type="text" id="to_date" name="to_date" class="form-control flat-picker" value="{{ $request->to_date }}" placeholder="To Date">
                                            </div>
                                        </div>
                                        <div class="col-2" style="margin-left:-20px">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-outline-primary btn-lg success-grad" style="height: calc(2.1rem + .75rem + 2px);"><i class="fa fa-filter"></i> Filter</button>
                                            </div>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>



                    </div>
                    <br>
                    <div class="table-responsive">
                        <!-- <table id="recharge-report-table" class="table table-striped table-sm border is-data-table"> -->
                        <table id="retailerdatatable" class="table table-striped  table-bordered table-sm border ">
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    @foreach($complaintListTH as $i => $head)
                                    <th>{{ $complaintListTH[$i]['name'] }}</th>
                                    @endforeach

                                </tr>
                            </thead>
                            <tbody>
                                @foreach($complaintList as $index => $report)
                                <tr>
                                    <td>{{ $index+1 }}</td>
                                    @foreach($complaintListTH as $i => $head)
                                    @if($head['label'] == 'trans_date')
                                    <td>
                                        {{isset($complaintList[$index]['trans_date']) && $complaintList[$index]['trans_date'] ? (date('d/m/y H:i:s',strtotime($complaintList[$index]['trans_date']))) : '' }}
                                    </td>
                                    @elseif($head['label'] == 'order_status')
                                    <td class="{{ $complaintList[$index][$head['label']] == 'SUCCESS' ? 'text-success' : ($complaintList[$index][$head['label']] == 'PENDING' ? 'text-warning' : 'text-danger') }}">
                                        {{ $complaintList[$index][$head['label']]}}
                                    </td>
                                    @elseif($head['label'] == 'action')
                                    <td class="btn-group">
                                        @if($complaintList[$index]['order_status'] != 'FAILED')
                                        @if($pageName == 'Money Transfer')
                                        <button type="button" id="view-invoice" onclick="getSurcharge( {{ json_encode($report) }}, {{ $rechargeReports_forinvoice[$index] }}, {{ $index }})" class="btn btn-warning btn-sm"><i class="mdi mdi-printer"></i> View </button>
                                        @else
                                        @if($complaintList[$index]['order_status'] == 'PENDING')

                                        @endif
                                        @endif
                                        @endif

                                        @if(Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN'))

                                        @if($complaintList[$index]['order_status'] == 'PENDING')
                                        <!-- <button class="btn btn-sm btn-warning change-status-btn" value="{{ $complaintList[$index]['complaint_id'] }}" title="Change Status" data-toggle="tooltip"><i class="mdi mdi-shuffle-variant"></i></button> -->
                                        @elseif($complaintList[$index]['order_status'] == 'SUCCESS')
                                        <!-- <button class="btn btn-sm btn-warning change-status-btn" value="{{ $complaintList[$index]['complaint_id'] }}" title="Change Status" data-toggle="tooltip"><i class="mdi mdi-shuffle-variant"></i></button> -->
                                        @endif
                                        <button class="btn btn-sm btn-warning change-status-btn" value="{{ $complaintList[$index]['complaint_id'] }}" title="Change Status" data-toggle="tooltip"><i class="mdi mdi-shuffle-variant"></i></button>

                                        <button type="button" class="btn btn-sm btn-info chnagetime-btn" title="Change time" value="{{ $complaintList[$index]['complaint_id'] }}">
                                            <i class="fa fa-clock"></i>
                                        </button>
                                        @endif
                                    </td>
                                    @else
                                    <td> {{ $complaintList[$index][$head['label']]}} </td>
                                    @endif
                                    @endforeach

                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Sr No</th>
                                    @foreach($complaintListTH as $i => $head)
                                    <th>{{ $complaintListTH[$i]['name'] }}</th>
                                    @endforeach
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Complaint Reports table ends -->

</div>

<!--  RequestReply modal starts -->
<div class="modal" id="complaintReplyModal" tabindex="-1" role="dialog" aria-labelledby="complaintReplyModal">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel1"><span class="modal-action-name"></span>Your Reply</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form method="post" action="{{ route('complaint_reply') }}" id="complaint_reply">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="complaint_id" id="complaint_id">

                    <div class="row">
                        <div class="col-12">
                            <label for="admin_reply">Enter Reply</label>
                            <br>
                            <div class="form-group">
                                <textarea type="text" class="form-control" id="admin_reply" name="admin_reply" required></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-info submit-btn"><i class="fa fa-reply"></i> Send</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--  Request Reply modal ends -->

<!-- Change Status modal starts -->
<div class="modal" id="chgStatusModal" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel1" style="margin-left:60px">Change Status</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form id="chgCompltStatusForm" action="{{ route('change_complaint_status') }}" method="post">
                @csrf
                <input type="hidden" name="change_complaint_id" id="change_complaint_id">
                <div class="form-group container mt-2">
                    <label for="complt_status">Select Status</label>
                    <select name="complt_status" id="complt_status" class="form-control" required>
                        <option selected disabled value="">Select</option>
                        <option value="PENDING" id="tran-sts-success-option">PENDING</option>
                        <option value="SOLVED">SOLVED</option>
                    </select>
                </div>

                <div class="col-12">
                    <label for="admin_reply">Enter Reply</label>
                    <br>
                    <div class="form-group">
                        <textarea type="text" class="form-control" id="admin_reply" name="admin_reply" required></textarea>
                    </div>
                </div>


                <button type="submit" id="change-tran-status-btn" class="btn btn-info btn-block mt-4">
                    Update
                </button>
            </form>
        </div>
    </div>
</div>
<!-- Change Status modal ends -->

<!--  Change Default  time modal starts -->
<div class="modal" id="changeTimeModel" tabindex="-1" role="dialog" aria-labelledby="changeTimeModel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel1"><span class="modal-action-name"></span>Required Time</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form method="post" action="{{ route('changetime') }}" id="complaint_reply">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="change_time_complaint_id" id="change_time_complaint_id">

                    <div class="row">
                        <div class="col-12">
                            <label for="admin_reply">Enter Time</label>
                            <br>
                            <div class="form-group">
                                <textarea type="text" class="form-control" id="change_time" name="change_time" required></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-info submit-btn"> Send</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--  Change Default  time modal ends -->
<!-- </section> -->

<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<!--Datable plugins -->
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('template_assets/dist/js/pages/datatable/datatable-basic.init.js') }}"></script>
<!-- Datatable plugin ends -->
<script src="{{ asset('template_assets\other\js\flatpickr.js') }}"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script src="{{ asset('dist\reports\js\rechargeReport.js') }}"></script>
<script src="{{ asset('dist\complaint\js\complaint.js') }}"></script>

<script>
    $(document).ready(function() {
        $('#retailerdatatable').DataTable({
            "pageLength": 10
        });
    });
</script>

@endsection