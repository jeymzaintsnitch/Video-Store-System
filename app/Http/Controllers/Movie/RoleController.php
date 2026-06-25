<?php

namespace App\Http\Controllers\Movie;

use App\Services\AuditService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class RoleController extends Controller
{
    public function __construct()
    {
        Gate::authorize('edit users'); 
    }


    public function index()
    {
        $roles = Role::with('permissions')->withCount('users')->get();
        AuditService::log('VIEW', 'Viewed roles list');
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        // Group permissions by resource for a cleaner UI
        $permissions = Permission::orderBy('name')->get()->groupBy(function ($p) {
            $parts = explode(' ', $p->name);
            return end($parts); // e.g. "movies", "tapes", "users"
        });
        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'            => 'required|string|max:100|unique:roles,name',
            'permission_ids'  => 'nullable|array',
            'permission_ids.*' => 'exists:permissions,id',
        ]);

        $role = Role::create(['name' => $data['name'], 'guard_name' => 'web']);

        if (!empty($data['permission_ids'])) {
            $role->syncPermissions(array_map('intval', $data['permission_ids']));
        }

        \App\Services\AuditService::log('CREATE', "Created role: {$role->name}");

        return redirect()->route('roles.index')
            ->with('success', "Role \"{$role->name}\" created successfully.");
    }

    public function show(Role $role)
    {
        $role->load('permissions');
        AuditService::log('VIEW', "Viewed role: {$role->name}");
        return view('roles.show', compact('role'));
    }

    public function edit(Role $role)
    {
        $permissions = Permission::orderBy('name')->get()->groupBy(function ($p) {
            $parts = explode(' ', $p->name);
            return end($parts);
        });
        $rolePermissionIds = $role->permissions->pluck('id')->toArray();
        return view('roles.edit', compact('role', 'permissions', 'rolePermissionIds'));
    }

    public function update(Request $request, Role $role)
    {
        $data = $request->validate([
            'name'            => 'required|string|max:100|unique:roles,name,' . $role->id,
            'permission_ids'  => 'nullable|array',
            'permission_ids.*' => 'exists:permissions,id',
        ]);

        $old = $role->name;
        $role->update(['name' => $data['name']]);

        $permissionIds = isset($data['permission_ids']) ? array_map('intval', $data['permission_ids']) : [];
        $role->syncPermissions($permissionIds);

        \App\Services\AuditService::log('UPDATE', "Updated role: {$old} → {$role->name}");

        return redirect()->route('roles.index')
            ->with('success', "Role \"{$role->name}\" updated successfully.");
    }

    public function destroy(Role $role)
    {
        if ($role->name === 'Admin') {
            return redirect()->route('roles.index')
                ->with('error', 'The Admin role cannot be deleted.');
        }

        $name = $role->name;
        $role->delete();

        AuditService::log('DELETE', "Deleted role: {$name}");

        return redirect()->route('roles.index')
            ->with('success', "Role \"{$name}\" deleted successfully.");
    }
}
