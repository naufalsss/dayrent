<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- FIX RADIKAL: Langsung query ke tabel cms_configs secara global -->
    @php
        $dbConfigLogo = \Illuminate\Support\Facades\DB::table('cms_configs')->where('key', 'app_logo')->value('value');
        $dbConfigName = \Illuminate\Support\Facades\DB::table('cms_configs')->where('key', 'app_name')->value('value');

        // Gunakan hasil query database, jika kosong baru pakai fallback default
        $appLogo = !empty($dbConfigLogo) ? $dbConfigLogo : null;
        $appName = !empty($dbConfigName) ? $dbConfigName : 'Day-Rent';
    @endphp

    <title>Admin Dashboard - {{ $appName }}</title>
    
    <!-- TAUTAN FAVICON DINAMIS PADA TAB BROWSER -->
    @if(!empty($appLogo))
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $appLogo) }}?v={{ time() }}">
        <link rel="shortcut icon" href="{{ asset('storage/' . $appLogo) }}?v={{ time() }}">
    @else
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8F9FC; }
        
        .custom-scroll::-webkit-scrollbar {
            width: 5px;
        }
        .custom-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scroll::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }
    </style>
</head>
<body class="bg-[#F8F9FC] text-slate-800 h-screen overflow-hidden flex">

    <aside class="w-64 bg-slate-50 border-r border-slate-200/80 shadow-sm flex flex-col justify-between p-5 flex-shrink-0 h-full">
        <div class="space-y-6 flex flex-col flex-1">
            
            <!-- LOGO SIDEBAR WORKSPACE (CLEAN TANPA BACKGROUND BIRU PEKAT) -->
            <div class="flex items-center gap-3 px-2 pt-2 pb-4 border-b border-slate-100">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center font-black text-slate-800 text-base flex-shrink-0 overflow-hidden">
                    @if(!empty($appLogo))
                        <img src="{{ asset('storage/' . $appLogo) }}" class="w-full h-full object-contain">
                    @else
                        <div class="w-full h-full bg-blue-600 rounded-xl flex items-center justify-center font-black text-white text-base shadow-md shadow-blue-600/20">
                            {{ substr($appName, 0, 1) }}
                        </div>
                    @endif
                </div>
                <div class="leading-tight text-left">
                    <span class="font-extrabold text-sm tracking-tight block text-slate-900 uppercase">{{ $appName }}</span>
                    <span class="text-[10px] text-slate-400 font-bold tracking-widest uppercase">Workspace</span>
                </div>
            </div>

            <nav class="flex flex-col gap-1 flex-1 overflow-y-auto custom-scroll pr-1">
                <a href="{{ route('admin.dashboard') }}" 
                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-xs uppercase tracking-wider transition duration-150 whitespace-nowrap {{ Route::is('admin.dashboard') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/10' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-4 h-4 stroke-current" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
                    </svg>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.categories.index') }}" 
                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-xs uppercase tracking-wider transition duration-150 whitespace-nowrap {{ Route::is('admin.categories.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/10' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-4 h-4 stroke-current" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Kategori Rental</span>
                </a>

                <a href="{{ route('admin.items.index') }}" 
                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-xs uppercase tracking-wider transition duration-150 whitespace-nowrap {{ Route::is('admin.items.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/10' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-4 h-4 stroke-current" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <span>Unit Barang</span>
                </a>

                <a href="{{ route('admin.stock.index') }}" 
                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-xs uppercase tracking-wider transition duration-150 whitespace-nowrap {{ Route::is('admin.stock.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/10' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-4 h-4 stroke-current" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m-8-10l8 4m-8-4v10l8 4m0-10V11" />
                    </svg>
                    <span>Manajemen Stok</span>
                </a>

                <a href="{{ route('admin.rentals.index') }}" 
                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-xs uppercase tracking-wider transition duration-150 whitespace-nowrap {{ Route::is('admin.rentals.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/10' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-4 h-4 stroke-current" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    <span>Data Penyewaan</span>
                </a>

                <a href="{{ route('admin.promos.index') }}" 
                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-xs uppercase tracking-wider transition duration-150 whitespace-nowrap {{ Route::is('admin.promos.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/10' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-4 h-4 stroke-current" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                    </svg>
                    <span>Promo Slider</span>
                </a>

                <a href="{{ route('admin.promo-codes.index') }}" 
                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-xs uppercase tracking-wider transition duration-150 whitespace-nowrap {{ Route::is('admin.promo-codes.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/10' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-4 h-4 stroke-current" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                    </svg>
                    <span>Kelola Voucher</span>
                </a>

                <a href="{{ route('admin.users.index') }}" 
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-xs uppercase tracking-wider transition duration-150 whitespace-nowrap {{ Route::is('admin.users.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/10' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-4 h-4 min-w-[16px] min-h-[16px] flex-shrink-0 stroke-current" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>Account Management</span>
                </a>

                <a href="{{ route('admin.cms') }}" 
                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-xs uppercase tracking-wider transition duration-150 whitespace-nowrap {{ Route::is('admin.cms*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/10' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-4 h-4 stroke-current" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>Konfigurasi Web</span>
                </a>

                <a href="{{ route('admin.merchant-approval.index') }}" 
                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-xs uppercase tracking-wider transition duration-150 whitespace-nowrap {{ Route::is('admin.merchant-approval.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/10' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-4 h-4 stroke-current" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <span>Persetujuan Merchant</span>
                </a>
            </nav>
        </div>

        <div class="pt-4 border-t border-slate-100">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider text-rose-600 hover:bg-rose-50 transition text-left cursor-pointer border-0 bg-transparent whitespace-nowrap">
                    <svg class="w-4 h-4 stroke-current" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span>Keluar Sistem</span>
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 flex flex-col h-full min-w-0 overflow-hidden">
        <header class="w-full bg-slate-50 border-b border-slate-200/80 py-4 px-8 flex items-center justify-between flex-shrink-0 z-30">
            <div class="flex items-center">
                <h1 class="text-sm font-extrabold text-slate-800 tracking-tight uppercase">
                    @if(Route::is('admin.dashboard')) Dashboard Overview @elseif(Route::is('admin.categories.*')) Kategori Rental @elseif(Route::is('admin.items.*')) Unit Katalog @elseif(Route::is('admin.promo-codes.*')) Manajemen Kode Promo @elseif(Route::is('admin.merchant-approval.*')) Persetujuan Akun Merchant @else Workspace Admin @endif
                </h1>
            </div>
            
            <div class="flex items-center gap-3">
                <div class="leading-tight text-right hidden md:block">
                    <p class="text-xs font-extrabold text-slate-900">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] text-slate-400 font-bold tracking-tight">{{ auth()->user()->email }}</p>
                </div>
                <img class="h-8 w-8 rounded-xl object-cover border border-slate-200 flex-shrink-0 shadow-sm" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=ffffff&color=1e293b&bold=true">
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-6 md:p-8 custom-scroll">
            @yield('content')
        </div>
    </main>

</body>
</html>