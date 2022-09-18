<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PackageCommissionDetail extends Model
{
    protected $table = "tbl_pkg_commission_dtls";
    protected $primaryKey = "pkg_commission_id";

    public $timestamps = false;

    protected $fillable = [
        'commission_type',

        'ccf_commission',
        'api_charge_commission',

        'admin_commission',
        'api_commission',
        'md_commission',
        'distributor_commission',
        'retailer_commission',

        'from_range',
        'to_range',

        'ccf_commission_type',
        'api_charge_commission_type',
        'admin_commission_type',
        'md_commission_type',
        'distributor_commission_type',
        'retailer_commission_type',

        'service_id',
        'pkg_id',
        'operator_id',

        'activated_status',
        'is_deleted',
    ];
}
