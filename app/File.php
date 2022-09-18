<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $table = "tbl_files";

    protected $fillable = [
        'name',
        'file_path'
    ];

    public function panFile()
    {
        return $this->hasMany('App\KycDetail', 'pan_front_file_id');
    }

    public function aadharFrontFile()
    {
        return $this->hasMany('App\KycDetail', 'aadhar_front_file_id');
    }

    public function aadharBackFile()
    {
        return $this->hasMany('App\KycDetail', 'aadhar_back_file_id');
    }

    public function photoFrontFile()
    {
        return $this->hasMany('App\KycDetail', 'photo_front_file_id');
    }

    public function photoInnerFile()
    {
        return $this->hasMany('App\KycDetail', 'photo_inner_file_id');
    }

    public function profilePic()
    {
        return $this->hasMany('App\User', 'profile_pic_id');
    }
}
