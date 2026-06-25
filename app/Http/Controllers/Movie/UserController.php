<?php

namespace App\Http\Controllers\Movie;


use App\Models\User;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        Gate::authorize('edit users'); 
    }

    public function index(Request $request)
    {
        $users = User::with('roles')
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%")
                ->orWhere('email', 'like', "%{$request->search}%"))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        AuditService::log('VIEW', 'Viewed users list');

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::orderBy('name')->get();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)],
            'role'     => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->assignRole($data['role']);

        AuditService::log('CREATE', "Created user: {$user->name} ({$user->email}) with role {$data['role']}", User::class, $user->id);

        return redirect()->route('users.index')
            ->with('success', "User \"{$user->name}\" created successfully.");
    }

    public function show(User $user)
    {
        $user->load('roles', 'permissions');
        AuditService::log('VIEW', "Viewed user profile: {$user->name}", User::class, $user->id);
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::orderBy('name')->get();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role'  => 'required|exists:roles,name',
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['required', 'confirmed', Password::min(8)];
        }

        $data = $request->validate($rules);

        $old = $user->only(['name', 'email']);

        $user->update([
            'name'  => $data['name'],
            'email' => $data['email'],
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($data['password'])]);
        }

        $user->syncRoles([$data['role']]);

        AuditService::log(
            'UPDATE',
            "Updated user: {$user->name} ({$user->email})",
            User::class,
            $user->id,
            $old,
            $user->fresh()->only(['name', 'email'])
        );

        return redirect()->route('users.index')
            ->with('success', "User \"{$user->name}\" updated successfully.");
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        $name = $user->name;
        AuditService::log('DELETE', "Deleted user: {$name}", User::class, $user->id, $user->toArray());

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', "User \"{$name}\" deleted successfully.");
    }
}