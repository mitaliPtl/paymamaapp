@extends('layouts.full_new')

@section('page_content')
<!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="page-breadcrumb border-bottom">
                <div class="row">
                    <div class="col-lg-3 col-md-4 col-xs-12 justify-content-start d-flex align-items-center">
                        <h5 class="font-medium text-uppercase mb-0">Success</h5>
                    </div>
                    <div class="col-lg-9 col-md-8 col-xs-12 d-flex justify-content-start justify-content-md-end align-self-center">
                        <nav aria-label="breadcrumb" class="mt-2">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Success</li>
                            </ol>
                        </nav>
                      
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="page-content container-fluid">

                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>ALERT !!</strong> DO NOT REFRESH THIS PAGE .
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                @if(isset($money_transfer_response))
                    @if($money_transfer_response ['status'] == "true")
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>SUCCESS</strong> {{ $money_transfer_response['msg'] }} .
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @elseif($money_transfer_response['data']['status'] == "false")

                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>FAILED</strong> {{ $money_transfer_response['msg'] }} .
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                @endif
        
                <div class="row ">
                    <div class="col-12"  >
                        <div style="float: right; margin: 10px;" ><button  class="btn btn-rounded btn-danger" onclick="subCharges();"><i class="fa fa-print"></i> PRINT</button></div>
                    </div>                    
                </div>
                <div class="row">
                    <div class="col-12 col-lg-6">
                        <div class="card">
                            <div class="card-body">
                               
                                <div class="row details1">
                                     <div class="col-12 col-lg-6"><img src="{{ asset('template_new/img/user.png') }}" width="30px"> <b>{{ $request->mobile_no }}</b></div>
                                  <div class="col-12 col-lg-6"></div>

                                   <div class="col-12 col-lg-6"><b>Rs. {{ $request->transfer_amount }}</b></div>
                                  <div class="col-12 col-lg-6"><img src="{{ asset('template_new/img/success.png') }}" width="40px" style="float:right"></div>
                              

                                 <div class="col-12 col-lg-6">A/c Name</div>
                                  <div class="col-12 col-lg-6">{{ $receipientDtls->recipient_name }}</div>
                                  @if($data['operator_name'] == 'BHIM_UPI')
                                  <div class="col-12 col-lg-6">UPI ID :</div>
                                  @else
                                  <div class="col-12 col-lg-6">A/C No :</div>
                                  @endif
                                    <div class="col-12 col-lg-6">{{ $receipientDtls->bank_account_number }}</div>
                                   
                                    @if($data['operator_name'] != 'BHIM_UPI')
                                       <div class="col-12 col-lg-6">Bank Name :</div>
                                   <div class="col-12 col-lg-6">{{ $receipientDtls->bank_name }}</div>

                                    <div class="col-12 col-lg-6">IFSC Code :</div>
                                   <div class="col-12 col-lg-6">{{ $receipientDtls->ifsc }}</div>
                                    @endif
                                   <div class="col-12 col-lg-6">Mode :</div>

                                   <div class="col-12 col-lg-6">
                                   @if($data['operator_name'] == 'BHIM_UPI')
                                   {{ $money_transfer_response['money']['mode'] }} 
                                   @else
                                   {{ $request->trans_type }}
                                    @endif
                                    </div>

                               </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                    <div class="card">
                            <div class="card-body">
                               
                                <div class="row details">
                                   <div class="col-12 col-lg-6">Transfer Amount <br>
                                    <b>Rs. {{ $money_transfer_response['money']['amount'] }}</b></div>
                                  <div class="col-12 col-lg-6">
                                    CCF Charges<br>
                                    <b>Rs. {{ $money_transfer_response['money']['CCFcharges'] }}</b></div>
                              

                                

                                  <div class="col-12 col-lg-6">Cashback<br>
                                    <b>Rs. {{ $money_transfer_response['money']['Cashback'] }}</b></div>
                                    <div class="col-12 col-lg-6">TDS Amount<br>
                                        <b>Rs. {{ $money_transfer_response['money']['TDSamount'] }}</b></div>

                                    <div class="col-12 col-lg-6">Transfer Charges <br>
                                        <b>Rs. {{ $money_transfer_response['money']['PayableCharge'] }}</b></div>
                                   <div class="col-12 col-lg-6">Total<br>
                                    <b><span class="success-amt">Rs. {{ $money_transfer_response['money']['FinalAmount'] }}</span></b></div>

                                  

                               </div>
                            </div>
                        </div>
                    </div>
               
               
              
                </div>
                <div class="row">
                    <div class="col-12 col-lg-12">
                        <div class="card">
                            <div class="card-body">
                            
                                <div class="table-responsive m-t-40">
                                        <table class="table display table-bordered table-striped no-wrap">
                                            <thead>
                                                <tr>
                                                    <th>SMART ID</th>
                                                    <th>BANK REF. ID</th>
                                                    <th>AMOUNT</th>
                                                    <th>STATUS</th>
                                                
                                                
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(isset($money_transfer_response['result']['fund_transfer_status']))
                                                    @foreach($money_transfer_response['result']['fund_transfer_status'] as $mt_key => $mt_value)

                                                    <tr>
                                                        <td>{{ $mt_value['order_no'] }}</td>
                                                        <td>{{ $mt_value['transaction_id'] }}</td>
                                                        <td>{{ $mt_value['transaction_amount'] }}</td>
                                                        <td>Success</td>
                                                    
                                                    
                                                    </tr>
                                                    @endforeach
                                                @else
                                                <tr>
                                                    <td>{{ $money_transfer_response['money']['order_no'] }}</td>
                                                    <td>{{ $money_transfer_response['money']['bank_transaction_id'] }}</td>
                                                    <td>{{ $money_transfer_response['money']['amount'] }}</td>
                                                    <td>{{ $money_transfer_response['money']['order_status'] }}</td>
                                                    
                                                
                                                
                                                </tr>
                                                @endif
                                            
                                            
                                            
                                            </tbody>
                                        </table>
                                </div>
                            </div>
                        </div>



                    </div>
                </div>

            </div>
        <!-- Surcharge Modal starts -->
        <div class="modal fade" id="surchargeModal">
            <div class="modal-dialog modal-sm">
            <div class="modal-content">
            
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Surcharge ? </h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                
                <!-- Modal body -->
                    <div class="modal-body">
                        <!-- <form action="{{-- route('preview_recipt') --}}" method="post"> -->
                            <!-- @csrf -->
                            <input type="hidden" name="order_id" id="order_id" value="{{ $data['order_id']}}">
                            <input type="hidden" id="web_url" value="{{ Config::get('constants.WEBSITE_BASE_URL') }}">
                            
                        <div class="form-group btn-group">
                            <button type="button" class="btn btn-warning" style="pointer-events:none"><i class="mdi mdi-currency-inr"></i></button>
                            <input type="text" name="subcharge" id="subcharge" class="form-control" placeholder="Enter here" >
                            <!-- <button type="button" class="btn btn-info"  id="btn-subcharge">Proceed</button> -->
                        
                            <button type="button" class="btn btn-info" onclick="showInvice()">Proceed</button>
                        </div>
                        <!-- </form> -->
                    </div>
            </div>
            </div>
        </div>
        <!-- Surcharge Modal ends -->

        <!-- Invoice Modal starts -->
        <div class="modal fade" id="invoiceModal" style="background:white">
            <div class="modal-dialog" style="max-width:80%">
            <div class="modal-content">
            
                <!-- Modal body -->
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-8">
                                <!-- <img class="mt-3" src="{{-- asset('template_assets/assets/images/logos/logo-text-flat.png') --}}" style="width:30%"> -->
                                <img src="{{ asset('template_assets/assets/images/login_big_sm_py.png') }}" style="width:22%">
                            </div>
                            <div class="col-4">
                                <table class="table table-sm invoice-table">
                                    <thead>
                                        <tr>
                                            <th colspan="2"class="text-center">INVOICE</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="font-sm">Shope Name  </td>
                                            <td class="font-sm text-right"><span id="user_shope_name" class="user_shope_name"></span></td>
                                        </tr>
                                        <tr>
                                            <td class="font-sm">Mobile. No.</td>
                                            <td class="label text-right font-sm text-right user_mobile_no" id="user_mobile_no" ></td>
                                        </tr>
                                        <tr>
                                            <td class="font-sm">Email</td>
                                            <td class="label text-right font-sm text-right user_email" id="user_email"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <table class="table table-sm invoice-table">
                                    <tbody>
                                        <tr>
                                            <td>Sender Mobile No.:</td>
                                            <td class="text-right label sender_mobile_number " id="sender_mobile_number" ></td>
                                        </tr>
                                        <tr>
                                            <td>Transfer Type:</td> 
                                            <td class="text-right label transaction_type" id="transaction_type" ></td>
                                        </tr>
                                        <tr>
                                            <td>Date:</td>
                                            <td class="text-right label transaction_date" id="transaction_date"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-6">
                                <table class="table table-sm invoice-table">
                                    <tbody>
                                        <tr>
                                            <td>Beneficiary Name:</td>
                                            <td class="text-right label recipient_name" id="recipient_name" ></td>
                                        </tr>
                                        <tr>
                                            <td>
                                        
                                                <!-- UPI Id: -->
                                                @if($data['operator_name'] == 'BHIM_UPI')
                                                UPI ID:
                                                @else
                                                Account No:
                                                @endif
                                            </td>
                                            <td class="text-right label bank_account_number_top" id="bank_account_number_top" ></td>
                                        </tr>
                                        @if($data['operator_name'] != 'BHIM_UPI')
                                        <tr id="ifsc_header">
                                            
                                            <td>IFSC Code:</td>
                                            <td class="text-right label ifsc" id="ifsc" ></td>
                                        </tr>
                                        @endif
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <table class="table table-sm invoice-table">
                                    <thead>
                                        <tr>
                                            <th>Sr. No.</th>
                                            <th id="acc_upi_header">
                                            @if($data['operator_name'] == 'BHIM_UPI')
                                                    UPI Id.
                                            @else  
                                                    Account No.
                                            @endif 
                                            </th>
                                            <th>Transaction ID</th>
                                            <th id="order_id_header">
                                                
                                            @if($data['operator_name'] == 'BHIM_UPI')
                                                    SMART ID
                                            @else
                                                    Ref. No
                                            @endif
                                            </th>
                                            <th>Status</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                
                                        <tbody id="same_group_id_row">
                                        
                                    
                                        </tbody>
                            
                                </table>
                            </div>
                        </div>

                        <div class="row">
                        <div class="col-8">
                            <div class="btn-group mt-5 btn-print">
                                <button type="button" onclick="printRecipt()"   class="btn btn-warning btn-sm"><i class="mdi mdi-printer"></i> Print Invoice </button>
                                <button type="button"  class="btn btn-light btn-sm" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                            <div class="col-4">
                                <table class="table table-sm invoice-table table-bordered">
                                
                                    <tbody>
                                        <tr>
                                            <td>Basic Amount :</td>
                                            <td class="label"><i class="mdi mdi-currency-inr"></i> <span id="basic_amt" class="basic_amt" ></span></td>
                                        </tr>
                                        <tr>
                                            <td>Surcharge:</td>
                                            <td class="label"><span> <i class="mdi mdi-currency-inr"></i> <span id="surCharge" class="surCharge"></span></td>
                                        </tr>
                                        
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th class="label text-success">Total:</th>
                                            <th class="label text-success"><i class="mdi mdi-currency-inr"></i> <span id="total_amount" class="total_amount" ></span></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                <!-- Modal body ends-->
                <div class="row">
                    <div class="col-12 text-center">
                        <span class="label" style="font-size:10px">[Thank You for using Smart Pay]</span>
                    </div>
                </div>
        </div>
        <!-- Invoice Modal ends -->
<script src="{{ asset('dist\service_type\js\custom_moneyTrans.js') }}"></script>
@endsection

