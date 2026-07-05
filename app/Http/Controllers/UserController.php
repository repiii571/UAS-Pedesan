<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Daftar akun (admin & kasir).
     */
    public function index()
    {
        $users = User::latest()->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Form tambah akun baru.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Simpan akun baru (default role: kasir).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:admin,kasir',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Akun berhasil ditambahkan.');
    }

    /**
     * Hapus akun. Admin tidak boleh menghapus akunnya sendiri.
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'Kamu tidak bisa menghapus akunmu sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Akun berhasil dihapus.');
    }
}
