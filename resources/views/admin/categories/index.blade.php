@extends('admin.master')

@section('content')
    <style>
        .category-container .premium-white-card {
            border-radius: 24px !important;
            background-color: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02) !important;
            width: 100% !important;
        }
        
        .category-container .input-premium-light {
            background-color: #f8fafc !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 14px !important;
            padding: 12px 16px !important;
            font-size: 13px !important;
            color: #1e293b !important;
            margin-top: 10px !important;
            width: 100% !important;
            transition: all 0.2s ease;
        }
        
        .category-container .input-premium-light:focus {
            border-color: #7c3aed !important;
            background-color: #ffffff !important;
            outline: none !important;
            box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.08) !important;
        }
    </style>

    @if(session('success'))
        <div class="p-4 mb-4 text-sm font-bold text-emerald-700 bg-emerald-50 rounded-2xl border border-emerald-200 flex items-center gap-2 shadow-sm">
            <span>✅</span> {{ session('success') }}
        </div>
    @endif

    <div class="category-container flex flex-col gap-6 w-full">
        
        <div class="premium-white-card p-6">
            <h4 class="font-extrabold text-base text-slate-900 pb-3 border-b border-slate-100 mb-5 flex items-center gap-2">
                <span>➕</span> Tambah Kategori Baru
            </h4>
            
            <form method="POST" action="{{ route('admin.categories.store') }}" class="flex flex-col md:flex-row items-end gap-5">
                @csrf
                <div class="flex flex-col flex-1 w-full">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-wide">Nama Kategori</label>
                    <input type="text" name="name" placeholder="Misal: Mobil, Kamera, Motor, Kostum" class="input-premium-light" required>
                </div>

                <button type="submit" class="w-full md:w-auto bg-violet-600 hover:bg-violet-500 text-white font-bold text-xs px-8 py-3.5 rounded-xl shadow-md shadow-violet-600/25 transition duration-200 uppercase tracking-wider cursor-pointer border-0 h-[48px] whitespace-nowrap">
                    Simpan Kategori 💾
                </button>
            </form>
        </div>

        <div class="premium-white-card p-6 overflow-hidden">
            <div class="pb-4 border-b border-slate-100 mb-6 flex items-center justify-between">
                <h4 class="font-extrabold text-base text-slate-900">
                    📋 Daftar Kategori Terdaftar
                </h4>
                <span class="text-[11px] font-bold bg-slate-100 text-slate-500 px-3 py-1 rounded-full">
                    Total: {{ $categories->count() }} Kategori
                </span>
            </div>

            <div class="overflow-x-auto rounded-2xl overflow-hidden border border-slate-100 bg-slate-50/50">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-[11px] font-bold uppercase tracking-wider text-slate-400 bg-slate-50 border-b border-slate-200/60">
                            <th class="py-4 px-6">Nama Kategori</th>
                            <th class="py-4 px-6">Slug URL Otomatis</th>
                            <th class="py-4 px-6 text-center w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs font-semibold text-slate-600 divide-y divide-slate-100">
                        @forelse($categories as $category)
                            <tr class="hover:bg-white transition duration-150">
                                <td class="py-4 px-6 font-bold text-slate-900">{{ $category->name }}</td>
                                <td class="py-4 px-6 text-slate-400 font-mono text-[11px] tracking-tight">{{ $category->slug }}</td>
                                <td class="py-4 px-6">
                                    <div class="flex justify-center items-center">
                                        <form method="POST" action="{{ route('admin.categories.destroy', $category->id) }}" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-rose-50 hover:bg-rose-100 text-rose-600 px-4 py-2 rounded-xl font-bold transition duration-150 cursor-pointer text-[11px] border-0">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-24 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center space-y-4 px-4">
                                        <span class="text-6xl opacity-75">📁</span>
                                        <p class="font-extrabold text-slate-700 text-sm tracking-tight">Belum ada data kategori yang terdaftar</p>
                                        <p class="text-xs text-slate-400 max-w-sm leading-relaxed font-medium">Silakan isi dan daftarkan nama kategori baru kelompok kalian terlebih dahulu lewat formulir kustom di atas, Bree!</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection