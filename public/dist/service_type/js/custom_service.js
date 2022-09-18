var all_months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Sep", "Oct", "Nov", "Dec"];

// Start For Mobile Recharge
$('#txtMobileNo').keyup(function() {


$('#view_plans').css('display', 'none');
$('#121_offers').css('display', 'none');
$('#offerModal').css('display', 'none');
$('#allPlanModal').css('display', 'none');
    allDTHPlans = [];
    var mobileNumber = $(this).val();
    // var serviceType = $("input[name='service_type']:checked").val();
    var opEle = $('#ddlOperator');
    

    if (mobileNumber.length == 10) {

        opEle.prop('disabled', true);
        $.ajax({
            type: 'POST',
            url: "get_operator_mobile_info",
            data: $.param({ mobile: mobileNumber, request_from: "API" }),
            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
            dataType: "json",
            success: function(response) {
                console.log(response);
                if (response['status'] == true) {
                    console.log('true');
                    // $('.ddlOperator option[value="'+response['result']['operator_id'] +'"]')
                    // $('#ddlOperator option[' + response['result']['operator_id'] + ']').attr('selected','selected');
                    $("#ddlOperator option[value='"+ response['result']['operator_id'] +"']").prop('selected', true);

                    // $scope.$apply(function() {
                    //     $scope.opCircle = response['result']['circle'];
                    // });

                    // opEle.val(response['result']['operator_id']);
                    // opEle.trigger('change');
                    if (response['result']['circle']) {
                        $('#opCircle').val(response['result']['circle']);
                        $('#view_plans').css('display', 'block');
                    }

                    offer121();
                    // viewPlan(opEle, response['result']['operator_id'], response['result']['circle']);
                }
                opEle.prop('disabled', false);
            },
            error: function(response) {
                 console.log(response);
                opEle.prop('disabled', false);
            }
        });
    } else {
        opEle.val('');
    }
});

function getAvailablePlans(plan){
   
    var opEle = $('#ddlOperator');
    $( "#plan_info" ).empty();
    if (plan == '121 Offer') {
        offer121();
    }else{
        $.ajax({
            type: 'POST',
            url: "get_operator_rech_pln",
            data: $.param({ operator_id: opEle.val(), circle:  $('#opCircle').val(), type: plan, request_from: "API" }),
            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
            dataType: "json",
            success: function(response) {
                console.log(response);
                if (response['status'] == true) {
                    plan_tr = `<tr ><td class="label">Amount</td><td class="label" >Details</td><td class="label">Talktime</td><td class="label">Validity</td></tr>`;
                    $.each( response['result'], function( index, value ){
                        // console.log(value);
                        plan_tr =  plan_tr + `<tr onclick="setFinalAmnt(`+value.amount +`)" style="cursor: pointer;"><td>₹`+value.amount +`</td> <td style="text-align: center">`+value.detail +`</td> <td>`+value.talktime +` </td> <td>`+value.validity +`</td></tr>`;
                    });
                   
                    $( "#plan_info" ).html( plan_tr );
    
                   
                } else {
                    console.log(response);
                }
            },
            error: function(response) {
                console.log(response);
            }
        });
    }
    
}

$('#view_plans').click(function() {
    $('#offerModal').css('display', 'none');
    $('#allPlanModal').css('display', 'block');
    $('#nav-link-0').trigger('click');
});

function setFinalAmnt(amt){
    $('#txtAmount').val(amt);
}

function offer121(){
// $('#ddlOperator').change(function() {
    var opId = $('#ddlOperator option:selected').val();
    // $scope.offers121Data = [];
   
    $( "#offer_info" ).empty();
    var mobileNumber = $('#txtMobileNo').val();
    $('#121_offers').css('display', 'none');
    // 121 Offers Flow
    if (opId && mobileNumber) {
        $.ajax({
            type: 'POST',
            url: "get_121_offers_info",
            data: $.param({ mobile: mobileNumber, operator_id: opId, request_from: "API" }),
            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
            dataType: "json",
            success: function(response) {
                console.log(response);
                if (response['status'] == true) {
                    // $('#121_offers').css('display', 'block');
                        // $scope.offers121Data = response['result'];
                        offer_tr = `<tr ><td class="label">Price</td><td class="label" >Details</td</tr>`;
                        $.each( response['result'], function( index, value ){
                            // console.log(value);
                            offer_tr =  offer_tr + `<tr onclick="setFinalAmnt(`+value.price +`)" style="cursor: pointer;"><td>₹`+value.price +`</td> <td style="text-align: center">`+value.ofrtext +`</td> </tr>`;
                        });
                       
                        // $( "#offer_info" ).html( offer_tr );
                        $( "#plan_info" ).html( offer_tr );
                } else {
                    
                }
            },
            error: function(response) {
                console.log(response);

                // $scope.offers121Data = [];
            }
        });
    }


// });

}

$('#ddlOperator').change(function() {
    offer121();
});

$('#121_offers').click(function() {
    $('#allPlanModal').css('display', 'none');
    $('#offerModal').css('display', 'block');

});

