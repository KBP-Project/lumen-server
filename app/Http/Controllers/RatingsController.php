<?php

namespace App\Http\Controllers;

use App\Models\Ratings;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\{DB, Validator};

use Illuminate\Support\Carbon;

class RatingsController extends Controller
{
    public function getRating(Request $request)
    {
        try {
            $data = Ratings::select('msc.customer_name', 'ur.code_role', 'mr.nama_role', 'pics.users_id', 'fsc.nama_subkategori', 'ratings.rating_value', 'ratings.pesan_feedback', 'ratings.created_at', 'ratings.created_by', 'pf.nama', 'fc.nama_kategori', 'pf2.nama as nama_pic')
                ->leftJoin('pics', 'pics.code_subkategori', '=', 'ratings.code_subkategori')
                ->leftJoin('profiles as pf', 'pf.users_id', '=', 'ratings.created_by')
                ->leftJoin('user_roles as ur', 'ur.users_id', '=', 'ratings.created_by')
                ->leftJoin('master_roles as mr', 'mr.code_role', '=', 'ur.code_role')
                ->leftJoin('placement as plm', 'plm.users_id', '=', 'ratings.created_by')
                ->leftJoin('mst_customer as msc', 'msc.customer_code', '=', 'plm.customer_code')
                ->leftJoin('faq_subcategories as fsc', 'fsc.code_subkategori', '=', 'ratings.code_subkategori')
                ->leftJoin('faq_categories as fc', 'fc.code_kategori', '=', 'fsc.code_kategori')
                ->leftJoin('profiles as pf2','pf2.users_id','=','pics.users_id')
                ->orderBy('ratings.id','desc')
                ->get();

            return response()->json([
                'code' => 200,
                'status' => true,
                'data' => $data,
            ], 200);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function postData(Request $request)
    // : JsonResponse
    {
    

        // Memasukkan data baru ke dalam tabel faq_categories
        $data = [
            'code_subkategori' => $request->input('code_subkategori'),
            'rating_value' => $request->input('rating_value'),
            'pesan_feedback' => $request->input('pesan_feedback'),
            'created_by' => $request->input('created_by'),
            'created_at' => Carbon::now()
        ];

        $save = Ratings::insert($data);

        // return $data;

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'Data added successfully',
        ], 200);
        // } catch (\Throwable $th) {
        //     // Tangkap kesalahan dan kirim respons dengan status 500
        //     return response()->json([
        //         'code' => 500,
        //         'status' => false,
        //         'message' => 'Failed to add data',
        //     ], 500);
        // }
    }
}
