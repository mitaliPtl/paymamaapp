<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */
 //AEPS Transaction APi
        Route::get('aeps', 'ServiceType\AepsController@index')->name('aeps');
        Route::post('aeps', 'ServiceType\AepsController@aeps_transaction')->name('aeps_transaction');
        //Route::get('aeps/onboarding', 'ServiceType\AepsController@onboarding')->name('onboarding');
        Route::post('aeps_onboarding', 'ServiceType\AepsController@aeps_onboarding')->name('aeps_onboarding');
     //   Route::get('aeps_otp', 'ServiceType\AepsController@aeps_otp')->name('aeps_otp');
        Route::post('validate_otp', 'ServiceType\AepsController@validate_otp')->name('validate_otp');
        Route::post('aeps/resend_otp', 'ServiceType\AepsController@resend_otp')->name('resend_otp');
       // Route::get('aeps_ekyc', 'ServiceType\AepsController@aeps_ekyc')->name('aeps_ekyc');
        Route::post('complete_kyc', 'ServiceType\AepsController@complete_kyc')->name('complete_kyc');
 
 
 //AEPS Transaction API Ends here
 
 
 // User Apis
 
 Route::get('allbeneficiarytocashfree', 'User\UserController@allbeneficiarytocashfree')->name('allbeneficiarytocashfree');
         Route::get('create_va_test', 'User\UserController@create_va_test')->name('create_va_test');
Route::post('ocr_verification', 'User\UserController@ocr_verification')->name('ocr_verification');
Route::post('manual_verification', 'User\UserController@manual_verification')->name('manual_verification');
Route::post('sendregistrationsms', 'User\UserController@sendregsms')->name('sendregistrationsms');
Route::post('create_va', 'User\UserController@create_va_test')->name('create_va_test');
Route::get('fetch_va/{id}', 'User\UserController@fetch_va')->name('fetch_va');
Route::post('user_sign_up_details', 'User\UserController@userSignUpDetails')->name('user_sign_up_details');
Route::post('get_pincode', 'User\UserController@getPincode')->name('user.getPincode');
Route::post('user_registration', 'User\UserController@register')->name('user.store');

Route::post('signUpRetailer', 'User\UserController@signUpUser')->name('user.signUpUser');
Route::post('signUpDistributor', 'User\UserController@signUpUser')->name('user.signUpUser');

Route::post('do_verify_mobile', 'User\APIUserVerificationController@verifyMobileAPI')->name('do_verify_mobile');
Route::post('do_verify_user', 'User\APIUserVerificationController@verifyUserAPI')->name('do_verify_user');

Route::get('get_states', 'DataController@getStates')->name('get_states');
Route::post('get_biller_by_StateCode', 'ServiceType\RechargesMobileDthController@getBillerByStateCode')->name('get_biller_by_StateCode');
Route::post('get_biller_by_biller_id', 'ServiceType\RechargesMobileDthController@getBillerInfo')->name('get_biller_by_biller_id');
Route::post('get_city_by_state_code', 'ServiceType\RechargesMobileDthController@getCityByStateCode')->name('get_city_by_state_code');

Route::post('check_verification', 'User\UserController@check_verification')->name('check_verification');

Route::get('facematch/{id}', 'User\UserController@facematch')->name('facematch');

Route::post('user_short_info', 'User\UserController@user_short_info')->name('user_short_info');
 
Route::post('change_txn_status', 'Reports\TransactionReportsController@changeTransactionStatusApi')->name('transaction.change_txn_status');
Route::post('telegram_order_api', 'Other\TelegramController@telegramOrderAPI')->name('telegram.order_status');
// Application Details API new
Route::get('app_details', 'Application\AppDetailController@appDtlApi')->name('application.app_details');
Route::get('app_details_new', 'Application\AppDetailController@appDtlApi_new')->name('application.app_details_new');
// Route::get('slidder_banners_smartpay', 'Application\SlidderBannerController@getSlidderBannerDtlsApi')->name('application.slidder_banners_smartpay');

