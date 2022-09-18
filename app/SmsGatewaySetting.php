<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmsGatewaySetting extends Model
{
    protected $table = "tbl_sms_gateway_settings";
    protected $primaryKey = 'id';
    
    public $timestamps = false;

    protected $fillable = [
        'api_name',
       
        'api_url',
        'username',
        'password',
        'activated_status',
        'is_deleted',
        'updated_on'
    ];
}
