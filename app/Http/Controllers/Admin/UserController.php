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
    public function index()
    {
        $users = User::orderBy('id', 'desc')->paginate(10)
            ->withQueryString()
            ->through(fn ($user) => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'address' => $user->address,
                'cnic_no' => $user->cnic_no,
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
        $validate = $request->validate([
            'name' => ['required', 'string', 'min:5', 'max:50'],
            'email' => ['required', 'string', 'email', 'max:50', 'unique:users'],
            'phone' => ['required', 'unique:users', 'max:50'],
            'address' => ['required', 'string', 'min:5', 'max:100'],
            'cnic_no' => ['required'],
            'designation' => ['required'],
            'qualification' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required'],
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'cnic_no'  =>  $request->cnic_no,
            'designation'  =>  $request->designation,
            'qualification'   => $request->qualification,
            'password' => Hash::make($request->password),
        ];

        $user = User::create($data);
        $user->assignRole($validate['role']);
    }

    public function update(Request $request)
    {        
        $user = User::findOrFail($request->user_id);

        $validate = $request->validate([
            'user_id' => ['required'],
            'name' => ['required', 'string', 'min:5', 'max:50'],
            'email' => ['required', 'string', 'email', 'max:50'],
            'address' => ['required', 'string', 'min:5', 'max:100'],
            'phone' => ['required'],
            'cnic_no' => ['required'],
            'designation' => ['required'],
            'qualification' => ['required'],
            'role' => ['required'],
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'cnic_no'  =>  $request->cnic_no,
            'designation'  =>  $request->designation,
            'qualification'   => $request->qualification,
            'password' => Hash::make($request->password),
        ];

        $user->update($data);
        $user->assignRole($validate['role']);
    }
}