function confirmSubmit(){
    // console.log($('#txtMobileNo').val());
    if ($('#payment_type').val() == 'mobile') {

        $('#confirm_customer_value').text($('#txtMobileNo').val());
        $('#confirm_operator_value').text($('#ddlOperator option:selected').text());
        $('#confirm_amt_value').text($('#txtAmount').val());

    }else if($('#payment_type').val() == 'dth') {
       
        $('#confirm_customer_value').text($('#txtCustomerNo').val());
        $('#confirm_operator_value').text( $('#ddlOperator_dth option:selected').text() );
        $('#confirm_amt_value').text($('#txtAmountDTH').val());
        
    }
    
   
   
    $('#comfirmtionModal').modal('show');


}
$('#confirmed_recharge').click(function() {
    $('#comfirmtionModal').modal('hide');
    $('.preloader_blur').css("display", "block");

    if ($('#payment_type').val() == 'mobile') {

        $('#recharge_resp_customer_value').text($('#txtMobileNo').val());
        $('#recharge_resp_operator_value').text($('#ddlOperator option:selected').text());
        $('#recharge_resp_amt_value').text($('#txtAmount').val());

    }else if($('#payment_type').val() == 'dth') {
       
        $('#recharge_resp_customer_value').text($('#txtCustomerNo').val());
        $('#recharge_resp_operator_value').text( $('#ddlOperator_dth option:selected').text() );
        $('#recharge_resp_amt_value').text($('#txtAmountDTH').val());
        
    }

    if ($('#payment_type').val() == 'mobile') {
        mobilesubmit();
    }else if($('#payment_type').val() == 'dth') {
        dthsubmit();
    }
});

function mobilesubmit(){
    $('.preloader_blur').css("display", "block");
    $('#btnRecharge').prop('disabled', true);
    var userMpin = $('#txtMobileTpin').val();
    if (userMpin == "") {
        $('.error-mpin').css('display', 'block');
        return false;
    }

    var rechargeAPI = $('#recharge_api').val();
    var reqBody = {
        "operatorID": $('#ddlOperator option:selected').val(),
        "mobileNumber": $('#txtMobileNo').val(),
        "amount": $('#txtAmount').val(),
        "mpin": userMpin,
        "token": $('#api_key').val(),
        "user_id": $('#user_id').val(),
        "role_id": $('#role_id').val(),
    };

    $.ajax({
        type: 'POST',
        url: rechargeAPI,
        data: JSON.stringify(reqBody),
        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
        dataType: "json",
        success: function(response) {
            

            if (response['status'] == "true") {
                // resetForm( $('#recharge_prepaid'));
                // toastr.success(response['msg'], response['result']['status']);
                resp_msg =  response['msg'];
                //    resp_msg =  resp_msg.replace(/ /g, "_");
                // $.get( "/delete_beneficiary_api/"+response['result']['status']+"/"+resp_msg, function( data ) {
                // });
                
                $('#resp_success_logo').css('display', 'block');
                $('#resp_failed_logo').css('display', 'none');
                $("#modal_resp_msg").removeClass("text-danger").addClass("text-success");
                $('#modal_resp_msg').text(resp_msg);
                
                // location.reload();


            } else if (response['status'] == "false") {
                $('#resp_success_logo').css('display', 'none');
                $('#resp_failed_logo').css('display', 'block');
                $('#modal_resp_msg').text(response['msg']);
                $("#modal_resp_msg").removeClass("text-success").addClass("text-danger");

                // toastr.error(response['msg']);
                $("#alert_block").addClass("alert-danger");
                $('#alert_head').text("FAILED");
                $('#alert_msg').text(response['msg']);
                $('#alert_block').css("display", "block");
            }
            $('.preloader_blur').css("display", "none");

            $('#rechargeDoneModel').modal('show');

            $('#btnRecharge').prop('disabled', false);
            $('#txtMobileTpin').val('');
           
        },
        error: function(response) {
            // toastr.error(response['msg']);

            $("#alert_block").addClass("alert-danger");
            $('#alert_head').text("FAILED");
            $('#alert_msg').text('failed');
            $('#alert_block').css("display", "block");

            $('.preloader_blur').css("display", "none");
            $('#btnRecharge').prop('disabled', false);
            $('#txtMobileTpin').val('');
           
        }
    });

}

$('#recharge_ok').click(function() {
    location.reload();
});

// reset form after successfull transaction
var resetForm = (element) => {
    element.data('validator').resetForm();
    element[0].reset();

}

//start DTh
$('#ddlOperator_dth').change(function() {
    $('#account_details').css("display", "none");
    var operatorList = $('#operator-list').val();
    var operatorCodeVal = [];
    if (operatorList) {
        operatorCodeVal = JSON.parse(operatorList);
    } 

    

    var dthId = $(this).val();
    var operatorCode = null;

    operatorCodeVal.forEach(element => {
        if (dthId == element['operator_id']) {
            operatorCode = element['operator_info_code'];
        }
    });

    if (operatorCode) {
        $.ajax({
            type: 'POST',
            url: "get_dth_plan_info",
            data: $.param({ operator_id: operatorCode }),
            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
            dataType: "json",
            success: function(response) {
                console.log(response);
                // $scope.$apply(function() {
                //     $scope.allDTHPlans = response;
                // });
            },
            error: function(response) { console.log(response); }
        });
    }
});

