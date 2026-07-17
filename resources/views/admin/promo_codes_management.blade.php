@extends('admin.master')

@section('content')
    @if(session('success'))
        <div class="p-4 mb-6 text-xs font-bold text-emerald-600 bg-emerald-50 rounded-2xl border border-emerald-200 flex items-center gap-2 max-w-5xl">
            <span>✨</span> {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col gap-6 w-full max-w-5xl items-stretch">
        
        <!-- BAGIAN 1: FORM INPUT KODE VOUCHER BARU -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm w-full">
            <h4 class="font-extrabold text-xs text-slate-800 pb-3 border-b border-slate-100 mb-5 uppercase tracking-wider">
                Buat Kode Promo Baru
            </h4>
            
            <form method="POST" action="{{ route('admin.promo-codes.store') }}" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 items-start">
                    
                    <div class="flex flex-col">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Kode Voucher</label>
                        <input type="text" name="code" placeholder="Contoh: DAYRENTPRO" 
                        class="w-full mt-2 bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:border-blue-600 focus:bg-white outline-none transition h-[42px] uppercase font-bold" required>
                    </div>

                    <div class="flex flex-col">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Tipe Potongan</label>
                        <select name="type" class="w-full mt-2 bg-slate-50/50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:border-blue-600 focus:bg-white outline-none transition h-[42px] cursor-pointer font-medium" required>
                            <option value="percentage">Persentase (%)</option>
                            <option value="nominal">Nominal Tunai (Rp)</option>
                        </select>
                    </div>

                    <div class="flex flex-col">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Nilai Potongan</label>
                        <input type="number" name="reward_value" placeholder="Misal: 10 atau 50000" 
                        class="w-full mt-2 bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:border-blue-600 focus:bg-white outline-none transition h-[42px]" required>
                    </div>

                    <div class="flex flex-col">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Kuota Limit Pemakaian</label>
                        <input type="number" name="max_uses" value="100" min="1" 
                        class="w-full mt-2 bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:border-blue-600 focus:bg-white outline-none transition h-[42px]" required>
                    </div>

                    <div class="flex flex-col">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Tanggal Kedaluwarsa</label>
                        <input type="date" name="expired_at" 
                        class="w-full mt-2 bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-2 text-xs text-slate-600 focus:border-blue-600 focus:bg-white outline-none transition h-[42px] cursor-pointer" required>
                    </div>

                </div>

                <div class="flex justify-end pt-4 border-t border-slate-100">
                    <button type="submit" class="w-full md:w-auto md:min-w-[240px] bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs py-3 rounded-xl shadow-sm transition duration-150 uppercase tracking-wider flex items-center justify-center gap-2 cursor-pointer border-0 h-[44px]">
                        Simpan Voucher Promo
                    </button>
                </div>
            </form>
        </div>

        <!-- BAGIAN 2: MONITORING TABEL LIVE DATA VOUCHER -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm w-full overflow-hidden">
            <h4 class="font-extrabold text-xs text-slate-800 pb-3 border-b border-slate-100 mb-5 uppercase tracking-wider">
                Daftar Voucher Aktif Terdaftar
            </h4>

            <div class="overflow-x-auto rounded-xl border border-slate-200">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-[10px] font-bold uppercase tracking-widest text-slate-400 bg-slate-50 border-b border-slate-200">
                            <th class="py-3.5 px-5">Kode Voucher</th>
                            <th class="py-3.5 px-5">Tipe &amp; Nilai Potongan</th>
                            <th class="py-3.5 px-5 text-center">Pemakaian Kuota</th>
                            <th class="py-3.5 px-5">Tanggal Expired</th>
                            <th class="py-3.5 px-5 text-center w-40">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs font-medium text-slate-600 divide-y divide-slate-100">
                        @forelse($promos as $p)
                            <tr class="hover:bg-slate-50/40 transition duration-100">
                                <td class="py-3 px-5 font-bold text-blue-600 uppercase tracking-wide">
                                    {{ $p->code }}
                                </td>
                                <td class="py-3 px-5">
                                    @if($p->type === 'percentage')
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-violet-50 text-violet-600 border border-violet-200">
                                            {{ (int)$p->reward_value }}% OFF
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-emerald-50 text-emerald-600 border border-emerald-200">
                                            Rp {{ number_format($p->reward_value, 0, ',', '.') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-5 text-center font-mono">
                                    <span class="text-slate-800 font-bold">{{ $p->total_used }}</span> <span class="text-slate-300">/</span> <span class="text-slate-400">{{ $p->max_uses }}</span>
                                </td>
                                <td class="py-3 px-5 text-slate-500 font-semibold">
                                    {{ date('d M Y', strtotime($p->expired_at)) }}
                                </td>
                                <td class="py-3 px-5">
                                    <div class="flex items-center justify-center gap-2">
                                        <form method="POST" action="{{ route('admin.promo-codes.destroy', $p->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus voucher ini dari sistem?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-600 px-3 py-1.5 rounded-lg font-bold transition cursor-pointer text-[10px] uppercase tracking-wider border-0">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-16 text-center text-slate-400 font-medium">Belum ada data kode promo aktif di sistem. Silakan tambahkan kupon promo sewa melalui form di atas, Bree!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection