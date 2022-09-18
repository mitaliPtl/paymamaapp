<?php

namespace App;

use Config;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\State;
use App\City;
use App\StoreCategory;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'tbl_users';
    // protected $primaryKey = 'user_id';
    protected $primaryKey = 'userId';

    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'roleId',
        'username',
        'mpin',
        'mobile',
        'alternate_mob_no',
        'pan_no',
        'aadhar_no',
        'gst_no',
        'whatsapp_no',
        'telegram_no',
        'min_balance',
        'roleId',
        'parent_role_id',
        'parent_user_id',
        'package_id',
        'state_id',
        'district_id',
        'address',
        'zip_code',
        'wallet_balance',
        'commission_id',
        'store_name',
        'store_category_id',
        'logged_otp',
        'createdDtm',
        'createdBy',
        'pg_options',
        'min_amount_deposit',
        'max_amount_deposit',
        'min_amount_withdraw',
        'max_amount_withdraw',
        'business_name',
        'activated_status',
        'updatedBy',
        'pg_status',
        'fos_id',
        'distributor_credit',
        'fos_credit',
        'isSpam'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get User List By Providing Role Id
     */
    public static function getUserFromRole($roleId)
    {
        $response = [];
        if (isset($roleId) && $roleId) {
            $response = self::select('userId', 'first_name')->where('roleId', $roleId)->where('isDeleted', Config::get('constants.NOT-DELETED'))->where('activated_status', Config::get('constants.ACTIVE'));
        }

        return $response;
    }

    public function profilePic()
    {
        return $this->belongsTo('App\File', 'profile_pic_id');
    }

    /**
     * Get User Store Name by providing User Id
     */
    public static function getStoreNameById($userId)
    {
        $response = "";
        if (isset($userId) && $userId) {
            $response = self::where('userId', $userId)->pluck('store_name')->first();
        }

        return $response;
    }
     public static function getMobilenoById($userId)
    {
        $response = "";
        if (isset($userId) && $userId) {
            $response = self::where('userId', $userId)->pluck('mobile')->first();
        }

        return $response;
    }
    public static function getUsernameId($userId)
    {
        $response = "";
        if (isset($userId) && $userId) {
            $response = self::where('userId', $userId)->pluck('username')->first();
        }

        return $response;
    }
    
    public static function getIdbyUsername($username)
    {
        $response = "";
        if (isset($username) && $username) {
            $response = self::where('username', $username)->pluck('userId')->first();
        }

        return $response;
    }

    /**
     * Get User Column Detail by providing User Id
     */
    public static function getClmnValById($userId, $column)
    {
        $response = "";
        if (isset($userId) && $userId) {
            $response = self::where('userId', $userId)->pluck($column)->first();
        }

        return $response;
    }

    /**
     * Get User Meta Column Detail by providing User Id
     */
    public static function getMetaClmnValById($userId, $column)
    {
        $response = "";
        if (isset($userId) && $userId) {
            $clmnId = self::where('userId', $userId)->pluck($column)->first();
            if (isset($clmnId) && $clmnId) {
                if ($column == "state_id") {
                    $response = State::getStateNameById($clmnId);
                } else if ($column == "district_id") {
                    $response = City::getCityNameById($clmnId);
                } else if ($column == "store_category_id") {
                    $response = StoreCategory::getStoreCatNameById($clmnId);
                }
            }
        }

        return $response;
    }

    public static function getUsernameById($userId){
        $response = "";
        if (isset($userId) && $userId) {
            $response = self::where('userId', $userId)->pluck('username')->first();
        }

        return $response;
    } 
    
    public static function getMobileById($userId){
        $response = "";
        if (isset($userId) && $userId) {
            $response = self::where('userId', $userId)->pluck('mobile')->first();
        }

        return $response;
    } 
    
    public function ekyc(){
        return $this->hasOne('App\Ekyc','user_id','userId');
    }
    
    public function nonkyc(){
        return $this->hasOne('App\Ekyc','user_id')->where('complete_kyc', '0');
    }
    
    public function role(){
        return $this->hasOne('App\Role','roleId','parent_role_id');
    }
    
    public function storecategory(){
        return $this->hasOne('App\StoreCategory','id','store_category_id');
    }
    
    public function parentuser(){
        return $this->hasOne('App\User','userId','parent_user_id');
    }
}
