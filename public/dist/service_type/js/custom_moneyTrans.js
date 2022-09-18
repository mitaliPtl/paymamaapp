 // Onsubmit of Sender Mobile dtls form 
 $('#verify-otp-sb-btn').click(() => {
    console.log("submit");
   
    reqBody = {
       "sender_mobile_number": $('#mobile_no').val(),
       "otp": $('#reg_otp').val(),
       "token": $('#user_token').val(),
       "user_id": $('#user_id').val(),
       "role_id": $('#role_id').val(),
       "operatorID": $('#operator_id').val()
       };
var mok=$('#mobile_no').val();
var ope=$('#operator_id').val();
console.log(reqBody);
$.ajax({
   type: 'POST',
   url: $('#api').val(),
   data: JSON.stringify(reqBody),
   'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
   dataType: "json",
   success: function(response) {
       alert(response);
       console.log(response);
       if (response['status'] == "true")
       {
          //console.log('failedaa');
          // $('#error_msg').text('Invalid OTPss!! Retry')
           console.log('verified');
           //$('#error_msg').text('Invalid OTPwww!! Retry')
            window.location = "/money_transfer/";
        //   $("#verifyOTPForm").attr("action", "verify_otp");
        //   $( "#verifyOTPForm" ).submit();
        //   $("#getSenderByAcc").attr("action", "get_sender_details");
        //   $("#getSenderByAcc").attr("method", "post");
        //   $("#getSenderByAcc").append('<input type="hidden" name="sender_mobile_number" value="'+mok+'" />')
        //   $("#getSenderByAcc").append('<input type="hidden" name="ope" value="'+ope+'" />')
        //   $("#getSenderByAcc" ).submit();
           //console.log(mob_no);
        //   top.location.href="money_transfer";
           
           
           //added part
       } else if (response['status'] == "false") {
           // toastr.error(response['msg'], response['result'] ? response['result']['status'] : '');
         //  console.log('failed');
           $('#error_msgs').text('Invalid OTPs!! Retry')

       }
   
   },
   error: function(response) {
       // toastr.error(response['msg'], response['result'] ? response['result']['status'] : '');
       console.log(response);
       console.log('failed');
   }
});

});


$('#neft_id').click(() => {
   // $("#imps_id").removeClass("btn-success");
   $("#imps_id").removeClass("success-grad");
   $("#imps_id").addClass("btn-outline-success");

   $("#neft_id").removeClass("btn-outline-success");
   // $("#neft_id").addClass("btn-success");
   $("#neft_id").addClass("success-grad");

   $('#trans_type').val('NEFT');

});

$('#imps_id').click(() => {
   // $("#neft_id").removeClass("btn-success");
   $("#neft_id").removeClass("success-grad");
   $("#neft_id").addClass("btn-outline-success");

   $("#imps_id").removeClass("btn-outline-success");
   // $("#imps_id").addClass("btn-success");
   $("#imps_id").addClass("success-grad");

   $('#trans_type').val('IMPS');

});

function selected_Bank(bank_code) {
   ifsc_Array = JSON.parse($('#bank_ifsc').val());
   sled = $('select[name=bank_code] option').filter(':selected').val()
   card_value = ifsc_Array[sled]; //gets jquery object at index i
   $('#beneficiary_ifsc').val(card_value);
}


$('#verify_bnk_acc').click(() => {
   $('#action').val('verify_account');
  
   $( "#saveBeneficiaryFrom" ).submit();
});

$('#add_bnfcry').click(() => {
   $('#action').val('add_beneficiary');
   $( "#saveBeneficiaryFrom" ).submit();
});


// $('#delete-btn').click(() => {
//     alert("j");
//     console.log($(this).val());
//     $('#deleteModal').modal('show');
//     $('#delete_beneficiary_id').val($(this).val());
  
// });

function deleteFunction(recp_id){
   
   console.log(recp_id);
   $('#deleteModal').modal('show');
   $('#delete_beneficiary_id').val(recp_id);
}
function deletePopupFunction(recp_id){
   
   // console.log(recp_id);
   $('#deleteModal').modal('show');
   $('#delete_beneficiary_id').val(recp_id);
}

function startTransaction(recip_id){
  
   $('#benificiary_id').val(recip_id);
   $('#moneytransferMoneyForm').submit();
   
}

