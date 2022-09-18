var addEditModal = $('#bankAcAddModal');
var addformID = $('#addBankAcForm');

var addBnkEditModal = $('#bankAddModal');
var addBnkformID = $('#addBankForm');

// Edit Functionality
$('.edit-btn').click(function () {
    var id = $(this).val();
    $.get('/edit_bank_account?id=' + id, function (data) {

        $('#id').val(data.id);
        $('#bank_name').val(data.bank_name);
        $('#account_no').val(data.account_no);
        $('#ifsc_code').val(data.ifsc_code);
        $('#address').val(data.address);

        addEditModal.modal('show');
    })
});

// Edit Functionality
$('.edit-bnk-btn').click(function () {
    var id = $(this).val();
    $.get('/edit_bank?id=' + id, function (data) {
        
        data_arr = jQuery.parseJSON( data );
        $('#bank_id').val(data_arr.BankID);
        $('#bank_name').val(data_arr['BANK_NAME']);
        $('#shortcode').val(data_arr.ShortCode);
        $('#ifsc_prefix').val(data_arr.ifsc_prefix);
        addBnkEditModal.modal('show');
    })
});

// On Modal show check mode and change the action text
addEditModal.on('show.bs.modal', function () {
    $id = $('#id').val();
    if ($id == 0) {
        $('.submit-btn').html('Add');
        $('.modal-action-name').html('Add');
    } else {
        $('.submit-btn').html('Update');
        $('.modal-action-name').html('Update');
    }
});

addBnkEditModal.on('show.bs.modal', function () {
    $id = $('#bank_id').val();
    if ($id == 0) {
        $('.submit-btn').html('Add');
        $('.modal-action-name').html('Add');
    } else {
        $('.submit-btn').html('Update');
        $('.modal-action-name').html('Update');
    }
});


// Reset Add Form on modal close
addEditModal.on('hidden.bs.modal', function () {
    $('#id').val(0);
    var addForm = $("#addBankAcForm");
    addformID.data('validator').resetForm();
    addformID[0].reset();
});

addBnkEditModal.on('hidden.bs.modal', function () {
    $('#bank_id').val(0);
    var addForm = $("#addBankForm");
    $('#addBankForm').trigger("reset");
    addBnkformID.data('validator').resetForm();

    addBnkformID[0].reset();
});

addformID.submit(function () {
    if (addformID.valid()) {
        $('.submit-btn').prop('disabled', true);
    }
});

addBnkformID.submit(function () {
    if (addBnkformID.valid()) {
        $('.submit-btn').prop('disabled', true);
    }
});

// Togglebutton status script starts 
// $('.status-btn').change(function () {

//     var activeStatus = $(this).prop('checked');
//     var rowId = $(this).data('id');

//     $.get('/change_service_typ_active_status?id=' + rowId + '&status=' + activeStatus, function (data) {
//         if (!data) {
//             $setStatus = activeStatus ? 'off' : 'on';
//             $('#status-btn_' + rowId).bootstrapToggle($setStatus);
//         }
//     });
// });

// Delete status script starts 
$('.delete-btn').click(function () {

    swal({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to recover this imaginary file!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                var rowId = $(this).data('id');
                $.get('/change_bank_ac_delete_status?id=' + rowId, function (data) {
                    if (data) {
                        swal("Deleted Successfully!", {
                            icon: "success",
                            buttons: false
                        });
                        setTimeout(() => {
                            location.reload(true);
                        }, 500);
                    }
                });
            }
        });
});



$('.upload-btn').click(function() {
    // console.log("hii");
    $('#acc_id').val($(this).val());
    $('#fileUploadModal').modal('show');

});

$('.upload-bnk-btn').click(function() {
    console.log($(this).val());
    $('#bank_id_logo').val($(this).val());
    $('#fileUploadModal').modal('show');

});



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
                $('#logo_id').val(data['id']);
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