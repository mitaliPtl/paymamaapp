<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OffersNotice extends Model
{
    protected $table = "tbl_notice";
    protected $primaryKey = "notice_id";
    public $timestamps = false;

    protected $fillable = [
       
        "notice_title",
        "notice_description",
        "image",
        "notice_type",
        "notice_visible",
        "notice_isDeleted",
        "created_on",
        "updated_on"
    ];
}