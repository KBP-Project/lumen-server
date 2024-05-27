<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;

class users extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = "users";

    protected $fillable = [
        'nama',
        'email',
        'password',

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
    public function getData(){
        $data = users::orderBy('id', 'ASC')->get();
        return $data;
    }
}

