/**
 * File : addUser.js
 * 
 * This file contain the validation of add Package setting form
 * 
 * Using validation plugin : jquery.validate.js
 * 
 * @author Alok Das
 */

$(function(){
	
	var addUserForm = $("#addPackageSettingForm");
	
	var validator = addUserForm.validate({
		
		rules:{
			package_name :{ required : true },
			retailer_cost :{ required : true },
			distributor_cost :{ required : true },
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
			package_name :{ required : "This field is required" },
			retailer_cost :{ required : "This field is required" },
			distributor_cost :{ required : "This field is required" },
		}
	});
});
