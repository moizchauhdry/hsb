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
                'permissions' => $role->permissions->pluck('id')->toArray(),
                'created_at' => $role->created_at->format('d-m-Y h:i A'),
                'updated_at' => $role->updated_at->format('d-m-Y h:i A'),
            ]);

        $permissions = Permission::orderBy('order','asc')->get();

        return Inertia::render('Role/Index', [
            'roles' => $roles,
            'permissions' => $permissions,
        ]);
    }

    public function create(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|min:5|max:255|unique:roles',
            'permissions' => 'required|array',
        ]);

        $role = Role::create(['name' => $data['name']]);
        $role->syncPermissions($data['permissions']);
    }

    public function update(Request $request)
    {
        $role = Role::findOrFail($request->id);

        if (auth()->id() != 1) {
            if ($role->id == 1 || $role->id == 2) {
                abort('403','Permission Denied.');
            }
        }

        $data = $request->validate([
            'id' => 'required',
            'name' => 'required|string|min:5|max:50',
            'permissions' => 'required|array',
        ]);

        $role->update(['name' => $request->name]);
        $role->syncPermissions($data['permissions']);
    }
}
