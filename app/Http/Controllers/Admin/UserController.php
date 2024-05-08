<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => ['required', 'string', 'min:5', 'max:50'],
            'email' => ['required', 'string', 'email', 'max:50', 'unique:users'],
            // 'phone' => ['required', 'unique:users', 'max:50'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required'],
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            // 'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ];

        $user = User::create($data);
        $user->assignRole($validate['role']);
    }

    public function update(Request $request)
    {
        // dd($request->all());
        $user = User::findOrFail($request->user_id);

        $validate = $request->validate([
            'user_id' => ['required'],
            'name' => ['required', 'string', 'min:5', 'max:50'],
            'email' => ['required', 'string', 'email', 'max:50'],
            'role' => ['required'],
        ]);

        // dd($user);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            // 'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ];

        $user->update($data);
        $user->assignRole($validate['role']);
    }
}