function accountInfo(){
// $('#txtCustomerNo').keyup(function() {
    $('#alert_block').css("display", "none");
    $('#account_details').css("display", "none");

    var dthMbNo = $('#txtCustomerNo').val();
    var dthAcId = $('#ddlOperator_dth option:selected').val();
    if (dthMbNo.length > 6) {
        $.ajax({
            type: 'GET',
            url: "get_dth_ac_info",
            data: $.param({ operator_id: dthAcId, mobile: dthMbNo }),
            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
            dataType: "json",
            success: function(response) {
                console.log(response);
                if (response && response['status'] == "true") {
                    $('#dthAcInfo_Name').text(response['result']['Name']);
                    $('#dthAcInfo_vc').text(response['result']['vc']);
                    $('#dthAcInfo_Rmn').text(response['result']['Rmn']);
                    $('#dthAcInfo_Balance').text(response['result']['Balance']);
                    $('#dthAcInfo_Next_Recharge_Date').text(response['result']['NextRechargeDate']);
                    $('#dthAcInfo_Plan').text(response['result']['Plan']);
                    $('#account_details').css("display", "block");
                } else {
                    $("#alert_block").addClass("alert-danger");
                    $('#alert_head').text("Check ");
                    $('#alert_msg').text(response['msg']);
                    $('#alert_block').css("display", "block");
                    // $scope.$apply(function() {
                    //     $scope.dthAcInfo = "";
                    // });
                }

            },
            error: function(response) {
                console.log(response);

                // $scope.dthAcInfo = "";
            }
        });
    }
}
// });

//dtch recharge
function dthsubmit(){
    $('.preloader_blur').css("display", "block");
    $('#btnRecharge').prop('disabled', true);
    var userMpin = $('#txtTpin_DTH').val();
    if (userMpin == "") {
        $('.error-mpin').css('display', 'block');
        return false;
    }

    var rechargeAPI = $('#recharge_api').val();
    var reqBody = {
        "operatorID": $('#ddlOperator_dth option:selected').val(),
        "mobileNumber": $('#txtCustomerNo').val(),
        "amount": $('#txtAmountDTH').val(),
        "mpin": userMpin,
        "token": $('#api_key').val(),
        "user_id": $('#user_id').val(),
        "role_id": $('#role_id').val(),
    };

    $.ajax({
        type: 'POST',
        url: rechargeAPI,
        data: JSON.stringify(reqBody),
        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
        dataType: "json",
        success: function(response) {
            if (response['status'] == "true") {
                // resetForm( $('#recharge_dth'));
                // toastr.success(response['msg'], response['result']['status']);
                resp_msg =  response['msg'];
                //    resp_msg =  resp_msg.replace(/ /g, "_");
                // $.get( "/delete_beneficiary_api/"+response['result']['status']+"/"+resp_msg, function( data ) {
                // });
                // location.reload();

                $('#resp_success_logo').css('display', 'block');
                $('#resp_failed_logo').css('display', 'none');
                $("#modal_resp_msg").removeClass("text-danger").addClass("text-success");
                $('#modal_resp_msg').text(resp_msg);


            } else if (response['status'] == "false") {

                $('#resp_failed_logo').css('display', 'block');
                $('#resp_success_logo').css('display', 'none');
                $('#modal_resp_msg').text(response['msg']);
                $("#modal_resp_msg").removeClass("text-success").addClass("text-danger");

                // toastr.error(response['msg']);
                $("#alert_block").addClass("alert-danger");
                $('#alert_head').text("FAILED");
                $('#alert_msg').text(response['msg']);
                $('#alert_block').css("display", "block");
            }
            $('.preloader_blur').css("display", "none");
            $('#btnRecharge').prop('disabled', false);
            $('#txtTpin_DTH').val('');
            $('#rechargeDoneModel').modal('show'); 
        },
        error: function(response) {
            // toastr.error(response['msg']);

            $("#alert_block").addClass("alert-danger");
            $('#alert_head').text("FAILED");
            $('#alert_msg').text('failed');
            $('#alert_block').css("display", "block");

            $('.preloader_blur').css("display", "none");
            $('#btnRecharge').prop('disabled', false);
            $('#txtTpin_DTH').val('');
           
        }
    });

}

var electric_biller_state_wise;

function onBillerStateNew(id){

    city_id = null;

    reqBody = { 
                    biller_cat: $('#service_name').val(), 
                    state_code: $('#electricity_state_new option:selected').val(), 
                    token: $('#api_key').val(), 
                    user_id: $('#user_id').val(), 
                    role_id: $('#role_id').val(), 
                    city_name: city_id 
                };
    console.log(reqBody);

    $.ajax({
        type: 'POST',
        url: $('#state_biller_api').val(),
        // data: JSON.stringify(reqBody),
        data: $.param(reqBody),
        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
        dataType: "json",
        success: function(response) {
            console.log(response);
            if (response['status'] == true) {
                electric_biller_state_wise = response['result'];
                console.log("true");
                biller_options = `<option value="">--Select Biller--</option>`;
                $.each( response['result'], function( index, value ){
                    // console.log(value);
                    biller_options =  biller_options + `<option value="`+ value['biller_code'] +`">`+ value['biller_name'] +` </option>`;
                });
                $('#elect_biller_new').empty().append(biller_options);

            } else if (response['status'] == "false") {
             
            }
            
           
        },
        error: function(response) {
            console.log(response);

           
        }
    });


}


