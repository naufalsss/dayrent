@extends('admin.master')

@section('content')
    @if(session('success'))
        <div class="p-4 mb-6 text-xs font-bold text-emerald-600 bg-emerald-50 rounded-2xl border border-emerald-200 flex items-center gap-2 max-w-5xl">
            <span>✨</span> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 mb-6 text-xs font-bold text-rose-600 bg-rose-50 rounded-2xl border border-rose-200 flex items-center gap-2 max-w-5xl">
            <span>🚨</span> {{ session('error') }}
        </div>
    @endif

    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm w-full max-w-5xl">
        
        <!-- HEADER KONTEN + DROPDOWN SINKRONISASI FILTER -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 pb-4 border-b border-slate-100 mb-5">
            <div>
                <h4 class="font-extrabold text-xs text-slate-800 uppercase tracking-wider">Manajemen Transaksi Penyewaan</h4>
                <p class="text-[11px] text-slate-400 font-medium mt-1">
                    @if(auth()->user()->role === 'admin' && !empty($searchMerchant))
                        🔍 Menampilkan hasil pencarian untuk merchant: <strong class="text-blue-600">{{ $searchMerchant }}</strong>
                    @else
                        Pantau data customer masuk, waktu transaksi, dan kelola keputusan persetujuan berkas sewa.
                    @endif
                </p>
            </div>
            
            <!-- MASTER INTEGRATED SEARCH & FILTER FORM -->
            <form method="GET" action="{{ route('admin.rentals.index') }}" class="flex flex-wrap items-center gap-3 lg:flex-shrink-0">
                
                <!-- INPUT PENCARIAN MERCHANT KHUSUS ROLE ADMIN SUPER -->
                @if(auth()->user()->role === 'admin')
                    <div class="flex items-center gap-1.5">
                        <input type="text" name="search_merchant" value="{{ $searchMerchant ?? '' }}"
                               placeholder="Cari nama toko merchant..." 
                               class="bg-slate-50 border border-slate-200 text-slate-700 text-xs font-semibold px-4 py-2 rounded-xl focus:outline-none focus:border-blue-600 h-[38px] w-48 placeholder-slate-400">
                        @if(!empty($searchMerchant))
                            <a href="{{ route('admin.rentals.index') }}" class="text-[10px] text-slate-400 hover:text-rose-600 font-bold px-1 transition uppercase tracking-wider">Reset</a>
                        @endif
                    </div>
                @endif

                <div class="flex items-center gap-2">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider whitespace-nowrap">Filter Waktu:</label>
                    <select name="time_status" onchange="this.form.submit()" 
                            class="bg-slate-50 border border-slate-200 text-slate-700 text-xs font-bold px-3 py-2 rounded-xl cursor-pointer focus:outline-none focus:border-blue-600 h-[38px] min-w-[150px]">
                        <option value="all" {{ ($timeStatus ?? 'all') === 'all' ? 'selected' : '' }}>🟢 Semua Transaksi</option>
                        <option value="active" {{ ($timeStatus ?? 'all') === 'active' ? 'selected' : '' }}>⚡ Masa Sewa Aktif</option>
                        <option value="critical" {{ ($timeStatus ?? 'all') === 'critical' ? 'selected' : '' }}>⚠️ Kurang dari 1 Jam</option>
                        <option value="expired" {{ ($timeStatus ?? 'all') === 'expired' ? 'selected' : '' }}>🚨 Masa Sewa Habis</option>
                    </select>
                </div>

                @if(auth()->user()->role === 'admin')
                    <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold px-4 h-[38px] rounded-xl transition cursor-pointer border-0 shadow-sm shadow-blue-600/10 uppercase tracking-wider">
                        Cari
                    </button>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto rounded-xl border border-slate-200/60">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[10px] font-bold uppercase tracking-widest text-slate-400 bg-slate-50 border-b border-slate-200/60">
                        <th class="py-3.5 px-5 w-64">Waktu Sewa & Durasi</th>
                        <th class="py-3.5 px-5">Unit & Penyewa</th>
                        <th class="py-3.5 px-5">Nomor WhatsApp</th>
                        <th class="py-3.5 px-5 text-center">Status</th>
                        <th class="py-3.5 px-5 text-center">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="text-xs font-medium text-slate-600 divide-y divide-slate-100">
                    @forelse($rentals as $rental)
                        <tr class="hover:bg-slate-50/40 transition duration-100">
                            
                            <td class="py-3.5 px-5 space-y-1">
                                <div class="font-bold text-slate-700">Mulai: {{ date('d M Y, H:i', strtotime($rental->created_at)) }} WIB</div>
                                @if(isset($rental->expired_at))
                                    <div class="text-[11px] text-rose-600 font-semibold">Selesai: {{ date('d M Y, H:i', strtotime($rental->expired_at)) }} WIB</div>
                                    <div class="text-[10px] text-slate-400">Durasi sewa: {{ $rental->duration }} Berbasis Waktu</div>
                                    
                                    <div class="pt-1">
                                        @if($rental->status === 'expired')
                                            <span class="bg-slate-100 text-slate-600 font-mono px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider">
                                                WAKTU SEWA HABIS
                                            </span>
                                        @elseif($rental->status === 'returned')
                                            <span class="bg-blue-50 text-blue-600 font-mono px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider">
                                                SELESAI DIKEMBALIKAN
                                            </span>
                                        @elseif($rental->status === 'declined')
                                            <span class="bg-rose-50 text-rose-600 font-mono px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider">
                                                PESANAN DITOLAK
                                            </span>
                                        @else
                                            <span id="countdown-{{ $rental->id }}" class="bg-blue-50 text-blue-600 font-mono px-2 py-0.5 rounded text-[10px] font-bold">
                                                Menghitung...
                                            </span>

                                            <script>
                                                (function() {
                                                    const rawDateStr = "{{ $rental->expired_at }}".replace(" ", "T") + "+07:00";
                                                    const countDownDate = new Date(rawDateStr).getTime();
                                                    
                                                    const timer = setInterval(function() {
                                                        const now = new Date().getTime();
                                                        const distance = countDownDate - now;

                                                        if (distance < 0) {
                                                            clearInterval(timer);
                                                            document.getElementById("countdown-{{ $rental->id }}").innerHTML = "WAKTU SEWA HABIS";
                                                            document.getElementById("countdown-{{ $rental->id }}").className = "bg-slate-100 text-slate-600 font-mono px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider";
                                                        } else {
                                                            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                                                            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                                            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                                            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                                                            let output = "";
                                                            if (days > 0) output += days + "h ";
                                                            output += hours + "jam " + minutes + "m " + seconds + "s";
                                                            
                                                            document.getElementById("countdown-{{ $rental->id }}").innerHTML = "Sisa: " + output;
                                                        }
                                                    }, 1000);
                                                })();
                                            </script>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-slate-400 italic text-[10px]">Data durasi belum tercatat</span>
                                @endif
                            </td>
                            
                            <td class="py-3.5 px-5">
                                <span class="font-bold text-slate-800 text-sm block">{{ $rental->customer_name }}</span>
                                <div class="text-[11px] text-slate-400 mt-0.5">Unit: <span class="text-slate-600 font-semibold">{{ $rental->item_name }}</span></div>
                            </td>
                            
                            <td class="py-3.5 px-5 font-mono text-slate-500 font-semibold tracking-tight">{{ $rental->whatsapp_number }}</td>
                            
                            <td class="py-3.5 px-5 text-center">
                                @if($rental->status === 'approved')
                                    <span class="bg-emerald-50 text-emerald-600 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border border-emerald-100">Approved</span>
                                @elseif($rental->status === 'declined')
                                    <span class="bg-rose-50 text-rose-600 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border border-rose-100">Declined</span>
                                @elseif($rental->status === 'expired')
                                    <span class="bg-slate-100 text-slate-600 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border border-slate-200">Expired</span>
                                @elseif($rental->status === 'returned')
                                    <span class="bg-blue-50 text-blue-600 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border border-blue-100">Returned</span>
                                @else
                                    <span class="bg-amber-50 text-amber-600 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border border-amber-100">Pending</span>
                                @endif
                            </td>
                            
                            <td class="py-3.5 px-5 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    @if($rental->status === 'pending')
                                        <form method="POST" action="{{ route('admin.rentals.approve', $rental->id) }}">
                                            @csrf
                                            <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white text-[10px] font-bold uppercase tracking-wider px-3 py-1.5 rounded-lg shadow-sm transition cursor-pointer border-0">Approve</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.rentals.decline', $rental->id) }}">
                                            @csrf
                                            <button type="submit" class="bg-slate-100 hover:bg-slate-200 text-slate-600 text-[10px] font-bold uppercase tracking-wider px-3 py-1.5 rounded-lg transition cursor-pointer border-0">Decline</button>
                                        </form>
                                    @elseif(($rental->status === 'expired' || ($rental->status === 'approved' && isset($rental->expired_at) && strtotime($rental->expired_at) <= time())) && $rental->status !== 'returned')
                                        <form method="POST" action="{{ route('admin.rentals.confirmReturn', $rental->id) }}">
                                            @csrf
                                            <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 text-white text-[10px] font-bold uppercase tracking-wider px-3 py-1.5 rounded-lg shadow-sm transition cursor-pointer border-0">
                                                ✓ Dikembalikan
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.rentals.destroy', $rental->id) }}" onsubmit="return confirm('Apakah kamu yakin ingin menghapus riwayat transaksi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200/50 text-[10px] font-bold uppercase tracking-wider px-3 py-1.5 rounded-lg transition cursor-pointer">
                                                Hapus
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.rentals.destroy', $rental->id) }}" onsubmit="return confirm('Apakah kamu yakin ingin menghapus riwayat transaksi ini dari dashboard admin?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200/50 text-[10px] font-bold uppercase tracking-wider px-3 py-1.5 rounded-lg transition cursor-pointer">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-16 text-center text-slate-400 font-bold text-xs uppercase tracking-wider">
                                📭 Belum ada data transaksi penyewaan yang sesuai.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection