<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;

class MasterCustomer extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = "mst_customer";

    protected $fillable = [
    
        'customer_code',
        'customer_name', 
        'customer_address', 
        'customer_pos_code',
        'customer_longitude',
        'customer_latitude',
        'pic1',
        'pic1_phone',
        'pic1_email',
        'pic2',
        'pic2_phone',
        'pic2_email',
        'start_contract',
        'end_contract',
        
        'created_by',
        'created_at',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by',
    ];

    protected function serializeDate(DateTimeInterface $date){
        return $date->format('Y-m-d H:i:s');
    }
}