 @extends('layouts.full_new')
<!-- This Page CSS -->
<link rel="stylesheet" type="text/css" href="{{ asset('template_new/assets/libs/select2/dist/css/select2.min.css') }}">
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" ></script>
@section('page_content')
 <div class="page-breadcrumb border-bottom" style="">
                <div class="row">
                    <div class="col-lg-3 col-md-4 col-xs-12 justify-content-start d-flex align-items-center">
                        <h5 class="font-medium text-uppercase mb-0">ADD BENEFICIARY</h5>
                    </div>
                    <div class="col-lg-9 col-md-8 col-xs-12 d-flex justify-content-start justify-content-md-end align-self-center">
                        <nav aria-label="breadcrumb" class="mt-2">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item"><a href="">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Money Transfer</li>
                            </ol>
                        </nav>&nbsp;&nbsp;&nbsp;
                       
                      
                    </div>
                </div>
            </div>
            
      <div class="row" style="margin-left:20px;padding:15px;padding-bottom:140px !important;">
            
                    <div class="col-12" style="margin-top:10px;">
                        <form action="{{ route('get_dmt_sender_details') }}" method="post" id="saveBeneficiaryFrom" autocomplete="off" >
                                    @csrf
                                 
                                    <input type="hidden" name="sender_mobile_number" id="sender_mobile_number"  class="form-control" value="{{ $request->sender_mobile_no }}">
                                     <button type="submit" class="btn btn-primary" id="back_senderDtls" style="margin-bottom: 5px"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button>
                           </form>
                           
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
                                <form action="{{ route('insert_beneficiary') }}" method="post" id="saveBeneficiaryFrom" autocomplete="off" >
                                    @csrf
                                  
                                    <?php
                                        
                                    ?>
                                   
   
                                    <input type="hidden" name="sender_mobile_no" id="sender_mobile_no"  class="form-control" value="{{ $request->sender_mobile_no }}">
                           
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
                                            <label class="card-title" for="beneficiary_mobile"> MOBILE NUMBER:</label>
                                        </div>
                                        <div class="col-md-4">
                                        
                                                <input type="text" name="beneficiary_mobile" id="beneficiary_mobile"  class="form-control" value="{{ $request->sender_mobile_no }}" placeholder="Enter Mobile" required readonly>
                                                <span id="message"></span>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="card-title" for="beneficiary_acc_no">ACCOUNT NUMBER :</label>
                                               
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" name="beneficiary_acc_no" id="beneficiary_acc_no" class="form-control" value="{{ (isset($request->beneficiary_acc_no))?  $request->beneficiary_acc_no : '' }}"  placeholder="Enter Account No."required>
                                                <span id="message"></span>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="card-title" for="select_bank_code">BANK NAME:</label>
                                        </div>
                                        <div class="col-md-4">
                                        
                                                

                                                <div class="fl-wrap fl-wrap-select">
                                                        <select class="select2 form-control custom-select" name="bank_code" onchange="selected_Bank();" id="select_bank_code" style="width:100%" >
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
                                            <label class="card-title" for="beneficiary_ifsc">IFSC CODE:</label>
                                        </div>
                                        <div class="col-md-4">
                                           
                                                <input type="text" name="beneficiary_ifsc" id="beneficiary_ifsc" class="form-control"  placeholder="Enter IFSC" required>
                                                <span id="message"></span>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="col-md-12 col-sm-12 " style=";margin-left:586px;">
                                              <button type="button" id="verifybank" class="btn btn-primary btn-lg " style="width:150px;margin-top:38px;background:#35a753;" onclick="verifybankaccount();">Verify</button>
                                             <button type="submit" class="btn success-grad btn-lg" id="add_bnfcry" style="width:150px;margin-top:38px">Submit</button>
                                            
                                    </div>
                                    
                                </form>
                                <script>
                                function selected_Bank() {
                                   var bankcode = $('#select_bank_code').val();
                                  $.ajax({
                                            url: "{{ url('getifsc') }}",
                                            method: 'get',
                                            data: {
                                                         bankcode: bankcode
                                                        
                                                },
                                            success: function(result){
                                $('#beneficiary_ifsc').val(result);
                               
                                             
                            }});
                                }
                                </script>
                               
     </div>
                    </div>
                </div>        
                <div style="height:430px;"></div>
           
            <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
           <script>
               function isNumber(evt) {
                        evt = (evt) ? evt : window.event;
                        var charCode = (evt.which) ? evt.which : evt.keyCode;
                        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                            return false;
                        }
                        return true;
                    }
                    
                function verifybankaccount()
                {
                    var bankaccountno=$('#beneficiary_acc_no').val();
                    var bankifsc=$('#beneficiary_ifsc').val();
                    var bankname=$('#select_bank_code').val();
                    var name=$('#beneficiary_name').val();
                    var phone=$('#beneficiary_mobile').val();
                    
                    $.ajax({
                                url: "{{ url('verifybankaccount') }}",
                                method: 'get',
                                data: {
                                             bankaccountno: bankaccountno,
                                             bankifsc: bankifsc,
                                             bankname: bankname,
                                             name: name,
                                             phone: phone
                                            
                                    },
                                success: function(result){
                                alert(result);         
                                if(result== "Bank Account details verified successfully")
                                {
                                    $('#verifybank').hide();
                                }
                                else
                                {
                                    
                                }
                                //console.log(result);
                                // $("#response").empty();
                                // $('#displaynow').hide();
                                // $("#disabledthen").attr("disabled", true)
                                // $('#displaythen').show();
                                // document.getElementById('displaythen').style.display = "block";
                                // // RESULT
                                              
                                // $.each(result,function(index,value,username){
                                //     var splitted = value.split("-"); 
                                //     $("#response").append('<option value="'+splitted[0]+'" name="days[]">'+splitted[1]+'</option>');
                                // });
                                             
                            }});
                    
                    
                }
           </script>

    <!-- ============================================================== -->
    <!-- END Add Beneficiary -->
    <!-- ============================================================== -->
<script src="{{ asset('template_new\assets\libs\select2\dist\js\select2.full.min.js') }}"></script>
<script src="{{ asset('template_new\assets\libs\select2\dist\js\select2.min.js') }}"></script>
<script src="{{ asset('template_new\dist\js\pages\forms\select2\select2.init.js') }}"></script>

    
@endsection

