<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $configs['app_name'] ?? 'Day-Rent' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite('resources/css/app.css')
</head>
<body class="bg-slate-900 text-white min-h-screen relative overflow-x-hidden" style="font-family: 'Poppins', sans-serif;">

    <div class="absolute top-0 left-0 w-full h-[640px] z-0 pointer-events-none overflow-hidden bg-cover bg-center bg-no-repeat"
         style="background-image: linear-gradient(to bottom, rgba(15, 23, 42, 0.45) 0%, rgba(15, 23, 42, 0.92) 80%, #0f172a 100%), url('{{ (isset($configs['hero_bg_image']) && !empty($configs['hero_bg_image'])) ? asset('storage/' . $configs['hero_bg_image']) : asset('images/default_hero_bg.jpg') }}');">
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 relative z-10">
            
            <nav id="mainNavbar" class="sticky top-6 z-50 transition-all duration-300 backdrop-blur-md bg-white/10 border border-white/20 rounded-2xl px-6 py-4 flex items-center justify-between shadow-lg">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center font-bold text-white text-lg">
                        {{ substr($configs['app_name'] ?? 'D', 0, 1) }}
                    </div>
                    <span class="font-extrabold text-xl tracking-wider text-white">{{ $configs['app_name'] ?? 'DAY-RENT' }}</span>
                </div>

                <div class="hidden md:flex items-center gap-8 font-medium">
                    <a href="/" class="text-white border-b-2 border-blue-500 pb-1">Beranda</a>
                    <a href="/catalog" class="text-slate-300 hover:text-white transition">Katalog</a>
                    <a href="/bantuan" class="text-slate-300 hover:text-white transition">Bantuan</a>
                </div>

                <div class="flex items-center gap-4">
                    @auth
                        @php
                            // Ambil notifikasi aktif milik user yang belum dinilai rating-nya
                            $myNotifications = DB::table('notifications')
                                ->where('user_id', auth()->id())
                                ->where('is_rated', false)
                                ->orderBy('created_at', 'desc')
                                ->get();
                        @endphp

                        <div class="relative">
                            <button onclick="toggleNotifDropdown()" id="notifButton" class="relative backdrop-blur-md bg-white/5 border border-white/10 p-2.5 rounded-xl hover:bg-white/10 hover:border-white/20 transition cursor-pointer flex items-center justify-center group border-0">
    
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="w-5 h-5 group-hover:scale-105 transition duration-200">
                                    <path d="M224 512c35.32 0 63.97-28.65 63.97-64H160.03c0 35.35 28.65 64 63.97 64zm215.39-149.71c-19.32-20.76-55.47-51.99-55.47-154.29 0-77.7-54.48-139.9-127.94-155.16V32c0-17.67-14.32-32-31.98-32s-31.98 14.33-31.98 32v20.84C118.56 68.1 64.08 130.3 64.08 208c0 102.3-36.15 133.53-55.47 154.29-6 6.45-8.66 14.16-8.61 21.71.11 16.4 12.98 32 32.1 32h383.8c19.12 0 32-15.6 32.1-32 .05-7.55-2.61-15.27-8.61-21.71z" id="id_101" style="fill: rgb(255, 255, 255);"></path>
                                </svg>

                                @if($myNotifications->count() > 0)
                                    <span class="absolute top-0 right-0 block h-4 w-4 transform translate-x-1/3 -translate-y-1/3 rounded-full bg-rose-500 border-2 border-slate-900 text-[9px] font-black flex items-center justify-center text-white">
                                        {{ $myNotifications->count() }}
                                    </span>
                                @endif
                            </button>

                            <div id="notifDropdownCard" class="absolute right-0 mt-3 w-72 backdrop-blur-lg bg-slate-950/95 border border-white/10 rounded-2xl shadow-2xl p-3 hidden z-50 transition-all duration-200 space-y-2 max-h-80 overflow-y-auto">
                                <h4 class="text-xs font-bold uppercase tracking-wider text-blue-400 px-1 pb-1 border-b border-white/5">Pemberitahuan Penyewaan</h4>
                                
                                @forelse($myNotifications as $notif)
                                    <div class="p-2.5 rounded-xl bg-white/5 border border-white/5 space-y-2 text-left">
                                        <p class="text-[11px] font-black text-amber-400 leading-none">{{ $notif->title }}</p>
                                        <p class="text-[10px] text-slate-300 leading-tight">{{ $notif->message }}</p>
                                        <button onclick="openRatingModal({{ $notif->id }}, '{{ $notif->title }}')" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold text-[9px] py-1.5 rounded-lg transition uppercase tracking-wider cursor-pointer border-0">
                                            Beri Bintang Rating ⭐
                                        </button>
                                    </div>
                                @empty
                                    <p class="text-[10px] text-slate-500 text-center py-4 font-medium">Belum ada notifikasi baru untuk Anda.</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="relative">
                            <div onclick="toggleUserDropdown()" id="userMenuButton" class="flex items-center gap-3 bg-white/5 border border-white/10 p-1.5 pr-4 rounded-full cursor-pointer hover:bg-white/10 transition duration-200 select-none">
                                <img class="h-9 w-9 rounded-full object-cover border-2 border-blue-500" 
                                    src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=0D8ABC&color=fff" 
                                    alt="User avatar">
                                <div class="text-left leading-tight hidden sm:block">
                                    <p class="text-xs font-semibold text-white">{{ auth()->user()->name }}</p>
                                    <p class="text-[10px] text-slate-400 capitalize">{{ auth()->user()->role }} Account</p>
                                </div>
                                <span class="text-xs text-slate-400">▼</span>
                            </div>

                            <div id="userDropdownCard" class="absolute right-0 mt-3 w-56 backdrop-blur-lg bg-slate-950/95 border border-white/10 rounded-2xl shadow-2xl p-2 hidden z-50 transition-all duration-200 text-left">
                                <div class="px-3 py-2 border-b border-white/5 mb-1 sm:hidden">
                                    <p class="text-xs font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                                    <p class="text-[10px] text-slate-400 truncate">{{ auth()->user()->email }}</p>
                                </div>

                                @if(auth()->user()->role === 'admin')
                                    <a href="/admin/rentals" class="flex items-center gap-2 px-3 py-2.5 text-xs font-semibold text-slate-200 hover:text-white hover:bg-white/5 rounded-xl transition">
                                        Dashboard
                                    </a>
                                @else
                                    <a href="/profile/complete" class="flex items-center gap-2 px-3 py-2.5 text-xs font-semibold text-slate-200 hover:text-white hover:bg-white/5 rounded-xl transition">
                                        Account
                                    </a>
                                    <a href="/history-order" class="flex items-center gap-2 px-3 py-2.5 text-xs font-semibold text-slate-200 hover:text-white hover:bg-white/5 rounded-xl transition">
                                        History Order
                                    </a>
                                @endif

                                <div class="border-t border-white/5 mt-1 pt-1">
                                    <form method="POST" action="/logout">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-2 px-3 py-2.5 text-xs font-bold text-rose-400 hover:text-rose-300 hover:bg-rose-500/10 rounded-xl transition text-left cursor-pointer border-0 bg-transparent">
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="/login" class="bg-blue-600 hover:bg-blue-500 text-white px-5 py-2 rounded-xl font-semibold shadow-md shadow-blue-600/30 transition duration-200 text-sm">
                            Masuk Akun
                        </a>
                    @endauth
                </div>
            </nav>

            <main class="mt-20 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-7 space-y-6">
                    <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight leading-tight uppercase max-w-2xl">
                        {{ $configs['hero_title'] ?? 'SISTEM RENTAL UNIVERSAL' }}
                    </h1>
                    <p class="text-slate-300 text-base md:text-lg max-w-xl">
                        {{ $configs['hero_subtitle'] ?? 'Kustomisasi deskripsi utama aplikasi web rental kamu dengan sangat mudah di sini.' }}
                    </p>
                    <div class="pt-2">
                        <a href="javascript:void(0)" onclick="jalankanSmoothScroll()" class="inline-flex items-center gap-2 bg-blue-500 hover:bg-blue-400 text-white font-bold px-8 py-3.5 rounded-xl shadow-lg shadow-blue-500/40 transition transform hover:-translate-y-0.5 text-sm tracking-wider uppercase">
                            {{ $configs['hero_button_text'] ?? 'MULAI SEKARANG' }}
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" /></svg>
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-5 flex flex-wrap lg:flex-nowrap gap-4 justify-start lg:justify-end">
                    <div class="backdrop-blur-md bg-white/5 border border-white/10 rounded-2xl p-5 text-center min-w-[110px] sm:min-w-[130px] flex-1">
                        <h3 id="countPelanggan" data-target="1000" class="text-2xl font-bold text-white tracking-tight">0</h3>
                        <p class="text-xs text-slate-400 mt-1 font-medium">Pelanggan</p>
                    </div>
                    <div class="backdrop-blur-md bg-white/5 border border-white/10 rounded-2xl p-5 text-center min-w-[110px] sm:min-w-[130px] flex-1">
                        <h3 id="countBerdiri" data-target="5" class="text-2xl font-bold text-white tracking-tight">0</h3>
                        <p class="text-xs text-slate-400 mt-1 font-medium">Berdiri</p>
                    </div>
                    <div class="backdrop-blur-md bg-white/5 border border-white/10 rounded-2xl p-5 text-center min-w-[110px] sm:min-w-[130px] flex-1">
                        <h3 class="text-2xl font-bold text-white tracking-tight flex items-center justify-center gap-1">
                            <span id="countRating" data-target="4.9">0.0</span>
                            <span class="text-yellow-400 text-xl">★</span>
                        </h3>
                        <p class="text-xs text-slate-400 mt-1 font-medium">Rating</p>
                    </div>
                </div>
            </main>
        </div> 

        <div class="w-full py-16 mt-24 relative overflow-hidden"
             style="background: linear-gradient(to bottom, 
            rgba(15, 23, 42, 0) 0%, 
            rgba(11, 19, 43, 0.4) 15%, 
            {{ $configs['slider_bg_color_start'] ?? '#0B132B' }} 40%, 
            {{ $configs['slider_bg_color_end'] ?? '#1C2541' }} 70%, 
            rgba(28, 37, 65, 0.4) 85%, 
            rgba(15, 23, 42, 0) 100%);">
            
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <section class="relative group">
                    <button id="prevBtn" class="absolute left-[-20px] top-1/2 -translate-y-1/2 z-10 bg-white/10 hover:bg-white/20 text-white border border-white/20 p-3 rounded-full backdrop-blur-md opacity-0 group-hover:opacity-100 transition duration-300 cursor-pointer shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    <div class="overflow-hidden rounded-2xl">
                        <div id="sliderTrack" class="flex transition-transform duration-500 ease-out gap-6">
                            @forelse($promos as $promo)
                                <div class="min-w-[100%] md:min-w-[calc(33.333%-16px)] rounded-2xl shadow-xl flex flex-col justify-between h-44 border border-white/10 relative p-6 group/card overflow-hidden transition duration-300 hover:scale-[1.02]">
                                    <div class="absolute inset-0 z-0 bg-cover bg-center pointer-events-none"
                                         style="background-image: linear-gradient(to right, rgba(15, 23, 42, 0.75) 0%, rgba(15, 23, 42, 0.35) 60%, rgba(15, 23, 42, 0.15) 100%), url('{{ asset('storage/' . $promo->image) }}');">
                                    </div>
                                    <div class="relative z-10">
                                        @if($promo->badge_color === 'purple')
                                            <span class="bg-purple-500/30 text-purple-300 text-[10px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wider border border-purple-500/20">{{ $promo->tag }}</span>
                                        @elseif($promo->badge_color === 'emerald')
                                            <span class="bg-emerald-500/30 text-emerald-400 border border-emerald-500/20 text-[10px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wider">{{ $promo->tag }}</span>
                                        @elseif($promo->badge_color === 'amber')
                                            <span class="bg-amber-500/30 text-amber-300 text-[10px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wider border border-amber-500/20">{{ $promo->tag }}</span>
                                        @elseif($promo->badge_color === 'rose')
                                            <span class="bg-rose-500/30 text-rose-300 text-[10px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wider border border-rose-500/20">{{ $promo->tag }}</span>
                                        @else
                                            <span class="bg-blue-500/30 text-blue-300 text-[10px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wider border border-blue-500/20">{{ $promo->tag }}</span>
                                        @endif
                                        <h4 class="text-base font-bold mt-3 text-white leading-snug tracking-tight drop-shadow-md">{{ $promo->title }}</h4>
                                    </div>
                                    <div class="relative z-10">
                                        <a href="{{ $promo->link_url }}" class="text-xs {{ $promo->badge_color === 'purple' ? 'text-purple-400 hover:text-purple-300' : ($promo->badge_color === 'emerald' ? 'text-emerald-400 hover:text-emerald-300' : ($promo->badge_color === 'amber' ? 'text-amber-400 hover:text-amber-300' : ($promo->badge_color === 'rose' ? 'text-rose-400 hover:text-rose-300' : 'text-blue-400 hover:text-blue-300'))) }} flex items-center gap-1 font-semibold group-hover/card:translate-x-1 transition duration-200">
                                            {{ $promo->link_text }} <span class="text-sm">→</span>
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="min-w-[100%] md:min-w-[calc(33.333%-16px)] backdrop-blur-md bg-white/5 border border-white/10 p-6 rounded-2xl shadow-xl flex flex-col justify-center items-center h-44 text-center w-full">
                                    <span class="text-2xl mb-1">📢</span>
                                    <h4 class="text-sm font-bold text-slate-300">Untuk saat ini promo belum tersedia</h4>
                                    <p class="text-xs text-slate-500 mt-1">Pantau terus halaman ini untuk penawaran menarik berikutnya!</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <button id="nextBtn" class="absolute right-[-20px] top-1/2 -translate-y-1/2 z-10 bg-white/10 hover:bg-white/20 text-white border border-white/20 p-3 rounded-full backdrop-blur-md opacity-0 group-hover:opacity-100 transition duration-300 cursor-pointer shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </section>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <section id="kategoriSection" class="mt-16 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold tracking-wide uppercase text-white">Jelajahi Kategori Rental</h2>
                    <p class="text-xs text-slate-400 mt-1">Pilih tipe barang untuk memfilter unit yang ingin kamu sewa</p>
                </div>
            </div>

            <div class="flex items-center gap-3 overflow-x-auto pb-4 scrollbar-hide snap-x">
                @foreach($categories as $category)
                    <button onclick="filterCategory('{{ $category['slug'] }}', this)" 
                            class="category-btn snap-center flex flex-col items-center justify-center min-w-[90px] sm:min-w-[110px] p-4 rounded-xl backdrop-blur-md bg-white/5 border border-white/10 hover:bg-white/10 hover:border-blue-500/50 cursor-pointer transition duration-200 group {{ $category['slug'] === 'all' ? 'active-category' : '' }}">
                        <span class="text-xs font-semibold text-slate-300 group-hover:text-white transition tracking-wide">{{ $category['name'] }}</span>
                    </button>
                @endforeach
            </div>
        </section>

        <div class="w-full py-6">
            <div class="max-w-7xl mx-auto">
                <div id="itemsGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-5 items-stretch">
                    @foreach($items as $item)
                        <div class="item-card backdrop-blur-md bg-white/5 border border-white/10 rounded-2xl overflow-hidden shadow-xl hover:border-slate-700 hover:scale-[1.01] transition duration-300 flex flex-col justify-between"
                            data-category="{{ $item->category->slug }}">
                            
                            <div class="relative w-full aspect-square bg-slate-800 overflow-hidden">
                                <img src="{{ asset($item->image) }}" alt="{{ $item->name }}" class="w-full h-full object-cover hover:scale-105 transition duration-500">
                            </div>

                            <div class="p-5 flex-1 flex flex-col justify-between">
                                <div class="flex flex-col h-full justify-between">
                                    <div>
                                        <div class="w-fit bg-violet-500/10 text-violet-400 border border-violet-500/20 px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider">
                                            {{ $item->category->name }}
                                        </div>
                                        <h3 class="text-base font-extrabold text-white leading-snug line-clamp-2 min-h-[48px] tracking-tight pt-2.5">
                                            {{ $item->name }}
                                        </h3>
                                    </div>

                                    <div class="flex items-center justify-between text-[11px] pt-3 mt-3 border-t border-white/5 font-medium">
                                        <div class="flex items-center gap-1 text-amber-400">
                                            <span>⭐</span>
                                            <span class="text-slate-300">
                                                {{ $item->rating > 0 ? number_format($item->rating, 1) : 'New' }}
                                            </span>
                                        </div>
                                        <div class="text-slate-400">
                                            <span>Tersewa:</span>
                                            <span class="text-slate-200 font-bold">{{ $item->total_rented }}</span>
                                        </div>
                                        <div class="text-slate-400">
                                            <span>Stok:</span>
                                            <span class="{{ $item->stock > 0 ? 'text-emerald-400' : 'text-rose-400' }} font-bold">
                                                {{ $item->stock }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="pt-3 mt-3 border-t border-white/5 space-y-3">
                                        <div>
                                            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider mb-0.5">Harga Sewa</p>
                                            <p class="text-base font-extrabold text-white tracking-tight leading-none">
                                                Rp {{ is_numeric(str_replace('.', '', $item->price)) ? number_format(str_replace('.', '', $item->price), 0, ',', '.') : $item->price }}
                                                <span class="text-slate-400 font-normal text-[11px] tracking-normal">/{{ $item->rent_mode ?? 'hari' }}</span>
                                            </p>
                                        </div>

                                        @if($item->stock > 0)
                                            <a href="{{ route('items.checkout', $item->id) }}" 
                                                class="w-full bg-blue-600 hover:bg-blue-500 text-white font-black text-[11px] py-3 rounded-xl shadow-md transition duration-200 cursor-pointer flex items-center justify-center tracking-wider uppercase border-0">
                                                Sewa Sekarang
                                            </a>
                                        @else
                                            <button disabled class="w-full bg-amber-500/10 text-amber-400 border border-amber-500/20 font-black text-[11px] py-3 rounded-xl cursor-not-allowed tracking-wider uppercase flex items-center justify-center">
                                                Currently Being Rented
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div id="noItemsMessage" class="hidden text-center py-12 backdrop-blur-md bg-white/5 border border-white/10 rounded-2xl w-full">
                    <span class="text-4xl">🔍</span>
                    <h3 class="text-base font-bold text-white mt-3">Barang Tidak Ditemukan</h3>
                    <p class="text-xs text-slate-400 mt-1">Belum ada unit yang terdaftar di kategori sewa ini.</p>
                </div>
            </div>
        </div>
    </div>

    <footer class="relative mt-32 border-t border-white/10 bg-slate-950/95 backdrop-blur-md overflow-hidden w-full">
        <div class="absolute inset-0 pointer-events-none overflow-hidden z-0 opacity-40">
            <div class="absolute bottom-[-10px] left-[10%] w-2 h-2 bg-sky-400 rounded-full blur-[1px] animate-float-slow"></div>
            <div class="absolute bottom-[-10px] left-[30%] w-3 h-3 bg-blue-400 rounded-full blur-[1px] animate-float-medium"></div>
            <div class="absolute bottom-[-10px] left-[50%] w-1.5 h-1.5 bg-sky-300 rounded-full animate-float-fast"></div>
            <div class="absolute bottom-[-10px] left-[70%] w-2.5 h-2.5 bg-blue-500 rounded-full blur-[1px] animate-float-slow"></div>
            <div class="absolute bottom-[-10px] left-[90%] w-2 h-2 bg-sky-400 rounded-full animate-float-medium"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 pb-8 border-b border-white/5">
                <div class="md:col-span-4 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center font-bold text-white text-lg">
                            {{ substr($configs['app_name'] ?? 'D', 0, 1) }}
                        </div>
                        <span class="font-extrabold text-xl tracking-wider text-white">{{ $configs['app_name'] ?? 'DAY-RENT' }}</span>
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed max-w-sm">
                        Sistem Informasi Content Management System (CMS) persewaan harian universal terintegrasi otomatis untuk efisiensi bisnis rental Anda.
                    </p>
                </div>

                <div class="hidden md:block md:col-span-2"></div>

                <div class="md:col-span-3 space-y-3">
                    <h4 class="text-xs font-bold uppercase tracking-widest text-blue-400">Navigasi</h4>
                    <ul class="space-y-2 text-xs text-slate-400">
                        <li><a href="/" class="hover:text-white transition">Beranda</a></li>
                        <li><a href="/catalog" class="hover:text-white transition">Katalog Rental</a></li>
                        <li><a href="/bantuan" class="hover:text-white transition">Pusat Bantuan</a></li>
                    </ul>
                </div>

                <div class="md:col-span-3 space-y-3">
                    <h4 class="text-xs font-bold uppercase tracking-widest text-blue-400">Kontak Proyek</h4>
                    <ul class="space-y-2 text-xs text-slate-400">
                        <li class="flex items-center gap-2"><span>📍</span> Universitas Logistik dan Bisnis Internasional</li>
                        <li class="flex items-center gap-2"><span>✉️</span> suppdayrent@gmail.com</li>
                        <li class="flex items-center gap-2"><span>⚡</span> Naufal & Zikmal</li>
                    </ul>
                </div>
            </div>

            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-[11px] text-slate-500 font-medium">
                <p>&copy; 2026 {{ $configs['app_name'] ?? 'DAY-RENT' }}. All rights reserved.</p>
                <div class="flex gap-6">
                    <a href="#" class="hover:text-slate-400 transition">Terms of Service</a>
                    <a href="#" class="hover:text-slate-400 transition">Privacy Policy</a>
                </div>
            </div>
        </div>
    </footer>

    <style>
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        
        .active-category {
            background-color: rgba(59, 130, 246, 0.2) !important;
            border-color: rgb(59, 130, 246) !important;
            box-shadow: 0 4px 20px -2px rgba(59, 130, 246, 0.3);
        }
        .active-category span { color: #ffffff !important; }

        @keyframes floatUp {
            0% { transform: translateY(0) scale(1); opacity: 0.8; }
            80% { opacity: 0.6; }
            100% { transform: translateY(-300px) scale(0.4); opacity: 0; }
        }
        .animate-float-slow { animation: floatUp 8s infinite linear; }
        .animate-float-medium { animation: floatUp 6s infinite linear; animation-delay: 2s; }
        .animate-float-fast { animation: floatUp 4s infinite linear; animation-delay: 1s; }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // NAVBAR SCROLL EFFECT
            const navbar = document.getElementById("mainNavbar");
            window.addEventListener("scroll", function() {
                if (window.scrollY > 20) {
                    navbar.classList.remove("bg-white/10", "border-white/20");
                    navbar.classList.add("bg-slate-950/70", "border-white/10", "backdrop-blur-lg");
                } else {
                    navbar.classList.remove("bg-slate-950/70", "border-white/10", "backdrop-blur-lg");
                    navbar.classList.add("bg-white/10", "border-white/20");
                }
            });

            // COUNTER ANIMATION
            function animateCounters() {
                const duration = 1000;
                const pelangganEl = document.getElementById("countPelanggan");
                const targetPelanggan = parseInt(pelangganEl.getAttribute("data-target"));
                let startPelanggan = 0;
                const stepPelanggan = targetPelanggan / (duration / 16);

                const berdiriEl = document.getElementById("countBerdiri");
                const targetBerdiri = parseInt(berdiriEl.getAttribute("data-target"));
                let startBerdiri = 0;
                const stepBerdiri = targetBerdiri / (duration / 16);

                const ratingEl = document.getElementById("countRating");
                const targetRating = parseFloat(ratingEl.getAttribute("data-target"));
                let startRating = 0;
                const stepRating = targetRating / (duration / 16);

                const timer = setInterval(() => {
                    startPelanggan += stepPelanggan;
                    if (startPelanggan >= targetPelanggan) {
                        pelangganEl.innerText = targetPelanggan + "+";
                    } else {
                        pelangganEl.innerText = Math.floor(startPelanggan) + "+";
                    }

                    startBerdiri += stepBerdiri;
                    if (startBerdiri >= targetBerdiri) {
                        berdiriEl.innerText = targetBerdiri + " Thn";
                    } else {
                        berdiriEl.innerText = Math.floor(startBerdiri) + " Thn";
                    }

                    startRating += stepRating;
                    if (startRating >= targetRating) {
                        ratingEl.innerText = targetRating.toFixed(1);
                    } else {
                        ratingEl.innerText = startRating.toFixed(1);
                    }

                    if (startPelanggan >= targetPelanggan && startBerdiri >= targetBerdiri && startRating >= targetRating) {
                        clearInterval(timer);
                        pelangganEl.innerText = targetPelanggan + "+";
                        berdiriEl.innerText = targetBerdiri + " Thn";
                        ratingEl.innerText = targetRating.toFixed(1);
                    }
                }, 16);
            }
            animateCounters();

            // SLIDER TRACK PROMO
            const track = document.getElementById("sliderTrack");
            const prevBtn = document.getElementById("prevBtn");
            const nextBtn = document.getElementById("nextBtn");
            let currentIndex = 0;
            const totalCards = {{ $promos->count() > 0 ? $promos->count() : 1 }};
            
            function getCardsPerView() { return window.innerWidth >= 768 ? 3 : 1; }

            function updateSlider() {
                const cardsPerView = getCardsPerView();
                const maxIndex = totalCards - cardsPerView;
                if (currentIndex > maxIndex) currentIndex = 0;
                else if (currentIndex < 0) currentIndex = maxIndex;

                const cardWidthPercentage = 100 / cardsPerView;
                track.style.transform = `translateX(calc(-${currentIndex * cardWidthPercentage}% - ${currentIndex === 0 ? 0 : 16}px))`;
            }

            nextBtn.addEventListener("click", () => { currentIndex++; updateSlider(); resetAutoPlay(); });
            prevBtn.addEventListener("click", () => { currentIndex--; updateSlider(); resetAutoPlay(); });

            let autoPlayInterval = setInterval(() => { currentIndex++; updateSlider(); }, 4000);
            function resetAutoPlay() {
                clearInterval(autoPlayInterval);
                autoPlayInterval = setInterval(() => { currentIndex++; updateSlider(); }, 4000);
            }
            window.addEventListener("resize", updateSlider);
        });

        // FILTER KATEGORI JAVASCRIPT
        function filterCategory(slug, element) {
            document.querySelectorAll('.category-btn').forEach(btn => { btn.classList.remove('active-category'); });
            element.classList.add('active-category');
            const cards = document.querySelectorAll('.item-card');
            let visibleCount = 0;

            cards.forEach(card => {
                const cardCategory = card.getAttribute('data-category');
                if (slug === 'all' || cardCategory === slug) {
                    card.style.display = 'block'; 
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            const noItemsMessage = document.getElementById('noItemsMessage');
            if (visibleCount === 0) noItemsMessage.classList.remove('hidden');
            else noItemsMessage.classList.add('hidden');
        }

        // SMOOTH SCROLL
        function jalankanSmoothScroll() {
            const target = document.getElementById("kategoriSection");
            const navbar = document.getElementById("mainNavbar");
            if (target) {
                const targetPosition = target.getBoundingClientRect().top + window.scrollY;
                const navbarHeight = navbar ? navbar.offsetHeight : 90;
                const extraPadding = 20;
                const toPosition = targetPosition - navbarHeight - extraPadding;
                const startPosition = window.scrollY;
                const distance = toPosition - startPosition;
                const duration = 800;
                let startTime = null;

                function easeInOutCubic(t, b, c, d) {
                    t /= d / 2;
                    if (t < 1) return c / 2 * t * t * t + b;
                    t -= 2;
                    return c / 2 * (t * t * t + 2) + b;
                }

                function animationLoop(currentTime) {
                    if (startTime === null) startTime = currentTime;
                    const timeElapsed = currentTime - startTime;
                    const run = easeInOutCubic(timeElapsed, startPosition, distance, duration);
                    window.scrollTo(0, run);
                    if (timeElapsed < duration) requestAnimationFrame(animationLoop);
                    else window.scrollTo(0, toPosition);
                }
                requestAnimationFrame(animationLoop);
            }
        }

        // TOGGLE POPUP DROPDOWN HANDLERS
        window.toggleUserDropdown = function() {
            const dropdown = document.getElementById("userDropdownCard");
            if (dropdown) dropdown.classList.toggle("hidden");
        }

        window.toggleNotifDropdown = function() {
            const dropdown = document.getElementById("notifDropdownCard");
            if (dropdown) dropdown.classList.toggle("hidden");
        }

        window.openRatingModal = function(notifId, title) {
            const modal = document.getElementById("ratingModal");
            const form = document.getElementById("ratingForm");
            if (modal && form) {
                form.action = `/notifications/${notifId}/rate`;
                modal.classList.remove("hidden");
                modal.classList.add("flex");
                document.getElementById("notifDropdownCard").classList.add("hidden");
            }
        }

        window.closeRatingModal = function() {
            const modal = document.getElementById("ratingModal");
            if (modal) {
                modal.classList.add("hidden");
                modal.classList.remove("flex");
            }
        }

        // AUTO CLOSE DROPDOWNS
        document.addEventListener("click", function(event) {
            const uCard = document.getElementById("userDropdownCard");
            const uBtn = document.getElementById("userMenuButton");
            if (uCard && uBtn && !uBtn.contains(event.target) && !uCard.contains(event.target)) {
                uCard.classList.add("hidden");
            }

            const nCard = document.getElementById("notifDropdownCard");
            const nBtn = document.getElementById("notifButton");
            if (nCard && nBtn && !nBtn.contains(event.target) && !nCard.contains(event.target)) {
                nCard.classList.add("hidden");
            }
        });
    </script>

    <div id="ratingModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm hidden items-center justify-center z-[100] p-4 transition-all duration-300">
        <div class="bg-slate-900 border border-white/10 rounded-3xl p-6 max-w-sm w-full shadow-2xl space-y-4 text-center transform scale-95 transition-transform duration-300">
            <div class="w-12 h-12 bg-amber-500/10 text-amber-400 rounded-full flex items-center justify-center text-xl mx-auto font-bold">
                ⭐
            </div>
            <div>
                <h3 class="text-base font-extrabold text-white" id="modalNotifTitle">Beri Penilaian Unit</h3>
                <p class="text-xs text-slate-400 mt-1">Bagaimana pengalaman sewa unit Anda? Feedback Anda sangat membantu tim kelompok kami.</p>
            </div>

            <form id="ratingForm" method="POST" action="" class="space-y-4">
                @csrf
                <div class="flex items-center justify-center gap-3 text-2xl py-2">
                    @for($i = 1; $i <= 5; $i++)
                        <label class="cursor-pointer transition transform hover:scale-110">
                            <input type="radio" name="rating" value="{{ $i }}" class="hidden peer" required>
                            <span class="text-slate-600 peer-checked:text-amber-400 hover:text-amber-300 transition">★</span>
                        </label>
                    @endfor
                </div>

                <div class="flex gap-2 pt-2">
                    <button type="button" onclick="closeRatingModal()" class="flex-1 bg-white/5 hover:bg-white/10 text-slate-300 text-xs font-bold py-3 rounded-xl transition cursor-pointer border-0">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-500 text-white text-xs font-black py-3 rounded-xl transition shadow-md shadow-blue-600/20 cursor-pointer border-0 uppercase tracking-wider">
                        Kirim Rating
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>