function onBillerState(id){

    // var reqBody = {
    //     "biller_cat": $('#service_name').val(),
    //     "state_code": $('#electricity_state option:selected').val(),
    //     "token": $('#api_key').val(),
    //     "user_id": $('#user_id').val(),
    //     "role_id": $('#role_id').val()
    // };

    city_id = null;
    
    if ( ( id == 'electricity_state') && ($('#electricity_city').length) ) {

       cities = setCityStatewise($('#electricity_state option:selected').val());
       city_options = `<option value="">--Select City--</option>`;
       $.each( cities, function( index, value ){
           // console.log(value);
           city_options =  city_options + `<option value="`+ value['city_name'] +`">`+ value['city_name'] +` </option>`;
       });
       $('#electricity_city').empty().append(city_options);


    }
    
    if ($('#electricity_city').length) {
        city_id = $('#electricity_city option:selected').val();
        
    }

    reqBody = { 
                    biller_cat: $('#service_name').val(), 
                    state_code: $('#electricity_state option:selected').val(), 
                    token: $('#api_key').val(), 
                    user_id: $('#user_id').val(), 
                    role_id: $('#role_id').val(), 
                    city_name: city_id 
                };
    console.log(reqBody);

    $.ajax({
        type: 'POST',
        url: $('#state_biller_api').val(),
        // data: JSON.stringify(reqBody),
        data: $.param(reqBody),
        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
        dataType: "json",
        success: function(response) {
            console.log(response);
            if (response['status'] == true) {
                electric_biller_state_wise = response['result'];
                console.log("true");
                biller_options = `<option value="">--Select Biller--</option>`;
                $.each( response['result'], function( index, value ){
                    // console.log(value);
                    biller_options =  biller_options + `<option value="`+ value['billerId'] +`">`+ value['billerName'] +` </option>`;
                });
                $('#elect_biller').empty().append(biller_options);

            } else if (response['status'] == "false") {
             
            }
            
           
        },
        error: function(response) {
            console.log(response);
            // toastr.error(response['msg']);

           
        }
    });
    // $.get( "/delete_beneficiary_api/"+response['result']['status']+"/"+resp_msg, function( data ) {
    // });


}

$('#elect_biller_new').change(function() {
    $('#bill_paid').css('display', 'none');
    biller_id = $('#elect_biller_new option:selected').val();
    console.log(biller_id);
    bill_row = [];
    if (electric_biller_state_wise) {
        bill_row = electric_biller_state_wise.filter(biller => biller.billerId == biller_id);    
    }else{
        // console.log("y");

        bill_row= getBillerInfoById(biller_id);
        // console.log(bill_row);
        // bill_row = bill_row.result;
    }

    myinput = `<div class="form-group">
                    <div class="fl-wrap fl-wrap-input fl-has-focus">
                        <input type="text" style="cursor:text !important;background-color: white;" class="form-control fl-input" id="" minlength="8" maxlength="20" name="a[]" placeholder="Service Number" data-placeholder="Enter Service Number">
                    </div>
                </div>`;
    myinput = myinput+ `<div class="FormButtons">
                            <input type="button"   class="btn btn-lg  success-grad" value="Fetch"
                                id="fetch_elect" name="fetch_elect" tabindex="5"
                                onclick="fetchBillNew()">
                        </div>`;
    $("#electric_inputparam").html(myinput  );

});


