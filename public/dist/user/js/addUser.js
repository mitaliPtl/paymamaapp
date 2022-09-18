/**
 * File : addUser.js
 * 
 * This file contain the validation of add user form
 * 
 * Using validation plugin : jquery.validate.js
 * 
 * @author Alok Das
 */

$(function () {

	var addUserForm = $("#addUserForm");

	var validator = addUserForm.validate({

		rules: {
			first_name: { required: true },
			email: { required: true, email: true, remote: { url: "/checkUserValueExists", type: "get",data: {column: 'email' } } },
			password: { required: true },
			password_confirmation: { required: true, equalTo: "#password" },
			mobile: { required: true, digits: true, minlength:10,maxlength:10, remote: { url: "/checkUserValueExists", type: "get",data: {column: 'mobile' } } },
			username: { required: true, remote: { url: "/checkUserValueExists", type: "get",data: { column: 'username' } } },
			roleId: { required: true },
			mpin: { required: true, minlength:4,maxlength:4 },
			last_name: { required: true },
			parent_role_id: { required: true },
			package_id: { required: true },
			state_id: { required: true },
			district_id: { required: true },
			address: { required: true },
			zip_code: { required: true },
			parent_user_id: { required: true },
		},
		highlight: function (element) {
			$(element).closest('.form-group').addClass('has-error');
			$(element).closest('.form-control').css("border-color", "#a94442");
		},
		unhighlight: function (element) {
			$(element).closest('.form-group').removeClass('has-error');
			$(element).closest('.form-control').css("border-color", "#00b8e6");
		},
		messages: {
			first_name: { required: "This field is required" },
			email: { required: "This field is required", email: "Please enter valid email address", remote: "Email already taken" },
			password: { required: "This field is required" },
			first_name: { required: "This field is required", equalTo: "Please enter same password" },
			mobile: { required: "This field is required", digits: "Please enter numbers only", remote: "Mobile No. already taken" },
			username: { required: "This field is required", remote: "Username already taken" },
			roleId: { required: "This field is required" },
			mpin: { required: "This field is required", digits: "Please enter numbers only"},
			last_name: { required: "This field is required" },
			parent_role_id: { required: "This field is required" },
			package_id: { required: "This field is required" },
			state_id: { required: "This field is required" },
			district_id: { required: "This field is required" },
			address: { required: "This field is required" },
			zip_code: { required: "This field is required" },
			parent_user_id: { required: "This field is required" },
		}
	});

	var addUserForm = $("#editUserForm");
	var userId = $('#user_id').val();

	var validator = addUserForm.validate({

		rules: {
			first_name: { required: true },
			email: { required: true, email: true },
			email: { required: true, email: true, remote: { url: "/checkUserValueExists", type: "get", data: { id: userId, column: 'email' } } },
			password: { required: true },
			password_confirmation: { required: true, equalTo: "#password" },
			// mobile: { required: true, digits: true },
			mobile: { required: true, digits: true, minlength:10,maxlength:10, remote: { url: "/checkUserValueExists", type: "get", data: { id: userId, column: 'mobile' } } },
			username: { required: true },
			username: { required: true, remote: { url: "/checkUserValueExists", type: "get", data: { id: userId, column: 'username' } } },
			roleId: { required: true },
			mpin: { required: true, minlength:4,maxlength:4 },
			last_name: { required: true },
			parent_role_id: { required: true },
			package_id: { required: true },
			state_id: { required: true },
			district_id: { required: true },
			address: { required: true },
			zip_code: { required: true },
			parent_user_id: { required: true },
		},
		highlight: function (element) {
			$(element).closest('.form-group').addClass('has-error');
			$(element).closest('.form-control').css("border-color", "#a94442");
		},
		unhighlight: function (element) {
			$(element).closest('.form-group').removeClass('has-error');
			$(element).closest('.form-control').css("border-color", "#00b8e6");
		},
		messages: {
			first_name: { required: "This field is required" },
			email: { required: "This field is required", email: "Please enter valid email address", remote: "Email already taken" },
			password: { required: "This field is required" },
			first_name: { required: "This field is required", equalTo: "Please enter same password" },
			mobile: { required: "This field is required", digits: "Please enter numbers only", remote: "Mobile No. already taken" },
			username: { required: "This field is required", remote: "Username already taken" },
			roleId: { required: "This field is required" },
			mpin: { required: "This field is required", digits: "Please enter numbers only"},
			last_name: { required: "This field is required" },
			parent_role_id: { required: "This field is required" },
			package_id: { required: "This field is required" },
			state_id: { required: "This field is required" },
			district_id: { required: "This field is required" },
			address: { required: "This field is required" },
			zip_code: { required: "This field is required" },
			parent_user_id: { required: "This field is required" },
		}
	});
	
	var addUserForm = $("#addUserSAForm");

	var validator = addUserForm.validate({

		rules: {
			first_name: { required: true },
			email: { required: true, email: true, remote: { url: "/checkUserValueExists", type: "get",data: {column: 'email' } } },
			password: { required: true },
			password_confirmation: { required: true, equalTo: "#password" },
			mobile: { required: true, digits: true, minlength:10,maxlength:10, remote: { url: "/checkUserValueExists", type: "get",data: {column: 'mobile' } } },
			username: { required: true, remote: { url: "/checkUserValueExists", type: "get",data: { column: 'username' } } },
			roleId: { required: true },
			mpin: { required: true, minlength:4,maxlength:4 },
			last_name: { required: true },
			
		},
		highlight: function (element) {
			$(element).closest('.form-group').addClass('has-error');
			$(element).closest('.form-control').css("border-color", "#a94442");
		},
		unhighlight: function (element) {
			$(element).closest('.form-group').removeClass('has-error');
			$(element).closest('.form-control').css("border-color", "#00b8e6");
		},
		messages: {
			first_name: { required: "This field is required" },
			email: { required: "This field is required", email: "Please enter valid email address", remote: "Email already taken" },
			password: { required: "This field is required" },
			first_name: { required: "This field is required", equalTo: "Please enter same password" },
			mobile: { required: "This field is required", digits: "Please enter numbers only", remote: "Mobile No. already taken" },
			username: { required: "This field is required", remote: "Username already taken" },
			roleId: { required: "This field is required" },
			mpin: { required: "This field is required", digits: "Please enter numbers only"},
			last_name: { required: "This field is required" },
			parent_role_id: { required: "This field is required" },
		}
	});

	var addUserForm = $("#editUserSAForm");
	var userId = $('#user_id').val();

	var validator = addUserForm.validate({

		rules: {
			first_name: { required: true },
			email: { required: true, email: true },
			email: { required: true, email: true, remote: { url: "/checkUserValueExists", type: "get", data: { id: userId, column: 'email' } } },
			password: { required: true },
			password_confirmation: { required: true, equalTo: "#password" },
			// mobile: { required: true, digits: true },
			mobile: { required: true, digits: true, minlength:10,maxlength:10, remote: { url: "/checkUserValueExists", type: "get", data: { id: userId, column: 'mobile' } } },
			username: { required: true },
			username: { required: true, remote: { url: "/checkUserValueExists", type: "get", data: { id: userId, column: 'username' } } },
			roleId: { required: true },
			mpin: { required: true, minlength:4,maxlength:4 },
			last_name: { required: true },
			
		},
		highlight: function (element) {
			$(element).closest('.form-group').addClass('has-error');
			$(element).closest('.form-control').css("border-color", "#a94442");
		},
		unhighlight: function (element) {
			$(element).closest('.form-group').removeClass('has-error');
			$(element).closest('.form-control').css("border-color", "#00b8e6");
		},
		messages: {
			first_name: { required: "This field is required" },
			email: { required: "This field is required", email: "Please enter valid email address", remote: "Email already taken" },
			password: { required: "This field is required" },
			first_name: { required: "This field is required", equalTo: "Please enter same password" },
			mobile: { required: "This field is required", digits: "Please enter numbers only", remote: "Mobile No. already taken" },
			username: { required: "This field is required", remote: "Username already taken" },
			roleId: { required: "This field is required" },
			mpin: { required: "This field is required", digits: "Please enter numbers only"},
			last_name: { required: "This field is required" },
			
		}
	});

});
