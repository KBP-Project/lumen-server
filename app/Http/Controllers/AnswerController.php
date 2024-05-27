<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\{DB, Validator};

class AnswerController extends Controller
{
    public function getData(Request $request): JsonResponse
    {
        try {
            $getData = Answer::all();

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
            $getData = Answer::find($id);

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
                'code_subkategori' => 'required|string',
                'answer_text'=> 'required|string'
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
            Answer::create([
                'code_subkategori' => $request->input('code_subkategori'),
                'answer_text' => $request->input('answer_text'),
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
            $answer = Answer::find($id);

            if (!$answer) {
                return response()->json([
                    'message' => 'Data not found',
                    'code' => 404,
                    'status' => false,
                ], 404);
            }

            $answer->delete();

            return response()->json([
                'code' => 200,
                'status' => true,
                'message' => 'Data deleted successfully',
            ], 200);
        } catch (\Throwable $th) {
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
            // Cari data jawaban berdasarkan ID
            $answer = Answer::find($id);

            // Jika data tidak ditemukan, kirim respons dengan status 404
            if (!$answer) {
                return response()->json([
                    'message' => 'Data not found',
                    'code' => 404,
                    'status' => false,
                ], 404);
            }

            // Validasi input
            $validator = Validator::make($request->all(), [
                'code_subkategori' => 'required|string',
                'answer_text'=> 'required|string',
            ]);

            // Jika validasi gagal, kembalikan pesan error
            if ($validator->fails()) {
                return response()->json([
                    'code' => 400,
                    'status' => false,
                    'message' => $validator->errors()->first(),
                ], 400);
            }

            // Update data jawaban
            $answer->update([
                'code_subkategori' => $request->input('code_subkategori'),
                'answer_text' => $request->input('answer_text'),
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