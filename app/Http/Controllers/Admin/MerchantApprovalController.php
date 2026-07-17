<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MerchantApprovalController extends Controller
{
    // 1. Tampilkan Semua Request Pengajuan Merchant
    public function index()
    {
        $applications = DB::table('merchant_applications')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.merchant_approval.index', compact('applications'));
    }

    // 2. Aksi Approve Pengajuan
    public function approve($id)
    {
        $app = DB::table('merchant_applications')->where('id', $id)->first();

        if (!$app) {
            return redirect()->back()->with('error', 'Data pengajuan tidak ditemukan!');
        }

        if ($app->status !== 'pending') {
            return redirect()->back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        DB::beginTransaction();
        try {
            // Pindahkan & daftarkan ke tabel users dengan role 'merchant'
            DB::table('users')->insert([
                'name' => $app->name,
                'email' => $app->email,
                'password' => $app->password, // Menggunakan hash password dari form register awal
                'role' => 'merchant',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Update status pengajuan menjadi approved
            DB::table('merchant_applications')->where('id', $id)->update([
                'status' => 'approved',
                'updated_at' => now()
            ]);

            DB::commit();
            return redirect()->back()->with('success', '✨ Akun Merchant berhasil disetujui dan didaftarkan ke sistem!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    // 3. Aksi Decline Pengajuan
    public function decline($id)
    {
        $app = DB::table('merchant_applications')->where('id', $id)->first();

        if (!$app) {
            return redirect()->back()->with('error', 'Data pengajuan tidak ditemukan!');
        }

        if ($app->status !== 'pending') {
            return redirect()->back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        // Ubah status menjadi declined (Data formulir tetap utuh agar admin bisa meninjau riwayat kapan saja)
        DB::table('merchant_applications')->where('id', $id)->update([
            'status' => 'declined',
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', '❌ Pengajuan Merchant berhasil ditolak.');
    }
}