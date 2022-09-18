<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KycDetail extends Model
{
    protected $table = "tbl_kyc_details";

    public $timestamps = false;

    protected $fillable = [
        'user_id',

        'pan_front_file_id',
        'pan_front_file_status',

        'aadhar_front_file_id',
        'aadhar_front_file_status',

        'aadhar_back_file_id',
        'aadhar_back_file_status',

        'photo_front_file_id',
        'photo_front_file_status',

        'photo_inner_file_id',
        'photo_inner_file_status',
        'longitude',
        'latitude',
        'status',
        'is_deleted',
        'updated_on'
    ];

    public function panFile()
    {
        return $this->belongsTo('App\File', 'pan_front_file_id');
    }

    public function aadharFrontFile()
    {
        return $this->belongsTo('App\File', 'aadhar_front_file_id');
    }

    public function aadharBackFile()
    {
        return $this->belongsTo('App\File', 'aadhar_back_file_id');
    }

    public function photoFrontFile()
    {
        return $this->belongsTo('App\File', 'photo_front_file_id');
    }

    public function photoInnerFile()
    {
        return $this->belongsTo('App\File', 'photo_inner_file_id');
    }
}
