<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    protected $table = "tbl_transaction_dtls";
    protected $primaryKey = "id";
    public $timestamps = false;
    
    protected $fillable = [
        "service_id",
        "order_id",
        "group_id",
        "user_id",
        "mobileno",
        "operator_id",
        "api_id",
        "recipient_id",
        "transaction_status",
        "transaction_id",
        "transaction_type",
        "trans_date",
        "imps_name",
        "bank_transaction_id",
        "total_amount",
        "charge_amount",
        "basic_amount",
        "debit_amount",
        "credit_amount",
        "charges_tax",
        "commission_tax",
        "commission_tds",
        "balance",
        "order_status",
        "id_deleted",
        "updated_on",
        "transaction_msg",
        "remarks",
        "CCFcharges",
        "Cashback",
        "TDSamount",
        "PayableCharge",
        "FinalAmount",
        "response_msg",
        "txnRespType",
        "inputParams",
        "CustConvFee",
        "RespAmount",
        "RespBillDate",
        "RespBillNumber",
        "RespBillPeriod",
        "RespCustomerName",
        "RespDueDate",
        "request_amount",
        "sync_time",
        "billerID",
        "billPayType",
        "bill_orderId",
        "access_type",
        "ip_address",
        "source",
        "bank_account_no",
        "account_holder_name",
        "transactionamount",
        "superMerchantId",
        "rrnno",
        "client_reference_id",
        "aadharnumber",
        "aeps_balance",
        "retailer_commision",
        "distributor_commision",
        "master_distributor_commission",
        "admin_commision",
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
