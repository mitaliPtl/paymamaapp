<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    protected $table = "tbl_template";
    protected $primaryKey = "template_id";
    public $timestamps = false;
    
    protected $fillable = [
        "service_id",
        "role_id",
        "template",
        "timing",
        "isDeleted",
        "created_on",
        "template_updated"
        
    ];

    
    // protected $casts = [
    //     'total_amount' => 'float',
    // ];

    public static function getTemplateById($id)
    {
        $response = "";
        if (isset($alias) && $alias) {
            $result = self::where('template_id', $id)->pluck('template');
            if (count($result) > 0) {
                $response = $result[0];
            }

        }

        return $response;
    }
}
