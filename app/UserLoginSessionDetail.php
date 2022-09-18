<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserLoginSessionDetail extends Model
{
    protected $table = "tbl_users_login_session_dtl";
    protected $primaryKey = "id";
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'role_id',
        'apiKey',
    ];

    /**
     * Get user api_key Providing user's id
     */
    public static function getUserApikey($userId)
    {
        $result = "";
        $apiKey = isset(self::where('user_id', $userId)->pluck("apikey")[0]) ? self::where('user_id', $userId)->pluck("apikey")[0] :'';
        if ($apiKey) {
            $result = $apiKey;
        }

        return $result;
    }
}
