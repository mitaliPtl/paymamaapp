<?php

namespace App;

use Config;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\State;
use App\City;
use App\StoreCategory;

class Verification extends Authenticatable
{
    use Notifiable;

    protected $table = 'tbl_verification';
    // protected $primaryKey = 'user_id';
    protected $primaryKey = 'id';

    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
   
}
