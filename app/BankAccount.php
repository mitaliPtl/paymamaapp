<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $table="tbl_bank_accounts";

    protected $fillable =[
        "bank_name",
        "bank_icon",
        "account_no",
        "ifsc_code",
        "address",
        "balance",
    ];
}
