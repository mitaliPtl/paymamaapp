<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServicesType extends Model
{
    protected $table = "tbl_services_type";
    protected $primaryKey = 'service_id';

    public $timestamps = false;
    
    protected $fillable = [
        'service_name',
        'alias',
        'service_dtls'
    ];

    public function Operator()
    {
        return $this->hasMany('App\OperatorSetting', 'service_id');
    }

    public function Transaction()
    {
        return $this->hasMany('App\TransactionDetail', 'service_id');
    }

    public function WalletTransaction()
    {
        return $this->hasMany('App\WalletTransactionDetail', 'service_id');
    }
}
