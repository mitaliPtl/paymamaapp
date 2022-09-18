/**
 * File : addUser.js
 * 
 * This file contain the validation of add Bank Account form
 * 
 * Using validation plugin : jquery.validate.js
 * 
 * @author Alok Das
 */

$(function(){
	
	var addUserForm = $("#addBankAcForm");
	
	var validator = addUserForm.validate({
		
		rules:{
			bank_name :{ required : true },
			ifsc_code :{ required : true },
			account_no :{ required : true }
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
			bank_name :{ required : "This field is required" },		
			ifsc_code :{ required : "This field is required" },		
			account_no :{ required : "This field is required" },		
		}
	});
});
