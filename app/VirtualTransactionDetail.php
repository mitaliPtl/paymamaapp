<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VirtualTransactionDetail extends Model
{
    protected $table = "tbl_virtual_trans_dtls";
    protected $primaryKey = "id";
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'order_id',
        'transaction_id',
        'transaction_status',
        'bank_trans_id',
        'transaction_type',
        'trans_date',
        'payment_type',
        'payment_mode',
        'total_amount',
        'balance',
        'charge_amount',
    ];
}


