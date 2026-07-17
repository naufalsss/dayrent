@extends('admin.master')

@section('content')
    @if(session('success'))
        <div class="p-4 mb-6 text-xs font-bold text-emerald-600 bg-emerald-50 rounded-2xl border border-emerald-200 flex items-center gap-2 max-w-5xl">
            <span>✨</span> {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col gap-6 w-full max-w-5xl items-stretch">
        
        <!-- FORM TAMBAH SLIDER PROMO -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm w-full">
            <h4 class="font-extrabold text-xs text-slate-800 pb-3 border-b border-slate-100 mb-5 uppercase tracking-wider">
                Tambah Card Slider
            </h4>
            
            <form method="POST" action="{{ route('admin.promos.store') }}" enctype="multipart/form-data" class="space-y-6" onsubmit="assembleFinalLinkUrl('create')">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 items-start">
                    
                    <div class="flex flex-col">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Nama Tag (Badge)</label>
                        <input type="text" name="tag" placeholder="Misal: Universal Promo" 
                        class="w-full mt-2 bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:border-blue-600 focus:bg-white outline-none transition h-[42px]" required>
                    </div>

                    <div class="flex flex-col">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Isi Kalimat Promo</label>
                        <input type="text" name="title" placeholder="Misal: Dapatkan Diskon Sewa Hingga 20%!" 
                        class="w-full mt-2 bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:border-blue-600 focus:bg-white outline-none transition h-[42px]" required>
                    </div>

                    <!-- DROPDOWN WARNA: DIBERSIHKAN DARI EMOJI & WARNA TEXT ALAY -->
                    <div class="flex flex-col">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Warna Tema Badge</label>
                        <select name="badge_color" class="w-full mt-2 bg-slate-50/50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:border-blue-600 focus:bg-white outline-none transition h-[42px] cursor-pointer font-semibold" required>
                            <option value="#3b82f6">Biru (Default)</option>
                            <option value="#a855f7">Ungu</option>
                            <option value="#10b981">Hijau (Emerald)</option>
                            <option value="#f59e0b">Kuning (Amber)</option>
                            <option value="#f43f5e">Merah (Rose)</option>
                        </select>
                    </div>

                    <div class="flex flex-col">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Teks Tombol Tautan</label>
                        <input type="text" name="link_text" placeholder="Misal: Gunakan Promo, Jelajahi" 
                        class="w-full mt-2 bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:border-blue-600 focus:bg-white outline-none transition h-[42px]" required>
                    </div>

                    <div class="flex flex-col">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Upload Foto Background</label>
                        <input type="file" name="background_image" 
                        class="w-full mt-2 bg-slate-50/50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-600 file:mr-3 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 outline-none transition h-[42px] cursor-pointer" required>
                    </div>

                    <div class="flex flex-col">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Tipe Target Tautan</label>
                        <select id="targetTypeSelect" onchange="switchTargetTypeForm(this.value)" class="w-full mt-2 bg-slate-50/50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 font-bold focus:border-blue-600 focus:bg-white outline-none transition h-[42px] cursor-pointer">
                            <option value="item">Targeting Item</option>
                            <option value="category">Targeting Kategori</option>
                        </select>
                    </div>

                    <!-- BOX DINAMIS INPUT TARGET -->
                    <div class="flex flex-col md:col-span-2 lg:col-span-3 bg-slate-50 border border-slate-100 p-4 rounded-xl space-y-3">
                        <div id="wrapperTargetItem" class="space-y-3">
                            <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Pencarian &amp; Pemilihan Unit Barang</label>
                            <div class="flex gap-2 w-full">
                                <select id="categoryFilter" class="bg-white border border-slate-200 text-slate-700 text-xs font-medium px-3 py-2 rounded-xl cursor-pointer focus:outline-none focus:border-blue-600 h-[42px] min-w-[100px]">
                                    <option value="all">Semua</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <input type="text" id="itemSearchInput" placeholder="Ketik nama unit barang untuk memfilter..." class="bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs flex-1 text-slate-800 focus:outline-none focus:border-blue-600 h-[42px]">
                            </div>

                            <select id="itemSelect" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 font-bold focus:border-blue-600 outline-none transition h-[42px] cursor-pointer">
                                <option value="" disabled selected>-- Pilih Unit Barang Yang Ingin Dipromokan --</option>
                                @foreach($items as $item)
                                    <option value="/items/{{ $item->id }}/checkout" data-category="{{ $item->category_id }}" data-name="{{ strtolower($item->name) }}">
                                        [{{ $item->category->name ?? 'Tanpa Kategori' }}] {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div id="wrapperTargetCategory" class="space-y-2 hidden">
                            <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Pilih Halaman Kategori Utama</label>
                            <select id="categorySelect" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 font-bold focus:border-blue-600 outline-none transition h-[42px] cursor-pointer">
                                <option value="" disabled selected>-- Pilih Target Kategori Yang Dituju --</option>
                                @foreach($categories as $category)
                                    <option value="/catalog?category={{ $category->slug }}&search=">
                                        Kategori: {{ $category->name }} (Menampilkan Semua Barang Terkait)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <input type="hidden" name="link_url" id="realLinkUrlInput" value="">
                    </div>

                </div>

                <div class="flex justify-end pt-4 border-t border-slate-100">
                    <button type="submit" class="w-full md:w-auto md:min-w-[240px] bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs py-3 rounded-xl shadow-sm transition duration-150 uppercase tracking-wider flex items-center justify-center gap-2 cursor-pointer border-0 h-[44px]">
                        Simpan Promo Slider
                    </button>
                </div>
            </form>
        </div>

        <!-- DAFTAR SLIDER PROMO AKTIF (UI SUDAH DIRAPIKAN) -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm w-full overflow-hidden">
            <h4 class="font-extrabold text-xs text-slate-800 pb-3 border-b border-slate-100 mb-5 uppercase tracking-wider">
                Daftar Card Slider Aktif
            </h4>

            <div class="overflow-x-auto rounded-xl border border-slate-200">
                <table class="w-full text-left border-collapse table-fixed">
                    <thead>
                        <tr class="text-[10px] font-bold uppercase tracking-widest text-slate-400 bg-slate-50 border-b border-slate-200">
                            <th class="py-3.5 px-4 w-24">Background</th>
                            <th class="py-3.5 px-4 w-36">Tag/Badge</th>
                            <th class="py-3.5 px-4">Isi Konten Promo</th>
                            <th class="py-3.5 px-4 w-52">Tautan Hasil URL</th>
                            <th class="py-3.5 px-4 text-center w-36">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs font-medium text-slate-600 divide-y divide-slate-100 align-middle">
                        @forelse($promos as $promo)
                            @php
                                $is_item_link = preg_match('/\/items\/(\d+)\/checkout/', $promo->link_url, $item_matches);
                                $is_cat_link = preg_match('/\/catalog\?category=([^&]+)/', $promo->link_url, $cat_matches);
                                
                                $extracted_item_id = $item_matches[1] ?? '';
                                $extracted_cat_slug = $cat_matches[1] ?? '';
                            @endphp
                            <tr class="hover:bg-slate-50/40 transition duration-100">
                                <td class="py-4 px-4">
                                    @if($promo->image)
                                        <img src="{{ asset('storage/' . $promo->image) }}" class="h-9 w-16 object-cover rounded-lg border border-slate-100 shadow-sm">
                                    @else
                                        <div class="h-9 w-16 bg-slate-100 text-slate-400 text-[9px] rounded-lg flex items-center justify-center font-bold">No Image</div>
                                    @endif
                                </td>
                                <td class="py-4 px-4">
                                    <span class="inline-block px-2.5 py-1 rounded-md text-[10px] font-bold uppercase text-white shadow-sm tracking-wide text-center"
                                          style="background-color: {{ str_contains($promo->badge_color, '#') ? $promo->badge_color : '#3b82f6' }}">
                                        {{ $promo->tag }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 font-bold text-slate-800 leading-relaxed break-words">
                                    {{ $promo->title }}
                                </td>
                                <td class="py-4 px-4">
                                    <!-- FIX TEXT OVERFLOW: URL terpotong rapi dengan ellipsis (...) dan tidak merusak layout -->
                                    <span class="font-mono text-blue-600 font-bold text-[11px] tracking-tight truncate block max-w-[190px]" title="{{ $promo->link_url }}">
                                        {{ $promo->link_url }}
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    <!-- FIX ACTIONS GRID: Dibuat sejajar rapi dengan tinggi seragam -->
                                    <div class="flex items-center justify-center gap-1.5">
                                        <button type="button" 
                                                onclick="openEditPromoModal('{{ $promo->id }}', '{{ $promo->tag }}', '{{ $promo->title }}', '{{ $promo->badge_color }}', '{{ $promo->link_text }}', '{{ $promo->link_url }}', '{{ $extracted_item_id }}', '{{ $extracted_cat_slug }}')" 
                                        class="bg-slate-100 hover:bg-slate-200 border border-slate-200 text-slate-700 h-7 px-2.5 rounded-lg text-[10px] font-bold uppercase tracking-wider transition cursor-pointer flex items-center justify-center">
                                            Edit
                                        </button>

                                        <form method="POST" action="{{ route('admin.promos.destroy', $promo->id) }}" onsubmit="return confirm('Hapus card promo ini dari slider depan?')" class="m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-600 h-7 px-2.5 rounded-lg font-bold transition cursor-pointer text-[10px] uppercase tracking-wider flex items-center justify-center">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-16 text-center text-slate-400 font-medium">Belum ada data promo dinamis. Slider depan otomatis menggunakan fallback template, Bree!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- MODAL BOX MODERASI EDIT SLIDER PROMO -->
    <div id="editPromoModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 w-full max-w-xl overflow-hidden p-6 transform transition-all">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-5">
                <h3 class="font-extrabold text-xs text-slate-800 uppercase tracking-wider">
                    Edit Card Slider Promo
                </h3>
                <button type="button" onclick="closeEditPromoModal()" class="text-slate-400 hover:text-slate-600 font-bold border-0 bg-transparent text-sm cursor-pointer">✕</button>
            </div>

            <form id="editPromoForm" method="POST" enctype="multipart/form-data" class="space-y-4" onsubmit="assembleFinalLinkUrl('edit')">
                @csrf
                @method('PUT')

                <div class="flex flex-col">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Nama Tag (Badge)</label>
                    <input type="text" name="tag" id="edit_tag" class="w-full mt-2 bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-2 text-xs text-slate-800 focus:border-blue-600 focus:bg-white outline-none transition h-[42px]" required>
                </div>

                <div class="flex flex-col">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Isi Kalimat Promo</label>
                    <input type="text" name="title" id="edit_title" class="w-full mt-2 bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-2 text-xs text-slate-800 focus:border-blue-600 focus:bg-white outline-none transition h-[42px]" required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- EDIT COLOR SELECT: DIBERSIHKAN JUGA AGAR CLEAN -->
                    <div class="flex flex-col">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Warna Tema Badge</label>
                        <select name="badge_color" id="edit_badge_color" class="w-full mt-2 bg-slate-50/50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:border-blue-600 focus:bg-white outline-none transition h-[42px] cursor-pointer font-semibold" required>
                            <option value="#3b82f6">Biru</option>
                            <option value="#a855f7">Ungu</option>
                            <option value="#10b981">Hijau</option>
                            <option value="#f59e0b">Kuning</option>
                            <option value="#f43f5e">Merah</option>
                        </select>
                    </div>

                    <div class="flex flex-col">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Teks Tombol Tautan</label>
                        <input type="text" name="link_text" id="edit_link_text" class="w-full mt-2 bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-2 text-xs text-slate-800 focus:border-blue-600 focus:bg-white outline-none transition h-[42px]" required>
                    </div>
                </div>

                <div class="flex flex-col">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Ganti Foto Background <span class="text-[10px] text-slate-400 lowercase">(Kosongkan jika tidak diubah)</span></label>
                    <input type="file" name="background_image" class="w-full mt-2 bg-slate-50/50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-600 file:mr-3 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 outline-none transition h-[42px] cursor-pointer">
                </div>

                <div class="flex flex-col bg-slate-50 p-4 border border-slate-200 rounded-xl space-y-3">
                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Modifikasi Target Link Tautan</label>
                    <select id="editTargetTypeSelect" onchange="switchEditTargetTypeForm(this.value)" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 outline-none h-[42px]">
                        <option value="item">Targeting Item Barang</option>
                        <option value="category">Targeting Halaman Kategori</option>
                    </select>

                    <div id="editWrapperItem">
                        <select id="edit_item_id" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 font-bold h-[42px]">
                            <option value="">-- Silakan Pilih Unit Barang --</option>
                            @foreach($items as $item)
                                <option value="/items/{{ $item->id }}/checkout">[{{ $item->category->name ?? 'Tanpa Kategori' }}] {{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="editWrapperCategory" class="hidden">
                        <select id="edit_category_id" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 font-bold h-[42px]">
                            <option value="">-- Silakan Pilih Kategori Target --</option>
                            @foreach($categories as $category)
                                <option value="/catalog?category={{ $category->slug }}&search=">Kategori: {{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <input type="hidden" name="link_url" id="editRealLinkUrlInput" value="">
                </div>

                <div class="flex gap-2.5 justify-end pt-4 mt-4 border-t border-slate-100">
                    <button type="button" onclick="closeEditPromoModal()" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs uppercase tracking-wider transition border-0 cursor-pointer">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs shadow-sm transition border-0 cursor-pointer uppercase tracking-wider">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- JAVASCRIPT LOGIC ENGINE -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const categoryFilter = document.getElementById('categoryFilter');
            const itemSearchInput = document.getElementById('itemSearchInput');
            const itemSelect = document.getElementById('itemSelect');
            const originalOptions = Array.from(itemSelect.options);

            function filterItems() {
                const selectedCategory = categoryFilter.value;
                const searchText = itemSearchInput.value.toLowerCase().trim();
                itemSelect.innerHTML = '';
                originalOptions.forEach(option => {
                    if (option.value === "") { itemSelect.appendChild(option); return; }
                    const itemCategory = option.getAttribute('data-category');
                    const itemName = option.getAttribute('data-name');
                    if ((selectedCategory === 'all' || itemCategory === selectedCategory) && itemName.includes(searchText)) {
                        itemSelect.appendChild(option);
                    }
                });
            }
            categoryFilter.addEventListener('change', filterItems);
            itemSearchInput.addEventListener('input', filterItems);
        });

        function switchTargetTypeForm(type) {
            if(type === 'item') {
                document.getElementById('wrapperTargetItem').classList.remove('hidden');
                document.getElementById('wrapperTargetCategory').classList.add('hidden');
            } else {
                document.getElementById('wrapperTargetItem').classList.add('hidden');
                document.getElementById('wrapperTargetCategory').classList.remove('hidden');
            }
        }

        function switchEditTargetTypeForm(type) {
            if(type === 'item') {
                document.getElementById('editWrapperItem').classList.remove('hidden');
                document.getElementById('editWrapperCategory').classList.add('hidden');
            } else {
                document.getElementById('editWrapperItem').classList.add('hidden');
                document.getElementById('editWrapperCategory').classList.remove('hidden');
            }
        }

        function assembleFinalLinkUrl(mode) {
            if(mode === 'create') {
                const type = document.getElementById('targetTypeSelect').value;
                const finalInput = document.getElementById('realLinkUrlInput');
                if(type === 'item') {
                    finalInput.value = document.getElementById('itemSelect').value;
                } else {
                    finalInput.value = document.getElementById('categorySelect').value;
                }
            } else {
                const type = document.getElementById('editTargetTypeSelect').value;
                const finalEditInput = document.getElementById('editRealLinkUrlInput');
                if(type === 'item') {
                    finalEditInput.value = document.getElementById('edit_item_id').value;
                } else {
                    finalEditInput.value = document.getElementById('edit_category_id').value;
                }
            }
        }

        function openEditPromoModal(id, tag, title, badge_color, link_text, full_link, item_id, cat_slug) {
            const modal = document.getElementById('editPromoModal');
            const form = document.getElementById('editPromoForm');
            
            form.action = "{{ url('admin/promos') }}/" + id;

            document.getElementById('edit_tag').value = tag;
            document.getElementById('edit_title').value = title;
            document.getElementById('edit_badge_color').value = badge_color;
            document.getElementById('edit_link_text').value = link_text;
            document.getElementById('editRealLinkUrlInput').value = full_link;
            
            if(cat_slug !== '') {
                document.getElementById('editTargetTypeSelect').value = 'category';
                switchEditTargetTypeForm('category');
                document.getElementById('edit_category_id').value = "/catalog?category=" + cat_slug + "&search=";
                document.getElementById('edit_item_id').value = "";
            } else {
                document.getElementById('editTargetTypeSelect').value = 'item';
                switchEditTargetTypeForm('item');
                document.getElementById('edit_item_id').value = "/items/" + item_id + "/checkout";
                document.getElementById('edit_category_id').value = "";
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        document.getElementById('editPromoModal').addEventListener('click', function(e) {
            if(e.target === this) closeEditPromoModal();
        });

        function closeEditPromoModal() {
            const modal = document.getElementById('editPromoModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
@endsection