function getSenderDtlsByAcc(mob_no)
{
   $("#getSenderByAcc").attr("action", "get_sender_details");
   $("#getSenderByAcc").attr("method", "post");
   $("#getSenderByAcc").append('<input type="hidden" name="sender_mobile_number" value="'+mob_no+'" />')
   $("#getSenderByAcc" ).submit();
   console.log(mob_no);
}

function subCharges(){
   $('#surchargeModal').modal('show');
}

$('#btn-subcharge').click(() => {
   $('#surchargeModal').modal('hide');
   $('#invoiceModal').modal('show');

   reqBody = {
       "order_id": $('#order_id').val(),
       "subcharge": $('#subcharge').val(),
       };

       $.get( "/receipt_data/"+$('#order_id').val()+"/"+$('#subcharge').val(), function( data ) {
           // console.log( typeof data ); // string
           console.log( data );
           
           if (data.status == 'true') {
               $("#user_shope_name").text(data.result.user_details.store_name);
               $("#user_mobile_no").text(data.result.user_details.mobile);
               $("#user_email").text(data.result.user_details.email);

               $("#sender_mobile_number").text(data.result.beneficiary.sender_mobile_number);
               $("#transaction_type").text(data.result.beneficiary.transfer_type);
               $("#transaction_date").text(data.result.beneficiary.trans_date);

               $("#recipient_name").text(data.result.beneficiary.beneficiary_name);
               $("#bank_account_number_top").text(data.result.beneficiary.account_no);
               if (data.operator == 'money_transfer') {
                       $("#ifsc").text(data.result.beneficiary.ifsc);
               }else{
                   $("#ifsc_header").remove();
                   $("#acc_upi_header").text('UPI Id');
                   $("#order_id_header").text('SMART ID');
               }

               $("#basic_amt").text(data.result.basic_amount);
               $("#surCharge").text(data.result.subcharge);
               $("#total_amount").text(data.result.total_amount);
               trans_row = '';
               if (data.operator == 'money_transfer') {
                   sr = 1;
                   for (i = 0; i < data.result.transfer_records.length; i++) {
                       trans_row= trans_row + 
                                       '<tr>' +
                                           '<td>' + sr + '</td>' +
                                           '<td class="bank_account_number" id ="bank_account_number" >' + data.result.transfer_records[i].account_no + ' </td>' +
                                           '<td class="transaction_id" id ="transaction_id" >' + data.result.transfer_records[i].bank_transaction_id + '</td>' +
                                           '<td class="reference_number" id ="reference_number" >' + data.result.transfer_records[i].order_id + '</td>' +
                                           '<td class="text-success"><i class="fa fa-check-circle"></i> SUCCESS</td>' +
                                           '<td class="label"><i class="mdi mdi-currency-inr"></i> <span class="transaction_amount" id="transaction_amount">' + data.result.transfer_records[i].amount + '</span></td>' +
                                       '</tr>';
                       sr++;
                   }
               
               }else{
                   sr = 1;
                   for (i = 0; i < data.result.transfer_records.length; i++) {

                       trans_row= trans_row +    '<tr>'+
                                                   '<td>' + sr + '</td>'+
                                                   '<td id="upi_bank_account_number">' + data.result.transfer_records[i].account_no + '</td>'+
                                                   '<td id="upi_transaction_id">' + data.result.transfer_records[i].bank_transaction_id + '</td>'+
                                                   '<td id="upi_reference_number">' + data.result.transfer_records[i].order_id + '</td>'+
                                                   '<td class="text-success"><i class="fa fa-check-circle"></i> SUCCESS</td>'+
                                                   '<td class="label"><i class="mdi mdi-currency-inr"></i> <span class="transaction_amount" id="upi_transaction_amount">' + data.result.transfer_records[i].amount + '</span></td>'+                                                    
                                               '</tr>';
                               sr++;
                   }
                   // trans_row = 


                   
               }


               $("#same_group_id_row").html(trans_row);


           }


         });


  
});

function showInvice(){
   order_id =  $('#order_id').val();
   sbcharge= $('#subcharge').val();
   web_url = $('#web_url').val();
   link_open = web_url + 'invoice/' + order_id + '/' + sbcharge;
   $('#surchargeModal').modal('hide');

   window.open(link_open);
}


function printRecipt(){
   window.print();
   $('#invoiceModal').modal('hide');
}

// $('#process_transfer').click(function(event)  {
//     // $('#surchargeModal').modal('hide');
   
//     $('.preloader').css("display", "block");

// });

