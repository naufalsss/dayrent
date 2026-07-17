<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Unit - {{ $configs['app_name'] ?? 'Day-Rent' }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite('resources/css/app.css')
</head>
<body class="bg-slate-950 text-white min-h-screen relative overflow-x-hidden" style="font-family: 'Poppins', sans-serif;">

    <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 relative z-10">
        
        <!-- HEADER NAVBAR UTAMA -->
        <nav id="mainNavbar" class="sticky top-6 z-50 transition-all duration-300 backdrop-blur-md bg-white/10 border border-white/20 rounded-md px-6 py-4 flex items-center justify-between shadow-lg mb-12">
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
                <a href="/" class="text-slate-300 hover:text-white transition">Beranda</a>
                <a href="/catalog" class="text-white border-b-2 border-blue-500 pb-1">Katalog</a>
                <a href="/guide" class="text-slate-300 hover:text-white transition">Panduan</a>
                <a href="/bantuan" class="text-slate-300 hover:text-white transition">Bantuan</a>
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
                                    <p class="text-[11px] font-black text-amber-400 leading-none">{{ $notif->title }}</p>
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
                                <a href="/admin/rentals" class="block px-3 py-2.5 text-xs font-semibold text-slate-200 hover:text-white hover:bg-white/5 rounded-md transition">Dashboard</a>
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

        <!-- CONTENT FILTERS & SEARCH -->
        <div class="w-full max-w-4xl mx-auto mb-16 space-y-6">
            <div class="flex justify-center w-full">
                <form action="/catalog" method="GET" class="w-full max-w-2xl relative">
                    <input type="hidden" name="category" value="{{ $categorySlug }}">
                    
                    <div class="relative flex items-center">
                        <div class="absolute left-4 pointer-events-none z-10 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" id="Outline" viewBox="0 0 24 24" class="w-4 h-4 fill-slate-400 transition duration-200">
                                <path d="M23.707,22.293l-5.969-5.969a10.016,10.016,0,1,0-1.414,1.414l5.969,5.969a1,1,0,0,0,1.414-1.414ZM10,18a8,8,0,1,1,8-8A8.009,8.009,0,0,1,10,18Z"/>
                            </svg>
                        </div>
                        
                        <input type="text" 
                               name="search" 
                               value="{{ $search }}" 
                               placeholder="Cari unit sewa favoritmu..." 
                               class="w-full bg-white/5 border border-white/10 text-white text-sm pl-12 pr-24 py-3.5 rounded-md focus:outline-none focus:border-blue-500 transition placeholder-slate-500">
                        
                        <button type="submit" 
                                 class="absolute right-2 bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs px-4 py-2 rounded-md transition uppercase tracking-wider cursor-pointer border-0">
                            Cari
                        </button>
                    </div>
                </form>
            </div>

            <div class="flex flex-wrap justify-center gap-2.5 pt-1">
                <a href="/catalog?category=all&search={{ $search }}" 
                   class="px-5 py-2 text-xs font-bold uppercase tracking-wider rounded-none border transition whitespace-nowrap {{ $categorySlug === 'all' ? 'bg-blue-600 text-white border-blue-600 font-extrabold shadow-lg' : 'bg-white/5 text-slate-400 border-white/10 hover:border-slate-500 hover:text-white' }}">
                    Semua
                </a>
                @foreach($categories as $cat)
                    <a href="/catalog?category={{ $cat->slug }}&search={{ $search }}" 
                       class="px-5 py-2 text-xs font-bold uppercase tracking-wider rounded-none border transition whitespace-nowrap {{ $categorySlug === $cat->slug ? 'bg-blue-600 text-white border-blue-600 font-extrabold' : 'bg-white/5 text-slate-400 border-white/10 hover:border-slate-500 hover:text-white' }}">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- MAIN CATALOG WRAPPER WITH CENTERED WHITE HEADERS & BALANCED GRID -->
        @if($items->count() > 0)
            @php
                $groupedItems = $items->groupBy('category_name');
            @endphp

            <div class="space-y-24 mb-24">
                @foreach($groupedItems as $catName => $catProducts)
                    <div class="w-full space-y-10">
                        
                        <!-- FIX: TEKS BESAR WARNA PUTIH DI TENAH + GARIS AKSEN BAWAH -->
                        <div class="text-center w-full max-w-xl mx-auto space-y-2">
                            <h2 class="text-3xl md:text-4xl font-black uppercase tracking-widest text-white drop-shadow-lg">
                                {{ $catName }}
                            </h2>
                            <div class="h-[1px] w-24 bg-blue-500 mx-auto"></div>
                        </div>

                        <!-- GRID FLUID DENGAN RATA TENGAH (ANTI MEPET KIRI JIKA SISA 2 CARD) -->
                        <div class="flex flex-wrap justify-center gap-6">
                            @foreach($catProducts as $index => $item)
                                <div class="catalog-fade-card relative w-full sm:w-[calc(50%-12px)] md:w-[calc(33.333%-16px)] lg:w-[calc(25%-18px)] aspect-[3/4] rounded-none overflow-hidden shadow-2xl group border border-white/10 hover:border-blue-500/40 transition duration-300 shrink-0"
                                     style="--card-delay: {{ ($index % 4) * 0.12 }}s;">
                                    
                                    <!-- FIX LOGIC BREE: Mengamankan deteksi string folder dari admin maupun folder merchant -->
                                    <img src="{{ (str_starts_with($item->image, '/storage/') || str_starts_with($item->image, 'storage/')) ? asset(ltrim($item->image, '/')) : asset('storage/' . $item->image) }}" 
                                         alt="{{ $item->name }}" 
                                         class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-500 z-0">
                                    
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent z-10"></div>

                                    <div class="absolute inset-0 p-5 z-20 flex flex-col justify-between">
                                        <div class="flex justify-between items-start">
                                            <div class="backdrop-blur-md bg-black/40 text-violet-400 border border-violet-500/30 px-2.5 py-1 rounded-none text-[9px] font-black uppercase tracking-wider">
                                                {{ $item->category_name }}
                                            </div>
                                            <div class="backdrop-blur-md bg-black/40 text-amber-400 border border-amber-500/20 px-2 py-0.5 rounded-none text-[10px] font-bold flex items-center gap-1">
                                                <span>⭐</span>
                                                <span class="text-white">{{ $item->rating > 0 ? number_format($item->rating, 1) : 'New' }}</span>
                                            </div>
                                        </div>

                                        <div class="space-y-3">
                                            <div>
                                                <h3 class="text-sm font-extrabold text-white tracking-tight leading-tight line-clamp-2 drop-shadow-md">
                                                    {{ $item->name }}
                                                </h3>
                                                <div class="flex items-center gap-3 text-[10px] text-slate-300 font-medium mt-1 drop-shadow">
                                                    <span>Tersewa: <strong class="text-white">{{ $item->total_rented ?? 0 }}</strong></span>
                                                    <span class="text-white/20">|</span>
                                                    <span>Stok: <strong class="{{ $item->stock > 0 ? 'text-emerald-400' : 'text-rose-400' }}">{{ $item->stock }}</strong></span>
                                                </div>
                                            </div>

                                            <div>
                                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">Harga Sewa</p>
                                                <p class="text-base font-black text-white tracking-tight leading-none drop-shadow-md">
                                                    Rp {{ is_numeric(str_replace('.', '', $item->price)) ? number_format(str_replace('.', '', $item->price), 0, ',', '.') : $item->price }}
                                                    <span class="text-slate-400 font-normal text-[11px] tracking-normal">/hari</span>
                                                </p>
                                            </div>

                                            @if($item->stock > 0)
                                                <a href="{{ route('items.checkout', $item->id) }}" 
                                                   class="w-full bg-blue-600/90 hover:bg-blue-500 backdrop-blur-sm text-white font-bold text-[11px] py-3 px-4 rounded-none transition duration-200 cursor-pointer flex items-center justify-between tracking-wider uppercase border-0 group/btn">
                                                    <span>Sewa Sekarang</span>
                                                    <span class="text-sm transform group-hover/btn:translate-x-1 transition duration-200">→</span>
                                                </a>
                                            @else
                                                <button disabled 
                                                        class="w-full bg-rose-950/60 backdrop-blur-sm text-rose-400 border border-rose-500/30 font-bold text-[11px] py-3 px-4 rounded-none cursor-not-allowed tracking-wider uppercase flex items-center justify-center">
                                                    Sedang Disewa
                                                </button>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-20 backdrop-blur-md bg-white/5 border border-white/10 rounded-none w-full mb-24">
                <span class="text-5xl block mb-4">🔍</span>
                <h3 class="text-lg font-bold text-white">Unit Tidak Ditemukan</h3>
                <p class="text-xs text-slate-400 mt-1">Maaf, kata kunci pencarian atau kategori sewa yang kamu pilih belum tersedia.</p>
                <a href="/catalog" class="inline-block mt-6 text-xs bg-white/10 hover:bg-white/20 px-4 py-2.5 rounded-none font-bold transition">Reset Filter</a>
            </div>
        @endif

    </div>

    <!-- RATING MODAL BOX -->
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

                <div class="flex gap-2 pt-2">
                    <button type="button" onclick="closeRatingModal()" class="flex-1 bg-white/5 hover:bg-white/10 text-slate-300 text-xs font-bold py-3 rounded-none transition cursor-pointer border-0">Batal</button>
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-500 text-white text-xs font-black py-3 rounded-none transition shadow-md shadow-blue-600/20 cursor-pointer border-0 uppercase tracking-wider">Kirim Rating</button>
                </div>
            </form>
        </div>
    </div>

    <!-- FOOTER EKSKLUSIF ORIGINAL -->
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
                        <li><a href="/bantuan" class="hover:text-white transition">Pusat Bantuan</a></li>
                    </ul>
                </div>

                <div class="md:col-span-3 space-y-3">
                    <h4 class="text-xs font-bold uppercase tracking-widest text-blue-400">Kontak Proyek</h4>
                    <ul class="space-y-2 text-xs text-slate-400">
                        <li class="flex items-center gap-2"><span>📍</span> Universitas Logistik dan Bisnis Internasional</li>
                        <li class="flex items-center gap-2"><span>✉️</span> suppdayrent@gmail.com</li>
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

    <!-- STYLES CORE ENGINE -->
    <style>
        .catalog-fade-card {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.6s cubic-bezier(0.25, 1, 0.5, 1), transform 0.6s cubic-bezier(0.25, 1, 0.5, 1);
            transition-delay: var(--card-delay, 0s);
            will-change: opacity, transform;
        }
        .catalog-fade-card.show-card {
            opacity: 1;
            transform: translateY(0);
        }

        @keyframes floatUp {
            0% { transform: translateY(0) scale(1); opacity: 0.8; }
            100% { transform: translateY(-300px) scale(0.4); opacity: 0; }
        }
        .animate-float-slow { animation: floatUp 8s infinite linear; }
        .animate-float-medium { animation: floatUp 6s infinite linear; animation-delay: 2s; }
        .animate-float-fast { animation: floatUp 4s infinite linear; animation-delay: 1s; }
    </style>

    <!-- INTERACTIVE SCRIPTS -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // ENGINE INTERSECTION OBSERVER FADE UP
            const cards = document.querySelectorAll(".catalog-fade-card");
            const observerOptions = {
                root: null,
                threshold: 0.05,
                rootMargin: "0px 0px -30px 0px"
            };

            const cardObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add("show-card");
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            cards.forEach(card => cardObserver.observe(card));

            // NAVBAR SCROLL ENGINE
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
        });

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
</body>
</html>