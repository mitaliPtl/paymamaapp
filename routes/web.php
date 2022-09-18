<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::get('resetlimit', 'DMT\ResetController@resetlimit')->name('resetlimit');

Route::get('tester', 'DataController@tester')->name('test');

Route::get('eepdf', 'ServiceType\AepsController@texportPDF')->name('aepstest');
 
Route::get('/', function () {
    return view('welcome');
});

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('sample', function () {
    return view('sample');
});

Route::get('uathome', 'HomeController@uathome')->name('uathome');
Route::get('uataeps', 'ServiceType\AepsController@uatonboarding')->name('uataeps');

Route::get('logins', function () {
    return view('website.login');
});

Route::get('signup', function () {
    return view('website.signup');
})->name('signup');

Route::post('signup', 'User\UserController@signUpUser')->name('signup');

 #AEPS Callback route
    Route::post('aepscallback', 'ServiceType\AepsController@aepscallback')->name('aepscallback');
    #Ends Here
    
    Route::get('aepsstatuscheck', 'ServiceType\AepsController@statuscheck')->name('aepsstatuscheck');

Route::get('terms_conditions', function () {
    return view('website.terms_and_conditions');
});
Route::get('privacy_policy', function () {
    return view('website.privacy_policy');
});
Route::get('refund_and_returnpolicy', function () {
    return view('website.refundandreturnpolicy');
});
Route::get('grievance-redressal-policy', function () {
    return view('website.grievance-redressal-');
});

// Route::get('check-route', function () {
//   return redirect()->route('aeps')->with('merror',"Duplicate Transaction");
// });

Route::view('/privacy_policy', 'website.privacy_policy');
Route::view('/about_us', 'website.about_us');
Route::view('/sample_test', 'website.sample_test');
Route::view('/contact_us', 'website.contact_us');
Route::view('/services', 'website.services');

Route::post('verify_mobile', 'User\UserVerificationController@verifyMobile')->name('verify_mobile');
Route::post('verify-user', 'User\UserVerificationController@verifyUser')->name('verify-user');
Route::get('get_states', 'HomeController@getStates')->name('get_states');
Route::post('inquirysubmit', 'InquirySubmitController@inquirysubmit');
Route::get('qr/{id}', 'HomeController@qr')->name('showQr');

Route::get('verify-2fa', 'User\UserController@verify2fa')->name('verify-2fa');
Route::post('check-2fa', 'User\UserController@check2fa')->name('check-2fa');
Auth::routes();

/**
 * Redirecting to login on successful logout
 */
Route::get('logout', function () {
    Auth::logout();
    return Redirect::to('login');
});
Route::get('getcharge', 'ServiceType\AepsController@getcharge')->name('getcharge');
Route::get('getcity', 'ServiceType\AepsController@getcity')->name('getcity');

Route::view('/qr_code', 'website.qr_code');
 Route::get('changewebhookstatusfromcodeigniter/{order_id}', 'Reports\TransactionReportsController@changewebhookstatusfromcodeigniter')->name('changewebhookstatusfromcodeigniter');
