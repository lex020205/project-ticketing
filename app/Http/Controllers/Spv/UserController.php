<?php

namespace App\Http\Controllers\Spv;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

// Modul 12 - SPV User Management
// Ringkas: CRUD user, status akun, dan reset password.
class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('role');

        $query->when($request->filled('role_id'), function ($builder) use ($request) {
            $builder->where('role_id', $request->role_id);
        });

        $query->when($request->filled('status_user'), function ($builder) use ($request) {
            $builder->where('status_user', $request->status_user);
        });

        $query->when($request->filled('keyword'), function ($builder) use ($request) {
            $keyword = trim($request->keyword);

            $builder->where(function ($subQuery) use ($keyword) {
                $subQuery->where('name', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%")
                    ->orWhere('nomor_telepon', 'like', "%{$keyword}%");
            });
        });

        $users = $query->orderBy('name')->get();
        $roles = Role::orderBy('nama_role')->get();

        return view('spv.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::orderBy('nama_role')->get();

        return view('spv.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role_id' => 'required|exists:roles,id',
            'nomor_telepon' => 'nullable|string|max:30',
            'password' => 'required|string|min:8|confirmed',
            'status_user' => 'required|in:aktif,nonaktif',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()
            ->route('spv.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function show(User $user)
    {
        $user->load('role');

        return view('spv.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::orderBy('nama_role')->get();

        return view('spv.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role_id' => 'required|exists:roles,id',
            'nomor_telepon' => 'nullable|string|max:30',
            'password' => 'nullable|string|min:8|confirmed',
            'status_user' => 'required|in:aktif,nonaktif',
        ]);

        $payload = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role_id' => $validated['role_id'],
            'nomor_telepon' => $validated['nomor_telepon'] ?? null,
            'status_user' => $validated['status_user'],
        ];

        if (!empty($validated['password'])) {
            $payload['password'] = Hash::make($validated['password']);
        }

        $user->update($payload);

        return redirect()
            ->route('spv.users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function toggleStatus(User $user)
    {
        if ((int) $user->id === (int) auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun sendiri.');
        }

        $user->update([
            'status_user' => $user->status_user === 'aktif' ? 'nonaktif' : 'aktif',
        ]);

        return back()->with('success', 'Status user berhasil diperbarui.');
    }

    public function resetPassword(User $user)
    {
        if ((int) $user->id === (int) auth()->id()) {
            return back()->with('error', 'Anda tidak dapat reset password akun sendiri dari menu ini.');
        }

        $user->update([
            'password' => Hash::make('password'),
        ]);

        return back()->with('success', 'Password user berhasil direset menjadi password.');
    }
}
