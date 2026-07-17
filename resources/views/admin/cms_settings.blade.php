@extends('admin.master')

@section('content')
    @if(session('success'))
        <div class="p-4 mb-6 text-xs font-bold text-emerald-600 bg-emerald-50 rounded-2xl border border-emerald-200 flex items-center gap-2 max-w-5xl">
            <span>✨</span> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm w-full max-w-5xl">
        <form method="POST" action="{{ route('admin.cms.update') }}" enctype="multipart/form-data" class="flex flex-col h-full justify-between">
            @csrf
            
            <div class="space-y-6">
                <div class="pb-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h4 class="font-extrabold text-xs text-slate-800 uppercase tracking-wider">Landing Page Configurations</h4>
                        <p class="text-[11px] text-slate-400 font-medium mt-1">Ubah teks elemen landing page publik secara real-time dari formulir di bawah ini.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Nama Aplikasi / Brand</label>
                        <input type="text" name="app_name" value="{{ $configs['app_name'] ?? 'DAY-RENT' }}" 
                        class="w-full mt-1 bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:border-blue-600 focus:bg-white outline-none transition h-[42px]">
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Teks Tombol Utama Hero</label>
                        <input type="text" name="hero_button_text" value="{{ $configs['hero_button_text'] ?? 'MULAI SEKARANG' }}" 
                        class="w-full mt-1 bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:border-blue-600 focus:bg-white outline-none transition h-[42px]">
                    </div>

                    <div class="flex flex-col gap-1.5 md:col-span-2">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Judul Utama (Hero Title)</label>
                        <input type="text" name="hero_title" value="{{ $configs['hero_title'] ?? '' }}" 
                        class="w-full mt-1 bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:border-blue-600 focus:bg-white outline-none transition h-[42px]">
                    </div>

                    <div class="flex flex-col gap-1.5 md:col-span-2">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Subjudul Pendukung (Hero Subtitle)</label>
                        <textarea name="hero_subtitle" rows="3" 
                        class="w-full mt-1 bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:border-blue-600 focus:bg-white outline-none transition resize-none leading-relaxed"></textarea>
                    </div>

                    <div class="flex flex-col gap-1.5 md:col-span-2 border-t border-slate-100 pt-5">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Custom Background Hero Section</label>
                        <input type="file" name="hero_bg_image" 
                        class="w-full mt-1 bg-slate-50/50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-600 file:mr-3 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 outline-none transition h-[42px] cursor-pointer">
                        <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider mt-1">*Format wajib: .jpg, .jpeg, .png (Maksimal 3MB)</p>

                        @if(!empty($configs['hero_bg_image']))
                            <div class="mt-3 p-3 bg-slate-50 rounded-xl border border-slate-200/60 max-w-xs">
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-1.5">Background Aktif:</span>
                                <img src="{{ asset('storage/' . $configs['hero_bg_image']) }}" class="h-20 w-full object-cover rounded-lg border border-slate-200 shadow-sm">
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="p-4 mt-3 text-xs font-bold text-rose-700 bg-rose-50 rounded-xl border border-rose-200 flex flex-col gap-1">
                                <span class="flex items-center gap-2">⚠️ Gagal Memperbarui Tampilan:</span>
                                <ul class="list-disc pl-5 text-[11px] font-semibold text-rose-600 mt-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                    <!-- Upload Logo Aplikasi -->
                    <div class="flex flex-col gap-1.5 md:col-span-2 border-t border-slate-100 pt-5">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Custom Logo Aplikasi (Navbar & Footer)</label>
                        <input type="file" name="app_logo" 
                        class="w-full mt-1 bg-slate-50/50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-600 file:mr-3 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 outline-none transition h-[42px] cursor-pointer">
                        <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider mt-1">*Format wajib: .png, .jpg, .jpeg (Rekomendasi rasio 1:1 transparan, Maksimal 2MB)</p>

                        @if(!empty($configs['app_logo']))
                            <div class="mt-3 p-3 bg-slate-50 rounded-xl border border-slate-200/60 max-w-xs flex items-center gap-4">
                                <div>
                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-1.5">Logo Aktif:</span>
                                    <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center overflow-hidden border border-slate-200 shadow-sm p-1">
                                        <img src="{{ asset('storage/' . $configs['app_logo']) }}" class="w-full h-full object-contain">
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="flex flex-col gap-3 md:col-span-2 border-t border-slate-100 pt-5">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Custom Warna Background Slider Promo (Solid Gradient)</label>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="flex flex-col gap-1.5">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Warna Awal (Kiri)</span>
                                <input type="color" name="slider_bg_color_start" value="{{ $configs['slider_bg_color_start'] ?? '#0B132B' }}" class="w-full h-10 bg-slate-50 p-1 rounded-xl border border-slate-200 cursor-pointer">
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Warna Akhir (Kanan)</span>
                                <input type="color" name="slider_bg_color_end" value="{{ $configs['slider_bg_color_end'] ?? '#1C2541' }}" class="w-full h-10 bg-slate-50 p-1 rounded-xl border border-slate-200 cursor-pointer">
                            </div>
                        </div>
                        <p class="text-[10px] text-slate-400 font-medium">Warna ini akan menjadi latar belakang blok melintang di belakang card slider aktif depan.</p>
                    </div>
                </div>
            </div>

            <div class="pt-5 border-t border-slate-100 mt-6 flex justify-end gap-2 flex-shrink-0">
                <button type="reset" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-400 hover:text-slate-600 hover:bg-slate-50 transition cursor-pointer border-0 bg-transparent uppercase tracking-wider">
                    Reset
                </button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs px-6 py-2.5 rounded-xl shadow-sm transition cursor-pointer border-0 uppercase tracking-wider">
                    Simpan Konfigurasi
                </button>
            </div>
        </form>
    </div>
@endsection