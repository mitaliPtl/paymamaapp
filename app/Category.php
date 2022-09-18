<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = "tbl_category";
    protected $primaryKey = "category_id";
    public $timestamps = false;
    
    protected $fillable = [
        "category",
        "category_is_deleted",
        "category_created_on",
        "category_updated_on"
        
    ];

    // protected $casts = [
    //     'total_amount' => 'float',
    // ];

   
}
