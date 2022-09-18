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
	
	var filterForm = $("#filterForm");
	
	var validator = filterForm.validate({
		
		rules:{
			service_id :{ required : true },
			api_id :{ required : true },
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
			service_id :{ required : "This field is required" },
			api_id :{ required : "This field is required" },
		}
	});
});
