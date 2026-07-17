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
        $users = User::latest()->get();
        return view('admin.users.index', compact('users'));
    }

    // 2. Tambah Akun Manual Baru (FIX: Ditambahkan validasi 'merchant')
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,user,merchant', // FIX: Menambahkan string merchant agar lolos validasi
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), 
            'role' => $request->role, 
        ]);

        return redirect()->back()->with('success', 'Akun baru berhasil didaftarkan ke sistem!');
    }

    // 3. Edit / Update Data & Password
    public function update(Request $request, $id) 
    {
        $user = User::findOrFail($id);

        // Validasi Email & Password opsional
        $request->validate([
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
        ]);

        // KOREKSI SAKTI: Jika target yang diedit adalah akun bermutu ADMIN
        if ($user->role === 'admin') {
            // Hanya memperbarui email saja (Nama terproteksi/tidak diubah)
            $user->email = $request->email;
        } else {
            // Jika akun USER biasa atau MERCHANT: Nama, email, dan role boleh diperbarui bebas
            $request->validate([
                'name' => 'required|string|max:255',
                'role' => 'required|in:admin,user,merchant', // FIX: Validasi role saat update
            ]);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->role = $request->role; // FIX: Menyimpan update perubahan role akun
        }

        // Jika password baru diisi di form, lakukan enkripsi lalu update
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Data akun ' . $user->name . ' berhasil diperbarui!');
    }

    // 4. Hapus Akun Manual
    public function destroy($id) 
    {
        if ((int)$id === (int)auth()->id()) {
            return redirect()->back()->with('error', 'Gagal! Anda tidak diperbolehkan menghapus akun Anda sendiri yang sedang aktif.');
        }

        $user = User::findOrFail($id);
        $user->delete();
        
        return redirect()->back()->with('success', 'Akun pengguna berhasil dihapus dari sistem!');
    }
}