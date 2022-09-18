$(function () {

    var chgPwdForm = $("#chgPwdForm");
    var userId = $('#loggedUserId').val();

    var validator = chgPwdForm.validate({

        rules: {
            password: { required: true },
            verification_otp: { required: true, minlength: 6, digits: true, remote: { url: "/verify_sent_otp", type: "get", data: { id: userId } } },
            password_confirmation: { required: true, equalTo: "#profile_password" },
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
            password: { required: "This field is required" },
            verification_otp: { required: "This field is required", digits: "Please enter numbers only", remote: "Invalid OTP" },
        }
    });


    var chgMpinForm = $('#chgMpinForm');

    var validator = chgMpinForm.validate({

        rules: {
            mpin: { required: true, minlength:4,maxlength:4 },
            verification_otp: { required: true, minlength: 6, digits: true, remote: { url: "/verify_sent_otp", type: "get", data: { id: userId } } },
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
			mpin: { required: "This field is required", digits: "Please enter numbers only"},
            verification_otp: { required: "This field is required", digits: "Please enter numbers only", remote: "Invalid OTP" },
        }
    });

    var updateKycForm = $('#updateKycForm');

    var validator = updateKycForm.validate({

        rules: {
            pan_front_file_id: { required: true },
            aadhar_front_file_id: { required: true },
            aadhar_back_file_id: { required: true },
            photo_front_file_id: { required: true },
            photo_inner_file_id: { required: true },
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
			pan_front_file_id: { required: "This field is required"},
			aadhar_front_file_id: { required: "This field is required"},
			aadhar_back_file_id: { required: "This field is required"},
			photo_front_file_id: { required: "This field is required"},
			photo_inner_file_id: { required: "This field is required"},
        }
    });

});