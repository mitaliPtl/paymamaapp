<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransferCreditReport extends Model
{
    protected $table = "tbl_transfer_credit_report";
    protected $primaryKey = "id";
    public $timestamps = false;

    protected $fillable = [
        "order_id",
        "reference_id",
        // "payment_type",
        "transfer_by_id",
        "transfer_by_role",
        "transfer_to_id",
        "transfer_to_role",
        "mobile_transfer_by",
        "amount",
        "trans_date",
        "transaction_type",
        "balance",
        "transfer_type",
        "created",
        "updated"
    ];
}
