var addEditModal = $('#storeCategoryAddModal');
var addformID = $('#addStoreCategoryForm');

// Edit Functionality
$('.edit-btn').click(function () {
    var id = $(this).val();
    $.get('/edit_store_category?id=' + id, function (data) {

        $('#id').val(data.id);
        $('#store_category_name').val(data.store_category_name);

        addEditModal.modal('show');
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

// Reset Add Form on modal close
addEditModal.on('hidden.bs.modal', function () {
    $('#id').val(0);
    var addForm = addformID;
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

    $.get('/change_store_category_active_status?id=' + rowId + '&status=' + activeStatus, function (data) {
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
                $.get('/change_store_category_delete_status?id=' + rowId, function (data) {
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