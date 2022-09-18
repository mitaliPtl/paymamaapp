// On change of service type drop down
$('#service_id,#api_id').change(function () {
    $('#filter-submit-btn').trigger('click');
});

// Onclick of save operator details
$('.save-op-dtls-btn').click(function () {
    var operatorId = $(this).data('operator-id');
    var opDtlsId = $(this).data('op-details-id');
    var apiSettingId = $('#api_id').val();
    var serviceTypeId = $('#service_id').val();
    var operator_code = $('#operator_code_' + operatorId).val();
    if ($("#filterForm").valid() && operator_code) {
        // console.log(opDtlsId, operatorId, Number(apiSettingId), Number(serviceTypeId), operator_code);
        $.get('/save_op_details?api_operator_id=' + opDtlsId + '&operator_id=' + operatorId + '&api_id=' + Number(apiSettingId) + '&service_id=' + Number(serviceTypeId) + '&operator_code=' + operator_code, function (data) {
            if (data) {
                toastr.success('API Operator Details Saved Successfully!', 'Success');
                location.reload();
            }
        })
    }

});