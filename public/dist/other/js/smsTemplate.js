var addEditModal = $('#smsTemplateAddModal');
var addformID = $('#addSmsTemplateForm');

// Edit Functionality
$('.edit-btn').click(function () {
    var id = $(this).val();
    $.get('/edit_sms_template?id=' + id, function (data) {

        $('#id').val(data.id);
        $('#sms_name').val(data.sms_name);
        $('#alias').val(data.alias);
        $('#template').val(data.template);

        addEditModal.modal('show');
        $('#alias').trigger('change');

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
    addformID.data('validator').resetForm();
    addformID[0].reset();
    $('.tags-info').tooltip('dispose');
});

addformID.submit(function () {
    if (addformID.valid()) {
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
                $.get('/change_sms_template_delete_status?id=' + rowId, function (data) {
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

$('#alias').change(function() {
    var allowedTags = $('option:selected', this).attr('data-allowed-tags');
    allowedTags = allowedTags ? JSON.parse(allowedTags).toString() : '';
    $('.tags-info').tooltip('dispose');
    if(allowedTags){
        $('.tags-info').tooltip(
            {
                title : "Allowed Tags : "+allowedTags
            }
        );
        $('.tags-info').tooltip('enable');
    }else{
        $('.tags-info').tooltip('disable');
    }   
});