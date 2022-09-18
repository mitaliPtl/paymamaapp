/**
 * File : addUser.js
 * 
 * This file contain the validation of add API setting form
 * 
 * Using validation plugin : jquery.validate.js
 * 
 * @author Alok Das
 */

$(function(){
	
	var addUserForm = $("#addPayGateSettingForm");
	
	var validator = addUserForm.validate({
		
		rules:{
			payment_gateway_name :{ required : true },
			working_key :{ required : true },
			charges :{ required : true },
			password : { required : true },
			password_confirmation : {required : true, equalTo: "#password"},
			// username : { required : true , remote:{	url:"checkPayGateSettingUsernameExists", type:"get"} },
			username : { required : true },
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
			payment_gateway_name :{ required : "This field is required" },
			working_key :{ required : "This field is required" },
			charges :{ required : "This field is required" },
			password : { required : "This field is required" },
			username : { required : "This field is required" , remote : "Username already taken" },
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
