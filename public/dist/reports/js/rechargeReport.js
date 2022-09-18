$("#from_date,#to_date").flatpickr();

$('#from_date').change(function() {
    $('#to_date').flatpickr({
        "minDate": new Date($('#from_date').val())
    });
});

$('#service_id').change(function() {
    $('#filter-submit-btn').trigger('click');
});

$('#pdf-btn').click(function() {
    $('#is_export').val(1);
    $('#filter-submit-btn').trigger('click');
    setTimeout(() => {
        $('#is_export').val(0);
        toastr.success('Please wait while your pdf is being generated!');
    }, 500);
});

$('#excel-btn').click(function() {
    $('#is_export').val(2);
    $('#filter-submit-btn').trigger('click');
    setTimeout(() => {
        $('#is_export').val(0);
        toastr.success('Please wait while your excel is being generated!');
    }, 500);
});

$('.change-pending-status-btn').click(function() {
    $('#tran-sts-success-option').css('display', 'block');
    $('.error').css('display', 'none');
    var transactionId = $(this).val();
    $('#transaction_status').val('');
    $('#chgStatusModal').modal('show');
    if (transactionId) {
        $('#selected_transaction_id').val(transactionId);
    }
});

$('.change-success-status-btn').click(function() {
    $('#tran-sts-success-option').css('display', 'none');
    $('.error').css('display', 'none');
    var transactionId = $(this).val();
    $('#transaction_status').val('');
    $('#chgStatusModal').modal('show');
    if (transactionId) {
        $('#selected_transaction_id').val(transactionId);
    }
});

$('#change-tran-status-btn').click(function() {
    if ($("#chgTranStatusForm").valid()) {
        $('.preloader').css('display', 'block');
        $('#chgStatusModal').modal('hide');
    }
});

var chgTranStatusForm = $("#chgTranStatusForm");

var validator = chgTranStatusForm.validate({

    rules: {
        transaction_status: { required: true },
    },
    highlight: function(element) {
        $(element).closest('.form-group').addClass('has-error');
        $(element).closest('.form-control').css("border-color", "#a94442");
    },
    unhighlight: function(element) {
        $(element).closest('.form-group').removeClass('has-error');
        $(element).closest('.form-control').css("border-color", "#00b8e6");
    },
    messages: {
        transaction_status: { required: "This field is required" },
    }
});




var total_amt = 0.00;
var bill_total_amt = 0.00;

function getSurcharge(data, all_data, indx) {
    total_amt = 0

    // total_amt = parseInt(all_data.total_amount);

    all_records = $("#rechargeReports_forinvoice").val();
    all_records = JSON.parse(all_records);
    record = all_records[indx];
    console.log(record);
    group_ui = '';
    if (record.alias == "upi_transfer") {
        group_ui = '<tr>' +
        '<td>' + 1 + '</td>' +
        '<td class="bank_account_number" id ="bank_account_number" >' + record.bank_account_number + ' </td>' +
        '<td class="transaction_id" id ="transaction_id" >' + record.bank_transaction_id + '</td>' +
        '<td class="reference_number" id ="reference_number" >' + record.order_id + '</td>' +
        '<td class="text-success"><i class="fa fa-check-circle"></i> SUCCESS</td>' +
        '<td class="label"><i class="mdi mdi-currency-inr"></i> <span class="transaction_amount" id="transaction_amount">' + record.total_amount + '</span></td>' +
        '</tr>';
        // console.log(record);

        total_amt = parseFloat(total_amt) + parseFloat(record.total_amount);
        document.getElementById("upi_bank_account_number").innerHTML = record.bank_account_number;
        document.getElementById("upi_transaction_id").innerHTML = record.bank_transaction_id;
        document.getElementById("upi_reference_number").innerHTML = record.order_id;
        document.getElementById("upi_transaction_amount").innerHTML = record.total_amount;
        
        $(".basic_amt").text(total_amt);
        console.log(group_ui);

    }else{
        if (record.group_id) {

            same_gid = all_records.filter(gid => gid.group_id == record.group_id);

            sr = 1;
            for (i = 0; i < same_gid.length; i++) {

                group_ui = group_ui +
                    '<tr>' +
                    '<td>' + sr + '</td>' +
                    '<td class="bank_account_number" id ="bank_account_number" >' + same_gid[i].bank_account_number + ' </td>' +
                    '<td class="transaction_id" id ="transaction_id" >' + same_gid[i].bank_transaction_id + '</td>' +
                    '<td class="reference_number" id ="reference_number" >' + same_gid[i].order_id + '</td>' +
                    '<td class="text-success"><i class="fa fa-check-circle"></i> SUCCESS</td>' +
                    '<td class="label"><i class="mdi mdi-currency-inr"></i> <span class="transaction_amount" id="transaction_amount">' + same_gid[i].total_amount + '</span></td>' +
                    '</tr>';
                total_amt = parseFloat(total_amt) + parseFloat(same_gid[i].total_amount);
                sr++;
            }
            // $(".basic_amt").text(total_amt);
        } else {
            group_ui = '<tr>' +
                '<td>' + 1 + '</td>' +
                '<td class="bank_account_number" id ="bank_account_number" >' + record.bank_account_number + ' </td>' +
                '<td class="transaction_id" id ="transaction_id" >' + record.bank_transaction_id + '</td>' +
                '<td class="reference_number" id ="reference_number" >' + record.order_id + '</td>' +
                '<td class="text-success"><i class="fa fa-check-circle"></i> SUCCESS</td>' +
                '<td class="label"><i class="mdi mdi-currency-inr"></i> <span class="transaction_amount" id="transaction_amount">' + record.total_amount + '</span></td>' +
                '</tr>';
            // console.log(record);
            total_amt = parseFloat(total_amt) + parseFloat(record.total_amount);

        }
       
        
        document.getElementById("same_group_id_row").innerHTML = group_ui;

        $(".basic_amt").text(total_amt);
    }
   
    $(".transaction_date").text(all_data.trans_date);
    $(".sender_mobile_number").text(all_data.mobileno);
    $(".transaction_type").text(all_data.transaction_type);
    $(".bank_account_number_top").text(all_data.bank_account_number);
    $(".recipient_name").text(all_data.imps_name);
    $(".ifsc").text(all_data.ifsc);
    $(".total_amount").text(all_data.total_amount);

    $(newFunction()).modal('show');

    function newFunction() {
        // return '#invoiceModal';
        return '#surchargeModal';

    }
}

