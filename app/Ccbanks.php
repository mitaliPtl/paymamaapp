<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ccbanks extends Model
{
    protected $table = 'cc_bank_list';
    public $timestamps = false;
    
    protected $fillable = [
        'cc_sender_id',
        'bank_name',
        'bank_acc_no',
        'bank_acc_name',
        'bank_acc_ifsc',
        'is_verified'
    ];
    
}