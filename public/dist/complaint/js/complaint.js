$('.reply-btn').click(function() {
    var balReqId = $(this).val();
    $('#complaint_id').val(balReqId);
    $('#complaintReplyModal').modal('show');

})

$('.change-status-btn').click(function() {
    var comReqId = $(this).val();
    $('#complaint_id').val(comReqId);
    $('#change_complaint_id').val(comReqId);
    $('#chgStatusModal').modal('show');

})

$('.chnagetime-btn').click(function() {
    var comReqId = $(this).val();
    $('#change_time_complaint_id').val(comReqId);
    $('#changeTimeModel').modal('show');

})


//template
$('.add-template-btn').click(function() {
    // var comReqId = $(this).val();
    // $('#change_time_complaint_id').val(comReqId);
    $('#add-template').modal('show');

})

function edit_template() {

}

$('.edit-templt-btn').click(function() {
    var temp_index = $(this).val();
    all_template = $("#all_template").val();
    all_template = JSON.parse(all_template);


    $("#edit_temp_text").val(all_template[temp_index].template);
    $("#edit_default_time").val(all_template[temp_index].timing);
    $("#edit_temp_id").val(all_template[temp_index].template_id);

    $("#edit_temp_role option[value=" + all_template[temp_index].role_id + "]").prop('selected', true);
    $("#edit_temp_service option[value=" + all_template[temp_index].service_id + "]").prop('selected', true);

    $('#edit-template').modal('show');

})

$('.delete_templt-btn').click(function() {
    var temp_index = $(this).val();
    all_template = $("#all_template").val();
    all_template = JSON.parse(all_template);
    $("#delete_temp_id").val(all_template[temp_index].template_id);
    $('#daleteTemplateModel').modal('show');

})