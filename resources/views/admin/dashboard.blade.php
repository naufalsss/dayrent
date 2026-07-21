@extends('admin.master')

@section('content')
<div class="space-y-8">
    
    <!-- STATISTIK METRIK CARD (GRID 5 COLUMNS) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm hover:border-slate-300 transition duration-150">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Kategori</p>
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
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Unit Barang</p>
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
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Pendapatan</p>
                    <h3 class="text-2xl font-extrabold text-blue-600 mt-1">Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}</h3>
                </div>
                <div class="w-7 h-7 bg-slate-50 border border-slate-100 rounded-lg flex items-center justify-center text-xs text-slate-400">💰</div>
            </div>
            <div class="flex items-center gap-1.5 mt-4">
                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Akumulasi Finansial</span>
            </div>
        </div>

        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm hover:border-slate-300 transition duration-150">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Merchant</p>
                    <h3 class="text-2xl font-extrabold text-slate-900 mt-1">{{ $totalMerchant ?? 0 }}</h3>
                </div>
                <div class="w-7 h-7 bg-slate-50 border border-slate-100 rounded-lg flex items-center justify-center text-xs text-slate-400">🏪</div>
            </div>
            <div class="flex items-center gap-1.5 mt-4">
                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Merchant Aktif</span>
            </div>
        </div>
    </div>

    <!-- AREA GRAFIK ANALITIK -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- GRAFIK 1: ITEM TERPOPULER -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider">Item Terpopuler</h3>
                    <p class="text-[11px] text-slate-400 font-medium mt-0.5">Item yang paling sering disewa pelanggan.</p>
                </div>
                <form method="GET" action="{{ route('admin.dashboard') }}" id="formDays">
                    <!-- HIDDEN YEAR DIPERBAIKI / DIHAPUS SUPAYA TIDAK BIKIN EROR -->
                    @if(isset($yearFilter))
                        <input type="hidden" name="year" value="{{ $yearFilter }}">
                    @endif
                    <select name="days" onchange="document.getElementById('formDays').submit()" class="text-xs font-bold text-slate-600 bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1.5 focus:outline-none focus:border-slate-300 cursor-pointer">
                        <option value="30" {{ ($daysFilter ?? 30) == 30 ? 'selected' : '' }}>30 Hari Terakhir</option>
                        <option value="60" {{ ($daysFilter ?? 30) == 60 ? 'selected' : '' }}>60 Hari Terakhir</option>
                        <option value="90" {{ ($daysFilter ?? 30) == 90 ? 'selected' : '' }}>90 Hari Terakhir</option>
                        <option value="180" {{ ($daysFilter ?? 30) == 180 ? 'selected' : '' }}>180 Hari Terakhir</option>
                        <option value="360" {{ ($daysFilter ?? 30) == 360 ? 'selected' : '' }}>360 Hari Terakhir</option>
                    </select>
                </form>
            </div>
            <div class="relative h-64 w-full">
                <canvas id="popularItemsChart"></canvas>
            </div>
        </div>

        <!-- GRAFIK 2: TREN PENDAPATAN -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider">Tren Pendapatan</h3>
                    <p class="text-[11px] text-slate-400 font-medium mt-0.5">Grafik total nominal pendapatan bulanan masuk.</p>
                </div>
            </div>
            <div class="relative h-64 w-full">
                <canvas id="monthlyEarningsChart"></canvas>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // ---------------------------------------------------------
    // RENDER GRAFIK 1: ITEM TERPOPULER (BAR CHART HORIZONTAL)
    // ---------------------------------------------------------
    const ctxItems = document.getElementById('popularItemsChart').getContext('2d');
    new Chart(ctxItems, {
        type: 'bar',
        data: {
            labels: {!! json_encode($popularItemLabels ?? []) !!},
            datasets: [{
                label: 'Jumlah Transaksi Sewa',
                data: {!! json_encode($popularItemValues ?? []) !!},
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

    // ---------------------------------------------------------
    // RENDER GRAFIK 2: TREN PENDAPATAN BULANAN (LINE CHART ELEGAN)
    // ---------------------------------------------------------
    const ctxEarnings = document.getElementById('monthlyEarningsChart').getContext('2d');
    const defaultMonths = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    const earningsLabels = {!! json_encode($monthlyLabels ?? null) !!} || defaultMonths;

    new Chart(ctxEarnings, {
        type: 'line',
        data: {
            labels: earningsLabels,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: {!! json_encode($monthlyEarnings ?? []) !!},
                borderColor: 'rgba(37, 99, 235, 1)',
                backgroundColor: 'rgba(37, 99, 235, 0.05)',
                borderWidth: 3,
                fill: true,
                tension: 0.3,
                pointRadius: 4,
                pointBackgroundColor: 'rgba(37, 99, 235, 1)'
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
                    ticks: { 
                        font: { size: 10 },
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    } 
                }
            }
        }
    });
</script>
@endsection