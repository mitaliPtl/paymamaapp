<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ekyc extends Model
{
    protected $table = "tbl_ekyc";
    protected $primaryKey = 'id';
    
    public $timestamps = false;
    
    protected $fillable = [
        'user_id',
        'aadhaar_kyc',
        'zip_file',
        'share_code',
        'mobile',
        'aadhaar_no',
        'aadhaar_name',
        'aadhaar_address',
        'aadhaar_image',
        'pan_kyc',
        'pan_no',
        'pan_name',
        'pan_file',
        'bank_kyc',
        'acc_no',
        'acc_name',
        'ifsc_code',
        'bank_name',
        'branch_name',
        'selfie_kyc',
        'selfie_image',
        'success_score',
        'business_kyc',
        'business_name',
        'business_address',
        'pincode',
        'state',
        'city',
        'category',
        'front_image',
        'inside_image',
        'latitude',
        'longitude',
        'blat',
        'blong',
        'complete_kyc'
    ];
}
