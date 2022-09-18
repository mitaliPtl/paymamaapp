$("#expense_date").flatpickr();

$('.add-expenses-btn').click(function() {
    // var comReqId = $(this).val();
    // $('#change_time_complaint_id').val(comReqId);
    $('#add-expense').modal('show');

})


// $('.edit-expense-btn').click(function() {
//     alert();
//     var expense_index = $(this).val();
//     all_expenses = $("#all_expenses").val();
//     all_expenses = JSON.parse(all_expenses);
//     expense = all_expenses[expense_index];
//     $("#edit_temp_text").val(expense.template);
//     // var comReqId = $(this).val();
//     // $('#change_time_complaint_id').val(comReqId);
//     $('#edit-expense').modal('show');

// })