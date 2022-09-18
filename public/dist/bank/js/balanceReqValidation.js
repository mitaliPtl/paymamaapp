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
	
	var transBalForm = $("#transBalForm");
	
	var validator = transBalForm.validate({
		
		rules:{
			mpin: { required: true, remote: { url: "verifyUserMpin", type: "get" } },
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
		}
	});

	var addBalanceReqForm = $("#addBalanceReqForm");
	
	var validator = addBalanceReqForm.validate({
		
		rules:{
			bank: { required: true},
			amount: { required: true},
			reference_id: { required: true},
			reference_id: { required: true},
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
            bank: { required: "This field is required"},
            amount: { required: "This field is required"},
            reference_id: { required: "This field is required"},
            reference_id: { required: "This field is required"},
		}
	});
});
