@extends('layouts.full_new')

@section('page_content')
    @if($data['page_name'] == 'transfer_form')
    <style>
      .text-red{
            color : #cb1933;
        }
        .colon-algin{
            float:right;
        }
    </style>
        <!-- ============================================================== -->
        <!-- Start Money tranfer From -->
        <!-- ============================================================== -->

        <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
            <div class="page-breadcrumb border-bottom">
                <div class="row">
                    <div class="col-lg-3 col-md-4 col-xs-12 justify-content-start d-flex align-items-center">
                        <h5 class="font-medium text-uppercase mb-0">Do Transaction</h5>
                    </div>
                    <!-- <div class="col-lg-9 col-md-8 col-xs-12 d-flex justify-content-start justify-content-md-end align-self-center">
                        <nav aria-label="breadcrumb" class="mt-2">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Do Transaction</li>
                            </ol>
                        </nav>
                      
                    </div> -->
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="page-content container-fluid" id="do_transfer_page">
                
        @if(isset($money_transfer_response))
            @if($money_transfer_response['status'] == "true")
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>SUCCESS</strong> {{ $money_transfer_response['msg'] }} .
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @elseif($money_transfer_response['status'] == "false")

            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>FAILED</strong> {{ $money_transfer_response['msg'] }} .
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif
        @endif

            <div class="alert alert-dismissible fade show" role="alert" id="alert_block" style="display:none;">
                <strong id="alert_head">SUCCESS</strong><span id="alert_msg"> </span> .
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
           
                <div class="card material-card">
                    <div class="card-body">
                        <h5 class="card-title mt-2 mb-2">Do Transaction</h5>
                        {{-- json_encode($receipientDtls) --}}
                        <!-- <form action="{{ route('transfer_money') }}" method="post" id="doTransactionForm"> -->
                        <form  method="post" id="doTransactionForm"  autocomplete="off">
                            @csrf
                            <div class="table-responsive">
                                <table class="table table-bordered" id="tranfer_table">
                                    <input type="hidden" id="cfree_beneficiaryid" name="cfree_beneficiaryid" value="{{ $receipientDtls->cfree_beneficiaryid }}">
                                    <input type="hidden" id="benificiary" name="benificiary" value="{{ $receipientDtls->recipient_id }}">
                                    <input type="hidden" name="mobile_no" id="sender_mobile" value="{{  $data['mobile_no'] }}">
                                    <input type="hidden" name="operator_name" id="operator_name" value="{{  $data['operator_name'] }}">
                                    <input type="hidden" id="requestBody" value="{{  json_encode($data['requestBody']) }}">
                                    <input type="hidden" name="verify_bank_api" id="VERIFY_BANK_API" value="{{ Config::get('constants.MONEY_TRANSFER.VERIFY_BNK_AC') }}">
                                    <input type="hidden" name="isconfirmed" name="isconfirmed" value="0">
                                    @if($data['operator_name']  == 'BHIM_UPI')
                                    <input id="trans_type" type="hidden" name="trans_type" value="UPI">
                                    @else
                                    <input id="trans_type" type="hidden" name="trans_type" value="IMPS">
                                    @endif
                                    <tbody>
                                        <tr>
                                            <th scope="row">BENEFICIARY NAME</th>
                                            <td  id="verified_recip_name">{{ $receipientDtls->recipient_name }}</td>
                                          
                                        </tr>
                                        <tr>
                                            <th scope="row">
                                            @if($data['operator_name']  == 'BHIM_UPI')
                                                UPI ID
                                            @else
                                                ACCOUNT NUMBER
                                            @endif
                                                
                                            </th>
                                           <td>{{ $receipientDtls->bank_account_number }}</td>
                                          
                                        </tr>
                                        @if( $data['operator_name'] != 'BHIM_UPI')
                                        <tr>
                                            <th scope="row">BANK NAME</th>
                                            <td>{{ $receipientDtls->bank_name }}</td>
                                            
                                        </tr>
                                        <tr>
                                            <th scope="row">IFSC CODE</th>
                                             <td>{{ $receipientDtls->ifsc }}</td>
                                            
                                        </tr>
                                        
                                          <tr>
                                            <th scope="row">TRANSACTION TYPE</th>
                                           <td>
                                          

                                            <button type="button" class="btn waves-effect waves-light btn-rounded success-grad" id="imps_id">IMPS</button> &nbsp;&nbsp;
                                            <button type="button" class="btn waves-effect waves-light btn-rounded btn-outline-success" id="neft_id">NEFT</button>

                               
                                        </td>
                                        @endif
                                        </tr>
                                         <tr>
                                            <th scope="row">AMOUNT</th>
                                            <td><input type="text" class="profile col-3" value="" name="transfer_amount" id="transfer_amount" onkeyup="rnum.value = toWords(transfer_amount.value);">
                                            <textarea rows="1" cols="40" name="rnum" style="border:none !important;"></textarea>
                                            </td>
                                            
                                        </tr>
                                       
                                            <script type="text/javascript">
