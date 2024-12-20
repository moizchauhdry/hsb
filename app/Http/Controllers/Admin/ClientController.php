<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BusinessClass;
use App\Models\User;
use App\Models\UserClient;
use App\Models\UserCob;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        try {
            if (is_array($request->date_value)) {
                $from_date = isset($request->date_value[0]) ? Carbon::parse($request->date_value[0])->format("Y-m-d") : NULL;
                $to_date = isset($request->date_value[1]) ? Carbon::parse($request->date_value[1])->format("Y-m-d") : NULL;
            } else {
                $date_value = explode(',', $request->date_value);
                $from_date = isset($date_value[0]) ? Carbon::parse($date_value[0])->format("Y-m-d") : NULL;
                $to_date = isset($date_value[1]) ? Carbon::parse($date_value[1])->format("Y-m-d") : NULL;
            }
        } catch (\Throwable $th) {
            //throw $th;
        }

        $filter = [
            'search' => $request->search ?? "",
            'page_count' => $request->page_count ?? "",

            'date_type' => $request->date_type ?? "",
            'from_date' => $from_date ?? "",
            'to_date' => $to_date ?? "",

            'policy_type' => $request->policy_type ?? "",
            'client' => $request->client ?? "",
            'agency' => $request->agency ?? "",
            'insurer' => $request->insurer ?? "",
            'department' => $request->department ?? "",
            'group' => $request->group ?? "",
            'cob' => $request->cob ?? "",
        ];

        $query = User::query()
            ->select([
                'users.id as user_id',
                'users.name as user_name',
                'users.code as user_code',
                'users.created_at as user_created_at',
                DB::raw('COUNT(DISTINCT p.id) as policy_count'),
                DB::raw('COUNT(DISTINCT policy_claims.id) as policy_claim_count'),
                DB::raw('GROUP_CONCAT(DISTINCT business_classes.class_name SEPARATOR ", ") as cobs'),
                DB::raw('GROUP_CONCAT(DISTINCT insurances.name SEPARATOR ", ") as insurers'),
            ])
            ->leftJoin('policies as p', 'users.id', '=', 'p.client_id')
            ->leftJoin('policy_claims', 'policy_claims.policy_id', '=', 'p.id')
            ->leftJoin('business_classes', 'p.cob_id', '=', 'business_classes.id')
            ->leftJoin('insurances', 'p.insurer_id', '=', 'insurances.id')
            ->role('client')
            ->groupBy('users.id', 'users.name', 'users.email', 'users.code', 'users.created_at')
            ->orderBy('users.id', 'desc');

        if ($filter) {
            $query->when($filter['search'], function ($q) use ($filter) {
                $q->where('users.code', $filter['search'])
                    ->orWhere('users.name', 'LIKE', '%' . $filter['search'] . '%')
                    ->orWhere('users.email', 'LIKE', '%' . $filter['search'] . '%');
            });

            $query->when(!empty($filter['date_type']) && !empty($filter['from_date']) && !empty($filter['to_date']), function ($q) use ($filter) {
                $q->where('p.' . $filter['date_type'], ">=", $filter['from_date']);
                $q->where('p.' . $filter['date_type'], "<=", $filter['to_date']);
            });

            $query->when($filter['policy_type'], function ($q) use ($filter) {
                $types = is_array($filter['policy_type']) ? $filter['policy_type'] : explode(',', $filter['policy_type']);
                $q->whereIn('p.policy_type', $types);
            });

            $query->when(!empty($filter['client']), function ($q) use ($filter) {
                $clients = is_array($filter['client']) ? $filter['client'] : explode(',', $filter['client']);
                $q->whereIn('p.client_id', $clients);
            });

            $query->when(!empty($filter['agency']), function ($q) use ($filter) {
                $agencies = is_array($filter['agency']) ? $filter['agency'] : explode(',', $filter['agency']);
                $q->whereIn('p.agency_id', $agencies);
            });

            $query->when(!empty($filter['insurer']), function ($q) use ($filter) {
                $insurers = is_array($filter['insurer']) ? $filter['insurer'] : explode(',', $filter['insurer']);
                $q->whereIn('p.insurer_id', $insurers);
            });

            $query->when($filter['cob'], function ($q) use ($filter) {
                $cobs = is_array($filter['cob']) ? $filter['cob'] : explode(',', $filter['cob']);
                $q->whereIn('p.cob_id', $cobs);
            });
        }

        $users = $query->paginate($filter['page_count'])
            ->withQueryString();

        $roles = Role::select('id', 'name')->get();

        return Inertia::render('Client/Index', [
            'users' => $users,
            'roles' => $roles,
            'filter' => $filter,
        ]);
    }

    private function save($request, $edit_mode)
    {
        $rules = [
            'name' => ['required', 'string', 'min:3', 'max:50'],
            // 'email' => ['nullable', 'string', 'email', 'max:50'],
            'phone' => ['nullable', 'max:50'],
            'address' => ['nullable', 'string', 'min:3', 'max:100'],
            'cnic_no' => ['nullable'],
            'designation' => ['nullable'],
            'qualification' => ['nullable'],
            'role' => ['required'],
            'type' => ['required'],
            'cob_id' => ['nullable'],
        ];

        if ($request->role != 2) {
            if ($edit_mode) {
                $rules += [
                    'email' => [
                        'required',
                        Rule::unique('users', 'email')->ignore($request->user_id),
                    ],
                ];
            } else {
                $rules += [
                    'password' => ['required', 'string', 'min:8', 'confirmed'],
                    'email' => ['required', 'email', 'unique:users,email'],
                ];
            }
        }

        $validate = $request->validate($rules);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'cnic_name'  =>  $request->cnic_name,
            'cnic_no'  =>  $request->cnic_no,
            'cnic_expiry_date'  =>  $request->cnic_expiry_date,
            'father_name'  =>  $request->father_name,
            'gender'  =>  $request->gender,
            'dob'  =>  $request->dob,
            'type'  =>  $request->type,
            'designation'  =>  $request->designation,
            'qualification'   => $request->qualification,
        ];

        if ($edit_mode) {
            $user = User::find($request->user_id);
            $user->update($data);
            $user->syncRoles($validate['role']);
        } else {
            if ($request->role == 2) {
                $data += [
                    'password' => Hash::make(0),
                ];
            } else {
                $data += [
                    'password' => Hash::make($request->password),
                ];
            }

            $user = User::create($data);
            $user->assignRole($validate['role']);
        }

        if ($request->cob_id) {
            UserCob::where('user_id', $user->id)->delete();
            foreach ($request->cob_id as $key => $cob) {
                UserCob::create([
                    'user_id' => $user->id,
                    'cob_id' => $cob,
                ]);
            }
        }
    }

    public function create(Request $request)
    {
        $this->save($request, false);
    }

    public function edit($id)
    {
        $user = User::find($id);

        $selected_cobs = UserCob::query()
            ->from('user_cobs as uc')
            ->select('cob.id as cob_id')
            ->join('business_classes as cob', 'cob.id', 'uc.cob_id')
            ->where('uc.user_id', $id)
            ->get()->pluck('cob_id');

        $data = [
            'user' => $user,
            'role_id' => $user->roles[0]->id,
            'selected_cobs' => $selected_cobs,
        ];

        return response()->json($data);
    }

    public function update(Request $request)
    {
        $this->save($request, true);
    }

    public function assignCob(Request $request)
    {
        // dd($request->all());

        $user = User::find($request->user_id);
        UserCob::where('user_id', $user->id)->delete();

        if ($request->cob_id) {
            foreach ($request->cob_id as $key => $cob) {
                UserCob::create([
                    'user_id' => $user->id,
                    'cob_id' => $cob,
                ]);
            }
        }
    }

    public function selectedCob($id)
    {
        $items = UserCob::query()
            ->where('user_id', $id)
            ->pluck('cob_id')->toArray();

        $data = [
            'items' => $items,
        ];

        return response()->json($data);
    }

    public function selectedClient($id)
    {
        $items = UserClient::query()
            ->where('user_id', $id)
            ->pluck('client_id')->toArray();

        $data = [
            'items' => $items,
        ];

        return response()->json($data);
    }

    public function assignClient(Request $request)
    {
        $user = User::find($request->user_id);
        UserClient::where('user_id', $user->id)->delete();

        if ($request->client_id) {
            foreach ($request->client_id as $key => $client) {
                UserClient::create([
                    'user_id' => $user->id,
                    'client_id' => $client,
                ]);
            }
        }
    }
}
