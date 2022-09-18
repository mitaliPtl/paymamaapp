<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BalanceRequest extends Model
{
    protected $table = "tbl_balance_requests";

    protected $fillable = [
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
