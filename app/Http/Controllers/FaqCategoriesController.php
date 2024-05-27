<?php

namespace App\Http\Controllers;

use App\Models\{FaqCategories, FaqSubcategories, Answer, Pic};
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\{DB, Validator};
use Carbon\Carbon;

class FaqCategoriesController extends Controller
{
    public function getData(Request $request): JsonResponse
    {
        try {
            $per_page = $request->input('per_page', 9);
            // $getData = FaqCategories::orderBy('id', 'ASC')->paginate($per_page);
            $getData = (new FaqCategories)->getData($per_page);

            return response()->json([
                'data' => $getData,
                'code' => 200,
                'status' => true,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function newCat(Request $request): JsonResponse
    {
        try {
            $name = strtoupper($request->input('name')); // Mengubah nama kategori menjadi huruf kapital
            $code = $request->input('code');
            $icon = $request->input('selectIcon');
            $prioritas = $request->input('prioritas');

            // Mengambil record kategori terakhir termasuk yang telah dihapus (soft-deleted)
            $lastCategoryRecord = FaqCategories::withTrashed()->latest()->first();
            $lastCategoryId = $lastCategoryRecord ? intval(substr($lastCategoryRecord->code_kategori, 1)) : 0;
            $newCategoryCode = 'K' . ($lastCategoryId + 1);

            $data = [
                'code_role' => $code,
                'nama_kategori' => $name,
                'code_kategori' => $newCategoryCode,
                'icon' => $icon,
                'prioritas' => $prioritas,
            ];

            FaqCategories::create($data);

            return response()->json([
                'code' => 200,
                'status' => true,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function getCat($role): JsonResponse
    {
        try {
            if ($role == 'all') {
                $getData = FaqCategories::orderBy('id','desc')->get();
            } else {
                $getData = FaqCategories::where('code_role', $role)->orderBy('id','desc')->get();
            }

            return response()->json([
                'data' => $getData,
                'code' => 200,
                'status' => true,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function getSubCat(Request $request, $id): JsonResponse
    {
        try {

            $getData = FaqSubcategories::where('code_kategori', $id)->get();

            return response()->json([
                'data' => $getData,
                'code' => 200,
                'status' => true,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function getAnswer(Request $request, $id): JsonResponse
    {
        try {

            $getData = Answer::where('code_subkategori', $id)->first();

            return response()->json([
                'data' => $getData,
                'code' => 200,
                'status' => true,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function getDataById(Request $request, int $id): JsonResponse
    {
        try {
            $per_page = $request->input('per_page', 9);
            $getData = FaqCategories::find($id)->paginate($per_page);

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

            foreach ($request->formSubkategori as $data) {
                $lastSubcategoryRecord = FaqSubcategories::withTrashed()
                    ->orderBy('id', 'desc')
                    ->first();

                $lastSubcategoryId = $lastSubcategoryRecord ? intval(substr($lastSubcategoryRecord->code_subkategori, 2)) : 0;
                $newSubcategoryCode = 'SK' . ($lastSubcategoryId + 1);

                // Memasukkan data baru ke dalam tabel FaqSubcategories
                FaqSubcategories::create([
                    'code_kategori' => $request->input('idCat'),
                    'code_subkategori' => $newSubcategoryCode,
                    'nama_subkategori' => strtoupper($data['nama_subkategori']),
                    // 'pic_id' => $data['pic_id'],
                ]);

                // Memasukkan data baru ke dalam tabel Answer
                Answer::create([
                    'code_subkategori' => $newSubcategoryCode,
                    'answer_text' => strtoupper($data['answer_text']),
                ]);

                Pic::create([
                    'code_subkategori' => $newSubcategoryCode,
                    'users_id' => (int) $data['pic_id'],
                ]);
            }
            // Jika berhasil, kembalikan respons sukses
            return response()->json([
                'code' => 200,
                'status' => true,
                'message' => 'Data successfully added.',
            ], 200);
        } catch (\Throwable $th) {
            // Jika terjadi kesalahan, kembalikan respons error server
            return response()->json([
                'code' => 500,
                'status' => false,
                // 'message' => 'Internal Server Error.',
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function deleteData(Request $request, $id): JsonResponse
    {
        try {
            // Temukan record FaqSubcategories berdasarkan id
            $faqSubCategory = FaqSubcategories::find($id);

            if (!$faqSubCategory) {
                return response()->json([
                    'message' => 'Data tidak ditemukan',
                    'code' => 404,
                    'status' => false,
                ], 404);
            }

            // Hapus jawaban terkait berdasarkan code_subkategori
            Answer::where('code_subkategori', $faqSubCategory->code_subkategori)->delete();

            // Hapus data PIC terkait berdasarkan code_subkategori
            Pic::where('code_subkategori', $faqSubCategory->code_subkategori)->delete();

            // Hapus subkategori
            $faqSubCategory->delete();

            return response()->json([
                'code' => 200,
                'status' => true,
                'message' => 'Data berhasil dihapus',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'status' => false,
                'message' => 'Gagal menghapus data',
            ], 500);
        }
    }

    public function deleteCat(Request $request, $id)
    {
        try {
            $faqCategory = FaqCategories::where('id', $id)->with('subcategories.answers')->first();

            if (!$faqCategory) {
                return response()->json([
                    'message' => 'Data not found',
                    'code' => 404,
                    'status' => false,
                ], 404);
            }

            // Hapus jawaban terkait
            foreach ($faqCategory->subcategories as $subcategory) {
                $subcategory->answers()->delete();
            }

            // Hapus subkategori terkait
            $faqCategory->subcategories()->delete();

            // Hapus kategori
            $faqCategory->delete();

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

    public function editData(Request $request): JsonResponse
    {
        try {

            // Dapatkan subkategori berdasarkan ID
            $subcategory = FaqSubcategories::findOrFail($request->input('id'));

            // Update data subkategori
            $subcategory->update([
                'nama_subkategori' => strtoupper($request->input('nama_subkategori')),
            ]);

            // Update data jawaban terkait subkategori
            $answer = Answer::where('code_subkategori', $subcategory->code_subkategori)->firstOrFail();
            $answer->update([
                'answer_text' => strtoupper($request->input('answer_text')),
            ]);

            // Update data PIC terkait subkategori
            $pic = Pic::where('code_subkategori', $subcategory->code_subkategori)->firstOrFail();
            $pic->update([
                'users_id' => (int) $request->input('users_id'),
            ]);

            // Jika berhasil, kembalikan respons sukses
            return response()->json([
                'code' => 200,
                'status' => true,
                'message' => 'Data successfully updated.',
            ], 200);
        } catch (\Throwable $th) {
            // Jika terjadi kesalahan, kembalikan respons error server
            return response()->json([
                'code' => 500,
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function editCat(Request $request, $id): JsonResponse
    {
        try {
            // Mendapatkan data kategori FAQ berdasarkan ID
            $faqCategory = FaqCategories::where('id', $id)->first();

            // Memperbarui data kategori FAQ
            $faqCategory->update([
                'nama_kategori' =>  strtoupper($request->input('nama_kategori')),
            ]);

            // Mengembalikan respons JSON
            return response()->json([
                'data' => $faqCategory,
                'code' => 200,
                'status' => true,
                'message' => 'Category updated successfully'
            ], 200);
        } catch (\Throwable $th) {
            // Mengembalikan respons JSON dalam kasus terjadi kesalahan
            return response()->json([
                'code' => 500,
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function uploadExcel(Request $request)
    {
        try {
            $reqData = $request->all();

            $rowExcel = json_decode(json_encode($reqData["excelRows"]));

            $listRowSubFAQ = [];
            $listRowAnswer = [];
            $listRowPic = [];
            $lastSubcategoryRecord = FaqSubcategories::withTrashed()
                ->orderBy('id', 'desc')
                ->first();
            $lastSubcategoryId = $lastSubcategoryRecord ? intval(substr($lastSubcategoryRecord->code_subkategori, 2)) : 0;

            DB::beginTransaction();

            foreach ($rowExcel as $row) {
                $lastSubcategoryId++;
                $newSubcategoryCode = 'SK' . $lastSubcategoryId;
                $listRowSubFAQ[] = [
                    'code_kategori' => $row[0],
                    'code_subkategori' => $newSubcategoryCode,
                    'nama_subkategori' => strtoupper($row[2]),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];

                $listRowAnswer[] = [
                    'code_subkategori' => $newSubcategoryCode,
                    'answer_text' => strtoupper($row[3]),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()

                ];

                $listRowPic[] = [
                    'code_subkategori' => $newSubcategoryCode,
                    'users_id' => (int) $row[1],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];
            }

            if (count($listRowSubFAQ) > 0) FaqSubcategories::insert($listRowSubFAQ);
            if (count($listRowAnswer) > 0) Answer::insert($listRowAnswer);
            if (count($listRowPic) > 0) Pic::insert($listRowPic);

            // Lakukan proses yang sesuai dengan data yang diterima dari Vue.js
            // Misalnya, Anda dapat menyimpannya ke dalam database atau melakukan operasi lainnya.

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Data Excel berhasil diterima dan diproses.',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $e,
            ], 400);
        }
    }
}
