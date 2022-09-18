$("#from_date,#to_date").flatpickr();

$('#from_date').change(function () {
    $('#to_date').flatpickr({
        "minDate": new Date($('#from_date').val())
    });
});
