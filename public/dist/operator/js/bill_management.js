$('.upload-btn').click(function() {
    console.log("hii");
    $('#biller_id').val($(this).val());
    $('#fileUploadModal').modal('show');

});

$('.custom-param-btn').click(function() {
    console.log("hii");
    // $('#biller_id').val($(this).val());
    $('#updateCustomParamModel').modal('show');

});

function customParams(cutparams, status, biller_id){
    $('.custom_param_status')[0].checked = false;
    $('#input_params').text('');
    $('#input_params_id').val('');
    console.log(cutparams);
    console.log(status);
    $('#input_params').text(cutparams);
    $('#input_params_id').val(biller_id);
    if(status == 'Yes'){
        // $('#custom_param_status').prop('checked', true);
        $('.custom_param_status')[0].checked = true;
        console.log("inside");
    }
    $('#updateCustomParamModel').modal('show');
}


$('#fileUploadForm').submit(function(e) {

    e.preventDefault();

    var formData = new FormData(this);

    $.ajax({
        type: 'POST',
        url: "upload-file",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: (data) => {
            this.reset();
            $('#chooseFile').val('');
            $('.custom-file-label').html('Select File');
            $('#fileUploadModal').modal('hide');
            if (data) {
                $('#uploaded_file_id').val(data['id']);
                // $('#add-offersnotice').modal('show'); // To be changed
                $('#uploadBillerForm').submit();
                $('#form-file-up-btn').removeClass('btn-warning').addClass('btn-success');
            }

            toastr.success('Image has been uploaded successfully');
        },
        error: function(data) {
            toastr.error("Failed");
        }
    });



});