$('#elect_biller').change(function() {
    $('#bill_paid').css('display', 'none');
    biller_id = $('#elect_biller option:selected').val();
    console.log(biller_id);
    bill_row = [];
    if (electric_biller_state_wise) {
        bill_row = electric_biller_state_wise.filter(biller => biller.billerId == biller_id);    
    }else{
        // console.log("y");

        bill_row= getBillerInfoById(biller_id);
        // console.log(bill_row);
        // bill_row = bill_row.result;
    }


    // bill_row = electric_biller_state_wise.filter(biller => biller.billerId == biller_id);    

    console.log(bill_row[0].billerInputParams);
    // str = billerInputParams = bill_row[0].billerInputParams; 

    // new_str = str.replace('"billerInputParams": ', '');
    // console.log(JSON.parse(new_str));

    
    myinput = ''
    if( ('billercustomize' in bill_row[0]) && (bill_row[0]['billercustomize'] == 'Yes') ){
        myinput = myinput+` <div class="form-group">
                                <div class="fl-wrap fl-wrap-input fl-has-focus">
                                    <input type="text"
                                        style="cursor:text !important;background-color: white;"
                                        class="form-control fl-input" id=""
                                          name="a[]"
                                        placeholder="` + bill_row[0]['billercustomizeInputParams'] + `"
                                        data-placeholder="Enter ` + bill_row[0]['billercustomizeInputParams'] +`">
                                </div>
                            </div>`;
    }else {
        if (bill_row[0].billerInputParams) {
            input_params = JSON.parse(bill_row[0].billerInputParams);
        }else{
            billerInputParams = bill_row[0].billerInputParams; 
            input_params = JSON.parse(bill_row[0].billerInputParams);
        }
        input_params = input_params.paramInfo;

        if ("paramName" in input_params) {
            myinput = myinput+` <div class="form-group">
                            <div class="fl-wrap fl-wrap-input fl-has-focus">
                                <input type="text"
                                    style="cursor:text !important;background-color: white;"
                                    class="form-control fl-input" id=""
                                    minlength="`+ input_params.minLength +`"  maxlength="`+ input_params.maxLength +`"  name="a[]"
                                    placeholder="` + input_params.paramName + `"
                                    data-placeholder="Enter ` + input_params.paramName +`">
                            </div>
                        </div>`;


            // myinput = myinput + ' <div class="col-sm-12 col-md-6">' +
            //     '<div class="form-group">' +
            //     '<label for=" ' + input_params.paramName + '">' + input_params.paramName + '</label>' +
            //     '<input type="number" class="form-control" id="' + input_params.paramName + '" name="a[]" minLength= "' + input_params.minLength + '" maxLength= "' + input_params.maxLength + '">' +
            //     '</div>' +
            //     '</div>';
            // html_inputs = html_inputs + ' ' + myinput;
        } else {
            $.each(input_params, function(key, valueObj) {
                // console.log(key + "/" + valueObj);
                myinput = myinput+` <div class="form-group">
                            <div class="fl-wrap fl-wrap-input fl-has-focus">
                                <input type="text"
                                    style="cursor:text !important;background-color: white;"
                                    class="form-control fl-input" id=""
                                    minlength="`+ valueObj.minLength +`"  maxlength="`+ valueObj.maxLength +`"  name="a[]"
                                    placeholder="` + valueObj.paramName + `"
                                    data-placeholder="Enter ` + valueObj.paramName +`">
                            </div>
                        </div>`;
                // myinput = ' <div class="col-sm-12 col-md-6">' +
                //     '<div class="form-group">' +
                //     '<label for=" ' + valueObj.paramName + '">' + valueObj.paramName + '</label>' +
                //     '<input type="number" class="form-control" id="' + valueObj.paramName + '" name="a[]" minLength= "' + valueObj.minLength + '" maxLength= "' + valueObj.maxLength + '">' +
                //     '</div>' +
                //     '</div>';
                // html_inputs = html_inputs + ' ' + myinput;
            });

        }
    }
    myinput = myinput+ `<div class="FormButtons">
                            <input type="button"   class="btn btn-lg  success-grad" value="Fetch"
                                id="fetch_elect" name="fetch_elect" tabindex="5"
                                onclick="fetchBill()">
                        </div>`;
    $("#electric_inputparam").html(myinput  );

});
var consumer_no = '';
var customer_name = '';
function fetchBillNew(){
    
    $('#fetch_elect').css('display', 'none');
    $('#bill_paid').css('display', 'none');
    $('.preloader_blur').css("display", "block");
    $('#alert_block').css("display", "none");

    input_arr = $("input[name='a[]']").map(function() { return $(this).val(); }).get();
    console.log(input_arr);
    reqBody = {
        "token": $("#api_key").val(),
        "user_id": $("#user_id").val(),
        "role_id": $('#role_id').val(),
        "billerID": $('#elect_biller_new option:selected').val(),
        "a0": input_arr[0],
        "a1": input_arr[1]
    };

    console.log(reqBody);

    $.ajax({
        type: 'POST',
        url: $("#get_biller_details_new").val(),
        data: JSON.stringify(reqBody),
        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
        dataType: "json",
        success: function(response) {
            console.log(response);
            if (response && response['status'] == "true") {
                
                var consumer_no = response.result.customer_name
                var customer_name = response.result.customer_name
                console.log('true');
                info_bill = '';
                info_bill = info_bill + `   <tr>
                                                <td class="label">Biller Name:</td>
                                                <td >`+$('#elect_biller_new option:selected').text()+`</td>
                                            </tr>
                                            <tr>
                                                <td class="label">Consumer No:</td>
                                                <td >`+response.result.consumer_no+`</td>
                                            </tr>
                                            <tr>
                                                <td class="label">Consumer Name:</td>
                                                <td >`+response.result.customer_name+`</td>
                                            </tr>
                                            <tr>
                                                <td class="label">Bill Date:</td>
                                                <td >`+response.result.bill_date+`</td>
                                            </tr>
                                            <tr>
                                                <td class="label">Due Date:</td>
                                                <td >`+response.result.due_date+`</td>
                                            </tr>
                                            <tr>
                                                <td class="label">Due Amount:</td>
                                                <td >`+response.result.due_amount+`</td>
                                            </tr>`;

                    bill_Amt =  `<table class="table  equal-cols  table-bordered  border">
                                        <td>Amount</td>
                                        <td>
                                            <input type="text" name="pay_amount" id="pay_amount"  class="form-control" value="`+response.result.due_amount+`"   required >
                                        </td>
                                    </tr>
                                    <tr>
                                    <td>MPIN</td>
                                    <td>
                                        <input type="password"
                                                style="cursor:text !important;background-color: white;"
                                                class="form-control fl-input" id="txtBillpin"
                                                maxlength="4" name="txtBillpin"
                                                onkeypress="return isNumeric(event);" placeholder="MPIN"
                                                tabindex="4"
                                                onfocus="if (this.hasAttribute('readonly')) { this.removeAttribute('readonly'); this.blur(); this.focus();  }"
                                                data-placeholder="Enter MPIN">
                                                
                                    <td>
                                </table>`;
                
                $("#bill_order_id").val(response.result.orderId);
                $("#bill_info").html(info_bill );
                $("#payAmt").html(bill_Amt );
                $("#bill_details").css('display', 'block' );
            } else {

                $("#alert_block").addClass("alert-danger");
                $('#alert_head').text("FAILED");
                $('#alert_msg').text(response.result.errorInfo.error.errorMessage);
                $('#alert_block').css("display", "block");
                // $('#fetch_elect').prop('disabled', false);
                $('#fetch_elect').css('display', 'block');
            }
            $('.preloader_blur').css("display", "none");
           
           

        },
        error: function(response) {
            
            console.log(response);
                $("#alert_block").addClass("alert-danger");
                $('#alert_head').text("FAILED");
                $('#alert_msg').text(response.result.errorInfo.error.errorMessage);
                $('#alert_block').css("display", "block");
                // $('#fetch_elect').prop('disabled', false);
                $('#fetch_elect').css('display', 'block');
                $('.preloader_blur').css("display", "none");
        }
    });
}


