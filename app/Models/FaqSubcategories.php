<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;

class FaqSubcategories extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = "faq_subcategories";

    protected $fillable = [
        'code_kategori',
        'code_subkategori',
        'nama_subkategori',

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
        $data = FaqSubcategories::orderBy('id', 'ASC')->get();
        return $data;
    }
    public function answers()
    {
        return $this->hasMany(Answer::class, 'code_subkategori', 'code_subkategori');
    }
    public function category()
    {
        return $this->belongsTo(FaqCategories::class, 'code_kategori', 'code_kategori');
    }
}


