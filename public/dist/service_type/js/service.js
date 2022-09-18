$('input[name="service_type"]').change(() => {
    serviceType = $('input[name="service_type"]:checked').val();
    if (serviceType) {
        $('#all_operator_id,#all_operator_id-error').css('display', 'none');

    }

    if (serviceType == "Prepaid") {
        $('#pre_operator_id').css('display', 'block');
        $('#post_operator_id,#post_operator_id-error').css('display', 'none');
    }
    if (serviceType == "Postpaid") {
        $('#post_operator_id').css('display', 'block');
        $('#pre_operator_id,#pre_operator_id-error').css('display', 'none');
    }
});

$('#mobile-mpin-btn').click(() => {
    $('#mobile-submit-btn').trigger('click');
    if ($('#mpin').val() != "")
        $('#mpinModal').modal('hide');
});

$('#dth-mpin-btn').click(() => {
    $('#dth-submit-btn').trigger('click');
    if ($('#mpin').val() != "")
        $('#mpinModal').modal('hide');
});

// Onsubmit of Mobile Recharge 
$('#mobile-submit-btn').click(() => {
    $('#dth-mpin-btn').css('display', 'none');
    $('#mobile-mpin-btn').css('display', 'block');
    var formEle = $('#mobileRechargeForm');
    if (formEle.valid()) {
        submitMobDth(formEle);
    }
});

// Onsubmit of DTH Recharge 
$('#dth-submit-btn').click(() => {
    $('#mobile-mpin-btn').css('display', 'none');
    $('#dth-mpin-btn').css('display', 'block');
    var formEle = $('#dthRechargeForm');
    if (formEle.valid()) {
        submitMobDth(formEle);
    }
});

// Submit from data to the server
var submitMobDth = (formEle) => {
    var userMpin = $('#mpin').val();
    if (userMpin == "") {
        $('#mpinModal').modal('show');
        return false;
    }


    var rechargeAPI = $('#recharge_api').val();
    var reqData = formEle.serializeArray();
    if (reqData) {
        $('.fa-spinner').css('display', 'inline-flex');
        $('.proceed-button').prop('disabled', true);
        var getValByName = (name) => {
            var value = "";
            $.each(reqData, function(i, field) {
                if (field.name == name) {
                    value = field.value;
                }
            });
            return value;
        }

        var reqBody = {
            "operatorID": getValByName('operatorID'),
            "mobileNumber": getValByName('mobileNumber'),
            "amount": getValByName('amount'),
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
                    toastr.success(response['msg'], response['result']['status']);
                    resetForm(formEle);
                } else if (response['status'] == "false") {
                    toastr.error(response['msg']);
                }
                $('.fa-spinner').css('display', 'none');
                $('.proceed-button').prop('disabled', false);
                $('#mpin').val('');
                $('#mobile-mpin-btn').css('display', 'none');
            },
            error: function(response) {
                toastr.error(response['msg']);
                $('.fa-spinner').css('display', 'none');
                $('.proceed-button').prop('disabled', false);
                $('#mpin').val('');
                $('#mobile-mpin-btn').css('display', 'none');
            }
        });
    }
}

// reset form after successfull transaction
var resetForm = (element) => {
    element.data('validator').resetForm();
    element[0].reset();

}

var resetFormId = (fromId) => {
    var formEle = "";
    if (fromId == "mobile") {
        $('#clearDTHScopes').trigger('click');
        formEle = $('#dthRechargeForm');
    } else if (fromId == "dth") {
        $('#clearMobScopes').trigger('click');
        formEle = $('#mobileRechargeForm');
    }

    resetForm(formEle);
}

var operatorList = $('#operator-list').val();
if (operatorList) {
    operatorCodeVal = JSON.parse(operatorList);
} else {
    operatorCodeVal = [];
}

var allPlanModal = $('#allPlanModal');

// End for Mobile Recharge

// Handle By Angular
var app = angular.module('myApp', []);
app.config(function($interpolateProvider) {
    $interpolateProvider.startSymbol('<%=');
    $interpolateProvider.endSymbol('%>');
});

