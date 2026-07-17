<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // =========================================================================
        // FIX SAKTI: SEEDER OTOMATISASI DUA ROLE AKUN UNTUK TESTING TIM KELOMPOK
        // =========================================================================
        
        // 1. Membuat Akun Admin Utama Kelompok Day-Rent
        User::create([
            'name' => 'Admin DayRent',
            'email' => 'admin@dayrent.test',        // Email Login Admin
            'password' => Hash::make('password123'), // Password Login Admin
            'role' => 'admin',                       // KUNCI: Set langsung jadi admin agar lolos middleware
        ]);

        // 2. Membuat Akun User Biasa untuk simulasi pengajuan sewa unit
        User::create([
            'name' => 'Mohammad Naufal Murfid',
            'email' => 'user@dayrent.test',         // Email Login User
            'password' => Hash::make('password123'), // Password Login User
            'role' => 'user',                        // Set jadi user biasa
        ]);
    }
}