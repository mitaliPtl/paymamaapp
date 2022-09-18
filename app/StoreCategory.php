<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreCategory extends Model
{
    protected $table = "tbl_store_categories";
    public $timestamps = false;

    protected $fillable = [
        'store_category_name',
        'activated_status',
        'is_deleted',
        'updated_on'
    ];

    /**
     * Get Store Category Name by providing Id
     */
    public static function getStoreCatNameById($id)
    {
        $response = "";
        if (isset($id) && $id) {
            $response = self::where('id', $id)->pluck('store_category_name')->first();
        }

        return $response;
    }
}
