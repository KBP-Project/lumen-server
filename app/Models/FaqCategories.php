<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use DateTimeInterface;
use App\Models\Profile;

class FaqCategories extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = "faq_categories";

    protected $fillable = [
        'code_role',
        'code_kategori',
        'nama_kategori',
        'icon',
        'prioritas',
        
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

    public function getData($per_page, $tipe = null){
        try {
            $data = DB::table('faq_categories as fc')
                ->join('faq_subcategories as fsc', 'fsc.code_kategori', '=', 'fc.code_kategori')
                ->join('answers as a', 'a.code_subkategori', '=', 'fsc.code_subkategori')
                ->join('master_roles as mr', 'mr.code_role', '=', 'fc.code_role')
                ->join('pics as p', 'p.code_subkategori', '=', 'fsc.code_subkategori')
                ->join('profiles as pr', 'p.users_id', 'pr.id')
                ->select('fsc.id', 'mr.nama_role', 'fc.nama_kategori', 'fc.icon', 'fc.prioritas', 'fsc.nama_subkategori',  'a.answer_text' ,'p.users_id' ,'a.created_at', 'a.updated_at', 'pr.nama', 'pr.nickname')
                ->whereNull('fsc.deleted_at');
                $tipe ? $data = $data -> where('mr.code_role', $tipe) : $data;
                // ->orderBy('fc.id', 'ASC')
                $data=$data->latest('fc.id')
                ->paginate($per_page);
                
                return $data;
        } catch (\Throwable $th) {
            return $th->getMessage(); // Added semicolon here
        }
    }
    public function subcategories()
    {
        return $this->hasMany(FaqSubcategories::class, 'code_kategori', 'code_kategori');
    }  
    public function answers()
    {
        return $this->hasManyThrough(Answer::class, FaqSubcategories::class, 'code_kategori', 'code_subkategori', 'code_kategori', 'code_subkategori');
    }
}


