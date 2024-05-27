<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

use App\Models\MasterRole;

class MasterRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tableName = (new MasterRole)->getTable();
        if (Schema::hasTable($tableName)) {
            $rowCount = MasterRole::count();
            if ($rowCount > 0) {
                MasterRole::truncate();
            }
            $sequence = $tableName."_id_seq";
            DB::statement("ALTER SEQUENCE $sequence RESTART WITH 1");
        }
        
        $schame = [
            [
                'code_role' => 'R1',
                'nama_role' => 'KLIEN',

                'created_by' => 'Seeder',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'updated_by' => null,
                'deleted_at' => null,
                'deleted_by' => null,
            ],
            
            [
                'code_role' => 'R2',
                'nama_role' => 'KARYAWAN',
                
                'created_by' => 'Seeder',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'updated_by' => null,
                'deleted_at' => null,
                'deleted_by' => null,
            ],
        ];

        MasterRole::insert($schame);
    }
}
