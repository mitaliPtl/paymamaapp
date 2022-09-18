var addEditModal = $('#payGateSettingAddModal');
var chPwdModal = $('#payGateSettingChPwdModal');
var addformID = $('#addPayGateSettingForm');
var chPwdformID = $('#chPwdForm');

// Edit Functionality
$('.edit-btn').click(function () {
    var id = $(this).val();
    $.get('/edit_pay_gate_setting?id=' + id, function (data) {

        $('#id').val(data.id);
        $('#payment_gateway_name').val(data.payment_gateway_name);
        $('#working_key').val(data.working_key);
        $('#username').val(data.username);
        $('#charges').val(data.charges);

        $('#pwdRow').css('display', 'none');
        addEditModal.modal('show');
    })
});

// On Modal show check mode and change the action text
addEditModal.on('show.bs.modal', function () {
    $id = $('#id').val();
    if ($id == 0) {
        $('.form-submit-btn').html('Add');
        $('.modal-action-name').html('Add');
    } else {
        $('.form-submit-btn').html('Update');
        $('.modal-action-name').html('Update');
    }
});

// Reset Add Form on modal close
addEditModal.on('hidden.bs.modal', function () {
    $('#id').val(0);
    addformID.data('validator').resetForm();
    addformID[0].reset();
    $('#pwdRow').css('display', '');
});

// Change Password Functionality
$('.ch-pwd_btn').click(function () {
    var id = $(this).val();
    $.get('/edit_pay_gate_setting?id=' + id, function (data) {

        $('#ch_pwd_id').val(data.id);

        $('#pwdRow').css('display', 'none');
        chPwdModal.modal('show');
    })
});

// Reset Add Form on modal close
chPwdModal.on('hidden.bs.modal', function () {
    $('#ch_pwd_id').val(0);
    chPwdformID.data('validator').resetForm();
    chPwdformID[0].reset();
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

    $.get('/change_pay_gate_set_active_status?id=' + rowId + '&status=' + activeStatus, function (data) {
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
                $.get('/change_pay_gate_set_delete_status?id=' + rowId, function (data) {
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