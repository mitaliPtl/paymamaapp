<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TDS extends Model
{
    protected $table = "tbl_tds";
    protected $primaryKey = "tds_id";
    public $timestamps = false;
    
    protected $fillable = [
        "user_id",
        "role_id",
        "tds_period",
        "file_id",
        "created_on",    
    ];

    // protected $casts = [
    //     'total_amount' => 'float',
    // ];

   
}
