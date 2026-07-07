@extends('admin.master')

@section('content')
    <style>
        .item-container .premium-white-card {
            border-radius: 24px !important;
            background-color: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02) !important;
            width: 100% !important;
        }
        
        .item-container .input-premium-light {
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
        
        .item-container .input-premium-light:focus {
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

    <div class="item-container flex flex-col gap-6 w-full">
        
        <div class="premium-white-card p-6">
            <h4 class="font-extrabold text-base text-slate-900 pb-3 border-b border-slate-100 mb-5 flex items-center gap-2">
                <span>➕</span> Tambah Unit Sewa Baru
            </h4>
            
            <form method="POST" action="{{ route('admin.items.store') }}" enctype="multipart/form-data" class="space-y-5">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                    <div class="flex flex-col">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-wide">Pilih Kategori</label>
                        <select name="category_id" class="input-premium-light cursor-pointer" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex flex-col">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-wide">Nama Unit Barang</label>
                        <input type="text" name="name" placeholder="Misal: Honda Civic, Sony A7S" class="input-premium-light" required>
                    </div>
                    
                    <div class="flex flex-col">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-wide">Harga Sewa (Rp)</label>
                        <input type="text" name="price" placeholder="Misal: 450.000" class="input-premium-light" required>
                    </div>

                    <div class="flex flex-col">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-wide">Rentang Waktu Sewa</label>
                        <select name="rent_mode" class="input-premium-light cursor-pointer" required>
                            <option value="hari">Per Hari</option>
                            <option value="bulan">Per Bulan</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 items-start">
                    <div class="flex flex-col">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-wide">Fitur Utama</label>
                        <input type="text" name="features" placeholder="Misal: Turbo, Manual, 4 Seats" class="input-premium-light" required>
                        <span class="text-[10px] text-slate-400 font-bold leading-tight mt-2 block">*Gunakan tanda koma ( , ) sebagai pembatas fitur</span>
                    </div>

                    <div class="flex flex-col">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-wide">Upload Foto Unit</label>
                        <label class="group relative flex flex-row items-center justify-between w-full h-[46px] border border-dashed border-slate-200 rounded-xl bg-slate-50/50 hover:bg-violet-50/30 hover:border-violet-400 transition duration-200 cursor-pointer overflow-hidden px-4 mt-2">
                            <div class="flex items-center gap-2" id="uploadPrompt">
                                <span class="text-base group-hover:animate-bounce">📸</span>
                                <p class="text-xs font-bold text-slate-600">Pilih berkas foto unit</p>
                            </div>
                            <div class="hidden items-center gap-2" id="filePreview">
                                <span class="text-base">🖼️</span>
                                <p class="text-xs font-bold text-violet-600 truncate max-w-[200px]" id="fileNameText"></p>
                            </div>
                            <span class="text-[10px] text-emerald-500 font-bold bg-emerald-50 px-2 py-1 rounded-full hidden" id="fileBadge">Terpilih</span>
                            <input type="file" name="image" id="imageInput" accept="image/*" class="hidden" required onchange="previewFileNama()">
                        </label>
                    </div>
                </div>

                <div class="pt-2 flex justify-end">
                    <button type="submit" class="w-full md:w-auto bg-violet-600 hover:bg-violet-500 text-white font-bold text-xs px-10 py-3.5 rounded-xl shadow-md shadow-violet-600/25 transition duration-200 uppercase tracking-wider cursor-pointer border-0 h-[48px]">
                        Simpan Unit Sewa 💾
                    </button>
                </div>
            </form>
        </div>

        <div class="premium-white-card p-6 overflow-hidden">
            <h4 class="font-extrabold text-base text-slate-900 pb-3 border-b border-slate-100 mb-5">
                📋 Unit Barang yang Tersedia
            </h4>

            <div class="overflow-x-auto rounded-2xl overflow-hidden border border-slate-100 bg-slate-50/50">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-[11px] font-bold uppercase tracking-wider text-slate-400 bg-slate-50 border-b border-slate-200/60">
                            <th class="py-4 px-5 w-24">Foto</th>
                            <th class="py-4 px-5">Nama Unit</th>
                            <th class="py-4 px-5">Kategori</th>
                            <th class="py-4 px-5">Harga Skema</th>
                            <th class="py-4 px-5 text-center w-40">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs font-semibold text-slate-600 divide-y divide-slate-100">
                        @forelse($items as $item)
                            <tr class="hover:bg-white transition duration-150">
                                <td class="py-3 px-5">
                                    <img src="{{ asset($item->image) }}" class="w-12 h-10 object-cover rounded-lg border border-slate-100 shadow-sm">
                                </td>
                                <td class="py-3 px-5 font-bold text-slate-900 leading-tight">
                                    {{ $item->name }}
                                    <div class="text-[10px] text-slate-400 font-medium mt-0.5">{{ str_replace(',', ' • ', $item->features) }}</div>
                                </td>
                                <td class="py-3 px-5">
                                    <span class="bg-violet-50 text-violet-600 px-2.5 py-1 rounded-full text-[10px] font-bold">
                                        {{ $item->category->name }}
                                    </span>
                                </td>
                                <td class="py-3 px-5 font-extrabold text-slate-900">
                                    Rp {{ $item->price }} <span class="text-[10px] text-slate-400 font-normal">/{{ $item->rent_mode ?? 'hari' }}</span>
                                </td>
                                <td class="py-3 px-5">
                                    <div class="flex items-center justify-center gap-2">
                                        <button onclick="openEditModal(
                                                    '{{ $item->id }}', 
                                                    '{{ addslashes($item->name) }}', 
                                                    '{{ $item->category_id }}', 
                                                    '{{ $item->price }}', 
                                                    '{{ $item->rent_mode ?? 'hari' }}', 
                                                    '{{ json_encode($item->features) }}'
                                                )" 
                                                class="bg-blue-50 hover:bg-blue-100 text-blue-600 px-3 py-1.5 rounded-xl text-xs font-bold transition duration-200 cursor-pointer">
                                            Edit
                                        </button>

                                        <form method="POST" action="{{ route('admin.items.destroy', $item->id) }}" onsubmit="return confirm('Hapus unit ini dari katalog?')">
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
                                <td colspan="5" class="py-24 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center space-y-4 px-4">
                                        <span class="text-6xl opacity-75">🚗</span>
                                        <p class="font-extrabold text-slate-700 text-sm tracking-tight">Katalog unit barang masih kosong</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <div id="editItemModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-all duration-200">
        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-xl w-full max-w-2xl overflow-hidden p-6 transform scale-95 transition-transform duration-200">
            
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-5">
                <h4 class="font-extrabold text-base text-slate-900 flex items-center gap-2">
                    <span>✏️</span> Edit Data Unit Sewa
                </h4>
                <button type="button" onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600 border-0 bg-transparent text-lg cursor-pointer font-bold">✕</button>
            </div>

            <form id="editItemForm" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="flex flex-col">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-wide">Kategori</label>
                        <select name="category_id" id="edit_category_id" class="input-premium-light cursor-pointer" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex flex-col">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-wide">Nama Unit</label>
                        <input type="text" name="name" id="edit_name" class="input-premium-light" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="flex flex-col">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-wide">Harga Sewa (Rp)</label>
                        <input type="text" name="price" id="edit_price" class="input-premium-light" required>
                    </div>

                    <div class="flex flex-col">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-wide">Skema Waktu</label>
                        <select name="rent_mode" id="edit_rent_mode" class="input-premium-light cursor-pointer" required>
                            <option value="hari">Per Hari</option>
                            <option value="bulan">Per Bulan</option>
                        </select>
                    </div>

                    <div class="flex flex-col">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-wide">Fitur Utama</label>
                        <input type="text" name="features" id="edit_features" class="input-premium-light" required>
                    </div>
                </div>

                <div class="flex flex-col">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-wide">Ganti Foto Unit <span class="text-[10px] text-slate-400 lowercase">(Kosongkan jika tidak ingin diubah)</span></label>
                    <label class="group relative flex flex-row items-center justify-between w-full h-[46px] border border-dashed border-slate-200 rounded-xl bg-slate-50/50 hover:bg-violet-50/30 hover:border-violet-400 transition duration-200 cursor-pointer overflow-hidden px-4 mt-2">
                        <div class="flex items-center gap-2">
                            <span class="text-base">📸</span>
                            <p class="text-xs font-bold text-slate-600" id="editUploadText">Pilih berkas foto baru jika ada...</p>
                        </div>
                        <input type="file" name="image" id="editImageInput" accept="image/*" class="hidden" onchange="document.getElementById('editUploadText').innerText = this.files[0].name">
                    </label>
                </div>

                <div class="pt-3 border-t border-slate-100 flex justify-end gap-3">
                    <button type="button" onclick="closeEditModal()" class="px-5 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs uppercase tracking-wider cursor-pointer border-0">Batal</button>
                    <button type="submit" class="bg-violet-600 hover:bg-violet-500 text-white font-bold text-xs px-8 py-3 rounded-xl shadow-md shadow-violet-600/25 transition duration-200 uppercase tracking-wider cursor-pointer border-0">Simpan Perubahan 💾</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewFileNama() {
            const input = document.getElementById('imageInput');
            const prompt = document.getElementById('uploadPrompt');
            const preview = document.getElementById('filePreview');
            const nameText = document.getElementById('fileNameText');
            const badge = document.getElementById('fileBadge');

            if (input.files && input.files[0]) {
                nameText.innerText = input.files[0].name;
                prompt.classList.add('hidden');
                preview.classList.remove('hidden');
                preview.classList.add('flex');
                badge.classList.remove('hidden');
            }
        }

        // Buka Pop-up Edit dan Isi Data Otomatis (Fix Anti-Crash + Sinkronisasi Rent Mode)
        function openEditModal(id, name, categoryId, price, rentMode, featuresJson) {
            const modal = document.getElementById('editItemModal');
            const form = document.getElementById('editItemForm');
            
            form.action = "{{ url('admin/items') }}/" + id;

            // Isi value input form modal
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_category_id').value = categoryId;
            document.getElementById('edit_price').value = price;
            document.getElementById('edit_rent_mode').value = rentMode;

            // Bersihkan data JSON string agar tampil sebagai teks normal di textarea
            try {
                let cleanFeatures = JSON.parse(featuresJson);
                document.getElementById('edit_features').value = cleanFeatures;
            } catch(e) {
                document.getElementById('edit_features').value = featuresJson;
            }
            
            document.getElementById('editUploadText').innerText = "Pilih berkas foto baru jika ada...";

            // Tampilkan modal
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeEditModal() {
            const modal = document.getElementById('editItemModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
@endsection