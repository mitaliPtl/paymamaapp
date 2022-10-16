@extends('layouts.full_new')
<!-- This Page CSS -->
<link rel="stylesheet" type="text/css" href="{{ asset('template_new/assets/libs/select2/dist/css/select2.min.css') }}">
@section('page_content')
@if(@$data['page_name'] == 'customer_mobile')
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="page-breadcrumb border-bottom">
                <div class="row">
                    <div class="col-lg-3 col-md-4 col-xs-12 justify-content-start d-flex align-items-center">
                        <h5 class="font-medium text-uppercase mb-0">GET CUSTOMER INFO</h5>
                    </div>
                    <!-- <div class="col-lg-9 col-md-8 col-xs-12 d-flex justify-content-start justify-content-md-end align-self-center">
                        <nav aria-label="breadcrumb" class="mt-2">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Money Trasnfer</li>
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
            <div class="page-content container-fluid">
            @if(isset($data))
                @if(isset($data['success']) )
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>SUCCESS</strong> {{ $data['success'] }} .
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @elseif(isset($data['error']) )

                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>FAILED</strong> {{ $data['error'] }} .
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif
            @endif
                <div class="row">
                    <div class="col-12">
                        <div class="material-card card">
                            <div class="card-body">
                                <form action="{{ route('pg-wallet-get-sender-details') }}" method="post" id="senderMobForm"  autocomplete="off" >
                                    @csrf
                                    <input type="hidden" name="operator_name" value="{{ $data['operator_name'] }}">
                                    <div class="row">
                                        
                                        <div class="col-md-3 col-sm-12">
                                            <div class="form-group">
                                                <label class="card-title blue-font" for="sender_mobile_number "><i class="mdi mdi-cellphone"></i> Sender Mobile:</label>
                                                <input type="text" name="sender_mobile_number" id="sender_mobile_number" onkeypress="return isNumber(event)" class="form-control" minlength="10" maxlength="10"  placeholder="Enter Mobile No">
                                                <span id="message"></span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-2 col-sm-12">
                                        <button type="submit" class="btn success-grad btn-lg" id="sender-mob-sb-btn" style="margin-top:38px; height: calc(2.1rem + .75rem + 2px);">Search</button>
                                            
                                        </div>
                                
                                
                                    </div>
                                </form>
                               
                            </div>
                        </div>
                    </div>
                </div>        
            </div>
              
           <script>
               function isNumber(evt) {
                        evt = (evt) ? evt : window.event;
                        var charCode = (evt.which) ? evt.which : evt.keyCode;
                        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                            return false;
                        }
                        return true;
                    }
           </script>
            <!-- ============================================================== -->