function fetchBill(){
    
    $('#fetch_elect').css('display', 'none');
    $('#bill_paid').css('display', 'none');
    $('.preloader_blur').css("display", "block");
    $('#alert_block').css("display", "none");

    input_arr = $("input[name='a[]']").map(function() { return $(this).val(); }).get();
    console.log(input_arr);
    reqBody = {
        "token": $("#api_key").val(),
        "user_id": $("#user_id").val(),
        "role_id": $('#role_id').val(),
        "billerID": $('#elect_biller option:selected').val(),
        "a0": input_arr[0],
        "a1": input_arr[1]
    };

    console.log(reqBody);

    $.ajax({
        type: 'POST',
        url: $("#get_biller_details").val(),
        data: JSON.stringify(reqBody),
        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
        dataType: "json",
        success: function(response) {
            console.log(response);
            if (response && response['status'] == "true") {

                console.log('true');
                info_bill = '';
                $.each(response.result.inputParams.input, function(key, valueObj) {
                    info_bill = info_bill + ` <tr>
                                        <td class="label">`+valueObj.paramName+`:</td>
                                        <td >`+valueObj.paramValue+`</td>
                                    </tr>`;
                });
                info_bill = info_bill + `   <tr>
                                                <td class="label">Consumer Name:</td>
                                                <td >`+response.result.billerResponse.customerName+`</td>
                                            </tr>
                                            <tr>
                                                <td class="label">Bill Date:</td>
                                                <td >`+response.result.billerResponse.billDate+`</td>
                                            </tr>`;
                // check_amt_option = response.result.billerResponse.amountOptions;
                if (response.result.billerResponse.amountOptions) {
                    $.each(response.result.billerResponse.amountOptions.option, function(key, valueObj) {
                        info_bill = info_bill + ` <tr>
                                            <td class="label">`+valueObj.amountName+`:</td>
                                            <td >`+valueObj.amountValue/100+`</td>
                                        </tr>`;
                    });
                }
                

                $.each(response.result.additionalInfo.info, function(key, valueObj) {
                    info_bill = info_bill + ` <tr>
                                        <td class="label">`+valueObj.infoName+`:</td>
                                        <td >`+valueObj.infoValue+`</td>
                                    </tr>`;
                });

                isExact =      `<td>
                                        <input type="text" name="pay_amount" id="pay_amount"  class="form-control" value="`+ response.result.billerResponse.billAmount/100 +`"   required >
                                        
                                </td>`;
                if (response.result.billerPaymentExactness == 'Exact') {
                    isExact = `<td style="font-size: 1.2rem;">
                                        <input type="hidden" name="pay_amount" id="pay_amount"  class="form-control" value="`+ response.result.billerResponse.billAmount/100 +`" required disabled>
                                        `+ response.result.billerResponse.billAmount/100 +`
                                </td>`;
                }

                dueDate  = new Date(response.result.billerResponse.dueDate);
                    bill_Amt =  `<table class="table  equal-cols  table-bordered  border">
                                                
                                                <tr>
                                                    <td>Due Date</td>
                                                   <td> `+ dueDate.getDate() +`-`+ all_months[ dueDate.getMonth() ]+`-`+ dueDate.getFullYear() +`</td>
                                                </tr>
                                                <tr>
                                                    <td>Amount</td>
                                                    `+ isExact +`
                                                </tr>
                                                <tr>
                                                <td>MPIN</td>
                                                <td>
                                                    <input type="password"
                                                            style="cursor:text !important;background-color: white;"
                                                            class="form-control fl-input" id="txtBillpin"
                                                            maxlength="4" name="txtBillpin"
                                                            onkeypress="return isNumeric(event);" placeholder="MPIN"
                                                            tabindex="4"
                                                            onfocus="if (this.hasAttribute('readonly')) { this.removeAttribute('readonly'); this.blur(); this.focus();  }"
                                                            data-placeholder="Enter MPIN">
                                                            
                                                <td>
                                            </table>`;
                
                $("#bill_order_id").val(response.result.orderId);
                $("#bill_info").html(info_bill );
                $("#payAmt").html(bill_Amt );
                $("#bill_details").css('display', 'block' );

                // set confirmation
                bbps_ui = '';

                //biller name
                bbps_ui = bbps_ui + `<tr>
                                        <th class="text-red">
                                            Biller Name <span class="colon-algin">:</span>
                                        </th>
                                        <td> `+$('#elect_biller option:selected').text()+` </td>
                                    </tr>`;
                
                $.each(response.result.inputParams.input, function(key, valueObj) {
                    bbps_ui = bbps_ui + ` <tr>
                                                    <th class="text-red">`+valueObj.paramName+`<span class="colon-algin">:</span></th>
                                                    <td >`+valueObj.paramValue+`</td>
                                                </tr>`;
                });
                bbps_ui = bbps_ui + `<tr>
                                        <th class="text-red">
                                            Consumer Name <span class="colon-algin">:</span>
                                        </th>
                                        <td> `+response.result.billerResponse.customerName+` </td>
                                    </tr>
                                    <tr>
                                        <th class="text-red">
                                            Amount <span class="colon-algin">:</span>
                                        </th>
                                        <td id="on_confirm_amt">  `+ response.result.billerResponse.billAmount/100 +` </td>
                                    </tr>`;
                $("#confirmation-bbps-table").html(bbps_ui );
                // console.log(bbps_ui);
            } else {

                // console.log(response);
                $("#alert_block").addClass("alert-danger");
                $('#alert_head').text("FAILED");
                $('#alert_msg').text(response.result.errorInfo.error.errorMessage);
                $('#alert_block').css("display", "block");
                // $('#fetch_elect').prop('disabled', false);
                $('#fetch_elect').css('display', 'block');
            }
            $('.preloader_blur').css("display", "none");
           
           

        },
        error: function(response) {
            
            console.log(response);
                $("#alert_block").addClass("alert-danger");
                $('#alert_head').text("FAILED");
                $('#alert_msg').text(response.result.errorInfo.error.errorMessage);
                $('#alert_block').css("display", "block");
                // $('#fetch_elect').prop('disabled', false);
                $('#fetch_elect').css('display', 'block');
                $('.preloader_blur').css("display", "none");
        }
    });
}

