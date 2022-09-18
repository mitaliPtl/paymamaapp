$(() => {

    var paymenrForm = $("#paymentForm");

    var validator = paymenrForm.validate({

        rules: {
            transaction_type: { required: true },
            transaction_amount: { required: true, digits: true },
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
            transaction_type: { required: "This field is required" },
            transaction_amount: { required: "This field is required" },
        }
    });

    var sendMobForm = $("#senderMobForm");

    var validator = sendMobForm.validate({

        rules: {
            sender_mobile_number: { required: true, digits: true, minlength: 10, maxlength: 10 },
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
            sender_mobile_number: { required: "This field is required" },
        }
    });

    var senderRegForm = $("#senderRegForm");

    var validator = senderRegForm.validate({

        rules: {
            first_name: { required: true },
            last_name: { required: true },
            dob: { required: true },
            pincode: { required: true, digits: true },
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
            first_name: { required: "This field is required" },
            last_name: { required: "This field is required" },
            dob: { required: "This field is required" },
            pincode: { required: "This field is required" },
        }
    });

});