<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DMTVerificationList extends Model
{
    protected $table = 'tbl_dmt_verified_banks';
    protected $primaryKey = 'id';
    
    public $timestamps = false;

    // protected $fillable = [
    //     'api_name',
    //     'api_dtls',
    //     'api_url',
    //     'password',
    //     'username',
    //     'balance',
    //     'activated_status',
    //     'is_deleted',
    // ];
    
}

?>