/**
 * File : addUser.js
 * 
 * This file contain the validation of add Service Type form
 * 
 * Using validation plugin : jquery.validate.js
 * 
 * @author Alok Das
 */

$(function(){
	
	var addSmsTemplateForm = $("#addSmsTemplateForm");
	
	var validator = addSmsTemplateForm.validate({
		
		rules:{
			sms_name :{ required : true },
			alias :{ required : true },
			template :{ required : true },
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
			sms_name :{ required : "This field is required" },		
			alias :{ required : "This field is required" },		
			template :{ required : "This field is required" },		
		}
	});
});
