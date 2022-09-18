<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $table = 'tbl_packages';
    protected $primaryKey = 'package_id';
}