//Updated by Ashish 
Route::post('telegram_bot', 'Other\TelegramController@telegram')->name('other.telegram');

Route::get('fp_bank_list', 'ServiceType\AepsController@fpbankList')->name('fp_bank_list');
// Route::post('aeps_transaction_api', 'ServiceType\AepsController@aeps_transaction_api')->name('aeps_transaction_api');
Route::post('webhook', 'Payment\WebhookController@index');
Route::post('cashfree_va_webhook', 'Payment\WebhookController@cashfree_webhook');
Route::post('paytm_webhook', 'Payment\OnlinePaymentController@paytm_webhook');

//payment gatewaycharges
Route::get('payment_gateway_charges', 'Payment\OnlinePaymentController@paymentGatewayCharges')->name('application.app_details');
Route::post('receipt_data', 'ServiceType\MoneyTransferController@receiptData')->name('receipt_data');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

///Route::post('icici_onboarding', 'ServiceType\IciciController@index')->name('icici_cash_deposit');
Route::post('icici_onboarding', 'ServiceType\AepsController@icicicashdeposit')->name('icici_onboarding');

Route::post('validate_iciciotp', 'ServiceType\AepsController@validate_iciciotp')->name('validate_iciciotp');
Route::post('iciciresendcashdeposit', 'ServiceType\AepsController@iciciresendcashdeposit')->name('iciciresendcashdeposit');
Route::post('validateaccounticici', 'ServiceType\AepsController@validate_transaction')->name('validateaccounticici');

Route::post('payment_gateway_limits', 'Payment\OnlinePaymentController@paymentGatewayLimits')->name('application.app_details');
   Route::post('aeps_transaction_api', 'ServiceType\AepsController@aeps_transaction_api')->name('aeps_transaction_api');
