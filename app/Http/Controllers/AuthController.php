<?php

namespace App\Http\Controllers;

use App\Models\{Users};
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\{DB, Validator};
use Carbon\Carbon;

class AuthController extends Controller
{
    public function Login(Request $request)
    {
        $check = Users::select('users.id', 'email', 'p.nama', 'nickname', 'nomor_hp', 'msc.customer_name', 'ur.code_role')
            ->leftJoin('user_roles as ur', 'ur.users_id', '=', 'users.id')
            ->leftJoin('placement as pl', 'pl.users_id', '=', 'users.id')
            ->leftJoin('mst_customer as msc', 'msc.customer_code', '=', 'pl.customer_code')
            ->leftJoin('profiles as p', 'p.users_id', '=', 'users.id')
            ->where('email', $request->email)
            ->where('password', $request->password)
            ->first();
        if ($check) {
            return response()->json([
                'data' => $check,
                'code' => 200,
                'status' => true,
            ], 200);
        }
    }
    public function LoginWeb(Request $request)
    {
        $check = Users::select('users.id', 'email', 'users.nama', 'p.nickname', 'p.nomor_hp')
            ->leftJoin('profiles as p', 'p.users_id', '=', 'users.id')
            ->where('email', $request->email)
            ->where('password', $request->password)
            ->first();
        if ($check) {
            return response()->json([
                'data' => $check,
                'code' => 200,
                'status' => true,
            ], 200);
        }
    }
}
