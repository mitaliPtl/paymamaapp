// On change of service type drop down
$(document).ready(function() {

    // if ( $('#selectedServiceAlias').val() !=  $('#moneyTransferAlias').val() ) {
    //     $('#operator_id').prop('disabled', true);
    // }

    $('#service_id, #pkg_id,#operator_id').change(function () {
        var selectedServiceAlias = $('#service_id').find('option:selected').attr('data-service_alias');
        
        // $('#margin_pkg_id').val(selectedServiceAlias);
        if (selectedServiceAlias == 'upi_transfer' ) {
            $('#operator_id').prop('disabled', true);
        }
        $('#filter-submit-btn').trigger('click');
    });

    $("#add_retailer_input").click(function(e){ //on add input button click
		// e.preventDefault();
		
			$("#retailer_inputs").append('<div class="form-group"><input type="text"  class="form-control " name="r_margin[]"/></div>'); //add input box
		
    });
    
    $("#submit_margin").click(function(e){ //on add input button click
		// e.preventDefault();
		
			$('#margin_pkg_id')	.val($('#pkg_id').find('option:selected').attr('value'));
            $('#margin_service_id')	.val($('#service_id').find('option:selected').attr('value'));
            if ( $('#selectedServiceAlias').val() ==  $('#moneyTransferAlias').val() ) {
                $('#margin_op_id')	.val($('#operator_id').find('option:selected').attr('value'));
            }
			
    });

});