Route::group(['middleware' => ['is_logged_in']], function () {
    
     //Check for Admin
     Route::group(['middleware' => ['admin-api']], function () {
        Route::post('change_transaction_status', 'Reports\TransactionReportsController@changeTransactionStatusApi')->name('transaction.change_transaction_status');
     });
     
    // Route::get('fp_bank_list', 'ServiceType\AepsController@fpbankList')->name('fp_bank_list');
 

    // Application Details API
    Route::post('app_details', 'Application\AppDetailController@appDtlApi')->name('application.appDtlApi');
    Route::post('slidder_banners', 'Application\SlidderBannerController@getSlidderBannerDtlsApi')->name('application.getSlidderBannerDtlsApi');
    Route::post('slidder_banners_link', 'Application\SlidderBannerController@getSlidderBannerDtlsWithLinkApi')->name('application.getSlidderBannerDtlsWithLinkApi');

    // Bank Apis
    // Route::post('payment_gateway_limits', 'Payment\OnlinePaymentController@paymentGatewayLimits')->name('application.app_details');
    Route::post('bank_accounts', 'Bank\BankAcController@getBankAccoounts')->name('bank.getBankAccoounts');
    Route::post('get_qr_code_image', 'Bank\BalanceRequestController@getQRCode')->name('bank.getQRCode');
    Route::post('balance_request_bank_list', 'Bank\BalanceRequestController@getBRBankListAPI')->name('bank.getBRBankListAPI');
    Route::post('balance_request', 'Bank\BalanceRequestController@balanceRequest')->name('bank.balanceRequest');
    Route::post('balance_request_report', 'Bank\BalanceRequestController@balanceRequestReportApi')->name('bank.balanceRequestReportApi');
    Route::post('transfer_fund', 'Bank\TransRevBalController@transferBalanceApi')->name('bank.transferBalanceApi');
    Route::post('get_all_transfer', 'Bank\TransRevBalController@allTransferApi')->name('get_all_transfer');
    //revert transfer
    Route::post('do_sendotp_before_revert', 'Bank\TransRevBalController@sendOPTBeforeRevert')->name('do_sendotp_before_revert');
    Route::post('do_revert_balance', 'Bank\TransRevBalController@revertBalanceAPI')->name('do_revert_balance');

    // Add money API
    Route::post('add_money_via_pmt_gtway', 'Payment\OnlinePaymentController@addMoneyPymtGtWayApi')->name('payment.addMoneyPymtGtWayApi');

    //Route::post('user_updation', 'User\UserController@register')->name('user.update');
    Route::post('user_updation', 'User\UserController@updateretailerid')->name('user_updation');
    // Route::post('user_updation', 'User\UserController@updateUserAPI')->name('user.updateUserAPI');

    Route::post('get_user_detail', 'User\UserController@getUserDetail')->name('user.getUserDetail');
    
    //new kyc
    Route::post('check_kyc', 'User\UserController@checkKyc')->name('user.checkKyc');
    Route::post('aadhaar_kyc', 'User\UserController@submitAadharKyc')->name('user.submitAadharKyc');
    Route::post('pan_kyc', 'User\UserController@submitPanKyc')->name('user.submitPanKyc');
    Route::post('bank_kyc', 'User\UserController@submitBankKyc')->name('user.submitBankKyc');
    Route::post('selfie_kyc', 'User\UserController@submitSelfieKyc')->name('user.submitSelfieKyc');
    Route::post('business_kyc', 'User\UserController@submitBusinessKyc')->name('user.submitBusinessKyc');
    
    Route::post('get_user', 'User\UserController@APIgetUserById')->name('user.APIgetUserById');

    Route::post('update_profile_pic', 'User\UserController@updateUserProfilePicApi')->name('user.updateUserProfilePicApi');
    Route::post('update_user_secure_info', 'User\UserController@updateUserSecureData')->name('user.updateUserSecureData');
    Route::post('user_list', 'User\UserController@dTsRetailerListApi')->name('user.dTsRetailerListApi');
    Route::post('create_distributor_member', 'User\UserController@createDistributorMember')->name('user.createDistributorMember');
    Route::post('user_parent_info', 'User\UserController@getParentInfoAPI')->name('user.getParentInfoAPI');

    //payment getway Report
    Route::post('payment_gateway_report', 'Payment\OnlinePaymentController@getPaymentGatewayReportAPI')->name('payment.getPaymentGatewayReportAPI');
    
    // Virtual Account Report
    Route::post('virtual_account_report', 'Payment\OnlinePaymentController@getVirtualAccountReportAPI')->name('payment.getVirtualAccountReportAPI');
    
    // QR Code Report
    Route::post('qr_code_report', 'Payment\OnlinePaymentController@getQrCodeReportAPI')->name('payment.getQrCodeReportAPI');

    // Operator Apis
    Route::post('operator_helpline', 'Operator\OperatorController@getOperatorHelpline')->name('operator.getOperatorHelpline');

    // Wallet Transaction Apis
    Route::post('passbook', 'Reports\PassbookController@getPassbookDetails')->name('passbook.filter');
    Route::post('commissions', 'Reports\CommissionReportsController@getCommissionDetails')->name('commissions.filter');

    // Send Sms API
    Route::post('send_sms', 'User\UserController@sendSmsApi')->name('user.sendSms');
        
    // File Upload API
    Route::post('upload_file', 'Other\FileUploadController@fileUploadByApi')->name('file.upload');

    // KYC API  
    Route::post('submit_kyc_request', 'User\UserController@updateKycApi')->name('user.updateKycApi');
    Route::post('get_kyc_details', 'User\UserController@getKycDetails')->name('user.getKycDetails');

    // Package Commission Details API
    Route::post('package_commission_details', 'Settings\PackageCommissionDetailController@getPackageCommDtls')->name('packageComDtls.getPackageCommDtls');
    Route::post('my_commission', 'Settings\PackageCommissionDetailController@myCommissionAPI')->name('packageComDtls.myCommissionAPI');
    
    Route::post('get_operator_mobile_info', 'ServiceType\RechargesMobileDthController@getOperatorMobileInfo')->name('recharge.getOperatorMobileInfo');
    Route::post('get_mob_rech_pln_info', 'ServiceType\RechargesMobileDthController@getOperatorRechargePlans')->name('recharge.getOperatorRechargePlans');
    Route::post('get_dth_rech_pln_info', 'ServiceType\RechargesMobileDthController@getDTHPlanInfo')->name('recharge.getDTHPlanInfo');
    Route::post('get_121_offers_info', 'ServiceType\RechargesMobileDthController@get121OffersInfo')->name('recharge.get121OffersInfo');

    Route::post('sync_transaction', 'Settings\ApiConfigurationController@syncTransactionAPI')->name('sync_transaction');

    // Invoice Download
    Route::post('download_recipt', 'Application\ReciptController@getRecipt')->name('recipt.getRecipt');
    Route::post('download_bill', 'Application\ReciptController@getReciptBill')->name('recipt.getReciptBill');
    // get all complaint templates
    Route::post('get_templates', 'Complaint\APIComplaintController@getAllTemplate')->name('complaint.get_templates');
    Route::post('create_complaint', 'Complaint\APIComplaintController@createComplaint')->name('complaint.create_complaint');
    Route::post('get_complaint', 'Complaint\APIComplaintController@getComplaint')->name('complaint.get_complaint');
    Route::post('create_complaint_msg', 'Complaint\APIComplaintController@createComplaintMsg')->name('complaint.create_complaint_msg');
    // Route::post('get_complaint_new', 'Complaint\APIComplaintController@getComplaint_new')->name('complaint.get_complaint_new');
    
    Route::post('offers-notices', 'OffersNotice\APIOffersNoticeController@index')->name('offers-notices');

    Route::post('get_userTDS', 'TDS\TDSController@getUserTDSAPI')->name('get_userTDS');

    Route::post('get_user_list', 'User\APIUserController@index')->name('get_user_list');

    Route::post('send_credit_return', 'CreditReport\CreditReportController@creditReturnAPI')->name('send_credit_return');   
    Route::post('get_DisUsers', 'CreditReport\CreditReportController@getDisUsersAPI')->name('getDisUsers');   
    Route::post('get_userCreditHistory', 'CreditReport\CreditReportController@userCreditHistoryAPI')->name('get_userCreditHistory');   
    
    Route::post('getupiPaytm_intial', 'Payment\PaytmController@getPaytmTransactionApiGatewayDetails')->name('getupiPaytm_intial');
    Route::post('getupiPaytm_process', 'Payment\PaytmController@getupiPaytmTransactionApiGatewayDetails_new')->name('getupiPaytm_process');

     //Report Summery
     Route::post('report_summery', 'Bank\TransRevBalController@reportSummeryAPI')->name('report_summery');

      //User pia-chart 
    Route::post('get_user_graph', 'User\UserGraphController@getUserGraphAPI')->name('get_user_graph');
    
    //DMT margin
    Route::post('get_dmt_margin', 'Settings\DMTMarginController@getDMTMarginAPI')->name('get_dmt_margin');

     //user certificate
    Route::post('get_user_certificate', 'User\UserController@userCertificateAPI')->name('get_user_certificate');

    Route::post('get_notification_logs', 'User\UserController@getNotificationLogs')->name('get_notification_logs');

    Route::post('get_fos_frm_dis_api', 'User\UserController@getFosByDistAPI')->name('get_fos_frm_dis_api');
    //update Retailer's FOS 
    Route::post('update_user_fos', 'User\UserController@updateUserFos')->name('update_user_fos');

    Route::post('notification_viewed', 'User\UserController@notificationViewed')->name('notification_viewed');

    Route::post('balance_received_byfos', 'Bank\TransRevBalController@balanceReceivedByFos')->name('balance_received_byfos');


    Route::post('do_money_transfer_api', 'ServiceType\MoneyTransferController@doMoneyTransfer_API')->name('do_money_transfer_api');

  


});


// Route::post('delete_beneficiary_api', 'ServiceType\MoneyTransferController@deleteBeneficiaryAPI')->name('delete_beneficiary_api');
