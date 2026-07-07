<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - {{ $configs['app_name'] ?? 'Day-Rent' }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite('resources/css/app.css')
</head>
<body class="bg-slate-950 text-white min-h-screen font-sans overflow-x-hidden" style="font-family: 'Poppins', sans-serif;">

    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-12">
        
        <div class="hidden lg:flex lg:col-span-7 relative items-center p-12 bg-cover bg-center overflow-hidden"
             style="background-image: linear-gradient(to right, rgba(15, 23, 42, 0.9) 0%, rgba(15, 23, 42, 0.4) 100%), url('{{ asset('images/default_hero_bg.jpg') }}');">
            
            <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="relative z-10 max-w-xl space-y-4">
                <div class="inline-flex items-center gap-3 bg-white/5 border border-white/10 px-4 py-2 rounded-2xl backdrop-blur-md">
                    <div class="w-6 h-6 bg-blue-500 rounded-md flex items-center justify-center font-black text-xs">
                        {{ substr($configs['app_name'] ?? 'D', 0, 1) }}
                    </div>
                    <span class="text-xs font-bold tracking-widest uppercase">{{ $configs['app_name'] ?? 'DAY-RENT' }} Ecosystem</span>
                </div>
                
                <h1 class="text-4xl lg:text-5xl font-black text-white leading-tight uppercase tracking-tight">
                    BERGABUNG & MULAI <br>
                    <span class="text-blue-500">BERTRANSAKSI.</span>
                </h1>
                <p class="text-slate-400 text-sm leading-relaxed font-medium">
                    Daftarkan akun kelompok Anda sekarang untuk menikmati akses penuh persewaan unit secara instan, pemantauan hitung mundur riil, dan kemudahan manajemen data profil.
                </p>
            </div>

            <div class="absolute bottom-8 left-12 z-10 text-xs text-slate-500 font-medium">
                &copy; 2026 {{ $configs['app_name'] ?? 'DAY-RENT' }} Universal. Kelompok Naufal & Zikmal.
            </div>
        </div>

        <div class="col-span-1 lg:col-span-5 flex flex-col justify-center items-center p-6 sm:p-12 bg-slate-900 border-l border-l-white/5 relative">
            
            <a href="/" class="absolute top-6 left-6 text-xs font-semibold text-slate-500 hover:text-white transition flex items-center gap-1 group">
                <span class="group-hover:-translate-x-0.5 transition duration-200">←</span> Kembali ke katalog
            </a>

            <div class="w-full max-w-md space-y-6">
                <div>
                    <h2 class="text-2xl font-black text-white tracking-tight">Buat Akun Baru</h2>
                    <p class="text-xs text-slate-400 mt-1.5 font-medium">Lengkapi data di bawah untuk mendaftarkan akun konsumen Anda.</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <div class="space-y-1.5">
                        <label for="name" class="text-xs font-bold uppercase tracking-wider text-slate-400">Nama Lengkap</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                               class="w-full bg-slate-950 border {{ $errors->has('name') ? 'border-rose-500 focus:ring-rose-500/20' : 'border-white/10 focus:ring-blue-500/20' }} rounded-xl px-4 py-3 text-xs font-medium text-white placeholder-slate-600 focus:outline-none focus:ring-4 transition duration-200"
                               placeholder="Nama lengkap Anda">
                        @if($errors->has('name'))
                            <p class="text-[11px] text-rose-500 font-bold mt-1">❌ {{ $errors->first('name') }}</p>
                        @endif
                    </div>

                    <div class="space-y-1.5">
                        <label for="email" class="text-xs font-bold uppercase tracking-wider text-slate-400">Alamat Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                               class="w-full bg-slate-950 border {{ $errors->has('email') ? 'border-rose-500 focus:ring-rose-500/20' : 'border-white/10 focus:ring-blue-500/20' }} rounded-xl px-4 py-3 text-xs font-medium text-white placeholder-slate-600 focus:outline-none focus:ring-4 transition duration-200"
                               placeholder="nama@email.com">
                        @if($errors->has('email'))
                            <p class="text-[11px] text-rose-500 font-bold mt-1">❌ {{ $errors->first('email') }}</p>
                        @endif
                    </div>

                    <div class="space-y-1.5">
                        <label for="password" class="text-xs font-bold uppercase tracking-wider text-slate-400">Kata Sandi</label>
                        <div class="relative flex items-center">
                            <input id="password" type="password" name="password" required autocomplete="new-password"
                                   class="w-full bg-slate-950 border {{ $errors->has('password') ? 'border-rose-500 focus:ring-rose-500/20' : 'border-white/10 focus:ring-blue-500/20' }} rounded-xl pl-4 pr-12 py-3 text-xs font-medium text-white placeholder-slate-600 focus:outline-none focus:ring-4 transition duration-200"
                                   placeholder="••••••••">
                            <button type="button" onclick="togglePasswordVisibility('password', 'eyeIconReg')" class="absolute right-4 bg-transparent border-0 cursor-pointer text-slate-500 hover:text-slate-300 transition p-0 flex items-center">
                                <span id="eyeIconReg" class="text-base">👁️</span>
                            </button>
                        </div>
                        @if($errors->has('password'))
                            <p class="text-[11px] text-rose-500 font-bold mt-1">❌ {{ $errors->first('password') }}</p>
                        @endif
                    </div>

                    <div class="space-y-1.5">
                        <label for="password_confirmation" class="text-xs font-bold uppercase tracking-wider text-slate-400">Konfirmasi Kata Sandi</label>
                        <div class="relative flex items-center">
                            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                                   class="w-full bg-slate-950 border border-white/10 focus:ring-blue-500/20 rounded-xl pl-4 pr-12 py-3 text-xs font-medium text-white placeholder-slate-600 focus:outline-none focus:ring-4 transition duration-200"
                                   placeholder="••••••••">
                            <button type="button" onclick="togglePasswordVisibility('password_confirmation', 'eyeIconConfirm')" class="absolute right-4 bg-transparent border-0 cursor-pointer text-slate-500 hover:text-slate-300 transition p-0 flex items-center">
                                <span id="eyeIconConfirm" class="text-base">👁️</span>
                            </button>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" 
                                class="w-full bg-blue-600 hover:bg-blue-500 text-white font-black text-xs py-3.5 rounded-xl shadow-lg shadow-blue-600/20 transition transform hover:-translate-y-0.5 uppercase tracking-widest cursor-pointer border-0">
                            Daftar Akun Baru
                        </button>
                    </div>
                </form>

                <div class="text-center pt-4 border-t border-white/5">
                    <p class="text-xs text-slate-500 font-medium">
                        Sudah memiliki akun terdaftar? 
                        <a href="/login" class="text-blue-400 hover:text-blue-300 font-bold ml-0.5 transition">Masuk Sini →</a>
                    </p>
                </div>

            </div>
        </div>

    </div>

    <script>
        window.togglePasswordVisibility = function(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const eyeIcon = document.getElementById(iconId);
            if (passwordInput && eyeIcon) {
                if (passwordInput.type === "password") {
                    passwordInput.type = "text";
                    eyeIcon.innerText = "🔒";
                } else {
                    passwordInput.type = "password";
                    eyeIcon.innerText = "👁️";
                }
            }
        }
    </script>
</body>
</html>