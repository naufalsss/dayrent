<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panduan Penyewaan - {{ $configs['app_name'] ?? 'Day-Rent' }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite('resources/css/app.css')
</head>
<body class="bg-slate-950 text-white min-h-screen relative overflow-x-hidden" style="font-family: 'Poppins', sans-serif;">

    <!-- Background Efek Glow -->
    <div class="absolute top-0 right-1/4 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-1/3 left-1/4 w-96 h-96 bg-violet-500/5 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 relative z-10">
        
        <!-- HEADER NAVBAR UTAMA -->
        <nav id="mainNavbar" class="sticky top-6 z-50 transition-all duration-300 backdrop-blur-md bg-white/10 border border-white/20 rounded-md px-6 py-4 flex items-center justify-between shadow-lg mb-16">
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
                <a href="/catalog" class="text-slate-300 hover:text-white transition">Katalog</a>
                <a href="/guide" class="text-white border-b-2 border-blue-500 pb-1">Panduan</a>
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

        <!-- TITLE HEADLINE -->
        <div class="text-center w-full max-w-xl mx-auto mb-16 space-y-3 scroll-fade-up">
            <h1 class="text-3xl md:text-4xl font-black uppercase tracking-widest text-white drop-shadow-lg">
                HOW TO RENT
            </h1>
            <p class="text-xs text-slate-400">Ikuti 4 langkah mudah berikut untuk melakukan penyewaan unit premium di Day-Rent.</p>
            <div class="h-[1px] w-24 bg-blue-500 mx-auto"></div>
        </div>

        <!-- TIMELINE CONTAINER -->
        <div class="relative max-w-4xl mx-auto mb-24">
            <!-- Garis Tengah Timeline (Desktop) -->
            <div class="hidden md:block absolute left-1/2 transform -translate-x-1/2 w-[1px] h-full bg-gradient-to-b from-blue-500 via-violet-500 to-transparent z-0 opacity-30"></div>

            <div class="space-y-12 relative z-10">
                
                <!-- LANGKAH 1 -->
                <div class="flex flex-col md:flex-row items-center justify-between scroll-fade-up">
                    <div class="w-full md:w-[calc(50%-30px)] text-left md:text-right order-2 md:order-1">
                        <div class="backdrop-blur-md bg-white/5 border border-white/10 p-6 rounded-none shadow-xl hover:border-blue-500/40 transition duration-300 group">
                            <span class="text-[10px] font-black uppercase tracking-widest text-blue-400 bg-blue-500/10 px-2.5 py-1 border border-blue-500/20">Langkah 01</span>
                            <h3 class="text-base font-extrabold text-white mt-3 group-hover:text-blue-400 transition">Eksplorasi & Pilih Unit</h3>
                            <p class="text-xs text-slate-400 mt-2 leading-relaxed">
                                Buka halaman <a href="/catalog" class="text-blue-400 hover:underline">Katalog</a>, cari unit yang kamu butuhkan. Gunakan filter kategori untuk mempercepat pencarian. Pastikan status unit tertulis <strong class="text-emerald-400">Sewa Sekarang</strong> (Stok tersedia).
                            </p>
                        </div>
                    </div>
                    <!-- Node Tengah -->
                    <div class="w-10 h-10 bg-slate-900 border-2 border-blue-500 rounded-full flex items-center justify-center font-bold text-sm text-white z-20 my-4 md:my-0 order-1 md:order-2 shadow-lg shadow-blue-500/20">1</div>
                    <div class="hidden md:block w-[calc(50%-30px)] order-3"></div>
                </div>

                <!-- LANGKAH 2 -->
                <div class="flex flex-col md:flex-row items-center justify-between scroll-fade-up">
                    <div class="hidden md:block w-[calc(50%-30px)]"></div>
                    <!-- Node Tengah -->
                    <div class="w-10 h-10 bg-slate-900 border-2 border-violet-500 rounded-full flex items-center justify-center font-bold text-sm text-white z-20 my-4 md:my-0 shadow-lg shadow-violet-500/20">2</div>
                    <div class="w-full md:w-[calc(50%-30px)] text-left p-0">
                        <div class="backdrop-blur-md bg-white/5 border border-white/10 p-6 rounded-none shadow-xl hover:border-violet-500/40 transition duration-300 group">
                            <span class="text-[10px] font-black uppercase tracking-widest text-violet-400 bg-violet-500/10 px-2.5 py-1 border border-violet-500/20">Langkah 02</span>
                            <h3 class="text-base font-extrabold text-white mt-3 group-hover:text-violet-400 transition">Checkout & Pengisian Data</h3>
                            <p class="text-xs text-slate-400 mt-2 leading-relaxed">
                                Klik tombol order, kamu akan dialihkan ke halaman formulir sewa. Isi **tanggal mulai**, **durasi sewa (hari)**, dan lengkapi berkas jaminan yang diminta sistem untuk keperluan verifikasi keamanan  Day-Rent.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- LANGKAH 3 -->
                <div class="flex flex-col md:flex-row items-center justify-between scroll-fade-up">
                    <div class="w-full md:w-[calc(50%-30px)] text-left md:text-right order-2 md:order-1">
                        <div class="backdrop-blur-md bg-white/5 border border-white/10 p-6 rounded-none shadow-xl hover:border-blue-500/40 transition duration-300 group">
                            <span class="text-[10px] font-black uppercase tracking-widest text-blue-400 bg-blue-500/10 px-2.5 py-1 border border-blue-500/20">Langkah 03</span>
                            <h3 class="text-base font-extrabold text-white mt-3 group-hover:text-blue-400 transition">Konfirmasi & Ambil Barang</h3>
                            <p class="text-xs text-slate-400 mt-2 leading-relaxed">
                                Tim Admin kami akan melakukan validasi pesananmu dengan cepat. Setelah disetujui, kamu bisa datang langsung ke *pick-up point* Day-Rent atau menunggu kurir mengantarkan unit pesananmu sesuai jadwal.
                            </p>
                        </div>
                    </div>
                    <!-- Node Tengah -->
                    <div class="w-10 h-10 bg-slate-900 border-2 border-blue-500 rounded-full flex items-center justify-center font-bold text-sm text-white z-20 my-4 md:my-0 order-1 md:order-2 shadow-lg shadow-blue-500/20">3</div>
                    <div class="hidden md:block w-[calc(50%-30px)] order-3"></div>
                </div>

                <!-- LANGKAH 4 -->
                <div class="flex flex-col md:flex-row items-center justify-between scroll-fade-up">
                    <div class="hidden md:block w-[calc(50%-30px)]"></div>
                    <!-- Node Tengah -->
                    <div class="w-10 h-10 bg-slate-900 border-2 border-violet-500 rounded-full flex items-center justify-center font-bold text-sm text-white z-20 my-4 md:my-0 shadow-lg shadow-violet-500/20">4</div>
                    <div class="w-full md:w-[calc(50%-30px)] text-left">
                        <div class="backdrop-blur-md bg-white/5 border border-white/10 p-6 rounded-none shadow-xl hover:border-violet-500/40 transition duration-300 group">
                            <span class="text-[10px] font-black uppercase tracking-widest text-violet-400 bg-violet-500/10 px-2.5 py-1 border border-violet-500/20">Langkah 04</span>
                            <h3 class="text-base font-extrabold text-white mt-3 group-hover:text-violet-400 transition">Selesai & Beri Rating</h3>
                            <p class="text-xs text-slate-400 mt-2 leading-relaxed">
                                Jika durasi sewa telah berakhir, kembalikan unit dengan baik. Setelah admin memperbarui status transaksi, lonceng notifikasi akunmu akan berbunyi otomatis. Buka notifikasi tersebut untuk memberikan **bintang rating testimoni** demi mendukung project kami!
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- CTA JELAJAH KEMBALI -->
        <div class="text-center scroll-fade-up mb-12">
            <p class="text-xs text-slate-400 mb-4">Sudah paham seluruh prosedurnya? Yuk langsung cari unit favoritmu.</p>
            <a href="/catalog" class="inline-block bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs px-8 py-4 rounded-none uppercase tracking-widest transition border-0 shadow-lg shadow-blue-600/20">
                Buka Katalog Rental
            </a>
        </div>

    </div>

    <!-- RATING MODAL BOX -->
    <div id="ratingModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm hidden items-center justify-center z-[100] p-4 transition-all duration-300">
        <div class="bg-slate-900 border border-white/10 rounded-none p-6 max-w-sm w-full shadow-2xl space-y-4 text-center transform scale-95 transition-transform duration-300">
            <div class="w-12 h-12 bg-amber-500/10 text-amber-400 rounded-full flex items-center justify-center text-xl mx-auto font-bold">⭐</div>
            <div>
                <h3 class="text-base font-extrabold text-white" id="modalNotifTitle">Beri Penilaian Unit</h3>
                <p class="text-xs text-slate-400 mt-1">Bagaimana pengalaman sewa unit Anda?</p>
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
                    <button type="button" onclick="closeRatingModal()" class="flex-1 bg-white/5 hover:bg-white/10 text-slate-300 text-xs font-bold py-3 rounded-none transition border-0">Batal</button>
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-500 text-white text-xs font-black py-3 rounded-none transition shadow-md shadow-blue-600/20 border-0 uppercase tracking-wider">Kirim Rating</button>
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

    <!-- STYLES ENGINE FOR SCROLL FADE UP & PARTICLES -->
    <style>
        .scroll-fade-up {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.7s cubic-bezier(0.25, 1, 0.5, 1), transform 0.7s cubic-bezier(0.25, 1, 0.5, 1);
            will-change: opacity, transform;
        }
        .scroll-fade-up.show {
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
            // OBSERVER SCROLL FADE UP ENGINE
            const fadeElements = document.querySelectorAll(".scroll-fade-up");
            const observerOptions = {
                root: null,
                threshold: 0.1,
                rootMargin: "0px 0px -40px 0px"
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

            // NAVBAR SCROLL DECORATION
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