<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BalanceRequest extends Model
{
    protected $table = "tbl_balance_requests";

    protected $fillable = [
        "deposit_date",
        "account_holder_name",
        "account_holder_bank_name",
        "account_holder_mode",
        "transaction_id",
        "bank",
        "mode",
        "reference_id",
        "user_id",
        "role",
        "mobile_no",
        "amount",
        "message",
        "receipt_file",
        "admin_reply",
        "trans_date",
        "status",
    ];
}
