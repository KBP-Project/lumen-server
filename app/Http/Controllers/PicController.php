<?php

namespace App\Http\Controllers;

use App\Models\{Pic, Profile, users};
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PicController extends Controller
{
    public function getPic(): JsonResponse
    {
            try{
            $data = Pic::select('p.nama', 'p.nickname','p.nomor_hp','u.email')
            // ->leftJoin('faq_subcategories as fsc','fsc.code_subkategori','=','pics.code_subkategori')
            ->leftJoin('users as u','u.id','=','pics.users_id')
            ->leftJoin('profiles as p','p.users_id','=','pics.users_id')
            ->where('pics.deleted_at', null)
            ->groupBy('u.id','p.nama', 'p.nickname','p.nomor_hp','u.email')
            ->get();
            // return $data;

            return response()->json([
                'code' => 200,
                'status' => true,
                'data'=> $data,
            ],200);
        }catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'status' => false,
                'message' => $th->getMessage()
            ],500);
        }
    }

    public function editPic(Request $request, $id): JsonResponse
    {
        try {
            // Temukan record Pic
            $pic = Pic::findOrFail($id);

            // Temukan record user dan profile terkait
            $user = users::findOrFail($pic->users_id);
            $profile = Profile::where('users_id', $pic->users_id)->firstOrFail();

            // Perbarui record terkait
            $profile->update([
                'nickname' => $request->input('nickname'),
                'nomor_hp' => $request->input('nomor_hp'),
            ]);

            $user->update([
                'email' => $request->input('email'),
            ]);

            // Kembalikan respon sukses
            return response()->json([
                'data' => [
                    'pic' => $pic,
                    'profile' => $profile,
                    'user' => $user
                ],
                'code' => 200,
                'status' => true,
                'message' => 'Pic berhasil diperbarui'
            ], 200);
        } catch (\Throwable $th) {
            // Kembalikan respon error
            return response()->json([
                'code' => 500,
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}


