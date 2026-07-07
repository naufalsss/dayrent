<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // 1. Tampilkan Halaman Utama & List Akun
    public function index()
    {
        // Ambil semua user kecuali akun yang sedang login saat ini (biar gak sengaja kehapus sendiri)
        $users = User::where('id', '!=', auth()->id())->latest()->get();
        return view('admin.users.index', compact('users'));
    }

    // 2. Tambah Akun Manual Baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Enkripsi password demi keamanan
        ]);

        return redirect()->back()->with('success', 'Akun pengguna baru berhasil didaftarkan secara manual! ✅');
    }

    // 3. Edit / Update Password User Manual (ANTI-COLLECTION INSTANCE)
    public function update(Request $request, $id) // <-- Ganti 'User $user' menjadi '$id' mentah
    {
        $request->validate([
            'password' => 'required|string|min:8',
        ]);

        // Cari data user secara paksa berdasarkan ID tunggal
        $user = User::findOrFail($id);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'Password akun ' . $user->name . ' berhasil diperbarui! 🔑');
    }

    // 4. Hapus Akun Manual (ANTI-COLLECTION INSTANCE)
    public function destroy($id) // <-- Ganti 'User $user' menjadi '$id' mentah
    {
        // Cari data user secara paksa berdasarkan ID tunggal
        $user = User::findOrFail($id);
        
        $user->delete();
        return redirect()->back()->with('success', 'Akun pengguna berhasil dihapus dari sistem!');
    }
}