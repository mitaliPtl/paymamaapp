<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExpensesReport extends Model
{
    protected $table="tbl_expenses_report";
    public $timestamps = false;
    protected $fillable =[
        "user_id",
        "bank_id",
        "category_id",
        "date",
        "category_bank",
        "account_name",
        "description",
        "cr_dr",
        "amount",
        "balance"
    ];
}
