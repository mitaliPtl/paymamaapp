/**
 * File : addUser.js
 * 
 * This file contain the validation of add Operator form
 * 
 * Using validation plugin : jquery.validate.js
 * 
 * @author Alok Das
 */

$(function(){
	
	var addForm = $("#addOperatorSettingsForm");
	
	var validator = addForm.validate({
		rules:{
			operator_id :{ required : true },
			default_api_id :{ required : true },
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
			operator_id :{ required : "This field is required" },
			default_api_id :{ required : "This field is required" },
		}
	});
});