Route::group(['middleware' => ['auth', 'paymentstatus', 'generalmiddleware']], function () {
// Route::get('test', 'User\UserController@test')->name('test');
    Route::get('verify-otp', 'HomeController@verifyOtp')->name('verify-otp');
    Route::post('update_kyc', 'User\UserController@updateKyc')->name('update_kyc');
    Route::post('check-otp', 'HomeController@checkOtp')->name('check-otp');
    Route::get('send_otp', 'User\UserController@sendOtp')->name('send_otp');
    Route::get('verify_sent_otp', 'User\UserController@verifySentOtp')->name('verify_sent_otp');

    Route::get('permission-denied', 'HomeController@permissionDenied')->name('permissionDenied');

    //Check for Admin
    Route::group(['middleware' => ['admin']], function () {
        Route::get('admin-home', 'HomeController@index')->name('admin-home');
        Route::get('downloadbackup', 'HomeController@backup')->name('downloadbackup');
        
        //Payment getway Report
        Route::get('bank_statement', 'ServiceType\IciciController@icici_account_statement')->name('icici_statement');
        Route::post('bank_statement', 'ServiceType\IciciController@icici_account_statement')->name('icici_statement_filter');
        
        
        // Service type routes starts here
        Route::get('create_service_type', 'ServiceType\ServiceTypeController@create')->name('create_service_type');
        Route::get('edit_service_type', 'ServiceType\ServiceTypeController@edit')->name('edit_service_type');
        Route::post('create_service_type', 'ServiceType\ServiceTypeController@store')->name('store_service_type');
        Route::get('service_type', 'ServiceType\ServiceTypeController@index')->name('service_type');
        Route::get('change_service_typ_active_status', 'ServiceType\ServiceTypeController@changeActiveStatus')->name('change_service_typ_active_status');
        Route::get('change_service_typ_delete_status', 'ServiceType\ServiceTypeController@changeDeleteStatus')->name('change_service_typ_delete_status');

        // Api setting routes starts here
        Route::get('edit_api_setting', 'Settings\ApiSettingController@edit')->name('edit_api_setting');
        Route::post('api_setting', 'Settings\ApiSettingController@store')->name('store_api_setting');
        Route::get('api_setting_ch_pwd', 'Settings\ApiSettingController@index')->name('api_setting_ch_pwd_index');
        Route::post('api_setting_ch_pwd', 'Settings\ApiSettingController@changePassword')->name('api_setting_ch_pwd');
        Route::get('checkApiSettingUsernameExists', 'Settings\ApiSettingController@checkUsernameExists')->name('checkApiSettingUsernameExists');
        Route::get('api_setting', 'Settings\ApiSettingController@index')->name('api_setting');
        Route::get('change_api_set_active_status', 'Settings\ApiSettingController@changeActiveStatus')->name('change_api_set_active_status');
        Route::get('change_api_set_delete_status', 'Settings\ApiSettingController@changeDeleteStatus')->name('change_api_set_delete_status');

        // Operator routes starts here
        Route::post('operator', 'Operator\OperatorController@store')->name('create_operator');
        Route::get('edit_operator', 'Operator\OperatorController@edit')->name('edit_operator');
        Route::get('checkOperatorCodeExists', 'Operator\OperatorController@checkOperatorCodeExists')->name('checkOperatorCodeExists');
        Route::get('operator', 'Operator\OperatorController@index')->name('operator');
        Route::get('change_operator_active_status', 'Operator\OperatorController@changeActiveStatus')->name('change_operator_active_status');
        Route::get('change_operator_delete_status', 'Operator\OperatorController@changeDeleteStatus')->name('change_operator_delete_status');

        //Operator Details routes start here
        Route::get('operator_dtls', 'Operator\OperatorDetailsController@index')->name('operator_dtls');
        Route::post('operator_dtls', 'Operator\OperatorDetailsController@index')->name('operator_dtls_filter');
        Route::get('save_op_details', 'Operator\OperatorDetailsController@storeOpdetails')->name('save_op_details');

        // Package Setting routes starts here
        Route::post('package_setting', 'Settings\PackageSettingController@store')->name('create_package_setting');
        Route::get('edit_package_setting', 'Settings\PackageSettingController@edit')->name('edit_package_setting');
        Route::get('package_setting', 'Settings\PackageSettingController@index')->name('package_setting');
        Route::get('change_pack_setting_active_status', 'Settings\PackageSettingController@changeActiveStatus')->name('change_pack_setting_active_status');
        Route::get('change_pack_setting_delete_status', 'Settings\PackageSettingController@changeDeleteStatus')->name('change_pack_setting_delete_status');

        //Package Commission Details routes start here
        Route::get('pack_comm_dtls', 'Settings\PackageCommissionDetailController@index')->name('pack_comm_dtls');
        Route::post('pack_comm_dtls', 'Settings\PackageCommissionDetailController@index')->name('pack_comm_dtls_filter');

        Route::get('save_pk_comm_details', 'Settings\PackageCommissionDetailController@storeOpdetails')->name('save_op_details');
        Route::post('save_pk_comm_details', 'Settings\PackageCommissionDetailController@storeOpdetails')->name('store_op_details');

        // Operator settings routes starts here
        Route::post('operator_settings', 'Operator\OperatorSettingsController@store')->name('create_operator_settings');
        Route::get('operator_settings', 'Operator\OperatorSettingsController@index')->name('operator_settings');
        

        // Api Amount Details routes starts here
        Route::get('edit_api_amount_details', 'Settings\ApiAmountDetailController@edit')->name('edit_api_amount_details');
        Route::post('api_amount_details', 'Settings\ApiAmountDetailController@store')->name('store_api_amount_details');
        Route::get('api_amount_details', 'Settings\ApiAmountDetailController@index')->name('api_amount_details');
        Route::get('change_api_amount_dtls_active_status', 'Settings\ApiAmountDetailController@changeActiveStatus')->name('change_api_amount_dtls_active_status');
        Route::get('change_api_amount_dtls_delete_status', 'Settings\ApiAmountDetailController@changeDeleteStatus')->name('change_api_amount_dtls_delete_status');

        // Payment Gateway setting routes starts here
        Route::get('edit_pay_gate_setting', 'Settings\PaymentGatewaySettingsController@edit')->name('edit_pay_gate_setting');
        Route::post('pay_gate_setting', 'Settings\PaymentGatewaySettingsController@store')->name('store_pay_gate_setting');
        Route::get('pay_gate_setting_ch_pwd', 'Settings\PaymentGatewaySettingsController@index')->name('pay_gate_setting_ch_pwd');
        Route::post('api_pay_gate_ch_pwd', 'Settings\PaymentGatewaySettingsController@changePassword')->name('api_pay_gate_ch_pwd');
        Route::get('checkPayGateSettingUsernameExists', 'Settings\PaymentGatewaySettingsController@checkUsernameExists')->name('checkPayGateSettingUsernameExists');
        Route::get('pay_gate_setting', 'Settings\PaymentGatewaySettingsController@index')->name('pay_gate_setting');
        Route::get('change_pay_gate_set_active_status', 'Settings\PaymentGatewaySettingsController@changeActiveStatus')->name('change_pay_gate_set_active_status');
        Route::get('change_pay_gate_set_delete_status', 'Settings\PaymentGatewaySettingsController@changeDeleteStatus')->name('change_pay_gate_set_delete_status');

        // SMS Gateway setting routes starts here
        Route::get('edit_sms_gate_setting', 'Settings\SmsGatewaySettingsController@edit')->name('edit_sms_gate_setting');
        Route::post('sms_gate_setting', 'Settings\SmsGatewaySettingsController@store')->name('store_sms_gate_setting');
        Route::get('sms_gate_setting_ch_pwd', 'Settings\SmsGatewaySettingsController@index')->name('sms_gate_setting_ch_pwd');
        Route::post('sms_gate_ch_pwd', 'Settings\SmsGatewaySettingsController@changePassword')->name('sms_gate_ch_pwd');
        Route::get('checkSmsGateSettingUsernameExists', 'Settings\SmsGatewaySettingsController@checkUsernameExists')->name('checkSmsGateSettingUsernameExists');
        Route::get('sms_gate_setting', 'Settings\SmsGatewaySettingsController@index')->name('sms_gate_setting');
        Route::get('change_sms_gate_set_active_status', 'Settings\SmsGatewaySettingsController@changeActiveStatus')->name('change_sms_gate_set_active_status');
        Route::get('change_sms_gate_set_delete_status', 'Settings\SmsGatewaySettingsController@changeDeleteStatus')->name('change_sms_gate_set_delete_status');

        // Day Book Routes starts
        Route::get('day_book', 'Other\DayBookController@index')->name('day_book');
        Route::post('day_book', 'Other\DayBookController@index')->name('filter_day_book');

        // Bank account routes
        Route::post('bank_account', 'Bank\BankAcController@store')->name('bank_account.store');
        Route::get('edit_bank_account', 'Bank\BankAcController@edit')->name('bank_account.edit');
        Route::get('change_bank_ac_delete_status', 'Bank\BankAcController@changeDeleteStatus')->name('change_bank_ac_delete_status');
        Route::post('add_money', 'Bank\BankAcController@addMoney')->name('add_money');
        Route::post('upload_logo', 'Bank\BankAcController@uploadLogo')->name('upload_logo');

        Route::post('balance_request_reply', 'Bank\BalanceRequestController@balReqReply')->name('balance_request_reply');
        Route::post('transfer_request_balance', 'Bank\BalanceRequestController@transferBalance')->name('transfer_request_balance');
        Route::get('deline_bal_req/{id}', 'Bank\BalanceRequestController@declineBalRequest')->name('deline_bal_req');

        // Store Category routes starts
        Route::get('store_category', 'User\StoreCategoryController@index')->name('store_category');
        Route::post('store_category', 'User\StoreCategoryController@store')->name('store_store_category');
        Route::get('edit_store_category', 'User\StoreCategoryController@edit')->name('edit_store_category');
        Route::get('change_store_category_active_status', 'User\StoreCategoryController@changeActiveStatus')->name('change_store_category_active_status');
        Route::get('change_store_category_delete_status', 'User\StoreCategoryController@changeDeleteStatus')->name('change_store_category_delete_status');
   
        // Sms Template Routes
        Route::get('sms_template', 'Other\SmsController@index')->name('sms_template');
        Route::post('sms_template', 'Other\SmsController@store')->name('store_sms_template');
        Route::get('edit_sms_template', 'Other\SmsController@edit')->name('edit_sms_template');
        Route::get('change_sms_template_active_status', 'Other\SmsController@changeActiveStatus')->name('change_sms_template_active_status');
        Route::get('change_sms_template_delete_status', 'Other\SmsController@changeDeleteStatus')->name('change_sms_template_delete_status');
       
        // Transaction route for Recharges
        Route::post('change_transaction_status', 'Reports\TransactionReportsController@changeTransactionStatus')->name('change_transaction_status');
       
        
    
        // Member Passbook routes
        Route::get('member_passbook', 'Reports\PassbookController@memberPassbook')->name('member_passbook');
        Route::post('member_passbook', 'Reports\PassbookController@memberPassbook')->name('filter_member_passbook');

       

Route::get('generates', function (){
    \Illuminate\Support\Facades\Artisan::call('storage:link');
    echo 'ok';
});
        // Slidder Banner Rotes
        Route::get('slidder_banner', 'Application\SlidderBannerController@index')->name('slidder_banner');
        Route::post('create_slidder_banner', 'Application\SlidderBannerController@store')->name('create_slidder_banner');
        Route::get('edit_slidder_banner', 'Application\SlidderBannerController@edit')->name('edit_slidder_banner');
        Route::get('slidder_banner_delete', 'Application\SlidderBannerController@deleteSlidderBanner')->name('slidder_banner_delete');

        Route::post('redirect_banner', 'Application\SlidderBannerController@redirectBanner')->name('redirect_banner');

        Route::get('sync_transaction/{id}', 'Settings\ApiConfigurationController@syncTransaction')->name('sync_transaction');


        //Office Expenses
        Route::get('office_expenses_report', 'OfficeExpenses\OfficeExpensesReportController@index')->name('office_expenses_report');
        Route::post('office_expenses_report', 'OfficeExpenses\OfficeExpensesReportController@index')->name('filter_office_expenses_report');
        Route::post('add_expense', 'OfficeExpenses\OfficeExpensesReportController@addOfficeExpense')->name('add_expense');
        
        Route::get('office_expenses_category', 'OfficeExpenses\CategoryController@index')->name('office_expenses_category');
        Route::post('add_categoryOfficeExpenses', 'OfficeExpenses\CategoryController@addCategoryOfficeExpenses')->name('add_categoryOfficeExpenses');
        Route::post('edit_categoryOfficeExpenses', 'OfficeExpenses\CategoryController@editCategoryOfficeExpenses')->name('edit_categoryOfficeExpenses');
        Route::post('delete_categoryOfficeExpenses', 'OfficeExpenses\CategoryController@deleteCategoryOfficeExpenses')->name('delete_categoryOfficeExpenses');

        //TDS
        Route::get('tds_report', 'TDS\TDSController@tdsReport')->name('tds_report');
        Route::post('tds_report', 'TDS\TDSController@tdsReport')->name('filter_tds_report');
        Route::get('tds_upload', 'TDS\TDSController@index')->name('tds_upload');
        Route::post('upload_tds', 'TDS\TDSController@uploadTDS')->name('upload_tds');
        Route::get('view_tds/{id}', 'TDS\TDSController@viewTDSById')->name('view_tds');
        Route::post('tds_history_ByDate', 'TDS\TDSController@tdsHistoryByDate')->name('tds_history_ByDate');
        Route::get('tds_history_ByDate', 'TDS\TDSController@tdsHistoryByDate')->name('tds_history_ByDate');

        //Payment getway Report
        Route::get('payment_gateway_report', 'Payment\OnlinePaymentController@paymentGatewayReport')->name('payment_gateway_report');
        Route::post('payment_gateway_report', 'Payment\OnlinePaymentController@paymentGatewayReport')->name('payment_gateway_report_filter');

        //Payment getway Report
        Route::get('user_payment_gateway_report', 'Payment\OnlinePaymentController@getPaymentGatewayReport')->name('user_payment_gateway_report');
        Route::post('user_payment_gateway_report', 'Payment\OnlinePaymentController@getPaymentGatewayReport')->name('user_payment_gateway_report_filter');
        
        //Virtual account Report
        Route::get('user_virtual_account_report', 'Payment\OnlinePaymentController@getVirtualAccountReport')->name('user_virtual_account_report');
        Route::post('user_virtual_account_report', 'Payment\OnlinePaymentController@getVirtualAccountReport')->name('user_virtual_account_report_filter');
        
        //QR Code Payment Report
        Route::get('user_qr_code_report', 'Payment\OnlinePaymentController@getQrCodeReport')->name('user_qr_code_account_report');
        Route::post('user_qr_code_report', 'Payment\OnlinePaymentController@getQrCodeReport')->name('user_qr_code_report_filter');
        
        Route::get('charges_setting', 'Payment\OnlinePaymentController@chargesSetting')->name('charges_setting');
        Route::post('update_charges_setting', 'Payment\OnlinePaymentController@updateChargesSetting')->name('update_charges_setting');


        // Change Table Date Format
        // Route::get('change_tran_tbl_date_format', 'Reports\TransactionReportsController@chnageTranTblDateformat2Default')->name('change_tran_tbl_date_format');
        // Route::get('change_wallet_tran_tbl_date_format', 'Reports\TransactionReportsController@chnageWlltTranTblDateformat2Default')->name('change_wallet_tran_tbl_date_format');
    
        //BBPS managment
        Route::get('bbps_management', 'Operator\OperatorController@bbpsManagement')->name('bbps_management');
        Route::post('bbps_management', 'Operator\OperatorController@bbpsManagement')->name('filter_bbps_management');
        Route::post('upload_biller_image', 'Operator\OperatorController@uploadBillerImage')->name('upload_biller_image');
        Route::post('add_bbps_biller', 'Operator\OperatorController@addBbpsBiller')->name('add_bbps_biller');
        Route::post('update_custom_param', 'Operator\OperatorController@updateCustomParam')->name('update_custom_param');

        
        Route::get('general_setting', 'Settings\GeneralSettingController@index')->name('general_setting');
        Route::post('update_social_media', 'Settings\GeneralSettingController@updateSocialMedia')->name('update_social_media');
        Route::post('update_verify_charges', 'Settings\GeneralSettingController@updateVerifyCharges')->name('update_verify_charges');
        Route::post('update_paylimit', 'Settings\GeneralSettingController@updatePayLimit')->name('update_paylimit');
        Route::post('update_company', 'Settings\GeneralSettingController@updateCompany')->name('update_company');
        Route::post('update_other', 'Settings\GeneralSettingController@updateOther')->name('update_other');

        
        Route::post('update_qr_code', 'Settings\GeneralSettingController@updateQRCode')->name('update_qr_code');
        
        //DMT margin
        Route::get('dmt_margin', 'Settings\DMTMarginController@index')->name('dmt_margin');
        Route::post('dmt_margin_filter', 'Settings\DMTMarginController@index')->name('dmt_margin_filter');
        Route::post('update_margin', 'Settings\DMTMarginController@updateMargin')->name('update_margin');

        //user services

        Route::post('update_user_sesrvices', 'User\UserController@updateUserSesrvices')->name('update_user_sesrvices');
        Route::get('checkperrole', 'User\UserController@checkperrole')->name('checkperrole');
        Route::get('checkbottomrole', 'User\UserController@checkbottomrole')->name('checkbottomrole');
        Route::get('checkperroleforedituser', 'User\UserController@checkperroleforedituser')->name('checkperroleforedituser');
        Route::post('sendregsms', 'User\UserController@sendregsms')->name('sendregsms');
        Route::post('signUpUserbyAdmin', 'User\UserController@signUpUserbyAdmin')->name('signUpUserbyAdmin');
        
        //user pg services
        Route::post('update_user_pg_sesrvices', 'User\UserController@updateUserPgSesrvices')->name('update_user_pg_sesrvices');
        Route::get('changeStatus', 'User\UserController@changeStatus')->name('changeStatus');
        
        Route::get('updatebankdetails', 'User\UserController@updatebankdetails')->name('updatebankdetails');
         //sub admin
         Route::get('create_subadmin', 'User\UserController@createSubAdmin')->name('create_subadmin');
         Route::post('store_subadmin', 'User\UserController@storeSubAdmin')->name('store_subadmin');
 
        Route::get('edit_subadmin/{id}', 'User\UserController@createSubAdmin')->name('edit_subadmin');
        Route::post('update_subadmin/{id}', 'User\UserController@storeSubAdmin')->name('update_subadmin');
        Route::post('delete_user', 'User\UserController@deleteUser')->name('delete_user');

        Route::get('get_user_permission', 'User\UserController@getUserPermission')->name('get_user_permission');
        Route::post('update_user_permisssion', 'User\UserController@updateUserPermisssion')->name('update_user_permisssion');
        

    Route::post('delete_verification_user', 'User\UserController@deleteVerificationUser')->name('delete_verification_user');
    Route::get('manualverify/{id}', 'User\UserController@manual_verification')->name('manualverify');
        //payment
    });
    // Admin route ends

    // Allow for distributor and retailer only
    Route::middleware(['addt'])->group(function () {
        
        // Member User Routes starts
        Route::get('user_list', 'User\UserController@index')->name('user_list');
        Route::get('user_list_ekyc', 'User\UserController@nonEKycUser')->name('user_list_ekyc');
        Route::post('user_list_ekyc', 'User\UserController@nonEKycUser')->name('filter_user_list_ekyc');
         Route::get('viewkyc/{id}', 'User\UserController@viewkyc')->name('viewkyc');
         Route::get('verification_list', 'User\UserController@verification_list')->name('verification_list');
        Route::get('spam_list/{name}', 'User\UserController@index')->name('spam_list');
        Route::get('spam_verification_list/{name}', 'User\UserController@verification_list')->name('spam_verification_list');
        Route::get('retailer_verification_list/{roles}', 'User\UserController@verification_list')->name('retailer_verification_list');
        Route::post('user_list', 'User\UserController@index')->name('filter_user_list');

        Route::get('user_spam/{id}', 'User\UserController@spamUser')->name('user_spam');
        Route::get('regenerate_qr/{id}', 'User\UserController@regenerateQr')->name('regenerate_qr');
        Route::get('remove_spam/{id}', 'User\UserController@removeSpam')->name('remove_spam');
    
        //for Verification List
        Route::get('verification_spam/{id}', 'User\UserController@spamVerificationUser')->name('verification_spam');
        Route::get('remove_verification_spam/{id}', 'User\UserController@removeVerificationSpam')->name('remove_verification_spam');
        //for Verifiation List End



        Route::get('change_user_active_status', 'User\UserController@changeActiveStatus')->name('change_user_active_status');

        Route::get('create_member', 'User\UserController@create')->name('create_member');
        Route::get('create_new', 'User\UserController@createnew')->name('create_new');
        

       
         Route::post('updateuserid/{id}', 'User\UserController@updateuser')->name('updateuserid');
         Route::post('updateuserqr/{id}', 'User\UserController@updateuserqr')->name('updateuserqr');
        // Route::post('edit_user/{id}', 'User\UserController@store')->name('update_user');
        Route::post('create_member', 'User\UserController@store')->name('store_member');
        // Route::post('get_user_frm_parent_role_id', 'User\UserController@getUserFromPrntRole')->name('getUserFromPrntRole');
    
        //Transfer/Revert Balance Routes
        Route::get('all_transfer', 'Bank\TransRevBalController@allTransfer')->name('all_transfer');
        Route::post('all_transfer', 'Bank\TransRevBalController@allTransfer')->name('filter_all_transfer');
        Route::get('transfer_revert_balance', 'Bank\TransRevBalController@index')->name('transfer_revert_balance');
        Route::post('transfer_revert_balance', 'Bank\TransRevBalController@index')->name('find_user');
        Route::post('transfer_balance', 'Bank\TransRevBalController@transferBalance')->name('transfer_balance');
        Route::post('revert_balance', 'Bank\TransRevBalController@revertBalance')->name('revert_balance');
        Route::get('send_revert_otp', 'Bank\TransRevBalController@sendRevertOtp')->name('send_revert_otp');
        Route::get('verifyRevertOTPMpin', 'Bank\TransRevBalController@verifyRevertOTPMpin')->name('verifyRevertOTPMpin');
        Route::get('sync_transaction/{id}', 'Settings\ApiConfigurationController@syncTransaction')->name('sync_transaction');

         //Offer and notice
        Route::post('add_offersnotice', 'OffersNotice\OffersNoticeController@addOffersNotice')->name('add_offersnotice');
         Route::post('edit_offersnotice', 'OffersNotice\OffersNoticeController@editOffersNotice')->name('edit_offersnotice');
         Route::post('delete_offersnotice', 'OffersNotice\OffersNoticeController@deleteOffersNotice')->name('delete_offersnotice');
         Route::post('view_offersnotice', 'OffersNotice\OffersNoticeController@viewOffersNotice')->name('view_offersnotice');

        //Credit Report
        Route::get('credit_report', 'CreditReport\CreditReportController@index')->name('credit_report');      
        Route::post('credit_report', 'CreditReport\CreditReportController@index')->name('filter_credit_report');   
         
        Route::get('view_history/{id}', 'CreditReport\CreditReportController@userCreditHistory')->name('view_history');   
        Route::post('view_history/{id}', 'CreditReport\CreditReportController@userCreditHistory')->name('filter_view_history');
           
        Route::post('credit_return', 'CreditReport\CreditReportController@creditReturn')->name('credit_return');   

        Route::get('member_passbook_pm', 'Reports\PassbookController@memberPassbookPaymama')->name('memberPassbookPaymama');
        Route::post('member_passbook_pm', 'Reports\PassbookController@memberPassbookPaymama')->name('memberPassbookPaymamapost');

        Route::get('distributor_fos_list', 'User\UserController@distributorFosList')->name('distributorFosList');

        Route::get('create_new_pm', 'User\UserController@createnewpm')->name('createnewpm');  ///createretailer


        Route::get('create_new_fos', 'User\UserController@createnewfos')->name('createnewfos');  //createfosgetpage
        Route::post('store_new_fos', 'User\UserController@storenewfos')->name('storenewfos');  //storenewfos

        Route::get('create_new_retailer', 'User\UserController@createnewretailer')->name('createnewretailer');  //createnewretailer
        Route::post('store_new_retailer', 'User\UserController@storenewretailer')->name('storenewretailer');  //storenewretailer
        
    });

    // Allow for distributor and retailer only
    Route::middleware(['dtrt'])->group(function () {
        Route::get('fetch_all_contact_details', 'DMT\SenderRegistrationController@fetch_all_contact_details')->name('fetch_all_contact_details');
        Route::get('resetlimit', 'DMT\SenderRegistrationController@resetlimit')->name('resetlimit');
        Route::get('get_dmt_sender_details', 'DMT\SenderRegistrationController@index')->name('get_dmt_sender_details');
        Route::post('get_dmt_sender_details', 'DMT\SenderRegistrationController@checksenderexists')->name('get_dmt_sender_details');
        Route::post('add_dmt_beneficiary', 'DMT\BeneficiaryController@adddmtbeneficiary')->name('add_dmt_beneficiary');
        Route::get('deleteallbeneficiary', 'DMT\BeneficiaryController@deleteallbeneficiary')->name('deleteallbeneficiary');
        Route::get('verifybankaccount', 'DMT\BeneficiaryController@verifybankaccount')->name('verifybankaccount');
        Route::get('getifsc', 'DMT\BeneficiaryController@getifsc')->name('getifsc');
        Route::post('register_dmt_sender', 'DMT\SenderRegistrationController@registerSender')->name('register_dmt_sender');
        Route::post('insert_beneficiary', 'DMT\BeneficiaryController@insertbeneficiary')->name('insert_beneficiary');
        Route::post('verify_dmt_otp', 'DMT\SenderRegistrationController@verifyOTP')->name('verify_dmt_otp');
        Route::post('resend_dmt_otp', 'DMT\SenderRegistrationController@resendOTP')->name('resend_dmt_otp');
        Route::post('deletedmtbeneficiary', 'DMT\BeneficiaryController@deletedmtbeneficiary')->name('deletedmtbeneficiary');
        Route::get('transferdmtmoney/{id}/{senderid}', 'DMT\TransferController@transferdmtmoney')->name('transferdmtmoney');
        Route::post('DoDmtTransaction', 'DMT\TransferController@DoDmtTransaction')->name('DoDmtTransaction');
        
        
        Route::get('home/', 'HomeController@index')->name('home');
        
        // Route::get('home_new', 'HomeController@index_new')->name('home');
        
        Route::get('home_pia_chart', 'Sample\SampleController@homePiaChart')->name('home_pia_chart');
        Route::post('home_pia_chart', 'Sample\SampleController@homePiaChart')->name('home_pia_chart_filter');
        //Recharges Routes
        Route::get('recharges', 'ServiceType\RechargesMobileDthController@index')->name('recharges');
        // Money Transfer Route
        Route::get('money_transfer', 'ServiceType\MoneyTransferController@index')->name('money_transfer');
        Route::get('get_sender_details', 'ServiceType\MoneyTransferController@getSenderDetails')->name('get_sender_details');
        Route::post('get_sender_details', 'ServiceType\MoneyTransferController@getSenderDetails')->name('get_sender_details');
      // Route::get('get_sender_detailss', 'ServiceType\MoneyTransferController@getSenderDetails')->name('get_sender_details');


        // Online Payment Routes
        Route::get('online_payment', 'Payment\OnlinePaymentController@onlinePayment')->name('online_payment');
        Route::post('online_payment_status', 'Payment\OnlinePaymentController@onlinePaymentStatus')->name('onlinePaymentStatus');

        // Balance Request Routes
        // Route::post('balance_request', 'Bank\BalanceRequestController@store')->name('balance_request.store');
        Route::post('send_balance_request', 'Bank\BalanceRequestController@store')->name('send_balance_request');

        Route::get('operator_helpline', 'Operator\OperatorController@operatorHelpLine')->name('operator_helpline');
        Route::post('operator_helpline', 'Operator\OperatorController@operatorHelpLine')->name('filter_operator_helpline');

        // My Commission routes
        Route::get('my_commission', 'Settings\PackageCommissionDetailController@myCommission')->name('my_commission');
        Route::post('my_commission', 'Settings\PackageCommissionDetailController@myCommission')->name('filter_my_commission');
     
        Route::get('sync_transaction/{id}', 'Settings\ApiConfigurationController@syncTransaction')->name('sync_transaction');

        Route::get('all-offers-notice', 'OffersNotice\OffersNoticeController@getOffersNotice_RT_DT')->name('all-offers-notice');
        // Route::get('all-offers-notice', 'OffersNotice\OffersNoticeController@getOffersNotice_RT_DT')->name('all-offers-notice');
        // Route::get('offers-notice-dtrt/{id}', 'OffersNotice\OffersNoticeController@getOffersNotice_RT_DT')->name('offers-notice-dtrt');
        Route::get('offers-notice-dtrt', 'OffersNotice\OffersNoticeController@getOffersNotice_RT_DT')->name('offers-notice-dtrt');
       

        //Payment getway Report
        Route::get('user_payment_gateway_report', 'Payment\OnlinePaymentController@getPaymentGatewayReport')->name('user_payment_gateway_report');
        Route::post('user_payment_gateway_report', 'Payment\OnlinePaymentController@getPaymentGatewayReport')->name('user_payment_gateway_report_filter');
       
        //certificate
        Route::get('user_certificate', 'User\UserController@userCertificate')->name('user_certificate');

        //money transfer
        Route::post('get_sender_details', 'ServiceType\MoneyTransferController@getSenderDetails')->name('get_sender_details');
        Route::post('register_sender', 'ServiceType\MoneyTransferController@registerSender')->name('register_sender');
        Route::post('verify_otp', 'ServiceType\MoneyTransferController@verifyOTP')->name('verify_otp');
        Route::post('transfer_money', 'ServiceType\MoneyTransferController@transferMoney')->name('transfer_money');
        Route::post('add_beneficiary', 'ServiceType\MoneyTransferController@addBeneficiary')->name('add_beneficiary');
        

        // Route::get('money_transfer_new', 'ServiceType\MoneyTransferController@index_new')->name('money_transfer');
        /** START - KYC ROUTE */
        Route::post('aadhaar-verify', 'User\UserController@submitAadharKyc')->name('aadhaarVerify');
        Route::post('pan-verify', 'User\UserController@submitPanKyc')->name('panVerify');
        Route::post('bank-verify', 'User\UserController@submitBankKyc')->name('bankVerify');
        Route::post('photo-verify', 'User\UserController@submitSelfieKyc')->name('photoVerify');
        Route::post('business-verify', 'User\UserController@submitBusinessKyc')->name('businessVerify');
        Route::get('kyc-status', 'User\UserController@getAuthUserKycStatus')->name('userKycStatus');
        Route::get('pincode', 'User\UserController@getPincode')->name('pincode');
        /** END - KYC ROUTE */
    });
    
   
    
    
    // Allow for Retailer only
    Route::middleware(['retailer'])->group(function () {
        Route::get('card_transfer', 'ServiceType\CardBankController@index')->name('card_bank');
        Route::get('verify_pan', 'ServiceType\CardBankController@pan_verify')->name('card_pan_verify');
        Route::post('check_mobile', 'ServiceType\CardBankController@check_mobile')->name('check_mobile');
        Route::post('verify_card', 'ServiceType\CardBankController@verify_user')->name('verify_card_user');
        Route::get('otp_verification', 'ServiceType\CardBankController@card_otp')->name('card_otp');
        Route::post('otp_validate', 'ServiceType\CardBankController@validate_otp')->name('validate_card_otp');
        Route::post('otp_resend', 'ServiceType\CardBankController@resend_otp')->name('resend_card_otp');
        Route::get('cc_bank_list/{mobile}', 'ServiceType\CardBankController@cc_bank_list')->name('cc_bank_list');
        Route::post('new_cc_beneficiary', 'ServiceType\CardBankController@newBeneficiary')->name('new_cc_beneficiary');
        Route::post('add_cc_beneficiary', 'ServiceType\CardBankController@addBeneficiary')->name('add_cc_beneficiary');
        
        Route::post('get_operator_mobile_info', 'ServiceType\RechargesMobileDthController@getOperatorMobileInfo')->name('get_operator_mobile_info');
        Route::post('get_operator_rech_pln', 'ServiceType\RechargesMobileDthController@getOperatorRechargePlans')->name('get_operator_rech_pln');
        Route::post('get_dth_plan_info', 'ServiceType\RechargesMobileDthController@getDTHPlanInfo')->name('get_dth_plan_info');
        Route::post('get_121_offers_info', 'ServiceType\RechargesMobileDthController@get121OffersInfo')->name('get_121_offers_info');
        Route::get('get_dth_ac_info', 'ServiceType\RechargesMobileDthController@getDTHAcInfo')->name('get_dth_ac_info');

        Route::get('distributor_info', 'User\UserController@getDistributorInfo')->name('getDistributorInfo');

        Route::post('pay_elect_bill', 'ServiceType\RechargesMobileDthController@payElectBill')->name('pay_elect_bill');

        Route::get('get_biller_info', 'ServiceType\RechargesMobileDthController@getBillerInfo')->name('get_biller_info');
        
        
        Route::get('aeps', 'ServiceType\AepsController@index')->name('aeps');
        Route::get('aeps_device_driver', 'ServiceType\ServiceTypeController@aepsDeviceDriver')->name('aepsDeviceDriver');
        
        Route::get('aadharpay', 'ServiceType\AepsController@aadharpay')->name('aadharpay');
        Route::post('aeps_transaction', 'ServiceType\AepsController@aeps_transaction')->name('aeps_transaction');
        Route::get('aeps/onboarding', 'ServiceType\AepsController@onboarding')->name('onboarding');
        Route::post('aeps/onboarding', 'ServiceType\AepsController@aeps_onboarding')->name('aeps_onboarding');
        Route::get('aeps_otp', 'ServiceType\AepsController@aeps_otp')->name('aeps_otp');
        Route::post('validate_otp', 'ServiceType\AepsController@validate_otp')->name('validate_otp');
        //delete after use
        Route::get('validate_otp', 'ServiceType\AepsController@validate_otp')->name('validate_otp');
        Route::get('complete_kyc', 'ServiceType\AepsController@complete_kyc')->name('complete_kyc');
        Route::get('successaeps', 'ServiceType\AepsController@successaeps')->name('successaeps');
        Route::post('resend_otp', 'ServiceType\AepsController@resend_otp')->name('resend_otp');
        Route::get('aeps_ekyc', 'ServiceType\AepsController@aeps_ekyc')->name('aeps_ekyc');
        Route::post('complete_kyc', 'ServiceType\AepsController@complete_kyc')->name('complete_kyc');

        
    });
    
        Route::post('icici/validate_transaction', 'ServiceType\AepsController@validate_transaction')->name('validate_transaction');

        Route::get('icici_cash_deposit', 'ServiceType\IciciController@index')->name('icici_cash_deposit');
   Route::post('icici/icicicashdeposit', 'ServiceType\AepsController@icicicashdeposit')->name('icicicashdeposit');
     
      //  Route::post('icici_cash_deposit', 'ServiceType\IciciController@aeps_transaction')->name('icici_transaction');
        Route::get('icici/icicionboarding', 'ServiceType\AepsController@icicionboarding')->name('icicionboarding');
        
        Route::post('icici/iciciresendcashdeposit', 'ServiceType\AepsController@iciciresendcashdeposit')->name('iciciresendcashdeposit');
        Route::post('icici/icicitransactionlist', 'ServiceType\AepsController@icicitransactionlist')->name('icicitransactionlist');
        Route::get('icici/onboarding', function () {
            return view('modules.icici.onboarding');
        });
        //Route::post('icici/onboarding', 'ServiceType\IciciController@aeps_onboarding')->name('icici_onboarding');
        Route::get('icici/otp', 'ServiceType\IciciController@aeps_otp')->name('otp');
        Route::post('icici/validate_iciciotp', 'ServiceType\AepsController@validate_iciciotp')->name('validate_iciciotp');

       // Route::post('resend_otp', 'ServiceType\IciciController@resend_otp')->name('resend_otp');
        Route::get('icici/ekyc', 'ServiceType\IciciController@icici_ekyc')->name('icici_ekyc');
       // Route::post('icici/ekyc', 'ServiceType\IciciController@complete_kyc')->name('complete_kyc');


        //Routing For ICICI Cash Deposit
   // Route::get('icici_cash_deposit', 'ServiceType\IciciController@generateotp')->name('icici_Cash_deposit');
    //Routing End For ICICI Cash Deposit
    
    // Route::get('verify-2fa', 'HomeController@verify2fa')->name('verify-2fa');
    // Route::post('check-2fa', 'HomeController@check2fa')->name('check-2fa');
    Route::get('two_factor', 'User\UserController@twoFactor')->name('two_factor');
    Route::post('two_factor', 'User\UserController@UpdatetwoFactor')->name('two_factor');

    Route::get('edit_user/{id}', 'User\UserController@create')->name('edit_user');
    Route::get('edit_retailer_user/{id}', 'User\UserController@editforretailer')->name('edit_retailer_user');
    
    Route::post('edit_user/{id}', 'User\UserController@store')->name('update_user');
    Route::post('updateretailerid/{id}', 'User\UserController@updateretailerid')->name('updateretailerid');
    Route::post('get_city_frm_state_id', 'User\UserController@getCityFromStateId')->name('getCityFromStateId');
    Route::post('get_user_frm_parent_role_id', 'User\UserController@getUserFromPrntRole')->name('getUserFromPrntRole');
    // Route::post('edit_user_post', 'User\UserController@create')->name('edit_user_post');

    Route::get('checkUserValueExists', 'User\UserController@checkUserValueExists')->name('checkUserValueExists');
    
    Route::get('edit_userekyc/{id}', 'User\UserController@edit_ekyc')->name('edit_userekyc');
    Route::post('update_userekyc', 'User\UserController@update_ekyc')->name('update_userekyc');


    Route::get('reset_user_pwd/{id}', 'User\UserController@resetUserPwd')->name('reset_user_pwd');
    Route::post('change_user_Pwd', 'User\UserController@chgPwd')->name('change_user_Pwd');
    Route::post('change_user_mpin', 'User\UserController@chgMpin')->name('change_user_mpin');

    // Upload File Route
    Route::post('upload-file', 'Other\FileUploadController@fileUpload')->name('fileUpload');

    // Bank Account Routes
    Route::get('bank_account', 'Bank\BankAcController@index')->name('bank_account');

    Route::get('bank_list', 'Bank\BankAcController@getAllBanks')->name('bank_list');
    Route::get('delete_bank/{id}', 'Bank\BankAcController@deletebank')->name('delete_bank');
    Route::get('edit_bank', 'Bank\BankAcController@editBank')->name('bank_account.edit_bank');
    Route::post('add_bank', 'Bank\BankAcController@addUpdateBannk')->name('add_bank');
    Route::post('bank_logo', 'Bank\BankAcController@uploadBankLogo')->name('bank_logo');


    // Balance Request routes
    Route::get('balance_request', 'Bank\BalanceRequestController@index')->name('balance_request');
    Route::post('balance_request', 'Bank\BalanceRequestController@index')->name('balance_request_filter');
    //Check User Mpin
    Route::get('verifyUserMpin', 'User\UserController@verifyUserMpin')->name('verifyUserMpin');

    // Transaction Reports routes
    Route::get('transaction_report', 'Reports\TransactionReportsController@index')->name('transaction_report');
    Route::post('transaction_report', 'Reports\TransactionReportsController@index')->name('filter_transaction_report');
    Route::get('transaction_details', 'Reports\TransactionReportsController@transactionDetails')->name('transaction_details');
    Route::post('transaction_details', 'Reports\TransactionReportsController@transactionDetails')->name('filter_transaction_details');

    // Commission Reports routes
    Route::get('commission_report', 'Reports\CommissionReportsController@index')->name('commission_report');
    Route::post('commission_report', 'Reports\CommissionReportsController@index')->name('filter_commision_report');

   
    // Passbook routes
    Route::get('passbook', 'Reports\PassbookController@index')->name('passbook');
    Route::post('passbook', 'Reports\PassbookController@index')->name('filter_passbook');
    
    //Virtual Account Report Routes
    Route::get('user_virtual_account_report', 'Payment\OnlinePaymentController@getVirtualAccountReport')->name('user_virtual_account_report');
    Route::post('user_virtual_account_report', 'Payment\OnlinePaymentController@getVirtualAccountReport')->name('user_virtual_account_report_filter');
    
    //QR Code Payment Report
    Route::get('user_qr_code_report', 'Payment\OnlinePaymentController@getQrCodeReport')->name('user_qr_code_account_report');
    Route::post('user_qr_code_report', 'Payment\OnlinePaymentController@getQrCodeReport')->name('user_qr_code_report_filter');

    // PayTM Gateway Routes
    // Route::post('/payment', 'Payment\PaytmController@pay')->name('payment');
    // Route::post('/payment/status', 'Payment\PaytmController@paymentCallback')->name('paymentStatus');
    
    // Cashfree Gateway Routes
    Route::post('/payment', 'Payment\OnlinePaymentController@payCashfree')->name('payment');
    Route::get('/payment/status', 'Payment\OnlinePaymentController@paymentCallback')->name('paymentStatusCashfree');
    
    //Razorpay Gateway Routes
    // Route::post('/payment', 'Payment\RazorpayController@pay')->name('payment');
    // Route::post('/payment/status', 'Payment\RazorpayController@paymentCallback')->name('paymentStatus');

    Route::get('update_profile_pic', 'User\UserController@updateUserProfilePicApi')->name('update_profile_pic');

     //complaint
    Route::get('complaints', 'Complaint\ComplaintController@index')->name('complaints');
    Route::post('complaints', 'Complaint\ComplaintController@index')->name('filter_complaints');
    Route::post('complaint_reply', 'Complaint\ComplaintController@complaintReply')->name('complaint_reply');
    Route::post('change_complaint_status', 'Complaint\ComplaintController@change_complaint_status')->name('change_complaint_status');
    Route::post('changetime', 'Complaint\ComplaintController@changeDefaultTime')->name('changetime');
    
    Route::post('dist_add_complaint', 'Complaint\ComplaintController@addComplaintDis')->name('dist_add_complaint');
    
    

    //template
    Route::get('template', 'Complaint\TemplateController@index')->name('template');
    Route::post('template', 'Complaint\TemplateController@index')->name('filter_template');
    Route::post('add_template', 'Complaint\TemplateController@addTemplate')->name('add_template');
    Route::post('edit_template', 'Complaint\TemplateController@editTemplate')->name('edit_template');
    Route::post('delete_template', 'Complaint\TemplateController@deleteTemplate')->name('delete_template');

    Route::post('add_complaint', 'Complaint\ComplaintController@addComplaint')->name('add_complaint');


    Route::get('offers-notice', 'OffersNotice\OffersNoticeController@index')->name('offers-notice');
                 
    Route::get('view_tds/{id}', 'TDS\TDSController@viewTDSById')->name('view_tds');

    Route::view('/qr_code', 'modules.sample.qr_code');
    Route::post('generate_qr', 'Sample\SampleController@generateQR')->name('generate_qr');

    Route::post('get_fos_frm_dis', 'User\UserController@getFosByDist')->name('getFosByDist');
   
    Route::get('receipt_data/{order}/{subcharge}', 'ServiceType\MoneyTransferController@receiptData')->name('receipt_data');

    Route::get('invoice/{order}/{subcharge}', 'HomeController@showInvoice')->name('invoice');

    Route::get('delete_beneficiary_api/{response_status}/{response_msg}', 'ServiceType\MoneyTransferController@deleteBeneficiaryAPI')->name('delete_beneficiary_api');

    Route::get('notifications', 'User\UserController@userNotification')->name('notifications');
   
});

//Authentication Failure
Route::get('/authentication-failure', function () {
    $response['status'] = false;
    $response['message'] = "Authentication Failure!!";
    return $response;;
});


//Authentication Failure
Route::get('/permission-forbidden', function () {
    $response['status'] = false;
    $response['message'] = "Permission Forbidden!!";
    return $response;;
});
