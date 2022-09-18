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
	
	var addUserForm = $("#addSmsGateSettingForm");
	
	var validator = addUserForm.validate({
		
		rules:{
			api_name :{ required : true },
			api_url :{ required : true },
			password : { required : true },
			password_confirmation : {required : true, equalTo: "#password"},
			// username : { required : true , remote:{	url:"checkSmsGateSettingUsernameExists", type:"get"} },
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
			api_name :{ required : "This field is required" },
			api_url :{ required : "This field is required" },
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
