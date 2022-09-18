<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentGatewayReport extends Model
{
    protected $table = "tbl_payment_gateway_report";
    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'role_id',
        'order_id',
        'transaction_id',
        'transaction_status',
        'response_msg',
        'bank_trans_id',
        'total_amount',
        'trans_date',
        'payment_status',
        'payment_mode',
        'payment_method'
    ];
}
