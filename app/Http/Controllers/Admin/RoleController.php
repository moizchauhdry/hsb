<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::orderBy('id', 'desc')->paginate(10)
        ->withQueryString()
        ->through(fn ($role) => [
            'id' => $role->id,
            'name' => $role->name,
            'created_at' => $role->created_at->format('d-m-Y h:i A'),
            'updated_at' => $role->updated_at->format('d-m-Y h:i A'),
        ]);

        return Inertia::render('Role/Index', [
            'roles' => $roles,
        ]);
    }
}
