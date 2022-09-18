/**
 * File : addUser.js
 * 
 * This file contain the validation of add Service Type form
 * 
 * Using validation plugin : jquery.validate.js
 * 
 * @author Alok Das
 */

$(function () {

    var phoneRechargeForm = $("#mobileRechargeForm");

    var validator = phoneRechargeForm.validate({

        rules: {
            service_type: { required: true },
            operatorID: { required: true },
            mobileNumber: { required: true, digits: true, minlength: 10, maxlength: 10 },
            amount: { required: true, digits: true},
        },
        highlight: function (element) {
            $(element).closest('.form-group').addClass('has-error');
            $(element).closest('.form-control').css("border-color", "#a94442");
        },
        unhighlight: function (element) {
            $(element).closest('.form-group').removeClass('has-error');
            $(element).closest('.form-control').css("border-color", "#00b8e6");
        },
        messages: {
            service_type: { required: "This field is required" },
            operatorID: { required: "This field is required" },
            mobileNumber: { required: "This field is required" },
            amount: { required: "This field is required" },
        }
    });

    var dthRechargeForm = $("#dthRechargeForm");

    var validator = dthRechargeForm.validate({

        rules: {
            operatorID: { required: true },
            mobileNumber: { required: true },
            amount: { required: true, digits: true},
        },
        highlight: function (element) {
            $(element).closest('.form-group').addClass('has-error');
            $(element).closest('.form-control').css("border-color", "#a94442");
        },
        unhighlight: function (element) {
            $(element).closest('.form-group').removeClass('has-error');
            $(element).closest('.form-control').css("border-color", "#00b8e6");
        },
        messages: {
            operatorID: { required: "This field is required" },
            mobileNumber: { required: "This field is required" },
            amount: { required: "This field is required" },
        }
    });

    var electricityPaymentForm = $("#electricityPaymentForm");

    var validator = electricityPaymentForm.validate({

        rules: {
            bill_type: { required: true },
            state_id: { required: true },
        },
        highlight: function (element) {
            $(element).closest('.form-group').addClass('has-error');
            $(element).closest('.form-control').css("border-color", "#a94442");
        },
        unhighlight: function (element) {
            $(element).closest('.form-group').removeClass('has-error');
            $(element).closest('.form-control').css("border-color", "#00b8e6");
        },
        messages: {
            bill_type: { required: "This field is required" },
            state_id: { required: "This field is required" },
        }
    });

    var fastTagPaymentForm = $("#fastTagPaymentForm");

    var validator = fastTagPaymentForm.validate({

        rules: {
            bank_id: { required: true },
            vehical_number: { required: true }
        },
        highlight: function (element) {
            $(element).closest('.form-group').addClass('has-error');
            $(element).closest('.form-control').css("border-color", "#a94442");
        },
        unhighlight: function (element) {
            $(element).closest('.form-group').removeClass('has-error');
            $(element).closest('.form-control').css("border-color", "#00b8e6");
        },
        messages: {
            bank_id: { required: "This field is required" },
            vehical_number: { required: "This field is required" },
        }
    });

    var loanPaymentForm = $("#loanPaymentForm");

    var validator = loanPaymentForm.validate({

        rules: {
            lender_id: { required: true },
            loan_number: { required: true }
        },
        highlight: function (element) {
            $(element).closest('.form-group').addClass('has-error');
            $(element).closest('.form-control').css("border-color", "#a94442");
        },
        unhighlight: function (element) {
            $(element).closest('.form-group').removeClass('has-error');
            $(element).closest('.form-control').css("border-color", "#00b8e6");
        },
        messages: {
            lender_id: { required: "This field is required" },
            loan_number: { required: "This field is required" },
        }
    });

    var cdtCrdPaymentForm = $("#cdtCrdPaymentForm");

    var validator = cdtCrdPaymentForm.validate({

        rules: {
            cdt_crd_number: { required: true }
        },
        highlight: function (element) {
            $(element).closest('.form-group').addClass('has-error');
            $(element).closest('.form-control').css("border-color", "#a94442");
        },
        unhighlight: function (element) {
            $(element).closest('.form-group').removeClass('has-error');
            $(element).closest('.form-control').css("border-color", "#00b8e6");
        },
        messages: {
            cdt_crd_number: { required: "This field is required" },
        }
    });
});
