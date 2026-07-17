<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    @php
        $dbConfigLogo = \Illuminate\Support\Facades\DB::table('cms_configs')->where('key', 'app_logo')->value('value');
        $appName = "Day-Rent";
    @endphp
    
    <title>Merchant Panel - {{ $appName }}</title>
    
    @if(!empty($dbConfigLogo))
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $dbConfigLogo) }}?v={{ time() }}">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8F9FC; }
        .custom-scroll::-webkit-scrollbar { width: 5px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    </style>
</head>
<body class="bg-[#F8F9FC] text-slate-800 h-screen overflow-hidden flex">

    <aside class="w-64 bg-slate-50 border-r border-slate-200/80 shadow-sm flex flex-col justify-between p-5 flex-shrink-0 h-full">
        <div class="space-y-6 flex flex-col flex-1">
            
            <div class="flex items-center gap-3 px-2 pt-2 pb-4 border-b border-slate-100">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center font-black text-slate-800 text-base flex-shrink-0 overflow-hidden">
                    @if(!empty($dbConfigLogo))
                        <img src="{{ asset('storage/' . $dbConfigLogo) }}" class="w-full h-full object-contain">
                    @else
                        <div class="w-full h-full bg-emerald-600 rounded-xl flex items-center justify-center font-black text-white text-base">D</div>
                    @endif
                </div>
                <div class="leading-tight text-left">
                    <span class="font-extrabold text-sm tracking-tight block text-slate-900 uppercase">{{ $appName }}</span>
                    <span class="text-[10px] text-emerald-600 font-bold tracking-widest uppercase">Merchant Panel</span>
                </div>
            </div>

            <nav class="flex flex-col gap-1 flex-1 overflow-y-auto custom-scroll pr-1">
                <a href="{{ route('merchant.dashboard') }}" 
                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-xs uppercase tracking-wider transition duration-150 {{ Route::is('merchant.dashboard') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/10' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-4 h-4 stroke-current" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" /></svg>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('merchant.items.index') }}" 
                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-xs uppercase tracking-wider transition duration-150 {{ Route::is('merchant.items.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/10' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-4 h-4 stroke-current" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                    <span>Unit Barang</span>
                </a>

                <a href="{{ route('merchant.stock.index') }}" 
                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-xs uppercase tracking-wider transition duration-150 {{ Route::is('merchant.stock.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/10' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-4 h-4 stroke-current" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m-8-10l8 4m-8-4v10l8 4m0-10V11" /></svg>
                    <span>Manajemen Stok</span>
                </a>

                <a href="{{ route('merchant.rentals.index') }}" 
                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-xs uppercase tracking-wider transition duration-150 {{ Route::is('merchant.rentals.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/10' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-4 h-4 stroke-current" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                    <span>Data Penyewaan</span>
                </a>
            </nav>
        </div>

        <div class="pt-4 border-t border-slate-100">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider text-rose-600 hover:bg-rose-50 transition text-left cursor-pointer border-0 bg-transparent">
                    <svg class="w-4 h-4 stroke-current" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                    <span>Keluar Sistem</span>
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 flex flex-col h-full min-w-0 overflow-hidden">
        <header class="w-full bg-slate-50 border-b border-slate-200/80 py-4 px-8 flex items-center justify-between flex-shrink-0 z-30">
            <h1 class="text-sm font-extrabold text-slate-800 uppercase tracking-tight">
                @if(Route::is('merchant.dashboard')) Dashboard Overview @elseif(Route::is('merchant.items.*')) Unit Katalog @elseif(Route::is('merchant.stock.*')) Manajemen Stok @else Workspace Merchant @endif
            </h1>
            <div class="flex items-center gap-3">
                <div class="leading-tight text-right hidden md:block">
                    <p class="text-xs font-extrabold text-slate-900">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] text-slate-400 font-bold tracking-tight uppercase">Merchant</p>
                </div>
                <img class="h-8 w-8 rounded-xl object-cover border border-slate-200 shadow-sm" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=10b981&color=ffffff&bold=true">
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-6 md:p-8 custom-scroll">
            @yield('content')
        </div>
    </main>
</body>
</html>