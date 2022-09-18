$('.add-offersnotice-btn').click(function() {
    // var comReqId = $(this).val();
    // $('#change_time_complaint_id').val(comReqId);
    $('#add-offersnotice').modal('show');

})


$('.edit-offersnotice-btn').click(function() {

    editoffersnotice_Form.reset();
    $('#edit_offersnotice_role').select2({
        placeholder: "Select",
        width: 'resolve',
        // allowClear: true
    });

    var values = '';
    var arr = [];
    var offersnotice_index = $(this).val();
    all_offersnotice = $("#all_offersnotice").val();
    all_offersnotice = JSON.parse(all_offersnotice);
    offersnotice = all_offersnotice[offersnotice_index];
    // console.log(offersnotice.notice_visible);
    // $("#edit_offersnotice_role option:selected").prop("selected", false);
    // $("#edit_offersnotice_role option:selected").removeAttr("selected");

    arr = offersnotice.notice_visible;
    var values = arr.join(',');

    $.each(values.split(","), function(i, e) {
        $("#edit_offersnotice_role option[value='" + e + "']").prop("selected", true);
    });



    // $("#edit_offersnotice_role option:contains(" + offersnotice.notice_visible + ")").attr("selected", true);

    $("#edit_offersnotice_type option:contains(" + offersnotice.notice_type + ")").attr("selected", true);
    $("#edit_title").val(offersnotice.notice_title);
    $("#edit_description").val(offersnotice.notice_description);
    $("#edit_offersnotice_id").val(offersnotice.notice_id);

    $('#edit-offersnotice').modal('show');

})



$('.delete-offersnotice-btn').click(function() {
    var offersnotice_index = $(this).val();
    all_offersnotice = $("#all_offersnotice").val();
    all_offersnotice = JSON.parse(all_offersnotice);
    offersnotice = all_offersnotice[offersnotice_index];
    console.log(offersnotice);
    $("#delete_offersnotice_id").val(offersnotice.notice_id);
    $('#daleteoffersnoticeModel').modal('show');

})

$('.view-offersnotice-btn').click(function() {
    var offersnotice_index = $(this).val();
    all_offersnotice = $("#offers").val();
    all_offersnotice = JSON.parse(all_offersnotice);
    offersnotice = all_offersnotice[offersnotice_index];
    console.log(offersnotice);
    // $('img[src="' + oldSrc + '"]').attr('src', newSrc);
    img = "{{ asset('" + offersnotice.image + "')}}";
    $("#image_id").attr("src", img);

    $("#view_offersnotice_id").val(offersnotice.notice_id);
    $('#viewoffersnoticeModel').modal('show');

})


$('#form-file-up-btn').click(function() {
        // var transferReqId = $(this).val();
        // $('#trans_req_id').val(transferReqId);

        $('#add-offersnotice').modal('hide');
        $('#fileUploadModal').modal('show');

    })
    // $('#file-upload-btn').click(function() {
    //     // var transferReqId = $(this).val();
    //     // $('#trans_req_id').val(transferReqId);
    //     $('#fileUploadModal').modal('hide');
    //     $('#add-offersnotice').modal('show');

// })

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
                $('#add-offersnotice').modal('show'); // To be changed
                $('#form-file-up-btn').removeClass('btn-warning').addClass('btn-success');
            }

            toastr.success('Image has been uploaded successfully');
        },
        error: function(data) {
            toastr.error("Failed");
        }
    });
});
// File Upload Script ends


$('#edit-form-file-up-btn').click(function() {
    // var transferReqId = $(this).val();
    // $('#trans_req_id').val(transferReqId);
    $('#edit-offersnotice').modal('hide');
    $('#edit-fileUploadModal').modal('show');

})

$('#edit-fileUploadForm').submit(function(e) {

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
            $('#edit-fileUploadModal').modal('hide');
            if (data) {
                $('#edit-uploaded_file_id').val(data['id']);
                $('#edit-offersnotice').modal('show'); // To be changed
                $('#edit-form-file-up-btn').removeClass('btn-warning').addClass('btn-success');
            }

            toastr.success('Image has been uploaded successfully');
        },
        error: function(data) {
            toastr.error("Failed");
        }
    });
});

$(function() {
    $('#offersnotice_role').select2({
        placeholder: "Select",
        width: 'resolve'
            // allowClear: true
    });

    $('#edit_offersnotice_role').select2({
        placeholder: "Select",
        width: 'resolve'
            // allowClear: true
    });
});
// File Upload Script ends