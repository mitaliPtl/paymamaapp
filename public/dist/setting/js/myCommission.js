// On change of service type drop down
$('#service_id, #pkg_id').change(function () {
    $('#filter-submit-btn').trigger('click');
});