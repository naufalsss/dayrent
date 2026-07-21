@extends('merchant.master')

@section('content')
    @if(session('success'))
        <div class="p-4 mb-6 text-xs font-bold text-emerald-600 bg-emerald-50 rounded-2xl border border-emerald-200 flex items-center gap-2 max-w-6xl">
            <span>✨</span> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 mb-6 text-xs font-bold text-rose-600 bg-rose-50 rounded-2xl border border-rose-200 flex items-center gap-2 max-w-6xl">
            <span>⚠️</span> {{ session('error') }}
        </div>
    @endif

    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm max-w-6xl">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 mb-6 gap-3">
            <div>
                <h4 class="font-extrabold text-sm text-slate-800 uppercase tracking-wider">Manajemen Transaksi Penyewaan Unit</h4>
                <p class="text-xs text-slate-400 mt-0.5">Pantau berkas masuk, durasi sewa, dan konfirmasi pengembalian barang milik toko lu secara mandiri.</p>
            </div>
        </div>

        <div class="overflow-x-auto rounded-xl border border-slate-200">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[10px] font-bold uppercase tracking-widest text-slate-400 bg-slate-50 border-b border-slate-200">
                        <th class="py-3.5 px-5">Waktu Sewa & Unit</th>
                        <th class="py-3.5 px-5">Unit & Penyewa</th>
                        <th class="py-3.5 px-5">Nomor WhatsApp</th>
                        <th class="py-3.5 px-5">Status</th>
                        <th class="py-3.5 px-5 text-center w-52">Tindakan Merchant</th>
                    </tr>
                </thead>
                <tbody class="text-xs font-medium text-slate-600 divide-y divide-slate-100">
                    @forelse($rentals as $rental)
                        <tr class="hover:bg-slate-50/40 transition">
                            <td class="py-4 px-5">
                                <span class="text-slate-400 text-[10px] block">Diajukan: {{ date('d M Y, H:i', strtotime($rental->created_at)) }}</span>
                                <span class="text-slate-800 font-bold block mt-1">Mulai: {{ date('d M Y', strtotime($rental->created_at)) }}</span>
                                <span class="text-rose-600 font-bold block">Selesai: {{ $rental->expired_at ? date('d M Y', strtotime($rental->expired_at)) : '-' }}</span>
                            </td>
                            <td class="py-4 px-5">
                                <div class="flex items-center gap-3">
                                    
                                    <!-- FIX JALUR GAMBAR: Menggunakan logika deteksi string folder terisolasi untuk merchant -->
                                    <img src="{{ (str_starts_with($rental->item_image, '/storage/') || str_starts_with($rental->item_image, 'storage/')) ? asset(ltrim($rental->item_image, '/')) : asset('storage/' . $rental->item_image) }}" 
                                         alt="{{ $rental->item_name }}" 
                                         class="w-10 h-10 rounded-lg object-cover border border-slate-200 bg-slate-50">
                                    
                                    <div>
                                        <p class="font-black text-slate-800 leading-tight">{{ $rental->customer_name }}</p>
                                        <span class="text-[10px] text-slate-400 font-bold uppercase">Unit: {{ $rental->item_name }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-5 font-mono text-[11px] font-semibold text-slate-700">{{ $rental->whatsapp_number }}</td>
                            <td class="py-4 px-5">
                                @if($rental->status === 'returned')
                                    <span class="px-2 py-0.5 rounded-md text-[9px] font-bold uppercase bg-blue-50 text-blue-600 border border-blue-100">Returned</span>
                                @elseif($rental->status === 'approved')
                                    @if(isset($rental->expired_at) && strtotime($rental->expired_at) > time())
                                        <div class="flex flex-col gap-1">
                                            <span class="px-2 py-0.5 rounded-md text-[9px] font-bold uppercase bg-emerald-50 text-emerald-600 border border-emerald-100 w-fit">Approved</span>
                                            <span id="countdown-{{ $rental->id }}" class="bg-blue-50 text-blue-600 font-mono px-2 py-0.5 rounded text-[9px] font-bold w-fit mt-1">
                                                Menghitung...
                                            </span>
                                        </div>
                                        <script>
                                            (function() {
                                                const rawDateStr = "{{ $rental->expired_at }}".replace(" ", "T") + "+07:00";
                                                const countDownDate = new Date(rawDateStr).getTime();
                                                
                                                const timer = setInterval(function() {
                                                    const now = new Date().getTime();
                                                    const distance = countDownDate - now;

                                                    if (distance < 0) {
                                                        clearInterval(timer);
                                                        document.getElementById("countdown-{{ $rental->id }}").innerHTML = "WAKTU HABIS";
                                                        document.getElementById("countdown-{{ $rental->id }}").className = "bg-rose-50 text-rose-600 font-mono px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider w-fit mt-1 border border-rose-100";
                                                    } else {
                                                        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                                                        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                                        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                                        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                                                        let output = "";
                                                        if (days > 0) output += days + "h ";
                                                        output += hours + "j " + minutes + "m " + seconds + "s";
                                                        
                                                        document.getElementById("countdown-{{ $rental->id }}").innerHTML = "Sisa: " + output;
                                                    }
                                                }, 1000);
                                            })();
                                        </script>
                                    @else
                                        <span class="px-2 py-0.5 rounded-md text-[9px] font-bold uppercase bg-rose-50 text-rose-600 border border-rose-100">Expired</span>
                                    @endif
                                @elseif($rental->status === 'declined')
                                    <span class="px-2 py-0.5 rounded-md text-[9px] font-bold uppercase bg-rose-50 text-rose-600 border border-rose-100">Declined</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-md text-[9px] font-bold uppercase bg-amber-50 text-amber-600 border border-amber-100">Pending</span>
                                @endif
                            </td>
                            <td class="py-4 px-5 text-center">
                                @if($rental->status === 'pending')
                                    <!-- FIX ACTION: Menampilkan tombol Approve & Decline khusus untuk Akun Merchant -->
                                    <div class="flex items-center justify-center gap-2">
                                        <form method="POST" action="{{ route('merchant.rentals.approve', $rental->id) }}">
                                            @csrf
                                            <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-[10px] px-3 py-1.5 rounded-lg uppercase tracking-wider shadow-sm transition cursor-pointer border-0">
                                                Approve
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('merchant.rentals.decline', $rental->id) }}" onsubmit="return confirm('Yakin ingin menolak pengajuan sewa unit ini?')">
                                            @csrf
                                            <button type="submit" class="bg-rose-600 hover:bg-rose-500 text-white font-bold text-[10px] px-3 py-1.5 rounded-lg uppercase tracking-wider shadow-sm transition cursor-pointer border-0">
                                                Decline
                                            </button>
                                        </form>
                                    </div>

                                @elseif($rental->status === 'approved')
                                    <form method="POST" action="{{ route('merchant.rentals.returned', $rental->id) }}" onsubmit="return confirm('Konfirmasi bahwa unit barang sewa ini sudah kembali dengan aman?')">
                                        @csrf
                                        <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-bold text-[10px] px-3 py-1.5 rounded-lg uppercase tracking-wider shadow-sm transition cursor-pointer border-0">
                                            Selesai Sewa
                                        </button>
                                    </form>

                                @elseif($rental->status === 'returned')
                                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">✅ Unit Kembali</span>
                                
                                @elseif($rental->status === 'declined')
                                    <span class="text-[10px] text-rose-400 font-bold uppercase tracking-wider block">❌ Order Ditolak</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-16 text-center text-slate-400">
                                <p class="font-bold text-xs uppercase tracking-tight">Katalog order lu masih kosong, Bree.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection