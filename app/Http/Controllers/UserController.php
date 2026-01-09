<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Display users list
     */
    public function index()
    {
        $users = User::orderBy('role')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.master.users.index', compact('users'));
    }

    /**
     * Show create user form
     */
    public function create()
    {
        return view('admin.master.users.create');
    }

    /**
     * Store new user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'role' => ['required', 'in:penjaga,pemilik'],
            'password' => ['required', 'confirmed', Password::min(6)],
        ], [
            'name.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'role.required' => 'Role harus dipilih',
            'password.required' => 'Password harus diisi',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.user.index')
            ->with('success', 'User berhasil ditambahkan');
    }

    /**
     * Show edit user form
     */
    public function edit(User $user)
    {
        return view('admin.master.users.edit', compact('user'));
    }

    /**
     * Update user
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'role' => ['required', 'in:penjaga,pemilik'],
            'password' => ['nullable', 'confirmed', Password::min(6)],
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'role' => $validated['role'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return redirect()->route('admin.user.index')
            ->with('success', 'User berhasil diupdate');
    }

    /**
     * Delete user
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri');
        }

        $user->delete();

        return redirect()->route('admin.user.index')
            ->with('success', 'User berhasil dihapus');
    }
}
