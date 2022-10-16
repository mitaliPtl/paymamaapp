<?php

namespace App;

use Config;
use Illuminate\Database\Eloquent\Model;

class PGBenificiaryDetails extends Model
{
    protected $table = "tbl_pg_benificiary_dtls";
    protected $primaryKey = "recipient_id";
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'recipient_name',
        'bank_name',
        'bank_code',
        'bank_account_number',
        'ifsc',
        'recipient_status',
        'is_verified',
        'verified_name',
        'recipient_mobile_number',
        'sender_mobile_number',
        'api_id',
        'is_deleted',
        'api_name',
        'razorpay_fund_acc_id',
        'cfree_fund_acc_id'
    ];

    
}