// American Numbering System
var th = ['', 'Thousand', 'million', 'billion', 'trillion'];

var dg = ['Zero', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];

var tn = ['Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];

var tw = ['Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

function toWords(s) {
    s = s.toString();
    s = s.replace(/[\, ]/g, '');
    if (s != parseFloat(s)) return 'not a number';
    var x = s.indexOf('.');
    if (x == -1) x = s.length;
    if (x > 15) return 'too big';
    var n = s.split('');
    var str = '';
    var sk = 0;
    for (var i = 0; i < x; i++) {
        if ((x - i) % 3 == 2) {
            if (n[i] == '1') {
                str += tn[Number(n[i + 1])] + ' ';
                i++;
                sk = 1;
            } else if (n[i] != 0) {
                str += tw[n[i] - 2] + ' ';
                sk = 1;
            }
        } else if (n[i] != 0) {
            str += dg[n[i]] + ' ';
            if ((x - i) % 3 == 0) str += 'hundred ';
            sk = 1;
        }
        if ((x - i) % 3 == 1) {
            if (sk) str += th[(x - i - 1) / 3] + ' ';
            sk = 0;
        }
    }
    if (x != s.length) {
        var y = s.length;
        str += 'point ';
        for (var i = x + 1; i < y; i++) str += dg[n[i]] + ' ';
    }
    return str.replace(/\s+/g, ' ');

}
    </script>
                                        </tr>

                                        <tr>
                                            <th scope="row">MPIN</th>
                                            <td><input type="text" name="mpin" id="mpin"  class="profile col-3" value=""></td>

                                        </tr>
                                        
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-center">
                            @if( $data['operator_name'] != 'BHIM_UPI')
                            <button type="button" class="btn-success btn-lg btn " id="verify_transfer" >Verify</button>
                            @endif
                            <button type="button" class="btn-success btn-lg btn success-grad " id="process_transfer">Transfer</button>
                            <!-- <button type="button" class="btn-success btn-lg btn success-grad " id="process_transfer" onclick="this.style.display='none'">Transfer</button> -->
                            <!-- <button type="button" class="btn-success btn-lg btn success-grad " id="process_transfer_test" >Transfer Test</button> -->
                            


                            </div>
                            


                        </form>
                    </div>
                </div>
                   
                </div>
              
        <!-- ============================================================== -->
        <!-- END Money tranfer From -->
        <!-- ============================================================== -->

    <!-- verification Modal starts -->
    <div class="modal fade" id="verifyModel">
        <div class="modal-dialog modal-md">
        <div class="modal-content">
        
            <!-- Modal Header -->
            <div class="modal-header">
                <!-- <h4 class="modal-title">Verification </h4> -->
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal body -->
                <div class="modal-body">
                    <center>

                    <input type="hidden" id="web_url" value="{{ Config::get('constants.WEBSITE_BASE_URL') }}">
                    <!-- <form action="{{-- route('preview_recipt') --}}" method="post"> -->
                        <!-- @csrf -->
                        <div id="error_div" style="display:none">
                            <img src="{{ asset('template_new/img/pending_ic.png') }}" alt="pending" style="width: 95px;">
                            <h3 class="text-danger" id="modal_error_msg"></h3>
                        </div>
                        <div id="success_div" style="display:none">
                            
                            <img src="{{ asset('template_new/img/verify_ic.png') }}" alt="verified" style="width: 75px;">
                            <h3 class="text-success" >Name Verification is Success</h3>
                            <h3>Name : <b id="beni_model_name"></b></h3>
                            <h3>
                                            @if($data['operator_name']  == 'BHIM_UPI')
                                                UPI ID :
                                            @else
                                                Bank Acc. No. :
                                            @endif    
                                <b id="beni_model_acc_no"></b>
                            </h3>

                        </div>
                        
                       
                        
                    
                    <!-- </form> -->
                    </center>
                </div>
        </div>
        </div>
    </div>
    <!-- verification Modal ends -->

    <!-- Confirmation Modal starts -->
    <div class="modal fade" id="comfirmtionModal">
        <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
        
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Transaction Summary </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal body -->
                <div class="modal-body">
                    <center>
                        <div class="col-8">
                        <table id="confirmation-table" class="table" style="font-size: 18px">
                            
                            <tr>
                                <th class="text-red">Beneficiary Name <span class="colon-algin">:</span></th>
                                <td > {{ $receipientDtls->recipient_name }} </td>
                            </tr>
                            <tr>
                                <th class="text-red">
                                            @if($data['operator_name']  == 'BHIM_UPI')
                                                UPI ID <span class="colon-algin">:</span>
                                            @else
                                            Account Number <span class="colon-algin">:</span>
                                            @endif   
                                </th>
                                <td>{{ $receipientDtls->bank_account_number }}</td>
                            </tr>
                            {{-- @if($receipientDtls->recipient_mobile_number)
                            <tr>
                                <th class="text-red">Mobile <span class="colon-algin">:</span></th>
                                <td >{{ $receipientDtls->recispient_mobile_number }}</td>
                            </tr>
                            @endif --}}
                            @if($receipientDtls->bank_name)
                            <tr>
                                <th class="text-red">Bank Name <span class="colon-algin">:</span></th>
                                <td >{{ $receipientDtls->bank_name }}</td>
                            </tr>
                            @endif 
                            <tr>
                                <th class="text-red">Amount <span class="colon-algin">:</span></th>
                                <th id="trans_amt"></th>
                            </tr>
                            
                            <tr>
                                <th class="text-red">Mode <span class="colon-algin">:</span></th>
                                <td id="trans_mode">
                                    @if($data['operator_name']  == 'BHIM_UPI')
                                    UPI
                                    @endif   
                                </td>
                            </tr>
                        </table>
                        </div>
                        
                   
                    </center>
                  
                </div>
                <div class="modal-footer" style="justify-content: center;">
                    <button type="button" class="btn btn-primary success-grad btn-lg" id="confirmed_pay">Confirm</button>
                    <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal">Close</button>
                </div>
        </div>
        </div>
    </div>
    <!-- Confirmation Modal ends -->

    <!-- START SUCCESS PAGE -->

            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="page-content container-fluid" id = "success_page" style="display:none;">

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
                <div class="alert alert-dismissible fade show" role="alert" id="alert_block" style="display:none;">
                    <strong id="alert_head">SUCCESS</strong><span id="alert_msg"> </span> .
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
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

                                   <div class="col-12 col-lg-6"><b id="resp_total_amt">Rs. {{ $request->transfer_amount }}</b></div>
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

                                   <div class="col-12 col-lg-6" id="resp_trans_mode">
                                   @if($data['operator_name'] == 'BHIM_UPI')
                                    UPI
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
                            <div class="card-body" >
                              
                               <div class="row details" id="resp_charges">

                                  

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
                                            <tbody id="resp_trans_row">
                                            
                                            
                                            
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
                <div class="modal-dialog modal-md">
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
                                <input type="hidden" name="order_id" id="order_id" value="">
                                <input type="hidden" id="web_url" value="{{ Config::get('constants.WEBSITE_BASE_URL') }}">
                                
                            <div class="form-group btn-group">
                                <button type="button" class="btn btn-warning" style="pointer-events:none"><i class="mdi mdi-currency-inr"></i></button>
                                <input type="text" name="subcharge" id="subcharge" class="form-control" placeholder="Enter here" >
                                <!-- <button type="button" class="btn btn-info"  id="btn-subcharge">Proceed</button> -->
                            
                                <button type="button" class="btn btn-info success-grad" onclick="showInvice()" >Proceed</button>
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
    <!-- END SUCCESS PAGE -->



    @endif
<script src="{{ asset('dist\service_type\js\custom_moneyTrans.js') }}"></script>
@endsection