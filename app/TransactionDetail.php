<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    protected $table = "tbl_transaction_dtls";
    protected $primaryKey = "id";
    public $timestamps = false;
    
    protected $fillable = [
        "operator_id",
        "transaction_status",
        "order_status",
        "api_id",
        "updated_on",
        "order_id",
        "service_id",
        "user_id",
        "mobileno",
        "trans_date",
        "total_amount",
        "charge_amount",
        "superMerchantId",
        "bank_account_no",
        "transactionamount",
        "response_msg",
        "rrnno",
        "bank_name",
        "account_holder_name",
        "aeps_bank_id",
        "fpTransactionId"
    ];

    protected $casts = [
        'total_amount' => 'float',
    ];

    public function services()
    {
        return $this->belongsTo('App\ServicesType', 'service_id');
    }

    //Total sales of this month
    public static function getSalesByUserId($user_id){
        $response = "";
        if (isset($user_id) && $user_id) {
            // $currentMonth = date('m');

            $first_day = date('Y-m-01'); // hard-coded '01' for first day
            $last_day  = date('Y-m-t');

            $response = self::where('user_id', $user_id)->where('order_status', 'SUCCESS')
                            ->whereDate('trans_date', '>=', $first_day)->whereDate('trans_date', '<=', $last_day)
                                ->pluck('total_amount')->sum();
        }

        return $response; 
    }
}
