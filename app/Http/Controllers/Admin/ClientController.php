<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BusinessClass;
use App\Models\ClientGroup;
use App\Models\User;
use App\Models\UserClient;
use App\Models\UserCob;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        // dd($request->all());

        $role = Auth::user()->roles[0];

        $page_count = $request->page_count ?? 10;
        
        $policy_type = [];
        if ($request->policy_type) {
            $policy_type = is_array($request->policy_type) ? $request->policy_type : explode(',', $request->policy_type);
        }

        $client_ids = [];
        if ($request->client_ids) {
            $client_ids = is_array($request->client_ids) ? $request->client_ids : explode(',', $request->client_ids);
        }
        
        $agency = [];
        if ($request->agency) {
            $agency = is_array($request->agency) ? $request->agency : explode(',', $request->agency);
        }
        
        $insurer = [];
        if ($request->insurer) {
            $insurer = is_array($request->insurer) ? $request->insurer : explode(',', $request->insurer);
        }
        
        $department = [];
        if ($request->department) {
            $department = is_array($request->department) ? $request->department : explode(',', $request->department);
        }

        $group = [];
        if ($request->group) {
            $group = is_array($request->group) ? $request->group : explode(',', $request->group);
        }

        $cob = [];
        if ($request->cob) {
            $cob = is_array($request->cob) ? $request->cob : explode(',', $request->cob);
        }

        $filter = [
            'search' => $request->search ?? "",
            'date_type' => $request->date_type ?? "",
            'from_date' => $request->from_date ?? "",
            'to_date' => $request->to_date ?? "",
            'policy_type' => $policy_type,
            'client_ids' => $client_ids,
            'agency' => $agency,
            'insurer' => $insurer,
            'department' => $department,
            'group' => $group,
            'cob' => $cob,
        ];

        $query = User::query()
            ->select([
                'users.id as user_id',
                'users.name as user_name',
                'users.code as user_code',
                'users.created_at as user_created_at',
                DB::raw('COUNT(DISTINCT p.id) as policy_count'),
                // DB::raw("COUNT(DISTINCT CASE WHEN p.policy_type IN ('new', 'renewal', 'cover') THEN p.id ELSE NULL END) as policy_count"),
                DB::raw('COUNT(DISTINCT policy_claims.id) as policy_claim_count'),
                DB::raw('GROUP_CONCAT(DISTINCT cob.class_name SEPARATOR ", ") as cobs'),
                DB::raw('GROUP_CONCAT(DISTINCT insurances.name SEPARATOR ", ") as insurers'),
            ])
            ->leftJoin('policies as p', 'users.id', '=', 'p.client_id')
            ->leftJoin('policy_claims', 'policy_claims.policy_id', '=', 'p.id')
            ->leftJoin('business_classes as cob', 'p.cob_id', '=', 'cob.id')
            ->leftJoin('insurances', 'p.insurer_id', '=', 'insurances.id')
            ->role('client')
            ->whereIn('p.policy_type', ['new', 'renewal', 'cover'])
            ->groupBy('users.id', 'users.name', 'users.email', 'users.code', 'users.created_at')
            ->orderBy('policy_count', 'desc');

        if ($role->id != 1) {
            $query->leftJoin('user_cobs as uc', function ($join) {
                $join->on('uc.cob_id', '=', 'p.cob_id')->where('uc.user_id', auth()->id());
            })
                ->leftJoin('user_clients as ucl', function ($join) {
                    $join->on('ucl.client_id', '=', 'p.client_id')->where('ucl.user_id', auth()->id());
                })
                ->where(function ($q) {
                    $q->whereNotNull('uc.id')->orWhereNotNull('ucl.id');
                });
        }

        if ($filter) {

            $query->when($filter['search'], function ($q) use ($filter) {
                $q->where('users.code', $filter['search'])
                    ->orWhere('users.name', 'LIKE', '%' . $filter['search'] . '%')
                    ->orWhere('users.email', 'LIKE', '%' . $filter['search'] . '%');
            });

            $query->when($filter['date_type'], function ($q) use ($filter) {
                if ($filter['from_date']) {
                    $q->where('p.' . $filter['date_type'], ">=", $filter['from_date']);
                }
                if ($filter['to_date']) {
                    $q->where('p.' . $filter['date_type'], "<=", $filter['to_date']);
                }
            });

            $query->when($filter['policy_type'], function ($q) use ($filter) {
                $q->whereIn('p.policy_type', $filter['policy_type']);
            });

            $query->when(!empty($filter['client_ids']), function ($q) use ($filter) {
                $q->whereIn('p.client_id', $filter['client_ids']);
            });

            $query->when(!empty($filter['agency']), function ($q) use ($filter) {
                $q->whereIn('p.agency_id', $filter['agency']);
            });

            $query->when(!empty($filter['insurer']), function ($q) use ($filter) {
                $q->whereIn('p.insurer_id', $filter['insurer']);
            });

            $query->when($filter['cob'], function ($q) use ($filter) {
                $q->whereIn('p.cob_id', $filter['cob']);
            });

            $query->when($filter['department'], function ($q) use ($filter) {
                $q->whereIn('cob.department_id', $filter['department']);
            });

            $query->when($filter['group'], function ($q) use ($filter) {
                $q->whereIn('cob.group_id', $filter['group']);
            });

            // $query->when($filter['client_group_code'] !== null, function ($q) use ($filter) {
            //     if ($filter['client_group_code'] === 0 || $filter['client_group_code'] === '0') {
            //         $q->where('users.client_group_code', 0);
            //     } else {
            //         $client_groups = is_array($filter['client_group_code'])
            //             ? $filter['client_group_code']
            //             : explode(',', $filter['client_group_code']);
            //         $q->whereIn('users.client_group_code', $client_groups);
            //     }
            // });
        }

        $users = $query->paginate($page_count)->withQueryString();

        $roles = Role::select('id', 'name')->get();

        // dd($filter);

        return Inertia::render('Client/Index', [
            'users' => $users,
            'roles' => $roles,
            'filters' => $filter,
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

    public function groupIndex(Request $request)
    {        
        $page_count = $request->page_count ?? 10;
        
        $policy_type = [];
        if ($request->policy_type) {
            $policy_type = is_array($request->policy_type) ? $request->policy_type : explode(',', $request->policy_type);
        }

        $client_ids = [];
        if ($request->client_ids) {
            $client_ids = is_array($request->client_ids) ? $request->client_ids : explode(',', $request->client_ids);
        }
        
        $agency = [];
        if ($request->agency) {
            $agency = is_array($request->agency) ? $request->agency : explode(',', $request->agency);
        }
        
        $insurer = [];
        if ($request->insurer) {
            $insurer = is_array($request->insurer) ? $request->insurer : explode(',', $request->insurer);
        }
        
        $department = [];
        if ($request->department) {
            $department = is_array($request->department) ? $request->department : explode(',', $request->department);
        }

        $group = [];
        if ($request->group) {
            $group = is_array($request->group) ? $request->group : explode(',', $request->group);
        }

        $cob = [];
        if ($request->cob) {
            $cob = is_array($request->cob) ? $request->cob : explode(',', $request->cob);
        }

        $filter = [
            'search' => $request->search ?? "",
            'date_type' => $request->date_type ?? "",
            'from_date' => $request->from_date ?? "",
            'to_date' => $request->to_date ?? "",
            'policy_type' => $policy_type,
            'client_ids' => $client_ids,
            'agency' => $agency,
            'insurer' => $insurer,
            'department' => $department,
            'group' => $group,
            'cob' => $cob,
        ];
        
        $groups = ClientGroup::clientGroupList($filter)
            ->paginate($page_count)
            ->withQueryString();

        return Inertia::render('Client/Group', [
            'page_type' => "policies",
            'groups' => $groups,
            'filters' => $filter,
        ]);
    }
}
