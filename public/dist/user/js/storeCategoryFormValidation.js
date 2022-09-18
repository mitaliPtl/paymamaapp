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
	
	var addStoreCategoryForm = $("#addStoreCategoryForm");
	
	var validator = addStoreCategoryForm.validate({
		
		rules:{
			store_category_name :{ required : true },
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
			store_category_name :{ required : "This field is required" },		
		}
	});
});
