@extends('admin.master')

@section('content')
    <style>
        .finset-card { border-radius: 24px !important; border: 1px solid #e2e8f0 !important; background-color: #ffffff !important; }
        .input-finset { background-color: #f8fafc !important; border: 1px solid #e2e8f0 !important; border-radius: 12px !important; padding: 10px 14px !important; font-size: 13px !important; color: #1e293b !important; margin-top: 8px !important; }
        .input-finset:focus { border-color: #7c3aed !important; background-color: #ffffff !important; outline: none; }
    </style>

    @if(session('success'))
        <div class="p-4 mb-4 text-sm font-bold text-emerald-700 bg-emerald-50 rounded-2xl border border-emerald-200 flex items-center gap-2">
            <span>✅</span> {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col gap-6 w-full items-stretch">
        
        <div class="finset-card p-6 shadow-sm w-full">
            <h4 class="font-extrabold text-base text-slate-900 pb-3 border-b border-slate-100 mb-5 flex items-center gap-2">
                <span>➕</span> Tambah Card Slider
            </h4>
            
            <form method="POST" action="{{ route('admin.promos.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 items-end">
                    <div class="flex flex-col">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-wide">Nama Tag (Badge)</label>
                        <input type="text" name="tag" placeholder="Misal: Universal Promo" class="input-finset" required>
                    </div>

                    <div class="flex flex-col">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-wide">Isi Kalimat Promo</label>
                        <input type="text" name="title" placeholder="Misal: Dapatkan Diskon Sewa Hingga 20%!" class="input-finset" required>
                    </div>
                    
                    <div class="flex flex-col">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-wide">Warna Tema Badge</label>
                        <select name="badge_color" class="input-finset cursor-pointer" required>
                            <option value="blue">Biru (Blue)</option>
                            <option value="purple">Ungu (Purple)</option>
                            <option value="emerald">Hijau (Emerald)</option>
                            <option value="amber">Kuning (Amber)</option>
                            <option value="rose">Merah (Rose)</option>
                        </select>
                    </div>

                    <div class="flex flex-col">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-wide">Teks Tombol Tautan</label>
                        <input type="text" name="link_text" placeholder="Misal: Lihat Ketentuan, Jelajahi" class="input-finset" required>
                    </div>

                    <div class="flex flex-col">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-wide">Upload Foto Background</label>
                        <input type="file" name="background_image" class="input-finset file:mr-4 file:py-1 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100 cursor-pointer" required>
                    </div>

                    <div class="flex flex-col relative">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-wide">Target Unit Barang</label>
                        <div class="flex gap-2 mt-2">
                            <select id="categoryFilter" class="bg-slate-100 text-slate-700 text-xs font-bold px-3 py-2.5 rounded-xl border border-slate-200 cursor-pointer focus:outline-none">
                                <option value="all">Semua Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <input type="text" id="itemSearchInput" placeholder="🔍 Cari nama..." class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-xs flex-1 text-slate-800 focus:outline-none focus:border-violet-500">
                        </div>

                        <select name="item_id" id="itemSelect" class="input-finset cursor-pointer w-full font-bold" required>
                            <option value="" disabled selected>-- Pilih Unit Barang --</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}" data-category="{{ $item->category_id }}" data-name="{{ strtolower($item->name) }}">
                                    [{{ $item->category->name ?? 'Tanpa Kategori' }}] {{ $item->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="w-full lg:col-span-3 flex justify-end pt-2">
                        <button type="submit" class="w-full lg:w-1/3 bg-emerald-600 hover:bg-emerald-500 text-white font-extrabold text-xs py-3.5 rounded-xl shadow-lg shadow-emerald-600/20 transition duration-200 uppercase tracking-wider flex items-center justify-center gap-2 cursor-pointer border-0 h-[45px]">
                            SIMPAN PROMO SLIDER 💾
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="finset-card p-6 shadow-sm w-full overflow-hidden">
            <h4 class="font-extrabold text-base text-slate-900 pb-3 border-b border-slate-100 mb-5">
                📋 Daftar Card Slider Aktif
            </h4>

            <div class="overflow-x-auto rounded-2xl overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-[11px] font-bold uppercase tracking-wider text-slate-400 bg-slate-50">
                            <th class="py-3.5 px-6">Background</th>
                            <th class="py-3.5 px-6">Tag/Badge</th>
                            <th class="py-3.5 px-6">Isi Konten Promo</th>
                            <th class="py-3.5 px-6">Tautan Hasil URL</th>
                            <th class="py-3.5 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs font-semibold text-slate-600 divide-y divide-slate-100">
                        @forelse($promos as $promo)
                            @php
                                preg_match('/\/items\/(\d+)\/checkout/', $promo->link_url, $matches);
                                $current_item_id = $matches[1] ?? '';
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-4 px-6">
                                    @if($promo->image)
                                        <img src="{{ asset('storage/' . $promo->image) }}" class="h-10 w-20 object-cover rounded-xl border border-slate-200 shadow-sm">
                                    @else
                                        <div class="h-10 w-20 bg-slate-100 text-slate-400 text-[10px] rounded-xl flex items-center justify-center font-bold">No Image</div>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-slate-100 text-slate-700 border border-slate-200">
                                        {{ $promo->tag }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 font-bold text-slate-900 leading-normal">
                                    {{ $promo->title }}
                                </td>
                                <td class="py-4 px-6 font-mono text-violet-600 font-bold text-[11px]">
                                    {{ $promo->link_url }}
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button type="button" 
                                                onclick="openEditPromoModal('{{ $promo->id }}', '{{ $promo->tag }}', '{{ $promo->title }}', '{{ $promo->badge_color }}', '{{ $promo->link_text }}', '{{ $current_item_id }}')" 
                                                class="bg-amber-50 hover:bg-amber-100 text-amber-600 px-3 py-1.5 rounded-xl font-bold transition cursor-pointer text-[11px] border-0">
                                            Edit
                                        </button>

                                        <form method="POST" action="{{ route('admin.promos.destroy', $promo->id) }}" onsubmit="return confirm('Hapus card promo ini dari slider depan?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-rose-50 hover:bg-rose-100 text-rose-600 px-3 py-1.5 rounded-xl font-bold transition cursor-pointer text-[11px] border-0">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-slate-400 font-medium">Belum ada data promo dinamis. Slider depan otomatis menggunakan fallback template, Bree!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="editPromoModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-[28px] shadow-2xl border border-slate-100 w-full max-w-xl overflow-hidden transform transition-all p-6">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-5">
                <h3 class="font-extrabold text-base text-slate-900 flex items-center gap-2">
                    <span>✏️</span> Edit Card Slider Promo
                </h3>
                <button type="button" onclick="closeEditPromoModal()" class="text-slate-400 hover:text-slate-600 font-bold border-0 bg-transparent text-lg cursor-pointer">✕</button>
            </div>

            <form id="editPromoForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div class="flex flex-col">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-wide">Nama Tag (Badge)</label>
                        <input type="text" name="tag" id="edit_tag" class="input-finset" required>
                    </div>

                    <div class="flex flex-col">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-wide">Isi Kalimat Promo</label>
                        <input type="text" name="title" id="edit_title" class="input-finset" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex flex-col">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-wide">Warna Tema Badge</label>
                            <select name="badge_color" id="edit_badge_color" class="input-finset cursor-pointer" required>
                                <option value="blue">Biru (Blue)</option>
                                <option value="purple">Ungu (Purple)</option>
                                <option value="emerald">Hijau (Emerald)</option>
                                <option value="amber">Kuning (Amber)</option>
                                <option value="rose">Merah (Rose)</option>
                            </select>
                        </div>

                        <div class="flex flex-col">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-wide">Teks Tombol Tautan</label>
                            <input type="text" name="link_text" id="edit_link_text" class="input-finset" required>
                        </div>
                    </div>

                    <div class="flex flex-col">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-wide">Ganti Foto Background (Kosongkan jika tidak ingin diubah)</label>
                        <input type="file" name="background_image" class="input-finset file:mr-4 file:py-1 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100 cursor-pointer">
                    </div>

                    <div class="flex flex-col">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-wide">Target Unit Barang</label>
                        <select name="item_id" id="edit_item_id" class="input-finset cursor-pointer font-bold" required>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}">
                                    [{{ $item->category->name ?? 'Tanpa Kategori' }}] {{ $item->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex gap-3 justify-end pt-5 mt-5 border-t border-slate-100">
                    <button type="button" onclick="closeEditPromoModal()" class="px-5 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs transition border-0 cursor-pointer">Batal</button>
                    <button type="submit" class="px-5 py-3 rounded-xl bg-violet-600 hover:bg-violet-500 text-white font-bold text-xs shadow-lg shadow-violet-600/20 transition border-0 cursor-pointer">Simpan Perubahan 💾</button>
                </div>
            </form>
        </div>
    </div>

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

        function openEditPromoModal(id, tag, title, badge_color, link_text, item_id) {
            const modal = document.getElementById('editPromoModal');
            const form = document.getElementById('editPromoForm');
            
            form.action = "{{ url('admin/promos') }}/" + id;

            document.getElementById('edit_tag').value = tag;
            document.getElementById('edit_title').value = title;
            document.getElementById('edit_badge_color').value = badge_color;
            document.getElementById('edit_link_text').value = link_text;
            document.getElementById('edit_item_id').value = item_id;

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