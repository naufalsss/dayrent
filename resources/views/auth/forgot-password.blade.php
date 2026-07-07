<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi - {{ $configs['app_name'] ?? 'Day-Rent' }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite('resources/css/app.css')
</head>
<body class="bg-slate-950 text-white min-h-screen flex items-center justify-center p-4 relative overflow-x-hidden" style="font-family: 'Poppins', sans-serif;">

    <div class="absolute top-1/3 left-1/2 -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl pointer-events-none z-0"></div>

    <div class="w-full max-w-md backdrop-blur-xl bg-slate-900 border border-white/10 rounded-3xl p-6 sm:p-8 shadow-2xl relative z-10 space-y-6">
        
        <a href="/login" class="text-xs font-semibold text-slate-500 hover:text-white transition flex items-center gap-1 group">
            <span class="group-hover:-translate-x-0.5 transition duration-200">←</span> Kembali ke login
        </a>

        <div class="space-y-2">
            <h2 class="text-xl font-black text-white tracking-tight uppercase">Recovery Password</h2>
            <p class="text-xs text-slate-400 leading-relaxed font-medium">
                Masukkan alamat email terdaftar di bawah ini, kami akan mengirimkan tautan pemulihan kata sandi baru lewat email.
            </p>
        </div>

        @if (session('status'))
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl text-xs font-bold text-emerald-400">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf

            <div class="space-y-1.5">
                <label for="email" class="text-xs font-bold uppercase tracking-wider text-slate-400">Alamat Email Terdaftar</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full bg-slate-950 border {{ $errors->has('email') ? 'border-rose-500 focus:ring-rose-500/20' : 'border-white/10 focus:ring-blue-500/20' }} rounded-xl px-4 py-3.5 text-xs font-medium text-white placeholder-slate-600 focus:outline-none focus:ring-4 transition duration-200"
                       placeholder="nama@email.com">
                @if($errors->has('email'))
                    <p class="text-[11px] text-rose-500 font-bold mt-1">❌ {{ $errors->first('email') }}</p>
                @endif
            </div>

            <div class="pt-2">
                <button type="submit" 
                        class="w-full bg-blue-600 hover:bg-blue-500 text-white font-black text-xs py-4 rounded-xl shadow-lg shadow-blue-600/20 transition transform hover:-translate-y-0.5 uppercase tracking-widest cursor-pointer border-0">
                    Kirim Tautan Pemulihan
                </button>
            </div>
        </form>
    </div>

</body>
</html>