<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApiSetting extends Model
{
    protected $table = 'tbl_api_settings';
    protected $primaryKey = 'api_id';
    
    public $timestamps = false;

    protected $fillable = [
        'api_name',
        'api_dtls',
        'api_url',
        'password',
        'username',
        'balance',
        'activated_status',
        'is_deleted',
    ];

    /**
     * Get API Name by providing User Id
     */
    public static function getApiNameById($id)
    {
        return "hello";
        $response = "";
        if (isset($id) && $id) {
            $response = self::where('api_id', $id)->pluck('api_name')->first();
        }

        return $response;
    }
    public static function getApiusernameById($Id)
    {
        $response = '';
        if (isset($Id) && $Id) {
           
            $response = self::where('api_id',$Id)->pluck('username')->first(); 
        }
         return $response;
    }
    public static function getApitokenById($Id)
    {
        $response = '';
        if (isset($Id) && $Id) {
           
            $response = self::where('api_id',$Id)->pluck('api_token')->first(); 
        }
         return $response;
    }
    public static function getApisecretkeyById($Id)
    {
        $response = '';
        if (isset($Id) && $Id) {
           
            $response = self::where('api_id',$Id)->pluck('api_secretkey')->first(); 
        }
         return $response;
    }
}
