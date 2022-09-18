setTimeout(() => {
    if ($('#session_success_msg').val()) {
        toastr.success($('#session_success_msg').val(), 'Success');
    } else if ($('#session_error_msg').val()) {
        toastr.error($('#session_error_msg').val(), 'Failure');
    }
}, 10);

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Get Url parameter value
var getUrlParam = function getUrlParam(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
};

// Set table with the below class
if ($("table").hasClass("is-data-table")) {
    $('.is-data-table').DataTable({
        "pageLength": 50,
        "stateSave": true,
        "lengthMenu": [10, 25, 50, 100],
    }).on('page.dt', function () {
        $('.preloader').css('display', '');
        location.reload();
    }).on('length.dt', function () {
        $('.preloader').css('display', '');
        location.reload();
    });
}


// Apply Toggle button for table status column
if ($("table").hasClass("status-btn")) {
    $('.status-btn').bootstrapToggle({
        on: 'Active',
        off: 'Inactive',
        width: '80%',
        size: 'small'
    });
}

// Enable input mask here
if ($('input').hasClass('input-mask')) {
    $('.credit-card').inputmask("9999 9999 9999 9999", { "clearIncomplete": true });
}

// File Upload Script starts
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$('#image').change(function () {

    let reader = new FileReader();

    reader.onload = (e) => {

        $('#image_preview_container').attr('src', e.target.result);
    }

    reader.readAsDataURL(this.files[0]);

});


$('#kycFileUploadForm').submit(function (e) {

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
            $('#chooseKycFile').val('');
            $('.custom-file-label').html('Select File');
            $('#kycFileUploadMdl').modal('hide');
            var btnData = $('#btn-data').val();
            var fileData = $('#file-data').val();
            var imgData = $('#img-data').val();

            if (data) {
                $('#' + btnData).removeClass('btn-light').addClass('btn-success');
                $('#' + fileData).val(data['id']);
                $('#' + imgData).attr('src', data['file_path']);
                $('#' + imgData).attr('alt', data['name']);
            }

            toastr.success('Image has been uploaded successfully');
        },
        error: function (data) {
            toastr.error("Failed");
        }
    });
});
// File Upload Script ends

var otpPwdSent = false;
var otpMpinSent = false;
// Change Password Flow
$('#chg-pwd-btn').click(function () {
    otpPwdSent = false;
    $('#chgPwdModal').modal('show');
});

// Change Mpin Flow
$('#chg-mpin-btn').click(function () {
    otpMpinSent = false;
    $('#chgMpinModal').modal('show');
});

$('#chgPwdForm #profile_password_confirmation').keyup(function () {
    if ($('#chgPwdForm').valid()) {
        $('#pwd-otp-verify-div').css('display', 'block');
        $loggedUserId = $('#loggedUserId').val();
        $mobileNo = $('#loggedUserMobileNo').val();
        if (otpPwdSent == false) {
            sendOTP($loggedUserId, $mobileNo);
            otpPwdSent = true;
        }
    } else {
        $('#pwd-otp-verify-div').css('display', 'none');
    }
});

var otpMpinSent = false;
$('#chgMpinForm #new-mpin').focusin(function () {
    $('#mpin-otp-verify-div').css('display', 'block');
    $loggedUserId = $('#loggedUserId').val();
    $mobileNo = $('#loggedUserMobileNo').val();
    if (otpMpinSent == false) {
        sendOTP($loggedUserId, $mobileNo);
        otpMpinSent = true;
    }
});

// Send OTP here 
var sendOTP = (loggedUserId, mobile) => {
    $.get('/send_otp?id=' + loggedUserId + '&mobile=' + mobile, function (data) {
    });
}

// Change Mpin Flow
$('#update-kyc-btn').click(function () {
    //show kyc message
    $('#KycMsgModal').modal('show');

    //upload documents
    // $('#updateKYCModal').modal('show');
});



function openUploadModal(btnId, fileId, imgId) {
    // $('#updateKYCModal').modal('hide');
    $('#kycFileUploadMdl').modal('show');
    $('#btn-data').val(btnId);
    $('#file-data').val(fileId);
    $('#img-data').val(imgId);
}

