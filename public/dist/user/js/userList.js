// Togglebutton status script starts 
$('.status-btn').change(function () {

    var activeStatus = $(this).prop('checked');
    var rowId = $(this).data('id');

    $.get('/change_user_active_status?id=' + rowId + '&status=' + activeStatus, function (data) {
        if (!data) {
            $setStatus = activeStatus ? 'off' : 'on';
            $('#status-btn_' + rowId).bootstrapToggle($setStatus);
        }
    });
});

// Load KYC status change Modal
function loadKycStatusMdl(userData){
    $('#updateKYCStatusModal').modal('show');
    if(userData.hasOwnProperty('user_id')){
        $('#kyc_row_id').val(userData['id']);
        $('#ad-kyc-status').val(userData['status']);

        // set default status
        $('#ad-pan_front_file_status').val(userData['pan_front_file_status']);
        $('#ad-aadhar_front_file_status').val(userData['aadhar_front_file_status']);
        $('#ad-aadhar_back_file_status').val(userData['aadhar_back_file_status']);
        $('#ad-photo_front_file_status').val(userData['photo_front_file_status']);
        $('#ad-photo_inner_file_status').val(userData['photo_inner_file_status']);
        // default status ends

        if(userData.hasOwnProperty('pan_file') && userData['pan_file']){
            $('#ad-pan_front_file_id').val(userData['pan_file']['id']);

            $('#ad-pan_front_file_status').val(userData['pan_front_file_status']);

            $('#pan_front_img a').attr('href',userData['pan_file']['file_path']);
            $('#pan_front_img img').attr('src',userData['pan_file']['file_path']);
            $('#pan_front_img img').attr('alt',userData['pan_file']['name']);
        }
        if(userData.hasOwnProperty('aadhar_front_file') && userData['aadhar_front_file']){
            $('#ad-aadhar_front_file_id').val(userData['aadhar_front_file']['id']);

            $('#ad-aadhar_front_file_status').val(userData['aadhar_front_file_status']);

            $('#aadhar_front_img a').attr('href',userData['aadhar_front_file']['file_path']);
            $('#aadhar_front_img img').attr('src',userData['aadhar_front_file']['file_path']);
            $('#aadhar_front_img img').attr('alt',userData['aadhar_front_file']['name']);
        }
        if(userData.hasOwnProperty('aadhar_back_file') && userData['aadhar_back_file']){
            $('#ad-aadhar_back_file_id').val(userData['aadhar_back_file']['id']);

            $('#ad-aadhar_back_file_status').val(userData['aadhar_back_file_status']);

            $('#aadhar_back_img a').attr('href',userData['aadhar_back_file']['file_path']);
            $('#aadhar_back_img img').attr('src',userData['aadhar_back_file']['file_path']);
            $('#aadhar_back_img img').attr('alt',userData['aadhar_back_file']['name']);
        }
        if(userData.hasOwnProperty('photo_front_file') && userData['photo_front_file']){
            $('#ad-photo_front_file_id').val(userData['photo_front_file']['id']);

            $('#ad-photo_front_file_status').val(userData['photo_front_file_status']);

            $('#photo_front_img a').attr('href',userData['photo_front_file']['file_path']);
            $('#photo_front_img img').attr('src',userData['photo_front_file']['file_path']);
            $('#photo_front_img img').attr('alt',userData['photo_front_file']['name']);
        }
        if(userData.hasOwnProperty('photo_inner_file') && userData['photo_inner_file']){
            $('#ad-photo_inner_file_id').val(userData['photo_inner_file']['id']);

            $('#ad-photo_inner_file_status').val(userData['photo_inner_file_status']);

            $('#photo_inner_img a').attr('href',userData['photo_inner_file']['file_path']);
            $('#photo_inner_img img').attr('src',userData['photo_inner_file']['file_path']);
            $('#photo_inner_img img').attr('alt',userData['photo_inner_file']['name']);
        }
    }
}

$('.kyc-status-dd').change(function(){
    var panFilestatus =  $('#ad-pan_front_file_status').val();
    var aadharFrontFilestatus =  $('#ad-aadhar_front_file_status').val();
    var aadharBackFilestatus =  $('#ad-aadhar_back_file_status').val();
    var photoFrontFilestatus =  $('#ad-photo_front_file_status').val();
    var photoInnerFilestatus =  $('#ad-photo_inner_file_status').val();

    if(panFilestatus && aadharFrontFilestatus && aadharBackFilestatus && photoFrontFilestatus && photoInnerFilestatus){
        $('#ad-kyc-status').val('PENDING');
        if(panFilestatus == 'APPROVED' && aadharFrontFilestatus == 'APPROVED' && aadharBackFilestatus == 'APPROVED' && photoFrontFilestatus == 'APPROVED' && photoInnerFilestatus == 'APPROVED'){
            $('#ad-kyc-status').val('APPROVED');
        }else

        if(panFilestatus == 'PENDING' && aadharFrontFilestatus == 'PENDING' && aadharBackFilestatus == 'PENDING' && photoFrontFilestatus == 'PENDING' && photoInnerFilestatus == 'PENDING'){
            $('#ad-kyc-status').val('PENDING');
        }

        if(panFilestatus == 'DECLINED' && aadharFrontFilestatus == 'DECLINED' && aadharBackFilestatus == 'DECLINED' && photoFrontFilestatus == 'DECLINED' && photoInnerFilestatus == 'DECLINED'){
            $('#ad-kyc-status').val('DECLINED');
        }
    }
});

