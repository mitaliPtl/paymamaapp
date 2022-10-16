{{-- @extends('layouts.full') --}}
@extends('layouts.full_new')

@section('page_content')

<!-- <section> -->
<div class="page-content container-fluid">
<!-- This page plugin CSS -->
<link href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css"
        href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('template_assets/other/css/bootstrap-toggle.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('template_assets/other/css/flatpickr.min.css') }}">

<link rel="stylesheet" type="text/css" href="{{ asset('dist/bank/css/balance_request.css') }}">
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
                <h4 class="card-title" style="font-weight:bold;color:#BE1D2C;"> BALANCE REQUEST REPORT</h4>
                
               
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
                                <div class="col-2" style="margin-left:-20px;">
                                    <div class="form-group">
                                        <input type="text" id="to_date" name="to_date"  class="form-control flat-picker"  value="{{ $request->to_date }}" placeholder="To Date">
                                    </div>
                                </div>
                                <div class="col-2" style="margin-left:-20px;">
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
                @if( Auth::user()->roleId == Config::get('constants.DISTRIBUTOR') || Auth::user()->roleId == Config::get('constants.RETAILER') || Auth::user()->roleId == Config::get('constants.MASTER_DISTRIBUTOR'))
                <div class="row card-title">
                    <div class="col-12 text-right">
                        <a href="/balance_request" class="btn btn-primary btn-md balance-request-btn btn-lg success-grad" style="height: calc(2.1rem + .75rem + 2px);"><i class="fa fa-plus"></i> Request Now</a>
                    </div>
                </div>
                @endif
                <br>
                <div class="table-responsive">
                        <table id="recharge-report-table" class="table table-striped table-sm border is-data-table">
                   <!-- <table id="va-ac-table" class="table table-striped table-sm border" style="text-align:center;"> -->

                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>DATE & TIME</th>
                                <th>DEPOSIT DATE</th>
                                <th>ORDER ID</th>
                                <!-- <th>Reference Id</th> -->
                                
                                
                                @if( Auth::user()->roleId == Config::get('constants.ADMIN'))
                                <!-- <th>BUSINESS NAME</th> -->
                                <th>ROLE</th>
                                <th>DEPOSITER'S NAME</th>
                                <th>DEPOSITER'S BANK</th>
                                <th>MOBILE NUMBER</th>
                                @endif
                                <th>AMOUNT</th>
                                <th>BANK DETAILS</th>
                                <th>BRANCH</th>
                                <th>MODE</th>
                                <th>REFERENCE NUMBER</th>
                                <th>DEPOSITER DETAILS</th>
                                <th>REMARK</th>
                                <th>STATUS</th>
                                <th>ADMIN RESPONSE</th>
                                 <th>ACTION</th>
                                @if( Auth::user()->roleId == Config::get('constants.ADMIN'))
                                <th class="text-center">ACTION</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($balanceRequests as $index => $request)
                                <tr>
                                    <td>{{ $index+1 }}</td>
                                    <td>{{ isset($request->trans_date) ? date('d/m/y H:m:s', strtotime($request->trans_date)) : ''}}</td>
                                    <td>{{ isset($request->deposit_date) ? date('d/m/y', strtotime($request->deposit_date)) : ''}}</td>
                                    <td>{{ $request->transaction_id }}</td>
                                   
                                    
                                    
                                    @if( Auth::user()->roleId == Config::get('constants.ADMIN'))
                                    <!-- <td>{{ $request->user_id }}</td> -->
                                    <td>{{ $request->role }}</td>
                                    <td>{{ $request->account_holder_name }}</td>
                                    <td>{{ $request->account_holder_bank_name }}</td>
                                    <td>{{ $request->mobile_no }}</td>
                                    @endif
                                    
                                    <td>{{ $request->amount }}</td>
                                    <td><?php
                                        $bankDetails=explode("//", $request->bank);
                                        echo  @$bankDetails[0];
                                    ?>
                                    </td>
                                    <td><?php
                                    echo  @$bankDetails[1];
                                    ?>
                                    </td>
                                    <td>{{ $request->mode }}</td>
                                    <td>{{ $request->reference_id }}</td>
                                     <td></td>
                                    <td>{{ $request->message }}</td>
                                    
                                    
                                    <td  class="label text-center {{ $request->status == 'PENDING' ? 'text-warning' : ($request->status == 'DECLINE' ? 'text-danger' : 'text-success')}}">
                                        <i class="fa {{ $request->status == 'PENDING' ? 'fa-hourglass-half' : ($request->status == 'DECLINE' ? 'fa-times-circle' : 'fa-check-circle')}}"></i> {{ $request->status }}</td>
                                        <td></td>
                                        <td></td>
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
                                <th>DATE & TIME</th>
                                <th>DEPOSIT DATE</th>
                                <th>ORDER ID</th>
                                <!-- <th>Reference Id</th> -->
                                
                                
                                @if( Auth::user()->roleId == Config::get('constants.ADMIN'))
                                <!-- <th>BUSINESS NAME</th> -->
                                <th>ROLE</th>
                                <th>DEPOSITER'S NAME</th>
                                <th>DEPOSITER'S BANK</th>
                                <th>MOBILE NUMBER</th>
                                @endif
                                <th>AMOUNT</th>
                                <th>BANK DETAILS</th>
                                <th>BRANCH</th>
                                <th>MODE</th>
                                <th>REFERENCE NUMBER</th>
                                <th>DEPOSITER DETAILS</th>
                                <th>REMARK</th>
                                <th>STATUS</th>
                                <th>ADMIN RESPONSE</th>
                                 <th>ACTION</th>
                                @if( Auth::user()->roleId == Config::get('constants.ADMIN'))
                                <th class="text-center">ACTION</th>
                                @endif
                            </tr>
                        </tfoot>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Balance Request table ends -->
<style>
                                 td{
                                      border:1px solid #7f7f7f14 !important;
                                  }
                                  th{
                                      border:1px solid #7f7f7f14 !important;
                                  }
                                </style>

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
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="bank">Transfer Bank</label>
                                    <select class="form-control" id="bank" name="bank"> 
                                        <option disabled selected>Select</option>
                                        @foreach($bankAccounts as $i => $account)
                                                <option value="{{ $account['bank_name'] }}">{{ $account['bank_name'] }}</option>
                                            <!-- @if($account['type'] == "mode")
                                                <option value="{{ $account['value'] }}" class="font-sm">&nbsp;&nbsp;{{ $account['name'] }}</option>
                                            @endif -->
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                         </div>
                        
                        <div class="row">
                        <div class="col-6">
                                <div class="form-group">
                                    <label for="transefer_mode">Transfer Mode</label>
                                    <select class="form-control" id="transefer_mode" name="transefer_mode"> 
                                        <option disabled selected>Transfer Mode</option>
                                        <option value="IMPS">IMPS</option>
                                    <option value="RTGS">RTGS</option>
                                    <option value="NEFT">NEFT</option>
                                    <option value="CASH">CASH</option>
                                    <option value="UPI">UPI</option>
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
                                    <label for="amount">Date</label>
                                    <input type="date" class="form-control" id="date_deposited" name="date_deposited">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="account_holder_name">Depositer's Name</label>
                                    <input type="text" class="form-control" id="account_holder_name" name="account_holder_name">
                                </div>
                            </div>
                            
                        </div>
                        
                        
                        
                        <div class="row">
                        <div class="col-6">
                                <div class="form-group">
                                    <label for="account_holder_bank_name">Depositer's Bank Name</label>
                                    <input type="text" class="form-control" id="account_holder_bank_name" name="account_holder_bank_name">
                                </div>
                            </div>
                        <div class="col-6">
                                <div class="form-group">
                                    <label for="account_holder_mode">Depositer's Payment Mode</label>
                                    <select class="form-control"  name="account_holder_mode" id="account_holder_mode">
                                    <option value="">Depositer's Mode</option>
                                    <option value="IMPS">IMPS</option>
                                    <option value="RTGS">RTGS</option>
                                    <option value="NEFT">NEFT</option>
                                    <option value="CASH">CASH</option>
                                    <option value="UPI">UPI</option>
                                    </select>
                                </div>
                            </div>
                            
                            
                        </div>
                        <div class="row">
                        <div class="col-6">
                                <div class="form-group">
                                    <label for="reference_id">UTR Number</label>
                                    <input type="text" class="form-control" id="reference_id" name="reference_id">
                                </div>
                            </div>
                        <div class="col-6">
                                <div class="form-group">
                                    <label for="message">Remark</label>
                                    <input type="text" class="form-control" id="message" name="message">
                                </div>
                            </div>
                          
                          <!--   <div class="col-12 mt-2  text-center hide-this" id="qr-code-div">
                                    <h4>Scan QR Code</h4>
                                    <img src="{{ $qrCodeFilePath }}" alt="QR Code" style="width:60%">
                            </div> -->
                        </div>
                        
                        <div class="row">
                            <div class="col-12" id="receiptFile-div">
                                <div class="form-group">
                                    <button type="button" id="form-file-up-btn" class="btn btn-warning btn-md" style="width:100%"><i class="mdi mdi-upload"></i> Upload Receipt</button>
                                    <input type="hidden" class="form-control" id="uploaded_file_id" name="receipt_file">
                                </div>
                            </div>

                          <!--   <div class="col-12 mt-2  text-center hide-this" id="qr-code-div">
                                    <h4>Scan QR Code</h4>
                                    <img src="{{ $qrCodeFilePath }}" alt="QR Code" style="width:60%">
                            </div> -->
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
