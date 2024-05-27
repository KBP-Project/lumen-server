<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\{DB, Validator};

class NewsController extends Controller
{
    public function getData(Request $request): JsonResponse
    {
        try {
            $getData = News::all();

            return response()->json([
                'data'=> $getData,
                'code' => 200,
                'status' => true,
            ],200);
        }catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'status' => false
            ],500);
        }
    }

    public function getDataById(Request $request, int $id): JsonResponse
    {
        try {
            $getData = News::find($id);

            if (!$getData) {
                return response()->json([
                    'message' => 'Data not found',
                    'code' => 404,
                    'status' => false,
                ], 404);
            }
            
            return response()->json([
                'data' => $getData,
                'code' => 200,
                'status' => true,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'status' => false
            ], 500);
        }
    }

    public function postData(Request $request): JsonResponse
    {  
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'title' => 'required|string',
                'body_news' => 'required|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
            ]);
    
            // Jika validasi gagal, kembalikan pesan error
            if ($validator->fails()) {
                return response()->json([
                    'code' => 400,
                    'status' => false,
                    'message' => $validator->errors()->first(),
                ], 400);
            }
    
            // Memasukkan data baru ke dalam tabel faq_categories
            News::create([
                'title' => $request->input('title'),
                'body_news' => $request->input('body_news'),
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
            ]);

            return response()->json([
                'code' => 200,
                'status' => true,
                'message' => 'Data added successfully',
            ], 200);
        } catch (\Throwable $th) {
            // Tangkap kesalahan dan kirim respons dengan status 500
            return response()->json([
                'code' => 500,
                'status' => false,
                'message' => 'Failed to add data',
            ], 500);
        }
    }

    public function deleteData(Request $request, int $id): JsonResponse
    {
        try {
            // Cari data kategori FAQ berdasarkan ID
            $News = News::find($id);
    
            // Jika data tidak ditemukan, kirim respons dengan status 404
            if (!$News) {
                return response()->json([
                    'message' => 'Data not found',
                    'code' => 404,
                    'status' => false,
                ], 404);
            }
    
            // Hapus data kategori FAQ
            $News->delete();
    
            // Kirim respons sukses
            return response()->json([
                'code' => 200,
                'status' => true,
                'message' => 'Data deleted successfully',
            ], 200);
        } catch (\Throwable $th) {
            // Tangkap kesalahan dan kirim respons dengan status 500
            return response()->json([
                'code' => 500,
                'status' => false,
                'message' => 'Failed to delete data',
            ], 500);
        }
    }
    
    public function editData(Request $request, int $id): JsonResponse
    {
        try {
            // Cari data kategori FAQ berdasarkan ID
            $News = News::find($id);

            // Jika data tidak ditemukan, kirim respons dengan status 404
            if (!$News) {
                return response()->json([
                    'message' => 'Data not found',
                    'code' => 404,
                    'status' => false,
                ], 404);
            }

            // Validasi input
            $validator = Validator::make($request->all(), [
                'title' => 'required|string',
                'body_news' => 'required|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
            ]);

            // Jika validasi gagal, kembalikan pesan error
            if ($validator->fails()) {
                return response()->json([
                    'code' => 400,
                    'status' => false,
                    'message' => $validator->errors()->first(),
                ], 400);
            }

            // Update data kategori FAQ
            $News ->update([
                'title' => $request->input('title'),
                'body_news' => $request->input('body_news'),
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
            ]);

            // Kirim respons sukses
            return response()->json([
                'code' => 200,
                'status' => true,
                'message' => 'Data updated successfully',
            ], 200);
        } catch (\Throwable $th) {
            // Tangkap kesalahan dan kirim respons dengan status 500
            return response()->json([
                'code' => 500,
                'status' => false,
                'message' => 'Failed to update data',
            ], 500);
        }
    }
}