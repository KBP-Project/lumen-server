<?php

namespace App\Http\Controllers;

use App\Models\HeaderMessages;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\{DB, Validator};

class HeaderMessagesController extends Controller
{
    public function getData(Request $request): JsonResponse
    {
        try {
            $getData = HeaderMessages::all();

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
            $getData = HeaderMessages::find($id);

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
                'code_message' => 'required|string|unique:header_messages,code_message',
                'title' => 'required|string',
                'pembuat_percakapan'=> 'required|integer',
                'status'=> 'required|boolean'
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
            HeaderMessages::create([
                'code_subkategori' => $request->input('code_subkategori'),
                'code_message' => $request->input('code_message'),
                'title' => $request->input('title'),
                'pembuat_percakapan' => $request->input('pembuat_percakapan'),
                'status' => $request->input('status'),
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
            $headerMessage = HeaderMessages::find($id);
    
            // Jika data tidak ditemukan, kirim respons dengan status 404
            if (!$headerMessage) {
                return response()->json([
                    'message' => 'Data not found',
                    'code' => 404,
                    'status' => false,
                ], 404);
            }
    
            // Hapus data kategori FAQ
            $headerMessage->delete();
    
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
            $headerMessage = HeaderMessages::find($id);

            // Jika data tidak ditemukan, kirim respons dengan status 404
            if (!$headerMessage) {
                return response()->json([
                    'message' => 'Data not found',
                    'code' => 404,
                    'status' => false,
                ], 404);
            }

            // Validasi input
            $validator = Validator::make($request->all(), [
                'code_subkategori' => 'required|string',
                'code_message' => 'required|string|unique:header_messages,code_message,' . $id,
                'title' => 'required|string',
                'pembuat_percakapan'=> 'required|integer',
                'status'=> 'required|boolean'
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
            $headerMessage->update([
                'code_subkategori' => $request->input('code_subkategori'),
                'code_message' => $request->input('code_message'),
                'title' => $request->input('title'),
                'pembuat_percakapan' => $request->input('pembuat_percakapan'),
                'status' => $request->input('status'),
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