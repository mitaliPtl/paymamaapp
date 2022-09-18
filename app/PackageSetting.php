<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PackageSetting extends Model
{
    protected $table = "tbl_package_settings";
    protected $primaryKey ="package_id";

    public $timestamps = false;

    protected $fillable = [
        'package_name',
        'package_descr',
        'retailer_cost',
        'distributor_cost',
        'activated_status',
        'is_deleted',
    ];
}
