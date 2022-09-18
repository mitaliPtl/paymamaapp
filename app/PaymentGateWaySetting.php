<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentGateWaySetting extends Model
{
    protected $table = "tbl_payment_gateway_settings";
    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'payment_gateway_name',
        'working_key',
        'username',
        'password',
        'charges',
        'activated_status',
        'is_deleted',
    ];
}
