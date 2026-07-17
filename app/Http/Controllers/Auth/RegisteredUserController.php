<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validasi dasar untuk User maupun Merchant
        // Kunci Aman: Email divalidasi harus unik di tabel users DAN tabel merchant_applications
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 
                'string', 
                'lowercase', 
                'email', 
                'max:255', 
                'unique:'.User::class,
                'unique:merchant_applications,email'
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'account_type' => ['required', 'string', 'in:user,merchant'],
        ]);

        // 2. ALUR REGISTRASI MERCHANT (Masuk Antrean Persetujuan Admin)
        if ($request->account_type === 'merchant') {
            $request->validate([
                'shop_name' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'string', 'max:20'],
                'shop_description' => ['required', 'string'],
                'ktp_number' => ['required', 'string', 'max:30'],
                'npwp_personal' => ['required', 'string', 'max:40'],
                'business_type' => ['required', 'string', 'in:individual,company'],
            ]);

            // Jika dia mendaftar sebagai CV/PT, lakukan validasi tambahan berkas legalitas perusahaan
            if ($request->business_type === 'company') {
                $request->validate([
                    'nib_number' => ['required', 'string', 'max:50'],
                    'akta_number' => ['required', 'string', 'max:100'],
                    'npwp_business' => ['required', 'string', 'max:40'],
                ]);
            }

            DB::table('merchant_applications')->insert([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'shop_name' => $request->shop_name,
                'phone' => $request->phone,
                'shop_description' => $request->shop_description,
                'ktp_number' => $request->ktp_number,
                'npwp_personal' => $request->npwp_personal,
                'business_type' => $request->business_type,
                'nib_number' => $request->business_type === 'company' ? $request->nib_number : null,
                'akta_number' => $request->business_type === 'company' ? $request->akta_number : null,
                'npwp_business' => $request->business_type === 'company' ? $request->npwp_business : null,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->route('login')->with('success', '✨ Pengajuan akun Merchant & Berkas Legalitas berhasil terkirim! Silakan tunggu peninjauan dokumen oleh pihak Admin.');
        }

        // 3. ALUR REGISTRASI USER BIASA (Langsung Aktif & Login)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // Jika di database lu default-nya bukan 'user', lu bisa paksa isi di sini:
            // 'role' => 'user', 
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Di web.php lu, rute '/dashboard' otomatis redirect ke '/'. Jadi kita langsung lempar ke beranda utama
        return redirect()->route('home');
    }
}