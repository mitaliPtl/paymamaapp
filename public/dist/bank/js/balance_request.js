$("#from_date,#to_date").flatpickr();

$('#from_date').change(function () {
    $('#to_date').flatpickr({
        "minDate": new Date($('#from_date').val())
    });
});


$('.reply-btn').click(function () {
    var balReqId = $(this).val();
    $('#bal_req_id').val(balReqId);
    $('#requestReplyModal').modal('show');

})

$('.transfer-btn').click(function () {
    var transferReqId = $(this).val();
    $('#trans_req_id').val(transferReqId);
    $('#balTransModal').modal('show');

})

$('#form-file-up-btn').click(()=>{
    $('#balanceReqModal').modal('hide');
    $('#fileUploadModal').modal('show');
});

$('#fileUploadForm').submit(function (e) {

    e.preventDefault();

    var formData = new FormData(this);

    $.ajax({
        type: 'POST',
        url: "upload-file",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: (data) => {
            this.reset();
            $('#chooseFile').val('');
            $('.custom-file-label').html('Select File');
            $('#fileUploadModal').modal('hide');
            if (data){
                $('#uploaded_file_id').val(data['id']);
                $('#balanceReqModal').modal('show'); // To be changed
                $('#form-file-up-btn').removeClass('btn-warning').addClass('btn-success');
            }
                
            toastr.success('Image has been uploaded successfully');
        },
        error: function (data) {
            toastr.error("Failed");
        }
    });
});
// File Upload Script ends

$('#bank').change(function(){
    var bankVal = $(this).val();
    
    if(bankVal == "QR_CODE"){
        $('#qr-code-div').css('display','block');
        $('#receiptFile-div').css('display','none');
    }else{
        $('#receiptFile-div').css('display','block');
        $('#qr-code-div').css('display','none');

    }
});
