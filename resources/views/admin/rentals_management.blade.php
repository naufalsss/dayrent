@extends('admin.master')

@section('content')
    <style>
        .finset-card {
            border-radius: 24px !important;
            border: 1px solid #e2e8f0 !important;
            background-color: #ffffff !important;
        }
    </style>

    @if(session('success'))
        <div class="p-4 mb-4 text-sm font-bold text-emerald-700 bg-emerald-50 rounded-2xl border border-emerald-200">
            {{ session('success') }}
        </div>
    @endif

    <div class="finset-card p-6 shadow-sm flex flex-col overflow-hidden">
        <div class="pb-4 border-b border-slate-100 mb-5">
            <h4 class="font-extrabold text-base text-slate-900">Manajemen Transaksi Penyewaan</h4>
            <p class="text-[11px] text-slate-400 font-medium mt-0.5">Pantau data customer masuk, waktu transaksi, dan kelola keputusan persetujuan berkas sewa.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-xs font-bold text-slate-400 uppercase tracking-wider">
                        <th class="pb-3 pl-2 w-64">Waktu Sewa & Durasi</th>
                        <th class="pb-3">Unit & Penyewa</th>
                        <th class="pb-3">Nomor WhatsApp</th>
                        <th class="pb-3 text-center">Status</th>
                        <th class="pb-3 text-center">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="text-sm font-medium text-slate-700 divide-y divide-slate-50">
                    @forelse($rentals as $rental)
                        <tr>
                            <td class="py-4 pl-2 text-xs text-slate-500">
                                <div class="font-bold text-slate-700">Mulai: {{ date('d M Y, H:i', strtotime($rental->created_at)) }} WIB</div>
                                @if(isset($rental->expired_at))
                                    <div class="text-[11px] text-rose-600 font-semibold mt-0.5">Selesai: {{ date('d M Y, H:i', strtotime($rental->expired_at)) }} WIB</div>
                                    <div class="text-[10px] text-slate-400 mt-0.5">Durasi sewa: {{ $rental->duration }} Berbasis Waktu</div>
                                    
                                    <div class="mt-2">
                                        <span id="countdown-{{ $rental->id }}" class="bg-blue-50 text-blue-600 font-mono px-2 py-1 rounded text-[10px] font-bold">
                                            Menghitung...
                                        </span>
                                    </div>

                                    <script>
                                        (function() {
                                            // Ubah spasi menjadi 'T' dan tambahkan +07:00 (WIB offset) agar dibaca akurat oleh browser lokal
                                            const rawDateStr = "{{ $rental->expired_at }}".replace(" ", "T") + "+07:00";
                                            const countDownDate = new Date(rawDateStr).getTime();
                                            
                                            const timer = setInterval(function() {
                                                const now = new Date().getTime();
                                                const distance = countDownDate - now;

                                                if (distance < 0) {
                                                    clearInterval(timer);
                                                    document.getElementById("countdown-{{ $rental->id }}").innerHTML = "MASA SEWA HABIS";
                                                    document.getElementById("countdown-{{ $rental->id }}").className = "bg-rose-50 text-rose-600 font-mono px-2 py-1 rounded text-[10px] font-bold";
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
                                @else
                                    <span class="text-slate-400 italic">Data durasi belum tercatat</span>
                                @endif
                            </td>
                            
                            <td class="py-4">
                                <span class="font-bold text-slate-800">{{ $rental->customer_name }}</span>
                                <div class="text-[11px] text-slate-400 font-normal">Unit: {{ $rental->item_name }}</div>
                            </td>
                            <td class="py-4 text-slate-600 font-semibold">{{ $rental->whatsapp_number }}</td>
                            <td class="py-4 text-center">
                                @if($rental->status === 'approved')
                                    <span class="bg-emerald-50 text-emerald-600 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide border border-emerald-200">Approved</span>
                                @elseif($rental->status === 'declined')
                                    <span class="bg-rose-50 text-rose-600 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide border border-rose-200">Declined</span>
                                @elseif($rental->status === 'expired')
                                    <span class="bg-slate-100 text-slate-600 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide border border-slate-200">Expired</span>
                                @else
                                    <span class="bg-amber-50 text-amber-600 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide border border-amber-200">Pending</span>
                                @endif
                            </td>
                            <td class="py-4 text-center">
                                @if($rental->status === 'pending')
                                    <div class="flex items-center justify-center gap-2">
                                        <form method="POST" action="{{ route('admin.rentals.approve', $rental->id) }}">
                                            @csrf
                                            <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 text-white text-[11px] font-bold px-3 py-1.5 rounded-lg shadow-sm transition cursor-pointer border-0">Approve</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.rentals.decline', $rental->id) }}">
                                            @csrf
                                            <button type="submit" class="bg-rose-600 hover:bg-rose-500 text-white text-[11px] font-bold px-3 py-1.5 rounded-lg shadow-sm transition cursor-pointer border-0">Decline</button>
                                        </form>
                                    </div>
                                @else
                                    <form method="POST" action="{{ route('admin.rentals.destroy', $rental->id) }}" onsubmit="return confirm('Apakah kamu yakin ingin menghapus riwayat transaksi ini dari dashboard admin?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 text-xs font-bold px-3 py-1.5 rounded-xl transition cursor-pointer">
                                            Hapus Riwayat
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400 font-semibold text-xs">Belum ada antrean transaksi penyewaan masuk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection