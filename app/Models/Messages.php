<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;

class Messages extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = "messages";

    protected $fillable = [
        'code_message',
        'body_messages',
        'from',
        'to',

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
        $data = Messages::orderBy('id', 'ASC')->get();
        return $data;
    }
}

