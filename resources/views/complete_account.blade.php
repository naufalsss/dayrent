<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lengkapi Akun - {{ $configs['app_name'] ?? 'Day-Rent' }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite('resources/css/app.css')
</head>
<body class="bg-slate-950 text-white min-h-screen flex items-center justify-center p-4" style="font-family: 'Poppins', sans-serif;">

    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-xl bg-slate-900 border border-white/10 rounded-3xl p-6 sm:p-8 shadow-2xl space-y-6 relative z-10">
        
        <div class="flex items-center justify-between pb-4 border-b border-white/5">
            <div>
                <h2 class="text-xl font-black uppercase tracking-tight text-white">Lengkapi Profil</h2>
                <p class="text-[11px] text-slate-400 mt-1 font-medium">Lengkapi atau perbarui informasi data diri Anda secara mandiri.</p>
            </div>
            <a href="/" class="text-xs bg-white/5 hover:bg-white/10 border border-white/10 px-3 py-1.5 rounded-xl transition duration-200">
                Kembali
            </a>
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl text-xs font-bold text-emerald-400">
                🎉 {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 bg-rose-500/10 border border-rose-500/20 rounded-2xl text-xs font-bold text-rose-400 space-y-1">
                @foreach($errors->all() as $error)
                    <p>❌ {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="/profile/complete" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-400">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required
                           class="w-full bg-slate-950 border border-white/10 focus:ring-blue-500/20 rounded-xl px-4 py-3 text-xs font-medium text-white focus:outline-none focus:ring-4 transition duration-200">
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-400">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required
                           class="w-full bg-slate-950 border border-white/10 focus:ring-blue-500/20 rounded-xl px-4 py-3 text-xs font-medium text-white focus:outline-none focus:ring-4 transition duration-200">
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="text-xs font-bold uppercase tracking-wider text-slate-400">Nomor WhatsApp Aktif</label>
                <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number', auth()->user()->whatsapp_number) }}" required placeholder="Contoh: 081234567xxx"
                       class="w-full bg-slate-950 border border-white/10 focus:ring-blue-500/20 rounded-xl px-4 py-3 text-xs font-medium text-white focus:outline-none focus:ring-4 transition duration-200">
            </div>

            <div class="border-t border-white/5 pt-4 space-y-4">
                <div class="bg-blue-500/5 border border-blue-500/10 p-3 rounded-2xl">
                    <p class="text-[10px] text-blue-400 leading-relaxed font-semibold">Kosongkan kolom Kata Sandi Baru di bawah ini jika Anda tidak ingin mengubah password akun Anda saat ini.</p>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-400">Kata Sandi Baru (Opsional)</label>
                    <input type="password" name="new_password" placeholder="Masukkan password baru jika ingin diubah"
                           class="w-full bg-slate-950 border border-white/10 focus:ring-blue-500/20 rounded-xl px-4 py-3 text-xs font-medium text-white focus:outline-none focus:ring-4 transition duration-200">
                </div>

                <div class="space-y-1.5 pt-2 border-t border-white/5">
                    <label class="text-xs font-bold uppercase tracking-wider text-amber-400">Konfirmasi Kata Sandi Saat Ini (Wajib)</label>
                    <input type="password" name="current_password" required placeholder="Verifikasi sandi aktif Anda saat ini"
                           class="w-full bg-slate-950 border border-amber-500/30 focus:ring-amber-500/20 rounded-xl px-4 py-3 text-xs font-medium text-white focus:outline-none focus:ring-4 transition duration-200">
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" 
                        class="w-full bg-blue-600 hover:bg-blue-500 text-white font-black text-xs py-3.5 rounded-xl shadow-lg shadow-blue-600/20 transition transform hover:-translate-y-0.5 uppercase tracking-widest cursor-pointer border-0">
                    Simpan Perubahan Data
                </button>
            </div>
        </form>
    </div>

</body>
</html>