@extends('merchant.master')

@section('content')
    @if(session('success'))
        <div class="p-4 mb-6 text-xs font-bold text-emerald-600 bg-emerald-50 rounded-2xl border border-emerald-200 flex items-center gap-2 max-w-6xl">
            <span>✨</span> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 mb-6 text-xs font-bold text-rose-600 bg-rose-50 rounded-2xl border border-rose-200 flex items-center gap-2 max-w-6xl">
            <span>⚠️</span> {{ session('error') }}
        </div>
    @endif

    <div class="flex flex-col gap-6 w-full max-w-6xl">
        
        <!-- BAGIAN 1: FORM TAMBAH DATA UNIT -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm">
            <h4 class="font-extrabold text-xs text-slate-800 pb-3 border-b border-slate-100 mb-5 uppercase tracking-wider">
                Tambah Unit Sewa Baru (Merchant)
            </h4>
            
            <form method="POST" action="{{ route('merchant.items.store') }}" enctype="multipart/form-data" class="space-y-5">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="flex flex-col">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Pilih Kategori</label>
                        <select name="category_id" class="w-full mt-2 bg-slate-50/50 border border-slate-200/80 rounded-xl px-3 py-2 text-xs text-slate-800 focus:border-blue-600 focus:bg-white outline-none transition h-[42px] cursor-pointer" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex flex-col">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Nama Unit Barang</label>
                        <input type="text" name="name" placeholder="Misal: Honda Vario 160cc" 
                        class="w-full mt-2 bg-slate-50/50 border border-slate-200/80 rounded-xl px-4 py-2 text-xs text-slate-800 placeholder-slate-400 focus:border-blue-600 focus:bg-white outline-none transition h-[42px]" required>
                    </div>
                    
                    <div class="flex flex-col">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Harga Sewa (Rp)</label>
                        <input type="number" name="price" placeholder="Misal: 85000" 
                        class="w-full mt-2 bg-slate-50/50 border border-slate-200/80 rounded-xl px-4 py-2 text-xs text-slate-800 placeholder-slate-400 focus:border-blue-600 focus:bg-white outline-none transition h-[42px]" required>
                    </div>

                    <div class="flex flex-col">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Rentang Waktu Sewa</label>
                        <select name="rent_mode" class="w-full mt-2 bg-slate-50/50 border border-slate-200/80 rounded-xl px-3 py-2 text-xs text-slate-800 focus:border-blue-600 focus:bg-white outline-none transition h-[42px] cursor-pointer" required>
                            <option value="hari">Per Hari</option>
                            <option value="bulan">Per Bulan</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
                    <div class="flex flex-col md:col-span-1">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Stok Awal Unit</label>
                        <input type="number" name="stock" min="1" placeholder="Misal: 3" 
                        class="w-full mt-2 bg-slate-50/50 border border-slate-200/80 rounded-xl px-4 py-2 text-xs text-slate-800 placeholder-slate-400 focus:border-blue-600 focus:bg-white outline-none transition h-[42px]" required>
                    </div>

                    <div class="flex flex-col md:col-span-1">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Fitur Utama</label>
                        <input type="text" name="features" placeholder="Misal: 2 Helm SNI, Jas Hujan Double" 
                        class="w-full mt-2 bg-slate-50/50 border border-slate-200/80 rounded-xl px-4 py-2 text-xs text-slate-800 placeholder-slate-400 focus:border-blue-600 focus:bg-white outline-none transition h-[42px]" required>
                        <span class="text-[10px] text-slate-400 font-bold leading-tight mt-2 block">*Gunakan tanda koma ( , ) sebagai pembatas fitur</span>
                    </div>

                    <div class="flex flex-col md:col-span-1">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Upload Foto Unit</label>
                        <label class="group relative flex flex-row items-center justify-between w-full h-[42px] border border-dashed border-slate-200 rounded-xl bg-slate-50/50 hover:bg-blue-50/30 hover:border-blue-400 transition duration-200 cursor-pointer overflow-hidden px-4 mt-2">
                            <div class="flex items-center gap-2" id="uploadPrompt">
                                <span class="text-xs group-hover:animate-bounce">📸</span>
                                <p class="text-xs font-bold text-slate-500">Pilih berkas foto unit</p>
                            </div>
                            <div class="hidden items-center gap-2" id="filePreview">
                                <span class="text-xs">🖼️</span>
                                <p class="text-xs font-bold text-blue-600 truncate max-w-[150px]" id="fileNameText"></p>
                            </div>
                            <span class="text-[10px] text-emerald-500 font-bold bg-emerald-50 px-2 py-0.5 rounded-full hidden" id="fileBadge">Terpilih</span>
                            <input type="file" name="image" id="imageInput" accept="image/*" class="hidden" required onchange="previewFileNama()">
                        </label>
                    </div>
                </div>

                <div class="pt-2 flex justify-end">
                    <button type="submit" class="w-full md:w-auto bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs px-8 py-2.5 rounded-xl shadow-sm transition duration-150 uppercase tracking-wider cursor-pointer border-0 h-[42px]">
                        Simpan Unit Sewa
                    </button>
                </div>
            </form>
        </div>

        <!-- BAGIAN 2: TABEL DATA UNIT DENGAN ENGINE FILTER BARU -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm overflow-hidden">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-slate-100 mb-5">
                <h4 class="font-extrabold text-xs text-slate-800 uppercase tracking-wider">
                    Unit Barang yang Tersedia
                </h4>
                
                <!-- ENGINE FILTER CASING PLATINUM -->
                <form method="GET" action="{{ route('merchant.items.index') }}" class="flex flex-wrap items-center gap-2.5 w-full md:w-auto">
                    <div class="relative w-full sm:w-48">
                        <input type="text" name="keyword" value="{{ $keyword ?? '' }}" placeholder="Cari nama unit..." 
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 text-xs text-slate-800 placeholder-slate-400 outline-none focus:border-blue-600 focus:bg-white transition h-[36px]">
                    </div>
                    
                    <div class="w-full sm:w-44">
                        <select name="category_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-2.5 py-1.5 text-xs text-slate-800 outline-none focus:border-blue-600 focus:bg-white transition h-[36px] cursor-pointer">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ (isset($categoryId) && $categoryId == $cat->id) ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="flex gap-1.5 w-full sm:w-auto">
                        <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-white font-bold text-[11px] px-4 py-1.5 rounded-xl uppercase tracking-wider transition h-[36px] cursor-pointer border-0">
                            Filter
                        </button>
                        @if(!empty($keyword) || !empty($categoryId))
                            <a href="{{ route('merchant.items.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-[11px] px-3 py-2 rounded-xl uppercase tracking-wider transition h-[36px] flex items-center justify-center border border-slate-200/60 decoration-none">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto rounded-xl border border-slate-200/60">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-[10px] font-bold uppercase tracking-widest text-slate-400 bg-slate-50 border-b border-slate-200/60">
                            <th class="py-3.5 px-5 w-24">Foto</th>
                            <th class="py-3.5 px-5">Nama Unit</th>
                            <th class="py-3.5 px-5">Kategori</th>
                            <th class="py-3.5 px-5">Harga Skema</th>
                            <th class="py-3.5 px-5 text-center w-40">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs font-medium text-slate-600 divide-y divide-slate-100">
                        @forelse($items as $item)
                            <tr class="hover:bg-slate-50/40 transition duration-100">
                                <td class="py-3 px-5">
                                    <img src="{{ (str_starts_with($item->image, '/storage/') || str_starts_with($item->image, 'storage/')) ? asset(ltrim($item->image, '/')) : asset('storage/' . $item->image) }}" 
                                         class="w-12 h-9 object-cover rounded-lg border border-slate-100 shadow-sm">
                                </td>
                                <td class="py-3 px-5 font-bold text-slate-800 leading-tight">
                                    {{ $item->name }}
                                    <div class="text-[10px] text-slate-400 font-medium mt-0.5">{{ str_replace(',', ' • ', $item->features) }}</div>
                                </td>
                                <td class="py-3 px-5">
                                    <span class="bg-blue-50 text-blue-600 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">
                                        {{ $item->category_name ?? 'Tanpa Kategori' }}
                                    </span>
                                </td>
                                <td class="py-3 px-5 font-extrabold text-slate-900">
                                    Rp {{ number_format(str_replace('.', '', $item->price), 0, ',', '.') }} <span class="text-[10px] text-slate-400 font-normal">/{{ $item->rent_mode ?? 'hari' }}</span>
                                </td>
                                <td class="py-3 px-5">
                                    <div class="flex items-center justify-center gap-2">
                                        <!-- FIX MODAL: Data stock parameter dihilangkan dari pemanggilan openEditModal -->
                                        <button onclick="openEditModal(
                                                    '{{ $item->id }}', 
                                                    '{{ addslashes($item->name) }}', 
                                                    '{{ $item->category_id }}', 
                                                    '{{ $item->price }}', 
                                                    '{{ $item->rent_mode ?? 'hari' }}', 
                                                    '{{ addslashes($item->features) }}'
                                                )" 
                                        class="bg-slate-100 hover:bg-slate-200 border border-slate-200/60 text-slate-700 px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider transition cursor-pointer">
                                            Edit
                                        </button>

                                        <form method="POST" action="{{ route('merchant.items.destroy', $item->id) }}" onsubmit="return confirm('Hapus unit ini dari katalog?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-rose-50 hover:bg-rose-100 border border-rose-200/40 text-rose-600 px-3 py-1.5 rounded-lg font-bold transition cursor-pointer text-[10px] uppercase tracking-wider border-0">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-20 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center space-y-3 px-4">
                                        <span class="text-4xl opacity-50">🔍</span>
                                        <p class="font-extrabold text-slate-700 text-xs tracking-tight uppercase">Tidak ada unit sewa yang sesuai dengan pencarian</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- MODAL EDIT RINGKAS TANPA INPUTAN STOK -->
    <div id="editItemModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-all duration-200">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-xl w-full max-w-xl overflow-hidden p-6 transform scale-95 transition-transform duration-200">
            
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-5">
                <h4 class="font-extrabold text-xs text-slate-800 uppercase tracking-wider">
                    Edit Data Unit Sewa Merchant
                </h4>
                <button type="button" onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600 border-0 bg-transparent text-sm cursor-pointer font-bold">✕</button>
            </div>

            <form id="editItemForm" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="flex flex-col">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Kategori</label>
                        <select name="category_id" id="edit_category_id" class="w-full mt-2 bg-slate-50/50 border border-slate-200/80 rounded-xl px-3 py-2 text-xs text-slate-800 focus:border-blue-600 focus:bg-white outline-none transition h-[42px] cursor-pointer" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex flex-col">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Nama Unit</label>
                        <input type="text" name="name" id="edit_name" class="w-full mt-2 bg-slate-50/50 border border-slate-200/80 rounded-xl px-4 py-2 text-xs text-slate-800 focus:border-blue-600 focus:bg-white outline-none transition h-[42px]" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="flex flex-col">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Harga Sewa (Rp)</label>
                        <input type="text" name="price" id="edit_price" class="w-full mt-2 bg-slate-50/50 border border-slate-200/80 rounded-xl px-4 py-2 text-xs text-slate-800 focus:border-blue-600 focus:bg-white outline-none transition h-[42px]" required>
                    </div>

                    <div class="flex flex-col">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Skema Waktu</label>
                        <select name="rent_mode" id="edit_rent_mode" class="w-full mt-2 bg-slate-50/50 border border-slate-200/80 rounded-xl px-3 py-2 text-xs text-slate-800 focus:border-blue-600 focus:bg-white outline-none transition h-[42px] cursor-pointer" required>
                            <option value="hari">Per Hari</option>
                            <option value="bulan">Per Bulan</option>
                        </select>
                    </div>
                </div>

                <div class="flex flex-col">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Fitur Utama</label>
                    <input type="text" name="features" id="edit_features" class="w-full mt-2 bg-slate-50/50 border border-slate-200/80 rounded-xl px-4 py-2 text-xs text-slate-800 focus:border-blue-600 focus:bg-white outline-none transition h-[42px]" required>
                </div>

                <div class="flex flex-col">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Ganti Foto Unit <span class="text-[10px] text-slate-400 lowercase">(Kosongkan jika tidak ingin diubah)</span></label>
                    <label class="group relative flex flex-row items-center justify-between w-full h-[42px] border border-dashed border-slate-200 rounded-xl bg-slate-50/50 hover:bg-blue-50/30 hover:border-blue-400 transition duration-200 cursor-pointer overflow-hidden px-4 mt-2">
                        <div class="flex items-center gap-2">
                            <span class="text-xs">📸</span>
                            <p class="text-xs font-bold text-slate-500" id="editUploadText">Pilih berkas foto baru jika ada...</p>
                        </div>
                        <input type="file" name="image" id="editImageInput" accept="image/*" class="hidden" onchange="document.getElementById('editUploadText').innerText = this.files[0].name">
                    </label>
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-end gap-2.5">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs uppercase tracking-wider cursor-pointer border-0">Batal</button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs px-6 py-2 rounded-xl shadow-sm transition duration-150 uppercase tracking-wider cursor-pointer border-0">Simpan Perubahan</button>
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

        // FIX: Parameter stock dibuang agar modal bekerja ringkas dan bersih
        function openEditModal(id, name, categoryId, price, rentMode, featuresJson) {
            const modal = document.getElementById('editItemModal');
            const form = document.getElementById('editItemForm');
            
            form.action = "{{ url('merchant/items') }}/" + id;

            document.getElementById('edit_name').value = name;
            document.getElementById('edit_category_id').value = categoryId;
            document.getElementById('edit_price').value = price;
            document.getElementById('edit_rent_mode').value = rentMode;

            try {
                let cleanFeatures = JSON.parse(featuresJson);
                document.getElementById('edit_features').value = cleanFeatures;
            } catch(e) {
                document.getElementById('edit_features').value = featuresJson;
            }
            
            document.getElementById('editUploadText').innerText = "Pilih berkas foto baru jika ada...";

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