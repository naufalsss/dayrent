@extends('admin.master')

@section('content')
    <style>
        .finset-card-form {
            border-radius: 24px !important;
            border: 1px solid #e2e8f0 !important;
            background-color: #ffffff !important;
        }
        .input-finset {
            background-color: #f8fafc !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 12px !important;
            padding: 10px 14px !important;
            font-size: 13px !important;
            font-weight: 500 !important;
            color: #1e293b !important;
            transition: all 0.2s ease !important;
        }
        .input-finset:focus {
            border-color: #7c3aed !important;
            background-color: #ffffff !important;
            box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.1) !important;
            outline: none !important;
        }
    </style>

    @if(session('success'))
        <div class="p-4 mb-2 text-sm font-bold text-emerald-700 bg-emerald-50 rounded-2xl border border-emerald-200 flex items-center gap-2 animate-pulse">
            <span>✅</span> {{ session('success') }}
        </div>
    @endif

    <div class="finset-card-form p-6 shadow-sm flex-1 flex flex-col justify-between overflow-hidden">
        <form method="POST" action="{{ route('admin.cms.update') }}" enctype="multipart/form-data" class="flex flex-col h-full justify-between">
            @csrf
            
            <div class="space-y-6">
                <div class="pb-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h4 class="font-extrabold text-base text-slate-900">Landing Page Configurations</h4>
                        <p class="text-[11px] text-slate-400 font-medium mt-0.5">Ubah teks element landing page publik secara real-time dari formulir di bawah ini.</p>
                    </div>
                    <span class="text-xl">⚙️</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Nama Aplikasi / Brand</label>
                        <input type="text" name="app_name" value="{{ $configs['app_name'] ?? 'DAY-RENT' }}" class="input-finset">
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Teks Tombol Utama Hero</label>
                        <input type="text" name="hero_button_text" value="{{ $configs['hero_button_text'] ?? 'MULAI SEKARANG' }}" class="input-finset">
                    </div>

                    <div class="flex flex-col gap-1.5 md:col-span-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Judul Utama (Hero Title)</label>
                        <input type="text" name="hero_title" value="{{ $configs['hero_title'] ?? '' }}" class="input-finset">
                    </div>

                    <div class="flex flex-col gap-1.5 md:col-span-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Subjudul Pendukung (Hero Subtitle)</label>
                        <textarea name="hero_subtitle" rows="3" class="input-finset resize-none">{{ $configs['hero_subtitle'] ?? '' }}</textarea>
                    </div>

                    <div class="flex flex-col gap-1.5 md:col-span-2 border-t border-slate-50 pt-4">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Custom Background Hero Section</label>
                        <input type="file" name="hero_bg_image" class="input-finset file:mr-4 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100 cursor-pointer">
                        <p class="text-[10px] text-slate-400 font-medium">Format wajib: .jpg, .jpeg, .png (Maksimal ukuran file 3MB)</p>

                        @if(!empty($configs['hero_bg_image']))
                            <div class="mt-2 p-3 bg-slate-50 rounded-2xl border border-slate-100 max-w-md">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1.5">Background Aktif Saat Ini:</span>
                                <img src="{{ asset('storage/' . $configs['hero_bg_image']) }}" class="h-24 w-full object-cover rounded-xl border border-slate-200/60 shadow-sm">
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="p-4 mb-4 text-sm font-bold text-rose-700 bg-rose-50 rounded-2xl border border-rose-200 flex flex-col gap-1">
                                <span class="flex items-center gap-2">⚠️ Gagal Memperbarui Tampilan:</span>
                                <ul class="list-disc pl-5 text-xs font-semibold text-rose-600 mt-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                    <div class="flex flex-col gap-3 md:col-span-2 border-t border-slate-50 pt-4">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Custom Warna Background Slider Promo (Solid Gradient)</label>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="flex flex-col gap-1.5">
                                <span class="text-[11px] font-bold text-slate-400 uppercase">Warna Awal (Kiri)</span>
                                <input type="color" name="slider_bg_color_start" value="{{ $configs['slider_bg_color_start'] ?? '#0B132B' }}" class="w-full h-11 bg-slate-50 p-1 rounded-xl border border-slate-200 cursor-pointer">
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <span class="text-[11px] font-bold text-slate-400 uppercase">Warna Akhir (Kanan)</span>
                                <input type="color" name="slider_bg_color_end" value="{{ $configs['slider_bg_color_end'] ?? '#1C2541' }}" class="w-full h-11 bg-slate-50 p-1 rounded-xl border border-slate-200 cursor-pointer">
                            </div>
                        </div>
                        <p class="text-[10px] text-slate-400 font-medium">Warna ini akan menjadi latar belakang blok melintang di belakang card slider aktif depan.</p>
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100 mt-8 flex justify-end gap-3 flex-shrink-0">
                <button type="reset" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-400 hover:bg-slate-50 transition cursor-pointer">
                    Reset Perubahan
                </button>
                <button type="submit" class="bg-violet-600 hover:bg-violet-500 text-white font-bold text-xs px-6 py-2.5 rounded-xl shadow-md shadow-violet-600/25 transition cursor-pointer">
                    Simpan Konfigurasi 💾
                </button>
            </div>
        </form>
    </div>
@endsection