app.controller('rechargeCtrl', function($scope, $http) {
    $scope.test = "Successfull!!";

    $scope.opCode = null;
    $scope.opCircle = null;
    $scope.allPlans = [];
    $scope.allDTHPlans = [];
    $scope.offers121Data = [];
    $scope.planType = ['TUP', 'FTT', '2G', '3G', 'SMS', 'LSC', 'OTR', 'RMG'];
    $scope.selectedAmount = "";
    $scope.dthAcId = "";
    $scope.dthAcInfo = "";

    $scope.getAvailablePlans = (planType) => {
        $scope.allPlans = [];
        var opEle = null;
        if (serviceType == "Prepaid") {
            opEle = $('#pre_operator_id');
        } else if (serviceType == "Postpaid") {
            opEle = $('#post_operator_id');
        }

        $.ajax({
            type: 'POST',
            url: "get_operator_rech_pln",
            data: $.param({ operator_id: opEle.val(), circle: $scope.opCircle, type: planType, request_from: "API" }),
            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
            dataType: "json",
            success: function(response) {
                if (response['status'] == true) {
                    $scope.$apply(function() {
                        $scope.allPlans = response['result'];
                    });
                } else {
                    $scope.$apply(function() {
                        $scope.allPlans = [];
                    });
                }
            },
            error: function(response) {
                $scope.allPlans = [];
            }
        });
    }

    // Start For Mobile Recharge
    $('#phone_mobile_no').keyup(function() {
        $scope.allDTHPlans = [];
        var mobileNumber = $(this).val();
        var serviceType = $("input[name='service_type']:checked").val();
        var opEle = null;
        if (serviceType == "Prepaid") {
            opEle = $('#pre_operator_id');
        } else if (serviceType == "Postpaid") {
            opEle = $('#post_operator_id');
        }

        if (mobileNumber.length == 10) {

            opEle.prop('disabled', true);
            $.ajax({
                type: 'POST',
                url: "get_operator_mobile_info",
                data: $.param({ mobile: mobileNumber, request_from: "API" }),
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                dataType: "json",
                success: function(response) {
                    // console.log(response);
                    if (response['status'] == true) {
                        $scope.$apply(function() {
                            $scope.opCircle = response['result']['circle'];
                        });

                        opEle.val(response['result']['operator_id']);
                        opEle.trigger('change');
                    }
                    opEle.prop('disabled', false);
                },
                error: function(response) {
                    //  console.log(response);
                    opEle.prop('disabled', false);
                }
            });
        } else {
            opEle.val('');
        }
    });


    allPlanModal.on('show.bs.modal', function() {
        $('#nav-link-1').trigger('click');
    });


    allPlanModal.on('hidden.bs.modal', function() {
        $scope.$apply(function() {
            $scope.allPlans = [];
            // $scope.allDTHPlans = [];
            // $scope.opCircle = "";
            // $scope.opCode = "";
        });
    });

    $('#pre_operator_id,#post_operator_id').change(function() {
        var opId = $(this).val();
        $scope.offers121Data = [];

        var mobileNumber = $('#phone_mobile_no').val();

        // 121 Offers Flow
        if (opId && mobileNumber) {
            $.ajax({
                type: 'POST',
                url: "get_121_offers_info",
                data: $.param({ mobile: mobileNumber, operator_id: opId, request_from: "API" }),
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                dataType: "json",
                success: function(response) {
                    if (response['status'] == true) {
                        $scope.$apply(function() {
                            $scope.offers121Data = response['result'];
                        });
                    } else {
                        $scope.$apply(function() {
                            $scope.offers121Data = [];
                        });
                    }
                },
                error: function(response) {
                    $scope.offers121Data = [];
                }
            });
        }


    });

    $scope.setFinalAmnt = (amount) => {
        setTimeout(() => {
            $('#phone_amount').val(amount);
            allPlanModal.modal('hide');
            $('#offers121Modal').modal('hide');
        }, 10);
    }

    $scope.setFinalDTHAmnt = (amount) => {
        setTimeout(() => {
            $('#dth_amount').val(amount);
            allPlanModal.modal('hide');
        }, 10);
    }

    $('#dth_operator_id').change(function() {
        $scope.allPlans = [];
        $scope.allDTHPlans = [];
        $scope.dthAcInfo = "";
        var dthId = $(this).val();
        $scope.dthAcId = dthId;
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
                    $scope.$apply(function() {
                        $scope.allDTHPlans = response;
                    });
                },
                error: function(response) {}
            });
        }
    });

    $('#dth_mobile_no').keyup(function() {
        var dthMbNo = $(this).val();

        if (dthMbNo.length > 6) {
            $.ajax({
                type: 'GET',
                url: "get_dth_ac_info",
                data: $.param({ operator_id: $scope.dthAcId, mobile: dthMbNo }),
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                dataType: "json",
                success: function(response) {
                    if (response && response['status'] == "true") {
                        $scope.$apply(function() {
                            $scope.dthAcInfo = response['result'];
                        });
                    } else {
                        $scope.$apply(function() {
                            $scope.dthAcInfo = "";
                        });
                    }

                },
                error: function(response) {
                    $scope.dthAcInfo = "";
                }
            });
        }
    });


    // electricity
    $('#view-bill-elect').click(function() {

        $('#view-bill-elect').css('display', 'none');
        $('#view_loader').css('display', 'block');

        apiUrl = $("#get_biller_details").val();
        // api_key = $("#api_key").val();
        // user_id = $("#user_id").val();
        // role_id = $("#role_id").val();
        // operator = $("#operator_electricity").val();

        console.log(apiUrl);
        input_arr = $("input[name='a[]']")
            .map(function() { return $(this).val(); }).get();


        reqBody = {
            "token": $("#api_key").val(),
            "user_id": $("#user_id").val(),
            "role_id": $('#role_id').val(),
            "billerID": $('#bill_type').val(),
            "a0": input_arr[0],
            "a1": input_arr[1]
        };

        console.log(reqBody);
        $.ajax({
            type: 'POST',
            url: apiUrl,
            data: JSON.stringify(reqBody),
            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
            dataType: "json",
            success: function(response) {
                if (response && response['status'] == "true") {

                    console.log(response);
                    $('#electricityPaymentForm').css('display', 'none');
                    $('#bill_details').css('display', 'block');

                    info_bill = '';
                    start = ' <div class="row">';
                    end = '</div>';
                    $.each(response.result.inputParams.input, function(key, valueObj) {
                        info_bill = info_bill + '<div class="col-sm-12 col-md-2">' +
                            valueObj.paramName + '</div>' +
                            '<div class="col-sm-12 col-md-2">' +
                            valueObj.paramValue + '</div>';
                    });
                    info_bill = start + info_bill + end;

                    basic_amt = '<div class="col-sm-12 col-md-2"> Customer Name </div>' +
                        '<div class="col-sm-12 col-md-2">' + response.result.billerResponse.customerName + '</div>' +

                        '<div class="col-sm-12 col-md-2"> Bill Amount   </div>' +
                        '<div class="col-sm-12 col-md-2">' + response.result.billerResponse.billAmount + '</div>' +

                        '<div class="col-sm-12 col-md-2"> Bill Date </div>' +
                        '<div class="col-sm-12 col-md-2">' + response.result.billerResponse.billDate + '</div>' +

                        '<div class="col-sm-12 col-md-2"> Bill Number</div>' +
                        '<div class="col-sm-12 col-md-2">' + response.result.billerResponse.billNumber + '</div>' +

                        '<div class="col-sm-12 col-md-2">Bill Period</div>' +
                        '<div class="col-sm-12 col-md-2">' + response.result.billerResponse.billPeriod + '</div>' +

                        '<div class="col-sm-12 col-md-2">Due Date</div>' +
                        '<div class="col-sm-12 col-md-2">' + response.result.billerResponse.dueDate + '</div>';

                    info_bill = info_bill + start + basic_amt + end;

                    info_bill_amt = '';
                    $.each(response.result.billerResponse.amountOptions.option, function(key, valueObj) {
                        info_bill_amt = info_bill_amt + '<div class="col-sm-12 col-md-2">' +
                            valueObj.amountName + '</div>' +
                            '<div class="col-sm-12 col-md-2">' +
                            valueObj.amountValue + '</div>';
                    });


                    info_bill = info_bill + start + info_bill_amt + end;

                    add_info = '';
                    $.each(response.result.additionalInfo.info, function(key, valueObj) {
                        info_bill_amt = info_bill_amt + '<div class="col-sm-12 col-md-2">' +
                            valueObj.infoName + '</div>' +
                            '<div class="col-sm-12 col-md-2">' +
                            valueObj.infoValue + '</div>';
                    });

                    info_bill = info_bill + start + add_info + end;

                    pay_btn = start + '<div class="col-sm-12 col-md-2"><button type="button" class="btn btn-primary" onclick="openPayModel();" id="pay_bill" >PAY</button> </div>' + end;

                    info_bill = info_bill + pay_btn;


                    $("#bill_details").html(info_bill);


                    $("#pay_token").val($("#api_key").val());
                    $("#pay_user_id").val($("#user_id").val());
                    $("#pay_role_id").val($('#role_id').val());
                    $("#pay_biller_id").val($('#bill_type').val());
                    $("#pay_order_id").val(response.result.orderId);

                    $('#view_loader').css('display', 'none');

                } else {

                    console.log(response);
                    $("#error_msg").text(response.msg + " !!! " + response.result.errorInfo.error.errorMessage);
                    $('#view-bill-elect').css('display', 'block');
                    $('#view_loader').css('display', 'none');

                }

            },
            error: function(response) {
                // alert("error");
                console.log(response);
                $("#error_msg").text(response.msg + " !!! " + response.result.errorInfo.error.errorMessage);
                $('#view-bill-elect').css('display', 'block');
                $('#view_loader').css('display', 'none');
            }
        });

    });



    $('.bill_type').on('change', function(e) {
        biller_id = $(this).val();
        // console.log(biller_id);
        all_billers = JSON.parse($("#biller_list").val());
        // console.log(all_billers);

        bill_row = all_billers.result.filter(biller => biller.billerId == biller_id);
        // console.log(bill_row[0].billerId);

        input_params = JSON.parse(bill_row[0].billerInputParams);
        input_params = input_params.paramInfo
        // console.log(input_params);

        html_inputs = '';
        myinput = '';
        start = '<div class="row">';
        end = '</div>';
        // $.each(input_params, function(propName, propVal) {
        //     console.log(propVal);

        // myinput = ' <div class="col-sm-12 col-md-2">' +
        //     '<div class="form-group">' +
        //     '<label for=" ' + propVal.paramName + '">' + propVal.paramName + '</label>' +
        //     '<input type="number" class="form-control" id="' + propVal.paramName + '" name="' + propVal.paramName + '" minLength= "' + propVal.minLength + '">' +
        //     '</div>' +
        //     '</div>';
        //     html_inputs = html_inputs + ' ' + myinput;
        // });
        if ("paramName" in input_params) {
            myinput = ' <div class="col-sm-12 col-md-6">' +
                '<div class="form-group">' +
                '<label for=" ' + input_params.paramName + '">' + input_params.paramName + '</label>' +
                '<input type="number" class="form-control" id="' + input_params.paramName + '" name="a[]" minLength= "' + input_params.minLength + '" maxLength= "' + input_params.maxLength + '">' +
                '</div>' +
                '</div>';
            html_inputs = html_inputs + ' ' + myinput;
        } else {
            $.each(input_params, function(key, valueObj) {
                console.log(key + "/" + valueObj);
                myinput = ' <div class="col-sm-12 col-md-6">' +
                    '<div class="form-group">' +
                    '<label for=" ' + valueObj.paramName + '">' + valueObj.paramName + '</label>' +
                    '<input type="number" class="form-control" id="' + valueObj.paramName + '" name="a[]" minLength= "' + valueObj.minLength + '" maxLength= "' + valueObj.maxLength + '">' +
                    '</div>' +
                    '</div>';
                html_inputs = html_inputs + ' ' + myinput;
            });

        }

        console.log(html_inputs)
        $(".inputParams").html(start + html_inputs + end);


    });


    $('#sub-pay').click(function() {
        $('#payBillModel').modal('hide');

        apiUrl = $("#pay_bill").val();
        // apiUrl = '';

        // api_key = $("#api_key").val();
        // user_id = $("#user_id").val();
        // role_id = $("#role_id").val();
        // operator = $("#operator_electricity").val();

        console.log(apiUrl);
        // input_arr = $("input[name='a[]']")
        //     .map(function() { return $(this).val(); }).get();

        // response = JSON.parse($("#pay_response").val());




        reqBody = {
            "token": $("#api_key").val(),
            "user_id": $("#user_id").val(),
            "role_id": $('#role_id').val(),
            "billerID": $('#bill_type').val(),
            "billPayType": "normal",
            "orderId": $('#pay_order_id').val(),
            "amount": $('#payamount').val(),
            "mpin": $('#pay_mpin').val(),
            "operatorID": $('#pay_operator_id').val()
        };





        console.log(reqBody);
        $.ajax({
            type: 'POST',
            url: apiUrl,
            data: JSON.stringify(reqBody),
            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
            dataType: "json",
            success: function(response) {
                if (response && response['status'] == "true") {

                    $('#payBillModel').modal('hide');
                    // $('#payBillRecipt').modal('show');
                    $('#invoiceModal').modal('show');
                    $('#bill_forinvoice').val($("#pay_response").val());

                    input_para = '';
                    $.each(response.result.inputParams.input, function(key, valueObj) {

                        input_para = input_para + '<tr>' +
                            '<td> ' + valueObj.paramName + ' </td>' +
                            '<td> ' + valueObj.paramValue + '</td>' +
                            '</tr>';
                    });

                    $('#customer_name').text(response.result.RespCustomerName);
                    $('#bill_date').text(response.result.RespBillDate);
                    $('#bill_no').text(response.result.RespBillNumber);

                    $('#input_param').html(input_para);

                    $('#bill_row').html(input_para);
                    tbl = '<tr>' +
                        '<td>1</td>' +
                        '<td> ' + response.result.txnRefId + '</td>' +
                        '<td>order id</td>' +
                        '<td> ' + response.result.responseReason + '</td>' +
                        '<td> ' + response.result.RespAmount + '</td>' +
                        '</tr>';
                    $('#basic_amt').text($('#payamount').val());
                    // $('#basic_amt').text(response.result.RespAmount);
                    $('#total_amount').text($('#payamount').val());
                    // $('#total_amount').text(response.result.RespAmount);
                    $('#bill_row').html(tbl);
                    // $('#showRecipt').css('display', 'block');
                    // $('#showRecipt').html(
                    //     tbl + '<div class="col-md-3"><a class="btn btn-warning btn-sm  " id="" onclick="subCharges()"> <i class="fa fa-print"></i>Print </a></div>'
                    // );


                } else {
                    console.log(response);
                    msg = '<div class="row"><p style="color: red;">' + response.result.errorInfo.error.errorMessage + ' </p></div>';

                    $('#payBillRecipt').model('show');
                    $('#failedRecipt').css('display', 'block');
                    $('#failedRecipt').html(msg);
                }

            },
            error: function(response) {

                msg = '<div class="row"><p style="color: red;"> ' + response.result.errorInfo.error.errorMessage + ' </p></div>';

                $('#failedRecipt').css('display', 'block');
                $('#failedRecipt').html(msg);
                $('#payBillRecipt').model('show');
                console.log(response);
            }
        });


    });




    $scope.clearMobScopes = () => {
        $scope.allPlans = [];
        $scope.offers121Data = [];
        $scope.opCode = "";
        $scope.opCircle = "";
    }

    $scope.clearDTHScopes = () => {
        $scope.allDTHPlans = [];
        $scope.dthAcInfo = "";
        $scope.dthAcId = "";
    }
});

$('#pay_bill').click(function() {
    // var transferReqId = $(this).val();
    // $('#trans_req_id').val(transferReqId);


})

function subCharges() {

    // $('#payBillRecipt').modal('hide');
    $('#surchargeModal').modal('show');

}

function openPayModel() {
    // alert();
    $('#payBillModel').modal('show');
}

function printRecipt() {
    sub_charge = $("#inputsurCharge").val();
    total = parseFloat(sub_charge) + parseFloat($("#total_amount").text());

    $('#surCharge').text(sub_charge);
    $('#total_amount').text(total);
    window.print();
    // $("#inputsurCharge").val("");
    $('#surchargeModal').modal('hide');
}