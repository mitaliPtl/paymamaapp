var addEditModal = $('#serviceTypeAddModal');
var addformID = $('#addServiceTypeForm');

// Edit Functionality
$('.edit-btn').click(function () {
    var service_id = $(this).val();
    $.get('/edit_service_type?service_id=' + service_id, function (data) {

        $('#service_id').val(data.service_id);
        $('#service_name').val(data.service_name);
        $('#alias').val(data.alias);
        $('#service_dtls').val(data.service_dtls);

        addEditModal.modal('show');
    })
});

// On Modal show check mode and change the action text
addEditModal.on('show.bs.modal', function () {
    $serviceId = $('#service_id').val();
    if ($serviceId == 0) {
        $('.submit-btn').html('Add');
        $('.modal-action-name').html('Add');
    } else {
        $('.submit-btn').html('Update');
        $('.modal-action-name').html('Update');
    }
});

// Reset Add Form on modal close
addEditModal.on('hidden.bs.modal', function () {
    $('#service_id').val(0);
    var addForm = $("#addServiceTypeForm");
    addformID.data('validator').resetForm();
    addformID[0].reset();
});

addformID.submit(function () {
    if (addformID.valid()) {
        $('.submit-btn').prop('disabled', true);
    }
});

// Togglebutton status script starts 
$('.status-btn').change(function () {

    var activeStatus = $(this).prop('checked');
    var rowId = $(this).data('id');

    $.get('/change_service_typ_active_status?id=' + rowId + '&status=' + activeStatus, function (data) {
        if (!data) {
            $setStatus = activeStatus ? 'off' : 'on';
            $('#status-btn_' + rowId).bootstrapToggle($setStatus);
        }
    });
});

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
                $.get('/change_service_typ_delete_status?id=' + rowId, function (data) {
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