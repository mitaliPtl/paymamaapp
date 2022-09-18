<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ccsenders extends Model
{
    protected $table = 'cc_senders';
    public $timestamps = false;
    
    protected $fillable = [
        'user_id',
        'name',
        'mobile',
        'pan',
        'trans_limit'
    ];
    
}