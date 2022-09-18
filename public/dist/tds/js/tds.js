$('#role_id').change(function() {
    // alert();
    var role = $("#role_id option:selected").val();

    dtrt_users = JSON.parse($('#users').val());
    alusers = dtrt_users.filter(r_id => r_id.roleId == role);


    var optionsUser = ' <option selected="" disabled="" value="Select">Select</option>';
    for (var i = 0; i < alusers.length; i++) {
        optionsUser += "<option value='" + alusers[i].userId + "'>" + alusers[i].store_name + " (" + alusers[i].username + ")</option>";
    }
    $('select[name="user_id"]').empty().append(optionsUser);

});

$('#form-file-up-btn').click(function() {

    $('#fileUploadModal').modal('show');

})



$('.view-tds').click(function() {
    f_date = $("#from_date").val();
    t_date = $("#to_date").val();
    u_id = $(this).val();

    $("#from_date_form").val(f_date);
    $("#to_date_form").val(t_date);
    $("#user_id_form").val(u_id);
    $("#viewTDS").submit();

})


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
                $('#form-file-up-btn').removeClass('btn-warning').addClass('btn-success');
            }

            toastr.success('Image has been uploaded successfully');
        },
        error: function(data) {
            toastr.error("Failed");
        }
    });



});


function Export() {

    $("#recharge-report-table").table2excel({
        filename: "Table.xls"
    });
}

// File Upload Script ends