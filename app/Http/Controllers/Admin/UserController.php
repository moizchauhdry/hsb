<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

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
            'role' => $user->roles[0]->name,
            'created_at' => $user->created_at->format('d-m-Y h:i A'),
        ]);

        return Inertia::render('User/Index', [
            'users' => $users,
        ]);
    }
}