$('#pay_btn_new').click(function() {
    procced_pay_new();
});

function procced_pay_new(){
    
    $('.preloader_blur').css("display", "block");
    $('#alert_block').css("display", "none");
    
    apiUrl = $("#pay_bill_new").val();
    input_arr = $("input[name='a[]']").map(function() { return $(this).val(); }).get();
    reqBody = {
        "token": $("#api_key").val(),
        "user_id": $("#user_id").val(),
        "role_id": $('#role_id').val(),
        "billerID": $('#elect_biller_new option:selected').val(),
        "billPayType": "normal",
        "orderId": $('#bill_order_id').val(),
        "amount": $('#pay_amount').val(),
        "mpin": $('#txtBillpin').val(),
        "a0": input_arr[0],
        "operatorID": $('#operator_id').val(),
        "customer_name": customer_name,
        "consumer_no": consumer_no
    };
    console.log(reqBody);
    $.ajax({
        type: 'POST',
        url: apiUrl,
        data: JSON.stringify(reqBody),
        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
        dataType: "json",
        success: function(response) {
            console.log(response);
            if (response && response['status'] == "true") {
                $('#bill_details').css("display", "none");

                input_para = '';
                input_para = ` <tr>
                                    <td class="label"> Customer Name:</td>
                                    <td >`+response.result.RespCustomerName+`</td>
                                </tr>
                                <tr>
                                    <td class="label"> Bill Date:</td>
                                    <td >`+response.result.RespBillDate+`</td>
                                </tr>
                                <tr>
                                    <td class="label"> Bill No:</td>
                                    <td >`+response.result.RespBillNumber+`</td>
                                </tr>`;

                paid_amt = `<table class="table  equal-cols  table-bordered  border">
                                <tr>
                                    <td >Transaction ID:</td>
                                <td > `+ response.result.txnRefId +`</td>
                                </tr>
                                <tr>
                                    <td>Status:</td>
                                    <td>`+ response.result.responseReason  +`</td>
                                </tr>
                                <tr>
                                <td>Paid Amount:</td>
                                <td>`+ $('#pay_amount').val() +`<td>
                                </tr>
                            </table>`;
                $('#bill_paid_info').html(input_para);
                $('#paid_payAmt').html(paid_amt);
                
                $('#bill_paid').css("display", "block");
                $("#alert_block").addClass("alert-success");
                $('#alert_head').text("Success");
                $('#alert_block').css("display", "block");
                $('#txtBillpin').val("");
                $('#resp_order_id').val(response['order_id']);

            } else {
                console.log(response);

                $("#alert_block").addClass("alert-danger");
                $('#alert_head').text("FAILED");
                if (response.result == null) {
                    $('#alert_msg').text(response.msg);
                    
                }else{
                    $('#alert_msg').text(response.result.errorInfo.error.errorMessage);
                }
                $('#alert_msg').text(response.msg);
                $('#alert_block').css("display", "block");
                $('#fetch_elect').prop('disabled', false);
            }
            $('.preloader_blur').css("display", "none");
           
           
        },
        error: function(response) {
                $("#alert_block").addClass("alert-danger");
                $('#alert_head').text("FAILED");
                $('#alert_msg').text(response.result.error_message);
                $('#alert_block').css("display", "block");
                $('#fetch_elect').prop('disabled', false);
                $('.preloader_blur').css("display", "none");

        }
    });
}

