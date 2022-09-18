<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
    protected $table = 'tbl_operators';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'service_type',
        'operator_name',
        'operator_code',
        'id_deleted',
    ];

    public function servicesType()
    {
        return $this->belongsTo('App\ServicesType', 'service_type');
    }
}