// Upload Profile Pic with user Id
var updateProfilePic = (fileId) =>{
    var userId = $('#loggedUserId').val();
    $.ajax({
        type: 'GET',
        url: "update_profile_pic",
        data: $.param({ user_id : userId,profile_pic_id: fileId, request_from : 'in-app'}),
        cache: false,
        contentType: false,
        processData: false,
        success: (data) => {
            console.log(data);
            location.reload();            
        },
        error: function (data) {
            toastr.error("Failed");
        }
    });
}

// Profile Pic Upload
$('#profilePicUploadForm').submit(function (e) {

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
            $('#chooseProfPicFile').val('');
            $('.custom-file-label').html('Select File');
            $('#updateProfilePicMdl').modal('hide');
            if(data)
            updateProfilePic(data['id']);
        },
        error: function (data) {
            toastr.error("Failed");
        }
    });
});


// Profile Pic Upload Script ends

// update pic
$('#updateProfilePic').click(function () {
    $('#updateProfilePicMdl').modal('show');
});

//Open QR Code 
$('#balance_request_qrcode').click(function () {
    $('#balanceReqQRModal').modal('show');
});

//Security Modal
$('#security').click(function () {
    $('#securityModal').modal('show');
});

//Security Modal
$('#security').click(function () {
    $('#securityModal').modal('show');
});
$('#change_pwd').click(function () {
    $('#chgPwdModal').modal('show');
});
$('#change_mpin').click(function () {
    $('#chgMpinModal').modal('show');
});

function  showParentInfo(){
    // console.log(username);
    reqBody = { token: $('#loggedSessionToken').val(), user_id: $('#loggedUserId').val(), role_id: $('#loggedRoleId').val() };
    console.log(reqBody);
    
    $.ajax({
        type: 'POST',
        url: "/api/user_parent_info",
        data: $.param( reqBody ),
        // data: JSON.stringify(reqBody),
        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
        dataType: "json",
       
        success: function(response) {
            console.log(response);
           parent_info_ui = ``;
           if (response.status == true) {
                if ('distributor' in response.result) {
                    parent_info_ui = parent_info_ui + `<div class="col-6 col-md-6 col-sm-12 red_right_border">
                                                            <center><h2 class="parent_title"> Distributor </h2></center>
                                                            <table class="table">
                                                                <tr>
                                                                    <td><span class="parent_lable"> NAME <span class="colon-algin">:</span></span></td>
                                                                    <td><span class="parent_value">`+ response.result.distributor.first_name +` `+ response.result.distributor.last_name  +`</span></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><span class="parent_lable"> MOBILE<span class="colon-algin">:</span></span></td>
                                                                    <td><span class="parent_value">`+ response.result.distributor.mobile +`</span></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><span class="parent_lable"> ADDRESS<span class="colon-algin">:</span></span></td>
                                                                    <td><span class="parent_value">`+ response.result.distributor.address +`</span></td>
                                                                </tr>
                                                            </table>
                                                        </div>`;
                }
                if ('fos' in response.result) {
                    parent_info_ui = parent_info_ui + `<div class="col-6 col-md-6 col-sm-12 ">
                                                            <center><h2 class="parent_title"> FOS </h2></center>
                                                            <table class="table">
                                                                <tr>
                                                                    <td><span class="parent_lable"> NAME <span class="colon-algin">:</span></span></td>
                                                                    <td><span class="parent_value">`+ response.result.fos.first_name +` `+ response.result.fos.last_name  +`</span></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><span class="parent_lable"> MOBILE<span class="colon-algin">:</span></span></td>
                                                                    <td><span class="parent_value">`+ response.result.fos.mobile +`</span></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><span class="parent_lable"> ADDRESS<span class="colon-algin">:</span></span></td>
                                                                    <td><span class="parent_value">`+ response.result.fos.address +`</span></td>
                                                                </tr>
                                                            </table>
                                                        </div>`;
                }
                
               $('#userparent_info').html(parent_info_ui);
               $('#parentInfoModal').modal('show');
            }
           
        },
        error: function(response) {
            
            console.log(response);
        }
    });

}

$('#user_certificate').click(function () {
    $('#userCertificateModal').modal('show');
});


 $('#print-certificate-btn').click(function () {
    window.print();
});
