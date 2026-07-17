<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Penyewaan Saya - {{ config('app.name', 'DAY-RENT') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
</head>
<body class="bg-slate-900 text-white min-h-screen relative py-12" style="font-family: 'Poppins', sans-serif;">
    
    <div class="max-w-4xl mx-auto px-4">
        <div class="flex items-center justify-between mb-8 pb-4 border-b border-white/10">
            <div>
                <h2 class="text-xl font-black uppercase tracking-wider">History Order</h2>
                <p class="text-xs text-slate-400 mt-0.5">Pantau status persetujuan dari berkas unit barang yang kamu sewa.</p>
            </div>
            <a href="/" class="bg-white/5 border border-white/10 text-slate-300 hover:text-white hover:bg-white/10 px-4 py-2 rounded-xl text-xs font-bold transition">Kembali</a>
        </div>

        <div class="space-y-4">
            @forelse($myHistory as $history)
                <!-- MODIFIKASI: Dibungkus tag <a> agar seluruh area kartu bisa diklik menuju halaman order rincian -->
                <a href="{{ route('order.details', $history->id) }}" class="block group">
                    <div class="backdrop-blur-md bg-white/5 border border-white/10 rounded-2xl p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 transition group-hover:border-white/30 group-hover:bg-white/[0.07]">
                        <div class="flex items-center gap-4">
                            
                            <!-- FIX LOGIC BREE: Pengecekan cerdas string letak penyimpanan gambar history order -->
                            <img src="{{ (str_starts_with($history->item_image, '/storage/') || str_starts_with($history->item_image, 'storage/')) ? asset(ltrim($history->item_image, '/')) : asset('storage/' . $history->item_image) }}" 
                                 alt="{{ $history->item_name }}" 
                                 class="w-14 h-14 object-cover rounded-xl border border-white/5 bg-slate-800 transition group-hover:scale-105">
                            
                            <div>
                                <h4 class="font-extrabold text-base text-white tracking-tight leading-snug group-hover:text-blue-400 transition">{{ $history->item_name }}</h4>
                                
                                <!-- TAMBAHAN KODE ORDER UNIK USER -->
                                <p class="text-[10px] text-blue-400 font-bold uppercase tracking-wider mt-0.5">ORDER ID: {{ $history->order_code ?? 'MANUAL-ORDER' }}</p>
                                
                                <p class="text-[11px] text-slate-400 mt-1 font-medium">Customer: {{ $history->customer_name }}</p>
                                <p class="text-[10px] text-slate-500 mt-0.5">Diajukan pada: {{ date('d M Y, H:i', strtotime($history->created_at)) }} WIB</p>
                            </div>
                        </div>

                        <div class="sm:text-right w-full sm:w-auto flex sm:flex-col items-center sm:items-end justify-between sm:justify-center gap-3">
                            <!-- FIX ENGINE BADGE STATUS: Menyelaraskan status database admin secara dinamis -->
                            @if(strtolower($history->status) === 'approved')
                                <span class="bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">Approved</span>
                            
                            @elseif(strtolower($history->status) === 'declined')
                                <span class="bg-rose-500/10 text-rose-400 border border-rose-500/20 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">Declined</span>
                            
                            @elseif(strtolower($history->status) === 'returned')
                                <span class="bg-blue-500/10 text-blue-400 border border-blue-500/20 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">Returned</span>
                            
                            @elseif(strtolower($history->status) === 'expired')
                                <span class="bg-slate-500/20 text-slate-400 border border-white/10 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">Expired</span>
                            
                            @else
                                <span class="bg-amber-500/10 text-amber-400 border border-amber-500/20 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">Pending</span>
                            @endif

                            <!-- TOMBOL NAVIGASI KLIK INDIKATOR -->
                            <span class="text-[11px] px-5 text-slate-400 font-bold group-hover:text-white transition flex items-center gap-1">
                                Details
                            </span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="text-center py-12 backdrop-blur-md bg-white/5 border border-white/10 rounded-2xl">
                    <p class="text-xs text-slate-400">Kamu belum pernah melakukan transaksi sewa unit barang.</p>
                </div>
            @endforelse
        </div>
    </div>

</body>
</html>