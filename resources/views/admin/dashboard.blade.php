@extends('admin.master')

@section('content')
    <!-- SUNTIKAN STYLE KUSTOM AGAR TUMPUL SECARA PAKSA & FIX TOMBOL PANAH -->
    <style>
        .finset-card {
            border-radius: 24px !important; /* Memaksa sudut card tumpul sempurna (setara rounded-3xl) */
            border: 1px solid #e2e8f0 !important;
            background-color: #ffffff !important;
        }
        .btn-panah-bulat {
            width: 28px !important;
            height: 28px !important;
            min-width: 28px !important;
            min-height: 28px !important;
            max-width: 28px !important;
            max-height: 28px !important;
            border-radius: 9999px !important; /* Memaksa lingkaran murni 100% tidak bisa gepeng */
            border: 1px solid #e2e8f0 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            box-sizing: border-box !important;
        }
    </style>

    <!-- 1. Grid 4 Kartu Utama -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 flex-shrink-0">
        
        <!-- Card 1: Total Kategori -->
        <div class="finset-card p-6 shadow-sm flex flex-col justify-between relative overflow-hidden">
            <div class="flex justify-between items-start">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Kategori</span>
                <div class="btn-panah-bulat font-bold text-slate-400 text-xs hover:bg-slate-50 transition cursor-pointer">↗</div>
            </div>
            <div class="mt-4">
                <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $totalKategori }}</h3>
                <p class="text-[10px] text-emerald-600 font-bold mt-1 flex items-center gap-1">
                    <span>↑ 12.1%</span> <span class="text-slate-400 font-medium">vs last month</span>
                </p>
            </div>
        </div>

        <!-- Card 2: Total Unit Barang -->
        <div class="finset-card p-6 shadow-sm flex flex-col justify-between relative overflow-hidden">
            <div class="flex justify-between items-start">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Unit Barang</span>
                <div class="btn-panah-bulat font-bold text-slate-400 text-xs hover:bg-slate-50 transition cursor-pointer">↗</div>
            </div>
            <div class="mt-4">
                <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $totalBarang }}</h3>
                <p class="text-[10px] text-emerald-600 font-bold mt-1 flex items-center gap-1">
                    <span>↑ 6.3%</span> <span class="text-slate-400 font-medium">vs last month</span>
                </p>
            </div>
        </div>

        <!-- Card 3: Penyewaan Aktif -->
        <div class="finset-card p-6 shadow-sm flex flex-col justify-between relative overflow-hidden">
            <div class="flex justify-between items-start">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Penyewaan Aktif</span>
                <div class="btn-panah-bulat font-bold text-slate-400 text-xs hover:bg-slate-50 transition cursor-pointer">↗</div>
            </div>
            <div class="mt-4">
                <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight">0</h3>
                <p class="text-[10px] text-rose-500 font-bold mt-1 flex items-center gap-1">
                    <span>↓ 2.4%</span> <span class="text-slate-400 font-medium">vs last month</span>
                </p>
            </div>
        </div>

        <!-- Card 4: Total Pendapatan -->
        <div class="finset-card p-6 shadow-sm flex flex-col justify-between relative overflow-hidden">
            <div class="flex justify-between items-start">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Pendapatan</span>
                <div class="btn-panah-bulat font-bold text-slate-400 text-xs hover:bg-slate-50 transition cursor-pointer">↗</div>
            </div>
            <div class="mt-4">
                <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight">Rp 0</h3>
                <p class="text-[10px] text-emerald-600 font-bold mt-1 flex items-center gap-1">
                    <span>↑ 12.1%</span> <span class="text-slate-400 font-medium">vs last month</span>
                </p>
            </div>
        </div>
    </div>

    <!-- 2. Bagian Bawah: Tabel Transaksi -->
    <div class="finset-card p-6 shadow-sm flex-1 flex flex-col justify-between overflow-hidden mb-1">
        <div class="flex flex-col h-full">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4 flex-shrink-0">
                <h4 class="font-extrabold text-base text-slate-900">Recent logs / transactions</h4>
                <a href="#" class="text-xs font-bold text-violet-600 hover:underline flex items-center gap-1">See all ↗</a>
            </div>

            <div class="overflow-x-auto flex-1 rounded-2xl overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-[11px] font-bold uppercase tracking-wider text-slate-400 bg-slate-50">
                            <th class="py-3.5 px-4 rounded-l-xl">Date</th>
                            <th class="py-3.5 px-4">User</th>
                            <th class="py-3.5 px-4">Action / Unit</th>
                            <th class="py-3.5 px-4 rounded-r-xl">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs font-semibold text-slate-600 divide-y divide-slate-100">
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4 px-4 text-slate-400">03 Jun 16:30</td>
                            <td class="py-4 px-4 font-bold text-slate-900">{{ auth()->user()->name }}</td>
                            <td class="py-4 px-4 text-slate-500">Registered New Account via Breeze</td>
                            <td class="py-4 px-4"><span class="bg-emerald-50 text-emerald-600 px-2.5 py-1 rounded-full text-[10px] font-extrabold tracking-wide">Success</span></td>
                        </tr>
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4 px-4 text-slate-400">03 Jun 15:12</td>
                            <td class="py-4 px-4 font-bold text-slate-900">System Bot</td>
                            <td class="py-4 px-4 text-slate-500">Compiled Asset via Vite Dev Engine</td>
                            <td class="py-4 px-4"><span class="bg-violet-50 text-violet-600 px-2.5 py-1 rounded-full text-[10px] font-extrabold tracking-wide">Active</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection