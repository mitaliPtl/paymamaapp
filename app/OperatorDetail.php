<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OperatorDetail extends Model
{
    protected $table = "tbl_api_operator_dtls";
    protected $primaryKey = "api_operator_id";
    public $timestamps = false;

    protected $fillable = [
        'operator_code',
        'service_id',
        'api_id',
        'operator_id',
        'activated_status',
        'is_deleted',
    ];
}
