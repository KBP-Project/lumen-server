<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;

class Pic extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = "pics";

    protected $fillable = [
        'code_subkategori',
        'users_id',

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
        $data = Pic::orderBy('id', 'ASC')->get();
        return $data;
    }
}
