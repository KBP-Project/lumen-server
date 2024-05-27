<?php

namespace App\Http\Controllers;

use App\Models\Messages;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\{DB, Validator};

class MessagesController extends Controller
{
    public function getData(Request $request): JsonResponse
    {
        try {
            $getData = Messages::all();

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
            $getData = Messages::find($id);

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
                'code_message' => 'required|string|unique:header_messages,code_message',
                'body_message' => 'required|string',
                'from' => 'required|integer',
                'to' => 'required|integer',
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
            Messages::create([
                'code_message' => $request->input('code_message'),
                'body_message' => $request->input('body_message'),
                'from' => $request->input('from'),
                'to' => $request->input('to'),
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
            $Messages = Messages::find($id);
    
            // Jika data tidak ditemukan, kirim respons dengan status 404
            if (!$Messages) {
                return response()->json([
                    'message' => 'Data not found',
                    'code' => 404,
                    'status' => false,
                ], 404);
            }
    
            // Hapus data kategori FAQ
            $Messages->delete();
    
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
            $Messages = Messages::find($id);

            // Jika data tidak ditemukan, kirim respons dengan status 404
            if (!$Messages) {
                return response()->json([
                    'message' => 'Data not found',
                    'code' => 404,
                    'status' => false,
                ], 404);
            }

            // Validasi input
            $validator = Validator::make($request->all(), [
                'code_message' => 'required|string|unique:header_messages,code_message',
                'body_message' => 'required|string',
                'from' => 'required|integer',
                'to' => 'required|integer',
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
            $Messages->update([
                'code_message' => $request->input('code_message'),
                'body_message' => $request->input('body_message'),
                'from' => $request->input('from'),
                'to' => $request->input('to'),
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