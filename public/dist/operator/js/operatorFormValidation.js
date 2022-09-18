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
	
	var addForm = $("#addOperatorForm");
	
	var validator = addForm.validate({
		
		rules:{
			operator_name :{ required : true },
			service_type :{ required : true },
			// helpline_no :{ minlength : 10, maxlength:10 },
			helpline_no :{ minlength : 0 },
			// operator_code : { required : true , remote:{	url:"checkOperatorCodeExists", type:"get"} },
			operator_code : { required : true },
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
			operator_name :{ required : "This field is required" },
			service_type :{ required : "This field is required" },
			operator_code : { required : "This field is required" , remote : "Operator Code already taken" },
		}
	});
});
