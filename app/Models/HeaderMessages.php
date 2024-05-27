<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;

class HeaderMessages extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = "header_messages";

    protected $fillable = [
        'code_subkategori',
        'code_message',
        'title',
        'pembuat_percakapan',
        'status',

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
        $data = HeaderMessages::orderBy('id', 'ASC')->get();
        return $data;
    }
}


