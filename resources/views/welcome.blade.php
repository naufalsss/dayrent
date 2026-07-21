<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $configs['app_name'] ?? 'Day-Rent' }} - Premium Rental Platform</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- DYNAMIC FAVICON -->
    @if(isset($configs['app_logo']) && !empty($configs['app_logo']))
        <link rel="icon" href="{{ asset('storage/' . $configs['app_logo']) }}" type="image/x-icon">
    @else
        <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    @endif
</head>
<body class="bg-slate-950 text-white min-h-screen relative overflow-x-hidden pt-24" style="font-family: 'Poppins', sans-serif;">
    <div class="w-full max-w-full overflow-x-hidden relative">

    <!-- HERO BACKGROUND DARI DASHBOARD ADMIN DENGAN LIS GRADASI FADE MEWAH BARU -->
    <div class="absolute top-0 left-0 w-full h-[740px] z-0 pointer-events-none overflow-hidden bg-cover bg-center bg-no-repeat"
         style="background-image: linear-gradient(to bottom, rgba(15, 23, 42, 0.35) 0%, rgba(15, 23, 42, 0.75) 50%, rgba(15, 23, 42, 0.95) 80%, #0f172a 92%, #020617 100%), url('{{ (isset($configs['hero_bg_image']) && !empty($configs['hero_bg_image'])) ? asset('storage/' . $configs['hero_bg_image']) : asset('images/default_hero_bg.jpg') }}');">
    </div>

    <!-- FIX RADIKAL NAVBAR GLOBAL OUTSIDE HERO INNER CONTAINER -->
    <div class="fixed top-0 inset-x-0 z-50 transition-all duration-300 py-4 px-4 sm:px-6 lg:px-8" id="navbarWrapper">
        <div class="max-w-7xl mx-auto">
            <nav id="mainNavbar" class="transition-all duration-300 backdrop-blur-md bg-white/10 border border-white/20 rounded-md px-6 py-4 flex items-center justify-between shadow-lg">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center font-bold text-white text-lg overflow-hidden p-0.5 flex-shrink-0">
                        @if(!empty($configs['app_logo']))
                            <img src="{{ asset('storage/' . $configs['app_logo']) }}" class="w-full h-full object-contain rounded-full">
                        @else
                            {{ substr($configs['app_name'] ?? 'D', 0, 1) }}
                        @endif
                    </div>
                    <span class="font-extrabold text-sm md:text-base tracking-wider text-white">{{ $configs['app_name'] ?? 'DAY-RENT' }}</span>
                </div>

                <div class="hidden md:flex items-center gap-8 font-medium">
                    <a href="/" class="text-white border-b-2 border-blue-500 pb-1">Beranda</a>
                    <a href="/catalog" class="text-slate-300 hover:text-white transition">Katalog</a>
                    <a href="/guide" class="text-slate-300 hover:text-white transition">Panduan</a>
                    <a href="/help" class="text-slate-300 hover:text-white transition">Bantuan</a>
                </div>

                <div class="flex items-center gap-4">
                    @auth
                        @php
                            $myNotifications = DB::table('notifications')
                                ->where('user_id', auth()->id())
                                ->where('is_rated', false)
                                ->orderBy('created_at', 'desc')
                                ->get();
                        @endphp

                        <!-- Lonceng Notifikasi -->
                        <div class="relative">
                            <button onclick="toggleNotifDropdown()" id="notifButton" class="relative backdrop-blur-md bg-white/5 border border-white/10 p-2.5 rounded-md hover:bg-white/10 hover:border-white/20 transition cursor-pointer flex items-center justify-center group border-0 bg-transparent">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="w-5 h-5 group-hover:scale-105 transition duration-200">
                                    <path d="M224 512c35.32 0 63.97-28.65 63.97-64H160.03c0 35.35 28.65 64 63.97 64zm215.39-149.71c-19.32-20.76-55.47-51.99-55.47-154.29 0-77.7-54.48-139.9-127.94-155.16V32c0-17.67-14.32-32-31.98-32s-31.98 14.33-31.98 32v20.84C118.56 68.1 64.08 130.3 64.08 208c0 102.3-36.15 133.53-55.47 154.29-6 6.45-8.66 14.16-8.61 21.71.11 16.4 12.98 32 32.1 32h383.8c19.12 0 32-15.6 32.1-32 .05-7.55-2.61-15.27-8.61-21.71z" style="fill: rgb(255, 255, 255);"></path>
                                </svg>
                                @if($myNotifications->count() > 0)
                                    <span class="absolute top-0 right-0 block h-4 w-4 transform translate-x-1/3 -translate-y-1/3 rounded-full bg-rose-500 text-[9px] font-black flex items-center justify-center text-white">
                                        {{ $myNotifications->count() }}
                                    </span>
                                @endif
                            </button>

                            <div id="notifDropdownCard" class="absolute right-0 mt-3 w-72 backdrop-blur-lg bg-slate-950/95 border border-white/10 rounded-md shadow-2xl p-3 hidden z-50 transition-all duration-200 space-y-2 max-h-80 overflow-y-auto">
                                <h4 class="text-xs font-bold uppercase tracking-wider text-blue-400 px-1 pb-1 border-b border-white/5">Pemberitahuan Penyewaan</h4>
                                @forelse($myNotifications as $notif)
                                    <div class="p-2.5 rounded-md bg-white/5 border border-white/5 space-y-2 text-left" id="notif-card-{{ $notif->id }}">
                                        <p class="text-[11px] font-black text-amber-400 winding-none">{{ $notif->title }}</p>
                                        <p class="text-[10px] text-slate-300 leading-tight">{{ $notif->message }}</p>
                                        <div class="flex gap-2">
                                            <button onclick="openRatingModal({{ $notif->id }}, '{{ $notif->title }}')" class="flex-1 bg-blue-600 hover:bg-blue-500 text-white font-bold text-[9px] py-1.5 rounded-md transition uppercase tracking-wider cursor-pointer border-0">
                                                Beri Bintang ⭐
                                            </button>
                                            <button onclick="dismissRatingNotif({{ $notif->id }})" class="w-8 flex items-center justify-center bg-white/5 hover:bg-rose-500/20 text-slate-400 hover:text-rose-400 font-bold text-[10px] rounded-md border border-white/10 transition cursor-pointer" title="Abaikan">
                                                ✕
                                            </button>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-[10px] text-slate-500 text-center py-4 font-medium">Belum ada notifikasi baru untuk Anda.</p>
                                @endforelse
                            </div>
                        </div>

                        <!-- Profil Dropdown Menu -->
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

                            <div id="userDropdownCard" class="absolute right-0 mt-3 w-56 backdrop-blur-lg bg-slate-950/95 border border-white/10 rounded-md shadow-2xl p-2 hidden z-50 transition-all duration-200 text-left">
                                @if(auth()->user()->role === 'admin')
                                    <a href="/admin/rentals" class="block px-3 py-2.5 text-xs font-semibold text-slate-200 hover:text-white hover:bg-white/5 rounded-md transition">Dashboard Admin</a>
                                @elseif(auth()->user()->role === 'merchant')
                                    <a href="{{ route('merchant.dashboard') }}" class="block px-3 py-2.5 text-xs font-semibold text-emerald-400 hover:text-white hover:bg-emerald-500/10 rounded-md transition">Dashboard Merchant</a>
                                    <a href="/profile/complete" class="block px-3 py-2.5 text-xs font-semibold text-slate-200 hover:text-white hover:bg-white/5 rounded-md transition">Account</a>
                                    <a href="/history-order" class="block px-3 py-2.5 text-xs font-semibold text-slate-200 hover:text-white hover:bg-white/5 rounded-md transition">History Order</a>
                                @else
                                    <a href="/profile/complete" class="block px-3 py-2.5 text-xs font-semibold text-slate-200 hover:text-white hover:bg-white/5 rounded-md transition">Account</a>
                                    <a href="/history-order" class="block px-3 py-2.5 text-xs font-semibold text-slate-200 hover:text-white hover:bg-white/5 rounded-md transition">History Order</a>
                                @endif

                                <div class="border-t border-white/5 mt-1 pt-1">
                                    <form method="POST" action="/logout">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-2 px-3 py-2.5 text-xs font-bold text-rose-400 hover:text-rose-300 hover:bg-rose-500/10 rounded-md transition text-left cursor-pointer border-0 bg-transparent">Logout</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="/login" class="bg-blue-600 hover:bg-blue-500 text-white px-5 py-2 rounded-md font-semibold shadow-md shadow-blue-600/30 transition duration-200 text-sm hidden md:block">Masuk Akun</a>
                    @endauth
                    
                    <!-- Hamburger Menu Button -->
                    <button id="mobileMenuBtn" class="md:hidden text-white hover:text-blue-400 focus:outline-none transition cursor-pointer">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                </div>
            </nav>
        </div>
    </div>

    <!-- MOBILE DRAWER MENU -->
    <div id="mobileDrawer" class="fixed inset-x-0 top-0 z-[100] bg-slate-950/98 backdrop-blur-2xl border-b border-white/10 p-6 transform -translate-y-full transition-transform duration-300 shadow-2xl md:hidden">
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center font-bold text-white text-lg overflow-hidden p-0.5">
                    @if(!empty($configs['app_logo']))
                        <img src="{{ asset('storage/' . $configs['app_logo']) }}" class="w-full h-full object-contain rounded-full">
                    @else
                        {{ substr($configs['app_name'] ?? 'D', 0, 1) }}
                    @endif
                </div>
                <span class="font-extrabold text-sm md:text-base tracking-wider text-white">{{ $configs['app_name'] ?? 'DAY-RENT' }}</span>
            </div>
            <button id="closeDrawerBtn" class="text-white hover:text-rose-400 focus:outline-none transition cursor-pointer">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="flex flex-col gap-5 font-semibold text-lg">
            <a href="/" class="text-blue-400 pb-2 border-b border-white/10">Beranda</a>
            <a href="/catalog" class="text-slate-300 hover:text-white transition pb-2 border-b border-white/10">Katalog</a>
            <a href="/guide" class="text-slate-300 hover:text-white transition pb-2 border-b border-white/10">Panduan</a>
            <a href="/help" class="text-slate-300 hover:text-white transition pb-2 border-b border-white/10">Bantuan</a>
            @guest
                <a href="/login" class="bg-blue-600 hover:bg-blue-500 text-white px-5 py-3 rounded-md font-bold text-center mt-4 tracking-wider uppercase text-sm">Masuk Akun</a>
            @endguest
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 relative z-10">
        <!-- HERO SECTION -->
        <main class="mt-12 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center scroll-fade-up show">
            <div class="lg:col-span-7 space-y-6 text-left">
                <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight leading-tight uppercase max-w-2xl text-white">
                    {{ $configs['hero_title'] ?? 'SISTEM RENTAL UNIVERSAL' }}
                </h1>
                <p class="text-slate-300 text-base md:text-lg max-w-xl">
                    {{ $configs['hero_subtitle'] ?? 'Kustomisasi deskripsi utama aplikasi web rental kamu dengan sangat mudah di sini.' }}
                </p>
                
                <div class="pt-2 flex flex-wrap gap-4">
                    <a href="/catalog" class="inline-flex items-center gap-2 bg-blue-500 hover:bg-blue-400 text-white font-bold px-8 py-3.5 rounded-none shadow-lg shadow-blue-500/40 transition text-sm tracking-wider uppercase border-0">
                        {{ $configs['hero_button_text'] ?? 'JELAJAHI KATALOG' }}
                    </a>
                    <a href="/guide" class="inline-flex items-center bg-white/5 hover:bg-white/10 border border-white/10 text-white font-bold px-8 py-3.5 rounded-none transition text-sm tracking-wider uppercase">
                        Cara Sewa
                    </a>
                </div>
            </div>

            <!-- COUNTER STATISTIK KANAN -->
            <div class="lg:col-span-5 flex flex-wrap lg:flex-nowrap gap-4 justify-start lg:justify-end">
                <div class="backdrop-blur-md bg-white/5 border border-white/10 rounded-none p-5 text-center min-w-[110px] sm:min-w-[130px] flex-1">
                    <h3 id="countPelanggan" data-target="1000" class="text-2xl font-bold text-white tracking-tight">0</h3>
                    <p class="text-xs text-slate-400 mt-1 font-medium">Pelanggan</p>
                </div>
                <div class="backdrop-blur-md bg-white/5 border border-white/10 rounded-none p-5 text-center min-w-[110px] sm:min-w-[130px] flex-1">
                    <h3 id="countBerdiri" data-target="5" class="text-2xl font-bold text-white tracking-tight">0</h3>
                    <p class="text-xs text-slate-400 mt-1 font-medium">Berdiri</p>
                </div>
                <div class="backdrop-blur-md bg-white/5 border border-white/10 rounded-none p-5 text-center min-w-[110px] sm:min-w-[130px] flex-1">
                    <h3 class="text-2xl font-bold text-white tracking-tight flex items-center justify-center gap-1">
                        <span id="countRating" data-target="4.9">0.0</span>
                        <span class="text-yellow-400 text-xl">★</span>
                    </h3>
                    <p class="text-xs text-slate-400 mt-1 font-medium">Rating</p>
                </div>
            </div>
        </main>
    </div> 

    <!-- SECTION HOT OFFER + SMART SLIDER -->
    <section class="mt-28 scroll-fade-up relative z-10 w-full" 
             style="background: linear-gradient(to bottom, transparent 0%, rgba(11, 19, 43, 0.4) 15%, {{ $configs['slider_bg_color_start'] ?? '#0B132B' }} 40%, {{ $configs['slider_bg_color_end'] ?? '#1C2541' }} 65%, rgba(11, 19, 43, 0.4) 85%, transparent 100%);">
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <!-- HEADER HOT OFFER -->
            <div class="text-center mb-12">
                <span class="bg-blue-500/10 text-blue-500 border border-blue-500/20 px-4 py-1.5 rounded-none text-[10px] font-black uppercase tracking-widest animate-pulse">
                    Promo Terbatas Hari Ini
                </span>
                <h2 class="text-3xl md:text-4xl font-black tracking-tight uppercase text-white mt-4">KUPON</h2>
                <p class="text-xs text-slate-400 max-w-md mx-auto mt-1">Pakai kupon diskon khusus buat dapetin harga yang murah bangett.</p>
                <div class="h-0.5 w-12 bg-blue-500 mx-auto mt-3"></div>
            </div>

            <!-- SLIDER PROMO -->
            <div class="relative group/slider px-4 sm:px-0">
                <button id="prevBtn" class="absolute left-2 sm:-left-5 top-1/2 -translate-y-1/2 z-40 bg-slate-900/50 hover:bg-white/20 text-white border border-white/20 p-3 rounded-full backdrop-blur-md transition duration-300 cursor-pointer shadow-lg border-0 sm:opacity-0 group-hover/slider:opacity-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" /></svg>
                </button>
                <button id="nextBtn" class="absolute right-2 sm:-right-5 top-1/2 -translate-y-1/2 z-40 bg-slate-900/50 hover:bg-white/20 text-white border border-white/20 p-3 rounded-full backdrop-blur-md transition duration-300 cursor-pointer shadow-lg border-0 sm:opacity-0 group-hover/slider:opacity-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" /></svg>
                </button>

                {{-- Wrapper: overflow visible agar mobile slide-down tidak terpotong --}}
                <div class="rounded-3xl pb-2">
                    <div id="sliderTrack" class="flex transition-transform duration-500 ease-out gap-6">
                        @forelse($promos as $promoIndex => $promo)
                            @php
                                // Mapping warna badge dari DB field badge_color → inline style hex
                                $colorMap = [
                                    '#3b82f6' => ['bg' => '#1e40af33', 'border' => '#3b82f6', 'text' => '#93c5fd'],
                                    '#a855f7' => ['bg' => '#6b21a833', 'border' => '#a855f7', 'text' => '#d8b4fe'],
                                    '#10b981' => ['bg' => '#06542033', 'border' => '#10b981', 'text' => '#6ee7b7'],
                                    '#f59e0b' => ['bg' => '#78350f33', 'border' => '#f59e0b', 'text' => '#fcd34d'],
                                    '#f43f5e' => ['bg' => '#88152633', 'border' => '#f43f5e', 'text' => '#fda4af'],
                                ];
                                $badgeClr = $colorMap[$promo->badge_color] ?? $colorMap['#3b82f6'];
                                $promoLink = (str_starts_with($promo->link_url, 'http') || str_starts_with($promo->link_url, '/'))
                                    ? $promo->link_url
                                    : url($promo->link_url);
                            @endphp

                            {{-- Wrapper per-card: tidak ada overflow-hidden di sini agar slide-down bisa keluar --}}
                            <div class="promo-outer-wrap min-w-[85vw] sm:min-w-[calc(50%-12px)] md:min-w-[calc(33.333%-16px)] flex-shrink-0 relative z-10">

                                {{-- === KARTU UTAMA === --}}
                                <div class="relative h-44 rounded-3xl overflow-hidden shadow-2xl group/promo border border-white/10 hover:border-blue-500/40 transition-all duration-300 cursor-pointer"
                                     onclick="togglePromoMobile(this)">

                                    {{-- Background Gambar --}}
                                    <div class="absolute inset-0 bg-cover bg-center"
                                         style="background-image: linear-gradient(to right, rgba(9,15,30,0.9) 0%, rgba(15,23,42,0.5) 60%, transparent 100%), url('{{ asset('storage/' . $promo->image) }}');"></div>

                                    {{-- Konten Kartu --}}
                                    <div class="relative z-10 h-full p-5 flex flex-col justify-between">
                                        {{-- Badge Warna Dinamis via Inline Style --}}
                                        <span class="self-start text-[10px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wider border"
                                              style="background-color: {{ $badgeClr['bg'] }}; border-color: {{ $badgeClr['border'] }}; color: {{ $badgeClr['text'] }};">
                                            {{ $promo->tag }}
                                        </span>

                                        <div class="flex items-center justify-between">
                                            <a href="{{ $promoLink }}"
                                               class="text-xs text-blue-400 hover:text-blue-300 flex items-center gap-1 font-semibold transition duration-200"
                                               onclick="event.stopPropagation()">
                                                {{ $promo->link_text }} <span>→</span>
                                            </a>
                                            <span class="md:hidden text-[10px] text-slate-400 font-medium flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                                Detail
                                            </span>
                                        </div>
                                    </div>

                                    {{-- === DESKTOP HOVER OVERLAY (muncul saat hover, hanya di md+) === --}}
                                    <div class="absolute inset-0 bg-slate-900/92 backdrop-blur-sm z-20 p-5 flex flex-col justify-between
                                                opacity-0 group-hover/promo:opacity-100 transition-all duration-300
                                                pointer-events-none group-hover/promo:pointer-events-auto
                                                hidden md:flex">
                                        <div>
                                            <span class="self-start text-[10px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wider border"
                                                  style="background-color: {{ $badgeClr['bg'] }}; border-color: {{ $badgeClr['border'] }}; color: {{ $badgeClr['text'] }};">
                                                {{ $promo->tag }}
                                            </span>
                                            <h4 class="text-sm font-bold text-white mt-3 leading-tight line-clamp-3">{{ $promo->title }}</h4>
                                            <p class="text-[9px] font-black text-blue-400 tracking-wider uppercase mt-1">⚡ Info Promo</p>
                                        </div>
                                        <a href="{{ $promoLink }}"
                                           class="w-full bg-blue-600 hover:bg-blue-500 text-white text-[10px] font-bold py-2 rounded-xl text-center tracking-wider uppercase transition duration-200">
                                            {{ $promo->link_text }} →
                                        </a>
                                    </div>
                                </div>

                                {{-- === MOBILE SLIDE-DOWN PANEL (di bawah kartu, hanya di < md) === --}}
                                <div class="promo-mobile-panel max-h-0 overflow-hidden transition-all duration-400 ease-out md:hidden rounded-b-2xl relative z-50 pointer-events-auto">
                                    <div class="bg-slate-800/95 backdrop-blur-md border-x border-b border-blue-500/30 px-4 py-3 rounded-b-2xl">
                                        <h4 class="text-xs font-bold text-white leading-tight mb-1 line-clamp-2">{{ $promo->title }}</h4>
                                        <p class="text-[9px] font-black text-blue-400 tracking-wider uppercase mb-2">⚡ Info Promo</p>
                                        <a href="{{ $promoLink }}"
                                           class="block w-full bg-blue-600 hover:bg-blue-500 text-white text-[10px] font-bold py-1.5 rounded-xl text-center tracking-wider uppercase cursor-pointer"
                                           onclick="event.stopPropagation(); window.location.href='{{ $promoLink }}';">
                                            {{ $promo->link_text }} →
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="min-w-[100%] md:min-w-[calc(33.333%-16px)] backdrop-blur-md bg-white/5 border border-white/10 p-6 rounded-3xl shadow-xl flex flex-col justify-center items-center h-44 text-center w-full">
                                <span class="text-2xl mb-1">📢</span>
                                <h4 class="text-sm font-bold text-slate-300">Untuk saat ini promo belum tersedia</h4>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- MAIN BODY CONTENT -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 mt-20">
        <!-- SECTION 1: POPULAR PRO STOCKS -->
        <section class="bg-slate-900 border border-white/5 p-8 sm:p-12 rounded-none mb-32 shadow-2xl relative overflow-hidden scroll-fade-up">
            <div class="absolute top-0 right-0 w-64 h-64 bg-blue-500/5 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="text-center space-y-2 mb-8">
                <h2 class="text-2xl sm:text-3xl font-black tracking-tight uppercase text-white">SEDANG POPULER</h2>
                <p class="text-xs text-slate-400 max-w-md mx-auto">Top 3 unit paling sering disewa, bisa kamu filter langsung berdasarkan kategori pilihanmu.</p>
                <div class="h-0.5 w-12 bg-blue-500 mx-auto mt-3"></div>
            </div>

            <div class="flex flex-wrap justify-center gap-2 mb-10">
                <button onclick="filterPopular('all', this)" class="pop-filter-btn px-4 py-1.5 text-[11px] font-bold uppercase tracking-wider rounded-none border transition border-blue-600 bg-blue-600 text-white">
                    Semua
                </button>
                @foreach($categories as $cat)
                    <button onclick="filterPopular('{{ $cat->slug }}', this)" class="pop-filter-btn px-4 py-1.5 text-[11px] font-bold uppercase tracking-wider rounded-none border transition border-white/10 bg-white/5 text-slate-400 hover:border-slate-500 hover:text-white">
                        {{ $cat->name }}
                    </button>
                @endforeach
            </div>

            {{-- ============================================================= --}}
            {{-- MOBILE VIEW: Compact Horizontal List (hidden on md+) --}}
            {{-- ============================================================= --}}
            <div id="popularContainer" class="flex flex-col gap-3 md:hidden">
                @foreach($items as $index => $popItem)
                    @php 
                        $currentSlug = $popItem->category->slug ?? DB::table('categories')->where('id', $popItem->category_id)->value('slug') ?? '';
                        $currentCategoryName = $popItem->category->name ?? DB::table('categories')->where('id', $popItem->category_id)->value('name') ?? 'General';
                        $imageUrl = (str_starts_with($popItem->image, '/storage/') || str_starts_with($popItem->image, 'storage/'))
                            ? asset(ltrim($popItem->image, '/'))
                            : asset('storage/' . $popItem->image);
                        $badgeColors = [
                            0 => 'from-yellow-400 to-yellow-600 text-slate-900',
                            1 => 'from-slate-200 to-slate-400 text-slate-900',
                            2 => 'from-amber-700 to-amber-900 text-white',
                        ];
                        $badgeEmojis = ['🥇', '🥈', '🥉'];
                    @endphp

                    <div class="popular-card flex flex-row items-center bg-slate-800/80 p-2.5 rounded-xl border border-slate-700/50 w-full group hover:border-blue-500/50 transition-all duration-200"
                         data-category="{{ $currentSlug }}"
                         data-rented="{{ $popItem->total_rented }}">

                        {{-- Sisi Kiri: Gambar + Badge --}}
                        <div class="relative w-24 h-24 flex-shrink-0">
                            <img src="{{ $imageUrl }}" alt="{{ $popItem->name }}"
                                 class="w-full h-full object-cover rounded-lg group-hover:scale-105 transition duration-300">
                            <span class="absolute top-1 left-1 bg-gradient-to-br {{ $badgeColors[$index] ?? 'from-slate-600 to-slate-800 text-white' }} text-[9px] font-extrabold px-1.5 py-0.5 rounded shadow-md">
                                {{ $badgeEmojis[$index] ?? '' }} #{{ $index + 1 }}
                            </span>
                        </div>

                        {{-- Sisi Kanan: Semua Data Teks --}}
                        <div class="flex-1 flex flex-col justify-between pl-3 h-24 overflow-hidden">
                            {{-- Baris 1: Nama & Rating --}}
                            <div class="flex justify-between items-start gap-2">
                                <h4 class="font-bold text-sm text-white line-clamp-1 leading-tight flex-1">{{ $popItem->name }}</h4>
                                <span class="text-[11px] text-amber-400 font-bold flex items-center gap-0.5 shrink-0">⭐ {{ $popItem->rating > 0 ? number_format($popItem->rating, 1) : 'New' }}</span>
                            </div>

                            {{-- Baris 2: Info Sewa & Stok --}}
                            <p class="text-[11px] text-slate-400 mt-0.5">
                                Tersewa: <strong class="text-slate-200">{{ $popItem->total_rented }}</strong>
                                &nbsp;|&nbsp;
                                Stok: <strong class="{{ $popItem->stock > 0 ? 'text-emerald-400' : 'text-rose-400' }}">{{ $popItem->stock }}</strong>
                            </p>

                            {{-- Baris 3: Harga + Tombol Sewa --}}
                            <div class="flex justify-between items-center mt-auto">
                                <span class="text-sm font-bold text-blue-400">
                                    Rp{{ is_numeric(str_replace('.', '', $popItem->price)) ? number_format(str_replace('.', '', $popItem->price), 0, ',', '.') : $popItem->price }}<span class="text-[10px] text-slate-400 font-normal">/hr</span>
                                </span>
                                @if($popItem->stock > 0)
                                    <a href="/catalog?search={{ urlencode($popItem->name) }}"
                                       class="px-3 py-1.5 bg-blue-600 hover:bg-blue-500 text-white text-[11px] font-bold rounded-lg transition duration-200">
                                        SEWA
                                    </a>
                                @else
                                    <span class="px-3 py-1.5 bg-slate-700 text-slate-500 text-[11px] font-bold rounded-lg cursor-not-allowed">
                                        KOSONG
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- ============================================================= --}}
            {{-- DESKTOP VIEW: Vertical Cards Grid (hidden on mobile) --}}
            {{-- ============================================================= --}}
            <div id="popularContainerDesktop" class="hidden md:flex md:flex-row gap-6 items-end justify-center min-h-[450px]">
                @foreach($items as $index => $popItem)
                    @php 
                        $currentCategoryName = $popItem->category->name ?? DB::table('categories')->where('id', $popItem->category_id)->value('name') ?? 'General';
                        $imageUrl = (str_starts_with($popItem->image, '/storage/') || str_starts_with($popItem->image, 'storage/'))
                            ? asset(ltrim($popItem->image, '/'))
                            : asset('storage/' . $popItem->image);
                    @endphp

                    {{-- Podium Order: #3=kiri, #1=tengah(utama), #2=kanan --}}
                    @php
                        $desktopOrderClass = match($index) {
                            0 => 'md:order-2 md:scale-105 md:z-10',  // Juara 1 di tengah, sedikit lebih besar
                            1 => 'md:order-3',                        // Juara 2 di kanan
                            2 => 'md:order-1',                        // Juara 3 di kiri
                            default => ''
                        };
                    @endphp

                    <div class="popular-card relative w-1/3 max-w-xs aspect-[3/4] rounded-none overflow-hidden shadow-2xl group border border-white/10 hover:border-blue-500/40 transition-all duration-300 {{ $desktopOrderClass }}"
                         data-category="{{ $popItem->category->slug ?? DB::table('categories')->where('id', $popItem->category_id)->value('slug') ?? '' }}"
                         data-rented="{{ $popItem->total_rented }}">

                        <img src="{{ $imageUrl }}" alt="{{ $popItem->name }}"
                             class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-500 z-0">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent z-10"></div>

                        @if($index == 0)
                            <div class="absolute top-0 left-0 z-30 bg-gradient-to-br from-yellow-400 to-yellow-600 text-slate-900 text-xs font-black px-3 py-1 shadow-md flex items-center gap-1">🥇 #1</div>
                        @elseif($index == 1)
                            <div class="absolute top-0 left-0 z-30 bg-gradient-to-br from-slate-200 to-slate-400 text-slate-900 text-xs font-black px-3 py-1 shadow-md flex items-center gap-1">🥈 #2</div>
                        @elseif($index == 2)
                            <div class="absolute top-0 left-0 z-30 bg-gradient-to-br from-amber-700 to-amber-900 text-white text-xs font-black px-3 py-1 shadow-md flex items-center gap-1">🥉 #3</div>
                        @endif

                        <div class="absolute inset-0 p-5 z-20 flex flex-col justify-between">
                            <div class="flex justify-between items-start">
                                <span class="backdrop-blur-md bg-black/40 text-violet-400 border border-violet-500/30 px-2.5 py-1 text-[8px] font-black uppercase tracking-wider">{{ $currentCategoryName }}</span>
                                <span class="backdrop-blur-md bg-black/40 text-amber-400 border border-amber-500/20 px-2 py-0.5 text-[9px] font-bold flex items-center gap-1">⭐ {{ $popItem->rating > 0 ? number_format($popItem->rating, 1) : 'New' }}</span>
                            </div>
                            <div class="space-y-3">
                                <div>
                                    <h3 class="text-sm font-extrabold text-white tracking-tight leading-tight line-clamp-2 drop-shadow-md">{{ $popItem->name }}</h3>
                                    <div class="flex items-center gap-3 text-[9px] text-slate-300 font-medium drop-shadow mt-0.5">
                                        <span>Tersewa: <strong class="text-white">{{ $popItem->total_rented }}</strong></span>
                                        <span class="text-white/20">|</span>
                                        <span>Stok: <strong class="text-emerald-400">{{ $popItem->stock }}</strong></span>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">Harga Sewa</p>
                                    <p class="text-sm font-black text-white">
                                        Rp {{ is_numeric(str_replace('.', '', $popItem->price)) ? number_format(str_replace('.', '', $popItem->price), 0, ',', '.') : $popItem->price }}
                                        <span class="text-slate-400 font-normal text-[9px]">/hr</span>
                                    </p>
                                </div>
                                @if($popItem->stock > 0)
                                    <a href="/catalog?search={{ urlencode($popItem->name) }}" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold text-[10px] py-3 rounded-none transition duration-200 flex items-center justify-center uppercase tracking-wider border-0">
                                        Sewa Sekarang
                                    </a>
                                @else
                                    <button disabled class="w-full bg-slate-800 text-slate-500 font-bold text-[10px] py-3 rounded-none flex items-center justify-center uppercase tracking-wider border border-white/5 cursor-not-allowed">
                                        Unit Sedang Disewa 🚫
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div id="popEmptyMessage" class="hidden text-center py-12 text-slate-400 text-xs">
                Tidak ada produk populer yang ditemukan pada kategori ini.
            </div>

            <div class="flex justify-center mt-12">
                <a href="/catalog" class="bg-white/5 hover:bg-white/10 border border-white/10 text-white text-xs font-bold py-3 px-6 rounded-none tracking-wider uppercase transition flex items-center gap-2">
                    <span>Explore Full Catalog</span>
                    <span>→</span>
                </a>
            </div>
        </section>


        <!-- SECTION 2: THE BEST SERVICE SHOWCASE -->
        <section class="mb-24 scroll-fade-up">
            <div class="bg-slate-900/80 border border-white/8 rounded-2xl sm:rounded-3xl overflow-hidden shadow-2xl px-6 py-10 sm:px-10 sm:py-14 max-w-5xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center">
                    <!-- Gambar -->
                    <div class="flex justify-center order-last lg:order-first">
                        <div class="relative w-full max-w-[280px] sm:max-w-sm aspect-[4/3] rounded-2xl overflow-hidden shadow-xl">
                            <div class="absolute inset-0 bg-blue-500/10 rounded-2xl transform rotate-1 translate-x-2 translate-y-2 blur-sm"></div>
                            <div class="absolute inset-0 border border-white/10 bg-slate-800 rounded-2xl p-1.5 shadow-2xl overflow-hidden">
                                <img src="https://images.unsplash.com/photo-1468495244123-6c6c332eeece?q=80&w=800" class="w-full h-full object-cover rounded-xl filter grayscale contrast-125" alt="Material Showcase">
                            </div>
                        </div>
                    </div>

                    <!-- Teks -->
                    <div class="space-y-4 text-left">
                        <h2 class="text-2xl sm:text-3xl font-black tracking-tight uppercase leading-tight text-white">
                            RENTAL TERPERCAYA INDONESIA <br><span class="text-blue-500">CUMA DAYRENT TEMPATNYA</span>
                        </h2>
                        <p class="text-slate-400 text-sm font-light leading-relaxed">
                            Setiap unit perangkat keras atau peralatan yang disewa melalui sistem manajemen sewa ini dijamin telah melewati pemeriksaan pemeliharaan menyeluruh demi menjamin user experience.
                        </p>
                        <div class="space-y-2.5 pt-1 text-xs font-medium text-slate-300">
                            <div class="flex items-center gap-3">
                                <span class="text-blue-500 text-sm shrink-0">✓</span>
                                <span>Service Fast Response</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-blue-500 text-sm shrink-0">✓</span>
                                <span>High-Quality Maintenance Secara Berkala</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-blue-500 text-sm shrink-0">✓</span>
                                <span>Proteksi Penuh &amp; Asuransi Keamanan Transaksi</span>
                            </div>
                        </div>
                        <div class="pt-2">
                            <a href="/catalog" class="inline-block bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs px-7 py-3 rounded-xl uppercase tracking-widest transition border-0">
                                Jelajahi Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECTION 3: SEE WHAT USER ARE SAYING -->
        <section class="border-t border-white/5 pt-16 mb-24 text-center space-y-12 overflow-hidden scroll-fade-up">
            <div class="space-y-2">
                <h2 class="text-2xl sm:text-3xl font-black tracking-tight uppercase text-white">Komentar</h2>
                <p class="text-xs text-slate-400 max-w-md mx-auto">Penasaran gimana pendapat mereka setelah cobain dayrent?</p>
                <div class="h-0.5 w-12 bg-blue-500 mx-auto mt-2"></div>
            </div>

            <div class="marquee-wrapper relative w-full flex overflow-x-hidden">
                <div class="marquee-track flex gap-6 animate-marquee">
                    
                    @php
                        $realFeedbacks = DB::table('notifications')
                            ->join('users', 'notifications.user_id', '=', 'users.id')
                            ->where('notifications.is_rated', true)
                            ->where('notifications.message', 'like', '%||%')
                            ->select('notifications.*', 'users.name as user_name')
                            ->orderBy('notifications.updated_at', 'desc')
                            ->limit(6)
                            ->get();
                    @endphp

                    @forelse($realFeedbacks as $fb)
                        @php
                            $reviewParts = explode('||', $fb->message);
                            $userStars = (int)$reviewParts[0];
                            $userTextReview = $reviewParts[1];
                        @endphp
                        <div class="backdrop-blur-md bg-white/5 border border-white/10 p-6 rounded-none flex flex-col justify-between space-y-6 w-[350px] shrink-0">
                            <div class="space-y-2 text-left">
                                <div class="text-amber-400 text-xs">
                                    @for($s = 1; $s <= 5; $s++)
                                        {{ $s <= $userStars ? '⭐' : '&#9734;' }}
                                    @endfor
                                </div>
                                <p class="text-xs text-slate-300 leading-relaxed font-light">
                                    "{!! e($userTextReview) !!}"
                                </p>
                            </div>
                            <div class="text-left">
                                <p class="text-xs font-extrabold text-white">{{ $fb->user_name }}</p>
                                <p class="text-[9px] text-slate-500">Verified Customer • {{ \Carbon\Carbon::parse($fb->updated_at)->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="backdrop-blur-md bg-white/5 border border-white/10 p-6 rounded-none flex flex-col justify-between space-y-6 w-[350px] shrink-0">
                            <div class="space-y-2 text-left">
                                <div class="text-amber-400 text-xs">⭐⭐⭐⭐•</div>
                                <p class="text-xs text-slate-300 leading-relaxed font-light">"Pelayanan cepat, transaksi aman, mudah, dan tentunya terjamin kemanannya."</p>
                            </div>
                            <div class="text-left">
                                <p class="text-xs font-extrabold text-white">Naufal Pro</p>
                                <p class="text-[10px] text-slate-500">Developer • Just Now</p>
                            </div>
                        </div>
                    @endforelse

                </div>
            </div>
        </section>
    </div>

    <!-- MODAL BOX RATING & KOMENTAR DINAMIS -->
    <div id="ratingModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm hidden items-center justify-center z-[100] p-4 transition-all duration-300">
        <div class="bg-slate-900 border border-white/10 rounded-none p-6 max-w-sm w-full shadow-2xl space-y-4 text-center transform scale-95 transition-transform duration-300">
            <div class="w-12 h-12 bg-amber-500/10 text-amber-400 rounded-full flex items-center justify-center text-xl mx-auto font-bold">⭐</div>
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

                <div class="space-y-1 text-left">
                    <label class="text-[10px] uppercase font-bold tracking-wider text-slate-400">Tulis Ulasan / Komentar</label>
                    <textarea name="review_comment" rows="3" required
                              placeholder="Masukkan testimoni kepuasan Anda tentang barang ini..." 
                              class="w-full bg-slate-950 border border-white/10 text-white text-xs px-3 py-2 rounded-md focus:outline-none focus:border-blue-500 transition placeholder-slate-600 resize-none"></textarea>
                </div>

                <div class="flex gap-2 pt-2">
                    <button type="button" onclick="closeRatingModal()" class="flex-1 bg-white/5 hover:bg-white/10 text-slate-300 text-xs font-bold py-3 rounded-none transition cursor-pointer border-0">Batal</button>
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-500 text-white text-xs font-black py-3 rounded-none transition shadow-md shadow-blue-600/20 cursor-pointer border-0 uppercase tracking-wider">Kirim Rating</button>
                </div>
            </form>
        </div>
    </div>

    <!-- FOOTER -->
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
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center font-bold text-white text-lg overflow-hidden p-0.5 flex-shrink-0">
                            @if(!empty($configs['app_logo']))
                                <img src="{{ asset('storage/' . $configs['app_logo']) }}" class="w-full h-full object-contain rounded-full">
                            @else
                                {{ substr($configs['app_name'] ?? 'D', 0, 1) }}
                            @endif
                        </div>
                        <span class="font-extrabold text-sm md:text-base tracking-wider text-white">{{ $configs['app_name'] ?? 'DAY-RENT' }}</span>
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
                        <li><a href="/guide" class="hover:text-white transition">Guide</a></li>
                        <li><a href="/help" class="hover:text-white transition">Pusat Bantuan</a></li>
                    </ul>
                </div>

                <div class="md:col-span-3 space-y-3">
                    <h4 class="text-xs font-bold uppercase tracking-widest text-blue-400">Contact Person</h4>
                    <ul class="space-y-2 text-xs text-slate-400">
                        <li class="flex items-center gap-2"><span>📍</span> Universitas Logistik dan Bisnis Internasional</li>
                        <li class="flex items-center gap-2"><span>✉️</span> falldayrent@gmail.com</li>
                        <li class="flex items-center gap-2"><span>⚡</span> Naufal &amp; Zikmal</li>
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

    <!-- CSS MARQUEE ANIMATION & PODIUM CLASS -->
    <style>
        .scroll-fade-up {
            opacity: 0;
            transform: translateY(40px);
            transition: opacity 0.8s cubic-bezier(0.25, 1, 0.5, 1), transform 0.8s cubic-bezier(0.25, 1, 0.5, 1);
            will-change: opacity, transform;
        }
        .scroll-fade-up.show {
            opacity: 1;
            transform: translateY(0);
        }

        @keyframes marqueeRight {
            0% { transform: translateX(calc(-50% - 12px)); }
            100% { transform: translateX(0); }
        }
        .animate-marquee {
            animation: marqueeRight 25s linear infinite;
        }
        .marquee-wrapper:hover .animate-marquee {
            animation-play-state: paused;
        }

        @keyframes floatUp {
            0% { transform: translateY(0) scale(1); opacity: 0.8; }
            100% { transform: translateY(-300px) scale(0.4); opacity: 0; }
        }
        .animate-float-slow { animation: floatUp 8s infinite linear; }
        .animate-float-medium { animation: floatUp 6s infinite linear; animation-delay: 2s; }
        .animate-float-fast { animation: floatUp 4s infinite linear; animation-delay: 1s; }

        @keyframes hoverFloat {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .animate-float-card {
            animation: hoverFloat 5s ease-in-out infinite;
        }
        
        .podium-1 {
            transform: scale(1.05) !important;
            z-index: 30 !important;
            border-color: rgba(59, 130, 246, 0.6) !important;
            box-shadow: 0 25px 50px -12px rgba(59, 130, 246, 0.25);
        }
        .podium-2 { transform: scale(0.95) !important; z-index: 20 !important; opacity: 0.9; }
        .podium-3 { transform: scale(0.95) !important; z-index: 20 !important; opacity: 0.9; }

        .podium-2 .animate-float-card { animation-delay: 0.5s; animation-duration: 5.5s; }
        .podium-3 .animate-float-card { animation-delay: 1s; animation-duration: 5.2s; }
    </style>

    <!-- JAVA SCRIPT INTERACTION ENGINE -->
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const fadeElements = document.querySelectorAll(".scroll-fade-up");
            const observerOptions = {
                root: null,
                threshold: 0.1,
                rootMargin: "0px 0px -50px 0px"
            };

            const scrollObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add("show");
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            fadeElements.forEach(el => scrollObserver.observe(el));

            // FIX LOGIC: JavaScript Scroll Engine yang sinkron dengan pembungkus Fixed Baru
            const wrapper = document.getElementById("navbarWrapper");
            const navbar = document.getElementById("mainNavbar");
            
            window.addEventListener("scroll", function() {
                if (window.scrollY > 40) {
                    wrapper.classList.remove("py-4");
                    wrapper.classList.add("py-2");
                    navbar.classList.remove("bg-white/10", "border-white/20");
                    navbar.classList.add("bg-slate-950/80", "border-white/10", "backdrop-blur-xl", "shadow-2xl");
                } else {
                    wrapper.classList.remove("py-2");
                    wrapper.classList.add("py-4");
                    navbar.classList.remove("bg-slate-950/80", "border-white/10", "backdrop-blur-xl", "shadow-2xl");
                    navbar.classList.add("bg-white/10", "border-white/20");
                }
            });

            document.querySelectorAll("[data-target]").forEach(el => {
                const target = parseFloat(el.getAttribute("data-target"));
                if(isNaN(target)) return;
                let start = 0;
                const step = target / 60;
                const timer = setInterval(() => {
                    start += step;
                    if(start >= target) {
                        clearInterval(timer);
                        el.innerText = el.id === "countRating" ? target.toFixed(1) : Math.floor(target) + "+";
                    } else {
                        el.innerText = el.id === "countRating" ? start.toFixed(1) : Math.floor(start) + "+";
                    }
                }, 16);
            });

            window.filterPopular('all', document.querySelector('.pop-filter-btn'));
        });

        window.filterPopular = function(slug, element) {
            // Update tombol filter aktif
            document.querySelectorAll('.pop-filter-btn').forEach(btn => {
                btn.classList.remove('bg-blue-600', 'text-white', 'border-blue-600');
                btn.classList.add('bg-white/5', 'text-slate-400', 'border-white/10');
            });
            element.classList.remove('bg-white/5', 'text-slate-400', 'border-white/10');
            element.classList.add('bg-blue-600', 'text-white', 'border-blue-600');

            const mobileContainer  = document.getElementById('popularContainer');
            const desktopContainer = document.getElementById('popularContainerDesktop');

            // Kumpulkan semua card dari kedua container
            const mobileCards  = Array.from(mobileContainer.querySelectorAll('.popular-card'));
            const desktopCards = Array.from(desktopContainer.querySelectorAll('.popular-card'));

            // Sembunyikan semua dulu
            mobileCards.forEach(c  => { c.style.display = 'none'; });
            desktopCards.forEach(c => { c.style.display = 'none'; });

            // Filter berdasarkan kategori
            const filteredMobile  = mobileCards.filter(c  => slug === 'all' || c.getAttribute('data-category') === slug);
            const filteredDesktop = desktopCards.filter(c => slug === 'all' || c.getAttribute('data-category') === slug);

            // Urutkan berdasarkan total_rented terbanyak
            const sortFn = (a, b) => parseInt(b.getAttribute('data-rented')) - parseInt(a.getAttribute('data-rented'));
            filteredMobile.sort(sortFn);
            filteredDesktop.sort(sortFn);

            const topMobile  = filteredMobile.slice(0, 3);
            const topDesktop = filteredDesktop.slice(0, 3);

            if (topMobile.length > 0 || topDesktop.length > 0) {
                document.getElementById('popEmptyMessage').classList.add('hidden');

                // Tampilkan mobile cards (flex-row)
                topMobile.forEach(card => {
                    card.style.display = 'flex';
                    mobileContainer.appendChild(card);
                });

                // Tampilkan desktop cards (block for grid cell)
                topDesktop.forEach(card => {
                    card.style.display = 'block';
                    desktopContainer.appendChild(card);
                });
            } else {
                document.getElementById('popEmptyMessage').classList.remove('hidden');
            }
        }

        window.toggleUserDropdown = function() {
            document.getElementById("userDropdownCard").classList.toggle("hidden");
        }
        window.toggleNotifDropdown = function() {
            document.getElementById("notifDropdownCard").classList.toggle("hidden");
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

        window.dismissRatingNotif = function(notifId) {
            fetch(`/notifications/${notifId}/dismiss`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const card = document.getElementById(`notif-card-${notifId}`);
                    if (card) {
                        card.style.opacity = '0';
                        setTimeout(() => card.remove(), 300);
                    }
                }
            })
            .catch(err => console.error(err));
        }

        window.closeRatingModal = function() {
            const modal = document.getElementById("ratingModal");
            if (modal) {
                modal.classList.add("hidden");
                modal.classList.remove("flex");
            }
        }

        document.addEventListener("click", function(event) {
            const nCard = document.getElementById("notifDropdownCard");
            const nBtn = document.getElementById("notifButton");
            if (nCard && nBtn && !nBtn.contains(event.target) && !nCard.contains(event.target)) {
                nCard.classList.add("hidden");
            }
            const uCard = document.getElementById("userDropdownCard");
            const uBtn = document.getElementById("userMenuButton");
            if (uCard && uBtn && !uBtn.contains(event.target) && !uCard.contains(event.target)) {
                uCard.classList.add("hidden");
            }
        });

        // ==========================================
        // SLIDER PROMO JAVASCRIPT LOGIC
        // ==========================================
        const track = document.getElementById('sliderTrack');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        
        if (track && track.children.length > 0) {
            let currentIndex = 0;
            const items = Array.from(track.children);
            const totalItems = items.length;
            
            function updateSlider() {
                // Asumsi gap-6 (24px)
                const itemWidth = items[0].getBoundingClientRect().width + 24; 
                track.style.transform = `translateX(-${currentIndex * itemWidth}px)`;
            }

            if (nextBtn) {
                nextBtn.addEventListener('click', () => {
                    currentIndex = (currentIndex + 1) % totalItems;
                    updateSlider();
                });
            }

            if (prevBtn) {
                prevBtn.addEventListener('click', () => {
                    currentIndex = (currentIndex - 1 + totalItems) % totalItems;
                    updateSlider();
                });
            }

            // Autoplay setiap 4 detik
            let autoplayInterval = setInterval(() => {
                currentIndex = (currentIndex + 1) % totalItems;
                updateSlider();
            }, 4000);

            // Pause autoplay saat kursor di atas slider
            track.addEventListener('mouseenter', () => clearInterval(autoplayInterval));
            track.addEventListener('mouseleave', () => {
                autoplayInterval = setInterval(() => {
                    currentIndex = (currentIndex + 1) % totalItems;
                    updateSlider();
                }, 4000);
            });
            
            // Re-kalkulasi lebar saat resize
            window.addEventListener('resize', updateSlider);
        }

        // ==========================================
        // MOBILE HAMBURGER JAVASCRIPT LOGIC
        // ==========================================
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const closeDrawerBtn = document.getElementById('closeDrawerBtn');
        const mobileDrawer = document.getElementById('mobileDrawer');

        if(mobileMenuBtn && closeDrawerBtn && mobileDrawer) {
            mobileMenuBtn.addEventListener('click', () => {
                mobileDrawer.classList.remove('-translate-y-full');
            });
            closeDrawerBtn.addEventListener('click', () => {
                mobileDrawer.classList.add('-translate-y-full');
            });
        }

        // ==========================================
        // PROMO CARD TAP-TOGGLE MOBILE (Slide-Down)
        // ==========================================
        window.togglePromoMobile = function(cardEl) {
            // Hanya aktif di layar sentuh / mobile (width < 768px)
            if (window.innerWidth >= 768) return;

            // Cari outer-wrap parent
            const outerWrap = cardEl.closest('.promo-outer-wrap');
            if (!outerWrap) return;

            const panel = outerWrap.querySelector('.promo-mobile-panel');
            if (!panel) return;

            const isOpen = panel.style.maxHeight && panel.style.maxHeight !== '0px';

            // Tutup semua panel lain dulu (accordion)
            document.querySelectorAll('.promo-mobile-panel').forEach(p => {
                p.style.maxHeight = '0px';
            });

            // Toggle: buka jika belum terbuka
            if (!isOpen) {
                panel.style.maxHeight = panel.scrollHeight + 'px';
            }
        }
    </script>
</body>
</html>