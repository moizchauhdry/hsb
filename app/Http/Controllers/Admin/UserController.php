<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $filter = [
            'search' => $request->search,
        ];

        $users = User::orderBy('id', 'desc')
            ->when($filter['search'], function ($q) use ($filter) {
                $q->where('id', $filter['search']);
                $q->orWhere('name', 'LIKE', '%' . $filter['search'] . '%');
            })
            ->paginate(10)
            ->withQueryString()
            ->through(fn ($user) => [
                'id' => $user->id,
                'code' => $user->code,
                'name' => $user->name,
                'email' => $user->email,
                'address' => $user->address,
                'cnic_name'  =>  $user->cnic_name,
                'cnic_no'  =>  $user->cnic_no,
                'cnic_expiry_date'  =>  $user->cnic_expiry_date,
                'father_name'  =>  $user->father_name,
                'gender'  =>  $user->gender,
                'dob'  =>  $user->dob,
                'type'  =>  $user->type,
                'phone' => $user->phone,
                'designation' => $user->designation,
                'qualification' => $user->qualification,
                'role' => $user->roles[0]->name ?? '-',
                'role_id' => $user->roles[0]->id ?? NULL,
                'created_at' => $user->created_at->format('d-m-Y h:i A'),
            ]);

        $roles = Role::select('id', 'name')->get();

        return Inertia::render('User/Index', [
            'users' => $users,
            'roles' => $roles,
        ]);
    }

    public function create(Request $request)
    {
        $rules = [
            'name' => ['required', 'string', 'min:3', 'max:50'],
            'email' => ['nullable', 'string', 'email', 'max:50', 'unique:users'],
            'phone' => ['nullable', 'unique:users', 'max:50'],
            'address' => ['nullable', 'string', 'min:3', 'max:100'],
            'cnic_no' => ['nullable'],
            'designation' => ['nullable'],
            'qualification' => ['nullable'],
            'role' => ['required'],
        ];

        if ($request->role == 1) {
            $rules += [
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'email' => ['required'],
            ];
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
            // 'role_users_id'  =>  $validate['role'],
            'qualification'   => $request->qualification,
        ];

        if ($request->role != 2) {
            $data += [
                'password' => Hash::make($request->password),
            ];
        } else {
            $data += [
                'password' => Hash::make(0),
            ];
        }

        $user = User::create($data);
        $user->assignRole($validate['role']);
    }

    public function update(Request $request)
    {
        $user = User::findOrFail($request->user_id);

        $validate = $request->validate([
            'user_id' => ['required'],
            'name' => ['required', 'string', 'min:3', 'max:50'],
            'email' => ['nullable', 'string', 'email', 'max:50'],
            'address' => ['nullable', 'string', 'min:3', 'max:100'],
            'phone' => ['nullable'],
            'cnic_no' => ['nullable'],
            'designation' => ['nullable'],
            'qualification' => ['nullable'],
            'role' => ['required'],
        ]);

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
            // 'role_users_id'  =>  $validate['role'],
            'designation'  =>  $request->designation,
            'qualification'   => $request->qualification,
            'password' => Hash::make($request->password),
        ];

        $user->update($data);
        $user->syncRoles($validate['role']);
    }
}