$('.role-tab').click(function(){
    var clickedRole = $(this).val();
    console.log(clickedRole);
    $('#role_id_inp').val(clickedRole);
    $('#filter-submit-btn').trigger('click');
});

$('.services-btn').click(function(){
    // console.log($(this).val());
    service_ui ='';
    if ($(this).val()) {
        service_arr = JSON.parse($(this).val());
        if (service_arr.length >0) {
            document.getElementById("allow_service_user_id").value = service_arr[0]['user_id'];
        }
        $("#user_service_dtls").empty();
    
    
    
    for (let index = 0; index < service_arr.length; index++) {
       
        // console.log(service_arr[index]['alias']);
        checked_status = '';
        // checked_value = 0;
        
        if (service_arr[index]['status'] == 1) {
            checked_status = 'checked';
            // checked_value = 1;
        }
        service_ui =   service_ui+ `<div class="row">
                            <div class = "col-md-6">`+service_arr[index]['service_name']+`</div>
                            <div class = "col-md-6">
                                    <fieldset class="checkbox">
                                        <label>
                                            <input type="checkbox"  name="`+service_arr[index]['alias']+`" `+checked_status+` > Allowed
                                        </label>
                                    </fieldset>
                            </div>
                        </div>`;
    }
    
    
    }
    document.getElementById("user_service_dtls").innerHTML =  service_ui;
    // console.log(service_arr);
    $('#user-services').modal('show');
   
});

$('.pg-options-btn').click(function(){
    if ($(this).val()) {
        console.log($(this).val());
        pg_arr = JSON.parse($(this).val());

        if (Object.keys(pg_arr).length > 0) {
            console.log(pg_arr);
            document.getElementById("allow_pg_user_id").value = pg_arr['user_id'];
            
            if(pg_arr['pg_status'] == 1) {
                $('#pg_status').prop('checked', true);
                $('#pg_status').val('1');
            }
            if(pg_arr['credit_card']['status'] == 1) {
                $('#credit_card').prop('checked', true);
                $('#credit_card').val('1');
            }
            if(pg_arr['debit_card']['status'] == 1) {
                $('#debit_card').prop('checked', true);
                $('#debit_card').val('1');
            }
            if(pg_arr['rupay_card']['status'] == 1) {
                $('#rupay_card').prop('checked', true);
                $('#rupay_card').val('1');
            }
            if(pg_arr['upi']['status'] == 1) {
                $('#upi').prop('checked', true);
                $('#upi').val('1');
            }
            if(pg_arr['wallet']['status'] == 1) {
                $('#wallet').prop('checked', true);
                $('#wallet').val('1');
            }
            if(pg_arr['net_banking']['status'] == 1) {
                $('#net_banking').prop('net_banking', true);
                $('#net_banking').val('1');
            }
            $('#cc_charge_mode').val(pg_arr['credit_card']['charge']);
            $('#dc_charge_mode').val(pg_arr['debit_card']['charge']);
            $('#rc_charge_mode').val(pg_arr['rupay_card']['charge']);
            $('#upi_charge_mode').val(pg_arr['upi']['charge']);
            $('#nb_charge_mode').val(pg_arr['net_banking']['charge']);
        }
    }
    $('#pg-options').modal('show');
   
});


$('.delete_btn').click(function(){
    var user_id = $(this).val();
    console.log(user_id);
    $('#delete_user_id').val(user_id);
    $('#user-delete').modal('show');
});

$('.permission-btn').click(function () {
    var id = $(this).val();
    menulist = $('#menulist').val();
    menulist = JSON.parse(menulist);

    permission_ui = '';
    $.get('/get_user_permission?id=' + id, function (data) {
        permisssion = JSON.parse(data);
        $.each(menulist, function(index, value){
            $.each(permisssion, function(p_ind, p_val){
                checked_status = '';
                // if (p_ind == index) {
                if (p_ind == value.alias) {
                    if (p_val == 1) {
                        checked_status = 'checked';
                        // checked_value = 1;
                    }
                    permission_ui =   permission_ui+ `<div class="row">
                            <div class = "col-md-6">`+value.menu_title+`</div>
                            <div class = "col-md-6">
                                    <fieldset class="checkbox">
                                        <label>
                                            <input type="checkbox"  name="`+p_ind+`" `+checked_status+` > Allowed
                                        </label>
                                    </fieldset>
                            </div>
                        </div>`;
                }
            });
        });

        console.log(permission_ui);
        // document.getElementById("user_permission").innerHTML =  permission_ui;
        $("#user_permission").html(permission_ui);
        $('#permission_id').val(permisssion.user_id);
        // console.log(service_arr);
        $('#user-permission').modal('show');
    })
});
