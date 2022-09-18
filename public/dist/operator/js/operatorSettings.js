var addEditModal = $('#operatorSettingsAddModal');
var addformID = $('#addOperatorSettingsForm');

// On Modal show check mode and change the action text
addEditModal.on('show.bs.modal', function () {
    $operatorSettingsId = $('#operator_settings_id').val();
});

// Reset Add Form on modal close
addEditModal.on('hidden.bs.modal', function () {
    $('#operator_settings_id').val(0);
    addformID.data('validator').resetForm();
    addformID[0].reset();
    $('#operator_id').select2({
        placeholder: "Select",
        width: 'resolve'
        // allowClear: true
    });
});

addformID.submit(function () {
    if (addformID.valid()) {
        $('.form-submit-btn').prop('disabled', true);
    }
});

$(function() {
    $('#operator_id').select2({
        placeholder: "Select",
        width: 'resolve'
        // allowClear: true
    });
});

// Check for validation on change
$('#operator_id').change(function(){
    $(this).valid();
})