function getSurchargeBill(all_data, indx) {
    // console.log(ord_id);
    // var id = $(this).val();
    // $.get('/edit_bank_account?id=' + id, function (data) {

    //     $('#id').val(data.id);
    //     $('#bank_name').val(data.bank_name);
    //     $('#account_no').val(data.account_no);
    //     $('#ifsc_code').val(data.ifsc_code);
    //     $('#address').val(data.address);

    //     addEditModal.modal('show');
    // })

    // console.log(all_data);
    bill_total_amt = 0.00;
    // all_records = $("#rechargeReports_forinvoice").val();
    response = JSON.parse(all_data.response_msg);
    console.log(response);

    // $('#payBillModel').modal('hide');
    // //$('#payBillRecipt').modal('show');
    // $('#invoiceModal').modal('show');
    // $('#bill_forinvoice').val($("#pay_response").val());

    input_para = '';
    if(Array.isArray(response.inputParams.input)){

    
        $.each(response.inputParams.input, function(key, valueObj) {

            input_para = input_para + '<tr>' +
                '<td> ' + valueObj.paramName + ' </td>' +
                '<td> ' + valueObj.paramValue + '</td>' +
                '</tr>';
        });
    }else{
        input_para = input_para + '<tr>' +
                '<td> ' + response.inputParams.input.paramName + ' </td>' +
                '<td> ' + response.inputParams.input.paramValue + '</td>' +
                '</tr>';
    }
    console.log(input_para);
    $('#customer_name').text(response.RespCustomerName);
    // $('#bill_date').text(response.RespBillDate);
    $('#bill_date').text(all_data.trans_date);
    $('#bill_no').text(response.RespBillNumber);
    $('#biller_name').text(all_data.billerName);
console.log(input_para);
    $('#input_param').html(input_para);

    $('#bill_row').html(input_para);
    tbl = '<tr>' +
        '<td>1</td>' +
        '<td> ' + response.txnRefId + '</td>' +
        '<td>' + all_data.order_id + '</td>' +
        '<td> ' + response.responseReason + '</td>' +
        '<td> ' + all_data.request_amount + '</td>' +
        '</tr>';
    bill_total_amt = parseFloat(all_data.request_amount);
    $('#bill_basic_amt').text(all_data.request_amount);
    $('#bill_total_amount').text(all_data.request_amount);
    $('#bill_row').html(tbl);

    $('#surchargeModal').modal('show');

}

function viewInvice(pageName) {
    inputsurCharge = parseInt($("#inputsurCharge").val());

    $('#surchargeModal').modal('hide');
    if (pageName == 'Money Transfer' || (pageName == 'UPI TRANSFER')) {
        $(".surCharge").text(inputsurCharge);
        final_amt = inputsurCharge + total_amt;
        $(".total_amount").text(final_amt);

        $('#invoiceModal').modal('show');
    }
    if (pageName == 'Bill Payment') {
        final_amt = parseFloat(inputsurCharge) + parseFloat(bill_total_amt);
        $("#bill_surCharge").text(inputsurCharge);
        $("#bill_total_amount").text(final_amt);
        $('#invoiceModalBill').modal('show');
    }




}

function printRecipt() {
    window.print();
    $("#inputsurCharge").val("");
    $('#invoiceModal').modal('hide');
}

$('.add-complaint-btn').click(function() {
    var ord_id = $(this).val();
    // all_template = $("#all_template").val();
    // all_template = JSON.parse(all_template);
    $("#complaint_order_id").val(ord_id);
    $('#addComplaintModel').modal('show');

})

// Edit Functionality
$('.edit-btn').click(function () {
    
});

function showInvice(){
    order_id =  $('#recipt_ordere_id').val();
    sbcharge= $('#inputsurCharge').val();
    web_url = $('#web_url').val();
    link_open = web_url + 'invoice/' + order_id + '/' + sbcharge;
    $('#surchargeModal').modal('hide');
 
    window.open(link_open);
 }

 function getBillSurcharge(o_id) {

    console.log(o_id);
    $('#recipt_ordere_id').val(o_id);
    $('#surchargeModal').modal('show');
 }
