<?php
return [
    "WEBSITE_NAME" => "www.paymamaapp.in",
    "WEBSITE_BASE_URL" => $websiteBaseUrl = "https://paymamaapp.in/",
    "DEFAULT_HELPLINE" => "8374913154",
    "EXCEPTION_MSG" => "Something went wrong",
    "AD_LOGIN_PIN" => 123456,
    //user Role constants starts here
    "ADMIN" => 1,
    "DISTRIBUTOR" => 2,
    "FOS" => 3,
    "RETAILER" => 4,
    "CUSTOMER" => 5,
    // "MASTER_DISTRIBUTOR" => 6,
    "SUB_ADMIN" => 6,
    "RAZORPAY_KEY" => "rzp_live_yzkdaheJQB0KYy",
    "RAZORPAY_SECRET" => "d8d7I3mc0L1oaSFiCa8yBWRf",
    "CASHFREE_COLLECT_KEY" => "CF181206E0FI1B5H8LCI2QI",
    "CASHFREE_COLLECT_SECRET" => "5e9641382c064b79e93b8c82a7d518131aa5cd8f",
    "CASHFREE_PAYOUT_KEY" => "CF154737C6L0GFLJDDO8UP2KFU3G",
    "CASHFREE_PAYOUT_SECRET" => "11bd7c7cc53eb959188b10fcc7c282a067ad4997",
    "APICLUB_API_KEY" => "35aea0ed0c44d3b5906e4da7c914b8d6",
    "TELEGRAM_BOT_ID" => "2059334712:AAGrn4QSKM3tD2rGsFKRjRtGUX5QZYE2Sf8",

    // Role Alias starts
    "ROLE_ALIAS" => [
        "SYSTEM_ADMIN" => "admin",
        "SUB_ADMIN" => "sub_admin",
        "DISTRIBUTOR" => "distributor",
        "FOS" => "fos",
        "RETAILER" => "retailer",
        "CUSTOMER" => "customer",
    ],
    // Role Alias ends

    // SMS Settings constants starts here
    "AUTH_KEY" => 'fc32ec171ca366efcacb86719bdcc54f',
    "SENDER_ID" => 'PYMAMA',

    // Activated Status
    "ACTIVE" => "YES",
    "IN-ACTIVE" => "NO",

    // Delete Status
    "DELETED" => 1,
    "NOT-DELETED" => 0,

    //SPAM User
    "SPAM" => 1,
    "NOT-SPAM" => 0,

    // Distributor Payment Type starts
    "DT_PAYMENT_TYPE" => [
        "CREDIT" => "Credit",
        "CASH" => "Cash",
    ],
    // Distributor Payment Type ends
    "RECHARGE" => array(
        "NAME" => "RECHARGE",
        "VALUE" => array(
            "MOBILE" => 1,
            "DTH" => 2,
        ),
    ),
    "MONEY_TRANSFER" => 4,
    "MONEY_TRANSFER_LABEL" => "MONEY_TRANSFER",
    "AEPS" => 5,
    "AEPS_LABEL" => "AEPS",
    "BILL_PAYMENTS" => 3,
    "BILL_PAYMENTS_LABEL" => "BILL_PAYMENTS",
    "COMPLAINT_LABEL" => "COMPLAINT",
    "UPI_TRANSFER_LABEL" => "UPI_TRANSFER",
    "AADHAR_PAY_LABEL" => "AADHAR_PAY",
    "ICICI_CASH_DEPOSIT_LABEL" => "ICICI_CASH_DEPOSIT",
    "Mini_Statement_LABEL" => "Mini_Statement",
    "BALANCE_INQUIRY_LABEL" => "BALANCE_INQUIRY",
    
    

    // API-Method Mapping starts
    "API_ALIAS_METHOD" => [
        "ambika" => "rechargeByAmbika",
        "ambika_new" => "rechargeByAmbikaNew",
        "m_robotics" => "rechargeByMRobotics",
        "champion" => "rechargeByChampion",
        "samriddhipay" => "rechargeBySamriddhipay",
        "api_master" => "rechargeByApiMaster",
        "technopayment" => "rechargeByTechnoPayment",
    ],
    // API-Method Mapping ends

    // Service Type Alias starts
    "SERVICE_TYPE_ALIAS" => [
        "MOBILE_PREPAID" => "mobile_prepaid",
        "MOBILE_POSTPAID" => "mobile_postpaid",
        "DTH" => "dth",
        "BILL_PAYMENTS" => "bill_payments",
        "MONEY_TRANSFER" => "money_transfer",
        "AEPS" => "aeps",
        "UPI_TRANSFER" => "upi_transfer",
        "ICICI_CASH_DEPOSIT" => "icicicd",
        "AADHAR_PAY" => "ap",
        "Mini_Statement" => "Mini_Statement",
        "BALANCE_INQUIRY" => "BALANCE_INQUIRY",
    ],
    // Service Type Alias ends

    "APP_DTLS_ALIAS" => [
        "VERSION" => "app_version",
    ],

    "SERVICE_ID"=>[
        "electricity" => 17,
        "fast_tag" => 18,
        "loan_payment" => 19,
        "credit_card_bill" => 20,
        "water" => 24,
        // "education" => 27,
        "education" => 25,
        "life_insurance" => 26,
        "insurance" => 27,
        "broadband" => 28,
        "lpg" => 29,
        // "lpg" => 28,
        "postpaid"=>43
    ],

    //Home slider
    "HOME_BANNER_SLIDER" => $websiteBaseUrl ."api/slidder_banners_link",
    //Notification
    "USER_NOTIFICATION_LIST" => $websiteBaseUrl ."api/get_notification_logs",



    // SMS Template Alias starts
    "SMS_TEMPLATE_ALIAS" => [
        "RESET_USER_PWD" => [
            "name" => "reset_user_pwd",
            "allowed_tags" => array("Username (:username)", "MPIN (:mpin)", "Password (:password)"),
        ],
        "USER_REGISTRATION" => [
            "name" => "user_registration",
            "allowed_tags" => array("Username (:username)", "MPIN (:mpin)", "Password (:password)"),
        ],
        "KYC_APPROVAL" => [
            "name" => "kyc_approval",
            "allowed_tags" => array(),
        ],
        "BALANCE_ADDED" => [
            "name" => "balance_added",
            "allowed_tags" => array("Last Balance (:last_balance_amount)", " Updated Balance (:updated_balance_amount)", " Amount (:amount)"),
        ],
        "BALANCE_DEDUCT" => [
            "name" => "balance_deduct",
            "allowed_tags" => array("Last Balance (:last_balance_amount)", " Updated Balance (:updated_balance_amount)", " Amount (:amount)"),
        ],
        "REVERT_OTP_MSG" => [
            "name" => "revert_otp_msg",
            "allowed_tags" => array("Revert Amount (:revert_amount)", " OTP (:otp)"),
        ],
        "VERIFY_USER_OTP" => [
            "name" => "verify_user_otp",
            "allowed_tags" => array("(:otp)"),
        ],
        "SENDER_REGISTRATION" => [
            "name" => "sender_registration",
            "allowed_tags" => array("(:otp)"),
        ],
    ],
    // SMS Template Alias ends

    // Transaction Reports Details starts

    "RECHARGE_ADMIN_TD" => [
        array(
            "name" => "Date",
            "label" => "trans_date",
        ),
        array(
            "name" => "Retailer",
            "label" => "user_id",
        ),
        array(
            "name" => "Mobile",
            "label" => "mobile",
        ),
        array(
            "name" => "Order ID",
            "label" => "order_id",
        ),
        array(
            "name" => "API",
            "label" => "api_id",
        ),
        array(
            "name" => "Response",
            "label" => "transaction_msg",
        ),
        array(
            "name" => "Operator",
            "label" => "operator_id",
        ),
        array(
            "name" => "Transaction ID",
            "label" => "transaction_id",
        ),
        array(
            "name" => "Customer Mobile",
            "label" => "mobileno",
        ),
        array(
            "name" => "Amount",
            "label" => "total_amount",
        ),
        array(
            "name" => "Source",
            "label" => "source",
        ),
        array(
            "name" => "IP Address",
            "label" => "ip_address",
        ),
        array(
            "name" => "Status",
            "label" => "order_status",
        ),
        array(
            "name" => "Action",
            "label" => "action",
        ),
    ],

    "RECHARGE_RT_TD" => [
        array(
            "name" => "Date",
            "label" => "trans_date",
        ),
        array(
            "name" => "Order ID",
            "label" => "order_id",
        ),
        array(
            "name" => "Operator",
            "label" => "operator_id",
        ),
        array(
            "name" => "Transaction ID",
            "label" => "transaction_id",
        ),
        array(
            "name" => "Customer Mobile",
            "label" => "mobileno",
        ),
        array(
            "name" => "Amount",
            "label" => "total_amount",
        ),
        array(
            "name" => "Status",
            "label" => "order_status",
        ),
        // array(
        //     "name" => "Complaint",
        //     "label" => "template",
        // ),
        array(
            "name" => "Action",
            "label" => "action",
        ),
    ],

    "RECHARGE_DIS_TD" => [
        array(
            "name" => "Date",
            "label" => "trans_date",
        ),
        array(
            "name" => "Retailer",
            "label" => "user_id",
        ),
        array(
            "name" => "Mobile",
            "label" => "mobile",
        ),
        array(
            "name" => "Order ID",
            "label" => "order_id",
        ),
        array(
            "name" => "Operator",
            "label" => "operator_id",
        ),
        array(
            "name" => "Transaction ID",
            "label" => "transaction_id",
        ),
        array(
            "name" => "Customer Mobile",
            "label" => "mobileno",
        ),
        array(
            "name" => "Amount",
            "label" => "total_amount",
        ),
        array(
            "name" => "Status",
            "label" => "order_status",
        ),
    ],

    "MONEY_TRANSFER_ADMIN_TD" => [
        array(
            "name" => "Date",
            "label" => "trans_date",
        ),
        array(
            "name" => "Retailer",
            "label" => "user_id",
        ),
        array(
            "name" => "Mobile",
            "label" => "mobile",
        ),
        array(
            "name" => "SMART ID",
            "label" => "order_id",
        ),
        array(
            "name" => "API",
            // "label" => "api_id",
            "label" => "api_alias",
        ),
        array(
            "name" => "Response",
            "label" => "transaction_msg",
        ),
        array(
            "name" => "Operator",
            "label" => "operator_id",
        ),
        array(
            "name" => "Bank Name ",
            "label" => "bank_name",
        ),
        array(
            "name" => "Bank Transaction ID",
            "label" => "bank_transaction_id",
        ),
        array(
            "name" => "Customer Mobile",
            "label" => "mobileno",
        ),
        array(
            "name" => "Beneficiary Name",
            "label" => "imps_name",
        ),
        array(
            "name" => "Account No",
            "label" => "bank_account_number",
        ),
        array(
            "name" => "Mode",
            "label" => "transaction_type",
        ),
        array(
            "name" => "Amount",
            "label" => "total_amount",
        ),
        array(
            "name" => "Transfer Charge",
            "label" => "PayableCharge",
        ),
        array(
            "name" => "Total Amount",
            "label" => "FinalAmount",
        ),
        array(
            "name" => "CCF",
            "label" => "CCFcharges",
        ),

        array(
            "name" => "Cashback",
            "label" => "Cashback",
        ),
        array(
            "name" => "TDS",
            "label" => "TDSamount",
        ),
        array(
            "name" => "Source",
            "label" => "source",
        ),
        array(
            "name" => "IP Address",
            "label" => "ip_address",
        ),
        array(
            "name" => "Status",
            "label" => "order_status",
        ),
        array(
            "name" => "Action",
            "label" => "action",
        ),
    ],
    
    "AEPS_ADMIN_TD" => [
         array(
            "name" => "DATE & TIME",
            "label" => "trans_date",
        ),
        array(
            "name" => "RETAILER ID",
            "label" => "retailer_id",
        ),
        array(
            "name" => "RETAILER STORE NAME",
            "label" => "user_id",
        ),
        array(
            "name" => "RETAILER MOBILE NO.",
            "label" => "user_mobile_no",
        ),
        // array(
        //     "name" => "RRN",
        //     "label" => "rrnno",
        // ),
        array(
            "name" => "API NAME",
            // "label" => "api_id",
            "label" => "api_id",
        ),
        // array(
        //     "name" => "MOBILE NUMBER",
        //     "label" => "mobile",
        // ),
        array(
            "name" => "ORDER ID",
            "label" => "order_id",
        ),
        // array(
        //     "name" => "Operator",
        //     "label" => "operator_id",
        // ),
        // array(
        //     "name" => "TRANSACTION ID",
        //     "label" => "transaction_id",
        // ),
        array(
            "name" => "TRANSACTION ID",
            "label" => "transaction_id",
        ),
        array(
            "name" => "CLIENT REF ID",
            "label" => "client_reference_id",
        ),
        array(
            "name" => "RRN",
            "label" => "rrnno",
        ),
        array(
            "name" => "CUSTOMER MOBILE NUMBER",
            "label" => "mobileno",
        ),
        array(
            "name" => "AADHAR NUMBER",
            "label" => "aadharnumber",
        ),
        array(
            "name" => "BANK NAME",
            "label" => "aeps_bank_id",
        ),
        // array(
        //     "name" => "RRN",
        //     "label" => "rrnno",
        // ),
        array(
            "name" => "AMOUNT",
            "label" => "total_amount",
        ),
        array(
            "name" => "USER ACCOUNT BALANCE",
            "label" => "aeps_balance",
        ),
        array(
            "name" => "RETAILER COMMISSION",
            "label" => "retailer_commision",
        ),
        array(
            "name" => "DISTRIBUTOR COMMISSION",
            "label" => "distributor_commision",
        ),
         array(
            "name" => "ADMIN COMMISSION",
            "label" => "admin_commision",
        ),
        array(
            "name" => "STATUS",
            "label" => "order_status",
        ),
        array(
            "name" => "RESPONSE",
            "label" => "response_msg",
        ),
       array(
            "name" => "SOURCE",
            "label" => "source",
        ),
        array(
            "name" => "IP Address",
            "label" => "ip_address",
        ),
       
        array(
            "name" => "Action",
            "label" => "action",
        ),
    ],
    
    "AADHAR_PAY_ADMIN_TD" => [
        //   array(
        //     "name" => "API NAME",
        //     // "label" => "api_id",
        //     "label" => "api_id",
        // ),
         array(
            "name" => "DATE & TIME",
            "label" => "trans_date",
        ),
        // array(
        //     "name" => "Retailer",
        //     "label" => "user_id",
        // ),
        array(
            "name" => "RETAILER ID",
            "label" => "retailer_id",
        ),
        array(
            "name" => "RETAILER STORE NAME",
            "label" => "user_id",
        ),
        array(
            "name" => "RETAILER MOBILE NUMBER",
            "label" => "user_mobile_no",
        ),
        // array(
        //     "name" => "Merchant ID",
        //     "label" => "user_id",
        // ),
        // array(
        //     "name" => "MOBILE NUMBER",
        //     "label" => "mobile",
        // ),
         array(
            "name" => "API",
            "label" => "api_id",
        ),
        array(
            "name" => "ORDER ID",
            "label" => "order_id",
        ),
         array(
            "name" => "TRANSACTION ID",
            "label" => "transaction_id",
        ),
         array(
            "name" => "CLIENT REFRENCE ID",
            "label" => "client_reference_id",
        ),
        
         array(
            "name" => "RRN",
            "label" => "rrnno",
        ),
        // array(
        //     "name" => "API",
        //     // "label" => "api_id",
        //     "label" => "api_name",
        // ),
        // array(
        //     "name" => "Response",
        //     "label" => "transaction_msg",
        // ),
        array(
            "name" => "CUSTOMER MOBILE NUMBER",
            "label" => "mobileno",
        ),
       
        array(
            "name" => "AADHAR NUMBER",
            "label" => "aadharnumber",
        ),
         array(
            "name" => "BANK NAME",
            "label" => "aeps_bank_id",
        ),
        // array(
        //     "name" => "Client Reference ID",
        //     "label" => "bank_name",
        // ),
        // array(
        //     "name" => "Bank Reference ID ",
        //     "label" => "bank_name",
        // ),
        array(
            "name" => "AMOUNT",
            "label" => "total_amount",
        ),
         array(
            "name" => "USER ACCOUNT BALANCE",
            "label" => "aeps_balance",
        ),
         array(
            "name" => "RETAILER CHARGE",
            "label" => "retailer_commision",
        ),
        // array(
        //     "name" => "COMMISSION",
        //     "label" => "PayableCharge",
        // ),
        array(
            "name" => "DISTRIBUTOR COMMISSION",
            "label" => "distributor_commision",
        ),
        array(
            "name" => "ADMIN COMMISSION",
            "label" => "admin_commision",
        ),
        
         array(
            "name" => "STATUS",
            "label" => "order_status",
        ),
         array(
            "name" => "RESPONSE",
            "label" => "transaction_msg",
        ),
        array(
            "name" => "SOURCE",
            "label" => "source",
        ),
        array(
            "name" => "IP ADDRESS",
            "label" => "ip_address",
        ),
       
        array(
            "name" => "Action",
            "label" => "action",
        ),
    ],
    
    "ICICI_CASH_DEPOSIT_ADMIN_TD" => [
       array(
            "name" => "DATE & TIME",
            "label" => "trans_date",
        ),
        // array(
        //     "name" => "Retailer",
        //     "label" => "user_id",
        // ),
        array(
            "name" => "RETAILER ID",
            "label" => "retailer_id",
        ),
        array(
            "name" => "RETAILER STORE NAME",
            "label" => "user_id",
        ),
        array(
            "name" => "RETAILER MOBILE NUMBER",
            "label" => "user_mobile_no",
        ),
        // array(
        //     "name" => "Merchant ID",
        //     "label" => "user_id",
        // ),
        // array(
        //     "name" => "MOBILE NUMBER",
        //     "label" => "mobile",
        // ),
         array(
            "name" => "API NAME",
            "label" => "api_alias",
        ),
        array(
            "name" => "ORDER ID",
            "label" => "order_id",
        ),
         array(
            "name" => "TRANSACTION ID",
            "label" => "transaction_id",
        ),
         array(
            "name" => "CLIENT REFRENCE ID",
            "label" => "client_reference_id",
        ),
        
         array(
            "name" => "RRN",
            "label" => "rrnno",
        ),
        // array(
        //     "name" => "API",
        //     // "label" => "api_id",
        //     "label" => "api_name",
        // ),
        // array(
        //     "name" => "Response",
        //     "label" => "transaction_msg",
        // ),
        array(
            "name" => "CUSTOMER MOBILE NUMBER",
            "label" => "mobileno",
        ),
        array(
            "name" => "Account Holder Name",
            "label" => "account_holder_name",
        ),
        array(
            "name" => "Account No.",
            "label" => "bank_account_no",
        ),
      
        array(
            "name" => "Bank Name",
            "label" => "bank_nameicici",
        ),
        array(
            "name" => "Amount",
            "label" => "total_amount",
        ),
        array(
            "name" => "Retailer Comm/Charge",
            "label" => "PayableCharge",
        ),
        array(
            "name" => "DISTRIBUTOR COMMISSION",
            "label" => "distributor_commision",
        ),
        array(
            "name" => "ADMIN COMMISSION",
            "label" => "admin_commision",
        ),
        
         array(
            "name" => "STATUS",
            "label" => "order_status",
        ),
         array(
            "name" => "RESPONSE",
            "label" => "response_msg",
        ),
        array(
            "name" => "SOURCE",
            "label" => "source",
        ),
        array(
            "name" => "IP ADDRESS",
            "label" => "ip_address",
        ),
       
        array(
            "name" => "Action",
            "label" => "action",
        ),
    ],
    
    "AADHAR_PAY_DIS_TD" => [
        array(
            "name" => "DATE",
            "label" => "trans_date",
        ),
        // array(
        //     "name" => "Retailer",
        //     "label" => "user_id",
        // ),
        array(
            "name" => "RETAILER ID",
            "label" => "retailer_id",
        ),
        array(
            "name" => "RETAILER STORE NAME",
            "label" => "user_id",
        ),
        // array(
        //     "name" => "Merchant ID",
        //     "label" => "user_id",
        // ),
        array(
            "name" => "RETAILER MOBILE NUMBER",
            "label" => "mobile",
        ),
        array(
            "name" => "ORDER ID",
            "label" => "order_id",
        ),
         array(
            "name" => "TRANSACTION ID",
            "label" => "transaction_id",
        ),
         array(
            "name" => "CLIENT REFRENCE ID",
            "label" => "client_reference_id",
        ),
         array(
            "name" => "RRN",
            "label" => "rrnno",
        ),
        // array(
        //     "name" => "API",
        //     // "label" => "api_id",
        //     "label" => "api_name",
        // ),
        // array(
        //     "name" => "Response",
        //     "label" => "transaction_msg",
        // ),
        array(
            "name" => "MOBILE NUMBER",
            "label" => "mobileno",
        ),
       
        array(
            "name" => "AADHAR NUMBER",
            "label" => "aadharnumber",
        ),
         array(
            "name" => "BANK NAME",
            "label" => "aeps_bank_id",
        ),
        // array(
        //     "name" => "Client Reference ID",
        //     "label" => "bank_name",
        // ),
        // array(
        //     "name" => "Bank Reference ID ",
        //     "label" => "bank_name",
        // ),
        array(
            "name" => "AMOUNT",
            "label" => "total_amount",
        ),
         array(
            "name" => "USER ACCOUNT BALANCE",
            "label" => "aeps_balance",
        ),
         array(
            "name" => "RETAILER CHARGE",
            "label" => "retailer_commision",
        ),
        // array(
        //     "name" => "COMMISSION",
        //     "label" => "PayableCharge",
        // ),
        array(
            "name" => "DISTRIBUTOR COMMISSION",
            "label" => "distributor_commision",
        ),
        
        array(
            "name" => "Status",
            "label" => "order_status",
        ),
        array(
            "name" => "Response",
            "label" => "transaction_msg",
        ),
        
        array(
            "name" => "Action",
            "label" => "action",
        ),
        // array(
        //     "name" => "Source",
        //     "label" => "source",
        // ),
    ],
    
    "ICICI_CASH_DEPOSIT_DIS_TD" => [
     array(
            "name" => "DATE & TIME",
            "label" => "trans_date",
        ),
        // array(
        //     "name" => "Retailer",
        //     "label" => "user_id",
        // ),
        array(
            "name" => "RETAILER ID",
            "label" => "retailer_id",
        ),
        array(
            "name" => "RETAILER STORE NAME",
            "label" => "user_id",
        ),
        array(
            "name" => "RETAILER MOBILE NUMBER",
            "label" => "user_mobile_no",
        ),
        // array(
        //     "name" => "Merchant ID",
        //     "label" => "user_id",
        // ),
        // array(
        //     "name" => "MOBILE NUMBER",
        //     "label" => "mobile",
        // ),
        array(
            "name" => "ORDER ID",
            "label" => "order_id",
        ),
         array(
            "name" => "TRANSACTION ID",
            "label" => "transaction_id",
        ),
         array(
            "name" => "CLIENT REFRENCE ID",
            "label" => "client_reference_id",
        ),
        
         array(
            "name" => "RRN",
            "label" => "rrnno",
        ),
        // array(
        //     "name" => "API",
        //     // "label" => "api_id",
        //     "label" => "api_name",
        // ),
        // array(
        //     "name" => "Response",
        //     "label" => "transaction_msg",
        // ),
        array(
            "name" => "CUSTOMER MOBILE NUMBER",
            "label" => "mobileno",
        ),
        array(
            "name" => "Account Holder Name",
            "label" => "account_holder_name",
        ),
        array(
            "name" => "Account No.",
            "label" => "bank_account_no",
        ),
      
        array(
            "name" => "Bank Name",
            "label" => "bank_nameicici",
        ),
        array(
            "name" => "Amount",
            "label" => "total_amount",
        ),
        array(
            "name" => "Retailer Comm/Charge",
            "label" => "PayableCharge",
        ),
        array(
            "name" => "DISTRIBUTOR COMMISSION",
            "label" => "distributor_commision",
        ),
        
         array(
            "name" => "STATUS",
            "label" => "order_status",
        ),
         array(
            "name" => "RESPONSE",
            "label" => "transaction_msg",
        ),


        array(
            "name" => "Action",
            "label" => "action",
        ),
    ],

    //AADHAR PAY AND CASH DEPOSIT RETAILER PANEL TABLE COLUMNS
    "AADHAR_PAY_RT_TD" => [
               array(
            "name" => "ORDER ID",
            "label" => "order_id",
        ),
         array(
            "name" => "TRANSACTION ID",
            "label" => "transaction_id",
        ),
         array(
            "name" => "CLIENT REFRENCE ID",
            "label" => "client_reference_id",
        ),
        
         array(
            "name" => "RRN",
            "label" => "rrnno",
        ),
        // array(
        //     "name" => "API",
        //     // "label" => "api_id",
        //     "label" => "api_name",
        // ),
        // array(
        //     "name" => "Response",
        //     "label" => "transaction_msg",
        // ),
        array(
            "name" => "CUSTOMER MOBILE NUMBER",
            "label" => "mobileno",
        ),
        array(
            "name" => "Account Holder Name",
            "label" => "bank_name",
        ),
        array(
            "name" => "Account No.",
            "label" => "bank_name",
        ),
      
        array(
            "name" => "Bank Name",
            "label" => "bank_name",
        ),
        array(
            "name" => "Amount",
            "label" => "total_amount",
        ),
        array(
            "name" => "Retailer Comm/Charge",
            "label" => "PayableCharge",
        ),
        array(
            "name" => "DISTRIBUTOR COMMISSION",
            "label" => "distributor_commision",
        ),
        
         array(
            "name" => "STATUS",
            "label" => "order_status",
        ),
         array(
            "name" => "RESPONSE",
            "label" => "transaction_msg",
        ),


        array(
            "name" => "Action",
            "label" => "action",
        ),

    ],
    "AADHAR_PAY_DT_TD" => [
        array(
            "name" => "Date",
            "label" => "trans_date",
        ),
        array(
            "name" => "ORDER ID",
            "label" => "order_id",
        ),
        array(
            "name" => "TRANSACTION ID",
            "label" => "transaction_id",
        ),
         array(
            "name" => "CLIENT REFRENCE ID",
            "label" => "client_reference_id",
        ),
          array(
            "name" => "RRN",
            "label" => "rrnno",
        ),
        array(
            "name" => "MOBILE NUMBER",
            "label" => "mobile",
        ),
        array(
            "name" => "AADHAR NUMBER",
            "label" => "aadharnumber",
        ),
        array(
            "name" => "BANK NAME",
            "label" => "aeps_bank_id",
        ),
        array(
            "name" => "AMOUNT",
            "label" => "total_amount",
        ),
        
       array(
            "name" => "USER ACCOUNT BALANCE",
            "label" => "aeps_balance",
        ),
        array(
            "name" => "CHARGE",
            "label" => "retailer_commision",
        ),
        
         array(
            "name" => "STATUS",
            "label" => "order_status",
        ),
       
        array(
            "name" => "RESPONSE",
            "label" => "transaction_msg",
        ),
        array(
            "name" => "Action",
            "label" => "action",
        ),
    ],
    // "AADHAR_PAY_ADMIN_TD" => [
    //       array(
    //         "name" => "API NAME",
    //         // "label" => "api_id",
    //         "label" => "api_id",
    //     ),
    //     array(
    //         "name" => "Date",
    //         "label" => "trans_date",
    //     ),
        
    //     array(
    //         "name" => "ORDER ID",
    //         "label" => "order_id",
    //     ),
    //     array(
    //         "name" => "TRANSACTION ID",
    //         "label" => "transaction_id",
    //     ),
    //      array(
    //         "name" => "CLIENT REFRENCE ID",
    //         "label" => "client_reference_id",
    //     ),
    //       array(
    //         "name" => "RRN",
    //         "label" => "rrnno",
    //     ),
    //     array(
    //         "name" => "MOBILE NUMBER",
    //         "label" => "mobile",
    //     ),
    //     array(
    //         "name" => "AADHAR NUMBER",
    //         "label" => "aadharnumber",
    //     ),
    //     array(
    //         "name" => "BANK NAME",
    //         "label" => "aeps_bank_id",
    //     ),
    //     array(
    //         "name" => "AMOUNT",
    //         "label" => "total_amount",
    //     ),
        
    //   array(
    //         "name" => "USER ACCOUNT BALANCE",
    //         "label" => "aeps_balance",
    //     ),
    //     array(
    //         "name" => "CHARGE",
    //         "label" => "retailer_commision",
    //     ),
        
    //      array(
    //         "name" => "STATUS",
    //         "label" => "order_status",
    //     ),
       
    //     array(
    //         "name" => "RESPONSE",
    //         "label" => "transaction_msg",
    //     ),
    //     array(
    //         "name" => "Action",
    //         "label" => "action",
    //     ),
    // ],
    
    "ICICI_CASH_DEPOSIT_RT_TD" => [
        // array(
        //     "name" => "Date",
        //     "label" => "trans_date",
        // ),
       
       
        // array(
        //     "name" => "Merchant ID",
        //     "label" => "superMerchantId",
        // ),
         array(
            "name" => "ORDER ID",
            "label" => "order_id",
        ),
         array(
            "name" => "TRANSACTION ID",
            "label" => "transaction_id",
        ),
         array(
            "name" => "CLIENT REF ID",
            "label" => "client_reference_id",
        ),
        
         array(
            "name" => "RRN",
            "label" => "rrnno",
        ),
        // array(
        //     "name" => "API",
        //     // "label" => "api_id",
        //     "label" => "api_name",
        // ),
        // array(
        //     "name" => "Response",
        //     "label" => "transaction_msg",
        // ),
        array(
            "name" => "CUSTOMER MOBILE NUMBER",
            "label" => "mobileno",
        ),
        array(
            "name" => "Account Holder Name",
            "label" => "account_holder_name",
        ),
        array(
            "name" => "ACCOUNT NUMBER",
            "label" => "bank_account_no",
        ),
      
        array(
            "name" => "Bank",
            "label" => "bank_nameicici",
        ),
        array(
            "name" => "Amount",
            "label" => "total_amount",
        ),
        array(
            "name" => "Retailer Comm/Charge",
            "label" => "PayableCharge",
        ),
       
        
         array(
            "name" => "STATUS",
            "label" => "order_status",
        ),
         array(
            "name" => "RESPONSE",
            "label" => "transaction_msg",
        ),


        array(
            "name" => "Action",
            "label" => "action",
        ),
    ],
    //ENDS HERE

    "MONEY_TRANSFER_RT_TD" => [
        array(
            "name" => "DATE & TIME",
            "label" => "trans_date",
        ),
        array(
            "name" => "SMART ID",
            "label" => "order_id",
        ),
        array(
            "name" => "OPERATOR",
            "label" => "operator_id",
        ),
        
        // array(
        //     "name" => "Bank Transaction ID",
        //     "label" => "bank_transaction_id",
        // ),
        array(
            "name" => "SENDER NUMBER",
            "label" => "mobileno",
        ),
        array(
            "name" => "BENIFICIARY NAME",
            "label" => "imps_name",
        ),
        array(
            "name" => "ACCOUNT NUMBER",
            "label" => "bank_account_number",
        ),
        array(
            "name" => "BANK",
            "label" => "recipient_id",
        ),
        array(
            "name" => "IFSC CODE",
            "label" => "ifsc_code",
        ),
        array(
            "name" => "MODE",
            "label" => "transaction_type",
        ),
        array(
            "name" => "AMOUNT",
            "label" => "total_amount",
        ),
        array(
            "name" => "CCF",
            "label" => "CCFcharges",
        ),
        array(
            "name" => "CASHBACK",
            "label" => "Cashback",
        ),
        array(
            // "name" => "Transfer Charge",
            "name" => "CHARGE",
            "label" => "PayableCharge",
        ),
       
        
        array(
            "name" => "TDS",
            "label" => "TDSamount",
        ),

        array(
            // "name" => "Total Amount",
            "name" => "NET PAYABLE",
            "label" => "FinalAmount",
        ),

        array(    
            "name" => "BANK REFERENCE ID",
            "label" => "bank_transaction_id",
        ),
        array(
            "name" => "STATUS",
            "label" => "order_status",
        ),
        // array(
        //     "name" => "COMPLAINT",
        //     "label" => "template",
        // ),
        array(
            "name" => "ACTION",
            "label" => "action",
        ),
        
    ],

    "MONEY_TRANSFER_DIS_TD" => [
        array(
            "name" => "Date",
            "label" => "trans_date",
        ),
        array(
            "name" => "Retailer",
            "label" => "user_id",
        ),
        array(
            "name" => "Mobile",
            "label" => "mobile",
        ),
        array(
            "name" => "SMART ID",
            "label" => "order_id",
        ),
        array(
            "name" => "Operator",
            "label" => "operator_id",
        ),
        array(
            "name" => "Bank Name ",
            "label" => "bank_name",
        ),
        array(
            "name" => "Bank Transaction ID",
            "label" => "bank_transaction_id",
        ),
        array(
            "name" => "Customer Mobile",
            "label" => "mobileno",
        ),
        array(
            "name" => "Beneficiary Name",
            "label" => "imps_name",
        ),
        array(
            "name" => "Account No",
            "label" => "bank_account_number",
        ),
        array(
            "name" => "Mode",
            "label" => "transaction_type",
        ),
        array(
            "name" => "Amount",
            "label" => "total_amount",
        ),
        array(
            "name" => "Transfer Charge",
            "label" => "PayableCharge",
        ),
        array(
            "name" => "Total Amount",
            "label" => "FinalAmount",
        ),
        array(
            "name" => "CCF",
            "label" => "CCFcharges",
        ),

        array(
            "name" => "Cashback",
            "label" => "Cashback",
        ),
        array(
            "name" => "TDS",
            "label" => "TDSamount",
        ),

        array(
            "name" => "Status",
            "label" => "order_status",
        ),
        array(
            "name" => "Action",
            "label" => "action",
        ),
    ],

    "UPI_TRANSFER_ADMIN_TD" => [
        array(
            "name" => "Date",
            "label" => "trans_date",
        ),
        array(
            "name" => "Retailer",
            "label" => "user_id",
        ),
        array(
            "name" => "Mobile",
            "label" => "mobile",
        ),
        array(
            "name" => "SMART ID",
            "label" => "order_id",
        ),
        array(
            "name" => "API",
            "label" => "api_id",
        ),
        array(
            "name" => "Response",
            "label" => "transaction_msg",
        ),
        array(
            "name" => "Operator",
            "label" => "operator_id",
        ),
       
        array(
            "name" => "Bank Transaction ID",
            "label" => "bank_transaction_id",
        ),
        array(
            "name" => "Customer Mobile",
            "label" => "mobileno",
        ),
        array(
            "name" => "Beneficiary Name",
            "label" => "imps_name",
        ),
        array(
            "name" => "UPI ID",
            "label" => "bank_account_number",
        ),
        array(
            "name" => "Mode",
            "label" => "transaction_type",
        ),
        array(
            "name" => "Amount",
            "label" => "total_amount",
        ),
        array(
            "name" => "Transfer Charge",
            "label" => "PayableCharge",
        ),
        array(
            "name" => "Total Amount",
            "label" => "FinalAmount",
        ),
        array(
            "name" => "CCF",
            "label" => "CCFcharges",
        ),

        array(
            "name" => "Cashback",
            "label" => "Cashback",
        ),
        array(
            "name" => "TDS",
            "label" => "TDSamount",
        ),
        array(
            "name" => "Source",
            "label" => "source",
        ),
        array(
            "name" => "IP Address",
            "label" => "ip_address",
        ),
        array(
            "name" => "Status",
            "label" => "order_status",
        ),
        array(
            "name" => "Action",
            "label" => "action",
        ),
    ],

    "UPI_TRANSFER_RT_TD" => [
        array(
            "name" => "DATE & TIME",
            "label" => "trans_date",
        ),
        array(
            "name" => "SMART ID",
            "label" => "order_id",
        ),
        array(
            "name" => "OPERATOR",
            "label" => "operator_id",
        ),
       
        
        array(
            "name" => "SENDER NUMBER",
            "label" => "mobileno",
        ),
        array(
            "name" => "BENIFICIARY NAME",
            "label" => "imps_name",
        ),
        array(
            "name" => "UPI ID",
            "label" => "bank_account_number",
        ),
        array(
            "name" => "MODE",
            "label" => "transaction_type",
        ),
        array(
            "name" => "AMOUNT",
            "label" => "total_amount",
        ),
        array(
            "name" => "CCF",
            "label" => "CCFcharges",
        ),
        array(
            "name" => "CASHBACK",
            "label" => "Cashback",
        ),
        array(
            // "name" => "Transfer Charge",
            "name" => "CHARGE",
            "label" => "PayableCharge",
        ),
        array(
            "name" => "TDS",
            "label" => "TDSamount",
        ),
        array(
            // "name" => "Total Amount",
            "name" => "NET PAYABLE",
            "label" => "FinalAmount",
        ),
       

        array(
            "name" => "BANK REFERENCE ID",
            "label" => "bank_transaction_id",
        ),
        
        array(
            "name" => "Status",
            "label" => "order_status",
        ),
        // array(
        //     "name" => "Complaint",
        //     "label" => "template",
        // ),
        array(
            "name" => "ACTION",
            "label" => "action",
        ),
        
    ],

    "UPI_TRANSFER_DIS_TD" => [
        array(
            "name" => "Date",
            "label" => "trans_date",
        ),
        array(
            "name" => "Retailer",
            "label" => "user_id",
        ),
        array(
            "name" => "Mobile",
            "label" => "mobile",
        ),
        array(
            "name" => "SMART ID",
            "label" => "order_id",
        ),
        array(
            "name" => "Operator",
            "label" => "operator_id",
        ),
        
        array(
            "name" => "Bank Transaction ID",
            "label" => "bank_transaction_id",
        ),
        array(
            "name" => "Customer Mobile",
            "label" => "mobileno",
        ),
        array(
            "name" => "Beneficiary Name",
            "label" => "imps_name",
        ),
        array(
            "name" => "UPI ID",
            "label" => "bank_account_number",
        ),
        array(
            "name" => "Mode",
            "label" => "transaction_type",
        ),
        array(
            "name" => "Amount",
            "label" => "total_amount",
        ),
        array(
            "name" => "Transfer Charge",
            "label" => "PayableCharge",
        ),
        array(
            "name" => "Total Amount",
            "label" => "FinalAmount",
        ),
        array(
            "name" => "CCF",
            "label" => "CCFcharges",
        ),

        array(
            "name" => "Cashback",
            "label" => "Cashback",
        ),
        array(
            "name" => "TDS",
            "label" => "TDSamount",
        ),

        array(
            "name" => "Status",
            "label" => "order_status",
        ),
        array(
            "name" => "Action",
            "label" => "action",
        ),
    ],

    "BILL_PAYMENTS_ADMIN_TD" => [
        array(
            "name" => "Date",
            "label" => "trans_date",
        ),
        array(
            "name" => "Retailer",
            "label" => "user_id",
        ),
        array(
            "name" => "Mobile",
            "label" => "mobile",
        ),
        array(
            "name" => "Order ID",
            "label" => "order_id",
        ),
        array(
            "name" => "Biller Name",
            "label" => "billerName",
        ),
        array(
            "name" => "API",
            "label" => "api_id",
        ),
        array(
            "name" => "Response",
            "label" => "transaction_msg",
        ),
        // array(
        //     "name" => "Service Name",
        //     "label" => "service_id",
        // ),
        array(
            "name" => "Operator",
            "label" => "operator_id",
        ),
        array(
            "name" => "Transaction ID",
            "label" => "transaction_id",
        ),
        array(
            "name" => "Account No",
            // "label" => "account_no",
            "label" => "response_msg",
        ),
        array(
            "name" => "Customer Name",
            "label" => "customer_name",
        ),
        array(
            "name" => "Customer Mobile",
            "label" => "mobileno",
        ),
        array(
            "name" => "Amount",
            "label" => "total_amount",
        ),
        array(
            "name" => "Transfer Charge",
            "label" => "charge_amount",
        ),
        array(
            "name" => "Status",
            "label" => "order_status",
        ),
    ],

    "BILL_PAYMENTS_RT_TD" => [
        array(
            "name" => "Date",
            "label" => "trans_date",
        ),
        array(
            "name" => "Order ID",
            "label" => "order_id",
        ),
        array(
            "name" => "Operator",
            "label" => "operator_id",
        ),
        array(
            "name" => "Biller Name",
            "label" => "billerName",
        ),
        array(
            "name" => "Transaction ID",
            "label" => "transaction_id",
        ),
        array(
            "name" => "Account No",
            // "label" => "account_no",
            "label" => "response_msg",
        ),
        array(
            "name" => "Customer Name",
            "label" => "customer_name",
        ),
        array(
            "name" => "Customer Mobile",
            "label" => "mobileno",
        ),
        array(
            "name" => "Amount",
            "label" => "total_amount",
        ),
        array(
            "name" => "Transfer Charge",
            "label" => "charge_amount",
        ),
        array(
            "name" => "Status",
            "label" => "transaction_status",
        ),
        // array(
        //     "name" => "Complaint",
        //     "label" => "template",
        // ),
        array(
            "name" => "Action",
            "label" => "action",
        ),
    ],

    "BILL_PAYMENTS_DIS_TD" => [
        array(
            "name" => "Date",
            "label" => "trans_date",
        ),
        array(
            "name" => "Retailer",
            "label" => "user_id",
        ),
        array(
            "name" => "Mobile",
            "label" => "mobile",
        ),
        array(
            "name" => "Order ID",
            "label" => "order_id",
        ),
        array(
            "name" => "Operator",
            "label" => "operator_id",
        ),
        array(
            "name" => "Biller Name",
            "label" => "billerName",
        ),
        array(
            "name" => "Service Name",
            "label" => "service_id",
        ),
        array(
            "name" => "Transaction ID",
            "label" => "transaction_id",
        ),
        array(
            "name" => "Account no",
            // "label" => "account_no",
            "label" => "response_msg",
        ),
        array(
            "name" => "Customer Name",
            "label" => "customer_name",
        ),
        array(
            "name" => "Customer Mobile",
            "label" => "mobileno",
        ),
        array(
            "name" => "Amount",
            "label" => "total_amount",
        ),
        array(
            "name" => "Transfer Charge",
            "label" => "charge_amount",
        ),
        array(
            "name" => "Status",
            "label" => "order_status",
        ),
    ],

    "AEPS_RT_TD" => [
        //  array(
        //     "name" => "Sr. No.",
        //     "label" => "",
        // ),
        array(
            "name" => "DATE & TIME",
            "label" => "trans_date",
        ),
        array(
            "name" => "ORDER ID",
            "label" => "order_id",
        ),
        array(
            "name" => "TRANSACTION ID",
            "label" => "transaction_id",
        ),
        array(
            "name" => "CLIENT REF ID",
            "label" => "client_reference_id",
        ),
        array(
            "name" => "RRN",
            "label" => "rrnno",
        ),
        array(
            "name" => "MOBILE NUMBER",
            "label" => "mobileno",
        ),
        array(
            "name" => "AADHAR NUMBER",
            "label" => "aadharnumber",
        ),
        array(
            "name" => "BANK NAME",
            "label" => "aeps_bank_id",
        ),
        // array(
        //     "name" => "RRN",
        //     "label" => "rrnno",
        // ),
        array(
            "name" => "AMOUNT",
            "label" => "total_amount",
        ),
        array(
            "name" => "USER ACCOUNT BALANCE",
            "label" => "aeps_balance",
        ),
        array(
            "name" => "COMMISSION",
            "label" => "retailer_commision",
        ),
        
        array(
            "name" => "STATUS",
            "label" => "order_status",
        ),
        array(
            "name" => "RESPONSE",
            "label" => "response_msg",
        ),
         array(
            "name" => "Action",
            "label" => "action",
        )
        // array(
        //     "name" => "COMPLAINT & RECEIPT ICON",
        //     "label" => "bank_name",
        // ),
        // array(
        //     "name" => "Status",
        //     "label" => "transaction_status",
        // ),
        // array(
        //     "name" => "Complaint",
        //     "label" => "complaint",
        // ),
    ],

    "AEPS_DIS_TD" => [
        array(
            "name" => "DATE & TIME",
            "label" => "trans_date",
        ),
         array(
            "name" => "RETAILER ID",
            "label" => "retailer_id",
        ),
        array(
            "name" => "RETAILER STORE NAME",
            "label" => "user_id",
        ),
        // array(
        //     "name" => "RRN",
        //     "label" => "rrnno",
        // ),
        array(
            "name" => "MOBILE NUMBER",
            "label" => "mobile",
        ),
        array(
            "name" => "ORDER ID",
            "label" => "order_id",
        ),
        // array(
        //     "name" => "Operator",
        //     "label" => "operator_id",
        // ),
        array(
            "name" => "TRANSACTION ID",
            "label" => "transaction_id",
        ),
       
        array(
            "name" => "CLIENT REF ID",
            "label" => "client_reference_id",
        ),
        array(
            "name" => "RRN",
            "label" => "rrnno",
        ),
        array(
            "name" => "MOBILE NUMBER",
            "label" => "mobileno",
        ),
        array(
            "name" => "AADHAR NUMBER",
            "label" => "aadharnumber",
        ),
        array(
            "name" => "BANK NAME",
            "label" => "aeps_bank_id",
        ),
        // array(
        //     "name" => "RRN",
        //     "label" => "rrnno",
        // ),
        array(
            "name" => "AMOUNT",
            "label" => "total_amount",
        ),
        array(
            "name" => "USER ACCOUNT BALANCE",
            "label" => "aeps_balance",
        ),
        array(
            "name" => "RETAILER COMMISSION",
            "label" => "retailer_commision",
        ),
        array(
            "name" => "DISTRIBUTOR COMMISSION",
            "label" => "distributor_commision",
        ),
        
        array(
            "name" => "STATUS",
            "label" => "order_status",
        ),
        array(
            "name" => "RESPONSE",
            "label" => "response_msg",
        ),
         array(
            "name" => "Action",
            "label" => "action",
        )
    ],
    
    //MINISTATEMENT START HERE
    "Mini_Statement_RT_TD" => [
        //  array(
        //     "name" => "Sr. No.",
        //     "label" => "",
        // ),
        array(
            "name" => "DATE & TIME",
            "label" => "trans_date",
        ),
        array(
            "name" => "ORDER ID",
            "label" => "order_id",
        ),
        array(
            "name" => "TRANSACTION ID",
            "label" => "transaction_id",
        ),
        array(
            "name" => "CLIENT REF ID",
            "label" => "client_reference_id",
        ),
         array(
            "name" => "RRN",
            "label" => "rrnno",
        ),
        array(
            "name" => "MOBILE NUMBER",
            "label" => "mobileno",
        ),
        array(
            "name" => "AADHAR NUMBER",
            "label" => "aadharnumber",
        ),
        array(
            "name" => "BANK NAME",
            "label" => "aeps_bank_id",
        ),
         array(
            "name" => "AVAILABLE BALANCE",
            "label" => "aeps_balance",
        ),
        // array(
        //     "name" => "RRN",
        //     "label" => "rrnno",
        // ),
        // array(
        //     "name" => "AMOUNT",
        //     "label" => "total_amount",
        // ),
        array(
            "name" => "COMMISSION",
            "label" => "retailer_commision",
        ),
       
        array(
            "name" => "STATUS",
            "label" => "order_status",
        ),
        array(
            "name" => "RESPONSE",
            "label" => "response_msg",
        ),
         array(
            "name" => "Action",
            "label" => "action",
        )
        // array(
        //     "name" => "COMPLAINT & RECEIPT ICON",
        //     "label" => "bank_name",
        // ),
        // array(
        //     "name" => "Status",
        //     "label" => "transaction_status",
        // ),
        // array(
        //     "name" => "Complaint",
        //     "label" => "complaint",
        // ),
     ],

     "Mini_Statement_DIS_TD" => [
        //  array(
        //     "name" => "Sr. No.",
        //     "label" => "",
        // ),
        array(
            "name" => "DATE & TIME",
            "label" => "trans_date",
        ),
         array(
            "name" => "RETAILER ID",
            "label" => "retailer_id",
        ),
        array(
            "name" => "RETAILER STORE NAME",
            "label" => "user_id",
        ),
        array(
            "name" => "RETAILER MOBILE NO.",
            "label" => "user_mobile_no",
        ),
        array(
            "name" => "ORDER ID",
            "label" => "order_id",
        ),
        array(
            "name" => "TRANSACTION ID",
            "label" => "transaction_id",
        ),
        array(
            "name" => "CLIENT REF ID",
            "label" => "client_reference_id",
        ),
       
        array(
            "name" => "RRN NO.",
            "label" => "rrnno",
        ),
         array(
            "name" => "MOBILE NUMBER",
            "label" => "mobileno",
        ),
        array(
            "name" => "AADHAR NUMBER",
            "label" => "aadharnumber",
        ),
        array(
            "name" => "BANK NAME",
            "label" => "aeps_bank_id",
        ),
        array(
            "name" => "AVAILABLE BALANCE",
            "label" => "aeps_balance",
        ),
        // array(
        //     "name" => "RRN",
        //     "label" => "rrnno",
        // ),
        // array(
        //     "name" => "AMOUNT",
        //     "label" => "total_amount",
        // ),
        array(
            "name" => "RETAILER COMMISSION",
            "label" => "retailer_commision",
        ),
         array(
            "name" => "DISTRIBUTOR COMMISSION",
            "label" => "distributor_commision",
        ),
        // array(
        //     "name" => "USER ACCOUNT BALANCE",
        //     "label" => "aeps_balance",
        // ),
        array(
            "name" => "STATUS",
            "label" => "order_status",
        ),
        array(
            "name" => "RESPONSE",
            "label" => "response_msg",
        ),
         array(
            "name" => "Action",
            "label" => "action",
        )
        // array(
        //     "name" => "COMPLAINT & RECEIPT ICON",
        //     "label" => "bank_name",
        // ),
        // array(
        //     "name" => "Status",
        //     "label" => "transaction_status",
        // ),
        // array(
        //     "name" => "Complaint",
        //     "label" => "complaint",
        // ),
     
     ],
     "Mini_Statement_ADMIN_TD" => [
        //  array(
        //     "name" => "Sr. No.",
        //     "label" => "",
        // ),
        array(
            "name" => "DATE & TIME",
            "label" => "trans_date",
        ),
         array(
            "name" => "RETAILER ID",
            "label" => "retailer_id",
        ),
        array(
            "name" => "RETAILER STORE NAME",
            "label" => "user_id",
        ),
        array(
            "name" => "RETAILER MOBILE NUMBER",
            "label" => "user_mobile_no",
        ),
         array(
            "name" => "API NAME",
            // "label" => "api_id",
            "label" => "api_id",
        ),
        array(
            "name" => "ORDER ID",
            "label" => "order_id",
        ),
        array(
            "name" => "TRANSACTION ID",
            "label" => "transaction_id",
        ),
        array(
            "name" => "CLIENT REF ID",
            "label" => "client_reference_id",
        ),
       
        array(
            "name" => "RRN NO.",
            "label" => "rrnno",
        ),
        array(
            "name" => "CUSTOMER MOBILE NUMBER",
            "label" => "mobileno",
        ),
        array(
            "name" => "AADHAR NUMBER",
            "label" => "aadharnumber",
        ),
        array(
            "name" => "BANK NAME",
            "label" => "aeps_bank_id",
        ),
        array(
            "name" => "AVAILABLE BALANCE",
            "label" => "aeps_balance",
        ),
        // array(
        //     "name" => "RRN",
        //     "label" => "rrnno",
        // ),
        // array(
        //     "name" => "AMOUNT",
        //     "label" => "total_amount",
        // ),
        array(
            "name" => "RETAILER COMMISSION",
            "label" => "retailer_commision",
        ),
         array(
            "name" => "DISTRIBUTOR COMMISSION",
            "label" => "distributor_commision",
        ),
        array(
            "name" => "ADMIN COMMISSION",
            "label" => "admin_commision",
        ),
        // array(
        //     "name" => "USER ACCOUNT BALANCE",
        //     "label" => "aeps_balance",
        // ),
        array(
            "name" => "STATUS",
            "label" => "order_status",
        ),
        array(
            "name" => "RESPONSE",
            "label" => "response_msg",
        ),
          array(
            "name" => "SOURCE",
            "label" => "source",
        ),
         array(
            "name" => "IP Address",
            "label" => "ip_address",
        ),
         array(
            "name" => "Action",
            "label" => "action",
        )
        // array(
        //     "name" => "COMPLAINT & RECEIPT ICON",
        //     "label" => "bank_name",
        // ),
        // array(
        //     "name" => "Status",
        //     "label" => "transaction_status",
        // ),
        // array(
        //     "name" => "Complaint",
        //     "label" => "complaint",
        // ),
     
     ],
      "BALANCE_INQUIRY_ADMIN_TD" => [
        //  array(
        //     "name" => "Sr. No.",
        //     "label" => "",
        // ),
        array(
            "name" => "DATE & TIME",
            "label" => "trans_date",
        ),
         array(
            "name" => "RETAILER ID",
            "label" => "retailer_id",
        ),
        array(
            "name" => "RETAILER STORE NAME",
            "label" => "user_id",
        ),
        array(
            "name" => "RETAILER MOBILE NUMBER",
            "label" => "user_mobile_no",
        ),
         array(
            "name" => "API NAME",
            // "label" => "api_id",
            "label" => "api_id",
        ),
        array(
            "name" => "ORDER ID",
            "label" => "order_id",
        ),
        array(
            "name" => "TRANSACTION ID",
            "label" => "transaction_id",
        ),
        array(
            "name" => "CLIENT REF ID",
            "label" => "client_reference_id",
        ),
       
        array(
            "name" => "RRN NO.",
            "label" => "rrnno",
        ),
         array(
            "name" => "CUSTOMER MOBILE NUMBER",
            "label" => "mobileno",
        ),
        array(
            "name" => "AADHAR NUMBER",
            "label" => "aadharnumber",
        ),
        array(
            "name" => "BANK NAME",
            "label" => "aeps_bank_id",
        ),
        array(
            "name" => "AVAILABLE BALANCE",
            "label" => "aeps_balance",
        ),
        // array(
        //     "name" => "RRN",
        //     "label" => "rrnno",
        // ),
        // array(
        //     "name" => "AMOUNT",
        //     "label" => "total_amount",
        // ),
        // array(
        //     "name" => "RETAILER COMMISSION",
        //     "label" => "retailer_commission",
        // ),
        //  array(
        //     "name" => "DISTRIBUTOR COMMISSION",
        //     "label" => "distributor_commission",
        // ),
        // array(
        //     "name" => "ADMIN COMMISSION",
        //     "label" => "admin_commission",
        // ),
        // array(
        //     "name" => "USER ACCOUNT BALANCE",
        //     "label" => "aeps_balance",
        // ),
        array(
            "name" => "STATUS",
            "label" => "order_status",
        ),
        array(
            "name" => "RESPONSE",
            "label" => "response_msg",
        ),
        array(
            "name" => "SOURCE",
            "label" => "source",
        ),
         array(
            "name" => "IP Address",
            "label" => "ip_address",
        ),
         array(
            "name" => "Action",
            "label" => "action",
        )
        // array(
        //     "name" => "COMPLAINT & RECEIPT ICON",
        //     "label" => "bank_name",
        // ),
        // array(
        //     "name" => "Status",
        //     "label" => "transaction_status",
        // ),
        // array(
        //     "name" => "Complaint",
        //     "label" => "complaint",
        // ),
     
     ],
    
    //MINISTATREMENT ENDS HERE

    // Transaction Reports Details ends
    
    // Commission Reports Details starts

    "RECHARGE_COM_ADMIN_TD" => [
        array(
            "name" => "Date",
            "label" => "trans_date",
        ),
        array(
            "name" => "Retailer",
            "label" => "user_id",
        ),
        array(
            "name" => "Order ID",
            "label" => "order_id",
        ),
        array(
            "name" => "API",
            "label" => "api_id",
        ),
        array(
            "name" => "Operator",
            "label" => "operator_id",
        ),
        array(
            "name" => "Customer Mobile",
            "label" => "mobile",
        ),
        array(
            "name" => "Amount",
            "label" => "total_amount",
        ),
        array(
            "name" => "RT Commission",
            "label" => "retailer_commission",
        ),
        array(
            "name" => "DT Commission",
            "label" => "distributor_commission",
        ),
        array(
            "name" => "Admin Commission",
            "label" => "admin_commission",
        ),
    ],

    "RECHARGE_COM_RT_TD" => [
        array(
            "name" => "Date",
            "label" => "trans_date",
        ),
        array(
            "name" => "Order ID",
            "label" => "order_id",
        ),
        array(
            "name" => "Operator",
            "label" => "operator_id",
        ),
        array(
            "name" => "Customer Mobile",
            "label" => "mobile",
        ),
        array(
            "name" => "Amount",
            "label" => "total_amount",
        ),
        array(
            "name" => "Commission",
            "label" => "retailer_commission",
        ),
    ],

    "RECHARGE_COM_DIS_TD" => [
        array(
            "name" => "Date",
            "label" => "trans_date",
        ),
        array(
            "name" => "Retailer",
            "label" => "user_id",
        ),
        array(
            "name" => "Order ID",
            "label" => "order_id",
        ),
        array(
            "name" => "Operator",
            "label" => "operator_id",
        ),
        array(
            "name" => "Amount",
            "label" => "total_amount",
        ),
        array(
            "name" => "RT commission",
            "label" => "retailer_commission",
        ),
        array(
            "name" => "DT commission",
            "label" => "distributor_commission",
        ),
    ],

    "MONEY_TRANSFER_COM_ADMIN_TD" => [
        array(
            "name" => "Date",
            "label" => "trans_date",
        ),
        array(
            "name" => "Retailer",
            "label" => "user_id",
        ),
        array(
            "name" => "SMART ID",
            "label" => "order_id",
        ),
        array(
            "name" => "API",
            "label" => "api_id",
        ),
        array(
            "name" => "Operator",
            "label" => "operator_id",
        ),
        array(
            "name" => "Mode",
            "label" => "mode",
        ),
        array(
            "name" => "Account No",
            "label" => "bank_account_number",
        ),
        array(
            "name" => "Amount",
            "label" => "total_amount",
        ),
        array(
            "name" => "Retailer Charge",
            "label" => "retailer_commission",
        ),
        array(
            "name" => "DT commission",
            "label" => "distributor_commission",
        ),
        array(
            "name" => "Admin commission",
            "label" => "admin_commission",
        ),
    ],

    "MONEY_TRANSFER_COM_RT_TD" => [
    ],

    "MONEY_TRANSFER_COM_DIS_TD" => [
        array(
            "name" => "Date",
            "label" => "trans_date",
        ),
        array(
            "name" => "Retailer",
            "label" => "user_id",
        ),
        array(
            "name" => "SMART ID",
            "label" => "order_id",
        ),
        array(
            "name" => "Operator",
            "label" => "operator_id",
        ),
        array(
            "name" => "Mode",
            "label" => "mode",
        ),
        array(
            "name" => "Account No",
            "label" => "bank_account_number",
        ),
        array(
            "name" => "Amount",
            "label" => "total_amount",
        ),
        array(
            "name" => "RT Charge",
            "label" => "retailer_commission",
        ),
        array(
            "name" => "DT Commission",
            "label" => "distributor_commission",
        ),
    ],

    "UPI_TRANSFER_COM_ADMIN_TD" => [
        array(
            "name" => "Date",
            "label" => "trans_date",
        ),
        array(
            "name" => "Retailer",
            "label" => "user_id",
        ),
        array(
            "name" => "SMART ID",
            "label" => "order_id",
        ),
        array(
            "name" => "API",
            "label" => "api_id",
        ),
        array(
            "name" => "Operator",
            "label" => "operator_id",
        ),
        array(
            "name" => "Mode",
            "label" => "mode",
        ),
        array(
            "name" => "Account No",
            "label" => "bank_account_number",
        ),
        array(
            "name" => "Amount",
            "label" => "total_amount",
        ),
        array(
            "name" => "Retailer Charge",
            "label" => "retailer_commission",
        ),
        array(
            "name" => "DT commission",
            "label" => "distributor_commission",
        ),
        array(
            "name" => "Admin commission",
            "label" => "admin_commission",
        ),
    ],

    "UPI_TRANSFER_COM_RT_TD" => [
    ],

    "UPI_TRANSFER_COM_DIS_TD" => [
        array(
            "name" => "Date",
            "label" => "trans_date",
        ),
        array(
            "name" => "Retailer",
            "label" => "user_id",
        ),
        array(
            "name" => "SMART ID",
            "label" => "order_id",
        ),
        array(
            "name" => "Operator",
            "label" => "operator_id",
        ),
        array(
            "name" => "Mode",
            "label" => "mode",
        ), 
        array(
            "name" => "Account No",
            "label" => "bank_account_number",
        ),
        array(
            "name" => "Account",
            "label" => "bank_account_number",
        ),
        array(
            "name" => "Amount",
            "label" => "total_amount",
        ),
        array(
            "name" => "RT Charge",
            "label" => "retailer_commission",
        ),
        array(
            "name" => "DT Commission",
            "label" => "distributor_commission",
        ),
    ],

    "BILL_PAYMENTS_COM_ADMIN_TD" => [
        array(
            "name" => "Date",
            "label" => "trans_date",
        ),
        array(
            "name" => "Retailer",
            "label" => "user_id",
        ),
        array(
            "name" => "Order ID",
            "label" => "order_id",
        ),
        array(
            "name" => "API",
            "label" => "api_id",
        ),
        // array(
        //     "name" => "Service Name",
        //     "label" => "service_id",
        // ),
        array(
            "name" => "Operator",
            "label" => "operator_id",
        ),
        array(
            "name" => "Customer",
            "label" => "customer_mobile",
        ),
        array(
            "name" => "Biller",
            "label" => "billerName",
        ),

        array(
            "name" => "Amount",
            "label" => "total_amount",
        ),
        array(
            "name" => "RT Commission",
            "label" => "retailer_commission",
        ),
        array(
            "name" => "DT Commission",
            "label" => "distributor_commission",
        ),
        array(
            "name" => "Admin Commission",
            "label" => "admin_commission",
        ),
    ],

    "BILL_PAYMENTS_COM_RT_TD" => [
        array(
            "name" => "Date",
            "label" => "trans_date",
        ),
        array(
            "name" => "Order ID",
            "label" => "order_id",
        ),
        // array(
        //     "name" => "Service Name",
        //     "label" => "service_id",
        // ),
        array(
            "name" => "Operator",
            "label" => "operator_id",
        ),
        array(
            "name" => "Customer",
            "label" => "customer_mobile",
        ),
        array(
            "name" => "Biller",
            "label" => "billerName",
        ),
        array(
            "name" => "Amount",
            "label" => "total_amount",
        ),
        array(
            "name" => "RT Commission",
            "label" => "retailer_commission",
        ),

    ],

    "BILL_PAYMENTS_COM_DIS_TD" => [
        array(
            "name" => "Date",
            "label" => "trans_date",
        ),
        array(
            "name" => "Retailer",
            "label" => "user_id",
        ),
        array(
            "name" => "Order ID",
            "label" => "order_id",
        ),
        // array(
        //     "name" => "Service Name",
        //     "label" => "service_id",
        // ),
        array(
            "name" => "Operator",
            "label" => "operator_id",
        ),
        array(
            "name" => "Customer",
            "label" => "customer_mobile",
        ),
        array(
            "name" => "Biller",
            "label" => "billerName",
        ),
        array(
            "name" => "Amount",
            "label" => "total_amount",
        ),
        array(
            "name" => "RT Commission",
            "label" => "retailer_commission",
        ),
        array(
            "name" => "DT Commission",
            "label" => "distributor_commission",
        ),
    ],

    "AEPS_COM_ADMIN_TD" => [
        array(
            "name" => "Date",
            "label" => "trans_date",
        ),
        array(
            "name" => "Retailer",
            "label" => "user_id",
        ),
        array(
            "name" => "Order ID",
            "label" => "order_id",
        ),
        array(
            "name" => "API",
            "label" => "api_id",
        ),
        array(
            "name" => "Operator",
            "label" => "operator_id",
        ),
        array(
            "name" => "Amount",
            "label" => "total_amount",
        ),
        array(
            "name" => "RT Commission",
            "label" => "retailer_commission",
        ),
        array(
            "name" => "DT Commission",
            "label" => "distributor_commission",
        ),
        array(
            "name" => "Admin Commission",
            "label" => "admin_commission",
        ),
    ],

    "AEPS_COM_RT_TD" => [
        array(
            "name" => "Date",
            "label" => "trans_date",
        ),
        array(
            "name" => "Order ID",
            "label" => "order_id",
        ),
        array(
            "name" => "Operator",
            "label" => "operator_id",
        ),
        array(
            "name" => "Aadhar No",
            "label" => "aadhar_no",
        ),
        array(
            "name" => "Amount",
            "label" => "total_amount",
        ),
        array(
            "name" => "Commission",
            "label" => "commission",
        ),
    ],

    "AEPS_COM_DIS_TD" => [
        array(
            "name" => "Date",
            "label" => "trans_date",
        ),
        array(
            "name" => "Retailer",
            "label" => "user_id",
        ),
        array(
            "name" => "Order ID",
            "label" => "order_id",
        ),
        array(
            "name" => "Operator",
            "label" => "Operator_id",
        ),
        array(
            "name" => "Amount",
            "label" => "total_amount",
        ),
        array(
            "name" => "RT Commission",
            "label" => "retailer_commission",
        ),
        array(
            "name" => "DT Commission",
            "label" => "distributor_commission",
        ),
    ],

    // Passbook Details starts
    "PASSBOOK_ADMIN_TD" => [
        array(
            "name" => "DATE & TIME",
            "label" => "trans_date",
        ),
        array(
            "name" => "TRANSACTION ID",
            "label" => "order_id",
        ),
        array(
            "name" => "TRANSACTION TYPE",
            "label" => "payment_type",
        ),
        array(
            "name" => "DESCRIPTION",
            "label" => "payment_mode",
        ),
        // array(
        //     "name" => "Order ID",
        //     "label" => "order_id",
        // ),
        // array(
        //     "name" => "CR/DR",
        //     "label" => "transaction_type",
        // ),
        array(
            "name" => "DEBIT AMOUNT",
            "label" => "debit_amount",
        ),
        array(
            "name" => "CREDIT AMOUNT",
            "label" => "credit_amount",
        ),
        
        // array(
        //     "name" => "Amount",
        //     "label" => "total_amount",
        // ),
        array(
            "name" => "CURRENT BALANCE",
            "label" => "balance",
        ),
    ],

    "PASSBOOK_RT_TD" => [
        array(
            "name" => "DATE & TIME",
            "label" => "trans_date",
        ),
        array(
            "name" => "SMART ID",
            "label" => "order_id",
        ),
        array(
            "name" => "TRANSACTION ID",
            "label" => "transaction_id",
        ),
        array(
            "name" => "TRANSACTION TYPE",
            "label" => "payment_type",
        ),
        array(
            "name" => "DESCRIPTION",
            "label" => "payment_mode",
        ),
        // array(
        //     "name" => "Order ID",
        //     "label" => "order_id",
        // ),
        // array(
        //     "name" => "CR/DR",
        //     "label" => "transaction_type",
        // ),

        array(
            "name" => "DEBIT AMOUNT",
            "label" => "debit_amount",
        ),
        array(
            "name" => "CREDIT AMOUNT",
            "label" => "credit_amount",
        ),
        
        // array(
        //     "name" => "Amount",
        //     "label" => "total_amount",
        // ),
        array(
            "name" => "CURRENT BALANCE",
            "label" => "balance",
        ),
    ],

    "PASSBOOK_DIS_TD" => [
        array(
            "name" => "DATE & TIME",
            "label" => "trans_date",
        ),
        array(
            "name" => "TRANSACTION ID",
            "label" => "order_id",
        ),
        array(
            "name" => "TRANSACTION TYPE",
            "label" => "payment_type",
        ),
        array(
            "name" => "DESCRIPTION",
            "label" => "payment_mode",
        ),
        // array(
        //     "name" => "Order ID",
        //     "label" => "order_id",
        // ),
        // array(
        //     "name" => "CR/DR",
        //     "label" => "transaction_type",
        // ),
        array(
            "name" => "DEBIT AMOUNT",
            "label" => "debit_amount",
        ),
        array(
            "name" => "CRDIT AMOUNT",
            "label" => "credit_amount",
        ),
        
        // array(
        //     "name" => "Amount",
        //     "label" => "total_amount",
        // ),
        array(
            "name" => "CURRENT BALANCE",
            "label" => "balance",
        ),
    ],
    // Passbook Details ends

    // commission Reports Details

    // Transaction Reports Filters starts
    "ALL_SRVC_TYP_ADMIN_FILTER" => [
        array(
            "id" => "from_date",
            "name" => "from_date",
            "label" => "From Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "to_date",
            "name" => "to_date",
            "label" => "To Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "state_id",
            "name" => "state_id",
            "label" => "Select State",
            "type" => "select",
        ),
        array(
            "id" => "city_id",
            "name" => "city_id",
            "label" => "Select City",
            "type" => "select",
        ),
        array(
            "id" => "store_category_id",
            "name" => "store_category_id",
            "label" => "Select Store Category",
            "type" => "select",
        ),
    ],

    "RECHARGE_ADMIN_FILTER" => [
        array(
            "id" => "from_date",
            "name" => "from_date",
            "label" => "From Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "to_date",
            "name" => "to_date",
            "label" => "To Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "username_mobile",
            "name" => "username_mobile",
            "label" => "Username/Mobile",
            "type" => "text",
        ),
        array(
            "id" => "api_id",
            "name" => "api_id",
            "label" => "Select API",
            "type" => "select",
        ),
        array(
            "id" => "service_id",
            "name" => "service_id",
            "label" => "Select Service Type",
            "type" => "select",
        ),
        array(
            "id" => "operator_id",
            "name" => "operator_id",
            "label" => "Select Operator",
            "type" => "select",
        ),
        array(
            "id" => "order_status",
            "name" => "order_status",
            "label" => "Select Status",
            "type" => "select",
        ),

    ],

    "RECHARGE_RT_FILTER" => [
        array(
            "id" => "from_date",
            "name" => "from_date",
            "label" => "From Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "to_date",
            "name" => "to_date",
            "label" => "To Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "operator_id",
            "name" => "operator_id",
            "label" => "Select Operator",
            "type" => "select",
        ),
        array(
            "id" => "order_status",
            "name" => "order_status",
            "label" => "Select Status",
            "type" => "select",
        ),
    ],

    "RECHARGE_DIS_FILTER" => [
        array(
            "id" => "from_date",
            "name" => "from_date",
            "label" => "From Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "to_date",
            "name" => "to_date",
            "label" => "To Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "operator_id",
            "name" => "operator_id",
            "label" => "Select Operator",
            "type" => "select",
        ),
        array(
            "id" => "order_status",
            "name" => "order_status",
            "label" => "Select Status",
            "type" => "select",
        ),
    ],

    "MONEY_TRANSFER_ADMIN_FILTER" => [
        array(
            "id" => "from_date",
            "name" => "from_date",
            "label" => "From Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "to_date",
            "name" => "to_date",
            "label" => "To Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "username_mobile",
            "name" => "username_mobile",
            "label" => "Username/Mobile",
            "type" => "text",
        ),
        array(
            "id" => "api_id",
            "name" => "api_id",
            "label" => "Select API",
            "type" => "select",
        ),
        array(
            "id" => "operator_id",
            "name" => "operator_id",
            "label" => "Select Operator",
            "type" => "select",
        ),
        array(
            "id" => "order_status",
            "name" => "order_status",
            "label" => "Select Status",
            "type" => "select",
        ),

    ],

    "MONEY_TRANSFER_RT_FILTER" => [
        array(
            "id" => "from_date",
            "name" => "from_date",
            "label" => "From Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "to_date",
            "name" => "to_date",
            "label" => "To Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "operator_id",
            "name" => "operator_id",
            "label" => "Select Operator",
            "type" => "select",
        ),
        array(
            "id" => "order_status",
            "name" => "order_status",
            "label" => "Select Status",
            "type" => "select",
        ),
    ],

    "MONEY_TRANSFER_DIS_FILTER" => [
        array(
            "id" => "from_date",
            "name" => "from_date",
            "label" => "From Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "to_date",
            "name" => "to_date",
            "label" => "To Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "operator_id",
            "name" => "operator_id",
            "label" => "Select Operator",
            "type" => "select",
        ),
        array(
            "id" => "order_status",
            "name" => "order_status",
            "label" => "Select Status",
            "type" => "select",
        ),
    ],

    "UPI_TRANSFER_ADMIN_FILTER" => [
        array(
            "id" => "from_date",
            "name" => "from_date",
            "label" => "From Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "to_date",
            "name" => "to_date",
            "label" => "To Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "username_mobile",
            "name" => "username_mobile",
            "label" => "Username/Mobile",
            "type" => "text",
        ),
        array(
            "id" => "api_id",
            "name" => "api_id",
            "label" => "Select API",
            "type" => "select",
        ),
        array(
            "id" => "operator_id",
            "name" => "operator_id",
            "label" => "Select Operator",
            "type" => "select",
        ),
        array(
            "id" => "order_status",
            "name" => "order_status",
            "label" => "Select Status",
            "type" => "select",
        ),

    ],

    "UPI_TRANSFER_RT_FILTER" => [
        array(
            "id" => "from_date",
            "name" => "from_date",
            "label" => "From Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "to_date",
            "name" => "to_date",
            "label" => "To Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "operator_id",
            "name" => "operator_id",
            "label" => "Select Operator",
            "type" => "select",
        ),
        array(
            "id" => "order_status",
            "name" => "order_status",
            "label" => "Select Status",
            "type" => "select",
        ),
    ],

    "UPI_TRANSFER_DIS_FILTER" => [
        array(
            "id" => "from_date",
            "name" => "from_date",
            "label" => "From Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "to_date",
            "name" => "to_date",
            "label" => "To Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "operator_id",
            "name" => "operator_id",
            "label" => "Select Operator",
            "type" => "select",
        ),
        array(
            "id" => "order_status",
            "name" => "order_status",
            "label" => "Select Status",
            "type" => "select",
        ),
    ],

    "BILL_PAYMENTS_ADMIN_FILTER" => [
        array(
            "id" => "from_date",
            "name" => "from_date",
            "label" => "From Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "to_date",
            "name" => "to_date",
            "label" => "To Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "username_mobile",
            "name" => "username_mobile",
            "label" => "Username/Mobile",
            "type" => "text",
        ),
        array(
            "id" => "api_id",
            "name" => "api_id",
            "label" => "Select API",
            "type" => "select",
        ),
        array(
            "id" => "operator_id",
            "name" => "operator_id",
            "label" => "Select Operator",
            "type" => "select",
        ),
        array(
            "id" => "order_status",
            "name" => "order_status",
            "label" => "Select Status",
            "type" => "select",
        ),

    ],

    "BILL_PAYMENTS_RT_FILTER" => [
        array(
            "id" => "from_date",
            "name" => "from_date",
            "label" => "From Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "to_date",
            "name" => "to_date",
            "label" => "To Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "operator_id",
            "name" => "operator_id",
            "label" => "Select Operator",
            "type" => "select",
        ),
        array(
            "id" => "order_status",
            "name" => "order_status",
            "label" => "Select Status",
            "type" => "select",
        ),
    ],

    "BILL_PAYMENTS_DIS_FILTER" => [
        array(
            "id" => "from_date",
            "name" => "from_date",
            "label" => "From Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "to_date",
            "name" => "to_date",
            "label" => "To Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "operator_id",
            "name" => "operator_id",
            "label" => "Select Operator",
            "type" => "select",
        ),
        array(
            "id" => "order_status",
            "name" => "order_status",
            "label" => "Select Status",
            "type" => "select",
        ),
    ],

    "AEPS_ADMIN_FILTER" => [
        array(
            "id" => "from_date",
            "name" => "from_date",
            "label" => "From Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "to_date",
            "name" => "to_date",
            "label" => "To Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "username_mobile",
            "name" => "username_mobile",
            "label" => "Username/Mobile",
            "type" => "text",
        ),
        array(
            "id" => "api_id",
            "name" => "api_id",
            "label" => "Select API",
            "type" => "select",
        ),
        array(
            "id" => "operator_id",
            "name" => "operator_id",
            "label" => "Select Operator",
            "type" => "select",
        ),
        array(
            "id" => "order_status",
            "name" => "order_status",
            "label" => "Select Status",
            "type" => "select",
        ),

    ],
     "BALANCE_INQUIRY_ADMIN_FILTER" => [
        array(
            "id" => "from_date",
            "name" => "from_date",
            "label" => "From Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "to_date",
            "name" => "to_date",
            "label" => "To Date",
            "type" => "date_picker",
        ),
       
        array(
            "id" => "order_status",
            "name" => "order_status",
            "label" => "Select Status",
            "type" => "select",
        ),

    ],
    
    "AADHAR_PAY_ADMIN_FILTER" => [
        array(
            "id" => "from_date",
            "name" => "from_date",
            "label" => "From Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "to_date",
            "name" => "to_date",
            "label" => "To Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "username_mobile",
            "name" => "username_mobile",
            "label" => "Username/Mobile",
            "type" => "text",
        ),
        array(
            "id" => "api_id",
            "name" => "api_id",
            "label" => "Select API",
            "type" => "select",
        ),
        array(
            "id" => "operator_id",
            "name" => "operator_id",
            "label" => "Select Operator",
            "type" => "select",
        ),
        array(
            "id" => "order_status",
            "name" => "order_status",
            "label" => "Select Status",
            "type" => "select",
        ),

    ],
    "ICICI_CASH_DEPOSIT_ADMIN_FILTER" => [
        array(
            "id" => "from_date",
            "name" => "from_date",
            "label" => "From Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "to_date",
            "name" => "to_date",
            "label" => "To Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "username_mobile",
            "name" => "username_mobile",
            "label" => "Username/Mobile",
            "type" => "text",
        ),
        array(
            "id" => "api_id",
            "name" => "api_id",
            "label" => "Select API",
            "type" => "select",
        ),
        array(
            "id" => "operator_id",
            "name" => "operator_id",
            "label" => "Select Operator",
            "type" => "select",
        ),
        array(
            "id" => "order_status",
            "name" => "order_status",
            "label" => "Select Status",
            "type" => "select",
        ),

    ],
    "AADHAR_PAY_RT_FILTER" => [
        array(
            "id" => "from_date",
            "name" => "from_date",
            "label" => "From Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "to_date",
            "name" => "to_date",
            "label" => "To Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "username_mobile",
            "name" => "username_mobile",
            "label" => "Username/Mobile",
            "type" => "text",
        ),
        // array(
        //     "id" => "api_id",
        //     "name" => "api_id",
        //     "label" => "Select API",
        //     "type" => "select",
        // ),
        // array(
        //     "id" => "operator_id",
        //     "name" => "operator_id",
        //     "label" => "Select Operator",
        //     "type" => "select",
        // ),
        array(
            "id" => "order_status",
            "name" => "order_status",
            "label" => "Select Status",
            "type" => "select",
        ),

    ],
    "ICICI_CASH_DEPOSIT_RT_FILTER" => [
        array(
            "id" => "from_date",
            "name" => "from_date",
            "label" => "From Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "to_date",
            "name" => "to_date",
            "label" => "To Date",
            "type" => "date_picker",
        ),
        
        array(
            "id" => "order_status",
            "name" => "order_status",
            "label" => "Select Status",
            "type" => "select",
        ),

    ],

    "AEPS_RT_FILTER" => [
        array(
            "id" => "from_date",
            "name" => "from_date",
            "label" => "From Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "to_date",
            "name" => "to_date",
            "label" => "To Date",
            "type" => "date_picker",
        ),
        // array(
        //     "id" => "operator_id",
        //     "name" => "operator_id",
        //     "label" => "Select Operator",
        //     "type" => "select",
        // ),
        array(
            "id" => "order_status",
            "name" => "order_status",
            "label" => "Select Status",
            "type" => "select",
        ),
    ],

    "AEPS_DIS_FILTER" => [
        array(
            "id" => "from_date",
            "name" => "from_date",
            "label" => "From Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "to_date",
            "name" => "to_date",
            "label" => "To Date",
            "type" => "date_picker",
        ),
        // array(
        //     "id" => "operator_id",
        //     "name" => "operator_id",
        //     "label" => "Select Operator",
        //     "type" => "select",
        // ),
        array(
            "id" => "order_status",
            "name" => "order_status",
            "label" => "Select Status",
            "type" => "select",
        ),
    ],
    
    
    //Ministatement filter starts
     "Mini_Statement_RT_FILTER" => [
        array(
            "id" => "from_date",
            "name" => "from_date",
            "label" => "From Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "to_date",
            "name" => "to_date",
            "label" => "To Date",
            "type" => "date_picker",
        ),
        // array(
        //     "id" => "operator_id",
        //     "name" => "operator_id",
        //     "label" => "Select Operator",
        //     "type" => "select",
        // ),
        array(
            "id" => "order_status",
            "name" => "order_status",
            "label" => "Select Status",
            "type" => "select",
        ),
    ],
    

    "Mini_Statement_DIS_FILTER" => [
        array(
            "id" => "from_date",
            "name" => "from_date",
            "label" => "From Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "to_date",
            "name" => "to_date",
            "label" => "To Date",
            "type" => "date_picker",
        ),
        // array(
        //     "id" => "operator_id",
        //     "name" => "operator_id",
        //     "label" => "Select Operator",
        //     "type" => "select",
        // ),
        array(
            "id" => "order_status",
            "name" => "order_status",
            "label" => "Select Status",
            "type" => "select",
        ),
    ],
     "Mini_Statement_ADMIN_FILTER" => [
        array(
            "id" => "from_date",
            "name" => "from_date",
            "label" => "From Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "to_date",
            "name" => "to_date",
            "label" => "To Date",
            "type" => "date_picker",
        ),
        // array(
        //     "id" => "operator_id",
        //     "name" => "operator_id",
        //     "label" => "Select Operator",
        //     "type" => "select",
        // ),
        array(
            "id" => "order_status",
            "name" => "order_status",
            "label" => "Select Status",
            "type" => "select",
        ),
    ],
    
    //Ministatemennt gfilter ends
    
    "ICICI_CASH_DEPOSIT_DIS_FILTER" => [
        array(
            "id" => "from_date",
            "name" => "from_date",
            "label" => "From Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "to_date",
            "name" => "to_date",
            "label" => "To Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "operator_id",
            "name" => "operator_id",
            "label" => "Select Operator",
            "type" => "select",
        ),
        array(
            "id" => "order_status",
            "name" => "order_status",
            "label" => "Select Status",
            "type" => "select",
        ),
    ],
    "AADHAR_PAY_DIS_FILTER" => [
        array(
            "id" => "from_date",
            "name" => "from_date",
            "label" => "From Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "to_date",
            "name" => "to_date",
            "label" => "To Date",
            "type" => "date_picker",
        ),
        // array(
        //     "id" => "operator_id",
        //     "name" => "operator_id",
        //     "label" => "Select Operator",
        //     "type" => "select",
        // ),
        array(
            "id" => "order_status",
            "name" => "order_status",
            "label" => "Select Status",
            "type" => "select",
        ),
    ],
    // Transaction Reports Filters ends

    // Commission Reports Filters starts
    "RECHARGE_COM_ADMIN_FILTER" => [
        array(
            "id" => "from_date",
            "name" => "from_date",
            "label" => "From Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "to_date",
            "name" => "to_date",
            "label" => "To Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "api_id",
            "name" => "api_id",
            "label" => "Select API",
            "type" => "select",
        ),
        array(
            "id" => "service_id",
            "name" => "service_id",
            "label" => "Select Service Type",
            "type" => "select",
        ),
        array(
            "id" => "operator_id",
            "name" => "operator_id",
            "label" => "Select Operator",
            "type" => "select",
        ),
    ],

    "RECHARGE_COM_RT_FILTER" => [
        array(
            "id" => "from_date",
            "name" => "from_date",
            "label" => "From Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "to_date",
            "name" => "to_date",
            "label" => "To Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "operator_id",
            "name" => "operator_id",
            "label" => "Select Operator",
            "type" => "select",
        ),
    ],

    "RECHARGE_COM_DIS_FILTER" => [
        array(
            "id" => "from_date",
            "name" => "from_date",
            "label" => "From Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "to_date",
            "name" => "to_date",
            "label" => "To Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "operator_id",
            "name" => "operator_id",
            "label" => "Select Operator",
            "type" => "select",
        ),
    ],

    "MONEY_TRANSFER_COM_ADMIN_FILTER" => [
        array(
            "id" => "from_date",
            "name" => "from_date",
            "label" => "From Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "to_date",
            "name" => "to_date",
            "label" => "To Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "api_id",
            "name" => "api_id",
            "label" => "Select API",
            "type" => "select",
        ),
        array(
            "id" => "operator_id",
            "name" => "operator_id",
            "label" => "Select Operator",
            "type" => "select",
        ),
    ],

    "MONEY_TRANSFER_COM_RT_FILTER" => [
    ],

    "MONEY_TRANSFER_COM_DIS_FILTER" => [
        array(
            "id" => "from_date",
            "name" => "from_date",
            "label" => "From Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "to_date",
            "name" => "to_date",
            "label" => "To Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "operator_id",
            "name" => "operator_id",
            "label" => "Select Operator",
            "type" => "select",
        ),
    ],

    "UPI_TRANSFER_COM_ADMIN_FILTER" => [
        array(
            "id" => "from_date",
            "name" => "from_date",
            "label" => "From Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "to_date",
            "name" => "to_date",
            "label" => "To Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "api_id",
            "name" => "api_id",
            "label" => "Select API",
            "type" => "select",
        ),
        array(
            "id" => "operator_id",
            "name" => "operator_id",
            "label" => "Select Operator",
            "type" => "select",
        ),
    ],

    "UPI_TRANSFER_COM_RT_FILTER" => [
    ],

    "UPI_TRANSFER_COM_DIS_FILTER" => [
        array(
            "id" => "from_date",
            "name" => "from_date",
            "label" => "From Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "to_date",
            "name" => "to_date",
            "label" => "To Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "operator_id",
            "name" => "operator_id",
            "label" => "Select Operator",
            "type" => "select",
        ),
    ],

    "BILL_PAYMENTS_COM_ADMIN_FILTER" => [
        array(
            "id" => "from_date",
            "name" => "from_date",
            "label" => "From Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "to_date",
            "name" => "to_date",
            "label" => "To Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "api_id",
            "name" => "api_id",
            "label" => "Select API",
            "type" => "select",
        ),
        array(
            "id" => "operator_id",
            "name" => "operator_id",
            "label" => "Select Operator",
            "type" => "select",
        ),
    ],

    "BILL_PAYMENTS_COM_RT_FILTER" => [
        array(
            "id" => "from_date",
            "name" => "from_date",
            "label" => "From Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "to_date",
            "name" => "to_date",
            "label" => "To Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "operator_id",
            "name" => "operator_id",
            "label" => "Select Operator",
            "type" => "select",
        ),
    ],

    "BILL_PAYMENTS_COM_DIS_FILTER" => [
        array(
            "id" => "from_date",
            "name" => "from_date",
            "label" => "From Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "to_date",
            "name" => "to_date",
            "label" => "To Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "service_id",
            "name" => "service_id",
            "label" => "Select Service",
            "type" => "select",
        ),
    ],

    "AEPS_COM_ADMIN_FILTER" => [
        array(
            "id" => "from_date",
            "name" => "from_date",
            "label" => "From Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "to_date",
            "name" => "to_date",
            "label" => "To Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "api_id",
            "name" => "api_id",
            "label" => "Select API",
            "type" => "select",
        ),
    ],

    "AEPS_COM_RT_FILTER" => [
        array(
            "id" => "from_date",
            "name" => "from_date",
            "label" => "From Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "to_date",
            "name" => "to_date",
            "label" => "To Date",
            "type" => "date_picker",
        ),
    ],

    "AEPS_COM_DIS_FILTER" => [
        array(
            "id" => "from_date",
            "name" => "from_date",
            "label" => "From Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "to_date",
            "name" => "to_date",
            "label" => "To Date",
            "type" => "date_picker",
        ),
    ],
    // Commission Reports Filters ends

    // Member Passbook Filter List
    "MEMBER_PASSBOOK_ADMIN_FILTER" => [
        array(
            "id" => "username_mobile",
            "name" => "username_mobile",
            "label" => "Username/Mobile",
            "type" => "text",
        ),
        array(
            "id" => "from_date",
            "name" => "from_date",
            "label" => "From Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "to_date",
            "name" => "to_date",
            "label" => "To Date",
            "type" => "date_picker",
        )
    ],

    // Passbook filters starts
    "PASSBOOK_ADMIN_FILTER" => [
        array(
            "id" => "from_date",
            "name" => "from_date",
            "label" => "From Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "to_date",
            "name" => "to_date",
            "label" => "To Date",
            "type" => "date_picker",
        ),
    ],

    "PASSBOOK_RT_FILTER" => [
        array(
            "id" => "from_date",
            "name" => "from_date",
            "label" => "From Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "to_date",
            "name" => "to_date",
            "label" => "To Date",
            "type" => "date_picker",
        ),
    ],

    "PASSBOOK_DIS_FILTER" => [
        array(
            "id" => "from_date",
            "name" => "from_date",
            "label" => "From Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "to_date",
            "name" => "to_date",
            "label" => "To Date",
            "type" => "date_picker",
        ),
    ],

    "BALANCE_REQUEST_FILTER" => [
        array(
            "id" => "from_date",
            "name" => "from_date",
            "label" => "From Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "to_date",
            "name" => "to_date",
            "label" => "To Date",
            "type" => "date_picker",
        ),
    ],
    // Passbook filters ends

    // Recharge and Bill payment list starts
    "RECHARGE_BILL_PAYENTS" => [
        array(
            "name" => "Mobile",
            "icon" => "mdi mdi-cellphone",
            "route" => "recharges",
            "key" => "mobile",
            "logo"=>"1_mobile_ic.png"
        ),
        array(
            "name" => "DTH",
            "icon" => "mdi mdi-television-guide",
            "route" => "recharges",
            "key" => "dth",
            "logo"=>"DTH.png"
        ),
        array(
            "name" => "Postpaid",
            "icon" => "mdi mdi-cellphone",
            "route" => "recharges",
            "key" => "postpaid",
            "logo"=>"1_mobile_ic.png"
        ),
        array(
            "name" => "Electricity",
            "icon" => "mdi mdi-lightbulb-outline",
            "route" => "recharges",
            "key" => "electricity",
            "logo"=>"electricitybill.png"
        ),
        array(
            "name" => "Electricity New",
            "icon" => "mdi mdi-lightbulb-outline",
            "route" => "recharges",
            "key" => "electricity_new",
            "logo"=>"electricitybill.png"
        ),
        array(
            "name" => "Water",
            "icon" => "mdi mdi-water",
            "route" => "recharges",
            "key" => "water",
            "logo"=>"waterbill.png"
        ), 
        array(
            "name" => "Loan Payment",
            "icon" => "mdi mdi-bank",
            "route" => "recharges",
            "key" => "loan_payment",
            "logo"=>"loanpayment.png"
        ), 
        array(
            "name" => "FASTag",
            "icon" => "mdi mdi-barcode",
            "route" => "recharges",
            "key" => "fast_tag",
            "logo"=>"fasttag.png"
        ),
        
        array(
            "name" => "LPG",
            "icon" => "mdi mdi-gas-cylinder",
            "route" => "recharges",
            "key" => "lpg",
            "logo"=>"lpg.png"
        ),
        array(
            "name" => "Credit Card ",
            "icon" => "mdi mdi-credit-card",
            "route" => "recharges",
            "key" => "credit_card_bill",
            "logo"=>"creditcardbill.png"
        ),
        array(
            "name" => "Broadband",
            "icon" => "mdi mdi-router-wireless",
            "route" => "recharges",
            "key" => "broadband",
            "logo"=>"broadband.png"
        ), 
        
        array(
            "name" => "Education",
            "icon" => "mdi mdi-book-open-page-variant",
            "route" => "recharges",
            "key" => "education",
            "logo"=>"education.png"
        ),
        array(
            "name" => "Life Insurance",
            "icon" => "mdi mdi-heart-pulse",
            "route" => "recharges",
            "key" => "life_insurance",
            "logo"=>"life-insurrance.png"
        ),
        array(
            "name" => "Insurance",
            "icon" => "mdi mdi-shield",
            "route" => "recharges",
            "key" => "insurance",
            "logo"=>"insurance.png"
        ), 
         
        
        
        
        
    ],
    // Recharge and bill payment ends

    // Operator Details starts
    "OPERATOR" => [
        "GET_LIST_URL" => $websiteBaseUrl ."admin/index.php/OperatorApi",
    ],
    // Operator Details ends

    "RECHARGE_API" => $websiteBaseUrl ."admin/index.php/RechargeApi",

    "RECH_MOB_DTH_RQ_KEY" => [
        "OPERATOR_ID" => "operatorID",
        "MOBILE_NO" => "mobileNumber",
        "AMOUNT" => "amount",
        "MPIN" => "mpin",
        "API_KEY" => "token",
        "USER_ID" => "user_id",
        "ROLE_ID" => "role_id",
    ],

    // Money transfer constants starts
    "MONEY_TRANSFER" => [
        "TYPE" => [
            array(
                "id" => "smart",
                "name" => "Smart",
                "icon" => "fab fa-cc-visa",
                "minimum_amount" => "20,000",
            ),
            array(
                "id" => "crazy",
                "name" => "Crazy",
                "icon" => "fab fa-cc-mastercard",
                "minimum_amount" => "20,000",
            ),
        ],
        "GET_SENDER_DTLS_API" => $websiteBaseUrl ."admin/index.php/MoneyTransferApi/getSenderDetails",
        "CREATE_SENDER_API" => $websiteBaseUrl ."admin/index.php/MoneyTransferApi/createSender",
        "VERIFY_SENDER_REG_API" => $websiteBaseUrl ."admin/index.php/MoneyTransferApi/verifySenderRegistration",
        "RESEND_OTP_API" => $websiteBaseUrl ."admin/index.php/MoneyTransferApi/resendOTP",
        "VERIFY_BNK_AC" => $websiteBaseUrl ."admin/index.php/MoneyTransferApi/verifyBankAccount",
        "CREATE_RECEPIENT_API" => $websiteBaseUrl ."admin/index.php/MoneyTransferApi/addRecipient",
        "GET_BANK_LIST" => $websiteBaseUrl ."admin/index.php/MoneyTransferApi/getBankList",
        "GET_RECEIPIENT_LIST" => $websiteBaseUrl ."admin/index.php/MoneyTransferApi/getRecipientList",
        "DELETE_RECEP_API" => $websiteBaseUrl ."admin/index.php/MoneyTransferApi/deleteRecipient",
        "FUND_TRN_API" => $websiteBaseUrl ."admin/index.php/MoneyTransferApi/doFundTransfer",
        "MULTIPLE_FUND_TRN_API" => $websiteBaseUrl ."admin/index.php/MoneyTransferApi/doMultipleFundTransfer",

        "GET_RECEIPIENT_LIST_UPI" => $websiteBaseUrl ."admin/index.php/Upi_Transfer/getRecipientList",
        "VERIFY_UPI_AC" => $websiteBaseUrl ."admin/index.php/Upi_Transfer/verifyBankAccount",
        "CREATE_RECEPIENT_API_UPI" => $websiteBaseUrl ."admin/index.php/Upi_Transfer/addRecipient",
        "DELETE_RECEP_API_UPI" => $websiteBaseUrl ."admin/index.php/Upi_Transfer/deleteRecipient",
        "FUND_TRN_API_UPI" => $websiteBaseUrl ."admin/index.php/Upi_Transfer/doFundTransfer",

        "FUND_TRN_API_CRAZY" => $websiteBaseUrl ."admin/index.php/Upi_Transfer/doFundTransfer",
        "CREATE_RAZOR_FUND_ACCOUNT" => $websiteBaseUrl ."admin/index.php/MoneyTransferApi/createFundAccount",

        

    ],
    // Money transfer constants ends

    "BILL_PAYMENTS_API"=>[
        "ELECTRICITY"=>[
            "GET_BILLER_LIST" => $websiteBaseUrl ."admin/index.php/RechargeApi/getbillerlist_bycategory",
            "GET_BILLER_DETAILS" => $websiteBaseUrl ."admin/index.php/RechargeApi/fetchBillDetails",
            "GET_BILLER_DETAILS_NEW" => $websiteBaseUrl ."admin/index.php/RechargeApi/fetchBillDetailsNew",
            "PAY_BILL" => $websiteBaseUrl ."admin/index.php/RechargeApi/billPay",
            "PAY_BILL_NEW" => $websiteBaseUrl ."admin/index.php/RechargeApi/billPayNew",
            "GET_BILLER_BY_STATE" => $websiteBaseUrl ."api/get_biller_by_StateCode",
            "GET_BILLER_BY_BILLER_ID" => $websiteBaseUrl ."api/get_biller_by_biller_id",
            "GET_CITY_BY_STATE_CODE" => $websiteBaseUrl ."api/get_city_by_state_code",
        ]
    ],


    // Payment Type Constant starts
    "PAYMENT_TYPE" => [
        "PAYMT_WALLET" => "LOAD_WALLET",
        "PAYMT_SERVICE" => "SERVICE",
        "PAYMT_REFUND" => "REFUND",
        "PAYMT_COMMISSION" => "COMMISSION",
        "OFFICE_EXPENSES" => "OFFICE_EXPENSES"
    ],
    "PAYMENT_GTWAY_TYPE" => [
        "PAYMT_GATEWAY" => "PAYMENT GATEWAY",
        "DIRECT_TRANSFER" => "DIRECT TRANSFER",
        "REVERT" => "REVERT",
        "PYMT_GTWY_CHRG" => "PAYMENT_GATEWAY_CHARGE",
        "TRN_FAILURE" => "TRANSACTION_FAILURE",
        "RETURN" => "RETURN",
    ],
    // Payment Type Constant ends

    // Bank Accounts  Transfer Mode Type starts
    "BANK_TRANS_MODE" => [
        "CASH",
        "NEFT/RTGS",
        "IMPS",
        "TO SELF",
    ],
    // Bank Accounts  Transfer Mode Type ends

    // User Code starts
    "USER_CODES" => [
        "ADMIN" => "AD",
        "RETAILER" => "RT",
        "DISTRIBUTOR" => "DT",
        "FOS" => "FOS",
        "MASTER_DISTRIBUTOR" => "MD",
        "SUB_ADMIN" => "SA",
    ],
    // User Code Ends

    // User Store starts
    "STORE_LIST" => [
        "MOBILE_SHOP" => "Mobile Shop",
        "GROCERY_SHOP" => "Grocery Shop",
        "MEDICAL_SHOP" => "Medical Shop",
    ],
    // User Store Ends


    //Coomplaint Admin
    "COMPLAINT_ADMIN_TD" => [
        array(
            "name" => "Date",
            "label" => "complaint_date",
        ),
        array(
            "name" => "Complaint ID",
            "label" => "complaint_id",
        ),
        array(
            "name" => "Store Name",
            "label" => "store_name",
        ),
        array(
            "name" => "Role",
            "label" => "role",
        ),
        array(
            "name" => "Mobile",
            "label" => "mobile",
        ),
        array(
            "name" => "Smart ID",
            "label" => "order_id",
        ),
        array(
            "name" => "API",
            "label" => "api_id",
        ),
        array(
            "name" => "Response",
            "label" => "transaction_msg",
        ),
        array(
            "name" => "Operator",
            "label" => "operator_id",
        ),
        array(
            "name" => "Transaction ID",
            "label" => "transaction_id",
        ),
        array(
            "name" => "Transaction Date",
            "label" => "trans_date",
        ),
        array(
            "name" => "Customer Mobile",
            "label" => "mobileno",
        ),
        array(
            "name" => "Amount",
            "label" => "total_amount",
        ),
        array(
            "name" => "Order Status",
            "label" => "order_status",
        ),
        array(
            "name" => "Message",
            "label" => "template",
        ),

        array(
            "name" => "Admin's Reply",
            "label" => "admin_reply",
        ),
        
        array(
            "name" => "Updated",
            "label" => "admin_reply_date",
        ),
        array(
            "name" => "Default Time",
            "label" => "comp_default_time",
        ),
        array(
            "name" => "Status",
            "label" => "complaint_status",
        ),
        array(
            "name" => "Action",
            "label" => "action",
        ),
    ],

    "COMPLAINT_ADMIN_FILTER" => [
        array(
            "id" => "from_date",
            "name" => "from_date",
            "label" => "From Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "to_date",
            "name" => "to_date",
            "label" => "To Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "username_mobile",
            "name" => "username_mobile",
            "label" => "Username/Mobile",
            "type" => "text",
        ),
        array(
            "id" => "api_id",
            "name" => "api_id",
            "label" => "Select API",
            "type" => "select",
        ),
        array(
            "id" => "service_id",
            "name" => "service_id",
            "label" => "Select Service Type",
            "type" => "select",
        ),
        array(
            "id" => "operator_id",
            "name" => "operator_id",
            "label" => "Select Operator",
            "type" => "select",
        ),
        array(
            "id" => "order_status",
            "name" => "order_status",
            "label" => "Select Status",
            "type" => "select",
        ),

    ],

    //Coomplaint Retailer
    "COMPLAINT_RT_TD" => [
        array(
            "name" => "Date",
            "label" => "complaint_date",
        ),
        array(
            "name" => "Complaint ID",
            "label" => "complaint_id",
        ),
        array(
            "name" => "Smart ID",
            "label" => "order_id",
        ),
        array(
            "name" => "Operator",
            "label" => "operator_id",
        ),
        array(
            "name" => "Transaction ID",
            "label" => "transaction_id",
        ),
        array(
            "name" => "Transaction Date",
            "label" => "trans_date",
        ),
        array(
            "name" => "Customer Mobile",
            "label" => "mobileno",
        ),
        array(
            "name" => "Amount",
            "label" => "total_amount",
        ),
        array(
            "name" => "Order Status",
            "label" => "order_status",
        ),

        array(
            "name" => "Message",
            "label" => "template",
        ),
       
        array(
            "name" => "Admin's Reply",
            "label" => "admin_reply",
        ),
        
        array(
            "name" => "Updated",
            "label" => "admin_reply_date",
        ),
        array(
            "name" => "Default time",
            "label" => "comp_default_time",
        ),
        array(
            "name" => "Status",
            "label" => "complaint_status",
        ),
        
    ],

    // "COMPLAINT_RT_FILTER" => [
    //     array(
    //         "id" => "from_date",
    //         "name" => "from_date",
    //         "label" => "From Date",
    //         "type" => "date_picker",
    //     ),
    //     array(
    //         "id" => "to_date",
    //         "name" => "to_date",
    //         "label" => "To Date",
    //         "type" => "date_picker",
    //     ),
    //     array(
    //         "id" => "username_mobile",
    //         "name" => "username_mobile",
    //         "label" => "Username/Mobile",
    //         "type" => "text",
    //     ),
    //     array(
    //         "id" => "api_id",
    //         "name" => "api_id",
    //         "label" => "Select API",
    //         "type" => "select",
    //     ),
    //     array(
    //         "id" => "service_id",
    //         "name" => "service_id",
    //         "label" => "Select Service Type",
    //         "type" => "select",
    //     ),
    //     array(
    //         "id" => "operator_id",
    //         "name" => "operator_id",
    //         "label" => "Select Operator",
    //         "type" => "select",
    //     ),
    //     array(
    //         "id" => "order_status",
    //         "name" => "order_status",
    //         "label" => "Select Status",
    //         "type" => "select",
    //     ),

    // ],


    // Coomplaint Dis
    "COMPLAINT_DIS_TD" => [
        array(
            "name" => "Date",
            "label" => "complaint_date",
        ),
        array(
            "name" => "Complaint ID",
            "label" => "complaint_id",
        ),
        // array(
        //     "name" => "Service ID",
        //     "label" => "service_id",
        // ),
        array(
            "name" => "Store Name",
            "label" => "store_name",
        ),

        array(
            "name" => "Mobile No",
            "label" => "mobile",
        ),
        array(
            "name" => "Smart ID",
            "label" => "order_id",
        ),
        array(
            "name" => "Operator",
            "label" => "operator_name",
        ),
        array(
            "name" => "Transaction ID",
            "label" => "transaction_id",
        ),
        array(
            "name" => "Transaction Date",
            "label" => "trans_date",
        ),
        array(
            "name" => "Customer Mobile",
            "label" => "mobileno",
        ),
        array(
            "name" => "Amount",
            "label" => "total_amount",
        ),
        array(
            "name" => "Order Status",
            "label" => "order_status",
        ),  
        array(
            "name" => "Message",
            "label" => "template",
        ),
        array(
            "name" => "Admin's Reply",
            "label" => "admin_reply",
        ),
        array(
            "name" => "Complaint",
            "label" => "complaint_message",
        ),
        array(
            "name" => "Updated Date",
            "label" => "admin_reply_date",
        ),
        array(
            "name" => "Default time",
            "label" => "comp_default_time",
        ),
        
        array(
            "name" => "Complaint Status",
            "label" => "complaint_status",
        ),
       
    ],

     // Offers notice for Admin
     "OFFERS_NOTICE_ADMIN_TD" => [
        array(
            "name" => "ID",
            "label" => "notice_id",
        ),
        array(
            "name" => "Title",
            "label" => "notice_title",
        ),
        array(
            "name" => "Description",
            "label" => "notice_description",
        ),
        array(
            "name" => "Type",
            "label" => "notice_type",
        ),

        array(
            "name" => "Visible To",
            "label" => "notice_visible",
        ),
        array(
            "name" => "Date",
            "label" => "created_on",
        ),
       
       
    ],

    "OFFERS_NOTICE_RT_DT_TD" => [
        array(
            "name" => "Notice ID",
            "label" => "notice_id",
        ),
        array(
            "name" => "Title",
            "label" => "notice_title",
        ),
        array(
            "name" => "Description",
            "label" => "notice_description",
        ),
        array(
            "name" => "Type",
            "label" => "notice_type",
        ),
        array(
            "name" => "Image",
            "label" => "file_path",
        ),
        array(
            "name" => "Date",
            "label" => "created_on",
        ),
       
       
    ],

    
    // Notice Type
    "OFFERS_NOTICE_TYPE" => [
        "OFFER" => 1,
        "NOTICE" => 2,
        "ALERT" => 3
    ],
    
    "OFFICE_EXPENSES_ADMIN_TD" => [
        array(
            "name" => "Date",
            "label" => "date",
        ),
        array(
            "name" => "Category",
            // "label" => "category",
            "label" => "category_bank",
        ),
        array(
            "name" => "Account",
            "label" => "account_name",
        ),
        array(
            "name" => "Description",
            // "label" => "payment_mode",
            "label" => "description",
        ),
       
        array(
            "name" => "Cr/Dr",
            "label" => "cr_dr",
        ),
        array(
            "name" => "Amount",
            "label" => "amount",
        ),
        array(
            "name" => "Balance",
            "label" => "balance",
        ),
       
       
       
    ],

    "OFFICE_EXPENSES_ADMIN_FILTER" => [
        array(
            "id" => "from_date",
            "name" => "from_date",
            "label" => "From Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "to_date",
            "name" => "to_date",
            "label" => "To Date",
            "type" => "date_picker",
        ),
       
        array(
            "id" => "category_id",
            "name" => "filter_category_id",
            "label" => "Select Category",
            "type" => "select",
        ),
        array(
            "id" => "bank_name",
            "name" => "filter_bank_name",
            "label" => "Select Bank",
            "type" => "select",
        ),
    ],

    "TDS_USER" =>[
        "DISTRIBUTOR",
        "RETAILER" 
    ],

    "CREDIT_REPORT_RT_TD"=>[
        array(
            "name" => "Name",
            "label" => "first_name",
        ),
        array(
            "name" => "Date",
            "label" => "trans_date",
        ),
        array(
            "name" => "Mobile",
            "label" => "mobile_transfer_by",
        ),
        array(
            "name" => "Transfer Type",
            "label" => "transfer_type",
        ),
        array(
            "name" => "CR/DR",
            "label" => "transaction_type",
        ),
        array(
            "name" => "Amount",
            "label" => "amount",
        ),
        array(
            "name" => "Balance",
            "label" => "balance",
        ),
    ],
  
    "CREDIT_REPORT_FOS_TD"=>[
        array(
            "name" => "Name",
            "label" => "first_name",
        ),
        array(
            "name" => "Date",
            "label" => "trans_date",
        ),
        array(
            "name" => "Mobile",
            "label" => "mobile_transfer_by",
        ),
        array(
            "name" => "Transfer Type",
            "label" => "transfer_type",
        ),
        array(
            "name" => "CR/DR",
            "label" => "transaction_type",
        ),
        array(
            "name" => "Amount",
            "label" => "amount",
        ),
        array(
            "name" => "Balance",
            "label" => "balance",
        ),
    ],

    "CREDIT_REPORT_FILTER" => [
        array(
            "id" => "from_date",
            "name" => "from_date",
            "label" => "From Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "to_date",
            "name" => "to_date",
            "label" => "To Date",
            "type" => "date_picker",
        ),
       
    ],

    "TDS_REPORT" => [
        array(
            "name" => "Username",
            "label" => "username",
        ),
        array(
            "name" => "Store Name",
            "label" => "store_name",
        ),
        array(
            "name" => "Name",
            "label" => "name",
        ),
        array(
            "name" => "Pan No",
            "label" => "pan_no",
        ),
        array(
            "name" => "Reg Date",
            "label" => "createdDtm",
        ),
        array(
            "name" => " TDS Amount",
            "label" => "tds_amount",
        ),
        array(
            "name" => "Action",
            "label" => "action",
        ),
    ],

    
    "TDS_REPORT_FILTER" => [
        array(
            "id" => "from_date",
            "name" => "from_date",
            "label" => "From Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "to_date",
            "name" => "to_date",
            "label" => "To Date",
            "type" => "date_picker",
        ),
       
    ],

    "TDS_HISTORY" => [
        array(
            "name" => "Trans Date",
            "label" => "trans_date",
        ),
        array(
            "name" => "Order ID ",
            "label" => "order_id",
        ),
        array(
            "name" => "Amount",
            "label" => "total_amount",
        ),
        array(
            "name" => "Cashback",
            "label" => "Cashback",
        ),
        array(
            "name" => "TDS",
            "label" => "TDSamount",
        )
        
    ],

    "PAYMENT_GATEWAY_REPORT" => [
        array(
            "name" => "Date",
            "label" => "trans_date",
        ),
        array(
            "name" => "Transaction ID",
            "label" => "transaction_id",
        ),
        array(
            "name" => "Order ID",
            "label" => "order_id",
        ),
        array(
            "name" => "Store Name",
            "label" => "store_name",
        ),
        array(
            "name" => "Payment Mode",
            "label" => "gateway_mode",
        ),
        array(
            "name" => "Amount",
            "label" => "total_amount",
        ),
        array(
            "name" => "Status",
            "label" => "transaction_status",
        )
      
        
    ],

    "PAYMENT_GATEWAY_FILTER" => [
        array(
            "id" => "from_date",
            "name" => "from_date",
            "label" => "From Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "to_date",
            "name" => "to_date",
            "label" => "To Date",
            "type" => "date_picker",
        ),
       
    ],

    
    "USER_PAYMENT_GATEWAY_REPORT" => [
        array(
            "name" => "DATE",
            "label" => "trans_date",
        ),
        array(
            "name" => "ORDER ID",
            "label" => "order_id",
        ),
        array(
            "name" => "STORE NAME",
            "label" => "store_name",
        ),
        array(
            "name" => "USERNAME",
            "label" => "username",
        ),
        array(
            "name" => "PAYMENT MODE",
            "label" => "payment_mode",
        ),
        array(
            "name" => "PAYMENT METHOD",
            "label" => "payment_method",
        ),
        array(
            "name" => "BANK REFERENCE ID",
            "label" => "bank_trans_id",
        ),
        array(
            "name" => "AMOUNT",
            "label" => "total_amount",
        ),
        // array(
        //     "name" => "CHARGE",
        //     "label" => "charges",
        // ),
        array(
            "name" => "STATUS",
            "label" => "transaction_status",
        ),
        // array(
        //     "name" => "Transaction ID",
        //     "label" => "transaction_id",
        // ),
        // array(
        //     "name" => "Bank ID",
        //     "label" => "bank_trans_id",
        // ),
      
       
       
        // array(
        //     "name" => "Response",
        //     "label" => "response_msg",
        // ),
      
       
       
      
        
    ],

    "USER_PAYMENT_GATEWAY_FILTER" => [
        array(
            "id" => "from_date",
            "name" => "from_date",
            "label" => "From Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "to_date",
            "name" => "to_date",
            "label" => "To Date",
            "type" => "date_picker",
        ),

        array(
            "id" => "filter_payment_mode",
            "name" => "filter_payment_mode",
            "label" => "Select Payment",
            "type" => "select",
        ),
       
    ],
    
    "USER_VIRTUAL_ACCOUNT_REPORT" => [
        array(
            "name" => "DATE",
            "label" => "trans_date",
        ),
        array(
            "name" => "ORDER ID",
            "label" => "order_id",
        ),
        array(
            "name" => "TRANSACTION ID",
            "label" => "transaction_id",
        ),
        array(
            "name" => "USERNAME",
            "label" => "username",
        ),
        array(
            "name" => "STORE NAME",
            "label" => "store_name",
        ),
        // array(
        //     "name" => "MOBILE NUMBER",
        //     "label" => "mobile",
        // ),
        array(
            "name" => "PAYMENT MODE",
            "label" => "payment_mode",
        ),
        array(
            "name" => "AMOUNT",
            "label" => "total_amount",
        ),
        array(
            "name" => "RRN",
            "label" => "bank_trans_id",
        ),
        array(
            "name" => "STATUS",
            "label" => "transaction_status",
        ),
    ],

    "GRAPH_FILTER" => [
        array(
            "id" => "from_date",
            "name" => "from_date",
            "label" => "From Date",
            "type" => "date_picker",
        ),
        array(
            "id" => "to_date",
            "name" => "to_date",
            "label" => "To Date",
            "type" => "date_picker",
        ),
       
    ],

    "BBPS_FILTER" => [
        array(
            "id" => "operator_name",
            "name" => "filter_operator_name",
            "label" => "Select Operator",
            "type" => "select",
        ),
        // array(
        //     "id" => "to_date",
        //     "name" => "to_date",
        //     "label" => "To Date",
        //     "type" => "date_picker",
        // ),
       
    ],

    "MONEY_TRANSFER_OPERATOR"=>[
        "SMART_MONEY"=>"SMART MONEY",
        "CRAZY_MONEY"=>"CRAZY MONEY",
        "BHIM_UPI"=>"BHIM UPI",
    ]
  
]   
?>