$('#process_transfer').click(function(event)  {
   if ($('transfer_amount').val() == '') {
       alert("Enter Amount");
       return false;
   }

   $('#comfirmtionModal').modal('show');
   $('#trans_amt').text( $('#transfer_amount').val());
   $('#trans_mode').text( $('#trans_type').val());
   // $('.preloader').css("display", "block");

});

$('#confirmed_pay').click(function(event)  {
    
   $('.preloader_blur').css("display", "block");
   $('#comfirmtionModal').modal('hide');
   // $( "#doTransactionForm" ).submit();
    if ($('#operator_name').val() != 'BHIM_UPI') {
        $('#resp_trans_mode').text($('#trans_type').val());
    }else{
        $('#resp_trans_mode').text('UPI');

    }
    $('#resp_total_amt').text($('#transfer_amount').val());

   requestBody =  JSON.parse($('#requestBody').val());
   reqBody = {
       // "sender_mobile_number": requestBody.sender_mobile_number,
       "token": requestBody.token,
        "user_id": requestBody.user_id,
        "role_id": requestBody.role_id,
       "mobile_no": requestBody.sender_mobile_number,
       "operator_name": $('#operator_name').val(),
       "benificiary": $('#benificiary').val(),
       "transfer_amount": $('#transfer_amount').val(),
       "trans_type": $('#trans_type').val(),
       "mpin": $('#mpin').val()
       };
console.log(reqBody);
      web_url =  $('#web_url').val();
   $.ajax({
       type: 'POST',
       url: web_url + 'api/do_money_transfer_api',
       data: $.param( { token: requestBody.token, user_id: requestBody.user_id, role_id: requestBody.role_id, mobile_no: requestBody.sender_mobile_number, operator_name: $('#operator_name').val(), benificiary: $('#benificiary').val(), transfer_amount: $('#transfer_amount').val(), trans_type: $('#trans_type').val(), mpin: $('#mpin').val() }),
       'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
       dataType: "json",
       success: function(response) {

           console.log(response);

           if (response['status'] == "true") {
           
               console.log('verified');
               if (response['groups'] == "yes")
               {
               resp_charges = ` <div class="col-12 col-lg-6">Transfer Amount <br>
                                <b>Rs. `+response.transaction_sums.amount+`</b></div>
                                <div class="col-12 col-lg-6">
                                CCF Charges<br>
                                <b>Rs.  `+response.transaction_sums.CCFcharges+`</b></div>
                            

                            

                                <div class="col-12 col-lg-6">Cashback<br>
                                <b>Rs.  `+response.transaction_sums.Cashback+`</b></div>
                                <div class="col-12 col-lg-6">TDS Amount<br>
                                    <b>Rs.  `+response.transaction_sums.TDSamount+`</b></div>

                                <div class="col-12 col-lg-6">Transfer Charges <br>
                                    <b>Rs.  `+response.transaction_sums.PayableCharge+`</b></div>
                                <div class="col-12 col-lg-6">Total<br>
                                <b><span class="success-amt">Rs.  `+response.transaction_sums.FinalAmount+`</span></b></div>`;
               }
               else
               {
               resp_charges = ` <div class="col-12 col-lg-6">Transfer Amount <br>
                                <b>Rs. `+response.money.amount+`</b></div>
                                <div class="col-12 col-lg-6">
                                CCF Charges<br>
                                <b>Rs.  `+response.money.CCFcharges+`</b></div>
                            

                            

                                <div class="col-12 col-lg-6">Cashback<br>
                                <b>Rs.  `+response.money.Cashback+`</b></div>
                                <div class="col-12 col-lg-6">TDS Amount<br>
                                    <b>Rs.  `+response.money.TDSamount+`</b></div>

                                <div class="col-12 col-lg-6">Transfer Charges <br>
                                    <b>Rs.  `+response.money.PayableCharge+`</b></div>
                                <div class="col-12 col-lg-6">Total<br>
                                <b><span class="success-amt">Rs.  `+response.money.FinalAmount+`</span></b></div>`;    
               }
                $("#resp_charges").html(resp_charges);
               
                resp_trans_row = ``;
                trans_id ='';
              //  if ("group_id" in response.money) {
                    if (response['groups'] == "yes")
                    {
                    
                    
                     $.each( response.money, function( index, value ){
                            
                    

                        resp_trans_row =  resp_trans_row +`<tr>
                                                                <td>`+value.order_no+`</td>
                                                                <td>`+value.bank_transaction_id+`</td>
                                                                <td>`+value.total_amount+`</td>
                                                                <td>`+value.order_status+`</td>
                                                            </tr>`;
                        
                    });
                    
                    $('#order_id').val(response.money.order_no);
               
                    }
                else{
                    if (response.money.bank_transaction_id != null) {
                        trans_id = response.money.bank_transaction_id;
                    }else{
                        trans_id = response.money.transaction_id;
                    }
                    // <td>`+response.money.order_status+`</td>

                    result_status='';
                    if (response.money.order_status != '') {
                        result_status = response.money.order_status;
                    }else{
                        result_status = response.result.status;
                    }

                    resp_trans_row = `<tr>
                                        <td>`+response.money.order_no+`</td>
                                        <td>`+trans_id+`</td>
                                        <td>`+response.money.amount+`</td>
                                        
                                        <td>`+result_status+`</td>
                                    </tr>`;
                    $('#order_id').val(response.money.order_no);

                }
                $("#resp_trans_row").html(resp_trans_row);
                $("#do_transfer_page").css('display', 'none');

                $("#alert_block").addClass("alert-success");
                $('#alert_head').text("SUCCESS");
                $('#alert_msg').text(' Transaction Done Successfully');
                $('#alert_block').css("display", "block");

                $("#success_page").css('display', 'block');

           } else if (response['status'] == "false") {
               // toastr.error(response['msg'], response['result'] ? response['result']['status'] : '');
               $("#alert_block").addClass("alert-danger");
               $('#alert_head').text("FAILED");
               $('#alert_msg').text(response.msg + "==" +response.records);
               $('#alert_block').css("display", "block");
               
               console.log('failed');
           }
           $('.preloader_blur').css("display", "none");
        //   console.log(response.result.status);
       },
       error: function(response) {
           // toastr.error(response['msg'], response['result'] ? response['result']['status'] : '');
           console.log(response);
           
           $("#alert_block").addClass("alert-danger");
           $('#alert_head').text("FAILED");
        //    $('#alert_msg').text(response['msg']);
           $('#alert_block').css("display", "block");
           $('.preloader_blur').css("display", "none");
           console.log('failed failed');
         
       }
   });

});