@elseif(@$data['page_name'] == 'sender_details')


    <!-- ============================================================== -->
    <!-- START Sender Details (If mobile no is registered) -->
    <!-- ============================================================== -->

            <div class="page-breadcrumb border-bottom">
                <div class="row">
                    <div class="col-lg-3 col-md-4 col-xs-12 justify-content-start d-flex align-items-center">
                        <h5 class="font-medium text-uppercase mb-0" id="div_heading">Sender Info</h5>
                    </div>
                    <!-- <div class="col-lg-9 col-md-8 col-xs-12 d-flex justify-content-start justify-content-md-end align-self-center">
                        <nav aria-label="breadcrumb" class="mt-2">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Sender Info</li>
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

                    


            <div class="page-content container-fluid"  id="sender_details">
                @if(isset($data))
                    @if(isset($data['success']) )
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>SUCCESS</strong> {{ $data['success'] }} .
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @elseif(isset($data['error']) )

                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>FAILED</strong> {{ $data['error'] }} .
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                @endif

                @if(Session::has('success_msg'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>SUCCESS</strong>  {{ Session::get('success_msg') }}.{{  Session::forget('success_msg') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @elseif(Session::has('error_msg')) 

                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>FAILED</strong>  {{ Session::get('error_msg') }}.
                        {{  Session::forget('error_msg') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
               
                    
               
                    <!-- ============================================================== -->

             
             
                   
                    <div class="card material-card">
                        <div class="card-body">
                            
                            <div class="table-responsive m-t-40">
                                <table id="config-table" class="table display table-bordered table-striped no-wrap">
                                    <tr>
                                    {{-- json_encode($sender_dtls) --}}
                                    
                                            <td><b>NAME : {{ ( isset($sender_dtls['result']['sender_name']) ) ? $sender_dtls['result']['sender_name'] : '25' }}</b></td>
                                            <td>
                                                <b>AVAIL LIMIT : 
                                                @if($data['operator_name']  == 'BHIM_UPI')
                                                {{ ( isset($sender_dtls['result']['Upi_available_limit']) ) ? $sender_dtls['result']['Upi_available_limit'] : '' }}
                                                @elseif($data['operator_name']  == 'SMART_MONEY')
                                                {{ ( isset($sender_dtls['result']['available_limit']) ) ? $sender_dtls['result']['available_limit'] : '' }}
                                                @elseif($data['operator_name']  == 'CRAZY_MONEY')
                                                {{ ( isset($sender_dtls['result']['available_limit_crazy']) ) ? $sender_dtls['result']['available_limit_crazy'] : '' }}
                                                @endif
                                                </b>
                                            </td>
                                            
                                        <?php
                                                    // print_r($sender_dtls['result']);
                                        ?>
                                        </tr>
                                        <tr>
                                            <td><b>MOBILE No : {{ ( isset($sender_dtls['result']['sender_mobile_number']) ) ? $sender_dtls['result']['sender_mobile_number'] : '' }}</b></td>
                                            <td>
                                                <b>USED LIMIT : 
                                                @if($data['operator_name']  == 'BHIM_UPI')
                                                {{ ( isset($sender_dtls['result']['Upi_used_limit']) ) ? $sender_dtls['result']['Upi_used_limit'] : '' }}
                                                @elseif($data['operator_name']  == 'SMART_MONEY')
                                                {{ ( isset($sender_dtls['result']['used_limit']) ) ? $sender_dtls['result']['used_limit'] : '' }}
                                                @elseif($data['operator_name']  == 'CRAZY_MONEY')
                                                {{ ( isset($sender_dtls['result']['used_limit_crazy']) ) ? $sender_dtls['result']['used_limit_crazy'] : '' }}
                                                @endif
                                                </b>
                                            </td>
                                        
                                            
                                        </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                <div class="card material-card">
                    <div class="card-body">
                        <h5 class="card-title mt-2">Receiver Accounts</h5>
                        {{-- json_encode($sender_receipient_list['result']) --}}
                        <!-- <form id="transferMoneyForm" method="post" action="{{-- route('add_beneficiary') --}}"> -->
                        <!-- @csrf -->
                            <input type="hidden" name="mobile_no" value="{{  $data['mobile_no'] }}"  id="mobile_no">
                            <input type="hidden" name="operator_name" value="{{  $data['operator_name'] }}" id="operator_name">
                            <input type="hidden" name="FUNDACC_API" value="{{ Config::get('constants.MONEY_TRANSFER.CREATE_RAZOR_FUND_ACCOUNT') }}" id="FUNDACC_API">
                        <!-- <button type="submit" style="float:right;" class="btn-primary btn-sm btn">Add Beneficiary</button> -->
                        <!-- </form> -->
                        <button type="button" style="float:right;margin-bottom: 5px;" class="btn-primary btn add-beneficiary-btn success-grad font-22" >Add Beneficiary</button>

                                                    <form id="moneytransferMoneyForm" method="post" action="{{ route('transfer_money') }}"  autocomplete="off" >
                                                        @csrf
                                                         <input type="hidden" name="mobile_no" value="{{  $data['mobile_no'] }}" id="mobile_no">
                                                        <input type="hidden" name="operator_name" value="{{ $data['operator_name'] }}" id="operator_name">
                                                        <input type="hidden" name="benificiary" value="" id="benificiary_id">
                                                        
                                                    </form>
                        <!-- <form id="transferMoneyForm" method="post" action="{{-- route('transfer_money') --}}">
                                                        @csrf -->
                            <!-- <input type="hidden" name="mobile_no" value="{{--  $data['mobile_no'] --}}" id="mobile_no">
                            <input type="hidden" name="operator_name" value="{{--  $data['operator_name'] --}}" id="operator_name"> -->
                            <div class="table-responsive m-t-40">
                                <table id="config-table" class="table display table-bordered table-striped no-wrap ">

                                    <thead>
                                        <tr>
                                            <td><b>Sr</b></td>
                                            <td><b> NAME</b></td>
                                            
                                            @if( ($data['operator_name'] == 'SMART_MONEY') || ($data['operator_name'] == 'CRAZY_MONEY') )
                                            <td><b>BANK NAME</b></td>
                                            <td><b>IFSC CODE</b></td>
                                            <td><b>ACCOUNT NUMBER</b></td>
                                            @elseif( $data['operator_name'] == 'BHIM_UPI')
                                            <td><b>MOBILE NO.</b></td>
                                            <td><b>UPI ID</b></td>
                                            @endif
                                           
                                            <td><b>ACTION</b></td>
                                        </tr>

                                    </thead>
                                
                                <tbody>
                                <?php
                                //print_r($sender_receipient_list);
                                ?>
                                    @if(@$sender_receipient_list['status'] == 'true')
                                        @if(count($sender_receipient_list['result']['recipient_list'])>0)

                                            @foreach($sender_receipient_list['result']['recipient_list'] as $recip_key => $recip_value)
                                            <tr>
                                                <td >
                                                    <!-- <input type="radio" name="benificiary" value="{{-- $recip_value['recipient_id'] --}}">  -->
                                                    {{ $recip_key+1 }} 
                                                    {{-- json_encode($recip_value) --}}
                                                </td>
                                                <td  ><p onclick="startTransaction( {{ $recip_value['recipient_id'] }} )" style="cursor: pointer;">{{ $recip_value['recipient_name'] }}</p></td>
                                                @if( ( $data['operator_name'] == 'SMART_MONEY') || ($data['operator_name'] == 'CRAZY_MONEY') )
                                                <td>{{ $recip_value['bank_name'] }}  {{-- $recip_value['is_verified'] --}}</td>
                                                <td>{{ $recip_value['ifsc'] }}</td>
                                                @elseif( $data['operator_name'] == 'BHIM_UPI')
                                                <td>{{ $recip_value['recipient_mobile_number'] }}</td>
                                                @endif

                                                <td>{{ $recip_value['bank_account_number'] }}</td>

                                                <td>

                                                    @if( ($data['operator_name'] == 'SMART_MONEY') || ($data['operator_name'] == 'CRAZY_MONEY') )
                                                                @if($recip_value['is_verified'] == 'Y')
                                                                    <img src="{{ asset('template_new/img/verify_ic.png') }}" alt="verified" style="width: 25px;">
                                                                @else
                                                                <img src="{{ asset('template_new/img/pending_ic.png') }}" alt="pending" style="width: 40px;">

                                                                @endif
                                                            @if( (empty($recip_value['razorpay_fund_acc_id']) ) &&  ($data['operator_name'] == 'CRAZY_MONEY') )
                                                            <a type="button" class=""  id="czypay_acc" onclick="createFundAcc( {{ $recip_value['recipient_id'] }} )"><img src="{{ asset('template_new/img/razorpay.png') }}" ></a>
                                                            @endif
                                                    @endif
                                                    <!-- <a href="javascript:void(0)" class=" pr-2" data-toggle="tooltip" title="" data-original-title="Edit">
                                                    <i class="ti-marker-alt"></i></a>  -->
                                                    @if( $data['operator_name'] == 'CRAZY_MONEY' )
                                                        @if(!empty($recip_value['razorpay_fund_acc_id']) )
                                                        <button type="button" class="btn trasfer-btn btn-success" onclick="startTransaction( {{ $recip_value['recipient_id'] }} )" ><i   class="ti-share"></i></button>
                                                        @endif
                                                    @else
                                                   <button type="button" class="btn trasfer-btn btn-success" onclick="startTransaction( {{ $recip_value['recipient_id'] }} )" ><i   class="ti-share"></i></button>

                                                   @endif
                                                    <!-- <button type="button" id="delete-btn" class="btn btn-danger" value="{{-- $recip_value['recipient_id'] --}}" onclick="deleteFunction({{-- $recip_value['recipient_id'] --}})" data-original-title="Delete"><i class="ti-trash"></i></a> -->

                                                    <button type="button" id="delete-btn-new" class="btn btn-danger"  onclick="deletePopupFunction({{ $recip_value['recipient_id'] }})" data-original-title="Delete"><i class="ti-trash"></i></a>

                                                </td>

                                            
                                            </tr>
                                        
                                            @endforeach


                                        @endif
                                    @endif
                                    
                                    </tbody>
                                    
                                    

                                </table>
                            </div>
                        <!-- <button class="btn-success btn-md btn" type="submit">Do Transaction</button> -->
                        <!-- </form> -->
                    </div>
                </div>
                   
            </div>

                <!-- Modal -->
                <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <!-- <form action="{{-- route('get_sender_details') --}}" method="post" > -->
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Delete Beneficiary</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    
                        <!-- @csrf -->
                        <div class="modal-body">
                                    Are you sure?<br>
                                    <input type="hidden" name="sender_mobile_number" value="{{  $data['mobile_no'] }}">
                                    <input type="hidden" name="operator_name" value="{{  $data['operator_name'] }}">
                                    <input type="hidden" name="delete_beneficiary_id" id="delete_beneficiary_id" value="">
                                    <input type="hidden" name="action" id="action" value="delete">

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-danger" onclick="deleteBeneFunction()">Delete</button>
                        </div>
                        <!-- </form> -->
                    </div>
                </div>
                </div>
              
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->

    <!-- ============================================================== -->
    <!-- START Sender Details (If mobile no is registered) -->
    <!-- ============================================================== -->


    <!-- ============================================================== -->
    <!-- START Add Beneficiary  -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->



            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="page-breadcrumb border-bottom" style="display: none;">
                <div class="row">
                    <div class="col-lg-3 col-md-4 col-xs-12 justify-content-start d-flex align-items-center">
                        <h5 class="font-medium text-uppercase mb-0">ADD BENEFICIARY</h5>
                    </div>
                    <div class="col-lg-9 col-md-8 col-xs-12 d-flex justify-content-start justify-content-md-end align-self-center">
                        <nav aria-label="breadcrumb" class="mt-2">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item"><a href="">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Money Trasnfer</li>
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
            <div class="page-content container-fluid" id="add_beneficiary" style="display: none;">
              
                <div class="alert alert-success alert-dismissible fade show" role="alert" style="display:none" id="success_alert">
                        <strong>SUCCESS</strong> <span id="success_msg"> </span> .
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>

                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="display:none" id="error_alert">
                        <strong>FAILED!! </strong> <span id="error_msg"> </span> .
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
            <button type="button" class="btn btn-primary" id="back_senderDtls" style="margin-bottom: 5px"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button>
                @if( ($data['operator_name'] == 'SMART_MONEY') || ($data['operator_name'] == 'CRAZY_MONEY') )
                <input type="hidden" name="bank_ifsc" id="bank_ifsc" value="{{ json_encode($data['bank_ifsc']) }}">
                <input type="hidden" name="verify_bank_api" id="VERIFY_BANK_API" value="{{ Config::get('constants.MONEY_TRANSFER.VERIFY_BNK_AC') }}">
                <input type="hidden" name="verify_bank_api" id="ADD_BANK_ACCOUNT_API" value="{{ Config::get('constants.MONEY_TRANSFER.CREATE_RECEPIENT_API') }}">
                @elseif( $data['operator_name'] == 'BHIM_UPI')
                <input type="hidden" name="verify_upi_api" id="VERIFY_UPI_API" value="{{ Config::get('constants.MONEY_TRANSFER.VERIFY_UPI_AC') }}">
                <input type="hidden" name="add_upi_api" id="ADD_UPI_ACCOUNT_API" value="{{ Config::get('constants.MONEY_TRANSFER.CREATE_RECEPIENT_API_UPI') }}">

                @endif
                <div class="row">
                    <div class="col-12">
                        <div class="material-card card">
                            <div class="card-body">
                            
                                <form action="{{ route('add_beneficiary') }}" method="post" id="saveBeneficiaryFrom" autocomplete="off" >
                                    @csrf
                                    <input type="hidden" name="mobile_no" value="{{  $data['mobile_no']  }}">
                                    <input type="hidden" name="operator_name" value="{{  $data['operator_name'] }}">
                                    <input type="hidden" name="action" id="action" value="">
                                    <?php
                                            // if (isset($data['verify_accc_resp'])) {
                                            //     print_r($data['verify_accc_resp']);
                                            // }
                                            
                                    ?>
                                   


                           
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="card-title" for="beneficiary_name"> NAME:</label>
                                        </div>
                                        <div class="col-md-4">
                                                <input type="text" name="beneficiary_name" id="beneficiary_name"  class="form-control" value="{{ (isset($request->beneficiary_name))?  $request->beneficiary_name : '' }}"   placeholder="Enter Name"required>
                                                <span id="message"></span>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="card-title" for="beneficiary_mobile"> MOBILE:</label>
                                        </div>
                                        <div class="col-md-4">
                                        
                                                <input type="text" name="beneficiary_mobile" id="beneficiary_mobile"  class="form-control" value="{{ (isset($request->beneficiary_mobile))?  $request->beneficiary_mobile : '' }}"   placeholder="Enter Mobile"required>
                                                <span id="message"></span>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="card-title" for="beneficiary_acc_no">
                                            @if($data['operator_name']  == 'BHIM_UPI')
                                                UPI ID :
                                            @else
                                                ACCOUNT NO. :
                                            @endif
                                            </label>
                                               
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" name="beneficiary_acc_no" id="beneficiary_acc_no" class="form-control" value="{{ (isset($request->beneficiary_acc_no))?  $request->beneficiary_acc_no : '' }}"  placeholder="Enter {{ ($data['operator_name']  == 'BHIM_UPI') ? 'UPI ID' : ' Account No.' }}"required>
                                                <span id="message"></span>
                                        </div>
                                    </div>
                                    <hr>
                                   
                                    @if( ($data['operator_name'] == 'RAZORPAY') || ($data['operator_name'] == 'CASHFREE') )
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="card-title" for="select_bank_code">BANK:</label>
                                        </div>
                                        <div class="col-md-4">
                                        
                                                

                                                <div class="fl-wrap fl-wrap-select">
                                                        <!-- <select class="form-control fl-select" name="bank_code" onchange="selected_Bank(this);" id="select_bank_code"  > -->
                                                        <select class="select2 form-control custom-select" style="width: 100%; height:36px;" name="bank_code" onchange="selected_Bank(this);" id="select_bank_code" >
                                                            <option disabled selected>Select Bank</option>    
                                                            @foreach($data['bankList']['result']['bank_list'] as $i =>$bank)
                                                                @if(isset($request->bank_code) && ($request->bank_code == $bank['bank_code']) )
                                                                    <option value="{{ $bank['bank_code'] }}"  selected>{{ $bank['bank_name'] }} </option>
                                                                @else
                                                                <option value="{{ $bank['bank_code'] }}"  >{{ $bank['bank_name'] }} </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                        </div>
                                    </div>
                                    
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="card-title" for="beneficiary_ifsc">IFSC:</label>
                                        </div>
                                        <div class="col-md-4">
                                           
                                                <input type="text" name="beneficiary_ifsc" id="beneficiary_ifsc" class="form-control" value="{{ (isset($request->beneficiary_ifsc))?  $request->beneficiary_ifsc : '' }}"  placeholder="Enter IFSC"required>
                                                <span id="message"></span>
                                        </div>
                                    </div>
                                    <hr>
                                    @endif

                                    <div class="col-md-12 col-sm-12 " style="text-align:center;">
                                            @if( ($data['operator_name'] == 'SMART_MONEY') || ($data['operator_name'] == 'CRAZY_MONEY') )
                                            <button type="button" class="btn btn-success btn-lg " id="verify_bnk_acc_" style="margin-top:38px">Verify</button>
                                           
                                            <button type="button" class="btn success-grad btn-lg" id="add_bnfcry_" style="margin-top:38px">Submit</button>
                                            @elseif( $data['operator_name'] == 'BHIM_UPI')
                                            <button type="button" class="btn btn-success btn-lg " id="verify_upi_acc_" style="margin-top:38px">Verify</button>
                                            <button type="button" class="btn success-grad btn-lg" id="add_bnfcry_upi" style="margin-top:38px; display:none;" >Submit</button>

                                            @endif
                                    </div>
                                    
                                </form>
                               <input type="hidden" id="isverifiedupi" value="0">
                            </div>
                        </div>
                    </div>
                </div>          
            </div>



    <!-- ============================================================== -->
    <!-- END Add Beneficiary -->
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
                        
                       
                        <div class="col-12">&nbsp;</div>
                        
                    
                    <!-- </form> -->
                    </center>
                </div>
        </div>
        </div>
    </div>
    <!-- verification Modal ends -->

@elseif(@$data['page_name'] == 'sender_details_acc_no')


    <!-- ============================================================== -->
    <!-- START Sender Details (If mobile no is registered) -->
    <!-- ============================================================== -->

            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="page-breadcrumb border-bottom">
                <div class="row">
                    <div class="col-lg-3 col-md-4 col-xs-12 justify-content-start d-flex align-items-center">
                        <h5 class="font-medium text-uppercase mb-0">Sender Info</h5>
                    </div>
                    <!-- <div class="col-lg-9 col-md-8 col-xs-12 d-flex justify-content-start justify-content-md-end align-self-center">
                        <nav aria-label="breadcrumb" class="mt-2">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Sender Info</li>
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
            <div class="page-content container-fluid">
                @if(isset($data))
                    @if(isset($data['success']) )
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>SUCCESS </strong> {{ $data['success'] }} .
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @elseif(isset($data['error']) )

                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>FAILED </strong> {{ $data['error'] }} .
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                @endif
               <!-- ============================================================== -->
               <form action="" method="" id="getSenderByAcc" autocomplete="off" >
                   @csrf
                   <input type="hidden" name="operator_name" value="{{ $data['operator_name'] }}">
               </form>
               @foreach($sender_by_acc['result'] as $sender_key => $sender_value)
                <div class="card material-card">
                    <div class="card-body">
                    <button type="button" class="btn success-grad" style="float: right;" onclick="getSenderDtlsByAcc({{ $sender_value['sender_mobile_number'] }})"><i class="fa fa-share"></i></button>
                        
                        <div class="table-responsive m-t-40">
                            <table id="config-table" class="table display table-bordered table-striped no-wrap">
                                    <tr>
                                        <td onclick="getSenderDtlsByAcc({{ $sender_value['sender_mobile_number'] }})" style="cursor: pointer;">
                                            <b>NAME</b> : {{ ( isset($sender_value['sender_name']) ) ? $sender_value['sender_name'] : '' }}
                                        </td>
                                        <td>
                                            <b>AVAIL LIMIT</b> : 
                                            @if($data['operator_name']  == 'BHIM_UPI')
                                            {{ ( isset($sender_value['Upi_available_limit']) ) ? $sender_value['Upi_available_limit'] : '' }}
                                            @elseif ($data['operator_name'] == 'SMART_MONEY')
                                            {{ ( isset($sender_value['available_limit']) ) ? $sender_value['available_limit'] : '' }}
                                            @elseif ($data['operator_name'] == 'CRAZY_MONEY')
                                            {{ ( isset($sender_value['available_limit_crazy']) ) ? $sender_value['available_limit_crazy'] : '' }}
                                            @endif
                                        </td>
                                        
                                       <?php
                                                // print_r($sender_dtls['result']);
                                       ?>
                                    </tr>
                                    <tr>
                                        <td><b>MOBILE No.</b> : {{ ( isset($sender_value['sender_mobile_number']) ) ? $sender_value['sender_mobile_number'] : '' }}</td>
                                        <td>
                                            <b>USED LIMIT</b> : 
                                            @if($data['operator_name']  == 'BHIM_UPI')
                                            {{ ( isset($sender_value['Upi_used_limit']) ) ? $sender_value['Upi_used_limit'] : '' }}
                                            @elseif ($data['operator_name'] == 'SMART_MONEY')
                                            {{ ( isset($sender_value['available_limit']) ) ? $sender_value['available_limit'] : '' }}
                                            @elseif ($data['operator_name'] == 'CRAZY_MONEY')
                                            {{ ( isset($sender_value['used_limit_crazy']) ) ? $sender_value['used_limit_crazy'] : '' }}
                                            @endif
                                        </td>
                                       
                                        
                                    </tr>
                            </table>
                        </div>
                    </div>
                </div>
                @endforeach
                
               
              
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->

    <!-- ============================================================== -->
    <!-- START Sender Details (If mobile no is registered) -->
    <!-- ============================================================== -->

@elseif(@$data['page_name'] == 'add_beneficiary')
    <!-- ============================================================== -->
    <!-- START Add Beneficiary  -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="page-breadcrumb border-bottom">
                <div class="row">
                    <div class="col-lg-3 col-md-4 col-xs-12 justify-content-start d-flex align-items-center">
                        <h5 class="font-medium text-uppercase mb-0">ADD BENEFICIARY</h5>
                    </div>
                    <div class="col-lg-9 col-md-8 col-xs-12 d-flex justify-content-start justify-content-md-end align-self-center">
                        <nav aria-label="breadcrumb" class="mt-2">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item"><a href="">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Bank Trasnfer</li>
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
                @if(isset($data))
                    @if(isset($data['success']) )
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>SUCCESS</strong> {{ $data['success'] }} .
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @elseif(isset($data['error']) )

                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>FAILED</strong> {{ $data['error'] }} .
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                @endif

              <input type="hidden" name="bank_ifsc" id="bank_ifsc" value="{{ json_encode($data['bank_ifsc']) }}">
                <div class="row">
                    <div class="col-12">
                        <div class="material-card card">
                            <div class="card-body">
                            
                                <form action="{{ route('pg-wallet-add-beneficiary') }}" method="post" id="saveBeneficiaryFrom" autocomplete="off" >
                                    @csrf
                                    <input type="hidden" name="mobile_no" value="{{  $data['mobile_no']  }}">
                                    <input type="hidden" name="operator_name" value="{{  $data['operator_name'] }}">
                                    <input type="hidden" name="action" id="action" value="">
                                    <?php
                                            // if (isset($data['verify_accc_resp'])) {
                                            //     print_r($data['verify_accc_resp']);
                                            // }
                                            
                                    ?>
                                   


                           
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="card-title" for="beneficiary_name"> Name:</label>
                                        </div>
                                        <div class="col-md-4">
                                                <input type="text" name="beneficiary_name" id="beneficiary_name"  class="form-control" value="{{ (isset($request->beneficiary_name))?  $request->beneficiary_name : '' }}"   placeholder="Enter Name"required>
                                                <span id="message"></span>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="card-title" for="beneficiary_mobile"> Mobile:</label>
                                        </div>
                                        <div class="col-md-4">
                                        
                                                <input type="text" name="beneficiary_mobile" id="beneficiary_mobile"  class="form-control" value="{{ (isset($request->beneficiary_mobile))?  $request->beneficiary_mobile : '' }}"   placeholder="Enter Mobile"required>
                                                <span id="message"></span>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="card-title" for="beneficiary_acc_no">Account No. :</label>
                                               
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" name="beneficiary_acc_no" id="beneficiary_acc_no" class="form-control" value="{{ (isset($request->beneficiary_acc_no))?  $request->beneficiary_acc_no : '' }}"  placeholder="Enter Account No."required>
                                                <span id="message"></span>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="card-title" for="select_bank_code">Bank:</label>
                                        </div>
                                        <div class="col-md-4">
                                        
                                                

                                                <div class="fl-wrap fl-wrap-select">
                                                        <select class="form-control fl-select" name="bank_code" onchange="selected_Bank(this);" id="select_bank_code"  >
                                                            <option disabled selected>Select Bank</option>    
                                                            @foreach($data['bankList']['result']['bank_list'] as $i =>$bank)
                                                                @if(isset($request->bank_code) && ($request->bank_code == $bank['bank_code']) )
                                                                    <option value="{{ $bank['bank_code'] }}"  selected>{{ $bank['bank_name'] }} </option>
                                                                @else
                                                                <option value="{{ $bank['bank_code'] }}"  >{{ $bank['bank_name'] }} </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                        </div>
                                    </div>
                                    
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="card-title" for="beneficiary_ifsc">IFSC:</label>
                                        </div>
                                        <div class="col-md-4">
                                           
                                                <input type="text" name="beneficiary_ifsc" id="beneficiary_ifsc" class="form-control" value="{{ (isset($request->beneficiary_ifsc))?  $request->beneficiary_ifsc : '' }}"  placeholder="Enter IFSC"required>
                                                <span id="message"></span>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="col-md-12 col-sm-12 " style="text-align:center;">
                                            @if(isset($data['account_verified']) && ($data['account_verified'] == '0') )
                                            <button type="button" class="btn btn-primary btn-lg " id="verify_bnk_acc" style="margin-top:38px">Verify</button>
                                            @endif
                                            <button type="button" class="btn success-grad btn-lg" id="add_bnfcry" style="margin-top:38px">Submit</button>
                                            
                                    </div>
                                    
                                </form>
                               
                            </div>
                        </div>
                    </div>
                </div>          
            </div>



    <!-- ============================================================== -->
    <!-- END Add Beneficiary -->
    <!-- ============================================================== -->
    

@endif
<script src="{{ asset('dist\service_type\js\custom_moneyTrans.js') }}"></script>

<script src="{{ asset('template_new\assets\libs\select2\dist\js\select2.full.min.js') }}"></script>
    <script src="{{ asset('template_new\assets\libs\select2\dist\js\select2.min.js') }}"></script>
    <script src="{{ asset('template_new\dist\js\pages\forms\select2\select2.init.js') }}"></script>

    
@endsection


