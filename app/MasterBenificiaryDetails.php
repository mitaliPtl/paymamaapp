<?php

namespace App;

use Config;
use Illuminate\Database\Eloquent\Model;

class MasterBenificiaryDetails extends Model
{
    protected $table = "tbl_master_benificiary_dtls";
    protected $primaryKey = "id";
    public $timestamps = false;

    protected $fillable = [
        'id',
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
        'is_deleted',
        'razorpay_fund_acc_id',
        'cfree_fund_acc_id'
    ];

    
}
