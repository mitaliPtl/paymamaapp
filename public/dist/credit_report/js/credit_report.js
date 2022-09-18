$('#return-credit').click(function() {
    alert();
    var u_id = $(this).val();
    $('#user_id').val(u_id);
    $('#creditReturnModel').modal('show');

})

function rerurnCredit(u_id) {

    $('#user_id').val(u_id);
    $('#creditReturnModel').modal('show');
}