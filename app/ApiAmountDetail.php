<?php

namespace App;
//comment
use Illuminate\Database\Eloquent\Model;

class ApiAmountDetail extends Model
{
    protected $table = "tbl_api_by_amount_dtls";
    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'api_id',
        'operator_id',
        'amount',
        'add_date',
        'balance',
        'activated_status',
        'is_deleted',
    ];
}
