<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $table = 'tbl_state_mst';
    protected $primaryKey = 'state_id';

    /**
     * Get State Name by providing Id
     */
    public static function getStateNameById($id)
    {
        $response = "";
        if (isset($id) && $id) {
            $response = self::where('state_id', $id)->pluck('state_name')->first();
        }

        return $response;
    }
}
