@extends('admin.master')

@section('content')
    @if(session('success'))
        <div class="p-4 mb-6 text-xs font-bold text-emerald-600 bg-emerald-50 rounded-2xl border border-emerald-200 flex items-center gap-2 max-w-5xl">
            <span>✨</span> {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col gap-6 w-full max-w-5xl">
        
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <h4 class="font-extrabold text-xs text-slate-800 pb-3 border-b border-slate-100 mb-5 uppercase tracking-wider">
                Daftarkan Akun Baru
            </h4>
            
            <form method="POST" action="{{ route('admin.users.store') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 items-end gap-4">
                @csrf
                <div class="flex flex-col w-full">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Nama Lengkap</label>
                    <input type="text" name="name" placeholder="Misal: Ahmad Zaki" 
                    class="w-full mt-2 bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:border-blue-600 focus:bg-white outline-none transition h-[42px]" required>
                </div>

                <div class="flex flex-col w-full">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Alamat Email</label>
                    <input type="email" name="email" placeholder="nama@domain.com" 
                    class="w-full mt-2 bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:border-blue-600 focus:bg-white outline-none transition h-[42px]" required>
                </div>

                <div class="flex flex-col w-full">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Password Awal</label>
                    <input type="password" name="password" placeholder="Minimal 8 karakter" 
                    class="w-full mt-2 bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:border-blue-600 focus:bg-white outline-none transition h-[42px]" required>
                </div>

                <div class="flex flex-col w-full">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Role Hak Akses</label>
                    <select name="role" class="w-full mt-2 bg-slate-50/50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:border-blue-600 focus:bg-white outline-none transition h-[42px] cursor-pointer" required>
                        <option value="user">User</option>
                        <option value="merchant">Merchant</option> <!-- FIX TAMBAHAN: Opsi Role Merchant -->
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <div class="lg:col-span-4 flex justify-end mt-2">
                    <button type="submit" class="w-full md:w-auto bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs px-6 py-2.5 rounded-xl shadow-sm transition duration-150 uppercase tracking-wider cursor-pointer border-0 h-[42px] whitespace-nowrap">
                        Tambah Akun
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm overflow-hidden">
            <h4 class="font-extrabold text-xs text-slate-800 pb-3 border-b border-slate-100 mb-5 uppercase tracking-wider">
                Pengguna Sistem Terdaftar
            </h4>

            <div class="overflow-x-auto rounded-xl border border-slate-200">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-[10px] font-bold uppercase tracking-widest text-slate-400 bg-slate-50 border-b border-slate-200">
                            <th class="py-3.5 px-5">Nama Pengguna</th>
                            <th class="py-3.5 px-5">Email</th>
                            <th class="py-3.5 px-5">Role</th>
                            <th class="py-3.5 px-5">Tanggal Terdaftar</th>
                            <th class="py-3.5 px-5 text-center w-48">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs font-medium text-slate-600 divide-y divide-slate-100">
                        @forelse($users as $user)
                            <tr class="hover:bg-slate-50/40 transition duration-100">
                                <td class="py-3.5 px-5 font-bold text-slate-800">
                                    <div class="flex items-center gap-3">
                                        <img class="h-7 w-7 rounded-lg object-cover flex-shrink-0" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=F1F5F9&color=0F172A&bold=true">
                                        <span class="align-middle inline-block leading-none">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-5 text-slate-500 font-mono text-[11px] tracking-tight">{{ $user->email }}</td>
                                <td class="py-3.5 px-5">
                                    <!-- FIX BADGE STYLE: Pewarnaan badge dinamis untuk role merchant -->
                                    @if($user->role === 'admin')
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase bg-blue-50 text-blue-600 border border-blue-100">admin</span>
                                    @elseif($user->role === 'merchant')
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase bg-emerald-50 text-emerald-600 border border-emerald-100">merchant</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase bg-slate-100 text-slate-600">user</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-5 text-slate-400">{{ $user->created_at->format('d M Y - H:i') }} WIB</td>
                                <td class="py-3.5 px-5">
                                    <div class="flex items-center justify-center gap-2">
                                        @if($user->role !== 'admin' || $user->id === auth()->id())
                                            <button type="button" 
                                                    onclick="openEditModal('{{ $user->id }}', '{{ $user->name }}', '{{ $user->email }}', '{{ $user->role }}')" 
                                            class="bg-slate-100 hover:bg-slate-200 border border-slate-200 text-slate-700 px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider transition cursor-pointer">
                                                Edit Akun
                                            </button>
                                        @else
                                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Terproteksi</span>
                                        @endif

                                        @if($user->id !== auth()->id())
                                            <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" onsubmit="return confirm('Hapus permanen akun ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-rose-50 hover:bg-rose-100 border border-rose-200/40 text-rose-600 px-3 py-1.5 rounded-lg font-bold transition cursor-pointer text-[10px] uppercase tracking-wider border-0">
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-16 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <span class="text-3xl opacity-50">👥</span>
                                        <p class="font-extrabold text-slate-700 text-xs tracking-tight uppercase">Belum ada akun lain yang terdaftar</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT DATA AKUN -->
    <div id="editModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-xl w-full max-w-md overflow-hidden p-6 transform scale-95 transition-transform duration-200">
            
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-5">
                <h4 class="font-extrabold text-xs text-slate-800 uppercase tracking-wider">
                    Edit Data Akun Pengguna
                </h4>
                <button type="button" onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600 border-0 bg-transparent text-sm cursor-pointer font-bold">✕</button>
            </div>

            <form id="editForm" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="flex flex-col" id="container_name">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Nama Pengguna</label>
                    <input type="text" name="name" id="modal_user_name" class="w-full mt-2 bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-xs text-slate-800 focus:border-blue-600 focus:bg-white outline-none transition h-[42px]">
                </div>

                <div class="flex flex-col">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Alamat Email</label>
                    <input type="email" name="email" id="modal_user_email" class="w-full mt-2 bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-xs text-slate-800 focus:border-blue-600 focus:bg-white outline-none transition h-[42px]" required>
                </div>

                <!-- FIX INPUT MODAL: Menambahkan opsi ubah hak akses / role akun -->
                <div class="flex flex-col" id="container_role">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Ubah Role Hak Akses</label>
                    <select name="role" id="modal_user_role" class="w-full mt-2 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:border-blue-600 focus:bg-white outline-none transition h-[42px] cursor-pointer" required>
                        <option value="user">User</option>
                        <option value="merchant">Merchant</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <div class="flex flex-col">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Ganti Password <span class="text-[10px] text-slate-400 lowercase">(Kosongkan jika tidak diubah)</span></label>
                    <input type="password" name="password" placeholder="Masukkan password baru" 
                    class="w-full mt-2 bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-2 text-xs text-slate-800 placeholder-slate-400 focus:border-blue-600 focus:bg-white outline-none transition h-[42px]" minlength="8">
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-end gap-2.5">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs uppercase tracking-wider cursor-pointer border-0">Batal</button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs px-5 py-2 rounded-xl shadow-sm transition border-0 cursor-pointer uppercase tracking-wider">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function openEditModal(id, name, email, role) {
        const modal = document.getElementById('editModal');
        const form = document.getElementById('editForm');
        
        form.action = "{{ url('admin/users') }}/" + id;
        
        const inputName = document.getElementById('modal_user_name');
        const inputEmail = document.getElementById('modal_user_email');
        const inputRole = document.getElementById('modal_user_role');
        
        inputName.value = name;
        inputEmail.value = email;
        inputRole.value = role;

        // KOREKSI: Jika akun bertipe admin utama, proteksi data sensitifnya
        if(role === 'admin') {
            inputName.readOnly = true;
            inputName.classList.add('bg-slate-100', 'text-slate-400', 'select-none');
            inputName.classList.remove('bg-slate-50', 'text-slate-800');
            
            // Sembunyikan pilihan role jika akun tersebut admin biar tidak sengaja ter-downgrade
            document.getElementById('container_role').classList.add('hidden');
        } else {
            inputName.readOnly = false;
            inputName.classList.remove('bg-slate-100', 'text-slate-400', 'select-none');
            inputName.classList.add('bg-slate-50', 'text-slate-800');
            document.getElementById('container_role').classList.remove('hidden');
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeEditModal() {
        const modal = document.getElementById('editModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    </script>
@endsection