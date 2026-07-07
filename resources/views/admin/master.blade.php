<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - {{ $configs['app_name'] ?? 'Day-Rent' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        .custom-scroll::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
    </style>
</head>
<body class="bg-[#F8F9FC] text-slate-800 h-screen overflow-hidden flex p-4 sm:p-5 md:p-6 gap-6">

    <aside class="w-72 bg-white rounded-[2rem] border border-slate-200/60 shadow-sm flex flex-col justify-between p-5 overflow-hidden flex-shrink-0 h-full">
        <div class="space-y-7 flex flex-col flex-1">
            
            <div class="flex items-center gap-3 px-2 pt-1">
                <div class="w-10 h-10 bg-violet-600 rounded-2xl flex items-center justify-center font-black text-white text-xl shadow-lg shadow-violet-600/25 flex-shrink-0">
                    {{ substr($configs['app_name'] ?? 'D', 0, 1) }}
                </div>
                <div class="leading-tight text-left">
                    <span class="font-extrabold text-sm tracking-wider block text-slate-900 uppercase">{{ $configs['app_name'] ?? 'DAY-RENT' }}</span>
                    <span class="text-[10px] text-slate-400 font-bold tracking-widest uppercase">Workspace</span>
                </div>
            </div>

            <nav class="flex flex-col gap-1.5 flex-1">
    
                <a href="{{ route('admin.dashboard') }}" 
                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-xs uppercase tracking-wider transition duration-150 whitespace-nowrap {{ Route::is('admin.dashboard') ? 'bg-violet-50 text-violet-600 shadow-sm' : 'text-slate-500 hover:bg-slate-50' }}">
                    <svg class="w-4 h-4 stroke-current" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
                    </svg>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.categories.index') }}" 
                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-xs uppercase tracking-wider transition duration-150 whitespace-nowrap {{ Route::is('admin.categories.*') ? 'bg-violet-50 text-violet-600 shadow-sm' : 'text-slate-500 hover:bg-slate-50' }}">
                    <svg class="w-4 h-4 stroke-current" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Kategori Rental</span>
                </a>

                <a href="{{ route('admin.items.index') }}" 
                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-xs uppercase tracking-wider transition duration-150 whitespace-nowrap {{ Route::is('admin.items.*') ? 'bg-violet-50 text-violet-600 shadow-sm' : 'text-slate-500 hover:bg-slate-50' }}">
                    <svg class="w-4 h-4 stroke-current" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <span>Unit Barang</span>
                </a>

                <a href="{{ route('admin.cms') }}" 
                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-xs uppercase tracking-wider transition duration-150 whitespace-nowrap {{ Route::is('admin.cms*') ? 'bg-violet-50 text-violet-600 shadow-sm' : 'text-slate-500 hover:bg-slate-50' }}">
                    <svg class="w-4 h-4 stroke-current" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>Konfigurasi Web</span>
                </a>

                <a href="{{ route('admin.users.index') }}" 
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-xs uppercase tracking-wider transition duration-150 whitespace-nowrap {{ Route::is('admin.users.*') ? 'bg-violet-50 text-violet-600 shadow-sm' : 'text-slate-500 hover:bg-slate-50' }}">
                    <svg class="w-4 h-4 stroke-current" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span>Account Management</span>
                </a>

                <a href="{{ route('admin.promos.index') }}" 
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-xs uppercase tracking-wider transition duration-150 whitespace-nowrap {{ Route::is('admin.admin.promos.*') ? 'bg-violet-50 text-violet-600 shadow-sm' : 'text-slate-500 hover:bg-slate-50' }}">
                    <svg class="w-4 h-4 stroke-current" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                    </svg>
                    <span>Promo Slider</span>
                </a>
                
                <a href="{{ route('admin.stock.index') }}" 
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition {{ Request::routeIs('admin.stock.*') ? 'bg-blue-50 text-blue-600' : 'text-slate-500 hover:bg-slate-50' }}">
                    <span>📦</span>
                    <span>Manajemen Stok</span>
                </a>

                <a href="{{ route('admin.rentals.index') }}" 
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition {{ Request::routeIs('admin.rentals.*') ? 'bg-blue-50 text-blue-600' : 'text-slate-500 hover:bg-slate-50' }}">
                    <span>Data Penyewaan</span>
                </a>

            </nav>
        </div>

        <div class="pt-4 border-t border-slate-100">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-xs uppercase tracking-wider text-rose-600 hover:bg-rose-50 transition text-left cursor-pointer border-0 bg-transparent whitespace-nowrap">
                    <svg class="w-4 h-4 stroke-current" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span>Keluar Sistem</span>
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 flex flex-col gap-6 h-full min-w-0 overflow-hidden">
        
        <header class="w-full bg-white border border-slate-200/60 p-3 rounded-[1.5rem] shadow-sm flex items-center justify-between flex-shrink-0 z-30">
            <div class="flex items-center gap-1.5 pl-2">
                <div class="bg-violet-600 text-white text-xs font-extrabold px-4 py-2 rounded-xl shadow-md shadow-violet-600/10 tracking-wide">
                    {{ Route::is('admin.categories.*') ? 'Data Kategori' : 'Katalog Unit' }}
                </div>
            </div>
            
            <div class="flex items-center gap-3 pr-2">
                <div class="leading-tight text-right hidden md:block">
                    <p class="text-xs font-extrabold text-slate-900">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] text-slate-400 font-bold tracking-tight">{{ auth()->user()->email }}</p>
                </div>
                <img class="h-9 w-9 rounded-xl object-cover border border-violet-100 flex-shrink-0 shadow-sm" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=7c3aed&color=fff">
            </div>
        </header>

        <div class="flex-1 overflow-y-auto pr-1 custom-scroll pb-6">
            @yield('content')
        </div>
        
    </main>

</body>
</html>