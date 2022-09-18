<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApiLogDetail extends Model
{
    protected $table = "tbl_apilog_dts";
    protected $primaryKey = "id";
    public $timestamps = false;

    // turn off only updated_at
    const UPDATED_AT = false;
}
