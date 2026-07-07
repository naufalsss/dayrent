<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout {{ $item->name }} - {{ $configs['app_name'] ?? 'Day-Rent' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        body { font-family: 'Poppins', sans-serif; }
        /* Light Mode Shadow & Border Smooth */
        .checkout-card { background-color: #ffffff; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03); }
        .input-checkout { background-color: #f8fafc !important; border: 1px solid #cbd5e1 !important; color: #0f172a !important; }
        .input-checkout:focus { border-color: #1e3a8a !important; background-color: #ffffff !important; outline: none; box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1); }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-12">
        
        <nav id="mainNavbar" class="sticky top-6 z-50 transition-all duration-300 bg-blue-950 border border-white/10 rounded-2xl px-6 py-4 flex items-center justify-between shadow-lg mb-12">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center font-bold text-white text-lg">
                    {{ substr($configs['app_name'] ?? 'D', 0, 1) }}
                </div>
                <span class="font-extrabold text-xl tracking-wider text-white">{{ $configs['app_name'] ?? 'DAY-RENT' }}</span>
            </div>

            <div class="hidden md:flex items-center gap-8 font-medium">
                <a href="/" class="text-slate-400 hover:text-white transition">Beranda</a>
                <a href="/#itemsGrid" class="text-slate-400 hover:text-white transition">Katalog</a>
                <a href="/bantuan" class="text-slate-300 hover:text-white transition">Bantuan</a>
            </div>

            <div>
                @auth
                    <div class="flex items-center gap-3 bg-white/5 border border-white/10 p-1.5 pr-4 rounded-full">
                        <img class="h-9 w-9 rounded-full object-cover border-2 border-blue-500" 
                             src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=0D8ABC&color=fff" 
                             alt="User avatar">
                        <div class="text-left leading-tight hidden sm:block">
                            <p class="text-xs font-semibold text-white">{{ auth()->user()->name }}</p>
                            <p class="text-[10px] text-slate-400">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                @else
                    <a href="/login" class="bg-blue-600 hover:bg-blue-500 text-white px-5 py-2 rounded-xl font-semibold shadow-md shadow-blue-600/30 transition duration-200 text-sm">
                        Masuk Akun
                    </a>
                @endauth
            </div>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <div class="lg:col-span-5 space-y-6">
                <div class="w-full aspect-square bg-slate-200 rounded-3xl overflow-hidden border border-slate-200 shadow-md">
                    <img src="{{ asset($item->image) }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                </div>

                <div class="checkout-card p-6 rounded-3xl space-y-5">
                    <div class="pb-4 border-b border-slate-100">
                        <span class="text-xs font-bold uppercase tracking-widest text-blue-600 bg-blue-50 px-2.5 py-1 rounded-md">{{ $item->category->name }}</span>
                        <h1 class="text-2xl font-black text-slate-900 mt-3 leading-tight tracking-tight">{{ $item->name }}</h1>
                    </div>

                    <div class="space-y-2">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Deskripsi Unit</h4>
                        <p class="text-sm text-slate-600 leading-relaxed">
                            Unit {{ $item->name }} dalam condition prima, bersih, dan siap pakai untuk menunjang kebutuhan harian Anda. 
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
                    <form action="{{ route('items.checkout.store', $item->id) }}" method="POST" class="space-y-5">
                        @csrf 
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
                                    oninput="updateTotalHarga({{ is_numeric(str_replace('.', '', $item->price)) ? str_replace('.', '', $item->price) : $item->price }})">
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wide">{{ $item->rent_mode }}</span>
                            </div>
                        </div>

                        <div class="flex flex-col">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Kode Voucher (Opsional)</label>
                            <div class="flex gap-2 mt-2">
                                <input type="text" name="kode_voucher" placeholder="Contoh: DAYRENTPROMO" class="input-checkout rounded-xl py-3 px-4 text-sm font-light tracking-wider flex-1 focus:ring-2 focus:ring-blue-950/20">
                                <button type="button" onclick="alert('Voucher dimasukkan!')" class="bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs px-5 rounded-xl transition cursor-pointer">Gunakan</button>
                            </div>
                        </div>

                        <div class="pt-5 mt-6 border-t border-slate-100 space-y-3 bg-slate-50 p-4 rounded-2xl border border-slate-200">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Ringkasan Biaya</h3>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-slate-500 font-medium">Harga Dasar (1 {{ ucfirst($item->rent_mode) }})</span>
                                <span class="font-bold text-slate-800">Rp {{ is_numeric(str_replace('.', '', $item->price)) ? number_format(str_replace('.', '', $item->price), 0, ',', '.') : $item->price }}</span>
                            </div>
                            <div class="flex justify-between items-center pt-3 border-t border-slate-200">
                                <span class="text-sm font-bold text-slate-700">Total Pembayaran</span>
                                <span id="totalBayarText" class="text-xl font-black text-blue-900 tracking-tight">Rp {{ is_numeric(str_replace('.', '', $item->price)) ? number_format(str_replace('.', '', $item->price), 0, ',', '.') : $item->price }}</span>
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

    <script>
        function updateTotalHarga(pricePerUnit) {
            // 1. Tangkap angka durasi, jika kosong set default ke 1
            const duration = document.getElementById('rentalDuration').value || 1;
            
            // 2. Kalkulasi matematika murni total biaya
            const total = pricePerUnit * duration;
            
            // 3. Format totalan ke standar Rupiah ribuan (Contoh: Rp 450.000)
            const formattedTotal = new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 0
            }).format(total);

            // 4. Ubah isi teks ringkasan biaya secara instan di layar
            const totalDisplay = document.getElementById('totalBayarText');
            if(totalDisplay) {
                totalDisplay.innerText = 'Rp ' + formattedTotal;
            }
        }
    </script>
</body>
</html>