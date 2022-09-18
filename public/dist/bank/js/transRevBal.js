$('#revertModal').on('show.bs.modal', function () {
    setTimeout(() => {
        var transferRevBalReqForm = $("#revertBalReqForm");

        var revBalvalidator = transferRevBalReqForm.validate({
            
            rules:{
                mpin: { required: true, remote: { url: "verifyUserMpin", type: "get" } },
                payment_type: { required: true },
                bank: { required: true },
                revert_amount: { required: true },
                amount_sent: { required: true },
                reference_id: { required: true },
                otp: { required: true ,remote: { url: "verifyRevertOTPMpin", type: "get", data: { recp_id: $('#rev_user_id').val() } }},
            },
            highlight: function(element) {
                $(element).closest('.form-group').addClass('has-error');
                $(element).closest('.form-control').css("border-color","#a94442");
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error');
                $(element).closest('.form-control').css("border-color","#00b8e6");
            },
            messages:{
                mpin: { required: "This field is required", remote: "Incorrect Mpin" },
                payment_type: { required: "This field is required" },
                bank: { required: "This field is required" },
                revert_amount: { required: "This field is required" },
                amount_sent: { required: "This field is required" },
                reference_id: { required: "This field is required" },
                otp: { required: "This field is required", remote: "Invalid OTP." },
            }
        });
    }, 100);
});

$('.revert-btn').click(function () {
    var data = $(this).val();
    data =  JSON.parse(data);
    $('#revertModal').modal('show');

    $('#rv_user_name').html(data['user_name'] + "(" + data['username'] + ")");
    $('#rv_user_mobile').html(data['mobile']);
    $('#rv_user_role').html(data['role_name']);
    $('#rv_user_balance').html(data['wallet_balance']);

    $('#rev_user_id').val(data['userId']);
    $('#rev_user_mobile').val(data['mobile']);
    $('#rev_user_role_id').val(data['roleId']);
})

$('.transfer-btn').click(function () {
    var data = $(this).val();
    data =  JSON.parse(data);
    $('#transferModal').modal('show');

    $('#tr_user_name').html(data['user_name'] + "(" + data['username'] + ")");
    $('#tr_user_mobile').html(data['mobile']);
    $('#tr_user_role').html(data['role_name']);
    $('#tr_user_balance').html(data['wallet_balance']);

    $('#trans_user_id').val(data['userId']);
    $('#trans_user_mobile').val(data['mobile']);
    $('#trans_user_role_id').val(data['roleId']);
});

$('.fake-revert').click(function(){
    var isRevFrmValid = $('#revertBalReqForm').valid();
    var recepUserId = $('#rev_user_id').val();
    var revAmount = $('#revert_amount').val();

    if(isRevFrmValid){
        $('.retailer-otp-div,.submit-btn').css('display','block');
        $('.fake-revert').css('display','none');

        $.ajax({
            type: 'GET',
            url: "send_revert_otp",
            data: $.param({ recep_id: recepUserId, revert_amount: revAmount }),
            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
            dataType: "json",
            success: function (response) {
               console.log(response);
            },
            error: function (response) {
            }
        });
    }
});


