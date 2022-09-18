var addSlidderBannerForm = $("#addSlidderBannerForm");

var validator = addSlidderBannerForm.validate({

    rules: {
        role_id: { required: true },
        platform: { required: true },
        location: { required: true },
        image_file_ids: { required: true },
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
        role_id: { required: "This field is required" },
        platform: { required: "This field is required" },
        location: { required: "This field is required" },
        image_file_ids: { required: "This field is required" },
    }
});