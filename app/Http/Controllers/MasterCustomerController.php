<?php

namespace App\Http\Controllers;

// get master of controller
use App\Http\Controllers\Controller;

// get models
use App\Models\Master\{MasterCustomer, MasterCutoff, MasterWorkhour};
use App\Models\User\{User, UserRoles};
use App\Models\Transaction\{Profiles, Placement, Pic, PlacementDepartemen};

// get library of process
use Request;
use Exception;
use Crypt;

class MasterCustomerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function show()
    {
        try {
            // get data master customer join users
            $data = MasterCustomer::select('mst_customer.id', 'mst_customer.customer_code', 'mst_customer.customer_name', 'mst_customer.customer_address', 'mst_customer.status', 'mst_customer.customer_pos_code', 'mst_customer.customer_latitude', 'mst_customer.customer_longitude', 'mst_cutoff.cutoff_start', 'mst_cutoff.cutoff_end', 'mst_customer.prefix')
                ->leftJoin('mst_cutoff', 'mst_cutoff.customer_code', '=', 'mst_customer.customer_code')
                ->orderBy('mst_customer.customer_name', 'asc')
                ->where('mst_customer.deleted_at', null)->get();

            foreach ($data as $key => $value) {
                $value->pic = Pic::select('users.id', 'profiles.profile_name', 'profiles.profile_address', 'users.email')
                    ->leftJoin('users', 'users.id', '=', 'pic_list.users_id')
                    ->leftJoin('profiles', 'profiles.users_id', '=', 'pic_list.users_id')
                    ->where('customer_code', $value->customer_code)
                    ->get();
            }

            if (count($data) > 0) {
                $this->return = array_merge((array)$this->return, [
                    'data' => $data
                ]);
            } else {
                throw new Exception("Data Kosong");
            }
        } catch (\Throwable $err) {
            $this->return = array_merge((array)$this->return, [
                'status'    => false,
                'message'   => $err->getMessage(),
                'code'      => 200
            ]);
        }
        return response()->json($this->return, $this->return['code']);
    }

    public function getByCustomerCode($customer_code)
    {
        try {
            // get data master customer by id
            $data = MasterCustomer::where('customer_code', $customer_code)->first();

            if (count($data) > 0) {
                $this->return = array_merge((array)$this->return, [
                    'data' => $data
                ]);
            } else {
                throw new Exception("Data Kosong");
            }
        } catch (\Throwable $err) {
            $this->return = array_merge((array)$this->return, [
                'status'    => false,
                'message'   => $err->getMessage(),
                'code'      => 200
            ]);
        }
        return response()->json($this->return, $this->return['code']);
    }

    public function add(Request $r)
    {
        // Validation code exists
        if ((new MasterCustomer)->isExists($r)) {
            $this->return = array_merge((array)$this->return, [
                'status' => false,
                'message' => 'Kode atau nama telah terdaftar',
                'code' => 401
            ]);

            return response()->json($this->return, $this->return['code']);
        }

        $newCustomer = [
            'customer_code' => $r->customer_code,
            'customer_name' => $r->customer_name,
            'customer_address' => $r->customer_address,
            'customer_pos_code' => $r->customer_pos_code,
            'customer_latitude' => $r->customer_latitude,
            'customer_longitude' => $r->customer_longitude,
            'status'    => $r->status,
            'prefix'    => $r->prefix,
            'created_by' => $r->created_by,
        ];

        if (!($id = (new MasterCustomer)->add($newCustomer))) {
            // if not inserted
            $this->return = array_merge((array)$this->return, [
                'status'    => false,
                'message'   => 'failed insert add new customer',
                'code'      => 400
            ]);
            // throw new Exception("Gagal Menyimpan Data");
        } else {
            if ($r->cutoff_start != null && $r->cutoff_end != null) {
                $cutoff = [
                    'customer_code' => $r->customer_code,
                    'cutoff_start' => $r->cutoff_start,
                    'cutoff_end' => $r->cutoff_end,
                    'created_by' => $r->created_by
                ];

                $save = (new MasterCutoff)->add($cutoff);
            }


            if (count($r->pic) > 0) {
                foreach ($r->pic as $key => $value) {
                    $token = md5($this->curTimestamp);
                    array_push(
                        $value,
                        $value['pwd_hash'] = Crypt::encrypt(substr($token, 0, 8)),
                        $value['verified_token'] = md5($token),
                        $value['created_by'] = $r->created_by,
                        $value['customer_code'] = $r->customer_code
                    );

                    $id = (new User)->add([
                        'email' => $value['email'],
                        'username' => $value['username'],
                        'pwd_hash' => $value['pwd_hash'],
                        'verified_token' => $value['verified_token'],
                        'created_by' => $value['created_by'],
                        'device_id' => '',
                        'status'    => 0,
                    ]);


                    if ($id) {
                        $pic = [
                            'users_id' => $id,
                            'customer_code' => $value['customer_code'],
                            'status' => 1,
                            'created_by' => $value['created_by']
                        ];
                        $save = (new Pic)->add($pic);
                    }
                    // check profile post
                    if ($value['nik'] != null) {
                        $profile = array(
                            'users_id' => $id,
                            'nik' => $value['nik'],
                            'profile_name' => $value['profile_name'],
                            'profile_gender' => '',
                            'profile_address' => $value['profile_address'],
                            'profile_phone' => $value['profile_phone'],
                            'profile_phone2' => '',
                            'remaining_leave' => 0,
                            'created_by' => $value['created_by']
                        );
                        $save = (new Profiles)->add($profile);
                    }

                    // check placement customer post
                    if ($value['customer_code'] != null) {
                        $customer = array(
                            'users_id' => $id,
                            'customer_code' => $value['customer_code'],
                            'created_by' => $value['created_by']
                        );
                        $save = (new Placement)->add($customer);
                    }
                    // check placement departemen code
                    // if ($value['departemen_code'] != null) {
                    //     $placement_departemen = array(
                    //         'users_id' => $id,
                    //         'departemen_code' => $value['departemen_code'],
                    //         'created_by' => $value['created_by']
                    //     );
                    //     $save = (new PlacementDepartemen)->add($placement_departemen);
                    // }

                    // check role access post
                    if ($value['nik'] != null) {
                        // if not exists
                        $userRole = array(
                            'users_id' => $id,
                            'mst_roles_id' => 4,
                            'created_by' => $value['created_by']
                        );
                        $save = (new UserRoles)->add($userRole);
                    }
                }
            }
            // if success
            $this->return = array_merge((array)$this->return, [
                'data' => $id
            ]);
        }

        return response()->json($this->return, $this->return['code']);
    }

    public function edit(Request $r)
    {
        // validation input
        $this->validate($r, [
            'id' => 'required',
            'customer_code' => 'required',
            'customer_name' => 'required',
            'customer_address' => 'required',
            'updated_by' => 'required',
        ]);

        try {
            $update = [
                'customer_code' => $r->customer_code,
                'customer_name' => $r->customer_name,
                'customer_address' => $r->customer_address,
                'customer_pos_code' => $r->customer_pos_code,
                'customer_latitude' => $r->customer_latitude,
                'customer_longitude' => $r->customer_longitude,
                'prefix' => $r->prefix,
                'status' => $r->status,
                'updated_by' => $r->updated_by
            ];
            if (!($sql = (new MasterCustomer)->edit($update, $r->id))) {
                // if failed
                throw new Exception("Gagal Menyimpan Data");
            } else {
                if ($r->has('cutoff_start')) {
                    (new MasterCutoff)->edit([
                        'cutoff_start' => $r->cutoff_start,
                        'cutoff_end' => $r->cutoff_end,
                    ], $r->id);
                }
                // if success
                $this->return = array_merge((array)$this->return, [
                    'data' => $sql->id
                ]);
            }
        } catch (\Throwable $err) {
            $this->return = array_merge((array)$this->return, [
                'status' => false,
                'message' => $err->getMessage(),
                'code' => 401
            ]);
        }

        return response()->json($this->return, $this->return['code']);
    }

    public function delete(Request $r)
    {
        // proses soft delete
        if (!($sql = (new MasterCustomer)->del($r->id))) {
            // if failed
            $this->return = array_merge((array)$this->return, [
                'status' => false,
                'message' => 'Gagal menghapus data',
                'code' => 401
            ]);
        } else {
            // if success
            return response()->json([
                'status' => true,
                'message' => 'Berhasil!',
                'data'  => [],
                'code'  => 200
            ], 200);
        }
    }

    public function getCode()
    {
        $kode =  '001';

        $count = MasterCustomer::count();

        if ($count > 0) {
            $kode = str_pad($kode + $count, 3, '0', STR_PAD_LEFT);
            return 'MC' . $kode;
        } else {
            return 'MC' . $kode;
        }
    }
}