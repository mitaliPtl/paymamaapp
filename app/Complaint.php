<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $table = "tbl_complaints";
    protected $primaryKey = "id";
    public $timestamps = false;
    
    protected $fillable = [
        "complaint_id",
        "order_id",
        "user_id",
        "role_id",
        "recipient_id",
        "transaction_id",
        "template_id",
        "complaint_message",
        "comp_default_time",
        "admin_reply",
        "complaint_date",
        "admin_reply_date",
        "complaint_status",
        "updated_on"
        
    ];

    // protected $casts = [
    //     'total_amount' => 'float',
    // ];

   
}
