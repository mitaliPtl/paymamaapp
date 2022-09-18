<?php
namespace App;

use App\KycDetail;
use App\Role;
use App\UserLoginSessionDetail;
use Illuminate\Support\Facades\Auth;

class CustomAuth extends Auth
{
    /**
     * Get User Role Alias
     */
    public static function userRoleAlias()
    {
        $result = "";
        $alias = Role::where('roleId', self::user()->roleId)->pluck('alias');
        if ($alias && count($alias) > 0) {
            $result = $alias[0];
        }
        return $result;
    }

    /**
     * Get User Kyc Details
     */
    public static function userKycId()
    {
        $result = "";
        $keyDtl = KycDetail::where('user_id', self::id())
            ->with(['panFile', 'aadharFrontFile', 'aadharBackFile', 'photoFrontFile', 'photoInnerFile'])
            ->get();
        if ($keyDtl && count($keyDtl) > 0) {
            $result = $keyDtl[0];
        }
        return $result;
    }

    /**
     * Get User API token key Details
     */
    public static function apiKey()
    {
        $result = "";

        $apikey = UserLoginSessionDetail::where('user_id', self::id())->where('role_id', self::user()->roleId)->pluck('apikey')->first();
        
        if (isset($apikey) && $apikey) {
            $result = $apikey;
        }
        return $result;
    }

    /**
     * Get User Profile Pic Path
     */
    public static function dpPath()
    {
        $result = "";

        $filePath = File::where('id', self::user()->profile_pic_id)->pluck('file_path')->first();
        
        if (isset($filePath) && $filePath) {
            $result = $filePath;
        }
        return $result;
    }
}