$('.add-beneficiary-btn').click(() => {
   $('.preloader').css("display", "block");

   $('#sender_details').css("display", "none");
   $('#add_beneficiary').css("display", "block");
   $('#div_heading').text("ADD BENEFICIARY");

   $('.preloader').css("display", "none");

});

$('#back_senderDtls').click(() => {
   $('#add_beneficiary').css("display", "none");
   $('#sender_details').css("display", "block");
   $('#div_heading').text("Sender Info");

});


$('#verify_bnk_acc_').click(() => {
   $('.preloader_blur').css("display", "block");
   $('#success_div').css('display', 'none');
   $('#error_div').css('display', 'none');

   requestBody =  JSON.parse($('#requestBody').val());
   error_code = 0;
   if($.trim($('#beneficiary_acc_no').val()) == ''){
       error_code = 1;
       alert('Please Enter Bank Account No.');
   }

   if($('#select_bank_code').val() == ''){
       error_code = 1;
       alert('Please Select Bank');
   }

   if($('#beneficiary_ifsc').val() == ''){
       error_code = 1;
       alert('Please Enter IFSC Code');
   }

   
   console.log(error_code);
   if (error_code == 0) {
       
   
       reqBody = {
           "sender_mobile_number": requestBody.sender_mobile_number,
           "token": requestBody.token,
           "user_id": requestBody.user_id,
           "role_id": requestBody.role_id,
           "operatorID": requestBody.operatorID,
           "bank_account_number": $('#beneficiary_acc_no').val(),
           "bank_code": $('#select_bank_code').val(),
           "ifsc": $('#beneficiary_ifsc').val(),
           "reference_number": "121"
           };


       $.ajax({
           type: 'POST',
           url: $('#VERIFY_BANK_API').val(),
           data: JSON.stringify(reqBody),
           'Content-Type': 'application/json',
           dataType: "json",
           
           success: function(response) {

               console.log(response);
               if (response['status'] == "true") {
               
                   console.log('verified');
                   $('#isverifiedupi').val('1');
                   $('#success_msg').text("Account Is Verified");
                   $('#success_alert').css("display", "block");
                   $('#error_alert').css("display", "none");

                   if (response['result']['verify_account_holder'] != '') {
                       $('#beneficiary_name').val(response['result']['verify_account_holder']);

                       $('#beni_model_name').text(response['result']['verify_account_holder']);
                       
                   }
                   if (response['result']['verify_account_number'] != '') {
                       $('#beni_model_acc_no').text(response['result']['verify_account_number']);   
                   }
                   $('.preloader_blur').css("display", "none");
                   $('#verify_bnk_acc_').css("display", "none");

                   $('#success_div').css('display', 'block');

               } else if (response['status'] == "false") {
                   // toastr.error(response['msg'], response['result'] ? response['result']['status'] : '');
               console.log('failed');
               // $('#error_msg').text("Account ");
               $('#error_alert').css("display", "block");
               $('#success_alert').css("display", "none");

               $('.preloader_blur').css("display", "none");
               
               $('#modal_error_msg').text(response['msg']);
               $('#error_div').css('display', 'block');
       
               }
               $('#verifyModel').modal('show');
           },
           error: function(response) {
               // toastr.error(response['msg'], response['result'] ? response['result']['status'] : '');
               
               $('#modal_error_msg').text('FAILED');
               $('#error_div').css('display', 'block');
               console.log('failed');
               $('#verifyModel').modal('show');
           }
       });

   }
});

