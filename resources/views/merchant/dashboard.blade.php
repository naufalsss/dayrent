@extends('merchant.master')

@section('content')
    <div class="flex flex-col gap-8 w-full max-w-6xl">
        <!-- HEADER KARTU SELAMAT DATANG -->
        <div class="bg-gradient-to-r from-emerald-600 to-teal-600 rounded-3xl p-6 md:p-8 text-white shadow-md shadow-emerald-600/10 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl md:text-2xl font-extrabold tracking-tight">Selamat Datang di Merchant Panel, {{ auth()->user()->name }}! 🚀</h2>
                <p class="text-xs text-emerald-100/90 mt-1 font-medium">Kelola unit barang sewaan lu dan pantau konfirmasi pengembalian customer dari satu halaman.</p>
            </div>
            <a href="#" class="bg-white/10 hover:bg-white/20 border border-white/20 px-4 py-2 rounded-xl text-xs font-bold transition uppercase tracking-wider whitespace-nowrap">
                Lihat Lapak Publik
            </a>
        </div>

        <!-- STATISTIK KARTU RINGKAS -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-xl text-emerald-600 font-bold">📦</div>
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Unit Barang Saya</p>
                    <h3 class="text-2xl font-black text-slate-800 mt-0.5">{{ $totalItems }} <span class="text-xs font-bold text-slate-400">Unit</span></h3>
                </div>
            </div>

            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-xl text-blue-600 font-bold">📝</div>
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Transaksi Sewa</p>
                    <h3 class="text-2xl font-black text-slate-800 mt-0.5">{{ $totalRentals }} <span class="text-xs font-bold text-slate-400">Kali</span></h3>
                </div>
            </div>
        </div>

        <!-- TABEL KARTU PENYEWAAN TERBARU -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm overflow-hidden">
            <h4 class="font-extrabold text-xs text-slate-800 pb-3 border-b border-slate-100 mb-5 uppercase tracking-wider flex items-center gap-2">
                <span>🔔</span> Riwayat Sewa Masuk Terbaru
            </h4>

            <div class="overflow-x-auto rounded-xl border border-slate-200">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-[10px] font-bold uppercase tracking-widest text-slate-400 bg-slate-50 border-b border-slate-200">
                            <th class="py-3.5 px-5">Order ID / Unit Barang</th>
                            <th class="py-3.5 px-5">Nama Penyewa</th>
                            <th class="py-3.5 px-5">Durasi Sewa</th>
                            <th class="py-3.5 px-5">Status</th>
                            <th class="py-3.5 px-5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs font-medium text-slate-600 divide-y divide-slate-100">
                        @forelse($recentRentals as $rental)
                            <tr class="hover:bg-slate-50/40 transition">
                                <td class="py-3.5 px-5">
                                    <p class="font-bold text-slate-800">{{ $rental->item_name }}</p>
                                    <span class="text-[10px] text-blue-500 font-mono tracking-tight uppercase font-semibold">{{ $rental->order_code }}</span>
                                </td>
                                <td class="py-3.5 px-5 text-slate-700 font-semibold">{{ $rental->customer_name }}</td>
                                <td class="py-3.5 px-5 text-slate-500 font-medium">{{ $rental->duration }} Hari / Bulan</td>
                                <td class="py-3.5 px-5">
                                    @if($rental->status === 'returned')
                                        <span class="px-2 py-0.5 rounded-md text-[9px] font-bold uppercase bg-blue-50 text-blue-600 border border-blue-100">Returned</span>
                                    @elseif($rental->status === 'approved')
                                        <span class="px-2 py-0.5 rounded-md text-[9px] font-bold uppercase bg-emerald-50 text-emerald-600 border border-emerald-100">Active / Approved</span>
                                    @elseif($rental->status === 'declined')
                                        <span class="px-2 py-0.5 rounded-md text-[9px] font-bold uppercase bg-rose-50 text-rose-600 border border-rose-100">Declined</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-md text-[9px] font-bold uppercase bg-amber-50 text-amber-600 border border-amber-100">Pending</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-5 text-center">
                                    <a href="{{ route('merchant.rentals.index') }}" class="text-[11px] font-bold text-slate-400 hover:text-emerald-600 transition">
                                        Kelola Transaksi
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-slate-400 uppercase font-bold text-[11px]">
                                    Belum ada transaksi sewa masuk untuk unit produk lu, Bree.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection