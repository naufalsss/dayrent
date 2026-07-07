<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    // Menampilkan halaman lengkap akun
    public function completeAccountView()
    {
        return view('complete_account');
    }

    // Memproses update data profil & password user
    public function updateCompleteAccount(Request $request)
    {
        $user = auth()->user();

        // 1. Validasi Input Dasar
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'whatsapp_number' => 'required|string|max:20',
            'current_password' => 'required',
            'new_password' => 'nullable|string|min:8',
        ]);

        // 2. KUNCI UTAMA: Cek apakah password aktif saat ini cocok dengan di database
        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Kata sandi saat ini yang Anda masukkan salah!']);
        }

        // 3. Siapkan Array Update Data
        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'whatsapp_number' => $request->whatsapp_number,
            'updated_at' => now(),
        ];

        // 4. Jika user mengisi field password baru, enkripsi lalu masukkan ke array
        if ($request->filled('new_password')) {
            $updateData['password'] = \Illuminate\Support\Facades\Hash::make($request->new_password);
        }

        // 5. Eksekusi update langsung ke tabel users
        DB::table('users')->where('id', $user->id)->update($updateData);

        return redirect()->back()->with('success', 'Data profil dan nomor akun Anda berhasil diperbarui!');
    }

}