//BBPS Confirm Modal
$('#pay_btn_test').click(function() {
    $('#comfirmtionBBPSModal').modal('show');
    $('#on_confirm_amt').text($('#pay_amount').val());
    // $('#allPlanModal').css('display', 'block');
    // $('#nav-link-0').trigger('click');
});
$('#confirmed_bbps').click(function() {
    procced_pay();
    $('#comfirmtionBBPSModal').modal('hide');
   
});
//pay bill
function procced_pay(){
    
    $('.preloader_blur').css("display", "block");
    $('#alert_block').css("display", "none");

    apiUrl = $("#pay_bill").val();
    reqBody = {
        "token": $("#api_key").val(),
        "user_id": $("#user_id").val(),
        "role_id": $('#role_id').val(),
        "billerID": $('#elect_biller').val(),
        "billPayType": "normal",
        "orderId": $('#bill_order_id').val(),
        "amount": $('#pay_amount').val(),
        "mpin": $('#txtBillpin').val(),
        "operatorID": $('#operator_id').val()
    };
console.log(reqBody);
    $.ajax({
        type: 'POST',
        url: apiUrl,
        data: JSON.stringify(reqBody),
        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
        dataType: "json",
        success: function(response) {
            console.log(response);
            if (response && response['status'] == "true") {
                $('#bill_details').css("display", "none");
                // $('#payBillModel').modal('hide');
                // // $('#payBillRecipt').modal('show');
                // $('#invoiceModal').modal('show');
                // $('#bill_forinvoice').val($("#pay_response").val());

                input_para = '';
                input_para = ` <tr>
                                    <td class="label"> Customer Name:</td>
                                    <td >`+response.result.RespCustomerName+`</td>
                                </tr>
                                <tr>
                                    <td class="label"> Bill Date:</td>
                                    <td >`+response.result.RespBillDate+`</td>
                                </tr>
                                <tr>
                                    <td class="label"> Bill No:</td>
                                    <td >`+response.result.RespBillNumber+`</td>
                                </tr>`;
                $.each(response.result.inputParams.input, function(key, valueObj) {

                    input_para = input_para + '<tr>' +
                                                '<td class="label"> ' + valueObj.paramName + ' </td>' +
                                                '<td > ' + valueObj.paramValue + '</td>' +
                                            '</tr>';
                });

                // $('#input_param').html(input_para);
                paid_amt = `<table class="table  equal-cols  table-bordered  border">
                                                
                                    <tr>
                                        <td >Transaction ID:</td>
                                    <td > `+ response.result.txnRefId +`</td>
                                    </tr>
                                    <tr>
                                        <td>Status:</td>
                                        <td>`+ response.result.responseReason  +`</td>
                                    </tr>
                                    <tr>
                                    <td>Paid Amount:</td>
                                    <td>`+ $('#pay_amount').val() +`<td>
                                    </tr>
                                </table>`;
                                // <tr>
                                //     <td>Amount:</td>
                                //     <td>`+response.result.RespAmount+`<td>
                                //     </tr>
                $('#bill_paid_info').html(input_para);
                $('#paid_payAmt').html(paid_amt);

                // tbl = '<tr>' +
                //     '<td>1</td>' +
                //     '<td> ' + response.result.txnRefId + '</td>' +
                //     '<td>order id</td>' +
                //     '<td> ' + response.result.responseReason + '</td>' +
                //     '<td> ' + response.result.RespAmount + '</td>' +
                //     '</tr>';
                // $('#basic_amt').text($('#payamount').val());
                // // $('#basic_amt').text(response.result.RespAmount);
                // $('#total_amount').text($('#payamount').val());
                // // $('#total_amount').text(response.result.RespAmount);
                // $('#bill_row').html(tbl);
                // // $('#showRecipt').css('display', 'block');
                // // $('#showRecipt').html(
                // //     tbl + '<div class="col-md-3"><a class="btn btn-warning btn-sm  " id="" onclick="subCharges()"> <i class="fa fa-print"></i>Print </a></div>'
                // // );
                
                $('#bill_paid').css("display", "block");
                $("#alert_block").addClass("alert-success");
                $('#alert_head').text("Success");
                $('#alert_block').css("display", "block");
                $('#txtBillpin').val("");
                $('#resp_order_id').val(response['order_id']);

            } else {
                console.log(response);
                // msg = '<div class="row"><p style="color: red;">' + response.result.errorInfo.error.errorMessage + ' </p></div>';

                $("#alert_block").addClass("alert-danger");
                $('#alert_head').text("FAILED");
                if (response.result == null) {
                    $('#alert_msg').text(response.msg);
                    
                }else{
                    $('#alert_msg').text(response.result.errorInfo.error.errorMessage);
                }
                $('#alert_msg').text(response.msg);
                $('#alert_block').css("display", "block");
                $('#fetch_elect').prop('disabled', false);
            }
            $('.preloader_blur').css("display", "none");
           
           
        },
        error: function(response) {

            // msg = '<div class="row"><p style="color: red;"> ' + response.result.errorInfo.error.errorMessage + ' </p></div>';

            // $('#failedRecipt').css('display', 'block');
            // $('#failedRecipt').html(msg);
            // $('#payBillRecipt').model('show');
            console.log(response);


                $("#alert_block").addClass("alert-danger");
                $('#alert_head').text("FAILED");
                $('#alert_msg').text(response.result.errorInfo.error.errorMessage);
                $('#alert_block').css("display", "block");
                $('#fetch_elect').prop('disabled', false);
                $('.preloader_blur').css("display", "none");

        }
    });

}

function getBillerInfoById(billeId){
    // reqBody = ;
    console.log(billeId);
    var result=null;
    $.ajax({
        async: false,
        type: 'POST',
        url: $('#biller_by_id_api').val(),
        // data: JSON.stringify(reqBody),
        data: $.param({ biller_id: billeId, token: $('#api_key').val(), user_id: $('#user_id').val(), role_id: $('#role_id').val()  }),
        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
        dataType: "json",
        success: function(response) {
            console.log(response);
            // electric_biller_state_wise = response['result'];
        //    return response['result'];
           result =  response['result'];
        //    set_electric_biller_state_wise( response['result']);
            
           
        },
        error: function(response) {
            // console.log(response);
            // toastr.error(response['msg']);

           
        }
    });
            console.log(result);

    return result;
}

function set_electric_biller_state_wise(result){
            electric_biller_state_wise = result;
return;
}

function setCityStatewise(state_id){
    var result=null;
    $.ajax({
        async: false,
        type: 'POST',
        url: $('#city_by_state_api').val(),
        // data: JSON.stringify(reqBody),
        data: $.param({ state_code: state_id, token: $('#api_key').val(), user_id: $('#user_id').val(), role_id: $('#role_id').val()  }),
        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
        dataType: "json",
        success: function(response) {
            console.log(response.result);
            // electric_biller_state_wise = response['result'];
        //    return response['result'];
           result =  response['result'];
        //    set_electric_biller_state_wise( response['result']);
            
           
        },
        error: function(response) {
            // console.log(response);
            // toastr.error(response['msg']);

           
        }
    });
            console.log(result);

    return result;
}


$('#subcharge_btn').click(function() {
    $('#surchargeModal').modal('show');
});


function viewInvice(){
   order_id =  $('#resp_order_id').val();
   sbcharge= $('#inputsurCharge').val();
   web_url = $('#web_url').val();
   link_open = web_url + 'public/invoice/' + order_id + '/' + sbcharge;
   $('#surchargeModal').modal('hide');

   window.open(link_open);
}