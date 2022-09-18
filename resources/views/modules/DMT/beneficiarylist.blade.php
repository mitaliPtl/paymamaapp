@extends('layouts.full_new')
<!-- This Page CSS -->
<link rel="stylesheet" type="text/css" href="{{ asset('template_new/assets/libs/select2/dist/css/select2.min.css') }}">
@section('page_content')

      <div class="row" style="margin-left:20px;padding:15px;padding-bottom:380px !important;">
          
                    <div class="col-12">
                        <div class="card material-card">
                        <div class="card-body">
                                  @if(isset($data['error']) )

                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>FAILED</strong> {{ $data['error'] }} .
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                      @if(isset($data['success']) )

                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong> </strong> {{ $data['success'] }} .
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                           
                            <div class="table-responsive m-t-40">
                                 <h2 class="font-medium text-uppercase mb-0">SENDER DETAILS</h2>
                                 <hr style="color:red !important;background-color:red !important;width:20%;font-weight:bold;height:3px;float:left;">
                                <table id="config-table" class="table display table-bordered table-striped no-wrap">
                                    <tr>
                                    {{-- json_encode($sender_dtls) --}}
                                    
                                            <td><b>NAME : {{ ( isset($sender_dtls['first_name']) ) ? $sender_dtls['first_name']." ".$sender_dtls['last_name'] : '25' }}</b></td>
                                             <td><b>MOBILE NUMBER : {{ ( isset($sender_dtls['sender_mobile_number']) ) ? $sender_dtls['sender_mobile_number'] : '' }}</b></td>
                                            <td>
                                                <b>AVAIL LIMIT : 
                                               
                                               
                                                {{ ( isset($sender_dtls['available_limit']) ) ? $sender_dtls['available_limit'] : '' }}
                                                
                                                </b>
                                            </td>
                                            
                                        <?php
                                                    // print_r($sender_dtls['result']);
                                        ?>
                                       
                                           
                                            <td>
                                                <b>USED LIMIT : 
                                               
                                                {{ ( isset($sender_dtls['used_limit']) ) ? $sender_dtls['used_limit'] : '' }}
                                              
                                                </b>
                                            </td>
                                        
                                            
                                        </tr>
                                </table>
                            </div>
                        </div>
                    
                   </div>
                    <div class="card material-card">
                        <div class="card-body">
                    <form action="add_dmt_beneficiary" method="post">
                         @csrf
                          <h2 class="font-medium text-uppercase mb-0">Receiver Accounts</h2>
                                 <hr style="color:red !important;background-color:red !important;width:20%;font-weight:bold;height:3px;float:left;">
                    <input type="hidden" name="sender_mobile_no" value="{{ $sender_dtls['sender_mobile_number'] }}">
                   
                    <button type="submit" style="float:right;margin-bottom: 5px;background:#35a753;margin-top:-28px;" class="btn btn-primary btn-lg font-22" >Add Beneficiary</button>
                  
                    </form>
                       <div class="table-responsive m-t-40">
                          
                            
                          
                           <br>
                      <link href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css"
        href="{{ asset('template_assets/assets/extra-libs/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
                               <table id="example" class="table table-striped table-bordered" style="width:100%">

                                    <thead>
                                        <tr>
                                            <td><b>SR. NO</b></td>
                                            <td><b>ACCOUNT HOLDER NAME</b></td>
                                             <td><b>ACCOUNT NUMBER</b></td>
                                            <td><b>BANK NAME</b></td>
                                            <td><b>IFSC CODE</b></td>
                                            <!--<td><b>MOBILE NUMBER</b></td>-->
                                           
                                            <td><b>ACTION</b></td>
                                        </tr>

                                    </thead>
                                
                                <tbody>
                                   
                                        @if(count($sender_receipient_list)>0)

                                            @foreach($sender_receipient_list as $recip_key => $recip_value)
                                            <tr>
                                                <td>
                                                    <!-- <input type="radio" name="benificiary" value="{{-- $recip_value['recipient_id'] --}}">  -->
                                                    {{ $recip_key+1 }} 
                                                    {{-- json_encode($recip_value) --}}
                                                </td>
                                                <td  hidden="hidden" class="category-id">{{ $recip_value['recipient_id'] }}</td>
                                                <td><p onclick="startTransaction( {{ $recip_value['recipient_id'] }} )" style="cursor: pointer;">{{ $recip_value['recipient_name'] }}</p></td>
                                                  <td>{{ $recip_value['bank_account_number'] }}</td>
                                                <td>{{ $recip_value['bank_name'] }}  {{-- $recip_value['is_verified'] --}}</td>
                                                <td>{{ $recip_value['ifsc'] }}</td>
                                                
                                                <!--<td>{{ $recip_value['recipient_mobile_number'] }}</td>-->
                                               

                                            

                                                <td>

                                                                @if($recip_value['is_verified'] == 'Y')
                                                                    <img src="{{ asset('template_new/img/verify_ic.png') }}" alt="verified" style="width: 25px;">
                                                                @else
                                                                <img src="{{ asset('template_new/img/pending_ic.png') }}" alt="pending" style="width: 40px;">

                                                                @endif
                                                            &nbsp;
                                                             <a href="transferdmtmoney/{{ $recip_value['recipient_id'] }}/{{ $sender_dtls['id'] }}"><button type="button" class="btn trasfer-btn btn-success"><i   class="ti-share"></i></button></a>
                                                             &nbsp;
                                                    <!--        <a type="button" class=""  id="czypay_acc" onclick="createFundAcc( {{ $recip_value['recipient_id'] }} )"><img src="{{ asset('template_new/img/razorpay.png') }}" ></a>-->
                                                          
                                                    
                                                    <!-- <a href="javascript:void(0)" class=" pr-2" data-toggle="tooltip" title="" data-original-title="Edit">
                                                    <!--<i class="ti-marker-alt"></i></a>  -->
                                                    
                                                    <!--    @if(!empty($recip_value['razorpay_fund_acc_id']) )-->
                                                    <!--    <button type="button" class="btn trasfer-btn btn-success" onclick="startTransaction( {{ $recip_value['recipient_id'] }} )" ><i   class="ti-share"></i></button>-->
                                                    <!--    @endif-->
                                                    
                                                    <!-- <button type="button" id="delete-btn" class="btn btn-danger" value="{{-- $recip_value['recipient_id'] --}}" onclick="deleteFunction({{-- $recip_value['recipient_id'] --}})" data-original-title="Delete"><i class="ti-trash"></i></a> -->
                                                    <!--<div class="span4 proj-div" data-toggle="modal" data-target="#GSCCModal">Clickable content, graphics, whatever</div>-->
                                                    <button type="button" id="" class="delete-btn-new btn btn-danger" data-original-title="Delete" data-toggle="modal" data-target=".GSCCModal"><i class="fa fa-delete"></i>Delete</button>
                                                    
                                                        </div>
                                                </td>

                                            
                                            </tr>
                                        
                                            @endforeach

                                        @endif
                                         <script>
                               $(document).ready(function () {
                                $('#example').DataTable();
                            });
                           </script>
                                                      <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/f2c75b7247b/integration/bootstrap/3/dataTables.bootstrap.js"></script>
                                    <script>
                                    
                                        $('document').ready(function() {
                                            $('.delete-btn-new').click(function() {
                                                $tr = $(this).closest('tr');
                                                var values=$('.category-id', $tr).text();
                                                var iNum = parseInt(values);
                                                 $("#hiddenid").val(iNum);
                                               
                                               
                                            });
                                        });
                                    </script>
                                    
                                    </tbody>
                                    
                                    

                                </table>
                                <div id="" class="GSCCModal modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                         <div class="modal-dialog">
                                                            <div class="modal-content">
                                                              <div class="modal-header">
                                                                <center>
                                                                   <h4 class="modal-title" id="myModalLabel">Enter MPIN to Delete</h4>
                                                                </center>
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;  </button>
                                                               
                                                              </div>
                                                              <div class="modal-body">
                                                                <form action="deletedmtbeneficiary" method="post">
                                                                    @csrf
                                                                    <div class="form-group">
                                                                        <label>Enter Mpin</label>
                                                                        <input type="text" class="form-control" name="mpin"/>
                                                                        <input type="hidden" id="hiddenid" name="beneficiaryid" style="color:black;">
                                                                        <input type="hidden" value="{{$sender_dtls['sender_mobile_number']}}" name="senderno">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <button type="submit" class="btn btn-primary">Submit</button>
                                                                    </div>
                                                                </form>
                                                              </div>
                                                              <div class="modal-footer">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                              
                                                              </div>
                                                            </div>
                                                          </div>
                            </div>
                    </div>
                </div>        
                </div>
                <div style="height:430px;"></div>
              
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
    <!-- END Add Beneficiary -->
    <!-- ============================================================== -->
<script src="{{ asset('dist\service_type\js\custom_moneyTrans.js') }}"></script>
<script src="{{ asset('template_new\assets\libs\select2\dist\js\select2.full.min.js') }}"></script>
<script src="{{ asset('template_new\assets\libs\select2\dist\js\select2.min.js') }}"></script>
<script src="{{ asset('template_new\dist\js\pages\forms\select2\select2.init.js') }}"></script>

    
@endsection


