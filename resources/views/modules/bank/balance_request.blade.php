{{-- @extends('layouts.full') --}}
@extends('layouts.full_new')

@section('page_content')

<!-- <section> -->
<div class="page-content container-fluid">
<!-- This page plugin CSS -->
<link href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css"
        href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('template_assets\other\css\bootstrap-toggle.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('template_assets\other\css\flatpickr.min.css') }}">

<link rel="stylesheet" type="text/css" href="{{ asset('dist\bank\css\balance_request.css') }}">
<style>
    th {
  text-transform: uppercase;
}
</style>
<!-- Balance Request table starts -->
                @if(Session::has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>SUCCESS</strong>  {{ Session::get('success') }}.{{  Session::forget('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @elseif(Session::has('error')) 

                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>FAILED</strong>  {{ Session::get('error') }}.
                        {{  Session::forget('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
<div class="row">
               
    <div class="col-12">
        <div class="material-card card">
            <div class="card-body">
                <h4 class="card-title">Balance Request</h4>
               
                <div class="row">
                    <div class="col-12 text-right mb-2">
                      @if(isset($total_amt) && $total_amt)
                        <button type="button" title="Total Amount" class="btn btn-light info-button btn-md mr-2"> Amount : {{ $total_amt }}</button>
                        @endif
                    </div>
                    <div class="col-11">
                    <div class="collapse show" id="filterBox">
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
                                        <button type="submit" class="btn btn-outline-primary btn-lg success-grad" style="height: calc(2.1rem + .75rem + 2px);"><i class="fa fa-filter"></i> Filter</button>
                                    </div>
                                </div>
                                
                            </div>
                        </form>     
                    </div>
                    </div>
                    <div class="col-1 text-right">
                                <a type="button" href="{{ $_SERVER['REQUEST_URI'] }}" class="btn btn-sm" ><i class="mdi mdi-refresh fa-2x"></i></a>
                                </div>
                    </div>
                <br>
                @if( Auth::user()->roleId == Config::get('constants.DISTRIBUTOR') || Auth::user()->roleId == Config::get('constants.RETAILER'))
                <div class="row card-title">
                    <div class="col-12 text-right">
                        <button type="button" class="btn btn-primary btn-md balance-request-btn btn-lg success-grad" data-toggle="modal" data-target="#balanceReqModal" style="height: calc(2.1rem + .75rem + 2px);"><i class="fa fa-plus"></i> Request Now</button>
                    </div>
                </div>
                @endif
                <br>
                    <!-- <table id="balance-request-table" class="table table-striped table-sm border is-data-table"> -->
                    <table id="balance-request-table" class="table table-striped table-bordered table-sm border is-data-table">

                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Bank</th>
                                <th>Mode</th>
                                <th>Reference Id</th>
                                @if( Auth::user()->roleId == Config::get('constants.ADMIN'))
                                <th>Member Name</th>
                                <th>Role</th>
                                <th>Mobile No.</th>
                                @endif
                                <th>Amount</th>
                                <th>Message</th>
                                <th>Admin's Reply</th>
                                <th>Transfer Date</th>
                                <th>Status</th>
                                @if( Auth::user()->roleId == Config::get('constants.ADMIN'))
                                <th class="text-center">Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($balanceRequests as $index => $request)
                                <tr>
                                    <td>{{ $index+1 }}</td>
                                    <td>{{ $request->bank }}</td>
                                    <td>{{ $request->mode }}</td>
                                    <td>{{ $request->reference_id }}</td>
                                    @if( Auth::user()->roleId == Config::get('constants.ADMIN'))
                                    <td>{{ $request->user_name }}</td>
                                    <td>{{ $request->role }}</td>
                                    <td>{{ $request->mobile_no }}</td>
                                    @endif
                                    <td>{{ $request->amount }}</td>
                                    <td>{{ $request->message }}</td>
                                    <td>{{ $request->admin_reply }}</td>
                                    <td>{{ isset($request->trans_date) ? date('d/m/y H:m:s', strtotime($request->trans_date)) : ''}}</td>
                                    <td  class="label text-center {{ $request->status == 'PENDING' ? 'text-warning' : ($request->status == 'DECLINE' ? 'text-danger' : 'text-success')}}">
                                        <i class="fa {{ $request->status == 'PENDING' ? 'fa-hourglass-half' : ($request->status == 'DECLINE' ? 'fa-times-circle' : 'fa-check-circle')}}"></i> {{ $request->status }}</td>
                                    @if( Auth::user()->roleId == Config::get('constants.ADMIN'))
                                    <td class="text-center">
                                        <div class="btn-group">
                                            @if( $request->status == 'PENDING')

                                            <button type="button" class="btn btn-sm btn-primary transfer-btn" title="Transfer" value="{{ $request->id }}">
                                                <i class="fa fa-paper-plane"></i>
                                            </button>
                                            @endif
                                          
                                            <button type="button" class="btn btn-sm btn-info reply-btn" title="Reply" value="{{ $request->id }}">
                                                <i class="fa fa-reply"></i>
                                            </button>
                                            @if($request->reciept_src)
                                            <a href="{{$request->reciept_src}}" target="_blank" class="btn btn-sm btn-warning view-btn" data-id="{{ $request->id }}" title="View">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            @endif
                                            @if( $request->status == 'PENDING')
                                            <a href="{{ route('deline_bal_req', $request->id  ) }}" class="btn btn-sm btn-danger decline-btn" title="Decline" value="{{ $request->id }}">
                                                <i class="fa fa-ban"></i>
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Sr No</th>
                                <th>Bank</th>
                                <th>Mode</th>
                                <th>Reference Id</th>
                                @if( Auth::user()->roleId == Config::get('constants.ADMIN'))
                                <th>Member Name</th>
                                <th>Role</th>
                                <th>Mobile No.</th>
                                @endif
                                <th>Amount</th>
                                <th>Message</th>
                                <th>Admin's Reply</th>
                                <th>Transfer Date</th>
                                <th>Status</th>
                                @if( Auth::user()->roleId == Config::get('constants.ADMIN'))
                                <th class="text-center">Action</th>
                                @endif
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Balance Request table ends -->

<!-- Balance Request Add modal starts -->
<div class="modal" id="balanceReqModal" tabindex="-1" role="dialog" aria-labelledby="balanceReqModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel1"><span class="modal-action-name"></span> Request Balance</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form method="post" action="{{ route('send_balance_request') }}" id="addBalanceReqForm">
            @csrf
                <div class="modal-body">
                        <input type="hidden" name="id" id="id">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="bank">Select Bank</label>
                                    <select class="form-control" id="bank" name="bank"> 
                                        <option disabled selected>Select</option>
                                        <option class="label text-blue" value="QR_CODE">QR Code</option>
                                        @foreach($bankAccounts as $i => $account)
                                            @if($account['type'] == "label")
                                                <option disabled class="label text-info">{{ $account['name'] }}</option>
                                            @endif
                                            @if($account['type'] == "mode")
                                                <option value="{{ $account['value'] }}" class="font-sm">&nbsp;&nbsp;{{ $account['name'] }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="amount">Amount</label>
                                    <input type="number" class="form-control" id="amount" name="amount">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="reference_id">Ref. Id</label>
                                    <input type="text" class="form-control" id="reference_id" name="reference_id">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="message">Remark</label>
                                    <input type="text" class="form-control" id="message" name="message">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6" id="receiptFile-div">
                                <div class="form-group">
                                    <button type="button" id="form-file-up-btn" class="btn btn-warning btn-md" style="width:100%"><i class="mdi mdi-upload"></i> Upload Receipt</button>
                                    <input type="hidden" class="form-control" id="uploaded_file_id" name="receipt_file">
                                </div>
                            </div>

                            <div class="col-12 mt-2  text-center hide-this" id="qr-code-div">
                                    <h4>Scan QR Code</h4>
                                    <img src="{{ $qrCodeFilePath }}" alt="QR Code" style="width:60%">
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-lg" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-lg submit-btn success-grad">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Balance Request Add modal ends -->

<!-- File Upload modal starts -->
<div class="modal" id="fileUploadModal" role="dialog">
            <div class="modal-dialog modal-sm" role="document" style="margin-left:525px">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLabel1"><span class="modal-action-name"></span>Upload File</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <form id="fileUploadForm" enctype="multipart/form-data">
                    @csrf
                        <div class="custom-file">
                            <input type="file" name="file" class="custom-file-input" id="chooseFile" required>
                            <label class="custom-file-label" for="chooseFile">Select file</label>
                        </div>

                        <button type="submit" id="file-upload-btn" class="btn btn-info btn-block mt-4">
                            Upload File
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <!-- File uplaod modal ends -->

<!-- Balance RequestReply modal starts -->
<div class="modal" id="requestReplyModal" tabindex="-1" role="dialog" aria-labelledby="requestReplyModal">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel1"><span class="modal-action-name"></span>Your Reply</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form method="post" action="{{ route('balance_request_reply') }}" id="balReqReplyForm">
            @csrf
                <div class="modal-body">
                    <input type="hidden" name="bal_req_id" id="bal_req_id">

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
<!-- Balance Request Reply modal ends -->

<!-- Transfer Balance modal starts -->
<div class="modal" id="balTransModal" tabindex="-1" role="dialog" aria-labelledby="balTransModal">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel1"><span class="modal-action-name"></span>Transfer Balance</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form method="post" action="{{ route('transfer_request_balance') }}" id="transBalForm">
            @csrf
                <div class="modal-body">
                    <input type="hidden" name="trans_req_id" id="trans_req_id">

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="admin_reply">Enter MPin</label>
                                <input type="number" class="form-control" id="mpin" name="mpin" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="admin_reply">Enter Reply</label>
                                <textarea type="text" class="form-control" id="trans_bal_message" name="message"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary submit-btn"><i class="fa fa-paper-plane"></i> Transfer</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Transfer Balance modal ends -->

</div>
<!-- </section> -->

<script src="{{ asset('template_assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<!--Datable plugins -->
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('template_assets/dist/js/pages/datatable/datatable-basic.init.js') }}"></script>
<!-- Datatable plugin ends -->
<script src="{{ asset('template_assets\other\js\flatpickr.js') }}"></script>

<script src="template_assets\other\js\bootstrap-toggle.min.js"></script>
<script src="template_assets\other\js\sweetalert.min.js"></script>
<script src="{{ asset('template_assets/assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script src="{{ asset('dist\bank\js\balanceReqValidation.js') }}"></script>
<script src="{{ asset('dist\bank\js\balance_request.js') }}"></script>
@endsection
