<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'tbl_district_mst';
    protected $primaryKey = 'city_id';

    /**
     * Get City List By Providing State Id
     */
    public static function getCityFromState($stateId)
    {
        $response = [];
        if (isset($stateId) && $stateId) {
            $response = self::select('city_id','city_name')->where('state_id', $stateId);
        }

        return $response;
    }

    /**
     * Get State Name by providing Id
     */
    public static function getCityNameById($id)
    {
        $response = "";
        if (isset($id) && $id) {
            $response = self::where('city_id', $id)->pluck('city_name')->first();
        }

        return $response;
    }
}
