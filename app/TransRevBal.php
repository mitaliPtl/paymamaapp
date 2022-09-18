<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransRevBal extends Model
{
    protected $table = "tbl_transfer_revert_balances";

    protected $fillable = [
        "bank",
        "reference_id",
        "payment_type",
        "user_id",
        "transfered_by",
        "role",
        "mobile_no",
        "amount",
        "trans_date",
        "balance",
        "transfer_type",
    ];
}
