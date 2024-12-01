<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessClass;
use App\Models\User;
use App\Models\UserClient;
use App\Models\UserCob;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request, $slug)
    {
        $filter = [
            'search' => $request->search,
            'page_count' => $request->page_count,
        ];

        $users = User::query()
            ->when($slug == 'users', function ($q) {
                $q->withoutRole('client');
            })
            ->when($slug == 'clients', function ($q) {
                $q->role('client');
            })
            ->orderBy('id', 'desc')
            ->when($filter['search'], function ($q) use ($filter) {
                $q->where('code', $filter['search']);
                $q->orWhere('name', 'LIKE', '%' . $filter['search'] . '%');
                $q->orWhere('email', 'LIKE', '%' . $filter['search'] . '%');
            })
            ->paginate($filter['page_count'])
            ->withQueryString()
            ->through(fn($user) => [
                'id' => $user->id,
                'code' => $user->code,
                'name' => $user->name,
                'email' => $user->email,
               
                'type'  =>  $user->type,
                'phone' => $user->phone,
                
                'role' => $user->roles[0]->name ?? '-',
                'role_id' => $user->roles[0]->id ?? NULL,
                'created_at' => $user->created_at->format('d-m-Y h:i A'),

                'user_cobs_count' => $user->cobs->count(),
                'total_cob_count' => BusinessClass::count(),

                'user_clients_count' => $user->clients->count(),
                'total_client_count' => User::role('client')->count(),
            ]);

        $roles = Role::select('id', 'name')->get();

        return Inertia::render('User/Index', [
            'users' => $users,
            'roles' => $roles,
            'slug' => $slug,
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
        // dd($request->all());

        $user = User::find($request->user_id);
        UserClient::where('user_id', $user->id)->delete();

        if ($request->client_id) {
            foreach ($request->client_id as $key => $cob) {
                dd($cob);
                UserClient::create([
                    'user_id' => $user->id,
                    'client_id' => $cob,
                ]);
            }
        }
    }
}
