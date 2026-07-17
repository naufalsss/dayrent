<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout {{ $item->name }} - {{ $configs['app_name'] ?? 'Day-Rent' }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    @vite('resources/css/app.css')
    <style>
        body { font-family: 'Poppins', sans-serif; }
        
        /* BALIK KE LIGHT MODE KONTEN SEMULA */
        .checkout-card { background-color: #ffffff; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03); }
        .input-checkout { background-color: #f8fafc !important; border: 1px solid #cbd5e1 !important; color: #0f172a !important; }
        .input-checkout:focus { border-color: #1e3a8a !important; background-color: #ffffff !important; outline: none; box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1); }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen relative">

    <!-- TOAST NOTIFICATION KUSTOM PREMIUM -->
    <div id="customToast" class="fixed top-28 left-1/2 transform -translate-x-1/2 z-[100] translate-y-[-20px] opacity-0 pointer-events-none transition-all duration-300 ease-out max-w-sm w-full flex items-center p-4 rounded-2xl shadow-xl">
        <div id="toastIcon" class="flex-shrink-0 w-8 h-8 rounded-xl flex items-center justify-center font-bold text-xs shadow-sm"></div>
        <div class="ml-3 text-xs font-bold text-slate-800 tracking-tight" id="toastMessage"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-12">
        
        <!-- FIX UTAMA: NAVBAR LANGSUNG GELAP TOTAL BG-SLATE-950 DARI AWAL (ANTI-TRANSPARAN) -->
        <nav id="mainNavbar" class="sticky top-6 z-50 bg-blue-950 border border-white/10 rounded-md px-6 py-4 flex items-center justify-between shadow-lg mb-12">
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
                        <button onclick="toggleNotifDropdown()" id="notifButton" class="relative bg-white/5 border border-white/10 p-2.5 rounded-md hover:bg-white/10 hover:border-white/20 transition cursor-pointer flex items-center justify-center group border-0 bg-transparent">
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

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <div class="lg:col-span-5 space-y-6">
                <div class="w-full aspect-square bg-slate-200 rounded-3xl overflow-hidden border border-slate-200 shadow-md">
                    <!-- FIX LOGIC BREE: Pengecekan cerdas string letak penyimpanan gambar -->
                    <img src="{{ (str_starts_with($item->image, '/storage/') || str_starts_with($item->image, 'storage/')) ? asset(ltrim($item->image, '/')) : asset('storage/' . $item->image) }}" 
                         alt="{{ $item->name }}" 
                         class="w-full h-full object-cover">
                </div>

                <div class="checkout-card p-6 rounded-3xl space-y-5">
                    <div class="pb-4 border-b border-slate-100">
                        <span class="text-xs font-bold uppercase tracking-widest text-blue-600 bg-blue-50 px-2.5 py-1 rounded-md">{{ $item->category->name }}</span>
                        <h1 class="text-2xl font-black text-slate-900 mt-3 leading-tight tracking-tight">{{ $item->name }}</h1>
                    </div>

                    <div class="space-y-2">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Deskripsi Unit</h4>
                        <p class="text-sm text-slate-600 leading-relaxed">
                            Unit {{ $item->name }} dalam kondisi prima, bersih, dan siap pakai untuk menunjang kebutuhan harian Anda. 
                            Dilengkapi dengan fitur utama: <span class="text-blue-600 font-semibold">{{ str_replace(',', ' • ', $item->features) }}</span>.
                        </p>
                    </div>

                    <div class="p-4 rounded-2xl bg-blue-50 border border-blue-100 flex gap-3 items-start">
                        <span class="text-xl">🛡️</span>
                        <div>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-blue-800">Garansi Day-Rent menjamin</h4>
                            <p class="text-[11px] text-slate-600 mt-1 leading-normal">Jaminan unit pengganti atau pengembalian dana penuh jika unit mengalami kendala operasional saat serah terima.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-7 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-md">
                
                <div class="bg-blue-950 p-6 text-white">
                    <h2 class="text-lg font-extrabold tracking-tight uppercase flex items-center gap-2">
                         Lengkapi Informasi Penyewaan
                    </h2>
                    <p class="text-xs text-blue-200 mt-1">Pastikan data anda benar sebelum melakukan pembayaran</p>
                </div>

                <div class="p-6 lg:p-8 space-y-6">
                    <form action="{{ route('items.checkout.store', $item->id) }}" method="POST" class="space-y-5" id="mainCheckoutForm">
                        @csrf 
                        
                        <!-- HIDDEN DATA -->
                        @php $rawPrice = (int) str_replace('.', '', $item->price); @endphp
                        <input type="hidden" id="rawBasePrice" value="{{ $rawPrice }}">
                        <input type="hidden" id="hiddenPromoApplied" name="applied_promo_code" value="">

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-400 uppercase">Nama Lengkap Sesuai KTP</label>
                            <input type="text" name="customer_name" 
                                value="{{ old('customer_name', auth()->user()->name) }}" required
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs font-medium text-slate-700"
                                placeholder="Masukkan nama lengkap Anda...">
                        </div>

                        <div class="space-y-1.5 mt-4">
                            <label class="text-xs font-bold text-slate-400 uppercase">Nomor WhatsApp Aktif</label>
                            <input type="text" name="whatsapp_number" 
                                value="{{ old('whatsapp_number', auth()->user()->whatsapp_number) }}" required
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs font-medium text-slate-700"
                                placeholder="Contoh: 081234567xxx">
                        </div>
                        
                        <div>
                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-2">
                                Durasi Penyewaan (Per {{ ucfirst($item->rent_mode) }})
                            </label>
                            <div class="flex items-center gap-3">
                                <input type="number" 
                                    name="duration" 
                                    id="rentalDuration"
                                    min="1" 
                                    value="1" 
                                    required 
                                    class="w-full input-checkout rounded-xl px-4 py-3.5 text-xs font-semibold outline-none focus:border-blue-900 transition text-center font-bold"
                                    oninput="updateTotalHarga()">
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wide">{{ $item->rent_mode }}</span>
                            </div>
                        </div>

                        <div class="flex flex-col">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Kode Voucher (Opsional)</label>
                            <div class="flex gap-2 mt-2">
                                <input type="text" id="inputPromoCode" placeholder="Contoh: DAYRENTPROMO" class="input-checkout rounded-xl py-3 px-4 text-sm font-light tracking-wider flex-1 focus:ring-2 focus:ring-blue-950/20">
                                <button type="button" id="btnApplyPromo" class="bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs px-5 rounded-xl transition cursor-pointer border-0">Gunakan</button>
                            </div>
                        </div>

                        <div class="pt-5 mt-6 border-t border-slate-100 space-y-3 bg-slate-50 p-4 rounded-2xl border border-slate-200">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Ringkasan Biaya</h3>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-slate-500 font-medium">Harga Dasar (1 {{ ucfirst($item->rent_mode) }})</span>
                                <span class="font-bold text-slate-800">Rp {{ number_format($rawPrice, 0, ',', '.') }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-slate-500 font-medium">Subtotal Sewa</span>
                                <span class="font-bold text-slate-800" id="displaySubtotal">Rp {{ number_format($rawPrice, 0, ',', '.') }}</span>
                            </div>

                            <!-- ELEMEN PENGURANG HARGA VOUCHER -->
                            <div id="rowDiscountBox" class="flex justify-between items-center text-sm text-emerald-600 hidden">
                                <span class="font-medium flex items-center gap-1">Diskon Voucher (<span id="txtAppliedLabel" class="font-bold"></span>)</span>
                                <span class="font-extrabold" id="displayDiscount">- Rp 0</span>
                            </div>

                            <div class="flex justify-between items-center pt-3 border-t border-slate-200">
                                <span class="text-sm font-bold text-slate-700">Total Pembayaran</span>
                                <span id="totalBayarText" class="text-xl font-black text-blue-900 tracking-tight">Rp {{ number_format($rawPrice, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="w-full bg-blue-950 hover:bg-blue-900 text-white font-black text-xs py-4 rounded-2xl shadow-md transition duration-200 tracking-wider uppercase cursor-pointer border-0">
                                Lanjutkan Pemesanan ↗
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- RATING MODAL BOX -->
    <div id="ratingModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm hidden items-center justify-center z-[100] p-4 transition-all duration-300">
        <div class="bg-slate-900 border border-white/10 rounded-xl p-6 max-w-sm w-full shadow-2xl space-y-4 text-center transform scale-95 transition-transform duration-300">
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
                    <button type="button" onclick="closeRatingModal()" class="flex-1 bg-white/5 hover:bg-white/10 text-slate-300 text-xs font-bold py-3 rounded-xl transition border-0">Batal</button>
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-500 text-white text-xs font-black py-3 rounded-xl transition shadow-md shadow-blue-600/20 border-0 uppercase tracking-wider">Kirim Rating</button>
                </div>
            </form>
        </div>
    </div>

    <!-- SCRIPT ENGINE (KUNCI WARNA SINKRON) -->
    <script>
        let currentDiscount = 0;
        let toastTimeout = null;

        // DROPDOWN ENGINE
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

        // FUNGSI TOAST KUSTOM
        function showToast(message, type) {
            const toast = document.getElementById('customToast');
            const iconBox = document.getElementById('toastIcon');
            const msgBox = document.getElementById('toastMessage');
            
            msgBox.innerText = message;
            if (toastTimeout) clearTimeout(toastTimeout);
            
            if (type === 'success') {
                toast.className = "fixed top-28 left-1/2 transform -translate-x-1/2 z-[100] max-w-sm w-full flex items-center p-4 bg-emerald-50 border border-emerald-200 rounded-2xl shadow-lg transition-all duration-300 ease-out";
                iconBox.className = "flex-shrink-0 w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center font-bold text-xs shadow-sm shadow-emerald-500/20";
                iconBox.innerText = "✓";
            } else {
                toast.className = "fixed top-28 left-1/2 transform -translate-x-1/2 z-[100] max-w-sm w-full flex items-center p-4 bg-rose-50 border border-rose-200 rounded-2xl shadow-lg transition-all duration-300 ease-out";
                iconBox.className = "flex-shrink-0 w-8 h-8 rounded-xl bg-rose-500 text-white flex items-center justify-center font-bold text-xs shadow-sm shadow-rose-500/20";
                iconBox.innerText = "🚨";
            }
            
            toast.classList.remove('opacity-0', 'translate-y-[-20px]', 'pointer-events-none');
            toast.classList.add('opacity-100', 'translate-y-0');
            
            toastTimeout = setTimeout(() => {
                toast.classList.remove('opacity-100', 'translate-y-0');
                toast.classList.add('opacity-0', 'translate-y-[-20px]', 'pointer-events-none');
            }, 3000);
        }

        function updateTotalHarga() {
            const basePrice = parseFloat(document.getElementById('rawBasePrice').value);
            const duration = parseFloat(document.getElementById('rentalDuration').value) || 1;
            
            const subtotal = basePrice * duration;
            document.getElementById('displaySubtotal').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(subtotal);

            currentDiscount = 0;
            document.getElementById('rowDiscountBox').classList.add('hidden');
            document.getElementById('hiddenPromoApplied').value = '';
            document.getElementById('inputPromoCode').value = '';

            document.getElementById('totalBayarText').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(subtotal);
        }

        document.getElementById('btnApplyPromo').addEventListener('click', function(e) {
            e.preventDefault();
            
            const promoCodeInput = document.getElementById('inputPromoCode').value.trim();
            const basePrice = parseFloat(document.getElementById('rawBasePrice').value);
            const duration = parseFloat(document.getElementById('rentalDuration').value) || 1;
            const subtotalValue = basePrice * duration;

            if (!promoCodeInput) {
                showToast('Silakan ketikkan kode kupon promonya dulu, Bree!', 'error');
                return;
            }

            fetch('{{ route('checkout.applyPromo') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    promo_code: promoCodeInput,
                    subtotal: subtotalValue
                })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    currentDiscount = data.discount;
                    
                    document.getElementById('txtAppliedLabel').innerText = data.promo_code_string;
                    document.getElementById('displayDiscount').innerText = '- Rp ' + new Intl.NumberFormat('id-ID').format(data.discount);
                    document.getElementById('rowDiscountBox').classList.remove('hidden');
                    
                    document.getElementById('totalBayarText').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(data.grand_total);
                    document.getElementById('hiddenPromoApplied').value = data.promo_code_string;
                    
                    showToast(data.message, 'success');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                
                currentDiscount = 0;
                document.getElementById('rowDiscountBox').classList.add('hidden');
                document.getElementById('hiddenPromoApplied').value = '';
                document.getElementById('totalBayarText').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(subtotalValue);
                
                const errorText = error.message || 'Gagal memproses kode kupon, periksa masa berlaku kuota diskon.';
                showToast(errorText, 'error');
            });
        });
    </script>
</body>
</html>