$('#add_bnfcry_').click(() => {

   $('.preloader_blur').css("display", "block");
   requestBody =  JSON.parse($('#requestBody').val());

   error_code = 0;
   if($.trim($('#beneficiary_acc_no').val()) == ''){
       error_code = 1;
       alert('Please Enter Bank Account No.');
   }

   if($('#select_bank_code').val() == ''){
       error_code = 1;
       alert('Please Select Bank');
   }

   if($('#beneficiary_ifsc').val() == ''){
       error_code = 1;
       alert('Please Enter IFSC Code');
   }

   if($('#beneficiary_name').val() == ''){
       error_code = 1;
       alert('Please Enter Name');
   }
   if($('#beneficiary_mobile').val() == ''){
       error_code = 1;
       alert('Please Enter Mobile No');
   }


   if (error_code == 0) {
       
   
       reqBody = {
           "sender_mobile_number": requestBody.sender_mobile_number,
           "token": requestBody.token,
           "user_id": requestBody.user_id,
           "role_id": requestBody.role_id,
           "operatorID": requestBody.operatorID,
           "bank_account_number": $('#beneficiary_acc_no').val(),
           "bank_code": $('#select_bank_code').val(),
           "ifsc": $('#beneficiary_ifsc').val(),
           "recipient_name": $('#beneficiary_name').val(),
           "recipient_mobile_number": $('#beneficiary_mobile').val(),
           "is_verified": $('#isverifiedupi').val(),
           };

           $.ajax({
               type: 'POST',
               url: $('#ADD_BANK_ACCOUNT_API').val(),
               data: JSON.stringify(reqBody),
               'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
               dataType: "json",
               success: function(response) {
   
                   console.log(response);
                   if (response['status'] == "true") {
                   
                       console.log('verified');
                       
                       // $('#success_msg').text("Account Is Verified");
                       // $('#success_alert').css("display", "block");
                       // $('#error_alert').css("display", "none");
                       resp_msg =  response['msg'];
                       resp_msg =  resp_msg.replace(/ /g, "_");
                       $.get( "/delete_beneficiary_api/"+response['status']+"/"+resp_msg, function( data ) {
                       });

                       $('.preloader_blur').css("display", "none");
                       // $('#verify_bnk_acc_').css("display", "none");
                       location.reload();
                   } else if (response['status'] == "false") {
                       // toastr.error(response['msg'], response['result'] ? response['result']['status'] : '');
                       console.log('failed');

                       resp_msg =  response['msg'];
                       resp_msg =  resp_msg.replace(/ /g, "_");
                       $.get( "/delete_beneficiary_api/"+response['status']+"/"+resp_msg, function( data ) {
                       });

                   $('#error_msg').text(response['msg']);
                   $('#error_alert').css("display", "block");
                   $('#success_alert').css("display", "none");
   
                   $('.preloader_blur').css("display", "none");

                   setTimeout(function(){ }, 3000);

                   location.reload();
           
                   }
               
               },
               error: function(response) {
                   // toastr.error(response['msg'], response['result'] ? response['result']['status'] : '');
                   console.log(response);
                   console.log('failed');
               }
           });
   }
});


