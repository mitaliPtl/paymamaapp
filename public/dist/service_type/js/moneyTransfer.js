// Handle By Angular
var app = angular.module('myApp', []);
app.config(function($interpolateProvider) {
    $interpolateProvider.startSymbol('<%=');
    $interpolateProvider.endSymbol('%>');
});

var setActiveBtn = (btnId) => {
    $('.card-btn').removeClass('btn-primary').addClass('btn-light');
    $('#' + btnId).removeClass('btn-light').addClass('btn-primary');

    //show sender mobile no field
    $('#send-mob-dtls-section').css('display', 'block');
    $('#sender-details').css('display', 'none');

    //set operatorId 
    $("#operator_id").val(btnId);

}

// reset form after successfull transaction
var resetForm = (element) => {
    // element.data('validator').resetForm();
    element[0].reset();
}

$("#dob").flatpickr();

var ifsc_Array = [];

app.controller('moneyTransferCtrl', function($scope, $http) {
    // default post header
    $http.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded; charset=UTF-8';

    $scope.senderReceipientList = [];
    $scope.singleRcpInfo = {};
    $scope.confirmPayment = false;
    $scope.senderName = "";
    $scope.transaction_fees = 10;
    $scope.surCharge = "";
    $scope.transactionData = {};
    $scope.transactionSum = {};
    $scope.invoiceData = {};

    $scope.init = () => {
        setTimeout(() => {
            $scope.$apply(() => {
                $scope.senderReceipientList = [];
                // $('#invoiceModal').modal('show');
            });
        }, 100);
    }

    // Javascript

    // Onsubmit of Sender Mobile dtls form 
    $('#sender-mob-sb-btn').click(() => {
        if ($("#senderMobForm").valid() == false)
            return false;

        $scope.init();
        var getSenderDtlsAPI = $('#sender_dtls_api').val();
        var formEle = $('#senderMobForm');
        if (formEle.valid()) {
            submitDetails(formEle, getSenderDtlsAPI);
        }
    });

    // Onsubmit of Sender Mobile dtls form 
    $('#registr-sender-btn').click(() => {
        if ($("#senderRegForm").valid() == false)
            return false;

        var createSenderDtlsAPI = $('#create_sender_api').val();
        var formEle = $('#senderRegForm');
        if (formEle.valid()) {
            submitDetails(formEle, createSenderDtlsAPI);
        }
    });

    // On Otp Verificaion
    $('#otp-verify-btn').click(() => {
        var verifySenderRegAPI = $('#verify_sender_reg_api').val();
        var formEle = $('#verifySenderRegForm');
        if (formEle.valid()) {
            submitDetails(formEle, verifySenderRegAPI);
        }
    });

    // Submit from data to the server
    var submitDetails = (formEle, apiUrl) => {
        var reqData = formEle.serializeArray();
        if (reqData) {

            var getValByName = (name) => {
                var value = "";
                $.each(reqData, function(i, field) {
                    if (field.name == name) {
                        value = field.value;
                    }
                });
                return value;
            }

            if ($('#sender_mobile_number').val()) {
                $('#please-wait-send-mb').css('display', 'inline-flex');
                $('#sender-mob-sb-btn').css('display', 'none');
            } else {
                return;
            }


            var reqBody = {};
            if (getValByName('otp')) {
                $('#please-wait-varify-otp').css('display', 'initial');
                $('#otp-verify-btn').css('display', 'none');

                reqBody = {
                    "sender_mobile_number": $('#sender_mobile_number').val(),
                    "otp": getValByName('otp'),
                    "token": $('#api_key').val(),
                    "user_id": $('#user_id').val(),
                    "role_id": $('#role_id').val(),
                    "operatorID": $('#operator_id').val() === "smart" ? "40" : "21"
                };

                console.log(reqBody);
                console.log(apiUrl);
                $.ajax({
                    type: 'POST',
                    url: apiUrl,
                    data: JSON.stringify(reqBody),
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                    dataType: "json",
                    success: function(response) {
                        if (response['status'] == "true") {
                            toastr.success(response['msg'], response['result'] ? response['result']['status'] : '');
                            resetForm(formEle);
                            $('#verifyOTPMdl').modal('hide');

                            // added part
                            // setSenderDtls(response['result']);
                            $('#sender-mob-sb-btn').trigger('click');
                            $('#send-mob-dtls-section,#sender-registration-section').css('display', 'none');
                            $('#sender-details').css('display', 'block');
                            //added part
                        } else if (response['status'] == "false") {
                            toastr.error(response['msg'], response['result'] ? response['result']['status'] : '');
                        }
                        $('#please-wait-varify-otp').css('display', 'none');
                        $('#otp-verify-btn').css('display', 'initial');
                    },
                    error: function(response) {
                        toastr.error(response['msg'], response['result'] ? response['result']['status'] : '');
                        $('#please-wait-varify-otp').css('display', 'none');
                        $('#otp-verify-btn').css('display', 'initial');
                    }
                });
            } else if (getValByName('first_name')) {
                reqBody = {
                    "sender_mobile_number": $('#sender_mobile_number').val(),
                    "first_name": getValByName('first_name'),
                    "last_name": getValByName('last_name'),
                    "pincode": getValByName('pincode'),
                    "token": $('#api_key').val(),
                    "user_id": $('#user_id').val(),
                    "role_id": $('#role_id').val(),
                    "operatorID": $('#operator_id').val() === "smart" ? "40" : "21"
                };
                console.log(reqBody);
                console.log(apiUrl);
                $.ajax({
                    type: 'POST',
                    url: apiUrl,
                    data: JSON.stringify(reqBody),
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                    dataType: "json",
                    success: function(response) {
                        if (response['status'] == "true") {
                            toastr.success(response['msg'], response['result'] ? response['result']['status'] : '');
                            resetForm(formEle);
                            verifyOtp(response['result']['sender_mobile_number']);
                        } else if (response['status'] == "false") {
                            toastr.error(response['msg'], response['result'] ? response['result']['status'] : '');
                        }
                    },
                    error: function(response) {
                        toastr.error(response['msg'], response['result'] ? response['result']['status'] : '');
                    }
                });
            } else {

                reqBody = {
                    "sender_mobile_number": getValByName('sender_mobile_number'),
                    "token": $('#api_key').val(),
                    "user_id": $('#user_id').val(),
                    "role_id": $('#role_id').val(),
                    "operatorID": $('#operator_id').val() === "smart" ? "40" : "21"

                };

                console.log(reqBody);
                console.log(apiUrl);
                $.ajax({
                    type: 'POST',
                    url: apiUrl,
                    data: JSON.stringify(reqBody),
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                    dataType: "json",
                    success: function(response) {
                        console.log(response);
                        if (response['status'] == "true") {
                            let message = response['result'] ? response['result']['status'] : '';
                            toastr.success(response['msg'], message);
                            // resetForm(formEle);
                            setSenderDtls(response['result']);
                            getSenderReceipientList();
                            $('#send-mob-dtls-section').css('display', 'none');
                            $('#sender-details').css('display', 'block');
                            $('#sender-registration-section').css('display', 'none');
                        } else if (response['status'] == "false") {
                            let message = response['result'] ? response['result']['status'] : '';
                            toastr.error(response['msg'], message);
                            // if (response['result'] != null) {
                                $('#send-mob-dtls-section').css('display', 'none');
                                $('#sender-registration-section').css('display', 'block');
                            // }
                        }
                        $('#please-wait-send-mb').css('display', 'none');
                        $('#sender-mob-sb-btn').css('display', '');
                    },
                    error: function(response) {
                        toastr.error(response['msg'], response['result'] ? response['result']['status'] : '');
                        $('#please-wait-send-mb').css('display', 'none');
                        $('#sender-mob-sb-btn').css('display', '');
                    }
                });
            }
        }
    }

    $('.bk-2-send-mob').click(() => {
        $('#send-mob-dtls-section').css('display', 'block');
        $('#sender-registration-section,#sender-details').css('display', 'none');
        $scope.senderReceipientList = [];
    });

    var verifyOtp = (mobileNo) => {
        $('#verifyOTPMdl').modal('show');
        // Enable input mask here
        if ($('input').hasClass('input-mask')) {
            // $('.otp-mask').inputmask("999", { "clearIncomplete": true });
        }

        $('#otp-mob-no').html(mobileNo);

    }

    var setSenderDtls = (data) => {
        $('#sender-name').html(data['sender_name']);
        $scope.senderName = data['sender_name'];
        // $('#sender-av-limit').html();
        $('#sender-mob-no').html(data['sender_mobile_number']);
    }

    $('#resend-otp-lbl').click(() => {
        resendOTP();
        $('#resend-otp-lbl').css('pointer-events', 'none');
        $('#otp-timer-label').css('display', 'block').css('font-size', '10px');
        startTimer(30);
    });

    var startTimer = (timeLeft) => {
        var elem = document.getElementById('otp-resend-timer');
        var timerId = setInterval(countdown, 1000);

        function countdown() {
            if (timeLeft == -1) {
                clearTimeout(timerId);
                $('#resend-otp-lbl').css('pointer-events', 'all');
                $('#otp-timer-label').css('display', 'none');
            } else {
                elem.innerHTML = timeLeft;
                timeLeft--;
            }
        }
    }

    var resendOTP = () => {

        if ($('#sender_mobile_number').val()) {
            reqBody = {
                "sender_mobile_number": $('#sender_mobile_number').val(),
                "token": $('#api_key').val(),
                "user_id": $('#user_id').val(),
                "role_id": $('#role_id').val(),
                "operatorID": $('#operator_id').val() === "smart" ? "40" : "21"
            };
            var apiUrl = $('#resend_otp_api').val();
            $.ajax({
                type: 'POST',
                url: apiUrl,
                data: JSON.stringify(reqBody),
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                dataType: "json",
                success: function(response) {
                    if (response['status'] == "true") {
                        toastr.success(response['msg'], response['result'] ? response['result']['status'] : '');

                    } else if (response['status'] == "false") {
                        toastr.error(response['msg'], response['result'] ? response['result']['status'] : '');
                    }

                },
                error: function(response) {
                    toastr.error(response['msg'], response['result'] ? response['result']['status'] : '');
                }
            });
        }
    }

    setTimeout(() => {
        // $('#addBeneficiaryMdl').modal('show');
    }, 1000);

    //get bank list
    $('#add-beneficiary').click(() => {
        var getBankListAPI = $('#get_bank_list_api').val();
        var formEle = $('#senderMobForm');
        if (formEle.valid()) {
            getBankList(formEle, getBankListAPI);
        }
    });

    var getBankList = (formEle, apiUrl) => {
        var reqData = formEle.serializeArray();
        if (reqData) {
            var reqBody = {};
            reqBody = {
                "sender_mobile_number": $('#sender_mobile_number').val(),
                "token": $('#api_key').val(),
                "user_id": $('#user_id').val(),
                "role_id": $('#role_id').val(),
                "operatorID": $('#operator_id').val() === "smart" ? "40" : "21"
            };

            console.log(apiUrl);
            console.log(reqBody);
            $.ajax({
                type: 'POST',
                url: apiUrl,
                data: JSON.stringify(reqBody),
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                dataType: "json",
                success: function(response) {
                    if (response['status'] == "true") {

                        for (var i = 0, keys = Object.keys(response.result.bank_list), l = keys.length; i < l; i++) {

                            ifsc_Array[response.result.bank_list[i].bank_code] = response.result.bank_list[i].ifsc_prefix
                            $('#select_bank_code').append('<option value="' + response.result.bank_list[i].bank_code + '">' + response.result.bank_list[i].bank_name + '</option>')
                        }

                    } else if (response['status'] == "false") {
                        // console.log(response);
                    }

                },
                error: function(response) {
                    // console.log(response);
                }
            });

        }
    }


    $('#bank-ac-verify-btn').click(() => {
        var apiUrl = $('#verify_bank_ac_api').val();
        submitForBeneficiary(apiUrl, 'verify-bank-ac');
    });

    $('#benf-reg-btn').click(() => {
        var apiUrl = $('#add_recepient_api').val();
        submitForBeneficiary(apiUrl, 'register-benf');
    });

    var submitForBeneficiary = (apiUrl, type = null, recepInfo = null) => {
        var formEle = $('#beneficiaryRegForm');
        var formData = formEle.serializeArray();

        var getValByName = (name) => {
            var value = "";
            $.each(formData, function(i, field) {
                if (field.name == name) {
                    value = field.value;
                }
            });
            return value;
        }


        var reqBody = {
            "sender_mobile_number": $('#sender_mobile_number').val(),
            "token": $('#api_key').val(),
            "user_id": $('#user_id').val(),
            "role_id": $('#role_id').val(),
            "operatorID": $('#operator_id').val() === "smart" ? "40" : "21"
        }


        if (formData) {
            reqBody["bank_account_number"] = getValByName('bank_account_number');
            reqBody["bank_code"] = getValByName('bank_code');
            reqBody["ifsc"] = getValByName('ifsc');
        }

        if (type == "verify-bank-ac") {
            reqBody['reference_number'] = 121;
            $('#bank-ac-verify-spinner').css('display', 'initial');
        } else if (type == "register-benf") {
            reqBody['recipient_name'] = getValByName('recipient_name');
            reqBody['recipient_mobile_number'] = getValByName('recipient_mobile_number');
            $('#benf-reg-spinner').css('display', 'initial');
        } else if (type == "verify_from_payment") {
            reqBody['reference_number'] = 121;
            $('#ac-verification-spinner').css('display', 'initial');
            reqBody["bank_account_number"] = recepInfo['bank_account_number'];
            reqBody["bank_code"] = recepInfo['bank_code'];
            reqBody["ifsc"] = recepInfo['ifsc'];
        }
        console.log(reqBody);
        console.log(apiUrl);
        $.ajax({
            type: 'POST',
            url: apiUrl,
            data: JSON.stringify(reqBody),
            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
            dataType: "json",
            success: function(response) {
                console.log(response);
                if (response['status'] == "true") {

                    toastr.success(response['msg'], response['result'] ? response['result']['status'] : '');

                    if (type == "verify_from_payment")
                        $('#ac-verification-btn').css('display', 'none');
                    else {
                        resetForm(formEle);
                        $('#addBeneficiaryMdl').modal('hide');
                    }
                    getSenderReceipientList();

                } else if (response['status'] == "false") {
                    toastr.error(response['msg'], response['result'] ? response['result']['status'] : '');
                }
                $('#bank-ac-verify-spinner').css('display', 'none');
                $('#benf-reg-spinner,#ac-verification-spinner').css('display', 'none');
            },
            error: function(response) {
                toastr.error(response['msg'], response['result'] ? response['result']['status'] : '');
                $('#bank-ac-verify-spinner').css('display', 'none');
                $('#benf-reg-spinner,#ac-verification-spinner').css('display', 'none');
            }
        });
    }

    // Javascript ends

    var getSenderReceipientList = () => {
        if ($('#sender_mobile_number').val()) {

            reqBody = {
                "sender_mobile_number": $('#sender_mobile_number').val(),
                "token": $('#api_key').val(),
                "user_id": $('#user_id').val(),
                "role_id": $('#role_id').val(),
                "operatorID": $('#operator_id').val() === "smart" ? "40" : "21"

            };

            var apiUrl = $('#get_sender_recp_api').val();
            $.ajax({
                type: 'POST',
                url: apiUrl,
                data: JSON.stringify(reqBody),
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                dataType: "json",
                success: function(response) {
                    if (response['status'] == "true") {
                        toastr.success(response['msg'], response['result'] ? response['result']['status'] : '');
                        $scope.$apply(function() {
                            $scope.senderReceipientList = response['result']['recipient_list'];
                        });
                    } else if (response['status'] == "false") {
                        toastr.error(response['msg'], response['result'] ? response['result']['status'] : '');
                        $scope.senderReceipientList = [];
                    }

                },
                error: function(response) {
                    toastr.error(response['msg'], response['result'] ? response['result']['status'] : '');
                    $scope.senderReceipientList = [];
                }
            });
        }

    }

    $scope.deleteRecep = (recepId) => {
        var apiUrl = $('#delete_recep_api').val();


        if (recepId) {

            var request = {
                "sender_mobile_number": $('#sender_mobile_number').val(),
                "token": $('#api_key').val(),
                "user_id": $('#user_id').val(),
                "role_id": $('#role_id').val(),
                "recipient_id": recepId,
                "operatorID": $('#operator_id').val() === "smart" ? "40" : "21"
            }
            console.log(request);
            console.log(apiUrl);
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this imaginary file!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {

                    // Delete starts
                    $http({
                        method: "POST",
                        url: apiUrl,
                        data: JSON.stringify(request),
                    }).then(function success(response) {
                        if (response['data']['status'] == "true") {
                            swal("Deleted Successfully!", {
                                icon: "success",
                                buttons: false,
                                timer: 2000
                            });
                            setTimeout(() => {
                                getSenderReceipientList();
                            }, 500);
                        } else if (response['data']['status'] == "false") {
                            toastr.error(response['data']['msg'], response['data']['result'] ? response['data']['result']['status'] : '');
                        }
                    }, function error(response) {
                        toastr.error(response['data']['msg'], response['data']['result'] ? response['data']['result']['status'] : '');
                    });
                    //Delete ends
                }
            });


        }
    }

    $scope.loadPaymentModal = (recepData) => {
        $scope.resetScopeVars();
        $('#paymentMdl').modal('show');
        if (recepData) {
            $scope.singleRcpInfo = recepData;
        }
    }

    $scope.doPayment = (recepInfo) => {
        if (recepInfo) {

            if ($("#paymentForm").valid() == false)
                return false

            var formEle = $('#paymentForm');
            var formData = formEle.serializeArray();
            var getValByName = (name) => {
                var value = "";
                $.each(formData, function(i, field) {
                    if (field.name == name) {
                        value = field.value;
                    }
                });
                return value;
            }

            var request = {
                "sender_mobile_number": $('#sender_mobile_number').val(),
                "token": $('#api_key').val(),
                "user_id": $('#user_id').val(),
                "role_id": $('#role_id').val(),

                "recipient_id": recepInfo.recipient_id,
                "transaction_amount": getValByName('transaction_amount'),
                "transaction_type": getValByName('transaction_type'),
                "operatorID": $('#operator_id').val() === "smart" ? "40" : "21",
                "mpin": $('#mpin').val(),

            };



            if (!$scope.confirmPayment) {
               
                
                $scope.transactionData = request;
                $('#confirmPaymentMdl').modal('show');
                $('#paymentMdl').modal('hide');
                return false;
            }
          console.log(request);
            

            var apiUrl = $('#fund_trn_api').val();
            
            $('#fund-trn-spinner').css('display', 'initial');
            $('#fund-trn-btn').prop('disabled', true);
            $scope.transactionSum = {};
            $http({
                method: "POST",
                url: apiUrl,
                data: JSON.stringify(request),
            }).then(function success(response) {
                if (response['data']['status'] == "true") {

                    $('#mpin').val('');
                    $('#confirmPaymentMdl').modal('hide');
                    $('#paymentMdl').modal('hide');
                    $scope.confirmPayment = false;

                    $scope.transactionSum = response['data']['result'];
                    
                    resetForm(formEle);
                    swal(response['data']['msg'], {
                        icon: "success",
                        buttons: false,
                        timer: 3000
                    });
                    setTimeout(() => {
                        $('#pymtSummMdl').modal('show');
                    }, 3000);
                } else if (response['data']['status'] == "false") {

                    toastr.error(response['data']['msg'], response['data']['result'] ? response['data']['result']['status'] : '');
                    $scope.confirmPayment = false;
                   
                   
                }
                $('#fund-trn-spinner').css('display', 'none');
                $('#fund-trn-btn').prop('disabled', false);
                $('#mpin').val('');
                $('#paymentMdl').modal('hide');
                $('#confirmPaymentMdl').modal('hide');
                $("#paymentForm").trigger('reset'); //jquery
                

            }, function error(response) {
                toastr.error(response['data']['msg'], response['data']['result'] ? response['data']['result']['status'] : '');
                $scope.confirmPayment = false;
                $('#paymentMdl').modal('hide');
                $("#paymentForm").trigger('reset'); //jquery
                $('#fund-trn-spinner').css('display', 'none');
                $('#fund-trn-btn').prop('disabled', false);

                $('#paymentMdl').modal('hide');
                    
                $("#paymentForm").trigger('reset'); //jquery
                $('#mpin').val('');
            });
        }
    }

    $scope.sum = (num1, num2) => {
        return Number(num1) + Number(num2);
    }

    $scope.sub = (num1, num2) => {
        return Number(num1) - Number(num2);
    }

    $scope.showSurchargeMdl = () => {
        $('#pymtSummMdl').modal('hide');
        $('#surchargeModal').modal('show');
    }

    $scope.showInvoice = () => {
        $('#surchargeModal').modal('hide');
        $('#invoiceModal').modal('show');
    }

    $scope.printInvoice = () => {
        window.print();
        $('#invoiceModal').modal('hide');
    }

    // Reset Payment modal close
    $('#invoiceModal').on('hidden.bs.modal', function() {
        $scope.resetScopeVars();
    });

    $scope.resetScopeVars = () => {
        $scope.transactionData = {};
        $scope.transactionSum = {};
        $scope.singleRcpInfo = {};
        $scope.surCharge = "";
        $scope.transaction_fees = 10;
    }

    $scope.doVerification = (recepInfo) => {
        var apiUrl = $('#verify_bank_ac_api').val();
        submitForBeneficiary(apiUrl, 'verify_from_payment', recepInfo);
    }

    $scope.init();



});
// end of Angular Ctrl

function selected_Bank(bank_code) {

    sled = $('select[name=bank_code] option').filter(':selected').val()
    card_value = ifsc_Array[sled]; //gets jquery object at index i
    $('.ifsc').val(card_value);
}

$(function() {
    $('#select_bank_code').select2({
        placeholder: "Select",
        width: 'resolve',
        // allowClear: true
    });
});