var addEditModal = $('#packageSettingAddModal');
var chPwdModal = $('#packageSettingChPwdModal');
var addformID = $('#addPackageSettingForm');

// Edit Functionality
$('.edit-btn').click(function () {
    var package_id = $(this).val();
    $.get('/edit_package_setting?package_id=' + package_id, function (data) {

        $('#package_id').val(data.package_id);
        $('#package_name').val(data.package_name);
        $('#package_descr').val(data.package_descr);
        $('#retailer_cost').val(data.retailer_cost);
        $('#distributor_cost').val(data.distributor_cost);

        addEditModal.modal('show');
    })
});

// On Modal show check mode and change the action text
addEditModal.on('show.bs.modal', function () {
    $packageId = $('#package_id').val();
    if ($packageId == 0) {
        $('.form-submit-btn').html('Add');
        $('.modal-action-name').html('Add');
    } else {
        $('.form-submit-btn').html('Update');
        $('.modal-action-name').html('Update');
    }
});

// Reset Add Form on modal close
addEditModal.on('hidden.bs.modal', function () {
    $('#package_id').val(0);
    addformID.data('validator').resetForm();
    addformID[0].reset();
});

addformID.submit(function () {
    if (addformID.valid()) {
        $('.form-submit-btn').prop('disabled', true);
    }
});

// Togglebutton status script starts 
$('.status-btn').change(function () {

    var activeStatus = $(this).prop('checked');
    var rowId = $(this).data('id');

    $.get('/change_pack_setting_active_status?id=' + rowId + '&status=' + activeStatus, function (data) {
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
                $.get('/change_pack_setting_delete_status?id=' + rowId, function (data) {
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