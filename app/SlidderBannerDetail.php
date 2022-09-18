<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SlidderBannerDetail extends Model
{
    protected $table = "tbl_slidder_banner_dtls";

    protected $fillable =[
        'role_id',
        'platform',
        'location',
        'image_file_ids'
    ];

    protected $casts = [
        'image_file_ids' => 'array'
   ];
}
