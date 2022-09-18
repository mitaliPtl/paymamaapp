@extends('layouts.full_new')
<!-- This Page CSS -->
<link rel="stylesheet" type="text/css" href="{{ asset('template_new/assets/libs/select2/dist/css/select2.min.css') }}">
@section('page_content')
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
      <div class="row" style="margin-left:20px;padding:15px;padding-bottom:380px !important;">
          
                    <div class="col-12">
                       <div class="card material-card">
                    <div class="card-body">
                        <h5 class="card-title mt-2 mb-2">Do Transaction</h5>
                        {{-- json_encode($receipientDtls) --}}
                      <form action="{{ route('DoDmtTransaction') }}" method="post" id="">
                        <!--<form method="post" action="DoDmtTransaction"  autocomplete="off">-->
                            @csrf
                            <div class="table-responsive">
                                <table class="table table-bordered" id="tranfer_table">
                                 
                                    
                                    
                                    <input type="hidden" id="cfree_beneficiaryid" name="cfree_beneficiaryid" value="{{ $receipientDtls->cfree_beneficiaryid}}">
                                    <input type="hidden" id="benificiary" name="benificiary" value="{{ $receipientDtls->recipient_id }}">
                                    <input type="hidden" id="benificiary" name="account_no" value="{{ $receipientDtls->bank_account_number }}">
                                    <input type="hidden" id="benificiary" name="bank_name" value="{{ $receipientDtls->bank_name }}">
                                    <input type="hidden" id="benificiary" name="account_holder_name" value="{{ $receipientDtls->recipient_name }}">
                                    <input type="hidden" id="benificiary" name="ifsc_code" value="{{ $receipientDtls->ifsc }}">
                                    
                                    <input type="hidden" id="benificiary" name="sender_mobile_number" value="{{ $receipientDtls->sender_mobile_number }}">
                                    <input type="hidden" name="mobile_no" id="sender_mobile">
                                    <input type="hidden" id="requestBody" value="">
                                    <input type="hidden" name="verify_bank_api" id="VERIFY_BANK_API" value="">
                                    
                                    
                                    <input type="hidden" name="isconfirmed" name="isconfirmed" value="0">
                                 
                                    <input id="trans_type" type="hidden" name="trans_type" value="UPI">
                                    
                                    <!--<input id="trans_type" type="hidden" name="trans_type" value="IMPS">-->
                                 
                                    <tbody>
                                        <tr>
                                            <th scope="row">BENEFICIARY NAME</th>
                                            <td  id="verified_recip_name">{{ $receipientDtls->recipient_name }}</td>
                                          
                                        </tr>
                                        <tr>
                                            <th scope="row">
                                          
                                                ACCOUNT NUMBER
                                           
                                            </th>
                                           <td>{{ $receipientDtls->bank_account_number }}</td>
                                          
                                        </tr>
                                        <tr>
                                            <th scope="row">
                                          
                                                BANK NAME
                                           
                                            </th>
                                           <td>{{ $receipientDtls->bank_name }}</td>
                                          
                                        </tr>
                                        <tr>
                                            <th scope="row">
                                          
                                                IFSC CODE
                                           
                                            </th>
                                           <td>{{ $receipientDtls->ifsc }}</td>
                                          
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
                          
                            <button type="submit" class="btn-success btn-lg btn success-grad " id="process_transfer">Transfer</button>
                            <!-- <button type="button" class="btn-success btn-lg btn success-grad " id="process_transfer" onclick="this.style.display='none'">Transfer</button> -->
                            <!-- <button type="button" class="btn-success btn-lg btn success-grad " id="process_transfer_test" >Transfer Test</button> -->
                            


                            </div>
                            


                        </form>
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


