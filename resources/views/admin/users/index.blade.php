@extends('admin.master')

@section('content')
    <style>
        .user-container .premium-white-card {
            border-radius: 24px !important;
            background-color: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02) !important;
            width: 100% !important;
        }
        
        .user-container .input-premium-light {
            background-color: #f8fafc !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 12px !important;
            padding: 11px 14px !important;
            font-size: 13px !important;
            color: #1e293b !important;
            margin-top: 8px !important;
            width: 100% !important;
            transition: all 0.2s ease;
            height: 46px !important;
        }
        
        .user-container .input-premium-light:focus {
            border-color: #7c3aed !important;
            background-color: #ffffff !important;
            outline: none !important;
            box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.08) !important;
        }
    </style>

    @if(session('success'))
        <div class="p-4 mb-4 text-sm font-bold text-emerald-700 bg-emerald-50 rounded-2xl border border-emerald-200 flex items-center gap-2 shadow-sm">
            <span>✅</span> {{ session('success') }}
        </div>
    @endif

    <div class="user-container flex flex-col gap-6 w-full">
        
        <div class="premium-white-card p-6">
            <h4 class="font-extrabold text-base text-slate-900 pb-3 border-b border-slate-100 mb-5 flex items-center gap-2">
                <span>👤</span> Daftarkan Akun Baru
            </h4>
            
            <form method="POST" action="{{ route('admin.users.store') }}" class="flex flex-col md:flex-row items-end gap-5">
                @csrf
                <div class="flex flex-col flex-1 w-full">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-wide">Nama Lengkap</label>
                    <input type="text" name="name" placeholder="Misal: Ahmad Zaki" class="input-premium-light" required>
                </div>

                <div class="flex flex-col flex-1 w-full">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-wide">Alamat Email</label>
                    <input type="email" name="email" placeholder="nama@domain.com" class="input-premium-light" required>
                </div>

                <div class="flex flex-col flex-1 w-full">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-wide">Password Awal</label>
                    <input type="password" name="password" placeholder="Minimal 8 karakter" class="input-premium-light" required>
                </div>

                <button type="submit" class="w-full md:w-auto bg-violet-600 hover:bg-violet-500 text-white font-bold text-xs px-8 py-3.5 rounded-xl shadow-md shadow-violet-600/25 transition duration-200 uppercase tracking-wider cursor-pointer border-0 h-[48px] whitespace-nowrap">
                    Tambah Akun 💾
                </button>
            </form>
        </div>

        <div class="premium-white-card p-6 overflow-hidden">
            <h4 class="font-extrabold text-base text-slate-900 pb-3 border-b border-slate-100 mb-5">
                📋 Pengguna Sistem Terdaftar
            </h4>

            <div class="overflow-x-auto rounded-2xl overflow-hidden border border-slate-100 bg-slate-50/50">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-[11px] font-bold uppercase tracking-wider text-slate-400 bg-slate-50 border-b border-slate-200/60">
                            <th class="py-4 px-6">Nama Pengguna</th>
                            <th class="py-4 px-6">Email</th>
                            <th class="py-4 px-6">Tanggal Terdaftar</th>
                            <th class="py-4 px-6 text-center w-48">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs font-semibold text-slate-600 divide-y divide-slate-100">
                        @forelse($users as $user)
                            <tr class="hover:bg-white transition duration-150">
                                <td class="py-4 px-6 font-bold text-slate-900">
                                    <div class="flex items-center gap-3">
                                        <img class="h-7 w-7 rounded-lg object-cover flex-shrink-0" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=EBF5FF&color=007AFF">
                                        <span class="align-middle inline-block leading-none">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-slate-500 font-mono">{{ $user->email }}</td>
                                <td class="py-4 px-6 text-slate-400">{{ $user->created_at->format('d M Y - H:i') }} WIB</td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center justify-center gap-2">
                                        <button type="button" 
                                                onclick="openPasswordModal('{{ $user->id }}', '{{ $user->name }}')" 
                                                class="bg-violet-50 hover:bg-violet-100 text-violet-600 px-3 py-1.5 rounded-xl font-bold transition cursor-pointer text-[11px] border-0">
                                            Ganti Password
                                        </button>

                                        <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" onsubmit="return confirm('Hapus permanen akun ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-rose-50 hover:bg-rose-100 text-rose-600 px-3 py-1.5 rounded-xl font-bold transition cursor-pointer text-[11px] border-0">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-24 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center space-y-4 px-4">
                                        <span class="text-6xl opacity-75">👥</span>
                                        <p class="font-extrabold text-slate-700 text-sm tracking-tight">Belum ada akun lain yang terdaftar</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <div id="passwordModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-xl w-full max-w-md overflow-hidden p-6 transform scale-95 transition-transform duration-200">
            
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-5">
                <h4 class="font-extrabold text-base text-slate-900 flex items-center gap-2">
                    <span>🔑</span> Ganti Password Akun
                </h4>
                <button type="button" onclick="closePasswordModal()" class="text-slate-400 hover:text-slate-600 border-0 bg-transparent text-lg cursor-pointer font-bold">✕</button>
            </div>

            <form id="passwordForm" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="flex flex-col">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-wide">Nama Pengguna</label>
                    <input type="text" id="modal_user_name" class="input-premium-light opacity-60 bg-slate-100" readonly>
                </div>

                <div class="flex flex-col">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-wide">Masukkan Password Baru</label>
                    <input type="password" name="password" placeholder="Minimal 8 karakter baru" class="input-premium-light" required minlength="8">
                </div>

                <div class="pt-3 border-t border-slate-100 flex justify-end gap-3">
                    <button type="button" onclick="closePasswordModal()" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs uppercase tracking-wider cursor-pointer border-0">Batal</button>
                    <button type="submit" class="bg-violet-600 hover:bg-violet-500 text-white font-bold text-xs px-6 py-2.5 rounded-xl shadow-md shadow-violet-600/25 transition duration-200 uppercase tracking-wider cursor-pointer border-0">Update Password 💾</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function openPasswordModal(id, name) {
        const modal = document.getElementById('passwordModal');
        const form = document.getElementById('passwordForm');
        
        // FIX SAKTI: Kita kunci ke URL absolut 'admin/users' agar klop dengan list terminal lu!
        form.action = "{{ url('admin/users') }}/" + id;
        
        document.getElementById('modal_user_name').value = name;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closePasswordModal() {
        const modal = document.getElementById('passwordModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    </script>
@endsection