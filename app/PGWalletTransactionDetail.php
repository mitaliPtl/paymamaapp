<?php

namespace App;

use Config;
use Illuminate\Database\Eloquent\Model;

class PGWalletTransactionDetail extends Model
{
    protected $table = "tbl_pg_wallet_trans_dtls";
    protected $primaryKey = "id";
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'user_id',
        'transaction_status',
        'response_msg',
        'bank_trans_id',
        'transaction_type',
        'transaction_id',
        'trans_date',
        'payment_type',
        'payment_mode',
        'gateway_mode',
        'expense_category',
        'total_amount',
        'balance',
        'id_deleted',

        // newly added
        'service_id',
        'operator_id',
        'api_id',
        'charge_amount',
    ];

    public function services()
    {
        return $this->belongsTo('App\ServicesType', 'service_id');
    }

    /**
     * Get Commission Amount by providing userId and  Transaction Detail
     */
    public static function getComAmtByTranDtl($tranDtl)
    {
        $response = "";
        if ((isset($tranDtl->user_id) && $tranDtl->user_id) && isset($tranDtl->order_id) && isset($tranDtl->order_id)) {
            $response = self::where('user_id', $tranDtl->user_id)
                ->where('order_id', $tranDtl->order_id)
                ->where('payment_type', Config::get('constants.PAYMENT_TYPE.PAYMT_COMMISSION'))
                ->pluck('total_amount')->first();
        }

        return $response;
    }
}
