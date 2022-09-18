<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmsTemplate extends Model
{
    protected $table = "tbl_sms_templates";
    public $timestamps = false;

    protected $fillable = [
        'sms_name',
        'alias',
        'template',
        'updated_on'
    ];
}
