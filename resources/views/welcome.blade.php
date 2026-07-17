<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $configs['app_name'] ?? 'Day-Rent' }} - Premium Rental Platform</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite('resources/css/app.css')
</head>
<body class="bg-slate-950 text-white min-h-screen relative overflow-x-hidden pt-24" style="font-family: 'Poppins', sans-serif;">

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
                    <span class="font-extrabold text-xl tracking-wider text-white">{{ $configs['app_name'] ?? 'DAY-RENT' }}</span>
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
                                    <div class="p-2.5 rounded-md bg-white/5 border border-white/5 space-y-2 text-left">
                                        <p class="text-[11px] font-black text-amber-400 winding-none">{{ $notif->title }}</p>
                                        <p class="text-[10px] text-slate-300 leading-tight">{{ $notif->message }}</p>
                                        <button onclick="openRatingModal({{ $notif->id }}, '{{ $notif->title }}')" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold text-[9px] py-1.5 rounded-md transition uppercase tracking-wider cursor-pointer border-0">
                                            Beri Bintang Rating ⭐
                                        </button>
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
                        <a href="/login" class="bg-blue-600 hover:bg-blue-500 text-white px-5 py-2 rounded-md font-semibold shadow-md shadow-blue-600/30 transition duration-200 text-sm">Masuk Akun</a>
                    @endauth
                </div>
            </nav>
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
                    Promo Terbatas Ahad Ini
                </span>
                <h2 class="text-3xl md:text-4xl font-black tracking-tight uppercase text-white mt-4">Hot Offer</h2>
                <p class="text-xs text-slate-400 max-w-md mx-auto mt-1">Gunakan kode diskon khusus dan sewa item pilihan dengan penawaran harga terbaik.</p>
                <div class="h-0.5 w-12 bg-blue-500 mx-auto mt-3"></div>
            </div>

            <!-- SLIDER PROMO -->
            <div class="relative group/slider">
                <button id="prevBtn" class="absolute left-[-20px] top-1/2 -translate-y-1/2 z-30 bg-white/10 hover:bg-white/20 text-white border border-white/20 p-3 rounded-full backdrop-blur-md opacity-0 group-hover/slider:opacity-100 transition duration-300 cursor-pointer shadow-lg border-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" /></svg>
                </button>

                <div class="overflow-visible rounded-3xl pb-2">
                    <div id="sliderTrack" class="flex transition-transform duration-500 ease-out gap-6">
                        @forelse($promos as $promo)
                            <div class="min-w-[100%] md:min-w-[calc(33.333%-16px)] relative group/card-wrapper z-10 h-44">
                                <div class="absolute inset-0 z-20 bg-slate-900 border border-white/10 p-6 flex flex-col justify-between h-full rounded-3xl shadow-2xl transition-all duration-300 group-hover/card-wrapper:scale-[1.01] group-hover/card-wrapper:border-blue-500/40">
                                    <div class="absolute inset-0 z-0 bg-cover bg-center pointer-events-none rounded-3xl"
                                         style="background-image: linear-gradient(to right, rgba(9, 15, 30, 0.85) 0%, rgba(15, 23, 42, 0.4) 60%, transparent 100%), url('{{ asset('storage/' . $promo->image) }}');">
                                    </div>
                                    <div class="relative z-10">
                                        <span class="bg-blue-600/30 text-blue-300 text-[10px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wider border border-blue-500/20">{{ $promo->tag }}</span>
                                    </div>
                                    <div class="relative z-10">
                                        <a href="{{ (str_starts_with($promo->link_url, 'http') || str_starts_with($promo->link_url, '/')) ? $promo->link_url : url($promo->link_url) }}" class="text-xs text-blue-400 hover:text-blue-300 flex items-center gap-1 font-semibold transition duration-200">
                                            {{ $promo->link_text }} <span class="text-sm">→</span>
                                        </a>
                                    </div>
                                </div>

                                <div class="absolute inset-x-3 bottom-0 top-24 z-0 bg-slate-800/95 backdrop-blur-md border-x border-b border-white/20 p-4 pt-5 rounded-b-3xl shadow-xl transition-all duration-500 cubic-bezier(0.16, 1, 0.3, 1) transform translate-y-0 opacity-0 group-hover/card-wrapper:translate-y-[60px] group-hover/card-wrapper:opacity-100 flex flex-col justify-end">
                                    <h4 class="text-xs font-semibold text-slate-100 leading-tight tracking-tight line-clamp-2 mb-0.5">
                                        {{ $promo->title }}
                                    </h4>
                                    <span class="text-[8px] font-black text-blue-400 tracking-wider uppercase">⚡ Info Promo</span>
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

                <button id="nextBtn" class="absolute right-[-20px] top-1/2 -translate-y-1/2 z-30 bg-white/10 hover:bg-white/20 text-white border border-white/20 p-3 rounded-full backdrop-blur-md opacity-0 group-hover/slider:opacity-100 transition duration-300 cursor-pointer shadow-lg border-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" /></svg>
                </button>
            </div>
        </div>
    </section>

    <!-- MAIN BODY CONTENT -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 mt-20">
        <!-- SECTION 1: POPULAR PRO STOCKS -->
        <section class="bg-slate-900 border border-white/5 p-8 sm:p-12 rounded-none mb-32 shadow-2xl relative overflow-hidden scroll-fade-up">
            <div class="absolute top-0 right-0 w-64 h-64 bg-blue-500/5 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="text-center space-y-2 mb-8">
                <h2 class="text-2xl sm:text-3xl font-black tracking-tight uppercase text-white">Popular Pro Stocks</h2>
                <p class="text-xs text-slate-400 max-w-md mx-auto">3 unit paling sering disewa oleh user, filter langsung berdasarkan kategori pilihanmu.</p>
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

            <div id="popularContainer" class="grid grid-cols-1 md:grid-cols-3 gap-12 items-center justify-center min-h-[450px]">
                @foreach($items as $popItem)
                    @php 
                        $currentSlug = $popItem->category->slug ?? DB::table('categories')->where('id', $popItem->category_id)->value('slug') ?? '';
                        $currentCategoryName = $popItem->category->name ?? DB::table('categories')->where('id', $popItem->category_id)->value('name') ?? 'General';
                    @endphp
                    
                    <div class="popular-card relative w-full aspect-[3/4] rounded-none overflow-hidden shadow-2xl group border border-white/10 hover:border-blue-500/40 transition-all duration-300"
                         data-category="{{ $currentSlug }}"
                         data-rented="{{ $popItem->total_rented }}">
                        
                        <div class="animate-float-card w-full h-full relative flex flex-col justify-between">
                            <img src="{{ (str_starts_with($popItem->image, '/storage/') || str_starts_with($popItem->image, 'storage/')) ? asset(ltrim($popItem->image, '/')) : asset('storage/' . $popItem->image) }}" 
                                 alt="{{ $popItem->name }}" 
                                 class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-500 z-0">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent z-10"></div>
                            
                            <div class="absolute inset-0 p-5 z-20 flex flex-col justify-between">
                                <div class="flex justify-between items-start">
                                    <span class="backdrop-blur-md bg-black/40 text-violet-400 border border-violet-500/30 px-2.5 py-1 rounded-none text-[8px] font-black uppercase tracking-wider">
                                        {{ $currentCategoryName }}
                                    </span>
                                    <span class="backdrop-blur-md bg-black/40 text-amber-400 border border-amber-500/20 px-2 py-0.5 rounded-none text-[9px] font-bold flex items-center gap-1">
                                        ⭐ {{ $popItem->rating > 0 ? number_format($popItem->rating, 1) : 'New' }}
                                    </span>
                                </div>

                                <div class="space-y-3">
                                    <div>
                                        <h3 class="text-sm font-extrabold text-white tracking-tight leading-tight line-clamp-2 drop-shadow-md">
                                            {{ $popItem->name }}
                                        </h3>
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
        <section class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center mb-32 scroll-fade-up">
            <div class="lg:col-span-6 flex justify-center order-last lg:order-first">
                <div class="relative w-full max-w-sm aspect-[4/3]">
                    <div class="absolute inset-0 bg-blue-50 rounded-none transform rotate-2 translate-x-3 translate-y-3 opacity-10 blur-sm"></div>
                    <div class="absolute inset-0 border border-white/10 bg-slate-900 rounded-none p-2 shadow-2xl overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1468495244123-6c6c332eeece?q=80&w=800" class="w-full h-full object-cover filter grayscale contrast-125" alt="Material Showcase">
                    </div>
                </div>
            </div>

            <div class="lg:col-span-6 space-y-6 text-left">
                <h2 class="text-3xl font-black tracking-tight uppercase leading-tight text-white">
                    THE BEST RENTAL SERVICE WITH <br><span class="text-blue-500">PREMIUM SYSTEM MANAGEMENT</span>
                </h2>
                <p class="text-slate-400 text-sm font-light leading-relaxed">
                    Setiap unit perangkat keras atau peralatan yang disewa melalui sistem manajemen sewa ini dijamin telah melewati pemeriksaan pemeliharaan menyeluruh demi menjamin user experience.
                </p>
                <div class="space-y-3 pt-2 text-xs font-medium text-slate-300">
                    <div class="flex items-center gap-3">
                        <span class="text-blue-500 text-sm">✓</span>
                        <span>Service Fast Response</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-blue-500 text-sm">✓</span>
                        <span>High-Quality Maintenance Secara Berkala</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-blue-500 text-sm">✓</span>
                        <span>Proteksi Penuh &amp; Asuransi Keamanan Transaksi</span>
                    </div>
                </div>
                <div class="pt-4">
                    <a href="/catalog" class="inline-block bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs px-8 py-4 rounded-none uppercase tracking-widest transition border-0">
                        Jelajahi Sekarang
                    </a>
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
                                <p class="text-xs text-slate-300 leading-relaxed font-light">"Kondisi unitnya luar biasa terawat, bersih seperti baru gres. Sistem transaksinya instan tanpa ribet."</p>
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
            document.querySelectorAll('.pop-filter-btn').forEach(btn => {
                btn.classList.remove('bg-blue-600', 'text-white', 'border-blue-600');
                btn.classList.add('bg-white/5', 'text-slate-400', 'border-white/10');
            });
            element.classList.remove('bg-white/5', 'text-slate-400', 'border-white/10');
            element.classList.add('bg-blue-600', 'text-white', 'border-blue-600');

            const container = document.getElementById('popularContainer');
            const cards = Array.from(document.querySelectorAll('.popular-card'));
            
            cards.forEach(card => {
                card.classList.remove('podium-1', 'podium-2', 'podium-3');
                card.style.display = 'none';
            });

            let validCards = cards.filter(card => {
                return slug === 'all' || card.getAttribute('data-category') === slug;
            });

            validCards.sort((a, b) => {
                return parseInt(b.getAttribute('data-rented')) - parseInt(a.getAttribute('data-rented'));
            });

            let topThree = validCards.slice(0, 3);

            if (topThree.length > 0) {
                document.getElementById('popEmptyMessage').classList.add('hidden');
                
                let podiumArr = [];
                if (topThree.length === 1) {
                    topThree[0].classList.add('podium-1');
                    podiumArr = [topThree[0]];
                } else if (topThree.length === 2) {
                    topThree[0].classList.add('podium-1');
                    topThree[1].classList.add('podium-2');
                    podiumArr = [topThree[1], topThree[0]];
                } else if (topThree.length === 3) {
                    topThree[0].classList.add('podium-1');
                    topThree[1].classList.add('podium-2');
                    topThree[2].classList.add('podium-3');
                    podiumArr = [topThree[1], topThree[0], topThree[2]];
                }

                podiumArr.forEach(card => {
                    card.style.display = 'block';
                    container.appendChild(card);
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
    </script>
</body>
</html>