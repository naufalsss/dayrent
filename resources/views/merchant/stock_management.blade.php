@extends('merchant.master')

@section('content')
    @if(session('success'))
        <div class="p-4 mb-6 text-xs font-bold text-emerald-600 bg-emerald-50 rounded-2xl border border-emerald-200 flex items-center gap-2 max-w-5xl">
            <span>✨</span> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm w-full max-w-5xl">
        
        <div class="pb-4 border-b border-slate-100 mb-5">
            <h4 class="font-extrabold text-xs text-slate-800 uppercase tracking-wider">Manajemen Stok Barang</h4>
            <p class="text-[11px] text-slate-400 font-medium mt-1">Atur kuantitas sisa unit rental secara real-time khusus barang dagangan toko lu.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 mb-6">
            <div class="sm:col-span-8">
                <input type="text" id="searchBarang" placeholder="Cari nama unit kendaraan..." 
                class="w-full bg-slate-50/50 border border-slate-200/80 rounded-xl px-4 py-2 text-xs text-slate-800 placeholder-slate-400 focus:border-blue-600 focus:bg-white focus:ring-4 focus:ring-blue-600/5 outline-none transition h-[40px]">
            </div>
            <div class="sm:col-span-4">
                <select id="filterKategori" class="w-full bg-slate-50/50 border border-slate-200/80 rounded-xl px-3 py-2 text-xs text-slate-700 focus:border-blue-600 focus:bg-white outline-none transition h-[40px] cursor-pointer">
                    <option value="all">Semua Kategori</option>
                    @foreach($items->unique('category_name') as $cat)
                        <option value="{{ strtolower($cat->category_name) }}">{{ $cat->category_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="overflow-x-auto rounded-xl border border-slate-200/60">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[10px] font-bold uppercase tracking-widest text-slate-400 bg-slate-50 border-b border-slate-200/60">
                        <th class="py-3.5 px-5">Unit Kendaraan</th>
                        <th class="py-3.5 px-5">Kategori</th>
                        <th class="py-3.5 px-5 text-center w-48">Jumlah Stok</th>
                        <th class="py-3.5 px-5 text-center w-40">Aksi Manajemen</th>
                    </tr>
                </thead>
                <tbody id="stockTableBody" class="text-xs font-medium text-slate-600 divide-y divide-slate-100">
                    @forelse($items as $item)
                        <tr class="stock-row hover:bg-slate-50/40 transition duration-100" data-name="{{ strtolower($item->name) }}" data-category="{{ strtolower($item->category_name) }}">
                            <td class="py-3 px-5 flex items-center gap-3">
                                <img src="{{ (str_starts_with($item->image, '/storage/') || str_starts_with($item->image, 'storage/')) ? asset(ltrim($item->image, '/')) : asset('storage/' . $item->image) }}" 
                                     class="w-10 h-10 object-cover rounded-xl border border-slate-100 shadow-sm">
                                <span class="font-bold text-slate-800">{{ $item->name }}</span>
                            </td>
                            <td class="py-3 px-5 text-slate-400 uppercase text-[10px] tracking-wider">{{ $item->category_name }}</td>
                            <td class="py-3 px-5 text-center">
                                <form method="POST" action="{{ route('merchant.stock.update', $item->id) }}" class="flex items-center justify-center gap-2">
                                    @csrf
                                    <input type="number" name="stock" value="{{ $item->stock ?? 0 }}" 
                                    class="w-16 bg-slate-50 border border-slate-200 rounded-lg px-2 py-1 text-center text-xs font-bold text-slate-800 focus:border-blue-600 focus:bg-white outline-none transition" min="0">
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white text-[10px] font-bold uppercase tracking-wider px-3 py-1.5 rounded-lg transition cursor-pointer border-0">
                                        Simpan
                                    </button>
                                </form>
                            </td>
                            <td class="py-3 px-5 text-center">
                                <form method="POST" action="{{ route('admin.stock.delete', $item->id) }}" onsubmit="return confirm('Yakin ingin mengosongkan stok?')">
                                    @csrf
                                    <button type="submit" class="bg-rose-50 hover:bg-rose-100 border border-rose-200/40 text-rose-600 px-3 py-1.5 rounded-lg font-bold transition cursor-pointer text-[10px] uppercase tracking-wider border-0">
                                        Kosongkan
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-16 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <span class="text-3xl opacity-50">📦</span>
                                    <p class="font-extrabold text-slate-700 text-xs tracking-tight uppercase">Belum ada unit barang untuk dikelola stoknya.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    <tr id="noResultsRow" class="hidden">
                        <td colspan="4" class="py-12 text-center text-slate-400 font-bold text-xs uppercase tracking-wide">Data unit tidak ditemukan.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const searchInput = document.getElementById('searchBarang');
            const filterSelect = document.getElementById('filterKategori');
            const rows = document.querySelectorAll('.stock-row');
            const noResultsRow = document.getElementById('noResultsRow');

            function filterTable() {
                const searchValue = searchInput.value.toLowerCase().trim();
                const filterValue = filterSelect.value.toLowerCase();
                let visibleCount = 0;

                rows.forEach(row => {
                    const itemName = row.getAttribute('data-name');
                    const itemCategory = row.getAttribute('data-category');
                    const matchesSearch = itemName.includes(searchValue);
                    const matchesCategory = (filterValue === 'all' || itemCategory === filterValue);

                    if (matchesSearch && matchesCategory) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                noResultsRow.classList.toggle('hidden', visibleCount > 0);
            }

            searchInput.addEventListener('input', filterTable);
            filterSelect.addEventListener('change', filterTable);
        });
    </script>
@endsection