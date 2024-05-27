<?php

namespace App\Http\Controllers;

use App\Models\FaqSubcategories;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\{DB, Validator};

class FaqSubcategoriesController extends Controller
{
    public function getData(Request $request): JsonResponse
    {
        try {
            $getData = FaqSubcategories::all();

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
            $getData = FaqSubcategories::find($id);

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
                'code_kategori' => 'required|string',
                'code_subkategori' => 'required|string|unique:faq_subcategories,code_subkategori',
                'nama_subkategori' => 'required|string',
                'pic_id'=> 'required|integer'
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
            FaqSubcategories::create([
                'code_kategori' => $request->input('code_kategori'),
                'code_subkategori' => $request->input('code_subkategori'),
                'nama_subkategori' => $request->input('nama_subkategori'),
                'pic_id' => $request->input('pic_id'),
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
            // Cari data kategori FAQ Sub berdasarkan ID
            $faqSubCategory = FaqSubcategories::find($id);
    
            // Jika data tidak ditemukan, kirim respons dengan status 404
            if (!$faqSubCategory) {
                return response()->json([
                    'message' => 'Data not found',
                    'code' => 404,
                    'status' => false,
                ], 404);
            }
    
            // Hapus data kategori FAQ Sub
            $faqSubCategory->delete();
    
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
            // Cari data subkategori FAQ berdasarkan ID
            $faqSubCategory = FaqSubcategories::find($id);

            // Jika data tidak ditemukan, kirim respons dengan status 404
            if (!$faqSubCategory) {
                return response()->json([
                    'message' => 'Data not found',
                    'code' => 404,
                    'status' => false,
                ], 404);
            }

            // Validasi input
            $validator = Validator::make($request->all(), [
                'code_kategori' => 'required|string',
                'code_subkategori' => 'required|string|unique:faq_subcategories,code_subkategori,' . $id,
                'nama_subkategori' => 'required|string',
                'pic_id'=> 'required|integer'
            ]);

            // Jika validasi gagal, kembalikan pesan error
            if ($validator->fails()) {
                return response()->json([
                    'code' => 400,
                    'status' => false,
                    'message' => $validator->errors()->first(),
                ], 400);
            }

            // Update data subkategori FAQ
            $faqSubCategory->update([
                'code_kategori' => $request->input('code_kategori'),
                'code_subkategori' => $request->input('code_subkategori'),
                'nama_subkategori' => $request->input('nama_subkategori'),
                'pic_id' => $request->input('pic_id'),
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