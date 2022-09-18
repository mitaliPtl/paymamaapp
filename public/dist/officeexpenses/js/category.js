$('.add-category-btn').click(function() {
    // var comReqId = $(this).val();
    // $('#change_time_complaint_id').val(comReqId);
    $('#add-category').modal('show');

})


$('.edit-category-btn').click(function() {

    var cat_index = $(this).val();
    categories = $("#categories").val();
    categories = JSON.parse(categories);

    $("#edit_category_text").val(categories[cat_index].category);
    $("#edit_category_id").val(categories[cat_index].category_id);

    $('#edit-category').modal('show');

})

$('.delete-category-btn').click(function() {

    var cat_id = $(this).val();
    // categories = $("#categories").val();
    // categories = JSON.parse(categories);

    // $("#edit_category_text").val(categories[cat_index].category);
    $("#delete_category_id").val(cat_id);

    $('#delete-category').modal('show');

})