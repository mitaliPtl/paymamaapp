/**
 * File : addUser.js
 * 
 * This file contain the validation of add API Amount details form
 * 
 * Using validation plugin : jquery.validate.js
 * 
 * @author Alok Das
 */

$(function(){
	
	var addUserForm = $("#addApiAmountDetailsForm");
	
	var validator = addUserForm.validate({
		
		rules:{
			api_id :{ required : true },
			operator_id :{ required : true },
			amount :{ required : true },
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
			api_id :{ required : "This field is required" },
			operator_id :{ required : "This field is required" },
			amount :{ required : "This field is required" },
		}
	});

	var addUserForm = $("#chPwdForm");
	
	var validator = addUserForm.validate({
		
		rules:{
			
			ch_pwd_password : { required : true },
			ch_pwd_password_confirmation : {required : true, equalTo: "#ch_pwd_password"},
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
			ch_pwd_password : { required : "This field is required" },
		}
	});
});
