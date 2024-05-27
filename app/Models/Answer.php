<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;

class Answer extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = "answers";

    protected $fillable = [
        'code_subkategori',
        'answer_text',

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
        $data = Answer::orderBy('id', 'ASC')->get();
        return $data;
    }
    public function faqSubcategory()
    {
        return $this->belongsTo(FaqSubcategories::class, 'code_subkategori', 'code_subkategori');
    }
}

