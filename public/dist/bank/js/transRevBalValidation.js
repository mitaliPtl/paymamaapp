/**
 * File : addUser.js
 * 
 * This file contain the validation of add Operator Details form
 * 
 * Using validation plugin : jquery.validate.js
 * 
 * @author Alok Das
 */

$(function(){
	
	var transferRevBalReqForm = $("#transferBalReqForm");
	
	var validator = transferRevBalReqForm.validate({
		
		rules:{
			mpin: { required: true, remote: { url: "verifyUserMpin", type: "get" } },
			payment_type: { required: true },
			bank: { required: true },
			amount: { required: true },
			reference_id: { required: true },
		},
		highlight: function(element) {
            $(element).closest('.form-group').addClass('has-error');
            $(element).closest('.form-control').css("border-color","#a94442");
		},
		unhighlight: function(element) {
			$(element).closest('.form-group').removeClass('has-error');
			$(element).closest('.form-control').css("border-color","#00b8e6");
		},
		messages:{
            mpin: { required: "This field is required", remote: "Incorrect Mpin" },
            payment_type: { required: "This field is required" },
            bank: { required: "This field is required" },
            amount: { required: "This field is required" },
            reference_id: { required: "This field is required" },
		}
    });
    
    // var transferRevBalReqForm = $("#revertBalReqForm");
	// var recepUserId = $('#rev_user_id').val();
	// console.log(recepUserId);

	// var revBalvalidator = transferRevBalReqForm.validate({
		
	// 	rules:{
	// 		mpin: { required: true, remote: { url: "verifyUserMpin", type: "get" } },
	// 		payment_type: { required: true },
    //         bank: { required: true },
	// 		revert_amount: { required: true },
	// 		amount_sent: { required: true },
	// 		reference_id: { required: true },
	// 		user_id: { required: true },
	// 		otp: { required: true ,remote: { url: "verifyRevertOTPMpin", type: "get", data: { recp_id: $('#rev_user_id').val() } }},
	// 	},
	// 	highlight: function(element) {
    //         $(element).closest('.form-group').addClass('has-error');
    //         $(element).closest('.form-control').css("border-color","#a94442");
	// 	},
	// 	unhighlight: function(element) {
	// 		$(element).closest('.form-group').removeClass('has-error');
	// 		$(element).closest('.form-control').css("border-color","#00b8e6");
	// 	},
	// 	messages:{
    //         mpin: { required: "This field is required", remote: "Incorrect Mpin" },
    //         payment_type: { required: "This field is required" },
    //         bank: { required: "This field is required" },
    //         revert_amount: { required: "This field is required" },
    //         amount_sent: { required: "This field is required" },
	// 		reference_id: { required: "This field is required" },
    //         otp: { required: "This field is required", remote: "Invalid OTP." },
	// 	}
	// });
});
