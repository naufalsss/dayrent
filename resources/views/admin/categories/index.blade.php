@extends('admin.master')

@section('content')
    @if(session('success'))
        <div class="p-4 mb-6 text-xs font-bold text-emerald-600 bg-emerald-50 rounded-2xl border border-emerald-200 flex items-center gap-2 max-w-5xl">
            <span>✨</span> {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col gap-6 w-full max-w-5xl">
        
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm">
            <h4 class="font-extrabold text-xs text-slate-800 pb-3 border-b border-slate-100 mb-5 uppercase tracking-wider">
                Tambah Kategori Baru
            </h4>
            
            <form method="POST" action="{{ route('admin.categories.store') }}" class="flex flex-col md:flex-row items-end gap-4">
                @csrf
                <div class="flex flex-col flex-1 w-full">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Nama Kategori</label>
                    <input type="text" name="name" placeholder="Misal: Mobil, Kamera, Motor, Kostum" 
                    class="w-full mt-2 bg-slate-50/50 border border-slate-200/80 rounded-xl px-4 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:border-blue-600 focus:bg-white focus:ring-4 focus:ring-blue-600/5 outline-none transition duration-150" required>
                </div>

                <button type="submit" class="w-full md:w-auto bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs px-6 py-3 rounded-xl shadow-sm transition duration-150 uppercase tracking-wider cursor-pointer border-0 h-[42px] whitespace-nowrap">
                    Simpan Kategori
                </button>
            </form>
        </div>

        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm overflow-hidden">
            <div class="pb-4 border-b border-slate-100 mb-5 flex items-center justify-between">
                <h4 class="font-extrabold text-xs text-slate-800 uppercase tracking-wider">
                    Daftar Kategori Terdaftar
                </h4>
                <span class="text-[10px] font-bold bg-slate-100 text-slate-500 px-3 py-1 rounded-full uppercase tracking-wider">
                    Total: {{ $categories->count() }} Kategori
                </span>
            </div>

            <div class="overflow-x-auto rounded-xl border border-slate-200/60">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-[10px] font-bold uppercase tracking-widest text-slate-400 bg-slate-50 border-b border-slate-200/60">
                            <th class="py-3.5 px-5">Nama Kategori</th>
                            <th class="py-3.5 px-5">Slug URL Otomatis</th>
                            <th class="py-3.5 px-5 text-center w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs font-medium text-slate-600 divide-y divide-slate-100">
                        @forelse($categories as $category)
                            <tr class="hover:bg-slate-50/40 transition duration-100">
                                <td class="py-3.5 px-5 font-bold text-slate-800">{{ $category->name }}</td>
                                <td class="py-3.5 px-5 text-slate-400 font-mono text-[11px] tracking-tight">{{ $category->slug }}</td>
                                <td class="py-3.5 px-5">
                                    <div class="flex justify-center items-center">
                                        <form method="POST" action="{{ route('admin.categories.destroy', $category->id) }}" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-rose-50 hover:bg-rose-100 border border-rose-200/40 text-rose-600 px-3 py-1.5 rounded-lg font-bold transition duration-150 cursor-pointer text-[10px] uppercase tracking-wider border-0">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-20 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center space-y-3 px-4">
                                        <span class="text-4xl opacity-50">📁</span>
                                        <p class="font-extrabold text-slate-700 text-xs tracking-tight uppercase">Belum ada data kategori</p>
                                        <p class="text-[11px] text-slate-400 max-w-xs leading-relaxed font-medium">Silakan isi dan daftarkan nama kategori baru kelompok kalian terlebih dahulu lewat formulir di atas, Bree!</p>
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