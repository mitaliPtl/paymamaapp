$("#from_date,#to_date").flatpickr();

$('#from_date').change(function () {
    $('#to_date').flatpickr({
        "minDate": new Date($('#from_date').val())
    });
});

$('#service_id,#state_id').change(function(){
    $('#filter-submit-btn').trigger('click');
});