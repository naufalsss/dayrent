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
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm hover:border-slate-300 transition duration-150">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kategori Unit</p>
                        <h3 class="text-2xl font-extrabold text-slate-900 mt-1">{{ $totalKategori ?? 0 }}</h3>
                    </div>
                    <div class="w-7 h-7 bg-slate-50 border border-slate-100 rounded-lg flex items-center justify-center text-xs text-slate-400">📁</div>
                </div>
                <div class="flex items-center gap-1.5 mt-4">
                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Kategori Terdaftar</span>
                </div>
            </div>

            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm hover:border-slate-300 transition duration-150">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Unit Barang Saya</p>
                        <h3 class="text-2xl font-extrabold text-slate-900 mt-1">{{ $totalBarang ?? 0 }}</h3>
                    </div>
                    <div class="w-7 h-7 bg-slate-50 border border-slate-100 rounded-lg flex items-center justify-center text-xs text-slate-400">📦</div>
                </div>
                <div class="flex items-center gap-1.5 mt-4">
                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Unit Siap Sewa</span>
                </div>
            </div>

            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm hover:border-slate-300 transition duration-150">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Penyewaan Aktif</p>
                        <h3 class="text-2xl font-extrabold text-slate-900 mt-1">{{ $penyewaanAktif ?? 0 }}</h3>
                    </div>
                    <div class="w-7 h-7 bg-slate-50 border border-slate-100 rounded-lg flex items-center justify-center text-xs text-slate-400">⚡</div>
                </div>
                <div class="flex items-center gap-1.5 mt-4">
                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Rental Berjalan</span>
                </div>
            </div>

            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm hover:border-slate-300 transition duration-150">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pendapatan Bersih</p>
                        <h3 class="text-2xl font-extrabold text-emerald-600 mt-1">Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}</h3>
                    </div>
                    <div class="w-7 h-7 bg-slate-50 border border-slate-100 rounded-lg flex items-center justify-center text-xs text-slate-400">💰</div>
                </div>
                <div class="flex items-center gap-1.5 mt-4">
                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Akumulasi Finansial</span>
                </div>
            </div>
        </div>

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider">Item Terpopuler</h3>
                        <p class="text-[11px] text-slate-400 font-medium mt-0.5">Item Anda yang paling sering disewa.</p>
                    </div>
                    <form method="GET" action="{{ route('merchant.dashboard') }}" id="formDays">
                        <select name="days" onchange="document.getElementById('formDays').submit()" class="text-xs font-bold text-slate-600 bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1.5 focus:outline-none focus:border-slate-300 cursor-pointer">
                            <option value="30" {{ $daysFilter == 30 ? 'selected' : '' }}>30 Hari Terakhir</option>
                            <option value="60" {{ $daysFilter == 60 ? 'selected' : '' }}>60 Hari Terakhir</option>
                            <option value="90" {{ $daysFilter == 90 ? 'selected' : '' }}>90 Hari Terakhir</option>
                            <option value="180" {{ $daysFilter == 180 ? 'selected' : '' }}>180 Hari Terakhir</option>
                            <option value="360" {{ $daysFilter == 360 ? 'selected' : '' }}>360 Hari Terakhir</option>
                        </select>
                    </form>
                </div>
                <div class="relative h-64 w-full">
                    <canvas id="popularItemsChart"></canvas>
                </div>
            </div>

            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider">Tren Pendapatan</h3>
                        <p class="text-[11px] text-slate-400 font-medium mt-0.5">Pendapatan bersih 6 bulan terakhir.</p>
                    </div>
                </div>
                <div class="relative h-64 w-full">
                    <canvas id="monthlyEarningsChart"></canvas>
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

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctxItems = document.getElementById('popularItemsChart').getContext('2d');
        new Chart(ctxItems, {
            type: 'bar',
            data: {
                labels: {!! json_encode($popularItemLabels) !!},
                datasets: [{
                    label: 'Jumlah Transaksi Sewa',
                    data: {!! json_encode($popularItemValues) !!},
                    backgroundColor: 'rgba(99, 102, 241, 0.8)',
                    borderRadius: 6,
                    borderWidth: 0
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: { ticks: { stepSize: 1, font: { size: 10 } }, grid: { display: false } },
                    y: { ticks: { font: { size: 11, weight: 'bold' } } }
                }
            }
        });

        const ctxEarnings = document.getElementById('monthlyEarningsChart').getContext('2d');
        new Chart(ctxEarnings, {
            type: 'line',
            data: {
                labels: {!! json_encode($monthlyLabels) !!},
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: {!! json_encode($monthlyEarnings) !!},
                    borderColor: 'rgba(16, 185, 129, 1)', // Emerald-500
                    backgroundColor: 'rgba(16, 185, 129, 0.05)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.3,
                    pointRadius: 4,
                    pointBackgroundColor: 'rgba(16, 185, 129, 1)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { size: 10 } } },
                    y: { 
                        beginAtZero: true, 
                        grid: { borderDash: [5, 5], color: '#f1f5f9' },
                        ticks: { font: { size: 10 }, callback: function(value) { return 'Rp ' + (value/1000) + 'k'; } }
                    }
                }
            }
        });
    </script>
@endsection