//bhim upi
$('#verify_upi_acc_').click(() => {
   $('.preloader_blur').css("display", "block");

   $('#success_div').css('display', 'none');
   $('#error_div').css('display', 'none');

   requestBody =  JSON.parse($('#requestBody').val());
   error_code = 0;
   if($.trim($('#beneficiary_acc_no').val()) == ''){
       error_code = 1;
       alert('Please Enter UPI ID');
   }

   
   console.log(error_code);
   if (error_code == 0) {
       
   
       reqBody = {
           "sender_mobile_number": requestBody.sender_mobile_number,
           "token": requestBody.token,
           "user_id": requestBody.user_id,
           "role_id": requestBody.role_id,
           "operatorID": requestBody.operatorID,
           "bank_account_number": $('#beneficiary_acc_no').val(),
           "bank_code": " ",
           "ifsc": " ",
           "reference_number": "121"
           };

console.log(reqBody);
console.log($('#VERIFY_UPI_API').val());
       $.ajax({
           type: 'POST',
           url: $('#VERIFY_UPI_API').val(),
           data: JSON.stringify(reqBody),
           'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
           dataType: "json",
           success: function(response) {

               console.log(response);
               if (response['status'] == "true") {
               
                   console.log('verified');
                   $('#isverifiedupi').val('1');
                   $('#verify_upi_acc_').css("display", "none");
                   $('#add_bnfcry_upi').css("display", "");

                   $('#success_msg').text("UPI ID Is Verified");
                   $('#success_alert').css("display", "block");
                   $('#error_alert').css("display", "none");

                   if (response['result']['verify_account_holder'] != '') {
                       $('#beneficiary_name').val(response['result']['verify_account_holder']);

                       $('#beni_model_name').text(response['result']['verify_account_holder']);
                       $('#beni_model_acc_no').text(response['result']['verify_account_number']);
                   }
                   $('.preloader_blur').css("display", "none");
                   $('#verify_bnk_acc_').css("display", "none");
                   
                   $('#success_div').css('display', 'block');

               } else if (response['status'] == "false") {
                   // toastr.error(response['msg'], response['result'] ? response['result']['status'] : '');
                   console.log('failed');
                   // $('#error_msg').text("Account ");
                   $('#error_alert').css("display", "block");
                   $('#success_alert').css("display", "none");

                   $('.preloader_blur').css("display", "none");
               
                   $('#modal_error_msg').text(response['msg']);
                   $('#error_div').css('display', 'block');
           
               }
               $('#verifyModel').modal('show');
           
           },
           error: function(response) {
               // toastr.error(response['msg'], response['result'] ? response['result']['status'] : '');
               
               $('#modal_error_msg').text('FAILED');
               $('#error_div').css('display', 'block');
               console.log('failed');
               $('#verifyModel').modal('show');
           }
       });

   }
});


$('#add_bnfcry_upi').click(() => {

   if ($('#isverifiedupi').val() == '0' ) {
       $('#add_bnfcry_upi').css("display", "none");
       $('#error_msg').text("Please Verify UPI ID ");
       $('#error_alert').css("display", "block");
       $('#success_alert').css("display", "none");

       $('.preloader_blur').css("display", "none");
       return false;
   }


   $('.preloader_blur').css("display", "block");
   requestBody =  JSON.parse($('#requestBody').val());

   error_code = 0;
   if($.trim($('#beneficiary_acc_no').val()) == ''){
       error_code = 1;
       alert('Please Enter Bank Account No.');
   }

   
  

   if($('#beneficiary_name').val() == ''){
       error_code = 1;
       alert('Please Enter Name');
   }

   if($('#beneficiary_mobile').val() == ''){
       error_code = 1;
       alert('Please Enter Mobile No');
   }


   if (error_code == 0) {
       
   
       reqBody = {
           "sender_mobile_number": requestBody.sender_mobile_number,
           "token": requestBody.token,
           "user_id": requestBody.user_id,
           "role_id": requestBody.role_id,
           "operatorID": requestBody.operatorID,
           "bank_account_number": $('#beneficiary_acc_no').val(),
           "bank_code":" ",
           "ifsc": " ",
           "recipient_name": $('#beneficiary_name').val(),
           "recipient_mobile_number": $('#beneficiary_mobile').val(),
           };

           $.ajax({
               type: 'POST',
               url: $('#ADD_UPI_ACCOUNT_API').val(),
               data: JSON.stringify(reqBody),
               'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
               dataType: "json",
               success: function(response) {
   
                   console.log(response);
                   if (response['status'] == "true") {
                   
                       console.log('verified');
                       
                       // $('#success_msg').text("Account Is Verified");
                       // $('#success_alert').css("display", "block");
                       // $('#error_alert').css("display", "none");
                       resp_msg =  response['msg'];
                       resp_msg =  resp_msg.replace(/ /g, "_");
                       //used to set response status in session
                       $.get( "/delete_beneficiary_api/"+response['status']+"/"+resp_msg, function( data ) {
                       });

                       $('.preloader_blur').css("display", "none");
                       // $('#verify_bnk_acc_').css("display", "none");
                       location.reload();
                   } else if (response['status'] == "false") {
                       // toastr.error(response['msg'], response['result'] ? response['result']['status'] : '');
                       console.log('failed');

                       resp_msg =  response['msg'];
                       resp_msg =  resp_msg.replace(/ /g, "_");
                       $.get( "/delete_beneficiary_api/"+response['status']+"/"+resp_msg, function( data ) {
                       });

                   $('#error_msg').text(response['msg']);
                   $('#error_alert').css("display", "block");
                   $('#success_alert').css("display", "none");
   
                   $('.preloader_blur').css("display", "none");

                   setTimeout(function(){ }, 3000);

                   location.reload();
           
                   }
               
               },
               error: function(response) {
                   // toastr.error(response['msg'], response['result'] ? response['result']['status'] : '');
                   
                   console.log('failed');
               }
           });
   }
});



function deleteBeneFunction(){

   $('#deleteModal').modal('hide');
  

   requestBody =  JSON.parse($('#requestBody').val());
   reqBody = {
       "sender_mobile_number": requestBody.sender_mobile_number,
       "token": requestBody.token,
       "user_id": requestBody.user_id,
       "role_id": requestBody.role_id,
       "operatorID": requestBody.operatorID,
      "recipient_id" :  $('#delete_beneficiary_id').val()
       };
   console.log(reqBody);
   $.ajax({
       type: 'POST',
       url: $('#DELETE_BENEFICIARY').val(),
       data: JSON.stringify(reqBody),
       'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
       dataType: "json",
       success: function(response) {

           console.log(response);
           if (response['status'] == "true") {
              resp_msg =  response['msg'];
           //    resp_msg =  resp_msg.replace(/ /g, "_");
               $.get( "/delete_beneficiary_api/"+response['status']+"/"+resp_msg, function( data ) {
               });
               console.log("/delete_beneficiary_api/"+response['status']+"/"+resp_msg);
               
               // $('#success_msg').text("Account Is Verified");
               // $('#success_alert').css("display", "block");
               // $('#error_alert').css("display", "none");

               // if (response['result']['verify_account_holder'] != '') {
               //     $('#beneficiary_name').val(response['result']['verify_account_holder']);
               // }
               $('.preloader_blur').css("display", "block");
               location.reload();
               // $('#verify_bnk_acc_').css("display", "none");
               
           } else if (response['status'] == "false") {
               // toastr.error(response['msg'], response['result'] ? response['result']['status'] : '');
                   console.log('failed');
                   // $('#error_msg').text("Account ");
                   // $('#error_alert').css("display", "block");
                   // $('#success_alert').css("display", "none");

                   // $('.preloader_blur').css("display", "none");
                   resp_msg =  response['msg'];
                   // resp_msg =  resp_msg.replace(/ /g, "_");
                   $.get( "/delete_beneficiary_api/"+response['status']+"/"+resp_msg, function( data ) {
                   });
                   console.log("/delete_beneficiary_api/"+response['status']+"/"+resp_msg);
                   $('.preloader_blur').css("display", "block");
                   location.reload();
           }
       
       },
       error: function(response) {
           // toastr.error(response['msg'], response['result'] ? response['result']['status'] : '');
           
           console.log('failed');
       }
   });
}

