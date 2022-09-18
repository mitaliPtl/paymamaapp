<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OperatorSetting extends Model
{
    protected $table = "tbl_operator_settings";
    protected $primaryKey = 'operator_id';

    public $timestamps = false;

    protected $fillable = [
        'operator_name',
        'operator_code',

        'operator_id',
        'service_id',
        'color_code',
        'helpline_no',
        'default_api_id',

        'activated_status',
        'is_deleted',
    ];

    public function servicesType()
    {
        return $this->belongsTo('App\ServicesType', 'service_id');
    }

    /**
     * Get Operator Name by providing User Id
     */
    public static function getOperatorNameById($id)
    {
        $response = "";
        if (isset($id) && $id) {
            $response = self::where('operator_id', $id)->pluck('operator_name')->first();
        }

        return $response;
    }
}