$('#verify_transfer').click(() => {

   $('.preloader_blur').css("display", "block");
   $('#success_div').css('display', 'none');
   $('#error_div').css('display', 'none');

   requestBody =  JSON.parse($('#requestBody').val());
   
   
   reqBody = {
       "sender_mobile_number": requestBody.sender_mobile_number,
       "token": requestBody.token,
       "user_id": requestBody.user_id,
       "role_id": requestBody.role_id,
       "operatorID": ($('#operator_name').val() == 'CRAZY_MONEY') ? requestBody.smartpay : requestBody.operatorID,
       "bank_account_number": requestBody.bank_account_number,
       "bank_code": requestBody.bank_code,
       "ifsc": requestBody.ifsc,
       "reference_number": "121"
       };

       console.log(reqBody);
   $.ajax({
       type: 'POST',
       url: $('#VERIFY_BANK_API').val(),
       data: JSON.stringify(reqBody),
       'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
       dataType: "json",
       success: function(response) {

           console.log(response);
           if (response['status'] == "true") {
           
               console.log('verified');
               $( "#verify_transfer" ).remove();
               // $('#success_msg').text("Account Is Verified");
               // $('#success_alert').css("display", "block");
               // $('#error_alert').css("display", "none");

               if (response['result']['verify_account_holder'] != '') {
                   $('#verified_recip_name').text(response['result']['verify_account_holder']);

                   $('#beni_model_name').text(response['result']['verify_account_holder']);
                   $('#beni_model_acc_no').text(response['result']['verify_account_number']);
               }
               
               $("#alert_block").addClass("alert-success");
               $('#alert_head').text("SUCCESS");
               $('#alert_msg').text('User is Verified');
               $('#alert_block').css("display", "block");

               $('.preloader_blur').css("display", "none");
               // $('#verify_bnk_acc_').css("display", "none");
               
               $('#success_div').css('display', 'block');
               // $('#verifyModel').modal('show');
               
           } else if (response['status'] == "false") {
               // toastr.error(response['msg'], response['result'] ? response['result']['status'] : '');
               console.log('failed');
               
               // $('#error_msg').text("Account ");
               // $('#error_alert').css("display", "block");
               // $('#success_alert').css("display", "none");
               $("#alert_block").addClass("alert-danger");
               $('#alert_head').text("FAILED");
               $('#alert_msg').text(response['msg']);
               $('#alert_block').css("display", "block");
               $('.preloader_blur').css("display", "none");

               
               $('#modal_error_msg').text(response['msg']);
               $('#error_div').css('display', 'block');
               // $('#verifyModel').modal('show');
           }
           $('#verifyModel').modal('show');
       
       },
       error: function(response) {
           // toastr.error(response['msg'], response['result'] ? response['result']['status'] : '');
               $("#alert_block").addClass("alert-danger");
               $('#alert_head').text("FAILED");
               $('#alert_msg').text('failed');
               $('#alert_block').css("display", "block");
               $('.preloader_blur').css("display", "none");

               $('#modal_error_msg').text('FAILED');
               $('#error_div').css('display', 'block');
           console.log('failed');
           $('#verifyModel').modal('show');

       }
   });

   

   

});
// $('#verify_transfer_test').click(() => {
//     $('#verifyModel').modal('show');
// });
function createFundAcc(id){

   alert(id);
   $('.preloader_blur').css("display", "block");
   requestBody =  JSON.parse($('#requestBody').val());
   reqBody = {
                   "sender_mobile_number": $('#mobile_no').val(),
                   "operatorID": $('#operator_name').val(),
                   "recipient_id": id,
                   "token": requestBody.token,
                   "user_id": requestBody.user_id,
                   "role_id": requestBody.role_id
               };
   
   $.ajax({
       type: 'POST',
       url: $('#FUNDACC_API').val(),
       data: JSON.stringify(reqBody),
       'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
       dataType: "json",
       success: function(response) {

           console.log(response);
           if (response['status'] == "true") {
               resp_msg =  "Fund Account is Created";
               //    resp_msg =  resp_msg.replace(/ /g, "_");
               $.get( "/delete_beneficiary_api/"+response['status']+"/"+resp_msg, function( data ) {
               });
               location.reload();
               // $("#alert_block").addClass("alert-success");
               // $('#alert_head').text("SUCCESS");
               // $('#alert_msg').text('Fund Account is Created');
               // $('#alert_block').css("display", "block");

               // $('.preloader_blur').css("display", "none");
              
               
           } else if (response['status'] == "false") {
               
               console.log('failed');
               
               resp_msg =  response['msg'];
               // resp_msg =  resp_msg.replace(/ /g, "_");
               $.get( "/delete_beneficiary_api/"+response['status']+"/"+resp_msg, function( data ) {
               });
               console.log("/delete_beneficiary_api/"+response['status']+"/"+resp_msg);
               location.reload();

               // $("#alert_block").addClass("alert-danger");
               // $('#alert_head').text("FAILED");
               // $('#alert_msg').text(response['msg']);
               // $('#alert_block').css("display", "block");
               // $('.preloader_blur').css("display", "none");
               
   
           }
       
       },
       error: function(response) {
               $("#alert_block").addClass("alert-danger");
               $('#alert_head').text("FAILED");
               $('#alert_msg').text('failed');
               $('#alert_block').css("display", "block");
               $('.preloader_blur').css("display", "none");
               
           console.log(response);
       }
   });

}

